# WPRaffle Theme — Changelog

All notable changes to WPRaffle Theme are documented here.
Format loosely follows [Keep a Changelog](https://keepachangelog.com/).

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
