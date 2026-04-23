import { Head, Link, router, usePage } from '@inertiajs/react';
import { FormEvent, useState } from 'react';

import AppLogoIcon from '@/components/app-logo-icon';
import AgentRegistry from '@/actions/App/Http/Controllers/AgentRegistry';
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
                <span className="absolute inline-flex size-full animate-ping rounded-full bg-foreground/40 opacity-75" style={{ animationDuration: '2s' }} />
                <span className="relative inline-flex size-1.5 rounded-full bg-foreground/60" />
            </span>
        );
    }
    return <span className="relative inline-flex size-1.5 shrink-0 rounded-full bg-muted-foreground/40" />;
}

function CapBadges({ agent }: { agent: AgentSummary }) {
    const caps = [
        agent.supports_streaming && 'streaming',
        agent.supports_push_notifications && 'push',
        agent.has_auth && 'auth',
    ].filter(Boolean) as string[];

    if (caps.length === 0) return null;

    return (
        <div className="flex flex-wrap gap-1">
            {caps.map((cap) => (
                <span
                    key={cap}
                    className="rounded border border-border px-1.5 py-px font-mono text-[10px] text-muted-foreground"
                >
                    {cap}
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

                {/* Page header */}
                <div className="border-b border-border">
                    <div className="mx-auto max-w-7xl px-6 py-8">
                        <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p className="mb-2 font-mono text-[11px] uppercase tracking-[0.15em] text-muted-foreground">
                                    <Link href={home()} className="hover:text-foreground transition-colors">A2A Registry</Link>
                                    <span className="mx-1.5 opacity-40">/</span>
                                    Agents
                                </p>
                                <h1
                                    className="text-3xl tracking-tight sm:text-4xl"
                                    style={{ fontFamily: '"Syne", sans-serif', fontWeight: 800 }}
                                >
                                    Agents
                                </h1>
                                {agents.total > 0 && (
                                    <p className="mt-1 font-mono text-[11px] text-muted-foreground">
                                        {agents.total} registered
                                    </p>
                                )}
                            </div>

                            {/* Search */}
                            <form onSubmit={handleSearch} className="flex items-center gap-2">
                                <div className="relative">
                                    <input
                                        type="text"
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        placeholder="Search agents..."
                                        className="h-8 w-56 rounded-md border border-border bg-background px-3 font-mono text-xs placeholder:text-muted-foreground/50 focus:border-foreground/30 focus:outline-none transition-colors"
                                    />
                                </div>
                                {filters.q && (
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setSearch('');
                                            router.get(AgentRegistry.AgentListingWebController.index.url(), {}, { replace: true });
                                        }}
                                        className="font-mono text-[11px] text-muted-foreground hover:text-foreground transition-colors"
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
                                {filters.q
                                    ? `No agents matching "${filters.q}"`
                                    : 'No agents registered yet.'}
                            </p>
                        </div>
                    ) : (
                        <>
                            {/* Column headers */}
                            <div className="hidden grid-cols-[minmax(0,1fr)_80px_100px_180px_60px] gap-x-6 border-b border-border py-2.5 text-[10px] font-medium uppercase tracking-widest text-muted-foreground lg:grid">
                                <span>Name · Provider</span>
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
                                        className="animate-in fade-in group w-full cursor-pointer py-4 text-left transition-colors hover:bg-muted/30"
                                        style={{
                                            animationDelay: `${i * 40}ms`,
                                            animationDuration: '300ms',
                                            animationFillMode: 'both',
                                        }}
                                    >
                                        <div className="grid grid-cols-1 gap-2 lg:grid-cols-[minmax(0,1fr)_80px_100px_180px_60px] lg:items-center lg:gap-x-6">
                                            {/* Name + provider */}
                                            <div className="flex min-w-0 items-center gap-3">
                                                <StatusDot status={agent.status} />
                                                <div className="min-w-0">
                                                    <div className="flex min-w-0 items-center gap-2">
                                                        <span className="truncate font-mono text-sm font-medium group-hover:underline group-hover:underline-offset-2">
                                                            {agent.name}
                                                        </span>
                                                    </div>
                                                    <div className="truncate text-xs text-muted-foreground">
                                                        {agent.provider_name}
                                                    </div>
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

                                            <div className="text-right font-mono text-xs text-muted-foreground lg:block hidden">
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
                                    <div className="flex gap-4">
                                        {agents.links.map((link, i) => {
                                            const isPrev = i === 0;
                                            const isNext = i === agents.links.length - 1;
                                            if (!isPrev && !isNext) return null;
                                            if (!link.url) {
                                                return (
                                                    <span
                                                        key={i}
                                                        className="font-mono text-xs text-muted-foreground/40"
                                                    >
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
