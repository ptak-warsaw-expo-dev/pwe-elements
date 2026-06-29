<?php

/**
 * Registers and enqueues common and mode-specific assets with filemtime versions.
 */
final class PWE_Conference_Cap_Assets {

    /**
     * Enqueue assets shared by all shortcode modes.
     */
    public static function enqueue_common(): void {
        self::enqueue_style('pwe-conference-cap-css', 'assets/common/conference_cap.css');
    }

    /**
     * Enqueue mode assets only for the active mode.
     */
    public static function enqueue_mode(string $mode): void {
        $assets = array(
            'full_mode'       => array('modes/full_mode/assets/full_mode.css'),
            'simple_mode'     => array('modes/simple_mode/assets/simple_mode.css'),
            'medal_ceremony'  => array('modes/medal_ceremony/assets/medal_ceremony.css'),
            'trends_panel'    => array('modes/trends_panel/assets/trends_panel.css'),
            'warsawexpo'      => array('modes/warsawexpo/assets/warsawexpo.css', 'modes/warsawexpo/assets/warsawexpo.js'),
        );

        foreach ($assets[$mode] ?? $assets['full_mode'] as $asset) {
            $handle = 'pwe-conference-cap-' . sanitize_key(str_replace(array('/', '.css', '.js'), '-', $asset));

            if (substr($asset, -3) === '.js') {
                self::enqueue_script($handle, $asset, array('jquery'));
                continue;
            }

            self::enqueue_style($handle, $asset);
        }
    }

    /**
     * Enqueue the modal/navigation data script after speaker data is ready.
     */
    public static function enqueue_runtime(array $speakers_data_mapping, bool $one_conference_mode, string $archive): void {
        self::enqueue_script('pwe-conference-cap-js', 'assets/common/conference_cap.js', array('jquery'));
        wp_localize_script(
            'pwe-conference-cap-js',
            'confCapData',
            array(
                'data'        => $speakers_data_mapping,
                'oneConfMode' => $one_conference_mode,
                'archive'     => $archive,
            )
        );
    }

    private static function enqueue_style(string $handle, string $relative_path): void {
        $path = PWE_CONFERENCE_CAP_PATH . $relative_path;
        $url  = PWE_CONFERENCE_CAP_URL . $relative_path;

        if (file_exists($path)) {
            wp_enqueue_style($handle, $url, array(), (string) filemtime($path));
        }
    }

    private static function enqueue_script(string $handle, string $relative_path, array $deps = array()): void {
        $path = PWE_CONFERENCE_CAP_PATH . $relative_path;
        $url  = PWE_CONFERENCE_CAP_URL . $relative_path;

        if (file_exists($path)) {
            wp_enqueue_script($handle, $url, $deps, (string) filemtime($path), true);
        }
    }
}
