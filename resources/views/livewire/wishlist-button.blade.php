<button
    wire:click="toggle"
    type="button"
    title="{{ $isWishlisted ? 'นำออกจากรายการโปรด' : 'เพิ่มในรายการโปรด' }}"
    class="{{ $size === 'sm' ? 'h-8 w-8' : 'h-10 w-10' }} rounded-full flex items-center justify-center transition-all duration-200 {{ $isWishlisted ? 'bg-red-50 text-red-500 hover:bg-red-100' : 'bg-white/80 backdrop-blur-sm text-gray-400 hover:text-red-500 hover:bg-red-50' }} border {{ $isWishlisted ? 'border-red-200' : 'border-gray-200' }} shadow-sm"
>
    <svg class="{{ $size === 'sm' ? 'h-4 w-4' : 'h-5 w-5' }} transition-transform duration-200 {{ $isWishlisted ? 'scale-110' : '' }}" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
    </svg>
</button>
