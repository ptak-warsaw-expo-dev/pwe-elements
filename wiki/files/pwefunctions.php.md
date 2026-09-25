# `pwefunctions.php`

Lokalny fallback wspólnych funkcji używany bez PWE System.

## Metadane

- **Kategoria:** `compatibility-fallback`
- **Rozmiar:** 175633 B
- **Liczba linii:** 4308
- **Źródło:** `pwefunctions.php`

## Klasy i metody

### `PWECommonFunctions` — linia 4

  - `public static id_rnd()` — linia 13
  - `public static lang()` — linia 21
  - `public static add_log($message, $filename = 'logs')` — linia 45
  - `private static debug_log($message, $type = 'log')` — linia 63
  - `public static output_db_connection_logs()` — linia 89
### `self` — linia 96

- Brak metod.
### `true` — linia 207

- Brak metod.
### `is_dir` — linia 312

- Brak metod.
### `round` — linia 759

- Brak metod.

## Funkcje globalne

- `resolve_server_addr_fallback()` — linia 115
- `get_database_servers()` — linia 134
- `connect_database()` — linia 193
- `set_db_timeout()` — linia 283
- `get_database_json_cache_dir()` — linia 304
- `get_database_json_cache_path($source, $cache_key)` — linia 336
- `pack_database_json_value($value)` — linia 350
- `unpack_database_json_value($value)` — linia 374
- `read_database_json_cache($source, $cache_key)` — linia 401
- `write_database_json_cache($source, $cache_key, $data, array $args = [])` — linia 432
- `refresh_database_json_cache($domain = null)` — linia 493
- `get_database_fairs_data($fair_domain = null)` — linia 889
- `get_database_fairs_data_adds($fair_domain = null)` — linia 1135
- `get_database_translations_data($fair_domain = null)` — linia 1258
- `get_database_associates_data($fair_domain = null,
            bool $fair_block = false)` — linia 1481
- `get_database_store_data()` — linia 1615
- `get_database_store_packages_data()` — linia 1708
- `get_database_meta_data($data_id = null, $domain = null)` — linia 1796
- `get_database_groups_contacts_data()` — linia 1909
- `get_database_groups_callcenter_data()` — linia 1989
- `get_database_groups_data()` — linia 2068
- `get_database_week_data($fair_domain = null)` — linia 2147
- `get_database_week_all($fair_domain = null)` — linia 2234
- `get_all_week_domains()` — linia 2322
- `get_database_logotypes_data($fair_domain = null)` — linia 2409
- `get_database_conferences_data($domain = null)` — linia 2528
- `get_database_conference_adds_data($conf_id)` — linia 2630
- `get_database_fairs_data_profiles($fair_domain = null)` — linia 2726
- `get_database_premieres_data($fair_domain = null)` — linia 2801
- `get_database_fairs_data_opinions($fair_domain = null)` — linia 2884
- `get_database_fairs_data_sectors($fair_domain = null)` — linia 2969
- `get_database_fairs_data_tickets($fair_domain = null)` — linia 3053
- `get_database_fairs_data_speakers($fair_domain = null)` — linia 3137
- `get_database_fairs_data_guests($fair_domain = null)` — linia 3226
- `get_database_fairs_data_attractions($fair_domain = null)` — linia 3313
- `get_database_fairs_data_files($fair_domain = null)` — linia 3400
- `get_database_elements_data()` — linia 3487
- `get_database_elements_order_data()` — linia 3580
- `remove_logo_duplicates(array $logos)` — linia 3675
- `pwe_color($color)` — linia 3706
- `generate_fair_data($fair)` — linia 3731
- `generate_fair_translation_data($fair)` — linia 3797
- `json_fairs()` — linia 3835
- `transform_dates($start_date, $end_date, $include_hours = true)` — linia 3937
- `decode_clean_content($encoded_content)` — linia 3972
- `json_decode($encoded_variable)` — linia 3981
- `findColor($primary, $secondary, $default = '')` — linia 3990
- `findPalletColorsStatic()` — linia 4005
- `findPalletColors()` — linia 4035
- `lang_pl()` — linia 4065
- `languageChecker($pl, $en = '', $de = '')` — linia 4076
- `adjustBrightness($hex, $steps)` — linia 4091
- `findFormsGF($mode = '')` — linia 4114
- `findFormsID($form_name)` — linia 4139
- `checkForMobile()` — linia 4158
- `findBestLogo($logo_color = false)` — linia 4168
- `findAllImages($firstPath, $image_count = false, $secondPath = '/doc/galeria')` — linia 4218
- `findBestFile($file_path)` — linia 4246
- `isTradeDateExist()` — linia 4265
- `inputRange()` — linia 4282
- `input_range_field_html($settings, $value)` — linia 4287

## Rejestracje WordPress / GF

- **filter:** `wpdb_connect_timeout` — linia 207
- **action:** `wp_footer` — linia 4307

## Wybrane wywołania statyczne

- `self::resolve_server_addr_fallback()`
- `self::get_database_servers()`
- `self::add_log()`
- `self::debug_log()`
- `self::get_database_json_cache_dir()`
- `self::pack_database_json_value()`
- `self::unpack_database_json_value()`
- `self::get_database_json_cache_path()`
- `self::read_database_json_cache()`
- `self::connect_database()`
- `self::write_database_json_cache()`
- `self::remove_logo_duplicates()`
- `self::findPalletColorsStatic()`
- `self::get_database_fairs_data()`
- `self::get_database_translations_data()`
- `self::generate_fair_data()`
- `self::generate_fair_translation_data()`
- `DateTime::createFromFormat()`
- `GFAPI::get_forms()`

## API WordPress rozpoznane heurystycznie

- `apply_filters()`
- `get_locale()`
- `wp_upload_dir()`
- `current_user_can()`
- `is_admin()`
- `add_filter()`
- `wp_mkdir_p()`
- `wp_json_encode()`
- `get_option()`
- `wp_die()`
- `do_shortcode()`
- `esc_attr()`
- `add_action()`

## Dołączane pliki / wyrażenia include

- `_hours = true) {`
- `_hours ? "Y/m/d H:i" : "Y/m/d"`

## Tabele / właściwości `$wpdb`

- `dbh`
- `get_var`

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
