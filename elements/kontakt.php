<?php
/**
* Class PWElementContact
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementContact extends PWElements {

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
            $element_output[] =
                array(
                    'type' => 'checkbox',
                    'group' => 'PWE Element',
                    'heading' => __('Horizontal display', 'pwelement'),
                    'param_name' => 'horizontal',
                    'value' => '',
                    'dependency' => array(
                        'element' => 'pwe_element',
                        'value' => 'PWElementContact',
                    ),
                );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    *
    * @return string @output
    */
    public static function output($atts) {
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';

        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $pwe_groups_contacts_data = PWECommonFunctions::get_database_groups_contacts_data();

        $source_utm = (isset($_SERVER['argv'][0])) ? $_SERVER['argv'][0] : '';

        $current_domain = $_SERVER['HTTP_HOST'];
        
        $pwe_edition = trim(do_shortcode('[pwe_edition]')) === '1';

        foreach ($pwe_groups_data as $group) {

            if ($current_domain != $group->fair_domain) {
                continue;
            }

            $current_group = $group->fair_group;

            foreach ($pwe_groups_contacts_data as $group_contact) {

                $contact_group_name = $group_contact->groups_name;

                $is_b2c_special = (
                    ($current_group === "b2c" || $current_group === "b2c-new")
                    && $pwe_edition
                );

                /*
                * B2C OVERRIDE FOR PREMIERE EDITION
                */
                if ($is_b2c_special
                    && $contact_group_name === "gr1"
                    && (
                        $group_contact->groups_slug === "biuro-ob"
                        || $group_contact->groups_slug === "ob-marketing-media"
                    )
                ) {

                    $data = json_decode($group_contact->groups_data);

                    if ($group_contact->groups_slug === "biuro-ob") {

                        $service_emails = array_map(
                            'trim',
                            preg_split('/[,;]+/', trim($data->email))
                        );

                        $service_phone = trim($data->tel);
                    }

                    if ($group_contact->groups_slug === "ob-marketing-media") {

                        $marketing_emails = array_map(
                            'trim',
                            preg_split('/[,;]+/', trim($data->email))
                        );
                    }

                    continue;
                }

                /*
                * OTHER CONTACTS
                */

                if ($group->fair_group === $contact_group_name) {

                    $data = json_decode($group_contact->groups_data);

                    if ($group_contact->groups_slug === "biuro-ob") {

                        $service_emails = array_map(
                            'trim',
                            preg_split('/[,;]+/', trim($data->email))
                        );

                        $service_phone = trim($data->tel);
                    }

                    if ($group_contact->groups_slug === "ob-marketing-media") {

                        $marketing_emails = array_map(
                            'trim',
                            preg_split('/[,;]+/', trim($data->email))
                        );
                    }

                    if ($group_contact->groups_slug === "ob-tech-wyst") {

                        $consultant_email = trim($data->email);
                    }

                    if ($group_contact->groups_slug === "osoba-kontakt") {

                        $contact_person_name  = trim($data->name);
                        $contact_person_email = trim($data->email);
                        $contact_person_phone = trim($data->tel);
                    }
                }
            }
        }

        $service_email = !empty($service_email) ? $service_email : "zgloszenia@warsawexpo.eu";

        $output = '
        <style>
            .pwelement_'. self::$rnd_id .' .pwe-container-contact {
                padding: 36px;
                border: 1px solid black;
                border-radius: 18px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-container-contact-items {
                display:flex;
                flex-direction: column;
                gap: 18px;
                margin-top: 18px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-contact-icon-item {
                display:flex;
                align-items: center;
                gap: 18px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-contact-icon-item a {
                font-size: 14px;
                display: flex;
                flex-wrap: wrap;
            }
            .pwelement_'. self::$rnd_id .' .pwe-container-contact img{
                max-width: 110px !important;
                border-radius: 18px;
            }
            .pwelement_'. self::$rnd_id .' .uncode_text_column :is(p, a),
            .pwelement_'. self::$rnd_id .' .pwe-heading-text h4 {
                margin: 0;
                ' . $text_color . '
            }
            .pwelement_'. self::$rnd_id .' .pwe-container-contact .main-pwe-heading-text {
                padding-top: 0;
                text-transform: uppercase;
            }
            #pweContact .gform_confirmation_message span {
                color: white !important;
            }

            @media (max-width:860px){
                .pwelement_'. self::$rnd_id .' .pwe-container-contact {
                    padding: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-contact-icon-item {
                    flex-wrap: wrap;
                    justify-content: center;
                    text-align: center;
                    flex-direction: column;
                }
                .pwelement_'. self::$rnd_id .' .pwe-heading-text {
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-heading-text h4 {
                    width: 100%;
                    margin-bottom: 10px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-contact-icon-item p {
                    min-width: 160px;
                }
            }';

            if (isset($atts["horizontal"]) && $atts["horizontal"] == "true") {
                $output .= '
                .pwelement_'. self::$rnd_id .' .pwe-container-contact-items {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-evenly;
                }
                .pwelement_'. self::$rnd_id .' .pwe-contact-icon-item {
                    flex-direction: column;
                    text-align: center;
                    flex: 1;
                }
                .pwelement_'. self::$rnd_id .' {
                    padding: 9px 0;
                }';
            }
        $output .= '
        </style>

        <div id="contact" class="pwe-container-contact">

            <div class="pwe-heading-text main-pwe-heading-text">
                <h4>'.PWElementContactForm::multi_translation("customer_service").'</h4>
            </div>

            <div class="pwe-container-contact-items">

                <div class="pwe-contact-icon-item">
                    <img src="/wp-content/plugins/pwe-media/media/Phone.jpg" alt="grafika słuchawka">
                    <div class="uncode_text_column">
                        <p>
                            <b>'.PWElementContactForm::multi_translation("customer_service_office").'</b>';
                            if (!empty($service_phone)) {
                                $output .= '<a href="tel:'. $service_phone .'">'. $service_phone .'</a>';
                            }

                            if (!empty($service_emails) && is_array($service_emails)) {
                                foreach ($service_emails as $email) {
                                    $output .= '
                                    <a href="mailto:'. $email .'">
                                        <span>'. str_replace("@warsawexpo.eu", "", $email) .'</span><span>@warsawexpo.eu</span>
                                    </a>';
                                }
                            }
                            $output .= '
                        </p>
                    </div>
                </div>';

                if (!empty($consultant_email)) {
                    $output .= '
                    <div class="pwe-contact-icon-item">
                        <img src="/wp-content/plugins/pwe-media/media/WystawcyZ.jpg" alt="grafika wystawcy">
                        <div class="uncode_text_column">
                            <p>
                                <b>'.PWElementContactForm::multi_translation("technical_support").'</b>
                                <a href="mailto:'. $consultant_email .' ">
                                    <span>'. $consultant_email .'</span>
                                </a>
                            </p>
                        </div>
                    </div>';
                }

            $output .= '
            </div>

            <div class="pwe-heading-text main-pwe-heading-text" style="margin-top: 36px;">
                <h4>'.PWElementContactForm::multi_translation("media_marketing").'</h4>
            </div>

            <div class="pwe-container-contact-items">';

                if (!empty($marketing_emails)) {
                    $output .= '
                    <div class="pwe-contact-icon-item">
                        <img src="/wp-content/plugins/pwe-media/media/Marketing.jpg" alt="grafika technicy">
                        <div class="uncode_text_column" style="overflow-wrap: anywhere;">
                            <p>
                                <b>'.PWElementContactForm::multi_translation("media_marketing_service").'</b>';

                                if (!empty($marketing_emails) && is_array($marketing_emails)) {
                                    foreach ($marketing_emails as $email) {
                                        $output .= '
                                        <a href="mailto:'. $email .'">
                                            <span>'. str_replace("@warsawexpo.eu", "", $email) .'</span><span>@warsawexpo.eu</span>
                                        </a>';
                                    }
                                }

                                $output .= '
                            </p>
                        </div>
                    </div>';
                }

                if (!empty($contact_person_name) && (!empty($contact_person_email) || !empty($contact_person_phone))) {
                    $output .= '
                    <div class="pwe-contact-icon-item">
                        <img src="/wp-content/plugins/pwe-media/media/Person.jpg" alt="grafika osoby">
                        <div class="uncode_text_column" style="overflow-wrap: anywhere;">
                            <p>
                                <b>'. $contact_person_name .'</b>';
                                if (!empty($contact_person_phone)) {
                                    $output .= '<a href="tel:'. $contact_person_phone .'">'. $contact_person_phone .'</a>';
                                }
                                if (!empty($contact_person_email)) {
                                    $output .= '<a href="mailto:'. $contact_person_email .'">'. $contact_person_email .'</a>';
                                }
                            $output .= '
                            </p>
                        </div>
                    </div>';
                }

                $output .= '
            </div>

        </div>';

        $output .= '
        <script>
            document.addEventListener("DOMContentLoaded", function () {
               const utm = "'. $source_utm .'";

               function getCookie(name) {
                  let value = "; " + document.cookie;
                  let parts = value.split("; " + name + "=");
                  if (parts.length === 2) return parts.pop().split(";").shift();
                  return null;
                }

                function deleteCookie(name) {
                    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                }

                let utmPWE = utm;
                let utmCookie = getCookie("utm_params");
                let utmInput = document.querySelector(".utm-class input");

                if (utmInput) {
                    utmInput.value = utmPWE;
                }

            });
        </script>';

        return $output;
    }
}