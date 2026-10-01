<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category')
            ->latest('id');

        // Search kode / nama produk
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status') === 'active'
            );
        }

        $products = $query
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact(
            'products',
            'categories'
        ));
    }

    /**
     * Form tambah produk.
     */
    public function create(): View
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query->where('status', true)),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:products,code',
            ],
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'unit' => [
                'required',
                'string',
                'max:30',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk.
     */
    public function edit(Product $product): View
    {
        $categories = Category::where(function ($query) use ($product) {
            $query->where('status', true)
                ->orWhere('id', $product->category_id);
        })
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact(
            'product',
            'categories'
        ));
    }

    /**
     * Update produk.
     */
    public function update(
        Request $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id'),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'code')
                    ->ignore($product->id),
            ],
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'unit' => [
                'required',
                'string',
                'max:30',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orderItems()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with(
                    'error',
                    'Produk tidak dapat dihapus karena sudah digunakan pada transaksi/kebutuhan. Silakan nonaktifkan produk.'
                );
        }

        if ($product->stockMovements()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with(
                    'error',
                    'Produk tidak dapat dihapus karena sudah memiliki riwayat stok. Silakan nonaktifkan produk.'
                );
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
