<?php

/**
 * Normalizes raw CAP conference JSON by language.
 */
final class PWE_Conference_Cap_Conference_Normalizer {

    /**
     * Decode and select language-specific conference data.
     */
    public function language_data($conference, string $language): array {
        $decoded = json_decode((string) ($conference->conf_data ?? ''), true);

        if (!is_array($decoded)) {
            return array();
        }

        return $decoded[strtoupper($language)] ?? array();
    }
}

