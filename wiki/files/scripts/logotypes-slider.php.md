# `scripts/logotypes-slider.php`

Plik first-party PWE Elements w kategorii `script-helper`.

## Metadane

- **Kategoria:** `script-helper`
- **Rozmiar:** 30960 B
- **Liczba linii:** 510
- **Źródło:** `scripts/logotypes-slider.php`

## Klasy i metody

### `PWELogotypesSlider` — linia 3

  - `public __construct()` — linia 8
  - `private static createDOM($id_rnd, $media_url, $min_image, $max_image, $images_options)` — linia 20

## Funkcje globalne

- `generateScript($id_rnd, $media_url, $min_image, $slide_speed)` — linia 253
- `sliderOutput($media_url = [], $slide_speed = 3000, $images_options = "")` — linia 484

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `self::createDOM()`
- `self::generateScript()`

## API WordPress rozpoznane heurystycznie

- `do_shortcode()`
- `get_locale()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
