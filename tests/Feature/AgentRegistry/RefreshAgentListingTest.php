<?php

use App\Enums\AgentListingStatus;
use App\Models\AgentListing;
use App\Models\AgentListingRevision;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('operators can refresh an existing agent listing', function () {
    config()->set('agent-registry.operator_emails', ['operator@example.com']);

    $listing = AgentListing::factory()->create([
        'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        'name' => 'Route Planner',
        'description' => 'Old description.',
        'agent_version' => '1.0.0',
        'etag' => '"agent-card-v1"',
        'content_hash' => 'old-hash',
        'status' => AgentListingStatus::Active,
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

    $user = User::factory()->create([
        'email' => 'operator@example.com',
    ]);

    $this->actingAs($user)
        ->postJson(route('agent-listings.refresh', $listing))
        ->assertOk()
        ->assertJsonPath('data.name', 'Route Planner')
        ->assertJsonPath('data.status', 'active');

    $listing->refresh();

    expect($listing->agent_version)->toBe('1.3.0');
    expect($listing->description)->toBe('Updated description.');
    expect($listing->etag)->toBe('"agent-card-v2"');
    expect($listing->revisions()->count())->toBe(2);
});

test('refresh returns the existing listing when the agent card is not modified', function () {
    config()->set('agent-registry.operator_emails', ['operator@example.com']);

    $listing = AgentListing::factory()->create([
        'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        'etag' => '"agent-card-v1"',
    ]);

    Http::fake([
        'https://agents.example.com/.well-known/agent-card.json' => Http::response('', 304, [
            'ETag' => '"agent-card-v1"',
        ]),
    ]);

    $user = User::factory()->create([
        'email' => 'operator@example.com',
    ]);

    $this->actingAs($user)
        ->postJson(route('agent-listings.refresh', $listing))
        ->assertOk()
        ->assertJsonPath('data.id', $listing->public_id);

    expect($listing->fresh()->revisions()->count())->toBe(0);
});
