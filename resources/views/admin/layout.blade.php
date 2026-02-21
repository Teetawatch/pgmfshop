<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - PGMF Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#0d9488',
                        secondary: '#1e293b',
                        accent: '#3b82f6',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                        success: '#10b981'
                    },
                    fontFamily: {
                        'inter': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak]{display:none!important}
        * { font-family: 'Inter', system-ui, sans-serif; }
        .scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        .glass-effect { 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden font-inter" x-data="{ sidebarOpen: false }">
    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside class="w-72 sidebar-gradient text-gray-300 flex flex-col fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out lg:translate-x-0 shadow-2xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-700/50 shrink-0 bg-gradient-to-r from-primary/20 to-transparent">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <div>
                    <span class="text-white font-bold text-lg">PGMF Admin</span>
                    <p class="text-gray-400 text-xs">ระบบจัดการร้านค้า</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-4 space-y-1 px-4 text-sm overflow-y-auto scrollbar-thin">
            <!-- Main Navigation -->
            <div class="space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-home class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">แดชบอร์ด</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-cube class="w-5 h-5 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">สินค้า</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-bars-4 class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">หมวดหมู่</span>
                </a>
                
                <a href="{{ route('admin.stock.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.stock.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-archive-box class="w-5 h-5 {{ request()->routeIs('admin.stock.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">สต็อกสินค้า</span>
                </a>
                
                <a href="{{ route('admin.orders.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">คำสั่งซื้อ</span>
                </a>
                
                <a href="{{ route('admin.shipping.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.shipping.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-truck class="w-5 h-5 {{ request()->routeIs('admin.shipping.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">ค่าจัดส่ง</span>
                </a>
                
                <a href="{{ route('admin.customers.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-user-group class="w-5 h-5 {{ request()->routeIs('admin.customers.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">ลูกค้า</span>
                </a>
                
                <a href="{{ route('admin.reviews.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reviews.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-star class="w-5 h-5 {{ request()->routeIs('admin.reviews.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">รีวิว</span>
                </a>
                
                <a href="{{ route('admin.banners.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.banners.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-photo class="w-5 h-5 {{ request()->routeIs('admin.banners.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">แบนเนอร์</span>
                </a>
                
                <a href="{{ route('admin.coupons.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.coupons.*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                    <x-heroicon-o-tag class="w-5 h-5 {{ request()->routeIs('admin.coupons.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                    <span class="font-medium">คูปอง</span>
                </a>
            </div>

            <!-- Reports Section -->
            <div class="pt-4 mt-4 border-t border-gray-700/50">
                <p class="px-4 py-2 text-xs text-gray-500 uppercase tracking-wider font-semibold">รายงาน</p>
                <div class="space-y-0.5 mt-2">
                    <a href="{{ route('admin.reports.sales') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.sales*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                        <x-heroicon-o-chart-bar class="w-5 h-5 {{ request()->routeIs('admin.reports.sales*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                        <span class="font-medium">รายงานยอดขาย</span>
                    </a>
                    
                    <a href="{{ route('admin.reports.best-selling') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.best-selling*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                        <x-heroicon-o-arrow-trending-up class="w-5 h-5 {{ request()->routeIs('admin.reports.best-selling*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                        <span class="font-medium">สินค้าขายดี</span>
                    </a>
                    
                    <a href="{{ route('admin.reports.low-stock') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.low-stock*') ? 'bg-gradient-to-r from-primary to-teal-600 text-white shadow-lg' : 'hover:bg-gray-700/50 text-gray-300 hover:text-white' }}">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 {{ request()->routeIs('admin.reports.low-stock*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" />
                        <span class="font-medium">สต็อกต่ำ</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Profile Section -->
        <div class="p-4 border-t border-gray-700/50 bg-gradient-to-t from-gray-900/50 to-transparent">
            <div class="flex items-center gap-3 px-2 py-3 text-sm">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-teal-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition-all duration-200 border border-gray-700/50 hover:border-red-500/30">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                    ออกจากระบบ
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-72 h-screen flex flex-col">
        <!-- Header -->
        <header class="h-16 glass-effect border-b border-gray-200/50 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 shadow-soft">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2.5 -ml-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                    <x-heroicon-o-bars-3 class="w-5 h-5" />
                </button>
                <div class="flex items-center gap-3">
                    <div class="w-1 h-8 bg-gradient-to-b from-primary to-teal-600 rounded-full"></div>
                    <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'แดชบอร์ด')</h1>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500 bg-gray-100 px-3 py-2 rounded-lg">
                    <x-heroicon-o-calendar class="w-4 h-4" />
                    <span>{{ now()->format('d/m/Y H:i') }}</span>
                </div>
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-teal-600 rounded-lg flex items-center justify-center text-white text-sm font-bold shadow-md">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto scrollbar-thin bg-gray-50">
            <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-3 shadow-soft animate-in slide-in-from-top-2 duration-300">
                        <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-3 shadow-soft animate-in slide-in-from-top-2 duration-300">
                        <x-heroicon-o-x-circle class="w-5 h-5 text-red-500" />
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm shadow-soft animate-in slide-in-from-top-2 duration-300">
                        <div class="flex items-center gap-3 mb-2">
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-500" />
                            <span class="font-semibold">กรุณาตรวจสอบข้อมูลต่อไปนี้:</span>
                        </div>
                        <div class="space-y-1 ml-8">
                            @foreach($errors->all() as $error)
                                <p class="text-red-600">• {{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="animate-in fade-in-50 duration-300">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    @yield('scripts')
</body>
</html>
