<?php

/**
 * Class PWElementStand
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementStand extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/zabudowa.json';

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
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important';
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important';
        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $output = '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-btn {
                    color: '. $btn_text_color .';
                    background-color: '. $btn_color .';
                    border: 1px solid '. $btn_border .';
                }
                .pwelement_'. self::$rnd_id .' .pwe-btn:hover {
                    color: '. $btn_text_color .';
                    background-color: '. $darker_btn_color .'!important;
                    border: 1px solid '. $darker_btn_color .'!important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-min-media-wrapper img {
                    border-radius: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-container-stand {
                    display:flex;
                    flex-wrap: wrap;
                    justify-content: center;
                }
                @media (max-width:960px) {
                    .pwelement_'. self::$rnd_id .' .pwe-container-stand {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-container-stand .pwe-block-1 {
                        order:2;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-container-stand .pwe-block-2 {
                        order:1;
                    }
                    .pwelement_'. self::$rnd_id .' .hidden-mobile {
                        display:none;
                    }
                }
            </style>

            <div id="stand" class="pwe-container-stand">
                <div class="pwe-block-1 half-block-padding" style="flex:1;">
                    <div class="heading-text el-text main-heading-text text-centered">
                    <h4>
                        '. self::multi_translation("stands") .'
                    </h4>
                    </div>';
                    $output .= '<p class="pwe-line-height hidden-mobile" style="color '. $text_color .';">
                        '. self::multi_translation("catalog") .'
                    </p>';
                    $output .= '<div class="pwe-btn-container">
                        <span>
                            <a class="pwe-link btn pwe-btn" target="_blank" href="'. self::multi_translation("stands_link") .'">'. self::multi_translation("stands_button") .'</a>
                        </span>
                        <span>
                            <a class="pwe-link btn pwe-btn" target="_blank" href="'. self::multi_translation("catalog_link") .'">'. self::multi_translation("catalog_button") .'</a>
                        </span>
                    </div>
                </div>

                <div class="pwe-block-2 single-media-wrapper half-block-padding pwe-min-media-wrapper" style="flex:1;">
                    <img src="/wp-content/plugins/pwe-media/media/zabudowa.webp" alt="zdjęcie przykładowej zabudowy"/>
                </div>
            </div>';

        return $output;

    }
}