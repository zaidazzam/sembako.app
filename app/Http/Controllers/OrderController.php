<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Exports\OrderRecapExport;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warung;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Order::with([
            'warung',
            'createdBy',
            'items.product',
        ])
            ->latest('order_date')
            ->latest('id');

        /*
        |--------------------------------------------------------------------------
        | PETUGAS
        |--------------------------------------------------------------------------
        | Hanya bisa melihat kebutuhan dari warung yang ditugaskan.
        */
        if ($user->isPetugas()) {
            $warungIds = $user->assignedWarungs()
                ->pluck('warungs.id');

            $query->whereIn('warung_id', $warungIds);
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | WARUNG YANG BISA DIPILIH
        |--------------------------------------------------------------------------
        */

        if ($user->isAdmin()) {

            $warungs = Warung::where('status', true)
                ->orderBy('name')
                ->get();

        } else {

            $warungs = $user->assignedWarungs()
                ->where('warungs.status', true)
                ->orderBy('warungs.name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUK
        |--------------------------------------------------------------------------
        */

        $products = Product::with('category')
            ->active()
            ->orderBy('name')
            ->get();

        return view('orders.create', compact(
            'warungs',
            'products'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
$validated = $request->validate([
    'warung_id' => ['required', 'integer', 'exists:warungs,id'],
    'order_date' => ['required', 'date'],
    'notes' => ['nullable', 'string'],

    'items' => ['required', 'array', 'min:1'],

    'items.*.product_id' => [
        'required',
        'integer',
        'exists:products,id',
        'distinct',
    ],

    'items.*.quantity' => [
        'required',
        'numeric',
        'gt:0',
    ],

    'items.*.notes' => [
        'nullable',
        'string',
    ],
]);

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | SECURITY PETUGAS
        |--------------------------------------------------------------------------
        */

        if ($user->isPetugas()) {

            $isAssigned = $user->assignedWarungs()
                ->where('warungs.id', $validated['warung_id'])
                ->exists();

            abort_unless($isAssigned, 403);
        }

        $warung = Warung::findOrFail(
            $validated['warung_id']
        );

        $order = DB::transaction(function () use (
            $validated,
            $user,
            $warung
        ) {

            $order = Order::create([
                'order_number' => $this->generateOrderNumber($warung),

                'warung_id' => $warung->id,

                'created_by' => $user->id,

                'status' => OrderStatus::SUBMITTED,

                'order_date' => $validated['order_date'],

                'notes' => $validated['notes'] ?? null,

                'submitted_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {

                $product = Product::findOrFail(
                    $item['product_id']
                );

                $order->items()->create([
                    'product_id' => $product->id,

                    'quantity' => $item['quantity'],

                    /*
                    | Snapshot unit saat kebutuhan dicatat
                    */
                    'unit' => $product->unit,

                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return $order;
        });

        return redirect()
            ->route('orders.show', $order)
            ->with(
                'success',
                'Kebutuhan warung berhasil dicatat.'
            );
    }

    public function show(Order $order): View
    {
        $this->authorizeOrderAccess($order);

        $order->load([
            'warung',
            'createdBy',
            'items.product.category',
        ]);

        return view(
            'orders.show',
            compact('order')
        );
    }

    private function authorizeOrderAccess(Order $order): void
    {
        $user = request()->user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->isAdmin()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PETUGAS
        |--------------------------------------------------------------------------
        */

        $allowed = $user->assignedWarungs()
            ->where(
                'warungs.id',
                $order->warung_id
            )
            ->exists();

        abort_unless($allowed, 403);
    }

    private function generateOrderNumber(Warung $warung): string
    {
        return 'KB-'
            . now()->format('YmdHis')
            . '-'
            . $warung->code;
    }

    public function edit(Order $order): View
{
    $this->authorizeOrderAccess($order);

    $order->load([
        'warung',
        'items.product.category',
    ]);

    $products = Product::with('category')
        ->active()
        ->orderBy('name')
        ->get();

    return view('orders.edit', compact(
        'order',
        'products'
    ));
}


public function update(Request $request, Order $order): RedirectResponse
{
    $this->authorizeOrderAccess($order);

    $validated = $request->validate([
        'order_date' => [
            'required',
            'date',
        ],

        'notes' => [
            'nullable',
            'string',
        ],

        'items' => [
            'required',
            'array',
            'min:1',
        ],

        'items.*.product_id' => [
            'required',
            'integer',
            'distinct',
            Rule::exists('products', 'id')
                ->where(fn ($query) => $query->where('status', true)),
        ],

        'items.*.quantity' => [
            'required',
            'numeric',
            'gt:0',
        ],

        'items.*.notes' => [
            'nullable',
            'string',
        ],
    ]);

    DB::transaction(function () use ($validated, $order) {

        $order->update([
            'order_date' => $validated['order_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hapus item lama
        |--------------------------------------------------------------------------
        */

        $order->items()->delete();


        /*
        |--------------------------------------------------------------------------
        | Simpan item baru
        |--------------------------------------------------------------------------
        */

        foreach ($validated['items'] as $item) {

            $product = Product::active()
                ->findOrFail($item['product_id']);

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit' => $product->unit,
                'notes' => $item['notes'] ?? null,
            ]);
        }
    });

    return redirect()
        ->route('orders.show', $order)
        ->with('success', 'Kebutuhan berhasil diperbarui.');
}
public function destroy(Order $order): RedirectResponse
{
    $this->authorizeOrderAccess($order);

    DB::transaction(function () use ($order) {
        // Hapus item kebutuhan terlebih dahulu
        $order->items()->delete();

        // Hapus order
        $order->delete();
    });

    return redirect()
        ->route('orders.index')
        ->with('success', 'Kebutuhan berhasil dihapus.');
}

public function recap(Request $request): View
{
    $user = $request->user();

    $recap = $this->getRecapData($request);

    $totalProducts = $recap->count();

    $totalQuantity = $recap->sum('total_quantity');

    $totalPrice = $recap->sum('total_price');

    $ordersQuery = Order::query()
        ->whereIn('status', [
            OrderStatus::SUBMITTED->value,
            OrderStatus::PROCESSING->value,
            OrderStatus::READY->value,
        ]);

    if ($user->isPetugas()) {

        $warungIds = $user->assignedWarungs()
            ->pluck('warungs.id');

        $ordersQuery->whereIn('warung_id', $warungIds);
    }

    if ($request->filled('date_from')) {

        $ordersQuery->whereDate(
            'order_date',
            '>=',
            $request->date_from
        );
    }

    if ($request->filled('date_to')) {

        $ordersQuery->whereDate(
            'order_date',
            '<=',
            $request->date_to
        );
    }

    $orders = $ordersQuery->get();

    $totalWarungs = $orders
        ->pluck('warung_id')
        ->unique()
        ->count();

    return view('orders.recap', compact(
        'recap',
        'orders',
        'totalProducts',
        'totalQuantity',
        'totalPrice',
        'totalWarungs'
    ));
}
public function exportRecap(Request $request)
{
    $recap = $this->getRecapData($request);

    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');

    /*
    |--------------------------------------------------------------------------
    | TOTAL WARUNG
    |--------------------------------------------------------------------------
    */

    $user = $request->user();

    if ($user->isAdmin()) {
        $totalWarungs = \App\Models\Warung::where('status', true)
            ->count();
    } else {
        $totalWarungs = $user->assignedWarungs()
            ->where('warungs.status', true)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL PRODUK
    |--------------------------------------------------------------------------
    */

    $totalProducts = $recap->count();

    /*
    |--------------------------------------------------------------------------
    | TOTAL NILAI
    |--------------------------------------------------------------------------
    */

    $totalPrice = (float) $recap->sum('total_price');

    /*
    |--------------------------------------------------------------------------
    | NAMA FILE
    |--------------------------------------------------------------------------
    */

    $filename = 'rekap-kebutuhan';

    if ($dateFrom && $dateTo) {
        $filename .= "-{$dateFrom}-sampai-{$dateTo}";
    } elseif ($dateFrom) {
        $filename .= "-mulai-{$dateFrom}";
    } elseif ($dateTo) {
        $filename .= "-sampai-{$dateTo}";
    } else {
        $filename .= '-' . now()->format('Y-m-d');
    }

    return Excel::download(
        new \App\Exports\OrderRecapExport(
            $recap,
            $dateFrom,
            $dateTo,
            $totalWarungs,
            $totalProducts,
            $totalPrice
        ),
        $filename . '.xlsx'
    );
}

public function exportRecapPdf(Request $request)
{
    $recap = $this->getRecapData($request);

    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');

    $totalQuantity = $recap->sum('total_quantity');
    $totalPrice = $recap->sum('total_price');

    $pdf = Pdf::loadView('orders.recap-pdf', [
        'recap' => $recap,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'totalQuantity' => $totalQuantity,
        'totalPrice' => $totalPrice,
    ]);

    $pdf->setPaper('A4', 'landscape');

    $filename = 'rekap-kebutuhan';

    if ($dateFrom && $dateTo) {
        $filename .= "-{$dateFrom}-sampai-{$dateTo}";
    } elseif ($dateFrom) {
        $filename .= "-mulai-{$dateFrom}";
    } elseif ($dateTo) {
        $filename .= "-sampai-{$dateTo}";
    } else {
        $filename .= '-' . now()->format('Y-m-d');
    }

    return $pdf->download($filename . '.pdf');
}

private function getRecapData(Request $request)
{
    $user = $request->user();

    $ordersQuery = Order::query()
        ->with([
            'warung',
            'items.product.category',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Filter status
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $ordersQuery->where(
            'status',
            $request->status
        );

    } else {

        // Default: hanya kebutuhan yang masih aktif
        $ordersQuery->whereIn('status', [
            OrderStatus::SUBMITTED->value,
            OrderStatus::PROCESSING->value,
            OrderStatus::READY->value,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Petugas hanya melihat warung yang ditugaskan
    |--------------------------------------------------------------------------
    */

    if ($user->isPetugas()) {

        $warungIds = $user->assignedWarungs()
            ->pluck('warungs.id');

        $ordersQuery->whereIn(
            'warung_id',
            $warungIds
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Filter tanggal
    |--------------------------------------------------------------------------
    */

    if ($request->filled('date_from')) {

        $ordersQuery->whereDate(
            'order_date',
            '>=',
            $request->date_from
        );
    }

    if ($request->filled('date_to')) {

        $ordersQuery->whereDate(
            'order_date',
            '<=',
            $request->date_to
        );
    }

    $orders = $ordersQuery
        ->orderBy('order_date')
        ->orderBy('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Rekap Produk
    |--------------------------------------------------------------------------
    */

    $recap = [];

    foreach ($orders as $order) {

        foreach ($order->items as $item) {

            $product = $item->product;

            if (! $product) {
                continue;
            }

            $productId = $product->id;

            if (! isset($recap[$productId])) {

                $recap[$productId] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'category_name' => $product->category?->name ?? '-',
                    'unit' => $item->unit,
                    'total_quantity' => 0,
                    'total_price' => 0,
                    'warung_ids' => [],
                    'warung_details' => [],
                ];
            }

            $quantity = (float) $item->quantity;

            $recap[$productId]['total_quantity'] += $quantity;

            $recap[$productId]['total_price'] +=
                $quantity * (float) $product->price;

            $warungId = $order->warung_id;

            /*
            |--------------------------------------------------------------------------
            | Warung pertama kali membutuhkan produk
            |--------------------------------------------------------------------------
            */

            if (! in_array(
                $warungId,
                $recap[$productId]['warung_ids']
            )) {

                $recap[$productId]['warung_ids'][] = $warungId;

                $recap[$productId]['warung_details'][] = [
                    'warung_id' => $warungId,
                    'warung_name' => $order->warung->name,
                    'quantity' => $quantity,
                ];

            } else {

                /*
                |--------------------------------------------------------------------------
                | Jika warung memiliki lebih dari satu order
                |--------------------------------------------------------------------------
                */

                foreach (
                    $recap[$productId]['warung_details']
                    as &$warungDetail
                ) {

                    if (
                        $warungDetail['warung_id']
                        == $warungId
                    ) {

                        $warungDetail['quantity'] += $quantity;

                        break;
                    }
                }

                unset($warungDetail);
            }
        }
    }

    return collect($recap)
        ->sortBy('product_name')
        ->values();
}

    public function updateStatus(
    Request $request,
    Order $order
): RedirectResponse {

    $this->authorizeOrderAccess($order);

    $validated = $request->validate([
        'status' => [
            'required',
            'in:submitted,processing,ready,completed,cancelled',
        ],
    ]);

    $newStatus = OrderStatus::from(
        $validated['status']
    );

    $updateData = [
        'status' => $newStatus,
    ];

    /*
    |--------------------------------------------------------------------------
    | TIMESTAMP STATUS
    |--------------------------------------------------------------------------
    */

    if ($newStatus === OrderStatus::PROCESSING) {
        $updateData['processed_at'] = now();
    }

    if ($newStatus === OrderStatus::COMPLETED) {
        $updateData['completed_at'] = now();
    }

    $order->update($updateData);

    return redirect()
        ->route('orders.show', $order)
        ->with(
            'success',
            'Status kebutuhan berhasil diperbarui.'
        );
}
}
