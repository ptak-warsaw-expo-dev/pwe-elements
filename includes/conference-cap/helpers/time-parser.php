<?php

/**
 * Normalizes time ranges used in CAP schedules.
 */
final class PWE_Conference_Cap_Time_Parser {

    /**
     * Normalize a time range to start/end in HH:MM.
     */
    public static function normalize_hour_string(string $time_string): ?array {
        $time_string = trim(str_replace(array('–', '—', '−'), '-', $time_string));
        $time_string = preg_replace('/\s+/', '', $time_string);
        $time_string = str_replace('.', ':', (string) $time_string);

        if (preg_match('/^(\d{1,2}:\d{2})(?:-(\d{1,2}:\d{2}))?$/', $time_string, $matches)) {
            return array(
                'start' => $matches[1],
                'end'   => $matches[2] ?? null,
            );
        }

        return null;
    }
}

