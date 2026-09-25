<?php

class PWEDisplayInfo extends PWECommonFunctions {
    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        self::$fair_colors = $this->findPalletColors();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos($color_key, 'main2') != false){
                self::$main2_color = $color_value;
            }
        }

        // Hook actions
        add_action('wp_enqueue_scripts', array($this, 'addingScripts'));

        add_action('init', array($this, 'initVCMapPWEDisplayInfo'));
        add_shortcode('pwe_display_info', array($this, 'PWEDisplayInfoOutput'));
    }

    /**
     * Initialize VC Map PWEDisplayInfo.
     */
    public function initVCMapPWEDisplayInfo() {

        require_once plugin_dir_path(__FILE__) . 'classes/display-info_box.php';
        require_once plugin_dir_path(__FILE__) . 'classes/display-info_speakers.php';

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Display info', 'pwe_display_info'),
                'base' => 'pwe_display_info',
                'category' => __( 'PWE Elements', 'pwe_display_info'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'param_holder_class' => 'pwelement__backend',
                'params' => array_merge(
                    array( 
                        array(
                            'type' => 'dropdown',
                            'group' => 'main',
                            'heading' => __( 'Display info format', 'pwe_display_info'),
                            'param_name' => 'display_info_format',
                            'description' => __( 'Select format.', 'pwe_display_info'),
                            'value' => array(
                                'Display info box' => 'PWEDisplayInfoBox',
                                'Display info speakers' => 'PWEDisplayInfoSpeakers',
                            ),
                            'save_always' => true,
                            'admin_label' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'main',
                            'heading' => __('Custom Id', 'pwe_display_info'),
                            'param_name' => 'display_info_custom_id',
                            'admin_label' => true,
                            'dependency' => array(
                                'element' => 'display_info_format',
                                'value' => array('PWEDisplayInfoBox', 'PWEDisplayInfoSpeakers'),
                            ),
                        ),
                        ...PWEDisplayInfoBox::initElements(),
                        ...PWEDisplayInfoSpeakers::initElements(),
                        // colors setup                     
                        array(
                            'type' => 'dropdown',
                            'group' => 'options',
                            'heading' => __('Select button color <a href="#" onclick="yourFunction(`btn_color_manual_hidden`, `btn_color`)">Hex</a>', 'pwe_display_info'),
                            'param_name' => 'btn_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button color for the element.', 'pwe_display_info'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'options',
                            'heading' => __('Write button color <a href="#" onclick="yourFunction(`btn_color`, `btn_color_manual_hidden`)">Pallet</a>', 'pwe_display_info'),
                            'param_name' => 'btn_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button color for the element.', 'pwe_display_info'),
                            'value' => '',
                            'save_always' => true
                        ),
                        array(
                            'type' => 'dropdown',
                            'group' => 'options',
                            'heading' => __('Select button text color <a href="#" onclick="yourFunction(`btn_text_color_manual_hidden`, `btn_text_color`)">Hex</a>', 'pwe_display_info'),
                            'param_name' => 'btn_text_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button text color for the element.', 'pwe_display_info'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_text_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'options',
                            'heading' => __('Write button text color <a href="#" onclick="yourFunction(`btn_text_color`, `btn_text_color_manual_hidden`)">Pallet</a>', 'pwe_display_info'),
                            'param_name' => 'btn_text_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button text color for the element.', 'pwe_display_info'),
                            'value' => '',
                            'save_always' => true
                        ),
                        array(
                            'type' => 'dropdown',
                            'group' => 'options',
                            'heading' => __('Select button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color_manual_hidden`, `btn_shadow_color`)">Hex</a>', 'pwe_display_info'),
                            'param_name' => 'btn_shadow_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button shadow color for the element.', 'pwe_display_info'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_shadow_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'options',
                            'heading' => __('Write button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color`, `btn_shadow_color_manual_hidden`)">Pallet</a>', 'pwe_display_info'),
                            'param_name' => 'btn_shadow_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button shadow color for the element.', 'pwe_display_info'),
                            'value' => '',
                            'save_always' => true
                        ),
                        // color END
                        array(
                            'type' => 'textarea_raw_html',
                            'group' => 'CSS',
                            'heading' => __('Custom css', 'pwelement'),
                            'param_name' => 'display_info_css',
                            'description' => __('Hidden text', 'pwelement'),
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'display_info_format',
                                'value' => array('PWEDisplayInfoBox', 'PWEDisplayInfoSpeakers'),
                            ),
                        ),
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
            'PWEDisplayInfoBox'         => 'classes/display-info_box.php',
            'PWEDisplayInfoSpeakers'    => 'classes/display-info_speakers.php',
        );
    }

    /**
     * Adding Scripts
     */
    public function addingScripts($atts){
        $js_file = plugins_url('assets/display-info.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/display-info.js');
        wp_enqueue_script('pwe-display-info-js', $js_file, array('jquery'), $js_version, true);

        $locale = get_locale();
        wp_localize_script('pwe-display-info-js', 'pweScriptData', array(
            'locale' => $locale
        ));
    }

    /**
     * Output method for pwe_display_info shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWEDisplayInfoOutput($atts, $content = null) {

        // pwe_display_info output
        extract( shortcode_atts( array(
            'display_info_format' => '',
            'display_info_custom_id' => '',
            'display_info_css' => '',
        ), $atts ));

        $display_info_css_decoded = base64_decode($display_info_css);// Decoding Base64
        $display_info_css_code = urldecode($display_info_css_decoded); // Decoding URL

        self::$rnd_id = !empty($display_info_custom_id) ? $display_info_custom_id : self::id_rnd();

        if ($this->findClassElements()[$display_info_format]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$display_info_format];
            
            if (class_exists($display_info_format)) {
                $output_class = new $display_info_format;
                $output = $output_class->output($atts, $content);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $display_info_format .' does not exist")</script>';
                $output = '';
            }
        } else {
            echo '<script>console.log("File with class ' . $display_info_format .' does not exist")</script>';
        }
        
        $output = do_shortcode($output);

        $output_html = '<style>'. $display_info_css_code .'</style>';

        $display_info_word = $display_info_format == 'PWEDisplayInfoBox' ? 'box' : 'speaker';

        $output_html .= '<div id="info-'. $display_info_word .'-'. self::$rnd_id .'" class="info-'. $display_info_word .'">' . $output . '</div>';

        return $output_html;
    }
}