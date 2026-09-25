# `elements/two_cols.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 45955 B
- **Liczba linii:** 1158
- **Źródło:** `elements/two_cols.php`

## Klasy i metody

### `PWElementTwoCols` — linia 7

  - `public __construct()` — linia 13
  - `public catalogFunctions()` — linia 17
  - `public pweProfileButtons()` — linia 21
  - `public static initElements()` — linia 28
  - `public static multi_translation($key)` — linia 455
  - `public static output($atts)` — linia 474

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `PWECommonFunctions::decode_clean_content()`
- `self::adjustBrightness()`
- `self::multi_translation()`
- `PWEProfileButtons::getImagesFromDirectory()`
- `PWECommonFunctions::get_database_logotypes_data()`
- `CatalogFunctions::logosChecker()`
- `PWECommonFunctions::id_rnd()`
- `PWESliderScripts::sliderScripts()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `get_locale()`
- `shortcode_atts()`
- `do_shortcode()`
- `wp_get_attachment_image_url()`
- `esc_url()`
- `current_user_can()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/catalog_functions.php'`
- `_once plugin_dir_path(__FILE__) . 'profile/classes/profile-buttons.php'`
- `_once plugin_dir_path(__FILE__) . '/../scripts/slider.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
