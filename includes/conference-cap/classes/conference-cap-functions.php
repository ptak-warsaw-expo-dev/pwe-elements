<?php
class PWEConferenceCapFunctions extends PWEConferenceCap{    

    public static function findConferenceMode($new_class) {

        switch ($new_class){
            case 'PWEConferenceCapSimpleMode' : 
                return array (
                'php' => 'classes/conference-cap-simple-mode/conference-cap-simple-mode.php',
                'css' => 'classes/conference-cap-simple-mode/conference-cap-simple-mode-style.css',
                );
            case 'PWEConferenceCapMedalCeremony' : 
                return array (
                    'php' => 'classes/conference-cap-medal-ceremony/conference-cap-medal-ceremony.php',
                    'css' => 'classes/conference-cap-medal-ceremony/conference-cap-medal-ceremony-style.css',
                );
            default :
                return array (
                    'php' => 'classes/conference-cap-full-mode/conference-cap-full-mode.php',
                    'css' => 'classes/conference-cap-full-mode/conference-cap-full-mode-style.css',
                );
        };
    }

    public static function speakerImageMini($speaker_images) { 
        // Filtrowanie pustych wartości
        $head_images = array_filter($speaker_images);
        // Resetowanie indeksów tablicy
        $head_images = array_values($head_images); 
        
        // Jeśli nie ma obrazów, zwracamy pusty string
        if (empty($head_images)) {
            return ''; 
        }
    
        // Inicjalizacja kontenera na obrazy
        $speaker_html = '<div class="pwe-box-speakers-img">';
    
        // Pętla po obrazach i dynamiczne ustawianie ich pozycji
        foreach ($head_images as $i => $image_src) {    
            if (!empty($image_src)) {
                $z_index = (1 + $i);
                $margin_top_index = '';
                $max_width_index = "50%";
    
                // Ustawienia pozycji w zależności od liczby prelegentów
                switch (count($head_images)) {
                    case 1:
                        $top_index = "unset";
                        $left_index = "unset";
                        $max_width_index = "80%";
                        break;
    
                    case 2:
                        switch ($i) {
                            case 0:
                                $margin_top_index = "margin-top: 20px";
                                $max_width_index = "50%";
                                $top_index = "-20px";
                                $left_index = "10px";
                                break;
                            case 1:
                                $max_width_index = "50%";
                                $top_index = "0";
                                $left_index = "-10px";
                                break;
                        }
                        break;
    
                    case 3:
                        switch ($i) {
                            case 0:
                                $top_index = "0";
                                $left_index = "15px";
                                break;
                            case 1:
                                $top_index = "40px";
                                $left_index = "-15px";
                                break;
                            case 2:
                                $top_index = "-15px";
                                $left_index = "-30px";
                                break;
                        }
                        break;
    
                    default:
                        switch ($i) {
                            case 0:
                                $margin_top_index = 'margin-top: 5px !important;';
                                break;
                            case 1:
                                $left_index = "-10px";
                                break;
                            default:
                                $reszta = $i % 2;
                                if ($reszta == 0) {
                                    $top_index = ($i / 2 * -15) . "px";
                                    $left_index = "0";
                                } else {
                                    $top_index = (floor($i / 2) * -15) . "px";
                                    $left_index = "-10px";
                                }
                                break;
                        }
                }
    
                // Generowanie HTML obrazów
                $speaker_html .= '<img class="pwe-box-speaker" src="'. esc_url($image_src) .'" alt="speaker portrait" 
                    style="position:relative; z-index:'.$z_index.'; top:'.$top_index.'; left:'.$left_index.'; max-width: '.$max_width_index.';'.$margin_top_index.';" />';
            }
        }
    
        $speaker_html .= '</div>';
    
        return $speaker_html;
    }

    public static function pwe_convert_rgb_to_hex($content) {
        return preg_replace_callback('/rgb\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/i', function ($matches) {
            // Zabezpieczenie: ogranicz zakres wartości
            $r = max(0, min(255, (int)$matches[1]));
            $g = max(0, min(255, (int)$matches[2]));
            $b = max(0, min(255, (int)$matches[3]));
            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }, $content);
    }

    public static function copySpeakerImgByStructure(array $json): array {
        if (!isset($json['PL'], $json['EN'])) {
            return $json;
        }

        /* -------- 1.   iteracja po dniach wg pozycji -------- */
        $plDayKeys = array_keys($json['PL']);
        $enDayKeys = array_keys($json['EN']);
        $maxDays   = min(count($plDayKeys), count($enDayKeys));

        for ($d = 0; $d < $maxDays; $d++) {

            $plSessions = $json['PL'][$plDayKeys[$d]];
            $enSessions = &$json['EN'][$enDayKeys[$d]];

            if (!is_array($plSessions)) {
                continue;
            }

            /* -------- 2.   iteracja po prelekcjach (tylko 'pre‑X') -------- */
            foreach ($plSessions as $preKey => $plPre) {

                if (!is_array($plPre) || strpos($preKey, 'pre-') !== 0) {
                    continue;
                }
                if (!isset($enSessions[$preKey]) || !is_array($enSessions[$preKey])) {
                    continue;
                }

                $enPre = &$enSessions[$preKey];

                /* -------- 3.   legent‑Y – kopiuj url gdy w EN pusto -------- */
                foreach ($plPre as $fieldKey => $plField) {
                    if (!is_array($plField) || strpos($fieldKey, 'legent-') !== 0) {
                        continue;
                    }

                    if (!isset($enPre[$fieldKey]) || !is_array($enPre[$fieldKey])) {
                        continue;
                    }

                    if (empty($enPre[$fieldKey]['url']) && !empty($plField['url'])) {
                        $enPre[$fieldKey]['url'] = $plField['url'];
                    }
                }
            }
        }

        return $json;
    }

    public static function getConferencePatronLogosFromList($conf_id, $conf_slug, $logo_files = []) {
        $cap_db = PWECommonFunctions::connect_database();
        if (!$cap_db) {
            return '<!-- Brak połączenia z bazą danych CAP -->';
        }

        if (empty($logo_files)) {
            return '<!-- Brak logotypów -->';
        }

        // Pobierz dane dodatkowe z conf_adds
        $adds_raw = $cap_db->get_results(
            $cap_db->prepare("SELECT slug, data FROM conf_adds WHERE conf_id = %d", $conf_id),
            ARRAY_A
        );

        // Slugi => dane
        $adds = [];
        foreach ($adds_raw as $row) {
            $slug = trim($row['slug']);
            $adds[$slug] = json_decode($row['data'], true);
        }

        // URL do katalogu
        $patroni_dir_url = 'https://cap.warsawexpo.eu/public/uploads/conf/' . $conf_slug . '/patrons';
        $output = '';

        foreach ($logo_files as $slug) {
            $slug = trim($slug);
            $data = $adds[$slug] ?? [];

            $logo_url = $patroni_dir_url . '/' . $slug;
            $alt = !empty($data['alt']) ? esc_attr($data['alt']) : 'Patron Logo';
            $title = !empty($data['desc']) ? esc_attr($data['desc']) : '';
            $link = !empty($data['link']) ? esc_url($data['link']) : '';
            $class = 'conference_patroni_logo';

            $output .= '<div class="conference_cap__patrons-container-logo">';
            $img_html = '<img src="' . esc_url($logo_url) . '" data-no-lazy="1" alt="' . $alt . '" class="' . $class . '">';

            $output .= $link
                ? '<a href="' . $link . '" target="_blank" rel="noopener noreferrer">' . $img_html . '</a>'
                : $img_html;

            if (!empty($title)) {
                $output .= '<span class="conference_cap__patrons-container-logo-title">' . $title . '</span>';
            }

            $output .= '</div>';
        }

        return $output;
    }

    public static function getConferenceOrganizer($conf_id, $conf_slug, $lang) {
        $cap_db = PWECommonFunctions::connect_database();
        if (!$cap_db) {
            return null;
        }

        // Kolejność preferencji wg języka:
        $preferred_slugs = ($lang === 'PL')
            ? ['org-name_pl']
            : ['org-name_en', 'org-name_pl'];

        // Dodatkowy legacy fallback
        $all_slugs = array_merge($preferred_slugs, ['org-name']);

        // Zbuduj placeholdery do IN (...)
        $placeholders = implode(',', array_fill(0, count($all_slugs), '%s'));

        // Pobierz potencjalne wartości jednym zapytaniem
        $sql = $cap_db->prepare(
            "SELECT slug, data
            FROM conf_adds
            WHERE conf_id = %d
            AND slug IN ($placeholders)",
            array_merge([$conf_id], $all_slugs)
        );
        $rows = $cap_db->get_results($sql, ARRAY_A);

        // Zmapuj po slugach i oczyść
        $by_slug = [];
        if (!empty($rows)) {
            foreach ($rows as $r) {
                if (!empty($r['data']) && $r['data'] !== 'null') {
                    $by_slug[$r['slug']] = trim($r['data'], "\"");
                }
            }
        }

        // Wybierz wg preferencji językowych
        $organizer_name = '';
        foreach ($preferred_slugs as $slug_key) {
            if (!empty($by_slug[$slug_key])) {
                $organizer_name = $by_slug[$slug_key];
                break;
            }
        }

        // Jeśli nadal brak nazwy — nie pokazuj nic (nie ma sensu sprawdzać logo)
        if (empty($organizer_name)) {
            return null;
        }

        // Sprawdź logo (2xx/3xx -> OK)
        $logo_url = 'https://cap.warsawexpo.eu/public/uploads/conf/' . $conf_slug . '/organizer/conf_organizer.webp';
        $response = wp_remote_head($logo_url);
        $code = is_wp_error($response) ? 0 : (int) wp_remote_retrieve_response_code($response);
        if ($code < 200 || $code >= 400) {
            // Brak/nieosiągalne logo — zwróć tylko opis
            return [
                'logo_url' => null,
                'desc'     => $organizer_name,
            ];
        }

        return [
            'logo_url' => $logo_url,
            'desc'     => $organizer_name,
        ];
    }

    protected static function debugConferencesConsole( array $database_data ) {

        if ( ! is_user_logged_in() || ! current_user_can( 'administrator' ) ) {
            return;
        }
    
        $debug = array_map(
            static function ( $conf ) {
                return array(
                    'slug'       => $conf->conf_slug,
                    'updated'    => $conf->updated ?? null,
                    'updated_at' => $conf->updated_at ?? null,
                );
            },
            $database_data
        );
    
        wp_register_script( 'pwe-conference-cap-debug', '' );
        wp_add_inline_script(
            'pwe-conference-cap-debug',
            'console.groupCollapsed("PWEConferenceCap – recent changes");' .
            'console.table(' . wp_json_encode( $debug ) . ');' .
            'console.groupEnd();'
        );
        wp_enqueue_script( 'pwe-conference-cap-debug' );
    }

  }