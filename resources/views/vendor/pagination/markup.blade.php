@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-100 text-slate-300">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-[#1A2B56]">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span aria-disabled="true" class="flex h-9 min-w-9 items-center justify-center px-2 text-sm text-slate-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="flex h-9 min-w-9 items-center justify-center rounded-lg bg-[#A855F7] px-3 text-sm font-bold text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="flex h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 px-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-[#1A2B56]">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-[#1A2B56]">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        @else
            <span aria-disabled="true" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-100 text-slate-300">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        @endif
    </nav>
@endif
