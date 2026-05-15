/* generated using openapi-typescript-codegen -- do not edit */
/* istanbul ignore file */
/* tslint:disable */
/* eslint-disable */
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

