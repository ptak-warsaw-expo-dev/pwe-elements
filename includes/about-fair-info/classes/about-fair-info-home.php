<?php

class PWEAboutFairInfoHome {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $rnd_class, $fair_group, $title, $desc, $img) {
        $output = '';

        // Styl
        $output .= '<style>
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__container {
                display: flex;
                align-items: stretch;
                gap: 36px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__left-column {
                flex: 1 1 calc(33% - 18px);
                min-width: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 18px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__right-column {
                flex: 1 1 calc(66% - 18px);
                min-width: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 18px;
                padding: 36px;
                position: relative;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__right-column:before {
                content: "";
                position: absolute;
                right: 0;
                top: 0;
                width: 70%;
                height: 100%;
                border-radius: 18px;
                background: #eaeaea;
                z-index: 0;
            }
            .' . $rnd_class . ' .pwe-iframe {
                width: 100%;
                height: auto;
                aspect-ratio: 16 / 9;
                border-radius: 18px;
                z-index: 1;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__left-column {
                align-items: flex-start;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__title {
                font-size: 32px;
                font-weight: 800;
                margin: 0;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__subtitle {
                font-size: 16px;
                font-weight: 800;
                margin: 0;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__desc p {
                font-size: 16px;
                margin: 0;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__btn {
                background: var(--accent-color);
                border: 1px solid var(--accent-color) !important;
                text-decoration: none;
                text-transform: uppercase;
                color: white !important;
                font-size: 16px;
                text-align: center;
                font-weight: 600;
                padding: 13px 31px;
                border-radius: 36px;
                min-width: 240px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__img {
                border-radius: 18px;
                width: 100%;
                height: 100%;
                object-fit: cover;
                margin: auto;
            }
            @media(max-width:760px) {
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__container {
                    flex-direction: column;
                }
            }
            @media(max-width:570px) {
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__right-column {
                    padding: 0;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__right-column:before {
                    display: none;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__left-column {
                    align-items: center;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__title {
                    text-align: left;
                    font-size: 24px;
                    width: 100%;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__subtitle {
                    font-size: 16px;
                    width: 100%;
                    text-align: left;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__btn {
                    transform: scale(.8);
                }
            }
        </style>';

        // Layout
        $output .= '<div id="pwe-about-fair-' . $fair_group . '">
            <div class="pwe-about-fair-' . $fair_group . '__container">
                <div class="pwe-about-fair-' . $fair_group . '__left-column">
                    <h2 class="pwe-about-fair-' . $fair_group . '__title">' . PWECommonFunctions::languageChecker('O targach', 'About the fair') . '</h2>
                    <h4 class="pwe-about-fair-' . $fair_group . '__subtitle">' . $title . '</h4>
                    <div class="pwe-about-fair-' . $fair_group . '__desc">' . $desc . '</div>
                    <a class="pwe-about-fair-' . $fair_group . '__btn pwe-btn" href="' . PWECommonFunctions::languageChecker('/rejestracja/', '/en/registration/') . '" class="pwe-conf-short-info-default__btn">' . PWECommonFunctions::languageChecker('Zarejestruj się', 'Registration') . '</a>
                </div>
                <div class="pwe-about-fair-' . $fair_group . '__right-column">
                    <iframe class="pwe-iframe" src="https://www.youtube.com/embed/ox56e6nK7pY?si=Ql6envB1ngY3wuhg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>';

        return $output;
    }
}
