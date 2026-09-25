# `includes/news/news.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 5820 B
- **Liczba linii:** 149
- **Źródło:** `includes/news/news.php`

## Klasy i metody

### `PWENews` — linia 6

  - `public __construct()` — linia 11
  - `public initVCMapPWENews()` — linia 19
  - `private getTemplatesMap()` — linia 66
  - `private getBasenameFromType($template_type)` — linia 76
  - `private getClassFile($template_type)` — linia 85
  - `private enqueueAssets($template_type)` — linia 96
  - `public PWENewsOutput($atts, $content = null)` — linia 119

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 14

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `plugin_dir_path()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `do_shortcode()`
- `esc_attr()`

## Dołączane pliki / wyrażenia include

- `_once $file`
- `_once $template_file`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
