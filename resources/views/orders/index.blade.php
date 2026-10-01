{{-- resources/views/orders/index.blade.php --}}

<x-app-layout>

    @section('title', 'Daftar Kebutuhan')
    @section('page-title', 'Daftar Kebutuhan')

    <div class="orders-page">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h2>Daftar Kebutuhan</h2>
                <p>Daftar kebutuhan barang dari setiap warung.</p>
            </div>

            <a href="{{ route('orders.create') }}" class="btn-primary">
                <span>＋</span>
                Catat Kebutuhan
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="table-card">

            <div class="table-responsive">
                <div class="table-wrapper">

                    <table class="orders-table">

                        <thead>
                            <tr>
                                <th>NO. KEBUTUHAN</th>
                                <th>WARUNG</th>
                                <th>TANGGAL</th>
                                <th>RINCIAN KEBUTUHAN</th>
                                <th>DIBUAT OLEH</th>
                                <th>STATUS</th>
                                <th>TOTAL</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($orders as $order)

                                <tr>

                                    {{-- NOMOR --}}
                                    <td>

                                        <a href="{{ route('orders.show', $order) }}" class="order-number">
                                            {{ $order->order_number }}
                                        </a>

                                    </td>


                                    {{-- WARUNG --}}
                                    <td>

                                        <div class="warung-name">
                                            {{ $order->warung->name }}
                                        </div>

                                        <div class="warung-code">
                                            {{ $order->warung->code }}
                                        </div>

                                    </td>


                                    {{-- TANGGAL --}}
                                    <td>

                                        {{ $order->order_date->format('d/m/Y') }}

                                    </td>


                                    {{-- RINCIAN KEBUTUHAN --}}
                                    <td>

                                        <div class="requirement-list">

                                            @foreach ($order->items as $item)
                                                <div class="requirement-item">

                                                    <div class="requirement-product">

                                                        {{ $item->product->name }}

                                                    </div>

                                                    <div class="requirement-quantity">

                                                        {{ rtrim(rtrim(number_format($item->quantity, 3, ',', '.'), '0'), ',') }}

                                                        {{ $item->unit }}

                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>


                                        <div class="requirement-summary">

                                            {{ $order->items->count() }}
                                            jenis produk

                                        </div>

                                    </td>


                                    {{-- DIBUAT OLEH --}}
                                    <td>

                                        {{ $order->createdBy->name }}

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        <span class="status-badge">

                                            <span class="status-dot"></span>

                                            {{ $order->status->value === 'submitted' ? 'Terkirim' : ucfirst($order->status->value) }}

                                        </span>

                                    </td>


                                    {{-- TOTAL HARGA --}}
                                    <td>

                                        @php
                                            $total = $order->items->sum(function ($item) {
                                                return $item->quantity * $item->product->price;
                                            });
                                        @endphp

                                        <div class="order-total">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </div>

                                    </td>


                                    {{-- AKSI --}}
                                    <td>

                                        <a href="{{ route('orders.show', $order) }}" class="btn-detail"
                                            title="Lihat detail">
                                            👁
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="empty-table">
                                        Belum ada data kebutuhan.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>
            </div>

            {{-- PAGINATION --}}
            @if ($orders->hasPages())
                <div class="pagination-wrapper">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>

    </div>


    <style>
        .table-wrapper {
            width: 100%;
            overflow-x: auto;

            background: #fff;

            border: 1px solid #dfe3e8;
            border-radius: 9px;

            box-shadow:
                0 1px 3px rgba(0, 0, 0, .03);
        }


        .orders-table {
            width: 100%;

            border-collapse: separate;
            border-spacing: 0;

            font-size: 12px;
        }


        .orders-table th {
            padding: 13px 14px;

            background: #f8fafc;

            border-bottom: 1px solid #dfe3e8;
            border-right: 1px solid #e5e7eb;

            text-align: left;

            font-size: 10px;
            font-weight: 700;

            color: #475569;

            white-space: nowrap;
        }


        .orders-table th:last-child {
            border-right: none;
        }


        .orders-table td {
            padding: 14px;

            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;

            vertical-align: middle;

            color: #374151;
        }


        .orders-table td:last-child {
            border-right: none;
        }


        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }


        .orders-table tbody tr:hover {
            background: #fafcff;
        }


        /* ==========================================
   ORDER NUMBER
========================================== */

        .order-number {
            color: #2563eb;

            font-weight: 700;

            text-decoration: none;

            white-space: nowrap;
        }


        .order-number:hover {
            text-decoration: underline;
        }


        /* ==========================================
   WARUNG
========================================== */

        .warung-name {
            font-weight: 700;

            color: #1f2937;
        }


        .warung-code {
            margin-top: 3px;

            font-size: 10px;

            color: #94a3b8;
        }


        /* ==========================================
   REQUIREMENT
========================================== */

        .requirement-list {
            display: flex;

            flex-direction: column;

            gap: 5px;

            min-width: 220px;
        }


        .requirement-item {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 20px;

            padding-bottom: 5px;

            border-bottom: 1px dashed #e5e7eb;
        }


        .requirement-item:last-child {
            border-bottom: none;

            padding-bottom: 0;
        }


        .requirement-product {
            color: #1f2937;

            font-weight: 600;

            line-height: 1.4;
        }


        .requirement-quantity {
            color: #2563eb;

            font-weight: 700;

            white-space: nowrap;
        }


        .requirement-summary {
            margin-top: 8px;

            font-size: 10px;

            color: #94a3b8;
        }


        /* ==========================================
   TOTAL
========================================== */

        .order-total {
            color: #2563eb;

            font-weight: 700;

            white-space: nowrap;
        }


        /* ==========================================
   STATUS
========================================== */

        .status-badge {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 5px 9px;

            border-radius: 6px;

            background: #eff6ff;

            color: #2563eb;

            font-size: 10px;

            font-weight: 600;

            white-space: nowrap;
        }


        .status-dot {
            width: 5px;
            height: 5px;

            border-radius: 50%;

            background: #2563eb;
        }


        /* ==========================================
   DETAIL BUTTON
========================================== */

        .btn-detail {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 32px;
            height: 32px;

            border: 1px solid #dbe3ec;

            border-radius: 7px;

            background: #fff;

            color: #475569;

            text-decoration: none;

            transition: .15s ease;
        }


        .btn-detail:hover {
            background: #f8fafc;

            border-color: #2563eb;

            color: #2563eb;
        }


        /* ==========================================
   EMPTY
========================================== */

        .empty-table {
            padding: 40px !important;

            text-align: center;

            color: #94a3b8 !important;
        }


        /* ==========================================
   MOBILE
========================================== */

        @media (max-width: 900px) {

            .table-wrapper {
                overflow-x: auto;
            }


            .orders-table {
                min-width: 1100px;
            }

        }

        /* ================================
           ORDERS PAGE
        ================================= */

        .item-summary {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .total-order-price {
            font-size: 12px;
            font-weight: 700;
            color: #2563eb;
            white-space: nowrap;
        }

        .orders-page {
            width: 100%;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .page-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .page-header p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #6b7280;
        }


        /* ================================
           BUTTON
        ================================= */

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            min-height: 40px;
            padding: 0 16px;

            border: none;
            border-radius: 8px;

            background: #2563eb;
            color: #fff;

            font-size: 13px;
            font-weight: 600;

            text-decoration: none;
            cursor: pointer;

            transition: all .2s ease;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            color: #fff;
            transform: translateY(-1px);
        }


        /* ================================
           TABLE CARD
        ================================= */

        .table-card {
            background: #fff;
            border: 1px solid #dfe3e8;
            border-radius: 10px;
            overflow: hidden;

            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }


        /* ================================
           TABLE
        ================================= */

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 13px;
        }

        .orders-table thead {
            background: #f8fafc;
        }

        .orders-table th {
            padding: 14px 16px;

            border-right: 1px solid #e5e7eb;
            border-bottom: 2px solid #dfe3e8;

            color: #374151;

            font-size: 11px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .3px;

            text-align: left;
            white-space: nowrap;
        }

        .orders-table th:last-child {
            border-right: none;
        }

        .orders-table td {
            padding: 15px 16px;

            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;

            color: #374151;

            vertical-align: middle;
            background: #fff;
        }

        .orders-table td:last-child {
            border-right: none;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .orders-table tbody tr {
            transition: background .15s ease;
        }

        .orders-table tbody tr:hover td {
            background: #f8faff;
        }


        /* ================================
           ORDER NUMBER
        ================================= */

        .order-number {
            font-size: 12px;
            font-weight: 700;
            color: #2563eb;
            white-space: nowrap;
        }


        /* ================================
           WARUNG
        ================================= */

        .warung-name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
        }

        .warung-code {
            margin-top: 3px;
            font-size: 11px;
            color: #9ca3af;
        }


        /* ================================
           DATE
        ================================= */

        .date-text {
            color: #4b5563;
            white-space: nowrap;
        }


        /* ================================
           ITEM BADGE
        ================================= */

        .item-badge {
            display: inline-flex;
            align-items: center;

            padding: 5px 9px;

            border-radius: 6px;

            background: #f1f5f9;
            border: 1px solid #e2e8f0;

            color: #475569;

            font-size: 11px;
            font-weight: 600;
        }


        /* ================================
           CREATOR
        ================================= */

        .creator-name {
            font-weight: 500;
            color: #374151;
        }


        /* ================================
           STATUS
        ================================= */

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 5px 9px;

            border-radius: 6px;

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-draft {
            background: #f3f4f6;
            color: #6b7280;
        }

        .status-submitted {
            background: #eff6ff;
            color: #2563eb;
        }

        .status-processing {
            background: #fff7ed;
            color: #ea580c;
        }

        .status-ready {
            background: #ecfeff;
            color: #0891b2;
        }

        .status-completed {
            background: #ecfdf5;
            color: #059669;
        }

        .status-cancelled {
            background: #fef2f2;
            color: #dc2626;
        }


        /* ================================
           ACTION
        ================================= */

        .text-center {
            text-align: center !important;
        }

        .action-btn {
            width: 32px;
            height: 32px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 1px solid #dbe2ea;
            border-radius: 7px;

            background: #fff;
            color: #4b5563;

            text-decoration: none;

            font-size: 13px;

            transition: all .15s ease;
        }

        .action-btn:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #2563eb;
        }


        /* ================================
           EMPTY
        ================================= */

        .empty-state {
            padding: 60px 20px !important;
            text-align: center;
            border-right: none !important;
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .empty-title {
            font-size: 15px;
            font-weight: 700;
            color: #374151;
        }

        .empty-text {
            margin: 5px 0 18px;
            font-size: 12px;
            color: #9ca3af;
        }


        /* ================================
           PAGINATION
        ================================= */

        .pagination-wrapper {
            padding: 14px 18px;
            border-top: 1px solid #e5e7eb;
            background: #fafafa;
        }


        /* ================================
           MOBILE
        ================================= */

        @media (max-width: 768px) {

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-header h2 {
                font-size: 18px;
            }

            .btn-primary {
                width: 100%;
            }

            .table-card {
                border-radius: 8px;
            }

            .orders-table {
                min-width: 950px;
            }

            .orders-table th,
            .orders-table td {
                padding: 12px 14px;
            }

        }
    </style>

</x-app-layout>
