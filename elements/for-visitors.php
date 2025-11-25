<?php
/**
* Class PWElementForVisitors
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementForVisitors extends PWElements {

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
        for($i=0; $i<2; $i++){
            $element_output[] =
                array(
                    'type' => 'textarea',
                    'group' => 'PWE Element',
                    'heading' => __('Visitors text' . ($i+1), 'pwelement'),
                    'param_name' => 'visitor_text' . ($i+1),
                    'param_holder_class' => 'backend-textarea',
                    'value' => '',
                    'dependency' => array(
                        'element' => 'pwe_element',
                        'value' => 'PWElementForVisitors',
                    ),
                );
        }
        return $element_output;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/for_visitors.json';

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
    * @return string @output
    */
    public static function output($atts) {
        $all_images = self::findAllImages('/doc/galeria/zdjecia_wys_odw', 2);

        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';
        $btn_text_color = 'color:' . self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important; border-width: 0 !important;';
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important;';
        $btn_shadow_color = 'box-shadow: 9px 9px 0px -5px ' . self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black') . '!important;';
        $btn_border = 'border: 1px solid ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important;';
        // $img_shadow = 'box-shadow: 9px 9px 0px -6px ' . self::findColor(self::$main2_color,  self::$accent_color, 'black'). ' !important;';


        $output = '';

        $output .= '
            <style>
                .pwelement_' . self::$rnd_id . ' #forVisitors p {
                    ' . $text_color . '
                }
                .pwelement_'.self::$rnd_id.' .pwe-btn {
                    '. $btn_text_color
                    . $btn_color
                    . $btn_shadow_color
                    . $btn_border .'
                    box-shadow: unset !important;
                    border-radius: 10px !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-content-visitors-item {
                    width: 100%;
                    display:flex;
                    justify-content: center;
                    gap: 36px;
                    padding-bottom: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-visitors-image-block,
                .pwelement_'. self::$rnd_id .' .pwe-visitors-text-block{
                    width: 50%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-visitors-text-block {
                    align-content: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-visitors-image-block img {
                    width: 100%;
                    aspect-ratio: 16/9;
                    object-fit: cover;
                    border-radius: 18px;
                }
                @media (max-width:768px){
                    .pwelement_'. self::$rnd_id .' .pwe-content-visitors-item {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-column-reverse{
                        flex-direction: column-reverse;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-image-block,
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-text-block {
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-text {
                        padding: 18px 0;
                    }
                }
            </style>

            <div id="forVisitors"class="pwe-container-visitors">
                <div class="pwe-content-visitors-item pwe-align-left">
                    <div class="pwe-visitors-image-block uncode-single-media-wrapper">
                        <img src="' . $all_images[0] . '" alt="visitors image 1">
                    </div>
                    <div class="pwe-visitors-text-block">
                        <div class="pwe-visitors-text">
                            <p>';
                                if(!isset($atts['visitor_text1']) || $atts['visitor_text1'] == ''){
                                    $output .= self::multi_translation("for_visitors");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['visitor_text1']);
                                }
                            $output .= '</p>
                        </div>
                    </div>
                </div>

                <!-- for-visitors-item -->
                <div class="pwe-content-visitors-item pwe-column-reverse pwe-align-left column-reverse">
                    <div class="pwe-visitors-text-block">
                        <div class="pwe-visitors-text">
                            <p>';
                                if(!isset($atts['visitor_text2']) || $atts['visitor_text2'] == ''){
                                    $output .= self::multi_translation("for_visitors_1");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['visitor_text2']);
                                }
                            $output .= '</p>
                        </div>
                        <div class="pwe-btn-container">
                            <span>';
                            if (do_shortcode('[trade_fair_group]') === 'b2c') {
                                $output .= '
                                <a class="pwe-link btn border-width-0 shadow-black btn-accent btn-flat pwe-btn" href="'.self::multi_translation("ticket_link").'">
                                '.self::multi_translation("ticket_text").'
                                </a>';
                            } else {
                                $output .= '
                                <a class="pwe-link btn border-width-0 shadow-black btn-accent btn-flat pwe-btn" href='.self::multi_translation("register_link").'>'.self::multi_translation("register_text").'</a>';
                            }
                            $output .= '
                            </span>
                        </div>
                    </div>
                    <div class="pwe-visitors-image-block uncode-single-media-wrapper">
                        <img src="' . $all_images[1] . '"alt="visitors image 2">
                    </div>
                </div>
            </div>';

        return $output;
    }
}