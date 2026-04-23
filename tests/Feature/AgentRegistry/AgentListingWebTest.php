<?php

use App\Enums\AgentListingStatus;
use App\Models\AgentListing;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests can view the agents listing page', function () {
    AgentListing::factory()->create([
        'name' => 'Alpha Agent',
        'status' => AgentListingStatus::Active,
    ]);

    $this->get(route('agents.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('agents/index')
            ->has('agents.data', 1)
            ->where('agents.data.0.name', 'Alpha Agent')
        );
});

test('the agents listing page only shows publicly visible agents', function () {
    AgentListing::factory()->create(['status' => AgentListingStatus::Active]);
    AgentListing::factory()->create(['status' => AgentListingStatus::Stale]);
    AgentListing::factory()->create(['status' => AgentListingStatus::Degraded]);
    AgentListing::factory()->create(['status' => AgentListingStatus::Disabled]);
    AgentListing::factory()->create(['status' => AgentListingStatus::Error]);

    $this->get(route('agents.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('agents.data', 3));
});

test('the agents listing page supports search', function () {
    AgentListing::factory()->create([
        'name' => 'Research Atlas',
        'status' => AgentListingStatus::Active,
        'search_document' => 'research atlas analysis',
    ]);

    AgentListing::factory()->create([
        'name' => 'Planner Agent',
        'status' => AgentListingStatus::Active,
        'search_document' => 'route planning',
    ]);

    $this->get(route('agents.index', ['q' => 'research']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('agents.data', 1)
            ->where('agents.data.0.name', 'Research Atlas')
        );
});

test('guests can view a publicly visible agent detail page', function () {
    $agent = AgentListing::factory()->create([
        'name' => 'My Agent',
        'status' => AgentListingStatus::Active,
    ]);

    $this->get(route('agents.show', $agent))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('agents/show')
            ->where('agent.name', 'My Agent')
            ->where('agent.id', $agent->public_id)
            ->has('agent.skills')
            ->has('agent.raw_card')
        );
});

test('stale and degraded agents are also publicly visible', function () {
    $stale = AgentListing::factory()->create(['status' => AgentListingStatus::Stale]);
    $degraded = AgentListing::factory()->create(['status' => AgentListingStatus::Degraded]);

    $this->get(route('agents.show', $stale))->assertOk();
    $this->get(route('agents.show', $degraded))->assertOk();
});

test('hidden agents return 404 on the detail page', function () {
    $disabled = AgentListing::factory()->create(['status' => AgentListingStatus::Disabled]);
    $error = AgentListing::factory()->create(['status' => AgentListingStatus::Error]);

    $this->get(route('agents.show', $disabled))->assertNotFound();
    $this->get(route('agents.show', $error))->assertNotFound();
});

test('the agent detail page includes all required data fields', function () {
    $agent = AgentListing::factory()->create([
        'status' => AgentListingStatus::Active,
        'skills_json' => [
            [
                'id' => 'search',
                'name' => 'Web Search',
                'description' => 'Searches the web.',
                'tags' => ['search'],
                'examples' => ['Find recent news about AI'],
                'inputModes' => ['text/plain'],
                'outputModes' => ['application/json'],
            ],
        ],
    ]);

    $this->get(route('agents.show', $agent))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('agent.skills.0.id', 'search')
            ->where('agent.skills.0.name', 'Web Search')
            ->has('agent.card_url')
            ->has('agent.source_url')
            ->has('agent.validation_warnings')
        );
});
