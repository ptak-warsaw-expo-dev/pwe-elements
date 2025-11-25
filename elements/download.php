<?php

/**
 * Class PWElementDonwload
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementDonwload extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/dokumenty.json';

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
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');

        $filter = ($text_color != 'white') ? '.pwelement_'.self::$rnd_id.' #download img { filter: invert(100%); }' : '';

        $output = '';

        $output .= '
        <style>
            #download {
                display:flex;
                align-items: center;
                color:white;
                border: 0;
                max-width: 500px;
                margin: auto;
                border-radius: 18px;
            }
            .pwelement_'.self::$rnd_id.' #download :is(h3, a){
                color: '.$text_color.' !important;
            }

            ' . $filter . '

            @media (max-width:959px){
                .t-m-display-none{
                    display:none;
                }
            }
        </style>

        <div id="download" class="pwe-download-container style-accent-bg single-block-padding">
            <div class="single-media-wrapper wpb_column t-m-display-none half-block-padding" style="flex:1;">
                <img src="/wp-content/plugins/pwe-media/media/download-icon.png" alt="ikonka pobierania"/>
            </div>

            <div style="flex:5">
                <div class="heading-text el-text text-centered">
                    '. self::multi_translation("documents") .'
                </div>

                <div>
                    <p class="text-centered">
                        '. self::multi_translation("fair_regulations") .'
                        '. self::multi_translation("facility_regulations") .'
                        '. self::multi_translation("building_regulations") .'
                        '. self::multi_translation("voucher") .'
                 </p>
                </div>
            </div>
        </div>';

        return $output;
    }
  }