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
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-60 bg-gray-900 text-gray-300 flex flex-col fixed inset-y-0 left-0 z-30">
        <div class="h-14 flex items-center px-5 border-b border-gray-800">
            <span class="text-white font-bold text-lg">PGMF Admin</span>
        </div>
        <nav class="flex-1 py-4 space-y-1 px-3 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                แดชบอร์ด
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                สินค้า
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                หมวดหมู่
            </a>
            <a href="{{ route('admin.stock.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.stock.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                สต็อกสินค้า
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                คำสั่งซื้อ
            </a>
            <a href="{{ route('admin.shipping.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.shipping.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                ค่าจัดส่ง
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                ลูกค้า
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                รีวิว
            </a>
            <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.banners.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                แบนเนอร์
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.coupons.*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                คูปอง
            </a>

            <div class="pt-2 mt-2 border-t border-gray-800">
                <p class="px-3 py-1.5 text-xs text-gray-500 uppercase tracking-wider">รายงาน</p>
                <a href="{{ route('admin.reports.sales') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.sales*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    รายงานยอดขาย
                </a>
                <a href="{{ route('admin.reports.best-selling') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.best-selling*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    สินค้าขายดี
                </a>
                <a href="{{ route('admin.reports.low-stock') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.low-stock*') ? 'bg-primary text-white' : 'hover:bg-gray-800' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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
    <div class="flex-1 ml-60">
        <header class="h-14 bg-white border-b flex items-center justify-between px-6 sticky top-0 z-20">
            <h1 class="text-sm font-semibold text-gray-700">@yield('title', 'แดชบอร์ด')</h1>
            <span class="text-xs text-gray-400">{{ now()->format('d/m/Y H:i') }}</span>
        </header>

        <main class="p-6">
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
