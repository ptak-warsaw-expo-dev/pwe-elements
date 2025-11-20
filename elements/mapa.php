<?php

/**
 * Class PWElementMapa
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementMapa extends PWElements {

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
                'heading' => __('Map mode', 'pwe_element'),
                'param_name' => 'pwe_map_mode',
                'save_always' => true,
                'value' => array(
                    'Default' => 'default_mode',
                    '3D' => '3d_mode',
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapa',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'PWE Element',
                'heading' => __('Model color', 'pwelement'),
                'param_name' => 'pwe_map_color',
                'description' => __('Write or select color of model', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => '3d_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Overlay color', 'pwelement'),
                'param_name' => 'pwe_map_overlay',
                'description' => __('Write or select color of overlay', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => '3d_mode',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('3D Model', 'pwelement'),
                'param_name' => 'pwe_map_default_3d',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'PWE Element',
                'heading' => __('Model color', 'pwelement'),
                'param_name' => 'pwe_map_default_color',
                'description' => __('Write or select color of model', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_default_3d',
                    'value' => 'true',
                ),
            ),
            array(
                'type' => 'attach_image',
                'group' => 'PWE Element',
                'heading' => __('Additional image', 'pwelement'),
                'param_name' => 'pwe_map_image',
                'description' => __('Choose additional image from the media library.', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => '3d_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title first', 'pwelement'),
                'param_name' => 'pwe_custom_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of visitors', 'pwelement'),
                'param_name' => 'pwe_number_visitors',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of exhibitors', 'pwelement'),
                'param_name' => 'pwe_number_exhibitors',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of countries', 'pwelement'),
                'param_name' => 'pwe_number_countries',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Percent of polish visitors', 'pwelement'),
                'param_name' => 'pwe_percent_polish_visitors',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
            // array(
            //     'type' => 'textfield',
            //     'group' => 'PWE Element',
            //     'heading' => __('Date of events', 'pwelement'),
            //     'param_name' => 'pwe_event_date',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_map_mode',
            //         'value' => 'default_mode',
            //     ),
            // ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Exhibition space', 'pwelement'),
                'param_name' => 'pwe_exhibition_space',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('More LOGOS', 'pwelement'),
                'param_name' => 'pwe_mapa_more_logos',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_map_mode',
                    'value' => 'default_mode',
                ),
            ),
        );
        return $element_output;
    }

    public static function output($atts) {
      
        extract( shortcode_atts( array(
            'pwe_map_mode' => '',
            'pwe_map_default_3d' => '',
            'pwe_map_color' => '',
            'pwe_map_overlay' => '',
            'pwe_map_default_color' => '',
            'pwe_map_image' => '',
            'pwe_custom_title' => '',
            'pwe_number_visitors' => '',
            'pwe_number_exhibitors' => '',
            'pwe_number_countries' => '',
            'pwe_percent_polish_visitors' => '',
            // 'pwe_event_date' => '',
            'pwe_exhibition_space' => '',
        ), $atts ));

        $pwe_number_visitors = !empty($pwe_number_visitors) ? $pwe_number_visitors : 0;
        $pwe_percent_polish_visitors = !empty($pwe_percent_polish_visitors) ? $pwe_percent_polish_visitors : 0;
        $pwe_number_countries = !empty($pwe_number_countries) ? $pwe_number_countries : 15;

        $map_more_logos = (isset($atts['pwe_mapa_more_logos'])) ? explode(';', $atts['pwe_mapa_more_logos']) : '';

        if ($pwe_map_mode === '3d_mode') {

            $hex_color = !empty($pwe_map_color) ? ltrim($pwe_map_color, '#') : ltrim(self::$accent_color, '#');
            $pwe_map_overlay = !empty($pwe_map_overlay) ? $pwe_map_overlay : 'inherit';

            $output = '
            <style>
                .pwe-map__container-3d {
                    position: relative;
                    width: 100%;
                }
                .pwe-map__container-3d canvas {
                    width: 100% !important;
                    height: auto !important;
                    aspect-ratio: 1 / 1;
                }
                .pwe-map__canvas-overlay {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: '. $pwe_map_overlay .';
                    z-index: 2;
                }
                .pwe-map__numbers {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 100%;
                }
            </style>';

            $pwe_map_image_src = !empty($pwe_map_image) ? wp_get_attachment_url($pwe_map_image) : '/doc/numbers.webp';
            $image_availible = (!empty($pwe_map_image) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/numbers.webp')) ? true : false; 

            $output .= '
                <div id="pweMap" class="pwe-map">
                    <div id="container-3d" class="pwe-map__container-3d">';

                        if ($image_availible) {
                            $output .= '
                            <div class="pwe-map__numbers">
                                <img src="'. $pwe_map_image_src .'"/>
                            </div>';
                        }

                        $output .= '
                        <div class="pwe-map__canvas-overlay"></div>
                    </div>
                </div>
            ';
        } else {

            if ($pwe_map_default_3d == true) {

                $hex_color = !empty($pwe_map_default_color) ? ltrim($pwe_map_default_color, '#') : ltrim(self::$accent_color, '#');

                $output = '
                <style>
                    .pwe-map__wrapper {
                        position: relative;
                        display: flex;
                        justify-content: space-between
                    }
                    .pwe-map__staticts {
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        max-width: 260px;
                        gap: 12px;
                        z-index: 1;
                        background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, 
                                                              rgba(255, 255, 255, 0.9) 10%, 
                                                              rgba(255, 255, 255, 0.8) 20%,
                                                              rgba(255, 255, 255, 0.7) 30%, 
                                                              rgba(255, 255, 255, 0.6) 40%,  
                                                              rgba(255, 255, 255, 0.5) 50%, 
                                                              rgba(255, 255, 255, 0.4) 60%, 
                                                              rgba(255, 255, 255, 0.3) 70%,
                                                              rgba(255, 255, 255, 0.2) 80%,  
                                                              rgba(255, 255, 255, 0.1) 90%,
                                                              rgba(255, 255, 255, 0) 100%);
                    }
                    .pwe-map__title {
                        margin-top: 0;
                        text-transform: uppercase;
                        font-size: 40px;
                        max-width: 550px;
                        text-shadow: 0px 0px 2px white;
                    }
                    .pwe-map__rounded-stat {
                        display: flex;
                        justify-content: space-around;
                        gap: 12px;
                    }
                    .pwe-map__rounded-element {
                        width: 120px;
                        min-height: 120px;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        border:5px solid;
                        border-radius:100%;
                        text-align: center;
                    }
                    .pwe-map__rounded-element p {
                        margin-top: 0px;
                        line-height: 1;
                    }
                    .pwe-map__stats-element-title {
                        font-weight: 700;
                        font-size: 26px;
                        text-shadow: 0px 0px 2px white;
                    }
                    .pwe-map__stats-element-desc {
                        font-size: 26px;
                        margin-top: 0px;
                        line-height: 1;
                        text-shadow: 0px 0px 2px white;
                    }
                    .pwe-map__logotypes {
                        max-width: 260px;
                        z-index: 1;
                        background: linear-gradient(to left, rgba(255, 255, 255, 1) 0%, 
                                                             rgba(255, 255, 255, 0.9) 10%, 
                                                             rgba(255, 255, 255, 0.8) 20%,
                                                             rgba(255, 255, 255, 0.7) 30%, 
                                                             rgba(255, 255, 255, 0.6) 40%,  
                                                             rgba(255, 255, 255, 0.5) 50%, 
                                                             rgba(255, 255, 255, 0.4) 60%, 
                                                             rgba(255, 255, 255, 0.3) 70%,
                                                             rgba(255, 255, 255, 0.2) 80%,  
                                                             rgba(255, 255, 255, 0.1) 90%,
                                                             rgba(255, 255, 255, 0) 100%);
                    }   
                    .pwe-map__logo-container {
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                    }
                    .pwe-map__logotypes-data {
                        margin-top: 12px;
                        font-weight:750;
                        font-size: 20px;
                        text-align: center !important;
                    }
                    .pwe-map__container-3d {
                        position: absolute;
                        width: 100%;
                        max-width: 600px;
                        display: flex;
                        align-items: center;
                        bottom: 0;
                        left: 50%;
                        transform: translate(-50%, 0);
                    }
                    .pwe-map__container-3d canvas {
                        width: 100% !important;
                        height: auto !important;
                        aspect-ratio: 1 / 1;
                    }
                    @media (max-width: 960px) {
                        .pwe-map__title {
                            font-size: 34px !important;
                        }
                    }
                    @media (max-width: 650px) {
                        .pwe-map__logotypes {
                            display: none;
                        }
                        .pwe-map__staticts {
                            max-width: 100%;
                            padding: 72px 0;
                        }
                        .pwe-map__stats-container {
                            display: flex;
                            flex-wrap: wrap;
                        }
                        .pwe-map__stats-element {
                            width: 50%;
                        }
                        .pwe-map__stats-element-title,
                        .pwe-map__stats-element-desc {
                            font-size: 22px;
                        }
                        .pwe-map__staticts {
                            background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, 
                                                                rgba(255, 255, 255, 0.6) 10%, 
                                                                rgba(255, 255, 255, 0.5) 20%,
                                                                rgba(255, 255, 255, 0.4) 30%, 
                                                                rgba(255, 255, 255, 0.3) 40%,  
                                                                rgba(255, 255, 255, 0.3) 50%, 
                                                                rgba(255, 255, 255, 0.3) 60%, 
                                                                rgba(255, 255, 255, 0.4) 70%,
                                                                rgba(255, 255, 255, 0.5) 80%,  
                                                                rgba(255, 255, 255, 0.6) 90%,
                                                                rgba(255, 255, 255, 1) 100%);
                        }
                        
                    } 
                    @media (max-width: 600px) {
                        .pwe-map__staticts {
                            padding: 36px 0;
                            gap: 18px;
                        }        
                    }
                    @media (max-width: 450px) {
                        .pwe-map__staticts {
                            padding: 0;
                        }
                        .pwe-map__stats-element-title,
                        .pwe-map__stats-element-desc {
                            font-size: 18px;
                        }
                    }
                </style>';

                $output .= '
                <div id="pweMap" class="pwe-map">
                    <div class="pwe-map__wrapper">
                        <div class="pwe-map__staticts">
                            <h2 class="pwe-map__title text-accent-color">'. $pwe_custom_title .'</h2>
                            <div class="pwe-map__rounded-stat">
                                <div class="pwe-map__rounded-element text-accent-color">
                                    <p style="font-weight: 800; font-size: 21px;">
                                        <span class="countup" data-count="'. $pwe_number_visitors .'">0</span>
                                    </p>
                                    <p style="font-size:12px">'. self::languageChecker('odwiedzających', 'visitors') .'</p>
                                </div>
                                <div class="pwe-map__rounded-element pwe-map__rounded-element-country">
                                    <p style="font-weight: 800; font-size: 27px;">
                                        <span class="countup" data-count="'. $pwe_number_countries .'">0</span>
                                    </p>
                                    <p style="font-size:12px">'. self::languageChecker('krajów', 'countries') .'</p>
                                </div>
                            </div>
                            <div class="pwe-map__stats-container">
                                <div class="pwe-map__stats-element">
                                    <p class="text-accent-color pwe-map__stats-element-title">'.
                                        self::languageChecker('Polska -', 'Poland -') .' 
                                        <span class="countup" data-count="'. floor($pwe_number_visitors / 100 * $pwe_percent_polish_visitors) .'">0</span>
                                    </p>
                                    <p class="pwe-map__stats-element-desc"><span class="countup" data-count="'. $pwe_percent_polish_visitors .'">0</span> %</p>
                                </div>
                                <div class="pwe-map__stats-element">
                                    <p class="text-accent-color pwe-map__stats-element-title">'.
                                        self::languageChecker('Zagranica -', 'Abroad -').' 
                                        <span class="countup" data-count="'. ($pwe_number_visitors - floor($pwe_number_visitors / 100 * $pwe_percent_polish_visitors)) .'">0</span>
                                    </p>
                                    <p class="pwe-map__stats-element-desc">
                                    <span class="countup" data-count="'. (100 - $pwe_percent_polish_visitors) .'">0</span> %</p>
                                </div>
                                <div class="pwe-map__stats-element">
                                    <p class="text-accent-color pwe-map__stats-element-title">
                                        <span class="countup" data-count="'. $pwe_exhibition_space .'">0</span> m<sup>2</sup>
                                    </p>
                                    <p class="pwe-map__stats-element-desc">'. self::languageChecker('powierzchni<br>wystawienniczej', 'exhibition<br>space') .'</p>
                                </div>
                                <div class="pwe-map__stats-element">
                                    <p class="text-accent-color pwe-map__stats-element-title">
                                        <span class="countup" data-count="'. $pwe_number_exhibitors .'">0</span>
                                    </p>
                                    <p class="pwe-map__stats-element-desc">'. self::languageChecker('wystawców', 'exhibitors') .'</p>
                                </div>
                            </div>
                        </div>

                        <div id="container-3d" class="pwe-map__container-3d"></div>

                        <div class="pwe-map__logotypes">
                            <div class="pwe-map__logo-container">'.
                                self::languageChecker('<img src="/doc/logo-color.webp"/>', '<img src="/doc/logo-color-en.webp"/>');
                                if (is_array($map_more_logos)){
                                    foreach($map_more_logos as $single_logo){
                                        $output .= '<img src="' . $single_logo . '"/>';
                                    }
                                    $output .= '<p class="pwe-map__logotypes-data" style="text-align: right;">'. self::languageChecker('[trade_fair_date]', '[trade_fair_date_eng]') .'</p>';
                                } else {
                                    $output .= '<p class="pwe-map__logotypes-data" style="text-align: center;">'. self::languageChecker('[trade_fair_date]', '[trade_fair_date_eng]') .'</p>';
                                }
                                $output .= '
                            </div>

                        </div>
                    </div>
                </div>';
            } else {
                $output = '
                <style>
                    .pwe-mapa-staticts {
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                    }
                    .pwe-mapa-staticts h2 {
                        margin-top: 0;
                        text-transform: uppercase;
                        font-size: 40px;
                        max-width: 550px;
                    }
                    .pwe-container-mapa {
                        display:flex;
                        justify-content: space-between;
                        min-height:50vh;
                    }
                    .pwe-mapa-rounded-stat {
                        display: flex;
                        gap: 15px;
                    }
                    .pwe-mapa-rounded-element {
                        width: 120px;
                        min-height: 120px;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        border:5px solid;
                        border-radius:100%;
                        text-align: center;
                    }
                    .pwe-mapa-rounded-element p {
                        margin-top:0px;
                        line-height: 1;
                    }
                    .pwe-mapa-stats-element-title {
                        font-weight: 700;
                        font-size: 28px;
                    }
                    .pwe-mapa-stats-element-desc {
                        font-size: 25px;
                    }
                    .pwe-mapa-stats-element-title, .pwe-mapa-stats-element-desc {
                        line-height: 1;
                    }
                    .pwe-mapa-stats-element {
                        margin:20px 0;
                    }
                    .pwe-mapa-stats-element p {
                        margin-top:0px !important;
                    }
                    .pwe-container-mapa {
                        background-image:url(/doc/mapka.webp);
                        background-position: center;
                        background-size: contain;
                        background-repeat: no-repeat;
                        display:flex;
                        justify-content: space-between;
                    }
                    .pwe-mapa-stats-element-mobile {
                        display:none;
                    }';

                    if (is_array($map_more_logos)){
                        $output .=
                        '.pwe-mapa-logo-container img {
                            max-width: 200px;
                            margin: 10px;
                        }';
                    } else {
                        $output .=
                        '.pwe-mapa-logo-container img {
                            max-width: 250px;
                        }';
                    }

                    $output .= '
                    .pwe-mapa-right {
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        align-items: flex-end;
                    }
                    .pwe-mapa-right-data {
                        margin-top:0;
                        font-weight:750;
                        font-size: 20px;
                    }
                    .pwe-mapa-rounded-element-country-right {
                        display:none;
                    }
                    @media (min-width: 1200px){
                        .pwe-container-mapa {
                            height: 670px;
                        }
                    }
                    @media(max-width:1100px){
                        .pwe-mapa-rounded-element-country-right {
                            display:flex;
                        }
                        .pwe-mapa-logo-container img {
                            max-width: 200px;
                        }
                        .pwe-mapa-stats-element-title, .pwe-mapa-stats-element-desc {
                            font-size: 20px;
                        }
                        .pwe-mapa-staticts h2 {
                            font-size: 25px;
                            max-width: 550px;
                        }
                        .pwe-mapa-rounded-element {
                            width: 120px;
                            min-height: 120px;
                            border: 3px solid;
                            margin-left: 15px;
                        }
                        .pwe-mapa-rounded-element-country {
                            display:none;
                        }
                    }
                    @media (max-width: 599px){
                        .pwe-mapa-staticts {
                            width: 100%;
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            text-align: center;
                        }
                        .pwe-mapa-right {
                            display:none;
                        }
                        .pwe-mapa-stats-element-mobile, .pwe-mapa-rounded-element-country  {
                            display:flex;
                        }
                        .pwe-container-mapa {
                            background-image:none;
                            justify-content: center;
                        }
                        .pwe-mapa-rounded-stat {
                            margin: 20px 0 15px;
                        }
                        .pwe-mapa-stats-container {
                            width:100%;
                        }
                        .pwe-mapa-staticts .mobile-estymacje-image {
                            margin-top:0 !important;
                            height:230px;
                            background-image:url(/doc/mapka_mobile.webp);
                            background-position: center;
                            background-size: contain;
                            background-repeat: no-repeat;
                        }
                        .pwe-mapa-stats-element {
                            margin: 10px 0 0;
                        }
                    }

                    .pwe-map__container-3d {
                        position: relative;
                        width: 100%;
                    }
                    .pwe-map__container-3d canvas {
                        width: 100% !important;
                        height: auto !important;
                        aspect-ratio: 1 / 1;
                    }
                </style>

                <div id="mapa" class="pwe-container-mapa">
                    <div class="pwe-mapa-staticts">
                        <h2 class="text-accent-color">'. $pwe_custom_title .'</h2>
                        <div class="pwe-mapa-rounded-stat">
                            <div class="pwe-mapa-rounded-element text-accent-color">
                                <p style="font-weight: 800; font-size: 21px;">'. $pwe_number_visitors .'</p>
                                <p style="font-size:12px">'. self::languageChecker('odwiedzających', 'visitors') .'</p>
                            </div>
                            <div class="pwe-mapa-rounded-element pwe-mapa-rounded-element-country">
                                <p style="font-weight: 800; font-size: 27px;">'. $pwe_number_countries .'</p>
                                <p style="font-size:12px">'. self::languageChecker('krajów', 'countries') .'</p>
                            </div>
                        </div>
                        <div class="pwe-mapa-stats-container">
                            <div class="pwe-mapa-stats-element">
                                <p class="text-accent-color pwe-mapa-stats-element-title">'.
                                    self::languageChecker('Polska -', 'Poland -') .' '. floor($pwe_number_visitors / 100 * $pwe_percent_polish_visitors) .'
                                </p>
                                <p class="pwe-mapa-stats-element-desc">'. $pwe_percent_polish_visitors .' %</p>
                            </div>
                            <div class="pwe-mapa-stats-element">
                                <p class="text-accent-color pwe-mapa-stats-element-title">'.
                                    self::languageChecker('Zagranica -', 'Abroad -').' '. ($pwe_number_visitors - floor($pwe_number_visitors / 100 * $pwe_percent_polish_visitors)) .'
                                </p>
                                <p class="pwe-mapa-stats-element-desc">'. (100 - $pwe_percent_polish_visitors) .' %</p>
                            </div>
                            <div class="mobile-estymacje-image"></div>
                            <div class="pwe-mapa-stats-element">
                                <p class="text-accent-color pwe-mapa-stats-element-title">'. $pwe_exhibition_space .' m<sup>2</sup></p>
                                <p class="pwe-mapa-stats-element-desc">'. self::languageChecker('powierzchni<br>wystawienniczej', 'exhibition<br>space') .'</p>
                            </div>
                            <div class="pwe-mapa-stats-element">
                                <p class="text-accent-color pwe-mapa-stats-element-title">'. $pwe_number_exhibitors .'</p>
                                <p class="pwe-mapa-stats-element-desc">'. self::languageChecker('wystawców', 'exhibitors') .'</p>
                            </div>
                        </div>
                    </div>';
                    
                    $output .= '
                    <div class="pwe-mapa-right">
                        <div class="pwe-mapa-logo-container">'.
                            self::languageChecker('<img src="/doc/logo-color.webp"/>', '<img src="/doc/logo-color-en.webp"/>');
                            if (is_array($map_more_logos)){
                                foreach($map_more_logos as $single_logo){
                                    $output .= '<img src="' . $single_logo . '"/>';
                                }
                                $output .= '<p class="pwe-mapa-right-data" style="text-align: right;">'. self::languageChecker('[trade_fair_date]', '[trade_fair_date_eng]') .'</p>';
                            } else {
                                $output .= '<p class="pwe-mapa-right-data" style="text-align: center;">'. self::languageChecker('[trade_fair_date]', '[trade_fair_date_eng]') .'</p>';
                            }
                            $output .= '
                        </div>
                        <div class="pwe-mapa-rounded-element pwe-mapa-rounded-element-country-right">
                            <p style="font-weight: 800; font-size: 24px;">'. $pwe_number_countries .'</p>
                            <p style="font-size:12px">'. self::languageChecker('krajów', 'countries') .'</p>
                        </div>
                    </div>
                </div>';
            }
        
        }

        $output .= '
        <script>
            
        document.addEventListener("DOMContentLoaded", () => {
            const countUpElements = document.querySelectorAll(".countup");

            // Function to animate a number
            const animateCount = (element) => {
                const targetValue = parseInt(element.getAttribute("data-count"), 10);
                if (isNaN(targetValue)) {
                    console.error("Invalid data-count value for element:", element);
                    return;
                }

                const duration = 3000; // Animation duration in ms
                const startTime = performance.now();

                const update = (currentTime) => {
                    const elapsedTime = currentTime - startTime;
                    const progress = Math.min(elapsedTime / duration, 1); // Maximum 1
                    const currentValue = Math.floor(progress * targetValue);

                    element.textContent = currentValue;

                    if (progress < 1) {
                        requestAnimationFrame(update);
                    }
                };

                requestAnimationFrame(update);
            };

            // Element visibility observer
            const observer = new IntersectionObserver(
                (entries, observerInstance) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            const element = entry.target;
                            animateCount(element);
                            observerInstance.unobserve(element); // Unfollow after animation
                        }
                    });
                },
                {
                    threshold: 0.5, // Element must be at least 50% visible
                }
            );

            // Adding an observer to each element with class .countup
            countUpElements.forEach((element) => observer.observe(element));
        });
            
        </script>';

        if ($pwe_map_mode === '3d_mode' || $pwe_map_default_3d == true) {
            $output .= '
            <script src="/wp-content/plugins/PWElements/assets/three-js/three.min.js"></script>
            <script src="/wp-content/plugins/PWElements/assets/three-js/GLTFLoader.js"></script>

            <script>
                // Initialize scene, camera and renderer
                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(75, 1 / 1, 0.1, 1000);
                const renderer = new THREE.WebGLRenderer({
                    alpha: true,
                    antialias: true,
                    precision: "highp"
                });
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.setPixelRatio(window.devicePixelRatio);
                document.getElementById("container-3d").appendChild(renderer.domElement);

                renderer.domElement.addEventListener("webglcontextlost", (event) => {
                    console.warn("WebGL context lost");
                    event.preventDefault(); // Zapobiega domyślnemu zachowaniu
                });

                renderer.domElement.addEventListener("webglcontextrestored", () => {
                    console.log("WebGL context restored");
                    // Konieczne może być ponowne załadowanie modelu i sceny
                    initScene();
                });

                // Add light to scene
                const ambientLight = new THREE.AmbientLight(0xffffff, 1);
                scene.add(ambientLight);

                const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
                directionalLight.position.set(5, 10, 7.5).normalize();
                scene.add(directionalLight);

                // Loading the GLTF model using GLTFLoader
                const loader = new THREE.GLTFLoader();
                loader.load("/wp-content/plugins/pwe-media/media/mapa.gltf", function(gltf) {
                    const model = gltf.scene;

                    // Create group as pivot
                    const pivot = new THREE.Group();

                    // Usuń poprzedni model (jeśli istnieje)
                    if (scene.children.includes(pivot)) {
                        pivot.traverse((node) => {
                            if (node.isMesh) {
                                node.geometry.dispose();
                                if (node.material.map) node.material.map.dispose();
                                node.material.dispose();
                            }
                        });
                        scene.remove(pivot);
                    }

                    pivot.add(model);

                    // Set model scaling to 3.5, 3.5, 3.5
                    model.scale.set(3.5, 3.5, 3.5);

                    // Change model color to blue while preserving materials
                    model.traverse((node) => {
                        if (node.isMesh) {
                            // Preserve shadow settings and change color at the same time
                            node.material = new THREE.MeshStandardMaterial({
                                color: 0x'. $hex_color .',   // Kolor modelu
                                metalness: node.material.metalness,  // Preserving metallicity
                                roughness: node.material.roughness,  // Preserving roughness
                                emissive: node.material.emissive,  // Emission behavior
                                opacity: node.material.opacity,    // Preserving transparency
                                transparent: node.material.transparent // Preserving transparency
                            });

                            // Shadow behavior
                            node.castShadow = true;
                            node.receiveShadow = true;
                        }
                    });

                    // Center the model in the pivot group
                    const box = new THREE.Box3().setFromObject(model);
                    const center = box.getCenter(new THREE.Vector3());
                    model.position.set(-center.x, -center.y, -center.z);

                    // Additional correction to the model position to align it with the bottom
                    // model.position.y -= 0.2;  // Przesuwamy model nieco w dół, by zmniejszyć przestrzeń u dołu

                    // Adding a pivot to the scene
                    scene.add(pivot);

                    // Animation function
                    function animate() {
                        requestAnimationFrame(animate);
                        renderer.render(scene, camera);
                    }

                    // Rotate the pivot around the Y axis while scrolling
                    // window.addEventListener("scroll", () => {
                    //     const rotationAmount = window.scrollY * 0.001;
                    //     pivot.rotation.y = rotationAmount;
                    // });

                    // Camera initialization
                    camera.position.z = 11; // Zwiększenie wartości z (oddalenie kamery od modelu)

                    // Optionally, we increase the FOV (camera viewing angle)
                    camera.fov = 40;
                    camera.updateProjectionMatrix();

                    // Animation function (auto rotate)
                    let autoRotate = true; // Flag to enable/disable auto rotation
                    let rotationSpeed = 0.006; // Rotation speed
                    let lastFrameTime = 0;
                    const targetFPS = 30; // Limited to 30 frames per second
                    const frameDuration = 1000 / targetFPS;

                    function animate(time) {
                        if (time - lastFrameTime >= frameDuration) {
                            lastFrameTime = time;

                            // Obrót modelu
                            if (autoRotate) {
                                model.rotation.y += rotationSpeed;
                            }

                            renderer.render(scene, camera);
                        }
                        requestAnimationFrame(animate);
                    }

                    // Start animation
                    animate(0);

                }, undefined, function(error) {
                    console.error("An error happened while loading the model:", error);
                });

                // Update camera aspect ratio when window resizes
                window.addEventListener("resize", () => {
                    const container = document.getElementById("container-3d");
                    const width = container.clientWidth;
                    const height = container.clientHeight;

                    // We update the camera aspect ratio based on the container size
                    camera.aspect = width / height;
                    camera.updateProjectionMatrix();

                    // We set the renderer size based on the container dimensions
                    renderer.setSize(width, height);
                });
            </script>'; 
        }

        

        return $output;
    }
}