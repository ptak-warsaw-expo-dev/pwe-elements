# `includes/katalog-wystawcow/main-katalog-wystawcow.php`

Plik first-party PWE Elements w kategorii `exhibitor-catalog`.

## Metadane

- **Kategoria:** `exhibitor-catalog`
- **Rozmiar:** 11277 B
- **Liczba linii:** 262
- **Źródło:** `includes/katalog-wystawcow/main-katalog-wystawcow.php`

## Klasy i metody

### `PWECatalog` — linia 3

  - `public __construct()` — linia 12
  - `public initVCMapElements()` — linia 32
  - `addingStyles()` — linia 53
  - `addingScripts($atts)` — linia 63
  - `public PWECatalogOutput($atts, $content = null)` — linia 77

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp_enqueue_scripts` — linia 24
- **action:** `wp_enqueue_scripts` — linia 25
- **action:** `init` — linia 27

## Wybrane wywołania statyczne

- `PWECommonFunctions::findPalletColorsStatic()`
- `CatalogFunctions::initVCMapPWECatalog()`
- `CatalogFunctions::vcMapPWECatalogCustom()`
- `PWECommonFunctions::get_database_meta_data()`
- `PWECommonFunctions::findColor()`
- `PWECommonFunctions::adjustBrightness()`
- `CatalogFunctions::findClassElements()`
- `CatalogFunctions::logosChecker()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `current_user_can()`
- `do_shortcode()`
- `shortcode_atts()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/catalog_functions.php'`
- `_once $slider_path`
- `_once plugin_dir_path(__FILE__) . $catalog_format`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
