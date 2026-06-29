<?php

/**
 * Data access boundary for CAP conferences.
 */
final class PWE_Conference_Cap_Conference_Repository {

    /**
     * Read conference data using the existing shared CAP integration.
     */
    public function all(?string $domain = null): array {
        if (!class_exists('PWECommonFunctions')) {
            return array();
        }

        return (array) PWECommonFunctions::get_database_conferences_data($domain);
    }
}

