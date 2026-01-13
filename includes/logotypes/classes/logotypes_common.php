<?php

class PWElementAdditionalLogotypes {

    public static function additionalArray() {
        return array(
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Layout', 'pwe_logotypes'),
                'param_name' => 'logotypes_layout',
                'save_always' => true,
                'value' => array(
                    'Flex' => 'flex',
                    'Grid' => 'grid',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Aditional options',
                'heading' => __('Text-align title', 'pwe_logotypes'),
                'param_name' => 'logotypes_position_title',
                'description' => __('Default left, for header dafault center', 'pwe_logotypes'),
                'save_always' => true,
            ),
            array(
                'type' => 'textfield',
                'group' => 'Aditional options',
                'heading' => __('Min width logotypes (flex only)', 'pwe_logotypes'),
                'param_name' => 'logotypes_min_width_logo',
                'description' => __('Default min width for flex 140px', 'pwe_logotypes'),
                'save_always' => true,
            ),
            array(
                'type' => 'textfield',
                'group' => 'Aditional options',
                'heading' => __('Number of logotypes in slider', 'pwe_logotypes'),
                'param_name' => 'slides_to_show',
                'description' => __('Default 7', 'pwe_logotypes'),
                'save_always' => true,
                'param_holder_class' => 'backend-area-one-fourth-width',
            ),
            array(
                'type' => 'textfield',
                'group' => 'Aditional options',
                'heading' => __('Number of logotypes in slider breakpoint 960', 'pwe_logotypes'),
                'param_name' => 'slides_to_show_960',
                'description' => __('Default 5', 'pwe_logotypes'),
                'save_always' => true,
                'param_holder_class' => 'backend-area-one-fourth-width',
            ),
            array(
                'type' => 'textfield',
                'group' => 'Aditional options',
                'heading' => __('Number of logotypes in slider breakpoint 600', 'pwe_logotypes'),
                'param_name' => 'slides_to_show_600',
                'description' => __('Default 3', 'pwe_logotypes'),
                'save_always' => true,
                'param_holder_class' => 'backend-area-one-fourth-width',
            ),
            array(
                'type' => 'textfield',
                'group' => 'Aditional options',
                'heading' => __('Number of logotypes in slider breakpoint 400', 'pwe_logotypes'),
                'param_name' => 'slides_to_show_400',
                'description' => __('Default 2', 'pwe_logotypes'),
                'save_always' => true,
                'param_holder_class' => 'backend-area-one-fourth-width',
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Items shadow', 'pwe_logotypes'),
                'param_name' => 'logotypes_items_shadow',
                'description' => __('2px 2px 12px #cccccc', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Aditional options',
                'heading' => __('Custom css of items', 'pwe_logotypes'),
                'param_name' => 'logotypes_items_custom_style',
                'save_always' => true,
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Turn on full width', 'pwe_logotypes'),
                'param_name' => 'logotypes_slider_full_width',
                'description' => __('Turn on full width', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Slider desktop', 'pwe_logotypes'),
                'param_name' => 'logotypes_slider_desktop',
                'description' => __('Check if you want to display in slider on desktop.', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('3 lines', 'pwe_logotypes'),
                'param_name' => 'logotypes_slider_3_row',
                'description' => __('Check if you want to display 3 row slider.', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Grid mobile', 'pwe_logotypes'),
                'param_name' => 'logotypes_grid_mobile',
                'description' => __('Check if you want to display in grid on mobile.', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Turn off dots', 'pwe_logotypes'),
                'param_name' => 'logotypes_dots_off',
                'description' => __('Check if you want to turn on dots.', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Randomise logotypes', 'pwe_logotypes'),
                'param_name' => 'logotypes_display_random1',
                'description' => __('Check if you want to display logotypes random.', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Randomise logotypes (top 20)', 'pwe_logotypes'),
                'param_name' => 'logotypes_display_random_top_20',
                'description' => __('Check if you want to display logotypes random (top 20 only).', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Aditional options',
                'heading' => __('Arrows', 'pwe_logotypes'),
                'param_name' => 'logotypes_display_arrows',
                'description' => __('Check if you want to display arrows.', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'param_group',
                'group' => 'Links',
                'heading' => __('Add link', 'pwe_logotypes'),
                'param_name' => 'logotypes_files',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Filename(ex. file.png)', 'pwe_logotypes'),
                        'param_name' => 'logotype_filename',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Link', 'pwe_logotypes'),
                        'param_name' => 'logotype_link',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Logo color', 'pwe_logotypes'),
                        'param_name' => 'logotype_color',
                        'save_always' => true,
                        'admin_label' => true,
                        'param_holder_class' => 'dropdown-checkbox',
                        'value' => array(
                            'No' => '',
                            'Yes' => 'true'
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Custom style', 'pwe_logotypes'),
                        'param_name' => 'logotype_style',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                ),
            ),
        );
    }

    public static function getLangDesc($meta) {
        if (empty($meta) || !is_array($meta)) {
            return "";
        }

        $locale = get_locale();
        $lang   = substr($locale, 0, 2);

        $descs = array_filter($meta, function($key){
            return strpos($key, 'desc_') === 0;
        }, ARRAY_FILTER_USE_KEY);

        if (empty($descs)) {
            return "";
        }

        $currentKey = "desc_" . $lang;

        if (!empty($descs[$currentKey])) {
            return $descs[$currentKey];
        }

        if ($lang === "pl") {
            if (!empty($descs["desc_en"])) return $descs["desc_en"];
        } else {
            if (!empty($descs["desc_en"])) return $descs["desc_en"];
            if (!empty($descs["desc_pl"])) return $descs["desc_pl"];
        }

        return reset($descs);
    }

    public static function getLangLink($data) {
        $locale = get_locale();
        $lang = substr($locale, 0, 2);

        $pl = $data["logos_link"] ?? '';
        $en = $data["logos_link_en"] ?? '';

        if (!empty($pl) && empty($en)) {
            return $pl;
        }

        if (!empty($pl) && !empty($en)) {

            if ($lang === 'pl') {
                return $pl;
            }

            return $en;
        }

        if (empty($pl) && !empty($en)) {
            return $en;
        }

        return '';
    }

    public static function multi_translation($key, $plural = false)
    {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../../../translations/includes/logotypes_common.json';

        $translations_data = json_decode(file_get_contents($translations_file), true);

        $translations_map = $translations_data[$locale] ?? $translations_data['en_US'];


        if ($plural === true) {
            if (isset($translations_map['plurals'][$key])) {
                return $translations_map['plurals'][$key];
            }

            return $key;
        }

        return $translations_map[$key] ?? $key;
    }


    public static function additionalOutput($atts, $el_id, $logotypes = null, $exhibitors_logotypes = null) {

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        extract( shortcode_atts( array(
            'logotypes_media' => '',
            'logotypes_catalog' => '',
            'logotypes_layout' => '',
            'logotypes_title' => '',
            'logotypes_name' => '',
            'logotypes_exhibitors_on' => '',
            'logotypes_caption_on' => '',
            'logotypes_position_title' => '',
            'logotypes_min_width_logo' => '',
            'logotypes_slider_full_width' => '',
            'logotypes_slider_desktop' => '',
            'logotypes_grid_mobile' => '',
            'logotypes_dots_off' => '',
            'logotypes_display_random1' => '',
            'logotypes_display_random_top_20' => '',
            'logotypes_slider_logo_white' => '',
            'logotypes_slider_logo_color' => '',
            'logotypes_items_shadow' => '',
            'logotypes_items_custom_style' => '',
            'logotypes_files' => '',
            'pwe_header_logotypes' => '',
            'conf_logotypes_catalog' => '',
            'slides_to_show' => '',
            'slides_to_show_960' => '',
            'slides_to_show_600' => '',
            'slides_to_show_400' => '',
            'logotypes_slider_3_row' => '',
            'logotypes_display_arrows' => '',
        ), $atts ));

        if ($logotypes_position_title == ''){
            $logotypes_position_title = 'left';
        }

        $output = '';

        if ($logotypes_media != '' || $logotypes_catalog != '' || !empty($pwe_header_logotypes) || !empty($conf_logotypes_catalog)){

            $logotypes_default_width = $mobile == 1 ? '80px' : '140px';
            $logotypes_min_width_logo = !empty($logotypes_min_width_logo) ? $logotypes_min_width_logo : $logotypes_default_width;
            $slides_to_show = !empty($slides_to_show) ? $slides_to_show : 7;
            $slides_to_show_960 = !empty($slides_to_show_960) ? $slides_to_show_960 : 5;
            $slides_to_show_600 = !empty($slides_to_show_600) ? $slides_to_show_600 : 3;
            $slides_to_show_400 = !empty($slides_to_show_400) ? $slides_to_show_400 : 2;
            $logotypes_items_shadow = $logotypes_items_shadow == true ? '2px 2px 12px #cccccc' : 'none';
            $slider_class = ($mobile != 1 && ($logotypes_slider_desktop == true) || $mobile == 1 && ($logotypes_grid_mobile != true || (!empty($header_logotypes_media_urls) && $header_logotypes_slider_off != true))) ? 'pwe-slides' : '';

            $output .= '
            <style>';
            if ($mobile != 1 && ($logotypes_slider_desktop != true) || $mobile == 1 && ($logotypes_grid_mobile == true) && $logotypes_layout !== "grid"){
                $output .= '
                .pwelement_'. $el_id .'.pwe_logotypes .pwe-logo-item-container {
                    '. ($logotypes_items_shadow !== 'none' ? 'min-width: 200px;' : '') .'
                }
                @media(max-width:920px){
                    .pwelement_'. $el_id .' .pwe-logo-item-container {
                        '. ($logotypes_items_shadow !== 'none' ? 'min-width: 135px;' : '') .'
                    }
                }';
            }
            $output .= '
            </style>';

            $output .= '
            <style>
                .pwelement_'. $el_id .' .pwe-container-logotypes-gallery {
                    z-index: 1;
                }
                .pwelement_'. $el_id .'.pwe_logotypes .pwe-logo-item-container {
                    box-shadow: '. $logotypes_items_shadow .';
                    '. ($logotypes_items_shadow !== 'none' ? 'background-color: white;' : '') .'
                    border-radius: 10px;
                    overflow: hidden;
                    '. ($logotypes_items_shadow !== 'none' ? 'padding: 10px;' : 'padding: 5px;') .'
                    background-color: white !important;
                }
                .pwelement_'. $el_id .' .pwe-header-logotypes .pwe-logo-item-container,
                .pwelement_'. $el_id .' .pwe-logo-item-container {
                    margin: 5px;
                }
                .pwelement_'. $el_id .' .pwe-logo-item {
                    max-width: '. $logotypes_min_width_logo .';
                    '. ($logotypes_items_shadow !== 'none' ? 'max-width: 100px;' : $logotypes_min_width_logo) .'
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    margin: 0 auto;
                }
                .pwelement_'. $el_id .' .pwe-logo-item p {
                    margin: 8px 0 0;
                    font-size: 14px;
                    font-weight: 500;
                }
                .pwelement_'. $el_id .' .slick-slide .pwe-logo-item {
                    max-width: 100%;
                }
                .pwelement_'. $el_id .' .pwe-logo-item img {
                    object-fit: contain;
                    aspect-ratio: 3 / 2;
                }
                .pwelement_'. $el_id .' .pwe-logotypes-title {
                    display: flex;
                    justify-content: '. $logotypes_position_title .';
                }
                .pwe-logotypes-title h4 {
                    margin: 0;
                }
                .row-parent:has(.pwelement_'. $el_id .' .pwe-full-width)  {
                    max-width: 100% !important;
                }
                .pwelement_'. $el_id .' .pwe-white-logotypes img,
                .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-gallery-wrapper img {
                    filter: brightness(0) invert(1);
                    transition: all .3s ease;
                }
                .pwelement_'. $el_id .' .pwe-white-logotypes img:hover,
                .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-gallery-wrapper img:hover {
                    filter: none;
                }
                .pwelement_'. $el_id .' .pwe-logo-original img {
                    filter: none !important;
                }
                .pwelement_'. $el_id .' .pwe-color-logotypes .pwe-logo-item img {
                    filter: none !important;
                }
                .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-title {
                    justify-content: center;
                }

                .pwelement_'. $el_id .' .pwe-logo-item-container p {
                    text-transform: uppercase;
                    font-size: 12px;
                    font-weight: 700;
                    color: black;
                    white-space: break-spaces;
                    text-align: center;
                    line-height: 1.1 !important;
                    margin: 5px;
                }
            </style>';

            if ($slider_class !== 'pwe-slides') {

                if ($logotypes_layout == "" || $logotypes_layout == "flex") {
                    $output .= '
                    <style>
                    .pwelement_'. $el_id .' .pwe-logotypes-gallery-wrapper {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 10px;
                        margin-top: 18px;
                    }
                    .pwelement_'. $el_id .' .pwe-logo-item  {
                        min-height: 90px;
                    }
                    </style>';

                    if (empty($slider_class) && $logotypes_items_shadow !== 'none') {
                        $output .= '
                        <style>
                            @media(max-width:620px) {
                                .pwelement_'. $el_id .' .pwe-logo-item-container {
                                    max-width: 27% !important;
                                    min-width: 27% !important;
                                    padding: 5px !important;
                                }
                                .pwelement_'. $el_id .' .pwe-logo-item-container p {
                                    font-size: 9px !important;
                                    font-weight: 600 !important;
                                }
                            }
                            @media(max-width:350px) {
                                .pwelement_'. $el_id .' .pwe-logo-item-container {
                                    max-width: 25% !important;
                                    min-width: 25% !important;
                                }
                            }
                        </style>';
                    }

                } else {
                    $output .= '
                    <style>
                    .pwelement_'. $el_id .' .pwe-logotypes-gallery-wrapper {
                        display: grid;
                        grid-template-columns: repeat(6, 1fr);
                        gap: 16px;
                    }
                    @media(max-width: 960px) {
                        .pwelement_'. $el_id .' .pwe-logotypes-gallery-wrapper {
                            grid-template-columns: repeat(5, 1fr);
                            gap: 16px;
                        }
                        .pwelement_'. $el_id .' .pwe-logotypes-gallery-wrapper .pwe-logo-item {
                            margin: 0;
                            min-width: 100%;
                        }
                    }

                    @media(max-width: 550px) {
                        .pwelement_'. $el_id .' .pwe-logotypes-gallery-wrapper {
                            grid-template-columns: repeat(3, 1fr);
                            gap: 10px !important;
                        }
                        .pwelement_'. $el_id .' .pwe-logo-item-container {
                            padding: 5px !important;
                        }
                    }
                    @media(max-width: 450px) {
                        .pwelement_'. $el_id .' .pwe-logo-item-container p {
                            font-size: 10px;
                        }
                    }
                    @media(max-width: 380px) {
                        .pwelement_'. $el_id .' .pwe-logo-item-container p {
                            font-size: 8px;
                        }
                    }
                    </style>';
                }

            }

            $pwe_header_logotypes_urldecode = urldecode($pwe_header_logotypes);
            $pwe_header_logotypes_json = json_decode($pwe_header_logotypes_urldecode, true);
            $header_logotypes_media_urls = [];
            $header_logotypes_slider_off = '';

            if (is_array($pwe_header_logotypes_json)) {
                foreach ($pwe_header_logotypes_json as $logotypes_item) {
                    if (isset($logotypes)) {
                        $header_logotypes_url = $logotypes["logotypes_catalog"];
                        $header_logotypes_title = $logotypes["logotypes_title"];
                        $header_logotypes_name = $logotypes["logotypes_name"];
                        $header_logotypes_width = $logotypes["logotypes_width"];
                        $header_logotypes_media = $logotypes["logotypes_media"];
                        $header_logotypes_slider_off = $logotypes["logotypes_slider_off"];
                        $header_logotypes_caption_on = $logotypes["logotypes_caption_on"];
                        $header_logotypes_items_width = $logotypes["logotypes_items_width"];
                    } else {
                        $header_logotypes_url = $logotypes_item["logotypes_catalog"];
                        $header_logotypes_title = $logotypes_item["logotypes_title"];
                        $header_logotypes_name = $logotypes_item["logotypes_name"];
                        $header_logotypes_width = $logotypes_item["logotypes_width"];
                        $header_logotypes_media = $logotypes_item["logotypes_media"];
                        $header_logotypes_slider_off = $logotypes_item["logotypes_slider_off"];
                        $header_logotypes_caption_on = $logotypes_item["logotypes_caption_on"];
                        $header_logotypes_items_width = $logotypes_item["logotypes_items_width"];
                    }
                    $header_logotypes_media_ids = explode(',', $header_logotypes_media);
                }
            }

            if (isset($header_logotypes_media_ids)) {
                foreach ($header_logotypes_media_ids as $id) {
                    $url = wp_get_attachment_url($id);
                    if ($url) {
                        $header_logotypes_media_urls[] = $url;
                    }
                }
            }

            $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
            $domain = $_SERVER['HTTP_HOST'];
            $server_url = ($is_https ? 'https://' : 'http://') . $domain;

            $unique_id = rand(10000, 99999);
            $element_unique_id = 'pweLogotypes-' . $unique_id;

            if (!empty($conf_logotypes_catalog)) {
                $logotypes_catalog = $conf_logotypes_catalog;
            }

            if (!empty($pwe_header_logotypes)) {
                $logotypes_catalog = $header_logotypes_url;
                $logotypes_title = $header_logotypes_title;
                $logotypes_name = $header_logotypes_name;
            }

            $files = [];

            if ($logotypes_catalog == "partnerzy obiektu") {
                $files = glob($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/pwe-media/media/partnerzy-obiektu/*.{jpeg,jpg,png,webp,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);
            } else if ($logotypes_catalog == "wspierają nas") {
                $files = glob($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/pwe-media/media/wspieraja-nas/*.{jpeg,jpg,png,webp,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);

                $output .= '
                    <style>
                        .pwelement_'. $el_id .' .pwe-logotypes-gallery-wrapper {
                            max-width: 900px;
                            margin: 0 auto;
                        }
                    </style>';

            } else if (($logotypes_catalog == "" && $logotypes_media == "") && $header_logotypes_media_urls !== "") {
                $files = $header_logotypes_media_urls;
            } else {
                $logotypes_media = explode(',', $logotypes_media);

                // Add media URLs if they exist
                if (!empty($logotypes_media)) {
                    foreach ($logotypes_media as $image_id) {
                        $logotype_url = wp_get_attachment_url($image_id);
                        if (!empty($logotype_url)) {
                            $files[] = $logotype_url;
                        }
                    }
                }

                // Processing logotypes_catalog
                if (!empty($logotypes_catalog)) {
                    // Remove any excess commas
                    $logotypes_catalog = trim($logotypes_catalog, ',');

                    $logotypes_catalogs = explode(',', $logotypes_catalog);
                    // Remove whitespace for each element
                    $logotypes_catalogs = array_map('trim', $logotypes_catalogs);

                    // If "catalog" doesn't exist, add it at the beginning
                    if ($logotypes_exhibitors_on == true && !in_array('wystawcy', $logotypes_catalogs)) {
                        array_unshift($logotypes_catalogs, 'wystawcy');
                    }

                    $exhibitors_catalog = [];

                    if ($logotypes_exhibitors_on == true && !empty($exhibitors_logotypes)) {
                        foreach ($exhibitors_logotypes as $exhibitors_logotype) {
                            $exhibitors_catalog[] = $exhibitors_logotype['img'];
                        }

                         // Add exhibitors only if >= 10
                        if (count($exhibitors_catalog) >= 10 && !in_array('wystawcy', $logotypes_catalogs)) {
                            array_unshift($logotypes_catalogs, 'wystawcy');
                        }

                        if ($logotypes_display_random_top_20 == true) {
                            if (count($exhibitors_catalog) >= 20) {
                                // Split the array: first 20 elements + the rest
                                $first_20 = array_slice($exhibitors_catalog, 0, 20);
                                $remaining = array_slice($exhibitors_catalog, 20);

                                // Only shuffle the first 20 items
                                shuffle($first_20);

                                // Merge both parts back into one array
                                $exhibitors_catalog = array_merge($first_20, $remaining);
                            } else {
                                // If there are less than 20 elements, shuffle the whole thing
                                shuffle($exhibitors_catalog);
                            }
                        }
                    }


                    // Re-join directories into one string
                    $logotypes_catalog = implode(',', $logotypes_catalogs);

                    // Queue for processing directories
                    $directories_to_process = [];
                    foreach ($logotypes_catalogs as $catalog) {
                        $catalog = trim($catalog);

                        // Ignoring "/"
                        if ($catalog === '/') {
                            continue;
                        }

                        $full_path = $_SERVER['DOCUMENT_ROOT'] . '/doc/' . $catalog;

                        // Check if it's a file
                        if (is_file($full_path)) {
                            // Adding a direct file to the image list
                            $files[] = substr($full_path, strpos($full_path, '/doc/'));
                        } else {
                            // If it's a folder, add it to process
                            $directories_to_process[] = $full_path;
                        }
                    }

                    // Check if 'catalog' was added and if so, add $exhibitors_catalog to $files
                    if ($logotypes_exhibitors_on == true && in_array('wystawcy', $logotypes_catalogs) && count($exhibitors_catalog) >= 10) {
                        foreach ($exhibitors_catalog as $catalog_img) {
                            $files[] = $catalog_img;
                        }
                    }

                    while (!empty($directories_to_process)) {
                        $current_directory = array_shift($directories_to_process);

                        // Add all images from the current directory
                        $catalog_urls = glob($current_directory . '/*.{jpeg,jpg,png,webp,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);

                        foreach ($catalog_urls as $catalog_image_url) {
                            $files[] = substr($catalog_image_url, strpos($catalog_image_url, '/doc/'));
                        }

                        // Find subdirectories and add them to the queue
                        $sub_directories = glob($current_directory . '/*', GLOB_ONLYDIR);

                        // Sort subdirectories by creation date
                        usort($sub_directories, function($a, $b) {
                            return filemtime($a) - filemtime($b);
                        });

                        // Adding subdirectories to the queue
                        foreach ($sub_directories as $sub_directory) {
                            $directories_to_process[] = $sub_directory;
                        }
                    }
                }
            }

            if (count($files) > 0) {
                foreach ($files as &$file) {
                    $file = [
                        'url' => $file,
                        'desc_pl' => basename(dirname($file)),
                        'desc_en' => '',
                        'link' => ''
                    ];
                }
                unset($file);
            }


            $cap_logotypes_data = PWECommonFunctions::get_database_logotypes_data();

            if (!empty($cap_logotypes_data)) {

                $saving_paths = function (&$files, $logo_data) {

                    $meta = json_decode($logo_data->meta_data, true);
                    $data = json_decode($logo_data->data ?? '{}', true);

                    $currentLocale = get_locale();

                    $visibilityFlags = array_filter($data, function ($key) {
                        return preg_match('/^logos_[a-z]{2}_[A-Z]{2}$/', $key);
                    }, ARRAY_FILTER_USE_KEY);

                    if (empty($visibilityFlags)) {
                        $showLogo = true;
                    } else {
                        // If no flags: show
                        $allNull = true;
                        foreach ($visibilityFlags as $val) {
                            if (!is_null($val)) {
                                $allNull = false;
                                break;
                            }
                        }

                        if ($allNull) {
                            $showLogo = true;
                        } else {
                            $keyForCurrentLocale = 'logos_' . $currentLocale;

                            if (isset($visibilityFlags[$keyForCurrentLocale])) {
                                $showLogo = ($visibilityFlags[$keyForCurrentLocale] === 'true');
                            } else {
                                $showLogo = false;
                            }
                        }
                    }

                    if (!$showLogo) {
                        return;
                    }

                    $desc_pl = $meta["desc_pl"] ?? '';
                    $desc_en = $meta["desc_en"] ?? '';

                    $element = [
                        'url'  => 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url,
                        'desc_pl' => $desc_pl,
                        'desc_en' => $desc_en,
                        'link' => self::getLangLink($data, 'logos_link'),
                        'meta' => $meta
                    ];

                    if (!in_array($element, $files)) {
                        $files[] = $element;
                    }
                };

                if (strpos($logotypes_catalog, 'Rotator 2') !== false) {
                    $files = [];

                    $order = [
                        "partner-honorowy",
                        "partner-merytoryczny",
                        "partner-targow",
                        "patron-medialny",
                        "partner-branzowy",
                        "industry-media-partner",
                        "partner-strategiczny",
                        "principal-partner"
                    ];

                    // Group by logos_type
                    $grouped = [];
                    foreach ($cap_logotypes_data as $logo_data) {
                        if (in_array($logo_data->logos_type, $order)) {
                            $grouped[$logo_data->logos_type][] = $logo_data;
                        }
                    }

                    // Add in the specified order
                    foreach ($order as $type) {
                        if (!empty($grouped[$type])) {
                            foreach ($grouped[$type] as $logo_data) {
                                $saving_paths($files, $logo_data);
                            }
                        }
                    }
                }

                if (strpos($logotypes_catalog, 'Rotator Konferencji') !== false) {
                    $files = [];

                    foreach ($cap_logotypes_data as $logo_data) {
                        if ($logo_data->logos_type === "patron-medialny" ||
                            $logo_data->logos_type === "partner-merytoryczny") {
                            $saving_paths($files, $logo_data);
                        }
                    }
                }

                if (strpos($logotypes_catalog, 'international') !== false) {
                    $files = [];

                    foreach ($cap_logotypes_data as $logo_data) {
                        if ($logo_data->logos_type === "international-partner" ||
                            $logo_data->logos_type === "miedzynarodowy-patron-medialny") {
                            $saving_paths($files, $logo_data);
                        }
                    }
                }

                $logotypes_catalogs_array = explode(',', $logotypes_catalog);
                foreach ($cap_logotypes_data as $logo_data) {
                    if (in_array($logo_data->logos_type, $logotypes_catalogs_array)) {
                        $saving_paths($files, $logo_data);
                    }
                }

                // Check if 'catalog' was added and if so, add $exhibitors_catalog to $files
                if ($logotypes_exhibitors_on == true && in_array('wystawcy', $logotypes_catalogs) && count($logotypes_catalogs) > 1) {
                    foreach ($exhibitors_catalog as $catalog_img) {
                        $element = [
                            'url' => $catalog_img,
                            'desc_pl' => 'wystawca',
                            'desc_en' => 'exhibitor',
                            'link' => ''
                        ];

                        // Adding logos_url to $files only if it is not already there
                        if (!in_array($element, $files)) {
                            $files[] = $element;
                        }
                    }

                    shuffle($files);
                }
            }

            if (count($files) > 0) {

                // Sorting - moving files from “Partner Targów” to the end of the array
                // usort($files, function ($a, $b) {
                //     // Użycie tylko pierwszego elementu (URL) do porównania
                //     $a_url = $a['url'];
                //     $b_url = $b['url'];

                //     // Naprawienie podwójnych slashes
                //     $a_url = str_replace('//', '/', $a_url);
                //     $b_url = str_replace('//', '/', $b_url);

                //     // Sprawdzenie, czy to "Partner Targów"
                //     $a_is_partner = strpos($a_url, 'Logotypy/Rotator 2/Partner Targów') !== false;
                //     $b_is_partner = strpos($b_url, 'Logotypy/Rotator 2/Partner Targów') !== false;

                //     if ($a_is_partner === $b_is_partner) {
                //         return 0;
                //     }

                //     return $a_is_partner ? 1 : -1;
                // });

                $files = array_map(function($file) {
                    // Check if the path is local
                    if (strpos($file['url'], 'https://') !== 0 && strpos($file['url'], 'http://') !== 0) {
                        // Replace double slashes with one only if path is local
                        $file['url'] = preg_replace('/\/+/', '/', $file['url']);
                    }
                    return $file;
                }, $files);

                if($logotypes_display_random1) {
                    // Randomise files
                    shuffle($files);
                } else {
                    if (empty($cap_logotypes_data)) {
                        // Separation files
                        $links = [];
                        $localFiles = [];

                        // Keeping order
                        foreach ($files as $file) {
                            if (strpos($file['url'], 'https://www2.pwe-expoplanner.com') === 0) {
                                $links[] = $file;
                            } else {
                                $localFiles[] = $file;
                            }
                        }

                        // Sorting local files
                        usort($localFiles, function($a, $b) {
                            $fileA = basename($a[0]);
                            $fileB = basename($b[0]);

                            preg_match('/^(\d+)/', $fileA, $matchA);
                            preg_match('/^(\d+)/', $fileB, $matchB);

                            $hasNumA = !empty($matchA);
                            $hasNumB = !empty($matchB);

                            if ($hasNumA && !$hasNumB) {
                                return -1;
                            }
                            if (!$hasNumA && $hasNumB) {
                                return 1;
                            }

                            if ($hasNumA && $hasNumB) {
                                $numA = (int)$matchA[1];
                                $numB = (int)$matchB[1];

                                if ($numA === $numB) {
                                    return strcasecmp($fileA, $fileB);
                                }
                                return $numA <=> $numB;
                            }

                            return strcasecmp($fileA, $fileB);
                        });

                        // Merging
                        $files = array_merge($links, $localFiles);
                    }
                }

                // Calculate width for header logotypes
                if (isset($header_logotypes_width) && $header_logotypes_width . '%' !== '%') {
                    if ($header_logotypes_width >= 70 && $header_logotypes_width < 100) {
                        $header_logotypes_width  -= 3;
                    } else if ($header_logotypes_width >= 50 && $header_logotypes_width < 70) {
                        $header_logotypes_width  -= 2;
                    } else if ($header_logotypes_width >= 30 && $header_logotypes_width < 50) {
                        $header_logotypes_width  -= 1;
                    }
                    $output .= '<style>
                                    #'.$element_unique_id .' {
                                        width: '.$header_logotypes_width.'%;
                                    }
                                </style>';
                }

                if (empty($logotypes_title)) {
                    $output .= '<style>
                                    #'. $element_unique_id .' .pwe-logotypes-title {
                                        display: none !important;
                                    }
                                </style>';
                }

                $output .= '
                <div id="'. $element_unique_id .'" class="pwe-container-logotypes-gallery">
                    <div class="pwe-logotypes-title main-heading-text">';
                    $logotypes_title = str_replace(array('`{`', '`}`'), array('[', ']'), $logotypes_title);
                    if (do_shortcode("[trade_fair_group]") === "gr2") {
                        $logotypes_title = str_replace(
                            self::multi_translation("international_patrons"),
                            self::multi_translation("international_patrons"),
                            mb_strtolower($logotypes_title, "UTF-8")
                        );
                    }

                    $output .= '
                        <h4 class="pwe-uppercase"><span>'. $logotypes_title .'</span></h4>
                    </div>
                    <div class="'. $slider_class .' pwe-logotypes-gallery-wrapper'; $output .= ($logotypes_slider_logo_white == "true") ? ' pwe-white-logotypes' : '';
                        $output .= ($logotypes_slider_logo_color == "true") ? ' pwe-color-logotypes' : '';
                        $output .= (isset($logotypes_slider_full_width) && $logotypes_slider_full_width == "true") ? ' pwe-full-width' : '';
                        $output .= '">';

                    $images_url = array();
                    $updated_images_url = array();

                    // Decoding logo data from JSON format
                    $logotypes_files_urldecode = urldecode($logotypes_files);
                    $logotypes_files_json = json_decode($logotypes_files_urldecode, true);

                    // Search all files and generate their URL paths
                    foreach ($files as $index => $file) {
                        $url = $file['url']; // Używamy tylko pierwszego elementu
                        $desc_pl = $file['desc_pl'];
                        $desc_en = $file['desc_en'];
                        $link = $file['link'];
                        $short_path = '';

                        // Removing double "//"
                        $url = preg_replace('#(?<!https:)//+#', '/', $url);

                        // Deciding on the path structure depending on the directory
                        if ($logotypes_catalog == "partnerzy obiektu" || $logotypes_catalog == "wspierają nas") {
                            $short_path = substr($url, strpos($url, '/wp-content/'));
                        } else {
                            $short_path = substr($url, strpos($url, '/doc/'));
                        }

                        // Build the full path to the image
                        if ($header_logotypes_media_urls !== '') {
                            $full_path = $short_path;
                        } else {
                            $full_path = $server_url . $short_path;
                        }

                        // Dodajemy do $images_url tylko URL
                        $images_url[] = $full_path;

                        // Reset variable for each image
                        $site = '';
                        $class = '';
                        $style = '';
                        $target_blank = '';

                        // Search for the corresponding logo and set the properties
                        foreach ($logotypes_files_json as $logotype) {
                            if (!empty($logotype["logotype_filename"]) && strpos($full_path, $logotype["logotype_filename"]) !== false) {
                                $site = $logotype["logotype_link"];
                                $class = ($logotype["logotype_color"] === "true") ? 'pwe-logo-original' : '';
                                $style = ($logotype["logotype_style"] === "") ? '' : $logotype["logotype_style"];
                                $target_blank = (strpos($site, 'http') !== false) ? 'target="_blank"' : '';
                                break;
                            }
                        }

                        // Add the HTTPS protocol if it is not included in the link
                        if (!empty($site) && !preg_match("~^(?:f|ht)tps?://~i", $site) && (strpos($site, 'http') !== false)) {
                            $site = "https://" . $site;
                        }

                        // Używamy $desc_pl jako folder_name jeśli jest dostępne, inaczej wyciągamy z URL
                        $folder_name = PWECommonFunctions::lang_pl() ? (!empty($desc_pl) ? $desc_pl : basename(dirname($url))) : (!empty($desc_en) ? $desc_en : basename(dirname($url)));
                        $meta = $file['meta'] ?? [];

                        // Build the final data structure for the image
                        $updated_images_url[] = array(
                            "img"   => $full_path,
                            "site"  => (!empty($link) && $link != null) ? $link : $site,
                            "class" => $class,
                            "style" => $style,
                            "target_blank" => $target_blank,
                            "folder_name" => $folder_name,
                            "logotypes_name" => !empty($logotypes_name) ? $logotypes_name : ($logotypes_caption_on ? self::getLangDesc($meta) : "")
                        );
                    }

                    // echo '<pre>';
                    // var_dump($updated_images_url);
                    // echo '</pre>';

                    // List of translations
                    $translations = array(
                        "Partner Targów" => "Fair<br>Partner",
                        "Partner Targow" => "Fair<br>Partner",
                        "Partner Pokazów" => "Show<br>Partner",
                        "Partner Konferencji" => "Conference<br>Partner",
                        "Partner Merytoryczny" => "Content<br>Partner",
                        "Partner Strategiczny" => "Strategic<br>Partner",
                        "Partner Gold" => "Partner<br>Gold",
                        "Partner Platinum" => "Platinum<br>Partner",
                        "Partner Główny" => "Main<br>Partner",
                        "Partner Honorowy" => "Honorary<br>Partner",
                        "Targi Wspiera" => "Fair<br>Supports",
                        "Kraj Partnerski" => "Partner<br>Country",
                        "Oficjalny Partner Targów" => "Official Fair<br>Partner",
                        "Patron" => "Patron",
                        "Patron Medialny" => "Media<br>Patron",
                        "Patron Honorowy" => "Honorary<br>Patron",
                        "Patron Branżowy" => "Industry<br>Patron",
                        "Patron Medialny Branżowy" => "Industry Media<br>Patron",
                        "Patron Ambasady" => "Patronage<br>of the Embassy",
                        "Wystawca" => "Exhibitor"
                    );

                    $images_options = array();
                    $images_options[] = array(
                        "element_id" => $el_id,
                        "logotypes_caption_on" => $logotypes_caption_on,
                        "header_logotypes_caption_on" => isset($header_logotypes_caption_on) ? $header_logotypes_caption_on : '',
                        "logotypes_dots_off" => $logotypes_dots_off,
                        "caption_translations" => $translations
                    );

                    // Output logotypes
                    if (!$logotypes_slider_3_row) {
                        if (count($updated_images_url) > 0) {
                            foreach ($updated_images_url as $url) {
                                // Ustalanie tekstu alternatywnego (alt)
                                $alt_text = (!empty($url["folder_name"]) && !preg_match('/\d{2}/', $url["folder_name"])) ? $url["folder_name"] . pathinfo($url["img"])['filename'] : "gallery element";

                                // Ustalanie napisu (caption) dla logotypów
                                if (($logotypes_caption_on == true || (isset($header_logotypes_caption_on) && $header_logotypes_caption_on == true)) && empty($logotypes_name)) {

                                        if (strpos($url["img"], 'expoplanner.com') !== false) {
                                           $logo_caption_text = '<p>'. self::multi_translation("exhibitor").'</p>';
                                        } else {
                                            if (get_locale() == 'pl_PL') {
                                                $logo_caption_text = '<p>' . str_replace(' ', '<br>', $url["folder_name"]) . '</p>';
                                            } else {
                                                $logo_caption_text = array_key_exists($url["folder_name"], $translations)
                                                    ? '<p>' . $translations[$url["folder_name"]] . '</p>'
                                                    : '<p>' . $url["folder_name"] . '</p>';
                                            }
                                        }

                                } else {
                                    $logo_caption_text = '<p>' . $url["logotypes_name"] . '</p>';
                                }

                                $logo_caption_text = str_replace(self::multi_translation("international"), "",mb_strtolower($logo_caption_text, "UTF-8")
                                );

                                // Ustalanie szerokości elementów logotypów
                                $logotypes_items_width = isset($header_logotypes_items_width) && $header_logotypes_items_width != ''
                                    ? 'min-width:' . $header_logotypes_items_width . ';'
                                    : '';

                                // Ustalanie otwierania w nowej karcie
                                $target_blank = (strpos($url["site"], 'http') !== false) ? 'target="_blank"' : '';



                                // Renderowanie logotypów
                                if (!empty($url["img"])) {
                                    if (!empty($url["site"])) {
                                        $output .= '
                                        <a class="pwe-logo-item-container" ' . $target_blank . ' href="' . $url["site"] . '" style="' . $logotypes_items_custom_style . '">
                                            <div class="pwe-logo-item ' . $url["class"] . '" style="' . $url["style"] . ' ' . $logotypes_items_width . ' ' . $logotypes_items_custom_style . '">
                                                <img id="'. pathinfo($url["img"])['filename'] .'" alt="' . $alt_text . '" data-no-lazy="1" src="' . $url["img"] . '"/>
                                                ' . $logo_caption_text . '
                                            </div>
                                        </a>';
                                    } else {
                                        $output .= '
                                        <div class="pwe-logo-item-container" style="' . $logotypes_items_custom_style . '">
                                            <div class="pwe-logo-item ' . $url["class"] . '" style="' . $url["style"] . ' ' . $logotypes_items_width . '">
                                                <img id="'. pathinfo($url["img"])['filename'] .'" alt="' . $alt_text . '" data-no-lazy="1" src="' . $url["img"] . '"/>
                                                ' . $logo_caption_text . '
                                            </div>
                                        </div>';
                                    }
                                }
                            }
                        }
                    } else {
                        // Obsługa trybu slidera 3-rzędowego
                        if (count($updated_images_url) > 0) {
                            $logos_count = count($updated_images_url);
                            $rows = ($logos_count < 30) ? 2 : 3;
                            $logos_chunked = array_chunk($updated_images_url, ceil($logos_count / $rows));

                            $output .= '<div class="logotypes-slider-wrapper">';

                            foreach ($logos_chunked as $index => $logos_group) {
                                $slider_id = $element_unique_id . "-slider-" . ($index + 1);
                                $direction = ($index == 1) ? 'rtl' : 'ltr';

                                $output .= '<div class="'. $element_unique_id .'-logotypes-slider logotypes-slider" id="'. $slider_id .'" data-direction="'. $direction .'">';

                                foreach ($logos_group as $url) {
                                    // Ustalanie napisu (caption) dla logotypów
                                    if (($logotypes_caption_on == true || (isset($header_logotypes_caption_on) && $header_logotypes_caption_on == true)) && empty($logotypes_name)) {
                                        if (get_locale() == 'pl_PL') {
                                            $logo_caption_text = (strpos($url["img"], 'expoplanner.com') !== false)
                                                ? '<p>Wystawca</p>'
                                                : '<p>' . str_replace(' ', '<br>', $url["folder_name"]) . '</p>';
                                        } else {
                                            $logo_caption_text = array_key_exists($url["folder_name"], $translations)
                                                ? '<p>' . $translations[$url["folder_name"]] . '</p>'
                                                : '<p>' . $url["folder_name"] . '</p>';
                                        }
                                    } else {
                                        $logo_caption_text = '<p>' . $url["logotypes_name"] . '</p>';
                                    }

                                    // Ustalanie szerokości elementów logotypów
                                    $logotypes_items_width = isset($header_logotypes_items_width) && $header_logotypes_items_width != ''
                                        ? 'min-width:' . $header_logotypes_items_width . ';'
                                        : '';

                                    $target_blank = (strpos($url["site"], 'http') !== false) ? 'target="_blank"' : '';

                                    if (!empty($url["img"])) {
                                        if (!empty($url["site"])) {
                                            $output .= '
                                            <a class="pwe-logo-item-container" ' . $target_blank . ' href="' . $url["site"] . '" style="' . $logotypes_items_custom_style . '">
                                                <div class="pwe-logo-item ' . $url["class"] . '" style="' . $url["style"] . ' ' . $logotypes_items_width . ' ' . $logotypes_items_custom_style . '">
                                                    <img data-no-lazy="1" src="' . $url["img"] . '" />
                                                    ' . $logo_caption_text . '
                                                </div>
                                            </a>';
                                        } else {
                                            $output .= '
                                            <div class="pwe-logo-item-container" style="' . $logotypes_items_custom_style . '">
                                                <div class="pwe-logo-item ' . $url["class"] . '" style="' . $url["style"] . ' ' . $logotypes_items_width . '">
                                                    <img data-no-lazy="1" src="' . $url["img"] . '" />
                                                    ' . $logo_caption_text . '
                                                </div>
                                            </div>';
                                        }
                                    }
                                }
                                $output .= '</div>';
                            }
                            $output .= '</div>';
                        }

                        $output .= '
                        <style>
                            .'.$element_unique_id.'-logotypes-slider {
                                display: flex !important;
                                flex-wrap: nowrap;
                                justify-content: center;
                                align-items: center;
                                overflow: hidden;
                                width: 100%;
                                position: relative;
                                opacity: 0;
                                margin: 0 auto;
                                padding: 0;
                                overflow: hidden;
                            }
                            .'.$element_unique_id.'-logotypes-slider .slick-list {
                                margin-left: 9px !important;
                            }
                            .'.$element_unique_id.'-logotypes-slider .slick-slide {
                                display: flex !important;
                                justify-content: center;
                                align-items: center;
                                gap:10px;
                                '. ($logotypes_items_shadow !== 'none' ? 'min-width:auto !important;' : '') .'
                                '. ($logotypes_items_shadow !== 'none' ? 'margin:5px !important;' : '') .'
                            }
                            .'.$element_unique_id.'-logotypes-slider[data-direction="rtl"] .slick-track {
                                float: right;
                            }

                        </style>';
                    }

                    $output .= '
                    </div>';

                    if ($logotypes_display_arrows) {
                        $output .= '
                        <span class="pwe-logotypes__arrow pwe-logotypes__arrow-prev pwe-arrow pwe-arrow-prev">‹</span>
                        <span class="pwe-logotypes__arrow pwe-logotypes__arrow-next pwe-arrow pwe-arrow-next">›</span>';
                    }

                $output .= '
                </div>';

                if ($mobile != 1 && ($logotypes_slider_desktop == true) || $mobile == 1 && ($logotypes_grid_mobile != true || (!empty($header_logotypes_media_urls) && $header_logotypes_slider_off != true))) {

                    $logotypes_dots_display = $logotypes_dots_off != true ? 'true' : '';
                    include_once plugin_dir_path(dirname(dirname(__DIR__))) . 'scripts/slider.php';

                    if(!$logotypes_slider_3_row){
                        $output .= PWESliderScripts::sliderScripts('logotypes', '#'. $element_unique_id, $logotypes_dots_display, $logotypes_display_arrows, $slides_to_show, $options = null, $slides_to_show_960, $slides_to_show_600, $slides_to_show_400);
                    } else {
                        $slides_to_show = !empty($slides_to_show) ? $slides_to_show : 7;
                        wp_enqueue_style('slick-slider-css', plugins_url('../../../assets/slick-slider/slick.css', __FILE__));
                        wp_enqueue_style('slick-slider-theme-css', plugins_url('../../../assets/slick-slider/slick-theme.css', __FILE__));
                        wp_enqueue_script('slick-slider-js', plugins_url('../../../assets/slick-slider/slick.min.js', __FILE__), array('jquery'), null, true);
                        $output .= '
                            <script>
                                jQuery(document).ready(function ($) {
                                    setTimeout(function () {
                                        let sliders = $(".logotypes-slider");

                                        sliders.each(function () {
                                            let isRTL = $(this).data("direction") === "rtl";
                                            let totalSlides = $(this).find(".pwe-logo-item-container").length;
                                            let slidesToShow = ' . $slides_to_show . ';
                                            let useInfinite = totalSlides > slidesToShow;

                                            $(this).slick({
                                                slidesToShow: slidesToShow,
                                                slidesToScroll: 1,
                                                autoplay: true,
                                                autoplaySpeed: 2000,
                                                arrows: false,
                                                dots: false,
                                                infinite: useInfinite,
                                                speed: 1000,
                                                cssEase: "linear",
                                                rtl: isRTL,
                                                draggable: false,
                                                swipe: false,
                                                touchMove: false,
                                                pauseOnHover: false,
                                                waitForAnimate: false,
                                                responsive: [
                                                    {
                                                        breakpoint: 1024,
                                                        settings: {
                                                            slidesToShow: 5
                                                        }
                                                    },
                                                    {
                                                        breakpoint: 768,
                                                        settings: {
                                                            slidesToShow: 3
                                                        }
                                                    }
                                                ]
                                            });
                                        });

                                        sliders.css({ "opacity": "1" });
                                    }, 500);
                                });
                            </script>';
                    }
                }

            }
        }

        $get_database_fairs_data_encode = json_encode(PWECommonFunctions::get_database_fairs_data());
        $get_database_logotypes_data_encode = json_encode(PWECommonFunctions::get_database_logotypes_data());
        $get_database_meta_data_encode = json_encode(PWECommonFunctions::get_database_meta_data());
        $updated_images_url = json_encode($updated_images_url);

        $output .= '
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const get_database_fairs_data = ' . $get_database_fairs_data_encode . ';
                const get_database_logotypes_data = ' . $get_database_logotypes_data_encode . ';
                const get_database_meta_data = ' . $get_database_meta_data_encode . ';
                const updated_images_url = '. $updated_images_url .';

                // console.log(get_database_logotypes_data);

                // if (updated_images_url != null) {
                //     updated_images_url.forEach(logo => {
                //         if (logo.site !== "" && logo.site != null) {
                //             console.log("url:" + logo.img + " " + "link:" + logo.site);
                //         }
                //     });
                // }

                // Funkcja do sprawdzania statusu obrazu
                function checkImage(url, callback) {
                    const img = new Image();
                    img.onload = () => callback(true);
                    img.onerror = () => callback(false);
                    img.src = url;
                }

                document.querySelectorAll(".pwe-logo-item img").forEach(img => {
                    const url = img.getAttribute("src");

                    // Check if the image exists
                    checkImage(url, exists => {
                        if (!exists) {
                            // Delete the entire image container
                            const container = img.closest(".pwe-logo-item-container");
                            if (container) {
                                container.remove();
                            }
                        }
                    });
                });

            });
        </script>';

        return $output;
    }

}