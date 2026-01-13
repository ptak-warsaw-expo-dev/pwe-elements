<?php

class PWENewsSayAboutUs extends PWENews {
    public function __construct() {}
        public static function initElements(){
            $dep = array(
                'element' => 'news_template_type',
                'value'   => 'PWENewsSayAboutUs',
            );

            return array(
                // Title - <h1>
                array(
                    'type' => 'textfield',
                    'group' => 'News',
                    'heading' => __('News Title', 'pwelement'),
                    'param_name' => 'news_say_about_us_title',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Description - <p>
                array(
                    'type' => 'textarea_raw_html',
                    'group' => 'News',
                    'heading' => __('News Description', 'pwelement'),
                    'param_name' => 'news_say_about_us_description',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                //Title - <h2>
                array(
                    'type' => 'textfield',
                    'group' => 'News',
                    'heading' => __('News Subtitle', 'pwelement'),
                    'param_name' => 'news_say_about_us_subtitle',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Description - <p>
                array(
                    'type' => 'textarea_raw_html',
                    'group' => 'News',
                    'heading' => __('News Additional Description', 'pwelement'),
                    'param_name' => 'news_say_about_us_additional_description',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Text inside quotation marks
                array(
                    'type' => 'textarea_raw_html',
                    'group' => 'News',
                    'heading' => __('Text inside Quotation Marks', 'pwelement'),
                    'param_name' => 'news_say_about_us_quotation_text',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Subtitle under quotation marks - <h3>
                array(
                    'type' => 'textfield',
                    'group' => 'News',
                    'heading' => __('Subtitle under Quotation Marks', 'pwelement'),
                    'param_name' => 'news_say_about_us_quotation_subtitle',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Button link under blocks subtitle
                array(
                    'type' => 'textfield',
                    'group' => 'News',
                    'heading' => __('Blocks Button Link (SPRAWDŹ)', 'pwelement'),
                    'param_name' => 'news_say_about_us_blocks_button_link',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Title on top of blocks - <h4>
                array(
                    'type' => 'textfield',
                    'group' => 'News',
                    'heading' => __('Title on Top of Blocks', 'pwelement'),
                    'param_name' => 'news_say_about_us_blocks_title',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Reapetable blocks - main strengths
                array(
                    'type' => 'param_group',
                    'group' => 'News',
                    'heading' => __('Main strengths', 'pwelement'),
                    'param_name' => 'news_say_about_us_blocks_repeater',
                    'dependency' => $dep,
                    'params' => array(
                        array(
                            'type' => 'textarea_raw_html',
                            'heading' => __('Block Icon (SVG)', 'pwelement'),
                            'param_name' => 'news_say_about_us_blocks_icon',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Block Title', 'pwelement'),
                            'param_name' => 'news_say_about_us_blocks_inside_title',
                            'admin_label' => true,
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textarea_raw_html',
                            'heading' => __('Block Desc', 'pwelement'),
                            'param_name' => 'news_say_about_us_blocks_desc',
                            'save_always' => true,
                        ),
                    ),
                ),
                // Subtitle under blocks - <h5>
                array(
                    'type' => 'textfield',
                    'group' => 'News',
                    'heading' => __('Subtitle under Blocks', 'pwelement'),
                    'param_name' => 'news_say_about_us_blocks_subtitle',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Description under blocks - <p>
                array(
                    'type' => 'textarea_raw_html',
                    'group' => 'News',
                    'heading' => __('Description under Blocks', 'pwelement'),
                    'param_name' => 'news_say_about_us_blocks_description',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Portal domain link - <p>
                array(
                    'type' => 'textfield',
                    'group' => 'News',
                    'heading' => __('Portal Domain Link', 'pwelement'),
                    'param_name' => 'news_say_about_us_domain_link',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Text under domain title - <p>
                array(
                    'type' => 'textarea_raw_html',
                    'group' => 'News',
                    'heading' => __('Text under Domain Title', 'pwelement'),
                    'param_name' => 'news_say_about_us_domain_text',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
                // Image - logo of portal
                array(
                    'type' => 'attach_image',
                    'group' => 'News',
                    'heading' => __('Insert Logo of Portal: ', 'pwelement'),
                    'param_name' => 'news_say_about_us_image',
                    'save_always' => true,
                    'dependency' => $dep,
                ),
            );
        }

        public static function output($atts, $content = null) {

        $args = shortcode_atts(array(
            'news_say_about_us_title' => '',
            'news_say_about_us_description' => '',
            'news_say_about_us_subtitle' => '',
            'news_say_about_us_additional_description' => '',
            'news_say_about_us_quotation_text' => '',
            'news_say_about_us_quotation_subtitle' => '',
            'news_say_about_us_blocks_title' => '',
            'news_say_about_us_blocks_repeater' => '',
            'news_say_about_us_blocks_subtitle' => '',
            'news_say_about_us_blocks_button_link' => '',
            'news_say_about_us_blocks_description' => '',
            'news_say_about_us_domain_link' => '',
            'news_say_about_us_domain_text' => '',
            'news_say_about_us_image' => '',
        ), $atts);

        // HTML fields (textarea_raw_html)
        $html_fields = array(
            'news_say_about_us_description',
            'news_say_about_us_additional_description',
            'news_say_about_us_quotation_text',
            'news_say_about_us_blocks_desc',     
            'news_say_about_us_blocks_description',
            'news_say_about_us_domain_text',
        );

        // Text fields (textfield)
        $text_fields = array(
            'news_say_about_us_blocks_inside_title',
            'news_say_about_us_title',
            'news_say_about_us_subtitle',
            'news_say_about_us_quotation_subtitle',
            'news_say_about_us_blocks_title',
            'news_say_about_us_domain_link',
            'news_say_about_us_blocks_subtitle',
        );

        foreach ($html_fields as $field) {
            $raw = isset($args[$field]) ? (string)$args[$field] : '';
            $args[$field] = wp_kses_post(PWECommonFunctions::decode_clean_content($raw));
        }

        foreach ($text_fields as $field) {
            $raw = isset($args[$field]) ? (string)$args[$field] : '';
            $args[$field] = sanitize_text_field($raw);
        }

        $args['news_say_about_us_blocks_button_link'] = esc_url_raw(
            (string)($args['news_say_about_us_blocks_button_link'] ?? '')
        );


        // Local variables
        $blocks = vc_param_group_parse_atts( $args['news_say_about_us_blocks_repeater'] );
        if (!is_array($blocks)) {$blocks = [];}

        $title   = $args['news_say_about_us_title'];
        $desc    = $args['news_say_about_us_description'];
        $subtitle= $args['news_say_about_us_subtitle'];
        $add_desc= $args['news_say_about_us_additional_description'];

        $q_text  = $args['news_say_about_us_quotation_text'];
        $q_sub   = $args['news_say_about_us_quotation_subtitle'];

        $b_title = $args['news_say_about_us_blocks_title'];
        $b_sub   = $args['news_say_about_us_blocks_subtitle'];
        $blocks_btn_link = $args['news_say_about_us_blocks_button_link'];
        $b_desc  = $args['news_say_about_us_blocks_description'];

        $d_title = $args['news_say_about_us_domain_link'];
        $d_text  = $args['news_say_about_us_domain_text'];

        $image   = $args['news_say_about_us_image'];
        
        // get background image URL
        $home_parts = wp_parse_url( home_url('/') );
        $origin = $home_parts['scheme'] . '://' . $home_parts['host'] . (isset($home_parts['port']) ? ':' . $home_parts['port'] : '');
        $bg_url = esc_url( $origin . '/doc/background.webp' );


        
        // SVGs
        $quote_svg = '
        <svg class="pwe-news-say-about-us__quote" viewBox="0 0 512 512" aria-hidden="true" focusable="false">
            <path d="M119.472,66.59C53.489,66.59,0,120.094,0,186.1c0,65.983,53.489,119.487,119.472,119.487c0,0-0.578,44.392-36.642,108.284c-4.006,12.802,3.135,26.435,15.945,30.418c9.089,2.859,18.653,0.08,24.829-6.389c82.925-90.7,115.385-197.448,115.385-251.8C238.989,120.094,185.501,66.59,119.472,66.59z"></path>
            <path d="M392.482,66.59c-65.983,0-119.472,53.505-119.472,119.51c0,65.983,53.489,119.487,119.472,119.487c0,0-0.578,44.392-36.642,108.284c-4.006,12.802,3.136,26.435,15.945,30.418c9.089,2.859,18.653,0.08,24.828-6.389C479.539,347.2,512,240.452,512,186.1C512,120.094,458.511,66.59,392.482,66.59z"></path>
        </svg>';

        $icon_block_1 = <<<SVG
        <svg class="pwe-news-say-about-us__block-svg" viewBox="0 0 512 512" aria-hidden="true" focusable="false">
            <path d="M93.8,114.6c-4.7,1.1-1.7,0.9-5.6,1.4C71.5,119.6,83.9,122.8,93.8,114.6z M387.5,121.3c1.2-0.8,5.4-4.9-7.7-8.9  c0.8,4.1-2.7,3.7-2.7,6c9.7,8.8,13.7,24.1,26.1,27.3C405.6,134.7,392.2,129.3,387.5,121.3z M84.9,111.4c1.5,8.9,8.2-9.4,8.3-15.9  c-2.6,1.5-5.2,3-7.9,4.2c6.3,3.2,0.8,6.6-6,11.7C65.5,128.6,92.2,98,84.9,111.4z M256,0C114.6,0,0,114.6,0,256  c0,141.3,114.6,256,256,256c141.4,0,256-114.7,256-256C512,114.6,397.4,0,256,0z M262.8,85.8l1.2,0.4c-4.8,6.2,25,24.3,3.6,25.8  c-20,5.7,8.4-5.2-7.1-3.3C268.7,97.3,254,97.1,262.8,85.8z M141.4,102.2c-7.2-6-29.8,8.2-21.9,4.8c19.6-7.7,1.3,0.8,5.9,10  c-4.2,8.7-1.4-8.6-11.8,1.7c-7.5,1.7-25.9,18.7-23.6,13.5c-0.6,8.1-21.9,17.7-24.8,31.2c-7,18.7-1.7-0.7-3-8  c-10-12.7-28.2,21.5-22.8,35c9.1-16,8.4-1.7,1.8,5.4c6.7,12.3-6.1,28.3,6.6,37.4c5.6,1.3,16.8-18.8,11.9,2.1  c3.4-18.1,9.4,4.3,19.1-0.7c0.6,9.5,6.5,5.1,7.8,16.6c16.2-1.2,31,26.2,11.7,31.4c2.9-0.8,8.6,4.3,15.2,0.4  c11.2,8.9,40.7,10,41.5,32c-20.3,9.7-5,36.3-22.6,45.8c-20.2-3-6.9,24.9-15.4,21.7c3.4,20.1-20.4-2.6-11.2,8.5  c16.9,10.4-7.4,8.3,0.2,15.9c-8.5-1.8,5.3,15.8,7.6,22.3c12.2,19.8-10.5-4.4-17.2-11c-6.4-12.8-21.5-37.3-25.7-57.4  c-2.4-29.2-25-48.8-30.2-77.3c-5.2-15.9,14.3-41.4,3.8-50.3c-9.1-7.1-5.4-31.4-10.8-44.2c13.5-58.5,56.4-107.8,107.9-137  c-5.3,3.9,30.3-10.1,26.2-6.7c-1.1,2.5,20.8-9.5,34-11.3c-1.4,0.2-34.3,12-25.2,10.4c-14.1,6.9-1.4,3,5.6-0.5  c-14,10.3-24.8,7.4-40.7,16.5c-16,4.2-12.7,20.8-24.1,29.1c6.7,1.2,23.5-17.3,33.3-23.8c22.5-10.9-11.4,19.8,10,6.6  c-7.2,6.7-5.7,17.4-10.1,20.4C148.2,92.1,159.1,97.9,141.4,102.2z M176.4,56.2c-2.3,3.1-5.5,9.8-7.4,5.7c-2.6,1.3-3.6,6.9-8.5,2.4  c2.9-2.1,5.9-7.1,0.2-4c2.6-2.8,25.8-10.7,24.5-13.7c4.1-2.6,3.7-3.9-1-2.3c-2.4-0.8,5.7-7.6,16.5-8.5c1.5,0,2.1,1-0.6,0.7  c-16.3,5-9.3,3.6,1.7,0c-4.2,2.4-7.1,3.1-7.8,4.2c11-4-0.6,2.9,1.9,2.4c-3.1,1.6,0.5,2.1-5.5,4.4c1.1-0.9-9.8,6.5-3.3,4.3  C180.8,57.8,178,57.9,176.4,56.2z M186,70.5c0.2-9.6,14-15.7,12.3-16.2c17-8-5.9,0.3,7.5-6.9c5-0.5,15.6-16.5,30.3-17.5  c16.2-4.9,8.7,0.3,20.7-4.3l-2.4,2c-2.1,0.3,0.5,4-7.1,9.6c-0.8,8.7-14.5,4.7-7.7,14c-4.4-6.3-11-0.2-2.7,0.4  c-8.9,6.8-29.6,8-39.5,19.3C191,80.1,185.1,77.2,186,70.5z M257.1,102.5c-6.8,16.4-13.4-2.4-1.4-5.4  C258.7,98.7,259.9,99.2,257.1,102.5z M231.5,69.7c-2-7.4-0.4-3.5,11.5-7C251.2,68.6,235.7,72.5,231.5,69.7z M417.7,363.2  c-9.4-16.2,11.4-31.2,18.4-44.8C435.2,334.3,433.2,350,417.7,363.2z M453.1,178.1c-10.2,0.8-19.4,3.2-28.6-2.6  c-21.2-23.2,3.9,26.2,10.9,6c25.2,9.6-0.4,51-16.3,46.7c-8.9-19.2-19.9-40.3-39.3-49.7c14.9,16.5,22.3,36.8,38.3,51.7  c1.1,20.8,22.2-7.6,20.9,8.5c2,27.7-31.3,44.3-25.5,72.1c12.4,25.3-23.9,29.9-19.8,52.6c-14.6,16.3-30.2,38.3-56.4,34.8  c0-13.8-7-25.5-8.6-39.7c-14.2-18,15-37.3-3.1-56.1c-20.9-4.7,4.3-33.5-17.2-30.8c-12.9-12.9-31.8-0.4-50.3-0.3  c-23.2,2.2-47.1-28.5-36.8-50.2c-8.2-22.6,26-29.2,26.9-49.1c16.4-13.7,39.7-12,61.9-15.2c-1.6,15.9,15.2,16,27.9,21.3  c7.1-17.2,29.2,2.8,44.3-8.1c5.2-25.4-14.7-10.1-26.1-18.2c-13.8-20.2,29.5-10.4,25-21c-16.8-0.1-7.3-20.7-19.2-9.2  c10.7,1.9-1.9,10.3-1.6,0.7c-16.2-4.7-0.6,18.4-8.8,20.6c-12.5-5.2-6.6,5.9-5.3,7.6c-5.4,11.7-12-17.2-27.3-16.4  c-15.2-13.9-6,6.3,7.2,9.6c-2.8,0.8,1.6,12.3-1.9,7.4c-10.9-15-31.6-25-43.9-6.6c-1.3,17.2-36.3,22.1-30.7,2  c-8.2-20.8,25.4-0.6,22.3-17.2c-21.6-14.3,5.9-9.7,13.2-23.1c16.6,0.5,0.7-13.6,8.5-17.7c-0.8,15.3,12.7,12.4,23.4,9.5  c-2.6-8.8,6.4-8.5,0.9-15.8c24.8-9.9-18.9,4.6-10.1-17.1c-10.7-7.4-4.5,16.3,0,18.8c0.3,7.3-5.9,16.3-14.4,1  c-12.4,8.1-11.1-8.2-11.9-6.5c-1.4-6.3,9.4-6.6,9.5-17.6c-0.9-7-0.7-10.7,4.3-11.1c0.4,1,20.5,1.3,27.6,9.6  c-19.4-3.9-2.9,3.2,5.8,7.2c-9.3-7.3,3.7,0-3.9-8.3c3,0.6-8.3-11.4,3.3-0.9c-6.3-7.5,12.3-5.3,1.3-10.9c16.1,4.5,6.6,0.4-2.9-3.7  c-26.2-15.6,46.3,21.1,16.7,4.8c18.9,4.1-40.4-14.6-13.4-6.4c-10.3-4.5-0.3-2,9,0.9c-16.7-5.2-41.7-14.9-40.7-15.3  c5.8,0.4,11.5,1.7,17,3.3c17.1,5.1-4.9-1.2-0.2-1.1C373.8,44,425.3,83.4,456.6,134.9c7.3,7.7,27.2,58.6,16.8,36  c4.7,18,5.4,37.4,7.9,55.8c-5.2-5.8-11-27.2-16-39.1C463.2,192.2,460.8,181.1,453.1,178.1z"></path>
        </svg>
        SVG;

        $icon_block_2 = <<<SVG
        <svg class="pwe-news-say-about-us__block-svg" viewBox="0 0 32 32" aria-hidden="true" focusable="false">
            <path d="M5.344 10.688q0 2.912 1.44 5.344t3.936 3.84q0.512 0.736 1.056 1.344 0 0-0.032 0.064t0 0.064v4.256q0 0.896 0.608 1.536t1.504 0.608h4.288q0.864 0 1.504-0.608t0.608-1.536v-4.256q0-0.032 0-0.064t-0.032-0.064q0.544-0.608 1.088-1.344 2.432-1.376 3.904-3.84t1.472-5.344-1.44-5.344-3.904-3.904-5.344-1.44-5.344 1.44-3.904 3.904-1.408 5.344zM13.856 30.944q0 0.448 0.32 0.768t0.768 0.288h2.112q0.448 0 0.768-0.288t0.32-0.768-0.32-0.768-0.768-0.32h-2.112q-0.448 0-0.768 0.32t-0.32 0.768z"></path>
        </svg>
        SVG;

        $icon_block_3 = <<<SVG
        <svg class="pwe-news-say-about-us__block-svg" viewBox="0 0 512 512" aria-hidden="true" focusable="false">
            <path d="M494 61.363l-82.58 77.934 78.994 132.96 3.586-4.458V61.362zM18 62.5v225.893c4.48.582 9.863.903 15.295.96 11.87.125 21.654-.65 27.15-1.144L113.1 154.974 18 62.5zm389.154 104.86l-7.04 4.556c-.15.097-5.362 3.336-6.893 4.29l-10.605 6.42.15.09c-4.914 3.057-6.28 3.917-11.857 7.38-2.83 1.757-2.9 1.798-5.584 3.465-20.29-10.907-42.306-19.29-67.998-25.882-32.312 9.762-66.542 23.888-100.722 37.142 14.19 17.087 29.96 22.651 45.845 22.85 18.42.23 37.25-7.78 50.218-16.754l7.4-5.12 7.426 10.73 115.453 83.33 45.112-29.987-60.906-102.51zM126.477 170.1L81.11 284.887 97.76 297.69l30.795-34.905 2.467-2.795 3.72-.232c1.5-.094 2.98-.138 4.44-.13 10.212.066 19.342 2.716 26.19 8.76 5.072 4.472 8.444 10.426 10.4 17.32l2.28-.142c11.995-.75 22.802 1.725 30.63 8.63 7.827 6.907 11.63 17.323 12.38 29.32l.07 1.08c6.44 1.216 12.205 3.752 16.893 7.888 7.828 6.906 11.63 17.32 12.38 29.317l.197 3.12c.642.202 1.275.424 1.9.658l2.033-2.853 5.47-7.678 2.813-3.95 7.33 5.223 59.428 42.336c6.464-1.594 10.317-4.075 12.46-7.086 2.147-3.012 3.233-7.47 2.624-14.107l-71.258-51.03-7.318-5.24 5.19-7.246 6.67-9.365 7.33 5.223 80.335 57.226c6.464-1.593 10.32-4.074 12.463-7.085 2.144-3.01 3.23-7.457 2.625-14.082l-92.398-65.55-7.34-5.21 10.414-14.68 7.343 5.208 92.414 65.565c6.47-1.594 10.327-4.075 12.473-7.088 2.148-3.015 3.233-7.476 2.62-14.125l-110.44-79.71c-14.655 8.688-33.402 15.648-53.557 15.396-23.587-.295-48.817-11.566-67.377-40.05a9 9 0 0 1 4.343-13.327c13.014-4.945 26.163-10.17 39.343-15.354l-92.056-6.834zm12.902 107.62l-47.564 53.91c.927 6.746 3.04 10.942 5.887 13.454 2.847 2.512 7.275 4.085 14.084 4.164l47.563-53.908c-.927-6.747-3.04-10.945-5.887-13.457-2.847-2.512-7.274-4.084-14.084-4.162zm43.308 25.81l-53.713 60.88c.926 6.747 3.04 10.945 5.886 13.457 2.85 2.51 7.275 4.083 14.085 4.16l53.713-60.878c-.926-6.748-3.04-10.944-5.887-13.457-2.846-2.512-7.273-4.085-14.083-4.164zm29.34 38.286l-47.56 53.91c.927 6.746 3.04 10.943 5.887 13.456 2.848 2.512 7.275 4.083 14.084 4.162L232 359.44c-.927-6.75-3.04-10.947-5.887-13.46-2.847-2.512-7.274-4.083-14.084-4.162zm24.702 39.137l-38.794 44.28c.925 6.76 3.038 10.962 5.888 13.476 2.845 2.51 7.267 4.082 14.067 4.163l38.796-44.28c-.926-6.758-3.04-10.96-5.89-13.476-2.844-2.51-7.266-4.08-14.066-4.162zm35.342 4.79c1.694 4.62 2.673 9.74 3.014 15.192l.232 3.704-8.277 9.448 26.724 19.037c6.464-1.594 10.316-4.075 12.46-7.086 2.145-3.01 3.233-7.464 2.628-14.093l-36.78-26.2z"></path>
        </svg>
        SVG;


        ob_start();
        // getting domain
        $domain = parse_url(home_url(), PHP_URL_HOST);
        // getting colors from PWE shortcodes
        $accent_color = trim( do_shortcode('[pwe_color_accent domain="' . $domain . '"]') );
        $main2_color  = trim( do_shortcode('[pwe_color_main2 domain="' . $domain . '"]') );
        $accent_color = sanitize_hex_color($accent_color) ?: $accent_color;
        $main2_color  = sanitize_hex_color($main2_color)  ?: $main2_color;


        // Featured image at top
        $post_id       = get_the_ID();
        $thumbnail_id  = get_post_thumbnail_id($post_id);
        $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';
        
        $style_vars = sprintf(
        '--accent-color:%s;--main2-color:%s;--pwe-bg:url(%s);',
        esc_attr($accent_color),
        esc_attr($main2_color),
        esc_url($bg_url)
        );

        $register_url = esc_url( home_url(self::languageChecker('/rejestracja/' , '/registration/')) );

        $domain_img_html = '';
        $image_id = absint($args['news_say_about_us_image']);
        if ($image_id) {
            $domain_img_html = wp_get_attachment_image(
                $image_id,
                'full',
                false,
                array('loading' => 'lazy', 'class' => 'pwe-news-say-about-us__domain-image')
            );
        }


        $domain_clean_text = '';
        if(!empty($d_title)){
            $domain_clean_text = preg_replace('#^https?://#', '', rtrim($d_title, '/'));
        }

?>
        <!-- HEADER AND TEXTS -->
<section class="pwe-news-say-about-us" style="<?php echo esc_attr($style_vars); ?>">
        <?php if ($thumbnail_url): ?>
        <img
            class="pwe-news-say-about-us__header"
            src="<?php echo esc_url($thumbnail_url); ?>"
            alt="<?php echo esc_attr( $title ?: self::languageChecker('Grafika nagłówkowa artykułu', 'Article header graphic')); ?>"
            loading="lazy"
        >
        <?php endif; ?>

        <div class="pwe-news-say-about-us__container-header">
            <?php if ($title !== ''): ?>
                <h1 class="pwe-news-say-about-us__title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>

            <?php if ($desc !== ''): ?>
                <div class="pwe-news-say-about-us__description"><?php echo $desc; ?></div>
            <?php endif; ?>

            <?php if ($subtitle !== ''): ?>
                <h2 class="pwe-news-say-about-us__subtitle"><?php echo esc_html($subtitle); ?></h2>
            <?php endif; ?>

            <?php if ($add_desc !== ''): ?>
                <div class="pwe-news-say-about-us__additional-description"><?php echo $add_desc; ?></div>
            <?php endif; ?>
        </div>

        <!-- FULL WIDTH BACKGROUND AND QUOTE -->
  <section class="pwe-news-say-about-us__quote-section">
    <div class="pwe-news-say-about-us__bg" aria-hidden="true"></div>
    <div class="pwe-news-say-about-us__container-quote">
        <div class="pwe-news-say-about-us__quote-box">
            <div class="pwe-news-say-about-us__quote-icon pwe-news-say-about-us__quote-icon--left">
                <?php echo $quote_svg; ?>
            </div>
            <div class="pwe-news-say-about-us__quote-icon pwe-news-say-about-us__quote-icon--right">
                <?php echo $quote_svg; ?>
            </div>
            <div class="pwe-news-say-about-us__quotation-text">
                <?php echo wp_kses_post($args['news_say_about_us_quotation_text']); ?>
            </div>
        </div>
    </div>
  </section>
    <!-- CHECK THE ARTICLE -->
    <section class="pwe-news-say-about-us__under-quote-button-section">
        <div class="pwe-news-say-about-us__quotation-subtitle">
            <?php echo esc_html($args['news_say_about_us_quotation_subtitle']); ?>
        </div>

        <?php if (!empty($blocks_btn_link)): ?>
            <div class="pwe-news-say-about-us__blocks-check">
                <a class="pwe-news-say-about-us__btn pwe-news-say-about-us__btn--check"
                   href="<?php echo esc_url($blocks_btn_link); ?>"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?php echo self::languageChecker('SPRAWDŹ', 'CHECK'); ?>
                </a>
                <div class="pwe-section-separator"></div>
            </div>
        <?php endif; ?>
    </section>
  <!-- BLOCKS -->
  <section class="pwe-news-say-about-us__blocks">
    <div class="pwe-news-say-about-us__container-blocks">
  <?php if ($b_title !== ''): ?>
    <h3 class="pwe-news-say-about-us__blocks-title"><?php echo esc_html($b_title); ?></h3>
  <?php endif; ?>
  <div class="pwe-section-separator"></div>

<div class="title-above-blocks">
     <?php echo self::languageChecker(
        'Trzy główne atuty wydarzenia',
        'Three main advantages of the event'
    ); ?>
</div>
  <div class="pwe-news-say-about-us__blocks-grid">
    
    <?php
    
    $default_svgs = array($icon_block_1, $icon_block_2, $icon_block_3);

    if (!empty($blocks)) :
        $i = 0;
        foreach ($blocks as $card) :

            $card_title = !empty($card['news_say_about_us_blocks_inside_title'])
                ? sanitize_text_field($card['news_say_about_us_blocks_inside_title'])
                : '';

            $card_desc = !empty($card['news_say_about_us_blocks_desc'])
                ? wp_kses_post(PWECommonFunctions::decode_clean_content($card['news_say_about_us_blocks_desc']))
                : '';

            
            $icon_html = '';
            if (!empty($card['news_say_about_us_blocks_icon'])) {
                $icon_html = wp_kses_post(PWECommonFunctions::decode_clean_content($card['news_say_about_us_blocks_icon']));
            }
            if ($icon_html === '') {
                $icon_html = $default_svgs[$i % count($default_svgs)];
            }
    ?>
        <div class="pwe-news-say-about-us__block">
            <div class="pwe-news-say-about-us__block-icon"><?php echo $icon_html; ?></div>

            <?php if ($card_title !== ''): ?>
                <div class="pwe-news-say-about-us__block-title"><?php echo esc_html($card_title); ?></div>
            <?php endif; ?>

            <?php if ($card_desc !== ''): ?>
                <div class="pwe-news-say-about-us__block-text"><?php echo $card_desc; ?></div>
            <?php endif; ?>
        </div>
    <?php
            $i++;
        endforeach;
    endif;
    ?>


  </div>

  <?php if ($b_sub !== ''): ?>
    <div class="pwe-news-say-about-us__blocks-subtitle"><?php echo esc_html($b_sub); ?></div>
  <?php endif; ?>

  <?php if ($b_desc !== ''): ?>
    <div class="pwe-news-say-about-us__blocks-description"><?php echo $b_desc; ?></div>
  <?php endif; ?>
<div class="pwe-news-say-about-us__cta">
    <a href="<?php echo $register_url; ?>" class="pwe-news-say-about-us__btn"><?php echo self::languageChecker('Weź udział', 'Join the event'); ?></a>
  </div>
<div class="pwe-section-separator"></div>
</div>
</section>

  <!-- ABOUT THE PORTAL -->
  <section class="pwe-news-say-about-us__domain">
  <div class="pwe-news-say-about-us__container-left">
    <div class="pwe-news-say-about-us__domain-label"><?php echo self::languageChecker('O portalu', 'About the portal'); ?></div>

    <?php echo $domain_img_html; ?>
  </div>
  <div class="pwe-news-say-about-us__container-right">
    <div class="pwe-news-say-about-us__domain-title">
    <a href="<?php echo esc_url($args['news_say_about_us_domain_link']); ?>" target="_blank" rel="noopener noreferrer">
        <?php echo esc_html($domain_clean_text); ?>
    </a>
  </div>

    <div class="pwe-news-say-about-us__domain-text">
      <?php echo wp_kses_post($args['news_say_about_us_domain_text']); ?>
      </div>
    </div>
  </div>
</section>


</section>
    <?php
    return ob_get_clean();
    }
}