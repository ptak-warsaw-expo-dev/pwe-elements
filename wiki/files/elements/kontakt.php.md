# `elements/kontakt.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 26071 B
- **Liczba linii:** 692
- **Źródło:** `elements/kontakt.php`

## Klasy i metody

### `PWElementContact` — linia 7

  - `public __construct()` — linia 12
  - `public static initElements()` — linia 21
  - `private static pwe_clean_value($value)` — linia 45
  - `private static pwe_option_value($option_name)` — linia 60
  - `private static pwe_first_not_empty($manual_value, $default_value = '')` — linia 73
  - `private static pwe_split_emails($value)` — linia 89
  - `private static pwe_phone_href($phone)` — linia 113
  - `private static pwe_data_value($data, $field)` — linia 124
  - `private static pwe_render_email_links($emails)` — linia 150
  - `public static output($atts)` — linia 183

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::pwe_clean_value()`
- `self::pwe_split_emails()`
- `self::findColor()`
- `PWECommonFunctions::get_database_groups_data()`
- `PWECommonFunctions::get_database_groups_contacts_data()`
- `self::pwe_data_value()`
- `self::pwe_first_not_empty()`
- `self::pwe_option_value()`
- `PWElementContactForm::multi_translation()`
- `self::pwe_phone_href()`
- `self::pwe_render_email_links()`

## API WordPress rozpoznane heurystycznie

- `get_option()`
- `esc_html()`
- `esc_url()`
- `shortcode_atts()`
- `sanitize_text_field()`
- `wp_unslash()`
- `do_shortcode()`
- `wp_json_encode()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
