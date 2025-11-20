<?php 

/**
 * Class PWEExhibitorWorkerGenerator
 * 
 * This class adding creating html for exhibitors to easy register workers.
 */
class PWEExhibitorWorkerGenerator extends PWEExhibitorGenerator {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output.
     * 
     * @param array @atts options
     * @return string html output
     */
    public static function output($atts) {

        extract( shortcode_atts( array(
            'generator_form_id' => '',
            'exhibitor_generator_html_text' => '',
        ), $atts ));

        $generator_html_text_decoded = base64_decode($exhibitor_generator_html_text);
        $generator_html_text_decoded = urldecode($generator_html_text_decoded);
        $generator_html_text_content = wpb_js_remove_wpautop($generator_html_text_decoded, true);

        $output = '';
        $output .= '
        <div class="exhibitor-generator">
            <div class="exhibitor-generator__wrapper">
                <div class="exhibitor-generator__left">
                    <div class="exhibitor-generator__left-wrapper">
                        <h3>' . PWECommonFunctions::languageChecker('WYGENERUJ<br>IDENTYFIKATOR DLA<br>SIEBIE I OBSŁUGI STOISKA', 'GENERATE</br>A VIP INVITATION</br>FOR YOUR GUESTS!') . '</h3>
                    </div>
                </div>
                <div class="exhibitor-generator__right">
                    <div class="exhibitor-generator__right-wrapper">
                        <div class="exhibitor-generator__right-title">
                            <h3>' . PWECommonFunctions::languageChecker('WYGENERUJ<br>IDENTYFIKATOR DLA<br>SIEBIE I OBSŁUGI STOISKA', 'GENERATE</br>A VIP INVITATION</br>FOR YOUR GUESTS!') . '</h3>
                        </div>
                        <div class="exhibitor-generator__right-form">
                            [gravityform id="'. $generator_form_id .'" title="false" description="false" ajax="false"]
                        </div>';
                        if (!empty($generator_html_text_content)) {
                            $output .= '<div class="exhibitor-generator__right-text">' . $generator_html_text_content . '</div>';
                        }
                    $output .= '    
                    </div>
                </div>
            </div>
        </div>
        ';

        return $output; 
    }
}