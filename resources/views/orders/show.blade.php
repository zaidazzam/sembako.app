<x-app-layout>

    @section('title', 'Detail Kebutuhan')
    @section('page-title', 'Detail Kebutuhan')

    <div class="order-detail-page">

        {{-- HEADER --}}
        <div class="detail-header-actions">

            <a href="{{ route('orders.index') }}" class="btn-secondary">
                ← Kembali
            </a>

            <a href="{{ route('orders.edit', $order) }}" class="btn-primary">
                ✎ Edit Kebutuhan
            </a>

            <form method="POST" action="{{ route('orders.destroy', $order) }}"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kebutuhan ini?')" class="delete-form">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn-danger">
                    🗑 Hapus
                </button>

            </form>

        </div>


        {{-- INFORMATION --}}
        <div class="info-grid">

            {{-- WARUNG --}}
            <div class="info-card">

                <div class="info-label">
                    WARUNG
                </div>

                <div class="info-value">
                    {{ $order->warung->name }}
                </div>

                <div class="info-sub">
                    {{ $order->warung->code }}
                </div>

            </div>


            {{-- TANGGAL --}}
            <div class="info-card">

                <div class="info-label">
                    TANGGAL
                </div>

                <div class="info-value">
                    {{ $order->order_date->translatedFormat('d F Y') }}
                </div>

            </div>

            {{-- TOTAL PESANAN --}}
            <div class="info-card">

                <div class="info-label">
                    TOTAL PESANAN
                </div>

                @php
                    $totalOrder = $order->items->sum(function ($item) {
                        return $item->quantity * $item->product->price;
                    });
                @endphp

                <div class="total-order-price-detail">
                    Rp {{ number_format($totalOrder, 0, ',', '.') }}
                </div>

            </div>


            {{-- DICATAT OLEH --}}
            <div class="info-card">

                <div class="info-label">
                    DICATAT OLEH
                </div>

                <div class="info-value">
                    {{ $order->createdBy->name }}
                </div>

            </div>


            {{-- STATUS --}}
            <div class="info-card">

                <div class="info-label">
                    STATUS
                </div>

                @php
                    $status = $order->status->value;

                    $statusLabel = match ($status) {
                        'draft' => 'Draft',
                        'submitted' => 'Terkirim',
                        'processing' => 'Diproses',
                        'ready' => 'Siap',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($status),
                    };
                @endphp

                <span class="status-badge status-{{ $status }}">
                    <span class="status-dot"></span>
                    {{ $statusLabel }}
                </span>

            </div>

        </div>


        {{-- PRODUCT TABLE --}}
        <div class="detail-card">

            <div class="card-header">

                <div>
                    <h3>Daftar Kebutuhan</h3>

                    <p>
                        {{ $order->items->count() }} jenis produk
                    </p>
                </div>

                <a href="{{ route('orders.edit', $order) }}" class="btn-small">
                    ✎ Edit
                </a>

            </div>


            <div class="table-responsive">

                <table class="detail-table">

                    <thead>
                        <tr>
                            <th width="25%">PRODUK</th>
                            <th width="15%">KATEGORI</th>
                            <th width="15%" class="text-right">HARGA</th>
                            <th width="10%" class="text-center">JUMLAH</th>
                            <th width="15%" class="text-right">SUBTOTAL</th>
                            <th width="20%">CATATAN</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($order->items as $item)
                            @php
                                $subtotal = $item->quantity * $item->product->price;
                            @endphp

                            <tr>

                                {{-- PRODUK --}}
                                <td>
                                    <div class="product-name">
                                        {{ $item->product->name }}
                                    </div>

                                    <div class="product-code">
                                        {{ $item->product->code }}
                                    </div>
                                </td>

                                {{-- KATEGORI --}}
                                <td>
                                    <span class="category-text">
                                        {{ $item->product->category->name }}
                                    </span>
                                </td>

                                {{-- HARGA --}}
                                <td class="text-right">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </td>

                                {{-- JUMLAH --}}
                                <td class="text-center">

                                    <span class="quantity-value">
                                        {{ rtrim(rtrim(number_format($item->quantity, 3, ',', '.'), '0'), ',') }}
                                    </span>

                                    <span class="unit-text">
                                        {{ $item->unit }}
                                    </span>

                                </td>

                                {{-- SUBTOTAL --}}
                                <td class="text-right">

                                    <span class="subtotal-value">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>

                                </td>

                                {{-- CATATAN --}}
                                <td>

                                    @if ($item->notes)
                                        <span class="notes-text">
                                            {{ $item->notes }}
                                        </span>
                                    @else
                                        <span class="empty-notes">
                                            -
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="empty-state">
                                    Belum ada produk pada kebutuhan ini.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
                <div class="order-total">

                    <div class="order-total-label">
                        Total Pesanan
                    </div>

                    <div class="order-total-value">
                        Rp {{ number_format($totalOrder, 0, ',', '.') }}
                    </div>

                </div>

            </div>

        </div>


        {{-- CATATAN UMUM --}}
        @if ($order->notes)
            <div class="detail-card notes-card">

                <div class="card-header">

                    <div>

                        <h3>Catatan Umum</h3>

                    </div>

                </div>

                <div class="general-notes">
                    {{ $order->notes }}
                </div>

            </div>
        @endif

    </div>

    <div class="status-update-card">
        <div class="status-update-info">
            <div class="status-update-label">STATUS KEBUTUHAN</div>

            <div class="status-update-current">
                <span class="status-dot"></span>

                @switch($order->status->value)
                    @case('submitted')
                        Menunggu Diproses
                    @break

                    @case('processing')
                        Diproses
                    @break

                    @case('ready')
                        Siap
                    @break

                    @case('completed')
                        Selesai
                    @break

                    @case('cancelled')
                        Dibatalkan
                    @break

                    @default
                        {{ $order->status->value }}
                @endswitch
            </div>
        </div>

        <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="status-update-form">
            @csrf
            @method('PATCH')

            <select name="status" class="status-select">
                <option value="submitted" @selected($order->status->value === 'submitted')>
                    Menunggu Diproses
                </option>

                <option value="processing" @selected($order->status->value === 'processing')>
                    Diproses
                </option>

                <option value="ready" @selected($order->status->value === 'ready')>
                    Siap
                </option>

                <option value="completed" @selected($order->status->value === 'completed')>
                    Selesai
                </option>

                <option value="cancelled" @selected($order->status->value === 'cancelled')>
                    Dibatalkan
                </option>
            </select>

            <button type="submit" class="btn-update-status">
                Simpan Status
            </button>
        </form>
    </div>

    <style>
        .status-update-card {
            margin-top: 18px;
            padding: 18px 20px;
            background: #ffffff;
            border: 1px solid #dfe3e8;
            border-radius: 9px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .status-update-info {
            min-width: 200px;
        }

        .status-update-label {
            margin-bottom: 7px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            color: #8a94a6;
        }

        .status-update-current {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #2563eb;
        }

        .status-update-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-select {
            height: 40px;
            min-width: 210px;
            padding: 0 35px 0 12px;

            font-size: 13px;
            color: #374151;

            background-color: #ffffff;
            border: 1px solid #cfd6df;
            border-radius: 6px;

            outline: none;
            cursor: pointer;
        }

        .status-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, .1);
        }

        .btn-update-status {
            height: 40px;
            padding: 0 16px;

            border: none;
            border-radius: 6px;

            background: #2563eb;
            color: #ffffff;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;
            transition: .2s ease;
        }

        .btn-update-status:hover {
            background: #1d4ed8;
        }

        @media (max-width: 700px) {
            .status-update-card {
                flex-direction: column;
                align-items: stretch;
            }

            .status-update-form {
                width: 100%;
            }

            .status-select {
                flex: 1;
                min-width: 0;
            }

            .btn-update-status {
                white-space: nowrap;
            }
        }

        @media (max-width: 500px) {
            .status-update-form {
                flex-direction: column;
                align-items: stretch;
            }

            .status-select,
            .btn-update-status {
                width: 100%;
            }
        }

        @media (max-width: 700px) {

            .detail-header-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .detail-header-actions>a,
            .detail-header-actions .delete-form,
            .detail-header-actions .btn-danger {
                width: 100%;
            }

            .detail-header-actions .delete-form {
                grid-column: 1 / -1;
            }

        }

        .detail-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .delete-form {
            margin: 0;
            padding: 0;
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            min-height: 38px;
            padding: 0 14px;

            border: 1px solid #dc2626;
            border-radius: 7px;

            background: #fff;
            color: #dc2626;

            font-size: 12px;
            font-weight: 600;

            cursor: pointer;

            transition: all .15s ease;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }

        .btn-danger:active {
            transform: translateY(1px);
        }

        /* =================================
           PAGE
        ================================= */

        .order-detail-page {
            width: 100%;
        }

        .order-total {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 30px;

            padding: 18px 20px;

            border-top: 2px solid #e5e7eb;
            background: #f8fafc;
        }

        .order-total-label {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
        }

        .order-total-value {
            min-width: 160px;

            font-size: 18px;
            font-weight: 800;

            color: #2563eb;

            text-align: right;
        }

        /* =================================
           HEADER
        ================================= */

        .detail-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 20px;
        }

        .detail-header-info {
            min-width: 0;
        }

        .breadcrumb {
            margin-bottom: 7px;

            font-size: 11px;
            color: #6b7280;
        }

        .breadcrumb span {
            margin: 0 5px;
            color: #9ca3af;
        }

        .detail-header h2 {
            margin: 0;

            font-size: 20px;
            font-weight: 700;

            color: #111827;
        }

        .detail-header p {
            margin: 5px 0 0;

            font-size: 13px;
            color: #6b7280;
        }

        .detail-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }


        /* =================================
           BUTTON
        ================================= */

        .btn-primary,
        .btn-secondary,
        .btn-small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            border-radius: 7px;

            text-decoration: none;
            font-size: 12px;
            font-weight: 600;

            transition: all .15s ease;

            cursor: pointer;
        }

        .btn-primary {
            min-height: 38px;
            padding: 0 14px;

            background: #2563eb;
            border: 1px solid #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }

        .btn-secondary {
            min-height: 38px;
            padding: 0 14px;

            background: #fff;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            color: #111827;
        }

        .btn-small {
            min-height: 32px;
            padding: 0 11px;

            background: #fff;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-small:hover {
            border-color: #93c5fd;
            background: #eff6ff;
            color: #2563eb;
        }


        /* =================================
           INFO GRID
        ================================= */

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;

            margin-bottom: 20px;
        }

        .info-card {
            min-height: 100px;

            padding: 18px;

            background: #fff;

            border: 1px solid #dfe3e8;
            border-radius: 9px;

            box-shadow: 0 1px 3px rgba(0, 0, 0, .03);
        }

        .info-label {
            margin-bottom: 9px;

            font-size: 10px;
            font-weight: 700;

            color: #9ca3af;

            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 700;

            color: #1f2937;
        }

        .info-sub {
            margin-top: 4px;

            font-size: 11px;
            color: #9ca3af;
        }


        /* =================================
           DETAIL CARD
        ================================= */

        .detail-card {
            margin-bottom: 16px;

            background: #fff;

            border: 1px solid #dfe3e8;
            border-radius: 9px;

            overflow: hidden;

            box-shadow: 0 1px 3px rgba(0, 0, 0, .03);
        }

        .card-header {
            min-height: 66px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 13px 16px;

            border-bottom: 1px solid #e5e7eb;
            background: #fafbfc;
        }

        .card-header h3 {
            margin: 0;

            font-size: 14px;
            font-weight: 700;

            color: #1f2937;
        }

        .card-header p {
            margin: 4px 0 0;

            font-size: 11px;
            color: #9ca3af;
        }


        /* =================================
           TABLE
        ================================= */

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .detail-table {
            width: 100%;

            border-collapse: collapse;

            font-size: 12px;
        }

        .detail-table th {
            padding: 12px 14px;

            background: #f8fafc;

            border-right: 1px solid #e5e7eb;
            border-bottom: 2px solid #dfe3e8;

            color: #4b5563;

            font-size: 10px;
            font-weight: 700;

            text-align: left;
            text-transform: uppercase;
            letter-spacing: .3px;

            white-space: nowrap;
        }

        .detail-table th:last-child {
            border-right: none;
        }

        .detail-table td {
            padding: 14px;

            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;

            vertical-align: middle;

            color: #374151;
        }

        .detail-table td:last-child {
            border-right: none;
        }

        .detail-table tbody tr:last-child td {
            border-bottom: none;
        }

        .detail-table tbody tr:hover td {
            background: #f8faff;
        }


        /* =================================
           PRODUCT
        ================================= */

        .product-name {
            font-size: 13px;
            font-weight: 700;

            color: #1f2937;
        }

        .product-code {
            margin-top: 3px;

            font-size: 10px;
            color: #9ca3af;
        }

        .category-text {
            color: #4b5563;
        }


        /* =================================
           QUANTITY
        ================================= */

        .quantity-value {
            font-size: 13px;
            font-weight: 700;

            color: #1f2937;
        }

        .unit-badge {
            display: inline-flex;

            padding: 4px 8px;

            border-radius: 5px;

            background: #f1f5f9;
            border: 1px solid #e2e8f0;

            color: #475569;

            font-size: 10px;
            font-weight: 600;
        }


        /* =================================
           NOTES
        ================================= */

        .notes-text {
            color: #4b5563;
        }

        .empty-notes {
            color: #9ca3af;
        }

        .general-notes {
            padding: 16px;

            font-size: 13px;
            line-height: 1.6;

            color: #4b5563;
            white-space: pre-line;
        }


        /* =================================
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


        /* =================================
           EMPTY
        ================================= */

        .empty-state {
            padding: 40px !important;

            text-align: center;

            color: #9ca3af;
        }


        /* =================================
           RESPONSIVE
        ================================= */

        @media (max-width: 991px) {

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 768px) {

            .detail-header {
                align-items: stretch;
                flex-direction: column;
            }

            .detail-header-actions {
                width: 100%;
            }

            .detail-header-actions a {
                flex: 1;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .detail-table {
                min-width: 750px;
            }

        }
    </style>

</x-app-layout>
