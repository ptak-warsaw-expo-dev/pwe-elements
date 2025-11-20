<?php

/**
 * Initialize VC Map Elements.
 */
function initVCMapHeader() {
    // Create an instance of the class to use non-static methods
    $pweHeaderInstance = new PWEHeader();

    // Check if Visual Composer is available
    if (class_exists('Vc_Manager')) {
        vc_map(array(
            'name' => __('PWE Header', 'pwe_header'),
            'base' => 'pwe_header',
            'category' => __('PWE Elements', 'pwe_header'),
            'admin_enqueue_css' => plugin_dir_url(dirname(dirname( __DIR__ ))) . 'backend/backendstyle.css',
            'admin_enqueue_js' => plugin_dir_url(dirname(dirname( __DIR__ ))) . 'backend/backendscript.js',
            'params' => array_merge(
                array(
                    // colors setup
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select text color <a href="#" onclick="yourFunction(`text_color_manual_hidden`, `text_color`)">Hex</a>', 'pwe_header'),
                        'param_name' => 'text_color',
                        'param_holder_class' => 'backend-fields backend-area-one-fifth-width',
                        'description' => __('Select text color for the element.', 'pwe_header'),
                        'value' => $pweHeaderInstance->findPalletColors(),
                        'dependency' => array(
                            'element' => 'text_color_manual_hidden',
                            'value' => array(''),
                            'callback' => "hideEmptyElem",
                        ),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Write text color <a href="#" onclick="yourFunction(`text_color`, `text_color_manual_hidden`)">Pallet</a>', 'pwe_header'),
                        'param_name' => 'text_color_manual_hidden',
                        'param_holder_class' => 'main-options pwe_dependent-hidden',
                        'description' => __('Write hex number for text color for the element.', 'pwe_header'),
                        'value' => '',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select text shadow color <a href="#" onclick="yourFunction(`text_shadow_color_manual_hidden`, `text_shadow_color`)">Hex</a>', 'pwe_header'),
                        'param_name' => 'text_shadow_color',
                        'param_holder_class' => 'backend-fields backend-area-one-fifth-width',
                        'description' => __('Select shadow text color for the element.', 'pwe_header'),
                        'value' => $pweHeaderInstance->findPalletColors(),
                        'dependency' => array(
                            'element' => 'text_color_manual_hidden',
                            'value' => array(''),
                        ),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Write text shadow color <a href="#" onclick="yourFunction(`text_shadow_color`, `text_shadow_color_manual_hidden`)">Pallet</a>', 'pwe_header'),
                        'param_name' => 'text_shadow_color_manual_hidden',
                        'param_holder_class' => 'main-options pwe_dependent-hidden',
                        'description' => __('Write hex number for text shadow color for the element.', 'pwe_header'),
                        'value' => '',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select button color <a href="#" onclick="yourFunction(`btn_color_manual_hidden`, `btn_color`)">Hex</a>', 'pwe_header'),
                        'param_name' => 'btn_color',
                        'param_holder_class' => 'backend-fields backend-area-one-fifth-width',
                        'description' => __('Select button color for the element.', 'pwe_header'),
                        'value' => $pweHeaderInstance->findPalletColors(),
                        'dependency' => array(
                            'element' => 'btn_color_manual_hidden',
                            'value' => array(''),
                        ),
                        'save_always' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Write button color <a href="#" onclick="yourFunction(`btn_color`, `btn_color_manual_hidden`)">Pallet</a>', 'pwe_header'),
                        'param_name' => 'btn_color_manual_hidden',
                        'param_holder_class' => 'main-options pwe_dependent-hidden',
                        'description' => __('Write hex number for button color for the element.', 'pwe_header'),
                        'value' => '',
                        'save_always' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select button text color <a href="#" onclick="yourFunction(`btn_text_color_manual_hidden`, `btn_text_color`)">Hex</a>', 'pwe_header'),
                        'param_name' => 'btn_text_color',
                        'param_holder_class' => 'backend-fields backend-area-one-fifth-width',
                        'description' => __('Select button text color for the element.', 'pwe_header'),
                        'value' => $pweHeaderInstance->findPalletColors(),
                        'dependency' => array(
                            'element' => 'btn_text_color_manual_hidden',
                            'value' => array(''),
                        ),
                        'save_always' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Write button text color <a href="#" onclick="yourFunction(`btn_text_color`, `btn_text_color_manual_hidden`)">Pallet</a>', 'pwe_header'),
                        'param_name' => 'btn_text_color_manual_hidden',
                        'param_holder_class' => 'main-options pwe_dependent-hidden',
                        'description' => __('Write hex number for button text color for the element.', 'pwe_header'),
                        'value' => '',
                        'save_always' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color_manual_hidden`, `btn_shadow_color`)">Hex</a>', 'pwe_header'),
                        'param_name' => 'btn_shadow_color',
                        'param_holder_class' => 'backend-fields backend-area-one-fifth-width',
                        'description' => __('Select button shadow color for the element.', 'pwe_header'),
                        'value' => $pweHeaderInstance->findPalletColors(),
                        'dependency' => array(
                            'element' => 'btn_shadow_color_manual_hidden',
                            'value' => array(''),
                        ),
                        'save_always' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Write button shadow color <a href="#" onclick="yourFunction(`btn_shadow_color`, `btn_shadow_color_manual_hidden`)">Pallet</a>', 'pwe_header'),
                        'param_name' => 'btn_shadow_color_manual_hidden',
                        'param_holder_class' => 'main-options pwe_dependent-hidden',
                        'description' => __('Write hex number for button shadow color for the element.', 'pwe_header'),
                        'value' => '',
                        'save_always' => true
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select main color <a href="#" onclick="yourFunction(`main_header_color_manual_hidden`, `main_header_color`)">Hex</a>', 'pwe_header'),
                        'param_name' => 'main_header_color',
                        'param_holder_class' => 'backend-fields backend-area-one-fifth-width',
                        'description' => __('Select main color for the element.', 'pwe_header'),
                        'value' => $pweHeaderInstance->findPalletColors(),
                        'dependency' => array(
                            'element' => 'main_header_color_manual_hidden',
                            'value' => array(''),
                            'callback' => "hideEmptyElem",
                        ),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Write main color <a href="#" onclick="yourFunction(`main_header_color`, `main_header_color_manual_hidden`)">Pallet</a>', 'pwe_header'),
                        'param_name' => 'main_header_color_manual_hidden',
                        'param_holder_class' => 'main-options pwe_dependent-hidden',
                        'description' => __('Write hex number for main color for the element.', 'pwe_header'),
                        'value' => '',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Select main color text <a href="#" onclick="yourFunction(`main_header_color_text_manual_hidden`, `main_header_color_text`)">Hex</a>', 'pwe_header'),
                        'param_name' => 'main_header_color_text',
                        'param_holder_class' => 'backend-fields backend-area-one-fifth-width',
                        'description' => __('Select main color text for the element.', 'pwe_header'),
                        'value' => $pweHeaderInstance->findPalletColors(),
                        'dependency' => array(
                            'element' => 'main_header_color_text_manual_hidden',
                            'value' => array(''),
                            'callback' => "hideEmptyElem",
                        ),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'PWE Element',
                        'heading' => __('Write main color text <a href="#" onclick="yourFunction(`main_header_color_text`, `main_header_color_text_manual_hidden`)">Pallet</a>', 'pwe_header'),
                        'param_name' => 'main_header_color_text_manual_hidden',
                        'param_holder_class' => 'main-options pwe_dependent-hidden',
                        'description' => __('Write hex number for main color text for the element.', 'pwe_header'),
                        'value' => '',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Modes', 'pwe_header'),
                        'param_name' => 'pwe_header_modes',
                        'admin_label' => true,
                        'value' => array(
                            'Default' => '',
                            'Simple mode' => 'simple_mode',
                            'Registration mode' => 'registration_mode',
                            'Coference mode' => 'conference_mode',
                            'Squares mode' => 'squares_mode',
                            'Video mode' => 'video_mode',
                            'Glass mode' => 'glass_mode',
                            'Glass mode v2' => 'glass_mode_v2',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'PWE Element',
                        'heading' => __('Form id', 'pwelement'),
                        'param_name' => 'pwe_header_form_id',
                        'save_always' => true,
                        'value' => array_merge(
                            array('Wybierz' => ''),
                            $pweHeaderInstance->findFormsGF(),
                        ),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'registration_mode',
                                'conference_mode'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'PWE Element',
                        'heading' => __('Conference mode', 'pwe_header'),
                        'param_name' => 'pwe_header_simple_conference',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'simple_mode'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Options',
                        'heading' => __('Conferece link', 'pwe_header'),
                        'description' => __('Default (/wydarzenia/ - PL), (/en/conferences/ - EN)', 'pwe_header'),
                        'param_name' => 'pwe_header_conference_link',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'registration_mode',
                                'conference_mode'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Options',
                        'heading' => __('Conferece logo url', 'pwe_header'),
                        'description' => __('Default (/kongres/)', 'pwe_header'),
                        'param_name' => 'pwe_header_conference_logo_url',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'registration_mode',
                                'conference_mode'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Options',
                        'heading' => __('Custom title', 'pwe_header'),
                        'description' => __('Change main title', 'pwe_header'),
                        'param_name' => 'pwe_header_custom_title',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                '',
                                'simple_mode',
                                'registration_mode',
                                'conference_mode',
                                'squares_mode',
                                'video_mode',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'Options',
                        'heading' => __('Background position', 'pwe_header'),
                        'param_name' => 'pwe_header_bg_position',
                        'value' => array(
                            'Top' => 'top',
                            'Center' => 'center',
                            'Bottom' => 'bottom'
                        ),
                        'std' => 'center',
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Turn on buttons', 'pwe_header'),
                        'param_name' => 'pwe_header_button_on',
                        'description' => __('Select options to display button:', 'pwe_header'),
                        'save_always' => true,
                        'value' => array(
                            __('register', 'pwe_header') => 'register',
                            __('ticket', 'pwe_header') => 'ticket',
                            __('conference', 'pwe_header') => 'conference',
                            __('video', 'pwe_header') => 'video',
                        ),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(''),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Options',
                        'heading' => __('Tickets button link', 'pwe_header'),
                        'description' => __('Default (/bilety/ - PL), (/en/tickets/ - EN)', 'pwe_header'),
                        'param_name' => 'pwe_header_tickets_button_link',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(''),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Options',
                        'heading' => __('Register button link', 'pwe_header'),
                        'description' => __('Default (/rejestracja/ - PL), (/en/registration/ - EN)', 'pwe_header'),
                        'param_name' => 'pwe_header_register_button_link',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(''),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Options',
                        'heading' => __('Conferences button link', 'pwe_header'),
                        'description' => __('Default (/wydarzenia/ - PL), (/en/conferences/ - EN)', 'pwe_header'),
                        'param_name' => 'pwe_header_conferences_button_link',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(''),
                        ),
                    ),
                    array(
                        'type' => 'textarea_raw_html',
                        'group' => 'Options',
                        'heading' => __('Conferences custom title', 'pwe_header'),
                        'description' => __('Default (Konferencje - PL), (Conferences - EN)', 'pwe_header'),
                        'param_name' => 'pwe_header_conferences_title',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(''),
                        ),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'group' => 'Options',
                        'heading' => __('Overlay color', 'pwe_header'),
                        'param_name' => 'pwe_header_overlay_color',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'input_range',
                        'group' => 'Options',
                        'heading' => __('Overlay opacity', 'pwe_header'),
                        'param_name' => 'pwe_header_overlay_range',
                        'value' => '0',
                        'min' => '0',
                        'max' => '1',
                        'step' => '0.01',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'input_range',
                        'group' => 'Options',
                        'heading' => __('Max width logo (px)', 'pwe_header'),
                        'description' => __('Default 260px', 'pwe_header'),
                        'param_name' => 'pwe_header_logo_width',
                        'value' => '260',
                        'min' => '100',
                        'max' => '600',
                        'step' => '1',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Main logo color', 'pwe_header'),
                        'param_name' => 'pwe_header_logo_color',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Congress logo color', 'pwe_header'),
                        'param_name' => 'pwe_header_congress_logo_color',
                        'description' => __('Add kongres-color.webp', 'pwe_header'),
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                '',
                                'registration_mode',
                                'conference_mode'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Hide association', 'pwe_header'),
                        'param_name' => 'pwe_header_association_hide',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                '',
                                'registration_mode',
                                'conference_mode'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('No margin&padding main logo', 'pwe_header'),
                        'param_name' => 'pwe_header_logo_marg_pag',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(''),
                        ),
                    ),
                    array(
                        'type' => 'param_group',
                        'group' => 'Options',
                        'heading' => __('Additional buttons', 'pwe_header'),
                        'param_name' => 'pwe_header_buttons',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => __('URL', 'pwe_header'),
                                'param_name' => 'pwe_header_button_link',
                                'save_always' => true,
                                'admin_label' => true
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => __('Text', 'pwe_header'),
                                'param_name' => 'pwe_header_button_text',
                                'save_always' => true,
                                'admin_label' => true
                            ),
                        ),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                '',
                                'video_mode'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Show shadow', 'pwe_header'),
                        'param_name' => 'pwe_header_shadow',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'video_mode',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Options',
                        'heading' => __('Shadow value', 'pwe_header'),
                        'description' => __('linear-gradient(to bottom, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0) 45%);', 'pwe_header'),
                        'param_name' => 'pwe_header_shadow_value',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'video_mode',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Center text', 'pwe_header'),
                        'param_name' => 'pwe_header_center',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'video_mode',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Left site text without bg', 'pwe_header'),
                        'param_name' => 'pwe_header_without_bg',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'video_mode',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Options',
                        'heading' => __('Show counter in header', 'pwe_header'),
                        'param_name' => 'pwe_header_counter',
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'video_mode',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'attach_image',
                        'group' => 'Options',
                        'heading' => __('Main fair logo', 'pwelement'),
                        'param_name' => 'new_main_logotype',
                        'save_always' => true,
                        'admin_label' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(
                                'video_mode',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'param_group',
                        'group' => 'Logotypes',
                        'heading' => __('Logotypes', 'pwe_header'),
                        'param_name' => 'pwe_header_logotypes',
                        'params' => array(
                            array(
                            'type' => 'attach_images',
                            'heading' => __('Logotypes catalog', 'pwe_header'),
                            'param_name' => 'logotypes_media',
                            'save_always' => true
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Logotypes catalog', 'pwe_header'),
                                'param_name' => 'logotypes_catalog',
                                'description' => __('Put catalog name in /doc/ where are logotypes.', 'pwe_header'),
                                'save_always' => true
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Logotypes Title', 'pwe_header'),
                                'param_name' => 'logotypes_title',
                                'description' => __('Set title to diplay over the gallery', 'pwe_header'),
                                'save_always' => true
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => esc_html__('Logotypes Name', 'pwe_header'),
                                'param_name' => 'logotypes_name',
                                'description' => __('Set custom name thumbnails', 'pwe_header'),
                                'save_always' => true
                            ),
                            array(
                                'type' => 'input_range',
                                'heading' => __('Gallery width (%)', 'pwe_header'),
                                'param_name' => 'logotypes_width',
                                'value' => '100',
                                'min' => '0',
                                'max' => '100',
                                'step' => '1',
                                'save_always' => true
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => __('Turn off slider', 'pwe_header'),
                                'param_name' => 'logotypes_slider_off',
                                'save_always' => true,
                                'param_holder_class' => 'dropdown-checkbox',
                                'value' => array(
                                    'No' => '',
                                    'Yes' => 'true'
                                ),
                            ),
                            array(
                                'type' => 'dropdown',
                                'heading' => __('Turn on captions', 'pwe_header'),
                                'param_name' => 'logotypes_caption_on',
                                'save_always' => true,
                                'param_holder_class' => 'dropdown-checkbox',
                                'value' => array(
                                    'No' => '',
                                    'Yes' => 'true'
                                ),
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Logotypes width (___px)', 'pwe_header'),
                                'param_name' => 'logotypes_items_width',
                                'save_always' => true
                            ),
                        ),
                        'dependency' => array(
                            'element' => 'pwe_header_modes',
                            'value' => array(''),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Aditional options',
                        'heading' => __('Logotypes color', 'pwe_header'),
                        'param_name' => 'logotypes_slider_logo_color',
                        'description' => __('Check if you want to change the logotypes white to color. ', 'pwe_header'),
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Aditional options',
                        'heading' => __('Association fair logo color', 'pwe_header'),
                        'param_name' => 'association_fair_logo_color',
                        'description' => __('Check if you want to change the logotypes color to color. ', 'pwe_header'),
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                    ),
                    array(
                        'type' => 'checkbox',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Turn off automatically get partners from CAP', 'pwe_header'),
                        'param_name' => 'pwe_header_cap_auto_partners_off',
                        'description' => __('Check if you want to turn off automatically retrieve partners from the database.', 'pwe_header'),
                        'save_always' => true,
                        'value' => array(__('True', 'pwe_header') => 'true',),
                    ),
                    array(
                        'type' => 'dropdown',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Widget position', 'pwe_header'),
                        'param_name' => 'pwe_header_partners_position',
                        'value' => array(
                            'Top' => 'top',
                            'Center' => 'center',
                            'Bottom' => 'bottom'
                        ),
                        'std' => 'center',
                    ),
                    array(
                        'type' => 'colorpicker',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Color title', 'pwe_header'),
                        'param_name' => 'pwe_header_partners_title_color',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'colorpicker',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Color background', 'pwe_header'),
                        'param_name' => 'pwe_header_partners_background_color',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Title widget', 'pwe_header'),
                        'param_name' => 'pwe_header_partners_title',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_cap_auto_partners_off',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'attach_images',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Select Partners/Patrons from media gallery', 'pwe_header'),
                        'param_name' => 'pwe_header_partners_items',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_cap_auto_partners_off',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Partners/Patrons catalog', 'pwe_header'),
                        'param_name' => 'pwe_header_partners_catalog',
                        'save_always' => true,
                        'dependency' => array(
                            'element' => 'pwe_header_cap_auto_partners_off',
                            'value' => array('true'),
                        ),
                    ),
                    array(
                        'type' => 'param_group',
                        'group' => 'Partners/Patrons',
                        'heading' => __('Other partners/patrons', 'pwe_header'),
                        'param_name' => 'pwe_header_other_partners',
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => __('Logotypes Title', 'pwe_header'),
                                'param_name' => 'pwe_header_partners_other_title',
                                'save_always' => true
                            ),
                            array(
                                'type' => 'attach_images',
                                'heading' => __('Logotypes', 'pwe_header'),
                                'param_name' => 'pwe_header_partners_other_logotypes',
                                'save_always' => true
                            ),
                            array(
                                'type' => 'textfield',
                                'group' => 'Partners/Patrons',
                                'heading' => __('Partners/Patrons catalog', 'pwe_header'),
                                'param_name' => 'pwe_header_partners_other_logotypes_catalog',
                                'save_always' => true,
                            ),
                        ),
                        'dependency' => array(
                            'element' => 'pwe_header_cap_auto_partners_off',
                            'value' => array('true'),
                        ),
                    ),
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
                    // Add additional options from class extends
                    ...PWElementAdditionalLogotypes::additionalArray(),
                )
            ),
        ));
    }
}

// Hook to initialize the Visual Composer map
add_action('vc_before_init', 'initVCMapHeader');