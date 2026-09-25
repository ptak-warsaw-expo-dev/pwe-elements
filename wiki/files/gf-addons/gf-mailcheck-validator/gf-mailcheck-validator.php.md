# `gf-addons/gf-mailcheck-validator/gf-mailcheck-validator.php`

Plik first-party PWE Elements w kategorii `gravity-forms-addon`.

## Metadane

- **Kategoria:** `gravity-forms-addon`
- **Rozmiar:** 12393 B
- **Liczba linii:** 443
- **Źródło:** `gf-addons/gf-mailcheck-validator/gf-mailcheck-validator.php`

## Klasy i metody

### `GF_Mailcheck_Validator` — linia 3

  - `public __construct()` — linia 5
  - `public enqueue_assets()` — linia 10
  - `private get_current_language()` — linia 57
  - `private get_messages()` — linia 70
  - `private get_valid_domains()` — linia 150
  - `private get_domain_corrections()` — linia 262
  - `private uses_new_email_validator($field)` — linia 379
  - `public validate_email_domain($result, $value, $form, $field)` — linia 398

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp_enqueue_scripts` — linia 6
- **filter:** `gform_field_validation` — linia 7

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_filter()`
- `is_admin()`
- `wp_enqueue_script()`
- `plugin_dir_url()`
- `plugin_dir_path()`
- `wp_localize_script()`
- `wp_enqueue_style()`
- `get_locale()`
- `apply_filters()`
- `esc_html()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
