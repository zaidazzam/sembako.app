<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Sembako App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <div
        x-data="{ sidebarOpen: false }"
        class="min-h-screen"
    >

        {{-- Mobile Overlay --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
        ></div>


        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200
                   transform transition-transform duration-200 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >

            {{-- Logo --}}
            <div class="h-20 px-6 flex items-center border-b border-slate-100">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">

                        {{-- Store Icon --}}
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 10l2-6h14l2 6M5 10v10h14V10M3 10h18M9 20v-6h6v6"
                            />
                        </svg>

                    </div>

                    <div>
                        <h1 class="font-bold text-slate-800 text-lg leading-tight">
                            Sembako App
                        </h1>

                        <p class="text-xs text-slate-400">
                            Manajemen Kebutuhan Warung
                        </p>
                    </div>

                </div>

            </div>


            {{-- Navigation --}}
            <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-160px)]">

                {{-- Dashboard --}}
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl
                           {{ request()->routeIs('admin.dashboard')
                                ? 'bg-blue-50 text-blue-600'
                                : 'text-slate-600 hover:bg-slate-50' }}"
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
                            d="M3 13h8V3H3v10zm10 8h8V11h-8v10zM3 21h8v-6H3v6zm10-12h8V3h-8v6z"
                        />
                    </svg>

                    <span class="text-sm font-medium">
                        Dashboard
                    </span>

                </a>


                {{-- Kebutuhan Warung --}}
                <div class="pt-4 pb-2 px-4">

                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Kebutuhan Warung
                    </p>

                </div>


                {{-- Catat Kebutuhan --}}
                <a
                    href="{{ route('orders.create') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl
                           {{ request()->routeIs('orders.create')
                                ? 'bg-blue-50 text-blue-600'
                                : 'text-slate-600 hover:bg-slate-50' }}"
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
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 016 0M9 5h6M9 12h6M9 16h4"
                        />
                    </svg>

                    <span class="text-sm font-medium">
                        Catat Kebutuhan
                    </span>

                </a>


                {{-- Daftar Kebutuhan --}}
                <a
                    href="{{ route('orders.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl
                           {{ request()->routeIs('orders.index')
                                ? 'bg-blue-50 text-blue-600'
                                : 'text-slate-600 hover:bg-slate-50' }}"
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
                            d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"
                        />
                    </svg>

                    <span class="text-sm font-medium">
                        Daftar Kebutuhan
                    </span>

                </a>


                {{-- Data --}}
                <div class="pt-4 pb-2 px-4">

                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Data Master
                    </p>

                </div>


                {{-- Warung --}}
                <a
                    href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50"
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
                            d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01M9 12h.01M15 12h.01"
                        />
                    </svg>

                    <span class="text-sm font-medium">
                        Data Warung
                    </span>

                </a>


                {{-- Produk --}}
                <a
                    href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50"
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
                            d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"
                        />
                    </svg>

                    <span class="text-sm font-medium">
                        Data Produk
                    </span>

                </a>

            </nav>


            {{-- User --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-100 bg-white">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-slate-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19a6 6 0 00-12 0M9 13a4 4 0 100-8 4 4 0 000 8zm6-6h6m-3-3v6"
                            />
                        </svg>

                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="text-sm font-semibold text-slate-700 truncate">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-slate-400 truncate">
                            {{ auth()->user()->email }}
                        </p>

                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            title="Logout"
                            class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50"
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
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"
                                />
                            </svg>
                        </button>

                    </form>

                </div>

            </div>

        </aside>


        {{-- Main --}}
        <div class="lg:pl-64 min-h-screen">

            {{-- Topbar --}}
            <header class="h-20 bg-white border-b border-slate-200 sticky top-0 z-30">

                <div class="h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">

                    {{-- Mobile Button --}}
                    <button
                        @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-lg hover:bg-slate-100"
                    >
                        <svg
                            class="w-6 h-6 text-slate-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>


                    {{-- Page Header --}}
                    <div class="hidden lg:block">

                        <p class="text-sm text-slate-400">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </p>

                    </div>


                    {{-- User --}}
                    <div class="flex items-center gap-4 ml-auto">

                        {{-- Notification --}}
                        <button
                            class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100"
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
                                    d="M15 17h5l-1.5-2V10a6.5 6.5 0 00-13 0v5L4 17h5m6 0a3 3 0 01-6 0"
                                />
                            </svg>

                        </button>

                        <div class="h-7 w-px bg-slate-200"></div>

                        <div class="flex items-center gap-3">

                            <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center">

                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 21a8 8 0 10-16 0m8-12a4 4 0 100-8 4 4 0 000 8z"
                                    />
                                </svg>

                            </div>

                            <div class="hidden sm:block">

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ auth()->user()->name }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    Administrator
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </header>


            {{-- Page Content --}}
            <main>

                @isset($header)
                    <div class="bg-white border-b border-slate-200">

                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                            {{ $header }}
                        </div>

                    </div>
                @endisset

                {{ $slot }}

            </main>

        </div>

    </div>

</body>

</html>
