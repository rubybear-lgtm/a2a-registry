import { Head, Link } from '@inertiajs/react';
import { useMemo } from 'react';

import AgentRegistry from '@/actions/App/Http/Controllers/AgentRegistry';
import AppLogoIcon from '@/components/app-logo-icon';

function JsonHighlight({ json }: { json: string }) {
    const tokens = useMemo(() => {
        const re =
            /("(?:\\.|[^"\\])*")\s*:|("(?:\\.|[^"\\])*")|(true|false|null)|(-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?)|([{}[\],])/g;
        const nodes: React.ReactNode[] = [];
        let last = 0;
        let idx = 0;

        for (const match of json.matchAll(re)) {
            const [full, key, str, kw, num, punct] = match;
            const start = match.index!;

            if (start > last) nodes.push(json.slice(last, start));
            last = start + full.length;

            if (key !== undefined) {
                nodes.push(
                    <span key={idx++}>
                        <span className="text-sky-400/80">{key}</span>
                        <span className="text-muted-foreground">{full.slice(key.length)}</span>
                    </span>,
                );
            } else if (str !== undefined) {
                nodes.push(<span key={idx++} className="text-emerald-400/80">{str}</span>);
            } else if (kw !== undefined) {
                nodes.push(<span key={idx++} className="text-violet-400/80">{kw}</span>);
            } else if (num !== undefined) {
                nodes.push(<span key={idx++} className="text-amber-400/80">{num}</span>);
            } else if (punct !== undefined) {
                nodes.push(<span key={idx++} className="text-muted-foreground/50">{punct}</span>);
            }
        }

        if (last < json.length) nodes.push(json.slice(last));
        return nodes;
    }, [json]);

    return <>{tokens}</>;
}

interface Agent {
    id: string;
    name: string;
    provider_name: string;
    agent_version: string;
    preferred_protocol_binding: string;
    preferred_protocol_version: string;
    supports_streaming: boolean;
    has_auth: boolean;
    supports_push_notifications: boolean;
    skills: string[];
}

interface Props {
    agents: Agent[];
    total: number;
}

const DISPLAY = '"Big Shoulders Display", sans-serif';

const EXAMPLE_CARD = `{
  "name": "currency-agent",
  "version": "1.0.0",
  "provider": { "organization": "A2A Samples" },
  "capabilities": {
    "streaming": true,
    "pushNotifications": true
  },
  "skills": [
    { "id": "convert_currency", "name": "Currency Exchange Rates Tool" }
  ]
}`;

export default function Welcome({ agents, total }: Props) {
    return (
        <>
            <Head title="A2A Registry">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=big-shoulders-display:700,800&family=figtree:300,400,500,600"
                    rel="stylesheet"
                />
            </Head>

            <div className="min-h-screen bg-background text-foreground antialiased">
                {/* Nav */}
                <header className="sticky top-0 z-50 border-b border-border bg-background">
                    <nav className="mx-auto flex h-12 max-w-7xl items-center px-6">
                        <div className="flex flex-1 items-center gap-3">
                            <AppLogoIcon className="size-4" />
                            <span className="text-sm font-semibold tracking-tight">A2A Registry</span>
                            <span className="hidden font-mono text-[10px] text-muted-foreground sm:inline">
                                protocol/1.0
                            </span>
                        </div>
                        <Link
                            href={AgentRegistry.AgentListingWebController.index.url()}
                            className="py-3 -my-3 font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                        >
                            All agents →
                        </Link>
                    </nav>
                </header>

                {/* Hero — compact, orients fast */}
                <section className="hero-animate mx-auto max-w-7xl px-6 pb-14 pt-14">
                    <p className="hero-label mb-5 font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground">
                        A2A · Open Registry
                    </p>
                    <h1
                        className="hero-title mb-5 leading-none tracking-tight"
                        style={{
                            fontFamily: DISPLAY,
                            fontWeight: 700,
                            fontSize: 'clamp(3.25rem, 7vw, 5.5rem)',
                        }}
                    >
                        The A2A Registry.
                    </h1>
                    <p className="hero-subtitle max-w-[48ch] text-sm leading-relaxed text-muted-foreground">
                        Machine-readable agent cards. Version-tracked. Validation-enforced.
                    </p>
                </section>

                {/* Agent table — the main content */}
                <section className="border-t border-border">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="flex items-center justify-between border-b border-border py-3">
                            <span className="font-mono text-[10px] uppercase tracking-[0.14em] text-muted-foreground">
                                Recent
                            </span>
                            <span className="font-mono text-[10px] text-muted-foreground">
                                {agents.length} of {total}
                            </span>
                        </div>

                        {/* Column headers — desktop */}
                        <div className="hidden grid-cols-[minmax(0,1fr)_72px_92px_160px_minmax(0,1fr)] gap-x-6 border-b border-border py-2 text-[10px] font-medium uppercase tracking-widest text-muted-foreground lg:grid">
                            <span>Agent</span>
                            <span>Version</span>
                            <span>Protocol</span>
                            <span>Capabilities</span>
                            <span>Skills</span>
                        </div>

                        <div className="divide-y divide-border">
                            {agents.map((agent, i) => (
                                <Link
                                    key={agent.id}
                                    href={AgentRegistry.AgentListingWebController.show.url(agent.id)}
                                    className="animate-in fade-in group grid grid-cols-1 gap-y-1 py-3.5 transition-colors hover:bg-muted/20 lg:grid-cols-[minmax(0,1fr)_72px_92px_160px_minmax(0,1fr)] lg:items-center lg:gap-x-6"
                                    style={{
                                        animationDelay: `${i * 55}ms`,
                                        animationDuration: '280ms',
                                        animationFillMode: 'both',
                                    }}
                                >
                                    <div className="min-w-0">
                                        <span className="block truncate font-mono text-sm font-medium group-hover:underline group-hover:underline-offset-2">
                                            {agent.name}
                                        </span>
                                        <span className="text-xs text-muted-foreground">{agent.provider_name}</span>
                                    </div>

                                    <div className="font-mono text-xs text-muted-foreground">
                                        {agent.agent_version}
                                    </div>

                                    <div className="font-mono text-xs text-muted-foreground">
                                        {agent.preferred_protocol_binding?.toLowerCase()}/
                                        {agent.preferred_protocol_version}
                                    </div>

                                    <div className="flex flex-wrap gap-1">
                                        {agent.supports_streaming && (
                                            <span
                                                style={{
                                                    color: 'var(--solar-cyan)',
                                                    borderColor: 'color-mix(in oklch, var(--solar-cyan), transparent 80%)',
                                                    backgroundColor: 'color-mix(in oklch, var(--solar-cyan), transparent 95%)',
                                                }}
                                                className="rounded-sm border px-1.5 py-px font-mono text-[10px]"
                                            >
                                                streaming
                                            </span>
                                        )}
                                        {agent.supports_push_notifications && (
                                            <span
                                                style={{
                                                    color: 'var(--solar-orange)',
                                                    borderColor: 'color-mix(in oklch, var(--solar-orange), transparent 80%)',
                                                    backgroundColor: 'color-mix(in oklch, var(--solar-orange), transparent 95%)',
                                                }}
                                                className="rounded-sm border px-1.5 py-px font-mono text-[10px]"
                                            >
                                                push
                                            </span>
                                        )}
                                        {agent.has_auth && (
                                            <span
                                                style={{
                                                    color: 'var(--solar-violet)',
                                                    borderColor: 'color-mix(in oklch, var(--solar-violet), transparent 80%)',
                                                    backgroundColor: 'color-mix(in oklch, var(--solar-violet), transparent 95%)',
                                                }}
                                                className="rounded-sm border px-1.5 py-px font-mono text-[10px]"
                                            >
                                                auth
                                            </span>
                                        )}
                                    </div>

                                    <div className="truncate font-mono text-xs text-muted-foreground">
                                        {agent.skills.join(', ')}
                                    </div>
                                </Link>
                            ))}
                        </div>

                        <div className="border-t border-border py-5">
                            <Link
                                href={AgentRegistry.AgentListingWebController.index.url()}
                                className="font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                            >
                                All {total} agents →
                            </Link>
                        </div>
                    </div>
                </section>

                {/* Protocol — for newcomers to A2A, below the fold */}
                <section className="border-t border-border">
                    <div className="mx-auto max-w-7xl px-6 py-16 lg:py-20">
                        <div className="grid gap-12 lg:grid-cols-2 lg:gap-20">
                            <div>
                                <p className="mb-4 font-mono text-[10px] uppercase tracking-[0.15em] text-muted-foreground">
                                    Protocol
                                </p>
                                <h2
                                    className="mb-5 leading-tight"
                                    style={{
                                        fontFamily: DISPLAY,
                                        fontWeight: 700,
                                        fontSize: 'clamp(1.75rem, 3vw, 2.25rem)',
                                    }}
                                >
                                    Built on the A2A open standard.
                                </h2>
                                <p className="mb-8 max-w-[52ch] text-sm leading-relaxed text-muted-foreground">
                                    The Agent-to-Agent protocol defines a common interface for AI agents to
                                    communicate and delegate tasks. Each agent publishes a verifiable agent
                                    card — a machine-readable manifest of skills, interfaces, and
                                    authentication requirements.
                                </p>
                                <div className="space-y-3">
                                    {(
                                        [
                                            ['Fetch an agent card', 'GET /api/v1/agents/{id}/card'],
                                            ['Filter by capability', 'GET /api/v1/agents?streaming=true'],
                                            ['Browse by provider', 'GET /api/v1/agents?provider={name}'],
                                        ] as const
                                    ).map(([label, cmd]) => (
                                        <div key={label} className="flex flex-col gap-1">
                                            <span className="text-[11px] text-muted-foreground">{label}</span>
                                            <code className="rounded-sm border border-border bg-muted/30 px-3 py-1.5 font-mono text-xs">
                                                {cmd}
                                            </code>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div>
                                <p className="mb-4 font-mono text-[10px] uppercase tracking-[0.15em] text-muted-foreground">
                                    Example card
                                </p>

                                <pre className="overflow-x-auto rounded-sm border border-border bg-muted/30 p-5 font-mono text-[12px] leading-[1.7]">
                                    <JsonHighlight json={EXAMPLE_CARD} />
                                </pre>

                            </div>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="border-t border-border">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                        <div className="flex items-center gap-2.5 font-mono text-[10px] text-muted-foreground">
                            <AppLogoIcon className="size-3 opacity-40" />
                            <span>A2A Registry</span>
                            <span className="opacity-30">·</span>
                            <span className="opacity-50">Open Source</span>
                        </div>
                        <div className="flex gap-5 font-mono text-[10px] text-muted-foreground">
                            <a
                                href="https://google.github.io/A2A"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="transition-colors hover:text-foreground"
                            >
                                A2A Spec
                            </a>
                            <span className="opacity-40">API v1</span>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
