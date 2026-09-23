# WPRaffle Theme — Changelog

All notable changes to WPRaffle Theme are documented here.
Format loosely follows [Keep a Changelog](https://keepachangelog.com/).


## [1.4.0] — 2026-09-23

### Release hardening
- Added a plugin-owned frontend context and asset API, extended by the theme for native templates.
- Fixed missing WPRaffle card, preset, header and navigation styling on the Competitions template.
- Covered competitions, winners, charities, draw results, instant wins, WooCommerce shop/product taxonomies, raffle products, account endpoints and shortcode content.
- Made the theme integration stylesheet resilient when the plugin stylesheet handle is unavailable.
- Removed an accidental temporary JavaScript file and refreshed release metadata.
- Unified every native/default page template on one preset-aware hero, content, spacing and empty-state system.
- Standardised all selectable template labels to the `WPRaffle — Page Name` convention while retaining existing filenames and page assignments.
- Fixed mobile homepage/header horizontal overflow by reducing phone actions to the essential account and cart controls.
- Removed fictional public winner placeholders; the homepage Winners section now appears only with featured winner data.
- Fixed Trustpilot-only testimonial visibility, dead Winners-section hero links and duplicate FAQ click handlers.
- Made shortcode/block-driven Raffles pages use the same full-width competition
  section and grid contract as the native Competitions template.
- Added a dedicated wide My Account content container plus polished navigation,
  nested WPRaffle tabs, forms, addresses, notices, tables and mobile layouts.
- Changed the header account action to a solid icon matching the cart control.
- Added content-aware local asset versions so browser/CDN caches cannot retain
  stale 1.4.0 CSS or JavaScript after a release-hardening update.

### Premium product completion
- Added plugin-backed operational health/readiness checks for raffle tables, competition content and WooCommerce core pages.
- Added downloadable support/system reports from Theme Options.
- Added a one-click child-theme ZIP generator for update-safe customisations.
- Added native About and Instant Wins page templates and included them in recommended-page setup.
- Expanded Theme Options search with common setting aliases such as mobile menu, competition cards, child theme and diagnostics.
- Added a direct recommended-plugin management shortcut to the Control Centre.

- Coordinated WPRaffle plugin 1.4.0 integration with a stable capability/context API.
- Added one-click idempotent Elementor template/library importing from Theme Options.
- Consolidated raffle-specific Elementor dynamic data ownership into the plugin.
- Theme dashboard now reports the active WPRaffle integration version and widget capability count.
### Initial 1.4.0 development — 2026-08-17

### Added

- New **WPRaffle Control Centre** as the default Theme Options landing page with site-readiness checks, quick actions and current-style preview.
- Guided **Site Setup** workflow with safe one-click creation of recommended pages and primary navigation. Existing pages are detected and never overwritten.
- **Starter Sites** screen for Default, Golf, Car, Retro, Diamond and Elite, applying each real preset plus recommended layout/card defaults without importing fake competition data.
- **Template Library** surfacing all native WordPress page templates and bundled Elementor Theme Builder/section templates.
- New native templates for How It Works, FAQ, Draw Results, Contact and Legal/Policy pages.
- **Tools** area with JSON settings export/import for staging, backup and cloning.
- **System Status** screen for WordPress, PHP, WooCommerce, WPRaffle, Elementor, HTTPS, memory and permalink health.
- First-activation welcome notice linking directly to the Control Centre.
- Theme Options navigation search for quickly locating settings.

### Changed

- Theme Options navigation reorganised into Control Centre, Design, Pages, Content, Marketing and Developer groups.
- Renamed the user-facing `Enhancements (v1.2)` tab to **Features**.
- Theme version bumped to **1.4.0**.

### Responsive Admin
- Optimised admin/settings screens for desktop, tablet and mobile.
- Added touch-friendly controls, responsive grids, scrollable admin navigation, stacked mobile forms and safe table overflow.

### Unified Admin Experience
- Plugin Settings now uses the same WPRaffle-branded Control Centre design language as Theme Options.
- Desktop uses a persistent sidebar; tablet/mobile uses a hamburger dropdown that displays the current section name.
- Removed horizontal settings tabs on smaller screens and standardised cards, forms, navigation and touch targets across plugin and theme.

### Hero CTA controls
- Added configurable primary and secondary homepage hero buttons, including text, destination, custom page/URL, visibility and new-tab options.

## [1.3.5] — 2026-08-17

### Changed
- Added refreshed **Diamond** styling with a premium black/gold competition presentation.
- Added refreshed **Elite** styling with an obsidian/platinum/champagne high-end presentation.
- Includes the **Golf** refresh from 1.3.2, **Car** refresh from 1.3.3 and **Retro** refresh from 1.3.4.
- Added shared header fixes for Search and Notifications icon colours.
- Added full-viewport mobile navigation with `100dvh`, safe-area padding and independent scrolling.

### Compatibility
- Default remains the fresh-install default style.
- Existing Diamond, Golf, Retro, Car and Elite preset selections remain available.
- Visual refresh styles are isolated to their matching preset body classes.

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

### Day / Night design system
- Rebuilt the frontend day/night mode with preset-specific palettes for Default, Golf, Car, Retro, Diamond and Elite.
- Replaced the generic moon icon with a branded accessible day/night switch.
- Applied mode colours before first paint to prevent light/dark flashing.
- Extended night mode across WPRaffle cards, native pages, WooCommerce, forms, FAQs, How It Works, notices and supporting UI.

### Release-candidate hardening
- Fixed Homepage Hero CTA settings persistence, including selected page IDs, custom URLs, enabled states and new-tab options.
- Renamed legacy Diamond-era settings/preset nonce fields to WPRaffle-scoped names.
- Added a late-loaded preset integration layer for all native 1.4.0 page templates so Default, Golf, Car, Retro, Diamond and Elite are visually inherited consistently.
- Hardened settings JSON imports with capability/nonce protection, a 1 MB size limit, JSON extension checks, known-key filtering and field-level sanitisation.
- Sanitised imported theme mods by expected type.

### Native-template consistency fixes
- Removed literal escaped whitespace accidentally rendered before the viewport meta tag and in the admin dashboard.
- Added an explicit native-template body marker so header, logo, navigation, scrolled header and mobile drawer always inherit the active preset.
- Normalised the native Competitions page to the same `.rc-card` responsive grid contract used by raffle/shop loops.
- Added a full rendered-template scan for line-leading escaped control characters.
