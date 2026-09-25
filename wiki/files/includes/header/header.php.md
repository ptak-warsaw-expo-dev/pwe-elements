# `includes/header/header.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 15583 B
- **Liczba linii:** 345
- **Źródło:** `includes/header/header.php`

## Klasy i metody

### `PWEHeader` — linia 3

  - `public __construct()` — linia 11
  - `public headerFunctions()` — linia 32
  - `public static multi_translation($key)` — linia 36
  - `public PWEHeaderOutput($atts, $content = null)` — linia 62

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `vc_before_init` — linia 27

## Wybrane wywołania statyczne

- `self::findColor()`
- `self::id_rnd()`
- `self::lang_pl()`
- `self::multi_translation()`
- `self::transform_dates()`
- `self::isTradeDateExist()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `add_action()`
- `add_shortcode()`
- `get_locale()`
- `get_option()`
- `update_option()`
- `shortcode_atts()`
- `get_the_title()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__DIR__) . 'logotypes/classes/logotypes_common.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_functions.php'`
- `s/header.json'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_default.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_simple.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_badge.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_squares.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_video.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_glass.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/header_glass_v2.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
