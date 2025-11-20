<?php

/**
 * Class PWElementMainCountdown
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementMainCountdown extends PWElements {
    public static $countdown_rnd_id;
    public static $today_date;
    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        date_default_timezone_set('Europe/Warsaw');
        self::$today_date = new DateTime();
        self::$countdown_rnd_id = rand(10000, 99999);
        parent::__construct();

        require_once plugin_dir_path(__FILE__) . 'js/countdown.php';
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Custom Timer', 'pwelement'),
                'param_name' => 'custom_timer',
                'description' => __('Enable to create custom timer'),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMainCountdown',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Turn off background', 'pwelement'),
                'param_name' => 'turn_off_timer_bg',
                'description' => __('Enable to create custom timer'),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMainCountdown',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'heading' => __('Add countdown', 'pwelement'),
                'param_name' => 'countdowns',
                'param_holder_class' => 'main-param-group countdown-params',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => array('PWElementMainCountdown','PWElementHomeGallery'),
                ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Start', 'pwelement'),
                        'param_name' => 'countdown_start',
                        'description' => __('Format (Y/m/d h:m)', 'pwelement'),
                        'param_holder_class' => 'backend-textfield',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('End', 'pwelement'),
                        'param_name' => 'countdown_end',
                        'description' => __('Format (Y/m/d h:m)', 'pwelement'),
                        'param_holder_class' => 'backend-textfield',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Placeholder text', 'pwelement'),
                        'param_name' => 'countdown_text',
                        'description' => __('Default: "Do targów pozostało/Until the start of the fair"', 'pwelement'),
                        'param_holder_class' => 'backend-textfield',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Button Text', 'pwelement'),
                        'param_name' => 'countdown_btn_text',
                        'param_holder_class' => 'backend-textfield',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Button Url', 'pwelement'),
                        'param_name' => 'countdown_btn_url',
                        'param_holder_class' => 'backend-textfield',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Font size', 'pwelement'),
                        'param_name' => 'countdown_font_size',
                        'param_holder_class' => 'backend-textfield',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Font weight', 'pwelement'),
                        'param_name' => 'countdown_weight',
                        'param_holder_class' => 'backend-textfield',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Turn off placeholder text', 'pwelement'),
                        'param_name' => 'turn_off_countdown_text',
                        'value' => array(__('True', 'pwelement') => 'true',),
                        'param_holder_class' => 'backend-basic-checkbox',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Turn off button', 'pwelement'),
                        'param_name' => 'turn_off_countdown_button',
                        'value' => array(__('True', 'pwelement') => 'true',),
                        'param_holder_class' => 'backend-basic-checkbox',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Turn off background', 'pwelement'),
                        'param_name' => 'turn_off_countdown_bg',
                        'value' => array(__('True', 'pwelement') => 'true',),
                        'param_holder_class' => 'backend-basic-checkbox',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Limit width', 'pwelement'),
                        'param_name' => 'countdown_limit_width',
                        'value' => array(__('True', 'pwelement') => 'true',),
                        'param_holder_class' => 'backend-basic-checkbox',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Row->Column', 'pwelement'),
                        'param_name' => 'countdown_column',
                        'value' => array(__('True', 'pwelement') => 'true',),
                        'param_holder_class' => 'backend-basic-checkbox',
                    ),
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide change register button', 'pwelement'),
                'param_name' => 'show_register_bar',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMainCountdown',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Change day to d etc.', 'pwelement'),
                'param_name' => 'show_short_name_data',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMainCountdown',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide seconds', 'pwelement'),
                'param_name' => 'hide_seconds',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMainCountdown',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
        );

        return $element_output;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/countdown.json';

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
    /**
     * Static private method to remove from JS out of date timer variables.
     *      *
     * @param array @right_date array off only new timers
     */
    private static function getRightData($count) {
        foreach ($count as $key => $value) {
            $start_date = !empty($value["countdown_start"]) ? new DateTime($value["countdown_start"]) : self::$today_date;
            $end_date = new DateTime($value["countdown_end"]);

            if ($start_date > self::$today_date || $end_date < self::$today_date) {
                unset($count[$key]);
            } else {
                break;
            }
        }
        return array_values($count);
    }


    /**
     * Set up default stats.
     * Returns array for timer display on main site.
     *
     * @return array @main_timer options
     */
    public static function main_timer() {
        $output_time_start_def = array(
            'countdown_start' => '',
            'countdown_end' => do_shortcode('[trade_fair_datetotimer]'),
            'countdown_text' => self::multi_translation("until_the_fair")
        );
        $output_time_end_def = array(
            'countdown_start' => '',
            'countdown_end' => do_shortcode('[trade_fair_enddata]'),
            'countdown_text' => self::multi_translation("until_the_fair_end")
        );
        $countdown_next_year = substr(trim(do_shortcode('[trade_fair_datetotimer]')), 0, 4) + 1;
        $countdown_next_year .=  substr(do_shortcode('[trade_fair_datetotimer]'), 4);

        $output_time_start_next_year = array(
            'countdown_start' => '',
            'countdown_end' => $countdown_next_year,
            'countdown_text' => self::multi_translation("until_the_fair")
        );

        $output_button = array(
            'countdown_btn_text' => self::multi_translation("countdown_btn_text"),
            'countdown_btn_url' => self::multi_translation("countdown_btn_url")
        );

        $output_default[0] = array_merge($output_time_start_def, $output_button);
        $output_default[1] = array_merge($output_time_end_def, $output_button);
        $output_default[2] = array_merge($output_time_start_next_year, $output_button);

        return $output_default;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {
        extract(shortcode_atts(array(
            'custom_timer' => '',
            'turn_off_timer_bg' => '',
            'countdowns' => '',
            'show_short_name_data' => '',
            'hide_seconds' => '',
        ), $atts ));

        $show_short = isset($atts['show_short_name_data']) && $atts['show_short_name_data'] === 'true';

        $output = '';

        // Get current domain
        $current_domain = do_shortcode('[trade_fair_domainadress]');

        // Getting shortcode values
        $trade_fair_start_date = do_shortcode('[trade_fair_datetotimer]'); // ex. "2027/01/14 10:00"
        $trade_fair_end_date = do_shortcode('[trade_fair_enddata]'); // ex. "2027/01/16 17:00"

        // Convert to timestamp
        $trade_fair_start_date_timestamp = strtotime($trade_fair_start_date);
        $trade_fair_end_date_timestamp = strtotime($trade_fair_end_date);
        $current_timestamp = time();

        // Get the day of the week
        $trade_fair_start_date_week = date('l', $trade_fair_start_date_timestamp);
        $trade_fair_end_date_week = date('l', $trade_fair_end_date_timestamp);
        // Get time
        $trade_fair_start_date_hour = date('H:i', $trade_fair_start_date_timestamp);
        $trade_fair_end_date_hour = date('H:i', $trade_fair_end_date_timestamp);

        // Changing English names of days to Polish ones
        if(get_locale() == 'pl_PL') {
            $days = [
                'Monday' => 'Poniedziałek',
                'Tuesday' => 'Wtorek',
                'Wednesday' => 'Środa',
                'Thursday' => 'Czwartek',
                'Friday' => 'Piątek',
                'Saturday' => 'Sobota',
                'Sunday' => 'Niedziela'
            ];

            $trade_fair_start_date_week = $days[$trade_fair_start_date_week] ?? $trade_fair_start_date_week;
            $trade_fair_end_date_week = $days[$trade_fair_end_date_week] ?? $trade_fair_end_date_week;
        }

        // Get JSON
        $fairs_json = PWECommonFunctions::json_fairs();

        $fair_items_json = [];

        foreach ($fairs_json as $fair) {
            // Getting start and end dates
            $date_start = isset($fair['date_start']) ? strtotime($fair['date_start']) : null;
            $date_end = isset($fair['date_end']) ? strtotime($fair['date_end']) : null;

            // Checking if the date is in the range
            if ($date_start && $date_end) {
                if (($date_start >= $trade_fair_start_date_timestamp && $date_start <= $trade_fair_end_date_timestamp) ||
                    ($date_end >= $trade_fair_start_date_timestamp && $date_end <= $trade_fair_end_date_timestamp)) {
                    $fair_items_json[] = [
                        "domain" => $fair["domain"],
                        "halls" => $fair["hall"],
                        "color" => $fair["color_accent"]
                    ];
                } else {
                    if ($fair["domain"] === $current_domain) {
                        if (!$error_displayed) {
                            $output .= '<script>console.error("Shortcode dates and JSON dates do not match. Shortcode dates - ('. $trade_fair_start_date .' - '. $trade_fair_end_date .')JSON dates - ('. $fair['date_start'] .' - '. $fair['date_end'] .')");</script>';
                        }
                    }
                }
            }
        }

        $all_halls = '';

        $json_data_all = [];
        $json_data_active = [];

        foreach ($fair_items_json as $item) {
            $halls = array_map('trim', explode(',', $item['halls']));
            foreach ($halls as $hall) {
                $json_data_all[] = [
                    "id" => $hall,
                    "domain" => $item['domain']
                ];
            }

            if ($item['domain'] === $current_domain) {
                foreach ($halls as $hall) {
                    $json_data_active[] = [
                        "id" => $hall
                    ];

                    // Adding halls to $all_halls without numbers
                    $clean_hall = preg_replace('/\d/', '', $hall);
                    if (strpos($all_halls, $clean_hall) === false) {
                        $all_halls .= $clean_hall . ', ';
                    }
                }
            }
        }

        // Convert to string
        $all_halls = rtrim($all_halls, ', ');

        // Using the plural or singular form of a word
        $halls_word = (count(array_filter(array_map('trim', explode(',', $all_halls)))) > 1)
            ? self::multi_translation("halls")
            : self::multi_translation("hall");


        $all_entries = '';

        // Map assigning halls to their entrances
        $hall_entries = [
            'A' => ['A8'],
            'B' => ['B8', 'B16'],
            'C' => ['C8', 'C16'],
            'D' => ['D8', 'D16'],
            'E' => ['E1', 'E6'],
            'F' => ['F1', 'F7'],
            'A1' => ['A8'], 'A2' => ['A8'],
            'B1' => ['B8'], 'B2' => ['B8'], 'B3' => ['B16'], 'B4' => ['B16'],
            'C1' => ['C8'], 'C2' => ['C8'], 'C3' => ['C16'], 'C4' => ['C16'],
            'D1' => ['D8'], 'D2' => ['D8'], 'D3' => ['D16'], 'D4' => ['D16'],
            'E1' => ['E1'], 'E2' => ['E1'], 'E3' => ['E6'], 'E4' => ['E6'],
            'F1' => ['F7'], 'F2' => ['F7'], 'F3' => ['F1'], 'F4' => ['F1']
        ];

        $matching_entries = [];

        // Iterate through active halls
        foreach ($json_data_active as $item) {
            $hall_id = $item['id'];

            // Check if the hall has an assigned entrance
            if (isset($hall_entries[$hall_id])) {
                // Adding input to the output list
                foreach ($hall_entries[$hall_id] as $entry) {
                    $matching_entries[] = $entry;
                }
            }
        }

        // Remove duplicates and convert to string
        $all_entries = implode(', ', array_unique($matching_entries));

        // Using the plural or singular form of a word
        $entries_word = (count(array_filter(array_map('trim', explode(',', $all_entries)))) > 1)
            ? self::multi_translation("entrances")
            : self::multi_translation("entrance");

        $diff_timestamp = ($trade_fair_start_date_timestamp - $current_timestamp);
        $time_to_end_timestamp = ($trade_fair_end_date_timestamp - $current_timestamp);

        $output .= '<div style="visibility: hidden; width: 0; height: 0;" id="main-content">...</div>';

        if ((($trade_fair_start_date_timestamp != false && $trade_fair_end_date_timestamp != false) && !empty($trade_fair_start_date)) &&
            $diff_timestamp < (7 * 60 * 60) && $time_to_end_timestamp > 0 && $custom_timer != true) {

            $output .= '
            <style>
                .row-parent:has(.pwelement_'. self::$rnd_id .') {
                    max-width: 100% !important;
                    padding: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours {
                    background-color: var(--accent-color);
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    /* max-width: 1200px; */
                    margin: 0 auto;
                    padding: 10px 36px;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours p {
                    font-size: 20px;
                    color: white;
                    margin: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours__block {
                    display: flex;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours__title {
                    font-weight: 700;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours__date {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    padding: 0 18px;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours__date .hours {
                    font-weight: 700;
                    padding: 0 18px;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours__hall {
                    padding:0 25px;
                    display: flex;
                }
                .pwelement_'. self::$rnd_id .' .opening-hours__hall p {
                    padding:0 10px;
                }
                @media(max-width: 1050px) {
                    .pwelement_'. self::$rnd_id .' .opening-hours {
                        justify-content: center;
                    }
                    .pwelement_'. self::$rnd_id .' .opening-hours p {
                        font-size: 18px;
                    }
                    .pwelement_'. self::$rnd_id .' .opening-hours__block {
                        flex-wrap: wrap;
                        justify-content: center;
                        gap: 0;
                    }
                }
                @media(max-width: 500px) {
                    .pwelement_'. self::$rnd_id .' .opening-hours {
                        padding: 10px 18px;
                    }
                    .pwelement_'. self::$rnd_id .' .opening-hours p {
                        font-size: 16px;
                        text-align: center;
                        line-height: 1.4;
                    }
                    .pwelement_'. self::$rnd_id .' .opening-hours__date {
                        padding: 0;
                    }
                }
                @media(max-width: 370px) {
                    .pwelement_'. self::$rnd_id .' .opening-hours p {
                        font-size: 14px;
                    }
                }
            </style>

            <div id="openingHours" class="opening-hours">
                <div class="opening-hours__block">
                    <p class="opening-hours__title">'. self::multi_translation("hours") .'</p>
                    <p class="opening-hours__date pwe-uppercase">'. $trade_fair_start_date_week .' - '. $trade_fair_end_date_week .'<span class="hours">'. $trade_fair_start_date_hour .' - '. $trade_fair_end_date_hour .'</span></p>
                    <div class="opening-hours__hall">
                        <p>'. $halls_word .' <strong>'. $all_halls .'</strong></p>
                        <p>'. $entries_word .' <strong>'. $all_entries .'</strong></p>
                    </div>
                </div>
                <p class="opening-hours__adress">Al. Katowicka 62, 05-830 Nadarzyn</p>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    document.querySelector(".row-container:has(.pwelement_'. self::$rnd_id .')").classList.add("sticky-element");
                });
            </script>';
        } else if ($trade_fair_start_date_timestamp != false && !empty($trade_fair_start_date)) {
            $countdown = vc_param_group_parse_atts($countdowns);

            foreach($countdown as $main_id => $main_value){
                if ($custom_timer && $main_value["countdown_end"] == '') {
                    $main_value["countdown_end"] = do_shortcode('[trade_fair_datetotimer]');
                }
                foreach($main_value as $id => $key){
                    $countdown[$main_id][$id] = do_shortcode($key);
                }
            }

            $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important';
            $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
            $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color) . '!important';
            $darker_btn_color = self::adjustBrightness($btn_color, -20);

            $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

            if(($custom_timer)){
                $right_countdown = self::getRightData($countdown);
            } else {
                $right_countdown = self::getRightData(self::main_timer());
            }

            $turn_off_countdown_bg = (isset($right_countdown[0]['turn_off_countdown_bg']) && !empty($right_countdown[0]['turn_off_countdown_bg'])) ? $right_countdown[0]['turn_off_countdown_bg'] : '';
            $countdown_limit_width = (isset($right_countdown[0]['countdown_limit_width']) && !empty($right_countdown[0]['countdown_limit_width'])) ? $right_countdown[0]['countdown_limit_width'] : '';
            $countdown_weight = (isset($right_countdown[0]['countdown_weight']) && !empty($right_countdown[0]['countdown_weight'])) ? $right_countdown[0]['countdown_weight'] : '';
            $countdown_column = (isset($right_countdown[0]['countdown_column']) && !empty($right_countdown[0]['countdown_column'])) ? $right_countdown[0]['countdown_column'] : '';

            $countdown_bg = ($turn_off_countdown_bg == 'true') ? 'inherit' : self::$accent_color;
            $countdown_width = ($countdown_limit_width == 'true') ? '1200px' : '100%';
            $countdown_font_weight = ($countdown_weight == '') ? '700' : $countdown_weight;

            if ($mobile != 1) {
                $countdown_font_size = (isset($right_countdown[0]['countdown_font_size']) && !empty($right_countdown[0]['countdown_font_size'])) ? $right_countdown[0]['countdown_font_size'] : '18px';
            } else {
                $countdown_font_size = (isset($right_countdown[0]['countdown_font_size']) && !empty($right_countdown[0]['countdown_font_size'])) ? $right_countdown[0]['countdown_font_size'] : '16px';
            }

            $countdown_font_size = str_replace("px", "", $countdown_font_size);

            $ending_date = new DateTime($right_countdown[0]['countdown_end']);

            $date_dif = self::$today_date->diff($ending_date);

            $flex_direction = ($countdown_column == true) ? 'flex-direction: column;' : '';

            if ($atts['turn_off_timer_bg'] == true) {
                $output .=
                '<style>
                    .row-parent:has(.pwelement_' . self::$rnd_id . ') {
                        background: inherit;
                        max-width: ' . $countdown_width . ';
                        padding: 0 !important;
                    }';
            } else {
                $output .=
                '<style>
                    .row-parent:has(.pwelement_' . self::$rnd_id . ') {
                        background: ' . $countdown_bg . ';
                        max-width: ' . $countdown_width . ';
                        padding: 0 !important;
                    }';
            }
            if (count($right_countdown)){
                $output .= '
                    .row-parent:has(.pwelement_' . self::$rnd_id . ') {
                        background: ' . $countdown_bg . ';
                        max-width: ' . $countdown_width . ';
                        padding: 0 !important;
                    }
                    .pwelement_'. self::$rnd_id .' #main-timer p {
                        color: '. $text_color .';
                        margin: 9px auto;
                        font-size: ' . $countdown_font_size . 'px !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-btn {
                        color: '. $btn_text_color .';
                        background-color: '. $btn_color .';
                        border: 1px solid '. $btn_color .';
                        margin: 9px 18px;
                        transform: scale(1) !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-btn:hover {
                        color: '. $btn_text_color .';
                        background-color: '. $darker_btn_color .'!important;
                        border: 1px solid '. $darker_btn_color .'!important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-timer-text {
                        font-weight: ' . $countdown_font_weight . ';
                        text-transform: uppercase;
                        margin: 9px auto;
                    }
                    .pwelement_'. self::$rnd_id .' .countdown-container {
                        display: flex;
                        justify-content: space-evenly;
                        flex-wrap: wrap;
                        ' . $flex_direction . '
                        align-items: center;
                        max-width: 1200px;
                        margin: 0 auto;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-countdown-timer {
                        min-width: 450px;
                        text-align: center;
                    }
                    @media (min-width: 300px) and (max-width: 1200px) {
                        .pwelement_'. self::$rnd_id .' #main-timer p {
                            font-size: calc(14px + (' . $countdown_font_size . ' - 14) * ( (100vw - 300px) / (1200 - 300) )) !important;
                        }
                    }
                    @media (max-width:570px){
                        .pwelement_'. self::$rnd_id .' .countdown-container {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: space-evenly;
                            align-items: baseline;
                            margin: 8px auto;
                        }
                        .pwelement_'. self::$rnd_id .' .pwe-countdown-timer {
                            min-width: 100%;
                        }
                        .pwelement_'. self::$rnd_id .' #main-timer p {
                            margin: 0 auto;
                        }
                    }';
                    $output .= '
                    @media (max-width:959px){
                        .wpb_column:has(.pwelement_'. self::$rnd_id .') {
                            padding-top: 0 !important;
                        }
                    }';
                if($show_short_name_data){
                    $output .= '.pwelement_'. self::$rnd_id .' .pwe-timer-text {
                        text-transform: none;
                    }';
                }
                $output .= '</style>';

                $output .='<div id="main-timer" class="countdown-container" data-show-register-bar="'. $atts['show_register_bar'] .'">';

                $turn_off_countdown_text = isset($right_countdown[0]['turn_off_countdown_text']) ? $right_countdown[0]['turn_off_countdown_text'] : '';

                if ($turn_off_countdown_text != true && $right_countdown[0]['countdown_text'] != '') {
                    $output .='<p id="timer-header-text-' . self::$countdown_rnd_id . '" class="timer-header-text pwe-timer-text">' . $right_countdown[0]['countdown_text'] . '</p>';
                };
                if(!$show_short_name_data){
                    $output .='<p id="pwe-countdown-timer-' . self::$countdown_rnd_id . '" class="pwe-countdown-timer pwe-timer-text">
                        ' . $date_dif->days . ''. self::multi_translation("days") .'' . $date_dif->h . ''. self::multi_translation("hours_1") .'' . $date_dif->i . ''. self::multi_translation("minutes") .'';

                        if(!$mobile && !$hide_seconds){
                            $output .= $date_dif->s . ' '. self::multi_translation("seconds") .'
                            </p>';
                        } else {
                            $output .= '</p>';
                        }
                } else {
                    $output .='<p id="pwe-countdown-timer-' . self::$countdown_rnd_id . '" class="pwe-countdown-timer pwe-timer-text">
                                    ' . $date_dif->days . ' d ' . $date_dif->h . ' h ' . $date_dif->i . ' min ';
                                    if(!$mobile && !$hide_seconds){
                                        $output .= $date_dif->s . ' s
                                                </p>';
                                    } else {
                                        $output .= '</p>';
                                    }
                }

                $turn_off_countdown_button = isset($right_countdown[0]['turn_off_countdown_button']) ? $right_countdown[0]['turn_off_countdown_button'] : '';
                if ($turn_off_countdown_button != true && $right_countdown[0]['countdown_btn_text'] != '') {
                    $output .='<a id="timer-button-' . self::$countdown_rnd_id . '" class="timer-button pwe-btn btn" href="' . $right_countdown[0]['countdown_btn_url'] . '">' . $right_countdown[0]['countdown_btn_text'] . '</a>';
                };
                $output .='</div>';

                PWECountdown::output($right_countdown, self::$countdown_rnd_id, array('show_short_name_data' => $show_short, 'hide_seconds' => $hide_seconds,));

            } else {
                $output .= '</style>';
            }


            // $output .= '
            // <script>
            //     document.addEventListener("DOMContentLoaded", function() {
            //         if (document.querySelector(".row-parent:has(.pwelement_' . self::$rnd_id . ')")) {
            //             const countdownEl = document.querySelector(".row-parent:has(.pwelement_' . self::$rnd_id . ')");
            //             countdownEl.style.opacity = 1;
            //             countdownEl.style.transition = "opacity 0.3s ease";
            //         }
            //     });
            // </script>';
        } else { $output = '<style>.row-container:has(.pwelement_'. self::$rnd_id .') {display: none !important;}</style>'; }

        return $output;
    }
}