# WPRaffle Theme v1.3.0 Release Notes

**Release date:** 5 August 2026
**Version:** 1.3.0
**Previous version:** 1.2.1

> A cleanup + Elementor release that pairs with **WPRaffle Plugin v1.3.1**.
> Retires the legacy `diamond` prefix across ~1,100 references, hard-codes the
> update repository, removes redundant code, fixes two latent bugs, and ships a
> substantial Elementor expansion: dynamic tags for plugin data, 7 new homepage
> section blocks, 4 new Theme Builder templates, and a dedicated Elementor
> stylesheet.

---

## Headlines

- **Legacy `diamond` prefix retired.** Every technical identifier that still
  used the old "Diamond" brand has been renamed to the `wpr` / `wpraffle-theme`
  prefix — CSS variables (`--diamond-*` → `--wpr-*`), classes, theme-mod keys,
  the admin page slug, the `diamondData` JS object, image-size handles, and 37
  `@package Diamond` docblocks. **The user-facing "Diamond" colour preset is
  intentionally preserved.** A one-time DB migration + a read fallback ensure
  no saved value is ever lost.
- **Elementor dynamic tags** for plugin data — any native Elementor widget can
  now bind to the live raffle id, ticket price, draw date, or the global charity
  total, instead of hardcoding values.
- **Elementor library expanded** — 7 new homepage section blocks (covering
  every section the PHP homepage renders) + 4 new Theme Builder templates
  (Cart, Checkout, 404, Search).
- **Update repository hard-coded** to `wpraffle/wpraffle-theme` — no longer
  editable from the UI.
- **Two latent bugs fixed:** testimonial headings that disappeared after the
  rename, and the single-raffle Elementor template that rendered nothing.

---

## Fixed

- **Testimonial headings disappeared** after the diamond→wpr rename: the
  template still read `diamond_testimonials_title/subtitle` (empty after
  migration). Now uses the `wpraffle_theme_mod()` fallback helper against the
  renamed `wpr_*` keys.
- **Single-raffle Elementor template rendered nothing.** The
  `[raffle id="{{current_product_id}}"]` placeholder was invalid Elementor
  syntax. Now uses `[raffle]`, which auto-resolves to the current product's
  raffle (paired with a plugin-side fix in v1.3.1).

---

## Security / Hardening

- **Update repository hard-coded** (`WPRaffle_Theme_Updater::REPO`). The
  editable "GitHub repository" field on Appearance → Theme Options → Advanced
  is replaced with a fixed label + link to
  https://github.com/wpraffle/wpraffle-theme, and the settings save handler
  ignores any posted `github_repo`.

---

## Added — Elementor

- **Dynamic tags** (group `🎁 WPRaffle Theme`):
  - **Raffle ID** (current product)
  - **Ticket Price** (current raffle)
  - **Draw Date** (current raffle)
  - **Charity Total Raised** (global, summed across active charities)
- **`[wpraffle_charity_total]` shortcode** — outputs the always-current
  charity total; used in the Elementor templates in place of the previously
  hardcoded `£2,800,000`.
- **7 homepage section blocks** for the Elementor library, covering every
  section the PHP homepage renders: `how-it-works`, `featured-spotlight`,
  `stats-counter`, `countdown`, `live-draw`, `testimonials`, `faq`.
- **4 Theme Builder templates:** Cart, Checkout, 404, Search results.
- **Dedicated `assets/css/elementor.css`** enqueued only when Elementor is
  active — centralises canvas max-width, responsive column-stacking, and
  section padding for the `wpr-*` classes.

---

## Changed — `diamond` → `wpr` prefix rename

The legacy prefix is retired everywhere except the intentional colour-preset
name. (~1,100 references across ~60 files.)

- **CSS custom properties:** `--diamond-*` → `--wpr-*` (~55 names, ~367 uses).
- **CSS classes / selectors:** `.diamond-*` → `.wpr-*` (~95 selectors).
- **Theme-mod keys:** `diamond_*` → `wpr_*` (~20 keys), with a one-time
  `theme_mods` migration (flag-gated) + a `wpraffle_theme_mod()` read fallback.
- **Admin page slug:** `diamond-settings` → `wpraffle-theme-settings`.
- **Nonces / form identifiers:** `diamond_nonce`, `diamond_save_settings`,
  `diamond_preset`, `diamond_action`, `diamond_tab` → `wpr_*`.
- **Form namespace:** `$_POST['diamond']` → `$_POST['wpr_settings']`.
- **JS localised object:** `diamondData` → `wprThemeData`.
- **Image-size handles:** `diamond-card`, `-card-wide`, `-hero`, `-winner` →
  `wpr-*`.
- **TGM slug id:** `'diamond'` → `'wpraffle-theme'`.
- **Docblocks:** `@package Diamond` → `@package WPRaffle_Theme` (37 files).
- **Elementor template titles:** "Diamond Home" → "WPRaffle Theme Home", etc.

> ⚠️ **Child themes / custom CSS** referencing `.diamond-*` classes or
> `--diamond-*` variables must be updated to `.wpr-*` / `--wpr-*`.

---

## Changed — Redundancy cleanup

- Removed empty deprecated `create_plugin_pages()` and the no-op
  `enqueue_google_fonts()` + its hook.
- Removed dead first-pass font-URL computation in `load_dynamic_fonts()`.
- Removed the unused `DEFAULT_REPO` constant.
- Deleted a byte-identical duplicate CSS block in `assets/css/wpraffle.css`.
- Deleted the orphan `template-parts/share-buttons.php` (never loaded).
- Refreshed `elementor/README.md` (renamed "Diamond", removed a dead file
  reference, documented the new sections/templates/tags).

---

## Upgrade notes

- **No breaking changes for end users.** The diamond→wpr rename is internal;
  saved Theme Options values are migrated automatically and protected by a
  read fallback.
- **Child themes / custom CSS:** update any `.diamond-*` / `--diamond-*`
  references to `.wpr-*` / `--wpr-*`.
- The update repo is now fixed; any previously-stored custom repo is ignored.
- Dynamic tags + the new Theme Builder templates require PRO Elements (bundled)
  or Elementor Pro.

---

## What's next

- Custom Elementor widgets for the homepage sections (replacing the
  shortcode-in-a-widget pattern with purpose-built controls).
- A "Single Raffle" Theme Builder condition so a full single-raffle template
  can be assigned visually.
- Live-draw embed section block.
