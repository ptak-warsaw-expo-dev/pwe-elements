<?php

trait PWEConferenceShortInfoFunctions {

    /** WYBÓR KLASY RENDERERA (widok skrótu) */
    public static function getConferenceRendererClass($domain, $fair_group) {

        $base   = plugin_dir_path(__FILE__);
        $domain = strtolower((string) $domain); // opcjonalna normalizacja

        // Mapowanie domen → [plik, klasa]
        $domainMap = [
            'warsawhome.eu'           => ['conference-short-info-home.php',  'PWEConferenceShortInfoHome'],
            'warsawhomefurniture.com' => ['conference-short-info-home.php',  'PWEConferenceShortInfoHome'],
            'warsawhometextile.com'   => ['conference-short-info-home.php',  'PWEConferenceShortInfoHome'],
            'warsawhomelight.com'     => ['conference-short-info-home.php',  'PWEConferenceShortInfoHome'],
            'warsawhomekitchen.com'   => ['conference-short-info-home.php',  'PWEConferenceShortInfoHome'],
            'warsawhomebathroom.com'  => ['conference-short-info-home.php',  'PWEConferenceShortInfoHome'],
            'warsawbuild.eu'          => ['conference-short-info-home.php',  'PWEConferenceShortInfoHome'],
            'mr.glasstec.pll'         => ['conference-short-info-solar.php', 'PWEConferenceShortInfoSolar'],
            'remadays.com'            => ['conference-short-info-rema.php', 'PWEConferenceShortInfoRema'],
            'warsawhvacexpo.com'      => ['conference-short-info-hvac.php', 'PWEConferenceShortInfoHvac'],
            'industryweek.pl'         => ['conference-short-info-pack.php', 'PWEConferenceShortInfoPack'],
        ];

        // Najpierw decyzja po domenie (bez zmian w logice)
        if (isset($domainMap[$domain])) {
            require_once $base . '/' . $domainMap[$domain][0];
            return $domainMap[$domain][1];
        }

        switch ($fair_group) {
            case 'gr1':
                require_once plugin_dir_path(__FILE__) . '/conference-short-info-gr1.php';
                return 'PWEConferenceShortInfoGr1';

            default:
                require_once plugin_dir_path(__FILE__) . '/conference-short-info-default.php';
                return 'PWEConferenceShortInfoDefault';
        }
    }

    /** WYBÓR KLASY RENDERERA (widok harmonogramu) */
    public static function getConferenceRendererClassSchedule($domain, $fair_group) {

        switch ($fair_group) {
            case 'gr3':
                require_once plugin_dir_path(__FILE__) . '/conference-short-info-gr3-schedule.php';
                return 'PWEConferenceShortInfoGr3Schedule';

            default:
                require_once plugin_dir_path(__FILE__) . '/conference-short-info-default-schedule.php';
                return 'PWEConferenceShortInfoDefaultSchedule';
        }
    }

    /** DNI TARGOWE NA PODSTAWIE SHORTCÓDÓW */
    public static function getFairDaysFromShortcodes(): array {

        $start_raw = do_shortcode('[trade_fair_datetotimer]');
        $end_raw   = do_shortcode('[trade_fair_enddata]');

        $start = DateTime::createFromFormat('Y/m/d H:i', $start_raw);
        $end   = DateTime::createFromFormat('Y/m/d H:i', $end_raw);
        if (!$start || !$end) return [];

        if ($end < $start) [$start, $end] = [$end, $start];

        $days = [];
        $period = new DatePeriod($start, new DateInterval('P1D'), (clone $end));
        foreach ($period as $d) $days[] = $d->format('Y-m-d');
        return $days;
    }

    /** Rok końca targów (YYYY) na podstawie shortcode'u */
    public static function getFairEndYear(): ?int {
        $end_raw = do_shortcode('[trade_fair_enddata]');           // np. 2025/10/15 18:00
        $end     = DateTime::createFromFormat('Y/m/d H:i', $end_raw);
        return $end ? (int)$end->format('Y') : null;
    }

    /** Wyciąga rok (YYYY) ze slug’a, np. "akademia-budowy-domu-warsaw-home-2025" -> 2025 */
    public static function getYearFromSlug(string $slug): ?int {
        // dopasuj 4-cyfrowy rok „w segmentach” sluga
        if (preg_match('~(?:^|-)(20\d{2})(?:-|$)~', $slug, $m)) {
            return (int)$m[1];
        }
        return null;
    }

    /** Filtr: konferencje „aktualne” = rok w slug’u == rok końca targów */
    public static function filterCurrentConferencesByEndYear(array $all_conferences): array {
        $fair_end_year = self::getFairEndYear();
        if (!$fair_end_year) return [];
        $out = [];
        foreach ($all_conferences as $conf) {
            $y = self::getYearFromSlug((string)$conf->conf_slug);
            if ($y && $y === $fair_end_year) $out[] = $conf;
        }
        return $out;
    }

    /** CZY SĄ KONFERENCJE W DNIACH TARGOWYCH */
    public static function hasValidConferences($all_conferences, $fair_days): bool {

        if (empty($fair_days)) return false;

        foreach ($all_conferences as $conf) {
            $decoded_data = json_decode($conf->conf_data ?? '', true);
            $lang = (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE === 'en') ? 'EN' : 'PL';
            if (!is_array($decoded_data) || !isset($decoded_data[$lang])) continue;

            foreach (array_keys($decoded_data[$lang]) as $key) {
                if ($key === 'main-desc') continue;
                $parsed_date = self::parse_conference_key_to_date($key, $conf->conf_slug);
                if ($parsed_date && in_array($parsed_date, $fair_days, true)) {
                    return true;
                }
            }
        }
        return false;
    }


    /** PARSOWANIE DATY Z KLUCZA */
    public static function parse_conference_key_to_date($key, $conf_slug = '') {
        $candidates = [];

        foreach (explode(';;', (string)$key) as $piece) {
            $piece = trim(html_entity_decode(strip_tags($piece)));
            if ($piece !== '') $candidates[] = $piece;
        }
        if (empty($candidates)) {
            $candidates[] = trim(html_entity_decode(strip_tags((string)$key)));
        }

        // Regexy dla różnych formatów
        $date_patterns = [
            'dmy_slash' => '\b\d{1,2}/\d{1,2}/\d{4}\b',
            'dmy_dot'   => '\b\d{1,2}\.\d{1,2}\.\d{4}\b',
            'ymd_dash'  => '\b\d{4}-\d{1,2}-\d{1,2}\b',
            'ymd_slash' => '\b\d{4}/\d{1,2}/\d{1,2}\b',
            'range'     => '\b\d{1,2}-\d{1,2}[\.\/-]\d{1,2}[\.\/-]\d{4}\b',
        ];

        $formats_map = [
            'dmy_slash' => 'd/m/Y',
            'dmy_dot'   => 'd.m.Y',
            'ymd_dash'  => 'Y-m-d',
            'ymd_slash' => 'Y/m/d',
        ];

        foreach ($candidates as $raw) {
            $raw = trim(preg_replace('/[^\x20-\x7E]/', '', $raw)); // usuń dziwne znaki

            foreach ($date_patterns as $type => $pattern) {
                if (preg_match("~$pattern~u", $raw, $m)) {
                    $found = $m[0];

                    if ($type === 'range') {
                        if (preg_match('~^(\d{1,2})-(\d{1,2})([\.\/-]\d{1,2}[\.\/-]\d{4})$~', $found, $parts)) {
                            $found = $parts[1] . $parts[3];
                            $type = (strpos($parts[3], '.') !== false) ? 'dmy_dot' : 'dmy_slash';
                        }
                    }

                    if (isset($formats_map[$type])) {
                        $fmt = $formats_map[$type];
                        $dt = DateTime::createFromFormat($fmt, $found);
                        $errors = DateTime::getLastErrors();

                        if ($dt && (empty($errors['error_count']) || $errors['error_count'] == 0)) {
                            return $dt->format('Y-m-d');
                        }
                    }

                    // Jeśli createFromFormat zawiodło → fallback na strtotime()
                    $ts = strtotime($found);
                    if ($ts !== false) {
                        return date('Y-m-d', $ts);
                    }
                }
            }
        }

        echo "<script>console.log('Nieparsowalna data | slug: "
            . addslashes($conf_slug) . " | key: " . addslashes((string)$key) . "');</script>";
        return null;
    }




    /** ORGANIZATOR KONFERENCJI (logo + opis) */
    public static function getConferenceOrganizer($conf_id, $conf_slug, $lang) {

        $logo_url = 'https://cap.warsawexpo.eu/public/uploads/conf/' . $conf_slug . '/organizer/conf_organizer.webp';
        $organizer_name = '';

        $preferred_slugs = ($lang === 'PL') ? ['org-name_pl'] : ['org-name_en', 'org-name_pl'];

        $cap_db = PWECommonFunctions::connect_database();
        if ($cap_db) {
            $placeholders = implode(',', array_fill(0, count($preferred_slugs), '%s'));
            $sql = $cap_db->prepare(
                "SELECT slug, data FROM conf_adds WHERE conf_id = %d AND slug IN ($placeholders)",
                array_merge([$conf_id], $preferred_slugs)
            );
            $rows = $cap_db->get_results($sql, ARRAY_A);

            $by_slug = [];
            foreach ($rows as $r) {
                if (!empty($r['data']) && $r['data'] !== 'null') {
                    $by_slug[$r['slug']] = trim($r['data'], "\"");
                }
            }
            foreach ($preferred_slugs as $slug_key) {
                if (!empty($by_slug[$slug_key])) { $organizer_name = $by_slug[$slug_key]; break; }
            }
        }

        $has_logo = false;
        $response = wp_remote_head($logo_url);
        $code = is_wp_error($response) ? 0 : (int) wp_remote_retrieve_response_code($response);
        if ($code >= 200 && $code < 400) $has_logo = true;

        if (empty($organizer_name) && !$has_logo) return null;

        return ['logo_url' => $has_logo ? $logo_url : null, 'desc' => $organizer_name];
    }

    /** SORTOWANIE: zwykłe -> medal -> panel; w grupach po conf_order (rosnąco) */
    public static function sortConferencesCustom(array $confs): array {
        usort($confs, function($a, $b) {

            // 0 = zwykłe, 1 = medal, 2 = panel
            $groupOf = static function($c) {
                $slug = strtolower($c->conf_slug ?? '');
                if (strpos($slug, 'panel') !== false) return 2;
                if (strpos($slug, 'medal') !== false) return 1;
                return 0;
            };

            $ga = $groupOf($a);
            $gb = $groupOf($b);
            if ($ga !== $gb) {
                return $ga <=> $gb; // różne -> medal -> panel
            }

            // w obrębie tej samej grupy sort po conf_order (liczbowo, puste na koniec)
            $oa = isset($a->conf_order) && $a->conf_order !== '' ? (int)$a->conf_order : PHP_INT_MAX;
            $ob = isset($b->conf_order) && $b->conf_order !== '' ? (int)$b->conf_order : PHP_INT_MAX;
            if ($oa !== $ob) return $oa <=> $ob;

            // tie-breaker dla stabilności
            $na = $a->conf_name_pl ?? $a->conf_name_en ?? '';
            $nb = $b->conf_name_pl ?? $b->conf_name_en ?? '';
            $cmp = strcmp($na, $nb);
            if ($cmp !== 0) return $cmp;

            return (int)($a->id ?? 0) <=> (int)($b->id ?? 0);
        });

        return $confs;
    }

    /** Utility: zamiana ;; na <br> w nazwach konferencji */
    public static function normalizeConferenceNames(array $confs): array {
        foreach ($confs as $conf) {
            if (isset($conf->conf_name_pl)) {
                $conf->conf_name_pl = str_replace(';;', '<br>', $conf->conf_name_pl);
            }
            if (isset($conf->conf_name_en)) {
                $conf->conf_name_en = str_replace(';;', '<br>', $conf->conf_name_en);
            }
        }
        return $confs;
    }

}
