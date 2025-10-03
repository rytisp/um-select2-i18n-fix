=== UM Select2 i18n Fix ===
Contributors: rytispetkevicius
Tags: select2, ultimate member, javascript, i18n, compatibility
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.2
Stable tag: 1.1.2
License: (C) 2025 All Rights Reserved

Fixes Select2 i18n “reading 'define'” crashes by loading Select2 full early, blocking i18n bundles, and adding an early AMD guard.

== Description ==

Some themes/plugins enqueue Select2 i18n files before AMD exists, causing errors like:
> Cannot read properties of undefined (reading 'define')

This plugin:

1. Injects an early AMD no-op guard in `<head>`.
2. Blocks Select2 i18n bundles (including `en.js`) from being registered/printed.
3. Force-loads the **full** Select2 build early so AMD is available.

Works out of the box with Ultimate Member’s bundled Select2. Optional code (commented) shows how to block other sources too.

== Installation ==

1. Upload the plugin and activate it.
2. No settings. Visit a front-end page that uses Select2 to confirm the error is gone.

== Frequently Asked Questions ==

= Does this affect the admin area? =
No. The selective blocking and early load occur on the front end only.

= Will this break localization strings in Select2? =
It removes the i18n bundles, relying on the full build’s safe defaults. Most sites do not need the extra locale files, and this avoids the AMD race.

= Can I block other Select2 i18n paths? =
Yes—see the commented regex in the code to broaden matching.

== Changelog ==

= 1.1.2 =
* Set `Tested up to: 6.8`.
* Align license to GPLv2 or later in header & readme.
* Unify plugin name across header/readme (“UM Select2 i18n Fix”).
* Trimmed short description to ≤150 chars.

= 1.1.1 =
* Initial public release.

== Upgrade Notice ==

= 1.1.2 =
Compliance fixes for WordPress.org review (tested up to, license, naming, short description).
