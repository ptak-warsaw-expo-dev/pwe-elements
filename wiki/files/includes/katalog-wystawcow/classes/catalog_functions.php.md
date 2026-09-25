# `includes/katalog-wystawcow/classes/catalog_functions.php`

Plik first-party PWE Elements w kategorii `exhibitor-catalog`.

## Metadane

- **Kategoria:** `exhibitor-catalog`
- **Rozmiar:** 35328 B
- **Liczba linii:** 843
- **Źródło:** `includes/katalog-wystawcow/classes/catalog_functions.php`

## Klasy i metody

### `CatalogFunctions` — linia 3

  - `public static findClassElements()` — linia 10
  - `public static logosChecker($katalog_id, $PWECatalogFull = 'PWECatalogFull', $pwe_catalog_random = false, $file_changer = null, $catalog_display_duplicate = false, $catalog_year = null)` — linia 29
  - `public static sync_archive_catalog_entry($katalog_id, $catalog_year = null)` — linia 264

## Funkcje globalne

- `compareDates($a, $b)` — linia 216
- `orderChanger($change, $data)` — linia 447
- `multi_translation($key, $plural = false)` — linia 528
- `checkTitle($title, $format)` — linia 553
- `vcMapPWECatalogCustom()` — linia 570
- `initVCMapPWECatalog()` — linia 598

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_meta_data()`
- `self::orderChanger()`
- `PWECommonFunctions::add_log()`
- `self::multi_translation()`
- `PWECommonFunctions::findPalletColorsStatic()`

## API WordPress rozpoznane heurystycznie

- `current_user_can()`
- `wp_localize_script()`
- `wp_mkdir_p()`
- `get_locale()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `s/katalog-wystawcow.json'`

## Tabele / właściwości `$wpdb`

- `last_error`
- `get_var`
- `prepare`
- `query`

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
