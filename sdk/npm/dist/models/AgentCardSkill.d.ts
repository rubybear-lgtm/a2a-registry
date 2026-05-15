import type { SecurityRequirement } from './SecurityRequirement';
export type AgentCardSkill = {
    id: string;
    name: string;
    description: string;
    tags: Array<string>;
    examples?: Array<string> | null;
    inputModes?: Array<string> | null;
    outputModes?: Array<string> | null;
    securityRequirements?: Array<SecurityRequirement> | null;
};
