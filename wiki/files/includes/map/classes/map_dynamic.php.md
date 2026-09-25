# `includes/map/classes/map_dynamic.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 10368 B
- **Liczba linii:** 225
- **Źródło:** `includes/map/classes/map_dynamic.php`

## Klasy i metody

### `PWEMapDynamic` — linia 7

  - `public __construct()` — linia 13
  - `public static multi_translation($key)` — linia 17
  - `public static ordinal_suffix($n)` — linia 36
  - `public static get_custom_title($edition)` — linia 47
  - `public static output($atts)` — linia 96

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::multi_translation()`
- `self::ordinal_suffix()`
- `self::get_custom_title()`

## API WordPress rozpoznane heurystycznie

- `get_locale()`
- `shortcode_atts()`
- `do_shortcode()`
- `plugin_dir_path()`

## Dołączane pliki / wyrażenia include

- `s/map.json'`
- `_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php'`
- `_once plugin_dir_path(__FILE__) . 'presets/map_dynamic_preset_1.php'`
- `_once plugin_dir_path(__FILE__) . 'presets/map_dynamic_preset_2.php'`
- `_once plugin_dir_path(__FILE__) . 'presets/map_dynamic_preset_3.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
