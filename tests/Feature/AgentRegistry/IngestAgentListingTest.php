<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['agent-registry.management_token' => 'registry-secret']);
});

test('admins can ingest a valid agent card with the management token', function () {
    Http::fake([
        'https://agents.example.com/.well-known/agent-card.json' => Http::response([
            'name' => 'Route Planner',
            'description' => 'Plans routes for travelers.',
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
            'version' => '1.2.3',
            'documentationUrl' => 'https://docs.example.com/route-planner',
            'capabilities' => [
                'streaming' => true,
                'pushNotifications' => false,
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
                    'examples' => ['Plan a route from Denver to Moab.'],
                ],
            ],
        ], 200, [
            'Content-Type' => 'application/json',
            'ETag' => '"agent-card-v1"',
            'Last-Modified' => 'Wed, 22 Apr 2026 12:00:00 GMT',
        ]),
    ]);

    $this->withToken('registry-secret')
        ->postJson(route('agent-listings.store'), [
            'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Route Planner')
        ->assertJsonPath('data.source_url', 'https://agents.example.com/.well-known/agent-card.json')
        ->assertJsonPath('data.preferred_interface_url', 'https://agents.example.com/a2a');

    $this->assertDatabaseHas('agent_listings', [
        'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        'name' => 'Route Planner',
        'agent_version' => '1.2.3',
    ]);

    $this->assertDatabaseHas('agent_listing_revisions', [
        'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        'response_status' => 200,
        'is_valid' => true,
    ]);
});

test('ingestion requires a valid management token', function () {
    $this->postJson(route('agent-listings.store'), [
        'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
    ])->assertForbidden();

    $this->withToken('wrong-token')
        ->postJson(route('agent-listings.store'), [
            'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        ])
        ->assertForbidden();
});

test('ingesting an existing source url is rejected', function () {
    Http::fake([
        'https://agents.example.com/.well-known/agent-card.json' => Http::response([
            'name' => 'Route Planner',
            'description' => 'Plans routes for travelers.',
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
            'version' => '1.2.3',
            'capabilities' => [
                'streaming' => true,
                'pushNotifications' => false,
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
        ]),
    ]);

    $this->withToken('registry-secret')
        ->postJson(route('agent-listings.store'), [
            'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        ])->assertCreated();

    $this->withToken('registry-secret')
        ->postJson(route('agent-listings.store'), [
            'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['source_url']);
});

test('ingestion returns validation errors for malformed agent cards', function () {
    Http::fake([
        'https://agents.example.com/.well-known/agent-card.json' => Http::response([
            'name' => 'Broken Agent',
            'description' => 'Missing required fields.',
        ], 200, [
            'Content-Type' => 'application/json',
        ]),
    ]);

    $this->withToken('registry-secret')
        ->postJson(route('agent-listings.store'), [
            'source_url' => 'https://agents.example.com/.well-known/agent-card.json',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'supportedInterfaces',
            'version',
            'capabilities',
            'defaultInputModes',
            'defaultOutputModes',
            'skills',
        ]);
});
