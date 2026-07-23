@extends('layouts.app')

@section('titre', 'Modifier un établissement')

@section('contenu')

<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <h1 class="font-display font-semibold text-2xl text-ink">Modifier l'établissement</h1>
        <p class="text-muted text-sm mt-1">Modifie les informations de ton établissement.</p>
    </div>

    <form action="{{ route('establishments.update', $establishment) }}" method="POST" class="card rounded-xl p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Nom --}}
        <div>
            <label class="block text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                Nom de l'établissement <span class="text-rose-500">*</span>
            </label>

            <input type="text" name="name" value="{{ old('name', $establishment->name) }}"
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
                    <option value="{{ $type->value }}" @selected(old('type', $establishment->type->value) == $type->value)>
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
                    <option value="{{ $tone->value }}" @selected(old('tone', $establishment->tone->value) == $tone->value)>
                        {{ $tone->label() }}
                    </option>
                @endforeach
            </select>

            @error('tone')
                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Informations --}}
        <div class="rounded-lg bg-stone border border-line p-4">
            <p class="text-xs font-semibold text-muted uppercase tracking-wide mb-3">Informations</p>

            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2 text-ink">
                    <svg width="14" height="14" class="text-muted shrink-0" fill="none" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="17" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M3 9h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Créé le <strong class="font-medium">{{ $establishment->created_at->format('d/m/Y') }}</strong>
                </div>
                <div class="flex items-center gap-2 text-ink">
                    <svg width="14" height="14" class="text-muted shrink-0" fill="none" viewBox="0 0 24 24">
                        <path d="M12 20h9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    </svg>
                    Dernière modification <strong class="font-medium">{{ $establishment->updated_at->diffForHumans() }}</strong>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pt-2 border-t border-line flex items-center justify-between">
            <a href="{{ route('establishments.index') }}" class="text-sm text-muted hover:text-ink transition-colors">
                Annuler
            </a>

            <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg font-medium text-sm">
                Enregistrer
            </button>
        </div>

    </form>

</div>

@endsection