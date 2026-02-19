@php $user = auth()->user(); @endphp

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="grid md:grid-cols-4 gap-6">
        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Profile Card --}}
            <div class="bg-white rounded-lg border p-5">
                <div class="flex flex-col items-center text-center">
                    @if($user->social_avatar || $user->avatar)
                        <img src="{{ $user->social_avatar ?: $user->avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-100" referrerpolicy="no-referrer" />
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h2 class="font-bold mt-3">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    @if($user->social_provider)
                        <span class="inline-flex items-center gap-1.5 mt-2 text-xs text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full">
                            @if($user->social_provider === 'google')
                                <svg class="h-3 w-3" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                            @else
                                <svg class="h-3 w-3 text-[#1877F2]" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            @endif
                            {{ ucfirst($user->social_provider) }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="bg-white rounded-lg border overflow-hidden">
                <a href="{{ route('account') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium bg-gray-50 text-gray-900 border-l-2 border-[hsl(var(--primary))]">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    ข้อมูลส่วนตัว
                </a>
                <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 border-l-2 border-transparent hover:text-gray-900 transition-colors">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    คำสั่งซื้อ
                </a>
                <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 border-l-2 border-transparent hover:text-gray-900 transition-colors">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    ที่อยู่จัดส่ง
                </a>
                @if($user->role === 'admin')
                    <a href="/admin" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 border-l-2 border-transparent hover:text-gray-900 transition-colors">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        แอดมิน
                    </a>
                @endif
                <button wire:click="logout" class="flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 border-l-2 border-transparent w-full transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    ออกจากระบบ
                </button>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="md:col-span-3 space-y-6">
            {{-- Personal Info --}}
            <div class="bg-white rounded-lg border">
                <div class="px-5 py-4 border-b">
                    <h2 class="font-bold">ข้อมูลส่วนตัว</h2>
                </div>
                <div class="p-5">
                    <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">ชื่อ</dt>
                            <dd class="text-sm font-medium">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">อีเมล</dt>
                            <dd class="text-sm font-medium">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">เบอร์โทร</dt>
                            <dd class="text-sm font-medium">{{ $user->phone ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">สถานะ</dt>
                            <dd class="text-sm font-medium">{{ $user->role === 'admin' ? 'แอดมิน' : 'สมาชิก' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">สมาชิกตั้งแต่</dt>
                            <dd class="text-sm font-medium">{{ $user->created_at->locale('th')->translatedFormat('j F Y') }}</dd>
                        </div>
                        @if($user->social_provider)
                            <div>
                                <dt class="text-xs text-gray-500 mb-1">เข้าสู่ระบบผ่าน</dt>
                                <dd class="text-sm font-medium">{{ ucfirst($user->social_provider) }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Addresses --}}
            <div class="bg-white rounded-lg border">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <h2 class="font-bold">ที่อยู่จัดส่ง</h2>
                    <a href="{{ route('account.addresses') }}" class="text-sm text-[hsl(var(--primary))] hover:underline">จัดการ</a>
                </div>
                <div class="p-5">
                    @php $addresses = $user->addresses ?? []; @endphp
                    @if(is_array($addresses) && count($addresses) > 0)
                        <div class="space-y-3">
                            @foreach(array_slice($addresses, 0, 2) as $addr)
                                <div class="p-3 rounded-lg border {{ ($addr['is_default'] ?? false) ? 'border-[hsl(var(--primary))]/30 bg-blue-50/30' : '' }}">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="text-sm font-medium">{{ $addr['name'] ?? '' }}</span>
                                        <span class="text-gray-300">|</span>
                                        <span class="text-sm text-gray-500">{{ $addr['phone'] ?? '' }}</span>
                                        @if($addr['is_default'] ?? false)
                                            <span class="text-[11px] text-[hsl(var(--primary))] bg-[hsl(var(--primary))]/10 px-1.5 py-0.5 rounded font-medium">ค่าเริ่มต้น</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 line-clamp-1">
                                        {{ $addr['address'] ?? '' }}@if($addr['district'] ?? ''), {{ $addr['district'] }}@endif @if($addr['province'] ?? ''), {{ $addr['province'] }}@endif {{ $addr['postal_code'] ?? '' }}
                                    </p>
                                </div>
                            @endforeach
                            @if(count($addresses) > 2)
                                <p class="text-xs text-gray-400 text-center">และอีก {{ count($addresses) - 2 }} ที่อยู่</p>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <svg class="h-10 w-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <p class="text-sm">ยังไม่มีที่อยู่จัดส่ง</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
