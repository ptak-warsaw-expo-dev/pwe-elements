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
                        "desc"       => $event_desc,
                        "category"   => $lang_pl ? $fair['category_pl'] : $fair['category_en'],
                        "visitors"   => isset($fair['visitors']) ? $fair['visitors'] : null,
                        "exhibitors" => isset($fair['exhibitors']) ? $fair['exhibitors'] : null,
                        "area"       => isset($fair['area']) ? $fair['area'] : null,
                        "catalog"    => isset($fair['catalog']) ? $fair['catalog'] : ''
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

    while (have_posts()):
        the_post();

        $output .= '
        <div data-parent="true" class="vc_row row-container boomapps_vcrow '. $title .'" data-section="21" itemscope itemtype="http://schema.org/Event">
            <div class="single-event" data-imgready="true">
                <div class="single-event__wrapper">';

                    $output .= '
                    <div id="singleEventHeader" class="single-event__header">
                        <div class="single-event__header-stripes">';

                        foreach ($all_events_json as $event) {
                            $domain = $event['domain'];
                            $name   = $event['name'];

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
                            <div class="single-event__stats-text">
                                <h3>'. ($lang_pl ? "Statystyki wydarzeń" : "Event statistics") .'</h3>
                                <p>'. ($lang_pl ? "Łączne przedstawienie kluczowych kategorii, które definiują skalę wydarzenia" : "A combined representation of the key categories that define the scale of the event") .'</p>
                            </div>
                            <div class="single-event__stats-diagram" id="diagram-block">
                                <div class="single-event__stats-diagram-number">
                                <span class="single-event__stats-count-up" data-target="'. $exhibitors_num .'">0</span>
                                <div class="single-event__stats-caption">'. ($lang_pl ? "Wystawców" : "Exhibitors") .'</div>
                                </div>
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
                                </div>

                            </div>
                            <div class="single-event__stats-numbers">
                                <div class="single-event__stats-numbers-row">
                                <span class="single-event__stats-dot">
                                    <img src="/wp-content/plugins/PWElements/includes/calendar/assets/view-icon.png">
                                </span>
                                <div>
                                    <span class="single-event__stats-count-up" data-target="'. $visitors_num .'">0</span>
                                    <div class="single-event__stats-caption">'. ($lang_pl ? "Odwiedzających" : "Visitors") .'</div>
                                </div>
                                </div>
                                <div class="single-event__stats-numbers-row">
                                <span class="single-event__stats-dot">
                                    <img src="/wp-content/plugins/PWElements/includes/calendar/assets/globus-icon.png">
                                </span>
                                <div>
                                    <span class="single-event__stats-count-up" data-target="'. $visitors_foreign_num .'">0</span>
                                    <div class="single-event__stats-caption">'. ($lang_pl ? "W tym z zagranicy" : "Including from abroad") .'</div>
                                </div>
                                </div>
                                <div class="single-event__stats-numbers-row">
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
                    const percent = 0.'. $increase_percent .'; // '. $increase_percent .'%
                    const pathLength = arc.getTotalLength();
                    arc.style.strokeDasharray = `0,${pathLength}`;
                    setTimeout(() => {
                        arc.style.transition = "stroke-dasharray 1.2s cubic-bezier(.7,.03,.48,1.01)";
                        arc.style.strokeDasharray = `${pathLength * percent},${pathLength}`;
                    }, 200);
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
                        <div class="single-event__container-events">
                            <div class="single-event__events-title">
                                <h4>'. multi_translation("most_important_industry_events_in_europe") .'</h4>
                            </div>
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