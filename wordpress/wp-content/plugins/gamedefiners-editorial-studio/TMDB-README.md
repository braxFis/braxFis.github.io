# Editorial Studio 0.1.16 – TMDB i artikelverkstaden

1. Uppdatera det befintliga tillägget **GameDefiners Editorial Studio**.
2. Öppna **Editorial Studio → Inställningar → TMDB – filmunderlag**.
3. Ange TMDB:s **API Read Access Token**, spara. Tomt fält behåller sparad token; kryssrutan tar bort den.
4. Öppna **Skapa artikel**, välj **Ämne: Film (TMDB)** och sök på filmtitel.
5. Välj rätt film och årtal. Ange rubrik, artikelvinkel och eventuella verifierade källor.
6. Generera utkast. TMDB-underlaget hämtas på servern, och filmreporterns befintliga instruktioner används. Inget publiceras automatiskt.

Token kan även anges som `GDES_TMDB_READ_TOKEN` i wp-config.php eller `TMDB_API_READ_TOKEN` i servermiljön. Dessa går före sparad token. Den befintliga `TMDB_API_KEY`-konfigurationen fungerar fortfarande. WP-CLI:s `gamedefiners releases scan --source=tmdb` använder nu samma klient och kan därför läsa token från inställningarna.

TMDB-underlaget omfattar synopsis, primärt releasedatum, genre, regissör, skådespelare, produktionsbolag och speltid när dessa finns. Primärt releasedatum är inte automatiskt svensk premiär; produktionsbolag är inte automatiskt distributör. Metadata är bakgrund och ersätter inte journalistisk faktakontroll. Recensioner kräver egna visningsanteckningar; spelens recensionskriterier skickas inte till filmreportern.

Vald film sparas i artikelns `_gdes_tmdb_id`, separat från spelkopplingar. Det kopplar inte automatiskt artikeln till en VOD-filmsida. Befintliga RAWG/IGDB-moduler, COMA Game Articles och Content Export ändras inte.

Inga TMDB-affischer hämtas eller importeras. API-åtkomst ger inte automatiskt rätt att publicera bilder. Följ TMDB:s användnings-, kommersiella licens- och attribueringsvillkor på https://developer.themoviedb.org/docs/faq och https://developer.themoviedb.org/docs/logos-and-attribution.
