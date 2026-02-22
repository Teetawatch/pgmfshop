<div class="min-h-screen bg-gray-50">
    <!-- Hero Header -->
    <div class="bg-[#FF6512]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <x-heroicon-o-wallet class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">ชำระเงิน</h1>
                    <p class="text-white/70 text-sm">{{ count($items) }} รายการ</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
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
                @if($slipVerification)
                    {{-- Verification Score --}}
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">ผลตรวจสลิป</span>
                            <span class="text-sm font-bold {{ $slipVerification['percentage'] >= 80 ? 'text-green-600' : ($slipVerification['percentage'] >= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ $slipVerification['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all {{ $slipVerification['percentage'] >= 80 ? 'bg-green-500' : ($slipVerification['percentage'] >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $slipVerification['percentage'] }}%"></div>
                        </div>
                    </div>

                    {{-- Check Results --}}
                    <div class="space-y-1.5 mb-4">
                        @foreach($slipVerification['checks'] as $check)
                            <div class="flex items-center gap-2 text-sm">
                                @if($check['passed'])
                                    <x-heroicon-o-check-circle class="h-4 w-4 text-green-500 shrink-0" />
                                @else
                                    <x-heroicon-o-x-circle class="h-4 w-4 text-red-500 shrink-0" />
                                @endif
                                <span class="{{ $check['passed'] ? 'text-gray-600' : 'text-red-600' }}">{{ $check['detail'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

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
        <div class="flex items-center justify-center gap-2 mb-8">
            <button wire:click="goToStep1" class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all {{ $step >= 1 ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-500' }}">
                <x-heroicon-o-map-pin class="h-4 w-4" />
                ที่อยู่ & จัดส่ง
            </button>
            <div class="w-8 h-0.5 {{ $step > 1 ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
            <span class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium {{ $step >= 2 ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-500' }}">
                <x-heroicon-o-wallet class="h-4 w-4" />
                ชำระเงิน
            </span>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @if($step === 1)
                    <div class="space-y-6">
                        {{-- Address --}}
                        <div class="bg-white rounded-lg border">
                            <div class="p-4 border-b"><h2 class="font-bold flex items-center gap-2"><x-heroicon-o-map-pin class="h-5 w-5 text-[hsl(var(--primary))]" /> ที่อยู่จัดส่ง</h2></div>
                            <div class="p-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium">ชื่อผู้รับ *</label>
                                        <input type="text" wire:model="addressName" placeholder="ชื่อ-นามสกุล" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]" />
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium">เบอร์โทร *</label>
                                        <input type="text" wire:model="addressPhone" placeholder="08x-xxx-xxxx" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]" />
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium">ที่อยู่ *</label>
                                    <input type="text" wire:model="addressLine" placeholder="บ้านเลขที่ ซอย ถนน" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]" />
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium">เขต/อำเภอ</label>
                                        <input type="text" wire:model="addressDistrict" placeholder="เขต/อำเภอ" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]" />
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium">จังหวัด *</label>
                                        <input type="text" wire:model="addressProvince" placeholder="จังหวัด" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]" />
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium">รหัสไปรษณีย์ *</label>
                                        <input type="text" wire:model="addressPostalCode" placeholder="10xxx" maxlength="5" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))]" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping --}}
                        <div class="bg-white rounded-lg border">
                            <div class="p-4 border-b">
                                <h2 class="font-bold flex items-center gap-2">
                                    <x-heroicon-o-truck class="h-5 w-5 text-[hsl(var(--primary))]" />
                                    การจัดส่ง
                                </h2>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="flex items-center gap-4 p-4 rounded-xl border-2 border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5 shadow-sm">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shrink-0 border border-gray-200">
                                        <img src="{{ vite_image('ThailandPost_Logo.svg') }}" alt="ไปรษณีย์ไทย" class="h-7 w-7 object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold">ไปรษณีย์ไทย</p>
                                        <p class="text-xs text-gray-500 mt-0.5">จัดส่งทั่วประเทศ 3-5 วันทำการ</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-[hsl(var(--primary))]">฿{{ number_format($shippingCost, 0) }}</span>
                                    </div>
                                </div>

                                @if($shippingRates->count() > 0)
                                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                        <p class="text-xs font-medium text-blue-700 mb-1.5">อัตราค่าจัดส่ง</p>
                                        <div class="space-y-1">
                                            @foreach($shippingRates as $rate)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-blue-600">{{ $rate->name }}</span>
                                                    <span class="font-semibold text-blue-800">฿{{ number_format($rate->price, 0) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <button wire:click="goToStep2" class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            ถัดไป: เลือกวิธีชำระเงิน
                        </button>
                    </div>
                @endif

                @if($step === 2)
                    <div class="space-y-6">
                        {{-- Payment Methods --}}
                        <div class="bg-white rounded-lg border">
                            <div class="p-4 border-b"><h2 class="font-bold flex items-center gap-2"><x-heroicon-o-wallet class="h-5 w-5 text-[hsl(var(--primary))]" /> เลือกวิธีชำระเงิน</h2></div>
                            <div class="p-4 space-y-3">
                                @foreach([['promptpay', 'PromptPay (QR Code)', 'ชำระผ่าน PromptPay พร้อมแนบสลิป', 'bg-blue-600'], ['bank_transfer', 'โอนเงินผ่านธนาคาร', 'โอนเงินแล้วแนบสลิป', 'bg-orange-500']] as $method)
                                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all {{ $paymentMethod === $method[0] ? 'border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5 shadow-sm' : 'border-transparent bg-gray-50 hover:bg-gray-100' }}">
                                        <input type="radio" wire:model.live="paymentMethod" value="{{ $method[0] }}" class="accent-[hsl(var(--primary))] h-4 w-4" />
                                        <div class="w-10 h-10 {{ $method[3] }} rounded-xl flex items-center justify-center">
                                            @if($method[0] === 'promptpay')
                                                <x-heroicon-o-device-phone-mobile class="h-5 w-5 text-white" />
                                            @else
                                                <x-heroicon-o-building-library class="h-5 w-5 text-white" />
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $method[1] }}</p>
                                            <p class="text-sm text-gray-500">{{ $method[2] }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- PromptPay Info with QR Code --}}
                        @if($paymentMethod === 'promptpay')
                            <div class="bg-linear-to-b from-blue-50/50 to-white rounded-lg border border-blue-200 p-5">
                                <div class="flex items-center justify-center gap-2 mb-4">

                                    <h3 class="font-bold text-lg text-blue-800">สแกน QR Code เพื่อชำระเงิน</h3>
                                </div>

                                <div class="bg-white rounded-xl p-6 border border-blue-100 shadow-sm text-center space-y-4">
                                    {{-- QR Code Image --}}
                                    <div class="inline-block p-3 bg-white rounded-xl border-2 border-blue-100 shadow-md">
                                        <img src="{{ vite_image('paymentbiller.png') }}" alt="Bill Payment QR Code" class="w-56 h-auto mx-auto" />
                                    </div>

                                    {{-- Save QR Code Button --}}
                                    <div class="flex justify-center">
                                        <button 
                                            onclick="downloadImage('{{ vite_image('paymentbiller.png') }}', 'PGMF-Payment-QR.png')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
                                        >
                                            <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                            บันทึกรูปภาพ QR Code
                                        </button>
                                    </div>

                                    {{-- Amount --}}
                                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                        <p class="text-sm text-gray-500">ยอดชำระ</p>
                                        <p class="text-3xl font-bold text-blue-700 mt-1">฿{{ number_format($total, 2) }}</p>
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
                                    <div class="flex items-center gap-2 justify-center text-xs text-gray-400 pt-2 border-t border-gray-100">
                                        <span>เปิดแอปธนาคาร → สแกน QR → ชำระเงิน → แนบสลิปด้านล่าง</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Bank Transfer Info --}}
                        @if($paymentMethod === 'bank_transfer')
                            <div class="bg-orange-50/30 rounded-lg border border-orange-200 p-5 space-y-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <x-heroicon-o-building-library class="h-4 w-4 text-orange-500" />
                                    <p class="font-semibold text-orange-700">บัญชีธนาคาร</p>
                                </div>
                                @php
                                    $bankAccounts = [
                                        ['ธนาคารกรุงศรี (BAY)', 'มูลนิธิคณะก้าวหน้า', '493-1-08673-2', vite_image('banks/bay.svg'), '#FFD700'],
                                    ];
                                @endphp
                                @foreach($bankAccounts as $acc)
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-white border">
                                        <div class="w-10 h-10 rounded-lg shrink-0 flex items-center justify-center" style="background-color: {{ $acc[4] }}">
                                            <img src="{{ $acc[3] }}" alt="{{ $acc[0] }}" class="w-7 h-7 object-contain" />
                                        </div>
                                        <div class="text-sm">
                                            <p class="font-medium">{{ $acc[0] }}</p>
                                            <p class="text-gray-500">{{ $acc[1] }}</p>
                                            <p class="font-mono font-bold">{{ $acc[2] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Slip Upload --}}
                        <div class="bg-white rounded-lg border">
                            <div class="p-4 border-b">
                                <h2 class="font-bold flex items-center gap-2">
                                    <x-heroicon-o-arrow-up-tray class="h-5 w-5 text-[hsl(var(--primary))]" />
                                    แนบสลิปการชำระเงิน *
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">อัปโหลดรูปสลิปหลังชำระเงิน (รองรับ JPG, PNG, WEBP ไม่เกิน 5MB)</p>
                            </div>
                            <div class="p-4">
                                <div x-data="{ dragging: false }" class="relative">
                                    <input type="file" wire:model="paymentSlip" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" x-on:dragover.prevent="dragging = true" x-on:dragleave="dragging = false" x-on:drop="dragging = false" />
                                    <div class="border-2 border-dashed rounded-xl p-8 text-center transition-all" :class="dragging ? 'border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5' : 'border-gray-300 hover:border-gray-400'">
                                        @if($paymentSlip)
                                            <div class="space-y-3">
                                                <div class="w-48 mx-auto rounded-lg overflow-hidden border shadow-sm">
                                                    <img src="{{ $paymentSlip->temporaryUrl() }}" alt="สลิป" class="w-full h-auto" />
                                                </div>
                                                <div class="flex items-center justify-center gap-2 text-green-600">
                                                    <x-heroicon-o-check-circle class="h-5 w-5" />
                                                    <span class="font-medium">อัปโหลดสลิปแล้ว</span>
                                                </div>
                                                <p class="text-xs text-gray-500">คลิกเพื่อเปลี่ยนรูป</p>
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto">
                                                    <x-heroicon-o-arrow-up-tray class="h-6 w-6 text-gray-400" />
                                                </div>
                                                <p class="font-medium text-gray-700">ลากไฟล์หรือคลิกเพื่อเลือกรูปสลิป</p>
                                                <p class="text-sm text-gray-400">JPG, PNG, WEBP (ไม่เกิน 5MB)</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Loading indicator --}}
                                <div wire:loading wire:target="paymentSlip" class="mt-3 flex items-center gap-2 text-sm text-[hsl(var(--primary))]">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    <span>กำลังอัปโหลด...</span>
                                </div>

                                @error('paymentSlip')
                                    <div class="mt-3 flex items-center gap-2 text-sm text-red-600">
                                        <x-heroicon-o-exclamation-circle class="h-4 w-4 shrink-0" />
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror

                                {{-- Transfer Info --}}
                                <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-4">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <x-heroicon-o-pencil-square class="h-4 w-4 text-[hsl(var(--primary))]" />
                                        ข้อมูลการโอนเงิน *
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-medium text-gray-600">วันที่โอนเงิน *</label>
                                            <input type="date" wire:model="transferDate" max="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] bg-white" />
                                            @error('transferDate')
                                                <p class="text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-medium text-gray-600">เวลาที่โอน *</label>
                                            <input type="time" wire:model="transferTime" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] bg-white" />
                                            @error('transferTime')
                                                <p class="text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-medium text-gray-600">จำนวนเงินที่โอน (บาท) *</label>
                                            <input type="number" wire:model="transferAmount" step="0.01" min="1" placeholder="0.00" class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[hsl(var(--ring))] bg-white" />
                                            @error('transferAmount')
                                                <p class="text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-start gap-2 p-3 bg-amber-50 rounded-lg border border-amber-200 text-sm">
                                    <x-heroicon-o-exclamation-circle class="h-4 w-4 text-amber-500 mt-0.5 shrink-0" />
                                    <p class="text-amber-700">สลิปจะถูกตรวจสอบโดยทีมงาน หากสลิปไม่ถูกต้องอาจถูกยกเลิกคำสั่งซื้อ</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button wire:click="goToStep1" class="flex-1 py-3 border border-gray-200 rounded-md font-medium hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                                <x-heroicon-o-map-pin class="h-4 w-4" />
                                ย้อนกลับ
                            </button>
                            <button wire:click="placeOrder" class="flex-1 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center gap-2" wire:loading.attr="disabled">
                                <span wire:loading wire:target="placeOrder"><svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg></span>
                                <span wire:loading wire:target="placeOrder">กำลังสั่งซื้อ...</span>
                                <span wire:loading.remove wire:target="placeOrder">
                                    <x-heroicon-o-shield-check class="h-4 w-4 inline" />
                                    ยืนยันสั่งซื้อ (฿{{ number_format($total, 0) }})
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Order Summary Sidebar --}}
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24 space-y-4">
                    <h2 class="font-bold flex items-center gap-2 text-gray-900">
                        <x-heroicon-o-cube class="h-5 w-5 text-[#FF6512]" />
                        สรุปคำสั่งซื้อ
                    </h2>
                    <div class="max-h-64 overflow-y-auto space-y-3 pr-1">
                        @foreach($items as $item)
                            @php $p = $item['product']; $img = is_array($p->images) ? ($p->images[0] ?? '') : ''; $opts = $item['options'] ?? []; @endphp
                            <div class="flex gap-3">
                                <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                    <img src="{{ $img }}" alt="{{ $p->name }}" class="w-full h-full object-cover" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium line-clamp-1">{{ $p->name }}</p>
                                    @if(!empty($opts))
                                        <p class="text-[10px] text-gray-400 mt-0.5">
                                            @if(!empty($opts['size']))ไซส์: {{ $opts['size'] }}@endif
                                            @if(!empty($opts['size']) && !empty($opts['color'])) · @endif
                                            @if(!empty($opts['color']))สี: {{ $opts['color'] }}@endif
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500">x{{ $item['quantity'] }}</p>
                                    <p class="text-sm font-bold text-[#FF6512]">฿{{ number_format($p->price * $item['quantity'], 0) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr class="border-gray-200">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">ราคาสินค้า ({{ $totalItems }} ชิ้น)</span>
                            <span>฿{{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">ค่าจัดส่ง (ไปรษณีย์ไทย)</span>
                            <span>฿{{ number_format($shippingCost, 0) }}</span>
                        </div>
                    </div>
                    <hr class="border-gray-200">
                    <div class="flex justify-between font-bold text-lg">
                        <span>รวมทั้งสิ้น</span>
                        <span class="text-[#FF6512]">฿{{ number_format($total, 0) }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 pt-1">
                        <x-heroicon-o-shield-check class="h-3.5 w-3.5" />
                        <span>ชำระเงินปลอดภัย 100%</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
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
