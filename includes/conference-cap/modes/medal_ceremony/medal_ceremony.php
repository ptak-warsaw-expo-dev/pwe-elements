<?php

/**
 * Legacy Medal Ceremony renderer kept in the mode folder as the single source of truth.
 */
class PWEConferenceCapMedalCeremony {

    public function __construct() {}

    public static function output($atts, $sessions, $conf_function, $conf_name, $day, $conf_slug, $conf_location) {
        return '<div class="conference_cap_medal_ceremony__main-container">
            <div class="conference_cap_medal_ceremony__title">
                <h2>' . wp_kses_post($conf_name) . '</h2>
            </div>
            <div class="conference_cap_medal_ceremony__ceremony-container">
                <div class="conference_cap_medal_ceremony__date">
                    <h4>' . PWECommonFunctions::languageChecker('Data', 'Date') . '</h4>
                    <span>' . wp_kses_post($day) . '</span>
                </div>
                <div class="conference_cap_medal_ceremony__location">
                    <h4>' . PWECommonFunctions::languageChecker('Lokalizacja', 'Location') . '</h4>
                    <span>' . wp_kses_post($conf_location) . '</span>
                </div>
            </div>
        </div>';
    }
}

/**
 * New mode class wrapper for context-based rendering.
 */
final class PWE_Conference_Cap_Medal_Ceremony {

    public function render(array $context): string {
        return PWEConferenceCapMedalCeremony::output(
            $context['attributes'] ?? array(),
            $context['sessions'] ?? array(),
            $context['legacy_functions'] ?? new PWEConferenceCapFunctions(),
            $context['conference_name'] ?? '',
            $context['day_label'] ?? '',
            $context['conference_slug'] ?? '',
            $context['conference_location'] ?? ''
        );
    }

    public function get_assets(): array {
        return array('css' => array('modes/medal_ceremony/assets/medal_ceremony.css'), 'js' => array());
    }
}

