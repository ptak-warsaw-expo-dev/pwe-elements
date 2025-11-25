<?php

/**
 * Class PWElementStepTwoExhibitor
 * Extends PWElements class and defines a pwe Visual Composer element for x-steps-form.
 */
class PWElementStepTwoExhibitor extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
        add_filter('gform_pre_render', array($this, 'hideFieldsBasedOnAdminLabel'));
    }
    /**
     * Ukrywa pola w formularzu na podstawie etykiety admina.
     *
     * @param array $form Formularz Gravity Forms
     * @return array Zaktualizowany formularz
     */
    public function hideFieldsBasedOnAdminLabel($form) {
        if ($_SESSION["pwe_reg_entry"]["email"]) {
            foreach ($form['fields'] as &$field) {
                if (in_array($field->adminLabel, ['mail', 'number'])) {
                    $field->visibility = 'hidden';
                }
            }
        }
        return $form;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/pot_rej_wys.json';

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

    public static function get_translations() {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/pot_rej_wys.json';

        $translations_data = json_decode(file_get_contents($translations_file), true);

        if (isset($translations_data[$locale])) {
            return $translations_data[$locale];
        }

        return $translations_data['en_US'];
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
                'heading' => __('Registration form', 'pwelement'),
                'param_name' => 'registration_form_step2_exhibitor',
                'save_always' => true,
                'value' => array_merge(
                    array('Wybierz' => ''),
                    self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwoExhibitor',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Update entries one form', 'pwelement'),
                'param_name' => 'registration_form_step2_exhibitor_update_entries',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwoExhibitor',
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Registration form www2', 'pwelement'),
                'param_name' => 'registration_form_step2_exhibitor_www2',
                'save_always' => true,
                'value' => array_merge(
                    array('Wybierz dodatkowy formularz' => ''),
                    self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'registration_form_step2_exhibitor_update_entries',
                    'value' => 'true',
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
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black');
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color);
        $btn_shadow_color = self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black');
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color);

        extract( shortcode_atts( array(
            'registration_form_step2_exhibitor' => '',
            'registration_form_step2_exhibitor_update_entries' => '',
            'registration_form_step2_exhibitor_www2' => '',
        ), $atts ));


        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $pwe_groups_contacts_data = PWECommonFunctions::get_database_groups_contacts_data();

        $source_utm = (isset($_SERVER['argv'][0])) ? $_SERVER['argv'][0] : '';
        // Get domain address
        $current_domain = $_SERVER['HTTP_HOST'];

        foreach ($pwe_groups_data as $group) {
            if ($current_domain == $group->fair_domain) {
                $current_group = $group->fair_group;
            }
        }


        $confirmation_button_text = self::multi_translation("generate_an_offer");
        $main_page_text_btn = self::multi_translation("back_to_main_page");;

        $file_url = plugins_url('elements/fetch.php', dirname(__FILE__));

        /* Update text tranform */
        $lang = get_locale();

        $userSessionEmail = $_SESSION["pwe_reg_entry"]["email"]  ?? null;
        $userSessionPhone = $_SESSION["pwe_reg_entry"]["phone"] ?? null;


        $directUrl = $_SESSION['pwe_exhibitor_entry']['current_url'];

        if ($directUrl == "/zostan-wystawca/" || $directUrl == "/en/become-an-exhibitor/" || $directUrl == "/de/bestaetigung-der-ausstellerregistrierung/") {
            $form_to_update = $registration_form_step2_exhibitor;
        } elseif (strpos($registration_form_step2_exhibitor, "Potwierdzenie rejestracji wystawcy") !== false) {
            $form_to_update = $registration_form_step2_exhibitor;
        } else {
            $form_to_update = $registration_form_step2_exhibitor_www2;
        }

        // $translations = [
        //     'pl' => [
        //         'name' => 'Imię i Nazwisko',
        //         'area' => 'Wybierz powierzchnię wystawienniczą',
        //         'company' => 'Firma',
        //         'confirm_text' => 'Dziękujemy za uzupełnienie danych. Do usłyszenia już wkrótce. Zespół Ptak Warsaw Expo',
        //         'error' => 'Oznaczona pola są wymagane!',
        //         'tax' => 'NIP',
        //         'company_desc' => 'Dodatkowe informacje o firmie',
        //         'consent' => '* pola oznaczone gwiazdką są obowiązkowe',
        //         'from' => 'od',
        //         'to' => 'do',
        //     ],
        //     'en' => [
        //         'name' => 'Name',
        //         'area' => 'Select exhibition area',
        //         'company' => 'Company',
        //         'confirm_text' => 'Thank you for completing the data. We look forward to hearing from you soon. Ptak Warsaw Expo Team',
        //         'error' => 'Marked fields are required!',
        //         'tax' => 'TAX ID',
        //         'company_desc' => 'Additional information about the company',
        //         'consent' => '* fields marked with an asterisk are required',
        //         'from' => 'from',
        //         'to' => 'to',
        //     ]
        // ];

        $t = PWElementStepTwoExhibitor::get_translations();

        $output = '
            <style>
                .row-parent:has(.pwelement_'. self::$rnd_id .' #pweForm){
                    max-width: 100%;
                    padding: 0 !important;
                }
                .wpb_column:has(.pwelement_'. self::$rnd_id .' #pweForm) {
                    max-width: 100%;
                }
                .pwelement_'. self::$rnd_id .' #pweForm {
                    display: flex;
                    gap: 20px;
                }
                .pwelement_'. self::$rnd_id .' #pweForm>div{
                    align-content: center;
                    min-height: 643px;
                    width: 33%;
                    flex: 1;
                }
                .pwelement_'. self::$rnd_id .' .form {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    text-align: left;
                    padding: 36px;
                    background-color: #E8E8E8;
                    min-height: inherit;
                }
                .pwelement_'. self::$rnd_id .' .form h3{
                    color: '. self::$accent_color .';
                }
                .pwelement_'. self::$rnd_id .' .form form {
                    margin-top: 18px;
                }
                .pwelement_'. self::$rnd_id .' .form .gform_wrapper {
                    width: 100%
                }
                .pwelement_'. self::$rnd_id .' .form .gform_fields {
                    display: flex;
                    flex-direction: column;
                    // gap: 18px;
                    padding:0 !important;
                }
                .pwelement_'. self::$rnd_id .' .form form label{
                    text-align: left;
                    font-weight: 700;
                }
                .pwelement_'. self::$rnd_id .' .gform_wrapper :is(label, .gfield_description) {
                    color: black;
                }
                .pwelement_'. self::$rnd_id .' .form form :is(input:not([type="checkbox"]), textarea) {
                    margin-top: 4px !important;
                    margin-bottom:0px !important;
                    width: 100%;
                    border-radius: 10px;
                    box-shadow: none !important;
                }
                .pwelement_'. self::$rnd_id .' form :is(input, textarea){
                    border: 1px solid;
                    border-color: black !important;
                }
                .pwelement_'. self::$rnd_id .' form .gfield_required_asterisk{
                    // display: none !important;
                }
                .pwelement_'. self::$rnd_id .' .gform_required_legend {
                    display: none !important;
                }
                .pwelement_'. self::$rnd_id .' .gform_footer {
                    display: block !important;
                    text-align: center;
                    visibility: hidden !important;
                    width: 0;
                    height: 0;
                    padding: 0;
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' .form form {
                    margin-top:0 !important;
                }
                .pwelement_'. self::$rnd_id .' .gform_legacy_markup_wrapper {
                    margin-bottom:0;
                    margin-top:0;
                }
                .pwelement_'. self::$rnd_id .'  .gform_legacy_markup_wrapper .gform_footer {
                    padding: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' input[type=submit] {
                    background-color: '. $btn_color .' !important;
                    border: 2px solid '. $btn_color .' !important;
                    color: '. $btn_text_color .';
                    border-radius: 10px !important;
                    font-size: 1em;
                    margin: 0 auto 0;
                    align-self: center;
                    box-shadow: none !important;
                    font-size: 12px;
                    white-space: pre-wrap;
                }
                .pwelement_'. self::$rnd_id .' input[type=submit]:hover {
                    background-color: white !important;
                    color: '. $btn_color .' !important;
                    border: 2px solid '. $btn_color .' !important;
                }
                .pwelement_'. self::$rnd_id .' .form-left {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    text-align: -webkit-right;
                    padding: 36px;
                }
                .pwelement_'. self::$rnd_id .' .form-left > div {
                    text-align:left;
                    max-width: 450px;
                }
                .pwelement_'. self::$rnd_id .' .form-left span{
                    color: ' . $btn_color . ';
                }
                .pwelement_'. self::$rnd_id .' .form-right{
                    padding: 36px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 27px;
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .form-right .pwe-link{
                    color: white;
                    background-color: black;
                    border: 1px solid black;
                }
                .pwelement_'. self::$rnd_id .' .form-right .pwe-link:hover{
                    color: black;
                    background-color: white;
                }
                .pwelement_'. self::$rnd_id .' #pweForm:has(.gform_confirmation_wrapper) .display-before-submit {
                    display: none;
                }
                .pwelement_'. self::$rnd_id .' .display-before-submit {
                    margin-bottom:10px;
                }
                .pwelement_'. self::$rnd_id .' .display-after-submit{
                    display: none;
                }
                .pwelement_'. self::$rnd_id .' #pweForm:has(.gform_confirmation_wrapper) .display-after-submit{
                    display: block !important;
                }
                .pwelement_'. self::$rnd_id .' #pweForm .gform_confirmation_message {
                    font-size: 20px;
                    font-weight: 600;
                    text-align: center;
                }

                .pwelement_'. self::$rnd_id .' .pwe_reg_exhibitor {
                    margin-top: 18px;
                    color: white;
                    background-color: '. $btn_color .' !important;
                    border: 2px solid '. $btn_color .' !important;
                    border-radius: 10px !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe_reg_exhibitor:hover{
                    color: black;
                    background-color: white !important;
                    border: 2px solid '. $btn_color .' !important;
                }
                .pwelement_47852 .gfield_checkbox {
                    display: grid !important;
                    grid-template-columns: 1fr 1fr;
                    gap: 5px;
                }
                .pwelement_'. self::$rnd_id .' .gfield_checkbox .gchoice {
                    min-width:120px;
                }
                .pwelement_'. self::$rnd_id .' .gfield_checkbox input[type="checkbox"]  {
                    width: 16px !important;
                    height: 16px !important;
                    border-radius: 50% !important;
                }
                .pwelement_'. self::$rnd_id .' .gfield_checkbox label {
                    font-weight: 500 !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-submitting-buttons .pwe-btn {
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
                .pwelement_'. self::$rnd_id .'  .gform_wrapper  .gfield_consent_label {
                    font-size: 12px !important;
                }
                .pwelement_'. self::$rnd_id .' .gform_legacy_markup_wrapper ul li.gfield {
                    margin-top:5px !important;

                }
                .pwelement_'. self::$rnd_id .' .gform_wrapper .ginput_container_consent  :is(label, legend) {
                    font-weight: 500;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-wrapper {
                    margin-top:9px;
                    position: relative;
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
                .pwelement_'. self::$rnd_id .' input[type=tel] {
                    margin-bottom: 18px;
                    margin-top: 9px !important;
                }
                .pwelement_'. self::$rnd_id .' .form form .input-range-container input[type="range"] {
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
                    border:0px !important;
                    margin-bottom:18px !important;
                }
                .pwelement_'. self::$rnd_id .' .input-range-container .input-range-track {
                    width: 100%;
                    height: 5px;
                    position: absolute;
                    margin: auto;
                    top: -13px;
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
                    width: 100px !important;
                    padding-right: 35px !important;
                    height: 43px;
                }
                .pwelement_'. self::$rnd_id .' .input-range-values .unit-label {
                    position: absolute;
                    top: 20px;
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
                .pwelement_'. self::$rnd_id .' textarea {
                    padding: 9px 8px !important;
                    height: 45px  !important;
                }
                @media (min-width:650px) and (max-width:1080px){
                    .pwelement_'. self::$rnd_id .' .form-right {
                        display:none;
                    }
                }
                @media (max-width:650px){
                    .pwelement_'. self::$rnd_id .' #pweForm {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' #pweForm>div{
                        width: unset;
                        min-height: unset;
                    }
                    .pwelement_'. self::$rnd_id .' :is(h2,h3,h4,h5,p){
                        margin-top: 18px;
                    }
                    .pwelement_'. self::$rnd_id .' #pweForm .form-right .pwe-btn{
                        transform-origin: center;
                    }
                }
            </style>


            <div id="pweForm">
                <div class="form-left">
                    <div>'. self::multi_translation("thank_you_for_registering") .'
                    </div>
                </div>';

                $output .= '
                <div class="form">
                    <div class="display-before-submit">'. self::multi_translation("provide_additional_details") .'</div>';

                    if(($directUrl == "/zostan-wystawca/" || $directUrl == "/en/become-an-exhibitor/" || $directUrl == "/de/bestaetigung-der-ausstellerregistrierung/") && (strpos($registration_form_step2_exhibitor, "Potwierdzenie rejestracji wystawcy") === false)){
                        $output .= '
                        <div class="gf_browser_chrome gform_wrapper gravity-theme gform-theme--no-framework">
                            <form id="addressUpdateForm">
                                <div class="gform-body gform_body">
                                    <div class="gform_fields">
                                        <div class="gfield gfield--width-full">
                                            <label style="padding:0;" class="gfield_label gform-field-label">' . $t['name'] . ' *</label>
                                            <input type="text" id="name" placeholder="' . $t['name'] . '" required/>
                                        </div>
                                        <div style="margin-top:18px;" class="gfield gfield--width-full">
                                            <label style="padding:0;" class="gfield_label gform-field-label">' . $t['tax'] . ' *</label>
                                            <input type="text" id="nip" placeholder="' . $t['tax'] . '" />
                                        </div>
                                        <div style="margin-top:18px;" class="gfield gfield--width-full">
                                            <label style="padding:0;" class="gfield_label gform-field-label">' . $t['company_desc'] . '</label>
                                            <input type="text" id="company" placeholder="' . $t['company_desc'] . '" required/>
                                        </div>
                                        <div style="display:none !important;" class="input-area" >
                                            <label style="padding:0;" class="gfield_label gform-field-label">' . $t['area'] . '</label>
                                            <input type="text" id="area" placeholder="' . $t['area'] . '" required/>

                                        </div>
                                        <div style="margin-top:18px;" id="statusMessage" class="status-message"></div>
                                    </div>
                                </div>
                                <p style="font-weight: 500; margin-top: 5px; font-size: 12px;">'. $t['consent'] .'</p>
                            </form>
                        </div>';
                    } else {
                        $output .= '[gravityform id="'. $form_to_update .'" title="false" description="false" ajax="false"]';
                    }
                    if($directUrl == "/zostan-wystawca/" || $directUrl == "/en/become-an-exhibitor/" || $directUrl == "/de/bestaetigung-der-ausstellerregistrierung/"){
                        $output .= '
                        <input style="margin-top:20px;" type="submit" id="pweConfirmation" class="display-before-submit" value="'. $confirmation_button_text .'" onclick="updateGravityForm()">';
                    } else {
                        $output .= '
                        <input type="submit" id="pweConfirmation" class="display-before-submit" value="'. $confirmation_button_text .'">';
                    }
                    $output .= '
                    <div class="pwe-submitting-buttons display-after-submit">
                        <a href="'. self::multi_translation("back_link") .'"><button class="btn pwe-btn pwe_reg_exhibitor">'. $main_page_text_btn .'</button></a>
                    </div>';
                $output .= '
                </div>';

                $output .= '
                <div class="form-right">
                    <img class="img-stand" src="/wp-content/plugins/pwe-media/media/zabudowa.webp" alt="zdjęcie przykładowej zabudowy"/>
                    <h5>'. self::multi_translation("dedicated_market_place") .'</h5>
                        <a class="pwe-link btn pwe-btn btn-stand" target="_blank" '. self::multi_translation("see_the_offer") .'</a>
                </div>
            </div>
        ';
        $output .= '

        ';
        if (($directUrl == "/zostan-wystawca/" || $directUrl == "/en/become-an-exhibitor/" || $directUrl == "/de/bestaetigung-der-ausstellerregistrierung/") && (strpos($registration_form_step2_exhibitor, "Potwierdzenie rejestracji wystawcy") === false)){
            $output .= '
            <script>
                const formEmail = document.querySelector(".input-area");

                function updateGravityForm() {
                    const fields = ["name", "area", "nip"];
                    let hasError = false;
                    let firstErrorField = null;

                    fields.forEach(id => {
                        const field = document.getElementById(id);
                        if (!field.value.trim()) {
                            field.classList.add("error-border");
                            if (!firstErrorField) {
                                firstErrorField = field;
                            }
                            hasError = true;
                        } else {
                            field.classList.remove("error-border");
                        }
                    });

                    if (hasError) {
                        document.getElementById("statusMessage").innerText = "'.$t['error'].'";
                        document.getElementById("statusMessage").classList.add("error");
                        firstErrorField.focus();
                        return;
                    }

                    const name = document.getElementById("name").value.trim();
                    const area = document.getElementById("area").value.trim();
                    const company = document.getElementById("company").value.trim();
                    const nip = document.getElementById("nip").value.trim();
                    const statusMessage = document.getElementById("statusMessage");
                    const formName = "'.$form_to_update.'";
                    const direction = "exhibitor";

                    if (!name || !area || !nip) {
                        statusMessage.innerText = "' . $t['error'] . '";
                        statusMessage.classList.add("error");
                        return;
                    }

                    const formData = { name, area, company, nip, formName,  direction };

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
                            statusMessage.appendChild(confirmationWrapper);

                            const messageContainer = document.querySelector("#pweForm .form");

                            const message = document.createElement("div");
                            message.classList.add("gform_confirmation_message");
                            message.innerText = "' . $t['confirm_text'] . '";


                            messageContainer.insertBefore(message, messageContainer.firstChild);
                        } else {
                            statusMessage.innerText = "Wystąpił błąd: " + data.message;
                            statusMessage.classList.add("error");
                        }
                    })
                    .catch(error => {
                        console.error("Błąd:", error);
                        statusMessage.innerText = "Wystąpił problem z aktualizacją.";
                        statusMessage.classList.add("error");
                    });
                }
            </script>';
        } else {
            $output .= '
            <script>
                const form = document.querySelector(".pwelement_'. self::$rnd_id .' form");
                const formEmail = form.querySelector(".input-area");

                document.addEventListener("DOMContentLoaded", function() {
                    let gformFields = document.querySelector(".gform_fields");
                    if (gformFields) {
                        let newParagraph = document.createElement("p");
                        newParagraph.style.fontWeight = "500";
                        newParagraph.style.marginTop = "5px";
                        newParagraph.style.fontSize = "12px";
                        newParagraph.innerHTML = "'. $t['consent'] .'";

                        gformFields.appendChild(newParagraph);
                    }


                    jQuery(document).ready(function($){

                        let userGroup = "'. $current_group .'";

                        document.querySelectorAll("label.gfield_label").forEach(label => {

                            if (label.textContent.trim().toLowerCase() === "patron") {

                                const inputId = label.getAttribute("for");
                                const input = document.getElementById(inputId);

                                if (input) {
                                input.value = userGroup;
                                }
                            }
                        });

                        const buttonSubmit = document.querySelector(".pwelement_'. self::$rnd_id .' .gform_footer input[type=submit]");
                        let userArea = localStorage.getItem("user_area");

                        if (userArea && userArea.trim() !== "") {
                            $(".con-area").hide();
                        }

                        $(".pwelement_'. self::$rnd_id .' #pweConfirmation").on("click", function() {
                            let userEmail = "'. $userSessionEmail .'" || localStorage.getItem("user_email");
                            let userTel = "'. $userSessionPhone .'" || localStorage.getItem("user_tel");
                            let userDirection = localStorage.getItem("user_direction");

                            if (userGroup) {
                                $(".pwelement_'. self::$rnd_id .' .ginput_container_email").find("input").val(userGroup);
                            }

                            if (userEmail) {
                                $(".pwelement_'. self::$rnd_id .' .ginput_container_email").find("input").val(userEmail);
                            }
                            if (userTel) {
                                $(".pwelement_'. self::$rnd_id .' .ginput_container_phone").find("input").val(userTel);
                            }
                            if (userArea) {
                                $(".pwelement_'. self::$rnd_id .' .input-area").find("input").val(userArea);
                            }

                            buttonSubmit.click();
                        });


                    });
                });

            </script>';

        }

        if(strpos($registration_form_step2_exhibitor, "Potwierdzenie rejestracji wystawcy") === false){
        $output .= '
            <script>
                const sliderContainer = document.createElement("div");
                sliderContainer.className = "input-range-container";
                sliderContainer.innerHTML = `
                    <div class="input-range-wrapper">
                        <p style="font-size:14px; font-weight:700; margin-top:18px;">'. self::multi_translation("exhibition_space") .'</p>
                        <div class="input-range-inputs">
                            <div class="input-range-track"></div>
                            <input type="range" min="16" max="100" value="16"  id="inputRange1" oninput="slideOne()">
                            <input type="range" min="16" max="100" value="36" id="inputRange2" oninput="slideTwo()">
                        </div>
                        <div class="input-range-values">
                            <span class="input-range-value-label">'.$t['from'].'</span>
                            <div class="input-container">
                                <input type="number" min="0" max="999" value="16" id="inputRangeValue1" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3)">
                                <span class="unit-label">m²</span>
                            </div>
                            <span class="input-range-value-label">'.$t['to'].'</span>
                            <div class="input-container">
                                <input type="number" min="0" max="999" value="36" id="inputRangeValue2" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3)">
                                <span class="unit-label">m²</span>
                            </div>
                        </div>
                    </div>
                `;


                formEmail.insertAdjacentElement("afterend", sliderContainer);

                function updateArea() {
                    areaInput.value = minValue.value + " - " + maxValue.value + " m²";
                }

                document.addEventListener("DOMContentLoaded", function () {
                    slideOne();
                    slideTwo();
                    fillColor();
                });

                let sliderOne = document.getElementById("inputRange1");
                let sliderTwo = document.getElementById("inputRange2");
                let displayValOne = document.getElementById("inputRangeValue1");
                let displayValTwo = document.getElementById("inputRangeValue2");
                let minGap = 1;
                let sliderTrack = document.querySelector(".input-range-track");
                let sliderMaxValue = parseInt(sliderOne.max);
                let sliderMinValue = parseInt(sliderOne.min);

                function slideOne() {
                    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) < minGap) {
                        sliderOne.value = parseInt(sliderTwo.value) - minGap;
                    }
                    displayValOne.value = sliderOne.value;
                    fillColor();
                    updateArea();
                }

                function slideTwo() {
                    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) < minGap) {
                        sliderTwo.value = parseInt(sliderOne.value) + minGap;
                    }
                    displayValTwo.value = sliderTwo.value;
                    fillColor();
                    updateArea();
                }

                function fillColor() {
                    let percent1 = ((sliderOne.value - sliderMinValue) / (sliderMaxValue - sliderMinValue)) * 100;
                    let percent2 = ((sliderTwo.value - sliderMinValue) / (sliderMaxValue - sliderMinValue)) * 100;
                    sliderTrack.style.background = `linear-gradient(to right, #dadae5 ${percent1}%, #007bff ${percent1}%, #007bff ${percent2}%, #dadae5 ${percent2}%)`;
                }

                function updateArea() {
                    const areaContainer = document.getElementsByClassName("input-area")[0];
                    areaContainer.style = "display:none !important;";
                    if (areaContainer) {
                        const areaInput = areaContainer.getElementsByTagName("input")[0];
                        if (areaInput) {
                            areaInput.value = displayValOne.value + " - " + displayValTwo.value + " m²";
                        }
                    }
                }

                displayValOne.addEventListener("input", function () {
                    let val = parseInt(displayValOne.value);
                    if (val < sliderMinValue) val = sliderMinValue;
                    if (val > parseInt(sliderTwo.value) - minGap) val = parseInt(sliderTwo.value) - minGap;
                    sliderOne.value = val;
                    slideOne();
                });

                displayValTwo.addEventListener("input", function () {
                    let val = parseInt(displayValTwo.value);
                    if (val > sliderMaxValue) val = sliderMaxValue;
                    if (val < parseInt(sliderOne.value) + minGap) val = parseInt(sliderOne.value) + minGap;
                    sliderTwo.value = val;
                    slideTwo();
                });

                function preventTyping(event) {
                    if (event.key !== "ArrowUp" && event.key !== "ArrowDown" && event.key !== "Tab") {
                        event.preventDefault();
                    }
                }

                displayValOne.addEventListener("keydown", preventTyping);
                displayValTwo.addEventListener("keydown", preventTyping);

            </script>';}
        return $output;
    }
}