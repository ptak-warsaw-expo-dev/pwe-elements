<?php

/**
 * Class PWEProfileThreeCols
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWEProfileThreeCols extends PWEProfile {

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
                'type' => 'textarea_raw_html',
                'heading' => __('Iframe', 'pwe_profile'),
                'param_name' => 'profile_threecols_iframe',
                'save_always' => true,
                'param_holder_class' => 'backend-textarea-raw-html',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileThreeCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom scope color', 'pwe_profile'),
                'param_name' => 'profile_color_scope_custom',
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileThreeCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom exhibitor color', 'pwe_profile'),
                'param_name' => 'profile_color_exhibitor_custom',
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileThreeCols',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom visitor color', 'pwe_profile'),
                'param_name' => 'profile_color_visitor_custom',
                'param_holder_class' => 'backend-area-one-third-width',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileThreeCols',
                ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Items', 'pwe_profile'),
                'param_name' => 'profile_threecols_items',
                'param_holder_class' => 'backend-textarea-raw-html',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileThreeCols',
                ),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Title', 'pwe_profile'),
                        'param_name' => 'profile_title_select',
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => array(
                            'Custom' => '',
                            'PROFIL ODWIEDZAJĄCEGO' => 'profile_title_visitors',
                            'PROFIL WYSTAWCY' => 'profile_title_exhibitors',
                            'ZAKRES BRANŻOWY' => 'profile_title_scope',
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Custom title', 'pwe_profile'),
                        'param_name' => 'profile_title_custom',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Icon', 'pwe_profile'),
                        'param_name' => 'profile_icon',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea_raw_html',
                        'heading' => __('Text', 'pwe_profile'),
                        'param_name' => 'profile_text',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'param_group',
                        'heading' => __('Items icons', 'pwe_profile'),
                        'param_name' => 'profile_threecols_items_icons',
                        'params' => array(
                            array(
                                'type' => 'attach_image',
                                'heading' => __('Icon', 'pwe_profile'),
                                'param_name' => 'profile_item_icon',
                                'save_always' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => __('Title', 'pwe_profile'),
                                'param_name' => 'profile_item_title',
                                'save_always' => true,
                                'admin_label' => true,
                            ),
                        ),
                    ),
                ),
            ),
        );

        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {

        extract( shortcode_atts( array(
            'profile_threecols_iframe' => '',
            'profile_threecols_items' => '',
            'profile_color_scope_custom' => '',
            'profile_color_exhibitor_custom' => '',
            'profile_color_visitor_custom' => '',
        ), $atts ));

        $profile_threecols_items_urldecode = urldecode($profile_threecols_items);
        $profile_threecols_items_json = json_decode($profile_threecols_items_urldecode, true);

        $lighter_accent_color = self::adjustBrightness(self::$accent_color, +20);
        $light_accent_color = self::adjustBrightness(self::$accent_color, +40);

        $profile_iframe_code = self::decode_clean_content($profile_threecols_iframe);

        if (!empty($profile_threecols_iframe)) {
            // Extract src from iframe
            preg_match('/src="([^"]+)"/', $profile_iframe_code, $match);
            $src = $match[1];

            // Extract the video ID from the URL
            preg_match('/embed\/([^?]+)/', $src, $match);
            $video_id = $match[1];
        } else {
            $video_id = 'R0Ckz1dVxoQ';
        }
        $profile_color_scope_custom = !empty($profile_color_scope_custom) ? $profile_color_scope_custom : '#002b46';
        $profile_color_exhibitor_custom = !empty($profile_color_exhibitor_custom) ? $profile_color_exhibitor_custom : '#1573a3';
        $profile_color_visitor_custom = !empty($profile_color_visitor_custom) ? $profile_color_visitor_custom : '#49c2cb';

        $output = '
<style>
    /* Color */
    #PWEProfileThreeCols #column-profile_scope {
        background-color: '. $profile_color_scope_custom .';
        border:2px solid '. $profile_color_scope_custom .';
    }

    #PWEProfileThreeCols #column-profile_exhibitors {
        background-color: '. $profile_color_exhibitor_custom .';
        border:2px solid '. $profile_color_exhibitor_custom .';
    }

    #PWEProfileThreeCols #column-profile_visitors {
        background-color:'. $profile_color_visitor_custom .';
        border:2px solid '. $profile_color_visitor_custom .';
    }
    #PWEProfileThreeCols .column.large {
        background-color: white !important;
    }
    #PWEProfileThreeCols .small h2 {
        color:white;
    }
    #PWEProfileThreeCols .container {
        display: grid;
        grid-template-columns: 80% 10% 10%;
        height: 450px;
        transition: grid-template-columns 0.5s ease;
    }

    #PWEProfileThreeCols .column {
        background-color: #f0f0f0;
        border: 2px solid #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: background-color 0.5s ease;
        cursor: pointer;
        border-radius: 20px;
        position: relative;
    }

    #PWEProfileThreeCols .column:hover {
        background-color: #e0e0e0;
    }

    #PWEProfileThreeCols .content {
        transition: transform 0.5s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
        flex-direction: column;
    }

    #PWEProfileThreeCols .column h2 {
        font-size: 1.5rem;
        margin: 20px 0;
        white-space: nowrap;
        transition: transform 0.5s ease;
    }

    #PWEProfileThreeCols .column.small h2 {
        transform: rotate(-90deg);
    }

    #PWEProfileThreeCols .column.large h2 {
        transform: rotate(0);
    }

    /* Kontener na treść wewnątrz kolumny */
    #PWEProfileThreeCols .profile-content {
        display: none;
        width: 100%;
        padding: 20px;
        opacity: 0;
        transition: opacity 1s ease;
    }
    #PWEProfileThreeCols .profile-content.opacity-1 {
        opacity: 1;
    }

    #PWEProfileThreeCols .column.large .profile-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap:10px;
    }

    #PWEProfileThreeCols .profile-content-element {
        width: 30%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #PWEProfileThreeCols .profile-content-element img {
        max-width:80px;
    }
    #PWEProfileThreeCols .profile-content-element p {
        line-height: 1.2;
        margin-top: 0px;
    }
    #PWEProfileThreeCols .profile-threecols h2 {
        text-transform: uppercase !important;;
    }
    #PWEProfileThreeCols .profile-content-element p {
        text-transform: uppercase;
        font-weight: 500;
        padding-top: 8px;
    }
    @media(max-width: 450px) {
        #PWEProfileThreeCols .profile-threecols h2 {
            font-size: 16px !important;
        }
        #PWEProfileThreeCols .profile-content-element p {
            padding-top:4px;
        }
    }
    @media(max-width:620px){
        #PWEProfileThreeCols {
            display: flex;
            justify-content: center;
            margin: 0px auto;
            min-height: 850px;
            align-items: center;
        }
        #PWEProfileThreeCols .container {
            transform: rotate(90deg);
            min-width: 700px;
            min-height: 370px;
            height: 370px;
        }
        #PWEProfileThreeCols .content {
            transform: rotate(-90deg);
        }
        #PWEProfileThreeCols  .profile-content {
            gap: 0px !important;
            padding: 5px;
        }
        #PWEProfileThreeCols .column.large .profile-content {
            justify-content: center;
        }
        #PWEProfileThreeCols .profile-content-element {
            width: 185px;
        }
        #PWEProfileThreeCols .profile-content-element p {
            max-width: 90%;
            margin: 0 auto;
            font-size: 10px;
        }
        #PWEProfileThreeCols .column.small h2 {
            transform: none;
        }
    }
    @media(max-width:960px){
        .container {
            height: 500px;
        }
        .profile-content-element {
            width: 47%;
        }
        .profile-content-element p {
            font-size: 12px;
        }
        .container {
            height: 600px;
        }
    }
</style>';

$output .= '<div class="container container-' . self::$rnd_id . '">';

if (is_array($profile_threecols_items_json)) {
    foreach ($profile_threecols_items_json as $profile_item) {
        $profile_icon_nmb = $profile_item["profile_icon"];
        $profile_icon_src = wp_get_attachment_url($profile_icon_nmb);

        $profile_title_select = $profile_item["profile_title_select"];
        $profile_title_custom = $profile_item["profile_title_custom"];
        $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom;

        // Określamy ID kolumny na podstawie tytułu
        $profile_id = strtolower(str_replace('_title', '', $profile_title));

        // Przypisujemy tytuł i ikony do kolumny
        if ($profile_title == 'profile_title_visitors') {
            $profile_title = (get_locale() == 'pl_PL') ? "Profil odwiedzającego" : "Visitor profile";
            $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/visitor_profile_icon_white.webp';
        } else if ($profile_title == 'profile_title_exhibitors') {
            $profile_title = (get_locale() == 'pl_PL') ? "Profil wystawcy" : "Exhibitor profile";
            $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/exhibitor_profile_icon_white.webp';
        } else if ($profile_title == 'profile_title_scope') {
            $profile_title = (get_locale() == 'pl_PL') ? "Zakres branżowy" : "Industry scope";
            $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/industry_scope_icon_white.webp';
        }

        // Pobieramy treść kolumny
        $profile_text = $profile_item["profile_text"];
        $profile_text_content = self::decode_clean_content($profile_text);

        // Przygotowujemy treść elementów dla ikonek (jeśli dostępna)
        $profile_items_icons = $profile_item["profile_threecols_items_icons"];
        $profile_items_icons_urldecode = urldecode($profile_items_icons);
        $profile_items_icons_json = json_decode($profile_items_icons_urldecode, true);

        // Ustawiamy klasę dla kolumny (początkowa kolumna "Zakres branżowy" jest "large", reszta "small")
        $is_active = ($profile_title == 'Zakres branżowy' || $profile_title == 'Industry scope') ? 'large' : 'small';
        $is_opacity1 = ($profile_title == 'Zakres branżowy' || $profile_title == 'Industry scope') ? 'opacity-1' : '';

        // Generujemy kod HTML kolumny
        $output .= '
        <div class="column ' . $is_active . '" id="column-' . $profile_id . '" onclick="changeLayout(\'' . $profile_id . '\')">
            <div class="content">
                <h2>' . $profile_title . '</h2>
                <div class="profile-content '. $is_opacity1 .'">';

        // Wstawiamy odpowiednią treść do kolumny
        if (empty($profile_text_content)) {
            foreach ($profile_items_icons_json as $profile_icon) {
                $profile_item_icon = $profile_icon["profile_item_icon"];
                $profile_item_title = $profile_icon["profile_item_title"];
                $profile_item_icon_src = wp_get_attachment_url($profile_item_icon);

                $output .= '
                <div class="profile-content-element">
                    <img src="' . $profile_item_icon_src . '" alt="' . $profile_item_title . '"/>
                    <p>' . $profile_item_title . '</p>
                </div>';
            }
        } else {
            $output .= $profile_text_content;
        }

        $output .= '
                </div>
            </div>
        </div>';
    }
}

$output .= '</div>';

$output .= '
<script>
function changeLayout(activeColumnId) {
    const container = document.querySelector(".container");
    const columns = document.querySelectorAll(".column");

    // Reset all columns
    columns.forEach((col) => {
        col.classList.remove("large", "small");
        const content = col.querySelector(".profile-content");
        if (content) {
            content.classList.remove("opacity-1"); // Usuń klasę opacity-1 z wszystkich
        }
    });


    // Update grid layout and classes based on clicked column
    if (activeColumnId === "profile_scope") {
        container.style.gridTemplateColumns = "80% 10% 10%";
        const column = document.getElementById("column-profile_scope");
        column.classList.add("large");
        document.getElementById("column-profile_exhibitors").classList.add("small");
        document.getElementById("column-profile_visitors").classList.add("small");

        setTimeout(() => {
            const content = column.querySelector(".profile-content");
            if (content) {
                content.classList.add("opacity-1");
            }
        }, 300);
    } else if (activeColumnId === "profile_exhibitors") {
        container.style.gridTemplateColumns = "10% 80% 10%";
        const column = document.getElementById("column-profile_exhibitors");
        column.classList.add("large");
        document.getElementById("column-profile_scope").classList.add("small");
        document.getElementById("column-profile_visitors").classList.add("small");

        setTimeout(() => {
            const content = column.querySelector(".profile-content");
            if (content) {
                content.classList.add("opacity-1");
            }
        }, 300);
    } else if (activeColumnId === "profile_visitors") {
        container.style.gridTemplateColumns = "10% 10% 80%";
        const column = document.getElementById("column-profile_visitors");
        column.classList.add("large");
        document.getElementById("column-profile_scope").classList.add("small");
        document.getElementById("column-profile_exhibitors").classList.add("small");

        setTimeout(() => {
            const content = column.querySelector(".profile-content");
            if (content) {
                content.classList.add("opacity-1");
            }
        }, 300);
    }
}
</script>';


        return $output;
    }
}