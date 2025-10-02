# UM Select2 i18n Fix

Fixes the common **`TypeError: Cannot read properties of undefined (reading 'define')`** caused by **Ultimate Member’s bundled Select2 i18n files** (e.g. `en.js`) when loaded before the Select2 AMD loader.

This plugin:
- Loads the **Select2 full build** (`select2.full.min.js`) early in the `<head>` so AMD is always defined.
- Removes **Select2 i18n bundles** (like `en.js` and other locale files) that cause crashes.
- Injects a **JavaScript guard** to prevent fatal errors if i18n executes before AMD is ready.
- Leaves Select2 working as expected in English (default) or your chosen language.

---

## Why?

Ultimate Member (UM) includes its own copy of Select2. In some setups (especially with caching/optimization plugins like Autoptimize, WP Rocket, LiteSpeed), the **i18n files load before the core Select2 script**, resulting in:


This plugin fixes the load order and prevents the crash.

---

## Installation

### From GitHub
1. Download or clone this repository.
2. Zip the folder:

um-select2-i18n-hard-fix/
 -um-select2-i18n-hard-fix.php
 -README.md

3. In WordPress Admin:  
`Plugins → Add New → Upload Plugin → Choose File` → upload the ZIP.
4. Click **Activate**.

### Manual
1. Upload the `um-select2-i18n-hard-fix` folder into `/wp-content/plugins/`.
2. Go to WordPress Admin → **Plugins**.
3. Activate **UM Select2 i18n Hard Fix**.

---

## Requirements

- WordPress 5.8+
- Ultimate Member 4.0.13 (or later, adjust path if UM changes structure)

---

## How It Works

- **Guard script in `<head>`**: Defines a stub `S2.define` and `S2.require` if missing, so i18n can’t crash the page.
- **Blocks i18n files**: Removes UM’s `assets/libs/select2/i18n/*.js` from the scripts queue.
- **Forces core load**: Ensures `select2.full.min.js` is loaded before anything else, in `<head>`.

---

## Troubleshooting

1. **Still see the error?**  
- Clear all caches (page cache, object cache, CDN, optimizer).
- Hard refresh with `Ctrl+Shift+R` (or Cmd+Shift+R).
- Check console for `[UM Select2 Hard Fix]` log — it reports whether jQuery, Select2, and AMD are present.

2. **Optimization plugins**  
Exclude the following from aggregation/defer/async:
- `/wp-content/plugins/ultimate-member/assets/libs/select2/select2.full.min.js`
- `/wp-content/plugins/ultimate-member/assets/libs/select2/i18n/`

3. **UM updates**  
If UM changes its internal file paths, update the plugin’s reference to `select2.full.min.js`.

---

## License

MIT

---

## Contributing

Pull requests welcome. If UM changes its file structure in future updates, patches to update the Select2 path are appreciated.
