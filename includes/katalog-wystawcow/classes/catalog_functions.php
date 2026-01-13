<?php

class CatalogFunctions {

    /**
     * Check class for file if exists.
     *
     * @return array
     */
    public static function findClassElements() {
        // Array off class placement
        return array(
            'PWECatalogFull'   => 'classes/catalog_full.php',
            'PWECatalog21'     => 'classes/catalog_21.php',
            'PWECatalog10'     => 'classes/catalog_10.php',
            'PWECatalog7'      => 'classes/catalog_7.php',
            'PWECatalogCombined'   => 'classes/catalog_combined.php',
        );
    }


    /**
     * Get logos for catalog
     *
     * @param string $katalog_id fair id for api.
     * @param string $katalog_format format of display.
     * @return array
     */
    public static function logosChecker($katalog_id, $PWECatalogFull = 'PWECatalogFull', $pwe_catalog_random = false, $file_changer = null, $catalog_display_duplicate = false){

        $basic_wystawcy = [];
        $data = [];

        // Budowanie URL
        $today = new DateTime();
        $formatted_date = $today->format('Y-m-d');
        $token = md5("#22targiexpo22@@@#" . $formatted_date);
        $exh_catalog_address = PWECommonFunctions::get_database_meta_data('exh_catalog_address');
        $can_url = $exh_catalog_address . $token . '&id_targow=' . $katalog_id;

        if (current_user_can('administrator')) {
            if (empty($katalog_id)) {
                echo '<script>console.error("Brak ID katalogu wystawców")</script>';
            }
        }

        // Try local file first
        $local_file = $_SERVER['DOCUMENT_ROOT'] . '/doc/pwe-exhibitors.json';

        if (file_exists($local_file)) {
            $json = file_get_contents($local_file);
            $data = json_decode($json, true);

            if (is_array($data) && isset($data[$katalog_id]['Wystawcy'])) {
                $basic_wystawcy = $data[$katalog_id]['Wystawcy'];

                if (current_user_can('administrator')) {
                    echo '<script>console.log("Dane pobrane z lokalnego pliku (https://'.  $_SERVER['HTTP_HOST'] .'/doc/pwe-exhibitors.json) dla katalogu ' . $katalog_id . '. Link do katalogu expoplanner: '. $can_url .'")</script>';
                };
            }
        }

        // If local missing/invalid → get external JSON
        if (empty($basic_wystawcy) && !empty($katalog_id)) {
            try {
                $json = @file_get_contents($can_url);

                if ($json === false) {
                    throw new Exception('Nie można pobrać danych JSON z zewnętrznego źródła.');
                }

                $data = json_decode($json, true);
                if (!is_array($data)) {
                    throw new Exception('Błąd dekodowania danych JSON.');
                }

                $basic_wystawcy = reset($data)['Wystawcy'] ?? [];

                if (current_user_can('administrator')) {
                    echo '<script>console.log("Dane pobrane z zewnętrznego API '. $can_url .'")</script>';
                }

            } catch (Exception $e) {
                error_log("[" . date('Y-m-d H:i:s') . "] logosChecker błąd: " . $e->getMessage());
                $basic_wystawcy = [];
            }
        }

        $logos_array = array();

        $basic_wystawcy = (!empty($file_changer)) ? self::orderChanger($file_changer, $basic_wystawcy) : $basic_wystawcy;

        if(!empty($basic_wystawcy)) {
            if(!$catalog_display_duplicate){
                $basic_wystawcy = array_reduce($basic_wystawcy, function($acc, $curr) {
                $name = $curr['Nazwa_wystawcy'];
                $existingIndex = array_search($name, array_column($acc, 'Nazwa_wystawcy'));
                if ($existingIndex === false) {
                    $acc[] = $curr;
                } else {
                    if($acc[$existingIndex]["Data_sprzedazy"] !== null && $curr["Data_sprzedazy"] !== null && strtotime($acc[$existingIndex]["Data_sprzedazy"]) < strtotime($curr["Data_sprzedazy"])){
                        $acc[$existingIndex] = $curr;
                    }
                }
                return $acc;
                }, []);
            }
        } else {
            $basic_wystawcy = [];
        }

        switch($PWECatalogFull) {
            case 'PWECatalogFull':
                $logos_array = $basic_wystawcy;
                echo '<script>console.log("exhibitors count -> '.count($logos_array).'")</script>';
                wp_localize_script('pwe-katalog-js', 'katalog_data', $logos_array);
                break;
            case 'PWECatalog21' :
                $i = 0;
                foreach($basic_wystawcy as $wystawca){
                    if($wystawca['URL_logo_wystawcy']){
                        $logos_array[] = $wystawca;
                        $i++;
                        if($i >=21){
                            break;
                        }
                    }
                }
                break;
            case 'PWECatalogCombined' :
                $i = 0;
                foreach($basic_wystawcy as $wystawca){
                    if($wystawca['URL_logo_wystawcy']){
                        $logos_array[] = $wystawca;
                        $i++;
                    }
                }
                break;
            case 'PWECatalog10' :
                $i = 0;
                foreach($basic_wystawcy as $wystawca){
                    if($wystawca['URL_logo_wystawcy']){
                        $logos_array[] = $wystawca;
                        $i++;
                        if($i >=20){
                            break;
                        }
                    }
                }
                break;
            case 'PWECatalog7' :
                $i = 0;
                function compareDates($a, $b) {
                    $dateA = new DateTime($a['Data_sprzedazy']);
                    $dateB = new DateTime($b['Data_sprzedazy']);

                    if ($dateA == $dateB) {
                        return 0;
                    }
                    return ($dateA < $dateB) ? -1 : 1;
                }
                usort($basic_wystawcy, 'compareDates');

                foreach($basic_wystawcy as $wystawca){
                        $logos_array[] = $wystawca;
                        $i++;
                    if($i >=7){
                        break;
                    }
                }
                break;
            default :
                if(!is_numeric($PWECatalogFull)){
                    break;
                }

                $i = 0;
                foreach($basic_wystawcy as $wystawca){
                    if ($wystawca['URL_logo_wystawcy']){
                        $logos_array[] = $wystawca;
                        $i++;
                        if ($i >= $PWECatalogFull) {
                            break;
                        }
                    }
                }

        }
        if($pwe_catalog_random){
            shuffle($logos_array);
        }
        return $logos_array;
    }

    /**
     * Order Changer
     */
    public static function orderChanger($change, $data) {
        $change = html_entity_decode($change, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $change_array = explode(';;', $change);

        foreach($change_array as $single_change){
            if (strpos($single_change, '<=>') !== false) {
                $id = [];
                $names = explode('<=>', $single_change);
                foreach($names as $name){
                $name = trim($name);
                if(is_numeric($name)){
                    $id[] = $name. '.00';
                } else {
                    $found = false;
                    foreach($data as $index => $exhi) {
                        if(stripos($exhi['Nazwa_wystawcy'],  $name) !== false){
                            $id[] = $index;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        echo '<script>console.error("Nie znaleziono wystawcy ' . $name . '")</script>';
                        break 2;
                    }
                }
                }
                if($id[0] && $id[1] && count($data) > $id[0] && count($data) > $id[1]){
                    list($data[$id[0]], $data[$id[1]]) = [$data[$id[1]], $data[$id[0]]];
                } elseif($id[0] && $id[1]) {
                    echo '<script>console.error("Lista zawiera tylko '. count($data) .' wystawców, wystawców, sprawdź poprawność '.$single_change.'")</script>';
                }
            } elseif (strpos($single_change, '=>>') !== false) {
                $id = [];
                $names = explode('=>>', $single_change);
                foreach($names as $name){
                    $name = trim($name);
                    if(is_numeric($name)){
                        $id[] = $name.'.00';
                    } else {
                        $found = false;
                        foreach($data as $index => $exhi){
                            if(stripos($exhi['Nazwa_wystawcy'],  $name) !== false){
                                $id[] = $index;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            echo '<script>console.error("Nie znaleziono wystawcy ' . $name . '")</script>';
                            break 2;
                        }
                    }
                }
                if(is_numeric($id[0]) && is_numeric($id[1]) && count($data) > $id[0] && count($data) > $id[1]){
                    if($id[0]>$id[1]){
                        $temp = $data[$id[1]];
                        $data[$id[1]] = $data[$id[0]];
                        for($i = ($id[1]+1).'.00'; $i<$id[0]; $i= ($i+1).".00"){
                            $temp1 = $data[$i];
                            $data[$i] = $temp;
                            $temp = $temp1;
                        }
                        $data[$id[0]] = $temp;
                    } else {
                        $temp = $data[$id[1]];
                        $data[$id[1]] = $data[$id[0]];
                        for($i = ($id[1]-1).'.00'; $i>$id[0]; $i= ($i-1).'.00'){
                            $temp1 = $data[$i];
                            $data[$i] = $temp;
                            $temp = $temp1;
                        }
                        $data[$id[0]] = $temp;
                    }
                } else {
                echo '<script>console.error("Lista zawiera tylko '. count($data) .' wystawców, sprawdź poprawność '.$single_change.'")</script>';
                }
            }
        }
        return $data;
    }
    public static function multi_translation($key, $plural = false)
    {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../../../translations/includes/katalog-wystawcow.json';

        $translations_data = json_decode(file_get_contents($translations_file), true);

        $translations_map = $translations_data[$locale] ?? $translations_data['en_US'];


        if ($plural === true) {
            if (isset($translations_map['plurals'][$key])) {
                return $translations_map['plurals'][$key];
            }

            return $key;
        }

        return $translations_map[$key] ?? $key;
    }

    /**
     * Check Title for Exhibitors Catalog
     */
    // public static function initElements() {
    // }
    public static function checkTitle($title, $format) {

        if (substr($title, 0, 2) === "``") {
            $exhibitors_title = substr($title, 2, -2);
        } elseif($format == 'PWECatalogFull'){
            $exhibitors_title = self::multi_translation('catalog') . $title;
        } elseif ($format == 'PWECatalog21' || $format == 'PWECatalog10'){
            $exhibitors_title = self::multi_translation('exhibitors') . (($title) ? $title : do_shortcode('[trade_fair_catalog_year]'));
        } elseif ($format == 'PWECatalog7'){
            $exhibitors_title = self::multi_translation('new_exhibitors') . $title;
        }
        return $exhibitors_title;
    }

    /**
     * Custom VC map.
     */
    public static function vcMapPWECatalogCustom() {
        $element_output = array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Logos changer', 'pwe_katalog'),
                'param_name' => 'file_changer',
                'group' => 'Custom Settings',
                'description' => __( 'Changer for logos divided by ";;" try to put names <br> change places "name<=>name or position";<br> move to position "name=>>name or position";', 'pwe_katalog'),
                'save_always' => true,
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Randomise katalog', 'pwe_katalog'),
                'param_name' => 'pwecatalog_display_random1',
                'group' => 'Custom Settings',
                'description' => __('Check if you want to display exhibitors randome.', 'pwe_katalog'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_katalog') => 'true',),
            ),
        );

        return $element_output;
    }

    /**
     * Initialize VC Map PWECatalog.
     */
    public static function initVCMapPWECatalog() {
        $element_output = array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Enter ID', 'pwe_katalog'),
                'param_name' => 'identification',
                'description' => __( 'Enter trade fair ID number.', 'pwe_katalog'),
                'param_holder_class' => 'backend-textfield',
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Enter Archive Year <br>or Title in "..." ', 'pwe_katalog'),
                'param_name' => 'katalog_year',
                'description' => __( 'Enter year for display in catalog title or us "" to change full title.', 'pwe_katalog'),
                'param_holder_class' => 'backend-textfield',
                'save_always' => true,
                'admin_label' => true
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Catalog format', 'pwe_katalog'),
                'param_name' => 'format',
                'description' => __( 'Select catalog format.', 'pwe_katalog'),
                'param_holder_class' => 'backend-textfield',
                'value' => array(
                'Select' => '',
                'Full' => 'PWECatalogFull',
                'Top21' => 'PWECatalog21',
                'Top10' => 'PWECatalog10',
                'Recently7' => 'PWECatalog7',
                'Combined' => 'PWECatalogCombined'
                ),
                'save_always' => true,
                'admin_label' => true
            ),
            // colors setup
            array(
                'type' => 'dropdown',
                'heading' => __('Select text color <a href="#" onclick="yourFunction(`text_color_manual_hidden`, `text_color`)">Hex</a>', 'pwe_katalog'),
                'param_name' => 'text_color',
                'param_holder_class' => 'main-options',
                'description' => __('Select text color for the element.', 'pwe_katalog'),
                'value' => PWECommonFunctions::findPalletColorsStatic(),
                'dependency' => array(
                    'element' => 'text_color_manual_hidden',
                    'value' => array(''),
                    'callback' => "hideEmptyElem",
                ),
                'save_always' => true,
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Write text color <a href="#" onclick="yourFunction(`text_color`, `text_color_manual_hidden`)">Pallet</a>', 'pwe_katalog'),
                'param_name' => 'text_color_manual_hidden',
                'param_holder_class' => 'main-options pwe_dependent-hidden',
                'description' => __('Write hex number for text color for the element.', 'pwe_katalog'),
                'value' => '',
                'save_always' => true,
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Select text shadow color <a href="#" onclick="yourFunction(`text_shadow_color_manual_hidden`, `text_shadow_color`)">Hex</a>', 'pwe_katalog'),
                'param_name' => 'text_shadow_color',
                'param_holder_class' => 'main-options',
                'description' => __('Select shadow text color for the element.', 'pwe_katalog'),
                'value' => PWECommonFunctions::findPalletColorsStatic(),
                'dependency' => array(
                    'element' => 'text_shadow_color_manual_hidden',
                    'value' => array(''),
                ),
                'save_always' => true,
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Write text shadow color <a href="#" onclick="yourFunction(`text_shadow_color`, `text_shadow_color_manual_hidden`)">Pallet</a>', 'pwe_katalog'),
                'param_name' => 'text_shadow_color_manual_hidden',
                'param_holder_class' => 'main-options pwe_dependent-hidden',
                'description' => __('Write hex number for text shadow color for the element.', 'pwe_katalog'),
                'value' => '',
                'save_always' => true,
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Select button color <a href="#" onclick="yourFunction(`btn_color_manual_hidden`, `btn_color`)">Hex</a>', 'pwe_katalog'),
                'param_name' => 'btn_color',
                'param_holder_class' => 'main-options',
                'description' => __('Select button color for the element.', 'pwe_katalog'),
                'value' => PWECommonFunctions::findPalletColorsStatic(),
                'dependency' => array(
                    'element' => 'btn_color_manual_hidden',
                    'value' => array(''),
                ),
                'save_always' => true
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Write button color <a href="#" onclick="yourFunction(`btn_color`, `btn_color_manual_hidden`)">Pallet</a>', 'pwe_katalog'),
                'param_name' => 'btn_color_manual_hidden',
                'param_holder_class' => 'main-options pwe_dependent-hidden',
                'description' => __('Write hex number for button color for the element.', 'pwe_katalog'),
                'value' => '',
                'save_always' => true
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Select button text color <a href="#" onclick="yourFunction(`btn_text_color_manual_hidden`, `btn_text_color`)">Hex</a>', 'pwe_katalog'),
                'param_name' => 'btn_text_color',
                'param_holder_class' => 'main-options',
                'description' => __('Select button text color for the element.', 'pwe_katalog'),
                'value' => PWECommonFunctions::findPalletColorsStatic(),
                'dependency' => array(
                    'element' => 'btn_text_color_manual_hidden',
                    'value' => array(''),
                ),
                'save_always' => true
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Write button text color <a href="#" onclick="yourFunction(`btn_text_color`, `btn_text_color_manual_hidden`)">Pallet</a>', 'pwelement'),
                'param_name' => 'btn_text_color_manual_hidden',
                'param_holder_class' => 'main-options pwe_dependent-hidden',
                'description' => __('Write hex number for button text color for the element.', 'pwelement'),
                'value' => '',
                'save_always' => true
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Select button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color_manual_hidden`, `btn_shadow_color`)">Hex</a>', 'pwelement'),
                'param_name' => 'btn_shadow_color',
                'param_holder_class' => 'main-options',
                'description' => __('Select button shadow color for the element.', 'pwelement'),
                'value' => PWECommonFunctions::findPalletColorsStatic(),
                'dependency' => array(
                    'element' => 'btn_shadow_color_manual_hidden',
                    'value' => array(''),
                ),
                'save_always' => true
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Write button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color`, `btn_shadow_color_manual_hidden`)">Pallet</a>', 'pwelement'),
                'param_name' => 'btn_shadow_color_manual_hidden',
                'param_holder_class' => 'main-options pwe_dependent-hidden',
                'description' => __('Write hex number for button shadow color for the element.', 'pwelement'),
                'value' => '',
                'save_always' => true
            ),
            // color END
            array(
                'type' => 'textfield',
                'heading' => __( 'Export link', 'pwe_katalog'),
                'param_name' => 'export_link',
                'description' => __( 'Export link', 'pwe_katalog'),
                'save_always' => true,
                'admin_label' => true
            ),
            // array(
            //     'type' => 'checkbox',
            //     'heading' => __('Hide details', 'pwe_katalog'),
            //     'param_name' => 'details',
            //     'description' => __('Check to use to hide details. ONLY full catalog.', 'pwe_katalog'),
            //     'param_holder_class' => 'backend-basic-checkbox',
            //     'admin_label' => true,
            //     'value' => array(__('True', 'pwe_katalog') => 'true',),
            // ),
            // array(
            //     'type' => 'checkbox',
            //     'heading' => __('Hide stand', 'pwe_katalog'),
            //     'param_name' => 'stand',
            //     'description' => __('Check to use to hide stand. ONLY full catalog.', 'pwe_katalog'),
            //     'param_holder_class' => 'backend-basic-checkbox',
            //     'admin_label' => true,
            //     'value' => array(__('True', 'pwe_katalog') => 'true',),
            // ),
            array(
                'type' => 'checkbox',
                'heading' => __('Registration', 'pwe_katalog'),
                'param_name' => 'ticket',
                'description' => __('Default height logotypes 110px. ONLY top10.', 'pwe_katalog'),
                'param_holder_class' => 'backend-basic-checkbox',
                'admin_label' => true,
                'value' => array(__('True', 'pwe_katalog') => 'true',),
                'dependency' => array(
                'element' => 'format',
                'value' => array('top10')
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Slider desktop', 'pwe_katalog'),
                'param_name' => 'slider_desktop',
                'description' => __('Check if you want to display in slider on desktop.', 'pwe_katalog'),
                'param_holder_class' => 'backend-basic-checkbox',
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_katalog') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Grid mobile', 'pwe_katalog'),
                'param_name' => 'grid_mobile',
                'description' => __('Check if you want to display in grid on mobile.', 'pwe_katalog'),
                'param_holder_class' => 'backend-basic-checkbox',
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_katalog') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Turn off dots', 'pwe_katalog'),
                'param_name' => 'slider_dots_off',
                'description' => __('Check if you want to turn off dots.', 'pwe_katalog'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_katalog') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Show duplicate exhibitor', 'pwe_katalog'),
                'param_name' => 'catalog_display_duplicate',
                'description' => __('Check if you want to show exhibitors duplicate.', 'pwe_katalog'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_katalog') => 'true',),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Items shadow', 'pwe_logotypes'),
                'param_name' => 'catalog_items_shadow',
                'description' => __('2px 2px 12px #cccccc', 'pwe_logotypes'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_logotypes') => 'true',),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom css of items', 'pwe_logotypes'),
                'param_name' => 'catalog_items_custom_style',
                'save_always' => true,
            ),
        );
        return $element_output;
    }
}