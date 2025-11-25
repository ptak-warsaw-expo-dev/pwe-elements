<?php 

/**
 * Class PWElementNumbers
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementButton extends PWElements {

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
                'heading' => __('Button name', 'pwelement'),
                'param_name' => 'pwe_button_name',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementButton',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Button link', 'pwelement'),
                'param_name' => 'pwe_button_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementButton',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Target blank', 'pwelement'),
                'param_name' => 'pwe_button_target_blank',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementButton',
                ),
            ),
        );
        return $element_output;
    }    

    public static function output($atts) {  
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']) . '!important';
        $btn_border = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']) . '!important';
        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        extract( shortcode_atts( array(
            'pwe_button_name' => '',
            'pwe_button_link' => '',
            'pwe_button_target_blank' => '',
        ), $atts ));  

        $id_rnd = PWECommonFunctions::id_rnd();

        $pwe_button_target_blank = $pwe_button_target_blank == true ? 'target="_blank"' : '';
        
        $output = '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-button-link {
                    color: '. $btn_text_color .';
                    background-color: '. $btn_color .';
                    border: 1px solid '. $btn_border .';
                    border-radius: 10px;
                    min-width: 240px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-button-link:hover {
                    color: '. $btn_text_color .';
                    background-color: '. $darker_btn_color .'!important;
                    border: 1px solid '. $darker_btn_color .'!important;
                }
            </style>

            <div id="pweButton-'. $id_rnd .'" class="pwe-button">
                <a class="pwe-button-link btn" href="'. $pwe_button_link .'" '. $pwe_button_target_blank .'>'. $pwe_button_name .'</a> 
            </div>';

        return $output;
    }
}