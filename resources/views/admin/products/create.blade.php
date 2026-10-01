<x-app-layout>

    <div class="page-header">
        <div>
            <h1>Tambah Produk</h1>
            <p>Tambahkan produk baru ke data master.</p>
        </div>

        <a
            href="{{ route('admin.products.index') }}"
            class="btn-secondary"
        >
            ← Kembali
        </a>
    </div>

    <div class="form-card">

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Terdapat kesalahan:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.products.store') }}"
        >
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="category_id">
                        Kategori <span>*</span>
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        required
                    >
                        <option value="">Pilih kategori</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="code">
                        Kode Produk <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="Contoh: MIE-001"
                        required
                    >
                </div>

                <div class="form-group full">
                    <label for="name">
                        Nama Produk <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Mi Instan Goreng"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="unit">
                        Satuan <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="unit"
                        name="unit"
                        value="{{ old('unit') }}"
                        placeholder="Contoh: dus, kg, pcs"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="price">
                        Harga <span>*</span>
                    </label>

                    <input
                        type="number"
                        id="price"
                        name="price"
                        value="{{ old('price', 0) }}"
                        min="0"
                        step="0.01"
                        placeholder="0"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="minimum_stock">
                        Minimum Stok <span>*</span>
                    </label>

                    <input
                        type="number"
                        id="minimum_stock"
                        name="minimum_stock"
                        value="{{ old('minimum_stock', 0) }}"
                        min="0"
                        step="0.01"
                        placeholder="0"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="status">
                        Status <span>*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                    >
                        <option value="1" @selected(old('status', '1') == '1')}>
                            Aktif
                        </option>

                        <option value="0" @selected(old('status') === '0')}>
                            Nonaktif
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-footer">
                <a
                    href="{{ route('admin.products.index') }}"
                    class="btn-secondary"
                >
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    Simpan Produk
                </button>
            </div>

        </form>

    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0 0 5px;
            font-size: 26px;
            font-weight: 700;
        }

        .page-header p {
            margin: 0;
            color: #64748b;
        }

        .form-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .form-group label span {
            color: #dc2626;
        }

        .form-group input,
        .form-group select {
            height: 42px;
            padding: 0 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            outline: none;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2563eb;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 16px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 14px;
        }

        .alert ul {
            margin: 8px 0 0;
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .page-header {
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>

</x-app-layout>
