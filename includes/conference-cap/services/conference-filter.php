<?php

/**
 * Small filter helpers for conference collections.
 */
final class PWE_Conference_Cap_Conference_Filter {

    /**
     * Keep conferences whose slugs are in the allowed list.
     */
    public function by_slugs(array $conferences, array $allowed_slugs): array {
        return array_values(array_filter($conferences, static function ($conference) use ($allowed_slugs): bool {
            return in_array($conference->conf_slug ?? '', $allowed_slugs, true);
        }));
    }
}

