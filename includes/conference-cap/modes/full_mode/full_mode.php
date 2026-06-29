<?php

/**
 * Legacy Full Mode renderer kept in the mode folder as the single source of truth.
 */
class PWEConferenceCapFullMode {

    public function __construct() {}

    public static function output($atts, $sessions, $conf_function, &$speakersDataMapping, &$all_day_speakers, $short_day, $conf_slug_index, $panel, $conf_location, $prelegent_show) {
        $has_any_speaker_info = false;
        $lecture_counter = 0;
        $all_speakers_combined = [];

        foreach ($sessions as $session) {
            foreach ($session as $key => $value) {
                if (strpos($key, 'legent-') === 0 && is_array($value)) {
                    if (!empty($value['url']) || !empty($value['desc'])) {
                        $has_any_speaker_info = true;
                        break 2;
                    }
                }
            }
        }

        $content = '<div class="conference_cap__lecture-container">';

        foreach ($sessions as $key => $session) {
            if (strpos($key, 'pre-') !== 0) {
                continue;
            }

            $lecture_counter++;
            $lectureId = $conf_slug_index . '_' . $short_day . '_' . 'pre-' . $lecture_counter;
            $time  = isset($session['time']) ? $session['time'] : '';
            $title = isset($session['title']) ? $session['title'] : '';
            $desc  = isset($session['desc']) ? $session['desc'] : '';
            $formatted_speaker_names = [];
            $speakers = [];

            foreach ($session as $speaker_key => $value) {
                if (strpos($speaker_key, 'legent-') === 0 && is_array($value)) {
                    $speakers[] = $value;
                }
            }

            $content .= '<div id="' . esc_attr($lectureId) . '" class="conference_cap__lecture-box">';

            $speakers_bios = [];
            $speaker_images = [];

            if (!empty($speakers)) {
                foreach ($speakers as $speaker) {
                    $raw_name = isset($speaker['name']) ? $speaker['name'] : '';
                    $name_parts = explode(';;', $raw_name);

                    $speaker_name_html = esc_html($name_parts[0]);
                    if (isset($name_parts[1])) {
                        $speaker_name_html .= '<br><span class="conference_cap__lecture-name-subline">' . esc_html($name_parts[1]) . '</span>';
                    }

                    $speaker_name_plain = esc_html(trim($name_parts[0] . (isset($name_parts[1]) ? ' ' . $name_parts[1] : '')));
                    $speaker_url  = isset($speaker['url']) ? $speaker['url'] : '';
                    $speaker_desc = isset($speaker['desc']) ? $speaker['desc'] : '';

                    if (!empty($speaker_name_plain) && $speaker_name_plain !== '*') {
                        if (!empty($speaker_url)) {
                            $speaker_images[] = $speaker_url;
                        }

                        if (!empty($speaker_desc)) {
                            $speakers_bios[] = array(
                                'name' => $speaker_name_plain,
                                'name_html' => $speaker_name_html,
                                'url'  => $speaker_url,
                                'bio'  => $speaker_desc,
                            );
                        }

                        $formatted_speaker_names[] = $speaker_name_html;
                        $all_speakers_combined[] = array(
                            'name_html' => $speaker_name_html,
                            'url' => $speaker_url,
                            'desc' => $speaker_desc,
                        );
                    }
                }

                if ($prelegent_show && $has_any_speaker_info) {
                    $content .= '<div class="conference_cap__lecture-speaker">';

                    if (!empty($speaker_images)) {
                        $content .= '<div class="conference_cap__lecture-speaker-img">' . $conf_function::speakerImageMini($speaker_images) . '</div>';
                    }

                    if (!empty($speakers_bios)) {
                        $speakersDataMapping[$conf_slug_index . '_' . $short_day][$lectureId] = $speakers_bios;
                        $content .= '<button class="conference_cap__lecture-speaker-btn" data-lecture-id="' . esc_attr($lectureId) . '">BIO</button>';
                    }

                    $content .= '</div>';
                }
            }

            $content .= '
                <div class="conference_cap__lecture-box-info">
                    <h4 class="conference_cap__lecture-time">' . esc_html($time) . '</h4>';

            if (!empty($formatted_speaker_names) && implode('', $formatted_speaker_names) !== 'brak') {
                $content .= '<h5 class="conference_cap__lecture-name">' . implode('<br>', $formatted_speaker_names) . '</h5>';
            }

            $content .= '<h4 class="conference_cap__lecture-title">' . esc_html($title) . '</h4>
                    <div class="conference_cap__lecture-desc"><p>' . wp_kses_post($desc) . '</p></div>
                </div>
            </div>';
        }

        $content .= '</div>';

        if (!$prelegent_show && !empty($all_speakers_combined) && is_array($all_day_speakers)) {
            $all_day_speakers = array_merge($all_day_speakers, $all_speakers_combined);

            if (!isset($speakersDataMapping[$conf_slug_index])) {
                $speakersDataMapping[$conf_slug_index] = [];
            }

            foreach ($all_speakers_combined as $index => $speaker) {
                $lectureId = 'global_' . $index;

                if (!empty($speaker['desc'])) {
                    $speakersDataMapping[$conf_slug_index][$lectureId] = [
                        'name'      => strip_tags($speaker['name_html']),
                        'name_html' => $speaker['name_html'],
                        'url'       => $speaker['url'],
                        'bio'       => $speaker['desc'],
                    ];
                }
            }
        }

        return $content;
    }
}

/**
 * New mode class wrapper for context-based rendering.
 */
final class PWE_Conference_Cap_Full_Mode {

    public function render(array $context): string {
        $mapping = $context['speakers_data_mapping'] ?? array();
        $all_day_speakers = $context['all_day_speakers'] ?? array();

        return PWEConferenceCapFullMode::output(
            $context['attributes'] ?? array(),
            $context['sessions'] ?? array(),
            $context['legacy_functions'] ?? new PWEConferenceCapFunctions(),
            $mapping,
            $all_day_speakers,
            $context['day_key'] ?? '',
            $context['conference_slug'] ?? '',
            (bool) ($context['settings']['panel'] ?? false),
            $context['conference_location'] ?? '',
            (bool) ($context['settings']['show_speakers'] ?? true)
        );
    }

    public function get_assets(): array {
        return array('css' => array('modes/full_mode/assets/full_mode.css'), 'js' => array());
    }
}

