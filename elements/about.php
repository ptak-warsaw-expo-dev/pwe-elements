<?php

/**
 * Class PWElementAbout
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementAbout extends PWElements {

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
                'heading' => __('Select looking form', 'pwelement'),
                'param_name' => 'about_select',
                'save_always' => true,
                'value' => array(
                    'orginal' => 'orginal',
                    '80% 20%' => 'difrent_size',
                    '50% 50% resize' => 'same_size_resize',
                ),
                'std' => 'visitors',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Left text', 'pwelement'),
                'param_name' => 'pwe_about_left_text',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Right text (description)', 'pwelement'),
                'param_name' => 'pwe_about_right_text',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Left image src', 'pwelement'),
                'param_name' => 'pwe_about_left_img_src',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Right image src', 'pwelement'),
                'param_name' => 'pwe_about_right_img_src',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Left button link', 'pwelement'),
                'param_name' => 'pwe_about_left_btn_url',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Right button link', 'pwelement'),
                'param_name' => 'pwe_about_right_btn_url',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),

            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Left short text', 'pwe_element'),
                'param_name' => 'pwe_about_short_left_text',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Right short text', 'pwe_element'),
                'param_name' => 'pwe_about_short_right_text',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementAbout',
                ),
            ),
        );
        return $element_output;
    }

    public static function output($atts) {

        extract( shortcode_atts( array(
            'about_select' => '',
            'pwe_about_left_text' => '',
            'pwe_about_right_text' => '',
            'pwe_about_left_img_src' => '',
            'pwe_about_right_img_src' => '',
            'pwe_about_left_btn_url' => '',
            'pwe_about_right_btn_url' => '',
            'pwe_about_short_left_text' => '',
            'pwe_about_short_right_text' => '',
        ), $atts ));

        if (PWECommonFunctions::lang_pl()) {
            $default_title_text = '<h4>Odkryj rynkowe nowości i spotkaj liderów branż – wszystko w jednym miejscu i czasie!</h4>';
            $default_desc_text = '<p>[trade_fair_name] to jedyne w Polsce wydarzenie dedykowane branży kabli, które łączy innowacje, technologie i ekspertów. Targi to doskonała okazja do nawiązywania kontaktów i odkrywania najnowszych trendów w tej dziedzinie.</p>';

            $pwe_about_left_btn_url = (!empty($pwe_about_left_btn_url)) ? $pwe_about_left_btn_url : '/poznaj-targi/';
            $pwe_about_right_btn_url = (!empty($pwe_about_right_btn_url)) ? $pwe_about_right_btn_url : '/wydarzenia';
        } else {
            $default_title_text = '<h4>Discover market news and meet industry leaders – all in one place and time!</h4>';
            $default_desc_text = '<p>[trade_fair_name] is the only event in Poland dedicated to the cable industry, which combines innovation, technology and experts. The fair is a great opportunity to establish contacts and discover the latest trends in this field.</p>';

            $pwe_about_left_btn_url = (!empty($pwe_about_left_btn_url)) ? $pwe_about_left_btn_url : '/en/explore-the-fair/';
            $pwe_about_right_btn_url = (!empty($pwe_about_right_btn_url)) ? $pwe_about_right_btn_url : '/en/conferences/';
        }

        $title_text = PWECommonFunctions::decode_clean_content($pwe_about_left_text);
        $desc_text = PWECommonFunctions::decode_clean_content($pwe_about_right_text);

        $pwe_about_short_left_text = PWECommonFunctions::decode_clean_content($pwe_about_short_left_text);
        $pwe_about_short_right_text = PWECommonFunctions::decode_clean_content($pwe_about_short_right_text);

        $title_text = !empty($title_text) ? $title_text : $default_title_text;
        $desc_text = !empty($desc_text) ? $desc_text : $default_desc_text;


        if($about_select =='orginal'){

            $pwe_about_left_img_src = (!empty($pwe_about_left_img_src)) ? $pwe_about_left_img_src : '/wp-content/plugins/pwe-media/media/poznaj-targi.jpg';
            $pwe_about_right_img_src = (!empty($pwe_about_right_img_src)) ? $pwe_about_right_img_src : '/wp-content/plugins/pwe-media/media/poznaj-konferencje.jpg';

            $output = '
            <style>
                .pwe-discover-btns .pwe-discover-btns__container_text h3,
                .pwe-discover-btns .pwe-discover-btns__container_text p {
                    color: white;
                }
                .pwe-discover-btns .pwe-discover-btns__heading {
                    display: flex;
                    gap: 30px;
                }
                .pwe-discover-btns .pwe-discover-btns__heading_left,
                .pwe-discover-btns .pwe-discover-btns__heading_right {
                    flex: .50;
                    color: black;
                }
                .pwe-discover-btns .pwe-discover-btns__heading_left p,
                .pwe-discover-btns .pwe-discover-btns__heading_right p {
                    font-size: 20px;
                    font-weight: 500;
                }
                .pwe-discover-btns .pwe-discover-btns__heading_left :is(h2, h3, h4, h5, h6, p, span),
                .pwe-discover-btns .pwe-discover-btns__heading_right :is(h2, h3, h4, h5, h6, p, span) {
                    line-height: 1.3;
                    margin: 0;
                }
                .pwe-discover-btns .pwe-discover-btns__container {
                    margin-top: 40px;
                    display: flex;
                    gap: 20px;
                }
                .pwe-discover-btns__container_image {
                    flex: .5;
                    display: flex;
                    justify-content: center;
                    flex-direction: column;
                    align-items: center;
                    position: relative;
                    overflow: hidden;
                    border-radius: 30px;
                }
                .pwe-discover-btns__container_image img {
                    padding: 6px 0;
                }
                .pwe-discover-btns__container_image_left,
                .pwe-discover-btns__container_image_right {
                    transition: .3s ease;
                }
                .pwe-discover-btns__container_image:hover .pwe-discover-btns__container_image_left,
                .pwe-discover-btns__container_image:hover .pwe-discover-btns__container_image_right {
                    transform: scale(1.1);
                }
                .pwe-discover-btns__container_image_left,
                .pwe-discover-btns__container_image_right {
                    background-size: cover !important;
                    background-repeat: no-repeat !important;
                    min-height: 500px;
                    width: 100%;
                    filter: brightness(70%);
                    -webkit-filter: brightness(50%);
                }
                .pwe-discover-btns__container_image_left {
                    background: url('. $pwe_about_left_img_src .');
                }
                .pwe-discover-btns__container_image_right {
                    background: url('. $pwe_about_right_img_src .');
                }
                .pwe-discover-btns__container_text {
                    position: absolute;
                    top: 50%;
                    left: 45%;
                    transform: translate(-45%, -50%);
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                .pwe-discover-btns__container_text a {
                    transition: all .2s linear;
                    text-align: center;
                }
                .pwe-discover-btns__container_text a:hover{
                    background: white;
                    color: black !important
                }
                .pwe-discover-btns__container_text p {
                    text-transform: uppercase;
                }
                .pwe-discover-btns__container_image p {
                    font-size: 24px;
                    font-weight: 600;
                    color: white;
                    margin: 0;
                    text-align: center;
                    line-height: 1.2;
                }
                .pwe-discover-btns__container_image a {
                    text-decoration: none;
                    color: white;
                    border: 1px solid white;
                    padding: 10px 20px;
                    border-radius: 10px;
                    margin-top: 8px;
                }
                @media(max-width:960px) {
                    .pwe-discover-btns__container_image_left,
                    .pwe-discover-btns__container_image_right {
                        min-height: 300px;
                    }
                    .pwe-discover-btns .pwe-discover-btns__heading_left p,
                    .pwe-discover-btns .pwe-discover-btns__heading_right p {
                        font-size: 16px;
                    }
                }
                @media(max-width:560px) {
                    .pwe-discover-btns__heading,
                    .pwe-discover-btns__container {
                        flex-direction: column;
                    }
                }
            </style>

            <div id="pweDiscoverBtns" class="pwe-discover-btns">
                <div class="pwe-discover-btns__wrapper">
                    <div class="pwe-discover-btns__heading">
                        <div class="pwe-discover-btns__heading_left">
                            '. $title_text .'
                        </div>
                        <div class="pwe-discover-btns__heading_right">
                            '. $desc_text .'
                        </div>
                    </div>

                    <div class="pwe-discover-btns__container">
                        <div class="pwe-discover-btns__container_image ">
                            <div class="pwe-discover-btns__container_image_left">
                            </div>
                            <div class="pwe-discover-btns__container_text">
                            <p>'. self::languageChecker('Poznaj Targi', 'Discover the Fair') .'</p>
                            <img src="/doc/logo.webp">
                            <a href="'. $pwe_about_left_btn_url .'">'. self::languageChecker('Dowiedz się więcej', 'Learn more') .'</a>
                            </div>
                        </div>
                        <div class="pwe-discover-btns__container_image ">
                            <div class="pwe-discover-btns__container_image_right"></div>
                            <div class="pwe-discover-btns__container_text">
                            <p>'. self::languageChecker('Poznaj Konferencje', 'Discover Conferences') .'</p>
                            <img src="/doc/kongres.webp">
                            <a href="'. $pwe_about_right_btn_url .'">'. self::languageChecker('Dowiedz się więcej', 'Learn more') .'</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        } else if($about_select =='difrent_size') {

            $pwe_about_left_img_src = (!empty($pwe_about_left_img_src)) ? $pwe_about_left_img_src : '/doc/logo.webp';
            $pwe_about_right_img_src = (!empty($pwe_about_right_img_src)) ? $pwe_about_right_img_src : '/doc/kongres.webp';

            $output ='
            <div id="about-us" class="about-us">
                <div class="about-us_container">
                    <div class="about-us_container_image about-us_container-left">
                        <div class="about-us_container_text">
                            <div>
                                <h3>'. self::languageChecker('O targach', 'About the fair') .'</h3>
                                <img src="'. $pwe_about_left_img_src .'">
                            </div>
                            '. $title_text .'
                        </div>
                        <a href="/rejestracja">'. self::languageChecker('WEŹ UDZIAŁ', 'TAKE PARTICIPATION') .'</a>
                    </div>
                    <div class="about-us_container_image about-us_container-right">
                        <div class="about-us_container_text">
                            <h3>'. self::languageChecker('O KONFERENCJI', 'ABOUT THE CONFERENCE') .'</h3>
                            '. $desc_text .'
                            <img src="/doc/kongres.webp">
                        </div>
                        <a href="/wydarzenia">'. self::languageChecker('Dowiedz się więcej', 'LEARN MORE') .'</a>
                    </div>
                </div>
            </div>

            <style>
                .about-us .about-us_container {
                margin-top: 40px;
                display: flex;
                gap: 20px;
            }

            .about-us_container .about-us_container-left,
            .about-us_container .about-us_container-right {
                position: relative;
                overflow: visible;
            }
            .about-us_container .about-us_container-left {
                flex:0.75;
            }
            .about-us_container .about-us_container-right {
                flex:0.25;
            }
            .about-us_container .about-us_container-left::before,
            .about-us_container .about-us_container-right::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                filter: brightness(30%);
                z-index: 1;
                border-radius: 30px;
            }

            .about-us_container .about-us_container-left::before {
                background-image: url(/wp-content/plugins/pwe-media/media/poznaj-targi.jpg);
            }

            .about-us_container .about-us_container-right::before {
                background-image: url(/wp-content/plugins/pwe-media/media/poznaj-konferencje.jpg);
            }

            .about-us_container_image {
                display: flex;
                justify-content: center;
                flex-direction: column;
                align-items: center;
                position: relative;
                z-index: 2; /* Tekst i przyciski nad pseudo-elementem */
            }

            .about-us_container_text {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-evenly;
                z-index: 3;
                margin-bottom: 45px;
                padding: 0 18px;
            }

            .about-us_container_text :is(h2, h3, h4, h5, h6, p, span) {
                color: white;
                text-align: center;
            }

            .about-us_container_text div {
                display: flex;
                justify-content: space-evenly;
                align-items: center;
                width: 100%;
                margin: 24px 0;
            }

            .about-us_container_text div img {
                height: auto;
                max-width: 320px;
            }

            .about-us_container a {
                position: absolute;
                bottom: -41px;
                left: 50%;
                transform: translate(-50%, -50%);
                background: ' .self::$accent_color .';
                text-align: center;
                text-decoration: none;
                color: white;
                border: 1px solid white;
                text-transform: uppercase;
                font-size: 15px;
                min-width: 200px;
                font-weight: 600;
                padding: 10px 5px;
                border-radius: 10px;
                min-width: 150px;
                transition: all 0.2s linear;
                z-index: 3;
            }

            .about-us_container a:hover {
                color: white !important;
            }

            .about-us_container_image p,
            .about-us_container h3 {
                font-size: 18px;
                font-weight: 500;
                color: white;
                margin: 0;
                text-align: center;
                line-height: 1.3;
                z-index: 3;
            }

            .about-us_container h3 {
                font-size: 24px;
                text-transform: uppercase;
                letter-spacing: 2px;
                margin: 24px 0;
            }

            .about-us_container-right a {
                min-width: 195px;
            }

            .about-us_container-right .about-us_container_text img {
                max-width: 70%;
            }
            .about-us_container-right img {
                margin-top:30px;
            }
            /* Responsive Design */
            @media (max-width: 1200px) {
                .about-us_container .about-us_container-left {
                    flex:0.7;
                }
                .about-us_container .about-us_container-right {
                    flex:0.3;
                }
                .about-us_container_image p {
                    font-size: 16px;
                }
            }

            @media (max-width: 960px) {
                .about-us_container .about-us_container-left {
                    flex:0.6;
                }
                .about-us_container .about-us_container-right {
                    flex:0.4;
                }
                .about-us_container_text div img {
                    max-width: 250px;
                    width: 100%;
                }
                .about-us_container_text div {
                    flex-wrap: wrap;
                    flex-direction: column;
                }
            }

            @media (max-width: 760px) {
                .about-us_heading,
                .about-us_container {
                    flex-direction: column;
                }

                .about-us .about-us_container {
                    gap: 35px;
                }

                .about-us_container_image p {
                    font-size: 16px;
                }
            }

            @media (max-width: 620px) {
                .about-us_container_text div {
                    flex-direction: column;
                }
            }
            </style>';
        } else if($about_select == 'same_size_resize') {
            $output .= '
            <style>
                .pwe-discover-btns {
                    width: 100%;
                }

                .pwe-discover-btns .pwe-discover-btns__container {
                    display: flex;
                    gap: 20px;
                    margin-top: 40px;
                }

                .pwe-discover-btns__container_image {
                    flex-grow: 1;
                    position: relative;
                    overflow: hidden;
                    border-radius: 30px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    transition: flex-grow 0.5s ease;
                    cursor: pointer;
                }

                .pwe-discover-btns__container_image.expanded {
                    flex-grow: 3;
                }

                .pwe-discover-btns__container_image_left,
                .pwe-discover-btns__container_image_right {
                    position: relative;
                    background-size: cover;
                    background-repeat: no-repeat;
                    min-height: 500px;
                    width: 100%;
                    filter: brightness(20%);
                    transition: filter 0.5s ease;
                }

                .pwe-discover-btns__container_image_left {
                    background-image: url("https://piotrek.targibiurowe.com/wp-content/uploads/2023/05/DSA03787-1.jpg");
                }

                .pwe-discover-btns__container_image_right {
                    background-image: url("https://piotrek.targibiurowe.com/wp-content/uploads/2023/05/DSA04389_1-1.jpg");
                }

                .pwe-discover-btns__container_text {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    text-align: center;
                    color: white;
                    width:90%;
                    min-height: 400px;
                    display: flex;
                    flex-direction: column;
                    align-items: stretch;
                    justify-content: space-around;
                }

                .pwe-discover-btns__container_text a {
                    display: block;
                    color: white;
                    border: 1px solid white;
                    padding: 10px 20px;
                    border-radius: 10px;
                    text-decoration: none;
                    margin-top: 10px;
                    transition: background 0.3s ease, color 0.3s ease;
                    width: 250px;
                    margin: 0 auto;
                }

                .pwe-discover-btns__container_text a:hover {
                    background: '. $btn_color .';
                    color: white !Important;
                }
                .display-without-hover p {
                    font-size:36px;
                    margin-bottom:35px;
                }
                .display-after-hover {
                    display: none;
                    opacity: 0;
                    transform: scale(0.95);
                    transition: opacity 0.6s ease, transform 0.1s ease;
                }

                .display-after-hover p {
                    opacity: 0;
                    transition: opacity 1s ease;
                    font-size:18px;
                }

                .display-without-hover {
                    opacity: 1;
                    transition: opacity 0.2s ease, transform 0.2s ease, height 0.2s ease;
                }

                /* Po najechaniu na kontener, display-after-hover pojawia się */
                .show .display-after-hover {
                    display: block;
                    opacity: 1;
                    transform: scale(1);
                }

                .show .display-after-hover p {
                    opacity: 1;
                    transition-delay: 2s; /* Opóźnienie dla bardziej płynnego pojawienia się tekstu */
                }

                .show .display-without-hover {
                    opacity: 0;
                    height: 0;
                    transform: scale(0.95);
                }

                @media(max-width: 960px) {
                    .pwe-discover-btns__container_image_left,
                    .pwe-discover-btns__container_image_right {
                        min-height: 300px;
                    }
                }

                @media(max-width: 560px) {
                    .pwe-discover-btns__container {
                        flex-direction: column;
                    }
                }
            </style>
            <div id="pweDiscoverBtns" class="pwe-discover-btns">
                <div class="pwe-discover-btns__container">
                    <div class="pwe-discover-btns__container_image" id="leftImage">
                        <div class="pwe-discover-btns__container_image_left"></div>
                            <div class="pwe-discover-btns__container_text">
                                <div class="display-without-hover">
                                    '.$pwe_about_short_left_text.'
                                    <img src="'.$pwe_about_left_img_src.'" alt="Logo 1">
                                </div>
                                <div class="display-after-hover">
                                '. $title_text  .'
                                </div>
                                <a href="/poznaj-targi">Dowiedz się więcej</a>
                            </div>
                        </div>
                        <div class="pwe-discover-btns__container_image" id="rightImage">
                            <div class="pwe-discover-btns__container_image_right"></div>
                            <div class="pwe-discover-btns__container_text">
                            <div class="display-without-hover">
                                '.$pwe_about_short_right_text.'
                                <img src="'.$pwe_about_right_img_src .'" alt="Logo 2">
                            </div>
                            <div class="display-after-hover">
                                '. $desc_text .'
                            </div>
                            <a href="/wydarzenia">Dowiedz się więcej</a>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const leftImage = document.getElementById("leftImage");
                const rightImage = document.getElementById("rightImage");

                function expandLeft() {
                    leftImage.classList.add("expanded");
                    rightImage.classList.remove("expanded");

                    // Opóźnienie, aby tekst pojawił się po zakończeniu animacji rozszerzenia
                    setTimeout(() => {
                        leftImage.classList.add("show");
                    }, 500); // 0.5 sekundy odpowiada czasowi animacji flex-grow
                }

                function expandRight() {
                    rightImage.classList.add("expanded");
                    leftImage.classList.remove("expanded");

                    // Opóźnienie, aby tekst pojawił się po zakończeniu animacji rozszerzenia
                    setTimeout(() => {
                        rightImage.classList.add("show");
                    }, 500); // 0.5 sekundy odpowiada czasowi animacji flex-grow
                }

                function resetWidth() {
                    leftImage.classList.remove("expanded", "show");
                    rightImage.classList.remove("expanded", "show");
                }

                // Przypisanie zdarzeń
                leftImage.addEventListener("mouseenter", expandLeft);
                leftImage.addEventListener("mouseleave", resetWidth);
                rightImage.addEventListener("mouseenter", expandRight);
                rightImage.addEventListener("mouseleave", resetWidth);
            </script>
            ';
        }

        return $output;
    }
}



