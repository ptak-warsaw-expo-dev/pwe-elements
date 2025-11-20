<?php

class PWElements {
    public static $rnd_id;
    public static $fair_colors;
    public static $fair_forms;
    public static $accent_color;
    public static $main2_color;

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        self::$rnd_id = rand(10000, 99999);
        self::$fair_colors = $this->findPalletColors();
        self::$fair_forms = $this->findFormsGF();
        self::$accent_color = (self::$fair_colors['Accent']) ? self::$fair_colors['Accent'] : '';

        foreach(self::$fair_colors as $color_key => $color_value){
            if(strpos(strtolower($color_key), 'main2') !== false){
                self::$main2_color = $color_value;
            }
        }

        // Hook actions
        add_action('wp_enqueue_scripts', array($this, 'addingStyles'));
        add_action('wp_enqueue_scripts', array($this, 'addingScripts'));

        // AJAX load more posts
        add_action('init', array($this, 'load_more_posts'));

        add_action('init', array($this, 'initVCMapElements'));
        add_shortcode('pwelement', array($this, 'PWElementsOutput'));

        // add_action('gform_after_submission', array($this, 'entryToSession'), 10, 2);
    }

    // public function entryToSession($entry, $form) {
    //     $entry_returner = array();

    //     foreach($form['fields'] as $single_field){
    //         if($single_field['type'] == 'email'){
    //             $entry_returner['email'] = $entry[$single_field['id']];
    //             continue;
    //         }

    //         if($single_field['type'] == 'phone'){
    //             $entry_returner['phone'] = $entry[$single_field['id']];
    //             continue;
    //         }
    //     }
    //     $_SESSION['pwe_reg_entry'] = $entry_returner;
    // }

    /**
     * Initialize VC Map Elements.
     */
    public function initVCMapElements() {
        //Add all vc_map elements files
        require_once plugin_dir_path(__FILE__) . 'badge-local.php';
        require_once plugin_dir_path(__FILE__) . 'for-exhibitors.php';
        require_once plugin_dir_path(__FILE__) . 'for-visitors.php';
        require_once plugin_dir_path(__FILE__) . 'kontakt.php';
        require_once plugin_dir_path(__FILE__) . 'kontakt-info.php';
        require_once plugin_dir_path(__FILE__) . 'gallery.php';
        require_once plugin_dir_path(__FILE__) . 'videos.php';
        require_once plugin_dir_path(__FILE__) . 'profile.php';
        require_once plugin_dir_path(__FILE__) . 'promote-yourself.php';
        require_once plugin_dir_path(__FILE__) . 'sticky-buttons.php';
        require_once plugin_dir_path(__FILE__) . 'countdown.php';
        require_once plugin_dir_path(__FILE__) . 'wydarzenia-ogolne.php';
        require_once plugin_dir_path(__FILE__) . 'posts.php';
        require_once plugin_dir_path(__FILE__) . 'footer.php';
        require_once plugin_dir_path(__FILE__) . 'generator-wystawcow.php';
        require_once plugin_dir_path(__FILE__) . 'registration.php';
        require_once plugin_dir_path(__FILE__) . 'registration-content.php';
        require_once plugin_dir_path(__FILE__) . 'x_step_registration.php';
        require_once plugin_dir_path(__FILE__) . 'zaproszenie.php';
        require_once plugin_dir_path(__FILE__) . 'ticket.php';
        require_once plugin_dir_path(__FILE__) . 'numbers.php';
        require_once plugin_dir_path(__FILE__) . 'confSection.php';
        require_once plugin_dir_path(__FILE__) . 'confHeader.php';
        require_once plugin_dir_path(__FILE__) . 'trends-panel.php';
        require_once plugin_dir_path(__FILE__) . 'mapa.php';
        require_once plugin_dir_path(__FILE__) . 'mapa-test.php';
        require_once plugin_dir_path(__FILE__) . 'step2.php';
        require_once plugin_dir_path(__FILE__) . 'pot_rej.php';
        require_once plugin_dir_path(__FILE__) . 'pot_rej_wys.php';
        require_once plugin_dir_path(__FILE__) . 'pot_akt_bil.php';
        require_once plugin_dir_path(__FILE__) . 'test.php';
        require_once plugin_dir_path(__FILE__) . 'test1.php';
        require_once plugin_dir_path(__FILE__) . 'test2.php';
        require_once plugin_dir_path(__FILE__) . 'resend-ticket.php';
        require_once plugin_dir_path(__FILE__) . 'header-new.php';
        require_once plugin_dir_path(__FILE__) . 'hale.php';
        require_once plugin_dir_path(__FILE__) . 'opinions.php';
        require_once plugin_dir_path(__FILE__) . 'button.php';
        require_once plugin_dir_path(__FILE__) . 'about.php';
        require_once plugin_dir_path(__FILE__) . 'why-its-worth.php';
        require_once plugin_dir_path(__FILE__) . 'qr-check.php';
        require_once plugin_dir_path(__FILE__) . 'pot_vip.php';
        require_once plugin_dir_path(__FILE__) . 'contact-form.php';
        require_once plugin_dir_path(__FILE__) . 'single-image.php';
        require_once plugin_dir_path(__FILE__) . 'medals.php';
        require_once plugin_dir_path(__FILE__) . 'other_events.php';
        require_once plugin_dir_path(__FILE__) . 'two_cols.php';
        require_once plugin_dir_path(__FILE__) . 'timeline-stats.php';
        require_once plugin_dir_path(__FILE__) . 'popup.php';
        require_once plugin_dir_path(__FILE__) . 'news-summary.php';
        require_once plugin_dir_path(__FILE__) . 'medal-form.php';

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __('PWE Elements', 'pwelement'),
                'base' => 'pwelement',
                'category' => __('PWE Elements', 'pwelement'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __FILE__ )) . 'backend/backendstyle.css',
                'admin_enqueue_js' => plugin_dir_url(dirname( __FILE__ )) . 'backend/backendscript.js',
                'params' => array_merge(
                    array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select an element', 'pwelement'),
                            'param_name' => 'pwe_element',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select an element to display its files.', 'pwelement'),
                            'value' => $this->getAvailableElements(),
                            'save_always' => true,
                            'admin_label' => true,
                        ),
                        // colors setup
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select text color <a href="#" onclick="yourFunction(`text_color_manual_hidden`, `text_color`)">Hex</a>', 'pwelement'),
                            'param_name' => 'text_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select text color for the element.', 'pwelement'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'text_color_manual_hidden',
                                'value' => array(''),
                                'callback' => "hideEmptyElem",
                            ),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write text color <a href="#" onclick="yourFunction(`text_color`, `text_color_manual_hidden`)">Pallet</a>', 'pwelement'),
                            'param_name' => 'text_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for text color for the element.', 'pwelement'),
                            'value' => '',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select text shadow color <a href="#" onclick="yourFunction(`text_shadow_color_manual_hidden`, `text_shadow_color`)">Hex</a>', 'pwelement'),
                            'param_name' => 'text_shadow_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select shadow text color for the element.', 'pwelement'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'text_shadow_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write text shadow color <a href="#" onclick="yourFunction(`text_shadow_color`, `text_shadow_color_manual_hidden`)">Pallet</a>', 'pwelement'),
                            'param_name' => 'text_shadow_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for text shadow color for the element.', 'pwelement'),
                            'value' => '',
                            'save_always' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select button color <a href="#" onclick="yourFunction(`btn_color_manual_hidden`, `btn_color`)">Hex</a>', 'pwelement'),
                            'param_name' => 'btn_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button color for the element.', 'pwelement'),
                            'value' => $this->findPalletColors(),
                            'dependency' => array(
                                'element' => 'btn_color_manual_hidden',
                                'value' => array(''),
                            ),
                            'save_always' => true
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Write button color <a href="#" onclick="yourFunction(`btn_color`, `btn_color_manual_hidden`)">Pallet</a>', 'pwelement'),
                            'param_name' => 'btn_color_manual_hidden',
                            'param_holder_class' => 'main-options pwe_dependent-hidden',
                            'description' => __('Write hex number for button color for the element.', 'pwelement'),
                            'value' => '',
                            'save_always' => true
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Select button text color <a href="#" onclick="yourFunction(`btn_text_color_manual_hidden`, `btn_text_color`)">Hex</a>', 'pwelement'),
                            'param_name' => 'btn_text_color',
                            'param_holder_class' => 'main-options',
                            'description' => __('Select button text color for the element.', 'pwelement'),
                            'value' => $this->findPalletColors(),
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
                            'value' => $this->findPalletColors(),
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
                        // Add additional options from class extends
                        ...PWBadgeElement::initElements(),
                        ...PWElementVideos::initElements(),
                        ...PWElementProfile::initElements(),
                        ...PWElementStickyButtons::initElements(),
                        ...PWElementForExhibitors::initElements(),
                        ...PWElementForVisitors::initElements(),
                        ...PWElementContact::initElements(),
                        ...PWElementMedalForm::initElements(),
                        ...PWElementContactInfo::initElements(),
                        ...PWElementPromot::initElements(),
                        ...PWElementConferences::initElements(),
                        ...PWElementHomeGallery::initElements(),
                        ...PWElementPosts::initElements(),
                        ...PWElementMainCountdown::initElements(),
                        ...PWElementFooter::initElements(),
                        ...PWElementGenerator::initElements(),
                        ...PWElementRegistration::initElements(),
                        ...PWElementRegContent::initElements(),
                        ...PWElementInvite::initElements(),
                        ...PWElementXForm::initElements(),
                        ...PWElementTicket::initElements(),
                        ...PWElementNumbers::initElements(),
                        ...PWElementConfSection::initElements(),
                        ...PWElementMapa::initElements(),
                        ...PWElementMapaTest::initElements(),
                        ...PWElementHeaderConference::initElements(),
                        ...PWElementPotwierdzenieRejestracji::initElements(),
                        ...PWElementStepTwoExhibitor::initElements(),
                        ...PWElementStepTwo::initElements(),
                        ...PWElementTicketActConf::initElements(),
                        ...PWElementOpinions::initElements(),
                        ...PWElementButton::initElements(),
                        ...PWElementAbout::initElements(),
                        ...PWElementWhyItsWorth::initElements(),
                        ...PWElementQRChekcer::initElements(),
                        ...PWElementConfirmationVip::initElements(),
                        ...PWElementContactForm::initElements(),
                        ...PWElementSingleImage::initElements(),
                        ...PWElementOtherEvents::initElements(),
                        ...PWElementTwoCols::initElements(),
                        ...PWElementHale::initElements(),
                        ...PWElementMedals::initElements(),
                        ...PWElementTimelineStats::initElements(),
                        ...PWElementPopup::initElements(),
                        ...PWElementNewsSummary::initElements(),
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
     * Get available elements for the dropdown.
     *
     * @return array
     */
    private function getAvailableElements() {
        // Creating an instance of elements to choose from
        return array(
            'Select' => '',
            'Adres Ptak Warsaw Expo'         => 'PWElementAddress',
            'Aktywacja QR'                   => 'PWEActiveQR',
            'Badge-Local'                    => 'PWBadgeElement',
            'Countdown'                      => 'PWElementMainCountdown',
            'Conference Side Events'         => 'PWElementConfSideEvents',
            'Conference Header'              => 'PWElementHeaderConference',
            'Contact form'                   => 'PWElementContactForm',
            'Dodaj do kalendarza'            => 'PWCallendarAddElement',
            'Dodaj do Apple Kalendarz'       => 'PWAppleCalendarElement',
            'Dodaj do Google Kalendarz'      => 'PWGoogleCalendarElement',
            'Dodaj do Outlook Kalendarz'     => 'PWOutlookCalendarElement',
            'Dodaj do Office Kalendarz'      => 'PWOfficeCalendarElement',
            'Dokumenty'                      => 'PWElementDonwload',
            'Exhibitors-benefits'            => 'PWElementExhibitors',
            'FAQ'                            => 'PWElementFaq',
            'Footer'                         => 'PWElementFooter',
            'For Exhibitors'                 => 'PWElementForExhibitors',
            'For Visitors'                   => 'PWElementForVisitors',
            'Generator wystawcow'            => 'PWElementGenerator',
            'Grupy zorganizowane'            => 'PWElementGroups',
            'Halls'                           => 'PWElementHale',
            'Informacje organizacyjne'       => 'PWElementOrgInfo',
            'Informacje kontaktowe'          => 'PWElementContactInfo',
            'Kalendarz do potwierdzenia'     => 'PWElementConfCallendar',
            'Kontakt'                        => 'PWElementContact',
            'Main Page About'                => 'PWElementAbout',
            'Main Page Gallery - mini'       => 'PWElementHomeGallery',
            'Map'                            => 'PWElementMapa',
            'Map test'                       => 'PWElementMapaTest',
            'Medals'                         => 'PWElementMedals',
            'Mapka dojazdu'                  => 'PWElementRoute',
            'Medale formularz'               => 'PWElementMedalForm',
            'Numbers'                        => 'PWElementNumbers',
            'Nie przegap'                    => 'PWElementDontMiss',
            'Organizator'                    => 'PWElementOrganizer',
            'Opinions'                       => 'PWElementOpinions',
            'Other Events'                   => 'PWElementOtherEvents',
            'Panel trendów'                  => 'PWElementTrendsPanel',
            'Posts'                          => 'PWElementPosts',
            'Potwierdzenie Rejestracji'      => 'PWElementPotwierdzenieRejestracji',
            'Potwierdzenie Rejestracji Wystawcy' => 'PWElementStepTwoExhibitor',
            'Potwierdzenie Aktywacji Biletu' => 'PWElementTicketActConf',
            'Potwierdzenie Vip'              => 'PWElementConfirmationVip',
            'Profile'                        => 'PWElementProfile',
            'Ramka Facebook'                 => 'PWElementSocials',
            'Registration'                   => 'PWElementRegistration',
            'Registration content'           => 'PWElementRegContent',
            'Resend ticket'                  => 'PWEResendTicket',
            'Sekcja konferencji'             => 'PWElementConfSection',
            'Single image'                   => 'PWElementSingleImage',
            'Sticky buttons'                 => 'PWElementStickyButtons',
            'Step2'                          => 'PWElementStepTwo',
            'Timeline Stats'                 => 'PWElementTimelineStats',
            'Ticket'                         => 'PWElementTicket',
            'Two cols'                       => 'PWElementTwoCols',
            'Videos'                         => 'PWElementVideos',
            'Visitors Timer'                 => 'PWElementVisitorsTimer',
            'Voucher'                        => 'PWElementVoucher',
            'Visitors Benefits'              => 'PWElementVisitorsBenefits',
            'Wydarzenia ogólne'              => 'PWElementConferences',
            'Wypromuj się na targach'        => 'PWElementPromot',
            'X-form'                         => 'PWElementXForm',
            'Zabudowa'                       => 'PWElementStand',
            'Zaproszenie'                    => 'PWElementInvite',
            'Test'                           => 'PWElementTest',
            'Test1'                          => 'PWElementTest1',
            'Test2'                          => 'PWElementTest2',
            'Header New'                     => 'PWElementHeaderNew',
            'Button'                         => 'PWElementButton',
            'Why its worth'                  => 'PWElementWhyItsWorth',
            'Qr checker'                     => 'PWElementQRChekcer',
            'Popup'                          => 'PWElementPopup',
            'News Summary'                          => 'PWElementNewsSummary',
        );
    }

    /**
     * Check class for file if exists.
     *
     * @return array
     */
    private function findClassElements() {
        // Array off class placement
        return array(
            'PWEActiveQR'               => 'active_qr.php',
            'PWElementAddress'          => 'ptakAdress.php',
            'PWBadgeElement'            => 'badge-local.php',
            'PWElementMainCountdown'    => 'countdown.php',
            'PWCallendarAddElement'     => 'calendarAdd.php',
            'PWAppleCalendarElement'    => 'calendarApple.php',
            'PWGoogleCalendarElement'   => 'calendarGoogle.php',
            'PWOutlookCalendarElement'  => 'calendarOutlook.php',
            'PWOfficeCalendarElement'   => 'calendarOffice.php',
            'PWElementConfCallendar'    => 'confCalendar.php',
            'PWElementConfSection'      => 'confSection.php',
            'PWElementConfirmationVip'  => 'pot_vip.php',
            'PWElementContactForm'      => 'contact-form.php',
            'PWElementHeaderConference' => 'confHeader.php',
            'PWElementConfSideEvents'   => 'conf_side_events.php',
            'PWElementAbout'            => 'about.php',
            'PWElementDonwload'         => 'download.php',
            'PWElementExhibitors'       => 'exhibitors-benefits.php',
            'PWElementFaq'              => 'faq.php',
            'PWElementFooter'           => 'footer.php',
            'PWElementForExhibitors'    => 'for-exhibitors.php',
            'PWElementForVisitors'      => 'for-visitors.php',
            'PWElementGenerator'        => 'generator-wystawcow.php',
            'PWElementGroups'           => 'grupy.php',
            'PWElementMapa'             => 'mapa.php',
            'PWElementMapaTest'         => 'mapa-test.php',
            'PWElementMedals'           => 'medals.php',
            'PWElementMedalForm'        => 'medal-form.php',
            'PWElementOrgInfo'          => 'org-information.php',
            'PWElementOpinions'         => 'opinions.php',
            'PWElementOtherEvents'      => 'other_events.php',
            'PWElementContactInfo'      => 'kontakt-info.php',
            'PWElementContact'          => 'kontakt.php',
            'PWElementHomeGallery'      => 'gallery.php',
            'PWElementRoute'            => 'route.php',
            'PWElementRegistration'     => 'registration.php',
            'PWElementRegContent'       => 'registration-content.php',
            'PWEResendTicket'           => 'resend-ticket.php',
            'PWElementNumbers'          => 'numbers.php',
            'PWElementDontMiss'         => 'niePrzegap.php',
            'PWElementOrganizer'        => 'organizator.php',
            'PWElementPosts'            => 'posts.php',
            'PWElementPotwierdzenieRejestracji' => 'pot_rej.php',
            'PWElementProfile'          => 'profile.php',
            'PWElementSocials'          => 'socialMedia.php',
            'PWElementStickyButtons'    => 'sticky-buttons.php',
            'PWElementStepTwo'          => 'step2.php',
            'PWElementStepTwoExhibitor' => 'pot_rej_wys.php',
            'PWElementSingleImage'      => 'single-image.php',
            'PWElementTicketActConf'    => 'pot_akt_bil.php',
            'PWElementTicket'           => 'ticket.php',
            'PWElementTrendsPanel'      => 'trends-panel.php',
            'PWElementVideos'           => 'videos.php',
            'PWElementVisitorsTimer'    => 'visitors_timer.php',
            'PWElementVoucher'          => 'voucher.php',
            'PWElementVisitorsBenefits' => 'visitors-benefits.php',
            'PWElementConferences'      => 'wydarzenia-ogolne.php',
            'PWElementPromot'           => 'promote-yourself.php',
            'PWElementXForm'            => 'x_step_registration.php',
            'PWElementStand'            => 'zabudowa.php',
            'PWElementInvite'           => 'zaproszenie.php',
            'PWElementTest'             => 'test.php',
            'PWElementTest1'            => 'test1.php',
            'PWElementTest2'            => 'test2.php',
            'PWElementHeaderNew'        => 'header-new.php',
            'PWElementHale'             => 'hale.php',
            'PWElementButton'           => 'button.php',
            'PWElementWhyItsWorth'      => 'why-its-worth.php',
            'PWElementQRChekcer'        => 'qr-check.php',
            'PWElementTwoCols'          => 'two_cols.php',
            'PWElementTimelineStats'    => 'timeline-stats.php',
            'PWElementPopup'            => 'popup.php',
            'PWElementNewsSummary'      => 'news-summary.php',
        );
    }

    /**
     * Function to handle the AJAX request
     */
    public function load_more_posts() {
        require_once plugin_dir_path(__FILE__) . '/../backend/load-more-posts.php';
    }

    /**
     * Adding Styles
     */
    public function addingStyles(){
        $css_file = plugins_url('css/style.css', __FILE__);
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'css/style.css');
        wp_enqueue_style('pwelement-css', $css_file, array(), $css_version);
    }

    /**
     * Adding Scripts
     */
    public function addingScripts(){
        $js_file = plugins_url('js/script.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'js/script.js');
        wp_enqueue_script('pwelement-js', $js_file, array('jquery'), $js_version, true);

        $data_js_array = array(
            'accent_color' => self::$accent_color,
            'main2_color' => self::$main2_color,
        );

        // Exclusions JS
        $exclusions_js_file = plugins_url('js/exclusions.js', __FILE__);
        $exclusions_js_version = filemtime(plugin_dir_path(__FILE__) . 'js/exclusions.js');
        wp_enqueue_script('exclusions-js', $exclusions_js_file, array('jquery'), $exclusions_js_version, true);
        wp_localize_script( 'exclusions-js', 'data_js', $data_js_array );
    }


    /**
     * Adding Styles
     */
    public static function findColor($primary, $secondary, $default = ''){
        if($primary != ''){
            return $primary;
        } elseif ($secondary != ''){
            return $secondary;
        } else {
            return $default;
        }
    }

    /**
     * Finding preset colors pallet.
     *
     * @return array
     */
    public function findPalletColors() {
        $uncode_options = get_option('uncode');
        $accent_uncode_color = $uncode_options["_uncode_accent_color"];
        $custom_element_colors = array();

        if (isset($uncode_options["_uncode_custom_colors_list"]) && is_array($uncode_options["_uncode_custom_colors_list"])) {
            $custom_colors_list = $uncode_options["_uncode_custom_colors_list"];

            foreach ($custom_colors_list as $color) {
                $title = $color['title'];
                $color_value = $color["_uncode_custom_color"];
                $color_id = $color["_uncode_custom_color_unique_id"];

                if ($accent_uncode_color != $color_id) {
                    $custom_element_colors[$title] = $color_value;
                } else {
                    $accent_color_value = $color_value;
                    $custom_element_colors = array_merge(array('Accent' => $accent_color_value), $custom_element_colors);
                }
            }
            $custom_element_colors = array_merge(array('Default' => ''), $custom_element_colors);
        }
        return $custom_element_colors;
    }

    /**
     * Laguage check for text
     *
     * @param string $pl text in Polish.
     * @param string $pl text in English.
     * @return string
     */
    public static function languageChecker($pl, $en = ''){
        return get_locale() == 'pl_PL' ? $pl : $en;
    }

     /**
     * Function to change color brightness (taking color in hex format)
     *
     * @return array
     */
    public static function adjustBrightness($hex, $steps) {
        // Convert hex to RGB
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Shift RGB values
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        // Convert RGB back to hex
        return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
                . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
                . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Finding all GF forms
     *
     * @return array
     */
    public function findFormsGF(){
        $pwe_forms_array = array();
        if (is_admin()) {
            if (method_exists('GFAPI', 'get_forms')) {
                $pwe_forms = GFAPI::get_forms();
                foreach ($pwe_forms as $form) {
                    $pwe_forms_array[$form['id']] = $form['title'];
                }
            }
        }
        return $pwe_forms_array;
    }

    /**
     * Finding all target form id
     *
     * @param string $form_name
     * @return string
     */
    public static function findFormsID($form_name){
        $pwe_form_id = '';
        if (method_exists('GFAPI', 'get_forms')) {
            $pwe_forms = GFAPI::get_forms();
            foreach ($pwe_forms as $form) {
                if ($form['title'] === $form_name){
                    $pwe_form_id = $form['id'];
                    break;
                }
            }
        }
        return $pwe_form_id;
    }

    /**
     * Mobile displayer check
     *
     * @return bool
     */
    public static function checkForMobile(){
        return (preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']));
    }

    /**
     * Laguage check for text
     *
     * @param bool $logo_color schould logo be in color.
     * @return string
     */
    public static function findBestLogo($logo_color = false){
        $filePaths = array(
            '/doc/logo-color-en.webp',
            '/doc/logo-color-en.png',
            '/doc/logo-color.webp',
            '/doc/logo-color.png',
            '/doc/logo-en.webp',
            '/doc/logo-en.png',
            '/doc/logo.webp',
            '/doc/logo.png'
        );

        switch (true){
            case(get_locale() == 'pl_PL'):
                if($logo_color){
                    foreach ($filePaths as $path) {
                        if (strpos($path, '-en.') === false && file_exists(ABSPATH . $path)) {
                            return '<img src="' . $path . '"/>';
                        }
                    }
                } else {
                    foreach ($filePaths as $path) {
                        if ( strpos($path, 'color') === false && strpos($path, '-en.') === false && file_exists(ABSPATH . $path)) {
                            return '<img src="' . $path . '"/>';
                        }
                    }
                }
                break;

            case(get_locale() == 'en_US'):
                if($logo_color){
                    foreach ($filePaths as $path) {
                        if (file_exists(ABSPATH . $path)) {
                            return '<img src="' . $path . '"/>';
                        }
                    }
                } else {
                    foreach ($filePaths as $path) {
                        if (strpos($path, 'color') === false && file_exists(ABSPATH . $path)) {
                            return '<img src="' . $path . '"/>';
                        }
                    }
                }
                break;
        }
    }

    /**
     * Finding URL of all images based on katalog
     */
    public static function findAllImages($firstPath, $image_count = false, $secondPath = '/doc/galeria'){
        $firstPath = $_SERVER['DOCUMENT_ROOT'] . $firstPath;

        if (is_dir($firstPath) && !empty(glob($firstPath . '/*.{jpeg,jpg,png,webp,svg,JPEG,JPG,PNG,WEBP}', GLOB_BRACE))) {
            $exhibitorsImages = glob($firstPath . '/*.{jpeg,jpg,png,webp,svg,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);
        } else {
            $secondPath = $_SERVER['DOCUMENT_ROOT'] . $secondPath;
            $exhibitorsImages = glob($secondPath . '/*.{jpeg,jpg,png,webp,svg,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);
        }
        $count = 0;
        foreach($exhibitorsImages as $image){
            if($image_count != false && $count >= $image_count){
                break;
            } else {
                $exhibitors_path[] = substr($image, strpos($image, '/doc/'));
                $count++;
            }
        }

        return $exhibitors_path;
    }

    /**
     * Laguage check for text
     *
     * @param bool $logo_color schould logo be in color.
     * @return string
     */
    public static function findBestFile($file_path){
        $filePaths = array(
            '.webp',
            '.jpg',
            '.png'
        );

        foreach($filePaths as $com){
            if(file_exists(ABSPATH . $file_path . $com)){
                return $file_path . $com;
            }
        }
    }
    /**
     * Trade fair date existance check
     *
     * @return bool
     */
    public static function isTradeDateExist() {

        $seasons = ["nowa data", "wiosna", "lato", "jesień", "zima", "new date", "spring", "summer", "autumn", "winter"];
        $trade_date_lower = strtolower(do_shortcode('[trade_fair_date]'));

        // Przeszukiwanie tablicy w poszukiwaniu pasującego sezonu
        foreach ($seasons as $season) {
            if (strpos($trade_date_lower, strtolower($season)) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Output method for PWelement shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string
     */
    public function PWElementsOutput($atts, $content = null) {
        // PWelement output
        extract( shortcode_atts( array(
            'pwe_element' => '',
            'pwe_replace' => '',
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

        $output = '';

        if ($this->findClassElements()[$pwe_element]){
            require_once plugin_dir_path(__FILE__) . $this->findClassElements()[$pwe_element];

            if (class_exists($pwe_element)) {
                $output_class = new $pwe_element;
                $output .= $output_class->output($atts, $content);
            } else {
                // Log if the class doesn't exist
                echo '<script>console.log("Class '. $pwe_element .' does not exist")</script>';
                $output .= '';
            }
        } else {
            echo '<script>console.log("File with class ' . $pwe_element .' does not exist")</script>';
        }

        $output = do_shortcode($output);

        $file_cont = '<div class="pwelement pwelement_'.self::$rnd_id.'">' . $output . '</div>';

        if ($input_replace_array_html && $output_replace_array_html) {
            $file_cont = str_replace($input_replace_array_html, $output_replace_array_html, $file_cont);
        }

        return $file_cont;
    }
}