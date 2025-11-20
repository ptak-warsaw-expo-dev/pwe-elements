<?php

class PWEConferenceShortInfoSolar {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {
        $output = '';

        // Styl
        $output .= '<style>

            .pwe-conf-short-info-home__wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 36px;
            }

            .pwe-conf-short-info-home__column-container {
                display: flex;
                align-items: center;
                gap: 36px;
            }

            .pwe-conf-short-info-home__left {
                flex: 1 1 33%;
            }

            .pwe-conf-short-info-home__right {
                flex: 1 1 66%;
            }

            .pwe-conf-short-info-home__title {
                color: #303133;
                font-weight: 500;
                margin: 0;
            }

            @media(max-width:960px) {

            }

            @media(max-width:760px) {

            }

        </style>';

        // Layout
        $output .= '<div class="pwe-conf-short-info-home__wrapper">
            <h2 class="pwe-conf-short-info-home__title">' . $title . '</h2>
            <div class="pwe-conf-short-info-home__column-container">
                <div class="pwe-conf-short-info-home__left">
                    <img src="/doc/kongres-color.webp" alt="Congress logo">
                </div>
                <div class="pwe-conf-short-info-home__right">
                    <p class="pwe-conf-short-info-home__desc">' . $desc . '</p>
                </div>
            </div>
            <div class="pwe-conf-short-info-home__org-container">
            
            </div>
            <a href="' . PWECommonFunctions::languageChecker('/wydarzenia/', '/en/conferences/') . '" class="pwe-conf-short-info-home__btn">' . PWECommonFunctions::languageChecker('Szczegóły', 'Details') . '</a>
        </div>';

        return $output;
    }
}
