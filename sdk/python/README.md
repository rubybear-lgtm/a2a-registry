# a2a-registry-sdk

Python client for the [A2A Registry API](https://github.com/rubybear-lgtm/a2a-registry). Discover and browse registered A2A protocol agents.

## Install

```bash
pip install a2a-registry-sdk
```

## Quick start

```python
from a2a_registry_sdk import A2ARegistryClient

client = A2ARegistryClient("https://your-registry.example.com")

# List active agents matching a search query
result = client.list_agents(q="calendar", status="active")
for agent in result.data:
    print(agent.name)

# Fetch a specific agent listing
agent = client.get_agent("01JQZ...")

# Fetch the raw A2A 1.0 agent card
card = client.get_agent_card("01JQZ...")
```

## Configuration

`A2ARegistryClient(base_url: str, *, headers: dict[str, str] | None = None, timeout: float | None = None)`

- `base_url` — Your registry host URL (required)
- `headers` — Additional HTTP headers (optional)
- `timeout` — Request timeout in seconds (optional)

## API

### `A2ARegistryClient`

#### `list_agents(*, q=None, status=None, page=None)`

List visible agent listings. Returns `PaginatedAgentListingResponse`.

- `q` — Case-insensitive search across name, description, provider
- `status` — Filter by listing status (`"active"`, `"stale"`, `"degraded"`)
- `page` — Paginator page number

#### `get_agent(agent_listing)`

Fetch a single visible agent listing. Returns `AgentListingResponse`.

#### `get_agent_card(agent_listing)`

Fetch the raw A2A 1.0 agent card. Returns `AgentCardResponse`.

## License

MIT
