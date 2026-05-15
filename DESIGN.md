---
name: A2A Registry
description: Dual-state infrastructure for the agentic web.
colors:
  background: "oklch(0.98 0.005 90)"
  foreground: "oklch(0.13 0.008 250)"
  surface: "oklch(0.99 0.005 90)"
  border: "oklch(0.85 0.01 90)"
  secondary-text: "oklch(0.52 0.008 250)"
  accent: "oklch(0.72 0.06 250)"
  destructive: "oklch(0.55 0.18 25)"
typography:
  display:
    fontFamily: '"Big Shoulders Display", sans-serif'
    fontSize: "clamp(3.25rem, 7vw, 5.5rem)"
    fontWeight: 700
    lineHeight: 1
    letterSpacing: "tight"
  body:
    fontFamily: "Figtree, sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.5
  label:
    fontFamily: "Figtree, sans-serif"
    fontSize: "10px"
    fontWeight: 500
    letterSpacing: "0.18em"
  mono:
    fontFamily: "ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace"
    fontSize: "12px"
rounded:
  sm: "4px"
  md: "6px"
  lg: "8px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
components:
  button-primary:
    backgroundColor: "{colors.foreground}"
    textColor: "{colors.background}"
    rounded: "{rounded.sm}"
    padding: "8px 16px"
  badge:
    backgroundColor: "transparent"
    textColor: "{colors.secondary-text}"
    rounded: "{rounded.sm}"
    padding: "2px 6px"
---

# Design System: A2A Registry

## 1. Overview

**Creative North Star: "The Decommissioned Mission Patch"**

The A2A Registry is designed as infrastructure for the agentic web. It rejects the polished, glowing aesthetics of modern SaaS tools (Linear, Railway, Vercel) in favor of a precise, authoritative engineering atmosphere. It should feel like a technical standards document or a decommissioned NASA mission control terminal — something built by engineers for engineers, where information density and scanability are prioritized over decoration.

The system exists in two states: **The Technical Archive** (Light) and **The Cold Terminal** (Dark). Both prioritize clarity and engineered precision over stylistic trends. "Agents for everyone" is the goal, served through an interface that feels like a permanent record.

**Key Characteristics:**
- **Dual-State Infrastructure:** Functional duality between an archival paper aesthetic and a technical terminal.
- **Infrastructure, not product:** No marketing language; the data speaks for itself.
- **Commanding Typography:** Agent names are heroes, rendered as system designations.
- **High Restraint:** Zero ornamental elements; every pixel is load-bearing.

## 2. Colors

The system uses two distinct palettes tied together by a consistent technical accent.

### Primary
- **Light Solarized NASA** (oklch(0.98 0.005 90)): The archival "paper" baseline.
- **Dark Solarized NASA** (oklch(0.13 0.008 250)): The deep "ink" or "terminal" baseline.

### Secondary
- **Solarized Violet** (oklch(0.50 0.12 285)): A muted technical accent used for active states, focus points, and authentication markers.

### Functional Accents (Solarized)
- **Solarized Green** (oklch(0.60 0.12 145)): Status indicators (Active), system health.
- **Solarized Cyan** (oklch(0.60 0.12 200)): Streaming capabilities, data flow.
- **Solarized Orange** (oklch(0.55 0.15 45)): Push notifications, alerts.
- **Solarized Blue** (oklch(0.55 0.12 250)): Primary links, documentation markers.

### Neutral
- **Tonal Surfaces:** Tonal shifts in OKLCH lightness (elevated in dark, dimmed in light) for cards and popovers.
- **System Borders:** Precise, thin lines for defining structure (oklch(0.85 0.01 90) in light, oklch(0.245 0.007 250) in dark).

### Named Rules
**The Restraint Rule.** The primary accent is used on ≤10% of any given screen. Its rarity communicates importance.
**The Functional Color Rule.** Use Solarized functional accents strictly for encoding meaning (capabilities, status). Never for pure decoration.
**The No-Glow Rule.** No neon, glows, or "look how dark" effects. Surfaces are flat and controlled.

## 3. Typography

**Display Font:** Big Shoulders Display (weight 700-800)
**Body Font:** Figtree (geometric sans)
**Label/Mono Font:** System Monospace

**Character:** A pairing of scoreboard-style infrastructure headings with clean, modern geometric body text.

### Hierarchy
- **Display** (700-800, 56-72px, 1): Agent names on detail pages. commanding mission designations.
- **Headline** (700, clamp(3.25rem, 7vw, 5.5rem), 1): Main section headers.
- **Title** (600, 18-24px, 1.2): Component titles and navigation.
- **Body** (400, 16px, 1.5): Descriptive text and descriptions. Max line length 65–75ch.
- **Label** (500, 10px, 0.18em, uppercase): Section markers and metadata headers.

### Named Rules
**The Designation Rule.** Agent names are never just "titles"; they are system designations. Use Big Shoulders Display at large scale to enforce this.

## 4. Elevation

The system uses tonal layering rather than shadows to convey depth. Separation is achieved through subtle shifts in OKLCH lightness and precise borders.

### Shadow Vocabulary
- **No Shadow Default:** Surfaces are strictly flat.
- **Ambient Feedback:** Very subtle tonal shifts may occur on hover, but never traditional dropshadows.

### Named Rules
**The Tonal Layering Rule.** Depth is communicated by tonal shifts (lightness), not shadows.

## 5. Components

Components are refined and restrained, feeling like certified parts in a larger system.

### Buttons
- **Shape:** Sharp corners or very small radius (4px).
- **Primary:** High-contrast (Foreground on Background), 8px 16px padding.
- **Secondary:** Tonal (Surface on Background) or bordered.

### Badges / Tags
- **Style:** Bordered (1px) with transparent backgrounds.
- **Typography:** Mono or small Label style.

### Cards / Containers
- **Corner Style:** Small radius (6px).
- **Background:** Tonal Surface.
- **Border:** System Border.

### Navigation
- **Style:** Minimalist top bar.
- **Typography:** Figtree medium, 14px.

## 6. Do's and Don'ts

### Do:
- **Do** use OKLCH for all color definitions to maintain precise tints.
- **Do** use Big Shoulders Display for agent names and hero headings only.
- **Do** prioritize information density. Let the data speak.
- **Do** use system monospace for technical data (versions, IDs).

### Don't:
- **Don't** use backdrop-blur nav that looks like every SaaS product.
- **Don't** use `border-left` accent stripes (prohibited).
- **Don't** use gradient text.
- **Don't** use "dark mode with glowing accents" or neon.
- **Don't** use marketing language ("the definitive registry").
