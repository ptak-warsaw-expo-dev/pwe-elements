<?php
/**
* Class PWOutlookCalendarElement
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWOutlookCalendarElement extends PWElements {

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

        $data = 'BEGIN:VCALENDAR' . PHP_EOL .
                'VERSION:2.0' . PHP_EOL .
                'BEGIN:VEVENT' . PHP_EOL .
                'DTSTART:' . substr($trade_start, 0, 4) . substr($trade_start, 5, 2) . substr($trade_start, 8, 2) . 'T' . substr($trade_start, 11, 2). '0000' . PHP_EOL .
                'DTEND:' . substr($trade_end, 0, 4) . substr($trade_end, 5, 2) . substr($trade_end, 8, 2) . 'T' . substr($trade_end, 11, 2). '0000' . PHP_EOL .
                'SUMMARY:' . $trade_name . PHP_EOL .
                'DESCRIPTION:' . $trade_desc . PHP_EOL .
                'LOCATION:Al. Katowicka 62, 05-830 Nadarzyn' . PHP_EOL .
                'END:VEVENT' . PHP_EOL .
                'END:VCALENDAR' . PHP_EOL;

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/pwe-media/media/Outlook.ics';
        $fileSaved = file_put_contents($filePath, $data);

        $output = '
        <div id="calendar-outlook" class="pwe-container-calendar-add text-centered">
            <a class="outlook" alt="link do kalendarza outlook" href="/wp-content/plugins/pwe-media/media/Outlook.ics">
                <img alt="ikonka outlook" src="/wp-content/plugins/pwe-media/media/outlook.png"/>
                <p class="calendar-icon font-weight-700">'.
                self::languageChecker(
                    <<<PL
                    Kalendarz<br>Outlook
                    PL,
                    <<<EN
                    Outlook<br>Calendar
                    EN
                )
                .'</p>
            </a>
        </div>';

        return $output;
    }
}
