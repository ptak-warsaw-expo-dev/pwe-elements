<?php
/**
* Class PWElementOrgInfo
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementOrgInfo extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
        require_once plugin_dir_path(__FILE__) . 'calendarApple.php';
        require_once plugin_dir_path(__FILE__) . 'calendarGoogle.php';
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/org-information.json';

        $translations_data = json_decode(file_get_contents($translations_file), true);

        if (!isset($translations_data[$locale])) {
            $locale = 'en_US';
        }

        $value = $translations_data[$locale][$key] ?? $key;

        if (strpos($value, '.html') !== false) {
            $html_path = __DIR__ . '/../translations/elements/org-information/' . $value;
            if (file_exists($html_path)) {
                return file_get_contents($html_path);
            }
        }

        return $value;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    *
    * @return string @output
    */
    public static function output($atts) {
        $text_color = 'color:' . self::findColor($atts["text_color_manual_hidden"], $atts["text_color"], "black") . '!important;';
        $text_color_shadow = self::findColor($atts["text_shadow_color_manual_hidden"], $atts["text_shadow_color"], "white");

        $output = '';

        $output .= '
            <style>
                .row-parent:has(.pwelement_'. self::$rnd_id .' #orgInfo) {
                    max-width: 100%;
                    padding: 0 !important;
                }

                .pwelement_'. self::$rnd_id .' .orgInfo-header-text{
                    ' . $text_color . '
                    text-shadow: 2px 2px ' . $text_color_shadow . ';
                }
                .row-container:has(.pwe-container-org-info) .row-parent {
                    padding: 0 !important;
                }
                .pwe-container-org-info a {
                    font-weight: 600;
                    color: blue;
                }
                .pwe-org-info-header {
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: cover;
                }
                .pwe-org-info-header h1 {
                    text-align: center;
                    padding: 100px 18px;
                    margin: 0 auto;
                    max-width: 1200px;
                }
                .pwe-org-info-header h1 span {
                    font-size: 54px;
                }
                @media (min-width: 300px) and (max-width: 1200px) {
                    .pwe-org-info-header h1 span {
                        font-size: calc(24px + (54 - 24) * ( (100vw - 300px) / (1200 - 300) ));
                    }
                }
                .pwe-org-info-fixed-width {
                    margin: 0 auto;
                    max-width: 1200px;
                }
                #dane-kontaktowe, #wazne-informacje, #procedury-stoisk, #rozladunek, #dokumenty,
                #dane-kontaktowe_en, #wazne-informacje_en, #procedury-stoisk_en, #rozladunek_en, #dokumenty_en {
                    scroll-margin: 90px;
                }
                .pwe-container-org-info a{
                    text-decoration: underline;
                }';


        if(self::isTradeDateExist() == false){
            $output .= '
            .no-data_info {
                display: none;
            }
            .no-data_remove {
                display: block;
            }';
        } else {
            $output .= '
            .no-data_info {
                display: block;
            }
            .no-data_remove {
                display: none;
            }';
        };

        $output .= '
            </style>

            <div id="orgInfo" class="pwe-container-org-info">
                <div class="pwe-org-info-header pwe-kv-bg" style="background-image: url(' . self::findBestFile("/doc/background") . ');">
                    <h1 class="orgInfo-header-text">
                        <span>'.
                            self::multi_translation("information_for_exhibitors")
                        .'</span>
                    </h1>
                </div>'.
                    self::multi_translation("information_for_exhibitors_text")
            ."</div>";

        return $output;
    }
}