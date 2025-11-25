<?php 

/**
 * Class PWEProfileTabs
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWEProfileTabs extends PWEProfile {

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
                'param_name' => 'profile_tabs_iframe',
                'save_always' => true,
                'param_holder_class' => 'backend-textarea-raw-html',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileTabs', 
                ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Items', 'pwe_profile'),
                'param_name' => 'profile_tabs_items',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileTabs', 
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
                        'param_name' => 'profile_tabs_items_icons',
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
            'profile_tabs_iframe' => '',
            'profile_tabs_items' => '',
        ), $atts ));

        $profile_tabs_items_urldecode = urldecode($profile_tabs_items);
        $profile_tabs_items_json = json_decode($profile_tabs_items_urldecode, true);

        $lighter_accent_color = self::adjustBrightness(self::$accent_color, +20);
        $light_accent_color = self::adjustBrightness(self::$accent_color, +40);

        $profile_iframe_code = self::decode_clean_content($profile_tabs_iframe);

        if (!empty($profile_tabs_iframe)) {
            // Extract src from iframe
            preg_match('/src="([^"]+)"/', $profile_iframe_code, $match);
            $src = $match[1];

            // Extract the video ID from the URL
            preg_match('/embed\/([^?]+)/', $src, $match);
            $video_id = $match[1];
        } else {
            $video_id = 'R0Ckz1dVxoQ';  
        }

        $profile_tabs_iframe = '<iframe data-src="https://www.youtube.com/embed/'. $video_id .'?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&playlist='. $video_id .'"
                            title="YouTube video player" frameborder="0" marginwidth="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; muted">
                            </iframe>';

        $output = '
        <style>
            .row-container:has(.profile-tabs .pwe-profiles) {
                padding: 0 !important;
            }
            .row-parent:has(.profile-tabs .pwe-profiles) {
                max-width: 100%;
                padding: 0 !important;
            }
            .profile-tabs .pwe-profiles {
                display: flex;
                justify-content: center;
            }
            .profile-tabs .pwe-profiles__wrapper {
                display: flex;
                justify-content: space-around;
                align-items: center;
                max-width: 100%;
                width: 100%;
                margin: 36px;
                gap: 36px;
                border-radius: 40px;
            }
            .profile-tabs .pwe-profiles__iframe {
                width: 40%;
                display: flex;
                align-items: center;
            }   
            .profile-tabs .pwe-profiles__iframe iframe,
            .profile-tabs .pwe-profiles__iframe .rll-youtube-player {
                aspect-ratio: 16 / 9;
                box-shadow: none;
                border-radius: 18px;
                width: 100%;
            }
            .profile-tabs .pwe-profiles__iframe iframe {  
                pointer-events: none;
            }
            .profile-tabs .pwe-profiles__content {
                margin-top: 36px;
                width: 60%;
            }
            .profile-tabs .pwe-profiles__tabs-items  {
                justify-content: space-around;
                overflow: visible;
            }
            .profile-tabs .pwe-profiles__tabs-content {
                background-color: white;
                border-radius: 30px;
                z-index: 10;
                position: relative;
            }
            .profile-tabs .pwe-profiles__tab {
                width: 100%;
                position: relative;
                top: -51px;
                filter: url("#fancy-goo");
                transition: .3s ease;
                cursor: pointer;
            }
            .profile-tabs .pwe-profiles__tab .pwe-profiles__tab-head {
                display: flex;
                justify-content: center;
                align-items: center;
                min-width: 33.33%;
                white-space: nowrap;
                text-align: center;
                height: 50px;
                position: absolute;
                padding: 8px;
                z-index: 2;
                gap: 10px;
            }
            .profile-tabs .pwe-profiles__tabs-items .pwe-profiles__tab:first-child .pwe-profiles__tab-head {
                left: 0px;
            }
            .profile-tabs .pwe-profiles__tabs-items .pwe-profiles__tab:last-child .pwe-profiles__tab-head {
                right: 0px;
            }
            .profile-tabs .pwe-profiles__tab-title {
                font-size: 16px;
            }
            .profile-tabs .pwe-profiles__tab-text {
                width: 100%;
                padding: 36px;
            }  
            .profile-tabs .pwe-profiles__tab-text ul {
                margin: 0;
            } 
            .pwe-profiles__tab-head-bottom {
                width: 100%;
                height: 50px;
                position: absolute;
                top: 50px;
                z-index: 5;
            }
            .profile-tabs svg {
                display: none;
            }
            .tab-profile_scope {
                z-index: 7;
            }
            .tab-profile_visitors {
                z-index: 6;
            }
            .tab-profile_exhibitors {
                z-index: 5;
            }
            #profile_scope.pwe-profiles__tab-content,
            .tab-profile_scope .pwe-profiles__tab-head,
            .tab-profile_scope .pwe-profiles__tab-head-bottom {
                background-color: '. self::$accent_color .';
            }
            #profile_visitors.pwe-profiles__tab-content,
            .tab-profile_visitors .pwe-profiles__tab-head,
            .tab-profile_visitors .pwe-profiles__tab-head-bottom {
                background-color: '. $lighter_accent_color .';
            }
            #profile_exhibitors.pwe-profiles__tab-content,
            .tab-profile_exhibitors .pwe-profiles__tab-head,
            .tab-profile_exhibitors .pwe-profiles__tab-head-bottom {
                background-color: '. $light_accent_color .';
            }
            .profile-tabs .pwe-profiles__tabs-items .pwe-profiles__tab.active {
                z-index: 10;
            }
            
            .profile-tabs .pwe-profiles__tabs-items .pwe-profiles__tab img {
                width: 30px;
                border-radius: 10px;
            }
            .profile-tabs .pwe-profiles__tab-title {
                margin: 0;
                color: white;
            }
            .profile-tabs .pwe-profiles__tab-content {
                opacity: 0;
                visibility: hidden;
                height: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
                position: relative;
	            z-index: 9;
                border-radius: 14px;
                color: white;
            }
            .pwe-profiles__tab-content.active {
                opacity: 1;
                visibility: visible;
                height: auto;
                pointer-events: auto;
            }


            .profile-tabs .pwe-profiles__item-icons {
                display: flex;
                justify-content: space-around;
                flex-wrap: wrap;
                gap: 18px;
            }
            .profile-tabs .pwe-profiles__item-icon {
                display: flex;
                flex-direction: column;
                width: 30%;
                align-items: center;
                gap: 18px;
            }
            .profile-tabs .pwe-profiles__item-icon img {
                background-color: '. self::$main2_color .';
                border-radius: 50%;
                padding: 10px;
                max-width: 120px;
            }
            .profile-tabs .pwe-profiles__item-icon span {
                text-transform: uppercase;
                font-weight: 600;
                font-size: 13px;
                text-align: center;
                width: 100%;
                max-width: 200px;
            }


            @media (max-width: 1250px) {
                .profile-tabs .pwe-profiles__wrapper {
                    flex-direction: column-reverse;
                }
                .profile-tabs .pwe-profiles__content {
                    width: 100%;
                }
                .profile-tabs .pwe-profiles__iframe {
                    width: 100%;

                }
            }
            @media (max-width: 768px) {
                .profile-tabs .pwe-profiles__tab-text {
                    padding: 18px;
                }
                .profile-tabs .pwe-profiles__tab-text ul li {
                    font-size: 14px;
                }
                .profile-tabs .pwe-profiles__tab-head {
                    max-width: 100px;
                }
                .profile-tabs .pwe-profiles__tab-title {
                    font-size: 14px;
                    white-space: break-spaces !important;
                }
            }   
            @media (min-width: 600px) {
                .profile-tabs .pwe-profiles__tabs-items .pwe-profiles__tab:not(.active):hover {
                    top: -60px;
                }
            }  
            @media (max-width: 600px) {
                .profile-tabs .pwe-profiles__tab-head img {
                    display: none;
                }
                .profile-tabs .pwe-profiles__tab-title {
                    font-size: 12px;
                    white-space: break-spaces !important;
                }
            }
            
        </style>';
    
        $output .= '
        <div class="pwe-profiles">
            <div class="pwe-profiles__wrapper">
                <div class="pwe-profiles__iframe">'. $profile_tabs_iframe .'</div>
                <div class="pwe-profiles__content">
                    <div class="pwe-profiles__tabs-items">';
    
                        if (is_array($profile_tabs_items_json)) {
                            foreach ($profile_tabs_items_json as $profile_item) {
                                $profile_icon_nmb = $profile_item["profile_icon"];
                                $profile_icon_src = wp_get_attachment_url($profile_icon_nmb);  
                        
                                $profile_title_select = $profile_item["profile_title_select"];
                                $profile_title_custom = $profile_item["profile_title_custom"];
                                $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom; 
                                
                                $profile_id = strtolower(str_replace('_title', '', $profile_title));

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

                                $active = ($profile_title == 'profile_title_scope') ? 'active' : '';
                                
                                $output .= '
                                <div class="pwe-profiles__tab pwe-profiles__item tab-'. $profile_id .' '. $active .'" onclick="openTab(event, \''. $profile_id .'\')">

                                        <span class="pwe-profiles__tab-head">
                                            <img src="'. $profile_icon_src .'">
                                            <h5 class="pwe-profiles__tab-title">'. $profile_title .'</h5>
                                        </span>
                                        <div class="pwe-profiles__tab-head-bottom"></div>

                                </div>';
                            }
                        }
    
                    $output .= '
                    </div>

                    <div class="pwe-profiles__tabs-content">';

                        foreach ($profile_tabs_items_json as $profile_item) {
                            $profile_text = $profile_item["profile_text"];
                            $profile_title_select = $profile_item["profile_title_select"];
                            $profile_title_custom = $profile_item["profile_title_custom"];
                            $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom;
                            
                            $profile_id = strtolower(str_replace('_title', '', $profile_title));

                            $profile_items_icons = $profile_item["profile_tabs_items_icons"];
                            $profile_items_icons_urldecode = urldecode($profile_items_icons);
                            $profile_items_icons_json = json_decode($profile_items_icons_urldecode, true);
                        
                            $profile_text_content = self::decode_clean_content($profile_text);

                            $output .= '
                            <div id="'. $profile_id .'" class="pwe-profiles__tab-content tab-content-'.self::id_rnd().'">
                                <div class="pwe-profiles__tab-text">';
                                if (empty($profile_text_content)) {
                                    $output .= '<div class="pwe-profiles__item-icons">';
                                    foreach ($profile_items_icons_json as $profile_icon) {
                                        $profile_item_icon = $profile_icon["profile_item_icon"];
                                        $profile_item_title = $profile_icon["profile_item_title"];
                                        $profile_item_icon_src = wp_get_attachment_url($profile_item_icon);  
                                        
                                        $output .= '
                                        <div class="pwe-profiles__item-icon">
                                            <img src="'. $profile_item_icon_src .'" />
                                            <span>'. $profile_item_title .'</span>
                                        </div>';
                                    }
                                    $output .= '</div>';
                                } else {
                                    $output .= $profile_text_content;
                                }
                                $output .= '   
                                </div>
                            </div>';
                        }
                    
                    $output .= '
                    </div>
                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                <defs>
                    <filter id="fancy-goo">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="9" result="blur" />
                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                        <feComposite in="SourceGraphic" in2="goo" operator="atop" />
                    </filter>
                </defs>
            </svg>
        </div>';
        
        $output .= '
        <script>
            // Function to open the selected tab
            function openTab(evt, name) {
                var i, tabcontent, tab;
                tabcontent = document.getElementsByClassName("pwe-profiles__tab-content");

                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].classList.remove("active"); // Usuń klasę active, aby ukryć zawartość
                }

                tab = document.getElementsByClassName("pwe-profiles__tab");
                for (i = 0; i < tab.length; i++) {
                    tab[i].className = tab[i].className.replace(" active", "");
                }

                // Dodaj klasę active do aktywnego taba
                var activeTab = document.getElementById(name);
                activeTab.classList.add("active");
                evt.currentTarget.className += " active"; // Dodaj klasę active do klikniętej zakładki
            }

            // Automatically open the first tab on page load
            document.addEventListener("DOMContentLoaded", function() {
                var firsttab = document.querySelector(".pwe-profiles__tab");
                var firstTabContent = document.querySelector(".pwe-profiles__tab-content");

                // Ensure the first tab and its content are displayed and marked as active
                if (firsttab && firstTabContent) {
                    firsttab.classList.add("active");
                    firstTabContent.classList.add("active");
                }        
            });


            const tabHeads = document.querySelectorAll(".pwe-profiles__tab-head");

            function updateTabPositions() {
                let previousWidth = 0;

                tabHeads.forEach((tab) => {
                    // Ustaw "left" dla każdego elementu
                    tab.style.left = `${previousWidth}px`;

                    // Zaktualizuj szerokość dla następnego elementu
                    previousWidth += tab.offsetWidth;
                });
            }

            // Wywołaj funkcję przy pierwszym załadowaniu strony
            updateTabPositions();

            // Nasłuchuj zmian rozmiaru okna
            window.addEventListener("resize", updateTabPositions);

        </script>';
    
        
        return $output;
    }
}