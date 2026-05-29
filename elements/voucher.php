<?php

/**
 * Class PWElementVoucher
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementVoucher extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/voucher.json';

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

        $fair_group = do_shortcode('[trade_fair_group]');

        if($fair_group === 'gr2' || $fair_group === 'gr3') {
            return;
        }
        
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
                .pwelement_'. self::$rnd_id .' .pwe-container-voucher {
                    display:flex;
                    flex-wrap: wrap;
                    justify-content: center;
                }
                @media (max-width:960px) {
                    .pwelement_'. self::$rnd_id .' .pwe-container-voucher {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .hidden-mobile {
                        display:none !important;
                    }
                }
            </style>

            <div id="PWEvoucher"class="pwe-container-voucher">
                <div class="uncode-single-media-wrapper half-block-padding pwe-min-media-wrapper" style="flex:1;">
                    <img style="vertical-align: bottom;" src="/wp-content/plugins/pwe-media/media/voucher.webp" alt="grafika przykładowego vouchera"/>
                </div>

                <div class="half-block-padding" style="flex:1;">
                    <div class="heading-text el-text text-centered main-heading-text">
                        <h4>'. self::multi_translation("voucher_title") .'</h4>
                    </div>';
                    $output .= '<p class="pwe-line-height hidden-mobile"  style="color '. $text_color .';">'. self::multi_translation("voucher_desc") .'</p>';

                    $output .= '<div class="pwe-btn-container">
                        <span>
                            <a class="pwe-link btn pwe-btn" href="'. self::multi_translation("voucher_link") .'">'. self::multi_translation("voucher_button") .'</a>
                    </div>
                </div>
            </div>';

        return $output;
    }
}