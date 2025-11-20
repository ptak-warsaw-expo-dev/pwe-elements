<?php

$text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');
$btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
$btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);
$btn_border = '1px solid ' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'black');
$darker_btn_color = self::adjustBrightness($btn_color, -20);

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
        min-height: auto !important;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .pwelement_'. $el_id .' .header-wrapper-column {
        max-width: 650px;
        width: 100%;
        justify-content: space-evenly;
        align-items: center;
        display: flex;
        flex-direction: column;
        padding: 36px;
    }
    .pwelement_'. $el_id .' .pwe-header-simple-logo {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 18px;
    }
    .pwelement_'. $el_id .' .pwe-header-simple-logo .pwe-btn-container {
        width: 240px;
        height: 50px;
        padding: 0;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-btn {
        background-color: '. $btn_color .' !important;
        border: '. $btn_color .' !important;
        color: '. $btn_text_color .' !important;
        width: 100%;
        height: 100%;
        transform: scale(1) !important;
        text-transform: uppercase;
        transition: .3s ease;
        font-size: 15px;
        font-weight: 600;
        padding: 6px 18px !important;
        letter-spacing: 0.1em;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .pwelement_'. $el_id .' .pwe-header-simple-logo .pwe-btn:hover {
        color: '. $btn_text_color .';
        background-color: '. $darker_btn_color .'!important;
        border: 2px solid '. $darker_btn_color .'!important;
    }
    .pwelement_'. $el_id .' .pwe-header-text {
        display: flex;
        flex-direction: column-reverse;
    }
    .pwelement_'. $el_id .' .pwe-header-text h2 {
        font-size: 40px;
        margin: 0;
    }
    @media (max-width: 1200px) {
        .pwelement_'. $el_id .' .pwe-header-text h2 {
            font-size: calc(24px + (40 - 24) * ( (100vw - 300px) / (1200 - 300) ));
        }
    }
    @media (min-width: 960px) {
        .pwelement_'. $el_id .' .pwe-header-wrapper {
            min-height: 350px !important;
            height: 350px;
        }
        .pwelement_'. $el_id .' .header-wrapper-column {
            max-width: 1200px;
            flex-direction: row;
            gap: 60px;
        }
    }
    @media (max-width: 960px) {
        .pwelement_'. $el_id .' .header-wrapper-column {
            padding: 18px;
        }
    }
</style>

<div id="pweHeader" class="pwe-header">
    <div style="background-image: url('. $background_header .');"  class="pwe-header-container pwe-header-background">
        <div class="pwe-header-wrapper">

            <div class="header-wrapper-column">';

                if ($pwe_header_modes == "simple_mode" &&  $pwe_header_simple_conference == true) {
                    $output .='
                    <div class="pwe-header-simple-logo">
                        <img class="pwe-header-logo" src="'. $logo_url .'" alt="logo-'. $trade_fair_name .'">
                        <div id="pweBtnRegistration" class="pwe-btn-container header-button">
                            <a id="main-content" class="pwe-link pwe-btn" href="'. $pwe_header_register_button_link .'" '.
                                self::languageChecker('alt="link do rejestracji"><h3 style="margin: 0; color: white;">Weź udział</h3></span>', 'alt="link to registration">Take part</span>')
                            .'</a>
                        </div>
                    </div>';
                } else {
                    $output .= '<img class="pwe-header-logo" src="'. $logo_url .'" alt="logo-'. $trade_fair_name .'">';
                }

                $output .= '
                <div class="pwe-header-text">
                    <h1>'. $trade_fair_desc .'</h1>
                    <h2>'. $trade_fair_date .'</h2>
                </div>';

            $output .= '
            </div>

        </div>
    </div>
</div>';

return $output;