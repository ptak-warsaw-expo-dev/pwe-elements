<?php

class PWEConferenceShortInfoHvac {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {
        $output = '';

        // Styl
        $output .= '<style>
            .' . $rnd_class .' .pwe-conf-short-info-hvac__wrapper {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: stretch;
                gap: 36px;
            }
                
            .' . $rnd_class .' .pwe-conf-short-info-hvac__left {
                flex: 1 1 calc(50% - 18px);
                min-height: 400px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 18px;
                background: url(/doc/new_template/conference_img.webp);
                border-radius: 30px;
                background-repeat: no-repeat;
                background-size: cover;
                transition: transform 0.3s ease-in-out;
                transform: scale(1);
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__left:hover {
                transform: scale(1.05);
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__logo {
                object-fit: contain;
                padding: 36px;
                height: 80%;
                width: 80%;
                background: rgb(91 91 91 / 20%);
                box-shadow: 0 8px 32px 0 rgb(0 0 0 / 37%);
                backdrop-filter: blur(2.5px);
                -webkit-backdrop-filter: blur(2.5px);
                border-radius: 10px;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__right {
                flex: 1 1 calc(50% - 18px);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                gap: 18px;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__right-content {
                display: flex;
                flex-direction: column;
                gap: 18px;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__title {
                font-size: clamp(1rem, 30vw, 3.8rem);
                text-align: left;
                font-weight: 900;
                line-height: 1;
                white-space: nowrap;
                width: 100%;
                overflow: hidden;
                margin-top: 0px;
                color: var(--accent-color);
                opacity: .5;
                text-transform: uppercase;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__name {
                font-size: 29px;
                font-weight: 600;
                width: 100%;
                text-align: left;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__desc p {
                margin: 0;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__btn {
                display: block;
                text-align: center;
                font-weight: 600;
                margin-top: 18px;
                padding: 13px 29px;
                background: var(--main2-color);
                color: white !important;
                border-radius: 10px;
                text-transform: uppercase;
                transition: all 0.3s ease-in-out;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__btn:hover {
                background-color: color-mix(in srgb, var(--main2-color) 80%, white 20%) !important;
                color: white !important;
                border: unset !important;
            }

            .' . $rnd_class .' .pwe-conf-short-info-hvac__logo img {
                max-width: 240px;
                max-height: 80px;
                object-fit: contain;
                width: 100%;
            }

            @media(max-width:960px) {

            }

            @media(max-width:760px) {

                .' . $rnd_class .' .pwe-conf-short-info-hvac__wrapper {
                    flex-direction: column;
                    padding: 18px;
                    gap: 18px;
                }
                .' . $rnd_class .' .pwe-conf-short-info-hvac__right {
                    flex: 1 1 100%;
                    width: 100%;
                    padding: 0;
                    gap: 18px;
                }
            }

        </style>';

        // Layout
        $output .= '<div class="pwe-conf-short-info-hvac__wrapper">
            <div class="pwe-conf-short-info-hvac__left">
                <img class="pwe-conf-short-info-hvac__logo" src="/doc/kongres.webp" alt="Congress logo">
            </div>
            <div class="pwe-conf-short-info-hvac__right">
                <h2 class="pwe-conf-short-info-hvac__title">' . PWECommonFunctions::languageChecker('Konferencja', 'Conference') . '</h2>
                <div class="pwe-conf-short-info-hvac__right-content">
                    <div class="pwe-conf-short-info-hvac__name">' . $title . '</div>
                    <div class="pwe-conf-short-info-hvac__desc"><p>' . $desc . '</p></div>
                </div>
                <a href="' . PWECommonFunctions::languageChecker('/wydarzenia/', '/en/conferences/') . '" class="pwe-conf-short-info-hvac__btn">' . PWECommonFunctions::languageChecker('Sprawdź harmonogram', 'Check out the schedule') . '</a>
            </div>
        </div>';

        return $output;
    }
}
