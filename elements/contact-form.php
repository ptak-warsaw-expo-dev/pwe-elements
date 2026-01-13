<?php

/**
 * Class PWElementContactForm
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementContactForm extends PWElements {

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
                'heading' => __('Select Form', 'pwe_element'),
                'param_name' => 'contact_form_id',
                'save_always' => true,
                'value' => array_merge(
                  array('Wybierz' => ''),
                  self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementContactForm',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom button text form', 'pwe_element'),
                'param_name' => 'contact_button_text',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementContactForm',
                ),
            ),
        );
        return $element_output;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/contact.json';

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
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') .' !important';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') .' !important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'black') .'!important';

        $output = '';

        global $contact_button_text, $contact_form_id;

        extract( shortcode_atts( array(
            'contact_form_id' => '',
            'contact_button_text' => '',
        ), $atts ));

        $contact_button_text = empty($contact_button_text) ? self::multi_translation("send_message") : $contact_button_text;


        $output .= '
        <style>
            .pwelement_'. self::$rnd_id .' .pwe-contact {
                background-color: '. self::$accent_color .';
                border-radius: 18px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-contact__wrapper {
                padding: 36px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-contact input[type="text"],
            .pwelement_'. self::$rnd_id .' .pwe-contact input[type="email"],
            .pwelement_'. self::$rnd_id .' .pwe-contact input[type="tel"],
            .pwelement_'. self::$rnd_id .' .pwe-contact textarea {
                box-shadow: none !important;
                border-radius: 8px;
            }
            .pwelement_'. self::$rnd_id .' .gform_fields,
            .pwelement_'. self::$rnd_id .' .gfield, .gfield_radio,
            .pwelement_'. self::$rnd_id .' .pwe-contact h2 {
                padding: 0 !important;
            }
            .pwelement_'. self::$rnd_id .' .pwe-contact h2 {
                margin: 0 !important;
                text-transform: uppercase;
            }
            .pwelement_'. self::$rnd_id .' .pwe-contact :is(h2, p, label) {
                color: white;
            }
            .pwelement_'. self::$rnd_id .' .gfield_required {
                display: none;
            }
            .pwelement_'. self::$rnd_id .' .gform_footer {
                display: flex;
            }
           .pwelement_'. self::$rnd_id .' input[type=submit].gform_button {
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .pwelement_'. self::$rnd_id .' .pwe-btn {
                background-color: white;
                border-width: 1px;
                border-radius: 10px;
                border: 2px solid white;
                font-size: 14px;
                color: black;
                align-self: center;
                transform: scale(1) !important;
            }
            .pwelement_'. self::$rnd_id .' .pwe-btn:hover {
                background-color: #eeeeee;
                border: 2px solid #eeeeee;
            }
        </style>';

        $output .= '
        <div id="pweContact" class="pwe-contact">
            <div class="pwe-contact__wrapper">
                <div class="pwe-contact__text">
                    <h2>'.self::multi_translation("write_to_us").'</h2>
                    <p>'. self::multi_translation("reply"). '</p>
                </div>
                <div class="pwe-contact__form">
                    [gravityform id="'. $contact_form_id .'" title="false" description="false" ajax="false"]
                </div>

            </div>
        </div>';

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

            function custom_gform_submit_button($button, $form) {
                global $contact_button_text, $contact_form_id;
                $contact_form_id_nmb = get_form_id_by_title($contact_form_id);

                if ($form['id'] == $contact_form_id_nmb) {
                    $button = '<input type="submit" id="gform_submit_button_'. $contact_form_id_nmb .'" class="gform_button button" value="'.$contact_button_text.'" onclick="if(window[&quot;gf_submitting_'.$contact_form_id_nmb.'&quot;]){return false;}  if( !jQuery(&quot;#gform_'.$contact_form_id_nmb.'&quot;)[0].checkValidity || jQuery(&quot;#gform_'.$contact_form_id_nmb.'&quot;)[0].checkValidity()){window[&quot;gf_submitting_'.$contact_form_id_nmb.'&quot;]=true;}  " onkeypress="if( event.keyCode == 13 ){ if(window[&quot;gf_submitting_'.$contact_form_id_nmb.'&quot;]){return false;} if( !jQuery(&quot;#gform_'.$contact_form_id_nmb.'&quot;)[0].checkValidity || jQuery(&quot;#gform_'.$contact_form_id_nmb.'&quot;)[0].checkValidity()){window[&quot;gf_submitting_'.$contact_form_id_nmb.'&quot;]=true;}  jQuery(&quot;#gform_'.$contact_form_id_nmb.'&quot;).trigger(&quot;submit&quot;,[true]); }">
                    <button id="pweContactBtn" class="btn pwe-btn">'. $contact_button_text .'</button>';
                }
                return $button;
            }
            add_filter('gform_submit_button', 'custom_gform_submit_button', 10, 2);
        }
        if(get_locale() != "pl_PL" && get_locale() != "en_US" ){

            $output .= '
            <script>
                function translateElement(element, newText) {
                    if (!element) return;
                    element.textContent = newText;
                }

                const nameInput = document.querySelector(\'input[placeholder="First name and last name"]\');
                if (nameInput) {
                    nameInput.placeholder = "'. self::multi_translation("first_name"). '";
                }

                const helpTextarea = document.querySelector(\'textarea[placeholder="How can we help you?"]\');
                if (helpTextarea) {
                    helpTextarea.placeholder = "'. self::multi_translation("help_you"). '";
                }

                const departmentLabel = Array.from(document.querySelectorAll("label"))
                    .find(label => label.textContent.trim().startsWith("Department"));

                if (departmentLabel) {
                    translateElement(departmentLabel, "'. self::multi_translation("department"). '");
                }

                const departmentField = departmentLabel?.closest(".gfield");
                if (departmentField) {
                    const radioLabels = departmentField.querySelectorAll("label.gform-field-label--type-inline");

                    radioLabels.forEach(label => {
                        const text = label.textContent.trim();

                        switch (text) {
                            case "Exhibitors":
                                label.textContent = "Aussteller";
                                break;
                            case "Visitors":
                                label.textContent = "Besucher";
                                break;
                            case "Technical Department":
                                label.textContent = "Technische Abteilung";
                                break;
                            case "Marketing":
                                label.textContent = "Marketing";
                                break;
                        }
                    });
                }
            </script>';
        };

        return $output;

    }
}