<?php

namespace App\Http\Controllers\AgentRegistry;

use App\Enums\AgentListingStatus;
use App\Http\Controllers\Controller;
use App\Models\AgentListing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AgentListingWebController extends Controller
{
    public function index(Request $request): Response
    {
        $request->validate([
            'q' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);

        $query = AgentListing::query()
            ->whereIn('status', [
                AgentListingStatus::Active,
                AgentListingStatus::Stale,
                AgentListingStatus::Degraded,
            ])
            ->orderBy('name');

        if ($search = trim((string) $request->string('q'))) {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('provider_name', 'like', "%{$search}%")
                    ->orWhere('search_document', 'like', "%{$search}%");
            });
        }

        return Inertia::render('agents/index', [
            'agents' => $query
                ->paginate(50)
                ->withQueryString()
                ->through(fn (AgentListing $agent): array => [
                    'id' => $agent->public_id,
                    'name' => $agent->name,
                    'description' => $agent->description,
                    'provider_name' => $agent->provider_name,
                    'agent_version' => $agent->agent_version,
                    'preferred_protocol_binding' => $agent->preferred_protocol_binding,
                    'preferred_protocol_version' => $agent->preferred_protocol_version,
                    'supports_streaming' => $agent->supports_streaming,
                    'supports_push_notifications' => $agent->supports_push_notifications,
                    'supports_extended_agent_card' => $agent->supports_extended_agent_card,
                    'has_auth' => $agent->has_auth,
                    'status' => $agent->status->value,
                    'fetched_at' => $agent->fetched_at?->toAtomString(),
                    'skills_count' => is_array($agent->skills_json) ? count($agent->skills_json) : 0,
                ]),
            'filters' => ['q' => $request->string('q')->toString() ?: null],
        ]);
    }

    public function show(AgentListing $agentListing): Response
    {
        abort_unless($this->isPubliclyVisible($agentListing), 404);

        return Inertia::render('agents/show', [
            'agent' => [
                'id' => $agentListing->public_id,
                'name' => $agentListing->name,
                'description' => $agentListing->description,
                'provider_name' => $agentListing->provider_name,
                'agent_version' => $agentListing->agent_version,
                'preferred_interface_url' => $agentListing->preferred_interface_url,
                'preferred_protocol_binding' => $agentListing->preferred_protocol_binding,
                'preferred_protocol_version' => $agentListing->preferred_protocol_version,
                'documentation_url' => $agentListing->documentation_url,
                'icon_url' => $agentListing->icon_url,
                'source_url' => $agentListing->source_url,
                'card_url' => route('api.v1.agent-listings.card', $agentListing),
                'has_auth' => $agentListing->has_auth,
                'supports_streaming' => $agentListing->supports_streaming,
                'supports_push_notifications' => $agentListing->supports_push_notifications,
                'supports_extended_agent_card' => $agentListing->supports_extended_agent_card,
                'status' => $agentListing->status->value,
                'last_http_status' => $agentListing->last_http_status,
                'last_error' => $agentListing->last_error,
                'fetched_at' => $agentListing->fetched_at?->toAtomString(),
                'validated_at' => $agentListing->validated_at?->toAtomString(),
                'provider' => $agentListing->provider_json,
                'capabilities' => $agentListing->capabilities_json,
                'skills' => $agentListing->skills_json,
                'supported_interfaces' => $agentListing->supported_interfaces_json,
                'security_schemes' => $agentListing->security_schemes_json,
                'security_requirements' => $agentListing->security_requirements_json,
                'validation_warnings' => collect($agentListing->validation_warnings_json ?? [])
                    ->flatMap(
                        fn (mixed $messages, string $key): array => collect(is_array($messages) ? $messages : [$messages])
                            ->map(fn (mixed $message): string => sprintf('%s: %s', Str::headline($key), (string) $message))
                            ->all(),
                    )
                    ->values()
                    ->all(),
                'raw_card' => $agentListing->raw_card_json,
            ],
        ]);
    }

    private function isPubliclyVisible(AgentListing $agentListing): bool
    {
        return in_array($agentListing->status, [
            AgentListingStatus::Active,
            AgentListingStatus::Stale,
            AgentListingStatus::Degraded,
        ], true);
    }
}
