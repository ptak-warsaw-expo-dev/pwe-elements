<?php

/**
 * Central escaping helpers for values commonly emitted by the conference cap.
 */
final class PWE_Conference_Cap_Sanitizer {

    /**
     * Sanitize descriptive HTML while allowing normal post markup.
     */
    public static function post_html(string $html): string {
        return wp_kses_post($html);
    }

    /**
     * Sanitize text intended for element content.
     */
    public static function text(string $text): string {
        return esc_html($text);
    }
}

