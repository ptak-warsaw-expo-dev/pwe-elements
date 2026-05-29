<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'pwe_elements_page');

function pwe_get_admin_pages()
{
    return [
        'general' => [
            'page_title'    => 'General Settings',
            'menu_title'    => 'General Settings',
            'tab_title'     => 'General',
            'slug'          => 'pwe-elements-general',
            'option_group'  => 'pwe_general_options_group',
            'settings_page' => 'pwe-general-settings',
        ],
        'menu' => [
            'page_title'    => 'Menu Settings',
            'menu_title'    => 'Menu Settings',
            'tab_title'     => 'Menu',
            'slug'          => 'pwe-elements-menu',
            'option_group'  => 'pwe_menu_options_group',
            'settings_page' => 'pwe-menu-settings',
        ],
        'translation' => [
            'page_title'          => 'Translation Settings',
            'menu_title'          => 'Translation Settings',
            'tab_title'           => 'Translations',
            'slug'                => 'pwe-elements-translation',
            'type'                => 'action_page',
            'after_form_callback' => 'pwe_render_translation_sync_panel',
        ],
    ];
}

function pwe_elements_page()
{
    $pages = pwe_get_admin_pages();

    add_menu_page(
        'PWE Elements',
        'PWE Elements',
        'manage_options',
        'pwe-elements',
        'pwe_render_admin_page',
        'dashicons-layout',
        3
    );

    foreach ($pages as $page) {
        add_submenu_page(
            'pwe-elements',
            $page['page_title'],
            $page['menu_title'],
            'manage_options',
            $page['slug'],
            'pwe_render_admin_page'
        );
    }
}

function pwe_render_admin_tabs($current_slug)
{
    $pages = pwe_get_admin_pages();

    echo '<h2 class="nav-tab-wrapper">';

    foreach ($pages as $page) {
        $active_class = ($current_slug === $page['slug']) ? ' nav-tab-active' : '';

        echo '<a href="' . esc_url(admin_url('admin.php?page=' . $page['slug'])) . '" class="nav-tab' . esc_attr($active_class) . '">';
        echo esc_html($page['tab_title']);
        echo '</a>';
    }

    echo '</h2>';
}

function pwe_get_current_admin_page()
{
    $pages = pwe_get_admin_pages();

    $current_slug = isset($_GET['page'])
        ? sanitize_key($_GET['page'])
        : 'pwe-elements-general';

    if ($current_slug === 'pwe-elements') {
        return $pages['general'];
    }

    foreach ($pages as $page) {
        if ($page['slug'] === $current_slug) {
            return $page;
        }
    }

    return $pages['general'];
}

function pwe_render_admin_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $page = pwe_get_current_admin_page();

    echo '<div class="wrap">';
    echo '<h1>' . esc_html($page['page_title']) . '</h1>';

    pwe_render_admin_tabs($page['slug']);

    if (($page['type'] ?? 'settings_page') === 'settings_page') {
        echo '<form method="post" action="options.php">';
        settings_fields($page['option_group']);
        do_settings_sections($page['settings_page']);
        submit_button('Zapisz ustawienia');
        echo '</form>';
    }

    if (!empty($page['after_form_callback']) && is_callable($page['after_form_callback'])) {
        call_user_func($page['after_form_callback']);
    }

    echo '</div>';
}
