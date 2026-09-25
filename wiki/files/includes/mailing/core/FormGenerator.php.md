# `includes/mailing/core/FormGenerator.php`

Plik first-party PWE Elements w kategorii `mailing-dormant`.

## Metadane

- **Kategoria:** `mailing-dormant`
- **Rozmiar:** 9455 B
- **Liczba linii:** 273
- **Źródło:** `includes/mailing/core/FormGenerator.php`

## Klasy i metody

### `PWE_FormGenerator` — linia 4

  - `public static apply(array $p)` — linia 6

## Funkcje globalne

- `processOneLang(string $lang, array $p)` — linia 20
- `findFormByTitle(string $title)` — linia 36
- `createForm(string $lang, array $p)` — linia 52
- `updateForm(array $form, string $lang, array $p)` — linia 119
- `buildFields(string $lang, array $fields)` — linia 198
- `log(string $msg)` — linia 267

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `self::log()`
- `self::processOneLang()`
- `self::findFormByTitle()`
- `self::updateForm()`
- `self::createForm()`
- `GFAPI::get_forms()`
- `GFAPI::get_form()`
- `self::buildFields()`
- `GFAPI::add_form()`
- `GFAPI::update_form()`
- `GF_Fields::create()`
- `PWE_NotificationProcessor::mailing_log()`

## API WordPress rozpoznane heurystycznie

- `do_shortcode()`
- `is_wp_error()`

## Dołączane pliki / wyrażenia include

- `d'] ?? false),`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
