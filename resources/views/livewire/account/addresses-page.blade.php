<div class="min-h-screen bg-gray-50">
    <!-- Hero Header -->
    <div class="bg-[#FF6512]">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center gap-2 text-sm text-white/70 mb-4">
                <a href="{{ route('account') }}" class="hover:text-white transition-colors">บัญชีของฉัน</a>
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                <span class="text-white font-medium">ที่อยู่จัดส่ง</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <h1 class="text-xl font-bold text-white">ที่อยู่จัดส่ง</h1>
                </div>
                @if($editing === null)
                    <button wire:click="startNew" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 text-white rounded-lg text-sm font-medium hover:bg-white/30 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5v14"/></svg>
                        เพิ่มที่อยู่ใหม่
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">

    {{-- Add/Edit Form --}}
    @if($editing !== null)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-900">{{ $editing === 'new' ? 'เพิ่มที่อยู่ใหม่' : 'แก้ไขที่อยู่' }}</h2>
                <button wire:click="cancel" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-1 block">ชื่อผู้รับ <span class="text-red-400">*</span></label>
                        <input type="text" wire:model="formName" placeholder="ชื่อ-นามสกุล" class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] focus:border-[hsl(var(--ring))]" />
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-1 block">เบอร์โทร <span class="text-red-400">*</span></label>
                        <input type="text" wire:model="formPhone" placeholder="08x-xxx-xxxx" class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] focus:border-[hsl(var(--ring))]" />
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">ที่อยู่ <span class="text-red-400">*</span></label>
                    <input type="text" wire:model="formAddress" placeholder="บ้านเลขที่ ซอย ถนน" class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] focus:border-[hsl(var(--ring))]" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-1 block">เขต/อำเภอ</label>
                        <input type="text" wire:model="formDistrict" placeholder="เขต/อำเภอ" class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] focus:border-[hsl(var(--ring))]" />
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-1 block">จังหวัด <span class="text-red-400">*</span></label>
                        <input type="text" wire:model="formProvince" placeholder="จังหวัด" class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] focus:border-[hsl(var(--ring))]" />
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-1 block">รหัสไปรษณีย์ <span class="text-red-400">*</span></label>
                        <input type="text" wire:model="formPostalCode" placeholder="xxxxx" maxlength="5" class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] focus:border-[hsl(var(--ring))]" />
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="formIsDefault" class="rounded border-gray-300" />
                    <span class="text-sm">ตั้งเป็นที่อยู่เริ่มต้น</span>
                </label>
                <div class="flex gap-3 pt-2">
                    <button wire:click="save" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        บันทึก
                    </button>
                    <button wire:click="cancel" class="px-5 py-2.5 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Address List --}}
    @if(count($addresses) === 0 && $editing === null)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16">
            <svg class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <h2 class="font-bold mb-1">ยังไม่มีที่อยู่จัดส่ง</h2>
            <p class="text-sm text-gray-500 mb-5">เพิ่มที่อยู่เพื่อใช้ในการสั่งซื้อ</p>
            <button wire:click="startNew" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5v14"/></svg>
                เพิ่มที่อยู่ใหม่
            </button>
        </div>
    @else
        <div class="space-y-3">
            @foreach($addresses as $index => $addr)
                <div class="bg-white rounded-xl shadow-sm border p-5 {{ ($addr['is_default'] ?? false) ? 'border-orange-200' : 'border-gray-200' }}">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="text-sm font-medium">{{ $addr['name'] ?? '' }}</p>
                                <span class="text-gray-300">|</span>
                                <p class="text-sm text-gray-500">{{ $addr['phone'] ?? '' }}</p>
                                @if($addr['is_default'] ?? false)
                                    <span class="text-[11px] text-orange-700 bg-orange-100 px-2 py-0.5 rounded-full font-medium">ค่าเริ่มต้น</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">
                                {{ $addr['address'] ?? '' }}
                                @if($addr['district'] ?? ''), {{ $addr['district'] }}@endif
                                @if($addr['province'] ?? ''), {{ $addr['province'] }}@endif
                                @if($addr['postal_code'] ?? '') {{ $addr['postal_code'] }}@endif
                            </p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            @if(!($addr['is_default'] ?? false))
                                <button wire:click="setDefault({{ $index }})" class="inline-flex items-center gap-1 px-2 py-1 text-xs text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" {{ $editing !== null ? 'disabled' : '' }}>
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    ตั้งเป็นค่าเริ่มต้น
                                </button>
                            @endif
                            <button wire:click="startEdit({{ $index }})" class="h-8 w-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded transition-colors" {{ $editing !== null ? 'disabled' : '' }}>
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button wire:click="delete({{ $index }})" wire:confirm="ต้องการลบที่อยู่นี้?" class="h-8 w-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors" {{ $editing !== null ? 'disabled' : '' }}>
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    </div>
</div>
