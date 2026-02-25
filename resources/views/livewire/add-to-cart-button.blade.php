<button wire:click="addToCart"
        wire:loading.attr="disabled"
        onclick="event.stopPropagation();"
        class="p-2.5 bg-slate-900 text-white rounded-lg hover:bg-orange-500 transition-colors shadow-sm disabled:opacity-60 disabled:cursor-not-allowed"
        title="เพิ่มลงตะกร้า">
    <svg wire:loading wire:target="addToCart" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    <x-heroicon-o-shopping-cart wire:loading.remove wire:target="addToCart" class="w-5 h-5" />
</button>
