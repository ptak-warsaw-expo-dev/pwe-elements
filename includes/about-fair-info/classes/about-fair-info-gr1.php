<?php

class PWEAboutFairInfoGr1 {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $rnd_class, $fair_group, $title, $desc, $img, $exhibitorsData = []) {
        $output = '';

        $hasMany = !empty($exhibitorsData['has_many']);
        $logos   = is_array($exhibitorsData['logos'] ?? null) ? $exhibitorsData['logos'] : [];

        // Styl
        $output .= '<style>
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__container {
                display: flex;
                align-items: stretch;
                gap: 36px;
                padding: 36px;
                background: #EFEFEF;
                border-radius: 18px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__left-column, 
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__right-column {
                flex: 1 1 calc(50% - 18px);
                min-width: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 18px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__left-column {
                align-items: flex-start;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__title {
                font-size: 44px;
                font-weight: 800;
                margin: 0;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__subtitle {
                font-size: 20px;
                font-weight: 800;
                margin: 0;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__desc p {
                font-size: 16px;
                margin: 0;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__btn {
                background: var(--accent-color);
                text-decoration: none;
                color: white !important;
                font-size: 16px;
                text-align: center;
                font-weight: 600;
                padding: 13px 29px;
                border-radius: 36px;
                min-width: 220px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__img {
                border-radius: 18px;
                width: 100%;
                height: 100%;
                object-fit: cover;
                margin: auto;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__logos {
                display: flex;
                height: 100%;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                gap: 18px;
                background: white;
                padding: 18px;
                border-radius: 12px;
                align-content: space-around;
            }
            .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__logo {
                aspect-ratio: 3/2;
                object-fit: contain;
                width: calc(30% - 6px);
                height: auto;
            }
            @media(max-width:760px) {
                .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__container {
                    flex-direction: column;
                }
            }
            @media(max-width:570px) {
                .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__container {
                    padding: 36px 18px 18px;
                }
                .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__left-column {
                    align-items: center;
                }
                .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__title {
                    text-align: center;
                    font-size: 24px;
                    width: 100%;
                }
                .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__subtitle {
                    font-size: 18px;
                    width: 100%;
                    text-align: left;
                }
                .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__subtitle {
                    font-size: 18px;
                }
                .' . $rnd_class . ' .pwe-about-fair-' .  $fair_group . '__logo {
                    width: calc(48% - 6px);
                }
            }
        </style>';

        // Layout
        $output .= '<div id="pwe-about-fair-' .  $fair_group . '">
            <div class="pwe-about-fair-' .  $fair_group . '__container">
                <div class="pwe-about-fair-' .  $fair_group . '__left-column">
                    <h2 class="pwe-about-fair-' .  $fair_group . '__title">' . PWECommonFunctions::languageChecker('O targach', 'About the fair') . '</h2>
                    <h4 class="pwe-about-fair-' .  $fair_group . '__subtitle">' . $title . '</h4>
                    <div class="pwe-about-fair-' .  $fair_group . '__desc">' . $desc . '</div>
                    <a class="pwe-about-fair-' .  $fair_group . '__btn pwe-btn" href="' . PWECommonFunctions::languageChecker('/rejestracja/', '/en/registration/') . '" class="pwe-conf-short-info-default__btn">' . PWECommonFunctions::languageChecker('Zarejestruj się', 'Registration') . '</a>
                </div>
                <div class="pwe-about-fair-' .  $fair_group . '__right-column">';
                    if ($hasMany && !empty($logos)) {
                        $output .= '<div class="pwe-about-fair-' .  $fair_group . '__logos">';
                        foreach ($logos as $logo) {
                            $alt = $logo['name'] ?: 'Exhibitor logo';
                            $output .= '<img class="pwe-about-fair-' .  $fair_group . '__logo" data-no-lazy="1" src="' . esc_url($logo['url']) . '" alt="' . esc_attr($alt) . '">';
                        }
                        $output .= '</div>';
                    } else {
                        $output .= $img;
                    }
                 $output .= '</div>
            </div>
        </div>';

        return $output;
    }
}
