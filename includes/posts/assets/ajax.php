<?php
add_action('wp_ajax_pwe_ajax_load_posts', 'pwe_ajax_load_posts');
add_action('wp_ajax_nopriv_pwe_ajax_load_posts', 'pwe_ajax_load_posts');

function pwe_ajax_load_posts() {
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : (PWECommonFunctions::lang_pl() ? 'news' : 'news-en');
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 24;

    if (!empty($search) && mb_strlen($search) < 3) {
        wp_send_json([
            'html' => '',
            'max'  => 0
        ]);
    }

    $output = '';

    $allowed_slugs = [];

    if (!empty($_POST['allowed_slugs'])) {
        $allowed_slugs = array_map('trim', explode(',', (string) $_POST['allowed_slugs']));
        $allowed_slugs = array_map('sanitize_title', $allowed_slugs);
        $allowed_slugs = array_values(array_unique(array_filter($allowed_slugs)));
    }

    $index = (($paged - 1) * $posts_per_page);

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
    );

    if(!empty($category) && $category !== 'all') {
        $args['tax_query'] = array(array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $category
        ));
    }

    if (!empty($search) && mb_strlen($search) >= 3) {

        $search = mb_substr($search, 0, 50);
        global $wpdb;

        $words = preg_split('/\s+/', $search);

        add_filter('posts_where', function($where) use ($wpdb, $words) {
            foreach ($words as $word) {
                if (mb_strlen($word) >= 3) {
                    $like = '%' . $wpdb->esc_like($word) . '%';
                    $where .= $wpdb->prepare(
                        " AND {$wpdb->posts}.post_title LIKE %s",
                        $like
                    );
                }
            }
            return $where;
        });
    }

    $query = new WP_Query($args);
    if($query->have_posts()) {
        while($query->have_posts()): $query->the_post();

            $index++;

            $post_id = get_the_ID();
            $word_count = 20;

            $post_content = get_the_content();
            $excerpt = '';
            $vc_content = ''; 

            if (preg_match('/pwe_news_summary_desc="([^"]+)"/', $post_content, $matches)) {
                $vc_content = wpb_js_remove_wpautop(urldecode(base64_decode($matches[1])), true);
            } elseif (preg_match('/pwe_news_upcoming_desc="([^"]+)"/', $post_content, $matches)) {
                $vc_content = wpb_js_remove_wpautop(urldecode(base64_decode($matches[1])), true);
            } elseif (preg_match('/news_say_about_us_description="([^"]+)"/', $post_content, $matches)) {
                $vc_content = wpb_js_remove_wpautop(urldecode(base64_decode($matches[1])), true);
            } elseif (preg_match('/\[vc_column_text.*?\](.*?)\[\/vc_column_text\]/s', $post_content, $matches)) {
                $vc_content = $matches[1];
            }

            $vc_content = wp_strip_all_tags($vc_content);

            if (!empty($vc_content)) {
                $words = preg_split('/\s+/', trim($vc_content));
                $excerpt = implode(' ', array_slice($words, 0, $word_count)) . '...';
            }

            $link = get_permalink();
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
            $title = get_the_title();
            $date = get_the_date('Y-m-d');

            $max_chars = 50;
            if (mb_strlen($title, 'UTF-8') > $max_chars) {
                $title = mb_substr($title, 0, $max_chars, 'UTF-8') . '...';
            }

            $date_obj = new DateTime($date);
            $formatted_date = $date_obj->format('d M Y');

            if (get_locale() == 'pl_PL') {
                $formatted_date = str_replace(
                    array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
                    array('Sty','Lut','Mar','Kwi','Maj','Cze','Lip','Sie','Wrz','Paź','Lis','Gru'),
                    $formatted_date
                );
            }

            $post_categories = get_the_category($post_id);
            $filtered_categories = [];

            if (!empty($post_categories) && is_array($post_categories)) {
                foreach ($post_categories as $cat) {

                    if (!isset($cat->slug)) {
                        continue;
                    }

                    if (!empty($allowed_slugs)) {
                        if (in_array($cat->slug, $allowed_slugs, true)) {
                            $filtered_categories[] = $cat;
                        }
                        continue;
                    }
                    
                    $excluded = ['news', 'news-en', 'news-pl', 'news-en-2'];
                    if (!in_array($cat->slug, $excluded, true)) {
                        $filtered_categories[] = $cat;
                    }
                }
            }

            if ($index == 1) {
                $new = (object) [
                    'name' => PWECommonFunctions::lang_pl() ? 'Nowość' : 'New',
                    'slug' => PWECommonFunctions::lang_pl() ? 'nowosc' : 'new',
                    'term_id' => 0
                ];

                array_unshift($filtered_categories, $new);
            }

            if ($index == 1) {
                $max_chars = 90;
            } else {
                $max_chars = 50;
            }

            $item_class = 'pwe-posts__item';

            if ($index == 1) {
                $item_class .= ' pwe-posts__item--large';
            } elseif ($index == 2 || $index == 3) {
                $item_class .= ' pwe-posts__item--side';
            } elseif ($index > 3) {
                $item_class .= ' pwe-posts__item--medium';
            }

            $output .= '
            <!-- POST -->
            <a class="'. $item_class .'" href="'. $link .'" data-index="'. $index .'">

                <div class="pwe-posts__item-thumbnail-container" style="
                    background-image:url('.$image.');">
                </div>

                <div class="pwe-posts__item-content">
                    <div class="pwe-posts__item-content-info">
                        <div class="pwe-posts__item-meta">';
                            if (!empty($filtered_categories)) {
                                $output .= '
                                <div class="pwe-posts__item-categories">';
                                        foreach ($filtered_categories as $cat) {
                                            $output .= '<p class="pwe-posts__item-category">'. $cat->name .'</p>';
                                        }                       
                                    $output .= '
                                </div>
                                <svg width="20" height="20" viewBox="5 5 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g stroke-width="0"/>
                                    <g stroke-linecap="round" stroke-linejoin="round"/>
                                    <g> <circle cx="12.5" cy="12.5" r="1.5" fill="#999999" stroke="#999999" stroke-width="1.2"/> </g>
                                </svg>';
                            }
                            $output .= '
                            <p class="pwe-posts__item-date">'. $formatted_date .'</p>
                        </div>
                        <h4 class="pwe-posts__item-title">'. $title .'</h4>
                        <p class="pwe-posts__item-excerpt">'. $excerpt .'</p>
                    </div>
                    
                    <p class="pwe-posts__item-btn">
                        <span>'. (PWECommonFunctions::lang_pl() ? 'Czytaj więcej' : 'Read more') .'</span>
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.58266 11.0817C2.19221 11.4721 1.55899 11.472 1.16844 11.0817C0.777921 10.6912 0.777921 10.058 1.16844 9.66747L7.71125 3.12466L1.87486 3.12466C1.32279 3.12441 0.874968 2.6769 0.874968 2.12477C0.874968 1.57264 1.32279 1.12512 1.87486 1.12487L10.1254 1.12487C10.6774 1.12512 11.1253 1.57264 11.1253 2.12477L11.1246 10.3746C11.1244 10.9268 10.6769 11.3745 10.1247 11.3745C9.57257 11.3743 9.1249 10.9267 9.12478 10.3746L9.12478 4.53956L2.58266 11.0817Z" fill="'. (($index == 1) ? 'white' : 'var(--main2-color)') .'"/>
                        </svg>
                    </p>
                </div>

            </a>';

        endwhile;
    }
    wp_reset_postdata();

    remove_all_filters('posts_where');

    wp_send_json(array(
        'html' => $output,
        'max'  => $query->max_num_pages
    ));

    wp_die();
}