<?php

use App\Actions\AgentRegistry\ValidateAgentCard;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

test('it validates a strict a2a 1 0 agent card', function () {
    $validator = new ValidateAgentCard;

    $result = $validator([
        'name' => 'Route Planner',
        'description' => 'Plans routes for travelers.',
        'supportedInterfaces' => [
            [
                'url' => 'https://agents.example.com/a2a',
                'protocolBinding' => 'JSONRPC',
                'protocolVersion' => '1.0',
            ],
        ],
        'provider' => [
            'organization' => 'Example Agents',
            'url' => 'https://example.com',
        ],
        'version' => '1.2.3',
        'capabilities' => [
            'streaming' => true,
            'pushNotifications' => false,
            'extendedAgentCard' => false,
        ],
        'defaultInputModes' => ['text/plain'],
        'defaultOutputModes' => ['application/json'],
        'skills' => [
            [
                'id' => 'route-planning',
                'name' => 'Route Planning',
                'description' => 'Creates travel itineraries and routes.',
                'tags' => ['travel', 'maps'],
                'examples' => ['Plan a route from Denver to Moab.'],
            ],
        ],
        'securitySchemes' => [
            'bearerAuth' => [
                'httpAuthSecurityScheme' => [
                    'scheme' => 'Bearer',
                ],
            ],
        ],
        'securityRequirements' => [
            ['bearerAuth' => []],
        ],
    ]);

    expect($result['warnings'])->toBe([]);
    expect($result['card']['version'])->toBe('1.2.3');
});

test('it maps the legacy security field to security requirements with a warning', function () {
    $validator = new ValidateAgentCard;

    $result = $validator([
        'name' => 'Legacy Security Agent',
        'description' => 'Uses the old security field.',
        'supportedInterfaces' => [
            [
                'url' => 'https://agents.example.com/a2a',
                'protocolBinding' => 'JSONRPC',
                'protocolVersion' => '1.0',
            ],
        ],
        'version' => '1.0.0',
        'capabilities' => [],
        'defaultInputModes' => ['text/plain'],
        'defaultOutputModes' => ['application/json'],
        'skills' => [
            [
                'id' => 'echo',
                'name' => 'Echo',
                'description' => 'Echoes the input.',
                'tags' => ['utility'],
            ],
        ],
        'security' => [
            ['bearerAuth' => []],
        ],
    ]);

    expect($result['card']['securityRequirements'])->toBe([
        ['bearerAuth' => []],
    ]);
    expect($result['warnings']['securityRequirements'])->not->toBeEmpty();
});

test('it rejects malformed agent cards', function () {
    $validator = new ValidateAgentCard;

    expect(fn () => $validator([
        'name' => 'Broken Agent',
        'description' => 'Missing required fields.',
        'capabilities' => [],
        'defaultInputModes' => ['text/plain'],
        'defaultOutputModes' => ['application/json'],
        'skills' => [],
    ]))->toThrow(ValidationException::class);
});

test('it rejects malformed security requirement maps', function () {
    $validator = new ValidateAgentCard;

    expect(fn () => $validator([
        'name' => 'Broken Security Agent',
        'description' => 'Has malformed security requirements.',
        'supportedInterfaces' => [
            [
                'url' => 'https://agents.example.com/a2a',
                'protocolBinding' => 'JSONRPC',
                'protocolVersion' => '1.0',
            ],
        ],
        'version' => '1.0.0',
        'capabilities' => [],
        'defaultInputModes' => ['text/plain'],
        'defaultOutputModes' => ['application/json'],
        'skills' => [
            [
                'id' => 'echo',
                'name' => 'Echo',
                'description' => 'Echoes the input.',
                'tags' => ['utility'],
            ],
        ],
        'securityRequirements' => [
            ['bearerAuth' => 'token'],
        ],
    ]))->toThrow(ValidationException::class);
});
