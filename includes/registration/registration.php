<?php

/**
 * Class PWERegistration
 * Extends pwe_registrations class and defines a custom Visual Composer element for vouchers.
 */
class PWERegistration extends PWECommonFunctions {

    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;
    public static $fair_forms;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        self::$rnd_id = rand(10000, 99999);
        self::$fair_forms = $this->findFormsGF();
        self::$fair_colors = $this->findPalletColors();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos(strtolower($color_key), 'main2') !== false){
                self::$main2_color = $color_value;
            }
        }

        // Hook actions
        add_action('init', array($this, 'initVCMapPWERegistration'));

        add_shortcode('pwe_registration', array($this, 'PWERegistrationOutput'));
        add_action('gform_after_submission', array($this, 'entryToSession'), 10, 2);
    }

    public function entryToSession($entry, $form) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $current_path = parse_url($current_url, PHP_URL_PATH);

        $is_exhibitor_page = strpos($current_url, '/zostan-wystawca/') !== false ||
                            strpos($current_url, '/en/become-an-exhibitor/') !== false ||
                            strpos($current_url, '/krok2/') !== false ||
                            strpos($current_url, '/step2/') !== false;

        $is_registration_page = strpos($current_url, '/rejestracja/') !== false ||
                                strpos($current_url, '/en/registration/') !== false ||
                                strpos($current_url, '/registration/') !== false;


        if ($is_exhibitor_page) {
            $_SESSION['pwe_exhibitor_entry'] = [
                'entry_id' => $entry['id'],
                'current_url' => $current_path,
            ];
        } elseif ($is_registration_page) {
            $_SESSION['pwe_reg_entry'] = [
                'entry_id' => $entry['id'],
            ];
        }

        foreach ($form['fields'] as $single_field) {
            if ($single_field['type'] == 'email') {
                if ($is_exhibitor_page) {
                    $_SESSION['pwe_exhibitor_entry']['email'] = $entry[$single_field['id']];
                } elseif ($is_registration_page) {
                    $_SESSION['pwe_reg_entry']['email'] = $entry[$single_field['id']];
                }
                continue;
            }

            if ($single_field['type'] == 'phone') {
                if ($is_exhibitor_page) {
                    $_SESSION['pwe_exhibitor_entry']['phone'] = $entry[$single_field['id']];
                } elseif ($is_registration_page) {
                    $_SESSION['pwe_reg_entry']['phone'] = $entry[$single_field['id']];
                }
                continue;
            }
        }
    }
    /**
 * Initialize VC Map PWERegistration.
 */
public function initVCMapPWERegistration() {

    require_once plugin_dir_path(__FILE__) . 'classes/registration_visitors.php';
    require_once plugin_dir_path(__FILE__) . 'classes/registration_exhibitors.php';
    require_once plugin_dir_path(__FILE__) . 'classes/registration_potential_exhibitors.php';
    require_once plugin_dir_path(__FILE__) . 'classes/registration_accreditations.php';
    // Check if Visual Composer is available
    if (class_exists('Vc_Manager')) {
        vc_map( array(
            'name' => __( 'PWE Registration', 'pwe_registration'),
            'base' => 'pwe_registration',
            'category' => __( 'PWE Elements', 'pwe_registration'),
            'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
            'params' => array_merge(
                array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Select registration type', 'pwe_registration'),
                        'param_name' => 'registration_type',
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => array(
                            'Visitors' => 'PWERegistrationVisitors',
                            'Exhibitors' => 'PWERegistrationExhibitors',
                            'Potential exhibitors' => 'PWERegistrationPotentialExhibitors',
                            'Accreditations' => 'PWERegistrationAccreditations',
                            'Ticket' => 'PWERegistrationTicket',
                        ),
                        'std' => 'PWERegistrationVisitors',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Registration Form', 'pwe_registration'),
                        'param_name' => 'registration_form_id',
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'save_always' => true,
                        'value' => array_merge(
                            array('Wybierz' => ''),
                            self::$fair_forms,
                        ),
                        'dependency' => array(
                            'element' => 'pwe_registration',
                            'value' => 'PWElementRegistration',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Select button color <a href="#" onclick="yourFunction(`btn_color_manual_hidden`, `btn_color`)">Hex</a>', 'pwe_registration'),
                        'param_name' => 'btn_color',
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'description' => __('Select button color for the element.', 'pwe_registration'),
                        'value' => $this->findPalletColors(),
                        'dependency' => array(
                            'element' => 'btn_color_manual_hidden',
                            'value' => array(''),
                        ),
                        'save_always' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Write button color <a href="#" onclick="yourFunction(`btn_color`, `btn_color_manual_hidden`)">Pallet</a>', 'pwe_registration'),
                        'param_name' => 'btn_color_manual_hidden',
                        'param_holder_class' => 'backend-area-one-fourth-width pwe_dependent-hidden',
                        'description' => __('Write hex number for button color for the element.', 'pwe_registration'),
                        'value' => '',
                        'save_always' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Select button text color <a href="#" onclick="yourFunction(`btn_text_color_manual_hidden`, `btn_text_color`)">Hex</a>', 'pwe_registration'),
                        'param_name' => 'btn_text_color',
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'description' => __('Select button text color for the element.', 'pwe_registration'),
                        'value' => $this->findPalletColors(),
                        'dependency' => array(
                            'element' => 'btn_text_color_manual_hidden',
                            'value' => array(''),
                        ),
                        'save_always' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Write button text color <a href="#" onclick="yourFunction(`btn_text_color`, `btn_text_color_manual_hidden`)">Pallet</a>', 'pwe_registration'),
                        'param_name' => 'btn_text_color_manual_hidden',
                        'param_holder_class' => 'backend-area-one-fourth-width pwe_dependent-hidden',
                        'description' => __('Write hex number for button text color for the element.', 'pwe_registration'),
                        'value' => '',
                        'save_always' => true
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Show ticket', 'pwe_registration'),
                        'param_name' => 'register_show_ticket',
                        'description' => __('Check if you want to show ticket.', 'pwe_registration'),
                        'value' => '',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Ticket link', 'pwe_registration'),
                        'param_name' => 'register_ticket_link',
                        'description' => __('Enter the link for the ticket.', 'pwe_registration'),
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'register_show_ticket',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Ticket during the fair', 'pwe_registration'),
                        'param_name' => 'register_ticket_price_frist',
                        'description' => __('Enter the custom price for the ticket.', 'pwe_registration'),
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'register_show_ticket',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Ticket price', 'pwe_registration'),
                        'param_name' => 'register_ticket_price',
                        'description' => __('Enter the custom price for the ticket.', 'pwe_registration'),
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'register_show_ticket',
                            'value' => array('true'),
                        ),
                    ),
                    // array(
                    //     'type' => 'textarea',
                    //     'heading' => __('Registration benefits', 'pwe_registration'),
                    //     'param_name' => 'register_ticket_register_benefits',
                    //     'description' => __('Enter custom HTML list of register benefits. If left empty, default content will be used.', 'pwe_registration'),
                    //     'param_holder_class' => 'backend-area-one-two-width',
                    //     'save_always' => true,
                    //     'admin_label' => true,
                    //     'dependency' => array(
                    //         'element' => 'register_show_ticket',
                    //         'value' => array('true'),
                    //     ),
                    // ),
                    // array(
                    //     'type' => 'textarea',
                    //     'heading' => __('Ticket benefits', 'pwe_registration'),
                    //     'param_name' => 'register_ticket_benefits',
                    //     'description' => __('Enter custom HTML list of ticket benefits. If left empty, default content will be used.', 'pwe_registration'),
                    //     'param_holder_class' => 'backend-area-one-two-width',
                    //     'save_always' => true,
                    //     'dependency' => array(
                    //         'element' => 'register_show_ticket',
                    //         'value' => array('true'),
                    //     ),
                    // ),
                    array(
                        'type' => 'param_group',
                        'group' => 'Replace Strings',
                        'param_name' => 'pwe_replace',
                        'params' => array(
                            array(
                                'type' => 'textarea',
                                'heading' => __('Input HTML', 'pwelement'),
                                'param_name' => 'input_replace_html',
                                'save_always' => true,
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => __('Output HTML', 'pwelement'),
                                'param_name' => 'output_replace_html',
                                'save_always' => true,
                                'admin_label' => true
                            ),
                        ),
                    ),

                ),
            ),
        ));
    }
}

/**
     * Adding Scripts
     */
    public function addingScripts(){
        
        $source_utm = (isset($_SERVER['argv'][0])) ? $_SERVER['argv'][0] : '';

        $data_js_array = array(
            'source_utm' => $source_utm,
        );

        // JS
        $js_file = plugins_url('assets/script.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/script.js');
        wp_enqueue_script('script-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script( 'script-js', 'data_js', $data_js_array );
    }

    /**
     * Check class for file if exists.
     *
     * @return array
     */
    private function findClassElements() {
        // Array off class placement
        return array(
            'PWERegistrationVisitors'               => 'classes/registration_visitors.php',
            'PWERegistrationExhibitors'             => 'classes/registration_exhibitors.php',
            'PWERegistrationPotentialExhibitors'    => 'classes/registration_potential_exhibitors.php',
            'PWERegistrationAccreditations'         => 'classes/registration_accreditations.php',
        );
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public function PWERegistrationOutput($atts) {
        $this->addingScripts();

        extract( shortcode_atts( array(
            'registration_type' => '',
            'registration_form_id' => '',
            'pwe_replace' => '',
            'register_show_ticket' => '',
            'register_ticket_link' => '',
            'register_ticket_price' => '',
            'register_ticket_price_frist' => '',
            'register_ticket_benefits' => '',
            'register_ticket_register_benefits' => '',
        ), $atts ));

        // Replace strings
        $pwe_replace_urldecode = urldecode($pwe_replace);
        $pwe_replace_json = json_decode($pwe_replace_urldecode, true);
        $input_replace_array_html = array();
        $output_replace_array_html = array();
        foreach ($pwe_replace_json as $replace_item) {
            $input_replace_array_html[] = $replace_item["input_replace_html"];
            $output_replace_array_html[] = $replace_item["output_replace_html"];
        }

        if ($this->findClassElements()[$registration_type]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$registration_type];

            if (class_exists($registration_type)) {
                $output_class = new $registration_type;
                $output = $output_class->output($atts, $registration_type, $registration_form_id, $register_show_ticket);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $registration_type .' does not exist")</script>';
                $output = '';
            }
        } else {
            echo '<script>console.log("File with class ' . $registration_type .' does not exist")</script>';
        }

        $output = do_shortcode($output);

        $output .= '
        <style>
            .pwelement_'. self::$rnd_id .' input[type="submit"] {
                border-radius: 10px !important;
                box-shadow: none !important;
                border: none !important;
            }
            .pwelement_'. self::$rnd_id .' .gfield--type-consent {
                line-height: 1.2 !important;
            }
            .pwelement_'. self::$rnd_id .' .gfield--type-consent input[type="checkbox"] {
                margin-top: 0 !important;
            }
        </style>';

        $output_html = '<div class="pwelement pwelement_'. self::$rnd_id .'">' . $output . '</div>';

        if ($input_replace_array_html && $output_replace_array_html) {
            $output_html = str_replace($input_replace_array_html, $output_replace_array_html, $output_html);
        }

        return $output_html;
    }
}