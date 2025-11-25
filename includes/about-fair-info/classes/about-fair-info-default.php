<?php

class PWEAboutFairInfoDefault {

    public static function initElements() {
        return [];
    }

    public static function output($atts, $rnd_class, $fair_group, $title, $desc, $img, $exhibitorsData = []) {
        $output = '';

        $bg_image = '/doc/new_template/logo-long.webp';

        $hasMany = !empty($exhibitorsData['has_many']);
        $logos   = is_array($exhibitorsData['logos'] ?? null) ? $exhibitorsData['logos'] : [];

        // Styl
        $output .= '<style>
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__container {
                display: flex;
                align-items: stretch;
                gap: 36px;
                border-radius: 18px;
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
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__left-column h3 {
                display: block;
                margin: 10px auto;
                font-size: 20px;
                text-transform: uppercase;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__title {
                font-size: 29px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__subtitle {
                font-size: 20px;
                font-weight: 800;
                margin: 0;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__btn {
                color: white !important;
                min-width: 200px;
                padding: 10px 20px;
                display: block;
                margin: 0 auto;
                border-radius: 10px;
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
                width: 100%;
                height: 100%;
                object-fit: cover;
                margin: auto;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__logos-container {
                border-radius: 30px;
                padding: 15px;
                display: flex;
                flex-direction: column;
                gap: 12px;
                -webkit-box-shadow: 4px 17px 30px -7px rgba(66, 68, 90, 1);
                -moz-box-shadow: 4px 17px 30px -7px rgba(66, 68, 90, 1);
                box-shadow: 4px 17px 30px -7px rgba(66, 68, 90, 1);
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__logos {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
            }
            .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__logo {
                aspect-ratio: 3/2;
                object-fit: contain;
                width: calc(30% - 6px);
                height: auto;
            }
            @media(max-width:760px) {
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__container {
                    flex-direction: column;
                }
            }
            @media(max-width:570px) {
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__left-column {
                    align-items: center;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__logos {
                    justify-content: center;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__title {
                    text-align: center;
                    font-size: 24px;
                    width: 100%;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__subtitle {
                    font-size: 18px;
                    width: 100%;
                    text-align: left;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__subtitle {
                    font-size: 18px;
                }
                .' . $rnd_class . ' .pwe-about-fair-' . $fair_group . '__logo {
                    width: calc(48% - 6px);
                }
            }
        </style>';

        // Layout
        $output .= '<div id="pwe-about-fair-' . $fair_group . '">';
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $bg_image)) {
                $output .= '
                    <div class="background-image">
                        <img src="'. $bg_image .'" alt="Logo [trade_fair_name]"/>
                    </div>';
            }
            $output .= '<div class="pwe-about-fair-' . $fair_group . '__container">
                <div class="pwe-about-fair-' . $fair_group . '__left-column">';
                    if ($hasMany && !empty($logos)) {
                        $output .= '<div class="pwe-about-fair-' . $fair_group . '__logos-container">
                            <h3>' . PWECommonFunctions::languageChecker('Wystawcy', 'Exhibitors') . '</h3>
                            <div class="pwe-about-fair-' . $fair_group . '__logos">';
                                foreach ($logos as $logo) {
                                    $alt = $logo['name'] ?: 'Exhibitor logo';
                                    $output .= '<img class="pwe-about-fair-' . $fair_group . '__logo" data-no-lazy="1" src="' . esc_url($logo['url']) . '" alt="' . esc_attr($alt) . '">';
                                }
                            $output .= '</div>
                        </div>';
                    } else {
                        $output .= $img;
                    }
                    $output .= '<a class="pwe-about-fair-' . $fair_group . '__btn pwe-btn accent" href="' . PWECommonFunctions::languageChecker('/galeria/', '/en/galerry/') . '" class="pwe-conf-short-info-' . $fair_group . '__btn">' . PWECommonFunctions::languageChecker('Galeria targów', 'Trade fair gallery') . '</a>';
                $output .= '</div>
                <div class="pwe-about-fair-' . $fair_group . '__right-column">
                    <div class="pwe-about-fair-' . $fair_group . '__right-column-content">
                        <h2 class="pwe-about-fair-' . $fair_group . '__title">' . $title . '</h2>
                        <p class="pwe-about-fair-' . $fair_group . '__desc">' . $desc . '</p>
                    </div>
                    <a class="pwe-about-fair-' . $fair_group . '__btn pwe-btn main2" href="' . PWECommonFunctions::languageChecker('/rejestracja/', '/en/registration/') . '" class="pwe-conf-short-info-' . $fair_group . '__btn">' . PWECommonFunctions::languageChecker('Dołacz do nas', 'Join us') . '</a>
                </div>
            </div>
        </div>';

        return $output;
    }
}
