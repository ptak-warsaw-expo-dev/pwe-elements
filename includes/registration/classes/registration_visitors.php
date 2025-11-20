<?php

/**
 * Class PWERegistrationVisitors
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWERegistrationVisitors extends PWERegistration {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
        add_filter('gform_pre_render', array($this, 'hideFieldsBasedOnAdminLabel'));
    }

    /**
     * Pobiera ID pola na podstawie jego Admin Label.
     *
     * @param array $form Formularz Gravity Forms jako tablica
     * @param string $admin_label Etykieta administratora pola (Admin Label)
     * @return int|null ID pola lub null, jeśli nie znaleziono
     */
    public static function getFieldIdByAdminLabel($form, $admin_label) {
        foreach ($form['fields'] as $field) {
            if (isset($field->adminLabel) && $field->adminLabel === $admin_label) {
                error_log('Found field ID ' . $field->id . ' for admin label: ' . $admin_label);
                return $field->id;
            }
        }
        error_log('No field found for admin label: ' . $admin_label);
        return null;
    }

    /**
     * Ukrywa pola w formularzu na podstawie etykiety admina.
     *
     * @param array $form Formularz Gravity Forms
     * @return array Zaktualizowany formularz
     */
    public function hideFieldsBasedOnAdminLabel($form) {
        if (is_page('rejestracja')) {
            foreach ($form['fields'] as &$field) {
                if (in_array($field->adminLabel, ['name', 'street', 'house', 'post', 'city'])) {
                    $field->visibility = 'hidden';
                }
            }
        }
        return $form;
    }
    public static $inline_styles = '';
    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts, $registration_type, $registration_form_id, $register_show_ticket) {

        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);

        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $current_domain = $_SERVER['HTTP_HOST'];
        $current_fair_group = null;

        foreach ($pwe_groups_data as $item) {
            if ($item->fair_domain === $current_domain) {
                $current_fair_group = $item->fair_group;
                break;
            }
        }

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $register_ticket_link = !empty($atts['register_ticket_link']) ? $atts['register_ticket_link'] : '';
        $register_ticket_price = !empty($atts['register_ticket_price']) ? $atts['register_ticket_price'] : '249';
        $register_ticket_price_frist = !empty($atts['register_ticket_price_frist']) ? $atts['register_ticket_price_frist'] : '150';

        $register_ticket_register_benefits  = !empty(trim(strip_tags($atts['register_ticket_register_benefits'] ?? '')))
        ? $atts['register_ticket_register_benefits']
        : '
            <ul class="ticket-card__benefits">
              <li>'. self::languageChecker('<strong>wejścia na targi po rejestracji przez 3 dni</strong>', '<strong>access to the trade fair for all 3 days upon registration</strong>') .'</li>
              <li>'. self::languageChecker('<strong>możliwość udziału w konferencjach</strong> lub warsztatach na zasadzie “wolnego słuchacza”', '<strong>the chance to join conferences</strong> or workshops as a listener') .'</li>
              <li>'. self::languageChecker('darmowy parking', 'free parking') .'</li>
            </ul>
        ';

        $register_ticket_benefits = !empty(trim(strip_tags($atts['register_ticket_benefits'] ?? '')))
            ? $atts['register_ticket_benefits']
            : '
                <ul class="ticket-card__benefits">
                    <li>'. self::languageChecker('<strong>fast track</strong> - szybkie wejście na targi dedykowaną bramką przez 3 dni', '<strong>fast track access</strong> – skip the line and enter the trade fair through a dedicated priority gate for all 3 days') .'</li>
                    <li>'. self::languageChecker('<strong>imienny pakiet</strong> - targowy przesyłany kurierem przed wydarzeniem', '<strong>Personalized trade fair package</strong> - delivered by courier to your address before the event') .'</li>
                    <li>'. self::languageChecker('<strong>welcome pack</strong> - przygotowany specjalnie przez wystawców', '<strong>welcome pack</strong> - a special set of materials and gifts prepared by exhibitors') .'</li>
                    <li>'. self::languageChecker('obsługa concierge', 'Concierge service') .'</li>
                    <li>'. self::languageChecker('możliwość udziału w konferencjach i  warsztatach', 'Access to conferences and workshops') .'</li>
                    <li>'. self::languageChecker('darmowy parking', 'Free parking') .'</li>
                </ul>
            ';


        if (isset($_SERVER['argv'][0])) {
            $source_utm = $_SERVER['argv'][0];
        } else {
            $source_utm = '';
        }

        if (strpos($source_utm, 'utm_source=premium') !== false  ) {
            $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badge-mockup.webp') ? '/doc/badge-mockup.webp' : '');
        } else if(strpos($source_utm, 'utm_source=byli') !== false || strpos($source_utm, 'utm_source=platyna') !== false ) {
            if (get_locale() == 'pl_PL') {
                $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup.webp') ? '/doc/badgevipmockup.webp' : '');
            } else {
                $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup-en.webp') ? '/doc/badgevipmockup-en.webp' : '/doc/badgevipmockup.webp');
            }
        }

        $domain = $parsed = parse_url(site_url())['host'];
        $fair_data = PWECommonFunctions::get_database_fairs_data($domain);
        $domain_gr = strtolower($fair_data[0]->fair_group);

        // CSS <----------------------------------------------------------------------------------------------<
        require_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php';

        switch (strtolower($fair_data[0]->fair_group)) {
            // case 'gr1':
            //     require_once plugin_dir_path(__DIR__) . 'assets/visitors_gr1.php';
            //     return render_gr1($atts);
            case 'gr2':
                require_once plugin_dir_path(__DIR__) . 'assets/visitors_gr2.php';
                $output .= render_gr2($atts, $source_utm, $badgevipmockup, $register_show_ticket);
                return $output ;
            // case 'gr3':
            //     require_once plugin_dir_path(__DIR__) . 'assets/visitors_gr3.php';
            //     return render_gr3($atts);
        }


        if (strpos($source_utm, 'utm_source=byli') !== false || strpos($source_utm, 'utm_source=premium') !== false || strpos($source_utm, 'utm_source=platyna') !== false) {
            $output .= '
            <div id="pweRegistration" class="pwe-registration vip">
                <div class="pwe-reg-column pwe-mockup-column">
                    <img src="'. $badgevipmockup .'">
                </div>
                <div class="pwe-reg-column pwe-registration-column">
                    <div class="pwe-registration-step-text">
                        <p>'. self::languageChecker('Krok 1 z 2', 'Step 1 of 2') .'</p>
                    </div>
                    <div class="pwe-registration-title">
                        <h4>'. self::languageChecker('Twój bilet na targi', 'Your ticket to the trade fair') .'</h4>
                    </div>
                    <div class="pwe-registration-form">
                        [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                    </div>
                </div>
            </div>';
            } else if($register_show_ticket === "true" && $domain_gr == "gr3") {
                $output .= '
                    <div id="pweRegistrationTicket" class="registration-ticket">
                      <h1 class="registration-ticket__title">'. self::languageChecker('Opcje biletów dla odwiedzających:', 'Ticket options for visitors:') .'</h1>
                      <div class="registration-ticket-container">
                        <div class="registration-ticket__option registration-ticket__option--standard">
                          <div class="ticket-card__label">'. self::languageChecker('Najczęstszy wybór', 'Most common choice') .'</div>
                          <div class="ticket-card__name">'. self::languageChecker('Bilet Branżowy', 'Trade Pass') .'</div>

                          <div class="ticket-card">
                            <div class="ticket-card__price">
                              <h2 class="ticket-card__price-value">'. self::languageChecker('Bezpłatny po rejestracji</br>online', 'Free after online</br>registration') .'</h2>
                              <p class="ticket-card__note">'. self::languageChecker('lub ', 'or ') .' '.$register_ticket_price_frist .' '. self::languageChecker('PLN podczas dni targowych', 'PLN during the trade fair days') .'</p>
                            </div>

                            <div class="ticket-card__details">
                                <p class="ticket-card__details-title">'. self::languageChecker('Bilet upoważnia do:', 'With this ticket, you get:') .'</p>
                                '. self::languageChecker(do_shortcode('[trade_fair_registration_benefits_pl]'), do_shortcode('[trade_fair_registration_benefits_en]')) .'
                              <div class="pwe-registration-form">
                                [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="registration-ticket__option registration-ticket__option--business">
                          <img src="/wp-content/plugins/pwe-media/media/fast-track.webp">
                          <div class="ticket-card__name">'. self::languageChecker('Business Priority Pass', 'Business Priority Pass') .'</div>
                          <div class="ticket-card">
                            <div class="ticket-card__price">
                              <h2 class="ticket-card__price-value">'.$register_ticket_price.' PLN</h2>
                              <p class="ticket-card__note">'. self::languageChecker('lub poproś o zaproszenie wystawcę', 'or request an invitation from an exhibitor') .'</p>
                              <a class="exhibitor-catalog" href="'. self::languageChecker('/katalog-wystawcow', '/en/exhibitors-catalog/') .'">'. self::languageChecker('katalog wystawców', 'exhibitor catalog') .'</a>
                            </div>

                            <div class="ticket-card__details">
                              <h2 class="ticket-card__details-title">'. self::languageChecker('Bilet upoważnia do:', 'With this ticket, you get:') .'</h2>
                                '. self::languageChecker(do_shortcode('[trade_fair_ticket_benefits_pl]'), do_shortcode('[trade_fair_ticket_benefits_en]')) .'
                              <div class="ticket-card__details_button">';
                              if(empty($register_ticket_link)){
                                $output .= '
                                 <a href="#" class="ticket-card__cta" data-popup-trigger>
                                  '. self::languageChecker('Kup bilet', 'Buy a ticket') .'
                                </a>';
                              } else {
                                $output .= '
                                <a target="_blank" href="'.$register_ticket_link.'" class="ticket-card__cta">
                                    '. self::languageChecker('Kup bilet', 'Buy a ticket') .'
                                </a>';
                              }
                              $output .= '
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    ';
                    if(empty($register_ticket_link)){
                        $output .= '
                        <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const popupTrigger = document.querySelector("[data-popup-trigger]");
                            const popup = document.getElementById("popup");
                            const popupClose = document.getElementById("popupClose");

                            popupTrigger.addEventListener("click", function(e) {
                                e.preventDefault();
                                popup.style.display = "flex";
                            });

                            popupClose.addEventListener("click", function() {
                                popup.style.display = "none";
                            });

                            window.addEventListener("click", function(e) {
                                if (e.target === popup) {
                                popup.style.display = "none";
                                }
                            });

                            const popupRegisterBtn = document.querySelector(".popup_katalog:not(.popup_rej)");

                            if (popupRegisterBtn) {
                                popupRegisterBtn.addEventListener("click", function(e) {
                                    e.preventDefault();
                                    popup.style.display = "none";
                                });
                            }
                        });
                        </script>

                        ';
                        $output.='<div class="popup" id="popup">
                            <div class="popup__content">
                                <div class="popup__content_text_container">
                                    <div class="popup__content_text">
                                        <p style="font-size:16px;">'. self::languageChecker('Poproś wybranego wystawcę o zaproszenie – to szybki i pewny sposób aby otrzymać Zaproszenie Priority Pass', 'Ask your chosen exhibitor for an invitation - it s a quick and sure way to get a Priority Pass invitation') .'</p>
                                        <p class="text">'. self::languageChecker('Obecna pula biletów Business Priority Pass przeznaczona do sprzedaży została wyczerpana. Zachęcamy do bezpłatnej rejestracji i odbioru Biletu Branżowego', 'The current pool of Business Priority Pass tickets for sale has been exhausted. We encourage you to register and pick up your Business Pass for free') .'</p>

                                    </div>
                                    <div class="popup__content_button">
                                        <div id="popupClose">+</div>
                                    </div>
                                </div>
                                <div class="popup__content_button_container">
                                    <a href="'. self::languageChecker('/rejestracja', '/en/registration/') .'" class="popup_katalog ">'. self::languageChecker('Zarejestruj się', 'Register') .'</a>
                                    <a href="'. self::languageChecker('/katalog-wystawcow', '/en/exhibitors-catalog/') .'" class="popup_katalog popup_rej">'. self::languageChecker('Katalog wystawców', 'Exhibitor Catalog') .'</a>
                                </div>
                            </div>

                        </div>';
                    }
        } else {
            $output .= '
            <div id="pweRegistration" class="pwe-registration for-visitors">
                <div class="pwe-registration-column">
                    <div id="pweForm">
                        <img class="form-badge-top" src="/wp-content/plugins/pwe-media/media/badge_top.png">
                        <div class="form-container pwe-registration">
                            <div class="form-badge-header">
                                <h1 class="form-header-title">'. self::languageChecker('Twój bilet<br>na targi', 'Your ticket<br>to the fair') .'</h1>
                                <a href="https://warsawexpo.eu/" target="_blank"><img class="form-header-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp" alt="Logo Ptak Warsaw Expo"></a>
                            </div>
                            <img class="form-badge-left" src="/wp-content/plugins/pwe-media/media/badge_left.png">
                            <img class="form-badge-bottom" src="/wp-content/plugins/pwe-media/media/badge_bottom.png">
                            <img class="form-badge-right" src="/wp-content/plugins/pwe-media/media/badge_right.png">
                            <a href="https://warsawexpo.eu/" target="_blank"><img class="form-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp" alt="Logo Ptak Warsaw Expo"></a>
                            <div class="form">
                                <h2 id="main-content" class="form-title">'. self::languageChecker('Twój bilet<br>na targi', 'Your ticket<br>to the fair') .'</h2>
                                <div class="pwe-registration-form">
                                    [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }

        return $output;
    }
}