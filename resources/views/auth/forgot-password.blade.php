<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display font-semibold text-2xl text-ink">Mot de passe oublié</h1>
        <p class="text-muted text-sm mt-1.5 leading-relaxed">
            {{ __("Indique ton adresse email et on t'enverra un lien pour choisir un nouveau mot de passe.") }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="toi@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Envoyer le lien de réinitialisation') }}
        </x-primary-button>
    </form>

    <p class="text-center text-sm text-muted mt-8 pt-6 border-t border-line">
        <a href="{{ route('login') }}" class="text-accent font-medium hover:underline">{{ __('← Retour à la connexion') }}</a>
    </p>
</x-guest-layout>