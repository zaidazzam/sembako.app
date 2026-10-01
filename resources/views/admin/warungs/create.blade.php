<x-app-layout>

    @section('title', 'Tambah Warung')
    @section('page-title', 'Tambah Warung')

    <div class="form-page">

        <div class="page-header">

            <div>
                <div class="breadcrumb">
                    Master Data
                    <span>/</span>
                    Warung
                    <span>/</span>
                    Tambah
                </div>

                <h2>Tambah Warung</h2>

                <p>
                    Tambahkan data warung dan akun pemilik.
                </p>
            </div>

            <a
                href="{{ route('admin.warungs.index') }}"
                class="btn-secondary"
            >
                ← Kembali
            </a>

        </div>


        @if($errors->any())

            <div class="alert alert-error">

                <strong>
                    Terdapat kesalahan:
                </strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('admin.warungs.store') }}"
        >

            @csrf

            <div class="form-card">

                <div class="form-card-header">
                    <h3>Informasi Warung</h3>
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="name">
                            Nama Warung <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control"
                            placeholder="Contoh: Warung A"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="code">
                            Kode Warung <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
                            class="form-control"
                            placeholder="Contoh: WAR-004"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="phone">
                            No. Telepon
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="form-control"
                            placeholder="08xxxxxxxxxx"
                        >

                    </div>


                    <div class="form-group">

                        <label for="status">
                            Status <span>*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-control"
                        >
                            <option value="1" @selected(old('status', '1') == '1')>
                                Aktif
                            </option>

                            <option value="0" @selected(old('status') === '0')>
                                Nonaktif
                            </option>
                        </select>

                    </div>


                    <div class="form-group full">

                        <label for="address">
                            Alamat
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            class="form-control textarea"
                            rows="3"
                            placeholder="Alamat lengkap warung..."
                        >{{ old('address') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="form-card">

                <div class="form-card-header">

                    <h3>Akun Pemilik Warung</h3>

                    <p>
                        Akun ini digunakan pemilik untuk login.
                    </p>

                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="owner_name">
                            Nama Pemilik <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="owner_name"
                            name="owner_name"
                            value="{{ old('owner_name') }}"
                            class="form-control"
                            placeholder="Nama pemilik"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="email">
                            Email Login <span>*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control"
                            placeholder="pemilik@email.com"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="password">
                            Password <span>*</span>
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Minimal 8 karakter"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="password_confirmation">
                            Konfirmasi Password <span>*</span>
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Ulangi password"
                            required
                        >

                    </div>

                </div>

            </div>


            <div class="form-actions">

                <a
                    href="{{ route('admin.warungs.index') }}"
                    class="btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Simpan Warung
                </button>

            </div>

        </form>

    </div>


    <style>

        .form-page {
            padding: 0;
        }

        /* HEADER */

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
        }

        .alert-error {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .alert ul {
            margin: 7px 0 0;
            padding-left: 18px;
        }

        .alert li {
            margin-bottom: 3px;
        }


        /* FORM CARD */

        .form-card {
            margin-bottom: 15px;

            background: #ffffff;
            border: 1px solid #dfe3e8;
            border-radius: 8px;
            overflow: hidden;
        }

        .form-card-header {
            padding: 16px 18px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            border-bottom: 1px solid #e5e7eb;
        }

        .form-card-header h3 {
            margin: 0;

            font-size: 14px;
            color: #111827;
        }

        .form-card-header p {
            margin: 4px 0 0;

            font-size: 11px;
            color: #94a3b8;
        }

        .code-badge {
            padding: 5px 9px;

            border-radius: 5px;

            background: #eff6ff;
            color: #2563eb;

            font-size: 10px;
            font-weight: 700;
        }


        /* FORM */

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;

            padding: 18px;
        }

        .form-group {
            min-width: 0;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;

            margin-bottom: 6px;

            font-size: 11px;
            font-weight: 600;

            color: #374151;
        }

        .form-group label span {
            color: #dc2626;
        }

        .form-control {
            width: 100%;
            height: 39px;

            padding: 0 11px;

            border: 1px solid #cfd6df;
            border-radius: 6px;

            background: #ffffff;
            color: #374151;

            font-size: 12px;

            outline: none;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 2px rgba(37, 99, 235, .1);
        }

        textarea.form-control {
            height: auto;
            min-height: 85px;

            padding: 10px 11px;

            resize: vertical;
        }

        .form-help {
            display: block;

            margin-top: 5px;

            font-size: 10px;
            color: #94a3b8;
        }


        /* INFO */

        .info-card {
            margin-bottom: 15px;
            padding: 13px 15px;

            display: flex;
            align-items: flex-start;
            gap: 10px;

            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 7px;

            color: #1e40af;
        }

        .info-icon {
            font-size: 15px;
        }

        .info-card strong {
            display: block;

            margin-bottom: 2px;

            font-size: 11px;
        }

        .info-card p {
            margin: 0;

            font-size: 11px;
            line-height: 1.5;
        }


        /* ACTION */

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;

            margin-top: 5px;
        }


        /* RESPONSIVE */

        @media (max-width: 700px) {

            .page-header {
                align-items: stretch;
                flex-direction: column;
            }

            .page-header .btn-secondary {
                width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn-primary,
            .form-actions .btn-secondary {
                width: 100%;
            }

        }

    </style>

</x-app-layout>
