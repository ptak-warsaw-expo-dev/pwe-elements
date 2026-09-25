# `elements/pot_rej.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 91081 B
- **Liczba linii:** 1754
- **Źródło:** `elements/pot_rej.php`

## Klasy i metody

### `PWElementPotwierdzenieRejestracji` — linia 7

  - `public __construct()` — linia 13
  - `public static add_field_apartment($reg_form_name_pr)` — linia 17
  - `public static initElements()` — linia 97
  - `public static output($atts, $content = '')` — linia 133

## Funkcje globalne

- `transform_dates($start_date, $end_date)` — linia 234

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `PWECommonFunctions::findFormsID()`
- `GFAPI::get_form()`
- `GFAPI::update_form()`
- `self::add_field_apartment()`
- `self::findColor()`
- `DateTime::createFromFormat()`
- `self::isTradeDateExist()`
- `self::languageChecker()`
- `PWECommonFunctions::languageChecker()`
- `PWECommonFunctions::get_database_meta_data()`

## API WordPress rozpoznane heurystycznie

- `get_option()`
- `is_wp_error()`
- `update_option()`
- `wp_list_pluck()`
- `shortcode_atts()`
- `do_shortcode()`
- `wp_safe_redirect()`
- `home_url()`
- `current_user_can()`
- `plugins_url()`
- `get_locale()`

## Dołączane pliki / wyrażenia include

- `d!',`
- `d />`
- `s("route")) {`
- `s("street_number")) {`
- `s("postal_code")) {`
- `s("locality")) {`
- `s("subpremise")) {`
- `s("-")) {`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
