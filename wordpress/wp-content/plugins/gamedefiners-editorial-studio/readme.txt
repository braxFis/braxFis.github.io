=== GameDefiners Editorial Studio ===
Contributors: gamedefiners
Tags: editorial, openai, reviews, gutenberg, writing
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 0.1.16
License: GPLv2 or later

A first-stage editorial plugin for GameDefiners.

== Version 0.1.1 includes ==
* Editorial Studio admin dashboard.
* Article draft generator for news, previews, reviews, guides and list articles.
* OpenAI Responses API integration, with model and editorial instruction settings.
* Creates normal WordPress posts as drafts. It never publishes automatically.
* A separate Gutenberg review score block with five centrally configurable criteria.
* Review criteria support: meets (1 point), does not meet (0 points), and not assessed yet.
* Existing review blocks preserve their stored criterion labels when global defaults are changed.
* OpenAI API key can be read from the GDES_OPENAI_API_KEY constant in wp-config.php.

== Not included yet ==
* RAWG integration. This version does not read, change or replace an existing RAWG plugin/database.
* Automatic web research or factual verification.
* Image retrieval or AI image generation.
* Editorial ideas feed or calendar.
* Background job queue for very long generation runs.

== Installation ==
1. Back up your WordPress site.
2. In WordPress, go to Plugins > Add New > Upload Plugin.
3. Upload gamedefiners-editorial-studio.zip and activate it.
4. Open Editorial Studio > Settings.
5. Add an OpenAI API key and save settings. You need an API key with API billing/credits enabled; a ChatGPT subscription alone does not provide API credits.
6. Open Editorial Studio > Skapa artikel to create a draft.
7. In a review post, use the block inserter and search for "GameDefiners: Recensionsbedömning".

== API key security ==
The API key is stored as a WordPress option if entered in the settings screen. For a production site, the preferred approach is to define GDES_OPENAI_API_KEY in wp-config.php and keep wp-config.php out of Git. Never put the key in JavaScript or commit it to GitHub.

== Editorial safety ==
Generated drafts are not fact-checked. Supply source notes and verify dates, platforms, quotes, prices and other claims before publishing. The plugin requires hands-on notes for review drafts and instructs the model not to invent play experience or review scores.

== Notes ==
* The default model ID is gpt-6.1-terra and can be changed in settings. Model availability and API pricing may change.
* The block is a dynamic Gutenberg block. If the plugin is deactivated, its rendered front-end output may disappear until the plugin is reactivated.
\n== Version 0.1.2 WP-CLI ==\nRun from the WordPress installation directory:\nwp gamedefiners generate --title="Skyrim: nyheter" --brief="Skriv en nyhetsartikel utifrån mina verifierade uppgifter." --type=news --author=1\nFor reviews, provide --hands-on-notes. CLI uses the same generator as the admin form. Creates drafts only. WP-CLI must be installed separately. No RAWG/IGDB integration or automatic game lookup is included.\n

== Version 0.1.16 ==
TMDB-filmsökning och filmunderlag i artikelverkstaden. Filmreporter via ämnesval, säkra serverbaserade TMDB-inställningar och gemensam TMDB-klient för release-scan. Se TMDB-README.md.
