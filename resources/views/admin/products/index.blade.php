<x-app-layout>
    <div class="page-header">
        <div>
            <h1>Data Produk</h1>
            <p>Kelola data produk sembako yang tersedia.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            + Tambah Produk
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <form method="GET" action="{{ route('admin.products.index') }}" class="filter-form">

            <div class="filter-group">
                <label for="search">Cari Produk</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Kode atau nama produk..."
                >
            </div>

            <div class="filter-group">
                <label for="category_id">Kategori</label>

                <select name="category_id" id="category_id">
                    <option value="">Semua Kategori</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected(request('category_id') == $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="status">Status</label>

                <select name="status" id="status">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')}>
                        Aktif
                    </option>
                    <option value="inactive" @selected(request('status') === 'inactive')}>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-primary">
                    Filter
                </button>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="btn-secondary"
                >
                    Reset
                </a>
            </div>

        </form>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Min. Stok</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($products as $product)

                        <tr>
                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>{{ $product->code }}</strong>
                            </td>

                            <td>
                                {{ $product->name }}
                            </td>

                            <td>
                                {{ $product->category?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $product->unit }}
                            </td>

                            <td>
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ rtrim(rtrim(number_format($product->minimum_stock, 2, ',', '.'), '0'), ',') }}
                                {{ $product->unit }}
                            </td>

                            <td>
                                @if ($product->status)
                                    <span class="badge badge-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="action-buttons">

                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="btn-small btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-small btn-delete"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="empty-state">
                                Belum ada data produk.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if ($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0 0 5px;
            font-size: 26px;
            font-weight: 700;
        }

        .page-header p {
            margin: 0;
            color: #64748b;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 15px;
            padding: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .filter-group input,
        .filter-group select {
            height: 40px;
            padding: 0 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            outline: none;
            background: #fff;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            border-color: #2563eb;
        }

        .filter-actions {
            display: flex;
            gap: 8px;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            padding: 0 16px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 14px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 13px;
            color: #475569;
            white-space: nowrap;
        }

        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #334155;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        .action-buttons form {
            margin: 0;
        }

        .btn-small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            padding: 0 10px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .empty-state {
            text-align: center !important;
            padding: 40px !important;
            color: #94a3b8 !important;
        }

        .pagination-wrapper {
            padding: 16px 20px;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 900px) {
            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 600px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-layout>
