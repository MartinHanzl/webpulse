# design-sync notes

- Repo is a Vue/Nuxt app — Claude Design consumes React only, so this sync is **tokens + styles only** (user-approved scope, 2026-07-07). No `_ds_bundle.js`, no component `.d.ts`/`.prompt.md`.
- `_ds_sync.json` intentionally omitted: layout is hand-authored (off-script), no component anchor recipe applies. Next sync rebuilds and re-uploads everything — bundle is small (~600 KB), that's fine.
- Bundle source of truth: `app/assets/css/theme.css` (demo brand variables + helpers) and `tailwind.config.js` (admin palette, leaf scale). Re-sync = re-derive `ds-bundle/tokens/*` from those two files.
- Fonts self-hosted in `ds-bundle/fonts/` — downloaded from Google Fonts (latin + latin-ext for Czech diacritics), 9 families: Fustat, Inter, Manrope, Bebas Neue, Schibsted Grotesk, Playfair Display, DM Sans, Rufina, Jost. Italic Playfair variants not included (lawyer demo uses them only marginally).
- Preview cards verified with project's Playwright (`node_modules/@playwright/test`), screenshots checked visually — all 8 demo themes recolor, Czech pangram renders in every family.
- `ds-bundle/` is generated output — do not commit (add to .gitignore if it bothers).
