<?php

namespace App\Jobs\AgentRegistry;

use App\Actions\AgentRegistry\IngestAgentCard;
use App\Enums\AgentListingStatus;
use App\Models\AgentListing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Validation\ValidationException;
use Throwable;

class RefreshAgentListing implements ShouldQueue
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
    public function handle(IngestAgentCard $ingestAgentCard): void
    {
        $agentListing = AgentListing::query()->find($this->agentListingId);

        if (! $agentListing) {
            return;
        }

        try {
            $agentListing = $ingestAgentCard($agentListing->source_url);
        } catch (ValidationException $exception) {
            $this->markRefreshFailure(
                $agentListing,
                collect($exception->errors())->flatten()->implode(' '),
            );

            return;
        } catch (Throwable $exception) {
            $this->markRefreshFailure($agentListing, $exception->getMessage());

            report($exception);

            throw $exception;
        }

        ValidateAgentListingLinks::dispatch($agentListing->id);
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

    private function markRefreshFailure(AgentListing $agentListing, string $message): void
    {
        $agentListing->forceFill([
            'status' => AgentListingStatus::Degraded,
            'last_error' => $message,
            'refresh_due_at' => now()->addMinutes((int) config('agent-registry.refresh_interval_minutes')),
        ])->save();
    }
}
