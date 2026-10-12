=== WPRaffle Theme ===
Contributors: wpraffle
Tags: e-commerce, entertainment, custom-colors, custom-menu
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
WC requires at least: 8.0
WC tested up to: 11.2
Stable tag: 1.4.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A companion WordPress theme for WPRaffle, with native competition pages, WooCommerce account/checkout styling, six visual presets, a homepage section manager and optional Elementor templates/widgets. This release updates the supplied 1.4.0 theme while retaining existing settings.

== Design update 1.4.6 ==

- Compact discovery into status/sort and expandable advanced filters with counts and removable selected-filter labels.
- Synchronize successful filters with URL history and restore results through Back/Forward; clear to defaults and retain page identity without JavaScript.
- Add explicit zero-result recovery, failed-request explanations and retries; clamp AJAX pagination to available pages.
- Simplify new card defaults to image/title/price/date/progress/action while retaining saved custom field orders.
- Improve typography, date hierarchy, 44px touch controls and press feedback; resolve legacy preset/style-order conflicts and use readable solid primary-action foregrounds.
- Cancel stale quick-view requests, preserve loading space, add failure retry and restore focus when a filtered trigger disappears.
- Stabilize mobile actions around small scroll changes and support motion, transparency and contrast preferences.
- Remove invented trust-statistic/payout/guarantee/donation defaults from native/starter components while retaining explicitly configured operator content.
- Add packaged design and preset contrast browser suites; preserve native dialogs and existing presets rather than adding a new gesture framework.

== Requirements ==

Declared minimums remain WordPress 6.5, PHP 8.1 and WooCommerce 8.0. Install WPRaffle 1.4.5 for the new discovery, account and card features. Elementor is optional for native pages; advanced Elementor templates require their supported builder capabilities.

Integration testing used WordPress 7.1.3, WooCommerce 11.2.0, Elementor 4.3.4, PHP 8.4.25 and MySQL 9.5. Minimum-version combinations, Elementor Pro/PRO Elements, and every wallet/currency/multilingual adapter were not verified. Supplied third-party assets and archives were preserved, not independently audited or automatically activated.

== Existing theme features ==

The source already includes Default, Diamond, Golf, Car, Retro and Elite presets; native homepage/competition/winner and informational templates; WooCommerce styling; a Control Centre with guided setup/starter sites/template library/settings import-export; Elementor templates/widgets/dynamic tags; homepage ordering; sticky/mobile entry calls to action; quick view; account-related templates; responsive styles and optional conversion/social-proof features. These existing features were verified before new functionality was selected.

== Changes in 1.4.5 ==

- Advanced competition discovery with category, price range, status, closing window, availability, sorting and bounded pagination.
- JavaScript-enhanced filtering with a server-rendered form fallback and accessible result announcements.
- Private preview of supported unsaved homepage/card settings, without publishing them.
- Keyboard-operable card-part ordering integrated with the plugin's whitelisted templates.
- Account Overview and Notifications through plugin 1.4.5's existing My Raffles endpoint.
- Quick-view focus trapping, Escape dismissal, focus return and filter-grid reinitialization.
- Mobile header overflow, visible focus, reduced-motion and skip-link improvements.
- Retained Elementor inheritance, social-proof escaping, private-product quick-view protection, safer URLs and login-logo fixes.

See [CHANGELOG.md](CHANGELOG.md), [RELEASE.md](RELEASE.md), [AUDIT-1.4.5.md](AUDIT-1.4.5.md), and [tests/README.md](tests/README.md).

== Install or upgrade ==

1. Back up theme settings/files/database and validate the pair on staging.
2. Install/upgrade WooCommerce and WPRaffle 1.4.5 first.
3. Upload `wpraffle-theme-1.4.5.zip` in Appearance → Themes → Add New → Upload Theme. Replace the previous theme when prompted, then activate it.
4. Open Appearance → Theme Options/Control Centre. Review presets, sections, menus, site identity, images and existing imported settings.
5. Create or select a page using the native Competitions template. Check desktop/mobile browsing, entry, cart, checkout and account pages.
6. Clear caches after updating both packages. Use a child theme for custom PHP/template changes so future upgrades preserve them.

Updating an earlier 1.4.5 build requires uploading these revised ZIPs; the user-requested version remains 1.4.5. No new theme database tables are introduced.

== Native pages and Elementor ==

Use the native templates for the homepage, competition listing, draw results, How It Works, FAQ, Contact and Legal pages without a page builder. Existing setup/template tools can help create pages and menus; review content and links before publishing.

Elementor widgets/templates remain optional. Dynamic tags now extend Elementor's correct Core DynamicTags data class and are registered only when its API is available. Test your own Elementor editor and templates after upgrading. The native site remains the baseline tested path; bundled alternative builder archives were not installed as part of this work.

== Advanced competition discovery ==

The native competition section/page offers category, minimum/maximum ticket price, active/upcoming/ended status, closing within 24 hours or 7 days, available/sold-out state, and newest/closing/price/remaining sorting. The initial page renders on the server; the Apply filters form works without JavaScript. With JavaScript, results update through a nonce-protected public AJAX handler and pagination buttons, with loading/result announcements and stale-response protection.

The theme uses WPRaffle 1.4.5's shared prepared, whitelisted query. Native pages show nine results per page; the shared page-size ceiling is 48. Public listings exclude private or password-protected linked products. Categories refer to the plugin's `raffle_category` taxonomy. Theme filter URLs use `wprt_` query keys. Existing shortcodes remain available for custom pages.

== Private unsaved preview ==

In Theme Options → Homepage or Product Cards, change supported settings and click Refresh live preview. The iframe shows whitelisted unsaved changes for the logged-in administrator only. Refresh replaces the previous token; a preview expires after 15 minutes. Preview requests do not invoke the settings-save handler.

Supported preview fields include homepage section enable/order, hero button text/links and card layout styles/part order. Arbitrary CSS/scripts, unrelated tabs and external iframe destinations are excluded. A preview URL does not grant guest access to unpublished settings. Responses are marked no-store/noindex. Use Save settings separately when ready to publish. Theme settings stored in browser/page caches may still need clearing after saving.

== Card layout builder ==

Theme Options → Product Cards provides Up/Down buttons to reorder supported fields. Title, image and price are retained; the entry CTA stays last. Allowed parts: title, image, draw date, countdown, cash alternative, price, progress, charity and CTA. Existing default order is preserved until changed.

The builder changes a whitelist of template parts, not arbitrary PHP, HTML or template paths. Existing style settings still control aspect ratio, hover/title/progress treatments. Confirm each chosen style on mobile and with long titles. Developer field order uses `wpraffle_card_fields`; the plugin validates the resulting names before inclusion.

== Customer account ==

Plugin 1.4.5 extends WooCommerce My Account → My Raffles with Overview and Notifications alongside existing Tickets/account views. Overview shows completed entries, competitions, wins and upcoming draws; wallet information appears only when the existing wallet API is available. Preferences control optional lifecycle emails for the account owner. Purchase/prize/security messages stay essential. Theme styling supports the overview; ticket/account data and permission enforcement live in the plugin.

== Accessibility and mobile behaviour ==

Quick view receives focus, traps Tab/Shift+Tab while open, closes with Escape, returns focus to its trigger and restores scrolling. Newly filtered cards initialize quick view correctly. Filter results have live announcements and busy states. Buttons and fields retain labels/focus styles; reduced-motion preferences suppress supported animations. Skip links target the native main content. The mobile header now accommodates long site titles without horizontal page overflow.

These are tested improvements, not a WCAG certification. Check screen readers, contrast across your chosen preset, translated labels, images, checkout extensions, keyboard navigation and custom content separately.

== Security and privacy ==

Public quick view is limited to active/finished competitions with public, unprotected products. Social-proof values are escaped, and modal URLs allow expected HTTP/HTTPS protocols. Administrator preview/settings writes require capability and nonce checks. Imported card-part names are whitelisted. Custom CSS and settings remain administrator-controlled.

Existing public winner/entry/social-proof features may display configured customer information. Review consent and operator settings before enabling them. Draw receipts added by the plugin exclude names/emails but are not an independent fairness certificate. Preview storage is a temporary per-administrator transient, not a new customer data store. SMS is not configured by the theme or plugin.

== Settings, assets and customization ==

Use the existing Style, Content, Homepage, Product Cards, conversion/layout and advanced controls for your site. Export settings before experiments; review imported values rather than treating an import as trusted code. Replace placeholder images, legal/contact text and links. External fonts/icons, newsletter, Instagram, analytics, social-proof and update integrations depend on existing settings and provider configuration.

Keep custom changes in a child theme and use established WordPress hooks. Theme 1.4.5 loads its additive CSS/discovery JavaScript alongside existing assets; it does not replace the entire design system. Optional template overrides can bypass default card/discovery behaviour, so verify them after updating.

== Tests and release limits ==

Executable tests in `tests/` cover private-product quick view, HTML escaping and URL restrictions, Elementor API present/absent, and real browser behaviour. Browser tests use a fresh disposable WordPress fixture and separately provided Playwright/Chrome; see tests/README.md. They never need production credentials or customer data.

Release checks passed for PHP/JavaScript syntax, plugin/theme unit/security suites, actual WordPress integration, concurrent allocation/draws, browser filtering, quick-view keyboard focus, mobile overflow, private homepage preview and card ordering. PHPCS/PHPStan, minimum-version matrix, full accessibility audit and real gateway/mail/wallet delivery were not available. PHP 8.4 logged an Elementor dependency deprecation and an early WooCommerce translation notice; neither was treated as a successful clean-log claim.

Before production, verify existing settings/child-theme upgrades, every important page/template, entry flow, checkout/refunds, mail/cron, optional builders and third-party extensions on your staging site. No production-site files were changed during testing.

== License ==

GPL-2.0-or-later. Preserve bundled third-party license notices. This package does not certify raffle legality, independent draw fairness or every extension's compatibility.

== Changelog ==

= 1.4.6 =

- Compact discovery into status/sort and expandable advanced filters with counts and removable selected-filter labels.
- Synchronize successful filters with URL history and restore results through Back/Forward; clear to defaults and retain page identity without JavaScript.
- Add explicit zero-result recovery, failed-request explanations and retries; clamp AJAX pagination to available pages.
- Simplify new card defaults to image/title/price/date/progress/action while retaining saved custom field orders.
- Improve typography, date hierarchy, 44px touch controls and press feedback; resolve legacy preset/style-order conflicts and use readable solid primary-action foregrounds.
- Cancel stale quick-view requests, preserve loading space, add failure retry and restore focus when a filtered trigger disappears.
- Stabilize mobile actions around small scroll changes and support motion, transparency and contrast preferences.
- Remove invented trust-statistic/payout/guarantee/donation defaults from native/starter components while retaining explicitly configured operator content.
- Add packaged design and preset contrast browser suites; preserve native dialogs and existing presets rather than adding a new gesture framework.


= 1.4.5 =

### Added
- Advanced competition filters and bounded pagination, progressive AJAX loading, result announcements and non-JavaScript form/pagination fallback.
- Administrator-only, expiring preview of supported unsaved homepage and card settings; no-store/noindex and same-origin restrictions.
- Whitelisted card-part ordering with keyboard-operable controls and retained core fields/last entry CTA.
- Overview styling for the plugin's account dashboard and optional notification preferences.
- Configurable isolated Playwright browser suite, plus existing quick-view/escaping/Elementor tests.

### Fixed
- Preview requests no longer invoke the existing settings-save handler or publish unsaved changes.
- Quick-view focus trapping, Escape close, focus return, scroll restoration and initialization after filtered grids.
- Mobile header overflow with long site titles, native skip-link targets, visible focus and reduced-motion styling.
- Escape social-proof values; restrict public quick view to permitted competitions and published/unprotected products.
- Validate modal link/image URL protocols and repair custom login-logo CSS.
- Correct optional Elementor dynamic-tag inheritance and ticket-price fallback behaviour.

### Compatibility
- Retain version 1.4.5, existing settings/presets and original theme folder name. Pair new features with WPRaffle 1.4.5.
- Browser checks cover native pages; minimum-version matrix, optional builders and full accessibility certification remain unverified.


== Upgrade Notice ==

= 1.4.5 =
Back up and stage both packages. Additive purchase order-line migration; manually replace earlier 1.4.5 builds. See RELEASE.md and AUDIT-1.4.5.md.
