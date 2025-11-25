<?php

class PWELogotypesSlider {
    
        /**
         * Initializes the slider.
         */
        public function __construct() {
        }

        /**
         * Creates the DOM structure for the slider.
         * 
         * @param int $id_rnd Random ID for the slider element.
         * @param array $media_url Array of media URLs.
         * @param int $min_image Minimum image index.
         * @param int $max_image Maximum image index.
         * @return string The DOM structure as HTML.
         */
        private static function createDOM($id_rnd, $media_url, $min_image, $max_image, $images_options) {
                
                $element_id = (!empty($images_options[0]['element_id'])) ? $images_options[0]['element_id'] : '';
                $logotypes_caption_on = (!empty($images_options[0]['logotypes_caption_on'])) ? $images_options[0]['logotypes_caption_on'] : '';
                $header_logotypes_caption_on = (!empty($images_options[0]['header_logotypes_caption_on'])) ? $images_options[0]['header_logotypes_caption_on'] : '';
                $logotypes_dots_off = (!empty($images_options[0]['logotypes_dots_off'])) ? $images_options[0]['logotypes_dots_off'] : ''; 
                $accent_color = do_shortcode('[trade_fair_accent]');

                $caption_translations = (!empty($images_options[0]['caption_translations'])) ? $images_options[0]['caption_translations'] : '';

                $target_blank = (!empty($images_options[0]['target_blank'])) ? $images_options[0]['target_blank'] : '';

                $output = '
                <style>
                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider,
                        #katalog-'. $element_id .' .pwe-element-logotypes-slider {
                                width: 100%;
                                overflow: hidden;
                                margin: 0 !important;
                        }
                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .slides,
                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .slides {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                margin: 0 !important;
                                min-height: 0 !important;
                                min-width: 0 !important;
                                pointer-events: auto;
                        }
                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .slides > div,
                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .slides > div {
                                padding: 0;
                                object-fit: contain !important;
                                
                        }
                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .slides a,
                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .slides a {
                                align-self: flex-start;
                        }
                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .slides .image-container,
                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .slides .image-container {
                                margin: 5px !important;
                        }
                        @keyframes slideAnimation {
                                from {
                                        transform: translateX(100%);
                                }
                                to {
                                        transform: translateX(0);
                                }
                        }
                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .slides .slide,
                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .slides .slide {
                                animation: slideAnimation 0.5s ease-in-out;
                        }
                        .pwe-element-logotypes-slider {
                                -webkit-touch-callout: none; /* iOS Safari */
                                -webkit-user-select: none; /* Safari */
                                -khtml-user-select: none; /* Konqueror HTML */
                                -moz-user-select: none; /* Firefox w przeszłości (stare wersje) */
                                -ms-user-select: none; /* Internet Explorer (>=10) / Edge */
                                        user-select: none; /* Aktualnie wspierane przez: Chrome, Opera and Firefox */
                        }
                </style>';

                if ($logotypes_caption_on == true || $header_logotypes_caption_on == true) {
                        $output .= '
                        <style>
                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .slides > div p,
                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .slides > div p {
                                width: 100%;
                                text-transform: uppercase;
                                font-size: 12px;
                                font-weight: 700;
                                color: black;
                                white-space: break-spaces;
                                text-align: center;
                                line-height: 1.1 !important;
                                padding: 5px;
                                margin: 0 !important;
                        }';
                }
                $output .= '</style>';
                
                $output .= '
                <div id="PWELogotypesSlider-'. $id_rnd .'" class="pwe-element-logotypes-slider">
                        <div class="slides">';
                        
                        $totalImages = count($media_url);
                        $imagesRange = range($min_image, $max_image - 1);

                        foreach ($imagesRange as $i) {
                                if ($i < 0) {
                                        $imgNumber = $totalImages + $i;
                                } elseif ($i >= $totalImages) {
                                        $imgNumber = $i - $totalImages;
                                } else {
                                        $imgNumber = $i;
                                }

                                $imageData = $media_url[$imgNumber];
                                $imageStyles = "background-image:url('{$imageData['img']}');";
                                $imageId = (!empty($imageData['id']) && $imageData['id'] == 'primary') ? 'as-' . $imageData['id'] : '';
                                // $imageUrl = $imageData['site']; //Links are disabled
                                $imageUrl = '';
                                $imageClass = !empty($imageData['class']) ? $imageData['class'] : '';
                                $imageStyle = !empty($imageData['style']) ? $imageData['style'] : '';
                                $imageCaption = (isset($imageData['folder_name'])) ? $imageData['folder_name'] : '';
                                $imageCustomCaption = (isset($imageData['logotypes_name'])) ?  $imageData['logotypes_name'] : '';

                                $currentDomain = $_SERVER['HTTP_HOST'];
                                $imageDomain = parse_url($imageUrl, PHP_URL_HOST);

                                if (!empty($target_blank) && $currentDomain !== $imageDomain) {
                                        $imageTargetBlank = $target_blank;
                                } else if ($currentDomain !== $imageDomain) {
                                        $imageTargetBlank = isset($imageData['target_blank']) ? $imageData['target_blank'] : '';
                                } else $imageTargetBlank = '';

                                if (($logotypes_caption_on == true || $header_logotypes_caption_on == true) && empty($imageCustomCaption)) {
                                        if (get_locale() == 'pl_PL') {
                                                if (strpos($imageData['img'], 'expoplanner.com') !== false) {
                                                        $logo_caption_text = '<p>Wystawca</p>';
                                                } else {
                                                        // Split folder_name into words and add <br> after the first word
                                                        $logotypes_caption_words = explode(" ", $imageCaption);
                                                        if (count($logotypes_caption_words) > 1) {
                                                                $logo_caption_text = '<p>' . $logotypes_caption_words[0] . '<br>' . implode(" ", array_slice($logotypes_caption_words, 1)) . '</p>';
                                                        } else {
                                                                $logo_caption_text = '<p>' . $imageCaption . '</p>'; // When folder_name is one word
                                                        }
                                                }
                                        } else {
                                                if (strpos($imageData['img'], 'expoplanner.com') !== false) {
                                                        $logo_caption_text = '<p>Exhibitor</p>';
                                                } else {
                                                        if (array_key_exists($imageCaption, $caption_translations)) {
                                                                $logo_caption_text = '<p>'. $caption_translations[$imageCaption] .'</p>';
                                                        } else {
                                                                $logo_caption_text = '<p>'. $imageCaption .'</p>';
                                                        }
                                                }
                                        }
                                } else {
                                        $logo_caption_text = '<p>'. $imageCustomCaption .'</p>';
                                }
                                
                                // Create HTML
                                if (!empty($imageUrl)) {
                                        $output .= '
                                        <div class="image-container">
                                                <a href="' . $imageUrl . '" ' . $imageTargetBlank . ' ' . $imageId . '"><div class="' . $imageClass . ' logo-with-link" style="' . $imageStyles . ' ' . $imageStyle . '"></div>
                                                '. $logo_caption_text .'</a>
                                        </div>';
                                } else {
                                        $output .= '
                                        <div class="image-container">
                                                <div ' . $imageClass . ' logo-without-link" style="' . $imageStyles . ' ' . $imageStyle . '"></div>
                                                '. $logo_caption_text .'
                                        </div>';
                                }
                        }

                        $output .= '
                        </div>';

                        if ($logotypes_dots_off != true) {

                                $output .= '
                                <style>
                                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .dots-container,
                                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .dots-container {
                                                display: none;
                                                text-align: center;
                                                margin-top: 18px !important;
                                        }
                                        #katalog-'. $element_id .' #top10 .pwe-element-logotypes-slider .dots-container {
                                                display: none;
                                                text-align: center;
                                                margin: 18px 0 36px !important;
                                        }
                                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .dot,
                                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .dot {
                                                display: inline-block;
                                                width: 15px;
                                                height: 15px;
                                                border-radius: 50%;
                                                background-color: #bbb;
                                                margin: 0 5px;
                                                cursor: pointer;
                                        }
                                        .pwelement_'. $element_id .' .pwe-element-logotypes-slider .dot.active,
                                        #katalog-'. $element_id .' .pwe-element-logotypes-slider .dot.active {
                                                background-color: '. $accent_color .';
                                        }   
                                </style>';

                                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                                if (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false || strpos($userAgent, 'Macintosh') !== false || strpos($userAgent, 'Mac OS X') !== false) {
                                        $output .= '
                                        <style>
                                                .pwelement_'. $element_id .' .pwe-element-logotypes-slider .dots-container {
                                                        margin-top: 36px !important;
                                                }
                                                #katalog-'. $element_id .' .pwe-element-logotypes-slider .dots-container {
                                                        margin-top: 36px !important;
                                                }      
                                        </style>';
                                }
                                
                                $output .= '
                                <div class="dots-container">
                                        <span class="dot active"></span>
                                        <span class="dot"></span>
                                        <span class="dot"></span>
                                </div>';
                        }

                $output .= '        
                </div>';
                return $output;
        }

        /**
         * Generates the necessary JavaScript for the slider functionality.
         * 
         * @param int $id_rnd Random ID for the slider element.
         * @param array $media_url Array of media URLs.
         * @param int $min_image Minimum image index.
         * @param int $slide_speed Slide transition speed in milliseconds.
         * @return string The JavaScript code as HTML.
         */
        private static function generateScript($id_rnd, $media_url, $min_image, $slide_speed) {

                $media_url_count = count($media_url);
                $min_image_adjusted = -$min_image;

                $output = '
                <script>
                        jQuery(function ($) {
                                const slider = document.querySelector("#PWELogotypesSlider-'. $id_rnd .'");
                                const slides = slider.querySelector(".slides");
                                const images = slider.querySelectorAll(".slides div");
                                const dotsContainer = slider.querySelector(".dots-container");
                                const dots = slider.querySelectorAll(".dots-container .dot");
                                const links = slider.querySelectorAll("a");

                                links.forEach(link => {
                                        link.addEventListener("mousedown", (e) => {
                                                e.preventDefault();
                                        });
                                });

                                let isMouseOver = false;
                                let isDragging = false;

                                let imagesMulti = "";
                                const slidesWidth = slides.clientWidth;

                                if (slidesWidth < 400) {
                                        imagesMulti = 2;
                                } else if (slidesWidth < 600) {
                                        imagesMulti = 3;
                                } else if (slidesWidth < 959) {
                                        imagesMulti = 5;
                                } else {
                                        imagesMulti = 7;
                                }

                                if (imagesMulti >= '. $media_url_count .') {
                                        $("#PWELogotypesSlider-'. $id_rnd .' .slides").each(function () {
                                                $(this).css("justify-content", "center");
                                                if ($(this).children().length > '. $media_url_count .') {
                                                        $(this).children().slice('. $media_url_count .').remove();
                                                };
                                        });
                                        const imageWidth = Math.floor((slidesWidth - imagesMulti * 10) / imagesMulti);
                                        images.forEach((image) => {
                                                image.style.maxWidth = imageWidth + "px";
                                                image.style.minWidth = imageWidth + "px";
                                        });
                                } else {
                                        const imageWidth = Math.floor((slidesWidth - imagesMulti * 10) / imagesMulti);
                                        images.forEach((image) => {
                                                image.style.minWidth = imageWidth + "px";
                                                image.style.maxWidth = imageWidth + "px";
                                        });
                                        const slidesTransform = (imageWidth + 10) * '. $min_image_adjusted .';

                                        slides.style.transform = `translateX(-${slidesTransform}px)`;

                                        if (dotsContainer) {
                                                dotsContainer.style.display = "block";
                                        }

                                        function nextSlide() {
                                                slides.querySelectorAll("#PWELogotypesSlider-'. $id_rnd .' .image-container").forEach(function (image) {
                                                        image.classList.add("slide");
                                                });

                                                const firstSlide = slides.firstElementChild;
                                                if (firstSlide) {
                                                        firstSlide.classList.add("first-slide");

                                                        // Przesuwamy pierwszy slajd na koniec
                                                        slides.appendChild(firstSlide);

                                                        setTimeout(() => {
                                                                firstSlide.classList.remove("first-slide");
                                                        }, '. ($slide_speed / 2) .');
                                                }

                                                // Usuwamy klasę "slide" ze wszystkich obrazów po określonym czasie
                                                setTimeout(function () {
                                                        slides.querySelectorAll("#PWELogotypesSlider-'. $id_rnd .' .image-container").forEach(function (image) {
                                                                image.classList.remove("slide");
                                                        });
                                                }, '. ($slide_speed / 2) .');

                                                updateCurrentSlide(1);
                                                
                                        }


                                        slider.addEventListener("mouseenter", function () {
                                                isMouseOver = true;
                                        });

                                        slider.addEventListener("mouseleave", function () {
                                                isMouseOver = false;
                                        });

                                        let isDown = false;
                                        let startX;
                                        let slideMove = 0;
                                        let currentSlide = 0;

                                        function updateDots() {
                                                if (dots[currentSlide]) {
                                                        dots.forEach(dot => dot.classList.remove("active"));
                                                        dots[currentSlide].classList.add("active");
                                                }
                                        }

                                        function updateCurrentSlide(delta) {
                                                currentSlide = (currentSlide + delta + dots.length) % dots.length;
                                                updateDots();
                                        }

                                        slider.addEventListener("mousedown", (e) => {
                                                isDown = true;
                                                slider.classList.add("active");
                                                startX = e.pageX - slider.offsetLeft;
                                        });

                                        slider.addEventListener("mouseleave", () => {
                                                isDown = false;
                                                slider.classList.remove("active");
                                                resetSlider(slideMove);
                                                slideMove = 0;
                                        });

                                        slider.addEventListener("mouseup", () => {
                                                isDown = false;
                                                slider.classList.remove("active");
                                                resetSlider(slideMove);
                                                slideMove = 0;
                                        });

                                        slider.addEventListener("mousemove", (e) => {
                                                if (!isDown) return;
                                                e.preventDefault();
                                                let preventDefaultNextTime = true;

                                                $(e.target).parent().on("click", function (event) {
                                                        if (preventDefaultNextTime) {
                                                                event.preventDefault();
                                                                preventDefaultNextTime = true;

                                                                setTimeout(() => {
                                                                        preventDefaultNextTime = false;
                                                                }, 200);
                                                        }
                                                });
                                                const x = e.pageX - slider.offsetLeft;
                                                const walk = (x - startX);
                                                const transformWalk = slidesTransform - walk;
                                                slides.style.transform = `translateX(-${transformWalk}px)`;
                                                slideMove = (walk / imageWidth);
                                        });

                                        // Kod obsługujący przesuwanie dotykiem na urządzeniach mobilnych

                                        slider.addEventListener("touchstart", (e) => {
                                                isDown = true;
                                                slider.classList.add("active");
                                                startX = e.touches[0].pageX - slider.offsetLeft;
                                        });

                                        slider.addEventListener("touchend", () => {
                                                isDown = false;
                                                slider.classList.remove("active");
                                                resetSlider(slideMove);
                                                slideMove = 0;
                                        });

                                        slider.addEventListener("touchmove", (e) => {
                                                if (!isDown) return;
                                                if (!e.cancelable) return;

                                                const x = e.touches[0].pageX - slider.offsetLeft;
                                                const walk = (x - startX);
                                                slides.style.transform = `translateX(-${slidesTransform - walk}px)`;
                                                slideMove = (walk / imageWidth);
                                        });

                                        const resetSlider = (slideWalk) => {
                                                const slidesMove = Math.abs(Math.round(slideWalk));
                                                for (let i = 0; i < slidesMove; i++) {
                                                        if (slideWalk > 0) {
                                                                const lastSlide = slides.lastElementChild;
                                                                if (lastSlide) {
                                                                        lastSlide.classList.add("last-slide");
                                                                        slides.insertBefore(lastSlide, slides.firstChild);
                                                                        lastSlide.classList.remove("last-slide");
                                                                        
                                                                        updateCurrentSlide(1);
                                                                        
                                                                }
                                                        } else {
                                                                const firstSlide = slides.firstElementChild;
                                                                if (firstSlide) {
                                                                        firstSlide.classList.add("first-slide");
                                                                        slides.appendChild(firstSlide);
                                                                        firstSlide.classList.remove("first-slide");
                                                                       
                                                                        updateCurrentSlide(1);
                                                                        
                                                                }
                                                        }
                                                }
                                                slides.style.transform = `translateX(-${slidesTransform}px)`;
                                        }

                                        setInterval(function () {
                                                if (!isMouseOver) {
                                                        nextSlide();
                                                }
                                        }, '. $slide_speed .');
                                }
                                });
                 
                </script>'; 
                return $output;
        }

        /**
         * Prepares and returns the HTML output for the slider.
         * 
         * @param array $media_url_array Array of media URLs or structures containing URLs and additional data.
         * @param int $slide_speed Speed of the slide transition.
         * @return string The HTML output for the slider.
         */
        public static function sliderOutput($media_url = [], $slide_speed = 3000, $images_options = "") {

                /*Random "id" if there is more than one element on page*/  
                $id_rnd = rand(10000, 99999);

                $output = '';
            
                /*Counting min elements for the gallery slider*/   
                if(count($media_url) > 10){
                    $max_image = floor(count($media_url) * 1.5);
                    $min_image = floor(-count($media_url) / 2);
                } else {
                    $max_image = count($media_url) * 2; 
                    $min_image = -count($media_url);
                }
            
                $output = self::createDOM($id_rnd, $media_url, $min_image, $max_image, $images_options);
            
                $output .= self::generateScript($id_rnd, $media_url, $min_image, $slide_speed);
            
                return $output;
        }
} 



