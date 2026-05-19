<?php

/**
 * Class PWElementMedals
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementMedals extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Presets', 'pwe_element'),
                'param_name' => 'medals_preset',
                'save_always' => true,
                'std'       => 'default',
                'value' => array(
                    'Default' => 'default',
                    'Stacked version' => 'stacked',
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMedals',
                ),
            ),
        );
        return $element_output;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/medals.json';

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

    public static function output($atts) {
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important';
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important';

        extract( shortcode_atts( array(
            'medals_preset' => '',
        ), $atts ));

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $trade_fair_edition = do_shortcode('[trade_fair_edition]');



        // global $fairs_data;

        // echo '<pre>';
        // var_dump(PWECommonFunctions::get_database_fairs_data());
        // echo '</pre>';



        if (trim($trade_fair_edition) === "Premierowa" || trim($trade_fair_edition) === "Premier") {
            $output = '
            <style>
                .pwe-medals__wrapper {
                    display: flex;
                    flex-direction: column;
                    box-shadow: 2px 2px 12px #cccccc;
                    border-radius: 36px;
                    padding: 36px;
                    text-align: center;
                    gap: 18px;
                }
                .pwe-medals__heading h4 {
                    margin: 0 auto;
                }
                .pwe-medals__items-container {
                    display: flex;
                    align-items: center;
                    max-width: 800px;
                    margin: 0 auto;
                    flex-wrap: wrap;
                }
                .pwe-medals__items {
                    width: 50%;
                }
                .pwe-medals__text {
                    max-width: 800px;
                    margin: 0 auto;
                }
                .pwe-medals__items-text {
                    text-align: left;
                }
                .pwe-medals__wrapper .pwe-medals__items_mobile {
                    display: none;
                }
                .pwe-medals__wrapper .pwe-button-link {
                    transform-origin: center !important;
                }
                @media(max-width: 650px) {
                    .pwe-medals__wrapper {
                        padding: 18px;
                    }
                    .pwe-medals__text p {
                        line-height: 1.3;
                        text-align: center;
                    }
                    .pwe-medals__items-text {
                        text-align: center;
                        margin: 0 auto;
                    }
                    .pwe-medals__items {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                    }
                    .pwe-medals__item img {
                        width: 100%;
                    }
                    .pwe-medals__wrapper .pwe-medals__items_mobile {
                        display: block;
                    }
                    .pwe-medals__wrapper .pwe-medals__items {
                        width: 100%;
                    }
                }
                .pwe-medals .pwe-button-link {
                    color: white;
                    background-color: ' . self::$accent_color . ';
                    border: 1px solid ' . self::$accent_color . ';
                    border-radius: 10px;
                    min-width: 240px;
                }
                .pwe-medals .pwe-button-link:hover {
                    color: white !important;
                    background-color: '. $darker_btn_color .'!important;
                    border: 1px solid '. $darker_btn_color .'!important;
                }
            </style>

            <div id="pweMedals" class="pwe-medals">
                <div class="pwe-medals__wrapper">
                    <div class="pwe-medals__heading">
                        <h4>'. self::multi_translation("award") .'</h4>
                    </div>
                    <div class="pwe-medals__text">
                        <p>'. self::multi_translation("text_premier") .'</p>
                    </div>
                    <div class="pwe-medals__text">
                        <p><strong>'. self::multi_translation("category_premier") .'</strong></p>
                    </div>
                    <div class="pwe-medals__items-container">
                        <div class="pwe-medals__items">
                            <div class="pwe-medals__item"><img src="'. self::multi_translation("premier_fair") .'"/></div>
                        </div>
                        <div class="pwe-medals__items-text">
                            <p>'. self::multi_translation("partner_premier") .'</p>
                            <p>'. self::multi_translation("cocreator_premier") .'</p>
                        </div>
                    </div>';

                    $output .= '
                    <div class="pwe-medals__button">
                        <a class="pwe-button-link btn" href="'. self::multi_translation("book_stand") .'">'. self::multi_translation("book_stand_button") .'</a>
                    </div>

                </div>
            </div>';

        } else {
            if ($medals_preset == 'default' || empty($medals_preset)) {
                $output = '
                <style>
                    .pwe-medals__wrapper {
                        display: flex;
                        flex-direction: column;
                        box-shadow: 2px 2px 12px #cccccc;
                        border-radius: 36px;
                        padding: 36px;
                        text-align: center;
                        gap: 18px;
                    }
                    .pwe-medals__heading h4 {
                        margin: 0 auto;
                        text-transform: uppercase;
                    }
                    .pwe-medals__items {
                        display: flex;
                        justify-content:center;
                        gap: 10px;
                    }
                       .pwe-medals__item {
                       flex:0.25;
                    }
                    .pwe-medals__wrapper .pwe-button-link {
                        transform-origin: center !important;
                    }
                    @media(max-width: 650px) {
                        .pwe-medals__wrapper {
                            padding: 18px;
                        }
                        .pwe-medals__text p {
                            line-height: 1.3;
                            text-align: left;
                        }
                        .pwe-medals__items {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: center;
                        }
                        .pwe-medals__item img {
                            width: 100%;
                        }
                        .pwe-medals__wrapper .pwe-medals__items_mobile {
                            display: block;
                        }
                        .pwe-medals__item {
                            flex:0.5;
                        }

                    }
                    .pwe-medals .pwe-button-link {
                        color: white;
                        background-color: ' . self::$accent_color . ';
                        border: 1px solid ' . self::$accent_color . ';
                        border-radius: 10px;
                        min-width: 240px;
                    }
                    .pwe-medals .pwe-button-link:hover {
                        color: white !important;
                        background-color: '. $darker_btn_color .'!important;
                        border: 1px solid '. $darker_btn_color .'!important;
                    }
                </style>';
            } else if ($medals_preset == 'stacked') {
                $output = '
                <style>
                    .pwe-medals__wrapper {
                        display: flex;
                        flex-direction: column;
                        border-radius: 36px;
                        padding: 36px;
                        text-align: center;
                        gap: 18px;
                    }
                    .pwe-medals__heading h4 {
                        margin: 0 auto;
                        font-weight: 800;
                    }
                    .pwe-medals__items {
                        position: relative;
                        height: 300px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                    .pwe-medals__wrapper .pwe-button-link {
                        transform-origin: center !important;
                    }
                    @media(min-width:650px) {
                        .pwe-medals__heading:first-of-type h4 {
                            font-size: 30px;
                        }
                        .pwe-medals__text p {
                            max-width: 900px;
                            margin: 18px auto 0;
                        }
                        .pwe-medals__item {
                            flex:0.5;
                        }
                        .pwe-medals__item img {
                            width: 25vw;
                            max-width: 300px;
                            height: auto;
                        }
                    }
                    @media(max-width: 650px) {
                        .pwe-medals__wrapper {
                            padding: 18px;
                        }
                        .pwe-medals__text p {
                            line-height: 1.3;
                            text-align: left;
                        }
                        .pwe-medals__items {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: center;
                        }

                        .pwe-medals__item img {
                            width: 100%;
                        }
                        .pwe-medals__wrapper .pwe-medals__items_mobile {
                            display: block;
                        }
                        .pwe-medals__item {
                            flex:0.5;
                        }
                    }
                    .pwe-medals .pwe-button-link {
                        color: ' . $btn_text_color . ';
                        background-color: ' . $btn_color . ';
                        border: 1px solid ' . $btn_border . ';
                        border-radius: 36px;
                        min-width: 240px;
                    }
                    .pwe-medals .pwe-button-link:hover {
                        color: white !important;
                        background-color: '. $darker_btn_color .'!important;
                        border: 1px solid '. $darker_btn_color .'!important;
                    }
                </style>';
            }

            $output .= '
            <div id="pweMedals" class="pwe-medals">
                <div class="pwe-medals__wrapper">
                    <div class="pwe-medals__heading">
                        <h4>'. self::multi_translation("award") .'</h4>
                    </div>
                    <div class="pwe-medals__text">'. self::multi_translation("text") .'</div>
                    <div class="pwe-medals__items">
                        <div class="pwe-medals__item"><img src="/wp-content/plugins/pwe-media/media/medals/medale-2026.webp"/></div>
                        <div class="pwe-medals__item"><img src="/wp-content/plugins/pwe-media/media/medals/medale-2026r.webp"/></div>
                    </div>';

                    $output .= '<div class="pwe-medals__items_mobile pwe-slides">';

                    $output .= '
                    <div class="pwe-medals__heading">
                        <h4>'. self::multi_translation("lider_text") .'</h4>
                    </div>
                    <div class="pwe-medals__button">
                        <a class="pwe-button-link btn" href="'. self::multi_translation("book_stand") .'">'. self::multi_translation("book_stand_button") .'</a>
                    </div>

                </div>
            </div>';
            }


        return $output;
    }
}