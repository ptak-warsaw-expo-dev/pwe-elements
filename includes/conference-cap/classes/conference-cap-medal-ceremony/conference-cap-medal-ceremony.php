
<?php
class PWEConferenceCapMedalCeremony extends PWEConferenceCap{

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function output($atts, $sessions, $conf_function, $conf_name, $day, $conf_slug, $conf_location){

        extract(shortcode_atts(array(
            'conference_cap_html' => '',
            'conference_cap_conference_mode' => '',
        ), $atts));

        $content .= 
            '<div class="conference_cap_medal_ceremony__main-container">
                <div class="conference_cap_medal_ceremony__title">
                    <h2>' . $conf_name . '</h2>
                </div>
                <div class="conference_cap_medal_ceremony__ceremony-container">
                    <div class="conference_cap_medal_ceremony__date">
                        <h4>'. PWECommonFunctions::languageChecker('Data', 'Date') .'</h4>
                        <span>' . $day . '</span>
                    </div>
                    <div class="conference_cap_medal_ceremony__location">
                        <h4>'. PWECommonFunctions::languageChecker('Lokalizacja', 'Location') .'</h4>
                        <span>' . $conf_location . '</span>
                    </div>
                </div>
            </div>';

        return $content;
    }

  }

  