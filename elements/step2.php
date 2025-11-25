<?php

/**
 * Class PWElementStepTwo
 * Extends PWElements class and defines a pwe Visual Composer element for x-steps-form.
 */
class PWElementStepTwo extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
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
                'param_name' => 'reg_form_name_step2',
                'save_always' => true,
                'value' => array_merge(
                    array('Wybierz' => ''),
                    self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwo',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Link exhibitor confirmation', 'pwelement'),
                'param_name' => 'step2_link_exhibitor_no',
                'description' => __('Default(pl:"/potwierdzenie-rejestracji/"; en:"/en/registration-confirmation/")', 'pwelement'),
                'save_always' => true,
                'value' => '',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwo',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Fair Logo', 'pwelement'),
                'param_name' => 'fair_logo_bottom',
                'description' => __('Fair logo for bottom info bar.', 'pwelement'),
                'save_always' => true,
                'value' => '',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwo',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'PWE Element',
                'heading' => __('Overlay color', 'pwe_header'),
                'param_name' => 'step2_overlay_color',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwo',
                ),
            ),
            array(
                'type' => 'input_range',
                'group' => 'PWE Element',
                'heading' => __('Overlay opacity', 'pwe_header'),
                'param_name' => 'step2_overlay_range',
                'value' => '0',
                'min' => '0',
                'max' => '1',
                'step' => '0.01',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwo',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('No forms', 'pwe_header'),
                'param_name' => 'step2_no_forms',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStepTwo',
                ),
            ),
        );
        return $element_output;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/step2.json';

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
     * Static method to display seccond step form (step2).
     * Returns the HTML output as a string.
     */
    public static function output($atts, $content = ''){
        extract( shortcode_atts( array(
            'reg_form_name_step2' => '',
            'step2_overlay_color' => '',
            'step2_overlay_range' => '',
            'step2_no_forms'      => '',
        ), $atts ));

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userSessionEmail = $_SESSION["pwe_reg_entry"]["email"]  ?? null;
        $userSessionPhone = $_SESSION["pwe_reg_entry"]["phone"] ?? null;

        $file_url = plugins_url('elements/session_fetch.php', dirname(__FILE__));

        $fair_logo = (get_locale() == "pl_PL") ? "/doc/logo-color.webp" : "/doc/logo-color-en.webp";

        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';
        $btn_text_color = 'color:' . self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important; border-width: 0 !important;';
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important;';
        $btn_shadow_color = 'box-shadow: 9px 9px 0px -5px ' . self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black') . '!important;';
        $btn_border = 'border: 1px solid ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important;';

        if(get_locale() == "pl_PL") {
            $step2_link_exhibitor_no = (empty($atts['step2_link_exhibitor_no'])) ? '/potwierdzenie-rejestracji/' : $atts['step2_link_exhibitor_no'];
        } else {
            $step2_link_exhibitor_no = (empty($atts['step2_link_exhibitor_no'])) ? '/en/registration-confirmation/' : $atts['step2_link_exhibitor_no'];
        }

        $output = '
            <style>
                .row-container:has(#Step2 .gform_wrapper) .row-parent {
                    padding: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' #Step2{
                    min-height: 700px;
                    display: flex;
                    align-items: center;
                }
                .pwelement_'. self::$rnd_id .' :is(.form-2, .form-2-right) {
                    width:50%;
                    padding: 9px 18px;
                }

                .pwelement_'. self::$rnd_id .' .form-2 span {
                    color: ' . $text_color . '
                }


                .pwelement_'. self::$rnd_id .' .form-2>div {
                    max-width: 500px;
                    text-align: left;
                    margin: auto;
                }
                .pwelement_'. self::$rnd_id .' .form-2-right {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    gap: 10px;
                    background-image: url(/doc/background.webp);
                    background-color: black;
                    background-size: cover;
                    background-position: center;
                    width:50%;
                    min-height: inherit;
                    padding: 36px;
                    z-index: 0;
                }
                .pwelement_'. self::$rnd_id .' .form-2-right:before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: '. $step2_overlay_color .';
                    opacity: '. $step2_overlay_range .';
                    z-index: -1;
                }
                .pwelement_'. self::$rnd_id .' .form-2 .pwe-submitting-buttons {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    margin-top: 36px;
                    gap: 18px;
                }
                .pwelement_'. self::$rnd_id .' .form-2 .pwe-submitting-buttons .exhibitor-yes{
                    color: white;
                    '. $btn_color .
                    $btn_border.'
                    border-radius: 10px !important;
                }
                .pwelement_'. self::$rnd_id .' .form-2 .pwe-submitting-buttons .exhibitor-yes:hover{
                    color: black;
                    background-color: white !important;
                    '. $btn_border .'
                }
                .pwelement_'. self::$rnd_id .' .form-2 .pwe-submitting-buttons .exhibitor-no{
                    color: black;
                    background-color: white !important;
                    '. $btn_border .'
                    border-radius: 10px !important;
                }
                .pwelement_'. self::$rnd_id .' .form-2 .pwe-submitting-buttons .exhibitor-no:hover{
                    color: white;
                    '. $btn_color .
                    $btn_border.'
                }
                .pwelement_'. self::$rnd_id .' .form-2 .wystawca{
                    margin-top:72px;
                }
                .pwelement_'. self::$rnd_id .' .form-2 .font13{
                    font-size: 13px;
                }
                .pwelement_'. self::$rnd_id .' .form-2-right img{
                    max-width: 350px;
                    max-height: 200px;
                    width: 100%;
                }
                .pwelement_'. self::$rnd_id .' .form-2-right :is(h4, h6) {
                    text-shadow: 0 0 2px black;
                    color: white !important;
                    margin-top: 0px;
                }
                .pwelement_'. self::$rnd_id .' .form-2-bottom{
                    background-color: #f7f7f7;
                    display: flex;
                    justify-content: center;
                    gap: 18px;
                    flex-wrap: wrap;
                    padding: 18px;
                }
                .pwelement_'. self::$rnd_id .' .form-2-bottom div{
                    flex:1;
                    display: flex;
                    justify-content: center;
                    flex-wrap: wrap;
                    gap:9px;
                }
                .pwelement_'. self::$rnd_id .' .form-2-bottom div>div{
                    flex:1;
                    min-width: 200px;
                }
                .pwelement_'. self::$rnd_id .' .form-2-bottom img{
                    max-height: 80px;
                }
                .pwelement_'. self::$rnd_id .' .form-2-bottom :is(.for-exhibitors, .for-visitors){
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .pwelement_'. self::$rnd_id .' .form-2-bottom :is(.for-exhibitors, .for-visitors) p{
                    margin-top: 0px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gravity-form {
                    visibility:hidden;
                    height: 0;
                    margin: 0;
                    padding: 0;
                }
                .pwelement_'. self::$rnd_id .' img {
                    object-fit: contain;
                }
                @media (max-width:650px){
                    .pwelement_'. self::$rnd_id .' .form-2 .wystawca{
                        margin-top:36px;
                    }
                }
                @media (min-width:570px) and (max-width:959px){
                    .vc_row .full-width:has(.pwelement_'. self::$rnd_id .') .wpb_column{
                        max-width: unset !important;
                    }
                    .pwelement_'. self::$rnd_id .' #Step2{
                        min-height: 600px;
                    }
                    .pwelement_'. self::$rnd_id .' .form-2>div {
                        width: unset;
                        padding: 18px;
                    }
                }
                @media (max-width:700px){
                    .pwelement_'. self::$rnd_id .' #Step2{
                        min-height: unset;
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' :is(.form-2, .form-2-right){
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .form-2>div {
                        width: unset;
                    }
                    .pwelement_'. self::$rnd_id .' .form-2-bottom div>div{
                        min-width: unset;
                    }
                    .pwelement_'. self::$rnd_id .' .form-2 .pwe-submitting-buttons {
                        flex-wrap: wrap;
                        justify-content: center;
                        transform: none !important;
                    }
                    .pwelement_'. self::$rnd_id .' .form-2 .pwe-submitting-buttons button {
                        transform: scale(1) !important;
                    }
                    .pwelement_'. self::$rnd_id .' .numbers p {
                        text-align: center;
                    }
                    .pwelement_'. self::$rnd_id .' .form-2-bottom div{
                        flex: unset;
                        width: 100%;
                    }
                }
            </style>
            <div id="Step2">
                <div class="form-2">
                    <div>
                        <h5 class="krok">
                        '. self::multi_translation("step") .'
                        </span></h5>
                        <h2 class="text-color-jevc-color">'. self::multi_translation("your_ticket") .'</h2>
                        <p class="font13">'. self::multi_translation("receive_e-mail") .'</p>
                        <h3 class="wystawca">'. self::multi_translation("become_exhibitor") .'
                        </h3>';
                        if(!$step2_no_forms){
                            $output .= '
                                <div class="pwe-gravity-form">
                                    [gravityform id="'. $reg_form_name_step2 .'" title="false" description="false" ajax="false"]
                                </div>

                                <div class="pwe-submitting-buttons">
                                    <button type="submit" class="btn exhibitor-yes" name="exhibitor-yes">
                                    '. self::multi_translation("i_am_interested") .'
                                    </button>';
                        } else {
                            $output .= '<div class="pwe-submitting-buttons">
                                    <a href="'. self::multi_translation("confirmation_link") .'">
                                        <button type="submit" class="btn exhibitor-yes" name="exhibitor-yes">'. self::multi_translation("confirmation_text") .'</button>
                                    </a>';
                        }


                            $trade_fair_date = do_shortcode('[trade_fair_enddata]');

                            $trade_fair_timestamp = strtotime($trade_fair_date);
                            $current_timestamp = time();

                            $days_difference = ($trade_fair_timestamp - $current_timestamp) / (60 * 60 * 24);

                            if ((($trade_fair_timestamp == false || empty($trade_fair_date)) || $days_difference > 17 || $trade_fair_timestamp < $current_timestamp) && get_locale() == "pl_PL") {
                                $output .= '
                                <a href="'. $step2_link_exhibitor_no .'">
                                    <button type="submit" class="btn exhibitor-no" name="exhibitor-no">'. self::multi_translation("no_thank_you") .'</button>
                                </a>';
                            } else {
                                $output .= '
                                <a href="'. self::multi_translation("back_link") .'">
                                    <button type="submit" class="btn exhibitor-no" name="exhibitor-no">'. self::multi_translation("no_thank_you") .'</button>
                                </a>';
                            }
                            $output .= '
                        </div>
                    </div>
                </div>
                <div class="form-2-right">';

                if(get_locale() == "pl_PL"){
                    $output .= '<img src="/doc/logo.webp">
                                <h4>[trade_fair_date]</h4>';
                } else {
                    $output .= '<img src="/doc/logo-en.webp">
                                <h4>[trade_fair_date_multilang]</h4>';
                };

                    $output .= '<h6>w Ptak Warsaw Expo</h6>
                </div>
            </div>
            <div class="form-2-bottom">
                <div class="logos">
                    <div class="pwe-logo">
                        <a href="https://warsawexpo.eu/" target="_blanc"><img src="' . plugin_dir_url(dirname( __FILE__ )) . "/media/logo_pwe_black.webp" . '"></a>
                    </div>
                    <div class="fair-logo">
                        <a href="'. self::multi_translation("back_link") .'"><img src="' . $fair_logo . '"></a>
                    </div>
                </div>
                <div class="numbers">
                    <div class="for-exhibitors">
                        <i class="fa fa-envelope-o fa-3x fa-fw"></i>
                        <p>'. self::multi_translation("become_an_exhibitor") .'<br> <a href="tel:48 517 121 906">+48 517 121 906</a>
                    </div>
                    <div class="for-visitors">
                        <i class="fa fa-phone fa-3x fa-fw"></i>
                        <p>'. self::multi_translation("visitors") .'<br> <a href="tel:48 513 903 628">+48 513 903 628</a>
                    </div>
                </div>
            </div>
            <script>
                    jQuery(document).ready(function($){

                        // $(".pwelement_'. self::$rnd_id .' .exhibitor-no").on("click", function(event){
                        //     if(!localStorage.getItem("user_email")){
                        //         event.preventDefault();
                        //         if($("html").attr("lang") == "pl-PL"){
                        //             window.location.href = "/rejestracja/";
                        //         } else {
                        //             window.location.href = "/en/registration/";
                        //         }
                        //         return;
                        //     }
                        // });

                        $(".pwelement_'. self::$rnd_id .' .exhibitor-yes").on("click", function(event){

                            // if(!localStorage.getItem("user_email")){
                            //     event.preventDefault();
                            //     if($("html").attr("lang") == "pl-PL"){
                            //         window.location.href = "/zostan-wystawca/";
                            //     } else {
                            //         window.location.href = "/en/become-an-exhibitor/";
                            //     }
                            //     return;
                            // }

                            // jQuery.ajax({
                            //     url: "'.$file_url.'",
                            //     method: "GET",
                            //     dataType: "json",
                            //     success: function (sessionData) {
                            //         let userEmail = sessionData.user_email ;
                            //         let userPhone = sessionData.user_phone ;

                            //         jQuery(".pwelement_'. self::$rnd_id .' .ginput_container_email").find("input").val(userEmail);
                            //         jQuery(".pwelement_'. self::$rnd_id .' .ginput_container_phone").find("input").val(userPhone);
                            //         jQuery(".pwelement_'. self::$rnd_id .' .gfield--type-consent").find("input").click();
                            //         jQuery(".pwelement_'. self::$rnd_id .' form").submit();

                            //     },
                            //     error: function () {
                            //         console.log("error");
                            //     }
                            // });
                            jQuery.ajax({
                                url: "'.$file_url.'",
                                method: "GET",
                                dataType: "json",
                                success: function (sessionData) {
                                    let userEmail = sessionData.user_email || localStorage.getItem("user_email");
                                    let userPhone = sessionData.user_phone || localStorage.getItem("user_tel");

                                    jQuery(".pwelement_'. self::$rnd_id .' .ginput_container_email").find("input").val(userEmail);
                                    jQuery(".pwelement_'. self::$rnd_id .' .ginput_container_phone").find("input").val(userPhone);
                                    jQuery(".pwelement_'. self::$rnd_id .' .gfield--type-consent").find("input").click();
                                    jQuery(".pwelement_'. self::$rnd_id .' form").submit();
                                },
                                error: function () {
                                    let userEmail = localStorage.getItem("user_email");
                                    let userPhone = localStorage.getItem("user_tel");

                                    jQuery(".pwelement_'. self::$rnd_id .' .ginput_container_email").find("input").val(userEmail);
                                    jQuery(".pwelement_'. self::$rnd_id .' .ginput_container_phone").find("input").val(userPhone);
                                    jQuery(".pwelement_'. self::$rnd_id .' .gfield--type-consent").find("input").click();
                                    jQuery(".pwelement_'. self::$rnd_id .' form").submit();
                                }
                            });
                        });
                    });

            </script>
        ';

        return $output;
    }
}