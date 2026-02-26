<div class="min-h-screen bg-slate-50">

    <!-- Hero Header -->
    <section class="bg-[#FF6B00] pt-10 pb-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <x-heroicon-o-credit-card class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold">ชำระเงิน</h1>
                    <p class="text-white/80 font-light">ดำเนินการสั่งซื้อและชำระเงิน</p>
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 pb-20 relative z-10">
    @if(count($items) === 0 && !$orderId)
        <div class="max-w-2xl mx-auto py-12">
            <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-slate-100">
                <div class="w-32 h-32 bg-[#FF6B00]/5 rounded-full flex items-center justify-center mx-auto mb-8">
                    <x-heroicon-o-shopping-bag class="h-16 w-16 text-[#FF6B00]/40" />
                </div>
                <h2 class="text-2xl font-bold mb-3 text-slate-800">ไม่มีสินค้าในตะกร้า</h2>
                <p class="text-slate-500 mb-8">เพิ่มสินค้าลงตะกร้าก่อนดำเนินการชำระเงิน</p>
                <a href="{{ route('products') }}" class="inline-flex items-center gap-2 bg-[#FF6B00] hover:bg-orange-600 text-white font-bold px-10 py-4 rounded-xl shadow-lg shadow-[#FF6B00]/20 transition-all transform hover:-translate-y-1">
                    เลือกซื้อสินค้า
                    <x-heroicon-o-arrow-right class="h-5 w-5" />
                </a>
            </div>
        </div>
    @elseif($step === 3)
        {{-- Order Confirmation --}}
        <div class="max-w-lg mx-auto py-12">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-check-circle class="h-10 w-10 text-green-500" />
                    </div>
                    <h1 class="text-3xl font-bold mb-2 text-slate-800">สั่งซื้อสำเร็จ!</h1>
                    <p class="text-slate-500">หมายเลขคำสั่งซื้อ</p>
                    <p class="text-xl font-mono font-bold text-[#FF6B00] mt-1">{{ $orderId }}</p>
                </div>
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#FF6B00] rounded-lg flex items-center justify-center">
                        @if($paymentMethod === 'promptpay')
                            <x-heroicon-o-device-phone-mobile class="h-4 w-4 text-white" />
                        @else
                            <x-heroicon-o-building-library class="h-4 w-4 text-white" />
                        @endif
                    </div>
                    <h3 class="font-semibold text-lg text-slate-800">{{ $paymentMethod === 'promptpay' ? 'ชำระผ่าน PromptPay' : 'โอนเงินผ่านธนาคาร' }}</h3>
                </div>
                <div class="bg-orange-50 rounded-xl p-3 text-center mb-4 border border-orange-100">
                    <p class="text-sm text-slate-500">ยอดชำระ</p>
                    <p class="text-2xl font-bold text-[#FF6B00]">฿{{ number_format($finalTotal, 0) }}</p>
                </div>
@php $slipVerification = session('slipVerification'); @endphp
                @if($slipVerification && ($slipVerification['can_auto_verify'] ?? false))
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-green-500" />
                            <p class="font-medium text-green-700">สลิปผ่านการตรวจสอบอัตโนมัติ</p>
                        </div>
                        <p class="text-sm text-green-600">ระบบยืนยันการชำระเงินเรียบร้อยแล้ว</p>
                    </div>
                @else
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <x-heroicon-o-clock class="h-5 w-5 text-amber-500" />
                            <p class="font-medium text-amber-700">รอทีมงานตรวจสอบสลิป</p>
                        </div>
                        <p class="text-sm text-amber-600">สลิปอยู่ระหว่างการตรวจสอบโดยทีมงาน</p>
                        <p class="text-xs text-amber-500 mt-1">ปกติใช้เวลาไม่เกิน 30 นาที</p>
                    </div>
                @endif
                <p class="text-sm text-slate-500 mt-6 mb-4 text-center">เราจะส่งอีเมลยืนยันคำสั่งซื้อไปที่ {{ auth()->user()->email }}</p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#FF6B00] hover:bg-orange-600 text-white rounded-xl font-medium transition-colors">ดูคำสั่งซื้อ</a>
                    <a href="{{ route('products') }}" class="inline-flex items-center gap-2 px-6 py-2.5 border border-slate-200 rounded-xl font-medium hover:bg-slate-50 transition-colors text-slate-700">เลือกซื้อต่อ</a>
                </div>
            </div>
        </div>
    @else
        {{-- Step Indicator --}}
        <div class="flex justify-center mb-4 px-4">
            <div class="flex items-center space-x-2 sm:space-x-4 max-w-full overflow-x-auto">
                <button wire:click="goToStep1" class="flex items-center space-x-2 px-3 sm:px-4 py-2 rounded-full shadow-sm transition-all whitespace-nowrap {{ $step >= 1 ? 'bg-[#FF6B00] text-white shadow-[#FF6B00]/20' : 'text-slate-500 bg-white border border-slate-200' }}">
                    <x-heroicon-o-truck class="h-4 w-4 shrink-0" />
                    <span class="text-xs sm:text-sm font-medium">ที่อยู่ & จัดส่ง</span>
                </button>
                <div class="h-0.5 w-8 sm:w-12 bg-slate-200 shrink-0"></div>
                <div class="flex items-center space-x-2 px-3 sm:px-4 py-2 rounded-full transition-all whitespace-nowrap {{ $step >= 2 ? 'bg-[#FF6B00] text-white shadow-sm shadow-[#FF6B00]/20' : 'text-slate-500 bg-white border border-slate-200' }}">
                    <x-heroicon-o-credit-card class="h-4 w-4 shrink-0" />
                    <span class="text-xs sm:text-sm font-medium">ชำระเงิน</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-6">
                @if($step === 1)
                    <div class="space-y-6">
                        {{-- Address --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
                            <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                                <x-heroicon-o-map-pin class="h-5 w-5 text-[#FF6B00]" />
                                <h2 class="text-xl font-semibold text-slate-800">ที่อยู่จัดส่ง</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700" for="fullname">ชื่อผู้รับ <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressName" id="fullname" placeholder="ชื่อ-นามสกุล" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors" />
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700" for="phone">เบอร์โทร <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressPhone" id="phone" placeholder="08x-xxx-xxxx" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors" />
                                </div>
                                <div class="col-span-1 md:col-span-2 space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700" for="address">ที่อยู่ <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressLine" id="address" placeholder="บ้านเลขที่ ซอย ถนน" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors" />
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700" for="district">เขต/อำเภอ</label>
                                    <input type="text" wire:model="addressDistrict" id="district" placeholder="เขต/อำเภอ" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors" />
                                </div>
                                <div class="space-y-2 relative" x-data="{
                                    provinceQuery: @entangle('provinceQuery'),
                                    showSuggestions: @entangle('showProvinceSuggestions'),
                                    suggestions: @entangle('provinceSuggestions'),
                                    selectedIndex: -1,
                                    init() {
                                        this.$watch('provinceQuery', () => { this.selectedIndex = -1; });
                                        document.addEventListener('click', (e) => {
                                            if (!this.$el.contains(e.target)) { this.showSuggestions = false; this.selectedIndex = -1; }
                                        });
                                    },
                                    selectProvince(province) { this.$wire.selectProvince(province); },
                                    handleKeydown(e) {
                                        if (!this.showSuggestions || this.suggestions.length === 0) return;
                                        if (e.key === 'ArrowDown') { e.preventDefault(); this.selectedIndex = Math.min(this.selectedIndex + 1, this.suggestions.length - 1); }
                                        else if (e.key === 'ArrowUp') { e.preventDefault(); this.selectedIndex = Math.max(this.selectedIndex - 1, -1); }
                                        else if (e.key === 'Enter') { e.preventDefault(); if (this.selectedIndex >= 0) this.selectProvince(this.suggestions[this.selectedIndex]); }
                                        else if (e.key === 'Escape') { this.showSuggestions = false; this.selectedIndex = -1; }
                                    },
                                    highlightSuggestion(index) { this.selectedIndex = index; }
                                }">
                                    <label class="block text-sm font-semibold text-slate-700" for="province">จังหวัด <span class="text-red-500">*</span></label>
                                    <input type="text" id="province" x-model="provinceQuery"
                                        @input="$wire.set('provinceQuery', $el.value)"
                                        @keydown="handleKeydown"
                                        @focus="showSuggestions = provinceQuery.length >= 1"
                                        placeholder="จังหวัด"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors" />
                                    <div x-show="showSuggestions && suggestions.length > 0"
                                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="(province, index) in suggestions" :key="index">
                                            <div @click="selectProvince(province)" @mouseover="highlightSuggestion(index)"
                                                 class="px-3 py-2 cursor-pointer text-sm"
                                                 :class="{ 'bg-orange-50 text-[#FF6B00]': index === selectedIndex, 'hover:bg-slate-50': index !== selectedIndex }">
                                                <span x-text="province"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div x-show="showSuggestions && suggestions.length === 0 && provinceQuery.length >= 1"
                                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg">
                                        <div class="px-3 py-2 text-sm text-slate-500">ไม่พบจังหวัดที่ค้นหา</div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700" for="zipcode">รหัสไปรษณีย์ <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressPostalCode" id="zipcode" placeholder="10xxx" maxlength="5" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors" />
                                </div>
                            </div>
                        </div>

                        {{-- Shipping --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
                            <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                                <x-heroicon-o-truck class="h-5 w-5 text-[#FF6B00]" />
                                <h2 class="text-xl font-semibold text-slate-800">การจัดส่ง</h2>
                            </div>
                            <div class="space-y-4">
                                <label class="relative flex items-center p-4 rounded-xl border-2 border-[#FF6B00]/20 bg-orange-50/30 cursor-pointer transition-colors">
                                    <input type="radio" checked disabled class="h-5 w-5 text-[#FF6B00] border-slate-300 focus:ring-[#FF6B00]" />
                                    <div class="ml-4 flex-1 flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 bg-white rounded-lg p-1 shadow-sm flex items-center justify-center overflow-hidden">
                                                <img src="{{ vite_image('ThailandPost_Logo.svg') }}" alt="ไปรษณีย์ไทย" class="h-full w-auto object-contain">
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800">ไปรษณีย์ไทย</p>
                                                <p class="text-sm text-slate-500">จัดส่งทั่วประเทศ 3-5 วันทำการ</p>
                                            </div>
                                        </div>
                                        <span class="text-lg font-bold text-[#FF6B00]">฿{{ number_format($shippingCost, 0) }}</span>
                                    </div>
                                </label>
                                @if($shippingRates->count() > 0)
                                    <div class="bg-orange-50 rounded-xl p-3 border border-orange-100">
                                        <p class="text-xs font-medium text-[#FF6B00] mb-1.5">อัตราค่าจัดส่ง</p>
                                        <div class="space-y-1">
                                            @foreach($shippingRates as $rate)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-slate-600">{{ $rate->name }}</span>
                                                    <span class="font-semibold text-slate-800">฿{{ number_format($rate->price, 0) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($step === 2)
                    <div class="space-y-6">
                        {{-- Payment Methods --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
                            <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                                <x-heroicon-o-credit-card class="h-5 w-5 text-[#FF6B00]" />
                                <h2 class="text-xl font-semibold text-slate-800">เลือกวิธีชำระเงิน</h2>
                            </div>
                            <div class="space-y-4">
                                @foreach([['promptpay', 'PromptPay (QR Code)', 'ชำระผ่าน PromptPay พร้อมแนบสลิป'], ['bank_transfer', 'โอนเงินผ่านธนาคาร', 'โอนเงินแล้วแนบสลิป']] as $method)
                                    <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ $paymentMethod === $method[0] ? 'border-[#FF6B00]/20 bg-orange-50/30' : 'border-slate-200 bg-slate-50 hover:bg-slate-100' }}">
                                        <input type="radio" wire:model.live="paymentMethod" value="{{ $method[0] }}" class="h-5 w-5 text-[#FF6B00] border-slate-300 focus:ring-[#FF6B00]" />
                                        <div class="ml-4 flex-1 flex items-center gap-4">
                                            <div class="h-12 w-12 bg-[#FF6B00] rounded-lg flex items-center justify-center">
                                                @if($method[0] === 'promptpay')
                                                    <x-heroicon-o-device-phone-mobile class="h-6 w-6 text-white" />
                                                @else
                                                    <x-heroicon-o-building-library class="h-6 w-6 text-white" />
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $method[1] }}</p>
                                                <p class="text-sm text-slate-500">{{ $method[2] }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- PromptPay Info with QR Code --}}
                        @if($paymentMethod === 'promptpay')
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                                <div class="flex items-center justify-center gap-2 mb-4">
                                    <x-heroicon-o-qr-code class="h-5 w-5 text-[#FF6B00]" />
                                    <h3 class="font-bold text-lg text-slate-800">สแกน QR Code เพื่อชำระเงิน</h3>
                                </div>
                                <div class="rounded-xl p-6 border border-slate-100 text-center space-y-4">
                                    <div class="inline-block p-3 bg-white rounded-xl border-2 border-slate-100 shadow-md">
                                        <img id="billPaymentQr" src="{{ $qrCodeImage }}" alt="Bill Payment QR Code" class="w-56 h-auto mx-auto" />
                                    </div>
                                    <div class="flex justify-center">
                                        <a href="{{ $qrCodeImage }}" download="PGMF-Payment-QR-{{ number_format($total, 2) }}.png"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#FF6B00] hover:bg-orange-600 text-white text-sm font-medium rounded-xl transition-colors">
                                            <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                            บันทึกรูปภาพ QR Code
                                        </a>
                                    </div>
                                    <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                                        <p class="text-sm text-slate-500">ยอดชำระ</p>
                                        <p class="text-3xl font-bold text-[#FF6B00] mt-1">฿{{ number_format($total, 2) }}</p>
                                    </div>
                                    <div class="space-y-1.5 text-sm">
                                        <p class="text-slate-500">ชื่อบัญชี: <span class="font-medium text-slate-700">{{ config('app.promptpay_name', 'ของที่ระลึกมูลนิธิ') }}</span></p>
                                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 max-w-xs mx-auto text-left">
                                            <span class="text-slate-500">Biller ID:</span>
                                            <span class="font-mono font-medium text-slate-700">{{ config('app.billpayment_biller_id', '099300045304207') }}</span>
                                            <span class="text-slate-500">เลขที่อ้างอิง 1:</span>
                                            <span class="font-mono font-medium text-slate-700">{{ config('app.billpayment_ref1', 'QR001') }}</span>
                                            <span class="text-slate-500">เลขที่อ้างอิง 2:</span>
                                            <span class="font-mono font-medium text-slate-700">{{ config('app.billpayment_ref2', '0') }}</span>
                                            <span class="text-slate-500">เลขที่อ้างอิง 3:</span>
                                            <span class="font-mono font-medium text-slate-700">{{ config('app.billpayment_ref3', '23012534') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 justify-center text-xs text-slate-400 pt-2 border-t border-slate-100">
                                        <x-heroicon-o-information-circle class="h-4 w-4 shrink-0" />
                                        <span>เปิดแอปธนาคาร → สแกน QR → ชำระเงิน → แนบสลิปด้านล่าง</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Bank Transfer Info --}}
                        @if($paymentMethod === 'bank_transfer')
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <x-heroicon-o-building-library class="h-5 w-5 text-[#FF6B00]" />
                                    <p class="font-semibold text-slate-800">บัญชีธนาคาร</p>
                                </div>
                                @php
                                    $bankAccounts = [
                                        ['ธนาคารกรุงศรี (BAY)', 'มูลนิธิคณะก้าวหน้า', '493-1-08673-2', vite_image('banks/bay.svg'), '#FFD700'],
                                    ];
                                @endphp
                                @foreach($bankAccounts as $acc)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="h-12 w-12 rounded-lg shrink-0 flex items-center justify-center" style="background-color: {{ $acc[4] }}">
                                            <img src="{{ $acc[3] }}" alt="{{ $acc[0] }}" class="h-8 w-8 object-contain" />
                                        </div>
                                        <div class="text-sm">
                                            <p class="font-medium text-slate-800">{{ $acc[0] }}</p>
                                            <p class="text-slate-500">{{ $acc[1] }}</p>
                                            <p class="font-mono font-bold text-slate-800">{{ $acc[2] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Slip Upload --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
                            <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                                <x-heroicon-o-paper-clip class="h-5 w-5 text-[#FF6B00]" />
                                <h2 class="text-xl font-semibold text-slate-800">แนบสลิปการชำระเงิน *</h2>
                            </div>
                            <p class="text-sm text-slate-500 mb-4">อัปโหลดรูปสลิปหลังชำระเงิน (รองรับ JPG, PNG, WEBP ไม่เกิน 5MB)</p>
                            <div class="p-4">
                                <div x-data="{
                                        dragging: false,
                                        previewSrc: null,
                                        handleFile(e) {
                                            const file = e.target.files[0];
                                            if (!file) return;
                                            const reader = new FileReader();
                                            reader.onload = (r) => { this.previewSrc = r.target.result; };
                                            reader.readAsDataURL(file);
                                        }
                                    }" class="relative">
                                    <input type="file" wire:model="paymentSlip" accept="image/jpeg,image/png,image/webp"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        x-on:dragover.prevent="dragging = true"
                                        x-on:dragleave="dragging = false"
                                        x-on:drop="dragging = false"
                                        x-on:change="handleFile($event)" />
                                    <div class="border-2 border-dashed rounded-xl p-8 text-center transition-all"
                                        :class="dragging ? 'border-[#FF6B00] bg-orange-50/30' : 'border-slate-200 hover:border-slate-300'">
                                        <template x-if="previewSrc">
                                            <div class="space-y-3">
                                                <div class="w-48 mx-auto rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                                    <img :src="previewSrc" alt="สลิป" class="w-full h-auto" />
                                                </div>
                                                <div class="flex items-center justify-center gap-2 text-green-600">
                                                    <x-heroicon-o-check-circle class="h-5 w-5" />
                                                    <span class="font-medium">อัปโหลดสลิปแล้ว</span>
                                                </div>
                                                <p class="text-xs text-slate-400">คลิกเพื่อเปลี่ยนรูป</p>
                                            </div>
                                        </template>
                                        <template x-if="!previewSrc">
                                            <div class="space-y-2">
                                                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto">
                                                    <x-heroicon-o-arrow-up-tray class="h-6 w-6 text-slate-400" />
                                                </div>
                                                <p class="font-medium text-slate-700">ลากไฟล์หรือคลิกเพื่อเลือกรูปสลิป</p>
                                                <p class="text-sm text-slate-400">JPG, PNG, WEBP (ไม่เกิน 5MB)</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div wire:loading wire:target="paymentSlip" class="mt-3 flex items-center gap-2 text-sm text-[#FF6B00]">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    <span>กำลังอัปโหลด...</span>
                                </div>

                                @error('paymentSlip')
                                    <div class="mt-3 flex items-center gap-2 text-sm text-red-500">
                                        <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror

                                {{-- Transfer Info --}}
                                <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-4">
                                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                        <x-heroicon-o-pencil-square class="h-4 w-4 text-[#FF6B00]" />
                                        ข้อมูลการโอนเงิน *
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-slate-600">วันที่โอนเงิน *</label>
                                            <input type="date" wire:model="transferDate" max="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors bg-white" />
                                            @error('transferDate') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-slate-600">เวลาที่โอน *</label>
                                            <input type="time" wire:model="transferTime" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors bg-white" />
                                            @error('transferTime') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-slate-600">จำนวนเงินที่โอน (บาท) *</label>
                                            <input type="number" wire:model="transferAmount" step="0.01" min="1" placeholder="0.00" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00] transition-colors bg-white" />
                                            @error('transferAmount') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-start gap-2 p-3 bg-amber-50 rounded-xl border border-amber-200 text-sm">
                                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-amber-500 mt-0.5 shrink-0" />
                                    <p class="text-amber-700">สลิปจะถูกตรวจสอบโดยทีมงาน หากสลิปไม่ถูกต้องอาจถูกยกเลิกคำสั่งซื้อ</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button wire:click="goToStep1" class="flex-1 py-3 border border-slate-200 bg-white rounded-xl font-medium hover:bg-slate-50 transition-colors flex items-center justify-center gap-2 text-slate-700">
                                <x-heroicon-o-arrow-left class="h-5 w-5" />
                                ย้อนกลับ
                            </button>
                            <button wire:click="placeOrder" class="flex-1 py-3 bg-[#FF6B00] hover:bg-orange-600 text-white rounded-xl font-medium transition-colors flex items-center justify-center gap-2 shadow-lg shadow-[#FF6B00]/20" wire:loading.attr="disabled">
                                <span wire:loading wire:target="placeOrder"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg></span>
                                <span wire:loading wire:target="placeOrder">กำลังสั่งซื้อ...</span>
                                <span wire:loading.remove wire:target="placeOrder" class="flex items-center gap-2">
                                    <x-heroicon-o-shield-check class="h-5 w-5" />
                                    ยืนยันสั่งซื้อ (฿{{ number_format($total, 0) }})
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Order Summary Sidebar --}}
            <div class="lg:col-span-4">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 sticky top-28">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
                        <x-heroicon-o-receipt-percent class="h-5 w-5 text-[#FF6B00]" />
                        สรุปคำสั่งซื้อ
                    </h2>
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            @php $p = $item['product']; $img = is_array($p->images) ? ($p->images[0] ?? '') : ''; $opts = $item['options'] ?? []; @endphp
                            <div class="flex gap-4">
                                <div class="h-20 w-20 shrink-0 bg-slate-100 rounded-xl overflow-hidden border border-slate-100">
                                    <img src="{{ $img }}" alt="{{ $p->name }}" class="h-full w-full object-cover" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-slate-800 line-clamp-2">{{ $p->name }}</h3>
                                    @if(!empty($opts))
                                        <p class="text-xs text-slate-500 mt-1">
                                            @if(!empty($opts['size']))ไซส์: {{ $opts['size'] }}@endif
                                            @if(!empty($opts['size']) && !empty($opts['color'])) • @endif
                                            @if(!empty($opts['color']))สี: {{ $opts['color'] }}@endif
                                        </p>
                                    @endif
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-sm text-slate-500">x{{ $item['quantity'] }}</p>
                                        <p class="font-bold text-[#FF6B00]">฿{{ number_format($p->price * $item['quantity'], 0) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 mb-6 border-b border-slate-100 pb-6">
                        <div class="flex justify-between text-slate-600 text-sm">
                            <span>ราคาสินค้า ({{ $totalItems }} ชิ้น)</span>
                            <span>฿{{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-sm">
                            <span>ค่าจัดส่ง (ไปรษณีย์ไทย)</span>
                            <span>฿{{ number_format($shippingCost, 0) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-bold text-slate-800">รวมทั้งสิ้น</span>
                        <span class="text-2xl font-extrabold text-[#FF6B00]">฿{{ number_format($total, 0) }}</span>
                    </div>

                    <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 mb-6">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <x-heroicon-o-shield-check class="h-4 w-4 text-[#FF6B00] shrink-0" />
                            <span>ชำระเงินปลอดภัย 100%</span>
                        </div>
                    </div>

                    @if($step === 1)
                        <button wire:click="goToStep2" class="w-full bg-[#FF6B00] hover:bg-orange-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-[#FF6B00]/20 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <span>ถัดไป: เลือกวิธีชำระเงิน</span>
                            <x-heroicon-o-arrow-right class="h-5 w-5" />
                        </button>
                    @endif  
                </div>
            </div>
        </div>
    @endif
    </main>

    <script>
    function downloadImage(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>
</div>
