<?php

/**
 * Normalizes pre-* session fields into a renderer-friendly structure.
 */
final class PWE_Conference_Cap_Session_Normalizer {

    private PWE_Conference_Cap_Speaker_Normalizer $speaker_normalizer;

    public function __construct(?PWE_Conference_Cap_Speaker_Normalizer $speaker_normalizer = null) {
        $this->speaker_normalizer = $speaker_normalizer ?? new PWE_Conference_Cap_Speaker_Normalizer();
    }

    /**
     * Normalize all pre-* sessions for a single day.
     */
    public function normalize_sessions(array $sessions, string $conference_slug = '', string $day_key = ''): array {
        $normalized = array();
        $counter = 0;

        foreach ($sessions as $session_key => $session) {
            if (strpos((string) $session_key, 'pre-') !== 0 || !is_array($session)) {
                continue;
            }

            $counter++;
            $speakers = $this->speaker_normalizer->normalize_speakers($session);

            $normalized[] = array(
                'id'       => $this->stable_id($conference_slug, $day_key, $session_key, $counter),
                'key'      => (string) $session_key,
                'time'     => (string) ($session['time'] ?? ''),
                'hour'     => (string) ($session['hour'] ?? ($session['time'] ?? '')),
                'title'    => (string) ($session['title'] ?? ''),
                'desc'     => (string) ($session['desc'] ?? ''),
                'hall'     => (string) ($session['hall'] ?? ''),
                'speakers' => $speakers,
            );
        }

        return $normalized;
    }

    private function stable_id(string $conference_slug, string $day_key, string $session_key, int $counter): string {
        $base = trim($conference_slug . '_' . $day_key . '_' . $session_key, '_');

        return sanitize_title($base !== '' ? $base : 'lecture-' . $counter);
    }
}

