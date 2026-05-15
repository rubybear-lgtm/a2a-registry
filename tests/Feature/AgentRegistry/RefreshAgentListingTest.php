<?php

use App\Actions\AgentRegistry\IngestAgentCard;
use App\Enums\AgentListingStatus;
use App\Jobs\AgentRegistry\RefreshAgentListing;
use App\Jobs\AgentRegistry\ValidateAgentListingLinks;
use App\Models\AgentListing;
use App\Models\AgentListingRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('the refresh job updates an existing agent listing and queues link validation', function () {
    Queue::fake();

    $listing = AgentListing::factory()->create([
        'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        'name' => 'Route Planner',
        'description' => 'Old description.',
        'agent_version' => '1.0.0',
        'etag' => '"agent-card-v1"',
        'content_hash' => 'old-hash',
        'status' => AgentListingStatus::Active,
        'refresh_due_at' => now()->subMinute(),
    ]);

    AgentListingRevision::factory()->for($listing)->create([
        'revision_number' => 1,
        'source_url' => $listing->source_url,
    ]);

    Http::fake([
        'https://agents.example.com/.well-known/agent-card.json' => Http::response([
            'name' => 'Route Planner',
            'description' => 'Updated description.',
            'supportedInterfaces' => [
                [
                    'url' => 'https://agents.example.com/a2a',
                    'protocolBinding' => 'JSONRPC',
                    'protocolVersion' => '1.0',
                ],
            ],
            'provider' => [
                'organization' => 'Example Agents',
                'url' => 'https://example.com',
            ],
            'version' => '1.3.0',
            'capabilities' => [
                'streaming' => true,
                'pushNotifications' => true,
                'extendedAgentCard' => false,
            ],
            'defaultInputModes' => ['text/plain'],
            'defaultOutputModes' => ['application/json'],
            'skills' => [
                [
                    'id' => 'route-planning',
                    'name' => 'Route Planning',
                    'description' => 'Creates travel itineraries and routes.',
                    'tags' => ['travel', 'maps'],
                ],
            ],
        ], 200, [
            'Content-Type' => 'application/json',
            'ETag' => '"agent-card-v2"',
            'Last-Modified' => 'Thu, 23 Apr 2026 12:00:00 GMT',
        ]),
    ]);

    (new RefreshAgentListing($listing->id))->handle(app(IngestAgentCard::class));

    $listing->refresh();

    expect($listing->agent_version)->toBe('1.3.0');
    expect($listing->description)->toBe('Updated description.');
    expect($listing->etag)->toBe('"agent-card-v2"');
    expect($listing->revisions()->count())->toBe(2);
    Queue::assertPushed(ValidateAgentListingLinks::class, function (ValidateAgentListingLinks $job) use ($listing): bool {
        return $job->agentListingId === $listing->id;
    });
});

test('the refresh job preserves a listing when the agent card is not modified', function () {
    Queue::fake();

    $listing = AgentListing::factory()->create([
        'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        'etag' => '"agent-card-v1"',
        'refresh_due_at' => now()->subMinute(),
    ]);

    Http::fake([
        'https://agents.example.com/.well-known/agent-card.json' => Http::response('', 304, [
            'ETag' => '"agent-card-v1"',
        ]),
    ]);

    (new RefreshAgentListing($listing->id))->handle(app(IngestAgentCard::class));

    expect($listing->fresh()->revisions()->count())->toBe(0);
    expect($listing->fresh()->last_http_status)->toBe(304);
    Queue::assertPushed(ValidateAgentListingLinks::class, function (ValidateAgentListingLinks $job) use ($listing): bool {
        return $job->agentListingId === $listing->id;
    });
});
