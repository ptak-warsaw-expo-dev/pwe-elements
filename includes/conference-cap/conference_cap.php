<?php

defined('ABSPATH') || exit;

define('PWE_CONFERENCE_CAP_PATH', plugin_dir_path(__FILE__));
define('PWE_CONFERENCE_CAP_URL', plugin_dir_url(__FILE__));

$pwe_conference_cap_files = array(
    'core/attributes.php',
    'core/mode-resolver.php',
    'core/assets.php',
    'core/vc-map.php',
    'helpers/template.php',
    'helpers/sanitizer.php',
    'helpers/date-parser.php',
    'helpers/time-parser.php',
    'services/conference-repository.php',
    'services/conference-filter.php',
    'services/conference-normalizer.php',
    'services/session-normalizer.php',
    'services/speaker-normalizer.php',
    'services/html-injection-service.php',
    'services/patron-logo-service.php',
    'services/organizer-logo-service.php',
    'modes/full_mode/full_mode.php',
    'modes/simple_mode/simple_mode.php',
    'modes/medal_ceremony/medal_ceremony.php',
    'modes/trends_panel/trends_panel.php',
    'modes/warsawexpo/warsawexpo.php',
    'core/legacy-shortcode-renderer.php',
    'core/shortcode.php',
    'core/plugin.php',
);

foreach ($pwe_conference_cap_files as $pwe_conference_cap_file) {
    require_once PWE_CONFERENCE_CAP_PATH . $pwe_conference_cap_file;
}

/**
 * Backward-compatible public facade for legacy extensions and static calls.
 */
class PWEConferenceCap {

    private $plugin;

    /**
     * Register the refactored plugin hooks.
     */
    public function __construct() {
        $this->plugin = new PWE_Conference_Cap_Plugin();
        $this->plugin->init();
    }

    /**
     * Legacy shortcode callback.
     *
     * @param array|string $atts Shortcode attributes.
     */
    public static function PWEConferenceCapOutput($atts): string {
        $shortcode = new PWE_Conference_Cap_Shortcode();

        return $shortcode->render(is_array($atts) ? $atts : array());
    }
}

new PWEConferenceCap();
