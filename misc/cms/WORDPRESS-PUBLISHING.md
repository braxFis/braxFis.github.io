# WordPress → COMA CMS

WordPress är redaktionens källa. CMS:et hämtar en lokal läskopia av offentligt
publicerade vanliga inlägg. Befintliga manuella CMS-artiklar och deras tabeller
ändras inte. Nyheter/recensioner/förhandstittar behåller sina WordPress-kategorier.

## Flöde

1. Redigera, välj spel med COMA Game Articles och publicera i WordPress.
2. Ett CLI-kommando hämtar exporten i sidor om 50 artiklar.
3. Alla sidor måste lyckas, ha samma exportversion och stämma med totalantalet.
4. En lokal JSON-kopia byts atomiskt när hela synken är klar.
5. CMS:et visar listan på /articles och fulltext på /articles/WORDPRESS_ID.
   Spelsidor visar artiklar med matchande spelkälla och ID.

WordPress-ID används som stabil identitet för en konfigurerad WordPress-sajt.
Samma artikel uppdateras vid omsynk; den skapas inte igen.
IGDB 42 och RAWG 42 är olika spel och blandas aldrig.

## Installation

1. Kopiera katalogen wordpress/wp-content/plugins/coma-content-export till
   wp-content/plugins på WordPress-servern. Aktivera **COMA Content Export** i
   WordPress → Tillägg. COMA Game Articles behövs bara för spelkopplingen.
2. Kontrollera den offentliga exporten:
   https://DIN-WORDPRESS/index.php?rest_route=/coma-sync/v1/articles&page=1
   Ange WordPress webbplatsadress (home URL), inklusive eventuell underkatalog,
   inte /wp-admin eller REST-adressen. Synken använder index.php?rest_route= och kräver inte omskrivning av /wp-json/-adresser.
3. Ställ in CMS-serverns miljövariabler (eller config/.env lokalt):
   WORDPRESS_URL=https://DIN-WORDPRESS
   WORDPRESS_MIRROR_FILE=/srv/coma-data/wordpress/articles.json
   WORDPRESS_ALLOW_HTTP=0
4. PHP behöver curl och dom. Docker installerar dom.
   Kör composer install i misc/cms om autoloadern inte redan är installerad.
5. Ge användaren som kör CLI och webbservern läsrätt till samma katalog.
   Katalogen måste ligga utanför public/. Använd en beständig volym i Docker/Render.
   Om WORDPRESS_MIRROR_FILE lämnas tom används misc/cms/var/wordpress/articles.json.
6. Förhandsgranska från misc/cms:
   php bin/sync-wordpress.php
   Resultatet visar added, updated, removed och total. Inget innehåll publiceras lokalt.
7. För över innehållet:
   php bin/sync-wordpress.php --apply
8. Öppna /articles i CMS:et. Lägg gärna länken i sajtens meny.

Lokalt med HTTP: ange WORDPRESS_ALLOW_HTTP=1 uttryckligen.
WORDPRESS_URL kan lämnas tom för att stänga av integrationen.

## Schemaläggning

Lägg detta i CMS-serverns cron (exempel, anpassa sökvägen):
    */5 * * * * cd /srv/coma/misc/cms && /usr/bin/php bin/sync-wordpress.php --apply

Det betyder normalt upp till fem minuters fördröjning efter publicering,
redigering eller avpublicering. Ingen personlig ChatGPT-automation behövs.
I Docker måste schemaläggaren köra kommandot i appens miljö och skriva till
samma beständiga volym som webbservern läser.

CLI-körningen är låst så att två synkar inte skriver samtidigt. Misslyckade
HTTP-anrop, ofullständiga sidor och ändrad exportversion avbryter hela körningen
med exitkod 1 och behåller senaste fungerande kopian. Övervaka dessa fel;
synken återförsöker vid nästa schemalagda körning.

## Publicering, bilder och begränsningar

- Bara publicerade, offentliga inlägg utan lösenord exporteras. Utkast, privata
  inlägg, användarkonton, lösenord och API-nycklar överförs inte.
- Material som avpubliceras, blir lösenordsskyddat eller raderas försvinner
  ur CMS-kopian vid nästa fullständigt lyckade synk. Vid ett avbrott kan den
  gamla offentliga kopian ligga kvar tills synken fungerar igen.
- WordPress renderar Gutenberg-block. CMS:et behåller grundläggande text,
  rubriker, listor, tabeller, länkar och bilder med en separat HTML-tillåtelselista.
  Skript, formulär, CSS, iframes och interaktiva WordPress-block följer inte med.
  Recensionsblockens renderade text kan följa med; egna CSS-effekter gör det inte.
- Bilder refererar fortfarande till WordPress mediabibliotek. WordPress måste
  vara tillgängligt för bilder. Detta är löpande publiceringssynk, inte en
  komplett migrering som gör att WordPress kan stängas av.
- Exporten är offentlig och avsedd för en vanlig offentlig redaktionell sajt.
  Sajter med medlems-/betalväggsplugin behöver en separat behörighetsintegration
  innan detta plugin aktiveras, eftersom publiceringsstatus inte beskriver alla
  tredjepartspluginers åtkomstregler.
- Första versionen stöder en WordPress-sajt, vanliga inlägg, max 10 000 artiklar,
  4 MiB per exportsida och cirka 32 MiB i den lokala kopian.
  Större arkiv bör använda en databasspegel och inkrementell synk.
- Importerade artiklar redigeras i WordPress; lokala JSON-ändringar skrivs över.
- Besökarnas sidvisningar gör inga WordPress-API-anrop. Synken hämtar
  ceil(antal inlägg / 50) + 1 sidor (minst två anrop inklusive versionskontrollen).

## Test

    php misc/cms/tests/wordpress_mirror_regression.php
    php misc/cms/tests/wordpress_export_regression.php

Testerna använder fixtures och lokala filer, inte någon driftsatt WordPress-sajt.
De täcker sidindelning, uppdateringar utan dubbletter, avpublicering, misslyckad
synk, versionskonflikter, spel-ID-separation, HTML-rening och skyddade inlägg.
