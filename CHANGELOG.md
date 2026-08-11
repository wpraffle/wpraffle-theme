# WPRaffle Theme — Changelog

All notable changes to WPRaffle Theme are documented here.
Format loosely follows [Keep a Changelog](https://keepachangelog.com/).

## [1.3.1] — 2026-08-11

### Added

- New **Default** style matching the WPRaffle main site: Inter typography,
  yellow/ink palette, generous spacing, pill buttons, rounded cards, dark
  header/footer and aligned raffle/WooCommerce presentation.
- A preset body class (`wpr-preset-{slug}`) and scoped Default stylesheet so
  Diamond, Golf, Car, Retro and Elite retain their existing presentation.

### Changed

- Fresh installs and installations with no saved preset now use Default.
  Explicitly saved legacy presets continue to resolve unchanged.
- Theme and asset version bumped to 1.3.1.

## [1.3.0] — 2026-08-05

### Added — Elementor improvements

- **Dynamic tags** for plugin data (group `🎁 WPRaffle Theme`): Raffle ID,
  Ticket Price, Draw Date (current product), and a global Charity Total Raised
  tag — so any native Elementor widget can bind to live values instead of
  hardcoding them. (`inc/class-wpraffle-theme-elementor-tags.php`)
- **`[wpraffle_charity_total]` shortcode** outputs the always-current charity
  total (sums active charities via the plugin). Used in the Elementor templates
  in place of the previously hardcoded `£2,800,000`. (`inc/class-wpraffle-theme-integration.php`)
- **7 homepage section blocks** for the Elementor library, covering every
  section the PHP homepage renders: `how-it-works`, `featured-spotlight`,
  `stats-counter`, `countdown`, `live-draw`, `testimonials`, `faq`.
  (`elementor/sections/`)
- **4 Theme Builder templates:** Cart, Checkout, 404, Search results — so they
  are visually editable. (`elementor/theme-builder/`)
- **Dedicated `assets/css/elementor.css`** enqueued only when Elementor is
  active, centralising canvas max-width, responsive column-stacking, and
  section padding for the `wpr-*` classes.

### Fixed

- **Testimonials headings disappeared** after the diamond→wpr rename: the
  template still read `diamond_testimonials_title/subtitle`. Now uses the
  `wpraffle_theme_mod()` fallback helper against the renamed keys.
  (`template-parts/testimonials.php`)
- **Single-raffle Elementor template rendered nothing:** the
  `[raffle id="{{current_product_id}}"]` placeholder was invalid Elementor
  syntax. Now uses `[raffle]`, which auto-resolves to the current product's
  raffle (plugin-side fix). (`elementor/theme-builder/single-raffle.json`)

### Changed

- **Hardcoded charity total removed** from `charity-donations.json` and
  `home.json` — both now use `[wpraffle_charity_total]`.
- **`elementor/README.md` refreshed:** renamed "Diamond" → "WPRaffle Theme",
  removed the dead `class-diamond-wpraffle.php` reference, documented the new
  sections, templates, and dynamic tags.

## [1.2.1] — 2026-08-05

### Security / Hardening

- **Update repository hard-coded.** The GitHub repo the theme updater polls is
  now a constant (`WPRaffle_Theme_Updater::REPO` = `wpraffle/wpraffle-theme`)
  and is no longer read from `wpraffle_theme_update_settings['github_repo']`.
  The editable "GitHub repository" field on Appearance → Theme Options →
  Advanced is replaced with a fixed label + link to
  https://github.com/wpraffle/wpraffle-theme, and the settings save handler
  ignores any posted `github_repo`. Theme update traffic can no longer be
  redirected to an arbitrary third-party repo.
  (`inc/class-wpraffle-theme-updater.php`,
  `inc/class-wpraffle-theme-settings.php`, `admin/views/settings-advanced.php`)

### Changed — "diamond" → "wpr" prefix rename (keep the Diamond colour preset)

The theme was originally branded "Diamond" and still used `diamond` as the
prefix for every technical identifier even after the rename to WPRaffle Theme.
The legacy prefix has now been retired in favour of `wpr` / `wpraffle-theme`.
**The user-facing "Diamond" colour preset (one of five: Diamond/Golf/Car/Retro/
Elite) is intentionally preserved** — only the internal prefix changed.

- **CSS custom properties:** every `--diamond-*` variable → `--wpr-*`
  (~55 variable names, ~367 usages across all CSS files).
- **CSS classes / selectors:** every `.diamond-*` class → `.wpr-*`
  (~95 selectors, ~415 usages, including markup and Elementor JSON).
- **Theme-mod / option field keys:** `diamond_*` → `wpr_*` (~20 keys).
- **Admin page slug:** `diamond-settings` → `wpraffle-theme-settings`
  (menu, redirects, admin-CSS body-class, updater).
- **Nonces / form identifiers:** `diamond_nonce`, `diamond_save_settings`,
  `diamond_preset`, `diamond_action`, `diamond_tab` → `wpr_*` equivalents.
- **Form namespace:** `$_POST['diamond'][...]` → `$_POST['wpr_settings'][...]`.
- **JS localised object:** `diamondData` → `wprThemeData` (3 JS files + inline).
- **Image-size handles:** `diamond-card`, `diamond-card-wide`, `diamond-hero`,
  `diamond-winner` → `wpr-*`.
- **`@package Diamond` docblocks** → `@package WPRaffle_Theme` (37 files).
- **TGM slug id** `'diamond'` → `'wpraffle-theme'`.
- **DB migration:** `wpraffle_theme_migrate_settings()` now renames every
  `diamond_*` theme_mod key → `wpr_*` inside the `theme_mods` row (one-time,
  flag-gated by `wpraffle_theme_migrated_diamond_mods_v1`).
- **Read fallback:** new `wpraffle_theme_mod()` helper falls back to the old
  `diamond_*` key for one release, so a saved value can never be lost even if
  the migration is skipped.

> ⚠️ **Child themes / custom CSS:** any custom CSS or child-theme templates
> referencing `.diamond-*` classes or `--diamond-*` variables will need updating
> to `.wpr-*` / `--wpr-*`.

### Changed — Redundancy cleanup

- Removed empty deprecated `create_plugin_pages()` and the no-op
  `enqueue_google_fonts()` method + its hook. (`inc/class-wpraffle-theme-setup.php`)
- Removed dead first-pass font-URL computation in `load_dynamic_fonts()`.
  (`inc/class-wpraffle-theme-settings.php`)
- Removed the unused `DEFAULT_REPO` constant. (`inc/class-wpraffle-theme-updater.php`)
- Deleted a byte-identical duplicate CSS block in `assets/css/wpraffle.css`
  (the `.raffle-image-card` / `.raffle-title-main` / `.raffle-price-value` /
  `.raffle-progress-bar-*` rules were defined twice).
- Deleted the orphan `template-parts/share-buttons.php` (never loaded by any
  `get_template_part()` call).

## [1.0.0] — 2026-07-08

### Added — Initial release

**Theme & design**
- Native WordPress theme purpose-built for the WPRaffles plugin.
- Design inspired by paragoncompetitions.co.uk: Montserrat typography, pill
  buttons, soft shadows, Bootstrap-derived radii. Vendor stack: Bootstrap 5.3,
  Swiper 11, Fancybox 6, Font Awesome 6.
- Full native template set: `front-page.php` (hero, featured winners, active
  competitions, charity donations, trust block), sticky header with top bar,
  4-column dark footer, blog (index/archive/single/search), 404, page,
  full-width page, comments, sidebar, custom search form.

**Theme Options panel** (Appearance → Theme Options)
- **Style tab:** 5 one-click colour presets (Diamond, Golf, Car, Retro, Elite)
  + 8 colour pickers. Drives both the theme's `--diamond-*` vars and the
  plugin's `--wpr-*` vars from one source.
- **Content tab:** page-assignment dropdowns (Competitions / Winners /
  Charities), hero copy + background, hero stats, all section headings, top-bar
  message, footer blurb, social links.
- **Advanced tab:** container width, card radius, sticky header / full-width
  header / top-bar toggles, updates section with "Check for updates now".

**WooCommerce integration**
- `archive-product.php` 3-col "Active Competitions" grid.
- WooCommerce wrapper swap, menu reordering, mini-cart fragment.
- Cart, checkout, My Account styling. Plugin's **My Raffles** tab renders
  natively (WC's default account template preserved).

**WPRaffles plugin integration** (documented surfaces only, no plugin edits)
- Palette ownership: theme drives the plugin's `--wpr-*` CSS variables; plugin's
  own inline styling suppressed so there's a single source of truth.
- Force-enqueues the plugin's `raffle-public` stylesheet on the homepage and
  template pages so raffle cards render identically everywhere.
- Featured winners via `Raffle_Featured_Winners::get_featured()` (real photos,
  names, prize titles).
- Charity total summed across all charities via
  `Raffle_Charity::calculate_total_raised_for_charity()`.
- `assets/css/wpraffle.css` styles the plugin's `.rc-card` BEM classes to the
  theme aesthetic.
- Graceful fallback throughout when the plugin is inactive.

**Elementor Theme Builder**
- Full JSON template set (header, footer, home, archive, single-raffle,
  single-charity, my-account) + 5 reusable section blocks.
- Pro Elements / Elementor Pro compatible (registers theme locations).

**Page templates**
- `page-winners.php` (renders `[raffle_ended_list]`).
- `page-charities.php` (renders total raised + `[raffle_charities]`).
- `page-full-width.php`.

**GitHub auto-updater**
- `inc/class-wpraffle-theme-updater.php`: polls
  `wpraffle/wpraffle-theme/releases/latest` every 12h (twicedaily cron),
  injects into WP's theme-update transient, themes_api details modal,
  post-install folder rename. Manual check button + repo/auto-update settings.

**Bundled dependencies**
- **PRO Elements** 4.1.0 (`lib/proelements/`) — GPL fork of Elementor Pro.
- **TGMPA** 2.6.1 (`lib/tgmpa/`) — one-click WooCommerce/Elementor/PRO Elements install.

**Other**
- `theme.json` palette/typography, custom image sizes, 5 menu locations,
  3 footer widget areas.
- Settings migration: copies the old `diamond_style_settings` option to
  `wpraffle_theme_settings` on first load.
