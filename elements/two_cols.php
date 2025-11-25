<?php

/**
 * Class PWElementTwoCols
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementTwoCols extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public function catalogFunctions() {
        require_once plugin_dir_path(__FILE__) . 'classes/catalog_functions.php';
    }

    public function pweProfileButtons() {
        require_once plugin_dir_path(__FILE__) . 'profile/classes/profile-buttons.php';
    }
    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Background image', 'pwelement'),
                'param_name' => 'pwe_two_cols_backgroundimage',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Background heading', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_heading',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Heading', 'pwelement'),
                'param_name' => 'pwe_two_cols_small_heading',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Description', 'pwelement'),
                'param_name' => 'pwe_two_cols_text',
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Image src', 'pwelement'),
                'param_name' => 'pwe_two_cols_img_src',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Left button text', 'pwelement'),
                'param_name' => 'pwe_two_cols_button_left',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Right button text', 'pwelement'),
                'param_name' => 'pwe_two_cols_button_right',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Left button link', 'pwelement'),
                'param_name' => 'pwe_two_cols_link_left',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
              'type' => 'colorpicker',
              'group' => 'PWE Element',
              'heading' => __('Button left color', 'pwe_map'),
              'param_name' => 'pwe_two_cols_button_left_color',
              'description' => __('Button left custom color', 'pwe_map'),
              'param_holder_class' => 'backend-area-one-fourth-width',
              'save_always' => true,
              'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementTwoCols',
              ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Right button link', 'pwelement'),
                'param_name' => 'pwe_two_cols_link_right',
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom conference directory', 'pwelement'),
                'param_name' => 'pwe_two_cols_custom_directory',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom slider numbers', 'pwelement'),
                'param_name' => 'pwe_two_cols_custom_slider_numbers',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
              'type' => 'colorpicker',
              'group' => 'PWE Element',
              'heading' => __('Button right color', 'pwe_map'),
              'param_name' => 'pwe_two_cols_button_right_color',
              'description' => __('Button right custom color', 'pwe_map'),
              'param_holder_class' => 'backend-area-one-fourth-width',
              'save_always' => true,
              'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementTwoCols',
              ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Column reverse', 'pwelement'),
                'param_holder_class' => 'backend-area-one-fifth-width',
                'param_name' => 'pwe_two_cols_reverse',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Column reverse mobile', 'pwelement'),
                'param_holder_class' => 'backend-area-one-fifth-width',
                'param_name' => 'pwe_two_cols_reverse_mobile',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show conference logo', 'pwelement'),
                'param_holder_class' => 'backend-area-one-fifth-width',
                'param_name' => 'pwe_two_cols_show_logocongres',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show exhibitors logo', 'pwelement'),
                'param_holder_class' => 'backend-area-one-fifth-width',
                'param_name' => 'pwe_two_cols_show_exhibitors',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show patrons logo', 'pwelement'),
                'param_holder_class' => 'backend-area-one-fifth-width',
                'param_name' => 'pwe_two_cols_show_mediapatrons',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show media slider', 'pwelement'),
                'param_holder_class' => 'backend-area-one-fifth-width',
                'param_name' => 'pwe_two_cols_slider',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Background title in column', 'pwelement'),
                'param_holder_class' => 'backend-area-one-fifth-width',
                'param_name' => 'pwe_two_cols_title_in_row',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
              'type' => 'checkbox',
              'group' => 'PWE Element',
              'heading' => __('Disable randomization', 'pwelement'),
              'param_holder_class' => 'backend-area-one-fifth-width',
              'param_name' => 'remove_randomize',
              'save_always' => true,
              'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementTwoCols',
              ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Shadow element',
                'heading' => __('Show shadow element left', 'pwelement'),
                'param_name' => 'pwe_two_cols_shadow_left',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element background image', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_background_left',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element link', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_link_left',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element heading', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_heading_left',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element subheading', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_subheading_left',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'Shadow element',
                'heading' => __('Shadow element text', 'pwelement'),
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_text_left',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
              'type' => 'attach_images',
              'group' => 'Shadow element',
              'heading' => __('Logos', 'pwelement'),
              'param_holder_class' => 'backend-area-half-width',
              'param_name' => 'pwe_two_cols_images_left',
              'save_always' => true,
              'dependency' => array(
                'element' => 'pwe_element',
                'value' => 'PWElementTwoCols',
              ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'Shadow element',
                'heading' => __('Show shadow element right', 'pwelement'),
                'param_name' => 'pwe_two_cols_shadow_right',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element background image', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_background_right',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element link', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_link_right',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element heading', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_heading_right',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'Shadow element',
                'heading' => __('Shadow element subheading', 'pwelement'),
                'param_holder_class' => 'backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_subheading_right',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'Shadow element',
                'heading' => __('Shadow element text', 'pwelement'),
                'param_holder_class' => 'backend-textarea-raw-html backend-area-half-width',
                'param_name' => 'pwe_two_cols_shadow_text_right',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTwoCols',
                ),
            ),
            array(
              'type' => 'attach_images',
              'group' => 'Shadow element',
              'heading' => __('Logos', 'pwelement'),
              'param_holder_class' => 'backend-area-half-width',
              'param_name' => 'pwe_two_cols_images_right',
              'save_always' => true,
              'dependency' => array(
                'element' => 'pwe_element',
                'value' => 'PWElementTwoCols',
              ),
            ),
        );
        return $element_output;
    }


    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/two_cols.json';

        // JSON file with translation
        $translations_data = json_decode(file_get_contents($translations_file), true);

        // Is the language in translations
        if (isset($translations_data[$locale])) {
            $translations_map = $translations_data[$locale];
        } else {
            // By default use English translation if no translation for current language
            $translations_map = $translations_data['en_US'];
        }

        // Return translation based on key
        return isset($translations_map[$key]) ? $translations_map[$key] : $key;
    }

    public static function output($atts) {

        extract( shortcode_atts( array(
            'pwe_two_cols_heading'       => '',
            'pwe_two_cols_small_heading' => '',
            'pwe_two_cols_text'          => '',
            'pwe_two_cols_img_src'       => '',
            'pwe_two_cols_backgroundimage' => '',
            'pwe_two_cols_button_left'   => '',
            'pwe_two_cols_button_right'  => '',
            'pwe_two_cols_link_right'    => '',
            'pwe_two_cols_link_left'    => '',
            'pwe_two_cols_button_left_color' => '',
            'pwe_two_cols_button_right_color' => '',
            'pwe_two_cols_reverse'         => '',
            'pwe_two_cols_show_logocongres'=> '',
            'pwe_two_cols_show_exhibitors' => '',
            'pwe_two_cols_show_mediapatrons' => '',
            'pwe_two_cols_slider' => '',
            'pwe_two_cols_reverse_mobile' => '',
            'pwe_two_cols_title_in_row' => '',
            'pwe_two_cols_images_left' => '',
            'pwe_two_cols_shadow_left' => '',
            'pwe_two_cols_shadow_heading_left' => '',
            'pwe_two_cols_shadow_subheading_left' => '',
            'pwe_two_cols_shadow_text_left' => '',
            'pwe_two_cols_shadow_right' => '',
            'pwe_two_cols_shadow_heading_right' => '',
            'pwe_two_cols_shadow_subheading_right' => '',
            'pwe_two_cols_shadow_text_right' => '',
            'pwe_two_cols_shadow_background_left' => '',
            'pwe_two_cols_shadow_background_right' => '',
            'pwe_two_cols_images_right' => '',
            'pwe_two_cols_shadow_link_left' => '',
            'pwe_two_cols_shadow_link_right' => '',
            'pwe_two_cols_custom_directory' => '',
            'pwe_two_cols_custom_slider_numbers' => '',
            'remove_randomize' => '',
        ), $atts ));

        $colas_text = PWECommonFunctions::decode_clean_content($pwe_two_cols_text);

        $pwe_two_cols_img_src = (!empty($pwe_two_cols_img_src)) ? $pwe_two_cols_img_src : '/wp-content/plugins/pwe-media/media/poznaj-targi.jpg';

        $pwe_two_cols_button_left_color = (!empty($pwe_two_cols_button_left_color)) ? $pwe_two_cols_button_left_color : self::$fair_colors['Accent'];

        $pwe_two_cols_button_darker = self::adjustBrightness($pwe_two_cols_button_left_color, -30);

        $pwe_two_cols_button_right_color = (!empty($pwe_two_cols_button_right_color)) ? $pwe_two_cols_button_right_color : $pwe_two_cols_button_darker;

        $unique_id = rand(10000, 99999);
        $element_unique_id = 'twocols-' . $unique_id;


        $pwe_two_cols_button_right = (!empty($pwe_two_cols_button_right)) ? $pwe_two_cols_button_right : self::multi_translation("join");
        $pwe_two_cols_button_left = (!empty($pwe_two_cols_button_left)) ? $pwe_two_cols_button_left : self::multi_translation("gallery");
        $pwe_two_cols_link_left = (!empty($pwe_two_cols_link_left)) ? $pwe_two_cols_link_left : self::multi_translation("galeria_link");
        $pwe_two_cols_link_right = (!empty($pwe_two_cols_link_right)) ? $pwe_two_cols_link_right : self::multi_translation("registration");


        /* Patroni */


        if($pwe_two_cols_show_mediapatrons || $pwe_two_cols_slider){

          if(empty($pwe_two_cols_custom_directory)){
            $base_directory = '/doc/Logotypy/Rotator 2';
          } else {
            $base_directory = $pwe_two_cols_custom_directory;
          }

          if(empty($pwe_two_cols_custom_slider_numbers)){
            $limit = 9;
          } else {
            $limit = $pwe_two_cols_custom_slider_numbers;
          }

          $patronImages = PWEProfileButtons::getImagesFromDirectory($base_directory, $limit, $remove_randomize);

          // Get logotypes from CAP database
          $cap_logotypes_data = PWECommonFunctions::get_database_logotypes_data();
          if (!empty($cap_logotypes_data)) {
            if (strpos($base_directory, 'Rotator 2') !== false) {
              $logotypy = [];
              if (do_shortcode('[trade_fair_group]') === 'gr2') {
                foreach ($cap_logotypes_data as $logo_data) {
                  if ($logo_data->logos_type === "partner-merytoryczny") {
                    $logotypy[] = 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url;
                  }
                }
              } else {
                foreach ($cap_logotypes_data as $logo_data) {
                  if ($logo_data->logos_type === "partner-targow" ||
                      $logo_data->logos_type === "patron-medialny" ||
                      $logo_data->logos_type === "partner-strategiczny" ||
                      $logo_data->logos_type === "partner-honorowy" ||
                      $logo_data->logos_type === "principal-partner" ||
                      $logo_data->logos_type === "industry-media-partner" ||
                      $logo_data->logos_type === "partner-branzowy" ||
                      $logo_data->logos_type === "partner-merytoryczny") {
                    $logotypy[] = 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url;
                  }
                }
              }
            }

            $logotypes_catalogs_array = explode(',', $base_directory);
            foreach ($cap_logotypes_data as $logo_data) {
                if (in_array($logo_data->logos_type, $logotypes_catalogs_array)) {
                    $logotypy[] = 'https://cap.warsawexpo.eu/public' . $logo_data->logos_url;
                }
            }
          }

          $logotypy = array_slice($patronImages, 0, 10);


        }

        /* End Patroni */

        /* Wystawcy */

        if ($pwe_two_cols_show_exhibitors) {
          $identification = do_shortcode('[trade_fair_catalog]');
          $exhibitors = CatalogFunctions::logosChecker($identification, "PWECatalog10");

          if (!is_array($exhibitors) || empty($exhibitors)) {
              echo '<script>console.error("Błąd: logosChecker nie zwrócił poprawnej listy wystawców")</script>';
              $logotypy = [];
          } else {
              // Check if each element has the key 'URL_logo_wystawcy'
              $logotypy = array_map(function ($exhibitor) {
                  return isset($exhibitor['URL_logo_wystawcy']) ? $exhibitor['URL_logo_wystawcy'] : null;
              }, $exhibitors);

              // Removing empty values ​​from the array
              $logotypy = array_filter($logotypy);

              // Trim the array to 10 elements
              $logotypy = array_slice($logotypy, 0, 20);
          }
      }

      $id_rnd = PWECommonFunctions::id_rnd();

      $output = '
      <style>

        .wpb_column:has(.'. $element_unique_id .'){
          padding-top:0 !important;
        }
        .'. $element_unique_id .' .info-image-container {
          display: flex;
          justify-content: center;
          gap: 25px;
          align-items: stretch;
        }

        .'. $element_unique_id .' .info-image-box, .'. $element_unique_id .' .two-cols-shadow-right, .'. $element_unique_id .' .two-cols-shadow-left, .'. $element_unique_id .' .info-text-box {
          display: flex;
          flex: 1;
          flex-direction: column;
          justify-content: space-between;
          position: relative;
          margin-bottom: 55px;
        }

        .'. $element_unique_id .' .info-image-box img {
          border-radius: 30px;
          height: 100%;
          object-fit: cover;
        }
        .'. $element_unique_id .' .info-text-box h2 {
          font-size:29px;
        }
        .'. $element_unique_id .' .info-text-box h6 {
          text-align: center;
          display: block;
          margin: 12px auto 8px;
          font-size: 13px;
        }
        .'. $element_unique_id .' .info-text-box .logo-kongres {
          max-width: 50%;
          margin: 0 auto;
          display: block;
        }
        .'. $element_unique_id .' .two-cols-logotypes {
          max-width: 500px;
          margin: 0 auto;
        }
        .'. $element_unique_id .' .two-cols-logotypes img {
          padding: 5px;
        }
        .'. $element_unique_id .' .akcent {
          background-color: '. $pwe_two_cols_button_left_color .'
        }

        .'. $element_unique_id .' .main-2 {
          background-color: '. $pwe_two_cols_button_right_color .'
        }

        .'. $element_unique_id .' .info-text-box a, .'. $element_unique_id .' .info-image-box a  {
          color: white !important;
          min-width: 200px;
          padding: 10px 20px;
          display: block;
          margin: 0 auto;
          border-radius: 10px;
          margin-top: 20px;
          text-align: center;
          transition: all 0.3s ease-in-out;
          font-weight: 500;
        }
        .'. $element_unique_id .' .slick-dots {
            transform: scale(.7) !important;
            bottom: 0px !important;
        }
        .'. $element_unique_id .' .background-title {
          font-size: clamp(8rem, 15vw, 8rem);
          text-align: center;
          font-weight: 900;
          line-height: 1;
          white-space: nowrap;
          width: 100%;
          overflow: hidden;
          margin-top: 0px;
          color: '. $pwe_two_cols_button_left_color .';
          opacity: .5;
          text-align: center;
          text-transform: uppercase;
        }

        .'. $element_unique_id .' .main-2:hover {
          background-color: '. $pwe_two_cols_button_left_color .';
        }

        .'. $element_unique_id .' .akcent:hover {
          background-color: '. $pwe_two_cols_button_right_color .';
        }
        .'. $element_unique_id .' .background-image {
          display:flex;
          justify-content: center;
        }
        .'. $element_unique_id .' .background-image img {
          width: 100%;
        }
        .'. $element_unique_id .' .logo-exhibitors {
          padding: 15px;
          border-radius: 30px;
          -webkit-box-shadow: 4px 17px 30px -7px rgba(66, 68, 90, 1);
          -moz-box-shadow: 4px 17px 30px -7px rgba(66, 68, 90, 1);
          box-shadow: 4px 17px 30px -7px rgba(66, 68, 90, 1);
        }
        .'. $element_unique_id .' .logo-exhibitors div {
          display: flex;
          flex-wrap: wrap;
          justify-content: space-around;
        }
        .'. $element_unique_id .' .logo-exhibitors h3 {
          display: block;
          margin: 10px auto;
          font-size: 20px;
          text-transform: uppercase;
        }
        .'. $element_unique_id .' .logo-exhibitors div img {
          display:none;
        }
        .'. $element_unique_id .' .logo-exhibitors div img {
          display: block;
          width: 30%;
          aspect-ratio: 3 / 2;
          object-fit: contain;
          height:auto;
        }
        .'. $element_unique_id .' .background-title-column {
          font-size: 69px !important;
          margin-bottom: -20px;
        }';
        if(!$pwe_two_cols_reverse){
        $output .= '
            .'. $element_unique_id .' .info-image-container {
              flex-direction: row-reverse;
            }
            ';
        }
      $output .='
        @media(max-width:1200px) {
          .'. $element_unique_id .' .background-title {
            font-size: 90px !important;
          }
          .'. $element_unique_id .' .background-title-column {
            font-size: 60px !important;
          }
        }

        @media(max-width:920px) {
          .'. $element_unique_id .' .logo-exhibitors div img:nth-child(-n+9) {
            width: 46%;
          }
          .'. $element_unique_id .' .info-image-container {
            flex-direction: column;
          }

          .'. $element_unique_id .' .background-title {
            font-size: 63px !important;
          }

          .'. $element_unique_id .' .info-image-box,
          .'. $element_unique_id .' .info-text-box {
            margin-bottom: 10px;
          }
        }';
        if(!$pwe_two_cols_reverse_mobile){
          $output .= '
            @media(max-width:920px){
              .'. $element_unique_id .' .info-image-container {
                flex-direction: column-reverse;
              }
            }
            ';
        }
        $output .= '
        @media(max-width:570px) {
          .'. $element_unique_id .' .background-title {
            font-size: 36px !important;
          }
          .'. $element_unique_id .' .info-text-box .logo-kongres {
            margin:15px auto !important;
          }
        }
      </style>';
      if($pwe_two_cols_shadow_left || $pwe_two_cols_shadow_right){
        $output .= '
        <style>
          .info-image-container {

          }
          .'. $element_unique_id .' .two-cols-shadow-right, .'. $element_unique_id .' .two-cols-shadow-left {
            min-height: 480px;
            border-radius: 30px;
            position: relative;
            overflow: hidden;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease-in-out;
            transform: scale(1);
          }
          .'. $element_unique_id .' .two-cols-shadow-right:hover, .'. $element_unique_id .' .two-cols-shadow-left:hover {
            transform: scale(1.05);
          }
          .'. $element_unique_id .'  .two-cols-shadow-right::before, .'. $element_unique_id .' .two-cols-shadow-left::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
            pointer-events: none;
          }
          .'. $element_unique_id .' .two-cols-shadow-right a, .'. $element_unique_id .' .two-cols-shadow-left a {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 10;
          }
          .'. $element_unique_id .' .two-cols-shadow-container h2, .'. $element_unique_id .' .two-cols-shadow-container .subheading {
            font-size: 22px;
            font-weight: 600 !important;
            text-transform:uppercase;
            color:white !important;
            margin:0 !important;
            text-align: center;
          }

          .'. $element_unique_id .' .two-cols-shadow-container .subheading {
            font-size: 18px;
          }
          .'. $element_unique_id .' .two-cols-shadow-container p {
            color:white !important;
            font-size: 16px;
            text-align: center;
            margin: auto;
          }
          .'. $element_unique_id .' .two-cols-shadow-container, .'. $element_unique_id .' .image-container {
            display: flex;
            align-items: center;
            justify-content:center;
          }

          .'. $element_unique_id .'  .two-cols-shadow-container {
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            z-index: 2;
            height: 80%;
            width: 80%;
            padding: 18px;
            background: rgb(91 91 91 / 20%);
            box-shadow: 0 8px 32px 0 rgb(0 0 0 / 37%);
            backdrop-filter: blur(2.5px);
            -webkit-backdrop-filter: blur(2.5px);
            border-radius: 10px;
          }

          .'. $element_unique_id .' .image-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            gap: 10px;
            max-height: 200px;
          }

          .'. $element_unique_id .' .image-container img {
            width: auto;
            height: 100%;
            object-fit: cover;
            display: block;
            max-height: 200px;
          }
          @media(max-width:1200px) and (min-width:920px){
            .'. $element_unique_id .' .image-container {
              max-height: 150px;
            }
          }
          @media(max-width:620px){
            .'. $element_unique_id .' .image-container {
              flex-direction: column;
              max-height: 100%;
            }
            .'. $element_unique_id .' .two-cols-shadow-container {
              margin:25px;
            }
          }
        </style>';
        }
      $output .= '

      <div id="two_cols_element-'. $id_rnd .'" class="'. $element_unique_id .'">';
        if($pwe_two_cols_heading && !$pwe_two_cols_title_in_row){
          $output .= '
            <div class="background-title">
              '. $pwe_two_cols_heading .'
            </div>';
        };
        if($pwe_two_cols_backgroundimage){
          $output .= '
            <div class="background-image">
              <img src="'. $pwe_two_cols_backgroundimage .'" alt="Logo [trade_fair_name]"/>
            </div>';
        }
        $output .= '<div class="info-image-container">';

        /* Normal two cols */

        if(!$pwe_two_cols_shadow_left){
          $output .= '<div class="info-text-box">';
            if($pwe_two_cols_heading && $pwe_two_cols_title_in_row){
              $output .= '
                <div class="background-title background-title-column">
                  '. $pwe_two_cols_heading .'
                </div>';
            }
          $output .= '
            <div>
              <h2>'. $pwe_two_cols_small_heading .'</h2>
              '. $colas_text .'';

              /* logo kongres */
              if($pwe_two_cols_show_logocongres){
                $output .= '
                <img class="logo-kongres" src="/doc/kongres-color.webp" alt="Congress logo"/>';
              }

              /* slider */
              if($pwe_two_cols_slider){
                $output .= '
                  <h6>'. self::multi_translation("patron_partners") .'</h6>
                  <div class="two-cols-logotypes pwe-slides">';

                foreach ($logotypy as $logo) {
                    $output .= '<img id="'. pathinfo($logo)['filename'] .'" data-no-lazy="1" src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="'. pathinfo($logo)['filename'] .'"/>';
                }
                $output .= '</div>';
                include_once plugin_dir_path(__FILE__) . '/../scripts/slider.php';
                $output .= PWESliderScripts::sliderScripts('two-cols-logotypes', '#two_cols_element-'. $id_rnd, $opinions_dots_display = 'true', $opinions_arrows_display = false, 5);
              }

            $output .= '
            </div>
            <a class="main-2" href="'. $pwe_two_cols_link_right .'">'. $pwe_two_cols_button_right .'</a>
          </div>';
        } else {

        /* SHADOW TWO COLS Right */

          $output .= '
          <div class="two-cols-shadow-right" style="background:url('.$pwe_two_cols_shadow_background_right.')";>';
            if($pwe_two_cols_shadow_link_right){
              $output .= '<a href="'.$pwe_two_cols_shadow_link_right.'"></a>';
            }
          $output .= '
            <div class="two-cols-shadow-container">';
              if($pwe_two_cols_shadow_heading_right){
                $output .='<h2>'. $pwe_two_cols_shadow_heading_right .'</h2>';
              }
              if($pwe_two_cols_images_right){
                $output .= '<div class="image-container">';

                  $image_ids = explode(',', $pwe_two_cols_images_right);

                  foreach ($image_ids as $image_id) {
                      $image_id = trim($image_id);

                      $image_url = wp_get_attachment_image_url((int)$image_id, 'full');

                      if ($image_url) {
                          $output .= '<img id="'. pathinfo($image_url)['filename'] .'" data-no-lazy="1" src="' . esc_url($image_url) . '" alt="Logo wystawcy"/>';
                      }
                  }

                $output .= '</div>';
              }
              if($pwe_two_cols_shadow_subheading_right){
                $output .= '<p class="subheading">'.$pwe_two_cols_shadow_subheading_right.'</p>';
              }
              if($pwe_two_cols_shadow_text_right){
                $shadow_text = PWECommonFunctions::decode_clean_content($pwe_two_cols_shadow_text_right);
                $output .= $shadow_text;
              }
          $output .='
            </div>
          </div>';
        };

        /* NORMAL TWO COLS */

        if(!$pwe_two_cols_shadow_right){
          $output .= '
            <div class="info-image-box">';

            /* Zdjęcie */
            if(!$pwe_two_cols_show_exhibitors && !$pwe_two_cols_show_mediapatrons){
              $output .= '<img data-no-lazy="1" src="'. $pwe_two_cols_img_src .'" />';
            }

            /* Wystawcy */
            if ($pwe_two_cols_show_exhibitors) {
                $output .= '
                <div class="logo-exhibitors">
                    <h3>'. self::multi_translation("exhibitors") .'</h3>
                    <div id="logotypes-container" class="logotypes-container pwe-container-logotypes" data-logos=\'' . json_encode($logotypy) . '\'>';

                // Tworzymy 9 pustych miejsc na logotypy
                for ($i = 0; $i < 9; $i++) {
                    $output .= '<img class="logo-placeholder" data-no-lazy="1"  alt="Logo wystawcy" style="visibility: hidden;">';
                }

                $output .= '</div></div>';
            }

            /* Medialni */
            if($pwe_two_cols_show_mediapatrons){
              $output .= '<div class="logo-exhibitors">
                <h3>'. self::multi_translation("patrons") .'</h3>
                <div class="logotypes-container pwe-container-logotypes">';

              foreach ($logotypy as $logo) {

                $output .= '<img id="'. pathinfo($logo)['filename'] .'" data-no-lazy="1" src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="Logo wystawcy">';
              }
              $output .= '</div></div>';
            }


            $output .= '

              <a class="akcent" href="'. $pwe_two_cols_link_left .'">'. $pwe_two_cols_button_left .'</a>
          </div>';
        } else {

        /* SHADOW TWO COLS LEFT */

          $output .= '
          <div class="two-cols-shadow-left" style="background:url('.$pwe_two_cols_shadow_background_left.')";>';
          if($pwe_two_cols_shadow_link_left){
            $output .= '<a href="'.$pwe_two_cols_shadow_link_left.'"></a>';
          }
          $output .= '<div class="two-cols-shadow-container">';
              if($pwe_two_cols_shadow_heading_left){
                $output .='<h2>'. $pwe_two_cols_shadow_heading_left .'</h2>';
              }
              if($pwe_two_cols_images_left){
                $output .= '<div class="image-container">';

                  $image_ids = explode(',', $pwe_two_cols_images_left);

                  foreach ($image_ids as $image_id) {
                      $image_id = trim($image_id);

                      $image_url = wp_get_attachment_image_url((int)$image_id, 'full');

                      if ($image_url) {
                          $output .= '<img id="'. pathinfo($image_url)['filename'] .'" data-no-lazy="1" src="' . esc_url($image_url) . '" alt="Logo wystawcy"/>';
                      }
                  }

                $output .= '</div>';
              }
              if($pwe_two_cols_shadow_subheading_left){
                $output .= '<p class="subheading">'.$pwe_two_cols_shadow_subheading_left.'</p>';
              }
              if($pwe_two_cols_shadow_text_left){
                $shadow_text = PWECommonFunctions::decode_clean_content($pwe_two_cols_shadow_text_left);
                $output .= '<p class="text">'.$shadow_text.'</p>';
              }
          $output .='
            </div></div>';

        };
        if ($pwe_two_cols_show_exhibitors || $pwe_two_cols_show_mediapatrons) {
          $output .= '
            <script>
            document.addEventListener("DOMContentLoaded", function () {
                let container = document.getElementById("logotypes-container");
                let placeholders = container.querySelectorAll(".logo-placeholder");
                let logos = JSON.parse(container.getAttribute("data-logos"));

                let logosToShow = [];

                if (logos.length >= 9) {
                    let randomIndexes = new Set();
                    while (randomIndexes.size < 9) {
                        let randomIndex = Math.floor(Math.random() * logos.length);
                        randomIndexes.add(randomIndex);
                    }
                    logosToShow = Array.from(randomIndexes).map(index => logos[index]);
                } else {
                    logosToShow = logos;
                }

                setTimeout(() => {
                    placeholders.forEach((img, i) => {
                        if (logosToShow[i]) {
                            img.src = logosToShow[i];
                            img.style.opacity = "1";
                            img.style.visibility = "visible";
                        } else {
                            img.style.display = "none";
                        }
                    });
                }, 10);
            });
            </script>';
        }
      $output .= '
       </div></div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const infoImageBox = document.querySelector(".'. $element_unique_id .' .info-image-box");
                const pweContainerLogotypes = document.querySelector(".'. $element_unique_id .' .pwe-container-logotypes");
                if ("'. current_user_can('administrator') .'" == true) {
                  if (pweContainerLogotypes && pweContainerLogotypes.children.length === 0) {
                      const loader = document.createElement("div");
                      loader.className = "pwe-loader";
                      pweContainerLogotypes.appendChild(loader);
                  }
                } else {
                  if (infoImageBox && pweContainerLogotypes && pweContainerLogotypes.children.length === 0) {
                    infoImageBox.style.display = "none";
                  }
                }
            });
        </script>';

        return $output;
    }
}