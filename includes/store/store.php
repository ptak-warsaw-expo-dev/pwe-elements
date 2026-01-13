<?php

class PWEStore extends PWECommonFunctions {

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        // Hook actions
        add_action('wp_enqueue_scripts', array($this, 'addingStyles'));
        add_action('wp_enqueue_scripts', array($this, 'addingScripts'));
        
        add_action('init', array($this, 'initVCMapPWEStore'));
        add_shortcode('pwe_store', array($this, 'PWEStoreOutput'));
    }

    /**
     * Initialize VC Map Elements.
     */
    public function initVCMapPWEStore() {
        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __('PWE Store', 'pwe_store'),
                'base' => 'pwe_store',
                'category' => __('PWE Elements', 'pwe_store'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'admin_enqueue_js' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendscript.js',
            ));
        }
    }

    public function fairs_array() { 
        $pwe_groups_data = self::get_database_groups_data(); 

        $edition_1 = [];
        $edition_2 = [];
        $edition_3 = [];
        $edition_b2c = [];

        foreach ($pwe_groups_data as $group) {
            if ($group->fair_group == "gr1") {
                $edition_1[] = $group->fair_domain;
            }
            if ($group->fair_group == "gr2") {
                $edition_2[] = $group->fair_domain;
            }
            if ($group->fair_group == "gr3") {
                $edition_3[] = $group->fair_domain;
            }
            if ($group->fair_group == "b2c") {
                $edition_b2c[] = $group->fair_domain;
            }
        }
        
        $editions = [
            "1"   => $edition_1,
            "2"   => $edition_2,
            "3"   => $edition_3,
            "b2c" => $edition_b2c
        ];
        
        $formatted_editions = [];
         
        foreach ($editions as $edition_key => $domains) {
            foreach ($domains as $domain) {
                $formatted_editions["edition_" . $edition_key][] = [
                    "domain"  => $domain,
                    "name"    => do_shortcode("[pwe_name_pl domain=\"$domain\"]"),
                    "desc"    => do_shortcode("[pwe_desc_pl domain=\"$domain\"]"),
                    "date"    => do_shortcode("[pwe_date_start domain=\"$domain\"]"),
                    "edition" => $edition_key
                ];
            }
        }

        return $formatted_editions;
    }

    public function addingStyles(){
        $css_file = plugins_url('assets/style.css', __FILE__);
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/style.css');
        wp_enqueue_style('pwe-store-css', $css_file, array(), $css_version);

        $css_file_feedback = plugins_url('assets/feedback.css', __FILE__);
        $css_version_feedback = filemtime(plugin_dir_path(__FILE__) . 'assets/feedback.css');
        wp_enqueue_style('pwe-store-feedback-css', $css_file_feedback, array(), $css_version_feedback);
    }

    public function addingScripts(){
        $pwe_groups_data = self::get_database_groups_data(); 

        foreach ($pwe_groups_data as $group) {
            if ($group->fair_domain == $_SERVER['HTTP_HOST']) {
                $current_group = $group->fair_group;
            }
        }

        $store_js_array = array(
            'api_key' => password_hash(PWE_API_KEY_4, PASSWORD_DEFAULT),
            'current_group' => $current_group
        );

        $js_file = plugins_url('assets/script.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/script.js');
        wp_enqueue_script('pwe-store-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script( 'pwe-store-js', 'store_js', $store_js_array );

        $js_file_feedback = plugins_url('assets/feedback.js', __FILE__);
        $js_version_feedback = filemtime(plugin_dir_path(__FILE__) . 'assets/feedback.js');
        wp_enqueue_script('pwe-store-feedback-js', $js_file_feedback, array('jquery'), $js_version_feedback, true);
    }

    public function price($product, $store_options, $pwe_meta_data, $category, $current_domain, $num_only = false) {
        if ($category == $product->prod_category && (self::lang_pl() ? !empty($product->prod_title_short_pl) : !empty($product->prod_title_short_en))) {
            foreach ($store_options as $domain_options) {
                if ($domain_options['domain'] === $current_domain) {
                    if (!empty($domain_options['options'])) {
                        $options = json_decode($domain_options['options'], true);
            
                        if (isset($options['products'])) {
                            foreach ($options['products'] as $key => $option) {
                                if ($product->prod_slug == $key) {
                                    // Prices
                                    $new_price_pl = $option['prod_price_pl'] ? $option['prod_price_pl'] : "";
                                    $new_price_en = $option['prod_price_en'] ? $option['prod_price_en'] : "";
            
                                    // Prices descriptions
                                    $new_price_desc_pl = $option['prod_price_desc_pl'] ? $option['prod_price_desc_pl'] : "";
                                    $new_price_desc_en = $option['prod_price_desc_en'] ? $option['prod_price_desc_en'] : "";
                                }
                            }
                        }

                        if (isset($options['options']['margin'])) {
                            $price_margin = ($options['options']['margin']);
                        }
                    }
        
                    break;
                }
            }
            
            $updated_price_pl = !empty($new_price_pl) ? $new_price_pl : $product->prod_price_pl;
            if (!empty($new_price_en)) {
                $updated_price_en = $new_price_en;
            } elseif (!empty($new_price_pl)) {
                $updated_price_en = $new_price_pl / $pwe_meta_data[0]->meta_data;
            } elseif (!empty($product->prod_price_en)) {
                $updated_price_en = $product->prod_price_en;
            } else {
                $updated_price_en = $product->prod_price_pl / $pwe_meta_data[0]->meta_data;
            }
            $updated_price_en = self::round_price($updated_price_en);

            $updated_price_desc_pl = !empty($new_price_desc_pl) ? $new_price_desc_pl : $product->prod_price_desc_pl;
            $updated_price_desc_en = !empty($new_price_desc_en) ? $new_price_desc_en : $product->prod_price_desc_en;

            $final_price_desc = self::lang_pl() ? $updated_price_desc_pl : $updated_price_desc_en;

            if ($price_margin && empty($new_price_pl)) {
                $updated_price_pl = $updated_price_pl + ($updated_price_pl * ($price_margin / 100));
                $updated_price_en = $updated_price_en + ($updated_price_en * ($price_margin / 100));

                $updated_price_pl = self::round_price($updated_price_pl);
                $updated_price_en = self::round_price($updated_price_en);
            }

            $final_price = "";

            if (!empty($updated_price_pl)) {
                $eur_price = $updated_price_pl / $pwe_meta_data[0]->meta_data;
                $eur_price = self::round_price($eur_price);

                $final_price = number_format((self::lang_pl() ? $updated_price_pl : (!empty($updated_price_en) ? $updated_price_en : $eur_price)), 0, ',', ' ') . ( self::lang_pl() ? ' zł ' : ' € ' );
            }
            
            $product_price = $final_price . ($num_only == true ? '' : $final_price_desc); 
            $product_price = $num_only == true ? preg_replace('/[^0-9\.]/', '', $product_price) : $product_price;
            
            return $product_price;
        }
    }

    public function round_price($price) {
        if ($price >= 1000) {
            return round($price, -1);
        } else if ($price < 1000 && $price >= 100) {
            return round($price, -1);
        } else if ($price >= 50 && $price < 100) {
            return round($price, -1);
        } else {
            return round($price);
        }
    }

    public function image_exists($url) {
        $headers = @get_headers($url, 1);
        return ($headers && strpos($headers[0], '200') !== false 
                && isset($headers["Content-Type"]) 
                && strpos($headers["Content-Type"], "image/") === 0);
    }
    
    public function PWEStoreOutput() {   
        $pwe_store_data = self::get_database_store_data(); 
        $pwe_store_packages_data = self::get_database_store_packages_data();
        $pwe_meta_data = self::get_database_meta_data();   

        $pwe_store_data_filtered = [];
        foreach ($pwe_store_data as $item) {
            if ((strpos($item->prod_groups, do_shortcode('[trade_fair_group]')) !== false || empty($item->prod_groups)) && strpos($item->prod_groups, 'hide') === false) {
                $pwe_store_data_filtered[] = $item;
            }
        }
        $pwe_store_data = $pwe_store_data_filtered;

        $categories = [];
        foreach ($pwe_store_data as $item) {
            $category = $item->prod_category;

            // Add the category to the array if it's not there yet
            if (!in_array($category, $categories)) {
                $categories[] = $category;
            }
        }

        $packages_categories = [];
        foreach ($pwe_store_packages_data as $item) {
            $packages_category = $item->packs_category;

            // Add the category to the array if it's not there yet
            if (!in_array($packages_category, $packages_categories)) {
                $packages_categories[] = $packages_category;
            }
        }

        $fairs_json = self::json_fairs();
        $store_options = [];
        foreach ($fairs_json as $fair) {
            $store_options[] = array(
                "domain" => $fair["domain"],
                "options" => $fair["shop"]
            );
        }

        // Get current domain
        $current_domain = do_shortcode('[trade_fair_domainadress]');

        $output = '
        <div id="pweStore" class="pwe-store '. explode('.', $current_domain)[0] .'">';
            
            require_once plugin_dir_path(__FILE__) . 'parts/store_header.php';

            require_once plugin_dir_path(__FILE__) . 'parts/store_product_details.php';

            require_once plugin_dir_path(__FILE__) . 'parts/store_cat_filter.php';

            require_once plugin_dir_path(__FILE__) . 'parts/store_product_card.php';

            require_once plugin_dir_path(__FILE__) . 'parts/store_fairs.php';

            require_once plugin_dir_path(__FILE__) . 'parts/store_feedback.php';

        $output .= '
        </div>';

        require_once plugin_dir_path(__FILE__) . 'parts/store_modals.php';

        // if (current_user_can( "administrator" )) {
        //     $pwe_groups_data = self::get_database_groups_data(); 
        //     $pwe_groups_data_contact = self::get_database_groups_contacts_data(); 

        //     $domains = [];
        //     foreach ($pwe_groups_data as $group) {
        //         if ($group->fair_group == "gr2") {
        //             $domains[] = $group->fair_domain;
        //         }
        //     }

        //     $pwe_groups_data_json_encode = json_encode($pwe_groups_data);
        //     $pwe_groups_data_contact_json_encode = json_encode($pwe_groups_data_contact);
        //     $pwe_store_data_json_encode = json_encode($pwe_store_data);
        //     $pwe_store_data_options_json_encode = json_encode($store_options);
        //     $pwe_store_packages_data_json_encode = json_encode($pwe_store_packages_data);
        //     $output .= '
        //     <script>
        //         document.addEventListener("DOMContentLoaded", function () {
        //             const pweGroups = ' . $pwe_groups_data_json_encode . ';
        //             const pweGroupsContact = ' . $pwe_groups_data_contact_json_encode . ';
        //             const storeData = ' . $pwe_store_data_json_encode . ';
        //             const storeDataOptions = ' . $pwe_store_data_options_json_encode . ';
        //             const storePackagesData = ' . $pwe_store_packages_data_json_encode . ';
                    
        //             console.log(pweGroups);
        //             console.log(pweGroupsContact);
        //             console.log(storeData);
        //             console.log(storeDataOptions);
        //             console.log(storePackagesData);
        //         });
        //     </script>';
        // }

        $output = do_shortcode($output);  
        
        return $output;
    }  
}