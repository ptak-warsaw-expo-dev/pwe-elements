<?php

/**
 * Registers the shortcode, Visual Composer map and lightweight compatibility facade.
 */
final class PWE_Conference_Cap_Plugin {

    /**
     * Boot WordPress integrations.
     */
    public function init(): void {
        add_action('init', array($this, 'register_vc_map'));
        add_shortcode('pwe_conference_cap', array($this, 'render_shortcode'));
    }

    /**
     * Register the WPBakery element with all legacy params intact.
     */
    public function register_vc_map(): void {
        PWE_Conference_Cap_VC_Map::register();
    }

    /**
     * Render the legacy-compatible shortcode.
     *
     * @param array|string $atts Shortcode attributes.
     */
    public function render_shortcode($atts): string {
        $shortcode = new PWE_Conference_Cap_Shortcode();

        return $shortcode->render(is_array($atts) ? $atts : array());
    }
}

