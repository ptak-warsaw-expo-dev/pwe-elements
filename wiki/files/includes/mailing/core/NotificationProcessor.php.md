# `includes/mailing/core/NotificationProcessor.php`

Plik first-party PWE Elements w kategorii `mailing-dormant`.

## Metadane

- **Kategoria:** `mailing-dormant`
- **Rozmiar:** 13229 B
- **Liczba linii:** 341
- **Źródło:** `includes/mailing/core/NotificationProcessor.php`

## Klasy i metody

### `PWE_NotificationProcessor` — linia 4

  - `public static setVersionChanged(bool $v)` — linia 7
  - `public static mailing_log(string $message)` — linia 9

## Funkcje globalne

- `period_seconds(array $p)` — linia 28
- `pickCandidatesByTitleMap(array $title_map, $option_key_prefix, array $p, array $not_followed_by = [])` — linia 53
- `processOne(int $form_id, string $lang, array $p)` — linia 103
- `resolveEmailFieldId(array $form, array $toOpt)` — linia 283
- `apply(array $p)` — linia 315

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `self::mailing_log()`
- `GFAPI::get_forms()`
- `self::period_seconds()`
- `GFAPI::get_form()`
- `self::resolveEmailFieldId()`
- `GFAPI::get_feeds()`
- `GFAPI::update_form()`
- `self::pickCandidatesByTitleMap()`
- `self::processOne()`

## API WordPress rozpoznane heurystycznie

- `wp_upload_dir()`
- `get_option()`
- `is_wp_error()`
- `update_option()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
