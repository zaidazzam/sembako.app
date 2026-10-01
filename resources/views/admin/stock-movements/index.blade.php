<x-app-layout>

    <div class="page-header">
        <div>
            <h1>Stok Produk</h1>
            <p>Kelola stok dan lihat riwayat pergerakan stok.</p>
        </div>

        <a href="{{ route('admin.stock-movements.create') }}" class="btn-primary">
            + Tambah Stok
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

    {{-- FILTER --}}
    <div class="card">

        <form method="GET" action="{{ route('admin.stock-movements.index') }}" class="filter-form">

            <div class="filter-group">
                <label for="search">
                    Cari Produk
                </label>

                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Kode atau nama produk...">
            </div>

            <div class="filter-group">
                <label for="category_id">
                    Kategori
                </label>

                <select name="category_id" id="category_id">
                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">

                <button type="submit" class="btn-primary">
                    Filter
                </button>

                <a href="{{ route('admin.stock-movements.index') }}" class="btn-secondary">
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- CURRENT STOCK --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Stok Saat Ini</h2>
                <p>Jumlah stok berdasarkan seluruh pergerakan stok.</p>
            </div>
        </div>

        <div class="table-wrapper">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Minimum</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($products as $product)
                        <tr>

                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $product->code }}
                                </strong>
                            </td>

                            <td>
                                {{ $product->name }}
                            </td>

                            <td>
                                {{ $product->category?->name ?? '-' }}
                            </td>

                            <td>
                                <strong>
                                    {{ $product->current_stock }}
                                    {{ $product->unit }}
                                </strong>
                            </td>

                            <td>
                                {{ $product->minimum_stock }}
                                {{ $product->unit }}
                            </td>

                            <td>

                                @if ($product->is_low_stock)
                                    <span class="badge badge-danger">
                                        Stok Menipis
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        Aman
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="empty-state">
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

    {{-- RIWAYAT --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Riwayat Pergerakan Stok</h2>
                <p>Daftar aktivitas stok terbaru.</p>
            </div>
        </div>

        <div class="table-wrapper">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Tipe</th>
                        <th>Quantity</th>
                        <th>Dicatat Oleh</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($movements as $movement)
                        <tr>

                            <td>
                                {{ $movements->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ $movement->created_at?->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <strong>
                                    {{ $movement->product?->code }}
                                </strong>

                                <br>

                                <span class="text-muted">
                                    {{ $movement->product?->name }}
                                </span>
                            </td>

                            <td>

                                @if ($movement->type->value === 'in')
                                    <span class="badge badge-success">
                                        Stok Masuk
                                    </span>
                                @elseif ($movement->type->value === 'out')
                                    <span class="badge badge-danger">
                                        Stok Keluar
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        Adjustment
                                    </span>
                                @endif

                            </td>

                            <td>
                                {{ number_format($movement->quantity, 0, ',', '.') }}
                                 {{ $movement->product?->unit }}
                            </td>

                            <td>
                                {{ $movement->createdBy?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $movement->notes ?: '-' }}
                            </td>

                            <td>

                                <form method="POST" action="{{ route('admin.stock-movements.destroy', $movement) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus riwayat stok ini? Stok akan berubah kembali.')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-small btn-delete">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="empty-state">
                                Belum ada riwayat stok.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($movements->hasPages())
            <div class="pagination-wrapper">
                {{ $movements->links('pagination::tailwind') }}
            </div>
        @endif

    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-header h2 {
            margin: 0 0 4px;
            font-size: 17px;
        }

        .card-header p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
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

        .filter-actions {
            display: flex;
            gap: 8px;
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

        .badge {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
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

        .btn-small {
            border: none;
            padding: 7px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .text-muted {
            color: #94a3b8;
            font-size: 12px;
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
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 800px) {
            .filter-form {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>

</x-app-layout>
