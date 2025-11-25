<?php 

// Funkcja AJAX do ładowania dodatkowych postów
function load_more_posts() {
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

    $args = array(
        'posts_per_page' => 18,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish',
        'offset' => $offset,
    );

    $query = new WP_Query($args);
                
    $posts_displayed = $query->post_count;
    
    $post_image_urls = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            $post_id = get_the_ID();
            $word_count = 10;
    
            // Get post content
            $post_content = get_post_field('post_content', $post_id);
                
            // Extract content inside [vc_column_text] shortcode
            preg_match('/\[vc_column_text.*?\](.*?)\[\/vc_column_text\]/s', $post_content, $matches);
            $vc_content = isset($matches[1]) ? $matches[1] : '';
    
            // Remove HTML
            $vc_content = wp_strip_all_tags($vc_content);
    
            // Check if the content is not empty
            if (!empty($vc_content)) {
                // Split content into words
                $words = explode(' ', $vc_content);
    
                // Extract the first $word_count words
                $excerpt = array_slice($words, 0, $word_count);
    
                // Combine words into one string
                $excerpt = implode(' ', $excerpt);
    
                // Add an ellipsis at the end
                $excerpt .= '...';
            } else {
                $excerpt = '';
            }
    
            $link = get_permalink();
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
            $title = get_the_title();
            $date = get_the_date('Y-m-d'); // Get date in YYYY-MM-DD format
            $load_more = get_locale() == 'pl_PL' ? 'CZYTAJ WIĘCEJ' : 'READ MORE';

            // Format the date
            $date_obj = new DateTime($date);
            $formatted_date = $date_obj->format('d M'); // Format as DD Mmm
            
            if (get_locale() == 'pl_PL') {
                // Convert month abbreviations to Polish
                $formatted_date = str_replace(
                    array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
                    array('sty', 'lut', 'mar', 'kwi', 'maj', 'cze', 'lip', 'sie', 'wrz', 'paź', 'lis', 'gru'),
                    $formatted_date
                );
            }
    
            echo '
            <a class="pwe-post" href="'. $link .'">
                <div class="pwe-post-thumbnail">
                    <div class="image-container" style="background-image:url('. $image .');"></div>
                    <p class="pwe-post-date">'. $formatted_date .'</p>
                </div> 
                <h5 class="pwe-post-title">'. $title .'</h5>
                <p class="pwe-post-excerpt">'. $excerpt .'</p>
                <button class="pwe-post-btn">'. $load_more .'</button>
            </a>';
    
        endwhile;
    } else {
        echo '';
    }

    wp_die(); // End after AJAX response
}

// Register AJAX actions
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');