<?php

namespace Database\Seeders;

use App\Models\AgentListing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentListingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgentListing::factory()->count(3)->create();
    }
}
