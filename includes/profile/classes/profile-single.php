<?php 

/**
 * Class PWEProfileSingle
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWEProfileSingle extends PWEProfile {

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

        // GET GALLERY IMAGES LIST
        $doc_images = glob($_SERVER['DOCUMENT_ROOT'] . '/doc/galeria/*.{jpeg,jpg,png,webp,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);
        $name_images = array();
        $name_images['Wybierz'] = '';
        foreach ($doc_images as $image_path) {
            $file_info = pathinfo($image_path);
            $file_name = $file_info['basename'];
            $name_images[$file_name] = $file_name;
        }

        $element_output = array(
            array(
                'type' => 'checkbox',
                'heading' => __('Title', 'pwe_profile'),
                'param_name' => 'profile_title_checkbox',
                'save_always' => true,
                'admin_label' => true,
                'value' => array(
                  __('PROFIL ODWIEDZAJĄCEGO', 'pwe_profile') => 'profile_title_visitors',
                  __('PROFIL WYSTAWCY', 'pwe_profile') => 'profile_title_exhibitors',
                  __('ZAKRES BRANŻOWY', 'pwe_profile') => 'profile_title_scope',
                  __('APLIKACJA', 'pwe_profile') => 'profile_application',
                ),
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Title custom', 'pwe_profile'),
                'param_name' => 'profile_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textarea_html',
                'heading' => __('Text', 'pwe_profile'),
                'param_name' => 'content',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'heading' => __('Show more text', 'pwe_profile'),
                'param_name' => 'profile_show_more',
                'description' => __('Hidden text', 'pwe_profile'),
                'param_holder_class' => 'backend-textarea-raw-html',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'heading' => __('Text mobile', 'pwe_profile'),
                'param_name' => 'profile_mobile_text',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'heading' => __('Show more text mobile', 'pwe_profile'),
                'param_name' => 'profile_mobile_show_more',
                'description' => __('Hidden text', 'pwe_profile'),
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'param_group',
                'param_name' => 'profile_images',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Catalog MEDIA', 'pwe_profile'),
                        'param_name' => 'catalog_media',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Catalog DOC', 'pwe_profile'),
                        'param_name' => 'catalog_doc',
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => $name_images
                    ),
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Reverse blocks', 'pwe_profile'),
                'param_name' => 'profile_reverse_block',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Buttons', 'pwe_profile'),
                'param_name' => 'profile_buttons',
                'save_always' => true,
                'value' => array(
                  __('Register button', 'pwe_profile') => 'profile_btn_rej',
                  __('Tickets button', 'pwe_profile') => 'profile_btn_tick',
                  __('Book a stand button', 'pwe_profile') => 'profile_btn_exhib',
                ),
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Border', 'pwe_profile'),
                'param_name' => 'profile_border',
                'save_always' => true,
                'value' => array(
                  __('border_top', 'pwe_profile') => 'border_top',
                  __('border_bottom', 'pwe_profile') => 'border_bottom',
                ),
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Background color', 'pwe_profile'),
                'param_name' => 'profile_background',
                'save_always' => true,
                'value' => self::$fair_colors,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ), 
            array(
                'type' => 'textfield',
                'heading' => __('Tickets button link', 'pwe_profile'),
                'description' => __('Default (/bilety/ - PL), (/tickets/ - EN)', 'pwe_profile'),
                'param_name' => 'profile_tickets_button_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Register button link', 'pwe_profile'),
                'description' => __('Default (/rejestracja/ - PL), (/registration/ - EN)', 'pwe_profile'),
                'param_name' => 'profile_register_button_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Exhibitors button link', 'pwe_profile'),
                'description' => __('Default (/zostan-wystawca/ - PL), (/become-an-exhibitor/ - EN)', 'pwe_profile'),
                'param_name' => 'profile_exhibitors_button_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Aspect ratio (Default 3/2)', 'pwe_profile'),
                'param_name' => 'profile_img_aspect_ratio',
                'description' => __('Default (PROFIL ODWIEDZAJĄCEGO i PROFIL WYSTAWCY - 1/1, ZAKRES BRANŻOWY - 3/2)', 'pwe_profile'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Max width (Default 80%)', 'pwe_profile'),
                'description' => __('Default (PROFIL ODWIEDZAJĄCEGO i PROFIL WYSTAWCY - 80%, ZAKRES BRANŻOWY - 100%)', 'pwe_profile'),
                'param_name' => 'profile_img_max_width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Padding (Default 18px 36px)', 'pwe_profile'),
                'param_name' => 'profile_padding_element',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSingle',
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
    public static function output($atts, $content = null) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']) . '!important';
        $profile_shadow = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important';

        extract( shortcode_atts( array(
            'profile_title_checkbox' => '',
            'profile_title' => '',
            'profile_images' => '',
            'profile_buttons' => '',
            'profile_border' => '',
            'profile_background' => '',
            'profile_reverse_block' => '',
            'profile_show_more' => '',
            'profile_mobile_text' => '',
            'profile_mobile_show_more' => '',
            'profile_tickets_button_link' => '',
            'profile_register_button_link' => '',
            'profile_exhibitors_button_link' => '',
            'profile_img_aspect_ratio' => '',
            'profile_img_max_width' => '',
            'profile_padding_element' => '',
        ), $atts ));

        // Main content field
        $profile_content = wpb_js_remove_wpautop($content, true);
        $profile_mobile_content = self::decode_clean_content($profile_mobile_text);

        // Show more content field
        $profile_hidden_content = self::decode_clean_content($profile_show_more);
        $profile_mobile_show_more_hidden = self::decode_clean_content($profile_mobile_show_more);

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        if (in_array('profile_title_scope', explode(',', $profile_title_checkbox))) {
            $profile_img_max_width = ($profile_img_max_width == '') ? '100%' : $profile_img_max_width;
        } else {
            $profile_img_max_width = ($profile_img_max_width == '') ? '80%' : $profile_img_max_width;
        }
        if (in_array('profile_title_visitors', explode(',', $profile_title_checkbox)) || in_array('profile_title_exhibitors', explode(',', $profile_title_checkbox))) {
            $profile_img_aspect_ratio = ($profile_img_aspect_ratio == '') ? '1/1' : $profile_img_aspect_ratio;
        } else {
            $profile_img_aspect_ratio = ($profile_img_aspect_ratio == '') ? '3/2' : $profile_img_aspect_ratio;
        }
        if ($profile_padding_element == '') {
            $profile_padding_element = '18px 36px';
        }

        $unique_id = rand(10000, 99999);
        $element_unique_id = 'profile-' . $unique_id;

        $custom_profile_class_title = "";
        
        if (in_array('profile_title_visitors', explode(',', $profile_title_checkbox))) {
            $profile_id = "visitor-profile";
            $custom_profile_title = (get_locale() == 'pl_PL') ? "Profil odwiedzającego" : "Visitor profile"; 
            $profile_img_aspect_ratio = ($profile_img_aspect_ratio == '') ? "auto" : $profile_img_aspect_ratio;
            if (get_locale() == "pl_PL") {
                $profile_header_text = '<p class="profile-header-text" style="color: '. $text_color .';">Wśród odwiedzających targi [trade_fair_name] znajdą się zaproszeni przez nas i Wystawców:</p>';
            } else {
                $profile_header_text = '<p class="profile-header-text" style="color: '. $text_color .';">Visitors of the [trade_fair_name_eng] fair will include invited by us and the Exhibitors:</p>';
            }
            $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color) . '!important';
        } else if (in_array('profile_title_exhibitors', explode(',', $profile_title_checkbox))) {
            $profile_id = "exhibitor-profile";
            $custom_profile_title = (get_locale() == 'pl_PL') ? "Profil wystawcy" : "Exhibitor profile";
            $profile_img_aspect_ratio = ($profile_img_aspect_ratio == '') ? "auto" : $profile_img_aspect_ratio;
            if (get_locale() == "pl_PL") {
                $profile_header_text = '<p class="profile-header-text" style="color: '. $text_color .';">Wśród Wystawców targów [trade_fair_name] znajdą się firmy z następujących sektorów:</p>';
            } else {
                $profile_header_text = '<p class="profile-header-text" style="color: '. $text_color .';">Exhibitors at [trade_fair_name_eng] will include companies from the following sectors:</p>';
            }
        } else if (in_array('profile_title_scope', explode(',', $profile_title_checkbox))) {
            $profile_id = "industry-scope";
            $custom_profile_title = (get_locale() == 'pl_PL') ? "Zakres branżowy" : "Industry scope";
            $custom_profile_class_title = "class=main-heading-text";
            $profile_img_aspect_ratio = ($profile_img_aspect_ratio == '') ? "auto" : $profile_img_aspect_ratio;
            if (get_locale() == "pl_PL") {
                $profile_header_text = '<p class="profile-header-text" style="color: '. $text_color .';">Podczas targów [trade_fair_name] będą reprezentowane następujące sektory i branże:</p>';
            } else {
                $profile_header_text = '<p class="profile-header-text" style="color: '. $text_color .';">The following sectors and industries will be represented at [trade_fair_name_eng]:</p>';
            }
        } else {
            $custom_profile_title = $profile_title;
            $profile_id = $element_unique_id;
        }

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $output = '
            <style>
                .profile-single-'. self::$rnd_id .' {
                    margin: 0 !important;
                }
                .profile-single-'. self::$rnd_id .' .pwe-btn {
                    color: '. $btn_text_color .';
                    background-color: '. $btn_color .';
                    border-radius: 10px;
                    border: none;
                }
                .profile-single-'. self::$rnd_id .' .pwe-btn:hover {
                    color: '. $btn_text_color .';
                    background-color: '. $darker_btn_color .'!important;
                    border: none;
                }
                .row-parent:has(.profile-single-'. self::$rnd_id .' .pwe-container-profile) {
                    max-width: 100%;
                    padding: 0 !important;  
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-wrapper {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: ' . $profile_padding_element . ';   
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-content {
                    display: flex;
                    gap: 36px;
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-text-block {
                    display: flex;
                    flex-direction: column;
                    
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-text-block h4 {
                    font-size: 20px !important;
                    font-weight: 600 !important;
                    text-transform: uppercase !important;
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-images-block {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-images-wrapper {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                    gap: 36px;
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-image {
                    object-fit: cover;
                    width: ' . $profile_img_max_width . ';
                    aspect-ratio: ' . $profile_img_aspect_ratio . ';
                    border-radius: 18px;
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-block {
                    width: 50%;
                    margin: 0 auto;
                }
                .profile-single-'. self::$rnd_id .' .pwe-profile-buttons {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-around;
                    padding: 18px 0;
                }
                .profile-single-'. self::$rnd_id .' .pwe-link {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }
                .profile-single-'. self::$rnd_id .' .profile-box-shadow-left {
                    margin-left: -10px;
                    margin-bottom: -31px;
                    box-shadow: -3px -3px ' . $profile_shadow . ';
                    width: 170px !important;
                    height: 40px;
                }
                .profile-single-'. self::$rnd_id .' .profile-box-shadow-right {
                    margin-right: -10px;
                    margin-top: -46px;
                    box-shadow: 3px 3px ' . $profile_shadow . ';
                    width: 170px !important;
                    height: 40px;
                    float: right;
                }
                .profile-single-'. self::$rnd_id .' .pwe-hidden-content ul,
                .profile-single-'. self::$rnd_id .' .pwe-hidden-content,
                .profile-single-'. self::$rnd_id .' .pwe-see-more {
                    margin: 0 !important;
                }
                #zakres-branzowy .pwe-uppercase {
                    padding: 0 12px 10px 0 !important;
                    margin-bottom: 18px;
                }
                @media (max-width: 960px) {
                    .profile-single-'. self::$rnd_id .' .pwe-profile-content {
                        flex-direction: column-reverse;
                        gap: 0 !important;
                    }
                    .profile-single-'. self::$rnd_id .' .pwe-profile-block {
                        width: 100% !important;
                    }
                    .profile-single-'. self::$rnd_id .' .pwe-profile-image {
                        aspect-ratio: auto !important;
                    }
                }
                @media (max-width: 600px) {
                    .profile-single-'. self::$rnd_id .' .pwe-profile-image {
                        width: 100%;
                    }
                }
            </style>';

        if ($profile_reverse_block == 'true') {
            $output .= '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-profile-content {
                    flex-direction: row-reverse;
                }
                @media (max-width: 960px) {
                    .pwelement_'. self::$rnd_id .' .pwe-profile-content {
                        flex-direction: column-reverse;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-profile-text-block p:first-of-type {
                        display: none;
                    }
                }
            </style>';
        }

        $output .= '
        <div id="'. $profile_id .'" class="pwe-container-profile" style="background-color:'. $profile_background .';">
            <div class="pwe-profile-wrapper">';
                if (in_array('border_top', explode(',', $profile_border))) {  
                    $output .= '<p class="profile-box-shadow-left">&nbsp;</p>';
                }
                $output .= '<div class="pwe-profile-main-section">
                    <div class="pwe-profile-content">
                        <div class="pwe-profile-text-block pwe-profile-block">';
                            if ($custom_profile_title) {
                                $output .= '<div '. $custom_profile_class_title .'><h4 class="pwe-uppercase" style="padding-bottom: 18px; color: '. $text_color .';">'. $custom_profile_title .'</h4></div>';
                            }

                            $output .= $profile_header_text;

                            if ($mobile == 1 && !empty($profile_mobile_content)) {
                                $output .= '<div style="color: '. $text_color .';";>'. $profile_mobile_content .'</div>';
                                if (!empty($profile_mobile_show_more_hidden)) {
                                    $showMore = get_locale() == "pl_PL" ? "więcej..." : "more...";
                                    $output .= '
                                        <div class="pwe-hidden-content" style="display: none; color: '. $text_color .';">'. $profile_mobile_show_more_hidden .'</div>
                                        <p class="pwe-see-more" style="cursor: pointer; color: '. $text_color .';">'. $showMore .'</p>';
                                }
                            } else {
                                $output .= '<div style="color: '. $text_color .';">'. $profile_content .'</div>';
                                if (!empty($profile_hidden_content)) {
                                    $showMore = get_locale() == "pl_PL" ? "więcej..." : "more...";
                                    $output .= '
                                        <div class="pwe-hidden-content" style="display: none; color: '. $text_color .';">'. $profile_hidden_content .'</div>
                                        <p class="pwe-see-more" style="cursor: pointer; color: '. $text_color .';">'. $showMore .'</p>';
                                }
                            }
                            
                        $output .= '
                        </div>
                        <div class="pwe-profile-images-block pwe-profile-block">
                            <div class="pwe-profile-images-wrapper">';

                                if (session_status() === PHP_SESSION_NONE) {
                                    session_start();
                                }

                                $profile_images_urldecode = urldecode($profile_images);
                                $profile_images_json = json_decode($profile_images_urldecode, true);

                                if (!isset($_SESSION['last_displayed_image_index'])) {
                                    $_SESSION['last_displayed_image_index'] = -1;
                                }

                                foreach ($profile_images_json as $index => $profile_image) {
                                    $profile_image_media = $profile_image["catalog_media"];
                                    $profile_image_url_media = wp_get_attachment_url($profile_image_media);
                                    $profile_image_doc = $profile_image["catalog_doc"];

                                    if (!empty($profile_image_url_media) && empty($profile_image_doc)) {
                                        $output .= '<img class="pwe-profile-image t-entry-visual" src="'. $profile_image_url_media .'" alt="'. $profile_title .'">';
                                    } else if (empty($profile_image_url_media) && !empty($profile_image_doc)) {
                                        $output .= '<img class="pwe-profile-image t-entry-visual" src="/doc/galeria/'. $profile_image_doc .'" alt="'. $profile_title .'">';
                                    } else if (!empty($profile_image_url_media) && !empty($profile_image_doc)) {
                                        $output .= '<img class="pwe-profile-image t-entry-visual" src="/doc/galeria/'. $profile_image_doc .'" alt="'. $profile_title .'">';
                                    } else {
                                        $profile_optimazed_images = $_SERVER['DOCUMENT_ROOT'] . '/doc/galeria/zdjecia_wys_odw';
                                        $profile_gallery_images = $_SERVER['DOCUMENT_ROOT'] . '/doc/galeria';
                                        $file_extensions = 'jpeg,jpg,png,webp,JPEG,JPG,PNG,WEBP';
                                        if (is_dir($profile_optimazed_images) && !empty(glob($profile_optimazed_images . '/*.{'. $file_extensions .'}', GLOB_BRACE))) {
                                            $profile_image_gallery_path = $profile_optimazed_images;
                                        } else {
                                            $profile_image_gallery_path = $profile_gallery_images;
                                        }
                                        $all_images = glob($profile_image_gallery_path . '/*.{'. $file_extensions .'}', GLOB_BRACE);
                                        sort($all_images); 

                                        $next_image_index = ($_SESSION['last_displayed_image_index'] + 1) % count($all_images);
                                        $next_image_path = $all_images[$next_image_index];
                                        $_SESSION['last_displayed_image_index'] = $next_image_index;
                                        
                                        $profile_image_gallery_short_path = substr($next_image_path, strpos($next_image_path, '/doc/'));
                                        $output .= '<img class="pwe-profile-image t-entry-visual" src="'. $profile_image_gallery_short_path.'" alt="'. $profile_title .'">';
                                    }
                                }

                            $output .= '</div>
                        </div>
                    </div>';

                    if (get_locale() == 'pl_PL') {
                        $profile_tickets_button_link = empty($profile_tickets_button_link) ? "/bilety/" : $profile_tickets_button_link;
                        $profile_register_button_link = empty($profile_register_button_link) ? "/rejestracja/" : $profile_register_button_link;
                        $profile_exhibitors_button_link = empty($profile_exhibitors_button_link) ? "/zostan-wystawca/" : $profile_exhibitors_button_link;
                    } else {
                        $profile_tickets_button_link = empty($profile_tickets_button_link) ? "/en/tickets/" : $profile_tickets_button_link;
                        $profile_register_button_link = empty($profile_register_button_link) ? "/en/registration/" : $profile_register_button_link;
                        $profile_exhibitors_button_link = empty($profile_exhibitors_button_link) ? "/en/become-an-exhibitor/" : $profile_exhibitors_button_link;
                    }

                    if (in_array('profile_btn_tick', explode(',', $profile_buttons)) || in_array('profile_btn_rej', explode(',', $profile_buttons)) || in_array('profile_btn_exhib', explode(',', $profile_buttons))) {
                        $output .= '  <div class="pwe-profile-buttons">';
                        if (in_array('profile_btn_tick', explode(',', $profile_buttons))) {
                            $output .= '<div class="pwe-btn-container">
                                            <a class="pwe-link btn pwe-btn" href="'. $profile_tickets_button_link .'"'. 
                                                self::languageChecker('alt="link do biletów">Kup bilet</a>', 'alt="link to tickets">Buy a ticket')
                                            .'</a>  
                                        </div>';
                        }
                        if (in_array('profile_btn_rej', explode(',', $profile_buttons))) {
                            $output .= '<div class="pwe-btn-container">
                                            <a class="pwe-link btn pwe-btn" href="'. $profile_register_button_link .'"'. 
                                                self::languageChecker('alt="link do rejestracji">Weź udział', 'alt="link to registration">Take a part')
                                            .'</a>  
                                        </div>';
                        }
                        if (in_array('profile_btn_exhib', explode(',', $profile_buttons))) {
                            $output .= '<div class="pwe-btn-container">
                                            <a class="pwe-link btn pwe-btn" href="'. $profile_exhibitors_button_link .'"'. 
                                                self::languageChecker('alt="link do rejestracji wystawców">Zostań wystawcą', 'alt="link to exhibitor registration">Book a stand')
                                            .'</a>  
                                        </div>';
                        }
                        $output .= '</div>';
                    }
                    
                $output .= '</div>';
                if (in_array('border_bottom', explode(',', $profile_border))) {
                    $output .= '<p class="profile-box-shadow-right">&nbsp;</p>';
                }
            $output .= '</div>
        </div>';
        
        if ($mobile == 1) {
            $output .= '<script>
                {
                    const imagesProfiles = document.querySelector(".profile-single-'. self::$rnd_id .' .custom-profile-images-wrapper");
                    if (imagesProfiles && imagesProfiles.children.length > 1) {
                        for (let i = 1; i < imagesProfiles.children.length; i++) {
                            imagesProfiles.children[i].style.display = "none";
                        }
                    }
                }
            </script>';
        }
       
        

        return $output;
    }
}