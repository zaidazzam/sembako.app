<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Dashboard') - Sembako App
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f6f8;
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* =========================
           SIDEBAR
        ========================= */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 240px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.25s ease;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 18px;
        }

        .brand-text {
            line-height: 1.2;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .brand-subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }

        .sidebar-menu {
            padding: 18px 12px;
        }

        .menu-section {
            margin-bottom: 22px;
        }

        .menu-title {
            padding: 0 12px;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 42px;
            padding: 9px 12px;
            margin-bottom: 3px;
            border-radius: 8px;
            color: #4b5563;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .menu-link:hover {
            background: #f3f6ff;
            color: #2563eb;
        }

        .menu-link.active {
            background: #eaf1ff;
            color: #2563eb;
            font-weight: 600;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        /* =========================
           USER AREA
        ========================= */
        .sidebar-user {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 12px;
            background: #ffffff;
            border-top: 1px solid #eeeeee;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #eaf1ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .user-info {
            min-width: 0;
            flex: 1;
        }

        .user-name {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .logout-btn {
            border: none;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            padding: 6px;
        }

        .logout-btn:hover {
            color: #dc2626;
        }

        /* =========================
           MAIN CONTENT
        ========================= */
        .app-main {
            margin-left: 240px;
            min-height: 100vh;
            width: calc(100% - 240px);
        }

        .app-header {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
        }

        .page-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .mobile-menu-btn {
            display: none;
            border: none;
            background: transparent;
            font-size: 22px;
            cursor: pointer;
        }

        .app-content {
            padding: 24px 28px;
        }

        /* =========================
           OVERLAY
        ========================= */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 999;
        }

        /* =========================
           MOBILE
        ========================= */
        @media (max-width: 991px) {

            .app-sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .app-sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .app-main {
                margin-left: 0;
                width: 100%;
            }

            .app-header {
                padding: 0 16px;
            }

            .mobile-menu-btn {
                display: block;
            }

            .app-content {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<div
    x-data="{ sidebarOpen: false }"
    @keydown.escape.window="sidebarOpen = false"
>

    {{-- OVERLAY MOBILE --}}
    <div
        class="sidebar-overlay"
        :class="{ 'open': sidebarOpen }"
        @click="sidebarOpen = false"
    ></div>


    {{-- SIDEBAR --}}
    <aside
        class="app-sidebar"
        :class="{ 'open': sidebarOpen }"
    >

        {{-- BRAND --}}
        <div class="sidebar-brand">

            <div class="brand-icon">
                🏪
            </div>

            <div class="brand-text">
                <div class="brand-title">
                    Sembako App
                </div>

                <div class="brand-subtitle">
                    Manajemen Kebutuhan Warung
                </div>
            </div>

        </div>


        {{-- MENU --}}
        <div class="sidebar-menu">

            {{-- DASHBOARD --}}
            <div class="menu-section">

                <a
                    href="{{ route('dashboard') }}"
                    class="menu-link {{ request()->routeIs('admin.dashboard', 'petugas.dashboard', 'warung.dashboard') ? 'active' : '' }}"
                    @click="sidebarOpen = false"
                >
                    <span class="menu-icon">▣</span>
                    <span>Dashboard</span>
                </a>

            </div>


            {{-- KEBUTUHAN WARUNG --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())

                <div class="menu-section">

                    <div class="menu-title">
                        Kebutuhan Warung
                    </div>

                    <a
                        href="{{ route('orders.create') }}"
                        class="menu-link {{ request()->routeIs('orders.create') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <span class="menu-icon">▣</span>
                        <span>Catat Kebutuhan</span>
                    </a>

                    <a
                        href="{{ route('orders.index') }}"
                        class="menu-link {{ request()->routeIs('orders.index', 'orders.show') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <span class="menu-icon">☷</span>
                        <span>Daftar Kebutuhan</span>
                    </a>

                </div>

            @endif


            {{-- DATA MASTER --}}
            @if(auth()->user()->isAdmin())

                <div class="menu-section">

                    <div class="menu-title">
                        Data Master
                    </div>

                    <a
                        href="#"
                        class="menu-link"
                    >
                        <span class="menu-icon">♜</span>
                        <span>Data Warung</span>
                    </a>

                    <a
                        href="#"
                        class="menu-link"
                    >
                        <span class="menu-icon">▤</span>
                        <span>Data Produk</span>
                    </a>

                    <a
                        href="#"
                        class="menu-link"
                    >
                        <span class="menu-icon">▦</span>
                        <span>Kategori Produk</span>
                    </a>

                </div>

            @endif

        </div>


        {{-- USER --}}
        <div class="sidebar-user">

            <div class="user-box">

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="user-info">

                    <div class="user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="user-role">
                        {{ auth()->user()->role->value }}
                    </div>

                </div>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="logout-btn"
                        title="Logout"
                    >
                        ↪
                    </button>

                </form>

            </div>

        </div>

    </aside>


    {{-- MAIN --}}
    <main class="app-main">

        {{-- HEADER --}}
        <header class="app-header">

            <div style="display:flex; align-items:center; gap:12px;">

                <button
                    type="button"
                    class="mobile-menu-btn"
                    @click="sidebarOpen = true"
                >
                    ☰
                </button>

                <h1 class="page-title">
                    @yield('page-title', 'Dashboard')
                </h1>

            </div>

        </header>


        {{-- CONTENT --}}
        <div class="app-content">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div style="
                    margin-bottom:16px;
                    padding:12px 16px;
                    background:#ecfdf5;
                    border:1px solid #a7f3d0;
                    color:#047857;
                    border-radius:8px;
                    font-size:13px;
                ">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ERROR --}}
            @if($errors->any())
                <div style="
                    margin-bottom:16px;
                    padding:12px 16px;
                    background:#fef2f2;
                    border:1px solid #fecaca;
                    color:#b91c1c;
                    border-radius:8px;
                    font-size:13px;
                ">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{ $slot }}

        </div>

    </main>

</div>

</body>
</html>
