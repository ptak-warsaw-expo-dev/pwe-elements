<?php 

/**
 * Class PWElementVideos
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementVideos extends PWElements {

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
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title element', 'pwelement'),
                'param_name' => 'pwe_video_custom_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementVideos',
                ),
              ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Opinions', 'pwelement'),
                'param_name' => 'pwe_video_opinions',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementVideos',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Turn off slider', 'pwelement'),
                'param_name' => 'pwe_videos_slider_off',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementVideos',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Turn off title', 'pwelement'),
                'param_name' => 'pwe_videos_title_off',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementVideos',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Video width', 'pwelement'),
                'param_name' => 'pwe_video_width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_videos_slider_off',
                    'value' => 'true',
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'heading' => __('Youtube iframes', 'pwelement'),
                'param_name' => 'pwe_videos_iframe',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementVideos',
                ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Title', 'pwelement'),
                        'param_name' => 'video_title',
                        'save_always' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Iframe', 'pwelement'),
                        'param_name' => 'video_iframe',
                        'save_always' => true
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
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';
        $btn_text_color = 'color:' . self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color']) . '!important; border-width: 0 !important;';
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color']) . '!important;';
        $btn_shadow_color = 'box-shadow: 9px 9px 0px -5px ' . self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color']) . '!important;';

        extract( shortcode_atts( array(
            'pwe_video_custom_title' => '',
            'pwe_video_opinions' => '',
            'pwe_video_width' => '',
            'pwe_videos_iframe' => '',
            'pwe_videos_slider_off' => '',
            'pwe_videos_title_off' => ''
        ), $atts ));

        $videos_urldecode = urldecode($pwe_videos_iframe);
        $videos_json = json_decode($videos_urldecode, true);
        foreach ($videos_json as $video) {
            $video_iframe = $video["video_iframe"];
        }

        if (!empty($video_iframe)) {
            if ($pwe_video_opinions == 'true') {
                $pwe_video_custom_title = (get_locale() == 'pl_PL') ? "REKOMENDACJE WYSTAWCÓW" : "EXHIBITOR RECOMMENDATIONS";
            } else {
                if (empty($pwe_video_custom_title)) {
                    $pwe_video_custom_title = (get_locale() == 'pl_PL') ? "Zobacz jak było na poprzednich edycjach" : "Check previous editions";
                }
            }
        } else {
            if (empty($pwe_video_custom_title)) {
                $pwe_video_custom_title = (get_locale() == 'pl_PL') ? "ZOBACZ JAK WYGLĄDAJĄ NASZE POZOSTAŁE TARGI" : "SEE WHAT OUR OTHER TRADE FAIRS LOOK LIKE";     
            }
        } 

        $pwe_video_width = (empty($pwe_video_width)) ? '47%' : $pwe_video_width;

        $output = '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-container-videos {
                    display: flex;
                    flex-direction: column;
                    gap: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-videos-title h4 {
                    margin: 0;
                }
                .pwelement_'. self::$rnd_id .' .pwe-videos {
                    display: flex;
                    justify-content: space-around;
                    flex-wrap: wrap;
                    gap: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-video-item {
                    width: '. $pwe_video_width .';
                    min-width: 300px;
                }
                .pwelement_'. self::$rnd_id .' iframe,
                .pwelement_'. self::$rnd_id .' .rll-youtube-player,
                .pwelement_'. self::$rnd_id .' .pwe-video-default {
                    box-shadow: unset;
                    width: 100%;
                    height: auto;
                    aspect-ratio: 16 / 9;
                    border-radius: 18px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-video-item p {
                    font-size: 18px;
                }
                @media (max-width:650px) {
                    .pwelement_'. self::$rnd_id .' .pwe-videos {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-video-item {
                        width: 100%;
                    }
                }
            </style>';

            if ($pwe_video_opinions == 'true') {
                $output .= '
                <style>
                    @media(min-width:1115px) {
                        .pwelement_'. self::$rnd_id .' .pwe-video-item {
                            width: 31%;
                            height: 200px;
                            position: relative;
                        }
                        .pwelement_'. self::$rnd_id .' .pwe-video-item p {
                            font-size: 16px;
                        }
                    }  
                </style>';
            }

            $output .= '
            <div id="pweVideos-'. self::$rnd_id .'" class="pwe-container-videos">';
                if ($pwe_videos_title_off != true) {
                    $output .= '
                    <div class="pwe-videos-title main-heading-text">
                        <h4 class="pwe-uppercase"><span>'. $pwe_video_custom_title .'</span></h4>
                    </div>';
                }
                $output .= ' 
                <div class="pwe-videos">';

                    if (empty($video_iframe)) {
                        if (get_locale() == 'pl_PL') {
                            $video_titles = [
                                "Ptak Warsaw Expo | 2023",
                                "Stolica Targów i Eventów w Polsce - Ptak Warsaw Expo"
                            ];
                        } else {
                            $video_titles = [
                                "Ptak Warsaw Expo | 2023",
                                "The capital of fairs and events in Poland - Ptak Warsaw Expo"
                            ];
                        }
                        $video_iframes = [
                            '<iframe width="560" height="315" data-src="https://www.youtube.com/embed/TgHh38jvkAY?si=pc01x3a22VkL-qoh" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
                            '<iframe width="560" height="315" data-src="https://www.youtube.com/embed/-RmRpZN1mHA?si=2QHfOrz0TUkNIJwP" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'
                        ];
                    } else {
                        foreach ($videos_json as $video) {
                            $video_titles[] = $video["video_title"];
                            $video_iframes[] = $video["video_iframe"];
                        }
                    }

                    if (!empty($video_iframes)) {
                        foreach ($video_iframes as $index => $video_iframe) {
                            $video_title = $video_titles[$index];

                            // Extract src from iframe
                            preg_match('/src="([^"]+)"/', $video_iframe, $match);
                            $src = $match[1];

                            // Extract the video ID from the URL
                            preg_match('/embed\/([^?]+)/', $src, $match);
                            $video_id = $match[1];

                            $video_plug = 'https://i.ytimg.com/vi/' . $video_id . '/sddefault.jpg';
                            $video_autoplay = ($pwe_videos_slider_off != true) ? '?autoplay=1' : '';
                            $video_src = 'https://www.youtube.com/embed/' . $video_id . $video_autoplay;
                            $video_iframe_html = '<iframe class="pwe-iframe" src="' . $video_src . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
                            $video_default_html = '<div class="pwe-video-default" style="background-image: url(' . $video_plug . ');">
                                                        <img src="/wp-content/plugins/pwe-media/media/youtube-button.webp" alt="youtube play button">
                                                </div>';

                            $iframes[] = array(
                                "title" => $video_title,
                                "iframe" => $video_iframe,
                                "plug" => $video_plug,
                                "id" => $video_id,
                                "src" => $video_src,
                                "html" => $video_iframe_html,
                                "default" => $video_default_html
                            );

                            if ($pwe_videos_slider_off == true) {
                                $output .= '<div class="pwe-video-item">' . $video_iframe_html . '<p>' . $video_title . '</p></div>';
                            }
                        }

                        $options[] = array(
                            "element_id" => self::$rnd_id,
                        );

                        if ($pwe_videos_slider_off != true) {
                            include_once plugin_dir_path(__FILE__) . '/../scripts/iframes-slider.php';
                            $output .= PWEIframesSlider::sliderOutput($iframes, 3000, $options); 
                        }
                    }
                $output .= '
                </div>
            </div>';

        return $output;

    }
}