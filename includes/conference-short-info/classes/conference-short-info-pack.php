<?php

class PWEConferenceShortInfoPack extends PWEConferenceShortInfo {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {

        $logotypy = [];

        $cap_logotypes_data = PWECommonFunctions::get_database_logotypes_data();

        if (!empty($cap_logotypes_data)) {
            $dozwolone_typy = [
                'partner-targow',
                'patron-medialny',
                'partner-strategiczny',
                'partner-honorowy',
                'principal-partner',
                'industry-media-partner',
                'partner-branzowy',
                'partner-merytoryczny'
            ];

            foreach ($cap_logotypes_data as $logo_data) {
                if (in_array($logo_data->logos_type, $dozwolone_typy)) {
                    $logotypy[] = 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url;
                }
            }
        }

        $output = '';

        // Styl
        $output .= '<style>

            .row.limit-width.row-parent:has(.' . $rnd_class . ') {
                padding: 0;
                max-width: 100%;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__wrapper {
                background: var(--main2-color);
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__column-header {
                position: relative;
                background: url(/doc/conference-section/conference-section-bg.webp);
                background-position: center;
                background-size: cover;
                background-repeat: no-repeat;
                height: 300px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__column {
                position: relative;
                max-width: 1200px;
                margin: 0 auto;
                padding: 36px;
                display: flex;
                flex-direction: column;
                height: 100%;
                justify-content: center;
                z-index: 1;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__column-2 {
                flex: 1 1 50%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__row {
                display: flex;
                flex-direction: row;
                gap: 36px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__container {
                width: max-content;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__title {
                font-weight: 700;
                margin: 0;
                font-size: 36px;
                text-transform: uppercase;
                color: white !important;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__btn {
                background-color: var(--accent-color);
                border: var(--accent-color);
                font-weight: 600 !important;
                letter-spacing: 0.1em !important;
                text-transform: uppercase !important;
                color: white !important;
                min-width: 240px;
                border-radius: 36px !important;
                padding: 13px 31px !important;
                margin: 12px 0 0;
                font-size: 12px !important;
                text-align: center;
                display: inline-block;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__desc {
                color: white;
                margin: 0;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__logo {
                max-width: 300px !important;
                margin: 0 auto;
                object-fit: contain;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-pack__column h4 {
                text-align: left;
                margin: 0;
                color: white !important;
                font-size: 20px !important;
            }

            .' . $rnd_class . ' .swiper {
                padding: 0 !important;
            }

            .' . $rnd_class . ' .swiper-slide {
                padding: 5px;
                background: white;
            }

            .' . $rnd_class . ' .swiper-button-next, 
            .' . $rnd_class . ' .swiper-button-prev, 
            .' . $rnd_class . ' .swiper-rtl .swiper-button-prev {
                right: 2px !important;
                top: calc(50% - 24px);
                font-size: 60px;
                font-weight: 700;
                color: white !important;
            }

            .' . $rnd_class . ' .swiper-button-prev  {
                left: 2px !important;
                right: auto !important;
            }

            .' . $rnd_class . ' .swiper-button-next:after,
            .' . $rnd_class . ' .swiper-button-prev:after, 
            .' . $rnd_class . ' .swiper-rtl .swiper-button-prev:after {
                display: none;
            }

            .' . $rnd_class . ' .conf-short-info-pack {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            @media (max-width: 768px) {
                .' . $rnd_class . ' .pwe-conf-short-info-pack__column:not(:has(.swiper)) {
                    align-items: center;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-pack__row {
                    flex-direction: column;
                }
                .' . $rnd_class . ' .pwe-conf-short-info-pack__column-header:before {
                    content: "";
                    width: 100%;
                    height: 100%;
                    position: absolute;
                    left: 0;
                    top: 0;
                    background: linear-gradient(90deg, rgba(0, 0, 0, 1) 18%, rgba(0, 0, 0, 0) 100%);
                    z-index: 0;
                }
            }
        </style>';

        // Layout
        $output .= '
        <div class="pwe-conf-short-info-pack__wrapper">
            <div class="pwe-conf-short-info-pack__column-header">
                <div class="pwe-conf-short-info-pack__column">
                    <div class="pwe-conf-short-info-pack__container">
                        <h2 class="pwe-conf-short-info-pack__title">' . PWECommonFunctions::languageChecker('Konferencja', 'Conference') . '</h2>
                        <a href="' . PWECommonFunctions::languageChecker('/wydarzenia/', '/en/conferences/') . '" class="pwe-conf-short-info-pack__btn">' . PWECommonFunctions::languageChecker('Dowiedz się więcej', 'Find out more') . '</a>
                    </div>
                </div>
            </div>
            <div class="pwe-conf-short-info-pack__column">
                <div class="pwe-conf-short-info-pack__row">
                    <div class="pwe-conf-short-info-pack__column-2">
                        <p class="pwe-conf-short-info-pack__desc">' . $desc . '</p>
                    </div>
                    <div class="pwe-conf-short-info-pack__column-2">
                        <img src="/doc/kongres.webp" class="pwe-conf-short-info-pack__logo" alt="Congress logo">
                    </div>
                </div>
            </div>
            <div class="pwe-conf-short-info-pack__column">';
                if (!empty($logotypy)) {
                    $output .= '<h4>' . PWECommonFunctions::languageChecker('PATRONI I PARTNERZY', 'PATRONS AND PARTNERS') . '</h4>';
                    $output .= '<div class="conf-short-info-pack">';
                        $output .= '<div class="swiper">';
                            $output .= '<div class="swiper-wrapper">';

                            foreach ($logotypy as $logo) {
                                $output .= '<div class="swiper-slide">';
                                $output .= '<img id="' . pathinfo($logo)['filename'] . '" data-no-lazy="1" src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="' . pathinfo($logo)['filename'] . '"/>';
                                $output .= '</div>';
                            }

                            $output .= '</div>'; // .swiper-wrapper
                            $output .= '<div class="swiper-pagination"></div>';
                        $output .= '</div>
                        <div class="swiper-button-prev">‹</div>
                        <div class="swiper-button-next">›</div>
                        <a href="' . PWECommonFunctions::languageChecker('/rejestracja/', '/en/registration/') . '" class="pwe-conf-short-info-pack__btn">' . PWECommonFunctions::languageChecker('Zarejestruj się', 'Register') . '</a>
                    </div>';
                }
            $output .= '
            </div>
        </div>';

        include_once plugin_dir_path(__FILE__) . '/../../../scripts/swiper.php';
        $output .= PWESwiperScripts::swiperScripts('conf-short-info-pack', '.conf-short-info-pack', 'true', 'true', '', ['spaceBetween' => 10],
            rawurlencode(json_encode([
                ['breakpoint_width' => 320, 'breakpoint_slides' => 2],
                ['breakpoint_width' => 420, 'breakpoint_slides' => 3],
                ['breakpoint_width' => 768, 'breakpoint_slides' => 5],
                ['breakpoint_width' => 1024, 'breakpoint_slides' => 7],
            ]))
        );

        return $output;
    }

}
