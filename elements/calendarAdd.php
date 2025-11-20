<?php
/**
* Class PWCallendarAddElement
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWCallendarAddElement extends PWElements {

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
        $element_output = 
        array(
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Logo in color', 'pwelement'),
                'param_name' => 'logo_color',
                'value' => '',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWCallendarAddElement',
                )
            ),
        );
        return $element_output;
    }
    
    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {
        extract( shortcode_atts( array(
            'logo_color' => '',
        ), $atts ));

        require_once plugin_dir_path(__FILE__) . 'calendarApple.php';
        require_once plugin_dir_path(__FILE__) . 'calendarGoogle.php';
        require_once plugin_dir_path(__FILE__) . 'calendarOffice.php';
        require_once plugin_dir_path(__FILE__) . 'calendarOutlook.php';

        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . ' !important;';
        $text_shadow_color = 'text-shadow: 2px 2px ' . self::findColor($atts['text_shadow_color_manual_hidden'], $atts['text_shadow_color'], 'black') . ' !important;';        

        $output = '';

        $output .= '
        <style>
            .row-parent:has(.pwelement_'.self::$rnd_id.' #calendar-add) {
                max-width: 100%;
                padding: 0 !important;
            }
            .wpb_column:has(.pwelement_'.self::$rnd_id.') {
                padding-top: 0 !important;
            }
            .pwelement_'.self::$rnd_id.' #calendar-add {
                background: no-repeat;
                background-size: cover;
                background-image:url(' . (file_exists(ABSPATH . "/doc/background.webp") ? "/doc/background.webp" : "/doc/background.jpg") . ');
                width: 100%;
            }
            .pwelement_'.self::$rnd_id.' #calendar-add :is(h2, h1, h3){
                '. $text_color
                . $text_shadow_color . '
                font-weight:700;
            }
            .pwelement_'.self::$rnd_id.' #calendar-add h1 {
                font-size: 76px;
                text-transform: uppercase;
            }
            .pwelement_'.self::$rnd_id.' .pwe-container-calendar-icons, 
            .pwelement_'.self::$rnd_id.' .pwe-header-calendarAdd {
                display: flex;
                justify-content: center;
                gap:30px;
                margin-top: 20px;
            }
            .pwelement_'.self::$rnd_id.' .pwe-inner-calendarAdd, 
            .pwelement_'.self::$rnd_id.' .pwe-container-calendar-icons {
                max-width: 1200px;
            }
            .pwelement_'.self::$rnd_id.' .pwe-inner-calendarAdd img {
                object-fit: contain;
                max-width: 300px !important;
            }
            .pwelement_'.self::$rnd_id.' .pwe-container-calendar-icons { 
                top:-30px;
                position: relative; 
            }
            .pwelement_'.self::$rnd_id.' .pwe-inner-calendar-icons {
                margin-top:30px;
            }
            .pwelement_'.self::$rnd_id.' .pwe-container-calendar-add { 
                flex:1;
                min-width: 100px;
                max-width: 180px;
                background: white;
                padding: 5px 0;
                border-radius: 18px;
            }
            .pwelement_'.self::$rnd_id.' .pwe-container-calendar-add p {
                color:black;
                margin:5px;
                line-height: 1.2;
            }
            .pwelement_'.self::$rnd_id.' .pwe-container-calendar-add img, 
            .pwelement_'.self::$rnd_id.' .pwe-header-calendarAdd img {
                max-height: 150px;
                width: auto;
                max-width:100%;
            }
            .pwelement_'.self::$rnd_id.' #calendar-add .calendar-icon a {
                color:black !important;
            }
            @media (min-width: 300px) and (max-width: 1200px) {
                .pwelement_'.self::$rnd_id.' #calendar-add h1 {
                    font-size: calc(24px + (76 - 24) * ( (100vw - 300px) / (1200 - 300) ));
                }
            }
            @media (max-width:959px){
                .pwelement_'.self::$rnd_id.' .pwe-container-calendar-icons {
                    padding: 10px;
                }
            }
            @media (max-width:570px){
                .pwelement_'.self::$rnd_id.' .pwe-container-calendar-icons, 
                .pwelement_'.self::$rnd_id.' .pwe-header-calendarAdd {
                    flex-wrap: wrap;
                }
                .pwelement_'.self::$rnd_id.' .pwe-container-calendar-add {
                    min-width: 35%;
                    max-width: 130px;
                }
                .pwelement_'.self::$rnd_id.' .pwe-container-calendar-add img {
                    max-height: 100px;
                }  
            }
        </style>

        <div id="calendar-add" class="pwe-container-calendar-main text-centered style-accent-bg">
            <div class="pwe-calendar-wrapper">
                <div class="pwe-inner-calendarAdd single-block-padding">
                    <div class="pwe-header-calendarAdd">'.
                            self::findBestLogo($logo_color)
                        .'<div class="pwe-header-text-calendarAdd">'.
                        self::languageChecker(
                            <<<PL
                                <h2>[trade_fair_name]</h2>
                                <h2>[trade_fair_desc]</h2>
                            PL,
                            <<<EN
                                <h2>[trade_fair_name_eng]</h2>
                                <h2>[trade_fair_desc_eng]</h2>
                            EN
                            )
                        .'</div>
                    </div>
                    <div class="pwe-header-calendar-add text-centered">
                        <h1 class="pwe-header-calendar-add-text">'.
                            self::languageChecker(
                                <<<PL
                                    Dodaj do kalendarza
                                PL,
                                <<<EN
                                    Add to calendar
                                EN
                                )
                            .'
                        </h1>
                    </div> 

                    
        
                    <div class="pwe-text-calendar-add text-centered">'.
                        self::languageChecker(
                            <<<PL
                                <h3>Wybierz ikonę swojej poczty aby dodać wydarzenie do kalendarza.</h3>
                            PL,
                            <<<EN
                                <h3>Select your mail icon to add the event to your calendar.</h3>
                            EN
                            )
                    .'</div>
                </div> 
                <div class="pwe-inner-calendar-icons text-centered style-accent-bg">';
                    
                    if (!self::isTradeDateExist()) {
                        $output .= '<div class="pwe-container-main-icons pwe-container-calendar-icons">';
                        $output .= PWGoogleCalendarElement::output($atts);
                        $output .= PWAppleCalendarElement::output($atts);
                        $output .= PWOutlookCalendarElement::output($atts);
                        $output .= PWOfficeCalendarElement::output($atts);
                        $output .='</div>';
                    } else {        
                        $output .='<div class="pwe-container-calendar-icons-empty double-bottom-padding double-top-padding">
                            <h2 style="margin:0;" class="pwe-uppercase text-centered">'.
                            self::languageChecker(
                                <<<PL
                                Nowa data wkrótce
                                PL,
                                <<<EN
                                New date coming soon
                                EN
                            )
                            .'</h2>
                        </div>';
                    }
                $output .='</div>
            </div>
        </div>';

        return $output;
    }
}

?> 
