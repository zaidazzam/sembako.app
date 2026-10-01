<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Rekap Kebutuhan</title>

    <style>

        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        .header {
            margin-bottom: 15px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #64748b;
            font-size: 10px;
        }

        .period {
            margin-top: 10px;
            padding: 8px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
            text-align: left;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 7px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .product-name {
            font-weight: bold;
        }

        .product-code {
            margin-top: 2px;
            color: #64748b;
            font-size: 8px;
        }

        .warung {
            margin-bottom: 3px;
        }

        .total-row td {
            font-weight: bold;
            background: #f8fafc;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #64748b;
        }

    </style>

</head>

<body>

    <div class="header">

        <div class="title">
            REKAP KEBUTUHAN WARUNG
        </div>

        <div class="subtitle">
            Sistem Manajemen Sembako
        </div>

        <div class="period">

            @if ($dateFrom && $dateTo)

                Periode:
                {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}
                -
                {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d F Y') }}

            @elseif ($dateFrom)

                Mulai:
                {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}

            @elseif ($dateTo)

                Sampai:
                {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d F Y') }}

            @else

                Semua Periode

            @endif

        </div>

    </div>


    <table>

        <thead>

            <tr>

                <th width="4%">No</th>

                <th width="18%">Produk</th>

                <th width="13%">Kategori</th>

                <th width="25%">Warung</th>

                <th width="12%">Total Kebutuhan</th>

                <th width="13%">Harga Satuan</th>

                <th width="15%">Total Nilai</th>

            </tr>

        </thead>


        <tbody>

            @forelse ($recap as $index => $item)

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>

                    <td>

                        <div class="product-name">
                            {{ $item['product_name'] }}
                        </div>

                        <div class="product-code">
                            {{ $item['product_code'] }}
                        </div>

                    </td>

                    <td>
                        {{ $item['category_name'] }}
                    </td>

                    <td>

                        @foreach ($item['warung_details'] as $warung)

                            <div class="warung">

                                {{ $warung['warung_name'] }}
                                :
                                {{ rtrim(
                                    rtrim(
                                        number_format(
                                            $warung['quantity'],
                                            3,
                                            ',',
                                            '.'
                                        ),
                                        '0'
                                    ),
                                    ','
                                ) }}
                                {{ $item['unit'] }}

                            </div>

                        @endforeach

                    </td>

                    <td class="right">

                        {{ rtrim(
                            rtrim(
                                number_format(
                                    $item['total_quantity'],
                                    3,
                                    ',',
                                    '.'
                                ),
                                '0'
                            ),
                            ','
                        ) }}

                        {{ $item['unit'] }}

                    </td>

                    <td class="right">

                        Rp
                        {{ number_format(
                            $item['total_price']
                            / max($item['total_quantity'], 1),
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>

                    <td class="right">

                        Rp
                        {{ number_format(
                            $item['total_price'],
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="center">
                        Belum ada kebutuhan.
                    </td>

                </tr>

            @endforelse


            @if ($recap->count())

                <tr class="total-row">

                    <td colspan="4" class="right">
                        TOTAL KESELURUHAN
                    </td>

                    <td class="right">

                        {{ rtrim(
                            rtrim(
                                number_format(
                                    $totalQuantity,
                                    3,
                                    ',',
                                    '.'
                                ),
                                '0'
                            ),
                            ','
                        ) }}

                    </td>

                    <td class="center">
                        -
                    </td>

                    <td class="right">

                        Rp
                        {{ number_format(
                            $totalPrice,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>

                </tr>

            @endif

        </tbody>

    </table>


    <div class="footer">

        Dicetak pada:
        {{ now()->format('d-m-Y H:i') }}

    </div>

</body>

</html>
