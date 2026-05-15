<?php

namespace App\Console\Commands;

use App\Enums\AgentListingStatus;
use App\Jobs\AgentRegistry\RefreshAgentListing;
use App\Models\AgentListing;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('agent-listings:refresh {--all : Refresh all listings regardless of refresh_due_at} {--limit=25 : Maximum number of listings to refresh}')]
#[Description('Refresh due agent listings from their source URLs')]
class RefreshAgentListings extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $listings = AgentListing::query()
            ->where('status', '!=', AgentListingStatus::Disabled)
            ->when(
                ! $this->option('all'),
                fn ($query) => $query
                    ->whereNotNull('refresh_due_at')
                    ->where('refresh_due_at', '<=', now()),
            )
            ->orderBy('refresh_due_at')
            ->limit($limit)
            ->get();

        if ($listings->isEmpty()) {
            $this->info('No agent listings are due for refresh.');

            return self::SUCCESS;
        }

        $listings->each(fn (AgentListing $agentListing) => RefreshAgentListing::dispatch($agentListing->id));

        $this->info(sprintf(
            'Dispatched %d agent listing refresh job(s).',
            $listings->count(),
        ));

        return self::SUCCESS;
    }
}
