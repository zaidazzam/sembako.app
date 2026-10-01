<x-app-layout>

    @section('title', 'Rekap Kebutuhan')
    @section('page-title', 'Rekap Kebutuhan')


    <div class="recap-page">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="page-header">

            <div>

                <div class="breadcrumb">
                    Kebutuhan
                    <span>/</span>
                    Rekap Kebutuhan
                </div>

                <h2>
                    Rekap Kebutuhan
                </h2>

                <p>
                    Gabungan kebutuhan barang dari seluruh warung.
                </p>

            </div>


            <div class="header-actions">

                <a href="{{ route('orders.index') }}" class="btn-secondary">
                    ← Daftar Kebutuhan
                </a>

            </div>

        </div>



        {{-- =====================================================
             FILTER
        ====================================================== --}}

        {{-- =====================================================
     FILTER
====================================================== --}}

        <div class="filter-card">

            <form method="GET" action="{{ route('orders.recap') }}" class="filter-form">

                {{-- DARI TANGGAL --}}
                <div class="filter-group">

                    <label for="date_from">
                        Dari Tanggal
                    </label>

                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="form-control">

                </div>


                {{-- SAMPAI TANGGAL --}}
                <div class="filter-group">

                    <label for="date_to">
                        Sampai Tanggal
                    </label>

                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="form-control">

                </div>


                {{-- STATUS --}}
                <div class="filter-group">

                    <label for="status">
                        Status
                    </label>

                    <select name="status" id="status" class="form-control">

                        <option value="" {{ !request()->filled('status') ? 'selected' : '' }}>
                            Status Aktif
                        </option>

                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>
                            Menunggu Diproses
                        </option>

                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>
                            Diproses
                        </option>

                        <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>
                            Siap
                        </option>

                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                            Selesai
                        </option>

                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                            Dibatalkan
                        </option>

                    </select>

                </div>


                {{-- ACTION --}}
                <div class="filter-actions">

                    <button type="submit" class="btn-primary">
                        🔍 Tampilkan
                    </button>

                    <a href="{{ route('orders.recap') }}" class="btn-secondary">
                        Reset
                    </a>

                    <button type="submit" formaction="{{ route('orders.recap.export') }}" formmethod="GET"
                        class="btn-download btn-download-excel">
                        <span class="download-icon">↓</span>
                        <span>Excel</span>
                    </button>

                    <button type="submit" formaction="{{ route('orders.recap.pdf') }}" formmethod="GET"
                        class="btn-download btn-download-pdf">
                        <span class="download-icon">↓</span>
                        <span>PDF</span>
                    </button>

                </div>

            </form>

        </div>



        {{-- =====================================================
             SUMMARY
        ====================================================== --}}

        <div class="summary-grid">


            {{-- WARUNG --}}

            <div class="summary-card">

                <div class="summary-icon">
                    🏪
                </div>

                <div>

                    <div class="summary-label">
                        Warung
                    </div>

                    <div class="summary-value">
                        {{ $totalWarungs }}
                    </div>

                    <div class="summary-description">
                        warung membutuhkan
                    </div>

                </div>

            </div>


            {{-- PRODUK --}}

            <div class="summary-card">

                <div class="summary-icon">
                    📦
                </div>

                <div>

                    <div class="summary-label">
                        Jenis Produk
                    </div>

                    <div class="summary-value">
                        {{ $totalProducts }}
                    </div>

                    <div class="summary-description">
                        jenis produk
                    </div>

                </div>

            </div>


            {{-- QUANTITY --}}

            <div class="summary-card">

                <div class="summary-icon">
                    🛒
                </div>

                <div>

                    <div class="summary-label">
                        Total Barang
                    </div>

                    <div class="summary-value">
                        {{ rtrim(rtrim(number_format($totalQuantity, 3, ',', '.'), '0'), ',') }}
                    </div>

                    <div class="summary-description">
                        total kebutuhan
                    </div>

                </div>

            </div>


            {{-- TOTAL HARGA --}}

            <div class="summary-card">

                <div class="summary-icon">
                    💰
                </div>

                <div>

                    <div class="summary-label">
                        Total Nilai
                    </div>

                    <div class="summary-value price">
                        Rp {{ number_format($totalPrice, 0, ',', '.') }}
                    </div>

                    <div class="summary-description">
                        estimasi total kebutuhan
                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             REKAP TABLE
        ====================================================== --}}

        <div class="recap-card">

            <div class="recap-card-header">

                <div>

                    <h3>
                        Rekap Semua Kebutuhan
                    </h3>

                    <p>
                        Produk yang sama dari setiap warung
                        dijumlahkan menjadi satu.
                    </p>

                </div>

            </div>


            <div class="table-wrapper">

                <table class="recap-table">

                    <thead>

                        <tr>

                            <th width="50">
                                #
                            </th>

                            <th>
                                PRODUK
                            </th>

                            <th>
                                KATEGORI
                            </th>

                            <th>
                                WARUNG
                            </th>

                            <th>
                                TOTAL KEBUTUHAN
                            </th>

                            <th>
                                HARGA SATUAN
                            </th>

                            <th>
                                TOTAL NILAI
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recap as $index => $item)

                            <tr>

                                {{-- NO --}}

                                <td class="text-center">

                                    {{ $index + 1 }}

                                </td>


                                {{-- PRODUK --}}

                                <td>

                                    <div class="product-name">

                                        {{ $item['product_name'] }}

                                    </div>

                                    <div class="product-code">

                                        {{ $item['product_code'] }}

                                    </div>

                                </td>


                                {{-- KATEGORI --}}

                                <td>

                                    <span class="category-badge">

                                        {{ $item['category_name'] }}

                                    </span>

                                </td>


                                {{-- WARUNG --}}

                                <td>

                                    <div class="warung-count">

                                        {{ count($item['warung_details']) }}
                                        warung

                                    </div>


                                    <div class="warung-list">

                                        @foreach ($item['warung_details'] as $warung)
                                            <div class="warung-item">

                                                <span>
                                                    {{ $warung['warung_name'] }}
                                                </span>

                                                <strong>
                                                    {{ rtrim(rtrim(number_format($warung['quantity'], 3, ',', '.'), '0'), ',') }}
                                                    {{ $item['unit'] }}
                                                </strong>

                                            </div>
                                        @endforeach

                                    </div>

                                </td>


                                {{-- TOTAL QUANTITY --}}

                                <td>

                                    <div class="quantity-total">

                                        {{ rtrim(rtrim(number_format($item['total_quantity'], 3, ',', '.'), '0'), ',') }}

                                        <span>
                                            {{ $item['unit'] }}
                                        </span>

                                    </div>

                                </td>


                                {{-- HARGA SATUAN --}}

                                <td>

                                    Rp
                                    {{ number_format($item['total_price'] / max($item['total_quantity'], 1), 0, ',', '.') }}

                                </td>


                                {{-- TOTAL NILAI --}}

                                <td>

                                    <div class="price-total">

                                        Rp
                                        {{ number_format($item['total_price'], 0, ',', '.') }}

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="empty-table">

                                    <div class="empty-icon">
                                        📦
                                    </div>

                                    <strong>
                                        Belum ada kebutuhan
                                    </strong>

                                    <p>
                                        Belum terdapat kebutuhan
                                        yang dapat direkap.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if ($recap->count())
                        <tfoot>

                            <tr>

                                <td colspan="4" class="footer-label">
                                    TOTAL KESELURUHAN
                                </td>

                                <td>

                                    <strong class="footer-quantity">

                                        {{ rtrim(rtrim(number_format($totalQuantity, 3, ',', '.'), '0'), ',') }}

                                    </strong>

                                </td>

                                <td>
                                    -
                                </td>

                                <td>

                                    <strong class="footer-price">

                                        Rp
                                        {{ number_format($totalPrice, 0, ',', '.') }}

                                    </strong>

                                </td>

                            </tr>

                        </tfoot>
                    @endif

                </table>

            </div>

        </div>

    </div>



    {{-- =========================================================
         CSS
    ========================================================== --}}

    <style>
        /* =====================================================
           PAGE
        ====================================================== */

        .recap-page {
            width: 100%;
        }


        /* =====================================================
           HEADER
        ====================================================== */

        .page-header {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 20px;

        }


        .breadcrumb {

            margin-bottom: 6px;

            font-size: 11px;

            color: #64748b;

        }


        .breadcrumb span {

            margin: 0 5px;

            color: #cbd5e1;

        }


        .page-header h2 {

            margin: 0;

            font-size: 20px;

            font-weight: 700;

            color: #111827;

        }


        .page-header p {

            margin: 5px 0 0;

            font-size: 12px;

            color: #64748b;

        }


        /* =====================================================
           BUTTON
        ====================================================== */

        .btn-primary,
        .btn-secondary {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-height: 38px;

            padding: 0 14px;

            border-radius: 7px;

            font-size: 12px;

            font-weight: 600;

            text-decoration: none;

            cursor: pointer;

            transition: .15s ease;

        }


        .btn-primary {

            border: 1px solid #2563eb;

            background: #2563eb;

            color: #fff;

        }


        .btn-primary:hover {

            background: #1d4ed8;

            border-color: #1d4ed8;

            color: #fff;

        }


        .btn-secondary {

            border: 1px solid #d1d5db;

            background: #fff;

            color: #374151;

        }


        .btn-secondary:hover {

            background: #f8fafc;

            color: #111827;

        }


        /* =====================================================
           FILTER
        ====================================================== */

        .filter-card {

            margin-bottom: 16px;

            padding: 16px;

            background: #fff;

            border: 1px solid #dfe3e8;

            border-radius: 9px;

        }


        .filter-form {

            display: flex;

            align-items: flex-end;

            gap: 12px;

        }


        .filter-group {

            width: 200px;

        }


        .filter-group label {

            display: block;

            margin-bottom: 6px;

            font-size: 11px;

            font-weight: 600;

            color: #374151;

        }


        .form-control {

            width: 100%;

            height: 38px;

            padding: 0 10px;

            border: 1px solid #d1d5db;

            border-radius: 7px;

            background: #fff;

            font-size: 12px;

            color: #374151;

            outline: none;

        }


        .form-control:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, .08);

        }


        .filter-actions {

            display: flex;

            gap: 7px;

        }


        /* =====================================================
           SUMMARY
        ====================================================== */

        .summary-grid {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 12px;

            margin-bottom: 16px;

        }


        .summary-card {

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 16px;

            background: #fff;

            border: 1px solid #dfe3e8;

            border-radius: 9px;

        }


        .summary-icon {

            display: flex;

            align-items: center;

            justify-content: center;

            width: 40px;

            height: 40px;

            border-radius: 8px;

            background: #eff6ff;

            font-size: 18px;

        }


        .summary-label {

            font-size: 10px;

            font-weight: 600;

            color: #64748b;

        }


        .summary-value {

            margin-top: 3px;

            font-size: 19px;

            font-weight: 800;

            color: #111827;

        }


        .summary-value.price {

            color: #2563eb;

            font-size: 16px;

        }


        .summary-description {

            margin-top: 2px;

            font-size: 9px;

            color: #94a3b8;

        }


        /* =====================================================
           RECAP CARD
        ====================================================== */

        .recap-card {

            overflow: hidden;

            background: #fff;

            border: 1px solid #dfe3e8;

            border-radius: 9px;

        }


        .recap-card-header {

            padding: 16px;

            border-bottom: 1px solid #e5e7eb;

        }


        .recap-card-header h3 {

            margin: 0;

            font-size: 14px;

            font-weight: 700;

            color: #1f2937;

        }


        .recap-card-header p {

            margin: 4px 0 0;

            font-size: 11px;

            color: #94a3b8;

        }


        /* =====================================================
           TABLE
        ====================================================== */

        .table-wrapper {

            width: 100%;

            overflow-x: auto;

        }


        .recap-table {

            width: 100%;

            border-collapse: separate;

            border-spacing: 0;

            font-size: 12px;

        }


        .recap-table th {

            padding: 12px 14px;

            background: #f8fafc;

            border-bottom: 1px solid #dfe3e8;

            border-right: 1px solid #e5e7eb;

            color: #475569;

            font-size: 10px;

            font-weight: 700;

            text-align: left;

            white-space: nowrap;

        }


        .recap-table th:last-child {

            border-right: none;

        }


        .recap-table td {

            padding: 14px;

            border-bottom: 1px solid #e5e7eb;

            border-right: 1px solid #e5e7eb;

            vertical-align: top;

            color: #374151;

        }


        .recap-table td:last-child {

            border-right: none;

        }


        .recap-table tbody tr:hover {

            background: #fafcff;

        }


        .recap-table tbody tr:last-child td {

            border-bottom: none;

        }


        /* =====================================================
           PRODUCT
        ====================================================== */

        .product-name {

            font-weight: 700;

            color: #1f2937;

        }


        .product-code {

            margin-top: 3px;

            font-size: 10px;

            color: #94a3b8;

        }


        .category-badge {

            display: inline-flex;

            padding: 4px 7px;

            border-radius: 5px;

            background: #f1f5f9;

            color: #475569;

            font-size: 10px;

            font-weight: 600;

        }


        /* =====================================================
           WARUNG
        ====================================================== */

        .warung-count {

            margin-bottom: 6px;

            font-size: 10px;

            font-weight: 600;

            color: #64748b;

        }


        .warung-list {

            display: flex;

            flex-direction: column;

            gap: 4px;

            min-width: 180px;

        }


        .warung-item {

            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding-bottom: 4px;

            border-bottom: 1px dashed #e5e7eb;

            font-size: 10px;

        }


        .warung-item:last-child {

            border-bottom: none;

        }


        .warung-item span {

            color: #475569;

        }


        .warung-item strong {

            color: #2563eb;

            white-space: nowrap;

        }


        /* =====================================================
           QUANTITY
        ====================================================== */

        .quantity-total {

            font-size: 14px;

            font-weight: 800;

            color: #111827;

            white-space: nowrap;

        }


        .quantity-total span {

            font-size: 11px;

            color: #64748b;

            font-weight: 500;

        }


        /* =====================================================
           PRICE
        ====================================================== */

        .price-total {

            font-weight: 700;

            color: #2563eb;

            white-space: nowrap;

        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .recap-table tfoot td {

            padding: 14px;

            background: #f8fafc;

            border-top: 2px solid #dbe3ec;

            border-right: 1px solid #e5e7eb;

            color: #1f2937;

        }


        .recap-table tfoot td:last-child {

            border-right: none;

        }


        .footer-label {

            text-align: right;

            font-size: 11px;

            font-weight: 800;

        }


        .footer-quantity {

            font-size: 14px;

        }


        .footer-price {

            color: #2563eb;

            white-space: nowrap;

        }


        /* =====================================================
           EMPTY
        ====================================================== */

        .empty-table {

            padding: 50px !important;

            text-align: center;

            color: #94a3b8 !important;

        }


        .empty-icon {

            margin-bottom: 8px;

            font-size: 28px;

        }


        .empty-table strong {

            display: block;

            color: #475569;

        }


        .empty-table p {

            margin: 5px 0 0;

            font-size: 11px;

        }


        .text-center {

            text-align: center;

        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 1100px) {

            .summary-grid {

                grid-template-columns:
                    repeat(2, 1fr);

            }


            .filter-form {

                flex-wrap: wrap;

            }

        }


        @media (max-width: 700px) {

            .page-header {

                align-items: stretch;

                flex-direction: column;

            }


            .header-actions .btn-secondary {

                width: 100%;

            }


            .filter-form {

                flex-direction: column;

                align-items: stretch;

            }


            .filter-group {

                width: 100%;

            }


            .filter-actions {

                width: 100%;

            }


            .filter-actions .btn-primary,
            .filter-actions .btn-secondary {

                flex: 1;

            }


            .summary-grid {

                grid-template-columns: 1fr;

            }


            .recap-table {

                min-width: 1050px;

            }

        }

        .filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Tombol download */
        .btn-download {
            height: 40px;
            padding: 0 15px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            border-radius: 6px;
            border: 1px solid transparent;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;
            transition: all .2s ease;
        }

        /* Icon */
        .download-icon {
            width: 18px;
            height: 18px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            font-size: 16px;
            font-weight: 700;
        }

        /* Excel */
        .btn-download-excel {
            color: #15803d;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .btn-download-excel:hover {
            color: #ffffff;
            background: #16a34a;
            border-color: #16a34a;
        }

        /* PDF */
        .btn-download-pdf {
            color: #dc2626;
            background: #fef2f2;
            border-color: #fecaca;
        }

        .btn-download-pdf:hover {
            color: #ffffff;
            background: #dc2626;
            border-color: #dc2626;
        }

        /* Mobile */
        @media (max-width: 700px) {
            .btn-download {
                flex: 1;
                min-width: 100px;
            }
        }
    </style>

</x-app-layout>
