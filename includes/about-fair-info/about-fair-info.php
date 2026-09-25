<?php

/**
 * PWEAboutFairInfo Class
 *
 * This class handles the exhibitor generator functionality for the PWE plugin.
 * It manages forms, color schemes, shortcode generation, and integrates with external
 * classes to generate exhibitor guest and staff data.
 */

class PWEAboutFairInfo {

    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;
    public static $local_lang_pl;
    private $atts;

    public function __construct() {
        $pweComonFunction = new PWECommonFunctions;
        self::$rnd_id = rand(10000, 99999);
        self::$fair_colors = $pweComonFunction->findPalletColors();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';
        self::$local_lang_pl = (get_locale() == 'pl_PL');

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos($color_key, 'main2') !== false){
                self::$main2_color = $color_value;
            }
        }

        add_action('init', array($this, 'initVCMapPWEAboutFairInfo'));
        add_shortcode('pwe_about_fair_info', array($this, 'PWEAboutFairInfoOutput'));
        add_action('wp_enqueue_scripts', array($this, 'addingStyles'));
    }

    private function getConferenceRendererClass($domain, $fair_group) {

        if (in_array($domain, ['warsawhome.eu', 'warsawhomefurniture.com', 'warsawhometextile.com', 'warsawhomelight.com', 'warsawhomekitchen.com', 'warsawhomebathroom.com', 'warsawbuild.eu'])) {
            require_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-home.php';
            return 'PWEAboutFairInfoHome';
        }

        switch ($fair_group) {
            case 'gr1':
                require_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-gr1.php';
                return 'PWEAboutFairInfoGr1';

            case 'gr2':
                require_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-gr2.php';
                return 'PWEAboutFairInfoGr2';

            case 'gr3':
                require_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-gr3.php';
                return 'PWEAboutFairInfoGr3';

            default:
                require_once plugin_dir_path(__FILE__) . 'classes/about-fair-info-default.php';
                return 'PWEAboutFairInfoDefault';
        }
    }

    private function getExhibitorsData(): array {
        $merge_exhibitors = [];
        $logos = [];

        // Pobranie katalogu — użyj dokładnie swojej metody
        // (tu tylko dopinam poprawne cudzysłowy, reszta jak w przykładzie)
        $exhibitors = CatalogFunctions::logosChecker(do_shortcode('[trade_fair_catalog]'), 'PWECatalogFull', false, null, false);

        if (is_array($exhibitors)) {
            foreach ($exhibitors as $exhibitor) {
                // Upewnij się, że nie psujemy oryginalnej struktury
                $merge_exhibitors[] = $exhibitor;

                // Spróbuj zgadnąć, gdzie siedzi logo; dopasuj do swojej struktury
                $logoName = $exhibitor['Nazwa_wystawcy'] ?? '';
                $logoUrl  = $exhibitor['URL_logo_wystawcy'] ?? '';

                if ($logoUrl && filter_var($logoUrl, FILTER_VALIDATE_URL)) {
                    $logos[] = [
                        'url' => $logoUrl,
                        'name' => $logoName
                    ];
                }
            }
        }

        $count = count($merge_exhibitors);

        if (!empty($logos)) {
            shuffle($logos);
            $logos = array_slice($logos, 0, 9);
        }

        return [
            'count'      => $count,
            'has_many'   => $count > 9,
            'logos'      => array_slice($logos, 0, 9),
            'exhibitors' => $merge_exhibitors,
        ];
    }

    public function initVCMapPWEAboutFairInfo() {
        if (class_exists('Vc_Manager')) {
            $domain = parse_url(site_url(), PHP_URL_HOST);
            $fair_data = PWECommonFunctions::get_database_fairs_data($domain);
            $fair_group = strtolower($fair_data[0]->fair_group ?? 'fallback');

            $renderer_class = $this->getConferenceRendererClass($domain, $fair_group);

            $params = method_exists($renderer_class, 'initElements') 
                ? $renderer_class::initElements() 
                : [];

            vc_map(array(
                'name' => __('PWE About Fair Info', 'pwe_about_fair_info'),
                'base' => 'pwe_about_fair_info',
                'category' => __('PWE Elements', 'pwe_about_fair_info'),
                'admin_enqueue_css' => plugin_dir_url(dirname(__DIR__)) . 'backend/backendstyle.css',
                'params' => $params,
            ));
        }
    }

    public function addingStyles() {
        $css_path = plugin_dir_path(__FILE__) . 'assets/about-fair-info-style.css';
        $css_url = plugins_url('assets/about-fair-info-style.css', __FILE__);
        $css_version = file_exists($css_path) ? filemtime($css_path) : false;

        if ($css_version) {
            wp_enqueue_style('pwe-about-fair-info-css', $css_url, array(), $css_version);
        }
    }

    // public function addingScripts($atts) {
    //     $js_path = plugin_dir_path(__FILE__) . 'assets/about-fair-info-script.js';
    //     $js_url = plugins_url('assets/script.js', __FILE__);
    //     $js_version = file_exists($js_path) ? filemtime($js_path) : false;

    //     if ($js_version) {
    //         wp_enqueue_script('pwe-about-fair-info-js', $js_url, array('jquery'), $js_version, true);
    //     }
    // }

    public function PWEAboutFairInfoOutput($atts) {
        global $local_lang_pl;
        $local_lang_pl = self::$local_lang_pl;

        $rnd_class = 'about-fair-info-' . esc_attr(self::$rnd_id);

        // $this->addingScripts($atts);

        $domain = parse_url(site_url(), PHP_URL_HOST);
        $fair_data = PWECommonFunctions::get_database_fairs_data($domain);
        $fair_group = strtolower($fair_data[0]->fair_group ?? 'fallback');

        $renderer_class = $this->getConferenceRendererClass($domain, $fair_group);
        $fairs_data_adds = PWECommonFunctions::get_database_fairs_data_adds();
        $selected_lang = self::$local_lang_pl ? 'pl' : 'en';

        $first_fair = $fairs_data_adds[0] ?? null;

        $title = $first_fair ? ($first_fair->{'about_title_' . $selected_lang} ?? '') : '';
        $desc  = $first_fair ? ($first_fair->{'about_desc_' . $selected_lang} ?? '') : '';

        $fair_img = '/doc/new_template/fair_img.webp';
        $default_img   = content_url('plugins/pwe-media/media/main-page/fair_img.webp');

        $check_img_path  = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $fair_img;

        $img = '';
        
        $img .= '<img class="pwe-about-fair-' . $fair_group . '__img" src="' . ((is_file($check_img_path) && is_readable($check_img_path)) ? home_url($fair_img) : $default_img) . '" alt="' . PWECommonFunctions::languageChecker('Odwiedzający na targach ' . do_shortcode('[trade_fair_name]') . '', 'Visitors at the ' . do_shortcode('[trade_fair_name_eng]') . '') . '">';

        
        $exhibitorsData = $this->getExhibitorsData();

        if (method_exists($renderer_class, 'output')) {
            $content = $renderer_class::output($atts, $rnd_class, $fair_group, $title, $desc, $img, $exhibitorsData);

            
            if (trim($content) === '') {
                return ''; // nic nie wyświetlaj, jeśli pusty wynik
            }

            return '<div id="PWEAboutFairInfo" class="' . $rnd_class . '">' . $content . '</div>';
        }

        return '<!-- Renderer not found -->';
    }

}
