<?php

/**
 * Class PWElementPosts
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementPosts extends PWElements {

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
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Select form', 'pwelement'),
                'param_name' => 'posts_modes',
                'save_always' => true,
                'value' => array(
                    'Slider mode' => 'posts_slider_mode',
                    'Full mode' => 'posts_full_mode',
                    'Full mode newest (top 6)' => 'posts_full_newest_mode',
                    'Full mode newest slider' => 'posts_full_newest_slider_mode',
                    'Simple mode with more button' => 'posts_simple_with_button_more',
                    'Syncing Slider' => 'posts_syncing_slider_mode',
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPosts',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Category', 'pwelement'),
                'param_name' => 'posts_category',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementPosts',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Posts to show', 'pwelement'),
                'param_name' => 'posts_to_show',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'posts_modes',
                    'value' => array(
                        'posts_slider_mode',
                        'posts_full_newest_slider_mode'
                    ),
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Posts count', 'pwelement'),
                'param_name' => 'posts_count',
                'save_always' => true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementPosts',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Aspect ratio', 'pwelement'),
                'param_name' => 'posts_ratio',
                'description' => __('Default 1/1', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                  'element' => 'posts_modes',
                  'value' => 'posts_slider_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Button link', 'pwelement'),
                'param_name' => 'posts_link',
                'description' => __('Default aktualnosci-news', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                  'element' => 'posts_modes',
                  'value' => 'posts_slider_mode',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide posts button', 'pwelement'),
                'param_name' => 'posts_btn',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true'),
                'dependency' => array(
                    'element' => 'posts_modes',
                    'value' => array('posts_slider_mode', 'posts_simple_with_button_more'),
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Box model', 'pwelement'),
                'param_name' => 'posts_box_model',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'posts_modes',
                  'value' => 'posts_slider_mode',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Display all categories (<= 5 posts)', 'pwelement'),
                'param_name' => 'posts_all_cat',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'posts_modes',
                  'value' => 'posts_slider_mode',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Display all posts (> 5 posts)', 'pwelement'),
                'param_name' => 'posts_all',
                'description' => __('View all posts in such categories (more than 5 posts)', 'pwelement'),
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'posts_modes',
                  'value' => 'posts_slider_mode',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Full width', 'pwelement'),
                'param_name' => 'posts_full_width',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                  'element' => 'posts_modes',
                  'value' => 'posts_slider_mode',
                ),
            ),
        );
        return $element_output;
    }


    public static function outputSliderSyncing($atts, $posts_data = array(), $posts_title = '') {

        wp_enqueue_style('slick-slider-css', plugins_url('../assets/slick-slider/slick.css', __FILE__));
        wp_enqueue_style('slick-slider-theme-css', plugins_url('../assets/slick-slider/slick-theme.css', __FILE__));
        wp_enqueue_script('slick-slider-js', plugins_url('../assets/slick-slider/slick.min.js', __FILE__), array('jquery'), null, true);

        $output ='
        <style>
            .posts_slider_syncing__container {
                display: flex;
                gap: 18px;
            }

            .posts_slider_syncing__title {
                margin: 24px auto;
            }

            .slider_syncing__main {
                width: 50%;
                z-index: 1;
                background-color: white;
            }

            .slider_syncing__nav {
                width: 75%;
                left: -27%;
            }

            #PostsSliderSyncing  .slider_syncing__item {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                border-radius: 18px;
                margin: 12px;
                box-sizing: border-box;
                box-shadow: -2px 4px 8px #0000001c;
                overflow: hidden;
            }

            .slider_syncing__nav .slider_syncing__item {
                margin: 0 18px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                box-sizing: border-box;
                margin: 12px;
            }

            #PostsSliderSyncing .slider_syncing__main .slick-slide img {
                height: 100%;
                max-height: 240px;
                width: 100%;
                object-fit: cover;
            }

            #PostsSliderSyncing .slider_syncing__nav .slick-slide img {
                height: 100%;
                max-height: 240px;
                width: 100%;
                object-fit: cover;
            }

            #PostsSliderSyncing .slider_syncing__item_info_container {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 0 20px 20px;
            }

            #PostsSliderSyncing .slider_syncing__item_date {
                background-color: #F8F8F8;
                padding: 4px 12px;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                margin: 0;
            }

            #PostsSliderSyncing .slider_syncing__item_title {
                font-size: 20px;
                margin: 0;
            }

            #PostsSliderSyncing .slider_syncing__description {
                margin: 0;
            }

            #PostsSliderSyncing .slider_syncing__read_more {
                margin: auto 20px 20px;
                padding: 8px 18px;
                width: 180px;
                border-radius: 36px;
                background-color: #F3F3F3;
                color: var(--accent-color) !important;
                font-weight: 600;
                font-size: 14px;
                display: flex;
                align-items: center;
            }

            #PostsSliderSyncing .slider_syncing__read_more:hover {
                background-color: #D9D9D9;
            }

            #PostsSliderSyncing .slider_syncing__read_more svg {
                width: 28px;
                margin-left: 6px;
                transition: 0.3s all;
            }

            #PostsSliderSyncing .slider_syncing__read_more:hover svg {
                margin-left: 24px;
            }

            .row.limit-width.row-parent:has(#PostsSliderSyncing) {
                padding: 18px;
            }

            @media(max-width:960px) {
            .slider_syncing__nav {
                left: -40%;
                }
            }
            @media(max-width:760px) {
                .slider_syncing__nav {
                    left: 0;
                    width: 50%;
                }
            }
             @media(max-width:600px) {
                .slider_syncing__main {
                    width: 100%;
                }
                #PostsSliderSyncing .slider_syncing__main .slick-slide img {
                    max-height: 160px;
                }
                .slider_syncing__nav {
                    display: none !important;
                }
                #PostsSliderSyncing .slider_syncing__description {
                    font-size: 14px;
                }
            }
        </style>

            <div id="PostsSliderSyncing" class="posts_slider_syncing__main_container pwelement_' . self::$rnd_id . '">
                    <h2 class="posts_slider_syncing__title">' . $posts_title . '</h2>
                    <div class="posts_slider_syncing__container">';
                        $output .= '<div class="slider_syncing slider_syncing__main">';
                            foreach ($posts_data as $post) {
                                $output .= '<div class="slider_syncing__item">';
                                    if (!empty($post['image'])) {
                                        $output .= '<img src="' . esc_url($post['image']) . '" alt="' . esc_attr($post['title']) . '">';
                                    }
                                    $output .= '<div class="slider_syncing__item_info_container">';
                                        $output .= '<p class="slider_syncing__item_date">data ' . esc_html($post['date']) . '</p>';
                                        $output .= '<h2 class="slider_syncing__item_title">' . esc_html($post['title']) . '</h2>';
                                        if (!empty($post['description'])) {
                                            $output .= '<p class="slider_syncing__description">' . esc_html($post['description']) . '...</p>';
                                            }
                                        $output .= '</div>';
                                        $output .= '<a href="' . esc_url($post['link']) . '" class="slider_syncing__read_more">' . self::languageChecker('Więcej', 'More') . '
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="var(--accent-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                </div>';
                            }
                        $output .= '</div>';

                        $output .= '<div class="slider_syncing slider_syncing__nav">';
                            foreach ($posts_data as $index => $post) {
                                $output .= '<div class="slider_syncing__item">';
                                        if (!empty($post['image'])) {
                                            $output .= '<img src="' . esc_url($post['image']) . '" alt="' . esc_attr($post['title']) . '">';
                                        }
                                        $output .= '<div class="slider_syncing__item_info_container">';
                                            $output .= '<p class="slider_syncing__item_date">data ' . esc_html($post['date']) . '</p>';
                                            $output .= '<h2 class="slider_syncing__item_title">' . esc_html(mb_strimwidth($post['title'], 0, 50, '...')) . '</h2>';
                                        $output .= '</div>';
                                        $output .= '<a href="' . esc_url($post['link']) . '" class="slider_syncing__read_more">' . self::languageChecker('Więcej', 'More') . '
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="var(--accent-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                    </div>';
                            }
                        $output .= '</div>
                    </div>
            </div>
        <script>
            jQuery(document).ready(function($) {
                const rootSingle = $(".pwelement_' . self::$rnd_id . ' .slider_syncing__main");
                const rootNav = $(".pwelement_' . self::$rnd_id . ' .slider_syncing__nav");

                function setGlobalMaxHeight() {
                    let maxHeight = 0;

                    $(".pwelement_' . self::$rnd_id . ' .slider_syncing__item").each(function () {
                        $(this).css("height", "auto");
                        const h = $(this).outerHeight();
                        if (h > maxHeight) maxHeight = h;
                    });

                    $(".pwelement_' . self::$rnd_id . ' .slider_syncing__item").height(maxHeight);
                    $(".pwelement_' . self::$rnd_id . ' .slick-slide").height("auto"); // reset
                    $(".pwelement_' . self::$rnd_id . ' .slider_syncing__main .slick-slide, .slider_syncing__nav .slick-slide").height(maxHeight);
                }

                rootSingle.on("init", function () {
                    setTimeout(setGlobalMaxHeight, 150);
                }).slick({
                    slide: ".slider_syncing__item",
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    adaptiveHeight: false,
                    infinite: true,
                    useTransform: true,
                    cssEase: "cubic-bezier(0.77, 0, 0.18, 1)",
                });

                rootNav.on("init", function () {
                    $(this).find(".slick-slide.slick-current").addClass("is-active");
                    setTimeout(setGlobalMaxHeight, 150);
                }).slick({
                    slide: ".slider_syncing__item",
                    slidesToShow: 3,
                    speed: 400,
                    slidesToScroll: 1,
                    dots: false,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    arrows: false,
                    focusOnSelect: false,
                    infinite: true,
                    responsive: [
                        {
                        breakpoint: 960,
                        settings: {
                            slidesToShow: 2
                        }
                        },
                        {
                        breakpoint: 760,
                        settings: {
                            slidesToShow: 1
                        }
                        }
                    ]
                });

                // Wstrzymaj oba slidery, gdy myszka jest nad którymkolwiek
                $(".pwelement_' . self::$rnd_id . ' #PostsSliderSyncing").on("mouseenter", function () {
                    rootSingle.slick("slickPause");
                    rootNav.slick("slickPause");
                }).on("mouseleave", function () {
                    rootSingle.slick("slickPlay");
                    rootNav.slick("slickPlay");
                });

                rootSingle.on("afterChange", function (event, slick, currentSlide) {
                    rootNav.slick("slickGoTo", currentSlide);
                    rootNav.find(".slick-slide.is-active").removeClass("is-active");
                    rootNav.find(`.slick-slide[data-slick-index="${currentSlide}"]`).addClass("is-active");
                });

                rootNav.on("click", ".slick-slide", function (event) {
                    // event.preventDefault();
                    var goToSingleSlide = $(this).data("slick-index");
                    rootSingle.slick("slickGoTo", goToSingleSlide);
                });

                $(window).on("resize", function () {
                    setTimeout(setGlobalMaxHeight, 200);
                });
            });
        </script>
        ';

        return $output;
    }
    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . ' !important;';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important';
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important';
        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $darker_main2_color = self::adjustBrightness(self::$main2_color, -20);

        extract( shortcode_atts( array(
            'posts_modes' => 'posts_slider_mode',
            'posts_category' => '',
            'posts_to_show' => '',
            'posts_count' => '',
            'posts_ratio' => '',
            'posts_link' => '',
            'posts_btn' => '',
            'posts_all_cat' => '',
            'posts_all' => '',
            'posts_full_width' => '',
            'posts_box_model' => '',
            ''
        ), $atts ));


        $trade_end = do_shortcode('[trade_fair_enddata]');
        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        $posts_title = "";
        if(get_locale() == "pl_PL") {
            $posts_title = ($posts_title == "") ? "Aktualności" : $posts_title;
            $posts_link = ($posts_link == "") ? "/aktualnosci/" : $posts_link;
            $posts_text = "Zobacz wszystkie";
        } else {
            $posts_title = ($posts_title == "") ? "News" : $posts_title;
            $posts_link = ($posts_link == "") ? "/en/news/" : $posts_link;
            $posts_text = "See all";
        }

        if ($posts_modes == "posts_slider_mode" || $posts_modes == "posts_simple_with_button_more") {
            $posts_ratio = ($posts_ratio == "") ? "1/1" : $posts_ratio;
        } else {
            $posts_ratio = ($posts_ratio == "") ? "21/9" : $posts_ratio;
        }

        $output = '';
        $output .= '
        <style>
            .pwelement_'.self::$rnd_id.' .pwe-btn {
                color: '. $btn_text_color .';
                background-color:'. $btn_color .';
                border:'. $btn_color .';
            }
            .pwelement_'.self::$rnd_id.' .pwe-btn:hover {
                color: '. $btn_text_color .';
                background-color:'. $darker_btn_color .' !important;
                border:'. $darker_btn_color .' !important;
            }
            .pwelement_'. self::$rnd_id .' .pwe-post-thumbnail .image-container {
                width: 100%;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                aspect-ratio: '. $posts_ratio .';
            }
            .pwelement_'. self::$rnd_id .' .pwe-post-title {
                text-align: left;
            }
        </style>';

        if ($posts_modes == "posts_slider_mode" || $posts_modes == "posts_simple_with_button_more") {
            $output .= '
            <style>
                .row-parent:has(.pwelement_'.self::$rnd_id.' .pwe-container-posts) {
                    max-width: 100%;
                    padding: 0 !important;
                }
                .pwelement_'.self::$rnd_id.' .pwe-posts-wrapper {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-posts {
                    opacity: 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post {
                    margin: 10px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-title {
                    padding-top: 18px;
                    margin: 0;
                    font-size: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-posts-title h4 {
                    margin: 0 auto 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-thumbnail .image-container {
                    border-radius: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-posts .slides {
                    align-items: flex-start !important;
                    gap: 16px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-posts .slides a {
                    padding: 10px;
                }
                @media (max-width: 1128px) {
                    .pwelement_'.self::$rnd_id.' .pwe-posts-wrapper {
                        padding: 36px;
                    }
                }
                @media (max-width: 650px) {
                    .pwelement_'. self::$rnd_id .' .pwe-posts .slides {
                        gap: 18px;
                    }
                }
            </style>';
            if($posts_box_model){
                $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-post {
                        border:1px solid rgba(235, 235, 235, 1);
                        padding:16px;
                        border-radius:12px;
                        box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.1);
                    }
                    .pwelement_'. self::$rnd_id .'  .slick-track {
                        display: flex !important;
                        align-items: stretch;
                    }

                    .pwelement_'. self::$rnd_id .'  .slick-slide  {
                        display: flex;
                        align-items: center; /* Opcjonalnie, jeśli chcesz wyśrodkować zawartość */
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post-thumbnail {
                        width:100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post {
                        flex-direction:column;
                    }
                </style>
                ';
            }
            // Display all categories across the full width of the page
            if ($posts_full_width === 'true' && $mobile != 1) {
                $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-posts-wrapper {
                        max-width: 100% !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-posts-title {
                        max-width: 1200px;
                        margin: 0 auto;
                        padding-left: 36px;
                    }
                    @media (max-width: 1128px) {
                        .pwelement_'. self::$rnd_id .' .pwe-posts-title {
                            padding-left: 0;
                        }
                    }
                </style>';
            }
            if($posts_modes == "posts_simple_with_button_more"){
                $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .slick-track {
                        align-items: stretch !important;
                        display:flex !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post-thumbnail .image-container {
                        border-radius: 36px 36px 0 0;
                        aspect-ratio: 16 / 9;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post .description  {
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        height: 100%;
                        padding:10px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post .description p {
                        color: '. $darker_btn_color .';
                        font-weight:700;
                        display: flex;
                        justify-content: right;
                        align-items: center;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post  .description img {
                        // background-color: '. $darker_btn_color .';
                    }
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
                    }
                    .pwelement_'. self::$rnd_id .' .slick-dots {
                        visibility: hidden;
                    }
                    .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-webkit-slider-thumb {
                        appearance: none;
                        -webkit-appearance: none;
                        height: 0; /* lub 1px jeśli musisz */
                        width: 0;
                        background: transparent;
                        box-shadow: none;
                    }
                    .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-moz-range-thumb {
                        height: 0;
                        width: 0;
                        background: transparent;
                        border: none;
                    }

                    .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-ms-thumb {
                        height: 0;
                        width: 0;
                        background: transparent;
                        border: none;
                    }
                    .pwelement_'. self::$rnd_id .' .slider-range_' . self::$rnd_id . '::-webkit-slider-thumb:hover {
                        background: black;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post {
                        display:flex !important;
                        flex-direction:column;
                        border-radius: 36px 36px 0 0;
                        -webkit-box-shadow: -2px 2px 4px 0px rgba(0, 0, 0, 0.17);
                        -moz-box-shadow: -2px 2px 4px 0px rgba(0, 0, 0, 0.17);
                        box-shadow: -2px 2px 4px 0px rgba(0, 0, 0, 0.17);
                        transition: .3s ease;
                        background-color:white !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-post:hover {
                        -webkit-box-shadow: -2px 2px 8px 0px rgba(0, 0, 0, 0.34);
                        -moz-box-shadow: -2px 2px 8px 0px rgba(0, 0, 0, 0.34);
                        box-shadow: -2px 2px 8px 0px rgba(0, 0, 0, 0.34);
                    }
                </style>';
            }
        } else {
            if ($posts_modes == "posts_full_mode" || $posts_modes == "posts_full_newest_mode") {
                $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-posts {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 20px;
                        opacity: 0;
                    }
                    @media (max-width: 960px) {
                        .pwelement_'. self::$rnd_id .' .pwe-posts {
                            grid-template-columns: repeat(2, 1fr);
                        }
                    }
                    @media (max-width: 500px) {
                        .pwelement_'. self::$rnd_id .' .pwe-posts {
                            grid-template-columns: repeat(1, 1fr);
                        }
                    }
                </style>';
            }
            $output .= '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-post {
                    display: flex;
                    flex-direction: column;
                    position: relative;
                    background-color: white;
                    border: 1px solid '. self::$main2_color .';
                    border-radius: 11px;
                    transition: .3s ease;
                    height: auto;
                    min-height: 350px;
                    margin: 10px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post:hover {
                    transform: scale(1.05);
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-title {
                    font-weight: 600;
                    font-size: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-thumbnail {
                    position: relative;
                    overflow: hidden;
                    border-radius: 10px 10px 0 0;
                    background-color: white;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-thumbnail .image-container {
                    border-radius: 10px 10px 0 0;
                    transition: .3s ease;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post:hover .pwe-post-thumbnail .image-container  {
                    transform: scale(1.05);
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-date {
                    position: absolute;
                    bottom: 10px;
                    left: 10px;
                    color: white;
                    font-size: 19px;
                    font-weight: 600;
                    line-height: 1.3;
                    width: 50px;
                    height: 50px;
                    text-transform: uppercase;
                    background-color: '. self::$main2_color .';
                    border-radius: 5px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                    z-index: 1;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-date:before {
                    position: absolute;
                    content:"";
                    background-color: rgba(0, 0, 0, 0.1);
                    bottom: 0;
                    left: 0;
                    right: 0;
                    width: 100%;
                    height: 50%;
                    z-index: 2;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-title {
                    font-size: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-title,
                .pwelement_'. self::$rnd_id .' .pwe-post-excerpt {
                    text-align: left;
                    color: #222;
                    padding: 9px;
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-excerpt {
                    padding: 9px 9px 50px;
                    line-height: 1.5;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-btn {
                    position: absolute;
                    color: white;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    padding: 10px;
                    background-color: '. self::$main2_color .';
                    border-radius: 0 0 10px 10px;
                    transition: .3s ease;
                }
                .pwelement_'. self::$rnd_id .' .pwe-post-btn:hover  {
                    background-color: '. $darker_main2_color .';
                }

                .pwelement_'. self::$rnd_id .' .load-more-btn-container {
                    margin: 36px auto;
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .load-more-btn-container button {
                    min-width: 240px;
                    padding: 10px;
                    text-transform: uppercase;
                    font-size: 14px;
                    font-weight: 600;
                    transition: .3s ease;
                }
                @media (max-width: 960px) {
                    .pwelement_'. self::$rnd_id .' .pwe-post-excerpt {
                        font-size: 14px;
                    }
                }
            </style>';
        }

        $output .= '<div id="pwePosts" class="pwe-container-posts">

            <div class="pwe-posts-wrapper">';

                if ($posts_modes == "posts_slider_mode" || $posts_modes == "posts_simple_with_button_more") {

                    $posts_count = ($posts_count == "") ? 5 : $posts_count;

                    $output .= '
                    <div class="pwe-posts-title main-heading-text">
                        <h4 class="pwe-uppercase"><span>' . $posts_title . '</span></h4>
                    </div>
                    <div class="pwe-posts pwe-slides" role="group" aria-roledescription="carousel" aria-live="polite">';

                    $all_categories = get_categories(array('hide_empty' => true));

                    if (!empty($posts_category) && term_exists($posts_category, 'category')) {
                        // We only use categories from `$posts category`
                        $category_name = $posts_category;
                    } else {
                        $category_names = array();

                        foreach ($all_categories as $category) {
                            // Checks if the category name contains the word 'news'
                            if (strpos(strtolower($category->name), 'news') !== false) {
                                // Use slug instead of category name
                                $category_names[] = $category->slug;
                            }
                        }

                        $category_name = implode(', ', $category_names);
                    }

                    $max_posts = ($posts_all !== 'true') ? min($posts_count, 18) : -1;

                    $args = array(
                        'posts_per_page' => $max_posts,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'post_status' => 'publish',
                        'category_name' => $category_name,
                    );

                    $query = new WP_Query($args);

                    $posts_displayed = $query->post_count;

                    $post_image_urls = array();
                    if ($query->have_posts()) {
                        while ($query->have_posts()) : $query->the_post();
                            $link = get_permalink();
                            $image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
                            $title = get_the_title();

                            $post_image_urls[] = array(
                                "img" => $image,
                                "link" => $link,
                                "title" => $title
                            );

                            if (!empty($image) && !empty($link) && !empty($title)){
                                $output .= '
                                <a class="pwe-post" href="'. $link .'">
                                        <div class="pwe-post-thumbnail">
                                            <div class="image-container" style="background-image:url('. $image .');"></div>
                                        </div>';
                                        if($posts_modes == "posts_simple_with_button_more"){
                                            $output .= '
                                            <div class="description">
                                                <h4 class="pwe-post-title">'. $title .'</h4>
                                                <p>' . self::languageChecker('Więcej', 'More') . '
                                                    <span class="arrow-wrapper" style="font-size:18px; margin-left:6px;">
                                                        🡲
                                                    </span>
                                                </p>
                                            </div>
                                            ';
                                        } else {
                                            $output .= '<h4 class="pwe-post-title">'. $title .'</h4>';
                                        }


                                $output .= '</a>';
                            }
                        endwhile;
                    }

                    wp_reset_postdata();

                    $output .= '</div>

                    <span class="pwe-opinions__arrow pwe-opinions__arrow-prev pwe-arrow pwe-arrow-prev">‹</span>
                    <span class="pwe-opinions__arrow pwe-opinions__arrow-next pwe-arrow pwe-arrow-next">›</span>';

                    $posts_to_show = (!empty($posts_to_show)) ? $posts_to_show : 3;

                    include_once plugin_dir_path(__FILE__) . '/../scripts/slider.php';

                    $output .= PWESliderScripts::sliderScripts('posts', '.pwelement_'. self::$rnd_id, $posts_dots_display = 'true', $posts_arrows_display = false, $posts_to_show);
                    if($posts_modes == "posts_simple_with_button_more"){
                        $slider_id = 'pwelement_' . self::$rnd_id . ' .pwe-slides';
                        $output .= '
                        <input type="range" class="slider-range_' . self::$rnd_id . '" min="0" step="1">
                        <script>
                        jQuery(document).ready(function ($) {
                            const $slider = $(".pwelement_' . self::$rnd_id . ' .'.$slider_id.'");
                            const $range = $(".pwelement_' . self::$rnd_id . ' .slider-range_' . self::$rnd_id . '");

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
                        </script>';
                    }
                    if ($posts_btn !== "true") {
                        $output .= '
                        <div class="pwe-btn-container" style="padding-top: 18px;">
                            <span>
                                <a class="pwe-link btn pwe-btn" href="'. $posts_link .'">'. $posts_text .'</a>
                            </span>
                        </div>';
                    }
                } else if ($posts_modes == "posts_full_mode" || $posts_modes == "posts_full_newest_mode" || $posts_modes == "posts_full_newest_slider_mode" ) {

                    $output .= '<div class="pwe-posts pwe-slides" role="group" aria-roledescription="carousel" aria-live="polite">';

                    if ($posts_modes == "posts_full_mode") {
                        $posts_count = ($posts_count == "") ? 18 : $posts_count;
                        $max_posts = ($posts_all !== 'true') ? min($posts_count, 18) : -1;
                    } else if ($posts_modes == "posts_full_newest_mode") {
                        $posts_count = ($posts_count == "") ? 6 : $posts_count;
                        $max_posts = $posts_count;
                    } else if ($posts_modes == "posts_full_newest_slider_mode") {
                        $posts_count = ($posts_count == "") ? 10 : $posts_count;
                        $max_posts = ($posts_all !== 'true') ? min($posts_count, 10) : -1;
                    }

                    $all_categories = get_categories(array('hide_empty' => true));

                    if (!empty($posts_category) && term_exists($posts_category, 'category')) {
                        // We only use categories from `$posts category`
                        $category_name = $posts_category;
                    } else {
                        $category_names = array();

                        foreach ($all_categories as $category) {
                            // Checks if the category name contains the word 'news'
                            if (strpos(strtolower($category->name), 'news') !== false) {
                                // Use slug instead of category name
                                $category_names[] = $category->slug;
                            }
                        }

                        $category_name = implode(', ', $category_names);
                    }

                    $args = array(
                        'posts_per_page' => $max_posts,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'post_status' => 'publish',
                        'category_name' => $category_name
                    );

                    $query = new WP_Query($args);

                    $posts_displayed = $query->post_count;

                    $post_image_urls = array();

                    if ($query->have_posts()) {
                        while ($query->have_posts()) : $query->the_post();
                            $post_id = get_the_ID();
                            $word_count = 10;


                            $post_content = get_post_field('post_content', $post_id);
                            $excerpt = '';
                            $vc_content = '';

                            if (preg_match('/pwe_news_summary_desc="([^"]+)"/', $post_content, $matches)) {

                                $encoded = $matches[1];
                                $decoded = wpb_js_remove_wpautop(urldecode(base64_decode($encoded)), true);
                                $vc_content = $decoded;

                            } elseif (preg_match('/pwe_news_upcoming_desc="([^"]+)"/', $post_content, $matches)) {

                                $encoded = $matches[1];
                                $decoded = wpb_js_remove_wpautop(urldecode(base64_decode($encoded)), true);
                                $vc_content = $decoded;

                            } elseif (preg_match('/\[vc_column_text.*?\](.*?)\[\/vc_column_text\]/s', $post_content, $matches)) {

                                $vc_content = $matches[1];

                            }

                            $vc_content = wp_strip_all_tags($vc_content);

                            if (!empty($vc_content)) {
                                $words = preg_split('/\s+/', trim($vc_content));
                                $excerpt = implode(' ', array_slice($words, 0, $word_count)) . '...';
                            }

                            $link = get_permalink();
                            $image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
                            $title = get_the_title();
                            $date = get_the_date('Y-m-d'); // Get date in YYYY-MM-DD format

                            $title_words = explode(' ', $title);
                            if (count($title_words) > 8) {
                                $title = implode(' ', array_slice($title_words, 0, 8)) . '...';
                            }

                            // Format the date
                            $date_obj = new DateTime($date);
                            $formatted_date = $date_obj->format('d M'); // Format as DD Mmm

                            if (get_locale() == 'pl_PL') {
                                // Convert month abbreviations to Polish
                                $formatted_date = str_replace(
                                    array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
                                    array('sty', 'lut', 'mar', 'kwi', 'maj', 'cze', 'lip', 'sie', 'wrz', 'paź', 'lis', 'gru'),
                                    $formatted_date
                                );
                            }

                            $post_image_urls[] = array(
                                "img" => $image,
                                "link" => $link,
                                "title" => $title,
                                "excerpt" => $excerpt,
                                "date" => $formatted_date
                            );

                            if (!empty($image) && !empty($link) && !empty($title)){
                                $output .= '
                                <a class="pwe-post" href="'. $link .'">
                                    <div class="pwe-post-thumbnail">
                                        <div class="image-container" style="background-image:url('. $image .');"></div>
                                        <p class="pwe-post-date">'. $formatted_date .'</p>
                                    </div>
                                    <h4 class="pwe-post-title">'. $title .'</h4>
                                    <p class="pwe-post-excerpt">'. $excerpt .'</p>
                                    <button class="pwe-post-btn">' . self::languageChecker('CZYTAJ WIĘCEJ', 'READ MORE') . '</button>
                                </a>';
                            }

                        endwhile;
                    }

                    wp_reset_postdata();

                    $output .= '</div>';

                    if ($posts_modes == "posts_full_newest_slider_mode") {
                        $posts_to_show = (!empty($posts_to_show)) ? $posts_to_show : 3;

                        include_once plugin_dir_path(__FILE__) . '/../scripts/slider.php';
                        $output .= PWESliderScripts::sliderScripts('posts', '.pwelement_'. self::$rnd_id, $posts_dots_display = 'true', $posts_arrows_display = false, $posts_to_show);
                    }

                    if ($posts_modes == "posts_full_mode" && $posts_displayed == 18) {
                        $output .= '
                        <div class="load-more-btn-container">
                            <button id="load-more-posts" class="pwe-btn" data-offset="18">' . self::languageChecker('Załaduj więcej','Load more') . '</button>
                        </div>';
                    }

                }

            $output .= '
            </div>

        </div>';

        $output .= '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const pwePostsElement = document.querySelector(".pwelement_' . self::$rnd_id . ' .pwe-posts");
                const pweSliderElement = document.querySelector(".pwe-posts .slides");
                const pwePostsRow = document.querySelector(".row-container:has(.pwe-posts)");
                pwePostsElement.style.opacity = 1;
                pwePostsElement.style.transition = "opacity 0.3s ease";
                if ((pwePostsElement && pwePostsElement.children.length === 0) || (pweSliderElement && pweSliderElement.children.length === 0)) {
                    pwePostsRow.classList.add("desktop-hidden", "tablet-hidden", "mobile-hidden");
                }

                const loadMoreButton = document.getElementById("load-more-posts");

                if (loadMoreButton) {
                    loadMoreButton.addEventListener("click", function() {
                        const button = this;
                        const offset = parseInt(button.getAttribute("data-offset"));

                        button.innerText = "' . self::languageChecker('Ładowanie...','Loading...') . '";
                        button.disabled = true;

                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "' . admin_url('admin-ajax.php') . '", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 400) {
                                const response = xhr.responseText;
                                const container = document.querySelector(".pwe-posts");
                                container.insertAdjacentHTML("beforeend", response);

                                const newOffset = offset + 18;
                                button.setAttribute("data-offset", newOffset);

                                // Check if all posts have been loaded
                                if (response.trim() === "") {
                                    button.remove();
                                } else {
                                    button.innerText = "' . self::languageChecker('Załaduj więcej','Load more') . '";
                                    button.disabled = false;
                                }
                            }
                        };
                        xhr.send("action=load_more_posts&offset=" + offset);
                    });
                }
            });
        </script>';

        if ($posts_modes == 'posts_syncing_slider_mode') {
            $max_posts = !empty($posts_count) ? intval($posts_count) : 10;

            $args = array(
                'posts_per_page' => $max_posts,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_status'    => 'publish',
            );

            if (!empty($posts_category) && term_exists($posts_category, 'category')) {
                $args['category_name'] = $posts_category;
            }

            $query = new WP_Query($args);
            $posts_data = array();

            if ($query->have_posts()) {
                while ($query->have_posts()) : $query->the_post();
                    $word_count = 10;

                    // Get post content
                    $post_content = get_post_field('post_content', $post_id);

                    // Extract content inside [vc_column_text] shortcode
                    preg_match('/\[vc_column_text.*?\](.*?)\[\/vc_column_text\]/s', $post_content, $matches);
                    $vc_content = isset($matches[1]) ? $matches[1] : '';

                    // Remove HTML
                    $vc_content = wp_strip_all_tags($vc_content);

                    // Check if the content is not empty
                    if (!empty($vc_content)) {
                        // Split content into words
                        $words = explode(' ', $vc_content);

                        // Extract the first $word_count words
                        $excerpt = array_slice($words, 0, $word_count);

                        // Combine words into one string
                        $excerpt = implode(' ', $excerpt);

                        // Add an ellipsis at the end
                        $excerpt .= '...';
                    } else {
                        $excerpt = '';
                    }

                    $posts_data[] = array(
                        'title' => get_the_title(),
                        'link'  => get_permalink(),
                        'image' => has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '',
                        'description' => $excerpt,
                        'date' => get_the_date('d.m.Y'),
                    );
                endwhile;
                wp_reset_postdata();
            }

            if (count($posts_data) >= 4) {
                return self::outputSliderSyncing($atts, $posts_data, $posts_title);
            } else {
                $atts['posts_modes'] = 'posts_slider_mode';
                return self::output($atts);
            }
        }

        return $output;

    }
}