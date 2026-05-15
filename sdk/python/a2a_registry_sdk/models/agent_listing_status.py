from enum import Enum

class AgentListingStatus(str, Enum):
    ACTIVE = "active"
    DEGRADED = "degraded"
    STALE = "stale"

    def __str__(self) -> str:
        return str(self.value)
