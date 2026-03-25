@extends('admin.layout')
@section('title', 'ข้อความติดต่อ #' . $contactMessage->id)

@section('content')
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            กลับไปรายการข้อความ
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Message Card -->
            <div class="bg-white rounded-xl shadow-soft border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-teal-600 rounded-xl flex items-center justify-center text-white font-bold">
                            {{ mb_substr($contactMessage->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $contactMessage->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $contactMessage->user->email }}</p>
                        </div>
                    </div>
                    @switch($contactMessage->status)
                        @case('new')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                ใหม่
                            </span>
                            @break
                        @case('read')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">อ่านแล้ว</span>
                            @break
                        @case('replied')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">ตอบกลับแล้ว</span>
                            @break
                        @case('closed')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">ปิดแล้ว</span>
                            @break
                    @endswitch
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-medium text-gray-600">{{ $contactMessage->subject_label }}</span>
                        <span class="text-xs text-gray-400">{{ $contactMessage->created_at->format('d/m/Y H:i') }} ({{ $contactMessage->created_at->diffForHumans() }})</span>
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-line leading-relaxed">{{ $contactMessage->message }}</div>
                </div>
            </div>

            <!-- Admin Reply (if exists) -->
            @if($contactMessage->admin_reply)
                <div class="bg-white rounded-xl shadow-soft border border-green-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-green-100 bg-green-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">ตอบกลับจากทีมงาน</p>
                                <p class="text-xs text-gray-500">
                                    โดย {{ $contactMessage->repliedByUser?->name ?? 'Admin' }}
                                    · {{ $contactMessage->replied_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-line leading-relaxed">{{ $contactMessage->admin_reply }}</div>
                    </div>
                </div>
            @endif

            <!-- Reply Form -->
            @if(!in_array($contactMessage->status, ['closed']))
                <div class="bg-white rounded-xl shadow-soft border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">
                            {{ $contactMessage->admin_reply ? 'แก้ไขคำตอบ' : 'ตอบกลับข้อความ' }}
                        </h3>
                    </div>
                    <form action="{{ route('admin.contact-messages.reply', $contactMessage) }}" method="POST" class="p-6">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <textarea name="admin_reply" rows="6"
                                      class="w-full p-4 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none placeholder:text-gray-400"
                                      placeholder="พิมพ์ข้อความตอบกลับ...">{{ old('admin_reply', $contactMessage->admin_reply) }}</textarea>
                            @error('admin_reply')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors shadow-md">
                                <x-heroicon-o-paper-airplane class="w-4 h-4" />
                                {{ $contactMessage->admin_reply ? 'อัปเดตคำตอบ' : 'ส่งคำตอบ' }}
                            </button>
                            <p class="text-xs text-gray-400">ระบบจะส่งอีเมลแจ้งลูกค้าอัตโนมัติ</p>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-soft border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">รายละเอียด</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">รหัส</span>
                        <span class="font-medium text-gray-900">#{{ $contactMessage->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">หัวข้อ</span>
                        <span class="font-medium text-gray-900">{{ $contactMessage->subject_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">สถานะ</span>
                        <span class="font-medium text-gray-900">{{ $contactMessage->status_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">วันที่ส่ง</span>
                        <span class="font-medium text-gray-900">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($contactMessage->replied_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">วันที่ตอบกลับ</span>
                            <span class="font-medium text-gray-900">{{ $contactMessage->replied_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Card -->
            <div class="bg-white rounded-xl shadow-soft border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">ข้อมูลลูกค้า</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-teal-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        {{ mb_substr($contactMessage->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $contactMessage->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $contactMessage->user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.customers.show', $contactMessage->user) }}"
                   class="inline-flex items-center gap-1 text-xs text-primary hover:text-teal-700 font-medium transition-colors">
                    <x-heroicon-o-user class="w-3.5 h-3.5" />
                    ดูข้อมูลลูกค้า
                </a>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-soft border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">การดำเนินการ</h3>
                <div class="space-y-2">
                    @if($contactMessage->status !== 'closed')
                        <form action="{{ route('admin.contact-messages.close', $contactMessage) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('ยืนยันการปิดข้อความนี้?')"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                                <x-heroicon-o-lock-closed class="w-4 h-4" />
                                ปิดข้อความ
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('ยืนยันการลบข้อความนี้? ไม่สามารถกู้คืนได้')"
                                class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-red-200">
                            <x-heroicon-o-trash class="w-4 h-4" />
                            ลบข้อความ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
