# `includes/mailing/mailing.php`

Plik first-party PWE Elements w kategorii `mailing-dormant`.

## Metadane

- **Kategoria:** `mailing-dormant`
- **Rozmiar:** 9347 B
- **Liczba linii:** 306
- **Źródło:** `includes/mailing/mailing.php`

## Klasy i metody

### `PWEMailing` — linia 16

  - `public __construct()` — linia 23
  - `public catalog_feedback_form()` — linia 55
  - `public catalog_exhibitors_details()` — linia 65
  - `public register_resend()` — linia 75
  - `public register_resend_platyna()` — linia 87
  - `public enable_honeypot_for_all_forms()` — linia 119
  - `private static gatePrecheck()` — linia 170
  - `private static gateCommit(int $todayYmd, ?string $currVer)` — linia 216
  - `private get_form_id_by_title(string $title)` — linia 229
  - `public cleanup_catalog_feedback_entries()` — linia 247

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `gform_loaded` — linia 39
- **action:** `gform_loaded` — linia 41
- **action:** `gform_loaded` — linia 43
- **action:** `gform_loaded` — linia 45
- **action:** `gform_loaded` — linia 47
- **action:** `gform_loaded` — linia 51
- **action:** `plugins_loaded` — linia 301

## Wybrane wywołania statyczne

- `self::gatePrecheck()`
- `PWE_NotificationProcessor::setVersionChanged()`
- `self::gateCommit()`
- `PWE_FormPresets::catalog_feedback_form()`
- `PWE_FormGenerator::apply()`
- `PWE_FormPresets::catalog_exhibitors_details()`
- `PWE_NotificationPresets::resend()`
- `PWE_NotificationProcessor::apply()`
- `PWE_NotificationPresets::resend_platyna()`
- `GFAPI::get_forms()`
- `GFAPI::get_form()`
- `GFAPI::update_form()`
- `PWE_NotificationProcessor::mailing_log()`
- `GFAPI::get_entries()`
- `GFAPI::update_entry_property()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `add_action()`
- `is_wp_error()`
- `get_option()`
- `update_option()`

## Dołączane pliki / wyrażenia include

- `_once __DIR__ . '/core/FormGenerator.php'`
- `_once __DIR__ . '/config/FormPresets.php'`
- `_once __DIR__ . '/core/NotificationProcessor.php'`
- `_once __DIR__ . '/config/NotificationPresets.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
