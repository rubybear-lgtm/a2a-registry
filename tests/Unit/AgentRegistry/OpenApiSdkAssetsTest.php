<?php

function sdkProjectRoot(): string
{
    return dirname(__DIR__, 3);
}

test('the openapi spec defines the public read-only sdk contract', function () {
    $specPath = sdkProjectRoot().'/sdk/openapi/a2a-registry.v1.json';

    expect($specPath)->toBeFile();

    $spec = json_decode(file_get_contents($specPath), true, 512, JSON_THROW_ON_ERROR);

    expect($spec['openapi'])->toBe('3.0.3');
    expect($spec['paths'])->toHaveKeys([
        '/api/v1/agents',
        '/api/v1/agents/{agentListing}',
        '/api/v1/agents/{agentListing}/card',
    ]);
    expect($spec['paths']['/api/v1/agents']['get']['operationId'])->toBe('listAgents');
    expect($spec['paths']['/api/v1/agents/{agentListing}']['get']['operationId'])->toBe('getAgent');
    expect($spec['paths']['/api/v1/agents/{agentListing}/card']['get']['operationId'])->toBe('getAgentCard');
    expect($spec['components']['schemas'])->toHaveKeys([
        'AgentListing',
        'AgentCardV1',
        'PaginatedAgentListingResponse',
    ]);
});

test('the generated npm and python sdk package manifests exist', function () {
    $npmManifestPath = sdkProjectRoot().'/sdk/npm/package.json';
    $pythonManifestPath = sdkProjectRoot().'/sdk/python/pyproject.toml';

    expect($npmManifestPath)->toBeFile();
    expect(sdkProjectRoot().'/sdk/npm/dist/index.js')->toBeFile();
    expect($pythonManifestPath)->toBeFile();
    expect(sdkProjectRoot().'/sdk/python/a2a_registry_sdk/client.py')->toBeFile();

    $npmManifest = json_decode(file_get_contents($npmManifestPath), true, 512, JSON_THROW_ON_ERROR);
    $pythonManifest = file_get_contents($pythonManifestPath);

    expect($npmManifest['name'])->toBe('@a2a-registry/sdk');
    expect($pythonManifest)->toContain('name = "agent2agent-registry"');
    expect($pythonManifest)->toContain('include = ["a2a_registry_sdk/py.typed"]');
    expect($pythonManifest)->not->toContain('README.md');
    expect($pythonManifest)->not->toContain('CHANGELOG.md');
});
