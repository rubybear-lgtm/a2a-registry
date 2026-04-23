<?php

namespace App\Actions\AgentRegistry;

use App\Enums\AgentListingStatus;
use App\Models\AgentListing;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IngestAgentCard
{
    public function __construct(
        protected FetchAgentCard $fetchAgentCard,
        protected ValidateAgentCard $validateAgentCard,
        protected NormalizeAgentCard $normalizeAgentCard,
    ) {}

    public function __invoke(string $sourceUrl): AgentListing
    {
        $existingListing = AgentListing::query()->where('source_url', $sourceUrl)->first();

        $fetchedCard = ($this->fetchAgentCard)(
            $sourceUrl,
            $existingListing?->etag,
            $existingListing?->last_modified,
        );

        if ($fetchedCard['not_modified']) {
            if (! $existingListing) {
                throw ValidationException::withMessages([
                    'source_url' => 'The remote endpoint reported no changes for a listing that does not exist locally.',
                ]);
            }

            $existingListing->fill([
                'last_http_status' => $fetchedCard['response_status'],
                'fetched_at' => $fetchedCard['fetched_at'],
                'refresh_due_at' => now()->addMinutes((int) config('agent-registry.refresh_interval_minutes')),
            ])->save();

            return $existingListing->refresh();
        }

        try {
            $validatedCard = ($this->validateAgentCard)($fetchedCard['raw_card']);
        } catch (ValidationException $exception) {
            if ($existingListing) {
                $this->recordInvalidRevision($existingListing, $fetchedCard, $exception);
            }

            throw $exception;
        }

        $normalizedCard = ($this->normalizeAgentCard)(
            $validatedCard['card'],
            $validatedCard['warnings'],
        );

        return DB::transaction(function () use ($existingListing, $fetchedCard, $normalizedCard, $validatedCard): AgentListing {
            $agentListing = $existingListing ?? new AgentListing;
            $agentListing->fill([
                ...$normalizedCard,
                'source_url' => $fetchedCard['source_url'],
                'status' => AgentListingStatus::Active,
                'etag' => $fetchedCard['etag'],
                'last_modified' => $fetchedCard['last_modified'],
                'content_type' => $fetchedCard['content_type'],
                'content_hash' => $fetchedCard['content_hash'],
                'last_http_status' => $fetchedCard['response_status'],
                'last_error' => null,
                'fetched_at' => $fetchedCard['fetched_at'],
                'validated_at' => now(),
                'refresh_due_at' => now()->addMinutes((int) config('agent-registry.refresh_interval_minutes')),
            ]);
            $agentListing->save();

            $nextRevisionNumber = (int) $agentListing->revisions()->max('revision_number') + 1;

            $agentListing->revisions()->create([
                'revision_number' => $nextRevisionNumber,
                'source_url' => $fetchedCard['source_url'],
                'response_status' => $fetchedCard['response_status'],
                'content_type' => $fetchedCard['content_type'],
                'etag' => $fetchedCard['etag'],
                'last_modified' => $fetchedCard['last_modified'],
                'raw_body' => $fetchedCard['raw_body'],
                'raw_card_json' => $fetchedCard['raw_card'],
                'normalized_card_json' => $validatedCard['card'],
                'validation_errors_json' => null,
                'validation_warnings_json' => $validatedCard['warnings'],
                'content_hash' => $fetchedCard['content_hash'],
                'is_valid' => true,
                'fetched_at' => $fetchedCard['fetched_at'],
            ]);

            return $agentListing->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $fetchedCard
     */
    private function recordInvalidRevision(
        AgentListing $agentListing,
        array $fetchedCard,
        ValidationException $exception,
    ): void {
        DB::transaction(function () use ($agentListing, $fetchedCard, $exception): void {
            $nextRevisionNumber = (int) $agentListing->revisions()->max('revision_number') + 1;

            $agentListing->fill([
                'status' => AgentListingStatus::Degraded,
                'last_http_status' => $fetchedCard['response_status'],
                'last_error' => 'Fetched Agent Card failed validation.',
                'fetched_at' => $fetchedCard['fetched_at'],
                'refresh_due_at' => now()->addMinutes((int) config('agent-registry.refresh_interval_minutes')),
            ])->save();

            $agentListing->revisions()->create([
                'revision_number' => $nextRevisionNumber,
                'source_url' => $fetchedCard['source_url'],
                'response_status' => $fetchedCard['response_status'],
                'content_type' => $fetchedCard['content_type'],
                'etag' => $fetchedCard['etag'],
                'last_modified' => $fetchedCard['last_modified'],
                'raw_body' => $fetchedCard['raw_body'],
                'raw_card_json' => $fetchedCard['raw_card'],
                'normalized_card_json' => null,
                'validation_errors_json' => $exception->errors(),
                'validation_warnings_json' => [],
                'content_hash' => $fetchedCard['content_hash'],
                'is_valid' => false,
                'fetched_at' => $fetchedCard['fetched_at'],
            ]);
        });
    }
}
