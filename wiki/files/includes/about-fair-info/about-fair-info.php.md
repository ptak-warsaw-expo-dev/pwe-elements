# `includes/about-fair-info/about-fair-info.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 7676 B
- **Liczba linii:** 195
- **Źródło:** `includes/about-fair-info/about-fair-info.php`

## Klasy i metody

### `PWEAboutFairInfo` — linia 11

  - `public __construct()` — linia 20
  - `private getConferenceRendererClass($domain, $fair_group)` — linia 38
  - `private getExhibitorsData()` — linia 64
  - `public initVCMapPWEAboutFairInfo()` — linia 105
  - `public addingStyles()` — linia 127
  - `public PWEAboutFairInfoOutput($atts)` — linia 147

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 33
- **action:** `wp_enqueue_scripts` — linia 35

## Wybrane wywołania statyczne

- `CatalogFunctions::logosChecker()`
- `PWECommonFunctions::get_database_fairs_data()`
- `renderer_class::initElements()`
- `PWECommonFunctions::get_database_fairs_data_adds()`
- `PWECommonFunctions::languageChecker()`
- `renderer_class::output()`

## API WordPress rozpoznane heurystycznie

- `get_locale()`
- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `do_shortcode()`
- `site_url()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_style()`
- `esc_attr()`
- `home_url()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-home.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-gr1.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-gr2.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-gr3.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-default.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
