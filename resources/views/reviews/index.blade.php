@extends('layouts.app')
@section('titre', 'Mes avis')

@section('contenu')
<div class="flex items-end justify-between mb-6">
    <div>
        <h1 class="font-display font-semibold text-2xl text-ink">Mes avis</h1>
    </div>
    <a href="{{ route('reviews.create') }}" class="btn-primary text-white px-5 py-2.5 rounded-lg font-medium text-sm flex items-center gap-2">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Analyser un avis
    </a>
</div>

{{-- Filtres --}}
<form method="GET" class="card rounded-xl p-4 mb-6 flex flex-wrap items-center gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Rechercher dans le texte…"
        class="flex-1 min-w-[180px] bg-stone border border-line rounded-lg px-3 py-2 text-sm text-ink placeholder:text-muted focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent">

    <select name="sentiment" class="bg-stone border border-line rounded-lg px-3 py-2 text-sm text-ink focus:outline-none focus:ring-1 focus:ring-accent">
        <option value="">Tous sentiments</option>
        @foreach ($sentiments as $s)
            <option value="{{ $s->value }}" {{ request('sentiment') === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
        @endforeach
    </select>

    <label class="flex items-center gap-2 text-sm cursor-pointer">
        <input type="checkbox" name="flagged" value="1" {{ request('flagged') === '1' ? 'checked' : '' }}
            class="rounded border-line bg-stone accent-accent">
        <span class="text-accent font-medium text-xs">⚠ Action requise</span>
    </label>

    <button type="submit" class="bg-stone border border-line hover:border-accent/40 text-muted hover:text-ink px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        Filtrer
    </button>

    @if (request()->hasAny(['search', 'sentiment', 'flagged']))
        <a href="{{ route('reviews.index') }}" class="text-sm text-muted hover:text-ink">✕ Réinitialiser</a>
    @endif
</form>

{{-- Liste --}}
@if ($reviews->isEmpty())
    <div class="card rounded-xl py-14 text-center border-dashed">
        <p class="text-muted text-sm">Aucun avis trouvé.</p>
        @if (!request()->hasAny(['search','sentiment','flagged']))
            <a href="{{ route('reviews.create') }}" class="text-accent text-sm font-medium hover:underline mt-2 inline-block">Analyser mon premier avis →</a>
        @endif
    </div>
@else
    <div class="space-y-2.5">
        @foreach ($reviews as $review)
        <a href="{{ route('reviews.show', $review) }}" class="card card-hover rounded-xl p-4 flex items-center gap-4 block">
            {{-- Barre couleur sentiment --}}
            <div class="w-1 self-stretch rounded-full shrink-0" style="background:{{ $review->sentiment->dot() }}"></div>

            <div class="flex-1 min-w-0">
                <p class="text-sm text-ink line-clamp-1 mb-1.5">{{ $review->raw_text }}</p>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded border text-xs font-medium {{ $review->sentiment->badgeClasses() }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>{{ $review->sentiment->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded border text-xs font-medium {{ $review->status->badgeClasses() }}">
                        {{ $review->status->label() }}
                    </span>
                    @if ($review->is_flagged)
                        <span class="text-xs text-accent font-medium flex items-center gap-1">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Flagué
                        </span>
                    @endif
                    @foreach ($review->tags->take(3) as $tag)
                        <span class="bg-stone border border-line px-2 py-0.5 rounded text-xs text-muted">{{ $tag->label }}</span>
                    @endforeach
                    @if ($review->tags->count() > 3)
                        <span class="text-xs text-muted">+{{ $review->tags->count() - 3 }}</span>
                    @endif
                </div>
            </div>

            <div class="text-right shrink-0 space-y-1">
                @if ($review->rating)
                    <p class="font-semibold text-sm" style="color:{{ $review->rating <= 2 ? '#E11D48' : ($review->rating === 3 ? '#B5502F' : '#10B981') }}">{{ $review->rating }}/5</p>
                @endif
                <p class="mono text-xs text-muted">{{ strtoupper($review->language ?? '?') }}</p>
                <p class="text-xs text-muted/70">{{ $review->created_at->diffForHumans() }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-6">{{ $reviews->links() }}</div>
@endif
@endsection