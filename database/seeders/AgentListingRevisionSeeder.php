<?php

namespace Database\Seeders;

use App\Models\AgentListing;
use App\Models\AgentListingRevision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class AgentListingRevisionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listing = AgentListing::factory()->create();

        AgentListingRevision::factory()->for($listing)->count(2)->state(new Sequence(
            ['revision_number' => 1],
            ['revision_number' => 2],
        ))->create();
    }
}
