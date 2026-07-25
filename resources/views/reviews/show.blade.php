@extends('layouts.app')
@section('titre', 'Détail de l\'avis')

@section('contenu')
<a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-1.5 text-sm text-muted hover:text-ink mb-5">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Tous les avis
</a>

<div class="grid lg:grid-cols-5 gap-6">

    {{-- Colonne principale (3/5) --}}
    <div class="lg:col-span-3 space-y-5">

        {{-- Avis original --}}
        <div class="card rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <p class="mono text-xs text-muted uppercase tracking-wide">Avis original</p>
                <div class="flex items-center gap-2">
                    @if ($review->rating)
                        <div class="flex items-center gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <span style="color:{{ $i <= $review->rating ? ($review->rating <= 2 ? '#E11D48' : ($review->rating === 3 ? '#B5502F' : '#10B981')) : '#D8D2C6' }}">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                            @endfor
                            <span class="mono text-xs text-muted ml-1">{{ $review->rating }}/5</span>
                        </div>
                    @endif
                    @if ($review->language)
                        <span class="mono text-xs bg-stone border border-line px-2 py-0.5 rounded text-muted">{{ strtoupper($review->language) }}</span>
                    @endif
                </div>
            </div>
            <p class="text-sm text-ink/90 leading-relaxed">{{ $review->raw_text }}</p>
        </div>

        {{-- Réponse IA --}}
        <div class="card rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-md bg-accent/10 border border-accent/20 flex items-center justify-center">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" class="text-accent"><path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                    </div>
                    <p class="mono text-xs text-accent uppercase tracking-wide">Réponse générée par l'IA</p>
                </div>
                @if ($review->response_text)
                    <button onclick="copyResponse()" id="copy-btn"
                        class="flex items-center gap-1.5 bg-stone border border-line hover:border-accent/40 text-muted hover:text-accent px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        Copier la réponse
                    </button>
                @endif
            </div>

            @if ($review->response_text)
                <p class="text-sm text-ink/90 leading-relaxed" id="response-text">{{ $review->response_text }}</p>

                @if ($review->status->value !== 'replied')
                    <form method="POST" action="{{ route('reviews.replied', $review) }}" class="mt-4 pt-4 border-t border-line">
                        @csrf @method('PATCH')
                        <button type="submit" class="flex items-center gap-2 text-xs font-medium text-emerald-600 hover:underline">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Marquer comme répondu (j'ai publié sur Google / TripAdvisor)
                        </button>
                    </form>
                @else
                    <p class="mt-3 text-xs text-emerald-600 flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Réponse publiée · {{ $review->updated_at->format('d/m/Y') }}
                    </p>
                @endif

            @else
                {{-- État de chargement avec animation --}}
                <div class="flex items-center gap-4 py-6" id="loading-block">
                    <div class="flex gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-accent animate-bounce" style="animation-delay:0s"></span>
                        <span class="w-2 h-2 rounded-full bg-accent animate-bounce" style="animation-delay:.18s"></span>
                        <span class="w-2 h-2 rounded-full bg-accent animate-bounce" style="animation-delay:.36s"></span>
                    </div>
                    <div>
                        <p class="text-sm text-ink font-medium">Analyse IA en cours…</p>
                        <p class="text-xs text-muted">Détection de langue, sentiment, thématiques et rédaction de la réponse</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Colonne infos (2/5) --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Analyse --}}
        <div class="card rounded-xl p-5">
            <p class="mono text-xs text-muted uppercase tracking-wide mb-4">Analyse IA</p>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-muted">Sentiment</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border text-xs font-medium {{ $review->sentiment->badgeClasses() }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $review->sentiment->label() }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-muted">Statut</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border text-xs font-medium {{ $review->status->badgeClasses() }}">
                        {{ $review->status->label() }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-muted">Langue</span>
                    <span class="mono font-semibold text-sm text-ink">{{ $review->language ? strtoupper($review->language) : '—' }}</span>
                </div>
                @if ($review->rating)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-muted">Note</span>
                    <div class="flex items-center gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="text-sm" style="color:{{ $i <= $review->rating ? ($review->rating <= 2 ? '#E11D48' : ($review->rating === 3 ? '#B5502F' : '#10B981')) : '#D8D2C6' }}">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
                @endif
            </div>

            @if ($review->is_flagged)
                <div class="mt-4 rounded-lg border border-accent/20 bg-accent/5 px-3 py-2.5 flex items-start gap-2">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-accent shrink-0 mt-0.5"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <p class="text-xs text-accent-dark font-medium leading-relaxed">Action humaine requise — vérifier et personnaliser la réponse avant publication.</p>
                </div>
            @endif
        </div>

        {{-- Tags thématiques --}}
        @if ($review->tags->isNotEmpty())
        <div class="card rounded-xl p-5">
            <p class="mono text-xs text-muted uppercase tracking-wide mb-3">Thématiques détectées</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($review->tags as $tag)
                    <span class="bg-stone border border-line px-2.5 py-1 rounded-lg text-xs font-medium text-ink">{{ $tag->label }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Méta --}}
        <div class="card rounded-xl p-5">
            <p class="mono text-xs text-muted uppercase tracking-wide mb-3">Informations</p>
            <div class="space-y-2 text-xs text-muted">
                <p>Soumis le <span class="text-ink">{{ $review->created_at->format('d/m/Y à H:i') }}</span></p>
                <p>Établissement <span class="text-ink">{{ $review->establishment->name }}</span></p>
                <p class="mono text-muted/70">#{{ $review->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Copier dans le presse-papier en un clic
function copyResponse() {
    const text = document.getElementById('response-text')?.textContent?.trim();
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copy-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Copié !';
        btn.style.color = '#10B981';
        btn.style.borderColor = 'rgba(16,185,129,0.4)';
        setTimeout(() => { btn.innerHTML = orig; btn.style.color = ''; btn.style.borderColor = ''; }, 2500);
    });
}

// Polling : actualise la page toutes les 3s si la réponse IA n'est pas encore là
@if (!$review->response_text)
let attempts = 0;
const reviewId = {{ $review->id }};
const interval = setInterval(async () => {
    attempts++;
    if (attempts > 40) { clearInterval(interval); return; }
    try {
        const r = await fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (r.ok) {
            const html = await r.text();
            const doc  = new DOMParser().parseFromString(html, 'text/html');
            if (doc.getElementById('response-text')) {
                clearInterval(interval);
                location.reload();
            }
        }
    } catch(e) { /* ignore */ }
}, 3000);
@endif
</script>
@endpush