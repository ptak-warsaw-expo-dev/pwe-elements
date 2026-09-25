# `includes/conference-calendar/conference-calendar.php`

Plik first-party PWE Elements w kategorii `conference-calendar`.

## Metadane

- **Kategoria:** `conference-calendar`
- **Rozmiar:** 41289 B
- **Liczba linii:** 897
- **Źródło:** `includes/conference-calendar/conference-calendar.php`

## Klasy i metody

### `PWEConferenceCalendar` — linia 3

  - `public __construct()` — linia 5
  - `public init_vc_map_pwe_conference_calendar()` — linia 15
  - `public static get_database_conferences_data()` — linia 29
  - `public fairs_array()` — linia 130
  - `public pwe_conference_calendar_loop_output()` — linia 151

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 7

## Wybrane wywołania statyczne

- `PWECommonFunctions::connect_database()`
- `PWECommonFunctions::get_database_groups_data()`
- `self::get_database_conferences_data()`
- `PWECommonFunctions::lang_pl()`
- `DateTime::createFromFormat()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `current_user_can()`
- `is_admin()`
- `do_shortcode()`
- `esc_url()`

## Dołączane pliki / wyrażenia include

- `s(selectedCategory))`
- `s(query) || fairDesc.includes(query) || confName.includes(query)`
- `s(selectedCategory)) show = false`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
