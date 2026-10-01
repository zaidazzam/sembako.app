<x-app-layout>

    @section('title', 'Kategori Produk')
    @section('page-title', 'Kategori Produk')

    <div class="page-container">

        <div class="page-header">
            <div>
                <div class="breadcrumb">
                    Data Master <span>/</span> Kategori Produk
                </div>

                <h2>Kategori Produk</h2>

                <p>Kelola kategori produk sembako.</p>
            </div>

            <a
                href="{{ route('admin.categories.create') }}"
                class="btn-primary"
            >
                + Tambah Kategori
            </a>
        </div>


        {{-- FILTER --}}

        <div class="filter-card">

            <form
                method="GET"
                action="{{ route('admin.categories.index') }}"
                class="filter-form"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Cari nama kategori..."
                >

                <select
                    name="status"
                    class="form-control"
                >
                    <option value="">Semua Status</option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Aktif
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Nonaktif
                    </option>
                </select>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Cari
                </button>

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="btn-secondary"
                >
                    Reset
                </a>

            </form>

        </div>


        {{-- TABLE --}}

        <div class="table-card">

            <div class="table-wrapper">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Produk</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($categories as $index => $category)

                            <tr>

                                <td>
                                    {{ $categories->firstItem() + $index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $category->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $category->description ?: '-' }}
                                </td>

                                <td>
                                    {{ $category->products_count }} produk
                                </td>

                                <td>

                                    @if($category->status)

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
                                            href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn-edit"
                                        >
                                            Edit
                                        </a>

                                        @if($category->products_count === 0)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.categories.destroy', $category) }}"
                                                onsubmit="return confirm('Hapus kategori ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn-delete"
                                                >
                                                    Hapus
                                                </button>
                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="empty-state">
                                    Belum ada kategori.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="pagination-wrapper">
                {{ $categories->links() }}
            </div>

        </div>

    </div>

    <style>
        .page-container {
            width: 100%;
        }

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .breadcrumb {
            margin-bottom: 7px;
            font-size: 11px;
            color: #64748b;
        }

        .breadcrumb span {
            margin: 0 6px;
        }

        .page-header h2 {
            margin: 0;
            font-size: 23px;
            color: #111827;
        }

        .page-header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .btn-primary,
        .btn-secondary {
            height: 38px;
            padding: 0 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            border: 1px solid #2563eb;
            background: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
        }

        .filter-card,
        .table-card {
            background: #fff;
            border: 1px solid #dfe3e8;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .filter-card {
            padding: 14px;
        }

        .filter-form {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .form-control {
            height: 38px;
            padding: 0 11px;
            border: 1px solid #cfd6df;
            border-radius: 6px;
            background: #fff;
            color: #374151;
            font-size: 12px;
            outline: none;
        }

        .filter-form .form-control:first-child {
            min-width: 260px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .data-table th {
            padding: 12px 14px;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-flex;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-success {
            background: #ecfdf5;
            color: #047857;
        }

        .badge-danger {
            background: #fef2f2;
            color: #b91c1c;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-edit,
        .btn-delete {
            height: 30px;
            padding: 0 9px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-delete {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
        }

        .empty-state {
            padding: 40px !important;
            text-align: center;
            color: #94a3b8 !important;
        }

        .pagination-wrapper {
            padding: 12px 14px;
        }

        @media (max-width: 700px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-header .btn-primary {
                width: 100%;
            }

            .filter-form {
                flex-direction: column;
            }

            .filter-form .form-control,
            .filter-form .btn-primary,
            .filter-form .btn-secondary {
                width: 100%;
            }
        }
    </style>

</x-app-layout>
