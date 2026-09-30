<x-app-layout>

    @section('title', 'Daftar Kebutuhan')
    @section('page-title', 'Daftar Kebutuhan')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
                    Daftar Kebutuhan
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Daftar kebutuhan barang dari setiap warung.
                </p>
            </div>

            <a
                href="{{ route('orders.create') }}"
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

                Catat Kebutuhan
            </a>

        </div>


        {{-- Success --}}
        @if(session('success'))

            <div
                class="
                    rounded-xl
                    border border-green-200
                    bg-green-50
                    px-4 py-3
                    text-sm
                    text-green-700
                "
            >
                {{ session('success') }}
            </div>

        @endif


        {{-- Table --}}
        <div
            class="
                bg-white
                rounded-2xl
                border border-slate-200
                shadow-sm
                overflow-hidden
            "
        >

            {{-- Mobile scroll --}}
            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px]">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                No. Kebutuhan
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                Warung
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                Jumlah Item
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                Dibuat Oleh
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold text-slate-500 uppercase">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($orders as $order)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- Order Number --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-slate-700">
                                        {{ $order->order_number }}
                                    </div>

                                </td>


                                {{-- Warung --}}
                                <td class="px-5 py-4">

                                    <div class="font-medium text-slate-700">
                                        {{ $order->warung->name }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        {{ $order->warung->code }}
                                    </div>

                                </td>


                                {{-- Date --}}
                                <td class="px-5 py-4">

                                    <span class="text-sm text-slate-600">
                                        {{ $order->order_date->format('d/m/Y') }}
                                    </span>

                                </td>


                                {{-- Items --}}
                                <td class="px-5 py-4">

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            rounded-full
                                            bg-blue-50
                                            px-3 py-1
                                            text-xs
                                            font-semibold
                                            text-blue-600
                                        "
                                    >
                                        {{ $order->items->count() }} produk
                                    </span>

                                </td>


                                {{-- Created By --}}
                                <td class="px-5 py-4">

                                    <div class="text-sm text-slate-600">
                                        {{ $order->createdBy->name }}
                                    </div>

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4">

                                    @php
                                        $status = $order->status->value;
                                    @endphp

                                    @if($status === 'submitted')

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-blue-50
                                                px-3 py-1
                                                text-xs
                                                font-semibold
                                                text-blue-600
                                            "
                                        >
                                            Terkirim
                                        </span>

                                    @elseif($status === 'processing')

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-yellow-50
                                                px-3 py-1
                                                text-xs
                                                font-semibold
                                                text-yellow-600
                                            "
                                        >
                                            Diproses
                                        </span>

                                    @elseif($status === 'ready')

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-green-50
                                                px-3 py-1
                                                text-xs
                                                font-semibold
                                                text-green-600
                                            "
                                        >
                                            Siap
                                        </span>

                                    @elseif($status === 'completed')

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-green-100
                                                px-3 py-1
                                                text-xs
                                                font-semibold
                                                text-green-700
                                            "
                                        >
                                            Selesai
                                        </span>

                                    @elseif($status === 'cancelled')

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-red-50
                                                px-3 py-1
                                                text-xs
                                                font-semibold
                                                text-red-600
                                            "
                                        >
                                            Dibatalkan
                                        </span>

                                    @else

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-slate-100
                                                px-3 py-1
                                                text-xs
                                                font-semibold
                                                text-slate-600
                                            "
                                        >
                                            Draft
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-4 text-right">

                                    <a
                                        href="{{ route('orders.show', $order) }}"
                                        class="
                                            inline-flex
                                            items-center
                                            justify-center
                                            w-9 h-9
                                            rounded-lg
                                            text-slate-500
                                            hover:bg-blue-50
                                            hover:text-blue-600
                                            transition
                                        "
                                        title="Lihat Detail"
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
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="px-5 py-12 text-center"
                                >

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="
                                                w-14 h-14
                                                rounded-full
                                                bg-slate-100
                                                flex items-center justify-center
                                            "
                                        >

                                            <svg
                                                class="w-7 h-7 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0118 8.414V19a2 2 0 01-2 2z"
                                                />
                                            </svg>

                                        </div>

                                        <h3 class="mt-4 font-semibold text-slate-700">
                                            Belum ada kebutuhan
                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">
                                            Silakan catat kebutuhan warung terlebih dahulu.
                                        </p>

                                        <a
                                            href="{{ route('orders.create') }}"
                                            class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700"
                                        >
                                            + Catat Kebutuhan
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($orders->hasPages())

                <div
                    class="
                        border-t border-slate-200
                        px-4 sm:px-5
                        py-4
                    "
                >
                    {{ $orders->links() }}
                </div>

            @endif

        </div>

    </div>

</x-app-layout>
