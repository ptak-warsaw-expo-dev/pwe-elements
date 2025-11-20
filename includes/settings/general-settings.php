<?php
add_action('admin_init', 'pwe_register_general_settings');

function pwe_register_general_settings() {
    register_setting('pwe_general_options_group', 'pwe_general_options');

    add_settings_section(
        'pwe_general_main_section',
        'PWE General Settings',
        'pwe_general_section_callback',
        'pwe-general-settings'
    );

    add_settings_field(
        'pwe_dp_shortcodes_unactive',
        'Deactivating get data to shortcodes',
        'pwe_dp_shortcodes_unactive_callback',
        'pwe-general-settings',
        'pwe_general_main_section'
    );
}

function pwe_general_section_callback() {
    echo '<p>General settings</p>';
}

function pwe_dp_shortcodes_unactive_callback() {
    $options = get_option('pwe_general_options');
    $is_checked = isset($options['pwe_dp_shortcodes_unactive']) && $options['pwe_dp_shortcodes_unactive'] === '1';
    echo '<input type="checkbox" name="pwe_general_options[pwe_dp_shortcodes_unactive]" value="1" ' . checked($is_checked, true, false) . '>';
}