<?php

class PWELogotypes extends PWECommonFunctions {
    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;
    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        self::$rnd_id = $this->id_rnd();
        self::$fair_colors = $this->findPalletColors();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos(strtolower($color_key), 'main2') !== false){
                self::$main2_color = $color_value;
            }
        }

        require_once plugin_dir_path(__FILE__) . 'classes/logotypes_common.php';
        
         // Hook actions
        add_action('init', array($this, 'initVCMapLogotypes'));
        add_shortcode('pwe_logotypes', array($this, 'PWELogotypesOutput'));
    }

    /**
     * Initialize VC Map Elements.
     */
    public function initVCMapLogotypes() {
        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __('PWE Logotypes', 'pwe_logotypes'),
                'base' => 'pwe_logotypes',
                'category' => __('PWE Elements', 'pwe_logotypes'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'admin_enqueue_js' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendscript.js',
                'params' => array_merge(
                    array(
                        // colors setup
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select text color <a href="#" onclick="yourFunction(`text_color_manual_hidden`, `text_color`)">Hex</a>', 'pwe_logotypes'),
                            'param_name' => 'text_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select text color for the element.', 'pwe_logotypes'),
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
                            'heading' => __('Write text color <a href="#" onclick="yourFunction(`text_color`, `text_color_manual_hidden`)">Pallet</a>', 'pwe_logotypes'),
                            'param_name' => 'text_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for text color for the element.', 'pwe_logotypes'),
                            'value' => '',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select text shadow color <a href="#" onclick="yourFunction(`text_shadow_color_manual_hidden`, `text_shadow_color`)">Hex</a>', 'pwe_logotypes'),
                            'param_name' => 'text_shadow_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select shadow text color for the element.', 'pwe_logotypes'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'text_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write text shadow color <a href="#" onclick="yourFunction(`text_shadow_color`, `text_shadow_color_manual_hidden`)">Pallet</a>', 'pwe_logotypes'),
                            'param_name' => 'text_shadow_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for text shadow color for the element.', 'pwe_logotypes'),
                            'value' => '',
                            'save_always' => true,
                        ),                        
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select button color <a href="#" onclick="yourFunction(`btn_color_manual_hidden`, `btn_color`)">Hex</a>', 'pwe_logotypes'),
                            'param_name' => 'btn_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button color for the element.', 'pwe_logotypes'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write button color <a href="#" onclick="yourFunction(`btn_color`, `btn_color_manual_hidden`)">Pallet</a>', 'pwe_logotypes'),
                            'param_name' => 'btn_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button color for the element.', 'pwe_logotypes'),
                            'value' => '',
                            'save_always' => true
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select button text color <a href="#" onclick="yourFunction(`btn_text_color_manual_hidden`, `btn_text_color`)">Hex</a>', 'pwe_logotypes'),
                            'param_name' => 'btn_text_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button text color for the element.', 'pwe_logotypes'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_text_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write button text color <a href="#" onclick="yourFunction(`btn_text_color`, `btn_text_color_manual_hidden`)">Pallet</a>', 'pwe_logotypes'),
                            'param_name' => 'btn_text_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button text color for the element.', 'pwe_logotypes'),
                            'value' => '',
                            'save_always' => true
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color_manual_hidden`, `btn_shadow_color`)">Hex</a>', 'pwe_logotypes'),
                            'param_name' => 'btn_shadow_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button shadow color for the element.', 'pwe_logotypes'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_shadow_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color`, `btn_shadow_color_manual_hidden`)">Pallet</a>', 'pwe_logotypes'),
                            'param_name' => 'btn_shadow_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button shadow color for the element.', 'pwe_logotypes'),
                            'value' => '',
                            'save_always' => true
                        ),    
                        array(
                            'type' => 'attach_images',
                            'group' => 'PWE Element',
                            'heading' => __('Select Images', 'pwe_logotypes'),
                            'param_name' => 'logotypes_media',
                            'description' => __('Choose images from the media library.', 'pwe_logotypes'),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'PWE Element',
                            'heading' => esc_html__('Logotypes catalog', 'pwe_logotypes'),
                            'param_name' => 'logotypes_catalog',
                            'description' => __('Put catalog name in /doc/ where are logotypes.', 'pwe_logotypes'),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'PWE Element',
                            'heading' => esc_html__('Title', 'pwe_logotypes'),
                            'param_name' => 'logotypes_title',
                            'description' => __('Set title to diplay over the gallery', 'pwe_logotypes'),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'PWE Element',
                            'heading' => esc_html__('Logotypes captions title', 'pwe_logotypes'),
                            'param_name' => 'logotypes_name',
                            'description' => __('Set custom name thumbnails', 'pwe_logotypes'),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'checkbox',
                            'group' => 'PWE Element',
                            'heading' => __('Turn on exhibitors (default top 21)', 'pwe_header'),
                            'param_name' => 'logotypes_exhibitors_on',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'PWE Element',
                            'heading' => __('Exhibitors count logotypes', 'pwe_header'),
                            'param_name' => 'logotypes_exhibitors_count',
                            'description' => __('Set exhibitors count of logotypes', 'pwe_logotypes'),
                            'save_always' => true,
                            'dependency' => array(
                                'element' => 'logotypes_exhibitors_on',
                                'value' => array(
                                    'true'
                                ),
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'group' => 'PWE Element',
                            'heading' => __('Turn on captions', 'pwe_header'),
                            'param_name' => 'logotypes_caption_on',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'checkbox',
                            'group' => 'Aditional options',
                            'heading' => __('Logotypes white', 'pwe_logotypes'),
                            'param_name' => 'logotypes_slider_logo_white',
                            'description' => __('Check if you want to change the logotypes color to white.', 'pwe_logotypes'),
                            'admin_label' => true,
                            'save_always' => true,
                            'value' => array(__('True', 'pwe_logotypes') => 'true',),
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => 'Aditional options',
                            'heading' => __( 'Logotypes changer', 'pwe_logotypes'),
                            'param_name' => 'logotypes_file_changer',
                            'description' => __( 'Changer for logos divided by ";;" try to put names <br> change places "name<=>name or position";<br> move to position "name=>>name or position";', 'pwe_logotypes'),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'param_group',
                            'group' => 'Replace Strings',
                            'param_name' => 'pwe_replace',
                            'params' => array(
                                array(
                                    'type' => 'textarea',
                                    'heading' => __('Input HTML', 'pwelement'),
                                    'param_name' => 'input_replace_html',
                                    'save_always' => true,
                                    'admin_label' => true
                                ),
                                array(
                                    'type' => 'textarea',
                                    'heading' => __('Output HTML', 'pwelement'),
                                    'param_name' => 'output_replace_html',
                                    'save_always' => true,
                                    'admin_label' => true
                                ),
                            ),
                        ),
                        // Add additional options from class extends
                        ...PWElementAdditionalLogotypes::additionalArray(),
                    )
                ),
            ));
        }
    }

    /**
     * Get logotypes for catalog (top 21)
     * 
     * @param string $catalog_id fair id for api.
     * @return array
     */
    public static function exhibitors_catalog_checker($catalog_id, $logotypes_exhibitors_count = 21, $file_changer = null){
        if  (!empty($catalog_id)) {
            $today = new DateTime();
            $formattedDate = $today->format('Y-m-d');
            $token = md5("#22targiexpo22@@@#" . $formattedDate);
            $exh_catalog_address = PWECommonFunctions::get_database_meta_data('exh_catalog_address');
            $canUrl = $exh_catalog_address . $token . '&id_targow=' . $catalog_id;

            try {
                $json = @file_get_contents($canUrl);
                if ($json === false) {
                    throw new Exception('Nie można pobrać danych JSON.');
                }
        
                $data = json_decode($json, true);
                if ($data === null) {
                    throw new Exception('Błąd dekodowania danych JSON.');
                }
        
                $basic_exhibitors = reset($data)['Wystawcy'];
            } catch (Exception $e) {
                if (current_user_can('administrator')) {
                    echo '<script>console.error("Błąd w exhibitors_catalog_checker: ' . addslashes($e->getMessage()) . '")</script>';
                }
                $basic_exhibitors = [];
            }
            $logotypes_array = array();

            $basic_exhibitors = (!empty($file_changer)) ? CatalogFunctions::orderChanger($file_changer, $basic_exhibitors) : $basic_exhibitors;

            if($basic_exhibitors != '') {
                $basic_exhibitors = array_reduce($basic_exhibitors, function($acc, $curr) {
                    $name = $curr['Nazwa_wystawcy'];
                    $existingIndex = array_search($name, array_column($acc, 'Nazwa_wystawcy'));
                    if ($existingIndex === false) {
                        $acc[] = $curr;
                    } else {
                        if($acc[$existingIndex]["Data_sprzedazy"] !== null && $curr["Data_sprzedazy"] !== null && strtotime($acc[$existingIndex]["Data_sprzedazy"]) < strtotime($curr["Data_sprzedazy"])){
                            $acc[$existingIndex] = $curr;
                        }
                    }
                    return $acc;
                }, []);            
            } else {
                $basic_exhibitors = [];
            }

            $i = 0;
            foreach($basic_exhibitors as $exhibitor){
                if ($exhibitor['URL_logo_wystawcy']){ 
                    $logotypes_array[] = $exhibitor;
                    $i++;
                    if ($i >= $logotypes_exhibitors_count) {
                        break;
                    }
                }
            }
            
            return $logotypes_array;
        }
    }
 

    /**
     * Output method for PWelement shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWELogotypesOutput($atts, $content = null) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important';

        extract( shortcode_atts( array(
            'pwe_replace' => '',
            'logotypes_exhibitors_count' => '',
            'logotypes_file_changer' => ''
        ), $atts ));

        $logotypes_exhibitors_count = !empty($logotypes_exhibitors_count) ? $logotypes_exhibitors_count : 21;

        $el_id = self::id_rnd();

        // Exhibitors logotypes top 21
        $exhibitors = self::exhibitors_catalog_checker(do_shortcode('[trade_fair_catalog]'), $logotypes_exhibitors_count, $logotypes_file_changer);

        if (!empty($exhibitors)) {
            $exhibitors_logotypes = array();
            foreach($exhibitors as $exhibitor){
                $exhibitors_logotypes[] = array('img' => $exhibitor['URL_logo_wystawcy']);
            } 
        } else $exhibitors_logotypes = array();

        $output = '';
        
        // Replace strings
        $pwe_replace_urldecode = urldecode($pwe_replace);
        $pwe_replace_json = json_decode($pwe_replace_urldecode, true);
        $input_replace_array_html = array();
        $output_replace_array_html = array();
        
        if (is_array($pwe_replace_json)) {
            foreach ($pwe_replace_json as $replace_item) {
                $input_replace_array_html[] = $replace_item["input_replace_html"];
                $output_replace_array_html[] = $replace_item["output_replace_html"];
            }
        }
        
        // // Adding the result from additionalOutput to $output
        $output .= PWElementAdditionalLogotypes::additionalOutput($atts, $el_id, null, $exhibitors_logotypes);
        
        $output = do_shortcode($output);
        
        $file_cont = '<div class="pwelement pwelement_'. $el_id .' pwe_logotypes">' . $output . '</div>';

        if ($input_replace_array_html && $output_replace_array_html) {
            $file_cont = str_replace($input_replace_array_html, $output_replace_array_html, $file_cont);
        }

        return $file_cont;
    }
}
