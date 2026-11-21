# GameDefiners – WP-CLI-kommandon

Den här README-filen skiljer på **verifierade kommandon i uppladdad källkod** och **kommandon från senare versioner som inte kunnat kontrolleras här**.

## Förutsättningar

- WordPress och WP-CLI är installerade.
- Kör kommandona från WordPress rotkatalog, eller lägg till `--path="C:\sökväg\till\wordpress"`.
- Ersätt `--author=1` om en annan WordPress-användare ska äga utkasten.
- API-nycklar och relevanta inställningar måste vara konfigurerade.

## Editorial Studio – verifierat i uppladdad version 0.1.6

### `gamedefiners generate`

Skapar ett AI-assisterat WordPress-utkast från en manuellt angiven titel och brief.

```powershell
php C:\wp-cli\wp-cli.phar gamedefiners generate --title="Minecraft Beginner Guide" --brief="Write an introduction for new players" --type=guide --language=en --author=1
```

Tillgängliga argument:

| Argument | Funktion |
| --- | --- |
| `--title` | Artikelrubrik |
| `--brief` | Redaktionell instruktion |
| `--type` | `news`, `preview`, `review`, `guide`, `list` (standard `news`) |
| `--language` | `sv` eller `en` (standard `sv`) |
| `--length` | `short`, `medium`, `long` (standard `medium`) |
| `--tone` | Valfri ton |
| `--facts` | Fakta som du själv tillför |
| `--hands-on-notes` | Egna testanteckningar; krävs för recensioner |
| `--author` | WordPress-användar-ID med rättighet att redigera inlägg |

### `gamedefiners releases scan`

Söker efter releaser och skapar artikelidéer som WordPress-utkast i **version 0.1.6**. Denna äldre version skickar inte automatiskt resultatet till AI-reportern.

Förhandsgranska TMDB-filmer utan att skapa utkast:

```powershell
php C:\wp-cli\wp-cli.phar gamedefiners releases scan --source=tmdb --days=90 --max=10 --min-popularity=7 --dry-run --author=1
```

Skapa högst ett enkelt utkast från TMDB i 0.1.6:

```powershell
php C:\wp-cli\wp-cli.phar gamedefiners releases scan --source=tmdb --days=90 --max=1 --min-popularity=7 --author=1
```

Tillgängliga argument:

| Argument | Funktion |
| --- | --- |
| `--source` | `rawg`, `tmdb`, `demo` eller `file` (standard `rawg`) |
| `--days` | Sökperiod, 1–365 dagar (standard 90) |
| `--max` | Max antal, 1–50 (standard 10) |
| `--min-popularity` | Minsta TMDB-popularitet (standard 10) |
| `--dry-run` | Visa urval utan att skapa utkast |
| `--demo` | Använd exempeldata |
| `--file` | Läs releaser från lokal JSON-fil |
| `--author` | WordPress-författare (standard 1) |

## Senare Editorial Studio-versioner (0.1.7–0.1.8)

I tidigare arbete beskrevs ett nytt `--mode=mainstream` och `--mode=indie` samt AI-generering från Release Scan. **Dessa argument och beteenden finns inte i den uppladdade 0.1.6-koden och har inte verifierats mot en ZIP med 0.1.8.** Om du har 0.1.8 installerat är detta det tidigare föreslagna testkommandot:

```powershell
php C:\wp-cli\wp-cli.phar gamedefiners releases scan --source=tmdb --mode=mainstream --days=90 --max=1 --author=1
```

Kör inte kommandot i produktion förrän du har kontrollerat version och beteende. Utan `--dry-run` kan det skapa utkast och orsaka AI-kostnader.

## Senare Editorial Studio – uppladdad version 0.3.0

Den uppladdade **Editorial Studio 0.3.0** innehåller `README.txt`, inte `README.md`, och ingen verifierbar `gdstudio generate`-CLI-implementation. AI-generering sker i denna version via WordPress-admin.

### Föreslaget i Editorial Studio 0.3.1 (inte verifierat här)

I tidigare arbete beskrevs ett nytt automatiskt RAWG-läge i GD Studio 0.3.1. För att dokumentera det som faktiskt fungerande behövs 0.3.1-ZIP-filen.

Förhandsgranskning enligt det tidigare förslaget:

```powershell
php C:\wp-cli\wp-cli.phar gamedefiners generate --auto --source=rawg --type=preview --max=3 --days=90 --dry-run --author=1
```

Generera ett utkast enligt det tidigare förslaget:

```powershell
php C:\wp-cli\wp-cli.phar gamedefiners generate --auto --source=rawg --type=preview --max=1 --days=90 --author=1
```

Dessa kommandon förutsätter **GD Studio 0.3.1 med den föreslagna funktionen** och en fungerande RAWG-anslutning. RAWG uppgavs vara nere vid senaste testet. Automatiska guider och nyheter ingår inte i det föreslagna läget; endast previews.

## Hjälp och felsökning

Se installerade WP-CLI-kommandon:

```powershell
php C:\wp-cli\wp-cli.phar help gamedefiners
php C:\wp-cli\wp-cli.phar help gamedefiners generate
php C:\wp-cli\wp-cli.phar help gamedefiners releases scan
```

Om `gamedefiners` finns i din installerade version:

```powershell
php C:\wp-cli\wp-cli.phar help gamedefiners
```

**Obs:** WP-CLI-kommandon måste köras mot rätt WordPress-installation, med rätt plugin aktiverat. För schemaläggning i Windows kan kommandona köras via Aktivitetsschemaläggaren, men testa först manuellt med `--dry-run` där det stöds.
