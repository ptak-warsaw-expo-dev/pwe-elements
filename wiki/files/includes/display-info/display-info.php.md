# `includes/display-info/display-info.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 11665 B
- **Liczba linii:** 238
- **Źródło:** `includes/display-info/display-info.php`

## Klasy i metody

### `PWEDisplayInfo` — linia 3

  - `public __construct()` — linia 12
  - `public initVCMapPWEDisplayInfo()` — linia 32
  - `private findClassElements()` — linia 170
  - `public addingScripts($atts)` — linia 181
  - `public PWEDisplayInfoOutput($atts, $content = null)` — linia 199

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp_enqueue_scripts` — linia 23
- **action:** `init` — linia 25

## Wybrane wywołania statyczne

- `PWEDisplayInfoBox::initElements()`
- `PWEDisplayInfoSpeakers::initElements()`
- `self::id_rnd()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_script()`
- `get_locale()`
- `wp_localize_script()`
- `shortcode_atts()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/display-info_box.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/display-info_speakers.php'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$display_info_format]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
