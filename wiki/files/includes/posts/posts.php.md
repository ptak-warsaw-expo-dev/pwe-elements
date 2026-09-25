# `includes/posts/posts.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 5155 B
- **Liczba linii:** 146
- **Źródło:** `includes/posts/posts.php`

## Klasy i metody

### `PWEPosts` — linia 6

  - `public __construct()` — linia 12
  - `public load_more_posts()` — linia 24
  - `public initVCMapPwePosts()` — linia 31
  - `private findClassElements()` — linia 104
  - `public pwePostsOutput($atts)` — linia 117

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 15
- **action:** `init` — linia 17

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `shortcode_atts()`
- `do_shortcode()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'assets/ajax.php'`
- `_once plugin_dir_path(__FILE__) . 'classes/posts-full/posts_full.php'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$posts_modes]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
