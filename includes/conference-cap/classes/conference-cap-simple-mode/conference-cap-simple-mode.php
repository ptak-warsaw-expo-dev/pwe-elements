
<?php
class PWEConferenceCapSimpleMode extends PWEConferenceCap{

        /**
       * Constructor method.
       * Calls parent constructor and adds an action for initializing the Visual Composer map.
       */
      public function __construct() {
        parent::__construct();
    }

    public static function output($atts, $sessions, $conf_function, &$speakersDataMapping){

        extract(shortcode_atts(array(
            'conference_cap_title' => '',
            'conference_cap_style' => '',
            'conference_cap_html' => '',
            'conference_cap_conference_mode' => '',
        ), $atts));

        $lecture_counter = 0;
        
        
        $content = '<div class="conference_cap_simple_mode__lecture-container">';
        
            foreach ($sessions as $key => $session) {
                if (strpos($key, 'pre-') !== 0) {
                    continue; // Pomijamy wpisy, które nie zaczynają się od "pre-"
                }
                
                $lecture_counter++;
                $lectureId = 'cap_simple_mode-lecture-' . $lecture_counter;
                $time  = isset($session['time']) ? $session['time'] : '';
                $title = isset($session['title']) ? $session['title'] : '';

                // Pobieramy dane prelegentów
                $speakers = [];
                foreach ($session as $key => $value) {
                    if (strpos($key, 'legent-') === 0 && is_array($value)) {
                        $speakers[] = $value;
                    }
                }

                $content .= '
                    <div id="' . esc_attr($lectureId) . '" class="conference_cap_simple_mode__lecture-box">
                        <div class="conference_cap_simple_mode__lecture-time-container">
                            <h4 class="conference_cap_simple_mode__lecture-time">' . esc_html($time) . '</h4>
                        </div>
                        <div class="conference_cap_simple_mode__lecture-box-info">
                            <h4 class="conference_cap_simple_mode__lecture-title">' . esc_html($title) . '</h4>';

                            if (!empty($speakers)) {
                            
                                foreach ($speakers as $speaker) {
                                    $speaker_name = isset($speaker['name']) ? $speaker['name'] : '';

                                }
                            }

                            $speaker_names = array_map(function ($speaker) {
                                return $speaker['name'];
                            }, $speakers);

                            if (!empty($speaker_names) && implode('', $speaker_names) !== 'brak') {
                                $content .= '<h5 class="conference_cap_simple_mode__lecture-name">' . (implode('<br>', $speaker_names)) . '</h5>';
                            }
                        
                        $content .= '</div>
                    </div>';
            }

        $content .= '</div>';

        return $content;
    }

}