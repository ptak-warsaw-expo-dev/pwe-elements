<?php

/**
 * Trends panel informational mode.
 */
final class PWE_Conference_Cap_Trends_Panel {

    /**
     * Render the trends panel.
     */
    public function render(array $context): string {
        return PWE_Conference_Cap_Template::render_template(
            PWE_CONFERENCE_CAP_PATH . 'modes/trends_panel/templates/wrapper.php',
            array('language' => $context['language'] ?? 'PL')
        );
    }

    /**
     * Return mode assets.
     */
    public function get_assets(): array {
        return array('css' => array('modes/trends_panel/assets/trends_panel.css'), 'js' => array());
    }
}

