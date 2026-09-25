# `elements/opinions.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 118802 B
- **Liczba linii:** 2187
- **Źródło:** `elements/opinions.php`

## Klasy i metody

### `PWElementOpinions` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 21
  - `public static getLangField(array $item, string $baseKey)` — linia 230
  - `public static multi_translation($key)` — linia 245
  - `public static outputOpinionsSwiper($atts)` — linia 263
  - `public static output($atts)` — linia 1211

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `PWECommonFunctions::get_database_fairs_data_opinions()`
- `self::multi_translation()`
- `self::getLangField()`
- `PWESwiperScripts::swiperScripts()`
- `self::outputOpinionsSwiper()`
- `PWECommonFunctions::get_database_week_data()`
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
