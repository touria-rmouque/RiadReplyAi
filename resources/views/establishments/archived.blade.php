@extends('layouts.app')
@section('titre', 'Établissements archivés')
@section('contenu')
<div class="max-w-7xl mx-auto ">

    {{-- En-tête --}}
    <div class="flex items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display font-semibold text-2xl text-ink">
                Établissements archivés
            </h1>
            <p class="text-sm text-slate-500 mt-2 max-w-md">
                Les établissements archivés peuvent être restaurés ou supprimés définitivement.
                Cette action est irréversible.
            </p>
        </div>

        <a href="{{ route('establishments.index') }}"
           class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-slate-200
                  text-sm font-medium text-slate-600 hover:text-[#1F3B57] hover:border-[#1F3B57]/30
                  hover:bg-[#1F3B57]/5 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
            </svg>
            Retour
        </a>
    </div>

    @if($establishments->isEmpty())
        {{-- État vide --}}
        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-10 py-16 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#1F3B57]/5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#1F3B57]/40" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="5" rx="1"/>
                    <path d="M5 9v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9"/>
                    <path d="M10 13h4"/>
                </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-700">
                Aucun établissement archivé
            </h2>
            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                Les établissements que vous supprimez apparaîtront ici avant leur suppression définitive.
            </p>
        </div>
    @else
        {{-- Liste --}}
        <div class="space-y-3">
            @foreach($establishments as $establishment)
                <div class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4
                            rounded-xl border border-slate-200 bg-white p-5
                            hover:border-[#1F3B57]/20 hover:shadow-sm transition-all">

                    <div class="flex items-center gap-4 min-w-0">
                        <div class="shrink-0 flex h-11 w-11 items-center justify-center rounded-lg bg-[#B8860B]/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#B8860B]" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="5" rx="1"/>
                                <path d="M5 9v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9"/>
                                <path d="M10 13h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="font-semibold text-slate-800 truncate">
                                {{ $establishment->name }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5 inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>
                                </svg>
                                Archivé le {{ $establishment->deleted_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2 shrink-0 sm:ml-4">
                        <form method="POST" action="{{ route('establishments.restore', $establishment->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2
                                       text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/>
                                </svg>
                                Restaurer
                            </button>
                        </form>

                        <form id="delete-form-{{ $establishment->id }}"
                              method="POST"
                              action="{{ route('establishments.force-delete', $establishment->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="openDeleteModal('delete-form-{{ $establishment->id }}', @js($establishment->name))"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-white px-4 py-2
                                       text-sm font-medium text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600
                                       transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                </svg>
                                <span class="hidden sm:inline">Supprimer définitivement</span>
                                <span class="sm:hidden">Supprimer</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Modal de confirmation de suppression --}}
<div id="delete-modal"
     class="hidden fixed inset-0 z-50 items-center justify-center bg-[#1F3B57]/40 backdrop-blur-[2px] px-4">
    <div id="delete-modal-panel"
         class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl scale-95 opacity-0 transition-all duration-150">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-rose-600" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 9v4"/><path d="M12 17h.01"/>
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
            </svg>
        </div>

        <h2 class="mt-4 text-base font-semibold text-slate-800">
            Supprimer définitivement ?
        </h2>
        <p class="mt-2 text-sm text-slate-500">
            <span id="delete-modal-name" class="font-medium text-slate-700"></span>
            sera supprimé de façon définitive, avec tout son historique d'avis. Cette action est irréversible.
        </p>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" onclick="closeDeleteModal()"
                class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600
                       hover:bg-slate-50 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="confirmDelete()"
                class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white
                       hover:bg-rose-700 transition-colors">
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>

<script>
    let deleteFormId = null;

    function openDeleteModal(formId, establishmentName) {
        deleteFormId = formId;
        document.getElementById('delete-modal-name').textContent = establishmentName;

        const modal = document.getElementById('delete-modal');
        const panel = document.getElementById('delete-modal-panel');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            panel.classList.remove('scale-95', 'opacity-0');
        });
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        const panel = document.getElementById('delete-modal-panel');
        panel.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            deleteFormId = null;
        }, 150);
    }

    function confirmDelete() {
        if (deleteFormId) {
            document.getElementById(deleteFormId).submit();
        }
    }

    document.getElementById('delete-modal').addEventListener('click', (e) => {
        if (e.target.id === 'delete-modal') closeDeleteModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !document.getElementById('delete-modal').classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
</script>
@endsection