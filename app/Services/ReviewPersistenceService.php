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

    public static function system(array $availableTags): string
{
    $slugs = implode(', ', array_keys($availableTags));

    return <<<PROMPT
Tu es un expert en gestion de la réputation en ligne spécialisé dans les établissements touristiques et de restauration.

Retourne UNIQUEMENT un JSON valide.

{
  "sentiment": "positive|neutral|negative",
  "language": "fr",
  "tags": [],
  "responseText": "",
  "isFlagged": false
}

RÈGLES

- sentiment : positive, neutral ou negative
- language : code ISO 639-1
- tags : utiliser uniquement :
{$slugs}
- responseText :
  - dans la langue de l'avis
  - 3 à 5 phrases
  - remercier le client
  - inclure le nom de l'établissement
  - respecter le ton demandé
  - si négatif : excuses + solution + invitation à revenir
- isFlagged : true uniquement si problème grave, sécurité, hygiène ou sentiment très négatif.

Ne retourne que le JSON, sans Markdown.
PROMPT;
}

    public function save(
        Review $review,
        ReviewAnalysisResult $analysis,
        ?int $rating = null,
    ): Review {

        $isFlagged = $analysis->isFlagged
            || $analysis->sentiment === Sentiment::Negative
            || ($rating !== null && $rating < 3);

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

        $tagIds = [];

        foreach ($analysis->tags as $slug) {

            if (! array_key_exists($slug, self::AVAILABLE_TAGS)) {
                continue;
            }

            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['label' => self::AVAILABLE_TAGS[$slug]]
            );

            $tagIds[] = $tag->id;
        }

        $review->tags()->sync($tagIds);

        Log::info('Review analysed', [
            'review_id' => $review->id,
            'sentiment' => $analysis->sentiment->value,
            'language'  => $analysis->language,
            'flagged'   => $isFlagged,
            'tags'      => $analysis->tags,
        ]);

        return $review->fresh(['tags']);
    }
}