<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstablishmentRequest;
use App\Http\Resources\EstablishmentResource;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EstablishmentController extends Controller
{
    /**
     * Liste des établissements de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        return EstablishmentResource::collection(
            $request->user()
                ->establishments()
                ->latest()
                ->get()
        );
    }

    /**
     * Création d'un établissement.
     */
    public function store(StoreEstablishmentRequest $request)
    {
        $establishment = $request->user()
            ->establishments()
            ->create($request->validated());

        return new EstablishmentResource($establishment);
    }

    /**
     * Afficher un établissement.
     */
    public function show(Establishment $establishment)
    {
        $this->authorize('view', $establishment);

        return new EstablishmentResource($establishment);
    }

    /**
     * Modifier un établissement.
     */
    public function update(
        StoreEstablishmentRequest $request,
        Establishment $establishment
    ) {
        $this->authorize('update', $establishment);

        $establishment->update(
            $request->validated()
        );

        return new EstablishmentResource($establishment);
    }

    /**
     * Supprimer un établissement.
     */
    public function destroy(Establishment $establishment)
    {
        $this->authorize('delete', $establishment);

        $establishment->delete();

        return response()->json([
            'message' => 'Établissement supprimé.'
        ], Response::HTTP_OK);
    }
}