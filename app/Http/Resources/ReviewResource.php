<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => $this->user?->name ?? 'Anônimo',
            'rating' => (int) $this->rating,
            'date' => $this->created_at->toIso8601String(),
            'title' => $this->title ?? $this->comment,
            'content' => $this->comment,
            'verified' => (bool) $this->verified,
        ];
    }
}
