# `includes/nav-menu/nav-menu.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 15028 B
- **Liczba linii:** 335
- **Źródło:** `includes/nav-menu/nav-menu.php`

## Klasy i metody

### `pweNavMenu` — linia 3

  - `public __construct()` — linia 5
  - `public detect_override()` — linia 21
  - `public addingStyles()` — linia 36
  - `public addingScripts()` — linia 43
  - `public pwe_nav_menu()` — linia 56
  - `private display_sub_menu($parent_id, $menu_items, $depth = 1)` — linia 225
### `Collapse_Adminbar` — linia 280

  - `public static init()` — linia 282
  - `public static hooks()` — linia 286
  - `public static collapse_styles()` — linia 294

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp_enqueue_scripts` — linia 10
- **action:** `wp_enqueue_scripts` — linia 11
- **action:** `init` — linia 14
- **action:** `wp_head` — linia 17
- **action:** `admin_bar_init` — linia 283
- **action:** `wp_head` — linia 291

## Wybrane wywołania statyczne

- `self::languageChecker()`
- `self::lang_pl()`
- `Collapse_Adminbar::init()`

## API WordPress rozpoznane heurystycznie

- `get_option()`
- `add_action()`
- `apply_filters()`
- `plugins_url()`
- `plugin_dir_path()`
- `wp_enqueue_style()`
- `do_shortcode()`
- `wp_enqueue_script()`
- `wp_localize_script()`
- `wp_get_nav_menu_items()`
- `esc_url()`
- `wp_kses_post()`
- `esc_attr()`
- `is_admin()`
- `is_multisite()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
