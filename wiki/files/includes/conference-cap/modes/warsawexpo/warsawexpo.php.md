# `includes/conference-cap/modes/warsawexpo/warsawexpo.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 16962 B
- **Liczba linii:** 339
- **Źródło:** `includes/conference-cap/modes/warsawexpo/warsawexpo.php`

## Klasy i metody

### `PWEConferenceCapWarsawExpo` — linia 2

  - `private static parse_date_to_standard($input)` — linia 6
  - `private static normalize_hour_string($time_str)` — linia 12
  - `public static output($atts, $lang)` — linia 18
### `PWE_Conference_Cap_WarsawExpo_Mode` — linia 326

  - `public render(array $context)` — linia 328
  - `public get_assets()` — linia 332

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `PWE_Conference_Cap_Date_Parser::parse_date_to_standard()`
- `PWE_Conference_Cap_Time_Parser::normalize_hour_string()`
- `PWE_Conference_Cap_Assets::enqueue_mode()`
- `PWECommonFunctions::get_database_conferences_data()`
- `self::parse_date_to_standard()`
- `self::normalize_hour_string()`
- `PWEConferenceCapWarsawExpo::output()`

## API WordPress rozpoznane heurystycznie

- `esc_url()`
- `esc_html()`
- `esc_attr()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
