<?php

class PWEConferenceShortInfoDefault extends PWEConferenceShortInfo {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {
        $output = '';

        // Styl
        $output .= '<style>
            .' . $rnd_class . ' .pwe-conf-short-info-default__wrapper {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                max-width: 1200px;
                margin: 0 auto !important;
                align-items: stretch;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__left {
                flex: 1;
                width: 50%;
                padding: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 18px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__left img {
                border-radius: 30px;
                height: 90%;
                object-fit: cover;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__right {
                flex: 1;
                width: 50%;
                max-width: 560px;
                padding: 24px 24px 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 18px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__right-content {
                height: 90%;
                width: 100%;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__title {
                font-size: clamp(1rem, 9vw, 8rem);
                text-align: center;
                font-weight: 900;
                line-height: 1;
                white-space: nowrap;
                width: 100%;
                overflow: hidden;
                margin-top: 0px;
                color: var(--accent-color);
                opacity: .5;
                text-align: center;
                text-transform: uppercase;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__subtitle {
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 15px;
                color: #000;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__desc {
                font-size: 16px;
                line-height: 1.75;
                font-weight: 400;
                color: #000;
                margin-bottom: 30px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__right-content h6 {
                text-align: center;
                display: block;
                margin: 12px auto 8px;
                font-size: 13px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__logo {
                margin-bottom: 20px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__logo img {
                max-width: 50%;
                margin: 0 auto;
                display: block;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__buttons {
                display: flex;
                gap: 20px;
                margin-top: 20px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__buttons .btn {
                padding: 12px 24px;
                background-color: #4B1E17;
                color: #fff;
                border: none;
                border-radius: 10px;
                text-decoration: none;
                font-weight: bold;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__btn {
                background: var(--accent-color);
                color: white !important;
                min-width: 200px;
                padding: 10px 20px;
                display: block;
                margin: 0 auto;
                border-radius: 10px;
                margin-top: 18px;
                text-align: center;
                transition: all 0.3s ease-in-out;
                font-weight: 500;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__buttons .btn.secondary {
                background-color: #2E2E2E;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__logotypes {
                display: flex;
                flex-wrap: nowrap;
                gap: 20px;
                overflow-x: auto;
                align-items: center;
                justify-content: center;
                margin-top: 20px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-default__logotypes img {
                height: 40px;
                object-fit: contain;
                flex-shrink: 0;
            }

            @media (max-width: 768px) {
                .' . $rnd_class . ' .pwe-conf-short-info-default__wrapper {
                    flex-direction: column;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__left, .pwe-conf-short-info-default__right {
                    flex: 1 1 100% !important;
                    width: 100% !important;
                    max-width: unset !important;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__right {
                    padding: 24px 0 0;
                }

                .' . $rnd_class . ' .pwe-conf-short-info-default__buttons {
                    flex-direction: column;
                }
            }
        </style>';

        // Tytuł
        $output .= '<div class="pwe-conf-short-info-default__title">' . PWEConferenceShortInfo::multi_translation("conference") . '
        </div>';

        // Layout
        $output .= '<div class="pwe-conf-short-info-default__wrapper">
            <div class="pwe-conf-short-info-default__left">
                <img src="/doc/new_template/conference_img.webp" alt="Publiczność konferencji">
                <a href="' . PWEConferenceShortInfo::multi_translation("registration_link") . '" class="pwe-conf-short-info-default__btn">' . PWEConferenceShortInfo::multi_translation("take_part") . '</a>
            </div>
            <div class="pwe-conf-short-info-default__right">
                <div class="pwe-conf-short-info-default__right-content">
                    <div class="pwe-conf-short-info-default__subtitle">' . $name . '</div>
                    <div class="pwe-conf-short-info-default__desc">' . $desc . '</div>
                    <div class="pwe-conf-short-info-default__logo">
                        <img src="/doc/kongres-color.webp" alt="Congress logo">
                    </div>';

                    // Logotypy z CAP
                    $logotypy = [];

                    $cap_logotypes_data = PWECommonFunctions::get_database_logotypes_data();

                    if (!empty($cap_logotypes_data)) {
                        if (do_shortcode('[trade_fair_group]') === 'gr2') {
                            $dozwolone_typy = [
                                'partner-merytoryczny'
                            ];
                        } else {
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
                        }

                        foreach ($cap_logotypes_data as $logo_data) {
                            if (in_array($logo_data->logos_type, $dozwolone_typy)) {
                                $logotypy[] = 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url;
                            }
                        }
                    }

                    if (!empty($logotypy)) {
                        $output .= '<h6>' . PWEConferenceShortInfo::multi_translation("patrons") . '</h6>';
                        $output .= '<div class="conf-short-info-default">';
                        $output .= '<div class="swiper">';
                        $output .= '<div class="swiper-wrapper">';

                        foreach ($logotypy as $logo) {
                            $output .= '<div class="swiper-slide">';
                            $output .= '<img id="' . pathinfo($logo)['filename'] . '" data-no-lazy="1" src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="' . pathinfo($logo)['filename'] . '"/>';
                            $output .= '</div>';
                        }

                        $output .= '</div>'; // .swiper-wrapper
                        $output .= '<div class="swiper-pagination"></div>';
                        $output .= '</div>'; // .swiper
                        $output .= '</div>'; // .conf-short-info-default

                        include_once plugin_dir_path(__FILE__) . '/../../../scripts/swiper.php';
                        $output .= PWESwiperScripts::swiperScripts('conf-short-info-default', '.conf-short-info-default', 'true', '', '', null,
                            rawurlencode(json_encode([
                                ['breakpoint_width' => 320, 'breakpoint_slides' => 2],
                                ['breakpoint_width' => 768, 'breakpoint_slides' => 3],
                                ['breakpoint_width' => 1024, 'breakpoint_slides' => 4],
                            ]))
                        );
                    }

                $output .= '
                </div>
                <a href="' . PWEConferenceShortInfo::multi_translation("conferences_link") . '" class="pwe-conf-short-info-default__btn secondary">' . PWEConferenceShortInfo::multi_translation("find_out_more") . '</a>
            </div>
        </div>';

        return $output;
    }

}
