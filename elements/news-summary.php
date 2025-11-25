<?php 

/**
 * Class PWElementNumbers
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementNewsSummary extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Sumarry Fair Domain', 'pwelement'),
                'param_name' => 'pwe_news_summary_domain',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('News Title', 'pwelement'),
                'param_name' => 'pwe_news_summary_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('News Description', 'pwelement'),
                'param_name' => 'pwe_news_summary_desc',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('News Statistics Title', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('News Statistics Subtitle', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_subtitle',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of countries', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_countries',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Current year', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_year',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Previous year', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_year_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of visitors (current period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_visitors',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of visitors (previous period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_visitors_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of exhibitors (current period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_exhibitors',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number of exhibitors (previous period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_exhibitors_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Exhibition space (current period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_space',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Exhibition space (previous period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_space_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('News iframe link', 'pwelement'),
                'param_name' => 'pwe_news_summary_iframe_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('News iframe Description', 'pwelement'),
                'param_name' => 'pwe_news_summary_iframe_desc',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'attach_images',
                'group' => 'PWE Element',
                'heading' => __('Select Images', 'pwelement'),
                'param_name' => 'pwe_news_summary_images',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('News Medal Description', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_desc',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Button title', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_button_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Button link', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_button_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'attach_images',
                'group' => 'PWE Element',
                'heading' => __('Select Images', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_images',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Conf Summary title', 'pwelement'),
                'param_name' => 'pwe_news_summary_conf_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Conf Summary desc', 'pwelement'),
                'param_name' => 'pwe_news_summary_conf_desc',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Button link', 'pwelement'),
                'param_name' => 'pwe_news_summary_conf_button_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Next edition title', 'pwelement'),
                'param_name' => 'pwe_news_summary_next_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Next edition title', 'pwelement'),
                'param_name' => 'pwe_news_summary_next_desc',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNewsSummary',
                ),
            ),
        );
        return $element_output;
    }    

    public static function output($atts) {  

       extract( shortcode_atts( array(
            'pwe_news_summary_domain'               => '',
            'pwe_news_summary_title'                => '',
            'pwe_news_summary_desc'                 => '',
            'pwe_news_summary_stats_title'          => '',
            'pwe_news_summary_stats_subtitle'       => '',
            'pwe_news_summary_stats_countries'      => '',
            'pwe_news_summary_stats_year'           => '',
            'pwe_news_summary_stats_year_previous'  => '',
            'pwe_news_summary_stats_visitors'       => '',
            'pwe_news_summary_stats_visitors_previous' => '',
            'pwe_news_summary_stats_exhibitors'     => '',
            'pwe_news_summary_stats_exhibitors_previous' => '',
            'pwe_news_summary_stats_space'          => '',
            'pwe_news_summary_stats_space_previous' => '',
            'pwe_news_summary_iframe_link'          => '',
            'pwe_news_summary_iframe_desc'          => '',
            'pwe_news_summary_images'               => '',
            'pwe_news_summary_medals_desc'          => '',
            'pwe_news_summary_medals_button_title'  => '',
            'pwe_news_summary_medals_button_link'   => '',
            'pwe_news_summary_medals_images'        => '',
            'pwe_news_summary_conf_title'           => '',
            'pwe_news_summary_conf_desc'            => '',
            'pwe_news_summary_conf_button_link'     => '',
            'pwe_news_summary_next_title'           => '',
            'pwe_news_summary_next_desc'            => '',
        ), $atts ));

        $id_rnd = PWECommonFunctions::id_rnd();

        $pwe_news_summary_desc = PWECommonFunctions::decode_clean_content($pwe_news_summary_desc);
        $pwe_news_summary_conf_desc = PWECommonFunctions::decode_clean_content($pwe_news_summary_conf_desc);

        // ODWIEDZAJĄCY
        $vis_curr = $pwe_news_summary_stats_visitors;
        $vis_prev = $pwe_news_summary_stats_visitors_previous;

        if ($vis_prev > 0) {
            $max = max($vis_curr, $vis_prev);
            $pwe_news_summary_stats_visitors_previous_percentage = ($vis_prev / $max) * 100;
            $pwe_news_summary_stats_visitors_percentage          = ($vis_curr / $max) * 100;
            $pwe_news_summary_stats_visitors_increase            = round(100 - $pwe_news_summary_stats_visitors_previous_percentage);
        } else {
            $pwe_news_summary_stats_visitors_previous_percentage = 0;
            $pwe_news_summary_stats_visitors_percentage          = $vis_curr > 0 ? 100 : 0;
            $pwe_news_summary_stats_visitors_increase            = null;
        }

        // WYSTAWCY
        $exh_curr = $pwe_news_summary_stats_exhibitors;
        $exh_prev = $pwe_news_summary_stats_exhibitors_previous;

        if ($exh_prev > 0) {
            $max = max($exh_curr, $exh_prev);
            $pwe_news_summary_stats_exhibitors_previous_percentage = ($exh_prev / $max) * 100;
            $pwe_news_summary_stats_exhibitors_percentage          = ($exh_curr / $max) * 100;
            $pwe_news_summary_stats_exhibitors_increase            = round(100 - $pwe_news_summary_stats_exhibitors_previous_percentage);
            $pwe_news_summary_stats_exhibitors_growth_percent      = round((($exh_curr - $exh_prev)/$exh_prev)*100);
        } else {
            $pwe_news_summary_stats_exhibitors_previous_percentage = 0;
            $pwe_news_summary_stats_exhibitors_percentage          = $exh_curr > 0 ? 100 : 0;
            $pwe_news_summary_stats_exhibitors_increase            = null;
            $pwe_news_summary_stats_exhibitors_growth_percent      = 0;
        }

        // POWIERZCHNIA
        $spc_curr = $pwe_news_summary_stats_space;
        $spc_prev = $pwe_news_summary_stats_space_previous;

        if ($spc_prev > 0) {
            $max = max($spc_curr, $spc_prev);
            $pwe_news_summary_stats_space_previous_percentage = ($spc_prev / $max) * 100;
            $pwe_news_summary_stats_space_percentage          = ($spc_curr / $max) * 100;
            $pwe_news_summary_stats_space_increase            = round(100 - $pwe_news_summary_stats_space_previous_percentage);
        } else {
            $pwe_news_summary_stats_space_previous_percentage = 0;
            $pwe_news_summary_stats_space_percentage          = $spc_curr > 0 ? 100 : 0;
            $pwe_news_summary_stats_space_increase            = null;
        }

        // WZOST WYSTAWCÓW
        $pwe_news_summary_stats_exhibitors_growth_percent = 0;
        if (is_numeric($pwe_news_summary_stats_exhibitors_previous) && $pwe_news_summary_stats_exhibitors_previous > 0) {
            $pwe_news_summary_stats_exhibitors_growth_percent =
                round((($pwe_news_summary_stats_exhibitors - $pwe_news_summary_stats_exhibitors_previous)
                / $pwe_news_summary_stats_exhibitors_previous) * 100);
        }

        // --- GALLERY SLIDER (z attach_images) ---
        $gallery_ids = array_filter(array_map('intval', explode(',', (string) $pwe_news_summary_images)));
        $gallery_html = '';

        if (!empty($gallery_ids)) {
            foreach ($gallery_ids as $img_id) {
                // responsywny <img> z alt
                $img = wp_get_attachment_image($img_id, 'medium_large', false, array(
                    'class'   => 'pwe-news-summary__gallery-img',
                    'loading' => 'lazy',
                    'decoding'=> 'async',
                ));
                if ($img) {
                    $gallery_html .= '<div class="pwe-news-summary__gallery-slide">'.$img.'</div>';
                }
            }
        }

        /* ======= JEDNORAZOWE NADPISANIE LINKÓW ======= */
        $current_host  = parse_url(home_url(), PHP_URL_HOST);
        $is_warsawexpo = ($current_host === 'warsawexpo.eu');

        $add_utms_if_none = function (string $url): string {
            if ($url === '') return $url;
            if (preg_match('/[?&]utm_[^=]+=/i', $url)) return $url;

            $fragment = '';
            if (false !== ($pos = strpos($url, '#'))) {
                $fragment = substr($url, $pos);
                $url      = substr($url, 0, $pos);
            }
            $sep = (strpos($url, '?') !== false) ? '&' : '?';
            return $url . $sep . 'utm_source=warsawexpo&utm_medium=news&utm_campaign=refferal' . $fragment;
        };

        $pwe_news_summary_reg_link = 'https://' . $pwe_news_summary_domain . self::languageChecker('/rejestracja/', '/en/registration/');
        $pwe_news_summary_exh_link = 'https://' . $pwe_news_summary_domain . self::languageChecker('/zostan-wystawca/', '/en/become-an-exhibitor/');

        if ($is_warsawexpo) {
            $pwe_news_summary_medals_button_link = $add_utms_if_none($pwe_news_summary_medals_button_link);
            $pwe_news_summary_conf_button_link   = $add_utms_if_none($pwe_news_summary_conf_button_link);

            $pwe_news_summary_reg_link = $add_utms_if_none(
                'https://' . $pwe_news_summary_domain . self::languageChecker('/rejestracja/', '/en/registration/')
            );
            $pwe_news_summary_exh_link = $add_utms_if_none(
                'https://' . $pwe_news_summary_domain . self::languageChecker('/zostan-wystawca/', '/en/become-an-exhibitor/')
            );
        }

        $a_target = $is_warsawexpo ? ' target="_blank"' : '';

        $post_id = get_the_ID();
        $thumbnail_id = get_post_thumbnail_id($post_id);
        $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'full');

        $accent_color = do_shortcode('[pwe_color_accent domain="' . $pwe_news_summary_domain . '"]');

        $output = '';

        $output .= '
        <style>
            .pwe-news-summary__hr {
                margin: 36px 0;
            }
            .pwe-news-summary__btn {
                padding: 13px 31px;
                font-size: 14px;
                min-width: 168px;
                text-align: center;
                font-weight: 600;
                color: #ffffff !important;
                background-color: var(--accent-color);
                border: 2px solid;
                border-color: var(--accent-color);
                border-radius: 8px;
            }
            .pwe-news-summary__btn:hover {
                color: var(--accent-color) !important;
                background-color: transparent;
            }
            .pwe-news-summary__btn--black {
                padding: 13px 31px;
                font-size: 14px;
                min-width: 168px;
                text-align: center;
                font-weight: 600;
                color: #ffffff !important;
                background-color: black;
                border: 2px solid;
                border-color: black;
                border-radius: 8px;
            }
            .pwe-news-summary__btn--black:hover {
                color: black !important;
                background-color: transparent;
            }
            .pwe-news-summary__header {
                width: 100%;
            }
            .pwe-news-summary__title {
                font-size: 32px !important;
            }
            .pwe-news-summary-stats .countup {
                text-shadow: 1px 1px 1px white;
            }
            .pwe-news-summary-stats__wrapper {
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 36px;
            }
            .pwe-news-summary-stats__title {
                margin-top: 0;
                text-transform: uppercase;
                font-size: 26px !important;
                text-shadow: 0px 0px 2px white;
            }
            .pwe-news-summary-stats__subtitle {
                margin-top: 0;
                color: ' . $accent_color . ';
            }
            .pwe-news-summary-stats__stats-section {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                position: relative;
                z-index: 1;
                padding: 18px 0;
            }
            .pwe-news-summary-stats__stats-diagram {
                width: 62%;
                min-height: 300px;
                display: flex;
                justify-content: center;
                gap: 36px;
                flex-wrap: wrap;
            }
            .pwe-news-summary-stats__stats-diagram-container {
                width: 64%;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 36px;
            }
            .pwe-news-summary-stats__stats-diagram-years-container {
                width: 100%;
                display: flex;
                justify-content: center;
                gap: 24px;
            }
            .pwe-news-summary-stats__stats-diagram-year {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .pwe-news-summary-stats__stats-diagram-year-box {
                width: 20px;
                aspect-ratio: 1 / 1;
                background: ' . $accent_color . ';
            }
            .pwe-news-summary-stats__stats-diagram-year:first-of-type .pwe-news-summary-stats__stats-diagram-year-box {
                background: color-mix(in srgb, ' . $accent_color . ', white 50%);
            }
            .pwe-news-summary-stats__stats-diagram-bars-container {
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
            }
            .pwe-news-summary-stats__stats-diagram-bars {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .pwe-news-summary-stats__stats-diagram-bars-wrapper {
                display: flex;
                gap: 18px;
                justify-content: center;
            }
            .pwe-news-summary-stats__stats-diagram-bar {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                justify-content: flex-end;
                height: 150px;
            }
            .pwe-news-summary-stats__stats-diagram-bar-item {
                background: ' . $accent_color . ';
                width: 42px;
                position: relative;
                display: flex;
                align-items: flex-end;
                justify-content: center;
            }
            .pwe-news-summary-stats__stats-diagram-bar:first-of-type .pwe-news-summary-stats__stats-diagram-bar-item {
                background: color-mix(in srgb, ' . $accent_color . ', white 50%);
            }
            .pwe-news-summary-stats__stats-diagram-bar-number {
                position: absolute;
                bottom: 100%;
                transform: translateY(0);
                text-align: center;
                font-size: 16px;
                font-weight: 600;
            }
            .pwe-news-summary-stats__stats-diagram-bar-number sup {
                top: 0;
            }
            .pwe-news-summary-stats__stats-diagram-bars-label {
                margin-top: 8px;
                text-align: center;
                font-size: 16px;
                font-weight: 600;
            }
            .pwe-news-summary-stats__stats-section span, .pwe-news-summary-stats__stats-section p {
                text-align: center;
                font-size: 16px;
                font-weight: 600;
            }
            .pwe-news-summary-stats__stats-diagram-countries-container {
                width: 18%;
                min-width: 150px;
                display: flex;
                justify-content: center;
                align-items: flex-start;
            }
            .pwe-news-summary-stats__stats-diagram-countries {
                width: 140px;
                aspect-ratio: 1 / 1;
                border-radius: 50%;
                border: 2px solid;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }
            .pwe-news-summary-stats__stats-diagram-countries *{
                margin: 0;
            }
            .pwe-news-summary-stats__stats-diagram-countries h2 {
                min-width: unset;
            }
            .pwe-news-summary-stats__stats-diagram-countries h2 span {
                color: ' . $accent_color . ';
                font-size: 40px;
                font-weight: 800;
            }
            .pwe-news-summary-stats__stats-number-container {
                width: 42%;
                display: flex;
                flex-direction: column;
                gap: 36px;
            }
            .pwe-news-summary-stats__stats-number-container .pwe-news-summary-stats__stats-number-box {
                display: flex;
                align-items: center;
                gap: 24px;
            }
            .pwe-news-summary-stats__stats-number-box-text {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }
            .pwe-news-summary-stats__stats-number-container .pwe-news-summary-stats__stats-number-box h2 {
                min-width: 200px;
                text-align: right;
            }
            .pwe-news-summary-stats__stats-number-container .pwe-news-summary-stats__stats-number-box h2 span {
                font-size: 40px;
                font-weight: 800;
            }
            .pwe-news-summary-stats__stats-number-box-text span {
                color: ' . $accent_color . ';
                font-weight: 800;
            }
            .pwe-news-summary-stats__stats-number-box-text p {
                text-transform: uppercase;
                font-size: 12px;
                text-align: left;
            }
            .pwe-news-summary-stats__stats-number-box *{
                margin: 0 !important;
            }
            @media(max-width:960px){
                .pwe-news-summary-stats__stats-section {
                    align-items: center;
                    gap: 36px;
                    flex-wrap: wrap;
                }
                .pwe-news-summary-stats__stats-diagram {
                    width: 50%;
                    min-height: 450px;
                    flex-direction: column-reverse;
                    align-items: center;
                    min-width: 360px;
                }
                .pwe-news-summary-stats__stats-diagram-container {
                    width: 100%;
                }
                .pwe-news-summary-stats__stats-number-container {
                    min-width: 350px;
                }
                .pwe-news-summary-stats__stats-number-container {
                    min-width: 280px;
                }
                .pwe-news-summary-stats__stats-number-container .pwe-news-summary-stats__stats-number-box h2 {
                    min-width: 160px;
                    font-size: 30px;
                }
            }
            @media(max-width:760px){
                .pwe-news-summary-stats__stats-section {
                    flex-direction: column-reverse;
                }
                .pwe-news-summary-stats__stats-diagram {
                    width: 100%;
                }
                .pwe-news-summary-stats__stats-diagram-bars-container {
                    max-width: 500px;
                    min-height: 240px;
                }
                .pwe-news-summary-stats__stats-number-container {
                    width: 100%;
                }
                .pwe-news-summary-stats__stats-number-box:not(.pwe-news-summary-stats__stats-diagram-countries) h2 {
                    width: 50%;
                }
            }
            @media(max-width:420px) {
                .pwe-news-summary-stats__title {
                    font-size: 20px !important;
                }
                .pwe-news-summary-stats__stats-section {
                    align-content: center;
                    padding: 36px 0;
                }
                .pwe-news-summary-stats__stats-diagram {
                    min-width: unset;
                }
                .pwe-news-summary-stats__stats-number-container .pwe-news-summary-stats__stats-number-box {
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 0;
                }
                .pwe-news-summary-stats__stats-number-box:not(.pwe-news-summary-stats__stats-diagram-countries) h2 {
                    width: 100%;
                    text-align: center;
                }
                .pwe-news-summary-stats__stats-number-box-text span {
                    display: none;
                }
                .pwe-news-summary-stats__stats-diagram-bar-number, .pwe-news-summary-stats__stats-diagram-bars-label {
                    font-size: 12px !important;
                }
                .pwe-news-summary-stats__stats-diagram-bar-item {
                    width: 20px;
                }
            }

            .pwe-news-summary-stats__container-3d {
                position: absolute;
                width: 100%;
                max-width: 800px;
                display: flex;
                align-items: center;
                bottom: -50%;
                left: clamp(110%,82vw,200%);
                transform: translate(-50%, 0);
                z-index: 0;
            }
            .pwe-news-summary-stats__container-3d::before {
                content: "";
                background: linear-gradient(20deg, white 10%, transparent 70%);
                width: 100%;
                height: 100%;
                position: absolute;
            }
            .pwe-news-summary-stats__container-3d canvas {
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 1 / 1;
            }
            @media(max-width:960px){
                .pwe-news-summary-stats__container-3d {
                    bottom: -25%;
                }
            }
            @media(max-width:960px){
                .pwe-news-summary-stats__container-3d {
                    bottom: -25%;
                }
            }
            @media(max-width:760px){
                .pwe-news-summary-stats__container-3d {
                    bottom: 5%;
                }
            }
            @media(max-width:600px){
                .pwe-news-summary-stats__container-3d {
                    min-width: 500px;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }
            }
            @media(max-width:420px){
                .pwe-news-summary-stats__container-3d {
                    bottom: 10%;
                }
            }



            .pwe-news-summary-stats__cards {
                display: flex;
                align-items: stretch;
                gap: 24px;
                margin-top: 24px;
            }
            .pwe-news-summary-stats__card {
                flex: 1 1 30%;
                padding: 24px;
                border-radius: 18px;
                background-color: #f7f7f7;
                box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.05);
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 18px;
            }
            .pwe-news-summary-stats__card h5 {
                font-size: 16px;
                text-align: center;
                margin: 0;
            }
            .pwe-news-summary-stats__card p {
                text-align: center;
                margin: auto;
            }



            .pwe-news-summary__iframe-container {
                display: flex;
                align-items: stretch;
                gap: 24px;
            }
            .pwe-news-summary__iframe-container iframe {
                flex: .6;
                border-radius: 24px;
                box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.05);
            }
            .pwe-news-summary__iframe-desc {
                flex: .4;
            }
            .pwe-news-summary__gallery-slide {
                aspect-ratio: 3/2;
                margin: 5px;
            }
            img.pwe-news-summary__gallery-img {
                object-fit: cover;
                object-position: center;
                height: 100%;
            }



            .pwe-news-summary__medal-container {
                display: flex;
                align-items: center;
                justify-content: space-around;
                gap: 24px;
            }
            .pwe-news-summary__medal-container img {
                flex: .4;
                max-width: 320px !important;
                aspect-ratio: 1/1;
                object-fit: cover;
                border-radius: 18px;
                box-shadow: 0px 30px 60px -30px rgba(0,0,0,.45);
            }
            .pwe-news-summary__medal-desc {
                flex: .6;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 24px;
            }
            .pwe-news-summary__medal-desc p {
                text-align: center;
            }



            .pwe-news-summary__conf-container {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 18px;
            }
            .pwe-news-summary__conf-title {
                margin: 0;
            }
            .pwe-news-summary__conf-container p {
                margin: 0;
            }
            

            .pwe-news-summary__next {
                display: flex;
                align-items: center;
                gap: 24px;
            }
            .pwe-news-summary__next-content {
                flex: .7;
                display: flex;
                flex-direction: column;
                gap: 18px;
            }
            .pwe-news-summary__next img {
                flex: .3;
                max-width: 360px;
                border-radius: 50%;
                box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.05);
            }
            .pwe-news-summary__next-btn-container {
                display: flex;
                justify-content: space-around;
                gap: 18px;
                padding: 18px 18px;
            }


            .pwe-news-summary__linkedin {
                display: flex;
                flex-direction: column;
                gap: 24px;
            }
            .pwe-news-summary__linkedin-content {
                display: flex;
                align-items: center;
                gap: 24px;
            }
            .pwe-news-summary__linkedin-content img {
                max-width: 260px;
            }
            .pwe-news-summary__linkedin-content-text {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }
            .pwe-news-summary__linkedin-title {
                margin: 0;
            }
            .pwe-news-summary__linkedin-subtitle {
                margin: 0;
                font-size: 18px !important;
            }
            .pwe-news-summary__linkedin-footer {
                display: flex;
                justify-content: space-between;
                gap: 24px;
            }


            @media(max-width:600px) {
                .pwe-news-summary__hr {
                    margin: 24px 0;
                }
                .pwe-news-summary__btn, .pwe-news-summary__btn--black {
                    padding: 9px 18px;
                    margin: 0 auto;
                }
                .pwe-news-summary__title {
                    font-size: 22px !important;
                }
                .pwe-news-summary-stats__cards {
                    flex-direction: column;
                }
                .pwe-news-summary__iframe-container {
                    flex-direction: column;
                }
                .pwe-news-summary__medal-container {
                    flex-direction: column;
                }
                .pwe-news-summary__next {
                    flex-direction: column-reverse;
                }
                .pwe-news-summary__next-btn-container { 
                    flex-direction: column;
                }
                .pwe-news-summary__linkedin-content {
                    flex-direction: column;
                }
                .pwe-news-summary__linkedin-footer {
                    flex-direction: column;
                }
            }

        </style>';

        $output .= '
        <div class="pwe-news-summary" id="PWENewsSummary">';
            if (!$is_warsawexpo) {
                $output .= '
                <img class="pwe-news-summary__header" src="' . $thumbnail_url . '" alt="'. self::languageChecker('Grafika nagłówkowa artykułu', 'Article header graphic') .'">';
            }
            $output .= '
            <h1 class="pwe-news-summary__title">' . $pwe_news_summary_title . '</h1>
            <hr class="pwe-news-summary__hr">
            <p class="pwe-news-summary__desc">' . $pwe_news_summary_desc . '</p>
            <hr class="pwe-news-summary__hr">';

            $output .= '
            <div class="pwe-news-summary-stats">
                <div class="pwe-news-summary-stats__wrapper">

                    <div class="pwe-news-summary-stats__title-section">
                        <h2 class="pwe-news-summary-stats__title">'. $pwe_news_summary_stats_title .'</h2>
                        <p class="pwe-news-summary-stats__subtitle">'. $pwe_news_summary_stats_subtitle .'</p> 
                    </div>

                    <div class="pwe-news-summary-stats__stats-section">
                        <div class="pwe-news-summary-stats__stats-diagram">
                            <div class="pwe-news-summary-stats__stats-diagram-container">
                                <!-- Years -->
                                <div class="pwe-news-summary-stats__stats-diagram-years-container">';
                                    if ($vis_prev > 0 || $exh_prev > 0 || $spc_prev > 0) {
                                        $output .= '
                                        <div class="pwe-news-summary-stats__stats-diagram-year">
                                            <div class="pwe-news-summary-stats__stats-diagram-year-box"></div>
                                            <span>'. $pwe_news_summary_stats_year_previous .'</span>
                                        </div>';
                                    }
                                    $output .= '
                                    <div class="pwe-news-summary-stats__stats-diagram-year">
                                        <div class="pwe-news-summary-stats__stats-diagram-year-box"></div>
                                        <span>'. $pwe_news_summary_stats_year .'</span>
                                    </div>
                                </div>

                                <!-- Bars -->
                                <div class="pwe-news-summary-stats__stats-diagram-bars-container"> 
                                    <!-- Bar 1 -->
                                    <div class="pwe-news-summary-stats__stats-diagram-bars">
                                        <div class="pwe-news-summary-stats__stats-diagram-bars-wrapper">';
                                            if ($vis_prev > 0) {
                                                $output .= '
                                                <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $pwe_news_summary_stats_visitors_previous_percentage .'">
                                                        <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $pwe_news_summary_stats_visitors_previous .'">0</span></div>
                                                    </div>
                                                </div>';
                                            }
                                            $output .= '
                                            <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $pwe_news_summary_stats_visitors_percentage .'">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $pwe_news_summary_stats_visitors .'">0</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="pwe-news-summary-stats__stats-diagram-bars-label">'. self::languageChecker('Odwiedzający', 'Visitors') .'</p>
                                    </div>

                                    <!-- Bar 2 -->
                                    <div class="pwe-news-summary-stats__stats-diagram-bars">
                                        <div class="pwe-news-summary-stats__stats-diagram-bars-wrapper">';
                                            if ($exh_prev > 0) {
                                                $output .= '
                                                <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $pwe_news_summary_stats_exhibitors_previous_percentage .'">
                                                        <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $pwe_news_summary_stats_exhibitors_previous .'">0</span></div>
                                                    </div>
                                                </div>';
                                            }
                                            $output .= '
                                            <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $pwe_news_summary_stats_exhibitors_percentage .'">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $pwe_news_summary_stats_exhibitors .'">0</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="pwe-news-summary-stats__stats-diagram-bars-label">'. self::languageChecker('Wystawcy', 'Exhibitors') .'</p>
                                    </div>

                                    <!-- Bar 3 -->
                                    <div class="pwe-news-summary-stats__stats-diagram-bars">
                                        <div class="pwe-news-summary-stats__stats-diagram-bars-wrapper">';
                                            if ($spc_prev > 0) {
                                                $output .= '
                                                <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $pwe_news_summary_stats_space_previous_percentage .'">
                                                        <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $pwe_news_summary_stats_space_previous .'">0</span> m<sup>2</sup></div>
                                                    </div>
                                                </div>';
                                            }
                                            $output .= '
                                            <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $pwe_news_summary_stats_space_percentage .'">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $pwe_news_summary_stats_space .'">0</span> m<sup>2</sup></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="pwe-news-summary-stats__stats-diagram-bars-label">'. self::languageChecker('Powierzchnia', 'Surface') .'</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Countries -->
                            <div class="pwe-news-summary-stats__stats-diagram-countries-container">
                                <div class="pwe-news-summary-stats__stats-diagram-countries pwe-news-summary-stats__stats-number-box">
                                    <h2><span class="countup" data-count="'. $pwe_news_summary_stats_countries .'">0</span></h2>
                                    <p>'. self::languageChecker('krajów', 'countries') .'</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pwe-news-summary-stats__stats-number-container">
                            <div class="pwe-news-summary-stats__stats-number-box">
                                <h2><span class="countup" data-count="'. $pwe_news_summary_stats_visitors .'">0</span></h2>
                                <div class="pwe-news-summary-stats__stats-number-box-text">
                                    <span>+</span>
                                    <p>'. self::languageChecker('odwiedzających', 'visitors') .'</p>
                                </div>
                            </div>
                            
                            <div class="pwe-news-summary-stats__stats-number-box">
                                <h2><span class="countup" data-count="'. $pwe_news_summary_stats_exhibitors .'">0</span></h2>
                                <div class="pwe-news-summary-stats__stats-number-box-text">
                                    <span>+</span>
                                    <p>'. self::languageChecker('wystawców', 'exhibitors') .'</p>
                                </div>
                            </div>
                            
                            <div class="pwe-news-summary-stats__stats-number-box">
                                <h2><span class="countup" data-count="'. $pwe_news_summary_stats_space .'">0</span> m<sup>2</sup></h2>
                                <div class="pwe-news-summary-stats__stats-number-box-text">
                                    <span>+</span>
                                    <p>'. self::languageChecker('powierzchni wystawienniczej', 'exhibition space') .'</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pwe-news-summary-stats__cards">
                    <div class="pwe-news-summary-stats__card">
                        <h5>'. self::languageChecker('Powierzchnia wystawiennicza:', 'Exhibition area:') .'</h5>
                        <p>'. self::languageChecker('Całkowita powierzchnia:', 'Total area:') .' <span style="color: #00b050;">' . $pwe_news_summary_stats_space . '</span> m².</p>
                    </div>
                    <div class="pwe-news-summary-stats__card">
                        <h5>'. self::languageChecker('Wystawcy:', 'Exhibitors:') .'</h5>';
                        if ($exh_prev > 0 && !empty($pwe_news_summary_stats_year_previous)) {
                            $output .='
                            <p><span style="color: #00b050;">' . $pwe_news_summary_stats_exhibitors . '</span> '. self::languageChecker('prezentujących swoje rozwiązania. Wzrost o', 'companies presenting their solutions. An increase of') .' <span style="color: #00b050;">' . $pwe_news_summary_stats_exhibitors_growth_percent . '%</span> '. self::languageChecker('w porównaniu do 2024 roku.', 'compared to 2024.') .'</p>';
                        } else {
                            $output .='
                            <p><span style="color: #00b050;">' . $pwe_news_summary_stats_exhibitors . '</span> '. self::languageChecker('firm z różnych branż zaprezentowało podczas wydarzenia swoje innowacyjne rozwiązania, produkty i technologie.', 'companies from various sectors presented their innovative solutions, products, and technologies during the event.', 'companies from various sectors presented their innovative solutions, products, and technologies during the event.') .'</p>';
                        }
                    $output .='
                    </div>
                    <div class="pwe-news-summary-stats__card">
                        <h5>'. self::languageChecker('Kraje uczestniczące:', 'Participating countries:') .'</h5>
                        <p>'. self::languageChecker('Reprezentanci', 'Representatives from') .'  <span style="color: #00b050;">' . $pwe_news_summary_stats_countries . ' '. self::languageChecker('krajów', 'countries') .' </span>, '. self::languageChecker('co nadało targom międzynarodowy charakter.', 'which gave the fair an international character.') .'</p>
                    </div>
                </div>
            </div>
            <hr class="pwe-news-summary__hr">';

            $output .= '
            <div class="pwe-news-summary__iframe" id="PWENewsSummaryIframe">
                <div class="pwe-news-summary__iframe-container">
                    <iframe width="560" height="315" src="' . $pwe_news_summary_iframe_link . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    <div class="pwe-news-summary__iframe-desc">
                        <p>' . $pwe_news_summary_iframe_desc . '</p>
                    </div>
                </div>
                <div class="pwe-news-summary__gallery-container">
                    <div class="pwe-news-summary__gallery-slider">
                        ' . $gallery_html . '
                    </div>
                </div>
            </div>
            <hr class="pwe-news-summary__hr">';

            $output .= '
            <div class="pwe-news-summary__medal" id="PWENewsSummaryMedal">
                <div class="pwe-news-summary__medal-container">
                    <div class="pwe-news-summary__medal-desc">
                        <p>' . $pwe_news_summary_medals_desc . '</p>
                        <a class="pwe-news-summary__btn" href="' . $pwe_news_summary_medals_button_link . '"' . $a_target . '>' . $pwe_news_summary_medals_button_title . '</a>
                    </div>
                    <img src="' . wp_get_attachment_url($pwe_news_summary_medals_images) . '" alt="'. self::languageChecker('Medale dla najlepszych wystawców', 'Medals for the best exhibitors') .'">
                </div>
            </div>
            <hr class="pwe-news-summary__hr">';

            $output .= '
            <div class="pwe-news-summary__conf" id="PWENewsSummaryConf">
                <div class="pwe-news-summary__conf-container">
                    <h2 class="pwe-news-summary__conf-title">' . $pwe_news_summary_conf_title . '</h2>
                    <p class="pwe-news-summary__conf-desc">' . $pwe_news_summary_conf_desc . '</p>
                    <a class="pwe-news-summary__btn--black" href="' . $pwe_news_summary_conf_button_link . '" ' . $a_target . '>'. self::languageChecker('Sprawdź relację z konferencji', 'See the conference highlights') .'</a>
                </div>
            </div>
            <hr class="pwe-news-summary__hr">';

            $output .= '
            <div class="pwe-news-summary__next" id="PWENewsSummaryNext">
                <div class="pwe-news-summary__next-content">
                    <h2 class="pwe-news-summary__next-title">' . $pwe_news_summary_next_title . '</h2>
                    <p class="pwe-news-summary__next-desc">' . $pwe_news_summary_next_desc . '</p>
                    <div class="pwe-news-summary__next-btn-container">
                        <a class="pwe-news-summary__btn" href="' . $pwe_news_summary_reg_link . '" ' . $a_target . '>'. self::languageChecker('Zarejestruj się', 'Register') .'</a>
                        <a class="pwe-news-summary__btn--black" href="' . $pwe_news_summary_exh_link . '" ' . $a_target . '>'. self::languageChecker('Zostań wystawcą', 'Become an exhibitor') .'</a>
                    </div>
                </div>
                <img src="https://' . $pwe_news_summary_domain . '/doc/kafelek.jpg" alt="'. self::languageChecker('Główne logo targów', 'Main trade fair logo') .'">
            </div>
            <hr class="pwe-news-summary__hr">';

            $output .= '
            <div class="pwe-news-summary__linkedin" id="PWENewsSummaryLinkedin">
                <div class="pwe-news-summary__linkedin-content">
                    <img src="https://mr.glasstec.pl/wp-content/plugins/pwe-media/media/nikodem.webp" alt="'. self::languageChecker('Nikodem Zygadło – dyrektor działu analiz', 'Nikodem Zygadło – Director of Analysis Department') .'">
                    <div class="pwe-news-summary__linkedin-content-text">
                        <h2 class="pwe-news-summary__linkedin-title">Nikodem Zygadło</h2>
                        <h3 class="pwe-news-summary__linkedin-subtitle">'. self::languageChecker('Dyrektor Działu Analiz | Z-ca Dyrektora ds. Rozwoju | Ptak Warsaw Expo', 'Director of Analysis Department | Deputy Director of Development | Ptak Warsaw Expo.') .'</h3>
                        <p class="pwe-news-summary__linkedin-desc">'. self::languageChecker('Pasjonat targów, z niemal 30-letnim doświadczeniem w tworzeniu, organizacji imprez i zarządzaniu projektami targowymi.', 'A trade fair enthusiast with nearly 30 years of experience in creating, organizing events and managing trade fair projects.') .'</p>
                    </div>
                </div>
                <div class="pwe-news-summary__linkedin-footer">
                    <p class="pwe-news-summary__linkedin-thx">'. self::languageChecker('Dziękujemy, że przeczytałaś/eś nasz artykuł do końca.', 'Thank you for reading our article to the end.') .'</p>
                    <a class="pwe-news-summary__btn--black" target="_blank" href="https://www.linkedin.com/build-relation/newsletter-follow?entityUrn=7185929412658302977">'. self::languageChecker('Dołącz do Newslettera na LinkedIn', 'Join the newsletter on LinkedIn') .'</a>
                </div>
            </div>
        </div>';

        $output .='
        <script>
            (function() {
            // Animacja liczb: od 0 do data-count
            function animateCount(el) {
                const raw = (el.getAttribute("data-count") || "0").toString().replace(/\s|,/g, "");
                const targetValue = parseFloat(raw) || 0;
                const duration = 3000;
                const start = performance.now();

                function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const current = Math.floor(progress * targetValue);
                el.textContent = current.toLocaleString();
                if (progress < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            }

            // Animacja słupków: wysokość do data-count%
            function animateBars() {
                const bars = document.querySelectorAll(".pwe-news-summary-stats__stats-diagram-bar-item");
                const duration = 1200;

                bars.forEach(bar => {
                const percent = parseFloat(bar.getAttribute("data-count")) || 0;
                bar.style.height = "0%";
                const start = performance.now();

                function grow(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const value = percent * progress;
                    bar.style.height = value + "%";
                    if (progress < 1) requestAnimationFrame(grow);
                }
                requestAnimationFrame(grow);
                });
            }

            // Inicjalizacja z IntersectionObserver (uruchamia się raz przy wejściu w viewport)
            document.addEventListener("DOMContentLoaded", function() {
                const countEls = document.querySelectorAll(".countup");
                const section = document.querySelector(".pwe-news-summary-stats");

                if (!section) return;

                const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;

                    // Odpal animacje słupków
                    animateBars();

                    // Odpal animacje liczników
                    countEls.forEach(el => animateCount(el));

                    obs.unobserve(entry.target);
                });
                }, { threshold: 0.1 });

                observer.observe(section);
            });
            })();

            jQuery(function($){
                var $slider = $(".pwe-news-summary__gallery-slider");

                if(!$slider.length) return;

                // Nie inicjuj drugi raz
                $slider.not(".slick-initialized").slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    swipeToSlide: true,
                    arrows: false,
                    dots: false,
                    responsive: [
                    { breakpoint: 480,  settings: { slidesToShow: 2 } }
                    ]
                });
            });
            </script>';

        return $output;
    }
}