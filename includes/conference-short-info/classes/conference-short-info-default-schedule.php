<?php

class PWEConferenceShortInfoDefaultSchedule extends PWEConferenceShortInfo {

    public static function initElements() {
        return [];
    }

    private static function is_mobile_device(): bool {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $mobile_agents = ['Mobile', 'Android', 'Silk/', 'Kindle', 'BlackBerry', 'Opera Mini', 'Opera Mobi', 'iPhone', 'iPod', 'iPad'];

        foreach ($mobile_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {

        $fair_days = self::getFairDaysFromShortcodes();

        $lang = (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE === 'en') ? 'EN' : 'PL';

        // if ($fair_start && $fair_end) {
        //     $interval = new DateInterval('P1D');
        //     $period = new DatePeriod($fair_start, $interval, $fair_end);
        //     foreach ($period as $day) {
        //         $fair_days[] = $day->format('Y-m-d');
        //     }
        // }

        $total_days = count($fair_days);
        if ($total_days === 0) return '<p>Brak danych o dniach targowych.</p>';

        $processed_conferences = [];
        foreach ($all_conferences as $conf) {
            $conf_slug = $conf->conf_slug;
            $organizer_info = self::getConferenceOrganizer($conf->id, $conf_slug, $lang);

            if (!$organizer_info) continue;

            $logo = $organizer_info['logo_url'];
            $organizer_name = $organizer_info['desc'];

            $decoded_data = json_decode($conf->conf_data, true);

            if (!is_array($decoded_data) || !isset($decoded_data[$lang])) continue;

            $keys = array_diff(array_keys($decoded_data[$lang]), ['main-desc']);
            if (empty($keys)) {
                if (current_user_can('manage_options')) {
                    echo "<script>console.log('Brak dni (tylko main-desc) | slug: " . addslashes($conf->conf_slug) . "');</script>";
                }
                continue;
            }

            $conference_dates = [];
            foreach (array_keys($decoded_data[$lang]) as $key) {
                if ($key === 'main-desc') continue;

                $parsed_date = self::parse_conference_key_to_date($key, $conf->conf_slug);
                if ($parsed_date) {
                    $conference_dates[] = $parsed_date;
                }
            }

            $conference_dates = array_filter($conference_dates, fn($date) => in_array($date, $fair_days));

            if (empty($conference_dates)) continue;

            $start_date = min($conference_dates);
            $end_date = max($conference_dates);
            $start_index = array_search($start_date, $fair_days);
            $end_index   = array_search($end_date, $fair_days);

            if ($start_index === false || $end_index === false) continue;

            $processed_conferences[] = [
                'title' => PWECommonFunctions::languageChecker($conf->conf_name_pl, $conf->conf_name_en ?? $conf->conf_name_pl),
                'logo' => $logo,
                'organizer' => $organizer_name,
                'start_index' => $start_index,
                'end_index' => $end_index,
                'slug' => $conf_slug
            ];
        }

        $is_mobile = self::is_mobile_device();

        $grouped_conferences = $is_mobile ? [] : array_chunk($processed_conferences, 5);
        $use_swiper = !$is_mobile && count($grouped_conferences) > 1;

        $output = '';

        $output .= '
            <style>
                .' . $rnd_class . ' .pwe-conf-short-info-default__wrapper {
                    background-color: color-mix(in srgb, var(--accent-color) 40%, white 60%);
                    background-color: #f2f2f2;
                    box-shadow: 0 0 12px -3px #888888;
                    padding: 24px 12px;
                    border-radius: 12px;
                    margin: 0 !important;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-default__top {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0 14px;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-default__top img {
                    max-width: 260px;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-default__top h3 {
                    font-size: 29px;
                    font-weight: 700;
                    max-width: 560px;
                    margin: 0;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__title-container {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                    text-align: right;
                }

                :root {
                    --border-color: #f2f2f2;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__table {
                    width: 100%;
                    border-collapse:
                    collapse; margin: 0px;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-default__table th,
                .' . $rnd_class . ' .pwe-conf-short-info-default__table td {
                    border: 2px solid var(--border-color) !important;
                    padding: 6px;
                    text-align: center;
                    background: white !important;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-default__timeline-bar {
                    height: 20px;
                    background-color: var(--accent-color);
                    animation: slide 1s ease-out;
                    position: relative; border-radius: 4px;
                }
                @keyframes slide { from { width: 0; } to { width: 100%; } }

                .' . $rnd_class . ' .pwe-conf-short-info-default__table td:not(:nth-child(1)):not(:nth-child(2)) {
                    border-left: none !important;
                    border-right: none !important;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__wrapper .pwe-conf-short-info-default__table td:last-of-type {
                    border-right: 2px solid var(--border-color) !important;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__table tbody tr:not(:nth-child(1)) {
                    border-top-style: dashed;
                    border-bottom-style: dashed;
                    border-color: var(--border-color);
                }
                .' . $rnd_class . ' .pwe-conf-short-info-default__org-logo {
                    max-height: 60px;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__buttons {
                    display: flex;
                    justify-content: space-around;
                    align-items: center;
                    margin-top: 18px;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__btn {
                    background-color: var(--main2-color);
                    color: white !important;
                    padding: 8px 24px;
                    min-width: 240px;
                    text-align: center;
                    border-radius: 8px;
                    text-transform: uppercase;
                    font-weight: 500;
                    font-size: 14px;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__row-link {
                    transition: 0.3s;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__row-link:hover {
                    transform: scale(1.01);
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__row-link:hover td {
                    background-color: color-mix(in srgb, var(--accent-color) 10%, white 90%) !important;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__conf-name {
                    font-size: 16px !important;
                    margin: 0;
                    color: black;
                }

                .' . $rnd_class . ' .swiper-horizontal>.swiper-pagination-bullets,
                .' . $rnd_class . ' .swiper-pagination-bullets.swiper-pagination-horizontal,
                .' . $rnd_class . ' .swiper-pagination-custom, .swiper-pagination-fraction {
                    bottom: 10%;
                }

                .' . $rnd_class . ' .swiper-pagination-bullet-active {
                    background: var(--accent-color);
                }

                .' . $rnd_class . ' .swiper-pagination-bullet {
                    width: 10px;
                    height: 10px;
                }

                @media (min-width: 768px) {
                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-list-wrapper {
                        display: none;
                    }
                }

                @media (max-width: 768px) {
                    .' . $rnd_class . ' .pwe-conf-short-info-default__top {
                        flex-direction: column;
                        text-align: center;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__title-container {
                        flex-direction: column-reverse;
                        text-align: center;
                        align-items: center;
                        gap: 6px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__top img {
                        max-width: 180px;
                        margin-bottom: 12px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__top h2 {
                        font-size: 12px;
                        line-height: 1.3;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__top h3 {
                        font-size: 16px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__table {
                        display: block;
                        width: 100%;
                        overflow-x: auto;
                        -webkit-overflow-scrolling: touch;
                        border-collapse: separate;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__table table,
                    .' . $rnd_class . ' .pwe-conf-short-info-default__table thead,
                    .' . $rnd_class . ' .pwe-conf-short-info-default__table tbody,
                    .' . $rnd_class . ' .pwe-conf-short-info-default__table th,
                    .' . $rnd_class . ' .pwe-conf-short-info-default__table td,
                    .' . $rnd_class . ' .pwe-conf-short-info-default__table tr {
                        white-space: nowrap;
                        display: none;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__btn {
                        min-width: 160px;
                        padding: 10px 14px;
                        font-size: 12px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__buttons {
                        flex-direction: column;
                        gap: 10px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-list-wrapper {
                        overflow-x: auto;
                        scroll-snap-type: x mandatory;
                        -webkit-overflow-scrolling: touch;
                        padding: 0 12px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-list {
                        display: flex;
                        gap: 16px;
                        padding: 16px 0;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-card {
                        flex: 0 0 80%;
                        scroll-snap-align: center;
                        background: white;
                        border-radius: 12px;
                        padding: 16px;
                        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
                        text-align: center;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-around;
                        align-items: center;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-card img {
                        max-width: 80%;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-card h3 {
                        margin-top: 18px;
                        font-size: 14px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-card p {
                        font-size: 12px;
                    }

                    .' . $rnd_class . ' .pwe-conf-short-info-default__mobile-card em {
                        background: var(--accent-color);
                        padding: 4px 10px;
                        color: white;
                        border-radius: 12px;
                        font-weight: 500;
                    }
                }
            </style>';

        $output .= '
        <div class="pwe-conf-short-info-default__wrapper">
            <div class="pwe-conf-short-info-default__top">
                <img src="/doc/kongres-color.webp" alt="Congress logo">
                <div class="pwe-conf-short-info-default__title-container">
                    <h2 class="pwe-conf-short-info-default__conf-name">' . do_shortcode('[trade_fair_conferance]') . '</h2>
                    <h3>' . $title . '</h3>
                </div>
            </div>';
            if (!$is_mobile) {
                $output .= '
                <div class="pwe-conf-short-info-default__multi-table-wrapper">';
                    if ($use_swiper) {
                        $output .= '
                        <div class="swiper">
                            <div class="swiper-wrapper">';
                    }
                    foreach ($grouped_conferences as $index => $group) {
                        if ($use_swiper) {
                            $output .= '<div class="swiper-slide">';
                        }
                        $output .= '<table class="pwe-conf-short-info-default__table">';
                            $output .= '<thead><tr><th>' . PWEConferenceShortInfo::multi_translation("organizer") . '</th><th>' . PWEConferenceShortInfo::multi_translation("subject") . '</th>';
                            foreach ($fair_days as $date) {
                                $output .= '<th>' . date('d.m', strtotime($date)) . '</th>';
                            }
                            $output .= '</tr></thead>
                            <tbody>';

                                foreach ($group as $conf) {
                                    $output .= '
                                    <tr class="pwe-conf-short-info-default__row-link" data-href="/' . PWEConferenceShortInfo::multi_translation("conferences_link") . '/?konferencja=' . esc_attr($conf['slug']) . '">
                                        <td><img src="' . esc_url($conf['logo']) . '" alt="" class="pwe-conf-short-info-default__org-logo"></td>
                                        <td><strong>' . esc_html(str_replace('<br>', '', $conf['title'])) . '</strong><br><small>' . esc_html($conf['organizer']) . '</small></td>';

                                        for ($i = 0; $i < $total_days; $i++) {
                                            if ($i === $conf['start_index']) {
                                                $colspan = $conf['end_index'] - $conf['start_index'] + 1;
                                                $output .= '<td colspan="' . $colspan . '"><div class="pwe-conf-short-info-default__timeline-bar" style="width:100%"></div></td>';
                                                $i = $conf['end_index'];
                                            } else {
                                                $output .= '<td></td>';
                                            }
                                        }
                                    $output .= '</tr>';
                                }

                            $output .= '</tbody>
                        </table>';
                        if ($use_swiper) {
                            $output .= '</div>'; // zamknięcie .swiper-slide
                        }
                    }
                    if ($use_swiper) {
                            $output .= '</div>'; // .swiper-wrapper
                            $output .= '
                            </div>
                            <div class="swiper-pagination"></div>
                        '; // .swiper
                    }

                $output .= '</div>'; // zamknięcie wrappera
            }

            $output .= '
            <div class="pwe-conf-short-info-default__mobile-list-wrapper">
                <div class="pwe-conf-short-info-default__mobile-list">';

                    foreach ($processed_conferences as $conf) {
                        $conf_days = array_slice($fair_days, $conf['start_index'], $conf['end_index'] - $conf['start_index'] + 1);
                        $conf_days_formatted = implode(', ', array_map(fn($d) => date('d.m', strtotime($d)), $conf_days));

                        $output .= '
                        <div class="pwe-conf-short-info-default__mobile-card">
                            <img src="' . esc_url($conf['logo']) . '" alt="">
                            <h3>' . esc_html($conf['title']) . '</h3>
                            <p><strong>' . esc_html($conf['organizer']) . '</strong></p>
                            <p><em>' . $conf_days_formatted . '</em></p>
                        </div>';
                    }

                $output .= '
                </div>
            </div>
        </div>';

        $output .= '
        <div class="pwe-conf-short-info-default__buttons">
            <a href="' . PWEConferenceShortInfo::multi_translation("registration_link") . '" class="pwe-conf-short-info-default__btn">' . PWEConferenceShortInfo::multi_translation("take_part") . '</a>
            <a href="' . PWEConferenceShortInfo::multi_translation("conferences_link") . '" class="pwe-conf-short-info-default__btn secondary">' . PWEConferenceShortInfo::multi_translation("find_out_more") . '</a>
        </div>';

        if ($use_swiper) {
            include_once plugin_dir_path(__FILE__) . '/../../../scripts/swiper.php';

            $swiper_options = [
                'loop' => false,
                'autoHeight' => true,
                'spaceBetween' => 24,
                'autoplay' => false,
            ];

            $swiper_breakpoints = json_encode([
                ['breakpoint_width' => 100, 'breakpoint_slides' => 1],
                ['breakpoint_width' => 768, 'breakpoint_slides' => 1],
                ['breakpoint_width' => 1200, 'breakpoint_slides' => 1]
            ]);

            $output .= PWESwiperScripts::swiperScripts('conference-pagination', '.pwe-conf-short-info-default__multi-table-wrapper', 'true', 'true', 'false', $swiper_options, $swiper_breakpoints
            );
        }

        $output .= '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelectorAll(".pwe-conf-short-info-default__row-link").forEach(function(row) {
                        row.style.cursor = "pointer";
                        row.addEventListener("click", function() {
                            window.location = row.getAttribute("data-href");
                        });
                    });
                });
            </script>';

        return $output;
    }
}
