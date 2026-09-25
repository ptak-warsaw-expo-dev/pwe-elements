# `elements/countdown.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 33728 B
- **Liczba linii:** 732
- **Źródło:** `elements/countdown.php`

## Klasy i metody

### `PWElementMainCountdown` — linia 7

  - `public __construct()` — linia 14
  - `public static initElements()` — linia 27
  - `public static multi_translation($key)` — linia 195
  - `private static getRightData($count)` — linia 218
  - `public static main_timer()` — linia 239
  - `public static output($atts)` — linia 277

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::multi_translation()`
- `PWECommonFunctions::json_fairs()`
- `self::findColor()`
- `self::adjustBrightness()`
- `self::getRightData()`
- `self::main_timer()`
- `PWECountdown::output()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `get_locale()`
- `do_shortcode()`
- `shortcode_atts()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'js/countdown.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
