# `includes/media-gallery/media-gallery.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 78130 B
- **Liczba linii:** 1399
- **Źródło:** `includes/media-gallery/media-gallery.php`

## Klasy i metody

### `PWEMediaGallery` — linia 3

  - `public __construct()` — linia 9
  - `public initVCMapMediaGallery()` — linia 24
  - `public addingStyles()` — linia 516
  - `public addingScripts()` — linia 525
  - `public PWEMediaGalleryOutput($atts, $content = null)` — linia 538

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp_enqueue_scripts` — linia 13
- **action:** `wp_enqueue_scripts` — linia 14
- **action:** `init` — linia 17

## Wybrane wywołania statyczne

- `self::languageChecker()`
- `PWESliderScripts::sliderScripts()`
- `PWESwiperScripts::swiperScripts()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `plugins_url()`
- `plugin_dir_path()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `shortcode_atts()`
- `esc_url()`
- `esc_attr()`
- `esc_html()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `s/media-gallery/assets/justified-gallery/justifiedGallery.css" rel="stylesheet">`
- `s/media-gallery/assets/justified-gallery/jquery.justifiedGallery.js"></script>`
- `_once plugin_dir_path(dirname(__DIR__)) . 'scripts/slider.php'`
- `s/media-gallery/assets/fotorama/fotorama.css" rel="stylesheet">`
- `s/media-gallery/assets/fotorama/fotorama.js"></script>`
- `s/media-gallery/assets/coverflow-gallery/coverflow-gallery.css" rel="stylesheet">'`
- `_once plugin_dir_path(dirname(__DIR__)) . 'scripts/swiper.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
