<?php

class pweNavMenu extends PWECommonFunctions {

    public function __construct() {
        
        $pwe_menu_options = get_option('pwe_menu_options', []);
		if (isset($pwe_menu_options['pwe_menu_active']) && $pwe_menu_options['pwe_menu_active']) {
            // Hook actions
            add_action('wp_enqueue_scripts', array($this, 'addingStyles'));
            add_action('wp_enqueue_scripts', array($this, 'addingScripts'));

            // Registering a function
            add_action('wp_head', array($this, 'pwe_nav_menu'), 5);   
        }
    }

    // Styles
    public function addingStyles(){
        $css_file = plugins_url('assets/style.css', __FILE__);
        $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/style.css');
        wp_enqueue_style('nav-menu-css', $css_file, array(), $css_version);
    }

    // Scripts
    public function addingScripts(){
        $menu_js_array = array(
            'menu_transparent' => !empty(get_option('pwe_menu_options', [])['pwe_menu_transparent']) ? "true" : "false",
            'trade_fair_datetotimer' => do_shortcode('[trade_fair_datetotimer]'),
            'trade_fair_enddata' => do_shortcode('[trade_fair_enddata]')
        );

        $js_file = plugins_url('assets/script.js', __FILE__);
        $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/script.js');
        wp_enqueue_script('nav-menu-js', $js_file, array('jquery'), $js_version, true);
        wp_localize_script( 'nav-menu-js', 'menu_js', $menu_js_array );
    }

    public function pwe_nav_menu() { 

        // Get menu locations
        $locations = get_nav_menu_locations();
    
        // Check if 'primary' location exists
        if (!isset($locations['primary'])) {
            return '<script>console.error("Menu location `primary` not found.");</script>';
        }
    
        // Get menu ID for 'primary' location
        $menu_id = $locations['primary']; 

        // WPML - Get the translated menu ID for the current language
        if (function_exists('icl_object_id')) {
            $menu_id = icl_object_id($menu_id, 'nav_menu', true, ICL_LANGUAGE_CODE);
        }
    
        // Get menu items
        $menu = wp_get_nav_menu_items($menu_id);
        if (empty($menu) || !is_array($menu)) {
            return '<script>console.error("No menu items found or invalid menu structure for menu ID: '. $menu_id .'");</script>';
        }
    
        // Organize menu items by ID
        $menu_items = array();
        foreach ($menu as $item) {
            if (isset($item->ID)) {
                $menu_items[$item->ID] = $item;
            } else {
                return '<script>console.error("Menu item without valid ID found.");</script>';
            }
        }

    
        $output = '';

        if (empty(get_option('pwe_menu_options', [])['pwe_menu_transparent'])) {
            $output .= '
            <style>
            body.home .pwe-menu {
                background-color: var(--accent-color);
            }
            </style>';
        }
        
        $output .= '
        <header id="pweMenu" class="pwe-menu"> 
            <a style="opacity: 0; width: 0; height: 0;"  href="#main-content" class="skip-link">Skip to main content</a>
            <div class="pwe-menu__wrapper">
                <div class="pwe-menu__main-logo">
                    <a class="pwe-menu__main-logo-ptak ' . (file_exists($_SERVER['DOCUMENT_ROOT'] . self::languageChecker('/doc/logo-x-pl.webp', '/doc/logo-x-en.webp')) ? "hidden-mobile" : "") . '" target="_blank" href="https://warsawexpo.eu'. self::languageChecker('/', '/en/') .'">
                        <img data-no-lazy="1" src="/wp-content/plugins/pwe-media/media/logo_pwe.webp" alt="logo ptak">
                    </a>
                    <a class="pwe-menu__main-logo-fair" href="'. self::languageChecker('/', '/en/') .'">';
                        if (self::lang_pl()) {
                            $output .= '<img data-no-lazy="1" src="' . (file_exists($_SERVER['DOCUMENT_ROOT'] . "/doc/logo-x-pl.webp") ? "/doc/logo-x-pl.webp" : "/doc/favicon.webp") . '" alt="logo fair">';
                        } else {
                            $output .= '<img data-no-lazy="1" src="' . (file_exists($_SERVER['DOCUMENT_ROOT'] . "/doc/logo-x-en.webp") ? "/doc/logo-x-en.webp" : "/doc/favicon.webp") . '" alt="logo fair">';
                        }
                    $output .= '
                    </a> 
                </div>
    
                <div class="pwe-menu__container-mobile">
                    <div class="pwe-menu__register-btn">
                        <a href="'. self::languageChecker('/rejestracja/', '/en/registration/') .'">'. self::languageChecker('WEŹ UDZIAŁ', 'TAKE A PART') .'</a>
                    </div>
                    
                    <div class="pwe-menu__burger">
                        <span></span>
                    </div>
                </div>
    
                <div class="pwe-menu__container">
                    <ul class="pwe-menu__nav">';
                        
                        foreach ($menu_items as $item) {
                            if (!isset($item->menu_item_parent) || !isset($item->ID)) {
                                $output .= '<script>console.error("Invalid menu item structure detected.");</script>';
                                continue;
                            }
    
                            if ($item->menu_item_parent == 0) {
                                $has_children = !empty(array_filter($menu_items, function($child) use ($item) {
                                    return $child->menu_item_parent == $item->ID;
                                }));
    
                                $target_blank = !empty($item->target) ? 'target="_blank"' : '';
    
                                $output .= '<li class="pwe-menu__item' . ((strpos($item->ID, 'wpml') === false) ? ($has_children ? ' has-children' : '') : '') . ' ' . ($item->button ?? '') . '">';
                                $output .= '<a '. $target_blank .' href="' . esc_url($item->url) . '"> ' . wp_kses_post($item->title);
                                $output .= (strpos($item->ID, 'wpml') === false) ? ($has_children ? '<span class="pwe-menu__arrow">›</span>' : '') : '';
                                $output .= '</a>';
                                $output .= (strpos($item->ID, 'wpml') === false) ? $this->display_sub_menu($item->ID, $menu_items) : '';
                                $output .= '</li>';
                            }
                        }
    
        $output .= '</ul>'; 
                    
        $socials = ot_get_option('_uncode_social_list');

        $socials_cap = array(
            'facebook' => do_shortcode('[pwe_facebook]'),
            'instagram' => do_shortcode('[pwe_instagram]'),
            'linkedin' => do_shortcode('[pwe_linkedin]'),
            'youtube' => do_shortcode('[pwe_youtube]')
        );
       
        if ((!empty($socials)) || !empty($socials_cap)) {
            $output .= '<ul class="pwe-menu__social">';
            if (!empty($socials_cap)) {
                if (!empty($socials_cap['facebook'])) {
                    $output .= '
                    <li class="pwe-menu__social-item-link social-icon facebook">
                        <a href="'. esc_url($socials_cap['facebook']) .'" class="social-menu-link" target="_blank" aria-label="Visit our Facebook profile">
                            <i class="fa fa-facebook-square"></i>
                        </a>
                    </li>';
                }
                if (!empty($socials_cap['instagram'])) {
                    $output .= '
                    <li class="pwe-menu__social-item-link social-icon instagram">
                        <a href="'. esc_url($socials_cap['instagram']) .'" class="social-menu-link" target="_blank" aria-label="Visit our Instagram profile">
                            <i class="fa fa-instagram"></i>
                        </a>
                    </li>';
                }
                if (!empty($socials_cap['linkedin'])) {
                    $output .= '
                    <li class="pwe-menu__social-item-link social-icon linkedin">
                        <a href="'. esc_url($socials_cap['linkedin']) .'" class="social-menu-link" target="_blank" aria-label="Visit our Linkedin profile">
                            <i class="fa fa-linkedin-square"></i>
                        </a>
                    </li>';
                }
                if (!empty($socials_cap['youtube'])) {
                    $output .= '
                    <li class="pwe-menu__social-item-link social-icon youtube">
                        <a href="'. esc_url($socials_cap['youtube']) .'" class="social-menu-link" target="_blank" aria-label="Visit our Youtube profile">
                            <i class="fa fa-youtube-play"></i>
                        </a>
                    </li>';
                }
            } else if (!empty($socials)) {
                foreach ($socials as $social) { 
                    $output .= '
                    <li class="pwe-menu__social-item-link social-icon '.esc_attr($social['_uncode_social_unique_id']).'">
                        <a href="'.esc_url($social['_uncode_link']).'" class="social-menu-link" target="_blank">
                            <i class="'.esc_attr($social['_uncode_social']).'"></i>
                        </a>
                    </li>';
                }
            }
            $output .= '</ul>';
        }
    
        $output .= '
                </div>
            </div>
            <div class="pwe-menu__overlay"></div>
        </header>';
    
        return $output;
    }
    
    // Function to display submenu
    private function display_sub_menu($parent_id, $menu_items, $depth = 1) {
        // Maximum nesting depth
        $max_depth = 10;

        // Stop recursion after reaching the maximum depth
        if ($depth > $max_depth) { 
            return '<script>console.error("Maximum submenu depth reached for parent ID: '. $parent_id .'");</script>';
        }

        // Filter children for a given parent
        $children = array_filter($menu_items, function($item) use ($parent_id) { 
            return $item->menu_item_parent == $parent_id;
        });

        if (!empty($children)) {
            $output = '';
            
            $output .= '<ul class="pwe-menu__submenu">';
            foreach ($children as $child) {
                $has_submenu_children = !empty(array_filter($menu_items, function($grandchild) use ($child) {
                    return $grandchild->menu_item_parent == $child->ID;
                }));

                $target_blank = !empty($child->target) ? 'target="_blank"' : '';

                $aria_label_for_visitors = (strpos(esc_url($child->url), self::languageChecker('/dla-odwiedzajacych/', '/for-visitors/')) !== false && $has_submenu_children != true) ? 'aria-label="Dlaczego warto: dla odwiedzajacych"' : '';
                $aria_label_for_exhibitors = (strpos(esc_url($child->url), self::languageChecker('/dla-wystawcow/', '/for-exhibitors/')) !== false && $has_submenu_children != true) ? 'aria-label="Dlaczego warto: dla wystawcow"' : '';

                if (!empty($aria_label_for_visitors)) {
                    $aria_label = $aria_label_for_visitors;
                } else if ($aria_label_for_exhibitors) {
                    $aria_label = $aria_label_for_exhibitors;
                } else $aria_label = '';

                $output .= '<li class="pwe-menu__submenu-item' . ($has_submenu_children ? ' has-children' : '') . '">';
                $output .= '<a '. $target_blank .' '. $aria_label .' href="' . esc_url($child->url) . '">' . wp_kses_post($child->title) . ($has_submenu_children ? '<span class="pwe-menu__arrow">›</span>' : '') . '</a>';
                $output .= $this->display_sub_menu($child->ID, $menu_items, $depth + 1);
                $output .= '</li>';
            }
            $output .= '</ul>';

            return $output;
        }

        return '';
    }

}

// Create a class object
$pwe_nav_menu = new pweNavMenu();

/**
 * Сollapse ADMIN-BAR (Toolbar) into left-top corner.
 *
 * @version 1.0
 */
final class Kama_Collapse_Toolbar {

    public static function init(){
        add_action( 'admin_bar_init', [ __CLASS__, 'hooks' ] );
    }

    public static function hooks(){

        // remove html margin bumps
        remove_action( 'wp_head', '_admin_bar_bump_cb' );

        add_action( 'wp_head', [ __CLASS__, 'collapse_styles' ] );
    }

    public static function collapse_styles(){

        // do nothing for admin-panel.
        // Remove this if you want to collapse admin-bar in admin-panel too.
        if( is_admin() ){
            return;
        }

        ob_start();
        ?>
        <style id="kama_collapse_admin_bar">
            @media screen and ( max-width: 600px ) {
                #wpadminbar{ background:none; float:left; width:auto; height:auto; bottom:0; min-width:0 !important; }
                #wpadminbar > *{ float:left !important; clear:both !important; }
                #wpadminbar .ab-top-menu li{ float:none !important; }
                #wpadminbar .ab-top-secondary{ float: none !important; }
                #wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper{ top:0; left:100%; white-space:nowrap; }
                #wpadminbar .quicklinks>ul>li>a{ padding-right:17px; }
                #wpadminbar .ab-top-secondary .menupop .ab-sub-wrapper{ left:100%; right:auto; }
                html{ margin-top:0!important; }

                #wpadminbar{ overflow:hidden; width:auto; height:30px; }
                #wpadminbar:hover{ overflow:visible; width:auto; height:auto; background:rgba(102,102,102,.7); }

                /* the color of the main icon */
                #wp-admin-bar-<?= is_multisite() ? 'my-sites' : 'site-name' ?> .ab-item:before{ color:#797c7d; }

                /* hide wp-logo */
                #wp-admin-bar-wp-logo{ display:none; }
                #wp-admin-bar-search{ display:none; }

                /* edit for twentysixteen */
                body.admin-bar:before{ display:none; }

                body.logged-in.admin-bar { padding-top: 0 !important; }

                /* Gutenberg */
                #wpwrap .edit-post-header{ top:0; }
                #wpwrap .edit-post-sidebar{ top:56px; }
                
            }   
        </style>
        <?php
        $styles = ob_get_clean();

        echo preg_replace( '/[\n\t]/', '', $styles ) ."\n";
    }
}

Kama_Collapse_Toolbar::init();

