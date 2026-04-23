# A2A Registry

A public registry for [Agent-to-Agent (A2A) protocol](https://google.github.io/A2A) agents. Browse registered agents, inspect their capabilities and skills, and fetch machine-readable agent cards.

## Stack

- **Backend:** Laravel 13, PHP 8.4
- **Frontend:** React 19, Inertia.js v3, Tailwind CSS v4
- **Database:** SQLite (default), any Laravel-supported DB
- **Testing:** Pest v4

## Local Setup

```bash
git clone git@github.com:rubybear-lgtm/a2a-registry.git
cd a2a-registry

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate
```

Start the dev server:

```bash
composer run dev
```

This starts Laravel, Vite, and the queue worker concurrently.

## Pages

| Route | Description |
|---|---|
| `/` | Landing page with protocol overview |
| `/agents` | Full agent listing with search |
| `/agents/{id}` | Agent detail — skills, interfaces, security, raw card JSON |

## API

All endpoints are public and return JSON.

```
GET /api/v1/agents
GET /api/v1/agents/{id}
GET /api/v1/agents/{id}/card
```

The `/card` endpoint returns the raw A2A agent card in its original format as fetched from the agent's endpoint.

## Adding Agents

Agents are added manually via the management API:

```bash
POST /manage/agent-listings        # register a new agent by card URL
POST /manage/agent-listings/{id}/refresh  # re-fetch and update an existing agent
```

## Testing

```bash
php artisan test
```
