Editorial Studio 0.1.13 – Bulkimport från 2018

Installera som uppdatering till Editorial Studio 0.1.12.
Öppna Editorial Studio > Bulkimport.
Ladda upp Spelarkiv_2012-2025_Pivot.xlsx. Första bladet (Speldata) läses.
Endast 2018–2025 importeras (80 spel i den tillhandahållna filen).
Exakt samma speltitel som redan finns som WordPress-inlägg hoppas över.
Klistra in generell artikelvinkel en gång, välj News eller Preview och spara.
Starta 1–10 utkast per omgång. Ett AJAX-anrop per artikel, håll sidan öppen.
Varje utkast skapas med GDES_Generator::generate från 0.1.12.
Ingen automatisk research och ingen automatisk publicering.
Fakta och källanteckningar är valfria. Granska alla AI-fakta före publicering.
Recensioner och guider ingår inte i massgenereringen eftersom de kräver eget underlag.
Fel stoppar körningen, och berörd rad markeras med fel för att undvika oändliga omförsök.
XLSX kräver PHP ZipArchive och SimpleXML. CSV fungerar utan dessa.
