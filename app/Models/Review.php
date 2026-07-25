<?php

namespace App\Models;

use App\Enums\ReviewStatus;
use App\Enums\Sentiment;
use App\Policies\ReviewPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[UsePolicy(ReviewPolicy::class)]
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'raw_text',
        'response_text',
        'sentiment',
        'language',
        'is_flagged',
        'status',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'sentiment'  => Sentiment::class,
            'status'     => ReviewStatus::class,
            'is_flagged' => 'boolean',
            'rating'     => 'integer',
        ];
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'review_tag');
    }
}