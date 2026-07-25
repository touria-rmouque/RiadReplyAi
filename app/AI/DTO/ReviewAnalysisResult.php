<?php

namespace App\Ai\DTO;

use App\Enums\Sentiment;

class ReviewAnalysisResult
{
    public function __construct(
        public Sentiment $sentiment,
        public string $language,
        public array $tags,
        public string $responseText,
        public bool $isFlagged,
    ) {}
}
