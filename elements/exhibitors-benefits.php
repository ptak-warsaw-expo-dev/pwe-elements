<?php
/**
* Class PWElementExhibitors
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementExhibitors extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/exhibitors-benefits.json';

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
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output =
        array(
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Logo in color', 'pwelement'),
                'param_name' => 'logo_color',
                'value' => '',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementExhibitors',
                )
            ),
        );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    *
    * @return string @output
    */
    public static function output($atts) {
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';
        $btn_text_color = 'color:' . self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important; border-width: 0 !important;';
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color']) . '!important;';
        $btn_shadow_color = 'box-shadow: 9px 9px 0px -5px ' . self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black') . '!important;';

        $border_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black');

        $output = '';

        $output .= '
        <style>

            .pwelement_'. self::$rnd_id .' .image-shadow {
                box-shadow: 9px 9px 0px -6px ' . self::$fair_colors['Accent'] . ';
            }
            .pwelement_'. self::$rnd_id .' .pwe-container-exhibitors-benefits {
                margin: 0 auto;
            }
            .pwelement_'. self::$rnd_id .' .pwe-row-benefits {
                width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .pwelement_'. self::$rnd_id .' .pwe-benefits {
                width: 100%;
                display: flex;
                gap: 36px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-benefit-item {
                width: 33%;
            }
            .pwelement_'. self::$rnd_id .' .pwe-benefit-img img {
                width: 100%;
                border-radius: 18px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-benefit-text p {
                padding:18px 0;
                ' . $text_color . '
            }
            .pwelement_'. self::$rnd_id .' .pwe-button {
                '.$btn_text_color
                .$btn_color
                .$btn_shadow_color.'
                box-shadow: unset !important;
                border-radius: 10px !important;
            }
            .pwelement_'. self::$rnd_id .' .pwe-border-top-left {
                box-shadow: -3px -3px ' . $border_color . ';
                margin-left: -18px;
                width: 170px !important;
                height: 40px;
            }

            .pwelement_'. self::$rnd_id .' .pwe-border-bottom-right {
                box-shadow: 3px 3px ' . $border_color . ';
                margin-right: -18px;
                width: 170px !important;
                height: 40px;
                float: right;
            }

            @media (max-width:570px) {
                .pwelement_'. self::$rnd_id .' .pwe-benefits {
                    flex-direction: column;
                }
                .pwelement_'. self::$rnd_id .' .pwe-benefit-item {
                    width: 100%;
                }
            }
        </style>

            <div id="exhibitorsBenefits" class="pwe-container-exhibitors-benefits">

                <div id="main-content" class="pwe-row-border">
                    <div class="pwe-border-top-left"></div>
                </div>
                    <!-- benefit-container -->'.
                self::languageChecker(
                    <<<PL
                    <div class="pwe-row-benefits">
                        <div class="pwe-benefits" style="justify-content: center;">
                            <div class="pwe-benefit-item">
                                <div class="pwe-benefit-img">
                                    <img src="/wp-content/plugins/pwe-media/media/ulga_pl.png" alt="Strefa Networkingu">
                                </div>
                                <div class="pwe-btn-container" style="padding: 18px;">
                                    <span>
                                        <a class="pwe-button btn btn-accent btn-flat" href="https://warsawexpo.eu/dla-organizatorow/#ulga"  target="_blank">Zobacz szczegóły</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    PL
                )
                .'<div class="pwe-row-benefits">
                    <div class="pwe-benefits">

                    <!-- benefit-item -->
                        <div class="pwe-benefit-item">
                            <div class="pwe-benefit-img">
                                <img src="'. self::multi_translation("benefit_networking_img") .'" alt="'. self::multi_translation("benefit_networking_text") .'">
                            </div>
                            <div class="pwe-benefit-text uncode_text_column pwe-align-left">
                                <p class="pwe-line-height">
                                    '. self::multi_translation("benefit_networking_text") .'
                                </p>
                            </div>
                        </div>

                        <!-- benefit-item -->
                        <div class="pwe-benefit-item">
                            <div class="pwe-benefit-img">
                                <img src="'. self::multi_translation("benefit_panel_img") .'" alt="'. self::multi_translation("benefit_panel_alt") .'">
                            </div>
                            <div class="pwe-benefit-text uncode_text_column pwe-align-left">
                                <p class="pwe-line-height">
                                    '. self::multi_translation("benefit_panel_text") .'
                                </p>
                            </div>
                        </div>

                        <!-- benefit-item -->
                        <div class="pwe-benefit-item">
                            <div class="pwe-benefit-img">
                                <img src="'. self::multi_translation("benefit_welcome_img") .'" alt="'. self::multi_translation("benefit_welcome_alt") .'">
                            </div>
                            <div class="pwe-benefit-text uncode_text_column pwe-align-left">
                                <p class="pwe-line-height">
                                    '. self::multi_translation("benefit_welcome_text") .'
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pwe-row-border">
                    <div class="pwe-border-bottom-right"></div>
                </div>

            </div>';

    return $output;
    }
}


