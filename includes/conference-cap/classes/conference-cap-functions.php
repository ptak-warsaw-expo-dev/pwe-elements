<?php
class PWEConferenceCapFunctions {

    public static function findConferenceMode($new_class) {

        switch ($new_class){
            case 'PWEConferenceCapSimpleMode' :
                return array (
                'php' => 'modes/simple_mode/simple_mode.php',
                'css' => 'modes/simple_mode/assets/simple_mode.css',
                );
            case 'PWEConferenceCapMedalCeremony' :
                return array (
                    'php' => 'modes/medal_ceremony/medal_ceremony.php',
                    'css' => 'modes/medal_ceremony/assets/medal_ceremony.css',
                );
            default :
                return array (
                    'php' => 'modes/full_mode/full_mode.php',
                    'css' => 'modes/full_mode/assets/full_mode.css',
                );
        };
    }

    public static function speakerImageMini($speaker_images) {
        $head_images = array_filter($speaker_images);
        $head_images = array_values($head_images);

        if (empty($head_images)) {
            return '';
        }

        // Initialize the image container
        $speaker_html = '<div class="pwe-box-speakers-img">';

        foreach ($head_images as $i => $image_src) {
            if (!empty($image_src)) {
                $z_index = (1 + $i);
                $margin_top_index = '';
                $max_width_index = "50%";

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

                $speaker_html .= '<img class="pwe-box-speaker" src="'. esc_url($image_src) .'" alt="speaker portrait"
                    style="position:relative; z-index:'.$z_index.'; top:'.$top_index.'; left:'.$left_index.'; max-width: '.$max_width_index.';'.$margin_top_index.';" />';
            }
        }

        $speaker_html .= '</div>';

        return $speaker_html;
    }

    public static function pwe_convert_rgb_to_hex($content) {
        return preg_replace_callback('/rgb\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/i', function ($matches) {
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

        /* -------- 1. Iterate days by position -------- */
        $plDayKeys = array_keys($json['PL']);
        $enDayKeys = array_keys($json['EN']);
        $maxDays   = min(count($plDayKeys), count($enDayKeys));

        for ($d = 0; $d < $maxDays; $d++) {

            $plSessions = $json['PL'][$plDayKeys[$d]];
            $enSessions = &$json['EN'][$enDayKeys[$d]];

            if (!is_array($plSessions)) {
                continue;
            }

            foreach ($plSessions as $preKey => $plPre) {

                if (!is_array($plPre) || strpos($preKey, 'pre-') !== 0) {
                    continue;
                }
                if (!isset($enSessions[$preKey]) || !is_array($enSessions[$preKey])) {
                    continue;
                }

                $enPre = &$enSessions[$preKey];

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
            return '<!-- No CAP database connection -->';
        }

        if (empty($logo_files)) {
            return '<!-- No logos -->';
        }

        // Fetch additional data from conf_adds
        $adds_raw = $cap_db->get_results(
            $cap_db->prepare("SELECT slug, data FROM conf_adds WHERE conf_id = %d", $conf_id),
            ARRAY_A
        );

        // Slugs => data
        $adds = [];
        foreach ($adds_raw as $row) {
            $slug = trim($row['slug']);
            $adds[$slug] = json_decode($row['data'], true);
        }
        
        // Directory URL
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

        $preferred_slugs = ($lang === 'PL')
            ? ['org-name_pl']
            : ['org-name_en', 'org-name_pl'];

        // Additional legacy fallback
        $all_slugs = array_merge($preferred_slugs, ['org-name']);

        // Build placeholders for IN (...)
        $placeholders = implode(',', array_fill(0, count($all_slugs), '%s'));

        $sql = $cap_db->prepare(
            "SELECT slug, data
            FROM conf_adds
            WHERE conf_id = %d
            AND slug IN ($placeholders)",
            array_merge([$conf_id], $all_slugs)
        );
        $rows = $cap_db->get_results($sql, ARRAY_A);

        $by_slug = [];
        if (!empty($rows)) {
            foreach ($rows as $r) {
                if (!empty($r['data']) && $r['data'] !== 'null') {
                    $by_slug[$r['slug']] = trim($r['data'], "\"");
                }
            }
        }

        $organizer_name = '';
        foreach ($preferred_slugs as $slug_key) {
            if (!empty($by_slug[$slug_key])) {
                $organizer_name = $by_slug[$slug_key];
                break;
            }
        }

        if (empty($organizer_name)) {
            return null;
        }

        $logo_url = 'https://cap.warsawexpo.eu/public/uploads/conf/' . $conf_slug . '/organizer/conf_organizer.webp';
        $response = wp_remote_head($logo_url);
        $code = is_wp_error($response) ? 0 : (int) wp_remote_retrieve_response_code($response);
        if ($code < 200 || $code >= 400) {
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

    public static function getConferenceOrganizersAll($conf_slug) {
        $cap_db = PWECommonFunctions::connect_database();
        if (!$cap_db) {
            return [];
        }

        $conference = $cap_db->get_row($cap_db->prepare("
            SELECT id, organizers_img
            FROM conferences
            WHERE conf_slug = %s
        ", $conf_slug), ARRAY_A);

        if (!$conference || empty($conference['organizers_img'])) {
            return [];
        }

        $conf_id = intval($conference['id']);

        $logos = array_map('trim', explode(",", $conference['organizers_img']));

        $results = [];

        foreach ($logos as $logo) {
            if ($logo === "") continue;

            $slug = 'org-' . $logo;

            $conf_add = $cap_db->get_row($cap_db->prepare("
                SELECT data
                FROM conf_adds
                WHERE slug = %s
                AND conf_id = %d
            ", $slug, $conf_id), ARRAY_A);

            $data = [];
            if (!empty($conf_add['data'])) {
                $data = json_decode($conf_add['data'], true);
            }

            $results[] = [
                "src" => 'https://cap.warsawexpo.eu/public/uploads/conf/' . $conf_slug . '/organizer/' . $logo,
                "data" => $data
            ];
        }

        return $results;
    }




    public static function debugConferencesConsole( array $database_data ) {

        if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
            return '';
        }

        $debug = array_map(
            static function ( $conf ) {
                return array(
                    'id'         => $conf->id ?? null,
                    'slug'       => $conf->conf_slug ?? null,
                    'site_link'  => $conf->conf_site_link ?? null,
                    'updated'    => $conf->updated ?? null,
                    'updated_at' => $conf->updated_at ?? null,
                );
            },
            $database_data
        );

        return '<script>
            console.groupCollapsed("PWEConferenceCap – conferences order");
            console.table(' . wp_json_encode( $debug ) . ');
            console.groupEnd();
        </script>';
    }

  }
