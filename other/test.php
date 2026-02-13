<?php

/**
 * Class PWETest
 * Extends maps class and defines a custom Visual Composer element for vouchers.
 */
class PWETest extends PWECommonFunctions {

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        add_action('init', array($this, 'initTest'));
        add_shortcode('pwe_test', array($this, 'PWETestOutput'));
    }

        /**
     * Initialize VC Map PWEMap.
     */
    public function initTest() {

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Test', 'pwe_test'),
                'base' => 'pwe_test',
                'category' => __( 'PWE Elements', 'pwe_test'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
            ));
        }
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public function PWETestOutput() {
       
    }
}


