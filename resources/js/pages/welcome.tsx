import { Head, Link } from '@inertiajs/react';

import AppLogoIcon from '@/components/app-logo-icon';
import AgentRegistry from '@/actions/App/Http/Controllers/AgentRegistry';

const SAMPLE_AGENTS = [
    {
        name: 'web-search-agent',
        provider: 'Anthropic',
        version: '1.2.0',
        protocol: 'a2a/1.0',
        skills: ['search', 'summarize', 'cite'],
        streaming: true,
        auth: false,
        push: false,
        isNew: true,
    },
    {
        name: 'code-executor',
        provider: 'Google DeepMind',
        version: '0.9.1',
        protocol: 'a2a/1.0',
        skills: ['execute', 'debug', 'test'],
        streaming: true,
        auth: true,
        push: false,
        isNew: false,
    },
    {
        name: 'document-analyzer',
        provider: 'Microsoft',
        version: '2.1.0',
        protocol: 'a2a/1.0',
        skills: ['extract', 'classify', 'summarize', 'translate'],
        streaming: false,
        auth: true,
        push: false,
        isNew: false,
    },
    {
        name: 'calendar-assistant',
        provider: 'Acme Corp',
        version: '1.0.3',
        protocol: 'a2a/1.0',
        skills: ['schedule', 'remind', 'lookup'],
        streaming: false,
        auth: true,
        push: true,
        isNew: false,
    },
    {
        name: 'data-pipeline-agent',
        provider: 'DataFlow Inc',
        version: '3.0.0',
        protocol: 'a2a/1.0',
        skills: ['transform', 'validate', 'route', 'aggregate'],
        streaming: true,
        auth: true,
        push: true,
        isNew: false,
    },
] as const;

const EXAMPLE_CARD = `{
  "name": "web-search-agent",
  "version": "1.2.0",
  "provider": { "name": "Anthropic" },
  "capabilities": {
    "streaming": true,
    "pushNotifications": false
  },
  "skills": [
    { "id": "search", "name": "Web Search" },
    { "id": "summarize", "name": "Summarize" }
  ]
}`;

export default function Welcome() {
    return (
        <>
            <Head title="A2A Registry — The open agent registry">
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
                            <AppLogoIcon className="size-4 fill-current" />
                            <span className="text-sm font-semibold tracking-tight">A2A Registry</span>
                            <span className="hidden font-mono text-[11px] text-muted-foreground sm:inline">
                                protocol/1.0
                            </span>
                        </div>

                        <div className="flex items-center gap-1">
                            <Link
                                href={AgentRegistry.AgentListingWebController.index.url()}
                                className="rounded px-3 py-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground"
                            >
                                Agents
                            </Link>
                        </div>
                    </nav>
                </header>

                {/* Hero */}
                <section className="mx-auto max-w-7xl px-6 pb-0 pt-20 lg:pt-28">
                    <p className="mb-5 font-mono text-[11px] uppercase tracking-[0.15em] text-muted-foreground">
                        Agent-to-Agent Protocol · Open Registry
                    </p>
                    <h1
                        className="mb-7 max-w-3xl text-5xl leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl"
                        style={{ fontFamily: '"Syne", sans-serif', fontWeight: 800 }}
                    >
                        Every A2A agent,
                        <br />
                        one registry.
                    </h1>
                    <p className="mb-10 max-w-lg text-base leading-relaxed text-muted-foreground sm:text-[17px]">
                        Discover and publish AI agents implementing the Agent-to-Agent
                        protocol. Machine-readable agent cards. Version-tracked.
                        Validation-enforced.
                    </p>
                    <div className="flex flex-wrap items-center gap-5">
                        <a
                            href="#agents"
                            className="inline-flex h-9 items-center rounded-md bg-foreground px-5 text-sm font-medium text-background transition-colors hover:bg-foreground/85"
                        >
                            Browse agents
                        </a>
                        <a
                            href="https://google.github.io/A2A"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="text-sm text-muted-foreground transition-colors hover:text-foreground"
                        >
                            A2A Protocol docs →
                        </a>
                    </div>
                </section>

                {/* Agent registry table */}
                <section id="agents" className="mt-20 border-t border-border">
                    <div className="mx-auto max-w-7xl px-6">
                        {/* Section header */}
                        <div className="flex items-center justify-between border-b border-border py-4">
                            <p className="font-mono text-[11px] uppercase tracking-[0.12em] text-muted-foreground">
                                Recent agents
                            </p>
                            <p className="font-mono text-[11px] text-muted-foreground">5 of 142</p>
                        </div>

                        {/* Column headers (desktop) */}
                        <div className="hidden grid-cols-[minmax(0,1fr)_80px_90px_160px_minmax(0,1fr)] gap-x-6 border-b border-border py-2.5 text-[10px] font-medium uppercase tracking-widest text-muted-foreground lg:grid">
                            <span>Name · Provider</span>
                            <span>Version</span>
                            <span>Protocol</span>
                            <span>Capabilities</span>
                            <span>Skills</span>
                        </div>

                        {/* Agent rows */}
                        <div className="divide-y divide-border">
                            {SAMPLE_AGENTS.map((agent, i) => (
                                <Link
                                    key={agent.name}
                                    href={AgentRegistry.AgentListingWebController.index.url()}
                                    className="animate-in fade-in group grid grid-cols-1 gap-2 py-4 transition-colors hover:bg-muted/30 lg:grid-cols-[minmax(0,1fr)_80px_90px_160px_minmax(0,1fr)] lg:items-center lg:gap-x-6"
                                    style={{
                                        animationDelay: `${i * 80}ms`,
                                        animationDuration: '350ms',
                                        animationFillMode: 'both',
                                    }}
                                >
                                    {/* Name + provider */}
                                    <div className="flex min-w-0 items-center gap-3">
                                        <span className="relative flex size-1.5 shrink-0">
                                            <span
                                                className="absolute inline-flex size-full animate-ping rounded-full bg-foreground opacity-25"
                                                style={{ animationDuration: `${2.5 + i * 0.35}s` }}
                                            />
                                            <span className="relative inline-flex size-1.5 rounded-full bg-foreground/50" />
                                        </span>
                                        <div className="min-w-0">
                                            <div className="flex min-w-0 items-center gap-2">
                                                <span className="truncate font-mono text-sm font-medium group-hover:underline group-hover:underline-offset-2">
                                                    {agent.name}
                                                </span>
                                                {agent.isNew && (
                                                    <span className="shrink-0 rounded border border-border px-1.5 py-px font-mono text-[9px] uppercase tracking-wider text-muted-foreground">
                                                        new
                                                    </span>
                                                )}
                                            </div>
                                            <div className="text-xs text-muted-foreground">
                                                {agent.provider}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Version */}
                                    <div className="font-mono text-xs text-muted-foreground">
                                        {agent.version}
                                    </div>

                                    {/* Protocol */}
                                    <div className="font-mono text-xs text-muted-foreground">
                                        {agent.protocol}
                                    </div>

                                    {/* Capabilities */}
                                    <div className="flex flex-wrap gap-1">
                                        {agent.streaming && (
                                            <span className="rounded border border-border px-1.5 py-0.5 font-mono text-[10px] text-muted-foreground">
                                                streaming
                                            </span>
                                        )}
                                        {agent.auth && (
                                            <span className="rounded border border-border px-1.5 py-0.5 font-mono text-[10px] text-muted-foreground">
                                                auth
                                            </span>
                                        )}
                                        {agent.push && (
                                            <span className="rounded border border-border px-1.5 py-0.5 font-mono text-[10px] text-muted-foreground">
                                                push
                                            </span>
                                        )}
                                    </div>

                                    {/* Skills */}
                                    <div className="truncate font-mono text-xs text-muted-foreground">
                                        {agent.skills.join(', ')}
                                    </div>
                                </Link>
                            ))}
                        </div>

                        {/* Browse all */}
                        <div className="border-t border-border py-6 text-center">
                            <Link
                                href={AgentRegistry.AgentListingWebController.index.url()}
                                className="text-sm text-muted-foreground transition-colors hover:text-foreground"
                            >
                                Browse all agents →
                            </Link>
                        </div>
                    </div>
                </section>

                {/* Protocol section */}
                <section className="border-t border-border bg-muted/20">
                    <div className="mx-auto max-w-7xl px-6 py-16 lg:py-24">
                        <div className="grid gap-12 lg:grid-cols-2 lg:gap-20">
                            <div>
                                <p className="mb-4 font-mono text-[11px] uppercase tracking-[0.15em] text-muted-foreground">
                                    The Protocol
                                </p>
                                <h2
                                    className="mb-5 text-2xl tracking-tight sm:text-3xl"
                                    style={{ fontFamily: '"Syne", sans-serif', fontWeight: 700 }}
                                >
                                    Built on the A2A open standard.
                                </h2>
                                <p className="mb-8 text-sm leading-relaxed text-muted-foreground">
                                    The Agent-to-Agent protocol defines a common interface for AI agents
                                    to communicate, delegate tasks, and expose capabilities. Each
                                    registered agent publishes a verifiable agent card — a
                                    machine-readable manifest of skills, interfaces, and authentication
                                    requirements.
                                </p>
                                <div className="space-y-3">
                                    {(
                                        [
                                            ['Fetch any agent card', 'GET /api/v1/agents/{id}/card'],
                                            [
                                                'Filter by capability',
                                                'GET /api/v1/agents?streaming=true',
                                            ],
                                            ['Browse by provider', 'GET /api/v1/agents?provider={name}'],
                                        ] as const
                                    ).map(([label, cmd]) => (
                                        <div key={label} className="flex flex-col gap-1">
                                            <span className="text-[11px] text-muted-foreground">
                                                {label}
                                            </span>
                                            <code className="rounded-md border border-border bg-background px-3 py-1.5 font-mono text-xs">
                                                {cmd}
                                            </code>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div>
                                <p className="mb-4 font-mono text-[11px] uppercase tracking-[0.15em] text-muted-foreground">
                                    Example agent card
                                </p>
                                <pre className="overflow-x-auto rounded-lg border border-border bg-background p-5 font-mono text-[12px] leading-[1.7] text-foreground">
                                    {EXAMPLE_CARD}
                                </pre>
                            </div>
                        </div>
                    </div>
                </section>

                {/* CTA */}
                <section className="border-t border-border">
                    <div className="mx-auto max-w-7xl px-6 py-16 lg:py-20">
                        <div className="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <h2
                                    className="mb-3 text-2xl tracking-tight sm:text-3xl"
                                    style={{ fontFamily: '"Syne", sans-serif', fontWeight: 700 }}
                                >
                                    Browse the registry.
                                </h2>
                                <p className="max-w-md text-sm leading-relaxed text-muted-foreground">
                                    Every registered A2A agent — discoverable, machine-readable, and
                                    always up to date.
                                </p>
                            </div>
                            <div className="flex shrink-0 flex-wrap gap-3">
                                <Link
                                    href={AgentRegistry.AgentListingWebController.index.url()}
                                    className="inline-flex h-9 items-center rounded-md bg-foreground px-5 text-sm font-medium text-background transition-colors hover:bg-foreground/85"
                                >
                                    Browse agents
                                </Link>
                                <a
                                    href="https://google.github.io/A2A"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex h-9 items-center rounded-md border border-border px-5 text-sm font-medium transition-colors hover:bg-muted"
                                >
                                    A2A Spec
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="border-t border-border">
                    <div className="mx-auto flex max-w-7xl flex-col items-start justify-between gap-4 px-6 py-5 text-xs text-muted-foreground sm:flex-row sm:items-center">
                        <div className="flex items-center gap-2">
                            <AppLogoIcon className="size-3.5 fill-current opacity-40" />
                            <span className="font-mono opacity-70">A2A Registry</span>
                            <span className="opacity-30">·</span>
                            <span className="font-mono opacity-70">Open Source</span>
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
                            <a
                                href="https://google.github.io/A2A"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="transition-colors hover:text-foreground"
                            >
                                GitHub
                            </a>
                            <span className="opacity-60">API v1</span>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
