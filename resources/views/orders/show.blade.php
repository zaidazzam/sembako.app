<x-app-layout>

    @section('title', 'Detail Kebutuhan')
    @section('page-title', 'Detail Kebutuhan')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

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
                        Detail
                    </span>

                </div>

                <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
                    {{ $order->order_number }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Detail kebutuhan {{ $order->warung->name }}
                </p>

            </div>

            <a
                href="{{ route('orders.index') }}"
                class="
                    inline-flex
                    items-center
                    justify-center
                    gap-2
                    px-4 py-3
                    rounded-xl
                    border border-slate-200
                    bg-white
                    text-sm
                    font-semibold
                    text-slate-600
                    hover:bg-slate-50
                "
            >
                Kembali
            </a>

        </div>


        {{-- Info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Warung --}}
            <div
                class="
                    bg-white
                    rounded-2xl
                    border border-slate-200
                    p-5
                    shadow-sm
                "
            >

                <p class="text-xs font-semibold uppercase text-slate-400">
                    Warung
                </p>

                <p class="mt-2 font-bold text-slate-800">
                    {{ $order->warung->name }}
                </p>

                <p class="text-sm text-slate-400">
                    {{ $order->warung->code }}
                </p>

            </div>


            {{-- Tanggal --}}
            <div
                class="
                    bg-white
                    rounded-2xl
                    border border-slate-200
                    p-5
                    shadow-sm
                "
            >

                <p class="text-xs font-semibold uppercase text-slate-400">
                    Tanggal
                </p>

                <p class="mt-2 font-bold text-slate-800">
                    {{ $order->order_date->format('d F Y') }}
                </p>

            </div>


            {{-- Dibuat Oleh --}}
            <div
                class="
                    bg-white
                    rounded-2xl
                    border border-slate-200
                    p-5
                    shadow-sm
                "
            >

                <p class="text-xs font-semibold uppercase text-slate-400">
                    Dicatat Oleh
                </p>

                <p class="mt-2 font-bold text-slate-800">
                    {{ $order->createdBy->name }}
                </p>

            </div>

        </div>


        {{-- Daftar Produk --}}
        <div
            class="
                bg-white
                rounded-2xl
                border border-slate-200
                shadow-sm
                overflow-hidden
            "
        >

            <div class="px-5 py-4 border-b border-slate-200">

                <h3 class="font-bold text-slate-800">
                    Daftar Kebutuhan
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    {{ $order->items->count() }} jenis produk
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[700px]">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                                Produk
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                                Kategori
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                                Jumlah
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                                Satuan
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                                Catatan
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($order->items as $item)

                            <tr>

                                <td class="px-5 py-4">

                                    <p class="font-semibold text-slate-700">
                                        {{ $item->product->name }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        {{ $item->product->code }}
                                    </p>

                                </td>


                                <td class="px-5 py-4 text-sm text-slate-500">

                                    {{ $item->product->category->name }}

                                </td>


                                <td class="px-5 py-4 text-right">

                                    <span class="font-bold text-slate-800">
                                        {{ rtrim(rtrim(number_format($item->quantity, 3, ',', '.'), '0'), ',') }}
                                    </span>

                                </td>


                                <td class="px-5 py-4 text-sm text-slate-500">

                                    {{ $item->unit }}

                                </td>


                                <td class="px-5 py-4 text-sm text-slate-500">

                                    {{ $item->notes ?: '-' }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Notes --}}
        @if($order->notes)

            <div
                class="
                    bg-white
                    rounded-2xl
                    border border-slate-200
                    p-5
                    shadow-sm
                "
            >

                <p class="text-xs font-semibold uppercase text-slate-400">
                    Catatan Kebutuhan
                </p>

                <p class="mt-2 text-sm text-slate-600 whitespace-pre-line">
                    {{ $order->notes }}
                </p>

            </div>

        @endif

    </div>

</x-app-layout>
