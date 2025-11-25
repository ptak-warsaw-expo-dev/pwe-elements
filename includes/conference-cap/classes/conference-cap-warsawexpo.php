<?php
class PWEConferenceCapWarsawExpo {

    /* -----------------------------------------------------------------
     *  Zamiana etykiet dat "2 kwietnia 2025" → ['key'=>'2025-04-02','display'=>'02.04.25']
     * -----------------------------------------------------------------*/
    private static function parse_date_to_standard($input) {
        $input = trim($input);

        // 1️⃣ Rozbijamy po ';;' i sprawdzamy każdy segment
        foreach (explode(';;', $input) as $segment) {
            $segment = trim($segment);

            // a) Szukamy daty w formacie numerycznym
            if (preg_match('/(\d{1,2}[.\-\/]\d{1,2}[.\-\/]\d{2,4})/', $segment, $m)) {
                $num = str_replace(['/', '-'], '.', $m[1]);   // normalizacja separatorów

                // ── ustalenie długości roku ───────────────────────────────────────────────
                $parts     = explode('.', $num);
                $yearPart  = end($parts);                     // "25" albo "2025"
                $fmt       = (strlen($yearPart) === 4) ? 'd.m.Y' : 'd.m.y';

                $d = DateTime::createFromFormat($fmt, $num);
                if ($d) return [
                    'key'     => $d->format('Y-m-d'),
                    'display' => $d->format('d.m.y')
                ];
            }
            // b) Lub z nazwą miesiąca (styczeń, …)
            $pl_months = [
                'stycznia'=>'01','lutego'=>'02','marca'=>'03','kwietnia'=>'04',
                'maja'=>'05','czerwca'=>'06','lipca'=>'07','sierpnia'=>'08',
                'września'=>'09','października'=>'10','listopada'=>'11','grudnia'=>'12'
            ];
            foreach ($pl_months as $pl=>$nr) {
                if (stripos($segment, $pl) !== false) {
                    $tmp = str_ireplace($pl, $nr, $segment);
                    $tmp = preg_replace('/\s+/', ' ', $tmp);
                    $tmp = str_replace(' ', '.', $tmp);
                    $d   = DateTime::createFromFormat('d.m.Y', $tmp);
                    if ($d) return [
                        'key'     => $d->format('Y-m-d'),
                        'display' => $d->format('d.m.y')
                    ];
                }
            }
        }
        return null;   // brak daty
    }

    /* -----------------------------------------------------------------
     *  Normalizacja ciągu godzin "9.00-10.30" → ['start'=>'09:00','end'=>'10:30']
     * -----------------------------------------------------------------*/
    private static function normalize_hour_string($time_str) {
        $time_str = trim(str_replace(['–', '—', '–', '−'], '-', $time_str));
        $time_str = preg_replace('/\s+/', '', $time_str);
        $time_str = str_replace('.', ':', $time_str);

        if (preg_match('/^(\d{1,2}:\d{2})(?:-(\d{1,2}:\d{2}))?$/', $time_str, $m)) {
            return [
                'start' => $m[1],
                'end'   => $m[2] ?? null
            ];
        }
        return null;
    }

    /* -----------------------------------------------------------------
     *  Główna funkcja shortcode
     * -----------------------------------------------------------------*/
    public static function output($atts, $lang) {
        $lang_key   = strtoupper($lang);
        $lang_lower = strtolower($lang);
        $database_data = [];

        // 1. Pobranie danych z baz określonych domen
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

        // 2. Grupowanie sesji wg daty
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

                // Utworzenie wpisu dziennego, jeśli jeszcze nie istnieje
                if (!isset($confs_by_day[$date_key])) {
                    $confs_by_day[$date_key] = [
                        'display' => $date_display,
                        'confs'   => []
                    ];
                }

                /* ------------------------------------------------------
                 *  Hala domyślna z conf_location_* – szukamy po obu stronach
                 * ------------------------------------------------------*/
                $conf_location_raw = $conf->{"conf_location_" . $lang_lower} ?? '';
                $conf_hall_default = '';
                if (!empty($conf_location_raw)) {
                    $parts = array_map('trim', explode(';;', $conf_location_raw));
                    // Najpierw szukaj fragmentu zawierającego słowo "Hala" / "Hall"
                    foreach ($parts as $p) {
                        if ( ($lang_lower === 'pl' && stripos($p, 'hala') !== false) ||
                             ($lang_lower !== 'pl' && stripos($p, 'hall') !== false) ) {
                            $conf_hall_default = $p;
                            break;
                        }
                    }
                    // Jeśli nie znaleziono słowa kluczowego, bierz pierwszy segment
                    if ($conf_hall_default === '' && !empty($parts[0])) {
                        $conf_hall_default = $parts[0];
                    }
                }

                $halls                    = [];
                $day_min_total_global     = null;
                $day_max_total_global     = null;

                // ——— iteracja po pojedynczych wystąpieniach ———
                foreach ($sessions as $session_id => $s) {
                    if (empty($s['title'])) continue;

                    $hall_raw = $conf_hall_default !== '' ? $conf_hall_default : ($s['hall'] ?? '');
                    $hall = $hall_raw ?: (($lang_lower === 'pl') ? 'Hala' : 'Hall');
                    $hour_raw = $s['hour'] ?? ($s['time'] ?? '');
                    if (empty($hour_raw)) continue;

                    $normalized = self::normalize_hour_string($hour_raw);
                    if (!$normalized) continue;

                    // Zamiana na minuty od północy
                    [$start_h, $start_m] = explode(':', $normalized['start']);
                    $start_total         = intval($start_h) * 60 + intval($start_m);

                    if ($normalized['end']) {
                        [$end_h, $end_m] = explode(':', $normalized['end']);
                        $end_total       = intval($end_h) * 60 + intval($end_m);
                    } else {
                        $end_total       = $start_total + 60;
                    }

                    // Globalne minima i maksima dla danego dnia
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

                // Domyślne ramy czasu (8‑16) jeżeli w ogóle brak sesji dla tej konf.
                if (!isset($day_min_total_global)) $day_min_total_global = 8 * 60;
                if (!isset($day_max_total_global)) $day_max_total_global = 16 * 60;

                /* -----------------------------------------------------------------
                *  ✅ Punkt 1: całe „min/max + dopisanie konferencji” wykonujemy
                *             TYLKO wtedy, gdy w $halls faktycznie coś powstało
                * -----------------------------------------------------------------*/
                if (!empty($halls)) {

                    // ► aktualizacja globalnych granic dnia
                    if (!isset($day_min_total_by_date[$date_key]) ||
                        $day_min_total_global < $day_min_total_by_date[$date_key]) {
                        $day_min_total_by_date[$date_key] = $day_min_total_global;
                    }

                    if (!isset($day_max_total_by_date[$date_key]) ||
                        $day_max_total_global > $day_max_total_by_date[$date_key]) {
                        $day_max_total_by_date[$date_key] = $day_max_total_global;
                    }

                    // ► unikamy duplikatów konferencji
                    $already_added = false;
                    if (isset($confs_by_day[$date_key]['confs'])) {
                        foreach ($confs_by_day[$date_key]['confs'] as $existing) {
                            if ($existing['slug'] === $conf->conf_slug) {
                                $already_added = true;
                                break;
                            }
                        }
                    }

                    // ► dopisz konferencję, jeżeli jeszcze jej nie ma
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

        // 🆕 USUWA puste dni (brak konferencji)
        foreach ($confs_by_day as $dk => $dinfo) {
            if (empty($dinfo['confs'])) {
                unset($confs_by_day[$dk]);
                unset($day_min_total_by_date[$dk]);
                unset($day_max_total_by_date[$dk]);
            }
        }

        if (empty($confs_by_day)) return '';

        /* -----------------------------------------------------------------
         *  3. Generowanie outputu HTML
         * -----------------------------------------------------------------*/

        // 3a. Uzupełniamy "min_hour" i "max_hour" dla każdego dnia
        $scale = 200; // piksele na godzinę
        ksort($confs_by_day);
        foreach ($confs_by_day as $date_key => &$day) {
            $day_min             = $day_min_total_by_date[$date_key] ?? (8 * 60);
            $day_max             = $day_max_total_by_date[$date_key] ?? (16 * 60);
            $day['min_hour']     = floor($day_min / 60);
            $day['max_hour']     = ceil($day_max / 60);
            if ($day['max_hour'] <= $day['min_hour'])
                $day['max_hour'] = $day['min_hour'] + 1; // min. 2h widoku
        }
        unset($day); // 🔑 zrywa referencję – zapobiega duplikowaniu danych

        // 3b. Zakładki dni i kontenery
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

                /* ---------- BLOK EXPO‑TABS (targi) ---------- */
                $output .= '<div class="conference-cap-warsawexpo__expo-tabs">';

                    $firstDayKey = $day_keys[0];
                    $firstDayFairs = [];

                    foreach ($confs_by_day[$firstDayKey]['confs'] as $c) {
                        $firstDayFairs[$c['fair']] = true;   // unikatowe domeny
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


                // 3c. Treści konkretnego dnia
                $day_index = 1;
                foreach ($day_keys as $k) {
                    $day_key    = 'day' . $day_index;
                    $currentDay = $confs_by_day[$k];

                    $output .= '<div class="conference-cap-warsawexpo__day-content ' . $day_key . ($day_index === 1 ? ' active' : '') . '">';
                        $output .= '<div class="conference-cap-warsawexpo__schedule-container">';

                            // Oś czasu
                            $output .= '<div class="conference-cap-warsawexpo__time-axis">';
                                for ($h = $currentDay['min_hour']; $h <= $currentDay['max_hour']; $h++) {
                                    $output .= '<div class="conference-cap-warsawexpo__time-label" style="height:' . $scale . 'px">' . $h . ':00</div>';
                                }
                            $output .= '</div>';

                            // Kolumny hal
                            foreach ($currentDay['confs'] as $conf) {
                                $expoSlug = esc_attr( $conf['slug'] );
                                foreach ($conf['halls'] as $hall => $sessions) {
                                    $defaultHallWord = ($lang_lower === 'pl') ? 'hala' : 'hall';   // używamy małych liter
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

                                        // Pojedyncze sesje
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
                                                $startOffset  = ($offset_min / 60) * $scale + 220; // 220px – wysokość nagłówka hali
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
         *  4. Styl i JS
         * -----------------------------------------------------------------*/
        $output .= '
            <style>
                .conference-cap-warsawexpo__container {
                    max-width: 1200px;
                    width: 100%;
                    margin: 0 auto;
                }
                .conference-cap-warsawexpo__heading {
                    text-align: left;
                    margin-bottom: 30px;
                    color: #111;
                    font-size: 1.7rem;
                    font-weight: 700;
                }

                /* DAY TABS */
                .conference-cap-warsawexpo__day-tabs {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 20px;
                    flex-wrap: wrap;
                }
                .conference-cap-warsawexpo__day-tabs button {
                    flex: 1;
                    min-width: 120px;
                    background: #fff;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    padding: 10px 18px;
                    cursor: pointer;
                    font-weight: 500;
                    transition: all 0.3s;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                    text-align: center;
                }
                .conference-cap-warsawexpo__day-tabs button:hover { 
                    border-color: var(--main2-color); 
                }
                .conference-cap-warsawexpo__day-tabs button.active {
                    background: white;
                    border-color: var(--main2-color);
                    font-weight: 700;
                }
                .conference-cap-warsawexpo__day-tabs button.active .conference-cap-warsawexpo__day-name, 
                .conference-cap-warsawexpo__day-tabs button.active .conference-cap-warsawexpo__day-date {
                    color: var(--main2-color);
                }
                .conference-cap-warsawexpo__day-tabs .conference-cap-warsawexpo__day-name { 
                    font-size: 0.95rem; display: block; 
                }
                .conference-cap-warsawexpo__day-tabs .conference-cap-warsawexpo__day-date {
                    font-size: 0.75rem;
                    margin-top: 4px;
                    color: #777;
                    display: block;
                }
                .conference-cap-warsawexpo__expo-tabs {
                    min-height: 100px;
                    display: flex;
                    gap: 20px;
                    overflow-x: auto;
                    margin: 18px 0 25px;
                    justify-content: center;
                }
                .conference-cap-warsawexpo__fair-btn {
                    max-width: 160px;
                    height: 100%;
                    aspect-ratio: 5/3;
                    padding: 0px !important;
                    border-radius: 12px;
                    box-shadow: 0 1px 4px rgb(0 0 0 / 20%);
                    overflow: hidden;
                    transition: 0.3s all;
                }
                .conference-cap-warsawexpo__fair-btn img {
                    width: 100%;
                    height: auto;
                    object-fit: cover;
                    aspect-ratio: 5 / 3;
                }
                .conference-cap-warsawexpo__fair-btn:not(.active) {
                    filter: grayscale(1);
                }

                /* EXPO LOGOS */
                .conference-cap-warsawexpo__expo-logos {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 30px;
                    flex-wrap: wrap;
                }
                .conference-cap-warsawexpo__expo-logo-placeholder {
                    flex: 1;
                    min-width: 120px;
                    height: 100px;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    background: transparent;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 10px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                    overflow: hidden;
                }
                .conference-cap-warsawexpo__expo-logo-placeholder img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                }
                /* SELECTED DAY EXPO */
                .conference-cap-warsawexpo__selected-day-expo {
                    text-align: center;
                    margin-bottom: 20px;
                    font-size: 0.9rem;
                    font-weight: 600;
                    color: #333;
                }

                /* SCHEDULE */
                .conference-cap-warsawexpo__schedule-container{
                    display:flex;
                    flex-direction:row;
                    border-top:1px solid #eaeaea;
                    position:relative;
                    overflow-x:auto;
                    cursor:grab;

                    /* blokada zaznaczania tekstu */
                    -webkit-user-select:none;
                    -ms-user-select:none;
                    user-select:none;

                    /* płynniejsze przewijanie (opcjonalnie) */
                    scroll-behavior:smooth;
                }
                /* kursor podczas przeciągania */
                .conference-cap-warsawexpo__schedule-container.grabbing{
                    cursor:grabbing;
                    scroll-behavior:auto; 
                }
                /* gdyby w środku były elementy z własnym user‑select */
                .conference-cap-warsawexpo__schedule-container *{
                    -webkit-user-select:none;
                    -ms-user-select:none;
                    user-select:none;
                }
                /* szerokość osi (musi się zgadzać z padding-left kontenera) */
                :root { --axis-w: 56px; }      

                .conference-cap-warsawexpo__schedule-container{
                    position: relative;
                    padding-left: var(--axis-w);   /* miejsce na oś */
                }
                /* oś godzin „przykleja się” do lewej krawędzi i zostaje tam podczas przewijania */
                .conference-cap-warsawexpo__time-axis {
                    position: sticky;
                    left: -71px;
                    top: 0;
                    width: var(--axis-w);
                    margin-left: calc(-1 * var(--axis-w));
                    z-index: 2;
                    background: #fff;
                    pointer-events: none;
                    flex-shrink: 0;
                    margin-top: 216px;
                }
                .conference-cap-warsawexpo__time-label {
                    text-align: right;
                    padding: 0px 4px 0px 10px;
                    font-size: 0.8rem;
                    color: #999;
                    background: #fff;
                    font-weight: 500;
                    height: 80px;
                    position: relative;
                    top: -10px;
                }
                .conference-cap-warsawexpo__hall-column {
                    position: relative;
                    min-width: 33.5%;
                    padding: 0 10px 20px;
                    border-right: 1px solid #eaeaea;
                    background-image: repeating-linear-gradient(to bottom, transparent 0, transparent 199px, #eaeaea 199px, #eaeaea 200px );
                    background-position: 0 16px;
                }
                .conference-cap-warsawexpo__hall-column:last-child { 
                    border-right: none; 
                }
                .conference-cap-warsawexpo__hall-header {
                    text-align: center;
                    margin-bottom: 20px;
                    height: 220px;
                    display: flex;
                    gap: 20px;
                    flex-direction: column;
                    justify-content: flex-start;
                }
                .conference-cap-warsawexpo__hall-banner-placeholder {
                    width: 100%;
                    height: 140px;
                    border-radius: 8px;
                    margin-bottom: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }
                .conference-cap-warsawexpo__hall-banner-placeholder img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                }
                .conference-cap-warsawexpo__hall-name {
                    font-weight: 700;
                    font-size: 1rem;
                    color: #333;
                }
                /* EVENT */
                .conference-cap-warsawexpo__event {
                    position: absolute;
                    left: 0;
                    right: 0;
                    background: #f7f7f7;
                    border: 1px solid #e5e5e5;
                    border-radius: 6px;
                    min-height: 60px;
                    padding: 12px;
                    font-size: 0.85rem;
                    transition: 0.2s ease;
                    box-shadow: 0 1px 4px rgb(0 0 0 / 10%);
                    margin: 0 5px;
                    box-sizing: border-box;
                }
                .conference-cap-warsawexpo__event:hover { 
                    transform: translateY(-30px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
                }
                .conference-cap-warsawexpo__event .title {
                    font-weight: 600;
                    margin-bottom: 6px;
                    color: #444;
                    line-height: 1.4;
                }
                .conference-cap-warsawexpo__event .time {
                    font-size: 0.75rem;
                    color: #888;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                }
                .conference-cap-warsawexpo__event .time::before {
                    content: "🕒";
                    font-size: 0.8rem;
                    margin-right: 5px;
                    opacity: 0.7;
                }
                .conference-cap-warsawexpo__speaker {
                    position: absolute;
                    bottom: 12px;
                    right: 12px;
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    background: #ddd;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                }
                .conference-cap-warsawexpo__speaker img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                /* TOGGLE */
                .conference-cap-warsawexpo__day-content {
                    display: none; 
                }
                .conference-cap-warsawexpo__day-content.active { 
                    display: block; 
                }

                /* RESPONSIVE */
                @media(max-width:960px) {
                    .conference-cap-warsawexpo__hall-column {
                        position: relative;
                        min-width: 50%;
                    }
                }
                @media(max-width:570px) {
                    .conference-cap-warsawexpo__hall-column {
                        position: relative;
                        min-width: 80%;
                    }
                    .main-conference-cap-warsawexpo__container .row-conference-cap-warsawexpo__container .row-parent:has(.conference-cap-warsawexpo__schedule-container) {
                        padding: 0 !important;
                    }
                }
            </style>';
        $output .= '
            <script>
                /* --------------------------------------------------------------
                *  Jeden wspólny skrypt jQuery – tabs, kafelki targów, grab‑scroll
                * --------------------------------------------------------------*/
                jQuery(function ($) {

                /* ============================================================
                *  1.  Drag‑scroll (grab) dla .schedule‑conference-cap-warsawexpo__container
                * ============================================================*/
                $(".conference-cap-warsawexpo__schedule-container").each(function () {
                const $scroller  = $(this);
                let down = false, startX = 0, startY = 0, startScroll = 0;

                $scroller
                    .on("touchstart mousedown", function (e) {
                        const p   = e.originalEvent.touches ? e.originalEvent.touches[0] : e;
                        if (e.type === "mousedown" && e.which !== 1) return;      // tylko L-MB
                        down        = true;
                        startX      = p.pageX;
                        startY      = p.pageY;
                        startScroll = this.scrollLeft;
                        $scroller.addClass("grabbing");
                    })

                    .on("touchmove mousemove", function (e) {
                        if (!down) return;

                        const p  = e.originalEvent.touches ? e.originalEvent.touches[0] : e;
                        const dx = p.pageX - startX;
                        const dy = p.pageY - startY;

                        /* przejmujemy gest TYLKO gdy ruch > w poziomie */
                        if (Math.abs(dx) > Math.abs(dy)) {
                            if (e.originalEvent.cancelable) e.preventDefault();   // ✔️
                            this.scrollLeft = startScroll - dx;
                        }
                        /* w przeciwnym razie pozwalamy, by strona przewijała się pionowo */
                    })

                    .on("touchend touchcancel mouseup mouseleave", () => {
                        down = false;
                        $scroller.removeClass("grabbing");
                    });
                });

                /* ============================================================
                *  2.  Przełączanie dni (tabs)
                * ============================================================*/
                const $dayTabs    = $(".conference-cap-warsawexpo__day-tabs button");
                const $dayContent = $(".conference-cap-warsawexpo__day-content");

                $dayTabs.on("click", function () {
                    const day = $(this).data("day");
                    $dayTabs.removeClass("active");
                    $(this).addClass("active");
                    $dayContent.removeClass("active").filter("."+day).addClass("active");
                    rebuildFairBar(day);              // odśwież pasek targów
                });

                /* ============================================================
                *  3.  Pasek targów (fair‑btn) + filtrowanie kolumn
                * ============================================================*/
                const $fairBar = $(".conference-cap-warsawexpo__expo-tabs");

                // buduje kafelki targów dla wskazanego dnia
                function rebuildFairBar(dayKey) {
                    const $day      = $(".conference-cap-warsawexpo__day-content."+dayKey);
                    if (!$day.length) return;

                    $fairBar.empty();                 // czyść poprzednie
                    const fairs = {};

                    // zbierz unikatowe targi z klas "fair-XXX"
                    $day.find(".conference-cap-warsawexpo__hall-column").each(function () {
                        const m = this.className.match(/fair-([^\s]+)/);
                        if (m) fairs[m[1]] = true;
                    });

                    // generuj kafelki
                    $.each(fairs, function (fair, _v) {
                        var $btn = $("<button class=\"conference-cap-warsawexpo__fair-btn\"></button>")
                                    .data("fair", fair)
                                    .append(
                                        "<img src=\"https://" + fair + "/doc/kafelek.jpg\" alt=\"" + fair + "\">"
                                    );
                        $fairBar.append($btn);
                    });

                    bindFairClicks(dayKey);

                    // domyślnie nic nie jest aktywne → widać wszystkie kolumny
                }

                // klik w kafelek targów
                function bindFairClicks(dayKey) {
                    $fairBar.find(".conference-cap-warsawexpo__fair-btn").off("click").on("click", function () {
                        const $btn      = $(this);
                        const isActive  = $btn.hasClass("active");

                        $fairBar.find(".conference-cap-warsawexpo__fair-btn").removeClass("active");

                        if (isActive) {
                            showAllColumns(dayKey);           // ponowne kliknięcie → reset filtra
                            return;
                        }

                        $btn.addClass("active");
                        filterColumns($btn.data("fair"), dayKey);
                    });
                }

                // pokazuje tylko kolumny z wybranego targu
                function filterColumns(slug, dayKey) {
                    $(".conference-cap-warsawexpo__day-content."+dayKey+" .conference-cap-warsawexpo__hall-column").each(function () {
                        const fair = (this.className.match(/fair-([^\s]+)/) || [null,""])[1];
                        $(this).toggle(fair === slug);
                    });
                }

                // pokazuje wszystkie kolumny
                function showAllColumns(dayKey) {
                    $(".conference-cap-warsawexpo__day-content."+dayKey+" .conference-cap-warsawexpo__hall-column").show();
                }

                /* pierwsze uruchomienie dla day1 */
                rebuildFairBar("day1");
                });
            </script>';

        return $output;
    }
}
