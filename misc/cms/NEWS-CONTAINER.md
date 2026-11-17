# News-container: förberedelse

Bygg från repots rot med `docker build -f misc/Dockerfile -t moviezimmer-news:local misc`.
Byggkontexten är begränsad till misc, där CMS:et och de gemensamma resurserna för /misc/css, /misc/js och /misc/img finns. Apache serverar misc/cms/public. WordPress körs separat.

Den här imagen är ett utkast, inte godkänd för publik drift. Säkerställ att de olika admin- och testvägarna har rätt åtkomstkontroll; granska även befintliga fristående PHP-filer i public. Kör inte produktionsdata innan behörighet, HTTPS, säkerhetskopiering och återställning har testats.

Lösenord och API-nycklar ska tillföras vid körning, inte checkas in eller byggas in. CMS:et använder egen MySQL-konfiguration via DB_HOST, DB_NAME, DB_USER och DB_PASS. WordPress-bakuppens databas är inte automatiskt CMS:ets databas; identifiera och migrera CMS-data separat.

Uploads, guides/storage och WordPress-spegeln är data. Inventera dem och konfigurera beständig lagring och backup. WORDPRESS_URL ska peka på publicerad WordPress och WORDPRESS_MIRROR_FILE på en beständig plats utanför public. Sync-kommandot måste schemaläggas separat. En ny Docker-image eller ny GitHub-kopia flyttar inte databas, uppladdningar eller live-spegel.

Enbart health.php kontrolleras av containerns röktest. Två gamla testfiler beror på WordPress-pluginer i gamla repot; flytta deras tester till WordPress-repot eller tillhandahåll test-fixtures från rätt version. Kör inga externa produktionsanrop i testerna.
