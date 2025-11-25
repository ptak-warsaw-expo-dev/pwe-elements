<?php

class PWESpeakersSlider {
        
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
        private static function createDOM($id_rnd, $media_url, $min_image, $max_image, $options) {
                
                $info_speakers_dots_off = (!empty($options[0]['info_speakers_dots_off'])) ? $options[0]['info_speakers_dots_off'] : '';

                $accent_color = do_shortcode('[trade_fair_accent]');
                $lect_color = (!empty($images_options[0]['lect_color'])) ? $images_options[0]['lect_color'] : $accent_color;
                $bio_color = (!empty($images_options[0]['bio_color'])) ? $images_options[0]['bio_color'] : $accent_color;

                $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

                $output = '
                <style>
                        #PWESpeakersSlider-'. $id_rnd .' {
                                width: 100%;
                                overflow: hidden;
                                margin: 0 !important;
                                -webkit-touch-callout: none; 
                                -webkit-user-select: none;
                                -khtml-user-select: none;
                                -moz-user-select: none; 
                                -ms-user-select: none; 
                                user-select: none;
                                opacity: 0;
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .slides {
                                display: flex;
                                align-items: stretch;
                                justify-content: space-between;
                                margin: 0 !important;
                                min-height: 0 !important;
                                min-width: 0 !important;
                                pointer-events: auto;
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .pwe-speaker {
                                padding:0;
                                object-fit: contain !important;
                                flex: 1;
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .pwe-speaker :is(span, p, h2, h3, h4, h5) {
                                margin: 0 !important;
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .pwe-speaker-container{
                                margin: 5px !important;
                                padding: 10px;
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .pwe-speaker-img {
                                background-repeat: no-repeat;
                                background-position: center;
                                background-size: cover;
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .pwe-speaker-name {
                                font-size: 24px;
                                color: '. $lect_color .';
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .pwe-speaker-excerpt {
                                color: '. $bio_color .';
                        }
                        @keyframes slideAnimation {
                                from {
                                        transform: translateX(100%);
                                }
                                to {
                                        transform: translateX(0);
                                }
                        }
                        #PWESpeakersSlider-'. $id_rnd .' .slides .slide {
                                animation: slideAnimation 0.5s ease-in-out;
                        }
                </style>';

                if ($options[0]['display_items_desktop'] == 1 || ($options[0]['display_items_tablet'] == 1 && $mobile) || ($options[0]['display_items_mobile'] == 1 && $mobile)) {
                        $speaker_max_width_img = "";
                        $speaker_max_width_img = (empty($options[0]['max_width_img'])) ? "150px;" : $options[0]['max_width_img'];
                        
                        $output .= '
                        <style>
                                #PWESpeakersSlider-'. $id_rnd .' .pwe-speaker-img {
                                        max-width: '. $speaker_max_width_img .';
                                }
                        </style>';
                }

                if ($options[0]['btn_hide'] == true) {
                        $output .= '
                        <style>
                                #PWESpeakersSlider-'. $id_rnd .' .slides {
                                        align-items: start !important;
                                }
                        </style>';
                }

                $output .= '
                <div id="PWESpeakersSlider-'. $id_rnd .'" class="pwe-speakers-slider">
                        <div class="slides">';
                        
                        for ($i = $min_image; $i < ($max_image); $i++) {

                                $id_rnd_slide = rand(10000, 99999);
                                
                                if($i<0){
                                        $elNumber = count($media_url) + $i;
                                        $imageStyles = "background-image:url('".$media_url[$elNumber]['img']."');";
                                } elseif($i>=0 && $i<(count($media_url))){
                                        $elNumber = $i;
                                        $imageStyles = "background-image:url('".$media_url[$elNumber]['img']."');";
                                } elseif($i>=(count($media_url))){
                                        $elNumber = ($i - count($media_url));
                                        $imageStyles = "background-image:url(".$media_url[$elNumber]['img'].");";
                                }

                                if (is_array($media_url[$elNumber]) && !empty($media_url[$elNumber]['img']) && !empty($media_url[$elNumber]['name'])){
                                        $speakerUrl = $media_url[$elNumber]['img'];
                                        $speakerName = $media_url[$elNumber]['name'];
                                        $speakerBio = $media_url[$elNumber]['bio'];
                                        $speakerBioExcerpt = $media_url[$elNumber]['bio_excerpt'];

                                        $output .= '<div class="pwe-speaker-'. $id_rnd_slide .' pwe-speaker pwe-speaker-container" href="'. $speakerUrl .'">
                                                        <div class="pwe-speaker-thumbnail">
                                                            <div class="pwe-speaker-img" style="'.$imageStyles.'"></div>
                                                        </div> 
                                                        <h5 class="pwe-speaker-name">'. $speakerName .'</h5>';
                                                        if (!empty($speakerBioExcerpt)) {
                                                                $output .= '<div class="pwe-speaker-excerpt">'. $speakerBioExcerpt .'</div>';
                                                        }
                                                        if (!empty($speakerBio)) {
                                                                $output .= '<div style="display: none;" class="pwe-speaker-desc">'. $speakerBio .'</div>';
                                                        }
                                                        if(!empty($speakerBio)){
                                                            $output .='<button class="pwe-speaker-btn">BIO</button>';
                                                        }
                                        $output .= '</div>';
                                }
                        }

                $output .= '</div>';

                if ($info_speakers_dots_off != true) {

                        $output .= '
                        <style>
                                #PWESpeakersSlider-'. $id_rnd .' .dots-container {
                                        display: none;
                                        text-align: center;
                                        margin-top: 18px !important;
                                }
                                #PWESpeakersSlider-'. $id_rnd .' .dot {
                                        display: inline-block;
                                        width: 15px;
                                        height: 15px;
                                        border-radius: 50%;
                                        background-color: #bbb;
                                        margin: 0 5px;
                                        cursor: pointer;
                                }
                                #PWESpeakersSlider-'. $id_rnd .' .dot.active {
                                        background-color: '. $accent_color .';
                                }   
                        </style>';
                        
                        $output .= '
                        <div class="dots-container">
                                <span class="dot active"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                        </div>';
                }
                
                $output .= '</div>';
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
        private static function generateScript($id_rnd, $media_url, $min_image, $slide_speed, $options) {
                $breakpoint_tablet = str_replace("px", "", $options[0]['breakpoint_tablet']);
                $breakpoint_mobile = str_replace("px", "", $options[0]['breakpoint_mobile']);

                $breakpoint_tablet = (empty($breakpoint_tablet)) ? '768' : $breakpoint_tablet;
                $breakpoint_mobile = (empty($breakpoint_mobile)) ? '420' : $breakpoint_mobile;

                $display_items_desktop_multi =(empty($options[0]['display_items_desktop'])) ? "3" : $options[0]['display_items_desktop'];
                $display_items_tablet_multi =(empty($options[0]['display_items_tablet'])) ? "2" : $options[0]['display_items_tablet'];
                $display_items_mobile_multi =(empty($options[0]['display_items_mobile'])) ? "1" : $options[0]['display_items_mobile'];

                $media_url_count = count($media_url);
                $min_image_adjusted = -$min_image;

                $output = '
                <script>
                        jQuery(function ($) {                         
                                const slider = document.querySelector("#PWESpeakersSlider-'. $id_rnd .'");
                                const slides = slider.querySelector(".slides");
                                const images = slides.querySelectorAll(".pwe-speaker");     
                                
                                const dotsContainer = slider.querySelector(".dots-container");
                                const dots = slider.querySelectorAll(".dots-container .dot");

                                let isMouseOver = false;
                                let isDragging = false;
                                
                                let imagesMulti = "";
                                const slidesWidth = slides.clientWidth;
                                
                                if (slidesWidth < '. $breakpoint_mobile .') {
                                        imagesMulti = '. $display_items_mobile_multi .';
                                } else if (slidesWidth < '. $breakpoint_tablet .') {
                                        imagesMulti = '. $display_items_tablet_multi .';
                                } else {
                                        imagesMulti = '. $display_items_desktop_multi .';
                                }
                                
                                if(imagesMulti >=  '. $media_url_count .'){
                                        $("#PWESpeakersSlider-'. $id_rnd .' .slides").each(function(){
                                                $(this).css("justify-content", "center");
                                                if ($(this).children().length > '. $media_url_count .'){
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
                                        const slidesTransform =  (imageWidth + 10) * '. $min_image_adjusted .';

                                        slides.style.transform = `translateX(-${slidesTransform}px)`;

                                        if (dotsContainer) {
                                                dotsContainer.style.display = "block";
                                        }

                                        function nextSlide() {
                                                slides.querySelectorAll("#PWESpeakersSlider-'. $id_rnd .' .pwe-speaker-container").forEach(function(image){
                                                        image.classList.add("slide");
                                                })

                                                const firstSlide = slides.firstElementChild;
                                                if (firstSlide) {
                                                        firstSlide.classList.add("first-slide");

                                                        // Przesuwamy pierwszy slajd na koniec
                                                        slides.appendChild(firstSlide);

                                                        setTimeout(() => {
                                                        firstSlide.classList.remove("first-slide");
                                                        }, '. ($slide_speed / 2) .');
                                                }

                                                setTimeout(function() {
                                                        slides.querySelectorAll("#PWESpeakersSlider-'. $id_rnd .' .pwe-speaker-container").forEach(function(image){
                                                                image.classList.remove("slide");
                                                        })
                                                }, '. ($slide_speed / 2) .');

                                                updateCurrentSlide(1);
                                        }                       

                                        slider.addEventListener("mousemove", function() {
                                                isMouseOver = true;
                                        });
                                        
                                        slider.addEventListener("mouseleave", function() {
                                                isMouseOver = false;
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
                                                for(i = 0; i < slidesMove; i++){
                                                        if (slideWalk > 0) {
                                                                const lastSlide = slides.lastElementChild;
                                                                if (lastSlide) {
                                                                        lastSlide.classList.add("last-slide");
                                                                        slides.insertBefore(lastSlide, slides.firstChild);
                                                                        lastSlide.classList.remove("last-slide");
                                                                        
                                                                        updateCurrentSlide(-1);
                                                                        
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
                                        setInterval(function() {
                                                if(!isMouseOver) { 
                                                        nextSlide()
                                                }
                                        }, '. $slide_speed .');
                                }

                                if (slider) {
                                        slider.style.opacity = 1;
                                        slider.style.transition = "opacity 0.3s ease";
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
        public static function sliderOutput($media_url, $slide_speed = 3000, $info_speakers_options) {
                $info_speakers_options_json = json_encode($info_speakers_options);
                $options = json_decode($info_speakers_options_json, true);

                $output = '';

                /*Random "id" if there is more than one element on page*/  
                $id_rnd = rand(10000, 99999);
                
                /*Counting min elements for the gallery slider*/   
                if(count($media_url) > 10){
                        $max_image = floor(count($media_url) * 1.5);
                        $min_image = floor(-count($media_url) / 2);
                } else {
                        $max_image = count($media_url) * 2; 
                        $min_image = -count($media_url);
                }
                
                $output = self::createDOM($id_rnd, $media_url, $min_image, $max_image, $options);
                
                $output .= self::generateScript($id_rnd, $media_url, $min_image, $slide_speed, $options);
                
                return $output;
        }
} 
