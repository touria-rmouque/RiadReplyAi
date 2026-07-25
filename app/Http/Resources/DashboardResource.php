<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'needs_setup' => $this['needsSetup'],

            'establishment' => isset($this['establishment'])
                ? new EstablishmentResource($this['establishment'])
                : null,

            'stats' => $this['stats'],

            'top_tags' => TagResource::collection(
                $this['topTags']
            ),

            'recent_reviews' => ReviewResource::collection(
                $this['recentReviews']
            ),
        ];
    }
}