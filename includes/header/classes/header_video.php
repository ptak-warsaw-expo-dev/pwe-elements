<?php

$text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');
$btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
$btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);
$btn_border = '1px solid ' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'black');
$darker_btn_color = self::adjustBrightness($btn_color, -20);

if ($pwe_header_shadow == true) {
    $pwe_header_shadow_value = (empty($pwe_header_shadow_value)) ? "linear-gradient(to bottom, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0) 45%);" : $pwe_header_shadow_value;
} else {
    $pwe_header_shadow_value = "transparent";
}

$new_main_logotype_id = isset($new_main_logotype) ? $new_main_logotype : '';

if (!empty($new_main_logotype_id)) {
    $image_attributes = wp_get_attachment_image_src($new_main_logotype_id, 'full');
    if ($image_attributes) {
        $new_main_logotype = $image_attributes[0];
    }
}

$logo_url = (empty($new_main_logotype)) ? $logo_url : $new_main_logotype;

$output .= '
<style>
    .pwelement_'. $el_id .' .video-background {
        position: relative;
    }
    .pwelement_'. $el_id .' .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }
    .pwelement_'. $el_id .' .video-overlay {
        background: '. $pwe_header_shadow_value . ';
    }
    .pwelement_'. $el_id .' .pwe-header-wrapper {
        position: relative;
        max-width: 100%;
        justify-content: center;
        align-items: center;
    }
    .pwelement_'. $el_id .' .pwe-header-column {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        justify-content: left;
    }
    .pwelement_'. $el_id .' .pwe-header-logo {
        max-width: '. $pwe_header_logo_width .'px !important;
    }
    .pwelement_'. $el_id .' .pwe-header-text {
        padding: 0 !important;
    }
    .pwelement_'. $el_id .' .pwe-header-text :is(h1, h2, .pwe-header-edition) {
        color: '. $text_color .';
        text-align: start;
        margin: 0;
        font-weight: 600;
    }
    .pwelement_'. $el_id .' .pwe-header-text h1 {
        text-transform: uppercase;
        font-size: 30px;
        font-weight: 500 !important;
        max-width: 600px;
        padding-top: 24px;
    }
    .pwelement_'. $el_id .' .pwe-header-text h2 {
        text-transform: lowercase;
        margin-top: 24px;
        font-size: 28px;
    }
    .pwelement_'. $el_id .' .pwe-header-text .pwe-header-edition {
        text-transform: uppercase;
        font-size: 30px;
        padding: 6px 8px;
        width: fit-content;
    }
    .pwelement_'. $el_id .' .pwe-header-text .pwe-header-city {
        color: '. $text_color .';
    }
    .pwelement_'. $el_id .' .pwe-header-text .pwe-header-location {
        display: none;
    }
    .pwelement_'. $el_id .' .pwe-header-edition {
        background-color: white;
    }
    .pwelement_'. $el_id .' .pwe-header-edition span {
        color: black;
    }
    .pwelement_'. $el_id .' .pwe-header-bottom {
        display: flex;
        flex-wrap: wrap;
        justify-content: start;
        align-items: center;
        padding-top: 24px;
        gap: 18px;
    }
    .pwelement_'. $el_id .' .pwe-header-bottom .header-button {
        width: 40%;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn-container {
        position: relative;
        width: 300px;
        height: 60px;
        padding: 0;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn {
        background-color: '. $btn_color .' !important;
        color: '. $btn_text_color .' !important;
        border: '. $btn_color .' !important;
        width: 100%;
        height: 100%;
        transform: scale(1) !important;
        transition: .3s ease;
        font-size: 16px;
        font-weight: 600;
        padding: 6px 18px !important;
        letter-spacing: 0.1em;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-transform: uppercase;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn-container .btn-small-text {
        font-size: 10px;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn-container .btn-angle-right {
        color: '. $btn_text_color .';
        position: absolute;
        right: 25px;
        top: -30%;
        height: 35px;
        font-size: 72px;
        transition: .3s ease;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn-container:hover .btn-angle-right {
        right: 20px;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn:hover {
        color: '. $btn_text_color .';
        background-color: '. $darker_btn_color .'!important;
        border: 1px solid '. $darker_btn_color .'!important;
    }
    .pwelement_'. $el_id .' .pwe-header .video-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
        pointer-events: none;
    }
    .pwelement_'. $el_id .' .pwe-header .video-background iframe {
        position: absolute;
        top: -36vh;
        left: 0;
        width: 100vw;
        height: 160vh;
        object-fit: cover;
        z-index: -1; /* Wideo w tle za elementami */
        pointer-events: none; /* Brak interakcji z wideo */
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-header-container {
        position: relative;
        width: 100%;
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-header-wrapper {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 0 20px;
        min-height: 80vh;
    }
    .pwelement_'. $el_id .' .pwe-header-column-container {
        max-width: 600px;
        padding: 36px;
        background-color: rgb(25 25 25 / 80%);
        border-radius: 35px;
        min-height: 50vh;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }
    .pwelement_'. $el_id .' .video-background video {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        object-fit: cover; /* Sprawia, że wideo jest skalowane bez zniekształceń */
        transform: translate(-50%, -50%);
    }
    .pwelement_'. $el_id .' .header-center-date {
        display:none !important;
    }';
    if ($pwe_header_center == true) {
        $output .= '
        .pwelement_'. $el_id .' .pwe-header-column {
            justify-content: center;
            align-items: center;
        }
        .pwelement_'. $el_id .' .pwe-header-text h1 {
            font-size: 32px;
            max-width: 1200px;
            text-align: center !important;
        }
        .pwelement_'. $el_id .' .pwe-header-text .header-center-date {
            margin-top: 12px;
            font-size: 56px;
            font-weight: 600;
            letter-spacing: 5px;
            text-align: center;
        }
        .pwelement_'. $el_id .' .pwe-header-text .pwe-header-edition {
            border-radius: 8px;
            margin-top: 12px;
        }
        .pwelement_'. $el_id .' .pwe-header-text .pwe-header-city {
            font-size: 30px;
            font-weight: 500;
            margin: 0;
        }
        .pwelement_'. $el_id .' .pwe-header-column-container {
            background-color: transparent;
            max-width: 700px;
        }
        .pwe-header-title {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            text-shadow: 0 0 6px black;
        }
        .pwelement_'. $el_id .' .pwe-header-date-block {
            display:none !important;
        }
        .pwelement_'. $el_id .' .header-center-date {
            display:block !important;
        }
        .pwe-header-main-content-block {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
        .pwelement_'. $el_id .' .pwe-header-bottom {
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 18px;
            margin: 0 auto;
        }';
    }
    if($pwe_header_without_bg == true) {
        $output .= '
        .pwelement_'. $el_id .' .pwe-header-column {
            max-width:100%;
        }
        .pwelement_'. $el_id .'  .pwe-header-column-container {
            max-width: 800px;
            background-color: transparent;
        }
        .pwelement_'. $el_id .' .pwe-header-text h1 {
            font-size: 27px;
            letter-spacing: 6px;
            font-weight: 400 !important;
            max-width: 800px;
        }
        .pwelement_'. $el_id .' .pwe-header-text h2 {
            margin-top: 24px;
            font-size: 38px;
            text-transform: uppercase;
            letter-spacing: 10px;
            font-weight: 600;
        }
        .pwelement_'. $el_id .' .btn-angle-right {
            display:none;
        }
        .pwelement_'. $el_id .' .pwe-header .pwe-btn {
            border: 2px solid white !important;
            border-radius: 10px !important;
            letter-spacing: 4px;
        }
        .pwelement_'. $el_id .' .pwe-header-bottom {
            flex-wrap: wrap;
            gap: 18px;
        }
        #countdownCustom {
          margin: 0px !important;
          width: 360px !important;
        }';
    }
    $output .='
    @media(max-width:1350px){
        .pwelement_'. $el_id .' .pwe-header .video-background iframe {
            width: 100vw;
            height: 100vh;
            top: -9vh;
        }
    }
    @media(max-width: 960px) {
        .pwelement_'. $el_id .' .video-background {
            display:none !important;
        }
        .pwelement_'. $el_id .' .pwe-header .pwe-header-container {
            height: auto;
        }
        .pwelement_'. $el_id .' .pwe-header-column-container {
            padding: 0;
            background-color: inherit;
            min-height: auto;
            margin: 0 auto;
        }
        .pwelement_'. $el_id .' .pwe-header .pwe-header-wrapper {
            min-height: auto;
            padding: 0;
        }
        .pwelement_'. $el_id .' .pwe-header-background {
            background-image: url("/doc/header_mobile.webp") !important;
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .pwelement_'. $el_id .' .pwe-bg-image1,
        .pwelement_'. $el_id .' .pwe-bg-image2,
        .pwelement_'. $el_id .' .pwe-bg-image3 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 2s ease-in-out;
            z-index: 1 !important;
        }
        .pwelement_'. $el_id .' .pwe-bg-image1 {
            background-image: url("/doc/header_mobile.webp");
            z-index: 1;
        }
        .pwelement_'. $el_id .' .pwe-bg-image2 {
            background-image: url("/wp-content/plugins/pwe-media/media/bg_mobile_2.webp");
            z-index: 2;
        }
        .pwelement_'. $el_id .' .pwe-bg-image3 {
            background-image: url("/wp-content/plugins/pwe-media/media/bg_mobile_3.webp");
            z-index: 3;
        }
        .pwelement_'. $el_id .' .pwe-header-background .visible {
            opacity: 1;
        }
        .pwelement_'. $el_id .' .pwe-header-main-content-block,
        .pwelement_'. $el_id .' .pwe-header-date-block {
            background-color: #00000099;
            padding: 18px;
            border-radius: 18px;
        }
        .pwelement_'. $el_id .' .pwe-header-main-content-block {
            max-width: 400px;
            display: flex;
            flex-direction: column-reverse;
            justify-content: center;
            align-items: center;
            text-align: center;
            gap: 18px;
        }
        .pwelement_'. $el_id .' .pwe-header-date-block {
            margin-top: 18px;
        }
        .pwelement_'. $el_id .' .pwe-header-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .pwelement_'. $el_id .' .pwe-header-text .pwe-header-location {
            display: block;
            margin: 0;
        }
        .pwelement_'. $el_id .' .pwe-header-edition span {
            color: black;
        }
        .pwelement_'. $el_id .' .pwe-header-img-v1-desktop {
            display: none;
        }
        .pwelement_'. $el_id .' .pwe-header-img-v1-mobile {
            display: flex;
        }
        .pwe-header-wrapper {
            flex-direction: column;
        }
        .pwelement_'. $el_id .' .pwe-header-column,
        .pwelement_'. $el_id .' .pwe-header-content-column,
        .pwelement_'. $el_id .' .pwe-header-image-column {
            width: 100%;
            max-width: 1200px;
            padding: 0;
            text-align: center;
        }
        .pwelement_'. $el_id .' .pwe-header-column {
            margin: 18px auto;
        }
        .pwelement_'. $el_id .' .pwe-header-content-column {
            padding: 36px 18px;
        }
        .pwelement_'. $el_id .' .pwe-header-bottom {
            flex-direction: column;
            justify-content: center;
            gap: 18px;
            padding-top: 18px;
        }
        .pwelement_'. $el_id .' .pwe-header-text :is(h1, h2, .pwe-header-edition) {
            text-align: center;
            width: auto;
            font-size: 22px;
        }
        .pwelement_'. $el_id .' .pwe-header-text h1 {
            padding-top: 0;
        }
        .pwelement_'. $el_id .' .pwe-header-text h2 {
            margin-top: 0;
        }
        .pwelement_'. $el_id .' .pwe-header-text .pwe-header-edition {
            margin-top: 10px;
        }
        .pwelement_'. $el_id .' .pwe-header-title {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .pwelement_'. $el_id .' .pwe-header-edition {
            width: fit-content;
        }
        .pwelement_'. $el_id .' .pwe-header-content-column {
            width: 100%;
        }
        .pwelement_'. $el_id .' .pwe-header-image-column {
            width: 100%;
        }
        .pwelement_'. $el_id .' .pwe-header-bottom .pwe-association {
            width: 100% !important;
        }
        .pwelement_'. $el_id .' .pwe-header-bottom .header-button {
            width: 100% !important;
            max-width: 320px !important;
        }
        .pwelement_'. $el_id .' .el-hidden-desktop {
            display: none;
        }';
        if ($pwe_header_center == true) {
            $output .= '
            .pwelement_'. $el_id .' .pwe-header-main-content-block {
                flex-direction: column;
            }
            .pwelement_'. $el_id .' .pwe-header .pwe-header-wrapper {
                justify-content: center;
            }
            .pwelement_'. $el_id .' .pwe-header .header-center-date {
                font-size: 34px;
            }
            .pwelement_'. $el_id .' .pwe-header-text .pwe-header-city {
                display: none;
            }
            .pwelement_'. $el_id .' .pwe-header  .pwe-header-title {
                gap: 15px;
            }
            .pwelement_'. $el_id .' .pwe-header-bottom {
                margin: 0 auto;
            }';
        }
        $output .='
    }
    @media(max-width: 450px) {
        .pwelement_'. $el_id .' .pwe-header-date-block {
            margin-top: 18px;
        }
    }
</style>

<div id="pweHeader" class="pwe-header">
    <div class="pwe-header-container pwe-header-background" style="background-image: url('. $background_header .');">
        <div class="pwe-bg-image1 pwe-bg-image"></div>
        <div class="pwe-bg-image2 pwe-bg-image"></div>
        <div class="pwe-bg-image3 pwe-bg-image"></div>

        <div class="pwe-header-wrapper">

            <div class="pwe-header-column pwe-header-content-column">
                <div class="pwe-header-column-container">

                    <div class="pwe-header-text">
                        <div class="pwe-header-main-content-block">
                            <img class="pwe-header-logo" src="'. $logo_url .'" alt="logo-'. $trade_fair_name .'">';
                            $output .= ($pwe_header_center == true) ? '<p class="pwe-header-edition"><span>'. $trade_fair_edition .'</span></p>' : ''; $output .= '
                            <div class="pwe-header-title">
                                <h1>'. $trade_fair_desc .'</h1>
                                <h2 class="header-center-date">'. $actually_date .'</h2>';
                                $city = PWEHeader::multi_translation("warsaw");
                                $output .= ($pwe_header_center != true) ? '<p class="pwe-header-edition"><span>'. $trade_fair_edition .'</span></p>' : '';
                                $output .= ($pwe_header_center == true) ? '<p class="pwe-header-city">'. $city .'</p>' : '';
                                $output .= '
                            </div>
                        </div>
                        <div class="pwe-header-date-block">
                            <h2>[trade_fair_date_multilang]<span class="el-hidden-desktop" style="text-transform: capitalize;">, '. $city .'</span></h2>
                            <p class="pwe-header-city pwe-header-location">'. PWEHeader::multi_translation("warsaw_poland") .'</p>
                        </div>
                    </div>

                    <div class="pwe-header-bottom">
                        <div id="pweBtnRegistration" class="pwe-btn-container header-button">
                            <a class="pwe-link pwe-btn" href="'. $pwe_header_register_button_link .'" alt="'. PWEHeader::multi_translation("link_to_registration") .'">
                                '. PWEHeader::multi_translation("register") .'
                                <span class="btn-small-text" style="display: block; font-weight: 300;">
                                    '. PWEHeader::multi_translation("free_ticket") .'
                                </span>
                            </a>
                            <span class="btn-angle-right">&#8250;</span>
                        </div>';

                        $pwe_header_buttons_urldecode = urldecode($pwe_header_buttons);
                        $pwe_header_buttons_json = json_decode($pwe_header_buttons_urldecode, true);
                        if ($pwe_header_buttons_json["0"]["pwe_header_button_text"]) {
                            $output .= '
                            <style>
                                .pwelement_'. $el_id .' #pweBtnRegistration {
                                    display: none;
                                }
                                .pwelement_'. $el_id .' .pwe-header .pwe-btn-container {
                                    width: 240px;
                                }
                            </style>';
                        }
                        if (is_array($pwe_header_buttons_json)) {
                            foreach ($pwe_header_buttons_json as $button) {
                                $button_url = $button["pwe_header_button_link"];
                                $button_text = $button["pwe_header_button_text"];

                                $target_blank_aditional = (strpos($button_url, 'http') !== false) ? 'target="blank"' : '';
                                if(!empty($button_url) && !empty($button_text) ) {
                                    $output .= '<div class="pwe-btn-container header-button">
                                        <a class="pwe-link pwe-btn" href="'. $button_url .'" '. $target_blank_aditional .' alt="'. $button_url .'">'. $button_text .'</a>
                                    </div>';
                                }
                            }
                        }

                    $output .= '
                    </div>';

                    // Countdown widget --------------------------------------------------------------------------------------<
                    if ($pwe_header_counter) {
                        require_once plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . 'widgets/counter-to-fair.php';
                    }

                    $output .= '
                </div>';

                // Partners widget --------------------------------------------------------------------------------------<
                $cap_logotypes_data = ($pwe_header_cap_auto_partners_off != true) ? PWECommonFunctions::get_database_logotypes_data() : "";

                if (!empty($cap_logotypes_data) || !empty($pwe_header_partners_items) || !empty($pwe_header_partners_catalog)) {
                // if (!empty($pwe_header_partners_items) || !empty($pwe_header_partners_catalog)) {
                    require_once plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . 'widgets/partners-widget.php';
                }
                $output .= '
            </div>

            <div class="video-background">
                <div class="video-overlay"></div>
                <video autoplay muted loop preload="auto" class="bg-video">
                    <source src="/doc/header.mp4" media="(min-width: 961px)">
                </video>
            </div>

        </div>
    </div>
</div>';