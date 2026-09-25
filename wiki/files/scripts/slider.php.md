# `scripts/slider.php`

Plik first-party PWE Elements w kategorii `script-helper`.

## Metadane

- **Kategoria:** `script-helper`
- **Rozmiar:** 18126 B
- **Liczba linii:** 399
- **Źródło:** `scripts/slider.php`

## Klasy i metody

### `PWESliderScripts` — linia 3

  - `public __construct()` — linia 8
  - `public static sliderScripts($id = '', $pwe_element = '.pwelement', $dots_display = false, $arrows_display = false, $slides_to_show = 5, $options = null, $slides_to_show_1 = 5, $slides_to_show_2 = 3, $slides_to_show_3 = 2, $breakpoints_raw = '')` — linia 13

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `PWECommonFunctions::findPalletColorsStatic()`

## API WordPress rozpoznane heurystycznie

- `wp_enqueue_style()`
- `plugins_url()`
- `wp_enqueue_script()`
- `plugin_dir_path()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__DIR__) . 'pwefunctions.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
