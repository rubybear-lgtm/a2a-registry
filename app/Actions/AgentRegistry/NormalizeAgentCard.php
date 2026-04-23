<?php

namespace App\Actions\AgentRegistry;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class NormalizeAgentCard
{
    /**
     * @param  array<string, mixed>  $agentCard
     * @param  array<string, list<string>>  $warnings
     * @return array<string, mixed>
     */
    public function __invoke(array $agentCard, array $warnings = []): array
    {
        /** @var array<string, mixed> $preferredInterface */
        $preferredInterface = Arr::first($agentCard['supportedInterfaces']);
        $skills = collect($agentCard['skills']);
        $providerName = Arr::get($agentCard, 'provider.organization');

        return [
            'name' => $agentCard['name'],
            'description' => $agentCard['description'],
            'agent_version' => $agentCard['version'],
            'documentation_url' => Arr::get($agentCard, 'documentationUrl'),
            'icon_url' => Arr::get($agentCard, 'iconUrl'),
            'provider_name' => $providerName,
            'preferred_interface_url' => $preferredInterface['url'],
            'preferred_protocol_binding' => $preferredInterface['protocolBinding'],
            'preferred_protocol_version' => $preferredInterface['protocolVersion'],
            'search_document' => $this->buildSearchDocument($agentCard, $skills, $providerName),
            'has_auth' => ! empty($agentCard['securitySchemes']) || ! empty($agentCard['securityRequirements']),
            'supports_streaming' => (bool) Arr::get($agentCard, 'capabilities.streaming', false),
            'supports_push_notifications' => (bool) Arr::get($agentCard, 'capabilities.pushNotifications', false),
            'supports_extended_agent_card' => (bool) Arr::get($agentCard, 'capabilities.extendedAgentCard', false),
            'raw_card_json' => $agentCard,
            'provider_json' => Arr::get($agentCard, 'provider'),
            'capabilities_json' => $agentCard['capabilities'],
            'supported_interfaces_json' => $agentCard['supportedInterfaces'],
            'security_schemes_json' => Arr::get($agentCard, 'securitySchemes'),
            'security_requirements_json' => Arr::get($agentCard, 'securityRequirements'),
            'default_input_modes_json' => $agentCard['defaultInputModes'],
            'default_output_modes_json' => $agentCard['defaultOutputModes'],
            'skills_json' => $agentCard['skills'],
            'signatures_json' => Arr::get($agentCard, 'signatures'),
            'validation_warnings_json' => $warnings,
        ];
    }

    /**
     * @param  array<string, mixed>  $agentCard
     * @param  Collection<int, array<string, mixed>>  $skills
     */
    private function buildSearchDocument(array $agentCard, Collection $skills, ?string $providerName): string
    {
        return $skills
            ->flatMap(static function (array $skill): array {
                return array_merge(
                    [$skill['name'] ?? null, $skill['description'] ?? null],
                    $skill['tags'] ?? [],
                    $skill['examples'] ?? [],
                );
            })
            ->prepend($providerName)
            ->prepend($agentCard['description'])
            ->prepend($agentCard['name'])
            ->filter(static fn (mixed $value): bool => is_string($value) && $value !== '')
            ->implode(' ');
    }
}
