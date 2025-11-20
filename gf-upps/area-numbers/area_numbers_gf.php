<?php 

class GFAreaNumbersField {
    public $newProperty = 'value';

    function __construct() {
        if ( is_admin() ) {
            add_action( 'plugins_loaded', array( $this, 'GF_admin_init' ), 14 );
        }
        else {
            add_action( 'plugins_loaded', array( $this, 'frontend_init' ), 14 );
        }
    }

    /**
     * Init frontend
     */
    function frontend_init() {
        require_once( plugin_dir_path( __FILE__ ) . 'area-frontend.php' );
        require_once( plugin_dir_path( __FILE__ ) . 'area-codes.php' );
    }

    /**
     * Init admin side
     */
    function GF_admin_init() {
        require_once( plugin_dir_path( __FILE__ ) . 'area-backend.php' );
        require_once( plugin_dir_path( __FILE__ ) . 'area-codes.php' );
    }  
}