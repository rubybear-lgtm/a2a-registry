import { OpenAPI } from './core/OpenAPI';
import { AgentsService } from './services/AgentsService';
export function createA2ARegistryClient(options) {
    OpenAPI.BASE = options.baseUrl;
    if (options.headers) {
        OpenAPI.HEADERS = options.headers;
    }
    return {
        agents: {
            list(params) {
                return AgentsService.listAgents(params ?? {});
            },
            get(agentListing) {
                return AgentsService.getAgent({ agentListing });
            },
            card(agentListing) {
                return AgentsService.getAgentCard({ agentListing });
            },
        },
    };
}
