<?php
/**
* Class PWElementDontMiss
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementDontMiss extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
        require_once plugin_dir_path(__FILE__) . 'calendarApple.php';
        require_once plugin_dir_path(__FILE__) . 'calendarGoogle.php';
    }
    
    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {
        $contact_items = vc_param_group_parse_atts($atts['new_contact'], true);

        $output = '';
        
        if (!preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT'])) {
        $output .= '
            <style>
            #niePrzegap {
                z-index: 100;
            }
            .pwe-container-niePrzegap{
                display:flex;
            }
            .pwe-container-niePrzegap{
                position: fixed;
                left: -250px;
                top: 30%;
                z-index: 11;
                transition: left 0.3s ease;
            }
            .pwe-container-niePrzegap:hover,
            .niePrzegap-hover,
            .pwe-container-niePrzegap.hovered-cal {
                left: 0 !important;
            }
            .row-container:has(.pwe-container-niePrzegap) .row-parent,
            .row-container:has(.pwe-container-niePrzegap) .wpb_column  {
                padding: 0 !important;
            }
            .pwe-inner-niePrzegap{
                width: 250px !important;
                height: 250px;
                background-color:#fff;
                border: 4px solid #000;
                padding: 10px 0;
            }
            .pwe-pointer-niePrzegap {
                margin: 0 -30px;
            }
            .pwe-pointer-niePrzegap i{
                text-shadow: 5px 0 #fff;
            }
            .pwe-header-niePrzegap h4{
                margin:10px;
            }
            .pwe-container-calendar-niePrzegap{
                display:flex;
                margin:20px 5px;
                gap: 5px;
                justify-content: space-evenly;
            }
            .pwe-container-calendar-niePrzegap .pwe-container-calendar-add {
                width: 100px;
            }
            .pwe-container-calendar-niePrzegap img{
                height:50px !important;
                object-fit: contain;
            }
            .pwe-container-calendar-niePrzegap p{
                font-size: 15px !important;
                margin-top: 5px;
                line-height: 1.2;
                text-wrap: wrap;
                width: 80%;
            }
            .pwe-pointer-wrapper-niePrzegap {
                display: flex;
            }
            .pwe-pointer-wrapper-niePrzegap i {
                color:black;
                position: absolute;
                left: 251px;
            }
            .pwe-vertival-text-niePrzegap {
                display: flex;
                height: 250px;
                position: absolute;
                left: 247px;
                background-color: black;
                border-radius: 0 8px 8px 0;
            }
            .pwe-vertival-text-niePrzegap p {
                color: white;
                padding: 11px 2px;
                margin: 0;
                text-align: center;
                font-weight: 600;
                writing-mode: vertical-rl;
                text-orientation: upright;
                letter-spacing: 0px;
                background-color: black;
                border-radius: 0 8px 8px 0;
            }
            .link-more{
                margin:10px;
                text-decoration: underline;
            }
            </style>

            <div id="niePrzegap" class="pwe-container-niePrzegap pwe-display-none">
                <div class="pwe-inner-niePrzegap">
                    <div class="pwe-header-niePrzegap text-centered">
                        <h4>'.
                            self::languageChecker(
                                <<<PL
                                    Dodaj wydarzenie<br> do kalendarza
                                PL,
                                <<<EN
                                    Add the event<br> to your calendar
                                EN
                            )
                        .'</h4>
                    </div>
                    <div class="pwe-container-calendar-niePrzegap text-centered">';
                        $output .= PWGoogleCalendarElement::output($atts);
                        $output .= PWAppleCalendarElement::output($atts);
                    $output .= '</div>
                    <div class="text-centered">'.
                        self::languageChecker(
                            <<<PL
                                <a class="link-more" href="/dodaj-do-kalendarza/">Więcej Kalendarzy</a>
                            PL,
                            <<<EN
                                <a class="link-more" href="/en/add-to-calendar/">More Calendars</a>
                            EN
                        )
                    .'</div>
                </div>
                <div class="pwe-pointer-wrapper-niePrzegap">
                    <div class="pwe-vertival-text-niePrzegap">
                        <p class="pwe-uppercase">'.
                            self::languageChecker(
                                <<<PL
                                    Nie przegap
                                PL,
                                <<<EN
                                    Do not miss
                                EN
                            )
                        .'</p>
                    </div>
                    <div class="pwe-pointer-niePrzegap">
                        <i class="fa fa-caret-right fa-4x fa-fw"></i>
                    </div>
                </div>
            </div>';

            if (current_user_can('administrator')) {
                $admin_username = 'Anton';
                $current_user = wp_get_current_user();
                if ($current_user->user_login == $admin_username) { echo '<style>#niePrzegap { display: none !important; }</style>'; }
            } 
        } else {
            $output = '<div id="niePrzegap"></div>';
        }
        return $output;
    }
}