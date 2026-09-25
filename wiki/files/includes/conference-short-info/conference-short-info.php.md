# `includes/conference-short-info/conference-short-info.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 8809 B
- **Liczba linii:** 218
- **Źródło:** `includes/conference-short-info/conference-short-info.php`

## Klasy i metody

### `PWEConferenceShortInfo` — linia 13

  - `public __construct()` — linia 22
  - `public initVCMapPWEConferenceShortInfo()` — linia 42
  - `public addingStyles()` — linia 63
  - `public PWEConferenceShortInfoOutput($atts)` — linia 83
  - `public static multi_translation($key)` — linia 199

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 35
- **action:** `wp_enqueue_scripts` — linia 37

## Wybrane wywołania statyczne

- `renderer_class::initElements()`
- `PWECommonFunctions::get_database_fairs_data()`
- `PWECommonFunctions::get_database_conferences_data()`
- `PWECommonFunctions::get_database_week_data()`
- `PWECommonFunctions::get_database_week_all()`
- `self::getFairDaysFromShortcodes()`
- `self::getConferenceOrganizer()`
- `self::hasValidConferences()`
- `self::filterCurrentConferencesByEndYear()`
- `self::sortConferencesCustom()`
- `self::normalizeConferenceNames()`
- `PWECommonFunctions::get_database_fairs_data_adds()`
- `PWECommonFunctions::lang_pl()`
- `renderer_class::output()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `get_locale()`
- `add_action()`
- `add_shortcode()`
- `site_url()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_style()`
- `esc_attr()`
- `esc_html()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/conference-short-info-functions.php'`
- `s/conference-short-info.json'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
