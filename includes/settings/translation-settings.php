<?php

if (!defined('ABSPATH')) {
    exit;
}

function pwe_get_translation_json_path()
{
    return plugin_dir_path(__FILE__) . 'website-translation.json';
}

function pwe_get_translation_language_names()
{
    return [
        'cs' => 'Czeski',
        'de' => 'Niemiecki',
        'en' => 'Angielski',
        'it' => 'Włoski',
        'lt' => 'Litewski',
        'lv' => 'Łotewski',
        'pl' => 'Polski',
        'sk' => 'Słowacki',
        'uk' => 'Ukraiński',
    ];
}

// Show available languages in JSON and active WPML languages, with badges and flags
function pwe_render_translation_languages_overview()
{
    $json_path = pwe_get_translation_json_path();

    if (!file_exists($json_path)) {
        echo '<div class="notice notice-warning inline"><p>Nie można odczytać listy języków — plik JSON nie istnieje.</p></div>';
        return;
    }

    $pages = json_decode(file_get_contents($json_path), true);

    if (!is_array($pages)) {
        echo '<div class="notice notice-warning inline"><p>Nie można odczytać listy języków — nieprawidłowy JSON.</p></div>';
        return;
    }

    $skip_languages = ['pl', 'en'];
    $json_language_codes = [];

    foreach ($pages as $translations) {
        if (!is_array($translations)) {
            continue;
        }

        foreach ($translations as $lang_code => $data) {
            $json_language_codes[$lang_code] = true;
        }
    }

    $json_language_codes = array_keys($json_language_codes);
    sort($json_language_codes);

    $active_languages = apply_filters('wpml_active_languages', null, ['skip_missing' => 0]);
    $active_languages = is_array($active_languages) ? $active_languages : [];

    $active_language_codes = array_keys($active_languages);

    $available_for_sync = [];

    if (isset($pages['home']) && is_array($pages['home'])) {
        foreach ($pages['home'] as $lang_code => $data) {
            if (in_array($lang_code, $skip_languages, true)) {
                continue;
            }

            if (!in_array($lang_code, $active_language_codes, true)) {
                continue;
            }

            $available_for_sync[] = $lang_code;
        }
    }

    echo '<div style="margin: 12px 0 18px;">';

    echo '<div style="padding: 14px 18px 16px; background: var(--color-background-primary, #fff); border: 0.5px solid #ccd0d4; border-radius: 8px; margin-bottom: 8px;">';
    echo '<p style="margin: 0 0 10px; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; color: #646970;">Języki dostępne w pliku JSON</p>';
    pwe_render_translation_language_badges($json_language_codes, $active_languages, $skip_languages, false);
    echo '</div>';

    echo '<div style="padding: 14px 18px 16px; background: var(--color-background-primary, #fff); border: 0.5px solid #ccd0d4; border-radius: 8px;">';
    echo '<p style="margin: 0 0 10px; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; color: #646970;">Języki aktywne w WPML — zostaną użyte do tłumaczeń</p>';
    pwe_render_translation_language_badges($available_for_sync, $active_languages, $skip_languages, true);
    echo '</div>';

    echo '</div>';
}

// Renders badges for given language codes, showing their status (active in WPML, skipped, etc.) with flags and labels
function pwe_render_translation_language_badges(array $language_codes, array $active_languages, array $skip_languages = [], bool $sync_mode = false): void
{
    if (empty($language_codes)) {
        echo '<p style="margin:0;font-size:13px;color:#646970;"><em>Brak.</em></p>';
        return;
    }

    echo '<div style="display:flex;flex-wrap:wrap;gap:6px;">';

    $language_names = pwe_get_translation_language_names();

    foreach ($language_codes as $lang_code) {
        $is_skipped   = in_array($lang_code, $skip_languages, true);
        $is_active_wpml = isset($active_languages[$lang_code]);

        $label = $language_names[$lang_code] ?? $lang_code;
        if (!empty($active_languages[$lang_code]['translated_name'])) {
            $label = $active_languages[$lang_code]['translated_name'];
        } elseif (!empty($active_languages[$lang_code]['native_name'])) {
            $label = $active_languages[$lang_code]['native_name'];
        } elseif (!empty($active_languages[$lang_code]['display_name'])) {
            $label = $active_languages[$lang_code]['display_name'];
        }

        if (!empty($active_languages[$lang_code]['country_flag_url'])) {
            $flag_url = $active_languages[$lang_code]['country_flag_url'];
        } elseif (defined('ICL_PLUGIN_URL')) {
            $flag_url = ICL_PLUGIN_URL . '/res/flags/' . sanitize_key($lang_code) . '.png';
        } else {
            $flag_url = '';
        }

        $flag = $flag_url
            ? '<img src="' . esc_url($flag_url) . '" alt="" style="width:16px;height:auto;border-radius:2px;flex-shrink:0;">'
            : '';

        $status = '';
        // if ($is_skipped) {
        //     $status = ' — pomijany';
        // } elseif (!$is_active_wpml) {
        //     $status = ' — nieaktywny';
        // } elseif ($sync_mode) {
        //     $status = ' — zostanie użyty';
        // }

        // Style per state
        if ($is_skipped) {
            $badge_style = 'border-color:#f0b849;background:#fef9ec;';
            $name_style  = 'color:#996800;';
        } elseif (!$is_active_wpml) {
            $badge_style = 'border-color:#ccd0d4;background:#f6f7f7;opacity:0.65;';
            $name_style  = 'color:#646970;';
        } elseif ($sync_mode) {
            $badge_style = 'border-color:#68de7c;background:#edfaef;';
            $name_style  = 'color:#0a6b24;';
        } else {
            $badge_style = 'border-color:#ccd0d4;background:#f6f7f7;';
            $name_style  = 'color:#50575e;';
        }

        echo '<span style="flex:1;max-width:120px;display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:6px;border:0.5px solid #ccd0d4;font-size:13px;line-height:1.4;' . $badge_style . '">';
        echo $flag;
        echo '<strong style="font-size:12px;font-weight:500;color:#1d2327;">' . esc_html(strtoupper($lang_code)) . '</strong>';
        echo '<span style="width:1px;height:12px;background:#ccd0d4;margin:0 2px;align-self:center;"></span>';
        echo '<span style="' . $name_style . '">' . esc_html($label . $status) . '</span>';
        echo '</span>';
    }

    echo '</div>';
}

// Main function to render the translation sync panel, handle form submission, and display results
function pwe_render_translation_sync_panel()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $result = null;

    if (
        isset($_POST['pwe_wpml_sync_nonce']) &&
        wp_verify_nonce($_POST['pwe_wpml_sync_nonce'], 'pwe_wpml_sync_pages')
    ) {
        try {
            $result = pwe_create_missing_wpml_pages_from_json();
        } catch (Throwable $e) {
            $result = [
                'status'  => 'critical_error',
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ];
        }
    }

    echo '<h2>Tworzenie stron WPML z JSON</h2>';
    echo '<p>Tworzy brakujące tłumaczenia stron z pliku <code>' . esc_html(basename(pwe_get_translation_json_path())) . '</code>.</p>';
    echo '<p><strong>PL i EN są pomijane.</strong> EN jest używany jako wzorzec.</p>';
    pwe_render_translation_languages_overview();

    if ($result) {
        echo '<div class="notice notice-info"><p><strong>Wynik synchronizacji:</strong></p></div>';
        echo '<pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-height:600px;overflow:auto;">';
        echo esc_html(print_r($result, true));
        echo '</pre>';
    }

    echo '<form method="post">';
    wp_nonce_field('pwe_wpml_sync_pages', 'pwe_wpml_sync_nonce');
    submit_button('Utwórz brakujące tłumaczenia');
    echo '</form>';
}

// Core function to create missing WPML pages based on the JSON configuration, with detailed logging and error handling
function pwe_create_missing_wpml_pages_from_json()
{
    if (!defined('ICL_SITEPRESS_VERSION')) {
        return [
            'status' => 'error',
            'message' => 'WPML nie jest aktywny.'
        ];
    }

    $json_path = pwe_get_translation_json_path();

    if (!file_exists($json_path)) {
        return [
            'status' => 'error',
            'message' => 'Nie znaleziono pliku website-translation.json: ' . $json_path
        ];
    }

    $pages = json_decode(file_get_contents($json_path), true);

    if (!is_array($pages)) {
        return [
            'status' => 'error',
            'message' => 'Nieprawidłowy JSON.'
        ];
    }

    $skip_languages = ['pl', 'en'];

    $active_languages = apply_filters('wpml_active_languages', null, ['skip_missing' => 0]);
    $active_language_codes = is_array($active_languages) ? array_keys($active_languages) : [];

    $allowed_language_codes = [];

    if (isset($pages['home']) && is_array($pages['home'])) {
        foreach ($pages['home'] as $home_lang_code => $home_data) {
            if (in_array($home_lang_code, $skip_languages, true)) {
                continue;
            }

            if (!in_array($home_lang_code, $active_language_codes, true)) {
                continue;
            }

            $allowed_language_codes[] = $home_lang_code;
        }
    }

    $summary = [
        'allowed_languages_by_home' => $allowed_language_codes,
        'created' => [],
        'skipped_existing' => [],
        'skipped_no_en_source' => [],
        'skipped_invalid_data' => [],
        'errors' => [],
    ];

    foreach ($pages as $page_key => $translations) {
        if (empty($translations['en']['url'])) {
            $summary['skipped_invalid_data'][] = $page_key . ' — brak EN URL';
            continue;
        }

        $is_home = ($translations['en']['url'] === '/');

        if ($is_home) {
            $en_page = pwe_get_home_page_in_lang('en');
        } else {
            $en_page = pwe_find_page_by_url_and_lang($translations['en']['url'], 'en');
        }

        if (!$en_page) {
            $summary['skipped_no_en_source'][] = $page_key . ' — nie znaleziono strony EN: ' . $translations['en']['url'];
            continue;
        }

        $element_type = apply_filters('wpml_element_type', 'page');

        $trid = apply_filters(
            'wpml_element_trid',
            null,
            $en_page->ID,
            $element_type
        );

        if (!$trid) {
            $summary['errors'][] = $page_key . ' — nie udało się pobrać TRID strony EN';
            continue;
        }

        foreach ($translations as $lang_code => $data) {
            if (in_array($lang_code, $skip_languages, true)) {
                continue;
            }

            if (!in_array($lang_code, $allowed_language_codes, true)) {
                continue;
            }

            if (empty($data['label']) || !isset($data['url'])) {
                $summary['skipped_invalid_data'][] = $page_key . ' / ' . $lang_code . ' — brak label lub url';
                continue;
            }

            if ($data['url'] === '/') {
                $existing_page = pwe_get_home_page_in_lang($lang_code);
            } else {
                $existing_page = pwe_find_page_by_url_and_lang($data['url'], $lang_code);
            }

            if ($existing_page) {
                $summary['skipped_existing'][] = $page_key . ' / ' . $lang_code . ' — istnieje: ' . $data['url'];
                continue;
            }

            $slug = ($data['url'] === '/')
                ? 'home-' . sanitize_key($lang_code)
                : pwe_get_slug_from_url($data['url']);

            if ($slug === '' && $data['url'] !== '/') {
                $summary['errors'][] = $page_key . ' / ' . $lang_code . ' — pusty slug po sanitizacji: ' . $data['url'];
                continue;
            }

            $parent_id = pwe_get_translated_parent_id($en_page->post_parent, $lang_code);

            $new_page_id = wp_insert_post([
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'post_title'     => sanitize_text_field($data['label']),
                'post_name'      => $slug,
                'post_content'   => wp_slash($en_page->post_content),
                'post_excerpt'   => wp_slash($en_page->post_excerpt),
                'post_parent'    => $parent_id !== null ? $parent_id : 0,
                'comment_status' => $en_page->comment_status,
                'ping_status'    => $en_page->ping_status,
                'menu_order'     => $en_page->menu_order,
            ], true);

            if (is_wp_error($new_page_id)) {
                $summary['errors'][] = $page_key . ' / ' . $lang_code . ' — wp_insert_post: ' . $new_page_id->get_error_message();
                continue;
            }

            do_action('wpml_set_element_language_details', [
                'element_id'           => $new_page_id,
                'element_type'         => $element_type,
                'trid'                 => $trid,
                'language_code'        => $lang_code,
                'source_language_code' => 'en',
            ]);

            pwe_copy_basic_page_meta($en_page->ID, $new_page_id);

            update_post_meta($new_page_id, '_pwe_json_page_key', sanitize_key($page_key));
            update_post_meta($new_page_id, '_pwe_json_language', sanitize_key($lang_code));
            update_post_meta($new_page_id, '_pwe_json_url', esc_url_raw($data['url']));
            update_post_meta($new_page_id, '_pwe_created_as_home_translation', $data['url'] === '/' ? '1' : '0');

            clean_post_cache($new_page_id);

            $summary['created'][] = $page_key . ' / ' . $lang_code . ' — utworzono: ' . $data['url'];
        }
    }

    flush_rewrite_rules(false);

    return $summary;
}

// Finds the home page for a given language code, using WPML's translation tables to ensure correct matching
function pwe_get_home_page_in_lang(string $lang_code): ?WP_Post
{
    $front_id = (int) get_option('page_on_front');

    if (!$front_id) {
        return null;
    }

    $translated_id = apply_filters('wpml_object_id', $front_id, 'page', false, $lang_code);

    if (!$translated_id) {
        return null;
    }

    $post = get_post((int) $translated_id);

    return ($post && $post->post_type === 'page') ? $post : null;
}

// Finds a page by its URL and language code, using WPML's translation tables to ensure correct matching
function pwe_find_page_by_url_and_lang(string $url, string $lang_code): ?WP_Post
{
    global $wpdb;

    $slug = pwe_get_slug_from_url($url);

    if ($url === '/' || $slug === '') {
        return pwe_get_home_page_in_lang($lang_code);
    }

    $element_type = 'post_page';

    $post_ids = $wpdb->get_col(
        $wpdb->prepare(
            "
            SELECT p.ID
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->prefix}icl_translations t
                ON t.element_id = p.ID
            WHERE p.post_type = 'page'
              AND p.post_name = %s
              AND t.element_type = %s
              AND t.language_code = %s
              AND p.post_status NOT IN ('trash', 'auto-draft')
            LIMIT 1
            ",
            $slug,
            $element_type,
            $lang_code
        )
    );

    if (!empty($post_ids[0])) {
        return get_post((int) $post_ids[0]);
    }

    return null;
}

// Converts a URL to a slug by extracting the last path segment, decoding it, removing accents, and sanitizing it for use as a post slug
function pwe_get_slug_from_url(string $url): string
{
    $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

    if ($path === '') {
        return '';
    }

    $parts = explode('/', $path);
    $slug = end($parts);

    $slug = urldecode($slug);
    $slug = remove_accents($slug);
    $slug = strtolower($slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');

    return sanitize_title($slug);
}

// Retrieves the translated parent page ID for a given English parent page ID and target language code, using WPML's translation tables to maintain correct page hierarchy in translations
function pwe_get_translated_parent_id(int $en_parent_id, string $lang_code): ?int
{
    $en_parent_id = (int) $en_parent_id;

    if ($en_parent_id <= 0) {
        return 0;
    }

    $translated_parent_id = apply_filters('wpml_object_id', $en_parent_id, 'page', false, $lang_code);

    return $translated_parent_id ? (int) $translated_parent_id : null;
}

// Copies basic metadata from the source page to the target page, including the page template and featured image, to maintain visual consistency across translations
function pwe_copy_basic_page_meta(int $source_id, int $target_id): void
{
    $excluded_meta_keys = [
        '_edit_lock',
        '_edit_last',
        '_wp_old_slug',
        '_icl_lang_duplicate_of',
        '_wpml_media_featured',
        '_wpml_media_duplicate',
        '_pwe_json_page_key',
        '_pwe_json_language',
        '_pwe_json_url',
        '_pwe_created_as_home_translation',
    ];

    $source_meta = get_post_meta($source_id);

    foreach ($source_meta as $meta_key => $meta_values) {
        if (in_array($meta_key, $excluded_meta_keys, true)) {
            continue;
        }

        delete_post_meta($target_id, $meta_key);

        foreach ($meta_values as $meta_value) {
            add_post_meta(
                $target_id,
                $meta_key,
                maybe_unserialize($meta_value)
            );
        }
    }

    $thumbnail_id = get_post_thumbnail_id($source_id);

    if ($thumbnail_id) {
        set_post_thumbnail($target_id, $thumbnail_id);
    }
}
