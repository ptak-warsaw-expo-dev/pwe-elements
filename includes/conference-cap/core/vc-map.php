<?php

/**
 * Visual Composer/WPBakery mapping for the legacy shortcode contract.
 */
final class PWE_Conference_Cap_VC_Map {

    /**
     * Register the VC element.
     */
    public static function register(): void {
        if (!class_exists('Vc_Manager')) {
            return;
        }

        vc_map(array(
            'name'              => __('PWE Conference CAP', 'pwe_exhibitor_generator'),
            'base'              => 'pwe_conference_cap',
            'category'          => __('PWE Elements', 'pwe_exhibitor_generator'),
            'admin_enqueue_css' => plugin_dir_url(dirname(__DIR__)) . 'backend/backendstyle.css',
            'class'             => 'costam',
            'params'            => self::params(),
        ));
    }

    /**
     * Keep all historical WPBakery parameters available.
     */
    public static function params(): array {
        return array(
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Display Slug', 'pwe_conference_cap'),
                'param_name' => 'conference_cap_conference_display_slug',
                'description' => __('Specify conference slug to display ', 'pwe_conference_cap'),
                'save_always' => true,
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-12 no-vc-param-name',
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Display domains', 'pwe_conference_cap'),
                'param_name' => 'conference_cap_domains',
                'description' => __('Podaj liste domen oddzielonych przecinkiem, np.: warsawexpo.eu,mr.glasstec.pl. Tylko konferencje przypisane do tych domen beda widoczne.', 'pwe_conference_cap'),
                'save_always' => true,
                'admin_label' => true,
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Archive', 'pwe_conference_cap'),
                'param_name' => 'conference_cap_conference_archive',
                'description' => __('Correct alias. If both archive fields are set, this value wins.', 'pwe_conference_cap'),
                'save_always' => true,
                'admin_label' => true,
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Arichive', 'pwe_conference_cap'),
                'param_name' => 'conference_cap_conference_arichive',
                'description' => __('Legacy typo kept for backward compatibility.', 'pwe_conference_cap'),
                'save_always' => true,
                'admin_label' => true,
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Select conference mode', 'pwe_conference_cap'),
                'param_name' => 'conference_cap_conference_mode',
                'save_always' => true,
                'value' => array(
                    'Mode' => '',
                    'Full Mode' => 'PWEConferenceCapFullMode',
                    'Full Mode Speakers' => 'PWEConferenceCapFullMode2',
                    'Simple Mode' => 'PWEConferenceCapSimpleMode',
                    'Medal Ceremony' => 'PWEConferenceCapMedalCeremony',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('One Conference Mode', 'pwe_conference_cap'),
                'param_name' => 'conference_cap_one_conference_mode',
                'description' => __('Mode disables the top navigation and sets the first conference visible', 'pwe_conference_cap'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_conference_cap') => 'true'),
            ),
            array(
                'type' => 'param_group',
                'group' => 'Custom Html',
                'heading' => __('Custom Html', 'pwe_conference_cap'),
                'param_name' => 'conference_cap_html',
                'params' => array(
                    array('type' => 'textfield', 'heading' => __('Conference slug', 'pwe_conference_cap'), 'param_name' => 'conference_cap_html_conf_slug', 'save_always' => true, 'admin_label' => true),
                    array('type' => 'dropdown', 'heading' => __('Position', 'pwe_conference_cap'), 'param_name' => 'conference_cap_html_position', 'value' => array(__('After header', 'pwe_conference_cap') => 'after_header', __('After patrons', 'pwe_conference_cap') => 'after_patrons', __('After location', 'pwe_conference_cap') => 'after_location', __('After title', 'pwe_conference_cap') => 'after_title', __('Before day', 'pwe_conference_cap') => 'before_day', __('After day', 'pwe_conference_cap') => 'after_day', __('After all', 'pwe_conference_cap') => 'after_all'), 'save_always' => true, 'admin_label' => true),
                    array('type' => 'textfield', 'heading' => __('Conference day', 'pwe_conference_cap'), 'param_name' => 'conference_cap_html_day', 'save_always' => true),
                    array('type' => 'textfield', 'heading' => __('Element ID', 'pwe_conference_cap'), 'param_name' => 'conference_cap_html_element_id', 'save_always' => true),
                    array('type' => 'textarea', 'heading' => __('Custom html', 'pwe_conference_cap'), 'param_name' => 'conference_cap_html_code', 'save_always' => true),
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'Manual Conferences',
                'heading' => __('Manually Added Conferences', 'pwe_conference_cap'),
                'param_name' => 'manual_conferences',
                'description' => __('Add the class .konferencja and id conference_ID to the element.', 'pwe_conference_cap'),
                'params' => array(
                    array('type' => 'attach_image', 'heading' => __('Conference Image', 'pwe_conference_cap'), 'param_name' => 'manual_conf_img', 'save_always' => true),
                    array('type' => 'textfield', 'heading' => __('Conference ID (Slug)', 'pwe_conference_cap'), 'param_name' => 'manual_conf_id', 'save_always' => true, 'admin_label' => true),
                    array('type' => 'textfield', 'heading' => __('Conference url', 'pwe_conference_cap'), 'param_name' => 'manual_conf_url', 'save_always' => true, 'admin_label' => true),
                ),
            ),
        );
    }
}

