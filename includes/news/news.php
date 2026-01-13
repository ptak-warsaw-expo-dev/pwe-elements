<?php
/**
 * Class PWENews
 * Główny manager szablonów newsa – wybiera katalog po nazwie klasy, ładuje plik PHP i opcjonalne assety.
 */
class PWENews extends PWECommonFunctions {

    public static $rnd_id;

    /** Konstruktor: rejestruje VC map i shortcode. */
    public function __construct() {
        self::$rnd_id = rand(10000, 99999);

        add_action('init', array($this, 'initVCMapPWENews'));
        add_shortcode('pwe_news', array($this, 'PWENewsOutput'));
    }

    /** Rejestracja elementu w WPBakery/VC – wyłącznie dropdown z wyborem szablonu. */
    public function initVCMapPWENews() {
        if (!class_exists('Vc_Manager')) return;

        // 1) Zawsze mamy dropdown wyboru szablonu
        $params = array(
            array(
                'type' => 'dropdown',
                'heading' => __('Select news template', 'pwe_news'),
                'param_name' => 'news_template_type',
                'save_always' => true,
                'admin_label' => true,
                'value' => array(
                    __('News Summary', 'pwe_news') => 'PWENewsSummary',
                    __('News Upcoming', 'pwe_news') => 'PWENewsUpcoming',
                    __('News Say About Us', 'pwe_news') => 'PWENewsSayAboutUs',
                    // __('News Interview', 'pwe_news') => 'PWENewsInterview',
                ),
                'std' => 'PWENewsSummary',
            ),
        );

        // 2) Dołącz parametry z poszczególnych szablonów (jeśli istnieją)
        foreach ($this->getTemplatesMap() as $class_name => $subdir) {
            $file = $this->getClassFile($class_name);
            if ($file && file_exists($file)) {
                require_once $file;
                if (class_exists($class_name) && method_exists($class_name, 'initElements')) {
                    $fields = call_user_func([$class_name, 'initElements']);
                    if (is_array($fields) && !empty($fields)) {
                        // Doklej pola danego szablonu do globalnych parametrów elementu VC
                        $params = array_merge($params, $fields);
                    }
                }
            }
        }

        // 3) Rejestracja elementu z pełną listą parametrów
        vc_map(array(
            'name' => __('PWE News', 'pwe_news'),
            'base' => 'pwe_news',
            'category' => __('PWE Elements', 'pwe_news'),
            'admin_enqueue_css' => plugin_dir_url(dirname(__DIR__)) . 'backend/backendstyle.css',
            'params' => $params,
        ));
    }

    private function getTemplatesMap() {
        return array(
            'PWENewsSummary' => 'classes/news-summary/',
            'PWENewsUpcoming' => 'classes/news-upcoming/',
            'PWENewsSayAboutUs' => 'classes/news-say-about-us/',
            // 'PWENewsInterview' => 'classes/news-interview/',
        );
    }

    private function getBasenameFromType($template_type) {
        if ($template_type === 'PWENewsSayAboutUs') return 'news-say-about-us';
        
        if (strpos($template_type, 'PWENews') !== 0) return false;
        return strtolower(str_replace('PWENews', 'news-', $template_type));
    }

    private function getClassFile($template_type) {
        $template_map = $this->getTemplatesMap();
        $base_name    = $this->getBasenameFromType($template_type);

        if (!$base_name || !isset($template_map[$template_type])) return false;

        $template_subdir = $template_map[$template_type];

        return trailingslashit(plugin_dir_path(__FILE__)) . $template_subdir . $base_name . '.php';
    }

    private function enqueueAssets($template_type) {
        $template_map = $this->getTemplatesMap();
        $base_name    = $this->getBasenameFromType($template_type);

        if (!$base_name || !isset($template_map[$template_type])) return;

        $template_subdir = $template_map[$template_type];

        $template_dir = trailingslashit(plugin_dir_path(__FILE__)) . $template_subdir;
        $template_url = trailingslashit(plugin_dir_url(__FILE__)) . $template_subdir;

        $css_file = $base_name . '-style.css';
        $js_file  = $base_name . '-scripts.js';

        if (file_exists($template_dir . $css_file)) {
            wp_enqueue_style('pwe-news-' . $template_type, $template_url . $css_file, array(), null);
        }

        if (file_exists($template_dir . $js_file)) {
            wp_enqueue_script('pwe-news-' . $template_type, $template_url . $js_file, array('jquery'), null, true);
        }
    }

    public function PWENewsOutput($atts, $content = null) {
        // NIE używaj shortcode_atts do „ucięcia” wszystkiego
        $atts = (array) $atts;
        if (!isset($atts['news_template_type'])) {
            $atts['news_template_type'] = '';
        }

        $template_type = trim($atts['news_template_type']);
        if ($template_type === '') return '';

        $template_file = $this->getClassFile($template_type);
        if (!$template_file || !file_exists($template_file)) return '';

        require_once $template_file;
        if (!class_exists($template_type) || !method_exists($template_type, 'output')) return '';

        $instance = new $template_type();

        // UJEDNOLIĆ SYGNATURĘ: lepiej, by output przyjmował ($atts, $content = null)
        $html = $instance->output($atts, $content);

        $html = do_shortcode($html);

        if (!empty($html)) {
            $this->enqueueAssets($template_type);
        }

        return '<div id="PWENews" class="pwe-news-' . esc_attr(self::$rnd_id) . '">' . $html . '</div>';
    }
}
