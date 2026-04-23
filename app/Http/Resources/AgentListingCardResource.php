<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentListingCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->public_id,
            'source_url' => $this->source_url,
            'status' => $this->status->value,
            'card' => $this->raw_card_json,
            'validation_warnings' => $this->validation_warnings_json ?? [],
            'etag' => $this->etag,
            'last_modified' => $this->last_modified,
            'content_hash' => $this->content_hash,
            'fetched_at' => $this->fetched_at?->toAtomString(),
            'validated_at' => $this->validated_at?->toAtomString(),
        ];
    }
}
