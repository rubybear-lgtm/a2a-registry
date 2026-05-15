import type { AgentCardResponse } from '../models/AgentCardResponse';
import type { AgentListingResponse } from '../models/AgentListingResponse';
import type { AgentListingStatus } from '../models/AgentListingStatus';
import type { PaginatedAgentListingResponse } from '../models/PaginatedAgentListingResponse';
import type { CancelablePromise } from '../core/CancelablePromise';
export declare class AgentsService {
    /**
     * List visible agent listings
     * Returns a paginated collection of publicly visible agent listings.
     * @returns PaginatedAgentListingResponse Paginated visible agent listings.
     * @throws ApiError
     */
    static listAgents({ q, status, page, }: {
        /**
         * Case-insensitive search across listing name, description, provider, and indexed agent metadata.
         */
        q?: string;
        /**
         * Filter results to a single visible listing status.
         */
        status?: AgentListingStatus;
        /**
         * Laravel paginator page number.
         */
        page?: number;
    }): CancelablePromise<PaginatedAgentListingResponse>;
    /**
     * Fetch a single visible agent listing
     * @returns AgentListingResponse The normalized agent listing resource.
     * @throws ApiError
     */
    static getAgent({ agentListing, }: {
        /**
         * The public registry identifier for the agent listing.
         */
        agentListing: string;
    }): CancelablePromise<AgentListingResponse>;
    /**
     * Fetch the raw A2A agent card for a listing
     * @returns AgentCardResponse The standardized A2A 1.0 agent card for the listing.
     * @throws ApiError
     */
    static getAgentCard({ agentListing, }: {
        /**
         * The public registry identifier for the agent listing.
         */
        agentListing: string;
    }): CancelablePromise<AgentCardResponse>;
}
