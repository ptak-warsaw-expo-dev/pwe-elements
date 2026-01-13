<?php

class PWEHeader extends PWECommonFunctions {
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;
    public static $fair_forms;
    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        $this->headerFunctions();

        self::$fair_forms = $this->findFormsGF();
        self::$fair_colors = $this->findPalletColors();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos(strtolower($color_key), 'main2') !== false){
                self::$main2_color = $color_value;
            }
        }

        require_once plugin_dir_path(__DIR__) . 'logotypes/classes/logotypes_common.php';

        // Hook actions
        add_action('vc_before_init', array($this, 'inputRange'));
        add_action('vc_before_init', array($this, 'pweCheckbox'));

        add_shortcode('pwe_header', array($this, 'PWEHeaderOutput'));
    }

    public function headerFunctions() {
        require_once plugin_dir_path(__FILE__) . 'classes/header_functions.php';
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../../translations/includes/header.json';

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
     * Output method for PWelement shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWEHeaderOutput($atts, $content = null) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');

        $el_id = self::id_rnd();

        global $registration_button_text, $pwe_header_form_id;

        extract( shortcode_atts( array(
            'pwe_header_button_on' => '',
            'pwe_header_modes' => '',
            'pwe_header_form_id' => '',
            'pwe_header_simple_conference' => '',
            'pwe_header_conference_link' => '',
            'pwe_header_conference_logo_url' => '',
            'pwe_header_custom_title' => '',
            'pwe_header_bg_position' => '',
            'pwe_header_tickets_button_link' => '',
            'pwe_header_register_button_link' => '',
            'pwe_header_conferences_button_link' => '',
            'pwe_header_buttons' => '',
            'pwe_header_conferences_title' => '',
            'pwe_header_logotypes' => '',
            'pwe_header_overlay_color' => '',
            'pwe_header_overlay_range' => '',
            'pwe_header_logo_width' => '',
            'pwe_header_logo_color' => '',
            'pwe_header_logo_marg_pag' => '',
            'pwe_header_congress_logo_color' => '',
            'association_fair_logo_color' => '',
            'pwe_header_association_hide' => '',
            'pwe_header_shadow' => '',
            'pwe_header_shadow_value' => '',
            'pwe_replace' => '',
            'pwe_header_center' => '',
            'pwe_header_without_bg' => '',
            'new_main_logotype' => '',
            'pwe_header_counter' => '',
            'pwe_header_cap_auto_partners_off' => '',
            'pwe_header_partners_position' => '',
            'pwe_header_partners_title' => '',
            'pwe_header_partners_items' => '',
            'pwe_header_partners_catalog' => '',
            'pwe_header_partners_other_logotypes_catalog' => '',
            'pwe_header_partners_title_color' => '',
            'pwe_header_partners_background_color' => '',
            'pwe_header_other_partners' => '',
        ), $atts ));

        // Replace strings
        $pwe_replace_urldecode = urldecode($pwe_replace);
        $pwe_replace_json = json_decode($pwe_replace_urldecode, true);
        $input_replace_array_html = array();
        $output_replace_array_html = array();

        if (is_array($pwe_replace_json)) {
            foreach ($pwe_replace_json as $replace_item) {
                $input_replace_array_html[] = $replace_item["input_replace_html"];
                $output_replace_array_html[] = $replace_item["output_replace_html"];
            }
        }

        $pwe_header_logo_width = ($pwe_header_logo_width == '') ? '260px' : $pwe_header_logo_width;
        $pwe_header_logo_width = str_replace("px", "", $pwe_header_logo_width);

        if (self::lang_pl()) {
            $pwe_header_tickets_button_link = empty($pwe_header_tickets_button_link) ? "/bilety/" : $pwe_header_tickets_button_link;
            $pwe_header_register_button_link = empty($pwe_header_register_button_link) ? "/rejestracja/" : $pwe_header_register_button_link;
            $pwe_header_conferences_button_link = empty($pwe_header_conferences_button_link) ? "/wydarzenia/" : $pwe_header_conferences_button_link;
        } else {
            $pwe_header_tickets_button_link = empty($pwe_header_tickets_button_link) ? "/en/tickets/" : $pwe_header_tickets_button_link;
            $pwe_header_register_button_link = empty($pwe_header_register_button_link) ? "/en/registration/" : $pwe_header_register_button_link;
            $pwe_header_conferences_button_link = empty($pwe_header_conferences_button_link) ? "/en/conferences/" : $pwe_header_conferences_button_link;
        }

        // General css
        $output = '
        <style>
            .row-parent:has(.pwelement_'. $el_id.' .pwe-header) {
                max-width: 100%;
                padding: 0 !important;
            }
            .wpb_column:has(.pwelement_'. $el_id.' .pwe-header) {
                max-width: 100%;
            }
            .pwelement_'. $el_id .' .pwe-header-wrapper {
                min-height: 60vh;
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                z-index: 2;
            }
            .pwelement_'. $el_id .' .pwe-header-logo {
                max-width: '. $pwe_header_logo_width .'px !important;
                width: 100%;
                height: auto;
                z-index: 1;
            }
            .pwelement_'. $el_id .' .pwe-header-background {
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }
            .pwelement_'. $el_id .' .pwe-header-text {
                padding: 18px 0;
                z-index: 1;
            }
            .pwelement_'. $el_id .' .pwe-header-text :is(h1, h2), .pwe-header .pwe-logotypes-title h4 {
                color: '. $text_color .';
                text-transform: uppercase;
                text-align: center;
                width: auto;
            }
            .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-title {
                justify-content: center;
            }
            .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-title h4 {
                box-shadow: 9px 9px 0px -6px '. $text_color .';
            }
            .pwelement_'. $el_id .' .pwe-header-text h1 {
                font-size: 30px;
            }
            .pwelement_'. $el_id .' .pwe-header-text h2 {
                font-size: 36px;
            }
            .pwelement_'. $el_id .' .pwe-header .slides div p,
            .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-gallery-wrapper div p{
                color: '. $text_color .';
            }
            .pwelement_'. $el_id .' .pwe-header .dots-container {
                display: none !important;
            }
            .pwelement_'. $el_id .' .pwe-header .pwe-header-logotypes {
                transition: .3s ease;
                opacity: 0;
            }
            @media (min-width: 300px) and (max-width: 1200px) {
                .pwelement_'.$el_id.' .pwe-header-text h1 {
                    font-size: calc(20px + (30 - 20) * ( (100vw - 300px) / (1200 - 300) ));
                }
                .pwelement_'.$el_id.' .pwe-header-text h2 {
                    font-size: calc(24px + (36 - 24) * ( (100vw - 300px) / (1200 - 300) ));
                }
            }
            @media (max-width: 960px) {
                .row-parent:has(.pwelement_'.$el_id.' .pwe-header) {
                    padding: 0 !important;
                }
                .pwelement_'.$el_id.' .pwe-btn-container a {
                    min-width: 280px !important;
                }
            }
        </style>';

        // $output .= '<script>console.log("mobile == '. preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']) .' ('. $_SERVER['HTTP_USER_AGENT'].') (line 178)");</script>';

        $base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $base_url .= "://".$_SERVER['HTTP_HOST'];

        $trade_fair_name = (self::lang_pl()) ? '[trade_fair_name]' : '[trade_fair_name_eng]';
        $trade_fair_date = (self::lang_pl()) ? '[trade_fair_date]' : '[trade_fair_date_eng]';

        if ($pwe_header_modes == "simple_mode" &&  $pwe_header_simple_conference == true) {
            $trade_fair_desc = !empty($pwe_header_custom_title) ? $pwe_header_custom_title : get_the_title();
            if($pwe_header_logo_color != 'true') {
                $logo_url = file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/kongres.webp') ? '/doc/kongres.webp' : "/doc/logo.webp";
            } else {
                $logo_url = file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/kongres-color.webp') ? '/doc/kongres-color.webp' : '/doc/kongres.webp';
            }
        } else {
            $trade_fair_desc = !empty($pwe_header_custom_title) ? $pwe_header_custom_title : ((self::lang_pl()) ? '[trade_fair_desc]' : '[trade_fair_desc_eng]');
            if($pwe_header_logo_color != 'true') {
                if (self::lang_pl()) {
                    $logo_url = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo.webp') ? '/doc/logo.webp' : '/doc/logo.png');
                } else {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-en.webp') || file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-en.png')) {
                        $logo_url = file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-en.webp') ? "/doc/logo-en.webp" : "/doc/logo-en.png";
                    } else {
                        $logo_url = file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo.webp') ? "/doc/logo.webp" : "/doc/logo.png";
                    }
                }
            } else {
                if (self::lang_pl()) {
                    $logo_url = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-color.webp') ? '/doc/logo-color.webp' : '/doc/logo-color.png');
                } else {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-color-en.webp') || file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-color-en.png')) {
                        $logo_url = file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-color-en.webp') ? "/doc/logo-color-en.webp" : "/doc/logo-color-en.png";
                    } else {
                        $logo_url = file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/logo-color.webp') ? "/doc/logo-color.webp" : "/doc/logo-color.png";
                    }
                }
            }
        }

        // Background url
        $file_path_header_background = glob('doc/background.webp');
        if (!empty($file_path_header_background)) {
            $file_path_header_background = $file_path_header_background[0];
            $file_url = $base_url . '/' . $file_path_header_background;
        }

        // Background position
        $positions = ['top', 'center', 'bottom'];
        foreach ($positions as $position) {
            if (in_array($position, explode(',', $pwe_header_bg_position))) {
                $output .= '
                    <style>
                        .pwelement_'. $el_id .' .pwe-header-background {
                            background-position: '. $position .' !important;
                        }
                    </style>';
                break;
            }
        }

        // Processing edition shortcode
        $trade_fair_edition_shortcode = do_shortcode('[trade_fair_edition]');
        if (strpos($trade_fair_edition_shortcode, '.') !== false) {
            $trade_fair_edition_text = self::multi_translation("edition");
        } else {
            $trade_fair_edition_text = ".&nbsp;" . self::multi_translation("edition");
        }
        $trade_fair_edition_first = self::multi_translation("premier_edition");
        $trade_fair_edition = (!is_numeric($trade_fair_edition_shortcode) || $trade_fair_edition_shortcode == 1) ? $trade_fair_edition_first : $trade_fair_edition_shortcode . $trade_fair_edition_text;

        // Shortcodes of dates
        $start_date = do_shortcode('[trade_fair_datetotimer]');
        $end_date = do_shortcode('[trade_fair_enddata]');

        // Transform the dates to the desired format
        $formatted_date = self::transform_dates($start_date, $end_date);

        // Format of date
        if (self::isTradeDateExist()) {
            $actually_date = (self::lang_pl()) ? '[trade_fair_date]' : '[trade_fair_date_eng]';
        } else {
            $actually_date = $formatted_date;
        }

        $background_congress = $base_url . '/wp-content/plugins/pwe-media/media/conference-background.webp';
        $background_header = ($pwe_header_modes == "conference_mode") ? $background_congress : $file_url;

        // Html of header
        if ($pwe_header_modes == "") {
            require_once plugin_dir_path(__FILE__) . 'classes/header_default.php';
        } else if ($pwe_header_modes == "simple_mode") {
            require_once plugin_dir_path(__FILE__) . 'classes/header_simple.php';
        } else if ($pwe_header_modes == "registration_mode" || $pwe_header_modes == "conference_mode") {
            require_once plugin_dir_path(__FILE__) . 'classes/header_badge.php';
        } else if ($pwe_header_modes == "squares_mode") {
            require_once plugin_dir_path(__FILE__) . 'classes/header_squares.php';
        } else if ($pwe_header_modes == "video_mode") {
            require_once plugin_dir_path(__FILE__) . 'classes/header_video.php';
        } else if ($pwe_header_modes == "glass_mode") {
            require_once plugin_dir_path(__FILE__) . 'classes/header_glass.php';
        } else if ($pwe_header_modes == "glass_mode_v2") {
            require_once plugin_dir_path(__FILE__) . 'classes/header_glass_v2.php';
        }

        $output = do_shortcode($output);

        $file_cont = '<div class="pwelement pwelement_'. $el_id .'">' . $output . '</div>';

        // Replace strings for content
        if ($input_replace_array_html && $output_replace_array_html) {
            $file_cont = str_replace($input_replace_array_html, $output_replace_array_html, $file_cont);
        }

        return $file_cont;
    }
}