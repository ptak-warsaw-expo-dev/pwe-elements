<?php

/**
 * Class PWElementStickyButtons
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementStickyButtons extends PWElements {

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
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Title', 'pwelement'),
                'param_name' => 'sticky_buttons_title',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'PWE Element',
                'heading' => __('Background kolor (default akcent)', 'pwelement'),
                'param_name' => 'sticky_buttons_cropped_background',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Aspect ratio (default 21/9)', 'pwelement'),
                'param_name' => 'sticky_buttons_aspect_ratio',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Font size buttons (default 12px)', 'pwelement'),
                'param_name' => 'sticky_buttons_font_size',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Width buttons (default 170px)', 'pwelement'),
                'param_name' => 'sticky_buttons_width',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'PWE Element',
                'heading' => __('Background full size kolor (default white)', 'pwelement'),
                'param_name' => 'sticky_buttons_full_size_background',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Aspect ratio full size (default 1/1)', 'pwelement'),
                'param_name' => 'sticky_buttons_aspect_ratio_full_size',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Font size full size buttons (default 16px)', 'pwelement'),
                'param_name' => 'sticky_buttons_font_size_full_size',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Width full width buttons (default 170px)', 'pwelement'),
                'param_name' => 'sticky_full_width_buttons_width',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide all sections except the first one', 'pwelement'),
                'param_name' => 'sticky_hide_sections',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show dropdown buttons (mobile in default shown)', 'pwelement'),
                'param_name' => 'sticky_buttons_dropdown',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide dropdown buttons for mobile', 'pwelement'),
                'param_name' => 'sticky_buttons_dropdown_mobile',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show full size buttons', 'pwelement'),
                'param_name' => 'sticky_buttons_full_size',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'description' => __('Turn on full size images', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Turn off auto scrolling', 'pwelement'),
                'param_name' => 'sticky_buttons_scroll',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'description' => __('Turn on full size images', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide buttons mini', 'pwelement'),
                'param_name' => 'sticky_buttons_mini_hide',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'description' => __('Hide buttons mini', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show info text show', 'pwelement'),
                'param_name' => 'sticky_buttons_info_text_show',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'description' => __('Nad: Wybierz jeden z poniższych aby dowiedzieć się więcej. / Select one of the following to find out more. Pod: Więcej wydarzeń konferencyjnych pojawi się wkrótce. / More conference events will follow soon.', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Show info text bottom', 'pwelement'),
                'param_name' => 'sticky_buttons_info_text_bottom',
                'param_holder_class' => 'backend-area-one-fifth-width',
                'description' => __('PL: Więcej wydarzeń konferencyjnych pojawi się wkrótce. / EN: More conference events will follow soon.', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Name parameter for sections & rows', 'pwelement'),
                'description' => __('Default "konferencja". Enter this name into a section or row as a class (Ex. link "www/wydarzenia/?konferencja=szkolenie") - (domain/page/?class=id)', 'pwelement'),
                'param_name' => 'sticky_buttons_parameter',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Additional options',
                'heading' => __('Static text', 'pwelement'),
                'param_name' => 'sticky_buttons_text_static',
                'description' => __('position: static;', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Additional options',
                'heading' => __('Cursor unset items', 'pwelement'),
                'param_name' => 'sticky_buttons_cursor_unset',
                'description' => __('cursor: unset;', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Additional options',
                'heading' => __('Limit width', 'pwelement'),
                'param_name' => 'sticky_buttons_limit_width',
                'description' => __('max-width: 1200px;', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Additional options',
                'heading' => __('Auto slider', 'pwelement'),
                'param_name' => 'sticky_buttons_auto_slider',
                'param_holder_class' => 'backend-area-half-width',
                'description' => __('Turn on slider for full size sticky buttons', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Additional options',
                'heading' => __('Show strip', 'pwelement'),
                'param_name' => 'sticky_buttons_show_strip',
                'param_holder_class' => 'backend-area-half-width',
                'description' => __('Turn on to show strip', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Additional options',
                'heading' => __('Slides to show', 'pwelement'),
                'param_name' => 'sticky_buttons_slides_to_show',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'param_name' => 'sticky_buttons',
                'heading' => __('Buttons', 'pwelement'),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementStickyButtons',
                ),
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Select Image', 'pwelement'),
                        'param_name' => 'sticky_buttons_images',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Select Full Size Image', 'pwelement'),
                        'param_name' => 'sticky_buttons_full_size_images',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Background color button', 'pwelement'),
                        'description' => __('Jeżeli jest dodatkowo dodany obrazek to ma większy priorytet', 'pwelement'),
                        'param_name' => 'sticky_buttons_color_bg',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Button text', 'pwelement'),
                        'param_name' => 'sticky_buttons_color_text',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Button link', 'pwelement'),
                        'param_name' => 'sticky_buttons_link',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Button id (PRZECZYTAJ!)', 'pwelement'),
                        'description' => __('Wpisując tutaj ID musisz dodać taki sam ID w elemencie który chcesz ukryć.', 'pwelement'),
                        'param_name' => 'sticky_buttons_id',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'Additional options',
                'heading' => __('Breakpoints (slidesPerView)', 'pwe_element'),
                'param_name' => 'sticky_buttons_breakpoints',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementStickyButtons',
                ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Min. Width (px)', 'pwe_element'),
                        'param_name' => 'sticky_buttons_breakpoint_width',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Slides Per View', 'pwe_element'),
                        'param_name' => 'sticky_buttons_breakpoint_slides',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                ),
            ),
        );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';

        extract( shortcode_atts( array(
            'sticky_buttons' => '',
            'sticky_buttons_dropdown' => '',
            'sticky_buttons_dropdown_mobile' => '',
            'sticky_buttons_full_size' => '',
            'sticky_buttons_cropped_background' => '',
            'sticky_buttons_full_size_background' => '',
            'sticky_buttons_aspect_ratio' => '',
            'sticky_buttons_aspect_ratio_full_size' => '',
            'sticky_hide_sections' => '',
            'sticky_buttons_font_size' => '',
            'sticky_buttons_font_size_full_size' => '',
            'sticky_buttons_width' => '',
            'sticky_full_width_buttons_width' => '',
            'sticky_buttons_mini_hide' => '',
            'sticky_buttons_parameter' => '',
            'sticky_buttons_scroll' => '',
            'sticky_buttons_info_text_show' => '',
            'sticky_buttons_info_text_bottom' => '',
            'sticky_buttons_title' => '',
            'sticky_buttons_text_static' => '',
            'sticky_buttons_cursor_unset' => '',
            'sticky_buttons_limit_width' => '',
            'sticky_buttons_auto_slider' => '',
            'sticky_buttons_slides_to_show' => '',
            'sticky_buttons_show_strip' => '',
            'sticky_buttons_breakpoints' => '',
        ), $atts ));

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        // Turn on dropdown on mobile
        if ($mobile == 1 && $sticky_buttons_dropdown_mobile != true) {
            $sticky_buttons_dropdown = "true";
        }

        $sticky_buttons_width = ($sticky_buttons_width == '') ? '170px' : $sticky_buttons_width;
        $sticky_full_width_buttons_width = ($sticky_full_width_buttons_width == '') ? '170px' : $sticky_full_width_buttons_width;
        $sticky_buttons_font_size_full_size = ($sticky_buttons_font_size_full_size == '') ? '16px' : $sticky_buttons_font_size_full_size;
        $sticky_buttons_font_size = ($sticky_buttons_font_size == '') ? '12px' : $sticky_buttons_font_size;

        $sticky_buttons_parameter = ($sticky_buttons_parameter == '') ? 'konferencja' : $sticky_buttons_parameter;

        if (get_locale() == 'pl_PL') {
            $sticky_buttons_info_text_bottom = (empty($sticky_buttons_info_text_bottom)) ? 'Więcej wydarzeń konferencyjnych pojawi się wkrótce.' : $sticky_buttons_info_text_bottom;
        } else {
            $sticky_buttons_info_text_bottom = (empty($sticky_buttons_info_text_bottom)) ? 'More conference events will follow soon.' : $sticky_buttons_info_text_bottom;
        }

        $output = '
            <style>
                #page-header {
                    position: relative;
                    z-index: 11;
                }
                .pwelement_'. self::$rnd_id .' {
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                .row-parent:has(.pwelement_'. self::$rnd_id .' .custom-container-sticky-buttons) {
                    padding: 0 !important;
                    max-width: 100% !important;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped {
                    position: relative;
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    padding: 28px 18px;
                    width: 100%;
                    gap: 24px;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size {
                    background-color: white;
                    z-index: 11;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped-container {
                    flex-direction: column;
                    width: 100%;
                    top: 0;
                    z-index: 10;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-head-container {
                    padding: 10px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                    cursor: pointer;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-head-container * {
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item {
                    text-align: center;
                    z-index: 8;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item.active {
                    transform: scale(1.1);
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped .custom-sticky-button-item {
                    max-width: ' . $sticky_buttons_width . ' !important;
                    min-width: ' . $sticky_buttons_width . ' !important;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item:hover {
                    transform: scale(1.1) !important;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item span {
                    padding: 5px;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item img,
                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item div {
                    border-radius: 8px;
                    width: 100%;
                    object-fit: cover;
                    cursor: pointer;
                    text-transform: uppercase;
                    font-size: 12px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    ' . $text_color . ';
                    font-weight: 600;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size .custom-sticky-button-item div {
                    font-size: ' . $sticky_buttons_font_size_full_size . ' !important;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped .custom-sticky-button-item div {
                    font-size: ' . $sticky_buttons_font_size . ' !important;
                }
                .pwelement_'. self::$rnd_id .' .custom-button-cropped {
                    aspect-ratio: 21/9;
                }
                .pwelement_'. self::$rnd_id .' .custom-button-full-size {
                    aspect-ratio: 1/1;
                }
                .pwelement_'. self::$rnd_id .' .custom-container-sticky-buttons .fa-chevron-down {
                    transition: 0.3s ease !important;
                }
                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item {
                    position: relative;
                    display: inline-block;
                    transition: ease .3s;
                }
                .pwelement_'. self::$rnd_id .' .custom-image-button {
                    display: block;
                    width: 100%;
                    height: auto;
                }
                .pwelement_'. self::$rnd_id .' .custom-image-button-text {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    color: white;
                    padding: 5px 10px;
                    font-size: 16px;
                }
                .pwelement_'. self::$rnd_id .' .sticky-buttons-info-top,
                .pwelement_'. self::$rnd_id .' .sticky-buttons-info-bottom {
                    position: relative;
                    z-index: 11;
                    background: white;
                    display: flex;
                    justify-content: center;
                    text-align: center;
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' .sticky-buttons-info-top {
                    padding: 18px 36px 0;
                }
                .pwelement_'. self::$rnd_id .' .sticky-buttons-info-bottom {
                    padding: 0 36px 18px;
                }
                .pwelement_'. self::$rnd_id .' .sticky-pin {
                    position: fixed !important;
                    top: 0;
                    right: 0;
                    left: 0;
                }
                .'. $sticky_buttons_parameter .' {
                    display: none;
                }
                @media (max-width: 600px) {
                    .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                        margin: 0 auto;
                        gap: 20px;
                    }
                    .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped .custom-sticky-button-item {
                        max-width: 140px !important;
                        min-width: 140px !important;
                    }
                    .pwelement_'. self::$rnd_id .' .custom-sticky-button-item:hover {
                        transform: unset;
                    }
                }
            </style>';

            if ($sticky_buttons_auto_slider != true) {
                $output .= '<style>
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size {
                                    position: relative;
                                    display: flex;
                                    flex-wrap: wrap;
                                    justify-content: center;
                                    padding: 28px 18px;
                                    width: 100%;
                                    gap: 24px;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size .custom-sticky-button-item {
                                    max-width: ' . $sticky_full_width_buttons_width . ' !important;
                                    min-width: ' . $sticky_full_width_buttons_width . ' !important;
                                }
                                @media (max-width: 600px) {
                                    .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size .custom-sticky-button-item {
                                        max-width: 140px !important;
                                        min-width: 140px !important;
                                    }
                                }
                            </style>';
            } else {
                $output .= '<style>
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size {
                                    gap: 0;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size .custom-sticky-button-item {
                                    margin: 18px;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size .custom-sticky-button-item img {
                                    max-width: 200px;
                                    margin: 0 auto;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item:hover {
                                    transform: scale(1.03) !important;
                                }
                                .pwelement_'. self::$rnd_id .' .slick-dotted.slick-slider {
                                    margin-bottom: 0;
                                }
                            </style>';
            }

            if ($sticky_buttons_text_static) {
                $output .= '<style>
                                .row-parent:has(.pwelement_'. self::$rnd_id .' .custom-container-sticky-buttons) {
                                    padding: 36px !important;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-image-button-text {
                                    position: static;
                                    transform: none;
                                    padding-top: 18px;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item div {
                                    text-transform: unset;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-full-size,
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped {
                                    padding: 18px 0;
                                }
                            </style>';
            }
            if ($sticky_buttons_cursor_unset) {
                $output .= '<style>
                                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item img,
                                .pwelement_'. self::$rnd_id .' .custom-sticky-button-item div {
                                    cursor: unset !important;
                                }
                            </style>';
            }
            if ($sticky_buttons_limit_width) {
                $output .= '<style>
                                .row-parent:has(.pwelement_'. self::$rnd_id .' .custom-container-sticky-buttons) {
                                    padding: 36px 36px 36px 36px;
                                    max-width: 1200px !important;
                                }
                            </style>';
            }

            if ($sticky_buttons_dropdown === "true") {
                $output .= '<style>
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped {
                                    display: flex;
                                    max-height: 0;
                                    overflow: hidden;
                                    padding: 0;
                                    transition: 0.5s ease;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped.open {
                                    max-height: 100%;
                                    padding: 28px 18px;
                                }
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped:before {
                                    content: "";
                                    background-color: rgba(255, 255, 255, 0.1);
                                    width: 100%;
                                    height: 100%;
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    z-index: 7;
                                }
                            </style>';
            }
            if ($sticky_buttons_full_size === "true") {
                $output .= '<style>
                                .pwelement_'. self::$rnd_id .' .custom-sticky-buttons-cropped-container {
                                    position: absolute;
                                }
                            </style>';
            }

            $sticky_buttons_urldecode = urldecode($sticky_buttons);
            $sticky_buttons_json = json_decode($sticky_buttons_urldecode, true);

            $buttons_urls = array();
            $full_size_buttons_urls = array();
            $buttons_id = array();
            $buttons_links = array();

            $unique_id = rand(10000, 99999);
            $element_unique_id = 'stickyButtons-' . $unique_id;

            $output .= '<div id="'. $element_unique_id .'" class="custom-container-sticky-buttons">';
                if (!empty($sticky_buttons_title)) {
                    $output .= '
                    <div class="sticky-buttons-title main-heading-text">
                        <h4 class="pwe-uppercase">'. $sticky_buttons_title .'</h4>
                    </div>';
                }
                if ($sticky_buttons_full_size === "true") {
                    if ($sticky_buttons_info_text_show == true) {
                        $output .= '<p class="sticky-buttons-info-top">'.
                        self::languageChecker(
                            <<<PL
                            Wybierz jeden z poniższych aby dowiedzieć się więcej.
                            PL,
                            <<<EN
                            Select one of the following to find out more.
                            EN
                        ) .'</p>';
                    }

                    if (is_array($sticky_buttons_json)) {
                        foreach ($sticky_buttons_json as $sticky_button) {
                            $button_id = $sticky_button["sticky_buttons_id"];
                            if (!empty($button_id)) {
                                $section_id = str_replace("-btn", "", $button_id);
                                $output .= '
                                <style>
                                    #'. $section_id .' {
                                        opacity: 0;
                                    }
                                </style>';
                            }
                        }
                    }

                    $output .= '
                    <div class="custom-sticky-buttons-full-size pwe-slides" style="background-color:'. $sticky_buttons_full_size_background .'!important;">';

                        if (is_array($sticky_buttons_json)) {
                            foreach ($sticky_buttons_json as $sticky_button) {

                                $attachment_full_size_img_id = $sticky_button["sticky_buttons_full_size_images"];
                                $link = $sticky_button["sticky_buttons_link"];
                                $button_id = $sticky_button["sticky_buttons_id"];
                                $button_color = $sticky_button["sticky_buttons_color_bg"];
                                $button_text = $sticky_button["sticky_buttons_color_text"];
                                $image_full_size_url = wp_get_attachment_url($attachment_full_size_img_id);
                                $full_size_buttons_urls[] = $image_full_size_url;

                                $target_blank = (strpos($link, 'http') !== false) ? 'target="blank"' : '';

                                if (!empty($image_full_size_url)) {
                                    if (!empty($link)) {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">
                                                        <a href="'. $link .'" '. $target_blank .'><img style="aspect-ratio:'. $sticky_buttons_aspect_ratio_full_size .';" class="custom-image-button custom-button-full-size" src="' . esc_url($image_full_size_url) . '" alt="sticky-button-'. $attachment_full_size_img_id .'"></a>
                                                        <div class="custom-image-button-text">'. $button_text .'</div>
                                                    </div>';
                                    } else {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">
                                                        <img style="aspect-ratio:'. $sticky_buttons_aspect_ratio_full_size .';" class="custom-image-button custom-button-full-size" src="' . esc_url($image_full_size_url) . '" alt="sticky-button-'. $attachment_full_size_img_id .'">
                                                        <div class="custom-image-button-text">'. $button_text .'</div>
                                                    </div>';
                                    }
                                } else {
                                    if (!empty($link)) {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">
                                                        <a href="'. $link .'" '. $target_blank .'><div style="background-color:'. $button_color .'; aspect-ratio:'. $sticky_buttons_aspect_ratio_full_size .';" class="custom-image-button custom-button-full-size"><span>'. $button_text .'</span></div></a>
                                                    </div>';
                                    } else {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">
                                                        <div style="background-color:'. $button_color .'; aspect-ratio:'. $sticky_buttons_aspect_ratio_full_size .';" class="custom-image-button custom-button-full-size"><span>'. $button_text .'</span></div>
                                                    </div>';
                                    }
                                }
                            }
                        } else {
                            $output .= 'Invalid JSON data.';
                        }

                    $output .= '
                    </div>';

                    if (!empty($sticky_buttons_breakpoints)) {

                        $sticky_buttons_breakpoints = json_decode(urldecode($sticky_buttons_breakpoints), true);

                        usort($sticky_buttons_breakpoints, function($a, $b) {
                            return (int)$a['sticky_buttons_breakpoint_width'] - (int)$b['sticky_buttons_breakpoint_width'];
                        });

                        $sticky_buttons_breakpoints_return = "return ";
                        foreach ($sticky_buttons_breakpoints as $bp) {
                            $width = (int)$bp['sticky_buttons_breakpoint_width'];
                            $slides = $bp['sticky_buttons_breakpoint_slides'];
                            $sticky_buttons_breakpoints_return .= "elementWidth < $width ? $slides :\n                        ";
                        }
                        $sticky_buttons_breakpoints_return .= 'slidesToShowSetting;';

                    }

                    if ($sticky_buttons_auto_slider == true) {
                        $sticky_buttons_slides_to_show = !empty($sticky_buttons_slides_to_show) ? $sticky_buttons_slides_to_show : 4;

                        include_once plugin_dir_path(__FILE__) . '/../scripts/slider.php';
                        $output .= PWESliderScripts::sliderScripts('sticky-buttons', '.pwelement_'. self::$rnd_id, $sticky_buttons_dots_display = 'true', $sticky_buttons_arrows_display = false, $sticky_buttons_slides_to_show, $options = null, $slides_to_show_1 = null, $slides_to_show_2 = null, $slides_to_show_3 = null, $sticky_buttons_breakpoints_return);

                        if($sticky_buttons_show_strip){

                            $event_count = count($sticky_buttons_json[0]);

                            $slider_id = 'pwelement_' . self::$rnd_id . ' .pwe-slides';
                            $output .= '
                            <input type="range" id="test" class="slider-range_' . self::$rnd_id . '" min="0" step="1">
                            <script>
                            jQuery(document).ready(function ($) {
                                const $slider = $(".'.$slider_id.'");
                                const $range = $(".slider-range_' . self::$rnd_id . '");

                                $slider.on("init", function (event, slick) {
                                    $range.attr("max", slick.slideCount - 1);
                                    $range.val(slick.currentSlide);
                                });
                                function updateSliderBackground($el) {
                                    const val = ($el.val() - $el.attr("min")) / ($el.attr("max") - $el.attr("min"));
                                    const percent = val * 100;
                                    $el.css("background", `linear-gradient(to right, black 0%, black ${percent}%, #ccc ${percent}%, #ccc 100%)`);
                                }
                                $slider.on("init", function (event, slick) {
                                    $range.attr("max", slick.slideCount - 1);
                                    $range.val(slick.currentSlide);
                                    updateSliderBackground($range);
                                });

                                $range.on("input", function () {
                                    const slideIndex = parseInt($(this).val(), 10);
                                    $slider.slick("slickGoTo", slideIndex);
                                    updateSliderBackground($range);
                                });

                                $slider.on("afterChange", function (event, slick, currentSlide) {
                                    $range.val(currentSlide);
                                    updateSliderBackground($range);
                                });
                            });
                            </script>
                            <style>
                                .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . ' {
                                    width: 100%;
                                    margin-top: 16px;
                                    height: 10px;
                                    border-radius: 5px;
                                    background: black;
                                    appearance: none;
                                    -webkit-appearance: none;
                                    overflow: hidden;
                                    cursor: pointer;
                                    transition: background 0.3s ease;
                                    display: none;
                                }

                                /* Ukrywamy kropki slicka, jeśli są */
                                .pwelement_'. self::$rnd_id .' .slick-dots {
                                    visibility: hidden;
                                }

                                /* KWADRATOWY THUMB DLA WEBKIT (Chrome, Safari) */
                                .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-webkit-slider-thumb {
                                    appearance: none;
                                    -webkit-appearance: none;
                                    height: 16px;
                                    width: 16px;
                                    background: black;
                                    border: 2px solid black;
                                    border-radius: 0; /* ← dzięki temu jest kwadratowy */
                                    cursor: pointer;
                                    transition: background 0.3s ease;
                                }

                                /* Dla Firefox (Moz) */
                                .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-moz-range-thumb {
                                    height: 16px;
                                    width: 16px;
                                    background:  black;
                                    border: 2px solid black;
                                    border-radius: 0;
                                    cursor: pointer;
                                }

                                /* Dla IE/Edge */
                                .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-ms-thumb {
                                    height: 16px;
                                    width: 16px;
                                    background: white;
                                    border: 2px solid black;
                                    border-radius: 0;
                                    cursor: pointer;
                                }

                                /* Efekt przy najechaniu */
                                .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-webkit-slider-thumb:hover {
                                    background: black !important;
                                    border-color: white;
                                }
                            </style>';
                            if ($event_count === 1 || $event_count === 2) {
                                $output .='
                                    <style>
                                         @media(max-width:470px){
                                            .pwelement_'. self::$rnd_id .'  .pwe-other-events__items {
                                                margin-bottom: 0 !important;
                                            }
                                            .pwelement_'. self::$rnd_id .'  .slick-dots {
                                                display:none !important;
                                            }
                                            .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . ' {
                                                display:block !important;
                                            }
                                        }
                                    </style>
                                ';
                            } else if($event_count>4){
                                $output .='
                                <style>
                                     @media(max-width:960px){
                                        .pwelement_'. self::$rnd_id .'  .pwe-other-events__items {
                                            margin-bottom: 0 !important;
                                        }
                                        .pwelement_'. self::$rnd_id .'  .slick-dots {
                                            display:none !important;
                                        }
                                        .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . ' {
                                            display:block !important;
                                        }
                                    }
                                </style>
                                ';
                            }
                        }
                    }
                }

            $output .= '
                <div class="sticky custom-sticky-buttons-cropped-container">
                    <div class="custom-sticky-head-container style-accent-bg" style="background-color:'. $sticky_buttons_cropped_background .'!important;">
                        <h4 class="custom-sticky-head-text" style="color: white;">Wybierz kongres
                            <i class="fa fa-chevron-down fa-1x fa-fw"></i>
                        </h4>
                    </div>
                    <div class="custom-sticky-buttons-cropped style-accent-bg" style="background-color:'. $sticky_buttons_cropped_background .'!important;">';

                        if (is_array($sticky_buttons_json)) {
                            foreach ($sticky_buttons_json as $sticky_button) {

                                $attachment_img_id = $sticky_button["sticky_buttons_images"];
                                $link = $sticky_button["sticky_buttons_link"];
                                $button_id = $sticky_button["sticky_buttons_id"];
                                $button_color = $sticky_button["sticky_buttons_color_bg"];
                                $button_text = $sticky_button["sticky_buttons_color_text"];
                                $image_url = wp_get_attachment_url($attachment_img_id);
                                $buttons_urls[] = $image_url;
                                $buttons_colors[] = $button_color;
                                $buttons_id[] = $button_id;
                                $buttons_links[] = $link;

                                $target_blank = (strpos($link, 'http') !== false) ? 'target="blank"' : '';

                                $section_id = str_replace("-btn", "", $button_id);

                                $output .= '<style>
                                    #'. $section_id .' {
                                        opacity: 0;
                                    }
                                </style>';

                                if ($sticky_buttons_mini_hide == true) {
                                    $output .= '<style>
                                        #'. $element_unique_id .' .custom-sticky-buttons-cropped-container {
                                            display: none !important;
                                        }
                                    </style>';
                                }

                                if (!empty($image_url)) {
                                    if (!empty($link)) {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">
                                                        <a href="'. $link .'" '. $target_blank .'><img style="aspect-ratio:'. $sticky_buttons_aspect_ratio .';" class="custom-image-button custom-button-cropped" src="' . esc_url($image_url) . '" alt="sticky-button-'. $attachment_img_id .'"></a>
                                                        <div class="custom-image-button-text">'. $button_text .'</div>
                                                    </div>';
                                    } else {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">
                                                        <img style="aspect-ratio:'. $sticky_buttons_aspect_ratio .';" class="custom-image-button custom-button-cropped" src="' . esc_url($image_url) . '" alt="sticky-button-'. $attachment_img_id .'">
                                                        <div class="custom-image-button-text">'. $button_text .'</div>
                                                    </div>';
                                    }
                                } else {
                                    if (!empty($link)) {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">';
                                            $output .= '<a href="'. $link .'"'. $target_blank .'><div style="background-color:'. $button_color .'; aspect-ratio:'. $sticky_buttons_aspect_ratio .';" class="custom-image-button custom-button-cropped"><span>'. $button_text .'</span></div></a>';
                                        $output .= '</div>';
                                    } else {
                                        $output .= '<div '. (!empty($button_id) ? 'id="' . $button_id . '-btn"' : '') .' class="custom-sticky-button-item">';
                                            $output .= '<div style="background-color:'. $button_color .'; aspect-ratio:'. $sticky_buttons_aspect_ratio .';" class="custom-image-button custom-button-cropped"><span>'. $button_text .'</span></div>';
                                        $output .= '</div>';
                                    }
                                }

                            }
                        } else {
                            $output .= 'Invalid JSON data.';
                        }

                        $output .= '</div>
                    </div>';
                    if ($sticky_buttons_info_text_show == true && count($sticky_buttons_json) < 4) {
                        $output .= '<p class="sticky-buttons-info-bottom">'. $sticky_buttons_info_text_bottom .'</p>';
                    }
                $output .= '
                </div>';

            $buttons_cropped_image = json_encode($buttons_urls);
            $buttons_cropped_color = json_encode($buttons_colors);

            $buttons_id_json = json_encode($buttons_id);

            $output .= '
            <script>

                document.addEventListener("DOMContentLoaded", () => {
                    const pweElement = document.querySelector(".pwelement_'.self::$rnd_id.'");
                    const stickyScroll = "'. $sticky_buttons_scroll .'";
                    const stickyMiniHide = "'. $sticky_buttons_mini_hide .'";
                    const stickyMiniUrlsImg = "'. $image_url.'";
                    const btnsId = ' . json_encode($buttons_id_json) . ';
                    const stickyButtonsDropdown = ' . json_encode($sticky_buttons_dropdown) . ';
                    const stickyButtonsFullSize = ' . json_encode($sticky_buttons_full_size) . ';
                    const tilesCroppedContainer = pweElement.querySelector(".custom-sticky-buttons-cropped-container");
                    const tilesCropped = pweElement.querySelector(".custom-sticky-buttons-cropped");
                    const tilesFullSize = pweElement.querySelector(".custom-sticky-buttons-full-size");
                    const stickyHeadContainer = pweElement.querySelector(".custom-sticky-head-container");
                    const uncodeMasthead = document.querySelector("#masthead .menu-container");
                    const customMasthead = document.querySelector("#pweMenu");
                    const containerMasthead = uncodeMasthead ? uncodeMasthead : customMasthead;
                    const pweMenu = document.querySelector("#pweMenu");
                    const containerPageHeader = document.querySelector("#page-header");
                    const containerCustomHeader = document.querySelector("#pweHeader");
                    const adminBar = document.querySelector("#wpadminbar");
                    const desktop = ' . json_encode($mobile === 0) . ';
                    const mobile = ' . json_encode($mobile === 1) . ';
                    const autoSlider = "' . $sticky_buttons_auto_slider . '";

                    pweElement.style.opacity = 1;

                    const hideElement = (element) => {
                        element.style.display = "none";
                    };
                    let displayValue = (autoSlider === true) ? "block" : "flex";
                    const showElement = (element, displayValue) => {
                        element.style.display = displayValue;
                    };
                    const setElementPosition = (element, position) => {
                        element.style.position = position;
                    };

                    const buttonsCroppedImage = ' . $buttons_cropped_image . ';
                    const buttonsCroppedColor = ' . $buttons_cropped_color . ';
                    const combinedArray = buttonsCroppedImage.concat(buttonsCroppedColor);
                    if (combinedArray.every(value => value === false || value === "")) {
                        hideElement(tilesCroppedContainer);
                    }

                    if (stickyButtonsDropdown !== "true") {
                        hideElement(stickyHeadContainer);
                        if (stickyButtonsFullSize === "true") { // dropdown on full size on
                            setElementPosition(tilesCroppedContainer, "absolute");
                            showElement(tilesCropped);
                            showElement(tilesFullSize);
                        } else { // dropdown on full size off
                            showElement(tilesCropped);
                        }
                    } else if (stickyButtonsDropdown === "true") {
                        showElement(stickyHeadContainer);
                        if (stickyButtonsFullSize === "true") { // dropdown off full size on
                            setElementPosition(tilesCroppedContainer, "absolute");
                            showElement(stickyHeadContainer);
                            hideElement(tilesCropped);
                            showElement(tilesFullSize);
                        } else { // dropdown off full size off
                            showElement(tilesCroppedContainer);

                        }
                    }

                    const stickyElement = pweElement.querySelector(".sticky");
                    const stickyClass = "sticky-pin";
                    let stickyPos;
                    let stickyHeight;

                    // Create a negative margin to prevent content "jumps":
                    var jumpPreventDiv = document.createElement("div");
                    jumpPreventDiv.className = "jumps-prevent";
                    stickyElement.parentNode.insertBefore(jumpPreventDiv, stickyElement.nextSibling);

                    if (containerMasthead && desktop) {
                        stickyPos = stickyElement.getBoundingClientRect().top + window.scrollY - containerMasthead.offsetHeight;
                    } else if (pweMenu && (desktop || mobile)) {
                        stickyPos = stickyElement.getBoundingClientRect().top + window.scrollY - pweMenu.offsetHeight;
                    } else {
                        stickyPos = stickyElement.getBoundingClientRect().top + window.scrollY;
                    }
                    function jumpsPrevent() {
                        stickyHeight = stickyElement.offsetHeight;
                        stickyElement.style.marginBottom = "-" + stickyHeight + "px";
                        stickyElement.nextElementSibling.style.paddingTop = stickyHeight + "px";
                    }
                    if (!tilesFullSize) {
                        jumpsPrevent(); // Run

                        // Function trigger:
                        window.addEventListener("resize", function () {
                            jumpsPrevent();
                        });
                    }

                    // Sticker function:
                    function stickerFn() {
                        const masthead = document.querySelector("#masthead");
                        const isStuckMasthead = masthead ? masthead.classList.contains("is_stuck") : false;
                        const stickyElementFixed = pweElement.querySelector(".sticky-pin");
                        const winTop = window.scrollY;
                        // Check element position:
                        if (winTop >= stickyPos) {
                            stickyElement.classList.add(stickyClass);
                            if (stickyElement) {
                                if ((containerMasthead || pweMenu) && adminBar && desktop) {
                                    if (containerMasthead) {
                                        stickyElement.style.top = containerMasthead.offsetHeight + adminBar.offsetHeight + "px";
                                    } else {
                                        stickyElement.style.top = pweMenu.offsetHeight + adminBar.offsetHeight + "px";
                                    }
                                } else if ((containerMasthead || pweMenu) && !adminBar && desktop) {
                                    if (containerMasthead) {
                                        stickyElement.style.top = containerMasthead.offsetHeight + "px";
                                    } else {
                                        stickyElement.style.top = pweMenu.offsetHeight + "px";
                                    }
                                } else if ((isStuckMasthead || pweMenu) && mobile) {
                                    if (isStuckMasthead) {
                                        stickyElement.style.top = containerMasthead.offsetHeight + "px";
                                    } else {
                                        stickyElement.style.top = pweMenu.offsetHeight + "px";
                                    }
                                } else {
                                    stickyElement.style.top = "0px";
                                }
                            }
                        } else {
                            stickyElement.classList.remove(stickyClass);
                            if (tilesFullSize) {
                                stickyElement.style.top = "0px";
                            }
                        }
                    }

                    stickerFn(); // Run

                    // Function trigger:
                    window.addEventListener("scroll", function () {
                        stickerFn();
                    });

                    if (btnsId && typeof btnsId === "string") {
                        try {
                            const btnsIdArray = JSON.parse(btnsId);
                            if (Array.isArray(btnsIdArray)) {
                                btnsIdArray.forEach(function(btnId) {
                                    const trimmedBtnId = btnId.trim();
                                    const vcRow = document.getElementById(trimmedBtnId);
                                    if (vcRow) {
                                        vcRow.classList.add("sticky-section-'. $unique_id .'");
                                        vcRow.classList.add("hide-section-'. $unique_id .'");
                                    }
                                });
                            } else {
                                console.error("Nie udało się przekształcić btnsId w tablicę.");
                            }
                        } catch (error) {
                            console.error("Błąd podczas parsowania JSON w btnsId:", error);
                        }
                    }

                    if (btnsId !== "") {
                        pweElement.querySelectorAll(".custom-sticky-button-item").forEach(function(button, index) {

                            button.style.transition = ".3s ease";

                            var hideSections = document.querySelectorAll(".page-wrapper .row-container.hide-section-'. $unique_id .'");

                            // Hide all sections except the first one
                            if ("' . $sticky_hide_sections . '" === "true") {
                                for (var i = 1; i < hideSections.length; i++) {
                                    hideSections[i].style.display = "none";
                                }
                                if (index === 0 && button) {
                                    if (hideSections.length > 0) {
                                        hideSections[0].style.display = "block";
                                    }
                                    button.style.transform = "scale(1.1)";
                                }
                            } else {
                                for (var i = 0; i < hideSections.length; i++) {
                                    hideSections[i].style.display = "none";
                                }
                            }

                            button.addEventListener("click", function() {
                                var targetId = button.id.replace("-btn", "");

                                let customScrollTop;
                                if (containerPageHeader) {
                                    customScrollTop = containerPageHeader.offsetHeight;
                                } else if (containerCustomHeader) {
                                    customScrollTop = containerCustomHeader.offsetHeight;
                                } else {
                                    customScrollTop = containerMasthead.offsetHeight;
                                }
                                if (document.querySelectorAll("header.menu-transparent").length > 0 && desktop) {
                                    customScrollTop -= containerMasthead.offsetHeight;
                                }
                                customScrollTop += "px";

                                // Hide all elements of .row-container
                                hideSections.forEach(function(section) {
                                    section.style.display = "none";
                                });

                                // Wyświetlamy elementy
                                var targetElement = document.getElementById(targetId);
                                if (targetElement) {
                                    targetElement.style.display = "block";

                                    // Scroll to the desired section
                                    if (stickyScroll !== "true") {
                                        // if (stickyButtonsFullSize == "true" && (stickyMiniUrlsImg == "" || (stickyMiniUrlsImg != "" && stickyMiniHide == "true"))) {

                                        targetElement.style.scrollMarginTop = containerMasthead.offsetHeight + "px";
                                            targetElement.scrollIntoView({ behavior: "smooth" });
                                        // } else {
                                        //     pweElement.querySelectorAll(".custom-sticky-button-item").forEach(function(button) {
                                        //         const scrollTopValue = parseInt(customScrollTop);
                                        //         button.addEventListener("click", function() {
                                        //             window.scrollTo({ top: scrollTopValue, behavior: "smooth" });
                                        //         });
                                        //     });
                                        // }
                                    }
                                }

                                if (button) {
                                    button.style.transform = "scale(1.1)";
                                }

                                pweElement.querySelectorAll(".custom-sticky-button-item").forEach(function(otherButton) {
                                    if (otherButton !== button) {
                                        otherButton.style.transform = "scale(1)";
                                    }
                                });
                            });

                        });
                    }

                    if (stickyButtonsDropdown === "true") {
                        var congressMenuSlide = pweElement.querySelector(".custom-sticky-buttons-cropped-container");

                        // Funkcja sprawdzająca kliknięcie poza menu
                        document.addEventListener("click", function (event) {
                            if (!event.target.closest(".custom-sticky-buttons-cropped-container")) {
                                // Jeśli menu jest otwarte, zamknij je
                                var menus = pweElement.querySelectorAll(".custom-sticky-buttons-cropped");
                                menus.forEach(function(menu) {
                                    menu.classList.remove("open");
                                    menu.style.maxHeight = "0";
                                    menu.style.padding = "0";
                                });
                                var icons = pweElement.querySelectorAll(".custom-sticky-head-container i");
                                icons.forEach(function(icon) {
                                    icon.classList.remove("fa-chevron-up");
                                    icon.classList.add("fa-chevron-down");
                                });
                            }
                        });

                        // Kliknięcie na .custom-sticky-head-container
                        var stickyHeadContainers = congressMenuSlide.querySelectorAll(".custom-sticky-head-container");
                        stickyHeadContainers.forEach(function(container) {
                            container.addEventListener("click", function () {
                                var menu = container.closest(".custom-sticky-buttons-cropped-container").querySelector(".custom-sticky-buttons-cropped");
                                if (menu.classList.contains("open")) {
                                    // Jeśli menu jest widoczne, zamknij je
                                    menu.classList.remove("open");
                                    menu.style.maxHeight = "0";
                                    menu.style.padding = "0";
                                    container.querySelector("i").classList.remove("fa-chevron-up");
                                    container.querySelector("i").classList.add("fa-chevron-down");
                                } else {
                                    // Jeśli menu nie jest widoczne, otwórz je
                                    menu.classList.add("open");
                                    menu.style.maxHeight = "500px";
                                    menu.style.padding = "28px 18px";
                                    container.querySelector("i").classList.remove("fa-chevron-down");
                                    container.querySelector("i").classList.add("fa-chevron-up");
                                }
                            });
                        });

                        // Obsługa zmiany rozmiaru okna
                        window.addEventListener("resize", function () {
                            if (window.innerWidth >= 1300) {
                                stickyHeadContainers.forEach(function(container) {
                                    container.removeEventListener("mouseenter", handleMouseEnter);
                                    container.addEventListener("mouseenter", handleMouseEnter);
                                });
                            } else {
                                stickyHeadContainers.forEach(function(container) {
                                    container.removeEventListener("mouseenter", handleMouseEnter);
                                });
                            }
                        });

                        // Funkcja obsługująca hover
                        function handleMouseEnter(event) {
                            var menu = event.target.closest(".custom-sticky-buttons-cropped-container").querySelector(".custom-sticky-buttons-cropped");
                            if (!menu.classList.contains("open")) {
                                menu.classList.add("open");
                                menu.style.maxHeight = "500px";
                                menu.style.padding = "28px 18px";
                                event.target.querySelector("i").classList.remove("fa-chevron-down");
                                event.target.querySelector("i").classList.add("fa-chevron-up");
                            }
                        }

                        // Obsługa przewijania strony
                        window.addEventListener("scroll", function () {
                            var menus = pweElement.querySelectorAll(".custom-sticky-buttons-cropped");
                            menus.forEach(function(menu) {
                                menu.classList.remove("open");
                                menu.style.maxHeight = "0";
                                menu.style.padding = "0";
                            });
                            var icons = pweElement.querySelectorAll(".custom-sticky-head-container i");
                            icons.forEach(function(icon) {
                                icon.classList.remove("fa-chevron-up");
                                icon.classList.add("fa-chevron-down");
                            });
                        });


                    } else {
                        pweElement.querySelector(".custom-sticky-head-container").style.display = "none";

                    }

                    const stickySections = document.querySelectorAll(".sticky-section-'. $unique_id .'");
                    stickySections.forEach(function (section) {
                        section.style.opacity = 1;
                    })

                });

                // Parameter for anchor
                function handleQueryParam() {
                    setTimeout(() => {
                        // Get the parameter from the current URL
                        const urlParams = new URLSearchParams(window.location.search);
                        const conferenceParam = urlParams.get("'. $sticky_buttons_parameter .'");

                        // Check if parameter exists
                        if (conferenceParam) {
                            // Show elements class with the appropriate id, hide the rest
                            const allElements = document.querySelectorAll(".'. $sticky_buttons_parameter .'");
                            const containerMasthead = document.querySelector("#masthead .menu-container");
                            allElements.forEach(function (element) {
                                if (element.id === conferenceParam) {
                                    element.style.display = "block";
                                    element.classList.remove("hide-section-'. $unique_id .'");
                                    setTimeout(() => {
                                        element.style.opacity = 1;
                                    }, 100);
                                    if ("'. $sticky_buttons_scroll .'" !== "true") {
                                        // Scroll to the element with id from the anchor
                                        element.style.scrollMarginTop = containerMasthead.offsetHeight + "px";
                                        element.scrollIntoView({ behavior: "smooth" });
                                    }
                                } else {
                                    element.style.display = "none";
                                }
                            });

                            // Add a .active class to the element with anchor id + -btn
                            var activeBtn = document.getElementById(conferenceParam + "-btn");
                            if (activeBtn) {
                                activeBtn.classList.add("active");
                            }
                        }
                    }, 500);
                }

                // Call the handler function when the page is loaded
                document.addEventListener("DOMContentLoaded", handleQueryParam);
                // Listen for changes to the conference parameter in the URL
                window.addEventListener("popstate", handleQueryParam);

            </script>';

        return $output;

    }
}