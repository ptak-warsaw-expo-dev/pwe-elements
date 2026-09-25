# `includes/exhibitor-generator/classes/exhibitor-visitor-generator.php`

Plik first-party PWE Elements w kategorii `exhibitor-generator`.

## Metadane

- **Kategoria:** `exhibitor-generator`
- **Rozmiar:** 7415 B
- **Liczba linii:** 185
- **Źródło:** `includes/exhibitor-generator/classes/exhibitor-visitor-generator.php`

## Klasy i metody

### `PWEExhibitorVisitorGenerator` — linia 8

  - `public __construct()` — linia 14
  - `public static fairStartDateCheck()` — linia 27
  - `public static senderFlowChecker()` — linia 47

## Funkcje globalne

- `output($atts)` — linia 91

## Rejestracje WordPress / GF

- **action:** `init` — linia 17
- **filter:** `gform_allow_html_field_label` — linia 18

## Wybrane wywołania statyczne

- `parent::__construct()`
- `PWECommonFunctions::lang_pl()`
- `PWECommonFunctions::get_gf_form_id()`
- `self::catalog_data()`
- `PWECommonFunctions::get_database_fairs_data()`
- `PWECommonFunctions::get_database_logotypes_data()`
- `PWECommonFunctions::get_database_conferences_data()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_filter()`
- `do_shortcode()`
- `shortcode_atts()`
- `wp_get_attachment_url()`
- `plugins_url()`
- `site_url()`
- `plugin_dir_path()`

## Dołączane pliki / wyrażenia include

- `s/exhibitor-generator/assets/media/logotyp_wystawcy.png'`
- `_once plugin_dir_path(__DIR__) . 'assets/visitors_gr1.php'`
- `_once plugin_dir_path(__DIR__) . 'assets/visitors_gr2.php'`
- `_once plugin_dir_path(__DIR__) . 'assets/visitors_gr3.php'`

## Tabele / właściwości `$wpdb`

- `mass_exhibitors_invite_query`
- `prefix`
- `get_var`
- `prepare`

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
