<div class="min-h-screen bg-gray-50">

    {{-- ===== HERO HEADER ===== --}}
    <div class="relative overflow-hidden"
         style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%);">
        <div class="bg-[#FF6B00] absolute inset-0">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <span class="material-icons-outlined text-xs">chevron_right</span>
                <span class="text-white font-medium">ที่อยู่จัดส่ง</span>
            </div>
            {{-- Title --}}
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/10 rounded-xl">
                        <span class="material-icons-outlined text-3xl text-white">home_work</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white tracking-tight">ที่อยู่จัดส่ง</h1>
                        <p class="text-gray-400 text-sm mt-0.5 text-white">{{ count($addresses) }} ที่อยู่</p>
                    </div>
                </div>
                @if($editing === null)
                    <button wire:click="startNew"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#ff6b00] text-white rounded-full text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm">
                        <span class="material-icons-outlined text-base">add_location_alt</span>
                        เพิ่มที่อยู่ใหม่
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT (overlaps hero) ===== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 mb-16 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- ===== SIDEBAR ===== --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-24">
                    <nav class="flex flex-col p-2 space-y-1">
                        <a href="{{ route('account') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">person</span>
                            ข้อมูลส่วนตัว
                        </a>
                        <a href="{{ route('account.orders') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">inventory_2</span>
                            คำสั่งซื้อ
                        </a>
                        <a href="{{ route('account.addresses') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-orange-50 text-[#ff6b00] font-medium transition-colors">
                            <span class="material-icons-outlined">location_on</span>
                            ที่อยู่จัดส่ง
                        </a>
                        <a href="{{ route('account.wishlist') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <span class="material-icons-outlined">favorite</span>
                            รายการโปรด
                        </a>
                        @if(auth()->user()->role === 'admin')
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

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Add / Edit Form --}}
                @if($editing !== null)
                    <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="flex items-center gap-4 p-6 border-b border-gray-100">
                            <div class="p-3 bg-orange-100 rounded-full text-[#ff6b00]">
                                <span class="material-icons-outlined text-2xl">{{ $editing === 'new' ? 'add_location_alt' : 'edit_location_alt' }}</span>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 flex-1">
                                {{ $editing === 'new' ? 'เพิ่มที่อยู่ใหม่' : 'แก้ไขที่อยู่' }}
                            </h2>
                            <button wire:click="cancel"
                                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <span class="material-icons-outlined">close</span>
                            </button>
                        </div>
                        <div class="p-6 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">
                                        ชื่อผู้รับ <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" wire:model="formName" placeholder="ชื่อ-นามสกุล"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff6b00]/30 focus:border-[#ff6b00] transition-colors" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">
                                        เบอร์โทร <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" wire:model="formPhone" placeholder="08x-xxx-xxxx"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff6b00]/30 focus:border-[#ff6b00] transition-colors" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">
                                    ที่อยู่ <span class="text-red-400">*</span>
                                </label>
                                <input type="text" wire:model="formAddress" placeholder="บ้านเลขที่ ซอย ถนน"
                                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff6b00]/30 focus:border-[#ff6b00] transition-colors" />
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">เขต/อำเภอ</label>
                                    <input type="text" wire:model="formDistrict" placeholder="เขต/อำเภอ"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff6b00]/30 focus:border-[#ff6b00] transition-colors" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">
                                        จังหวัด <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" wire:model="formProvince" placeholder="จังหวัด"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff6b00]/30 focus:border-[#ff6b00] transition-colors" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">
                                        รหัสไปรษณีย์ <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" wire:model="formPostalCode" placeholder="xxxxx" maxlength="5"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#ff6b00]/30 focus:border-[#ff6b00] transition-colors" />
                                </div>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" wire:model="formIsDefault"
                                       class="w-4 h-4 rounded border-gray-300 text-[#ff6b00] focus:ring-[#ff6b00]" />
                                <span class="text-sm text-gray-700 group-hover:text-gray-900">ตั้งเป็นที่อยู่เริ่มต้น</span>
                            </label>
                            <div class="flex gap-3 pt-2 border-t border-gray-100">
                                <button wire:click="save"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#ff6b00] text-white rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm">
                                    <span class="material-icons-outlined text-base">save</span>
                                    บันทึก
                                </button>
                                <button wire:click="cancel"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 bg-white text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                                    ยกเลิก
                                </button>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Address List --}}
                @if(count($addresses) === 0 && $editing === null)
                    {{-- Empty State --}}
                    <div class="bg-white rounded-xl shadow-sm p-12 flex flex-col items-center justify-center text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                            <span class="material-icons-outlined text-4xl text-gray-400">add_location_alt</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">ยังไม่มีที่อยู่จัดส่ง</h2>
                        <p class="text-gray-500 text-sm max-w-xs mb-6">เพิ่มที่อยู่สำหรับการจัดส่งสินค้า เพื่อความสะดวกในการสั่งซื้อครั้งถัดไป</p>
                        <button wire:click="startNew"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#ff6b00] text-white rounded-full text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm">
                            <span class="material-icons-outlined text-base">add_location_alt</span>
                            เพิ่มที่อยู่ใหม่
                        </button>
                    </div>

                @elseif(count($addresses) > 0)
                    <div class="space-y-4">
                        @foreach($addresses as $index => $addr)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden border
                                {{ ($addr['is_default'] ?? false) ? 'border-orange-200' : 'border-gray-100' }}">
                                <div class="p-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <div class="flex gap-4 flex-1 min-w-0">
                                        {{-- Icon --}}
                                        <div class="shrink-0 mt-0.5">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                                {{ ($addr['is_default'] ?? false) ? 'bg-orange-100 text-[#ff6b00]' : 'bg-gray-100 text-gray-400' }}">
                                                <span class="material-icons-outlined">{{ ($addr['is_default'] ?? false) ? 'home' : 'location_on' }}</span>
                                            </div>
                                        </div>
                                        {{-- Info --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <p class="text-sm font-semibold text-gray-900">{{ $addr['name'] ?? '' }}</p>
                                                <span class="text-gray-300">|</span>
                                                <p class="text-sm text-gray-500">{{ $addr['phone'] ?? '' }}</p>
                                                @if($addr['is_default'] ?? false)
                                                    <span class="inline-flex items-center gap-1 text-[11px] text-orange-700 bg-orange-100 px-2 py-0.5 rounded-full font-medium">
                                                        <span class="material-icons-outlined text-xs">star</span>
                                                        ค่าเริ่มต้น
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 leading-relaxed">
                                                {{ $addr['address'] ?? '' }}@if($addr['district'] ?? ''), {{ $addr['district'] }}@endif@if($addr['province'] ?? ''), {{ $addr['province'] }}@endif {{ $addr['postal_code'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1 shrink-0">
                                        @if(!($addr['is_default'] ?? false))
                                            <button wire:click="setDefault({{ $index }})"
                                                    {{ $editing !== null ? 'disabled' : '' }}
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs text-gray-500 hover:text-[#ff6b00] hover:bg-orange-50 rounded-lg transition-colors disabled:opacity-40">
                                                <span class="material-icons-outlined text-sm">star_outline</span>
                                                ตั้งค่าเริ่มต้น
                                            </button>
                                        @endif
                                        <button wire:click="startEdit({{ $index }})"
                                                {{ $editing !== null ? 'disabled' : '' }}
                                                class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-40">
                                            <span class="material-icons-outlined text-lg">edit</span>
                                        </button>
                                        <button wire:click="delete({{ $index }})"
                                                wire:confirm="ต้องการลบที่อยู่นี้?"
                                                {{ $editing !== null ? 'disabled' : '' }}
                                                class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-40">
                                            <span class="material-icons-outlined text-lg">delete_outline</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Add more button at bottom of list --}}
                        @if($editing === null)
                            <button wire:click="startNew"
                                    class="w-full py-4 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-400 hover:border-[#ff6b00] hover:text-[#ff6b00] hover:bg-orange-50/30 transition-colors flex items-center justify-center gap-2">
                                <span class="material-icons-outlined">add_location_alt</span>
                                เพิ่มที่อยู่ใหม่
                            </button>
                        @endif
                    </div>
                @endif

            </div>{{-- end lg:col-span-3 --}}
        </div>{{-- end grid --}}
    </main>
</div>
