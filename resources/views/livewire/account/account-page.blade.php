@php $user = auth()->user(); @endphp

<div class="min-h-screen bg-gray-50">

    {{-- ===== HERO HEADER ===== --}}
    <div class="relative overflow-hidden h-64 flex items-center"
         style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%);">
        <div class="absolute inset-0"
             style="background-image:url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff6b00' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);">
        </div>
        <div class="relative z-10 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 pb-8">

                {{-- Avatar --}}
                <div class="shrink-0">
                    @if($user->social_avatar || $user->avatar)
                        <img src="{{ $user->social_avatar ?: $user->avatar }}"
                             alt="{{ $user->name }}"
                             class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-white object-cover shadow-xl"
                             referrerpolicy="no-referrer" />
                    @else
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-white bg-linear-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-xl">
                            <span class="text-white text-4xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Name & Email --}}
                <div class="text-center sm:text-left text-white mb-2">
                    <h1 class="text-3xl font-bold tracking-tight mb-1">{{ $user->name }}</h1>
                    <div class="flex items-center justify-center sm:justify-start gap-2 text-gray-300 text-sm">
                        <span class="material-icons-outlined text-base">email</span>
                        {{ $user->email }}
                    </div>
                </div>

                {{-- Social badge --}}
                @if($user->social_provider)
                    <div class="sm:ml-auto mb-4 sm:mb-8">
                        @if($user->social_provider === 'facebook')
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-full shadow-lg transition-all transform hover:scale-105">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                เชื่อมต่อ Facebook
                            </span>
                        @elseif($user->social_provider === 'google')
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full shadow-lg">
                                <svg class="w-4 h-4" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57C21.36 18.45 22.56 15.56 22.56 12.25z" fill="#fff"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#fff"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#fff"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#fff"/></svg>
                                เชื่อมต่อ Google
                            </span>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT (overlaps hero) ===== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 mb-16 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- ===== SIDEBAR ===== --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-24">
                    <nav class="flex flex-col p-2 space-y-1">
                        <a href="{{ route('account') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-orange-50 text-[#ff6b00] font-medium transition-colors">
                            <span class="material-icons-outlined">person</span>
                            ข้อมูลส่วนตัว
                        </a>
                        <a href="{{ route('account.orders') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">inventory_2</span>
                            คำสั่งซื้อ
                        </a>
                        <a href="{{ route('account.addresses') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">location_on</span>
                            ที่อยู่จัดส่ง
                        </a>
                        <a href="{{ route('account.wishlist') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">favorite</span>
                            รายการโปรด
                        </a>
                        @if($user->role === 'admin')
                            <a href="/admin"
                               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <span class="material-icons-outlined">admin_panel_settings</span>
                                แอดมิน
                            </a>
                        @endif
                        <div class="my-1 border-t border-gray-100"></div>
                        <button wire:click="logout"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors w-full text-left">
                            <span class="material-icons-outlined">logout</span>
                            ออกจากระบบ
                        </button>
                    </nav>
                </div>
            </aside>

            {{-- ===== MAIN CARDS ===== --}}
            <div class="lg:col-span-3 space-y-8">

                {{-- Personal Info --}}
                <section class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-3 bg-orange-100 rounded-full text-[#ff6b00]">
                            <span class="material-icons-outlined text-2xl">manage_accounts</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">ข้อมูลส่วนตัว</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">ชื่อ</label>
                            <div class="text-base text-gray-900 font-medium border-b border-transparent group-hover:border-gray-200 transition-colors py-1">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">อีเมล</label>
                            <div class="text-base text-gray-900 font-medium border-b border-transparent group-hover:border-gray-200 transition-colors py-1">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">เบอร์โทร</label>
                            <div class="border-b border-transparent group-hover:border-gray-200 transition-colors py-1
                                {{ $user->phone ? 'text-base text-gray-900 font-medium' : 'text-base text-gray-400 italic' }}">
                                {{ $user->phone ?: '-' }}
                            </div>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">สถานะ</label>
                            <div class="py-1">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">แอดมิน</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">สมาชิก</span>
                                @endif
                            </div>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">สมาชิกตั้งแต่</label>
                            <div class="text-base text-gray-900 font-medium border-b border-transparent group-hover:border-gray-200 transition-colors py-1">
                                {{ $user->created_at->locale('th')->translatedFormat('j F Y') }}
                            </div>
                        </div>
                        @if($user->social_provider)
                            <div class="group">
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">เข้าสู่ระบบผ่าน</label>
                                <div class="flex items-center gap-2 text-base text-gray-900 font-medium border-b border-transparent group-hover:border-gray-200 transition-colors py-1">
                                    @if($user->social_provider === 'facebook')
                                        <svg class="w-4 h-4 text-blue-600 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        Facebook
                                    @elseif($user->social_provider === 'google')
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                        Google
                                    @else
                                        {{ ucfirst($user->social_provider) }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Shipping Addresses --}}
                <section class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-orange-100 rounded-full text-[#ff6b00]">
                                <span class="material-icons-outlined text-2xl">home_work</span>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">ที่อยู่จัดส่ง</h2>
                        </div>
                        <a href="{{ route('account.addresses') }}"
                           class="text-sm text-[#ff6b00] hover:text-orange-700 font-medium transition-colors">จัดการ</a>
                    </div>

                    @php $addresses = $user->addresses ?? []; @endphp
                    @if(is_array($addresses) && count($addresses) > 0)
                        <div class="space-y-4">
                            @foreach(array_slice($addresses, 0, 3) as $addr)
                                <div class="p-4 rounded-xl border {{ ($addr['is_default'] ?? false) ? 'border-orange-200 bg-orange-50/40' : 'border-gray-200 bg-gray-50/30' }}">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="text-sm font-semibold text-gray-900">{{ $addr['name'] ?? '' }}</span>
                                        <span class="text-gray-300">|</span>
                                        <span class="text-sm text-gray-500">{{ $addr['phone'] ?? '' }}</span>
                                        @if($addr['is_default'] ?? false)
                                            <span class="ml-auto text-[11px] text-orange-700 bg-orange-100 px-2 py-0.5 rounded-full font-medium">ค่าเริ่มต้น</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 line-clamp-2">
                                        {{ $addr['address'] ?? '' }}@if($addr['district'] ?? ''), {{ $addr['district'] }}@endif@if($addr['province'] ?? ''), {{ $addr['province'] }}@endif {{ $addr['postal_code'] ?? '' }}
                                    </p>
                                </div>
                            @endforeach
                            @if(count($addresses) > 3)
                                <p class="text-xs text-gray-400 text-center pt-1">และอีก {{ count($addresses) - 3 }} ที่อยู่</p>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 px-4 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                <span class="material-icons-outlined text-4xl text-gray-400">add_location_alt</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ยังไม่มีที่อยู่จัดส่ง</h3>
                            <p class="text-gray-500 text-center text-sm max-w-xs mb-6">เพิ่มที่อยู่สำหรับการจัดส่งสินค้า เพื่อความสะดวกในการสั่งซื้อครั้งถัดไป</p>
                            <a href="{{ route('account.addresses') }}"
                               class="px-6 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                                + เพิ่มที่อยู่ใหม่
                            </a>
                        </div>
                    @endif
                </section>

            </div>
        </div>
    </main>
</div>
