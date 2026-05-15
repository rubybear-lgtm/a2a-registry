from __future__ import annotations

from typing import Any

import httpx

from .api.agents import get_agent, get_agent_card, list_agents
from .client import Client
from .models.agent_listing_status import AgentListingStatus
from .types import UNSET


class A2ARegistryClient:
    """Ergonomic client for the A2A Registry API."""

    def __init__(
        self,
        base_url: str,
        *,
        headers: dict[str, str] | None = None,
        timeout: float | None = None,
    ) -> None:
        httpx_timeout = httpx.Timeout(timeout) if timeout is not None else None
        self._client = Client(
            base_url=base_url,
            headers=headers or {},
            timeout=httpx_timeout,
        )

    def list_agents(
        self,
        *,
        q: str | None = None,
        status: str | AgentListingStatus | None = None,
        page: int | None = None,
    ) -> Any:
        """List visible agent listings."""
        return list_agents.sync(
            client=self._client,
            q=q if q is not None else UNSET,
            status=AgentListingStatus(status) if status is not None else UNSET,
            page=page if page is not None else UNSET,
        )

    def get_agent(self, agent_listing: str) -> Any:
        """Fetch a single visible agent listing."""
        return get_agent.sync(
            client=self._client,
            agent_listing=agent_listing,
        )

    def get_agent_card(self, agent_listing: str) -> Any:
        """Fetch the raw A2A 1.0 agent card."""
        return get_agent_card.sync(
            client=self._client,
            agent_listing=agent_listing,
        )
