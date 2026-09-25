# `backend/shortcodes.php`

Lokalny fallback niskopoziomowych shortcode'ów `[pwe_*]` używany bez PWE System.

## Metadane

- **Kategoria:** `compatibility-fallback`
- **Rozmiar:** 11110 B
- **Liczba linii:** 292
- **Źródło:** `backend/shortcodes.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `get_fair_data($specific_domain = null)` — linia 3
- `pwe_get_shortcode_map()` — linia 142
- `register_dynamic_shortcodes()` — linia 232
- `handle_fair_shortcode($atts, $field)` — linia 237
- `PWE_GF_shortcodes($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format)` — linia 255

## Rejestracje WordPress / GF

- **action:** `init` — linia 251
- **filter:** `gform_replace_merge_tags` — linia 253

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_fairs_data()`
- `PWECommonFunctions::get_database_translations_data()`
- `PWECommonFunctions::generate_fair_data()`
- `PWECommonFunctions::generate_fair_translation_data()`

## API WordPress rozpoznane heurystycznie

- `current_user_can()`
- `is_admin()`
- `home_url()`
- `apply_filters()`
- `shortcode_atts()`
- `add_shortcode()`
- `add_action()`
- `add_filter()`
- `esc_html()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
