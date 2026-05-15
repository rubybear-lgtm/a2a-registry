/* generated using openapi-typescript-codegen -- do not edit */
/* istanbul ignore file */
/* tslint:disable */
/* eslint-disable */
import type { AgentCardResponse } from '../models/AgentCardResponse';
import type { AgentListingResponse } from '../models/AgentListingResponse';
import type { AgentListingStatus } from '../models/AgentListingStatus';
import type { PaginatedAgentListingResponse } from '../models/PaginatedAgentListingResponse';
import type { CancelablePromise } from '../core/CancelablePromise';
import { OpenAPI } from '../core/OpenAPI';
import { request as __request } from '../core/request';
export class AgentsService {
    /**
     * List visible agent listings
     * Returns a paginated collection of publicly visible agent listings.
     * @returns PaginatedAgentListingResponse Paginated visible agent listings.
     * @throws ApiError
     */
    public static listAgents({
        q,
        status,
        page,
    }: {
        /**
         * Case-insensitive search across listing name, description, provider, and indexed agent metadata.
         */
        q?: string,
        /**
         * Filter results to a single visible listing status.
         */
        status?: AgentListingStatus,
        /**
         * Laravel paginator page number.
         */
        page?: number,
    }): CancelablePromise<PaginatedAgentListingResponse> {
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
    public static getAgent({
        agentListing,
    }: {
        /**
         * The public registry identifier for the agent listing.
         */
        agentListing: string,
    }): CancelablePromise<AgentListingResponse> {
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
    public static getAgentCard({
        agentListing,
    }: {
        /**
         * The public registry identifier for the agent listing.
         */
        agentListing: string,
    }): CancelablePromise<AgentCardResponse> {
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
