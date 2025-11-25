<?php
/**
* Class PWGoogleCalendarElement
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWGoogleCalendarElement extends PWElements {

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
    public static function output() {
        $trade_desc = do_shortcode(
            self::languageChecker(
                <<<PL
                [trade_fair_desc]
                PL,
                <<<EN
                [trade_fair_desc_eng]
                EN
            )
        );
        
        $trade_name = do_shortcode(
            self::languageChecker(
                <<<PL
                [trade_fair_name]
                PL,
                <<<EN
                [trade_fair_name_eng]
                EN
            )
        );

        $trade_start = do_shortcode("[trade_fair_datetotimer]");
        $trade_end = do_shortcode("[trade_fair_enddata]");

        $linker = 'https://calendar.google.com/calendar/render?action=TEMPLATE&details=' . urlencode($trade_desc) . '&dates=' . substr($trade_start, 0, 4) . substr($trade_start, 5, 2) . substr($trade_start, 8, 2) . 'T' . substr($trade_start, 11, 2). '0000%2F' . substr($trade_end, 0, 4) . substr($trade_end, 5, 2) . substr($trade_end, 8, 2) . 'T' . substr($trade_end, 11, 2). '0000?0&location=Aleja%20Katowicka%2062%2C%2005-Aleja%20Katowicka%2062%2C%2005-830%20Nadarzyn%2C%20Polska&text=' . urlencode($trade_name);
        
        $output = '<div id="calendar-google" class="pwe-container-calendar-add text-centered">
                    <a class="google" alt="link do kalendarza google" href="' . $linker . '" target="_blank">
                        <img src="/wp-content/plugins/pwe-media/media/googlecalendar.png" alt="ikonka google calendar"/>
                        <p class="calendar-icon font-weight-700">'.
                        self::languageChecker(
                            <<<PL
                            Kalendarz<br>Google
                            PL,
                            <<<EN
                            Google<br>Calendar
                            EN
                        )
                    .'</a>
                </div>';

        return $output;
    }
}