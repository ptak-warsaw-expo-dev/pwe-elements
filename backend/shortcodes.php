<?php

function get_fair_data($specific_domain = null) {
    // Stores data so it doesn't need to be fetched every time
    static $cached_data = null;

    if ($cached_data === null) {
        // Get data from pwefunctions.php
        $pwe_fairs = PWECommonFunctions::get_database_fairs_data();
        $pwe_fairs_desc_translations = PWECommonFunctions::get_database_translations_data();

        static $console_logged = false;

        // Check if data is already in global variable
        if (!empty($pwe_fairs) && is_array($pwe_fairs)) {
            global $fairs_data;
            $fairs_data = ["fairs" => []];

            // Add data about the fair
            foreach ($pwe_fairs as $fair) {
                if (!isset($fair->fair_domain) || empty($fair->fair_domain)) {
                    continue;
                }

                $domain = $fair->fair_domain;

                // Save data about the fair in the table
                $fairs_data["fairs"][$domain] = PWECommonFunctions::generate_fair_data($fair);
            }

            // Add translations to the fair data
            foreach ($pwe_fairs_desc_translations as $translation) {
                if (!isset($translation['fair_domain']) || empty($translation['fair_domain'])) {
                    continue;
                }

                $domain = $translation['fair_domain'];

                $translation_data = PWECommonFunctions::generate_fair_translation_data($translation);

                // Merge data
                if (isset($fairs_data["fairs"][$domain])) {
                    $fairs_data["fairs"][$domain] = array_merge(
                        $fairs_data["fairs"][$domain],
                        $translation_data
                    );
                }
            }

            // $current_user = wp_get_current_user();
            // if ($current_user && $current_user->user_login === 'Anton') {
            //     var_dump($fairs_data);
            // }
        } else {
            // URL to JSON file
            $json_file = 'https://mr.glasstec.pl/doc/pwe-data.json';

            // Getting data from JSON file
            $json_data = @file_get_contents($json_file); // Use @ to ignore PHP warnings on failure

            // Checking if data has been downloaded
            if ($json_data === false) {
                if (current_user_can("administrator") && !is_admin()) {
                    echo '<script>console.error("Failed to fetch data from JSON file: ' . $json_file . '")</script>';
                }
                return null;
            }

            global $fairs_data;
            // Decoding JSON data
            $fairs_data = json_decode($json_data, true);

            // Checking JSON decoding correctness
            if (json_last_error() !== JSON_ERROR_NONE) {
                if (current_user_can("administrator") && !is_admin()) {
                    echo '<script>console.error("Error decoding JSON: ' . json_last_error_msg() . '")</script>';
                }
                return null;
            }

            // Checking if the data has the correct structure
            if (!isset($fairs_data['fairs']) || !is_array($fairs_data['fairs'])) {
                if (current_user_can("administrator") && !is_admin()) {
                    echo '<script>console.error("Invalid fairs data format in JSON file.")</script>';
                }
                return null;
            }

            if (!$console_logged) {
                if (current_user_can("administrator") && !is_admin()) {
                    echo '<script>console.error("Brak danych o targach w globalnej zmiennej (dane CAP DB), dane są pobrane z pwe-data.json")</script>';
                }
                $console_logged = true;
            }
        }

        $cached_data = [];

        // Transform the data into an associative array for faster access
        foreach ($fairs_data['fairs'] as $fair) {
            if (!isset($fair['domain']) || empty($fair['domain'])) {
                continue;
            }
            $cached_data[$fair['domain']] = $fair;
        }
    }

    // Domain definition
    if ($specific_domain) {
        $current_domain = $specific_domain;
    } else {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (empty($host)) {
            // CRON/CLI – weź hosta z ustawień WP
            $host = parse_url(home_url(), PHP_URL_HOST) ?: '';
        }
        $current_domain = $host;
    }

    // Return data or null if domain does not exist in data
    return $current_domain && isset($cached_data[$current_domain]) ? $cached_data[$current_domain] : null;
}

function register_dynamic_shortcodes() {
    // List of shortcodes and their corresponding fields
    $shortcodes = [
        'pwe_name_pl'               => 'name_pl', // [pwe_name_pl] || [pwe_name_pl domain="domain.com"]
        'pwe_name_en'               => 'name_en',
        'pwe_desc_pl'               => 'desc_pl',
        'pwe_desc_en'               => 'desc_en',
        'pwe_short_desc_pl'         => 'short_desc_pl',
        'pwe_short_desc_en'         => 'short_desc_en',
        'pwe_full_desc_pl'          => 'full_desc_pl',
        'pwe_full_desc_en'          => 'full_desc_en',
        'pwe_date_start'            => 'date_start',
        'pwe_date_start_hour'       => 'date_start_hour',
        'pwe_date_end'              => 'date_end',
        'pwe_date_end_hour'         => 'date_end_hour',
        'pwe_edition'               => 'edition',
        'pwe_visitors'              => 'fair_visitors_current',
        'pwe_visitors_foreign'      => 'fair_foreign_current',
        'pwe_exhibitors'            => 'fair_exhibitors_current',
        'pwe_countries'             => 'fair_countries_current',
        'pwe_area'                  => 'fair_area_current',
        'pwe_statistics_year_curr'  => 'fair_year_current',
        'pwe_visitors_prev'         => 'fair_visitors_previous',
        'pwe_visitors_foreign_prev' => 'fair_foreign_previous',
        'pwe_exhibitors_prev'       => 'fair_exhibitors_previous',
        'pwe_countries_prev'        => 'fair_countries_previous',
        'pwe_area_prev'             => 'fair_area_previous',
        'pwe_statistics_year_prev'  => 'fair_year_previous',
        'pwe_hall'                  => 'hall',
        'pwe_color_accent'          => 'color_accent',
        'pwe_color_main2'           => 'color_main2',
        'pwe_badge'                 => 'badge',
        'pwe_facebook'              => 'facebook',
        'pwe_instagram'             => 'instagram', // [pwe_instagram domain="domena"]
        'pwe_linkedin'              => 'linkedin',
        'pwe_youtube'               => 'youtube',
        'pwe_catalog'               => 'catalog',
        'pwe_catalog_id'            => 'catalog_id',
        'pwe_category_pl'           => 'category_pl',
        'pwe_category_en'           => 'category_en',
        'pwe_conference_name'       => 'conference_name',
        'pwe_name_cs'               => 'name_cs',
        'pwe_desc_cs'               => 'desc_cs',
        'pwe_short_desc_cs'         => 'short_desc_cs',
        'pwe_full_desc_cs'          => 'full_desc_cs',
        'pwe_name_de'               => 'name_de',
        'pwe_desc_de'               => 'desc_de',
        'pwe_short_desc_de'         => 'short_desc_de',
        'pwe_full_desc_de'          => 'full_desc_de',
        'pwe_name_lt'               => 'name_lt',
        'pwe_desc_lt'               => 'desc_lt',
        'pwe_short_desc_lt'         => 'short_desc_lt',
        'pwe_full_desc_lt'          => 'full_desc_lt',
        'pwe_name_lv'               => 'name_lv',
        'pwe_desc_lv'               => 'desc_lv',
        'pwe_short_desc_lv'         => 'short_desc_lv',
        'pwe_full_desc_lv'          => 'full_desc_lv',
        'pwe_name_ru'               => 'name_ru',
        'pwe_desc_ru'               => 'desc_ru',
        'pwe_short_desc_ru'         => 'short_desc_ru',
        'pwe_full_desc_ru'          => 'full_desc_ru',
        'pwe_name_sk'               => 'name_sk',
        'pwe_desc_sk'               => 'desc_sk',
        'pwe_short_desc_sk'         => 'short_desc_sk',
        'pwe_full_desc_sk'          => 'full_desc_sk',
        'pwe_name_uk'               => 'name_uk',
        'pwe_desc_uk'               => 'desc_uk',
        'pwe_short_desc_uk'         => 'short_desc_uk',
        'pwe_full_desc_uk'          => 'full_desc_uk'
    ];

    // Shortcode handling function
    function handle_fair_shortcode($atts, $field) {
        $atts = shortcode_atts(['domain' => null], $atts);
        $fair_data = get_fair_data($atts['domain']);
        return esc_html($fair_data[$field] ?? '');
    }

    // Registering shortcodes in the loop
    foreach ($shortcodes as $shortcode => $field) {
        add_shortcode($shortcode, function($atts) use ($field) {
            return handle_fair_shortcode($atts, $field);
        });
    }
}

add_action('init', 'register_dynamic_shortcodes');

add_filter('gform_replace_merge_tags', 'PWE_GF_shortcodes', 10, 7);

function PWE_GF_shortcodes($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {

    // Shortcode list => field in fair data
    $shortcode_map = [
        'pwe_name_pl'               => 'name_pl',
        'pwe_name_en'               => 'name_en',
        'pwe_desc_pl'               => 'desc_pl',
        'pwe_desc_en'               => 'desc_en',
        'pwe_short_desc_pl'         => 'short_desc_pl',
        'pwe_short_desc_en'         => 'short_desc_en',
        'pwe_full_desc_pl'          => 'full_desc_pl',
        'pwe_full_desc_en'          => 'full_desc_en',
        'pwe_date_start'            => 'date_start',
        'pwe_date_start_hour'       => 'date_start_hour',
        'pwe_date_end'              => 'date_end',
        'pwe_date_end_hour'         => 'date_end_hour',
        'pwe_edition'               => 'edition',
        'pwe_visitors'              => 'fair_visitors_current',
        'pwe_visitors_foreign'      => 'fair_foreign_current',
        'pwe_exhibitors'            => 'fair_exhibitors_current',
        'pwe_countries'             => 'fair_countries_current',
        'pwe_area'                  => 'fair_area_current',
        'pwe_statistics_year_curr'  => 'fair_year_current',
        'pwe_visitors_prev'         => 'fair_visitors_previous',
        'pwe_visitors_foreign_prev' => 'fair_foreign_previous',
        'pwe_exhibitors_prev'       => 'fair_exhibitors_previous',
        'pwe_countries_prev'        => 'fair_countries_previous',
        'pwe_area_prev'             => 'fair_area_previous',
        'pwe_statistics_year_prev'  => 'fair_year_previous',
        'pwe_hall'                  => 'hall',
        'pwe_color_accent'          => 'color_accent',
        'pwe_color_main2'           => 'color_main2',
        'pwe_badge'                 => 'badge',
        'pwe_facebook'              => 'facebook',
        'pwe_instagram'             => 'instagram',
        'pwe_linkedin'              => 'linkedin',
        'pwe_youtube'               => 'youtube',
        'pwe_catalog'               => 'catalog',
        'pwe_catalog_id'            => 'catalog_id',
        'pwe_category_pl'           => 'category_pl',
        'pwe_category_en'           => 'category_en',
        'pwe_conference_name'       => 'conference_name'
    ];

    // Searching for tags {pwe_xxx} and {pwe_xxx:domain=yyy}
    preg_match_all('/\{(pwe_[a-z0-9_]+)(:domain=([^}]+))?\}/i', $text, $matches, PREG_SET_ORDER);

    if (!$matches) {
        return $text;
    }

    foreach ($matches as $match) {

        $full_tag = $match[0];     // {pwe_instagram:domain=domain.pl}
        $tag_name = $match[1];     // pwe_instagram
        $domain   = $match[3] ?? null;

        if (!isset($shortcode_map[$tag_name])) {
            continue;
        }

        // Downloading trade fair data
        $fair_data = get_fair_data($domain);

        if (!$fair_data || !is_array($fair_data)) {
            $text = str_replace($full_tag, '', $text);
            continue;
        }

        $field = $shortcode_map[$tag_name];
        $value = $fair_data[$field] ?? '';

        $text = str_replace($full_tag, esc_html($value), $text);
    }

    return $text;
}