<?php

namespace App\Actions\Review;

use App\Jobs\AnalyseReviewJob;
use App\Models\Review;
use App\Models\User;

class StoreReviewAction
{
    public function execute(User $user, array $data): Review
    {
        $establishment = $user->currentEstablishment;

        abort_if($establishment === null, 403);

        $review = $establishment->reviews()->create([
            'raw_text' => $data['raw_text'],
            'rating'   => $data['rating'] ?? null,
        ]);

        AnalyseReviewJob::dispatch(
            reviewId: $review->id,
            rating: $review->rating,
        );

        return $review;
    }
}