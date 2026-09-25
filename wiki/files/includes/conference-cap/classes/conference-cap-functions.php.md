# `includes/conference-cap/classes/conference-cap-functions.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 12866 B
- **Liczba linii:** 390
- **Źródło:** `includes/conference-cap/classes/conference-cap-functions.php`

## Klasy i metody

### `PWEConferenceCapFunctions` — linia 2

  - `public static findConferenceMode($new_class)` — linia 4
  - `public static speakerImageMini($speaker_images)` — linia 25
  - `public static pwe_convert_rgb_to_hex($content)` — linia 116
  - `public static copySpeakerImgByStructure(array $json)` — linia 125
  - `public static getConferencePatronLogosFromList($conf_id, $conf_slug, $logo_files = [])` — linia 174
  - `public static getConferenceOrganizer($conf_id, $conf_slug, $lang)` — linia 230
  - `public static getConferenceOrganizersAll($conf_slug)` — linia 294
  - `public static debugConferencesConsole(array $database_data)` — linia 364

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_conference_adds_data()`
- `PWECommonFunctions::get_database_conferences_data()`

## API WordPress rozpoznane heurystycznie

- `esc_url()`
- `esc_attr()`
- `wp_remote_head()`
- `is_wp_error()`
- `wp_remote_retrieve_response_code()`
- `current_user_can()`
- `wp_json_encode()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
