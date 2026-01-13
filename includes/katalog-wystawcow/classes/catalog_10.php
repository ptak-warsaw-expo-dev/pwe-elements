<?php
class PWECatalog10 extends PWECatalog {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }

    public static function output($atts, $identification) {

        $pwecatalog_display_random = true;
        $file_changer = isset($atts['file_changer']) ? $atts['file_changer'] : null;

        $exhibitors = CatalogFunctions::logosChecker($identification, $atts['format'], $pwecatalog_display_random, $file_changer);


        if ($exhibitors === null) {
            return;
        }

        $output = '';

        $output .= '
        <style>
            .row-container:has(.pwe-registration) .exhibitors-catalog {
                border: 2px solid #564949;
                border-radius: 36px;
                margin-top: 0 !important;
            }
            .row-container:has(.pwe-registration) :is(.wpb_column, .uncol, .uncoltable, .uncont, .exhibitors-catalog, .custom-catalog){
                height: inherit !important;
            }
            .row-container:has(.pwe-registration) #top10 .catalog-custom-title {
                margin-top: 24px !important;
            }
            .row-container:has(.pwe-registration) .img-container-top10 {
                height: 85%;
                padding: 18px;
            }
        </style>';

        if (isset($_SERVER['argv'][0])) {
            $source_utm = $_SERVER['argv'][0];
        } else {
            $source_utm = '';
        }

        if(count($exhibitors) >= 12 && (strpos($source_utm, 'utm_source=byli') !== false || strpos($source_utm, 'utm_source=premium') !== false)){

            $output .= '
            <style>
                .row-container:has(.pwe-registration) .wpb_column:has(#top10) {
                    padding: 36px !important;
                }
                .row-container:has(.pwe-registration) .exhibitors-catalog {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    border: none !important;
                    padding: 0;
                }
                .wpb_column:has(#top10) {
                    width: 33% !important;
                    padding: 54px 36px;
                }
                #top10 {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }
                .img-container-top10 {
                    max-width: 430px;
                }
                .top10-text-container {
                    display: flex;
                    justify-content: center;
                    padding-bottom: 36px;
                }
                .top10-text {
                    max-width: 300px;
                    text-align: center;
                    font-size: 15px;
                    font-weight: 600;
                    line-height: 1.4;
                    margin: 0;
                }
                @media (max-width: 1150px) {
                    .wpb_column:has(#top10) {
                        width: 100% !important;
                        padding: 0;
                    }
                    .exhibitors-catalog:has(#top10) {
                        display: flex;
                        justify-content: space-evenly;
                        align-items: center;
                    }
                }
                @media (min-width: 960px) {
                    .img-container-top10 .cat-img, .img-container-top10 .slides div {
                        min-height: 50px;
                        min-width: 140px;
                        padding: 0;
                        margin: 0;
                    }
                }
                @media (max-width: 960px) {
                    .wpb_column:has(#top10) {
                        width: 100% !important;
                        padding: 0;
                    }
                    .exhibitors-catalog:has(#top10) {
                        display: flex;
                        flex-direction: column;
                    }
                    .top10-text {
                        max-width: 500px;
                    }
                }
            </style>';
            $output .= '
            <div class="top10-text-container">
                <p class="top10-text">'.
                    PWECommonFunctions::languageChecker(
                        <<<PL
                            Po wypełnieniu formularza zostaniesz przekierowany do kroku 2, gdzie otrzymasz dodatkowe informacje dotyczące uczestnictwa w targach.
                        PL,
                        <<<EN
                            After completing the form, you will be redirected to step 2, where you will receive additional information regarding participation in the fair.
                        EN
                    )
                .'</p>
            </div>';
        }

        $output .= '
        <div id="top10" class="custom-catalog main-heading-text">

            <h2 class="catalog-custom-title" style="width: fit-content;">'. CatalogFunctions::checkTitle($atts['katalog_year'], $atts['format']) .'</h2>
            <div class="img-container-top10 pwe-container-logotypes">';
                $logotypes_limit = 12;
                $logotypes_count = 0;
                if (($atts["slider_desktop"] == 'true' && PWECommonFunctions::checkForMobile() != '1' ) || ($atts["grid_mobile"] != 'true' && PWECommonFunctions::checkForMobile() == '1' )) {
                    $slider_array = array();
                    foreach($exhibitors as $exhibitor){
                        if ($logotypes_count >= $logotypes_limit) {
                            break;
                        }

                        $slider_array[] = array(
                            'img' => $exhibitor['URL_logo_wystawcy'],
                            'site' => "https://" . preg_replace('/^(https?:\/\/(www\.)?|(www\.)?)/', '', $exhibitor['www'])
                        );

                        $logotypes_count++;
                    }
                    $images_options = array();
                    $images_options[] = array(
                        "element_id" => self::$rnd_id,
                        "logotypes_dots_off" => $atts["slider_dots_off"]
                    );
                    require_once plugin_dir_path(dirname(dirname(dirname( __FILE__ )))) . 'scripts/logotypes-slider.php';
                    $output .= PWELogotypesSlider::sliderOutput($slider_array, 3000, $images_options);

                } else {
                    foreach ($exhibitors as $exhibitor){
                        if ($logotypes_count >= $logotypes_limit) {
                            break;
                        }

                        $exhibitorsUrl = "https://" . preg_replace('/^(https?:\/\/(www\.)?|(www\.)?)/', '', $exhibitor['www']);
                        $output .= '
                        <a target="_blank" href="'. $exhibitorsUrl .'">
                            <div class="cat-img" style="background-image: url(' . $exhibitor['URL_logo_wystawcy'] . ');"></div>
                        </a>';

                        $logotypes_count++;
                    }
                }
            $output .= '
            </div>

        </div>';

        return $output;
    }
}