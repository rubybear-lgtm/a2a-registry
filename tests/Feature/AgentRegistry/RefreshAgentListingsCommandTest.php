<?php

use App\Enums\AgentListingStatus;
use App\Jobs\AgentRegistry\RefreshAgentListing;
use App\Models\AgentListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('the refresh command dispatches jobs only for due agent listings', function () {
    Queue::fake();

    $dueListing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'source_url' => 'https://due.example.com/.well-known/agent-card.json',
        'name' => 'Due Agent',
        'agent_version' => '1.0.0',
        'refresh_due_at' => now()->subMinute(),
        'etag' => '"due-v1"',
    ]);

    $futureListing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'source_url' => 'https://future.example.com/.well-known/agent-card.json',
        'name' => 'Future Agent',
        'agent_version' => '1.0.0',
        'refresh_due_at' => now()->addDay(),
        'etag' => '"future-v1"',
    ]);

    $disabledListing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Disabled,
        'source_url' => 'https://disabled.example.com/.well-known/agent-card.json',
        'name' => 'Disabled Agent',
        'agent_version' => '1.0.0',
        'refresh_due_at' => now()->subMinute(),
        'etag' => '"disabled-v1"',
    ]);

    $this->artisan('agent-listings:refresh')
        ->expectsOutputToContain('Dispatched 1 agent listing refresh job')
        ->assertSuccessful();

    Queue::assertPushed(RefreshAgentListing::class, function (RefreshAgentListing $job) use ($dueListing): bool {
        return $job->agentListingId === $dueListing->id;
    });
    Queue::assertPushed(RefreshAgentListing::class, 1);
    Queue::assertNotPushed(RefreshAgentListing::class, function (RefreshAgentListing $job) use ($futureListing, $disabledListing): bool {
        return in_array($job->agentListingId, [$futureListing->id, $disabledListing->id], true);
    });
});
