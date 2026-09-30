<x-app-layout>

    @section('title', 'Catat Kebutuhan')
    @section('page-title', 'Catat Kebutuhan')

    <div
        x-data="orderForm()"
        class="space-y-6"
    >

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div>

            <div class="flex items-center gap-2 mb-2">

                <a
                    href="{{ route('orders.index') }}"
                    class="text-sm text-slate-400 hover:text-blue-600"
                >
                    Kebutuhan
                </a>

                <span class="text-slate-300">
                    /
                </span>

                <span class="text-sm text-slate-500">
                    Catat Kebutuhan
                </span>

            </div>

            <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
                Catat Kebutuhan Warung
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Masukkan produk dan jumlah kebutuhan dari warung.
            </p>

        </div>


        {{-- =========================================================
            VALIDATION ERROR
        ========================================================== --}}
        @if($errors->any())

            <div
                class="
                    rounded-xl
                    border border-red-200
                    bg-red-50
                    px-4 py-4
                "
            >

                <p class="font-semibold text-red-700 text-sm">
                    Terdapat kesalahan:
                </p>

                <ul class="mt-2 list-disc list-inside text-sm text-red-600 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
            FORM
        ========================================================== --}}
        <form
            method="POST"
            action="{{ route('orders.store') }}"
        >

            @csrf


            {{-- =====================================================
                INFORMASI WARUNG
            ====================================================== --}}
            <div
                class="
                    bg-white
                    rounded-2xl
                    border border-slate-200
                    shadow-sm
                    p-4 sm:p-6
                "
            >

                <h3 class="text-base font-bold text-slate-800">
                    Informasi Kebutuhan
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    Pilih warung dan tanggal pencatatan.
                </p>


                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Warung --}}
                    <div>

                        <label
                            for="warung_id"
                            class="block text-sm font-semibold text-slate-700 mb-2"
                        >
                            Warung
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="warung_id"
                            name="warung_id"
                            required
                            class="
                                w-full
                                rounded-xl
                                border border-slate-300
                                bg-white
                                px-4 py-3
                                text-sm
                                text-slate-700
                                focus:border-blue-500
                                focus:ring-blue-500
                            "
                        >

                            <option value="">
                                -- Pilih Warung --
                            </option>

                            @foreach($warungs as $warung)

                                <option
                                    value="{{ $warung->id }}"
                                    @selected(old('warung_id') == $warung->id)
                                >
                                    {{ $warung->name }}
                                    ({{ $warung->code }})
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Tanggal --}}
                    <div>

                        <label
                            for="order_date"
                            class="block text-sm font-semibold text-slate-700 mb-2"
                        >
                            Tanggal
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="date"
                            id="order_date"
                            name="order_date"
                            value="{{ old('order_date', now()->format('Y-m-d')) }}"
                            required
                            class="
                                w-full
                                rounded-xl
                                border border-slate-300
                                bg-white
                                px-4 py-3
                                text-sm
                                text-slate-700
                                focus:border-blue-500
                                focus:ring-blue-500
                            "
                        >

                    </div>

                </div>

            </div>


            {{-- =====================================================
                PRODUK
            ====================================================== --}}
            <div
                class="
                    mt-6
                    bg-white
                    rounded-2xl
                    border border-slate-200
                    shadow-sm
                    overflow-hidden
                "
            >

                {{-- Header --}}
                <div
                    class="
                        px-4 sm:px-6
                        py-4
                        border-b border-slate-200
                        flex flex-col sm:flex-row
                        sm:items-center
                        sm:justify-between
                        gap-3
                    "
                >

                    <div>

                        <h3 class="text-base font-bold text-slate-800">
                            Produk Kebutuhan
                        </h3>

                        <p class="mt-1 text-sm text-slate-400">
                            Tambahkan produk yang dibutuhkan warung.
                        </p>

                    </div>


                    {{-- Add Product --}}
                    <button
                        type="button"
                        @click="addItem()"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            gap-2
                            px-4 py-3
                            rounded-xl
                            bg-blue-600
                            text-white
                            text-sm
                            font-semibold
                            hover:bg-blue-700
                            transition
                        "
                    >

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>

                        Tambah Produk

                    </button>

                </div>


                {{-- Product Items --}}
                <div class="p-4 sm:p-6 space-y-4">

                    <template
                        x-for="(item, index) in items"
                        :key="item.key"
                    >

                        <div
                            class="
                                relative
                                rounded-2xl
                                border border-slate-200
                                bg-slate-50
                                p-4
                            "
                        >

                            {{-- Remove --}}
                            <button
                                type="button"
                                @click="removeItem(index)"
                                x-show="items.length > 1"
                                class="
                                    absolute
                                    top-3
                                    right-3
                                    w-9 h-9
                                    rounded-lg
                                    flex items-center justify-center
                                    text-slate-400
                                    hover:text-red-500
                                    hover:bg-red-50
                                    transition
                                "
                                title="Hapus produk"
                            >

                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>

                            </button>


                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 pr-10">

                                {{-- Product --}}
                                <div class="md:col-span-6">

                                    <label
                                        class="
                                            block
                                            text-sm
                                            font-semibold
                                            text-slate-700
                                            mb-2
                                        "
                                    >
                                        Produk
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        :name="`items[${index}][product_id]`"
                                        x-model="item.product_id"
                                        required
                                        class="
                                            w-full
                                            rounded-xl
                                            border border-slate-300
                                            bg-white
                                            px-4 py-3
                                            text-sm
                                            text-slate-700
                                            focus:border-blue-500
                                            focus:ring-blue-500
                                        "
                                    >

                                        <option value="">
                                            -- Pilih Produk --
                                        </option>

                                        @foreach($products as $product)

                                            <option
                                                value="{{ $product->id }}"
                                            >
                                                {{ $product->name }}
                                                -
                                                {{ $product->unit }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Quantity --}}
                                <div class="md:col-span-3">

                                    <label
                                        class="
                                            block
                                            text-sm
                                            font-semibold
                                            text-slate-700
                                            mb-2
                                        "
                                    >
                                        Jumlah
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="number"
                                        step="0.001"
                                        min="0.001"
                                        :name="`items[${index}][quantity]`"
                                        x-model="item.quantity"
                                        required
                                        placeholder="0"
                                        class="
                                            w-full
                                            rounded-xl
                                            border border-slate-300
                                            bg-white
                                            px-4 py-3
                                            text-sm
                                            text-slate-700
                                            focus:border-blue-500
                                            focus:ring-blue-500
                                        "
                                    >

                                </div>


                                {{-- Notes --}}
                                <div class="md:col-span-3">

                                    <label
                                        class="
                                            block
                                            text-sm
                                            font-semibold
                                            text-slate-700
                                            mb-2
                                        "
                                    >
                                        Catatan
                                    </label>

                                    <input
                                        type="text"
                                        :name="`items[${index}][notes]`"
                                        x-model="item.notes"
                                        placeholder="Opsional"
                                        class="
                                            w-full
                                            rounded-xl
                                            border border-slate-300
                                            bg-white
                                            px-4 py-3
                                            text-sm
                                            text-slate-700
                                            focus:border-blue-500
                                            focus:ring-blue-500
                                        "
                                    >

                                </div>

                            </div>

                        </div>

                    </template>


                    {{-- Empty --}}
                    <div
                        x-show="items.length === 0"
                        class="
                            py-10
                            text-center
                            text-sm
                            text-slate-400
                        "
                    >
                        Belum ada produk.
                    </div>

                </div>

            </div>


            {{-- =====================================================
                CATATAN UMUM
            ====================================================== --}}
            <div
                class="
                    mt-6
                    bg-white
                    rounded-2xl
                    border border-slate-200
                    shadow-sm
                    p-4 sm:p-6
                "
            >

                <label
                    for="notes"
                    class="
                        block
                        text-sm
                        font-semibold
                        text-slate-700
                        mb-2
                    "
                >
                    Catatan Umum
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    placeholder="Catatan tambahan untuk kebutuhan warung..."
                    class="
                        w-full
                        rounded-xl
                        border border-slate-300
                        bg-white
                        px-4 py-3
                        text-sm
                        text-slate-700
                        focus:border-blue-500
                        focus:ring-blue-500
                    "
                >{{ old('notes') }}</textarea>

            </div>


            {{-- =====================================================
                ACTION
            ====================================================== --}}
            <div
                class="
                    mt-6
                    flex flex-col-reverse
                    sm:flex-row
                    sm:justify-end
                    gap-3
                "
            >

                <a
                    href="{{ route('orders.index') }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        px-5 py-3
                        rounded-xl
                        border border-slate-200
                        bg-white
                        text-sm
                        font-semibold
                        text-slate-600
                        hover:bg-slate-50
                    "
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        px-5 py-3
                        rounded-xl
                        bg-blue-600
                        text-white
                        text-sm
                        font-semibold
                        hover:bg-blue-700
                        active:bg-blue-800
                        transition
                    "
                >

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    Simpan Kebutuhan

                </button>

            </div>

        </form>

    </div>


    {{-- =============================================================
        ALPINE JS
    ============================================================= --}}
    <script>

        function orderForm() {

            return {

                items: [
                    {
                        key: Date.now(),
                        product_id: '',
                        quantity: '',
                        notes: ''
                    }
                ],

                addItem() {

                    this.items.push({

                        key: Date.now() + Math.random(),

                        product_id: '',

                        quantity: '',

                        notes: ''

                    });

                },

                removeItem(index) {

                    if (this.items.length <= 1) {
                        return;
                    }

                    this.items.splice(index, 1);

                }

            };

        }

    </script>

</x-app-layout>
