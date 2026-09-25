# `elements/registration.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 62102 B
- **Liczba linii:** 1337
- **Źródło:** `elements/registration.php`

## Klasy i metody

### `PWElementRegistration` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 21
  - `public static custom_css_1()` — linia 137
  - `public static output($atts)` — linia 230

## Funkcje globalne

- `transform_dates($start_date, $end_date)` — linia 312
- `get_form_id_by_title($title)` — linia 1190

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::findColor()`
- `self::adjustBrightness()`
- `DateTime::createFromFormat()`
- `self::isTradeDateExist()`
- `self::languageChecker()`
- `PWElementRegHeader::output()`
- `self::custom_css_1()`
- `GFAPI::get_forms()`

## API WordPress rozpoznane heurystycznie

- `shortcode_atts()`
- `get_locale()`
- `do_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . '/../elements/registration-header.php'`
- `s("utm_source=byli") || utmCookie.includes("utm_source=premium"))) {`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
