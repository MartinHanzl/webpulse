# Vlastní WYSIWYG editor — analýza a plán

> Vytvořeno: 2026-06-28. Účel: rozhodnutí o náhradě WYSIWYG editoru v adminu vlastním řešením.

## Stav teď

- **CKEditor 5 Premium se nepoužívá vůbec** — 0 referencí v kódu adminu. Jen mrtvá závislost v `admin/package.json` (`@ckeditor/ckeditor5-build-classic`, `@ckeditor/ckeditor5-vue`, `ckeditor5`, `ckeditor5-premium-features`). → žádný reálný licenční náklad, lze rovnou smazat.
- **Reálný editor = Quill** (`@vueup/vue-quill`), licence MIT (zdarma).
- Centralizovaný v jediné komponentě: `admin/app/components/Base/Form/Editor.vue`.
- Použitý na ~27 stránkách (obsah/*, ubytovani/*, restaurace/*, nastaveni/*, smlouvy).
- **Už existuje rozjetý ProseMirror scaffold**: `admin/app/plugins/wysiwyg.client.ts` (`createWysiwygEditor`) — definovaný, ale nikde se nevolá → mrtvý kód.

### Klíčová výhoda architektury

Editor je v 1 komponentě. Vlastní editor = přepsat vnitřek `Editor.vue`, zachovat props/emits:
- props: `modelValue` (string, html), `label`, `name`, `rules`, `maxlength`
- emit: `update:modelValue`
- výstup: HTML (`content-type="html"`)

Všech 27 stránek se nedotkneš.

## Dnešní feature set (Quill toolbar)

Parita musí pokrýt:
- nadpisy H1–H3
- bold, italic, underline, strike
- seznamy ordered / bullet
- code-block, blockquote
- color, background
- link, image, video

## Cesty

| Cesta | Vlastní? | Práce | Pozn. |
|---|---|---|---|
| **Tiptap** (headless ProseMirror, Vue 3) | Ano — vlastní toolbar/UI/output, jen nereinventuje contenteditable engine | ~2–4 dny na paritu | **Doporučeno** |
| **Raw ProseMirror** (dokončit existující plugin) | Plně vlastní | ~1–2 týdny | Víc plumbingu: toolbar, link/image/video nodes, color marks, HTML serializace, paste handling |
| **Od nuly na `contenteditable`** | 100 % | měsíce | Nedoporučeno — selection/paste/cross-browser peklo, nulový přínos |

## Doporučení: Tiptap

- ProseMirror pod kapotou (stejná rodina jako existující scaffold), ale headless → toolbar i vzhled vlastní = „náš editor".
- Licence MIT, žádný vendor lock, čistý HTML výstup pod kontrolou.

### Bonus oproti Quillu

Napojit `image` na **Filemanager** (per-site presety v tabulce `filemanagers`) místo base64 embedu. Tiptap má na to čistý custom node.

## Rozhodnutí k udělání

1. **Knihovna**: Tiptap vs. raw ProseMirror
2. **Rozsah**: jen text formátování (parita) vs. i image upload přes Filemanager

## Navržené kroky implementace

1. POC: přepsat `Base/Form/Editor.vue` na Tiptap s parity toolbarem, otestovat na 1 stránce (např. `obsah/stranky/[id]`).
2. Doplnit zbývající nástroje (color/background, link, video, code-block, blockquote).
3. Custom image node → upload do Filemanageru.
4. Ověřit na všech 27 stránkách (HTML in/out kompatibilita se starým obsahem z Quillu).
5. Smazat mrtvé deps: CKEditor balíčky + nepoužitý ProseMirror plugin (pokud nahrazen Tiptapem).

## Kontext: ocenění projektu (související)

Náhrada editoru vlastním řešením odstraní vnímanou závislost na placeném CKEditoru → drobně zvyšuje prodejní hodnotu codebase. (Plný odhad ceny WebPulse řešen samostatně.)
