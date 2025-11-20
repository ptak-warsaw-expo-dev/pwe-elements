<?php

/**
 * Class PWEProfileSiteTabs
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWEProfileSiteTabs extends PWEProfile {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {

        $element_output = array(
            array(
                'type' => 'textarea_raw_html',
                'heading' => __('Iframe', 'pwe_profile'),
                'param_name' => 'profile_sitetabs_iframe',
                'save_always' => true,
                'param_holder_class' => 'backend-textarea-raw-html',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSiteTabs',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom scope color', 'pwe_profile'),
                'param_name' => 'profilesitetabs_color_scope_custom',
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSiteTabs',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom exhibitor color', 'pwe_profile'),
                'param_name' => 'profilesitetabs_color_exhibitor_custom',
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSiteTabs',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom visitor color', 'pwe_profile'),
                'param_name' => 'profilesitetabs_color_visitor_custom',
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSiteTabs',
                ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Items', 'pwe_profile'),
                'param_name' => 'profile_sitetabs_items',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileSiteTabs',
                ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Title', 'pwe_profile'),
                        'param_name' => 'profile_title_select',
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => array(
                            'Custom' => '',
                            'PROFIL ODWIEDZAJĄCEGO' => 'profile_title_visitors',
                            'PROFIL WYSTAWCY' => 'profile_title_exhibitors',
                            'ZAKRES BRANŻOWY' => 'profile_title_scope',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Custom title', 'pwe_profile'),
                        'param_name' => 'profile_title_custom',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Icon', 'pwe_profile'),
                        'param_name' => 'profile_icon',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea_raw_html',
                        'heading' => __('Text', 'pwe_profile'),
                        'param_name' => 'profile_text',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'param_group',
                        'heading' => __('Items icons', 'pwe_profile'),
                        'param_name' => 'profile_sitetabs_items_icons',
                        'params' => array(
                            array(
                                'type' => 'attach_image',
                                'heading' => __('Icon', 'pwe_profile'),
                                'param_name' => 'profile_item_icon',
                                'save_always' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Title', 'pwe_profile'),
                                'param_name' => 'profile_item_title',
                                'save_always' => true,
                                'admin_label' => true,
                            ),
                        ),
                    ),
                ),
            ),
        );

        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {

        extract( shortcode_atts( array(
            'profile_sitetabs_iframe' => '',
            'profile_sitetabs_items' => '',
            'profilesitetabs_color_scope_custom' => '',
            'profilesitetabs_color_exhibitor_custom' => '',
            'profilesitetabs_color_visitor_custom' => '',
        ), $atts ));

        $profile_sitetabs_items_urldecode = urldecode($profile_sitetabs_items);
        $profile_sitetabs_items_json = json_decode($profile_sitetabs_items_urldecode, true);

        $lighter_accent_color = self::adjustBrightness(self::$accent_color, +20);
        $light_accent_color = self::adjustBrightness(self::$accent_color, +40);

        $profile_iframe_code = self::decode_clean_content($profile_sitetabs_iframe);

        if (!empty($profile_sitetabs_iframe)) {
            // Extract src from iframe
            preg_match('/src="([^"]+)"/', $profile_iframe_code, $match);
            $src = $match[1];

            // Extract the video ID from the URL
            preg_match('/embed\/([^?]+)/', $src, $match);
            $video_id = $match[1];
        } else {
            $video_id = 'R0Ckz1dVxoQ';
        }
        $profilesitetabs_color_scope_custom = !empty($profilesitetabs_color_scope_custom) ? $profilesitetabs_color_scope_custom : '#03045E';
        $profilesitetabs_color_exhibitor_custom = !empty($profilesitetabs_color_exhibitor_custom) ? $profilesitetabs_color_exhibitor_custom : '#0077B6';
        $profilesitetabs_color_visitor_custom = !empty($profilesitetabs_color_visitor_custom) ? $profilesitetabs_color_visitor_custom : '#00B4D8';

        $output = '
      <style>
        .container-'. self::$rnd_id .' {
          display: flex;
          min-height: 40vh;
        }

        .container-'. self::$rnd_id .' .profile-menu {
          flex: 0.21;
          min-width: 60px;
          display: flex;
          flex-direction: column;
          padding-top: 20px;
          overflow: hidden;
        }

        .container-'. self::$rnd_id .' .profile-menu-item {
          display: flex;
          align-items: center;
          cursor: pointer;
          color: #333;
          font-size: 16px;
          justify-content: end;
          overflow: hidden;
        }

        .container-'. self::$rnd_id .' .profile-menu-item .profile-menu-item-element {
          display: flex;
          align-items: center;
          padding: 14px;
          color: white;
          border-radius: 50px 0 0 50px;
          width: 60px;
          transition: width 0.5s ease, background-color 0.3s ease;
        }

        .container-'. self::$rnd_id .' .profile-menu-item.expanded .profile-menu-item-element {
          width: 100%;
        }

        .container-'. self::$rnd_id .' .tab-profile_visitors .profile-menu-item-element {
          background-color: '. $profilesitetabs_color_visitor_custom .';
        }

        .container-'. self::$rnd_id .' .tab-profile_exhibitors .profile-menu-item-element {
          background-color: '. $profilesitetabs_color_exhibitor_custom .';
        }

        .container-'. self::$rnd_id .' .tab-profile_scope .profile-menu-item-element {
          background-color: '. $profilesitetabs_color_scope_custom .';
        }

        .container-'. self::$rnd_id .' .icon {
          width: 45px;
          height: 45px;
          min-width: 45px;
          margin-right: 5px;
          background-size: contain !important;
        }

        .container-'. self::$rnd_id .' .profile-menu-text {
          display: inline;
          white-space: nowrap;
          opacity: 0;
          transition: opacity 0.3s ease;
          font-size: 14px;
        }

        .container-'. self::$rnd_id .' .profile-menu-item.expanded .profile-menu-text {
          opacity: 1;
        }

        .container-'. self::$rnd_id .' .profile-content {
          flex: 0.79;
          display: flex;
          justify-content: center;
          align-items: center;
          background-color: #f3f3f3;
        }

        #profile_scope,
        #profile_exhibitors,
        #profile_visitors {
          flex-wrap: wrap;
          flex-direction: row;
          gap: 10px;
          justify-content: space-evenly;
          align-items: flex-start;
        }

        .container-'. self::$rnd_id .' .profile-content-element {
          width: 220px;
          padding: 15px;
        }

        .container-'. self::$rnd_id .' .profile-content-element img {
          width: 80px;
        }

        .container-'. self::$rnd_id .' .profile-content-element p {
          margin-top: 0 !important;
          line-height: 1.2;
        }

        .container-'. self::$rnd_id .' .view {
          display: none;
          width: 100%;
          height: 100%;
          background-color: white;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
          border-radius: 8px;
          text-align: center;
          justify-content: center;
          align-items: center;
        }

        .container-'. self::$rnd_id .' .hidden {
          display: none;
        }

        .container-'. self::$rnd_id .' .visible {
          display: flex;
        }

        @media(max-width:1200px) {
          .container-'. self::$rnd_id .' .profile-menu-item-element {
            width: 50px;
          }

          .container-'. self::$rnd_id .' .profile-content-element {
            width: 220px;
          }

          .container-'. self::$rnd_id .' .profile-content {
            flex: 0.77;
          }

          .container-'. self::$rnd_id .' .profile-menu {
            flex: 0.23;
          }

          .container-'. self::$rnd_id .' .icon {
            width: 35px;
            height: 35px;
            min-width: 35px;
            margin-right: 5px;
          }
        }

        @media(max-width:960px) {
          .container-'. self::$rnd_id .' .profile-menu .profile-menu-text {
            font-size: 14px;
            opacity: 1;
          }

          .container-'. self::$rnd_id .' {
            flex-direction: column;
          }

          .container-'. self::$rnd_id .' .profile-menu-item .profile-menu-item-element {
            width: 220px;
          }

          .container-'. self::$rnd_id .' .profile-menu {
            margin-bottom: 5px;
          }
        }

        @media(max-width:620px) {
          .container-'. self::$rnd_id .' .profile-menu-item .profile-menu-item-element {
            padding: 7px;
          }

          #profile_scope,
          #profile_exhibitors,
          #profile_visitors {
            gap: 5px;
          }

          .container-'. self::$rnd_id .' .profile-content-element p {
            font-size: 13px;
          }

          .container-'. self::$rnd_id .' .profile-content-element {
            width: 48%;
            padding: 5px;
          }
        }
      </style>';

        $output .= '
        <div class="container-'. self::$rnd_id .'">
          <div class="profile-menu">';
            if (is_array($profile_sitetabs_items_json)) {
              foreach ($profile_sitetabs_items_json as $profile_item) {
                $profile_icon_nmb = $profile_item["profile_icon"];
                $profile_icon_src = wp_get_attachment_url($profile_icon_nmb);

                $profile_title_select = $profile_item["profile_title_select"];
                $profile_title_custom = $profile_item["profile_title_custom"];
                $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom;

                $profile_id = strtolower(str_replace('_title', '', $profile_title));

                if ($profile_title == 'profile_title_visitors') {
                    $profile_title = (get_locale() == 'pl_PL') ? "Profil odwiedzającego" : "Visitor profile";
                    $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/visitor_profile_icon_white.webp';
                } else if ($profile_title == 'profile_title_exhibitors') {
                    $profile_title = (get_locale() == 'pl_PL') ? "Profil wystawcy" : "Exhibitor profile";
                    $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/exhibitor_profile_icon_white.webp';
                } else if ($profile_title == 'profile_title_scope') {
                    $profile_title = (get_locale() == 'pl_PL') ? "Zakres branżowy" : "Industry scope";
                    $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/industry_scope_icon_white.webp';
                }

                $active = ($profile_title == 'profile_title_scope') ? 'active' : '';

                $output .= '
                <div class="profile-menu-item tab-'. $profile_id .' " onclick="toggleprofilemenuItem(this, \''. $profile_id .'\')">
                  <div class="profile-menu-item-element">
                    <div style="background:url('. $profile_icon_src .');" class="icon"></div>
                    <span class="profile-menu-text">'. $profile_title .'</span>
                  </div>
                </div>';
              }
            }

            $output .= '
          </div>

          <div class="profile-content">';

            foreach ($profile_sitetabs_items_json as $profile_item) {
                $profile_text = $profile_item["profile_text"];
                $profile_title_select = $profile_item["profile_title_select"];
                $profile_title_custom = $profile_item["profile_title_custom"];
                $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom;

                $profile_id = strtolower(str_replace('_title', '', $profile_title));

                $profile_items_icons = $profile_item["profile_sitetabs_items_icons"];
                $profile_items_icons_urldecode = urldecode($profile_items_icons);
                $profile_items_icons_json = json_decode($profile_items_icons_urldecode, true);

                $profile_text_content = self::decode_clean_content($profile_text);

                $output .= '
                <div id="'. $profile_id .'" class="view">';
                    if (empty($profile_text_content)) {
                        foreach ($profile_items_icons_json as $profile_icon) {
                            $profile_item_icon = $profile_icon["profile_item_icon"];
                            $profile_item_title = $profile_icon["profile_item_title"];
                            $profile_item_icon_src = wp_get_attachment_url($profile_item_icon);

                             $output .= '
                             <div class="profile-content-element">
                                 <img src="'. $profile_item_icon_src .'" />
                                 <p>'. $profile_item_title .'</p>
                             </div>';
                         }
                     } else {
                         $output .= $profile_text_content;
                     }
                     $output .= '

                </div>';
              }

            $output .= '
        </div>
      </div>';

        $output .= '
        <script>
          function toggleprofilemenuItem(element, viewId) {
            // Usuwamy klasę "expanded" ze wszystkich elementów profile-menu
            const menuItems = document.querySelectorAll(".profile-menu-item");
            menuItems.forEach(item => {
              item.classList.remove("expanded");
            });

            // Dodajemy klasę "expanded" do klikniętego elementu
            element.classList.add("expanded");

            // Ukryj wszystkie widoki
            const views = document.querySelectorAll(".view");
            views.forEach(view => {
              view.classList.add("hidden");
            });

            // Wyświetl tylko wybrany widok
            const selectedView = document.getElementById(viewId);
            if (selectedView) {
              selectedView.classList.remove("hidden");
              selectedView.classList.add("visible");
            }
          }

          // Włączenie pierwszego elementu na starcie
          document.addEventListener("DOMContentLoaded", function () {
            const firstmenuItem = document.querySelector(".profile-menu .tab-profile_scope");
            if (firstmenuItem) {
              firstmenuItem.classList.add("expanded");
              document.getElementById("profile_scope").classList.add("visible");
            }
          });
        </script>';


        return $output;
    }
}