<?php

class PWEMediaGallerySlider {

    /**
     * Initializes the slider.
     */
    public function __construct() {}

        /**
     * Prepares and returns the HTML output for the slider.
     * 
     * @param array $media_url - Array of media URLs or structures containing URLs and additional data.
     * @param int $slide_speed - Speed of the slide transition.
     * @return string The HTML output for the slider.
     */
    public static function sliderOutput($media_url, $slide_speed = 3000) {
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
        
        $output .= self::createDOM($id_rnd, $media_url, $min_image, $max_image);
        
        $output .= self::generateScript($id_rnd, $media_url, $min_image, $max_image, 3000);
        
        return $output;
    }

    /**
     * Creates the DOM structure for the slider.
     * 
     * @param int $id_rnd - Random ID for the slider element.
     * @param array $media_url - Array of media URLs.
     * @param int $min_image - Minimum image index.
     * @param int $max_image - Maximum image index.
     * @return string The DOM structure as HTML.
     */
    private static function createDOM($id_rnd, $media_url, $min_image, $max_image) {
            
        $output = '
            <style>
                @keyframes slideAnimation {
                    from {
                        transform: translateX(100%);
                    }
                    to {
                        transform: translateX(0);
                    }
                }
                #PWEMediaGallerySlider-'. $id_rnd .' {
                        width: 100%;
                        overflow: hidden !important;
                        margin: 0 !important;
                }
                #PWEMediaGallerySlider-'. $id_rnd .' .slides {
                        display: flex;
                        align-items: flex-start !important;
                        justify-content: space-between;
                        margin: 5px !important;
                        pointer-events: auto;
                }
                #PWEMediaGallerySlider-'. $id_rnd .' .pwe-media-gallery-image {
                        margin: 5px !important;
                        padding: 0 10px;
                }
                #PWEMediaGallerySlider-'. $id_rnd .' .pwe-media-gallery-image img {
                        border-radius: 18px;
                }
                #PWEMediaGallerySlider-'. $id_rnd .' .slide {
                        animation: slideAnimation 0.5s ease-in-out;
                }
                #PWEMediaGallerySlider-'. $id_rnd .' .slide img {
                        object-fit: cover;
                }
                 
            </style>';

        if (isset($media_gallery_full_width) === 'true') {
                $output .= '<style>
                                #PWEMediaGallerySlider-'. $id_rnd .' .pwe-media-gallery-slider {
                                        overflow: visible !important;
                                }
                            </style>';
        }

        $output .= '
            <div id="PWEMediaGallerySlider-'. $id_rnd .'" class="pwe-media-gallery-slider">
                <div class="slides">';
                    
                for ($i = $min_image; $i < ($max_image); $i++) {
                        if($i<0){
                                $imgNumber = count($media_url) + $i;
                        } elseif( $i>=0 && $i < count($media_url)) {
                                $imgNumber = $i;
                        } elseif ($i >= count($media_url)) {
                                $imgNumber = $i - count($media_url);
                        }

                        if(is_array($media_url[$imgNumber]) && !empty($media_url[$imgNumber]['img'])){
                                $imageUrl = $media_url[$imgNumber]['img'];
                                $imageLink = $media_url[$imgNumber]['link'];
                                $aspectRatio = $media_url[$imgNumber]['aspect-ratio'];

                                if ($imageLink != "") {
                                        $output .= '<a href="'. $imageLink .'">
                                                        <div class="pwe-media-gallery-image">
                                                            <img src="'. $imageUrl .'" style="'. $aspectRatio .'">
                                                        </div>
                                                    </a>';  
                                } else {
                                        $output .= '<div class="pwe-media-gallery-image">
                                                        <img src="'. $imageUrl .'" style="'. $aspectRatio .'">
                                                    </div>';  
                                }      
                        } 
                        
                        if(is_array($media_url[$imgNumber]) && !empty($media_url[$imgNumber]["src_mini"])){
                                $imageUrl = $media_url[$imgNumber]['src_mini'];

                                $output .= '<div class="pwe-media-gallery-image">
                                        <img src="'. $imageUrl .'">
                                </div>';  
                                     
                        }          
                }

        $output .='</div></div>';
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

                if (!empty($media_url[$imgNumber]["src_mini"])) {
                        $imageUrl = $media_url[$imgNumber]['src_mini'];
                }

                $count_visible_thumbs = [
                        'desktop' => isset($media_url[$imgNumber]['count-visible-thumbs-desktop']) ? $media_url[$imgNumber]['count-visible-thumbs-desktop'] : 3,
                        'tablet' => isset($media_url[$imgNumber]['count-visible-thumbs-tablet']) ? $media_url[$imgNumber]['count-visible-thumbs-tablet'] : 2,
                        'mobile' => isset($media_url[$imgNumber]['count-visible-thumbs-mobile']) ? $media_url[$imgNumber]['count-visible-thumbs-mobile'] : 2
                ];
        
                $breakpoints = [
                        'tablet' => isset($media_url[$imgNumber]['breakpoint-tablet']) ? $media_url[$imgNumber]['breakpoint-tablet'] : 959, 
                        'mobile' => isset($media_url[$imgNumber]['breakpoint-mobile']) ? $media_url[$imgNumber]['breakpoint-mobile'] : 480 
                ];
                
                $count_visible_thumbs_json = (!empty($count_visible_thumbs)) ? json_encode($count_visible_thumbs) : "";
                $breakpoints_thumbs_json = (!empty($breakpoints)) ? json_encode($breakpoints) : "";         
        }

        $output = '
        <script>
        jQuery(function ($) {   
                const slider = document.querySelector("#PWEMediaGallerySlider-'.$id_rnd.'");
                const slides = slider.querySelector(".slides");
                const images = slides.querySelectorAll(".pwe-media-gallery-image");
                
                let isMouseOver = false;
                let imagesMulti = "";

                const slidesWidth = slider.clientWidth;
                
                if ("'. $imageUrl .'" != "") {
                        imagesMulti = 1;
                } else {
                        const countVisibleThumbs = ' . $count_visible_thumbs_json . ';
                        const breakpointsThumbs = ' . $breakpoints_thumbs_json . ';  
                        
                        // Logika wybierająca ilość obrazów do wyświetlenia w zależności od szerokości kontenera
                        if (slidesWidth <= breakpointsThumbs.mobile) {
                                imagesMulti = countVisibleThumbs.mobile;
                        } else if (slidesWidth > breakpointsThumbs.mobile && slidesWidth <= breakpointsThumbs.tablet) {
                                imagesMulti = countVisibleThumbs.tablet;
                        } else if (slidesWidth > breakpointsThumbs.tablet) {
                                imagesMulti = countVisibleThumbs.desktop;
                        }
                }
                
                // Jeżeli liczba obrazków mniejsza niż ilość wyświetlonych obrazków w jednym rzędzie
                if(imagesMulti >=  '. $media_url_count .'){

                        $("#PWEMediaGallerySlider-'. $id_rnd .' .slides").each(function(){
                                $(this).css("justify-content", "center"); // Wyśrodkowanie obrazów w kontenerze
                                if ($(this).children().length > '. $media_url_count .'){
                                        $(this).children().slice('. $media_url_count .').remove(); // Usunięcie nadmiarowych obrazów
                                };
                        });

                        // Obliczenie szerokości jednego obrazu w kontenerze
                        const imageWidth = Math.floor((slidesWidth - imagesMulti * 10) / imagesMulti);

                        // Ustawienie minimalnej i maksymalnej szerokości dla obrazów
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
                
                        // Obliczenie transformacji przesunięcia slajdów
                        let slidesTransform = (imageWidth + 10) * '. $min_image_adjusted .';
                
                        // Ustawienie transformacji CSS na slajdach
                        slides.style.transform = `translateX(-${slidesTransform}px)`; 
                
                        // Funkcja odpowiedzialna za automatyczne przesuwanie na następny slajd
                        function nextSlide() {
                                slides.querySelectorAll("#PWEMediaGallerySlider-'. $id_rnd .' .pwe-media-gallery-image").forEach(function(image){
                                        image.classList.add("slide"); // Dodaje klasę animacji do obrazów
                                })
                                slides.firstChild.classList.add("first-slide"); // Oznacza pierwszy slajd
                                const firstSlide = slides.querySelector(".first-slide");  
                        
                                slides.appendChild(firstSlide); // Przenosi pierwszy slajd na koniec listy
                        
                                firstSlide.classList.remove("first-slide"); // Usuwa oznaczenie pierwszego slajdu
                        
                                setTimeout(function() {
                                        slides.querySelectorAll("#PWEMediaGallerySlider-'. $id_rnd .' .pwe-media-gallery-image").forEach(function(image){
                                        image.classList.remove("slide"); // Usuwa klasę animacji
                                        })
                                }, '.($slide_speed / 2).'); // Czas po jakim usunięta zostaje klasa animacji
                        }                       
                
                        // Obsługa zdarzeń związanych z ruchem myszy nad sliderem
                        slider.addEventListener("mousemove", function() {
                                isMouseOver = true; // Ustawienie flagi na prawda gdy mysz jest nad sliderem
                        });
                                
                        slider.addEventListener("mouseleave", function() {
                                isMouseOver = false; // Ustawienie flagi na fałsz gdy mysz opuści slider
                        });
                        
                        let isDown = false; // Flaga wskazująca, czy przycisk myszy jest wciśnięty
                        let startX; // Zmienna przechowująca początkową pozycję X myszy
                        let startY; // Zmienna przechowująca początkową pozycję Y myszy
                        let slideMove = 0; // Zmienna przechowująca przesunięcie slajdu

                        const links = document.querySelectorAll("#PWEMediaGallerySlider-'. $id_rnd .' .pwe-media-gallery-image");
                        links.forEach(link => {
                                link.addEventListener("mousedown", (e) => {
                                e.preventDefault();
                                });
                        });
                        
                        // Obsługa zdarzenia wciśnięcia przycisku myszy
                        slider.addEventListener("mousedown", (e) => {
                                isDown = true; // Ustawienie flagi wciśnięcia na prawda
                                window.isDraggingMedia = false; // Flaga globalna, że nie ma przeciągania
                                slider.classList.add("active"); // Dodanie klasy "active" do slidera
                                startX = e.pageX - slider.offsetLeft; // Ustawienie początkowej wartości X myszy
                        });
                        
                        // Obsługa zdarzenia opuszczenia slidera przez mysz
                        slider.addEventListener("mouseleave", () => {
                                isDown = false; // Reset flagi wciśnięcia
                                slider.classList.remove("active"); // Usunięcie klasy "active"
                                resetSlider(slideMove); // Reset pozycji slajdu
                                slideMove = 0; // Reset przesunięcia slajdu
                        });
                        
                        // Obsługa zdarzenia zwolnienia przycisku myszy
                        slider.addEventListener("mouseup", () => {
                                isDown = false; // Reset flagi wciśnięcia
                                slider.classList.remove("active"); // Usunięcie klasy "active"
                                resetSlider(slideMove); // Reset pozycji slajdu
                                slideMove = 0; // Reset przesunięcia slajdu
                        });
                        
                        // Obsługa zdarzenia ruchu myszy nad sliderem
                        slider.addEventListener("mousemove", (e) => {
                                if (!isDown) return; // Jeśli przycisk myszy nie jest wciśnięty, przerwij funkcję
                                window.isDraggingMedia = true; // Ustawienie globalnej flagi przeciągania
                                e.preventDefault(); // Zapobieganie domyślnemu zachowaniu
                                let preventDefaultNextTime = true; // Ustawienie flagi zapobiegającej domyślnemu zachowaniu przy kolejnym zdarzeniu
                        
                                $(e.target).parent().on("click", function(event) {
                                        if (preventDefaultNextTime) {
                                        event.preventDefault(); // Zapobieganie domyślnemu zachowaniu przy kliknięciu
                                        preventDefaultNextTime = true;
                        
                                        setTimeout(() => {
                                                preventDefaultNextTime = false; // Reset flagi po określonym czasie
                                        }, 200);
                                        }
                                });
                        
                                const x = e.pageX - slider.offsetLeft; // Aktualna pozycja X myszy
                                const walk = (x - startX); // Obliczenie przesunięcia
                                const transformWalk = slidesTransform - walk; // Obliczenie nowej transformacji przesunięcia
                                slides.style.transform = `translateX(-${transformWalk}px)`; // Zastosowanie transformacji do stylów
                                slideMove = (walk / imageWidth); // Obliczenie przesunięcia w jednostkach obrazu
                        });
                        
                        // Kod obsługujący przesuwanie dotykiem na urządzeniach mobilnych
                        slider.addEventListener("touchstart", (e) => {
                                isDown = true; // Ustawienie flagi wciśnięcia na prawda
                                slider.classList.add("active"); // Dodanie klasy "active" do slidera
                                startX = e.touches[0].pageX - slider.offsetLeft; // Ustawienie początkowej wartości X dotyku
                                startY = e.touches[0].pageY; // Ustawienie początkowej wartości Y dotyku
                        });
                        
                        // Obsługa zdarzenia zakończenia dotyku
                        slider.addEventListener("touchend", () => {
                                isDown = false; // Reset flagi wciśnięcia
                                slider.classList.remove("active"); // Usunięcie klasy "active"
                                resetSlider(slideMove); // Reset pozycji slajdu
                                slideMove = 0; // Reset przesunięcia slajdu
                        });
                        
                        // Obsługa zdarzenia ruchu dotyku
                        slider.addEventListener("touchmove", (e) => {
                                if (!isDown) return; // Jeśli dotyk nie jest aktywny, przerwij funkcję
                                if (!e.cancelable) return; // Jeśli zdarzenie nie może być anulowane, przerwij funkcję
                        
                                const x = e.touches[0].pageX - slider.offsetLeft; // Aktualna pozycja X dotyku
                                const y = e.touches[0].pageY; // Aktualna pozycja Y dotyku
                                const walk = (x - startX); // Obliczenie przesunięcia poziomego
                                const verticalDiff = Math.abs(y - startY); // Obliczenie różnicy pomiędzy początkową a aktualną pozycją Y
                        
                                if (Math.abs(walk) > verticalDiff) { // Jeśli przesunięcie poziome jest większe niż pionowe
                                        e.preventDefault(); // Zapobieganie domyślnemu zachowaniu
                                        const transformWalk = slidesTransform - walk; // Obliczenie nowej transformacji przesunięcia
                                        slides.style.transform = `translateX(-${transformWalk}px)`; // Zastosowanie transformacji do stylów
                                        slideMove = (walk / imageWidth); // Obliczenie przesunięcia w jednostkach obrazu
                                }
                        });   
                        
                        // Funkcja resetująca slider do początkowej pozycji
                        const resetSlider = (slideWalk) => {
                                const slidesMove = Math.abs(Math.round(slideWalk)); // Obliczenie ilości przesunięć do resetowania
                                for(i = 0; i < slidesMove; i++){ // Pętla wykonująca resetowanie pozycji
                                        if(slideWalk > 0){ // Jeśli przesunięcie było dodatnie
                                                slides.lastChild.classList.add("last-slide"); // Oznaczenie ostatniego slajdu
                                                const lastSlide = slides.querySelector(".last-slide");  
                                                slides.insertBefore(lastSlide, slides.firstChild); // Przeniesienie ostatniego slajdu na początek
                                                lastSlide.classList.remove("last-slide"); // Usunięcie oznaczenia ostatniego slajdu
                                        } else { // Jeśli przesunięcie było ujemne
                                                slides.firstChild.classList.add("first-slide"); // Oznaczenie pierwszego slajdu
                                                const firstSlide = slides.querySelector(".first-slide");  
                                                slides.appendChild(firstSlide); // Przeniesienie pierwszego slajdu na koniec
                                                firstSlide.classList.remove("first-slide"); // Usunięcie oznaczenia pierwszego slajdu
                                        }
                                }
                                slides.style.transform = `translateX(-${slidesTransform}px)`; // Przywrócenie początkowej transformacji
                        } 
                        
                        // Ustawienie interwału do automatycznego przesuwania slajdów, gdy mysz nie jest nad sliderem
                        setInterval(function() {
                                if(!isMouseOver) { 
                                        nextSlide() // Wywołanie funkcji przesuwającej na następny slajd
                                }
                        }, '.$slide_speed.'); // Częstotliwość przesuwania slajdów
                }
        });
                            
        </script>'; 
        return $output;
    }

} 

?>