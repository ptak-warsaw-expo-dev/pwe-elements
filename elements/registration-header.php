<?php

/**
 * Class PWElementRegHeader
 * Extends PWElements class and defines a custom Visual Composer element.
 */
class PWElementRegHeader extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function header_additional_css() { 
        $registration_css = '
                <style>
                    #pweForm {
                        max-width: 450px;
                    }
                </style>';
        return $registration_css;
    }

    public static function visitors_additional_css() {
        $registration_css = '
                <style>
                    .row-parent:has(#pweForm) {
                        padding-top: 0 !important;
                    }
                    .row-inner:has(#pweForm) {
                        height: inherit !important;
                    }
                    .wpb_column:has(#top10) {
                        padding-top: 100px;
                    }
                    #pweForm {
                        max-width: 555px;
                    }
                    #pweForm .gform_footer {
                        padding-top: 18px !important;
                    }
                    @media (max-width:960px) {
                        .wpb_column:has(#top10) {
                            padding-top: 18px !important;
                        }
                    }
                </style>';
        return $registration_css;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($registration_form_id, $registration_modes, $registration_logo, $actually_date, $registration_name = "") {

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        if ($registration_modes == "conference_mode") {
            $main_badge_color = self::$accent_color;
        } else {
            $main_badge_color = self::$main2_color;
        }

        $darker_btn_color = self::adjustBrightness($main_badge_color, -20);

        $trade_fair_edition_shortcode = do_shortcode('[trade_fair_edition]');
        if (strpos($trade_fair_edition_shortcode, '.') !== false) {
            $trade_fair_edition_text = (get_locale() == 'pl_PL') ? " edycja" : " edition";
        } else {
            $trade_fair_edition_text = (get_locale() == 'pl_PL') ? ". edycja" : ". edition";
        }
        $trade_fair_edition_first = (get_locale() == 'pl_PL') ? "Premierowa Edycja" : "Premier Edition";
        $trade_fair_edition = (!is_numeric($trade_fair_edition_shortcode) || $trade_fair_edition_shortcode == 1) ? $trade_fair_edition_first : $trade_fair_edition_shortcode . $trade_fair_edition_text;
 
        if (isset($_SERVER['argv'][0])) {
            $source_utm = $_SERVER['argv'][0];
        } else {
            $source_utm = ''; 
        }

        $output = '
            <style>
                #pweForm {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    overflow: hidden;
                }
                #pweForm .form-container {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                    padding: 0 0 36px;
                    background: #e8e8e8;
                    overflow: hidden;
                    border-radius: 0 0 24px 24px;
                }
                #pweForm .form-badge-header {
                    background-color: '. $main_badge_color .';
                    width: 100%;
                    height: 80px;
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 36px;
                }
                #pweForm .form-badge-header .form-header-title {
                    font-size: 26px;
                    font-weight: 700;
                    margin: 0;
                    color: white;
                    display: none;
                }
                #pweForm .form-badge-header .form-header-image-qr {
                    width: 60px;
                    height: 60px;
                    aspect-ratio: 1/1;
                    object-fit: contain;
                    border-radius: 10px;
                    display: none;
                }
                #pweForm .form-badge-top {
                    position: relative;
                    width: 100%;
                }
                #pweForm .form-badge-right {
                    position: absolute;
                    right: 0;
                    top: 0;
                    bottom: 0;
                    width: 25px;
                }
                #pweForm .form-badge-bottom {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    z-index: 1;
                    height: 25px;
                    width: 100%;
                }
                #pweForm .form-badge-left {
                    position: absolute;
                    left: 0;
                    top: 0;
                    bottom: 0;
                    width: 25px;
                }
                #pweForm .form-image-qr {
                    position: absolute;
                    right: 36px;
                    top: 36px;
                    width: 100px;
                    object-fit: cover;
                    border-radius: 10px;
                    z-index: 1;
                }
                #pweForm .form {
                    width: 90%;
                    height: 100%;
                    padding: 36px 36px 18px;
                }
                #pweForm .form .form-title {
                    margin: 0;
                    font-size: 32px;
                    font-weight: 700;
                }
                #pweForm .form form {
                    display: flex;
                    flex-direction: column;
                }
                #pweForm input {
                    border: 1px solid #ACACAC !important;
                    border-radius: 10px;
                    box-shadow: none !important;
                }
                #pweForm .iti--allow-dropdown {
                    margin-top: 18px;
                }
                #pweForm .iti__country-list {
                    list-style: none;
                    padding: 0;
                }
                #pweForm input:not([type=checkbox]) {
                    margin: 0 auto 0;
                }
                #pweForm .gfield_consent_description {
                    overflow: unset;
                }
                #pweForm .gfield--type-consent{
                    overflow: hidden !important;
                }
                #pweForm .gfield--type-consent span {
                    display: inline !important;
                }
                #pweForm .gform_wrapper :is(label, .gfield_description),
                #pweForm .gform_legacy_markup_wrapper .gfield_required {
                    font-size:12px;
                    line-height: 15px;
                    color: black !important;
                }
                #pweForm .gform_legacy_markup_wrapper .gform_footer {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 0 auto !important;
                    padding: 0;
                    text-align: center;
                }
                // #pweForm input[type=submit].gform_button {
                //     visibility: hidden !important;
                //     width: 0;
                //     height: 0;
                //     padding: 0;
                //     margin: 0;
                // }
                #pweForm #pweRegister {
                    margin: 0;
                }
                #pweForm .pwe-btn-container {
                    margin: 0 !important;
                    padding: 0 !important;
                }
                #pweForm input[type=submit],
                #pweForm .pwe-btn {
                    background-color: '. $main_badge_color .' !important;
                    border-width: 1px !important;
                    border-radius: 10px !important;
                    border: 2px solid '. $main_badge_color .' !important;
                    font-size: 14px;
                    color: white;
                    align-self: center;
                    transform: scale(1) !important;
                }
                #pweForm input[type=submit]:hover,
                #pweForm .pwe-btn:hover {
                    background-color: '. $darker_btn_color .';
                    color: white;
                    border: 2px solid '. $main_badge_color .';
                }
                #pweForm .mail-error, #pweForm .tel-error, #pweForm .cons-error{
                    margin: 0 11px;
                    width:85%;
                }
                #pweForm .show-consent {
                    color: black !important;
                }
                #pweForm form :is(.email-error, .phone-error, .cons-error) {
                    font-size: 12px;
                    color: red;
                    width: 90%;
                    margin-top: 0px;
                    text-transform: uppercase;
                    background-color: rgba(255, 223, 224);
                }
                #pweForm .gform_validation_errors {
                    border: none;
                    margin: 0;
                    padding: 18px 0 0;
                }
                #pweForm .validation_message {
                    padding: 0;
                }
                #pweForm .gfield {
                    padding: 0;
                }
                #pweForm .gfield_error {
                    border: none;
                }
                #pweForm .gfield_label {
                    font-size: 14px !important;
                }
                #pweForm input[type="checkbox"]  {
                    min-width: 16px !important;
                    height: 16px !important;
                    border-radius: 50% !important;
                }
                #pweForm .form-required::after {
                    content: "" !important;
                }
                #pweForm .gfield_required_asterisk {
                    display: none !important;
                }
                #pweForm .iti--allow-dropdown {
                    margin-top: 0;
                }
                @media (max-width:960px) {
                    #pweForm {
                        padding-bottom: 0;
                    }
                    #pweForm .form {
                        padding: 0 18px 0;
                    }
                    #pweForm form {
                        padding: 0;
                    }
                    #pweForm .form-image-qr,
                    #pweForm .form-title {
                        display: none;
                    }
                    #pweForm .form-badge-header .form-header-title,
                    #pweForm .form-badge-header .form-header-image-qr {
                        display: flex;
                        align-items: center;
                    }
                }
                @media (max-width:450px){
                    #pweForm form {
                        width: 100%;
                    }
                    #pweForm .form h2 {
                        margin-top: 36px;
                        font-size: 24px;
                    }
                    #pweForm .form-image-qr {
                        top: 20px;
                        width: 80px;
                    }
                    #pweForm .consent-container {
                        margin-top: 18px;
                    }
                    #pweForm .pwe-btn {
                        padding: 12px 16px !important;
                        font-size: 12px;
                    }
                    #pweForm input[type=submit] {
                        font-size: 12px;
                        padding: 0 !important;
                    }
                    #pweForm input:not([type=checkbox]) {
                        width: 100%;
                    }
                }
            </style>';

            if ($registration_name == "header") {
                $output .= self::header_additional_css();
            } else if ($registration_name == "visitors") {
                $output .= self::visitors_additional_css();
            }

            if ($registration_modes == "conference_mode") {
                $output .= '
                <style>
                    @media (max-width:960px){
                        #pweForm .form-badge-header .form-header-title {
                            font-size: 22px;
                        }
                    }
                    @media (max-width: 380px){
                        #pweForm .form-badge-header {
                            height: 90px;
                        }
                        #pweForm .form-badge-header .form-header-title {
                            font-size: 18px;
                        }
                        #pweForm .form-badge-header .form-header-image-qr {
                            width: 75px;
                            height: 75px;
                        }
                    }
                </style>';

                $registration_forn_title = get_locale() == 'pl_PL' ? 'Twój bilet na<br>konferencje i targi' : 'Your ticket to<br>conferences and<br>trade fairs' ;
            } else {
                $registration_forn_title = get_locale() == 'pl_PL' ? 'Twój bilet<br>na targi' : 'Your ticket<br>to the fair' ;
            }

            $output .='
            <div id="pweForm">
                <img class="form-badge-top" src="/wp-content/plugins/pwe-media/media/badge_top.png">
                <div class="form-container pwe-registration">
                    <div class="form-badge-header">
                        <h2 class="form-header-title">'. $registration_forn_title .'</h2>
                        <a href="https://warsawexpo.eu/" target="_blank"><img class="form-header-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp"></a>
                    </div>
                    <img class="form-badge-left" src="/wp-content/plugins/pwe-media/media/badge_left.png">
                    <img class="form-badge-bottom" src="/wp-content/plugins/pwe-media/media/badge_bottom.png">
                    <img class="form-badge-right" src="/wp-content/plugins/pwe-media/media/badge_right.png">
                    <a href="https://warsawexpo.eu/" target="_blank"><img class="form-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp"></a>
                    <div class="form">
                        <h2 class="form-title">'. $registration_forn_title .'</h2>
                        <div class="pwe-registration-form">
                            [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                        </div>
                    </div>
                </div>
            </div>

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

                    if (utmCookie && (utmCookie.includes("utm_source=byli") || utmCookie.includes("utm_source=premium"))) {
                        deleteCookie("utm_params");
                    }

                    const buttonSubmit = document.querySelector(".gform_footer input[type=submit]");

                    if (buttonSubmit) {
                        buttonSubmit.addEventListener("click", function (event) {
                            event.preventDefault();

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

                            localStorage.setItem("user_country", countryValue);
                            localStorage.setItem("user_email", emailValue);
                            localStorage.setItem("user_tel", telValue);';

                            if ($registration_modes == "registration_mode") {
                                if (get_locale() == 'pl_PL') {
                                    $output .= 'localStorage.setItem("user_direction", "rejpl");';
                                } else {
                                    $output .= 'localStorage.setItem("user_direction", "rejen");';
                                }
                            } else if ($registration_modes == "conference_mode") {
                                $output .= 'localStorage.setItem("user_direction", "kongres");';
                            }

                            $output .= '
                            
                        });
                    }
                }
            </script>
        ';

        return $output;
    }
}