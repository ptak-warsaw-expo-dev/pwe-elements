<?php

/**
 * Loads organizer logo metadata from CAP.
 */
final class PWE_Conference_Cap_Organizer_Logo_Service {

    /**
     * Return all organizer logos for a conference slug using prepared SQL.
     */
    public function all(string $conference_slug): array {
        if (!class_exists('PWECommonFunctions')) {
            return array();
        }

        $cap_db = PWECommonFunctions::connect_database();
        if (!$cap_db) {
            return array();
        }

        $conference = $cap_db->get_row(
            $cap_db->prepare('SELECT id, organizers_img FROM conferences WHERE conf_slug = %s', $conference_slug),
            ARRAY_A
        );

        if (!$conference || empty($conference['organizers_img'])) {
            return array();
        }

        $conference_id = (int) $conference['id'];
        $logos = array_map('trim', explode(',', (string) $conference['organizers_img']));
        $results = array();

        foreach ($logos as $logo) {
            if ($logo === '') {
                continue;
            }

            $slug = 'org-' . $logo;
            $conf_add = $cap_db->get_row(
                $cap_db->prepare('SELECT data FROM conf_adds WHERE slug = %s AND conf_id = %d', $slug, $conference_id),
                ARRAY_A
            );

            $results[] = array(
                'src'  => 'https://cap.warsawexpo.eu/public/uploads/conf/' . rawurlencode($conference_slug) . '/organizer/' . rawurlencode($logo),
                'data' => !empty($conf_add['data']) ? json_decode((string) $conf_add['data'], true) : array(),
            );
        }

        return $results;
    }
}

