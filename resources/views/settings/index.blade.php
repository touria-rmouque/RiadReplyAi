@extends('layouts.app')
@section('titre', 'Paramètres')

@section('contenu')
<div class="max-w-xl mx-auto">
    <h1 class="font-display font-semibold text-2xl mb-8 text-ink">Mon établissement</h1>

    <form method="POST" action="{{ route('settings.store') }}" class="card rounded-xl p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                Nom de l'établissement <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $establishment?->name) }}" required
                placeholder="ex : Riad Al Yasmine"
                class="w-full bg-stone border border-line rounded-lg px-4 py-2.5 text-sm text-ink placeholder:text-muted focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent">
            <p class="text-xs text-muted mt-1.5">Ce nom apparaîtra dans les réponses générées.</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-3">
                Type d'établissement <span class="text-rose-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($types as $type)
                <label class="cursor-pointer group">
                    <input type="radio" name="type" value="{{ $type->value }}"
                        {{ old('type', $establishment?->type->value) === $type->value ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="card peer-checked:!border-accent peer-checked:bg-accent/5 rounded-xl p-4 text-center transition-all group-hover:border-accent/40 cursor-pointer">
                        <div class="w-9 h-9 mx-auto mb-2 rounded-lg bg-accent/10 flex items-center justify-center text-accent">
                            @if ($type === \App\Enums\EstablishmentType::Riad)
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                                    <path d="M4 21V10.5L8 7v3l4-3.5 4 3.5V7l4 3.5V21" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                    <path d="M9 21v-5a3 3 0 0 1 6 0v5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                    <path d="M12 4V2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                </svg>
                            @else
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                                    <path d="M7 2v9a2 2 0 0 0 2 2v9M7 2v6M9 2v6M11 2v6M11 2c1.5 0 2.5 2 2.5 4.5S12.5 11 11 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17 2v20M17 2c-2 0-3 2.5-3 5.5S15 12 17 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </div>
                        <p class="font-semibold text-sm text-ink">{{ $type->label() }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-3">
                Ton des réponses <span class="text-rose-500">*</span>
            </label>
            <div class="space-y-2.5">
                @foreach ($tones as $tone)
                <label class="cursor-pointer group flex items-center gap-3 card rounded-xl p-3 transition-all group-hover:border-accent/40 cursor-pointer {{ old('tone', $establishment?->tone->value) === $tone->value ? '!border-accent bg-accent/5' : '' }}">
                    <input type="radio" name="tone" value="{{ $tone->value }}"
                        {{ old('tone', $establishment?->tone->value) === $tone->value ? 'checked' : '' }}
                        class="accent-accent shrink-0">
                    <div>
                        <p class="font-semibold text-sm text-ink">{{ $tone->label() }}</p>
                        <p class="text-xs text-muted mt-0.5 capitalize">{{ $tone->promptDescription() }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div class="pt-2 border-t border-line">
            <button type="submit" class="btn-primary text-white px-7 py-2.5 rounded-lg font-medium text-sm">
                Enregistrer les paramètres
            </button>
        </div>
    </form>

    @if ($establishment)
    <div class="mt-5 card rounded-xl p-4">
        <p class="text-xs text-muted">
            Configuré depuis {{ $establishment->created_at->diffForHumans() }}
            · <span class="mono text-ink">{{ $establishment->reviews()->count() }}</span> avis analysés.
        </p>
    </div>
    @endif
</div>
@endsection