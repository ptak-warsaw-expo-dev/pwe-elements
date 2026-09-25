# `includes/map/map.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 21495 B
- **Liczba linii:** 458
- **Źródło:** `includes/map/map.php`

## Klasy i metody

### `PWEMap` — linia 7

  - `public __construct()` — linia 17
  - `public initVCMapPWEMap()` — linia 38
  - `public addingScripts($map_type, $map_dynamic_3d, $map_dynamic_preset, $map_color, $map_water_color)` — linia 352
  - `private findClassElements()` — linia 378
  - `public PWEMapOutput($atts)` — linia 392

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 29

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `wp_enqueue_script()`
- `plugins_url()`
- `wp_localize_script()`
- `shortcode_atts()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/map_dynamic.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/map_3d.php'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$map_type]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
