# `elements/calendarAdd.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 9185 B
- **Liczba linii:** 229
- **Źródło:** `elements/calendarAdd.php`

## Klasy i metody

### `PWCallendarAddElement` — linia 6

  - `public __construct()` — linia 12
  - `public static initElements()` — linia 20
  - `public static output($atts)` — linia 44

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::findColor()`
- `self::findBestLogo()`
- `self::languageChecker()`
- `self::isTradeDateExist()`
- `PWGoogleCalendarElement::output()`
- `PWAppleCalendarElement::output()`
- `PWOutlookCalendarElement::output()`
- `PWOfficeCalendarElement::output()`

## API WordPress rozpoznane heurystycznie

- `shortcode_atts()`
- `plugin_dir_path()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'calendarApple.php'`
- `_once plugin_dir_path(__FILE__) . 'calendarGoogle.php'`
- `_once plugin_dir_path(__FILE__) . 'calendarOffice.php'`
- `_once plugin_dir_path(__FILE__) . 'calendarOutlook.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
