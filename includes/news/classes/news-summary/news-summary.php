<?php
/**
 * Class PWENewsSummary
 * Szablon „News Summary” ładowany przez PWENews.
 */
class PWENewsSummary extends PWENews {

    /**
     * Uwaga: NIE wywołujemy parent::__construct(), żeby nie dublować hooków.
     */
    public function __construct() {}

    /**
     * Definicje pól dla WPBakery (jeśli gdzieś je łączysz z vc_map).
     * Poprawione dependency -> 'news_template_type'.
     */
    public static function initElements() {
        $dep = array(
            'element' => 'news_template_type',
            'value'   => 'PWENewsSummary',
        );

        return array(
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Summary Fair Domain', 'pwelement'),
                'param_name' => 'pwe_news_summary_domain',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('News Title', 'pwelement'),
                'param_name' => 'pwe_news_summary_title',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'News',
                'heading' => __('News Description', 'pwelement'),
                'param_name' => 'pwe_news_summary_desc',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('News Statistics Title', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_title',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('News Statistics Subtitle', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_subtitle',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Number of countries', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_countries',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Current year', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_year',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Previous year', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_year_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Number of visitors (current period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_visitors',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Number of visitors (previous period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_visitors_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Number of exhibitors (current period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_exhibitors',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Number of exhibitors (previous period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_exhibitors_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Exhibition space (current period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_space',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Exhibition space (previous period)', 'pwelement'),
                'param_name' => 'pwe_news_summary_stats_space_previous',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textarea',
                'group' => 'News',
                'heading' => __('News iframe title', 'pwelement'),
                'param_name' => 'pwe_news_summary_iframe_title',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('News iframe link', 'pwelement'),
                'param_name' => 'pwe_news_summary_iframe_link',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textarea',
                'group' => 'News',
                'heading' => __('News iframe Description', 'pwelement'),
                'param_name' => 'pwe_news_summary_iframe_desc',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'attach_images',
                'group' => 'News',
                'heading' => __('Select Images (gallery)', 'pwelement'),
                'param_name' => 'pwe_news_summary_images',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textarea',
                'group' => 'News',
                'heading' => __('News Medal Description', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_desc',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Button title', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_button_title',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Button link', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_button_link',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'attach_images',
                'group' => 'News',
                'heading' => __('Select Medal Image(s)', 'pwelement'),
                'param_name' => 'pwe_news_summary_medals_images',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Conf Summary title', 'pwelement'),
                'param_name' => 'pwe_news_summary_conf_title',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'News',
                'heading' => __('Conf Summary desc', 'pwelement'),
                'param_name' => 'pwe_news_summary_conf_desc',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Button link', 'pwelement'),
                'param_name' => 'pwe_news_summary_conf_button_link',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textfield',
                'group' => 'News',
                'heading' => __('Next edition title', 'pwelement'),
                'param_name' => 'pwe_news_summary_next_title',
                'save_always' => true,
                'dependency' => $dep,
            ),
            array(
                'type' => 'textarea',
                'group' => 'News',
                'heading' => __('Next edition description', 'pwelement'),
                'param_name' => 'pwe_news_summary_next_desc',
                'save_always' => true,
                'dependency' => $dep,
            ),
        );
    }

public static function output($atts) {

        
        $args = shortcode_atts( array(
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
            'pwe_news_summary_iframe_title'         => '',
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
        ), $atts );

        // mozna uzywac i $args['...'] i $pwe_news_summary_...
        extract($args);

        // Skróty z rzutowaniem
        $domain = trim((string)$args['pwe_news_summary_domain']);

        $vis_curr = (float)$args['pwe_news_summary_stats_visitors'];
        $vis_prev = (float)$args['pwe_news_summary_stats_visitors_previous'];

        $exh_curr = (float)$args['pwe_news_summary_stats_exhibitors'];
        $exh_prev = (float)$args['pwe_news_summary_stats_exhibitors_previous'];

        $spc_curr = (float)$args['pwe_news_summary_stats_space'];
        $spc_prev = (float)$args['pwe_news_summary_stats_space_previous'];

        $countries = (int)$args['pwe_news_summary_stats_countries'];

        $year_cur  = trim((string)$args['pwe_news_summary_stats_year']);
        $year_prev = trim((string)$args['pwe_news_summary_stats_year_previous']);

        $id_rnd = PWECommonFunctions::id_rnd();

        // Oczyszczone HTML
        $desc_html      = PWECommonFunctions::decode_clean_content($args['pwe_news_summary_desc']);
        $conf_desc_html = PWECommonFunctions::decode_clean_content($args['pwe_news_summary_conf_desc']);

        /* ======= KALKULACJE ======= */
        // Odwiedzający
        if ($vis_prev > 0) {
            $max = max($vis_curr, $vis_prev);
            $vis_prev_pct = ($max > 0) ? ($vis_prev / $max) * 100 : 0;
            $vis_curr_pct = ($max > 0) ? ($vis_curr / $max) * 100 : 0;
            $vis_increase = round(100 - $vis_prev_pct);
        } else {
            $vis_prev_pct = 0;
            $vis_curr_pct = ($vis_curr > 0) ? 100 : 0;
            $vis_increase = null;
        }

        // Wystawcy
        if ($exh_prev > 0) {
            $max = max($exh_curr, $exh_prev);
            $exh_prev_pct = ($exh_prev / $max) * 100;
            $exh_curr_pct = ($exh_curr / $max) * 100;
            $exh_increase = round(100 - $exh_prev_pct);
            $exh_growth_pct = round((($exh_curr - $exh_prev)/$exh_prev)*100);
        } else {
            $exh_prev_pct = 0;
            $exh_curr_pct = ($exh_curr > 0) ? 100 : 0;
            $exh_increase = null;
            $exh_growth_pct = 0;
        }

        // Powierzchnia
        if ($spc_prev > 0) {
            $max = max($spc_curr, $spc_prev);
            $spc_prev_pct = ($spc_prev / $max) * 100;
            $spc_curr_pct = ($spc_curr / $max) * 100;
            $spc_increase = round(100 - $spc_prev_pct);
        } else {
            $spc_prev_pct = 0;
            $spc_curr_pct = ($spc_curr > 0) ? 100 : 0;
            $spc_increase = null;
        }

        // --- GALLERY SLIDER (z attach_images) ---
        $gallery_ids = array_filter(array_map('intval', explode(',', (string)$args['pwe_news_summary_images'])));
        $gallery_html = '';

        if (!empty($gallery_ids)) {
            foreach ($gallery_ids as $img_id) {
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

        /* ======= JEDNORAZOWE NADPISANIE LINKÓW + UTMy ======= */
        $current_host  = parse_url(home_url(), PHP_URL_HOST);
        $is_warsawexpo = ($current_host === 'warsawexpo.eu');

        $add_utms_if_none = function (string $url): string {
            $url = trim($url);
            if ($url === '') return $url;
            if (preg_match('/[?&]utm_[^=]+=/i', $url)) return $url;

            $fragment = '';
            if (false !== ($pos = strpos($url, '#'))) {
                $fragment = substr($url, $pos);
                $url      = substr($url, 0, $pos);
            }
            $sep = (strpos($url, '?') !== false) ? '&' : '?';
            return $url . $sep . 'utm_source=warsawexpo&utm_medium=news&utm_campaign=referral' . $fragment;
        };

        $reg_path = self::languageChecker('/rejestracja/', '/en/registration/');
        $exh_path = self::languageChecker('/zostan-wystawca/', '/en/become-an-exhibitor/');

        $reg_link = 'https://' . $domain . $reg_path;
        $exh_link = 'https://' . $domain . $exh_path;

        $medals_btn_link = (string)$args['pwe_news_summary_medals_button_link'];
        $conf_btn_link   = (string)$args['pwe_news_summary_conf_button_link'];

        if ($is_warsawexpo) {
            $medals_btn_link = $add_utms_if_none($medals_btn_link);
            $conf_btn_link   = $add_utms_if_none($conf_btn_link);
            $reg_link        = $add_utms_if_none($reg_link);
            $exh_link        = $add_utms_if_none($exh_link);
        }

        $a_target = $is_warsawexpo ? ' target="_blank"' : '';

        // Featured image
        $post_id       = get_the_ID();
        $thumbnail_id  = get_post_thumbnail_id($post_id);
        $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

        // Medals image – weź pierwszy z attach_images
        $medals_ids = array_filter(array_map('intval', explode(',', (string)$args['pwe_news_summary_medals_images'])));
        $medals_img_url = '';
        if (!empty($medals_ids)) {
            $medals_img_url = wp_get_attachment_url($medals_ids[0]);
        }

        // Akcent (opcjonalny shortcode – zostawiamy)
        $accent_color = do_shortcode('[pwe_color_accent domain="' . $domain . '"]');

        /* ======= OUTPUT ======= */
        $output  = '';
        $output .= '<div class="pwe-news-summary" id="PWENewsSummary">';

        if (!$is_warsawexpo && $thumbnail_url) {
            $output .= '<img class="pwe-news-summary__header" src="' . esc_url($thumbnail_url) . '" alt="' . self::languageChecker('Grafika nagłówkowa artykułu', 'Article header graphic') . '">';
        }

        $output .= '<h1 class="pwe-news-summary__title">' . $pwe_news_summary_title . '</h1>
                 <hr class="pwe-news-summary__hr">
                 <p class="pwe-news-summary__desc">' . $desc_html . '</p>
                 <hr class="pwe-news-summary__hr">';

        // Blok statystyk
        if (!empty($pwe_news_summary_stats_title)) {
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
                                            <span>'. $year_prev .'</span>
                                        </div>';
                                    }
                                    $output .= '
                                    <div class="pwe-news-summary-stats__stats-diagram-year">
                                        <div class="pwe-news-summary-stats__stats-diagram-year-box"></div>
                                        <span>'. $year_cur .'</span>
                                    </div>
                                </div>

                                <!-- Bars -->
                                <div class="pwe-news-summary-stats__stats-diagram-bars-container">

                                    <!-- Bar 1: Visitors -->
                                    <div class="pwe-news-summary-stats__stats-diagram-bars">
                                        <div class="pwe-news-summary-stats__stats-diagram-bars-wrapper">';
                                            if ($vis_prev > 0) {
                                                $output .= '
                                                <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $vis_prev_pct .'">
                                                        <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $vis_prev .'">0</span></div>
                                                    </div>
                                                </div>';
                                            }
                                            $output .= '
                                            <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $vis_curr_pct .'">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $vis_curr .'">0</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="pwe-news-summary-stats__stats-diagram-bars-label">'. self::languageChecker('Odwiedzający', 'Visitors') .'</p>
                                    </div>

                                    <!-- Bar 2: Exhibitors -->
                                    <div class="pwe-news-summary-stats__stats-diagram-bars">
                                        <div class="pwe-news-summary-stats__stats-diagram-bars-wrapper">';
                                            if ($exh_prev > 0) {
                                                $output .= '
                                                <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $exh_prev_pct .'">
                                                        <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $exh_prev .'">0</span></div>
                                                    </div>
                                                </div>';
                                            }
                                            $output .= '
                                            <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $exh_curr_pct .'">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $exh_curr .'">0</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="pwe-news-summary-stats__stats-diagram-bars-label">'. self::languageChecker('Wystawcy', 'Exhibitors') .'</p>
                                    </div>

                                    <!-- Bar 3: Space -->
                                    <div class="pwe-news-summary-stats__stats-diagram-bars">
                                        <div class="pwe-news-summary-stats__stats-diagram-bars-wrapper">';
                                            if ($spc_prev > 0) {
                                                $output .= '
                                                <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $spc_prev_pct .'">
                                                        <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $spc_prev .'">0</span> m<sup>2</sup></div>
                                                    </div>
                                                </div>';
                                            }
                                            $output .= '
                                            <div class="pwe-news-summary-stats__stats-diagram-bar">
                                                <div class="pwe-news-summary-stats__stats-diagram-bar-item" data-count="'. $spc_curr_pct .'">
                                                    <div class="pwe-news-summary-stats__stats-diagram-bar-number"><span class="countup" data-count="'. $spc_curr .'">0</span> m<sup>2</sup></div>
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
                                    <h2><span class="countup" data-count="'. $countries .'">0</span></h2>
                                    <p>'. self::languageChecker('krajów', 'countries') .'</p>
                                </div>
                            </div>
                        </div>

                        <div class="pwe-news-summary-stats__stats-number-container">
                            <div class="pwe-news-summary-stats__stats-number-box">
                                <h2><span class="countup" data-count="'. $vis_curr .'">0</span></h2>
                                <div class="pwe-news-summary-stats__stats-number-box-text">
                                    <span>+</span>
                                    <p>'. self::languageChecker('odwiedzających', 'visitors') .'</p>
                                </div>
                            </div>

                            <div class="pwe-news-summary-stats__stats-number-box">
                                <h2><span class="countup" data-count="'. $exh_curr .'">0</span></h2>
                                <div class="pwe-news-summary-stats__stats-number-box-text">
                                    <span>+</span>
                                    <p>'. self::languageChecker('wystawców', 'exhibitors') .'</p>
                                </div>
                            </div>

                            <div class="pwe-news-summary-stats__stats-number-box">
                                <h2><span class="countup" data-count="'. $spc_curr .'">0</span> m<sup>2</sup></h2>
                                <div class="pwe-news-summary-stats__stats-number-box-text">
                                    <span>+</span>
                                    <p>'. self::languageChecker('powierzchni wystawienniczej', 'exhibition space') .'</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

                // Karty
            $output .= '
                <div class="pwe-news-summary-stats__cards">
                    <div class="pwe-news-summary-stats__card">
                        <h5>'. self::languageChecker('Powierzchnia wystawiennicza:', 'Exhibition area:') .'</h5>
                        <p>'. self::languageChecker('Całkowita powierzchnia:', 'Total area:') .' <span style="color:#00b050;">' . $spc_curr . '</span> m².</p>
                    </div>
                    <div class="pwe-news-summary-stats__card">
                        <h5>'. self::languageChecker('Wystawcy:', 'Exhibitors:') .'</h5>';

            if ($exh_prev > 0 && $year_prev !== '') {
                $output .= '<p><span style="color:#00b050;">' . $exh_curr . '</span> ' .
                        self::languageChecker('prezentujących swoje rozwiązania. Wzrost o', 'companies presenting their solutions. An increase of') .
                        ' <span style="color:#00b050;">' . $exh_growth_pct . '%</span> ' .
                        self::languageChecker('w porównaniu do ' . $year_prev . ' roku.', 'compared to ' . $year_prev . '.') .
                        '</p>';
            } else {
                $output .= '<p><span style="color:#00b050;">' . $exh_curr . '</span> ' .
                        self::languageChecker(
                            'firm z różnych branż zaprezentowało podczas wydarzenia swoje innowacyjne rozwiązania, produkty i technologie.',
                            'companies from various sectors presented their innovative solutions, products, and technologies during the event.'
                        ) .
                        '</p>';
            }

            $output .= '
                    </div>
                    <div class="pwe-news-summary-stats__card">
                        <h5>'. self::languageChecker('Kraje uczestniczące:', 'Participating countries:') .'</h5>
                        <p>'. self::languageChecker('Reprezentanci', 'Representatives from') .'  <span style="color:#00b050;">' .
                        esc_html((string)$countries) . ' ' . self::languageChecker('krajów', 'countries') .
                        ' </span>, ' . self::languageChecker('co nadało targom międzynarodowy charakter.', 'which gave the fair an international character.') . '</p>
                    </div>
                </div>
                <hr class="pwe-news-summary__hr">';

        }
        // Iframe + galeria
        if (!empty($pwe_news_summary_iframe_link) || !empty($pwe_news_summary_iframe_desc) || !empty($gallery_html)) {
            $output .= '
                <div class="pwe-news-summary__iframe" id="PWENewsSummaryIframe">';
                    if (!empty($pwe_news_summary_iframe_title)) {
                        $output .= '
                        <div class="pwe-news-summary__iframe-title">' . $pwe_news_summary_iframe_title . '</div>';
                    }
                    $output .= '
                    <div class="pwe-news-summary__iframe-container">';
                        if (!empty($pwe_news_summary_iframe_link)) {
                            // Extract the video ID from the URL
                            preg_match('/embed\/([^?]+)/', $pwe_news_summary_iframe_link, $match);
                            $video_id = $match[1];

                            $video_plug = 'https://i.ytimg.com/vi/' . $video_id . '/sddefault.jpg';
                            $video_src = 'https://www.youtube.com/embed/' . $video_id;
                            $video_iframe_html = '<iframe class="pwe-iframe" src="' . $video_src . '?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
                            $video_default_html = '<div class="pwe-news-summary__iframe-default" style="background-image: url(' . $video_plug . ');">
                                                        <img src="/wp-content/plugins/pwe-media/media/youtube-button.webp" alt="youtube play button">
                                                </div>';

                            $output .= '<div class="pwe-news-summary__iframe-box">' . $video_default_html . '</div>';

                        }
                        if (!empty($pwe_news_summary_iframe_desc)) {
                            $output .= '
                            <div class="pwe-news-summary__iframe-desc">
                                <p>' . $pwe_news_summary_iframe_desc . '</p>
                            </div>';
                        }
                        $output .= '
                    </div>';
                    if (!empty($gallery_html)) {
                        $output .= '
                        <div class="pwe-news-summary__gallery-container">
                            <div class="pwe-news-summary__gallery-slider">' . $gallery_html . '</div>
                        </div>';
                    }
                    $output .= '
                </div>
                <hr class="pwe-news-summary__hr">';
        }
        // Medale
        if (!empty($pwe_news_summary_medals_desc)) {
            $output .= '
                <div class="pwe-news-summary__medal" id="PWENewsSummaryMedal">
                    <div class="pwe-news-summary__medal-container">
                        <div class="pwe-news-summary__medal-desc">
                            <p>' . $pwe_news_summary_medals_desc . '</p>
                            <a class="pwe-news-summary__btn" href="' . $medals_btn_link . '"' . $a_target . '>' . $pwe_news_summary_medals_button_title . '</a>
                        </div>';
            if ($medals_img_url) {
                $output .= '<img src="' . $medals_img_url . '" alt="' . self::languageChecker('Medale dla najlepszych wystawców', 'Medals for the best exhibitors') . '">';
            }
            $output .= '
                    </div>
                </div>
                <hr class="pwe-news-summary__hr">';
        }
        // Konferencja
        if (!empty($pwe_news_summary_conf_title)) {
            $output .= '
                <div class="pwe-news-summary__conf" id="PWENewsSummaryConf">
                    <div class="pwe-news-summary__conf-container">
                        <h2 class="pwe-news-summary__conf-title">' . $pwe_news_summary_conf_title . '</h2>
                        <p class="pwe-news-summary__conf-desc">' . $conf_desc_html . '</p>
                        <a class="pwe-news-summary__btn--black" href="' . $conf_btn_link . '"' . $a_target . '>' . self::languageChecker('Sprawdź relację z konferencji', 'See the conference highlights') . '</a>
                    </div>
                </div>
                <hr class="pwe-news-summary__hr">';
        }
        // Następna edycja
        if (!empty($pwe_news_summary_next_title)) {
            $output .= '
                <div class="pwe-news-summary__next" id="PWENewsSummaryNext">
                    <div class="pwe-news-summary__next-content">
                        <h2 class="pwe-news-summary__next-title">' . $pwe_news_summary_next_title . '</h2>
                        <p class="pwe-news-summary__next-desc">' . $pwe_news_summary_next_desc . '</p>
                        <div class="pwe-news-summary__next-btn-container">
                            <a class="pwe-news-summary__btn" href="' . $reg_link . '"' . $a_target . '>' . self::languageChecker('Zarejestruj się', 'Register') . '</a>
                            <a class="pwe-news-summary__btn--black" href="' . $exh_link . '"' . $a_target . '>' . self::languageChecker('Zostań wystawcą', 'Become an exhibitor') . '</a>
                        </div>
                    </div>
                    <img src="https://' . $domain . '/doc/kafelek.jpg" alt="' . self::languageChecker('Główne logo targów', 'Main trade fair logo') . '">
                </div>
                <hr class="pwe-news-summary__hr">';
        }
        // LinkedIn (stały blok)
        $output .= '
            <div class="pwe-news-summary__linkedin" id="PWENewsSummaryLinkedin">
                <div class="pwe-news-summary__linkedin-content">
                    <img src="https://mr.glasstec.pl/wp-content/plugins/pwe-media/media/nikodem.webp" alt="' . self::languageChecker('Nikodem Zygadło – dyrektor działu analiz', 'Nikodem Zygadło – Director of Analysis Department') . '">
                    <div class="pwe-news-summary__linkedin-content-text">
                        <h2 class="pwe-news-summary__linkedin-title">Nikodem Zygadło</h2>
                        <h3 class="pwe-news-summary__linkedin-subtitle">' . self::languageChecker('Dyrektor Działu Analiz | Z-ca Dyrektora ds. Rozwoju | Ptak Warsaw Expo', 'Director of Analysis Department | Deputy Director of Development | Ptak Warsaw Expo') . '</h3>
                        <p class="pwe-news-summary__linkedin-desc">' . self::languageChecker('Pasjonat targów, z niemal 30-letnim doświadczeniem w tworzeniu, organizacji imprez i zarządzaniu projektami targowymi.', 'A trade fair enthusiast with nearly 30 years of experience in creating, organizing events and managing trade fair projects.') . '</p>
                    </div>
                </div>
                <div class="pwe-news-summary__linkedin-footer">
                    <p class="pwe-news-summary__linkedin-thx">' . self::languageChecker('Dziękujemy, że przeczytałaś/eś nasz artykuł do końca.', 'Thank you for reading our article to the end.') . '</p>
                    <a class="pwe-news-summary__btn--black" target="_blank" href="https://www.linkedin.com/build-relation/newsletter-follow?entityUrn=7185929412658302977">' . self::languageChecker('Dołącz do Newslettera na LinkedIn', 'Join the newsletter on LinkedIn') . '</a>
                </div>
            </div>
        </div>';

        $output .= '
            <script>
                jQuery(function ($) {
                    // Zmienna z HTML-em iframe (wstrzyknięta z PHP)
                    const iframeHtml = ' . json_encode($video_iframe_html) . ';

                    const defaultImage = document.querySelector(".pwe-news-summary__iframe-default");

                    if (defaultImage) {
                        defaultImage.addEventListener("click", function () {
                            const container = defaultImage.parentElement;
                            if (container) {
                                container.innerHTML = `<div class="pwe-news-summary__iframe-video">${iframeHtml}</div>`;
                            }
                        });
                    }
                });
            </script>';

        return $output;
    }
}
