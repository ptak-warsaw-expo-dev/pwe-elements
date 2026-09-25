# `qr-active/main-qr-active.php`

Plik first-party PWE Elements w kategorii `dormant-module`.

## Metadane

- **Kategoria:** `dormant-module`
- **Rozmiar:** 3703 B
- **Liczba linii:** 110
- **Źródło:** `qr-active/main-qr-active.php`

## Klasy i metody

### `PWEQRActive` — linia 3

  - `public __construct()` — linia 9
  - `public initVCMapPWEQRActive()` — linia 19
  - `private findClassElements()` — linia 58
  - `public static languageChecker($pl, $en = '')` — linia 72
  - `public PWEQRActiveOutput($atts, $content = null)` — linia 87

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 12

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `get_locale()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'qr-active-start.php'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$pwe_qr_active]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
