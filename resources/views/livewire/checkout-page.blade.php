<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 py-8">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>

    <style>
    .shadow-soft {
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
    }
    
    .input-enhanced {
        @apply w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-3 text-base focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all;
    }
    
    .input-enhanced:focus {
        @apply border-blue-600 shadow-lg;
    }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(count($items) === 0 && !$orderId)
        <div class="text-center py-16">
            <x-heroicon-o-cube class="h-16 w-16 mx-auto text-gray-400 mb-4" />
            <h1 class="text-2xl font-bold mb-2">ไม่มีสินค้าในตะกร้า</h1>
            <p class="text-gray-500 mb-6">เพิ่มสินค้าลงตะกร้าก่อนดำเนินการชำระเงิน</p>
            <a href="{{ route('products') }}"><button class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">เลือกซื้อสินค้า</button></a>
        </div>
    @elseif($step === 3)
        {{-- Order Confirmation --}}
        <div class="max-w-lg mx-auto py-12">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-check-circle class="h-10 w-10 text-green-500" />
                </div>
                <h1 class="text-3xl font-bold mb-2">สั่งซื้อสำเร็จ!</h1>
                <p class="text-gray-500">หมายเลขคำสั่งซื้อ</p>
                <p class="text-xl font-mono font-bold text-[#FF6512] mt-1">{{ $orderId }}</p>
            </div>

            <div class="bg-white rounded-lg border p-6 mb-6">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="w-8 h-8 {{ $paymentMethod === 'promptpay' ? 'bg-blue-600' : 'bg-orange-500' }} rounded-lg flex items-center justify-center">
                        @if($paymentMethod === 'promptpay')
                            <x-heroicon-o-device-phone-mobile class="h-4 w-4 text-white" />
                        @else
                            <x-heroicon-o-building-library class="h-4 w-4 text-white" />
                        @endif
                    </div>
                    <h3 class="font-semibold text-lg">{{ $paymentMethod === 'promptpay' ? 'ชำระผ่าน PromptPay' : 'โอนเงินผ่านธนาคาร' }}</h3>
                </div>
                <div class="bg-orange-50 rounded-lg p-3 text-center mb-4">
                    <p class="text-sm text-gray-500">ยอดชำระ</p>
                    <p class="text-2xl font-bold text-[#FF6512]">฿{{ number_format($finalTotal, 0) }}</p>
                </div>
@php $slipVerification = session('slipVerification'); @endphp
                {{-- Status Badge --}}
                @if($slipVerification && ($slipVerification['percentage'] ?? 0) >= 80)
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200 text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-green-500" />
                            <p class="font-medium text-green-700">สลิปผ่านการตรวจสอบอัตโนมัติ</p>
                        </div>
                        <p class="text-sm text-green-600">ระบบยืนยันการชำระเงินเรียบร้อยแล้ว</p>
                    </div>
                @else
                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-200 text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <x-heroicon-o-clock class="h-5 w-5 text-amber-500" />
                            <p class="font-medium text-amber-700">รอทีมงานตรวจสอบสลิป</p>
                        </div>
                        <p class="text-sm text-amber-600">สลิปอยู่ระหว่างการตรวจสอบโดยทีมงาน</p>
                        <p class="text-xs text-amber-500 mt-1">ปกติใช้เวลาไม่เกิน 30 นาที</p>
                    </div>
                @endif
            </div>

            <p class="text-sm text-gray-500 mb-6 text-center">เราจะส่งอีเมลยืนยันคำสั่งซื้อไปที่ {{ auth()->user()->email }}</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('account.orders') }}"><button class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">ดูคำสั่งซื้อ</button></a>
                <a href="{{ route('products') }}"><button class="px-6 py-2.5 border border-gray-200 rounded-lg font-medium hover:bg-gray-50 transition-colors">เลือกซื้อต่อ</button></a>
            </div>
        </div>
    @else
        {{-- Steps 1 & 2 --}}
        {{-- Step Indicator --}}
        <div class="flex justify-center mb-12">
            <div class="flex items-center space-x-4">
                <button wire:click="goToStep1" class="flex items-center space-x-2 px-4 py-2 rounded-full shadow-lg {{ $step >= 1 ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm' }} transition-all">
                    <span class="material-icons-round text-sm">local_shipping</span>
                    <span class="text-sm font-medium">ที่อยู่ & จัดส่ง</span>
                </button>
                <div class="h-0.5 w-12 {{ $step > 1 ? 'bg-gray-300 dark:bg-gray-700' : 'bg-gray-300 dark:bg-gray-700' }}"></div>
                <div class="flex items-center space-x-2 px-4 py-2 rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm' }} transition-all">
                    <span class="material-icons-round text-sm">payment</span>
                    <span class="text-sm font-medium">ชำระเงิน</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-6">
                @if($step === 1)
                    <div class="space-y-6">
                        {{-- Address --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <span class="material-icons-round text-blue-600">location_on</span>
                                <h2 class="text-xl font-semibold">ที่อยู่จัดส่ง</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <label class="block text-base font-semibold text-gray-800 dark:text-gray-200" for="fullname">ชื่อผู้รับ <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressName" id="fullname" placeholder="ชื่อ-นามสกุล" class="input-enhanced" />
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-base font-semibold text-gray-800 dark:text-gray-200" for="phone">เบอร์โทร <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressPhone" id="phone" placeholder="08x-xxx-xxxx" class="input-enhanced" />
                                </div>
                                <div class="col-span-1 md:col-span-2 space-y-3">
                                    <label class="block text-base font-semibold text-gray-800 dark:text-gray-200" for="address">ที่อยู่ <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressLine" id="address" placeholder="บ้านเลขที่ ซอย ถนน" class="input-enhanced" />
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-base font-semibold text-gray-800 dark:text-gray-200" for="district">เขต/อำเภอ</label>
                                    <input type="text" wire:model="addressDistrict" id="district" placeholder="เขต/อำเภอ" class="input-enhanced" />
                                </div>
                                <div class="space-y-2 relative" x-data="{ 
                                    provinceQuery: @entangle('provinceQuery'), 
                                    showSuggestions: @entangle('showProvinceSuggestions'),
                                    suggestions: @entangle('provinceSuggestions'),
                                    selectedIndex: -1,
                                    
                                    init() {
                                        this.$watch('provinceQuery', () => {
                                            this.selectedIndex = -1;
                                        });
                                        
                                        // Close suggestions when clicking outside
                                        document.addEventListener('click', (e) => {
                                            if (!this.$el.contains(e.target)) {
                                                this.showSuggestions = false;
                                                this.selectedIndex = -1;
                                            }
                                        });
                                    },
                                    
                                    selectProvince(province) {
                                        this.$wire.selectProvince(province);
                                    },
                                    
                                    handleKeydown(e) {
                                        if (!this.showSuggestions || this.suggestions.length === 0) return;
                                        
                                        if (e.key === 'ArrowDown') {
                                            e.preventDefault();
                                            this.selectedIndex = Math.min(this.selectedIndex + 1, this.suggestions.length - 1);
                                        } else if (e.key === 'ArrowUp') {
                                            e.preventDefault();
                                            this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                                        } else if (e.key === 'Enter') {
                                            e.preventDefault();
                                            if (this.selectedIndex >= 0) {
                                                this.selectProvince(this.suggestions[this.selectedIndex]);
                                            }
                                        } else if (e.key === 'Escape') {
                                            this.showSuggestions = false;
                                            this.selectedIndex = -1;
                                        }
                                    },
                                    
                                    highlightSuggestion(index) {
                                        this.selectedIndex = index;
                                    }
                                }">
                                    <label class="block text-base font-semibold text-gray-800 dark:text-gray-200" for="province">จังหวัด <span class="text-red-500">*</span></label>
                                    <input 
                                        type="text" 
                                        id="province"
                                        x-model="provinceQuery"
                                        @input="$wire.set('provinceQuery', $el.value)"
                                        @keydown="handleKeydown"
                                        @focus="showSuggestions = provinceQuery.length >= 1"
                                        placeholder="จังหวัด" 
                                        class="input-enhanced"
                                    />
                                    <!-- Province suggestions dropdown -->
                                    <div x-show="showSuggestions && suggestions.length > 0" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="(province, index) in suggestions" :key="index">
                                            <div @click="selectProvince(province)"
                                                 @mouseover="highlightSuggestion(index)"
                                                 class="px-3 py-2 cursor-pointer text-sm"
                                                 :class="{
                                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300': index === selectedIndex,
                                                     'hover:bg-gray-50 dark:hover:bg-gray-700': index !== selectedIndex
                                                 }">
                                                <span x-text="province"></span>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- No results message -->
                                    <div x-show="showSuggestions && suggestions.length === 0 && provinceQuery.length >= 1"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg">
                                        <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                            ไม่พบจังหวัดที่ค้นหา
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-base font-semibold text-gray-800 dark:text-gray-200" for="zipcode">รหัสไปรษณีย์ <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="addressPostalCode" id="zipcode" placeholder="10xxx" maxlength="5" class="input-enhanced" />
                                </div>
                            </div>
                        </div>

                        {{-- Shipping --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <span class="material-icons-round text-blue-600">local_shipping</span>
                                <h2 class="text-xl font-semibold">การจัดส่ง</h2>
                            </div>
                            <div class="space-y-4">
                                <label class="relative flex items-center p-4 rounded-xl border-2 border-blue-600/20 bg-blue-50/30 dark:bg-blue-900/10 cursor-pointer hover:border-blue-600 transition-colors">
                                    <input type="radio" checked disabled class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-600" />
                                    <div class="ml-4 flex-1 flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 bg-white rounded-lg p-1 shadow-sm flex items-center justify-center overflow-hidden">
                                                <img src="{{ vite_image('ThailandPost_Logo.svg') }}" alt="ไปรษณีย์ไทย" class="h-full w-auto object-contain">
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">ไปรษณีย์ไทย</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">จัดส่งทั่วประเทศ 3-5 วันทำการ</p>
                                            </div>
                                        </div>
                                        <span class="text-lg font-bold text-blue-600">฿{{ number_format($shippingCost, 0) }}</span>
                                    </div>
                                </label>
                                @if($shippingRates->count() > 0)
                                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                        <p class="text-xs font-medium text-blue-700 dark:text-blue-300 mb-1.5">อัตราค่าจัดส่ง</p>
                                        <div class="space-y-1">
                                            @foreach($shippingRates as $rate)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-blue-600 dark:text-blue-400">{{ $rate->name }}</span>
                                                    <span class="font-semibold text-blue-800 dark:text-blue-200">฿{{ number_format($rate->price, 0) }}</span>
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
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <span class="material-icons-round text-blue-600">payment</span>
                                <h2 class="text-xl font-semibold">เลือกวิธีชำระเงิน</h2>
                            </div>
                            <div class="space-y-4">
                                @foreach([['promptpay', 'PromptPay (QR Code)', 'ชำระผ่าน PromptPay พร้อมแนบสลิป', 'bg-blue-600'], ['bank_transfer', 'โอนเงินผ่านธนาคาร', 'โอนเงินแล้วแนบสลิป', 'bg-orange-500']] as $method)
                                    <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ $paymentMethod === $method[0] ? 'border-blue-600/20 bg-blue-50/30 dark:bg-blue-900/10' : 'border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                                        <input type="radio" wire:model.live="paymentMethod" value="{{ $method[0] }}" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-600" />
                                        <div class="ml-4 flex-1 flex items-center gap-4">
                                            <div class="h-12 w-12 {{ $method[3] }} rounded-lg flex items-center justify-center">
                                                @if($method[0] === 'promptpay')
                                                    <span class="material-icons-round text-white text-xl">smartphone</span>
                                                @else
                                                    <span class="material-icons-round text-white text-xl">account_balance</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $method[1] }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $method[2] }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- PromptPay Info with QR Code --}}
                        @if($paymentMethod === 'promptpay')
                            <div class="bg-linear-to-b from-blue-50/50 to-white dark:from-blue-900/20 dark:to-gray-800 rounded-xl border border-blue-200 dark:border-blue-800 p-5">
                                <div class="flex items-center justify-center gap-2 mb-4">
                                    <span class="material-icons-round text-blue-600">qr_code_scanner</span>
                                    <h3 class="font-bold text-lg text-blue-800 dark:text-blue-200">สแกน QR Code เพื่อชำระเงิน</h3>
                                </div>

                                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-blue-100 dark:border-blue-800 shadow-sm text-center space-y-4">
                                    {{-- QR Code Image --}}
                                    <div class="inline-block p-3 bg-white dark:bg-gray-800 rounded-xl border-2 border-blue-100 dark:border-blue-800 shadow-md">
                                        <img src="{{ vite_image('paymentbiller.png') }}" alt="Bill Payment QR Code" class="w-56 h-auto mx-auto" />
                                    </div>

                                    {{-- Save QR Code Button --}}
                                    <div class="flex justify-center">
                                        <button 
                                            onclick="downloadImage('{{ vite_image('paymentbiller.png') }}', 'PGMF-Payment-QR.png')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                                        >
                                            <span class="material-icons-round">download</span>
                                            บันทึกรูปภาพ QR Code
                                        </button>
                                    </div>

                                    {{-- Amount --}}
                                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">ยอดชำระ</p>
                                        <p class="text-3xl font-bold text-blue-700 dark:text-blue-300 mt-1">฿{{ number_format($total, 2) }}</p>
                                    </div>

                                    {{-- Bill Payment Info --}}
                                    <div class="space-y-1.5 text-sm">
                                        <p class="text-gray-500">ชื่อบัญชี: <span class="font-medium text-gray-700">{{ config('app.promptpay_name', 'มูลนิธิคณะก้าวหน้า') }}</span></p>
                                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 max-w-xs mx-auto text-left">
                                            <span class="text-gray-500">Biller ID:</span>
                                            <span class="font-mono font-medium text-gray-700">{{ config('app.billpayment_biller_id', '099300045304207') }}</span>
                                            <span class="text-gray-500">เลขที่อ้างอิง 1:</span>
                                            <span class="font-mono font-medium text-gray-700">{{ config('app.billpayment_ref1', 'QR001') }}</span>
                                            <span class="text-gray-500">เลขที่อ้างอิง 2:</span>
                                            <span class="font-mono font-medium text-gray-700">{{ config('app.billpayment_ref2', '0') }}</span>
                                            <span class="text-gray-500">เลขที่อ้างอิง 3:</span>
                                            <span class="font-mono font-medium text-gray-700">{{ config('app.billpayment_ref3', '23012534') }}</span>
                                        </div>
                                    </div>

                                    {{-- Instructions --}}
                                    <div class="flex items-center gap-2 justify-center text-xs text-gray-400 dark:text-gray-500 pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <span class="material-icons-round text-base">info</span>
                                        <span>เปิดแอปธนาคาร → สแกน QR → ชำระเงิน → แนบสลิปด้านล่าง</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Bank Transfer Info --}}
                        @if($paymentMethod === 'bank_transfer')
                            <div class="bg-orange-50/30 dark:bg-orange-900/20 rounded-xl border border-orange-200 dark:border-orange-800 p-5 space-y-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="material-icons-round text-orange-500">account_balance</span>
                                    <p class="font-semibold text-orange-700 dark:text-orange-300">บัญชีธนาคาร</p>
                                </div>
                                @php
                                    $bankAccounts = [
                                        ['ธนาคารกรุงศรี (BAY)', 'มูลนิธิคณะก้าวหน้า', '493-1-08673-2', vite_image('banks/bay.svg'), '#FFD700'],
                                    ];
                                @endphp
                                @foreach($bankAccounts as $acc)
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                        <div class="h-12 w-12 rounded-lg shrink-0 flex items-center justify-center" style="background-color: {{ $acc[4] }}">
                                            <img src="{{ $acc[3] }}" alt="{{ $acc[0] }}" class="h-8 w-8 object-contain" />
                                        </div>
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $acc[0] }}</p>
                                            <p class="text-gray-500 dark:text-gray-400">{{ $acc[1] }}</p>
                                            <p class="font-mono font-bold text-gray-900 dark:text-gray-100">{{ $acc[2] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Slip Upload --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6 sm:p-8 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <span class="material-icons-round text-blue-600">upload_file</span>
                                <h2 class="text-xl font-semibold">แนบสลิปการชำระเงิน *</h2>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">อัปโหลดรูปสลิปหลังชำระเงิน (รองรับ JPG, PNG, WEBP ไม่เกิน 5MB)</p>
                            <div class="p-4">
                                <div x-data="{ dragging: false }" class="relative">
                                    <input type="file" wire:model="paymentSlip" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" x-on:dragover.prevent="dragging = true" x-on:dragleave="dragging = false" x-on:drop="dragging = false" />
                                    <div class="border-2 border-dashed rounded-xl p-8 text-center transition-all" :class="dragging ? 'border-blue-600 bg-blue-50/30 dark:bg-blue-900/10' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'">
                                        @if($paymentSlip)
                                            <div class="space-y-3">
                                                <div class="w-48 mx-auto rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 shadow-sm">
                                                    <img src="{{ $paymentSlip->temporaryUrl() }}" alt="สลิป" class="w-full h-auto" />
                                                </div>
                                                <div class="flex items-center justify-center gap-2 text-green-600 dark:text-green-400">
                                                    <span class="material-icons-round">check_circle</span>
                                                    <span class="font-medium">อัปโหลดสลิปแล้ว</span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">คลิกเพื่อเปลี่ยนรูป</p>
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto">
                                                    <span class="material-icons-round text-gray-400 dark:text-gray-500">upload_file</span>
                                                </div>
                                                <p class="font-medium text-gray-700 dark:text-gray-300">ลากไฟล์หรือคลิกเพื่อเลือกรูปสลิป</p>
                                                <p class="text-sm text-gray-400 dark:text-gray-500">JPG, PNG, WEBP (ไม่เกิน 5MB)</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Loading indicator --}}
                                <div wire:loading wire:target="paymentSlip" class="mt-3 flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    <span>กำลังอัปโหลด...</span>
                                </div>

                                @error('paymentSlip')
                                    <div class="mt-3 flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                                        <span class="material-icons-round">error</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror

                                {{-- Transfer Info --}}
                                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 space-y-4">
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                        <span class="material-icons-round text-blue-600">edit_note</span>
                                        ข้อมูลการโอนเงิน *
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400">วันที่โอนเงิน *</label>
                                            <input type="date" wire:model="transferDate" max="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-shadow" />
                                            @error('transferDate')
                                                <p class="text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400">เวลาที่โอน *</label>
                                            <input type="time" wire:model="transferTime" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-shadow" />
                                            @error('transferTime')
                                                <p class="text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400">จำนวนเงินที่โอน (บาท) *</label>
                                            <input type="number" wire:model="transferAmount" step="0.01" min="1" placeholder="0.00" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-shadow" />
                                            @error('transferAmount')
                                                <p class="text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-start gap-2 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800 text-sm">
                                    <span class="material-icons-round text-amber-500 mt-0.5 shrink-0">warning</span>
                                    <p class="text-amber-700 dark:text-amber-300">สลิปจะถูกตรวจสอบโดยทีมงาน หากสลิปไม่ถูกต้องอาจถูกยกเลิกคำสั่งซื้อ</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button wire:click="goToStep1" class="flex-1 py-3 border border-gray-200 dark:border-gray-600 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                                <span class="material-icons-round">arrow_back</span>
                                ย้อนกลับ
                            </button>
                            <button wire:click="placeOrder" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2" wire:loading.attr="disabled">
                                <span wire:loading wire:target="placeOrder"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg></span>
                                <span wire:loading wire:target="placeOrder">กำลังสั่งซื้อ...</span>
                                <span wire:loading.remove wire:target="placeOrder">
                                    <span class="material-icons-round">security</span>
                                    ยืนยันสั่งซื้อ (฿{{ number_format($total, 0) }})
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Order Summary Sidebar --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6 border border-gray-200 dark:border-gray-700 sticky top-24">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <span class="material-icons-round text-orange-500">inventory_2</span>
                        <h2 class="text-xl font-semibold">สรุปคำสั่งซื้อ</h2>
                    </div>
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            @php $p = $item['product']; $img = is_array($p->images) ? ($p->images[0] ?? '') : ''; $opts = $item['options'] ?? []; @endphp
                            <div class="flex gap-4">
                                <div class="h-20 w-20 shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                                    <img src="{{ $img }}" alt="{{ $p->name }}" class="h-full w-full object-cover" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 line-clamp-2">{{ $p->name }}</h3>
                                    @if(!empty($opts))
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if(!empty($opts['size']))ไซส์: {{ $opts['size'] }}@endif
                                            @if(!empty($opts['size']) && !empty($opts['color'])) • @endif
                                            @if(!empty($opts['color']))สี: {{ $opts['color'] }}@endif
                                        </p>
                                    @endif
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">x{{ $item['quantity'] }}</p>
                                        <p class="font-bold text-orange-500">฿{{ number_format($p->price * $item['quantity'], 0) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-3 py-4 border-t border-b border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">ราคาสินค้า ({{ $totalItems }} ชิ้น)</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100">฿{{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">ค่าจัดส่ง (ไปรษณีย์ไทย)</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100">฿{{ number_format($shippingCost, 0) }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center py-4">
                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">รวมทั้งสิ้น</span>
                        <span class="text-2xl font-bold text-orange-500">฿{{ number_format($total, 0) }}</span>
                    </div>
                    <div class="flex items-center justify-center gap-2 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg mb-6">
                        <span class="material-icons-round text-base text-green-500">verified_user</span>
                        <span>ชำระเงินปลอดภัย 100%</span>
                    </div>
                    @if($step === 1)
                        <button wire:click="goToStep2" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 text-lg">
                            <span>ถัดไป: เลือกวิธีชำระเงิน</span>
                            <span class="material-icons-round">arrow_forward</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
    </div>

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
