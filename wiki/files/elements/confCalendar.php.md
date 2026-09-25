# `elements/confCalendar.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 4617 B
- **Liczba linii:** 138
- **Źródło:** `elements/confCalendar.php`

## Klasy i metody

### `PWElementConfCallendar` — linia 6

  - `public __construct()` — linia 12
  - `private static calendarDisplay($data)` — linia 22
  - `public static output($atts)` — linia 31

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::findColor()`
- `self::languageChecker()`
- `self::isTradeDateExist()`
- `PWGoogleCalendarElement::output()`
- `PWAppleCalendarElement::output()`
- `PWOutlookCalendarElement::output()`
- `PWOfficeCalendarElement::output()`

## API WordPress rozpoznane heurystycznie

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
