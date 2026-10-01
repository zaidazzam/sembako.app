<x-app-layout>

    @section('title', 'Catat Kebutuhan')
    @section('page-title', 'Catat Kebutuhan')

    @php
        $productData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'unit' => $product->unit,
                'category' => $product->category?->name ?? '-',
            ];
        })->values();
    @endphp


    <div class="create-order-page">

        {{-- ==========================================
             HEADER
        =========================================== --}}

        <div class="page-header">

            <div>

                <div class="breadcrumb">
                    Kebutuhan
                    <span>/</span>
                    Catat Kebutuhan
                </div>

                <h2>Catat Kebutuhan Warung</h2>

                <p>
                    Masukkan produk dan jumlah kebutuhan dari warung.
                </p>

            </div>

            <a
                href="{{ route('orders.index') }}"
                class="btn-secondary"
            >
                ← Kembali
            </a>

        </div>


        {{-- ==========================================
             VALIDATION ERROR
        =========================================== --}}

        @if($errors->any())

            <div class="alert-error">

                <div class="alert-title">
                    Terdapat kesalahan:
                </div>

                <ul>

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ==========================================
             FORM
        =========================================== --}}

        <form
            method="POST"
            action="{{ route('orders.store') }}"
            x-data="createOrderForm()"
        >

            @csrf


            {{-- ======================================
                 INFORMASI KEBUTUHAN
            ======================================= --}}

            <div class="form-card">

                <div class="card-header">

                    <div>

                        <h3>
                            Informasi Kebutuhan
                        </h3>

                        <p>
                            Pilih warung dan tanggal pencatatan.
                        </p>

                    </div>

                </div>


                <div class="form-body">

                    {{-- WARUNG --}}

                    <div class="form-group">

                        <label for="warung_id">
                            Warung <span>*</span>
                        </label>

                        <select
                            id="warung_id"
                            name="warung_id"
                            class="form-control"
                            required
                        >

                            <option value="">
                                -- Pilih Warung --
                            </option>

                            @foreach($warungs as $warung)

                                <option
                                    value="{{ $warung->id }}"
                                    {{ old('warung_id') == $warung->id ? 'selected' : '' }}
                                >
                                    {{ $warung->name }}
                                    ({{ $warung->code }})
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TANGGAL --}}

                    <div class="form-group">

                        <label for="order_date">
                            Tanggal <span>*</span>
                        </label>

                        <input
                            type="date"
                            id="order_date"
                            name="order_date"
                            value="{{ old('order_date', now()->format('Y-m-d')) }}"
                            class="form-control"
                            required
                        >

                    </div>

                </div>

            </div>



            {{-- ======================================
                 PRODUK KEBUTUHAN
            ======================================= --}}

            <div class="form-card">

                <div class="card-header">

                    <div>

                        <h3>
                            Produk Kebutuhan
                        </h3>

                        <p>
                            Tambahkan produk yang dibutuhkan warung.
                        </p>

                    </div>


                    <button
                        type="button"
                        class="btn-primary"
                        @click="addItem()"
                    >
                        ＋ Tambah Produk
                    </button>

                </div>


                <div class="product-list">

                    <template
                        x-for="(item, index) in items"
                        :key="item.key"
                    >

                        <div class="product-row">


                            {{-- ==================================
                                 PRODUK
                            =================================== --}}

                            <div class="form-group product-field">

                                <label>
                                    Produk <span>*</span>
                                </label>

                                <select
                                    :name="`items[${index}][product_id]`"
                                    x-model="item.product_id"
                                    class="form-control"
                                    required
                                >

                                    <option value="">
                                        -- Pilih Produk --
                                    </option>

                                    @foreach($products as $product)

                                        <option
                                            value="{{ $product->id }}"
                                        >
                                            {{ $product->name }}
                                            ({{ $product->unit }})
                                        </option>

                                    @endforeach

                                </select>


                                {{-- HARGA PRODUK --}}

                                <div
                                    class="product-info"
                                    x-show="item.product_id"
                                >

                                    <span
                                        x-text="getProductCategory(item.product_id)"
                                    ></span>

                                    <span class="separator">
                                        •
                                    </span>

                                    <span
                                        x-text="getProductPrice(item.product_id)"
                                    ></span>

                                </div>

                            </div>



                            {{-- ==================================
                                 JUMLAH
                            =================================== --}}

                            <div class="form-group quantity-field">

                                <label>
                                    Jumlah <span>*</span>
                                </label>

                                <input
                                    type="number"
                                    step="0.001"
                                    min="0.001"
                                    :name="`items[${index}][quantity]`"
                                    x-model="item.quantity"
                                    class="form-control"
                                    placeholder="0"
                                    required
                                >

                                <div
                                    class="unit-info"
                                    x-show="item.product_id"
                                    x-text="getProductUnit(item.product_id)"
                                ></div>

                            </div>



                            {{-- ==================================
                                 SUBTOTAL
                            =================================== --}}

                            <div class="form-group subtotal-field">

                                <label>
                                    Subtotal
                                </label>

                                <div
                                    class="subtotal-box"
                                    :class="{ 'empty': !item.product_id }"
                                    x-text="formatRupiah(itemSubtotal(item))"
                                >
                                    Rp 0
                                </div>

                            </div>



                            {{-- ==================================
                                 CATATAN
                            =================================== --}}

                            <div class="form-group notes-field">

                                <label>
                                    Catatan
                                </label>

                                <input
                                    type="text"
                                    :name="`items[${index}][notes]`"
                                    x-model="item.notes"
                                    class="form-control"
                                    placeholder="Opsional"
                                >

                            </div>



                            {{-- ==================================
                                 REMOVE
                            =================================== --}}

                            <div class="remove-field">

                                <button
                                    type="button"
                                    class="btn-remove"
                                    @click="removeItem(index)"
                                    :disabled="items.length <= 1"
                                    title="Hapus produk"
                                >
                                    ×
                                </button>

                            </div>


                        </div>

                    </template>


                    {{-- EMPTY PRODUCT --}}

                    <div
                        class="product-empty"
                        x-show="items.length === 0"
                    >

                        Belum ada produk.
                        Silakan klik
                        <strong>Tambah Produk</strong>.

                    </div>

                </div>

            </div>



            {{-- ======================================
                 CATATAN UMUM
            ======================================= --}}

            <div class="form-card">

                <div class="card-header">

                    <div>

                        <h3>
                            Catatan Umum
                        </h3>

                        <p>
                            Catatan tambahan untuk kebutuhan warung.
                        </p>

                    </div>

                </div>


                <div class="form-body">

                    <div class="form-group full-width">

                        <label for="notes">
                            Catatan
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="form-control"
                            placeholder="Catatan tambahan untuk kebutuhan warung..."
                        >{{ old('notes') }}</textarea>

                    </div>

                </div>

            </div>



            {{-- ======================================
                 TOTAL PESANAN
            ======================================= --}}

            <div class="total-card">

                <div>

                    <div class="total-label">
                        Total Pesanan
                    </div>

                    <div class="total-description">
                        Total berdasarkan jumlah produk dan harga saat ini.
                    </div>

                </div>


                <div
                    class="total-value"
                    x-text="formatRupiah(totalPrice())"
                >
                    Rp 0
                </div>

            </div>



            {{-- ======================================
                 ACTION
            ======================================= --}}

            <div class="form-actions">

                <a
                    href="{{ route('orders.index') }}"
                    class="btn-secondary"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="btn-primary btn-save"
                >
                    ✓ Simpan Kebutuhan
                </button>

            </div>

        </form>

    </div>



    {{-- ==============================================
         JAVASCRIPT
    =============================================== --}}

    <script>

        function createOrderForm() {

            return {

                products: @json($productData),

                items: [
                    {
                        key: Date.now(),
                        product_id: '',
                        quantity: '',
                        notes: ''
                    }
                ],


                /*
                |--------------------------------------------------------------------------
                | TAMBAH PRODUK
                |--------------------------------------------------------------------------
                */

                addItem() {

                    this.items.push({
                        key: Date.now() + Math.random(),
                        product_id: '',
                        quantity: '',
                        notes: ''
                    });

                },


                /*
                |--------------------------------------------------------------------------
                | HAPUS PRODUK
                |--------------------------------------------------------------------------
                */

                removeItem(index) {

                    if (this.items.length <= 1) {
                        return;
                    }

                    this.items.splice(index, 1);

                },


                /*
                |--------------------------------------------------------------------------
                | GET PRODUCT
                |--------------------------------------------------------------------------
                */

                getProduct(productId) {

                    return this.products.find(
                        product =>
                            String(product.id) === String(productId)
                    );

                },


                /*
                |--------------------------------------------------------------------------
                | CATEGORY
                |--------------------------------------------------------------------------
                */

                getProductCategory(productId) {

                    const product = this.getProduct(productId);

                    if (!product) {
                        return '';
                    }

                    return product.category;

                },


                /*
                |--------------------------------------------------------------------------
                | PRICE
                |--------------------------------------------------------------------------
                */

                getProductPrice(productId) {

                    const product = this.getProduct(productId);

                    if (!product) {
                        return '';
                    }

                    return this.formatRupiah(product.price);

                },


                /*
                |--------------------------------------------------------------------------
                | UNIT
                |--------------------------------------------------------------------------
                */

                getProductUnit(productId) {

                    const product = this.getProduct(productId);

                    if (!product) {
                        return '';
                    }

                    return `Satuan: ${product.unit}`;

                },


                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL
                |--------------------------------------------------------------------------
                */

                itemSubtotal(item) {

                    const product = this.getProduct(
                        item.product_id
                    );

                    if (!product) {
                        return 0;
                    }

                    const quantity =
                        parseFloat(item.quantity) || 0;

                    return quantity * product.price;

                },


                /*
                |--------------------------------------------------------------------------
                | TOTAL
                |--------------------------------------------------------------------------
                */

                totalPrice() {

                    return this.items.reduce(
                        (total, item) => {

                            return total +
                                this.itemSubtotal(item);

                        },
                        0
                    );

                },


                /*
                |--------------------------------------------------------------------------
                | RUPIAH
                |--------------------------------------------------------------------------
                */

                formatRupiah(value) {

                    return new Intl.NumberFormat(
                        'id-ID',
                        {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0
                        }
                    ).format(value || 0);

                }

            };

        }

    </script>



    {{-- ==============================================
         CSS
    =============================================== --}}

    <style>

        /* ==========================================
           PAGE
        =========================================== */

        .create-order-page {
            width: 100%;
        }


        /* ==========================================
           HEADER
        =========================================== */

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 20px;
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

        .page-header h2 {
            margin: 0;

            font-size: 20px;
            font-weight: 700;

            color: #111827;
        }

        .page-header p {
            margin: 5px 0 0;

            font-size: 13px;
            color: #6b7280;
        }


        /* ==========================================
           CARD
        =========================================== */

        .form-card {
            margin-bottom: 16px;

            background: #fff;

            border: 1px solid #dfe3e8;
            border-radius: 9px;

            overflow: hidden;

            box-shadow:
                0 1px 3px rgba(0, 0, 0, .03);
        }


        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            min-height: 66px;

            padding: 13px 16px;

            background: #fafbfc;

            border-bottom: 1px solid #e5e7eb;
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


        .form-body {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 18px;

            padding: 18px;
        }


        /* ==========================================
           FORM
        =========================================== */

        .form-group {
            min-width: 0;
        }


        .full-width {
            grid-column: 1 / -1;
        }


        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 11px;
            font-weight: 600;

            color: #374151;
        }


        .form-group label span {
            color: #ef4444;
        }


        .form-control {
            width: 100%;

            min-height: 40px;

            padding: 8px 11px;

            border: 1px solid #d1d5db;
            border-radius: 7px;

            background: #fff;

            color: #1f2937;

            font-size: 12px;

            outline: none;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }


        .form-control:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, .08);
        }


        textarea.form-control {
            resize: vertical;

            min-height: 100px;
        }


        /* ==========================================
           PRODUCT
        =========================================== */

        .product-list {
            padding: 16px;
        }


        .product-row {
            display: grid;

            grid-template-columns:
                2.2fr
                1fr
                1.2fr
                1.7fr
                42px;

            gap: 12px;

            padding: 16px;

            margin-bottom: 10px;

            background: #fff;

            border: 1px solid #e5e7eb;

            border-radius: 8px;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }


        .product-row:last-child {
            margin-bottom: 0;
        }


        .product-row:hover {
            border-color: #d1d5db;

            box-shadow:
                0 2px 5px
                rgba(0, 0, 0, .03);
        }


        .product-info {
            display: flex;
            align-items: center;

            gap: 5px;

            margin-top: 5px;

            font-size: 10px;

            color: #6b7280;
        }


        .product-info .separator {
            color: #d1d5db;
        }


        .unit-info {
            margin-top: 5px;

            font-size: 10px;

            color: #9ca3af;
        }


        /* ==========================================
           SUBTOTAL
        =========================================== */

        .subtotal-box {
            display: flex;
            align-items: center;

            width: 100%;

            min-height: 40px;

            padding: 8px 11px;

            border: 1px solid #dbeafe;
            border-radius: 7px;

            background: #eff6ff;

            color: #2563eb;

            font-size: 12px;
            font-weight: 700;

            white-space: nowrap;
        }


        .subtotal-box.empty {
            background: #f9fafb;

            border-color: #e5e7eb;

            color: #9ca3af;

            font-weight: 400;
        }


        /* ==========================================
           REMOVE
        =========================================== */

        .remove-field {
            display: flex;

            align-items: flex-end;

            justify-content: center;

            padding-bottom: 1px;
        }


        .btn-remove {
            width: 34px;
            height: 34px;

            display: flex;

            align-items: center;
            justify-content: center;

            border: 1px solid #fecaca;

            border-radius: 7px;

            background: #fef2f2;

            color: #dc2626;

            font-size: 20px;

            line-height: 1;

            cursor: pointer;

            transition: all .15s ease;
        }


        .btn-remove:hover:not(:disabled) {
            background: #fee2e2;

            border-color: #fca5a5;
        }


        .btn-remove:disabled {
            opacity: .4;

            cursor: not-allowed;
        }


        /* ==========================================
           EMPTY
        =========================================== */

        .product-empty {
            padding: 30px;

            text-align: center;

            border: 1px dashed #d1d5db;

            border-radius: 8px;

            color: #9ca3af;

            font-size: 12px;
        }


        /* ==========================================
           TOTAL
        =========================================== */

        .total-card {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 16px;

            padding: 18px 20px;

            background: #fff;

            border: 1px solid #dbeafe;

            border-radius: 9px;

            box-shadow:
                0 1px 3px
                rgba(0, 0, 0, .03);
        }


        .total-label {
            font-size: 13px;

            font-weight: 700;

            color: #374151;
        }


        .total-description {
            margin-top: 4px;

            font-size: 10px;

            color: #9ca3af;
        }


        .total-value {
            font-size: 20px;

            font-weight: 800;

            color: #2563eb;

            white-space: nowrap;
        }


        /* ==========================================
           BUTTON
        =========================================== */

        .btn-primary,
        .btn-secondary {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 6px;

            min-height: 38px;

            padding: 0 14px;

            border-radius: 7px;

            font-size: 12px;

            font-weight: 600;

            text-decoration: none;

            cursor: pointer;

            transition: all .15s ease;
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
            background: #f9fafb;

            color: #111827;
        }


        .form-actions {
            display: flex;

            justify-content: flex-end;

            gap: 8px;

            padding-bottom: 20px;
        }


        .btn-save {
            min-width: 160px;
        }


        /* ==========================================
           ERROR
        =========================================== */

        .alert-error {
            margin-bottom: 16px;

            padding: 13px 15px;

            border: 1px solid #fecaca;

            border-radius: 8px;

            background: #fef2f2;

            color: #991b1b;

            font-size: 12px;
        }


        .alert-title {
            margin-bottom: 5px;

            font-weight: 700;
        }


        .alert-error ul {
            margin: 5px 0 0;

            padding-left: 18px;
        }


        /* ==========================================
           RESPONSIVE
        =========================================== */

        @media (max-width: 1100px) {

            .product-row {
                grid-template-columns:
                    2fr
                    1fr
                    1.2fr
                    1.5fr
                    42px;
            }

        }


        @media (max-width: 900px) {

            .product-row {
                position: relative;

                grid-template-columns:
                    1fr 1fr;

                padding-right: 60px;
            }


            .product-field {
                grid-column: 1 / -1;
            }


            .notes-field {
                grid-column: 1 / -1;
            }


            .remove-field {
                position: absolute;

                top: 15px;
                right: 15px;

                padding: 0;
            }

        }


        @media (max-width: 768px) {

            .page-header {
                align-items: stretch;

                flex-direction: column;
            }


            .page-header .btn-secondary {
                width: 100%;
            }


            .form-body {
                grid-template-columns: 1fr;
            }


            .product-row {
                grid-template-columns: 1fr;

                padding-right: 55px;
            }


            .product-field,
            .notes-field {
                grid-column: auto;
            }


            .total-card {
                align-items: flex-start;

                flex-direction: column;
            }


            .total-value {
                font-size: 18px;
            }


            .form-actions {
                flex-direction: column-reverse;
            }


            .form-actions .btn-primary,
            .form-actions .btn-secondary {
                width: 100%;
            }


            .card-header {
                align-items: flex-start;

                flex-direction: column;
            }


            .card-header .btn-primary {
                width: 100%;
            }


            .product-list {
                padding: 12px;
            }


            .product-row {
                padding: 14px;
            }

        }

    </style>

</x-app-layout>
