<?php

class PWEPremieres extends PWECommonFunctions {

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        // Hook actions
        // add_action('wp_enqueue_scripts', array($this, 'addingStyles'));
        // add_action('wp_enqueue_scripts', array($this, 'addingScripts'));
        
        add_action('init', array($this, 'initVCMapPWEPremieres'));
        add_shortcode('pwe_premieres', array($this, 'PWEPremieresOutput'));
    }

    /**
     * Initialize VC Map Elements.
     */
    public function initVCMapPWEPremieres() {
        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map(array(
                'name' => __('PWE Premieres', 'pwe_premieres'),
                'base' => 'pwe_premieres',
                'category' => __('PWE Elements', 'pwe_premieres'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                'admin_enqueue_js' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendscript.js',
            ));
        }
    }

    // public function addingStyles(){
    //     $css_file = plugins_url('assets/style.css', __FILE__);
    //     $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/style.css');
    //     wp_enqueue_style('pwe-premieres-css', $css_file, array(), $css_version);
    // }

    // public function addingScripts(){
    //     $js_file = plugins_url('assets/script.js', __FILE__);
    //     $js_version = filemtime(plugin_dir_path(__FILE__) . 'assets/script.js');
    //     wp_enqueue_script('pwe-premieres-js', $js_file, array('jquery'), $js_version, true);
    // }
    
    public function PWEPremieresOutput() {  

        $current_domain = $_SERVER['HTTP_HOST'];
        $premieres = PWECommonFunctions::get_database_premieres_data($current_domain);

        $output = '
        <style>
            .limit-width:has(.pwe-premieres) {
                max-width: 100% !important;
                padding: 0 !important;
            }
            .pwe-premieres__title {
                display: flex;
                justify-content: center;
            }
            .pwe-premieres__title span {
                color: #0000002b;
                font-size: 90px;
                font-weight: 900;
                text-transform: uppercase;
                text-align: center;
                line-height: 1;
            }
            @media(max-width:1100px) {
                .pwe-premieres__title span {
                    font-size: 78px !important;
                }
            }
            @media(max-width:900px) {
                .pwe-premieres__title span {
                    font-size: 42px !important;
                }
            }
            #heroDescription {
                margin-top: 18px;
            }
            #heroDescription p {
                margin: 0;
            }
            #heroDescription p::-webkit-scrollbar {
                width: 10px;
            }
            #heroDescription p::-webkit-scrollbar-thumb {
                background-color: rgba(169, 169, 169, 0.7);
                border: none;
                border-radius: 12px;
            }
            #heroDescription p::-webkit-scrollbar-track {
                background-color: transparent;
            }
            .pwe-premieres__hero.loading {
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.6s ease, visibility 0s linear 0.6s;
            }
            .pwe-premieres__hero.loaded {
                opacity: 1;
                visibility: visible;
                transition: opacity 0.6s ease;
            }
            .pwe-premieres__hero * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                color: #fff !important;
            }
            .pwe-premieres__hero {
                position: relative;
                height: 60vh;
                min-height: 600px;
                overflow: hidden;
                box-shadow: 0 0 16px -2px black;
            }
            .pwe-premieres__bg-stack {
                position: absolute;
                inset: 0;
                z-index: 1;
            }
            .pwe-premieres__bg-layer {
                position: absolute;
                width: 62%;
                inset: 0;
                background-size: cover;
                background-position: center;
                transition: opacity .4s ease;
            }
            .pwe-premieres__bg-layer:before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: black;
                opacity: 0.6;
            }
            .pwe-premieres__bg-fullscreen-icon {
                position: absolute;
                top: 18px;
                right: 18px;
                width: 40px;
                height: auto;
                filter: brightness(0) invert(1);
                cursor: pointer;
                transition: .3s ease;
            }
            .pwe-premieres__bg-fullscreen-icon:hover {
                transform: scale(1.2);
            }
            .pwe-premieres__logo {
                position: absolute;
                left: 40px;
                top: 40px;
                z-index: 4;
                max-width: 50%;
            }
            .pwe-premieres__logo img {
                background: #ffffff73;
                border-radius: 12px;
                object-fit: contain;
            }
            .pwe-premieres__content {
                position: absolute;
                left: 40px;
                bottom: 40px;
                z-index: 4;
                max-width: 50%;
                text-align: left;
            }
            .pwe-premieres__content h2 {
                font-size: 30px;
                letter-spacing: 2px;
                line-height: 1.1;
                transition: opacity .5s ease, transform .5s ease;
            }
            .pwe-premieres__content h3 {
                font-size: 18px;
                font-weight: 300;
                margin-top: 8px;
                transition: opacity .5s ease .05s, transform .5s ease .05s;
            }
            .pwe-premieres__content p {
                font-size: 16px;
                font-weight: 500;
                margin-top: 20px;
                line-height: 1.6;
                color: #ddd;
                max-height: 150px;
                overflow-x: auto;
            }
            .pwe-premieres__progress {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 14px;
                margin-top: 40px;
            }
            .pwe-premieres__progress span:first-child {
                font-weight: 700;
                font-size: 32px;
            }
            .pwe-premieres__panel {
                position: absolute;
                right: 0;
                top: 0;
                height: 100%;
                width: 38%;
                display: flex;
                flex-direction: column;
                background: rgba(4, 52, 132, 0.6);
                backdrop-filter: blur(4px);
                z-index: 3;
                overflow: hidden;
            }
            .pwe-premieres__cards {
                display: flex;
                height: 100%;
                width: 100%;
                transition: transform 0.6s cubic-bezier(.25, .8, .25, 1);
            }
            .pwe-premieres__card {
                flex: 1 1 25%;
                display: flex;
                flex-direction: column-reverse;
                justify-content: flex-start;
                padding: 40px;
                background: rgba(8, 76, 180, 0.3);
                backdrop-filter: blur(3px);
                cursor: pointer;
                transition: flex-basis 0.6s cubic-bezier(.25, .8, .25, 1), background 0.3s ease;
                min-width: 200px;
                background-size: cover !important;
                background-position: center !important;
                text-align: left;
            }
            .pwe-premieres__card:not(:last-child) {
                border-right: 1px solid rgba(255, 255, 255, 0.12);
            }
            .pwe-premieres__card:hover {
                background: rgba(8, 76, 180, 0.5);
            }
            .pwe-premieres__card.active {
                background: rgba(8, 76, 180, 0.55);
            }
            .pwe-premieres__card.hidden {
                display: none !important;
            }
            .pwe-premieres__card-stand {
                font-size: 14px;
                opacity: 0.8;
            }
            .pwe-premieres__card-name {
                font-size: 22px;
                margin: 6px 0 24px;
                line-height: 1.2;
            }
            .pwe-premieres__nav {
                position: absolute;
                bottom: 40px;
                right: calc(38% + 20px);
                display: flex;
                gap: 16px;
                z-index: 4;
            }
            .pwe-premieres__nav button {
                all: unset;
                width: 42px;
                height: 42px;
                border: 1px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
                cursor: pointer;
                transition: background 0.3s ease;
                font-family: math;
                font-size: 24px;
            }
            .pwe-premieres__nav button:hover {
                background: rgba(255, 255, 255, 0.2);
            }
            .pwe-premieres__hero.single-slide .pwe-premieres__bg-layer {
                width: 100% !important;
            }
            .pwe-premieres__hero.single-slide .pwe-premieres__content {
                max-width: 92% !important;
            }
            .pwe-premieres__hero.single-slide .pwe-premieres__panel,
            .pwe-premieres__hero.single-slide .pwe-premieres__nav,
            .pwe-premieres__hero.single-slide .pwe-premieres__progress {
                display: none !important;
            }
            @media (max-width: 960px) {
                .pwe-premieres__panel {
                    display: none;
                    width: 100%;
                    height: 38%;
                    flex-direction: row;
                    bottom: 0;
                    top: auto;
                    right: 0;
                }
                .pwe-premieres__cards {
                    flex-direction: column;
                    height: 100%;
                    width: 100%;
                }
                .pwe-premieres__card {
                    min-width: unset;
                    min-height: 100px;
                    padding: 18px;
                    border-right: none;
                    border-bottom: 1px solid rgba(255,255,255,0.12);
                }
                .pwe-premieres__card:not(:last-child) {
                    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
                }
                .pwe-premieres__nav {
                    flex-direction: column;
                    right: 20px;
                    bottom: calc(38% + 20px);
                }
                .pwe-premieres__bg-layer {
                    width: 100%;
                    height: 100%;
                }
                .pwe-premieres__content {
                    max-width: 80%;
                    left: 20px;
                    bottom: calc(2% + 10px);
                }
                .pwe-premieres__content h2 {
                    font-size: 24px;
                }
                .pwe-premieres__content h3 {
                    font-size: 16px;
                }
                .pwe-premieres__content p {
                    font-size: 14px;
                    line-height: 1.4;
                }
                .pwe-premieres__logo {
                    left: 20px;
                    top: 20px;
                    max-width: 80%;
                }
                .pwe-premieres__progress {
                    margin-top: 20px;
                }
                .pwe-premieres__card-name {
                    margin: 6px 0 12px;
                }
                .pwe-premieres__nav button {
                    transform: rotate(90deg);
                }
            }

            .pwe-premieres__fullscreen {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999;
            }

            .pwe-premieres__fullscreen-image {
                max-width: 90%;
                max-height: 90%;
                object-fit: contain;
                border-radius: 20px;
            }
            .pwe-premieres__fullscreen-close {
                position: absolute;
                top: 30px;
                right: 30px;
                font-size: 50px;
                font-weight: bold;
                color: #fff;
                cursor: pointer;
                z-index: 999;
                user-select: none;
            }

        </style>';

        $output .= '
        <div id="pwePremieres" class="pwe-premieres">';

        if (!empty($premieres[0]->slug)) {
            
            $slides = [];

            foreach ($premieres as $premiere) {
                // dekodowanie JSON z kolumny "data"
                $data = json_decode($premiere->data, true);

                // w JSON klucz główny = slug
                if (!isset($data[$premiere->slug])) {
                    continue;
                }
                $item = $data[$premiere->slug];

                $slides[] = [
                    'name'      => PWECommonFunctions::lang_pl() ? $item['name_pl'] : (!empty($item['name_en']) ? $item['name_en'] : $item['name_pl']),
                    'desc'      => PWECommonFunctions::lang_pl() ? trim($item['desc_pl']) : (trim($item['desc_en']) ?? trim($item['desc_pl'])),
                    'exhibitor' => $item['exhibitor'] ?? '',
                    'stand'     => (!empty($item['stand']) ? (PWECommonFunctions::lang_pl() ? 'Stoisko: ' : 'Stand: ') . $item['stand'] : ''),
                    'img'       => $item['background'] ?? '',
                    'logo'      => $item['logo'] ?? ''
                ];
            }

            $output .= '
            <div class="pwe-premieres__title">
                <span>'. (PWECommonFunctions::lang_pl() ? "Co zobaczysz na [trade_fair_name]" : "What you'll see at [trade_fair_name_eng]") .'</span>
            </div>

            <div class="pwe-premieres__hero loading">
                <div class="pwe-premieres__bg-stack">
                    <div class="pwe-premieres__bg-layer" id="bgLayer">
                        <img class="pwe-premieres__bg-fullscreen-icon" src="/wp-content/plugins/pwe-media/media/fullscreen-icon.png" alt="fullscreen">
                    </div>
                </div>
                <div class="pwe-premieres__logo">
                    <img id="logoImg" src="" alt="Logo" style="max-height:80px;">
                </div>
                <div class="pwe-premieres__content">
                    <h2 id="heroTitle"></h2>
                    <h3 id="heroSubtitle"></h3>
                    <div id="heroDescription"></div>
                    <div class="pwe-premieres__progress"><span id="slideIndex">01</span>/<span id="slideTotal">04</span></div>
                </div>
                <div class="pwe-premieres__nav">
                    <button class="prev">&#8249;</button>
                    <button class="next">&#8250;</button>
                </div>
                <aside class="pwe-premieres__panel">
                    <div class="pwe-premieres__cards" id="cardsContainer"></div>
                </aside>
            </div>

            <script>

                const slides = '. json_encode($slides, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) .';

                (function() {

                    const cardsWrap = document.getElementById("cardsContainer");
                    const heroTitle = document.getElementById("heroTitle");
                    const heroSubtitle = document.getElementById("heroSubtitle");
                    const heroDescription = document.getElementById("heroDescription");
                    const slideIndexEl = document.getElementById("slideIndex");

                    if (slides.length === 1) {
                        document.querySelector(".pwe-premieres__hero").classList.add("single-slide");
                    }

                    slides.forEach((data, i) => {
                        const card = document.createElement("div");
                        card.className = "pwe-premieres__card";
                        card.style.backgroundImage = `linear-gradient(rgba(8,76,180,0.5), rgba(8,76,180,0.5)), url(${data.img})`;

                        card.dataset.index = i;
                        card.dataset.name = data.name;
                        if (data.stand) card.dataset.stand = data.stand;
                        card.dataset.exhibitor = data.exhibitor;
                        card.dataset.desc = data.desc;
                        card.dataset.bg = data.img;

                        const standExhibitor = data.stand ? `${data.stand} ${data.exhibitor}` : data.exhibitor;

                        card.innerHTML = `
                            <span class="pwe-premieres__card-stand">${standExhibitor}</span>
                            <span class="pwe-premieres__card-name">${data.name}</span>
                        `;
                        cardsWrap.appendChild(card);
                    });

                    const cards = Array.from(cardsWrap.querySelectorAll(".pwe-premieres__card"));
                    const total = cards.length;
                    document.getElementById("slideTotal").textContent = String(total).padStart(2, "0");

                    let heroIndex = 0;
                    let largeCardIndex = (heroIndex + 1) % total;

                    function changeBackground(url) {
                        const bg = document.getElementById("bgLayer");
                        if (!bg) return;
                        bg.style.opacity = "0";
                        setTimeout(() => {
                            bg.style.backgroundImage = `url(${url})`;
                            bg.style.opacity = "1";
                        }, 300);
                    }

                    function animateText() {
                        heroTitle.style.opacity = "0";
                        heroTitle.style.transform = "translateY(10px)";
                        heroSubtitle.style.opacity = "0";
                        heroSubtitle.style.transform = "translateY(10px)";
                        heroDescription.style.opacity = "0";
                        heroDescription.style.transform = "translateY(10px)";
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                heroTitle.style.opacity = "1";
                                heroTitle.style.transform = "translateY(0)";
                                heroSubtitle.style.opacity = "1";
                                heroSubtitle.style.transform = "translateY(0)";
                                heroDescription.style.opacity = "1";
                                heroDescription.style.transform = "translateY(0)";
                            }, 100);
                        });
                    }

                    function arrangeCards(){
                        cards.forEach((card, idx) => {
                            const rel = (idx - heroIndex + total) % total;
                            card.classList.toggle("hidden", rel === 0);
                            card.classList.toggle("active", rel === 1);
                            if (rel === 1) {
                                largeCardIndex = idx;
                            }
                            card.style.order = rel;
                        });
                    }

                    function flipAnimation(oldLargeIdx, delta) {
                        const oldEl = cards[oldLargeIdx];
                        const newEl = cards[largeCardIndex];
                        if (!oldEl || !newEl) return;
                        cardsWrap.style.transition = "none";
                        const isMobile = window.innerWidth <= 960;
                        cardsWrap.style.transform = isMobile
                        ? `translateY(${delta > 0 ? 50 : -50}px)`
                        : `translateX(${delta > 0 ? 50 : -50}px)`;
                        void cardsWrap.offsetWidth;
                        cardsWrap.style.transition = "transform 0.6s cubic-bezier(.25,.8,.25,1)";
                        cardsWrap.style.transform = "translateX(0)";
                    }

                    function go(delta) {
                        const oldLarge = largeCardIndex;
                        heroIndex = (heroIndex + delta + total) % total;
                        const heroData = slides[heroIndex];
                        slideIndexEl.textContent = String(heroIndex + 1).padStart(2, "0");
                        heroTitle.textContent = heroData.name;
                        heroSubtitle.textContent = (heroData.stand ? heroData.stand + " " : "") + heroData.exhibitor;
                        heroDescription.innerHTML = heroData.desc;
                        animateText();
                        changeBackground(heroData.img);
                        const logoEl = document.getElementById("logoImg");
                        if (heroData.logo) {
                            logoEl.src = heroData.logo;
                            logoEl.style.display = "block";
                        } else {
                            logoEl.src = "";
                            logoEl.style.display = "none";
                        }
                        arrangeCards();
                        flipAnimation(oldLarge, delta);
                    }

                    // Init
                    const heroData = slides[heroIndex];
                    heroTitle.textContent = heroData.name;
                    heroSubtitle.textContent = (heroData.stand ? heroData.stand + " " : "") + heroData.exhibitor;
                    heroDescription.innerHTML = heroData.desc;
                    changeBackground(heroData.img);
                    const logoEl = document.getElementById("logoImg");
                    if (heroData.logo) {
                        logoEl.src = heroData.logo;
                        logoEl.style.display = "block";
                    } else {
                        logoEl.src = "";
                        logoEl.style.display = "none";
                    }
                    arrangeCards();
                    slideIndexEl.textContent = "01";

                    document.querySelector(".next").addEventListener("click", () => go(1));
                    document.querySelector(".prev").addEventListener("click", () => go(-1));
                    cards.forEach((card, idx) => card.addEventListener("click", () => {
                        const delta = (idx - heroIndex + total) % total;
                        if (delta !== 0) go(delta);
                    }));

                })();

                window.addEventListener("load", () => {
                    const hero = document.querySelector(".pwe-premieres__hero");
                    hero.classList.remove("loading");
                    hero.classList.add("loaded");
                });

                const fullscreenIcon = document.querySelector(".pwe-premieres__bg-fullscreen-icon");
                const bgLayer = document.getElementById("bgLayer");

                fullscreenIcon.addEventListener("click", () => {
                    const bgImage = bgLayer.style.backgroundImage;

                    const urlMatch = bgImage.match(/url\\(["\']?(.*?)["\']?\\)/);
                    if (!urlMatch) return;
                    const imgUrl = urlMatch[1];

                    const fullscreen = document.createElement("div");
                    fullscreen.id = "pwePremieresFullscreen";
                    fullscreen.className = "pwe-premieres__fullscreen";

                    const img = document.createElement("img");
                    img.className = "pwe-premieres__fullscreen-image";
                    img.src = imgUrl;

                    const closeBtn = document.createElement("span");
                    closeBtn.className = "pwe-premieres__fullscreen-close";
                    closeBtn.innerHTML = "&times;";

                    const closeFullscreen = () => {
                        fullscreen.remove();
                        document.removeEventListener("keydown", escHandler);
                    };

                    fullscreen.addEventListener("click", (e) => {
                        if (e.target === fullscreen || e.target === closeBtn) {
                            closeFullscreen();
                        }
                    });

                    const escHandler = (e) => {
                        if (e.key === "Escape") {
                            closeFullscreen();
                        }
                    };
                    document.addEventListener("keydown", escHandler);

                    fullscreen.appendChild(img);
                    fullscreen.appendChild(closeBtn);
                    document.body.appendChild(fullscreen);
                });
                
            </script>';
        }

        $output .= '
        </div>';

        if (empty($premieres[0]->slug)) {
            $output .= '
            <style>
                .row-container:has(.pwe-premieres) {
                    display: none;
                }
            </style>'; 
        }

        $output = do_shortcode($output);  
        
        return $output;
    }  
}