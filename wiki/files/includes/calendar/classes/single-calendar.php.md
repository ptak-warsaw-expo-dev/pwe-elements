# `includes/calendar/classes/single-calendar.php`

Plik first-party PWE Elements w kategorii `calendar`.

## Metadane

- **Kategoria:** `calendar`
- **Rozmiar:** 177232 B
- **Liczba linii:** 3860
- **Źródło:** `includes/calendar/classes/single-calendar.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `adjustBrightness($hex, $steps)` — linia 20
- `multi_translation($key)` — linia 95
- `get_translated_field($fair, $field_base_name)` — linia 114
- `get_pwe_shortcode($shortcode, $domain)` — linia 137
- `check_available_pwe_shortcode($shortcodes_active, $shortcode)` — linia 143
- `format_date_range($start_date, $end_date, $months, $locale)` — linia 170
- `format_title($title)` — linia 3480
- `format_title($title)` — linia 3576

## Rejestracje WordPress / GF

- **filter:** `the_content` — linia 3

## Wybrane wywołania statyczne

- `DateTime::createFromFormat()`
- `PWECommonFunctions::transform_dates()`
- `PWECommonFunctions::json_fairs()`
- `CatalogFunctions::logosChecker()`
- `PWECommonFunctions::get_database_fairs_data_adds()`
- `PWECommonFunctions::get_database_translations_data()`
- `PWECommonFunctions::get_database_logotypes_data()`
- `PWECommonFunctions::lang_pl()`

## API WordPress rozpoznane heurystycznie

- `get_header()`
- `add_filter()`
- `get_the_ID()`
- `get_post_meta()`
- `get_locale()`
- `get_option()`
- `shortcode_exists()`
- `do_shortcode()`
- `esc_attr()`
- `get_the_title()`
- `plugin_dir_path()`
- `get_footer()`

## Dołączane pliki / wyrażenia include

- `s/calendar/assets/view-icon.png">`
- `s/calendar/assets/globus-icon.png">`
- `s/calendar/assets/area-icon.png">`
- `_once plugin_dir_path(dirname(__FILE__)) . 'assets/svg.php'`
- `s/calendar/assets/calendar-icon.png"><span>'. $days .' '. ($lang_pl ? "dni" : "days") .'</span>`
- `s/calendar/assets/right-arrow-icon.png"><span>'. count($all_events_json) .' '. $events_word_declination .'</span>`
- `s("catalog")) {`
- `s("?") ? `${currentUrl}&catalog` : `${currentUrl}?catalog``
- `s(query)`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
