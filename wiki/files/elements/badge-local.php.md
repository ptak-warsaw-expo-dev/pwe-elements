# `elements/badge-local.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 16399 B
- **Liczba linii:** 469
- **Źródło:** `elements/badge-local.php`

## Klasy i metody

### `PWBadgeElement` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 22
  - `public static massGenerator($badge_form_id)` — linia 50
  - `public static qrOnlyDownload($badge_form_id)` — linia 179
  - `public static pwe_download_temp_qr($url)` — linia 318
  - `public static badge_name_changer($content, $field, $value, $lead_id, $form_id)` — linia 372
  - `public static output($atts)` — linia 402

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **filter:** `gform_field_content` — linia 441

## Wybrane wywołania statyczne

- `parent::__construct()`
- `GFAPI::add_entry()`
- `GFAPI::get_entry()`
- `GFAPI::get_feeds()`
- `self::pwe_download_temp_qr()`
- `self::findColor()`
- `self::qrOnlyDownload()`
- `self::massGenerator()`

## API WordPress rozpoznane heurystycznie

- `is_wp_error()`
- `do_action()`
- `wp_upload_dir()`
- `do_shortcode()`
- `wp_remote_get()`
- `wp_remote_retrieve_response_code()`
- `wp_remote_retrieve_body()`
- `add_filter()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
