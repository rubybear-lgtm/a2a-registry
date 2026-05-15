## Design Context

### Users
Mixed audience — **developers/builders** publishing and integrating AI agents, and **enterprises/ops teams** evaluating agents for deployment. Both audiences are technically literate and value information density over decoration. They need to scan, compare, and assess agents quickly. Used in terminal-adjacent contexts: multiple monitors, dark environments, side-by-side with code editors and API docs.

### Brand Personality
**Precise, authoritative, forward-looking.** The registry is infrastructure for the agentic web — it should feel like it was built by engineers for engineers. Not a startup. Not marketed. Not designed to delight. Designed to be trusted.

Think: a decommissioned NASA mission patch, a technical standards document from the 1980s that happens to be about the future, a well-worn engineering terminal.

Three words: **precise · authoritative · cold**

Emotional goal: Users should feel they're looking at the definitive source of truth for AI agents — something that existed before them and will exist after.

### Aesthetic Direction

**Theme: Dark primary.** Developers use this side-by-side with terminals. Dark is the right context, not a style choice. Background `oklch(0.13 0.008 250)` — cold, slightly blue-tinted near-black. Not pure black, not "cool dark startup."

**Typography:**
- Display headings: **Big Shoulders Display** (weight 700–800). Used in stadium scoreboards, airport departure boards, mission signage. At large scale it reads as infrastructure, not interface. Agent names feel like system designations.
- Body: **Figtree** — clean geometric, reads well at tight sizes, not overused.
- Both available on Google Fonts (`https://fonts.bunny.net`). Neither is on the banned list.

**Colors — cold-tinted monochrome:**
- Background: `oklch(0.13 0.008 250)` — cold near-black
- Foreground: `oklch(0.93 0.006 250)` — cold near-white
- Muted surface: `oklch(0.19 0.007 250)` — elevated surface
- Border: `oklch(0.24 0.007 250)` — precise, thin
- Secondary text: `oklch(0.52 0.008 250)` — not gray, tinted
- Accent (use at most once per view): `oklch(0.72 0.06 250)` — muted technical blue-violet

**Typography rules for this project:**
- Agent names on detail pages: Big Shoulders Display at 56–72px. Commanding. Like a mission designation.
- Section labels: 10px, tracked wide, Figtree medium. No all-caps body text.
- Version numbers, protocol bindings, URLs: system monospace. These are data, not decoration.
- Kill the opacity cascade. No `opacity-40`, `opacity-70` everywhere — use the actual semantic color tokens.

**What this is NOT:**
- Not Linear. Not Railway. Not npm. Not Vercel. Not any startup dashboard.
- Not "dark mode with glowing accents."
- No animate-ping dots on every row. Status indicators once, used deliberately.
- No `border-left` accent stripes (banned).
- No gradient text (banned).
- No backdrop-blur nav that looks like every SaaS product made in 2023.

### Design Principles

1. **Infrastructure, not product** — No marketing language in the UI. No "the definitive registry." The data speaks. Headers are labels, not pitches.
2. **Agent names as system designations** — On detail pages the name is the hero. Large, condensed, commanding. It reads like a certified component in a system diagram.
3. **Dark by conviction** — Dark is correct here, not decorative. Surfaces are cold and controlled. No glows, no neon, no "look how dark" effects.
4. **Hierarchy through scale and weight** — Achieved by size contrast and weight, not by color. A 5-step scale with at least 1.3× between steps. Secondary information in secondary size, not just secondary opacity.
5. **Restraint is the statement** — Fewer colors, zero decoration, zero ornamental elements. Every pixel is load-bearing. The absence of decoration communicates precision.
