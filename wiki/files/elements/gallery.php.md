# `elements/gallery.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 30916 B
- **Liczba linii:** 624
- **Źródło:** `elements/gallery.php`

## Klasy i metody

### `PWElementHomeGallery` — linia 7

  - `public __construct()` — linia 15
  - `public static initElements()` — linia 28
  - `private static mainText()` — linia 221
  - `public static output($atts, $content = '')` — linia 247

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::checkForMobile()`
- `self::languageChecker()`
- `self::findColor()`
- `self::adjustBrightness()`
- `self::mainText()`
- `self::findAllImages()`
- `PWElementMainCountdown::output()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `wp_enqueue_style()`
- `plugins_url()`
- `wp_enqueue_script()`
- `shortcode_atts()`
- `get_locale()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'countdown.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
