<?php

// Template single post 
function pwe_calendar_single_template($single_template) { 
    global $post;

    // If the post type is 'event', use the template 'single-calendar.php'
    if ($post->post_type == 'event') {
        $single_template = plugin_dir_path(__FILE__) . 'classes/single-calendar.php';
    }

    return $single_template;
}
add_filter('single_template', 'pwe_calendar_single_template');

// Add custom meta description to <head>
function custom_meta_description() {
    global $post;
    
    $meta_description = '';

    if ($post->post_type == 'event') {
        $website = get_post_meta($post->ID, 'web_page_link', true);
        if (!empty($website)) {
            $host = parse_url($website, PHP_URL_HOST);
            $domain = preg_replace('/^www\./', '', $host);
        }

        $shortcodes_active = empty(get_option('pwe_general_options', [])['pwe_dp_shortcodes_unactive']);

        if (!function_exists('get_translated_field')) {
            function get_translated_field($fair, $field_base_name) {
                // Get the language in the format e.g. "de", "pl"
                $locale = get_locale(); // ex. "de_DE"
                $lang = strtolower(substr($locale, 0, 2)); // "de"

                // Check if a specific translation exists (e.g. fair_name_{lang})
                $field_with_lang = "{$field_base_name}_{$lang}";

                if (!empty($fair[$field_with_lang])) {
                    return $fair[$field_with_lang];
                }

                // Fallback to English
                $fallback = "{$field_base_name}_en";
                return $fair[$fallback] ?? '';
            }
        }

        if (!function_exists('get_pwe_shortcode')) {
            function get_pwe_shortcode($shortcode, $domain) {
                return shortcode_exists($shortcode) ? do_shortcode('[' . $shortcode . ' domain="' . $domain . '"]') : "";
            }
        }

        if (!function_exists('check_available_pwe_shortcode')) {
            function check_available_pwe_shortcode($shortcodes_active, $shortcode) {
                return $shortcodes_active && !empty($shortcode) && $shortcode !== "";
            }
        }

        $translates = PWECommonFunctions::get_database_translations_data($domain);

        $shortcode_full_desc_pl = get_pwe_shortcode("pwe_full_desc_pl", $domain);
        $shortcode_full_desc_pl_available = check_available_pwe_shortcode($shortcodes_active, $shortcode_full_desc_pl);
        $fair_full_desc = $shortcode_full_desc_pl_available ? get_translated_field($translates[0], 'fair_full_desc') : '';

        if (!empty($fair_full_desc)) {
            $meta_description = strstr($fair_full_desc, '<br>', true);
            
            // If strstr returned false (i.e. no <br>), we assign the entire content
            if ($meta_description === false) {
                $meta_description = $fair_full_desc;
            }
        }
    } 
    
    // Add meta description to the <head> section
    echo '<meta name="description" content="' . esc_attr(strip_tags($meta_description)) . '">';
}
add_action('wp_head', 'custom_meta_description');

// Register one custom post type
function create_event_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Events',
            'singular_name' => 'Event',
            'add_new' => 'Add new event',
            'add_new_item' => 'Add new event',
            'edit_item' => 'Edit event',
            'new_item' => 'New event',
            'view_item' => 'See the event',
            'all_items' => 'All events',
            'search_items' => 'Search event',
            'not_found' => 'No events',
            'not_found_in_trash' => 'No events in the basket',
            'menu_name' => 'PWE Events'
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'kalendarz-targowy'),
        'supports' => array('title', 'custom-fields'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar',
        'menu_position' => 3,
    );
    register_post_type('event', $args);

    // Registering a taxonomy (category) for a custom post type
    $taxonomy_args = array(
        'labels' => array(
            'name' => 'Event Categories',
            'singular_name' => 'Event Category',
            'search_items' => 'Search Categories',
            'all_items' => 'All Categories',
            'parent_item' => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item' => 'Edit Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'menu_name' => 'Categories',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'event-category'),
    );
    
    register_taxonomy('event_category', 'event', $taxonomy_args);
}
add_action('init', 'create_event_post_type');

// Taxonomy „Event Type” (event / week)
function create_event_type_taxonomy() {
    $args = array(
        'labels' => array(
            'name' => 'Event type',
            'singular_name' => 'Event type',
            'search_items' => 'Search for type',
            'all_items' => 'All types',
            'edit_item' => 'Edit type',
            'update_item' => 'Update type',
            'add_new_item' => 'Add new type',
            'new_item_name' => 'New type name',
            'menu_name' => 'Types of events'
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'event-type'),
    );
    register_taxonomy('event_type', 'event', $args);

    if (!get_term_by('slug', 'event', 'event_type')) {
        wp_insert_term('Event', 'event_type', ['slug' => 'event']);
    }
    if (!get_term_by('slug', 'week', 'event_type')) {
        wp_insert_term('Week', 'event_type', ['slug' => 'week']);
    }
}
add_action('init', 'create_event_type_taxonomy');

// Separate tabs in the admin menu
add_action('admin_menu', function() {
     // Remote the default "Add New"
    remove_submenu_page('edit.php?post_type=event', 'post-new.php?post_type=event');

    add_submenu_page(
        'edit.php?post_type=event',
        'Add a new event',
        'Add a new event',
        'edit_posts',
        'post-new.php?post_type=event&set_event_type=event'
    );
    add_submenu_page(
        'edit.php?post_type=event',
        'Add a new week',
        'Add a new week',
        'edit_posts',
        'post-new.php?post_type=event&set_event_type=week'
    );
    // One single events
    add_submenu_page(
        'edit.php?post_type=event',
        'Events (single)',
        'Events (single)',
        'edit_posts',
        'edit.php?post_type=event&event_type=event'
    );
    // Only weeks
    add_submenu_page(
        'edit.php?post_type=event',
        'Weeks of events',
        'Weeks of events',
        'edit_posts',
        'edit.php?post_type=event&event_type=week'
    );
});

// WP_ADMIN filtering (weeks only/events only)
add_action('restrict_manage_posts', function($post_type) {
    if ($post_type === 'event') {
        $selected = isset($_GET['event_type']) ? $_GET['event_type'] : '';
        $terms = get_terms(array(
            'taxonomy' => 'event_type',
            'hide_empty' => false,
        ));
        echo '<select name="event_type" id="event_type">';
        echo '<option value="">-- All types --</option>';
        foreach ($terms as $term) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($term->slug),
                selected($selected, $term->slug, false),
                esc_html($term->name)
            );
        }
        echo '</select>';
    }
});

// Taxonomy filter
add_filter('parse_query', function($query) {
    global $pagenow;
    if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'event' && isset($_GET['event_type']) && $_GET['event_type'] != '') {
        $query->query_vars['tax_query'][] = array(
            'taxonomy' => 'event_type',
            'field' => 'slug',
            'terms' => $_GET['event_type'],
        );
    }
});

// helper: find source post ID by WPML trid + source_lang
function pwe_wpml_source_from_request() {
    if (!defined('ICL_SITEPRESS_VERSION')) return 0;

    $trid = isset($_GET['trid']) ? (int) $_GET['trid'] : 0;
    if (!$trid) return 0;

    $source_lang = isset($_GET['source_lang']) ? sanitize_text_field($_GET['source_lang']) : '';

    // For CPT 'event' element type = 'post_event'
    $translations = apply_filters('wpml_get_element_translations', null, $trid, 'post_event');
    if (!is_array($translations)) return 0;

    if ($source_lang) {
        foreach ($translations as $t) {
            if (!empty($t->language_code) && $t->language_code === $source_lang) {
                return (int) $t->element_id;
            }
        }
    }
    // original
    foreach ($translations as $t) {
        if (!empty($t->original)) {
            return (int) $t->element_id;
        }
    }
    // fallback
    foreach ($translations as $t) {
        return (int) $t->element_id;
    }
    return 0;
}

// Helper for reading the type (week/event) from the source post
function pwe_get_event_type_slug($post_id) {
    $terms = wp_get_post_terms($post_id, 'event_type', array('fields' => 'slugs'));
    if (!empty($terms) && !is_wp_error($terms)) {
        return $terms[0];
    }
    $meta = get_post_meta($post_id, 'pwe_event_type', true);
    if (!empty($meta)) {
        return sanitize_key($meta);
    }
    return null;
}

// Append set_event_type={week|event} to the URL when creating the translation
add_action('load-post-new.php', function () {
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'event') return;

    // set original: from_post -> trid/source_lang
    $src_id = isset($_GET['from_post']) ? (int) $_GET['from_post'] : 0;
    if (!$src_id) {
        $src_id = pwe_wpml_source_from_request();
    }
    if (!$src_id) return;

    // what type is the original? (event|week)
    $slug = pwe_get_event_type_slug($src_id);
    if (empty($slug)) $slug = 'event';

    // if the URL doesn't have the correct set_event_type yet -> redirect with the attached parameter
    if (!isset($_GET['set_event_type']) || $_GET['set_event_type'] !== $slug) {
        wp_safe_redirect(add_query_arg('set_event_type', $slug));
        exit;
    }
});

// Various fields in edit (dynamic meta boxes)
add_action('add_meta_boxes_event', function($post) {
    $event_type = '';

    if (is_object($post) && isset($post->ID) && $post->ID) {
        $terms = wp_get_post_terms($post->ID, 'event_type', array('fields' => 'slugs'));
        if (!empty($terms)) {
            $event_type = $terms[0];
        } else {
            $event_type = get_post_meta($post->ID, 'pwe_event_type', true);
        }

        // Fallback – jeśli wciąż pusto, dociągnij z oryginału WPML
        if (empty($event_type) && defined('ICL_SITEPRESS_VERSION')) {
            $src_id = isset($_GET['from_post']) ? (int) $_GET['from_post'] : pwe_wpml_source_from_request();
            if ($src_id) {
                $event_type = pwe_get_event_type_slug($src_id);
            }
        }
    } else {
        if (isset($_GET['set_event_type'])) {
            $event_type = sanitize_key($_GET['set_event_type']);
        }
    }

    if ($event_type === 'week') {
        // Metabox for fairs field
        add_meta_box(
            'events_week_fairs',
            'All events of week',
            'events_week_fairs_callback',
            'event',
            'normal',
            'high'
        );
        // Metabox for dates fields
        add_meta_box(
            'events_dates',
            'Events week dates',
            'events_week_dates_callback',
            'event',
            'normal',
            'high'
        );
        // Metabox for other fields
        add_meta_box(
            'events_week_halls',
            'Halls',
            'events_week_halls_callback',
            'event',
            'normal',
            'high'
        );
        // Metabox for other fields
        add_meta_box(
            'events_week_other',
            'Other options',
            'events_week_other_callback',
            'event',
            'normal',
            'high'
        );
        // Featured Image
        add_meta_box(
            'featured_image_url',
            'Featured Image',
            'featured_image_meta_box_callback',
            'event',
            'side',
            'high'
        );
        // Secondary image
        add_meta_box(
            'secondary_image_url',
            'Secondary Image',
            'secondary_image_meta_box_callback',
            'event', 
            'side',
            'high'
        );
        // Metabox for header image
        add_meta_box(
            'header_image',
            'Header Image',
            'header_image_callback',
            'event',
            'side',
            'high'
        );
    }
    if ($event_type === 'event' || $event_type === '') {
        // Metabox for links fields
        add_meta_box(
            'event_links', 
            'Event links', 
            'event_links_callback', 
            'event', // Typ postu
            'normal', // Pozycja
            'high' // Priorytet
        );
        // Metabox for desc fields
        add_meta_box(
            'event_desc',
            'Event description',
            'event_desc_callback',
            'event',
            'normal',
            'high'
        );
        // Metabox for dates fields
        add_meta_box(
            'event_dates',
            'Event dates',
            'event_dates_callback',
            'event',
            'normal',
            'high'
        );
        // Metabox for colors fields
        add_meta_box(
            'event_colors',
            'Event colors',
            'event_colors_callback',
            'event',
            'normal',
            'high'
        );

        // Metabox fo statistics fields
        add_meta_box(
            'event_statistics',
            'Statistics',
            'event_statistics_callback',
            'event',
            'normal',
            'high'
        );
        // Metabox for the organizer fields
        add_meta_box(
            'event_organizer',
            'Organizer information',
            'event_organizer_callback',
            'event',
            'normal',
            'high'
        );
        // Metabox for other fields
        add_meta_box(
            'event_other',
            'Other information',
            'event_other_callback',
            'event',
            'normal',
            'high'
        );
        // Featured Image
        add_meta_box(
            'featured_image_url',
            'Featured Image',
            'featured_image_meta_box_callback',
            'event',
            'side',
            'high'
        );

        // Secondary image
        add_meta_box(
            'secondary_image_url',
            'Secondary Image',
            'secondary_image_meta_box_callback',
            'event', 
            'side',
            'high'
        );
        // Metabox for header image
        add_meta_box(
            'header_image',
            'Header Image',
            'header_image_callback',
            'event',
            'side',
            'low'
        );
        // Metabox for logo image
        add_meta_box(
            'logo_image',
            'Logo Image',
            'logo_image_callback',
            'event',
            'side',
            'low'
        );
        // Metabox for gallery of partners
        add_meta_box(
            'partners_gallery',
            'Partners Gallery',
            'partners_gallery_callback', 
            'event',
            'side',
            'low'
        );
    }
});

add_action('save_post_event', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (defined('DOING_AJAX') && DOING_AJAX) return;
 
    $current_types = wp_get_post_terms($post_id, 'event_type', ['fields' => 'ids']);
    if (!empty($current_types)) return;

    // Próbuj najpierw z GET/POST (np. jak tworzysz przez custom link)
    $event_type = null;
    if (isset($_GET['set_event_type'])) {
        $event_type = sanitize_key($_GET['set_event_type']);
    } elseif (isset($_POST['set_event_type'])) {
        $event_type = sanitize_key($_POST['set_event_type']);
    } else {
        $event_type = 'event';
    }

    update_post_meta($post_id, 'pwe_event_type', $event_type);

    $term = get_term_by('slug', $event_type, 'event_type');
    if ($term) {
        wp_set_post_terms($post_id, [$term->term_id], 'event_type');
    }
}, 10, 3);

// Replace the event_type taxonomy meta box with radio (single selection)
add_action('admin_menu', function() {
    remove_meta_box('event_typediv', 'event', 'side');
    add_meta_box('event_type_radio', 'Event type', function($post){
        $current = get_post_meta($post->ID, 'pwe_event_type', true);
        $terms = get_terms(['taxonomy' => 'event_type', 'hide_empty' => false]);
        foreach ($terms as $t) {
            printf(
                '<p><label><input type="radio" name="tax_input[event_type][]" value="%d" %s> %s</label></p>',
                $t->term_id,
                checked($current === $t->slug, true, false),
                esc_html($t->name)
            );
        }
    }, 'event', 'side', 'core');
});

// GENERAL metaboxes <----------------------------------<

function featured_image_meta_box_callback($post) {
    wp_nonce_field('save_featured_image', 'featured_image_nonce');
    $halls_image_url = get_post_meta($post->ID, '_featured_image_url', true);
    
    echo '
    <label for="featured_image_url">Featured Image URL:</label>
    <div class="featured-image-url-container">
        <input type="text" id="featured_image_url" name="featured_image_url" class="featured-image-url pwe-calendar-full-width-input" value="' . esc_attr($halls_image_url) . '" />
    </div>
    <br>
    <input type="button" class="button-secondary" value="Select Image" id="featured_image_button" />
    <div id="featured_image_preview" style="margin-top: 10px;">';
        if (!empty($halls_image_url)) {
            echo '<img src="' . esc_url($halls_image_url) . '" style="max-width: 250px; width: 100%;" />';
        }
    echo '
    </div>';
    
    ?>
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('#featured_image_button').click(function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Initialization uploader
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Select Featured Image',
                button: {
                    text: 'Select Image'
                },
                multiple: false
            });
            
            // After selecting the image, insert the URL into the field
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();

                $('.featured-image-url-container').html('<input type="text" id="featured_image_url" name="featured_image_url" class="featured-image-url pwe-calendar-full-width-input" value="' + attachment.url + '" />');
                $('#featured_image_preview').html('<img src="' + attachment.url + '" style="max-width: 250px; width: 100%;" />');
            });

            mediaUploader.open();
        });
    });
    </script>
    <?php
}

function secondary_image_meta_box_callback($post) {
    wp_nonce_field('save_secondary_image', 'secondary_image_nonce');
    $secondary_image_url = get_post_meta($post->ID, '_secondary_image_url', true);
    
    echo '
    <label for="secondary_image_url">Secondary Image URL:</label>
    <div class="secondary-image-url-container">
        <input type="text" id="secondary_image_url" name="secondary_image_url" class="secondary-image-url pwe-calendar-full-width-input" value="' . esc_attr($secondary_image_url) . '" />
    </div>
    <br>
    <input type="button" class="button-secondary" value="Select Image" id="secondary_image_button" />
    <div id="secondary_image_preview" style="margin-top: 10px;">';
        if (!empty($secondary_image_url)) {
            echo '<img src="' . esc_url($secondary_image_url) . '" style="max-width: 250px; width: 100%;" />';
        }
    echo '
    </div>';
    
    ?>
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('#secondary_image_button').click(function(e) {
            e.preventDefault();
            
            // Jeśli uploader istnieje, otwieramy go
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Initialization uploader
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Select Secondary Image',
                button: {
                    text: 'Select Image'
                },
                multiple: false
            });

            // After selecting the image, insert the URL into the field
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('.secondary-image-url-container').html('<input type="text" id="secondary_image_url" name="secondary_image_url" class="secondary-image-url pwe-calendar-full-width-input" value="' + attachment.url + '" />');
                $('#secondary_image_preview').html('<img src="' + attachment.url + '" style="max-width: 250px; width: 100%;" />'); // Wyświetlamy podgląd
            });

            mediaUploader.open();
        });
    });
    </script>
    <?php
}

function header_image_callback($post) {
    wp_nonce_field('save_header_image', 'header_image_nonce');
    $header_image_url = !empty(get_post_meta($post->ID, '_header_image', true)) ? get_post_meta($post->ID, '_header_image', true) : '';
    
    echo '
    <div class="pwe-calendar-input">
        <label for="header_image">Upload Header Image:</label>
        <div class="header-image-url-container">
            <input type="text" id="header_image" name="header_image" value="' . esc_attr($header_image_url) . '" style="width:100%;" />
        </div>
        <input type="button" class="button-secondary" value="Select Image" id="header_image_button" />
        <div id="header_image_preview" style="margin-top: 10px;">';
            if (!empty($header_image_url)) {
                echo '<img src="' . esc_url($header_image_url) . '" style="max-width: 250px; width: 100%;" />';
            }
        echo '
        </div>
    </div>';

    ?>
    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('#header_image_button').click(function(e) {
                e.preventDefault();

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                // Initializing the uploader
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Select Header Image',
                    button: {
                        text: 'Select Image'
                    },
                    multiple: false
                });

                // After selecting the image, insert the URL into the field
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();

                    $('.header-image-url-container').html('<input type="text" id="header_image" name="header_image" value="' + attachment.url + '" style="width:100%;" />');
                    $('#header_image_preview').html('<img src="' + attachment.url + '" style="max-width: 250px; width: 100%;" />');
                });

                mediaUploader.open();
            });
        });
    </script>
    <?php 
}

// WEEK metaboxes <----------------------------------<

function events_week_fairs_callback($post) {
    wp_nonce_field('save_events_week_fairs', 'events_week_fairs_nonce');
    $lang = ICL_LANGUAGE_CODE;
    $date_start = get_post_meta($post->ID, 'fair_date_start', true);
    $date_end   = get_post_meta($post->ID, 'fair_date_end', true);

    // Pobierz zapisane wykluczone targi
    $excluded_events = get_post_meta($post->ID, 'events_week_fairs_excluded', true);
    $excluded_events_array = !empty($excluded_events) ? explode(', ', $excluded_events) : [];

    $events_list = [];
    if (!empty($date_start) && !empty($date_end)) {
        $trade_fair_start_timestamp = strtotime($date_start);
        $trade_fair_end_timestamp   = strtotime($date_end);

        $fairs_json = PWECommonFunctions::json_fairs();

        foreach ($fairs_json as $fair) {
            $event_date_start = isset($fair['date_start']) ? strtotime($fair['date_start']) : null;
            $event_date_end   = isset($fair['date_end']) ? strtotime($fair['date_end']) : null;
            $event_domain     = $fair["domain"];

            if ($event_date_start && $event_date_end) {
                $isStartInside    = $event_date_start >= $trade_fair_start_timestamp && $event_date_start <= $trade_fair_end_timestamp;
                $isEndInside      = $event_date_end >= $trade_fair_start_timestamp && $event_date_end <= $trade_fair_end_timestamp;
                $isNotFastTextile = strpos($fair['domain'], "fasttextile.com") === false;
                $isNotExpoTrends  = strpos($fair['domain'], "expotrends.eu") === false;
                $isNotFabricsExpo = strpos($fair['domain'], "fabrics-expo.eu") === false;
                $isNotTest        = strpos($fair['domain'], "mr.glasstec.pl") === false;

                if (($isStartInside || $isEndInside) && $isNotFastTextile && $isNotExpoTrends && $isNotFabricsExpo && $isNotTest) {
                    $events_list[] = $event_domain;
                }
            }
        }
    }

    echo '<span style="color: red;">Wykluczone targi:</span>' . $excluded_events;

    // Display all fairs in a div (for preview only)
    echo '<div class="pwe-calendar-inputs-container">
            <div class="pwe-calendar-input">
                <span style="color: green;">Wszystkie targi przypadające w terminie od '. esc_html($date_start) .' do '. esc_html($date_end) .'</span>
                <div style="pointer-events: none; opacity: 0.5; margin-bottom: 10px;">';
                    echo esc_html(implode(', ', $events_list));
    echo '      </div>
            </div>
          </div>';

    // Display checkboxes for selecting exclusions
    if (!empty($events_list)) {
        echo '<div class="pwe-calendar-inputs-container">
                <span style="color: blue;">Wybierz targi do wykluczenia:</span>
                <div class="pwe-calendar-input" style="display: flex; flex-wrap: wrap; flex-direction: inherit; margin-top: 8px;">';
                    foreach ($events_list as $event) {
                        $checked = in_array($event, $excluded_events_array) ? 'checked' : '';
                        echo '
                        <label style="display:block; margin-bottom: 4px;">
                            <input type="checkbox" name="events_week_fairs_checkbox[]" value="'. esc_attr($event) .'" '. $checked .'> '. esc_html($event) .'
                        </label>';
                    }
        echo '</div>
        </div>';
    }
}

function events_week_dates_callback($post) {
    wp_nonce_field('save_events_week_dates', 'events_week_dates_nonce');
 
    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input one-third-width">
            <label for="fair_date_start">Fairs Date Start: </label>
            <input type="text" id="fair_date_start" name="fair_date_start" class="pwe-calendar-full-width-input" placeholder="Data początkowa" value="'. get_post_meta($post->ID, 'fair_date_start', true) .'" />
        </div>
        <div class="pwe-calendar-input one-third-width">
            <label for="fair_date_end">Fairs Date End: </label>
            <input type="text" id="fair_date_end" name="fair_date_end" class="pwe-calendar-full-width-input" placeholder="Data końcowa" value="'. get_post_meta($post->ID, 'fair_date_end', true) .'" />
        </div>
    </div>';
}

function events_week_halls_callback($post) {
    wp_nonce_field('save_events_week_halls', 'events_week_halls_nonce');

    $halls_image_url = get_post_meta($post->ID, 'events_week_halls_image_url', true);
    
    echo '
    <label for="events_week_halls_image_url">Featured Image URL:</label>
    <div class="events_week_halls_image_url-container">
        <input type="text" id="events_week_halls_image_url" name="events_week_halls_image_url" class="featured-image-url pwe-calendar-full-width-input" value="' . esc_attr($halls_image_url) . '" />
    </div>
    <br>
    <input type="button" class="button-secondary" value="Select Image" id="events_week_halls_image_url_button" />
    <div id="events_week_halls_image_url_preview" style="margin-top: 10px;">';
        if (!empty($halls_image_url)) {
            echo '<img src="' . esc_url($halls_image_url) . '" style="max-width: 250px; width: 100%;" />';
        }
    echo '
    </div>';
    
    ?>
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('#events_week_halls_image_url_button').click(function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Initialization uploader
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Select Image'
                },
                multiple: false
            });
            
            // After selecting the image, insert the URL into the field
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();

                $('.events_week_halls_image_url-container').html('<input type="text" id="events_week_halls_image_url" name="events_week_halls_image_url" class="events_week_halls_image_url pwe-calendar-full-width-input" value="' + attachment.url + '" />');
                $('#events_week_halls_image_url_preview').html('<img src="' + attachment.url + '" style="max-width: 250px; width: 100%;" />');
            });

            mediaUploader.open();
        });
    });
    </script>
    <?php
}

function events_week_other_callback($post) {
    wp_nonce_field('save_events_week_other', 'events_week_other_nonce');

    $percent = !empty(get_post_meta($post->ID, 'events_week_percent', true)) ? get_post_meta($post->ID, 'events_week_percent', true) : '';
    $area = !empty(get_post_meta($post->ID, 'events_week_area', true)) ? get_post_meta($post->ID, 'events_week_area', true) : '';
    $visitors = !empty(get_post_meta($post->ID, 'events_week_visitors', true)) ? get_post_meta($post->ID, 'events_week_visitors', true) : '';
    $visitors_foreign = !empty(get_post_meta($post->ID, 'events_week_visitors_foreign', true)) ? get_post_meta($post->ID, 'events_week_visitors_foreign', true) : '';
    $exhibitors = !empty(get_post_meta($post->ID, 'events_week_exhibitors', true)) ? get_post_meta($post->ID, 'events_week_exhibitors', true) : '';
    $color_1 = !empty(get_post_meta($post->ID, 'events_week_color_1', true)) ? get_post_meta($post->ID, 'events_week_color_1', true) : '';
    $color_2 = !empty(get_post_meta($post->ID, 'events_week_color_2', true)) ? get_post_meta($post->ID, 'events_week_color_2', true) : '';

    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="events_week_visitors">Visitors: </label>
            <input type="number" id="events_week_visitors" name="events_week_visitors" class="pwe-calendar-full-width-input" value="'. $visitors .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="events_week_visitors_foreign">Visitors foreign: </label>
            <input type="number" id="events_week_visitors_foreign" name="events_week_visitors_foreign" class="pwe-calendar-full-width-input" value="'. $visitors_foreign .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="events_week_exhibitors">Exhibitors: </label>
            <input type="number" id="events_week_exhibitors" name="events_week_exhibitors" class="pwe-calendar-full-width-input" value="'. $exhibitors .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="events_week_percent">Increase since last year (%): </label>
            <input type="number" id="events_week_percent" name="events_week_percent" class="pwe-calendar-full-width-input" value="'. $percent .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="events_week_area">Exhibition space: </label>
            <input type="number" id="events_week_area" name="events_week_area" class="pwe-calendar-full-width-input" value="'. $area .'" />
        </div>
    </div>
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="events_week_color_1">Color title 1: </label>
            <input type="text" id="events_week_color_1" name="events_week_color_1" class="pwe-calendar-full-width-input color-picker" value="'. $color_1 .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="events_week_color_2">Color title 2: </label>
            <input type="text" id="events_week_color_2" name="events_week_color_2" class="pwe-calendar-full-width-input color-picker" value="'. $color_2 .'" />
        </div>
    </div>';
}

// Function of saving data from metaboxes 
function save_events_week_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (isset($_POST['events_week_dates_nonce']) && wp_verify_nonce($_POST['events_week_dates_nonce'], 'save_events_week_dates')) {
        update_post_meta($post_id, 'fair_date_start', sanitize_text_field($_POST['fair_date_start']));
        update_post_meta($post_id, 'fair_date_end', sanitize_text_field($_POST['fair_date_end']));
    }

    if (isset($_POST['header_slider']) && is_array($_POST['header_slider'])) {
        $headers = array();
        foreach ($_POST['header_slider'] as $header) {
            $headers[] = array(
                'text' => sanitize_text_field($header['text'] ?? ''),
                'category' => sanitize_text_field($header['category'] ?? ''),
                'image_id' => isset($header['image_id']) ? intval($header['image_id']) : '',
            );
        }
        update_post_meta($post_id, 'header_slider', $headers);
    } else {
        delete_post_meta($post_id, 'header_slider');
    }

    if (isset($_POST['events_week_halls_nonce']) && wp_verify_nonce($_POST['events_week_halls_nonce'], 'save_events_week_halls')) {
        if (isset($_POST['events_week_halls_image_url'])) {
            update_post_meta($post_id, 'events_week_halls_image_url', sanitize_text_field($_POST['events_week_halls_image_url']));
        }
    }

    if (isset($_POST['events_week_other_nonce']) && wp_verify_nonce($_POST['events_week_other_nonce'], 'save_events_week_other')) {
        update_post_meta($post_id, 'events_week_percent', sanitize_text_field($_POST['events_week_percent']));
        update_post_meta($post_id, 'events_week_visitors', sanitize_text_field($_POST['events_week_visitors']));
        update_post_meta($post_id, 'events_week_visitors_foreign', sanitize_text_field($_POST['events_week_visitors_foreign']));
        update_post_meta($post_id, 'events_week_exhibitors', sanitize_text_field($_POST['events_week_exhibitors']));
        update_post_meta($post_id, 'events_week_area', sanitize_text_field($_POST['events_week_area']));
        update_post_meta($post_id, 'events_week_color_1', sanitize_text_field($_POST['events_week_color_1']));
        update_post_meta($post_id, 'events_week_color_2', sanitize_text_field($_POST['events_week_color_2']));
    }

    if (isset($_POST['events_week_fairs_nonce']) && wp_verify_nonce($_POST['events_week_fairs_nonce'], 'save_events_week_fairs')) {
        $excluded_events = isset($_POST['events_week_fairs_checkbox']) ? $_POST['events_week_fairs_checkbox'] : [];
        $excluded_events_string = implode(', ', array_map('sanitize_text_field', $excluded_events));
        update_post_meta($post_id, 'events_week_fairs_excluded', $excluded_events_string);
    }
    
    if (isset($_POST['featured_image_nonce']) && wp_verify_nonce($_POST['featured_image_nonce'], 'save_featured_image')) {
        if (isset($_POST['featured_image_url'])) {
            update_post_meta($post_id, '_featured_image_url', sanitize_text_field($_POST['featured_image_url']));
        }
    }

    if (isset($_POST['secondary_image_nonce']) && wp_verify_nonce($_POST['secondary_image_nonce'], 'save_secondary_image')) {
        if (isset($_POST['secondary_image_url'])) {
            update_post_meta($post_id, '_secondary_image_url', sanitize_text_field($_POST['secondary_image_url']));
        }
    }

    if (isset($_POST['header_image_nonce']) && wp_verify_nonce($_POST['header_image_nonce'], 'save_header_image')) {
        if (isset($_POST['header_image'])) {
            update_post_meta($post_id, '_header_image', sanitize_text_field($_POST['header_image']));
        }
    }
}

add_action('save_post', 'save_events_week_meta');

add_action('admin_enqueue_scripts', function($hook) {
    if ('post.php' == $hook || 'post-new.php' == $hook) {
        wp_enqueue_media();
    }
});

// EVENT metaboxes <----------------------------------<

function event_links_callback($post) {
    wp_nonce_field('save_event_links', 'event_links_nonce');
    $lang = ICL_LANGUAGE_CODE;
    $website = get_post_meta($post->ID, 'web_page_link', true);
    $target_blank = get_post_meta($post->ID, 'web_page_link_target_blank', true);
    if (!empty($website)) {
        $host = parse_url($website, PHP_URL_HOST);
        $domain = preg_replace('/^www\./', '', $host);
        $web_page_link = 'https://'. $domain .'/';
    }
    $categories = get_the_terms($post->ID, 'event_category');

    if ($categories && !is_wp_error($categories)) {
        $category_names = wp_list_pluck($categories, 'name');
        $category_string = implode(', ', $category_names);
    }

    $exhibitor_registration_link = !empty($website) ? $web_page_link . ($lang === "pl" ? 'zostan-wystawca/' : 'en/become-an-exhibitor/') : '';
    if (strpos(strtolower($category_string), 'b2c') !== false) {
        $buy_ticket_link = !empty($website) ? $web_page_link . ($lang === "pl" ? 'kup-bilet/' : 'en/buy-ticket/') : '';
    } else {
        $visitor_registration_link = !empty($website) ? $web_page_link . ($lang === "pl" ? 'rejestracja/' : 'en/registration/') : '';
    }
    
    $visitor_registration_link = !empty(get_post_meta($post->ID, 'visitor_registration_link', true)) ? get_post_meta($post->ID, 'visitor_registration_link', true) : $visitor_registration_link;
    $exhibitor_registration_link = !empty(get_post_meta($post->ID, 'exhibitor_registration_link', true)) ? get_post_meta($post->ID, 'exhibitor_registration_link', true) : $exhibitor_registration_link;
    $buy_ticket_link = !empty(get_post_meta($post->ID, 'buy_ticket_link', true)) ? get_post_meta($post->ID, 'buy_ticket_link', true) : $buy_ticket_link;

    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input one-seventh-width">
            <label for="web_page_link">Web Page Link: <span style="font-weight: 700;">(ENTER TO GET DATA FROM CAP)</span></label>
            <input type="text" id="web_page_link" name="web_page_link" class="pwe-calendar-full-width-input" placeholder="'. 'https://domain/' . ($lang === "pl" ? '' : 'en/') .'" value="'. $website .'" />
        </div>
        <div class="pwe-calendar-input one-third-width checkbox">
            <label for="web_page_link_target_blank">Open <strong>Web Page Link</strong><br>in a new window</label>
            <input type="checkbox" id="web_page_link_target_blank" name="web_page_link_target_blank" '. checked($target_blank, true, false) .'/>
        </div>
    </div>

    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input one-third-width">
            <label for="visitor_registration_link">Visitor Registration Link: </label>
            <input type="text" id="visitor_registration_link" name="visitor_registration_link" class="pwe-calendar-full-width-input" placeholder="'. 'https://domain/' . ($lang === "pl" ? 'rejestracja/' : 'en/registration/') .'" value="'. $visitor_registration_link .'" />
        </div>
        <div class="pwe-calendar-input one-third-width">
            <label for="exhibitor_registration_link">Exhibitor Registration Link: </label>
            <input type="text" id="exhibitor_registration_link" name="exhibitor_registration_link" class="pwe-calendar-full-width-input" placeholder="'. 'https://domain/' . ($lang === "pl" ? 'zostan-wystawca/' : 'en/become-an-exhibitor/') .'" value="'. $exhibitor_registration_link .'" />
        </div>
        <div class="pwe-calendar-input one-third-width">
            <label for="buy_ticket_link">Buy Ticket Link: </label>
            <input type="text" id="buy_ticket_link" name="buy_ticket_link" class="pwe-calendar-full-width-input" placeholder="'. 'https://domain/' . ($lang === "pl" ? 'kup-bilet/' : 'en/buy-ticket/') .'" value="'. $buy_ticket_link .'" />
        </div>
    </div>';
}
 
function event_desc_callback($post) {
    wp_nonce_field('save_event_desc', 'event_desc_nonce');
    $lang = ICL_LANGUAGE_CODE;
    $website = get_post_meta($post->ID, 'web_page_link', true);
    if (!empty($website)) {
        $host = parse_url($website, PHP_URL_HOST);
        $domain = preg_replace('/^www\./', '', $host);
    
        $event_desc = !empty(get_post_meta($post->ID, 'desc', true)) ? get_post_meta($post->ID, 'desc', true) : do_shortcode('[pwe_desc_'. ($lang === "pl" ? 'pl' : 'en') .' domain="' . $domain . '"]');
        $event_short_desc = !empty(get_post_meta($post->ID, 'short_desc', true)) ? get_post_meta($post->ID, 'short_desc', true) : do_shortcode('[pwe_short_desc_'. ($lang === "pl" ? 'pl' : 'en') .' domain="' . $domain . '"]');
    }
    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input">
            <label for="desc">Event desc</label>
            <input type="text" id="desc" name="desc" class="pwe-calendar-full-width-input" value="'. $event_desc .'" />
        </div>
    </div>
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input">
            <label for="short_desc">Short event desc </label>
            <input type="text" id="short_desc" name="short_desc" class="pwe-calendar-full-width-input" value="'. $event_short_desc .'" />
        </div>
    </div>';
}

function event_dates_callback($post) {
    wp_nonce_field('save_event_dates', 'event_dates_nonce');
    $shortcodes_active = empty(get_option('pwe_general_options', [])['pwe_dp_shortcodes_unactive']);
    $lang = ICL_LANGUAGE_CODE;
    $website = get_post_meta($post->ID, 'web_page_link', true);
    if (!empty($website)) {
        $host = parse_url($website, PHP_URL_HOST);
        $domain = preg_replace('/^www\./', '', $host);

        $pwe_db_date_start = do_shortcode('[pwe_date_start domain="' . $domain . '"]');
        $pwe_db_date_end = do_shortcode('[pwe_date_end domain="' . $domain . '"]');
        $pwe_db_date_start_available = $shortcodes_active && !empty($pwe_db_date_start) && $pwe_db_date_start !== "";
        $pwe_db_date_end_available = $shortcodes_active && !empty($pwe_db_date_end) && $pwe_db_date_end !== "";

        $fair_date_start_cap = $pwe_db_date_start_available ? date("d-m-Y", strtotime(str_replace("/", "-", $pwe_db_date_start))) : "";
        $fair_date_end_cap = $pwe_db_date_end_available ? date("d-m-Y", strtotime(str_replace("/", "-", $pwe_db_date_end))) : "";

        if ($lang == "pl") {
            $new_date_coming_soon = "Nowa data wkrótce";
        } else if ($lang == "en") {
            $new_date_coming_soon = "New date coming soon";
        } else if ($lang == "de") {
            $new_date_coming_soon = "Neuer Termin folgt in Kürze";
        } else if ($lang == "lt") {
            $new_date_coming_soon = "Nauja data netrukus";
        } else if ($lang == "lv") {
            $new_date_coming_soon = "Jauns datums drīzumā";
        } else if ($lang == "uk") {
            $new_date_coming_soon = "Нова дата незабаром";
        } else if ($lang == "cs") {
            $new_date_coming_soon = "Nový termín již brzy";
        } else if ($lang == "sk") {
            $new_date_coming_soon = "Nový termín už čoskoro";
        } else if ($lang == "ru") {
            $new_date_coming_soon = "Новая дата скоро";
        } else {
            $new_date_coming_soon = "New date coming soon";
        }

        // delete_post_meta($post->ID, 'quarterly_date');


        $quarterly_date = ((empty($fair_date_start_cap) || empty($fair_date_end_cap)) && empty(get_post_meta($post->ID, 'quarterly_date', true))) ? $new_date_coming_soon : get_post_meta($post->ID, 'quarterly_date', true);
    }
    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input one-third-width">
            <label for="fair_date_start">Fair Date Start: </label>
            <input type="text" id="fair_date_start" name="fair_date_start" class="pwe-calendar-full-width-input" placeholder="'. (!empty($fair_date_start_cap) ? $fair_date_start_cap : 'empty') .' - (Date from CAP DB)" value="'. get_post_meta($post->ID, 'fair_date_start', true) .'" />
        </div>
        <div class="pwe-calendar-input one-third-width">
            <label for="fair_date_end">Fair Date End: </label>
            <input type="text" id="fair_date_end" name="fair_date_end" class="pwe-calendar-full-width-input" placeholder="'. (!empty($fair_date_end_cap) ? $fair_date_end_cap : 'empty') .' - (Date from CAP DB)" value="'. get_post_meta($post->ID, 'fair_date_end', true) .'" />
        </div>
        <div class="pwe-calendar-input one-third-width">
            <label for="quarterly_date">Quarterly Date: </label>
            <input type="text" id="quarterly_date" name="quarterly_date" class="pwe-calendar-full-width-input" placeholder="'. $quarterly_date .'" value="'. get_post_meta($post->ID, 'quarterly_date', true) .'" />
        </div>
    </div>';
}

function event_colors_callback($post) {
    wp_nonce_field('save_event_colors', 'event_colors_nonce');
    $website = get_post_meta($post->ID, 'web_page_link', true);
    if (!empty($website)) {
        $host = parse_url($website, PHP_URL_HOST);
        $domain = preg_replace('/^www\./', '', $host);

        $main_color = !empty(get_post_meta($post->ID, 'main_color', true)) ? get_post_meta($post->ID, 'main_color', true) : do_shortcode('[pwe_color_accent domain="' . $domain . '"]');
        $main2_color = !empty(get_post_meta($post->ID, 'main2_color', true)) ? get_post_meta($post->ID, 'main2_color', true) : do_shortcode('[pwe_color_main2 domain="' . $domain . '"]');
    }
    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="main_color">Main Color: </label>
            <input type="text" id="main_color" name="main_color" class="pwe-calendar-full-width-input color-picker" value="'. $main_color .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="main2_color">Main2 Color: </label>
            <input type="text" id="main2_color" name="main2_color" class="pwe-calendar-full-width-input color-picker" value="'. $main2_color .'" />
        </div>
    </div>';
}

function event_statistics_callback($post) {
    wp_nonce_field('save_event_statistics', 'event_statistics_nonce');
    $website = get_post_meta($post->ID, 'web_page_link', true);
    if (!empty($website)) {
        $host = parse_url($website, PHP_URL_HOST);
        $domain = preg_replace('/^www\./', '', $host);

        $visitors = !empty(get_post_meta($post->ID, 'visitors', true)) ? get_post_meta($post->ID, 'visitors', true) : do_shortcode('[pwe_visitors domain="' . $domain . '"]');
        $exhibitors = !empty(get_post_meta($post->ID, 'visitors', true)) ? get_post_meta($post->ID, 'exhibitors', true) : do_shortcode('[pwe_exhibitors domain="' . $domain . '"]');
        $countries = !empty(get_post_meta($post->ID, 'countries', true)) ? get_post_meta($post->ID, 'countries', true) : do_shortcode('[pwe_countries domain="' . $domain . '"]');
        $area = !empty(get_post_meta($post->ID, 'area', true)) ? get_post_meta($post->ID, 'area', true) : do_shortcode('[pwe_area domain="' . $domain . '"]');
    }
    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="visitors">Number of visitors: </label>
            <input type="text" id="visitors" name="visitors" class="pwe-calendar-full-width-input"  value="'. $visitors .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="exhibitors">Number of exhibitors: </label>
            <input type="text" id="exhibitors" name="exhibitors" class="pwe-calendar-full-width-input"  value="'. $exhibitors .'" />
        </div>
    </div>
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="countries">Participating countries: </label>
            <input type="text" id="countries" name="countries" class="pwe-calendar-full-width-input"  value="'. $countries .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="area">Exhibition area: </label>
            <input type="text" id="area" name="area" class="pwe-calendar-full-width-input"  value="'. $area .'" />
        </div>
    </div>';
}

function event_organizer_callback($post) {
    wp_nonce_field('save_event_organizer', 'event_organizer_nonce');

    $organizer_website = !empty(get_post_meta($post->ID, 'organizer_website', true)) ? get_post_meta($post->ID, 'organizer_website', true) : "https://warsawexpo.eu/";
    $organizer_email = !empty(get_post_meta($post->ID, 'organizer_email', true)) ? get_post_meta($post->ID, 'organizer_email', true) : "info@warsawexpo.eu";
    $organizer_phone = !empty(get_post_meta($post->ID, 'organizer_phone', true)) ? get_post_meta($post->ID, 'organizer_phone', true) : "+48 518 739 124";
    $organizer_name = !empty(get_post_meta($post->ID, 'organizer_name', true)) ? get_post_meta($post->ID, 'organizer_name', true) : "Ptak Warsaw Expo";

    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="organizer_website">Organizer`s Website: </label>
            <input type="text" id="organizer_website" name="organizer_website" class="pwe-calendar-full-width-input" value="'. $organizer_website .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="organizer_email">Organizer`s Email: </label>
            <input type="email" id="organizer_email" name="organizer_email" class="pwe-calendar-full-width-input" value="'. $organizer_email .'" />
        </div>
    </div>
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="organizer_phone">Organizer`s Phone Number: </label>
            <input type="text" id="organizer_phone" name="organizer_phone" class="pwe-calendar-full-width-input" value="'. $organizer_phone .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="organizer_name">Organizer`s Name: </label>
            <input type="text" id="organizer_name" name="organizer_name" class="pwe-calendar-full-width-input" value="'. $organizer_name .'" />
        </div>
    </div>';
}

function event_other_callback($post) {
    wp_nonce_field('save_event_other', 'event_other_nonce');
    $website = get_post_meta($post->ID, 'web_page_link', true);
    if (!empty($website)) {
        $host = parse_url($website, PHP_URL_HOST);
        $domain = preg_replace('/^www\./', '', $host);

        $edition = !empty(get_post_meta($post->ID, 'edition', true)) ? get_post_meta($post->ID, 'edition', true) : do_shortcode('[pwe_edition domain="' . $domain . '"]');
        $badge = !empty(get_post_meta($post->ID, 'badge', true)) ? get_post_meta($post->ID, 'badge', true) : do_shortcode('[pwe_badge domain="' . $domain . '"]');
    }
    echo '
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input half-width">
            <label for="edition">Edition: </label>
            <input type="text" id="edition" name="edition" class="pwe-calendar-full-width-input" value="'. $edition .'" />
        </div>
        <div class="pwe-calendar-input half-width">
            <label for="badge">Badge prefix: </label>
            <input type="text" id="badge" name="badge" class="pwe-calendar-full-width-input" value="'. $badge .'" />
        </div>
    </div>
    <div class="pwe-calendar-inputs-container">
        <div class="pwe-calendar-input">
            <label for="keywords">Words for search engine </label>
            <input type="text" id="keywords" name="keywords" class="pwe-calendar-full-width-input" value="'. get_post_meta($post->ID, 'keywords', true) .'" />
        </div>
    </div>';
}

function logo_image_callback($post) {
    wp_nonce_field('save_logo_image', 'logo_image_nonce');
    $website = get_post_meta($post->ID, 'web_page_link', true);
    if ($website) {
        $logo_image_url = !empty(get_post_meta($post->ID, '_logo_image', true)) ? get_post_meta($post->ID, '_logo_image', true) : '';
    }
    echo '
    <div class="pwe-calendar-input">
        <label for="logo_image">Upload Logo Image:</label>
        <div class="logo-image-url-container">
            <input type="text" id="logo_image" name="logo_image" value="' . esc_attr($logo_image_url) . '" style="width:100%;" />
        </div>
        <input type="button" class="button-secondary" value="Select Image" id="logo_image_button" />
        <div id="logo_image_preview" style="margin-top: 10px; background: #c6c6c6;">';
            if (!empty($logo_image_url)) {
                echo '<img src="' . esc_url($logo_image_url) . '" style="max-width: 250px; width: 100%;" />';
            }
        echo '
        </div>
    </div>';

    ?>
    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('#logo_image_button').click(function(e) {
                e.preventDefault();

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                // Initialization uploader
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Select Logo Image',
                    button: {
                        text: 'Select Image'
                    },
                    multiple: false
                });

                // After selecting the image, insert the URL into the field
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();

                    $('.logo-image-url-container').html('<input type="text" id="logo_image" name="logo_image" value="' + attachment.url + '" style="width:100%;" />');
                    $('#logo_image_preview').html('<img src="' + attachment.url + '" style="max-width: 250px; width: 100%;" />');
                });

                // Otwórz uploader
                mediaUploader.open();
            });
        });
    </script>
    <?php
}

function partners_gallery_callback($post) {
    wp_nonce_field('save_partners_gallery', 'partners_gallery_nonce');

    $partners_images = get_post_meta($post->ID, '_partners_gallery', true);

    if (!$partners_images) {
        $partners_images = array();
    }

    echo '
    <div class="pwe-calendar-input">
        <label for="partners_gallery">Select Partner Images:</label>
        <input type="hidden" id="partners_gallery" name="partners_gallery" value="' . esc_attr(implode(',', $partners_images)) . '" />
        <input type="button" class="button-secondary" value="Select Images" id="partners_gallery_button" />
    </div>';

    echo '<div id="partners_gallery_images">';
    if (!empty($partners_images)) {
        foreach ($partners_images as $image_url) {
            echo '<div class="partner-image" data-url="' . esc_attr($image_url) . '">';
            echo '<img src="' . esc_url($image_url) . '" style="width: 50px; margin-right: 10px;"/>';
            echo '<button class="remove-image-button">Remove</button>';
            echo '</div>';
        }
    }
    echo '</div>';

    ?>
    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('#partners_gallery_button').click(function(e) {
                e.preventDefault();

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                // Initialization uploader
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Select Partner Images',
                    button: {
                        text: 'Select Images'
                    },
                    multiple: true,
                    library: {
                        type: 'image'
                    }
                });

                // Pre-select already selected images in media uploader
                mediaUploader.on('open', function() {
                    var selection = mediaUploader.state().get('selection');
                    // Convert PHP to JSON to pass data to JS
                    var selected = <?php echo json_encode($partners_images); ?>;
                    
                    selected.forEach(function(url) {
                        var attachment = wp.media.attachment(url);
                        attachment.fetch();
                        selection.add(attachment);
                    });
                });

                // After selecting the image, insert the URL into the field
                mediaUploader.on('select', function() {
                    var selection = mediaUploader.state().get('selection');
                    var imageUrls = [];

                    // Adding image URL
                    selection.each(function(attachment) {
                        imageUrls.push(attachment.attributes.url); 
                    });

                    // Inserting the images URL into the hidden field and showing the thumbnail
                    $('#partners_gallery').val(imageUrls.join(','));
                    updateImagesPreview(imageUrls);
                });

                mediaUploader.open();
            });

            // Function to update thumbnail image preview
            function updateImagesPreview(imageUrls) {
                var previewDiv = $('#partners_gallery_images');
                previewDiv.empty(); // Clear current thumbnails

                // Adding thumbnail images
                imageUrls.forEach(function(url) {
                    previewDiv.append('<div class="partner-image" data-url="' + url + '"><img src="' + url + '" style="width: 50px; margin-right: 10px;"/><button class="remove-image-button">Remove</button></div>');
                });
            }

            // Deleting selected image
            $('#partners_gallery_images').on('click', '.remove-image-button', function() {
                var imageUrl = $(this).closest('.partner-image').data('url');
                var currentImages = $('#partners_gallery').val().split(',');

                // Removing an image from the list
                currentImages = currentImages.filter(function(item) {
                    return item !== imageUrl;
                });

                // Update the hidden field
                $('#partners_gallery').val(currentImages.join(','));

                // Remove the thumbnail of the image
                $(this).closest('.partner-image').remove();
            });
        });
    </script>
    <?php
}

// Function of saving data from metaboxes
function save_event_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (isset($_POST['event_desc_nonce']) && wp_verify_nonce($_POST['event_desc_nonce'], 'save_event_desc')) {
        update_post_meta($post_id, 'desc', sanitize_text_field($_POST['desc']));
        update_post_meta($post_id, 'short_desc', sanitize_text_field($_POST['short_desc']));
    }

    if (isset($_POST['event_links_nonce']) && wp_verify_nonce($_POST['event_links_nonce'], 'save_event_links')) {
        update_post_meta($post_id, 'web_page_link', sanitize_text_field($_POST['web_page_link']));
        update_post_meta($post_id, 'visitor_registration_link', sanitize_text_field($_POST['visitor_registration_link']));
        update_post_meta($post_id, 'exhibitor_registration_link', sanitize_text_field($_POST['exhibitor_registration_link']));
        update_post_meta($post_id, 'buy_ticket_link', sanitize_text_field($_POST['buy_ticket_link']));

        $target_blank = isset($_POST['web_page_link_target_blank']) ? true : false;
        update_post_meta($post_id, 'web_page_link_target_blank', $target_blank);
    }

    if (isset($_POST['event_dates_nonce']) && wp_verify_nonce($_POST['event_dates_nonce'], 'save_event_dates')) {
        update_post_meta($post_id, 'fair_date_start', sanitize_text_field($_POST['fair_date_start']));
        update_post_meta($post_id, 'fair_date_end', sanitize_text_field($_POST['fair_date_end']));
        update_post_meta($post_id, 'quarterly_date', sanitize_text_field($_POST['quarterly_date']));
    }

    if (isset($_POST['event_colors_nonce']) && wp_verify_nonce($_POST['event_colors_nonce'], 'save_event_colors')) {
        update_post_meta($post_id, 'main_color', sanitize_text_field($_POST['main_color']));
        update_post_meta($post_id, 'main2_color', sanitize_text_field($_POST['main2_color']));
    } 

    if (isset($_POST['event_statistics_nonce']) && wp_verify_nonce($_POST['event_statistics_nonce'], 'save_event_statistics')) {
        update_post_meta($post_id, 'visitors', sanitize_text_field($_POST['visitors']));
        update_post_meta($post_id, 'exhibitors', sanitize_text_field($_POST['exhibitors']));
        update_post_meta($post_id, 'countries', sanitize_text_field($_POST['countries']));
        update_post_meta($post_id, 'area', sanitize_text_field($_POST['area']));
    }

    if (isset($_POST['event_organizer_nonce']) && wp_verify_nonce($_POST['event_organizer_nonce'], 'save_event_organizer')) {
        update_post_meta($post_id, 'organizer_website', sanitize_text_field($_POST['organizer_website']));
        update_post_meta($post_id, 'organizer_email', sanitize_email($_POST['organizer_email']));
        update_post_meta($post_id, 'organizer_phone', sanitize_text_field($_POST['organizer_phone']));
        update_post_meta($post_id, 'organizer_name', sanitize_text_field($_POST['organizer_name']));
    }

    if (isset($_POST['event_other_nonce']) && wp_verify_nonce($_POST['event_other_nonce'], 'save_event_other')) {
        update_post_meta($post_id, 'edition', sanitize_text_field($_POST['edition']));
        update_post_meta($post_id, 'badge', sanitize_text_field($_POST['badge']));
        update_post_meta($post_id, 'keywords', sanitize_text_field($_POST['keywords']));
    }

    if (isset($_POST['header_image_nonce']) && wp_verify_nonce($_POST['header_image_nonce'], 'save_header_image')) {
        if (isset($_POST['header_image'])) {
            update_post_meta($post_id, '_header_image', sanitize_text_field($_POST['header_image']));
        }
    }

    if (isset($_POST['logo_image_nonce']) && wp_verify_nonce($_POST['logo_image_nonce'], 'save_logo_image')) {
        if (isset($_POST['logo_image'])) {
            update_post_meta($post_id, '_logo_image', sanitize_text_field($_POST['logo_image']));
        }
    }

    if (isset($_POST['partners_gallery_nonce']) && wp_verify_nonce($_POST['partners_gallery_nonce'], 'save_partners_gallery')) {
        if (isset($_POST['partners_gallery'])) {
            // Separating image URLs written in a string (separated by commas) and saving as an array
            $image_urls = explode(',', sanitize_text_field($_POST['partners_gallery']));
            update_post_meta($post_id, '_partners_gallery', $image_urls);
        }
    }

    if (isset($_POST['featured_image_nonce']) && wp_verify_nonce($_POST['featured_image_nonce'], 'save_featured_image')) {
        if (isset($_POST['featured_image_url'])) {
            update_post_meta($post_id, '_featured_image_url', sanitize_text_field($_POST['featured_image_url']));
        }
    }
    
    if (isset($_POST['secondary_image_nonce']) && wp_verify_nonce($_POST['secondary_image_nonce'], 'save_secondary_image')) {
        if (isset($_POST['secondary_image_url'])) {
            update_post_meta($post_id, '_secondary_image_url', sanitize_text_field($_POST['secondary_image_url']));
        }
    }
}
add_action('save_post', 'save_event_meta');

// Hide secondary thumbnail meta box
function hide_secondary_thumbnail_meta_box() {
    echo '<style>.post-type-event #uncode-secondary-featured-image { display: none; }</style>';
}
add_action('admin_head', 'hide_secondary_thumbnail_meta_box');

function load_datepicker_styles() {
    ?>
    <style>
        /* Customize styles for jQuery UI Datepicker */
        .ui-datepicker {
            background: #fff !important;
            border: 1px solid #ccc !important;
            z-index: 9999 !important;
        }
        .ui-datepicker-header {
            background: #f1f1f1 !important;
            color: #333 !important;
        }
        .ui-datepicker td, .ui-datepicker th {
            color: #333 !important;
        }
        .ui-datepicker .ui-state-highlight {
            background: #5cb85c !important;
            color: white !important;
        }
        .ui-datepicker .ui-state-active {
            background: #0275d8 !important;
            color: white !important;
        }
        .ui-datepicker .ui-state-hover {
            background: #d9534f !important;
            color: white !important;
        }
        .ui-datepicker .ui-datepicker-prev, 
        .ui-datepicker .ui-datepicker-next {
            width: auto;
        }
        .ui-datepicker .ui-datepicker-prev-hover, 
        .ui-datepicker .ui-datepicker-next-hover {
            background: inherit !important;
            color: red !important;
        }
        .ui-datepicker .ui-datepicker-prev span, 
        .ui-datepicker .ui-datepicker-next span {
            position: static;
            margin-left: 0;
            margin-top: 3px;
        }
        .ui-icon {
            text-indent: 0 !important; 
            cursor: pointer;
        }
    </style>
    <?php
}
add_action('admin_head', 'load_datepicker_styles');

// Loading jQuery UI Datepicker
function load_datepicker_scripts($hook) {
    // Check if we're on the post editing page and it's the 'event' post type
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    // Make sure we're on the 'event' post type page
    if ('event' !== get_post_type()) {
        return;
    }

    // Enqueue jQuery (it may already be loaded, but this ensures it's loaded)
    wp_enqueue_script('jquery');

    // Enqueue jQuery UI Datepicker
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' ); 

    // Add inline script to initialize Datepicker on specific inputs
    wp_add_inline_script('jquery-ui-datepicker', "
        jQuery(document).ready(function($) {
            // Initialize Datepicker for fair date inputs
            $('#fair_date_start, #fair_date_end').datepicker({
                dateFormat: 'dd-mm-yy'
            });
        });
    ");
}
add_action('admin_enqueue_scripts', 'load_datepicker_scripts');

// Loading scripts and styles for color picker in the admin panel
function load_color_picker_script($hook) {
    // Checking if this is an 'event' post editing page
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    // Load scripts and styles for color picker
    wp_enqueue_style('wp-color-picker'); 
    wp_enqueue_script('wp-color-picker');

    // Add jQuery if not already loaded
    wp_enqueue_script('jquery');

    // Loading a script that will depend on jQuery and wp-color-picker
    wp_add_inline_script('wp-color-picker', "
        jQuery(document).ready(function($) {
            $('.color-picker').wpColorPicker();
        });
    ");
}
add_action('admin_enqueue_scripts', 'load_color_picker_script');

// Function to load custom styles in the admin panel
function load_admin_styles($hook) {
    // Checking if this is an 'event' post editing page
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    // Load styles in the admin panel
    wp_enqueue_style('calendar-admin-style', false);
    ?>
    <style>
        .pwe-calendar-inputs-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 10px;
        }
        .pwe-calendar-input {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 6px;
        }
        .pwe-calendar-input.checkbox {
            flex-direction: row-reverse;
            justify-content: center;
            align-items: center;
        }
        .pwe-calendar-input.checkbox input {
            width: 25px !important;
            height: 25px !important;
        }
        .pwe-calendar-input.checkbox input:before {
            margin: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        .pwe-calendar-input.one-seventh-width {
            width: 66%;
        }
        .pwe-calendar-input.half-width {
            width: 49%;
        }
        .pwe-calendar-input.one-third-width {
            width: 32%;
        }
        /* Stylowanie inputów */
        .pwe-calendar-full-width-input {
            width: 100%; /* Ustawiamy szerokość na 100% */
            padding: 8px;
            font-size: 14px;
            box-sizing: border-box; /* Upewniamy się, że padding nie wpłynie na szerokość */
        }
        #partners_gallery_images {
            margin-top: 10px;
        }


        .header-slider-button {
            margin-top: 10px;
            text-align: end; 
        }
        .add-header-slider {
            margin: 0 0 18px 18px;
        }
    </style>
    <?php
}
add_action('admin_enqueue_scripts', 'load_admin_styles');

// Function to add content editor at the bottom of the form
function move_content_editor_to_bottom() {
    if (get_post_type() != 'event' && get_post_type() != 'events_week') {
        return;
    }
    // Adding content editor after all metaboxes
    add_action('edit_form_after_editor', 'add_content_editor_to_bottom');
}
add_action('do_meta_boxes', 'move_content_editor_to_bottom');

// Function adding content editor
function add_content_editor_to_bottom() {
    global $post;
    
    if ($post->post_type == 'event') {
        wp_editor( 
            $post->post_content, 
            'content', 
            array(
                'textarea_name' => 'content', 
                'editor_height' => 200
            ) 
        );
    }
}

// Displaying the fair calendar
require_once plugin_dir_path(__FILE__) . 'classes/loop-calendar.php';