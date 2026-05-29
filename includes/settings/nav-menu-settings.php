<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_init', 'pwe_register_menu_settings');

function pwe_register_menu_settings()
{
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
}

function pwe_menu_section_callback()
{
    echo '<p>Customize your menu settings here.</p>';
}

function pwe_menu_active_callback()
{
    $options = get_option('pwe_menu_options', []);
    $is_checked = isset($options['pwe_menu_active']) && (string) $options['pwe_menu_active'] === '1';

    echo '<input type="hidden" name="pwe_menu_options[pwe_menu_active]" value="0">';
    echo '<label>';
    echo '<input type="checkbox" name="pwe_menu_options[pwe_menu_active]" value="1" ' . checked($is_checked, true, false) . '>';
    echo ' Włącz niestandardowe menu PWE.';
    echo '</label>';
}

function pwe_menu_transparent_callback()
{
    $options = get_option('pwe_menu_options', []);
    $is_checked = isset($options['pwe_menu_transparent']) && (string) $options['pwe_menu_transparent'] === '1';

    echo '<input type="hidden" name="pwe_menu_options[pwe_menu_transparent]" value="0">';
    echo '<label>';
    echo '<input type="checkbox" name="pwe_menu_options[pwe_menu_transparent]" value="1" ' . checked($is_checked, true, false) . '>';
    echo ' Ustaw transparentne menu tylko na stronie głównej.';
    echo '</label>';
}
