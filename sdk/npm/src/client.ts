import type { AgentListingStatus } from './models/AgentListingStatus';
import type { PaginatedAgentListingResponse } from './models/PaginatedAgentListingResponse';
import type { AgentListingResponse } from './models/AgentListingResponse';
import type { AgentCardResponse } from './models/AgentCardResponse';
import { OpenAPI } from './core/OpenAPI';
import { AgentsService } from './services/AgentsService';

export interface A2ARegistryClientOptions {
    baseUrl: string;
    headers?: Record<string, string>;
}

export function createA2ARegistryClient(options: A2ARegistryClientOptions) {
    OpenAPI.BASE = options.baseUrl;
    if (options.headers) {
        OpenAPI.HEADERS = options.headers;
    }

    return {
        agents: {
            list(params?: {
                q?: string;
                status?: AgentListingStatus;
                page?: number;
            }): Promise<PaginatedAgentListingResponse> {
                return AgentsService.listAgents(params ?? {});
            },

            get(agentListing: string): Promise<AgentListingResponse> {
                return AgentsService.getAgent({ agentListing });
            },

            card(agentListing: string): Promise<AgentCardResponse> {
                return AgentsService.getAgentCard({ agentListing });
            },
        },
    };
}

export type A2ARegistryClient = ReturnType<typeof createA2ARegistryClient>;
