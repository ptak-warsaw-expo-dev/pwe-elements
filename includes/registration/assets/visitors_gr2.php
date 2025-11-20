<?php

function render_gr2($atts, $source_utm){
  extract( shortcode_atts( array(
    'registration_form_id' => '',
    'register_show_ticket' => '',
    'register_ticket_price_frist' => '',
    'register_ticket_register_benefits' => '',
    'register_ticket_link' => '',
    'badgevipmockup' => '',
  ), $atts ));



  if (strpos($source_utm, 'utm_source=premium') !== false || strpos($source_utm, 'utm_source=platyna') !== false ) {
      $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup.webp') ? '/doc/badgevipmockup.webp' : '');
  } else if(strpos($source_utm, 'utm_source=byli') !== false ) {
      if (get_locale() == 'pl_PL') {
          $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup.webp') ? '/doc/badgevipmockup.webp' : '');
      } else {
          $badgevipmockup = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/badgevipmockup-en.webp') ? '/doc/badgevipmockup-en.webp' : '/doc/badgevipmockup.webp');
      }
  }

  if (strpos($source_utm, 'utm_source=byli') !== false || strpos($source_utm, 'utm_source=premium') !== false ) {

    $output .= '
    <div id="pweRegistration" class="pwe-registration vip">
        <div class="pwe-reg-column pwe-mockup-column">
            <img src="'. $badgevipmockup .'">
        </div>
        <div class="pwe-reg-column pwe-registration-column">
            <div class="pwe-registration-step-text">
                <p>'. PWECommonFunctions::languageChecker('Krok 1 z 2', 'Step 1 of 2') .'</p>
            </div>
            <div class="pwe-registration-title">
                <h4>'. PWECommonFunctions::languageChecker('Twój bilet na targi', 'Your ticket to the trade fair') .'</h4>
            </div>
            <div class="pwe-registration-form">
                [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
            </div>
        </div>
    </div>';
    } else if(strpos($source_utm, 'utm_source=platyna') !== false){
      $output .= '
            <div id="pweRegistration" class="pwe-registration platyna">
              <div class="pwe-registration-column">

                <div id="pweForm">
                  <div class="pweform_container">
                    <div class="form">
                      <h3>'. PWECommonFunctions::languageChecker('Krok 1 z 2', 'Step 1 of 2') .'</h3>
                      <h2 class="form-title">'. PWECommonFunctions::languageChecker('Twój bilet<br>na targi', 'Your ticket<br>to the fair') .'</h2>
                      <div class="pwe-registration-form">
                        [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                      </div>
                    </div>
                    <div class="benefits">
                      <h2>'. PWECommonFunctions::languageChecker('Zaproszenie Vip obejmuje', 'The Vip invitation includes') .'</h2>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/fasttrack.webp" />
                        <p>'. PWECommonFunctions::languageChecker('Wejście bezpłatne', 'Free entry') .'</br>FAST TRACK</p>
                      </div>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/obsluga.webp" />
                        <p>'. PWECommonFunctions::languageChecker('Obsługę concierge"a', 'Concierge service') .'</p>
                      </div>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/vip.webp" />
                        <p>'. PWECommonFunctions::languageChecker('Strefę VIP ROOM', 'VIP ROOM Zone') .'</p>
                      </div>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/aktywacja.webp" />
                        <p>'. PWECommonFunctions::languageChecker('Możliwość wcześniejszej</br>aktywacji zaproszenia', 'Possibility of earlier</br> activation of the invitation') .'</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>';
   } else {
            $output .= '
            <div id="pweRegistration" class="pwe-registration">
                <div class="pwe-registration-column">
                    <div id="pweForm">
                        <img class="form-badge-top" src="/wp-content/plugins/pwe-media/media/badge_top.png">
                        <div class="form-container pwe-registration">
                            <div class="form-badge-header">
                                <h2 class="form-header-title">'. PWECommonFunctions::languageChecker('Twój bilet<br>na targi', 'Your ticket<br>to the fair') .'</h2>
                                <a href="https://warsawexpo.eu/" target="_blank"><img class="form-header-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp"></a>
                            </div>
                            <img class="form-badge-left" src="/wp-content/plugins/pwe-media/media/badge_left.png">
                            <img class="form-badge-bottom" src="/wp-content/plugins/pwe-media/media/badge_bottom.png">
                            <img class="form-badge-right" src="/wp-content/plugins/pwe-media/media/badge_right.png">
                            <a href="https://warsawexpo.eu/" target="_blank"><img class="form-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp"></a>
                            <div class="form">
                                <h2 class="form-title">'. PWECommonFunctions::languageChecker('Twój bilet<br>na targi', 'Your ticket<br>to the fair') .'</h2>
                                <div class="pwe-registration-form">
                                    [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pwe-registration-grupy style-accent-bg">
                <div>
                    <h4>
                    '. PWECommonFunctions::languageChecker('Kontakt dla grup zorganizowanych', 'Contact for organized groups') .'
                    </h4>
                </div>
                <div>
                    <p>
                    '. PWECommonFunctions::languageChecker('W celu zapewnienia Państwu komfortowego udziału w naszych wydarzeniach, wstęp dla grup zorganizowanych możliwy jest tylko ostatniego dnia targów po wcześniejszej zgodzie Organizatora. W tym celu zachęcamy do kontaktu przez formularz dostępny na stronie: <a id="pweGroupsLink" href="https://warsawexpo.eu/grupy" alt="link do rejestracji grup zorganizowanych" target="_blank">warsawexpo.eu/grupy</a>. Pozostawienie plecaków oraz walizek w szatni jest obligatoryjne. Na targach obowiązuje business dress code.', 'To ensure a comfortable participation in our events, admission for organized groups is only possible on the last day of the fair, subject to prior approval by the Organizer. For this purpose, we encourage you to use the contact form available at: <a id="pweGroupsLink" href="https://warsawexpo.eu/en/groups" alt="link to group registration" target="_blank">warsawexpo.eu/en/groups</a>. Leaving backpacks and suitcases in the cloakroom is mandatory. A business dress code is required at the fair.') .'
                    </p>
                </div>
                <div class="pwe-btn-container">
                  '. PWECommonFunctions::languageChecker('<a class="pwe-btn" href="https://warsawexpo.eu/grupy/" target="_blank">Formularz kontaktowy</a>', '<a class="pwe-btn" href="https://warsawexpo.eu/en/groups/" target="_blank">Contact form</a>') .'
                </div>
            </div>
            <style>
            body .row-container:has(.pwe-registration) :is(.wpb_column, .uncol, .uncoltable, .uncont, .exhibitors-catalog, .custom-catalog) {
              height: auto !important;
            }
            .pwe-registration-grupy {
              margin: 20px 12px;
              border-radius:30px;
              padding:20px;
            }
            .pwe-registration-grupy h4,.pwe-registration-grupy p {
              color:white;
            }
            .pwe-registration-grupy p #pweGroupsLink {
              color:white !important;
              text-decoration:underline;
            }
            .pwe-registration-grupy .pwe-btn-container a {
              background-color: white !important;
              text-align: center;
              align-items: center;
              padding: 10px 5px;
              font-weight: 600;
            }
            </style>';
        }

  return $output;
}
