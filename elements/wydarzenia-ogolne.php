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
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => esc_html__('Get conferance from CAP', 'pwelement'),
                'param_name' => 'conf_cap',
                'description' => __('Set Yes if You want to get all elements from central data baze', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConferences',
                ),
            ),
        );

        return $element_output;
    }
    
    /**
    * Creating the connection to data dase.
    * 
    * @return wpdb Obiekt bazy danych
    */
    private static function connectToDatabase() {

        if ($_SERVER['SERVER_ADDR'] != '94.152.207.180') {
            $database_host = 'localhost';
            $database_name = 'warsawexpo_dodatkowa';
            $database_user = 'warsawexpo_admin-dodatkowy';
            $database_password = 'N4c-TsI+I4-C56@q';
        } else {
            $database_host = 'localhost';
            $database_name = 'automechanicawar_dodatkowa';
            $database_user = 'automechanicawar_admin-dodatkowa';
            $database_password = '9tL-2-88UAnO_x2e';
        }
        $custom_db = new wpdb($database_user, $database_password, $database_name, $database_host);

        return $custom_db;
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
     * Static method to generate the HTML output_general for the PWE Element.
     * Returns the HTML output_general as a string.
     * 
     * @param array @pre_data prelection
     * @return string @output html
     */
    private static function cap_css() {
        $css_output = '
            <style>
                .pwe-conf-day-selector{
                    display: flex;
                    justify-content: space-evenly;                
                }
                .pwe-conf-day-selector .active{
                    font-weight: 800;
                    color: ' . self::$accent_color . ';
                }
                .pwe-conf-day-select{
                    cursor: pointer;
                }
                .pwe-conf-pre{
                    display: flex;
                    flex-direction: row;
                    margin-bottom: 18px;
                    align-items: center;
                }
                .pwe-conf-head-container{
                    width: 30%;
                }
                .pwe-conf-pre-text-container{
                    text-align: left;
                    width: 70%;
                }
                .pwe-conf-pre-text-container :is(h3, h4, h5, p){
                    margin-top: 18px;
                }
                .speakers-img{
                    display: flex;
                    gap: 27px;
                    flex-direction: column;
                    align-items: center;
                }
                img{
                    position: relative;
                    border-radius: 50%;
                    z-index: 1;
                    top: unset;
                    left: unset;
                    width: 40%;
                }
            </style>
        ';

        return $css_output;
    }

    /**
     * Static method to generate the HTML output_general for the PWE Element.
     * Returns the HTML output_general as a string.
     * 
     * @param array @pre_data prelection
     * @return string @output html
     */
    private static function cap_js() {
        $css_output = '
            <script>
                jQuery(document).ready(function ($){
                    $(".pwe-conf-day-select").on("click", function(){
                        $(".pwe-conf-day-select").each(function(){
                            $(this).removeClass("active");
                        })
                        $(".pwe-conf-day").each(function(){
                            $(this).hide();
                        })
                        const classChecker = $(this).attr("id");
                        console.log($("." + classChecker));
                        $("." + classChecker).show();
                        $(this).addClass("active");
                    })

                })
            </script>
        ';

        return $css_output;
    }

    /**
     * Static method to generate the HTML output_general for the PWE Element.
     * Returns the HTML output_general as a string.
     * 
     * @param array @pre_data prelection
     * @return string @output html
     */
    private static function add_days($day_data) {
        $day_count = 1;
        $day_count_pre = 1;
        $day_output .= '
            <div class="pwe-conf-day-selector">
        ';

        foreach ($day_data as $key => $value) {         
            $day_output .= '
                <div class="pwe-conf-day-select" id="pwe-conf-day-' . $day_count . '">
                    <p>' . $key . '</p>
                </div>
            ';
            $day_count++;
        }

        $day_output .= '
            </div>
            <hr class="pwe-conf-break">
        ';

        foreach ($day_data as $value) {
            $day_output .= '
                <div class="pwe-conf-day pwe-conf-day-' . $day_count_pre . '">
                    ' . self::add_prelection($value) . '
                </div>
            ';
            $day_count_pre++;
        }
        return $day_output;
    }

    /**
     * Static method to generate the HTML output_general for the PWE Element.
     * Returns the HTML output_general as a string.
     * 
     * @param array @pre_data prelection
     * @return string @output html
     */
    private static function add_prelection($pre_data) {
        $pre_count = 1;
        foreach ($pre_data as $key => $value){
            $pre_output .='
                <div class="pwe-conf-pre">
                    <div class="pwe-conf-head-container">
                        <div class="speakers-img">';
                            foreach($value as $url_data){
                                if($url_data->url){
                                    $pre_output .='<img class="speake" src="' . $url_data->url . '">';
                                }
                                if($url_data->desc){
                                    $pre_output .='<button class="pwe-conf-lecturer-bio-btn btn btn-sm">BIO</button>';
                                }
                            }
            $pre_output .='
                        </div>
                    </div>
                    <div class="pwe-conf-pre-text-container">
                        <h4 class="lectur-time">' . $value->time . '</h4>
                        <h5 class="lecturer-name">';
                    foreach($value as $lect_key => $lect_data){
                        if (strpos($lect_key, 'legent') === 0 && $lect_data->name != '*') {
                            $pre_output .= $lect_data->name . '<br>';
                        }
                    }
            $pre_output .='
                        </h5>
                        <h3 class="lectur-title">' . $value->title . '</h3>
                        <div class="inside-text">
                            <p>' . $value->desc . '</p>
                        </div>
                    </div>
                </div>
                <hr>
            ';
            $day_count_pre++;
        }
        return $pre_output;
    }

    /**
     * Static method to generate the HTML output_general for the PWE Element.
     * Returns the HTML output_general as a string.
     * 
     * @param array @atts options
     * @return string @output html
     */
    private static function output_cap($atts) {
        $custom_db = self::connectToDatabase();

        $name = do_shortcode('[trade_fair_name]');
        $prepared_query = $custom_db->prepare("SELECT primary_fair FROM associates WHERE primary_fair = %s OR side1 = %s OR side2 = %s OR side3 = %s OR side4 = %s OR side5 = %s OR side6 = %s LIMIT 1", $name, $name, $name, $name, $name, $name, $name, $name);

        $results = $custom_db->get_results($prepared_query);
        $primary_fair = $results[0]->primary_fair;

        $prepared_query = $custom_db->prepare("SELECT * FROM conferances WHERE conf_main_fair = %s", $primary_fair);

        $conf_data = $custom_db->get_results($prepared_query);
        $data = json_decode($conf_data[0]->conf_data);

        $output_cap .= self::cap_css();
        $output_cap .= self::cap_js();

        $output_cap .= '
            <div class="conf-data text-centered">
                <div class="conf-header">
                <h3> Program Kongresu </h3>
                <p>Wybierz dzień kongresu aby sprawdzić program</p>
            </div>
        ';

        $output_cap .= self::add_days($data);

        return $output_cap;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     */
    public static function output($atts) {
        if($atts['conf_cap'] != true){
            return self::output_general($atts);
        } else {
            return self::output_cap($atts);
        }
    }
}