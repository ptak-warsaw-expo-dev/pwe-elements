<?php

/**
 * Class PWElementConferences
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementConferences extends PWElements {

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
        $element_output = array(
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => esc_html__('Logotypes catalog', 'pwelement'),
                'param_name' => 'conf_logotypes_catalog',
                'description' => __('Put catalog name in /doc/ where are logotypes.', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConferences',
                ),
            )
        );

        return $element_output;
    }

    /**
     * Static method to generate the HTML output_general for the PWE Element.
     * Returns the HTML output_general as a string.
     *
     * @param array @atts options
     * @return string @output html
     */
    public static function output_general($atts) {


        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important';

        require_once plugin_dir_path(__FILE__) . '/../logotypes/logotypes-additional.php';

        if ($text_color == '' || $text_color == '#000000' || $text_color == 'black') {
            $text_shadow = 'white !important;';
        } else {
            $text_shadow = 'black !important;';
        }

        extract( shortcode_atts( array(
            'conf_logotypes_catalog' => '',
        ), $atts ));

        $output = '
            <style>
                .row-parent:has(.pwelement_' . self::$rnd_id . ') {
                    max-width: 100% !important;
                    padding: 0 !important;
                }
                .pwelement_' . self::$rnd_id . ' #pweConferences h1{
                    color: '. $text_color .';
                    text-shadow: 2px 2px '. $text_shadow .';
                }
                .pwelement_'. self::$rnd_id .' .pwe-conferences-header {
                    background-image:url("'. (file_exists($_SERVER["DOCUMENT_ROOT"] . "/doc/background.webp") ? "/doc/background.webp" : "/doc/background.jpg") .'");
                    background-position: center;
                    background-size: cover;
                    background-repeat: no-repeat;
                    padding: 100px 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conferences-header h1 {
                    max-width: 1128px;
                    margin: 0 auto;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conferences-header h1 span {
                    font-size: 54px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conferences-logotypes h2 {
                    margin: 0;
                }
                @media (min-width: 300px) and (max-width: 1200px) {
                    .pwelement_'. self::$rnd_id .' .pwe-conferences-header h1 span {
                        font-size: calc(24px + (54 - 24) * ( (100vw - 300px) / (1200 - 300) ));
                    }
                }
                .pwelement_'. self::$rnd_id .' .pwe-conferences-logotypes .custom-logotypes-gallery-wrapper{
                    padding-top: 0 !important;
                }
            </style>

            <div id="pweConferences" class="pwe-conferences">
                <div class="pwe-conferences-header">
                    <h1 class="text-uppercase text-centered">
                        <span>'.
                            self::languageChecker(
                                <<<PL
                                Kongres Branży<br>[trade_fair_opisbranzy]
                                PL,
                                <<<EN
                                Trade congress<br>[trade_fair_opisbranzy_eng]
                                EN
                            )
                        .'</span>
                    </h1>
                </div>
                <div class="custom-width-limit single-block-padding">'.
                    self::languageChecker(
                        <<<PL
                            <p>Zapraszamy na specjalistyczną Konferencję, która odbędzie się w ramach międzynarodowych targów [trade_fair_name]. Tematyka spotkania skupiać się będzie na najnowszych trendach, innowacjach i wyzwaniach w branży [trade_fair_opisbranzy]. W jej programie znajdziecie Państwo prelekcje ekspertów, panele dyskusyjne, warsztaty oraz prezentacje firm powiązanych sektorem.</p>
                            <p>Nasze forum dyskusyjne będzie integralną częścią targów, co pozwoli uczestnikom na korzystanie z pełnego wachlarza możliwości, jakie niesie za sobą międzynarodowa wystawa. Udział w nim to doskonała okazja, aby podyskutować z liderami rynkowymi, nawiązać cenne kontakty biznesowe i poznać najnowsze trendy z branży.</p>
                            <p>Celem wydarzenia jest stworzenie platformy spotkań i rozmów dla specjalistów, przedsiębiorców i pasjonatów branży, którzy chcą wymieniać się wiedzą, doświadczeniami oraz nawiązywać nowe kontakty biznesowe.</p>
                            <p>Ramowy program Konferencji podczas [trade_fair_name]:</p>
                        PL,
                        <<<EN
                            <p>We invite you to a specialized Conference that will take place as part of the international [trade_fair_name_eng] trade fair. The meeting will focus on the latest trends, innovations, and challenges in the [trade_fair_opisbranzy_eng] industry. The conference program will include expert lectures, panel discussions, workshops, and presentations by companies associated with the sector.</p>
                            <p>Our discussion forum will be an integral part of the trade fair, allowing participants to take advantage of the full range of opportunities that an international exhibition offers. Participating in the forum is an excellent opportunity to engage in discussions with industry leaders, establish valuable business contacts, and learn about the latest trends in the field.</p>
                            <p>The event aims to provide a platform for industry professionals, entrepreneurs and enthusiasts to meet and talk with each other to exchange knowledge, experiences and make new business contacts.</p>
                            <p>Outline of the Conference program during [trade_fair_name_eng]:</p>
                        EN
                    )
                    .'<ul>'.
                        self::languageChecker(
                            <<<PL
                                <li>Najnowsze trendy i innowacje w branży [trade_fair_opisbranzy]</li>
                                <li>Digitalizacja i automatyzacja sektora</li>
                                <li>Nowe technologie w produkcji i procesach logistycznych</li>
                                <li>Wyzwania związane z zrównoważonym rozwojem branży [trade_fair_opisbranzy]</li>
                                <li>Przykłady dobrych praktyk – prezentacje firm</li>
                                <li>Przyszłość branży [trade_fair_opisbranzy]: prognozy i wyzwania</li>
                                <li>Zarządzanie produktem: najlepsze praktyki i trendy</li>
                                <li>Inwestycje i finansowanie w sektorze branżowym</li>
                            PL,
                            <<<EN
                                <li>Latest trends and innovations in the [trade_fair_opisbranzy_eng] industry</li>
                                <li>Digitalization and automation in the sector</li>
                                <li>New technologies in production and logistics processes</li>
                                <li>Challenges related to sustainable development in the [trade_fair_opisbranzy_eng] industry</li>
                                <li>Best practice examples - company presentations</li>
                                <li>The future of the [trade_fair_opisbranzy_eng] industry: forecasts and challenges</li>
                                <li>Product management: best practices and trends</li>
                                <li>Investments and financing in the industry sector</li>
                            EN
                        )
                    .'</ul>
                    <p>'.
                        self::languageChecker(
                            <<<PL
                            Te tematy pomogą uczestnikom poznać aktualne zmiany w branży [trade_fair_opisbranzy], a także nawiązać cenne kontakty biznesowe i wymienić się wiedzą z innymi specjalistami z tej dziedziny.
                            PL,
                            <<<EN
                            These topics will help participants understand the current changes in the [trade_fair_opisbranzy_eng] industry, establish valuable business contacts, and exchange knowledge with other specialists in the field.
                            EN
                        )
                    .'</p>
                </div>';

                $logotypes_catalog_path = $_SERVER['DOCUMENT_ROOT'] . '/doc/' . $conf_logotypes_catalog;
                $files = glob($logotypes_catalog_path . '/*');
                if(!empty($conf_logotypes_catalog) && is_dir($logotypes_catalog_path) && count($files) > 0) {
                    $output .= '<div class="pwe-conferences-logotypes text-centered custom-width-limit single-block-padding">
                        <h2>'.
                            self::languageChecker(
                                <<<PL
                                PARTNERZY MEDIALNI
                                PL,
                                <<<EN
                                MEDIA PATRONAGE
                                EN
                            )
                        .'</h2>';
                        $output .= PWElementAdditionalLogotypes::additionalOutput($atts, $logotypes);
                    $output .= '</div>';
                }
            $output .= '</div>

            <script>

                const congresSections = document.querySelectorAll(`[class^="konferencja-"]`);
                const pweConferences = document.querySelector(".row-container:has(.pwe-conferences)");
                const pageHeader = document.querySelector("#page-header");
                congresSections.forEach(function(section) {
                    const classList = section.classList;
                    // Znalezienie klasy zaczynającej się od "konferencja-"
                    const congresClass = Array.from(classList).find(cls => cls.startsWith("konferencja-"));
                    if (congresClass) {
                        const congresYear = congresClass.slice(-4);
                        const currentDate = new Date().getTime();
                        const tradeStart = new Date("'. $trade_start .'").getTime();
                        const tradeEnd = new Date("'. $trade_end .'").getTime();
                        if ((tradeStart - currentDate) <= 10368000000 && pweConferences.classList.contains("desktop-hidden")) {
                            section.classList.add("desktop-hidden", "tablet-hidden", "mobile-hidden");
                            pweConferences.classList.remove("desktop-hidden", "tablet-hidden", "mobile-hidden");
                            if (pageHeader) {
                                pageHeader.style.display = "none";
                            }
                        }
                    }
                });

            </script>';
            return $output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {
        return self::output_general($atts); 
    }
}