# `includes/article_author/article_author.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 4854 B
- **Liczba linii:** 144
- **Źródło:** `includes/article_author/article_author.php`

## Klasy i metody

### `PWEArticleAuthorManager` — linia 7

  - `public __construct()` — linia 11
  - `public initVCMapPWEArticleAuthor()` — linia 18
  - `private getTemplatesMap()` — linia 60
  - `private getBasenameFromType($template_type)` — linia 66
  - `private getClassFile($template_type)` — linia 71
  - `private enqueueAssets($template_type)` — linia 82
  - `public PWEArticleAuthorOutput($atts, $content = null)` — linia 116

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
