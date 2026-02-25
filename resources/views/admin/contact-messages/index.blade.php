@extends('admin.layout')
@section('title', 'ข้อความติดต่อ')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-soft border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-envelope class="w-5 h-5 text-gray-600" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500">ทั้งหมด</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-soft border border-blue-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-bell-alert class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['new'] }}</p>
                    <p class="text-xs text-gray-500">ใหม่</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-soft border border-yellow-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-eye class="w-5 h-5 text-yellow-600" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['read'] }}</p>
                    <p class="text-xs text-gray-500">อ่านแล้ว</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-soft border border-green-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['replied'] }}</p>
                    <p class="text-xs text-gray-500">ตอบกลับแล้ว</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-soft border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-lock-closed class="w-5 h-5 text-gray-500" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-500">{{ $stats['closed'] }}</p>
                    <p class="text-xs text-gray-500">ปิดแล้ว</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-soft border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ อีเมล หรือข้อความ..."
                       class="w-full h-10 px-4 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
            </div>
            <select name="status" class="h-10 px-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <option value="">สถานะทั้งหมด</option>
                @foreach(\App\Models\ContactMessage::STATUS_LABELS as $key => $label)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="subject" class="h-10 px-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <option value="">หัวข้อทั้งหมด</option>
                @foreach(\App\Models\ContactMessage::SUBJECTS as $key => $label)
                    <option value="{{ $key }}" {{ request('subject') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="h-10 px-6 bg-primary text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                ค้นหา
            </button>
            @if(request()->hasAny(['search', 'status', 'subject']))
                <a href="{{ route('admin.contact-messages.index') }}" class="h-10 px-4 flex items-center text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg transition-colors">
                    ล้าง
                </a>
            @endif
        </form>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-xl shadow-soft border border-gray-100 overflow-hidden">
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">สถานะ</th>
                            <th class="px-4 py-3 text-left font-medium">ลูกค้า</th>
                            <th class="px-4 py-3 text-left font-medium">หัวข้อ</th>
                            <th class="px-4 py-3 text-left font-medium">ข้อความ</th>
                            <th class="px-4 py-3 text-left font-medium">วันที่</th>
                            <th class="px-4 py-3 text-center font-medium">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($messages as $msg)
                            <tr class="hover:bg-gray-50 transition-colors {{ $msg->status === 'new' ? 'bg-blue-50/30' : '' }}">
                                <td class="px-4 py-3">
                                    @switch($msg->status)
                                        @case('new')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                                ใหม่
                                            </span>
                                            @break
                                        @case('read')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                อ่านแล้ว
                                            </span>
                                            @break
                                        @case('replied')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                ตอบกลับแล้ว
                                            </span>
                                            @break
                                        @case('closed')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                ปิดแล้ว
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-medium text-gray-900 {{ $msg->status === 'new' ? 'font-bold' : '' }}">{{ $msg->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $msg->user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-gray-700">{{ $msg->subject_label }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-gray-600 truncate max-w-xs">{{ Str::limit($msg->message, 80) }}</p>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="text-gray-600">{{ $msg->created_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $msg->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.contact-messages.show', $msg) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-primary bg-primary/10 rounded-lg hover:bg-primary/20 transition-colors">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                        ดู
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <x-heroicon-o-envelope class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500 font-medium">ไม่พบข้อความติดต่อ</p>
                <p class="text-gray-400 text-sm mt-1">ยังไม่มีข้อความติดต่อที่ตรงกับเงื่อนไข</p>
            </div>
        @endif
    </div>
@endsection
