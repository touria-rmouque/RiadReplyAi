<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display font-semibold text-2xl text-ink">Connexion</h1>
        <p class="text-muted text-sm mt-1.5">Accède à ton espace gérant</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="toi@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me + Forgot password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2 text-sm text-muted cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-line text-accent shadow-sm focus:ring-accent" name="remember">
                {{ __('Se souvenir de moi') }}
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-accent hover:underline" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <x-primary-button>
            {{ __('Se connecter') }}
        </x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="text-center text-sm text-muted mt-8 pt-6 border-t border-line">
            {{ __("Pas de compte ?") }}
            <a href="{{ route('register') }}" class="text-accent font-medium hover:underline">{{ __('Créer un compte') }}</a>
        </p>
    @endif
</x-guest-layout>