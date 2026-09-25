# `includes/logotypes/classes/logotypes_common.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 84360 B
- **Liczba linii:** 1662
- **Źródło:** `includes/logotypes/classes/logotypes_common.php`

## Klasy i metody

### `PWElementAdditionalLogotypes` — linia 3

  - `public static additionalArray()` — linia 5
  - `public static getLangDesc($meta)` — linia 221
  - `public static getLangLink($data)` — linia 253
  - `public static multi_translation($key, $plural = false)` — linia 280
  - `public static additionalOutput($atts, $el_id, $logotypes = null, $exhibitors_logotypes = null)` — linia 302

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `PWESliderScripts::sliderScripts()`
- `PWECommonFunctions::get_database_fairs_data()`
- `PWECommonFunctions::get_database_logotypes_data()`
- `PWECommonFunctions::get_database_meta_data()`

## API WordPress rozpoznane heurystycznie

- `get_locale()`
- `shortcode_atts()`
- `wp_get_attachment_url()`
- `plugin_dir_path()`
- `wp_enqueue_style()`
- `plugins_url()`
- `wp_enqueue_script()`

## Dołączane pliki / wyrażenia include

- `s/logotypes_common.json'`
- `_once plugin_dir_path(dirname(dirname(__DIR__))) . 'scripts/slider.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
