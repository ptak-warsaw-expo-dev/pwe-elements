# `elements/medal-form.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 14801 B
- **Liczba linii:** 354
- **Źródło:** `elements/medal-form.php`

## Klasy i metody

### `PWElementMedalForm` — linia 6

  - `public __construct()` — linia 12
  - `public update_medal_choices($form)` — linia 18
  - `public static initElements()` — linia 70
  - `public static output($atts)` — linia 97

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **filter:** `gform_pre_render` — linia 13
- **filter:** `gform_pre_validation` — linia 14

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_fairs_data_adds()`
- `PWECommonFunctions::lang_pl()`
- `self::findColor()`
- `PWECommonFunctions::get_database_fairs_data_files()`
- `self::languageChecker()`

## API WordPress rozpoznane heurystycznie

- `add_filter()`
- `shortcode_atts()`

## Dołączane pliki / wyrażenia include

- `s(fileExtension)) {`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
