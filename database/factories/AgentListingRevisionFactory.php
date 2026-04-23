<?php

namespace Database\Factories;

use App\Models\AgentListing;
use App\Models\AgentListingRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgentListingRevision>
 */
class AgentListingRevisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $domain = fake()->unique()->domainName();
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

        $supportedInterfaces = [
            [
                'url' => "https://{$domain}/a2a",
                'protocolBinding' => 'JSONRPC',
                'protocolVersion' => '1.0',
            ],
        ];
        $provider = [
            'organization' => $providerName,
            'url' => "https://{$domain}",
        ];
        $securitySchemes = [
            'bearerAuth' => [
                'httpAuthSecurityScheme' => [
                    'scheme' => 'Bearer',
                ],
            ],
        ];
        $rawCard = [
            'name' => $name,
            'description' => fake()->sentence(12),
            'supportedInterfaces' => $supportedInterfaces,
            'provider' => $provider,
            'version' => $agentVersion,
            'capabilities' => $capabilities,
            'defaultInputModes' => ['text/plain'],
            'defaultOutputModes' => ['application/json'],
            'skills' => $skills,
            'securitySchemes' => $securitySchemes,
            'securityRequirements' => [['bearerAuth' => []]],
        ];

        return [
            'agent_listing_id' => AgentListing::factory(),
            'revision_number' => 1,
            'source_url' => "https://{$domain}/.well-known/agent-card.json",
            'response_status' => 200,
            'content_type' => 'application/json',
            'etag' => fake()->sha1(),
            'last_modified' => 'Wed, 22 Apr 2026 12:00:00 GMT',
            'raw_body' => json_encode($rawCard, JSON_THROW_ON_ERROR),
            'raw_card_json' => $rawCard,
            'normalized_card_json' => $rawCard,
            'validation_errors_json' => null,
            'validation_warnings_json' => [],
            'content_hash' => hash('sha256', json_encode($rawCard, JSON_THROW_ON_ERROR)),
            'is_valid' => true,
            'fetched_at' => now(),
        ];
    }
}
