import { OpenAPI } from '../core/OpenAPI';
import { request as __request } from '../core/request';
export class AgentsService {
    /**
     * List visible agent listings
     * Returns a paginated collection of publicly visible agent listings.
     * @returns PaginatedAgentListingResponse Paginated visible agent listings.
     * @throws ApiError
     */
    static listAgents({ q, status, page, }) {
        return __request(OpenAPI, {
            method: 'GET',
            url: '/api/v1/agents',
            query: {
                'q': q,
                'status': status,
                'page': page,
            },
            errors: {
                422: `Validation error for unsupported query parameters.`,
            },
        });
    }
    /**
     * Fetch a single visible agent listing
     * @returns AgentListingResponse The normalized agent listing resource.
     * @throws ApiError
     */
    static getAgent({ agentListing, }) {
        return __request(OpenAPI, {
            method: 'GET',
            url: '/api/v1/agents/{agentListing}',
            path: {
                'agentListing': agentListing,
            },
            errors: {
                404: `The agent listing is not publicly visible or does not exist.`,
            },
        });
    }
    /**
     * Fetch the raw A2A agent card for a listing
     * @returns AgentCardResponse The standardized A2A 1.0 agent card for the listing.
     * @throws ApiError
     */
    static getAgentCard({ agentListing, }) {
        return __request(OpenAPI, {
            method: 'GET',
            url: '/api/v1/agents/{agentListing}/card',
            path: {
                'agentListing': agentListing,
            },
            errors: {
                404: `The agent listing is not publicly visible or does not exist.`,
            },
        });
    }
}
