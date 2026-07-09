# WebPulse Brand — conventions

This project ships **design tokens and styles only** (the source is a Vue/Nuxt app, so there is no React component bundle). Build every design with your own components, styled strictly with the vocabulary below. Content language is **Czech** — write UI copy in Czech unless asked otherwise.

## Two visual worlds

1. **Admin / CMS UI** — font `'Inter', sans-serif`, hex tokens on `:root`:
   `--wp-primary` (#4f46e5 indigo), `--wp-primary-light`, `--wp-primary-dark`, `--wp-secondary` (#0ea5e9), `--wp-accent` (#f59e0b), `--wp-surface` (#f8fafc), `--wp-text-primary` (#0f172a), `--wp-text-secondary` (#475569). Use as `color: var(--wp-primary)`.

2. **Client demo sites** — themed via CSS variables holding **space-separated RGB triplets**, consumed as `rgb(var(--brand) / <alpha>)`. **Wrap the whole design in a root element with class `demo-root`** and pick a theme with `data-demo`; without that wrapper every `rgb(var(--brand))` is invalid and text/fonts fall back to defaults.

```html
<div class="demo-root" data-demo="medical">
  <section class="section">
    <div class="container-x">
      <span style="background:rgb(var(--brand-soft));color:rgb(var(--brand-pop))">Štítek</span>
      <h2>Objednejte se online</h2>
      <p style="color:rgb(var(--brand-muted))">Popisný text…</p>
      <a style="background:rgb(var(--brand));color:#fff;border-radius:9999px">Objednat se</a>
    </div>
  </section>
</div>
```

## Brand variables (inside `.demo-root`)

`--brand` (primary), `--brand-dark` (dark sections/footers), `--brand-soft` (tinted backgrounds, badges), `--brand-cream` (off-white section background), `--brand-accent` (secondary accent), `--brand-ink` (headings/body text), `--brand-muted` (secondary text), `--brand-pop` (icons/links/badges — equals `--brand` except the dark `landscaping` theme where it is the yellow accent).

`data-demo` values and their look: *(default/lawn)* green Fustat · `remeslo` electric blue + amber, Fustat · `landscaping` dark green + yellow pop, Fustat · `restaurant` gold on charcoal, Bebas Neue headings + Schibsted Grotesk body · `lawyer` bronze on navy, Playfair Display headings + DM Sans body · `freelancer` crimson, Inter 800 · `spa` coral, Rufina headings + Jost body · `medical` turquoise + golden-yellow accent, Manrope 800.

Heading font/weight per theme is applied automatically by the stylesheet on `h1–h6` inside `.demo-root` (zero-specificity `:where()`, so your own utilities can override). Fixed green scale also exists: `--leaf-50 … --leaf-900`.

## Helper classes (defined in styles.css)

`.section` (100px vertical padding, 64px mobile) · `.container-x` (max-width 1320px, centered, 20px inline padding) · `.reveal` / `.reveal-left` / `.reveal-right` + `.is-visible` (scroll-reveal transitions) · `.marquee-mask` > `.marquee-track` (infinite logo strip) · `.sonar-ring` (expanding pulse ring, needs `position:relative` parent) · `.floaty` (gentle vertical float).

Buttons are pill-shaped (`border-radius:9999px`); cards use large radii (up to 2rem).

## Where the truth lives

Read before styling: `styles.css` (entry — helpers + imports), `tokens/colors.css` (every color variable and all 8 theme blocks), `tokens/typography.css` (font stacks per theme), `fonts/fonts.css` (self-hosted faces: Fustat, Inter, Manrope, Bebas Neue, Schibsted Grotesk, Playfair Display, DM Sans, Rufina, Jost — latin + latin-ext, so Czech diacritics render).
