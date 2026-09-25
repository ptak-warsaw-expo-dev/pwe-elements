# `scripts/posts-slider.php`

Plik first-party PWE Elements w kategorii `script-helper`.

## Metadane

- **Kategoria:** `script-helper`
- **Rozmiar:** 26876 B
- **Liczba linii:** 467
- **Źródło:** `scripts/posts-slider.php`

## Klasy i metody

### `PWEPostsSlider` — linia 3

  - `public __construct()` — linia 8
  - `private static createDOM($id_rnd, $media_url, $min_image, $max_image, $full_mode)` — linia 19
  - `private static generateScript($id_rnd, $media_url, $min_image, $slide_speed, $full_mode)` — linia 171
  - `public static sliderOutput($media_url, $slide_speed = 3000, $full_mode = "")` — linia 444

## Funkcje globalne

- Brak funkcji globalnych.

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
