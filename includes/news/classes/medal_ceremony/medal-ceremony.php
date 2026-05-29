<?php

/**
 * Class PWENewsMedalCeremony
 * Szablon ceremonii wręczenia medali z dynamicznym układem kategorii.
 */
class PWENewsMedalCeremony extends PWENews {

    public function __construct() {}

    public static function initElements() {
        $dep = array(
            'element' => 'news_template_type',
            'value'   => 'PWENewsMedalCeremony',
        );

        return array(
            array(
                'type'=> 'textfield',
                'group'=> 'News',
                'heading'=> __('News Title', 'pwelement'),
                'param_name'=> 'pwe_news_medal_ceremony_title',
                'save_always'=> true,
                'dependency'=> $dep,
            ),
            array(
                'type'=> 'textarea_raw_html',
                'group'=> 'News',
                'heading'=> __('Title desc', 'pwelement'),
                'param_name'=> 'pwe_news_medal_ceremony_title_desc',
                'save_always'=> true,
                'dependency'=> $dep,
            ),
            array(
                'type'=> 'textarea_raw_html',
                'group'=> 'News',
                'heading'=> __('Main desc', 'pwelement'),
                'param_name'=> 'pwe_news_medal_ceremony_main_desc',
                'save_always'=> true,
                'dependency'=> $dep,
            ),
            array(
                'type'=> 'param_group',
                'group'=> 'News',
                'heading'=> __('Winners', 'pwelement'),
                'param_name'=> 'pwe_news_medal_ceremony_category',
                'dependency'=> $dep,
                'params'=> array(
                    array(
                        'type'=> 'textarea_raw_html',
                        'group'=> 'News',
                        'heading'=> __('Category Name', 'pwelement'),
                        'param_name'=> 'pwe_news_medal_ceremony_category_name',
                        'save_always'=> true,
                    ),
                    array(
                        'type'=> 'param_group',
                        'group'=> 'News',
                        'heading'=> __('Category Winners', 'pwelement'),
                        'param_name'=> 'pwe_news_medal_ceremony_category_winners',
                        'params'=> array(
                            array(
                                'type'=> 'textfield',
                                'heading'=> __('Name', 'pwelement'),
                                'param_name'=> 'pwe_news_medal_ceremony_winners_name',
                                'admin_label'=> true,
                                'save_always'=> true,
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'type'=> 'textarea_raw_html',
                'group'=> 'News',
                'heading'=> __('Footer desc', 'pwelement'),
                'param_name'=> 'pwe_news_medal_ceremony_footer_desc',
                'save_always'=> true,
                'dependency'=> $dep,
            ),
            array(
                'type'=> 'textarea_raw_html',
                'group'=> 'News',
                'heading'=> __('News summary', 'pwelement'),
                'param_name'=> 'pwe_news_medal_ceremony_news_summary',
                'save_always'=> true,
                'dependency'=> $dep,
            ),
        );
    }

    public static function output($atts) {
        extract(shortcode_atts(array(
            'pwe_news_medal_ceremony_title'        => '',
            'pwe_news_medal_ceremony_title_desc'   => '',
            'pwe_news_medal_ceremony_main_desc'    => '',
            'pwe_news_medal_ceremony_category'     => '',
            'pwe_news_medal_ceremony_footer_desc'  => '',
            'pwe_news_medal_ceremony_news_summary' => '',
        ), $atts));

        // Dekodowanie treści HTML
        $title        = $pwe_news_medal_ceremony_title;
        $title_desc   = PWECommonFunctions::decode_clean_content($pwe_news_medal_ceremony_title_desc);
        $main_desc    = PWECommonFunctions::decode_clean_content($pwe_news_medal_ceremony_main_desc);
        $footer_desc  = PWECommonFunctions::decode_clean_content($pwe_news_medal_ceremony_footer_desc);
        $news_summary = PWECommonFunctions::decode_clean_content($pwe_news_medal_ceremony_news_summary);

        // Pobieranie obrazka wyróżniającego (Post Thumbnail)
        $post_id       = get_the_ID();
        $thumbnail_id  = get_post_thumbnail_id($post_id);
        $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

        // Parsowanie grup kategorii
        $categories = vc_param_group_parse_atts($pwe_news_medal_ceremony_category);

        // Obsługa języków dla tekstów stałych
        $txt_laureates   = self::languageChecker('Laureaci', 'Laureates');
        $txt_reg_btn     = self::languageChecker('Zarejestruj się', 'Register');
        $txt_exh_btn     = self::languageChecker('Zostań wystawcą', 'Become an exhibitor');
        $link_reg_path   = self::languageChecker('/rejestracja/', '/en/registration/');
        $link_exh_path   = self::languageChecker('/zostan-wystawca/', '/en/become-an-exhibitor/');

        $output = '';
        $output .= '<div class="pwe-news-medal_ceremony" id="PWENewsMedalCeremony">';

        // Nagłówek obrazkowy
        if ($thumbnail_url) {
            $output .= '<img class="pwe-news-medal_ceremony__header-img" src="' . esc_url($thumbnail_url) . '" alt="Header">';
        }

        $output .= '
            <div class="pwe-news-medal_ceremony__header">
                <h1 class="pwe-news-medal_ceremony__title">' . esc_html($title) . '</h1>
                <div class="pwe-news-medal_ceremony__header-desc">' . $title_desc . '</div>
            </div>
            <hr>
            <div class="pwe-news-medal_ceremony__main">
                <div class="pwe-news-medal_ceremony__main-text">
                    <h2 class="pwe-news-medal_ceremony__main-title">' . $txt_laureates . '</h2>
                    <div class="pwe-news-medal_ceremony__main-desc">' . $main_desc . '</div>
                </div>
                <div class="pwe-news-medal_ceremony__main-blocks">
        ';

        // Pętla po kategoriach z logiką szerokości
        if (!empty($categories) && is_array($categories)) {
            $total_categories = count($categories);
            $counter = 0;

            foreach ($categories as $category) {
                $counter++;
                
                // Sprawdzanie czy to ostatni element przy nieparzystej liczbie
                $is_odd_last = ($total_categories % 2 !== 0 && $counter === $total_categories);
                $extra_class = $is_odd_last ? ' pwe-news-medal_ceremony__main-categories--full-width' : '';

                $category_name_raw = isset($category['pwe_news_medal_ceremony_category_name']) ? $category['pwe_news_medal_ceremony_category_name'] : '';
                $category_name     = PWECommonFunctions::decode_clean_content($category_name_raw);
                $category_winners_raw = isset($category['pwe_news_medal_ceremony_category_winners']) ? $category['pwe_news_medal_ceremony_category_winners'] : '';
                $category_winners  = vc_param_group_parse_atts($category_winners_raw);

                $output .= '<div class="pwe-news-medal_ceremony__main-categories' . $extra_class . '">';
                
                if (!empty($category_name)) {
                    $output .= '<div class="pwe-news-medal_ceremony__categories-title">' . $category_name . '</div>';
                }

                if (!empty($category_winners) && is_array($category_winners)) {
                    $output .= '<ul class="pwe-news-medal_ceremony__winners-list">';
                    foreach ($category_winners as $winner) {
                        if (!empty($winner['pwe_news_medal_ceremony_winners_name'])) {
                            $output .= '<li>' . esc_html($winner['pwe_news_medal_ceremony_winners_name']) . '</li>';
                        }
                    }
                    $output .= '</ul>';
                }
                $output .= '</div>';
            }
        }

        $output .= '
                </div>
            </div>
            <div class="pwe-news-medal_ceremony__footer">
                <div class="pwe-news-medal_ceremony__footer-desc">' . $footer_desc . '</div>
                <div class="pwe-news-medal_ceremony__footer-summary">' . $news_summary . '</div>
            </div>
            <div class="pwe-news-medal_ceremony__buttons">
                <div class="pwe-news-medal_ceremony__btn-registration">
                    <a href="' . esc_url($link_reg_path) . '">' . $txt_reg_btn . '</a>
                </div>
                <div class="pwe-news-medal_ceremony__btn-become-exhibitor">
                    <a href="' . esc_url($link_exh_path) . '">' . $txt_exh_btn . '</a>
                </div>
            </div>
        </div>
        ';

        return $output;
    }
}