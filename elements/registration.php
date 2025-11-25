<?php

/**
 * Class PWElementRegistration
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementRegistration extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Select form', 'pwelement'),
                'param_name' => 'registration_select',
                'save_always' => true,
                'value' => array(
                    'Dla odwiedzających' => 'visitors',
                    'Dla wystawców' => 'exhibitors',
                    'Dla wystawców v2' => 'exhibitors_v2',
                ),
                'std' => 'visitors',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegistration',
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Modes', 'pwelement'),
                'param_name' => 'registration_modes',
                'value' => array(
                    'Registration mode' => 'registration_mode',
                    'Coference mode' => 'conference_mode'
                ),
                'dependency' => array(
                    'element' => 'registration_select',
                    'value' => array(
                        'visitors'
                    ),
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title form', 'pwelement'),
                'param_name' => 'registration_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegistration',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Custom text form', 'pwelement'),
                'param_name' => 'registration_text',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegistration',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom button text form', 'pwelement'),
                'param_name' => 'registration_button_text',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegistration',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Input step', 'pwelement'),
                'param_name' => 'registration_input_step',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegistration',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Height logotypes', 'pwelement'),
                'description' => __('Default 50px', 'pwelement'),
                'param_name' => 'registration_height_logotypes',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegistration',
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Registration Form', 'pwelement'),
                'param_name' => 'registration_form_id',
                'save_always' => true,
                'value' => array_merge(
                  array('Wybierz' => ''),
                  self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegistration',
                ),
            ),
        );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function custom_css_1() {
        $css_output = '
            <style>
                .gform_validation_errors {
                    border:none !important;
                }
                .pwelement_' . self::$rnd_id . ' .pwe-registration-column {
                    background-color: #e8e8e8;
                    padding: 18px 36px;
                    border: 2px solid #564949;
                }
                .pwelement_' . self::$rnd_id . ' input{
                    border: 2px solid #564949 !important;
                    box-shadow: none !important;
                    line-height: 1 !important;
                }
                .pwelement_' . self::$rnd_id . ' :is(label, label span, .gform_legacy_markup_wrapper .gfield_required, .gfield_description) {
                    color: black !important;
                }
                .pwelement_' . self::$rnd_id . ' input:not([type=checkbox]) {
                    border-radius: 11px !important;
                }
                .pwelement_' . self::$rnd_id . ' input[type=checkbox] {
                    border-radius: 2px !important;
                }
                .pwelement_' . self::$rnd_id . ' input[type=submit] {
                    background-color: #A6CE39 !important;
                    border-width: 1px !important;
                }
                .pwelement_' .self::$rnd_id. ' .gform_fields {
                    padding-left: 0 !important;
                }
                .pwelement_' .self::$rnd_id. ' .gform-field-label {
                    display: inline !important;
                }
                .pwelement_' .self::$rnd_id. ' .gform-field-label .show-consent,
                .pwelement_' .self::$rnd_id. ' .gform-field-label .gfield_required_asterisk {
                    display: inline !important;
                    margin-left: 0;
                    padding-left: 0;
                }
                .pwelement_' .self::$rnd_id. ' .gfield_required {
                    display: none !important;
                }
                // .pwelement_' .self::$rnd_id. ' .gform_button {
                //     visibility: hidden !important;
                //     width: 0 !important;
                //     height: 0 !important;
                //     padding: 0 !important;
                //     margin: 0 !important;
                // }
                /*ROZWIJANE ZGODY*/
                .pwelement_' .self::$rnd_id. ' .gfield_consent_description {
                    overflow: hidden !important;
                    max-height: auto !important;
                    border: none !important;
                    display: none;
                }
                .pwelement_' .self::$rnd_id. ' .show-consent:hover{
                    cursor: pointer;
                }
                .pwelement_' .self::$rnd_id. ' .ginput_container input {
                    margin: 0 !important;
                }
                .pwelement_' .self::$rnd_id. ' .gfield_label {
                    font-size: 14px !important;
                }
                .pwelement_' .self::$rnd_id. ' .gfield_consent_label {
                    padding-left: 5px;
                }
                @media (max-width:650px) {
                    .pwelement_' .self::$rnd_id. ' .gform_legacy_markup_wrapper .gform_footer {
                        margin: 0 auto !important;
                        padding: 0 !important;
                        text-align: center;
                    }
                }
                @media (max-width:400px) {
                    .pwelement_' .self::$rnd_id. ' input[type="submit"] {
                        font-size: 12px !important;
                    }
                }
            </style>
        ';
        return $css_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {

        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') .' !important';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') .' !important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'black') .'!important';
        $btn_border = '1px solid ' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'black') .' !important';

        $btn_color_vip = '#B69663';
        $darker_btn_vip_color = self::adjustBrightness($btn_color_vip, -20);

        $btn_color_premium = self::$accent_color;
        $darker_btn_premium_color = self::adjustBrightness($btn_color_premium, -20);

        $output = '';

        global $registration_button_text, $registration_form_id;

        extract( shortcode_atts( array(
            'registration_select' => '',
            'registration_title' => '',
            'registration_text' => '',
            'registration_button_text' => '',
            'registration_height_logotypes' => '',
            'registration_modes' => '',
            'registration_form_id' => '',
            'registration_input_step' => '',
        ), $atts ));

        if ($registration_modes == "conference_mode") {
            $main_form_color = self::$accent_color;
        } else {
            $main_form_color = self::$main2_color;
        }

        $darker_btn_color = self::adjustBrightness($main_form_color, -20);

        if (empty($registration_height_logotypes)) {
            $registration_height_logotypes = '50px';
        }

        if (empty($registration_input_step)) {
            $registration_input_step = '10';
        }

        if ($registration_select == "header_registration") {
            if (get_locale() == 'pl_PL') {
                $registration_button_text = ($registration_button_text == "") ? "Zarejestruj się<span style='display: block; font-weight: 300;'>Odbierz darmowy bilet</span>" : $registration_button_text;
            } else {
                $registration_button_text = ($registration_button_text == "") ? "Register<span style='display: block; font-weight: 300;'>Get a free ticket</span>" : $registration_button_text;
            }
        } else if ($registration_select == "exhibitors") {
            if(get_locale() == 'pl_PL') {
                $registration_title = ($registration_title == "") ? "DLA WYSTAWCÓW" : $registration_title;
                $registration_text = ($registration_text == "") ? "Zapytaj o stoisko<br>Wypełnij poniższy formularz, a my skontaktujemy się z Tobą w celu przedstawienia preferencyjnych stawek* za powierzchnię wystawienniczą i zabudowę stoiska.<br>*oferta ograniczona czasowo" : $registration_text;
                $registration_button_text = ($registration_button_text == "") ? "WYŚLIJ" : $registration_button_text;
            } else {
                $registration_title = ($registration_title == "") ? "BOOK A STAND" : $registration_title;
                $registration_text = ($registration_text == "") ? "Ask for a stand<br>Fill out the form below and we will contact you to present preferential rates *  for the exhibition space and stand construction<br>* limited time offer" : $registration_text;
                $registration_button_text = ($registration_button_text == "") ? "SEND" : $registration_button_text;
            }
        } else if ($registration_select == "exhibitors_v2") {
            if(get_locale() == 'pl_PL') {
                $registration_button_text = ($registration_button_text == "") ? "WYŚLIJ" : $registration_button_text;
            } else {
                $registration_button_text = ($registration_button_text == "") ? "SEND" : $registration_button_text;
            }
        } else {
            if (get_locale() == 'pl_PL') {
                $registration_title = ($registration_title == "") ? "DLA ODWIEDZAJĄCYCH" : $registration_title;
                $registration_text = ($registration_text == "") ? "Wypełnij formularz i odbierz darmowy bilet" : $registration_text;
                $registration_button_text = ($registration_button_text == "") ? "Zarejestruj się<span style='display: block; font-weight: 300;'>Odbierz darmowy bilet</span>" : $registration_button_text;
            } else {
                $registration_title = ($registration_title == "") ? "FOR VISITORS" : $registration_title;
                $registration_text = ($registration_text == "") ? "Fill out the form and receive your free ticket" : $registration_text;
                $registration_button_text = ($registration_button_text == "") ? "Register<span style='display: block; font-weight: 300;'>Get a free ticket</span>" : $registration_button_text;
            }
        }

        $start_date = do_shortcode('[trade_fair_datetotimer]');
        $end_date = do_shortcode('[trade_fair_enddata]');

        // Function to transform the date
        function transform_dates($start_date, $end_date) {
            // Convert date strings to DateTime objects
            $start_date_obj = DateTime::createFromFormat('Y/m/d H:i', $start_date);
            $end_date_obj = DateTime::createFromFormat('Y/m/d H:i', $end_date);

            // Check if the conversion was correct
            if ($start_date_obj && $end_date_obj) {
                // Get the day, month and year from DateTime objects
                $start_day = $start_date_obj->format('d');
                $end_day = $end_date_obj->format('d');
                $month = $start_date_obj->format('m');
                $year = $start_date_obj->format('Y');

                //Build the desired format
                $formatted_date = "{$start_day}-{$end_day}|{$month}|{$year}";
                return $formatted_date;
            } else {
                return "Invalid dates";
            }
        }

        // Transform the dates to the desired format
        $formatted_date = transform_dates($start_date, $end_date);

        if (self::isTradeDateExist()) {
            $actually_date = (get_locale() == 'pl_PL') ? '[trade_fair_date]' : '[trade_fair_date_eng]';
        } else {
            $actually_date = $formatted_date;
        }

        // Create unique id for element
        $unique_id = rand(10000, 99999);
        $element_unique_id = 'pweRegistration-' . $unique_id;

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        if (isset($_SERVER['argv'][0])) {
            $source_utm = $_SERVER['argv'][0];
        } else {
            $source_utm = '';
        }

        if (strpos($source_utm, 'utm_source=premium') !== false) {
            $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badge-mockup.webp') ? '/doc/badge-mockup.webp' : '');
        } else {
            if (get_locale() == 'pl_PL') {
                $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup.webp') ? '/doc/badgevipmockup.webp' : '');
            } else {
                $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup-en.webp') ? '/doc/badgevipmockup-en.webp' : '/doc/badgevipmockup.webp');
            }
        }

        if (strpos($source_utm, 'utm_source=byli') !== false) {
            $output .= '
            <style>
                .pwelement_'.self::$rnd_id.' #pweRegister,
                .pwelement_'.self::$rnd_id.' input[type="submit"] {
                    background-color: '. $btn_color_vip .' !important;
                    border: 2px solid '. $btn_color_vip .' !important;
                    color: white;
                }
                .pwelement_'.self::$rnd_id.' #pweRegister:hover,
                .pwelement_'.self::$rnd_id.' input[type="submit"]:hover {
                    background-color: '. $darker_btn_vip_color .' !important;
                    border: 2px solid '. $darker_btn_vip_color .' !important;
                }
            </style>';
        } else if (strpos($source_utm, 'utm_source=premium') !== false) {
            $output .= '
            <style>
                .pwelement_'.self::$rnd_id.' #pweRegister,
                .pwelement_'.self::$rnd_id.' input[type="submit"] {
                    background-color: '. $btn_color_premium .' !important;
                    border: 2px solid '. $btn_color_premium .' !important;
                    color: white;
                }
                .pwelement_'.self::$rnd_id.' #pweRegister:hover,
                .pwelement_'.self::$rnd_id.' input[type="submit"]:hover {
                    background-color: '. $darker_btn_premium_color .' !important;
                    border: 2px solid '. $darker_btn_premium_color .' !important;
                }
            </style>';
        } else {
            $output .= '
            <style>
                .pwelement_'. self::$rnd_id .' #pweRegister,
                .pwelement_'.self::$rnd_id.' input[type="submit"] {
                    background-color: '. $main_form_color .' !important;
                    border: 2px solid '. $main_form_color .' !important;
                    color: '. $btn_text_color .';
                }
                .pwelement_'. self::$rnd_id .' #pweRegister:hover,
                .pwelement_'.self::$rnd_id.' input[type="submit"]:hover {
                    background-color: '. $darker_btn_color .' !important;
                    border: 2px solid '. $darker_btn_color .' !important;
                }
            </style>';
        }

        $output .= '
        <style>
            .pwelement_'.self::$rnd_id.' input[type="submit"] {
                border-radius: 10px !important;
                box-shadow: none !important;
            }
            .pwelement_'. self::$rnd_id .' .gfield--type-consent {
                line-height: 1.2 !important;
            }
            .pwelement_'. self::$rnd_id .' .gfield--type-consent input[type="checkbox"] {
                margin-top: 0 !important;
            }
        </style>';

        if ($registration_select == "visitors") {

            if (strpos($source_utm, 'utm_source=byli') !== false || strpos($source_utm, 'utm_source=premium') !== false) {
                $output .= '
                <style>
                    .row-parent:has(.pwelement_'. self::$rnd_id .') .wpb_column {
                        padding: 0 !important;
                    }
                    .row-parent:has(.pwelement_'. self::$rnd_id .') {
                        max-width: 100% !important;
                        padding: 0 !important;
                    }

                    /* Zaślepka START <------------------------------------------< */
                    .row-container:has(.pwelement_' . self::$rnd_id .') .wpb_column:not(:has(.pwe-registration.vip)) {
                        max-width: 100% !important;
                        width: 100% !important;
                        height: auto;
                    }
                    .row-container:has(.pwelement_' . self::$rnd_id .') .wpb_column:not(:has(.pwe-registration.vip)) .uncode-single-media-wrapper {
                        display: flex;
                        justify-content: center;
                    }
                    .row-container:has(.pwelement_' . self::$rnd_id .') .wpb_column:not(:has(.pwe-registration.vip)) img {
                        max-width: 300px !important;
                    }
                    /* Zaślepka END <------------------------------------------< */

                    .wpb_column:has(.pwelement_'. self::$rnd_id .') {
                        width: 66% !important;
                        height: auto;
                    }
                    .pwelement_'. self::$rnd_id .',
                    .pwe-registration {
                        height: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-registration {
                        display: flex;
                    }
                    .pwelement_' .self::$rnd_id. ' .pwe-mockup-column {
                        width: 50%;
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: cover;
                    }
                    .pwelement_' .self::$rnd_id. ' .pwe-mockup-column img {
                        height: 100%;
                        float: right;
                        object-fit: cover;
                    }
                    .pwelement_' .self::$rnd_id. ' .pwe-registration-column {
                        position: relative;
                        background-color: #E8E8E8;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        padding: 36px 18px;
                        width: 50%;
                        gap: 18px;
                    }
                    .pwelement_' .self::$rnd_id. ' .pwe-registration-title {
                        min-width: 350px;
                    }
                    .pwelement_' .self::$rnd_id. ' .gform_wrapper {
                        max-width: 350px;
                    }
                    .pwelement_'. self::$rnd_id .'  .gform_footer {
                        text-align: center;
                    }
                    .pwelement_'. self::$rnd_id .' .gform_wrapper :is(label, .gfield_description, .show-consent) {
                        color: black;
                    }
                    .pwelement_'. self::$rnd_id .' :is(input[type="text"], input[type="number"], input[type="email"], input[type="tel"]) {
                        border: 2px solid #d6d6d6 !important;
                        border-radius: 10px;
                        box-shadow: none !important;
                        margin: 0;
                        font-size: 14px !important;
                    }
                    .pwelement_'. self::$rnd_id .' input[type="checkbox"] {
                        border: 2px solid #d6d6d6 !important;
                        border-radius: 50%;
                    }
                    // .pwelement_'.self::$rnd_id.' .gform_button {
                    //     visibility: hidden !important;
                    //     width: 0 !important;
                    //     height: 0 !important;
                    //     padding: 0 !important;
                    //     margin: 0 !important;
                    // }
                    .pwelement_'. self::$rnd_id .' .gfield_consent_label  {
                        font-size: 10px;
                        line-height: 1.2 !important;
                    }
                    .pwelement_'.self::$rnd_id.' .gfield_label {
                        font-size: 14px !important;
                    }
                    .pwelement_'.self::$rnd_id.' .gfield_required_asterisk {
                        display: none !important;
                    }
                    .pwelement_'.self::$rnd_id.' .pwe-registration-step-text {
                        width: 100%;
                        position: absolute;
                        top: 18px;
                        left: 18px;
                    }
                    .pwelement_'.self::$rnd_id.' .pwe-registration-step-text p {
                        margin: 0;
                    }
                    .pwelement_'.self::$rnd_id.' .gform_legacy_markup_wrapper ul.gform_fields li.gfield {
                        padding-right: 0;
                    }
                    @media (max-width: 1150px) {
                        .wpb_row:has(#'. $element_unique_id .') {
                            display: flex !important;
                            flex-direction: column !important;
                        }
                        .wpb_column:has(.pwelement_'. self::$rnd_id .') {
                            width: 100% !important;
                        }
                        .pwelement_' .self::$rnd_id. ' .pwe-mockup-column,
                        .pwelement_' .self::$rnd_id. ' .pwe-registration-column {
                            width: 100%;
                        }
                        .pwelement_' .self::$rnd_id. ' .pwe-registration-column {
                            padding: 72px 18px;
                        }
                    }
                    @media (max-width: 960px) {
                        .wpb_column:has(#'. $element_unique_id .') {
                            max-width: 100% !important;
                            padding: 0 !important;
                        }
                        .row-parent:has(#'. $element_unique_id .') {
                            padding: 0 !important;
                        }
                        .pwelement_' .self::$rnd_id. ' .pwe-registration-column {
                            padding: 36px 18px 18px;
                        }
                    }
                    @media (max-width: 750px) {
                        .pwelement_'. self::$rnd_id .' .pwe-registration {
                            flex-direction: column;
                        }
                        .pwelement_' .self::$rnd_id. ' .pwe-registration-title {
                            min-width: auto;
                        }
                        .pwelement_' .self::$rnd_id. ' .pwe-mockup-column {
                            height: 400px;
                        }
                    }
                </style>';

                if (strpos($source_utm, 'utm_source=byli') !== false) {
                    $output .= '
                    <style>
                        .pwelement_' .self::$rnd_id. ' .pwe-mockup-column {
                            background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/gen-bg.jpg);
                        }
                    </style>';
                }

                $output .= '
                <div id="'. $element_unique_id .'" class="pwe-registration vip">
                    <div class="pwe-reg-column pwe-mockup-column">
                        <img src="'. $badgevipmockup .'">
                    </div>
                    <div class="pwe-reg-column pwe-registration-column">
                        <div class="pwe-registration-step-text">
                            <p>'.
                                self::languageChecker(
                                    <<<PL
                                        Krok 1 z 2
                                    PL,
                                    <<<EN
                                        Step 1 of 2
                                    EN
                                )
                            .'</p>
                        </div>
                        <div class="pwe-registration-title">
                            <h4>'.
                                self::languageChecker(
                                    <<<PL
                                        Twój bilet na targi
                                    PL,
                                    <<<EN
                                        Your ticket to the fair
                                    EN
                                )
                            .'</h4>
                        </div>
                        <div class="pwe-registration-form">
                            [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                        </div>
                    </div>
                </div>';
            } else {

                $output .= '
                <style>
                    // .row-container:has(.pwe-registration) .wpb_column:has(.exhibitors-catalog):has(#top10) {
                    //     display: none !important;
                    // }
                    .row-container:has(.pwelement_'. self::$rnd_id .') {
                        background-image: url(/doc/background.webp);
                        background-repea: no-repeat;
                        background-position: center;
                        background-size: cover;
                    }
                    .exhibitors-catalog:has(#top10) {
                        background-color: white;
                        border: 2px solid '. $main_form_color .' !important;
                        border-radius: 18px;
                    }
                    @media (min-width: 959px) {
                        .row-container:has(#pweForm) .wpb_column,
                        .row-container:has(#top10) .wpb_column {
                            display: none;
                        }
                        .wpb_column:has(#top10),
                        .wpb_column:has(#pweForm) {
                            display: table-cell !important;
                        }
                    }
                    .wpb_column #pweForm {
                        margin: 0 auto;
                    }
                    .wpb_column:has(#pweForm) {
                        padding: 0;
                    }
                </style>';

                if (glob($_SERVER['DOCUMENT_ROOT'] . '/doc/header_mobile.webp', GLOB_BRACE)) {
                    $output .= '
                    <style>
                        @media (max-width: 960px) {
                            background-image: url(/doc/header_mobile.webp);
                        }
                    </style>';
                }

                $output .= '
                <div id="'. $element_unique_id .'" class="pwe-registration">
                    <div class="pwe-registration-column">';

                    include_once plugin_dir_path(__FILE__) . '/../elements/registration-header.php';
                    $output .= PWElementRegHeader::output($registration_form_id, $registration_modes, $registration_logo_color = "", $actually_date, $registration_name = "visitors");

                $output .= '
                    </div>
                </div>';

            }

        } else if ($registration_select == "exhibitors") {

            $output .= '
            <style>
                @media (min-width: 959px) {
                    // .row-container:has(#'. $element_unique_id .') .wpb_column,
                    // .row-container:has(#top10) .wpb_column {
                    //     display: none;
                    // }
                    .wpb_column:has(#top10),
                    .wpb_column:has(#'. $element_unique_id .') {
                        display: table-cell !important;
                    }
                }
                .wpb_column #'. $element_unique_id .' {
                    margin: 0 auto;
                }
                #'. $element_unique_id .' {
                    max-width: 555px !important;
                }
            </style>';

            if ($mobile != 1) {
                $output .= '<style>
                                .row-container:has(.img-container-top10) .img-container-top10 div {
                                    min-height: '. $registration_height_logotypes .';
                                    margin: 10px 5px !important;
                                }
                            </style>';
            }

            $output .= self::custom_css_1();
            $output .= '
            <div id="'. $element_unique_id .'" class="pwe-registration">
                <div class="pwe-registration-column">
                    <div id="pweFormContent" class="pwe-form-content">
                        <div class="pwe-registration-title main-heading-text">
                            <h4 class="custom-uppercase"><span>'. $registration_title .'</span></h4>
                        </div>
                        <div class="pwe-registration-text">';
                            $registration_text = str_replace(array('`{`', '`}`'), array('[', ']'), $registration_text);
                            $output .= '<p>'. wpb_js_remove_wpautop($registration_text, true) .'</p>
                        </div>
                    </div>
                    <div class="pwe-registration-form">
                        [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                    </div>
                </div>
            </div>';

        } else if ($registration_select == "exhibitors_v2") {

            $fair_logo = (isset($atts['fair_logo']) && $atts['fair_logo'] != '') ? $atts['fair_logo'] : self::languageChecker(
                <<<PL
                    /doc/logo-color.webp
                PL,
                <<<EN
                    /doc/logo-color-en.webp
                EN
            );
            $fair_logo = trim($fair_logo);


            $output .= '
            <style>
                .row-parent:has(.pwelement_' . self::$rnd_id . ') {
                    max-width: 100%;
                    padding: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration {
                    display: flex;
                    flex-direction: column;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-wrapper {
                    display: flex;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-column {
                    width: 50%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-form {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 18px 36px;
                }

                .pwelement_'. self::$rnd_id .' input[type="text"], input[type="number"], input[type="email"], input[type="tel"] {
                    border: 2px solid !important;
                    border-radius: 10px;
                    box-shadow: none;
                }
                .pwelement_'. self::$rnd_id .' input[type="checkbox"] {
                    min-width: 16px;
                    width: 16px;
                    height: 16px;
                    border-radius: 50%;
                }
                .pwelement_'. self::$rnd_id .' .gform_wrapper :is(label, .gfield_description) {
                    color: black;
                }
                .pwelement_'. self::$rnd_id .' .gfield_consent_label span {
                    display: inline-block !important;
                }
                .pwelement_'. self::$rnd_id .' input[type="number"]::-webkit-outer-spin-button,
                .pwelement_'. self::$rnd_id .' input[type="number"]::-webkit-inner-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' input[type="number"] {
                    -moz-appearance: textfield;
                }
                .pwelement_'. self::$rnd_id .' .gform_footer {
                    display: flex;
                    justify-content: center;
                    margin: 0 auto;
                }
                // .pwelement_' .self::$rnd_id. ' .gform_button {
                //     visibility: hidden !important;
                //     width: 0 !important;
                //     height: 0 !important;
                //     padding: 0 !important;
                //     margin: 0 !important;
                // }
                .pwelement_' .self::$rnd_id. ' .show-consent {
                    color: black;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-wrapper {
                    position: relative;
                    background-color: #ffffff;
                    border-radius: 10px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-wrapper h4 {
                    font-size: 16px;
                    font-weight: 700;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-inputs {
                    position: relative;
                    width: 100%;
                    height: 50px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"] {
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                    width: 100%;
                    outline: none;
                    position: absolute;
                    padding: 0 !important;
                    margin: auto;
                    top: 0;
                    bottom: 0;
                    background-color: transparent;
                    pointer-events: none;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-track {
                    width: 100%;
                    height: 5px;
                    position: absolute;
                    margin: auto;
                    top: 5px;
                    bottom: 0;
                    border-radius: 5px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]::-webkit-slider-runnable-track {
                    color: '. self::$main2_color .';
                    -webkit-appearance: none;
                    height: 5px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]::-moz-range-track {
                    -moz-appearance: none;
                    height: 5px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]::-ms-track {
                    appearance: none;
                    height: 5px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]::-webkit-slider-thumb {
                    -webkit-appearance: none;
                    height: 1.7em;
                    width: 1.7em;
                    background-color: '. self::$main2_color .';
                    cursor: pointer;
                    margin-top: -9px;
                    pointer-events: auto;
                    border-radius: 50%;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]::-moz-range-progress {
                    background-color: '. self::$main2_color .';
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]::-moz-range-thumb {
                    -webkit-appearance: none;
                    height: 1.7em;
                    width: 1.7em;
                    cursor: pointer;
                    border-radius: 50%;
                    background-color: '. self::$main2_color .';
                    pointer-events: auto;
                    border: none;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]::-ms-thumb {
                    appearance: none;
                    height: 1.7em;
                    width: 1.7em;
                    cursor: pointer;
                    border-radius: 50%;
                    background-color: '. self::$main2_color .';
                    pointer-events: auto;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container input[type="range"]:active::-webkit-slider-thumb {
                    background-color: #ffffff;
                    border: 1px solid '. self::$main2_color .';
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-values {
                    display: flex;
                    gap: 12px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-values .input-container {
                    position: relative;
                    display: inline-block;
                }
                .pwelement_'. self::$rnd_id .' .input-range-values input {
                    width: 100px;
                    padding-right: 20px;
                    height: 43px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-values .unit-label {
                    position: absolute;
                    top: 16px;
                    right: 8px;
                    font-weight: 600;
                    pointer-events: none;
                }
                .pwelement_'. self::$rnd_id .' .input-range-value-label {
                    display: flex;
                    align-items: center;
                    font-weight: 600;
                    margin: 9px 0px 0px 0px
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom {
                    background-color: #f4f4f4;
                    display: flex;
                    justify-content: space-around;
                    gap: 18px;
                    flex-wrap: wrap;
                    padding: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom div {
                    display: flex;
                    justify-content: center;
                    flex-wrap: wrap;
                    gap:9px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom .logos,
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom .numbers {
                    justify-content: space-around;
                    width: 49%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom .logos div {
                    min-width: auto;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom .numbers div {
                    min-width: 200px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom img {
                    max-height: 80px;
                    object-fit: contain;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom :is(.for-exhibitors, .for-visitors) {
                    display: flex;
                    justify-content: flex-start;
                    align-items: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-registration-bottom :is(.for-exhibitors, .for-visitors) p {
                    margin-top: 0px;
                }
                .pwelement_'. self::$rnd_id .' .iframe-column {
                    position: relative;
                }
                .pwelement_'. self::$rnd_id .' .iframe-column img {
                    position: absolute;
                    left: 18px;
                    bottom: 18px;
                    z-index: 1;
                    width: 70px;
                }
                .pwelement_'. self::$rnd_id .' .video-container {
                    position: relative;
                    width: 100%;
                    height: 100%;
                }
                .pwelement_'. self::$rnd_id .' .video-container iframe {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    pointer-events: none;
                }
                .pwelement_'. self::$rnd_id .' .fair-logo img,
                .pwelement_'. self::$rnd_id .' .pwe-logo img {
                    max-width: 170px;
                }
                @media (max-width: 960px) {
                    .row-parent:has(#'. $element_unique_id .') {
                        padding: 0 !important;
                    }
                    .wpb_column:has(.pwelement_'. self::$rnd_id .') {
                        max-width: 100% !important;
                        padding: 0 !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-registration-wrapper {
                        flex-direction: column-reverse;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-registration-column {
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .iframe-column img {
                        display: none;
                    }
                    .pwelement_'. self::$rnd_id .' .video-container {
                        display: none;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-registration-bottom .logos,
                    .pwelement_'. self::$rnd_id .' .pwe-registration-bottom .numbers {
                        width: 100%;
                    }
                }
                @media (max-width: 500px) {
                    .pwelement_'. self::$rnd_id .' .pwe-registration-bottom .numbers div {
                        min-width: 250px;
                    }
                }
            </style>';

            $output .= '
            <div id="'. $element_unique_id .'" class="pwe-registration">
                <div class="pwe-registration-wrapper">
                    <div class="pwe-registration-column iframe-column">
                        <img class="logo-pwe" src="/wp-content/plugins/pwe-media/media/logo_pwe.webp">
                        <div class="video-container">
                            <iframe src="https://www.youtube.com/embed/49KljiYGLA0?si=rDFQfo6rApq_fbZJ&autoplay=1&mute=1&loop=1&controls=0&showinfo=0&playlist=49KljiYGLA0"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                        </div>
                    </div>
                    <div class="pwe-registration-column form-column">
                        <div class="pwe-registration-form">
                            <h1>'.
                            self::languageChecker(
                                <<<PL
                                Zapytaj o stoisko
                                PL,
                                <<<EN
                                Ask for a stand
                                EN
                            )
                            .'</h1>
                            [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                        </div>
                    </div>
                </div>
                <div class="pwe-registration-bottom">
                    <div class="logos">
                        <div class="pwe-logo">
                            <a href="https://warsawexpo.eu/" target="_blanc"><img src="' . plugin_dir_url(dirname( __FILE__ )) . "/media/logo_pwe_black.webp" . '"></a>
                        </div>
                        <div class="fair-logo">
                            <a href="'.
                            self::languageChecker(
                                <<<PL
                                /
                                PL,
                                <<<EN
                                    /en/
                                EN
                            )
                            .'"><img src="' . $fair_logo . '"></a>
                        </div>
                    </div>
                    <div class="numbers">
                        <div class="for-exhibitors">
                            <i class="fa fa-envelope-o fa-3x fa-fw"></i>
                            <p>'.
                            self::languageChecker(
                                <<<PL
                                    "Zostań wystawcą"
                                PL,
                                <<<EN
                                    "Become an exhibitor"
                                EN
                            )
                        .'<br> <a href="tel:48 517 121 906">+48 517 121 906</a>
                        </div>
                        <div class="for-visitors">
                            <i class="fa fa-phone fa-3x fa-fw"></i>
                            <p>'.
                            self::languageChecker(
                                <<<PL
                                    "Odwiedzający"
                                PL,
                                <<<EN
                                    "Visitors"
                                EN
                            )
                        .'<br> <a href="tel:48 513 903 628">+48 513 903 628</a>
                        </div>
                    </div>
                </div>
            </div>';

            // $output .= '
            // <script>
            //     const sliderContainer = document.createElement("div");
            //     sliderContainer.className = "input-range-container";
            //     sliderContainer.innerHTML = `
            //         <div class="input-range-wrapper">
            //             <h4>'.
            //                 self::languageChecker(
            //                     <<<PL
            //                     Wybierz powierzchnię wystawienniczą
            //                     PL,
            //                     <<<EN
            //                     Choose an exhibition space
            //                     EN
            //                 )
            //             .'</h4>
            //             <div class="input-range-inputs">
            //                 <div class="input-range-track"></div>
            //                 <input type="range" min="0" max="180" value="0" step="'. $registration_input_step .'", id="inputRange1" oninput="slideOne()">
            //                 <input type="range" min="0" max="180" value="180" step="'. $registration_input_step .'" id="inputRange2" oninput="slideTwo()">
            //             </div>
            //             <div class="input-range-values">
            //                 <span class="input-range-value-label">od</span>
            //                 <div class="input-container">
            //                     <input type="number" min="0" max="999" value="0" id="inputRangeValue1" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3)">
            //                     <span class="unit-label">m²</span>
            //                 </div>
            //                 <span class="input-range-value-label">do</span>
            //                 <div class="input-container">
            //                     <input type="number" min="0" max="999" value="180" id="inputRangeValue2" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3)">
            //                     <span class="unit-label">m²</span>
            //                 </div>
            //             </div>
            //         </div>
            //     `;

            //     const form = document.querySelector(".pwelement_'. self::$rnd_id .' form");
            //     const formEmail = form.querySelector(".ginput_container_email");

            //     formEmail.insertAdjacentElement("afterend", sliderContainer);

            //     function updateArea() {
            //         areaInput.value = minValue.value + " - " + maxValue.value + " m²";
            //     }

            //     document.addEventListener("DOMContentLoaded", function () {
            //         slideOne();
            //         slideTwo();
            //         fillColor();
            //     });

            //     let sliderOne = document.getElementById("inputRange1");
            //     let sliderTwo = document.getElementById("inputRange2");
            //     let displayValOne = document.getElementById("inputRangeValue1");
            //     let displayValTwo = document.getElementById("inputRangeValue2");
            //     let minGap = 0;
            //     let sliderTrack = document.querySelector(".input-range-track");
            //     let sliderMaxValue = sliderOne.max;

            //     function slideOne() {
            //         if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
            //             sliderOne.value = parseInt(sliderTwo.value) - minGap;
            //         }
            //         displayValOne.value = sliderOne.value;
            //         fillColor();
            //         updateArea();
            //     }

            //     function slideTwo() {
            //         if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
            //             sliderTwo.value = parseInt(sliderOne.value) + minGap;
            //         }
            //         displayValTwo.value = sliderTwo.value;
            //         fillColor();
            //         updateArea();
            //     }

            //     function fillColor() {
            //         let percent1 = ((sliderOne.value - sliderOne.min) / (sliderMaxValue - sliderOne.min)) * 100;
            //         let percent2 = ((sliderTwo.value - sliderTwo.min) / (sliderMaxValue - sliderTwo.min)) * 100;
            //         sliderTrack.style.background = `linear-gradient(to right, #dadae5 ${percent1}% , '. self::$accent_color .' ${percent1}% , '. self::$accent_color .' ${percent2}%, #dadae5 ${percent2}%)`;
            //     }

            //     function updateArea() {
            //         const areaContainer = document.getElementsByClassName("input-area")[0];
            //         if (areaContainer) {
            //             const areaInput = areaContainer.getElementsByTagName("input")[0];
            //             if (areaInput) {
            //                 areaInput.value = displayValOne.value + " - " + displayValTwo.value + " m²";
            //             }
            //         }
            //     }
            // </script>';

        }

        if (class_exists('GFAPI')) {
            function get_form_id_by_title($title) {
                $forms = GFAPI::get_forms();
                foreach ($forms as $form) {
                    if ($form['title'] === $title) {
                        return $form['id'];
                    }
                }
                return null;
            }

            // function custom_gform_submit_button($button, $form) {
            //     global $registration_button_text, $registration_form_id;
            //     $registration_form_id_nmb = get_form_id_by_title($registration_form_id);

            //     if ($form['id'] == $registration_form_id_nmb) {
            //         $button = '<input type="submit" id="gform_submit_button_'. $registration_form_id_nmb .'" class="gform_button button" value="'.$registration_button_text.'" onclick="if(window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]){return false;}  if( !jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity || jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity()){window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]=true;}  " onkeypress="if( event.keyCode == 13 ){ if(window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]){return false;} if( !jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity || jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity()){window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]=true;}  jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;).trigger(&quot;submit&quot;,[true]); }">
            //         <button id="pweRegister" class="btn pwe-btn">'. $registration_button_text .'</button>';
            //     }
            //     return $button;
            // }
            // add_filter('gform_submit_button', 'custom_gform_submit_button', 10, 2);
        }

        $output .= '
        <script>

            // Funkcja zapisująca atrybut title do input
            function updateCountryInput() {
                // Znajdź element <div> z klasą iti__selected-flag
                const selectedFlag = document.querySelector(".iti__flag-container .iti__selected-flag");
                if (selectedFlag) {
                    // Pobierz wartość atrybutu title
                    let countryTitle = selectedFlag.getAttribute("title");

                    // Znajdź element input o klasie country
                    const countryInput = document.querySelector(".country input");
                    if (countryInput) {
                        // Zapisz wartość title do input
                        countryInput.value = countryTitle;
                    }
                }
            }

            // Funkcja dodająca nasłuchiwanie zdarzeń do elementów formularza
            function updateCountryInput() {
                const countryInput = document.querySelector(".country input");
                const selectedFlag = document.querySelector(".iti__selected-flag");
                if (countryInput && selectedFlag) {
                    countryInput.value = selectedFlag.getAttribute("title") || "";
                }
            }

            function addEventListenersToForm() {
                document.querySelectorAll("input, select, textarea, button").forEach(element => {
                    ["change", "input", "click", "focus"].forEach(event => {
                        element.addEventListener(event, updateCountryInput);
                    });
                });
            }

            function observeFlagChanges() {
                const selectedFlag = document.querySelector(".iti__selected-flag");
                if (selectedFlag) {
                    new MutationObserver(mutations => {
                        if (mutations.some(mutation => mutation.attributeName === "aria-expanded")) {
                            updateCountryInput();
                        }
                    }).observe(selectedFlag, { attributes: true });
                }
            }

            // Uruchomienie funkcji
            addEventListenersToForm();
            observeFlagChanges();

            window.onload = function () {
                function getCookie(name) {
                    let value = "; " + document.cookie;
                    let parts = value.split("; " + name + "=");
                    if (parts.length === 2) return parts.pop().split(";").shift();
                    return null;
                }

                function deleteCookie(name) {
                    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                }

                let utmPWE = "'. $source_utm .'";
                let utmCookie = getCookie("utm_params");
                let utmInput = document.querySelector(".utm-class input");

                if (utmCookie && (utmCookie.includes("utm_source=byli") || utmCookie.includes("utm_source=premium"))) {
                    deleteCookie("utm_params");
                }

                if (utmInput) {
                    utmInput.value = utmPWE;
                }

                const buttonSubmit = document.querySelector("#'. $element_unique_id .' .gform_footer input[type=submit]");

                if (buttonSubmit) {
                    buttonSubmit.addEventListener("click", function (event) {
                        const emailValue = document.getElementsByClassName("ginput_container_email")[0].getElementsByTagName("input")[0].value;

                        let telValue;
                        const telContainer = document.getElementsByClassName("ginput_container_phone")[0];

                        if (telContainer) {
                            telValue = telContainer.getElementsByTagName("input")[0].value;
                        } else {
                            telValue = "123456789";
                        }

                        let countryValue = "";
                        const countryContainer = document.getElementsByClassName("country")[0];
                        if (countryContainer) {
                            const countryInput = countryContainer.getElementsByTagName("input")[0];
                            if (countryInput) {
                                countryValue = countryInput.value;
                            }
                        }

                        localStorage.setItem("user_email", emailValue);
                        localStorage.setItem("user_country", countryValue);
                        localStorage.setItem("user_tel", telValue);';

                        if (get_locale() == 'pl_PL') {
                            $output .= 'localStorage.setItem("user_direction", "rejpl");';
                        } else {
                            $output .= 'localStorage.setItem("user_direction", "rejen");';
                        }

                        $output .= '
                        const areaContainer = document.getElementsByClassName("input-area")[0];
                        if (areaContainer) {
                            const areaValue = areaContainer.getElementsByTagName("input")[0].value;
                            localStorage.setItem("user_area", areaValue);
                        }
                    });
                }
            }

        </script>';

        return $output;
    }
}