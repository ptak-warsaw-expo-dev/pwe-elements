<?php
/**
* Class PWElementConfCallendar
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementConfCallendar extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    private static function calendarDisplay($data) {
    
    }
    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {
        require_once plugin_dir_path(__FILE__) . 'calendarApple.php';
        require_once plugin_dir_path(__FILE__) . 'calendarGoogle.php';
        require_once plugin_dir_path(__FILE__) . 'calendarOffice.php';
        require_once plugin_dir_path(__FILE__) . 'calendarOutlook.php';
        
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . ' !important;';

        echo '<script>console.log("'.$text_color.'")</script>';
        
        $output = '';

        $output .= '
        <style>
            .pwelement_' . self::$rnd_id . ' #onlyCalendar h3{
            ' . $text_color . '
            }
            .pwe-container-calendar-icons{
                display: flex;
                justify-content: center;
                gap:30px;
                margin-top: 20px;
            }
            .pwe-container-calendar-icons{
                max-width: 1200px;
            }
            .pwe-inner-calendarAdd img {
                object-fit: contain;
                max-width: 300px !important;
            }
            .pwe-container-calendar-icons{ 
                top:-30px;
                position: relative; 
            }
            .pwe-container-calendar-add{
                flex:1;
                min-width: 100px;
                max-width: 180px;
                background: white;
                padding:5px 0;
            }
            .pwe-container-calendar-add p{
                color:black;
                margin:5px;
                line-height: 1.2;
            }
            .pwe-container-calendar-add img{
                max-height: 150px;
                width: auto;
                max-width:100%;
            }
            @media (max-width:959px){
                .pwe-container-calendar-icons{
                    padding: 10px;
                }
            }
            @media (max-width:570px){
                .pwe-container-calendar-icons{
                    flex-wrap: wrap;
                }
                .pwe-container-calendar-add{
                    min-width: 35%;
                    max-width: 130px;
                }
                .pwe-container-calendar-add img{
                    max-height: 100px;
                }  
            }
        </style>

        <div id="onlyCalendar" class="pwe-container-onlyCalendar text-centered">
            <div class="half-block-padding">'.
            self::languageChecker(
                <<<PL
                    <h3>Nie przegap targów, dodaj datę do kalendarza</h3>
                PL,
                <<<EN
                    <h3>Don't miss the fair, add the date to your calendar</h3>
                EN
                )
            .'</div>';

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
        $output .= '</div>';

        return $output;
    }
}