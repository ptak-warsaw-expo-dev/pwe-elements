<?php

class PWECalendar extends PWECommonFunctions {

    public function __construct() {
        // Hook actions
        add_action('init', array($this, 'init_vc_map_pwe_calendar'));
        add_action('wp_ajax_load_more_calendar', [$this, 'load_more_calendar']);
        add_action('wp_ajax_nopriv_load_more_calendar', [$this, 'load_more_calendar']);

        add_shortcode('pwe_calendar', array($this, 'pwe_calendar_loop_output'));
    }

    /**
    * Initialize VC Map PWECalendar.
    */
    public function init_vc_map_pwe_calendar() {

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Calendar', 'pwe_calendar'),
                'base' => 'pwe_calendar',
                'category' => __( 'PWE Elements', 'pwe_calendar'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'params' => array_merge(
                    array(
                        array(
                            'type' => 'textfield',
                            'heading' => __('Posts display limit', 'pwe_calendar'),
                            'param_name' => 'pwe_calendar_posts_num',
                            'param_holder_class' => 'backend-area-one-fifth-width',
                            'admin_label' => true,
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Posts per page', 'pwe_calendar'),
                            'param_name' => 'pwe_calendar_posts_per_page',
                            'param_holder_class' => 'backend-area-one-fifth-width',
                            'admin_label' => true,
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Warsaw week ONLY', 'pwe_calendar'),
                            'param_name' => 'pwe_calendar_week',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(__('True', 'pwe_calendar') => 'true',),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('AJAX Load more', 'pwe_calendar'),
                            'param_name' => 'pwe_calendar_load_more',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(__('True', 'pwe_calendar') => 'true',),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('AJAX Pagination', 'pwe_calendar'),
                            'param_name' => 'pwe_calendar_pagination',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(__('True', 'pwe_calendar') => 'true',),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Premier edition only', 'pwe_calendar'),
                            'param_name' => 'pwe_calendar_premier_edition',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(__('True', 'pwe_calendar') => 'true',),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('Hide filter', 'pwe_calendar'),
                            'param_name' => 'pwe_calendar_hide_filter',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(__('True', 'pwe_calendar') => 'true',),
                        ),
                    ),
                ),
            ));
        }
    }

    public static function get_pwe_shortcode($shortcode, $domain) {
        return shortcode_exists($shortcode) ? do_shortcode('[' . $shortcode . ' domain="' . $domain . '"]') : "";
    }

    public static function check_available_pwe_shortcode($shortcodes_active, $shortcode) {
        return $shortcodes_active && !empty($shortcode) && $shortcode !== "";
    }

    public static function format_date_range($start_date, $end_date, $locale) {
        $months = json_decode(file_get_contents(__DIR__ . '/../assets/months.json'), true);

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

    public function pwe_calendar_loop_output($atts) {
        extract(shortcode_atts(array(
            'pwe_calendar_posts_num' => '',
            'pwe_calendar_posts_per_page' => '',
            'pwe_calendar_week' => '',
            'pwe_calendar_load_more' => '',
            'pwe_calendar_pagination' => '',
            'pwe_calendar_premier_edition' => '',
            'pwe_calendar_hide_filter' => '',
        ), $atts));

        $pwe_calendar_posts_num = !empty($pwe_calendar_posts_num) ? $pwe_calendar_posts_num : 0;

        $lang = ICL_LANGUAGE_CODE;

        // Creating a query for 'event' posts
        $args = array(
            'post_type' => array('event', 'events_week'),
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'lang' => $lang
        );

        $query = new WP_Query($args);

        // var_dump($query);

        $terms = get_terms(array(
            'taxonomy' => 'event_category',
            'hide_empty' => false,
        ));

        if (!is_wp_error($terms) && !empty($terms)) {
            $all_categories = array();
            foreach ($terms as $term) {
                $all_categories[] = array(
                    'name' => $term->name,
                    'slug' => $term->slug
                );
            }
        }

        if ($query->have_posts()) :

            $thumbnail_url = '';
            $lang_pl = get_locale() == "pl_PL";
            $shortcodes_active = empty(get_option('pwe_general_options', [])['pwe_dp_shortcodes_unactive']);

            $output = '
            <style>
                .pwe-calendar__wrapper {
                    max-width: 1400px;
                    margin: 0 auto;
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 24px;
                    margin-top: 36px;
                }
                .pwe-calendar__item {
                    background-color: #e9e9e9;
                    text-align: -webkit-center;
                    border-radius: 30px;
                    box-shadow: unset;
                    transition: .3s ease;
                }
                .pwe-calendar__item:hover {
                    transform: scale(1.05);
                }
                .pwe-calendar__wrapper:after {
                    content:none !important;
                }
                .pwe-calendar__link {
                    position: relative;
                    z-index: 1;
                    text-decoration: none;
                }
                .pwe-calendar__tile {
                    position: relative;
                    aspect-ratio: 1 / 1;
                    background-size: cover;
                    background-position: center;
                    border-radius: 0;
                    border-top-left-radius: 15px;
                    border-top-right-radius: 15px;
                    display: flex;
                    align-items: flex-end;
                    justify-content: center;
                }
                .pwe-calendar__short-name {
                    position: absolute;
                    top: 65%;
                    left: 50%;
                    transform: translate(-50%, -65%);
                    z-index: 1;
                    width: 300px;
                    max-width: 90%;
                }
                .pwe-calendar__short-name h4 {
                    color: white !important;
                    font-size: 17px;
                    font-weight: 600;
                    text-shadow: 0.1em 0.1em 0.2em black, 0.1em 0.1em 0.2em black, 0.1em 0.1em 0.2em black;
                    text-transform: uppercase;
                    text-align: center;
                }
                .pwe-calendar__statistics-word {
                    font-size: 13px;
                    color: grey;
                    margin: 0;
                    font-weight: 600;
                }
                .pwe-calendar_strip {
                    width: 100%;
                    display: flex;
                    align-items: center;
                    margin: 6px;
                }
                .pwe-calendar__button-check {
                    width: 40%;
                }
                .pwe-calendar__button-check p {
                    width: fit-content;
                    line-height: 1;
                    color: white;
                    font-size: 12px;
                    font-weight: 700;
                    margin: 0;
                    text-transform: uppercase;
                    background-color: grey;
                    padding: 9px;
                    border-radius: 12px;
                }
                .pwe-calendar__edition {
                    width: 60%;
                }
                .pwe-calendar__edition p {
                    margin: 0 0 0 2px;
                    line-height: 1;
                    color: white;
                    font-size: 15px;
                    font-weight: 700;
                    text-transform: uppercase;
                }
                .pwe-calendar__date {
                    padding: 6px;
                }
                .pwe-calendar__date h5 {
                    margin: 0;
                    line-height: 1.2;
                    font-weight: 700;
                    text-transform: uppercase;
                    display: flex;
                    justify-content: space-evenly;
                }
                .pwe-calendar_statistics {
                    padding: 10px;
                }
                .pwe-calendar__statistics-item {
                    display: flex;
                    justify-content: space-between;
                }
                .pwe-calendar__statistics-name {
                    width: 76%;
                    display: flex;
                    justify-content: space-between;
                    gap: 10px;
                }
                .pwe-calendar__statistics-name p {
                    margin: 0;
                    line-height: 1;
                    text-align: start;
                }
                .pwe-calendar__statistics-icon {
                    width: 20%;
                }
                .pwe-calendar__statistics-icon img {
                    max-width: 30px;
                    vertical-align: middle;
                }
                .pwe-calendar__statistics-label {
                    width: 65%;
                    font-size: 14px;
                    margin: 0;
                    line-height: 1;
                    color: black;
                }
                .pwe-calendar__statistics-value {
                    width: 35%;
                    font-size: 14px;
                    color: grey;
                    margin: 0;
                    font-weight: 800;
                    display: flex;
                    white-space: nowrap;
                    align-items: center;
                }
                @media (min-width: 960px){
                    .row-parent:has(.pwe-calendar__item){
                        max-width: unset !important;
                    }
                }
                @media (max-width: 1200px){
                    .pwe-calendar__wrapper {
                        grid-template-columns: repeat(3, 1fr);
                        gap: 18px;
                    }
                }
                @media (max-width: 960px){
                    .pwe-calendar__short-name h4 {
                        font-size: 14px;
                    }
                    .pwe-calendar__item:hover {
                        transform: scale(1.02);
                    }
                }
                @media (max-width: 768px) {
                    .pwe-calendar__wrapper {
                        grid-template-columns: repeat(2, 1fr);
                    }
                }
                @media (max-width: 569px) {
                    .main-container .row-parent:has(.pwe-calendar__item) {
                        padding: 10px;
                    }
                    .pwe-calendar__wrapper {
                        gap: 10px;
                    }
                    .pwe-calendar__short-name h4 {
                        font-size: 12px;
                    }
                    .pwe-calendar__date h5 {
                        font-size: 15px;
                    }
                    .pwe-calendar_strip {
                        width: 100%;
                        margin: 0;
                        height: 20%;
                    }
                    .pwe-calendar__button-check p,
                    .pwe-calendar__edition p {
                        font-size: 10px !important;
                        padding: 4px !important;
                    }
                    .pwe-calendar_statistics {
                        display: flex;
                        flex-direction: column;
                        gap: 6px;
                    }
                    .pwe-calendar__statistics-name {
                        flex-direction: column;
                        gap: 5px;
                    }
                }







                .week .pwe-calendar__short-name {
                    top: 50%;
                }
                .week .pwe-calendar__short-name h4 {
                    margin: 0;
                }
                .week .pwe-calendar__count-events {
                    width: 60%;
                }
                .week .pwe-calendar__count-events p {
                    font-size: 14px;
                    margin: 0;
                    color: white;
                    font-weight: 700;
                    text-transform: uppercase;
                }
                .week .pwe-calendar_info p {
                    color: black;
                    margin: 0;
                }
                .week .pwe-calendar_info-list {
                    text-align: left;
                    padding: 0px 12px 12px !important;
                    margin: 0;
                }
                .week .pwe-calendar_info-list li {
                    color: black;
                    line-height: 1.4;
                    font-size: 14px;
                    list-style: none;
                }
                @media (max-width: 569px) {
                    .week .pwe-calendar_info-list {
                        max-height: 150px;
                        overflow: scroll;
                    }
                }
            </style>
            ';

            $event_posts = [];

            while ($query->have_posts()) : $query->the_post();
                $post_id = get_the_ID();
                $permalink = get_the_permalink();

                if ($post_id) {
                    $website = get_post_meta($post_id, 'web_page_link', true);
                    $host = parse_url($website, PHP_URL_HOST);
                    $domain = preg_replace('/^www\./', '', $host);
                    $categories = get_the_terms($post_id, 'event_category');
                } else {
                    $domain = '';
                }

                $current_time = strtotime("now");

                $pwe_db_date_start = do_shortcode('[pwe_date_start domain="' . $domain . '"]');
                $pwe_db_date_end = do_shortcode('[pwe_date_end domain="' . $domain . '"]');
                $pwe_db_date_start_available = $shortcodes_active && !empty($pwe_db_date_start) && $pwe_db_date_start !== "";
                $pwe_db_date_end_available = $shortcodes_active && !empty($pwe_db_date_end) && $pwe_db_date_end !== "";

                $start_date = $pwe_db_date_start_available ? date("d-m-Y", strtotime(str_replace("/", "-", $pwe_db_date_start))) : get_post_meta($post_id, 'fair_date_start', true);
                $end_date = $pwe_db_date_end_available ? date("d-m-Y", strtotime(str_replace("/", "-", $pwe_db_date_end))) : get_post_meta($post_id, 'fair_date_end', true);

                $start_date = (empty($start_date) || (!empty($end_date) && strtotime($end_date . " +20 hours") < $current_time)) ? "28-01-2050" : $start_date;
                $end_date = (empty($end_date) || (!empty($end_date) && strtotime($end_date . " +20 hours") < $current_time)) ? "30-01-2050" : $end_date;

                $shortcode_edition = self::get_pwe_shortcode("pwe_edition", $domain);
                $shortcode_edition_available = self::check_available_pwe_shortcode($shortcodes_active, $shortcode_edition);
                $edition_num = $shortcode_edition_available ? $shortcode_edition : get_post_meta($post_id, 'edition', true);

                $event_type = get_post_meta($post_id, 'pwe_event_type', true);


                // Add only posts with edition_num == 1 if $pwe_calendar_premier_edition is true
                if ($pwe_calendar_premier_edition == true && $edition_num == 1) {
                    $event_posts[] = [
                        'post_id'     => $post_id,
                        'event_type'  => $event_type,
                        'edition_num' => $edition_num,
                        'start_date'  => $start_date,
                        'end_date'    => $end_date,
                        'domain'      => $domain,
                        'permalink'   => $permalink,
                        'categories'  => $categories,
                        'post_title'  => get_the_title(),
                    ];
                } elseif ($pwe_calendar_premier_edition == false) {
                    $event_posts[] = [
                        'post_id'     => $post_id,
                        'event_type'  => $event_type,
                        'edition_num' => $edition_num,
                        'start_date'  => $start_date,
                        'end_date'    => $end_date,
                        'domain'      => $domain,
                        'permalink'   => $permalink,
                        'categories'  => $categories,
                        'post_title'  => get_the_title(),
                    ];
                }

            endwhile;

            wp_reset_postdata();

            $event_posts_full = $event_posts;

            // Sorting by date first, then category, then week before event
            usort($event_posts, function ($a, $b) {

                // 1. SORT BY DATE
                $dateA = DateTime::createFromFormat('d-m-Y', $a['start_date']);
                $dateB = DateTime::createFromFormat('d-m-Y', $b['start_date']);

                $cmpDate = $dateA <=> $dateB;
                if ($cmpDate !== 0) {
                    return $cmpDate;
                }

                // 2. WEEK FIRST (within same date)
                $isWeekA = ($a['event_type'] === 'week') ? 0 : 1;
                $isWeekB = ($b['event_type'] === 'week') ? 0 : 1;

                $cmpWeek = $isWeekA <=> $isWeekB;
                if ($cmpWeek !== 0) {
                    return $cmpWeek;
                }

                // 3. SORT BY EDITION NUMBER (DESC)
                $editionA = !empty($a['edition_num']) ? (int) $a['edition_num'] : 0;
                $editionB = !empty($b['edition_num']) ? (int) $b['edition_num'] : 0;

                return $editionB <=> $editionA;
            });



            if (!empty($pwe_calendar_posts_num) && $pwe_calendar_posts_num > 0) {
                $event_posts = array_slice($event_posts, 0, $pwe_calendar_posts_num);
            }

            if ($pwe_calendar_hide_filter != true) {
                $output .= '
                <style>
                    .pwe-calendar__filter {
                        display: flex;
                        position: relative;
                        flex-wrap: wrap;
                        max-width: 1200px;
                        margin: 0 auto;
                    }
                    .pwe-calendar__filter div {
                        width: 50%;
                        padding: 0 5px;
                    }
                    .dont-show {
                        display: none;
                    }
                    .pwe-calendar__categories-dropdown {
                        width: 100%;
                    }
                    .pwe-calendar__filter input {
                        margin-top: 0 !important;
                    }
                    .pwe-calendar__filter input::placeholder {
                        color: white;
                    }
                    .pwe-calendar__categories-dropdown,
                    .pwe-calendar__filter input {
                        background: #1d1f24;
                        font-size: 18px;
                        width: 100%;
                        border: none;
                        color: #fff;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 0.7em 1.5em;
                        border-radius: 0.5em;
                        cursor: pointer;
                    }
                    .pwe-calendar__categories-dropdown-arrow {
                        border-left: 5px solid transparent;
                        border-right: 5px solid transparent;
                        border-top: 6px solid #fff;
                        transition: transform ease-in-out 0.3s;
                    }

                    .pwe-calendar__categories-dropdown-content {
                        margin: 5px 0px 0px 0px;
                        display: flex;
                        flex-direction: column;
                        flex-wrap: nowrap;
                        z-index: 1000;
                        overflow: hidden;
                        background: white;
                        list-style: none !important;
                        position: absolute;
                        top: 3.2em;
                        width: 49%;
                        padding: 0 !important;
                        visibility: hidden;
                        opacity: 0;
                        border-radius: 8px;
                    }
                    .pwe-calendar__categories-dropdown-content li {
                        cursor: pointer;
                        padding: 0px 0px 0px 0px;
                        color: white;
                        margin: 2px;
                        text-align: center;
                        background: #2f3238;
                        border-radius: 0.4em;
                        position: relative;
                        left: 100%;
                        transition: 0.4s;
                        transition-delay: calc(30ms * var(--delay));
                        font-size: 17px;
                    }
                    .dropdown-delay {
                        transition: 0.4s;
                        transition-delay: calc(30ms * 9);
                    }
                    .pwe-calendar__categories-dropdown-content.menu-open li {
                        left: 0;
                    }
                    .pwe-calendar__categories-dropdown-content.menu-open {
                        visibility: visible;
                        opacity: 1;
                    }
                    .pwe-calendar__categories-dropdown-arrow.arrow-rotate {
                        transform: rotate(180deg);
                    }
                    .pwe-calendar__categories-dropdown-content li:hover {
                        background: #1d1f24;
                    }
                    .pwe-calendar__categories-dropdown-content li a {
                        display: block;
                        padding: 0.7em 0.5em;
                        color: #fff;
                        margin: 0.1em 0;
                        text-decoration: none;
                    }
                    @media (max-width:800px) {
                        .pwe-calendar__categories-dropdown-content {
                            max-height: 2000px;
                            width: 100%;
                        }
                        .pwe-calendar__categories-dropdown-content li {
                            transition-delay: 0.2s;
                            transition-delay: calc(20ms * var(--delay));
                        }
                        .pwe-calendar__filter div {
                            width: 100%;
                            padding: 0;
                            margin: 5px 0;
                        }
                    }
                    .pwe-calendar__categories-dropdown-content .all {
                        background-color: #594334;
                        font-size: 21px;
                    }
                </style>

                <div class="pwe-calendar__filter">
                    <div class="pwe-calendar__categories-dropdown dropdown">
                        <button id="dropdownBtn" class="pwe-calendar__categories-dropdown dropdown-btn" aria-label="menu button" aria-haspopup="menu" aria-expanded="false" aria-controls="dropdown-menu">
                            <span>'. self::multi_translation("select_categories") .'</span>
                            <span class="pwe-calendar__categories-dropdown-arrow arrow"></span>
                        </button>
                        <ul class="pwe-calendar__categories-dropdown-content dropdown-content" role="menu" id="dropdown-menu"></ul>
                    </div>
                    <div class="pwe-calendar__search">
                        <input type="text" id="searchInput" placeholder="'. self::multi_translation("search") .'" />
                    </div>
                </div>';
            }

            $startMemory = memory_get_usage();

            $output .= '<div class="pwe-calendar__wrapper">';

                ob_start();

                foreach ($event_posts as $event) {
                    if ($pwe_calendar_week && $event['event_type'] !== "week") {
                        continue;
                    }
                    $output .= self::render_calendar_event_card($event, $shortcodes_active, $lang_pl);
                }

                $output .=
                ob_get_clean();

                wp_reset_postdata();

            $output .= '</div>';

            $endMemory = memory_get_usage();

            if (current_user_can('administrator')) {
                echo '<script>console.log("Calendar memory size loop - '. ($endMemory - $startMemory) / 1024 .'kb")</script>';
            }

            // Add load more button and script if needed
            if ($pwe_calendar_load_more && !empty($pwe_calendar_posts_num) && (count($event_posts_full) > $pwe_calendar_posts_num)) {
                $output .= '
                <div class="load-more-btn-container" style="text-align: center; margin-top: 36px;">
                    <button
                        id="loadMore"
                        data-page="2"
                        class="load-more"
                        style="text-transform: uppercase; background-color: var(--main2-color); border-color: var(--main2-color); border-radius: 10px; color: white; padding: 8px 14px; transition: .3s ease; transform: scale(1);">
                        ' . ($lang_pl ? "Załaduj więcej" : "Load more") . '
                    </button>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        let loadMoreBtn = document.getElementById("loadMore");

                        if (loadMoreBtn) {
                            loadMoreBtn.addEventListener("click", function () {
                                let button = this;
                                button.innerText = "'. ($lang_pl ? "Ładowanie..." : "Loading...") .'";
                                let page = button.getAttribute("data-page");

                                let xhr = new XMLHttpRequest();
                                xhr.open("POST", "/wp-admin/admin-ajax.php", true); // Użyj admin-ajax.php
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                                xhr.onload = function () {
                                    if (xhr.status === 200) {
                                            let response = xhr.responseText;
                                            if (response.trim()) {
                                                document.querySelector(".pwe-calendar__wrapper").insertAdjacentHTML("beforeend", response);
                                                button.setAttribute("data-page", parseInt(page) + 1);
                                                button.innerText = "'. ($lang_pl ? "Załaduj więcej" : "Load more") .'";
                                            } else {
                                                button.style.display = "none";
                                            }
                                    }
                                };

                                xhr.send("action=load_more_calendar&page=" + page);
                            });
                        }
                    });
                </script>';
            } else if ($pwe_calendar_pagination && !empty($pwe_calendar_posts_num) && (count($event_posts_full) > $pwe_calendar_posts_num)) {
                $output .= '
                <style>
                    .pwe-pagination-container {
                        position: relative;
                    }
                    .pwe-pagination {
                        display: flex;
                        justify-content: center;
                        gap: 6px;
                    }
                    .page-btn, .prev-btn, .next-btn {
                        background: inherit;
                        font-size: 18px;
                    }
                    .page-btn.active {
                        background: var(--main2-color);
                        color: white;
                        padding: 6px 12px;
                        border-radius: 8px;
                    }
                    .pwe-pagination-loading {
                        visibility: hidden;
                        position: absolute;
                        bottom: -8px;
                        left: 50%;
                        right: 50%;
                        transform: translate(-50%, -50%);
                        width: 200px;
                        height: 1px;
                        z-index: 9999;
                    }
                    .pwe-pagination-loading .loading-bar {
                        width: 0%;
                        height: 100%;
                        background-color: var(--main2-color);
                        position: absolute;
                        left: 50%;
                        transform: translateX(-50%);
                    }
                </style>

                <div class="pwe-pagination-container">
                    <div id="pwePagination" class="pwe-pagination"></div>
                    <div class="pwe-pagination-loading">
                        <div class="loading-bar"></div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // Reading the "calendar-page" parameter from the URL at the beginning
                        let urlParams = new URLSearchParams(window.location.search);
                        let currentCalendarPage = parseInt(urlParams.get("calendar-page")) || 1;

                        // Set the page if there is a "calendar-page" parameter in the URL
                        loadPosts(currentCalendarPage);

                        // Listening for changes in the URL (e.g. when the user clicks the "Back" button in browsers)
                        window.addEventListener("popstate", function() {
                            let urlParams = new URLSearchParams(window.location.search);
                            let calendarP = parseInt(urlParams.get("calendar-page")) || 1;
                            loadPosts(calendarP); // Loading the appropriate page
                        });

                        // Function to load posts
                        function loadPosts(calendarP) {
                            // Get the loading bar element outside the condition so its accessible throughout the function
                            let loadingBar = document.querySelector(".pwe-pagination-loading");

                            // Show loading bar before sending AJAX request
                            if (loadingBar) {
                                loadingBar.style.visibility = "visible"; // Display the loading bar
                                let loadingBarElement = loadingBar.querySelector(".loading-bar");
                                loadingBarElement.style.width = "0%"; // Reset width before starting the animation

                                // Animation of expanding the bar
                                let width = 0;
                                let interval = setInterval(function () {
                                    if (width >= 100) {
                                        clearInterval(interval);
                                    } else {
                                        width += 2; // Increase the width of the bar by 2% every 20ms
                                        loadingBarElement.style.width = width + "%";
                                    }
                                }, 20);
                            }

                            let xhr = new XMLHttpRequest();
                            xhr.open("POST", "/wp-admin/admin-ajax.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                            xhr.onload = function () {
                                if (xhr.status === 200) {
                                    let response = xhr.responseText;
                                    if (response.trim()) {
                                        // Update posts
                                        document.querySelector(".pwe-calendar__wrapper").innerHTML = response;

                                        // Update pagination based on current page
                                        updatePagination(calendarP);

                                        // Recreate URLSearchParams after page load or URL change
                                        let urlParams = new URLSearchParams(window.location.search);

                                        if (loadingBar) {
                                            setTimeout(function () {
                                                loadingBar.style.visibility = "hidden";
                                            }, 300);
                                        }

                                        if (urlParams.size != 0) {
                                            smoothScrollTo(document.querySelector(".pwe-calendar"), 300);
                                            setTimeout(function () {
                                                window.scrollBy(0, -150);
                                            }, 300);
                                        }
                                    }
                                }
                            };

                            xhr.send("action=load_more_calendar&calendar-page=" + calendarP + "&posts-per-page=" + '. $pwe_calendar_posts_per_page .');
                        }

                        // Function to update pagination buttons
                        function updatePagination(currentCalendarPage) {
                            let pagination = document.getElementById("pwePagination");
                            pagination.innerHTML = ""; // Clear existing pagination

                            let totalPages = parseInt(' . ceil(count($event_posts_full) / $pwe_calendar_posts_num) . '); // Calculate the number of pages
                            let maxPages = 5; // Maximum number of page buttons
                            let startPage = Math.max(1, currentCalendarPage - 1); // Calculate the starting page
                            let endPage = Math.min(totalPages, currentCalendarPage + 1); // Calculate the ending page

                            let paginationHTML = "";

                            // Show "Previous" button
                            paginationHTML += `<button class="prev-btn" data-calendar-page=" + (currentCalendarPage - 1) + ">‹</button>`;

                            // Show first page if its not visible
                            if (startPage > 2) {
                                paginationHTML += `<button class="page-btn" data-calendar-page="1">1</button>`;
                                paginationHTML += `<span class="dots">...</span>`;
                            }

                            // Show pages between the first page and the last page
                            for (let i = startPage; i <= endPage; i++) {
                                if (i === currentCalendarPage) {
                                    paginationHTML += `<button class="page-btn active" data-calendar-page="${i}">${i}</button>`;
                                } else {
                                    paginationHTML += `<button class="page-btn" data-calendar-page="${i}">${i}</button>`;
                                }
                            }

                            // Show last page if its not visible
                            if (endPage < totalPages - 1) {
                                paginationHTML += `<span class="dots">...</span>`;
                                paginationHTML += `<button class="page-btn" data-calendar-page="${totalPages}">${totalPages}</button>`;
                            }

                            // Show "Next" button
                            paginationHTML += `<button class="next-btn" data-calendar-page=" + (currentCalendarPage + 1) + ">›</button>`;

                            pagination.innerHTML = paginationHTML;

                             // Event listener for pagination buttons
                            pagination.querySelectorAll(".page-btn").forEach(btn => {
                                btn.addEventListener("click", function () {
                                    let calendarP = parseInt(this.getAttribute("data-calendar-page"));

                                    // Otherwise, update the URL with the selected page
                                    history.replaceState(null, null, "?calendar-page=" + calendarP);

                                    // Loading posts for the selected page
                                    loadPosts(calendarP);
                                });
                            });

                            // Event listener for previous/next buttons
                            pagination.querySelector(".prev-btn").addEventListener("click", function () {
                                if (currentCalendarPage > 1) {
                                    history.replaceState(null, null, "?calendar-page=" + (currentCalendarPage - 1));
                                    // Loading previous page
                                    loadPosts(currentCalendarPage - 1);
                                }
                            });

                            pagination.querySelector(".next-btn").addEventListener("click", function () {
                                if (currentCalendarPage < totalPages) {
                                    history.replaceState(null, null, "?calendar-page=" + (currentCalendarPage + 1));
                                    // Loading next page
                                    loadPosts(currentCalendarPage + 1);
                                }
                            });
                        }

                        // Function to smoothly scroll to an element with a specified duration
                        function smoothScrollTo(element, duration) {
                            let startPosition = window.pageYOffset;
                            let targetPosition = element.getBoundingClientRect().top + window.pageYOffset;
                            let distance = targetPosition - startPosition;
                            let startTime = null;

                            function animation(currentTime) {
                                if (startTime === null) startTime = currentTime;
                                let timeElapsed = currentTime - startTime;
                                let run = easeInOut(timeElapsed, startPosition, distance, duration);
                                window.scrollTo(0, run);
                                if (timeElapsed < duration) requestAnimationFrame(animation);
                            }

                            function easeInOut(t, b, c, d) {
                                let ts = (t /= d / 2) < 1 ? c / 2 * t * t + b : -c / 2 * (--t * (t - 2) - 1) + b;
                                return ts;
                            }

                            requestAnimationFrame(animation);

                        }
                    });
                </script>';
            }

        endif;

        if ($pwe_calendar_hide_filter != true) {
            $output .= '
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    function replacePL(text) {
                        return decodeURIComponent(text.replace(/\+/g, " ")).replace(/-/g, " ");
                    }

                    const dropdownBtn = document.querySelector(".dropdown-btn");
                    const dropdownContent = document.querySelector(".dropdown-content");
                    const dropdownMenu = document.getElementById("dropdown-menu");
                    const inputSearchElement = document.getElementById("searchInput");
                    const callendarContainer = document.querySelector(".pwe-calendar__wrapper");
                    const allEvents = callendarContainer ? callendarContainer.querySelectorAll(".pwe-calendar__item") : [];

                    // Get all categories from the search_category attribute
                    const categorySet = new Set();
                    allEvents.forEach(eventItem => {
                        const categories = eventItem.getAttribute("search_category")?.split(",").map(cat => cat.trim()) || [];
                        categories.forEach(cat => {
                            if (cat.toLowerCase() !== "b2c") categorySet.add(cat.toLowerCase());
                        });
                    });

                    // Convert Set to array
                    let allCategoriesArray = Array.from(categorySet);

                    // Sort alphabetically, but move "other" to the end
                    allCategoriesArray.sort((a, b) => {
                        if (a === "'. self::multi_translation('other') .'") return 1; // "other" after everything else
                        if (b === "'. self::multi_translation('other') .'") return -1;
                        return a.localeCompare(b); // alphabetical order for the rest
                    });

                    // Map to objects for dropdown
                    let sortedCategories = allCategoriesArray.map(cat => ({
                        name: cat,
                        slug: cat.toLowerCase().replace(/\s+/g, "-")
                    }));

                    // Add "All" at the beginning
                    sortedCategories.unshift({
                        name: "'. self::multi_translation('all') .'",
                        slug: "all"
                    });

                    // Add "Premier editions" at the end if needed
                    if ("'. $pwe_calendar_week .'" !== "true") {
                        sortedCategories.push({
                            name: "'. self::multi_translation('premier_edition') .'",
                            slug: "premier-edition"
                        });
                    }

                    // Now use sortedCategories to build your dropdown
                    let allCategoriesData = sortedCategories;

                    // Fill the dropdown
                    allCategoriesData.forEach((category, i) => {
                        const li = document.createElement("li");
                        const categorySlug = category.slug.toLowerCase().replace(/\s+/g, "-");
                        if (categorySlug) li.classList.add(categorySlug);
                        li.innerText = replacePL(category.name).toUpperCase();
                        li.style = `--delay: ${i + 1}`;

                        li.addEventListener("click", function (event) {
                            if (categorySlug === "all") {
                                // Show all events
                                allEvents.forEach(eventItem => eventItem.classList.remove("dont-show"));
                            } else if (categorySlug === "premier-edition") {
                                allEvents.forEach(eventItem => {
                                    const editionText = eventItem.querySelector(".pwe-calendar__edition p")?.innerText.toLowerCase() || "";
                                    const isPremier = editionText.includes("'. self::multi_translation("premier_edition") .'".toLowerCase());
                                    eventItem.classList.toggle("dont-show", !isPremier);
                                });
                            } else {
                                allEvents.forEach(eventItem => {
                                    const eventCategories = eventItem.getAttribute("search_category")?.split(",").map(cat => cat.trim().toLowerCase()) || [];
                                    const eventCategorySlug = eventCategories.filter(cat => cat !== "b2c").map(cat => cat.replace(/\s+/g, "-"));
                                    const isCategoryMatched = eventCategorySlug.includes(categorySlug);
                                    eventItem.classList.toggle("dont-show", !isCategoryMatched);
                                });
                            }

                            // Update dropdown button text
                            dropdownBtn.innerHTML = `<span>${event.target.innerText}</span><span class="arrow"></span>`;
                            dropdownBtn.click(); // Close dropdown
                        });

                        dropdownMenu?.appendChild(li);
                    });

                    // Hide B2C elements
                    const b2cElements = dropdownMenu?.querySelectorAll("[class*=\'b2c-\']");
                    b2cElements.forEach(element => element.style.display = "none");

                    // Search functionality
                    inputSearchElement?.addEventListener("input", () => {
                        const query = inputSearchElement.value.toLowerCase().trim();
                        allEvents.forEach(eventItem => {
                            const fairName = eventItem.getAttribute("search_engine")?.toLowerCase().trim() || "";
                            const shortName = eventItem.querySelector(".pwe-calendar__short-name")?.innerText.toLowerCase().trim() || "";
                            const match = eventItem.classList.contains(query) || fairName.includes(query) || shortName.includes(query);
                            eventItem.classList.toggle("dont-show", !match);
                        });
                    });

                    // Handle dropdown open/close
                    dropdownBtn?.addEventListener("click", () => {
                        let isExpanded = dropdownBtn.getAttribute("aria-expanded") === "true";
                        dropdownBtn.setAttribute("aria-expanded", !isExpanded);
                        dropdownContent?.classList.toggle("menu-open", !isExpanded);
                        dropdownContent?.classList.toggle("dropdown-delay", isExpanded);
                    });

                });
            </script>';
        }

        $output = do_shortcode($output);
        // Zwracamy HTML z dodanym wrapperem
        return '<div id="pweCalendar" class="pwe-calendar">' . $output . '</div>';
    }

    public function render_calendar_event_card($event, $shortcodes_active, $lang_pl = true) {
        $locale = get_locale();

        $post_id = $event['post_id'];
        if ($post_id) {
            $website = get_post_meta($post_id, 'web_page_link', true);
            $host = parse_url($website, PHP_URL_HOST);
            $domain = preg_replace('/^www\./', '', $host);
            $permalink = $event['permalink'];
        } else {
            $domain = '';
        }

        $event_type = get_post_meta($post_id, 'pwe_event_type', true);

        $post_meta = get_post_meta($post_id);

        // Get dates
        $start_date = $event['start_date'];
        $end_date = $event['end_date'];

        // Date formatting
        $formatted_date = self::format_date_range($start_date, $end_date, $locale);

        $date_object = DateTime::createFromFormat('d-m-Y', $start_date);

        if ($locale == "pl_PL") {
            $new_date_coming_soon = "Nowa data wkrótce";
        } else if ($locale == "en_US") {
            $new_date_coming_soon = "New date coming soon";
        } else if ($locale == "de_DE") {
            $new_date_coming_soon = "Neuer Termin folgt in Kürze";
        } else if ($locale == "lt_LT") {
            $new_date_coming_soon = "Nauja data netrukus";
        } else if ($locale == "lv_LV") {
            $new_date_coming_soon = "Jauns datums drīzumā";
        } else if ($locale == "uk") {
            $new_date_coming_soon = "Нова дата незабаром";
        } else if ($locale == "cs_CZ") {
            $new_date_coming_soon = "Nový termín již brzy";
        } else if ($locale == "sk_SK") {
            $new_date_coming_soon = "Nový termín už čoskoro";
        } else if ($locale == "ru_RU") {
            $new_date_coming_soon = "Новая дата скоро";
        } else {
            $new_date_coming_soon = "New date coming soon";
        }

        $quarterly_date = !empty($post_meta['quarterly_date'][0]) ? $post_meta['quarterly_date'][0] : $new_date_coming_soon;

        if (($date_object && $date_object->format('Y') == 2050) || (strtotime($end_date . " +1 day") < time())) {
            $fair_date =  $quarterly_date;
        } else {
            $fair_date = $formatted_date;
        }

        $target_blank = $post_meta['web_page_link_target_blank'][0];

        $lang = ICL_LANGUAGE_CODE;

        // [pwe_short_desc_{lang}]
        $shortcode_short_desc = self::get_pwe_shortcode("pwe_short_desc_$lang", $domain);
        $shortcode_short_desc_available = self::check_available_pwe_shortcode($shortcodes_active, $shortcode_short_desc);
        $short_desc = $shortcode_short_desc_available ? $shortcode_short_desc : $post_meta['short_desc'][0];

        // [pwe_visitors]
        $shortcode_visitors = self::get_pwe_shortcode("pwe_visitors", $domain);
        $shortcode_visitors_available = self::check_available_pwe_shortcode($shortcodes_active, $shortcode_visitors);
        $visitors_num = $shortcode_visitors_available ? $shortcode_visitors : $post_meta['visitors'][0];

        // [pwe_exhibitors]
        $shortcode_exhibitors = self::get_pwe_shortcode("pwe_exhibitors", $domain);
        $shortcode_exhibitors_available = self::check_available_pwe_shortcode($shortcodes_active, $shortcode_exhibitors);
        $exhibitors_num = $shortcode_exhibitors_available ? $shortcode_exhibitors : $post_meta['exhibitors'][0];

        // [pwe_countries]
        $shortcode_area = self::get_pwe_shortcode("pwe_area", $domain);
        $shortcode_area_available = self::check_available_pwe_shortcode($shortcodes_active, $shortcode_area);
        $area_num = $shortcode_area_available ? $shortcode_area : $post_meta['area'][0];

        // [pwe_edition]
        $shortcode_edition = self::get_pwe_shortcode("pwe_edition", $domain);
        $shortcode_edition_available = self::check_available_pwe_shortcode($shortcodes_active, $shortcode_edition);
        $edition_num = $shortcode_edition_available ? $shortcode_edition : $post_meta['edition'][0];
        $edition = '';
        if($edition_num == '1'){
            $edition .= self::multi_translation("premier_edition");
        } else {
            $edition .= $edition_num . self::multi_translation("edition");
        }

        // $categories = $event['categories'];
        // $category_names = '';
        // foreach ($categories as $category) {
        //         $category_names .= ' ' . $category->name;
        // }

        $categories = $event['categories'];
        $category_names = implode(', ', array_map(fn($c) => $c->name, $categories));

        $featured_image_url = $post_meta['_featured_image_url'][0];
        $secondary_image_url = $post_meta['_secondary_image_url'][0];

        if ($event_type === "" || $event_type === "event") {
            $output = '
            <div class="pwe-calendar__item" search_engine="'. $event['post_title'] .' '. $post_meta['keywords'][0] .' " search_category="' . $category_names . '">
                <a class="pwe-calendar__link" href="'. ($target_blank ? $website : $permalink) .'" '. ($target_blank ? 'target="_blank"' : '') .'>
                    <div class="pwe-calendar__tile" style="background-image:url(' . esc_url($secondary_image_url) . ');">';
                        if (!empty($short_desc)) {
                            $output .= '
                            <div class="pwe-calendar__short-name">
                                <h4>'. $short_desc .'</h4>
                            </div>';
                        };

                        $output .= '
                        <div class="pwe-calendar_strip">
                            <div class="pwe-calendar__button-check"><p>' . self::multi_translation("check_out") . ' ❯</p></div>';
                            if (!empty($edition_num)) {
                                $output .= '<div class="pwe-calendar__edition"><p>' . $edition . '</p></div>';
                            }
                        $output .= '
                        </div>';

                    $output .= '
                    </div>
                    <div class="pwe-calendar__date">
                        <h5>' . $fair_date . '</h5>
                    </div>';
                    if (!empty($visitors_num) && !empty($exhibitors_num) && !empty($area_num)) {
                        if ($edition == self::multi_translation("premier_edition")) {
                            $output .= '<div class="pwe-calendar__statistics-word">' . self::multi_translation("estimates") .'</div>';
                        } else {
                            $output .= '<div class="pwe-calendar__statistics-word">' . self::multi_translation("statistics") . '</div>';
                        }
                        $output .= '
                        <div class="pwe-calendar_statistics">
                            <div class="pwe-calendar__statistics-item">
                                <div class="pwe-calendar__statistics-icon">
                                    <img
                                        src="https://warsawexpo.eu/wp-content/uploads/2024/09/ikonka_odwiedzajacy.svg"
                                        alt="' . ($lang_pl ? "Ikona odwiedzający" : "Icon visitors") . '"
                                    >
                                </div>
                                <div class="pwe-calendar__statistics-name">
                                    <p class="pwe-calendar__statistics-label">' . self::multi_translation("visitors") . '</p>
                                    <p class="pwe-calendar__statistics-value">' . $visitors_num . '</p>
                                </div>
                            </div>
                            <div class="pwe-calendar__statistics-item">
                                <div class="pwe-calendar__statistics-icon">
                                    <img
                                        src="https://warsawexpo.eu/wp-content/uploads/2024/09/ikonka_wystawcy.svg"
                                        alt="' . ($lang_pl ? "Ikona wystawcy" : "Icon exhibitors") . '"
                                    >
                                </div>
                                <div class="pwe-calendar__statistics-name">
                                    <p class="pwe-calendar__statistics-label">' . self::multi_translation("exhibitors") . '</p>
                                    <p class="pwe-calendar__statistics-value">' . $exhibitors_num . '</p>
                                </div>
                            </div>
                            <div class="pwe-calendar__statistics-item">
                                <div class="pwe-calendar__statistics-icon">
                                    <img
                                        src="https://warsawexpo.eu/wp-content/uploads/2024/09/ikonka_powierzchnia.svg"
                                        alt="' . ($lang_pl ? "Ikona powierzchnia wystawiennicza" : "Icon exhibition area") . '"
                                    >
                                </div>
                                <div class="pwe-calendar__statistics-name">
                                    <p class="pwe-calendar__statistics-label">' . self::multi_translation("exhibition_area") . '</p>
                                    <p class="pwe-calendar__statistics-value">' . $area_num . ' m<sup>2</sup></p>
                                </div>
                            </div>
                        </div>';
                    }
                $output .= '
                </a>
            </div>';
        } else {
            $date_start = get_post_meta($post_id, 'fair_date_start', true);
            $date_end   = get_post_meta($post_id, 'fair_date_end', true);

            // Download saved excluded fairs
            $excluded_events = get_post_meta($post_id, 'events_week_fairs_excluded', true);
            $excluded_events_array = !empty($excluded_events) ? array_map('trim', explode(', ', $excluded_events)) : [];

            $events_map = [];
            if (!empty($date_start) && !empty($date_end)) {
                $trade_fair_start_timestamp = strtotime($date_start);
                $trade_fair_end_timestamp   = strtotime($date_end);

                $fairs_json = PWECommonFunctions::json_fairs();

                foreach ($fairs_json as $fair) {
                    $event_date_start = isset($fair['date_start']) ? strtotime($fair['date_start']) : null;
                    $event_date_end   = isset($fair['date_end']) ? strtotime($fair['date_end']) : null;
                    $event_domain     = $fair["domain"];
                    $event_name       = PWECommonFunctions::lang_pl() ? $fair["name_pl"] : $fair["name_pl"];

                    if ($event_date_start && $event_date_end) {
                        $isStartInside    = $event_date_start >= $trade_fair_start_timestamp && $event_date_start <= $trade_fair_end_timestamp;
                        $isEndInside      = $event_date_end >= $trade_fair_start_timestamp && $event_date_end <= $trade_fair_end_timestamp;
                        $isNotFastTextile = strpos($event_domain, "fasttextile.com") === false;
                        $isNotExpoTrends  = strpos($event_domain, "expotrends.eu") === false;
                        $isNotFabricsExpo = strpos($event_domain, "fabrics-expo.eu") === false;
                        $isNotTest        = strpos($event_domain, "mr.glasstec.pl") === false;

                        if (($isStartInside || $isEndInside) && $isNotFastTextile && $isNotExpoTrends && $isNotFabricsExpo && $isNotTest) {
                            $events_map[] = [
                                "domain"     => $event_domain,
                                "name"     => $event_name,
                                "visitors"   => isset($fair["visitors"]) ? $fair["visitors"] : null,
                                "exhibitors" => isset($fair["exhibitors"]) ? $fair["exhibitors"] : null,
                                "area"       => isset($fair["area"]) ? $fair["area"] : null
                            ];
                        }
                    }
                }
            }

            // Filtering the event map by exclusions and reindexing
            $filtered_events = array_values(array_filter($events_map, function($event) use ($excluded_events_array) {
                return !in_array(trim($event['domain']), $excluded_events_array, true);
            }));

            $cap_visitors = 0;
            $cap_exhibitors = 0;
            $cap_area = 0;

            foreach ($filtered_events as $event) {
                $cap_visitors += $event['visitors'];
                $cap_exhibitors += $event['exhibitors'];
                $cap_area += $event['area'];
            }

            $meta_visitors = get_post_meta($post_id, 'events_week_visitors', true);
            $meta_exhibitors = get_post_meta($post_id, 'events_week_exhibitors', true);
            $meta_area = get_post_meta($post_id, 'events_week_area', true);

            $week_visitors   = !empty($meta_visitors) ? $meta_visitors  : ceil($cap_visitors / 1000) * 1000;
            $week_exhibitors = !empty($meta_exhibitors) ? $meta_exhibitors  : ceil($cap_exhibitors / 10) * 10;
            $week_area       = !empty($meta_area) ? $meta_area : ceil($cap_area / 10) * 10;

            if (count($filtered_events) == 1) {
                $events_word_declination = "wydarzenie";
            } else if (count($filtered_events) > 1 && count($filtered_events) < 5) {
                $events_word_declination = "wydarzenia";
            } else {
                $events_word_declination = "wydarzeń";
            }

            $output = '
            <div class="pwe-calendar__item '. $event_type .'" search_engine="'. $event['post_title'] .' '. $post_meta['keywords'][0] .' " search_category="' . $category_names . '">
                <a class="pwe-calendar__link" href="'. $permalink .'">
                    <div class="pwe-calendar__tile" style="background-image:url(' . esc_url($secondary_image_url) . ');">
                        <div class="pwe-calendar__short-name">
                            <h4>'. get_the_title($post_id) .'</h4>
                        </div>
                        <div class="pwe-calendar_strip">
                            <div class="pwe-calendar__button-check"><p>' . self::multi_translation("check_out") . ' ❯</p></div>
                            <div class="pwe-calendar__count-events"><p>'. count($filtered_events) . ' ' . $events_word_declination .'</p></div>
                        </div>
                    </div>
                    <div class="pwe-calendar_info">
                        <div class="pwe-calendar__date">
                            <h5>' . $fair_date . '</h5>
                        </div>
                        <ul class="pwe-calendar_info-list">';
                            foreach ($filtered_events as $event) {
                                $output .= '<li>&#8226; '. $event['name'] .'</li>';
                            }
                        $output .= '
                        </ul>
                    </div>
                </a>
            </div>';
        }

        return $output;
    }

    public function load_more_calendar($pwe_calendar_pagination) {
        try {
            $page = $pwe_calendar_pagination ? (isset($_POST['calendar-page']) ? intval($_POST['calendar-page']) : 1) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
            $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 16;

            $args = array(
                'post_type' => 'event',
                'posts_per_page' => -1,
                'paged' => $page,
                'post_status' => 'publish',
            );

            $query = new WP_Query($args);
            $event_posts = [];

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    $website = get_post_meta($post_id, 'web_page_link', true);
                    $host = parse_url($website, PHP_URL_HOST);
                    $domain = preg_replace('/^www\./', '', $host);
                    $pwe_db_date_start = date("d-m-Y", strtotime(str_replace("/", "-", self::get_pwe_shortcode("pwe_date_start", $domain))));
                    $start_date = !empty($pwe_db_date_start) ? $pwe_db_date_start : "28-01-2050";
                    $end_date = get_post_meta($post_id, 'fair_date_end', true);
                    $end_date = empty($end_date) ? "30-01-2050" : $end_date;

                    $event_posts[] = [
                        'post_id' => $post_id,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'domain' => $domain,
                        'permalink' => get_permalink(),
                        'categories' => get_the_terms($post_id, 'event_category'),
                        'post_title' => get_the_title(),
                    ];
                }
            }

            wp_reset_postdata();

            usort($event_posts, function ($a, $b) {
                $a_date = DateTime::createFromFormat('d-m-Y', $a['start_date']);
                $b_date = DateTime::createFromFormat('d-m-Y', $b['start_date']);
                return $a_date <=> $b_date;
            });

            $offset = ($page - 1) * $posts_per_page;
            $paged_posts = array_slice($event_posts, $offset, $posts_per_page);

            foreach ($paged_posts as $event) {
                echo self::render_calendar_event_card($event, true, get_locale() == "pl_PL");
            }

            // Jeśli są posty do załadowania, zmień przycisk
            if ($query->found_posts > ($page * $posts_per_page)) {
                echo '<script>document.getElementById("loadMore").style.display = "block";</script>';
            } else {
                echo '<script>document.getElementById("loadMore").style.display = "none";</script>';
            }
        } catch (Throwable $e) {
            echo '<script>console.log("AJAX ERROR: '. $e->getMessage() .');</script>';
        }

        wp_die();
    }
}