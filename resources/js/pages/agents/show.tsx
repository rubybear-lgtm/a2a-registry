import { Head, Link, usePage } from '@inertiajs/react';
import { ChevronDown, Copy, ExternalLink } from 'lucide-react';
import { useCallback, useState } from 'react';

import AppLogoIcon from '@/components/app-logo-icon';
import AgentRegistry from '@/actions/App/Http/Controllers/AgentRegistry';
import { home } from '@/routes';
import { useClipboard } from '@/hooks/use-clipboard';

type AgentSkill = {
    id: string;
    name: string;
    description?: string;
    tags?: string[];
    examples?: string[];
    inputModes?: string[];
    outputModes?: string[];
};

type AgentInterface = {
    url: string;
    protocolBinding: string;
    protocolVersion: string;
};

type SecurityScheme = {
    httpAuthSecurityScheme?: { scheme: string };
    apiKeySecurityScheme?: { in: string; name: string };
    oauth2SecurityScheme?: { flows: Record<string, unknown> };
    [key: string]: unknown;
};

type Agent = {
    id: string;
    name: string;
    description: string | null;
    provider_name: string;
    agent_version: string;
    preferred_interface_url: string | null;
    preferred_protocol_binding: string;
    preferred_protocol_version: string | null;
    documentation_url: string | null;
    icon_url: string | null;
    source_url: string;
    card_url: string;
    has_auth: boolean;
    supports_streaming: boolean;
    supports_push_notifications: boolean;
    supports_extended_agent_card: boolean;
    status: string;
    last_http_status: number | null;
    last_error: string | null;
    fetched_at: string | null;
    validated_at: string | null;
    provider: { organization?: string; url?: string } | null;
    capabilities: { streaming?: boolean; pushNotifications?: boolean; extendedAgentCard?: boolean } | null;
    skills: AgentSkill[] | null;
    supported_interfaces: AgentInterface[] | null;
    security_schemes: Record<string, SecurityScheme> | null;
    security_requirements: Array<Record<string, unknown>> | null;
    validation_warnings: string[];
    raw_card: Record<string, unknown> | null;
};

type Props = {
    agent: Agent;
};

function relativeTime(iso: string): string {
    const diff = Date.now() - new Date(iso).getTime();
    const m = Math.floor(diff / 60000);
    if (m < 1) return 'just now';
    if (m < 60) return `${m}m ago`;
    const h = Math.floor(m / 60);
    if (h < 24) return `${h}h ago`;
    const d = Math.floor(h / 24);
    return `${d}d ago`;
}

function StatusLabel({ status }: { status: string }) {
    const label = status.charAt(0).toUpperCase() + status.slice(1);
    const isActive = status === 'active';
    const isError = status === 'error' || status === 'degraded';

    return (
        <span className="flex items-center gap-1.5">
            <span
                className={[
                    'relative flex size-1.5 rounded-full',
                    isActive ? 'bg-foreground/60' : isError ? 'bg-muted-foreground/60' : 'bg-muted-foreground/40',
                ].join(' ')}
            >
                {isActive && (
                    <span className="absolute inline-flex size-full animate-ping rounded-full bg-foreground/30" style={{ animationDuration: '2s' }} />
                )}
            </span>
            <span className="font-mono text-[11px] text-muted-foreground">{label}</span>
        </span>
    );
}

function SkillRow({ skill }: { skill: AgentSkill }) {
    const [open, setOpen] = useState(false);
    const hasExamples = skill.examples && skill.examples.length > 0;

    return (
        <div className="py-5">
            <div
                className={['flex items-start justify-between gap-4', hasExamples ? 'cursor-pointer' : ''].join(' ')}
                onClick={() => hasExamples && setOpen((v) => !v)}
                role={hasExamples ? 'button' : undefined}
                tabIndex={hasExamples ? 0 : undefined}
                onKeyDown={(e) => hasExamples && e.key === 'Enter' && setOpen((v) => !v)}
            >
                <div className="min-w-0 flex-1">
                    <div className="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <span className="text-sm font-medium">{skill.name}</span>
                        <span className="font-mono text-[11px] text-muted-foreground">{skill.id}</span>
                    </div>
                    {skill.description && (
                        <p className="mt-1 text-sm text-muted-foreground">{skill.description}</p>
                    )}
                    <div className="mt-2.5 flex flex-wrap gap-3">
                        {skill.tags && skill.tags.length > 0 && (
                            <div className="flex flex-wrap gap-1">
                                {skill.tags.map((tag) => (
                                    <span
                                        key={tag}
                                        className="rounded border border-border px-1.5 py-px font-mono text-[10px] text-muted-foreground"
                                    >
                                        {tag}
                                    </span>
                                ))}
                            </div>
                        )}
                        {skill.inputModes && skill.inputModes.length > 0 && (
                            <span className="font-mono text-[11px] text-muted-foreground">
                                in: {skill.inputModes.join(', ')}
                            </span>
                        )}
                        {skill.outputModes && skill.outputModes.length > 0 && (
                            <span className="font-mono text-[11px] text-muted-foreground">
                                out: {skill.outputModes.join(', ')}
                            </span>
                        )}
                    </div>
                </div>
                {hasExamples && (
                    <ChevronDown
                        className={[
                            'mt-0.5 size-3.5 shrink-0 text-muted-foreground/50 transition-transform duration-200',
                            open ? 'rotate-180' : '',
                        ].join(' ')}
                    />
                )}
            </div>

            {/* Examples — grid-template-rows expand */}
            {hasExamples && (
                <div
                    className="grid transition-[grid-template-rows] duration-200 ease-out"
                    style={{ gridTemplateRows: open ? '1fr' : '0fr' }}
                >
                    <div className="overflow-hidden">
                        <div className="mt-3 space-y-1.5 border-l-2 border-border pl-4">
                            <p className="font-mono text-[10px] uppercase tracking-wider text-muted-foreground/60">
                                Examples
                            </p>
                            {skill.examples!.map((ex, i) => (
                                <p key={i} className="text-sm text-muted-foreground">
                                    {ex}
                                </p>
                            ))}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

function SectionHeading({ children, count }: { children: React.ReactNode; count?: number }) {
    return (
        <div className="mb-5 flex items-center gap-3 border-b border-border pb-3">
            <h2 className="text-[11px] font-medium uppercase tracking-widest text-muted-foreground">
                {children}
            </h2>
            {count !== undefined && (
                <span className="font-mono text-[11px] text-muted-foreground/50">{count}</span>
            )}
        </div>
    );
}

function SidebarBlock({ label, children }: { label: string; children: React.ReactNode }) {
    return (
        <div>
            <p className="mb-2 font-mono text-[10px] uppercase tracking-wider text-muted-foreground/60">
                {label}
            </p>
            {children}
        </div>
    );
}

function securitySchemeDescription(name: string, scheme: SecurityScheme): string {
    if (scheme.httpAuthSecurityScheme) {
        return `HTTP ${scheme.httpAuthSecurityScheme.scheme}`;
    }
    if (scheme.apiKeySecurityScheme) {
        return `API Key (${scheme.apiKeySecurityScheme.in}: ${scheme.apiKeySecurityScheme.name})`;
    }
    if (scheme.oauth2SecurityScheme) {
        return 'OAuth 2.0';
    }
    return name;
}

export default function AgentsShow() {
    const { agent } = usePage<Props>().props;
    const [, copy] = useClipboard();
    const [copiedKey, setCopiedKey] = useState<string | null>(null);

    const copyWithFeedback = useCallback(
        async (text: string, key: string) => {
            const ok = await copy(text);
            if (ok) {
                setCopiedKey(key);
                setTimeout(() => setCopiedKey(null), 1500);
            }
        },
        [copy],
    );

    const capList = [
        agent.supports_streaming && 'streaming',
        agent.supports_push_notifications && 'push',
        agent.supports_extended_agent_card && 'extended card',
        agent.has_auth && 'auth',
    ].filter(Boolean) as string[];

    const rawCardJson = agent.raw_card
        ? JSON.stringify(agent.raw_card, null, 2)
        : null;

    const requiredSchemes = new Set(
        (agent.security_requirements ?? []).flatMap((r) => Object.keys(r)),
    );

    return (
        <>
            <Head title={`${agent.name} — A2A Registry`}>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&family=syne:700,800"
                    rel="stylesheet"
                />
            </Head>

            <div className="min-h-screen bg-background text-foreground antialiased">
                {/* Nav */}
                <header className="sticky top-0 z-50 border-b border-border bg-background/95 backdrop-blur-sm">
                    <nav className="mx-auto flex max-w-7xl items-center px-6 py-3">
                        <div className="flex flex-1 items-center gap-2.5">
                            <Link href={home()} className="flex items-center gap-2.5">
                                <AppLogoIcon className="size-4 fill-current" />
                                <span className="text-sm font-semibold tracking-tight">A2A Registry</span>
                            </Link>
                            <span className="hidden font-mono text-[11px] text-muted-foreground sm:inline">
                                protocol/1.0
                            </span>
                        </div>
                    </nav>
                </header>

                {/* Zone A — Header */}
                <div className="border-b border-border">
                    <div className="mx-auto max-w-7xl px-6 py-8 lg:py-10">
                        {/* Breadcrumb */}
                        <Link
                            href={AgentRegistry.AgentListingWebController.index.url()}
                            className="mb-5 inline-flex items-center gap-1.5 font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                        >
                            ← Agents
                        </Link>

                        {/* Name + version */}
                        <div className="mt-3 flex flex-wrap items-baseline gap-x-4 gap-y-2">
                            <h1
                                className="text-4xl tracking-tight sm:text-5xl"
                                style={{ fontFamily: '"Syne", sans-serif', fontWeight: 800 }}
                            >
                                {agent.name}
                            </h1>
                            <span className="rounded border border-border px-2 py-0.5 font-mono text-xs text-muted-foreground">
                                v{agent.agent_version}
                            </span>
                        </div>

                        {/* Meta row */}
                        <div className="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2">
                            <span className="text-sm text-muted-foreground">{agent.provider_name}</span>
                            <span className="font-mono text-xs text-muted-foreground">
                                {agent.preferred_protocol_binding?.toLowerCase()}/{agent.preferred_protocol_version}
                            </span>
                            <StatusLabel status={agent.status} />
                            {capList.map((cap) => (
                                <span
                                    key={cap}
                                    className="rounded border border-border px-1.5 py-px font-mono text-[10px] text-muted-foreground"
                                >
                                    {cap}
                                </span>
                            ))}
                        </div>

                        {/* Copy card URL */}
                        <div className="mt-6">
                            <button
                                onClick={() => copyWithFeedback(agent.card_url, 'header')}
                                className="inline-flex h-8 items-center gap-2 rounded-md bg-foreground px-4 text-xs font-medium text-background transition-colors hover:bg-foreground/85"
                            >
                                <Copy className="size-3" />
                                {copiedKey === 'header' ? 'Copied!' : 'Copy card URL'}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Validation warnings */}
                {agent.validation_warnings.length > 0 && (
                    <div className="border-b border-border bg-muted/30">
                        <div className="mx-auto max-w-7xl px-6 py-3">
                            <p className="mb-1 font-mono text-[10px] uppercase tracking-wider text-muted-foreground">
                                Validation warnings ({agent.validation_warnings.length})
                            </p>
                            <ul className="space-y-0.5">
                                {agent.validation_warnings.map((w, i) => (
                                    <li key={i} className="text-sm text-muted-foreground">
                                        {w}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                )}

                {/* Zone B — Two-column body */}
                <div className="mx-auto max-w-7xl px-6 py-10 lg:py-14">
                    <div className="grid gap-10 lg:grid-cols-[minmax(0,1fr)_300px] lg:gap-14 xl:grid-cols-[minmax(0,1fr)_320px]">
                        {/* Left column */}
                        <div className="space-y-12">
                            {/* Description */}
                            {agent.description && (
                                <section>
                                    <p className="text-base leading-relaxed text-muted-foreground">
                                        {agent.description}
                                    </p>
                                </section>
                            )}

                            {/* Skills */}
                            <section>
                                <SectionHeading count={agent.skills?.length ?? 0}>Skills</SectionHeading>
                                {agent.skills && agent.skills.length > 0 ? (
                                    <div className="divide-y divide-border">
                                        {agent.skills.map((skill) => (
                                            <SkillRow key={skill.id} skill={skill} />
                                        ))}
                                    </div>
                                ) : (
                                    <p className="text-sm text-muted-foreground">
                                        This agent has not declared any skills.
                                    </p>
                                )}
                            </section>

                            {/* Interfaces */}
                            {agent.supported_interfaces && agent.supported_interfaces.length > 0 && (
                                <section>
                                    <SectionHeading count={agent.supported_interfaces.length}>
                                        Interfaces
                                    </SectionHeading>
                                    <div className="divide-y divide-border">
                                        {agent.supported_interfaces.map((iface, i) => (
                                            <div key={i} className="py-4">
                                                <div className="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                                    <span className="font-mono text-xs font-medium">
                                                        {iface.protocolBinding}
                                                    </span>
                                                    <span className="font-mono text-[11px] text-muted-foreground">
                                                        v{iface.protocolVersion}
                                                    </span>
                                                </div>
                                                <div className="mt-1 flex items-center gap-2">
                                                    <span className="truncate font-mono text-xs text-muted-foreground">
                                                        {iface.url}
                                                    </span>
                                                    <button
                                                        onClick={() => copyWithFeedback(iface.url, `iface-${i}`)}
                                                        className="shrink-0 text-muted-foreground/50 transition-colors hover:text-foreground"
                                                        title="Copy URL"
                                                    >
                                                        <Copy className="size-3" />
                                                    </button>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </section>
                            )}

                            {/* Security */}
                            <section>
                                <SectionHeading>Security</SectionHeading>
                                {agent.security_schemes && Object.keys(agent.security_schemes).length > 0 ? (
                                    <div className="divide-y divide-border">
                                        {Object.entries(agent.security_schemes).map(([name, scheme]) => (
                                            <div key={name} className="py-4">
                                                <div className="flex flex-wrap items-center gap-x-3 gap-y-1">
                                                    <span className="font-mono text-xs font-medium">{name}</span>
                                                    <span className="font-mono text-[11px] text-muted-foreground">
                                                        {securitySchemeDescription(name, scheme)}
                                                    </span>
                                                    {requiredSchemes.has(name) && (
                                                        <span className="rounded border border-border px-1.5 py-px font-mono text-[10px] text-muted-foreground">
                                                            required
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <p className="text-sm text-muted-foreground">
                                        This agent does not require authentication.
                                    </p>
                                )}
                            </section>
                        </div>

                        {/* Right column — sticky sidebar */}
                        <div>
                            <div className="space-y-6 lg:sticky lg:top-20">
                                {/* Card endpoint */}
                                <div className="rounded-lg border border-border p-4">
                                    <SidebarBlock label="Card endpoint">
                                        <div className="mt-1 flex items-start gap-2">
                                            <code className="min-w-0 flex-1 truncate font-mono text-[11px] text-muted-foreground">
                                                {agent.card_url}
                                            </code>
                                            <button
                                                onClick={() => copyWithFeedback(agent.card_url, 'sidebar')}
                                                className="shrink-0 text-muted-foreground/50 transition-colors hover:text-foreground"
                                                title="Copy"
                                            >
                                                <Copy className="size-3.5" />
                                            </button>
                                        </div>
                                        {copiedKey === 'sidebar' && (
                                            <p className="mt-1 font-mono text-[10px] text-muted-foreground">
                                                Copied!
                                            </p>
                                        )}
                                    </SidebarBlock>
                                </div>

                                {/* Registry status */}
                                <div className="space-y-4 border-t border-border pt-5">
                                    <SidebarBlock label="Registry status">
                                        <div className="mt-2 space-y-2">
                                            {agent.fetched_at && (
                                                <div className="flex items-center justify-between">
                                                    <span className="text-xs text-muted-foreground">Fetched</span>
                                                    <span className="font-mono text-[11px] text-muted-foreground">
                                                        {relativeTime(agent.fetched_at)}
                                                    </span>
                                                </div>
                                            )}
                                            {agent.validated_at && (
                                                <div className="flex items-center justify-between">
                                                    <span className="text-xs text-muted-foreground">Validated</span>
                                                    <span className="font-mono text-[11px] text-muted-foreground">
                                                        {relativeTime(agent.validated_at)}
                                                    </span>
                                                </div>
                                            )}
                                            {agent.last_http_status && (
                                                <div className="flex items-center justify-between">
                                                    <span className="text-xs text-muted-foreground">HTTP status</span>
                                                    <span className="font-mono text-[11px] text-muted-foreground">
                                                        {agent.last_http_status}
                                                    </span>
                                                </div>
                                            )}
                                        </div>
                                    </SidebarBlock>

                                    {agent.last_error && (
                                        <div className="rounded border border-border p-3">
                                            <p className="font-mono text-[10px] uppercase tracking-wider text-muted-foreground/60">
                                                Last error
                                            </p>
                                            <p className="mt-1 font-mono text-[11px] text-muted-foreground">
                                                {agent.last_error}
                                            </p>
                                        </div>
                                    )}
                                </div>

                                {/* Provider */}
                                {agent.provider && (
                                    <div className="border-t border-border pt-5">
                                        <SidebarBlock label="Provider">
                                            <div className="mt-1">
                                                <p className="text-sm">{agent.provider.organization ?? agent.provider_name}</p>
                                                {agent.provider.url && (
                                                    <a
                                                        href={agent.provider.url}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="inline-flex items-center gap-1 font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                                                    >
                                                        {agent.provider.url.replace(/^https?:\/\//, '')}
                                                        <ExternalLink className="size-2.5" />
                                                    </a>
                                                )}
                                            </div>
                                        </SidebarBlock>
                                    </div>
                                )}

                                {/* External links */}
                                <div className="border-t border-border pt-5 space-y-2">
                                    <SidebarBlock label="Links">
                                        <div className="mt-2 flex flex-col gap-2">
                                            <a
                                                href={agent.source_url}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="inline-flex items-center gap-1.5 font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                                            >
                                                <ExternalLink className="size-2.5 shrink-0" />
                                                Source card
                                            </a>
                                            {agent.documentation_url && (
                                                <a
                                                    href={agent.documentation_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center gap-1.5 font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                                                >
                                                    <ExternalLink className="size-2.5 shrink-0" />
                                                    Documentation
                                                </a>
                                            )}
                                            {agent.preferred_interface_url && (
                                                <a
                                                    href={agent.preferred_interface_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center gap-1.5 font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                                                >
                                                    <ExternalLink className="size-2.5 shrink-0" />
                                                    Interface endpoint
                                                </a>
                                            )}
                                        </div>
                                    </SidebarBlock>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Zone C — Raw card */}
                <div className="border-t border-border bg-muted/20">
                    <div className="mx-auto max-w-7xl px-6 py-12">
                        <div className="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <h2 className="text-[11px] font-medium uppercase tracking-widest text-muted-foreground">
                                    Agent Card
                                </h2>
                                {agent.source_url && (
                                    <a
                                        href={agent.source_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="mt-1 inline-flex items-center gap-1 font-mono text-[11px] text-muted-foreground/60 transition-colors hover:text-muted-foreground"
                                    >
                                        {agent.source_url}
                                        <ExternalLink className="size-2.5" />
                                    </a>
                                )}
                            </div>
                            {rawCardJson && (
                                <button
                                    onClick={() => copyWithFeedback(rawCardJson, 'json')}
                                    className="inline-flex shrink-0 items-center gap-1.5 rounded-md border border-border bg-background px-3 py-1.5 font-mono text-[11px] text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                >
                                    <Copy className="size-3" />
                                    {copiedKey === 'json' ? 'Copied!' : 'Copy JSON'}
                                </button>
                            )}
                        </div>

                        {rawCardJson ? (
                            <pre className="overflow-x-auto rounded-lg border border-border bg-background p-6 font-mono text-[12px] leading-[1.75] text-foreground">
                                {rawCardJson}
                            </pre>
                        ) : (
                            <div className="rounded-lg border border-border bg-background p-8 text-center">
                                <p className="font-mono text-sm text-muted-foreground">
                                    Agent card unavailable
                                    {agent.last_error && (
                                        <>
                                            {' '}— {agent.last_error}
                                        </>
                                    )}
                                    {agent.last_http_status && (
                                        <span className="ml-2 font-mono text-[11px]">
                                            (HTTP {agent.last_http_status})
                                        </span>
                                    )}
                                </p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Footer */}
                <footer className="border-t border-border">
                    <div className="mx-auto flex max-w-7xl flex-col items-start justify-between gap-4 px-6 py-5 text-xs text-muted-foreground sm:flex-row sm:items-center">
                        <div className="flex items-center gap-2">
                            <AppLogoIcon className="size-3.5 fill-current opacity-40" />
                            <span className="font-mono opacity-70">A2A Registry</span>
                        </div>
                        <div className="flex gap-5 font-mono">
                            <a
                                href="https://google.github.io/A2A"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="transition-colors hover:text-foreground"
                            >
                                A2A Spec
                            </a>
                            <span className="opacity-60">API v1</span>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
