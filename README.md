# WPRaffle Theme

A premium WordPress theme purpose-built for the **WPRaffles** plugin. WPRaffle
Theme pairs a polished native WordPress baseline with a full **Elementor Theme
Builder** template set (PRO Elements compatible), plus a built-in options panel
and GitHub-hosted auto-updates.

> **Design reference:** 
> accent colours on dark/light surfaces, Montserrat typography, pill buttons,
> soft shadows. Ships **5 switchable colour presets** (Diamond, Golf, Car,
> Retro, Elite) configurable from **Appearance → Theme Options**.

---

## Requirements

| Requirement | Status | Notes |
|---|---|---|
| **WordPress** ≥ 6.5 | Required | |
| **PHP** ≥ 8.1 | Required | |
| **WooCommerce** ≥ 8.0 | Required | Raffles are WooCommerce products. The theme will install it for you via TGMPA. |
| **WPRaffles** plugin | Recommended | Theme works without it; sections light up extra content (winners, charities, raffle cards) when active. |
| **Elementor** (free) | Optional | Required only to edit the Theme Builder templates. |
| **PRO Elements** *or* Elementor Pro | Optional | Required only for Theme Builder (header/footer/archive/single). **Bundled** in `lib/proelements/`. |

---

## Installation

### Option A — Sync from the workspace (local dev)

The theme lives at `wpraffle-themes/wpraffle-theme/`. To deploy to the local
**raffles** test site:

```bash
rsync -av --delete --exclude '.DS_Store' \
  "/Users/liamkenyon/Documents/WPRAFFLES MAIN/wpraffle-themes/wpraffle-theme/" \
  "/Users/liamkenyon/Local Sites/raffles/app/public/wp-content/themes/wpraffle-theme/"
```

Then activate under **Appearance → Themes**.

### Option B — Zip upload

1. Zip the `wpraffle-theme/` folder.
2. **Appearance → Themes → Add New → Upload Theme** → choose the zip.
3. Activate.

### After activating

- **Appearance → Menus:** create menus and assign to **Primary**, **Top Bar**,
  and **Footer** locations.
- **Settings → Reading:** set a static **Home** page (the `front-page.php`
  template renders automatically) or import `elementor/theme-builder/home.json`.
- **Appearance → Theme Options:** pick a colour preset, set the homepage copy,
  and assign the Competitions / Winners / Charities pages.
- The bundled TGMPA notice will prompt you to install **WooCommerce** and
  recommend **Elementor** + **PRO Elements** if not already active.

---

## Appearance → Theme Options

The theme's control panel lives under **Appearance → Theme Options** with three
tabs:

### Style
- **Colour presets:** Diamond, Golf, Car, Retro, Elite — one click re-themes
  the whole site (both the theme's `--diamond-*` vars and the plugin's
  `--wpr-*` vars). Edit any colour to switch to a custom palette.
- **8 colour pickers:** accent, accent-2, dark, light, success, danger,
  warning, body text. Derived shades (darker, lighter, borders) are generated
  automatically.

### Content
- **Page assignments:** pick which pages the homepage "View all" buttons link
  to (Competitions / Winners / Charities). Leave on "Auto-detect" to fall back
  to the plugin's settings or a shortcode search.
- **Hero copy:** eyebrow, title, subtitle, background image, hero stats.
- **Section headings:** competitions, winners, charity, trust block.
- **Top bar** message, **footer** blurb, and **social links**.

### Advanced
- Container width, card corner radius.
- Sticky header / full-width header / show-top-bar toggles.
- **Updates:** installed version, latest available, "Check for updates now"
  button, GitHub repo setting, auto-update toggle.

---

## Two ways to build

### 1. Native WordPress (no page builder)

The theme ships a complete set of PHP templates that render the full layout:

- **Homepage** (`front-page.php`): hero, featured winners, active competitions,
  charity donations, trust block.
- **Header** (`header.php` + `template-parts/header.php`): sticky nav + top bar.
- **Footer** (`footer.php` + `template-parts/footer.php`): 4-column dark footer.
- **Shop / Active Competitions** (`woocommerce/archive-product.php`): 3-col grid.
- **Winners page** (`page-winners.php` template): renders `[raffle_ended_list]`.
- **Charities page** (`page-charities.php` template): total raised + `[raffle_charities]`.
- **Blog** (`index.php`, `archive.php`, `single.php`, `search.php`, `404.php`).

### 2. Elementor Theme Builder (PRO Elements / Elementor Pro)

A one-click template set lives in `elementor/`. See
**[`elementor/README.md`](elementor/README.md)** for the full import walkthrough.

| Template | File | Applies to |
|---|---|---|
| Header | `elementor/theme-builder/header.json` | Entire site |
| Footer | `elementor/theme-builder/footer.json` | Entire site |
| Home | `elementor/theme-builder/home.json` | Static front page |
| Product archive | `elementor/theme-builder/archive.json` | Shop / raffle category archives |
| Single raffle | `elementor/theme-builder/single-raffle.json` | Raffle products |
| Single charity | `elementor/theme-builder/single-charity.json` | `raffle_charity` CPT |
| My Account | `elementor/theme-builder/my-account.json` | `/my-account` |

---

## WPRaffles plugin integration

The theme talks to the plugin exclusively through documented surfaces (no plugin
file edits):

- **Palette ownership** — the Theme Options Style tab drives both the theme's
  `--diamond-*` vars and the plugin's `--wpr-*` vars from one source. The
  plugin's own styling output is suppressed so there's no competition.
- **Asset forcing** — the theme force-enqueues the plugin's `raffle-public`
  stylesheet on the homepage and template pages that render raffle shortcodes
  via `do_shortcode()`, so cards look identical on the homepage, the shop, and
  the raffles page.
- **Shortcodes** embedded in templates:
  - `[raffle_list status="active" columns="3" per_page="9"]` — Active Competitions.
  - `[raffle_ended_list]` — Winners page.
  - `[raffle_charities columns="3"]` — Charities grid.
- **Featured winners** — `WPRaffle_Theme_Integration::get_featured_winners()`
  reads rows flagged `is_featured` via `Raffle_Featured_Winners::get_featured()`.
- **Charity total** — sums across all charities via
  `Raffle_Charity::calculate_total_raised_for_charity()`.

If the plugin is not active, every integration degrades gracefully — sections
show neutral placeholders or prompts instead.

---

## GitHub auto-updates

The theme includes a self-contained GitHub release updater
(`inc/class-wpraffle-theme-updater.php`). It polls
`api.github.com/repos/wpraffle/wpraffle-theme/releases/latest` every 12 hours
(via a `twicedaily` cron) and injects an update into WordPress's theme-update
transient when a newer tag is found. Public repos only.

To publish an update:
1. Bump `Version:` in `style.css` (and the `WPRAFFLE_THEME_VERSION` constant in
   `functions.php`).
2. Tag a matching GitHub release (e.g. `v1.1.0`).
3. Upload the theme zipped as a release asset named `*.zip` (or the auto
   `zipball_url` fallback is used).

Sites running the older version will see the update in Dashboard → Updates.

---

## PRO Elements (bundled)

[PRO Elements](https://github.com/proelements/proelements) is the free GPL fork
of Elementor Pro that powers the Theme Builder. It is bundled at
`lib/proelements/proelements.zip` and offered for install via the TGMPA notice
on theme activation. It is **optional** — the native templates work without it.

---

## Asset checklist (replace these placeholders)

| File | Used for | Replace with |
|---|---|---|
| `assets/images/placeholder-winner.svg` | Winner photo fallback | Per-winner photos (set via the plugin's featured winners) |
| `assets/images/placeholder-prize.svg` | Competition product fallback | Product featured images |
| **Custom logo** | Header / footer | **Appearance → Customize → Site Identity → Logo** |
| **Hero background** | Homepage hero | Theme Options → Content → Hero background image |

---

## File map

```
wpraffle-theme/
├── style.css                         # Theme header (Version: 1.0.0)
├── theme.json                        # Block-editor palette/typography
├── functions.php                     # Bootstrap (loads inc/, defines constants)
├── header.php / footer.php           # Wrappers (fire wpraffle_theme_header/footer)
├── front-page.php                    # Homepage sections
├── index.php / single.php / archive.php / search.php / 404.php / page.php
├── page-winners.php                  # Template: Winners
├── page-charities.php               # Template: Charities
├── page-full-width.php              # Template: Full Width
├── searchform.php / sidebar.php / comments.php
├── template-parts/                   # Header, footer, hero, sections, cards
├── woocommerce/                      # archive-product.php
├── assets/
│   ├── css/ base, components, woocommerce, wpraffle
│   ├── js/ wpraffle-theme.js
│   └── images/ placeholders
├── inc/
│   ├── class-wpraffle-theme-setup.php
│   ├── class-wpraffle-theme-woocommerce.php
│   ├── class-wpraffle-theme-integration.php   # Plugin integration (palette, assets, data)
│   ├── class-wpraffle-theme-settings.php      # Theme Options panel
│   ├── class-wpraffle-theme-updater.php       # GitHub release updater
│   ├── class-wpraffle-theme-elementor.php
│   ├── class-wpraffle-theme-tgm.php
│   └── template-tags.php
├── admin/                            # Settings views + admin CSS/JS
├── elementor/                        # Theme Builder JSON + section blocks + README
└── lib/
    ├── tgmpa/                        # TGM Plugin Activation
    └── proelements/                  # Bundled PRO Elements zip
```

---

## Licence

GPL-2.0-or-later. Bundled PRO Elements is GPL-3.0. TGMPA is GPL-2.0.
