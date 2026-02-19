@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($items as $i => $item)
            <li class="flex items-center gap-1.5" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if(!empty($item['url']) && $i < count($items) - 1)
                    <a href="{{ $item['url'] }}" class="hover:text-gray-900 transition-colors" itemprop="item">
                        @if($i === 0)
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
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
                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
