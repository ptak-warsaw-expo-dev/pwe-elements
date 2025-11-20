<?php

class PWEConferenceShortInfoGr3Schedule extends PWEConferenceShortInfo {

    public static function confLang($pl, $en) {
        $current = get_locale();

        if ($current === 'pl_PL') {
            return $pl ?: $en;
        }

        return $en ?: $pl;
    }

    public static function initElements() {
        return [];
    }

    private static function limit_words(string $text, int $max_words = 12, string $ellipsis = '…'): string {
        $text = trim(strip_tags($text));               // na wszelki wypadek
        if ($max_words <= 0 || $text === '') return '';
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (!$words) return '';
        if (count($words) <= $max_words) return $text;
        return implode(' ', array_slice($words, 0, $max_words)) . $ellipsis;
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {

        $lang = (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE === 'en') ? 'EN' : 'PL';

        $processed_conferences = [];
        foreach ($all_conferences as $conf) {

            $conf_slug = $conf->conf_slug;
            $organizer_info = self::getConferenceOrganizer($conf->id, $conf_slug, $lang);

            // if (!$organizer_info) continue;

            $logo = $organizer_info['logo_url'];
            $logo_alt = $organizer_info['desc'];

            $fair_days = self::getFairDaysFromShortcodes();

            if (!empty($fair_start) && !empty($fair_end)) {
                $interval = new DateInterval('P1D');
                $period = new DatePeriod($fair_start, $interval, $fair_end);
                foreach ($period as $day) {
                    $fair_days[] = $day->format('Y-m-d');
                }
            }

            $decoded_data = json_decode($conf->conf_data, true);
            if (!is_array($decoded_data) || !isset($decoded_data[$lang])) continue;

            // NIE przerywamy, gdy brak dni – tylko logujemy
            $keys = array_diff(array_keys($decoded_data[$lang]), ['main-desc']);
            if (empty($keys) && current_user_can('manage_options')) {
                echo "<script>console.log('Brak dni (tylko main-desc) | slug: " . addslashes($conf->conf_slug) . "');</script>";
            }

            // Zbieramy potencjalne dni (może wyjść pusto – to OK)
            $conference_dates = [];
            foreach (array_keys($decoded_data[$lang]) as $key) {
                if ($key === 'main-desc') continue;
                $parsed_date = self::parse_conference_key_to_date($key, $conf->conf_slug);
                if ($parsed_date) $conference_dates[] = $parsed_date;
            }

            // Filtrowanie po dniach targów (jeśli są), ale nie przerywamy, gdy wynik pusty
            if (!empty($fair_days)) {
                $conference_dates = array_values(array_filter(
                    $conference_dates,
                    fn($date) => in_array($date, $fair_days, true)
                ));
            }

            // Ustal zakres dat lub zostaw pustą
            $date_range = '';
            if (!empty($conference_dates)) {
                $start_date = min($conference_dates);
                $end_date   = max($conference_dates);

                $start_dt = new DateTime($start_date);
                $end_dt   = new DateTime($end_date);

                if ($start_dt->format('Y-m-d') === $end_dt->format('Y-m-d')) {
                    // jeden dzień → "dd | mm | rrrr"
                    $date_range = $start_dt->format('d') . ' | ' . $start_dt->format('m') . ' | ' . $start_dt->format('Y');
                } else {
                    // wiele dni → "dd–dd | mm | rrrr"
                    $date_range = $start_dt->format('d') . '–' . $end_dt->format('d') . ' | ' . $start_dt->format('m') . ' | ' . $start_dt->format('Y');
                }
            }

            // Dodajemy konferencję ZAWSZE — data może być pusta
            $processed_conferences[] = [
                'slug'  => $conf_slug,
                'title' => self::confLang($conf->conf_name_pl, $conf->conf_name_en),
                'img' => self::confLang($conf->conf_img_pl, $conf->conf_img_en),
                'logo'  => $logo,
                'alt' => $logo_alt,
                'date'  => $date_range,
                'url' => self::confLang(
                    '/wydarzenia/?konferencja=' . $conf_slug,
                    '/en/conferences/?konferencja=' . $conf_slug
                ),
            ];
        }

        // echo '<pre style="width:600px;">';
        // var_dump($processed_conferences);
        // echo '</pre>';

        $output = '';

        // Styl
        $output .= '
        <style>

            .row.limit-width.row-parent:has(#pwe-conf-short-info-gr3-schedule) {
                padding: 36px 0 !important;
                max-width: 100% !important;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__top-container {
                max-width: 1200px;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                margin: 18px auto;
                padding: 0 36px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__congress-logo {
                max-width: 280px !important;
                width: 100%;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__title {
                font-size: clamp(1rem, 3vw, 2.5rem);
                font-weight: 900;
                line-height: 1;
                width: 100%;
                margin-top: 0px;
                color: var(--accent-color);
                /* opacity: .5; */
                text-transform: uppercase;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__subtitle {
                font-size: 26px;
                font-weight: 800;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container {
                position: relative;
                display: flex;
                align-items: center;
                background-image: url(/doc/background.webp);
                background-position: center;
                background-size: cover;
                background-repeat: no-repeat;
                justify-content: center;
                min-height: 450px;
                gap: 36px;
                padding: 0 36px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container:before {
                content: "";
                position: absolute;
                width: 100%;
                height: 100%;
                background: white;
                opacity: 0.4;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container:after {
                content: "";
                position: absolute;
                width: 100%;
                height: 100%;
                background: var(--main2-color);
                clip-path: polygon(58% 0, 100% 0, 100% 100%, 58% 100%, 42% 50%);
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__left {
                flex: 1 1 30%;
                position: relative;
                max-width: 400px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__info-box {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 18px;
                width: 100%;
                min-height: 340px;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr3-schedule__info-box{
                opacity:0;
                pointer-events:none;
                transition: opacity .75s ease;
            }
            .' . $rnd_class . '  .pwe-conf-short-info-gr3-schedule__info-box.is-active{
                opacity:1;
                pointer-events:auto;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__info-box-logo {
                max-width: 160px !important;
                aspect-ratio: 3/2;
                object-fit: contain;
                background: white;
                border-radius: 18px;
                padding: 8px;
                box-shadow: 0 0 8px -4px black;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__info-box-date {
                background: white;
                max-width: 260px;
                width: 100%;
                text-align: center;
                padding: 8px 12px;
                border-radius: 9px;
                font-size: 16px;
                font-weight: 600;
                box-shadow: 0 0 8px -4px black;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__info-box-title {
                font-size: 18px;
                font-weight: 600;
                text-align: center;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__info-box-btn {
                border-radius: 36px;
                color: white !important;
                background: #0000004f;
                transform-origin: center !important;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__right {
                flex: 1 1 60%;
            }

            .' . $rnd_class . ' .swiper {
                max-width: 1000px;
                margin: 0 auto;
                padding: 40px 20px !important;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__link {
                display: block;
                width: 100%;
                height: 100%;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__tile {
                transition: transform .45s ease;
                border-radius:18px;
            }

            .' . $rnd_class . ' .swiper-slide:hover .pwe-conf-short-info-gr3-schedule__tile {
                transform: scale(1.10);
            }
            .' . $rnd_class . ' .swiper-slide {
                aspect-ratio: 1/1;
                border-radius: 18px;
            }
            .' . $rnd_class . ' .swiper-wrapper {
                    margin-bottom: 7px !important;
            }
            .' . $rnd_class . ' .swiper-button-next, .' . $rnd_class . ' .swiper-button-prev {
                display: block;
                font-weight: 700;
                margin-top: -8px;
            }
            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container_swiper {
                position: relative;
                max-width: 1300px;
            }

            .' . $rnd_class . ' .swiper-pagination {
                background: white;
                padding: 2px 4px !important;
                bottom: 0px !important;
                border-radius: 36px;
            }

            .' . $rnd_class . ' .swiper-pagination-bullet-active {
                background: var(--main2-color) !important;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__tile {
                height: auto;
                width: 100%;
                aspect-ratio: 1 / 1;
                object-fit: cover;
            }
            @media (max-width: 1050px) {
                .' . $rnd_class . '  .swiper {
                    max-width: 90vw;
                }
            }
            @media (max-width: 960px) {
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__left,
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__right {
                    flex: 1 1 50%;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container:after {
                    clip-path: polygon(70% 0, 100% 0, 100% 100%, 70% 100%, 60% 50%);
                }
            }

            @media (max-width: 768px) {
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__top-container {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 12px;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__title {
                    font-size: clamp(22px, 5vw, 2.5rem);
                }
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__subtitle {
                    font-size: clamp(18px, 4vw, 2rem);
                }
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container {
                    flex-direction: column;
                    background-image: url(/doc/header_mobile.webp);
                    padding: 36px 0;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__left,
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__right {
                    flex: 1 1 100%;
                    padding: 0 36px;
                    max-width: unset;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr3-schedule__info-box{
                    display: none;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr3-schedule__info-box.is-active{
                    display: flex;
                    position: static;
                    transform: unset;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container:after {
                    clip-path: polygon(50% 70%, 100% 80%, 100% 100%, 0 100%, 0 80%);
                }

                .' . $rnd_class . ' .swiper {
                    position: relative;
                    padding: 0 10px 28px !important;
                    margin: 0 !important;
                }

            }
            @media(max-width:620px){
                .' . $rnd_class . ' .swiper-button-next, .' . $rnd_class . ' .swiper-button-prev {
                    display: none !important;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-gr3-schedule__column-container {
                    align-items: normal;
                }
            }
        </style>';

        if (count($processed_conferences) < 5){
            $output .= '
            <style>
                @media (min-width: 961px) {
                    .' . $rnd_class . '  .swiper-wrapper {
                        display: grid !important;
                        grid-template-columns: repeat(4, 1fr);
                        gap: 10px;
                        transform: none !important;
                    }

                    .' . $rnd_class . '  .swiper-slide {
                        width: auto !important;
                        min-height: auto !important;
                        margin: 0 !important;
                    }
                        .' . $rnd_class . ' .swiper-button-next, .' . $rnd_class . ' .swiper-button-prev, .' . $rnd_class . ' .swiper-pagination {
                        display: none !important;
                        }
                    }
            </style>';
        }
        // Layout
        $output .= '
        <div id="pwe-conf-short-info-gr3-schedule">
            <div class="pwe-conf-short-info-gr3-schedule__wrapper">
                <div class="pwe-conf-short-info-gr3-schedule__top-container">
                    <div class="pwe-conf-short-info-gr3-schedule__title-container">
                        <div class="pwe-conf-short-info-gr3-schedule__title">' . PWEConferenceShortInfo::multi_translation("conferences_events") . '</div>
                        <div class="pwe-conf-short-info-gr3-schedule__subtitle">' . $name . '</div>
                    </div>
                    <img class="pwe-conf-short-info-gr3-schedule__congress-logo" src="/doc/kongres-color.webp" alt="Congress logo">
                </div>
                <div class="pwe-conf-short-info-gr3-schedule__column-container" style="position: relative;">

                    <div class="pwe-conf-short-info-gr3-schedule__column-container_swiper">
                        <div class="swiper-button-prev"></div>
                        <div class="pwe-conf-short-info-gr3-schedule__right swiper" style="position: relative;">


                            <div class="swiper-wrapper">';

                                foreach ($processed_conferences as $item) {
                                    if (empty($item["img"])) continue;
                                    $src = htmlspecialchars($item["img"], ENT_QUOTES, "UTF-8");
                                    $alt = htmlspecialchars(($item["title"] ?: $item["slug"]), ENT_QUOTES, "UTF-8");
                                    $id  = htmlspecialchars(($item["slug"] ?: pathinfo($src, PATHINFO_FILENAME)), ENT_QUOTES, "UTF-8");

                                    $output .= '
                                    <div class="swiper-slide ' . $item['slug'] . '-slide">
                                    <a href="' . $item['url'] . '" class="pwe-conf-short-info-gr3-schedule__link">
                                        <img id="' . $id . '" class="pwe-conf-short-info-gr3-schedule__tile" data-no-lazy="1" src="' . $src . '" alt="' .  PWEConferenceShortInfo::multi_translation("conference")  . $alt . '">
                                        </a>
                                    </div>';
                                }
                            $output .= '

                            </div>

                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="swiper-button-next"></div>
                    </div>

                </div>
            </div>
        </div>';


        include_once plugin_dir_path(__FILE__) . '/../../../scripts/swiper.php';
        $output .= PWESwiperScripts::swiperScripts(
            'conf-short-info-gr3-schedule',
            '#pwe-conf-short-info-gr3-schedule',
            'true',
            'true',
            '',
            [
                'autoplay' =>
                ['delay'=>6000,
                'disableOnInteraction'=>true,
                'pauseOnMouseEnter'=>false],
                'slideToClickedSlide' => false,
                'watchSlidesProgress' => true,
                'forceLoop' => true,
                'allowTouchMove' => false,],
            rawurlencode(json_encode([
                ['breakpoint_width' => 300, 'breakpoint_slides' => 1.5, 'centeredSlides' => true],
                ['breakpoint_width' => 600, 'breakpoint_slides' => 3],
                ['breakpoint_width' => 1024, 'breakpoint_slides' => 4],
            ]))
        );

        $output .= '
    ';

        return $output;
    }
}
