# `includes/premieres/premieres.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 27203 B
- **Liczba linii:** 690
- **Źródło:** `includes/premieres/premieres.php`

## Klasy i metody

### `PWEPremieres` — linia 3

  - `public __construct()` — linia 8
  - `public initVCMapPWEPremieres()` — linia 20
  - `public static multi_translation($key)` — linia 33
  - `public PWEPremieresOutput()` — linia 64

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 13

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_premieres_data()`
- `PWECommonFunctions::lang_pl()`
- `self::multi_translation()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `get_locale()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `s/premieres.json'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
