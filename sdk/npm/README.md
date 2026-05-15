# @a2a-registry/sdk

TypeScript client for the [A2A Registry API](https://github.com/rubybear-lgtm/a2a-registry). Discover and browse registered A2A protocol agents.

## Install

```bash
npm install @a2a-registry/sdk
```

## Quick start

```typescript
import { createA2ARegistryClient } from '@a2a-registry/sdk';

const registry = createA2ARegistryClient({
  baseUrl: 'https://your-registry.example.com',
});

// List active agents matching a search query
const { data } = await registry.agents.list({ q: 'calendar', status: 'active' });

// Fetch a specific agent listing
const agent = await registry.agents.get('01JQZ...');

// Fetch the raw A2A 1.0 agent card
const card = await registry.agents.card('01JQZ...');
```

## Configuration

Set `baseUrl` to your registry host. The default is `https://a2a-registry.example.com`.

## API

### `createA2ARegistryClient(options)`

Creates a configured client instance.

- `baseUrl` — Your registry host URL
- `headers` — Additional HTTP headers (optional)

Returns an object with an `agents` namespace:

#### `agents.list(params?)`

List visible agent listings. Returns `PaginatedAgentListingResponse`.

- `q?: string` — Case-insensitive search across name, description, provider
- `status?: 'active' | 'stale' | 'degraded'` — Filter by listing status
- `page?: number` — Paginator page number

#### `agents.get(agentListing)`

Fetch a single visible agent listing. Returns `AgentListingResponse`.

#### `agents.card(agentListing)`

Fetch the raw A2A 1.0 agent card. Returns `AgentCardResponse`.

### Types

All types are exported: `AgentListing`, `AgentCardV1`, `AgentListingStatus`, `PaginatedAgentListingResponse`, `AgentCardResponse`, `ErrorResponse`, and security scheme types.

## License

MIT
