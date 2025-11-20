<?php

/**
 * PWEExhibitorGenerator Class
 *
 * This class handles the exhibitor generator functionality for the PWE plugin.
 * It manages forms, color schemes, shortcode generation, and integrates with external
 * classes to generate exhibitor guest and staff data.
 */

class PWEExhibitorGenerator{

    public static $exhibitor_logo_url;
    public static $exhibitor_name;
    public static $exhibitor_desc;
    public static $exhibitor_stand;
    public static $rnd_id;
    public static $fair_colors;
    public static $accent_color;
    public static $main2_color;
    public static $fair_forms;
    public static $local_lang_pl;
    private $atts;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        $pweComonFunction = new PWECommonFunctions;
        self::$rnd_id = rand(10000, 99999);
        self::$fair_forms = $pweComonFunction->findFormsGF('id');
        self::$fair_colors = $pweComonFunction->findPalletColors();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';
        self::$local_lang_pl = (get_locale() == 'pl_PL');

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos($color_key, 'main2') != false){
                self::$main2_color = $color_value;
            }
        }
        add_action('init', array($this, 'initVCMapPWEExhibitorGenerator'));
        add_shortcode('pwe_exhibitor_generator', array($this, 'PWEExhibitorGeneratorOutput'));

        // Hook actions
        add_action('wp_enqueue_scripts', array($this, 'addingStyles'));

    }

    /**
     * Initialize VC Map PWEExhibitorGenerator.
     */
    public function initVCMapPWEExhibitorGenerator() {

        require_once plugin_dir_path(__FILE__) . 'classes/exhibitor-visitor-generator.php';
        require_once plugin_dir_path(__FILE__) . 'classes/exhibitor-worker-generator.php';
        require_once plugin_dir_path(__FILE__) . 'classes/mass-vip-sender.php';

        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __( 'PWE Exhibitor Generator', 'pwe_exhibitor_generator'),
                'base' => 'pwe_exhibitor_generator',
                'category' => __( 'PWE Elements', 'pwe_exhibitor_generator'),
                'admin_enqueue_css' => plugin_dir_url(dirname(__DIR__)) . 'backend/backendstyle.css',
                'class' => 'costam',
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select form', 'pwelement'),
                        'param_name' => 'generator_form_id',
                        'save_always' => true,
                        'value' => array_merge(
                            array('Wybierz' => ''),
                            self::$fair_forms,
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select form mode', 'pwelement'),
                        'param_name' => 'exhibitor_generator_mode',
                        'save_always' => true,
                        'value' => array(
                            'Generator gości wystawców' => 'PWEExhibitorVisitorGenerator',
                            'Generator pracowników wystawców' => 'PWEExhibitorWorkerGenerator',
                        ),
                        'std' => 'PWEExhibitorVisitorGenerator',
                    ),
                    array(
                        'type' => 'textarea_raw_html',
                        'group' => 'PWE Element',
                        'heading' => __('Footer HTML Text', 'pwelement'),
                        'param_name' => 'generator_html_text',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,

                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'PWE Element',
                        'heading' => __('Włącza dodatkowe pole telefon', 'pwelement'),
                        'param_name' => 'phone_field',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'PWE Element',
                        'heading' => __('Dodatkowe powiadomienie dla patronów', 'pwelement'),
                        'param_name' => 'generator_patron',
                        'save_always' => true,
                    ),

                    array(
                        'type' => 'checkbox',
                        'group' => 'PWE Element',
                        'heading' => __('Połączeni z Katalogiem wystawców', 'pwelement'),
                        'param_name' => 'generator_catalog',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'param_group',
                        'group' => 'PWE Element',
                        'heading' => __('Personalizowanie Pod wystawce', 'pwelement'),
                        'description' => __('Dodaj wystawce do grupy i sprawdź na stronie pod parametrem <br> ?wysatwca=...', 'pwelement'),
                        'param_name' => 'company_edition',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => __('UNIKALNY! token  do adresu url <br> ?wystawca=', 'pwelement'),
                                'param_name' => 'exhibitor_token',
                                'save_always' => true,
                                'admin_label' => true,
                                'value' => '',
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Header Custom', 'pwelement'),
                                'param_name' => 'exhibitor_heder',
                                'save_always' => true,
                                'value' => '',
                            ),
                            array(
                                'type' => 'attach_image',
                                'heading' => __('Logo Wystawcy', 'pwelement'),
                                'param_name' => 'exhibitor_logo',
                                'save_always' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Nazwa Wystawcy', 'pwelement'),
                                'param_name' => 'exhibitor_name',
                                'save_always' => true,
                                'value' => '',
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => __('Opis Wystawcy', 'pwelement'),
                                'param_name' => 'exhibitor_desc',
                                'save_always' => true,
                                'value' => '',
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Pozycja na targach', 'pwelement'),
                                'param_name' => 'exhibitor_stand',
                                'save_always' => true,
                                'value' => '',
                            ),
                        ),
                    ),
                ),
            ));
        }
    }

    /**
     * Check class for file if exists.
     *
     * @return array
     */
    private function findClassElements() {
        // Array off class placement
        return array(
            'PWEExhibitorVisitorGenerator' => 'classes/exhibitor-visitor-generator.php',
            'PWEExhibitorWorkerGenerator'  => 'classes/exhibitor-worker-generator.php',
        );
    }

    /**
     * Enqueues styles for the exhibitor generator.
     *
     * Loads the CSS file from the `assets` folder and sets the version based on the file's last modified time.
     */
    public function addingStyles(){
        $css_file = plugins_url('assets/exhibitor-generator-style.css', __FILE__);
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/exhibitor-generator-style.css');
        wp_enqueue_style('pwe-exhibitor-generator-css', $css_file, array(), $css_version);
    }

    /**
     * Enqueues scripts for the exhibitor generator.
     *
     * Loads the JS file from the `assets` folder and localizes data for backend communication,
     * such as the secret key and language strings.
     *
     * @param array $atts Shortcode attributes.
     */
    public function addingScripts($atts){
        $send_data = [
            'phone_field' => $atts['phone_field'],
            'send_file' => plugins_url('assets/mass_vip.php', __FILE__ ),
            'secret' =>  hash_hmac('sha256', $_SERVER["HTTP_HOST"], AUTH_KEY),
            'lang' => get_locale(),
            'custom_form' => $atts['generator_form_id'],
        ];

        $js_file = plugins_url('assets/exhibitor-generator-script.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/exhibitor-generator-script.js');
        wp_enqueue_script('pwe-exhibitor-generator-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script( 'exclusions-js', 'send_data', $send_data );
    }

    /**
     * Hides Gravity Form fields based on their label.
     *
     * Targets fields with labels 'FIRMA ZAPRASZAJĄCA', 'FIRMA', 'INVITING COMPANY', and 'COMPANY'.
     * Adds the `gf_hidden` CSS class to hide these fields and sets a default value for the fields.
     *
     * @param array $form Gravity Form.
     * @param string $com_name The company name to set as the default value.
     * @return array Updated form with hidden fields.
     */
    public static function hide_field_by_label( $form, $com_name ) {
        $label_to_hide = ['FIRMA ZAPRASZAJĄCA', 'FIRMA', 'INVITING COMPANY', 'COMPANY'];

        foreach( $form['fields'] as &$field ) {
            if( in_array($field->label, $label_to_hide) ) {
                $field->cssClass .= ' gf_hidden';
                $field->defaultValue = $com_name;
            }
        }
        return $form;
    }

    /**
     * Retrieves exhibitor data from the trade show catalog based on the given exhibitor ID.
     *
     * Makes a request to an external API using the token and show ID, then returns the exhibitor's data
     * as an associative array.
     *
     * @param string $exhibitor_id The ID of the exhibitor.
     * @return array|null Returns the exhibitor's data as an associative array, or null if no data exists.
     */
    public static function catalog_data($exhibitor_id = null) {
        $katalog_id = do_shortcode('[trade_fair_catalog]');

        $today = new DateTime();
        $formattedDate = $today->format('Y-m-d');
        $token = md5("#22targiexpo22@@@#".$formattedDate);
        $exh_catalog_address = PWECommonFunctions::get_database_meta_data('exh_catalog_address');
        $canUrl = $exh_catalog_address . $token.'&id_targow='.$katalog_id;
        $json = file_get_contents($canUrl);
        if ($exhibitor_id === null){
            $data_array = json_decode($json, true);
            return  $data_array;
        }

        if ($json !== null){
            $search_id = $exhibitor_id . '.00';
            $data_array = json_decode($json, true);
            $exhibitors_data = reset($data_array)['Wystawcy'];
            $exhibitor =  $exhibitors_data[$search_id];
            return  $exhibitor;
        };
        return null;
    }

    /**
     * Output method for pwe_exhibitor_generator shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWEExhibitorGeneratorOutput($atts) {

        extract( shortcode_atts( array(
            'exhibitor_generator_mode' => '',
        ), $atts ));

        global $local_lang_pl;
        $local_lang_pl = self::$local_lang_pl;

        if ($this->findClassElements()[$exhibitor_generator_mode]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$exhibitor_generator_mode];

            // Check which generator mode is in use (guest or staff).
            // Based on the mode, load the appropriate class to generate the output.
            if (class_exists($exhibitor_generator_mode)) {
                $output_class = new $exhibitor_generator_mode;
                $output = $output_class->output($atts);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $exhibitor_generator_mode .' does not exist")</script>';
                $output = '';
            }
        } else {
            // Log if class file doesn't exist
            echo '<script>console.log("File with class ' . $exhibitor_generator_mode .' does not exist")</script>';
        }

        // Chengind shortcode in output
        $output = do_shortcode($output);

        // Finding with generator it is
        $exhibitor_generator_id_word = $exhibitor_generator_mode == 'PWEExhibitorVisitorGenerator' ? 'Visitor' : 'Worker';
        $exhibitor_generator_class_word = $exhibitor_generator_mode == 'PWEExhibitorVisitorGenerator' ? 'visitor' : 'worker';
        $exhibitor_generator_id = 'pweExhibitor'. $exhibitor_generator_id_word .'Generator';
        $exhibitor_generator_class = 'pwe-exhibitor-'. $exhibitor_generator_class_word .'-generator';

        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $current_domain = $_SERVER['HTTP_HOST'];
        $current_fair_group = null;

        foreach ($pwe_groups_data as $item) {
            if ($item->fair_domain === $current_domain) {
                $current_fair_group = $item->fair_group;
                break;
            }
        }

        $domain_gr_exhib = $current_fair_group;

        if ($domain_gr_exhib === 'gr3') {
            $email = 'media3@warsawexpo.eu';
        } else {
            $email = 'generator.wystawcow@warsawexpo.eu';
        }

        //Creating output for display
        $output_html = '
        <div id="'. $exhibitor_generator_id .'" class="'. $exhibitor_generator_class .'">
            ' . $output . '
            <div style="text-align: center; display: flex; justify-content: center;" class="heading-text exhibitor-generator-tech-support">
                <h3>'.
                    PWECommonFunctions::languageChecker(
                        <<<PL
                            Potrzebujesz pomocy?<br>
                            Skontaktuj się z nami - <a href="mailto:$email">$email</a>
                        PL,
                        <<<EN
                            Need help?<br>
                            Contact us - <a href="mailto:$email">$email</a>
                        EN
                    )
                .'</h3>
            </div>
        </div>';

        $this->addingScripts($atts);

        return $output_html;
    }
}