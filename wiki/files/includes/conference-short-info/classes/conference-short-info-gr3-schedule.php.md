# `includes/conference-short-info/classes/conference-short-info-gr3-schedule.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 19260 B
- **Liczba linii:** 491
- **Źródło:** `includes/conference-short-info/classes/conference-short-info-gr3-schedule.php`

## Klasy i metody

### `PWEConferenceShortInfoGr3Schedule` — linia 3

  - `public static confLang($pl, $en)` — linia 5
  - `public static initElements()` — linia 15
  - `private static limit_words(string $text, int $max_words = 12, string $ellipsis = '…')` — linia 19
  - `public static output($atts, $all_conferences, $rnd_class, $name, $title, $desc, $isWeek)` — linia 28

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `self::getConferenceOrganizer()`
- `self::getFairDaysFromShortcodes()`
- `self::parse_conference_key_to_date()`
- `self::confLang()`
- `PWEConferenceShortInfo::multi_translation()`
- `PWESwiperScripts::swiperScripts()`

## API WordPress rozpoznane heurystycznie

- `get_locale()`
- `current_user_can()`
- `plugin_dir_path()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . '/../../../scripts/swiper.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
