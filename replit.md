# Green Silk – DokuWiki Template

A modern, redesigned DokuWiki template with a clean two-column layout, auto dark/light mode, RTL support, and Persian/Arabic/English language switching.

## Stack

- **PHP** – DokuWiki template engine (`.php` files)
- **CSS** – Pure CSS with custom properties (no preprocessor)
- **Vanilla JS** – Inline script in `main.php`

## Structure

| File | Purpose |
|---|---|
| `main.php` | Main template HTML structure |
| `design.css` | Typography, content components, design tokens |
| `layout.css` | Header, sidebar, footer, navigation layout |
| `responsive.css` | Mobile/tablet breakpoints, side drawer |
| `rtl.css` | Right-to-left overrides |
| `_admin.css` | Admin panel styles |
| `_mediamanager.css` | Media manager styles |
| `print.css` | Print stylesheet |
| `style.ini` | DokuWiki color replacements & stylesheet loading order |

## Installation

1. Copy this folder to `lib/tpl/wiki-template/` inside a DokuWiki installation
2. Go to **Admin → Configuration → Template** and select `wiki-template`
3. Optionally place a `logo.png` (64×64px) in the `images/` folder

## Design System

CSS custom properties are defined in `design.css`:

- `--bg`, `--bg-card`, `--bg-raised`, `--bg-subtle`, `--bg-accent` – Surface layers
- `--fg`, `--fg-2`, `--fg-3`, `--fg-4` – Text hierarchy
- `--pr`, `--pr-hover`, `--pr-light`, `--pr-ring` – Brand green
- `--bd`, `--bd-strong` – Borders
- `--sh-1`, `--sh-2`, `--sh-3` – Elevation shadows
- `--r`, `--r2`, `--r3` – Border radii
- `--t` – Transition timing

Dark mode vars are defined under `[data-theme="dark"]`.

## User Preferences

- Keep the existing file structure (no build tools, no preprocessor)
- Maintain DokuWiki PHP template compatibility
- RTL (Persian/Arabic) support must remain fully functional
