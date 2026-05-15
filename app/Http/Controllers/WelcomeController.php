<?php

namespace App\Http\Controllers;

use App\Enums\AgentListingStatus;
use App\Models\AgentListing;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __invoke(): Response
    {
        $agents = AgentListing::query()
            ->whereIn('status', [AgentListingStatus::Active, AgentListingStatus::Stale, AgentListingStatus::Degraded])
            ->latest('fetched_at')
            ->limit(5)
            ->get()
            ->map(fn (AgentListing $agent): array => [
                'id' => $agent->public_id,
                'name' => $agent->name,
                'provider_name' => $agent->provider_name,
                'agent_version' => $agent->agent_version,
                'preferred_protocol_binding' => $agent->preferred_protocol_binding,
                'preferred_protocol_version' => $agent->preferred_protocol_version,
                'supports_streaming' => $agent->supports_streaming,
                'has_auth' => $agent->has_auth,
                'supports_push_notifications' => $agent->supports_push_notifications,
                'skills' => collect($agent->skills_json ?? [])->pluck('name')->all(),
            ]);

        $total = AgentListing::query()
            ->whereIn('status', [AgentListingStatus::Active, AgentListingStatus::Stale, AgentListingStatus::Degraded])
            ->count();

        return Inertia::render('welcome', [
            'agents' => $agents,
            'total' => $total,
        ]);
    }
}
