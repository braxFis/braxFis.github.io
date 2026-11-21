# Game data configuration

Set these in the hosting environment before deploying:
- DB_HOST, DB_NAME, DB_USER, DB_PASS (the existing production database).
- IGDB_CLIENT_ID and IGDB_CLIENT_SECRET (Twitch application credentials).
- RAWG_DISABLED=1 to use IGDB directly through fallback, or 0 with RAWG_API_KEY.
- OPENAI_API_KEY if editorial generation is used.
- APP_DEBUG=0 in production.

For local development, copy .env.example to config/.env and fill it in.
The bootstrap loads config/.env and then .env without overriding server variables.
Local environment files are ignored by Git.

Previously committed credentials must be revoked/rotated with Twitch, RAWG, OpenAI
and the database administrator as applicable. Removing files from the current tree
does not erase Git history. Do not reuse exposed values.

Run offline regression tests with:
    php misc/cms/tests/game_data_regression.php

The Docker image installs curl, pdo_mysql and zip. Apache serves misc/cms/public and routes game detail URLs to index.php.
