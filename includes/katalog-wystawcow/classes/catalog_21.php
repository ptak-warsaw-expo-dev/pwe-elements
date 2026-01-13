<?php
class PWECatalog21 extends PWECatalog {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }

    public static function output($atts, $identification) {
        $pwecatalog_display_random = isset($atts['pwecatalog_display_random1']) ? $atts['pwecatalog_display_random1'] : false;
        $file_changer = isset($atts['file_changer']) ? $atts['file_changer'] : null;
        
        $exhibitors = CatalogFunctions::logosChecker($identification, $atts['format'], $pwecatalog_display_random, $file_changer);

        if ($exhibitors === null){ 
            return;
        }

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);
        $output = '';

        if ($mobile) {
            $output .= '
            <style>
                .wpb_column:has(#katalog-' .self::$rnd_id. '){
                    padding: 0 !important;
                }
            </style>';
        }

        $output .= '
            <div id="top21" class="custom-catalog main-heading-text">';

            
            
            if (!$mobile){
                $output .= '<h2 class="catalog-custom-title" style="width: fit-content;">'. CatalogFunctions::checkTitle($atts['katalog_year'], $atts['format']).'</h2>';
            }
            $output .= '
                <div class="img-container-top21 pwe-container-logotypes">';                
                    if (($atts["slider_desktop"] == 'true' && PWECommonFunctions::checkForMobile() != '1' ) || ($atts["grid_mobile"] != 'true' && PWECommonFunctions::checkForMobile() == '1' )){
                        $slider_array = array();
                        foreach($exhibitors as $exhibitor){
                            $slider_array[] = array(
                                'img' => $exhibitor['URL_logo_wystawcy'],
                                'site' => "https://" . preg_replace('/^(https?:\/\/(www\.)?|(www\.)?)/', '', $exhibitor['www'])
                            );
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
                            $exhibitorsUrl = "https://" . preg_replace('/^(https?:\/\/(www\.)?|(www\.)?)/', '', $exhibitor['www']);
                            $output .= '
                                <a target="_blank" href="'. $exhibitorsUrl .'">
                                    <div class="cat-img" style="background-image: url(' . $exhibitor['URL_logo_wystawcy'] . ');"></div>
                                </a>';
                        }
                    }

        $output .= '
                </div>
                <div>
                    <span style="display: flex; justify-content: center;" class="btn-container">'.
                      PWECommonFunctions::languageChecker(
                            <<<PL
                                <a href="/katalog-wystawcow/" class="custom-link btn border-width-0 btn-accent" title="Katalog wystawców">ZOBACZ PEŁNY KATALOG</a>
                            PL,
                            <<<EN
                                <a href="/en/exhibitors-catalog/" class="custom-link btn border-width-0 btn-accent" title="Exhibitor Catalog">SEE FULL CATALOG</a>
                            EN
                        )
                    .'</span>
                </div>
            </div>';
        return $output;
    }
}