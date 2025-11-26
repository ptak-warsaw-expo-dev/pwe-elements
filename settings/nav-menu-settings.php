<?php
add_action('admin_init', 'pwe_register_menu_settings');

function pwe_register_menu_settings() {
    register_setting('pwe_menu_options_group', 'pwe_menu_options');

    add_settings_section(
        'pwe_menu_main_section',
        'PWE Menu Settings',
        'pwe_menu_section_callback',
        'pwe-menu-settings'
    );

    add_settings_field(
        'pwe_menu_active',
        'Active menu',
        'pwe_menu_active_callback',
        'pwe-menu-settings',
        'pwe_menu_main_section'
    );

    add_settings_field(
        'pwe_menu_transparent',
        'Transparent menu (Homepage only)',
        'pwe_menu_transparent_callback',
        'pwe-menu-settings',
        'pwe_menu_main_section'
    );

    // add_settings_field(
    //     'pwe_menu_logo',
    //     'Menu Logo',
    //     'pwe_menu_logo_callback',
    //     'pwe-menu-settings',
    //     'pwe_menu_main_section'
    // );

    // add_settings_field(
    //     'pwe_menu_color',
    //     'Menu Background Color',
    //     'pwe_menu_color_callback',
    //     'pwe-menu-settings',
    //     'pwe_menu_main_section'
    // );
}

function pwe_menu_section_callback() {
    echo '<p>Customize your menu settings here.</p>';
}

function pwe_menu_active_callback() {
    $options = get_option('pwe_menu_options');
    $is_checked = isset($options['pwe_menu_active']) && $options['pwe_menu_active'] === '1';
    echo '<input type="checkbox" name="pwe_menu_options[pwe_menu_active]" value="1" ' . checked($is_checked, true, false) . '>';
}

function pwe_menu_transparent_callback() {
    $options = get_option('pwe_menu_options');
    $is_checked = isset($options['pwe_menu_transparent']) && $options['pwe_menu_transparent'] === '1';
    echo '<input type="checkbox" name="pwe_menu_options[pwe_menu_transparent]" value="1" ' . checked($is_checked, true, false) . '>';
}

// function pwe_menu_logo_callback() {
//     $options = get_option('pwe_menu_options');
//     echo '<input type="text" name="pwe_menu_options[pwe_menu_logo]" value="' . esc_attr($options['pwe_menu_logo'] ?? '') . '" placeholder="Logo URL">';
// }

// function pwe_menu_color_callback() {
//     $options = get_option('pwe_menu_options');
//     echo '<input type="color" name="pwe_menu_options[pwe_menu_color]" value="' . esc_attr($options['pwe_menu_color'] ?? '#ffffff') . '">';
// }
