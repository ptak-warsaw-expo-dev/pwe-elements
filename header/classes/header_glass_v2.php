<?php
$text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');
$btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
$btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);
$darker_btn_color = self::adjustBrightness($btn_color, -20);

$trade_fair_dates = do_shortcode('[trade_fair_date_custom_format]');
$trade_fair_dates = str_replace("|", " | ", $trade_fair_dates);

$output .= '
    <style>
        .pwelement_'. $el_id .' .pwe-header-container {
            padding: 36px;
            position: relative;
        }
        .pwelement_'. $el_id .' .pwe-header-wrapper { 
            z-index:1; 
            display: flex;
            justify-content: space-between;
            flex-direction: row-reverse;
            align-items: end;
            gap: 36px;
        }
        .pwelement_'. $el_id .' .pwe-header-column.pwe-header-content-column {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .pwelement_'. $el_id .' .pwe-header-content-wrapper {
            display: flex;
            justify-content: end;
        }
        .pwelement_'. $el_id .' .pwe-header-tile h1 {
            font-size: 32px;
        }
        .pwelement_'. $el_id .' .pwe-header-tile {
            background: #ffffff87;
            max-width: 450px;
            backdrop-filter: blur(10px);
            padding: 36px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .pwelement_'. $el_id .' .pwe-header-title h1, 
        .pwelement_'. $el_id .' .pwe-header-title p {
            text-align: left;
            margin-top: 18px;
            text-transform: unset;
            color: black;
        }
        .pwelement_'. $el_id .' .pwe-header-edition {
            text-align: center;
        }  
        .pwelement_'. $el_id .' .pwe-header-edition span {
            background: var(--main2-color);
            color: #fff;
            padding: 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 20px;
        }
        .pwelement_'. $el_id .' .pwe-header-date-block {
            display: flex;
            align-items: center;
            margin-top: 18px;
        }
        .pwelement_'. $el_id .' .pwe-header-date-block h2 {
            margin: 0;
            font-size: 20px;
            text-transform: unset;
            color: black;
        }
        .pwelement_'. $el_id .' .pwe-header-date-block i {
            color: black;
            font-size: 22px;
        }
        .pwelement_'. $el_id .' .pwe-header-bottom {
            background: var(--accent-color);
            padding: 18px 36px;
        }
        .pwelement_'. $el_id .' .pwe-header-bottom-content {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
        }
        .pwelement_'. $el_id .' .pwe-btn-container {
            padding: 0;
            max-width: 450px;
            width: 100%;
            display: flex;
            align-items: center;
            margin-top: 18px;
        }
        .pwelement_'. $el_id .' #pweBtnRegistration .pwe-btn {
            background: '. $btn_color .';
            color: '. $btn_text_color .';
            padding: 13px 31px !important;
            border-radius: 24px !important;
            font-weight: 600;
            text-transform: uppercase;
            text-align: center;
            min-width: 240px !important;
        }
        .pwelement_'. $el_id .' #pweBtnRegistration .pwe-btn:hover {
            background: '. $darker_btn_color .';
        }
        .pwelement_'. $el_id .' #countdownCustom {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            background: var(--accent-color);
            padding: 24px 24px 16px;
            border-radius: 18px;
            position: absolute;
            bottom: 0;
            right: 4%;
        }
        .pwelement_'. $el_id .' .countdown-container {
            display: flex;
            justify-content: center;
            gap: 16px;
        }
        .pwelement_'. $el_id .' .countdown-text {
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
        }
        .pwelement_'. $el_id .' .countdown-number {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
            font-weight: 700;
            color: white;
        }
        .pwelement_'. $el_id .' #trade-fair-date-custom {
            display: none;
        }
        .pwelement_'. $el_id .' .pwe-header .pwe-header-logotypes {
            opacity: 1 !important;
        }
        .pwelement_'. $el_id .' .pwe-header-partners {
            position: static;
            height: auto;
            display: flex;
            justify-content: center;
            flex-direction: row;
            transform: unset;
            background: #ffffff;
            backdrop-filter: blur(10px);
            border-radius: 18px 18px 0 0;
            padding: 6px 18px 18px;
            gap: 18px;
            margin-top: 0;
            margin-bottom: -38px;
        }
        .pwelement_'. $el_id .' .pwe-header-container { 
            position: relative; 
            overflow: hidden; 
        }
        .pwelement_'. $el_id .' .pwe-bg-image {
            position: absolute; 
            inset: 0; 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;
            opacity: 0; 
            transition: opacity 800ms ease-in-out; 
            z-index: 0;
        }
        .pwelement_'. $el_id .' .pwe-bg-image.is-active { 
            opacity:1; 
        }
        
        @media(max-width:970px){
            .pwelement_'. $el_id .' .pwe-header-wrapper { 
                flex-direction: column;
                align-items: center;
            }
            .pwelement_'. $el_id .' .pwe-header-partners {
                position: static;
                height: auto;
                transform: unset;
                display: flex;
                justify-content: center;
                flex-direction: column;
                align-items: center;
            }
            .pwelement_'. $el_id .' .pwe-header-logotypes {
                max-width: 450px !important;
                width: 100%;
            }  
            .pwe-header-partners__items {
                flex-wrap: wrap !important;
            }
            .pwelement_'. $el_id .' .pwe-header-partners-container,
            .pwelement_'. $el_id .' .pwe-header-partners__title h3 {
                max-width: 100% !important;
            }
        }
        @media(max-width:570px) {
            .pwelement_'. $el_id .' .pwe-header-column.pwe-header-content-column {
                margin-bottom: 18px;
            }
            .pwelement_'. $el_id .' .pwe-header-date-block {
                justify-content: flex-start;
            }
            .pwelement_'. $el_id .' .pwe-header-tile h1 {
                font-size: 22px;
            }
            .pwelement_'. $el_id .' .pwe-header-logo {
                max-width: 180px !important;
            }
            .pwelement_'. $el_id .' .pwe-header-edition span {
                padding: 7px 21px;
                font-size: 14px;
            }
            .pwelement_'. $el_id .' .pwe-header-tile {
                padding: 18px;
            }
            .pwelement_'. $el_id .' .pwe-header-date-block h2 {
                font-size: 14px;
            }
            .pwelement_'. $el_id .' .pwe-header-column.pwe-header-content-column {
                align-items: center;
            }
            .pwelement_'. $el_id .' .pwe-header-bottom-timer {
                width: 100%;
            }
        }
    </style>

<div id="pweHeader" class="pwe-header">
    <div style="background-image: url('. $background_header .');"  class="pwe-header-container pwe-header-background">

        <div class="pwe-bg-image1 pwe-bg-image"></div>
        <div class="pwe-bg-image2 pwe-bg-image"></div>
        <div class="pwe-bg-image3 pwe-bg-image"></div>
        
        <div class="pwe-header-wrapper">

            <div class="pwe-header-column pwe-header-content-column">
                <div class="pwe-header-content-wrapper">
                    <div class="pwe-header-tile">
                        <div class="pwe-header-main-content-block">
                            <img class="pwe-header-logo" src="'. self::languageChecker('/doc/logo-color.webp', '/doc/logo-color-en.webp') .'" alt="logo-'. $trade_fair_name .'">
                            <div class="pwe-header-edition"><p><span>'. $trade_fair_edition .'</span></p></div>
                            <div class="pwe-header-title">
                                <h1>'. $trade_fair_desc .'</h1>
                            </div>
                        </div>
                        <div class="pwe-header-date-block">
                            <i class="fa fa-location-outline fa-2x fa-fw"></i>
                            <h2>'. $trade_fair_dates . self::languageChecker(' Warszawa', ' Warsaw') .'</h2>
                            <p></p>
                        </div>
                        <div id="pweBtnRegistration" class="pwe-btn-container header-button">
                            <a class="pwe-link pwe-btn" href="'. $pwe_header_register_button_link .'" alt="'. self::languageChecker('link do rejestracji', 'link to registration') .'">
                                '. self::languageChecker('Zarejestruj się', 'Register') .'
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pwe-header-logotypes">';
                $cap_logotypes_data = ($pwe_header_cap_auto_partners_off != true) ? PWECommonFunctions::get_database_logotypes_data() : "";
                if (!empty($cap_logotypes_data) || !empty($pwe_header_partners_items) || !empty($pwe_header_partners_catalog)) { 
                // if (!empty($pwe_header_partners_items) || !empty($pwe_header_partners_catalog)) { 
                    require_once plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . 'widgets/partners-widget.php';
                }
                $output .= '
            </div>
        </div>
    </div>
</div>

<script>
(function(){
  // --- KONFIG ---
  const INTERVAL = 6000; // ms między zmianami
  const images = [
    "/doc/new_home/header/hero_img_1.webp",
    "/doc/new_home/header/hero_img_2.webp",
    "/doc/new_home/header/hero_img_3.webp",
    "/doc/new_home/header/hero_img_4.webp"
  ];

  // kontener tła konkretnego elementu (z $el_id, żeby nie wpływać na inne)
  const root = document.querySelector(".pwelement_'. $el_id .' .pwe-header-container");
  if(!root || !images.length) return;

  // utwórz warstwy z tablicy obrazów
  const layers = images.map(src => {
    const d = document.createElement("div");
    d.className = "pwe-bg-image";
    d.style.backgroundImage = `url("${src}")`;
    root.prepend(d); // pod treścią, ale nad bazowym style background-image kontenera
    return d;
  });

  // preferencje systemowe — wolniejsza zmiana przy reduced motion
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const delay = reduceMotion ? Math.max(INTERVAL, 12000) : INTERVAL;

  // start
  let current = 0;
  layers[current].classList.add("is-active");

  function nextBg(){
    const prev = current;
    current = (current + 1) % layers.length;
    layers[prev].classList.remove("is-active");
    layers[current].classList.add("is-active");
  }

  let ticker = setInterval(nextBg, delay);

  // pauza gdy karta nieaktywna
  document.addEventListener("visibilitychange", () => {
    if (document.hidden) { clearInterval(ticker); }
    else { ticker = setInterval(nextBg, delay); }
  });
})();
</script>
';
