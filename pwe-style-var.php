<?php

class PWEStyleVar extends PWECommonFunctions {

    public function pwe_enqueue_style_var() {
        echo $this->pwe_style_var();
    }

    public function pwe_style_var() {
        $accent_color = shortcode_exists('trade_fair_accent') ? trim(do_shortcode('[trade_fair_accent]')) : '';
        if ($accent_color === '') {
            $accent_color = self::pwe_color('accent');
        }

        $accent_darker_color = self::adjustBrightness($accent_color, -20);
        $accent_dark_color   = self::adjustBrightness($accent_color, -50);
        $accent_lighter_color  = self::adjustBrightness($accent_color, +20);
        $accent_light_color  = self::adjustBrightness($accent_color, +60);

        $main2_color = shortcode_exists('trade_fair_main2') ? trim(do_shortcode('[trade_fair_main2]')) : '';
        if ($main2_color === '') {
            $main2_color = self::pwe_color('main2');
        }

        $main2_darker_color = self::adjustBrightness($main2_color, -20);
        $main2_dark_color   = self::adjustBrightness($main2_color, -50);
        $main2_lighter_color  = self::adjustBrightness($main2_color, +20);
        $main2_light_color  = self::adjustBrightness($main2_color, +60);

        $style = '
        <style>
            :root {
                --accent_light_color: ' . $accent_light_color . ';
                --accent_lighter_color: ' . $accent_lighter_color . ';
                --accent-color: ' . $accent_color . ';
                --accent_darker_color: ' . $accent_darker_color . ';
                --accent_dark_color: ' . $accent_dark_color . ';
                --main2_light_color: ' . $main2_light_color . ';
                --main2_lighter_color: ' . $main2_lighter_color . ';
                --main2-color: ' . $main2_color . ';
                --main2_darker_color: ' . $main2_darker_color . ';
                --main2_dark_color: ' . $main2_dark_color . ';
                --accent_color_95_white: color-mix(in srgb, var(--accent-color) 5%, #ffffff 95%);
                --main2_color_95_white: color-mix(in srgb, var(--main2-color) 5%, #ffffff 95%);
            }
        </style>';

        return $style;
    }
}

$pwe_style_var = new PWEStyleVar();