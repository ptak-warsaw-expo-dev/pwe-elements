<?php
/**
* Class PWElementForExhibitors
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementForExhibitors extends PWElements {

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
        for($i=0; $i<6; $i++){
            $element_output[] =
                array(
                    'type' => 'textarea',
                    'group' => 'PWE Element',
                    'heading' => __('Exhibitors text' . ($i+1), 'pwelement'),
                    'param_name' => 'exhibitor_text' . ($i+1),
                    'param_holder_class' => 'backend-textarea',
                    'value' => '',
                    'dependency' => array(
                        'element' => 'pwe_element',
                        'value' => 'PWElementForExhibitors',
                    ),
                );
        }
        return $element_output;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/for_exhibitors.json';

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
    public static function output($atts, $content = '') {

        $all_images = self::findAllImages('/doc/galeria/zdjecia_wys_odw', 6);

        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';
        // $img_shadow = 'box-shadow: 9px 9px 0px -6px ' . self::findColor(self::$main2_color,  self::$accent_color, 'black'). ' !important;';

        $output = '';

        $output .= '
            <style>
                .pwelement_' . self::$rnd_id . ' #forforExhibitors :is(h4, p){
                    ' . $text_color . '
                }

                .pwelement_'. self::$rnd_id .' .pwe-container-forexhibitors {
                    margin: 0 auto;
                }
                .pwelement_'. self::$rnd_id .' .pwe-content-forexhibitors-item {
                    width: 100%;
                    display:flex;
                    justify-content: center;
                    gap: 36px;
                    padding-bottom: 36px;
                }

                .pwelement_'. self::$rnd_id .' .pwe-forexhibitors-image-block,
                .pwelement_'. self::$rnd_id .' .pwe-forexhibitors-text-block {
                    width: 50%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-forexhibitors-text-block {
                    align-content: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-forexhibitors-image-block img {
                    width: 100%;
                    aspect-ratio: 16/9;
                    object-fit: cover;
                    border-radius: 18px;
                }

                @media (max-width:768px) {
                    .pwelement_'. self::$rnd_id .' .pwe-content-forexhibitors-item {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .column-reverse {
                        flex-direction: column-reverse !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-forexhibitors-image-block,
                    .pwelement_'. self::$rnd_id .' .pwe-forexhibitors-text-block {
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-forexhibitors-text {
                        padding: 18px 0;
                    }
                }

            </style>

            <div id="for-exhibitors" class="pwe-container-forexhibitors">

                <div class="pwe-content-forexhibitors-item column-reverse pwe-align-left">
                    <div class="pwe-forexhibitors-text-block">
                        <div class="pwe-visitors-benefits-heading main-heading-text">
                            <h4>'. self::multi_translation("for_ex_1_title") .'</h4>
                        </div>
                        <div class="pwe-forexhibitors-text">
                            <p>';
                                if(!isset($atts['exhibitor_text1']) || $atts['exhibitor_text1'] == ''){
                                    $output .= self::multi_translation("for_ex_1_text");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['exhibitor_text1']);
                                }
                            $output .= '</p>
                        </div>
                    </div>
                    <div class="pwe-forexhibitors-image-block uncode-single-media-wrapper">
                        <img src="' . $all_images[0] . '" alt="forexhibitors image 1">
                    </div>
                </div>

                <div class="pwe-content-forexhibitors-item pwe-align-left">
                    <div class="pwe-forexhibitors-image-block uncode-single-media-wrapper">
                            <img src="' . $all_images[1] . '" alt="forexhibitors image 2">
                    </div>
                    <div class="pwe-forexhibitors-text-block">
                        <div class="pwe-visitors-benefits-heading main-heading-text">
                            <h4>'. self::multi_translation("for_ex_2_title") .'</h4>
                        </div>
                        <div class="pwe-forexhibitors-text">
                            <p>';
                                if(!isset($atts['exhibitor_text2']) || $atts['exhibitor_text2'] == ''){
                                    $output .= self::multi_translation("for_ex_2_text");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['exhibitor_text2']);
                                }
                            $output .= '</p>
                        </div>
                    </div>
                </div>

                <!-- for-forexhibitors-item -->
                <div class="pwe-content-forexhibitors-item column-reverse pwe-align-left">
                    <div class="pwe-forexhibitors-text-block">
                        <div class="pwe-visitors-benefits-heading main-heading-text">
                            <h4>'. self::multi_translation("for_ex_3_title") .'</h4>
                        </div>
                        <div class="pwe-forexhibitors-text">
                            <p>';
                                if(!isset($atts['exhibitor_text3']) || $atts['exhibitor_text3'] == ''){
                                    $output .= self::multi_translation("for_ex_3_text");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['exhibitor_text3']);
                                }
                            $output .= '</p>
                        </div>
                    </div>
                    <div class="pwe-forexhibitors-image-block uncode-single-media-wrapper">
                            <img src="' . $all_images[2] . '" alt="forexhibitors image 3">
                    </div>
                </div>

                <!-- for-forexhibitors-item -->
                <div class="pwe-content-forexhibitors-item pwe-align-left">
                    <div class="pwe-forexhibitors-image-block uncode-single-media-wrapper">
                            <img src="' . $all_images[3] . '" alt="forexhibitors image 4">
                    </div>
                    <div class="pwe-forexhibitors-text-block">
                        <div class="pwe-visitors-benefits-heading main-heading-text">
                            <h4>'. self::multi_translation("for_ex_4_title") .'</h4>
                        </div>
                        <div class="pwe-forexhibitors-text">
                            <p>';
                                if(!isset($atts['exhibitor_text4']) || $atts['exhibitor_text4'] == ''){
                                    $output .= self::multi_translation("for_ex_4_text");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['exhibitor_text4']);
                                }
                            $output .= '</p>
                        </div>
                    </div>
                </div>

                <!-- for-forexhibitors-item -->
                <div class="pwe-content-forexhibitors-item column-reverse pwe-align-left">
                    <div class="pwe-forexhibitors-text-block">
                        <div class="pwe-visitors-benefits-heading main-heading-text">
                            <h4>'. self::multi_translation("for_ex_5_title") .'</h4>
                        </div>
                        <div class="pwe-forexhibitors-text">
                            <p>';
                                if(!isset($atts['exhibitor_text5']) || $atts['exhibitor_text5'] == ''){
                                    $output .= self::multi_translation("for_ex_5_text");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['exhibitor_text5']);
                                }
                            $output .= '</p>
                        </div>
                    </div>
                    <div class="pwe-forexhibitors-image-block uncode-single-media-wrapper">
                            <img src="' . $all_images[4] . '" alt="forexhibitors image 5">
                    </div>
                </div>

                <!-- for-forexhibitors-item -->
                <div class="pwe-content-forexhibitors-item pwe-align-left">
                    <div class="pwe-forexhibitors-image-block uncode-single-media-wrapper">
                            <img src="' . $all_images[5] . '" alt="forexhibitors image 6">
                    </div>
                    <div class="pwe-forexhibitors-text-block">
                        <div class="pwe-visitors-benefits-heading main-heading-text">
                            <h4>'. self::multi_translation("for_ex_6_title") .'</h4>
                        </div>
                        <div class="pwe-forexhibitors-text">
                            <p>';
                                if(!isset($atts['exhibitor_text6']) || $atts['exhibitor_text6'] == ''){
                                    $output .= self::multi_translation("for_ex_6_text");
                                } else {
                                    $output .= str_replace(array('`{`', '`}`'), array('[',']'), $atts['exhibitor_text6']);
                                }
                            $output .= '</p>
                        </div>
                    </div>
                </div>
            </div>';
        return $output;
    }
}
