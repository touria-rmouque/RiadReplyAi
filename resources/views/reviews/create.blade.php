@extends('layouts.app')
@section('titre', 'Analyser un avis')

@section('contenu')
<div class="max-w-2xl mx-auto">
    <h1 class="font-display font-semibold text-2xl mb-2 text-ink">Analyser un avis</h1>
    <p class="text-muted text-sm mb-8">Colle un avis brut. L'IA détecte la langue, le sentiment, les thématiques et génère une réponse personnalisée dans la langue de l'avis.</p>

    <form method="POST" action="{{ route('reviews.store') }}" class="card rounded-xl p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                Texte de l'avis <span class="text-rose-500">*</span>
            </label>
            <textarea name="raw_text" id="raw_text" rows="9"
                placeholder="Colle ici l'avis client (Booking, TripAdvisor, Google Maps…)&#10;&#10;Fonctionne en toutes langues : français, anglais, espagnol, arabe, allemand…"
                class="w-full bg-stone border border-line rounded-xl px-4 py-3 text-sm text-ink placeholder:text-muted focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent resize-none leading-relaxed">{{ old('raw_text') }}</textarea>
            <div class="flex items-center justify-between mt-1.5">
                <p class="text-xs text-muted">Minimum 20 caractères</p>
                <span id="char-count" class="mono text-xs text-muted">0 / 5000</span>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                Note du client <span class="text-muted/70">(optionnelle)</span>
            </label>
            <div class="flex items-center gap-1.5" id="star-container">
                @foreach ([1,2,3,4,5] as $star)
                <button type="button" data-value="{{ $star }}"
                    class="star-btn text-2xl transition-all hover:scale-110 focus:outline-none"
                    style="color:#D8D2C6">☆</button>
                @endforeach
                <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', '') }}">
                <span id="rating-label" class="text-xs text-muted ml-2">Non renseignée</span>
            </div>
            <p class="text-xs text-muted mt-1">Note ≤ 2/5 → flagué automatiquement "Action requise"</p>
        </div>

        <div class="pt-2 border-t border-line flex items-center gap-4">
            <button type="submit" id="submit-btn"
                class="btn-primary text-white px-7 py-2.5 rounded-lg font-medium text-sm flex items-center gap-2">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                Lancer l'analyse IA
            </button>
            <a href="{{ route('reviews.index') }}" class="text-sm text-muted hover:text-ink">Annuler</a>
        </div>
    </form>

    <div class="mt-5 card rounded-xl p-4 !border-accent/20 flex items-start gap-3">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" class="text-accent shrink-0 mt-0.5"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <div>
            <p class="text-xs font-semibold text-accent mb-0.5">Traitement asynchrone</p>
            <p class="text-xs text-muted">L'analyse IA prend quelques secondes. Tu seras redirigé vers la page de résultat qui se met à jour automatiquement.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Compteur de caractères
const textarea = document.getElementById('raw_text');
const counter  = document.getElementById('char-count');
textarea.addEventListener('input', () => {
    const len = textarea.value.length;
    counter.textContent = len + ' / 5000';
    counter.style.color = len > 4500 ? '#E11D48' : '#78716C';
});

// Système d'étoiles interactif
const stars      = document.querySelectorAll('.star-btn');
const ratingInput = document.getElementById('rating-input');
const ratingLabel = document.getElementById('rating-label');
const starLabels  = ['', 'Très mauvais ⚠', 'Mauvais ⚠', 'Moyen', 'Bien', 'Excellent ✓'];
let selected = parseInt(ratingInput.value) || 0;

function paint(val) {
    stars.forEach((s, i) => {
        s.textContent   = i < val ? '★' : '☆';
        s.style.color   = i < val ? (val <= 2 ? '#E11D48' : val === 3 ? '#B5502F' : '#10B981') : '#D8D2C6';
        s.style.transform = i < val ? 'scale(1.1)' : '';
    });
    ratingLabel.textContent = val ? starLabels[val] + ' (' + val + '/5)' : 'Non renseignée';
    ratingLabel.style.color = val <= 2 && val > 0 ? '#E11D48' : '#78716C';
}

paint(selected);

stars.forEach((s, i) => {
    s.addEventListener('click', () => {
        selected = selected === i + 1 ? 0 : i + 1; // toggle
        ratingInput.value = selected || '';
        paint(selected);
    });
    s.addEventListener('mouseenter', () => paint(i + 1));
    s.addEventListener('mouseleave', () => paint(selected));
});

// Désactiver le bouton pendant la soumission
document.querySelector('form').addEventListener('submit', () => {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin" width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" stroke="white" stroke-width="2" stroke-linecap="round"/></svg> Analyse en cours…';
});
</script>
@endpush