<?php

/**
 * Class PWElementGenerator
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementGenerator extends PWElements {

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
                'param_name' => 'worker_form_id',
                'save_always' => true,
                'value' => array_merge(
                  array('Wybierz' => ''),
                  self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementGenerator',
                ),
            ),
            // array(
            //     'type' => 'dropdown',
            //     'group' => 'PWE Element',
            //     'heading' => __('Guest form (right)', 'pwelement'),
            //     'param_name' => 'guest_form_id',
            //     'save_always' => true,
            //     'value' => array_merge(
            //       array('Wybierz' => ''),
            //       self::$fair_forms,
            //     ),
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementGenerator',
            //     ),
            // ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Select form mode', 'pwelement'),
                'param_name' => 'generator_select',
                'save_always' => true,
                'value' => array(
                    'Generator gości wystawców' => 'exhibitor_quest',
                    'Generator pracowników wystawców' => 'exhibitor_worker',
                ),
                'std' => 'exhibitor_quest',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementGenerator',
                ),
            ),
            // array(
            //     'type' => 'checkbox',
            //     'group' => 'PWE Element',
            //     'heading' => __('Ticketed fairs', 'pwelement'),
            //     'param_name' => 'generator_tickets',
            //     'param_holder_class' => 'backend-basic-checkbox backend-area-one-fourth-width',
            //     'description' => __('Footer text for ticketed fairs'),
            //     'save_always' => true,
            //     'admin_label' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementGenerator',
            //     ),
            // ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Footer HTML Text', 'pwelement'),
                'param_name' => 'generator_html_text',
                'param_holder_class' => 'backend-textarea-raw-html',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementGenerator',
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'heading' => __('Personalizowanie Pod wystawce', 'pwelement'),
                'description' => __('Dodaj wystawce do grupy i sprawdź na stronie pod parametrem <br> ?wysatwca=...', 'pwelement'),
                'param_name' => 'company_edition',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementGenerator',
                ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('UNIKALNY! token  do adresu url ?wystawca=', 'pwelement'),
                        'param_name' => 'exhibitor_token',
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => '',
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Logo Wystawcy', 'pwelement'),
                        'param_name' => 'exhibitor_logo',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Nazwa Wystawcy', 'pwelement'),
                        'param_name' => 'exhibitor_name',
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => '',
                    ),
                ),
            ),
        );
        return $element_output;
    }

    private static function generateToken() {
        $domain = $_SERVER["HTTP_HOST"];
        $secret_key = '^GY0ZlZ!xzn1eM5';
        return hash_hmac('sha256', $domain, $secret_key);
    }

    /**
     * Static method to hide specjal input.
     * Returns form for GF filter.
     *
     * @param array @form object
     */
    public static function hide_field_by_label( $form, $com_name ) {
        $label_to_hide = ['FIRMA ZAPRASZAJĄCA', 'FIRMA', 'INVITING COMPANY', 'COMPANY'];

        foreach( $form['fields'] as &$field ) {
            if( in_array($field->label, $label_to_hide) ) {
                $field->cssClass .= ' gf_hidden';
                $field->defaultValue = $com_name;
            }
        }
        return $form;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {
        $current_url = $_SERVER['REQUEST_URI'];
        $output = '';

        extract( shortcode_atts( array(
            'worker_form_id' => '',
            'generator_html_text' => '',
            'generator_select' => '',
        ), $atts ));

        $company_array = array();

        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $current_domain = $_SERVER['HTTP_HOST'];
        $current_fair_group = null;

        foreach ($pwe_groups_data as $item) {
            if ($item->fair_domain === $current_domain) {
                $current_fair_group = $item->fair_group;
                break;
            }
        }

        $domain_gr_exhib = $current_fair_group;

        if ($domain_gr_exhib === 'gr3') {
            $email = 'biuro.podawcze3@warsawexpo.eu';
        } else {
            $email = 'info@warsawexpo.eu';
        }


        if(isset($_GET['wystawca'])){

            $company_edition = vc_param_group_parse_atts( $atts['company_edition'] );
            foreach ($company_edition as $company){
                if($_GET['wystawca'] == $company['exhibitor_token']){
                    $company_array = $company;
                    break;
                }
            }

            if (isset($company_array['exhibitor_name'])){
                add_filter( 'gform_pre_render', function( $form ) use ( $company_array ) {
                    return self::hide_field_by_label( $form, $company_array['exhibitor_name'] );
                });
                add_filter( 'gform_pre_validation', function( $form ) use ( $company_array ) {
                    return self::hide_field_by_label( $form, $company_array['exhibitor_name'] );
                });
                add_filter( 'gform_pre_submission_filter', function( $form ) use ( $company_array ) {
                    return self::hide_field_by_label( $form, $company_array['exhibitor_name'] );
                });
                add_filter( 'gform_admin_pre_render', function( $form ) use ( $company_array ) {
                    return self::hide_field_by_label( $form, $company_array['exhibitor_name'] );
                });
            }
        }

        $send_file = plugins_url('other/mass_vip.php', dirname(__FILE__));

        $worker_entries = GFAPI::get_entries($worker_form_id);
        // $guest_entries = GFAPI::get_entries($guest_form_id);
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $registration_count = 0;
        foreach ($worker_entries as $entry) {
            $entry_ip = rgar($entry, 'ip');
            if ($entry_ip === $ip_address) {
                $registration_count++;
            }
        }
        // foreach ($guest_entries as $entry) {
        //     $entry_ip = rgar($entry, 'ip');
        //     if ($entry_ip === $ip_address) {
        //         $registration_count++;
        //     }
        // }

        $generator_html_text_decoded = base64_decode($generator_html_text);
        $generator_html_text_decoded = urldecode($generator_html_text_decoded);
        $generator_html_text_content = wpb_js_remove_wpautop($generator_html_text_decoded, true);

        if(isset($_GET['token'])){
            $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
        }


        // if ($generator_tickets == true) {
        //     if (get_locale() == 'pl_PL') {
        //         $generator_html_text_content = (empty($generator_html_text_content)) ? 'Darmowa rejestracja upoważnia do wejścia w dniu <strong class="gen-date">[trade_fair_branzowy]</strong>.' : $generator_html_text_content;
        //     } else {
        //         $generator_html_text_content = (empty($generator_html_text_content)) ? 'Free registration etitle you to enter only on <strong class="gen-date">[trade_fair_branzowy_eng]</strong>.' : $generator_html_text_content;
        //     }
        // } else {
        //     if (get_locale() == 'pl_PL') {
        //         $generator_html_text_content = (empty($generator_html_text_content)) ? 'Ze względów organizacyjnych ilość zaproszeń jest ograniczona. Rejestracja dostępna tylko do 30 dni przed targami.' : $generator_html_text_content;
        //     } else {
        //         $generator_html_text_content = (empty($generator_html_text_content)) ? 'For organizational reasons, the number of invitations is limited. Registration is only available up to 30 days before the fair.' : $generator_html_text_content;
        //     }
        // }

        $output = '
        <style>
            #page-header{
                display: none !important;
            }
            .pwe-generator-wystawcow .gform_validation_errors {
                display: none;
            }
            .pwe-generator-wystawcow .gform_legacy_markup_wrapper li.gfield.gfield_error {
                border-top: none;
                border-bottom: none;
                padding-bottom: 0;
                padding-top: 0;
                margin-top: 12px !important;
            }
            .pwe-generator-wystawcow .heading-text {
                text-align: center;
                padding: 18px 0;
            }
            .pwe-generator-wystawcow .heading-text h3 {
                margin: 0 auto;
                color: #000000 !important;
            }
            .pwe-generator-wystawcow .heading-text a {
                text-decoration: underline;
                color: black !important;
            }
            // .pwe-generator-wystawcow {
            //     padding: 18px 0 36px;
            // }
            .pwe-generator-wystawcow h2 {
                font-size: 28px !important;
                width: auto !important;
                padding: 0 10px;
            }
            .pwe-generator-wystawcow .gform_legacy_markup_wrapper .gform_footer {
                padding: 0 !important;
            }
            .pwe-generator-wystawcow .gform_legacy_markup_wrapper .gform_footer input[type=submit] {
                width: auto !important;
            }
            .pwe-generator-wystawcow .gform_footer {
                display: flex;
                justify-content: center;
            }
            .pwe-generator-wystawcow input[type="text"], input[type="submit"], input[type="email"] {
                box-shadow: none !important;
            }
            .pwe-generator-wystawcow input[type="submit"] {
                border-radius: 5px;
                margin: 0 auto 15px;
                width: 150px;
            }
            .pwe-generator-wystawcow input[type="file"] {
                width: 83px !important;
                padding: 2px 0px 2px 2px !important;
            }
            .pwe-generator-wystawcow .gform-body input[type="text"],
            .pwe-generator-wystawcow .gform-body input[type="email"] {
                padding-left: 45px !important;
                background-color: #D2D2D2 !important;
                border-radius: 5px !important;
                border: none !important;
                font-weight: 700;
                color: #555555;
            }
            .pwe-generator-wystawcow .ginput_container {
                position: relative;
            }
            .pwe-generator-wystawcow .gfield img {
                position: absolute;
                left: 7px;
                top: 9px;
                height: 24px;
            }
            .pwe-generator-wystawcow .gfield_validation_message,
            .pwe-generator-wystawcow .gform_submission_error {
                font-size: 10px !important;
                padding: 2px 2px 2px 6px !important;
                margin-top: 2px !important;
            }
            .pwe-generator-wystawcow .gform_submission_error {
                margin: 0 auto;
            }
            .pwe-generator-wystawcow .gform_validation_errors {
                padding: 0 !important;
            }
            .pwe-generator-wystawcow .container-forms h2,
            .pwe-generator-wystawcow .container-forms button {
                font-weight: 800;
            }
            .pwe-generator-wystawcow .gform-body {
                padding: 18px 18px 0 18px;
                max-width: 550px;
                margin: 0 auto;
            }
            .pwe-generator-wystawcow .table {
                display: table;
                width: 100%;
                height: 100%;
            }
            .pwe-generator-wystawcow .table-cell {
                display: table-cell;
                vertical-align: middle;
                -moz-transition: all 0.5s;
                -o-transition: all 0.5s;
                -webkit-transition: all 0.5s;
                transition: all 0.5s;
            }
            .pwe-generator-wystawcow .container {
                border-radius: 25px;
                position: relative;
                max-width: 1200px;
                // margin: 30px auto 0;
                height: 780px;
                top: 50%;
                -moz-transition: all 0.5s;
                -o-transition: all 0.5s;
                -webkit-transition: all 0.5s;
                transition: all 0.5s;
            }
            .pwe-generator-wystawcow .container .container-forms {
                position: relative;
            }
            .pwe-generator-wystawcow .container .btn-exh {
                cursor: pointer;
                text-align: center;
                margin: 0 auto;
                border-radius: 15px;
                width: 164px;
                font-size: 24px;
                margin: 12px 0;
                padding: 5px 0;
                opacity: 1;
                -moz-transition: all 0.5s;
                -o-transition: all 0.5s;
                -webkit-transition: all 0.5s;
                transition: all 0.5s;
            }
            .pwe-generator-wystawcow .container .btn-exh:hover {
                opacity: 0.7;
            }
            .pwe-generator-wystawcow .container .container-forms .container-info {
                display: flex;
                justify-content: space-between;
                text-align: left;
                width: 100%;
                -moz-transition: all 0.5s;
                -o-transition: all 0.5s;
                -webkit-transition: all 0.5s;
                transition: all 0.5s;
            }
            .pwe-generator-wystawcow .container .container-forms .container-info .info-item {
                text-align: center;
                width: 678px;
                height: 780px;
                display: inline-block;
                vertical-align: top;
                color: #fff;
                opacity: 1;
                -moz-transition: all 0.3s;
                -o-transition: all 0.3s;
                -webkit-transition: all 0.3s;
                transition: all 0.3s;
                background: #EBEBEB;
            }
            .pwe-generator-wystawcow .info-item-left {
                border-radius: 0 0 0 20px;
            }
            .pwe-generator-wystawcow .info-item-left h2 {
                color: #b79663;
            }
            .pwe-generator-wystawcow .info-item-left .gform_footer input[type="submit"] {
                background-color: #b79663 !important;
            }
            .pwe-generator-wystawcow .info-item-right {
                position: absolute;
                right: 0;
                border-radius: 0 0 20px 0;
            }
            .pwe-generator-wystawcow .info-item-right .gform_footer input[type="submit"],
            .btn-gold {
                background: #b79663 !important;
            }
            .pwe-generator-wystawcow .info-item-right h2 {
                color: #b79663;
            }
            .pwe-generator-wystawcow .container-info .info-item button,
            .pwe-generator-wystawcow .gform_footer input[type="submit"],
            .btn-gold{
                text-transform: uppercase;
                margin: 0 !important;
                color: white !important;
                font-weight: 600;
                font-size: 16px !important;
                border-radius: 16px !important;
                padding: 15px 25px;
                width: 200px;
                box-shadow: none !important;
                cursor: pointer;
                border: none;
            }
            .pwe-generator-wystawcow .container-info .none {
                width: 0px !important;
                overflow: hidden;
            }
            .pwe-generator-wystawcow .form-item img {
                opacity: 0.5;
                position: absolute;
                width: 100%;
                z-index: 0;
                left: 10px;
                top: 25%;
            }
            .pwe-generator-wystawcow .form-item h2,
            .pwe-generator-wystawcow .form-item h3,
            .pwe-generator-wystawcow .form-item button {
                z-index: 0;
                position: relative;
            }
            .pwe-generator-wystawcow .form-item h3 {
                font-weight: 400;
                font-size: 18px;
            }
            .pwe-generator-wystawcow .form-item-element-left,
            .pwe-generator-wystawcow .form-item-element-right {
                background-position: center;
                background-size: cover;
                background-repeat: no-repeat;
            }
            .pwe-generator-wystawcow .form-item-element-left {
                background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/badgevip.jpg);
                border-radius: 20px 0 0 20px;
            }
            .pwe-generator-wystawcow .form-item-element-left-wyst {
               background-image: url( /wp-content/plugins/pwe-media/media/generator-wystawcow/badgevipmockup-wys.webp);
            }
            .pwe-generator-wystawcow .form-item-element-right {
                background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/gen-bg.jpg);
                border-radius: 0 20px 20px 0;
            }
            .pwe-generator-wystawcow .form-item-element-right h2 {
                color: black;
            }
            .pwe-generator-wystawcow .container .container-forms .container-info .info-item .btn-exh {
                background-color: transparent;
                border: 1px solid #fff;
            }
            .pwe-generator-wystawcow .container .container-forms .container-info .info-item .table-cell {
                padding-right: 0;
            }
            .pwe-generator-wystawcow .container .container-form {
                overflow: hidden;
                position: absolute;
                left: 0px;
                top: 0px;
                width: 450px;
                height: 780px;
                -moz-transition: all 0.5s;
                -o-transition: all 0.5s;
                -webkit-transition: all 0.5s;
                transition: all 0.5s;
            }
            .pwe-generator-wystawcow .container .form-item {
                padding: 25px;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                opacity: 1;
                -moz-transition: all 0.5s;
                -o-transition: all 0.5s;
                -webkit-transition: all 0.5s;
                transition: all 0.5s;
                color: white !important;
                text-align: center;
            }
            .pwe-generator-wystawcow .container .form-item.sign-up {
                position: absolute;
                opacity: 0;
            }
            .pwe-generator-wystawcow .container.log-in .container-form {
                left: 678px;
            }
            .pwe-generator-wystawcow .container.log-in .container-form .form-item.sign-up {
                left: 0;
                opacity: 1;
            }
            .pwe-generator-wystawcow .container.log-in .container-form .form-item.log-in {
                left: -100%;
            }
            .pwe-generator-wystawcow .forms-container-info__btn {
                margin-top: 50px !important;
                background-color: transparent;
                color: black;
                border: 2px solid black;
            }
            .pwe-generator-wystawcow .guest-info {
                width: 100% !important;
                color: #000000 !important;
            }
            .pwe-generator-wystawcow .guest-info h5 {
                width: auto !important;
                color: black !important;
            }
            .pwe-generator-wystawcow .custom-tech-support-text {
                padding-top: 36px !important;
            }
            .pwe-generator-wystawcow input{
                background-repeat: no-repeat;
                background-size: 30px;
                background-position: 5px;
            }
            .pwe-generator-wystawcow :is(
            input[placeholder="IMIĘ I NAZWISKO (PRACOWNIKA)"],
            input[placeholder="IMIĘ I NAZWISKO (GOŚCIA)"],
            input[placeholder="NAME AND SURNAME (GUEST)"],
            input[placeholder="NAME AND SURNAME (EMPLOYEE)"],
            input[placeholder="NAME AND SURNAME"]) {
                background-image: url("/wp-content/plugins/pwe-media/media/generator-wystawcow/name.png");
            }
            .pwe-generator-wystawcow input[placeholder="FIRMA ZAPRASZAJĄCA"],
            .pwe-generator-wystawcow input[placeholder="FIRMA"],
            .pwe-generator-wystawcow input[placeholder="INVITING COMPANY"],
            .pwe-generator-wystawcow input[placeholder="COMPANY"] {
                background-image: url("/wp-content/plugins/pwe-media/media/generator-wystawcow/box.png");
            }
            .pwe-generator-wystawcow input[placeholder="E-MAIL OSOBY ZAPRASZANEJ"],
            .pwe-generator-wystawcow input[placeholder="E-MAIL OF THE INVITED PERSON"],
            .pwe-generator-wystawcow input[placeholder="E-MAIL"] {
                background-image: url("/wp-content/plugins/pwe-media/media/generator-wystawcow/email.png");
            }
            .pwe-generator-wystawcow input:-webkit-autofill,
            .pwe-generator-wystawcow input:-webkit-autofill:hover,
            .pwe-generator-wystawcow input:-webkit-autofill:focus {
                -webkit-text-fill-color: #555555 !important;
                -webkit-box-shadow: 0 0 0px 40rem #D2D2D2 inset !important;
                transition: background-color 5000s ease-in-out 0s;
            }
            .pwe-generator-wystawcow .gform_confirmation_message {
                color: black !important;
            }
            .pwe-generator-wystawcow .container .gen-btn-img {
                display:none !important;
                position: absolute !important;
                top: 0 !important;
                right: 0 !important;
                height: 350px;
                width: 350px;
                padding: 0;
                margin: 0 !important;
                border-radius: 0;
                background-size: contain;
                background-repeat: no-repeat;
            }
            .pwe-generator-wystawcow .gen-btn-img .btn-exh {
                position: absolute;
                top: 60px;
                right: 20px;
                height: 45px;
                width: 140px !important;
                border: 0 !important;
            }
            .pwe-generator-wystawcow .gen-mobile {
                display: none;
            }
            .pwe-generator-wystawcow .guest-info-icons {
                display: flex;
                justify-content: center;
                gap: 5px;
                padding: 0 8px
            }
            .pwe-generator-wystawcow .guest-info-icon-block {
                max-width: 110px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
                padding-top: 36px;
            }
            .pwe-generator-wystawcow .guest-info-icon-block img {
                height: 50px;
                object-fit: contain;
            }
            .pwe-generator-wystawcow .guest-info-icon-block p,
            .pwe-generator-wystawcow .gen-text {
                padding: 0;
                margin: 0;
                font-size: 14px;
                font-weight: 500;
                line-height: inherit;
            }
            .pwe-generator-wystawcow .gen-text {
                padding: 18px;
                font-size: 16px;
            }
            .pwe-generator-wystawcow .gen-text p {
                margin: 0 !important;
            }
            .pwe-generator-wystawcow .gen-text .gen-date {
                white-space: nowrap;
            }
            .pwe-generator-wystawcow .gfield--type-radio {
                display: flex;
                background-color: #D2D2D2 !important;
                border-radius: 5px !important;
                border: none !important;
                font-weight: 700;
                color: #323232;
                padding: 0px 7px;
                height: 39px;
                align-items: center;
            }
            @media(min-width:960px){
                .pwe-generator-wystawcow .gfield--type-radio {
                    margin-right: 15px;
                }
            }
            .pwe-generator-wystawcow .gfield--type-radio  .gfield_radio {
                display: flex;
                margin: 0px !important;
                justify-content: space-around;
            }
            .pwe-generator-wystawcow .gfield--type-radio .gfield_label {
                font-size: 17px !important;
                font-weight: 700 !important;
                color: #323232;
                text-transform: uppercase;
                margin: 0px;
                margin-top: 2px;
            }
            .pwe-generator-wystawcow .gfield--type-radio .gchoice {
                margin: 0px 5px !important;
                display:flex  !important;
            }
            .pwe-generator-wystawcow .gfield--type-radio .gchoice input {
                margin-top: 2px !important;
                padding: 10px !important;
                cursor: pointer;
            }
            .pwe-generator-wystawcow .gfield--type-radio .gfield_label {
                position: relative;
                padding-left: 40px; /* odsunięcie tekstu w prawo, dopasuj w razie potrzeby */
            }

            .pwe-generator-wystawcow .gfield--type-radio .gfield_label::before {
                content: "";
                position: absolute;
                left: -1px;
                top: 35%;
                transform: translateY(-50%);
                width: 30px;
                height: 30px;
                background-image: url(/wp-content/uploads/2025/10/ico-hala.svg);
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
            }
            .pwe-generator-wystawcow .gfield--type-radio .gchoice label {
                margin-left: 0px !important;
                padding-left: 5px !important;
                margin-top: 3px !important;
            }
            @media(max-width:640px){
                .pwe-generator-wystawcow .gfield--type-radio {
                    height: 50px;
                }
            }
            /* Modal */
            @media (max-width: 961px){
                .tabela-masowa{
                    display:none;
                }
            }

            .modal__elements {
                z-index: 9999;
                background-color: #fff;
                padding: 1rem;
                box-shadow: 9px 9px 0px -5px black;
                border: 2px solid black;
                border-radius: 0;
                max-width: 80%;
                max-height: 80%;
                overflow: auto;
                height: auto;
                min-width: 900px;
                text-align: center;
            }

            .tabela-masowa{
                width:unset !important;
            }

            .modal__elements input{
                text-align: center;
                margin: 18px auto;
            }

            .modal__elements input[type="text"]{
                width:60%;
            }

            .modal__elements input[type="text"],
            .modal__elements select {
                border: 1px solid black;
                border-radius: 5px;
            }

            .modal__elements .file-selctor label {
                text-align: left;
            }

            .modal__elements table{
                text-align: center;
                width:90%;
            }

            .modal__elements p{
                text-align: center;
                margin: 0;
            }
            .modal__elements table th{
                width:50%;
            }
            .modal__elements table td{
                background-repeat: no-repeat;
                background-position: center;
                text-align: center;
                padding: 0;
            }
            .modal__elements .mass-send-name:empty{
                background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/generator-imiona.webp);
            }
            .modal__elements .mass-send-email:empty{
                background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/geneartor-emaile.webp);
            }
            .modal__elements tr table{
                text-align: center;
                width:100%;
                margin-top: 0;
            }
            .modal__elements table input{
                margin-top: 0 !imprtant;
            }
            .modal__elements .error-color{
                color: red;
            }
            .modal__elements .btn-close{
                background-color: #b79663;
                padding: 0px 13px 5px 14px;
                border-radius: 50%;
                font-weight: 700;
                color: white;
                font-size: 30px;
                position: absolute;
                right: 10px;
                cursor: pointer;
            }
            .modal__elements label{
                font-weight: 600;
                font-size: 18px;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .modal__elements .zastepczy{
                color: #ccc;
                font-style: italic;
            }
            .file-uloader{
                margin-top: 18px;
            }
            .file-selctor {
                display:flex;
                gap:18px;
                justify-content: center;
                align-items: center;
                margin-top: 9px;
            }
            .file-selctor :is(label, select){
                width: 35%;
                min-width: 100px;
                margin: 0;
            }
            .file-selctor select{
                padding-right: 30px;
                cursor: pointer;
                background: #fff url(/wp-content/plugins/pwe-media/media/arrow-down.png) no-repeat right 5px top 55%;
                background-size: 20px 20px;
            }
            .wyslij.btn-gold{
                margin:18px !important;
            }
            @media (max-width:1200px) {
                .pwe-generator-wystawcow .container {
                    max-width: 900px !important;
                }
                .pwe-generator-wystawcow .container .container-forms .container-info .info-item {
                    width: 600px;
                }
                .pwe-generator-wystawcow .container .container-form {
                    width: 300px;
                }
                .pwe-generator-wystawcow .container.log-in .container-form {
                    left: 600px;
                }
                .pwe-generator-wystawcow .container .container-forms .container-info .info-item .table-cell {
                    padding-right: 0px !important;
                }
                .pwe-generator-wystawcow .container .gen-btn-img {
                    height: 300px;
                    width: 300px;
                }
                .pwe-generator-wystawcow .forms-container-form__right h2 {
                    font-size: 26px;
                }
                .pwe-generator-wystawcow .forms-container-form__right h5 {
                    font-size: 14px;
                }
            }

            @media (max-width:960px) {
                .pwe-generator-wystawcow .container {
                    max-width: none !important;
                    margin: 15px auto 0;
                    min-height: 700px;
                    height: auto;
                }
                .pwe-generator-wystawcow .container:has(.wyst) {
                    max-width: none !important;
                    margin: 15px auto 0;
                    min-height: 520px;
                    height: auto;
                }
                .pwe-generator-wystawcow .container ul.gform_fields li.gfield {
                    padding-right: 0 !important;
                }
                .pwe-generator-wystawcow .container .container-form {
                    width: 100%;
                    height: 250px;
                }
                .pwe-generator-wystawcow .forms-container-form__right h2 {
                    display: none;
                }
                .pwe-generator-wystawcow .container .container-forms .container-info .info-item {
                    position: absolute;
                    top: 250px;
                    width: 100%;
                    height: auto;
                    border-radius: 0 0 18px 18px !important;
                }
                .pwe-generator-wystawcow .container .container-form {
                    width: 100%;
                    border-radius: 18px 18px 0 0 !important;
                }
                .pwe-generator-wystawcow .container.log-in .container-form {
                    left: 0px;
                }
                .row-container:has(.pwe-generator-wystawcow) .row-parent {
                    padding: 18px !important;
                }
                .pwe-generator-wystawcow .info-item-left,
                .pwe-generator-wystawcow .container,
                .pwe-generator-wystawcow .info-item-right,
                .pwe-generator-wystawcow .form-item-element-left,
                .pwe-generator-wystawcow .form-item-element-right {
                    border-radius: 0px !important;
                }
                .pwe-generator-wystawcow .forms-container-info__btn {
                    margin-top:10px !important;
                }
                .pwe-generator-wystawcow .container .container-forms .container-info .info-item:nth-child(2) .table-cell {
                    padding-left: 0px !important;
                    padding-right: 0;
                }
                .pwe-generator-wystawcow .gform-body {
                    padding: 4px 16px 0 16px;
                }
                .pwe-generator-wystawcow .gform_footer input[type=submit] {
                    font-size: 16px !important;
                }
                .pwe-generator-wystawcow .custom-tech-support-text {
                    margin: 36px 0 !important;
                }
                .pwe-generator-wystawcow .form-item-element-left {
                    background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/gen-bg.jpg);
                }
                .pwe-generator-wystawcow .guest-info {
                    width: 100% !important;
                }
                .pwe-generator-wystawcow .gen-mobile {
                    display: block;
                }
                .pwe-generator-wystawcow .container .gen-btn-img {
                    display: none;
                    height: 280px;
                    width: 280px;
                }
                .pwe-generator-wystawcow .gen-btn-img .btn-exh {
                    top: 75px;
                    right: 12px;
                    height: 40px;
                    width: 120px !important;
                }
                .pwe-generator-wystawcow .forms-container-info__btn.btn-exh {
                    display: none;
                }
            }
            @media (max-width:640px) {
                .pwe-generator-wystawcow h2 {
                    font-size: 22px !important;
                }
                .pwe-generator-wystawcow .gform_fields {
                    padding: 0 !important;
                }
                .pwe-generator-wystawcow .guest-info-icon-block {
                    padding-top: 18px;
                }
                .pwe-generator-wystawcow .guest-info-icon-block p,
                .pwe-generator-wystawcow .gen-text {
                    font-size: 12px;
                }
            }
            @media (max-width:400px) {
                .pwe-generator-wystawcow .ginput_container .gform-body input[type="text"],
                .pwe-generator-wystawcow .ginput_container .gform-body input[type="email"] {
                    font-size: 12px !important;
                }
                .pwe-generator-wystawcow .heading-text h3 {
                    font-size: 18px;
                }
                .pwe-generator-wystawcow .guest-info-icon-block {
                    max-width: 90px;
                }
            }
        </style>

        <div id="pweGeneratorWystawcow" class="pwe-generator-wystawcow">
                ';
                    if ($generator_select == "exhibitor_quest") {
                    $output .= '

                    <div class="container">
                        <div class="container-forms">
                        <div class="container-info">
                        <div class="info-item info-item-left none">
                                <div class="table">
                                    <div class="table-cell">
                                        <div class="forms-container-form__left active">
                                            <h2>'.
                                                self::languageChecker(
                                                    <<<PL
                                                        WYGENERUJ<br>IDENTYFIKATOR DLA<br>SIEBIE I OBSŁUGI STOISKA
                                                    PL,
                                                    <<<EN
                                                        GENERATE<br>AN ID FOR YOURSELF<br>AND YOUR COWORKERS
                                                    EN
                                                )
                                            .'</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="info-item info-item-right">
                            <div class="table">
                                <div class="table-cell">
                                        <div class="guest-info">
                                            <div class="forms-container-form__right">
                                                <h2>'.
                                                    self::languageChecker(
                                                        <<<PL
                                                            WYGENERUJ</br>IDENTYFIKATOR VIP</br>DLA SWOICH GOŚCI!
                                                        PL,
                                                        <<<EN
                                                            GENERATE</br>A VIP INVITATION</br>FOR YOUR GUESTS!
                                                        EN
                                                    )
                                                .'</h2>';
                                                    if(isset($company_array['exhibitor_logo'])){
                                                        $output .= '<img style="max-height: 120px;" src="' . wp_get_attachment_url($company_array['exhibitor_logo']) . '">';
                                                    }
                                    $output .= '<h5>'.
                                                    self::languageChecker(
                                                        <<<PL
                                                            Identyfikator VIP uprawnia do:
                                                        PL,
                                                        <<<EN
                                                            The VIP invitation entitles you to:
                                                        EN
                                                    )
                                                .'</h5>
                                                <div class="guest-info-icons">

                                                    <div class="guest-info-icon-block">
                                                        <img src="/wp-content/plugins/pwe-media/media/generator-wystawcow/ico1.png" alt="icon1">
                                                        <p>'.
                                                        self::languageChecker(
                                                            <<<PL
                                                                Bezpłatnego skorzystania ze strefy VIP ROOM
                                                            PL,
                                                            <<<EN
                                                                Free use of the VIP ROOM zone
                                                            EN
                                                        )
                                                        .'</p>
                                                    </div>

                                                    <div class="guest-info-icon-block">
                                                        <img src="/wp-content/plugins/pwe-media/media/generator-wystawcow/ico3.png" alt="icon3">
                                                        <p>'.
                                                        self::languageChecker(
                                                            <<<PL
                                                                Fast track
                                                            PL,
                                                            <<<EN
                                                                Fast track
                                                            EN
                                                        )
                                                        .'</p>
                                                    </div>

                                                    <div class="guest-info-icon-block">
                                                        <img src="/wp-content/plugins/pwe-media/media/generator-wystawcow/ico4.png" alt="icon4">
                                                        <p>'.
                                                        self::languageChecker(
                                                            <<<PL
                                                                Opieki concierge`a
                                                            PL,
                                                            <<<EN
                                                                Concierge care
                                                            EN
                                                        )
                                                        .'</p>
                                                    </div>

                                                    <div class="guest-info-icon-block">
                                                        <img src="/wp-content/plugins/pwe-media/media/generator-wystawcow/ico2.png" alt="icon2">
                                                        <p>'.
                                                        self::languageChecker(
                                                            <<<PL
                                                                Uczestnictwa<br>we wszystkich konferencjach branżowych
                                                            PL,
                                                            <<<EN
                                                                Participation<br>in all industry conferences
                                                            EN
                                                        )
                                                        .'</p>
                                                    </div>

                                                </div>

                                                [gravityform id="'. $worker_form_id .'" title="false" description="false" ajax="false"]';

                                                if(!isset($company_array['exhibitor_logo'])){
                                                    $output .= '<button class="btn tabela-masowa btn-gold">'.
                                                    self::languageChecker(
                                                        <<<PL
                                                        Wysyłka zbiorcza
                                                        PL,
                                                        <<<EN
                                                        Collective send
                                                        EN
                                                    )
                                                    .'</button>';
                                                }

                                                $output .= '
                                            <!-- <div class="gen-text">'. $generator_html_text_content .'</div> -->
                                            </div>
                                            <div class="gen-btn-img" style="background-image: url('.
                                                self::languageChecker(
                                                    <<<PL
                                                        /wp-content/plugins/pwe-media/media/generator-wystawcow/gen-pl.png
                                                    PL,
                                                    <<<EN
                                                        /wp-content/plugins/pwe-media/media/generator-wystawcow/gen-en.png
                                                    EN
                                                )
                                            .');">
                                                <div class="forms-container-info__btn btn-exh"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container-form">
                            <div class="form-item form-item-element-left log-in">
                                <div class="table">
                                    <div class="table-cell">
                                        <div class="gen-mobile">
                                            <h2>'.
                                            self::languageChecker(
                                                <<<PL
                                                    WYGENERUJ</br>IDENTYFIKATOR VIP</br>DLA SWOICH GOŚCI!
                                                PL,
                                                <<<EN
                                                    GENERATE</br>A VIP INVITATION</br>FOR YOUR GUESTS!
                                                EN
                                            )
                                            .'</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-item form-item-element-right sign-up">
                                <div class="table">
                                    <div class="table-cell">
                                        <h2>'.
                                            self::languageChecker(
                                                <<<PL
                                                    WYGENERUJ<br>IDENTYFIKATOR VIP<br>DLA SWOICH GOŚCI!
                                                PL,
                                                <<<EN
                                                    GENERATE<br>A VIP INVITATION<br>FOR YOUR GUESTS!
                                                EN
                                            )
                                        .'</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                    };

                    if ($generator_select == "exhibitor_worker") {
                    $output .= '
                         <div class="container">
                        <div class="container-forms wyst">
                        <div class="container-info">
                        <div class="info-item info-item-left none">
                                <div class="table">
                                    <div class="table-cell">
                                        <div class="forms-container-form__left active">
                                            <h2>'.
                                                self::languageChecker(
                                                    <<<PL
                                                        WYGENERUJ<br>IDENTYFIKATOR DLA<br>SIEBIE I OBSŁUGI STOISKA
                                                    PL,
                                                    <<<EN
                                                        GENERATE<br>AN ID FOR YOURSELF<br>AND YOUR COWORKERS
                                                    EN
                                                )
                                            .'</h2>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="info-item info-item-right">
                            <div class="table">
                                <div class="table-cell">
                                        <div class="guest-info">
                                            <div class="forms-container-form__right">
                                                <h2>'.
                                                    self::languageChecker(
                                                        <<<PL
                                                            WYGENERUJ</br>IDENTYFIKATOR DLA</br>SIEBIE I OBSŁUGI STOISKA!
                                                        PL,
                                                        <<<EN
                                                            GENERATE</br>AN ID FOR YOURSELF</br>AND YOUR COWORKERS!
                                                        EN
                                                    )
                                                .'</h2>

                                                [gravityform id="'. $worker_form_id .'" title="false" description="false" ajax="false"]';
                                        $output .= ' <!-- <div class="gen-text">'. $generator_html_text_content .'</div> -->
                                            </div>
                                            <div class="gen-btn-img" style="background-image: url('.
                                                self::languageChecker(
                                                    <<<PL
                                                        /wp-content/plugins/pwe-media/media/generator-wystawcow/gen-pl.png
                                                    PL,
                                                    <<<EN
                                                        /wp-content/plugins/pwe-media/media/generator-wystawcow/gen-en.png
                                                    EN
                                                )
                                            .');">
                                                <div class="forms-container-info__btn btn-exh"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container-form">
                            <div class="form-item form-item-element-left form-item-element-left-wyst log-in">
                                <div class="table">
                                    <div class="table-cell">
                                        <div class="gen-mobile">
                                            <h2>'.
                                            self::languageChecker(
                                                <<<PL
                                                    WYGENERUJ<br>IDENTYFIKATOR DLA<br>SIEBIE I OBSŁUGI STOISKA
                                                PL,
                                                <<<EN
                                                    GENERATE<br>AN ID FOR YOURSELF<br>AND YOUR COWORKERS
                                                EN
                                            )
                                            .'</h2>
                                            <button class="forms-container-info__btn btn-exh">'.
                                                self::languageChecker(
                                                    <<<PL
                                                        KLIKNIJ
                                                    PL,
                                                    <<<EN
                                                        CHANGE
                                                    EN
                                                )
                                            .'</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-item form-item-element-right sign-up">
                                <div class="table">
                                    <div class="table-cell">
                                        <h2>'.
                                            self::languageChecker(
                                                <<<PL
                                                    WYGENERUJ<br>IDENTYFIKATOR VIP<br>DLA SWOICH GOŚCI!
                                                PL,
                                                <<<EN
                                                    GENERATE<br>A VIP INVITATION<br>FOR YOUR GUESTS!
                                                EN
                                            )
                                        .'</h2>
                                        <button class="forms-container-info__btn btn-exh">'.
                                            self::languageChecker(
                                                <<<PL
                                                KLIKNIJ
                                                PL,
                                                <<<EN
                                                CHANGE
                                                EN
                                            )
                                        .'</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                    };
                    $output .= '</div>
                </div>
                <div style="text-align: center; display: flex; justify-content: center;" class="heading-text custom-tech-support-text">
                    <h3>'.
                        self::languageChecker(
                            <<<PL
                                Potrzebujesz pomocy?<br>
                                Skontaktuj się z nami - <a href="mailto:$email">$email</a>
                            PL,
                            <<<EN
                                Need help?<br>
                                Contact us - <a href="mailto:$email">$email</a>
                            EN
                        )
                    .'</h3>

                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    let fileLabel = [];
                    let fileContent = "";
                    let fileArray = [];
                    let filteredArray = [];
                    let $closeBtn = "";
                    let emailTrue = false;

                    $(".tabela-masowa").on("click",function(){
                        const tableCont = [];

                        $("footer").hide();

                        let modalBox = "";
                        const $modal = $("<div></div>")
                            .addClass("modal")
                            .attr("id", "my-modal");

                        modalBox = `<div class="modal__elements">
                                        <span class="btn-close">x</span>
                                        <p style="max-width:90%;">'.
                                            self::languageChecker(
                                                <<<PL
                                                Uzupełnij poniżej nazwę firmy zapraszającej oraz wgraj plik (csv, xls, xlsx) z danymi osób, które powinny otrzymać zaproszenia VIP GOLD. Przed wysyłką zweryfikuj zgodność danych.
                                                PL,
                                                <<<EN
                                                Fill in below the name of the inviting company and the details of the people who should receive VIP GOLD invitations. Verify the accuracy of the data before sending.
                                                EN
                                            )
                                        .'</p>
                                        <input type="text" class="company" placeholder="'.
                                            self::languageChecker(
                                                <<<PL
                                                Firma Zapraszająca (wpisz nazwę swojej firmy)
                                                PL,
                                                <<<EN
                                                Inviting Company (your company's name)
                                                EN
                                            )
                                        .'"></input>
                                        <div class="file-uloader">
                                            <label for="fileUpload">Wybierz plik z danymi</label>
                                            <input type="file" id="fileUpload" name="fileUpload" accept=".csv, .xls, .xlsx">
                                            <p class="under-label">Dozwolone rozszerzenia .csv, .xls, .xlsx</p>
                                        </div>
                                        <button class="wyslij btn-gold">'.
                                            self::languageChecker(
                                                <<<PL
                                                Wyślij
                                                PL,
                                                <<<EN
                                                Send
                                                EN
                                            )
                                        .'</button>
                                    </div>`;

                        $modal.html(modalBox);

                        $(".page-wrapper").prepend($modal);

                        $modal.css("display", "flex");
                        $closeBtn = $modal.find(".btn-close");

                        $closeBtn.on("click", function () {
                            $modal.hide();
                            $("footer").show();
                        });

                        $modal.on("click", function (event) {
                            if ($(event.target)[0] === $modal[0]) {
                                $modal.hide();
                                $("footer").show();
                            }
                        });

                        $(".company").on("click", function(){
                            if($(this).next().attr("class").match(/-error/)){
                                $(this).next().remove();
                            }
                        });

                        $(document).ready(function() {
                            $("#fileUpload").on("change", function(event) {
                                $(".file-selctor").remove();
                                const file = event.target.files[0];

                                if (!file) {
                                    alert("Nie wybrano pliku.");
                                    return;
                                }

                                const allowedExtensions = ["csv", "xls", "xlsx"];
                                const fileExtension = file.name.split(".").pop().toLowerCase();

                                if (!allowedExtensions.includes(fileExtension)) {
                                    alert("Niewłaściwy typ pliku. Proszę wybrać plik CSV, XLS lub XLSX.");
                                    return;
                                }

                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    fileContent = e.target.result;

                                    if(file.name.split(".").pop().toLowerCase() != "csv"){
                                        const data = new Uint8Array(e.target.result);
                                        const workbook = XLSX.read(data, { type: "array" });
                                        const firstSheetName = workbook.SheetNames[0];
                                        const worksheet = workbook.Sheets[firstSheetName];
                                        fileContent = XLSX.utils.sheet_to_csv(worksheet);
                                    } else {
                                        fileContent = e.target.result;
                                    }
                                    fileContent = fileContent.replace(/\r/g, "");

                                    fileArray = fileContent.split("\n");

                                    filteredArray = [];

                                    fileArray.forEach(function(element){
                                        if (element.trim() !== "" && !/^[,\s"]+$/.test(element)){
                                            let newElement = element.split(/,(?=(?:[^"]|"[^"]*")*$)/);

                                            newElement = newElement.map(function(elem){
                                                elem = elem.replace(/\\\\/g, ``);
                                                elem = elem.replace(/\\"/g, ``);
                                                return elem;
                                            });

                                            filteredArray.push(newElement);
                                        }
                                    });

                                    fileLabel = filteredArray[0];

                                    $(".file-uloader").after(`<div class="file-selctor"><label>Kolumna z adresami e-mail</label><select type="select" id="email-column" name="email-column" class="selectoret vars-to-insert"></select></div>`);
                                    $(".file-uloader").after(`<div class="file-selctor"><label>Kolumna z imionami i nazwiskami</label><select type="select" id="name-column" name="name-column" class="selectoret vars-to-insert"></select></div>`);

                                    $(".selectoret").each(function(){
                                        $(this).append(`<option value="">Wybierz</option>`);
                                    });

                                    fileLabel.forEach(function(element) {
                                        $(".selectoret").each(function(){
                                            if(element != ""){
                                                $(this).append(`<option value="${element}">${element}</option>`);
                                            }
                                        })
                                    });

                                    $(".vars-to-insert").on("change", function(){
                                        if($(this).parent().next().attr("class").match(/-error/) == "-error"){
                                            $(this).parent().next().remove();
                                        }
                                    });

                                    $("#email-column").on("change", function(){
                                        const chosenLabel = $(this).val();
                                        const chosenID = fileLabel.findIndex(label => label == chosenLabel );
                                        let chosenErrors = -1;
                                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                                        for (const row of filteredArray){
                                            const rowArray = row;

                                            if (chosenErrors > 5){
                                                $(".file-selctor").has("#email-column").after(`<p class="email-error error-color" >'.
                                                    self::languageChecker(
                                                        <<<PL
                                                        W wybranej kolumnie znajduje się 5 lub więcej błędnych maili proszę o poprawienie przed kontynuacją.
                                                        PL,
                                                        <<<EN
                                                        Select a column with emails
                                                        EN
                                                    )
                                                .'</p>`);
                                                emailTrue = false;
                                                break ;
                                            } else if (rowArray[chosenID].length < 5 || !emailPattern.test(rowArray[chosenID])){
                                                chosenErrors++;
                                            } else {
                                                emailTrue = true;
                                            }
                                        };
                                    });

                                    $("#fileContent").text(fileContent);
                                };

                                if (fileExtension === "csv") {
                                    reader.readAsText(file);
                                } else {
                                    reader.readAsArrayBuffer(file);
                                }
                            });
                        });

                        $(".wyslij").on("click",function(){
                            if(!emailTrue){
                                return;
                            }
                            let pageLang = "' .get_locale(). '" == "pl_PL" ? "pl" : "en";
                            let company_name = "";
                            let emailColumn = "";
                            let nameColumn = "";

                            if ($(".company").val() != ""){
                                company_name = $(".company").val();
                            } else {
                                if ($(".company-error").length == 0){
                                    $(".company").after(`<p class="company-error error-color" >'.
                                        self::languageChecker(
                                            <<<PL
                                            Nazwa firmy jest wymagana
                                            PL,
                                            <<<EN
                                            Company Name is required
                                            EN
                                        )
                                    .'</p>`);
                                }
                            }

                            if ($("#email-column").val() != ""){
                                emailColumn = $("#email-column").val();
                            } else {
                                if ($(".email-error").length == 0){
                                    $(".file-selctor").has("#email-column").after(`<p class="email-error error-color" >'.
                                        self::languageChecker(
                                            <<<PL
                                            Wybierz kolumne z mailami
                                            PL,
                                            <<<EN
                                            Select a column with emails
                                            EN
                                        )
                                    .'</p>`);
                                }
                            }
                            if ($("#name-column").val() != ""){
                                nameColumn = $("#name-column").val();

                            } else {
                                if ($(".name-error").length == 0){
                                    $(".file-selctor").has("#name-column").after(`<p class="name-error error-color" >'.
                                        self::languageChecker(
                                            <<<PL
                                            Wybierz kolumne z danymi
                                            PL,
                                            <<<EN
                                            Select a column with Names
                                            EN
                                        )
                                    .'</p>`);
                                }
                            }

                            if(company_name == "" || emailColumn == "" || nameColumn == ""){
                                return;
                            }

                            const namelIndex = fileLabel.indexOf(nameColumn);
                            const emailIndex = fileLabel.indexOf(emailColumn);
                            let emailErrors = 0;

                            const tableCont = filteredArray.reduce((acc, row) => {
                                const rowArray = row;
                                if (rowArray[emailIndex] && rowArray[emailIndex].length > 5 && emailErrors < 5) {
                                    acc.push({ "name": rowArray[namelIndex], "email": rowArray[emailIndex] });
                                } else if (emailErrors < 5) {
                                    emailErrors++;
                                } else {

                                }
                                return acc;
                            }, []);

                            if (tableCont.length > 0 && tableCont.length < 5000 && emailErrors < 5){
                                $(".modal__elements").html("<span class=btn-close>x</span>");
                                $(".modal__elements").append("<div id=spinner class=spinner></div>");
                                $closeBtn = $modal.find(".btn-close");

                                $.post("' . $send_file . '", {
                                    token: "' . self::generateToken() .'",
                                    lang: pageLang,
                                    company: company_name,
                                    data: tableCont,
                                }, function(response) {

                                    resdata = JSON.parse(response);

                                    if (resdata == 1){
                                        $(".modal__elements").append(`<p style="color:green; font-weight: 600; width: 90%;">Dziękujemy za skorzystanie z generatora zaproszeń. Państwa goście wkrótce otrzymają zaproszenia VIP.</p>`);
                                    } else {
                                        $(".modal__elements").append(`<p style="color:red; font-weight: 600; width: 90%;">Przepraszamy, wystąpił problem techniczny. Spróbuj ponownie później lub zgłoś problem mailowo</p>`);
                                    }

                                    $("#spinner").remove();
                                    tableCont.splice(0, tableCont.length);
                                    $("#dataContainer").empty();
                                });
                            } else {
                                let errorMessage = "";
                                if(tableCont.length > 5000){
                                    errorMessage += "Za dużo znalezionych elementów, maksymalna ilość to 5000 lub źle odczytany plik";
                                }

                                $(".wyslij").before(`<p class="company-error error-color" style="font-weight:700;">'.
                                    self::languageChecker(
                                        <<<PL
                                        Przepraszamy, wystąpił problem techniczny. Spróbuj ponownie później lub zgłoś problem mailowo
                                        Error ->
                                        PL,
                                        <<<EN
                                        Sorry, there was a technical problem. Please try again later or report the problem by email
                                        Error ->
                                        EN
                                    )
                                .' ${errorMessage}</p>`);
                            }
                        });
                    });
                });

                var btnExhElements = document.querySelectorAll(".btn-exh");
                btnExhElements.forEach(function(btnExhElement) {
                    btnExhElement.addEventListener("click", function() {
                        var containerElements = document.querySelectorAll(".container");
                        var infoItemElements = document.querySelectorAll(".info-item");

                        containerElements.forEach(function(containerElement) {
                            containerElement.classList.toggle("log-in");
                        });

                        infoItemElements.forEach(function(infoItemElement) {
                            infoItemElement.classList.toggle("none");
                        });
                    });
                })

            </script>';

            $output .= "
            <script>
                var registrationCount = '" . $registration_count . "';
                if (document.querySelector('html').lang === 'pl-PL') {
                    const companyNameInput = document.querySelector('.wyst .forms-container-form__right input[placeholder=\'FIRMA ZAPRASZAJĄCA\']');
                    const companyEmailInput = document.querySelector('.wyst .forms-container-form__right input[placeholder=\'E-MAIL OSOBY ZAPRASZANEJ\']');
                    if (companyNameInput && companyEmailInput) {
                        companyNameInput.placeholder = 'FIRMA';
                        companyEmailInput.placeholder = 'E-MAIL';
                    }
                } else {
                    const companyNameInputEn = document.querySelector('.wyst  .forms-container-form__right input[placeholder=\'INVITING COMPANY\']');
                    const companyEmailInputEn = document.querySelector('.wyst .forms-container-form__right input[placeholder=\'E-MAIL OF THE INVITED PERSON\']');
                    if (companyNameInputEn && companyEmailInputEn) {
                        companyNameInputEn.placeholder = 'COMPANY';
                        companyEmailInputEn.placeholder = 'E-MAIL';
                    }
                }
            </script>";

        return $output;

    }
}