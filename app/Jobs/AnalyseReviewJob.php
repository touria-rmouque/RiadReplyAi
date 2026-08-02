<?php

namespace App\Jobs;

use App\Ai\Agents\ReviewAgent;
use App\Ai\DTO\ReviewAnalysisResult;
use App\Ai\Prompts\ReviewPrompt;
use App\Enums\Sentiment;
use App\Models\Review;
use App\Services\ReviewPersistenceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyseReviewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function __construct(
        public readonly int $reviewId,
        public readonly ?int $rating = null,
    ) {}

    public function handle(
        ReviewAgent $agent,
        ReviewPersistenceService $persistenceService,
    ): void {

        $review = Review::with('establishment')
            ->findOrFail($this->reviewId);

       $prompt = ReviewPrompt::user(
    review: $review->raw_text,
    establishment: $review->establishment,
    rating: $this->rating,
);
        $response = $agent->prompt($prompt);

        $data = json_decode($response->text, true);

        if (! is_array($data)) {
            throw new \RuntimeException('Réponse IA invalide.');
        }

        $result = new ReviewAnalysisResult(
        sentiment: Sentiment::from($data['sentiment']),
        language: $data['language'],
        tags: $data['tags'] ?? [],
        responseText: $data['response_text'],
        isFlagged: $data['is_flagged'] ?? false,
);

        $persistenceService->save(
            review: $review,
            analysis: $result,
            rating: $this->rating,
        );
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Analyse IA échouée', [
            'review_id' => $this->reviewId,
            'message' => $exception->getMessage(),
        ]);
    }
}