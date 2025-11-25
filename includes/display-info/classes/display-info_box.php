<?php 

/**
 * Class PWEDisplayInfoBox
 * Extends PWEDisplayInfo class and defines a custom Visual Composer element.
 */
class PWEDisplayInfoBox extends PWEDisplayInfo {

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
                'type' => 'checkbox',
                'group' => 'main',
                'heading' => __('Simple mode', 'pwe_display_info'),
                'param_name' => 'info_box_simple_mode',
                'description' => __('Display simple mode.', 'pwe_display_info'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'main',
                'heading' => __('Accordion preset', 'pwe_display_info'),
                'param_name' => 'info_box_accordion_preset',
                'description' => __('Turn on accordion preset. Does not work with simple mode setting!', 'pwe_display_info'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'main',
                'heading' => __('Title', 'pwe_display_info'),
                'param_name' => 'info_box_event_title',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'main',
                'heading' => __('Event time', 'pwe_display_info'),
                'param_name' => 'info_box_event_time',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textarea_html',
                'group' => 'main',
                'heading' => __('Description', 'pwe_display_info'),
                'param_name' => 'content',
                'description' => __('Put event description.', 'pwe_display_info'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'main',
                'heading' => __('Show more text', 'pwe_display_info'),
                'param_name' => 'info_box_show_more',
                'description' => __('Hidden text', 'pwe_display_info'),
                'param_holder_class' => 'backend-textarea-raw-html',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'heading' => __('Speakers', 'pwe_display_info'),
                'group' => 'main',
                'type' => 'param_group',
                'param_name' => 'info_box_speakers',
                'save_always' => true,
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Select Speaker Image', 'pwe_display_info'),
                        'param_name' => 'speaker_image',
                        'description' => __('Choose speaker image from the media library.', 'pwe_display_info'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Image src (doc/...)', 'pwe_display_info'),
                        'param_name' => 'speaker_image_doc',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Speaker Name', 'pwe_display_info'),
                        'param_name' => 'speaker_name',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Bio', 'pwe_display_info'),
                        'param_name' => 'speaker_bio',
                    ),
                ),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Info Box Style', 'pwe_display_info'),
                'param_name' => 'info_box_styles',
                'description' => __('Example: "border: 1px solid red; border-radius: 10px; padding: 18px; box-shadow: 4px 4px 7px 2px; ..."', 'pwe_display_info'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Title size', 'pwe_display_info'),
                'param_name' => 'info_box_title_size',
                'description' => __('Title font size.', 'pwe_display_info'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Tittle on top', 'pwe_display_info'),
                'param_name' => 'info_box_title_top',
                'description' => __('Check to move Title to top of Lecturers.', 'pwe_display_info'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'options',
                'heading' => __('Lecturers color', 'pwe_display_info'),
                'param_name' => 'info_box_lect_color',
                'description' => __('Color for lecturers names.', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'options',
                'heading' => __('Title Color', 'pwe_display_info'),
                'param_name' => 'info_box_title_color',
                'description' => __('Color for buton lecture Title.', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Bio container width (desktop)', 'pwe_display_info'),
                'param_name' => 'info_box_bio_container_width_desktop',
                'description' => __('Default 20%;', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Image modal width (desktop)', 'pwe_display_info'),
                'param_name' => 'info_box_modal_img_width_desktop',
                'description' => __('Default 150px;', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Image modal width (mobile)', 'pwe_display_info'),
                'param_name' => 'info_box_modal_img_width_mobile',
                'description' => __('Default 120px;', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Hide Photo/BIO block ', 'pwe_display_info'),
                'param_name' => 'info_box_hide_photo',
                'description' => __('Check to hide photo.', 'pwe_display_info'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Photo as square', 'pwe_display_info'),
                'param_name' => 'info_box_photo_square',
                'description' => __('Check to show photos as square.', 'pwe_display_info'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoBox',
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
    private static function speakerImageMini($speaker_images) { 
        $base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $base_url .= "://".$_SERVER['HTTP_HOST'];
      



        // Filtering out empty values
        $head_images = array_filter($speaker_images);
        // Reindex the array
        $head_images = array_values($head_images); 
       
        // Check if the merged image array is empty
        if (empty($head_images)) {
            return ''; // Return an empty string if there are no images
        }
    
        $speaker_html = '<div class="pwe-box-speakers-img">';
        
    
        // Loop through images
        for ($i = 0; $i < count($head_images); $i++) {    
            if (isset($head_images[$i]) && !empty($head_images[$i])) {
                // Distinguish between ID and path
                if (is_numeric($head_images[$i])) {
                    // If the value is an ID from WordPress Media Library
                    $image_src = wp_get_attachment_image_src($head_images[$i], 'full')[0]; // Get the full URL of the image
                } else {
                    // If the value is a direct path to the file
                    $image_src = $base_url . '/doc/' . $head_images[$i]; // Build the path based on the value from `$speaker_images_doc`
                }
    
                if ($image_src) {
                    $z_index = (1 + $i);
                    $margin_top_index = '';
                    $max_width_index = "50%";
    
                    // Set styles based on the number of images
                    switch (count($head_images)) {
                        case 1:
                            $top_index = "unset";
                            $left_index = "unset";
                            $max_width_index = "80%";
                            break;
    
                        case 2:
                            switch ($i) {
                                case 0:
                                    $margin_top_index = "margin-top : 20px";
                                    $max_width_index = "50%";
                                    $top_index = "-20px";
                                    $left_index = "10px";
                                    break;
    
                                case 1:
                                    $max_width_index = "50%";
                                    $top_index = "0";
                                    $left_index = "-10px";
                                    break;
                            }
                            break;
    
                        case 3:
                            switch ($i) {
                                case 0:
                                    $top_index = "0";
                                    $left_index = "15px";
                                    break;
    
                                case 1:
                                    $top_index = "40px";
                                    $left_index = "-15px";
                                    break;
    
                                case 2:
                                    $top_index = "-15px";
                                    $left_index = "-30px";
                                    break;
                            }
                            break;
    
                        default:
                            switch ($i) {
                                case 0:
                                    $margin_top_index = 'margin-top: 5px !important;';
                                    break;
    
                                case 1:
                                    $left_index = "-10px";
                                    break;
    
                                default:
                                    $reszta = $i % 2;
                                    if ($reszta == 0) {
                                        $top_index = $i / 2 * -15 . "px";
                                        $left_index = "0";
                                    } else {
                                        $top_index = floor($i / 2) * -15 . "px";
                                        $left_index = "-10px";
                                    }
                                    break;
                            }
                    }
    
                    // Add image to HTML
                    $speaker_html .= '<img class="pwe-box-speaker" src="'. esc_url($image_src) .'" alt="speaker portrait" style="position:relative; z-index:'.$z_index.'; top:'.$top_index.'; left:'.$left_index.'; max-width: '.$max_width_index.';'.$margin_top_index.';" />';
                }
            }
        }
        $speaker_html .= '</div>';
    
        return $speaker_html;
    }
    
    
    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     */
    public static function output($atts, $content = null) {
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']);
        $btn_border = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']);

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $rnd = rand(10000, 99999);

        extract( shortcode_atts( array(
            'info_box_simple_mode' => '',
            'info_box_accordion_preset' => '',
            'info_box_event_time' => '',
            'info_box_event_title' => '',
            'info_box_speakers' => '',
            'info_box_show_more' => '',
            'info_box_styles' => '',
            'info_box_lect_color' => '',
            'info_box_title_color' => '',
            'info_box_title_size' => '',
            'info_box_bio_container_width_desktop' => '',
            'info_box_modal_img_width_desktop' => '',
            'info_box_modal_img_width_mobile' => '',
            'info_box_hide_photo' => '',
            'info_box_title_top' => '', 
            'info_box_photo_square' => '',
        ), $atts ));

        if ($info_box_simple_mode != true) {
            $info_box_lect_color = empty($info_box_lect_color) ? 'black' : $info_box_lect_color;
        } else $info_box_lect_color = empty($info_box_lect_color) ? self::$accent_color : $info_box_lect_color;

        $info_box_bio_btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'black') .'!important';
        $info_box_bio_btn_text = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') .' !important;';

        $info_box_bio_container_width_desktop = empty($info_box_bio_container_width_desktop) ? '200px' : $info_box_bio_container_width_desktop;

        $info_box_modal_img_width_desktop = empty($info_box_modal_img_width_desktop) ? '150px' : $info_box_modal_img_width_desktop;
        $info_box_modal_img_width_mobile = empty($info_box_modal_img_width_mobile) ? '120px' : $info_box_modal_img_width_mobile;
        $info_box_title_size = empty($info_box_title_size) ? '18px' : $info_box_title_size;
        $info_box_photo_square = $info_box_photo_square != true ? '50%' : '0';

        $info_box_event_title = str_replace('``','"', $info_box_event_title);

        $output = '
        <style>
            #info-box-'. self::$rnd_id .' {
                display: flex;
                text-align: left;
                gap: 18px;
                '. $info_box_styles .'
            }
            #info-box-'. self::$rnd_id .' .pwe-box-speakers {
                width: '. $info_box_bio_container_width_desktop .';
                min-width: '. $info_box_bio_container_width_desktop .';
                display: flex;
                flex-direction: column;
                text-align: center;
            }
            #info-box-'. self::$rnd_id .' .pwe-box-speaker {
                border: 2px solid gray;
                aspect-ratio: 1/1;
                object-fit: cover;
            }
            #info-box-'. self::$rnd_id .' .pwe-box-info {
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 18px;
            }
            #info-box-'. self::$rnd_id .' .pwe-box-speaker {
                border-radius: '. $info_box_photo_square .';
                background: white;
            }
            #info-box-'. self::$rnd_id .' .pwe-box-speaker-btn {
                margin: 10px auto !important;
                color: '. $btn_text_color .';
                background-color: '. $btn_color .';
                border: 1px solid '. $btn_border .';
                padding: 6px 16px;
                font-weight: 600;
                width: 80px;
                border-radius: 10px;
                transition: .3s ease;
            }
            #info-box-'. self::$rnd_id .' .pwe-box-speaker-btn:hover {
                color: '. $btn_text_color .';
                background-color: '. $darker_btn_color .'!important;
                border: 1px solid '. $darker_btn_color .'!important;
            }
            #info-box-'. self::$rnd_id .' .pwe-box-lecture-time,
            #info-box-'. self::$rnd_id .' .pwe-box-lecture-title,
            #info-box-'. self::$rnd_id .' .pwe-box-lecturer-name {
                margin: 0;
            }
            #info-box-'. self::$rnd_id .' .pwe-box-lecture-desc p {
                font-size: 15px;
                margin: 8px 0 0;
            }

            #pweBoxModal-'. $rnd .' {
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                background-color: rgba(0, 0, 0, 0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                visibility: hidden;
                transition: opacity 0.3s, visibility 0.3s;
            }
            #pweBoxModal-'. $rnd .' .pwe-box-modal-content {
                position: relative;
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                border-radius: 20px;
                overflow-y: auto;
                width: 90%;
                max-width: 800px;
                max-height: 90%;
                transition: transform 0.3s;
                transform: scale(0);
            }
            #pweBoxModal-'. $rnd .' .pwe-box-modal-close {
                position: absolute;
                right: 18px;
                top: -6px;
                color: #000;
                float: right;
                font-size: 50px;
                font-weight: bold;
                transition: transform 0.3s;
                font-family: monospace;
            }
            #pweBoxModal-'. $rnd .' .pwe-box-modal-close:hover,
            #pweBoxModal-'. $rnd .' .pwe-box-modal-close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
                transform: scale(1.2);
            }
            #pweBoxModal-'. $rnd .' .pwe-box-modal-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 20px;
                gap: 18px;
            }
            #pweBoxModal-'. $rnd .' .pwe-box-modal-speaker {
                width: 100%;
                display: flex;
                gap: 18px;
            }
            #pweBoxModal-'. $rnd .' .pwe-box-modal-image {
                min-width: '. $info_box_modal_img_width_desktop .';
                max-width: '. $info_box_modal_img_width_desktop .';
            }
            #pweBoxModal-'. $rnd .' .pwe-box-modal-name {
                margin: 0;
            }
            #pweBoxModal-'. $rnd .' .pwe-modal-hr-container {
                width: 100%;
            }
            #pweBoxModal-'. $rnd .' .pwe-modal-hr {
                margin: 6px 0;
                border: 0;
                width: 100%;
                height: 1px;
                background-image: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, 0)), color-stop(rgba(0, 0, 0, .75)), to(rgba(0, 0, 0, 0)));
                background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, .75), rgba(0, 0, 0, 0));
            }
            #pweBoxModal-'. $rnd .'.is-visible {
                opacity: 1;
                visibility: visible;
            }
            #pweBoxModal-'. $rnd .'.is-visible .pwe-box-modal-content {
                transform: scale(1);
            }
            #pweBoxModal-'. $rnd .'.is-visible .pwe-box-modal-image {
                border-radius: 10px;
            }
            @media (max-width:650px) {
                #info-box-'. self::$rnd_id .' {
                    flex-direction: column;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-speakers {
                    width: 100% !important;
                    max-width: 300px;
                    margin: 0 auto;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-speaker {
                    width: 150px;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-info {
                    width: 100% !important;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-lecture-time,
                #info-box-'. self::$rnd_id .' .pwe-box-lecture-title,
                #info-box-'. self::$rnd_id .' .pwe-box-lecturer-name {
                    text-align: center;
                }

                #pweBoxModal-'. $rnd .' .pwe-box-modal-speaker {
                    flex-direction: column;
                }
                #pweBoxModal-'. $rnd .' .pwe-box-modal-speaker-img {
                    text-align: center;
                }
                #pweBoxModal-'. $rnd .' .pwe-box-modal-image {
                    min-width: '. $info_box_modal_img_width_mobile .';
                    max-width: '. $info_box_modal_img_width_mobile .';
                }
                #pweBoxModal-'. $rnd .' .pwe-box-modal-bio {
                    font-size: 14px;
                }
                #pweBoxModal-'. $rnd .' .pwe-box-modal-name {
                    text-align: center;
                }
            }
        </style>';

        if ($info_box_accordion_preset == true && $info_box_simple_mode != true) {
            $output .= '
            <style>
                #info-box-'. self::$rnd_id .' .pwe-box-info {
                    width: 100%;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-lecturer-name,
                #info-box-'. self::$rnd_id .' .pwe-box-speakers {
                    display: none;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-lecture-time,
                #info-box-'. self::$rnd_id .' .pwe-box-lecture-title {
                    text-align: start;
                }
                @media(min-width:960px){
                    #info-box-'. self::$rnd_id .' .pwe-box-lecture-time {
                        width: 15%;
                    }
                    #info-box-'. self::$rnd_id .' .pwe-box-lecture-title {
                        width: 84%;
                        font-size: 18px;
                    }
                    #info-box-'. self::$rnd_id .' .pwe-box-info {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: flex-end !important;
                        flex-direction: unset !important;
                        gap: 4px !important;
                    }
                    #info-box-'. self::$rnd_id .' .pwe-box-lecture-desc {
                        position: relative;
                        padding-bottom: 5px;
                        width: 84%;
                    }
                    #info-box-'. self::$rnd_id .' .pwe-see-more {
                        margin-right: 180px;
                        margin-top: -24px !important;
                    }
                    #info-box-'. self::$rnd_id .' .pwe-box-lecture-desc div, 
                    #info-box-'. self::$rnd_id .' .pwe-box-lecture-title {
                        padding-right: 62px; 
                    }
                }
                #info-box-'. self::$rnd_id .' .pwe-see-more {
                    text-align: right;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-lecture-desc::after {
                    content: "";
                    position: absolute;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    height: 1px;
                    background: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, 0)), color-stop(50%, rgba(0, 0, 0, .75)), to(rgba(0, 0, 0, 0)));
                    background: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, .75), rgba(0, 0, 0, 0));
                }

            </style>';
        }

        // Main content field
        $info_box_event_desc = wpb_js_remove_wpautop($content, true);

        // Show more content field
        $info_box_show_more_decoded = base64_decode($info_box_show_more);// Decoding Base64
        $info_box_show_more_decoded = urldecode($info_box_show_more_decoded); // Decoding URL
        $info_box_event_hidden_desc = wpb_js_remove_wpautop($info_box_show_more_decoded, true);// Remowe wpautop

        // Create arrays
        $speaker_images_doc = [];
        $speaker_images = [];
        $speaker_names = [];
        $speaker_bio = [];

        // Decoding and get speakers data
        if (isset($info_box_speakers)){
            $speakers_urldecode = urldecode($info_box_speakers);
            $speakers_json = json_decode($speakers_urldecode, true);    
            if ($speakers_json !== null && is_array($speakers_json)) {
                foreach ($speakers_json as $key => $speaker){
                    
                    if(isset($speaker["speaker_image_doc"]) && !empty($speaker["speaker_image_doc"])){
                        $speaker_images[] = $speaker["speaker_image_doc"];
                    } else if (isset($speaker["speaker_image"]) && !empty($speaker["speaker_image"])){
                        $speaker_images[] = $speaker["speaker_image"];
                    } 

                    if(isset($speaker["speaker_name"])){
                        $speaker_names[] = $speaker["speaker_name"];
                    }
                    if(isset($speaker["speaker_bio"])){
                        $speaker_bio[] = $speaker["speaker_bio"];
                    }
    
                    // Image src for modal
                    $speaker_images_src = wp_get_attachment_url($speaker["speaker_image"]); 
                    
                    $speakers_json[$key]['speaker_image'] = $speaker_images_src;
                    $speakers_json[$key]['speaker_image_doc'] = $speaker_images_doc;

                    if (isset($speaker["speaker_image_doc"]) && !empty($speaker["speaker_image_doc"])) {
                        $speaker_images_doc_url = '/doc/' . $speaker["speaker_image_doc"];
                        $speakers_json[$key]['speaker_image_doc_url'] = $speaker_images_doc_url;
                    } else {
                        $speaker_images_doc_url = '';
                    }
                    
                    if (isset($speaker["speaker_image"]) && !empty($speaker["speaker_image"])) {
                        $speaker_images_src = wp_get_attachment_url($speaker["speaker_image"]);
                        $speakers_json[$key]['speaker_image_url'] = $speaker_images_src;
                    } else {
                        $speaker_images_src = '';
                    }

                }
            }
        }
        
        if (empty($speaker_images[0]) && empty($speaker_images_doc[0])) {
            $output .= '
            <style>
                #info-box-'. self::$rnd_id .' .pwe-box-speakers {
                    justify-content: center;
                }
            </style>
            ';
        }

        // Output content
        if ($info_box_simple_mode != true) {
            if ($info_box_hide_photo != true) {
                $output .= '
                <div id="pweBoxSpeakers-'. $rnd .'" class="pwe-box-speakers">';
                    $output .= self::speakerImageMini($speaker_images);
                    if(!empty($speaker_bio[0])){
                        $output .='<button class="pwe-box-speaker-btn">BIO</button>';
                    }
                $output .= '
                </div>';
            }

            $output .= '
            <div id="pweBoxInfo-'. $rnd .'" class="pwe-box-info">';
                if(!empty($info_box_event_time)) {
                    $output .= '<h4 class="pwe-box-lecture-time">'. $info_box_event_time .'</h4>';
                }

                if($info_box_title_top) {
                    $output .= '<h4 class="pwe-box-lecture-title" style="font-size:'. $info_box_title_size .'; color:'. $info_box_title_color .';">'. $info_box_event_title .'</h4>';
                }

                if (!empty($speaker_names[0])) {
                    $output .= '<h5 class="pwe-box-lecturer-name" style="color:'. $info_box_lect_color .';">'. implode('<br>', $speaker_names) .'</h5>';
                }

                if(!$info_box_title_top) {
                    $output .= '<h4 class="pwe-box-lecture-title" style="font-size:'. $info_box_title_size .'; color:'. $info_box_title_color .';">'. $info_box_event_title .'</h4>';
                }

                $output .= '
                <div class="pwe-box-lecture-desc">';
                $output .= $info_box_event_desc;
                if (!empty($info_box_event_hidden_desc)) {
                    $showMore = get_locale() == "pl_PL" ? "więcej..." : "more...";
                    $output .= '
                            <div style="display: none;">'. $info_box_event_hidden_desc .'</div>
                            <p class="pwe-see-more" style="cursor: pointer;">'. $showMore .'</p>';
                }
                $output .= '
                </div>';

            $output .= '
            </div>';

            
            $speakers = json_encode($speakers_json);

            $output .= '
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const infoBox = document.querySelector("#info-box-'. self::$rnd_id .'");
                    const speakers = '. $speakers .';
                    const speakersBtn = infoBox.querySelector(".pwe-box-speaker-btn");

                    // Check if there is at least one speaker with name or bio
                    const shouldCreateModal = speakers.some(speaker => {
                        return (speaker.speaker_name && speaker.speaker_name.trim() !== "") && 
                            (speaker.speaker_bio && speaker.speaker_bio.trim() !== "");
                    });

                    // Create a new modal if the BIO button is clicked
                    if (speakersBtn && shouldCreateModal) {
                        speakersBtn.addEventListener("click", function() {
                            const modalDiv = document.createElement("div");
                            modalDiv.className = "pwe-box-modal";
                            modalDiv.id = "pweBoxModal-'. $rnd .'";
                            modalDiv.innerHTML = `
                                <div class="pwe-box-modal-content">
                                    <span class="pwe-box-modal-close">&times;</span>
                                </div>
                            `;
                            const modalContent = modalDiv.querySelector(".pwe-box-modal-content");

                            // Using innerHTML to add speakers
                            let speakersHTML = "";
                            speakers.forEach(speaker => {
                            
                                if (speaker.speaker_name != undefined && speaker.speaker_bio != undefined) {
                                    let speakerBlock = `<div class="pwe-box-modal-speaker">`;

                                    if (speaker.speaker_image_doc_url) {
                                        speakerBlock += `
                                            <div class="pwe-box-modal-speaker-img">
                                                <img src="${speaker.speaker_image_doc_url}" alt="Speaker Image" class="pwe-box-modal-image">
                                            </div>
                                        `;
                                    } else if (speaker.speaker_image_url) {
                                        speakerBlock += `
                                            <div class="pwe-box-modal-speaker-img">
                                                <img src="${speaker.speaker_image_url}" alt="Speaker Image" class="pwe-box-modal-image">
                                            </div>
                                        `;
                                    }

                                    // if (speaker.speaker_bio != undefined) {
                                        speakerBlock += `
                                            <div class="pwe-box-modal-speaker-text">
                                                <h5 class="pwe-box-modal-name">${speaker.speaker_name}</h5>
                                                <p class="pwe-box-modal-bio">${speaker.speaker_bio}</p>
                                            </div>
                                        </div>`;
                                    // }

                                    speakersHTML += speakerBlock;
                                } 
                            });

                            // Add a speaker info to the modal content container 
                            modalContent.innerHTML += speakersHTML;
                            // Add modal to body
                            document.body.appendChild(modalDiv);

                            // Add <hr> after each element
                            const allSpeakers = modalContent.querySelectorAll(".pwe-box-modal-speaker");
                            if (allSpeakers.length > 1) {
                                allSpeakers.forEach((speaker, index) => {
                                    // Add <hr> after each element that is not the last one
                                    if (index < allSpeakers.length - 1) {
                                        // Create <hr> element
                                        const hrModalContainer = document.createElement("div");
                                        hrModalContainer.className = "pwe-modal-hr-container";
                                        const hrModal = document.createElement("hr");
                                        hrModal.className = "pwe-modal-hr";
                                        hrModalContainer.appendChild(hrModal);
                                        speaker.parentNode.insertBefore(hrModalContainer, speaker.nextSibling);
                                    }
                                });
                            }

                            // Set 90% width for element
                            const modalSpeakers = modalDiv.querySelectorAll(".pwe-box-modal-speaker .pwe-box-modal-name");
                            if (modalSpeakers.length > 0) {
                                modalSpeakers[0].style.width = "90%";
                            }

                            requestAnimationFrame(() => {
                                modalDiv.classList.add("is-visible");
                            });
                            disableScroll();

                            // Close modal
                            modalDiv.querySelector(".pwe-box-modal-close").addEventListener("click", function() {
                                modalDiv.classList.remove("is-visible");
                                setTimeout(() => {
                                    modalDiv.remove();
                                    enableScroll();
                                }, 300); // Wait for the animation to finish before removing
                            });

                            // If the modal is closed, remove the modal
                            modalDiv.addEventListener("click", function(event) {
                                if (event.target === modalDiv) {
                                    modalDiv.classList.remove("is-visible");
                                    setTimeout(() => {
                                        modalDiv.remove();
                                        enableScroll();
                                    }, 300);
                                }
                            });
                        });
                    }

                    // Disable page scrolling if the module is active
                    function disableScroll() {
                        document.body.style.overflow = "hidden";
                        document.documentElement.style.overflow = "hidden";
                    }
                    // Enable page scrolling if the module is inactive
                    function enableScroll() {
                        document.body.style.overflow = "";
                        document.documentElement.style.overflow = "";
                    }
                });

            </script>';

        } else {
            // Output simple mode content
            $output .= ' 
            <style>
                #info-box-'. self::$rnd_id .' {
                    width: 100% !important;
                    padding: 10px;
                    margin: 0;
                    border-radius: 5px;
                }
                .wpb_column #info-box-'. self::$rnd_id .':nth-of-type(2n) {
                    background-color: #E5E4E2 !important;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-info {
                    width: 100% !important;  
                    flex-direction: row;
                    justify-content: start;
                    gap: 18px;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-lecture-time-container {
                    min-width: 140px;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-lecture-info-container {
                    display: flex;
                    flex-direction: column;
                }
                #info-box-'. self::$rnd_id .' .pwe-box-lecturer-name {
                    font-weight: 500;
                    padding-top: 8px;
                }
                @media (max-width:960px) {
                    #info-box-'. self::$rnd_id .' .pwe-box-info {
                        flex-direction: column !important;
                    }
                }
            </style>';

            $output .= '
            <div class="pwe-box-info">';
                $output .= '
                    <div class="pwe-box-lecture-time-container">
                        <h4 class="pwe-box-lecture-time">'. $info_box_event_time .'</h4>
                    </div>
                    <div class="pwe-box-lecture-info-container">
                        <h4 class="pwe-box-lecture-title" style="color:'. $info_box_title_color .';">'. $info_box_event_title .'</h4>
                        <h5 class="pwe-box-lecturer-name" style="color:'. $info_box_lect_color .';">'. implode('<br>', $speaker_names) .'</h5>
                    </div>';
            $output .= '
            </div>';
        }

        return $output;
    }
}