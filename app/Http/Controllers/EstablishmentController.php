<?php

namespace App\Http\Controllers;

use App\Enums\EstablishmentTone;
use App\Enums\EstablishmentType;
use App\Http\Requests\StoreEstablishmentRequest;
use App\Models\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstablishmentController extends Controller
{
    /**
     * Liste des établissements de l'utilisateur.
     */
    public function index(Request $request): View
    {
        return view('establishments.index', [
            'establishments' => $request->user()
                ->establishments()
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        return view('establishments.create', [
            'types' => EstablishmentType::cases(),
            'tones' => EstablishmentTone::cases(),
        ]);
    }

    /**
     * Enregistre un établissement.
     */
   public function store(StoreEstablishmentRequest $request): RedirectResponse
{
    $establishment = $request->user()
        ->establishments()
        ->create($request->validated());

    if (!$request->user()->current_establishment_id) {
        $request->user()->update([
            'current_establishment_id' => $establishment->id,
        ]);
    }

    return redirect()
        ->route('establishments.index')
        ->with(
            'status',
            'Établissement créé avec succès.'
        );
}

    /**
     * Formulaire de modification.
     */
    public function edit(Establishment $establishment): View
    {
        $this->authorize('update', $establishment);

        return view('establishments.edit', [
            'establishment' => $establishment,
            'types' => EstablishmentType::cases(),
            'tones' => EstablishmentTone::cases(),
        ]);
    }

    /**
     * Met à jour un établissement.
     */
    public function update(
        StoreEstablishmentRequest $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->authorize('update', $establishment);

        $establishment->update(
            $request->validated()
        );

        return redirect()
            ->route('establishments.index')
            ->with(
                'status',
                'Établissement mis à jour.'
            );
    }

    /**
     * Supprime un établissement.
     */
    public function destroy(
        Establishment $establishment
    ): RedirectResponse {
        $this->authorize('delete', $establishment);

        $establishment->delete();

        return redirect()
            ->route('establishments.index')
            ->with(
                'status',
                'Établissement supprimé.'
            );
    }

    public function switch(Establishment $establishment): RedirectResponse
{
    abort_if(
        $establishment->user_id !== auth()->id(),
        403
    );

    auth()->user()->update([
        'current_establishment_id' => $establishment->id,
    ]);

    return redirect()
        ->route('dashboard')
        ->with(
            'status',
            'Établissement actif modifié.'
        );
}
}