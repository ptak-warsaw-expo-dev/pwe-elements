<?php

/**
 * Maps legacy VC values to normalized mode folders and renderer class names.
 */
final class PWE_Conference_Cap_Mode_Resolver {

    /**
     * Resolve the configured mode. Unknown values intentionally fall back to Full Mode.
     */
    public static function resolve_mode(string $legacy_mode): array {
        $map = array(
            ''                              => array('mode' => 'full_mode', 'class' => 'PWE_Conference_Cap_Full_Mode', 'legacy_class' => 'PWEConferenceCapFullMode'),
            'PWEConferenceCapFullMode'      => array('mode' => 'full_mode', 'class' => 'PWE_Conference_Cap_Full_Mode', 'legacy_class' => 'PWEConferenceCapFullMode'),
            'PWEConferenceCapFullMode2'     => array('mode' => 'full_mode', 'class' => 'PWE_Conference_Cap_Full_Mode', 'legacy_class' => 'PWEConferenceCapFullMode'),
            'PWEConferenceCapSimpleMode'    => array('mode' => 'simple_mode', 'class' => 'PWE_Conference_Cap_Simple_Mode', 'legacy_class' => 'PWEConferenceCapSimpleMode'),
            'PWEConferenceCapMedalCeremony' => array('mode' => 'medal_ceremony', 'class' => 'PWE_Conference_Cap_Medal_Ceremony', 'legacy_class' => 'PWEConferenceCapMedalCeremony'),
            'PWEConferenceCapWarsawExpo'    => array('mode' => 'warsawexpo', 'class' => 'PWE_Conference_Cap_WarsawExpo_Mode', 'legacy_class' => 'PWEConferenceCapWarsawExpo'),
        );

        return $map[$legacy_mode] ?? $map[''];
    }
}

