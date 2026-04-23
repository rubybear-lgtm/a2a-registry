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

test('the public api returns a single visible listing', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
    ]);

    $this->getJson(route('api.v1.agent-listings.show', $listing))
        ->assertOk()
        ->assertJsonPath('data.id', $listing->public_id)
        ->assertJsonPath('data.source_url', $listing->source_url);
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

test('the public api hides disabled listings', function () {
    $listing = AgentListing::factory()->create([
        'status' => AgentListingStatus::Disabled,
    ]);

    $this->getJson(route('api.v1.agent-listings.show', $listing))
        ->assertNotFound();

    $this->getJson(route('api.v1.agent-listings.card', $listing))
        ->assertNotFound();
});
