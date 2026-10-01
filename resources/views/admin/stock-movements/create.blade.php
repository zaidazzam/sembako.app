<x-app-layout>

    <div class="page-header">
        <div>
            <h1>Tambah Pergerakan Stok</h1>
            <p>Catat stok masuk, stok keluar, atau penyesuaian stok.</p>
        </div>

        <a
            href="{{ route('admin.stock-movements.index') }}"
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
            action="{{ route('admin.stock-movements.store') }}"
        >
            @csrf

            <div class="form-grid">

                <div class="form-group full">
                    <label for="product_id">
                        Produk <span>*</span>
                    </label>

                    <select
                        name="product_id"
                        id="product_id"
                        required
                    >
                        <option value="">Pilih Produk</option>

                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                @selected(old('product_id') == $product->id)
                            >
                                {{ $product->code }}
                                - {{ $product->name }}
                                (Stok: {{ $product->current_stock }} {{ $product->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="type">
                        Tipe Stok <span>*</span>
                    </label>

                    <select
                        name="type"
                        id="type"
                        required
                    >
                        <option value="">Pilih Tipe</option>

                        <option
                            value="in"
                            @selected(old('type') === 'in')
                        >
                            Stok Masuk
                        </option>

                        <option
                            value="out"
                            @selected(old('type') === 'out')
                        >
                            Stok Keluar
                        </option>

                        <option
                            value="adjustment"
                            @selected(old('type') === 'adjustment')
                        >
                            Adjustment
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">
                        Quantity <span>*</span>
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        value="{{ old('quantity') }}"
                        min="0.001"
                        step="0.001"
                        placeholder="Contoh: 10"
                        required
                    >
                </div>

                <div class="form-group full">
                    <label for="notes">
                        Catatan
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        placeholder="Contoh: Pembelian stok dari supplier..."
                    >{{ old('notes') }}</textarea>
                </div>

            </div>

            <div class="info-box">
                <strong>Keterangan:</strong>

                <ul>
                    <li>
                        <b>Stok Masuk</b> → menambah stok.
                    </li>
                    <li>
                        <b>Stok Keluar</b> → mengurangi stok.
                    </li>
                    <li>
                        <b>Adjustment</b> → digunakan untuk koreksi stok.
                    </li>
                </ul>
            </div>

            <div class="form-footer">

                <a
                    href="{{ route('admin.stock-movements.index') }}"
                    class="btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Simpan Stok
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
        .form-group select,
        .form-group textarea {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            outline: none;
            background: #fff;
            font-family: inherit;
        }

        .form-group input,
        .form-group select {
            height: 42px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2563eb;
        }

        .info-box {
            margin-top: 20px;
            padding: 14px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
        }

        .info-box ul {
            margin: 8px 0 0;
            padding-left: 20px;
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
        }
    </style>

</x-app-layout>
