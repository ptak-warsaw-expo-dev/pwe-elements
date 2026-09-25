# `includes/exhibitor-generator/exhibitor-generator.php`

Plik first-party PWE Elements w kategorii `exhibitor-generator`.

## Metadane

- **Kategoria:** `exhibitor-generator`
- **Rozmiar:** 16877 B
- **Liczba linii:** 415
- **Źródło:** `includes/exhibitor-generator/exhibitor-generator.php`

## Klasy i metody

### `PWEExhibitorGenerator` — linia 11

  - `public __construct()` — linia 28
  - `public initVCMapPWEExhibitorGenerator()` — linia 52
  - `private findClassElements()` — linia 181
  - `public addingStyles()` — linia 194
  - `public addingScripts($atts)` — linia 208
  - `public static hide_field_by_label($form, $com_name)` — linia 233
  - `public static catalog_data($exhibitor_id = null)` — linia 254
  - `public PWEExhibitorGeneratorOutput($atts)` — linia 336

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 41
- **action:** `wp_enqueue_scripts` — linia 45

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_meta_data()`
- `PWECommonFunctions::get_database_groups_data()`
- `PWECommonFunctions::languageChecker()`

## API WordPress rozpoznane heurystycznie

- `get_locale()`
- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `wp_localize_script()`
- `do_shortcode()`
- `shortcode_atts()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/exhibitor-visitor-generator.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/exhibitor-worker-generator.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/mass-vip-sender.php'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$exhibitor_generator_mode]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
