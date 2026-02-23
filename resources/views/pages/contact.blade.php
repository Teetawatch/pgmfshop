@extends('layouts.app', [
    'title' => 'ติดต่อร้านค้า',
    'description' => 'ติดต่อ PGMF Shop - สอบถามข้อมูลสินค้า สั่งซื้อ และอื่นๆ',
])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">ติดต่อร้านค้า</h1>
        <p class="text-lg text-gray-600">สอบถามข้อมูลสินค้า สั่งซื้อ และอื่นๆ</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Left Side - Contact Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            @if(auth()->check())
                <h2 class="text-xl font-semibold text-gray-900 mb-6">ส่งข้อความถึงร้านค้า</h2>
                <form class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ชื่อ</label>
                        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               readonly>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">อีเมล</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               readonly>
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">หัวข้อ</label>
                        <input type="text" id="subject" name="subject" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="กรุณาระบุหัวข้อ" required>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">ข้อความ</label>
                        <textarea id="message" name="message" rows="6" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="กรุณากรอกข้อความของคุณ..." required></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        ส่งข้อความ
                    </button>
                </form>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-lock-closed class="h-8 w-8 text-blue-600" />
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">เข้าสู่ระบบเพื่อส่งข้อความถึงร้านค้า</h2>
                    <p class="text-gray-600 mb-6">กรุณาเข้าสู่ระบบเพื่อส่งข้อความติดต่อร้านค้า</p>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <x-heroicon-o-arrow-right class="h-5 w-5 mr-2" />
                        เข้าสู่ระบบ
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Side - Store Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">ข้อมูลร้าน</h2>
            
            <div class="space-y-6">
                <!-- Company Name -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                        <x-heroicon-o-building-office class="h-5 w-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-1">ชื่อบริษัท</h3>
                        <p class="text-gray-600">Progressive Movement Foundation</p>
                    </div>
                </div>

                <!-- Address -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                        <x-heroicon-o-map-pin class="h-5 w-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-1">ที่อยู่</h3>
                        <p class="text-gray-600">167 อาคารอนาคตใหม่ ชั้น 3 ยูนิตที่1<br>แขวงหัวหมาก เขตบางกะปิ<br>กรุงเทพมหานคร 10240</p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                        <x-heroicon-o-phone class="h-5 w-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-1">เบอร์โทรศัพท์</h3>
                        <p class="text-gray-600">02-123-4567</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                        <x-heroicon-o-envelope class="h-5 w-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-1">อีเมล</h3>
                        <p class="text-gray-600">Office@progressivemevement.in.th</p>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                        <x-heroicon-o-clock class="h-5 w-5 text-gray-600" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-1">เวลาทำการ</h3>
                        <p class="text-gray-600">จันทร์ - ศุกร์: 09:00 - 19:00 น.<br>เสาร์ - อาทิตย์: หยุดทำการ</p>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="font-medium text-gray-900 mb-4">ช่องทางติดต่ออื่น ๆ</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="tel:02-123-4567" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <x-heroicon-o-phone class="h-5 w-5 text-gray-600" />
                    </a>
                    <a href="https://facebook.com" target="_blank" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <x-heroicon-o-chat-bubble-left-right class="h-5 w-5 text-gray-600" />
                    </a>
                    <a href="https://line.me" target="_blank" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <x-heroicon-o-chat-bubble-oval class="h-5 w-5 text-gray-600" />
                    </a>
                    <a href="https://twitter.com" target="_blank" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <x-heroicon-o-cursor-arrow-rays class="h-5 w-5 text-gray-600" />
                    </a>
                    <a href="https://instagram.com" target="_blank" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <x-heroicon-o-photo class="h-5 w-5 text-gray-600" />
                    </a>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <x-heroicon-o-globe-alt class="h-5 w-5 text-gray-600" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
