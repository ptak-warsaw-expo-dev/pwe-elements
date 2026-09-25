# `elements/pot_rej_wys.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 49060 B
- **Liczba linii:** 1067
- **Źródło:** `elements/pot_rej_wys.php`

## Klasy i metody

### `PWElementStepTwoExhibitor` — linia 7

  - `public __construct()` — linia 13
  - `public hideFieldsBasedOnAdminLabel($form)` — linia 23
  - `public static multi_translation($key)` — linia 36
  - `public static get_translations()` — linia 55
  - `public static initElements()` — linia 72
  - `public static output($atts, $content = '')` — linia 124

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **filter:** `gform_pre_render` — linia 15

## Wybrane wywołania statyczne

- `parent::__construct()`
- `self::findColor()`
- `PWECommonFunctions::get_database_groups_data()`
- `PWECommonFunctions::get_database_groups_contacts_data()`
- `self::multi_translation()`
- `PWElementStepTwoExhibitor::get_translations()`

## API WordPress rozpoznane heurystycznie

- `add_filter()`
- `get_locale()`
- `shortcode_atts()`
- `plugins_url()`

## Dołączane pliki / wyrażenia include

- `d/>`
- `s("e-mail address")) {`
- `s("telephone number for marketing purposes")) {`
- `dInfo = Array.from(document.querySelectorAll("p"))`
- `s("campi contrassegnati"))`
- `dInfo) {`
- `dInfo, "* ' . self::multi_translation("required_fields") . '")`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
