<?php
get_header();
add_filter('the_content', 'wpautop');

$months_en = array(
    'stycznia' => 'january',
    'lutego' => 'february',
    'marca' => 'march',
    'kwietnia' => 'april',
    'maja' => 'may',
    'czerwca' => 'june',
    'lipca' => 'july',
    'sierpnia' => 'august',
    'września' => 'september',
    'października' => 'october',
    'listopada' => 'november',
    'grudnia' => 'december',
);

function adjustBrightness($hex, $steps) {
    // Convert hex to RGB
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Shift RGB values
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    // Convert RGB back to hex
    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
            . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
            . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}

$post_id = get_the_ID();
$site_url = get_post_meta($post_id, 'web_page_link', true);

$host = parse_url($site_url, PHP_URL_HOST);
$domain = preg_replace('/^www\./', '', $host);

$event_type = get_post_meta($post_id, 'pwe_event_type', true);

$lang_pl = get_locale() == "pl_PL";

$api_url = 'https://'. $domain .'/wp-content/plugins/custom-element/other/pwe_api.php';
$secretKey = defined('PWE_API_KEY_1') ? PWE_API_KEY_1 : '';
$token = hash_hmac('sha256', parse_url($api_url, PHP_URL_HOST), $secretKey);

$api_options = [
    'http' => [
        'header' => "Authorization: $token\r\n",
        'method' => 'GET',
    ]
];

$api_context = stream_context_create($api_options);
$api_response = file_get_contents($api_url, false, $api_context);
$api_media = json_decode($api_response, true);

$output = '';

$main_logo = 'https://'. $domain .'/doc/logo.webp';
$header_bg = 'https://'. $domain .'/doc/background.webp';
if (!empty($api_media["doc"])) {
    // Logo search
    foreach ($api_media["doc"] as $file) {
        if (!$lang_pl && strpos($file['path'], 'logo-calendar-pwe-en.webp') !== false) {
            $main_logo = $file['path'];
            break;
        } elseif (strpos($file['path'], 'logo-calendar-pwe.webp') !== false) {
            $main_logo = $file['path'];
        }
    }

    // Background search
    foreach ($api_media["doc"] as $file) {
        if (strpos($file['path'], 'doc/background.webp') !== false) {
            $header_bg = $file['path'];
        }
    }
}
$main_logo = !empty(get_post_meta($post_id, '_logo_image', true)) ? get_post_meta($post_id, '_logo_image', true) : $main_logo;
$header_bg = !empty(get_post_meta($post_id, '_header_image', true)) ? get_post_meta($post_id, '_header_image', true) : $header_bg;

function multi_translation($key) {
    $locale = get_locale();
    $translations_file = __DIR__ . '/../assets/translations.json';

    // JSON file with translation
    $translations_data = json_decode(file_get_contents($translations_file), true);

    // Is the language in translations
    if (isset($translations_data[$locale])) {
        $translations_map = $translations_data[$locale];
    } else {
        // By default use English translation if no translation for current language
        $translations_map = $translations_data['en_US'];
    }

    // Return translation based on key
    return isset($translations_map[$key]) ? $translations_map[$key] : $key;
}

function get_translated_field($fair, $field_base_name) {
    // Get the language in the format e.g. "de", "pl"
    $locale = get_locale(); // ex. "de_DE"
    $lang = strtolower(substr($locale, 0, 2)); // "de"

    // Check if a specific translation exists (e.g. fair_name_{lang})
    $field_with_lang = "{$field_base_name}_{$lang}";

    if (!empty($fair[$field_with_lang])) {
        return $fair[$field_with_lang];
    }

    // Fallback to English
    $fallback = "{$field_base_name}_en";
    return $fair[$fallback] ?? '';
}

// Get localize
$locale = get_locale();

$shortcodes_active = empty(get_option('pwe_general_options', [])['pwe_dp_shortcodes_unactive']);

if (!function_exists('get_pwe_shortcode')) {
    function get_pwe_shortcode($shortcode, $domain) {
        return shortcode_exists($shortcode) ? do_shortcode('[' . $shortcode . ' domain="' . $domain . '"]') : "";
    }
}

if (!function_exists('check_available_pwe_shortcode')) {
    function check_available_pwe_shortcode($shortcodes_active, $shortcode) {
        return $shortcodes_active && !empty($shortcode) && $shortcode !== "Brak danych";
    }
}

$pwe_db_date_start = do_shortcode('[pwe_date_start domain="' . $domain . '"]');
$pwe_db_date_end = do_shortcode('[pwe_date_end domain="' . $domain . '"]');
$pwe_db_date_start_available = $shortcodes_active && !empty($pwe_db_date_start) && $pwe_db_date_start !== "";
$pwe_db_date_end_available = $shortcodes_active && !empty($pwe_db_date_end) && $pwe_db_date_end !== "";

$start_date = $pwe_db_date_start_available ? date("d-m-Y", strtotime(str_replace("/", "-", $pwe_db_date_start))) : get_post_meta($post_id, 'fair_date_start', true);
$start_date = empty($start_date) ? "28-01-2050" : $start_date;
$end_date = $pwe_db_date_end_available ? date("d-m-Y", strtotime(str_replace("/", "-", $pwe_db_date_end))) : get_post_meta($post_id, 'fair_date_end', true);
$end_date = empty($end_date) ? "30-01-2050" : $end_date;

$custom_format_start_date = DateTime::createFromFormat('d-m-Y', $start_date);
$custom_format_end_date = DateTime::createFromFormat('d-m-Y', $end_date);
$custom_format_date = PWECommonFunctions::transform_dates($custom_format_start_date->format('Y/m/d'), $custom_format_end_date->format('Y/m/d'), false);

$interval = $custom_format_start_date->diff($custom_format_end_date);
$days = $interval->days + 1;

$months = json_decode(file_get_contents(__DIR__ . '/../assets/months.json'), true);


// Date formatting function
if (!function_exists('format_date_range')) {
    function format_date_range($start_date, $end_date, $months, $locale) {
        $start_parts = explode("-", $start_date);
        $end_parts = explode("-", $end_date);

        $start_day = intval($start_parts[0]);
        $end_day = intval($end_parts[0]);
        $start_month = $start_parts[1];
        $end_month = $end_parts[1];
        $year = $start_parts[2];

        // Select month name depending on language
        $lang_key = strtoupper(substr($locale, 0, 2));

        $start_month_name = isset($months[$start_month][$lang_key]) ? $months[$start_month][$lang_key] : "";
        $end_month_name = isset($months[$end_month][$lang_key]) ? $months[$end_month][$lang_key] : "";

        // Check if months are different
        switch ($lang_key) {
            case "PL":
                if ($start_month === $end_month) {
                    return "$start_day - $end_day $start_month_name $year";
                } else {
                    return "$start_day $start_month_name - $end_day $end_month_name $year";
                }
            case "EN":
                if ($start_month === $end_month) {
                    return "$start_month_name $start_day-$end_day, $year";
                } else {
                    return "$start_month_name $start_day - $end_month_name $end_day, $year";
                }
            case "DE":
                if ($start_month === $end_month) {
                    return "$start_day.-$end_day. $start_month_name $year";
                } else {
                    return "$start_day. $start_month_name - $end_day. $end_month_name $year";
                }
            case "LT":
                if ($start_month === $end_month) {
                    return "$year m. $start_month_name $start_day-$end_day d.";
                } else {
                    return "$year m. $start_month_name $start_day d. - $end_month_name $end_day d.";
                }
            case "LV":
                if ($start_month === $end_month) {
                    return "$start_day. - $end_day. $start_month_name $year";
                } else {
                    return "$start_day. $start_month_name - $end_day. $end_month_name $year";
                }
            case "UK":
                if ($start_month === $end_month) {
                    return "$start_day - $end_day $start_month_name $year";
                } else {
                    return "$start_day $start_month_name - $end_day $end_month_name $year";
                }
            case "CS":
                if ($start_month === $end_month) {
                    return "$start_day.-$end_day. $start_month_name $year";
                } else {
                    return "$start_day. $start_month_name - $end_day. $end_month_name $year";
                }
            case "SK":
                if ($start_month === $end_month) {
                    return "$start_day. - $end_day. $start_month_name $year";
                } else {
                    return "$start_day. $start_month_name - $end_day. $end_month_name $year";
                }
            case "RU":
                if ($start_month === $end_month) {
                    return "$start_day - $end_day $start_month_name $year";
                } else {
                    return "$start_day $start_month_name - $end_day $end_month_name $year";
                }
            default:
                if ($start_month === $end_month) {
                    return "$start_month_name $start_day-$end_day, $year";
                } else {
                    return "$start_month_name $start_day - $end_month_name $end_day, $year";
                }
        }
    }
}

// Date formatting
$formatted_date = format_date_range($start_date, $end_date, $months, $locale);

$date_object = DateTime::createFromFormat('d-m-Y', $start_date);

$quarterly_date = !empty(get_post_meta($post_id, 'quarterly_date', true)) ? get_post_meta($post_id, 'quarterly_date', true) : multi_translation("new_date_coming_soon");

if (($date_object && $date_object->format('Y') == 2050) || (strtotime($end_date . " +20 hours") < time())) {
    $fair_date =  $quarterly_date;
} else {
    $fair_date = $formatted_date;
}

if ($event_type === "week") {

    $lang = ICL_LANGUAGE_CODE;

    // Converting dates to timestamps
    $trade_fair_start_timestamp = strtotime($start_date);
    $trade_fair_end_timestamp = strtotime($end_date);

    // Pobierz zapisane wykluczone targi (domeny)
    $excluded_events = get_post_meta($post_id, 'events_week_fairs_excluded', true);
    $excluded_events_array = !empty($excluded_events) ? array_map('trim', explode(', ', $excluded_events)) : [];

    // Get JSON
    $fairs_json = PWECommonFunctions::json_fairs();

    $events_map = [];

    foreach ($fairs_json as $fair) {
        // Getting start and end dates
        $event_date_start = isset($fair['date_start']) ? strtotime($fair['date_start']) : null;
        $event_date_end   = isset($fair['date_end']) ? strtotime($fair['date_end']) : null;
        $category_pl      = isset($fair['category_pl']) ? $fair['category_pl'] : null;
        $category_en      = isset($fair['category_en']) ? $fair['category_en'] : null;
        $event_desc       = $lang_pl ? (isset($fair["desc_pl"]) ? $fair["desc_pl"] : '') : (isset($fair["desc_en"]) ? $fair["desc_en"] : '');
        $event_name       = $lang_pl ? $fair["name_pl"] : (isset($fair["name_en"]) ? $fair["name_en"] : $fair["name_pl"]);
        $event_domain     = isset($fair['domain']) ? $fair['domain'] : '';

        // Checking if the date is in the range
        if ($event_date_start && $event_date_end) {
            $isStartInside     = $event_date_start >= $trade_fair_start_timestamp && $event_date_start <= $trade_fair_end_timestamp;
            $isEndInside       = $event_date_end >= $trade_fair_start_timestamp && $event_date_end <= $trade_fair_end_timestamp;
            $isOtherDomain     = strpos($event_domain, $current_domain) === false;
            $isNotFastTextile  = strpos($event_domain, "fasttextile.com") === false;
            $isNotExpoTrends   = strpos($event_domain, "expotrends.eu") === false;
            $isNotFabricsExpo  = strpos($event_domain, "fabrics-expo.eu") === false;
            $isNotTest         = strpos($event_domain, "mr.glasstec.pl") === false;

            if (
                ($isStartInside || $isEndInside) &&
                $isOtherDomain &&
                $isNotFastTextile &&
                $isNotExpoTrends &&
                $isNotFabricsExpo &&
                $isNotTest
            ) {
                // Skip events whose domain is on the exclusion list
                if (!in_array(trim($event_domain), $excluded_events_array, true)) {
                    // Key by event name
                    $events_map[$event_name] = [
                        "domain"     => $event_domain,
                        "name"       => $event_name,
                        "date_start"    => isset($fair['date_start']) ? $fair['date_start'] : '',
                        "date_end"    => isset($fair['date_end']) ? $fair['date_end'] : '',
                        "desc"       => $event_desc,
                        "category"   => $lang_pl ? $fair['category_pl'] : $fair['category_en'],
                        "visitors"   => isset($fair['fair_visitors_current']) ? $fair['fair_visitors_current'] : null,
                        "exhibitors" => isset($fair['fair_exhibitors_current']) ? $fair['fair_exhibitors_current'] : null,
                        "area"       => isset($fair['fair_area_current']) ? $fair['fair_area_current'] : null,
                        "catalog"    => isset($fair['catalog']) ? $fair['catalog'] : '',
                        "edition"    => isset($fair['edition']) ? $fair['edition'] : '',
                        "hall"    => isset($fair['hall']) ? $fair['hall'] : '',
                        "color"    => isset($fair['color_accent']) ? $fair['color_accent'] : ''
                    ];
                }
            }
        }
    }

    // Now $fairs_week should contain the list of fairs after exclusions
    $fairs_week = array_keys($events_map);

    // The final array with event data
    $all_events_json = array_values($events_map); // wartości (bez kluczy) — już po wykluczeniach

    $merge_exhibitors = [];
    $conferences = [];

    foreach ($all_events_json as $event) {
        $exhibitors = CatalogFunctions::logosChecker($event['catalog'], 'PWECatalogCombined', false, null, false);
        $conferences[] = PWECommonFunctions::get_database_fairs_data_adds($event['domain']);

        if (is_array($exhibitors)) {
            foreach ($exhibitors as $exhibitor) {
                $exhibitor['id_katalogu'] = $event['catalog'];
                $merge_exhibitors[] = [
                    'domain' => $event['domain'],
                    'exhibitor' => $exhibitor
                ];
            }
        }
    }

    shuffle($merge_exhibitors);

    $exhibitors_logotypes = array_column($merge_exhibitors, 'exhibitor');
    $front_logos = array_slice($exhibitors_logotypes, 0, 19);
    $remaining_logos = array_slice($exhibitors_logotypes, 19);

    $duplicates = [];
    $unique_logotypes = [];

    foreach ($merge_exhibitors as $item) {
        $logo = $item['exhibitor']['Numer_stoiska'];
        if (!isset($duplicates[$logo])) {
            $duplicates[$logo] = true;
            $unique_logotypes[] = $item;
        }
    }

    $visitors_num = 0;
    $exhibitors_num = 0;
    $area_num = 0;

    foreach ($all_events_json as $event) {
        $visitors_num += (int)$event['visitors'];
        $exhibitors_num += (int)$event['exhibitors'];
        $area_num += (int)$event['area'];
    }

    $visitors_num = !empty(get_post_meta($post_id, 'events_week_visitors', true)) ? get_post_meta($post_id, 'events_week_visitors', true) : ceil($visitors_num / 1000) * 1000;
    $visitors_foreign_num = !empty(get_post_meta($post_id, 'events_week_visitors_foreign', true)) ? get_post_meta($post_id, 'events_week_visitors_foreign', true) : ceil(($visitors_num * 0.09) / 10) * 10;
    $exhibitors_num = !empty(get_post_meta($post_id, 'events_week_exhibitors', true)) ? get_post_meta($post_id, 'events_week_exhibitors', true) : ceil($exhibitors_num / 10) * 10;
    $area_num = !empty(get_post_meta($post_id, 'events_week_area', true)) ? get_post_meta($post_id, 'events_week_area', true) : ceil($area_num / 10) * 10;

    $increase_percent = !empty(get_post_meta($post_id, 'events_week_percent', true)) ? get_post_meta($post_id, 'events_week_percent', true) : 30;

    $color_1 = !empty(get_post_meta($post_id, 'events_week_color_1', true)) ? get_post_meta($post_id, 'events_week_color_1', true) : 'black';
    $color_2 = !empty(get_post_meta($post_id, 'events_week_color_2', true)) ? get_post_meta($post_id, 'events_week_color_2', true) : 'black';

    $events_week_halls_image_url = !empty(get_post_meta($post_id, 'events_week_halls_image_url', true)) ? get_post_meta($post_id, 'events_week_halls_image_url', true) : '';

    $header_image_url = !empty(get_post_meta($post_id, '_header_image', true)) ? get_post_meta($post_id, '_header_image', true) : '';
    $header_bg = !empty($header_image_url) ? 'url('. $header_image_url .')' : '#464646';

    $rotate_logo = (count($all_events_json) < 4) ? 'inherit' : 'rotate(-90deg)';
    $width_logo = (count($all_events_json) < 4) ? '300px' : '200px';

    $output .= '
    <style>
        .dont-show {
            display: none !important;
        }

        .single-event__header {
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 600px;
            background: '. $header_bg .';
        }

        .single-event__header .single-event__header-stripes {
            display: flex;
            height: 100%;
            width: 100%;
        }

        .single-event__header .single-event__header-stripe {
            flex: 1;
            background-size: cover;
            background-position: center;
            position: relative;
            transform: skew(-15deg);
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: start;
            padding-top: 100px;
            margin: 0;
            border-left: 2px solid #fb5607;
            box-shadow:
                inset 20px 0 40px rgba(0,0,0,0.6),
                20px 0 40px rgba(0,0,0,0.6);
        }
        .single-event__header .single-event__header-stripe:last-child {
            border-right: 2px solid #fb5607;
        }
        .single-event__header-stripe-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: self-start;
        }
        .single-event__header .single-event__header-stripe-logo {
            transform: '. $rotate_logo .';
            max-width: '. $width_logo .';
            aspect-ratio: 3 / 2;
            display: flex;
            transition: .3s ease;
            align-items: center;
        }
        .single-event__header-stripe:hover .single-event__header-stripe-logo {
            transform: '. $rotate_logo .' scale(1.1);
        }
        .single-event__header .single-event__header-stripe img {
            width: 100%;
            aspect-ratio: 3/2;
            object-fit: contain;
        }
        .single-event__header .single-event__header-title {
            background-image:url(https://rfl.warsawexpo.eu/wp-content/uploads/2025/09/bg_stripe_title_events-week.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            text-align: center;
            color: #fff;
            z-index: 5;
            bottom: 10%;
            width: 100%;
            padding: 30px;
            border-top: 2px solid #c8c8c8;
            border-bottom: 2px solid #c8c8c8;
        }
        .single-event__header .single-event__header-title h1 {
            font-size: 32px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 auto;
        }
        .single-event__header .single-event__header-date {
            font-size: 30px;
            margin: 0;
        }
        @media(min-width: 1800px) {
            .single-event__header .single-event__header-stripe-logo {
                max-width: 260px;
            }
        }
        @media(max-width: 960px) {
            .single-event__header {
                height: 400px;
            }
            .single-event__header .single-event__header-stripe {
                transform: inherit !important;
                box-shadow: inherit;
                border: none !important;
                padding-top: 0;
            }
            .single-event__header .single-event__header-stripe-logo {
                transform: inherit !important;
                max-width: 350px;
                padding: 18px;
            }
            .single-event__header .slick-track {
                height: 100%;
            }
            .single-event__header .single-event__header-title h1,
            .single-event__header .single-event__header-date {
                font-size: 18px;
                max-width: 280px;
                margin: 0 auto;
            }
        }
        @media(max-width: 570px) {
            .single-event__header {
                height: 320px;
            }
            .single-event__header .single-event__header-stripe-logo {
                max-width: 300px;
            }
            .single-event__header .single-event__header-title {
                padding: 8px;
            }
        }


        .single-event__main-content {
            display: flex;
            flex-direction: column;
            max-width: 1200px;
            padding: 36px 18px;
            margin: 0 auto;
            gap: 18px;
        }


        .single-event__description-title h3 {
            margin: 0;
            font-weight: bold;
            font-size: 2rem;
            background: linear-gradient(to right, '. $color_1 .', '. $color_2 .');
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .single-event__description-date h4 {
            margin: 18px 0 0;
            font-weight: 700;
        }
        .single-event__description-date span {
            white-space: nowrap;
        }
        .single-event__description-additional {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
        }
        .single-event__description-additional-item {
            display: flex;
            flex-direction: column;
            width: 49%;
            padding-top: 18px;
        }
        .single-event__description-additional-item-icon {
            display: flex;
            gap: 8px;
        }
        .single-event__description-additional-item-icon h5 {
            display: flex;
            align-items: center;
            margin: 0;
        }
        .single-event__description-additional-item-text p {
            margin: 6px 0 0;
        }
        @media (max-width: 768px) {
            .single-event__description-additional {
                flex-direction: column;
            }
            .single-event__description-additional-item {
                width: 100%;
            }
        }


        .single-event__stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-radius: 32px;
            box-shadow: 0 4px 16px #0002;
            padding: 36px;
            gap: 32px;
            margin-top: 18px;
        }
        .single-event__stats-text {
            flex: 1 1 310px;
            min-width: 240px;
        }
        .single-event__stats-text h3 {
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 10px;
        }
        .single-event__stats-text p {
            font-size: 16px;
            color: #202020;
            line-height: 1.32;
            font-weight: 700;
        }
        .single-event__stats-diagram {
            flex: 1 1 340px;
            min-width: 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .single-event__stats-diagram-number {
            text-align: center;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .single-event__stats-diagram-number .single-event__stats-count-up {
            font-size: 80px;
            line-height: 1;
        }
        .single-event__stats-caption {
            font-size: 1.24rem;
            margin-top: -3px;
            color: #222;
            font-weight: 400;
        }
        .single-event__stats-semicircle {
            position: relative;
            margin: 0 auto;
            background: #fff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .single-event__stats-diagram-label {
            position: absolute;
            left: 0; right: 0; bottom: 18px;
            text-align: center;
            width: 100%;
            font-size: 1.13rem;
            z-index: 2;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .single-event__stats-diagram-percent {
            color: #111;
            font-size: 1.31rem;
            font-weight: bold;
        }

        .diagram-arrow {
            font-size: 1.3rem;
            margin-top: -6px;
            margin-bottom: 2px;
            color: #222;
        }
        .single-event__stats-diagram-desc {
            font-size: 14px;
            font-weight: 400;
            color: #242424;
            margin-top: 0;
            background: #fff;
            border-radius: 8px;
            padding: 0 4px;
        }

        .single-event__stats-numbers {
            flex: 1 1 320px;
            min-width: 220px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .single-event__stats-numbers-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .single-event__stats-dot {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffe6d9;
        }
        .single-event__stats-dot img {
            width: 70%;
            object-fit: contain;
        }
        .single-event__stats-count-up {
            font-size: 2.3rem;
            font-weight: 800;
            color: #111;
            min-width: 70px;
            display: inline-block;
            transition: color .3s;
            letter-spacing: -1px;
        }
        .single-event__stats-numbers-row .unit {
            font-size: 1.2rem;
            margin-left: 2px;
            color: #222;
            font-weight: 500;
            font-weight: 800;
        }
        .single-event__stats-numbers-row .single-event__stats-caption {
            font-size: 16px;
            color: #222;
            font-weight: 400;
            margin-top: 0;
            letter-spacing: 0.01em;
        }

        @media (max-width: 960px) {
            .single-event__stats {
                flex-direction: column;
                gap: 18px;
                padding: 18px;
            }
            .single-event__stats-text,
            .single-event__stats-diagram,
            .single-event__stats-numbers {
                flex: auto;
            }
            .single-event__stats-diagram {
                margin: 0 auto;
            }
        }
        .single-event__catalog {
            margin-top: 36px;
        }
        .single-event__catalog-columns {
            display: flex;
            gap: 18px;
            justify-content: center;
            align-items: center;
            max-width: 1200px;
            margin: 32px auto;
        }
        .single-event__catalog-title h3 {
            margin: 0;
            text-transform: uppercase;
            text-align: center;
        }
        .single-event__catalog-logo-column {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .single-event__catalog-columns :is(.col-2, .col-4, .col-6) {
            margin-top: 50px;
        }
        .single-event__catalog-columns :is(.col-3, .col-5) {
            margin-top: -50px;
        }
        .single-event__catalog-columns :is(.col-1, .col-7) {
            margin-top: 50px;
        }
        .single-event__catalog-logo-tile {
            width: 140px;
            height: auto;
            aspect-ratio: 4 / 3;
            perspective: 1000px;
        }
        .single-event__catalog-flip-card {
            width: 100%;
            height: 100%;
        }
        .single-event__catalog-flip-card-inner {
            width: 100%;
            height: 100%;
            transition: transform 0.7s cubic-bezier(.4,2,.3,1);
            transform-style: preserve-3d;
            position: relative;
        }
        .flipped .single-event__catalog-flip-card-inner {
            transform: rotateY(180deg);
        }
        .single-event__catalog-flip-card-front,
        .single-event__catalog-flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 16px #0002;
            display: flex;
            align-items: center;
            justify-content: center;
            backface-visibility: hidden;
        }
        .single-event__catalog-flip-card-front img,
        .single-event__catalog-flip-card-back img {
            max-width: 90%;
            aspect-ratio: 3 / 2;
            object-fit: contain;
        }
        .single-event__catalog-flip-card-back {
            transform: rotateY(180deg);
        }


        .single-event__catalog-button {
            display: flex;
            max-width: 300px;
            margin: 18px auto 0;
            text-align: center;
            background: #fb5607;
            padding: 20px;
            border-radius: 12px;
            transition: .3s ease;
        }
        .single-event__catalog-button:hover {
            background: '. adjustBrightness("#fb5607", -30) .';
        }
        .single-event__catalog-button button {
            margin: 0 auto;
            font-weight: 700;
            color: white;
            background: transparent;
        }
        @media(max-width: 1200px) {
            .single-event__catalog-columns {
                flex-direction: column;
            }
            .single-event__catalog-logo-column {
                flex-direction: row;
            }
            .single-event__catalog-columns :is(.col-2, .col-4, .col-6, .col-3, .col-5, .col-1, .col-7) {
                margin-top: 0;
            }
        }
        @media(max-width: 550px) {
            .single-event__catalog-columns {
                gap: 10px;
            }
            .single-event__catalog-logo-column {
                gap: 10px;
            }
            .single-event__catalog-logo-tile {
                width: 120px;
            }
        }
        @media(max-width: 400px) {
            .single-event__catalog-logo-tile {
                width: 100px;
            }
        }



        .single-event__fairs-title h3 {
            text-transform: uppercase;
        }
        .single-event__fairs-counts {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }
        .single-event__fairs-counts img {
            width: 30px;
        }
        .single-event__fairs-counts span {
            display: flex;
            align-items: center;
            font-size: 20px;
        }
        .single-event__fairs-items {
            display: flex;
            flex-direction: column;
            gap: 48px;
            margin-top: 36px;
        }
        .single-event__fairs-item-wrapper {
            display: flex;
            gap: 36px;
        }
        .single-event__fairs-item-image {
            display: flex;
            flex-direction: column;
            width: 30%;
            gap: 18px;
        }
        .single-event__fairs-item-image .single-event__fairs-item-title {
            display: none;
        }
        .single-event__fairs-item-info .single-event__fairs-item-title {
            display: flex;
            flex-direction: column;
        }
        .single-event__fairs-item-bg {
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 18px;
            aspect-ratio: 16 / 9;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .single-event__fairs-item-bg img {
            width: 70%;
            max-height: 70%;
            object-fit: contain;
        }
        .single-event__fairs-item-info {
            width: 70%;
        }
        .single-event__fairs-item-title h4 {
            margin: 0;
        }
        .single-event__fairs-item-title p {
            margin: 0;
            color: #fb5607;
            font-size: 18px;
            font-weight: 600;
        }
        .single-event__fairs-item-desc p {
            margin: 0;
        }
        .single-event__fairs-item-buttons {
            gap: 18px;
            margin-top: 18px;
        }
        .single-event__fairs-item-info .single-event__fairs-item-buttons {
            display: flex;
        }
        .single-event__fairs-item-title .single-event__fairs-item-buttons {
            display: none;
        }
        .single-event__fairs-item-button {
            display: flex;
            justify-content: center;
            border: 2px solid #fb5607;
            width: 260px;
            border-radius: 8px;
            transition: .3s ease;
        }
        .single-event__fairs-item-button a {
            font-weight: 600;
            width: 100%;
            height: 100%;
            padding: 10px 18px;
            text-align: center;
        }
        .single-event__fairs-item-button.more {
            background: #fb5607;
            margin: 0;
        }
        .single-event__fairs-item-button.more:hover {
            background: '. adjustBrightness("#fb5607", -30) .';
            border: 2px solid '. adjustBrightness("#fb5607", -30) .';
        }
        .single-event__fairs-item-button.more a {
            color: white;
        }
        .single-event__fairs-item-button.register {
            background: white;
        }
        .single-event__fairs-item-button.register:hover {
            background: #fb5607;
        }
        .single-event__fairs-item-button.register a {
            color: #fb5607;
        }
        .single-event__fairs-item-button.register:hover a {
            color: white;
        }
        .single-event__fairs-item-slider {
            margin-top: 18px;
            max-height: 100px;
            overflow: hidden;
        }
        .single-event__fairs-item-slider .slick-track {
            margin-left: 0;
        }
        .single-event__fairs-item-logo {
            margin: 5px;
        }
        @media(max-width: 960px) {
            .single-event__fairs-item-wrapper {
                flex-direction: column;
            }
            .single-event__fairs-item-image,
            .single-event__fairs-item-info {
                width: 100%;
            }
            .single-event__fairs-item-title,
            .single-event__fairs-item-bg {
                width: 50%;
            }
            .single-event__fairs-item-bg {
                aspect-ratio: 21 / 9;
            }
            .single-event__fairs-item-image .single-event__fairs-item-title {
                display: flex;
                flex-direction: column;
            }
            .single-event__fairs-item-info .single-event__fairs-item-title {
                display: none;
            }
            .single-event__fairs-item-info .single-event__fairs-item-buttons {
                display: none;
            }
            .single-event__fairs-item-title .single-event__fairs-item-buttons {
                display: flex;
                flex-wrap: wrap;
            }
            .single-event__fairs-item-button {
                max-width: 260px;
                width: 100%;
                font-size: 14px;
            }
            .single-event__fairs-item-image {
                flex-direction: row-reverse;
            }

        }
        @media(max-width: 450px) {
            .single-event__fairs-items {
                gap: 24px;
            }
            .single-event__fairs-item-wrapper {
                gap: 18px;
            }
            .single-event__fairs-item-buttons {
                flex-direction: column;
                margin-top: 10px;
                gap: 10px;
            }
            .single-event__fairs-item-button {
                margin: 0 auto;
            }
            .single-event__fairs-item-title h4 {
                font-size: 16px;
            }
            .single-event__fairs-item-desc p {
                line-height: 1.4;
                font-size: 16px;
            }
        }



        .single-event__exhibitors {
            display: none;
            flex-direction: column;
        }
        .single-event__exhibitors-fairs {
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            padding: 18px;
        }
        .single-event__exhibitors-fairs-button-container {
            margin: 8px 0 0;
        }
        .single-event__exhibitors-fairs-button {
            background-position: center;
            background-size: cover;
        }
        .single-event__exhibitors-fairs-button,
        .single-event__exhibitors-fairs-button a {
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            aspect-ratio: 16/9;
            cursor: pointer;
            transition: .3s ease;
        }
        .single-event__exhibitors-fairs-button:hover,
        .single-event__exhibitors-fairs-button.active {
            transform: scale(1.05);
        }
        .single-event__exhibitors-fairs-button:not(.active) {
            filter: opacity(0.5);
        }
        .single-event__exhibitors-fairs-button-container p {
            font-weight: 600;
            line-height: 1.3;
            text-align: center;
            margin: 10px 0 0;
        }
        .single-event__exhibitors-fairs-button img {
            width: 80%;
            max-height: 80%;
            object-fit: contain;
        }
        .single-event__exhibitors-fairs-button.single-event__exhibitors-showall {
            flex-wrap: wrap;
            justify-content: space-evenly;
            gap: 4px;
            padding: 8px;
        }
        .single-event__exhibitors-fairs-button.single-event__exhibitors-showall img {
            width: 30%;
        }
        .single-event__exhibitors-catalog {
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px;
            padding: 18px;
        }
        .single-event__exhibitors-card {
            flex-direction: column;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 16px #0002;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .single-event__exhibitors-card img {
            aspect-ratio: 4 / 3;
            width: 100%;
            object-fit: contain;
            padding: 10px 10px 0;
        }
        .single-event__exhibitors-card p {
            margin: 0;
            text-align: center;
            font-weight: 600;
        }
        .single-event__exhibitors-showall,
        .single-event__exhibitors-hideall {
            width: 100%;
            justify-content: center;
        }
        .single-event__exhibitors-hideall {
            display: flex;
            margin-top: 18px;
        }
        .single-event__exhibitors-showall button,
        .single-event__exhibitors-hideall button {
            background: #fb5607;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
            text-transform: uppercase;
        }
        .single-event__exhibitors-showall button:hover,
        .single-event__exhibitors-hideall button:hover {
            background: '. adjustBrightness("#fb5607", -30) .';
        }


        @media(max-width:960px) {
            .single-event__exhibitors-fairs {
                grid-template-columns: repeat(4, 1fr);
            }
            .single-event__exhibitors-catalog {
                grid-template-columns: repeat(5, 1fr);
                gap: 10px;
            }
        }
        @media(max-width:600px) {
            .single-event__exhibitors-fairs {
                grid-template-columns: repeat(3, 1fr);
            }
            .single-event__exhibitors-fairs-button-container p {
                font-size: 14px;
            }
            .single-event__exhibitors-catalog {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        @media(max-width:500px) {
            .single-event__exhibitors-fairs {
                grid-template-columns: repeat(2, 1fr);
            }
            .single-event__exhibitors-catalog {
                grid-template-columns: repeat(3, 1fr);
            }
        }




        .single-event__exhibitor-modal {
            display: none;
            justify-content: center;
            align-items: center;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .single-event__exhibitor-modal-content {
            position: relative;
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 100%;
            height: auto;
            max-width: 500px;
            text-align: center;
            border-radius: 18px;
        }
        .single-event__exhibitor-modal-logo-container {
            text-align: center;
        }
        .single-event__exhibitor-modal-logo {
            max-width: 260px;
            aspect-ratio: 3 / 2;
            object-fit: contain;
        }
        .single-event__exhibitor-modal-name {
            margin: 18px 0 0;
        }
        .single-event__exhibitor-modal-name,
        .single-event__exhibitor-modal-stand,
        .single-event__exhibitor-modal-website {
            text-align: center;
        }
        .single-event__exhibitor-modal-close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 25px;
            cursor: pointer;
        }

        .single-event__exhibitor-modal-close-btn:hover,
        .single-event__exhibitor-modal-close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }



        .single-event__catalog-header {
            background-image: url('. $header_image_url .');
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 50vh;
            padding: 18px;
            overflow: hidden;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .single-event__catalog-header :is(h3, h4) {
            margin: 0;
            text-align: center;
            text-transform: uppercase;
            color: white;
        }
        .single-event__catalog-header h3 {
            font-size: 46px;
        }
        .single-event__catalog-header input {
            max-width: 300px;
            margin: 0 auto;
            color: black;
        }
        .single-event__catalog-header-wrapper {
            max-width: 600px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            background: rgb(22 22 22 / 40%);
            box-shadow: 0 8px 36px 0 rgba(0,0,0,0.18);
            color: #fff;
            padding: 36px;
            backdrop-filter: blur(3px);
            border-radius: 20px;
        }

        footer {
            display: inline !important;
        }
    </style>';

    // TEMPORARY <--------------------------------------------------------------------------------------<
    if ($_SERVER['REQUEST_URI'] === '/kalendarz-targowy/warsaw-defence-security-expo/' || $_SERVER['REQUEST_URI'] === '/en/fair-calendar/warsaw-defence-security-expo/') {
        $output .= '
        <style>
            .single-event__stats-numbers-row.visitors,
            .single-event__stats-numbers-row.visitors-foreign {
                display: none;
            }        
        </style>';

    }
    // TEMPORARY <--------------------------------------------------------------------------------------<
    
    while (have_posts()):
        the_post();

        $output .= '
        <div data-parent="true" class="vc_row row-container boomapps_vcrow '. $title .'" data-section="21" itemscope itemtype="http://schema.org/Event">
            <div class="single-event" data-imgready="true">
                <div class="single-event__wrapper">';

                    $output .= '
                    <div id="singleEventHeader" class="single-event__header">
                        <div class="single-event__header-stripes">';

                        $is_first_edition = false;

                        foreach ($all_events_json as $event) {
                            $domain   = $event['domain'];
                            $name     = $event['name'];
                            $edition  = $event['edition'];

                            if ($event['edition'] === "1") {
                                $is_first_edition = true;
                                break;
                            }

                            $output .= '
                            <div class="single-event__header-stripe" style="background-image:url(https://' . $domain . '/doc/background.webp)">
                                <a class="single-event__header-stripe-container" href="https://' . $domain . '/" target="_blank">
                                    <div class="single-event__header-stripe-logo">
                                        <img src="https://' . $domain . '/doc/logo.webp" alt="' . esc_attr($name) . '">
                                    </div>
                                </a>
                            </div>';
                        }

                        $output .= '
                        </div>
                        <div class="single-event__header-title">
                            <h1>' . get_the_title() . '</h1>
                            <p class="single-event__header-date">'. $custom_format_date .' Poland, Warsaw</p>
                        </div>
                    </div>';

                    $output .= '
                    <div class="single-event__main-content">

                        <div id="singleEventDescription" class="single-event__description">
                            <div class="single-event__description-wrapper">
                                <div class="single-event__description-title">
                                    <h3>'. get_the_title() .'</h3>
                                </div>
                                <div class="single-event__description-date">
                                    <h4>'. $fair_date .' | <span>Ptak Warsaw Expo</span></h4>
                                </div>
                                <div class="single-event__description-text">
                                    <p>'. get_the_content() .'</p>
                                </div>
                            </div>
                        </div>';

                        $output .= '
                        <div id="singleEventStats" class="single-event__stats">
                            <div class="single-event__stats-text">';
                                if (!$is_first_edition) {
                                    $output .= '<h3>'. ($lang_pl ? "Statystyki wydarzeń" : "Event statistics") .'</h3>';
                                } else {
                                    $output .= '<h3>'. ($lang_pl ? "Estymacje wydarzeń" : "Event Estimates") .'</h3>';
                                }
                                $output .= '
                                <p>'. ($lang_pl ? "Łączne przedstawienie kluczowych kategorii, które definiują skalę wydarzenia" : "A combined representation of the key categories that define the scale of the event") .'</p>
                            </div>
                            <div class="single-event__stats-diagram" id="diagram-block">
                                <div class="single-event__stats-diagram-number">
                                <span class="single-event__stats-count-up" data-target="'. $exhibitors_num .'">0</span>
                                <div class="single-event__stats-caption">'. ($lang_pl ? "Wystawców" : "Exhibitors") .'</div>
                                </div>';

                                if (!$is_first_edition) {
                                    $output .= '
                                    <div class="single-event__stats-semicircle">
                                        <svg width="300" height="150" viewBox="9 20 120 40">
                                            <!-- Background (full semicircle) -->
                                            <path
                                                d="M 20 70 A 50 50 0 0 1 120 70"
                                                stroke="#ffe4ce"
                                                stroke-width="14"
                                                fill="none"
                                                stroke-linecap="butt"
                                            />
                                            <!-- Percentage bar ('. $increase_percent .'%) -->
                                            <path
                                                id="arc-animate"
                                                d="M 20 70 A 50 50 0 0 1 120 70"
                                                stroke="#ff8a3b"
                                                stroke-width="18"
                                                fill="none"
                                                stroke-linecap="butt"
                                                style="stroke-dasharray: 0 999; transition: stroke-dasharray 1.2s;"
                                            />
                                        </svg>
                                        <div class="single-event__stats-diagram-label">
                                            <span class="single-event__stats-diagram-percent">
                                                <b>'. $increase_percent .'%</b>
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                                    <polyline points="3 17 9 11 13 15 21 7"/>
                                                    <polyline points="21 15 21 7 13 7"/>
                                                </svg>
                                            </span>
                                            <div class="single-event__stats-diagram-desc">'. ($lang_pl ? "przyrost do ostatniego roku" : "increase over the last year") .'</div>
                                        </div>
                                    </div>';
                                }

                            $output .= '
                            </div>
                            <div class="single-event__stats-numbers">
                                <div class="single-event__stats-numbers-row visitors">
                                    <span class="single-event__stats-dot">
                                        <img src="/wp-content/plugins/PWElements/includes/calendar/assets/view-icon.png">
                                    </span>
                                    <div>
                                        <span class="single-event__stats-count-up" data-target="'. $visitors_num .'">0</span>
                                        <div class="single-event__stats-caption">'. ($lang_pl ? "Odwiedzających" : "Visitors") .'</div>
                                    </div>
                                </div>
                                <div class="single-event__stats-numbers-row visitors-foreign">
                                    <span class="single-event__stats-dot">
                                        <img src="/wp-content/plugins/PWElements/includes/calendar/assets/globus-icon.png">
                                    </span>
                                    <div>
                                        <span class="single-event__stats-count-up" data-target="'. $visitors_foreign_num .'">0</span>
                                        <div class="single-event__stats-caption">'. ($lang_pl ? "W tym z zagranicy" : "Including from abroad") .'</div>
                                    </div>
                                </div>
                                <div class="single-event__stats-numbers-row area">
                                    <span class="single-event__stats-dot">
                                        <img src="/wp-content/plugins/PWElements/includes/calendar/assets/area-icon.png">
                                    </span>
                                    <div>
                                        <span class="single-event__stats-count-up" data-target="'. $area_num .'">0</span><span class="unit">m²</span>
                                        <div class="single-event__stats-caption">'. ($lang_pl ? "Powierzchni wystawienniczej" : "Exhibition space") .'</div>
                                    </div>
                                </div>
                            </div>
                        </div>';

                        if (!empty($exhibitors_logotypes) && count($exhibitors_logotypes) > 40) {

                            $output .= '
                            <div id="singleEventCatalog" class="single-event__catalog">
                                <div class="single-event__catalog-title">
                                    <h3>'. ($lang_pl ? "Wszyscy wystawcy" : "All exhibitors") .'</h3>
                                </div>
                                <div class="single-event__catalog-columns">';

                                // Number of logos in each column
                                $column_logo_counts = [2, 3, 3, 3, 3, 3, 2];
                                $index = 0;

                                for ($col = 0; $col < 7; $col++) {
                                    $output .= '<div class="single-event__catalog-logo-column col-' . ($col+1) . '">';
                                    for ($j = 0; $j < $column_logo_counts[$col]; $j++) {
                                        $img = htmlspecialchars($front_logos[$index]['URL_logo_wystawcy']);
                                        $name = htmlspecialchars($front_logos[$index]['Nazwa_wystawcy']);
                                        $output .= '
                                        <div class="single-event__catalog-logo-tile" data-index="'.$index.'">
                                            <div class="single-event__catalog-flip-card">
                                                <div class="single-event__catalog-flip-card-inner">
                                                    <div class="single-event__catalog-flip-card-front">
                                                        <img src="'. $img .'" alt="'. $name .'">
                                                    </div>
                                                    <div class="single-event__catalog-flip-card-back"></div>
                                                </div>
                                            </div>
                                        </div>';
                                        $index++;
                                    }
                                    $output .= '</div>';
                                }

                                $output .= '
                                </div>

                                <div class="single-event__catalog-button">
                                    <button>'. ($lang_pl ? "Zobacz wszystkich<br>wystawców" : "See all<br>exhibitors") .' '. (ceil(count($unique_logotypes) / 100) * 100) .'+</button>
                                </div>

                            </div>';

                        }

                        if (!empty($events_week_halls_image_url)) {
                            $output .= '
                            <div id="singleEventHalls" class="single-event__halls">
                                <div class="single-event__halls-wrapper">
                                    <div class="single-event__halls-image">
                                        <img src="'. $events_week_halls_image_url .'" alt="Halls">
                                    </div>
                                </div>
                            </div>';
                        } else {

                            $json_data_all = [];

                            foreach ($all_events_json as $item) {
                                $halls = array_map('trim', explode(',', $item['hall']));
                                foreach ($halls as $hall) {
                                    $json_data_all[] = [
                                        "id" => $hall,
                                        "domain" => $item['domain'],
                                        "color" => $item['color']
                                    ];
                                }
                            }          

                            $output .= '
                            <style>
                                .single-event__halls {
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: space-between;
                                    position: relative;
                                }
                                .single-event__halls .single-event__description-wrapper {
                                    margin-top: 18px;
                                    margin-bottom: -54px;
                                }
                                .single-event__halls-info img {
                                    max-width:250px;
                                }
                                .single-event__halls-information {
                                    margin-top: 18px;
                                }
                                .single-event__halls-information p {
                                    margin-top: 0px;
                                    font-size: 18px;
                                    text-transform: uppercase;
                                }
                                .single-event__halls-location {
                                    margin-top: 20px !important;
                                    font-size: 13px;
                                }
                                @media(max-width:960px){
                                    .single-event__halls {
                                        flex-direction: column;
                                    }
                                    .single-event__halls .single-event__description-wrapper {
                                        margin-bottom: 0;
                                    }
                                    .single-event__halls-info {
                                        width: 100%;
                                    }
                                }

                            </style>

                            <div id="singleEventHalls" class="single-event__halls">
                                <div class="single-event__description-wrapper">
                                    <div class="single-event__description-title">
                                        <h3>'. get_the_title() .'</h3>
                                    </div>
                                    <div class="single-event__description-date">
                                        <h4>'. $fair_date .' | <span>Ptak Warsaw Expo</span></h4>
                                    </div>
                                </div>
                                <svg id="pweHallsSvg" class="single-event__halls-svg" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="100 200 3000 1200">

                                    <defs>
                                        <style>
                                            .single-event__halls-element.active {
                                                transform: translate(10px, -20px);
                                                filter: drop-shadow(-10px 20px 20px black);
                                                transition: .3s ease;
                                            }
                                            .single-event__halls-element.active:hover {
                                                transform: translate(0, 0);
                                                filter: drop-shadow(-10px 20px 20px transparent);
                                            }
                                            .single-event__halls-link {
                                                cursor: pointer;
                                                display: none;
                                            }
                                            .single-event__halls-element.active > .single-event__halls-link {
                                                pointer-events: none;
                                                display: block;
                                            }
                                            .single-event__halls-element.unactive > .single-event__halls-link {
                                                pointer-events: unset;
                                                display: block;
                                            }
                                            .single-event__halls-element-color {
                                                fill: transparent;
                                                opacity: .7;
                                            }

                                            .single-event__halls-element-logo image {
                                                width: 300px;
                                                height: 200px;
                                            }
                                            .single-event__halls-element-favicon image {
                                                width: 140px;
                                                height: 140px;
                                            }

                                            #A .single-event__halls-element-logo,
                                            #B .single-event__halls-element-logo,
                                            #C .single-event__halls-element-logo,
                                            #D .single-event__halls-element-logo {
                                                transform: rotateX(55deg) rotateZ(56deg);
                                            }
                                            #A .single-event__halls-element-favicon,
                                            #B .single-event__halls-element-favicon,
                                            #C .single-event__halls-element-favicon,
                                            #D .single-event__halls-element-favicon {
                                                transform: rotateX(55deg) rotateZ(-30deg);
                                            }


                                            #E .single-event__halls-element-logo,
                                            #F .single-event__halls-element-logo {
                                                transform: rotateX(56deg) rotateZ(-27deg);
                                            }
                                            #E .single-event__halls-element-favicon,
                                            #F .single-event__halls-element-favicon {
                                                transform: rotateX(55deg) rotateZ(-30deg);
                                            }

                                            .st0 {
                                                fill: #42ab36;
                                            }
                                            .st1 {
                                                fill: #9dc7b3;
                                            }
                                            .st2 {
                                                fill: url(#Gradient_bez_nazwy_34);
                                            }
                                            .st3 {
                                                fill: url(#Gradient_bez_nazwy_44);
                                            }
                                            .st4 {
                                                fill: #6b5330;
                                            }
                                            .st5 {
                                                fill: url(#Gradient_bez_nazwy_39);
                                            }
                                            .st6 {
                                                fill: url(#Gradient_bez_nazwy_14);
                                            }
                                            .st7 {
                                                fill: url(#Gradient_bez_nazwy_16);
                                            }
                                            .st8 {
                                                fill: #fff;
                                                font-size: 24px;
                                                font-weight: 500;
                                            }
                                            .st8, .st9 {
                                                isolation: isolate;
                                            }
                                            .st10 {
                                                fill: #003c28;
                                            }
                                            .st11 {
                                                fill: url(#Gradient_bez_nazwy_49);
                                            }
                                            .st12 {
                                                fill: url(#Gradient_bez_nazwy_19);
                                            }
                                            .st13 {
                                                fill: #9e5174;
                                            }
                                            .st14 {
                                                fill: url(#Gradient_bez_nazwy_35);
                                            }
                                            .st15 {
                                                fill: url(#Gradient_bez_nazwy_41);
                                            }
                                            .st16 {
                                                fill: url(#Gradient_bez_nazwy_37);
                                            }
                                            .st17 {
                                                fill: #338498;
                                            }
                                            .st18 {
                                                fill: url(#Gradient_bez_nazwy_25);
                                            }
                                            .st19 {
                                                fill: url(#Gradient_bez_nazwy_52);
                                            }
                                            .st20 {
                                                fill: url(#Gradient_bez_nazwy_38);
                                            }
                                            .st21 {
                                                fill: url(#Gradient_bez_nazwy_26);
                                            }
                                            .st22 {
                                                fill: none;
                                            }
                                            .st23 {
                                                fill: url(#Gradient_bez_nazwy_48);
                                            }
                                            .st24 {
                                                fill: #ba1a3b;
                                            }
                                            .st25 {
                                                fill: #ffa935;
                                            }
                                            .st26 {
                                                fill: url(#Gradient_bez_nazwy_11);
                                            }
                                            .st27 {
                                                fill: url(#Gradient_bez_nazwy_24);
                                            }
                                            .st28 {
                                                fill: url(#Gradient_bez_nazwy_13);
                                            }
                                            .st29 {
                                                fill: url(#Gradient_bez_nazwy_50);
                                            }
                                            .st30 {
                                                fill: #c65789;
                                            }
                                            .st31 {
                                                fill: url(#Gradient_bez_nazwy_3);
                                            }
                                            .st32 {
                                                fill: #c4c4c4;
                                            }
                                            .st33 {
                                                fill: #fec902;
                                            }
                                            .st34 {
                                                fill: url(#Gradient_bez_nazwy_15);
                                            }
                                            .st35 {
                                                fill: url(#Gradient_bez_nazwy_23);
                                            }
                                            .st36 {
                                                fill: #f15844;
                                            }
                                            .st37 {
                                                fill: url(#Gradient_bez_nazwy_28);
                                            }
                                            .st38 {
                                                fill: url(#Gradient_bez_nazwy_43);
                                            }
                                            .st39 {
                                                fill: url(#Gradient_bez_nazwy_10);
                                            }
                                            .st40 {
                                                fill: #e4091e;
                                            }
                                            .st41 {
                                                fill: url(#Gradient_bez_nazwy_32);
                                            }
                                            .st42 {
                                                fill: #4fae32;
                                            }
                                            .st43 {
                                                fill: url(#Gradient_bez_nazwy_4);
                                            }
                                            .st44 {
                                                fill: url(#Gradient_bez_nazwy_7);
                                            }
                                            .st45 {
                                                fill: url(#Gradient_bez_nazwy_6);
                                            }
                                            .st46 {
                                                fill: #76bad5;
                                            }
                                            .st47 {
                                                fill: url(#Gradient_bez_nazwy_36);
                                            }
                                            .st48 {
                                                fill: url(#Gradient_bez_nazwy_21);
                                            }
                                            .st49 {
                                                fill: url(#Gradient_bez_nazwy_40);
                                            }
                                            .st50 {
                                                fill: url(#Gradient_bez_nazwy_20);
                                            }
                                            .st51 {
                                                fill: url(#Gradient_bez_nazwy_31);
                                            }
                                            .st52 {
                                                fill: #f2c780;
                                            }
                                            .st53 {
                                                fill: url(#Gradient_bez_nazwy_42);
                                            }
                                            .st54 {
                                                fill: url(#Gradient_bez_nazwy_27);
                                            }
                                            .st55 {
                                                fill: url(#Gradient_bez_nazwy_30);
                                            }
                                            .st56 {
                                                fill: url(#Gradient_bez_nazwy_33);
                                            }
                                            .st57 {
                                                fill: url(#Gradient_bez_nazwy_17);
                                            }
                                            .st58 {
                                                fill: #aaa;
                                            }
                                            .st59 {
                                                fill: url(#Gradient_bez_nazwy_8);
                                            }
                                            .st60 {
                                                fill: #3c3c3b;
                                            }
                                            .st61 {
                                                fill: url(#Gradient_bez_nazwy_45);
                                            }
                                            .st62 {
                                                fill: url(#Gradient_bez_nazwy_12);
                                            }
                                            .st63 {
                                                fill: #ededed;
                                            }
                                            .st64 {
                                                fill: url(#Gradient_bez_nazwy_2);
                                            }
                                            .st65 {
                                                fill: #3583c5;
                                            }
                                            .st66 {
                                                fill: url(#Gradient_bez_nazwy_51);
                                            }
                                            .st67 {
                                                fill: #fab511;
                                            }
                                            .st68 {
                                                fill: url(#Gradient_bez_nazwy_46);
                                            }
                                            .st69 {
                                                fill: url(#Gradient_bez_nazwy_22);
                                            }
                                            .st70 {
                                                fill: url(#Gradient_bez_nazwy_47);
                                            }
                                            .st71 {
                                                fill: #9e284a;
                                            }
                                            .st72 {
                                                fill: #83b163;
                                            }
                                            .st73 {
                                                fill: url(#Gradient_bez_nazwy_29);
                                            }
                                            .st74 {
                                                fill: url(#Gradient_bez_nazwy_18);
                                            }
                                        </style>

                                        <linearGradient id="Gradient_bez_nazwy_2" data-name="Gradient bez nazwy 2" x1="2538" y1="2274.3" x2="2460.7" y2="2449" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".5" stop-color="#cdcccc"/>
                                            <stop offset=".9" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_3" data-name="Gradient bez nazwy 3" x1="2287" y1="1037.7" x2="2244.9" y2="979.6" gradientTransform="translate(0 1594) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".4" stop-color="#ddd"/>
                                            <stop offset=".8" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_4" data-name="Gradient bez nazwy 4" x1="2346.7" y1="2217.3" x2="2267.3" y2="2396.8" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".5" stop-color="#cdcccc"/>
                                            <stop offset=".9" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                            <linearGradient id="Gradient_bez_nazwy_6" data-name="Gradient bez nazwy 6" x1="2665.3" y1="2111.9" x2="2692.5" y2="2012.6" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".2" stop-color="#ddd"/>
                                            <stop offset=".8" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_7" data-name="Gradient bez nazwy 7" x1="2368.5" y1="2063.5" x2="2324.4" y2="2002.7" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#ddd"/>
                                            <stop offset=".7" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_8" data-name="Gradient bez nazwy 8" x1="2465.9" y1="2052.2" x2="2492.8" y2="1954.2" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".2" stop-color="#ddd"/>
                                            <stop offset=".7" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_10" data-name="Gradient bez nazwy 10" x1="1995.8" y1="2117.5" x2="1922.1" y2="2284" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".5" stop-color="#cdcccc"/>
                                            <stop offset=".9" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_11" data-name="Gradient bez nazwy 11" x1="1757.9" y1="902.1" x2="1715.4" y2="843.6" gradientTransform="translate(0 1594) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset=".2" stop-color="#ededed"/>
                                            <stop offset=".4" stop-color="#ddd"/>
                                            <stop offset=".8" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_12" data-name="Gradient bez nazwy 12" x1="1811.7" y1="2065.8" x2="1737.3" y2="2233.9" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".5" stop-color="#cdcccc"/>
                                            <stop offset=".9" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_13" data-name="Gradient bez nazwy 13" x1="2108.6" y1="1962.8" x2="2129.1" y2="1870.4" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".2" stop-color="#ddd"/>
                                            <stop offset=".7" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_14" data-name="Gradient bez nazwy 14" x1="1916.3" y1="1912.7" x2="1940.5" y2="1824.6" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".2" stop-color="#ddd"/>
                                            <stop offset=".7" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_15" data-name="Gradient bez nazwy 15" x1="1823.1" y1="1935.7" x2="1783.3" y2="1880.8" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#ddd"/>
                                            <stop offset=".7" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_16" data-name="Gradient bez nazwy 16" x1="1463.6" y1="2061" x2="1471.7" y2="2033" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset=".2" stop-color="#ea155c"/>
                                            <stop offset=".3" stop-color="#ee4b82"/>
                                            <stop offset=".3" stop-color="#f37ea6"/>
                                            <stop offset=".4" stop-color="#f593b4"/>
                                            <stop offset=".6" stop-color="#ba1a3b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_17" data-name="Gradient bez nazwy 17" x1="1298.6" y1="2106.7" x2="1203" y2="1994.9" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_18" data-name="Gradient bez nazwy 18" x1="1193.3" y1="3150.6" x2="1114.2" y2="3052.3" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_19" data-name="Gradient bez nazwy 19" x1="1584.8" y1="1884.3" x2="1615.9" y2="1794.7" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_20" data-name="Gradient bez nazwy 20" x1="1477.6" y1="1952.1" x2="1383.8" y2="1842.4" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_21" data-name="Gradient bez nazwy 21" x1="1493.4" y1="-1354.7" x2="1519.7" y2="-1251.6" gradientTransform="translate(0 2188)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_22" data-name="Gradient bez nazwy 22" x1="1373.8" y1="2995.9" x2="1295" y2="2897.9" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_23" data-name="Gradient bez nazwy 23" x1="1256.9" y1="1959.2" x2="1260.9" y2="1959.2" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_24" data-name="Gradient bez nazwy 24" x1="1245.4" y1="1967.7" x2="1249.3" y2="1967.7" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_25" data-name="Gradient bez nazwy 25" x1="1208" y1="1990.8" x2="1215.7" y2="1964" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset=".2" stop-color="#ea155c"/>
                                            <stop offset=".3" stop-color="#ee4b82"/>
                                            <stop offset=".3" stop-color="#f37ea6"/>
                                            <stop offset=".4" stop-color="#f593b4"/>
                                            <stop offset=".6" stop-color="#ba1a3b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_26" data-name="Gradient bez nazwy 26" x1="1002.5" y1="2018.6" x2="906.9" y2="1906.9" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_27" data-name="Gradient bez nazwy 27" x1="897.2" y1="3062.5" x2="818.1" y2="2964.2" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_28" data-name="Gradient bez nazwy 28" x1="1288.7" y1="1796.3" x2="1319.8" y2="1706.7" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_29" data-name="Gradient bez nazwy 29" x1="1181.5" y1="1864" x2="1087.9" y2="1754.5" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_30" data-name="Gradient bez nazwy 30" x1="1197.3" y1="-1266.7" x2="1223.6" y2="-1163.5" gradientTransform="translate(0 2188)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_31" data-name="Gradient bez nazwy 31" x1="1077.7" y1="2907.8" x2="998.9" y2="2809.9" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_32" data-name="Gradient bez nazwy 32" x1="958.2" y1="1873.3" x2="962.1" y2="1873.3" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_33" data-name="Gradient bez nazwy 33" x1="946.6" y1="1881.8" x2="950.6" y2="1881.8" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_34" data-name="Gradient bez nazwy 34" x1="911.4" y1="1900.8" x2="919" y2="1874.3" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset=".2" stop-color="#ea155c"/>
                                            <stop offset=".3" stop-color="#ee4b82"/>
                                            <stop offset=".3" stop-color="#f37ea6"/>
                                            <stop offset=".4" stop-color="#f593b4"/>
                                            <stop offset=".6" stop-color="#ba1a3b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_35" data-name="Gradient bez nazwy 35" x1="705.5" y1="1931.5" x2="609.9" y2="1819.8" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_36" data-name="Gradient bez nazwy 36" x1="601.1" y1="2974.8" x2="522" y2="2876.5" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_37" data-name="Gradient bez nazwy 37" x1="991.8" y1="1709.2" x2="1022.9" y2="1619.6" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_38" data-name="Gradient bez nazwy 38" x1="884.6" y1="1777.1" x2="790.4" y2="1666.9" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_39" data-name="Gradient bez nazwy 39" x1="900.3" y1="-1179.6" x2="926.7" y2="-1076.4" gradientTransform="translate(0 2188)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_40" data-name="Gradient bez nazwy 40" x1="780.8" y1="2820.8" x2="701.9" y2="2722.8" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_41" data-name="Gradient bez nazwy 41" x1="663.5" y1="1784.5" x2="667.4" y2="1784.5" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_42" data-name="Gradient bez nazwy 42" x1="651.9" y1="1793" x2="655.8" y2="1793" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_43" data-name="Gradient bez nazwy 43" x1="616.5" y1="1816.6" x2="624.8" y2="1787.9" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset=".2" stop-color="#ea155c"/>
                                            <stop offset=".3" stop-color="#ee4b82"/>
                                            <stop offset=".3" stop-color="#f37ea6"/>
                                            <stop offset=".4" stop-color="#f593b4"/>
                                            <stop offset=".6" stop-color="#ba1a3b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_44" data-name="Gradient bez nazwy 44" x1="412.4" y1="1845.7" x2="316.8" y2="1734" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_45" data-name="Gradient bez nazwy 45" x1="307.1" y1="2889.6" x2="228" y2="2791.3" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_46" data-name="Gradient bez nazwy 46" x1="693.3" y1="1629.4" x2="780.3" y2="1429.1" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_47" data-name="Gradient bez nazwy 47" x1="591.5" y1="1691.1" x2="497.7" y2="1581.4" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_48" data-name="Gradient bez nazwy 48" x1="607.2" y1="-1094" x2="633.6" y2="-990.4" gradientTransform="translate(0 2188)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset="0" stop-color="#ddd"/>
                                            <stop offset=".4" stop-color="#9e9e9e"/>
                                            <stop offset="1" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_49" data-name="Gradient bez nazwy 49" x1="487.6" y1="2735" x2="408.7" y2="2637" gradientTransform="translate(0 3782) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#ededed"/>
                                            <stop offset=".3" stop-color="#9e9e9e"/>
                                            <stop offset=".5" stop-color="#1d1d1b"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_50" data-name="Gradient bez nazwy 50" x1="367.6" y1="1700.3" x2="371.5" y2="1700.3" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_51" data-name="Gradient bez nazwy 51" x1="356" y1="1708.8" x2="360" y2="1708.8" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#b2b2b2"/>
                                            <stop offset="0" stop-color="#c4c4c4"/>
                                            <stop offset=".2" stop-color="#dedede"/>
                                            <stop offset=".2" stop-color="#f0f0f0"/>
                                            <stop offset=".3" stop-color="#fbfbfb"/>
                                            <stop offset=".4" stop-color="#fff"/>
                                            <stop offset=".5" stop-color="#fafafa"/>
                                            <stop offset=".6" stop-color="#eee"/>
                                            <stop offset=".7" stop-color="#d8d8d8"/>
                                            <stop offset=".8" stop-color="#bbb"/>
                                            <stop offset=".9" stop-color="#959595"/>
                                            <stop offset=".9" stop-color="#878787"/>
                                        </linearGradient>
                                        <linearGradient id="Gradient_bez_nazwy_52" data-name="Gradient bez nazwy 52" x1="365.2" y1="1740.8" x2="373.3" y2="1712.7" gradientTransform="translate(0 2688) scale(1 -1)" gradientUnits="userSpaceOnUse">
                                            <stop offset=".2" stop-color="#ea155c"/>
                                            <stop offset=".3" stop-color="#ee4b82"/>
                                            <stop offset=".3" stop-color="#f37ea6"/>
                                            <stop offset=".4" stop-color="#f593b4"/>
                                            <stop offset=".6" stop-color="#ba1a3b"/>
                                        </linearGradient>
                                    </defs>

                                    <g id="drogi">
                                        <g>
                                        <path class="st32" d="M2994.2,683.8l-57.4-48.9-259-217.4c-2.2-2.5-7.9-3.7-12.7-2.7l-489.1,133.2c-8.7-5.6-23.7-7.9-36.8-5.2-12.9,2.7-19.9,9.5-17.8,16.3-107.2,21.4-458,134.6-582.3,157.5l-261.5-221.6c-.6-.5-1.4-.9-2.4-1.1l-3.7-3.1L134.6,826.4c-4.4,1.3-5.8,4.3-3.1,6.6l417,354.3c2.8,2.3,8.7,3.2,13.2,1.9l238.9-69.4c10.8,6,27.9,8.4,43.1,5.5,48.3,28.3,105.3,21.8,174.9,13.9,18.6-2.1,38.5-5.6,58.2-9.7,54.9-11.4,108.3-27.8,129.5-34.5,6.3,7.2,11,13.6,13.3,18.5,9.1,19.2,12.9,42.4,10.1,50.7l-7.2,27.2,19.8-5.5,6.4-21.7c2.7-8.1,0-17-3.6-31.9-.8-4.2-1.2-11.1,3.6-12.4,6.6-1.9,21.4,11,22.7,12.1h0c13.7,12.1,25.9,22.6,26.8,23.4l11.8,10.8,17.5-5-12.9-10.5c-.4-.3-36.4-30.8-60-48.6-19.9-15.1-58.6-45.9-93.8-67.8-.7-.4-1.4-.9-2.1-1.3,7.4-5.3,9.2-12.5,3.4-19.1h0l232.5-64.1c10.9,4.3,25.6,5.7,38.7,2.9,15.8-3.3,24.4-11.4,22.3-19.8l288.7-79.4c5.8,3.6,14.1,5.8,22.9,6.1l143.6,134.4,15.4-4.9-140.6-131.8c4-1.3,7.2-3.1,9.5-5.1,87.5,14.8,245.7-14.7,336.4-21.4l2.6-.2c18.8-1.4,158.7-31.1,266.8-47.3l60,53,15.9-4.8-57.8-51c55.3-8.4,106.6-16.8,106.6-16.8,0,0,382.9-110.3,404.3-116,7.6,6.7,40.7,34.1,46.8,39.5M1273.1,501.9l255.7,216.6c-1.5.3-2.9.5-4.3.8l-4.1.7-254.8-215.9,7.6-2.2h0ZM675.3,678.1l408.4,346c.2.2.4.3.7.4l-231.3,67.3-404.8-346.8,227-66.9h0ZM791.3,1111l-231.5,67.3-407.1-345.8,230.5-68.3,407.2,345c.2.6.5,1.2.8,1.8h0ZM792,1099.8l-399.4-338.4,46.5-13.7,397,340.1c-7.6-.9-15.8-.8-23.4.8-10.2,2.1-17.3,6.3-20.6,11.2h0ZM1013.6,1129.6c-71.9,8.2-116.5,11.8-154.7-9.9,8.3-5.2,10.7-12.7,5.1-19.6l232.6-67.6c10.7,7.3,29.8,10.4,46.5,7,.3,0,.6-.2.9-.2,18.9,13.6,39,31.1,53.9,46.6-25.1,8.1-115.4,35.9-184.4,43.8h0ZM1111.1,1003c-12.6,2.6-20.6,8.4-22.2,14.9l-404.3-342.5,47.9-14.1,399.5,340.7c-6.9-.6-14.1-.3-20.9,1.1h0ZM1382.4,941.8l-232.5,64.1c-.4-.2-.8-.3-1.2-.4l-406.9-347,226.8-66.9,411.1,348.1c.5.4,1.1.7,1.8,1,.3.3.5.7.8,1h0ZM1401.4,916.2c-11.4,2.4-19.1,7.3-21.6,13l-402-340.4,45.9-13.5,401.7,340.1c-7.8-1-16.2-.9-24,.7h0ZM1448.5,923.6c-1.1-.8-2.3-1.5-3.6-2.2l-411.9-348.7,223.3-65.8,418.3,354.4-226.1,62.4h0ZM1684,858.6l-153.4-130,.9-.2c2.3-.4,4.8-.8,7.5-1.3l152.7,129.4-7.7,2.1h0ZM2381.8,776.9l-253.8,44.3c-147.6,10.8-267,32.3-329.1,21.5-.4-1.5-1.2-3.1-2.6-4.6-7.1-8.1-25.5-12-41.1-8.8-12.2,2.5-19,8.7-18.1,15.1l-35.9,9.6-151.8-128.6c129.2-23.9,475.8-136.3,580.7-157.2,8.9,4.8,34.9,4.1,34.9,4.1,0,0,18.9-8.6,18.1-14.9l486.4-131.6,251.2,213.9c-61.7,15.9-403.1,115.1-403.1,115.1l-135.8,22.1h0Z"/>
                                        <rect class="st32" x="549.7" y="1032.6" width="2569.1" height="18.7" rx="9.3" ry="9.3" transform="translate(-215.3 542.4) rotate(-15.9)"/>
                                        </g>
                                        <rect class="st32" x="534.3" y="1013.1" width="2569.1" height="18.7" rx="9.3" ry="9.3" transform="translate(-210.5 537.4) rotate(-15.9)"/>
                                    </g>

                                    <g id="F"  class="single-event__halls-element full">
                                        <g id="F2_F1" class="single-event__halls-element half">
                                            <g id="F2" class="single-event__halls-element quarter">
                                                <g id="obiekt_F2">
                                                    <polygon id="bok_F2" class="st58" points="2424.3 456.9 2518.8 529.7 2518.8 586.6 2424.3 511.8 2424.3 456.9"/>
                                                    <polygon id="przod" class="st58" points="2518.8 586.6 2697.8 537 2697.9 474.6 2518.8 529.7 2518.8 586.6"/>
                                                    <polygon id="sufit_F2" class="st64" points="2424.3 456.9 2610.7 403.8 2613.6 403.8 2697.8 474.5 2518.8 529.7 2424.3 456.9"/>
                                                </g>
                                                <polygon id="kolor_F2" class="single-event__halls-element-color" points="2613.6 403.8 2697.8 474.5 2697.8 525.2 2518.8 586.6 2424.3 517.3 2424.3 456.9 2610.7 403.8 2613.6 403.8"/>
                                                <path id="belka_F2" class="st65" d="M2697.8,474.5l-84.2-70.7h-3s3-7.9,8.2-4.7,85,72.2,85,72.2c0,0-.2,4.4-6.1,3.2h.1Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1820" y="1840"/>
                                                </a>
                                            </g>

                                            <g id="F1" class="single-event__halls-element quarter">
                                                <g id="obiekt_F1">
                                                    <path id="bok_F1" class="st58" d="M2609.5,659l-90.7-72.4v-56.9l34.9,26.8,32.7,25.2,16.4,12.6,2,1.5s3.7,2.3,4.5,7.3.2,1.5.2,2.2v53.7h0Z"/>
                                                    <path id="struktura_F1" class="st45" d="M2795.5,604.7l-186,54.3v-53.7s.5-5.4-4.6-9.4-86.1-66.2-86.1-66.2l179.2-55.2,94.8,85.6s4,3,3.9,8.9c.2,5.9-1.1,35.8-1.1,35.8h0Z"/>
                                                </g>
                                                <path id="kolor_F1" class="single-event__halls-element-color" d="M2796.6,569c0-5.8-3.9-8.9-3.9-8.9l-94.8-85.6-179.1,55.2s80.9,62.2,86,66.2l-86-66.2v56.9c.1,0,90.7,72.4,90.7,72.4l186-54.3s1.3-29.9,1.1-35.8h0ZM2609.4,605.3c0-2-.4-3.6-1-5,1.3,2.7,1,5,1,5ZM2608.1,599.8s.1.2.2.4c0,0-.1-.2-.2-.4Z"/>
                                                <path id="belka_F1" class="st65" d="M2795.5,604.7l3.7,2.7,4.8-2.7,1.2-38.1s0-5.6-4.3-10.1-96.9-85.1-96.9-85.1c0,0-5.9-1.7-6.1,3.2,3.5,3.2,71.7,64.9,71.7,64.9l23.1,20.7c2.8,2.4,3.9,5.9,3.9,8.9v9.9c-.1,0-1.1,25.9-1.1,25.9v-.2h0Z"/>
                                                <g id="numer_hali_F" class="st9">
                                                    <g class="st9">
                                                        <g class="st9">
                                                            <text class="st8" transform="translate(2775.6 588.9)"><tspan x="-6" y="6">F</tspan></text>
                                                        </g>
                                                    </g>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1840" y="2000"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="1750" y="1980"/>
                                            </a>
                                        </g>

                                        <g id="F4_F3" class="single-event__halls-element half">
                                            <g id="F4" class="single-event__halls-element quarter">
                                                <g id="obiekt_F4">
                                                    <polygon id="bok_F4" class="st31" points="2301.9 643.1 2301.9 594.8 2227.5 531 2225.7 539.3 2227.5 579.2 2301.9 643.1"/>
                                                    <polygon id="przod_wnetrze_F4" class="st58" points="2301.9 643.1 2518.9 586.6 2518.8 529.7 2301.9 594.8 2301.9 643.1"/>
                                                    <polygon id="gora_F4" class="st43" points="2518.8 529.7 2424.3 456.9 2236.5 512.1 2227.5 531.1 2301.8 594.8 2518.8 529.7"/>
                                                </g>
                                                <polygon id="kolor_F4" class="single-event__halls-element-color" points="2424.3 456.9 2518.8 529.7 2518.8 586.6 2301.9 643.1 2227.6 579.3 2225.8 539.5 2227.5 531.1 2236.5 512.1 2424.3 456.9"/>
                                                <path id="belka_F4" class="st65" d="M2301.8,594.8l-74.3-63.8,9-19s.9-3.6-2.2-3.6-4.3,3.2-4.3,3.2l-11.1,22.6v42.3l4,4.4,4.6-1.7-1.8-39.9,1,.3,75,65.4.2-10.2h0Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1600" y="1840"/>
                                                </a>
                                            </g>

                                            <g id="F3" class="single-event__halls-element quarter">
                                                <g id="obiekt_F3">
                                                    <path id="bok_F3" class="st44" d="M2388.3,718.4l-86.4-75.3v-48.2l90.7,77.6s5.2,4.5,5.2,10.9-9.4,35-9.4,35h-.1Z"/>
                                                    <path id="struktura_F3" class="st59" d="M2396.5,721.5l1.2-36.2s.9-8-4.3-12-91.5-78.5-91.5-78.5l216.9-65.1,85.7,65.9s2.5,1.7,4,4.8,1,2.6,1,4.2v54.4l-213,62.5h0Z"/>
                                                </g>
                                                <path id="kolor_F3" class="single-event__halls-element-color" d="M2609.5,605.3c.2-6.6-4.6-9.4-4.6-9.4l-86.1-66.2-216.9,65.1v48.3l86.3,75.2,8.3,3.2,213-62.5v-53.8h0Z"/>
                                                <path id="belka_F3" class="st65" d="M2388,721.5l3.7,2.7,4.8-2.7,1.2-38.1s0-5.6-4.3-10.1-91.5-78.5-91.5-78.5c0,0-3.9,3.5-.2,10.2,3.5,3.2,60.5,51.2,60.5,51.2l23.1,20.7c2.8,2.4,3.9,5.9,3.9,8.9v9.9c-.1,0-1.1,25.9-1.1,25.9h0Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1620" y="2000"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="1530" y="1990"/>
                                            </a>
                                        </g>

                                        <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link full">
                                            <image class="single-event__halls-element-logo" href="" width="350" height="300" x="1620" y="1835"/>
                                        </a>
                                    </g>

                                    <g id="E" class="single-event__halls-element full">
                                        <g id="E2_E1" class="single-event__halls-element half">
                                            <g id="E2" class="single-event__halls-element quarter">
                                                <g id="obiekt_E2">
                                                    <polygon id="bok_E2" class="st58" points="1891 610.1 1970.5 678.7 1970.5 735.2 1891 660.2 1891 610.1"/>
                                                    <polygon id="przod_E2" class="st58" points="1970.5 678.7 1970.5 735.2 2144.6 685.6 2144.6 630.1 1970.5 678.7"/>
                                                    <polygon id="struktura_E2" class="st39" points="1970.5 678.7 2144.6 630.1 2081.1 573.5 2038.3 567.7 1891 610.1 1970.5 678.7"/>
                                                </g>
                                                <path id="kolor_E2" class="single-event__halls-element-color" d="M1970.5,678.7l174.1-48.5-63.5-56.6-42.8-5.8-147.3,42.4v49.1c0,1.2,79.4,75.9,79.4,75.9l174.2-49.6v-55.4c-.1,0-174.2,48.5-174.2,48.5h0Z"/>
                                                <path id="belka_E2" class="st36" d="M2144.6,630.1s6.1-.7,5.5-3.8c-2.7-2.3-58.7-51.8-64.6-57s-.7-.5-1.2-.5l-45.4-5.9s-8.3-.2-7.6,6.9l6.9-2,42.8,5.8,63.5,56.6h.1Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1210" y="1840"/>
                                                </a>
                                            </g>

                                            <g id="E1" class="single-event__halls-element quarter">
                                                <g id="obiekt_E1">
                                                    <path id="bok_E1" class="st58" d="M2038.6,801.6l-68.1-66.4v-56.5l53.5,44.6s2.2,1.4,4.8,3.7,6,5.9,8.1,11.4,1.3,3.6,1.6,5.7.2,4.4.2,6.7v50.8h0Z"/>
                                                    <path id="struktura_E1" class="st28" d="M2038.6,801.6l43.1-6.9,150.7-43.1,1.1-37.7v-1.3c-.2-1.1-.7-2.2-1.3-3l-1.1-1.5-86.5-78-174.1,48.6,40.1,33.4,13.3,11.1s7.9,5.3,10.3,9.8,3.8,7.1,4.3,11.7v56.6c0,0,0,0,0,0h0v.2Z"/>
                                                </g>
                                                <path id="kolor_E1" class="single-event__halls-element-color" d="M2233.4,712.7c-.1-1.1-.5-2.2-1.2-3l-1.1-1.5-86.5-78-174.1,48.5v56.5l68.1,66.4v-48.6,48.6l43.1-6.9,150.7-43.1,1.1-37.7v-1.3h-.1ZM2034.3,733.2s0,.2.2.3c-1.3-2-2.8-3.7-4.2-5.1,1.6,1.5,3.1,3.2,4,4.8ZM2038,741.6c.3,1,.5,2.1.6,3.3v.8c0-1.5-.3-2.9-.6-4.2h0Z"/>
                                                <path id="belka_E1" class="st36" d="M2144.6,630.1s.4-7.9,5.8-3.6,85.5,75.6,85.5,75.6c0,0,5.8,2.6,5.4,18.3s-.9,31.2-.9,31.2l-3.8,1.7-4.1-1.9,1.1-37.7s.6-3.1-7.4-10.3-81.5-73.5-81.5-73.5v.2h-.1Z"/>
                                                <g id="numer_hali_E" class="st9">
                                                    <g class="st9">
                                                        <g class="st9">
                                                            <text class="st8" transform="translate(2215 735.4)"><tspan x="-6" y="6">E</tspan></text>
                                                        </g>
                                                    </g>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1220" y="1990"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="1150" y="1930"/>
                                            </a>
                                        </g>

                                        <g id="E4_E3" class="single-event__halls-element half">
                                            <g id="E4" class="single-event__halls-element quarter">
                                                <g id="obiekt_E4">
                                                    <polygon id="przod_wnetrze_E4" class="st58" points="1970.5 678.7 1970.5 735.2 1773.5 779.8 1773.5 732 1970.5 678.7"/>
                                                    <polygon id="bok_E4" class="st26" points="1773.5 732 1773.5 779.8 1697.3 713.2 1697.3 666 1773.5 732"/>
                                                    <polygon id="sufit_E4" class="st62" points="1891 610.1 1697.3 666 1773.5 732 1970.5 678.7 1891 610.1"/>
                                                </g>
                                                <polygon id="kolor_E4" class="single-event__halls-element-color" points="1970.5 735.1 1773.5 779.8 1697.3 713.2 1697.3 666 1891 610.1 1970.5 678.7 1970.5 735.1"/>
                                                <path id="belka_E4" class="st36" d="M1773.5,732v8.5l-76-65.2-.8.4,1.6,38.9-3.8,1.4-3.6-3.1-1.7-40.3s0-12.3,8.7-6,75.5,65.4,75.5,65.4h.1Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1000" y="1840"/>
                                                </a>
                                            </g>

                                            <g id="E3" class="single-event__halls-element quarter">
                                                <g id="struktura_E3">
                                                    <path id="sciana_i_sufit_E3" class="st6" d="M1839.3,799.8l-1.4,30.5,200.7-28.7v-54.8s.2-8.6-6.9-17c-2-2.2-4.5-4.4-7.7-6.6-3.1-2.6-7.9-6.5-13.3-11.1-16.9-14.1-40.1-33.5-40.1-33.5l-197,53.5,58.1,50c5,4.6,7.7,11,7.7,17.8v-.2h0Z"/>
                                                    <polygon id="SCIANA_BOK_E3" class="st34" points="1773.5 733.2 1773.5 779.8 1830.5 829.5 1832.3 788.1 1773.5 733.2"/>
                                                </g>
                                                <g id="kolor_E3">
                                                    <path id="SUFIT_KOLOR_E3" class="single-event__halls-element-color" d="M2024,723.3c-12.8-10.7-53.5-44.6-53.5-44.6l-197,53.4,58.1,50c5,4.6,7.7,11,7.7,17.8l-1.4,30.5,200.7-28.6v-54.9s1-13-14.6-23.4v-.2h0Z"/>
                                                    <polygon id="BOK_KOLOR_E3" class="single-event__halls-element-color" points="1773.5 779.8 1830.5 829.5 1832.3 788.1 1773.5 733.2 1773.5 779.8"/>
                                                </g>
                                                <path id="belka_bok_E3" class="st36" d="M1773.5,740.5l-1.5-4.5c-.4-1.2,0-2.6.9-3.5l.6-.5,58.9,49s8.6,6.1,6.8,19c0,13-.8,31-.8,31l-5.8,2.2-3.3-3.1,1.2-30.4c.2-4.7-1.7-9.3-5.2-12.5l-51.9-46.7h.1Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1020" y="1980"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="940" y="1920"/>
                                            </a>
                                        </g>

                                        <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link full">
                                            <image class="single-event__halls-element-logo" href="" width="330" height="290" x="1020" y="1830"/>
                                        </a>
                                    </g>

                                    <g id="tunel_D">
                                        <path class="st24" d="M1482.7,653.4l-5.2,1.3-8-7c-2.1-1.8-3.6-3-6.1-5.3l-.5-4.7,19.8-2.2v18h0Z"/>
                                        <path id="Tunel_D" class="st7" d="M1481.3,632.2l-10.9-9.5c-.7-.6-1.6-.8-2.5-.6l-21,6.1,19.7,16.9,14-2.9c1.1-.3,1.9-1.3,2-2.5,0-1.7.2-4.1.2-5.2s-1.4-2.3-1.4-2.3h0Z"/>
                                    </g>

                                    <g id="D" class="single-event__halls-element full">
                                        <g id="D3_D4" class="single-event__halls-element half">
                                            <g id="D3" class="single-event__halls-element quarter">
                                                <g id="struktura_D3">
                                                    <g>
                                                    <path id="sufit_D3" class="st63" d="M1453,632.6l-89.9,23.6-175-150.6,82.6-24s2.7-.6,4.2-.4,3,.5,3.7,1.1c1.1.9,174.4,150.3,174.4,150.3h0Z"/>
                                                    <polygon id="sciana_bok_D3" class="st57" points="1363.1 710.6 1190.1 558.2 1188.2 505.6 1363.1 656.2 1363.1 710.6"/>
                                                    </g>
                                                    <polygon id="sciana_przod_D3" class="st58" points="1453 632.6 1450.1 689.5 1363.1 710.6 1363.1 656.2 1453 632.6"/>
                                                </g>
                                                <path id="kolor_D3" class="single-event__halls-element-color" d="M1278.6,482.4l174.4,150.3-3,56.9-87,21.1h0l-173-152.4-1.9-52.6,82.5-23.9s2.5-.7,4.5-.5,2.2.4,3.4,1.1h0Z"/>
                                                <path id="belka_D3" class="st25" d="M1188.2,505.6l82.6-24s5-1.4,7.9.7c-.2-3.6,0-12.1-13.4-7.9s-77,22.5-77,22.5c0,0-4,2.1,0,8.7h0Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1480" y="-590"/>
                                                </a>
                                            </g>

                                            <g id="D4" class="single-event__halls-element quarter">
                                                <g id="Struktura_D4">
                                                    <path id="przod_sciany_D4" class="st58" d="M1259.8,735.1l103.3-24.5v-54.4l-97.7,22.7s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,8.8.5,45.6.5,45.6h0Z"/>
                                                    <path id="sciany_D4" class="st74" d="M1259.8,735.1l-.5-48.6s-.3-5.9,6.9-7.7c7.2-1.9,96.9-22.6,96.9-22.6l-174.8-150.4-106.8,31.8s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.8h0Z"/>
                                                </g>
                                                <path id="kolor_D4" class="single-event__halls-element-color" d="M1363.1,656.2s-56.9,13.3-83.9,19.5c26.4-6.2,83.9-19.6,83.9-19.5l-174.9-150.6-106.7,31.9s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.9h0c0,0,103.2-24.6,103.2-24.6v-54.3Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1460" y="-490"/>
                                                </a>
                                                <path id="Belka_D4" class="st25" d="M1070.7,575.7l5.3,4.5h4.4s-5.4-31.9-5.3-32.5-1.3-8.1,6.3-10.3,106.8-31.8,106.8-31.8c0,0,3.3-3.3,0-8.7-7.2,2-110.3,31.3-110.3,31.3,0,0-12.1,2.6-10.8,14.9s3.6,32.6,3.6,32.6Z"/>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="520" y="1430"/>
                                            </a>
                                        </g>

                                        <g id="D1_D2" class="single-event__halls-element half">
                                            <g id="D1" class="single-event__halls-element quarter">
                                                <g id="objekt_D1">
                                                    <polygon id="sufit_D1" class="st63" points="1547.2 815.2 1636.8 791.8 1453 632.6 1363.1 656.2 1547.2 815.2"/>
                                                    <polygon id="przod_D1" class="st12" points="1638 840.7 1548.1 867.1 1547.2 825.1 1638.7 798.1 1642 799.2 1640 839.7 1638 840.7"/>
                                                    <polygon id="sciana_bok_D1" class="st50" points="1547.2 815.2 1547.2 825.1 1548.1 867.1 1363.2 710.6 1363.1 656.2 1547.2 815.2"/>
                                                </g>
                                                <path id="kolor_D1" class="single-event__halls-element-color" d="M1636.8,791.8l-89.7,23.5v2.4c-.2,1.1-.2,2.3,0,3.4v4l75.7-21.6,12.5-3.6s2.2-1.1,4.2-.8,1.4,3,1.4,3.4-3.8,38.5-3.8,38.5l-88.9,26.1-185-156.5v-54.4l90-23.5,183.8,159.2h-.2Z"/>
                                                <path id="belka_D1" class="st25" d="M1547.2,815.2l90.6-24.1s6.2-1.2,8.6,2.4,1.7,10.8,1.7,10.8l-2.8,36.3-3.6,1.7-4.8-1.2,3.9-39.4s1.2-4.6-5.6-1.8c-7.2,2.1-88.1,25.2-88.1,25.2l-.2-5v-4.8h.3Z"/>
                                                <g id="WEJSCIE_D1">
                                                    <polygon class="st60" points="1548.1 867.1 1558.2 864 1564.6 849.2 1561.9 838.6 1552.8 835.5 1548 835.7 1547.4 837.4 1548.1 867.1"/>
                                                    <path class="st25" d="M1547.3,831.8s10.2-2.7,15.3,3.2,6,13.7.9,22.7c-1.6,2.4-2.9,4.5-3.6,5.3s-2.6,1.3-3.6.3-.6-2.2,0-3.8,4.9-7.4,5.1-12.6c-.3-7.5-4-11.3-14-9.6v-5.6h-.1Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1790" y="-590"/>
                                                </a>
                                                <g id="numer_hali_D" class="st9">
                                                    <g class="st9">
                                                        <g class="st9">
                                                            <text class="st8" transform="translate(1619.9 823.8)"><tspan x="-6" y="6">D</tspan></text>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>

                                            <g id="D2" class="single-event__halls-element quarter">
                                                <g id="struktura_D2">
                                                    <path id="przod_sciany_D2" class="st48" d="M1548.1,867.1l-5.8-17.6,5.1-17.6-.2-6.7-91,24.6s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,6.9,1.9,23.6,2.6,30.5.2,1.9.3,3.3.3,3.3l95.1-26.9h0Z"/>
                                                    <path id="sciany_D2" class="st69" d="M1447.9,895.7l-5.2-30.2c-.8-4.5-.4-9.2,1.2-13.5s3.1-5.9,6.1-6.7c7.2-1.8,97.1-30.1,97.1-30.1l-184-159-35.2,8.2-59.5,13.8s-5.7.8-7.8,4.3c-2,2.7-1.5,7.2-1.4,11,0,8.7.5,41.4.5,41.4l188.1,160.7h.1Z"/>
                                                </g>
                                                <g id="kolor_D2">
                                                    <path id="BOK_d2" class="single-event__halls-element-color" d="M1448.3,846c.3,0,1.4-.5,3.6-1.2,18.8-5.5,95.2-29.6,95.2-29.6h0l-184.1-159-94.6,22c-3.3.6-9.2,2.4-9.2,15.5.2,11.4.5,41.2.5,41.2l188.1,160.7-4.4-25.5c-.4-2.1-1.3-10.1-1-12.2.8-5.8,2.3-9,5.8-11.9h0Z"/>
                                                    <path id="przod_D2" class="single-event__halls-element-color" d="M1542.3,849.5l5.1-17.6v-1.9l-.2-4.8s-86.8,23.4-91.7,24.8c-6.7,2.3-5.7,10.3-5.7,10.3h0c0,1.7,3.2,33.7,3.2,33.7l95.1-26.9-5.8-17.6Z"/>
                                                </g>
                                                <path id="belka_D2" class="st25" d="M1448.7,896.6c-1.5-1.5-2.9-2.5-3.7-3.4-.8-7.2-3.7-34-2-37.7.6-1.8,1.3-5.2,2.9-7.1s5.3-3.4,5.3-3.4l91.1-28.3c2.5-.8,5-1.5,5-1.5v9.9l-2.8.8-88.3,24c-.4,0-1.4.4-2.6,1.3-1.7,1.2-2.8,3.2-3.1,5.3,0,0-.4,2.4-.4,5.1l2.9,33c-2.4,1.1-4.3,2.1-4.3,2.1h0Z"/>
                                                <g id="Wejscie_D2">
                                                    <path class="st60" d="M1547.4,837.4l.6,29.7-9.1,2.6s-9.4-10.9-7-18,7.3-13.1,11.2-14.6,4.2.2,4.2.2h.1Z"/>
                                                    <path class="st25" d="M1539.5,869.5s-2.8,1.4-5.6-1.6-6.6-11-4-19c3.2-13,17.4-17.1,17.4-17.1v5.6c-1.5.5-8.1,4-11.2,9.7v.2c-3,5.6-2,12.4,2.1,17.3s2.9,3.2,1.4,4.9h-.1Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1780" y="-490"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="540" y="1750"/>
                                            </a>
                                        </g>

                                        <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link full">
                                            <image class="single-event__halls-element-logo" href="" width="350" height="200" x="1530" y="-580"/>
                                        </a>
                                    </g>

                                    <g id="wejscie_bok_d">
                                        <polygon class="st24" points="1272.2 745.6 1255.7 750.5 1241.6 738.3 1258.1 733.4 1272.2 745.6"/>
                                        <polygon class="st60" points="1272.2 745.6 1257.9 733.3 1257.8 702.5 1272.1 714.4 1272.2 745.6"/>
                                        <path class="st35" d="M1258.7,748.3c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5h0Z"/>
                                        <path class="st27" d="M1247.1,739.8c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5h0Z"/>
                                    </g>

                                    <g id="tunel_c_d">
                                        <path class="st24" d="M1256.5,721.5l13.5-4.4c1.3-.4,2.2-1.8,2.2-3.4l-.2-13.4v-9l-15.4.9v29.3h0Z"/>
                                        <path class="st24" d="M1261.4,720.1l-78,23.5-13.9-12.1c-2.1-1.8-2-1.7-3.5-3l3.1-2.2v-7.9l92.2-27.7v29.4h.1Z"/>
                                        <path class="st18" d="M1255,680.1l-108.4,31.7c7.4,6.4,19.3,16.7,19.3,16.7l106-31.1v-4.2c0-1.2.2-4-1.8-5.2l-15.2-7.9h0Z"/>
                                    </g>

                                    <g id="C" class="single-event__halls-element full">
                                        <g id="C3_C4" class="single-event__halls-element half">
                                            <g id="C3" class="single-event__halls-element quarter">
                                                <g id="struktura_C3">
                                                    <g id="sufit_i_bok_C3">
                                                    <path id="sufit_c3" class="st63" d="M1156.9,720.7l-89.9,23.7-175-150.7,82.6-24s2.7-.6,4.2-.4,3,.5,3.7,1.1c1.1.9,174.4,150.3,174.4,150.3h0Z"/>
                                                    <polygon id="sciana_bok_C3" class="st21" points="1067 798.7 894 646.3 892.1 593.7 1067 744.4 1067 798.7"/>
                                                    </g>
                                                    <polygon id="sciana_przod_C3" class="st58" points="1156.9 720.7 1154 777.5 1067 798.7 1067 744.4 1156.9 720.7"/>
                                                </g>
                                                <path id="kolor_C3" class="single-event__halls-element-color" d="M982.5,570.4l174.4,150.3-3,56.9-87,21.1h0l-173-152.4-1.9-52.6,82.5-23.9s2.5-.7,4.5-.5,2.2.4,3.4,1.1h0Z"/>
                                                <path id="belka_C3" class="st0" d="M892.1,593.7l82.6-24s5-1.4,7.9.7c-.2-3.6,0-12.1-13.4-7.9s-77,22.5-77,22.5c0,0-4,2.1,0,8.7h0Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1450" y="-260"/>
                                                </a>
                                            </g>

                                            <g id="C4" class="single-event__halls-element quarter">
                                                <g id="Struktura_C4">
                                                    <path id="przod_sciany_C4" class="st58" d="M963.7,823.2l103.3-24.5v-54.3l-97.7,22.6s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,8.8.5,45.6.5,45.6h0Z"/>
                                                    <path id="sciany_C4" class="st54" d="M963.7,823.2l-.5-48.6s-.3-5.9,6.9-7.7,96.9-22.5,96.9-22.5l-174.8-150.5-106.8,31.8s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.8h-.1Z"/>
                                                </g>
                                                <path id="kolor_C4" class="single-event__halls-element-color" d="M1067,744.4l-174.9-150.7-106.7,31.9s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.9h0c0,0,103.2-24.6,103.2-24.6v-54.2h0Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1410" y="-160"/>
                                                </a>
                                                <path id="Belka_C4" class="st0" d="M774.6,663.8l5.3,4.5h4.4s-5.4-31.9-5.3-32.5-1.3-8.1,6.3-10.3,106.8-31.8,106.8-31.8c0,0,3.3-3.3,0-8.7-7.2,2-110.3,31.3-110.3,31.3,0,0-12.1,2.6-10.8,14.9s3.6,32.6,3.6,32.6Z"/>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="180" y="1420"/>
                                            </a>
                                        </g>

                                        <g id="C1_C2" class="single-event__halls-element half">
                                            <g id="C1" class="single-event__halls-element quarter">
                                                <g id="objekt_C1">
                                                    <polygon id="sufit_C1" class="st63" points="1251.1 903.3 1340.7 879.8 1156.9 720.7 1067 744.4 1251.1 903.3"/>
                                                    <polygon id="przod_C1" class="st37" points="1341.9 928.8 1252 955.1 1251.1 913.1 1342.6 886.1 1345.9 887.2 1343.9 927.8 1341.9 928.8"/>
                                                    <polygon id="sciana_bok_C1" class="st73" points="1251.1 903.3 1251.1 913.1 1252 955.1 1067 798.6 1067 744.4 1251.1 903.3"/>
                                                </g>
                                                <path id="kolor_C1" class="single-event__halls-element-color" d="M1340.7,879.8l-89.7,23.5v2.4c-.2,1.1-.2,2.3,0,3.4v4l75.7-21.6,12.5-3.6s2.2-1.1,4.2-.8,1.4,3,1.4,3.4-3.8,38.5-3.8,38.5l-88.9,26.1-185-156.5v-54.2l90-23.7,183.8,159.2h-.2Z"/>
                                                <path id="belka_C1" class="st0" d="M1251.1,903.3l90.6-24.1s6.2-1.2,8.6,2.4,1.7,10.8,1.7,10.8l-2.8,36.3-3.6,1.7-4.8-1.2,3.9-39.4s1.2-4.6-5.6-1.8c-7.2,2.1-88.1,25.2-88.1,25.2l-.2-5v-4.8h.3Z"/>
                                                <g id="WEJSCIE_C1">
                                                    <polygon class="st60" points="1252 955.1 1262.1 952 1268.5 937.2 1265.8 926.6 1256.7 923.5 1251.9 923.8 1251.3 925.4 1252 955.1"/>
                                                    <path class="st0" d="M1251.2,919.9s10.2-2.7,15.3,3.2,6,13.7.9,22.7c-1.6,2.4-2.9,4.5-3.6,5.3s-2.6,1.3-3.6.3-.6-2.2,0-3.8,4.9-7.4,5.1-12.6c-.3-7.5-4-11.3-14-9.6v-5.6h-.1Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1760" y="-255"/>
                                                </a>
                                                <g id="numer_hali_C" class="st9">
                                                    <g class="st9">
                                                        <g class="st9">
                                                            <text class="st8" transform="translate(1323.8 911.8)"><tspan x="-6" y="6">C</tspan></text>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>

                                            <g id="C2" class="single-event__halls-element quarter">
                                                <g id="struktura_C2">
                                                    <path id="przod_sciany_C2" class="st55" d="M1252,955.1l-5.8-17.6,5.1-17.6-.2-6.7-91,24.6s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,6.9,1.9,23.6,2.6,30.5.2,1.9.3,3.3.3,3.3l95.1-26.9h0Z"/>
                                                    <path id="sciany_C2" class="st51" d="M1151.8,983.8l-5.2-30.2c-.8-4.5-.4-9.2,1.2-13.5s3.1-5.9,6.1-6.7c7.2-1.8,97.1-30.1,97.1-30.1l-184-158.9-47.9,11.1-46.6,10.8s-3,.5-5.1,1.6c-1.3.7-2.6,1.8-3.1,2.8-1.7,2.2-1.3,7.1-1.2,10.9,0,8.7.5,41.4.5,41.4l188.1,160.7h0Z"/>
                                                </g>
                                                <g id="kolor_C2">
                                                    <path id="sciany_C21" class="single-event__halls-element-color" d="M1251.1,903.3l-184.1-158.9-94.6,21.8c-3.3.7-9.2,2.5-9.2,15.6.2,11.4.5,41.2.5,41.2l188.1,160.7-4.4-25.5c-.4-2.1-1.3-10.1-1-12.2.8-5.8,2.3-9,5.8-11.9.3,0,1.4-.5,3.6-1.2,18.8-5.5,95.2-29.6,95.2-29.6h0Z"/>
                                                    <path id="przod_C2" class="single-event__halls-element-color" d="M1153.7,948.3c0,1.8,3.2,33.8,3.2,33.8l95.1-26.9-5.8-17.6,5.1-17.6v-1.9l-.2-4.8s-86.8,23.4-91.7,24.8c-6.7,2.3-5.7,10.3-5.7,10.3h0Z"/>
                                                </g>
                                                <path id="belka_C2" class="st0" d="M1152.6,984.7c-1.5-1.5-2.9-2.5-3.7-3.4-.8-7.2-3.7-34-2-37.7.6-1.8,1.3-5.2,2.9-7.1s5.3-3.4,5.3-3.4l91.1-28.3c2.5-.8,5-1.5,5-1.5v9.9l-2.8.8-88.3,24c-.4,0-1.4.4-2.6,1.3-1.7,1.2-2.8,3.2-3.1,5.3,0,0-.4,2.4-.4,5.1l2.9,33c-2.4,1.1-4.3,2.1-4.3,2.1h0Z"/>
                                                <g id="Wejscie_C2">
                                                    <path class="st60" d="M1251.3,925.4l.6,29.7-9.1,2.6s-9.4-10.9-7-18,7.3-13.1,11.2-14.6,4.2.2,4.2.2h.1Z"/>
                                                    <path class="st0" d="M1243.4,957.6s-2.8,1.4-5.6-1.6-6.6-11-4-19c3.2-13,17.4-17.1,17.4-17.1v5.6c-1.5.5-8.1,4-11.2,9.7v.2c-3,5.6-2,12.4,2.1,17.3s2.9,3.2,1.4,4.9h-.1Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1740" y="-155"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="200" y="1740"/>
                                            </a>
                                        </g>

                                        <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link full">
                                            <image class="single-event__halls-element-logo" href="" width="350" height="200" x="1480" y="-250"/>
                                        </a>
                                    </g>

                                    <g id="wejscie_bok_c">
                                        <polygon class="st24" points="973.4 831.5 956.9 836.3 942.8 824.1 959.3 819.3 973.4 831.5"/>
                                        <polygon class="st60" points="973.4 831.5 959.1 819.2 959.1 788.4 973.3 800.3 973.4 831.5"/>
                                        <path class="st41" d="M959.9,834.2c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5Z"/>
                                        <path class="st56" d="M948.3,825.7c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5Z"/>
                                    </g>

                                    <g id="tunel_b_c">
                                        <path class="st24" d="M957.8,812.4l13.5-4.4c1.3-.4,2.2-1.8,2.2-3.4l-.2-13.4v-9l-15.4.9v29.3h0Z"/>
                                        <path class="st24" d="M962.7,811l-73.6,21.8-14.9-12.2c-2-1.8-2.1-1.8-3.5-3.2l-.2-.2v-7.9l92.2-27.7v29.4h0Z"/>
                                        <path class="st2" d="M956.3,771l-104.2,29.9c7.4,6.4,18.2,16.3,18.2,16.3l102.9-28.9v-4.2c0-1.2.2-4-1.8-5.2l-15.2-7.9h0Z"/>
                                    </g>

                                    <g id="B" class="single-event__halls-element full">
                                        <g id="B3_B4" class="single-event__halls-element half">
                                            <g id="B3" class="single-event__halls-element quarter">
                                                <g id="struktura_B3">
                                                    <g>
                                                    <path id="sufit_B3" class="st63" d="M860,807.7l-90,23.7-174.9-150.7,82.6-24s2.7-.6,4.2-.4,3,.5,3.7,1.1c1.1.9,174.4,150.3,174.4,150.3h0Z"/>
                                                    <polygon id="sciana_bok_B3" class="st14" points="770 885.8 597.1 733.3 595.1 680.8 770 831.4 770 885.8"/>
                                                    </g>
                                                    <polygon id="sciana_przod_B3" class="st58" points="860 807.7 857 864.6 770 885.8 770 831.4 860 807.7"/>
                                                </g>
                                                <path id="kolor_B3" class="single-event__halls-element-color" d="M685.6,657.5l174.4,150.3-3,56.9-87,21.1v-54.4,54.4l-173-152.4-1.9-52.6,82.5-23.9s2.5-.7,4.5-.5,2.2.4,3.4,1.1h0Z"/>
                                                <path id="belka_B3" class="st33" d="M595.1,680.8l82.6-24s5-1.4,7.9.7c-.2-3.6,0-12.1-13.4-7.9s-77,22.5-77,22.5c0,0-4,2.1,0,8.7h0Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1400" y="75"/>
                                                </a>
                                            </g>

                                            <g id="B4" class="single-event__halls-element quarter">
                                                <g id="Struktura_B4">
                                                    <path id="przod_sciany_B4" class="st58" d="M666.7,910.3l103.3-24.5v-54.4l-97.7,22.7s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,8.8.5,45.6.5,45.6h0Z"/>
                                                    <path id="sciany_B4" class="st47" d="M666.7,910.3l-.5-48.6s-.3-5.8,6.9-7.7c6.8-1.8,87.1-20.4,96.1-22.4s.8-.2.8-.2l-174.8-150.4-106.8,31.8s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.8h-.1Z"/>
                                                </g>
                                                <path id="kolor_B4" class="single-event__halls-element-color" d="M770,831.4l-174.9-150.6-106.7,31.9s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.9h0c0,0,103.2-24.6,103.2-24.6v-54.3h0ZM770,831.4s-19.3,4.5-40.8,9.4c21.4-5,40.7-9.5,40.7-9.4h0Z"/>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1380" y="175"/>
                                                </a>
                                                <path id="Belka_B4" class="st33" d="M477.6,750.9l5.3,4.5h4.4s-5.4-31.9-5.3-32.5-1.3-8.1,6.3-10.3,106.8-31.8,106.8-31.8c0,0,3.3-3.3,0-8.7-7.2,2-110.3,31.3-110.3,31.3,0,0-12.1,2.6-10.8,14.9s3.6,32.6,3.6,32.6Z"/>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="-150" y="1400"/>
                                            </a>
                                        </g>

                                        <g id="B1_B2" class="single-event__halls-element half">
                                            <g id="B1" class="single-event__halls-element quarter">
                                                <g id="objekt_B1">
                                                    <polygon id="sufit_B1" class="st63" points="954.1 990.4 1043.8 966.9 860 807.7 770 831.4 954.1 990.4"/>
                                                    <polygon id="przod_B1" class="st16" points="1044.9 1015.8 955 1042.2 954.1 1000.2 1045.6 973.2 1049 974.3 1047 1014.8 1044.9 1015.8"/>
                                                    <polygon id="sciana_bok_B1" class="st20" points="954.1 990.4 954.1 1000.2 955 1042.2 769.8 885.8 770 831.4 954.1 990.4"/>
                                                </g>
                                                <path id="kolor_B1" class="single-event__halls-element-color" d="M1043.8,966.9l-89.7,23.5v2.4c-.2,1.1-.2,2.3,0,3.4v4l75.7-21.6,12.5-3.6s2.2-1.1,4.2-.8,1.4,3,1.4,3.4-3.8,38.5-3.8,38.5l-88.9,26.1-185-156.5-.2-54.3,90.2-23.6,183.8,159.2h-.2Z"/>
                                                <path id="belka_B1" class="st33" d="M954.1,990.4l90.6-24.1s6.2-1.2,8.6,2.4,1.7,10.8,1.7,10.8l-2.8,36.3-3.6,1.7-4.8-1.2,3.9-39.4s1.2-4.6-5.6-1.8c-7.2,2.1-88.1,25.2-88.1,25.2l-.2-5v-4.8h.3Z"/>
                                                <g id="WEJSCIE_B1">
                                                    <polygon class="st60" points="955 1042.2 965.2 1039.1 971.5 1024.3 968.8 1013.7 959.7 1010.6 955 1010.9 954.4 1012.4 955 1042.2"/>
                                                    <path class="st33" d="M954.3,1006.9s10.2-2.7,15.3,3.2,6,13.7.9,22.7c-1.6,2.4-2.9,4.5-3.6,5.3s-2.6,1.3-3.6.3-.6-2.2,0-3.8,4.9-7.4,5.1-12.6c-.3-7.5-4-11.3-14-9.6v-5.6h-.1Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1720" y="75"/>
                                                </a>
                                                <g id="numer_hali_B" class="st9">
                                                    <g class="st9">
                                                        <g class="st9">
                                                            <text class="st8" transform="translate(1026.8 998.9)"><tspan x="-6" y="6">B</tspan></text>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>

                                            <g id="B2" class="single-event__halls-element quarter">
                                                <g id="struktura_B2">
                                                    <path id="przod_sciany_b2" class="st5" d="M955.1,1042.2l-5.9-17.6,5.1-17.6-.2-6.7-91,24.6s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,6.9,1.9,23.6,2.6,30.5.2,1.9.3,3.3.3,3.3l95.1-27h.1Z"/>
                                                    <path id="sciany_b2" class="st49" d="M854.8,1070.9l-5.2-30.2c-.8-4.5-.4-9.2,1.2-13.5s3.1-5.9,6.1-6.7c7.2-1.8,97.1-30.1,97.1-30.1l-184-159-5.7,1.3-37,8.6-36.5,8.5-17.9,4.2s-3.7.9-5.3,3.5c-2,2.7-1.6,7.4-1.5,11.2,0,8.7.5,41.4.5,41.4l188.1,160.7h.1Z"/>
                                                </g>
                                                <g id="kolor_B2">
                                                    <path id="sciany_B2" class="single-event__halls-element-color" d="M954.1,990.4l-184.1-159-94.6,21.9c-3.3.7-9.2,2.5-9.2,15.6.2,11.4.5,41.2.5,41.2l188.1,160.7-4.4-25.5c-.4-2.1-1.3-10.1-1-12.2.8-5.8,2.3-9,5.8-11.9.3,0,1.4-.5,3.6-1.2,18.8-5.5,95.2-29.6,95.2-29.6h0Z"/>
                                                    <path id="przod_B2" class="single-event__halls-element-color" d="M856.8,1035.3c0,1.8,3.2,33.8,3.2,33.8l95-26.9-5.7-17.6,5.1-17.6v-1.9l-.2-4.8s-86.8,23.4-91.7,24.8c-6.7,2.3-5.7,10.3-5.7,10.3h0Z"/>
                                                </g>
                                                <path id="belka_b2" class="st33" d="M855.6,1071.8c-1.5-1.5-2.9-2.5-3.7-3.4-.8-7.2-3.7-34-2-37.7.6-1.8,1.3-5.2,2.9-7.1,2.2-2.6,5.3-3.4,5.3-3.4l91.1-28.3c2.5-.8,5-1.5,5-1.5v9.9l-2.8.8-88.3,24c-.4,0-1.4.4-2.6,1.3-1.7,1.2-2.8,3.2-3.1,5.3,0,0-.4,2.4-.4,5.1l2.9,33c-2.4,1.1-4.3,2.1-4.3,2.1h0Z"/>
                                                <g id="Wejscie_B2">
                                                    <path class="st60" d="M954.4,1012.4l.7,29.8-9.2,2.6s-9.4-10.9-7-18,7.3-13.1,11.2-14.6,4.2.2,4.2.2h0Z"/>
                                                    <path class="st33" d="M946.5,1044.6s-2.8,1.4-5.6-1.6-6.6-11-4-19c3.2-13,17.5-17.1,17.5-17.1v5.5c-1.5.5-8.2,4.1-11.3,9.8v.2c-3,5.6-2,12.4,2.1,17.3s2.9,3.2,1.4,4.9h-.1Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1710" y="175"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="-124" y="1730"/>
                                            </a>
                                        </g>

                                        <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link full">
                                            <image class="single-event__halls-element-logo" href="" width="350" height="200" x="1430" y="80"/>
                                        </a>
                                    </g>

                                    <g id="wejscie_bok_b">
                                        <polygon class="st24" points="678.7 920.3 662.2 925.1 648.1 912.9 664.6 908.1 678.7 920.3"/>
                                        <polygon class="st60" points="678.7 920.3 664.4 908 664.3 877.2 678.6 889.1 678.7 920.3"/>
                                        <path class="st15" d="M665.2,923c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5Z"/>
                                        <path class="st53" d="M653.6,914.5c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5Z"/>
                                    </g>

                                    <g id="tunel_a_b">
                                        <path class="st24" d="M663,896.2l13.5-4.4c1.3-.4,2.2-1.8,2.2-3.4l-.2-13.4v-9l-15.4.9v29.3h0Z"/>
                                        <path class="st24" d="M667.9,894.8l-74.2,22-14.1-12.2-4-3.6v-7.9l92.2-27.7v29.4h0Z"/>
                                        <path class="st38" d="M661.5,854.9l-104.7,30.1c7.4,6.4,21.1,18.2,21.1,18.2l100.7-31v-4.2c0-1.2.2-4-1.8-5.2l-15.2-7.9h0Z"/>
                                    </g>

                                    <g id="A" class="single-event__halls-element full">
                                        <g id="A3_A4" class="single-event__halls-element half">
                                            <g id="A3" class="single-event__halls-element quarter">
                                                <g id="struktura_A3">
                                                    <g id="bok_i_sufit_A3">
                                                        <path id="sufit_A3" class="st63" d="M566.8,893.6l-89.9,23.6-175-150.6,82.6-24s2.7-.6,4.2-.4,3,.5,3.7,1.1c1.1.9,174.4,150.3,174.4,150.3h0Z"/>
                                                        <polygon id="sciana_bok_A3" class="st3" points="476.9 971.6 303.9 819.2 302 766.6 476.9 917.2 476.9 971.6"/>
                                                    </g>
                                                    <polygon id="sciana_przod_A3" class="st58" points="566.8 893.6 563.9 950.5 476.9 971.6 476.9 917.2 566.8 893.6"/>
                                                </g>
                                                <path id="kolor_A3" class="single-event__halls-element-color" d="M392.4,743.3l174.4,150.3-3,56.9-87,21.1v-54.4,54.4l-173-152.4-1.9-52.6,82.5-23.9s2.5-.7,4.5-.5,2.2.4,3.4,1.1h.1Z"/>
                                                <path id="belka_A3" class="st65" d="M302,766.6l82.6-24s5-1.4,7.9.7c-.2-3.6,0-12.1-13.4-7.9s-77,22.5-77,22.5c0,0-4,2.1,0,8.7h-.1Z"/>
                                            </g>

                                            <g id="A4" class="single-event__halls-element quarter">
                                                <g id="Struktura_A4">
                                                    <path id="przod_sciany_A4" class="st58" d="M373.6,996.1l103.3-24.5v-54.4l-97.7,22.7s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,8.8.5,45.6.5,45.6h0Z"/>
                                                    <path id="sciany_A4" class="st61" d="M373.6,996.1l-.5-48.6s-.3-5.9,6.9-7.7,96.9-22.6,96.9-22.6l-174.8-150.4-106.8,31.8s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.8h-.1,0Z"/>
                                                </g>
                                                <path id="kolor_A31" class="single-event__halls-element-color" d="M477,917.2s-92.7,21.4-97.8,22.6c-1.9.6-3.2,1.5-4.1,2.5,1.4-1.6,3-2.4,5.4-2.9,4.9-1,96-22.1,96-22.1h.4l-174.9-150.7-106.7,31.9s-8.3.9-6,12,5,30.7,5,30.7l179.4,154.9h0c0,0,103.3-24.5,103.3-24.5v-54.4h0Z"/>
                                                <path id="Belka_A4" class="st65" d="M184.5,836.7l5.3,4.5h4.4s-5.4-31.9-5.3-32.5-1.3-8.1,6.3-10.3,106.8-31.8,106.8-31.8c0,0,3.3-3.3,0-8.7-7.2,2-110.3,31.3-110.3,31.3,0,0-12.1,2.6-10.8,14.9s3.6,32.6,3.6,32.6Z"/>
                                            </g>
                                        </g>

                                        <g id="A1_A2" class="single-event__halls-element half">
                                            <g id="A1" class="single-event__halls-element quarter">
                                                <g id="objekt_A1">
                                                    <polygon id="sufit_A1" class="st63" points="661 1076.2 750.6 1052.7 566.8 893.6 476.9 917.2 661 1076.2"/>
                                                    <polygon id="przod_A1" class="st68" points="751.7 1101.7 661.8 1128.1 661 1086.1 752.4 1059.1 755.7 1060.2 753.7 1100.7 751.7 1101.7"/>
                                                    <polygon id="sciana_bok_A1" class="st70" points="661 1076.2 661 1086.1 661.9 1128.1 476.9 971.5 476.9 917.2 661 1076.2"/>
                                                </g>
                                                <path id="kolor_A1" class="single-event__halls-element-color" d="M750.6,1052.7l-89.6,23.5v2.4c-.3,1.1-.3,2.3-.2,3.4v4l75.7-21.6,12.5-3.6s2.2-1.1,4.2-.8,1.4,3,1.4,3.4-3.8,38.5-3.8,38.5l-88.9,26.1-185-156.5v-54.3l90-23.6,183.8,159.2h-.1Z"/>
                                                <path id="belka_A1" class="st65" d="M661,1076.2l90.6-24.1s6.2-1.2,8.6,2.4,1.7,10.8,1.7,10.8l-2.8,36.3-3.6,1.7-4.8-1.2,3.9-39.4s1.2-4.6-5.6-1.8c-7.2,2.1-88,25.2-88,25.2l-.3-5v-4.8h.3,0Z"/>
                                                <g id="WEJSCIE_A1">
                                                    <polygon class="st60" points="661.9 1128.1 671.9 1125.1 678.4 1110.2 675.7 1099.5 666.6 1096.4 661.8 1096.7 661.2 1098.2 661.9 1128.1"/>
                                                    <path class="st65" d="M661.4,1092.7s9.9-2.6,15,3.3,6,13.7.9,22.7c-1.6,2.4-2.9,4.5-3.6,5.3s-2.6,1.3-3.6.3-.6-2.2,0-3.8,4.9-7.4,5.1-12.6c-.3-7.5-4-11.3-14-9.6v-5.5h.2,0Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1680" y="400"/>
                                                </a>
                                                <g id="numer_hali_A" class="st9">
                                                    <g class="st9">
                                                        <g class="st9">
                                                            <text class="st8" transform="translate(733.7 1084.8)"><tspan x="-6" y="6">A</tspan></text>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>

                                            <g id="A2" class="single-event__halls-element quarter">
                                                <g id="struktura_A2">
                                                    <path id="przod_sciany_A2" class="st23" d="M661.9,1128.1l-5.8-17.6,5-17.7v-6.7l-91.1,24.7s-3.1.7-5,3.6c-1.4,2.4-1.1,3.9-1.1,6.9,0,6.9,1.9,23.6,2.6,30.5.2,1.9.3,3.3.3,3.3l94.8-26.9h.3Z"/>
                                                    <path id="sciany_A2" class="st11" d="M561.7,1156.7l-5.2-30.2c-.8-4.5-.4-9.2,1.2-13.5s3.1-5.9,6.1-6.7c7.2-1.8,97.2-30.1,97.2-30.1l-184.1-159-94.7,22s-2,.3-3.9,1.1-2.9,1.4-3.5,2.4c-.4.5-.7.9-1,1.6s-.5,1.3-.7,2c-.5,2.6-.3,5.8-.2,8.2,0,8.7.5,41.4.5,41.4l188.1,160.7h.2Z"/>
                                                </g>
                                                <g id="kolor_A2">
                                                    <path id="przod_A2" class="single-event__halls-element-color" d="M563.6,1121.2c0,1.8,3.2,33.8,3.2,33.8l95.1-26.9-5.8-17.6,5.1-17.6v-1.9l-.2-4.8s-86.8,23.4-91.7,24.8c-6.7,2.3-5.7,10.3-5.7,10.3h0Z"/>
                                                    <path id="SUFIT_A2" class="single-event__halls-element-color" d="M661,1076.2l-96,29.8s-5.1,1.8-6.6,5.7-2.1,6.9-2.2,8.1.6,12.7.6,12.7l2.1,21.7-185.3-158.3-.4-48.8s.3-2.5,1.1-3.5,1.9-2.6,3.7-3.2c1.8-.6,5.6-1.5,5.6-1.5l15.1-3.5,78.4-18.1,184.1,159h-.2Z"/>
                                                </g>
                                                <path id="belka_A2" class="st65" d="M562.5,1157.6c-1.5-1.5-2.9-2.5-3.7-3.4-.8-7.2-3.7-34-2-37.7.6-1.8,1.3-5.2,2.9-7.1s5.3-3.4,5.3-3.4l91.1-28.3c2.5-.8,4.9-1.5,4.9-1.5v9.9l-2.7.8-88.3,24c-.4,0-1.4.4-2.6,1.3-1.7,1.2-2.8,3.2-3.1,5.3,0,0-.4,2.4-.4,5.1l2.9,33c-2.4,1.1-4.3,2.1-4.3,2.1h0Z"/>
                                                <g id="Wejscie_A2">
                                                    <path class="st60" d="M661.2,1098.2l.7,29.9-9.2,2.6s-9.4-10.9-7-18,7.3-13.1,11.2-14.6,4.2.2,4.2.2h0Z"/>
                                                    <path class="st65" d="M653.3,1130.5s-2.8,1.4-5.6-1.6-6.6-11-4-19c3.2-13,17.5-17.1,17.5-17.1v5.5c-1.5.5-8.2,4.1-11.3,9.8v.2c-3,5.6-2,12.4,2.1,17.3s2.9,3.2,1.4,4.9h-.1Z"/>
                                                </g>
                                                <a target="_blank" class="single-event__halls-link single-event__halls-element-logo-link quarter">
                                                    <image class="single-event__halls-element-logo" href="" width="175" height="100" x="1670" y="500"/>
                                                </a>
                                            </g>

                                            <a target="_blank" class="single-event__halls-link single-event__halls-element-favicon-link half">
                                                <image class="single-event__halls-element-favicon" href="" width="140" height="140" x="-450" y="1700"/>
                                            </a>
                                        </g>

                                    </g>

                                    <g id="wejscie_bok_A">
                                        <polygon class="st24" points="382.8 1004.5 366.3 1009.3 352.2 997.1 368.7 992.3 382.8 1004.5"/>
                                        <polygon class="st60" points="382.8 1004.5 368.5 992.2 368.5 961.4 382.7 973.3 382.8 1004.5"/>
                                        <path class="st29" d="M369.3,1007.2c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5h0Z"/>
                                        <path class="st66" d="M357.7,998.7c-.6,0-1.7-.2-1.7-1.5v-37.5h3.9v37.5c0,1.3-1.4,1.6-2.2,1.5h0Z"/>
                                    </g>

                                    <g id="tunel_A">
                                        <path class="st24" d="M363.1,982.4l4.1-1.1,13.6-3.8c1.1-.3,1.8-1.3,1.8-2.4v-18.4l-18.3-1.7v23.5c0,2.8-1.3,3.9-1.3,3.9h0Z"/>
                                        <path class="st71" d="M361.8,982.1l-10-8.3c-.9-.8-1.5-1.9-1.5-3.1v-20.1c.3-3,4.2-2.8,5.1-3.7l7,6.4c1.2,1.1,2,2.7,2,4.3v23.3c0,1.3-1.6,2.1-2.6,1.2h0Z"/>
                                        <path class="st19" d="M369.2,944.9l-11.5,1.2s-3.4.6-4,.8c-4,1.4-3.2,4.7-3.2,4.7,0,0,0-4,1.9-2.3.9.5,2.4,1.5,2.4,1.5l8.4,5.3c1.8,2,1.4,7,1.5,7.7v.3c0,1.8,1.8,3,3.5,2.5l14.6-5.7v-6.1c0-1,0-3.2-.9-3.7l-12.6-6.1h0Z"/>
                                    </g>

                                </svg>
                            </div>

                            <script>
                                const allItems = '. json_encode($json_data_all) .';

                                const allActiveItemsObject = [];

                                /* =========================
                                FULL
                                ========================= */
                                const addActiveClassToFullObject = () => {
                                    allItems.forEach(item => {
                                        if (!/^[A-Z]$/.test(item.id)) return;

                                        const fullObject = document.getElementById(item.id);
                                        if (!fullObject) return;

                                        // Full can always be active
                                        fullObject.classList.add("active");

                                        // Color
                                        const colors = fullObject.querySelectorAll(".single-event__halls-element-color");
                                        colors.forEach(el => {
                                            el.style.fill = item.color;
                                        });

                                        // Logo
                                        const logoLinks = fullObject.querySelectorAll(".single-event__halls-element-logo-link.full");
                                        logoLinks.forEach(link => {
                                            const logo = link.querySelector(".single-event__halls-element-logo");
                                            if (!logo) return;
                                            link.setAttribute("href", `https://${item.domain}`);
                                            logo.setAttribute("href", `https://${item.domain}/doc/logo.webp`);
                                        });

                                        allActiveItemsObject.push({ id: fullObject.id });
                                    });
                                };

                                /* =========================
                                HALF
                                ========================= */
                                const addActiveClassToHalfObject = () => {
                                    const halfItems = allItems.filter(item => /^[A-Z]\d$/.test(item.id));

                                    halfItems.forEach((item1, index) => {
                                        halfItems.slice(index + 1).forEach(item2 => {
                                            const combinedIds = [`${item1.id}_${item2.id}`, `${item2.id}_${item1.id}`];

                                            combinedIds.forEach(id => {
                                                const halfElement = document.getElementById(id);
                                                if (!halfElement) return;

                                                // Do not add active children if half is active
                                                halfElement.classList.add("active");

                                                const colors = halfElement.querySelectorAll(".single-event__halls-element-color");
                                                colors.forEach(el => {
                                                    el.style.fill = item1.color; // kolor bierzemy z pierwszego elementu
                                                });

                                                // Logo half
                                                const logoLinks = halfElement.querySelectorAll(".single-event__halls-element-favicon-link.half");
                                                logoLinks.forEach(link => {
                                                    const logo = link.querySelector(".single-event__halls-element-favicon");
                                                    if (!logo) return;
                                                    link.setAttribute("href", `https://${item1.domain}`);
                                                    logo.setAttribute("href", `https://${item1.domain}/doc/favicon.webp`);
                                                });

                                                allActiveItemsObject.push({ id: halfElement.id });
                                            });
                                        });
                                    });
                                };

                                /* =========================
                                QUARTER
                                ========================= */
                                const addActiveClassToQuarterObject = () => {
                                    allItems.forEach(item => {
                                        if (!/^[A-Z]\d$/.test(item.id)) return;

                                        const quarter = document.getElementById(item.id);
                                        if (!quarter) return;

                                        // If parent half or full is active, do not overwrite
                                        const parentHalfOrFull = quarter.closest(".single-event__halls-element.half, .single-event__halls-element.full");
                                        if (parentHalfOrFull && parentHalfOrFull.classList.contains("active")) return;

                                        quarter.classList.add("active");

                                        const colors = quarter.querySelectorAll(".single-event__halls-element-color");
                                        colors.forEach(el => {
                                            el.style.fill = item.color;
                                        });

                                        const logoLinks = quarter.querySelectorAll(".single-event__halls-element-logo-link.quarter");
                                        logoLinks.forEach(link => {
                                            const logo = link.querySelector(".single-event__halls-element-logo");
                                            if (!logo) return;
                                            link.setAttribute("href", `https://${item.domain}`);
                                            logo.setAttribute("href", `https://${item.domain}/doc/logo.webp`);
                                        });

                                        allActiveItemsObject.push({ id: quarter.id });
                                    });
                                };

                                /* =========================
                                INIT
                                ========================= */
                                window.addEventListener("load", () => {
                                    addActiveClassToFullObject();
                                    addActiveClassToHalfObject();
                                    addActiveClassToQuarterObject();
                                });

                            </script>';  
                        }

                        $output .= '
                        <div id="singleEventFairs" class="single-event__fairs">';

                            if (count($all_events_json) == 1) {
                                $events_word_declination = "wydarzenie";
                            } else if (count($all_events_json) > 1 && count($all_events_json) < 5) {
                                $events_word_declination = "wydarzenia";
                            } else {
                                $events_word_declination = "wydarzeń";
                            }

                            $output .= '
                            <div class="single-event__fairs-wrapper">
                                <div class="single-event__fairs-title">
                                    <h3>'. get_the_title() .'</h3>
                                    <div class="single-event__fairs-counts">
                                        <img src="/wp-content/plugins/PWElements/includes/calendar/assets/calendar-icon.png"><span>'. $days .' '. ($lang_pl ? "dni" : "days") .'</span>
                                        <img src="/wp-content/plugins/PWElements/includes/calendar/assets/right-arrow-icon.png"><span>'. count($all_events_json) .' '. ($lang_pl ? $events_word_declination : "events") .'</span>
                                    </div>
                                </div>
                                <div class="single-event__fairs-items">';

                                    foreach ($all_events_json as $event) {

                                        $translates = PWECommonFunctions::get_database_translations_data($event['domain']);

                                        // [pwe_name_{lang}]
                                        $shortcode_name = get_pwe_shortcode("pwe_name_$lang", $event['domain']);
                                        $shortcode_name_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_name);
                                        $fair_name = $shortcode_name_available ? get_translated_field($translates[0], 'fair_name') : '';

                                        // [pwe_date_start_{lang}]
                                        $shortcode_date_start = get_pwe_shortcode("pwe_date_start", $event['domain']);
                                        $shortcode_date_start_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_name);
                                        $fair_date_start = $shortcode_name_available ? $shortcode_date_start : '';

                                        $fair_year = substr($fair_date_start, 0, 4);

                                        $shortcode_full_desc = get_pwe_shortcode("pwe_full_desc_$lang", $event['domain']);
                                        $shortcode_full_desc_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_full_desc);
                                        $fair_full_desc = $shortcode_full_desc_available ? get_translated_field($translates[0], 'fair_full_desc') : '';

                                        if (!empty($fair_full_desc)) {
                                            $full_description = strstr($fair_full_desc, '<br>', true);

                                            // If strstr returned false (i.e. no <br>), we assign the entire content
                                            if ($full_description === false) {
                                                $full_description = $fair_full_desc;
                                            }
                                        }

                                        // [pwe_edition]
                                        $shortcode_edition = get_pwe_shortcode("pwe_edition", $event['domain']);
                                        $shortcode_edition_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_edition);

                                        if ($shortcode_edition_available) {
                                            if($shortcode_edition == '1'){
                                                $edition = multi_translation("premier_edition");
                                            } else {
                                                $edition = $shortcode_edition . multi_translation("edition");
                                            }
                                        }

                                        $cap_logotypes_data = PWECommonFunctions::get_database_logotypes_data($event['domain']);
                                        if (!empty($cap_logotypes_data)) {

                                            $saving_paths = function (&$files, $logo_data) {
                                                $link = $logo_data->logos_link;
                                                $element = [
                                                    'url' => 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url,
                                                    'link' => $link
                                                ];

                                                // Adding logos_url to $files only if it is not already there
                                                if (!in_array($element, $files)) {
                                                    $files[] = $element;
                                                }
                                            };


                                            $files = [];

                                            foreach ($cap_logotypes_data as $logo_data) {
                                                if ($logo_data->logos_type === "partner-targow" ||
                                                    $logo_data->logos_type === "patron-medialny" ||
                                                    $logo_data->logos_type === "partner-strategiczny" ||
                                                    $logo_data->logos_type === "partner-honorowy" ||
                                                    $logo_data->logos_type === "principal-partner" ||
                                                    $logo_data->logos_type === "industry-media-partner" ||
                                                    $logo_data->logos_type === "partner-branzowy" ||
                                                    $logo_data->logos_type === "partner-merytoryczny") {
                                                    $saving_paths($files, $logo_data);
                                                }
                                            }

                                        }

                                        $output .= '
                                        <div class="single-event__fairs-item">
                                            <div class="single-event__fairs-item-wrapper">
                                                <div class="single-event__fairs-item-image">
                                                    <div class="single-event__fairs-item-title">
                                                        <h4>'. $fair_name .' '. $fair_year .'</h4>
                                                        <p>'. $edition .'</p>
                                                        <div class="single-event__fairs-item-buttons">
                                                            <div class="single-event__fairs-item-button more">
                                                                <a href="https://'. $event['domain'] .''. ($lang_pl ? "/" : "/en/") .'" target="_blank">'. ($lang_pl ? "Dowiedz się więcej" : "Learn more") .'</a>
                                                            </div>
                                                            <div class="single-event__fairs-item-button register">
                                                                <a href="https://'. $event['domain'] .''. ($lang_pl ? "/rejestracja/" : "/en/registration/") .'">'. ($lang_pl ? "Weź udział" : "Take part") .'</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="single-event__fairs-item-bg" style="background-image: url(https://'. $event['domain'] .'/doc/background.webp)">
                                                        <img src="https://'. $event['domain'] .'/doc/logo.webp">
                                                    </div>
                                                </div>
                                                <div class="single-event__fairs-item-info">
                                                    <div class="single-event__fairs-item-title">
                                                        <h4>'. $fair_name .' '. $fair_year .'</h4>
                                                        <p>'. $edition .'</p>
                                                    </div>
                                                    <div class="single-event__fairs-item-desc">
                                                        <p>'. $full_description .'</p>
                                                    </div>
                                                    <div class="single-event__fairs-item-buttons">
                                                        <div class="single-event__fairs-item-button more">
                                                            <a href="https://'. $event['domain'] .''. ($lang_pl ? "/" : "/en/") .'" target="_blank">'. ($lang_pl ? "Dowiedz się więcej" : "Learn more") .'</a>
                                                        </div>
                                                        <div class="single-event__fairs-item-button register">
                                                            <a href="https://'. $event['domain'] .''. ($lang_pl ? "/rejestracja/" : "/en/registration/") .'">'. ($lang_pl ? "Weź udział" : "Take part") .'</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="single-event__fairs-item-slider">';

                                                foreach ($files as $logo) {
                                                    $output .= '<div class="single-event__fairs-item-logo"><img src="'. $logo['url'] .'"></div>';
                                                }

                                            $output .= '
                                            </div>

                                        </div>';
                                    }

                                $output .= '
                                </div>
                            </div>
                        </div>

                        <div id="singleEventConferences" class="single-event__conferences">';

                            $atts = [];
                            $domains = [];

                            foreach ($all_events_json as $event) {
                                $domains[] = $event['domain'];
                            }

                            $domains_string = implode(', ', $domains);

                            $atts['conference_cap_domains'] = $domains_string;

                            // if (strpos($_SERVER['HTTP_HOST'], 'warsawexpo.eu') !== false || strpos($_SERVER['HTTP_HOST'], 'rfl.warsawexpo.eu') !== false) {
                            //     require_once plugin_dir_path(dirname( __DIR__ )) . 'conference-cap/classes/conference-cap-warsawexpo.php';
                            //     $output .= PWEConferenceCapWarsawExpo::output($atts, $lang);
                            // }

                            $output .= '
                        </div>

                    </div>

                    <div id="singleEventExhibitors" class="single-event__exhibitors">';

                        $catalog_ids = '';

                        foreach ($all_events_json as $event) {
                            $domain = $event['domain'];
                            $catalog_id = do_shortcode('[pwe_catalog_id domain="' . $domain . '"]');

                            if ($catalog_ids !== '') {
                                $catalog_ids .= ',';
                            }
                            $catalog_ids .= (string)$catalog_id;
                        }

                        $catalog_ids = implode(',', array_unique(array_map('trim', explode(',', $catalog_ids))));

                        if (!empty($catalog_ids)) {
                            $output .= '
                            <style>
                                .exhibitor-catalog__header {
                                    display: none !important;
                                }
                                .exhibitor-catalog__main-columns {
                                    height: auto !important;
                                }
                            </style>';

                            $output .= '
                            <div id="singleEventCatalogHeader" class="single-event__catalog-header">
                                <div class="single-event__catalog-header-wrapper">
                                    <h3>'. ($lang_pl ? "Katalog wystawców" : "Exhibitor catalog") .'</h3>
                                    <h4>'. get_the_title() .'</h4>
                                </div>

                                <div id="hideAllExhibitors" class="single-event__exhibitors-hideall">
                                    <button>'. ($lang_pl ? "Zamknij katalog" : "Close catalog") .'</button>
                                </div>
                            </div>';

                            $output .= '
                            <div class="single-event__exhibitors-fairs">';

                                $domains_with_logos = [];
                                foreach ($merge_exhibitors as $exhibitor) {
                                    if (!empty($exhibitor['exhibitor']['URL_logo_wystawcy'])) {
                                        $domains_with_logos[$exhibitor['domain']] = true;
                                    }
                                }

                                $output .= '
                                <div class="single-event__exhibitors-fairs-button-container">
                                    <div id="showAllExhibitors" class="single-event__exhibitors-fairs-button single-event__exhibitors-showall active" data-domain="all" style="background: linear-gradient(to right, '. $color_1 .', '. $color_2 .');">';
                                        foreach ($all_events_json as $event) {
                                            if (!isset($domains_with_logos[$event['domain']])) continue;
                                            $output .= '<img src="https://'. $event['domain'] .'/doc/logo.webp" alt="' . $event['domain'] . '">';
                                        }
                                        $output .= '
                                    </div>
                                    <p>'. ($lang_pl ? "Wszystkie wydarzenia" : "All events") .'</p>
                                </div>';

                                foreach ($all_events_json as $event) {
                                    if (!isset($domains_with_logos[$event['domain']])) continue;

                                    $output .= '
                                    <div class="single-event__exhibitors-fairs-button-container">
                                        <div class="single-event__exhibitors-fairs-button" data-domain="' . $event['domain'] . '" style="background-image: url(https://'. $event['domain'] .'/doc/background.webp)">
                                            <a href="https://'. $event['domain'] .''. ($lang_pl ? "/katalog-wystawcow/" : "/en/exhibitors-catalog/") .'" target="_blank">
                                                <img src="https://'. $event['domain'] .'/doc/logo.webp" alt="' . $event['domain'] . '">
                                            </a>
                                        </div>
                                        <p>'. $event['desc'] .'</p>
                                    </div>';
                                }

                                $output .= '
                            </div>';

                            $output .= do_shortcode('[pwe-elements-auto-switch-page-catalog archive_catalog_id="'. $catalog_ids .'"]');
                        } else {
                            $output .= '
                            <div id="singleEventCatalogHeader" class="single-event__catalog-header">
                                <div class="single-event__catalog-header-wrapper">
                                    <h3>'. ($lang_pl ? "Katalog wystawców" : "Exhibitor catalog") .'</h3>
                                    <h4>'. get_the_title() .'</h4>
                                    <input id="searchInput" placeholder="'. ($lang_pl ? "Wyszukaj wystawców" : "Search for exhibitors") .'"/>
                                </div>

                                <div id="hideAllExhibitors" class="single-event__exhibitors-hideall">
                                    <button>'. ($lang_pl ? "Zamknij katalog" : "Close catalog") .'</button>
                                </div>
                            </div>

                            <div class="single-event__exhibitors-fairs">';

                                $domains_with_logos = [];
                                foreach ($merge_exhibitors as $exhibitor) {
                                    if (!empty($exhibitor['exhibitor']['URL_logo_wystawcy'])) {
                                        $domains_with_logos[$exhibitor['domain']] = true;
                                    }
                                }

                                $output .= '
                                <div class="single-event__exhibitors-fairs-button-container">
                                    <div id="showAllExhibitors" class="single-event__exhibitors-fairs-button interactive single-event__exhibitors-showall active" data-domain="all" style="background: linear-gradient(to right, '. $color_1 .', '. $color_2 .');">';
                                        foreach ($all_events_json as $event) {
                                            if (!isset($domains_with_logos[$event['domain']])) continue;
                                            $output .= '<img src="https://'. $event['domain'] .'/doc/logo.webp" alt="' . $event['domain'] . '">';
                                        }
                                        $output .= '
                                    </div>
                                    <p>'. ($lang_pl ? "Wszystkie wydarzenia" : "All events") .'</p>
                                </div>';

                                foreach ($all_events_json as $event) {
                                    if (!isset($domains_with_logos[$event['domain']])) continue;

                                    $output .= '
                                    <div class="single-event__exhibitors-fairs-button-container">
                                        <div class="single-event__exhibitors-fairs-button interactive" data-domain="' . $event['domain'] . '" style="background-image: url(https://'. $event['domain'] .'/doc/background.webp)">
                                            <img src="https://'. $event['domain'] .'/doc/logo.webp" alt="' . $event['domain'] . '">
                                        </div>
                                        <p>'. $event['desc'] .'</p>
                                    </div>';
                                }

                                $output .= '
                            </div>

                            <div class="single-event__exhibitors-catalog">';

                                $already_shown_logos = [];
                                foreach ($merge_exhibitors as $exhibitor) {
                                    $logo_url = $exhibitor['exhibitor']['URL_logo_wystawcy'];
                                    if (empty($logo_url)) continue;

                                    $is_duplicate = isset($already_shown_logos[$logo_url]);
                                    if (!$is_duplicate) $already_shown_logos[$logo_url] = true;

                                    $output .= '
                                    <div class="single-event__exhibitors-card' . ($is_duplicate ? ' is-duplicate' : ' is-main') . '"
                                        data-domain="' . htmlspecialchars($exhibitor['domain']) . '"
                                        data-logo="' . htmlspecialchars($logo_url) . '"
                                        data-name="' . htmlspecialchars($exhibitor['exhibitor']['Nazwa_wystawcy']) . '"
                                        data-booth="' . htmlspecialchars($exhibitor['exhibitor']['Numer_stoiska']) . '"
                                        data-website="' . htmlspecialchars($exhibitor['exhibitor']['www']) . '"
                                        style="display:' . ($is_duplicate ? 'none' : 'block') . ';">
                                        <img src="' . htmlspecialchars($logo_url) . '" alt="' . htmlspecialchars($exhibitor['exhibitor']['Nazwa_wystawcy']) . '">
                                        <p>' . htmlspecialchars($exhibitor['exhibitor']['Numer_stoiska']) . '</p>
                                    </div>';
                                }

                                $output .= '
                            </div>

                            <!-- Modal -->
                            <div id="exhibitor-modal" class="single-event__exhibitor-modal">
                                <div class="single-event__exhibitor-modal-content">
                                    <span class="single-event__exhibitor-modal-close-btn">&times;</span>
                                    <div class="single-event__exhibitor-modal-logo-container">
                                        <img class="single-event__exhibitor-modal-logo" src="" alt="Logo exhibitor" />
                                    </div>
                                    <h4 class="single-event__exhibitor-modal-name"></h4>
                                    <p class="single-event__exhibitor-modal-stand"></p>
                                    <a class="single-event__exhibitor-modal-website" href="#" target="_blank">'. ($lang_pl ? "Odwiedź stronę" : "Visit the website") .'</a>
                                </div>
                            </div>';
                        }

                        $output .= '
                    </div>

                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {

                // Exhibitors logotypes <-----------------------------------------------------------------<
                const tileCount = 19;
                const allLogos = '.json_encode($remaining_logos).';

                // Table of tiles and their current logos
                const tiles = document.querySelectorAll(".single-event__catalog-logo-tile");
                let displayedLogos = []; // logo wyświetlane na kafelkach
                let availableLogos = [...allLogos]; // logo do podmiany

                // Choose 19 unique logos to start with
                function pickUniqueLogos(source, n) {
                    const copy = [...source];
                    const picked = [];
                    for(let i=0; i<n && copy.length; i++) {
                        const idx = Math.floor(Math.random() * copy.length);
                        picked.push(copy.splice(idx, 1)[0]);
                    }
                    return picked;
                }

                // Jeżeli nie ma logotypów → wychodzimy i nic nie robimy
                if (allLogos.length === 0) {
                    console.warn("Brak logotypów do wyświetlenia.");
                } else {
                    displayedLogos = pickUniqueLogos(availableLogos, tileCount);
                    // Remove the displayed logo from the available ones
                    displayedLogos.forEach(logo => {
                        const idx = availableLogos.findIndex(l => l.URL_logo_wystawcy === logo.URL_logo_wystawcy);
                        if(idx !== -1) availableLogos.splice(idx, 1);
                    });

                    // Insert the logo onto the tiles
                    tiles.forEach((tile, idx) => {
                        let logo = displayedLogos[idx];
                        tile.querySelector(".single-event__catalog-flip-card-front").innerHTML = `<img src="${logo.URL_logo_wystawcy}" alt="${logo.Nazwa_wystawcy}">`;
                        // Losujemy pierwsze back z puli (mogą być już wyczerpane – wtedy znowu miksujemy całość)
                        let backLogo = pickUniqueLogos(availableLogos, 1)[0] || allLogos[Math.floor(Math.random() * allLogos.length)];
                        tile.querySelector(".single-event__catalog-flip-card-back").innerHTML = `<img src="${backLogo.URL_logo_wystawcy}" alt="${backLogo.Nazwa_wystawcy}">`;
                    });

                    let isFlipping = false;
                    let flipState = Array(tiles.length).fill(false); // zapamiętuje stan kafelków

                    function flipRandomTiles() {
                        if(isFlipping) return;
                        isFlipping = true;

                        // Get the indexes of all tiles
                        const tileIndices = Array.from({length: tiles.length}, (_, i) => i);

                        // Randomly select a unique tile index
                        const shuffled = tileIndices.sort(() => 0.5 - Math.random());
                        const selected = shuffled.slice(0, 1);

                        selected.forEach(tileIdx => {
                            const tile = tiles[tileIdx];
                            const frontDiv = tile.querySelector(".single-event__catalog-flip-card-front");
                            const backDiv = tile.querySelector(".single-event__catalog-flip-card-back");

                            // Randomize new logo on back (unique)
                            let unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                            if(unused.length === 0) {
                                availableLogos.push(displayedLogos[tileIdx]);
                                unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                            }
                            let newBackLogo = pickUniqueLogos(unused, 1)[0] || displayedLogos[tileIdx];

                            // Change the logo on the "other side"
                            if (!flipState[tileIdx]) {
                                backDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                                tile.classList.add("flipped");
                            } else {
                                frontDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                                tile.classList.remove("flipped");
                            }
                            flipState[tileIdx] = !flipState[tileIdx];

                            // After the animation is finished, replace the logo from the pool (asynchronously)
                            setTimeout(() => {
                                availableLogos.push(displayedLogos[tileIdx]);
                                displayedLogos[tileIdx] = newBackLogo;
                                const idxToRemove = availableLogos.findIndex(l => l.URL_logo_wystawcy === newBackLogo.URL_logo_wystawcy);
                                if(idxToRemove !== -1) availableLogos.splice(idxToRemove, 1);

                                // Nie ustawiaj isFlipping=false tutaj, bo może być kilka setTimeoutów!
                            }, 700);
                        });

                        // Unlock the flip after 0.7s (when the longest flip ends)
                        setTimeout(() => {
                            isFlipping = false;
                        }, 700);
                    }

                    // Function to flip a specific tile
                    function flipTile(tileIdx) {
                        const tile = tiles[tileIdx];
                        const frontDiv = tile.querySelector(".single-event__catalog-flip-card-front");
                        const backDiv = tile.querySelector(".single-event__catalog-flip-card-back");

                        // Randomize new logo on back (unique)
                        let unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                        if(unused.length === 0) {
                            availableLogos.push(displayedLogos[tileIdx]);
                            unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                        }
                        let newBackLogo = pickUniqueLogos(unused, 1)[0] || displayedLogos[tileIdx];

                        // Change the logo on the "other side"
                        if (!flipState[tileIdx]) {
                            backDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                            tile.classList.add("flipped");
                        } else {
                            frontDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                            tile.classList.remove("flipped");
                        }
                        flipState[tileIdx] = !flipState[tileIdx];

                        setTimeout(() => {
                            availableLogos.push(displayedLogos[tileIdx]);
                            displayedLogos[tileIdx] = newBackLogo;
                            const idxToRemove = availableLogos.findIndex(l => l.URL_logo_wystawcy === newBackLogo.URL_logo_wystawcy);
                            if(idxToRemove !== -1) availableLogos.splice(idxToRemove, 1);
                        }, 700);
                    }

                    // Click handling
                    tiles.forEach((tile, idx) => {
                        tile.addEventListener("click", function() {
                            flipTile(idx);
                        });
                    });

                    setInterval(flipRandomTiles, 2500);

                }

                // Slick slider (Header mobile) <-----------------------------------------------------------------<
                const fairsLogotypesSlider = document.querySelector(".single-event__header-stripes");

                if (fairsLogotypesSlider) {
                    jQuery(document).ready(function($) {
                        const mediaQuery = window.matchMedia("(max-width: 960px)");

                        function initOrDestroySlider(e) {
                            if (e.matches) {
                                // Jeśli szerokość okna <= 960px i slick nie jest jeszcze zainicjowany
                                if (!$(".single-event__header-stripes").hasClass("slick-initialized")) {
                                    $(".single-event__header-stripes").slick({
                                        infinite: true,
                                        slidesToShow: 1,
                                        slidesToScroll: 1,
                                        autoplay: true,
                                        autoplaySpeed: 2000,
                                        swipeToSlide: true,
                                        cssEase: "linear",
                                        fade: true
                                    });
                                }
                            } else {
                                // Jeśli powyżej 960px i slick już działa → wyłącz
                                if ($(".single-event__header-stripes").hasClass("slick-initialized")) {
                                    $(".single-event__header-stripes").slick("unslick");
                                }
                            }
                        }

                        // Wywołaj przy starcie
                        initOrDestroySlider(mediaQuery);
                        // Nasłuchuj zmian szerokości
                        mediaQuery.addListener(initOrDestroySlider);
                    });
                }

                // Slick slider (partners) <-----------------------------------------------------------------<
                const fairsItemSlider = document.querySelector(".single-event__fairs-item-slider");
                if (fairsItemSlider) {
                    jQuery(document).ready(function($) {
                        $(".single-event__fairs-item-slider").slick({
                            infinite: true,
                            slidesToShow: 10,
                            slidesToScroll: 1,
                            autoplay: true,
                            autoplaySpeed: 2000,
                            swipeToSlide: true,
                            responsive: [
                                {
                                    breakpoint: 1024,
                                    settings: {
                                        slidesToShow: 9,
                                    }
                                },
                                {
                                    breakpoint: 768,
                                    settings: {
                                        slidesToShow: 8,
                                    }
                                },
                                {
                                    breakpoint: 600,
                                    settings: {
                                        slidesToShow: 6,
                                    }
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        slidesToShow: 4,
                                    }
                                }
                            ]
                        });
                    });
                }

                // COUNTER ANIMATION
                function animateCounter(element, target, duration = 1100) {
                    let start = 0;
                    let startTimestamp = null;
                    const step = (timestamp) => {
                        if (!startTimestamp) startTimestamp = timestamp;
                        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                        const value = Math.floor(progress * (target - start) + start);
                        element.textContent = value.toLocaleString("pl-PL");
                        if (progress < 1) {
                            requestAnimationFrame(step);
                        } else {
                            element.textContent = target.toLocaleString("pl-PL");
                        }
                    };
                    requestAnimationFrame(step);
                }

                // SEMICIRCLE ANIMATION TO '. $increase_percent .'%
                function animateArc() {
                    const arc = document.getElementById("arc-animate");
                    if (arc) {
                        const percent = 0.'. $increase_percent .'; // '. $increase_percent .'%
                        const pathLength = arc.getTotalLength();
                        arc.style.strokeDasharray = `0,${pathLength}`;
                        setTimeout(() => {
                            arc.style.transition = "stroke-dasharray 1.2s cubic-bezier(.7,.03,.48,1.01)";
                            arc.style.strokeDasharray = `${pathLength * percent},${pathLength}`;
                        }, 200);
                    }
                }


                // ACTIVATE EVERYTHING ONLY AFTER SCROLLING TO THE SECTION!
                let statsAnimated = false;
                function triggerStatsAnimations() {
                    if (statsAnimated) return;
                    const statsSection = document.querySelector(".single-event__stats");
                    const rect = statsSection.getBoundingClientRect();
                    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                    if (rect.top < windowHeight - 70) {
                        // animate numbers
                        document.querySelectorAll(".single-event__stats-count-up").forEach(el => {
                        animateCounter(el, parseInt(el.dataset.target));
                        });
                        // animate semicircle
                        animateArc();
                        statsAnimated = true;
                    }
                }
                window.addEventListener("scroll", triggerStatsAnimations);
                window.addEventListener("load", triggerStatsAnimations);

                // Exhibitors logotypes (all) <-----------------------------------------------------------------<
                const catalogButton = document.querySelector(".single-event__catalog-button");
                const mainContent = document.querySelector(".single-event__main-content");
                const exhibitorsCotainer = document.querySelector(".single-event__exhibitors");
                const header = document.querySelector(".single-event__header");
                const hideAllExhibitorsButton = document.querySelector(".single-event__exhibitors-hideall");

                // Sprawdzenie, czy URL zawiera parametr ?catalog
                if (window.location.search.includes("catalog")) {
                    mainContent.style.display = "none";
                    header.style.display = "none";
                    exhibitorsCotainer.style.display = "block";

                    // Przewijanie do góry strony
                    window.scroll({ top: 0, behavior: "smooth" });
                }

                if (catalogButton) {
                    catalogButton.addEventListener("click", function() {
                        // Dodajemy parametr ?catalog do URL
                        const currentUrl = window.location.href;
                        const newUrl = currentUrl.includes("?") ? `${currentUrl}&catalog` : `${currentUrl}?catalog`;

                        // Zmiana URL w przeglądarce bez przeładowania strony
                        window.history.pushState({ path: newUrl }, "", newUrl);

                        // Ukrycie głównej treści i pokazanie kontenera wystawców
                        mainContent.style.display = "none";
                        header.style.display = "none";
                        exhibitorsCotainer.style.display = "block";

                        // Przewijanie do góry strony
                        window.scroll({ top: 0, behavior: "smooth" });
                    });
                }

                if (hideAllExhibitorsButton) {
                    hideAllExhibitorsButton.addEventListener("click", function() {
                        // Ukrycie katalogu i przywrócenie wcześniejszej zawartości
                        exhibitorsCotainer.style.display = "none";
                        mainContent.style.display = "block";
                        header.style.display = "block";

                        // Usunięcie parametru ?catalog z URL
                        const url = new URL(window.location.href);
                        url.searchParams.delete("catalog"); // usuwa parametr

                        // Zmiana URL w przeglądarce bez przeładowania strony
                        window.history.pushState({ path: url.href }, "", url.href);
                    });
                }

                document.querySelectorAll(".single-event__exhibitors-fairs-button.interactive").forEach(btn => {
                    btn.addEventListener("click", function() {
                        var domain = this.getAttribute("data-domain");
                        document.querySelectorAll(".single-event__exhibitors-card").forEach(card => {
                            card.style.display = "none";
                        });
                        document.querySelectorAll(".single-event__exhibitors-card[data-domain=\"" + domain + "\"]").forEach(card => {
                            card.style.display = "flex";
                        });
                        // Pokazuj przycisk "Pokaż wszystkie" jeśli jest ukryty
                        var showAllBtn = document.getElementById("showAllExhibitors");
                        if (showAllBtn.style.display === "none") {
                            showAllBtn.style.display = "flex";
                        }
                        // Klasa active na wybranym kafelku
                        document.querySelectorAll(".single-event__exhibitors-fairs-button.interactive").forEach(btn2 => btn2.classList.remove("active"));
                        this.classList.add("active");
                        showAllBtn.classList.remove("active");
                    });
                });
                document.getElementById("showAllExhibitors").addEventListener("click", function() {
                    document.querySelectorAll(".single-event__exhibitors-card").forEach(card => {
                        card.style.display = "flex";
                    });
                    document.querySelectorAll(".single-event__exhibitors-fairs-button.interactive").forEach(btn => btn.classList.remove("active"));
                    this.classList.add("active");
                    // // Ukryj przycisk po kliknięciu!
                    // this.style.display = "none";
                });

                // Find all exhibitor cards
                const exhibitorCards = document.querySelectorAll(".single-event__exhibitors-card");

                // Get the modal and its elements
                const modal = document.getElementById("exhibitor-modal");
                const modalLogo = document.querySelector(".single-event__exhibitor-modal-logo");
                const modalName = document.querySelector(".single-event__exhibitor-modal-name");
                const modalBooth = document.querySelector(".single-event__exhibitor-modal-stand");
                const modalWebsite = document.querySelector(".single-event__exhibitor-modal-website");
                const closeBtn = document.querySelector(".single-event__exhibitor-modal-close-btn");

                // Loop through each exhibitor card and add click event
                exhibitorCards.forEach(card => {
                    card.addEventListener("click", () => {
                        const logoUrl = card.getAttribute("data-logo");
                        const name = card.getAttribute("data-name");
                        const booth = card.getAttribute("data-booth");
                        const website = card.getAttribute("data-website");

                        // Update modal content
                        modalLogo.src = logoUrl;
                        modalName.textContent = name;
                        modalBooth.textContent = `'. ($lang_pl ? "Stoisko:" : "Stand:") .' ${booth}`;
                        modalWebsite.href = website;

                        // Show the modal
                        modal.style.display = "flex";
                    });
                });

                // Close modal when user clicks the close button
                closeBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });

                // Close modal when user clicks outside the modal content
                window.addEventListener("click", (event) => {
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                });


                const inputSearchElement = document.getElementById("searchInput");
                if (inputSearchElement && exhibitorCards.length > 0) {
                    inputSearchElement.addEventListener("input", () => {
                        const query = inputSearchElement.value.toLowerCase().trim();

                        exhibitorCards.forEach(exhibitor => {
                            const exhibitorName = exhibitor.getAttribute("data-name")?.toLowerCase().trim() || "";
                            const match = exhibitorName.includes(query);
                            exhibitor.classList.toggle("dont-show", !match);
                        });
                    });
                }

            });
        </script>';

    endwhile;

} else {

    $title = the_title('', '', false);
    $title = str_replace(' ', '-', $title);

    $organizer = (strpos(strtolower(get_post_meta($post_id, 'organizer_name', true)), 'warsaw') !== false) ? 'warsaw' : get_post_meta($post_id, 'organizer_name', true);

    if (substr($site_url, -4) === '/en/') {
        $site_url = substr($site_url, 0, -4) . '/';
    }

    $translates = PWECommonFunctions::get_database_translations_data($domain);

    // [pwe_desc_{lang}]
    $shortcode_desc_pl = get_pwe_shortcode("pwe_desc_pl", $domain);
    $shortcode_desc_pl_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_desc_pl);
    $fair_desc = $shortcode_desc_pl_available ? get_translated_field($translates[0], 'fair_desc') : get_post_meta($post_id, 'desc', true);

    // [pwe_full_desc_{lang}]
    $shortcode_full_desc_pl = get_pwe_shortcode("pwe_full_desc_pl", $domain);
    $shortcode_full_desc_pl_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_full_desc_pl);
    $fair_full_desc = $shortcode_full_desc_pl_available ? get_translated_field($translates[0], 'fair_full_desc') : get_the_content();

    // [pwe_color_accent]
    $shortcode_accent_color = get_pwe_shortcode("pwe_color_accent", $domain);
    $shortcode_accent_color_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_accent_color);
    $accent_color = $shortcode_accent_color_available ? $shortcode_accent_color : get_post_meta($post_id, 'main_color', true);

    $lighter_accent_color = adjustBrightness($accent_color, 50);
    $light_accent_color = adjustBrightness($accent_color, 70);

    // [pwe_color_main2]
    $shortcode_main2_color = get_pwe_shortcode("pwe_color_main2", $domain);
    $shortcode_main2_color_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_main2_color);
    $main2_color = $shortcode_main2_color_available ? $shortcode_main2_color : get_post_meta($post_id, 'main2_color', true);

    $dark_main2_color = adjustBrightness($main2_color, -30);

    // [pwe_visitors]
    $shortcode_visitors = get_pwe_shortcode("pwe_visitors", $domain);
    $shortcode_visitors_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_visitors);
    $visitors_num = $shortcode_visitors_available ? $shortcode_visitors : get_post_meta($post_id, 'visitors', true);

    // [pwe_exhibitors]
    $shortcode_exhibitors = get_pwe_shortcode("pwe_exhibitors", $domain);
    $shortcode_exhibitors_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_exhibitors);
    $exhibitors_num = $shortcode_exhibitors_available ? $shortcode_exhibitors : get_post_meta($post_id, 'exhibitors', true);

    // [pwe_countries]
    $shortcode_countries = get_pwe_shortcode("pwe_countries", $domain);
    $shortcode_countries_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_countries);
    $countries_num = ($shortcode_countries_available || $shortcode_countries !== "0") ? $shortcode_countries : get_post_meta($post_id, 'countries', true);

    // // [pwe_category_pl]
    // $shortcode_category_pl = get_pwe_shortcode("pwe_category_pl", $domain);
    // $shortcode_category_pl_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_category_pl);
    // $category_pl = ($shortcode_category_pl_available || $shortcode_category_pl !== "0") ? $shortcode_category_pl : get_post_meta($post_id, 'category_pl', true);

    // // [pwe_category_en]
    // $shortcode_category_en = get_pwe_shortcode("pwe_category_en", $domain);
    // $shortcode_category_en_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_category_en);
    // $category_en = ($shortcode_category_en_available || $shortcode_category_en !== "0") ? $shortcode_category_en : get_post_meta($post_id, 'category_en', true);

    // [pwe_edition]
    $shortcode_edition = get_pwe_shortcode("pwe_edition", $domain);
    $shortcode_edition_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_edition);

    if ($shortcode_edition_available) {
        if($shortcode_edition == '1'){
            $edition .= multi_translation("premier_edition");
        } else {
            $edition .= $shortcode_edition . multi_translation("edition");
        }
    }

    $first_date = explode('-', $start_date);
    $second_date = explode('-', $end_date);

    $schame_date_start = $first_date[2]. '-' .$first_date[1]. '-' . $first_date[0];
    $schame_date_end = $second_date[2]. '-' .$second_date[1]. '-' . $second_date[0];

    $output .= '
    <style>
        .color-accent {
            color: '. $accent_color .';
        }
        .color-main2 {
            color: '. $main2_color .';
        }
        .single-event__wrapper {
            display: flex;
            flex-direction: column;
            padding: 36px 18px;
        }
        .single-event__btn-container a {
            transition: .3s ease;
            box-shadow: 0px 0px 10px #7f7f7f !important;
        }
        .single-event__btn-container a:hover {
            transform: scale(0.98);
        }
        @media (max-width: 960px){
            .single-event__wrapper {
                padding: 0 0 18px;
            }
            .mobile-hidden {
                display: none;
            }
        }



        /* Header section <----------------------------> */
        .single-event__container-header {
            position: relative;
            background-image: url('. $header_bg .');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 18px;
            min-height: 300px;
        }
        .single-event__header-bottom {
            display: flex;
            justify-content: space-between;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
        }
        .single-event__header-edition {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            text-transform: uppercase;
            width: 100%;
            background: #00000070;
            margin-right: -18px;
            border-radius: 0 0 0 18px;
        }
        .single-event__header-edition span {
            color: white;
            font-size: 20px;
            margin-right: 18px;
            font-weight: 700;
        }
        .single-event__header-buttons {
            display: flex;
            padding: 0 18px 0;
            background: white;
            border-radius: 18px 0 0 0;
            gap: 18px;
            box-shadow: inset -3px -3px 5px 0px #ffffff, 3px 3px 5px 0px #ffffff, 3px 3px 5px 0px #ffffff, inset 3px 3px 5px 0px #cfcfcf;
        }
        .single-event__btn-container {
            min-width: 300px;
            margin-top: 14px;
        }
        .single-event__btn-container a {
            display: flex;
            justify-content: center;
            background: '. $main2_color .';
            color: white;
            border-radius: 36px;
            padding: 14px 36px;
            font-size: 16px;
            font-weight: 600;
        }
        .single-event__btn-container a.single-event__btn-dark {
            background: '. $dark_main2_color .';
        }
        .single-event__btn-container a:hover {
            background: '. $accent_color .';
        }
        .single-event__header-logotype {
            position: absolute;
            top: 45%;
            left: 36px;
            transform: translate(0, -55%);
            max-width: 300px;
        }
        .single-event__header-logotype img {
            width: 100%;
            max-height: 200px;
        }

        @media (max-width: 960px){
            .single-event__container-header {
                border-radius: 0;
            }
            .single-event__header-logotype {
                top: 40%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .single-event__header-bottom {
                bottom: 18px;
            }
            .single-event__header-edition {
                padding: 6px;
                border-radius: 0;
            }
        }


        /* Description section mobile <----------------------------> */
        .single-event__container-desc.mobile {
            display: none;
        }
        .single-event__header-buttons.mobile {
            display: none;
        }
        @media (max-width: 960px) {
            .single-event__container-desc.mobile {
                display: flex;
                padding: 18px;
                margin-top: -18px;
                background: white;
                border-radius: 18px 18px 0 0;
                z-index: 2;
            }
            .single-event__header-buttons.mobile {
                margin-top: 18px;
            }
            .single-event__header-buttons.desktop {
                display: none;
            }
            .single-event__header-buttons.mobile {
                display: flex;
                flex-wrap: wrap;
                box-shadow: unset;
                justify-content: center;
            }
        }
        @media (max-width: 450px) {
            .single-event__container-desc.mobile .single-event__date,
            .single-event__container-desc.mobile .single-event__main-title {
                text-align: center;
            }
        }


        /* Description section <----------------------------> */
        .single-event__container-desc {
            display: flex;
            gap: 20px;
            padding-top: 18px;
        }
        .single-event__desc-column.title {
            width: 45%;
        }
        .single-event__desc-column.description {
            width: 55%;
        }
        .single-event__desc-column.description .row-parent {
            padding: 0 !important;
        }
        .single-event__desc-column.description strong {
            color: '. $accent_color .' !important;
            font-weight: 700 !important;
        }
        .single-event__desc-column p {
            line-height: 1.3;
            margin: 0;
        }
        .single-event__date {
            font-size: 20px !important;
            text-transform: lowercase;
            margin: 0 0 10px 0;
            font-weight: 700;
        }
        .single-event__main-title {
            font-size: 30px !important;
            margin: 0;
            text-transform: unset !important;
        }
        .single-event__btn-container.webpage span {
            margin-left: 10px;
            font-size: 40px;
            font-weight: 300;
            height: 15px;
            width: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: .3s ease;
        }
        .single-event__btn-container.webpage:hover span {
            transform: translateX(10px);
        }
        .single-event__container-desc .single-event__btn-container a {
            margin-top: 26px;
            max-width: 300px !important;
        }
        @media (max-width: 960px){
            .single-event__container-desc.desktop {
                flex-direction: column;
                padding: 18px;
            }
            .single-event__container-desc {
                margin-top: 0;
                flex-wrap: wrap;
            }
            .single-event__desc-column {
                width: 100% !important;
            }
            .single-event__date {
                font-size: 26px !important;
            }
            .single-event__main-title {
                font-size: 30px !important;
            }
            .single-event__btn-container a {
                margin: 0 auto !important;
            }
            .single-event__container-desc.mobile .single-event__btn-container {
                margin-top: 0;
            }
        }
        /* <----------------------------> */




        /* Partners logotypes <----------------------------> */
        .single-event__container-partners {
            display: flex;
            padding-top: 18px;
        }
        .single-event__partners-title {
            display: flex;
            align-items: center;
            min-width: 320px;
        }
        .single-event__partners-title h4 {
            font-size: 24px !important;
            text-transform: uppercase;
            margin: 0;
        }
        .single-event__partners-logotypes {
            width: 100%;
            overflow: hidden;
            display: flex;
        }
        .single-event__partners-logo {
            margin: 10px;
        }
        .single-event__container-partners img {
            aspect-ratio: 4 / 2;
            object-fit: contain;
        }
        @media (max-width: 960px){
            .single-event__container-partners {
                box-shadow: 0px 0px 10px #d2d2d2 !important;
                padding: 18px;
                margin: 10px 0;
            }
        }
        @media (max-width: 650px){
            .single-event__container-partners {
                flex-direction: column;
            }
            .single-event__partners-title {
                margin: 0 auto;
                justify-content: center;
            }
            .single-event__partners-title h4 {
                font-size: 24px !important;
            }
        }
        /* <----------------------------> */


        /* Tiles section <----------------------------> */
        .single-event__container-tiles {
            display: flex;
            height: 400px;
            gap: 20px;
            padding-top: 36px;
        }
        .single-event__tiles-item {
            box-shadow: 0px 0px 7px #929292 !important;
        }
        .single-event__tiles-left-container {
            width: 45%;
            height: 100%;
            position: relative;
            background-image: url(https://'. $domain .'/doc/photo-calendar.webp);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 18px;
            transition: .3s ease;
        }
        .single-event__tiles-left-container a {
            display: flex;
            width: 100%;
            height: 100%;
        }
        .single-event__tiles-right-container {
            display: flex;
            flex-direction: column-reverse;
            width: 55%;
            height: 100%;
            gap: 20px;
        }
        .single-event__tiles-right-top {
            height: 50%;
            position: relative;
            border-radius: 18px;
            transition: .3s ease;
            background: '. $lighter_accent_color .';
        }
        .single-event__tiles-right-top:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-image: url(https://warsawexpo.eu/wp-content/uploads/2023/03/map_single_event_transparent.webp);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: 1;
        }
        /* <----------------------------> */

        /* Tiles section (numbers) */
        .single-event__statistics-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            float: right;
            align-items: center;
            width: 100%;
            height: 100%;
            gap: 36px;
            z-index: 2;
            gap: 30px;
        }
        .single-event__statistics-numbers {
            display: flex;
            flex-direction: column;
            margin: 0;
            text-shadow: 1px 1px 24px black;
        }
        .single-event__statistics-number {
            font-size: 42px;
            font-weight: 700;
            color: white;
            text-align: center;
        }
        .single-event__statistics-name {
            text-transform: uppercase;
            color: white;
            font-family: system-ui !important;
            text-shadow: 1px 1px 24px black;
        }
        /* <----------------------------> */

        /* Tiles section (conference) */
        .single-event__tiles-right-bottom {
            height: 50%;
            display: flex;
            gap: 20px;
        }
        .single-event__tiles-right-bottom-attractions {
            background-image: url(https://'. $domain .'/doc/attractions.webp);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            width: 100%;
            height: 100%;
            position: relative;
            border-radius: 18px;
            transition: .3s ease;
        }
        .single-event__tiles-right-bottom-attractions a {
            display: flex;
            width: 100%;
            height: 100%;
        }
        .single-event__tiles-right-bottom-left {
            width: ' . (empty($api_media["konferencje"]) ? "100%" : "50%") . ';
            height: 100%;
            position: relative;
            border-radius: 18px;
            transition: .3s ease;
            background: '. $accent_color .';
        }
        .single-event__tiles-right-bottom-left a {
            display: flex;
            justify-content: center;
            width: 100%;
            height: 100%;
        }
        .single-event__tiles-right-bottom-left img {
            object-fit: contain;
            padding: 24px;
            max-width: 260px;
            width: 100%;
        }
        .single-event__tiles-right-bottom-right {
            width: 50%;
            height: 100%;
            position: relative;
            border-radius: 18px;
            transition: .3s ease;
            background: white;
        }
        .single-event__conference-logotype {
            display: flex;
        }
        .single-event__tiles-hover:hover {
            transform: scale(0.98);
        }
        .single-event__container-tiles .single-event__single-event__stats-caption {
            position: absolute;
            left: 0;
            bottom: 0;
            background: white;
            padding: 14px 20px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 0 18px 0 0;
            z-index: 2;
            left: 0 !important;
            bottom: 0 !important;
            box-shadow: inset 2px -2px 3px 0px #ffffff,
                        -1px 7px 1px #ffffff,
                        -7px 7px 5px #ffffff,
                        -7px 1px 1px #ffffff,
                        inset -2px 2px 5px 0px #cfcfcf;
        }
        .single-event__container-tiles a {
            color: black !important;
        }

        @media (max-width: 960px){
            .single-event__container-tiles {
                flex-direction: column;
                height: auto;
                padding: 18px 18px 0;
            }
            .single-event__tiles-left-container {
                width: 100%;
                height: 300px;
            }
            .single-event__tiles-right-container {
                width: 100%;
                min-height: 300px;
            }
            .single-event__tiles-right-top {
                height: 33%;
                min-height: 150px;
            }
            .single-event__statistics-wrapper {
                min-height: 150px;
            }
            .single-event__statistics-number {
                font-size: 30px;
            }
            .single-event__statistics-name {
                font-size: 14px;
            }
            .single-event__container-tiles .single-event__single-event__stats-caption {
                padding: 10px;
                font-size: 14px;
            }
            .single-event__tiles-right-bottom {
                height: 66%;
                display: flex;
                flex-direction: column;
            }
            .single-event__tiles-right-bottom-left {
                width: 100%;
                min-height: 150px;
            }
            .single-event__tiles-right-bottom-left img {
                max-width: 300px;
            }
            .single-event__tiles-right-bottom-right,
            .single-event__tiles-right-bottom-attractions {
                width: 100%;
                min-height: 150px;
            }
            .single-event__conference-logotype {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .single-event__container-tiles .single-event__organizer .single-event__single-event__stats-caption {
                background: unset;
                border: none;
                text-align: center;
                bottom: 0 !important;
                left: 0 !important;
            }
            .single-event__conferences-logo {
                padding: 24px 10px;
            }
        }
        @media (max-width: 400px) {
            .single-event__statistics-name {
                font-size: 12px;
            }
        }
        /* <----------------------------> */

        /* Events logotypes <----------------------------> */
        .single-event__events-logotypes {
            margin-top: 18px;
        }
        .single-event__events-logo {
            margin: 10px;
            padding: 10px;
            box-shadow: 1px 1px 10px #bababa !important;
            border-radius: 18px;
            display: flex !important;
            flex-direction: column;
            justify-content: center;
        }
        .single-event__events-title h4 {
            font-size: 30px;
        }
        .single-event__events-logo-title {
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
        }
        @media (max-width: 960px){
            .single-event__container-events {
                padding: 0 18px;
            }
        }
        @media (max-width: 650px){
            .single-event__events-title h4 {
                font-size: 24px !important;
            }
        }
        /* <----------------------------> */

        /* Conferences logotypes <----------------------------> */
        .single-event__conferences-logotypes {
            margin: 0 auto;
            max-width: 200px;
        }
        .single-event__conferences-logo {
            padding: 24px;
        }
        .single-event__conferences-logo img {
            object-fit: contain;
            border-radius: 10px;
        }
        @media (max-width: 650px){
            .single-event__events-logo-title {
                font-size: 14px;
            }
        }


        /* Footer section <----------------------------> */
        .single-event__container-footer {
            display: flex;
            justify-content: space-evenly;
            padding: 18px;
            background-color: rgba(33, 0, 0, 0.05);
            border-radius: 18px;
            margin-top: 18px;
            gap: 10px;
        }
        .single-event__footer-ptak-logo {
            display: flex;
            justify-content: center;
            width: 33%;
        }
        .single-event__footer-content {
            width: 66%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .single-event__footer-ptak-adress,
        .single-event__footer-ptak-contact {
            display: flex;
            align-items: center;
        }
        .single-event__footer-ptak-logo img {
            width: 100px;
            object-fit: contain;
        }
        .single-event__footer-ptak-adress div,
        .single-event__footer-ptak-contact div {
            display: flex;
            flex-direction: column;
        }
        .single-event__footer-ptak-adress p,
        .single-event__footer-ptak-contact p {
            margin: 0;
        }
        @media (max-width: 960px) {
            .single-event__container-footer {
                margin: 18px 18px 0;
                padding: 8px;
            }
        }
        @media (max-width: 569px){
            .single-event__footer-content {
                flex-direction: column;
            }
        }
    </style>';

     while (have_posts()):
        the_post();

        $cap_logotypes_data = PWECommonFunctions::get_database_logotypes_data($domain);

        $output .= '
        <div data-parent="true" class="vc_row limit-width row-container boomapps_vcrow '. $title .'" data-section="21" itemscope itemtype="http://schema.org/Event">
            <div class="single-event" data-imgready="true">
                <div class="single-event__wrapper">';

                    // Header section
                    $output .= '
                    <div class="single-event__container-header">
                        <div class="single-event__header-logotype">
                            <img src="'. $main_logo .'" alt="Trade Fair Logo"/>
                        </div>
                        <div class="single-event__header-bottom">
                            <div class="single-event__header-edition">
                                <span>'. $edition .'</span>
                            </div>
                            <div class="single-event__header-buttons desktop">';
                                if(!empty(get_post_meta($post_id, 'buy_ticket_link', true))) {
                                    $output .= '
                                    <span class="single-event__btn-container">
                                        <a
                                            target="_blank"
                                            rel="noopener"
                                            href="'. get_post_meta($post_id, 'buy_ticket_link', true) .'"
                                            class="single-event__btn"
                                            data-title="'. multi_translation("buy_ticket") .'"
                                            title="'. multi_translation("buy_ticket") .'">
                                            '. multi_translation("buy_ticket") .'
                                        </a>
                                    </span>';
                                } else if(!empty(get_post_meta($post_id, 'visitor_registration_link', true))) {
                                    $output .= '
                                    <span
                                        itemprop="offers"
                                        itemscope
                                        itemtype="http://schema.org/Offer"
                                        class="single-event__btn-container">
                                        <a
                                            itemprop="url"
                                            target="_blank"
                                            rel="noopener"
                                            href="'. get_post_meta($post_id, 'visitor_registration_link', true) .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal"
                                            class="single-event__btn"
                                            data-title="'. multi_translation("collect_invitation") .'"
                                            title="'. multi_translation("collect_invitation") .'">
                                            '. multi_translation("collect_invitation") .'
                                        </a>
                                    </span>';
                                }
                                if(!empty(get_post_meta($post_id, 'exhibitor_registration_link', true))) {
                                    $output .= '
                                    <span class="single-event__btn-container single-event-btn">
                                        <a
                                            target="_blank"
                                            rel="noopener"
                                            href="'. get_post_meta($post_id, 'exhibitor_registration_link', true) .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal"
                                            class="single-event__btn single-event__btn-dark"
                                            data-title="'. multi_translation("become_exhibitor") .'"
                                            title="'. multi_translation("become_exhibitor") .'">
                                            '. multi_translation("become_exhibitor") .'
                                        </a>
                                    </span>';
                                }
                                $output .= '
                            </div>
                        </div>
                    </div>';

                    // Description section mobile
                    $output .= '
                    <div class="single-event__container-desc mobile">
                        <div class="single-event__desc-column title">
                            <time itemprop="startDate" datetime="'. $schame_date_start .'"></time>
                            <time itemprop="endDate" datetime="'. $schame_date_end .'"></time>
                            <h2 class="single-event__date color-accent">'. $fair_date .'</h2>
                            <h1 class="single-event__main-title" itemprop="name" style="text-transform:uppercase;">'. $fair_desc .'</h1>';
                            $output .= '
                            <div class="single-event__header-buttons mobile">';
                                if(!empty(get_post_meta($post_id, 'buy_ticket_link', true))) {
                                    $output .= '
                                    <span class="single-event__btn-container">
                                        <a
                                            target="_blank"
                                            rel="noopener"
                                            href="'. get_post_meta($post_id, 'buy_ticket_link', true) .'"
                                            class="single-event__btn"
                                            data-title="'. multi_translation("buy_ticket") .'"
                                            title="'. multi_translation("buy_ticket") .'">
                                            '. multi_translation("buy_ticket") .'
                                        </a>
                                    </span>';
                                } else if(!empty(get_post_meta($post_id, 'visitor_registration_link', true))) {
                                    $output .= '
                                    <span
                                        itemprop="offers"
                                        itemscope
                                        itemtype="http://schema.org/Offer"
                                        class="single-event__btn-container">
                                        <a
                                            itemprop="url"
                                            target="_blank"
                                            rel="noopener"
                                            href="'. get_post_meta($post_id, 'visitor_registration_link', true) .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal"
                                            class="single-event__btn"
                                            data-title="'. multi_translation("collect_invitation") .'"
                                            title="'. multi_translation("collect_invitation") .'">
                                            '. multi_translation("collect_invitation") .'
                                        </a>
                                    </span>';
                                }
                                if(!empty(get_post_meta($post_id, 'exhibitor_registration_link', true))) {
                                    $output .= '
                                    <span class="single-event__btn-container single-event-btn">
                                        <a
                                            target="_blank"
                                            rel="noopener"
                                            href="'. get_post_meta($post_id, 'exhibitor_registration_link', true) .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal"
                                            class="single-event__btn single-event__btn-dark"
                                            data-title="'. multi_translation("become_exhibitor") .'"
                                            title="'. multi_translation("become_exhibitor") .'">
                                            '. multi_translation("become_exhibitor") .'
                                        </a>
                                    </span>';
                                }
                                $output .= '
                            </div>
                        </div>
                    </div>';

                    // Partners section
                    if (!empty($cap_logotypes_data)) {

                        $saving_paths = function (&$files, $logo_data) {
                            $data = json_decode($logo_data->data, true);

                            $link = $data['logos_link'] ?? null;
                            $alt  = $data['logos_alt'] ?? ($logo_data->logos_alt ?? null);

                            $element = [
                                'url' => 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url,
                                'link' => $link,
                                'alt' => $alt
                            ];

                            // Adding logos_url to $files only if it is not already there
                            if (!in_array($element, $files)) {
                                $files[] = $element;
                            }
                        };


                        $files = [];

                        foreach ($cap_logotypes_data as $logo_data) {
                            if ($logo_data->logos_type === "partner-targow" ||
                                $logo_data->logos_type === "patron-medialny" ||
                                $logo_data->logos_type === "partner-strategiczny" ||
                                $logo_data->logos_type === "partner-honorowy" ||
                                $logo_data->logos_type === "principal-partner" ||
                                $logo_data->logos_type === "industry-media-partner" ||
                                $logo_data->logos_type === "partner-branzowy" ||
                                $logo_data->logos_type === "partner-merytoryczny") {
                                $saving_paths($files, $logo_data);
                            }
                        }

                        if (count($files) > 0) {

                            $output .= '
                            <div class="single-event__container-partners">
                                <div class="single-event__partners-title">
                                    <h4>'. multi_translation("patrons_and_partners") .'</h4>
                                </div>
                                <div class="single-event__partners-logotypes single-event__logotypes-slider">';
                                    foreach ($files as $logo) {
                                        if (!empty($logo['link'])) {
                                            $output .= '
                                            <a href="'. $logo["link"] .'" target="_blank">
                                                <div class="single-event__partners-logo">
                                                    <img src="'. $logo["url"] .'" alt="Partner\'s logo"/>
                                                </div>
                                            </a>';
                                        } else {
                                            $output .= '
                                            <div class="single-event__partners-logo">
                                                <img src="'. $logo["url"] .'" alt="Partner\'s logo"/>
                                            </div>';
                                        }
                                    }
                                $output .= '
                                </div>
                            </div>';

                        }

                    } else if (!empty(get_post_meta($post_id, 'partners_gallery', true))) {
                        $seperated_logotypes = get_post_meta($post_id, 'partners_gallery', true);

                        $output .= '
                        <div class="single-event__container-partners">
                            <div class="single-event__partners-title">
                                <h4>'. multi_translation("patrons_and_partners") .'</h4>
                            </div>
                            <div class="single-event__partners-logotypes single-event__logotypes-slider">';
                                foreach ($seperated_logotypes as $logo) {
                                    $url = trim($logo);
                                    $output .= '
                                    <div class="single-event__partners-logo">
                                        <img src="'. $url .'" alt="Partner\'s logo"/>
                                    </div>';
                                }
                            $output .= '
                            </div>
                        </div>';
                    }

                    // Description section
                    $output .= '
                    <div class="single-event__container-desc desktop">
                        <div class="single-event__desc-column title">
                            <time itemprop="startDate" datetime="'. $schame_date_start .'"></time>
                            <time itemprop="endDate" datetime="'. $schame_date_end .'"></time>
                            <h2 class="single-event__date color-accent mobile-hidden">'. $fair_date .'</h2>
                            <h1 class="single-event__main-title mobile-hidden" itemprop="name" style="text-transform:uppercase;">'. $fair_desc .'</h1>';
                            if(!empty(get_post_meta($post_id, 'web_page_link', true))) {
                                $output .= '
                                <span class="single-event__btn-container webpage">
                                    <a
                                        target="_blank"
                                        rel="noopener"
                                        itemprop="url"
                                        href="'. get_post_meta($post_id, 'web_page_link', true) .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal"
                                        class="single-event__btn"
                                        data-title="'. multi_translation("website") .'"
                                        title="'. multi_translation("website") .'">
                                        '. multi_translation("website") .' <span class="btn-angle-right">&#8250;</span>
                                    </a>
                                </span>';
                            }
                        $output .= '
                        </div>
                        <div class="single-event__desc-column description" itemprop="description">'.  $fair_full_desc .'</div>
                    </div>';

                    // Tiles section
                    if ($organizer === "warsaw") {
                        $output .= '
                        <div class="single-event__container-tiles">
                            <div class="single-event__tiles-left-container single-event__tiles-item single-event__tiles-hover">
                                <a href="https://'. $domain . ($lang_pl ? "/" : "/en/") .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal" target="_blank">
                                    <span class="single-event__single-event__stats-caption">'. multi_translation("discover_trade_fair") .'</span>
                                </a>
                            </div>
                            <div class="single-event__tiles-right-container">

                                <div class="single-event__tiles-right-top single-event__statistics single-event__tiles-item single-event__tiles-hover">
                                    <div class="single-event__statistics-wrapper">';
                                        if ($visitors_num !== "" && $visitors_num !== "0") {
                                            $output .= '
                                            <div class="single-event__statistics-numbers visitors">
                                                <span class="single-event__statistics-number countup" data-count="'. $visitors_num .'">0</span>
                                                <span class="single-event__statistics-name">'. multi_translation("visitors") .'</span>
                                            </div>';
                                        }
                                        if ($exhibitors_num !== "" && $exhibitors_num !== "0") {
                                            $output .= '
                                            <div class="single-event__statistics-numbers exhibitors">
                                                <span class="single-event__statistics-number countup" data-count="'. $exhibitors_num .'">0</span>
                                                <span class="single-event__statistics-name">'. multi_translation("exhibitors") .'</span>
                                            </div>';
                                        }
                                        if ($countries_num !== "" && $countries_num !== "0") {
                                            $output .= '
                                            <div class="single-event__statistics-numbers countries">
                                                <span class="single-event__statistics-number countup" data-count="'. $countries_num .'"></span>
                                                <span class="single-event__statistics-name">'. multi_translation("countries") .'</span>
                                            </div>';
                                        }
                                    $output .= '
                                    </div>
                                    <span class="single-event__single-event__stats-caption">'. ($shortcode_edition == '1' ? multi_translation("estimates") : multi_translation("statistics")) .'</span>
                                </div>

                                <div class="single-event__tiles-right-bottom">';

                                    if (!empty(get_post_meta($post_id, 'buy_ticket_link', true))) {
                                        $output .= '
                                        <div class="single-event__tiles-right-bottom-attractions single-event__tiles-item single-event__tiles-hover">
                                            <a href="https://'. $domain . multi_translation("attractions_url") .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal" target="_blank">
                                                <span class="single-event__single-event__stats-caption">'. multi_translation("attractions") .'</span>
                                            </a>
                                        </div>';
                                    } else {
                                        $output .= '
                                        <div class="single-event__tiles-right-bottom-left single-event__conference single-event__tiles-item single-event__tiles-hover">
                                            <a href="https://'. $domain . multi_translation("conference_url") .'?utm_source=warsawexpo&utm_medium=kalendarz&utm_campaign=refferal" target="_blank">
                                                <div class="single-event__conference-logotype">
                                                    <img src="https://'. $domain .'/doc/kongres.webp"/ alt="Congress Logo">
                                                </div>
                                                <span class="single-event__single-event__stats-caption">'. multi_translation("conference") .'</span>
                                            </a>
                                        </div>';

                                        if (!empty($api_media["konferencje"])) {
                                            $output .= '
                                            <div class="single-event__tiles-right-bottom-right single-event__organizer single-event__tiles-item single-event__tiles-hover">
                                                <div class="single-event__conferences-logotypes single-event__logotypes-slider">';
                                                    foreach ($api_media["konferencje"] as $logo) {
                                                        // Get filename without extension
                                                        $filename_conferences = $logo["title"];

                                                        // Matching name in format "Partner Targów - Partner of the Fair"
                                                        if (preg_match('/^(.*) - (.*)$/', $filename_conferences, $matches)) {
                                                            // Polish name before " - "
                                                            $title_pl = trim($matches[1]);
                                                            // English name after " - "
                                                            $title_en = trim($matches[2]);
                                                        } else {
                                                            // If no match found, use full name
                                                            $title_pl = $filename_conferences;
                                                            $title_en = $filename_conferences;
                                                        }

                                                        $output .= '
                                                        <div class="single-event__conferences-logo">
                                                            <img src="'. $logo["path"] .'" alt="'. ($lang_pl ? $title_pl : $title_en) .'"/>
                                                        </div>';
                                                    }
                                                $output .= '
                                                </div>
                                                <span class="single-event__single-event__stats-caption"></span>
                                            </div>';
                                        }
                                    }
                                    $output .= '
                                </div>

                            </div>
                        </div>';
                    }

                    // Events section
                    if (!empty($cap_logotypes_data)) {

                        $europe_events_logotypes = [];

                        $saving_paths = function (&$europe_events_logotypes, $logo_data) {
                            $data = json_decode($logo_data->data ?? '{}', true);

                            $link = $logo_data->logos_link;
                            $alt = !empty($data['logos_alt']) ? $data['logos_alt'] : $logo_data->logos_alt;

                            $element = [
                                'url' => 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url,
                                'link' => $link,
                                'alt' => $alt
                            ];

                            // Adding logos_url to $europe_events_logotypes only if it is not already there
                            if (!in_array($element, $europe_events_logotypes)) {
                                $europe_events_logotypes[] = $element;
                            }
                        };


                        $files = [];

                        foreach ($cap_logotypes_data as $logo_data) {
                            if ($logo_data->logos_type === "europe-event") {
                                $saving_paths($europe_events_logotypes, $logo_data);
                            }
                        }

                        function format_title($title) {
                            return preg_replace('/\((.*?)\)/', '<br><span style="color: #888;">($1)</span>', $title);
                        }

                        $output .= '
                        <div class="single-event__container-events">';

                        if($europe_events_logotypes){
                            $output .= '
                                <div class="single-event__events-title">
                                    <h4>'. multi_translation("most_important_industry_events_in_europe") .'</h4>
                                </div>';
                        }

                        $output .= '
                            <div class="single-event__events-logotypes single-event__logotypes-slider">';
                                $non_warsaw = [];
                                $warsaw_logos = [];

                                // Division of logos into two groups
                                foreach ($europe_events_logotypes as $logo) {
                                    // Get file alt
                                    $filename_events = $logo["alt"];

                                    // Check if name contains "warsaw" or "warsawa" (case-insensitive)
                                    if (stripos($filename_events, "warsaw") !== false || stripos($filename_events, "warszawa") !== false) {
                                        $warsaw_logos[] = $logo;
                                    } else {
                                        $non_warsaw[] = $logo;
                                    }
                                }

                                // Merge the boards – the ones with warsaw at the end
                                $sorted_logos = array_merge($non_warsaw, $warsaw_logos);

                                foreach ($sorted_logos as $logo) {
                                    // Get file name without extension
                                    $filename_events = $logo["alt"];

                                    // Matching name in format "Europe/IPM (Essen, Germany) - IPM (Essen, Germany)"
                                    if (preg_match('/^(.*) - (.*)$/', $filename_events, $matches)) {
                                        // Polish name before " - "
                                        $title_pl = trim($matches[1]);
                                        // English name after " - "
                                        $title_en = trim($matches[2]);
                                    } else {
                                        // If no match, use full name
                                        $title_pl = $filename_events;
                                        $title_en = $filename_events;
                                    }

                                    $formatted_title_pl = format_title($title_pl ?? '');
                                    $formatted_title_en = format_title($title_en ?? '');

                                    $output .= '
                                    <div class="single-event__events-logo">
                                        <img src="'. $logo["url"] .'" alt="'. (PWECommonFunctions::lang_pl() ? $title_pl : $title_en) .'"/>
                                        <div class="single-event__events-logo-title"><span>'. (PWECommonFunctions::lang_pl() ? $formatted_title_pl : $formatted_title_en) .'</span></div>
                                    </div>';
                                }
                            $output .= '
                            </div>
                        </div>';

                    } else if (!empty($api_media["wydarzenia"])) {

                        $api_media_events = $api_media["wydarzenia"];
                        $api_media_doc = $api_media["doc"];

                        // Check if the "Europa EN" folder exists by searching for its path
                        $europa_en_exists = false;
                        foreach ($api_media_doc as $item) {
                            if (strpos($item["path"], "/Logotypy/Europa EN/") !== false) {
                                $europa_en_exists = true;
                                break;
                            }
                        }

                        if ($europa_en_exists) {
                            // If "Europa EN" exists, filter logos from that folder
                            $europa_en_logotypes = array_filter($api_media_doc, function($item) {
                                return strpos($item["path"], "/Logotypy/Europa EN/") !== false;
                            });
                        } else {
                            // If "Europa EN" doesn't exist, fetch all logotypes from $api_media["wydarzenia"]
                            $europa_en_logotypes = array_filter($api_media_events, function($item) {
                                return strpos($item["path"], "Logotypy") !== false;
                            });
                        }

                        if (!$lang_pl && ($domain == 'targirehabilitacja.pl' || $domain == 'centralnetargirolnicze.com')) {
                            $events_logotypes = $europa_en_logotypes;
                        } else {
                            $events_logotypes = $api_media_events;
                        }

                        function format_title($title) {
                            return preg_replace('/\((.*?)\)/', '<br><span style="color: #888;">($1)</span>', $title);
                        }

                        $output .= '
                        <div class="single-event__container-events">
                            <div class="single-event__events-title">
                                <h4>'. multi_translation("most_important_industry_events_in_europe") .'</h4>
                            </div>
                            <div class="single-event__events-logotypes single-event__logotypes-slider">';
                                $non_warsaw = [];
                                $warsaw_logos = [];

                                // Division of logos into two groups
                                foreach ($events_logotypes as $logo) {
                                    // Get file name without extension
                                    $filename_events = basename($logo["path"], ".webp");

                                    // Check if name contains "warsaw" or "warsawa" (case-insensitive)
                                    if (stripos($filename_events, "warsaw") !== false || stripos($filename_events, "warszawa") !== false) {
                                        $warsaw_logos[] = $logo;
                                    } else {
                                        $non_warsaw[] = $logo;
                                    }
                                }

                                // Merge the boards – the ones with warsaw at the end
                                $sorted_logos = array_merge($non_warsaw, $warsaw_logos);

                                foreach ($sorted_logos as $logo) {
                                    // Get file name without extension
                                    $filename_events = basename($logo["path"], ".webp");

                                    // Matching name in format "Europe/IPM (Essen, Germany) - IPM (Essen, Germany)"
                                    if (preg_match('/^(.*) - (.*)$/', $filename_events, $matches)) {
                                        // Polish name before " - "
                                        $title_pl = trim($matches[1]);
                                        // English name after " - "
                                        $title_en = trim($matches[2]);
                                    } else {
                                        // If no match, use full name
                                        $title_pl = $filename_events;
                                        $title_en = $filename_events;
                                    }

                                    $formatted_title_pl = format_title($title_pl ?? '');
                                    $formatted_title_en = format_title($title_en ?? '');

                                    $output .= '
                                    <div class="single-event__events-logo">
                                        <img src="'. $logo["path"] .'" alt="'. ($lang_pl ? $title_pl : $title_en) .'"/>
                                        <div class="single-event__events-logo-title"><span>'. ($lang_pl ? $formatted_title_pl : $formatted_title_en) .'</span></div>
                                    </div>';
                                }
                            $output .= '
                            </div>
                        </div>';
                    }

                    // Footer section
                    $output .= '
                    <div class="single-event__container-footer" itemprop="location" itemscope itemtype="https://schema.org/Place">
                        <div class="single-event__footer-ptak-logo">
                            <meta itemprop="name" content="Ptak Warsaw Expo">
                            <meta itemprop="telephone" content="'. get_post_meta($post_id, 'organizer_phone', true) .'">';
                            if ($organizer === "warsaw") {
                                $output .= '
                                <img class="wp-image-95078 ptak-logo-item" src="https://warsawexpo.eu/wp-content/plugins/pwe-media/media/logo_pwe_black.png" width="155" height="135" alt="logo ptak">';
                            }  else if (strpos(mb_strtolower($organizer), "łódź") !== false) {
                                $output .= '
                                <img class="wp-image-95078 ptak-logo-item" src="https://warsawexpo.eu/wp-content/plugins/pwe-media/media/ptak-expo-lodz-logo.webp" width="155" height="135" alt="logo lódź">';
                            }
                        $output .= '
                        </div>
                        <div class="single-event__footer-content">
                            <div class="single-event__footer-ptak-adress" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                <div>';
                                if ($organizer === "warsaw") {
                                    $output .= '
                                    <p><span itemprop="streetAddress">Al. Katowicka 62</span></p>
                                    <p><span itemprop="postalCode">05-830</span><span itemprop="addressLocality"> Nadarzyn, '. ($lang_pl ? "Polska" : "Poland") .'</span></p>';
                                } else if (strpos(mb_strtolower($organizer), "łódź") !== false) {
                                    $output .= '
                                    <p><span itemprop="streetAddress">ul. Tuszyńska 72/74</span></p>
                                    <p><span itemprop="postalCode">95-030</span><span itemprop="addressLocality"> Rzgów k. Łodzi, '. ($lang_pl ? "Polska" : "Poland") .'</span></p>';
                                }
                                $output .= '
                                </div>
                            </div>
                            <div class="single-event__footer-ptak-contact" itemscope itemtype="https://schema.org/Organization">
                                <meta itemprop="name" content="Ptak Warsaw Expo">
                                <meta itemprop="description" content="Największe centrum targowo-kongresowe oraz lider organizacji targów w Europie Środkowej.">
                                <meta itemprop="url" content="https://warsawexpo.eu">
                                <div>';
                                if(!empty(get_post_meta($post_id, 'organizer_phone', true))) {
                                    $output .= '
                                    <p>
                                        <a class="color-accent"  href="tel:'. get_post_meta($post_id, 'organizer_phone', true) .'">
                                            <span itemprop="telephone">'. get_post_meta($post_id, 'organizer_phone', true) .'</span>
                                        </a>
                                    </p>';
                                }
                                if(!empty(get_post_meta($post_id, 'organizer_email', true))) {
                                    $output .= '
                                    <p>
                                        <a class="color-accent" href="mailto:'. get_post_meta($post_id, 'organizer_email', true) .'">
                                            <span  itemprop="email">'. get_post_meta($post_id, 'organizer_email', true) .'</span>
                                        </a>
                                    </p>';
                                }
                                $output .= '
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
            jQuery(function ($) {
                const slickSliders = $(".single-event__logotypes-slider");

                function initializeSlick(slider) {
                    const totalSlides = $(slider).children().length;
                    const currentSlidesToShow = getInitialSlidesToShow($(slider));

                    // Check if the slider is already initialized, if so reset it
                    if ($(slider).hasClass("slick-initialized")) {
                        $(slider).slick("unslick");
                    }

                    // Initialize Slick Slider for a given element
                    $(slider).slick({
                        infinite: true,
                        slidesToShow: currentSlidesToShow,
                        slidesToScroll: 1,
                        arrows: false,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        dots: false,
                        cssEase: "linear",
                        swipeToSlide: true,
                    }).on("init reInit afterChange", function (event, slick, currentSlide) {
                        updateCaption(this);
                    });

                    // Set the first single-event__stats-caption after initialization
                    $(slider).on("init", function () {
                        updateCaption(this);
                    });
                }

                function getInitialSlidesToShow(slider) {
                    const elementWidth = $(slider).width();
                    if ($(slider).hasClass("single-event__conferences-logotypes")) {
                        return 1;
                    } else {
                        return elementWidth < 480 ? 2 :
                            elementWidth < 768 ? 3 :
                            elementWidth < 960 ? 4 : 5;
                    }
                }

                function updateSlickSettings(slider) {
                    initializeSlick(slider);
                }

                function updateCaption(slider) {
                    const currentSlide = $(slider).find(".slick-current img");
                    const captionText = currentSlide.attr("alt") || "";
                    $(slider).closest(".single-event__tiles-right-bottom-right").find(".single-event__single-event__stats-caption").text(captionText);
                }

                // Initialize each slider separately
                slickSliders.each(function () {
                    const slider = this;
                    updateSlickSettings(slider);

                    // Size observer for each slider
                    const resizeObserver = new ResizeObserver(() => {
                        updateSlickSettings(slider);
                    });

                    resizeObserver.observe(slider);
                });

                // Function to set equal height
                function setEqualHeight() {
                    let maxHeight = 0;

                    // Reset the heights before calculations
                    $(".single-event__events-logo").css("height", "auto");

                    // Calculate the maximum height
                    $(".single-event__events-logo").each(function() {
                        const thisHeight = $(this).outerHeight();
                        if (thisHeight > maxHeight) {
                            maxHeight = thisHeight;
                        }
                    });

                    // Set the same height for all
                    $(".single-event__events-logo").css("minHeight", maxHeight);
                }

                // Call the function after loading the slider
                $(".single-event__events-logotypes").on("init", function() {
                    setEqualHeight();
                });

                // Call the function when changing the slide
                $(".single-event__events-logotypes").on("afterChange", function() {
                    setEqualHeight();
                });

                // Call the function at the beginning
                setEqualHeight();

                function animateCount(element) {
                    const targetValue = parseInt(element.getAttribute("data-count"), 10);
                    const duration = 3000;

                    const startTime = performance.now();
                    const update = (currentTime) => {
                        const elapsedTime = currentTime - startTime;
                        const progress = Math.min(elapsedTime / duration, 1);
                        const currentValue = Math.floor(progress * targetValue);

                        element.textContent = currentValue;

                        if (progress < 1) {
                            requestAnimationFrame(update);
                        }
                    };
                    requestAnimationFrame(update);
                }

                const countUpElements = document.querySelectorAll(".countup");

                const observer = new IntersectionObserver(
                    (entries, observerInstance) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const target = entry.target;

                                if (target.classList.contains("countup")) {
                                    animateCount(target);
                                } else if (!target.dataset.animated) {
                                    animateBars(target);
                                    target.dataset.animated = true;
                                }

                                observerInstance.unobserve(target);
                            }
                        });
                    },
                    {
                        threshold: 0.1
                    }
                );

                countUpElements.forEach(element => observer.observe(element));

            });
        </script>';

    endwhile;
}

// $output .= '
// <script>
//     document.addEventListener("DOMContentLoaded", function() {
//         const url = new URL(window.location);
//         const domain = "'. $domain .'";
//         url.searchParams.set("domain", domain);
//         window.history.pushState({}, "", url);
//     });
// </script>';

echo do_shortcode($output);

get_footer();
?>