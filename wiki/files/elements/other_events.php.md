# `elements/other_events.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 63616 B
- **Liczba linii:** 1302
- **Źródło:** `elements/other_events.php`

## Klasy i metody

### `PWElementOtherEvents` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 22
  - `public static getLangField($item, $base)` — linia 186
  - `public static multi_translation($key)` — linia 203
  - `private static is_excluded_domain(string $domain, array $excluded)` — linia 221
  - `public static outputOtherEventsSwiper($atts)` — linia 230
  - `public static output($atts)` — linia 759

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `PWECommonFunctions::json_fairs()`
- `self::getLangField()`
- `self::multi_translation()`
- `PWECommonFunctions::decode_clean_content()`
- `PWECommonFunctions::languageChecker()`
- `PWESwiperScripts::swiperScripts()`
- `self::outputOtherEventsSwiper()`
- `PWECommonFunctions::get_all_week_domains()`
- `DateTime::createFromFormat()`
- `self::is_excluded_domain()`
- `PWESliderScripts::sliderScripts()`

## API WordPress rozpoznane heurystycznie

- `get_locale()`
- `shortcode_atts()`
- `do_shortcode()`
- `plugin_dir_path()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . '/../scripts/swiper.php'`
- `_once plugin_dir_path(__FILE__) . '/../scripts/slider.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
