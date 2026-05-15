<?php

namespace App\Jobs\AgentRegistry;

use App\Actions\AgentRegistry\CheckAgentListingLinks;
use App\Models\AgentListing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ValidateAgentListingLinks implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $agentListingId) {}

    /**
     * Execute the job.
     */
    public function handle(CheckAgentListingLinks $checkAgentListingLinks): void
    {
        $agentListing = AgentListing::query()
            ->with('latestRevision')
            ->find($this->agentListingId);

        if (! $agentListing) {
            return;
        }

        $messages = $checkAgentListingLinks($agentListing);
        $warnings = is_array($agentListing->validation_warnings_json)
            ? $agentListing->validation_warnings_json
            : [];

        unset($warnings['links']);

        if ($messages !== []) {
            $warnings['links'] = $messages;
        }

        $agentListing->forceFill([
            'validation_warnings_json' => $warnings,
        ])->save();

        if ($agentListing->latestRevision) {
            $agentListing->latestRevision->forceFill([
                'validation_warnings_json' => $warnings,
            ])->save();
        }
    }

    /**
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping("agent-listing:{$this->agentListingId}"))
                ->shared()
                ->releaseAfter(60)
                ->expireAfter(180),
        ];
    }
}
