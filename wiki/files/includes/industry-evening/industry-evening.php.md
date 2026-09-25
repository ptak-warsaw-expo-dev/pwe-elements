# `includes/industry-evening/industry-evening.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 4580 B
- **Liczba linii:** 159
- **Źródło:** `includes/industry-evening/industry-evening.php`

## Klasy i metody

### `PWEIndustryEvening` — linia 3

  - `public __construct()` — linia 10
  - `public initVCMapPWEIndustryEvening()` — linia 16
  - `public addAttachmentToZaproszeniaNotification($notification, $form, $entry)` — linia 35
  - `public PWEIndustryEveningOutput($atts)` — linia 51

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 11
- **filter:** `gform_notification` — linia 12

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_fairs_data()`
- `GFAPI::get_form()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_filter()`
- `add_shortcode()`
- `wp_upload_dir()`
- `site_url()`
- `shortcode_atts()`
- `do_shortcode()`
- `esc_attr()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
