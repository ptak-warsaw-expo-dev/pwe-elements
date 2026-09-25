# `other/drop_generator.php`

Plik first-party PWE Elements w kategorii `legacy-tool`.

## Metadane

- **Kategoria:** `legacy-tool`
- **Rozmiar:** 8910 B
- **Liczba linii:** 244
- **Źródło:** `other/drop_generator.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `generateToken($domain)` — linia 235
- `validateToken($token, $domain)` — linia 241

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `GFAPI::get_forms()`
- `GFAPI::get_form()`
- `GFAPI::get_feeds()`
- `GFAPI::get_entries()`
- `GFAPI::add_entry()`
- `GFAPI::get_entry()`
- `GFAPI::send_notifications()`

## API WordPress rozpoznane heurystycznie

- `is_wp_error()`
- `do_action()`

## Dołączane pliki / wyrażenia include

- `_once($new_url)`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
