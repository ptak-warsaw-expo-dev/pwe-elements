
<?php
class PWEConferenceCapFullMode extends PWEConferenceCap{

        /**
       * Constructor method.
       * Calls parent constructor and adds an action for initializing the Visual Composer map.
       */
      public function __construct() {
        parent::__construct();
    }

    public static function output($atts, $sessions, $conf_function, &$speakersDataMapping, &$all_day_speakers, $short_day, $conf_slug_index, $panel, $conf_location, $prelegent_show){

        extract(shortcode_atts(array(
            'conference_cap_title' => '',
            'conference_cap_style' => '',
            'conference_cap_html' => '',
            'conference_cap_conference_mode' => '',
        ), $atts));

        $has_any_speaker_info = false;
        $lecture_counter = 0;
        $all_speakers_combined = [];
        
        foreach ($sessions as $session) {
            foreach ($session as $key => $value) {
                if (strpos($key, 'legent-') === 0 && is_array($value)) {
                    $name = isset($value['name']) ? trim($value['name']) : '';
                    $has_valid_name = !empty($name) && $name !== '*';

                    if ((!empty($value['url']) || !empty($value['desc']))) {
                        $has_any_speaker_info = true;
                        break 2;
                    }
                }
            }
        }

        $content = '<div class="conference_cap__lecture-container">';
        
            foreach ($sessions as $key => $session) {
                if (strpos($key, 'pre-') !== 0) {
                    continue; // Pomijamy wpisy, które nie zaczynają się od "pre-"
                }
                
                $lecture_counter++;
                $lectureId = $conf_slug_index . '_' . $short_day . '_' . 'pre-' . $lecture_counter;
                $time  = isset($session['time']) ? $session['time'] : '';
                $title = isset($session['title']) ? $session['title'] : '';
                $desc  = isset($session['desc']) ? $session['desc'] : '';

                $formatted_speaker_names = [];

                // Pobieramy dane prelegentów
                $speakers = [];
                foreach ($session as $key => $value) {
                    if (strpos($key, 'legent-') === 0 && is_array($value)) {
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
                        
                            // Do HTML-a
                            $speaker_name_html = esc_html($name_parts[0]);
                            if (isset($name_parts[1])) {
                                $speaker_name_html .= '<br><span class="conference_cap__lecture-name-subline">' . esc_html($name_parts[1]) . '</span>';
                            }
                        
                            // Do atrybutów alt, bio['name'], itp.
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
                                        'bio'  => $speaker_desc
                                    );
                                }
                        
                                // Zbieramy nazwę w wersji HTML do późniejszego użycia (np. <h5>)
                                $formatted_speaker_names[] = $speaker_name_html;

                                $all_speakers_combined[] = array(
                                    'name_html' => $speaker_name_html,
                                    'url' => $speaker_url,
                                    'desc' => $speaker_desc
                                );
                            }
                        }

                        if ($prelegent_show && $has_any_speaker_info) {
                        
                            $content .= '<div class="conference_cap__lecture-speaker">';
                        
                                // Dodanie funkcji speakerImageMini po pętli
                                if (!empty($speaker_images)) {
                                    $content .= '<div class="conference_cap__lecture-speaker-img">' . $conf_function::speakerImageMini($speaker_images) . '</div>';
                                }
                            
                                if (!empty($speakers_bios)) {
                                    $speakersDataMapping[$conf_slug_index . '_' . $short_day][$lectureId] = $speakers_bios;

                                    $content .= '<button class="conference_cap__lecture-speaker-btn" data-lecture-id="' . $lectureId . '">BIO</button>';
                                }

                            $content .= '</div>';
                        }
                    }
                    

                     $content .= '
                     <div class="conference_cap__lecture-box-info">
                        <h4 class="conference_cap__lecture-time">' . esc_html($time) . '</h4>';

                        $speaker_names = $formatted_speaker_names ?? [];
                        

                        if (!empty($speaker_names) && implode('', $speaker_names) !== 'brak') {
                            $content .= '<h5 class="conference_cap__lecture-name">' . implode('<br>', $speaker_names) . '</h5>';
                        }
                        
                        $content .= '<h4 class="conference_cap__lecture-title">' . esc_html($title) . '</h4>
                        <div class="conference_cap__lecture-desc"><p>' . $desc . '</p>
                        </div>
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

                $has_bio = !empty($speaker['desc']);

                // tylko jeśli ma bio — dodaj dane do JS
                if ($has_bio) {
                    $speakersDataMapping[$conf_slug_index][$lectureId] = [
                        'name'      => strip_tags($speaker['name_html']),
                        'name_html' => $speaker['name_html'],
                        'url'       => $speaker['url'],
                        'bio'       => $speaker['desc']
                    ];
                }

                // niezależnie od tego, czy ma bio — ID musi być konsekwentnie przypisany w HTML
            }
        }
        return $content;
    }

}