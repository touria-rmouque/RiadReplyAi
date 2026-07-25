<?php

namespace App\Http\Controllers;

use App\Actions\Review\ListReviewsAction;
use App\Actions\Review\MarkReviewRepliedAction;
use App\Actions\Review\StoreReviewAction;
use App\Enums\Sentiment;
use App\Http\Requests\ReviewFilterRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewStatusRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Liste des avis.
     */
    public function index(
        ReviewFilterRequest $request,
        ListReviewsAction $action
    ): View|RedirectResponse {

        $establishment = $request->user()->currentEstablishment;

        if (! $establishment) {
            return redirect()->route('establishments.index');
        }

        return view('reviews.index', [
            'reviews' => $action->execute(
                $establishment,
                $request->validated()
            ),
            'sentiments' => Sentiment::cases(),
        ]);
    }

    /**
     * Formulaire de création.
     */
    public function create(): View|RedirectResponse
    {
        if (! auth()->user()->currentEstablishment()) {
            return redirect()->route('establishments.index');
        }

        return view('reviews.create');
    }

    /**
     * Enregistre un avis et lance l'analyse IA.
     */
    public function store(
        StoreReviewRequest $request,
        StoreReviewAction $action
    ): RedirectResponse {

        $review = $action->execute(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('reviews.show', $review)
            ->with(
                'status',
                'Votre avis a été enregistré. L’analyse IA est en cours...'
            );
    }

    /**
     * Affiche un avis.
     */
    public function show(Review $review): View
    {
        $this->authorize('view', $review);

        return view('reviews.show', [
            'review' => $review->load('tags'),
        ]);
    }

    /**
     * Marque un avis comme répondu.
     */
    public function markReplied(
        UpdateReviewStatusRequest $request,
        Review $review,
        MarkReviewRepliedAction $action
    ): RedirectResponse {

        $this->authorize('update', $review);

        $action->execute($review);

        return back()->with(
            'status',
            'La réponse a été marquée comme publiée.'
        );
    }
}