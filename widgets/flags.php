<?php

$output .= '
<style>
    .pwe-flag {
        position: fixed;
        top: 36px;
        right: 36px;
        width: 40px;
        height: 30px;
        z-index: 999;
    }
    .pwe-flag img {
        width: 100%;
        transition: .3s ease;
        box-shadow: 0px 0px 5px black;
        border-radius: 4px;
    }
    .pwe-flag:hover img {
        transform: scale(1.2);
    }
</style>';

$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
if (!empty($languages)) {
    foreach ($languages as $lang) {
        if (!$lang['active']) {
            $output .= '<a class="pwe-flag" href="' . esc_url($lang['url']) . '">';
            $output .= '<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang['translated_name']) . ' flag">';
            $output .= '</a>';
        }
    }
}