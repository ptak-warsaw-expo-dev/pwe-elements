<?php

/*
 * Plugin Name: PWE Elements
 * Plugin URI: https://github.com/ptak-warsaw-expo-dev/pwe-elements
 * Description: Adding a PWE elements to the website.
 * Version: 3.1.5
 * Author: Marek Rumianek
 * Co-authors: Anton Melnychuk, Piotr Krupniewski, Jakub Choła
 * Author URI: github.com/RumianekMarek
 * Update URI: https://api.github.com/repos/ptak-warsaw-expo-dev/pwe-elements/releases/latest
 */



class PWElementsPlugin {
    public $PWEStyleVar;
    public $PWElements;
    public $PWELogotypes;
    public $PWEHeader;
    public $PWECatalog;
    public $PWEDisplayInfo;
    public $PWEMediaGallery;
    // public $PWEQRActive;
    public $GFAreaNumbersField;
    public $PWECatalog1;
    public $PWEExhibitorGenerator;
    public $PWEProfile;
    public $PWEStore;
    public $PWEConferenceCap;
    public $PWEIndustryEvening;
    public $PWEConferenceShortInfo;
    public $PWEAboutFairInfo;
    public $PWEAttractions;
    public $PWENews;
    // public $PWELogoFetcher;

    public function __construct() {
        // Clearing wp_rocket cache
        add_action( 'upgrader_process_complete', array( $this, 'clearWpRocketCacheOnPluginUpdate' ), 10, 2 );

        // Initialize classes
        $this->initClasses();

        $this->init();

        // Send CSS variables to <head>
        add_action('wp_head', array($this->PWEStyleVar, 'pwe_enqueue_style_var'), 1);

        // Add main CSS to wp_enqueue_scripts
        add_action('wp_enqueue_scripts', array($this, 'pwe_enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_slick_assets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_swiper_assets'));

        // $this -> resendTicket();

        // List of JavaScript files to exclude
        $excluded_js_files = [
            '/wp-content/plugins/PWElements/elements/js/exclusions.js',
            '/wp-content/plugins/PWElements/assets/three-js/three.min.js',
            '/wp-content/plugins/PWElements/assets/three-js/GLTFLoader.js',
            '/wp-content/plugins/PWElements/includes/nav-menu/assets/script.js',
            '/wp-content/plugins/PWElements/assets/swiper-slider/swiper-bundle.min.js',
            '/wp-content/cache/min/1/npm/swiper@11/swiper-bundle.min.js'
        ];

        // Excluding JS files from delayed loading (delay JS)
        add_filter('rocket_delay_js_exclusions', function ($excluded_files) use ($excluded_js_files) {
            return array_merge((array) $excluded_files, $excluded_js_files);
        });

        // Excluding JS files from defer (lazy loading)
        add_filter('rocket_exclude_defer_js', function ($defer_files) use ($excluded_js_files) {
            return array_merge((array) $defer_files, $excluded_js_files);
        }, 10, 1);

        add_filter( 'the_content', array($this, 'add_date_to_post') );

        // // Get count post views
        // add_action('template_redirect', array($this, 'get_count_views') );

        // // Display post views
        // // Only works on a single post in the main loop and main query
        // add_action('the_content', array($this, 'display_views'), 20 );
    }

    public function get_count_views() {
        if(is_admin() || !is_single() ) return; // nie licz adminow

        $post_id = get_queried_object_id(); // zapytanie o id biezacego posta
        if (!$post_id) return;

        $cookie = 'vc_post_' .$post_id; // nazwa cookie - do blokowania pozniej ponownego naliczenia
        if(isset($_COOKIE[$cookie])) return; // jesli juz jest to nic nie rob

        $views = (int) get_post_meta($post_id, 'vc_views', true); // pobranie aktualnych wyswietlen i rzutownie ich na integer
        update_post_meta($post_id, 'vc_views', $views + 1); // zapisanie wyswietlen + 1

        setcookie($cookie, 
            '1', // ustawienie zycia ciastka
            time() + 7200, // czas wygasniecia ciastka
            COOKIEPATH ?: '/', // sciezka cookie dostepna na calej domenie
            COOKIE_DOMAIN, // cookie dostepne na calej domenie
            is_ssl(), // wysylane przez ssl
            true //http true - ciasteczka nie beda dostepne dla JS
        );
    }

    public function display_views($content) {
        if(!is_singular('post') || !in_the_loop() || !is_main_query()) return $content;

        // Doklejenie paragrafu ze sformatowana liczba wg Wordpress (number_format_i18n)
        $views = (int) get_post_meta(get_the_ID(), 'vc_views', true);
        return $content . '<p class="vc_views">'. (PWECommonFunctions::lang_pl() ? "Wyświetlenia: " : "Views: ") . number_format_i18n($views) . '</p>';
    }

    public function add_date_to_post( $content ) {
        if ( is_single() && in_the_loop() && is_main_query() ) {
            $data_publikacji = get_the_date( 'j F Y' );
            $label = ( get_locale() === 'pl_PL' ) ? 'Data publikacji: ' : 'Date of publication: ';
            $content .= '<div class="pwe-post-date" style="font-style: italic; margin-top: 10px;">'. $label . esc_html( $data_publikacji ) . '</div>';
        }
        return $content;
    }

    public function pwe_enqueue_styles() {
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'pwe-style.css');
        wp_enqueue_style(
            'pwe-main-styles',
            plugin_dir_url(__FILE__) . 'pwe-style.css',
            array(),
            $css_version,
            'all'
        );
    }

    public function enqueue_slick_assets() {
        wp_enqueue_style('slick-slider-css', plugins_url('/assets/slick-slider/slick.css', __FILE__));
        wp_enqueue_style('slick-slider-theme-css', plugins_url('/assets/slick-slider/slick-theme.css', __FILE__));
        wp_enqueue_script('slick-slider-js', plugins_url('/assets/slick-slider/slick.min.js', __FILE__), array('jquery'), null, true);
    }

    public function enqueue_swiper_assets() {
        wp_enqueue_style('swiper-slider-theme-css', plugins_url('/assets/swiper-slider/swiper-bundle.min.css', __FILE__));
        wp_enqueue_script('swiper-slider-js', plugins_url('/assets/swiper-slider/swiper-bundle.min.js', __FILE__), array('jquery'), null, true);
    }

    private function initClasses() {

        // Helpers functions
        require_once plugin_dir_path(__FILE__) . 'pwefunctions.php';

        // Shortcodes from CAP
        require_once plugin_dir_path(__FILE__) . 'backend/shortcodes.php';

        // GF Mailing 
        require_once plugin_dir_path(__FILE__) . 'includes/mailing/mailing.php';
        $this->PWEMailing = new PWEMailing();

        if (is_admin()) {
            // Admin menu
            include_once plugin_dir_path(__FILE__) . 'includes/settings/admin-menu.php';
            // Settings nav menu
            include_once plugin_dir_path(__FILE__) . 'includes/settings/general-settings.php';
            // Settings nav menu
            include_once plugin_dir_path(__FILE__) . 'includes/settings/nav-menu-settings.php';
            // Settings shortcodes
            // include_once plugin_dir_path(__FILE__) . 'includes/settings/shortcodes-settings.php';
        }

        // Variables of styles
        require_once plugin_dir_path(__FILE__) . 'pwe-style-var.php';
        $this->PWEStyleVar = new PWEStyleVar();

        require_once plugin_dir_path(__FILE__) . 'includes/nav-menu/nav-menu.php';
        $this->pweNavMenu = new pweNavMenu();

        require_once plugin_dir_path(__FILE__) . 'elements/pwelements-options.php';
        $this->PWElements = new PWElements();

        require_once plugin_dir_path(__FILE__) . 'gf-upps/area-numbers/area_numbers_gf.php';
        $this->GFAreaNumbersField = new GFAreaNumbersField();

        require_once plugin_dir_path(__FILE__) . 'includes/katalog-wystawcow/main-katalog-wystawcow.php';
        $this->PWECatalog = new PWECatalog();

        require_once plugin_dir_path(__FILE__) . 'includes/exhibitor-generator/exhibitor-generator.php';
        $this->PWEExhibitorGenerator = new PWEExhibitorGenerator();

        require_once plugin_dir_path(__FILE__) . 'includes/profile/profile.php';
        $this->PWEProfile = new PWEProfile();

        require_once plugin_dir_path(__FILE__) . 'includes/header/header.php';
        $this->PWEHeader = new PWEHeader();

        require_once plugin_dir_path(__FILE__) . 'includes/logotypes/logotypes.php';
        $this->PWELogotypes = new PWELogotypes();

        require_once plugin_dir_path(__FILE__) . 'includes/display-info/display-info.php';
        $this->PWEDisplayInfo = new PWEDisplayInfo();

        require_once plugin_dir_path(__FILE__) . 'includes/media-gallery/media-gallery.php';
        $this->PWEMediaGallery = new PWEMediaGallery();

        require_once plugin_dir_path(__FILE__) . 'includes/registration/registration.php';
        $this->PWERegistration = new PWERegistration();

        require_once plugin_dir_path(__FILE__) . 'includes/map/map.php';
        $this->PWEMap = new PWEMap();

        require_once plugin_dir_path(__FILE__) . 'includes/store/store.php';
        $this->PWEStore = new PWEStore();

        require_once plugin_dir_path(__FILE__) . 'includes/conference-cap/conference_cap.php';
        $this->PWEConferenceCap = new PWEConferenceCap();

        if (!empty($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'warsawexpo.eu' || $_SERVER['HTTP_HOST'] === 'rfl.warsawexpo.eu')) {
            require_once plugin_dir_path(__FILE__) . 'includes/calendar/calendar.php';
            $this->PWECalendar = new PWECalendar();

            require_once plugin_dir_path(__FILE__) . 'includes/conference-calendar/conference-calendar.php';
            $this->PWEConferenceCalendar = new PWEConferenceCalendar();
        }

        // require_once plugin_dir_path(__FILE__) . 'includes/top10/pwelogofetcher.php';
        // $placeholderPath = plugin_dir_path(__FILE__) . 'media/ufi_black.png';
        // $this->PWELogoFetcher = new PWELogoFetcher($placeholderPath);

        // require_once plugin_dir_path(__FILE__) . 'qr-active/main-qr-active.php';
        // $this->PWEQRActive = new PWEQRActive();

        require_once plugin_dir_path(__FILE__) . 'includes/industry-evening/industry-evening.php';
        $this->PWEIndustryEvening = new PWEIndustryEvening();

        require_once plugin_dir_path(__FILE__) . 'includes/reviews/reviews.php';
        $this->PWEReviews = new PWEReviews();

        require_once plugin_dir_path(__FILE__) . 'other/test.php';
        $this->PWETest = new PWETest();

        require_once plugin_dir_path(__FILE__) . 'includes/conference-short-info/conference-short-info.php';
        $this->PWEConferenceShortInfo = new PWEConferenceShortInfo();

        require_once plugin_dir_path(__FILE__) . 'includes/about-fair-info/about-fair-info.php';
        $this->PWEAboutFairInfo = new PWEAboutFairInfo();

        require_once plugin_dir_path(__FILE__) . 'includes/premieres/premieres.php';
        $this->PWEPremieres = new PWEPremieres();

        require_once plugin_dir_path(__FILE__) . 'includes/attractions/attractions.php';
        $this->PWEAttractions = new PWEAttractions();

        require_once plugin_dir_path(__FILE__) . 'includes/news/news.php';
        $this->PWENews = new PWENews();
    }

    // Czyszczenie pamięci wp_rocket
    public function clearWpRocketCacheOnPluginUpdate( $upgrader_object, $options ) {
        $plugin = isset( $options['plugin'] ) ? $options['plugin'] : '';
        // Sprawdź, czy zaktualizowana wtyczka to twoja wtyczka
        if ( 'PWElements/pwelements.php' === $plugin ) {
            // Sprawdź, czy WP Rocket jest aktywny
            if ( function_exists( 'rocket_clean_domain' ) ) {
                // Wywołaj funkcję czyszczenia pamięci podręcznej WP Rocket
                rocket_clean_domain();
            }
        }
    }

    private function getGithubKey() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_klavio_setup';
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) != $table_name) {
            return null;
        }

        $github_pre = $wpdb->prepare("SELECT klavio_list_id FROM $table_name WHERE klavio_list_name = %s", 'github_secret_2');
        $github_result = $wpdb->get_results($github_pre);

        if (!empty($github_result)) {
            return $github_result[0]->klavio_list_id;
        } else {
            return null;
        }
    }

    private function init() {

        // Adres autoupdate
        include( plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php');

        $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
            'https://github.com/ptak-warsaw-expo-dev/pwe-elements',
            __FILE__,
            'pwe-elements'
        );

        if (self::getGithubKey()){
            $myUpdateChecker->setAuthentication(self::getGithubKey());
        }
        $myUpdateChecker->getVcsApi()->enableReleaseAssets();
    }
}

// Inicjalizacja wtyczki jako obiektu klasy
$PWElementsPlugin = new PWElementsPlugin();