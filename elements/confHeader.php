<?php 

/**
 * Class PWElementVoucher
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementHeaderConference extends PWElements {

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
                'heading' => __('Title', 'pwelement'),
                'param_name' => 'pwe_header_conference_title',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementHeaderConference',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Description', 'pwelement'),
                'param_name' => 'pwe_header_conference_desc',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementHeaderConference',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'PWE Element',
                'heading' => __('Overlay color', 'pwe_header'),
                'param_name' => 'pwe_header_conference_overlay_color',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementHeaderConference',
                ),
            ),
            array(
                'type' => 'input_range',
                'group' => 'PWE Element',
                'heading' => __('Overlay opacity', 'pwe_header'),
                'param_name' => 'pwe_header_conference_overlay_range',
                'value' => '0.80',
                'min' => '0',
                'max' => '1',
                'step' => '0.01',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementHeaderConference',
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
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');

        if ($text_color == '' || $text_color == '#000000 !important' || $text_color == 'black !important') {
            $text_shadow = 'white !important;';
        } else {
            $text_shadow = 'black !important;';
        }

        extract( shortcode_atts( array(
            'pwe_header_conference_overlay_color' => '',
            'pwe_header_conference_overlay_range' => '',
            'pwe_header_conference_title' => '', 
            'pwe_header_conference_desc' => '',
        ), $atts ));

        $id_rnd = rand(10000, 99999);

        $pwe_header_conference_overlay_color = empty($pwe_header_conference_overlay_color) ? 'black' : $pwe_header_conference_overlay_color;
        $pwe_header_conference_overlay_range = empty($pwe_header_conference_overlay_range) ? '0.8' : $pwe_header_conference_overlay_range;

        if (get_locale() == 'pl_PL') {
            $pwe_header_conference_title = !empty($pwe_header_conference_title) ? $pwe_header_conference_title : 'KONFERENCJA';
        } else {
            $pwe_header_conference_title = !empty($pwe_header_conference_title) ? $pwe_header_conference_title : 'CONFERENCE';
        }
        
        $pwe_header_conference_desc = !empty($pwe_header_conference_desc) ? '<h4 class="pwe-header-conference-description">'. $pwe_header_conference_desc .'</h4>' : '';

        $output = '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-header-conference {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    background-position: center;
                    min-height: 150px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-header-conference:before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: '. $pwe_header_conference_overlay_color .';
                    opacity: '. $pwe_header_conference_overlay_range .';
                    z-index: 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-header-conference-wrapper {
                    
                }
                .pwelement_'. self::$rnd_id .' .pwe-header-conference-content {
                    position: relative;
                    display: flex;
                    flex-direction: column; 
                    justify-content: center;
                    align-items: center;
                    padding: 18px;
                    z-index: 1;
                }
                .pwelement_'. self::$rnd_id .' .pwe-header-conference-content h4 {
                        text-align: center;
                        text-transform: uppercase;
                        margin: 0;
                        color: '. $text_color .';
                        text-shadow: 2px 2px '. $text_shadow .';
                }
                .pwelement_'. self::$rnd_id .' .pwe-header-conference-title {
                    font-size: 24px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-header-conference-description {
                    padding-top: 8px;
                }
                @media (max-width:640px) {
                    .pwelement_'. self::$rnd_id .' .pwe-header-conference-title {
                        font-size: 20px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-header-conference-description {
                        font-size: 16px;
                    }
                }
            </style>
            
            <div id="PWEHeaderConference-'. $id_rnd .'" class="pwe-header-conference" style="background-image: url(/wp-content/plugins/pwe-media/media/conference-background.webp)">
                <div class="pwe-header-conference-wrapper">
                    <div class="pwe-header-conference-content">
                        <h4 class="pwe-header-conference-title">'. $pwe_header_conference_title .'</h4>
                        '. $pwe_header_conference_desc .'
                    </div>
                </div>
            </div>';
        
        return $output;
    }
}