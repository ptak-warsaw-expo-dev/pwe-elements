<?php

/**
 * Class PWEMap3D
 * Extends PWEMap class and defines a custom Visual Composer element.
 */
class PWEMap3D extends PWEMap {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {

        extract( shortcode_atts( array(
            'map_type' => '',
            'map_color' => '',
            'map_overlay' => '',
            'map_image' => '',
        ), $atts ));

        $map_overlay = !empty($atts['map_overlay']) ? $atts['map_overlay'] : 'inherit';

        // CSS <----------------------------------------------------------------------------------------------<
        require_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php';

        $map_image_src = !empty($atts['map_image']) ? wp_get_attachment_url($atts['map_image']) : '/doc/numbers.webp';
        $map_image_availible = (!empty($atts['map_image']) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/numbers.webp')) ? true : false;

        $output .= '
            <div id="pweMap" class="pwe-map">
                <div id="container-3d" class="pwe-map__container-3d">';

                    if ($map_image_availible) {
                        $output .= '
                        <div class="pwe-map__numbers">
                            <img src="'. $map_image_src .'"/>
                        </div>';
                    }

                    $output .= '
                    <div class="pwe-map__canvas-overlay"></div>
                </div>
            </div>
        ';

        return $output;
    }
}