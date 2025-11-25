<?php

class PWESwiperScripts {

        /**
         * Initializes the slider.
         */
        public function __construct() {}

        /**
         * Prepares and returns the scripts for the slider.
         */
        public static function swiperScripts($id = '', $pwe_element = '.pwelement', $dots_display = false, $arrows_display = false, $scrollbar_display = false, $options = null, $breakpoints_raw = '') {
            wp_enqueue_style('pwe-swiper-css', plugins_url('../assets/swiper-slider/swiper-bundle.min.css', __FILE__));
            wp_enqueue_script('pwe-swiper-js', plugins_url('../assets/swiper-slider/swiper-bundle.min.js', __FILE__), array('jquery'), null, true);

            include_once plugin_dir_path(__DIR__) . 'pwefunctions.php';
            $fair_colors = PWECommonFunctions::findPalletColorsStatic();

            $accent_color = ($fair_colors['Accent']) ? $fair_colors['Accent'] : '';
            foreach($fair_colors as $color_key => $color_value){
                if(strpos(strtolower($color_key), 'main2') !== false){
                    $main2_color = $color_value;
                }
            }

            $breakpoints = [];

            if (!empty($breakpoints_raw)) {
                $decoded = json_decode(urldecode($breakpoints_raw), true);
                if (is_array($decoded)) {
                    foreach ($decoded as $item) {
                        $width = isset($item['breakpoint_width']) ? intval($item['breakpoint_width']) : 0;
                        if ($width < 0) continue;

                        $bp = [];

                        // slidesPerView — ułamki OK
                        if (isset($item['breakpoint_slides'])) {
                            $slides_raw = $item['breakpoint_slides'];
                            if (is_string($slides_raw)) {
                                $slides_raw = str_replace(',', '.', $slides_raw);
                            }
                            $slides = floatval($slides_raw);
                            if ($slides > 0) {
                                $bp['slidesPerView'] = $slides;
                            }
                        }

                        // przepuść dodatkowe klucze per-breakpoint
                        $allow = [
                            'centeredSlides','centeredSlidesBounds','spaceBetween','slidesPerGroup',
                            'slidesPerGroupSkip','initialSlide','watchSlidesProgress','loopAdditionalSlides',
                            'loopPreventsSlide','allowTouchMove','freeMode','effect','grabCursor', 'slidesPerView'
                        ];
                        foreach ($allow as $k) {
                            if (array_key_exists($k, $item)) {
                                $bp[$k] = $item[$k];
                            }
                        }

                        if (!empty($bp)) {
                            $breakpoints[$width] = $bp;
                        }
                    }
                    ksort($breakpoints, SORT_NUMERIC);
                }
            }

            // JSON do wstrzyknięcia w JS
            if (!empty($breakpoints)) {
                $swiper_breakpoints_json = wp_json_encode($breakpoints, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } else {
                $swiper_breakpoints_json = wp_json_encode([
                    400  => ['slidesPerView' => 1],
                    650  => ['slidesPerView' => 2],
                    960  => ['slidesPerView' => 3],
                    1100 => ['slidesPerView' => 4],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }

            $default_autoplay = [
                'delay' => 3000,
                'disableOnInteraction' => false,
                'pauseOnMouseEnter' => true,
            ];

            $autoplay_js = wp_json_encode($default_autoplay, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            if (is_array($options) && array_key_exists('autoplay', $options)) {
                $ap = $options['autoplay'];

                if ($ap === false) {
                    $autoplay_js = 'false'; // dokładnie JS-owe false
                } elseif (is_bool($ap) && $ap === true) {
                    // zostawiamy defaulty
                    $autoplay_js = wp_json_encode($default_autoplay, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                } elseif (is_numeric($ap)) {
                    // liczba -> delay
                    $merged = $default_autoplay;
                    $merged['delay'] = (int)$ap;
                    $autoplay_js = wp_json_encode($merged, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                } elseif (is_array($ap)) {
                    // merge user -> defaults (user ma pierwszeństwo)
                    $merged = array_merge($default_autoplay, $ap);
                    $autoplay_js = wp_json_encode($merged, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }
            }

            // --- Pozostałe opcje do JS, ale BEZ autoplay (żeby nie nadpisać powyższego)
            $options_no_autoplay = is_array($options) ? $options : [];
            if (array_key_exists('autoplay', $options_no_autoplay)) {
                unset($options_no_autoplay['autoplay']);
            }
            $js_options = !empty($options_no_autoplay)
                ? wp_json_encode($options_no_autoplay, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                : '{}';

            $output = '
            <style>

                :root {
                    --swiper-navigation-sides-offset: -24px;
                }

                ' . $pwe_element . ' {
                    opacity: 0;
                    visibility: hidden;
                    transition: opacity 0.6s ease;
                }
                ' . $pwe_element . '.pwe-visible {
                    opacity: 1;
                    visibility: visible;
                }

                ' . $pwe_element . ' .swiper {
                    width: 100%;
                    padding: 14px;
                    margin: 14px 0 0;
                }

                @media(max-width: 400px) {

                    ' . $pwe_element . ' .swiper-navigation-container {
                        display: none !important;
                    }

                }

            ';
                if ($arrows_display === "true" && $scrollbar_display === "true") {
                    $output .= '

                        ' . $pwe_element . ' .swiper-scrollbar.swiper-scrollbar-horizontal {
                            position: inherit;
                            height: 8px;
                        }
                        ' . $pwe_element . ' .swiper-button-prev,
                        ' . $pwe_element . ' .swiper-button-next {
                            position: inherit;
                            background: var(--accent-color);
                            color: white;
                            padding: 6px 24px;
                            border-radius: 36px;
                            min-width: 100px;
                            margin: 0;
                        }
                        ' . $pwe_element . ' .swiper-button-next:after,
                            ' . $pwe_element . ' .swiper-button-prev:after {
                            font-size: 22px;
                        }
                        ' . $pwe_element . ' .swiper-navigation-container {
                            display: flex;
                            align-items: center;
                            padding: 12px;
                            gap: 36px;
                        }
                        ' . $pwe_element . ' .swiper-arrows-container {
                            display: flex;
                            gap: 18px;
                        }';
                } else if (empty($arrows_display) && $scrollbar_display === "true") {

                     $output .= '

                        ' . $pwe_element . ' .swiper-scrollbar.swiper-scrollbar-horizontal {
                            position: inherit;
                            height: 8px;
                        }';

                } else {
                    $output .= '

                        ' . $pwe_element . ' .swiper-button-next,
                        ' . $pwe_element . ' .swiper-button-prev {
                            color: var(--accent-color);
                        }';
                }

                if ($dots_display === 'true') {
                    $output .= '
                    ' . $pwe_element . ' .swiper {
                        padding-bottom: 24px !important;
                    }
                    ' . $pwe_element . ' .swiper-pagination{
                        display: block;
                        overflow-x: auto;
                        overflow-y: hidden;
                        white-space: nowrap;
                        max-width: 50px;
                        scroll-behavior: smooth;
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                        left: 50% !important;
                        transform: translateX(-50%);
                        padding: 0 4px;
                    }
                    ' . $pwe_element . ' .swiper-horizontal>.swiper-pagination-bullets {
                        bottom: 3px;
                    }
                    ' . $pwe_element . ' .swiper-pagination-bullet-active {
                        background: var(--accent-color);
                    }
                    ' . $pwe_element . ' .swiper-pagination::-webkit-scrollbar{
                        width: 0;
                        height: 0;
                        background: transparent;
                    }
                        /* kropki w linii */
                    ' . $pwe_element . ' .swiper-pagination-bullet{
                        display: inline-block;
                        margin: 0 3px;
                    }';
                }

            $output .= '
            </style>';

            $output .= '
            <script>
                jQuery(function ($) {
                    const wrapper = document.querySelector("' . $pwe_element . '");
                    const container = wrapper.querySelector("' . $pwe_element . ' .swiper");
                    const slides = container.querySelectorAll("' . $pwe_element . ' .swiper-slide");
                    const slidesCount = slides.length;
                    const containerWidth = container.clientWidth;

                    const breakpoints = ' . $swiper_breakpoints_json . ';

                    let slidesPerView = 1;
                    const cw = Number(container.clientWidth);

                    // posortowane breakpointy -> odczyt slidesPerView + centeredSlides
                    let centeredSlides = false;
                    Object.keys(breakpoints)
                    .map(k => parseInt(k, 10))
                    .filter(n => !Number.isNaN(n))
                    .sort((a, b) => a - b)
                    .forEach(function (bpInt) {
                        if (cw >= bpInt) {
                        const conf = breakpoints[String(bpInt)] ?? breakpoints[bpInt];
                        if (conf && typeof conf === "object") {
                            if ("slidesPerView" in conf) {
                            slidesPerView = (conf.slidesPerView === "auto") ? "auto" : Number(conf.slidesPerView);
                            }
                            if ("centeredSlides" in conf) centeredSlides = !!conf.centeredSlides;
                        }
                        }
                    });

                    const totalSlides = Number(slidesCount);

                    // KONSERWATYWNA REGUŁA:
                    // - przy "auto" i tak nie ma sensownego porównania -> traktujemy jak 1
                    // - przy ułamkach (np. 1.5) wymagamy co najmniej floor(spv)+2 elementów do loop
                    // - przy liczbie całkowitej wymagamy spv+1
                    // - dodatkowo: dla centeredSlides i totalSlides <= 2 -> FORSUJ brak loopa
                    const spv = (slidesPerView === "auto") ? 1 : Math.max(1, Number(slidesPerView));
                    const needForLoop = (Number.isInteger(spv) ? (spv + 1) : (Math.floor(spv) + 2));
                    let shouldLoop = totalSlides >= needForLoop;

                    if (centeredSlides && totalSlides <= 2) {
                    shouldLoop = true;
                    }

                    // Zapisz do configu również "rewind", żeby na mobile nadal dało się „zawinąć”
                    const useRewind = !shouldLoop;

                    const swiperConfig = {
                        loop: shouldLoop,
                        spaceBetween: 20,
                        grabCursor: true,
                        observer: true,
                        observeParents: true,
                        autoplay: ' . $autoplay_js . ',
                        breakpoints: ' . $swiper_breakpoints_json . ',
                        on: {
                            init: function () {
                                setTimeout(() => {
                                    wrapper.classList.add("pwe-visible");
                                }, 500);

                                if (!shouldLoop) {
                                    const swiperWrapper = container.querySelector(".swiper-wrapper");
                                    const swiperPagination = container.querySelector(".swiper-pagination");
                                    swiperPagination.style.display = "none";
                                    if (swiperWrapper && "' . $id . '" !== "conf-short-info-gr3-schedule") {
                                        swiperWrapper.style.justifyContent = "center";
                                    }
                                }
                            }
                        }
                    };

                    ' . "const phpOptions = $js_options;
                    if (phpOptions && typeof phpOptions === 'object') {
                        if (phpOptions.on && typeof phpOptions.on === 'object') {
                            swiperConfig.on = Object.assign({}, swiperConfig.on || {}, phpOptions.on);
                            delete phpOptions.on;
                        }
                        Object.assign(swiperConfig, phpOptions);
                    }
                    ";

                    // Dodaj warunki dynamiczne do konfiguracji Swipera
                    if ($dots_display === "true") {
                        $output .= '
                            swiperConfig.pagination = {
                                el: "' . $pwe_element . ' .swiper-pagination",
                                clickable: true,
                                dynamicBullets: false,
                                dynamicMainBullets: 3
                            };';
                    }

                    if ($arrows_display === "true") {
                        $output .= '
                    swiperConfig.navigation = {
                        nextEl: "' . $pwe_element . ' .swiper-button-next",
                        prevEl: "' . $pwe_element . ' .swiper-button-prev"
                    };';
                    }

                    if ($arrows_display === "true" && $scrollbar_display === "true") {
                        $output .= '
                    swiperConfig.scrollbar = {
                        el: "' . $pwe_element . ' .swiper-scrollbar",
                        draggable: false
                    };';
                    } else if (empty($arrows_display) && $scrollbar_display === "true") {
                        $output .= '
                    swiperConfig.scrollbar = {
                        el: "' . $pwe_element . ' .swiper-scrollbar",
                        draggable: true
                    };';
                    }

                $output .= '

                    const swiper = new Swiper(container, swiperConfig);

                    // Przewijanie paginacji tak, by aktywna kropka była na środku
                    const paginationEl = container.querySelector(".swiper-pagination");

                    function scrollDotsToActive() {
                        if (!paginationEl) return;
                        const active = paginationEl.querySelector(".swiper-pagination-bullet-active");
                        if (!active) return;

                        // Wylicz docelowy scrollLeft tak, aby aktywna kropka była ~wycentrowana
                        const target =
                            active.offsetLeft - (paginationEl.clientWidth / 2) + (active.clientWidth / 2);

                        // jQuery animate – jak w Twoim przykładzie ze Slickiem
                        jQuery(paginationEl).stop(true).animate({ scrollLeft: Math.max(0, target) }, 300);
                        }

                        // Po inicjalizacji oraz przy każdej zmianie slajdu/rozmiaru
                        if (paginationEl) {
                        // po utworzeniu instancji Swipera kropki już są w DOM, można przewinąć
                        setTimeout(scrollDotsToActive, 0);

                        swiper.on("slideChange", scrollDotsToActive);
                        swiper.on("resize", scrollDotsToActive);

                        // gdy użytkownik kliknie w kropkę, przewiń po aktualizacji klasy „active”
                        paginationEl.addEventListener("click", () => {
                            setTimeout(scrollDotsToActive, 0);
                        });
                    }

                    // --- RĘCZNY klik do realnego indeksu, działa stabilnie w loop
                    if (swiper.params.loop === true && swiperConfig.slideToClickedSlide === true) {
                    // wyłącz natywne, żeby nie było podwójnych ruchów
                    swiper.params.slideToClickedSlide = false;

                    const allSlides = container.querySelectorAll(".swiper-slide");
                    allSlides.forEach((slideEl) => {
                        slideEl.addEventListener("click", (e) => {
                        // jeśli to był drag, nie reaguj na klik
                        if (swiper.animating) return;
                        const realAttr = slideEl.getAttribute("data-swiper-slide-index");
                        if (realAttr != null) {
                            const realIndex = parseInt(realAttr, 10);
                            swiper.slideToLoop(realIndex); // właściwy skok do klikniętego slajdu, niezależnie od duplikatów
                        }
                        });
                    });
                    }

                });
            </script>';

            return $output;
        }
}

?>