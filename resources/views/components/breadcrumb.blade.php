@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($items as $i => $item)
            <li class="flex items-center gap-1.5" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if(!empty($item['url']) && $i < count($items) - 1)
                    <a href="{{ $item['url'] }}" class="hover:text-gray-900 transition-colors" itemprop="item">
                        @if($i === 0)
                            <x-heroicon-o-home class="h-4 w-4" />
                        @else
                            <span itemprop="name">{{ $item['label'] }}</span>
                        @endif
                    </a>
                @else
                    <span class="text-gray-900 font-medium" itemprop="name">{{ $item['label'] }}</span>
                @endif
                <meta itemprop="position" content="{{ $i + 1 }}">
            </li>
            @if($i < count($items) - 1)
                <li aria-hidden="true">
                    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-400" />
                </li>
            @endif
        @endforeach
    </ol>
</nav>
