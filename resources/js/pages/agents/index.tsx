import { Head, Link, router, usePage } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { useState } from 'react';

import AgentRegistry from '@/actions/App/Http/Controllers/AgentRegistry';
import AppLogoIcon from '@/components/app-logo-icon';
import { home } from '@/routes';

type AgentSummary = {
    id: string;
    name: string;
    description: string | null;
    provider_name: string;
    agent_version: string;
    preferred_protocol_binding: string;
    preferred_protocol_version: string | null;
    supports_streaming: boolean;
    supports_push_notifications: boolean;
    supports_extended_agent_card: boolean;
    has_auth: boolean;
    status: string;
    fetched_at: string | null;
    skills_count: number;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type PaginatedAgents = {
    data: AgentSummary[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: PaginationLink[];
};

type Props = {
    agents: PaginatedAgents;
    filters: { q: string | null };
};

function StatusDot({ status }: { status: string }) {
    if (status === 'active') {
        return (
            <span className="relative flex size-1.5 shrink-0">
                <span
                    className="absolute inline-flex size-full animate-ping rounded-full bg-(--solar-green)/30"
                    style={{ animationDuration: '2.5s' }}
                />
                <span className="relative inline-flex size-1.5 rounded-full bg-(--solar-green)" />
            </span>
        );
    }

    return <span className="relative inline-flex size-1.5 shrink-0 rounded-full bg-muted-foreground/30" />;
}

function CapBadges({ agent }: { agent: AgentSummary }) {
    const caps = [
        { id: 'streaming', active: agent.supports_streaming, color: 'var(--solar-cyan)' },
        { id: 'push', active: agent.supports_push_notifications, color: 'var(--solar-orange)' },
        { id: 'auth', active: agent.has_auth, color: 'var(--solar-violet)' },
    ].filter(c => c.active);

    if (caps.length === 0) {
        return null;
    }

    return (
        <div className="flex flex-wrap gap-1">
            {caps.map((cap) => (
                <span
                    key={cap.id}
                    style={{ 
                        color: cap.color, 
                        borderColor: `color-mix(in oklch, ${cap.color}, transparent 80%)`, 
                        backgroundColor: `color-mix(in oklch, ${cap.color}, transparent 95%)` 
                    }}
                    className="rounded-sm border px-1.5 py-px font-mono text-[10px]"
                >
                    {cap.id}
                </span>
            ))}
        </div>
    );
}

export default function AgentsIndex() {
    const { agents, filters } = usePage<Props>().props;
    const [search, setSearch] = useState(filters.q ?? '');

    function handleSearch(e: FormEvent) {
        e.preventDefault();
        router.get(
            AgentRegistry.AgentListingWebController.index.url(),
            { q: search.trim() || undefined },
            { preserveState: true, replace: true },
        );
    }

    function handleRowClick(id: string) {
        router.visit(AgentRegistry.AgentListingWebController.show.url({ agentListing: id }));
    }

    return (
        <>
            <Head title="Agents — A2A Registry">
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
                            <Link href={home()} className="flex items-center gap-3 py-3 -my-3">
                                <AppLogoIcon className="size-4" />
                                <span className="text-sm font-semibold tracking-tight">A2A Registry</span>
                            </Link>
                            <span className="hidden font-mono text-[10px] text-muted-foreground sm:inline">
                                protocol/1.0
                            </span>
                        </div>
                    </nav>
                </header>

                {/* Page header */}
                <div className="border-b border-border">
                    <div className="mx-auto max-w-7xl px-6 py-8">
                        <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p className="mb-2 font-mono text-[10px] uppercase tracking-[0.15em] text-muted-foreground">
                                    <Link href={home()} className="text-muted-foreground transition-colors hover:text-foreground">
                                        A2A Registry
                                    </Link>
                                    <span className="mx-1.5 opacity-40">/</span>
                                    Agents
                                </p>
                                <h1
                                    className="leading-none tracking-tight"
                                    style={{
                                        fontFamily: '"Big Shoulders Display", sans-serif',
                                        fontWeight: 700,
                                        fontSize: '2.5rem',
                                    }}
                                >
                                    Agents
                                </h1>
                                {agents.total > 0 && (
                                    <p className="mt-1.5 font-mono text-[11px] text-muted-foreground">
                                        {agents.total} registered
                                    </p>
                                )}
                            </div>

                            {/* Search */}
                            <form onSubmit={handleSearch} className="flex items-center gap-2">
                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Search agents..."
                                    className="h-8 w-56 rounded-sm border border-border bg-muted/30 px-3 font-mono text-xs placeholder:text-muted-foreground/40 focus:border-(--solar-violet)/50 focus:ring-1 focus:ring-(--solar-violet)/20 focus:outline-none"
                                />
                                {filters.q && (
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setSearch('');
                                            router.get(AgentRegistry.AgentListingWebController.index.url(), {}, { replace: true });
                                        }}
                                        className="font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                                    >
                                        clear
                                    </button>
                                )}
                            </form>
                        </div>
                    </div>
                </div>

                {/* Table */}
                <div className="mx-auto max-w-7xl px-6">
                    {agents.data.length === 0 ? (
                        <div className="py-20 text-center">
                            <p className="font-mono text-sm text-muted-foreground">
                                {filters.q ? `No agents matching "${filters.q}"` : 'No agents registered yet.'}
                            </p>
                            {filters.q && (
                                <button
                                    onClick={() => {
                                        setSearch('');
                                        router.get(AgentRegistry.AgentListingWebController.index.url(), {}, { replace: true });
                                    }}
                                    className="mt-3 font-mono text-[11px] text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    Clear search →
                                </button>
                            )}
                        </div>
                    ) : (
                        <>
                            {/* Column headers */}
                            <div className="hidden grid-cols-[minmax(0,1fr)_72px_100px_180px_52px] gap-x-6 border-b border-border py-2 text-[10px] font-medium uppercase tracking-widest text-muted-foreground lg:grid">
                                <span>Agent</span>
                                <span>Version</span>
                                <span>Protocol</span>
                                <span>Capabilities</span>
                                <span className="text-right">Skills</span>
                            </div>

                            <div className="divide-y divide-border">
                                {agents.data.map((agent, i) => (
                                    <button
                                        key={agent.id}
                                        onClick={() => handleRowClick(agent.id)}
                                        className="animate-in fade-in group w-full cursor-pointer py-3.5 text-left transition-colors hover:bg-(--solar-violet)/5"
                                        style={{
                                            animationDelay: `${i * 35}ms`,
                                            animationDuration: '260ms',
                                            animationFillMode: 'both',
                                        }}
                                    >
                                        <div className="grid grid-cols-1 gap-y-1 lg:grid-cols-[minmax(0,1fr)_72px_100px_180px_52px] lg:items-center lg:gap-x-6">
                                            <div className="flex min-w-0 items-center gap-3">
                                                <StatusDot status={agent.status} />
                                                <div className="min-w-0">
                                                    <span className="block truncate font-mono text-sm font-medium group-hover:underline group-hover:underline-offset-2">
                                                        {agent.name}
                                                    </span>
                                                    <span className="block truncate text-xs text-muted-foreground">
                                                        {agent.provider_name}
                                                    </span>
                                                </div>
                                            </div>

                                            <div className="font-mono text-xs text-muted-foreground">
                                                {agent.agent_version}
                                            </div>

                                            <div className="font-mono text-xs text-muted-foreground">
                                                {agent.preferred_protocol_binding?.toLowerCase()}/
                                                {agent.preferred_protocol_version}
                                            </div>

                                            <CapBadges agent={agent} />

                                            <div className="hidden text-right font-mono text-xs text-muted-foreground lg:block">
                                                {agent.skills_count}
                                            </div>
                                        </div>
                                    </button>
                                ))}
                            </div>

                            {/* Pagination */}
                            {agents.last_page > 1 && (
                                <div className="flex items-center justify-between border-t border-border py-5">
                                    <p className="font-mono text-[11px] text-muted-foreground">
                                        {agents.from}–{agents.to} of {agents.total}
                                    </p>
                                    <div className="flex gap-5">
                                        {agents.links.map((link, i) => {
                                            const isPrev = i === 0;
                                            const isNext = i === agents.links.length - 1;

                                            if (!isPrev && !isNext) {
                                                return null;
                                            }

                                            if (!link.url) {
                                                return (
                                                    <span key={i} className="font-mono text-xs text-muted-foreground/30">
                                                        {isPrev ? '← Previous' : 'Next →'}
                                                    </span>
                                                );
                                            }

                                            return (
                                                <Link
                                                    key={i}
                                                    href={link.url}
                                                    className="font-mono text-xs text-muted-foreground transition-colors hover:text-foreground"
                                                >
                                                    {isPrev ? '← Previous' : 'Next →'}
                                                </Link>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}
                        </>
                    )}
                </div>

                {/* Footer */}
                <footer className="mt-16 border-t border-border">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                        <div className="flex items-center gap-2.5 font-mono text-[10px] text-muted-foreground">
                            <AppLogoIcon className="size-3 opacity-40" />
                            <span>A2A Registry</span>
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
