<?php

class PWEMediaGallery extends PWECommonFunctions {
    public static $rnd_id;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        self::$rnd_id = $this->id_rnd();

        // Hook actions
        add_action('wp_enqueue_scripts', array($this, 'addingStyles'));
        add_action('wp_enqueue_scripts', array($this, 'addingScripts'));
        
        // add_action('vc_before_init', array($this, 'inputRange'));
        add_action('init', array($this, 'initVCMapMediaGallery'));
        add_shortcode('pwe_media_gallery', array($this, 'PWEMediaGalleryOutput'));
    }

    /**
     * Initialize VC Map Elements.
     */
    public function initVCMapMediaGallery() {
        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __('PWE Media Gallery', 'pwe_media_gallery'),
                'base' => 'pwe_media_gallery',
                'category' => __('PWE Elements', 'pwe_media_gallery'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'admin_enqueue_js' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendscript.js',
                'params' => array(
                    array(
                        'type' => 'attach_images',
                        'group' => 'PWE Element',
                        'heading' => __('Select Images', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_images',
                        'description' => __('Choose images from the media library.', 'pwe_media_gallery'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Gallery Catalog', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_catalog',
                        'description' => __('Set a catalog name in /doc/', 'pwe_media_gallery'),
                        'save_always' => true,
                    ),  
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Unique ID', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_id',
                        'description' => __('This value has to be unique. Change it in case it`s needed.', 'pwe_media_gallery'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Gallery name', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_name',
                        'description' => __('Set a name that will by displayed at the top of gallery', 'pwe_media_gallery'),
                        'save_always' => true,
                    ),  
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Images clicked', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_clicked',
                        'description' => __('Action after clicking on the image', 'pwe_media_gallery'),
                        'save_always' => true,
                        'value' => array(
                            'Fullscreen' => 'fullscreen',
                            'Linked' => 'linked',
                            'Nothing' => 'nothing',
                        ),
                    ),
                    array(
                        'type' => 'param_group',
                        'group' => 'Links',
                        'heading' => __('Set link', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_links',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => __('Filename(ex. file.png)', 'pwe_media_gallery'),
                                'param_name' => 'media_gallery_filename',
                                'save_always' => true,
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Link', 'pwe_media_gallery'),
                                'param_name' => 'media_gallery_link',
                                'save_always' => true,
                                'admin_label' => true
                            ),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Layout', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_layout',
                        'param_holder_class' => 'media_gallery_layout',
                        'save_always' => true,
                        'value' => array(
                            'Columns' => 'columns',
                            'Grid' => 'grid',
                            'Justify' => 'flex',
                            'Carousel' => 'carousel',
                            'Slider with thumbnails' => 'slider',
                            'Coverflow' => 'coverflow',
                            'Carousel Dual Swiper' => 'carousel-dual-swiper',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'Justify',
                        'heading' => __('Justify last row', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_justify_last_row',
                        'description' => __('Don`t justify the last row', 'pwe_media_gallery'),
                        'save_always' => true,
                        'value' => array(
                            'Nojustify' => 'nojustify',
                            'Justify' => 'justify',
                            'Left' => 'left',
                            'Center' => 'center',
                            'Right' => 'right',
                            'Hide' => 'hide',
                        ),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'flex'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Aspect Ratio', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_aspect_ratio',
                        'description' => __('Set an aspect ratio for the images', 'pwe_media_gallery'),
                        'save_always' => true,
                        'value' => array(
                            'Default' => '',
                            '1:1' => '1/1',
                            '2:1' => '2/1',
                            '3:2' => '3/2',
                            '4:3' => '4/3',
                            '5:4' => '5/4',
                            '10:3' => '10/3',
                            '16:9' => '16/9',
                            '1:2' => '1/2',
                            '2:3' => '2/3',
                            '3:4' => '3/4',
                            '4:5' => '4/5',
                            '3:0' => '3/10',
                            '9:16' => '9/16',
                        ),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'carousel',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Gap', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_gap',
                        'description' => __('Set the items gap.', 'pwe_media_gallery'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'carousel',
                                'coverflow',
                                'carousel-dual-swiper',
                            ),
                        ),
                    ),  
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Border radius', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_border_radius',
                        'description' => __('Set the items border radius.', 'pwe_media_gallery'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'flex',
                                'carousel',
                            ),
                        ),
                    ),  
                    array(
                        'type' => 'textfield',
                        'group' => 'Justify',
                        'heading' => __('Margin items', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_margin',
                        'description' => __('Set the items margin.', 'pwe_media_gallery'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'flex'
                            ),
                        ),
                    ), 
                    array(
                        'type' => 'checkbox',
                        'group' => 'PWE Element',
                        'heading' => __('Full width', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_full_width',
                        'description' => __('overflow: visible;', 'pwe_media_gallery'),
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_media_gallery') => 'true',),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'flex',
                                'slider',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Navigation',
                        'heading' => __('Turn on dots', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_dots_display',
                        'description' => __('Check if you want to turn on dots.', 'pwe_media_gallery'),
                        'admin_label' => true,
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_media_gallery') => 'true',),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'carousel',
                                'coverflow',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Navigation',
                        'heading' => __('Dots inside of container', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_dots_inside',
                        'description' => __('Check if you want to turn on dots inside of container.', 'pwe_media_gallery'),
                        'admin_label' => true,
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_media_gallery') => 'true',),
                        'dependency' => array(
                            'element' => 'media_gallery_dots_display',
                            'value' => array(
                                'true',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Navigation',
                        'heading' => __('Turn on arrow', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_arrows_display',
                        'description' => __('Check if you want to turn on arrows.', 'pwe_media_gallery'),
                        'admin_label' => true,
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_media_gallery') => 'true',),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'carousel',
                                'coverflow',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Navigation',
                        'heading' => __('Arrows inside of container', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_arrows_inside',
                        'description' => __('Check if you want to turn on arrows inside of container.', 'pwe_media_gallery'),
                        'admin_label' => true,
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_media_gallery') => 'true',),
                        'dependency' => array(
                            'element' => 'media_gallery_arrows_display',
                            'value' => array(
                                'true',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Navigation',
                        'heading' => __('Add button (Go to gallery)', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_button',
                        'admin_label' => true,
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_media_gallery') => 'true',),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'carousel',
                                'coverflow',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Thumbnail width (desktop', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_thumbnails_width_desktop',
                        'description' => __('Specify the thumbnail width for desktop.', 'pwe_media_gallery'),
                        'param_holder_class' => 'backend-area-one-third-width thumbnails_width_columns',
                        'save_always' => true,
                        'value' => array(
                            'Default' => '',
                            '12 columns' => '12',
                            '11 columns' => '11',
                            '10 columns' => '10',
                            '9 columns' => '9',
                            '8 columns' => '8',
                            '7 columns' => '7',
                            '6 columns' => '6',
                            '5 columns' => '5',
                            '4 columns' => '4',
                            '3 columns' => '3',
                            '2 columns' => '2',
                            '1 column' => '1',
                        ),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'carousel',
                            ),
                        ),
                    ), 
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Thumbnail width (tablet)', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_thumbnails_width_tablet',
                        'description' => __('Specify the thumbnail width for tablet.', 'pwe_media_gallery'),
                        'param_holder_class' => 'backend-area-one-third-width thumbnails_width_columns',
                        'save_always' => true,
                        'value' => array(
                            'Default' => '',
                            '12 columns' => '12',
                            '11 columns' => '11',
                            '10 columns' => '10',
                            '9 columns' => '9',
                            '8 columns' => '8',
                            '7 columns' => '7',
                            '6 columns' => '6',
                            '5 columns' => '5',
                            '4 columns' => '4',
                            '3 columns' => '3',
                            '2 columns' => '2',
                            '1 column' => '1',
                        ),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'carousel',
                            ),
                        ),
                    ), 
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Thumbnail width (mobile)', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_thumbnails_width_mobile',
                        'description' => __('Specify the thumbnail width for mobile.', 'pwe_media_gallery'),
                        'param_holder_class' => 'backend-area-one-third-width thumbnails_width_columns',
                        'save_always' => true,
                        'value' => array(
                            'Default' => '',
                            '12 columns' => '12',
                            '11 columns' => '11',
                            '10 columns' => '10',
                            '9 columns' => '9',
                            '8 columns' => '8',
                            '7 columns' => '7',
                            '6 columns' => '6',
                            '5 columns' => '5',
                            '4 columns' => '4',
                            '3 columns' => '3',
                            '2 columns' => '2',
                            '1 column' => '1',
                        ),
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'carousel',
                            ),
                        ),
                    ),   
                    array(
                        'type' => 'textfield',
                        'group' => 'Justify',
                        'heading' => __('Thumbnails rows height (200px default)', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_thumbnails_rows_height',
                        'description' => __('Write the thumbnails rows height.', 'pwe_media_gallery'),
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'flex'
                            ),
                        ),
                    ),   
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Breakpoint for tablet', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_breakpoint_tablet',
                        'description' => __('Set a breakpoint for tablet (default 959px)', 'pwe_media_gallery'),
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'carousel',
                            ),
                        ),
                    ),  
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Breakpoint for mobile', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_breakpoint_mobile',
                        'description' => __('Set a breakpoint for mobile (default 480px)', 'pwe_media_gallery'),
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'media_gallery_layout',
                            'value' => array(
                                'columns',
                                'grid',
                                'carousel',
                            ),
                        ),
                    ), 
                    array(
                        'type' => 'textfield',
                        'group' => 'Coverflow Effect',
                        'heading' => __('Coverflow: Rotate', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_coverflow_rotate',
                        'dependency' => array('element' => 'media_gallery_layout', 'value' => array('coverflow')),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Coverflow Effect',
                        'heading' => __('Coverflow: Stretch', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_coverflow_stretch',
                        'dependency' => array('element' => 'media_gallery_layout', 'value' => array('coverflow')),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Coverflow Effect',
                        'heading' => __('Coverflow: Scale', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_coverflow_scale',
                        'dependency' => array('element' => 'media_gallery_layout', 'value' => array('coverflow')),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Coverflow Effect',
                        'heading' => __('Coverflow: Depth', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_coverflow_depth',
                        'dependency' => array('element' => 'media_gallery_layout', 'value' => array('coverflow')),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Coverflow Effect',
                        'heading' => __('Coverflow: Modifier', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_coverflow_modifier',
                        'dependency' => array('element' => 'media_gallery_layout', 'value' => array('coverflow')),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'Coverflow Effect',
                        'heading' => __('Coverflow: Slide Shadows', 'pwe_media_gallery'),
                        'param_name' => 'media_gallery_coverflow_shadows',
                        'value' => array('true' => 'true', 'false' => 'false'),
                        'dependency' => array('element' => 'media_gallery_layout', 'value' => array('coverflow')),
                    ),
                ),
            ));
        }
    }

    /**
     * Adding Styles
     */
    public function addingStyles(){
        $css_file = plugins_url('assets/media-gallery.css', __FILE__);
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/media-gallery.css');
        wp_enqueue_style('pwe-media-gallery-css', $css_file, array(), $css_version);
    }

    /**
     * Adding Scripts
     */
    public function addingScripts(){
        $js_file = plugins_url('assets/media-gallery.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/media-gallery.js');
        wp_enqueue_script('pwe-media-gallery-js', $js_file, array('jquery'), $js_version, true);
    }
    
    /**
     * Output method for PWEMediaGallery shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWEMediaGalleryOutput($atts, $content = null) {
        extract(shortcode_atts(array(
            'media_gallery_images' => '',
            'media_gallery_catalog' => '',
            'media_gallery_id' => '',
            'media_gallery_name' => '',
            'media_gallery_clicked' => '',
            'media_gallery_links' => '',
            'media_gallery_layout' => '',
            'media_gallery_justify_last_row' => '',
            'media_gallery_aspect_ratio' => '',
            'media_gallery_gap' => '',
            'media_gallery_border_radius' => '',
            'media_gallery_margin' => '',
            'media_gallery_full_width' => '',
            'media_gallery_dots_display' => '',
            'media_gallery_dots_inside' => '',
            'media_gallery_arrows_display' => '',
            'media_gallery_arrows_inside' => '',
            'media_gallery_button' => '',
            'media_gallery_thumbnails_width_desktop' => '',
            'media_gallery_thumbnails_width_tablet' => '',
            'media_gallery_thumbnails_width_mobile' => '',
            'media_gallery_thumbnails_rows_height' => '',
            'media_gallery_breakpoint_tablet' => '',
            'media_gallery_breakpoint_mobile' => '',
            'media_gallery_coverflow_rotate' => '',
            'media_gallery_coverflow_stretch' => '',
            'media_gallery_coverflow_scale' => '',
            'media_gallery_coverflow_depth' => '',
            'media_gallery_coverflow_modifier' => '',
            'media_gallery_coverflow_shadows' => '',
        ), $atts));
    
        $media_gallery_images = explode(',', $atts['media_gallery_images']);
        $media_gallery_catalog_url = glob($_SERVER['DOCUMENT_ROOT'] . '/doc/' . $media_gallery_catalog . '/*.{jpeg,jpg,png,webp,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);  
        
        $media_gallery_array = [];

        // Add the URLs of the gallery images to the array, if they exist
        foreach ($media_gallery_images as $image_id) {
            $media_gallery_image_url = wp_get_attachment_url($image_id);
            if ($media_gallery_image_url) {
                $media_gallery_array[] = $media_gallery_image_url;
            }
        }

        // Add the URLs of the images from the directory to the array, if they exist
        if (!empty($media_gallery_catalog)) {
            if (!empty($media_gallery_catalog_url)) {
                foreach ($media_gallery_catalog_url as $catalog_image_url) {
                    $media_gallery_array[] = substr($catalog_image_url, strpos($catalog_image_url, '/doc/'));
                }
            }
        }

        // Create unique id for element
        $unique_id = rand(10000, 99999);
        $element_unique_id = 'pweMediaGallery-' . $unique_id;
        $media_gallery_id = ($media_gallery_id == '') ? $element_unique_id : $media_gallery_id;

        // Extracting data from param group
        $media_gallery_links_urldecode = urldecode($media_gallery_links);
        $media_gallery_links_json = json_decode($media_gallery_links_urldecode, true);
        $media_gallery_filename_array = array();
        $media_gallery_link_array = array();
        foreach ($media_gallery_links_json as $media_file) {
            $media_gallery_filename_array[] = $media_file["media_gallery_filename"];
            $media_gallery_link_array[] = $media_file["media_gallery_link"];  
        }
        // Remap the array to use filenames as keys and links as values
        $media_gallery_links_map = array_column($media_gallery_links_json, 'media_gallery_link', 'media_gallery_filename');

        // Set aspect ratio for images
        $aspect_ratio_style = (!empty($media_gallery_aspect_ratio)) ? 'aspect-ratio: ' . $media_gallery_aspect_ratio . '; object-fit: cover;' : '';

        // Set breakpoints for gallery
        $media_gallery_breakpoint_tablet = ($media_gallery_breakpoint_tablet == '') ? '959' : $media_gallery_breakpoint_tablet;
        $media_gallery_breakpoint_mobile = ($media_gallery_breakpoint_mobile == '') ? '480' : $media_gallery_breakpoint_mobile;

        // Set gap for images
        $media_gallery_gap = (empty($media_gallery_gap)) ? '5' : $media_gallery_gap;

        // Set margin for images
        $media_gallery_margin = (empty($media_gallery_margin)) ? '5' : $media_gallery_margin;

        // Set border radius for images
        $media_gallery_border_radius = (empty($media_gallery_border_radius)) ? '0' : $media_gallery_border_radius;

        // Removing px if it exists
        $media_gallery_breakpoint_tablet = str_replace("px", "", $media_gallery_breakpoint_tablet);
        $media_gallery_breakpoint_mobile = str_replace("px", "", $media_gallery_breakpoint_mobile);
        $media_gallery_thumbnails_rows_height = str_replace("px", "", $media_gallery_thumbnails_rows_height);
        $media_gallery_gap = str_replace("px", "", $media_gallery_gap);
        $media_gallery_margin = str_replace("px", "", $media_gallery_margin);
        $media_gallery_border_radius = str_replace("px", "", $media_gallery_border_radius);

        // Coverflow Effects
        $coverflow_rotate   = is_numeric($media_gallery_coverflow_rotate) ? $media_gallery_coverflow_rotate : 0;
        $coverflow_stretch  = is_numeric($media_gallery_coverflow_stretch) ? $media_gallery_coverflow_stretch : 0;
        $coverflow_scale    = is_numeric($media_gallery_coverflow_scale) ? $media_gallery_coverflow_scale : 0.9;
        $coverflow_depth    = is_numeric($media_gallery_coverflow_depth) ? $media_gallery_coverflow_depth : 345;
        $coverflow_modifier = is_numeric($media_gallery_coverflow_modifier) ? $media_gallery_coverflow_modifier : 1.5;
        $coverflow_shadows  = ($media_gallery_coverflow_shadows === 'false') ? 'false' : 'true';

        
        $output = '';

        if (!empty($media_gallery_array)) {

            $layouts = explode(',', $media_gallery_layout);
            $layout_types = ['columns', 'grid', 'flex', 'carousel', 'slider'];
            foreach ($layout_types as $type) {
                if (in_array($type, $layouts)) {
                    switch ($type) {
                        case 'columns':

                            // Set the number of columns in the gallery
                            $columns_thumbnails_desktop = ($media_gallery_thumbnails_width_desktop == '') ? '3' : $media_gallery_thumbnails_width_desktop;
                            $columns_thumbnails_tablet = ($media_gallery_thumbnails_width_tablet == '') ? '2' : $media_gallery_thumbnails_width_tablet;
                            $columns_thumbnails_mobile = ($media_gallery_thumbnails_width_mobile == '') ? '2' : $media_gallery_thumbnails_width_mobile;

                            $output .=  '<style>
                                            /* Columns */
                                            #'. $media_gallery_id .' .pwe-media-gallery {
                                                column-gap: 0;
                                            }
                                            #'. $media_gallery_id .' .pwe-media-gallery-image {
                                                padding: '. $media_gallery_gap .'px;
                                            }
                                            #'. $media_gallery_id .' .pwe-media-gallery-image img {
                                                border-radius: '. $media_gallery_border_radius .'px;
                                            }
                                            @media (min-width: '. ($media_gallery_breakpoint_tablet + 1) .'px) {
                                                #'. $media_gallery_id .' .pwe-media-gallery {
                                                    column-count: '. $columns_thumbnails_desktop .';
                                                }
                                            }
                                            @media (max-width: '. $media_gallery_breakpoint_tablet .'px) {
                                                #'. $media_gallery_id .' .pwe-media-gallery {
                                                    column-count: '. $columns_thumbnails_tablet .';
                                                }
                                            }
                                            @media (max-width: '. $media_gallery_breakpoint_mobile .'px) {
                                                #'. $media_gallery_id .' .pwe-media-gallery {
                                                    column-count: '. $columns_thumbnails_mobile .';
                                                }
                                            } 
                                        </style>';
                            break;
                        case 'grid':

                            // Set the number of columns in the gallery
                            $grid_thumbnails_desktop = ($media_gallery_thumbnails_width_desktop == '') ? '3' : $media_gallery_thumbnails_width_desktop;
                            $grid_thumbnails_tablet = ($media_gallery_thumbnails_width_tablet == '') ? '2' : $media_gallery_thumbnails_width_tablet;
                            $grid_thumbnails_mobile = ($media_gallery_thumbnails_width_mobile == '') ? '2' : $media_gallery_thumbnails_width_mobile;

                            $output .=  '<style>
                                            /* Grid */
                                            #'. $media_gallery_id .' .pwe-media-gallery {
                                                display: grid;
                                                gap: '. $media_gallery_gap .'px;
                                            } 
                                            #'. $media_gallery_id .' .pwe-media-gallery-image img {
                                                border-radius: '. $media_gallery_border_radius .'px;
                                            }
                                            @media (min-width: '. ($media_gallery_breakpoint_tablet + 1) .'px) {
                                                #'. $media_gallery_id .' .pwe-media-gallery {
                                                    grid-template-columns: repeat('. $grid_thumbnails_desktop .', 1fr);
                                                }
                                            }
                                            @media (max-width: '. $media_gallery_breakpoint_tablet .'px) {
                                                #'. $media_gallery_id .' .pwe-media-gallery {
                                                    grid-template-columns: repeat('. $grid_thumbnails_tablet .', 1fr);
                                                }
                                            }
                                            @media (max-width: '. $media_gallery_breakpoint_mobile .'px) {
                                                #'. $media_gallery_id .' .pwe-media-gallery {
                                                    grid-template-columns: repeat('. $grid_thumbnails_mobile .', 1fr);
                                                }
                                            }
                                        </style>';
                            break;
                        case 'flex':

                            // Set the height of if images row in the gallery
                            $media_gallery_thumbnails_rows_height = ($media_gallery_thumbnails_rows_height == '') ? '200' : $media_gallery_thumbnails_rows_height;

                            $output .=  '<style>
                                            /* Flexbox */
                                            #'. $media_gallery_id .' .justified-gallery {
                                                opacity: 1 !important;
                                                min-height: auto !important;
                                            }
                                            #'. $media_gallery_id .' .pwe-media-gallery-image img {
                                                border-radius: '. $media_gallery_border_radius .'px;
                                            }
                                        </style>';
                            break;
                        case 'carousel':

                            // Set the width of if images in the carousel
                            $carousel_thumbnails_desktop = ($media_gallery_thumbnails_width_desktop == '') ? '3' : (string) $media_gallery_thumbnails_width_desktop;
                            $carousel_thumbnails_tablet = ($media_gallery_thumbnails_width_tablet == '') ? '2' : (string) $media_gallery_thumbnails_width_tablet;
                            $carousel_thumbnails_mobile = ($media_gallery_thumbnails_width_mobile == '') ? '2' : (string) $media_gallery_thumbnails_width_mobile;

                            if ($carousel_thumbnails_desktop == 1 && $carousel_thumbnails_tablet == 1 && $carousel_thumbnails_mobile == 1) {
                                $output .=  '<style>
                                                /* Carousel */
                                                #'. $media_gallery_id .' .pwe-gallery-container {
                                                    position: relative;
                                                    border-radius: '. $media_gallery_border_radius .'px;
                                                    overflow: hidden;
                                                }
                                                #'. $media_gallery_id .' .media-gallery-btn {
                                                    position: absolute;
                                                    bottom: 6px;
                                                    right: 6px;
                                                    color: white;
                                                    padding: 4px 8px;
                                                    font-size: 12px;
                                                    font-weight: 700;
                                                    text-transform: uppercase;
                                                    border: 2px solid white;
                                                    background: #00000075;
                                                    border-radius: 12px;
                                                    height: unset !important;
                                                }
                                            </style>';
                            }

                            $output .=  '<style>
                                            /* Carousel */
                                            #'. $media_gallery_id .' .pwe-media-gallery-image {
                                                margin: '. ($media_gallery_gap / 2) .'px;
                                            }
                                            #'. $media_gallery_id .' .pwe-media-gallery-image img {
                                                border-radius: '. $media_gallery_border_radius .'px;
                                            }
                                        </style>';
                            break;
                        case 'slider':

                            break;
                    }
                    break;
                }
            }

            $output .= '
            <div id="'. $media_gallery_id .'" class="pwe-container-media-gallery">
                <div class="pwe-media-gallery-wrapper">';

                    if ($media_gallery_name != '') {
                        $output .= '<div class="pwe-media-gallery-title main-heading-text" style="display: flex; padding-bottom: 36px;">
                                        <h4 class="pwe-uppercase" style="margin: 0;">
                                            <span>'. $media_gallery_name .'</span>
                                        </h4>
                                    </div>';
                    }

                    $simple_layout_types = ['columns', 'grid', 'flex'];
                    if (array_intersect($simple_layout_types, $layouts)) { // Gallery <--------------------------------------<
                        $output .= '
                        <div class="pwe-gallery-container pwe-media-gallery">
                        <link href="/wp-content/plugins/PWElements/includes/media-gallery/assets/justified-gallery/justifiedGallery.css" rel="stylesheet">
                        <script src="/wp-content/plugins/PWElements/includes/media-gallery/assets/justified-gallery/jquery.justifiedGallery.js"></script>
                        ';
                        foreach ($media_gallery_array as $image_url) {
                            $path_parts = pathinfo($image_url);
                            $image_filename = $path_parts['basename'];
                            
                            if ($media_gallery_clicked === 'linked' && isset($media_gallery_links_map[$image_filename])) {
                                $output .= '<a href="'. $media_gallery_links_map[$image_filename] .'">
                                                <div class="pwe-media-gallery-image">
                                                    <img src="' . $image_url . '" style="' . $aspect_ratio_style . '">
                                                </div>
                                            </a>';
                            } else { 
                                $output .= '<div class="pwe-media-gallery-image">
                                                <img src="' . $image_url . '" style="' . $aspect_ratio_style . '">
                                            </div>';
                            }
                        }
                        $output .= '</div>';  
                    } else if (in_array('carousel', $layouts)) { // Carousel <--------------------------------------<
                        $aspect_ratio_style = ($aspect_ratio_style == '') ? 'aspect-ratio: 4/3; object-fit: cover;' : $aspect_ratio_style;
                        $output .= '
                        <div class="pwe-gallery-container pwe-media-gallery-carousel pwe-slides">';

                            foreach ($media_gallery_array as $image_url) {
                                $path_parts = pathinfo($image_url);
                                $image_filename = $path_parts['basename'];

                                $media_gallery_options[] = array(
                                    "count-visible-thumbs-desktop" => $carousel_thumbnails_desktop,
                                    "count-visible-thumbs-tablet" => $carousel_thumbnails_tablet,
                                    "count-visible-thumbs-mobile" => $carousel_thumbnails_mobile,
                                    "breakpoint-tablet" => $media_gallery_breakpoint_tablet,
                                    "breakpoint-mobile" => $media_gallery_breakpoint_mobile,
                                    "media_gallery_dots_inside" => $media_gallery_dots_inside,
                                    "media_gallery_arrows_inside" => $media_gallery_arrows_inside,
                                );
                                
                                if ($media_gallery_clicked === 'linked' && isset($media_gallery_links_map[$image_filename])) {
                                    $output .= '<a href="'. $media_gallery_links_map[$image_filename] .'">
                                                    <div class="pwe-media-gallery-image">
                                                        <img src="' . $image_url . '" style="' . $aspect_ratio_style . '">
                                                    </div>
                                                </a>';
                                } else { 
                                    $output .= '<div class="pwe-media-gallery-image">
                                                    <img src="' . $image_url . '" style="' . $aspect_ratio_style . '">
                                                </div>';
                                }
                            }
                            
                        $output .= '</div>';

                        if ($media_gallery_button && ($carousel_thumbnails_desktop == 1 && $carousel_thumbnails_tablet == 1 && $carousel_thumbnails_mobile == 1)) {
                            $output .=  '<a href="'. self::languageChecker('/galeria/', '/en/gallery/') .'" class="media-gallery-btn">'. self::languageChecker('Przejdź do galerii', 'Go to gallery') .'</a>';
                        }

                        $output .= '
                        <span class="pwe-opinions__arrow pwe-opinions__arrow-prev pwe-arrow pwe-arrow-prev">‹</span>
                        <span class="pwe-opinions__arrow pwe-opinions__arrow-next pwe-arrow pwe-arrow-next">›</span>';  

                        include_once plugin_dir_path(dirname(__DIR__)) . 'scripts/slider.php';
                        $output .= PWESliderScripts::sliderScripts('media-gallery', '#'. $media_gallery_id, $media_gallery_dots_display, $media_gallery_arrows_display, $carousel_thumbnails_desktop, $media_gallery_options);
                    } else if (in_array('slider', $layouts)) { // Slider <--------------------------------------<
                        $output .= '<div class="pwe-gallery-container pwe-media-gallery-slider" style="margin: 0 auto; max-width: 1000px;">
                    
                            <link href="/wp-content/plugins/PWElements/includes/media-gallery/assets/fotorama/fotorama.css" rel="stylesheet">

                            <div class="pwe-media-gallery-slider-wrapper" >
                                <div 
                                    id="galleryContainer"
                                    class="fotorama" 
                                    data-allowfullscreen="native" 
                                    data-nav="thumbs" 
                                    data-navposition="middle"
                                    data-thumbwidth="144"
                                    data-thumbheight="96"
                                    data-transition="crossfade" 
                                    data-loop="true" 
                                    data-autoplay="true" 
                                    data-arrows="true" 
                                    data-click="true"
                                    data-swipe="false">';

                                    foreach ($media_gallery_array as $image_url) {
                                        $output .= '<img data-no-lazy="1" src="' . $image_url . '" alt="galery image">';
                                    }
                                
                                    $output .= '
                                </div>
                            </div>

                            <script src="/wp-content/plugins/PWElements/includes/media-gallery/assets/fotorama/fotorama.js"></script>
                        
                        </div>';  
                    } else if (in_array('coverflow', $layouts)) { // Coverflow <--------------------------------------<
                        // Podstawowe style coverflow
                        $output .= '
                        <div class="pwe-gallery-container pwe-media-gallery">
                            <link href="/wp-content/plugins/PWElements/includes/media-gallery/assets/coverflow-gallery/coverflow-gallery.css" rel="stylesheet">';

                        // Markup Swipera
                        $output .= '
                            <div class="swiper coverflow-gallery">
                                <div class="swiper-wrapper">';

                                    foreach ($media_gallery_array as $image_url) {
                                        $path_parts = pathinfo($image_url);
                                        $image_filename = $path_parts['basename'];

                                        $output .= '<div class="swiper-slide">';
                                            if ($media_gallery_clicked === 'linked' && isset($media_gallery_links_map[$image_filename])) {
                                                $output .= '<a href="'. esc_url($media_gallery_links_map[$image_filename]) .'">
                                                                <div class="pwe-media-gallery-image">
                                                                    <img src="' . esc_url($image_url) . '" style="' . esc_attr($aspect_ratio_style) . '">
                                                                </div>
                                                            </a>';
                                            } else { 
                                                $output .= '<div class="pwe-media-gallery-image">
                                                                <img src="' . esc_url($image_url) . '" style="' . esc_attr($aspect_ratio_style) . '">
                                                            </div>';
                                            }
                                        $output .= '</div>';
                                    }

                        // Paginacja — zawsze wstawiamy element, ale kontrolujemy widoczność CSS/JS niżej
                        $output .= '
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>';

                        // Na desktopie ukryj kropki, jeśli „Turn on dots” jest OFF; na mobile (<570px) pokaż zawsze
                        if ($media_gallery_dots_display !== 'true') {
                            $output .= '
                            <style>
                                @media (min-width: 570px) {
                                    #'. $media_gallery_id .' .swiper-pagination { display: none !important; }
                                    #'. $media_gallery_id .' .coverflow-gallery { padding: 0 !important; }
                                }
                                @media (max-width: 569px) {
                                    #'. $media_gallery_id .' .coverflow-gallery { padding: 24px !important; }
                                }
                            </style>';
                        } else {
                            $output .= '<style>
                                #'. $media_gallery_id .' .coverflow-gallery { padding-bottom: 36px; }
                            </style>';
                        }

                        // Inicjalizacja przez wspólny helper Swipera
                        include_once plugin_dir_path(dirname(__DIR__)) . 'scripts/swiper.php';

                        // OPCJE bazowe (desktop) – coverflow
                        $swiper_options = [
                            // globalnie coverflow, a w breakpointach zmienimy na „slide” dla mobile
                            'effect' => 'coverflow',
                            'grabCursor' => true,
                            'loopAdditionalSlides' => 5,
                            'speed' => 900,
                            'autoplay' => [
                                'delay' => 3000,
                                'disableOnInteraction' => false,
                                // pauseOnMouseEnter domyślnie true w helperze — nadpisujemy żeby zachować Twoje zachowanie
                                'pauseOnMouseEnter' => false,
                            ],
                            'coverflowEffect' => [
                                'rotate' => (float) $coverflow_rotate,
                                'stretch' => (float) $coverflow_stretch,
                                'scale' => (float) $coverflow_scale,
                                'depth' => (float) $coverflow_depth,
                                'modifier' => (float) $coverflow_modifier,
                                'slideShadows' => ($coverflow_shadows === 'true'),
                            ],
                            // Dodatkowo możesz włączyć klikanie w slajd jak w innych miejscach
                            'slideToClickedSlide' => true,
                            'watchSlidesProgress' => true,
                        ];

                        // BREAKPOINTY – mobile: zwykły „slide”, 2 karty, ujemny odstęp jak u Ciebie
                        $breakpoints = [
                            [ 'breakpoint_width' => 0,    'effect' => 'coverflow',    'centeredSlides' => true, 'slidesPerView' => 1, 'spaceBetween' => -160 ],
                            [ 'breakpoint_width' => 570,  'effect' => 'coverflow','centeredSlides' => true,  'slidesPerView' => 3, 'spaceBetween' => (int)$media_gallery_gap ],
                        ];

                        $output .= PWESwiperScripts::swiperScripts('media-gallery-coverflow', '#'. $media_gallery_id, 'true', $media_gallery_arrows_display ?: '', '', $swiper_options, rawurlencode(json_encode($breakpoints)));
                    }  else if (in_array('carousel-dual-swiper', $layouts)) { // Carousel Swiper <--------------------------------------<
                        $midpoint = ceil(count($media_gallery_array) / 2);
                        $top_slides = array_slice($media_gallery_array, 0, $midpoint);
                        $bottom_slides = array_slice($media_gallery_array, $midpoint);

                        $output .= '
                        <style>
                            #'. $media_gallery_id .' #dual-slider {
                                display: flex;
                                flex-direction: column;
                                gap: ' . $media_gallery_gap . 'px;
                                padding: 24px;
                                overflow: hidden;
                            }
                            #'. $media_gallery_id .' #dual-slider .swiper {
                                overflow: visible;
                            }
                            #'. $media_gallery_id .' #dual-slider .top-slider {
                                transform: translateX(-10%);
                            }
                            #'. $media_gallery_id .' #dual-slider .bottom-slider {
                                transform: translateX(-25%);
                            }
                            #'. $media_gallery_id .' #dual-slider .swiper-wrapper {
                                height: 280px;
                            }
                            #'. $media_gallery_id .' #dual-slider .swiper-slide {
                                overflow: hidden;
                                border-radius: 24px;
                            }
                            #'. $media_gallery_id .' #dual-slider .top-slider .swiper-slide {
                                padding-top: 36px;
                                transition: 0.3s;
                            }
                            #'. $media_gallery_id .' #dual-slider .top-slider .swiper-slide:hover {
                                padding-top: 0;
                            }
                            #'. $media_gallery_id .' #dual-slider .bottom-slider .swiper-slide {
                                padding-bottom: 36px;
                                transition: 0.3s;
                            }
                            #'. $media_gallery_id .' #dual-slider .bottom-slider .swiper-slide:hover {
                                padding-bottom: 0;
                            }
                            #'. $media_gallery_id .' #dual-slider .swiper-slide:before {
                                content: "";
                                width: 100%;
                                height: 100%;
                                position: absolute;
                                top: 0;
                                left: 0;
                                transition: 0.3s;
                                opacity: 0;
                            }
                            #'. $media_gallery_id .' #dual-slider .top-slider .swiper-slide:before {
                                background: linear-gradient(to bottom, black, transparent 50%);
                            }
                            #'. $media_gallery_id .' #dual-slider .bottom-slider .swiper-slide:before {
                                background: linear-gradient(to top, black, transparent 50%);
                            }
                            #'. $media_gallery_id .' #dual-slider .swiper-slide:hover:before {
                                opacity: 1;
                            }
                            #'. $media_gallery_id .' #dual-slider .swiper .swiper-slide {
                                position: relative;
                                height: 100%;
                                display: flex;
                            }
                            #'. $media_gallery_id .' #dual-slider .slide-title {
                                position: absolute;
                                left: 0;
                                padding: 18px;
                                font-size: 20px;
                                font-weight: 600;
                                color: white;
                                opacity: 0;
                                transition: 0.3s;
                            }
                            #'. $media_gallery_id .' #dual-slider .top-slider .slide-title { top: 0; }
                            #'. $media_gallery_id .' #dual-slider .bottom-slider .slide-title { bottom: 0; }
                            #'. $media_gallery_id .' #dual-slider .swiper-slide:hover .slide-title {
                                opacity: 1;
                            }
                            #'. $media_gallery_id .' #dual-slider .swiper-slide img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                                border-radius: 24px;
                                transition: 0.3s;
                            }
                            @media(max-width: 480px) {
                                #'. $media_gallery_id .' #dual-slider .top-slider,
                                #'. $media_gallery_id .' #dual-slider .bottom-slider {
                                    transform: translateX(0%);
                                }
                                #'. $media_gallery_id .' #dual-slider .swiper-wrapper {
                                    height: 260px;
                                }
                                 #'. $media_gallery_id .' #dual-slider .swiper-slide .slide-title {
                                    opacity: 1;
                                }
                                #'. $media_gallery_id .' #dual-slider .top-slider .swiper-slide {
                                    padding-top: 0;
                                }
                                #'. $media_gallery_id .' #dual-slider .bottom-slider .swiper-slide {
                                    padding-bottom: 0;
                                }
                                #'. $media_gallery_id .' #dual-slider .swiper-slide:before {
                                    opacity: 1;
                                }
                            }
                        </style>';

                        $output .= '<div id="dual-slider">';

                        // TOP SLIDER
                        $output .= '<div class="slider-wrapper top-slider"><div class="swiper topSwiper-' . esc_attr($media_gallery_id) . '"><div class="swiper-wrapper">';
                        foreach ($top_slides as $image_url) {
                            $path_parts = pathinfo($image_url);
                            $image_filename = $path_parts['basename'];
                            $title = basename($image_filename, '.' . $path_parts['extension']);

                            $output .= '<div class="swiper-slide">';
                            if ($media_gallery_clicked === 'linked' && isset($media_gallery_links_map[$image_filename])) {
                                $output .= '<a href="' . esc_url($media_gallery_links_map[$image_filename]) . '">';
                            }

                            $output .= '<img src="' . esc_url($image_url) . '" alt=""><span class="slide-title">' . esc_html($title) . '</span>';

                            if ($media_gallery_clicked === 'linked' && isset($media_gallery_links_map[$image_filename])) {
                                $output .= '</a>';
                            }

                            $output .= '</div>';
                        }
                        $output .= '</div></div></div>';

                        // BOTTOM SLIDER
                        $output .= '<div class="slider-wrapper bottom-slider"><div class="swiper bottomSwiper-' . esc_attr($media_gallery_id) . '"><div class="swiper-wrapper">';
                        foreach ($bottom_slides as $image_url) {
                            $path_parts = pathinfo($image_url);
                            $image_filename = $path_parts['basename'];
                            $title = basename($image_filename, '.' . $path_parts['extension']);

                            $output .= '<div class="swiper-slide">';
                            if ($media_gallery_clicked === 'linked' && isset($media_gallery_links_map[$image_filename])) {
                                $output .= '<a href="' . esc_url($media_gallery_links_map[$image_filename]) . '">';
                            }

                            $output .= '<img src="' . esc_url($image_url) . '" alt=""><span class="slide-title">' . esc_html($title) . '</span>';

                            if ($media_gallery_clicked === 'linked' && isset($media_gallery_links_map[$image_filename])) {
                                $output .= '</a>';
                            }

                            $output .= '</div>';
                        }
                        $output .= '</div></div></div>';

                        $output .= '</div>';

                        // Swiper init
                        $output .= '<script>
                            document.addEventListener("DOMContentLoaded", function () {
                                function duplicateSlidesIfNeeded(wrapperSelector, slidesPerView, slidesPerGroup) {
                                    const wrapper = document.querySelector(wrapperSelector);
                                    if (!wrapper) return;

                                    const slides = wrapper.children;
                                    const totalSlides = slides.length;
                                    const minSlides = slidesPerView + slidesPerGroup;
                                    let slidesToAdd = 0;

                                    if (totalSlides < minSlides || totalSlides % slidesPerGroup !== 0) {
                                        slidesToAdd = minSlides - totalSlides;
                                        if (totalSlides % slidesPerGroup !== 0) {
                                            slidesToAdd += slidesPerGroup - (totalSlides % slidesPerGroup);
                                        }

                                        const slidesHTML = Array.from(slides).map(slide => slide.outerHTML).join("");
                                        const tempDiv = document.createElement("div");
                                        tempDiv.innerHTML = slidesHTML;

                                        for (let i = 0; i < slidesToAdd; i++) {
                                            const clone = tempDiv.cloneNode(true);
                                            while (clone.firstChild) {
                                                wrapper.appendChild(clone.firstChild);
                                            }
                                        }
                                    }
                                }

                                // Duplikujemy slajdy PRZED tworzeniem Swipera
                                duplicateSlidesIfNeeded(".topSwiper-' . esc_attr($media_gallery_id) . ' .swiper-wrapper", 3.5, 3);
                                duplicateSlidesIfNeeded(".bottomSwiper-' . esc_attr($media_gallery_id) . ' .swiper-wrapper", 3.5, 3);

                                const commonSettings = {
                                    loop: true,
                                    spaceBetween: ' . $media_gallery_gap . ',
                                    speed: 900,
                                    autoplay: {
                                        delay: 3000,
                                        disableOnInteraction: false
                                    },
                                    breakpoints: {
                                        1024: { slidesPerView: 3.5 },
                                        768: { slidesPerView: 2 },
                                        480: { slidesPerView: 1 }
                                    }
                                };

                                const topSwiper = new Swiper(".topSwiper-' . esc_attr($media_gallery_id) . '", commonSettings);
                                const bottomSwiper = new Swiper(".bottomSwiper-' . esc_attr($media_gallery_id) . '", {
                                    ...commonSettings,
                                    autoplay: {
                                        delay: 3000,
                                        reverseDirection: true,
                                        disableOnInteraction: false
                                    }
                                });

                                const slidersContainer = document.getElementById("dual-slider");
                                slidersContainer.addEventListener("mouseenter", () => {
                                    topSwiper.autoplay.stop();
                                    bottomSwiper.autoplay.stop();
                                });
                                slidersContainer.addEventListener("mouseleave", () => {
                                    topSwiper.autoplay.start();
                                    bottomSwiper.autoplay.start();
                                });
                            });
                            </script>';

                    }


                    $output .= '  
                </div>
            </div>';

            if ($media_gallery_clicked !== 'linked' && $media_gallery_clicked !== 'nothing') {
                $output .= '
                    <script>
                    {
                        let enableScrolling = true;
                        window.isDragging = false;

                        const imagesArray = Array.from(document.querySelectorAll("#'. $media_gallery_id .' .pwe-media-gallery-image img"));
                
                        imagesArray.forEach((image, index) => {
                            image.addEventListener("click", (e) => {

                                if (window.isDraggingMedia) {
                                    e.preventDefault(); // Block the opening of the modal if there was movement
                                    window.isDraggingMedia = false; // Reset the flag after the click is handled
                                    return;
                                }

                                // Create popup
                                const popupDiv = document.createElement("div");
                                popupDiv.className = "pwe-media-gallery-popup";

                                // Left arrow for previous image
                                const leftArrow = document.createElement("span");
                                leftArrow.innerHTML = "&#10094;"; // HTML entity for left arrow
                                leftArrow.className = "pwe-media-gallery-left-arrow pwe-media-gallery-arrow";
                                popupDiv.appendChild(leftArrow);

                                // Right arrow for next image
                                const rightArrow = document.createElement("span");
                                rightArrow.innerHTML = "&#10095;"; // HTML entity for right arrow
                                rightArrow.className = "pwe-media-gallery-right-arrow pwe-media-gallery-arrow";
                                popupDiv.appendChild(rightArrow);
                        
                                // Close btn
                                const closeSpan = document.createElement("span");
                                closeSpan.innerHTML = "&times;";
                                closeSpan.className = "pwe-media-gallery-close";
                                popupDiv.appendChild(closeSpan);
                        
                                const popupImage = document.createElement("img");
                                popupImage.src = image.getAttribute("src");
                                popupImage.alt = "Popup Image";
                                popupDiv.appendChild(popupImage);
                        
                                // Add popup to <body>
                                document.body.appendChild(popupDiv);
                                popupDiv.style.display = "flex";

                                disableScroll();
                                enableScrolling = false;

                                // Function to change image in popup
                                let currentIndex = index; // Przechowuj bieżący indeks jako zmienną zewnętrzną

                                const changeImage = (direction) => {
                                    // Zastosowanie klasy fade-out przed zmianą źródła obrazu
                                    popupImage.classList.add("fade-out");
                                    popupImage.classList.remove("fade-in");

                                    setTimeout(() => {
                                        currentIndex += direction;

                                        if (currentIndex >= imagesArray.length) {
                                            currentIndex = 0; // Wraca do pierwszego obrazka
                                        } else if (currentIndex < 0) {
                                            currentIndex = imagesArray.length - 1; // Przechodzi do ostatniego obrazka
                                        }

                                        popupImage.src = imagesArray[currentIndex].getAttribute("src");

                                        // Usunięcie klasy fade-out i dodanie fade-in po zmianie źródła obrazu
                                        popupImage.classList.remove("fade-out");
                                        popupImage.classList.add("fade-in");
                                    }, 100);
                                };

                                leftArrow.addEventListener("click", () => changeImage(-1));
                                rightArrow.addEventListener("click", () => changeImage(1));

                                // Remove popup when clicking the close button
                                closeSpan.addEventListener("click", () => {
                                    popupDiv.remove();
                                    enableScroll();
                                    enableScrolling = true;
                                });

                                // Remove popup when clicking outside the image
                                popupDiv.addEventListener("click", (event) => {
                                    if (event.target === popupDiv) { // Checks if the clicked element is the popupDiv itself
                                        popupDiv.remove();
                                        enableScroll();
                                        enableScrolling = true;
                                    }
                                });
                            });
                        });

                        // Prevent scrolling on touchmove when enableScrolling is false
                        document.body.addEventListener("touchmove", (event) => {
                            if (!enableScrolling) {
                                event.preventDefault();
                            }
                        }, { passive: false });

                        // Disable page scrolling
                        function disableScroll() {
                            document.body.style.overflow = "hidden";
                            document.documentElement.style.overflow = "hidden";
                        }

                        // Enable page scrolling
                        function enableScroll() {
                            document.body.style.overflow = "";
                            document.documentElement.style.overflow = "";
                        }
                }
                </script>';
            }

            $output .= '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let pweMediaGallery = document.querySelectorAll(".pwe-container-media-gallery");
                    pweMediaGallery.forEach((element) => {
                        if (element) {
                            element.style.opacity = 1;
                            element.style.transition = "opacity 0.3s ease";
                        }
                    });
                });
            </script>';

        }

        $output .= ' 
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let pweElement = document.querySelector(".pwelement_'. self::$rnd_id .'");
                let pweElementRow = document.querySelector(".row-container:has(.pwelement_'. self::$rnd_id .')");
                let pweMediaGalleryContainer = pweElement.querySelector(".pwe-container-media-gallery") !== null;

                if (pweMediaGalleryContainer == false) {
                    pweElementRow.classList.add("desktop-hidden", "tablet-hidden", "mobile-hidden");
                }
            });
        </script>';
        
        if (in_array('flex', $layouts)) {
            $output .= '
            <script>
                jQuery(document).ready(function($){
                    $(".pwe-gallery-container").justifiedGallery ({
                        rowHeight : '. $media_gallery_thumbnails_rows_height .',
                        lastRow : "'. $media_gallery_justify_last_row .'",
                        margins : '. $media_gallery_margin .',
                    });
                });
            </script>';
        }
    
        $output = do_shortcode($output);
        
        $file_cont = '<div class="pwelement pwelement_'. self::$rnd_id .'">' . $output . '</div>';
    
        return $file_cont;

    }
    
}