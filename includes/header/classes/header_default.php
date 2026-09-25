<?php 

$text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');
$btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
$btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], '#000000');
$btn_border = '1px solid ' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'black');
$darker_btn_color = self::adjustBrightness($btn_color, -20);

if ($text_color == '' || $text_color == '#000000' || $text_color == 'black') {
    $text_shadow = 'white !important;';
} else {
    $text_shadow = 'black !important;';
}

$target_blank = (strpos($pwe_header_conferences_button_link, 'http') !== false) ? 'target="blank"' : '';

if ($pwe_header_logo_marg_pag == 'true') {
    $output .= '
    <style>
        .pwelement_'. $el_id .' .header-wrapper-column {
            padding: 0 18px 36px;
        }
        .pwelement_'. $el_id .' .pwe-header-text {
            padding: 0 0 18px;
        }
        .pwelement_'. $el_id .' .pwe-header-text h1 {
            margin: 0;
        }
    </style>';
}

if (glob($_SERVER['DOCUMENT_ROOT'] . '/doc/header_mobile.webp', GLOB_BRACE)) {
    $output .= '
    <style>
        @media (max-width: 569px) {
            #pweHeader .pwe-header-container {
                background-image: url(/doc/header_mobile.webp) !important;
            }
        }
    </style>';
}

$output .= '
<style>
    .pwelement_'. $el_id .' .pwe-header-container:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: '. $pwe_header_overlay_color .';
        opacity: '. $pwe_header_overlay_range .';
        z-index: 0;
    }
    .pwelement_'. $el_id .' .pwe-header-wrapper {
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .pwelement_'. $el_id .' .header-wrapper-column {
        max-width: 750px;
        width: 100%;
        justify-content: space-evenly;
        align-items: center;
        display: flex;
        flex-direction: column;
        padding: 36px 18px;
    }
    .pwelement_'. $el_id .' .pwe-header-text :is(h1, h2), 
    .pwe-header .pwe-logotypes-title h4 {
        text-shadow: 2px 2px '. $text_shadow .';
    }
    .pwelement_'. $el_id .' .header-button a {
        padding: 0 !important;
        height: 70px;
        display: flex;
        flex-flow: column;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
        z-index: 1;
    }
    .pwelement_'. $el_id .' .pwe-header-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        padding: 18px 0;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn-container {
        width: 320px;
        height: 75px;
        padding: 0;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn {
        background-color: '. $btn_color .' !important;
        color: '. $btn_text_color .' !important;
        border: '. $btn_border .' !important;
        width: 100%;
        height: 100%;
        transform: scale(1) !important;
        transition: .3s ease;
        font-size: 15px;
        font-weight: 600;
        padding: 6px 18px !important;
        letter-spacing: 0.1em;
        text-align: center;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn:hover {
        color: '. $btn_text_color .';
        background-color: '. $darker_btn_color .'!important;
        border: 1px solid '. $darker_btn_color .'!important;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-container-logotypes-gallery {
        position: relative;
        z-index: 1;
    }
    .pwelement_'. $el_id .' .pwe-header-logotypes {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        padding: 0 18px 36px;
        gap: 18px;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-association {
        padding: 0 18px 36px;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-header-curled-sheet {
        z-index: 1;
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
    }
    @media (max-width: 1200px) {
        .pwelement_'. $el_id .' .pwe-header .pwe-header-curled-sheet {
            display: none;
        }
    }
    @media (max-width: 960px) {
        .pwelement_'.$el_id.' .pwe-header-logotypes .pwe-container-logotypes-gallery {
            width: 100% !important;
        }
        .pwelement_'.$el_id.' .pwe-header .pwe-btn-container {
            width: 260px;
            height: 70px;
        }
        .pwelement_'.$el_id.' .pwe-header .pwe-btn {
            font-size: 13px;
        }
    }
</style>

<div id="pweHeader" class="pwe-header">
    <div style="background-image: url('. $background_header .');"  class="pwe-header-container pwe-header-background">
        <div class="pwe-header-wrapper">
            <div class="header-wrapper-column">

                <img class="pwe-header-logo" src="'. $logo_url .'" alt="logo-'. $trade_fair_name .'">

                <div class="pwe-header-text">
                    <h1>'. $trade_fair_desc .'</h1>
                    <h2>'. $trade_fair_date .'</h2>
                </div>

                <div class="pwe-header-buttons">';

                    if (in_array('register', explode(',', $pwe_header_button_on))) {
                        $output .= '
                        <div id="pweBtnRegistration" class="pwe-btn-container header-button">
                            <a class="pwe-link pwe-btn" href="'. $pwe_header_register_button_link .'" alt="'. self::languageChecker('link do rejestracji', 'link to registration') .'">
                                '. self::languageChecker('Zarejestruj się', 'Register') .'
                                <span class="btn-small-text" style="display: block; font-weight: 300;">
                                    '. self::languageChecker('Odbierz darmowy bilet', 'Get a free ticket') .'
                                </span>
                            </a>
                        </div>';
                    }
                    if (in_array('ticket', explode(',', $pwe_header_button_on))) {
                        $output .= '
                        <div id="pweBtnTickets" class="pwe-btn-container header-button">
                            <a class="pwe-link pwe-btn" href="'. $pwe_header_tickets_button_link .'" '.
                                self::languageChecker('alt="link do biletów">Kup bilet', 'alt="link to tickets">Buy a ticket')
                            .'</a>
                        </div>';
                    }
                    if (in_array('conference', explode(',', $pwe_header_button_on))) {
                        if (empty($pwe_header_conferences_title)) {
                            $pwe_header_conferences_title = (get_locale() == 'pl_PL') ? 'KONFERENCJE' : 'CONFERENCES';
                        } else {
                            $pwe_header_conferences_title = urldecode(base64_decode($pwe_header_conferences_title));
                        }
                        $output .= '
                        <div id="pweBtnConferences" class="pwe-btn-container header-button">
                            <a class="pwe-link pwe-btn" href="'. $pwe_header_conferences_button_link .'" '. $target_blank .' title="'.
                                self::languageChecker('konferencje', 'conferences').'">'. $pwe_header_conferences_title
                            .'</a>
                        </div>';
                    }

                    $pwe_header_buttons_urldecode = urldecode($pwe_header_buttons); 
                    $pwe_header_buttons_json = json_decode($pwe_header_buttons_urldecode, true);
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

            $output .= '
            </div>';

            // Logotypes slider --------------------------------------------------------------------------------------<
            $pwe_header_logotypes_urldecode = urldecode($pwe_header_logotypes);
            $pwe_header_logotypes_json = json_decode($pwe_header_logotypes_urldecode, true);
            if ($pwe_header_modes != "simple_mode") {
                if (is_array($pwe_header_logotypes_json) && !empty($pwe_header_logotypes_json)) {
                    $output .= '<div class="pwe-header-logotypes">';
                        foreach ($pwe_header_logotypes_json as $logotypes) {
                            $logotypes_width = $logotypes["logotypes_width"];
                            $logotypes_media = $logotypes["logotypes_media"];
                            $logotypes_catalog = $logotypes["logotypes_catalog"];
                            if(!empty($logotypes_catalog) || !empty($logotypes_media)) {
                                // Adding the result from additionalOutput to $output
                                $output .= PWElementAdditionalLogotypes::additionalOutput($atts, $el_id, $logotypes);
                            }
                        }
                    $output .= '</div>';
                }
            }

            // Parking widget --------------------------------------------------------------------------------------<
            require_once plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . 'widgets/parking-widget.php';
            
        $output .= '
        </div>
    </div>
</div>';


$output .= '
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pweLogotypesElement = document.querySelector(".pwelement_'. $el_id .' .pwe-header-logotypes");

        if ((pweLogotypesElement && pweLogotypesElement.children.length === 0)) {
            pweLogotypesElement.classList.add("desktop-hidden", "tablet-hidden", "mobile-hidden");
        }

        if (pweLogotypesElement) {
            pweLogotypesElement.style.opacity = 1;
        }

    });
</script>';
        
return $output;