# `includes/store/store.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 12954 B
- **Liczba linii:** 307
- **Źródło:** `includes/store/store.php`

## Klasy i metody

### `PWEStore` — linia 3

  - `public __construct()` — linia 8
  - `public initVCMapPWEStore()` — linia 20
  - `public fairs_array()` — linia 33
  - `public addingStyles()` — linia 80
  - `public addingScripts()` — linia 90
  - `public price($product, $store_options, $pwe_meta_data, $category, $current_domain, $num_only = false)` — linia 114
  - `public round_price($price)` — linia 185
  - `public image_exists($url)` — linia 197
  - `public PWEStoreOutput()` — linia 204

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp_enqueue_scripts` — linia 10
- **action:** `wp_enqueue_scripts` — linia 11
- **action:** `init` — linia 13

## Wybrane wywołania statyczne

- `self::get_database_groups_data()`
- `self::lang_pl()`
- `self::round_price()`
- `self::get_database_store_data()`
- `self::get_database_store_packages_data()`
- `self::get_database_meta_data()`
- `self::json_fairs()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `do_shortcode()`
- `plugins_url()`
- `plugin_dir_path()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `wp_localize_script()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'parts/store_header.php'`
- `_once plugin_dir_path(__FILE__) . 'parts/store_product_details.php'`
- `_once plugin_dir_path(__FILE__) . 'parts/store_cat_filter.php'`
- `_once plugin_dir_path(__FILE__) . 'parts/store_product_card.php'`
- `_once plugin_dir_path(__FILE__) . 'parts/store_fairs.php'`
- `_once plugin_dir_path(__FILE__) . 'parts/store_modals.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
