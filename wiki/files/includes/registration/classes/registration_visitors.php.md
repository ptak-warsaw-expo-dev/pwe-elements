# `includes/registration/classes/registration_visitors.php`

Plik first-party PWE Elements w kategorii `registration`.

## Metadane

- **Kategoria:** `registration`
- **Rozmiar:** 20733 B
- **Liczba linii:** 391
- **Źródło:** `includes/registration/classes/registration_visitors.php`

## Klasy i metody

### `PWERegistrationVisitors` — linia 7

  - `public __construct()` — linia 13
  - `public static getFieldIdByAdminLabel($form, $admin_label)` — linia 25
  - `public hideFieldsBasedOnAdminLabel($form)` — linia 42
  - `public static multi_translation($key)` — linia 53
  - `public static output($atts, $registration_type, $registration_form_id, $register_show_ticket)` — linia 78

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **filter:** `gform_pre_render` — linia 15

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::findColor()`
- `PWECommonFunctions::get_database_groups_data()`
- `self::adjustBrightness()`
- `self::multi_translation()`
- `PWECommonFunctions::get_database_fairs_data()`

## API WordPress rozpoznane heurystycznie

- `add_filter()`
- `get_locale()`
- `do_shortcode()`
- `site_url()`
- `plugin_dir_path()`

## Dołączane pliki / wyrażenia include

- `s/registration_visitors.json'`
- `_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php'`
- `_once plugin_dir_path(__DIR__) . 'assets/visitors_gr2.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
