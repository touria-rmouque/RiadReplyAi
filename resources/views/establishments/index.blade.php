@extends('layouts.app')

@section('titre', 'Mes établissements')

@section('contenu')
<div class="max-w-7xl mx-auto">

    <div class="flex items-end justify-between mb-8">
        <div>
            <h1 class="font-display font-semibold text-2xl text-ink">Mes établissements</h1>
            <p class="text-muted text-sm mt-1">Gère les établissements pour lesquels tu souhaites analyser les avis.</p>
        </div>

        <a href="{{ route('establishments.create') }}" class="btn-primary text-white px-5 py-2.5 rounded-lg font-medium text-sm flex items-center gap-2 shrink-0">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Ajouter un établissement
        </a>
    </div>

    @if($establishments->isEmpty())

        <div class="card rounded-xl py-16 text-center border-dashed">
            <div class="w-14 h-14 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-center mx-auto mb-5">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" class="text-accent">
                    <path d="M4 20V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v15" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M9 20v-5h6v5" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M8 8h.01M12 8h.01M16 8h.01M8 12h.01M12 12h.01M16 12h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>

            <h2 class="font-display font-semibold text-xl text-ink mb-2">Aucun établissement</h2>
            <p class="text-muted text-sm mb-6">Commence par créer ton premier établissement.</p>

            <a href="{{ route('establishments.create') }}" class="btn-primary inline-flex items-center gap-2 text-white px-6 py-2.5 rounded-lg font-medium text-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Ajouter un établissement
            </a>
        </div>

    @else

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            @foreach($establishments as $establishment)
                @php $isActive = auth()->user()->currentEstablishment?->id == $establishment->id; @endphp

                <div class="card card-hover rounded-xl p-6 {{ $isActive ? '!border-accent/30' : '' }}">

                    <div class="flex justify-between items-start gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 shrink-0 rounded-xl bg-stone border border-line flex items-center justify-center">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" class="text-ink/70">
                                    <path d="M4 20V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v15" stroke="currentColor" stroke-width="1.6"/>
                                    <path d="M9 20v-5h6v5" stroke="currentColor" stroke-width="1.6"/>
                                    <path d="M8 8h.01M12 8h.01M16 8h.01M8 12h.01M12 12h.01M16 12h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <h2 class="font-display font-semibold text-ink truncate">{{ $establishment->name }}</h2>
                                <p class="text-sm text-muted">{{ $establishment->type->label() }}</p>
                            </div>
                        </div>

                        @if($isActive)
                            <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Actif
                            </span>
                        @endif
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4">
                        <div class="rounded-lg bg-stone px-3 py-2.5">
                            <p class="text-xs text-muted mb-0.5">Type</p>
                            <p class="text-sm font-medium text-ink">{{ $establishment->type->label() }}</p>
                        </div>
                        <div class="rounded-lg bg-stone px-3 py-2.5">
                            <p class="text-xs text-muted mb-0.5">Ton IA</p>
                            <p class="text-sm font-medium text-ink">{{ $establishment->tone->label() }}</p>
                        </div>
                    </div>

                    <div class="mt-5 pt-5 border-t border-line flex flex-wrap items-center gap-2">

                        @if(!$isActive)
                            <form method="POST" action="{{ route('establishments.switch', $establishment) }}">
                                @csrf
                                <button class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Utiliser
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('establishments.edit', $establishment) }}"
                           class="bg-white border border-line hover:border-accent/40 text-ink px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Modifier
                        </a>

                        <button type="button"
                            onclick="openDeleteModal('{{ route('establishments.destroy', $establishment) }}', '{{ addslashes($establishment->name) }}')"
                            class="ml-auto w-9 h-9 flex items-center justify-center rounded-lg border border-line text-muted hover:border-rose-300 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Supprimer">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6h16Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                </div>
            @endforeach

        </div>

    @endif

</div>

{{-- Modal de confirmation de suppression --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-ink/40" onclick="closeDeleteModal()"></div>

    <div class="relative card rounded-xl p-6 w-full max-w-md">
        <div class="w-11 h-11 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center mb-4">
            <svg width="20" height="20" class="text-rose-600" fill="none" viewBox="0 0 24 24">
                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6h16Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <h3 class="font-display font-semibold text-lg text-ink mb-1">Supprimer cet établissement ?</h3>
        <p class="text-sm text-muted mb-6 leading-relaxed">
            Tu es sur le point de supprimer <strong id="delete-modal-name" class="text-ink font-medium"></strong>.
            Cette action est irréversible : tous les avis et paramètres associés seront perdus.
        </p>

        <div class="flex items-center justify-end gap-3">
            <button type="button" onclick="closeDeleteModal()"
                class="bg-white border border-line hover:border-accent/40 text-ink px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                Annuler
            </button>

            <form id="delete-modal-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl, name) {
        document.getElementById('delete-modal-form').setAttribute('action', actionUrl);
        document.getElementById('delete-modal-name').textContent = name;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endsection