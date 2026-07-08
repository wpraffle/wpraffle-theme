# Diamond — Elementor Templates

This folder ships a full set of **Elementor Theme Builder** templates and
reusable section blocks that recreate the Paragon-style layout for the Diamond
theme. They work with both **Elementor Pro** and the free **Pro Elements** fork
(github.com/proelements/proelements).

## Requirements

- **Elementor** (free) — required for any of these templates.
- **Elementor Pro** *or* **Pro Elements** — required for the Theme Builder
  templates (header, footer, archive, single, my-account).
- **WooCommerce** — required for the WooCommerce widgets used in `archive.json`,
  `single-raffle.json` and `my-account.json`.
- **WPRaffles** plugin — required for the `[raffle_list]` / `[raffle_charities]`
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
5. After importing, open each template and click **Edit with Elementor** to
   confirm it looks right, then **Publish**.
6. **Assign display conditions** when prompted (or under
   *Theme Builder → Edit Condition*):
   - Header / Footer → *Entire Site*
   - Archive → *Product Archives*
   - Single Raffle → *Products*
   - Single Charity → *raffle_charity* (Custom Post Type)
   - My Account → *URL contains* `/my-account`

> **Tip:** the native PHP templates in the theme already render the full
> Paragon layout with zero Elementor dependency. Import the Theme Builder set
> only if you want to edit those areas visually in Elementor.

## Importing the homepage

`home.json` is a **Page** template (not a Theme Builder document).

1. **Pages → Add New** → title it "Home" → **Publish**.
2. **Edit with Elementor** → click the folder icon (grey structure icon) →
   **Import Document** → choose `home.json`.
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

## Menu references

Several templates reference a WordPress menu with the slug **`primary`** (main
nav) and **`footer`** (footer links). Create these under
**Appearance → Menus → Manage Locations** and the nav widgets will pick them up
automatically.

## Customising the palette

All colours in the templates use the Diamond palette directly (pink `#E4678A`,
blue `#5CAEED`, green `#63DD92`, dark `#2C2C2C`, light `#F6F6F6`). The WPRaffles
plugin's own components inherit the same palette via the `--wpr-*` variable
overrides emitted by the theme (`inc/class-diamond-wpraffle.php`). To re-skin,
search-and-replace the hex values in the JSON, or change them in the Elementor
editor after import.
