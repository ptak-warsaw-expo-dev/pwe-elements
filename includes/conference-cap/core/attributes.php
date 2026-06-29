<?php

/**
 * Parses shortcode attributes without extract() and keeps legacy aliases alive.
 */
final class PWE_Conference_Cap_Attributes {

    private array $attributes;

    private function __construct(array $attributes) {
        $this->attributes = $attributes;
    }

    /**
     * Parse raw shortcode attributes and normalize legacy aliases.
     */
    public static function from_shortcode(array $raw_attributes): self {
        $defaults = array(
            'conference_cap_html'                    => '',
            'conference_cap_conference_mode'         => '',
            'conference_cap_conference_arichive'     => '',
            'conference_cap_conference_archive'      => '',
            'conference_cap_conference_display_slug' => '',
            'conference_cap_domains'                 => '',
            'conference_cap_one_conference_mode'     => '',
            'manual_conferences'                     => '',
        );

        $attributes = shortcode_atts($defaults, $raw_attributes, 'pwe_conference_cap');

        // Backward compatibility: the original VC param contains a typo ("arichive").
        // The corrected alias wins when both names are provided.
        if ($attributes['conference_cap_conference_archive'] !== '') {
            $attributes['conference_cap_conference_arichive'] = $attributes['conference_cap_conference_archive'];
        }

        return new self($attributes);
    }

    /**
     * Get a single normalized attribute.
     */
    public function get(string $key, $default = '') {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * Return normalized attributes for legacy renderers.
     */
    public function to_array(): array {
        return $this->attributes;
    }
}

