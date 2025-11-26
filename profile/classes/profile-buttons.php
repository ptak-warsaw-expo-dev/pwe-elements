<?php

/**
 * Class PWEProfileButtons
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWEProfileButtons extends PWEProfile {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function getImagesFromDirectory($basePath, $limit = 10, $order = false) {
        $allImages = [];

        $baseDir = ABSPATH . $basePath;

        if (is_dir($baseDir)) {
            $files = scandir($baseDir);

            foreach ($files as $file) {
                $filePath = $baseDir . '/' . $file;

                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_file($filePath) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                    $allImages[] = $basePath . '/' . $file;
                }
            }

            if (count($allImages) < $limit) {
                foreach ($files as $subfolder) {
                    $folderPath = $baseDir . '/' . $subfolder;

                    if (is_dir($folderPath) && $subfolder !== '.' && $subfolder !== '..') {
                        $subFiles = scandir($folderPath);

                        foreach ($subFiles as $subFile) {
                            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $subFile)) {
                                $allImages[] = $basePath . '/' . $subfolder . '/' . $subFile;
                            }
                        }
                    }
                }
            }
        }
        
        if (count($allImages) > $limit) {
            if($order){
                $allImages = array_slice($allImages, 0 , $limit);
            } else {
                $randomKeys = array_rand($allImages, $limit);
                $allImages = array_map(function($key) use ($allImages) {
                    return $allImages[$key];
                }, (array) $randomKeys);
                shuffle($allImages);
            }
        }

        return $allImages;
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {

        $element_output = array(
            array(
                'type' => 'param_group',
                'heading' => __('Items', 'pwe_profile'),
                'param_name' => 'profile_buttons_items',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileButtons',
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
                            'KONFERENCJA' => 'profile_title_visitors',
                            'PROFIL WYSTAWCY' => 'profile_title_exhibitors',
                            'ZAKRES BRANŻOWY' => 'profile_title_scope',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Custom title', 'pwe_profile'),
                        'param_name' => 'profile_title_custom',
                        'save_always' => true,
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
                        'type' => 'textfield',
                        'heading' => __('Custom button text', 'pwe_profile'),
                        'param_name' => 'profile_button_name',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Custon button href', 'pwe_profile'),
                        'param_name' => 'profile_button_href',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Logotypes', 'pwe_profile'),
                        'param_name' => 'profile_button_logotypes',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_profile') => 'true',),
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



    extract(shortcode_atts(array(
        'profile_buttons_items' => '',
        'base_directory' => 'doc/Logotypy/Rotator 2',
        'limit' => 10,
    ), $atts));
    $images = self::getImagesFromDirectory($base_directory);

    $profile_buttons_items_urldecode = urldecode($profile_buttons_items);
    $profile_buttons_items_json = json_decode($profile_buttons_items_urldecode, true);

    $output = '
    <style>
        .profile-buttons .pwe-profiles {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .profile-buttons .pwe-profiles__buttons-items {
            display: flex;
            width: 100%;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: space-around;
        }
        .profile-buttons .pwe-profiles__button {
            display: flex;
            justify-content: center;
            align-items: center;
            text-transform: uppercase;
            min-width: 160px;
            font-weight: 600;
            padding: 7px 10px;
            background-color: '.self::$main2_color.';
            color: #ffffff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, color 0.3s;
        }
        .profile-buttons .pwe-profiles__button.active {
            background-color: ' .self::$accent_color .';

        }
        .profile-buttons .pwe-profiles__contents {
          width: 100%;
        }
        .profile-buttons .pwe-profiles__content {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        .profile-buttons .pwe-profiles__content img {
          max-width: 300px;
          min-height: 150px;
          object-fit: contain;
        }
        .profile-buttons .pwe-profiles__content h3 {
          margin-top: 0px !important;
          color:'.self::$main2_color.';
        }
        .profile-buttons .pwe-profiles__content p {
          margin-bottom:25px;
        }
        .profile-buttons .pwe-profiles__content a {
          background-color:' .self::$accent_color .';
          color: white;
          padding: 8px 35px;
          border-radius: 25px;
          font-weight: 500;
          margin-top:25px;
        }
        .profile-buttons  .pwe-profiles-logotypes {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .profile-buttons  .pwe-profiles-logotypes img {
            width: 17%;
            min-height: 50px;
        }
        .profile-buttons .pwe-profiles__content.active {
          opacity: 1;
          transform: scale(1);
          display: flex;
          justify-content: center;
          align-items: center;
          flex-direction: column;
        }
        .profile-buttons .pwe-profiles__content:not(.active) {
            display: none;
        }

        /* Styl dla przycisku aktywnego */
        .profile-buttons .pwe-profiles__button.active {
            background-color: var(--accent-color, #ff5722); /* Można użyć akcentowego koloru */
            transform: scale(1.1);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.3s;
        }

        /* Animacja kliknięcia */
        .profile-buttons .pwe-profiles__button:active {
            transform: scale(0.95);
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.15);
            transition: transform 0.1s ease-in-out, box-shadow 0.1s ease-in-out;
        }

        @media(max-width:800px){
          .profile-buttons .pwe-profiles__button {
            min-width:100%;
          }
        }
        @media(max-width:620px){
          .profile-buttons .pwe-profiles__content p {
            margin-bottom:10px;
          }
          .profile-buttons .pwe-profiles__buttons-items {
            flex-wrap: wrap;
            gap: 6px;
          }

        }
    </style>';

    $output .= '
    <div class="pwe-profiles">
        <div class="pwe-profiles__buttons-items">';

    if (is_array($profile_buttons_items_json)) {
        foreach ($profile_buttons_items_json as $profile_item) {
            $profile_title_select = $profile_item["profile_title_select"];
            $profile_title_custom = $profile_item["profile_title_custom"];
            $profile_text = $profile_item["profile_text"];
            $profile_text_content = self::decode_clean_content($profile_text);
            $profile_button_name = $profile_item["profile_button_name"];
            $profile_button_href = $profile_item["profile_button_href"];
            $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom;
            $profile_custom = !empty($profile_title_custom) ? $profile_title_custom : $profile_title_select;
            $profile_id = strtolower(str_replace('_title', '', $profile_title));

            // Dostosowanie tytułów
            if ($profile_title == 'profile_title_visitors') {
                $profile_title = (get_locale() == 'pl_PL') ? "Konferencja" : "Visitor profile";
                $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/visitor_profile_icon_white.webp';
            } else if ($profile_title == 'profile_title_exhibitors') {
                $profile_title = (get_locale() == 'pl_PL') ? "Profil wystawcy" : "Exhibitor profile";
                $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/exhibitor_profile_icon_white.webp';
            } else if ($profile_title == 'profile_title_scope') {
                $profile_title = (get_locale() == 'pl_PL') ? "Zakres branżowy" : "Industry scope";
                $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/industry_scope_icon_white.webp';
            }

            // Aktywna zakładka (domyślnie "Konferencja")
            $active = ($profile_title == 'Konferencja') ? 'active' : '';



            $output .= '
            <div class="pwe-profiles__button button-' . $profile_id . ' ' . $active . '" onclick="openTab(event, \'' . $profile_id . '\')">
                ' . $profile_custom . '
            </div>';
        }
    }

    $output .= '
        </div>
        <div class="pwe-profiles__contents">';

    if (is_array($profile_buttons_items_json)) {
        foreach ($profile_buttons_items_json as $profile_item) {
            $profile_icon_nmb = $profile_item["profile_icon"];
            $profile_icon_src = wp_get_attachment_url($profile_icon_nmb);
            $profile_title_select = $profile_item["profile_title_select"];
            $profile_button_logotypes = $profile_item["profile_button_logotypes"];
            $profile_title_custom = $profile_item["profile_title_custom"];
            $profile_text = $profile_item["profile_text"];
            $profile_text_content = self::decode_clean_content($profile_text);
            $profile_button_name = $profile_item["profile_button_name"];
            $profile_button_href = $profile_item["profile_button_href"];
            $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom;
            $profile_id = strtolower(str_replace('_title', '', $profile_title));

            // Aktywna zawartość (domyślnie "Konferencja")
            $active = ($profile_title == 'profile_title_visitors') ? 'active' : '';

            $output .= '
            <div id="' . $profile_id . '" class="pwe-profiles__content ' . $active . '">';
               if($profile_button_logotypes){
                $output .= '<div class="pwe-profiles-logotypes">';

                    if (!empty($images)) {
                        foreach ($images as $image) {
                            // Tworzymy ścieżkę URL dla obrazu
                            $imageUrl = site_url($image) . '?v=' . uniqid();
                            $output .= '<img src="' . esc_url($imageUrl) . '" alt="Grafika">';
                        }
                    } else {
                        $output .= '<p>Brak grafik do wyświetlenia.</p>';
                    }

                $output .= '</div>';
               }
            $output .= '
                ' . $profile_text_content . '
                <img src="' . $profile_icon_src . '"/>';
            if($profile_button_name){
                $output .= '<a href="' . $profile_button_href . '">' . $profile_button_name . '</a>';
            }

            $output .= '</div>';
        }
    }

    $output .= '
        </div>
    </div>';

    $output .= '
    <script>
function openTab(event, tabId) {
    const buttons = document.querySelectorAll(".pwe-profiles__button");
    const contents = document.querySelectorAll(".pwe-profiles__content");

    // Deaktywuj wszystkie przyciski
    buttons.forEach(button => button.classList.remove("active"));

    // Ukryj wszystkie sekcje z animacją
    contents.forEach(content => {
        if (content.classList.contains("active")) {
            content.classList.remove("active");

                content.style.display = "none"; // Ukrycie elementu po animacji

        }
    });

    // Aktywuj kliknięty przycisk z animacją
    const clickedButton = event.currentTarget;
    clickedButton.classList.add("active");

    // Pokaż odpowiednią sekcję z animacją
    const activeContent = document.getElementById(tabId);
    if (activeContent) {
        activeContent.style.display = "flex"; // Ustawienie widoczności przed dodaniem klasy
        setTimeout(() => {
            activeContent.classList.add("active");
        }, 10); // Dodanie minimalnego opóźnienia dla płynnego przejścia
    }
}

    </script>';

    return $output;
}
}