# `pwelements.php`

Główny bootstrap wtyczki i warstwa kompatybilności.

## Metadane

- **Kategoria:** `bootstrap`
- **Rozmiar:** 16320 B
- **Liczba linii:** 415
- **Źródło:** `pwelements.php`

## Klasy i metody

### `PWElementsPlugin` — linia 14

  - `public __construct()` — linia 40
  - `public get_count_views()` — linia 103
  - `public display_views($content)` — linia 127
  - `public add_date_to_post($content)` — linia 136
  - `public pwe_enqueue_styles()` — linia 146
  - `public enqueue_slick_assets()` — linia 158
  - `public enqueue_swiper_assets()` — linia 165
  - `private hasPweSystem()` — linia 174
  - `private initSystemCompatibility()` — linia 212
  - `private initClasses()` — linia 243
  - `public clearWpRocketCacheOnPluginUpdate($upgrader_object, $options)` — linia 362
  - `private getGithubKey()` — linia 375
  - `private init()` — linia 394

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `upgrader_process_complete` — linia 46
- **action:** `init` — linia 54
- **action:** `wp_head` — linia 59
- **action:** `wp_enqueue_scripts` — linia 62
- **action:** `wp_enqueue_scripts` — linia 63
- **action:** `wp_enqueue_scripts` — linia 64
- **filter:** `rocket_delay_js_exclusions` — linia 79
- **filter:** `rocket_exclude_defer_js` — linia 84
- **filter:** `the_content` — linia 88

## Wybrane wywołania statyczne

- `PWECommonFunctions::lang_pl()`
- `Puc_v4_Factory::buildUpdateChecker()`
- `self::getGithubKey()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_filter()`
- `is_admin()`
- `is_single()`
- `get_queried_object_id()`
- `get_post_meta()`
- `update_post_meta()`
- `is_ssl()`
- `is_singular()`
- `in_the_loop()`
- `is_main_query()`
- `get_the_ID()`
- `get_the_date()`
- `get_locale()`
- `esc_html()`
- `plugin_dir_path()`
- `wp_enqueue_style()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_script()`
- `get_option()`
- `is_multisite()`
- `get_site_option()`

## Dołączane pliki / wyrażenia include

- `s/nav-menu/assets/script.js',`
- `_once $system_path . 'core/class-pwe-system-functions.php'`
- `_once $system_path . 'modules/shortcodes/backend-shortcodes.php'`
- `_once plugin_dir_path(__FILE__) . 'pwefunctions.php'`
- `_once plugin_dir_path(__FILE__) . 'backend/shortcodes.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/settings/admin-menu.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/settings/general-settings.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/settings/nav-menu-settings.php'`
- `_once plugin_dir_path(__FILE__) . 'pwe-style-var.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/nav-menu/nav-menu.php'`
- `_once plugin_dir_path(__FILE__) . 'elements/pwelements-options.php'`
- `_once plugin_dir_path(__FILE__) . 'gf-addons/area-numbers/area_numbers_gf.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/katalog-wystawcow/main-katalog-wystawcow.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/exhibitor-generator/exhibitor-generator.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/profile/profile.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/header/header.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/logotypes/logotypes.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/display-info/display-info.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/media-gallery/media-gallery.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/registration/registration.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/map/map.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/store/store.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/conference-cap/conference_cap.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/calendar/calendar.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/conference-calendar/conference-calendar.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/industry-evening/industry-evening.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/reviews/reviews.php'`
- `_once plugin_dir_path(__FILE__) . 'other/test.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/conference-short-info/conference-short-info.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/about-fair-info/about-fair-info.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/premieres/premieres.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/attractions/attractions.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/news/news.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/posts/posts.php'`
- `_once plugin_dir_path(__FILE__) . 'includes/article_author/article_author.php'`
- `plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php')`

## Tabele / właściwości `$wpdb`

- `custom_klavio_setup`
- `prefix`
- `get_var`
- `prepare`
- `get_results`

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
