<?php
add_action('admin_init', 'pwe_register_shortcodes_settings');

function pwe_register_shortcodes_settings() {
    register_setting('pwe_shortcodes_options_group', 'pwe_shortcodes_options');

    add_settings_section(
        'pwe_shortcodes_main_section',
        'PWE Shortcodes Settings',
        'pwe_shortcodes_section_callback',
        'pwe-shortcodes-settings'
    );

    add_settings_field(
        'pwe_shortcodes_fair_year',
        'Rok targów',
        'pwe_sc_fair_year_shortcode_field',
        'pwe-shortcodes-settings',
        'pwe_shortcodes_main_section'
    );
    
}

function pwe_shortcodes_section_callback() {
    echo '<p>Customize your shortcodes settings here.</p>';
}

function pwe_sc_fair_year_shortcode_field() {
    $options = get_option('pwe_shortcodes_options');

    $pwe_date_start = shortcode_exists("pwe_date_start") ? do_shortcode('[pwe_date_start]') : "";
    $pwe_date_start_available = (empty(get_option('pwe_general_options', [])['pwe_dp_shortcodes_unactive']) && !empty($pwe_date_start) && $pwe_date_start !== "");
    $result = $pwe_date_start_available ? date('Y', strtotime($pwe_date_start)) : get_option('trade_fair_catalog_year');

    $year = isset($options['pwe_sc_fair_year']) ? esc_attr($options['pwe_sc_fair_year']) : '';
    echo '<input 
            type="text" 
            id="pwe_sc_fair_year" name="pwe_shortcodes_options[pwe_sc_fair_year]" value="' . $year . '" />';
}

function pwe_sc_fair_year_shortcode_render() {
    $options = get_option('pwe_shortcodes_options');
    $year = isset($options['pwe_sc_fair_year']) ? esc_html($options['pwe_sc_fair_year']) : '';
    return $year;
}




// function pwe_register_shortcodes() {
//     add_shortcode('pwe_sc_fair_year', 'pwe_sc_fair_year_shortcode_render');
// } 

// add_action('init', 'pwe_register_shortcodes');







// Krok 1: Zarejestruj nazwę zmiennej, którą będzie rozpoznawał Yoast
add_filter('wpseo_register_extra_replacements', function() {
    return ['%%pwe_sc_fair_year%%'];
});

// Krok 2: Podstaw prawdziwą wartość za shortcode
add_filter('wpseo_replacements', function($replacements) {
    $options = get_option('pwe_shortcodes_options');
    $year = isset($options['pwe_sc_fair_year']) ? esc_html($options['pwe_sc_fair_year']) : '';
    $replacements['%%pwe_sc_fair_year%%'] = $year;
    return $replacements;
});

