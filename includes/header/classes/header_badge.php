<?php

$text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');
$btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
$btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);
$btn_border = '1px solid ' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);

if ($text_color == '' || $text_color == '#000000' || $text_color == 'black') {
    $text_shadow = 'white !important;';
} else {
    $text_shadow = 'black !important;';
}

if ($pwe_header_modes == "conference_mode") {
    $main_badge_color = self::$accent_color;
} else {
    $main_badge_color = self::$main2_color;
}

$main_header_color_manual_hidden = isset($atts['main_header_color_manual_hidden']) ? $atts['main_header_color_manual_hidden'] : null;
$main_header_color = isset($atts['main_header_color']) ? $atts['main_header_color'] : null;
$main_header_color_text_manual_hidden = isset($atts['main_header_color_text_manual_hidden']) ? $atts['main_header_color_text_manual_hidden'] : null;
$main_header_color_text = isset($atts['main_header_color_text']) ? $atts['main_header_color_text'] : null;

$main_header_color = self::findColor($main_header_color_manual_hidden, $main_header_color, $main_badge_color) . '!important';
$main_header_color_text = self::findColor($main_header_color_text_manual_hidden, $main_header_color_text, 'white') . '!important';

$darker_btn_color = self::adjustBrightness($main_header_color, -20);

if ($pwe_header_modes == "conference_mode") {
    $pwe_header_overlay_color = empty($pwe_header_overlay_color) ? self::$main2_color : $pwe_header_overlay_color;
    $pwe_header_overlay_range = $pwe_header_overlay_range == 0 ? 0.7 : $pwe_header_overlay_range;
}

if ($pwe_header_modes == "registration_mode") {
    $pwe_header_title = $trade_fair_desc;
    $pwe_header_title_short = (get_locale() == 'pl_PL') ? "[trade_fair_desc_short]" : "[trade_fair_desc_short_eng]";
} else if ($pwe_header_modes == "conference_mode") {
    $pwe_header_title = get_the_title();
    $pwe_header_title_short = get_the_title();
}

if ($pwe_header_congress_logo_color != true) {
    $congress_logo_url = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/kongres.webp') ? '/doc/kongres.webp' : '');
} else {
    $congress_logo_url = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/doc/kongres-color.webp') ? '/doc/kongres-color.webp' : '/doc/kongres.webp');
}
$pwe_header_conference_logo_url = (empty($pwe_header_conference_logo_url)) ? $congress_logo_url : $pwe_header_conference_logo_url;
$pwe_header_conference_link = empty($pwe_header_conference_link)
? (get_locale() == 'pl_PL' ? '/wydarzenia/' : '/en/conferences')
: $pwe_header_conference_link;
$pwe_header_reg_logo = ($pwe_header_modes == "conference_mode") ? $pwe_header_conference_logo_url : $logo_url;
$pwe_header_reg_logo_link = ($pwe_header_modes == "conference_mode") ? $pwe_header_conference_link : $base_url;



if (glob($_SERVER['DOCUMENT_ROOT'] . '/doc/header_mobile.webp', GLOB_BRACE) && $pwe_header_modes != "conference_mode") {
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
        position: relative;
        display: flex;
        padding: 0 18px 36px;
    }
    .pwelement_'. $el_id .' .header-info-column,
    .pwelement_'. $el_id .' .header-form-column {
        width: 50%;
        display: flex;
        justify-content: center;
    }
    .pwelement_'. $el_id .' .header-info-column {
        flex-direction: column;
        align-items: center;
    }
    .pwelement_'. $el_id .' .header-form-column {
        display: flex;
        flex-direction: column;
        justify-content: start;
        align-items: center;
    }
    .pwelement_'. $el_id .' .pwe-header-logo-container {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-top: 18px;
        padding: 24px;
    }
    .pwelement_'. $el_id .' .pwe-header-logo-container:before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border-radius: 16px;
    }
    .pwelement_'. $el_id .' .pwe-header-logo-container h1 {
        text-transform: uppercase;
        padding-top: 12px;
        font-size: 22px !important;
        margin: 0;
    }
    .pwelement_'. $el_id .' .pwe-header-logo-container :is(h1, h2, p) {
        position: relative;
        z-index: 2;
    }
    .pwelement_'. $el_id .' .pwe-header-logo-container :is(h1, h2, p:not(.pwe-header-edition)),
    .pwelement_'. $el_id .' .form-logo-container h2 {
        color: '. $main_header_color_text .';
        text-align: center;
        font-weight: 700;
    }
    .pwelement_'. $el_id .' .pwe-header-edition,
    #pweForm .form-edition {
        text-align: center;
        font-weight: 700;
    }
    .pwelement_'. $el_id .' .pwe-header-edition {
        max-width: '. $pwe_header_logo_width .'px !important;
        width: 100%;
        background: white;
        border-radius: 0;
        color: '. $main_header_color .';
        font-size: 28px;
        margin: 0;
        margin-top: 9px;
        padding: 3px 0;
        line-height: 1;
        text-transform: uppercase;
    }
    .pwelement_'. $el_id .' .pwe-header-text :is(h1, h2),
    .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-title h4,
    .pwelement_'. $el_id .' .pwe-association-title h2 {
        text-shadow: 0 0 1px '. $text_shadow .';
    }
    .pwelement_'. $el_id .' .pwe-header-text h1 {
        margin: 0;
    }
    .pwelement_'. $el_id .' .pwe-header-logo-container h2 {
        margin: 0;
        font-size: 28px !important;
    }
    .pwelement_'. $el_id .' .pwe-header-logo {
        max-width: '. $pwe_header_logo_width .'px !important;
        margin: 0 20px;
        position: relative;
        z-index: 2;
    }
    .pwelement_'. $el_id .' .header-info-column .pwe-association {
        margin-top: 36px;
    }
    .pwelement_'. $el_id .' .header-form-column .pwe-association {
        display: none;
    }
    .pwelement_'. $el_id .' .pwe-header .pwe-logotypes-title h4 {
        box-shadow: none !important;
    }
    .pwelement_'. $el_id .' .pwe-header-logotypes {
        margin-top: 36px;
        width: 100%;
    }
    .pwelement_'. $el_id .' #pweForm .pwe-btn {
        background-color: '. $main_header_color .';
        border: 2px solid '. $main_header_color .';
        color: '. $main_header_color_text .';
    }
    .pwelement_'. $el_id .' #pweForm .pwe-btn:hover {
        color: '. $main_header_color_text .';
        background-color: '. $darker_btn_color .'!important;
        border: 2px solid '. $darker_btn_color .'!important;
    }
    .pwelement_'. $el_id .' #pweForm .form-container:before {
        background-color: '. $main_header_color .';
    }
    .pwelement_'. $el_id .' .pwe-header-logo-container-mobile {
            display: none;
    }
    @media (max-width: 960px) {
        .pwelement_'. $el_id .' .pwe-header-logo-container-desktop,
        .pwelement_'. $el_id .' .pwe-header-text {
            display: none;
        }
        .pwelement_'. $el_id .' .pwe-header-logo-container-mobile {
            display: flex;
        }
        .pwelement_'. $el_id .' #pweForm .form-logo-container {
            background-color: '. $main_header_color .';
        }
        .pwelement_'. $el_id .' .pwe-header-wrapper {
            flex-direction: column-reverse;
            padding: 0 18px 0;
        }
        .pwelement_'. $el_id .' .header-info-column,
        .pwelement_'. $el_id .' .header-form-column {
            width: 100%;
            padding-bottom: 18px;
        }
        .pwelement_'. $el_id .' .header-form-column {
            align-items: center;
        }
        .pwelement_'. $el_id .' .header-form-column .pwe-association {
            display: block;
        }
        .pwelement_'. $el_id .' .pwe-header-text h1 {
            margin: 0;
            padding: 0 !important;
        }
        .pwelement_'. $el_id .' .pwe-header-text {
            padding: 36px 0 !important;
        }
        .pwelement_'. $el_id .' .pwe-header-text,
        .pwelement_'. $el_id .' .pwe-header-logo-container {
            max-width: 450px;
        }
    }
    @media (max-width: 450px) {
        .pwelement_'. $el_id .' .pwe-header-logo-container h1 {
            font-size: 20px !important;
        }
    }
</style>';

if ($pwe_header_modes == "conference_mode") {
    $output .= '
    <style>
        .pwelement_'. $el_id .' .pwe-header-logo-container:before {
            background-color: '. $main_header_color .';
        }
    </style>';
} else {
    $output .= '
    <style>
        .pwelement_'. $el_id .' .pwe-header-logo-container:before {
            background-color: '. $main_header_color .';
        }
    </style>';
}

if (!is_numeric($trade_fair_edition_shortcode) || $trade_fair_edition_shortcode == 1) {
    $output .= '
    <style>
        .pwelement_'. $el_id .' .pwe-header-edition {
            font-size: 20px;
        }
    </style>';
}

$output .= '
<div id="pweHeader" class="pwe-header">
    <div style="background-image: url('. $background_header .');"  class="pwe-header-container pwe-header-background">
        <div class="pwe-header-wrapper">

            <div class="header-info-column">
                <div class="pwe-header-text">
                    <h1>'. $pwe_header_title .'</h1>
                </div>
                <div class="pwe-header-logo-container pwe-header-logo-container-desktop">
                    <img class="pwe-header-logo" src="'. $pwe_header_reg_logo .'" alt="logo-'. $trade_fair_name .'">';
                    if (!empty($trade_fair_edition_shortcode) && $pwe_header_modes != "conference_mode") {
                        $output .= '<p class="pwe-header-edition">'. $trade_fair_edition .'</p>';
                    }
                    $output .= '
                    <h2>'. $actually_date .'</h2>
                    <p>Ptak Warsaw Expo</p>
                </div>';

                // Congress logo START --------------------------------------------------------------------------------------<
                if ($pwe_header_association_hide != true) {
                    if (!empty($pwe_header_conference_logo_url)) {
                        $output .= '
                        <style>
                            .pwe-association {
                                position: relative;
                                width: 100%;
                            }
                            .pwe-association-title {
                                display: flex;
                                justify-content: center;
                            }
                            .pwe-association-title h2 {
                                color: '. $text_color .';
                                margin: 0;
                                text-align: center !important;
                                margin-top: 0 !important;
                                box-shadow: none !important;
                                text-transform: inherit !important;
                            }
                            .pwe-association-logotypes {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                flex-wrap: wrap;
                                gap: 10px;
                            }
                            .pwe-association-logotypes .pwe-logo,
                            .pwe-association-logotypes .slides div {
                                background-size: contain;
                                background-repeat: no-repeat;
                                background-position: center;
                                min-width: 140px;
                                height: fit-content;
                                aspect-ratio: 3/2;
                                margin: 5px;
                            }
                            .pwe-association-logotypes .pwe-logo {
                                min-width: 200px;
                            }
                        </style>';

                        if ($association_fair_logo_color != 'true') {
                            $output .= '
                                <style>
                                    .pwelement_'. $el_id .' .pwe-association-logotypes .pwe-logo {
                                        filter: brightness(0) invert(1);
                                        transition: all .3s ease;
                                    }
                                </style>';
                        }

                        $output .= '
                        <div id="pweAssociation" class="pwe-association">
                            <div class="main-heading-text pwe-uppercase pwe-association-title">';
                                if ($pwe_header_modes == "conference_mode") {
                                    $output .= '<h2>' . self::languageChecker('Wydarzenie organizowane w ramach targów:', 'Event organised as part of the fair:') . '</h2>';
                                } else {
                                    $output .= '<h2>' . self::languageChecker('Wydarzenia Towarzyszące', 'Side Events') . '</h2>';
                                }
                            $output .= '
                            </div>
                            <div class="pwe-association-logotypes">';
                                if ($pwe_header_modes == "registration_mode") {
                                    $output .= '
                                        <a class="pwe-association-logo" href="'. $pwe_header_conference_link .'">
                                            <div class="pwe-logo" style="background-image: url(' . $pwe_header_conference_logo_url . ');"></div>
                                        </a>
                                    ';
                                } else {
                                    $output .= '
                                        <a class="pwe-association-logo" href="'. $base_url .'">
                                            <div class="pwe-logo" style="background-image: url(/doc/logo.webp);"></div>
                                        </a>
                                    ';
                                }
                            $output .= '
                            </div>
                        </div>';

                    }
                }

            $output .= '
            </div>';

            $output .= '
            <div class="header-form-column">';

                $output .= '
                <div class="pwe-header-logo-container pwe-header-logo-container-mobile">
                    <img class="pwe-header-logo" src="'. $pwe_header_reg_logo .'" alt="logo-'. $trade_fair_name .'">';
                    if (!empty($trade_fair_edition_shortcode) && $pwe_header_modes != "conference_mode") {
                        $output .= '<p class="pwe-header-edition">'. $trade_fair_edition .'</p>';
                    }
                    $output .= '
                    <h2>'. $actually_date .'</h2>
                    <h1 class="">'. $pwe_header_title_short .'</h1>
                </div>';

                include_once plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . 'elements/registration-header.php';
                $output .= PWElementRegHeader::output($pwe_header_form_id, $pwe_header_modes, $pwe_header_reg_logo, $actually_date, $registration_name = "header");

                if (get_locale() == 'pl_PL') {
                    $registration_button_text = ($registration_button_text == "") ? 'Zarejestruj się<span style="display: block; font-weight: 300;">Odbierz darmowy bilet</span>' : $registration_button_text;
                } else {
                    $registration_button_text = ($registration_button_text == "") ? 'Register<span style="display: block; font-weight: 300;">Get a free ticket</span>' : $registration_button_text;
                }

                if (class_exists('GFAPI')) {
                    function get_form_id_by_title($title) {
                        $forms = GFAPI::get_forms();
                        foreach ($forms as $form) {
                            if ($form['title'] === $title) {
                                return $form['id'];
                            }

                        }
                        return null;
                    }

                    // function custom_gform_submit_button($button, $form) {
                    //     global $registration_button_text, $pwe_header_form_id;
                    //     $registration_form_id_nmb = get_form_id_by_title($pwe_header_form_id);

                    //     if ($form['id'] == $registration_form_id_nmb) {
                    //         $button = '<input type="submit" id="gform_submit_button_'.$registration_form_id_nmb.'" class="gform_button button" value="" onclick="if(window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]){return false;}  if( !jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity || jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity()){window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]=true;}  " onkeypress="if( event.keyCode == 13 ){ if(window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]){return false;} if( !jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity || jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;)[0].checkValidity()){window[&quot;gf_submitting_'.$registration_form_id_nmb.'&quot;]=true;}  jQuery(&quot;#gform_'.$registration_form_id_nmb.'&quot;).trigger(&quot;submit&quot;,[true]); }">
                    //         <div class="pwe-btn-container">
                    //             <button id="pweRegister" class="btn pwe-btn">'. $registration_button_text .'</button>
                    //         </div>';
                    //     }
                    //     return $button;
                    // }
                    // add_filter('gform_submit_button', 'custom_gform_submit_button', 10, 2);
                }
            $output .= '
            </div>

        </div>
    </div>
</div>';

$output .= '
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pweElement = document.querySelector(".pwelement_'. $el_id .'");
        const pweAssociation = document.querySelector(".pwelement_'. $el_id .' .pwe-association");

        if (pweAssociation == null) {
            pweAssociation.style.display = "none";
        }
    });

    function updateCountryInput() {
        const countryInput = document.querySelector(".country input");
        const selectedFlag = document.querySelector(".iti__selected-flag");
        if (countryInput && selectedFlag) {
            countryInput.value = selectedFlag.getAttribute("title") || "";
        }
    }

    function addEventListenersToForm() {
        document.querySelectorAll("input, select, textarea, button").forEach(element => {
            ["change", "input", "click", "focus"].forEach(event => {
                element.addEventListener(event, updateCountryInput);
            });
        });
    }

    function observeFlagChanges() {
        const selectedFlag = document.querySelector(".iti__selected-flag");
        if (selectedFlag) {
            new MutationObserver(mutations => {
                if (mutations.some(mutation => mutation.attributeName === "aria-expanded")) {
                    updateCountryInput();
                }
            }).observe(selectedFlag, { attributes: true });
        }
    }

    addEventListenersToForm();
    observeFlagChanges();
</script>';

return $output;