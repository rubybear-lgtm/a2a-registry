<?php

use App\Enums\AgentListingStatus;
use App\Models\AgentListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

test('the public api lists visible agent listings', function () {
    AgentListing::factory()->create([
        'name' => 'Alpha Agent',
        'status' => AgentListingStatus::Active,
    ]);

    AgentListing::factory()->create([
        'name' => 'Beta Agent',
        'status' => AgentListingStatus::Stale,
    ]);

    AgentListing::factory()->create([
        'name' => 'Hidden Agent',
        'status' => AgentListingStatus::Disabled,
    ]);

    $this->getJson(route('api.v1.agent-listings.index'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('data.0.name', 'Alpha Agent')
            ->where('data.1.name', 'Beta Agent')
            ->missing('data.2')
            ->etc());
});

test('the public api list response matches the sdk contract', function () {
    $listing = AgentListing::factory()->create([
        'name' => 'SDK Agent',
        'status' => AgentListingStatus::Active,
        'provider_name' => 'SDK Provider',
        'skills_json' => [
            [
                'id' => 'sdk-search',
                'name' => 'SDK Search',
                'description' => 'Searches SDK-compatible registries.',
                'tags' => ['search', 'sdk'],
                'examples' => ['Find route-planning agents'],
                'inputModes' => ['text/plain'],
                'outputModes' => ['application/json'],
            ],
        ],
        'security_requirements_json' => [['bearerAuth' => []]],
    ]);

    $this->getJson(route('api.v1.agent-listings.index'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1, fn (AssertableJson $json) => $json
                ->whereAllType([
                    'id' => 'string',
                    'name' => 'string',
                    'description' => 'string|null',
                    'provider_name' => 'string|null',
                    'agent_version' => 'string',
                    'documentation_url' => 'string|null',
                    'icon_url' => 'string|null',
                    'preferred_interface_url' => 'string',
                    'preferred_protocol_binding' => 'string',
                    'preferred_protocol_version' => 'string',
                    'status' => 'string',
                    'has_auth' => 'boolean',
                    'supports_streaming' => 'boolean',
                    'supports_push_notifications' => 'boolean',
                    'supports_extended_agent_card' => 'boolean',
                    'default_input_modes' => 'array',
                    'default_output_modes' => 'array',
                    'supported_interfaces' => 'array',
                    'security_requirements' => 'array|null',
                    'skills' => 'array',
                    'source_url' => 'string',
                    'card_url' => 'string',
                    'fetched_at' => 'string|null',
                    'validated_at' => 'string|null',
                ])
                ->where('id', $listing->public_id)
                ->where('name', 'SDK Agent')
                ->where('provider_name', 'SDK Provider')
                ->where('skills.0.id', 'sdk-search')
                ->where('security_requirements.0.bearerAuth', [])
                ->etc()
            )
            ->has('links')
            ->whereAllType([
                'links.first' => 'string',
                'links.last' => 'string',
                'links.prev' => 'null',
                'links.next' => 'null',
                'meta.current_page' => 'integer',
                'meta.from' => 'integer',
                'meta.last_page' => 'integer',
                'meta.path' => 'string',
                'meta.per_page' => 'integer',
                'meta.to' => 'integer',
                'meta.total' => 'integer',
            ])
            ->where('meta.current_page', 1)
            ->where('meta.per_page', 15)
            ->where('meta.total', 1)
            ->etc());
});

test('the public api supports search and status filtering', function () {
    AgentListing::factory()->create([
        'name' => 'Research Atlas',
        'status' => AgentListingStatus::Active,
        'search_document' => 'research atlas analysis',
    ]);

    AgentListing::factory()->create([
        'name' => 'Planner',
        'status' => AgentListingStatus::Stale,
        'search_document' => 'route planning',
    ]);

    $this->getJson(route('api.v1.agent-listings.index', [
        'q' => 'research',
        'status' => AgentListingStatus::Active->value,
    ]))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Research Atlas');
});

test('the public api rejects unsupported status filters', function () {
    $this->getJson(route('api.v1.agent-listings.index', [
        'status' => 'disabled',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

test('the public api returns a single visible listing', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
    ]);

    $this->getJson(route('api.v1.agent-listings.show', $listing))
        ->assertOk()
        ->assertJsonPath('data.id', $listing->public_id)
        ->assertJsonPath('data.source_url', $listing->source_url);
});

test('the public api detail response matches the sdk contract', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'provider_name' => 'SDK Provider',
        'skills_json' => [
            [
                'id' => 'sdk-search',
                'name' => 'SDK Search',
                'description' => 'Searches SDK-compatible registries.',
                'tags' => ['search', 'sdk'],
                'examples' => ['Find route-planning agents'],
                'inputModes' => ['text/plain'],
                'outputModes' => ['application/json'],
            ],
        ],
    ]);

    $this->getJson(route('api.v1.agent-listings.show', $listing))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data')
            ->whereAllType([
                'data.id' => 'string',
                'data.name' => 'string',
                'data.description' => 'string|null',
                'data.provider_name' => 'string|null',
                'data.agent_version' => 'string',
                'data.documentation_url' => 'string|null',
                'data.icon_url' => 'string|null',
                'data.preferred_interface_url' => 'string',
                'data.preferred_protocol_binding' => 'string',
                'data.preferred_protocol_version' => 'string',
                'data.status' => 'string',
                'data.has_auth' => 'boolean',
                'data.supports_streaming' => 'boolean',
                'data.supports_push_notifications' => 'boolean',
                'data.supports_extended_agent_card' => 'boolean',
                'data.default_input_modes' => 'array',
                'data.default_output_modes' => 'array',
                'data.supported_interfaces' => 'array',
                'data.security_requirements' => 'array|null',
                'data.skills' => 'array',
                'data.source_url' => 'string',
                'data.card_url' => 'string',
                'data.fetched_at' => 'string|null',
                'data.validated_at' => 'string|null',
            ])
            ->where('data.id', $listing->public_id)
            ->where('data.provider_name', 'SDK Provider')
            ->where('data.skills.0.id', 'sdk-search')
            ->etc());
});

test('the public api returns the raw card for a visible listing', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'raw_card_json' => [
            'name' => 'Route Planner',
            'description' => 'Plans routes.',
        ],
    ]);

    $this->getJson(route('api.v1.agent-listings.card', $listing))
        ->assertOk()
        ->assertJsonPath('data.card.name', 'Route Planner');
});

test('the public api raw card response matches the sdk contract', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'raw_card_json' => [
            'name' => 'Route Planner',
            'description' => 'Plans routes.',
            'version' => '1.0.0',
        ],
        'validation_warnings_json' => [
            'securityRequirements' => ['legacy field mapped'],
        ],
    ]);

    $this->getJson(route('api.v1.agent-listings.card', $listing))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data')
            ->whereAllType([
                'data.id' => 'string',
                'data.source_url' => 'string',
                'data.status' => 'string',
                'data.card' => 'array',
                'data.validation_warnings' => 'array',
                'data.etag' => 'string|null',
                'data.last_modified' => 'string|null',
                'data.content_hash' => 'string|null',
                'data.fetched_at' => 'string|null',
                'data.validated_at' => 'string|null',
            ])
            ->where('data.id', $listing->public_id)
            ->where('data.card.version', '1.0.0')
            ->where('data.validation_warnings.securityRequirements.0', 'legacy field mapped')
            ->etc());
});

test('the public api serializes empty validation warnings as an object for sdk consumers', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'raw_card_json' => [
            'name' => 'Route Planner',
            'description' => 'Plans routes.',
            'version' => '1.0.0',
        ],
        'validation_warnings_json' => [],
    ]);

    $response = $this->getJson(route('api.v1.agent-listings.card', $listing))
        ->assertOk();

    $payload = json_decode($response->getContent());

    expect($payload->data->validation_warnings)->toBeObject();
    expect((array) $payload->data->validation_warnings)->toBe([]);
});

test('the public api hides disabled listings', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Disabled,
    ]);

    $this->getJson(route('api.v1.agent-listings.show', $listing))
        ->assertNotFound();

    $this->getJson(route('api.v1.agent-listings.card', $listing))
        ->assertNotFound();
});
