import type { AgentListingStatus } from './models/AgentListingStatus';
import type { PaginatedAgentListingResponse } from './models/PaginatedAgentListingResponse';
import type { AgentListingResponse } from './models/AgentListingResponse';
import type { AgentCardResponse } from './models/AgentCardResponse';
export interface A2ARegistryClientOptions {
    baseUrl: string;
    headers?: Record<string, string>;
}
export declare function createA2ARegistryClient(options: A2ARegistryClientOptions): {
    agents: {
        list(params?: {
            q?: string;
            status?: AgentListingStatus;
            page?: number;
        }): Promise<PaginatedAgentListingResponse>;
        get(agentListing: string): Promise<AgentListingResponse>;
        card(agentListing: string): Promise<AgentCardResponse>;
    };
};
export type A2ARegistryClient = ReturnType<typeof createA2ARegistryClient>;
