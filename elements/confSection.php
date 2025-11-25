<?php
/**
* Class PWElementConfSection
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementConfSection extends PWElements {

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
                'group' => 'PWE Element',
                'heading' => __('Gallery mode', 'pwelement'),
                'param_name' => 'pwe_conference_gallery_mode',
                'description' => __('Conference mode', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'group' => 'PWE Element',
                'heading' => __('Overlay color', 'pwe_header'),
                'param_name' => 'pwe_conference_section_overlay_color',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'input_range',
                'group' => 'PWE Element',
                'heading' => __('Overlay opacity', 'pwe_header'),
                'param_name' => 'pwe_conference_section_overlay_range',
                'value' => '0.80',
                'min' => '0',
                'max' => '1',
                'step' => '0.01',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Title', 'pwelement'),
                'param_name' => 'pwe_conference_section_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Description', 'pwelement'),
                'param_name' => 'pwe_conference_section_desc',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Text', 'pwelement'),
                'param_name' => 'pwe_conference_section_text',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Tags', 'pwelement'),
                'param_name' => 'pwe_conference_section_tags',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Conference link', 'pwelement'),
                'param_name' => 'pwe_conference_section_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Congress logo color', 'pwelement'),
                'param_name' => 'pwe_conference_section_logo_color',
                'description' => __('Add kongres-color.webp', 'pwelement'),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Logo white(filter)', 'pwelement'),
                'param_name' => 'pwe_conference_section_logo_white',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementConfSection',
                ),
            ),
        );
        return $element_output;
    }    
    
    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_shadow_color = self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black');

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);
        
        extract( shortcode_atts( array(
            'pwe_conference_gallery_mode' => '',
            'pwe_conference_section_overlay_color' => '',
            'pwe_conference_section_overlay_range' => '',
            'pwe_conference_section_title' => '', 
            'pwe_conference_section_desc' => '',
            'pwe_conference_section_text' => '',
            'pwe_conference_section_tags' => '',
            'pwe_conference_section_link' => '',
            'pwe_conference_section_logo_color' => '',
            'pwe_conference_section_logo_white' => '',
        ), $atts ));

        if ($pwe_conference_gallery_mode != true) {
            $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color);
            $pwe_conference_section_overlay_color = empty($pwe_conference_section_overlay_color) ? self::$main2_color : $pwe_conference_section_overlay_color;
        } else {
            $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);
            $pwe_conference_section_overlay_color = empty($pwe_conference_section_overlay_color) ? self::$accent_color : $pwe_conference_section_overlay_color;
        }

        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], $btn_color);
        $darker_btn_color = self::adjustBrightness($btn_color, -20);
        $pwe_conference_section_overlay_range = empty($pwe_conference_section_overlay_range) ? '0.8' : $pwe_conference_section_overlay_range;        

        if ($pwe_conference_section_logo_color != true) {
            $pwe_conference_logo_url = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/kongres.webp') ? '/doc/kongres.webp' : '/doc/logo.webp');
        } else {
            $pwe_conference_logo_url = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/kongres-color.webp') ? '/doc/kongres-color.webp' : '/doc/kongres.webp');
        }

        $pwe_conference_section_tags = empty($pwe_conference_section_tags) ? '#[trade_fair_badge]' : $pwe_conference_section_tags;

        if (get_locale() == 'pl_PL') {
            $pwe_conference_section_title = empty($pwe_conference_section_title) ? '[trade_fair_conferance]' : $pwe_conference_section_title;
            $pwe_conference_section_desc = empty($pwe_conference_section_desc) ? 'MIĘDZYNARODOWA KONFERENCJA BRANŻY [trade_fair_opisbranzy]' : $pwe_conference_section_desc;
            $pwe_conference_section_text = empty($pwe_conference_section_text) ? 'Rozwijaj swoje umiejętności z najlepszymi! Sprawdź ofertę wykładów i szkoleń przygotowaną specjalnie dla Ciebie.' : $pwe_conference_section_text;
            if ($pwe_conference_gallery_mode != true) {
                $pwe_conference_section_link = empty($pwe_conference_section_link) ? '/wydarzenia/' : $pwe_conference_section_link;
                $pwe_conference_section_btn_text = 'Sprawdź program';
            } else {
                $pwe_conference_section_link = empty($pwe_conference_section_link) ? '/rejestracja/' : $pwe_conference_section_link;
                $pwe_conference_section_btn_text = 'Zarejestruj się i weź udział';
            }  
        } else {
            $pwe_conference_section_title = empty($pwe_conference_section_title) ? '[trade_fair_conferance_eng]' : $pwe_conference_section_title;
            $pwe_conference_section_desc = empty($pwe_conference_section_desc) ? 'INTERNATIONAL INDUSTRY CONFERENCE OF THE [trade_fair_opisbranzy_eng]' : $pwe_conference_section_desc;
            $pwe_conference_section_text = empty($pwe_conference_section_text) ? 'Develop your skills with the best! Check out the offer of lectures and training prepared especially for you.' : $pwe_conference_section_text;
            if ($pwe_conference_gallery_mode != true) {
                $pwe_conference_section_link = empty($pwe_conference_section_link) ? '/en/conferences/' : $pwe_conference_section_link;
                $pwe_conference_section_btn_text = 'Check the program';
            } else {
                $pwe_conference_section_link = empty($pwe_conference_section_link) ? '/en/registration/' : $pwe_conference_section_link;
                $pwe_conference_section_btn_text = 'Register and take part';
            }
        }

        $output = '
            <style>
                .row-parent:has(.pwelement_'. self::$rnd_id .' .pwe-container-conference-section) {
                    max-width: 100% !important;
                    padding: 0 !important;  
                }
                .pwelement_'. self::$rnd_id .' .pwe-container-conference-section {
                    position: relative;
                    background-position: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-container-conference-section:before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: '. $pwe_conference_section_overlay_color .';
                    opacity: '. $pwe_conference_section_overlay_range .';
                    z-index: 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conference-section-columns {
                    position: relative;
                    max-width: 1200px;
                    margin: 0 auto;
                    display: flex;
                    gap: 18px;
                    z-index: 1;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conference-section-column {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    gap: 18px;
                    width: 50%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conference-section-logo {
                    max-width: 400px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conference-section-logo img {
                    width: 100%;
                }
                .pwelement_'. self::$rnd_id .' .pwe-btn-container {
                    padding-top: 0 !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conference-section-btn .pwe-link {
                    transform: none !important;
                    color: '. $btn_text_color .' !important;
                    background-color: '. $btn_color .' !important;
                    border: 1px solid '. $btn_border .' !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-conference-section-btn .pwe-link:hover {
                    color: '. $btn_text_color .' !important;
                    background-color: '. $darker_btn_color .' !important;
                    border: 1px solid '. $darker_btn_color .' !important;
                }
                @media (max-width:960px){
                    .pwelement_'. self::$rnd_id .' .pwe-conference-section-columns {
                        flex-direction: column;
                        padding: 36px;
                        gap: 36px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-section-column {
                        width: 100%;
                        gap: 18px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-logo-column,
                    .pwelement_'. self::$rnd_id .' .pwe-conference-section-logo {
                        margin: 0 auto;
                    }
                }  
            </style>';

            if ($pwe_conference_section_logo_white == true) {
                $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-conference-section-logo img {
                        filter: brightness(0) invert(1) !important;
                        transition: all .3s ease;
                    }               
                </style>';
            }
            

        if ($pwe_conference_gallery_mode != true) {
            $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-conference-section-columns {
                        padding: 72px 36px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-info-column :is(h4, h5, p) {
                        color: white !important;
                        margin: 0;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-info-column {
                        text-align: start;
                        width: 65%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-logo-column {
                        width: 35%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-name {
                        font-size: 24px !important;
                        margin: 0;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-desc {
                        font-size: 18px !important;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-tags {
                        font-weight: 700;
                    }
                    @media (max-width:960px){
                        .pwelement_'. self::$rnd_id .' .pwe-conference-info-column {
                            width: 100%;
                        }
                        .pwelement_'. self::$rnd_id .' .pwe-conference-logo-column {
                            width: 100%;
                        }
                    }  
                </style>
            ';

            $output .= '
                <div id="pweConfSection" class="pwe-container-conference-section" style="background-image: url(/wp-content/plugins/pwe-media/media/conference-background.webp);">
                    <div class="pwe-conference-section-columns">
                        <div class="pwe-conference-section-column pwe-conference-info-column">
                            <h4 class="pwe-conference-name pwe-uppercase">'. $pwe_conference_section_title .'</h4>
                            <h5 class="pwe-conference-desc pwe-uppercase">'. $pwe_conference_section_desc .'</h5>
                            <p class="pwe-conference-captions">'. $pwe_conference_section_text .'</p>
                            <p class="pwe-conference-tags">'. $pwe_conference_section_tags .'</p>
                        </div> 
                        <div class="pwe-conference-section-column pwe-conference-logo-column">
                            <div class="pwe-conference-section-logo">
                                <img src="'. $pwe_conference_logo_url .'" alt="conference logo">
                            </div>
                            <div class="pwe-conference-section-btn">
                                <div class="pwe-btn-container">
                                    <a class="pwe-link btn shadow-black pwe-btn" href="'. $pwe_conference_section_link .'">'. $pwe_conference_section_btn_text .'</a></a>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';

        } else {
            // $all_images = self::findAllImages('/doc/galeria/mini_conference', 4, '/doc/galeria/mini');

            $images_url = array(
                array(
                    "src_mini" => "/wp-content/plugins/pwe-media/media/events-mini/event_1.webp",
                ),
                array(
                    "src_mini" => "/wp-content/plugins/pwe-media/media/events-mini/event_2.webp",
                ),
                array(
                    "src_mini" => "/wp-content/plugins/pwe-media/media/events-mini/event_3.webp",
                ),
                array(
                    "src_mini" => "/wp-content/plugins/pwe-media/media/events-mini/event_4.webp",
                ),
            );

            $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-conference-section-columns {
                        padding: 36px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-conference-logo-column {
                        align-items: center;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-desc-wrapper .pwe-btn-container,
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-wrapper .pwe-btn-container {
                        display: flex;
                        justify-content: left;
                        text-align: center;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-top,
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-bottom {
                        display: flex;
                        flex-wrap: wrap;
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-top img,
                    .pwelement_'. self::$rnd_id .' .pwe-gallery-thumbs-bottom img {
                        width: 50%;
                        padding: 5px;
                        border-radius: 16px;
                    }
                    .pwelement_'. self::$rnd_id .' .slides img {
                        border-radius: 16px;
                    }
                </style>

                <div id="pweConfSection" class="pwe-container-conference-section" style="background-image: url(/wp-content/plugins/pwe-media/media/conference-background.webp);">
                    <div class="pwe-conference-section-columns">
                        <div class="pwe-conference-section-column pwe-conference-gallery-column">
                            <div class="pwe-gallery-thumbs">';   
                            if (!$mobile) {
                                $output .= '
                                <div class="pwe-gallery-thumbs-top">
                                    <img class="mini-img" src="' . $images_url[0]["src_mini"] . '" alt="mini gallery picture">
                                    <img class="mini-img" src="' . $images_url[1]["src_mini"] . '" alt="mini gallery picture">
                                </div>
                                <div class="pwe-gallery-thumbs-bottom">
                                    <img class="mini-img" src="' . $images_url[2]["src_mini"] . '" alt="mini gallery picture">
                                    <img class="mini-img" src="' . $images_url[3]["src_mini"] . '" alt="mini gallery picture">
                                </div>';
                            } else {
                                include_once plugin_dir_path(__FILE__) . '/../scripts/gallery-slider.php';
                                $output .= PWEMediaGallerySlider::sliderOutput($images_url);
                            }                        
                            $output .= '
                            </div>  
                            <div class="pwe-conference-section-btn">
                                <div class="pwe-btn-container">'.
                                    self::languageChecker(
                                        <<<PL
                                            <a class="pwe-link btn pwe-btn" href="/galeria/" alt="link do galerii">Przejdź do galerii</a>
                                        PL,
                                        <<<EN
                                            <a class="pwe-link btn pwe-btn" href="/en/gallery/" alt="link to gallery">Go to gallery</a>
                                        EN
                                    )
                            .'</div> 
                            </div>   
                                 
                        </div> 
                        <div class="pwe-conference-section-column pwe-conference-logo-column">
                            <div class="pwe-conference-section-logo">
                                <img src="'. $pwe_conference_logo_url .'" alt="conference logo">
                            </div>
                            <div class="pwe-conference-section-btn">
                                <div class="pwe-btn-container">
                                    <a class="pwe-link btn pwe-btn" href="'. $pwe_conference_section_link .'">'. $pwe_conference_section_btn_text .'</a></a>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
        
        

        return $output;
    }
}