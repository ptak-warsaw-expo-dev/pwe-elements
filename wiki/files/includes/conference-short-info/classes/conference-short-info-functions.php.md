# `includes/conference-short-info/classes/conference-short-info-functions.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 11568 B
- **Liczba linii:** 281
- **Źródło:** `includes/conference-short-info/classes/conference-short-info-functions.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `getConferenceRendererClass($domain, $fair_group)` — linia 6
- `getConferenceRendererClassSchedule($domain, $fair_group)` — linia 44
- `getFairDaysFromShortcodes()` — linia 58
- `getFairEndYear()` — linia 76
- `getYearFromSlug(string $slug)` — linia 83
- `filterCurrentConferencesByEndYear(array $all_conferences)` — linia 92
- `hasValidConferences($all_conferences, $fair_days)` — linia 104
- `parse_conference_key_to_date($key, $conf_slug = '')` — linia 126
- `getConferenceOrganizer($conf_id, $conf_slug, $lang)` — linia 195
- `sortConferencesCustom(array $confs)` — linia 233
- `normalizeConferenceNames(array $confs)` — linia 268

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `DateTime::createFromFormat()`
- `self::getFairEndYear()`
- `self::getYearFromSlug()`
- `self::parse_conference_key_to_date()`
- `DateTime::getLastErrors()`
- `PWECommonFunctions::connect_database()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `do_shortcode()`
- `wp_remote_head()`
- `is_wp_error()`
- `wp_remote_retrieve_response_code()`

## Dołączane pliki / wyrażenia include

- `_once $base . '/' . $domainMap[$domain][0]`
- `_once plugin_dir_path(__FILE__) . '/conference-short-info-gr1.php'`
- `_once plugin_dir_path(__FILE__) . '/conference-short-info-default.php'`
- `_once plugin_dir_path(__FILE__) . '/conference-short-info-gr3-schedule.php'`
- `_once plugin_dir_path(__FILE__) . '/conference-short-info-default-schedule.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
