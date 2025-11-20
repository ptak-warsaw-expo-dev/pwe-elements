<?php
/**
* Class PWElementFaq
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementFaq extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/faq.json';

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
        $text_color = 'color: ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important;';
        $title_shadow_color = 'box-shadow: 9px 9px 0px -6px ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important;';
        $border_color = 'border-bottom: 1px solid ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important;';

        $output = '';

        $output = '
            <style>
                .row-parent:has(.pwelement_'. self::$rnd_id .' #faq) {
                    max-width: 100%;
                    padding: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-faq-wrapper {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pytanie-odpowiedz{
                    max-width: 750px;
                    ' . $border_color . '
                }
                .pwelement_'. self::$rnd_id .' .pwe-container-faq h4 {
                        padding: 0 10px 5px 0;
                        ' . $title_shadow_color . '
                }
                .pwelement_'. self::$rnd_id .' .pytanie::after{
                    content: ">";
                    float: right;
                    top: 50%;
                    transform: rotate(0);
                    transition: transform 200ms ease-out;
                }
                .pwelement_'. self::$rnd_id .' .active.pytanie::after{
                    transform: rotate(90deg);
                }
                .pwelement_'. self::$rnd_id .' .pytanie {
                    cursor:pointer;
                }
                .pwelement_'. self::$rnd_id .' .odpowiedz {
                    padding-top: 0px !important;
                    margin-left: 20px;
                    display: none;
                }
                .pwelement_'. self::$rnd_id .' #faq :is(.pytanie, .odpowiedz, a, h4) {
                    ' . $text_color . '
                }
            </style>

            <div id="faq" class="pwe-container-faq style-accent-bg pwe-align-left">
                <div class="pwe-faq-wrapper">
                    <div class="heading-text el-text half-block-padding">
                        <h4>'. self::multi_translation("questions") .'</h4>
                    </div>

                    <div class="container-pytan half-block-padding link-text-underline">
                        <div class="pytanie-odpowiedz pytanie-odpowiedz-1">
                            <div class="pytanie half-block-padding">'. self::multi_translation("q1_question") .'</div>
                            <div class="odpowiedz half-block-padding">'. self::multi_translation("q1_answer") .'</div>
                        </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-2">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q2_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q2_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-3">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q3_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q3_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-4">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q4_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q4_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-5">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q5_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q5_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-6">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q6_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q6_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-7">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q7_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q7_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-8">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q8_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q8_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-9">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q9_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q9_answer") .'</div>
                            </div>
                            <div class="pytanie-odpowiedz pytanie-odpowiedz-10">
                                <div class="pytanie half-block-padding">'. self::multi_translation("q10_question") .'</div>
                                <div class="odpowiedz half-block-padding">'. self::multi_translation("q10_answer") .'</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                if (document.querySelector(".pwe-container-faq")) {
                    jQuery(function ($) {
                    $(".pytanie").click(function (event) {
                        $(event.target.nextElementSibling).slideToggle();
                        $(event.target).toggleClass("active");
                    });
                    });
                }
            </script>';

        return $output;
    }
}