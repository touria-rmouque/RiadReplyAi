<?php

namespace App\Http\Controllers\Api;

use App\Actions\Review\ListReviewsAction;
use App\Actions\Review\MarkReviewRepliedAction;
use App\Actions\Review\StoreReviewAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewFilterRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewStatusRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    /**
     * Liste des avis.
     */
    public function index(
        ReviewFilterRequest $request,
        ListReviewsAction $action
    ) {
        $establishment = $request->user()->currentEstablishment;

        if (! $establishment) {
            return response()->json([
                'message' => 'Aucun établissement sélectionné.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $reviews = $action->execute(
            $establishment,
            $request->validated()
        );

        return ReviewResource::collection($reviews);
    }

    /**
     * Créer un nouvel avis.
     */
    public function store(
        StoreReviewRequest $request,
        StoreReviewAction $action
    ) {
        $review = $action->execute(
            $request->user(),
            $request->validated()
        );

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Afficher un avis.
     */
    public function show(Review $review)
    {
        $this->authorize('view', $review);

        return new ReviewResource(
            $review->load('tags')
        );
    }

    /**
     * Marquer un avis comme répondu.
     */
    public function markReplied(
        UpdateReviewStatusRequest $request,
        Review $review,
        MarkReviewRepliedAction $action
    ) {
        $this->authorize('update', $review);

        $action->execute($review);

        return response()->json([
            'message' => 'La réponse a été publiée.'
        ]);
    }

    /**
     * Supprimer un avis.
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return response()->json([
            'message' => 'Avis supprimé.'
        ]);
    }
}