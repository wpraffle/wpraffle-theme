# WPRaffle Theme

Version 1.4.6

The companion WordPress theme for WPRaffle includes competition pages, WooCommerce layouts, customer account styling and configurable homepage sections.

## Requirements

- WordPress 6.5 or later
- PHP 8.1 or later
- WooCommerce 8.0 or later
- WPRaffle 1.4.6

Elementor is optional for native pages. Elementor templates require the relevant builder features.

## Features

- Default, Diamond, Golf, Car, Retro and Elite visual presets
- Native homepage, competition, winner and informational templates
- WooCommerce cart, checkout and customer account styling
- Homepage section selection and ordering
- Competition filters, sorting and pagination
- Configurable competition cards and quick view
- Private preview of supported unsaved settings
- Mobile entry controls and sticky entry bars
- Optional Elementor templates, widgets and dynamic tags
- Settings import and export

## What's new in 1.4.6

- Advanced filters sit inside an expandable panel, with counts and removable selections.
- Filter results support browser Back and Forward navigation.
- Empty results and failed requests provide clear recovery actions.
- Competition cards use a simpler default layout with clearer dates and price labels.
- Primary buttons have readable text across all six presets.
- Quick view supports request cancellation, retry and improved focus return.
- Mobile controls respond more consistently to scrolling and reduced-motion preferences.
- Starter content uses neutral copy and configurable statistics.

## Installation

1. Back up your files, database and theme settings.
2. Install and activate WooCommerce and WPRaffle 1.4.6.
3. Upload `wpraffle-theme-1.4.6.zip` through Appearance → Themes → Add New → Upload Theme.
4. Activate the theme and open Theme Options or the Control Centre.
5. Configure your preset, menus, homepage sections, site identity and images.
6. Clear caches and check the competition, cart, checkout and account pages.

Existing settings and saved card layouts are preserved. Version 1.4.6 adds no theme database tables. Use a child theme for custom PHP and template changes.

## Homepage and content

Use the homepage controls to enable, disable and order sections. Replace starter images, contact details, links and legal text before publishing. Add your own verified statistics and customer reviews where required.

Native templates support the homepage, competition listing, results, How It Works, FAQ, Contact and Legal pages. Elementor templates and widgets can be used alongside these layouts.

## Competition discovery

The competition listing provides status and sorting controls, with category, price, closing-window and availability options in the advanced filter panel. Selected filters can be removed individually or cleared together.

Successful changes update the page URL. Back and Forward restore the corresponding listing. If a request fails, the current results remain visible and a retry action is available. Filtering and pagination also work without JavaScript.

## Product cards

Theme Options → Product Cards controls the image ratio, title placement, progress display and supported card fields. Use the checkboxes to select optional fields and the Up/Down buttons to arrange them.

Images, titles, prices and the entry link remain available. The entry link stays last. Optional fields include draw date, countdown, cash alternative, progress and charity information.

New installations use image, title, price, draw date, progress and entry link. Saved layouts retain their existing order and can be changed in Product Cards.

## Live preview

In Homepage or Product Cards, select Refresh live preview to view supported unsaved changes. Previews are available only to the signed-in administrator and expire after 15 minutes.

Use Save settings to publish changes. Refreshing the preview does not save settings.

## Customer accounts

My Account → My Raffles provides the plugin's Overview, Tickets and Notifications views. The theme styles the account summary, next draw and entry history. Wallet information appears when a supported integration is available.

## Keyboard and display preferences

Quick view supports Tab and Shift+Tab navigation, Escape to close and focus return. Filter results announce loading and result changes. Supported animations respond to reduced-motion preferences, with additional styling for contrast and transparency preferences.

Check your chosen preset with your images, translated text, checkout extensions and custom content.

## Customization

Use a child theme for template overrides. Export theme settings before changing presets or importing a configuration. Custom templates may override the default card and filter layouts.

Fonts, newsletters, social feeds and other external integrations require their own provider settings. Configure these services through the appropriate theme or plugin controls.

## Updating

Install the plugin update before the theme update. Test competition browsing, entry, checkout, refunds and account pages on staging, including any custom templates and third-party extensions. Clear page and application caches after updating.

## License

GPL-2.0-or-later. Preserve bundled third-party license notices.

See [CHANGELOG.md](CHANGELOG.md) for version history and [RELEASE.md](RELEASE.md) for release notes.
