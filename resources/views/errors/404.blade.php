<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Halaman Tidak Ditemukan</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1f2937;
        }

        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .error-card {
            width: 100%;
            max-width: 500px;
            text-align: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 40px 30px;
        }

        .error-code {
            font-size: 64px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 16px;
        }

        .error-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .error-message {
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 16px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-secondary {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
        }
    </style>
</head>

<body>

<div class="error-page">

    <div class="error-card">

        <div class="error-code">
            404
        </div>

        <div class="error-title">
            Halaman Tidak Ditemukan
        </div>

        <div class="error-message">
            Halaman atau data yang Anda cari sudah tidak tersedia
            atau alamat yang Anda akses tidak ditemukan.
        </div>

        <div class="error-actions">
{{--
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                ← Kembali
            </a> --}}

            <a href="{{ route('orders.index') }}" class="btn btn-primary">
                Daftar Kebutuhan
            </a>

        </div>

    </div>

</div>

</body>
</html>
