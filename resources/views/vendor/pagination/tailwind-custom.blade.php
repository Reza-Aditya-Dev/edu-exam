@if ($paginator->hasPages())
    <div class="flex items-center gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 rounded-lg flex items-center justify-center text-on-surface-variant/40 bg-surface-container-low cursor-not-allowed">
                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-9 h-9 rounded-lg flex items-center justify-center text-on-surface bg-surface-container-low hover:bg-surface-container-high transition-colors" title="Halaman Sebelumnya">
                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 text-on-surface-variant font-label-md text-label-md">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-9 h-9 rounded-lg flex items-center justify-center font-label-md text-label-md bg-primary-container text-on-primary font-bold shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-9 h-9 rounded-lg flex items-center justify-center font-label-md text-label-md text-on-surface bg-surface-container-low hover:bg-surface-container-high transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-9 h-9 rounded-lg flex items-center justify-center text-on-surface bg-surface-container-low hover:bg-surface-container-high transition-colors" title="Halaman Selanjutnya">
                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
            </a>
        @else
            <span class="w-9 h-9 rounded-lg flex items-center justify-center text-on-surface-variant/40 bg-surface-container-low cursor-not-allowed">
                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
            </span>
        @endif
    </div>
@endif
