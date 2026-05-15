import type { AgentListing } from './AgentListing';
import type { PaginationLinks } from './PaginationLinks';
import type { PaginationMeta } from './PaginationMeta';
export type PaginatedAgentListingResponse = {
    data: Array<AgentListing>;
    links: PaginationLinks;
    meta: PaginationMeta;
};
