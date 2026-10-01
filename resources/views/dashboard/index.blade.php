<x-app-layout>

    @section('title', 'Dashboard')
    @section('page-title', 'Dashboard')

    <div class="dashboard-page">

        {{-- Header --}}
        <div class="dashboard-header">
            <div>
                <div class="dashboard-breadcrumb">
                    Dashboard
                </div>

                <h2>Dashboard</h2>

                <p>
                    Ringkasan kebutuhan dan aktivitas warung.
                </p>
            </div>
        </div>


        {{-- Summary --}}
        <div class="summary-grid">

            <div class="summary-card">
                <div class="summary-icon">🏪</div>

                <div>
                    <div class="summary-label">
                        Total Warung
                    </div>

                    <div class="summary-value">
                        {{ $totalWarungs }}
                    </div>

                    <div class="summary-description">
                        warung aktif
                    </div>
                </div>
            </div>


            <div class="summary-card">
                <div class="summary-icon">📦</div>

                <div>
                    <div class="summary-label">
                        Total Produk
                    </div>

                    <div class="summary-value">
                        {{ $totalProducts }}
                    </div>

                    <div class="summary-description">
                        produk aktif
                    </div>
                </div>
            </div>


            <div class="summary-card">
                <div class="summary-icon">⏳</div>

                <div>
                    <div class="summary-label">
                        Menunggu Diproses
                    </div>

                    <div class="summary-value">
                        {{ $totalSubmitted }}
                    </div>

                    <div class="summary-description">
                        kebutuhan
                    </div>
                </div>
            </div>


            <div class="summary-card">
                <div class="summary-icon">🔄</div>

                <div>
                    <div class="summary-label">
                        Sedang Diproses
                    </div>

                    <div class="summary-value">
                        {{ $totalProcessing }}
                    </div>

                    <div class="summary-description">
                        kebutuhan
                    </div>
                </div>
            </div>


            <div class="summary-card">
                <div class="summary-icon">📋</div>

                <div>
                    <div class="summary-label">
                        Siap
                    </div>

                    <div class="summary-value">
                        {{ $totalReady }}
                    </div>

                    <div class="summary-description">
                        kebutuhan
                    </div>
                </div>
            </div>


            <div class="summary-card">
                <div class="summary-icon">✅</div>

                <div>
                    <div class="summary-label">
                        Selesai
                    </div>

                    <div class="summary-value">
                        {{ $totalCompleted }}
                    </div>

                    <div class="summary-description">
                        kebutuhan
                    </div>
                </div>
            </div>

        </div>


        {{-- Total Nilai --}}
        <div class="value-card">

            <div>
                <div class="value-label">
                    TOTAL NILAI KEBUTUHAN
                </div>

                <div class="value-number">
                    Rp {{ number_format($totalPrice, 0, ',', '.') }}
                </div>

                <div class="value-description">
                    Estimasi seluruh kebutuhan yang tercatat
                </div>
            </div>

            <div class="value-icon">
                💰
            </div>

        </div>


        {{-- Bottom Grid --}}
        <div class="dashboard-grid">

            {{-- Kebutuhan Terbaru --}}
            <div class="dashboard-card">

                <div class="dashboard-card-header">
                    <div>
                        <h3>Kebutuhan Terbaru</h3>

                        <p>
                            Data kebutuhan terakhir yang dicatat.
                        </p>
                    </div>

                    <a
                        href="{{ route('orders.index') }}"
                        class="btn-small"
                    >
                        Lihat Semua
                    </a>
                </div>


                <div class="table-wrapper">

                    <table class="dashboard-table">

                        <thead>
                            <tr>
                                <th>No. Kebutuhan</th>
                                <th>Warung</th>
                                <th>Tanggal</th>
                                <th>Item</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($latestOrders as $order)

                                @php
                                    $statusLabel = match($order->status->value) {
                                        'submitted' => 'Menunggu Diproses',
                                        'processing' => 'Diproses',
                                        'ready' => 'Siap',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => $order->status->value,
                                    };

                                    $statusClass = match($order->status->value) {
                                        'submitted' => 'status-submitted',
                                        'processing' => 'status-processing',
                                        'ready' => 'status-ready',
                                        'completed' => 'status-completed',
                                        'cancelled' => 'status-cancelled',
                                        default => '',
                                    };
                                @endphp

                                <tr>

                                    <td>
                                        <a
                                            href="{{ route('orders.show', $order) }}"
                                            class="order-number"
                                        >
                                            {{ $order->order_number }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $order->warung->name }}
                                    </td>

                                    <td>
                                        {{ $order->order_date->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ $order->items->count() }} item
                                    </td>

                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="5"
                                        class="empty-table"
                                    >
                                        Belum ada kebutuhan.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Produk Terbanyak --}}
            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>
                        <h3>Produk Paling Dibutuhkan</h3>

                        <p>
                            Berdasarkan total kebutuhan.
                        </p>
                    </div>

                </div>


                <div class="product-list">

                    @forelse($topProducts as $index => $product)

                        <div class="product-item">

                            <div class="product-rank">
                                {{ $index + 1 }}
                            </div>

                            <div class="product-info">

                                <div class="product-name">
                                    {{ $product->name }}
                                </div>

                                <div class="product-code">
                                    {{ $product->code }}
                                </div>

                            </div>

                            <div class="product-quantity">

                                {{ rtrim(
                                    rtrim(
                                        number_format(
                                            $product->total_quantity,
                                            3,
                                            ',',
                                            '.'
                                        ),
                                        '0'
                                    ),
                                    ','
                                ) }}

                                {{ $product->unit }}

                            </div>

                        </div>

                    @empty

                        <div class="empty-product">
                            Belum ada data kebutuhan.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    <style>

        .dashboard-page {
            padding: 0;
        }

        .dashboard-header {
            margin-bottom: 20px;
        }

        .dashboard-breadcrumb {
            margin-bottom: 7px;
            font-size: 12px;
            color: #64748b;
        }

        .dashboard-header h2 {
            margin: 0;
            font-size: 24px;
            color: #111827;
        }

        .dashboard-header p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #64748b;
        }


        /* SUMMARY */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .summary-card {
            display: flex;
            align-items: center;
            gap: 14px;

            padding: 18px;

            background: #ffffff;
            border: 1px solid #dfe3e8;
            border-radius: 9px;
        }

        .summary-icon {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eff6ff;
            border-radius: 8px;

            font-size: 20px;
        }

        .summary-label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .summary-value {
            margin-top: 3px;

            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .summary-description {
            margin-top: 2px;

            font-size: 11px;
            color: #94a3b8;
        }


        /* VALUE */

        .value-card {
            margin-top: 14px;
            padding: 20px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            background: #ffffff;
            border: 1px solid #dfe3e8;
            border-radius: 9px;
        }

        .value-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            color: #64748b;
        }

        .value-number {
            margin-top: 5px;

            font-size: 27px;
            font-weight: 700;
            color: #2563eb;
        }

        .value-description {
            margin-top: 3px;

            font-size: 12px;
            color: #94a3b8;
        }

        .value-icon {
            font-size: 34px;
        }


        /* GRID */

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 14px;

            margin-top: 14px;
        }

        .dashboard-card {
            background: #ffffff;
            border: 1px solid #dfe3e8;
            border-radius: 9px;
            overflow: hidden;
        }

        .dashboard-card-header {
            padding: 17px 18px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            border-bottom: 1px solid #e5e7eb;
        }

        .dashboard-card-header h3 {
            margin: 0;

            font-size: 15px;
            color: #111827;
        }

        .dashboard-card-header p {
            margin: 4px 0 0;

            font-size: 11px;
            color: #94a3b8;
        }

        .btn-small {
            padding: 7px 11px;

            border: 1px solid #d1d5db;
            border-radius: 6px;

            color: #374151;
            background: #ffffff;

            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-small:hover {
            background: #f8fafc;
        }


        /* TABLE */

        .table-wrapper {
            overflow-x: auto;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dashboard-table th {
            padding: 11px 14px;

            text-align: left;

            font-size: 10px;
            font-weight: 700;

            color: #64748b;
            background: #f8fafc;

            border-bottom: 1px solid #e5e7eb;
        }

        .dashboard-table td {
            padding: 12px 14px;

            font-size: 12px;
            color: #374151;

            border-bottom: 1px solid #f1f5f9;
        }

        .order-number {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        .order-number:hover {
            text-decoration: underline;
        }


        /* STATUS */

        .status-badge {
            display: inline-flex;
            padding: 5px 8px;

            border-radius: 5px;

            font-size: 10px;
            font-weight: 600;
        }

        .status-submitted {
            color: #92400e;
            background: #fef3c7;
        }

        .status-processing {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .status-ready {
            color: #047857;
            background: #d1fae5;
        }

        .status-completed {
            color: #166534;
            background: #dcfce7;
        }

        .status-cancelled {
            color: #b91c1c;
            background: #fee2e2;
        }


        /* PRODUCTS */

        .product-list {
            padding: 5px 18px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 11px;

            padding: 13px 0;

            border-bottom: 1px solid #f1f5f9;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-rank {
            width: 28px;
            height: 28px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eff6ff;
            color: #2563eb;

            border-radius: 6px;

            font-size: 11px;
            font-weight: 700;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        .product-code {
            margin-top: 2px;

            font-size: 10px;
            color: #94a3b8;
        }

        .product-quantity {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            white-space: nowrap;
        }

        .empty-table,
        .empty-product {
            padding: 30px !important;

            text-align: center;
            color: #94a3b8 !important;
        }


        /* RESPONSIVE */

        @media (max-width: 1000px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .value-number {
                font-size: 22px;
            }

            .dashboard-card-header {
                align-items: flex-start;
                gap: 10px;
            }

            .dashboard-table {
                min-width: 650px;
            }
        }

    </style>

</x-app-layout>
