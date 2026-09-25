# `elements/resend-ticket.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 4451 B
- **Liczba linii:** 133
- **Źródło:** `elements/resend-ticket.php`

## Klasy i metody

### `PWEResendTicket` — linia 3

  - `public __construct()` — linia 9
  - `public static notification_sender()` — linia 13
  - `public static output($atts)` — linia 75

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp` — linia 10

## Wybrane wywołania statyczne

- `GFAPI::get_form()`
- `GFAPI::get_entry()`
- `GFAPI::send_notifications()`
- `self::notification_sender()`
- `self::languageChecker()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `wp_redirect()`
- `home_url()`
- `is_wp_error()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
