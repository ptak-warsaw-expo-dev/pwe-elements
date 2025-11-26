<?php 

/**
 * Class PWEProfileAllInOne
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWEProfileAllInOne extends PWEProfile {

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
                'param_name' => 'profile_iframe',
                'save_always' => true,
                'param_holder_class' => 'backend-textarea-raw-html',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileAllInOne', 
                ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Items', 'pwe_profile'),
                'param_name' => 'profile_items',
                'dependency' => array(
                    'element' => 'profile_type',
                    'value' => 'PWEProfileAllInOne', 
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
                        'heading' => __('Profile short description', 'pwe_profile'),
                        'param_name' => 'profile_desc',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea_raw_html',
                        'heading' => __('Text', 'pwe_profile'),
                        'param_name' => 'profile_text',
                        'param_holder_class' => 'backend-textarea-raw-html',
                        'save_always' => true,
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
            'profile_iframe' => '',
            'profile_items' => '',
        ), $atts ));

        $profile_items_urldecode = urldecode($profile_items);
        $profile_items_json = json_decode($profile_items_urldecode, true);

        $output = '
        <style>
            .row-container:has(.profile-all-in-one .pwe-profiles) {
                padding: 0 !important;
            }
            .row-parent:has(.profile-all-in-one .pwe-profiles) {
                max-width: 100%;
                padding: 0 !important;
            }
            .profile-all-in-one .pwe-profiles__wrapper {
                display: flex;
                justify-content: space-around;
                align-items: center;
                max-width: 100%;
                margin: 0 auto;
                padding: 18px 36px;
                gap: 36px;
            }
            .profile-all-in-one .pwe-profiles__iframe {
                width: 50%;
                max-width: 600px;
                display: flex;
                align-items: center;
            }
            .profile-all-in-one .pwe-profiles__iframe iframe,
            .profile-all-in-one .pwe-profiles__iframe .rll-youtube-player {
                aspect-ratio: 16 / 9;
                box-shadow: none;
                border-radius: 18px;
                width: 100%;
            }
            .profile-all-in-one .pwe-profiles__iframe iframe {
                pointer-events: none;
            }
            .profile-all-in-one .pwe-profiles__content {
                display: flex;
                flex-direction: column;
                gap: 10px;
                width: 50%;
                max-width: 800px;
            }
            .profile-all-in-one .pwe-profiles__title {
                width: 100%;
                text-align: center;
                padding: 24px 36px 18px;
            }
            .profile-all-in-one .pwe-profiles__title p {
                font-size: 20px;
                margin: 0;
                display: inline-block;
                line-height: 1.2;
            }
            .profile-all-in-one .pwe-profiles__title span {
                font-size: 30px !important;
            }
            .profile-all-in-one .pwe-profiles__items {
                display: flex;
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
            .profile-all-in-one .pwe-profiles__item {
                position: relative;
                background-color: #eaeaea;
                padding: 8px;
                border-radius: 18px;
            }
            .profile-all-in-one .pwe-profiles__accordion {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 18px;
            }
            .profile-all-in-one .pwe-profiles__title h3 {
                font-size: 30px;
            }
            .profile-all-in-one .pwe-profiles__title span {
                font-size: 20px;
                font-weight: 500;
            }
            .profile-all-in-one .pwe-profiles__accordion {
                cursor: pointer;
            }
            .profile-all-in-one .pwe-profiles__accordion::after {
                content: "›";
                position: absolute;
                transform: rotate(0);
                transition: transform 200ms ease-out;
                width: 20px;
                height: 20px;
                align-self: flex-end;
                font-size: 30px;
                font-weight: 700;
                bottom: 15px;
                right: 10px;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .profile-all-in-one .pwe-profiles__accordion.active::after {
                transform: rotate(90deg);
            }
            .profile-all-in-one .pwe-profiles__accordion-hidden {
                height: 0;
                overflow: hidden;
                transition: height 0.3s ease-out;
            }
            .profile-all-in-one .pwe-profiles__accordion-hidden.active {
                height: auto;
            }
            .profile-all-in-one .pwe-profiles__accordion-icon {
                width: 50px;
                min-width: 50px;
            }
            .profile-all-in-one .pwe-profiles__accordion-icon img {
                background-color: '. self::$accent_color .';
            }
            .profile-all-in-one .pwe-profiles__accordion-text {
                width: 100%;
                padding-right: 24px;
            }
            .profile-all-in-one .pwe-profiles__accordion-title {
                margin: 0;
                text-transform: uppercase;
            }
            .profile-all-in-one .pwe-profiles__accordion-hidden {
                padding-right: 24px;
            }
            .profile-all-in-one .pwe-profiles__accordion-desc,
            .profile-all-in-one .pwe-profiles__accordion-desc p,
            .profile-all-in-one .pwe-profiles__accordion-hidden p {
                margin: 0;
                line-height: 1.3;
            }
            @media (max-width: 960px) {
                .profile-all-in-one .pwe-profiles__wrapper {
                    flex-direction: column;
                }
                .profile-all-in-one .pwe-profiles__content,
                .profile-all-in-one .pwe-profiles__iframe {
                    width: 100%;
                }
            }
        </style>';

        $profile_iframe_code = self::decode_clean_content($profile_iframe);

        if (!empty($profile_iframe)) {
            // Extract src from iframe
            preg_match('/src="([^"]+)"/', $profile_iframe_code, $match);
            $src = $match[1];

            // Extract the video ID from the URL
            preg_match('/embed\/([^?]+)/', $src, $match);
            $video_id = $match[1];
        } else {
            $video_id = 'R0Ckz1dVxoQ'; 
        }

        $profile_iframe = '<iframe src="https://www.youtube.com/embed/'. $video_id .'?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&playlist='. $video_id .'"
                            title="YouTube video player" frameborder="0" marginwidth="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; muted">
                            </iframe>';
                                            
        $output .= '
        <div class="pwe-profiles">
            <div class="pwe-profiles__title">
                <p>'. self::languageChecker('<span>Odkryj,</span> dla kogo jest to wydarzenie i jakie firmy oraz technologie będą obecne!', '<span>Discover</span> who this event is for and what companies and technologies will be present!').'</p>
            </div>
            <div class="pwe-profiles__wrapper">
                <div class="pwe-profiles__iframe">'. $profile_iframe .'</div>
                <div class="pwe-profiles__content">
                    <div class="pwe-profiles__items">';
                        if (is_array($profile_items_json)) {
                            foreach ($profile_items_json as $profile_item) {
                                $profile_icon_nmb = $profile_item["profile_icon"];
                                $profile_icon_src = wp_get_attachment_url($profile_icon_nmb);  

                                $profile_title_select = $profile_item["profile_title_select"];
                                $profile_title_custom = $profile_item["profile_title_custom"];
                                $profile_title = !empty($profile_title_select) ? $profile_title_select : $profile_title_custom; 
                                $profile_desc = $profile_item["profile_desc"];
                                $profile_text = $profile_item["profile_text"];
                                
                                $profile_desc_content = self::decode_clean_content($profile_desc);
                                $profile_text_content = self::decode_clean_content($profile_text);

                                if ($profile_title == 'profile_title_visitors') {
                                    $profile_id = "visitorProfile";
                                    $profile_title = (get_locale() == 'pl_PL') ? "Profil odwiedzającego" : "Visitor profile"; 
                                    $profile_caption = (get_locale() == 'pl_PL') ? "Dowiedz się, kogo będziemy gościć na targach." : "Find out who we will be hosting at the fair."; 
                                    $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/visitor_profile_icon.webp';
                                } else if ($profile_title == 'profile_title_exhibitors') {
                                    $profile_id = "exhibitorProfile";
                                    $profile_title = (get_locale() == 'pl_PL') ? "Profil wystawcy" : "Exhibitor profile";
                                    $profile_caption = (get_locale() == 'pl_PL') ? "Poznaj różnorodność branż, z których przyjadą wystawcy." : "Discover the diversity of industries from which exhibitors will come."; 
                                    $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/exhibitor_profile_icon.webp';
                                } else if ($profile_title == 'profile_title_scope') {
                                    $profile_id = "industryScope";
                                    $profile_title = (get_locale() == 'pl_PL') ? "Zakres branżowy" : "Industry scope";
                                    $profile_caption = (get_locale() == 'pl_PL') ? "Zapoznaj się z sektorami targów [trade_fair_name]" : "Explore the sectors of [trade_fair_name]"; 
                                    $profile_icon_src = !empty($profile_icon_src) ? $profile_icon_src : '/wp-content/plugins/PWElements/includes/profile/media/industry_scope_icon.webp';
                                } else {
                                    $profile_id = "customProfile-" . self::$rnd_id;
                                    $profile_caption = '';
                                }

                                $profile_desc_content = !empty($profile_desc_content) ? $profile_desc_content : $profile_caption;

                                $output .= '
                                <div id="'. $profile_id .'" class="pwe-profiles__item">
                                    <div class="pwe-profiles__accordion">
                                        <div class="pwe-profiles__accordion-icon">
                                            <img src="'. $profile_icon_src .'">
                                        </div>
                                        <div class="pwe-profiles__accordion-text">
                                            <h5 class="pwe-profiles__accordion-title">'. $profile_title .'</h5>
                                            <div class="pwe-profiles__accordion-desc">'. $profile_desc_content .'</div>
                                        </div>  
                                    </div>
                                    <div class="pwe-profiles__accordion-hidden">'. $profile_text_content .'</div>
                                </div>';
                            }
                        }
                        $output .= '
                    </div>
                </div>
            </div>
        </div>
        
        <script>

            document.addEventListener("DOMContentLoaded", function() {
                const profilesAccordion = document.querySelectorAll(".pwe-profiles__accordion");

                profilesAccordion.forEach(function(profileAccordion) {
                    profileAccordion.addEventListener("click", function() {
                        const profileAccordionHidden = this.nextElementSibling;

                        // Close all other open accordions
                        profilesAccordion.forEach(function(otherAccordion) {
                            const otherAccordionHidden = otherAccordion.nextElementSibling;
                            if (otherAccordion !== profileAccordion && otherAccordionHidden.classList.contains("active")) {
                                otherAccordionHidden.style.height = otherAccordionHidden.scrollHeight + "px";
                                window.setTimeout(() => {
                                    otherAccordionHidden.style.height = "0";
                                }, 10);
                                otherAccordionHidden.classList.remove("active");
                                otherAccordion.classList.remove("active");
                            }
                        });

                        // Hiding and showing with animation
                        if (profileAccordionHidden.classList.contains("active")) {
                            profileAccordionHidden.style.height = profileAccordionHidden.scrollHeight + "px";
                            window.setTimeout(() => {
                                profileAccordionHidden.style.height = "0";
                            }, 10);
                        } else {
                            profileAccordionHidden.style.height = "0";
                            window.setTimeout(() => {
                                profileAccordionHidden.style.height = profileAccordionHidden.scrollHeight + "px";
                            }, 10);
                        }

                        profileAccordionHidden.classList.toggle("active");
                        this.classList.toggle("active");
                    });

                    // Reset height after animation ends
                    profileAccordion.nextElementSibling.addEventListener("transitionend", function() {
                        if (this.classList.contains("active")) {
                            this.style.height = "auto";
                        } else {
                            this.style.height = "0";
                        }
                    });
                });
            });

        </script>';
        
        return $output;
    }
}