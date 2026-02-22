@props(['type' => 'line', 'count' => 1, 'cols' => '', 'class' => ''])

@switch($type)
    {{-- Matches: partials/product-card.blade.php --}}
    @case('product-card')
        <div class="bg-white rounded-lg overflow-hidden border border-gray-100 h-full flex flex-col animate-pulse">
            {{-- Image: aspect-square --}}
            <div class="aspect-square bg-gray-200"></div>
            {{-- Info: p-3 flex-1 flex flex-col gap-1.5 --}}
            <div class="p-3 flex-1 flex flex-col gap-1.5">
                {{-- Name: 2 lines (text-xs sm:text-sm, line-clamp-2) --}}
                <div class="space-y-1">
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-3/4"></div>
                </div>
                {{-- Price + stars at bottom --}}
                <div class="mt-auto">
                    <div class="flex items-baseline gap-2">
                        <div class="h-4 sm:h-5 bg-gray-200 rounded w-16"></div>
                        <div class="h-3 bg-gray-200 rounded w-10"></div>
                    </div>
                    <div class="flex items-center gap-1 mt-1">
                        @for($i = 0; $i < 5; $i++)
                            <div class="h-2.5 w-2.5 bg-gray-200 rounded-full"></div>
                        @endfor
                        <div class="h-2.5 bg-gray-200 rounded w-12 ml-0.5"></div>
                    </div>
                </div>
            </div>
        </div>
        @break

    {{-- Configurable grid: default 2/3/4 cols (home), or pass cols="2 lg:3" (products page) --}}
    @case('product-grid')
        @php
            $gridCols = $cols ?: 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4';
        @endphp
        <div class="grid {{ $gridCols }}">
            @for($i = 0; $i < $count; $i++)
                <x-skeleton type="product-card" />
            @endfor
        </div>
        @break

    {{-- Matches: livewire/product-detail.blade.php --}}
    @case('product-detail')
        <div class="grid md:grid-cols-2 gap-8 lg:gap-12 animate-pulse">
            {{-- Left: Images --}}
            <div class="space-y-4">
                <div class="aspect-square rounded-lg bg-gray-200"></div>
                <div class="flex gap-2 overflow-x-auto">
                    @for($i = 0; $i < 4; $i++)
                        <div class="w-20 h-20 rounded-md bg-gray-200 shrink-0"></div>
                    @endfor
                </div>
            </div>
            {{-- Right: Product Info --}}
            <div class="space-y-6">
                {{-- Category + Title --}}
                <div>
                    <div class="h-3.5 bg-gray-200 rounded w-20 mb-1"></div>
                    <div class="h-7 md:h-9 bg-gray-200 rounded w-4/5"></div>
                </div>
                {{-- Rating --}}
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        @for($i = 0; $i < 5; $i++)
                            <div class="h-5 w-5 bg-gray-200 rounded-sm"></div>
                        @endfor
                        <div class="h-4 bg-gray-200 rounded w-6 ml-1"></div>
                    </div>
                    <div class="h-3.5 bg-gray-200 rounded w-32"></div>
                </div>
                {{-- Price --}}
                <div class="flex items-baseline gap-3">
                    <div class="h-9 bg-gray-200 rounded w-28"></div>
                    <div class="h-5 bg-gray-200 rounded w-16"></div>
                    <div class="h-5 bg-gray-200 rounded w-12"></div>
                </div>
                <div class="h-px bg-gray-200"></div>
                {{-- Description --}}
                <div>
                    <div class="h-5 bg-gray-200 rounded w-32 mb-2"></div>
                    <div class="space-y-1.5">
                        <div class="h-4 bg-gray-200 rounded w-full"></div>
                        <div class="h-4 bg-gray-200 rounded w-full"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    </div>
                </div>
                {{-- Book info --}}
                <div class="space-y-1.5">
                    <div class="h-3.5 bg-gray-200 rounded w-48"></div>
                    <div class="h-3.5 bg-gray-200 rounded w-40"></div>
                    <div class="h-3.5 bg-gray-200 rounded w-32"></div>
                </div>
                {{-- Stock --}}
                <div class="flex items-center gap-2">
                    <div class="h-4 bg-gray-200 rounded w-10"></div>
                    <div class="h-5 bg-gray-200 rounded w-24"></div>
                </div>
                {{-- Quantity --}}
                <div class="flex items-center gap-4">
                    <div class="h-4 bg-gray-200 rounded w-12"></div>
                    <div class="h-10 w-28 bg-gray-200 rounded-md"></div>
                </div>
                {{-- Action buttons --}}
                <div class="flex gap-3">
                    <div class="flex-1 h-12 bg-gray-200 rounded-md"></div>
                    <div class="flex-1 h-12 bg-gray-200 rounded-md"></div>
                    <div class="w-12 h-12 bg-gray-200 rounded-md"></div>
                </div>
                <div class="h-px bg-gray-200"></div>
                {{-- Features grid --}}
                <div class="grid grid-cols-3 gap-4">
                    @for($i = 0; $i < 3; $i++)
                        <div class="text-center space-y-1">
                            <div class="h-6 w-6 bg-gray-200 rounded mx-auto"></div>
                            <div class="h-4 bg-gray-200 rounded w-16 mx-auto"></div>
                            <div class="h-3 bg-gray-200 rounded w-10 mx-auto"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        @break

    @case('banner')
        <div class="rounded-xl overflow-hidden bg-gray-200 h-[220px] sm:h-[300px] md:h-[400px] animate-pulse"></div>
        @break

    {{-- Matches: cart-page.blade.php item structure --}}
    @case('cart-item')
        <div class="bg-white rounded-lg border p-4 animate-pulse">
            <div class="flex gap-4">
                <div class="w-24 h-24 rounded-md bg-gray-200 shrink-0"></div>
                <div class="flex-1 min-w-0">
                    {{-- Name --}}
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    {{-- Category --}}
                    <div class="h-3 bg-gray-200 rounded w-1/4 mt-1"></div>
                    {{-- Quantity + Price row --}}
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center border border-gray-200 rounded-md">
                            <div class="h-8 w-8 bg-gray-100"></div>
                            <div class="w-8 h-4 bg-gray-200 mx-1"></div>
                            <div class="h-8 w-8 bg-gray-100"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-5 bg-gray-200 rounded w-16"></div>
                            <div class="h-8 w-8 bg-gray-200 rounded-md"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @break

    {{-- Full cart page layout: items (lg:col-span-2) + summary sidebar --}}
    @case('cart-page')
        <div class="animate-pulse">
            <div class="h-9 bg-gray-200 rounded w-40 mb-8"></div>
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    @for($i = 0; $i < $count; $i++)
                        <x-skeleton type="cart-item" />
                    @endfor
                    <div class="flex justify-between">
                        <div class="h-9 bg-gray-200 rounded w-28"></div>
                        <div class="h-9 bg-gray-200 rounded w-24"></div>
                    </div>
                </div>
                <div>
                    <div class="bg-white rounded-lg border p-6 space-y-4">
                        <div class="h-6 bg-gray-200 rounded w-32"></div>
                        <div class="flex gap-2">
                            <div class="flex-1 h-10 bg-gray-200 rounded-md"></div>
                            <div class="h-10 w-14 bg-gray-200 rounded-md"></div>
                        </div>
                        <div class="h-px bg-gray-200"></div>
                        <div class="space-y-2">
                            <div class="flex justify-between"><div class="h-4 bg-gray-200 rounded w-16"></div><div class="h-4 bg-gray-200 rounded w-14"></div></div>
                            <div class="flex justify-between"><div class="h-4 bg-gray-200 rounded w-24"></div><div class="h-4 bg-gray-200 rounded w-10"></div></div>
                        </div>
                        <div class="h-px bg-gray-200"></div>
                        <div class="flex justify-between"><div class="h-6 bg-gray-200 rounded w-20"></div><div class="h-6 bg-gray-200 rounded w-16"></div></div>
                        <div class="h-12 bg-gray-200 rounded-md w-full"></div>
                    </div>
                </div>
            </div>
        </div>
        @break

    {{-- Matches: account/orders-page.blade.php order card --}}
    @case('order-item')
        <div class="bg-white rounded-lg border animate-pulse">
            {{-- Header: px-5 py-4 border-b --}}
            <div class="px-5 py-4 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-3">
                    <div class="h-4 bg-gray-200 rounded w-36"></div>
                    <div class="h-5 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="h-3.5 bg-gray-200 rounded w-40"></div>
            </div>
            {{-- Items: px-5 py-4 --}}
            <div class="px-5 py-4 space-y-3">
                @for($i = 0; $i < 2; $i++)
                    <div class="flex gap-3 items-center">
                        <div class="w-14 h-14 rounded-md bg-gray-200 shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div class="h-3.5 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 rounded w-8 mt-1"></div>
                        </div>
                        <div class="h-3.5 bg-gray-200 rounded w-14 shrink-0"></div>
                    </div>
                @endfor
            </div>
            {{-- Footer: px-5 py-3 bg-gray-50 border-t --}}
            <div class="px-5 py-3 bg-gray-50 border-t flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="h-3.5 bg-gray-200 rounded w-8"></div>
                    <div class="h-5 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="h-9 bg-gray-200 rounded-md w-28"></div>
            </div>
        </div>
        @break

    {{-- Matches: account/wishlist-page.blade.php card --}}
    @case('wishlist-card')
        <div class="bg-white rounded-lg overflow-hidden border border-gray-100 flex flex-col animate-pulse">
            <div class="aspect-square bg-gray-200"></div>
            <div class="p-3 flex-1 flex flex-col gap-1.5">
                <div class="space-y-1">
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-2/3"></div>
                </div>
                <div class="h-2.5 bg-gray-200 rounded w-16"></div>
                <div class="mt-auto">
                    <div class="flex items-baseline gap-2">
                        <div class="h-4 sm:h-5 bg-gray-200 rounded w-16"></div>
                        <div class="h-3 bg-gray-200 rounded w-10"></div>
                    </div>
                    <div class="flex items-center gap-1 mt-1">
                        @for($i = 0; $i < 5; $i++)
                            <div class="h-2.5 w-2.5 bg-gray-200 rounded-full"></div>
                        @endfor
                        <div class="h-2.5 bg-gray-200 rounded w-6 ml-0.5"></div>
                    </div>
                </div>
                {{-- Action buttons --}}
                <div class="flex gap-2 mt-2 pt-2 border-t border-gray-100">
                    <div class="flex-1 h-9 bg-gray-200 rounded-lg"></div>
                    <div class="h-9 w-9 bg-gray-200 rounded-lg"></div>
                </div>
            </div>
        </div>
        @break

    @case('table-row')
        <tr class="animate-pulse">
            @for($i = 0; $i < $count; $i++)
                <td class="px-4 py-3"><div class="h-4 bg-gray-200 rounded w-{{ ['full', '3/4', '1/2', '1/3', '2/3'][$i % 5] }}"></div></td>
            @endfor
        </tr>
        @break

    @case('line')
    @default
        <div class="animate-pulse {{ $class }}">
            @for($i = 0; $i < $count; $i++)
                <div class="h-4 bg-gray-200 rounded {{ $i < $count - 1 ? 'mb-2 w-full' : 'w-2/3' }}"></div>
            @endfor
        </div>
        @break
@endswitch
