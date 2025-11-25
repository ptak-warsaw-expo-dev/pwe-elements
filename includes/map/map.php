<?php

/**
 * Class PWEMap
 * Extends maps class and defines a custom Visual Composer element for vouchers.
 */
class PWEMap extends PWECommonFunctions {

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
        add_action('init', array($this, 'initVCMapPWEMap'));
        // add_action('wp_enqueue_scripts', array($this, 'addingScripts'));

        add_shortcode('pwe_map', array($this, 'PWEMapOutput'));
    }

    /**
     * Initialize VC Map PWEMap.
     */
    public function initVCMapPWEMap() {

        require_once plugin_dir_path(__FILE__) . 'classes/map_dynamic.php';
        require_once plugin_dir_path(__FILE__) . 'classes/map_3d.php';

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Map', 'pwe_map'),
                'base' => 'pwe_map',
                'category' => __( 'PWE Elements', 'pwe_map'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'params' => array_merge(
                    array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select map type', 'pwe_map'),
                            'param_name' => 'map_type',
                            'param_holder_class' => 'backend-area-one-fourth-width',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(
                                'Dynamic' => 'PWEMapDynamic',
                                '3D' => 'PWEMap3D',
                            ),
                            'std' => 'PWEMapDynamic',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select map preset', 'pwe_map'),
                            'param_name' => 'map_dynamic_preset',
                            'param_holder_class' => 'backend-area-one-fourth-width',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(
                                'Preset 1' => 'preset_1',
                                'Preset 2' => 'preset_2',
                                'Preset 3' => 'preset_3',
                            ),
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => __('Model color', 'pwe_map'),
                            'param_name' => 'map_color',
                            'description' => __('Write or select color of model', 'pwe_map'),
                            'param_holder_class' => 'backend-area-one-fourth-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => array(
                                    'PWEMapDynamic',
                                    'PWEMap3D'
                                ),
                            ),
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => __('Model water color', 'pwe_map'),
                            'param_name' => 'map_water_color',
                            'description' => __('Write or select color of water model', 'pwe_map'),
                            'param_holder_class' => 'backend-area-one-fourth-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => array(
                                    'PWEMapDynamic',
                                    'PWEMap3D'
                                ),
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Overlay color', 'pwe_map'),
                            'param_name' => 'map_overlay',
                            'param_holder_class' => 'backend-area-one-fourth-width',
                            'description' => __('Write or select color of overlay', 'pwe_map'),
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => array(
                                    'PWEMapDynamic',
                                    'PWEMap3D'
                                ),
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __('3D Model', 'pwe_map'),
                            'param_name' => 'map_dynamic_3d',
                            'save_always' => true,
                            'value' => array(__('True', 'pwe_map') => 'true',),
                            'dependency' => array(
                                'element' => 'map_dynamic_preset',
                                'value' => 'preset_1',
                            ),
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => __('Additional image', 'pwe_map'),
                            'param_name' => 'map_image',
                            'description' => __('Choose additional image from the media library.', 'pwe_map'),
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMap3D',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Title', 'pwe_map'),
                            'param_name' => 'map_custom_title',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Number of countries', 'pwe_map'),
                            'param_name' => 'map_number_countries',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Current year', 'pwe_map'),
                            'param_name' => 'map_year',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Previous year', 'pwe_map'),
                            'param_name' => 'map_year_previous',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Current edition', 'pwe_map'),
                            'param_name' => 'map_custom_current_edition',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_dynamic_preset',
                                'value' => 'preset_3',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Previous edition', 'pwe_map'),
                            'param_name' => 'map_custom_previous_edition',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_dynamic_preset',
                                'value' => 'preset_3',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Number of visitors (current period)', 'pwe_map'),
                            'param_name' => 'map_number_visitors',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Number of visitors (previous period)', 'pwe_map'),
                            'param_name' => 'map_number_visitors_previous',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Number of visitors (current period from abroad)', 'pwe_map'),
                            'param_name' => 'map_number_abroad_visitors',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_dynamic_preset',
                                'value' => 'preset_3',
                            ),
                        ),
                                                array(
                            'type' => 'textfield',
                            'heading' => __('Number of visitors (previous period from abroad)', 'pwe_map'),
                            'param_name' => 'map_number_abroad_visitors_previous',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_dynamic_preset',
                                'value' => 'preset_3',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Number of exhibitors (current period)', 'pwe_map'),
                            'param_name' => 'map_number_exhibitors',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Number of exhibitors (previous period)', 'pwe_map'),
                            'param_name' => 'map_number_exhibitors_previous',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Exhibition space (current period)', 'pwe_map'),
                            'param_name' => 'map_exhibition_space',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Exhibition space (previous period)', 'pwe_map'),
                            'param_name' => 'map_exhibition_space_previous',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_type',
                                'value' => 'PWEMapDynamic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Percent of polish visitors', 'pwe_map'),
                            'param_name' => 'map_percent_polish_visitors',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_dynamic_preset',
                                'value' => 'preset_1',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('More LOGOS', 'pwe_map'),
                            'param_name' => 'map_more_logotypes',
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'map_dynamic_preset',
                                'value' => 'preset_1',
                            ),
                        ),
                        array(
                            'type' => 'param_group',
                            'group' => 'Replace Strings',
                            'param_name' => 'pwe_replace',
                            'params' => array(
                                array(
                                    'type' => 'textarea',
                                    'heading' => __('Input HTML', 'pwe_map'),
                                    'param_name' => 'input_replace_html',
                                    'save_always' => true,
                                    'admin_label' => true
                                ),
                                array(
                                    'type' => 'textarea',
                                    'heading' => __('Output HTML', 'pwe_map'),
                                    'param_name' => 'output_replace_html',
                                    'save_always' => true,
                                    'admin_label' => true
                                ),
                            ),
                        ),
                    ),
                ),
            ));
        }
    }


    /**
     * Adding Scripts
     */
    public function addingScripts($map_type, $map_dynamic_3d, $map_dynamic_preset, $map_color, $map_water_color) {

        $data_js_array = array(
            'map_type' => $map_type,
            'map_dynamic_3d' => $map_dynamic_3d,
            'map_dynamic_preset' => $map_dynamic_preset,
            'map_color' => $map_color,
            'map_water_color' => $map_water_color,
            'accent_color' => self::$accent_color,
        );

        wp_enqueue_script('three-js', plugin_dir_url(dirname( __DIR__ )) .'assets/three-js/three.min.js', array('jquery'), 0.1, true);
        wp_enqueue_script('GLTFLoader-js', plugin_dir_url(dirname( __DIR__ )) .'assets/three-js/GLTFLoader.js', array('jquery'), 0.1, true);

        // JS
        $js_file = plugins_url('assets/script.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/script.js');
        wp_enqueue_script('script-map-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script( 'script-map-js', 'data_js', $data_js_array );
    }

    /**
     * Check class for file if exists.
     *
     * @return array
     */
    private function findClassElements() {
        // Array off class placement
        return array(
            'PWEMapDynamic'      => 'classes/map_dynamic.php',
            'PWEMap3D'      => 'classes/map_3d.php',
        );
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public function PWEMapOutput($atts) {

        extract( shortcode_atts( array(
            'map_type' => '',
            'map_dynamic_preset' => '',
            'map_dynamic_3d' => '',
            'map_color' => '',
            'map_water_color' => '',
            'map_overlay' => '',
            'map_image' => '',
            'map_custom_title' => '',
            'map_year' => '',
            'map_custom_current_edition' => '',
            'map_number_visitors' => '',
            'map_number_abroad_visitors' => '',
            'map_number_exhibitors' => '',
            'map_exhibition_space' => '',
            'map_year_previous' => '',
            'map_custom_previous_edition' => '',
            'map_number_visitors_previous' => '',
            'map_number_abroad_visitors' => '',
            'map_number_exhibitors_previous' => '',
            'map_exhibition_space_previous' => '',
            'map_number_countries' => '',
            'map_percent_polish_visitors' => '',
            'pwe_replace' => '',
        ), $atts ));

        // Replace strings
        $pwe_replace_urldecode = urldecode($pwe_replace);
        $pwe_replace_json = json_decode($pwe_replace_urldecode, true);
        $input_replace_array_html = array();
        $output_replace_array_html = array();
        foreach ($pwe_replace_json as $replace_item) {
            $input_replace_array_html[] = $replace_item["input_replace_html"];
            $output_replace_array_html[] = $replace_item["output_replace_html"];
        }

        if ($this->findClassElements()[$map_type]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$map_type];

            if (class_exists($map_type)) {
                $output_class = new $map_type;
                $output = $output_class->output($atts);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $map_type .' does not exist")</script>';
                $output = '';
            }
        } else {
            echo '<script>console.log("File with class ' . $map_type .' does not exist")</script>';
        }

        $this->addingScripts($map_type, $map_dynamic_3d, $map_dynamic_preset, $map_color, $map_water_color);

        $output = do_shortcode($output);

        $output_html = '<div class="pwe_map pwe_map_'. self::$rnd_id .'">' . $output . '</div>';

        if ($input_replace_array_html && $output_replace_array_html) {
            $output_html = str_replace($input_replace_array_html, $output_replace_array_html, $output_html);
        }

        return $output_html;

    }
}