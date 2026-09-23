# WPRaffle Theme 1.4.0

Release date: 23 September 2026  
Previous release: 1.3.5

WPRaffle Theme 1.4.0 is a production-hardening and visual-consistency release
designed for WPRaffle Plugin 1.4.0. It resolves missing or inconsistent styling
on PHP-rendered competition pages and completes the shared layout system across
native templates and WooCommerce account screens.

## Highlights

- Uses the plugin's supported frontend-context and asset-enqueue contract.
- Restores complete menu, header, preset and competition-card styling on native
  competition templates.
- Gives Raffles pages containing `[raffle_list]` or the raffle-list block the
  same full-width grid structure as the Competitions template.
- Unifies selectable and default page templates on one hero, container,
  content and empty-state contract.
- Refines WooCommerce My Account navigation, WPRaffle tabs, content panels,
  forms, addresses, notices and transaction tables.
- Adds safe tablet/mobile reflow for account navigation and wide tables.
- Uses a solid account glyph matching the cart action.
- Adds content-derived asset versions to prevent stale CSS and JavaScript after
  deployment without changing the public 1.4.0 version.
- Updates documentation and release metadata for the native
  `wpraffle.dev/docs/` documentation site.

## Fixed

- Theme integration styles could disappear when their plugin stylesheet
  dependency had not yet been registered.
- Competition pages rendered through PHP could be missed by shortcode-only
  asset detection.
- Raffles pages using the default page template were constrained to an article
  width instead of the competition grid.
- My Account panels, notices and nested navigation had inconsistent spacing and
  mobile wrapping.
- Cached 1.4.0 assets could survive subsequent styling corrections.

## Compatibility

- WordPress 6.5 or later; tested through 7.1.
- PHP 8.1 or later.
- WooCommerce 8.0 or later; tested through 11.0.
- WPRaffle Plugin 1.4.0 recommended.
- Elementor is optional; Theme Builder imports require Elementor Pro or the
  bundled GPL-compatible PRO Elements package.

## Upgrade notes

1. Back up the site and database.
2. Install or update WPRaffle Plugin 1.4.0.
3. Update the theme and purge page, object, CDN and browser caches.
4. Review saved menu locations, page assignments and the active style preset.
5. Verify the homepage, competition grid, single competition, Winners,
   Charities, cart, checkout and every My Account endpoint on staging.

Custom CSS or child themes still using historical `.diamond-*` selectors or
`--diamond-*` variables must migrate to the current `.wpr-*` and `--wpr-*`
names.

## Release asset

Upload `wpraffle-theme-1.4.0.zip` to the GitHub release for tag `v1.4.0`. The
archive must extract to the single top-level directory `wpraffle-theme/`.

Full historical changes are recorded in [CHANGELOG.md](CHANGELOG.md).
