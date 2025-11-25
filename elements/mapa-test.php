<?php

/**
 * Class PWElementMapaTest
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementMapaTest extends PWElements {

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
                'group' => 'PWE Element',
                'heading' => __('Title with color background', 'pwelement'),
                'param_name' => 'pwe_custom_title_color_1',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title first', 'pwelement'),
                'param_name' => 'pwe_custom_title_1',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of visitors', 'pwelement'),
                'param_name' => 'pwe_number_visitors_1',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of exhibitors', 'pwelement'),
                'param_name' => 'pwe_number_exhibitors_1',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of countries', 'pwelement'),
                'param_name' => 'pwe_number_countries_1',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Percent of polish visitors', 'pwelement'),
                'param_name' => 'pwe_percent_polish_visitors_1',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Date of events', 'pwelement'),
                'param_name' => 'pwe_event_date_1',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Exhibition space', 'pwelement'),
                'param_name' => 'pwe_exhibition_space_1',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMapaTest',
                ),
            ),
        );
        return $element_output;
    }

    public static function output($atts) {

        extract( shortcode_atts( array(
            'pwe_custom_title_color_1' => '',
            'pwe_custom_title_1' => '',
            'pwe_number_visitors_1' => '',
            'pwe_number_exhibitors_1' => '',
            'pwe_number_countries_1' => '',
            'pwe_percent_polish_visitors_1' => '',
            'pwe_event_date_1' => '',
            'pwe_exhibition_space_1' => '',
        ), $atts ));

        $pwe_number_visitors_1 = !empty($pwe_number_visitors_1) ? $pwe_number_visitors_1 : 0;
        $pwe_percent_polish_visitors_1 = !empty($pwe_percent_polish_visitors_1) ? $pwe_percent_polish_visitors_1 : 0;
        $pwe_number_countries_1 = !empty($pwe_number_countries_1) ? $pwe_number_countries_1 : 15;

        $output = '
        <style>
            #pweMapa {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            #pweMapa h2 {
                text-transform: uppercase;
                margin: 20px 0;
                text-align: center;
            }
            #pweMapa .pwe-container-mapa {
                background-image:url(/doc/mapka_mobile.webp);
                background-position: right;
                background-size: contain;
                background-repeat: no-repeat;
                display:flex;
                align-items: start;
                flex-direction: column;
                justify-content: center;
                max-width: 1200px;
                width: 100%;
                gap: 25px;
                padding: 50px 0;
            }
            #pweMapa .pwe-mapa-element {
                display: flex;
                justify-content: left;
                align-items: center;
                gap: 15px;
                box-shadow: 0px 1px 7px 0px grey;
                border-radius: 8px;
                padding: 0 25px;
                max-height: 70px;
            }
            #pweMapa .pwe-mapa-element p {
                color:white;
            }
            #pweMapa .pwe-mapa-element .pwe-mapa-element-heading {
                font-weight: 600;
                font-size: 38px;
                margin-top: 0;
            }
            #pweMapa .pwe-mapa-element .pew-mapa-element-text {
                font-size: 28px;
                font-weight: 500;
                margin-top: 10px;
                letter-spacing: 1px;
            }
            #pweMapa .pwe-mapa-element .fa {
                font-size: 35px;
            }
            #pweMapa .pwe-mapa-element .fa:before {
                color: white;
            }
            #pweMapa .mapka-mobile {
                display:none;
            }
            @media (min-width: 1200px){

            }
            @media(max-width:1150px){
                #pweMapa .pwe-container-mapa {
                    background-position: right;
                    justify-content: start;
                    gap: 15px;
                    padding: 40px 0;
                }
            }
            @media (max-width: 960px){
                #pweMapa .pwe-container-mapa {
                    background-position: center right;
                    background-size: 40%;
                    padding: 20px 0;
                }
                #pweMapa .pwe-mapa-element .pew-mapa-element-text {
                    font-size: 16px;
                    letter-spacing: 0px;
                }
                #pweMapa .pwe-mapa-element .pwe-mapa-element-heading {
                    font-size: 25px;
                    margin-top: 2px;
                }
                #pweMapa .pwe-mapa-element .fa {
                    font-size: 30px;
                }
            }
            @media(max-width:570px){
                #pweMapa .pwe-mapa-element .pew-mapa-element-text {
                    font-size: 16px;
                    letter-spacing: 0px;
                }
                #pweMapa .pwe-mapa-element .pwe-mapa-element-heading {
                    font-size: 25px;
                    margin-top: 2px;
                }
                #pweMapa .pwe-mapa-element .fa {
                    font-size: 30px;
                }
                #pweMapa .pwe-container-mapa {
                    background-image: none !important;
                }
                #pweMapa .mapka-mobile {
                    display: block;
                    margin-top: 15px;
                }
                #pweMapa h2 {
                    font-size: 20px;
                }
            }
            @media(max-width:370px){
                #pweMapa .pwe-mapa-element .pwe-mapa-element-heading {
                    font-size: 22px;
                }
                #pweMapa .pwe-mapa-element .pew-mapa-element-text {
                    font-size: 14px;
                }
            }
        </style>';

        if ($pwe_custom_title_color_1 == true) {
            $output .= '
                <style>
                .row-parent:has(#pweMapa) {
                    max-width: 100% !important;
                    padding: 0 !important;
                }
                #pweMapa h2 {
                    width: 100%;
                    background-color: red;
                    padding: 8px;
                    color: white !important;
                    margin: 0;
                }
                #pweMapa .pwe-container-mapa {
                    max-width: 1200px;
                    padding: 36px;
                    margin: 36px;
                }
                @media(max-width:680px){
                    #pweMapa h2 {
                        font-size: 20px;
                    }
                    #pweMapa .mapka-mobile {
                        padding: 0 36px 36px;
                    }
                }
                @media(max-width:430px){
                    #pweMapa h2 {
                        font-size: 16px;
                    }
                }
                </style>
            ';
        }

        $output .= '
        <div id="pweMapa">
            <h2 class="text-accent-color">'. $pwe_custom_title_1 .'</h2>
            <div class="pwe-container-mapa">
                <div class="pwe-mapa-elements text-accent-color">
                    <div class="pwe-mapa-element" style="background: '.self::$main2_color.'">
                        <i class="fa fa-group fa-1x fa-fw"></i>
                        <p class="pwe-mapa-element-heading">'. $pwe_number_visitors_1 .'</p>
                        <p class="pew-mapa-element-text">'.
                            self::languageChecker(
                            <<<PL
                                odwiedzających
                            PL,
                            <<<EN
                                visitors
                            EN
                            )
                        .'</p>
                    </div>
                </div>
                <div class="pwe-mapa-elements">
                    <div class="pwe-mapa-element" style="background-color: '.self::$accent_color.'">
                        <i class="fa fa-wpforms fa-1x fa-fw"></i>
                        <p class="pwe-mapa-element-heading">'. $pwe_number_exhibitors_1 .'</p>
                        <p class="pew-mapa-element-text">'.
                            self::languageChecker(
                            <<<PL
                                wystawców
                            PL,
                            <<<EN
                                exhibitors
                            EN
                            )
                        .'</p>
                    </div>
                </div>
                <div class="pwe-mapa-elements">
                    <div class="pwe-mapa-element" style="background-color: #9a1933;">
                        <i class="fa fa-world fa-1x fa-fw"></i>
                        <p class="pwe-mapa-element-heading">'. $pwe_number_countries_1 .'</p>
                        <p class="pew-mapa-element-text">'.
                            self::languageChecker(
                            <<<PL
                                krajów
                            PL,
                            <<<EN
                                countries
                            EN
                            )
                        .'</p>
                    </div>
                </div>
            </div>
            <img class="mapka-mobile" src="/doc/mapka_mobile.webp" />
        </div>';

        return $output;
    }
}