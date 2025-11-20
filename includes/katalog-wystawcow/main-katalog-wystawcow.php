<?php

class PWECatalog {
    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        require_once plugin_dir_path(__FILE__) . 'classes/catalog_functions.php';

        

        self::$rnd_id = rand(10000, 99999);
        self::$fair_colors = PWECommonFunctions::findPalletColorsStatic();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos($color_key, 'main2') != false){
                self::$main2_color = $color_value;
            }
        }
        add_action('wp_enqueue_scripts', array($this, 'addingStyles'));
        add_action('wp_enqueue_scripts', array($this, 'addingScripts'));

        add_action('init', array($this, 'initVCMapElements'));

        add_shortcode('pwe_katalog', array($this, 'PWECatalogOutput'));
    }

    public function initVCMapElements() {
        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __( 'PWE Katalog wystawców', 'pwe_katalog'),
                'base' => 'pwe_katalog',
                'category' => __( 'PWE Elements', 'pwe_katalog'),
                'admin_enqueue_css' => plugin_dir_url(dirname(dirname( __DIR__ ))) . 'backend/backendstyle.css',
                //Add all vc_map PWECatalog files
                'params' => array_merge(
                    array(
                        ...CatalogFunctions::initVCMapPWECatalog(),
                        ...CatalogFunctions::vcMapPWECatalogCustom(),
                    )
                )
            ));
        }
    }

    /**
     * Adding Styles
     */
    function addingStyles(){
        $css_file = plugins_url('assets/katalog.css', __FILE__);
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/katalog.css');
        wp_enqueue_style('pwe-katalog-css', $css_file, array(), $css_version);
    }


    /**
     * Adding Scripts
     */
    function addingScripts($atts){
        $js_file = plugins_url('assets/katalog.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/katalog.js');
        wp_enqueue_script('pwe-katalog-js', $js_file, array('jquery'), $js_version, true);
    }


    /**
     * Output method for pwe_katalog shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWECatalogOutput($atts, $content = null) {

        $exh_catalog_cron_pass = PWECommonFunctions::get_database_meta_data('cron_secret_pass');

        if (current_user_can('administrator')) {
            echo '<script>console.log("Link do odświeżenia katalogu: https://'. do_shortcode('[trade_fair_domainadress]') .'/wp-content/plugins/custom-element/other/mass_vip_cron.php?pass=' . $exh_catalog_cron_pass . '")</script>';
        };

        $btn_text_color = PWECommonFunctions::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = PWECommonFunctions::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important';
        $btn_shadow_color = PWECommonFunctions::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black') . '!important';
        $btn_border = PWECommonFunctions::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important';

        // pwe_katalog output
        extract( shortcode_atts( array(
            'format' => '',
        ), $atts ));

        $darker_btn_color = PWECommonFunctions::adjustBrightness($btn_color, -20);

        if ($format == 'PWECatalogFull'){
            $text_color = PWECommonFunctions::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important';
            $btn_text_color = PWECommonFunctions::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'black') . '!important';
        } else {
            $text_color = PWECommonFunctions::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important';
            $btn_text_color = PWECommonFunctions::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        }

        $slider_path = dirname(plugin_dir_path(__FILE__)) . '/scripts/slider.php';

        if (file_exists($slider_path)){
            include_once $slider_path;
        }

        if (!empty($atts['identification'])) {
            $identification = $atts['identification'];
        } else {
            $identification = do_shortcode('[trade_fair_catalog]');
        }
        $catalog_format = CatalogFunctions::findClassElements()[$format];

        if ($catalog_format){
            require_once plugin_dir_path(__FILE__) . $catalog_format;

            if (class_exists($format) && $identification) {
                $output_class = new $format;
                $output = $output_class->output($atts, $identification, $content);
            } else {
                echo '<script>console.log("Class '. $format .' or ID , does not exist")</script>';
                $output = '';
            }
        } else {
            echo '<script>console.log("Select a Catalog Format")</script>';
        }

        $output_html = '';

        if ($format == 'PWECatalog10') {
            $exhibitors_top10 = ($identification) ? CatalogFunctions::logosChecker($identification, "PWECatalog10") : 0;
            if ($exhibitors_top10 === null){
                if (current_user_can('administrator')) {
                    return '<p>Błędny numer katalogu wystawców</p>';
                } else {
                    return '<style>.row-container:has(.catalog-not-found-' . self::$rnd_id . '){display:none !important;}</style><div class="catalog-not-found-' . self::$rnd_id . '"></div>';
                }
            }
        }
        
        if ((empty($identification) || (is_array($exhibitors_top10) && count($exhibitors_top10) < 12)) && $format == 'PWECatalog10') {
            if (isset($_SERVER['argv'][0])) {
                $source_utm = $_SERVER['argv'][0];
            } else {
                $source_utm = '';
            }

            $current_page = $_SERVER['REQUEST_URI'];

            if (strpos($source_utm, 'utm_source=byli') !== false || strpos($source_utm, 'utm_source=premium') !== false) {
                $output_html .= '
                <style>
                    .row-container:has(.pwe-registration) .wpb_column:has(#katalog-'. self::$rnd_id .') {
                        position: relative;
                        background-image: url(/doc/header_mobile.webp);
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: cover;
                        padding: 0;
                    }
                    .row-container:has(.pwe-registration) .wpb_column:has(#katalog-'. self::$rnd_id .'):before {
                        content: "";
                        position: absolute;
                        top: 60%;
                        right: 0;
                        bottom: 0;
                        left: 0;
                        margin: auto;
                        max-width: 300px;
                        width: 90%;
                        background-image: url(/doc/logo.webp);
                        background-repeat: no-repeat;
                        background-size: contain;
                        transform: translateY(-60%);
                    }
                </style>';
            } else if (strpos($current_page, 'zostan-wystawca') || strpos($current_page, 'become-an-exhibitor')) {
                $output_html .= '
                <style>
                    .row-container:has(.pwe-registration) .wpb_column:has(#katalog-'. self::$rnd_id .'),
                    .row-container:has(.pwe-registration) .wpb_column:has(#katalog-'. self::$rnd_id .') * {
                        width: 100%;
                        height: 100%;
                    }
                    #katalog-'. self::$rnd_id .' {
                        position: relative;
                        background-image: url(/doc/header_mobile.webp);
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: cover;
                        padding: 0;
                    }
                    #katalog-'. self::$rnd_id .' #top10 {
                        display: none;
                    }
                    #katalog-'. self::$rnd_id .':before {
                        content: "";
                        position: absolute;
                        top: 60%;
                        right: 0;
                        bottom: 0;
                        left: 0;
                        margin: auto;
                        max-width: 300px;
                        width: 90%;
                        background-image: url(/doc/logo.webp);
                        background-repeat: no-repeat;
                        background-size: contain;
                        transform: translateY(-60%);
                    }
                </style>';
            } else {
                $output_html .= '
                <style>
                    .row-container:has(.pwe-registration) .wpb_column:has(#katalog-'. self::$rnd_id .') {
                        display: none !important;
                    }
                </style>';
            }
        }

        $output_html .= '
            <style>
                #katalog-'. self::$rnd_id .' .pwe-text-color {
                    text-align: center;
                    color:' . $text_color . ';
                }
                #katalog-'. self::$rnd_id .' .custom-link {
                    color: '. $btn_text_color .';
                    background-color: '. $btn_color .';
                    border: 1px solid '. $btn_border .';
                }
                #katalog-'. self::$rnd_id .' .custom-link:hover {
                    color: '. $btn_text_color .';
                    background-color: '. $darker_btn_color .'!important;
                    border: 1px solid '. $darker_btn_color .'!important;
                }
            </style>

            <div id="katalog-' . self::$rnd_id . '" class="exhibitors-catalog">' . $output . '</div>
            
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const pweContainerLogotypes = document.querySelector("#katalog-' . self::$rnd_id . ' .pwe-container-logotypes");

                    if (pweContainerLogotypes && pweContainerLogotypes.children.length === 0) {
                        const loader = document.createElement("div");
                        loader.className = "pwe-loader";
                        pweContainerLogotypes.appendChild(loader);
                    }
                });
            </script>';

        $output_html = do_shortcode($output_html);

        return $output_html;
    }
}