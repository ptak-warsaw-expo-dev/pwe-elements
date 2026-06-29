<?php

/**
 * Parses custom HTML injection param groups into lookup maps.
 */
final class PWE_Conference_Cap_HTML_Injection_Service {

    /**
     * Parse WPBakery param group custom HTML data.
     */
    public function parse(string $encoded_group): array {
        if ($encoded_group === '' || !function_exists('vc_param_group_parse_atts')) {
            return array();
        }

        return (array) vc_param_group_parse_atts($encoded_group);
    }
}

