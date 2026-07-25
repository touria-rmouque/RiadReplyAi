<?php

namespace App\Actions\Review;

use App\Enums\ReviewStatus;
use App\Models\Review;

class MarkReviewRepliedAction
{
    public function execute(Review $review): Review
    {
        $review->update([
            'status' => ReviewStatus::Replied,
        ]);

        return $review->refresh();
    }
}