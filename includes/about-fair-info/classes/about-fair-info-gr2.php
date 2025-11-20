<?php

class PWEAboutFairInfoGr2 {

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
                border-radius: 18px;
                background: #f2f2f2;
                padding: 36px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__left-column, 
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__right-column {
                flex: 1 1 calc(50% - 18px);
                min-width: 0;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                gap: 18px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__title-general {
                margin: 0;
                font-size: 36px;
                font-weight: 900;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__title {
                margin: 20px 0 0;
                font-size: 20px;
                font-weight: 700;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__desc p {
                line-height: 1.3;
                font-weight: 500;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__btn-container {
                width: 100%;
                margin-top: 18px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__btn {
                color: white !important;
                min-width: 200px;
                width: fit-content;
                padding: 18px;
                display: block;
                border-radius: 50px;
                text-align: center;
                transition: all 0.3s ease-in-out;
                font-weight: 500;
                text-transform: uppercase;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__btn.accent {
                background: var(--accent-color);
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__btn.main2 {
                background: var(--main2-color);
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__img {
                border-radius: 18px;
                object-fit: cover;
                height: 100%;
                aspect-ratio: 16 / 9;
            }
            @media(max-width:760px) {
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__container {
                    flex-direction: column;
                }
            }
            @media(max-width:570px) {
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__title {
                    text-align: center;
                    font-size: 24px;
                    width: 100%;
                }
            }
        </style>';

        // Layout
        $output .= '
        <div id="pwe-about-fair-' . $fair_group . '">
            <div class="pwe-about-fair-' . $fair_group . '__container">

                <div class="pwe-about-fair-' . $fair_group . '__right-column">
                    <div class="pwe-about-fair-' . $fair_group . '__right-column-content">
                        <h2 class="pwe-about-fair-' . $fair_group . '__title-general">O targach</h2>
                        <h3 class="pwe-about-fair-' . $fair_group . '__title">' . $title . '</h3>
                        <div class="pwe-about-fair-' . $fair_group . '__desc">' . $desc . '</div>
                    </div>
                    <div class="pwe-about-fair-' . $fair_group . '__btn-container">
                        <a 
                            class="pwe-about-fair-' . $fair_group . '__btn pwe-btn main2" 
                            href="' . PWECommonFunctions::languageChecker('/rejestracja/', '/en/registration/') . '" >
                            ' . PWECommonFunctions::languageChecker('Zarejestruj się', 'Register') . '
                        </a>
                    </div>
                </div>

                <div class="pwe-about-fair-' . $fair_group . '__left-column">'. $img .'</div>

            </div>
        </div>';

        return $output;
    }
}
