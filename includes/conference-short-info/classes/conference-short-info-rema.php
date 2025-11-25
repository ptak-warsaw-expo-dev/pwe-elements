<?php

class PWEConferenceShortInfoRema {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {
        $output = '';

        // Styl
        $output .= '<style>

            .row.limit-width.row-parent:has(.pwe-conf-short-info-rema__wrapper) {
                padding: 36px 0 !important;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-rema__wrapper {
                background: url(/doc/conference-section/conference-section-bg.webp);
                background-repeat: no-repeat;
                background-position: center center;
                background-size: cover;
                padding: 36px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-rema__wrapper::before {
                content: "";
                background-color: #000000;
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0px;
                opacity: 0.2;
            }
            
            .' . $rnd_class . ' .pwe-conf-short-info-rema__glass-bg {
                max-width: 1000px;
                background: rgb(91 91 91 / 20%);
                box-shadow: 0 8px 32px 0 rgb(0 0 0 / 37%);
                backdrop-filter: blur(2.5px);
                -webkit-backdrop-filter: blur(2.5px);
                border-radius: 10px;
                padding: 36px;
                display: flex;
                flex-direction: column;
                align-content: center;
                justify-content: center;
                align-items: center;
                gap: 16px;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-rema__logo {
                max-width: 370px;
                width: 100%;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-rema__title {
                font-weight: 800;
                max-width: 700px;
                color: #ffffff !important;
                text-align: center;
                margin: 0;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-rema__desc p {
                margin: 0;
                color: white;
                text-align: center;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-rema__btn {
                display: inline-block;
                color: white !important;
                background: var(--main2-color);
                border: 2px solid white;
                padding: 12px 24px;
                transition: 0.3s !important;
                text-transform: uppercase;
            }

            .' . $rnd_class . ' .pwe-conf-short-info-rema__btn:hover {
                color: white !important;
                background: transparent !important;
            }

            @media(max-width:760px) {
                .' . $rnd_class . ' .pwe-conf-short-info-rema__title {
                    font-size: 24px !important;
                }
            }

        </style>';

        // Layout
        $output .= '<div class="pwe-conf-short-info-rema__wrapper">
            <div class="pwe-conf-short-info-rema__glass-bg">
                <img class="pwe-conf-short-info-rema__logo" src="/doc/conference-section/conference-section-logo.webp" alt="Congress logo">
                <h2 class="pwe-conf-short-info-rema__title">' . $title . '</h2>
                <div class="pwe-conf-short-info-rema__desc"><p>' . $desc . '</p></div>
                <a href="' . PWECommonFunctions::languageChecker('/wydarzenia/', '/en/conferences/') . '" class="pwe-conf-short-info-rema__btn">' . PWECommonFunctions::languageChecker('Sprawdź program', 'Check the program') . '</a>
            </div>
        </div>';

        return $output;
    }
}
