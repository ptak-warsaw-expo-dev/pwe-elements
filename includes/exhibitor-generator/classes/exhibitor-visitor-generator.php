<?php

/**
 * Class PWEExhibitorVisitorGenerator
 *
 * This class adding creating html for exhibitors to easy register their clients
 */
class PWEExhibitorVisitorGenerator extends PWEExhibitorGenerator {

    /**
     * Constructor method.
     * Calls parent constructor
     */
    public function __construct() {
        parent::__construct();

        add_action('init', function() {
    add_filter('gform_allow_html_field_label', '__return_true');
});
    }

    /**
     * Static method to check if there are minimum one more day before fair starts
     *
     * @return bool starting date did not pass
     */
    public static function fairStartDateCheck() {
        $today_date = new DateTime();

        $fair_start_date = new DateTime(do_shortcode('[trade_fair_datetotimer]'));

        $date_diffarance = $today_date->diff($fair_start_date);
        // Check if date doesn't past yet and there is minimum one more day befor fair starts
        if($date_diffarance->invert == 0 && $date_diffarance->days > 0){
            return true;
        }
        return false;
    }

    /**
     * Static method to generate the HTML output.
     * Creating modal form to upload file with visitors data
     *
     * @param array @atts options
     * @return string html output
     */
    public static function senderFlowChecker() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mass_exhibitors_invite_query';
        if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") == $table_name) {
            $count_new = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_name WHERE status = %s",
                    'new'
                )
            );
        } else {
            return true;
        }

        $today_date = new DateTime();
        $fair_start_date = new DateTime(do_shortcode('[trade_fair_datetotimer]'));

        $date_diffarance = $today_date->diff($fair_start_date);

        if($date_diffarance->invert == 0){
            $hours_remaining = ($date_diffarance->days * 24 + $date_diffarance->h) - 34;
            $total_email_capacity = $hours_remaining * 100;

            $canSend = $total_email_capacity - $count_new;

            if($canSend < -2000 || $canSend > 0){
                echo '<script>console.log('.$canSend.')</script>';
            }

            if($total_email_capacity > $count_new){
                return true;
            }
        }
        return false;
    }


    /**
     * Static method to generate the HTML output for the PWE Element.
     * Additionaly can display personated page for Exhibitors registered in Exhibitors Catalog
     *
     * @param array @atts options
     * @return string html output
     */
    public static function output($atts) {
        extract( shortcode_atts( array(
            'generator_form_id' => '',
            'exhibitor_generator_html_text' => '',
            'generator_catalog' => '',
            'generator_patron' => '',
        ), $atts ));

        $all_exhibitors = array();
        $company_array = array();

        $catalog_array = self::catalog_data();

        if(!empty($catalog_array)){
            $all_exhibitors = reset($catalog_array)['Wystawcy'];
        }

        $pweGeneratorWebsite = strpos($_SERVER['REQUEST_URI'], '/generator-odwiedzajacych-pwe') !== false || strpos($_SERVER['REQUEST_URI'], '/en/exhibitor-generator-pwe/') !== false;
        // Check if ?katalog = * exists in the URL
        if(isset($_GET['katalog'])){
            // Verify if the exhibitor catalog is connected to the site
            if ($generator_catalog){
                // Generate personal exhibitor information based on the catalog number
                $catalog_array = self::catalog_data($_GET['katalog']);
                $company_array['exhibitor_token'] = $_GET['katalog'];
                $company_array['exhibitor_heder'] = '';
                $company_array['catalog_logo'] = self::$exhibitor_logo_url = $catalog_array['URL_logo_wystawcy'];
                $company_array['exhibitor_name'] = self::$exhibitor_name = $catalog_array['Nazwa_wystawcy'];
                $company_array['exhibitor_desc'] = self::$exhibitor_desc = $catalog_array['Opis_pl'];
            }
        // Check if ?wystawca=* exists in the URL
        } else if(isset($_GET['wystawca'])){
            // Generate personal exhibitor information based on PWElement config name
            $company_edition = vc_param_group_parse_atts( $atts['company_edition'] );
            foreach ($company_edition as $company){
                if(strtolower($_GET['wystawca']) == strtolower($company['exhibitor_token'])){
                    $company_array = $company;
                    break;
                }
            }
            self::$exhibitor_name = $company_array['exhibitor_name'];
            self::$exhibitor_desc = $company_array['exhibitor_desc'];
            self::$exhibitor_stand = $company_array['exhibitor_stand'];
            self::$exhibitor_logo_url = wp_get_attachment_url($company_array['exhibitor_logo']);
        } else {
            $catalog_array = self::catalog_data();
            if(!empty($catalog_array)){
                $all_exhibitors = reset($catalog_array)['Wystawcy'];
            }

            self::$exhibitor_logo_url = 'https://' . do_shortcode('[trade_fair_domainadress]') . '/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/logotyp_wystawcy.png';
        }

        if ($pweGeneratorWebsite) {
            self::$exhibitor_name = 'Ptak Warsaw Expo';
        }

        $send_file = plugins_url('other/mass_vip.php', dirname(dirname(dirname(__FILE__))));

        // Decode optional text for the page
        $generator_html_text_decoded = base64_decode($exhibitor_generator_html_text);
        $generator_html_text_decoded = urldecode($generator_html_text_decoded);
        $generator_html_text_content = wpb_js_remove_wpautop($generator_html_text_decoded, true);

        $domain = $parsed = parse_url(site_url())['host'];
        $fair_data = PWECommonFunctions::get_database_fairs_data($domain);
        switch (strtolower($fair_data[0]->fair_group)) {
            case 'gr1':
                require_once plugin_dir_path(__DIR__) . 'assets/visitors_gr1.php';
                return render_gr1($atts, $all_exhibitors, $pweGeneratorWebsite);

            case 'gr2':
            case 'b2c': // 👈 dodany case b2c
                $all_partners = PWECommonFunctions::get_database_logotypes_data();
                $all_conferences = PWECommonFunctions::get_database_conferences_data();
                require_once plugin_dir_path(__DIR__) . 'assets/visitors_gr2.php';
                return render_gr2(
                    $atts,
                    $all_exhibitors,
                    $all_partners,
                    $all_conferences,
                    $pweGeneratorWebsite,
                    $domain
                );

            case 'gr3':
                require_once plugin_dir_path(__DIR__) . 'assets/visitors_gr3.php';
                return render_gr3($atts, $all_exhibitors, $pweGeneratorWebsite);
        }
    }
}