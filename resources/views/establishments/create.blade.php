@extends('layouts.app')

@section('titre', 'Nouvel établissement')

@section('contenu')

<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <h1 class="font-display font-semibold text-2xl text-ink">Ajouter un établissement</h1>
        <p class="text-muted text-sm mt-1">Ajoute un nouvel établissement afin d'analyser ses avis clients avec l'IA.</p>
    </div>

    <form action="{{ route('establishments.store') }}" method="POST" class="card rounded-xl p-6 space-y-6">
        @csrf

        {{-- Nom --}}
        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                Nom de l'établissement <span class="text-rose-500">*</span>
            </label>

            <input type="text" name="name" value="{{ old('name') }}" placeholder="ex : Riad Atlas"
                class="w-full bg-stone border border-line rounded-lg px-4 py-2.5 text-sm text-ink placeholder:text-muted focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent">

            @error('name')
                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                Type d'établissement <span class="text-rose-500">*</span>
            </label>

            <select name="type"
                class="w-full bg-stone border border-line rounded-lg px-4 py-2.5 text-sm text-ink focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent">
                @foreach($types as $type)
                    <option value="{{ $type->value }}" @selected(old('type') == $type->value)>
                        {{ $type->label() }}
                    </option>
                @endforeach
            </select>

            @error('type')
                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ton IA --}}
        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                Ton des réponses IA <span class="text-rose-500">*</span>
            </label>

            <select name="tone"
                class="w-full bg-stone border border-line rounded-lg px-4 py-2.5 text-sm text-ink focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent">
                @foreach($tones as $tone)
                    <option value="{{ $tone->value }}" @selected(old('tone') == $tone->value)>
                        {{ $tone->label() }}
                    </option>
                @endforeach
            </select>

            @error('tone')
                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Conseil --}}
        <div class="rounded-lg bg-accent/5 border border-accent/20 p-4 flex items-start gap-3">
            <svg width="16" height="16" class="text-accent shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                <path d="M12 2L3 14h8l-1 8 10-12h-8l1-8z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-ink mb-1">Conseil</p>
                <p class="text-sm text-muted leading-relaxed">
                    Le ton sélectionné sera utilisé par l'IA pour générer automatiquement les réponses aux avis clients.
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pt-2 border-t border-line flex items-center justify-between">
            <a href="{{ route('establishments.index') }}" class="text-sm text-muted hover:text-ink transition-colors">
                Annuler
            </a>

            <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg font-medium text-sm">
                Créer l'établissement
            </button>
        </div>

    </form>

</div>

@endsection