# `includes/profile/profile.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 12408 B
- **Liczba linii:** 254
- **Źródło:** `includes/profile/profile.php`

## Klasy i metody

### `PWEProfile` — linia 7

  - `public __construct()` — linia 17
  - `public initVCMapPWEProfile()` — linia 36
  - `private findClassElements()` — linia 161
  - `public static multi_translation($key)` — linia 175
  - `public PWEProfileOutput($atts, $content = null)` — linia 201

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 29

## Wybrane wywołania statyczne

- `PWEProfileTabs::initElements()`
- `PWEProfileSiteTabs::initElements()`
- `PWEProfileAllInOne::initElements()`
- `PWEProfileSingle::initElements()`
- `PWEProfileThreeCols::initElements()`
- `PWEProfileButtons::initElements()`
- `PWEProfileCards::initElements()`
- `PWEProfileExpanding::initElements()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `get_locale()`
- `shortcode_atts()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/profile-tabs.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/profile-sitetabs.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/profile-all-in-one.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/profile-single.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/profile-three-cols.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/profile-buttons.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/profile-cards.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/profile-expanding.php'`
- `s/profile.json'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$profile_type]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
