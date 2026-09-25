# `includes/logotypes/logotypes.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 23865 B
- **Liczba linii:** 492
- **Źródło:** `includes/logotypes/logotypes.php`

## Klasy i metody

### `PWELogotypes` — linia 3

  - `public __construct()` — linia 11
  - `public initVCMapLogotypes()` — linia 32
  - `public static exhibitors_catalog_checker($catalog_id, $logotypes_exhibitors_count = 21, $file_changer = null)` — linia 268
  - `public PWELogotypesOutput($atts, $content = null)` — linia 433

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 25

## Wybrane wywołania statyczne

- `PWElementAdditionalLogotypes::additionalArray()`
- `PWECommonFunctions::get_database_meta_data()`
- `CatalogFunctions::orderChanger()`
- `self::findColor()`
- `self::id_rnd()`
- `self::exhibitors_catalog_checker()`
- `PWElementAdditionalLogotypes::additionalOutput()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `current_user_can()`
- `shortcode_atts()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/logotypes_common.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
