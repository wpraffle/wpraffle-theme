# WPRaffle Theme v1.0.0 Release Notes

**Release date:** 8 July 2026
**Version:** 1.0.0
**Initial release**

> The first release of WPRaffle Theme — a premium WordPress theme purpose-built
> for the WPRaffles plugin. Ships a polished native WordPress baseline, a full
> Elementor Theme Builder template set, a Theme Options panel with 5 switchable
> colour presets, and built-in GitHub auto-updates.

---

## Headlines

- **A dedicated theme for WPRaffles** — every template, card and section is
  designed to render the plugin's raffle competitions, featured winners and
  charity data natively. No fighting with generic e-commerce themes.
- **One palette controls everything** — the Theme Options Style tab drives both
  the theme's `--diamond-*` CSS variables and the plugin's `--wpr-*` variables
  from a single source. The plugin's own styling output is suppressed so there's
  no competition between two colour panels.
- **5 one-click colour presets** — Diamond, Golf, Car, Retro, Elite — re-theme
  the whole site instantly, or pick custom colours for full control.
- **Identical cards everywhere** — raffle competition cards render identically
  on the homepage, the WooCommerce shop, and the raffles page. The theme
  force-enqueues the plugin's stylesheet wherever it embeds shortcodes via
  `do_shortcode()`, so styling is consistent regardless of context.
- **GitHub auto-updates out of the box** — a self-contained updater polls
  `wpraffle/wpraffle-theme` for new releases and injects them into WordPress's
  update system. No manual FTP required for future releases.
- **Full Elementor Theme Builder set** — 7 one-click JSON templates (header,
  footer, home, archive, single-raffle, single-charity, my-account) + 5
  reusable section blocks, compatible with PRO Elements (bundled) or
  Elementor Pro.

---

## What's included

### Theme Options panel (Appearance → Theme Options)

**Style tab**
- 5 colour presets: Diamond, Golf, Car, Retro, Elite.
- 8 colour pickers (accent, accent-2, dark, light, success, danger, warning,
  body text) with auto-derived darker/lighter/border shades.

**Content tab**
- Page-assignment dropdowns: Competitions / Winners / Charities pages.
- Hero copy (eyebrow, title, subtitle, background image), hero stats.
- All section headings (active competitions, winners, charity, trust).
- Top-bar message, footer blurb, social links.

**Advanced tab**
- Container width, card corner radius.
- Sticky header / full-width header / show-top-bar toggles.
- Updates section: installed version, latest available, "Check for updates"
  button, GitHub repo setting, auto-update toggle.

### Native templates
- Homepage (`front-page.php`): hero, featured winners carousel, active
  competitions grid, charity donations banner, trust block.
- Sticky header with top bar; 4-column dark footer.
- WooCommerce shop archive (3-col grid, float/width neutralised).
- Page templates: Winners, Charities, Full Width.
- Blog: index, archive, single, search, 404, comments, sidebar.

### WPRaffles integration (no plugin file edits)
- Palette ownership — theme drives both `--diamond-*` and `--wpr-*` vars.
- Asset forcing — plugin's `raffle-public` stylesheet enqueued on the homepage
  and template pages so `.rc-card` renders styled everywhere.
- Featured winners via `Raffle_Featured_Winners::get_featured()` (real photos,
  names, prize titles, testimonials).
- Charity total summed across all charities via
  `Raffle_Charity::calculate_total_raised_for_charity()`.
- `assets/css/wpraffle.css` styles the plugin's `.rc-card` BEM classes.
- Graceful fallback throughout when the plugin is inactive.

### Elementor Theme Builder
- 7 JSON templates + 5 reusable section blocks (PRO Elements / Elementor Pro).
- Theme locations registered (header, footer, archive, single).

### GitHub updater
- `inc/class-wpraffle-theme-updater.php` — polls releases/latest every 12h,
  theme-update transient injection, themes_api details modal, post-install
  folder rename, manual check button.

### Bundled dependencies
- **PRO Elements** 4.1.0 (`lib/proelements/`) — GPL fork of Elementor Pro.
- **TGMPA** 2.6.1 (`lib/tgmpa/`) — one-click WooCommerce/Elementor/PRO Elements
  install on activation.

---

## Design

Inspired by paragoncompetitions.co.uk: Montserrat typography, pill buttons,
soft shadows, Bootstrap-derived radii. Vendor stack: Bootstrap 5.3, Swiper 11,
Fancybox 6, Font Awesome 6.

Default palette (Diamond preset): accent `#e4678a` (pink), accent-2 `#5caeed`
(blue), success `#63dd92` (green), dark `#2c2c2c`, light `#f6f6f6`.

---

## Requirements

| Requirement | Status |
|---|---|
| WordPress ≥ 6.5 | Required |
| PHP ≥ 8.1 | Required |
| WooCommerce ≥ 8.0 | Required |
| WPRaffles plugin | Recommended |
| Elementor (free) | Optional |
| PRO Elements / Elementor Pro | Optional (bundled) |

---

## Installation

1. Upload `wpraffle-theme-1.0.0.zip` via **Appearance → Themes → Add New →
   Upload Theme**.
2. Activate **WPRaffle Theme**.
3. **Appearance → Menus** — assign Primary / Top Bar / Footer menus.
4. **Settings → Reading** — set a static Home page.
5. **Appearance → Theme Options** — pick a preset, set hero copy, assign pages.

---

## What's next

This is the initial release. Future releases will be delivered via the built-in
GitHub updater — bump the version in `style.css`, tag a matching GitHub release
(e.g. `v1.1.0`), and sites running 1.0.0 will see the update automatically.
