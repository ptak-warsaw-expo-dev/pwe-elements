<?php
class PWEConferenceCapWarsawExpo {

    /* -----------------------------------------------------------------
     * -----------------------------------------------------------------*/
    private static function parse_date_to_standard($input) {
        return PWE_Conference_Cap_Date_Parser::parse_date_to_standard((string) $input);
    }

    /* -----------------------------------------------------------------
     * -----------------------------------------------------------------*/
    private static function normalize_hour_string($time_str) {
        return PWE_Conference_Cap_Time_Parser::normalize_hour_string((string) $time_str);
    }

    /* -----------------------------------------------------------------
     * -----------------------------------------------------------------*/
    public static function output($atts, $lang) {
        PWE_Conference_Cap_Assets::enqueue_mode('warsawexpo');
        $lang_key   = strtoupper($lang);
        $lang_lower = strtolower($lang);
        $database_data = [];

        if (!empty($atts['conference_cap_domains'])) {
            $domains = array_map('trim', explode(',', $atts['conference_cap_domains']));
            foreach ($domains as $domain) {
                $conf_data     = PWECommonFunctions::get_database_conferences_data($domain);

                foreach ($conf_data as $c) {
                    $c->fair = $domain;
                }

                $database_data = array_merge($database_data, $conf_data);

            }
        }

        $confs_by_day          = [];
        $expo_logos            = [];
        $day_min_total_by_date = [];
        $day_max_total_by_date = [];

        // 2. Group sessions by date
        foreach ($database_data as $conf) {
            if (empty($conf->conf_data)) continue;

            $confData = json_decode($conf->conf_data, true);
            if (!$confData || !isset($confData[$lang_key])) continue;

            $sessions_by_day = $confData[$lang_key];
            foreach ($sessions_by_day as $day_label => $sessions) {
                if (strpos(strtolower($day_label), 'main-desc') !== false) continue;

                $date_info = self::parse_date_to_standard($day_label);
                if (!$date_info) {
                    error_log("❗ Nie rozpoznano daty z etykiety: $day_label");
                    continue;
                }

                $date_key     = $date_info['key'];
                $date_display = $date_info['display'];

                if (!isset($confs_by_day[$date_key])) {
                    $confs_by_day[$date_key] = [
                        'display' => $date_display,
                        'confs'   => []
                    ];
                }

                /* ------------------------------------------------------
                 * ------------------------------------------------------*/
                $conf_location_raw = $conf->{"conf_location_" . $lang_lower} ?? '';
                $conf_hall_default = '';
                if (!empty($conf_location_raw)) {
                    $parts = array_map('trim', explode(';;', $conf_location_raw));
                    foreach ($parts as $p) {
                        if ( ($lang_lower === 'pl' && stripos($p, 'hala') !== false) ||
                             ($lang_lower !== 'pl' && stripos($p, 'hall') !== false) ) {
                            $conf_hall_default = $p;
                            break;
                        }
                    }
                    if ($conf_hall_default === '' && !empty($parts[0])) {
                        $conf_hall_default = $parts[0];
                    }
                }

                $halls                    = [];
                $day_min_total_global     = null;
                $day_max_total_global     = null;

                foreach ($sessions as $session_id => $s) {
                    if (empty($s['title'])) continue;

                    $hall_raw = $conf_hall_default !== '' ? $conf_hall_default : ($s['hall'] ?? '');
                    $hall = $hall_raw ?: (($lang_lower === 'pl') ? 'Hala' : 'Hall');
                    $hour_raw = $s['hour'] ?? ($s['time'] ?? '');
                    if (empty($hour_raw)) continue;

                    $normalized = self::normalize_hour_string($hour_raw);
                    if (!$normalized) continue;

                    [$start_h, $start_m] = explode(':', $normalized['start']);
                    $start_total         = intval($start_h) * 60 + intval($start_m);

                    if ($normalized['end']) {
                        [$end_h, $end_m] = explode(':', $normalized['end']);
                        $end_total       = intval($end_h) * 60 + intval($end_m);
                    } else {
                        $end_total       = $start_total + 60;
                    }

                    // Global min and max values for the day
                    if (!isset($day_min_total_global) || $start_total < $day_min_total_global) {
                        $day_min_total_global = $start_total;
                    }
                    if (!isset($day_max_total_global) || $end_total > $day_max_total_global) {
                        $day_max_total_global = $end_total;
                    }

                    $halls[$hall][] = [
                        'title'       => $s['title'],
                        'hour'        => $hour_raw,
                        'desc'        => $s['desc'] ?? '',
                        'speaker_img' => $s['speaker_img'] ?? ($s['legent-1']['url'] ?? '')
                    ];
                }

                if (!isset($day_min_total_global)) $day_min_total_global = 8 * 60;
                if (!isset($day_max_total_global)) $day_max_total_global = 16 * 60;

                /* -----------------------------------------------------------------
                * -----------------------------------------------------------------*/
                if (!empty($halls)) {

                    if (!isset($day_min_total_by_date[$date_key]) ||
                        $day_min_total_global < $day_min_total_by_date[$date_key]) {
                        $day_min_total_by_date[$date_key] = $day_min_total_global;
                    }

                    if (!isset($day_max_total_by_date[$date_key]) ||
                        $day_max_total_global > $day_max_total_by_date[$date_key]) {
                        $day_max_total_by_date[$date_key] = $day_max_total_global;
                    }

                    $already_added = false;
                    if (isset($confs_by_day[$date_key]['confs'])) {
                        foreach ($confs_by_day[$date_key]['confs'] as $existing) {
                            if ($existing['slug'] === $conf->conf_slug) {
                                $already_added = true;
                                break;
                            }
                        }
                    }

                    if (!$already_added) {
                        $confs_by_day[$date_key]['confs'][] = [
                            'title' => $conf->{"conf_name_" . $lang_lower},
                            'img'   => $conf->{"conf_img_" . $lang_lower},
                            'slug'  => $conf->conf_slug,
                            'fair'   => $conf->fair,
                            'halls' => $halls
                        ];
                    }
                }

                if (!empty($conf->{"conf_img_" . $lang})) {
                    $expo_logos[$conf->conf_slug] = esc_url($conf->{"conf_img_" . $lang});
                }
            }
        }

        foreach ($confs_by_day as $dk => $dinfo) {
            if (empty($dinfo['confs'])) {
                unset($confs_by_day[$dk]);
                unset($day_min_total_by_date[$dk]);
                unset($day_max_total_by_date[$dk]);
            }
        }

        if (empty($confs_by_day)) return '';

        /* -----------------------------------------------------------------
         *  3. Generate HTML output
         * -----------------------------------------------------------------*/

        $scale = 200;
        ksort($confs_by_day);
        foreach ($confs_by_day as $date_key => &$day) {
            $day_min             = $day_min_total_by_date[$date_key] ?? (8 * 60);
            $day_max             = $day_max_total_by_date[$date_key] ?? (16 * 60);
            $day['min_hour']     = floor($day_min / 60);
            $day['max_hour']     = ceil($day_max / 60);
            if ($day['max_hour'] <= $day['min_hour'])
                $day['max_hour'] = $day['min_hour'] + 1; // Minimum two-hour view
        }
        unset($day);

        $output  = '<div class="conference-cap-warsawexpo" id="conference-cap-warsawexpo">';
            $output  = '<div class="conference-cap-warsawexpo__container">';
                $output .= '<h2 class="conference-cap-warsawexpo__heading">Sprawdź program konferencji</h2>';

                $output .= '<div class="conference-cap-warsawexpo__day-tabs">';
                    $day_keys   = array_keys($confs_by_day);
                    $day_index  = 1;
                    foreach ($day_keys as $k) {
                        $day_key = 'day' . $day_index;
                        $output .= '<button data-day="' . $day_key . '"' . ($day_index === 1 ? ' class="active"' : '') . '>';
                            $output .= '<span class="conference-cap-warsawexpo__day-name">Dzień ' . $day_index . '</span>';
                            $output .= '<span class="conference-cap-warsawexpo__day-date">' . esc_html($confs_by_day[$k]['display']) . '</span>';
                        $output .= '</button>';
                        $day_index++;
                    }
                $output .= '</div>'; // .conference-cap-warsawexpo__day-tabs

                $output .= '<div class="conference-cap-warsawexpo__expo-tabs">';

                    $firstDayKey = $day_keys[0];
                    $firstDayFairs = [];

                    foreach ($confs_by_day[$firstDayKey]['confs'] as $c) {
                        $firstDayFairs[$c['fair']] = true;   // Unique domains
                    }

                    $i = 0;
                    foreach (array_keys($firstDayFairs) as $fair) {
                        $img = 'https://' . $fair . '/doc/kafelek.jpg';
                        $output .= '<button class="conference-cap-warsawexpo__fair-btn'.($i==0?' active':'').'" data-fair="'.$fair.'">';
                            $output .= '<img src="'.esc_url($img).'" alt="'.esc_attr($fair).'">';
                        $output .= '</button>';
                        $i++;
                    }
                $output .= '</div>'; // .conference-cap-warsawexpo__expo-tabs


                $day_index = 1;
                foreach ($day_keys as $k) {
                    $day_key    = 'day' . $day_index;
                    $currentDay = $confs_by_day[$k];

                    $output .= '<div class="conference-cap-warsawexpo__day-content ' . $day_key . ($day_index === 1 ? ' active' : '') . '">';
                        $output .= '<div class="conference-cap-warsawexpo__schedule-container">';

                            $output .= '<div class="conference-cap-warsawexpo__time-axis">';
                                for ($h = $currentDay['min_hour']; $h <= $currentDay['max_hour']; $h++) {
                                    $output .= '<div class="conference-cap-warsawexpo__time-label" style="height:' . $scale . 'px">' . $h . ':00</div>';
                                }
                            $output .= '</div>';

                            // Hall columns
                            foreach ($currentDay['confs'] as $conf) {
                                $expoSlug = esc_attr( $conf['slug'] );
                                foreach ($conf['halls'] as $hall => $sessions) {
                                    $defaultHallWord = ($lang_lower === 'pl') ? 'hala' : 'hall';
                                    $isPlaceholder   = ($hall === '') || (mb_strtolower(trim($hall)) === $defaultHallWord);
                                    $output .= '<div class="conference-cap-warsawexpo__hall-column fair-' . esc_attr($conf['fair']) . '">';
                                        $output .= '<div class="conference-cap-warsawexpo__hall-header">';
                                            $output .= '<div class="conference-cap-warsawexpo__hall-banner-placeholder">';
                                                if (!empty($conf['img'])) {
                                                    $output .= '<img src="' . esc_url($conf['img']) . '" alt="Baner ' . esc_attr($hall) . '">';
                                                }
                                            $output .= '</div>';
                                            if (!$isPlaceholder) {
                                                $output .= '<div class="conference-cap-warsawexpo__hall-name">'.esc_html($hall).'</div>';
                                            }
                                        $output .= '</div>'; // .conference-cap-warsawexpo__hall-header

                                        // Individual sessions
                                        foreach ($sessions as $s) {
                                            $startOffset = 0;
                                            $height      = $scale;
                                            $duration    = 60;
                                            $normalized  = self::normalize_hour_string($s['hour'] ?? '');

                                            if ($normalized) {
                                                [$start_h, $start_m] = explode(':', $normalized['start']);
                                                $start_total         = intval($start_h) * 60 + intval($start_m);

                                                if ($normalized['end']) {
                                                    [$end_h, $end_m] = explode(':', $normalized['end']);
                                                    $end_total       = intval($end_h) * 60 + intval($end_m);
                                                } else {
                                                    $end_total       = $start_total + 60;
                                                }

                                                $offset_min   = $start_total - ($currentDay['min_hour'] * 60);
                                                $duration     = $end_total - $start_total;
                                                $startOffset  = ($offset_min / 60) * $scale + 220;
                                                $height       = ($duration / 60) * $scale;
                                            }

                                            $output .= '<div class="conference-cap-warsawexpo__event" style="top:' . intval($startOffset) . 'px; height:' . intval($height) . 'px;">';
                                                $event_title = $s['title'];
                                                if ($duration <= 75 && mb_strlen($event_title) > 75) {
                                                    $event_title = mb_substr($event_title, 0, 75) . '...';
                                                }
                                                $output .= '<div class="title">' . esc_html($event_title) . '</div>';
                                                $output .= '<div class="time">' . esc_html($s['hour']) . '</div>';
                                            $output .= '</div>'; // .conference-cap-warsawexpo__event
                                        }
                                    $output .= '</div>'; // .conference-cap-warsawexpo__hall-column
                                }
                            }

                        $output .= '</div>'; // .conference-cap-warsawexpo__schedule-container
                    $output .= '</div>'; // .conference-cap-warsawexpo__day-content
                    $day_index++;
                }

            $output .= '</div>'; // .conference-cap-warsawexpo__container
        $output .= '</div>'; // #conference-cap-warsawexpo

        /* -----------------------------------------------------------------
         *  4. Styles and JS
         * -----------------------------------------------------------------*/
        
        

        return $output;
    }
}

/**
 * New mode class wrapper for context-based rendering.
 */
final class PWE_Conference_Cap_WarsawExpo_Mode {

    public function render(array $context): string {
        return PWEConferenceCapWarsawExpo::output($context['attributes'] ?? array(), $context['language'] ?? 'PL');
    }

    public function get_assets(): array {
        return array(
            'css' => array('modes/warsawexpo/assets/warsawexpo.css'),
            'js'  => array('modes/warsawexpo/assets/warsawexpo.js'),
        );
    }
}
