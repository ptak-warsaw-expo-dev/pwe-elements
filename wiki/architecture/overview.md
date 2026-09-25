---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# Architektura — overview

## Warstwy

1. `pwelements.php` — bootstrap, inicjalizacja klas, assetów, compatibility layer i updatera.
2. `PWE System compatibility` — wybór pomiędzy implementacją PWE System a lokalnym fallbackiem.
3. `elements/pwelements-options.php` — historyczny system elementów WPBakery oparty o klasę bazową `PWElements` i liczne klasy `PWElement*`.
4. `includes/*` — większe moduły domenowe: rejestracje, generator wystawców, katalog, kalendarz, posty, header, logotypy, media, store itd.
5. `Gravity Forms` — formularze, wpisy, feedy, powiadomienia i lifecycle rejestracji.
6. `direct HTTP / AJAX / CLI` — część procesów ma własne wejścia poza standardowym renderem strony.
7. `legacy / dormant` — kod utrzymywany w repozytorium, który nie zawsze jest ładowany przez bieżący bootstrap.

## Kluczowa zasada

Call graph pojedynczego pluginu nie opisuje całej rzeczywistości. `PWECommonFunctions` może oznaczać lokalną implementację z `pwefunctions.php` albo alias do `PWE_System_Functions`, zależnie od aktywności PWE System. Dlatego późniejszy cross-plugin graph powinien rozróżniać `resolved_local`, `resolved_cross_plugin`, `fallback` i `delegated`.

## Główne obszary biznesowe

- prezentacja i konfiguracja elementów stron targowych,
- dane targów i shortcody,
- rejestracje odwiedzających/wystawców,
- generatory zaproszeń i identyfikatorów,
- Gravity Forms + QR + powiadomienia,
- katalog wystawców,
- kalendarz wydarzeń i konferencji,
- posty/news,
- integracje z innymi pluginami PWE.
