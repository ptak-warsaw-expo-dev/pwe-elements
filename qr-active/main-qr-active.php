<?php

class PWEQRActive {
    public static $rnd_id;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        self::$rnd_id = rand(10000, 99999);

        add_action('init', array($this, 'initVCMapPWEQRActive'));
        add_shortcode('pwe_qr_active', array($this, 'PWEQRActiveOutput'));
    }

    /**
     * Initialize VC Map PWEQRActive.
     */
    public function initVCMapPWEQRActive() {

        require_once plugin_dir_path(__FILE__) . 'qr-active-start.php';

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE QR Active', 'pwe_qr_active'),
                'base' => 'pwe_qr_active',
                'category' => __( 'PWE Elements', 'pwe_qr_active'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __FILE__ )) . 'backend/backendstyle.css',
                'params' => array_merge(
                    array( 
                        array(
                            'type' => 'dropdown',
                            'group' => 'main',
                            'heading' => __( 'QR Active', 'pwe_qr_active'),
                            'param_name' => 'qr_active_step',
                            'description' => __( 'Select step.', 'pwe_qr_active'),
                            'value' => array(
                                'QR Active Start'         => 'PWElementQRActiveStart',
                                // 'QR Active A'             => 'PWElementQRActiveA',
                                // // 'QR Active B'             => 'PWElementQRActiveB',
                                // 'QR Active C'             => 'PWElementQRActiveC',
                            ),
                            'save_always' => true,
                            'admin_label' => true
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
            'PWElementQRActiveStart'             => 'qr-active-start.php'
        ); 
    }

    /**
     * Laguage check for text
     * 
     * @param string $pl text in Polish.
     * @param string $en text in English.
     * @return string 
     */
    public static function languageChecker($pl, $en = ''){
        if(get_locale() == 'pl_PL'){ 
            return $pl;
        } else {
            return $en;
        }
    }

    /**
     * Output method for pwe_qr_active shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWEQRActiveOutput($atts, $content = null) {

        if ($this->findClassElements()[$pwe_qr_active]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$pwe_qr_active];
            
            if (class_exists($pwe_qr_active)) {
                $output_class = new $pwe_qr_active;
                $output = $output_class->output($atts, $content);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $pwe_qr_active .' does not exist")</script>';
                $output = '';
            }
        } else {
            echo '<script>console.log("File with class ' . $pwe_qr_active .' does not exist")</script>';
        }
        
        $output = do_shortcode($output);

        $output_html = '<div id="PWEQRActive" class="pwe-qr-active">'. $output .'</div>';

        return $output_html;
    }
}