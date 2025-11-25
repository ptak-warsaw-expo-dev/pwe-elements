<?php

class PWEConferenceShortInfoGr1 {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $all_conferences, $rnd_class, $name, $title, $desc) {
        $output = '';

        // Styl
        $output .= '<style>
            .' . $rnd_class . '  .pwe-conf-short-info-gr1__wrapper {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: stretch;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__left {
                flex: 1 1 50%;
                z-index: 2;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__left img {
                height: 100%;
                border-radius: 22px;
                object-fit: cover;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__right {
                flex: 1 1 50%;
                padding: 48px 36px 0;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 36px;
                z-index: 2;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__right-content {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__title {
                font-size: 44px;
                font-weight: 800;
                margin: 0;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__name {
                font-size: 20px;
                font-weight: 800;
                margin: 0;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__subtitle {
                font-weight: 600;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__btn-container {
                display: flex;
                flex-direction: row;
                justify-content: space-around;
                align-items: center;
                flex-wrap: wrap;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__btn {
                width: 48%;
                text-align: center;
                font-weight: 600;
                padding: 13px 29px;
                background: black;
                color: white !important;
                border-radius: 36px;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__btn_accent {
                background: var(--accent-color);
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__logo {
                display: flex;
                justify-content: center;
                align-items: center;
                background: #EFEFEF;
                padding: 18px;
                border-radius: 22px;
            }

            .' . $rnd_class . '  .pwe-conf-short-info-gr1__logo img {
                max-width: 240px;
                max-height: 80px;
                object-fit: contain;
                width: 100%;
            }

            @media(min-width: 1120px) {
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__btn {
                    min-width: 220px;
                    max-width: 220px;
                }
            }

            @media(max-width:960px) {
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__btn {
                    font-size: 14px;
                    padding: 12px;
                }
            }

            @media(max-width:760px) {

                .row.limit-width.row-parent:has(#PWEConferenceShortInfo) {
                    padding: 0;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__wrapper {
                    flex-direction: column-reverse;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__left {
                    padding: 18px;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__right {
                    flex: 1 1 100%;
                    width: 100%;
                    padding: 0;
                    text-align: center;
                    gap: 18px;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__right-content {
                    padding: 36px 36px 0;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__logo {
                    z-index: 1;
                    margin: 0 36px;
                    padding: 18px;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__logo img {
                    max-width: 180px;
                }
                .' . $rnd_class . '  .pwe-conf-short-info-gr1__btn {
                    margin: 6px;
                    min-width: 180px;
                }
            }

        </style>';

        // Layout
        $output .= '<div class="pwe-conf-short-info-gr1__wrapper">
            <div class="pwe-conf-short-info-gr1__left">
                <img src="/doc/new_template/conference_img.webp" alt="Publiczność konferencji">
            </div>
            <div class="pwe-conf-short-info-gr1__right">
                <div class="pwe-conf-short-info-gr1__right-content">
                    <h2 class="pwe-conf-short-info-gr1__title">' . PWEConferenceShortInfo::multi_translation("conference") . '</h2>
                    <h4 class="pwe-conf-short-info-gr1__name">' . $name . '</h4>
                    <div class="pwe-conf-short-info-gr1__desc">' . $desc . '</div>
                    <div class="pwe-conf-short-info-gr1__btn-container">
                        <a href="' . PWEConferenceShortInfo::multi_translation("conferences_link") . '" class="pwe-conf-short-info-gr1__btn">' . PWEConferenceShortInfo::multi_translation("details") . '</a>
                        <a href="' . PWEConferenceShortInfo::multi_translation("registration_link") . '" class="pwe-conf-short-info-gr1__btn pwe-conf-short-info-gr1__btn_accent">' . PWEConferenceShortInfo::multi_translation("registration") . '</a>
                    </div>
                </div>
                <div class="pwe-conf-short-info-gr1__logo">
                    <img src="/doc/kongres-color.webp" alt="Congress logo">
                </div>
            </div>
        </div>';

        return $output;
    }
}
