<?php

/**
 * Class PWEPostsFull
 * Extends PWEMap class and defines a custom Visual Composer element.
 */
class PWEPostsFull extends PWEPosts {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public function output($atts) {

        wp_enqueue_style(
            'pwe-posts-css',
            plugin_dir_url(__FILE__) . 'assets/style.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/style.css')
        );

        extract( shortcode_atts( array(
            'posts_modes' => '',
            'posts_category' => '',
            'posts_per_page' => '',
            'posts_count' => '',
        ), $atts ));

        $posts_per_page = !empty($posts_per_page) ? $posts_per_page : 24;

        $category_slugs = [];

        if (!empty($posts_category)) {

            if (is_array($posts_category)) {
                $category_slugs = $posts_category;
            } else {
                $category_slugs = explode(',', $posts_category);
            }

            $category_slugs = array_map('trim', $category_slugs);
            $category_slugs = array_map('sanitize_title', $category_slugs);
            $category_slugs = array_unique($category_slugs);
        }

        $allowed_slugs_js = !empty($category_slugs) ? implode(',', $category_slugs) : '';

        $output = '
        <div id="pwePosts" class="pwe-posts">
            <div class="pwe-posts__wrapper">

                <div class="pwe-posts__header">

                    <!-- TITLE -->
                    <div class="pwe-posts__title">
                        <h1>'. (PWECommonFunctions::lang_pl() ? 'Aktualności' : 'News') .'</h1>
                        <p>PTAK WARSAW EXPO</p>
                    </div>

                    <!-- SEARCH -->
                    <div class="pwe-posts__search">
                        <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.6725 16.6412L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="var(--main2-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <input type="text" id="pwe-posts__search-input" placeholder="'. (PWECommonFunctions::lang_pl() ? 'Szukaj...' : 'Search...') .'">
                        <button id="pwe-posts__search-btn">'. (PWECommonFunctions::lang_pl() ? 'Szukaj' : 'Search') .'</button>
                    </div>

                </div>';

                if (!empty($category_slugs)) {
                    $output .= '
                    <!-- FILTER -->
                    <div class="pwe-posts__filters">
                        <button class="pwe-posts__filter-btn active" data-category="'. (PWECommonFunctions::lang_pl() ? 'news' : 'news-en') .'">'. (PWECommonFunctions::lang_pl() ? 'Wszystkie' : 'All') .'</button>';
                        foreach ($category_slugs as $slug) {
                            $term = get_term_by('slug', $slug, 'category');
                            if ($term && $term->count > 0) {
                                $output .= '<button class="pwe-posts__filter-btn" data-category="'.$slug.'">'.$term->name.'</button>';
                            }
                        }
                    $output .= '
                    </div>';
                }

                $output .= '
                <!-- PRELOADER -->
                <div class="pwe-posts__preloader">
                    <div class="pwe-posts__spinner"></div>
                </div>';

                $output .= '
                <!-- POSTS -->
                <div class="pwe-posts__container">';

                    $args = array(
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'posts_per_page' => $posts_per_page,
                        'paged'          => 1,
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'category',
                                'field'    => 'slug',
                                'terms'    => PWECommonFunctions::lang_pl() ? 'news' : 'news-en',
                            )
                        ),
                        'meta_query' => array(
                            array(
                                'key' => '_thumbnail_id',
                                'compare' => 'EXISTS'
                            )
                        )
                    );

                    $allowed_slugs = [];

                    if (!empty($posts_category)) {
                        $items = is_array($posts_category)
                            ? $posts_category
                            : array_map('trim', explode(',', $posts_category));

                        $allowed_slugs = array_values(array_unique(array_filter(array_map('sanitize_title', $items))));
                    }

                    $query = new WP_Query($args);

                    if ($query->have_posts()) {
                        $index = 0;
                        while ($query->have_posts()) : $query->the_post();

                            $post_id = get_the_ID();
                            $word_count = 16;

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

                            $index++;

                            if ($index == 1) {
                                $max_chars = 90;
                            } else {
                                $max_chars = 50;
                            }
                            
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

                            $item_class = 'pwe-posts__item';

                            if ($index == 1) {
                                $item_class .= ' pwe-posts__item--large';
                            } elseif ($index == 2 || $index == 3) {
                                $item_class .= ' pwe-posts__item--side';
                            } elseif ($index > 3) {
                                $item_class .= ' pwe-posts__item--medium';
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

                                    } else {

                                        $excluded = ['news','news-en','news-pl','news-en-2'];

                                        if (!in_array($cat->slug, $excluded, true)) {
                                            $filtered_categories[] = $cat;
                                        }

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

                $output .= '
                </div>';

                $total_posts = wp_count_posts('post')->publish;
                if($total_posts > $posts_per_page) {
                    $output .= '
                    <div class="pwe-posts__load-more-btn-container">
                        <button id="pwe-posts__load-more-posts" class="pwe-btn" data-page="1">'. (PWECommonFunctions::lang_pl() ? 'Załaduj więcej' : 'Load more') .'</button>
                    </div>';
                } else {
                    $output .= '<div class="pwe-posts__load-more-btn-container"></div>';
                }

            $output .= '
            </div>
        </div>';

        // JS
        $output .= '
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const container = document.querySelector(".pwe-posts__container");
                const loadMoreContainer = document.querySelector(".pwe-posts__load-more-btn-container");
                const filters = document.querySelectorAll(".pwe-posts__filter-btn");
                const searchInput = document.getElementById("pwe-posts__search-input");
                const searchBtn = document.getElementById("pwe-posts__search-btn");
                const preloader = document.querySelector(".pwe-posts__preloader");

                let currentPage = 1;
                let currentCategory = "'. (PWECommonFunctions::lang_pl() ? 'news' : 'news-en') .'";
                let currentSearch = "";
                let isLoading = false;

                function loadPosts(page, category, search = "", append = false) {

                    if (isLoading) return;
                    isLoading = true;
                    
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "'. admin_url('admin-ajax.php') .'", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");

                    const params = new URLSearchParams();
                    params.append("action", "pwe_ajax_load_posts");
                    params.append("page", page);
                    params.append("category", category ? category : "'. (PWECommonFunctions::lang_pl() ? 'news' : 'news-en') .'");
                    params.append("search", search);
                    params.append("allowed_slugs", "'. esc_js($allowed_slugs_js) .'");
                    params.append("posts_per_page", '. $posts_per_page .');

                    preloader.classList.add("active");

                    xhr.onload = function() {

                        isLoading = false;
                        preloader.classList.remove("active");

                        if(xhr.status >= 200 && xhr.status < 400) {
                            let response = {};
                            try { response = JSON.parse(xhr.responseText); } 
                            catch(e) { console.error("Błąd parsowania JSON:", xhr.responseText); return; }

                            if(!append) container.innerHTML = response.html;
                            else container.insertAdjacentHTML("beforeend", response.html);

                            // Load more button
                            loadMoreContainer.innerHTML = "";
                            if(currentPage < response.max) {
                                const btn = document.createElement("button");
                                btn.id = "pwe-posts__load-more-posts";
                                btn.className = "pwe-btn";
                                btn.innerText = "'. self::languageChecker("Załaduj więcej","Load more") .'";
                                loadMoreContainer.appendChild(btn);
                            }
                        }
                    };

                    xhr.onerror = function() {
                        isLoading = false;
                        preloader.classList.remove("active");
                    };

                    xhr.send(params.toString());
                }

                // Filters
                filters.forEach(btn => {
                    btn.addEventListener("click", function() {
                        filters.forEach(b => b.classList.remove("active"));
                        this.classList.add("active");
                        currentCategory = this.dataset.category || "news";
                        currentPage = 1;
                        currentSearch = "";
                        searchInput.value = "";
                        loadPosts(currentPage, currentCategory, currentSearch, false);
                    });
                });

                // Load more
                loadMoreContainer.addEventListener("click", function(e) {
                    if(e.target && e.target.id === "pwe-posts__load-more-posts") {
                        currentPage++;
                        loadPosts(currentPage, currentCategory, currentSearch, true);
                    }
                });

                // Search
                searchBtn.addEventListener("click", function() {
                    currentSearch = searchInput.value.trim();

                    if(currentSearch.length < 3) {
                        return;
                    }

                    currentPage = 1;
                    loadPosts(currentPage, currentCategory, currentSearch, false);
                });

                searchInput.addEventListener("keydown", function(e) {
                    if (e.key === "Enter") {
                        e.preventDefault();

                        currentSearch = searchInput.value.trim();

                        if(currentSearch.length < 3) {
                            return;
                        }

                        currentPage = 1;
                        loadPosts(currentPage, currentCategory, currentSearch, false);
                    }
                });

                searchInput.addEventListener("input", function() {
                    if (this.value.trim() === "") {
                        currentSearch = "";
                        currentPage = 1;
                        loadPosts(currentPage, currentCategory, currentSearch, false);
                    }
                });
            });
        </script>';

        return $output;
    }
}

