<?php

/**
 * Small PHP template renderer used by mode classes.
 */
final class PWE_Conference_Cap_Template {

    /**
     * Render a PHP template with isolated variables.
     */
    public static function render_template(string $template_path, array $data = array()): string {
        if (!file_exists($template_path)) {
            return '';
        }

        ob_start();
        foreach ($data as $key => $value) {
            if (is_string($key) && preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key) && !isset($$key)) {
                $$key = $value;
            }
        }
        include $template_path;

        return (string) ob_get_clean();
    }
}
