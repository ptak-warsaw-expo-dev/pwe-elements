# `includes/conference-cap/core/assets.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 2727 B
- **Liczba linii:** 73
- **Źródło:** `includes/conference-cap/core/assets.php`

## Klasy i metody

### `PWE_Conference_Cap_Assets` — linia 6

  - `public static enqueue_common()` — linia 11
  - `public static enqueue_mode(string $mode)` — linia 18
  - `public static enqueue_runtime(array $speakers_data_mapping, bool $one_conference_mode, string $archive)` — linia 42
  - `private static enqueue_style(string $handle, string $relative_path)` — linia 55
  - `private static enqueue_script(string $handle, string $relative_path, array $deps = array())` — linia 64

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `self::enqueue_style()`
- `self::enqueue_script()`

## API WordPress rozpoznane heurystycznie

- `wp_localize_script()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
