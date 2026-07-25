<?php

namespace App\Actions\Review;

use App\Models\Establishment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListReviewsAction
{
    public function execute(
        Establishment $establishment,
        array $filters = [],
    ): LengthAwarePaginator {
        return $establishment
            ->reviews()
            ->with('tags')
            ->when(
                filled($filters['sentiment'] ?? null),
                fn ($query) => $query->where(
                    'sentiment',
                    $filters['sentiment']
                )
            )
            ->when(
                ! empty($filters['flagged']),
                fn ($query) => $query->where(
                    'is_flagged',
                    true
                )
            )
            ->when(
                filled($filters['search'] ?? null),
                fn ($query) => $query->where(
                    'raw_text',
                    'like',
                    '%' . $filters['search'] . '%'
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }
}