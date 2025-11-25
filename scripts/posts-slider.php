<?php

class PWEPostsSlider {

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
        private static function createDOM($id_rnd, $media_url, $min_image, $max_image, $full_mode) {

                $accent_color = do_shortcode('[trade_fair_accent]');

                $output = '
                <style>
                        #PWEPostsSlider-'.$id_rnd.' {
                                transition: .3s ease;
                                opacity: 0;
                        }
                        .pwe-posts .pwe-posts-slider {
                                width: 100%;
                                overflow: hidden !important;
                                margin: 0 !important;
                                padding-top: 18px;
                        }
                        .pwe-posts .slides {
                                display: flex;
                                align-items: flex-start !important;
                                justify-content: space-between;
                                min-height : 0 !important;
                                min-width : 0 !important;
                                pointer-events: auto;
                                gap: 19px;
                        }
                        .pwe-posts .pwe-slide {
                                min-height: 350px;
                                padding: 0;
                        }
                        @keyframes slideAnimation {
                                from {
                                        transform: translateX(100%);
                                }
                                to {
                                        transform: translateX(0);
                                }
                        }
                        .pwe-posts .slides .slide {
                                animation: slideAnimation 0.5s ease-in-out;
                        }
                        @media (max-width: 1200px) {
                                .pwe-posts .pwe-posts-slider {
                                        overflow: visible !important;
                                }
                                .pwe-posts .slides {
                                        justify-content: space-between;
                                }
                        }
                </style>';

                if (isset($posts_full_width) && $posts_full_width === 'true') {
                        $output .= '
                        <style>
                                .pwe-posts-wrapper {
                                        overflow: visible !important;
                                }
                        </style>';
                }

                $output .= '
                <div id="PWEPostsSlider-'. $id_rnd .'" class="pwe-posts-slider">
                        <div class="slides">';
                        $load_more = get_locale() == 'pl_PL' ? 'CZYTAJ WIĘCEJ' : 'READ MORE';

                        for ($i = $min_image; $i < ($max_image); $i++) {
                                if($i<0){
                                        $imgNumber = count($media_url) + $i;
                                        $imageStyles = "background-image:url('".$media_url[$imgNumber]['img']."');";
                                } elseif( $i>=0 && $i < count($media_url)) {
                                        $imgNumber = $i;
                                        $imageStyles = "background-image:url('".$media_url[$imgNumber]['img']."');";
                                } elseif ($i >= count($media_url)) {
                                        $imgNumber = $i - count($media_url);
                                        $imageStyles = "background-image:url(".$media_url[$imgNumber]['img'].");";
                                }

                                if ($full_mode != 'true' && is_array($media_url[$imgNumber]) && !empty($media_url[$imgNumber]['img']) && !empty($media_url[$imgNumber]['link']) && !empty($media_url[$imgNumber]['title'])){
                                        $imageUrl = $media_url[$imgNumber]['link'];
                                        $imageTitle = $media_url[$imgNumber]['title'];
                                        $output .= '
                                        <a class="pwe-post" href="'. $imageUrl .'">
                                                <div class="pwe-post-thumbnail">
                                                        <div class="image-container" style="'. $imageStyles .'"></div>
                                                </div>
                                                <h5 class="pwe-post-title">'. $imageTitle .'</h5>
                                        </a>';
                                } else {
                                        $imageUrl = $media_url[$imgNumber]['link'];
                                        $imageSrc = $media_url[$imgNumber]['img'];
                                        $imageTitle = $media_url[$imgNumber]['title'];
                                        $imageExcerpt = !empty($imageExcerpt) ? $media_url[$imgNumber]['excerpt'] : '';
                                        $imageDate = !empty($imageDate) ? $media_url[$imgNumber]['date'] : '';
                                        if (!empty($imageSrc)) {
                                                $output .= '
                                                <div class="pwe-slide">
                                                        <a class="pwe-post" href="'. $imageUrl .'">
                                                                <div class="pwe-post-thumbnail">
                                                                        <div class="image-container" style="'. $imageStyles .'"></div>
                                                                        <p class="pwe-post-date">'. $imageDate .'</p>
                                                                </div>
                                                                <h5 class="pwe-post-title">'. $imageTitle .'</h5>
                                                                <p class="pwe-post-excerpt">'. $imageExcerpt .'</p>
                                                                <button class="pwe-post-btn">' . $load_more . '</button>
                                                        </a>
                                                </div>';
                                        }
                                }
                        }

                        $output .= '
                        </div>';

                        $output .= '
                        <style>
                                .pwe-posts .dots-container {
                                        display: none;
                                        text-align: center;
                                        margin-top: 36px;
                                }
                                .pwe-posts .dot {
                                        display: inline-block;
                                        width: 15px;
                                        height: 15px;
                                        border-radius: 50%;
                                        background-color: #bbb;
                                        margin: 0 5px;
                                        cursor: pointer;
                                }
                                .pwe-posts .dot.active {
                                        background-color: '. $accent_color .';
                                }
                        </style>

                        <div class="dots-container">
                                <span class="dot active"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                        </div>';

                $output .='</div>';
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
        private static function generateScript($id_rnd, $media_url, $min_image, $slide_speed, $full_mode) {
                $media_url_count = count($media_url);
                $min_image_adjusted = -$min_image;

                $output = '
                <script>
                        jQuery(function ($) {
                                const slider = document.querySelector("#PWEPostsSlider-'.$id_rnd.'");
                                const slides = document.querySelector("#PWEPostsSlider-'.$id_rnd.' .slides");
                                const images = document.querySelectorAll("#PWEPostsSlider-'.$id_rnd.' .slides .pwe-post");
                                const dotsContainer = slider.querySelector("#PWEPostsSlider-'.$id_rnd.' .dots-container");
                                const dots = slider.querySelectorAll("#PWEPostsSlider-'.$id_rnd.' .dots-container .dot");

                                slider.style.opacity = 1;
                                slider.style.transition = "opacity 0.3s ease";

                                let isMouseOver = false;
                                let isDragging = false;

                                let imagesMulti = "";
                                const slidesWidth = slider.clientWidth;';

                                if ($full_mode != 'true'){
                                        $output .= '
                                        if (slidesWidth < 400) {
                                                imagesMulti = 1;
                                        } else if (slidesWidth < 600) {
                                                imagesMulti = 2;
                                        } else if (slidesWidth < 900) {
                                                imagesMulti = 3;
                                        } else if (slidesWidth < 1100) {
                                                imagesMulti = 4;
                                        } else {
                                                imagesMulti = 4;
                                        }';
                                } else {
                                        $output .= '
                                        if (slidesWidth < 400) {
                                                imagesMulti = 1;
                                        } else if (slidesWidth < 600) {
                                                imagesMulti = 2;
                                        } else if (slidesWidth < 900) {
                                                imagesMulti = 3;
                                        } else {
                                                imagesMulti = 3;
                                        }';
                                }

                                $output .= '
                                if(imagesMulti >=  '. $media_url_count .'){
                                        $("#PWEPostsSlider-'. $id_rnd .' .slides").each(function(){
                                                $(this).css("justify-content", "center");
                                                if ($(this).children().length > '. $media_url_count .'){
                                                        $(this).children().slice('. $media_url_count .').remove();
                                                };
                                        });';

                                        if ($full_mode != 'true'){
                                                $output .= 'const imageWidth = Math.floor((slidesWidth - imagesMulti * 10) / imagesMulti);';
                                        } else {
                                                $output .= 'const imageWidth = Math.floor(((slidesWidth - imagesMulti * 10) / imagesMulti) - 10);';
                                        }

                                        $output .= '
                                        images.forEach((image) => {
                                                image.style.minWidth = imageWidth + "px";
                                                image.style.maxWidth = imageWidth + "px";
                                        });
                                } else {';

                                        if ($full_mode != 'true'){
                                                $output .= 'const imageWidth = Math.floor((slidesWidth - imagesMulti * 10) / imagesMulti);';
                                        } else {
                                                $output .= 'const imageWidth = Math.floor(((slidesWidth - imagesMulti * 10) / imagesMulti) - 10);';
                                        }

                                        $output .= '
                                        images.forEach((image) => {
                                                image.style.minWidth = imageWidth + "px";
                                                image.style.maxWidth = imageWidth + "px";
                                        });';

                                        if ($full_mode != 'true'){
                                                $output .= 'const slidesTransform = (imageWidth + 18) * '. $min_image_adjusted .';';
                                        } else {
                                                $output .= 'const slidesTransform = (imageWidth + 18) * '. $min_image_adjusted .';';
                                        }

                                        $output .= '
                                        slides.style.transform = `translateX(-${slidesTransform}px)`;

                                        if (dotsContainer) {
                                                dotsContainer.style.display = "block";
                                        }

                                        function nextSlide() {
                                                slides.querySelectorAll("#PWEPostsSlider-'. $id_rnd .' .pwe-post").forEach(function(image){
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
                                                        slides.querySelectorAll("#PWEPostsSlider-'. $id_rnd .' .pwe-post").forEach(function(image){
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

                                        const links = document.querySelectorAll("#PWEPostsSlider-'. $id_rnd .' a");
                                        links.forEach(link => {
                                                link.addEventListener("mousedown", (e) => {
                                                e.preventDefault();
                                                });
                                        });

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
        public static function sliderOutput($media_url, $slide_speed = 3000, $full_mode = "") {
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

                $output .= self::createDOM($id_rnd, $media_url, $min_image, $max_image, $full_mode);

                $output .= self::generateScript($id_rnd, $media_url, $min_image, 3000, $full_mode);

                return $output;
        }
}

?>