# `includes/calendar/classes/loop-calendar.php`

Plik first-party PWE Elements w kategorii `calendar`.

## Metadane

- **Kategoria:** `calendar`
- **Rozmiar:** 83500 B
- **Liczba linii:** 1762
- **Źródło:** `includes/calendar/classes/loop-calendar.php`

## Klasy i metody

### `PWECalendar` — linia 3

  - `public __construct()` — linia 5
  - `public init_vc_map_pwe_calendar()` — linia 17
  - `public static get_pwe_shortcode($shortcode, $domain)` — linia 90
  - `public static check_available_pwe_shortcode($shortcodes_active, $shortcode)` — linia 94
  - `public static format_date_range($start_date, $end_date, $locale)` — linia 98
  - `multi_translation($key)` — linia 181
  - `get_translated_field($fair, $field_base_name)` — linia 200

## Funkcje globalne

- `pwe_calendar_loop_output($atts)` — linia 217
- `normalize_calendar_domain($domain)` — linia 1187
- `is_calendar_week_event($event)` — linia 1194
- `sort_calendar_events_with_weeks($event_posts)` — linia 1214
- `render_calendar_event_card($event, $shortcodes_active, $lang_pl = true)` — linia 1354
- `load_more_calendar($pwe_calendar_pagination)` — linia 1700

## Rejestracje WordPress / GF

- **action:** `init` — linia 7
- **action:** `wp_ajax_load_more_calendar` — linia 8
- **action:** `wp_ajax_nopriv_load_more_calendar` — linia 9

## Wybrane wywołania statyczne

- `self::get_pwe_shortcode()`
- `self::check_available_pwe_shortcode()`
- `self::multi_translation()`
- `self::render_calendar_event_card()`
- `self::format_date_range()`
- `DateTime::createFromFormat()`
- `PWECommonFunctions::json_fairs()`
- `PWECommonFunctions::lang_pl()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `shortcode_exists()`
- `do_shortcode()`
- `get_locale()`
- `shortcode_atts()`
- `get_terms()`
- `is_wp_error()`
- `get_option()`
- `get_the_ID()`
- `get_post_meta()`
- `get_the_terms()`
- `get_the_title()`
- `wp_reset_postdata()`
- `current_user_can()`
- `esc_url()`
- `get_permalink()`
- `wp_die()`

## Dołączane pliki / wyrażenia include

- `s("'. self::multi_translation("premier_edition") .'".toLowerCase())`
- `s(categorySlug)`
- `s(query) ||`
- `s(query)`
- `s(eventItem)`
- `s(eventId)`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
