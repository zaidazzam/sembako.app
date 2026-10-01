<x-app-layout>

    @section('title', 'Tambah Kategori')
    @section('page-title', 'Tambah Kategori')

    <div class="form-page">

        <div class="page-header">

            <div>
                <div class="breadcrumb">
                    Data Master
                    <span>/</span>
                    Kategori Produk
                    <span>/</span>
                    Tambah
                </div>

                <h2>Tambah Kategori</h2>

                <p>Tambahkan kategori produk baru.</p>
            </div>

            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                ← Kembali
            </a>

        </div>


        @if ($errors->any())

            <div class="alert-error">

                <strong>Terdapat kesalahan:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="form-card">

                <div class="form-card-header">
                    <h3>Informasi Kategori</h3>
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Nama Kategori <span>*</span>
                        </label>

                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            class="form-control" required>

                    </div>


                    <div class="form-group">

                        <label>
                            Status <span>*</span>
                        </label>

                        <select name="status" class="form-control">

                            <option value="1" @selected(old('status', $category->status ? '1' : '0') === '1')>
                                Aktif
                            </option>

                            <option value="0" @selected(old('status', $category->status ? '1' : '0') === '0')>
                                Nonaktif
                            </option>

                        </select>

                    </div>


                    <div class="form-group full">

                        <label>
                            Deskripsi
                        </label>

                        <textarea name="description" class="form-control textarea" rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>

                </div>

            </div>


            <div class="form-actions">

                <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    Simpan Kategori
                </button>

            </div>

        </form>

    </div>


    <style>
        .form-page {
            width: 100%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 18px;
        }

        .breadcrumb {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 7px;
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

        .btn-secondary {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
        }

        .form-card {
            background: #fff;
            border: 1px solid #dfe3e8;
            border-radius: 8px;
            overflow: hidden;
        }

        .form-card-header {
            padding: 16px 18px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-card-header h3 {
            margin: 0;
            font-size: 14px;
            color: #111827;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 18px;
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
            background: #fff;
            color: #374151;
            font-size: 12px;
            outline: none;
        }

        textarea.form-control {
            height: auto;
            padding: 10px 11px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 15px;
        }

        .alert-error {
            margin-bottom: 15px;
            padding: 12px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 7px;
            font-size: 12px;
        }

        .alert-error ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        @media (max-width: 700px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
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

            .form-actions a,
            .form-actions button {
                width: 100%;
            }
        }
    </style>

</x-app-layout>
