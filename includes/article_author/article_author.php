<?php
/**
 * Class PWEArticleAuthorManager
 * Główny manager elementu Article Author – wybiera katalog po nazwie klasy,
 * ładuje plik PHP i opcjonalne assety.
 */
class PWEArticleAuthorManager extends PWElements {

    public static $rnd_id;

    public function __construct() {
        self::$rnd_id = rand(10000, 99999);

        add_action('init', array($this, 'initVCMapPWEArticleAuthor'));
        add_shortcode('pwe_article_author', array($this, 'PWEArticleAuthorOutput'));
    }

    public function initVCMapPWEArticleAuthor() {
        if (!class_exists('Vc_Manager')) return;

        $params = array(
            array(
                'type' => 'dropdown',
                'heading' => __('Select article author template', 'pwelement'),
                'param_name' => 'article_author_template_type',
                'save_always' => true,
                'admin_label' => true,
                'value' => array(
                    __('Article Author', 'pwelement') => 'PWEArticleAuthor',
                ),
                'std' => 'PWEArticleAuthor',
            ),
        );

        foreach ($this->getTemplatesMap() as $class_name => $subdir) {
            $file = $this->getClassFile($class_name);

            if ($file && file_exists($file)) {
                require_once $file;

                if (class_exists($class_name) && method_exists($class_name, 'initElements')) {
                    $fields = call_user_func(array($class_name, 'initElements'));

                    if (is_array($fields) && !empty($fields)) {
                        $params = array_merge($params, $fields);
                    }
                }
            }
        }

        vc_map(array(
            'name' => __('PWE Article Author', 'pwelement'),
            'base' => 'pwe_article_author',
            'category' => __('PWE Elements', 'pwelement'),
            'admin_enqueue_css' => plugin_dir_url(__FILE__) . 'backend/backendstyle.css',
            'params' => $params,
        ));
    }

    private function getTemplatesMap() {
        return array(
            'PWEArticleAuthor' => 'assets/',
        );
    }

    private function getBasenameFromType($template_type) {
        if ($template_type !== 'PWEArticleAuthor') return false;
        return 'article_author';
    }

    private function getClassFile($template_type) {
        $template_map = $this->getTemplatesMap();
        $base_name = $this->getBasenameFromType($template_type);

        if (!$base_name || !isset($template_map[$template_type])) return false;

        $template_subdir = $template_map[$template_type];

        return trailingslashit(plugin_dir_path(__FILE__)) . $template_subdir . $base_name . '.php';
    }

    private function enqueueAssets($template_type) {
        $template_map = $this->getTemplatesMap();
        $base_name = $this->getBasenameFromType($template_type);

        if (!$base_name || !isset($template_map[$template_type])) return;

        $template_subdir = $template_map[$template_type];

        $template_dir = trailingslashit(plugin_dir_path(__FILE__)) . $template_subdir;
        $template_url = trailingslashit(plugin_dir_url(__FILE__)) . $template_subdir;

        $css_file = $base_name . '.css';
        $js_file  = $base_name . '.js';

        if (file_exists($template_dir . $css_file)) {
            wp_enqueue_style(
                'pwe-article-author-' . $template_type,
                $template_url . $css_file,
                array(),
                null
            );
        }

        if (file_exists($template_dir . $js_file)) {
            wp_enqueue_script(
                'pwe-article-author-' . $template_type,
                $template_url . $js_file,
                array('jquery'),
                null,
                true
            );
        }
    }

    public function PWEArticleAuthorOutput($atts, $content = null) {
        $atts = (array) $atts;

        if (!isset($atts['article_author_template_type']) || empty($atts['article_author_template_type'])) {
            $atts['article_author_template_type'] = 'PWEArticleAuthor';
        }

        $template_type = trim($atts['article_author_template_type']);
        if ($template_type === '') return '';

        $template_file = $this->getClassFile($template_type);
        if (!$template_file || !file_exists($template_file)) return '';

        require_once $template_file;

        if (!class_exists($template_type) || !method_exists($template_type, 'output')) return '';

        $instance = new $template_type();
        $html = $instance->output($atts, $content);

        $html = do_shortcode($html);

        if (!empty($html)) {
            $this->enqueueAssets($template_type);
        }

        return '<div id="PWEArticleAuthor" class="pwe-article-author-' . esc_attr(self::$rnd_id) . '">' . $html . '</div>';
    }
}