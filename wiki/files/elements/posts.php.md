# `elements/posts.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 56369 B
- **Liczba linii:** 1257
- **Źródło:** `elements/posts.php`

## Klasy i metody

### `PWElementPosts` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 21
  - `public static outputSliderSyncing($atts, $posts_data = array(), $posts_title = '')` — linia 168
  - `public static output($atts)` — linia 457

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::languageChecker()`
- `self::findColor()`
- `self::adjustBrightness()`
- `PWESliderScripts::sliderScripts()`
- `self::outputSliderSyncing()`
- `self::output()`

## API WordPress rozpoznane heurystycznie

- `wp_enqueue_style()`
- `plugins_url()`
- `wp_enqueue_script()`
- `esc_url()`
- `esc_attr()`
- `esc_html()`
- `shortcode_atts()`
- `do_shortcode()`
- `get_locale()`
- `get_permalink()`
- `has_post_thumbnail()`
- `get_the_post_thumbnail_url()`
- `get_the_title()`
- `wp_reset_postdata()`
- `plugin_dir_path()`
- `get_the_ID()`
- `wp_strip_all_tags()`
- `get_the_date()`
- `admin_url()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . '/../scripts/slider.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
