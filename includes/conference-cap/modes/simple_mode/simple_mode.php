<?php

/**
 * Legacy Simple Mode renderer kept in the mode folder as the single source of truth.
 */
class PWEConferenceCapSimpleMode {

    public function __construct() {}

    public static function output($atts, $sessions, $conf_function, &$speakersDataMapping) {
        $lecture_counter = 0;
        $content = '<div class="conference_cap_simple_mode__lecture-container">';

        foreach ($sessions as $key => $session) {
            if (strpos($key, 'pre-') !== 0) {
                continue;
            }

            $lecture_counter++;
            $lectureId = 'cap_simple_mode-lecture-' . $lecture_counter;
            $time  = isset($session['time']) ? $session['time'] : '';
            $title = isset($session['title']) ? $session['title'] : '';
            $speakers = [];

            foreach ($session as $speaker_key => $value) {
                if (strpos($speaker_key, 'legent-') === 0 && is_array($value)) {
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

            $speaker_names = array_map(static function ($speaker) {
                return $speaker['name'];
            }, $speakers);

            if (!empty($speaker_names) && implode('', $speaker_names) !== 'brak') {
                $content .= '<h5 class="conference_cap_simple_mode__lecture-name">' . wp_kses_post(implode('<br>', $speaker_names)) . '</h5>';
            }

            $content .= '</div></div>';
        }

        $content .= '</div>';

        return $content;
    }
}

/**
 * New mode class wrapper for context-based rendering.
 */
final class PWE_Conference_Cap_Simple_Mode {

    public function render(array $context): string {
        $mapping = $context['speakers_data_mapping'] ?? array();

        return PWEConferenceCapSimpleMode::output(
            $context['attributes'] ?? array(),
            $context['sessions'] ?? array(),
            $context['legacy_functions'] ?? new PWEConferenceCapFunctions(),
            $mapping
        );
    }

    public function get_assets(): array {
        return array('css' => array('modes/simple_mode/assets/simple_mode.css'), 'js' => array());
    }
}

