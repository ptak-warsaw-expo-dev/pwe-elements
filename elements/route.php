<?php

/**
 * Class PWElementRoute
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementRoute extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/route.json';

        // JSON file with translation
        $translations_data = json_decode(file_get_contents($translations_file), true);

        // Is the language in translations
        if (isset($translations_data[$locale])) {
            $translations_map = $translations_data[$locale];
        } else {
            // By default use English translation if no translation for current language
            $translations_map = $translations_data['en_EN'];
        }

        // Return translation based on key
        return isset($translations_map[$key]) ? $translations_map[$key] : $key;
    }

    public static function output($atts, $content = '') {
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';

        $output = '';

        $output .='
            <style>
                .pwelement_'. self::$rnd_id .' #dojazd :is(h4, h5, p){
                    ' . $text_color . '
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-title-wrapper h4 {
                    width: auto !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-transport-item-img {
                    display: flex;
                    align-items: center;
                    padding-right: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-transport-item-img img {
                    width: 60px !important;
                    min-width: 60px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-image-bg {
                    aspect-ratio: 16/9;
                    background-position: center;
                    background-size: cover;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-image-bg h3 {
                    font-size: 22px !important;
                    max-width: 90%;
                    padding: 8px;
                    margin: 0;
                    color: white;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-area-wrapper {
                    padding-top: 36px;
                    display: flex;
                    gap: 36px;
                    flex-direction: column;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-area-block {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    gap: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-area-block img {
                    width: 80px;
                    padding: 0 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-area-item-text {
                    align-items: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-area-item-text h5 {
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-route-transport-block{
                    box-shadow: 9px 9px 0px -5px black;
                    border:2px solid;
                    padding:25px 25px !important;
                }

                @media (max-width:960px) {
                    .pwelement_'. self::$rnd_id .' #route {
                        padding: 36px 0;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-transport-wrapper,
                    .pwelement_'. self::$rnd_id .' .pwe-route-area-wrapper {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-half-width {
                        width: 100% !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-image-bg h3 {
                        font-size: 18px !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-area-block {
                        padding: 36px 0;
                    }
                }
                @media (max-width:600px) {
                    .pwelement_'. self::$rnd_id .' .pwe-align-center {
                        font-size: 16px !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-transport-item {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-transport-block h5{
                        margin: 0;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-transport-block img{
                        margin: 27px 0 0;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-transport-item-text {
                        text-align: center;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-transport-item-img,
                    .pwelement_'. self::$rnd_id .' .pwe-route-transport-item-text h5 {
                        padding: 0;
                        width: inherit !important;
                        justify-content: center;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-route-image-bg h3 {
                        font-size: 16px !important;
                    }
                }
            </style>

            <div id="dojazd" class="pwe-container-route">
                <div class="pwe-route-title-wrapper">
                    <h4 class="pwe-align-center">'. self::multi_translation("title") .'</h4>
                </div>
                <div class="pwe-route-transport-wrapper pwe-align-left single-top-padding pwe-full-gap">
                    <div class="pwe-route-map-block pwe-half-width">
                        <img class="pwe-full-width" src="/wp-content/plugins/pwe-media/media/mapka-wawa.png">
                        <div class="pwe-route-area-wrapper pwe-align-left">
                            <div class="pwe-route-image-bg-block">
                                <div style="background-image: url(/wp-content/plugins/pwe-media/media/ptak.jpg);" class="pwe-route-image-bg shadow-black">
                                    <h3>'. self::multi_translation("big_header") .'</h3>
                                </div>
                            </div>
                            <div class="pwe-route-area-block pwe-route">
                                <div class="pwe-route-area-item pwe-flex">
                                    <div class="pwe-route-area-item-img">
                                        <img src="/wp-content/plugins/pwe-media/media/entry.png">
                                    </div>
                                    <div class="pwe-route-area-item-text pwe-flex">
                                        <h5>'. self::multi_translation("area_1") .'</h5>
                                    </div>
                                </div>

                                <div class="pwe-route-area-item pwe-flex">
                                    <div class="pwe-route-area-item-img">
                                        <img src="/wp-content/plugins/pwe-media/media/leave.png">
                                    </div>
                                    <div class="pwe-route-area-item-text pwe-flex">
                                        <h5>'. self::multi_translation("area_2") .'</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="pwe-route-transport-block pwe-half-width pwe-route">
                        <div class="pwe-route-transport-item pwe-flex">
                            <div class="pwe-route-transport-item-img">
                                <img class="pwe-full-width" src="/wp-content/plugins/pwe-media/media/samolot.png">
                            </div>
                            <div class="pwe-route-transport-item-text">
                                <h4>'. self::multi_translation("plane_title") .'</h4>
                                <p>'. self::multi_translation("plane_text") .'</p>
                            </div>
                        </div>
                        <div class="pwe-route-transport-item pwe-flex">
                            <div class="pwe-route-transport-item-img">
                                <img class="pwe-full-width" src="/wp-content/plugins/pwe-media/media/train.png">
                            </div>
                            <div class="pwe-route-transport-item-text">
                                <h4>'. self::multi_translation("train_title") .'</h4>
                                <p>'. self::multi_translation("train_text") .'</p>
                            </div>
                        </div>

                        <div class="pwe-route-transport-item pwe-flex">
                            <div class="pwe-route-transport-item-img">
                                <img class="pwe-full-width" src="/wp-content/plugins/pwe-media/media/bus.png">
                            </div>
                            <div class="pwe-route-transport-item-text">
                                <h4>'. self::multi_translation("bus_title") .'</h4>
                                <p>'. self::multi_translation("bus_text") .'</p>
                            </div>
                        </div>

                        <div class="pwe-route-transport-item pwe-flex">
                            <div class="pwe-route-transport-item-img">
                                <img class="pwe-full-width" src="/wp-content/plugins/pwe-media/media/sedan.png">
                            </div>
                            <div class="pwe-route-transport-item-text">
                                <h4>'. self::multi_translation("car_title") .'</h4>
                                <p>'. self::multi_translation("car_text") .'</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        return $output;
    }
}