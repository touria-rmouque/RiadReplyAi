<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'review' => $this->raw_text,
            'language' => $this->language,
            'sentiment' => $this->sentiment,
            'status' => $this->status,
            'response' => $this->response_text,
            'is_flagged' => $this->is_flagged,

            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),

            'created_at' => $this->created_at,
        ];
    }
}