<?php

use App\Actions\AgentRegistry\IngestAgentCard;
use App\Enums\AgentListingStatus;
use App\Jobs\AgentRegistry\RefreshAgentListing;
use App\Models\AgentListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('the refresh job marks a listing degraded when the source agent card cannot be fetched', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'source_url' => 'https://broken.example.com/.well-known/agent-card.json',
        'refresh_due_at' => now()->subMinute(),
    ]);

    Http::fake([
        'https://broken.example.com/.well-known/agent-card.json' => Http::response('', 500),
    ]);

    (new RefreshAgentListing($listing->id))->handle(app(IngestAgentCard::class));

    expect($listing->fresh()->status)->toBe(AgentListingStatus::Degraded);
    expect($listing->fresh()->last_error)->toContain('Unable to fetch Agent Card. Received HTTP 500.');
});
