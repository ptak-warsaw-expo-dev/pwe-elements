<?php

/**
 * Class PWEConferenceCap
 */
class PWEConferenceCap {

    public static $exhibitor_logo_url;
    public static $exhibitor_name;
    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;
    public static $fair_forms;
    private $atts;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {


        add_action('init', array($this, 'initElements'));
        add_shortcode('pwe_conference_cap', array($this, 'PWEConferenceCapOutput'));

        // Hook actions
        add_action('wp_enqueue_scripts', array($this, 'addingStyles'));

    }


    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public function initElements() {
        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __( 'PWE Conference CAP', 'pwe_exhibitor_generator'),
                'base' => 'pwe_conference_cap',
                'category' => __( 'PWE Elements', 'pwe_exhibitor_generator'),
                'admin_enqueue_css' => plugin_dir_url(dirname(__DIR__)) . 'backend/backendstyle.css',
                'class' => 'costam',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Display Slug', 'pwe_conference_cap'),
                        'param_name' => 'conference_cap_conference_display_slug',
                        'description' => __('Specify conference slug to display '),
                        'save_always' => true,
                        'admin_label' => true,
                        'edit_field_class' => 'vc_col-sm-12 no-vc-param-name',
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Display domains', 'pwe_conference_cap'),
                        'param_name' => 'conference_cap_domains',
                        'description' => __('Podaj listę domen oddzielonych przecinkiem, np.: warsawexpo.eu,mr.glasstec.pl. Tylko konferencje przypisane do tych domen będą widoczne.', 'pwe_conference_cap'),
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Arichive', 'pwe_conference_cap'),
                        'param_name' => 'conference_cap_conference_arichive',
                        'description' => __('Type [ year ] of the conference to display or type [ all ] to display everything except the current one.', 'pwe_conference_cap'),
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select conference mode', 'pwe_conference_cap'),
                        'param_name' => 'conference_cap_conference_mode',
                        'save_always' => true,
                        'value' => array(
                            'Mode' => '',
                            'Full Mode' => 'PWEConferenceCapFullMode',
                            'Full Mode Speakers' => 'PWEConferenceCapFullMode2',
                            'Simple Mode' => 'PWEConferenceCapSimpleMode',
                            'Medal Ceremony' => 'PWEConferenceCapMedalCeremony',
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'PWE Element',
                        'heading' => __('One Conference Mode', 'pwe_conference_cap'),
                        'param_name' => 'conference_cap_one_conference_mode',
                        'description' => __('Mode disables the top navigation and sets the first conference visible', 'pwe_conference_cap'),
                        'admin_label' => true,
                        'value' => array(__('True', 'pwe_conference_cap') => 'true',),
                        'dependency' => array(
                            'element' => 'display_one_conference_mode',
                            'value' => 'PWECapOneConference',
                        ),
                    ),
                    array(
                        'type' => 'param_group',
                        'group' => 'Custom Html',
                        'heading' => __('Custom Html', 'pwe_conference_cap'),
                        'param_name' => 'conference_cap_html',
                        'dependency' => array(
                            'element' => 'pwe_conference_cap',
                            'value' => 'PWEConferenceCap',
                        ),
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => __('Conference slug', 'pwe_conference_cap'),
                                'param_name' => 'conference_cap_html_conf_slug',
                                'save_always' => true,
                                'admin_label' => true,
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => __('Position', 'pwe_conference_cap'),
                                'param_name' => 'conference_cap_html_position',
                                'value' => array(
                                    __('After header', 'pwe_conference_cap') => 'after_header',
                                    __('After patrons', 'pwe_conference_cap') => 'after_patrons',
                                    __('After location', 'pwe_conference_cap') => 'after_location',
                                    __('After title', 'pwe_conference_cap') => 'after_title',
                                    __('Before day', 'pwe_conference_cap') => 'before_day',
                                    __('After day', 'pwe_conference_cap') => 'after_day',
                                    __('After all', 'pwe_conference_cap') => 'after_all',
                                ),
                                'description' => __('Choose where to insert the custom HTML.', 'pwe_conference_cap'),
                                'save_always' => true,
                                'admin_label' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Conference day', 'pwe_conference_cap'),
                                'param_name' => 'conference_cap_html_day',
                                'save_always' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Element ID', 'pwe_conference_cap'),
                                'param_name' => 'conference_cap_html_element_id',
                                'description' => __('Enter the ID of an existing item on the page to be moved.', 'pwe_conference_cap'),
                                'save_always' => true,
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => __('Custom html', 'pwe_conference_cap'),
                                'param_name' => 'conference_cap_html_code',
                                'save_always' => true,
                            ),
                        ),
                    ),
                    array(
                        'type' => 'param_group',
                        'group' => 'Manual Conferences',
                        'heading' => __('Manually Added Conferences', 'pwe_conference_cap'),
                        'param_name' => 'manual_conferences',
                        'description' => __('Add the class .konferencja and id conference_ID to the element.', 'pwe_conference_cap'),
                        'params' => array(
                            array(
                                'type' => 'attach_image',
                                'heading' => __('Conference Image', 'pwe_conference_cap'),
                                'param_name' => 'manual_conf_img',
                                'save_always' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Conference ID (Slug)', 'pwe_conference_cap'),
                                'param_name' => 'manual_conf_id',
                                'description' => __('Enter the conference slug (this should match the ID of the content section you want to toggle).', 'pwe_conference_cap'),
                                'save_always' => true,
                                'admin_label' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Conference url', 'pwe_conference_cap'),
                                'param_name' => 'manual_conf_url',
                                'description' => __('Enter the conference url (https opens in new window, / opens in same ).', 'pwe_conference_cap'),
                                'save_always' => true,
                                'admin_label' => true,
                            ),
                        ),
                    ),

                )
            ));
        }
    }


    public function addingStyles(){
        $css_file = plugins_url('assets/conference-cap-style.css', __FILE__);
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/conference-cap-style.css');
        wp_enqueue_style('pwe-conference-cap-css', $css_file, array(), $css_version);
    }

        /**
     * Adding Scripts
     */
    public static function addingScripts($atts , $speakersDataMapping, $one_conf_mode = false, $archive = '') {
        $data = array(
            'data'   => $speakersDataMapping,
            'oneConfMode' => $one_conf_mode,
            'archive' => $archive,
        );

        $js_file = plugins_url('assets/conference-cap-script.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/conference-cap-script.js');
        wp_enqueue_script('pwe-conference-cap-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script('pwe-conference-cap-js', 'confCapData', $data);

    }



    public static function PWEConferenceCapOutput($atts) {

        require_once plugin_dir_path(__FILE__) . 'classes/conference-cap-functions.php';
        $conf_function = new PWEConferenceCapFunctions;

        // Pobieramy ustawienia shortcode
        extract(shortcode_atts(array(
            'conference_cap_html' => '',
            'conference_cap_conference_mode' => '',
            'conference_cap_conference_arichive' =>'',
            'conference_cap_domains',
        ), $atts));

        $lang = 'PL';
        if (strpos($_SERVER['REQUEST_URI'], '/en/') !== false) {
            $lang = 'EN';
        }

        if (strpos($_SERVER['HTTP_HOST'], 'warsawexpo.eu') !== false) {
            require_once plugin_dir_path(__FILE__) . 'classes/conference-cap-warsawexpo.php';
            return PWEConferenceCapWarsawExpo::output($atts, $lang, $conf_function);
        }

        $database_data = PWECommonFunctions::get_database_conferences_data();
        // PWEConferenceCapFunctions::debugConferencesConsole( $database_data ); // checking recent changes

        $one_conf_mode = isset($atts['conference_cap_one_conference_mode']) && $atts['conference_cap_one_conference_mode'] === 'true';
        $conference_cap_html = vc_param_group_parse_atts( $conference_cap_html );

        $global_inf_conf = [];
        if (!empty($conference_cap_html)) {
            foreach ($conference_cap_html as $conf_cap_html) {
                // Pobieramy docelowy conference slug
                $target_conf_slug = $conf_cap_html['conference_cap_html_conf_slug'];
                if (empty($target_conf_slug)) {
                    continue;
                }
                // Budujemy klucz w zależności od pozycji
                if ($conf_cap_html['conference_cap_html_position'] === 'after_header' ||
                    $conf_cap_html['conference_cap_html_position'] === 'after_location' ||
                    $conf_cap_html['conference_cap_html_position'] === 'after_title' ||
                    $conf_cap_html['conference_cap_html_position'] === 'after_patrons' ||
                    $conf_cap_html['conference_cap_html_position'] === 'after_all') {
                    $key = $conf_cap_html['conference_cap_html_position'] . '_' . $target_conf_slug;
                } else {
                    $key = $conf_cap_html['conference_cap_html_position'] .
                        (!empty($conf_cap_html['conference_cap_html_day']) ? '_' . $conf_cap_html['conference_cap_html_day'] : '');
                }

                // Jeśli klucz już istnieje, dołączamy nową zawartość
                $html_content = '';

                if (!empty($conf_cap_html['conference_cap_html_code'])) {
                    $html_content = $conf_cap_html['conference_cap_html_code'];
                } elseif (!empty($conf_cap_html['conference_cap_html_element_id'])) {
                    $element_id = esc_attr(trim($conf_cap_html['conference_cap_html_element_id']));
                    $html_content = '<div data-html-inject-id="' . $element_id . '"></div>';
                }

                if (!empty($html_content)) {
                    if (isset($global_inf_conf[$target_conf_slug][$key])) {
                        $global_inf_conf[$target_conf_slug][$key] .= '<br>' . $html_content;
                    } else {
                        $global_inf_conf[$target_conf_slug][$key] = $html_content;
                    }
                }
            }
        }

        // Wczytanie i przygotowanie manualnych konferencji
        $manual_conferences = [];
        if (!empty($atts['manual_conferences'])) {
            $manual_conferences = vc_param_group_parse_atts($atts['manual_conferences']);
        }

        // Poprawna obsługa manualnych konferencji (żeby nie zostały nadpisane błędnie)
        $has_valid_manual_conf = !empty(array_filter($manual_conferences, function($conf) {
            return !empty($conf['manual_conf_img']) && !empty($conf['manual_conf_id']);
        }));

        $new_manual_conferences = [];
        $manual_slugs_to_hide = [];

        if (!empty($manual_conferences)) {
            foreach ($manual_conferences as $manual_conf) {
                $slug = $manual_conf['manual_conf_id'] ?? '';
                $url  = $manual_conf['manual_conf_url'] ?? '';

                if (!empty($slug)) {
                    if (!empty($url)) {
                        $manual_slugs_to_hide[] = $slug;
                        $new_manual_conferences[] = $manual_conf;
                    } else {
                        $new_manual_conferences[] = $manual_conf;
                    }
                }
            }
        }

        // Na tym etapie `$manual_conferences` jest poprawne i nie zostanie później błędnie nadpisane
        $manual_conferences = $new_manual_conferences;


        // Tablica na zapis danych prelegentów (bio) – identyfikator lecture-box => dane
        $speakersDataMapping = array();

        $output = '';

        // echo '<pre  style="width: 1200px;">';
        // var_dump($database_data);
        // echo '</pre>';

        $no_conference = true;

        $normalTiles = [];
        $specialTiles = [];

        $cap_database = true;
        // **Górna nawigacja konferencji (tylko obrazki z ID)**
        $output .= '<div id="conference-cap" class="conference_cap__main-container">';

            if (!empty($database_data)) {
                $byYear = [];
                foreach ($database_data as $conf) {

                    if (preg_match('/(20\d{2})(?!\d)/', $conf->conf_slug, $m)) {
                        $year = (int) $m[1];

                        $fullConfData = json_decode($conf->conf_data, true);
                        if ($fullConfData === null) {
                            // echo '<script>console.warn("'.$conf->conf_slug.' - conf_data = null")</script>';
                            $byYear[$year][$conf->conf_slug][] = [];
                            continue;
                        }
                        $confData = PWEConferenceCapFunctions::copySpeakerImgByStructure($fullConfData)[$lang] ?? [];

                        $byYear[$year][$conf->conf_slug][] = $confData;
                    }
                }

                $currentYear  = (int) date('Y', strtotime(do_shortcode('[trade_fair_enddata]')));
                if ($currentYear < 2000) $currentYear = do_shortcode('[trade_fair_catalog_year]');
                $previousYear = $currentYear - 1;

                $archiveYear = trim($atts['conference_cap_conference_arichive'] ?? '');
                if ($archiveYear !== '' && $archiveYear !== 'all') {
                    $yearToShow = (int) $archiveYear;
                } else {
                    $yearToShow = isset($byYear[$currentYear])
                        ? $currentYear
                        : $previousYear;
                }

                $conf_slugs = $byYear[$yearToShow] ?? [];

                if (($atts['conference_cap_conference_arichive'] ?? '') === 'all') {
                    $conf_slugs = [];
                    foreach ($byYear as $year => $slugs) {
                        if ( $year === $currentYear ) {
                            continue;
                        }
                        $conf_slugs += $slugs;
                    }
                }

                $domain = $_SERVER['HTTP_HOST']; // np. mr.glasstec.pl2

                uksort($conf_slugs, function($a_slug, $b_slug) use ($database_data, $domain) {

                    $getOrder = function($slug, $domain, $database_data) {

                        foreach ($database_data as $conf) {

                            if ($conf->conf_slug !== $slug) {
                                continue;
                            }

                            $links = $conf->conf_site_link;

                            $pattern = '/\b' . preg_quote($domain, '/') . '\b\s*\[(\d+)\]/';

                            if (preg_match($pattern, $links, $m)) {
                                return intval($m[1]);
                            }

                            return 999;
                        }

                        return 999;
                    };

                    return $getOrder($a_slug, $domain, $database_data) <=> $getOrder($b_slug, $domain, $database_data);
                });

                // Przetwarzanie 'conference_cap_conference_display_slug'
                $display_slugs_raw = $atts['conference_cap_conference_display_slug'] ?? '';
                if (!empty($display_slugs_raw)) {
                    $display_slugs = array_map('trim', explode(',', $display_slugs_raw));
                    $filtered_conf_slugs = [];
                    foreach ($display_slugs as $slug) {
                        if (isset($conf_slugs[$slug])) {
                            $filtered_conf_slugs[$slug] = $conf_slugs[$slug];
                        }
                    }
                    $conf_slugs = $filtered_conf_slugs;
                }

            } else {
                $cap_database = false;
            }
            if (!$one_conf_mode) {
                // Generujemy nawigację (kafelki)
                $output .= '<div class="conference_cap__conf-slug-navigation">';

                        // 2. Manualne konferencje (dane pobrane z VC)
                        $has_valid_manual_conf = !empty(array_filter($manual_conferences, function($conf) {
                            return !empty($conf['manual_conf_img']) && !empty($conf['manual_conf_id']);
                        }));

                        // TERAZ dopiero przygotowujemy manuale oraz slugi do ukrycia
                        $new_manual_conferences = [];
                        $manual_slugs_to_hide = [];

                        if (!empty($manual_conferences)) {
                            foreach ($manual_conferences as $manual_conf) {
                                $slug = $manual_conf['manual_conf_id'] ?? '';
                                $url  = $manual_conf['manual_conf_url'] ?? '';

                                if (!empty($slug)) {
                                    if (!empty($url)) {
                                        // Manual ma URL → wygrywa manual
                                        $manual_slugs_to_hide[] = $slug;
                                        $new_manual_conferences[] = $manual_conf;
                                    } else {
                                        // Manual nie ma URL
                                        if (isset($conf_slugs[$slug])) {
                                            // Slug istnieje w bazie → POMIJAMY manual
                                            continue;
                                        } else {
                                            // Slug nie istnieje w bazie → dodajemy manual
                                            $new_manual_conferences[] = $manual_conf;
                                        }
                                    }
                                }
                            }
                        }


                        // Teraz USUWAMY z bazy konferencje, które mają taki sam slug jak manuale z URL
                        foreach ($manual_slugs_to_hide as $slug_to_hide) {
                            if (isset($conf_slugs[$slug_to_hide])) {
                                unset($conf_slugs[$slug_to_hide]);
                            }
                        }


                        foreach ($conf_slugs as $conf_slug => $conferences) {
                            $conf_img = '';
                            foreach ($database_data as $conf) {
                                if ($conf->conf_slug === $conf_slug) {
                                    $lang_key = strtolower($lang);
                                    $img_field = 'conf_img_' . $lang_key;

                                    if (!empty($conf->$img_field)) {
                                        $conf_img = esc_url($conf->$img_field);
                                    } elseif (!empty($conf->conf_img_pl)) {
                                        // fallback na PL, jeśli brak EN
                                        $conf_img = esc_url($conf->conf_img_pl);
                                    }

                                    break;
                                }

                            }
                            if (empty($conf_img)) {
                                continue;
                            }


                            // Tworzymy HTML kafelka
                            $tile = '<img src="' . $conf_img . '" alt="' . esc_attr($conf_slug) . '" id="nav_' . esc_attr($conf_slug) . '" class="conference_cap__conf-slug-img">';

                            // Jeśli slug należy do specjalnych, dodajemy do specjalnych, w przeciwnym razie do normalnych
                            if (strpos($conf_slug, 'medal') !== false) {
                                $specialTiles['medal'][] = $tile;
                            } elseif (strpos($conf_slug, 'panel') !== false) {
                                $specialTiles['panel'][] = $tile;
                            } else {
                                $normalTiles[] = $tile;
                            }
                        }

                        // 1. Normalne konferencje z bazy
                        foreach ($normalTiles as $tile) {
                            $output .= $tile;
                        }

                    $manual_conferences = $new_manual_conferences;

                    if ($has_valid_manual_conf) {
                        $output .= '
                        <style>
                            .post-body .konferencja {
                                display: none;
                            }
                        </style>';

                        foreach ($manual_conferences as $manual_conf) {
                            $manual_img = !empty($manual_conf['manual_conf_img']) ? wp_get_attachment_url($manual_conf['manual_conf_img']) : '';
                            $manual_slug = $manual_conf['manual_conf_id'] ?? '';
                            $manual_url  = $manual_conf['manual_conf_url'] ?? '';

                            if (!empty($manual_img) && !empty($manual_slug)) {
                                $link_start = '';
                                $link_end = '';

                                if (!empty($manual_url)) {
                                    if (strpos($manual_url, 'https://') === 0 || strpos($manual_url, 'http://') === 0) {
                                        $link_start = '<a href="' . esc_url($manual_url) . '" target="_blank">';
                                        $link_end = '</a>';
                                    } elseif (strpos($manual_url, '/') === 0) {
                                        $link_start = '<a href="' . esc_url($manual_url) . '">';
                                        $link_end = '</a>';
                                    }
                                }

                                $output .= $link_start .
                                    '<img src="' . esc_url($manual_img) . '" alt="' . esc_attr($manual_slug) . '" id="nav_' . esc_attr($manual_slug) . '" class="conference_cap__conf-slug-img manual-conference">' .
                                    $link_end;
                            }
                        }
                    }

                    if (!empty($specialTiles)) {
                        // 3. Na końcu kafelki specjalne – w kolejności: "medal" i "panel"
                        if (!empty($specialTiles['medal'])) {
                            foreach ($specialTiles['medal'] as $tile) {
                                $output .= $tile;
                            }
                        }
                        if (!empty($specialTiles['panel'])) {
                            foreach ($specialTiles['panel'] as $tile) {
                                $output .= $tile;
                            }
                        }
                    }

                    if (empty($normalTiles) && empty($specialTiles) && empty($manual_conferences)) {
                        $no_conference = true;
                    } else {
                        $no_conference = false;
                    }

                    if($no_conference) {
                        $output .= '
                        <h2 class="conference_cap__conf-slug-title">'. PWECommonFunctions::languageChecker('Harmonogram zostanie udostępniony wkrótce', 'The schedule will be made available soon') .'</h2>';
                    }

                $output .= '</div>'; // Zamknięcie `conf-tabs`

                $tileCount = count($normalTiles) + count($specialTiles['medal'] ?? []) + count($specialTiles['panel'] ?? []) + count($manual_conferences);
                if ($tileCount > 0 && $tileCount <= 3) {
                    $output .= '<div class="conference_cap__more-coming-soon">'. PWECommonFunctions::languageChecker('Więcej wydarzeń pojawi się wkrótce', 'More events coming soon') .'</div>';
                }
            }


            // **Główna struktura HTML**
            if ($cap_database || !empty($manual_conferences)) {
            $output .= '<div class="conference_cap__conf-slugs-container">';

                foreach ($conf_slugs as $conf_slug => $conferences) {
                    // Pobranie danych konferencji
                    $conf_img = '';
                    $conf_name = '';
                    $conf_location = '';
                    foreach ($database_data as $conf) {
                        if ($conf->conf_slug === $conf_slug) {
                            $lang_key = strtolower($lang);
                            $img_field = 'conf_img_' . $lang_key;
                            $conf_img = !empty($conf->$img_field) ? esc_url($conf->$img_field) : (!empty($conf->conf_img_pl) ? esc_url($conf->conf_img_pl) : '');
                            if ($lang === 'EN') {
                                $conf_name = !empty($conf->conf_name_en) ? str_replace(';;', '<br>', esc_html($conf->conf_name_en)) : '';
                                $conf_location = !empty($conf->conf_location_en) ? str_replace(';;', '<br>', esc_html($conf->conf_location_en)) : '';
                            } else {
                                $conf_name = !empty($conf->conf_name_pl) ? str_replace(';;', '<br>', esc_html($conf->conf_name_pl)) : '';
                                $conf_location = !empty($conf->conf_location_pl) ? str_replace(';;', '<br>', esc_html($conf->conf_location_pl)) : '';
                            }
                            $conf_style = !empty($conf->conf_style) ? $conf->conf_style : 'PWEConferenceCapFullMode';
                            break;
                        }
                    }

                    $panel = ($conf_style === 'PWEConferenceCapPanelTrendow');

                    $inf_conf = isset($global_inf_conf[$conf_slug]) ? $global_inf_conf[$conf_slug] : [];

                    $conf_mode = $conference_cap_conference_mode;
                    $prelegent_show = true;

                    if ($conf_mode === 'PWEConferenceCapFullMode2') {
                        $conf_mode = 'PWEConferenceCapFullMode';
                        $prelegent_show = false;
                    }

                    if (empty($conf_mode)) {
                        $conf_mode = $conf_style ?? 'PWEConferenceCapFullMode';
                    }

                    $conference_modes = PWEConferenceCapFunctions::findConferenceMode($conf_mode);
                    $new_class = $conf_mode;


                    require_once plugin_dir_path(__FILE__) . $conference_modes['php'];
                    $mode_class = new $new_class;

                    $css_handle = 'conference-style-' . sanitize_title($conf_style);

                    // Załaduj CSS
                    $css_file = plugins_url($conference_modes['css'], __FILE__);
                    if (file_exists(plugin_dir_path(__FILE__) . $conference_modes['css'])) {
                        $css_version = filemtime(plugin_dir_path(__FILE__) . $conference_modes['css']);
                        wp_enqueue_style($css_handle, $css_file, array(), $css_version);
                    }

                    // **Kontener dla danej konferencji**
                    $output .= '<div id="conf_' . esc_attr($conf_slug) . '" class="conference_cap__conf-slug">';
                        // **Nagłówek konferencji**
                        $output .= '<div class="conference_cap__conf-slug-header">';
                            if (!empty($conf_img) && (get_class($mode_class) !== 'PWEConferenceCapMedalCeremony')) {
                                $output .= '
                                <img src="' . $conf_img . '" alt="' . esc_attr($conf_name) . '" class="conference_cap__conf-slug-image">
                                <div class="conference_cap__after-header-html">' . ($inf_conf['after_header_' . $conf_slug] ?? '') . '</div>';

                                // Organizator konferencji (OLD)
                                $organizer = PWEConferenceCapFunctions::getConferenceOrganizer((int)$conf->id, $conf->conf_slug, $lang);
                                // Organizatorzy konferencji (NEW)
                                $organizers_all = PWEConferenceCapFunctions::getConferenceOrganizersAll($conf->conf_slug);

                                if (!empty($organizers_all)) {
                                    $output .= '
                                    <div class="conference_cap__conf-organizer-wrapper">
                                        <h2 class="conference_cap__conf-organizer-title">';
                                            if (count($organizers_all) > 1) {
                                                $output .= PWECommonFunctions::languageChecker('Organizatorzy Konferencji', 'Conference Organizers');
                                            } else {
                                                $output .= PWECommonFunctions::languageChecker('Organizator Konferencji', 'Conference Organizer');
                                            }
                                            $output .= '
                                        </h2>
                                        <div class="conference_cap__conf-organizer-logotypes">';
                                            foreach ($organizers_all as $organizer) {
                                                if (!empty($organizer['src'])) {
                                                    $org_name_pl = !empty($organizer['data']['orgNamePl']) ? esc_html($organizer['data']['orgNamePl']) : '';
                                                    $org_name_en = !empty($organizer['data']['orgNameEn']) ? esc_html($organizer['data']['orgNameEn']) : '';
                                                    $org_name = ($lang == 'PL') ? $org_name_pl : (!empty($org_name_en) ? $org_name_en : $org_name_pl);
                                                    $link = !empty($organizer['data']['link']) ? esc_html($organizer['data']['link']) : '';
                                                    $order = !empty($organizer['data']['order']) ? esc_html($organizer['data']['order']) : '';
                                                    if (!empty($link)) {
                                                        $output .= '
                                                        <a href="'. $link .'" target="_blank">
                                                            <div class="conference_cap__conf-organizer-logo">
                                                                <img src="' . esc_url($organizer['src']) . '" alt="' . $org_name . '" class="conference_cap__conf-org-logo">
                                                            </div>
                                                        </a>';
                                                    } else {
                                                        $output .= '
                                                        <div class="conference_cap__conf-organizer-logo">
                                                            <img src="' . esc_url($organizer['src']) . '" alt="' . $org_name . '" class="conference_cap__conf-org-logo">
                                                        </div>';
                                                    }
                                                }
                                            }
                                            $output .= '
                                        </div>
                                    </div>';
                                } else if ($organizer && !empty($organizer['logo_url'])) {
                                    $output .= '
                                    <div class="conference_cap__conf-organizer-wrapper old">
                                        <h2 class="conference_cap__conf-organizer-title">' .
                                            PWECommonFunctions::languageChecker('Organizator Konferencji', 'Conference Organizer') .
                                        '</h2>
                                        <div class="conference_cap__conf-organizer-logo">';
                                            $output .= '
                                            <img src="' . esc_url($organizer['logo_url']) . '" alt="' . esc_attr($organizer['desc']) . '" class="conference_cap__conf-org-logo">
                                            <span class="conference_cap__conf-organizer-logo-title">' . esc_html($organizer['desc']) . '</span>
                                        </div>
                                    </div>';
                                }

                                if (!empty($conf->conf_patrons_img)) {
                                    $logo_files = explode(',', $conf->conf_patrons_img);
                                    if (!empty($logo_files)) {
                                        include_once plugin_dir_path(__FILE__) . '/../../scripts/slider.php';
                                        $output .= PWESliderScripts::sliderScripts(
                                            'capconf',
                                            '#conf_' . esc_attr($conf_slug),
                                            $opinions_dots_display = 'true',
                                            $opinions_arrows_display = false,
                                            $slides_to_show = 7
                                        );

                                        $output .= '
                                        <h2 class="conference_cap__conf-logotypes-title">' .
                                            PWECommonFunctions::languageChecker(
                                                $conf->conf_patrons_pl ?? 'Patroni Konferencji',
                                                $conf->conf_patrons_en ?? 'Conference Patrons'
                                            ) .
                                        '</h2>
                                        <div class="conference_patroni_logos pwe-slides">';

                                        $output .= PWEConferenceCapFunctions::getConferencePatronLogosFromList($conf->id, $conf->conf_slug, $logo_files);

                                        $output .= '</div>';
                                    }
                                }

                                $output .= '
                                <div class="conference_cap__after-patrons-html">' . ($inf_conf['after_patrons_' . $conf_slug] ?? '') . '</div>
                                <h2 class="conference_cap__conf-slug-location">' . $conf_location . '</h2>
                                <div class="conference_cap__after-location-html">' . ($inf_conf['after_location_' . $conf_slug] ?? '') . '</div>
                                <h2 class="conference_cap__conf-slug-title">' . $conf_name . '</h2>
                                <div class="conference_cap__after-title-html">' . ($inf_conf['after_title_' . $conf_slug] ?? '') . '</div>';
                            }else if(get_class($mode_class) == 'PWEConferenceCapMedalCeremony'){
                                foreach ($conferences as $confData) {
                                    foreach ($confData as $day => $sessions) {
                                        if ( $day === 'main-desc' ) {
                                            continue;
                                        }
                                        $day = str_replace(';;', '<br>', wp_kses_post($day));
                                        $output .= $mode_class::output($atts, $sessions, $conf_function, $conf_name, $day, $conf_slug, $conf_location);
                                        break 2;
                                    }
                                }
                            }else {
                                $output .= '
                                <div class="conference_cap__conf-slug-default-header" style="background-image: url(/wp-content/plugins/pwe-media/media/conference-background.webp)">
                                        <div class="conference_cap__conf-slug-default-content">
                                            <h4 class="conference_cap__conf-slug-default-title">' . $conf_name . '</h4>
                                        </div>
                                </div>';
                            }
                            foreach ($conferences as $confDataSet) {
                                if (!empty($confDataSet['main-desc'])) {
                                    $allowed_tags = wp_kses_allowed_html('post');
                                    foreach (['span', 'strong', 'p', 'em', 'u'] as $tag) {
                                        $allowed_tags[$tag]['style'] = true;
                                    }

                                    $main_desc_clean = html_entity_decode(stripslashes($confDataSet['main-desc']));
                                    $main_desc_clean = PWEConferenceCapFunctions::pwe_convert_rgb_to_hex($main_desc_clean);

                                    $output .= `<div class='conference_cap__conf-main-desc'>` . wp_kses($main_desc_clean, $allowed_tags) . `</div>`;
                                }
                                break;
                            }
                            $output .= '
                                <div class="conference_cap__conf-button">
                                    <a href="'. PWECommonFunctions::languageChecker('/rejestracja/', '/en/registration/') .'">'. PWECommonFunctions::languageChecker('Odbierz darmowy bilet', 'Receive a free ticket') .'</a>
                                </div>';

                        $output .= '</div>'; // Zamknięcie nagłówka

                        // TYMCZASOWE ROZWIĄZANIE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        if ($_SERVER['HTTP_HOST'] === "beautydays.pl" && ($conf_name === "Warsztaty makijażu z Magdaleną Pieczonką" || $conf_name === "Makeup Masterclass with Magdalena Pieczonka")) {
                            $output .= '
                            <div id="pweButton-'. $conf_slug .'" class="pwe-button" style="display: flex; justify-content: center; margin: 18px auto;">
                                <a class="pwe-button-link btn" style="background: #176a7c; color: white; border-radius: 8px;" href="'. (($lang === "PL") ? '/rejestracja/' : '/en/registration/') .'">'. (($lang === "PL") ? 'Odbierz zaproszenie' : 'Receive your invitation') .'</a>
                            </div>';
                        }
                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                        if (get_class($mode_class) !== 'PWEConferenceCapMedalCeremony') {
                            // **Zakładki dni**
                            // Sprawdź, czy są jakiekolwiek sesje do pokazania
                            $has_sessions = false;
                            foreach ($conferences as $confData) {
                                foreach ($confData as $day => $sessions) {
                                    if ($day === 'main-desc') continue;

                                    foreach ($sessions as $session_id => $session_data) {
                                        if (strpos($session_id, 'pre-') === 0 && !empty($session_data['title'])) {
                                            $has_sessions = true;
                                            break 3;
                                        }
                                    }
                                }
                            }
                            if ($has_sessions) {
                                $output .= '<div class="conference_cap__conf-slug-navigation-days">';
                                foreach ($conferences as $confData) {
                                    $dayCounter = 1;
                                    foreach ($confData as $day => $sessions) {
                                            if ($day === 'main-desc') {
                                                continue; // pomijamy main-desc w pętli dni
                                            }
                                            $short_day = 'day-' . $dayCounter++;
                                            $day = str_replace(';;', '<br>', wp_kses_post($day));
                                            $output .= '<button id="tab_' . esc_attr($conf_slug) . '_' . esc_attr($short_day) . '" class="conference_cap__conf-slug-navigation-day">' . $day . '</button>';
                                        }
                                    }
                                $output .= '</div>'; // Zamknięcie kontenera zakładek dni
                            }

                            // **Treść dla poszczególnych dni**
                            $all_day_speakers = [];

                            $output .= '<div class="conference_cap__conf-slug-contents">';
                                foreach ($conferences as $confData) {
                                    $dayCounter = 1;
                                    foreach ($confData as $day => $sessions) {
                                        if ($day === 'main-desc') {
                                            continue;
                                        }
                                        $short_day = 'day-' . $dayCounter++;
                                        $output .= '
                                        <div id="content_' . esc_attr($conf_slug) . '_' . esc_attr($short_day) . '" class="conference_cap__conf-slug-content">
                                            <div class="conference_cap__before-day-html">' . ($inf_conf['before_day_' . esc_attr($conf_slug) . '_' . esc_attr($short_day)] ?? '') . '</div>
                                                '. $mode_class::output($atts, $sessions, $conf_function, $speakersDataMapping, $all_day_speakers, $short_day, $conf_slug, $panel, $conf_location, $prelegent_show) .'
                                            <div class="conference_cap__after-day-html">' . ($inf_conf['after_day_' . esc_attr($conf_slug) . '_' . esc_attr($short_day)] ?? '') . '</div>';
                                        $output .= '</div>'; // Zamknięcie kontenera treści dnia
                                    }
                                    if ($prelegent_show == false) {
                                        $output .= '<div class="conference_cap__lecture-speaker-flex">';

                                        foreach ($all_day_speakers as $index => $speaker) {
                                            $lectureId = 'global_' . $index;
                                            $has_image = !empty($speaker['url']);
                                            $has_name  = !empty($speaker['name_html']);
                                            $has_bio   = !empty($speaker['desc']);

                                            // Pomijamy tych bez bio i bez zdjęcia
                                            if (!$has_image && !$has_bio) {
                                                continue;
                                            }

                                            // Pomijamy duplikaty (po nazwie i firmie)
                                            $speakerKey = md5(strip_tags($speaker['name_html']));
                                            if (isset($seen_speakers[$speakerKey])) {
                                                continue;
                                            }
                                            $seen_speakers[$speakerKey] = true;

                                            $output .= '<div class="conference_cap__lecture-speaker-item">';
                                            $output .= '<div class="conference_cap__lecture-speaker-photo-name">';
                                            $output .= '<div class="conference_cap__lecture-speaker-img">';
                                            $output .= '<img src="' . esc_url($speaker['url']) . '" alt="" class="conference_cap__lecture-speaker-img-item" />';
                                            $output .= '</div>';

                                            if ($has_name) {
                                                $output .= '<h5 class="conference_cap__lecture-name">' . $speaker['name_html'] . '</h5>';
                                            }

                                            $output .= '</div>'; // photo-name

                                            if ($has_bio) {
                                                $output .= '<button class="conference_cap__lecture-speaker-btn" data-lecture-id="' . $lectureId . '">BIO</button>';

                                                // ⬇️ DANE DO JS – ZGODNE Z HTML-em
                                                if (!isset($speakersDataMapping[$conf_slug])) {
                                                    $speakersDataMapping[$conf_slug] = [];
                                                }

                                                $speakersDataMapping[$conf_slug][$lectureId] = [
                                                    'name'      => strip_tags($speaker['name_html']),
                                                    'name_html' => $speaker['name_html'],
                                                    'url'       => $speaker['url'],
                                                    'bio'       => $speaker['desc']
                                                ];
                                            }

                                            $output .= '</div>'; // .lecture-speaker-item
                                        }

                                        $output .= '</div>'; // .lecture-speaker-flex
                                    }

                                }

                            $output .= '</div>'; // Zamknięcie kontenera zakładek

                            $output .= '<div class="conference_cap__after-all-html">' . ($inf_conf['after_all_' . $conf_slug] ?? '') . '</div>';

                            if($panel === true){
                                require_once plugin_dir_path(__FILE__) . 'assets/conference-cap-trends-panel.php';
                                $output .= PWEConferenceCapTrendsPanel::output($atts);
                            }
                        }
                    $output .= '</div>'; // Zamknięcie `conf-tab`
                    if ($one_conf_mode) {
                        break; // pokaż tylko pierwszą konferencję
                    }
                }

            $output .= '</div>'; // Zamknięcie `conf-tabs`
            }

        $output .= '</div>';

        self::addingScripts($atts, $speakersDataMapping, $one_conf_mode, $conference_cap_conference_arichive);

        return $output;
    }
}