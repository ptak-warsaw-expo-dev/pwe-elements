# `includes/calendar/calendar.php`

Plik first-party PWE Elements w kategorii `calendar`.

## Metadane

- **Kategoria:** `calendar`
- **Rozmiar:** 75781 B
- **Liczba linii:** 1773
- **Źródło:** `includes/calendar/calendar.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `pwe_calendar_single_template($single_template)` — linia 4
- `custom_meta_description()` — linia 17
- `get_translated_field($fair, $field_base_name)` — linia 32
- `get_pwe_shortcode($shortcode, $domain)` — linia 51
- `check_available_pwe_shortcode($shortcodes_active, $shortcode)` — linia 57
- `create_event_post_type()` — linia 84
- `create_event_type_taxonomy()` — linia 137
- `pwe_wpml_source_from_request()` — linia 241
- `pwe_get_event_type_slug($post_id)` — linia 274
- `featured_image_meta_box_callback($post)` — linia 574
- `secondary_image_meta_box_callback($post)` — linia 629
- `header_image_callback($post)` — linia 684
- `events_week_fairs_callback($post)` — linia 742
- `events_week_desc_callback($post)` — linia 808
- `events_week_link_callback($post)` — linia 820
- `events_week_dates_callback($post)` — linia 839
- `events_week_halls_callback($post)` — linia 855
- `events_week_other_callback($post)` — linia 911
- `save_events_week_meta($post_id)` — linia 958
- `event_links_callback($post)` — linia 1044
- `event_desc_callback($post)` — linia 1100
- `event_dates_callback($post)` — linia 1126
- `event_colors_callback($post)` — linia 1187
- `event_statistics_callback($post)` — linia 1210
- `event_organizer_callback($post)` — linia 1245
- `event_other_callback($post)` — linia 1276
- `logo_image_callback($post)` — linia 1305
- `partners_gallery_callback($post)` — linia 1364
- `save_event_meta($post_id)` — linia 1480
- `hide_secondary_thumbnail_meta_box()` — linia 1564
- `load_datepicker_styles()` — linia 1569
- `load_datepicker_scripts($hook)` — linia 1622
- `load_color_picker_script($hook)` — linia 1654
- `load_admin_styles($hook)` — linia 1677
- `move_content_editor_to_bottom()` — linia 1747
- `add_content_editor_to_bottom()` — linia 1757

## Rejestracje WordPress / GF

- **filter:** `single_template` — linia 14
- **action:** `wp_head` — linia 81
- **action:** `init` — linia 134
- **action:** `init` — linia 167
- **action:** `admin_menu` — linia 170
- **action:** `restrict_manage_posts` — linia 207
- **filter:** `parse_query` — linia 229
- **action:** `load-post-new.php` — linia 287
- **action:** `add_meta_boxes_event` — linia 309
- **action:** `save_post_event` — linia 530
- **action:** `admin_menu` — linia 556
- **action:** `save_post` — linia 1034
- **action:** `admin_enqueue_scripts` — linia 1036
- **action:** `save_post` — linia 1561
- **action:** `admin_head` — linia 1567
- **action:** `admin_head` — linia 1619
- **action:** `admin_enqueue_scripts` — linia 1651
- **action:** `admin_enqueue_scripts` — linia 1674
- **action:** `admin_enqueue_scripts` — linia 1744
- **action:** `edit_form_after_editor` — linia 1752
- **action:** `do_meta_boxes` — linia 1754

## Wybrane wywołania statyczne

- `PWECommonFunctions::get_database_translations_data()`
- `PWECommonFunctions::json_fairs()`

## API WordPress rozpoznane heurystycznie

- `plugin_dir_path()`
- `add_filter()`
- `get_post_meta()`
- `get_option()`
- `get_locale()`
- `shortcode_exists()`
- `do_shortcode()`
- `esc_attr()`
- `add_action()`
- `register_post_type()`
- `register_taxonomy()`
- `get_term_by()`
- `wp_insert_term()`
- `get_terms()`
- `esc_html()`
- `sanitize_text_field()`
- `apply_filters()`
- `wp_get_post_terms()`
- `is_wp_error()`
- `wp_safe_redirect()`
- `update_post_meta()`
- `wp_set_post_terms()`
- `wp_nonce_field()`
- `esc_url()`
- `wp_verify_nonce()`
- `delete_post_meta()`
- `wp_enqueue_media()`
- `get_the_terms()`
- `wp_list_pluck()`
- `wp_enqueue_script()`
- `wp_register_style()`
- `wp_enqueue_style()`
- `wp_add_inline_script()`
- `wp_editor()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'classes/loop-calendar.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
