GameDefiners Editorial Studio 0.1.15 – Bildhantering

Bygger på Editorial Studio 0.1.14 (menu-fix) och behåller befintlig bulkimport.
GD Studio ändras inte.

Installation: WordPress → Tillägg → Lägg till nytt → Ladda upp tillägg → välj ZIP. Uppdatera befintligt Editorial Studio.

Användning:
1. Öppna Editorial Studio → Bilder.
2. Välj artikel i listan (senaste 100 artiklar utan utvald bild).
3. Välj antingen RAWG-bildsökning eller AI-illustration.
4. Granska den importerade/genererade bilden och klicka Godkänn som utvald bild.
5. Bildinställningar finns längst ner på sidan (kräver administratörsrättigheter).

API-nycklar:
OpenAI: samma API-nyckel som Editorial Studio → Inställningar.
RAWG: ange i Bildinställningar eller definiera RAWG_API_KEY i wp-config.php.

Kostnad: API-anropet debiteras av OpenAI enligt deras aktuella prislista. De uppskattade USD-beloppen i pluginet är endast vägledande. Kostnadsspärren bygger på uppskattning och är inte en hård kontogräns. Kontrollera aktuell modellprissättning före massanvändning.

Viktigt:
- AI-genererade bilder ska märkas som illustrationer, inte screenshots.
- RAWG:s bilder är inte automatiskt fria att publicera. Kontrollera licens/rättigheter.
- Ingen bildgenerering startar automatiskt av bulkimport.
- Bild-API kräver att ditt OpenAI-konto har åtkomst till vald modell.
- AI-bildens API-förfrågan använder liggande 1536x1024; det är 3:2, inte exakt 16:9. WordPress kan beskära till 16:9 efteråt.
- Befintliga artiklar och bilder raderas inte vid uppgradering.
- Testa på staging/ta backup före installation. Inte testat mot en körande WordPress-installation här.
