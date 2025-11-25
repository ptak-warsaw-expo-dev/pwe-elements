<?php

class PWEConferenceShortInfoHome {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {
        $output = '';

        // Styl
        $output .= '<style>
            .pwe-conf-short-info-home__wrapper {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: stretch;
                gap: 36px;
                padding: 36px;
                background: #eaeaea;
                border-radius: 12px;
            }
                
            .pwe-conf-short-info-home__left {
                flex: 1 1 calc(50% - 18px);
                display: flex;
                flex-direction: column;
                gap: 18px;
            }

            .pwe-conf-short-info-home__title {
                margin: 0;
                font-weight: 800;
                font-size: 32px;
            }
                
            .pwe-conf-short-info-home__left img {
                width: 100%;
                height: calc(100% - 18px);
                max-width: 460px;
                object-fit: contain;
            }

            .pwe-conf-short-info-home__right {
                flex: 1 1 calc(50% - 18px);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 36px;
                z-index: 2;
            }

            .pwe-conf-short-info-home__right-content {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .pwe-conf-short-info-home__name {
                font-size: 20px;
                font-weight: 800;
            }

            .pwe-conf-short-info-home__subtitle {
                font-weight: 600;
            }

            .pwe-conf-short-info-home__btn-container {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
            }

            .pwe-conf-short-info-home__btn {
                width: 48%;
                text-align: center;
                font-weight: 600;
                padding: 13px 29px;
                background: black;
                color: white !important;
                border-radius: 36px;
                text-transform: uppercase;
                transition: 0.3s;
            }

            .pwe-conf-short-info-home__btn:hover {
                background-color: color-mix(in srgb, var(--accent-color) 70%, white 30%) !important;
                color: white !important;
                border: unset !important;
            }

            .pwe-conf-short-info-home__btn_accent {
                background: var(--accent-color);
            }

            .pwe-conf-short-info-home__logo {
                display: flex;
                justify-content: center;
                align-items: center;
                background: #EFEFEF;
                padding: 18px;
                border-radius: 22px;
            }

            .pwe-conf-short-info-home__logo img {
                max-width: 240px;
                max-height: 80px;
                object-fit: contain;
                width: 100%;
            }

            @media(min-width: 1120px) {
                .pwe-conf-short-info-home__btn {
                    min-width: 220px;
                    max-width: 220px;
                }
            }

            @media(max-width:960px) {
                .pwe-conf-short-info-home__wrapper {
                    flex-direction: column;
                }
                .pwe-conf-short-info-home__btn {
                    font-size: 14px;
                    padding: 12px;
                }
            }

            @media(max-width:760px) {

                .row.limit-width.row-parent:has(#PWEConferenceShortInfo) {
                    padding: 18px;
                }
                .pwe-conf-short-info-home__wrapper {
                    flex-direction: column;
                    padding: 18px;
                    gap: 18px;
                }
                .pwe-conf-short-info-home__right {
                    flex: 1 1 100%;
                    width: 100%;
                    padding: 0;
                    gap: 18px;
                }
                .pwe-conf-short-info-home__right-content {
                    padding: 18px;
                }
                .pwe-conf-short-info-home__logo {
                    z-index: 1;
                    margin: 0 36px;
                    padding: 18px;
                }
                .pwe-conf-short-info-home__logo img {
                    max-width: 180px;
                }
                .pwe-conf-short-info-home__btn-container {
                    justify-content: center;
                }
                .pwe-conf-short-info-home__btn {
                    margin: 6px;
                    width: 46%;
                    min-width: 200px;
                }
            }

        </style>';

        // Layout
        $output .= '<div class="pwe-conf-short-info-home__wrapper">
            <div class="pwe-conf-short-info-home__left">
                <h2 class="pwe-conf-short-info-home__title">' . PWECommonFunctions::languageChecker('Konferencja', 'Conference') . '</h2>
                <img src="/doc/kongres-color.webp" alt="Congress logo">
            </div>
            <div class="pwe-conf-short-info-home__right">
                <div class="pwe-conf-short-info-home__right-content">
                    <div class="pwe-conf-short-info-home__name">' . $name . '</div>
                    <div class="pwe-conf-short-info-home__desc"><p>' . $desc . '</p></div>
                    <div class="pwe-conf-short-info-home__btn-container">
                        <a href="' . PWECommonFunctions::languageChecker('/wydarzenia/', '/en/conferences/') . '" class="pwe-conf-short-info-home__btn">' . PWECommonFunctions::languageChecker('Szczegóły', 'Details') . '</a>
                        <a href="' . PWECommonFunctions::languageChecker('/rejestracja/', '/en/registration/') . '" class="pwe-conf-short-info-home__btn pwe-conf-short-info-home__btn_accent">' . PWECommonFunctions::languageChecker('Zarejestruj się', 'Registration') . '</a>
                    </div>
                </div>
            </div>
        </div>';

        return $output;
    }
}
