# WPRaffle Theme

WPRaffle Theme is the official open-source WordPress theme for the WPRaffle
plugin. It provides a polished competition storefront, native page templates,
WooCommerce styling and optional Elementor Theme Builder templates.

[Documentation](https://wpraffle.dev/docs/getting-started/wpraffle-theme/) ·
[Releases](https://github.com/wpraffle/wpraffle-theme/releases) ·
[Changelog](CHANGELOG.md) ·
[WPRaffle plugin](https://github.com/wpraffle/wpraffle)

## Version 1.4.0

Theme 1.4.0 is the paired release for WPRaffle Plugin 1.4.0. It unifies native
templates, plugin components and WooCommerce screens under one dependable
asset and layout contract.

### Release highlights

- Correct header, navigation, preset and competition-card styling across native
  competition templates.
- Consistent layouts for Competitions, Raffles, Winners, Charities, Instant
  Wins, Draw Results and standard pages.
- Content-aware Raffles pages automatically use the full competition grid.
- Refined WooCommerce My Account navigation, panels, notices, forms and tables.
- Responsive account and ticket views without horizontal collisions.
- Solid account icon aligned with the cart and other header actions.
- Content-aware asset versions that invalidate stale browser/CDN files.
- Preset-aware day/night styling across native templates.
- Control Centre readiness checks, guided setup, starter imports, system
  reports and child-theme generation.

See [RELEASE.md](RELEASE.md) for upgrade and acceptance notes.

## Included experience

- Purpose-built homepage with configurable competition sections.
- Native competition, winner, charity, result and instant-win templates.
- WooCommerce shop, product, cart, checkout and My Account presentation.
- Six style presets: Default, Diamond, Golf, Car, Retro and Elite.
- Responsive header, mobile navigation and four-column footer.
- Custom logo, colours, typography and section controls.
- Elementor dynamic tags, Theme Builder templates and homepage section blocks.
- Gutenberg and wide-block support.
- GitHub-based theme update checking.

The theme remains usable without Elementor. PRO Elements or Elementor Pro is
only required for importing and editing Theme Builder templates.

## Requirements

| Component | Minimum | Tested through |
|---|---:|---:|
| WordPress | 6.5 | 7.1 |
| PHP | 8.1 | — |
| WooCommerce | 8.0 | 11.0 |
| WPRaffle plugin | 1.4.0 recommended | 1.4.0 |

## Installation

1. Install and activate `wpraffle-1.4.0.zip` first.
2. Download `wpraffle-theme-1.4.0.zip` from the theme GitHub release.
3. In WordPress, open **Appearance → Themes → Add New → Upload Theme**.
4. Upload the ZIP, install it and activate WPRaffle Theme.
5. Open **Appearance → Theme Options** and complete the guided setup.
6. Assign menus, logo and required WPRaffle pages.
7. Purge page, object, CDN and browser caches after upgrading.

Test upgrades and preset changes on staging before applying them to a live
competition website.

## Native templates

The theme includes native templates for:

- Homepage and standard content pages.
- Active competitions and shortcode/block-driven raffle listings.
- Winners and past competitions.
- Charities and fundraising totals.
- Instant wins and draw results.
- WooCommerce archives, products, cart, checkout and My Account.
- Blog, archive, search and 404 views.

Selectable page templates use consistent `WPRaffle — Page Name` labels while
their existing filenames remain stable for saved WordPress assignments.

## Elementor

The `elementor/` directory contains optional Theme Builder templates and
section blocks. Import guidance is in
[`elementor/README.md`](elementor/README.md). The bundled PRO Elements package
is GPL-licensed and optional; native templates do not depend on it.

## Support and contributing

- Read the [theme documentation](https://wpraffle.dev/docs/getting-started/wpraffle-theme/).
- Search or open a [GitHub issue](https://github.com/wpraffle/wpraffle-theme/issues).
- Include WordPress, WooCommerce, PHP, plugin and theme versions with reports.
- Mention the active preset and whether Elementor is enabled for visual issues.

## Licences

WPRaffle Theme is licensed under GPL-2.0-or-later. Bundled PRO Elements is
GPL-3.0, and TGMPA is GPL-2.0.
