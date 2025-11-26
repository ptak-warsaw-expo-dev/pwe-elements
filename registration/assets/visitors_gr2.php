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
                <p>'. PWERegistrationVisitors::multi_translation("step_1_of_2").'</p>
            </div>
            <div class="pwe-registration-title">
                <h4>'. PWERegistrationVisitors::multi_translation("your_ticket").'</h4>
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
                      <h3>'. PWERegistrationVisitors::multi_translation("step_1_of_2").'</h3>
                      <h2 class="form-title">'. PWERegistrationVisitors::multi_translation("ticket").'
                      </h2>
                      <div class="pwe-registration-form">
                        [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                      </div>
                    </div>
                    <div class="benefits">
                      <h2>'. PWERegistrationVisitors::multi_translation("vip_invitation").'</h2>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/fasttrack.webp" />
                        <p>'. PWERegistrationVisitors::multi_translation("Free entry").'</br>FAST TRACK</p>
                      </div>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/obsluga.webp" />
                        <p>'. PWERegistrationVisitors::multi_translation("concierge_service").'</p>
                      </div>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/vip.webp" />
                        <p>'. PWERegistrationVisitors::multi_translation("VIP_zone").'</p>
                      </div>
                      <div class="benefits_icon">
                        <img src="/wp-content/plugins/pwe-media/media/platyna/aktywacja.webp" />
                        <p>'. PWERegistrationVisitors::multi_translation("earlier_activation").'</p>
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
                                <h2 class="form-header-title">'. PWERegistrationVisitors::multi_translation("ticket").'</h2>
                                <a href="https://warsawexpo.eu/" target="_blank"><img class="form-header-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp"></a>
                            </div>
                            <img class="form-badge-left" src="/wp-content/plugins/pwe-media/media/badge_left.png">
                            <img class="form-badge-bottom" src="/wp-content/plugins/pwe-media/media/badge_bottom.png">
                            <img class="form-badge-right" src="/wp-content/plugins/pwe-media/media/badge_right.png">
                            <a href="https://warsawexpo.eu/" target="_blank"><img class="form-image-qr" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp"></a>
                            <div class="form">
                                <h2 class="form-title">'. PWERegistrationVisitors::multi_translation("ticket").'</h2>
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
                    '. PWERegistrationVisitors::multi_translation("organized_groups").'
                    </h4>
                </div>
                <div>
                    <p>
                    '. PWERegistrationVisitors::multi_translation("organized_groups_info").'
                    </p>
                </div>
                <div class="pwe-btn-container">
                  '. PWERegistrationVisitors::multi_translation("groups_link").'
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
