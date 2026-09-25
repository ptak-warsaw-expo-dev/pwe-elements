# `includes/registration/registration.php`

Plik first-party PWE Elements w kategorii `registration`.

## Metadane

- **Kategoria:** `registration`
- **Rozmiar:** 21530 B
- **Liczba linii:** 442
- **Źródło:** `includes/registration/registration.php`

## Klasy i metody

### `PWERegistration` — linia 7

  - `public __construct()` — linia 18
  - `public entryToSession($entry, $form)` — linia 37
  - `public initVCMapPWERegistration()` — linia 148
  - `public addingScripts()` — linia 338
  - `private findClassElements()` — linia 358
  - `public PWERegistrationOutput($atts)` — linia 374

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 31
- **action:** `gform_after_submission` — linia 34

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_script()`
- `wp_localize_script()`
- `shortcode_atts()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/registration_visitors.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/registration_exhibitors.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/registration_potential_exhibitors.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/registration_accreditations.php'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$registration_type]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
