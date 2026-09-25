# `elements/pot_akt_bil.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 19551 B
- **Liczba linii:** 474
- **Źródło:** `elements/pot_akt_bil.php`

## Klasy i metody

### `PWElementTicketActConf` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 21
  - `private static notification_succes($entry_id , $form)` — linia 38
  - `private static notification_sender()` — linia 70
  - `public static output($atts)` — linia 113

## Funkcje globalne

- `transform_dates($start_date, $end_date)` — linia 139

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `GF_Fields::create()`
- `GFAPI::update_form()`
- `GFAPI::update_entry_field()`
- `GFAPI::get_form()`
- `GFAPI::get_entry()`
- `GFAPI::send_notifications()`
- `self::notification_succes()`
- `self::notification_sender()`
- `self::findColor()`
- `DateTime::createFromFormat()`
- `self::isTradeDateExist()`
- `self::languageChecker()`

## API WordPress rozpoznane heurystycznie

- `is_wp_error()`
- `get_locale()`
- `shortcode_atts()`
- `do_shortcode()`
- `plugin_dir_url()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
