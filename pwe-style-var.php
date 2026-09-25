<?php

class PWEStyleVar extends PWECommonFunctions {

    /**
     * OUTPUT CSS FOR <head>
     */
    public function pwe_enqueue_style_var() {
        echo $this->pwe_style_var();
    }

    /**
     * COLOR MAP (single source of truth)
     */
    private function get_color_map() {
        return [
            'accent' => [
                'shortcode' => 'trade_fair_accent',
                'fallback'  => 'accent',
            ],
            'main2' => [
                'shortcode' => 'trade_fair_main2',
                'fallback'  => 'main2',
            ],
        ];
    }

    /**
     * INIT SHORTCODES
     */
    public function init_color_shortcodes() {

        $map = $this->get_color_map();

        foreach ($map as $key => $data) {

            add_shortcode($key . '_color', function () use ($key) {
                return $this->get_color($key, 'base');
            });

            add_shortcode($key . '_light_color', function () use ($key) {
                return $this->get_color($key, 'light');
            });

            add_shortcode($key . '_lighter_color', function () use ($key) {
                return $this->get_color($key, 'lighter');
            });

            add_shortcode($key . '_dark_color', function () use ($key) {
                return $this->get_color($key, 'dark');
            });

            add_shortcode($key . '_darker_color', function () use ($key) {
                return $this->get_color($key, 'darker');
            });
        }

        // ONE DYNAMIC COLOR MIX SHORTCODE
        add_shortcode('color_mix', [$this, 'handle_color_mix']);
    }

    /**
     * CORE FUNCTION TO GET COLOR VALUE
     */
    private function get_color($key, $type = 'base') {

        $map = $this->get_color_map();

        if (!isset($map[$key])) {
            return '';
        }

        $data = $map[$key];

        $color = shortcode_exists($data['shortcode'])
            ? trim(do_shortcode('[' . $data['shortcode'] . ']'))
            : '';

        if ($color === '') {
            $color = self::pwe_color($data['fallback']);
        }

        $variants = [
            'base'    => $color,
            'dark'    => do_shortcode('[color_mix base="' . $color . '" percent="20" target="black"]'),
            'darker'  => do_shortcode('[color_mix base="' . $color . '" percent="50" target="black"]'),
            'light'   => do_shortcode('[color_mix base="' . $color . '" percent="50" target="white"]'),
            'lighter' => do_shortcode('[color_mix base="' . $color . '" percent="20" target="white"]'),
        ];

        return $variants[$type] ?? $color;
    }

    /**
     * DYNAMIC COLOR MIX SHORTCODE
     * Example:
     * [color_mix base="accent" percent="95" target="white"]
     */
    public function handle_color_mix($atts) {

        $atts = shortcode_atts([
            'base'    => 'accent',
            'percent' => '95',
            'target'  => 'white',
        ], $atts);

        $base    = $atts['base'];
        $percent = (int) $atts['percent'];
        $target  = strtolower(trim($atts['target']));

        if ($percent < 0 || $percent > 100) {
            return '';
        }

        if (preg_match('/^#[0-9a-fA-F]{6}$/', $base)) {
            $base_color = $base;
        } else {
            $colors = [
                'accent' => do_shortcode('[trade_fair_accent]'),
                'main2'  => do_shortcode('[trade_fair_main2]'),
            ];

            $base_color = $colors[$base] ?? '';
        }

        if (!$base_color) {
            return '';
        }

        if ($target === 'white') {
            $target_color = '#ffffff';
        } elseif ($target === 'black') {
            $target_color = '#000000';
        } else {
            $target_color = $target;
        }

        if (
            !preg_match('/^#[0-9a-fA-F]{6}$/', $base_color) ||
            !preg_match('/^#[0-9a-fA-F]{6}$/', $target_color)
        ) {
            return '';
        }

        $hexToRgb = static function ($hex) {
            $hex = ltrim($hex, '#');

            return [
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2)),
            ];
        };

        $base_rgb   = $hexToRgb($base_color);
        $target_rgb = $hexToRgb($target_color);

        $ratio = $percent / 100;

        $r = (int) ($base_rgb[0] * (1 - $ratio) + $target_rgb[0] * $ratio);
        $g = (int) ($base_rgb[1] * (1 - $ratio) + $target_rgb[1] * $ratio);
        $b = (int) ($base_rgb[2] * (1 - $ratio) + $target_rgb[2] * $ratio);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * GENERATING CSS VARIABLES
     */
    public function pwe_style_var() {

        $map = $this->get_color_map();

        $style = '<style>:root {';

        foreach ($map as $key => $data) {

            $base    = $this->get_color($key, 'base');
            $dark    = $this->get_color($key, 'dark');
            $darker  = $this->get_color($key, 'darker');
            $light   = $this->get_color($key, 'light');
            $lighter = $this->get_color($key, 'lighter');

            $style .= "
                --{$key}_dark_color: {$darker};
                --{$key}_darker_color: {$dark};
                --{$key}-color: {$base};
                --{$key}_lighter_color: {$lighter};
                --{$key}_light_color: {$light};
            ";
        }

        // Add additional CSS variables for color mixing
        $style .= "
            --accent_color_95_white: color-mix(in srgb, var(--accent-color) 5%, #ffffff 95%);
            --main2_color_95_white: color-mix(in srgb, var(--main2-color) 5%, #ffffff 95%);
        ";

        $style .= '}</style>';

        return $style;
    }
}