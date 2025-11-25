<?php

class PWESliderScripts {

        /**
         * Initializes the slider.
         */
        public function __construct() {}

        /**
         * Prepares and returns the scripts for the slider.
         */
        public static function sliderScripts($id = '', $pwe_element = '.pwelement', $dots_display = false, $arrows_display = false, $slides_to_show = 5, $options = null, $slides_to_show_1 = 5, $slides_to_show_2 = 3, $slides_to_show_3 = 2, $breakpoints_raw = '') {
            wp_enqueue_style('slick-slider-css', plugins_url('../assets/slick-slider/slick.css', __FILE__));
            wp_enqueue_style('slick-slider-theme-css', plugins_url('../assets/slick-slider/slick-theme.css', __FILE__));
            wp_enqueue_script('slick-slider-js', plugins_url('../assets/slick-slider/slick.min.js', __FILE__), array('jquery'), null, true);

            include_once plugin_dir_path(__DIR__) . 'pwefunctions.php';
            $fair_colors = PWECommonFunctions::findPalletColorsStatic();
            $accessibility = ($id == 'posts') ? 'accessibility: false,' : '';

            $accent_color = ($fair_colors['Accent']) ? $fair_colors['Accent'] : '';
            foreach($fair_colors as $color_key => $color_value){
                if(strpos(strtolower($color_key), 'main2') !== false){
                    $main2_color = $color_value;
                }
            }

            $output = '
            <style>
                '. $pwe_element .' .pwe-slides {
                    visibility: hidden;
                    opacity: 0;
                    height: 0;
                    width: 0;
                    transition: .3s ease;
                }
                '. $pwe_element .' .slick-slide {
                    height: auto;
                }
                '. $pwe_element .' .pwe-arrow {
                    display: block;
                    position: absolute;
                    top: 50%;
                    transform: translate(0, -50%);
                    font-size: 60px;
                    font-weight: 700;
                    z-index: 1;
                    cursor: pointer;
                }
                '. $pwe_element .' .pwe-arrow-prev {
                    left: 14px;
                }
                '. $pwe_element .' .pwe-arrow-next {
                    right: 14px;
                }
                '. $pwe_element .' .slick-dots {
                    position: relative;
                    width: 100%;
                    max-width: 90px;
                    overflow: hidden;
                    white-space: nowrap;
                    padding: 0 !important;
                    list-style: none;
                    margin: auto !important;
                }
                '. $pwe_element .' .slick-dots li {
                    width: 16px;
                    height: 16px;
                    margin: 0 7px;
                    background-color: #bbb;
                    border: none;
                    border-radius: 50%;
                }
                '. $pwe_element .' .slick-dots li button {
                    opacity: 0;
                }
                '. $pwe_element .' .slick-dots li.slick-active {
                    transform-origin: center;
                    background: '. $main2_color .';
                }
            </style>';

            if ($id == 'media-gallery') { // media-gallery.php <-------------------------------------------------------------<

                if ($options[0]["media_gallery_dots_inside"] == true) {
                    $output .= '
                    <style>
                        '. $pwe_element .' .slick-dots {
                            position: absolute;
                            bottom: 10px;
                            left: 50%;
                            transform: translate(-50%);
                        }
                        '. $pwe_element .' .slick-dots li {
                            width: 10px;
                            height: 10px;
                            margin: 0 10px;
                            background-color: transparent;
                            border: 1px solid white;
                        }
                        '. $pwe_element .' .slick-dots li.slick-active {
                            transform-origin: center;
                            background: white;
                        }
                    </style>';
                } else {
                    $output .= '
                    <style>
                        '. $pwe_element .' .pwe-gallery-container {
                            overflow: visible;
                        }
                    </style>';
                }
                if ($options[0]["media_gallery_arrows_inside"] == true) {
                    $output .= '
                    <style>
                        '. $pwe_element .' .pwe-arrow {
                            top: 50%;
                            transform: translate(0, -50%);
                            background-color: white;
                            height: 30px;
                            width: 30px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border-radius: 50%;
                            font-size: 30px;
                            padding-bottom: 5px;
                        }
                    </style>';
                } else {
                    $output .= '
                    <style>
                        '. $pwe_element .' .pwe-arrow-prev {
                            left: -26px;
                        }
                        '. $pwe_element .' .pwe-arrow-next {
                            right: -26px;
                        }
                    </style>';
                }

            }

            if ($id == 'logotypes') {
                $output .= '
                <style>
                    '. $pwe_element .' .pwe-arrow-prev {
                        left: -26px;
                    }
                    '. $pwe_element .' .pwe-arrow-next {
                        right: -26px;
                    }
                </style>';
            }

            $center_mode = "";
            $center_padding = "";

            if ($id == 'logotypes') { // logotypes_common.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 400 ? '. $slides_to_show_3 .' :
                        elementWidth < 600 ? '. $slides_to_show_2 .' :
                        elementWidth < 960 ? '. $slides_to_show_1 .':
                        slidesToShowSetting;
                ';
            } else if ($id == 'opinions') { // opinions.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 550 ? 1 :
                        elementWidth < 960 ? 2 :
                        elementWidth < 1100 ? 3 :
                        elementWidth < 1400 ? 4 :
                        slidesToShowSetting;
                ';
            } else if ($id == 'opinions-preset-4') { // opinions.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 600 ? 1 :
                        elementWidth < 960 ? 1 :
                        elementWidth < 1100 ? 1 :
                        elementWidth < 1400 ? 1 :
                        slidesToShowSetting;
                ';

                $center_mode = 'centerMode: true,';
                $center_padding = 'centerPadding: slickSlider.width() < 960 ? "0px" : "100px"';


            } else if ($id == 'opinions-preset-5') { // opinions.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 600 ? 1 :
                        elementWidth < 960 ? 2 :
                        elementWidth < 1100 ? 2 :
                        elementWidth < 1400 ? 2 :
                        slidesToShowSetting;
                ';
            } else if ($id == 'posts') { // posts.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 500 ? 1 :
                        elementWidth < 700 ? 2 :
                        elementWidth < 900 ? 3 :
                        slidesToShowSetting;
                ';
            } else if ($id == 'two-cols-logotypes') { // two-cols.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 400 ? 2 :
                        elementWidth < 600 ? 2 :
                        elementWidth < 900 ? 3 :
                        elementWidth < 1100 ? 4 :
                        slidesToShowSetting;
                ';
            } else if ($id == 'pwe-medals__items_mobile') { // medals.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 400 ? 2 :
                        elementWidth < 600 ? 2 :
                        elementWidth < 900 ? 3 :
                        elementWidth < 1100 ? 4 :
                        slidesToShowSetting;
                ';
            } else if ($id == 'sticky-buttons') { // sticky-buttons.php <-------------------------------------------------------------<
                if (!empty($breakpoints_raw)) {
                    $get_initial_slides_to_show = $breakpoints_raw;
                } else {
                    $get_initial_slides_to_show = '
                    return (
                        elementWidth < 400 ? 1 :
                        elementWidth < 600 ? 2 :
                        elementWidth < 900 ? 3 :
                        elementWidth < 1100 ? 4 :
                        slidesToShowSetting
                    );';
                }
            } else if ($id == 'other-events') { // other-events.php <-------------------------------------------------------------<
                if ($options[0]["other_events_preset"] == 'preset_1') {
                    $get_initial_slides_to_show = '
                    return  elementWidth < 900 ? 1 :
                            slidesToShowSetting;
                    ';
                } else {
                    $get_initial_slides_to_show = '
                    return  elementWidth < 400 ? 1 :
                            elementWidth < 600 ? 2 :
                            elementWidth < 900 ? 3 :
                            elementWidth < 1100 ? 4 :
                            slidesToShowSetting;
                    ';
                }
            } else if ($id == 'media-gallery') { // media-gallery.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < '. $options[0]["breakpoint-mobile"] .' ? '. $options[0]["count-visible-thumbs-mobile"] .' :
                        elementWidth < '. $options[0]["breakpoint-tablet"] .' ? '. $options[0]["count-visible-thumbs-tablet"] .' :
                        slidesToShowSetting;
                ';
            } else if ($id == 'capconf') { // conference_cap.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 400 ? 2 :
                        elementWidth < 600 ? 3 :
                        elementWidth < 1100 ? 5 :
                        slidesToShowSetting;
                ';
            } else if ($id == 'display-info') { // conference_cap.php <-------------------------------------------------------------<
                $get_initial_slides_to_show = '
                return  elementWidth < 400 ? 1 :
                        elementWidth < 600 ? 2 :
                        elementWidth < 1100 ? 3 :
                        slidesToShowSetting;
                ';
            }

            $output .= '
            <script>
                jQuery(function ($) {
                    const pweElement = $("'. $pwe_element .'");
                    const slickSlider = $("'. $pwe_element .' .pwe-slides");
                    const sliderArrows = $("'. $pwe_element .' .pwe-arrow");
                    const totalSlides = slickSlider.children().length;

                    const sliderDotsDisplay = "'. $dots_display .'";
                    const sliderArrowsDisplay = "'. $arrows_display .'";
                    const slidesToShowSetting = '. $slides_to_show .';

                    // Function to initialize Slick Slider
                    function initializeSlick(arrowsEnabled = false, dotsEnabled = false) {
                        const currentSlidesToShow = getInitialSlidesToShow();

                        // Destroy Slick if already initialized
                        if (slickSlider.hasClass("slick-initialized")) {
                            slickSlider.slick("unslick");
                        }

                        // Initialize Slick Slider
                        slickSlider.slick({
                            infinite: true,
                            slidesToShow: currentSlidesToShow,
                            slidesToScroll: 1,
                            arrows: arrowsEnabled,
                            nextArrow: $("'. $pwe_element .' .pwe-arrow-next"),
                            prevArrow: $("'. $pwe_element .' .pwe-arrow-prev"),
                            autoplay: true,
                            autoplaySpeed: 5000,
                            speed: 600,
                            dots: dotsEnabled,
                            cssEase: "linear",
                            '. $accessibility .'
                            swipeToSlide: true,
                            '. $center_mode .'
                            '. $center_padding .'
                        });

                        // Hide arrows if arrows are disabled
                        if (!arrowsEnabled) {
                            sliderArrows.hide();
                        } else {
                            sliderArrows.show();
                        }

                        monitorRoleAttributes();
                    }

                    // Function to monitor role="tabpanel" and remove it if it"s added
                    function monitorRoleAttributes() {
                        // Create a MutationObserver to watch for changes in the DOM
                        const observer = new MutationObserver(function(mutationsList) {
                            for (const mutation of mutationsList) {
                                if (mutation.type === "attributes" && mutation.attributeName === "role") {
                                    const target = mutation.target;
                                    if (target.getAttribute("role") === "tabpanel") {
                                        target.removeAttribute("role"); // Remove role="tabpanel"
                                    }
                                }
                            }
                        });

                        // Target all img elements inside .two-cols-logotypes
                        const targetNodes = document.querySelectorAll(".two-cols-logotypes img");

                        // Observe changes in these elements
                        targetNodes.forEach(node => {
                            observer.observe(node, {
                                attributes: true // Watch for attribute changes
                            });
                        });
                    }

                    // Settings for slidesToShow based on breakpoints
                    function getInitialSlidesToShow() {
                        const elementWidth = pweElement.width();
                        '. $get_initial_slides_to_show .'
                    }

                    // Check if arrows and dots should be enabled
                    function updateSlickSettings() {
                        const currentSlidesToShow = getInitialSlidesToShow();
                        let dotsEnabled = totalSlides > currentSlidesToShow && sliderDotsDisplay === "true";
                        let arrowsEnabled = totalSlides > currentSlidesToShow && sliderArrowsDisplay === "true";

                        initializeSlick(arrowsEnabled, dotsEnabled);

                        if (dotsEnabled) {
                            slickSlider.on("afterChange", function (event, slick, currentSlide) {
                                const $slickDots = $(event.target).find(".slick-dots");
                                const dotWidth = 30;

                                // Calculate the offset based on the currentSlide index
                                const scrollPosition = (currentSlide - 1) * dotWidth;

                                // Set scrollLeft directly on the .slick-dots container
                                $slickDots.animate({ scrollLeft: scrollPosition }, 300);
                            });
                        }
                    }

                    // Initialize slider on document ready
                    updateSlickSettings();

                    // Reinitialize slider on window resize and element resize
                    const resizeObserver = new ResizeObserver(() => {
                        updateSlickSettings();
                    });

                    resizeObserver.observe(pweElement[0]);

                    slickSlider.css({
                        "visibility": "visible",
                        "opacity": "1",
                        "height": "auto",
                        "width": "auto"
                    });

                });
            </script>';

            return $output;
        }
}

?>