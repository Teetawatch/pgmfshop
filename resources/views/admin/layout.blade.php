<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - PGMF Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#0d9488', danger: '#ef4444' }
                }
            }
        }
    </script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ sidebarOpen: false }">
    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-gray-300 flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-200 ease-in-out lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <div class="h-14 flex items-center justify-between px-5 border-b border-gray-800 shrink-0">
            <span class="text-white font-bold text-lg">PGMF Admin</span>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>
        <nav class="flex-1 py-4 space-y-1 px-3 text-sm overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-home class="w-4 h-4" />
                แดชบอร์ด
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-cube class="w-4 h-4" />
                สินค้า
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-bars-4 class="w-4 h-4" />
                หมวดหมู่
            </a>
            <a href="{{ route('admin.stock.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.stock.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-archive-box class="w-4 h-4" />
                สต็อกสินค้า
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-clipboard-document-list class="w-4 h-4" />
                คำสั่งซื้อ
            </a>
            <a href="{{ route('admin.shipping.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.shipping.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-truck class="w-4 h-4" />
                ค่าจัดส่ง
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-user-group class="w-4 h-4" />
                ลูกค้า
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-star class="w-4 h-4" />
                รีวิว
            </a>
            <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.banners.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-photo class="w-4 h-4" />
                แบนเนอร์
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.coupons.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <x-heroicon-o-tag class="w-4 h-4" />
                คูปอง
            </a>

            <div class="pt-2 mt-2 border-t border-gray-800">
                <p class="px-3 py-1.5 text-xs text-gray-500 uppercase tracking-wider">รายงาน</p>
                <a href="{{ route('admin.reports.sales') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.sales*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                    <x-heroicon-o-chart-bar class="w-4 h-4" />
                    รายงานยอดขาย
                </a>
                <a href="{{ route('admin.reports.best-selling') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.best-selling*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                    <x-heroicon-o-arrow-trending-up class="w-4 h-4" />
                    สินค้าขายดี
                </a>
                <a href="{{ route('admin.reports.low-stock') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.low-stock*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                    <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                    สต็อกต่ำ
                </a>
            </div>
        </nav>
        <div class="p-3 border-t border-gray-800">
            <div class="flex items-center gap-3 px-3 py-2 text-sm">
                <div class="w-7 h-7 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500 text-[10px] truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-1.5 text-xs text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg">
                    ออกจากระบบ
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <header class="h-14 bg-white border-b flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-1.5 -ml-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                    <x-heroicon-o-bars-3 class="w-5 h-5" />
                </button>
                <h1 class="text-sm font-semibold text-gray-700">@yield('title', 'แดชบอร์ด')</h1>
            </div>
            <span class="text-xs text-gray-400 hidden sm:inline">{{ now()->format('d/m/Y H:i') }}</span>
        </header>

        <main class="p-4 sm:p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
