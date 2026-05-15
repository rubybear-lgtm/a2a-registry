# A2A Registry

A public registry for [Agent-to-Agent (A2A) protocol](https://google.github.io/A2A) agents. The app lets you ingest public A2A agent cards, normalize them into searchable registry records, browse them on the web, query them over a versioned JSON API, and generate SDKs from a checked-in OpenAPI contract.

## Why this exists

At its core, the A2A Registry and its SDKs solve the **discovery and integration problem for the agentic web**. 

While the Agent-to-Agent (A2A) protocol defines *how* AI agents talk to each other, they still need a way to find each other.

### 1. Agent Discovery
If you build an AI agent that can book flights, how does a personal assistant agent know your agent exists? How does it know what capabilities your agent has, what API endpoints it exposes, or what authentication it requires? 

Without a registry, agent integration requires manual hardcoding. Developers have to read documentation, manually configure endpoints, and hardcode API keys for every single agent-to-agent connection.

### 2. The Registry Solution
The registry acts as the "DNS" or "npm" for AI agents. It provides:
- **Standardized Ingestion:** It fetches public A2A "agent cards" (JSON manifests defining an agent's capabilities, interfaces, and security requirements) and validates them against the A2A 1.0 spec.
- **Normalization & Search:** It indexes these cards so they can be searched by capability, provider, or name.
- **Human Evaluation:** A precise, authoritative web interface where developers and ops teams can browse, compare, and evaluate agents before trusting them.
- **Health Monitoring:** It continuously re-validates agent cards and degrades listings if the agent's endpoints go down or their card becomes invalid.

### 3. Programmatic Integration via SDKs
The npm and Python SDKs solve the "last mile" problem for developers building agentic systems. 

Instead of writing raw HTTP requests to search the registry and parse complex JSON schemas, a developer building a Python or TypeScript agent can use the SDK to dynamically discover capabilities at runtime:

```python
# A personal assistant agent needs to find a calendar agent
calendar_agents = registry.list_agents(q="calendar", status="active")

# It fetches the exact connection details (interfaces, auth requirements)
card = registry.get_agent_card(calendar_agents.data[0].id)

# Now it knows exactly how to initiate an A2A handshake with that agent
```

This transforms the A2A protocol from a theoretical standard into a practical, searchable ecosystem where agents can dynamically discover, evaluate, and connect with one another.

## Stack

- Backend: Laravel 13, PHP 8.3+
- Frontend: React 19, Inertia.js v3, Tailwind CSS v4
- Database: SQLite by default, any Laravel-supported database
- Testing: Pest v4
- SDK contract: OpenAPI 3.0.3

## Features

### Public registry experience

- Landing page at `/` with registry overview, total visible listing count, and recently fetched agents
- Public listing page at `/agents` with search across agent name, description, provider, and indexed agent metadata
- Public detail page at `/agents/{agentListing}` keyed by the listing `public_id`
- Detail pages expose normalized registry metadata including:
  - provider information
  - capabilities
  - supported interfaces
  - security schemes and security requirements
  - validation warnings
  - raw agent card JSON
- Publicly visible listing states are `active`, `stale`, and `degraded`

### Read-only API

All read endpoints are public and versioned under `/api/v1`.

- `GET /api/v1/agents`
  - paginated list endpoint
  - supports `q` search
  - supports `status` filtering for `active`, `stale`, or `degraded`
- `GET /api/v1/agents/{agentListing}`
  - returns the normalized registry representation
- `GET /api/v1/agents/{agentListing}/card`
  - returns the raw standardized A2A 1.0 agent card envelope

The API uses stable public IDs, not database primary keys.

### Ingestion and refresh

- `POST /manage/agent-listings`
  - ingests a new agent card from a remote `source_url`
  - requires `Authorization: Bearer $AGENT_REGISTRY_MANAGEMENT_TOKEN` or `X-Agent-Registry-Token`
  - validates the fetched JSON against the expected A2A 1.0 card structure
  - normalizes the card into registry fields for search and discovery
- Scheduled refresh command:
  - `php artisan agent-listings:refresh`
  - the scheduler runs it every minute with `--limit=25`
  - due listings are selected from `refresh_due_at`
  - dispatches one queued refresh job per due listing
  - `--all` is available to force-refresh all non-disabled listings
  - refreshed listings also run queued link validation against URLs discovered in the A2A card

### Agent-card processing

- Remote fetches use Laravel's HTTP client with explicit timeouts
- Conditional requests use `ETag` and `Last-Modified`
- `304 Not Modified` responses update fetch timing without rewriting the listing
- Fetched cards must be JSON objects
- Validation enforces the expected A2A 1.0 shape for:
  - supported interfaces
  - provider
  - capabilities
  - input and output modes
  - skills
  - security schemes
  - security requirements
- Legacy `security` fields are mapped to `securityRequirements` with a warning during ingestion
- Failed revalidations degrade the listing instead of silently discarding the fetch result

### Registry data model

- Listings use a public ULID `public_id` for routing and API access
- Each successful or failed ingest creates a revision record
- Listings persist normalized fields for search, capabilities, interfaces, auth support, and raw card payloads
- Search documents are built from agent metadata, provider data, skills, tags, and examples

### SDK and contract generation

- Checked-in OpenAPI contract: [sdk/openapi/a2a-registry.v1.json](sdk/openapi/a2a-registry.v1.json)
- Generated npm client package: `@a2a-registry/sdk`
- Generated Python package: `a2a-registry-sdk`
- Generation commands:
  - `npm run sdk:generate`
  - `npm run sdk:generate:npm`
  - `npm run sdk:generate:python`

The current SDK scope is read-only and covers the public `/api/v1/agents` endpoints.

### Management access

- Built-in user accounts are disabled
- Registry ingestion is controlled by a management token
- Set `AGENT_REGISTRY_MANAGEMENT_TOKEN` to enable admin-only agent submission without creating user accounts

## Local setup

### Option 1: bootstrap with Composer

```bash
git clone git@github.com:rubybear-lgtm/a2a-registry.git
cd a2a-registry
composer run setup
```

### Option 2: manual setup

```bash
git clone git@github.com:rubybear-lgtm/a2a-registry.git
cd a2a-registry

composer install
npm install

cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

## Running locally

```bash
composer run dev
```

This starts the Laravel app server, queue listener, log tailing, and Vite dev server concurrently.

## Testing and verification

Run the full PHP test suite:

```bash
php artisan test --compact
```

Useful repo checks:

```bash
vendor/bin/pint --dirty --format agent
npm run types:check
npm run lint:check
npm run format:check
```

## API summary

### Web routes

- `GET /`
- `GET /agents`
- `GET /agents/{agentListing}`

### Management routes

- `POST /manage/agent-listings`

### API routes

- `GET /api/v1/agents`
- `GET /api/v1/agents/{agentListing}`
- `GET /api/v1/agents/{agentListing}/card`

## Notes

- `source_url` ingestion requires HTTPS
- management writes require `AGENT_REGISTRY_MANAGEMENT_TOKEN`
- `supportedInterfaces[*].url` must use HTTPS unless targeting `localhost` or `127.0.0.1`
- Refresh cadence defaults to `AGENT_REGISTRY_REFRESH_INTERVAL_MINUTES=1440`
- SDK generation currently requires network access the first time generator tooling is installed
