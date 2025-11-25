<?php

/**
 * Class PWElementSingleImage
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementSingleImage extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'attach_image',
                'group' => 'PWE Element',
                'heading' => __('Media', 'pwe_element'),
                'param_name' => 'single_image_media',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementSingleImage',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Image src', 'pwe_element'),
                'param_name' => 'single_image_src',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementSingleImage',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Image style', 'pwe_element'),
                'param_name' => 'single_image_style',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementSingleImage',
                ),
            ),
        );
        return $element_output;
    }

    public static function output($atts) {

        extract( shortcode_atts( array(
            'single_image_media' => '',
            'single_image_src' => '',
            'single_image_style' => '',
        ), $atts ));

        $attachment_id = $single_image_media;

        $single_image_media = wp_get_attachment_url($single_image_media);

        $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

        $alt_text = !empty($alt_text) ? $alt_text : 'single image';

        if (!empty($single_image_src)) {
            $image_src = $single_image_src;
        } else if (!empty($single_image_media)) {
            $image_src = $single_image_media;
        } else $image_src = '';

        $output = '
        <style>
            .pwelement_'. self::$rnd_id .' .pwe-single-image {
                text-align: center;
            }
            .pwelement_'. self::$rnd_id .' .pwe-single-image img {
                width: 100%;
            }
        </style>

        <div id="pweSingleImage" class="pwe-single-image">
            '. ((!empty($image_src)) ? '<img src="'. $image_src .'" style="'. $single_image_style .'" alt="'. $alt_text .'"/>' : '') .'
        </div>';


        return $output;
    }
}