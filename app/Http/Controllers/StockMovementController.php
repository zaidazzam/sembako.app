<?php

namespace App\Http\Controllers;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Menampilkan stok produk dan riwayat pergerakan stok.
     */
    public function index(Request $request): View
    {
        $productsQuery = Product::with('category')
            ->latest('id');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $productsQuery->where(function ($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $productsQuery->where(
                'category_id',
                $request->input('category_id')
            );
        }

        // if ($request->input('stock_status') === 'low') {
        //     $productsQuery->whereHas('stockMovements');
        // }

        $products = $productsQuery
            ->paginate(10)
            ->withQueryString();

        $categories = \App\Models\Category::orderBy('name')->get();

        $movements = StockMovement::with([
            'product.category',
            'createdBy',
        ])
            ->latest('id')
            ->paginate(10, ['*'], 'movements_page')
            ->withQueryString();

        return view('admin.stock-movements.index', compact(
            'products',
            'categories',
            'movements'
        ));
    }

    /**
     * Form tambah pergerakan stok.
     */
    public function create(): View
    {
        $products = Product::active()
            ->with('category')
            ->orderBy('name')
            ->get();

        return view(
            'admin.stock-movements.create',
            compact('products')
        );
    }

    /**
     * Simpan pergerakan stok.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')
                    ->where(fn ($query) => $query->where('status', true)),
            ],

            'type' => [
                'required',
                Rule::in([
                    StockMovementType::IN->value,
                    StockMovementType::OUT->value,
                    StockMovementType::ADJUSTMENT->value,
                ]),
            ],

            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $product = Product::lockForUpdate()
                ->findOrFail($validated['product_id']);

            $type = StockMovementType::from(
                $validated['type']
            );

            $quantity = (float) $validated['quantity'];

            /*
             * OUT tidak boleh melebihi stok tersedia.
             */
            if ($type === StockMovementType::OUT) {

                $currentStock = $product->current_stock;

                if ($quantity > $currentStock) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => "Stok {$product->name} saat ini hanya {$currentStock} {$product->unit}.",
                    ]);
                }
            }

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.stock-movements.index')
            ->with('success', 'Pergerakan stok berhasil dicatat.');
    }

    /**
     * Hapus riwayat stok.
     */
    public function destroy(
        StockMovement $stockMovement
    ): RedirectResponse {

        $stockMovement->delete();

        return redirect()
            ->route('admin.stock-movements.index')
            ->with('success', 'Riwayat stok berhasil dihapus.');
    }
}
