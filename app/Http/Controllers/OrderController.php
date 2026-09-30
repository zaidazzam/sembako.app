<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warung;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
            'warung_id' => [
                'required',
                'integer',
                'exists:warungs,id',
            ],

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
                'exists:products,id',
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
}
