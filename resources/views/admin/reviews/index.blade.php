@extends('admin.layout')
@section('title', 'จัดการรีวิว')

@section('content')
<!-- Rating Filter Tabs -->
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ !request('rating') ? 'bg-gray-800 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
        ทั้งหมด ({{ $ratingCounts['all'] }})
    </a>
    @for($r = 5; $r >= 1; $r--)
        <a href="{{ route('admin.reviews.index', ['rating' => $r]) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('rating') == $r ? 'bg-gray-800 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
            {{ $r }} ดาว ({{ $ratingCounts[$r] }})
        </a>
    @endfor
</div>

<!-- Search -->
<form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-4">
    @if(request('rating'))
        <input type="hidden" name="rating" value="{{ request('rating') }}">
    @endif
    <div class="relative max-w-sm">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อลูกค้า, สินค้า, หรือเนื้อหารีวิว..." class="w-full pl-10 pr-4 py-2 bg-white border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-gray-300">
        <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
    </div>
</form>

<!-- Reviews Table -->
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500">
                <tr>
                    <th class="px-5 py-3 text-left">ลูกค้า</th>
                    <th class="px-5 py-3 text-left">สินค้า</th>
                    <th class="px-5 py-3 text-center">คะแนน</th>
                    <th class="px-5 py-3 text-left">รีวิว</th>
                    <th class="px-5 py-3 text-left">วันที่</th>
                    <th class="px-5 py-3 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 shrink-0">
                                {{ strtoupper(mb_substr($review->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="text-gray-700 truncate max-w-[120px]">{{ $review->user->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-gray-700 truncate max-w-[150px] block">{{ $review->product->name ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <div class="flex justify-center gap-0.5">
                            @for($star = 1; $star <= 5; $star++)
                                <svg class="h-3.5 w-3.5 {{ $star <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <p class="text-gray-600 text-xs line-clamp-2 max-w-[250px]">{{ $review->comment }}</p>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500 whitespace-nowrap">
                        {{ $review->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('ต้องการลบรีวิวนี้?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">ลบ</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400">ยังไม่มีรีวิว</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reviews->hasPages())
        <div class="px-5 py-3 border-t">
            {{ $reviews->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
