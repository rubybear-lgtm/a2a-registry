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
            'validation_warnings' => $this->formatValidationWarnings(),
            'etag' => $this->etag,
            'last_modified' => $this->last_modified,
            'content_hash' => $this->content_hash,
            'fetched_at' => $this->fetched_at?->toAtomString(),
            'validated_at' => $this->validated_at?->toAtomString(),
        ];
    }

    /**
     * @return array<string, array<int, string>>|object
     */
    private function formatValidationWarnings(): array|object
    {
        $warnings = $this->validation_warnings_json;

        if (! is_array($warnings) || $warnings === []) {
            return (object) [];
        }

        if (array_is_list($warnings)) {
            return [
                'general' => array_values(array_map(
                    static fn (mixed $warning): string => (string) $warning,
                    $warnings,
                )),
            ];
        }

        return collect($warnings)
            ->map(static fn (mixed $messages): array => array_values(array_map(
                static fn (mixed $message): string => (string) $message,
                is_array($messages) ? $messages : [$messages],
            )))
            ->all();
    }
}
