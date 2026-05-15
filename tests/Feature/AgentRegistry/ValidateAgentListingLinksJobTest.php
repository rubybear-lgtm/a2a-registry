<?php

use App\Actions\AgentRegistry\CheckAgentListingLinks;
use App\Jobs\AgentRegistry\ValidateAgentListingLinks;
use App\Models\AgentListing;
use App\Models\AgentListingRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('the link validation job records broken links and preserves existing warnings', function () {
    $listing = AgentListing::factory()->create([
        'raw_card_json' => [
            'name' => 'Route Planner',
            'description' => 'Plans routes.',
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
            'version' => '1.0.0',
            'documentationUrl' => 'https://docs.example.com/agent',
            'iconUrl' => 'https://cdn.example.com/icon.png',
            'capabilities' => [],
            'defaultInputModes' => ['text/plain'],
            'defaultOutputModes' => ['application/json'],
            'skills' => [],
        ],
        'validation_warnings_json' => [
            'securityRequirements' => ['legacy field mapped'],
        ],
    ]);

    AgentListingRevision::factory()->for($listing)->create([
        'revision_number' => 1,
        'validation_warnings_json' => [
            'securityRequirements' => ['legacy field mapped'],
        ],
    ]);

    Http::fake([
        'https://agents.example.com/a2a' => Http::response('', 200),
        'https://example.com' => Http::response('', 403),
        'https://docs.example.com/agent' => Http::response('', 404),
        'https://cdn.example.com/icon.png' => Http::response('', 500),
    ]);

    (new ValidateAgentListingLinks($listing->id))->handle(app(CheckAgentListingLinks::class));

    $warnings = $listing->fresh()->validation_warnings_json;

    expect($warnings['securityRequirements'])->toBe(['legacy field mapped']);
    expect($warnings['links'])->toHaveCount(2);
    expect(implode(' ', $warnings['links']))->toContain('documentationUrl');
    expect(implode(' ', $warnings['links']))->toContain('iconUrl');
    expect(implode(' ', $warnings['links']))->toContain('HTTP 404');
    expect(implode(' ', $warnings['links']))->toContain('HTTP 500');
    expect($listing->fresh()->latestRevision?->validation_warnings_json['links'] ?? null)->toHaveCount(2);
});

test('the link validation job clears stale link warnings when all links are reachable', function () {
    $listing = AgentListing::factory()->create([
        'raw_card_json' => [
            'name' => 'Route Planner',
            'description' => 'Plans routes.',
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
            'version' => '1.0.0',
            'documentationUrl' => 'https://docs.example.com/agent',
            'capabilities' => [],
            'defaultInputModes' => ['text/plain'],
            'defaultOutputModes' => ['application/json'],
            'skills' => [],
        ],
        'validation_warnings_json' => [
            'securityRequirements' => ['legacy field mapped'],
            'links' => ['stale warning'],
        ],
    ]);

    AgentListingRevision::factory()->for($listing)->create([
        'revision_number' => 1,
        'validation_warnings_json' => [
            'securityRequirements' => ['legacy field mapped'],
            'links' => ['stale warning'],
        ],
    ]);

    Http::fake([
        'https://agents.example.com/a2a' => Http::response('', 200),
        'https://example.com' => Http::response('', 200),
        'https://docs.example.com/agent' => Http::response('', 200),
    ]);

    (new ValidateAgentListingLinks($listing->id))->handle(app(CheckAgentListingLinks::class));

    expect($listing->fresh()->validation_warnings_json)->toBe([
        'securityRequirements' => ['legacy field mapped'],
    ]);
    expect($listing->fresh()->latestRevision?->validation_warnings_json)->toBe([
        'securityRequirements' => ['legacy field mapped'],
    ]);
});
