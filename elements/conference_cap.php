<?php 

/**
 * Class PWElementConferenceCap
 * Extends PWElements class and defines a pwe Visual Composer element.
 */



class PWElementConferenceCap extends PWElements {

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
                'type'       => 'textfield',
                'group'      => 'PWE Element',
                'heading'    => __('Tytuł elementu', 'pwe_element'),
                'param_name' => 'conference_cap_title',
                'save_always'=> true,
                'std'        => __('Dane Konferencji', 'pwe_element'),
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementConferenceCap',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom style', 'pwe_element'),
                'param_name' => 'conference_cap_style',
                'save_always'=> true,
                'dependency' => array(
                  'element' => 'pwe_element',
                  'value' => 'PWElementConferenceCap',
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'heading' => __('Custom Html', 'pwe_element'),
                'param_name' => 'conference_cap_html',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConferenceCap',
                ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Conference slug', 'pwe_element'),
                        'param_name' => 'conference_cap_html_conf_slug',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Position', 'pwe_element'),
                        'param_name' => 'conference_cap_html_position',
                        'value' => array(
                            __('Before title', 'pwe_element') => 'before_title',
                            __('After title', 'pwe_element') => 'after_title',
                            __('Before day', 'pwe_element') => 'before_day',
                            __('After day', 'pwe_element') => 'after_day',
                        ),
                        'description' => __('Choose where to insert the custom HTML.', 'pwe_element'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Conference day', 'pwe_element'),
                        'param_name' => 'conference_cap_html_day',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea_raw_html',
                        'heading' => __('Custom html', 'pwe_element'),
                        'param_name' => 'conference_cap_html_code',
                        'save_always' => true,
                    ),
                ),
            ),            
        );
        return $element_output;
    }

    
    public static function connectToDatabase() {
        if ($_SERVER['SERVER_ADDR'] === '94.152.207.180') {
            $database_host = 'localhost';
            $database_name = 'automechanicawar_dodatkowa';
            $database_user = 'automechanicawar_admin-dodatkowa';
            $database_password = '9tL-2-88UAnO_x2e';
        }

        $cap_db = new wpdb($database_user, $database_password, $database_name, $database_host);

        // Check for errors errors
        if (!empty($cap_db->last_error)) {
            echo '<script>console.error("Błąd połączenia z bazą danych: '. $cap_db->last_error .'")</script>';
            return false;
        }

        // Additional connection test
        if (!$cap_db->dbh || mysqli_connect_errno()) {
            echo '<script>console.error("Błąd połączenia MySQL: '. mysqli_connect_error() .'")</script>';
            return false;
        }

        return $cap_db;
    }

    public static function getDatabaseDataConferences() {

        $cap_db = self::connectToDatabase();

        if (!$cap_db) {
            return [];
        }

        $results = $cap_db->get_results("SELECT * FROM conferences");
        // $results = $cap_db->get_results("SELECT conf_name, conf_slug, conf_data FROM conferences");

        // Debugging SQL errors
        if ($cap_db->last_error) {
            echo '<script>console.error("Błąd SQL: "'. $cap_db->last_error .'")</script>';
            return [];
        }

        return $results;
    }


    private static function speakerImageMini($speaker_images) { 
        // Filtrowanie pustych wartości
        $head_images = array_filter($speaker_images);
        // Resetowanie indeksów tablicy
        $head_images = array_values($head_images); 
        
        // Jeśli nie ma obrazów, zwracamy pusty string
        if (empty($head_images)) {
            return ''; 
        }
    
        // Inicjalizacja kontenera na obrazy
        $speaker_html = '<div class="pwe-box-speakers-img">';
    
        // Pętla po obrazach i dynamiczne ustawianie ich pozycji
        foreach ($head_images as $i => $image_src) {    
            if (!empty($image_src)) {
                $z_index = (1 + $i);
                $margin_top_index = '';
                $max_width_index = "50%";
    
                // Ustawienia pozycji w zależności od liczby prelegentów
                switch (count($head_images)) {
                    case 1:
                        $top_index = "unset";
                        $left_index = "unset";
                        $max_width_index = "80%";
                        break;
    
                    case 2:
                        switch ($i) {
                            case 0:
                                $margin_top_index = "margin-top: 20px";
                                $max_width_index = "50%";
                                $top_index = "-20px";
                                $left_index = "10px";
                                break;
                            case 1:
                                $max_width_index = "50%";
                                $top_index = "0";
                                $left_index = "-10px";
                                break;
                        }
                        break;
    
                    case 3:
                        switch ($i) {
                            case 0:
                                $top_index = "0";
                                $left_index = "15px";
                                break;
                            case 1:
                                $top_index = "40px";
                                $left_index = "-15px";
                                break;
                            case 2:
                                $top_index = "-15px";
                                $left_index = "-30px";
                                break;
                        }
                        break;
    
                    default:
                        switch ($i) {
                            case 0:
                                $margin_top_index = 'margin-top: 5px !important;';
                                break;
                            case 1:
                                $left_index = "-10px";
                                break;
                            default:
                                $reszta = $i % 2;
                                if ($reszta == 0) {
                                    $top_index = ($i / 2 * -15) . "px";
                                    $left_index = "0";
                                } else {
                                    $top_index = (floor($i / 2) * -15) . "px";
                                    $left_index = "-10px";
                                }
                                break;
                        }
                }
    
                // Generowanie HTML obrazów
                $speaker_html .= '<img class="pwe-box-speaker" src="'. esc_url($image_src) .'" alt="speaker portrait" 
                    style="position:relative; z-index:'.$z_index.'; top:'.$top_index.'; left:'.$left_index.'; max-width: '.$max_width_index.';'.$margin_top_index.';" />';
            }
        }
    
        $speaker_html .= '</div>';
    
        return $speaker_html;
    }
    

    public static function output($atts) {

        // Pobieramy ustawienia shortcode
        extract(shortcode_atts(array(
            'conference_cap_title' => '',
            'conference_cap_style' => '',
            'conference_cap_html' => '',
        ), $atts));

        $conference_cap_html = vc_param_group_parse_atts( $conference_cap_html );

        $database_data = self::getDatabaseDataConferences();
        // var_dump($database_data);
        
        // Zmienna na dynamiczne reguły CSS do pokazywania właściwej treści
        $dynamic_css = "";
        
        // Tablica na zapis danych prelegentów (bio) – identyfikator lecture-box => dane
        $speakersDataMapping = array();

        // Rozpoczynamy budowę wyjścia HTML – dodajemy styl (CSS)
        $output = '
            <style>

            /* Domyślnie ukrywamy wszystkie zakładki `conf_slug` */
                .conference_cap__conf-tab-content {
                    display: none;
                }

                /* Pokazujemy tylko aktywne `conf_slug` */
                .conference_cap__conf-tab-radio:checked ~ .conference_cap__conf-tabs-contents .conference_cap__conf-tab-content {
                    display: block;
                }

                /* Ukrywamy domyślnie wszystkie zakładki dni */
                .conference_cap__tab-content {
                    display: none;
                }

                /* Pokazujemy tylko aktywny dzień */
                .conference_cap__tab-radio:checked ~ .conference_cap__tabs-contents .conference_cap__tab-content {
                    display: block;
                }
                .conference_cap__conf-tab-radio, .conference_cap__tab-radio {
                    display: none !important;
                }
                .conference_cap__tabs-labels {
                    display: flex;
                    flex-wrap: nowrap;
                    margin: 10px;
                    justify-content: center;
                }
                .conference_cap__tab-label {
                    padding: 10px 20px;
                    background: #eee;
                    cursor: pointer;
                    margin: 4px;
                }
                .conference_cap__tab-radio:checked + .conference_cap__tab-label {
                    background: #ddd;
                    font-weight: bold;
                }
                .conference_cap__tab-content {
                    display: none;
                    padding: 15px;
                    border-top: 1px solid #ddd;
                }

                .conference_cap__conf-tabs-labels {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 24px;
                    padding: 28px 18px;
                }

                .conference_cap__conf-tabs-labels img{
                    border-radius: 8px;
                    width: 100%;
                    object-fit: cover;
                    aspect-ratio: 1/1;
                    max-width: 220px;
                    }

                .conference_cap__conf-title {
                    text-align: center;
                    margin: 36px auto;
                }

                /* ---- lecture-box ---- */
                .conference_cap__lecture-box {
                    display: flex;
                    text-align: left;
                    gap: 18px;
                    margin-top: 36px;
                    padding: 10px;
                }
                .conference_cap__lecture-speaker {
                    width: 200px;
                    min-width: 200px;
                    display: flex;
                    flex-direction: column;
                    text-align: center;
                }
                .conference_cap__lecture-speaker-item {
                    margin-bottom: 10px;
                }
                .conference_cap__lecture-box-info {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    gap: 18px;
                }
                .conference_cap__lecture-time, .conference_cap__lecture-name, .conference_cap__lecture-title, .conference_cap__lecture-desc p {
                    margin: 0;
                }
                .conference_cap__lecture-speaker-img img {
                    border-radius: 50%;
                    max-width: 80%;
                    background: white;
                    border: 2px solid gray;
                    aspect-ratio: 1 / 1;
                    object-fit: cover;
                }
                .conference_cap__lecture-speaker-btn {
                    margin: 10px auto !important;
                    color: white;
                    background-color: var(--accent-color);
                    border: 1px solid var(--accent-color);
                    padding: 6px 16px;
                    font-weight: 600;
                    width: 80px;
                    border-radius: 10px;
                    transition: .3s ease;
                }
                .conference_cap__lecture-speaker-btn:hover {
                    color: white;
                    background-color: color-mix(in srgb, var(--accent-color), black 20%);
                    border: 1px solid color-mix(in srgb, var(--accent-color), black 20%);
                }       
                /* Style modala */
                .custom-modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 10000;
                }
                .custom-modal {
                    background: #fefefe;
                    padding: 20px;
                    border-radius: 8px;
                    position: relative;
                    max-width: 800px;
                    width: 90%;
                    max-height: 90%;
                    overflow-y: auto;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                    border: 1px solid #888;
                    transition: transform 0.3s;
                    transform: scale(0);
                }
                .custom-modal-close {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: transparent;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                }
                .custom-modal-content {
                    display: flex;
                    flex-direction: column;
                    align-items: stretch;
                }
                .custom-modal-content img {
                    max-width: 150px;
                    border-radius: 8px;
                    margin-bottom: 10px;
                }
                .custom-modal-content h2 {
                    margin: 10px 0 10px;
                    font-size: 1.5em;
                }
                .custom-modal-content p {
                    margin: 0;
                }
                .custom-modal.visible {
                    transform: scale(1);
                }
            </style>
        ';
        
        if (!empty($conference_cap_title)) {
            $output .= '<h2>' . esc_html($conference_cap_title) . '</h2>';
        }
        
        $output .= '<div class="pwe-conference-cap-element" style="' . esc_attr($conference_cap_style) . '">';
        
        if (!empty($database_data)) {
            $conf_slug_index = 0;
            $conf_slugs = [];
            $dynamic_css = '';
            $conf_slug_radio_inputs = '';
            $conf_slug_tab_headers = '';
            $conf_slug_tab_contents = '';
        
            // **Zbiór `conf_slug` i grupowanie konferencji wg nich**
            foreach ($database_data as $conference) {
                if (empty($conference->conf_data)) {
                    continue;
                }
        
                $confData = json_decode($conference->conf_data, true);
                if (!is_array($confData)) {
                    continue;
                }
        
                $conf_slug = $conference->conf_slug;
                if (!isset($conf_slugs[$conf_slug])) {
                    $conf_slugs[$conf_slug] = [];
                }
                $conf_slugs[$conf_slug][] = $confData;
            }


            // **Generowanie zakładek `conf_slug`**
            foreach ($conf_slugs as $conf_slug => $conferences) {
                $inf_conf = [];

                foreach($conference_cap_html as $conf_cap_html){
                    if(in_array($conf_slug, $conf_cap_html)){
                        $inf_conf[$conf_cap_html['conference_cap_html_position'] . '_' . $conf_cap_html['conference_cap_html_day']] = PWECommonFunctions::decode_clean_content($conf_cap_html['conference_cap_html_code']);
                    }
                }

                $conf_slug_index++;
                $checked = ($conf_slug_index === 1) ? ' checked' : '';
        
                // Zakładki dla `conf_slug`
                $conf_slug_radio_inputs .= '<input type="radio" name="conference_cap_conf_tabs" id="conference_cap_conf_tab_' . $conf_slug_index . '" class="conference_cap__conf-tab-radio"' . $checked . '>';
                // Pobieranie obrazu dla danego conf_slug
                $image_src = '';
                foreach ($database_data as $conf) { // database_data zawiera dane z bazy
                    if ($conf->conf_slug === $conf_slug) {
                        $image_src = !empty($conf->conf_img) ? esc_url($conf->conf_img) : '';
                        break;
                    }
                }

                // Tworzenie etykiety zakładki - najpierw obraz, jeśli istnieje, inaczej tekst
                $conf_slug_tab_headers .= '<label for="conference_cap_conf_tab_' . $conf_slug_index . '" class="conference_cap__conf-tab-label">';

                if (!empty($image_src)) {
                    $conf_slug_tab_headers .= '<img src="' . $image_src . '" alt="' . esc_attr($conf_slug) . '" class="conference_cap__conf-tab-image">';
                } else {
                    $conf_slug_tab_headers .= esc_html($conf_slug);
                }

                $conf_slug_tab_headers .= '</label>';

        
                $conf_slug_content = '<div class="conference_cap__conf-tab-content" id="content_conference_cap_conf_tab_' . $conf_slug_index . '">';

                // Pobieranie obrazu i nazwy konferencji dla `conf_slug`
                $conf_img = '';
                $conf_name = '';

                foreach ($database_data as $conf) {
                    if ($conf->conf_slug === $conf_slug) {
                        $conf_img = !empty($conf->conf_img) ? esc_url($conf->conf_img) : '';
                        $conf_name = !empty($conf->conf_name) ? esc_html($conf->conf_name) : '';
                        break;
                    }
                }

                // **Dodanie nagłówka konferencji nad zakładkami dni - tylko jeśli istnieją dane**
                if (!empty($conf_img) || !empty($conf_name)) {
                    $conf_slug_content .= '<div class="conference_cap__conf-header">';
                    if (!empty($conf_img)) {
                        $conf_slug_content .= '<img src="' . $conf_img . '" alt="' . esc_attr($conf_name) . '" class="conference_cap__conf-header-img">';
                    }

                    $conf_slug_content .= $inf_conf['before_title_'] ?? '';

                    if (!empty($conf_name)) {
                        $conf_slug_content .= '<h2 class="conference_cap__conf-title">' . $conf_name . '</h2>';
                    }

                    $conf_slug_content .= $inf_conf['after_title_'] ?? '';

                    $conf_slug_content .= '</div>'; // Koniec nagłówka konferencji
                }

        
                // **Tworzenie zakładek dni dla `conf_slug`**
                $tab_index = 0;
                $radio_inputs = '';
                $tab_headers = '';
                $tab_contents = '';
                foreach ($conferences as $confData) {
                    
                    foreach ($confData as $day => $sessions) {
                        $data_check = explode(' ', $day);
                        $tab_index++;
                        $day_checked = ($tab_index === 1) ? ' checked' : '';
        
                        // **Unikalne ID dla każdego dnia w danym `conf_slug`**
                        $radio_inputs .= '<input type="radio" name="conference_cap_tabs_' . $conf_slug_index . '" id="conference_cap_tab_' . $conf_slug_index . '_' . $tab_index . '" class="conference_cap__tab-radio"' . $day_checked . '>';
                        $tab_headers .= '<label for="conference_cap_tab_' . $conf_slug_index . '_' . $tab_index . '" class="conference_cap__tab-label">' . esc_html($day) . '</label>';
        
                        $content = '<div class="conference_cap__tab-content" id="content_conference_cap_tab_' . $conf_slug_index . '_' . $tab_index . '">';

                        $content .= $inf_conf['before_day_' . $data_check[1]] ?? '';

                        if (!empty($sessions)) {
                            $content .= '<div class="conference_cap__lecture-container">';

                            foreach ($sessions as $key => $session) {
                                if (strpos($key, 'pre-') !== 0) {
                                    continue; // Pomijamy wpisy, które nie zaczynają się od "pre-"
                                }
        
                                $lecture_counter++;
                                $lectureId = 'cap-lecture-' . $lecture_counter;
                                $time  = isset($session['time']) ? $session['time'] : '';
                                $title = isset($session['title']) ? $session['title'] : '';
                                $desc  = isset($session['desc']) ? $session['desc'] : '';
        
                                // Pobieramy dane prelegentów
                                $speakers = [];
                                foreach ($session as $key => $value) {
                                    if (strpos($key, 'legent-') === 0 && is_array($value)) {
                                        $speakers[] = $value;
                                    }
                                }
        
                                $content .= '<div id="' . esc_attr($lectureId) . '" class="conference_cap__lecture-box">';
                                $content .= '<div class="conference_cap__lecture-speaker">';
                                $speakers_bios = [];
        
                                if (!empty($speakers)) {
                                    $speaker_images = []; // Tablica na zdjęcia prelegentów
                                
                                    foreach ($speakers as $speaker) {
                                        $speaker_name = isset($speaker['name']) ? $speaker['name'] : '';
                                        $speaker_url  = isset($speaker['url']) ? $speaker['url'] : '';
                                        $speaker_desc = isset($speaker['desc']) ? $speaker['desc'] : '';
                                
                                        if (!empty($speaker_name) && $speaker_name !== '*') {
                                            $content .= '<div class="conference_cap__lecture-speaker-item">';
                                
                                            if (!empty($speaker_url)) {
                                                // Zapisanie URL do tablicy, zamiast dodawania pojedynczego obrazka w pętli
                                                $speaker_images[] = $speaker_url;
                                            }
                                
                                            $content .= '</div>'; // Koniec .conference_cap__lecture-speaker-item
                                
                                            if (!empty($speaker_desc)) {
                                                $speakers_bios[] = array(
                                                    'name' => $speaker_name,
                                                    'url'  => $speaker_url,
                                                    'bio'  => $speaker_desc
                                                );
                                            }
                                        }
                                    }
                                
                                    // Dodanie funkcji speakerImageMini po pętli
                                    if (!empty($speaker_images)) {
                                        $content .= '<div class="conference_cap__lecture-speaker-img">' . self::speakerImageMini($speaker_images) . '</div>';
                                    }
                                
                                    if (!empty($speakers_bios)) {
                                        $speakersDataMapping[$lectureId] = $speakers_bios;
                                        $content .= '<button class="conference_cap__lecture-speaker-btn">BIO</button>';
                                    }
                                }
                                
                                $content .= '</div>';
        
                                $content .= '<div class="conference_cap__lecture-box-info">';
                                $content .= '<h4 class="conference_cap__lecture-time">' . esc_html($time) . '</h4>';
                                $speaker_names = array_map(function ($speaker) {
                                    return $speaker['name'];
                                }, $speakers);
    
                                if (!empty($speaker_names) && implode('', $speaker_names) !== 'brak') {
                                    $content .= '<h5 class="conference_cap__lecture-name">' . esc_html(implode(', ', $speaker_names)) . '</h5>';
                                }
                                
                                $content .= '<h4 class="conference_cap__lecture-title">' . esc_html($title) . '</h4>';
                                $content .= '<div class="conference_cap__lecture-desc"><p>' . esc_html($desc) . '</p></div>';
                                $content .= '</div>';
                                $content .= '</div>';
                            }
                        
                            
                            $content .= '</div>';
                        } else {
                            $content .= '<p>Brak danych do wyświetlenia.</p>';
                        }

                        $content .= $inf_conf['after_day_' . $data_check[1]] ?? '';

                        $content .= '</div>';
        
                        $tab_contents .= $content;
        
                        // **Dynamiczny CSS do przełączania dni w danym `conf_slug`**
                        $dynamic_css .= "
                            #content_conference_cap_tab_{$conf_slug_index}_{$tab_index} {
                                display: none;
                            }
                            #conference_cap_tab_{$conf_slug_index}_{$tab_index}:checked ~ .conference_cap__tabs-contents #content_conference_cap_tab_{$conf_slug_index}_{$tab_index} {
                                display: block;
                            }
                        ";
                    }
                }
        
                // **Struktura zakładek dni**
                $conf_slug_content .= '<div class="conference_cap__tabs">' . $radio_inputs . '<div class="conference_cap__tabs-labels">' . $tab_headers . '</div><div class="conference_cap__tabs-contents">' . $tab_contents . '</div></div>';
                $conf_slug_content .= '</div>';
        
                $conf_slug_tab_contents .= $conf_slug_content;
        
                // **Dynamiczny CSS do przełączania `conf_slug`**
                $dynamic_css .= "
                    #content_conference_cap_conf_tab_{$conf_slug_index} {
                        display: none;
                    }
                    #conference_cap_conf_tab_{$conf_slug_index}:checked ~ .conference_cap__conf-tabs-contents #content_conference_cap_conf_tab_{$conf_slug_index} {
                        display: block;
                    }
                ";
            }
        
            // **Główna struktura HTML**
            $output .= '<div class="conference_cap__conf-tabs">' . $conf_slug_radio_inputs . '<div class="conference_cap__conf-tabs-labels">' . $conf_slug_tab_headers . '</div><div class="conference_cap__conf-tabs-contents">' . $conf_slug_tab_contents . '</div></div>';
            $output .= '<style>' . $dynamic_css . '</style>';
        } else {
            $output .= '<p>Brak danych do wyświetlenia.</p>';
        }
        
    
        $output .= '</div>';
    
        $globalSpeakersData = json_encode($speakersDataMapping);
        $output .= '<script>
            window.speakersData = ' . $globalSpeakersData . ' || {};
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".conference_cap__lecture-speaker-btn").forEach(function(button) {
                    button.addEventListener("click", function(e) {
                        e.preventDefault();
                        var lectureBox = this.closest(".conference_cap__lecture-box");
                        if (!lectureBox) return;
                        var lectureId = lectureBox.getAttribute("id");
                        if (!lectureId || !window.speakersData[lectureId]) return;
                        openSpeakersModal(window.speakersData[lectureId]);
                    });
                });
    
                function openSpeakersModal(speakers) {
                    var overlay = document.createElement("div");
                    overlay.classList.add("custom-modal-overlay");
    
                    var modal = document.createElement("div");
                    modal.classList.add("custom-modal", "visible");
    
                    var modalContent = "";
                    speakers.forEach(function(speaker, index) {
                        modalContent += `<div class="modal-speaker">
                            ${ speaker.url ? `<img src="${speaker.url}" alt="${speaker.name}">` : "" }
                            <h2>${speaker.name}</h2>
                            <p>${speaker.bio}</p>
                        </div>`;
                        if(index < speakers.length - 1) {
                            modalContent += "<hr>";
                        }
                    });
    
                    modal.innerHTML = `<button class="custom-modal-close">&times;</button>
                        <div class="custom-modal-content">${modalContent}</div>`;
                    overlay.appendChild(modal);
                    document.body.appendChild(overlay);
    
                    modal.querySelector(".custom-modal-close").addEventListener("click", function() {
                        document.body.removeChild(overlay);
                    });
    
                    overlay.addEventListener("click", function(e) {
                        if(e.target === overlay) {
                            document.body.removeChild(overlay);
                        }
                    });
                }
            });
        </script>';
    
        return $output;
    }    
    
}
