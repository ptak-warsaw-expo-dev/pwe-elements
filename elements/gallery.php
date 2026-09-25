<?php

/**
 * Class PWElementHomeGallery
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementHomeGallery extends PWElements {
    public static $countdown_rnd_id;
    public static $today_date;

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();

        self::$countdown_rnd_id = rand(10000, 99999);
        self::$today_date = new DateTime();

        require_once plugin_dir_path(__FILE__) . 'countdown.php';
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'dropdown',
                'heading' => __('Select link color <a href="#" onclick="yourFunction(`link_color_manual_hidden`, `link_color`)">Hex</a>', 'pwelement'),
                'param_name' => 'link_color',
                'param_holder_class' => 'main-options',
                'description' => __('Select link color for the element.', 'pwelement'),
                'value' => self::$fair_colors,
                'dependency' => array(
                    'element' => 'link_color_manual_hidden',
                    'value' => array(''),
                    'callback' => "hideEmptyElem",
                ),
                'save_always' => true,
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Write link color <a href="#" onclick="yourFunction(`link_color`, `link_color_manual_hidden`)">Pallet</a>', 'pwelement'),
                'param_name' => 'link_color_manual_hidden',
                'param_holder_class' => 'main-options pwe_dependent-hidden',
                'description' => __('Write hex number for link color for the element.', 'pwelement'),
                'value' => '',
                'save_always' => true,
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Select link hover color <a href="#" onclick="yourFunction(`link_hover_color_manual_hidden`, `link_hover_color`)">Hex</a>', 'pwelement'),
                'param_name' => 'link_hover_color',
                'param_holder_class' => 'main-options',
                'description' => __('Select link hover color for the element.', 'pwelement'),
                'value' => self::$fair_colors,
                'dependency' => array(
                    'element' => 'link_hover_color_manual_hidden',
                    'value' => array(''),
                    'callback' => "hideEmptyElem",
                ),
                'save_always' => true,
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Write link hover color <a href="#" onclick="yourFunction(`link_hover_color`, `link_hover_color_manual_hidden`)">Pallet</a>', 'pwelement'),
                'param_name' => 'link_hover_color_manual_hidden',
                'param_holder_class' => 'main-options pwe_dependent-hidden',
                'description' => __('Write hex number for link hover color for the element.', 'pwelement'),
                'value' => '',
                'save_always' => true,
            ),            
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Header text', 'pwelement'),
                'param_name' => 'header_text',
                'description' => __('Set up a pwe hader text'),
                'param_holder_class' => 'backend-area-one-third-width',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Registration button text', 'pwelement'),
                'param_name' => 'button_text',
                'description' => __('Set up a pwe button text'),
                'param_holder_class' => 'backend-area-one-third-width',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Registration button URL', 'pwelement'),
                'param_name' => 'button_url',
                'description' => __('Set up a pwe button url'),
                'param_holder_class' => 'backend-area-one-third-width',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Text for desktop', 'pwelement'),
                'param_name' => 'desktop_text',
                'description' => __('Set up a pwe desktop description'),
                'param_holder_class' => 'backend-textarea-raw-html backend-area-one-fourth-width',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Text for mobile', 'pwelement'),
                'param_name' => 'mobile_text',
                'description' => __('Set up a pwe mobile description'),
                'param_holder_class' => 'backend-textarea-raw-html backend-area-one-fourth-width',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('HTML Text', 'pwelement'),
                'param_name' => 'gallery_html_text',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('HTML Text Hidden', 'pwelement'),
                'param_name' => 'gallery_html_text_hidden',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide Button', 'pwelement'),
                'param_name' => 'hide_button',
                'description' => __('Turn on to hide registration button'),
                'param_holder_class' => 'backend-basic-checkbox backend-area-one-fourth-width',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Add timer', 'pwelement'),
                'param_name' => 'add_timer',
                'description' => __('Add countdown timer to element'),
                'param_holder_class' => 'backend-basic-checkbox backend-area-one-fourth-width',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Conference', 'pwelement'),
                'param_name' => 'gallery_new',
                'param_holder_class' => 'backend-basic-checkbox backend-area-one-fourth-width',
                'description' => __('New visual'),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementHomeGallery',
                ),
                'save_always' => true,
                'admin_label' => true
            ),
        );
        return $element_output;
    }

    /**
     * Static private method to remove from JS out of date timer variables.
     *      *
     * @param array @right_date array off only new timers
     */
    private static function mainText() {
        if(self::checkForMobile()){
            return self::languageChecker(
                <<<PL
                    [trade_fair_name] to wydarzenie branżowe, którego celem jest zgromadzenie czołowych firm, ekspertów technicznych i praktyków związanych z sektorem w Polsce i całym regionie środkowo wschodniej Europy.
                PL,
                <<<EN
                    [trade_fair_name_eng] is a industry event that aims to bring together leading companies, technical experts and practitioners from Poland and the entire Central and Eastern European region.
                EN
            );
        } else {
            return self::languageChecker(
                <<<PL
                    [trade_fair_name] to wydarzenie branżowe, którego celem jest zgromadzenie czołowych firm, ekspertów technicznych i praktyków związanych z sektorem w Polsce i całym regionie środkowo wschodniej Europy. Targi oferują doskonałą okazję do nawiązania relacji biznesowych, prezentacji innowacyjnych technologii oraz wymiany wiedzy i doświadczeń. [trade_fair_name] to miejsce, gdzie innowacje spotykają się z praktycznym zapotrzebowaniem, a potencjał branży jest wykorzystywany do maksimum.
                PL,
                <<<EN
                    [trade_fair_name_eng] is a industry event that aims to bring together leading companies, technical experts and practitioners from Poland and the entire Central and Eastern European region. The fair offers an excellent opportunity to establish business relationships, showcase innovative technologies and exchange knowledge and experience. [trade_fair_name_eng] is a place where innovation meets practical demand, and the potential of the industry is exploited to the maximum.
                EN
            );
        }
    }
    /**
     * Static private method to remove from JS out of date timer variables.
     *
     * @param array @right_date array off only new timers
     */
    public static function output($atts, $content = '') {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color);
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], $btn_color);

        $link_color = self::findColor($atts['link_color_manual_hidden'], $atts['link_color'], self::$main2_color) . '!important';
        $link_hover_color = self::findColor($atts['link_hover_color_manual_hidden'], $atts['link_hover_color'], self::$accent_color) . '!important';

        $darker_btn_color = self::adjustBrightness($btn_color, -20);
        $darker_main2_btn_color = self::adjustBrightness(self::$main2_color, -20);
        $darker_light_btn_color = self::adjustBrightness('#ffffff', -30);

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        wp_enqueue_style('slick-slider-css', plugins_url('../assets/slick-slider/slick.css', __FILE__));
        wp_enqueue_style('slick-slider-theme-css', plugins_url('../assets/slick-slider/slick-theme.css', __FILE__));
        wp_enqueue_script('slick-slider-js', plugins_url('../assets/slick-slider/slick.min.js', __FILE__), array('jquery'), null, true);

        extract( shortcode_atts( array(
            'gallery_new' => '',
            'mobile_text' => '',
            'desktop_text' => '',
            'gallery_html_text' => '',
            'gallery_html_text_hidden' => '',
        ), $atts ));

        if(self::checkForMobile()){
            $gallery_text = ($mobile_text != '') ? $mobile_text : self::mainText();
        } else {
            $gallery_text = ($desktop_text != '') ? $desktop_text : self::mainText();
        }

        $gallery_html_text_decoded = base64_decode($gallery_html_text);
        $gallery_html_text_decoded = urldecode($gallery_html_text_decoded);
        $gallery_html_text_content = wpb_js_remove_wpautop($gallery_html_text_decoded, true);

        $gallery_html_text_hidden_decoded = base64_decode($gallery_html_text_hidden);
        $gallery_html_text_hidden_decoded = urldecode($gallery_html_text_hidden_decoded);
        $gallery_html_text_hidden_content = wpb_js_remove_wpautop($gallery_html_text_hidden_decoded, true);

        if ($gallery_new != true) {
            $btn_gallery_text = ($atts['button_text'] != '')
            ? $atts['button_text']
            : self::languageChecker(
                <<<PL
                Weź udział
                PL,
                <<<EN
                Take a part
                EN
            );
        } else {
            $btn_gallery_text = ($atts['button_text'] != '')
            ? $atts['button_text']
            : self::languageChecker(
                <<<PL
                Sprawdź program
                PL,
                <<<EN
                Check the program
                EN
            );
        }

        if ($gallery_new != true) {
            $gallery_title = ($atts['header_text'] != '') ? $atts['header_text'] : self::languageChecker('[trade_fair_desc]','[trade_fair_desc_eng]');
            $btn_gallery_url = ($atts['button_url'] != '') ? $atts['button_url'] : self::languageChecker('/rejestracja/', '/en/registration/');
        } else {
            $gallery_title = ($atts['header_text'] != '') ? $atts['header_text'] : self::languageChecker('[trade_fair_conferance]','[trade_fair_conferance_eng]');
            $btn_gallery_url = ($atts['button_url'] != '') ? $atts['button_url'] : self::languageChecker('#program', '#program');
        }
        $gallery_title = str_replace(array('`{`', '`}`'), array('[', ']'), $gallery_title);

        $all_images = self::findAllImages('/doc/galeria/mini', 4);

        $output = '';

        $output .= '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-btn-black {
                    border-radius: 10px;
                    color: #ffffff;
                    background-color: '. self::$main2_color .';
                    border: 1px solid '. self::$main2_color .';
                    margin: auto 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-btn-white {
                    border-radius: 10px;
                    color: black;
                    background-color: white;
                    border: 1px solid white;
                    margin: auto 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-btn-black:hover {
                    color: '. $btn_text_color .'!important;
                    background-color: '. $darker_main2_btn_color .'!important;
                    border: 1px solid '. $darker_main2_btn_color .'!important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-btn-white:hover {
                    color: black !important;
                    background-color: '. $darker_light_btn_color .' !important;
                    border: 1px solid '. $darker_light_btn_color .' !important;
                }
                .row-parent:has(.pwelement_'. self::$rnd_id .' .pwe-container-gallery) {
                    background: ' . self::$accent_color . ';
                    max-width: 100%;
                    padding: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-wrapper {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 18px 36px 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-section {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    gap: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-wrapper, .pwe-gallery-desc-wrapper {
                    width: 50%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs {
                    display: flex;
                    flex-wrap: wrap;
                    width: 100%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs img {
                    width: 100%;
                    padding: 5px;
                    border-radius: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc {
                    background-color: #eaeaea;
                    border-radius: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-wrapper .pwe-btn-container,
                .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-wrapper .pwe-btn-container {
                    display: flex;
                    justify-content: left;
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-content h3,
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-content h3 p {
                    font-size: 21px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-content .pwe-link {
                    color: '. $btn_text_color .' !important;
                    background-color: '. $btn_color .' !important;
                    border: 1px solid '. $btn_border .' !important;
                    transform: none !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-link {
                    transform: none !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-content .pwe-link:hover {
                    color: '. $btn_text_color .' !important;
                    background-color: '. $darker_btn_color .' !important;
                    border: 1px solid '. $darker_btn_color .' !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-see-more,
                .pwelement_'. self::$rnd_id .' .pwe-hidden-content p {
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-paragraph {
                    margin-top: 12px !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-paragraph p {
                    display:inline !important;
                }
                .pwelement_'. self::$rnd_id .' #pweGallery .pwe-gallery-desc-paragraph a {
                    color: '. $link_color .';
                } 
                .pwelement_'. self::$rnd_id .' #pweGallery .pwe-gallery-desc-paragraph a:hover {
                    color: '. $link_hover_color .';
                } 
                .pwelement_'. self::$rnd_id .' .pwe-btn-box {
                    margin-top: 8px;
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    justify-content: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-btn-container {
                    padding-top: 0 !important;
                }   
                @media (min-width: 961px) {
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-item {
                        width: 50% !important;
                    }
                }
                @media (max-width: 960px) {
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-wrapper {
                        padding: 36px 36px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-section {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-wrapper {
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-wrapper {
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-btn-container.mobile {
                        display: flex;
                    }
                }
                @media (max-width: 600px) {
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-top .mini-img:nth-of-type(2),
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-bottom .mini-img:nth-of-type(1),
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-bottom .mini-img:nth-of-type(2) {
                        display: none;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-top  .mini-img {
                        width: 90% !important;
                        margin:0 auto;
                    }
                }
                @media (max-width: 500px) {
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-section {
                        margin: -20px 0;
                    }

                    .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-wrapper .pwe-btn-container,
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-wrapper .pwe-btn-container {
                        justify-content: center;
                    }
                }
            </style>';

            if ($gallery_new == true) {
                $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-btn-box {
                        justify-content: start;
                    }
                    @media (max-width: 960px) {
                        .pwelement_'. self::$rnd_id .' .pwe-btn-box {
                            justify-content: center;
                        }
                    }
                </style>';
            }

            $output .= '
            <div id="pweGallery" class="pwe-container-gallery style-accent-bg">
                <div class="pwe-gallery-wrapper">
                    <div class="pwe-gallery-section">
                        <div class="pwe-gallery-thumbs-wrapper">
                            <div class="pwe-gallery-thumbs">
                                <div class="pwe-gallery-thumbs-item"><img class="mini-img" src="' . $all_images[0] . '" alt="mini galery picture"></div>
                                <div class="pwe-gallery-thumbs-item"><img class="mini-img" src="' . $all_images[1] . '" alt="mini galery picture"></div>
                                <div class="pwe-gallery-thumbs-item"><img class="mini-img" src="' . $all_images[2] . '" alt="mini galery picture"></div>
                                <div class="pwe-gallery-thumbs-item"><img class="mini-img" src="' . $all_images[3] . '" alt="mini galery picture"></div>      
                            </div>
                            <div class="pwe-btn-box">';

                            if ($gallery_new != true) {
                                $output .= '
                                <span class="pwe-btn-container gallery-link-btn mobile" style="display: none;">'.
                                    self::languageChecker(
                                        <<<PL
                                            <a class="pwe-link btn pwe-btn pwe-btn-black" href="/#profil-wystawcy" alt="link do galerii">Profil Wystawcy</a>
                                        PL,
                                        <<<EN
                                            <a class="pwe-link btn pwe-btn pwe-btn-black" href="/en/#profil-wystawcy" alt="link to gallery">Exhibitor Profile</a>
                                        EN
                                    )
                                .'</span>';
                                
                                $output .= '
                                <span class="pwe-btn-container gallery-link-btn">'.
                                    self::languageChecker(
                                        <<<PL
                                            <a class="pwe-link btn pwe-btn pwe-btn-white" href="/#zakres-branzowy" alt="link do galerii">Zakres branżowy</a>
                                        PL,
                                        <<<EN
                                            <a class="pwe-link btn pwe-btn pwe-btn-white" href="/en/#zakres-branzowy" alt="link to gallery">Industry scope</a>
                                        EN
                                    )
                                .'</span>';
                            }
                            
                            $output .= '
                                <span class="pwe-btn-container gallery-link-btn">'.
                                    self::languageChecker(
                                        <<<PL
                                            <a class="pwe-link btn pwe-btn pwe-btn-black" href="/galeria/" alt="link do galerii">Przejdź do galerii</a>
                                        PL,
                                        <<<EN
                                            <a class="pwe-link btn pwe-btn pwe-btn-black" href="/en/gallery/" alt="link to gallery">Go to gallery</a>
                                        EN
                                    )
                                .'</span>
                            </div>
                        </div>';

                        $output .= '
                        <div class="pwe-gallery-desc-wrapper">
                            <div class="pwe-gallery-desc">
                                <div class="pwe-gallery-desc-content single-block-padding pwe-align-left">
                                    <h3 style="margin: 0;"> '. wpb_js_remove_wpautop($gallery_title, true) .' </h3>
                                    <div class="pwe-gallery-desc-paragraph">';

                                        if (!empty($gallery_html_text_content)) {
                                            $output .= '' . $gallery_html_text_content . '';
                                            if (!empty($gallery_html_text_hidden_content)) {
                                                $showMore = get_locale() == "pl_PL" ? "więcej..." : "more...";
                                                $output .= '
                                                    <span class="pwe-hidden-content" style="display: none; color: '. $text_color .';">' . $gallery_html_text_hidden_content . '</span>
                                                    <div class="pwe-see-more" style="cursor: pointer; color: '. $text_color .';">' . $showMore . '</div>';
                                            }
                                        } else {
                                            $output .= '<p>' . $gallery_text . '</p>';
                                        }

                                    $output .= '
                                    </div>';

                                    if ($atts['hide_button'] != 'true') {
                                        $output .= '<span class="pwe-btn-container register-link-btn">
                                                        <a style="margin-top: 18px;" class="pwe-link pwe-btn btn" href="' . $btn_gallery_url . '" alt="link do rejestracji">' . $btn_gallery_text . '</a>
                                                    </span>';
                                    }

                                $output .= '
                                    </div>
                                </div>';

                            if($atts['add_timer']){
                                $output .='<div class="uncode-wrapper uncode-countdown timer-countdown" id="timerToGallery">';
                                $output .= PWElementMainCountdown::output($atts);
                                $output .= '</div>';
                            }

                        $output .= '
                        </div>
                    </div>
                </div>
            </div>';



            $output .= '
            <script>
            if (window.matchMedia("(max-width: 960px)").matches) {
                jQuery(function ($) {
                    $(".pwe-gallery-thumbs").slick({
                            infinite: true,
                            lazyLoad: "false",
                            slidesToShow: 4,
                            slidesToScroll: 1,
                            arrows: false,
                            autoplay: true,
                            autoplaySpeed: 3000,
                            dots: false,
                            cssEase: "linear",
                            responsive: [
                                    {
                                            breakpoint: 960,
                                            settings: { slidesToShow: 2, slidesToScroll: 1, }
                                    },
                                    {
                                            breakpoint: 500,
                                            settings: { slidesToShow: 1, slidesToScroll: 1, }
                                    }  
                            ] 
                    });  
                    
                });      
            }     
            </script>'; 

    return $output;
    }
}