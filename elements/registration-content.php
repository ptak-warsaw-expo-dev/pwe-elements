<?php 

/**
 * Class PWElementRegContent
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementRegContent extends PWElements {

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
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title form', 'pwelement'),
                'param_name' => 'form_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegContent',
                ),
            ),
              array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Custom text form', 'pwelement'),
                'param_name' => 'form_text',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegContent',
                ),
            ),
              array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom button text form', 'pwelement'),
                'param_name' => 'form_button_text',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementRegContent',
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
    public static function output($atts) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . ' !important';

        extract( shortcode_atts( array(
            'form_title' => '',
            'form_text' => '',
            'form_button_text' => '',
        ), $atts ));

        if(get_locale() == 'pl_PL') {
            $form_title = ($form_title == "") ? "DLA ODWIEDZAJĄCYCH" : $form_title;
            $form_text = ($form_text == "") ? "Wypełnij formularz i odbierz darmowy bilet" : $form_text;
            $form_button_text = ($form_button_text == "") ? "ODBIERZ DARMOWY BILET" : $form_button_text;
        } else {
            $form_title = ($form_title == "") ? "FOR VISITORS" : $form_title;
            $form_text = ($form_text == "") ? "Fill out the form and receive your free ticket" : $form_text;
            $form_button_text = ($form_button_text == "") ? "GET A FREE TICKET" : $form_button_text;
        }

        // Create unique id for element
        $unique_id = rand(10000, 99999);
        $element_unique_id = 'pweFormContent-' . $unique_id;
        
        $output = '
        <style>
            .row-container:has(.pwelement_'. self::$rnd_id .') .gform_wrapper input[type="submit"] {
                opacity: 0;
            }
            .row-container:has(.pwelement_'. self::$rnd_id .') .pwe-form-title h4, .pwe-form-text p {
                color: '. $text_color .';
            }
            .row-container:has(.pwelement_'. self::$rnd_id .') .pwe-form-title h4 {
                margin-top: 0 !important;
                box-shadow: 9px 9px 0px -6px '. $text_color .';
            }
            .row-container:has(.pwelement_'. self::$rnd_id .') .gform_wrapper :is(label, .gfield_description), 
            .row-container:has(.pwelement_'. self::$rnd_id .') .gform_legacy_markup_wrapper .gfield_required,
            .row-container:has(.pwelement_'. self::$rnd_id .') .show-consent {
                color: '. $text_color .';
            }
            .row-container:has(.pwelement_'. self::$rnd_id .') .gform-body ul {
                padding: 0 !important;
            }
            @media (max-width: 450px) {
                .row-container:has(.pwelement_'. self::$rnd_id .') input[type="submit"] {
                    padding: 12px 20px !important;
                    font-size: 3.3vw !important;
                }
            }
        </style>
        
        <div id="'. $element_unique_id .'" class="pwe-form-content">
            <div class="pwe-form-title main-heading-text">
                <h4 class="custom-uppercase"><span>'. $form_title .'</span></h4>
            </div>  
            <div class="pwe-form-text">';
                $form_text = str_replace(array('`{`', '`}`'), array('[', ']'), $form_text);
                $output .= '<p>'. wpb_js_remove_wpautop($form_text, true) .'</p>
            </div>
        </div>';
        
        $output .= "
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const pweRowContainer = document.querySelector('.row-container:has(.pwelement_". self::$rnd_id .")');
                let pweFormButton = pweRowContainer.querySelector('.gform_wrapper input[type=\'submit\']');
                if (pweFormButton) {
                    pweFormButton.style.opacity = 1;
                    pweFormButton.style.transition = 'opacity 0.3s ease';
                    pweFormButton.value = '". $form_button_text ."';
                }
            });  
        </script>";   

        return $output;

    }
}