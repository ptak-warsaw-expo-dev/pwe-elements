<?php 

/**
 * Class PWEDisplayInfoSpeakers
 * Extends PWEDisplayInfo class and defines a custom Visual Composer element.
 */
class PWEDisplayInfoSpeakers extends PWEDisplayInfo {

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
                'type' => 'checkbox',
                'group' => 'main',
                'heading' => __('Turn on slider', 'pwe_display_info'),
                'param_name' => 'info_speakers_slider_on',
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Turn off dots', 'pwe_display_info'),
                'param_name' => 'info_speakers_dots_off',
                'description' => __('Check if you want to turn on dots.', 'pwe_display_info'),
                'admin_label' => true,
                'save_always' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
            ), 
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Hide bio text', 'pwe_display_info'),
                'param_name' => 'info_speakers_bio_text_hide',
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Hide bio button', 'pwe_display_info'),
                'param_name' => 'info_speakers_bio_btn_hide',
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'heading' => __('Speakers', 'pwe_display_info'),
                'group' => 'main',
                'type' => 'param_group',
                'param_name' => 'info_speakers_speakers',
                'save_always' => true,
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Select Speaker Image', 'pwe_display_info'),
                        'param_name' => 'speaker_image',
                        'description' => __('Choose speaker image from the media library.', 'pwe_display_info'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Image src (doc/...)', 'pwe_display_info'),
                        'param_name' => 'speaker_image_doc',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Speaker Name', 'pwe_display_info'),
                        'param_name' => 'speaker_name',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Bio excerpt', 'pwe_display_info'),
                        'param_name' => 'speaker_bio_excerpt',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Bio full', 'pwe_display_info'),
                        'param_name' => 'speaker_bio',
                        'save_always' => true,
                    ),
                ),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'options',
                'heading' => __('Color name', 'pwe_display_info'),
                'param_name' => 'info_speakers_lect_color',
                'description' => __('Color for lecturers names.', 'pwe_display_info'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'options',
                'heading' => __('Color bio', 'pwe_display_info'),
                'param_name' => 'info_speakers_bio_color',
                'description' => __('Color for lecturers bio.', 'pwe_display_info'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Max width image', 'pwe_display_info'),
                'param_name' => 'info_speakers_max_width_img',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Width element', 'pwe_display_info'),
                'param_name' => 'info_speakers_element_width',
                'description' => __('Width element', 'pwe_display_info'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Photo as square', 'pwe_display_info'),
                'param_name' => 'info_speakers_photo_square',
                'description' => __('Check to show photos as square.', 'pwe_display_info'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'options',
                'heading' => __('Show excerpt in full', 'pwe_display_info'),
                'param_name' => 'info_speakers_excerpt_in_full',
                'description' => __('Check to show bio excerpt in bio full.', 'pwe_display_info'),
                'admin_label' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Display items for desktop', 'pwe_display_info'),
                'param_name' => 'info_speakers_display_items_desktop',
                'description' => __('Default 3', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-one-third-width',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),      
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Display items for tablet', 'pwe_display_info'),
                'param_name' => 'info_speakers_display_items_tablet',
                'description' => __('Default 2', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-one-third-width',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Display items for mobile', 'pwe_display_info'),
                'param_name' => 'info_speakers_display_items_mobile',
                'description' => __('Default 1', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-one-third-width',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Breakpoint for tablet', 'pwe_display_info'),
                'param_name' => 'info_speakers_breakpoint_tablet',
                'description' => __('Default 768px', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-half-width',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
                ),
            ), 
            array(
                'type' => 'textfield',
                'group' => 'options',
                'heading' => __('Breakpoint for mobile', 'pwe_display_info'),
                'param_name' => 'info_speakers_breakpoint_mobile',
                'description' => __('Default 420px', 'pwe_display_info'),
                'param_holder_class' => 'backend-area-half-width',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display_info_format',
                    'value' => 'PWEDisplayInfoSpeakers',
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
    public static function output($atts, $content = null) {
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']);
        $btn_border = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']);

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $rnd = rand(10000, 99999);

        extract( shortcode_atts( array(
            'info_speakers_slider_on' => '',
            'info_speakers_dots_off' => '',
            'info_speakers_bio_btn_hide' => '',
            'info_speakers_bio_text_hide' => '',
            'info_speakers_max_width_img' => '',
            'info_speakers_display_items_desktop' => '',
            'info_speakers_display_items_tablet' => '',
            'info_speakers_display_items_mobile' => '',
            'info_speakers_breakpoint_tablet' => '',
            'info_speakers_breakpoint_mobile' => '',
            'info_speakers_speakers' => '',
            'info_speakers_lect_color' => '',
            'info_speakers_bio_color' => '',
            'info_speakers_element_width' => '',
            'info_speakers_photo_square' => '',
            'info_speakers_excerpt_in_full' => '',
        ), $atts ) );

        $info_speakers_options = array();
        $info_speakers_options[] = array(
            "display_items_desktop" => $info_speakers_display_items_desktop,
            "display_items_tablet" => $info_speakers_display_items_tablet,
            "display_items_mobile" => $info_speakers_display_items_mobile,
            "breakpoint_tablet" => $info_speakers_breakpoint_tablet,
            "breakpoint_mobile" => $info_speakers_breakpoint_mobile,
            "max_width_img" => $info_speakers_max_width_img,
            "btn_hide" => $info_speakers_bio_btn_hide,
            "lect_color" => $info_speakers_lect_color,
            "bio_color" => $info_speakers_bio_color,
        );

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        $info_speakers_lect_color = empty($info_speakers_lect_color) ? 'black' : $info_speakers_lect_color;
        $info_speakers_bio_color = empty($info_speakers_bio_color) ? 'black' : $info_speakers_bio_color;
        $info_speakers_element_width = empty($info_speakers_element_width) ? '150px' : $info_speakers_element_width;
        $info_speakers_max_width_img = empty($info_speakers_max_width_img) ? '100%' : $info_speakers_max_width_img;
        $info_speakers_photo_square = $info_speakers_photo_square != true ? '50%' : '0';

        $output = '
        <style>
            #info-speaker-'. self::$rnd_id .' {
                text-align: center;
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 18px;
            }
            #info-speaker-'. self::$rnd_id .' .pwe-speaker {
                width: '. $info_speakers_element_width .' !important;
                min-width: '. $info_speakers_element_width .';
                display: flex;
                flex-direction: column;
                text-align: center;
                justify-content: space-between;
                gap: 14px;
            }
            #info-speaker-'. self::$rnd_id .' .pwe-speaker-name {
                color: '. $info_speakers_lect_color .';
            }
            #info-speaker-'. self::$rnd_id .' .pwe-speaker-img {
                width: '. $info_speakers_max_width_img .';
                border-radius: '. $info_speakers_photo_square .';
                margin: 0 auto;
                aspect-ratio: 1/1;
                object-fit: cover;
            }
            #info-speaker-'. self::$rnd_id .' .pwe-speaker-excerpt {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }
            #info-speaker-'. self::$rnd_id .' .pwe-speaker-btn {
                margin: 10px auto !important;
                color: '. $btn_text_color .';
                background-color: '. $btn_color .';
                border: 1px solid '. $btn_border .';
                padding: 6px 16px;
                font-weight: 600;
                width: 80px;
                border-radius: 10px;
                transition: .3s ease;
            }
            #info-speaker-'. self::$rnd_id .' .pwe-speaker-btn:hover {
                color: '. $btn_text_color .';
                background-color: '. $darker_btn_color .'!important;
                border: 1px solid '. $darker_btn_color .'!important;
            }
            #pweSpeakerModal-'. $rnd .' {
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                background-color: rgba(0, 0, 0, 0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                visibility: hidden;
                transition: opacity 0.3s, visibility 0.3s;
            }
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-content {
                position: relative;
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                border-radius: 20px;
                overflow-y: auto;
                width: 80%;
                max-width: 700px;
                max-height: 90%;
                transition: transform 0.3s;
                transform: scale(0);
            }
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-close {
                position: absolute;
                right: 18px;
                top: -6px;
                color: #000;
                float: right;
                font-size: 50px;
                font-weight: bold;
                transition: transform 0.3s;
                font-family: monospace;
            }
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-close:hover,
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
                transform: scale(1.2);
            }
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-title,
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-excerpt,
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-desc {
                margin: 18px 0 0;
            }
            #pweSpeakerModal-'. $rnd .' .pwe-speaker-modal-excerpt {
                text-align: center;
                max-width: 80%;
            }
            #pweSpeakerModal-'. $rnd .'.is-visible {
                opacity: 1;
                visibility: visible;
            }
            #pweSpeakerModal-'. $rnd .'.is-visible .pwe-speaker-modal-content {
                transform: scale(1);
            }
            #pweSpeakerModal-'. $rnd .'.is-visible .pwe-speaker-modal-image {
                border-radius: 10px;
            }
        </style>';

        if ($info_speakers_bio_btn_hide == true) {
            $output .= '<style>#info-speaker-'. self::$rnd_id .' .pwe-speaker-btn { display: none !important; }</style>';
        }
        if ($info_speakers_bio_text_hide == true) {
            $output .= '<style>#info-speaker-'. self::$rnd_id .' .pwe-speaker-desc { display: none !important; } #info-speaker-'. self::$rnd_id .' .pwe-speaker-excerpt { display: none !important; }</style>';
        }

        $speakers_urldecode = urldecode($info_speakers_speakers);
        $speakers_json = json_decode($speakers_urldecode, true);

        $info_speakers_slider = array();

        if (is_array($speakers_json)) {
            foreach ($speakers_json as $speaker){
                $speaker_image = $speaker["speaker_image"];
                $speaker_name = $speaker["speaker_name"];
                $speaker_bio = $speaker["speaker_bio"];
                $speaker_bio_excerpt = isset($speaker["speaker_bio_excerpt"]) ? $speaker["speaker_bio_excerpt"] : '';

                $speaker_image_src = wp_get_attachment_url($speaker_image); 
                $speaker_image_doc_src = $speaker["speaker_image_doc"]; 

                $speaker_img = !empty($speaker_image_doc_src) ? "https://" . $_SERVER['HTTP_HOST'] . "/doc/" . $speaker_image_doc_src : $speaker_image_src;

                $item_speaker_id = 'pweSpeaker-' . $rnd;

                if ($info_speakers_slider_on != true && !$mobile) {
                    $output .= '<div id="'. $item_speaker_id .'" class="pwe-speaker">';
                        // Check if the URL exists
                        if (filter_var($speaker_img, FILTER_VALIDATE_URL)) {
                            // Check if the first header indicates a successful response (200 OK)
                            if (isset(get_headers($speaker_img)[0]) && strpos(get_headers($speaker_img)[0], '200') !== false) {
                                $output .= !empty($speaker_image_doc_src) ? '<img class="pwe-speaker-img" src="https://' . $_SERVER['HTTP_HOST'] . '/doc/' . $speaker_image_doc_src .'">' : '<img class="pwe-speaker-img" src="'. $speaker_image_src .'">';
                            } else {
                                $output .= '<img class="pwe-speaker-img" src="/wp-content/plugins/PWElements/includes/display-info/media/white_square.jpg">';
                            }
                        }
                        $output .= '<h5 class="pwe-speaker-name" style="margin-top: 9px;">'. $speaker_name .'</h5>';
                        $output .= !empty($speaker_bio_excerpt) ? '<div class="pwe-speaker-excerpt">'. $speaker_bio_excerpt .'</div>' : '';
                        $output .= '<div class="pwe-speaker-desc" style="display:none;">'. $speaker_bio .'</div>';
                        $output .= !empty($speaker_bio) ? '<button class="pwe-speaker-btn">BIO</button>' : '';
                    $output .='</div>';
                }
                
                $info_speakers_slider[] = array(
                    "img" => $speaker_image_src,
                    "name" => $speaker_name,
                    "bio" => $speaker_bio,
                    "bio_excerpt" => $speaker_bio_excerpt
                );
            }

            if ($info_speakers_slider_on == true || $mobile) {         
                include_once plugin_dir_path(dirname(dirname(__DIR__))) . 'scripts/speakers-slider.php';
                $output .= PWESpeakersSlider::sliderOutput($info_speakers_slider, 3000, $info_speakers_options);
            }
        }   
        $output .='
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const speakers = document.querySelectorAll("#info-speaker-'. self::$rnd_id .' .pwe-speaker");
                    
                    speakers.forEach((speaker) => {
                        const img = speaker.querySelector(".pwe-speaker-img");
                        const name = speaker.querySelector(".pwe-speaker-name");
                        const excerpt = speaker.querySelector(".pwe-speaker-excerpt");
                        const desc = speaker.querySelector(".pwe-speaker-desc");
                        const btn = speaker.querySelector(".pwe-speaker-btn");

                        if (!desc || desc.textContent.trim() === "" && desc.children.length === 0) {
                            speaker.style.justifyContent = "flex-start";
                        }

                        if (img && img.src == undefined) {
                            const backgroundImage = window.getComputedStyle(img).getPropertyValue("background-image");
                            // Extracting the URL from the background-image value in the slider
                            const urlMatch = backgroundImage.match(/url\\(\\"?(.*?)\\"?\\)/);
                            const imageUrl = urlMatch ? urlMatch[1] : null;
                            if (imageUrl) {
                                img.src = imageUrl;
                            }
                        }
                        
                        if (btn) {
                            btn.addEventListener("click", function() {
                                const modalDiv = document.createElement("div");
                                modalDiv.className = "pwe-speaker-modal";
                                modalDiv.id = "pweSpeakerModal-'. $rnd .'";
                                modalDiv.innerHTML = `
                                    <div class="pwe-speaker-modal-content" style="display:flex; flex-direction:column; align-items:center; padding:20px;">
                                        <span class="pwe-speaker-modal-close">&times;</span>
                                        <img class="pwe-speaker-modal-image" src="" alt="Speaker Image" style="width:100%; max-width:150px;">
                                        <h5 class="pwe-speaker-modal-title">${name.innerHTML}</h5>';
                                        if ($info_speakers_excerpt_in_full) {
                                            $output .='<div class="pwe-speaker-modal-excerpt">${excerpt.innerHTML}</div>';
                                        }
                                        $output .='
                                        <div class="pwe-speaker-modal-desc">${desc.innerHTML}</div>
                                    </div>
                                `;

                                if (img) {
                                    modalDiv.querySelector(".pwe-speaker-modal-image").src = img.src;
                                } else {
                                    modalDiv.querySelector(".pwe-speaker-modal-image").style.display = "none";
                                }
                                
                                document.body.appendChild(modalDiv);
                                requestAnimationFrame(() => {
                                    modalDiv.classList.add("is-visible");
                                });
                                disableScroll();

                                // Close modal
                                modalDiv.querySelector(".pwe-speaker-modal-close").addEventListener("click", function() {
                                    modalDiv.classList.remove("is-visible");
                                    setTimeout(() => {
                                        modalDiv.remove();
                                        enableScroll();
                                    }, 300); // Czekaj na zakończenie animacji przed usunięciem
                                });

                                modalDiv.addEventListener("click", function(event) {
                                    if (event.target === modalDiv) {
                                        modalDiv.classList.remove("is-visible");
                                        setTimeout(() => {
                                            modalDiv.remove();
                                            enableScroll();
                                        }, 300);
                                    }
                                });
                            });
                        }
                    });
                });

                // Functions to turn scrolling off and on
                function disableScroll() {
                    document.body.style.overflow = "hidden";
                    document.documentElement.style.overflow = "hidden";
                }
                function enableScroll() {
                    document.body.style.overflow = "";
                    document.documentElement.style.overflow = "";
                }
            </script>
        ';

        return $output;
    }
}