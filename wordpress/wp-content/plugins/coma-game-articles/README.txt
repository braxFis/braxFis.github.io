COMA Game Articles v1.0.0
==========================

INSTALLATION
1. WordPress admin -> Tillägg -> Lägg till nytt -> Ladda upp tillägg.
2. Välj coma-game-articles.zip och aktivera.
3. Öppna Inställningar -> COMA Game Articles.
4. Lägg in RAWG API-nyckel och IGDB/Twitch Client ID + Client Secret. Om RAWG är nere räcker IGDB-uppgifterna.
5. Öppna Inlägg -> Redigera. I panelen "COMA: koppla till spel", sök på titel och välj rätt spel. Publicera/uppdatera.

SÖKNING OCH FALLBACK
- Försöker RAWG först (4 sekunders timeout), därefter IGDB vid nätverksfel, HTTP-fel, ogiltig JSON eller saknad nyckel.
- Om RAWG returnerar en giltig tom lista betraktas det inte som driftstopp. Ändra sökord, eller välj IGDB först i inställningarna.
- Sparar source + id + title. RAWG och IGDB har olika ID-rymder.
- Sökresultat cachas i 5 minuter, inklusive vilken källa som svarade.
- API-credentials skickas endast från WordPress-servern till respektive leverantör. Sökningen kräver inloggad redaktörsbehörighet.

PUBLICERADE ARTIKLAR
GET https://din-wordpress-domän.se/wp-json/coma/v1/games/igdb/123/articles
GET https://din-wordpress-domän.se/wp-json/coma/v1/games/rawg/123/articles
Äldre RAWG-only URL stöds också:
GET https://din-wordpress-domän.se/wp-json/coma/v1/games/123/articles

VIKTIG MVC-INTEGRATION
Detta tillägg kopplar WordPress-artiklar till ett game source + game ID.
Din befintliga MVC-route /games/{id} använder eventuellt RAWG-ID även när den hämtar spel från IGDB.
Då behövs ID-mappning (eller att MVC skickar IGDB-ID) för att hitta samma artiklar.
Det görs inte automatiskt här: RAWG-ID kan inte härledas från IGDB-ID bara genom att ändra API-källa.

SÄKERHET / BEGRÄNSNINGAR
- REST-endpointen är publik, men ger enbart publicerade inlägg.
- En artikel kopplas till ETT spel i denna första version.
- Stöder vanliga WordPress-inlägg (post), inte custom post types ännu.
- Om du byter källa för ett tidigare kopplat spel behöver du välja spel igen, eller bygga stabil ID-mappning i MVC.
- WordPress lagrar API-uppgifter i options-tabellen. På extra känsliga installationer kan du i stället lägga COMA_RAWG_API_KEY, COMA_IGDB_CLIENT_ID och COMA_IGDB_CLIENT_SECRET i wp-config.php.
