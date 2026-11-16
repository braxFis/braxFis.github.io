COMA Game Articles v1.2.0
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

FILMKOPPLINGAR (NYTT I 1.2.0)
1. Uppdatera samma tillägg: COMA Game Articles.
2. Inställningar -> COMA Game Articles -> TMDB API Read Access Token.
3. Inlägg -> Redigera -> panelen "COMA: koppla till film".
4. Sök filmtitel, välj rätt film/årtal och uppdatera artikeln.
5. "Ta bort filmkoppling" tar bort endast filmkopplingen när artikeln sparas.

Varje artikel kan ha en spelkoppling OCH en separat filmkoppling. Befintliga RAWG/IGDB-ID:n ändras inte. Film-ID, källa och titel sparas i _coma_movie_id, _coma_movie_source och _coma_movie_title. Snabbredigering utan panelens fält behåller kopplingen.

Publika filmartiklar:
GET https://din-wordpress-domän.se/wp-json/coma/v1/movies/tmdb/11/articles
Endast publicerade inlägg utan lösenord lämnas ut. Utkast, privata inlägg och lösenordsskyddade artiklar lämnas inte ut.

TMDB-token används endast på servern, skickas som Bearer-header och visas aldrig i JavaScript eller sökresultat. Du kan ange COMA_TMDB_READ_TOKEN i wp-config.php i stället. Tomt fält behåller tidigare token; kryssrutan tar bort sparad token. Sökresultat cachas i fem minuter och ny token ger en ny cacheidentitet. Ingen reservkälla till spel används för filmer.

Om du använder COMA Content Export: uppdatera även det till version 1.1.0 för att få med movie-fältet i synkflödet. Befintligt game-fält och schema 1 behålls. Konsumenten måste läsa movie-fältet och ha filmvyer för att visa filmkopplingar; det skapas inte automatiskt av WordPress-tillägget.

Inga filmartiklar genereras och inga affischer laddas ned. Du kopplar själv en film till en befintlig artikel. Följ TMDB:s API-, attribuerings- och kommersiella användningsvillkor. This product uses the TMDB API but is not endorsed or certified by TMDB.
