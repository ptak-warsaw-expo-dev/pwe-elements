<?php
/**
 * Class PWEAttractions
 */
class PWEAttractions extends PWECommonFunctions {

    public static $rnd_id;

    public function __construct() {
        self::$rnd_id = rand(10000, 99999);

        add_action('init', array($this, 'initVCMapPWEAttractions'));
        add_shortcode('pwe_attractions', array($this, 'PWEAttractionsOutput'));
    }

    public function initVCMapPWEAttractions() {

        require_once plugin_dir_path(__FILE__) . 'classes/attractions-slider/attractions-slider.php';

        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __('PWE attractions', 'pwe_attractions'),
                'base' => 'pwe_attractions',
                'category' => __('PWE Elements', 'pwe_attractions'),
                'admin_enqueue_css' => plugin_dir_url(dirname(__DIR__)) . 'backend/backendstyle.css',
                'params' => array_merge(
                    array(
                        array(
                            'type' => 'textfield',
                            'heading' => __('Title PL', 'pwe_attractions'),
                            'param_name' => 'pwe_attractions_title_pl',
                            'param_holder_class' => 'backend-area-half-width',
                            'admin_label' => true,
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Title EN', 'pwe_attractions'),
                            'param_name' => 'pwe_attractions_title_en',
                            'param_holder_class' => 'backend-area-half-width',
                            'admin_label' => true,
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __('Description PL', 'pwe_attractions'),
                            'param_name' => 'pwe_attractions_desc_pl',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __('Description EN', 'pwe_attractions'),
                            'param_name' => 'pwe_attractions_desc_en',
                            'param_holder_class' => 'backend-area-half-width',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select attractions type', 'pwe_attractions'),
                            'param_name' => 'attractions_type',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(
                                'Slider' => 'PWEAttractionsSlider',
                            ),
                        ),
                        ...PWEAttractionsSlider::initElements(),
                    )
                ),
            ));
        }
    }

    /**
     * Ścieżka do pliku klasy na podstawie typu.
     */
    private function getClassFile($type) {
        switch ($type) {
            case 'PWEAttractionsSlider':
                return 'classes/attractions-slider/attractions-slider.php';
            default:
                return false;
        }
    }

    /**
     * Dołączenie style.css i scripts.js z katalogu elementu.
     */
    private function enqueueAssets($type) {
        switch ($type) {
            case 'PWEAttractionsSlider':
                $rel_dir = 'classes/attractions-slider/';
                break;
            default:
                return;
        }

        $base_path = trailingslashit(plugin_dir_path(__FILE__)) . $rel_dir;
        $base_url  = trailingslashit(plugin_dir_url(__FILE__)) . $rel_dir;

        if (file_exists($base_path . 'style.css')) {
            wp_enqueue_style(
                'pwe-attractions-' . $type,
                $base_url . 'style.css'
            );
        }

        if (file_exists($base_path . 'scripts.js')) {
            wp_enqueue_script(
                'pwe-attractions-' . $type,
                $base_url . 'scripts.js',
                array('jquery'),
                false,
                true
            );
        }
    }

    public function PWEAttractionsOutput($atts, $content = null) {

        extract(shortcode_atts(array(
            'attractions_type'         => '',
            'pwe_attractions_title_pl' => '',
            'pwe_attractions_title_en' => '',
            'pwe_attractions_desc_pl'  => '',
            'pwe_attractions_desc_en'  => '',
        ), $atts));

        $class_file = $this->getClassFile($attractions_type);
        $output = '';

        if ($class_file) {
            require_once plugin_dir_path(__FILE__) . $class_file;

            if (class_exists($attractions_type)) {
                $output_class = new $attractions_type;
                $output = $output_class->output($atts, $content);
            } else {
                echo '<script>console.log("Class ' . esc_js($attractions_type) . ' does not exist")</script>';
            }
        } else {
            echo '<script>console.log("File for class ' . esc_js($attractions_type) . ' does not exist")</script>';
        }

        $output = do_shortcode($output);

        if (!empty($output)) {
            $this->enqueueAssets($attractions_type);
        }

        // Kontener główny – bez ID/klas typu, bo to będzie w środku elementu
        return '<div id="PWEAttractions" class="pwe-attractions-' . self::$rnd_id . '">' . $output . '</div>';
    }
}
