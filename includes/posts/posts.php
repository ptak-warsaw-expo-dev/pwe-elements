<?php

/**
 * Class PWEPosts
 */
class PWEPosts extends PWECommonFunctions {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {

        // AJAX load more posts
        add_action('init', array($this, 'load_more_posts'));
        // Hook actions
        add_action('init', array($this, 'initVCMapPwePosts'));
        add_shortcode('pwe_posts', array($this, 'pwePostsOutput'));
    }

    /**
     * Function to handle the AJAX request
     */
    public function load_more_posts() {
        require_once plugin_dir_path(__FILE__) . 'assets/ajax.php';
    }

    /**
     * Initialize VC Map PWEPosts.
     */
    public function initVCMapPwePosts() {

        require_once plugin_dir_path(__FILE__) . 'classes/posts-full/posts_full.php';

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Posts', 'pwe_posts'),
                'base' => 'pwe_posts',
                'category' => __( 'PWE Elements', 'pwe_posts'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'params' => array_merge(
                    array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select mode', 'pwe_posts'),
                            'param_name' => 'posts_modes',
                            'save_always' => true,
                            'admin_label' => true,
                            'value' => array(
                                'Full mode (tiles)' => 'PWEPostsFull',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Categories', 'pwe_posts'),
                            'param_name' => 'posts_category',
                            'save_always' => true,
                            'admin_label' => true,
                            'dependency' => array(
                                'element' => 'posts_modes',
                                'value' => array(
                                    'PWEPostsFull',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Posts per', 'pwe_posts'),
                            'param_name' => 'posts_per_page',
                            'save_always' => true,
                            'admin_label' => true,
                            'dependency' => array(
                                'element' => 'posts_modes',
                                'value' => array(
                                    'PWEPostsFull',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Posts count', 'pwe_posts'),
                            'param_name' => 'posts_count',
                            'save_always' => true,
                            'admin_label' => true,
                            'dependency' => array(
                                'element' => 'posts_modes',
                                'value' => array(
                                    'PWEPostsFull',
                                ),
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
            'PWEPostsFull'      => 'classes/posts-full/posts_full.php',
        );
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public function pwePostsOutput($atts) {

        extract( shortcode_atts( array(
            'posts_modes' => '',
            'posts_category' => '',
            'posts_per_page' => '',
            'posts_count' => '',
        ), $atts ));

        if ($this->findClassElements()[$posts_modes]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$posts_modes];

            if (class_exists($posts_modes)) {
                $output_class = new $posts_modes;
                $output = $output_class->output($atts);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $posts_modes .' does not exist")</script>';
                $output = '';
            }
        } else {
            echo '<script>console.log("File with class ' . $posts_modes .' does not exist")</script>';
        }

        $output = do_shortcode($output);

        return $output;

    }
}