---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# Bootstrap i lifecycle

## 1. Start

WordPress ładuje `pwelements.php`, a na końcu pliku tworzona jest instancja `PWElementsPlugin`.

## 2. Konstruktor

`PWElementsPlugin::__construct()`:

- ustawia strefę `Europe/Warsaw`,
- rejestruje czyszczenie cache po aktualizacji,
- uruchamia `initClasses()`,
- uruchamia updater,
- na `init` rejestruje shortcody kolorów,
- na `wp_head` emituje zmienne CSS,
- enqueue'uje główne style, Slick i Swiper,
- rejestruje wykluczenia JS dla WP Rocket,
- filtruje treść wpisów przez `add_date_to_post()`.

## 3. `initClasses()`

Najpierw wykonywane jest `initSystemCompatibility()`. Dopiero potem ładowane są klasy domenowe i elementy. Kolejność jest istotna, bo starsze moduły oczekują istnienia `PWECommonFunctions` oraz shortcode'ów `[pwe_*]`.

## 4. Warunkowe moduły

Kalendarz i conference calendar są ładowane tylko, jeśli `PWECommonFunctions::checkForMobile()` nie wskazuje mobile. Część innych modułów ma require zakomentowane i jest oznaczona jako `dormant`.

## 5. Updater

`init()` ładuje bundlowany Plugin Update Checker i konfiguruje aktualizację repozytorium zgodnie z kodem wtyczki.
