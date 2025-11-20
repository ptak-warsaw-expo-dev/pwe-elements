<?php

/**
 * Class PWElementXForm
 * Extends PWElements class and defines a pwe Visual Composer element for x-steps-form.
 */
class PWElementXForm extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }    
    
    /**
     * Adding Scripts
     */
    public function addingScripts(){

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
                'heading' => __('Form placement', 'pwelement'),
                'param_name' => 'x_form_placemant',
                'save_always' => true,
                'value' => array(
                    'Registration' => 'register',
                    'Step 2' => 'step2',
                    'Confirmation' => 'confirmation',
                    'Conferance' => 'conferance'
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementXForm',
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Registration form', 'pwelement'),
                'param_name' => 'reg_form_name',
                'save_always' => true,
                'value' => array_merge(
                  array('Wybierz' => ''),
                  self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementXForm',
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Exhibitors form', 'pwelement'),
                'param_name' => 'exh_form_name',
                'save_always' => true,
                'value' => array_merge(
                  array('Wybierz' => ''),
                  self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => array(
                        'confirmation',
                    ),
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Conference registration form', 'pwelement'),
                'param_name' => 'conf_form_name',
                'save_always' => true,
                'value' => array_merge(
                  array('Wybierz' => ''),
                  self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => array(
                        'confirmation',
                    ),
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Krok2', 'pwelement'),
                'param_name' => 'step2',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => array(
                        'register',
                        'conferance',
                    )
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('next step url', 'pwelement'),
                'param_name' => 'confirmation_url',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => array(
                        'step2',
                    )
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Confereces', 'pwelement'),
                'param_name' => 'conferences',
                'description' => __('Add all conference names separated by ";" .', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => 'conferance',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Go back url', 'pwelement'),
                'param_name' => 'go_back_url',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => array(
                        'step2','confirmation',
                    ),
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Fair logo', 'pwelement'),
                'param_name' => 'fair_logo',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => 'step2',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Additional Color', 'pwelement'),
                'param_name' => 'additional_color',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => array(
                        'step2','confirmation',
                    ),
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Editor mode', 'pwelement'),
                'param_name' => 'editor_mode',
                'save_always' => true,
                'value' => array(
                    'No Edition' => '',
                    'Step 2' => 'step2',
                    'confirmation_visitor' => 'confirmation_visitor',
                    'confirmation_exhibitor' => 'confirmation_exhibitor',
                ),
                'dependency' => array(
                    'element' => 'x_form_placemant',
                    'value' => array(
                        'confirmation',
                        'step2',
                    ),
                ),
            ),
            // array(
            //     'type' => 'textfield',
            //     'group' => 'PWE Element',
            //     'heading' => __('Osoba odpowiedzialna za wystawców', 'pwelement'),
            //     'param_name' => 'exhibitor_email',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'x_form_placemant',
            //         'value' => array(
            //             'step2',
            //             'confirmation',
            //         ),
            //     ),
            // ),
        );
        return $element_output;
    }

    /**
     * Sprawdza poprawność adresu e-mail.
     *
     * @param string $email Adres e-mail do sprawdzenia.
     * @return bool Zwraca true, jeśli adres e-mail jest poprawny, w przeciwnym razie false.
     */
    public static function redirectTo($url) {
        if (!current_user_can('administrator')) {
            if (!headers_sent()) {
                header('Location: ' . $url);
                exit();
            } else {
                echo "<script type='text/javascript'>";
                echo "window.location.href='$url';";
                echo "</script>";
                echo "<noscript>";
                echo "<meta http-equiv='refresh' content='0;url=$url' />";
                echo "</noscript>";
                exit();
            }
        }
    }
    
    /**
     * Static method to generate the HTML for Email.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     */
    public static function x_form_register($reg_form_id) {
        if(!GFAPI::form_id_exists($reg_form_id)){
            echo '<script>console.log("coś nie tak bo nie ma formularza")</script>';
            return;
        }

        $form_checker = GFAPI::get_form($reg_form_id);
        foreach($form_checker['fields'] as $field){
            if (strpos(strtolower($field->label), 'email') !== false){
                $email_id = $field->id;
            } else if (strpos(strtolower($field->label),'telefon') !== false || strpos(strtolower($field->label), 'phone') !== false){
                $phone_id = $field->id;
            } else if (strpos(strtolower($field->label),'konferencje') !== false){
                $conf_id = $field->id;
            }
        }

        $conferances = '';

        foreach($_POST as $id => $key){
            if (strpos(strtolower($id), 'konf-') === 0){
                $conferances .= substr($id, 5) . ' ';
            }
        }

        $inside_id_data = array(
            'form_id' => $reg_form_id,
            $email_id => sanitize_text_field($_POST['email']),
            $phone_id => $_POST['phone'],
            $conf_id => $conferances,
        );

        $entry_id = GFAPI::add_entry($inside_id_data);
        
        $notification_form_id = GFAPI::get_form($reg_form_id);
        $notification_entry = GFAPI::get_entry($entry_id);

        $notification = GFAPI::send_notifications($notification_form_id, $notification_entry);

        return $entry_id;
    }

    /**
     * Static method to generate the HTML for Email.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     */
    public static function add_info_email($qr_code_url, $post_data) {
        if ($_SESSION['updated']){
            return;
        }
        $email_entry_id = '';
        $email_data = '';

        $entry_data = GFAPI::get_entry($post_data['entry_id']);
        $form_data = GFAPI::get_form($entry_data['form_id']);

        foreach($entry_data as $id => $key){
            if (is_numeric($id) && (int)$id == $id){
                foreach($form_data["fields"] as $field){
                    if(strpos(strtolower($field['label']), 'email') != false && $email_entry_id == ''){
                        $email_entry_id = $field['id'];
                    }
                    if($field['id'] == $id && strtolower($field['label']) != 'captcha'){
                        $email_data .= '<p>' .$field['label']. ' - ' . $key . '</p>';
                        break;
                    }
                }
            }
        }

        $receiver = 'lidyst@warsawexpo.eu';
        $email_title = $entry_data[$email_entry_id] . ' - dodatkowe informacje';
        $email_output = '<div>' . do_shortcode("[trade_fair_name]") .'<p>' . $email_data . '</div>';

        $sent = wp_mail(
            $receiver,
            $email_title,
            $email_output,
            array('Content-Type: text/html; charset=UTF-8')
        );
    }

    /**
     * Static method to send request to become exhibitor.
     * This method registering crone action. 
     * 
     * @param string @entry_id entry id to send
     */
    public static function exhibitor_registering($entry_id, $exhibitor, $update = ''){
        
        $entry = GFAPI::get_entry($entry_id);

        $email_output = '';
        foreach($entry as $id => $key){
            if(is_numeric($id) && $key != '' && strpos($key, '/wp-content/uploads/') !== false){
                $email_output .= '<p>' . $key . '</p>';
            }
        }
        
        $email_title = ($update == '') ? do_shortcode("[trade_fair_name]") . ' - osoba zgłosiła chęć na zostanie wystawcą ' : $entry['1'] . ' - dodatkowe informacje wystawcy' ;

        $sent = wp_mail(
            $exhibitor,
            $email_title,
            $email_output,
            array('Content-Type: text/html; charset=UTF-8', do_shortcode("[trade_fair_rejestracja]"))
        );

    }

    /**
     * Static method to send request to become exhibitor.
     * This method registering crone action. 
     * 
     * @param string @entry_id entry id to send
     */
    public static function add_side_entry($reg_form_id, $post_data){        
        if(!GFAPI::form_id_exists($reg_form_id)){
            echo '<script>console.log("coś nie tak bo nie ma formularza")</script>';
            return false;
        } 
        if($_SESSION['updated']){
            return true;
        }
        
        $form_checker = GFAPI::get_form($reg_form_id);
        foreach($form_checker['fields'] as $field){      
                 
            if (strpos(strtolower($field->label), 'nazwisko') !== false || strpos(strtolower($field->label), 'name') !== false){
                $name_id = $field->id;
            } else if (strpos(strtolower($field->label),'telefon') !== false || strpos(strtolower($field->label),'phone') !== false){
                $phone_id = $field->id;
            } else if (strpos(strtolower($field->label),'nazwa') !== false || strpos(strtolower($field->label),'company') !== false){
                $company_id = $field->id;
            } else if (strpos(strtolower($field->label),'nip') !== false || strpos(strtolower($field->label),'tax') !== false){
                $nip_id = $field->id;
            }  else if (strpos(strtolower($field->label),'more') !== false ){
                $adds_id = $field->id;
            }  else if (strpos(strtolower($field->label),'wysylki') !== false ){
                $adres_id = $field->id;
            }
        }   
        
        if($name_id != '' && isset($post_data["imie"])){ GFAPI::update_entry_field($post_data["entry_id"], $name_id, ($post_data["imie"] . ' ' . $post_data["nazwisko"])); }
        if($phone_id != '' && isset($post_data["phone"])){ GFAPI::update_entry_field($post_data["entry_id"], $phone_id, ($post_data["phone"])); }
        if($company_id != '' && isset($post_data["company-name"])){ GFAPI::update_entry_field($post_data["entry_id"], $company_id, $post_data["company-name"]); }
        if($nip_id != '' && isset($post_data["company-nip"])){ GFAPI::update_entry_field($post_data["entry_id"], $nip_id, $post_data["company-nip"]); }
        if($adds_id != '' && isset($post_data["company-adds"])){ GFAPI::update_entry_field($post_data["entry_id"], $adds_id, $post_data["company-adds"]); }

        if($adres_id != '' && isset($post_data["ulica"])){ GFAPI::update_entry_field($post_data["entry_id"], $adres_id, 
            ($post_data["ulica"] . ' ' . $post_data["budynek"] . ' ' . $post_data["mieszkanie"] . ' ' . $post_data["kod_pocztowy"] . ' ' . $post_data["miasto"])); }

        
        return true;
    }

    /**
     * Static method to send request to become exhibitor.
     * This method registering crone action. 
     * 
     * @param string @entry_id entry id to send
     */
    public static function recaptcha_check(){        
        $secret = get_option('rg_gforms_captcha_private_key');
        $token = $_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secret,
            'response' => $token
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $result = json_decode($response, true);
        
        return $result['success'];
    }

    /**
     * Static method to display registration form (form1).
     * Returns the HTML output as a string.
     */
    public static function registrationHtml($reg_form_id, $step2_url, $conferences = ''){
        $captcha_public_key = get_option('rg_gforms_captcha_public_key');
        $reg_output = '
            <script src="https://www.google.com/recaptcha/api.js?render=' . $captcha_public_key . '"></script>
            <style>
                .pwelement_' . self::$rnd_id . ' .iti--allow-dropdown{
                    margin: 9px 0;
                    width: 100%;
                }
                .pwelement_' . self::$rnd_id . ' .form-1-top {
                    min-height: 465px;
                    padding: 18px 36px 36px;
                    background: #e8e8e8;
                    border: 2px solid #564949;
                }
                .pwelement_' . self::$rnd_id . ' h2 {
                    padding:0 9px 9px 0;
                    box-shadow: 9px 9px 0px -6px ' . self::$accent_color . ';
                }
                .pwelement_' . self::$rnd_id . ' input{
                    border: 2px solid #564949 !important;
                    border-radius: 10px;
                }
                .pwelement_' . self::$rnd_id . ' input:not([type=checkbox]) {
                    margin-top: 18px;
                    width: 90%;
                    box-shadow: unset;
                }
                .pwelement_' . self::$rnd_id . ' .consent-container{
                    margin-top: 36px;
                    display:flex;
                    gap: 10px;
                }
                .pwelement_' . self::$rnd_id . ' .consent-text,
                .pwelement_' . self::$rnd_id . ' .gfield_consent_description {
                    font-size:12px;
                    line-height: 15px;
                    cursor: pointer;
                }
                .pwelement_' . self::$rnd_id . ' .gfield_consent_description{
                    display: none;
                }
                .pwelement_' . self::$rnd_id . ' button[type=submit] {
                    background-color: #A6CE39 !important;
                    border-width: 1px;
                    border-radius: 10px;
                    border: 2px solid #564949;
                    font-size: 1em;
                    margin-top: 36px;
                }
                .mail-error, .tel-error, .cons-error{
                    margin:0 11px;
                    width:85%;
    
                }
                .pwelement_' . self::$rnd_id . ' .show-consent {
                    color: black !important; 
                }

                .pwelement_' . self::$rnd_id . ' form :is(.email-error, .phone-error, .cons-error) {
                    font-size: 12px;
                    color: red;
                    width: 90%;
                    margin-top: 0px;
                    text-transform: uppercase;
                    background-color: rgba(255, 223, 224);
                }

                @media (min-width: 900px){
                    .row-container:has(.img-container-top10) .img-container-top10 div {
                        min-height: 55px;
                        margin: 0 10px !important;
                    }
                }
                @media (min-width:570px) and (max-width:959px){
                    .pwelement_' . self::$rnd_id . ' .form-1-top {
                        padding: 18px 9px;
                        text-align: -webkit-center;
                    }
                    .pwelement_' . self::$rnd_id . ' button[type=submit] {
                        padding: 9px;
                        font-size: 2vw;
                    }
                    .pwelement_' . self::$rnd_id . ' consent-container {

                    }
                }
                @media (max-width:570px){
                    .pwelement_' . self::$rnd_id . ' h2 {
                        font-size: 5vw !important;
                    }
                    .pwelement_' . self::$rnd_id . ' button[type=submit] {
                        padding: 9px;
                        font-size: 3.2vw;
                    }
                    .pwelement_' . self::$rnd_id . ' :is(button, input:not([type=checkbox])) {
                        width: 100%;
                    }
                }
                
            </style>
            <div id="xForm">
                <div class="form-1-top pwe-registration">
                    <div class="form-1">
                        <h2 class="h4 text-color-jevc-color text-uppercase">'. 
                            self::languageChecker(
                                <<<PL
                                Dla odwiedzających
                                PL,
                                <<<EN
                                For Visitors
                                EN
                            )
                        .'</h2>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                Wypełnij formularz i odbierz darmowy bilet
                                PL,
                                <<<EN
                                Fill out the form and receive your free ticket
                                EN
                            )
                        .'</p>
                        <form id="registration" number="' . $reg_form_id . '" method="post" action="' . $step2_url . '">
                            <input type="email" class="email" name="email" placeholder="'. 
                                self::languageChecker(
                                    <<<PL
                                    Adres Email
                                    PL,
                                    <<<EN
                                    Email
                                    EN
                                )
                            .'" autocomplete="email" required>
                            <p align="center" class="email-error"></p>
                            <input type="tel" class="phone" name="phone" autocomplete="tel" required>
                            <p align="center" class="phone-error telefon-error"></p>';

                            if($conferences != ''){
                                $reg_output .= '
                                    <div class="conf-selector">'.
                                        self::languageChecker(
                                            <<<PL
                                                <p>Wybierz konferencje które cię interesują?</p>
                                            PL,
                                            <<<EN
                                                <p>Choose the conferences that interest you?</p>
                                            EN
                                        );
                                $conf_array = explode(';', $conferences);
                                foreach($conf_array as $conf_val){
                                    $reg_output .= '
                                        <div>
                                            <input class="' . $conf_val . '" name="Konf-' . $conf_val . '" type="checkbox">
                                            <label>' . $conf_val . '</label>
                                        </div>
                                    ';
                                }
                                $reg_output .= '</div>';
                            }

                            $reg_output .= '<div class="consent-container">
                                <input class="consent-input checkbox" type="checkbox" class="consent" name="consent" required>
                                <span class="consent-text">'. 
                                    self::languageChecker(
                                        <<<PL
                                        Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych w celach marketingowych i wysyłki wiadomości.<span class="show-consent">(Więcej)</span>
                                        <div class="gfield_consent_description">
                                            Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych, tj. 1) imię i nazwisko; 2) adres e-mail 3) nr telefonu w celach wysyłki wiadomości marketingowych i handlowych związanych z produktami i usługami oferowanymi przez Ptak Warsaw Expo sp. z o.o. za pomocą środków komunikacji elektronicznej lub bezpośredniego porozumiewania się na odległość, w tym na otrzymywanie informacji handlowych, stosownie do treści Ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną. Wiem, że wyrażenie zgody jest dobrowolne, lecz konieczne w celu dokonania rejestracji. Zgodę mogę wycofać w każdej chwili.
                                        </div>
                                        PL,
                                        <<<EN
                                        I agree to the processing by PTAK WARSAW EXPO sp. z o.o. my personal data for marketing purposes and sending messages. <span class="show-consent">(More)</span>
                                        <div class="gfield_consent_description">
                                            I agree to the processing by PTAK WARSAW EXPO sp. z o.o. of my personal data, i.e. 1) name and surname; 2) e-mail address; 3) telephone number for the purposes of sending marketing and commercial messages related to products and services offered by Ptak Warsaw Expo sp. z o.o. by means of electronic communication or direct remote communication, including receiving commercial information, pursuant to the Act of 18 July 2002 on the provision of services by electronic means. I know that the consent is voluntary but necessary for registration. I can withdraw my consent at any time.
                                        </div>
                                        EN
                                    )
                                .'</span>
                            </div>
                            <p align="center" class="cons-error"></p>
                            <button class="form-1-btn g-recaptcha" type="submit" name="step-1-submit" data-sitekey="' . $captcha_public_key . '" data-callback="onSubmit"> '. 
                                self::languageChecker(
                                    <<<PL
                                    Odbierz darmowy bilet
                                    PL,
                                    <<<EN
                                    Receive a free ticket
                                    EN
                                )
                            .'</button>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                jQuery(function ($) {
                    $(".show-consent").on("click touch", function () {
                        $(this).next().toggle("slow");
                    });
                });
            </script>
        ';
        
        $reg_form = GFAPI::get_form($reg_form_id);
        
        foreach($reg_form['fields'] as $field){
            if(strpos(strtolower($field->label), 'email') !== false) {
                $reg_email_id = $field->id;
            } else if(strpos(strtolower($field->label), 'telefon') !== false || strpos(strtolower($field->label), 'phone') !== false){
                $reg_phone_id = $field->id;
            }
        }
        
        $inner_array = array (
            'form_id' => $reg_form_id,
            'email_id' => $reg_email_id,
            'phone_id' => $reg_phone_id,
            'locale' => get_locale(),
            'utilsScript' => plugin_dir_path(__FILE__) .'frontend/js/utils.js',
            'elements' =>  '.phone',
        );
        
        $js_file = plugins_url('js/form-checker.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'js/form-checker.js');
        wp_enqueue_script('form-checker-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script('form-checker-js', 'inner', $inner_array );
        
        $css_file1 = plugins_url('css/intlTelInput.min.css', __FILE__);		
        $css_version1 = filemtime(plugin_dir_path(__FILE__) . 'css/intlTelInput.min.css');    
		wp_enqueue_style( 'spf_intlTelInput', $css_file1, array(), $css_version1 );

        $css_file2 = plugins_url('css/spf_style.css', __FILE__);		
        $css_version2 = filemtime(plugin_dir_path(__FILE__) . 'css/spf_style.css');    
		wp_enqueue_style( 'spf_style', $css_file2, array('spf_intlTelInput'), $css_version2 );

        $js_file1 = plugins_url('js/intlTelInput-jquery.min.js', __FILE__);		
        $js_version1 = filemtime(plugin_dir_path(__FILE__) . 'js/intlTelInput-jquery.min.js');        
        wp_enqueue_script('area_intlTelInput', $js_file1, array( 'jquery' ), $js_version3, true);

        return $reg_output;
    }

     /**
     * Static method to display registration form (form1).
     * Returns the HTML output as a string.
     */
    public static function registrationHtmlHeader($reg_form_id, $step2_url, $conferences = '', $pwe_header_modes){
        $captcha_public_key = get_option('rg_gforms_captcha_public_key');

        if ($pwe_header_modes == "conference_mode") {
            $main_badge_color = self::$accent_color;
        } else {
            $main_badge_color = self::$main2_color;
        }
        
        $reg_output = '
            <script src="https://www.google.com/recaptcha/api.js?render=' . $captcha_public_key . '"></script>
            <style>
                #xForm {
                    max-width: 450px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    overflow: hidden;
                    padding-bottom: 36px;
                }
                #xForm .form-1-top {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    min-height: 465px;
                    padding: 36px 0;
                    background: #e8e8e8;
                    overflow: hidden;
                    border-radius: 0 0 24px 24px;
                }
                #xForm .form-1-top:before {
                    content:"";
                    position: absolute;
                    top: 0;
                    right: 0;
                    left: 0;
                    background-color: '. $main_badge_color .';
                    height: 60px;
                    z-index: 0;
                }
                #xForm .form-1-badge-top {
                    position: relative;
                    width: 100%;
                }
                #xForm .form-1-badge-right {
                    position: absolute;
                    right: 0;
                    top: 0;
                    bottom: 0;
                    width: 25px;
                }
                #xForm .form-1-badge-bottom {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    z-index: 1;
                    height: 25px;
                }
                #xForm .form-1-badge-left {
                    position: absolute;
                    left: 0;
                    top: 0;
                    bottom: 0;
                    width: 25px;
                }
                #xForm .form-1-image-qr {
                    position: relative;
                    position: absolute;
                    right: 36px;
                    top: 36px;
                    width: 100px;
                    object-fit: cover;
                    border-radius: 10px;
                    z-index: 1;
                }
                #xForm .form-1 {
                    width: 90%;
                    padding: 36px;
                }
                #xForm .form-1 h2 {
                    font-size: 32px;
                    font-weight: 700;
                }
                #xForm .form-1 form {
                    padding-top: 18px;
                    display: flex;
                    flex-direction: column;
                }
                #xForm input {
                    border: 2px solid '. $main_badge_color .' !important;
                    border-radius: 10px;
                    box-shadow: none !important;
                }
                #xForm .iti--allow-dropdown {
                    margin-top: 18px;
                }
                #xForm .iti__country-list {
                    list-style: none;
                    padding: 0;
                }
                #xForm input:not([type=checkbox]) {
                    margin-top: 18px;
                    width: 90%;
                }
                #xForm .consent-container{
                    margin-top: 36px;
                    display:flex;
                    gap: 10px;
                    overflow: hidden;
                }
                #xForm .consent-text,
                #xForm .gfield_consent_description {
                    font-size:12px;
                    line-height: 15px;
                }
                #xForm .gfield_consent_description {
                    overflow: unset !important;
                    padding-top: 5px !important;
                }
                #xForm button[type=submit] {
                    background-color: '. $main_badge_color .';
                    border-width: 1px;
                    border-radius: 10px;
                    border: 2px solid '. $main_badge_color .' !important;
                    font-size: 1em;
                    margin-top: 36px;
                    color: white;
                    align-self: center;
                }
                #xForm button[type=submit]:hover {
                    background-color: white;
                    color: '. $main_badge_color .' !important;
                    border: 2px solid '. $main_badge_color .' !important;
                }
                #xForm .mail-error, #xForm .tel-error, #xForm .cons-error{
                    margin: 0 11px;
                    width:85%;
                }
                #xForm .show-consent {
                    color: black !important; 
                }
                #xForm form :is(.email-error, .phone-error, .cons-error) {
                    font-size: 12px;
                    color: red;
                    width: 90%;
                    margin-top: 0px;
                    text-transform: uppercase;
                    background-color: rgba(255, 223, 224);
                }
                @media (max-width:450px){
                    #xForm .form-1 {
                        width: 80%;
                        padding: 36px 18px;
                    }
                    #xForm form {
                        width: 100%;
                    }
                    #xForm .form-1-top {
                        padding: 0;
                    }
                    #xForm .form-1 h2 {
                        margin-top: 36px;
                        font-size: 24px;
                    }
                    #xForm .form-1-image-qr {
                        top: 20px;
                        width: 80px;
                    }
                    #xForm .consent-container {
                        margin-top: 18px;
                    }
                    #xForm button[type=submit] {
                        padding: 9px;
                        font-size: 12px;
                    }
                    #xForm input:not([type=checkbox]) {
                        width: 100%;
                    }
                }     
            </style>
            
            <div id="xForm">
                <img class="form-1-badge-top" src="/wp-content/plugins/pwe-media/media/badge_top.png">
                <div class="form-1-top pwe-registration">
                    <img class="form-1-badge-left" src="/wp-content/plugins/pwe-media/media/badge_left.png">
                    <img class="form-1-badge-bottom" src="/wp-content/plugins/pwe-media/media/badge_bottom.png">
                    <img class="form-1-badge-right" src="/wp-content/plugins/pwe-media/media/badge_right.png">
                    <img class="form-1-image-qr" src="/wp-content/plugins/pwe-media/media/badge_qr.png">
                    <div class="form-1">';
                        if ($pwe_header_modes == "conference_mode") {
                            $reg_output .= '<h2>'.
                            self::languageChecker(
                                <<<PL
                                Twój bilet na<br>konferencje i targi
                                PL,
                                <<<EN
                                Your ticket to<br>conferences and<br>trade fairs
                                EN
                            )
                            .'</h2>';
                        } else {
                            $reg_output .= '<h2>'.
                            self::languageChecker(
                                <<<PL
                                Twój bilet<br>na targi
                                PL,
                                <<<EN
                                Your ticket<br>to the fair
                                EN
                            )
                            .'</h2>';
                        }
                        $reg_output .= '
                        <form id="registration" number="' . $reg_form_id . '" method="post" action="' . $step2_url . '">
                            <input type="email" class="email" name="email" placeholder="'. 
                                self::languageChecker(
                                    <<<PL
                                    Adres Email
                                    PL,
                                    <<<EN
                                    Email
                                    EN
                                )
                            .'" autocomplete="email" required>
                            <p align="center" class="email-error"></p>
                            <input type="tel" class="phone" name="phone" autocomplete="tel" required>
                            <p align="center" class="phone-error telefon-error"></p>';

                            if($conferences != ''){
                                $reg_output .= '
                                    <div class="conf-selector">'.
                                        self::languageChecker(
                                            <<<PL
                                                <p>Wybierz konferencje które cię interesują?</p>
                                            PL,
                                            <<<EN
                                                <p>Choose the conferences that interest you?</p>
                                            EN
                                        );
                                $conf_array = explode(';', $conferences);
                                foreach($conf_array as $conf_val){
                                    $reg_output .= '
                                        <div>
                                            <input class="' . $conf_val . '" name="Konf-' . $conf_val . '" type="checkbox">
                                            <label>' . $conf_val . '</label>
                                        </div>
                                    ';
                                }
                                $reg_output .= '</div>';
                            }

                            $reg_output .= '<div class="consent-container">
                                <input class="checkbox" type="checkbox" class="consent" name="consent" required>
                                <span class="consent-text">'. 
                                    self::languageChecker(
                                        <<<PL
                                        Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych w celach marketingowych i wysyłki wiadomości. <span class="show-consent">(Więcej)</span>
                                        <div class="gfield_consent_description">
                                            Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych, tj. 1) imię i nazwisko; 2) adres e-mail 3) nr telefonu w celach wysyłki wiadomości marketingowych i handlowych związanych z produktami i usługami oferowanymi przez Ptak Warsaw Expo sp. z o.o. za pomocą środków komunikacji elektronicznej lub bezpośredniego porozumiewania się na odległość, w tym na otrzymywanie informacji handlowych, stosownie do treści Ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną. Wiem, że wyrażenie zgody jest dobrowolne, lecz konieczne w celu dokonania rejestracji. Zgodę mogę wycofać w każdej chwili.
                                        </div>
                                        PL,
                                        <<<EN
                                        I agree to the processing by PTAK WARSAW EXPO sp. z o.o. my personal data for marketing purposes and sending messages.  <span class="show-consent">(More)</span>
                                        <div class="gfield_consent_description">
                                            I agree to the processing by PTAK WARSAW EXPO sp. z o.o. of my personal data, i.e. 1) name and surname; 2) e-mail address; 3) telephone number for the purposes of sending marketing and commercial messages related to products and services offered by Ptak Warsaw Expo sp. z o.o. by means of electronic communication or direct remote communication, including receiving commercial information, pursuant to the Act of 18 July 2002 on the provision of services by electronic means. I know that the consent is voluntary but necessary for registration. I can withdraw my consent at any time.
                                        </div>
                                        EN
                                    )
                                .'</span>
                            </div>
                            <p align="center" class="cons-error"></p>
                            <button class="form-1-btn g-recaptcha" type="submit" name="step-1-submit" data-sitekey="' . $captcha_public_key . '" data-callback="onSubmit"> '. 
                                self::languageChecker(
                                    <<<PL
                                    Odbierz darmowy bilet
                                    PL,
                                    <<<EN
                                    Receive a free ticket
                                    EN
                                )
                            .'</button>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                jQuery(function ($) {
                    $(".show-consent").on("click touch", function () {
                        $(this).next().toggle("slow");
                    });
                });
            </script>
        ';
        
        $reg_form = GFAPI::get_form($reg_form_id);
        
        foreach($reg_form['fields'] as $field){
            if(strpos(strtolower($field->label), 'email') !== false) {
                $reg_email_id = $field->id;
            } else if(strpos(strtolower($field->label), 'telefon') !== false || strpos(strtolower($field->label), 'phone') !== false){
                $reg_phone_id = $field->id;
            }
        }
        
        $inner_array = array (
            'form_id' => $reg_form_id,
            'email_id' => $reg_email_id,
            'phone_id' => $reg_phone_id,
            'locale' => get_locale(),
            'utilsScript' => plugin_dir_path(__FILE__) .'frontend/js/utils.js',
            'elements' =>  '.phone',
        );
        
        $js_file = plugins_url('js/form-checker.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'js/form-checker.js');
        wp_enqueue_script('form-checker-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script('form-checker-js', 'inner', $inner_array );
        
        $css_file1 = plugins_url('css/intlTelInput.min.css', __FILE__);		
        $css_version1 = filemtime(plugin_dir_path(__FILE__) . 'css/intlTelInput.min.css');    
		wp_enqueue_style( 'spf_intlTelInput', $css_file1, array(), $css_version1 );

        $css_file2 = plugins_url('css/spf_style.css', __FILE__);		
        $css_version2 = filemtime(plugin_dir_path(__FILE__) . 'css/spf_style.css');    
		wp_enqueue_style( 'spf_style', $css_file2, array('spf_intlTelInput'), $css_version2 );

        $js_file1 = plugins_url('js/intlTelInput-jquery.min.js', __FILE__);		
        $js_version1 = filemtime(plugin_dir_path(__FILE__) . 'js/intlTelInput-jquery.min.js');        
        wp_enqueue_script('area_intlTelInput', $js_file1, array( 'jquery' ), $js_version3, true);
    
        return $reg_output;
    }
    

    /**
     * Static method to display seccond step form (step2).
     * Returns the HTML output as a string.
     */
    public static function step2Html($reg_form_id, $confirmation_url, $text_color, $fair_logo, $go_back_url){        
        if (isset($_POST['email']) && self::recaptcha_check()){   
            $entry_id = self::x_form_register($reg_form_id);
        } else {
            self::redirectTo($go_back_url);
        }
        
        $step2_output .= '
            <style>
                .pwelement_' . self::$rnd_id . ' #xForm{
                    min-height: 700px;
                    display: flex;
                    align-items: center;
                }
                .pwelement_' . self::$rnd_id . ' :is(.form-2, .form-2-right) {
                    flex: 1;
                    padding: 9px 18px;
                }

                .pwelement_' . self::$rnd_id . ' .form-2 span {
                    color: ' . $text_color . '
                }
                
                .pwelement_' . self::$rnd_id . ' .form-2>div {
                    width: 500px;
                    text-align: left;
                    margin: auto;
                }
                .pwelement_' . self::$rnd_id . ' .form-2 .wystawca {
                    margin-top: 65px;
                }
                .pwelement_' . self::$rnd_id . ' .form-2-right {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    gap: 10px;
                    background-image: url(/doc/background.webp);
                    background-color: black;
                    background-size: cover;
                    width:50%;
                    min-height: inherit;
                    padding: 36px;
                }
                
                .pwelement_' . self::$rnd_id . ' .form-2 .font13{
                    font-size: 13px;
                }
                .pwelement_' . self::$rnd_id . ' .form-2-right img{
                    max-width: 350px;
                    max-height: 200px;
                    width: 100%;
                }

                .pwelement_' . self::$rnd_id . ' .form-2-right :is(h4, h6) {
                    text-shadow: 0 0 2px black;
                    color: white !important;
                    margin-top: 0px;
                }
                .pwelement_' . self::$rnd_id . ' #exhibitor-question{
                    display: flex;
                    justify-content: space-between;
                    margin: 36px 9px;
                    flex-wrap: wrap;
                }
                .pwelement_' . self::$rnd_id . ' #exhibitor-question button{
                    border-radius: 10px;
                }

                #xForm #exhibitor-question .exhibitor-yes{
                    color: white;
                    background-color:' . $text_color . ';
                    border: 1px solid ' . $text_color . ';
                }

                .pwelement_' . self::$rnd_id . ' #exhibitor-question .exhibitor-yes:hover{
                    color: black;
                    background-color: white;
                    border: 1px solid ' . $text_color . ';
                }

                .pwelement_' . self::$rnd_id . ' #exhibitor-question .exhibitor-no{
                    color: black;
                    background-color: white;
                    border: 1px solid black;
                }

                .pwelement_' . self::$rnd_id . ' #exhibitor-question .exhibitor-no:hover{
                    color: white;
                    background-color: #232426;
                    flex-wrap: wrap;
                }
                .pwelement_' . self::$rnd_id . ' .form-2-bottom{
                    background-color: #f7f7f7;
                    display: flex;
                    justify-content: center;
                    gap: 18px;
                    flex-wrap: wrap;
                    padding: 18px;

                }
                .pwelement_' . self::$rnd_id . ' .form-2-bottom div{
                    flex:1;
                    display: flex;
                    justify-content: center;
                    flex-wrap: wrap;
                    gap:9px;
                }
                .pwelement_' . self::$rnd_id . ' .form-2-bottom div>div{
                    flex:1;
                    min-width: 200px;
                }
                .pwelement_' . self::$rnd_id . ' .form-2-bottom img{
                    max-height: 80px;
                }

                .pwelement_' . self::$rnd_id . ' .form-2-bottom :is(.for-exhibitors, .for-visitors){
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .pwelement_' . self::$rnd_id . ' .form-2-bottom :is(.for-exhibitors, .for-visitors) p{
                    margin-top: 0px;
                }
                @media (min-width:570px) and (max-width:959px){
                    .pwelement_' . self::$rnd_id . ' #xForm div{
                        padding: 18px;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-2>div {
                        width: unset;
                        padding: 18px;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-2-bottom {
                        flex-direction: column;
                    }
                }
                @media (max-width:569px){
                    .pwelement_' . self::$rnd_id . ' #xForm{
                        min-height: unset;
                        flex-wrap: wrap;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-2>div {
                        width: unset;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-2-bottom div>div{
                        min-width: unset;
                    }
                    .pwelement_' . self::$rnd_id . ' .form-2-bottom {
                        flex-direction: column;
                    }
                    .pwelement_' . self::$rnd_id . ' .numbers p {
                        text-align: center;
                    }
                }
            </style>
            <div id="xForm">
                <div class="form-2">
                    <div>
                        <h5 class="krok"> '. 
                            self::languageChecker(
                                <<<PL
                                    Krok <span>2 z 2
                                PL,
                                <<<EN
                                    Step <span>2 of 2
                                EN
                            )
                        .'</span></h5>
                        <h2 class="text-color-jevc-color">'. 
                            self::languageChecker(
                                <<<PL
                                    Twój bilet został<br>wygenerowany pomyślnie!
                                PL,
                                <<<EN
                                    Your ticket has been<br>generated successfully!
                                EN
                            )
                        .'</h2>
                        <p class="font13">'. 
                            self::languageChecker(
                                <<<PL
                                    Otrzymasz go na wskazany adres e-mail.<br>Może to potrwać kilka minut.
                                PL,
                                <<<EN
                                    You will receive it at the e-mail address indicated.<br>May take a few minutes.
                                EN
                            )
                        .'</p>
                        <h3 class="wystawca">'. 
                            self::languageChecker(
                                <<<PL
                                    Czy chcesz zostać <span>wystawcą</span> targów [trade_fair_name] ?
                                PL,
                                <<<EN
                                    Do you want to become a <span>exhibitor</span> of [trade_fair_name_eng] ?
                                EN
                            )
                        .'</h3>
                        <form id="exhibitor-question" method="post" action="' . $confirmation_url . '">
                            <input type="hidden" name="email" value="'.$_POST['email'].'">
                            <input type="hidden" name="phone" value="'.$_POST['phone'].'">
                            <input type="hidden" name="entry_id" value="'.$entry_id.'">
                            <button type="submit" class="btn exhibitor-yes" name="exhibitor-yes">'. 
                                self::languageChecker(
                                    <<<PL
                                        Tak, jestem zainteresowany
                                    PL,
                                    <<<EN
                                        Yes, I am interested
                                    EN
                                )
                            .'</button>
                            <button type="submit" class="btn exhibitor-no" name="exhibitor-no">'. 
                                self::languageChecker(
                                    <<<PL
                                        Nie, dziękuję
                                    PL,
                                    <<<EN
                                        No, thank you
                                    EN
                                )
                            .'</button>
                        </form>
                    </div>
                </div>
                <div class="form-2-right">'. 
                        self::languageChecker(
                            <<<PL
                                <img src="/doc/logo.webp">
                                <h4>[trade_fair_date]</h4>
                            PL,
                            <<<EN
                                <img src="/doc/logo-en.webp">
                                <h4>[trade_fair_date_eng]</h4>
                            EN
                        )
                    .'                    
                    <h6>w Ptak Warsaw Expo</h6>
                </div>
            </div>
            <div class="form-2-bottom">
                <div class="logos">
                    <div class="pwe-logo">
                        <img src="' . plugin_dir_url(dirname( __FILE__ )) . "/media/logo_pwe_black.webp" . '">
                    </div>
                    <div class="fair-logo">
                        <img src="' . $fair_logo . '">
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
        ';

        return $step2_output;
    }

    /**
     * Static method to display seccond step form (step2).
     * Returns the HTML output as a string.
     */
    public static function confirmYesHtml($atts, $exh_form_id, $text_color){
        $exh_confirmation = false;
        if (isset($_POST['exhibitor-yes'])){
            $exh_entry_id = self::x_form_register($exh_form_id);
        } else if (isset($_POST['exhibitors-form'])) {
            $exh_confirmation = self::add_side_entry($exh_form_id, $_POST);
            self::add_info_email($exh_form_id, $_POST);
        }
        
        $yes_output .= '
            <style>
                .pwelement_' . self::$rnd_id . ' #xForm{
                    display: flex;
                    gap: 20px;
                }
                .pwelement_' . self::$rnd_id . ' #xForm>div{
                    align-content: center;
                    min-height: 643px;
                    width: 33%;
                    flex: 1;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-left {
                    text-align: -webkit-right;
                    padding: 36px;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-left span{
                    color: ' . $text_color . ';
                }

                .pwelement_' . self::$rnd_id . ' .form-3{
                    text-align: left;
                    padding: 36px;
                    background-color: #E8E8E8;
                    min-height: inherit;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-right{
                    padding: 36px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 27px;
                    text-align: center;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-left>div {
                    text-align:left;
                    max-width: 450px;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 h3{
                    color: #c49a62;
                }
                
                .pwelement_' . self::$rnd_id . ' .form-3 form{
                    margin-top: 18px;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 form label{
                    text-align: left;
                    font-weight: 700;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 form :is(input, textarea){
                    margin-bottom: 18px;
                    width: 100%;
                    border-radius: 11px;
                    border-color: #c49a62 !important;
                    box-shadow: none !important;
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
                @media (min-width:570px) and (max-width:959px){
                    .pwelement_' . self::$rnd_id . ' .form-3-right {
                        display:none;
                    }
                }
                @media (max-width:569px){

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
                    .pwelement_' . self::$rnd_id . ' #xForm .form-3-right .pwe-btn{
                        transform-origin: center;
                    }
                }
            </style>
            <div id="xForm">
                <div class="form-3-left">
                    <div>'.
                        self::languageChecker(
                            <<<PL
                                <h2 class="text-color-jevc-color">Dziękujemy za rejestrację chęci udziału Państwa firmy na targach <span>[trade_fair_name]!</span></h2>
                                <p>Wkrótce nasz przedstawiciel skontaktuje się z Państwem, aby przedstawić ofertę wystawienniczą oraz korzyści płynące z udziału w targach.</p>
                            PL,
                            <<<EN
                                <h2 class="text-color-jevc-color">Thank you for registering your company's desire to participate in the trade fair <span>[trade_fair_name]!</span></h2>
                                <p>Our representative will be in touch with you shortly to present our exhibition offer and the benefits of participating in the fair.</p>
                            EN
                        )
                    .'
                    </div>
                </div>';

                if(!$exh_confirmation && isset($_POST['cos'])){
                    $yes_output .= '
                    <div class="form-3">'.
                        self::languageChecker(
                            <<<PL
                                <h3>Prosimy o podanie dodatkowych szczegółów</span></h3>
                                <p>Pomoże nam to w dobraniu odpowiednich warunków i usprawnieniu komunikacji.</p>
                            PL,
                            <<<EN
                                <h3>Please provide additional details</span></h3>
                                <p>This will help us to choose the right conditions and improve communication.</p>
                            EN
                        )
                    .'
                        <form id="form3" action="" method="post">'.
                            self::languageChecker(
                                <<<PL
                                    <label>Imię i nazwisko</label>
                                    <input type="text" class="imie" name="imie" placeholder="Imię i nazwisko osoby do kontaktu" autocomplete="name" required>
                                    <label>Firma</label>
                                PL,
                                <<<EN
                                    <label>Full name</label>
                                    <input type="text" class="imie" name="imie" placeholder="Name of the person to contact" autocomplete="name" required>
                                    <label>Company</label>
                                EN
                            )
                        .'
                            <input type="hidden" name="entry_id" value="' . $exh_entry_id . '">
                            <div>'.
                                self::languageChecker(
                                    <<<PL
                                        <input type="text" class="dane" name="company-name" placeholder="Nazwa Firmy" autocomplete="company">
                                        <input type="text" class="dane" name="company-nip" placeholder="NIP">
                                    PL,
                                    <<<EN
                                        <input type="text" class="dane" name="company-name" placeholder="Company Name" autocomplete="company">
                                        <input type="text" class="dane" name="company-nip" placeholder="TAX ID">
                                    EN
                                )
                            .'
                            </div>
                            <div class="button-submit">
                            '.
                                self::languageChecker(
                                    <<<PL
                                        <textarea type="text" class="dane" name="company-adds" placeholder="Dodatkowe Informacje" autocomplete="nip"></textarea>
                                        <button type="submit" name="exhibitors-form">Zatwierdź</button>
                                    PL,
                                    <<<EN
                                        <textarea type="text" class="dane" name="company-adds" placeholder="Additional information" autocomplete="nip"></textarea>
                                        <button type="submit" name="exhibitors-form">Submit</button>
                                    EN
                                )
                            .'
                            </div>
                        </form>
                    </div>';
                } else {
                    $yes_output .= '
                    <div class="form-3">'.
                        self::languageChecker(
                            <<<PL
                                <p>Dziękujemy za uzupełnienie danych. Do usłyszenia już wkrótce. <br> Zespół Ptak Warsaw Expo</p>
                                <a class="pwe-link btn pwe-btn btn-confirmation" href="/">Powrót</a>
                            PL,
                            <<<EN
                                <p>Thank you for completing the data. Do usłyszenia już wkrótce. <br> Ptak Warsaw Expo Team</p>
                                <a class="pwe-link btn pwe-btn btn-confirmation" href="/">Back</a>
                            EN
                        )
                    .'
                    </div>';
                }

                $yes_output .= '
                <div class="form-3-right">
                    <img class="img-stand" src="/wp-content/plugins/pwe-media/media/zabudowa.webp" alt="zdjęcie przykładowej zabudowy"/>
                    <h5>'. 
                        self::languageChecker(
                            <<<PL
                                Dedykowana Zabudowa Targowa
                            PL,
                            <<<EN
                                Dedicated Market Place
                            EN
                        )
                    .'</h5>
                        <a class="pwe-link btn pwe-btn btn-stand" target="_blank" '. 
                            self::languageChecker(
                                <<<PL
                                    href="https://warsawexpo.eu/zabudowa-targowa">Sprawdź ofertę zabudowy
                                PL,
                                <<<EN
                                    href="https://warsawexpo.eu/en/exhibition-stands">See the offer
                                EN
                            )
                        .'</a>
                </div>
            </div>
        ';

        return $yes_output;
    }

     /**
     * Static method to display seccond step form (step2).
     * Returns the HTML output as a string.
     */
    public static function confirmNoHtml($atts, $reg_form_id,  $conf_form_id, $text_color, $go_back_url){
        $recaptcha = false;
        if (isset($_POST['g-recaptcha-response'])){            
            if (self::recaptcha_check()){
                $recaptcha = true;
            } else {
                self::redirectTo($go_back_url);
            }
        }
        
        if (isset($_POST['exhibitor-no'])){
            $entry_id = $_POST['entry_id'];
        } else if ($recaptcha && !isset($_POST['entry_id'])) {    
            $entry_id = self::x_form_register($conf_form_id);
        } else if (isset($_POST['visitors-form'])) {
            $exh_confirmation = self::add_side_entry($reg_form_id, $_POST);
        }

        $no_output .= '
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
        <style>
        .pwelement_' . self::$rnd_id . ' #xForm{
                    display: flex;
                    gap: 20px;
                }
                .pwelement_' . self::$rnd_id . ' .very-strong{
                    font-weight:700;
                }

                .pwelement_' . self::$rnd_id . ' #xForm>div{
                    align-content: center;
                    min-height: 643px;
                    width: 33%;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-left {
                    text-align: -webkit-right;
                    padding: 36px;
                }

                .pwelement_' . self::$rnd_id . ' .form-3{
                    text-align: left;
                    padding: 25px 50px;
                    background-color: #E8E8E8;
                    min-height: inherit;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-right{
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 27px;
                }

                .pwelement_' . self::$rnd_id . ' .form-3-left>div {
                    text-align:left;
                    max-width: 450px;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 span{
                    color: #c49a62;
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

                .pwelement_' . self::$rnd_id . ' .form-3 form label{
                    text-align: left;
                    font-weight: 700;
                }

                .pwelement_' . self::$rnd_id . ' .form-3 form :is(input, textarea){
                    margin-bottom: 18px;
                    width: 100%;
                    border-radius: 11px;
                    border-color: #c49a62 !important;
                    box-shadow: none !important;
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
                @media (min-width:570px) and (max-width:959px){
                    .pwelement_' . self::$rnd_id . ' .form-3-right {
                        display:none;
                    }
                    .pwelement_' . self::$rnd_id . ' #xForm>div{
                        width: 50%;
                    }
                }
                @media (max-width:569px){
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
                }

            </style>

            <div id="xForm">
                <div class="form-3-left">
                    <div>'. 
                        self::languageChecker(
                            <<<PL
                                <h2 class="text-color-jevc-color">Dziękujemy za rejestrację na <br><span class="very-strong">[trade_fair_name]!</span></h2>
                                <p>Cieszymy się, że dołączasz do naszego wydarzenia, pełnego nowości rynkowych i inspiracji do zastosowania w Twojej firmie.</p><br>
                                <p><span class="very-strong">Zachęcamy do wypełnienia</span> ostatniego formularza, dzięki temu będziemy mogli przygotować dla Was <span class="very-strong">wyjątkowy pakiet powitalny VIP</span>, który usprawni Państwapobyt na targach.</p>
                            PL,
                            <<<EN
                                <h2 class="text-color-jevc-color">Thank you for registering at <br><span class="very-strong">[trade_fair_name_eng]!</span></h2>
                                <p>We are delighted that you are joining our event, full of market news and inspiration for use in your business.</p><br>
                                <p><span class="very-strong">We encourage you to fill in</span> the last form, thanks to which we will be able to prepare for you a <span class="very-strong">exclusive VIP welcome package</span> that will enhance your stay at the fair.</p>
                            EN
                        )
                    .'
                    </div>
                </div>';
                if(!$exh_confirmation){
                    $no_output .= '
                    <div class="form-3">
                        '. 
                            self::languageChecker(
                                <<<PL
                                    <h3>Podaj adres, na który mamy wysłać <span>darmowy pakiet powitalny VIP</span></h3>
                                    <p>Otrzymasz bezpłatny spersonalizowany identyfikator wraz z planem/harmonogramem targów oraz kartę parkingową.</p>
                                PL,
                                <<<EN
                                    <h3>Enter the address where we should send the <span>free VIP welcome pack</span></h3>
                                    <p>You will receive a complimentary personalised badge along with the exhibition schedule/schedule and a parking pass.</p>
                                EN
                            )
                        .'                    
                        <form id="form3" action="" method="post">
                            <input type="hidden" name="entry_id" value="' . $entry_id . '">
                            <div class="form3-half">
                                <div>
                                    '. 
                                        self::languageChecker(
                                            <<<PL
                                                <label>Imię</label>
                                                <input type="text" class="imie" name="imie" placeholder="Imię" autocomplete="first-name" required>
                                            PL,
                                            <<<EN
                                                <label>Name</label>
                                                <input type="text" class="imie" name="imie" placeholder="Name" autocomplete="first-name" required>
                                            EN
                                        )
                                    .'  
                                </div>
                                <div>
                                    '. 
                                        self::languageChecker(
                                            <<<PL
                                                <label>Nazwisko</label>
                                                <input type="text" class="nazwisko" name="nazwisko" placeholder="Nazwisko" autocomplete="family-name" required>
                                            PL,
                                            <<<EN
                                                <label>Surname</label>
                                                <input type="text" class="nazwisko" name="nazwisko" placeholder="Surname" autocomplete="family-name" required>
                                            EN
                                        )
                                    .'  
                                </div>
                            </div>
                                '. 
                                    self::languageChecker(
                                        <<<PL
                                            <label>Adres</label>
                                            <input type="text" class="ulica" name="ulica" placeholder="Adres" autocomplete="address" required>
                                        PL,
                                        <<<EN
                                            <label>Address</label>
                                            <input type="text" class="ulica" name="ulica" placeholder="Address" autocomplete="address" required>
                                        EN
                                    )
                                .'
                            <div class="form3-half">
                                <div>
                                    '. 
                                        self::languageChecker(
                                            <<<PL
                                                <label>Kod Pocztowy</label>
                                                <input type="text" class="kod_pocztowy" name="kod_pocztowy" placeholder="Kod pocztowy" autocomplete="postal-code" required>
                                            PL,
                                            <<<EN
                                                <label>Postcode</label>
                                                <input type="text" class="kod_pocztowy" name="kod_pocztowy" placeholder="Postcode" autocomplete="postal-code" required>
                                            EN
                                        )
                                    .'
                                </div>
                                <div>
                                    '. 
                                        self::languageChecker(
                                            <<<PL
                                                <label>Miasto</label>
                                                <input type="text" class="miasto" name="miasto" placeholder="Miasto" autocomplete="address-level2" required>
                                            PL,
                                            <<<EN
                                                <label>City</label>
                                                <input type="text" class="miasto" name="miasto" placeholder="City" autocomplete="address-level2" required>
                                            EN
                                        )
                                    .'
                                </div>
                            </div>
                            <div>'. 
                                self::languageChecker(
                                    <<<PL
                                        <button type="submit" name="visitors-form">Zamawiam bezpłatny identyfikator</button>
                                    PL,
                                    <<<EN
                                        <button type="submit" name="visitors-form">I order a free badge</button>
                                    EN
                                )
                            .'</div>
                        </form>
                    </div>';
                } else {
                    $no_output .= '
                    <div class="form-3">'. 
                    self::languageChecker(
                        <<<PL
                            <p>Dziękujemy za uzupełnienie danych. Do usłyszenia już wkrótce. <br> Zespół Ptak Warsaw Expo</p>
                            <a class="pwe-btn">Powrót</a>
                        PL,
                        <<<EN
                            <p>Thank you for completing the data. We look forward to hearing from you soon. <br>Ptak Warsaw Expo team</p>
                        EN
                    )
                .'
                        
                    </div>';
                }
                $no_output .= '
                <div class="form-3-right">
                    <img src="/doc/badge-mockup.webp">
                </div>
            </div>
            <script>
                jQuery(document).ready(function($){
                    $(".kod_pocztowy").inputmask("99-999");
                })
            </script>
        ';

        return $no_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     */
    public static function output($atts) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$main2_color);
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color);

        $go_back_url = ($atts['go_back_url'] != '') ? $atts['go_back_url'] : self::languageChecker(
            <<<PL
                /rejestracja
            PL,
            <<<EN
                /en/registration
            EN
        );

        $confirmation_url = ($atts['confirmation_url'] != '') ? $atts['confirmation_url'] : self::languageChecker(
            <<<PL
                /potwierdzenie-rejestracji
            PL,
            <<<EN
                /en/registration-confirmation
            EN
        );

        if ($atts['x_form_placemant'] == 'conferance'){
            $step2_url = ($atts['step2'] != '') ? $atts['step2'] : 
                self::languageChecker(
                    <<<PL
                        /potwierdzenie-rejestracji
                    PL,
                    <<<EN
                        /en/registration-confirmation
                    EN
                )
            ;
        } else {
            $step2_url = ($atts['step2'] != '') ? $atts['step2'] : 
                self::languageChecker(
                    <<<PL
                        /krok2
                    PL,
                    <<<EN
                        /en/step2
                    EN
                )
            ;
        }

        $fair_logo = ($atts['fair_logo'] != '') ? $atts['fair_logo'] : self::languageChecker(
            <<<PL
                /doc/logo-color.webp
            PL,
            <<<EN
                /doc/logo-color-en.webp
            EN
        );
        $fair_logo = trim($fair_logo);

        $reg_form_id = isset($atts['reg_form_name']) ? self::findFormsID($atts['reg_form_name']) : '';
        $exh_form_id = isset($atts['exh_form_name']) ? self::findFormsID($atts['exh_form_name']) : '';
        $conf_form_id = isset($atts['conf_form_name']) ? self::findFormsID($atts['conf_form_name']) : '';

        $exhibitor = ($atts['exhibitor_email'] != '') ? $atts['exhibitor_email'] : "lidyst@warsawexpo.eu";
        
        if (is_admin() || wp_doing_ajax()) {
            return;
        }
        
        if ($atts['x_form_placemant'] === 'register' || $atts['x_form_placemant'] === 'conferance'){
            return self::registrationHtml($reg_form_id, $step2_url, $atts['conferences']);
        } else if ($atts['x_form_placemant'] === 'step2'){
            return self::step2Html($reg_form_id, $confirmation_url, $text_color, $fair_logo, $go_back_url);
        } else if ($atts['x_form_placemant'] === 'confirmation' && (isset($_POST['exhibitor-yes']) || isset($_POST['exhibitors-form']))){
            return self::confirmYesHtml($atts, $exh_form_id, $text_color, $go_back_url);
        } else if ($atts['x_form_placemant'] === 'confirmation' && (isset($_POST['exhibitor-no']) || isset($_POST['g-recaptcha-response']) || isset($_POST['visitors-form']))) {
            return self::confirmNoHtml($atts, $reg_form_id,  $conf_form_id, $text_color, $go_back_url);
        } else {     
            self::redirectTo($go_back_url);
        }
    }
}