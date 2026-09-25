# `includes/attractions/attractions.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 5843 B
- **Liczba linii:** 156
- **Źródło:** `includes/attractions/attractions.php`

## Klasy i metody

### `PWEAttractions` — linia 5

  - `public __construct()` — linia 9
  - `public initVCMapPWEAttractions()` — linia 16
  - `private getClassFile($type)` — linia 78
  - `private enqueueAssets($type)` — linia 90
  - `public PWEAttractionsOutput($atts, $content = null)` — linia 120

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 12

## Wybrane wywołania statyczne

- `PWEAttractionsSlider::initElements()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `shortcode_atts()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/attractions-slider/attractions-slider.php'`
- `_once plugin_dir_path(__FILE__) . $class_file`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
