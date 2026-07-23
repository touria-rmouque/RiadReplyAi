@extends('layouts.app')
@section('titre', 'Modifier le profil')

@section('contenu')
<div class="max-w-xl mx-auto space-y-6">

    <div>
        <h1 class="font-display font-semibold text-2xl text-ink">Modifier le profil</h1>
    </div>

    {{-- Informations du profil --}}
    <div class="card rounded-xl p-6">
        <h2 class="font-display font-semibold text-ink mb-1">Informations</h2>
        <p class="text-sm text-muted mb-5">Mets à jour ton nom et ton adresse email.</p>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="name" :value="__('Nom')" />
                <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 text-xs text-accent">
                        {{ __('Ton adresse email n\'est pas vérifiée.') }}
                        <button form="send-verification" class="underline hover:no-underline">
                            {{ __('Renvoyer le lien de vérification.') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs text-emerald-600 font-medium">
                            {{ __('Un nouveau lien de vérification a été envoyé à ton adresse.') }}
                        </p>
                    @endif
                @endif
            </div>

            <div class="pt-2 border-t border-line">
                <x-primary-button class="!w-auto px-6">{{ __('Enregistrer') }}</x-primary-button>
            </div>
        </form>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                @csrf
            </form>
        @endif
    </div>

    {{-- Mot de passe --}}
    <div class="card rounded-xl p-6">
        <h2 class="font-display font-semibold text-ink mb-1">Mot de passe</h2>
        <p class="text-sm text-muted mb-5">Utilise un mot de passe long et unique pour sécuriser ton compte.</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <x-input-label for="current_password" :value="__('Mot de passe actuel')" />
                <x-text-input id="current_password" name="current_password" type="password" autocomplete="current-password" />
                <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Nouveau mot de passe')" />
                <x-text-input id="password" name="password" type="password" autocomplete="new-password" />
                <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
            </div>

            <div class="pt-2 border-t border-line">
                <x-primary-button class="!w-auto px-6">{{ __('Mettre à jour') }}</x-primary-button>
            </div>
        </form>
    </div>

    {{-- Suppression du compte --}}
    <div class="card rounded-xl p-6 !border-rose-200">
        <h2 class="font-display font-semibold text-ink mb-1">Supprimer le compte</h2>
        <p class="text-sm text-muted mb-5">
            Une fois ton compte supprimé, toutes ses données seront définitivement effacées. Télécharge ce dont tu as besoin avant.
        </p>

        <button type="button" onclick="document.getElementById('confirm-deletion-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 bg-white border border-rose-300 text-rose-600 hover:bg-rose-50 px-5 py-2.5 rounded-lg font-medium text-sm transition-colors">
            {{ __('Supprimer le compte') }}
        </button>

        {{-- Modal simple --}}
        <div id="confirm-deletion-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-ink/40" onclick="document.getElementById('confirm-deletion-modal').classList.add('hidden')"></div>

            <div class="relative card rounded-xl p-6 w-full max-w-md">
                <h3 class="font-display font-semibold text-ink mb-1">Es-tu sûr de vouloir supprimer ton compte ?</h3>
                <p class="text-sm text-muted mb-5">
                    Cette action est irréversible. Entre ton mot de passe pour confirmer la suppression définitive de ton compte.
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('delete')

                    <div>
                        <x-input-label for="password_delete" value="{{ __('Mot de passe') }}" class="sr-only" />
                        <x-text-input id="password_delete" name="password" type="password" placeholder="{{ __('Mot de passe') }}" />
                        <x-input-error class="mt-2" :messages="$errors->userDeletion->get('password')" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <x-secondary-button type="button" class="!w-auto px-5"
                            onclick="document.getElementById('confirm-deletion-modal').classList.add('hidden')">
                            {{ __('Annuler') }}
                        </x-secondary-button>
                        <x-danger-button class="!w-auto px-5">{{ __('Supprimer le compte') }}</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection