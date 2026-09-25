# `elements/fetch.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 5285 B
- **Liczba linii:** 171
- **Źródło:** `elements/fetch.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `getFormIdByTitle($formName)` — linia 22
- `getFieldIdByAdminLabel($form, $admin_label)` — linia 59
- `createField($form_id, $admin_label, $label)` — linia 68

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `GFAPI::get_forms()`
- `GFAPI::get_form()`
- `GFAPI::get_entry()`
- `GFAPI::update_form()`
- `GFAPI::update_entry_field()`
- `GFAPI::send_notifications()`

## API WordPress rozpoznane heurystycznie

- `is_wp_error()`
- `wp_remote_post()`
- `home_url()`

## Dołączane pliki / wyrażenia include

- `_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php')`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
