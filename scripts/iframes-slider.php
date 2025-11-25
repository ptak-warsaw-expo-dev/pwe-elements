<?php

class PWEIframesSlider {

        /**
         * Initializes the slider.
         */
        public function __construct() {}

        /**
         * Creates the DOM structure for the slider.
         * 
         * @param int $id_rnd - Random ID for the slider element.
         * @param array $media_url - Array of media URLs.
         * @param int $min_image - Minimum image index.
         * @param int $max_image - Maximum image index.
         * @return string The DOM structure as HTML.
         */
        private static function createDOM($id_rnd, $media_url, $min_image, $max_image, $options) {

                $element_id = (!empty($options[0]['element_id'])) ? $options[0]['element_id'] : '';
                $accent_color = do_shortcode('[trade_fair_accent]');
                
                $output = '
                <style>
                        .pwelement_'. $element_id .' .pwe-videos .pwe-videos-slider {
                            position: relative;
                            width: 100%;
                            margin: 0 !important;
                            overflow: hidden;
                        }
                        .pwelement_'. $element_id .' .pwe-videos .slides {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            align-items: flex-start;
                            margin: 0 !important;
                            min-height: 0 !important;
                            min-width: 0 !important;
                            pointer-events: auto;
                        }
                        .pwelement_'. $element_id .' .pwe-videos .pwe-video-item {
                            position: relative;
                            margin: 5px;
                            padding: 10px;
                        }
                        .pwelement_'. $element_id .' .pwe-videos .pwe-video-item iframe {
                            aspect-ratio: 16 / 9 !important;
                        }
                        .pwelement_'. $element_id .' .pwe-videos .pwe-video-default {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            background-position: center;
                            background-repeat: no-repeat;
                            background-size: cover;
                            aspect-ratio: 16 / 9;
                        }
                        .pwelement_'. $element_id .' .pwe-videos .pwe-video-default img {
                            max-width: 80px;
                            cursor: pointer;
                            transition: .3s ease;
                        }
                        .pwelement_'. $element_id .' .pwe-videos .pwe-video-default img:hover {
                            transform: scale(1.1);
                        }
                        @keyframes slideAnimation {
                            from {
                                transform: translateX(100%);
                            }
                            to {
                                transform: translateX(0);
                            }
                        }
                        @keyframes slideInRight {
                                from {
                                        transform: translateX(100%);
                                }
                                to {
                                        transform: translateX(0);
                                }
                        }

                        @keyframes slideInLeft {
                                from {
                                        transform: translateX(-100%);
                                }
                                to {
                                        transform: translateX(0);
                                }
                        }
                        .pwelement_'. $element_id .' .pwe-videos .slides .slide{
                            animation: slideAnimation 0.5s ease-in-out;
                        }
                        .pwelement_'. $element_id .' .slides .slide-right {
                                animation: slideInRight 0.5s ease-in-out;
                        }
                        .pwelement_'. $element_id .' .slides .slide-left {
                                animation: slideInLeft 0.5s ease-in-out;
                        }   
                        @media (max-width: 1200px) {
                                .pwelement_'. $element_id .' .pwe-videos .pwe-videos-slider {
                                        overflow: visible;
                                }
                        }   
                </style>';


                $output .= '
                <div id="PWEIframesSlider-'. $id_rnd .'" class="pwe-videos-slider">
                        <div class="slides">';
                        
                        for ($i = $min_image; $i < ($max_image); $i++) {
                                if($i<0){
                                        $imgNumber = count($media_url) + $i;
                                } elseif( $i>=0 && $i < count($media_url)) {
                                        $imgNumber = $i;
                                } elseif ($i >= count($media_url)) {
                                        $imgNumber = $i - count($media_url);
                                }

                                if(is_array($media_url[$imgNumber]) && !empty($media_url[$imgNumber]['iframe'])){
                                        $video_iframe = $media_url[$imgNumber]['iframe'];
                                        $video_title = $media_url[$imgNumber]['title'];
                                        $video_plug = $media_url[$imgNumber]['plug'];
                                        $video_id = $media_url[$imgNumber]['id'];
                                        $video_src = $media_url[$imgNumber]['src'];
                                        $video_iframe_html = $media_url[$imgNumber]['html'];
                                        $video_default_html = $media_url[$imgNumber]['default'];

                                        $output .= '<div class="pwe-video-item">'. $video_default_html .'<p>'. $video_title .'</p></div>';
                                }
                                
                        }

                        $output .= '
                        </div>';

                        $output .= '
                        <style>
                                .pwelement_'. $element_id .' .slider-arrow {
                                        position: absolute;
                                        top: 90px;
                                        transform: translateY(-50%);
                                        background-color: rgba(0, 0, 0, 0.5);
                                        color: white;
                                        border: none;
                                        padding: 10px;
                                        cursor: pointer;
                                        z-index: 10;
                                        font-size: 30px;
                                        display: none;
                                }
                                .pwelement_'. $element_id .' #prevButton {
                                        left: -0px;
                                }
                                .pwelement_'. $element_id .' #nextButton {
                                        right: -0px;
                                }
                        </style>

                        <button id="prevButton" class="slider-arrow">❮</button>
                        <button id="nextButton" class="slider-arrow">❯</button>
                        ';
                
                        $output .= '
                        <style>
                                .pwelement_'. $element_id .' .pwe-videos .dots-container {
                                        display: none;
                                        text-align: center;
                                        margin-top: 36px;
                                }
                                .pwelement_'. $element_id .' .pwe-videos .dot {
                                        display: inline-block;
                                        width: 15px;
                                        height: 15px;
                                        border-radius: 50%;
                                        background-color: #bbb;
                                        margin: 0 5px;
                                        cursor: pointer;
                                }
                                .pwelement_'. $element_id .' .pwe-videos .dot.active {
                                        background-color: '. $accent_color .';
                                }   
                        </style>
                        
                        <div class="dots-container">
                                <span class="dot active"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                        </div>';

                $output .= '
                </div>';
                return $output;
        }

        /**
         * Generates the necessary JavaScript for the slider functionality.
         * 
         * @param int $id_rnd - Random ID for the slider element.
         * @param array $media_url - Array of media URLs.
         * @param int $min_image - Minimum image index.
         * @param int $slide_speed - Slide transition speed in milliseconds.
         * @return string The JavaScript code as HTML.
         */
        private static function generateScript($id_rnd, $media_url, $min_image, $max_image, $slide_speed) {
                $media_url_count = count($media_url);
                $min_image_adjusted = -$min_image;

                for ($i = $min_image; $i < ($max_image); $i++) {
                    if($i<0){
                            $imgNumber = count($media_url) + $i;
                    } elseif( $i>=0 && $i < count($media_url)) {
                            $imgNumber = $i;
                    } elseif ($i >= count($media_url)) {
                            $imgNumber = $i - count($media_url);
                    }

                    $video_iframe_html = $media_url[$imgNumber]['html'];
                    $video_default_html = $media_url[$imgNumber]['default'];
                    $video_title = $media_url[$imgNumber]['title'];

                    $iframes_array[] = $video_iframe_html;
                    $iframes_default_array[] = $video_default_html;
                    $iframes_title_array[] = $video_title;
                } 

                $iframes_json = json_encode($iframes_array);
                $iframes_default_json = json_encode($iframes_default_array);
                $iframes_title_json = json_encode($iframes_title_array);

                $images_multi = count($media_url) <= 2 ? 2 : 3;

                $output = '
                <script>
                        jQuery(function ($) {                         
                                const slider = document.querySelector("#PWEIframesSlider-'.$id_rnd.'");
                                const slides = document.querySelector("#PWEIframesSlider-'.$id_rnd.' .slides");
                                const images = document.querySelectorAll("#PWEIframesSlider-'.$id_rnd.' .slides .pwe-video-item");
                                const dotsContainer = slider.querySelector(".dots-container");
                                const sliderArrows = slider.querySelectorAll(".slider-arrow");
                                const dots = slider.querySelectorAll(".dots-container .dot");

                                let isMouseOver = false;
                                let isDragging = false;
                                let isAnimating = false;
                                
                                let imagesMulti = "";
                                const slidesWidth = slider.clientWidth;

                                if (slidesWidth < 600) {
                                        imagesMulti = 1;
                                } else if (slidesWidth < 959) {
                                        imagesMulti = 2;
                                } else {
                                        imagesMulti = '. $images_multi .';
                                }

                                // Change the default thumbnail to iframe
                                const iframesArray = '. $iframes_json .';
                                const defaultImageHtml = '. $iframes_default_json .';
                                const iframesTitleArray = '. $iframes_title_json .';
                                
                                images.forEach((image, index) => {
                                        image.addEventListener("click", () => {
                                                let iframeHtml = iframesArray[index];
                                                let iframeTitle = iframesTitleArray[index];
                                                image.innerHTML = `<div class="pwe-video-iframe">${iframeHtml}</div>`;
                                                if (iframeTitle !== "") {
                                                        image.innerHTML += `<p class="pwe-video-title">${iframeTitle}</p>`;
                                                }
                                        });
                                });

                                if(imagesMulti >=  '. $media_url_count .'){
                                        $("#PWEIframesSlider-'. $id_rnd .' .slides").each(function(){
                                                $(this).css("justify-content", "center");
                                                if ($(this).children().length > '. $media_url_count .'){
                                                        $(this).children().slice('. $media_url_count .').remove();
                                                };
                                        });
                                        const imageWidth = Math.floor((slidesWidth - imagesMulti * 10) / imagesMulti);
                                        images.forEach((image) => {
                                                image.style.minWidth = imageWidth + "px";
                                                image.style.maxWidth = imageWidth + "px";
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

                                        const arrowHeight = imageWidth * 0.30;

                                        sliderArrows.forEach(function(event){
                                                event.style.top = arrowHeight + "px";
                                        });

                                        sliderArrows.forEach((arrow) => {
                                                arrow.style.display = "block";
                                        });

                                        function nextSlide() {
                                                if (isAnimating) return; // Sprawdzenie czy animacja jest w trakcie
                                                isAnimating = true;

                                                slides.querySelectorAll(".pwe-video-item").forEach(function(image) {
                                                        image.classList.add("slide-right");
                                                });

                                                const firstSlide = slides.firstElementChild;
                                                if (firstSlide) {
                                                        firstSlide.classList.add("first-slide");

                                                        slides.appendChild(firstSlide);

                                                        setTimeout(() => {
                                                                firstSlide.classList.remove("first-slide");
                                                                isAnimating = false;
                                                        }, 500);
                                                } else {
                                                        isAnimating = false;
                                                }

                                                setTimeout(function () {
                                                        slides.querySelectorAll(".pwe-video-item").forEach(function (image) {
                                                                image.classList.remove("slide-right");
                                                        });
                                                }, 500);

                                                updateCurrentSlide(1);
                                        }

                                        function prevSlide() {
                                                if (isAnimating) return;
                                                isAnimating = true;

                                                slides.querySelectorAll(".pwe-video-item").forEach(function(image) {
                                                        image.classList.add("slide-left");
                                                });

                                                const lastSlide = slides.lastElementChild;

                                                if (lastSlide) {
                                                        lastSlide.classList.add("last-slide");

                                                        slides.insertBefore(lastSlide, slides.firstChild);

                                                        setTimeout(() => {
                                                                lastSlide.classList.remove("last-slide");
                                                                isAnimating = false; 
                                                        }, 500);
                                                } else {
                                                        isAnimating = false;
                                                }

                                                // Usuwamy klasę "slide" ze wszystkich obrazów po określonym czasie
                                                setTimeout(function () {
                                                        slides.querySelectorAll(".pwe-video-item").forEach(function (image) {
                                                                image.classList.remove("slide-left");
                                                        });
                                                }, 500);

                                                updateCurrentSlide(-1);
                                        }

                                        slider.addEventListener("mousemove", function() {
                                                isMouseOver = true;
                                        });
                                        
                                        slider.addEventListener("mouseleave", function() {
                                                isMouseOver = false;
                                        });

                                        $("#PWEIframesSlider-'.$id_rnd.' #prevButton").on("click", function() {
                                                prevSlide();
                                        });

                                        $("#PWEIframesSlider-'.$id_rnd.' #nextButton").on("click", function() {
                                                nextSlide();
                                        });

                                        let isDown = false;
                                        let startX;
                                        let startY;
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

                                                $(e.target).parent().on("click", function(event) {
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
                                                startY = e.touches[0].pageY;
                                                
                                        });

                                        slider.addEventListener("touchend", () => {
                                                isDown = false;
                                                slider.classList.remove("active");
                                                resetSlider(slideMove);
                                                slideMove = 0;
                                                
                                        });

                                        slider.addEventListener("touchmove", (e) => {
                                                if (!isDown) return;
                                        
                                                if (!e.cancelable) return; // Dodajemy ten warunek, aby uniknąć błędu
                                        
                                                const x = e.touches[0].pageX - slider.offsetLeft;
                                                const y = e.touches[0].pageY;
                                                const walk = (x - startX);
                                                const verticalDiff = Math.abs(y - startY);
                                        
                                                if (Math.abs(walk) > verticalDiff) { // Tylko jeśli ruch poziomy jest większy niż pionowy
                                                        e.preventDefault();
                                                        const transformWalk = slidesTransform - walk;
                                                        slides.style.transform = `translateX(-${transformWalk}px)`;
                                                        slideMove = (walk / imageWidth);
                                                }
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
                                                                       
                                                                        updateCurrentSlide(-1);
                                                                        
                                                                }
                                                        }
                                                }
                                                slides.style.transform = `translateX(-${slidesTransform}px)`;
                                        }
                                                
                                        setInterval(function() {
                                                if (!isMouseOver && !isAnimating) {
                                                        nextSlide();
                                                }
                                        }, '.$slide_speed.');
                                }
                        });                 
                </script>'; 
                return $output;
        }

        /**
         * Prepares and returns the HTML output for the slider.
         * 
         * @param array $media_url - Array of media URLs or structures containing URLs and additional data.
         * @param int $slide_speed - Speed of the slide transition.
         * @return string The HTML output for the slider.
         */
        public static function sliderOutput($media_url, $slide_speed = 3000, $options = "") {
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
                
                $output .= self::createDOM($id_rnd, $media_url, $min_image, $max_image, $options);
                
                $output .= self::generateScript($id_rnd, $media_url, $min_image, $max_image, 3000);
                
                return $output;
        }
} 

?>