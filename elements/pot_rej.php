<?php

/**
 * Class PWElementPotwierdzenieRejestracji
 * Extends PWElements class and defines a pwe Visual Composer element for x-steps-form.
 */
class PWElementPotwierdzenieRejestracji extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function add_field_apartment($reg_form_name_pr) {

        $form_id = PWECommonFunctions::findFormsID($reg_form_name_pr);

        if ( empty( $form_id ) ) {
            return;
        }

        $done_option_key = "gf_apartment_added_$form_id";

        // delete_option( $done_option_key );

        // Jeśli już zrobione -> nic nie rób
        if ( get_option( $done_option_key ) ) {
            return;
        }

        $apartment_exists = false;

        $form = GFAPI::get_form( $form_id );
        if ( ! $form || is_wp_error( $form ) ) {
            echo "<script>console.log('GF: nie udało się pobrać formularza');</script>";
            return;
        }

        $form_title = isset($form['title']) ? $form['title'] : '';

        if (strpos($form_title, 'Rejestracja PL') === false ) {
            return;
        }

        // Sprawdź, czy pole już istnieje
        foreach ( $form['fields'] as $field ) {
            if ( ! empty( $field->adminLabel ) && ($field->adminLabel === 'apartment' || $field->adminLabel === 'local') ) {
                update_option( $done_option_key, 1 );
                return;
            }
        }

        $next_id = max( wp_list_pluck( $form['fields'], 'id' ) ) + 1;

        $new_apartment_field = new GF_Field_Text(array(
            'id'            => $next_id,
            'label'         => 'Numer lokalu',
            'adminLabel'    => 'apartment',
            'visibility'    => 'hidden',
            'inputName'     => 'numer_lokalu',
            'isRequired'    => false,
            'cssClass'      => 'pwe-field-apartment',
        ));

        $insert_index = null;
        foreach ($form['fields'] as $index => $field) {
            if (!empty($field->adminLabel) && $field->adminLabel === 'house') {
                $insert_index = $index + 1;
                break;
            }
        }

        if ($insert_index === null) {
            $form['fields'][] = $new_apartment_field;
        } else {
            array_splice($form['fields'], $insert_index, 0, array($new_apartment_field));
        }

        $result = GFAPI::update_form( $form );

        if ( is_wp_error( $result ) ) {
            error_log( 'GF: błąd zapisu formularza – ' . $result->get_error_message() );
            return;
        }

        // Oznacz jako wykonane, żeby kod więcej nie biegał
        update_option( $done_option_key, 1 );
    }

    // /**
    //  * Static method to initialize Visual Composer elements.
    //  * Returns an array of parameters for the Visual Composer element.
    //  */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Registration form Guest', 'pwelement'),
                'param_name' => 'reg_form_name_pr',
                'save_always' => true,
                'value' => array_merge(
                    array('Wybierz' => ''),
                    self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPotwierdzenieRejestracji',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Update entries one form', 'pwelement'),
                'param_name' => 'reg_form_update_entries',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPotwierdzenieRejestracji',
                ),
            ),
        );
        return $element_output;
    }

     /**
     * Static method to display seccond step form (step2).
     * Returns the HTML output as a string.
     */
    public static function output($atts, $content = ''){
        extract( shortcode_atts( array(
            'reg_form_name_pr' => '',
            'reg_form_update_entries' => '',
        ), $atts ));

        self::add_field_apartment($reg_form_name_pr);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            empty($_SESSION['pwe_reg_entry']['entry_id']) &&
            ($reg_form_update_entries === "true") &&
            (!is_user_logged_in() || !current_user_can('administrator'))
        ) {
            header("Location: /rejestracja");
            exit();
        }

        $file_url = plugins_url('elements/fetch.php', dirname(__FILE__));

        $source_utm = $_SERVER['argv'][0];
        $selected_form_id = '';
        //$selected_form = '';

        if (strpos($source_utm, 'utm_source=byli') === false){
            $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color);
            $confirmation_page_text_btn = (get_locale() == 'pl_PL') ? "Zamawiam Bezpłatny identyfikator" : "Order your Free ID" ;
        } else if (strpos($source_utm, 'utm_source=byli') !== false){
            $btn_color = '#b69663';
            $confirmation_page_text_btn = (get_locale() == 'pl_PL') ? "Wyślij" : "Send" ;
        }

        $form_name = $reg_form_name_pr;

        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black');
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');

        $btn_shadow_color = self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black');
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color);

        $main_page_text_btn = (get_locale() == 'pl_PL') ? "Powrót do strony głównej" : "Back to main page" ;

        $fair_logo = (get_locale() == "pl_PL") ? "/doc/logo-color.webp" : "/doc/logo-color-en.webp";

        //Edytion number

        $trade_fair_edition_shortcode = do_shortcode('[trade_fair_edition]');
        if (strpos($trade_fair_edition_shortcode, '.') !== false) {
            $trade_fair_edition_text = (get_locale() == 'pl_PL') ? " edycja" : " edition";
        } else {
            $trade_fair_edition_text = (get_locale() == 'pl_PL') ? ". edycja" : ". edition";
        }
        $trade_fair_edition_first = (get_locale() == 'pl_PL') ? "Premierowa Edycja" : "Premier Edition";
        $trade_fair_edition = (!is_numeric($trade_fair_edition_shortcode) || $trade_fair_edition_shortcode == 1) ? $trade_fair_edition_first : $trade_fair_edition_shortcode . $trade_fair_edition_text;

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

                /* Update text tranform */
        $lang = get_locale();

        $translations = [
            'pl' => [
                'name' => 'Imię i Nazwisko',
                'street' => 'Ulica',
                'house' => 'Numer budynku',
                'post' => 'Kod pocztowy',
                'city' => 'Miasto',
                'button' => 'Aktualizuj dane',
                'success' => 'Dane zostały zaktualizowane!',
                'error' => 'Wszystkie pola są wymagane!',
                'confirm_text' => 'Dziękujemy za skontaktowanie się z nami, odezwiemy się do Ciebie wkrótce.',
                'confirm_text_platyna' => 'Dziękujemy za rejestrację! Cieszymy się, że będziesz z nami na targach [trade_fair_name]. Do zobaczenia już wkrótce!',
            ],
            'en' => [
                'name' => 'Full Name',
                'street' => 'Street',
                'house' => 'Building',
                'post' => 'Postal Code',
                'city' => 'City',
                'button' => 'Update Data',
                'success' => 'Data has been updated!',
                'error' => 'All fields are required!',
                'confirm_text' => 'Thank you for contacting us, we will get back to you soon.',
                'confirm_text_platyna' => 'Thank you for registering! We are glad you will be with us at [trade_fair_name]. See you soon!',
            ]
        ];

        $t = (strpos($lang, 'en') !== false) ? $translations['en'] : $translations['pl'];

        $output = '
            <style>
                .pwelement_' . self::$rnd_id . ' #xForm{
                    display: flex;
                    min-height: 90vh;
                }
                .pwelement_' . self::$rnd_id . ' #xForm>div{
                    align-content: center;
                    min-height: 643px;
                    width: 33.3%;
                }
                .pwelement_' . self::$rnd_id . ' .very-strong{
                    font-weight:700;
                }
                .pwelement_' . self::$rnd_id . ' .form-3-left {
                    padding: 36px;
                }
                .pwelement_' . self::$rnd_id . ' .form-3-left h2 {
                    font-size: 24px;
                }
                .pwelement_' . self::$rnd_id . ' .form-3-left img {
                    width: 100%;
                }
                .pwelement_' . self::$rnd_id . ' .form-3 {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    text-align: left;
                    padding: 25px 50px;
                    background-color: #E8E8E8;
                    min-height: inherit;
                }
                .pwelement_' . self::$rnd_id . ' .form-3-right {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 27px;
                }
                .pwelement_' . self::$rnd_id . ' .golden-text {
                    color: #c49a62 !important;
                }
                .pwelement_' . self::$rnd_id . ' .silver-text {
                    color: #747474 !important;
                }
                .pwelement_' . self::$rnd_id . ' .form-3 form{
                    margin-top: 18px;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 form .form3-half{
                    display:flex;
                    gap:9px;
                }
                .pwelement_' . self::$rnd_id . ' .form-3 form .form3-half>div{
                    width: 100%;
                }

                .pwelement_' . self::$rnd_id . ' form{
                    margin-top:36px;
                }
                .pwelement_' . self::$rnd_id . ' form .gform_fields{
                    row-gap: 18px !important;
                }
                .pwelement_' . self::$rnd_id . ' form label{
                    margin-left: 5px;
                    text-align: left;
                    font-weight: 700 !important;
                }
                .pwelement_' . self::$rnd_id . ' form .gfield_required{
                    display: none !important;
                }
                .pwelement_' . self::$rnd_id . ' form .gform_footer{
                    visibility: hidden;
                    height:0;
                    margin: 0;
                    padding: 0;
                }
                .pwelement_' . self::$rnd_id . ' label{
                    padding-left: 10px;
                }
                .pwelement_'. self::$rnd_id .' .gform_wrapper :is(label, .gfield_description) {
                    color: black;
                }
                .pwelement_' . self::$rnd_id . ' form :is(input, textarea){
                    margin-bottom: 18px;
                    width: 100%;
                    border-radius: 11px !important;
                    border-color: #d6d6d6 !important;
                    box-shadow: none !important;
                }
                .pwelement_' . self::$rnd_id . ' .pwe_reg_visitor{
                    background-color: '. $btn_color .' !important;
                    border: 2px solid '. $btn_color .' !important;
                    color: '. $btn_text_color .';
                    margin-top: 36px;
                    border-radius: 10px !important;
                }
                .pwelement_' . self::$rnd_id . ' .pwe_reg_visitor:hover{
                    color: '. $btn_color .' !important;
                    background-color: white !important;
                    border: 2px solid '. $btn_color .' !important;
                }


                .pwelement_' . self::$rnd_id . ' .form-3 form div:has(button){
                    margin-top:18px;
                    text-align: center;
                    width: 100%;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 form button{
                    color: white;
                    background-color:' . $text_color . ';
                    border: 1px solid ' . $text_color . ';
                    border-radius: 11px;
                    text-wrap: balance;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 form button:hover{
                    color: black;
                    background-color: white;
                    border: 1px solid ' . $text_color . ';
                }

                .pwelement_' . self::$rnd_id . ' .form-3-right .pwe-link{
                    color: white;
                    background-color: black;
                    border: 1px solid black;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-right .pwe-link:hover{
                    color: black;
                    background-color: white;
                }

                .pwelement_' . self::$rnd_id . ' .pwe-submitting-buttons{
                    text-align: center;
                }

                .pwelement_' . self::$rnd_id . ' #xForm:has(.gform_confirmation_wrapper) .display-befor-subbmit{
                    display: none;
                }

                .pwelement_' . self::$rnd_id . ' .display-after-subbmit{
                    display: none;
                }

                .pwelement_' . self::$rnd_id . ' #xForm:has(.gform_confirmation_wrapper) .display-after-subbmit{
                    display: block !important;
                }
                .pwelement_' . self::$rnd_id . ' .pwe-submitting-buttons .pwe-btn {
                    transform: scale(1) !important;
                }
                .pwelement_'. self::$rnd_id .' .gform_wrapper :is(label, .gfield_description, legend),
                .pwelement_'. self::$rnd_id .' p {
                    color: '. $text_color .' !important;
                }
                .pwelement_'. self::$rnd_id .' .gform_wrapper :is(label, legend) {
                    font-size: 14px !important;
                    font-weight: 700;
                }
                .pwelement_'. self::$rnd_id .' .gform_confirmation_message:has(not(.vip_confirm)){
                    font-size: 20px;
                    font-weight: 600;
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .form-3-right_vip {
                    padding: 0 18px;
                    justify-content: center;
                }
                .pwelement_'. self::$rnd_id .' .form-3-right_vip>div {
                    max-width: 400px;
                }
                .pwelement_'. self::$rnd_id .' .form-3-left-vip {
                    margin-left: 0;
                    padding-left: 9px;
                    background-position: center;
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-image: url(/wp-content/plugins/pwe-media/media/background-vip.webp);
                }
                .pwelement_'. self::$rnd_id .' .vip_options {
                    margin: 50px 0;
                }
                .pwelement_'. self::$rnd_id .' .vip_options>div {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    margin: 9px;
                }
                .pwelement_'. self::$rnd_id .' .vip_options img {
                    width: 50px;
                }
                .pwelement_'. self::$rnd_id .' .vip_options_foot{
                    text-align: center;
                    font-weight: 700;
                    padding-bottom: 15px;
                }
                .pwelement_'. self::$rnd_id .' :is(.vip_options_foot p, .opis_vip){
                    font-size: 14px !important;
                }
                .pwelement_'. self::$rnd_id .' .vip_confirm h3{
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .vip_confirm p{
                    font-weight: 700;
                }
                .pwelement_'. self::$rnd_id .' .vip_confirm ul{
                    list-style: "-";
                    text-align: left;
                }
                .pwelement_'. self::$rnd_id .' .vip-pack{
                    max-width: 450px;
                    margin: 20px auto 0;
                    line-height: 1.2;
                    text-align: center;
                    font-size: 24px;
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
                .pwelement_'.self::$rnd_id.' .error-border {
                    border: 2px solid red !important;
                }
                .pwelement_'.self::$rnd_id.' .status-message.error {
                    border: 3px solid red;
                    padding: 9px 10px;
                    border-radius: 10px;
                    text-align: center;
                    background: white;
                    font-weight: 600;
                }
                @media (max-width:1100px) {
                    .pwelement_' . self::$rnd_id . ' .form-3 {
                        padding: 18px;
                    }
                }
                @media (min-width:570px) and (max-width:959px){
                    .pwelement_'. self::$rnd_id .' .form-3-right_vip {
                        padding: 0 25px;
                    }
                    .vc_row .full-width:has(.pwelement_' . self::$rnd_id . ') .wpb_column{
                        padding: 0 !important;
                    }
                    .vc_row .full-width:has(.pwelement_' . self::$rnd_id . ') {
                        padding: 0 !important;
                    }
                    .vc_row .full-width:has(.pwelement_' . self::$rnd_id . ') .wpb_column {
                        max-width: unset !important;
                    }
                    .pwelement_' . self::$rnd_id . ' #xForm {
                        gap: 0;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-3 {
                        padding: 18px;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-3-right-visit {
                        display:none;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-3-left-vip,
                    .pwelement_' . self::$rnd_id . ' .form-3-left-premium {
                        display: none;
                    }

                    .pwelement_' . self::$rnd_id . ' #xForm>div{
                        width: 50%;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-3-left {
                        margin-left: 0;
                    }
                }
                @media (max-width:569px){
                    .pwelement_' . self::$rnd_id . ' .form-3 {
                        padding: 36px;
                    }
                    .pwelement_'. self::$rnd_id .' .form-3-right_vip {
                        padding: 0 25px;
                    }
                    .pwelement_'. self::$rnd_id .' .vip-pack {
                        margin: 35px auto 0;
                    }
                    .vc_row .full-width:has(.pwelement_' . self::$rnd_id . ') .wpb_column{
                        padding: 0 !important;
                    }
                    .vc_row .full-width:has(.pwelement_' . self::$rnd_id . '){
                        padding: 0 !important;
                    }
                    .pwelement_' . self::$rnd_id . ' #xForm {
                        flex-direction: column;
                    }
                    .pwelement_' . self::$rnd_id . ' #xForm>div{
                        width: unset;
                        min-height: unset;
                    }
                    .pwelement_' . self::$rnd_id . ' :is(h2,h3,h4,h5,p){
                        margin-top: 18px;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-3 form button {
                        transform: scale(1);
                    }
                }
            </style>';
        if(strpos($source_utm, 'utm_source=platyna') === false){
            $output .= '
            <div id="xForm">';
            if (strpos($source_utm, 'utm_source=byli') === false) {
                $output .= '
                    <div class="form-3-left">
                        <div>'.
                            self::languageChecker(
                                <<<PL
                                    <h2 class="text-color-jevc-color display-befor-subbmit">Dziękujemy za rejestrację na <br><span class="very-strong">[trade_fair_name]!</span></h2>
                                    <h2 class="text-color-jevc-color display-after-subbmit">Dziękujemy za zamówienie pakietu VIP<br><span class="very-strong">[trade_fair_name]!</span></h2>

                                    <p class="">Cieszymy się, że dołączasz do naszego wydarzenia, pełnego nowości rynkowych i inspiracji do zastosowania w Twojej firmie.</p><br>

                                    <p class="display-befor-subbmit"><span class="very-strong">Zachęcamy do wypełnienia</span> ostatniego formularza, dzięki temu będziemy mogli przygotować dla Was <span class="very-strong">wyjątkowy pakiet powitalny VIP</span>, który usprawni Państwa pobyt na targach.</p>
                                    <p class="display-after-subbmit">Twój <span class="very-strong"> wyjątkowy pakiet powitalny VIP</span>  : spersonalizowany identyfikator wraz z planem/harmonogramem targów oraz kartę parkingowa, otrzymasz na podany w formularzu adres za pośrednictwem poczty polskiej na około tydzień przed targami.</p>
                                PL,
                                <<<EN
                                    <h2 class="text-color-jevc-color display-befor-subbmit">Thank you for registering at <br><span class="very-strong">[trade_fair_name_eng]!</span></h2>
                                    <h2 class="text-color-jevc-color display-after-subbmit">Thank you for ordering VIP welcome package <br><span class="very-strong">[trade_fair_name_eng]!</span></h2>

                                    <p class="">We are delighted that you are joining our event, full of market news and inspiration for use in your business.</p><br>

                                    <p class="display-befor-subbmit"><span class="very-strong">We encourage you to fill in</span> the last form, thanks to which we will be able to prepare for you a <span class="very-strong">exclusive VIP welcome package</span> that will enhance your stay at the fair.</p>
                                    <p class="display-after-subbmit">Your <span class="very-strong">exclusive VIP welcome package</span>  which includes a personalized badge with the trade fair plan/schedule and a parking card, will be sent to the address provided in the form via postal service approximately one week before the trade fair.</p>
                                EN
                            )
                        .'
                        </div>
                    </div>
                ';
            } else {
                if (strpos($source_utm, 'utm_source=byli') !== false ) {
                    if (get_locale() == 'pl_PL') {
                        $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup.webp') ? '/doc/badgevipmockup.webp' : '');
                    } else {
                        $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup-en.webp') ? '/doc/badgevipmockup-en.webp' : '/doc/badgevipmockup.webp');
                    }
                } else if( strpos($source_utm, 'utm_source=premium') !== false) {
                    $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badge-mockup.webp') ? '/doc/badge-mockup.webp' : '/doc/badge-mockup.webp');
                }
                $output .= '
                        <div class="form-3-left form-3-left-vip" style="padding:0;">
                            <img src="'. $badgevipmockup .'">
                        </div>
                    ';
            }
            $output .= '
                <div class="form-3">';
                    if (strpos($source_utm, 'utm_source=byli') === false) {
                        $output .=
                            self::languageChecker(
                                <<<PL
                                    <h3 class="display-befor-subbmit">Podaj adres, na który mamy wysłać <span class="golden-text">darmowy pakiet powitalny VIP</span></h3>
                                    <p class="display-befor-subbmit">Otrzymasz bezpłatny spersonalizowany identyfikator wraz z planem/harmonogramem targów oraz kartę parkingową.</p>
                                PL,
                                <<<EN
                                    <h3 class="display-befor-subbmit">Enter the address where we should send the <span class="golden-text">free VIP welcome pack</span></h3>
                                    <p class="display-befor-subbmit">You will receive a complimentary personalised badge along with the exhibition schedule/schedule and a parking pass.</p>
                                EN
                            );
                    } else {
                        if (strpos($source_utm, 'utm_source=byli') !== false) {
                            $output .='
                            <div class="pwe-registration-step-text">
                                <p>'.
                                    self::languageChecker(
                                        <<<PL
                                            Krok 2 z 2
                                        PL,
                                        <<<EN
                                            Step 2 of 2
                                        EN
                                    )
                                .'</p>
                            </div>' .
                            self::languageChecker(
                                <<<PL
                                    <p class="display-befor-subbmit vip-pack">Podaj adres, na który mamy wysłać <span class="golden-text">darmowy pakiet <strong>VIP</strong></span></p>
                                PL,
                                <<<EN
                                    <p class="display-befor-subbmit vip-pack">Enter the details to receive free <span class="golden-text">digital <strong>VIP</strong> package</span></p>
                                EN
                            );
                        }
                    }

                    if(!$reg_form_update_entries){
                    $output .= '
                        <div class="pwe-gravity-form">
                            [gravityform id="'. $form_name .'" title="false" description="false" ajax="false"]
                        </div>
                        <div class="pwe-submitting-buttons display-befor-subbmit">
                            <button id="pweSendStepTwo" class="btn pwe-btn pwe_reg_visitor">'. $confirmation_page_text_btn .'</button>
                        </div>
                        <div class="pwe-submitting-buttons display-after-subbmit">
                            <a href="'.
                            self::languageChecker(
                                <<<PL
                                /
                                PL,
                                <<<EN
                                    /en/
                                EN
                            )
                        .'"><button class="btn pwe-btn pwe_reg_visitor">'. $main_page_text_btn .'</button></a>
                        </div>
                    </div>';
                    } else {
                    $output .= '
                        <div class="pwe-gravity-form">
                            <div class="gf_browser_chrome gform_wrapper gravity-theme gform-theme--no-framework">
                                <form id="addressUpdateForm">
                                    <div class="gform-body gform_body">
                                        <div class="gform_fields">
                                            <div class="gfield gfield--width-full">
                                                <label class="gfield_label gform-field-label">' . $t['name'] . '</label>
                                                <input type="text" id="name" placeholder="' . $t['name'] . '" required />
                                            </div>
                                            <div class="gfield gfield--width-full">
                                                <label class="gfield_label gform-field-label">' . $t['street'] . '</label>
                                                <input type="text" id="street" placeholder="' . $t['street'] . '" required />
                                            </div>
                                            <div style="display: flex; flex-direction: row; gap: 10px; justify-content: space-between;">
                                                <div style="flex:1;"  class="gfield gfield--width-full">
                                                    <label class="gfield_label gform-field-label">' . $t['house'] . '</label>
                                                    <input type="text" id="house" placeholder="' . $t['house'] . '" required />
                                                </div>
                                                <div style="flex:1;"  class="gfield gfield--width-full">
                                                    <label class="gfield_label gform-field-label">Numer lokalu</label>
                                                    <input type="text" id="local" placeholder="Numer lokalu" required />
                                                </div>
                                            </div>
                                            <div style="display: flex; flex-direction: row; gap: 10px; justify-content: space-between;">
                                                <div style="flex:1;" class="gfield gfield--width-half">
                                                    <label class="gfield_label gform-field-label">' . $t['post'] . '</label>
                                                    <div style="display: flex; align-items: center;">
                                                        <input type="text" id="post1" maxlength="2" placeholder="00" required />
                                                        <span style="margin: 0 5px;">-</span>
                                                        <input type="text" id="post2" maxlength="3" placeholder="000" required />
                                                    </div>
                                                </div>
                                                <div style="flex:1;" class="gfield gfield--width-half">
                                                    <label class="gfield_label gform-field-label">' . $t['city'] . '</label>
                                                    <input type="text" id="city" placeholder="' . $t['city'] . '" required />
                                                </div>
                                            </div>
                                            <div id="statusMessage" class="status-message"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="pwe-submitting-buttons display-befor-subbmit">
                            <button id="pweSendStepTwo"  type="button" class="update-button btn pwe-btn pwe_reg_visitor"  onclick="updateGravityForm()">'. $confirmation_page_text_btn .'</button>
                        </div>
                        <div class="pwe-submitting-buttons display-after-subbmit">
                            <a href="'.
                            self::languageChecker(
                                <<<PL
                                /
                                PL,
                                <<<EN
                                    /en/
                                EN
                            )
                        .'"><button class="btn pwe-btn pwe_reg_visitor">'. $main_page_text_btn .'</button></a>
                        </div>
                    </div>
                    ';
                    }

                if(strpos($source_utm, 'utm_source=byli') === false) {
                    $output .= '
                        <div class="form-3-right form-3-right-visit">
                            <img src="/doc/badge-mockup.webp">
                        </div>
                    ';
                } else {
                    if (strpos($source_utm, 'utm_source=byli') !== false) {
                        $output .= '
                            <div class="form-3-right form-3-right_vip">
                                <div>
                                    <h3>'.
                                        self::languageChecker(
                                            <<<PL
                                                Pakiet VIP upoważnia do:
                                            PL,
                                            <<<EN
                                                The VIP package entitles you to:
                                            EN
                                        )
                                    .'</h3>
                                    <div class="vip_options">
                                        <div>
                                            <img src="/wp-content/plugins/pwe-media/media/vip_diament.webp">
                                            <span class="opis_vip">'.
                                                self::languageChecker(
                                                    <<<PL
                                                        Wstępu do strefy VIP
                                                    PL,
                                                    <<<EN
                                                        Admission to the VIP area
                                                    EN
                                                )
                                            .'</span>
                                        </div>
                                        <div>
                                            <img src="/wp-content/plugins/pwe-media/media/vip_ludzik.webp">
                                            <span class="opis_vip">'.
                                                self::languageChecker(
                                                    <<<PL
                                                        Uczestnictwa w wydarzeniach towarzyszących targom
                                                    PL,
                                                    <<<EN
                                                        Participation in events accompanying the fair
                                                    EN
                                                )
                                            .'</span>
                                        </div>
                                        <div>
                                            <img src="/wp-content/plugins/pwe-media/media/vip_wejscie-vip.webp">
                                            <span class="opis_vip">'.
                                                self::languageChecker(
                                                    <<<PL
                                                        Szybkiego wejścia na teren targów, gdzie czeka na Ciebie ponad 300 wystawców
                                                    PL,
                                                    <<<EN
                                                        Quick entry to the fairgrounds, where more than 300 exhibitors await you
                                                    EN
                                                )
                                            .'</span>
                                        </div>
                                        <div>
                                            <img src="/wp-content/plugins/pwe-media/media/vip_ulotka.webp">
                                            <span class="opis_vip">'.
                                                self::languageChecker(
                                                    <<<PL
                                                    Dostępu do materiałów targowych dostępnych wyłącznie w strefie VIP
                                                    PL,
                                                    <<<EN
                                                        Access to trade show materials available only in the VIP area
                                                    EN
                                                )
                                            .'</span>
                                        </div>
                                        <div>
                                            <img src="/wp-content/plugins/pwe-media/media/vip_wifi.webp">
                                            <span class="opis_vip">'.
                                                self::languageChecker(
                                                    <<<PL
                                                    Skorzystania z darmowego WI-FI i strefy ładowania urządzeń
                                                    PL,
                                                    <<<EN
                                                    Take advantage of free WI-FI and a device charging zone
                                                    EN
                                                )
                                            .'</span>
                                        </div>
                                    </div>
                                    <div class="vip_options_foot">
                                        <p>'.
                            self::languageChecker(
                                <<<PL
                                    Zarezerwuj swoje miejsce już dziś i podnieś swoje doświadczenie targowe na wyższy poziom!
                                PL,
                                <<<EN
                                    Reserve your spot today and elevate your trade show experience to the next level!
                                EN
                            )
                            .'</p>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            $output .= '
            </div>';
        } else {
            $output .= '
                <div id="pweRegistration" class="pwe-registration platyna">
                    <div class="pwe-registration-column">
                        <div id="pweForm">
                            <div class="pweform_container">
                                <div class="form">
                                    <h2 class="form-title">'. PWECommonFunctions::languageChecker('Podaj adres, na który mamy wysłać <span style="color:#616161">darmowy pakiet PLATINUM</span>', 'Enter the address to which we should send<br/>the <span style="color:#616161">free PLATINUM package</strong>') .'</h2>
                                    <div class="pwe-registration-form">
                                        <div class="pwe-gravity-form">
                                            <div class="gf_browser_chrome gform_wrapper gravity-theme gform-theme--no-framework">
                                                <form id="addressUpdateForm">
                                                    <div class="gform-body gform_body">
                                                        <div class="gform_fields">
                                                            <div class="gfield gfield--width-full">
                                                                <label class="gfield_label gform-field-label">' . $t['name'] . '</label>
                                                                <input type="text" id="name" placeholder="' . $t['name'] . '" required />
                                                            </div>
                                                            <div class="gfield gfield--width-full">
                                                                <label class="gfield_label gform-field-label">' . $t['street'] . '</label>
                                                                <input type="text" id="street" placeholder="' . $t['street'] . '" required />
                                                            </div>
                                                            <div style="display: flex; flex-direction: row; gap: 10px; justify-content: space-between;">
                                                                <div style="flex:1;"  class="gfield gfield--width-full">
                                                                    <label class="gfield_label gform-field-label">' . $t['house'] . '</label>
                                                                    <input type="text" id="house" placeholder="' . $t['house'] . '" required />
                                                                </div>
                                                                <div style="flex:1;"  class="gfield gfield--width-full">
                                                                    <label class="gfield_label gform-field-label">'. PWECommonFunctions::languageChecker('Numer lokalu', 'Premises number') .'</label>
                                                                    <input type="text" id="local" placeholder="'. PWECommonFunctions::languageChecker('Numer lokalu', 'Premises number') .'" required />
                                                                </div>
                                                            </div>
                                                            <div style="display: flex; flex-direction: row; gap: 10px; justify-content: space-between;">
                                                                <div style="flex:1;" class="gfield gfield--width-half">
                                                                    <label class="gfield_label gform-field-label">' . $t['post'] . '</label>
                                                                    <div style="display: flex; align-items: center;">
                                                                        <input type="text" id="post1" maxlength="2" placeholder="00" required />
                                                                        <span style="margin: 0 5px;">-</span>
                                                                        <input type="text" id="post2" maxlength="3" placeholder="000" required />
                                                                    </div>
                                                                </div>
                                                                <div style="flex:1;" class="gfield gfield--width-half">
                                                                    <label class="gfield_label gform-field-label">' . $t['city'] . '</label>
                                                                    <input type="text" id="city" placeholder="' . $t['city'] . '" required />
                                                                </div>
                                                            </div>
                                                            <div id="statusMessage" class="status-message"></div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="pwe-submitting-buttons display-befor-subbmit">
                                            <button id="pweSendStepTwo"  type="button" class="update-button btn pwe-btn pwe_reg_visitor"  onclick="updateGravityForm()">'. $confirmation_page_text_btn .'</button>
                                            </div>
                                            <div class="pwe-submitting-buttons display-after-subbmit">
                                                <a href="'.
                                                self::languageChecker(
                                                    <<<PL
                                                    /
                                                    PL,
                                                    <<<EN
                                                        /en/
                                                    EN
                                                )
                                            .'"><button class="btn pwe-btn pwe_reg_visitor">'. $main_page_text_btn .'</button></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="benefits">
                                    <h2>'. PWECommonFunctions::languageChecker('Pakiet PLATINIUM upoważnia do:', 'The PLATINIUM package<br/>entitles you to:') .'</h2>
                                    <div class="benefits_icon">
                                        <img src="/wp-content/plugins/pwe-media/media/platyna/fasttrack.webp" />
                                        <p>'. PWECommonFunctions::languageChecker('Wejście bezpłatne', 'Free entry') .'</br>FAST TRACK</p>
                                    </div>
                                    <div class="benefits_icon">
                                        <img src="/wp-content/plugins/pwe-media/media/platyna/obsluga.webp" />
                                        <p>'. PWECommonFunctions::languageChecker('Obsługę concierge"a', 'Concierge service') .'</p>
                                    </div>
                                    <div class="benefits_icon">
                                        <img src="/wp-content/plugins/pwe-media/media/platyna/vip.webp" />
                                        <p>'. PWECommonFunctions::languageChecker('Strefę VIP ROOM', 'VIP ROOM area') .'</p>
                                    </div>
                                    <div class="benefits_icon">
                                        <img src="/wp-content/plugins/pwe-media/media/platyna/aktywacja.webp" />
                                        <p>'. PWECommonFunctions::languageChecker('Możliwość wcześniejszej</br>aktywacji zaproszenia', 'Possibility of earlier</br> activation of the invitation') .'</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .limit-width:has(.platyna) {
                        max-width:none !important;
                        padding: 0px !important;
                    }
                    .wpb_column:has(.exhibitors-catalog) {
                        display:none !important;
                    }
                    .row-container:has(#pweRegistration) {
                        // background: linear-gradient(0deg, rgba(168, 168, 168, 0.58) 13%, rgba(150, 150, 150, 0.94) 23%, rgba(66, 66, 66, 0.02) 28%, rgba(170, 171, 175, 0.84) 100%);
                        background-image: url(/wp-content/plugins/pwe-media/media/platyna/platinum_background.webp);
                        background-size: contain;
                        background-repeat: no-repeat;
                    }
                    .pwe-registration-image-container img {
                        max-width:80%;
                    }
                    #pweForm {
                        width: 100%;
                        --p: 70px;
                        margin-left: auto;
                        max-width: 60%;
                        border-radius: 40px 0 0 40px;
                        overflow: hidden;
                        border: 1px solid #838B8F;
                        background: #838B8F;
                        background: linear-gradient(90deg, rgba(131, 139, 143, 1) 0%, rgba(224, 224, 224, 1) 50%, rgba(252, 252, 252, 1) 100%);
                    }
                    #pweRegistration .pweform_container {
                        display: flex;
                        max-width: 80%;
                        margin-left: auto;
                        padding: 18px 0;
                        margin-right: 15px;
                    }
                    #pweRegistration .form {
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                    }
                    #pweRegistration .form, .benefits {
                        flex:1;
                    }
                    .pwe-registration-column {
                        display: flex;
                        justify-content: right;
                        align-items: center;
                        min-height: 650px;
                    }
                    #pweRegistration .form-title, #pweRegistration .gform_confirmation_wrapper {
                        color:black;
                        font-weight:500;
                        text-align: center;
                        margin-top: 0px;
                        font-size: 30px;
                    }
                    .pwelement_'. self::$rnd_id .' form :is(input, textarea) {
                        margin-bottom:8px !important;
                    }
                    #pweRegistration .form h3, #pweRegistration .form label {
                        color:#737374;
                        font-weight:600 !important;
                    }
                    #pweRegistration form ul {
                        padding:0 !important;
                    }
                    #pweRegistration form ul input, input::placeholder {
                        border-radius:18px;
                        color: #8a8a8a !important;
                    }
                    #pweRegistration .ginput_container_consent label {
                        line-height: 1.4;
                        font-size: 12px;
                    }
                    #pweRegistration form input[type="submit"] {
                        display: inline-block;
                        width: 100%;
                        border-radius: 18px !important;
                        color:white !important;
                        background-color:#636363;
                    }
                    #pweRegistration .benefits_icon img {
                        width: 70px;
                    }
                    #pweRegistration .benefits {
                        gap: 10px;
                        text-align: center;
                        justify-content: space-around;
                    }
                    #pweRegistration .benefits, #pweRegistration .benefits .benefits_icon {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }
                    #pweRegistration .benefits .benefits_icon {
                        max-width: 250px;
                        justify-content: center;
                    }
                    #pweRegistration .benefits h2 {
                        color: #636363;
                        font-size: 18px;
                        font-weight: 700 !important;
                        margin-top: 60px;
                    }
                    #pweRegistration .benefits p {
                        color: #646464;
                        font-weight: 500;
                        line-height: 1.3;
                        margin-top: 0;
                        font-size: 14px;
                    }

                    @media(max-width:1500px){
                        .pweform_container {
                            padding: 0 0 5px 0;
                        }
                        .pwe-registration-column {
                            min-height: 580px;
                        }
                        .form h3, #pweRegistration .form-title {
                            margin-top: 14px;
                        }
                        #pweRegistration .benefits h2 {
                            margin-top: 60px;
                        }
                    }
                    @media(max-width:1200px){
                        .row-container:has(#pweRegistration) {
                            background-size: cover;
                        }
                        .pweform_container {
                            max-width: 90%;
                        }
                    }
                    @media(max-width:960px){
                        .row-container:has(.gform_wrapper, .pwe-container-grupy) .wpb_column, .row-container:has(.pwe-route) .wpb_column {
                            max-width: 100%;
                        }
                        #pweForm {
                            max-width: 100%;
                            border-radius: 40px;
                            margin: 15px 5px;
                        }
                    }
                    @media(max-width:650px){
                        .row-container:has(#pweRegistration) {
                            background: #838B8F;
                            background: linear-gradient(90deg, rgb(161 161 161) 0%, rgb(255 255 255) 50%, rgb(184 180 180) 100%);
                        }
                    }
                    @media(max-width:480px){
                        .pweform_container {
                            flex-direction: column;
                        }
                        #pweRegistration .benefits {
                            flex-wrap: wrap;
                            flex-direction: row;
                        }
                        #pweRegistration .benefits .benefits_icon {
                            flex: 1;
                        }
                        #pweRegistration .benefits h2 {
                            width: 100%;
                            margin-top: 20px;
                        }
                    }
                </style>
            ';
        }
            if(!$reg_form_update_entries){
                $output .= '
                <script>
                    jQuery(document).ready(function($){
                        $(".pwe_utm").find("input").val("'.$source_utm.'");
                        $(".pwelement_' . self::$rnd_id . ' .pwe_reg_visitor").on("click", function(event){
                            let userEmail = localStorage.getItem("user_email");
                            let userTel = localStorage.getItem("user_tel");
                            let userDirection = localStorage.getItem("user_direction");
                            let userCountry = localStorage.getItem("user_country");

                            if (userEmail) {
                                $(".pwelement_'. self::$rnd_id .' .ginput_container_email").find("input").val(userEmail);
                            }
                            if (userTel) {
                                $(".pwelement_'. self::$rnd_id .' .ginput_container_phone").find("input").val(userTel);
                            }
                            if (userDirection) {
                                $(".pwelement_'. self::$rnd_id .' input[placeholder=\"Kongres\"]").val(userDirection);
                            }
                            if (userCountry) {
                                $(".pwelement_'. self::$rnd_id .' .country input").val(userCountry);
                            }
                            $(".pwelement_'. self::$rnd_id .' .gfield--type-consent").find("input").click();
                            $(".pwelement_'. self::$rnd_id .' form").submit();
                        });
                    });
                </script>';
            } else {
                $output .= '
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD10_XMpLZxzQT_65E58g0yTq7GQBXUks4&libraries=places"></script>
                <script>

                    function initAutocomplete() {
                        const streetInput = document.getElementById("street");
                        const autocomplete = new google.maps.places.Autocomplete(streetInput, {
                            types: ["address"],
                            componentRestrictions: { country: "PL" }
                        });

                        autocomplete.addListener("place_changed", function () {
                            const place = autocomplete.getPlace();
                            if (!place.address_components) {
                                return;
                            }

                            let street = "";
                            let house = "";
                            let apartment = "";
                            let city = "";
                            let postCode = "";

                            place.address_components.forEach(component => {
                                if (component.types.includes("route")) {
                                    street = component.long_name;
                                } else if (component.types.includes("street_number")) {
                                    house = component.long_name;
                                } else if (component.types.includes("postal_code")) {
                                    postCode = component.long_name;
                                } else if (component.types.includes("locality")) {
                                    city = component.long_name;
                                } else if (component.types.includes("subpremise")) {
                                    apartment = component.long_name;
                                }
                            });

                            // fallback: spróbuj wykroić mieszkanie z adresu/tekstu
                            if (!apartment) {
                            const candidates = [
                                place.name || "",
                                place.formatted_address || "",
                                document.getElementById("street")?.value || ""
                            ].filter(Boolean);

                            apartment = extractApartmentFromText(candidates, house);
                            }

                            document.getElementById("street").value = street;
                            document.getElementById("house").value = house;
                            document.getElementById("local").value = apartment;
                            document.getElementById("city").value = city;
                            if (postCode.includes("-")) {
                                const [post1, post2] = postCode.split("-");
                                document.getElementById("post1").value = post1;
                                document.getElementById("post2").value = post2;
                            }
                        });
                    }

                    document.addEventListener("DOMContentLoaded", initAutocomplete);

                    document.addEventListener("DOMContentLoaded", function () {
                        const streetInput = document.getElementById("street");

                        // Nasłuchujemy zmiany atrybutów
                        const observer = new MutationObserver(mutations => {
                            mutations.forEach(mutation => {
                                if (mutation.attributeName === "disabled" && streetInput.hasAttribute("disabled")) {
                                    streetInput.removeAttribute("disabled");
                                }
                            });
                        });

                        // Uruchamiamy MutationObserver na `#street`, obserwujemy zmiany atrybutów
                        observer.observe(streetInput, { attributes: true });

                        // Dodatkowo: usunięcie `disabled`, jeśli użytkownik kliknie w pole
                        streetInput.addEventListener("focus", function () {
                            if (this.hasAttribute("disabled")) {
                                this.removeAttribute("disabled");
                            }
                        });
                    });

                    function updateGravityForm() {
                        const fields = ["name", "street", "house", "city", "post1", "post2"];
                        let hasError = false;
                        let firstErrorField = null;

                        document.getElementById("statusMessage").innerText = "";
                        document.getElementById("statusMessage").classList.remove("error");

                        fields.forEach(id => {
                            const field = document.getElementById(id);
                            if (!field.value.trim()) {
                                field.classList.add("error-border");
                                if (!firstErrorField) firstErrorField = field;
                                hasError = true;
                            } else {
                                field.classList.remove("error-border");
                            }
                        });

                        if (hasError) {
                            document.getElementById("statusMessage").innerText = "Wszystkie pola są wymagane!";
                            document.getElementById("statusMessage").classList.add("error");
                            firstErrorField.focus();
                            return;
                        }

                        const post1 = document.getElementById("post1");
                        const post2 = document.getElementById("post2");
                        const postCode = `${post1.value.trim()}-${post2.value.trim()}`;
                        const postPattern = /^\d{2}-\d{3}$/;

                        if (!postPattern.test(postCode)) {
                            post1.classList.add("error-border");
                            post2.classList.add("error-border");
                            document.getElementById("statusMessage").innerText = "Niepoprawny format kodu pocztowego (XX-XXX).";
                            document.getElementById("statusMessage").classList.add("error");
                            post1.focus();
                            return;
                        }

                        const formData = {
                            name: document.getElementById("name").value.trim(),
                            street: document.getElementById("street").value.trim(),
                            house: document.getElementById("house").value.trim(),
                            apartment: document.getElementById("local").value.trim(),
                            post: postCode,
                            city: document.getElementById("city").value.trim(),
                            formName: "'.$form_name.'",
                            direction: "registration"
                        };

                        fetch("'.$file_url.'", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Authorization": "qg58yn58q3yn5v"
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === "Dane zaktualizowane" || data.message === "Data has been updated!") {
                                document.getElementById("addressUpdateForm").classList.add("hidden");
                                const confirmationWrapper = document.createElement("div");
                                confirmationWrapper.classList.add("gform_confirmation_wrapper");
                                confirmationWrapper.style.textAlign = "center";
                                ';
                                if(strpos($source_utm, 'utm_source=platyna') === false){
                                    $output .= '
                                    confirmationWrapper.innerText = "'.$t['confirm_text'].'";
                                    document.getElementById("xForm").getElementsByClassName("form-3")[0].prepend(confirmationWrapper);';
                                } else {
                                    $output .= '
                                    confirmationWrapper.innerText = "'.$t['confirm_text_platyna'].'";

                                    document.getElementById("pweRegistration").getElementsByClassName("display-befor-subbmit")[0].style.display = "none";
                                    document.getElementById("pweRegistration").getElementsByClassName("form-title")[0].style.display = "none";
                                    document.getElementById("pweRegistration").getElementsByClassName("display-after-subbmit")[0].style.display = "block";
                                    document.getElementById("pweRegistration").getElementsByClassName("benefits")[0].style.display = "none";

                                    Object.assign(
                                        document.getElementById("pweRegistration").getElementsByClassName("pweform_container")[0].style,
                                        {
                                            margin: "0 auto",
                                            minHeight: "400px"
                                        }
                                    );

                                    document.getElementById("pweRegistration").getElementsByClassName("pwe-registration-form")[0].prepend(confirmationWrapper);';
                                }
                                $output .= '
                            } else {
                                document.getElementById("statusMessage").innerText = "Błąd: " + data.message;
                                document.getElementById("statusMessage").classList.add("error");
                            }
                        })
                        .catch(error => {
                            console.error("Błąd:", error);
                            document.getElementById("statusMessage").innerText = "Wystąpił problem z aktualizacją.";
                            document.getElementById("statusMessage").classList.add("error");
                        });
                    }
                    function extractApartmentFromText(candidates, house) {
                        let apartment = "";

                        for (let text of candidates) {
                            if (!text) continue;

                            let regex1 = new RegExp("\\b" + house + "\\s*/\\s*(\\w+)");
                            let match1 = text.match(regex1);
                            if (match1) {
                                return match1[1];
                            }

                            let match2 = text.match(/(?:m\.|lok\.)\s*(\w+)/i);
                            if (match2) {
                                return match2[1];
                            }
                        }

                        return apartment; // "" jeśli nic nie znalazło
                    }

                </script>';
            }

        if (strpos($source_utm, 'utm_source=byli') !== false) {

            $output .= '
            <style>
                .row-parent:has(.confirmation-vip) {
                    padding: 0;
                }
                .row-container:has(.confirmation-vip) {
                    display: flex;
                    width: 100%;
                    background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/gen-bg.jpg);
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: cover;
                }
                #xForm:has(.confirmation-vip) {
                    display: contents;
                }
                .pwelement_' .self::$rnd_id. ':has(.confirmation-vip) {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    padding: 36px;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: rgb(255 255 255 / 70%);
                    padding: 36px;
                    border-radius: 36px;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-wrapper {
                    width:100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-content-column {
                    width: 70%;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-column {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 30%;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-content-column h4 {
                    margin: 0;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-column-wrapper {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    max-width: 220px;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-column-wrapper h4 {
                    font-size: 20px;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-text {
                    border-right: 2px solid black;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logotypes {
                    width: 90%;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: nowrap;
                    padding: 20px 0;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logotypes-column {
                    width: 100%;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: nowrap;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logotypes img {
                    max-width: 25%;
                    height: auto;
                    flex-shrink: 1;
                    object-fit: contain;
                    transition: transform 0.2s ease-in-out;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-home-btn-container {
                    width: 100%;
                    margin-top: 36px;
                    text-align: center;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-home-btn {
                    background-color: white;
                    padding: 10px 18px;
                    border-radius: 10px;
                    text-transform: uppercase;
                    font-weight: 600;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-line {
                    display: none;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-container {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-container img {
                    position: relative;
                    z-index: 2;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-container p,
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-container h2 {
                    text-align: center;
                    font-weight: 700;
                    margin: 0;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-container p {
                    max-width: 260px !important;
                    width: 100%;
                    border-radius: 0;
                    color: '.self::$accent_color.' !important;
                    font-size: 20px;
                    margin-top: 9px;
                    padding: 0;
                    line-height: 1.2;
                    text-transform: uppercase;
                }
                .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-container h2 {
                    color: black !important;
                    position: relative;
                    z-index: 2;
                    font-size: 24px !important;
                    margin-top: 5px;
                }
                @media (min-width:1200px){
                    .pwelement_' . self::$rnd_id . ' #xForm:has(.confirmation-vip)>div {
                        min-height: auto !important;
                        width: 1200px !important;
                    }
                }
                @media (min-width:960px){
                    .pwelement_' . self::$rnd_id . ' #xForm:has(.confirmation-vip)>div {
                        min-height: auto !important;
                        width: 960px !important;
                    }
                }
                @media (max-width: 960px) {
                    .pwelement_' .self::$rnd_id. ' .vip-pack {
                        margin: 35px auto 0;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip h4 {
                        font-size: 18px;
                        text-align: center;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip :is(p, li) {
                        font-size: 14px;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-wrapper {
                        flex-direction: column;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip, .pwelement_' .self::$rnd_id. ' .confirmation-vip-content-column {
                        width: 100% !important;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-column {
                        width: 100%;
                        gap: 36px;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-home-btn-container {
                        min-height: auto !important;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-column-wrapper {
                        max-width: 200px;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-text {
                        border-right: none;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-line {
                        display: flex;
                        justify-content: center;
                        width: 100%;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-line hr {
                        width: 70%;
                        height: 2px;
                        background-color: black;
                        margin: 20px auto !important;
                        border: none;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-logo-container {
                        padding: 0 !important;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-logotypes {
                        width: 100%;
                        display: flex;
                        justify-content: space-between;
                        flex-direction: column;
                    }
                    .pwelement_' .self::$rnd_id. ' .confirmation-vip-logotypes-column {
                        width: 100%;
                    }
                }
                .fade-in {
                    opacity: 1;
                    min-height: auto;
                    transition: opacity 0.3s ease-in-out, max-height 0.3s ease-in-out;
                }
                .fade-out {
                    opacity: 0;
                    max-height: 0;
                    transition: opacity 0.3s ease-in-out, max-height 0.3s ease-in-out;
                }
            </style>';

            if (!is_numeric($trade_fair_edition_shortcode) || $trade_fair_edition_shortcode == 1) {
                $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .confirmation-vip-logo-container p {
                        font-size: 16px;
                    }
                </style>';
            }

            $output .= '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const content = `
                        <div class="confirmation-vip">
                            <div class="confirmation-vip-wrapper">
                                <div class="confirmation-vip-content-column confirmation-vip-column">
                                    <h4>'.
                                    self::languageChecker(
                                        <<<PL
                                        Dziękujemy za rejestrację na targi <span style="white-space: nowrap;">[trade_fair_name]</span>
                                        PL,
                                        <<<EN
                                        Thank you for registering for <span style="white-space: nowrap;">[trade_fair_name_eng]</span>
                                        EN
                                    )
                                    .'</h4>
                                    <div class="confirmation-vip-text">
                                        <p><strong>'.
                                            self::languageChecker(
                                                <<<PL
                                                Niebawem dotrze do Państwa przesyłka, w której znajdzie się:
                                                PL,
                                                <<<EN
                                                Check your email for:
                                                EN
                                            )
                                        .'</strong></p>
                                        <ul>'.
                                            self::languageChecker(
                                                <<<PL
                                                <li>Identyfikator VIP upoważniający do wejścia na teren targów i do dedykowanej strefy</li>
                                                <li>Zaproszenie do strefy VIP</li>
                                                <li>Kartę parkingową upoważniającą do korzystania z darmowego parkingu</li>
                                                <li>Szczegółowe informacje o targach i o wydarzeniach towarzyszących</li>
                                                PL,
                                                <<<EN
                                                <li>VIP badge authorizing entry to the fairgrounds and to the dedicated zone</li>
                                                <li>Invitation to the VIP zone</li>
                                                <li>Parking card authorizing use of free parking</li>
                                                EN
                                            )
                                        .'</ul>
                                    </div>'.
                                        self::languageChecker(
                                            <<<PL
                                                <div class="confirmation-vip-logotypes">
                                                    <div class="confirmation-vip-logotypes-column">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/inpost.png">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/dhl.png">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/ups.png">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/pocztex.png">
                                                    </div>
                                                    <div class="confirmation-vip-logotypes-column">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/fedex.png">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/poczta-polska.png">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/gls.png">
                                                        <img src="/wp-content/plugins/pwe-media/media/firmy-kurierskie/dpd.png">
                                                    </div>
                                                </div>
                                                PL,
                                                <<<EN

                                                EN
                                            )
                                        .'</div>

                                <div class="confirmation-vip-line">
                                    <hr>
                                </div>

                                <div class="confirmation-vip-logo-column confirmation-vip-column">
                                    <div class="confirmation-vip-column-wrapper">
                                        <div class="confirmation-vip-logo-container">
                                        '.
                                        self::languageChecker(
                                            <<<PL
                                            <img src="/doc/logo-color.webp">
                                            PL,
                                            <<<EN
                                            <img src="/doc/logo-color-en.webp">
                                            EN
                                        ).'
                                        <p class="confirmation-vip-logo-header-edition">
                                        '. $trade_fair_edition .'
                                        </p>
                                        <h2>'.$actually_date.'</h2>
                                        </div>
                                        <h4>Ptak Warsaw Expo</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="confirmation-vip-home-btn-container">'.
                        self::languageChecker(
                            <<<PL
                            <a href="/" class="confirmation-vip-home-btn">Strona główna</a>
                            PL,
                            <<<EN
                            <a href="/en/" class="confirmation-vip-home-btn">Home page</a>
                            EN
                        )
                        .'</div>`;

                    const targetNode = document.getElementById("xForm");

                    const observer = new MutationObserver(mutationsList => {
                        for (const mutation of mutationsList) {
                            if (mutation.type === "childList") {
                                for (const addedNode of mutation.addedNodes) {
                                    if (addedNode.nodeType === Node.ELEMENT_NODE && addedNode.classList.contains("gform_confirmation_wrapper")) {
                                        let xForm = document.getElementById("xForm");
                                        xForm.innerHTML = content;
                                        xForm.classList.add("has-confirmation");
                                    }
                                }
                            }
                        }
                    });

                    const config = { childList: true, subtree: true };

                    observer.observe(targetNode, config);

                    if (document.querySelector(".gform_confirmation_wrapper")) {
                        let xForm = document.getElementById("xForm");
                        xForm.innerHTML = content;
                    }
                });
            </script>';
        }

        return $output;
    }
}