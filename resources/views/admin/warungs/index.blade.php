<x-app-layout>

    @section('title', 'Data Warung')
    @section('page-title', 'Data Warung')

    <div class="warung-page">

        {{-- Header --}}
        <div class="page-header">

            <div>
                <div class="breadcrumb">
                    Master Data
                    <span>/</span>
                    Warung
                </div>

                <h2>Data Warung</h2>

                <p>
                    Kelola data warung dan akun pemilik warung.
                </p>
            </div>

            <a
                href="{{ route('admin.warungs.create') }}"
                class="btn-primary"
            >
                + Tambah Warung
            </a>

        </div>


        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif


        {{-- Filter --}}
        <div class="filter-card">

            <form
                method="GET"
                action="{{ route('admin.warungs.index') }}"
                class="filter-form"
            >

                <div class="filter-group search-group">

                    <label for="search">
                        Cari Warung
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama, kode, pemilik..."
                        class="form-control"
                    >

                </div>


                <div class="filter-group">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="form-control"
                    >
                        <option value="">
                            Semua Status
                        </option>

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

                </div>


                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        🔍 Cari
                    </button>

                    <a
                        href="{{ route('admin.warungs.index') }}"
                        class="btn-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>


        {{-- Table --}}
        <div class="data-card">

            <div class="data-card-header">

                <div>
                    <h3>Daftar Warung</h3>

                    <p>
                        Total {{ $warungs->total() }} warung
                    </p>
                </div>

            </div>


            <div class="table-wrapper">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>WARUNG</th>
                            <th>PEMILIK</th>
                            <th>KONTAK</th>
                            <th>KEBUTUHAN</th>
                            <th>STATUS</th>
                            <th width="150">AKSI</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($warungs as $index => $warung)

                            <tr>

                                <td>
                                    {{ $warungs->firstItem() + $index }}
                                </td>

                                <td>
                                    <div class="warung-name">
                                        {{ $warung->name }}
                                    </div>

                                    <div class="warung-code">
                                        {{ $warung->code }}
                                    </div>
                                </td>

                                <td>
                                    <div class="owner-name">
                                        {{ $warung->user->name }}
                                    </div>

                                    <div class="owner-email">
                                        {{ $warung->user->email }}
                                    </div>
                                </td>

                                <td>
                                    {{ $warung->phone ?: '-' }}
                                </td>

                                <td>
                                    {{ $warung->orders_count }}
                                </td>

                                <td>

                                    @if($warung->status)

                                        <span class="status-badge status-active">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">
                                            Nonaktif
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.warungs.edit', $warung) }}"
                                            class="btn-edit"
                                        >
                                            ✎ Edit
                                        </a>

                                        @if($warung->orders_count === 0)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.warungs.destroy', $warung) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus warung ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn-delete"
                                                >
                                                    🗑
                                                </button>
                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="empty-table"
                                >
                                    <div class="empty-icon">
                                        🏪
                                    </div>

                                    <strong>
                                        Belum ada data warung
                                    </strong>

                                    <p>
                                        Silakan tambahkan warung baru.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($warungs->hasPages())

                <div class="pagination-wrapper">
                    {{ $warungs->links() }}
                </div>

            @endif

        </div>

    </div>


    <style>

        .warung-page {
            padding: 0;
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


        /* BUTTON */

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
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }


        /* ALERT */

        .alert {
            margin-bottom: 15px;
            padding: 11px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .alert-success {
            color: #166534;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }


        /* FILTER */

        .filter-card {
            margin-bottom: 15px;
            padding: 15px;

            background: #ffffff;
            border: 1px solid #dfe3e8;
            border-radius: 8px;
        }

        .filter-form {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-group {
            width: 220px;
        }

        .search-group {
            width: 300px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;

            font-size: 11px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            width: 100%;
            height: 38px;
            padding: 0 11px;

            border: 1px solid #cfd6df;
            border-radius: 6px;

            background: #ffffff;
            color: #374151;

            font-size: 12px;
            outline: none;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, .1);
        }

        .filter-actions {
            display: flex;
            gap: 7px;
        }


        /* CARD */

        .data-card {
            background: #ffffff;
            border: 1px solid #dfe3e8;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-card-header {
            padding: 15px 17px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-card-header h3 {
            margin: 0;
            font-size: 14px;
            color: #111827;
        }

        .data-card-header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #94a3b8;
        }


        /* TABLE */

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 11px 14px;

            text-align: left;

            font-size: 10px;
            font-weight: 700;

            color: #64748b;
            background: #f8fafc;

            border-bottom: 1px solid #e5e7eb;
        }

        .data-table td {
            padding: 12px 14px;

            font-size: 12px;
            color: #374151;

            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .warung-name,
        .owner-name {
            font-weight: 600;
            color: #111827;
        }

        .warung-code,
        .owner-email {
            margin-top: 3px;
            font-size: 10px;
            color: #94a3b8;
        }


        /* STATUS */

        .status-badge {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 5px;

            font-size: 10px;
            font-weight: 600;
        }

        .status-active {
            color: #166534;
            background: #dcfce7;
        }

        .status-inactive {
            color: #991b1b;
            background: #fee2e2;
        }


        /* ACTION */

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-edit {
            height: 32px;
            padding: 0 10px;

            display: inline-flex;
            align-items: center;

            border: 1px solid #bfdbfe;
            border-radius: 5px;

            color: #2563eb;
            background: #eff6ff;

            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-edit:hover {
            background: #dbeafe;
        }

        .btn-delete {
            width: 32px;
            height: 32px;

            border: 1px solid #fecaca;
            border-radius: 5px;

            color: #dc2626;
            background: #fef2f2;

            cursor: pointer;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }


        /* EMPTY */

        .empty-table {
            padding: 50px 20px !important;
            text-align: center;
            color: #94a3b8 !important;
        }

        .empty-icon {
            margin-bottom: 8px;
            font-size: 30px;
        }

        .empty-table strong {
            display: block;
            color: #374151;
            font-size: 13px;
        }

        .empty-table p {
            margin: 4px 0 0;
            font-size: 11px;
        }


        /* PAGINATION */

        .pagination-wrapper {
            padding: 14px 17px;
        }


        /* RESPONSIVE */

        @media (max-width: 700px) {

            .page-header {
                align-items: stretch;
                flex-direction: column;
            }

            .page-header .btn-primary {
                width: 100%;
            }

            .filter-group,
            .search-group {
                width: 100%;
            }

            .filter-actions {
                width: 100%;
            }

            .filter-actions .btn-primary,
            .filter-actions .btn-secondary {
                flex: 1;
            }

            .data-table {
                min-width: 900px;
            }
        }

    </style>

</x-app-layout>
