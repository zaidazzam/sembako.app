<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <p class="text-sm text-slate-400 mb-1">
                    Dashboard
                </p>

                <h2 class="text-2xl font-bold text-slate-800">
                    Dashboard Admin
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Kelola kebutuhan barang dan data warung.
                </p>
            </div>

            <a
                href="{{ route('orders.create') }}"
                class="inline-flex items-center justify-center gap-2
                       px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold
                       rounded-xl hover:bg-blue-700 transition shadow-sm"
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
                        d="M12 5v14M5 12h14"
                    />
                </svg>

                Catat Kebutuhan

            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- Welcome --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-6">

                <div class="flex items-start gap-4">

                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">

                        <svg
                            class="w-6 h-6 text-blue-600"
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

                    </div>

                    <div>

                        <h3 class="text-lg font-bold text-slate-800">
                            Selamat datang, {{ auth()->user()->name }} 👋
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Kelola pencatatan kebutuhan warung dengan mudah melalui dashboard ini.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">


                {{-- Warung --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-slate-500">
                                Total Warung
                            </p>

                            <p class="text-3xl font-bold text-slate-800 mt-2">
                                -
                            </p>

                        </div>

                        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">

                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"
                                />
                            </svg>

                        </div>

                    </div>

                    <p class="text-xs text-slate-400 mt-3">
                        Warung aktif
                    </p>

                </div>


                {{-- Kebutuhan --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-slate-500">
                                Kebutuhan Hari Ini
                            </p>

                            <p class="text-3xl font-bold text-slate-800 mt-2">
                                -
                            </p>

                        </div>

                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">

                            <svg
                                class="w-6 h-6 text-emerald-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 016 0M9 5h6M9 12h6M9 16h4"
                                />
                            </svg>

                        </div>

                    </div>

                    <p class="text-xs text-slate-400 mt-3">
                        Pencatatan hari ini
                    </p>

                </div>


                {{-- Produk --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-slate-500">
                                Total Produk
                            </p>

                            <p class="text-3xl font-bold text-slate-800 mt-2">
                                -
                            </p>

                        </div>

                        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">

                            <svg
                                class="w-6 h-6 text-amber-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"
                                />
                            </svg>

                        </div>

                    </div>

                    <p class="text-xs text-slate-400 mt-3">
                        Produk aktif
                    </p>

                </div>


                {{-- Petugas --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-slate-500">
                                Petugas
                            </p>

                            <p class="text-3xl font-bold text-slate-800 mt-2">
                                -
                            </p>

                        </div>

                        <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center">

                            <svg
                                class="w-6 h-6 text-violet-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20a4 4 0 00-8 0M13 12a4 4 0 100-8 4 4 0 000 8zm7 8a4 4 0 00-3-3.87M17 4a4 4 0 010 8"
                                />
                            </svg>

                        </div>

                    </div>

                    <p class="text-xs text-slate-400 mt-3">
                        Petugas aktif
                    </p>

                </div>

            </div>


            {{-- Quick Menu --}}
            <div class="mb-8">

                <div class="mb-4">

                    <h3 class="text-lg font-bold text-slate-800">
                        Menu Cepat
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Akses fitur yang sering digunakan.
                    </p>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- Catat --}}
                    <a
                        href="{{ route('orders.create') }}"
                        class="group bg-white border border-slate-200 rounded-2xl p-6
                               shadow-sm hover:shadow-md hover:border-blue-200 transition"
                    >

                        <div class="flex items-start gap-4">

                            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center
                                        group-hover:bg-blue-100 transition">

                                <svg
                                    class="w-6 h-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 5v14M5 12h14"
                                    />
                                </svg>

                            </div>

                            <div class="flex-1">

                                <h4 class="font-semibold text-slate-800">
                                    Catat Kebutuhan
                                </h4>

                                <p class="text-sm text-slate-500 mt-1">
                                    Catat kebutuhan barang untuk masing-masing warung.
                                </p>

                            </div>

                            <svg
                                class="w-5 h-5 text-slate-300 group-hover:text-blue-600 transition"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </div>

                    </a>


                    {{-- Daftar --}}
                    <a
                        href="{{ route('orders.index') }}"
                        class="group bg-white border border-slate-200 rounded-2xl p-6
                               shadow-sm hover:shadow-md hover:border-emerald-200 transition"
                    >

                        <div class="flex items-start gap-4">

                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center
                                        group-hover:bg-emerald-100 transition">

                                <svg
                                    class="w-6 h-6 text-emerald-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"
                                    />
                                </svg>

                            </div>

                            <div class="flex-1">

                                <h4 class="font-semibold text-slate-800">
                                    Kebutuhan Warung
                                </h4>

                                <p class="text-sm text-slate-500 mt-1">
                                    Lihat seluruh pencatatan kebutuhan warung.
                                </p>

                            </div>

                            <svg
                                class="w-5 h-5 text-slate-300 group-hover:text-emerald-600 transition"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </div>

                    </a>

                </div>

            </div>


            {{-- Info --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">

                <div class="flex gap-3">

                    <svg
                        class="w-5 h-5 text-blue-600 mt-0.5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
                        />
                    </svg>

                    <div>

                        <p class="text-sm font-semibold text-blue-800">
                            Informasi
                        </p>

                        <p class="text-sm text-blue-700 mt-1">
                            Gunakan menu Catat Kebutuhan untuk memasukkan kebutuhan barang dari setiap warung.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
