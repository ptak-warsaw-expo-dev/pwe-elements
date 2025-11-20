<?php 

/**
 * Class PWEAttractionsSlider
 * Extends PWECommonFunctions and defines a custom Visual Composer element.
 */
class PWEAttractionsSlider extends PWEAttractions {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Pola VC (zależność jak w Twoim przykładzie – zmień 'attractions_type' jeśli u Ciebie klucz jest inny)
     */
    public static function initElements() {

        $element_output = array(
            array(
                'type' => 'dropdown',
                'heading' => __('Motyw', 'pwe_attractions'),
                'param_name' => 'pwe_theme',
                'value' => array(
                    __('Ciemny (domyślny)', 'pwe_attractions') => 'dark',
                    __('Jasny', 'pwe_attractions') => 'light',
                ),
                'std' => 'dark',
                'save_always' => true,
                'param_holder_class' => 'backend-area-one-fourth-width',
                'dependency' => array(
                    'element' => 'attractions_type',
                    'value'   => 'PWEAttractionsSlider',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'heading' => __('Progress bar – Color 1', 'pwe_attractions'),
                'param_name' => 'pwe_progress_bar_c1',
                'dependency' => array(
                    'element' => 'attractions_type',
                    'value'   => 'PWEAttractionsSlider',
                ),
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
            ),
            array(
                'type' => 'colorpicker',
                'heading' => __('Progress bar – Color 2', 'pwe_attractions'),
                'param_name' => 'pwe_progress_bar_c2',
                'dependency' => array(
                    'element' => 'attractions_type',
                    'value'   => 'PWEAttractionsSlider',
                ),
                'param_holder_class' => 'backend-area-one-fourth-width',
                'save_always' => true,
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Role Gradient', 'pwe_attractions'),
                'param_name' => 'pwe_role_gradients',
                'dependency' => array(
                    'element' => 'attractions_type',
                    'value'   => 'PWEAttractionsSlider',
                ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Role key (match text)', 'pwe_attractions'),
                        'param_name' => 'pwe_role_gradient_key',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Color One', 'pwe_attractions'),
                        'param_name' => 'pwe_role_gradient_color_one',
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Color Two', 'pwe_attractions'),
                        'param_name' => 'pwe_role_gradient_color_two',
                        'param_holder_class' => 'backend-area-one-fourth-width',
                        'save_always' => true,
                    ),
                ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Slajdy', 'pwe_attractions'),
                'param_name' => 'slider_items',
                'dependency' => array(
                    'element' => 'attractions_type',
                    'value'   => 'PWEAttractionsSlider',
                ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Kategoria', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_category',
                        'value' => array(
                            __('Nowi', 'pwe_attractions') => 'nowi',
                            __('Poprzedni', 'pwe_attractions') => 'poprzedni',
                        ),
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Image Link', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_image',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Role PL', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_role_pl',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Role EN', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_role_en',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Title PL', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_title_pl',
                        'param_holder_class' => 'backend-area-half-width',
                        'admin_label' => true,
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Title EN', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_title_en',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Description PL', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_desc_pl',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Description EN', 'pwe_attractions'),
                        'param_name' => 'pwe_attractions_slide_desc_en',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                    ),
                ),
            ),
        );

        return $element_output;
    }

    private static function renderSlide($item, $slugify) {
        $item_role_pl  = trim($item['pwe_attractions_slide_role_pl']  ?? '');
        $item_role_en  = trim($item['pwe_attractions_slide_role_en']  ?? '');
        $item_title_pl = trim($item['pwe_attractions_slide_title_pl'] ?? '');
        $item_title_en = trim($item['pwe_attractions_slide_title_en'] ?? '');
        $item_desc_pl  = trim($item['pwe_attractions_slide_desc_pl']  ?? '');
        $item_desc_en  = trim($item['pwe_attractions_slide_desc_en']  ?? '');
        $item_img      = esc_url($item['pwe_attractions_slide_image'] ?? '');

        $role = PWECommonFunctions::languageChecker($item_role_pl, $item_role_en);
        $title = PWECommonFunctions::languageChecker($item_title_pl, $item_title_en);
        $desc = PWECommonFunctions::languageChecker($item_desc_pl, $item_desc_en);

        $roles = array_values(array_filter(array_map('trim',
            preg_split('/\s*,\s*/u', (string)$role, -1, PREG_SPLIT_NO_EMPTY)
        ), static fn($s)=>$s!=='' ));

        $out = '<div class="swiper-slide">';
        $out .= ' <article class="pwe-attractions-slider__card"'.(!empty($roles) ? ' data-role="'.esc_attr(implode(', ', $roles)).'"' : '').'>';
        $out .= '  <div class="pwe-attractions-slider__media">';

        if (!empty($item_img)) {
            $out .= '<img class="pwe-attractions-slider__img" src="'.esc_url($item_img).'" alt="'.esc_attr($title).'" loading="lazy" decoding="async" referrerpolicy="no-referrer">';
        }

        foreach ($roles as $r) {
            $slug = $slugify($r);
            if ($slug === '') continue;
            $out .= '<span class="pwe-attractions-slider__badge badge--'.esc_attr($slug).'">'.esc_html($r).'</span>';
        }

        $out .= '  </div>';
        $out .= '  <div class="pwe-attractions-slider__body">';
        if (!empty($title)) $out .= '    <div class="pwe-attractions-slider__name">'.esc_html($title).'</div>';
        if (!empty($desc))  $out .= '    <div class="pwe-attractions-slider__desc">'.esc_html($desc).'</div>';
        $out .= '  </div>';
        $out .= ' </article>';
        $out .= '</div>';

        return $out;
    }

    /**
     * Duplikuje elementy aż do osiągnięcia min. liczby slajdów.
     */
    private static function ensureMinSlides(array $items, int $min): array {
        $count = count($items);
        if ($count === 0 || $min <= 0) return $items;
        if ($count >= $min) return $items;

        $out = $items;
        while (count($out) < $min) {
            // Ile jeszcze potrzeba
            $need = $min - count($out);
            // Doklejaj z oryginalnej puli po kolei
            foreach ($items as $it) {
                $out[] = $it;
                if (--$need <= 0) break;
            }
        }
        return $out;
    }

    /**
     * HTML output – tylko przez $output i return.
     */
    public static function output($atts) {

        extract(shortcode_atts(array(
            'pwe_role_gradients' => '',
            'slider_items' => '',
            'pwe_attractions_title_pl' => '',
            'pwe_attractions_title_en' => '',
            'pwe_attractions_desc_pl' => '',
            'pwe_attractions_desc_en' => '',
            'pwe_progress_bar_c1' => '',
            'pwe_progress_bar_c2' => '',
            'pwe_theme' => 'dark',
        ), $atts));

        wp_enqueue_style(
            'pwe-attractions-slider-fonts',
            'https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;600;800&display=swap',
            array(),
            null
        );

        $title = PWECommonFunctions::languageChecker($pwe_attractions_title_pl, $pwe_attractions_title_en);
        $desc = PWECommonFunctions::languageChecker($pwe_attractions_desc_pl, $pwe_attractions_desc_en);

        $items_raw = urldecode($slider_items);
        $items = $items_raw ? json_decode($items_raw, true) : array();
        if (!is_array($items)) $items = array();

        $pwe_role_gradients = $atts['pwe_role_gradients'] ?? '';
        $role_gradients = vc_param_group_parse_atts($pwe_role_gradients) ?: [];

        $slugify = function(string $s): string {
            $s = trim(mb_strtolower($s, 'UTF-8'));
            $map = ['ą'=>'a','ć'=>'c','ę'=>'e','ł'=>'l','ń'=>'n','ó'=>'o','ś'=>'s','ź'=>'z','ż'=>'z'];
            $s = strtr($s, $map);
            $s = preg_replace('/[^\p{L}\p{N}]+/u', '-', $s);
            return trim($s, '-');
        };

        // Gradienty CSS
        $css_badges = '';
        foreach ($role_gradients as $rg) {
            $key = (string)($rg['pwe_role_gradient_key'] ?? '');
            $c1  = (string)($rg['pwe_role_gradient_color_one'] ?? '');
            $c2  = (string)($rg['pwe_role_gradient_color_two'] ?? '');
            if ($key === '' || $c1 === '' || $c2 === '') continue;
            $slug = $slugify($key);
            if ($slug === '') continue;
            // prosty whitelist na kolory (hex 3/6)
            if (!preg_match('/^#([0-9a-fA-F]{3}){1,2}$/', $c1) || !preg_match('/^#([0-9a-fA-F]{3}){1,2}$/', $c2)) continue;

            $css_badges .= '.pwe-attractions-slider__badge.badge--'.esc_attr($slug)
                        .  '{background:linear-gradient(90deg,'.esc_attr($c1).','.esc_attr($c2).');color:#fff}'."\n";
        }

        // --- Progress bar gradient CSS ---
        $css_progress = '';
        $c1 = trim((string)$pwe_progress_bar_c1);
        $c2 = trim((string)$pwe_progress_bar_c2);

        // whitelist na #RGB / #RRGGBB
        $hex_ok = static function($c) {
            return (bool)preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $c);
        };

        // jeśli oba kolory ustawione i poprawne – ustaw gradient
        if ($c1 !== '' && $c2 !== '' && $hex_ok($c1) && $hex_ok($c2)) {
            $css_progress .= '
                .pwe-attractions-slider__progress .pwe-attractions-slider__bar, 
                .pwe-attractions-slider__tab[aria-selected="true"] {'
                        .  'background:linear-gradient(90deg,'.esc_attr($c1).','.esc_attr($c2).');'
                        .  '}';
        }

        // Grupowanie slajdów po kategorii
        $slides_by_category = [];
        foreach ($items as $item) {
            if (empty($item['pwe_attractions_slide_category'])) continue;
            $cat = $item['pwe_attractions_slide_category'];
            if (!isset($slides_by_category[$cat])) {
                $slides_by_category[$cat] = [];
            }
            $slides_by_category[$cat][] = $item;
        }

        $categories = array_keys($slides_by_category);

        if (isset($slides_by_category['nowi'])) {
            $categories = array_merge(['nowi'], array_values(array_diff($categories, ['nowi'])));
        }
        if (empty($categories)) {
            return;
        }
        $has_tabs = count($categories) > 1;
        $first_cat = $categories[0];

        $section_id = 'pwe-attractions-slider-' . uniqid();
        $theme = ($pwe_theme === 'light') ? 'light' : 'dark';

        $colors = ($theme === 'light')
        ? array('title'=>'#000','card_bg'=>'#fff','name'=>'#000','desc'=>'#3e3e3e')
        : array('title'=>'#fff','card_bg'=>'#000','name'=>'#fff','desc'=>'#9c9c9c');

        $css_theme  = '#'.esc_attr($section_id).' .pwe-attractions-slider__title{color:'.$colors['title'].'!important;}';
        $css_theme .= '#'.esc_attr($section_id).' .pwe-attractions-slider__card{background-color:'.$colors['card_bg'].'!important;}';
        $css_theme .= '#'.esc_attr($section_id).' .pwe-attractions-slider__name{color:'.$colors['name'].'!important;}';
        $css_theme .= '#'.esc_attr($section_id).' .pwe-attractions-slider__desc{color:'.$colors['desc'].'!important;}';

        // START HTML
        $output = '<style>' . $css_badges . $css_progress . $css_theme . '</style>';
        $output .= '<section class="pwe-attractions-slider" id="'.esc_attr($section_id).'" aria-label="Sekcja ambasadorów">';
        $output .= '  <div class="pwe-attractions-slider__heading">';
        $output .= '    <div>';
        $output .= '      <div class="pwe-attractions-slider__title">'.esc_html($title).'</div>';
        $output .= '      <div class="pwe-attractions-slider__subtitle">'.esc_html($desc).'</div>';
        $output .= '    </div>';

        // Zakładki
        if ($has_tabs) {
            $output .= '<div class="pwe-attractions-slider__tabs" role="tablist" aria-label="Przełącz sekcję">';
            foreach ($categories as $i => $cat) {
                $output .= '<button class="pwe-attractions-slider__tab" role="tab" '
                        . 'aria-selected="'.($i === 0 ? 'true' : 'false').'" '
                        . 'aria-controls="panel-'.$cat.'" '
                        . 'id="tab-'.$cat.'" '
                        . ($i === 0 ? 'tabindex="0"' : 'tabindex="-1"')
                        . '>'
                        . ucfirst($cat)
                        . '</button>';
            }
            $output .= '</div>';
        }

        $output .= '  </div>'; // .pwe-attractions-slider__heading

        // Karuzele
        $output .= '<div class="pwe-attractions-slider__carousels">';
        $output .= '  <div class="pwe-attractions-slider__progress" aria-hidden="true"><div class="pwe-attractions-slider__bar"></div></div>';

        foreach ($categories as $i => $cat) {
            $pane_id  = 'panel-' . $cat;
            $swiper_id = 'swiper-' . $cat;

            $output .= '<div id="'.esc_attr($pane_id).'" class="pwe-attractions-slider__pane" role="tabpanel" '
                    . 'aria-labelledby="tab-'.$cat.'" '.($i > 0 ? 'hidden' : '').'>';

            $output .= '  <div class="js-pwe-swiper js-pwe-swiper--'.esc_attr($cat).'">';
            $output .= '      <div class="swiper" id="'.esc_attr($swiper_id).'"><div class="swiper-wrapper">';

            $loopAdditionalSlides = 8;
            $minSlides = max(8, 2 * $loopAdditionalSlides + 1);
            $catSlides = self::ensureMinSlides($slides_by_category[$cat], $minSlides);

            foreach ($catSlides as $item) {
                $output .= self::renderSlide($item, $slugify);
            }

            $output .= '      </div><div class="swiper-pagination"></div></div>'; // .swiper, .swiper-pagination
            $output .= '    </div>'; // inner js-pwe-swiper
            $output .= '</div>';     // pane

        }

        $output .= '</div>'; // .pwe-attractions-slider__carousels
        $output .= '</section>';

        return $output;
    }

}
