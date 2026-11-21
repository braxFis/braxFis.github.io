GameDefiners Studio v0.3.0
=========================

Installation/update:
1. WordPress > Tillägg > Lägg till nytt > Ladda upp tillägg.
2. Upload gamedefiners-studio.zip and replace the existing version when prompted.
3. Activate if necessary. Existing settings are preserved.
4. GD Studio > Guides Studio: enter a game, click "Hämta uppdrag", select a mission and generate a draft.
5. Configure the OpenAI API key under GD Studio > Inställningar.

The mission dropdown is AI-generated, not sourced from a verified mission database. It can be incomplete or inaccurate and may not recognize every game. Main-story missions/chapters only. Each lookup may incur API costs; successful results are cached for 24 hours.
Guides still require author verification. Editorial Studio is unchanged.

Batch generation: Guides Studio > Hämta uppdrag > Generera alla uppdrag som utkast. One sequential API request per mission. Shows progress and links, can stop after current mission, skips existing drafts from previous batch runs. Confirm estimated volume before starting. Keep the admin tab open. Each mission costs API usage. AI mission list and generated guides must be fact-checked.

GD Studio 0.3.1: WP-CLI RAWG auto-preview
Set RAWG_API_KEY in wp-config.php or environment; configure OpenAI under GD Studio > Inställningar.
From WordPress root: php C:\wp-cli\wp-cli.phar gdstudio generate --auto --source=rawg --type=preview --max=3 --days=90 --dry-run --author=1
Remove --dry-run to create WordPress drafts (OpenAI API costs apply). No auto-guides or auto-news.
