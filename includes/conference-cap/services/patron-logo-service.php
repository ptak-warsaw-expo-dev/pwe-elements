<?php

/**
 * Loads patron logo metadata without emitting debug output.
 */
final class PWE_Conference_Cap_Patron_Logo_Service {

    /**
     * Render patron logos from the CAP adds table.
     */
    public function render_from_list(int $conference_id, string $conference_slug, array $logo_files): string {
        if (empty($logo_files) || !class_exists('PWECommonFunctions')) {
            return '';
        }

        $cap_db = PWECommonFunctions::connect_database();
        if (!$cap_db) {
            return '<!-- No CAP database connection -->';
        }

        $adds_raw = $cap_db->get_results(
            $cap_db->prepare('SELECT slug, data FROM conf_adds WHERE conf_id = %d', $conference_id),
            ARRAY_A
        );

        $adds = array();
        foreach ((array) $adds_raw as $row) {
            $adds[trim((string) $row['slug'])] = json_decode((string) $row['data'], true);
        }

        $base_url = 'https://cap.warsawexpo.eu/public/uploads/conf/' . rawurlencode($conference_slug) . '/patrons';
        $output = '';

        foreach ($logo_files as $slug) {
            $slug = trim((string) $slug);
            if ($slug === '') {
                continue;
            }

            $data = $adds[$slug] ?? array();
            $logo_url = $base_url . '/' . rawurlencode($slug);
            $alt = !empty($data['alt']) ? (string) $data['alt'] : 'Patron Logo';
            $title = !empty($data['desc']) ? (string) $data['desc'] : '';
            $link = !empty($data['link']) ? (string) $data['link'] : '';
            $img_html = '<img src="' . esc_url($logo_url) . '" data-no-lazy="1" alt="' . esc_attr($alt) . '" class="conference_patroni_logo">';

            $output .= '<div class="conference_cap__patrons-container-logo">';
            $output .= $link ? '<a href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer">' . $img_html . '</a>' : $img_html;
            $output .= $title ? '<span class="conference_cap__patrons-container-logo-title">' . esc_html($title) . '</span>' : '';
            $output .= '</div>';
        }

        return $output;
    }
}

