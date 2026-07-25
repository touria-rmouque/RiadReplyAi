<?php

namespace App\Actions\Dashboard;

use App\Enums\Sentiment;
use App\Models\Tag;
use App\Models\User;

class DashboardStatsAction
{
    public function execute(User $user): array
    {
        $establishment = $user->currentEstablishment;

        if (! $establishment) {
            return [
                'needsSetup' => true,
                'stats' => null,
                'topTags' => collect(),
                'recentReviews' => collect(),
            ];
        }

        $reviews = $establishment->reviews();

        $stats = [
            'total' => (clone $reviews)->count(),

            'positive' => (clone $reviews)
                ->where('sentiment', Sentiment::Positive)
                ->count(),

            'neutral' => (clone $reviews)
                ->where('sentiment', Sentiment::Neutral)
                ->count(),

            'negative' => (clone $reviews)
                ->where('sentiment', Sentiment::Negative)
                ->count(),

            'flagged' => (clone $reviews)
                ->where('is_flagged', true)
                ->count(),
        ];

        $topTags = Tag::query()
            ->withCount([
                'reviews' => fn ($query) => $query->where(
                    'establishment_id',
                    $establishment->id
                ),
            ])
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_count')
            ->limit(6)
            ->get();

        $recentReviews = $establishment
            ->reviews()
            ->with('tags')
            ->latest()
            ->take(5)
            ->get();

        return [
            'needsSetup' => false,
            'establishment' => $establishment,
            'stats' => $stats,
            'topTags' => $topTags,
            'recentReviews' => $recentReviews,
        ];
    }
}