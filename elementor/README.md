# WPRaffle Theme — Elementor Templates

This folder ships a full set of **Elementor Theme Builder** templates and
reusable section blocks that recreate the WPRaffle homepage layout for the
WPRaffle Theme. They work with both **Elementor Pro** and the free
**Pro Elements** fork (github.com/proelements/proelements).

## Requirements

- **Elementor** (free) — required for any of these templates.
- **Elementor Pro** *or* **Pro Elements** — required for the Theme Builder
  templates (header, footer, archive, single, my-account, cart, checkout,
  404, search).
- **WooCommerce** — required for the WooCommerce widgets used in `archive.json`,
  `single-raffle.json`, `my-account.json`, `cart.json`, `checkout.json` and
  `search.json`.
- **WPRaffles** plugin — required for the `[raffle_list]` / `[raffle_charities]`
  / `[raffle_countdown]` / `[raffle_live_draw]` / `[raffle_testimonials]`
  shortcodes embedded in `home.json` and the section blocks.

## Importing the full Theme Builder set

1. **Install & activate** Elementor + (Pro Elements *or* Elementor Pro).
2. Go to **Templates → Theme Builder**.
3. Click **Import** (the arrow at the top of the library).
4. Import each file from `theme-builder/` one by one:
   - `header.json`
   - `footer.json`
   - `archive.json`
   - `single-raffle.json`
   - `single-charity.json`
   - `my-account.json`
   - `cart.json`
   - `checkout.json`
   - `404.json`
   - `search.json`
5. After importing, open each template and click **Edit with Elementor** to
   confirm it looks right, then **Publish**.
6. **Assign display conditions** when prompted (or under
   *Theme Builder → Edit Condition*):
   - Header / Footer → *Entire Site*
   - Archive → *Product Archives*
   - Single Raffle → *Products*
   - Single Charity → *raffle_charity* (Custom Post Type)
   - My Account → *URL contains* `/my-account`
   - Cart → *URL contains* `/cart`
   - Checkout → *URL contains* `/checkout`
   - 404 → *404 page*
   - Search → *Search results*

> **Tip:** the native PHP templates in the theme already render the full
> WPRaffle layout with zero Elementor dependency. Import the Theme Builder set
> only if you want to edit those areas visually in Elementor.

## Importing the homepage

`theme-builder/home.json` is a **Page** template (not a Theme Builder document).

1. **Pages → Add New** → title it "Home" → **Publish**.
2. **Edit with Elementor** → click the folder icon (grey structure icon) →
   **Import Document** → choose `theme-builder/home.json`.
3. Set the page as the static homepage under **Settings → Reading**.

## Section blocks

The files in `sections/` are individual homepage sections exported as standalone
documents. Use them to assemble a homepage piece-by-piece, or drop them into any
existing page via **Import Document**:

| File | Section |
|------|---------|
| `sections/hero.json` | Hero banner |
| `sections/winners-carousel.json` | Featured Winners (uses `[raffle_list status="ended"]`) |
| `sections/active-competitions.json` | Active Competitions grid |
| `sections/charity-donations.json` | Charity total + grid |
| `sections/instant-payouts.json` | Secure / Free / Verified trust block |
| `sections/how-it-works.json` | 3-step "How It Works" grid (Choose / Enter / Win) |
| `sections/featured-spotlight.json` | Featured Competition spotlight (uses `[raffle_list featured="1"]`) |
| `sections/stats-counter.json` | "By the Numbers" animated counters (winners / raised / rating) |
| `sections/countdown.json` | Next Draw countdown (uses `[raffle_countdown]`) |
| `sections/live-draw.json` | Live Draw embed (uses `[raffle_live_draw]`) |
| `sections/testimonials.json` | Player testimonials (uses `[raffle_testimonials]` + fallback grid) |
| `sections/faq.json` | Frequently Asked Questions accordion |

## Dynamic tags

The theme registers a **WPRaffle Theme** dynamic-tag group
(see `inc/class-wpraffle-theme-elementor.php` and
`inc/class-wpraffle-theme-elementor-tags.php`) so any native Elementor widget
can bind to live values instead of hardcoding them in JSON:

- **Raffle ID** — the current product's linked raffle ID.
- **Ticket Price** — the current raffle's ticket price.
- **Draw Date** — the current raffle's draw date (formatted).
- **Charity Total Raised** — the global total raised across all raffles.

The charity total is also available as the `[wpraffle_charity_total]`
shortcode, used directly in `sections/charity-donations.json` and
`theme-builder/home.json` for the charity total banner. The Elementor tag form
of the same value lets you drop it into any heading, text or counter widget via
the dynamic tag picker.

## Menu references

Several templates reference a WordPress menu with the slug **`primary`** (main
nav) and **`footer`** (footer links). Create these under
**Appearance → Menus → Manage Locations** and the nav widgets will pick them up
automatically.

## Customising the palette

All colours in the templates use the WPRaffle palette directly (accent pink
`#E4678A`, accent blue `#5CAEED`, success green `#63DD92`, dark `#2C2C2C`,
light `#F6F6F6`). The WPRaffles plugin's own components inherit the same palette
via the `--wpr-*` variable overrides emitted by the theme
(`inc/class-wpraffle-theme-integration.php`). To re-skin, search-and-replace
the hex values in the JSON, or change them in the Elementor editor after import.
