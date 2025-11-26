<?php 

/**
 * Class PWEProfileexpanding
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWEProfileExpanding extends PWEProfile {

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
                'type' => 'textfield',
                'heading' => esc_html__('Title'),
                'param_name' => 'profile_expanding_title',
                'description' => __('Set title to diplay over the profiles'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileExpanding', 
                ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Items', 'pwe_profile'),
                'param_name' => 'profile_items_expanding',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileExpanding', 
                ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Title', 'pwe_profile'),
                        'param_name' => 'profile_title_select_expanding',
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => array(
                            'Custom' => 'custom',
                            'PROFIL ODWIEDZAJĄCEGO' => 'profile_title_visitors',
                            'PROFIL WYSTAWCY' => 'profile_title_exhibitors',
                            'ZAKRES BRANŻOWY' => 'profile_title_scope',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Custom title', 'pwe_profile'),
                        'param_name' => 'profile_title_custom_expanding',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Image', 'pwe_profile'),
                        'param_name' => 'profile_image_expanding',
                        'save_always' => true,
                    ),
                    array(
                      'type' => 'textarea_raw_html',
                      'group' => 'PWE Element',
                      'heading' => __('Text', 'pwelement'),
                      'param_name' => 'profile_expanding',
                      'save_always' => true,
                      'dependency' => array(
                          'element' => 'pwe_element',
                          'value' => 'PWElementProfile',
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
            'profile_expanding_title' => '',
            'profile_items_expanding' => '',
        ), $atts ));
      

        $profile_items_expanding_json = PWECommonFunctions::json_decode($profile_items_expanding);

        $output = '
        <style>
.pwe-profiles__main-container-expanding {
  display: flex;
  justify-content: space-between;
}

.pwe-profiles__expanding-item {
  width: 34%;
  min-height: 400px;
  max-height: 400px;
  cursor: pointer;
  overflow: hidden;
  position: relative;
  display: flex;
  flex-direction: row;
  transition: all 0.8s ease;
}
.pwe-profiles__expanding-item  .pwe-profile-image {
  height: 100%;
}

.pwe-profiles__expanding-item  .pwe-profile-image {
    height: 100%;
    width: 100%;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    position: relative;
}

.pwe-profiles__expanding-item .pwe-profile-image:before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: var(--accent-color);
    opacity: 0.6;
    transition: all 0.8s ease;
}

.pwe-profile-image .pwe-profiles__expanding-title {
    position: absolute;
    z-index: 1;
    color: white !important;
    bottom: 64px;
    left: 50%;
    transform: translate(-50%, 0);
    width: 260px;
    text-align: center;
    font-size: 18px !important;
    padding: 6px 14px;
    border-radius: 36px;
    border: 2px solid transparent;
    transition: all 0.4s ease;
}

.pwe-profile-image:hover .pwe-profiles__expanding-title {
    border: 2px solid white;
}

.pwe-profiles__expanding-item.active .pwe-profile-image > .pwe-profiles__expanding-title {
  display: none;
}

 
.pwe-profiles__expanding-item.active .pwe-profile-image:before {
    opacity: 0;
}

.pwe-profiles__expanding-item .pwe-profile-text {
    display: block;
    width: 0;
    opacity: 0;
    max-height: 400px;
    transition: all 0.8s ease;
}

.pwe-profiles__expanding-item.active {
  width: 100%;
  max-width: 100%;
  flex-direction: row;
}

.pwe-profiles__expanding-item.active .pwe-profile-text {
    display: block;
    flex: 1;
    width: 100%;
    opacity: 1;
    padding: 36px;
    max-height: 400px;
    overflow: hidden;
}
.pwe-profiles__expanding-title{
    margin: 0;
}
    .pwe-profiles__expanding-line {
    margin: 18px 0;
}
  .pwe-profiles__expanding-content-visable {
    overflow: auto;
    max-height: 82%;
    }

.pwe-profiles__expanding-item.active .pwe-profile-image {
  flex: .6;
}

.pwe-profiles__expanding-item.hidden {
  width: 0;
  display: block !important;
    visibility: visible !important;
}

/* Styl przycisku zamykania */
.pwe-profiles__expanding-item .close-btn {
  position: absolute;
  display: none;
  top: 10px;
  right: 10px;
  background: transparent;
  border: none;
  font-size: 24px;
  cursor: pointer;
  z-index: 10;
}
.pwe-profiles__expanding-item.active .close-btn {
  display: block;
}
  .pwe-profiles__expanding-content-visable ul{
  margin: 0 !important; 
      }
  @media(max-width: 900px){
.pwe-profiles__main-container-expanding {
    flex-direction: column;
}
    .pwe-profiles__expanding-item {
    width: 100% !important;
    min-height: 100px;
    max-height: unset;
    height: 16%;
      }
    .pwe-profiles__expanding-item .pwe-profile-image {
    min-height: 100px;
      }
    .pwe-profiles__expanding-item.active .pwe-profile-image {
    min-height: 100px;
      }
    .pwe-profiles__expanding-item .pwe-profile-text {
    max-height: 1px;
    padding: 0;
      }
.pwe-profiles__expanding-title {
    padding-top: 18px;
}
    .pwe-profiles__expanding-item.active {
    flex-direction: column;
}
    .pwe-profiles__expanding-item.active .pwe-profile-text {
    padding: 0;
    height: 300px;
}
    .pwe-profiles__expanding-item .pwe-profile-text {
    width: 100%;
    height: 0;
}
    .pwe-profiles__expanding-item {
    flex-direction: column;
}
    .pwe-profiles__expanding-content-visable {
    height: 300px;
    padding-right: 8px;
}
    .pwe-profiles__expanding-content-visable * {
    font-size: 12px !important
      }
.pwe-profile-image .pwe-profiles__expanding-title {
    bottom: 50%;
    transform: translate(-50%, 50%);
}

      }
        </style>';

        if (!empty($profile_expanding_title)) {
            $output .= '
            <div class="profile_expanding_title main-heading-text">
                <h4 class="pwe-uppercase"><span>'. $profile_expanding_title .'</span></h4>
            </div>';
        }
        $output .= '
        <div class="pwe-profiles__main-container-expanding">';

        foreach ($profile_items_expanding_json as $profile_item) {
          $profile_image = '';
          $profile_title_select_expanding = '';

          $profile_image_nmb_expanding = $profile_item["profile_image_expanding"];
          $profile_image_src_expanding = wp_get_attachment_url($profile_image_nmb_expanding);  

          $profile_expanding = $profile_item["profile_expanding"];
          $profile_content_expanding = self::decode_clean_content($profile_expanding);


          if ($profile_item["profile_title_select_expanding"] == 'profile_title_visitors') {
            $profile_id = "visitorProfile";
            $profile_title = (get_locale() == 'pl_PL') ? "Profil odwiedzającego" : "Visitor profile"; 
          } else if ($profile_item["profile_title_select_expanding"] == 'profile_title_exhibitors') {
            $profile_id = "exhibitorProfile";
            $profile_title = (get_locale() == 'pl_PL') ? "Profil wystawcy" : "Exhibitor profile";
          } else if ($profile_item["profile_title_select_expanding"] == 'profile_title_scope') {
            $profile_id = "industryScope";
            $profile_title = (get_locale() == 'pl_PL') ? "Zakres branżowy" : "Industry scope";
          } else {
            $profile_id = "customProfile-" . self::$rnd_id;
            $profile_title = $profile_item["profile_title_custom_expanding"];
          }

          $output .= '
          <div class="pwe-profiles__expanding-item">
            <div class="pwe-profile-image" style="background-image: url(' . $profile_image_src_expanding . ');">
              <h5 class="pwe-profiles__expanding-title">'. $profile_title .'</h5>
            </div>
            <div class="pwe-profile-text">
                <h5 class="pwe-profiles__expanding-title">'. $profile_title .'</h5>
                <hr class="pwe-profiles__expanding-line">
                <div class="pwe-profiles__expanding-content-visable">
                    '. $profile_content_expanding .'
                </div>
            </div>
          </div>';        
        }
        $output .= '
        </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll(".pwe-profiles__expanding-item");

  items.forEach(item => {
    item.addEventListener("click", () => {
      // Ukryj pozostałe
      items.forEach(el => {
        el.classList.add("hidden");
        el.classList.remove("active");
      });

      // Pokaz tylko kliknięty
      item.classList.remove("hidden");
      item.classList.add("active");

      // Dodaj przycisk zamykania tylko raz
if (!item.querySelector(".close-btn")) {
  const btn = document.createElement("button");
  btn.classList.add("close-btn");
  btn.innerText = "✕";

  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    items.forEach(el => {
      el.classList.remove("hidden");
      el.classList.remove("active");
    });
  });

  const textContainer = item.querySelector(".pwe-profile-text");
  if (textContainer) {
    textContainer.style.position = "relative"; // aby button miał punkt odniesienia
    textContainer.appendChild(btn);
  }
}
    });
  });
});

            </script>';

        return $output;
    }
}