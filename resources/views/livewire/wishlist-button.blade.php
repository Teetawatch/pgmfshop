<button
    wire:click="toggle"
    type="button"
    title="{{ $isWishlisted ? 'นำออกจากรายการโปรด' : 'เพิ่มในรายการโปรด' }}"
    class="{{ $size === 'sm' ? 'h-8 w-8' : 'h-10 w-10' }} rounded-full flex items-center justify-center transition-all duration-200 {{ $isWishlisted ? 'bg-red-50 text-red-500 hover:bg-red-100' : 'bg-white/80 backdrop-blur-sm text-gray-400 hover:text-red-500 hover:bg-red-50' }} border {{ $isWishlisted ? 'border-red-200' : 'border-gray-200' }} shadow-sm"
>
    @if($isWishlisted)
        <x-heroicon-s-heart class="{{ $size === 'sm' ? 'h-4 w-4' : 'h-5 w-5' }} transition-transform duration-200 scale-110" />
    @else
        <x-heroicon-o-heart class="{{ $size === 'sm' ? 'h-4 w-4' : 'h-5 w-5' }} transition-transform duration-200" />
    @endif
</button>
