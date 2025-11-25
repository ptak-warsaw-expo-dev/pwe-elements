<?php
add_action('admin_menu', 'pwe_elements_page');

function pwe_elements_page() {
    add_menu_page(
        'PWE Elements',
        'PWE Elements',
        'manage_options',
        'pwe-elements',
        'pwe_elements_render',
        'dashicons-layout',
        3
    );

    // Submenu for "General Settings"
    add_submenu_page(
        'pwe-elements', // Parent slug
        'General Settings', // Page title
        'General Settings', // Menu title
        'manage_options', // Capability
        'pwe-elements-general', // Menu slug
        'pwe_general_settings_render' // Callback function
    );
    // Submenu for "Menu Settings"
    add_submenu_page(
        'pwe-elements',
        'Menu Settings',
        'Menu Settings',
        'manage_options',
        'pwe-elements-menu',
        'pwe_menu_settings_render'
    );

    // // Submenu for "Shortcodes"
    // add_submenu_page(
    //     'pwe-elements',
    //     'Shortcodes',
    //     'Shortcodes (TEST)',
    //     'manage_options',
    //     'pwe-elements-shortcodes',
    //     'pwe_shortcodes_settings_render'
    // );
}

function pwe_elements_render() {
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    echo '
    <div class="wrap">
        <h1>Settings PWE</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=pwe-elements&tab=general" class="nav-tab '. ($active_tab === 'general' ? 'nav-tab-active' : '') .'">General</a>
            <a href="?page=pwe-elements&tab=menu" class="nav-tab '. ($active_tab === 'menu' ? 'nav-tab-active' : '') .'">Menu</a>
            <a href="?page=pwe-elements&tab=shortcodes" class="nav-tab '. ($active_tab === 'shortcodes' ? 'nav-tab-active' : '') .'">Shortcodes</a>
        </h2>
        <form method="post" action="options.php">';
            if ($active_tab === 'general') {
                settings_fields('pwe_general_options_group');
                do_settings_sections('pwe-general-settings');
            } else if ($active_tab === 'menu') {
                settings_fields('pwe_menu_options_group');
                do_settings_sections('pwe-menu-settings');
            } else if ($active_tab === 'shortcodes') {
                settings_fields('pwe_shortcodes_options_group');
                do_settings_sections('pwe-shortcodes-settings');
            } 
            submit_button();
            echo '
        </form>
    </div>';
}

function pwe_general_settings_render() {
    echo '
    <div class="wrap">
        <h1>General Settings</h1>
        <form method="post" action="options.php">';
            settings_fields('pwe_general_options_group');
            do_settings_sections('pwe-general-settings');
            submit_button();
    echo '
        </form>
    </div>';
}
function pwe_menu_settings_render() {
    echo '
    <div class="wrap">
        <h1>Menu Settings</h1>
        <form method="post" action="options.php">';
            settings_fields('pwe_menu_options_group');
            do_settings_sections('pwe-menu-settings');
            submit_button();
    echo '
        </form>
    </div>';
}

// function pwe_shortcodes_settings_render() {
//     echo '
//     <div class="wrap">
//         <h1>Shortcodes Settings</h1>
//         <form method="post" action="options.php">';
//             settings_fields('pwe_shortcodes_options_group');
//             do_settings_sections('pwe-shortcodes-settings');
//             submit_button();

//             echo '
//         </form>
//     </div>';
// }
 