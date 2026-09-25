# `elements/generator-wystawcow.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 80127 B
- **Liczba linii:** 1698
- **Źródło:** `elements/generator-wystawcow.php`

## Klasy i metody

### `PWElementGenerator` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 21
  - `private static generateToken()` — linia 134
  - `public static hide_field_by_label($form, $com_name)` — linia 146
  - `public static output($atts)` — linia 164

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **filter:** `gform_pre_render` — linia 207
- **filter:** `gform_pre_validation` — linia 210
- **filter:** `gform_pre_submission_filter` — linia 213
- **filter:** `gform_admin_pre_render` — linia 216

## Wybrane wywołania statyczne

- `parent::__construct()`
- `PWECommonFunctions::get_database_groups_data()`
- `self::hide_field_by_label()`
- `GFAPI::get_entries()`
- `self::languageChecker()`
- `self::generateToken()`

## API WordPress rozpoznane heurystycznie

- `shortcode_atts()`
- `add_filter()`
- `plugins_url()`
- `wp_get_attachment_url()`
- `get_locale()`

## Dołączane pliki / wyrażenia include

- `s(fileExtension)) {`
- `d`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
