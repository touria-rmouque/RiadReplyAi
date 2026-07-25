<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    private function ownsReview(User $user, Review $review): bool
    {
        return $review->establishment->user_id === $user->id;
    }

    public function view(User $user, Review $review): bool
    {
        return $this->ownsReview($user, $review);
    }

    public function update(User $user, Review $review): bool
    {
        return $this->ownsReview($user, $review);
    }

    public function delete(User $user, Review $review): bool
    {
        return $this->ownsReview($user, $review);
    }
}