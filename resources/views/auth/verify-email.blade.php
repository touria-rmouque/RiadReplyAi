<x-guest-layout>
    <div class="mb-6">
        <div class="w-11 h-11 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center mb-4">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" class="text-accent">
                <path d="M3 7l9 6 9-6M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="font-display font-semibold text-2xl text-ink">Vérifie ton adresse email</h1>
        <p class="text-muted text-sm mt-1.5 leading-relaxed">
            {{ __("Merci pour ton inscription ! Avant de commencer, clique sur le lien de vérification qu'on vient de t'envoyer par email. Si tu ne l'as pas reçu, on peut t'en renvoyer un.") }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 text-sm px-3 py-2.5">
            <svg width="14" height="14" class="shrink-0" viewBox="0 0 24 24" fill="none">
                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="font-medium">{{ __('Un nouveau lien de vérification a été envoyé à ton adresse email.') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <x-primary-button>
            {{ __('Renvoyer le lien de vérification') }}
        </x-primary-button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-6 pt-6 border-t border-line text-center">
        @csrf
        <button type="submit" class="text-sm text-muted hover:text-ink transition-colors">
            {{ __('Se déconnecter') }}
        </button>
    </form>
</x-guest-layout>