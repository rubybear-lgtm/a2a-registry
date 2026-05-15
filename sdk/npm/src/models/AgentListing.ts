/* generated using openapi-typescript-codegen -- do not edit */
/* istanbul ignore file */
/* tslint:disable */
/* eslint-disable */
import type { AgentCardSkill } from './AgentCardSkill';
import type { AgentCardSupportedInterface } from './AgentCardSupportedInterface';
import type { AgentListingStatus } from './AgentListingStatus';
import type { SecurityRequirement } from './SecurityRequirement';
export type AgentListing = {
    id: string;
    name: string;
    description: string | null;
    provider_name: string | null;
    agent_version: string;
    documentation_url: string | null;
    icon_url: string | null;
    preferred_interface_url: string;
    preferred_protocol_binding: string;
    preferred_protocol_version: string;
    status: AgentListingStatus;
    has_auth: boolean;
    supports_streaming: boolean;
    supports_push_notifications: boolean;
    supports_extended_agent_card: boolean;
    default_input_modes: Array<string>;
    default_output_modes: Array<string>;
    supported_interfaces: Array<AgentCardSupportedInterface>;
    security_requirements: Array<SecurityRequirement> | null;
    skills: Array<AgentCardSkill>;
    source_url: string;
    card_url: string;
    fetched_at: string | null;
    validated_at: string | null;
};

