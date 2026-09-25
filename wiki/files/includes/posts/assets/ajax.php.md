# `includes/posts/assets/ajax.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 9160 B
- **Liczba linii:** 227
- **Źródło:** `includes/posts/assets/ajax.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `pwe_ajax_load_posts()` — linia 5

## Rejestracje WordPress / GF

- **action:** `wp_ajax_pwe_ajax_load_posts` — linia 2
- **action:** `wp_ajax_nopriv_pwe_ajax_load_posts` — linia 3
- **filter:** `posts_where` — linia 58

## Wybrane wywołania statyczne

- `PWECommonFunctions::lang_pl()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `sanitize_text_field()`
- `wp_send_json()`
- `add_filter()`
- `get_the_ID()`
- `wp_strip_all_tags()`
- `get_permalink()`
- `has_post_thumbnail()`
- `get_the_post_thumbnail_url()`
- `get_the_title()`
- `get_the_date()`
- `get_locale()`
- `wp_reset_postdata()`
- `wp_die()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- `esc_like`
- `prepare`
- `posts`

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
