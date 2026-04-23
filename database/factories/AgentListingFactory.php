<?php

namespace Database\Factories;

use App\Enums\AgentListingStatus;
use App\Models\AgentListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgentListing>
 */
class AgentListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $domain = fake()->unique()->domainName();
        $sourceUrl = "https://{$domain}/.well-known/agent-card.json";
        $interfaceUrl = "https://{$domain}/a2a";
        $providerName = fake()->company();
        $name = $providerName.' Agent';
        $agentVersion = fake()->randomElement(['1.0.0', '1.1.0', '1.2.3']);
        $skills = [
            [
                'id' => fake()->slug(2),
                'name' => fake()->words(2, true),
                'description' => fake()->sentence(),
                'tags' => fake()->words(3),
                'examples' => [
                    fake()->sentence(),
                    fake()->sentence(),
                ],
                'inputModes' => ['text/plain'],
                'outputModes' => ['application/json'],
            ],
        ];

        $capabilities = [
            'streaming' => fake()->boolean(80),
            'pushNotifications' => fake()->boolean(30),
            'extendedAgentCard' => fake()->boolean(20),
        ];

        $provider = [
            'organization' => $providerName,
            'url' => "https://{$domain}",
        ];
        $supportedInterfaces = [
            [
                'url' => $interfaceUrl,
                'protocolBinding' => 'JSONRPC',
                'protocolVersion' => '1.0',
            ],
        ];
        $securitySchemes = fake()->boolean(50)
            ? [
                'bearerAuth' => [
                    'httpAuthSecurityScheme' => [
                        'scheme' => 'Bearer',
                    ],
                ],
            ]
            : null;
        $securityRequirements = $securitySchemes ? [['bearerAuth' => []]] : null;
        $rawCard = [
            'name' => $name,
            'description' => fake()->sentence(12),
            'supportedInterfaces' => $supportedInterfaces,
            'provider' => $provider,
            'version' => $agentVersion,
            'documentationUrl' => "https://docs.{$domain}/agent",
            'capabilities' => $capabilities,
            'defaultInputModes' => ['text/plain'],
            'defaultOutputModes' => ['application/json'],
            'skills' => $skills,
        ];

        if ($securitySchemes) {
            $rawCard['securitySchemes'] = $securitySchemes;
            $rawCard['securityRequirements'] = $securityRequirements;
        }

        return [
            'source_url' => $sourceUrl,
            'name' => $name,
            'description' => $rawCard['description'],
            'agent_version' => $agentVersion,
            'documentation_url' => $rawCard['documentationUrl'],
            'icon_url' => "https://{$domain}/icon.png",
            'provider_name' => $providerName,
            'preferred_interface_url' => $interfaceUrl,
            'preferred_protocol_binding' => 'JSONRPC',
            'preferred_protocol_version' => '1.0',
            'search_document' => implode(' ', [
                $name,
                $rawCard['description'],
                $providerName,
                $skills[0]['name'],
                $skills[0]['description'],
            ]),
            'has_auth' => $securitySchemes !== null,
            'supports_streaming' => $capabilities['streaming'],
            'supports_push_notifications' => $capabilities['pushNotifications'],
            'supports_extended_agent_card' => $capabilities['extendedAgentCard'],
            'status' => AgentListingStatus::Active,
            'raw_card_json' => $rawCard,
            'provider_json' => $provider,
            'capabilities_json' => $capabilities,
            'supported_interfaces_json' => $supportedInterfaces,
            'security_schemes_json' => $securitySchemes,
            'security_requirements_json' => $securityRequirements,
            'default_input_modes_json' => ['text/plain'],
            'default_output_modes_json' => ['application/json'],
            'skills_json' => $skills,
            'signatures_json' => null,
            'validation_warnings_json' => [],
            'etag' => fake()->sha1(),
            'last_modified' => 'Wed, 22 Apr 2026 12:00:00 GMT',
            'content_type' => 'application/json',
            'content_hash' => hash('sha256', json_encode($rawCard, JSON_THROW_ON_ERROR)),
            'last_http_status' => 200,
            'last_error' => null,
            'fetched_at' => now(),
            'validated_at' => now(),
            'refresh_due_at' => now()->addDay(),
        ];
    }
}
