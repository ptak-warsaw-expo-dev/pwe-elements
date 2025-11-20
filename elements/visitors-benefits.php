<?php

/**
 * Class PWElementVisitorsBenefits
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementVisitorsBenefits extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
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

    public static function output($atts, $content = '') {
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';

        $output = '';

        $output .='
            <style>
                .pwelement_' . self::$rnd_id . ' #visitorsBenefits :is(h3, h4) {
                    ' . $text_color . '
                }
                .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits-row {
                    padding-top: 18px;
                    width: 100%;
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    gap: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits-item {
                    width: 33%;
                }
                .pwe-visitors-benefits-item-img img {
                    background-color: [trade_fair_main2];
                }
                @media (max-width:768px) {
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits {
                        gap: 18px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits-item-heading h4 {
                        font-size: 16px;
                    }
                }
                @media (max-width:570px) {
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits-item {
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-visitors-benefits-item-heading h4 {
                        font-size: 20px;
                    }
                }
            </style>

            <div id="visitorsBenefits"class="pwe-container-visitors-benefits">
                <div id="main-content" class="pwe-visitors-benefits-heading main-heading-text">
                    <h3>'. self::multi_translation("why_it_worth") .'</h3>
                </div>
                <div class="pwe-visitors-benefits-row">
                    <div class="pwe-visitors-benefits">

                        <div class="pwe-visitors-benefits-item">
                            <div class="pwe-visitors-benefits-item-img">
                                <img src="/wp-content/plugins/pwe-media/media/lamp-b-150x150.webp" alt="lamp">
                            </div>
                            <div class="pwe-visitors-benefits-item-heading">
                                <h4 class="pwe-line-height"><h3>'. self::multi_translation("industry_trends") .'</h3></h4>
                            </div>
                        </div>
                        <div class="pwe-visitors-benefits-item">
                            <div class="pwe-visitors-benefits-item-img">
                                <img src="/wp-content/plugins/pwe-media/media/hands-b-150x150.webp" alt="handshake">
                            </div>
                            <div class="pwe-visitors-benefits-item-heading">
                                <h4 class="pwe-line-height">'. self::multi_translation("business_contacts") .'</h4>
                            </div>
                        </div>
                        <div class="pwe-visitors-benefits-item">
                            <div class="pwe-visitors-benefits-item-img">
                                <img src="/wp-content/plugins/pwe-media/media/head-b-150x150.webp" alt="head">
                            </div>
                            <div class="pwe-visitors-benefits-item-heading">
                                <h4 class="pwe-line-height">'. self::multi_translation("valuable_knowledge") .'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

    return $output;
    }
}