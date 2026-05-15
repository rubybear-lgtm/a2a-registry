import type { AgentCardV1 } from './AgentCardV1';
import type { AgentListingStatus } from './AgentListingStatus';
export type AgentCardEnvelope = {
    id: string;
    source_url: string;
    status: AgentListingStatus;
    card: AgentCardV1;
    validation_warnings: Record<string, Array<string>>;
    etag: string | null;
    last_modified: string | null;
    content_hash: string | null;
    fetched_at: string | null;
    validated_at: string | null;
};
