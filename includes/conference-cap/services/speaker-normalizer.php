<?php

/**
 * Normalizes CAP legent-* speaker fields.
 */
final class PWE_Conference_Cap_Speaker_Normalizer {

    /**
     * Build normalized speakers from a raw session array.
     */
    public function normalize_speakers(array $session): array {
        $speakers = array();

        foreach ($session as $key => $value) {
            if (strpos((string) $key, 'legent-') !== 0 || !is_array($value)) {
                continue;
            }

            $speaker = $this->normalize_speaker($value);

            if ($speaker !== null) {
                $speakers[] = $speaker;
            }
        }

        return $speakers;
    }

    /**
     * Normalize one legacy speaker payload.
     */
    public function normalize_speaker(array $speaker): ?array {
        $raw_name = trim((string) ($speaker['name'] ?? ''));

        if ($raw_name === '' || $raw_name === '*' || mb_strtolower($raw_name) === 'brak') {
            return null;
        }

        $parts = array_map('trim', explode(';;', $raw_name));
        $name = $parts[0] ?? '';
        $subtitle = $parts[1] ?? '';
        $url = trim((string) ($speaker['url'] ?? ''));
        $bio = (string) ($speaker['desc'] ?? '');
        $name_html = esc_html($name);

        if ($subtitle !== '') {
            $name_html .= '<br><span class="conference_cap__lecture-name-subline">' . esc_html($subtitle) . '</span>';
        }

        return array(
            'name_plain' => trim($name . ' ' . $subtitle),
            'name_html'  => $name_html,
            'subtitle'   => $subtitle,
            'url'        => $url,
            'bio'        => $bio,
            'has_bio'    => trim(wp_strip_all_tags($bio)) !== '',
            'has_image'  => $url !== '',
        );
    }

    /**
     * Prepare speaker data for the front-end BIO modal.
     */
    public function modal_data(array $speakers): array {
        return array_values(array_filter(array_map(static function (array $speaker): ?array {
            if (empty($speaker['has_bio'])) {
                return null;
            }

            return array(
                'name'      => $speaker['name_plain'],
                'name_html' => $speaker['name_html'],
                'url'       => $speaker['url'],
                'bio'       => $speaker['bio'],
            );
        }, $speakers)));
    }

    /**
     * Return only speaker image URLs.
     */
    public function images(array $speakers): array {
        return array_values(array_filter(array_map(static fn(array $speaker): string => (string) $speaker['url'], $speakers)));
    }
}

