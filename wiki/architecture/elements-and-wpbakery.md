---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# Elementy i WPBakery

PWE Elements posiada dwie historycznie rozwijane warstwy elementów:

- `elements/*` — duży zestaw klas `PWElement*` spiętych przez `elements/pwelements-options.php`,
- `includes/*` — większe samodzielne moduły rejestrujące własne shortcody i mapy `vc_map()`.

`PWElements` jest centralną klasą bazową/koordynującą dla wielu starszych elementów. `pwelements-options.php` ładuje ich pliki, agreguje parametry `initElements()` i buduje konfigurację elementu WPBakery.

Większe moduły, np. `PWEExhibitorGenerator`, `PWERegistration`, `PWECatalog`, `PWEHeader`, `PWENews`, rejestrują własne shortcody i konfiguracje WPBakery w konstruktorach.

To oznacza, że wejście biznesowe może mieć kilka poziomów: shortcode WPBakery → metoda output → dynamicznie wybrana klasa/preset → Gravity Forms / DB / zewnętrzna integracja.
