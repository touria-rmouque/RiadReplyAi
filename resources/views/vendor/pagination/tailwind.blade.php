@if ($paginator->hasPages())
<nav class="flex items-center justify-between">
    <p class="text-xs text-ink-400 mono">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} / {{ $paginator->total() }}</p>
    <div class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-elevated text-ink-600 text-sm cursor-not-allowed">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-elevated border border-line hover:border-amber-light text-ink-400 hover:text-amber-light text-sm transition-colors">←</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-8 h-8 flex items-center justify-center text-ink-600">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-light text-white text-sm font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-elevated border border-line hover:border-amber-light text-ink-400 hover:text-amber-light text-sm transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-elevated border border-line hover:border-amber-light text-ink-400 hover:text-amber-light text-sm transition-colors">→</a>
        @else
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-elevated text-ink-600 text-sm cursor-not-allowed">→</span>
        @endif
    </div>
</nav>
@endif
