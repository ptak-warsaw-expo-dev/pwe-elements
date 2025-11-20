<?php

/**
 * Class PWEMapDynamic
 * Extends PWEMap class and defines a custom Visual Composer element.
 */
class PWEMapDynamic extends PWEMap {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../../../translations/includes/map.json';

        // JSON file with translation
        $translations_data = json_decode(file_get_contents($translations_file), true);

        // Is the language in translations
        if (isset($translations_data[$locale])) {
            $translations_map = $translations_data[$locale];
        } else {
            // By default use English translation if no translation for current language
            $translations_map = $translations_data['en_US'];
        }

        // Return translation based on key
        return isset($translations_map[$key]) ? $translations_map[$key] : $key;
    }

    public static function ordinal_suffix($n) {
        if (!in_array(($n % 100), [11, 12, 13])) {
            switch ($n % 10) {
                case 1:  return $n . 'st';
                case 2:  return $n . 'nd';
                case 3:  return $n . 'rd';
            }
        }
        return $n . 'th';
    }

    public static function get_custom_title($edition) {

        // 1st edition (special case)
        if ($edition == 1) {
            return self::multi_translation("estimates_1st");
        }

        // Other editions (2, 3, 4…)
        $locale = get_locale();

        if ($locale === "en_US") {
            // English needs correct ordinal form
            if ($edition == 2) return self::multi_translation("industry_visitors_1st");
            if ($edition == 3) return self::multi_translation("industry_visitors_2nd");
            if ($edition == 4) return self::multi_translation("industry_visitors_3rd");

            // For 4+ use generic + ordinal_suffix
            $ordinal = self::ordinal_suffix($edition - 1);
            return str_replace("{number}", $ordinal, self::multi_translation("industry_visitors_nth"));
        }

        // PL, DE — normal placeholder
        return str_replace(
            "{number}",
            $edition - 1,
            self::multi_translation("industry_visitors_nth")
        );
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {

        extract( shortcode_atts( array(
            'map_type' => '',
            'map_dynamic_preset' => '',
            'map_dynamic_3d' => '',
            'map_color' => '',
            'map_overlay' => '',
            'map_image' => '',
            'map_custom_title' => '',
            'map_year' => '',
            'map_custom_current_edition' => '',
            'map_number_visitors' => '',
            'map_number_abroad_visitors' => '',
            'map_number_exhibitors' => '',
            'map_exhibition_space' => '',
            'map_year_previous' => '',
            'map_custom_previous_edition' => '',
            'map_number_visitors_previous' => '',
            'map_number_abroad_visitors_previous' => '',
            'map_number_exhibitors_previous' => '',
            'map_exhibition_space_previous' => '',
            'map_number_countries' => '',
            'map_percent_polish_visitors' => '',
            'map_exhibition_space' => '',
        ), $atts ));

        $pwe_visitors             = do_shortcode('[pwe_visitors]');
        $map_number_visitors      = !empty($pwe_visitors) ? (int)$pwe_visitors : (isset($map_number_visitors) ? (int)$map_number_visitors : 0);

        $pwe_visitors_foreign     = do_shortcode('[pwe_visitors_foreign]');
        $map_number_abroad_visitors = !empty($pwe_visitors_foreign) ? (int)$pwe_visitors_foreign : (isset($map_number_abroad_visitors) ? (int)$map_number_abroad_visitors : 0);

        $pwe_exhibitors           = do_shortcode('[pwe_exhibitors]');
        $map_number_exhibitors    = !empty($pwe_exhibitors) ? (int)$pwe_exhibitors : (isset($map_number_exhibitors) ? (int)$map_number_exhibitors : 0);

        $pwe_countries            = do_shortcode('[pwe_countries]');
        $map_number_countries     = !empty($pwe_countries) ? (int)$pwe_countries : (isset($map_number_countries) ? (int)$map_number_countries : 0);

        $pwe_area                 = do_shortcode('[pwe_area]');
        $map_exhibition_space     = !empty($pwe_area) ? (int)$pwe_area : (isset($map_exhibition_space) ? (int)$map_exhibition_space : 0);

        $pwe_visitors_prev        = do_shortcode('[pwe_visitors_prev]');
        $map_number_visitors_previous = !empty($pwe_visitors_prev) ? (int)$pwe_visitors_prev : (isset($map_number_visitors_previous) ? (int)$map_number_visitors_previous : 0);

        $pwe_visitors_foreign_prev = do_shortcode('[pwe_visitors_foreign_prev]');
        $map_number_abroad_visitors_previous = !empty($pwe_visitors_foreign_prev) ? (int)$pwe_visitors_foreign_prev : (isset($map_number_abroad_visitors_previous) ? (int)$map_number_abroad_visitors_previous : 0);

        $pwe_exhibitors_prev      = do_shortcode('[pwe_exhibitors_prev]');
        $map_number_exhibitors_previous = !empty($pwe_exhibitors_prev) ? (int)$pwe_exhibitors_prev : (isset($map_number_exhibitors_previous) ? (int)$map_number_exhibitors_previous : 0);

        $pwe_countries_prev       = do_shortcode('[pwe_countries_prev]');
        $map_number_countries_previous = !empty($pwe_countries_prev) ? (int)$pwe_countries_prev : 0;

        $pwe_area_prev            = do_shortcode('[pwe_area_prev]');
        $map_exhibition_space_previous = !empty($pwe_area_prev) ? (int)$pwe_area_prev : (isset($map_exhibition_space_previous) ? (int)$map_exhibition_space_previous : 0);

        $pwe_statistics_year_curr = do_shortcode('[pwe_statistics_year_curr]');
        $map_year                 = !empty($pwe_statistics_year_curr) ? (int)$pwe_statistics_year_curr : (isset($map_year) ? (int)$map_year : 0);

        $pwe_statistics_year_prev = do_shortcode('[pwe_statistics_year_prev]');
        $map_year_previous        = !empty($pwe_statistics_year_prev) ? (int)$pwe_statistics_year_prev : (isset($map_year_previous) ? (int)$map_year_previous : 0);

        $polish_visitors = max(0, $map_number_visitors - $map_number_abroad_visitors);

        if ($map_number_visitors > 0) {
            $percent_polish = ($polish_visitors / $map_number_visitors) * 100;
            $percent_abroad = ($map_number_abroad_visitors / $map_number_visitors) * 100;
        } else {
            $percent_polish = 0;
            $percent_abroad = 0;
        }

        $map_number_visitors         = (int)$map_number_visitors;
        $map_percent_polish_visitors = round($percent_polish);
        $map_number_countries        = !empty($map_number_countries) ? (int)$map_number_countries : 15;


        $map_more_logotypes = (isset($atts['map_more_logotypes'])) ? explode(';', $atts['map_more_logotypes']) : '';

        $map_custom_edition = (int) do_shortcode('[trade_fair_edition]');
        if ($map_custom_edition < 1) {
            $map_custom_edition = 1;
        }

        // if (!empty($map_dynamic_preset) && $map_dynamic_preset === 'preset_3') {
        //     if (!empty($map_year_previous) && !empty($map_custom_previous_edition)) {
        //         $map_custom_edition = $map_custom_previous_edition;
        //     }
        // }
        $map_custom_title = self::get_custom_title($map_custom_edition);

        if (!empty($map_number_visitors_previous)) {
            $max_visitors = max($map_number_visitors, $map_number_visitors_previous);
            $map_number_visitors_percentage = ($map_number_visitors / $max_visitors) * 100;
            $map_number_visitors_previous_percentage = ($map_number_visitors_previous / $max_visitors) * 100;

            $map_number_visitors_increase = round(100 - $map_number_visitors_previous_percentage);
        }

        if (!empty($map_number_exhibitors_previous)) {
            $max_exhibitors = max($map_number_exhibitors, $map_number_exhibitors_previous);
            $map_number_exhibitors_percentage = ($map_number_exhibitors / $max_exhibitors) * 100;
            $map_number_exhibitors_previous_percentage = ($map_number_exhibitors_previous / $max_exhibitors) * 100;

            $map_number_exhibitors_increase = round(100 - $map_number_exhibitors_previous_percentage);
        }

        if (!empty($map_exhibition_space_previous)) {
            $max_exhibition_space = max($map_exhibition_space, $map_exhibition_space_previous);
            $map_exhibition_space_percentage = ($map_exhibition_space / $max_exhibition_space) * 100;
            $map_exhibition_space_previous_percentage = ($map_exhibition_space_previous / $max_exhibition_space) * 100;

            $map_exhibition_space_increase = round(100 - $map_exhibition_space_previous_percentage);
        }

        // CSS <----------------------------------------------------------------------------------------------<
        require_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php';

        if ($map_dynamic_preset === 'preset_1') {
            require_once plugin_dir_path(__FILE__) . 'presets/map_dynamic_preset_1.php';
        } else if ($map_dynamic_preset === 'preset_2') {
            require_once plugin_dir_path(__FILE__) . 'presets/map_dynamic_preset_2.php';
        } else if ($map_dynamic_preset === 'preset_3') {
            require_once plugin_dir_path(__FILE__) . 'presets/map_dynamic_preset_3.php';
        }

        return $output;
    }
}