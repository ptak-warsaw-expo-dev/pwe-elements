<?php

/**
 * Class PWEProfile
 * Extends pwe_profiles class and defines a custom Visual Composer element for vouchers.
 */
class PWEProfile extends PWECommonFunctions {

    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        self::$rnd_id = rand(10000, 99999);
        self::$fair_colors = $this->findPalletColors();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos(strtolower($color_key), 'main2') !== false){
                self::$main2_color = $color_value;
            }
        }

        // Hook actions
        add_action('init', array($this, 'initVCMapPWEProfile'));
        add_shortcode('pwe_profile', array($this, 'PWEProfileOutput'));
    }

    /**
     * Initialize VC Map PWEProfile.
     */
    public function initVCMapPWEProfile() {

        require_once plugin_dir_path(__FILE__) . 'classes/profile-tabs.php';
        require_once plugin_dir_path(__FILE__) . 'classes/profile-sitetabs.php';
        require_once plugin_dir_path(__FILE__) . 'classes/profile-all-in-one.php';
        require_once plugin_dir_path(__FILE__) . 'classes/profile-single.php';
        require_once plugin_dir_path(__FILE__) . 'classes/profile-three-cols.php';
        require_once plugin_dir_path(__FILE__) . 'classes/profile-buttons.php';
        require_once plugin_dir_path(__FILE__) . 'classes/profile-cards.php';
        require_once plugin_dir_path(__FILE__) . 'classes/profile-expanding.php';

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Profile', 'pwe_profile'),
                'base' => 'pwe_profile',
                'category' => __( 'PWE Elements', 'pwe_profile'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'params' => array_merge(
                    array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select profile type', 'pwe_profile'),
                            'param_name' => 'profile_type',
                            'param_holder_class' => 'backend-area-one-fourth-width',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(
                                'Accordion' => 'PWEProfileAllInOne',
                                'Tabs' => 'PWEProfileTabs',
                                'Sitetabs' => 'PWEProfileSiteTabs',
                                'Single' => 'PWEProfileSingle',
                                'Three columns' => 'PWEProfileThreeCols',
                                'Buttons' => 'PWEProfileButtons',
                                'Cards' => 'PWEProfileCards',
                                'Expanding' => 'PWEProfileExpanding',
                            ),
                            'std' => 'PWEProfileAllInOne',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select text color <a href="#" onclick="yourFunction(`text_color_manual_hidden`, `text_color`)">Hex</a>', 'pwe_profile'),
                            'param_name' => 'text_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select text color for the element.', 'pwe_profile'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'text_color_manual_hidden',
                                'value' => array(''),
                                'callback' => "hideEmptyElem",
                            ),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write text color <a href="#" onclick="yourFunction(`text_color`, `text_color_manual_hidden`)">Pallet</a>', 'pwe_profile'),
                            'param_name' => 'text_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for text color for the element.', 'pwe_profile'),
                            'value' => '',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select button color <a href="#" onclick="yourFunction(`btn_color_manual_hidden`, `btn_color`)">Hex</a>', 'pwe_profile'),
                            'param_name' => 'btn_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button color for the element.', 'pwe_profile'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write button color <a href="#" onclick="yourFunction(`btn_color`, `btn_color_manual_hidden`)">Pallet</a>', 'pwe_profile'),
                            'param_name' => 'btn_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button color for the element.', 'pwe_profile'),
                            'value' => '',
                            'save_always' => true
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select button text color <a href="#" onclick="yourFunction(`btn_text_color_manual_hidden`, `btn_text_color`)">Hex</a>', 'pwe_profile'),
                            'param_name' => 'btn_text_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button text color for the element.', 'pwe_profile'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_text_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write button text color <a href="#" onclick="yourFunction(`btn_text_color`, `btn_text_color_manual_hidden`)">Pallet</a>', 'pwe_profile'),
                            'param_name' => 'btn_text_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button text color for the element.', 'pwe_profile'),
                            'value' => '',
                            'save_always' => true
                        ),
                        ...PWEProfileTabs::initElements(),
                        ...PWEProfileSiteTabs::initElements(),
                        ...PWEProfileAllInOne::initElements(),
                        ...PWEProfileSingle::initElements(),
                        ...PWEProfileThreeCols::initElements(),
                        ...PWEProfileButtons::initElements(),
                        ...PWEProfileCards::initElements(),
                        ...PWEProfileExpanding::initElements(),
                    ),
                ),
            ));
        }
    }

    /**
     * Check class for file if exists.
     *
     * @return array
     */
    private function findClassElements() {
        // Array off class placement
        return array(
            'PWEProfileTabs'      => 'classes/profile-tabs.php',
            'PWEProfileSiteTabs'  => 'classes/profile-sitetabs.php',
            'PWEProfileAllInOne'  => 'classes/profile-all-in-one.php',
            'PWEProfileSingle'    => 'classes/profile-single.php',
            'PWEProfileThreeCols' => 'classes/profile-three-cols.php',
            'PWEProfileButtons'   => 'classes/profile-buttons.php',
            'PWEProfileCards'     => 'classes/profile-cards.php',
            'PWEProfileExpanding'     => 'classes/profile-expanding.php',
        );
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../../translations/includes/profile.json';

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
    public function PWEProfileOutput($atts, $content = null) {

        extract( shortcode_atts( array(
            'profile_type' => '',
        ), $atts ));

        if ($this->findClassElements()[$profile_type]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$profile_type];

            if (class_exists($profile_type)) {
                $output_class = new $profile_type;
                $output = $output_class->output($atts, $content);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $profile_type .' does not exist")</script>';
                $output = '';
            }
        } else {
            echo '<script>console.log("File with class ' . $profile_type .' does not exist")</script>';
        }

        $output = do_shortcode($output);

        if ($profile_type == 'PWEProfileAllInOne') {
            $profile_el_id = 'ProfileAllInOne';
            $profile_el_class = 'profile-all-in-one';
        } else if ($profile_type == 'PWEProfileTabs') {
            $profile_el_id = 'ProfileTabs';
            $profile_el_class = 'profile-tabs';
        } else if ($profile_type == 'PWEProfileSiteTabs') {
            $profile_el_id = 'ProfileSiteTabs';
            $profile_el_class = 'profile-sitetabs';
        } else if ($profile_type == 'PWEProfileThreeCols') {
            $profile_el_id = 'PWEProfileThreeCols';
            $profile_el_class = 'profile-threecols';
        } else if ($profile_type == 'PWEProfileButtons') {
            $profile_el_id = 'PWEProfileButtons';
            $profile_el_class = 'profile-buttons';
        } else if ($profile_type == 'PWEProfileCards') {
            $profile_el_id = 'PWEProfileCards';
            $profile_el_class = 'profile-cards';
        } else if ($profile_type == 'PWEProfileExpanding') {
            $profile_el_id = 'PWEProfileExpanding';
            $profile_el_class = 'profile-expanding';
        } else {
            $profile_el_id = 'ProfileSingle'. self::$rnd_id;
            $profile_el_class = 'profile-single-'. self::$rnd_id;
        }

        $output_html = '<div id="'. $profile_el_id .'" class="'. $profile_el_class .' '. $profile_el_class . '-' . self::$rnd_id . '">' . $output . '</div>';

        return $output_html;
    }
}