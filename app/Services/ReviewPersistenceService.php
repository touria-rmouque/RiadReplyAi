<?php

namespace App\Services;

use App\Ai\DTO\ReviewAnalysisResult;
use App\Enums\ReviewStatus;
use App\Enums\Sentiment;
use App\Models\Review;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class ReviewPersistenceService
{
    /**
     * Tags autorisés.
     */
    public const AVAILABLE_TAGS = [
        'cleanliness' => 'Propreté',
        'staff'       => 'Personnel',
        'breakfast'   => 'Petit-déjeuner',
        'food'        => 'Cuisine',
        'noise'       => 'Bruit',
        'location'    => 'Emplacement',
        'room'        => 'Chambre',
        'price'       => 'Rapport qualité/prix',
        'wifi'        => 'WiFi',
        'service'     => 'Service',
        'atmosphere'  => 'Ambiance',
        'decoration'  => 'Décoration',
    ];

    /**
     * Sauvegarde le résultat de l'analyse IA d'un avis.
     */
    public function save(
        Review $review,
        ReviewAnalysisResult $analysis,
        ?int $rating = null,
    ): Review {

        $isFlagged = $analysis->isFlagged
            || $analysis->sentiment === Sentiment::Negative
            || ($rating !== null && $rating < 3);

        /*
         * Mise à jour de l'avis.
         */
        $review->update([
            'sentiment'     => $analysis->sentiment->value,
            'language'      => $analysis->language,
            'response_text' => $analysis->responseText,
            'is_flagged'    => $isFlagged,
            'rating'        => $rating,
            'status'        => $isFlagged
                ? ReviewStatus::Flagged->value
                : ReviewStatus::Replied->value,
        ]);

        /*
         * Synchronisation des tags.
         */
        $tagIds = [];

        foreach ($analysis->tags as $slug) {

            // Ignore les tags qui ne sont pas autorisés.
            if (!array_key_exists($slug, self::AVAILABLE_TAGS)) {
                continue;
            }

            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['label' => self::AVAILABLE_TAGS[$slug]]
            );

            $tagIds[] = $tag->id;
        }

        $review->tags()->sync($tagIds);

        /*
         * Log de l'analyse.
         */
        Log::info('Review analysed', [
            'review_id' => $review->id,
            'sentiment' => $analysis->sentiment->value,
            'language'  => $analysis->language,
            'flagged'   => $isFlagged,
            'tags'      => $analysis->tags,
        ]);

        /*
         * Retourne l'avis avec ses tags actualisés.
         */
        return $review->fresh(['tags']);
    }
}