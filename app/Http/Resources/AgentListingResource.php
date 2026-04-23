<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->public_id,
            'name' => $this->name,
            'description' => $this->description,
            'provider_name' => $this->provider_name,
            'agent_version' => $this->agent_version,
            'documentation_url' => $this->documentation_url,
            'icon_url' => $this->icon_url,
            'preferred_interface_url' => $this->preferred_interface_url,
            'preferred_protocol_binding' => $this->preferred_protocol_binding,
            'preferred_protocol_version' => $this->preferred_protocol_version,
            'status' => $this->status->value,
            'has_auth' => $this->has_auth,
            'supports_streaming' => $this->supports_streaming,
            'supports_push_notifications' => $this->supports_push_notifications,
            'supports_extended_agent_card' => $this->supports_extended_agent_card,
            'default_input_modes' => $this->default_input_modes_json,
            'default_output_modes' => $this->default_output_modes_json,
            'supported_interfaces' => $this->supported_interfaces_json,
            'security_requirements' => $this->security_requirements_json,
            'skills' => $this->skills_json,
            'source_url' => $this->source_url,
            'card_url' => route('api.v1.agent-listings.card', $this->resource),
            'fetched_at' => $this->fetched_at?->toAtomString(),
            'validated_at' => $this->validated_at?->toAtomString(),
        ];
    }
}
