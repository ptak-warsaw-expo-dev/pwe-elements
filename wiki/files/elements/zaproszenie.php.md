# `elements/zaproszenie.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 9972 B
- **Liczba linii:** 231
- **Źródło:** `elements/zaproszenie.php`

## Klasy i metody

### `PWElementInvite` — linia 7

  - `public __construct()` — linia 13
  - `public static initElements()` — linia 22
  - `private static generate($htmlhead_to_pdf, $htmlcont_to_pdf)` — linia 56
  - `public static output($atts)` — linia 114

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::findColor()`
- `self::generate()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `do_shortcode()`
- `wp_upload_bits()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . '../assets/tcpdf/tcpdf.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
