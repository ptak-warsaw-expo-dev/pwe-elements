<?php

$output = '';

if (
    (
        $registration_type == 'PWERegistrationVisitors' &&
        (
            ($domain_gr == 'gr3' && !$register_show_ticket) || $domain_gr != 'gr3'
        ) &&
        strpos($source_utm, 'utm_source=platyna') === false
    )
    ||
    strpos($source_utm, 'utm_source=byli') !== false
    ||
    strpos($source_utm, 'utm_source=premium') !== false
) {

    $btn_color_vip = '#B69663';
    $darker_btn_vip_color = self::adjustBrightness($btn_color_vip, -20);

    $btn_color_premium = self::$accent_color;
    $darker_btn_premium_color = self::adjustBrightness($btn_color_premium, -20);



    if (strpos($source_utm, 'utm_source=byli') !== false) {
        $output .= '
        <style>
            #pweRegistration input[type="submit"] {
                background-color: '. $btn_color_vip .' !important;
                border: 2px solid '. $btn_color_vip .' !important;
                color: white;
            }
            #pweRegistration input[type="submit"]:hover {
                background-color: '. $darker_btn_vip_color .' !important;
                border: 2px solid '. $darker_btn_vip_color .' !important;
            }
        </style>';
    } else if (strpos($source_utm, 'utm_source=premium') !== false || (strpos($source_utm, 'utm_source=platyna') !== false && $domain_gr !== "gr2") ) {
        $output .= '
        <style>
            #pweRegistration input[type="submit"] {
                background-color: '. $btn_color_premium .' !important;
                border: 2px solid '. $btn_color_premium .' !important;
                color: white;
            }
            #pweRegistration input[type="submit"]:hover {
                background-color: '. $darker_btn_premium_color .' !important;
                border: 2px solid '. $darker_btn_premium_color .' !important;
            }
        </style>';
    } else {
        $output .= '
        <style>
            #pweRegistration input[type="submit"] {
                background-color: '. $btn_color .';
                border: 2px solid '. $btn_color .';
                color: '. $btn_text_color .';
            }
            #pweRegistration input[type="submit"]:hover {
                background-color: '. $darker_btn_color .' !important;
                border: 2px solid '. $darker_btn_color .' !important;
            }
        </style>';
    }

    if (strpos($source_utm, 'utm_source=byli') !== false || strpos($source_utm, 'utm_source=premium') !== false || (strpos($source_utm, 'utm_source=platyna') !== false && $domain_gr !== "gr2")) {
        $output .= '
        <style>
            .row-parent:has(#pweRegistration) .wpb_column {
                padding: 0 !important;
            }
            .row-parent:has(#pweRegistration) {
                max-width: 100% !important;
                padding: 0 !important;
            }

            /* Zaślepka START <------------------------------------------< */
            .row-container:has(#pweRegistration) .wpb_column:not(:has(.pwe-registration.vip)) {
                max-width: 100% !important;
                width: 100% !important;
                height: auto;
            }
            .row-container:has(#pweRegistration) .wpb_column:not(:has(.pwe-registration.vip)) .uncode-single-media-wrapper {
                display: flex;
                justify-content: center;
            }
            .row-container:has(#pweRegistration) .wpb_column:not(:has(.pwe-registration.vip)) img {
                max-width: 300px !important;
            }
            /* Zaślepka END <------------------------------------------< */

            .wpb_column:has(#pweRegistration) {
                width: 66% !important;
                height: auto;
            }
            #pweRegistration {
                display: flex;
                min-height: 85vh;
                height: 100%;
            }
            #pweRegistration .pwe-mockup-column {
                width: 50%;
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
            }
            #pweRegistration .pwe-mockup-column img {
                height: 100%;
                float: right;
                object-fit: cover;
            }
            #pweRegistration .pwe-registration-column {
                position: relative;
                background-color: #E8E8E8;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 36px 18px;
                width: 50%;
                gap: 18px;
            }
            #pweRegistration .pwe-registration-title {
                min-width: 350px;
            }
            #pweRegistration .gform_wrapper {
                max-width: 350px;
            }
            #pweRegistration  .gform_footer {
                text-align: center;
            }
            #pweRegistration .gform_wrapper :is(label, .gfield_description, .show-consent) {
                color: black;
            }
            #pweRegistration :is(input[type="text"], input[type="number"], input[type="email"], input[type="tel"]) {
                border: 2px solid #d6d6d6 !important;
                border-radius: 10px;
                box-shadow: none !important;
                margin: 0;
                font-size: 14px !important;
            }
            #pweRegistration input[type="checkbox"] {
                border: 2px solid #d6d6d6 !important;
                border-radius: 50%;
            }
            #pweRegistration .gfield_consent_label  {
                font-size: 10px;
                line-height: 1.2 !important;
            }
            #pweRegistration .gfield_label {
                font-size: 14px !important;
            }
            #pweRegistration .gfield_required_asterisk {
                display: none !important;
            }
            #pweRegistration .pwe-registration-step-text {
                width: 100%;
                position: absolute;
                top: 18px;
                left: 18px;
            }
            #pweRegistration .pwe-registration-step-text p {
                margin: 0;
            }
            #pweRegistration .gform_fields {
                padding: 0 !important;
            }
            #pweRegistration .gform_legacy_markup_wrapper ul.gform_fields li.gfield {
                padding-right: 0;
            }
            @media (max-width: 1150px) {
                .wpb_row:has(#pweRegistration) {
                    display: flex !important;
                    flex-direction: column !important;
                }
                .wpb_column:has(#pweRegistration) {
                    width: 100% !important;
                }
                #pweRegistration .pwe-mockup-column,
                #pweRegistration .pwe-registration-column {
                    width: 100%;
                }
                #pweRegistration .pwe-registration-column {
                    padding: 72px 18px;
                }
            }
            @media (max-width: 960px) {
                .wpb_column:has(#pweRegistration) {
                    max-width: 100% !important;
                    padding: 0 !important;
                }
                .row-parent:has(#pweRegistration) {
                    padding: 0 !important;
                }
                #pweRegistration .pwe-registration-column {
                    padding: 36px 18px 18px;
                }
            }
            @media (max-width: 750px) {
                #pweRegistration {
                    flex-direction: column;
                }
                #pweRegistration .pwe-registration-title {
                    min-width: auto;
                }
                #pweRegistration .pwe-mockup-column {
                    height: 400px;
                }
            }
        </style>';

        if (strpos($source_utm, 'utm_source=byli') !== false) {
            $output .= '
            <style>
                #pweRegistration .pwe-mockup-column {
                    background-image: url(/wp-content/plugins/pwe-media/media/generator-wystawcow/gen-bg.jpg);
                }
            </style>';
        }

    }  else {

        $output .= '
        <style>
            .row-parent:has(#pweForm) {
                padding-top: 0 !important;
            }
            .row-inner:has(#pweForm) {
                height: inherit !important;
            }
            .wpb_column:has(#top10) {
                padding-top: 100px;
            }
            #pweForm {
                max-width: 555px;
            }
            #pweForm .gform_footer {
                padding-top: 18px !important;
            }
            @media (max-width:960px) {
                .wpb_column:has(#top10) {
                    padding-top: 18px !important;
                }
            }
            .row-container:has(#pweRegistration) {
                background-image: url(/doc/background.webp);
                background-repea: no-repeat;
                background-position: center;
                background-size: cover;
            }
            .exhibitors-catalog:has(#top10) {
                background-color: white;
                border: 2px solid '. $btn_color .' !important;
                border-radius: 18px;
            }
            @media (min-width: 959px) {
                .row-container:has(#pweForm) .wpb_column,
                .row-container:has(#top10) .wpb_column {
                    display: none;
                }
                .wpb_column:has(#top10),
                .wpb_column:has(#pweForm) {
                    display: table-cell !important;
                }
            }
            .wpb_column #pweForm {
                margin: 0 auto;
            }
            .wpb_column:has(#pweForm) {
                padding: 0;
            }
            #pweForm {
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                overflow: hidden;
            }
            #pweForm .form-container {
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 100%;
                padding: 0 0 36px;
                background: #e8e8e8;
                overflow: hidden;
                border-radius: 0 0 24px 24px;
            }
            #pweForm .form-badge-header {
                background-color: '. $btn_color .';
                width: 100%;
                height: 80px;
                display: flex;
                justify-content: space-between;
                padding: 10px 36px;
            }
            #pweForm .form-badge-header .form-header-title {
                font-size: 26px;
                font-weight: 700;
                margin: 0;
                color: white;
                display: none;
            }
            #pweForm .form-badge-header .form-header-image-qr {
                width: 60px;
                height: 60px;
                aspect-ratio: 1/1;
                object-fit: contain;
                border-radius: 10px;
                display: none;
            }
            #pweForm .form-badge-top {
                position: relative;
                width: 100%;
            }
            #pweForm .form-badge-right {
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 25px;
            }
            #pweForm .form-badge-bottom {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1;
                height: 25px;
                width: 100%;
            }
            #pweForm .form-badge-left {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 25px;
            }
            #pweForm .form-image-qr {
                position: absolute;
                right: 36px;
                top: 36px;
                width: 100px;
                object-fit: cover;
                border-radius: 10px;
                z-index: 1;
            }
            #pweForm .form {
                width: 90%;
                height: 100%;
                padding: 36px 36px 18px;
            }
            #pweForm .form .form-title {
                margin: 0;
                font-size: 32px;
                font-weight: 700;
            }
            #pweForm .form form {
                display: flex;
                flex-direction: column;
            }
            #pweForm input {
                border: 2px solid rgb(0, 0, 0) !important;
                border-radius: 10px;
                box-shadow: none !important;
            }
            #pweForm .gform_fields {
                padding: 0 !important;
            }
            #pweForm .iti--allow-dropdown {
                margin-top: 18px;
            }
            #pweForm .iti__country-list {
                list-style: none;
                padding: 0;
            }
            #pweForm input:not([type=checkbox]) {
                margin: 0 auto 0;
            }
            #pweForm .gfield_consent_description {
                overflow: unset;
            }
            #pweForm .gfield--type-consent{
                overflow: hidden !important;
            }
            #pweForm .gfield--type-consent span {
                display: inline !important;
            }
            #pweForm .gform_wrapper :is(label, .gfield_description),
            #pweForm .gform_legacy_markup_wrapper .gfield_required {
                font-size:12px;
                line-height: 15px;
                color: black !important;
            }
            #pweForm .gform_legacy_markup_wrapper .gform_footer {
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 auto !important;
                padding: 0;
                text-align: center;
            }
            #pweForm input[type=submit] {
                background-color: '. $btn_color .' !important;
                border: 2px solid '. $btn_color .' !important;
                border-radius: 10px !important;
                font-size: 14px;
                color: white;
                align-self: center;
                transform: scale(1) !important;
            }
            #pweForm input[type=submit]:hover {
                background-color: '. $darker_btn_color .' !important;
                border: 2px solid '. $darker_btn_color .' !important;
                color: white;
            }
            #pweForm .mail-error, #pweForm .tel-error, #pweForm .cons-error{
                margin: 0 11px;
                width:85%;
            }
            #pweForm .show-consent {
                color: black !important;
            }
            #pweForm form :is(.email-error, .phone-error, .cons-error) {
                font-size: 12px;
                color: red;
                width: 90%;
                margin-top: 0px;
                text-transform: uppercase;
                background-color: rgba(255, 223, 224);
            }
            #pweForm .gform_validation_errors {
                border: none;
                margin: 0;
                padding: 18px 0 0;
            }
            #pweForm .validation_message {
                padding: 0;
            }
            #pweForm .gfield {
                padding: 0;
            }
            #pweForm .gfield_error {
                border: none;
            }
            #pweForm .gfield_label {
                font-size: 14px !important;
            }
            #pweForm input[type="checkbox"]  {
                min-width: 16px !important;
                height: 16px !important;
                border-radius: 0px !important;
            }
            #pweForm .form-required::after {
                content: "" !important;
            }
            #pweForm .gfield_required_asterisk {
                display: none !important;
            }
            #pweForm .iti--allow-dropdown {
                margin-top: 0;
            }
            @media (max-width:960px) {
                #pweForm {
                    padding-bottom: 0;
                }
                #pweForm .form {
                    padding: 0 18px 0;
                }
                #pweForm form {
                    padding: 0;
                }
                #pweForm .form-image-qr,
                #pweForm .form-title {
                    display: none;
                }
                #pweForm .form-badge-header .form-header-title,
                #pweForm .form-badge-header .form-header-image-qr {
                    display: flex;
                    align-items: center;
                }
            }
            @media (max-width:450px){
                #pweForm form {
                    width: 100%;
                }
                #pweForm .form h2 {
                    margin-top: 36px;
                    font-size: 24px;
                }
                #pweForm .form-image-qr {
                    top: 20px;
                    width: 80px;
                }
                #pweForm .consent-container {
                    margin-top: 18px;
                }
                #pweForm .pwe-btn {
                    padding: 12px 16px !important;
                    font-size: 12px;
                }
                #pweForm input[type=submit] {
                    font-size: 12px;
                    padding: 0 !important;
                }
                #pweForm input:not([type=checkbox]) {
                    width: 100%;
                }
            }

        </style>';

        if (glob($_SERVER['DOCUMENT_ROOT'] . '/doc/header_mobile.webp', GLOB_BRACE)) {
            $output .= '
            <style>
                @media (max-width: 960px) {
                    .row-container:has(#pweRegistration) {
                        background-image: url(/doc/header_mobile.webp);
                    }
                }
            </style>';
        }
    }

} else if ($registration_type == 'PWERegistrationExhibitors') {

    $final_btn_color = ($domain_gr_exhib == 'gr3') ? self::$accent_color : $btn_color;
    $darker_btn_premium_color = self::adjustBrightness(self::$accent_color, -20);
    $final_darker_btn_color = ($domain_gr_exhib == 'gr3') ? $darker_btn_color_premium : $darker_btn_color;

    $output = '
    <style>
        .pwelement, .pwe-registration, .pwe-registration-column {
            height: 100%;
        }
        .gform_validation_errors {
            border:none !important;
        }
        #pweRegistration .pwe-registration-column {
            background-color: #e8e8e8;
            padding: 18px 36px;
            border: 2px solid #564949;
            border-radius: 36px;
        }
        #pweRegistration input {
            border: 2px solid #564949 !important;
            box-shadow: none !important;
            line-height: 1 !important;
        }
        #pweRegistration :is(label, label span, .gform_legacy_markup_wrapper .gfield_required, .gfield_description) {
            color: black !important;
        }
        #pweRegistration input:not([type=checkbox]) {
            border-radius: 11px !important;
        }
        #pweRegistration input[type=checkbox] {
            border-radius: 2px !important;
        }
        #pweRegistration input[type=submit] {
            background-color: '. $final_btn_color .' !important;
            border: 2px solid '. $final_btn_color .' !important;
            color: '. $btn_text_color .';
        }
        #pweRegistration input[type=submit]:hover {
            background-color: '. $final_darker_btn_color .' !important;
            border: 2px solid '. $final_darker_btn_color .' !important;
        }
        #pweRegistration .gform_fields {
            padding-left: 0 !important;
        }
        #pweRegistration .gform-field-label {
            display: inline !important;
        }
        #pweRegistration .gform-field-label .show-consent,
        #pweRegistration .gform-field-label .gfield_required_asterisk {
            display: inline !important;
            margin-left: 0;
            padding-left: 0;
        }
        #pweRegistration .gfield_required {
            display: none !important;
        }
        /*ROZWIJANE ZGODY*/
        #pweRegistration .gfield_consent_description {
            overflow: hidden !important;
            max-height: auto !important;
            border: none !important;
            display: none;
        }
        #pweRegistration .show-consent:hover{
            cursor: pointer;
        }
        #pweRegistration .ginput_container input {
            margin: 0 !important;
        }
        #pweRegistration .gfield_label {
            font-size: 14px !important;
        }
        #pweRegistration .gfield_consent_label {
            padding-left: 5px;
        }
        @media (max-width:650px) {
            #pweRegistration .gform_legacy_markup_wrapper .gform_footer {
                margin: 0 auto !important;
                padding: 0 !important;
                text-align: center;
            }
        }
        @media (max-width:400px) {
            #pweRegistration input[type="submit"] {
                font-size: 12px !important;
            }
        }
    </style>';
    if($domain_gr == "gr3"){
       $output = '<style>
            #pweRegistration input[type=submit] {
                background-color: red !important;
                border: 2px solid '. $btn_color_premium .' !important;
            }
            #pweRegistration input[type=submit]:hover {
                background-color: '. $darker_btn_premium_color.' !important;
                border: 2px solid '. $darker_btn_premium_color .' !important;
            }

       </style>';
    }
} else if ($registration_type == 'PWERegistrationPotentialExhibitors') {
    $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);
    $darker_btn_color = self::adjustBrightness($btn_color, -20);

    $output = '
    <style>
        @media (min-width: 959px) {
            .wpb_column:has(#pweRegistration.potential-exhibitors) {
                display: table-cell !important;
            }
        }
        .wpb_column #pweRegistration.potential-exhibitors {
            margin: 0 auto;
        }
        #pweRegistration.potential-exhibitors {
            max-width: 555px !important;
        }
        .gform_validation_errors {
            border:none !important;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-column {
            background-color: #e8e8e8;
            padding: 18px 36px;
            border: 2px solid #564949;
            border-radius: 36px;
        }
        #pweRegistration.potential-exhibitors input,
        #pweRegistration.potential-exhibitors select {
            border: 2px solid #564949 !important;
            box-shadow: none !important;
        }
        #pweRegistration.potential-exhibitors :is(label, label span, .gform_legacy_markup_wrapper .gfield_required, .gfield_description) {
            color: black !important;
        }
        #pweRegistration.potential-exhibitors input:not([type=radio]),
        #pweRegistration.potential-exhibitors select {
            border-radius: 8px !important;
        }
        #pweRegistration.potential-exhibitors input[type=radio] {
            border-radius: 4px !important;
            padding: 10px !important;
        }
        #pweRegistration.potential-exhibitors  input[type=radio]:checked:before {
            border-radius: 3px !important;
            width: 70%;
            height: 70%;
        }
        #pweRegistration.potential-exhibitors input[type=submit] {
            background-color: '. $btn_color .' !important;
            border: 2px solid '. $btn_color .' !important;
            color: white;
            text-transform: uppercase;
        }
        #pweRegistration.potential-exhibitors input[type=submit]:hover {
            background-color: '. $darker_btn_color.' !important;
            border: 2px solid '. $darker_btn_color .' !important;
        }
        #pweRegistration.potential-exhibitors .gform_wrapper input[type=text],
        #pweRegistration.potential-exhibitors .gform_wrapper select {
            padding: 10px 15px !important;
        }
        #pweRegistration.potential-exhibitors .gform_fields {
            padding-left: 0 !important;
        }
        #pweRegistration.potential-exhibitors .gform-field-label {
            display: inline !important;
        }
        #pweRegistration.potential-exhibitors .gform-field-label .show-consent,
        #pweRegistration.potential-exhibitors .gform-field-label .gfield_required_asterisk {
            display: inline !important;
            margin-left: 0;
            padding-left: 0;
        }
        #pweRegistration.potential-exhibitors .gfield_required {
            display: none !important;
        }
        /*ROZWIJANE ZGODY*/
        #pweRegistration.potential-exhibitors .gfield_consent_description {
            overflow: hidden !important;
            max-height: auto !important;
            border: none !important;
            display: none;
        }
        #pweRegistration.potential-exhibitors .show-consent:hover{
            cursor: pointer;
        }
        #pweRegistration.potential-exhibitors .ginput_container input {
            margin: 0 !important;
        }
        #pweRegistration.potential-exhibitors .gfield_label {
            font-size: 14px !important;
        }
        #pweRegistration.potential-exhibitors .gfield_consent_label {
            padding-left: 5px;
        }
        @media (max-width:650px) {
            #pweRegistration.potential-exhibitors .gform_legacy_markup_wrapper .gform_footer {
                margin: 0 auto !important;
                padding: 0 !important;
                text-align: center;
            }
        }
        @media (max-width:400px) {
            #pweRegistration.potential-exhibitors input[type="submit"] {
                font-size: 12px !important;
            }
        }


        #pweRegistration.potential-exhibitors .pwe-registration-fairs-select-container {
            position: relative;
            width: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-select {
            position: relative;
            width: 70%;
            cursor: pointer;
            border-radius: 4px;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-select-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 2px solid #564949 !important;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-search-input {
            width: 0;
            padding: 0;
            border: 1px solid #ccc;
            margin-top: 0;
            visibility: hidden;
            opacity: 0;
            height: 0;
            transition: .3s ease;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-select.open .pwe-registration-fairs-search-input {
            width: 100%;
            padding: 5px;
            margin-top: 10px;
            visibility: visible;
            opacity: 1;
            height: auto;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-options-container {
            visibility: hidden;
            opacity: 0;
            height: 0;
            transition: .3s ease;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            top: 106%;
            width: 0;
            background: #fff;
            border: 2px solid #564949;
            z-index: 10;
            border-radius: 8px;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-select.open .pwe-registration-fairs-options-container {
            visibility: visible;
            opacity: 1;
            height: auto;
            width: 100%;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-option {
            padding: 10px;
            cursor: pointer;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-option:hover {
            background-color: #f0f0f0;
        }
        #pweRegistration.potential-exhibitors .arrow-down {
            font-size: 16px;
            color: #333;
        }
        #pweRegistration.potential-exhibitors .pwe-registration-fairs-radio-buttons {
            display: flex;
            gap: 10px;
            width: 25%;
        }


        #pweRegistration.potential-exhibitors ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }
        #pweRegistration.potential-exhibitors ::-webkit-scrollbar-track {
            background: #f1f1f1; /* Kolor tła toru */
            border-radius: 8px;  /* Zaokrąglenie rogów toru */
        }
        #pweRegistration.potential-exhibitors ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 8px;
        }
        #pweRegistration.potential-exhibitors ::-webkit-scrollbar-thumb:hover {
            background: #555; /* Zmiana koloru rączki podczas najechania */
        }

        #pweRegistration.potential-exhibitors .gform_footer .gform_button {
            pointer-events: none !important;
            opacity: 0.5 !important;
            transition: .3s ease;
        }
        #pweRegistration.potential-exhibitors .gform_footer .gform_button.active {
            pointer-events: all !important;
            opacity: 1 !important;
        }
    </style>';
} else if ($registration_type == 'PWERegistrationAccreditations') {
    $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);
    $darker_btn_color = self::adjustBrightness($btn_color, -20);

    $output = '
    <style>
        #pweRegistration.accreditations {
            max-width: 750px !important;
        }
        #pweRegistration.accreditations .pwe-registration-title h3 {
            font-size: 36px;
        }
        #pweRegistration.accreditations .pwe-registration-form {
            margin-top: 36px;
        }
        #pweRegistration.accreditations .gform_validation_errors {
            border:none !important;
        }
        #pweRegistration.accreditations input,
        #pweRegistration.accreditations select {
            border: 1px solid #564949 !important;
            box-shadow: none !important;
        }
        #pweRegistration.accreditations :is(label, label span, .gform_legacy_markup_wrapper .gfield_required, .gfield_description) {
            color: black !important;
        }
        #pweRegistration.accreditations select {
            border-radius: 4px !important;
        }
        #pweRegistration.accreditations .gfield_checkbox {
            padding: 0;
        }
        #pweRegistration.accreditations input[type=submit] {
            background-color: '. $btn_color .' !important;
            border: 2px solid '. $btn_color .' !important;
            color: white;
            text-transform: uppercase;
        }
        #pweRegistration.accreditations input[type=submit]:hover {
            background-color: '. $darker_btn_color.' !important;
            border: 2px solid '. $darker_btn_color .' !important;
        }
        #pweRegistration.accreditations .gform_wrapper input[type=text],
        #pweRegistration.accreditations .gform_wrapper select {
            padding: 10px 15px !important;
        }
        #pweRegistration.accreditations .gform_fields {
            padding-left: 0 !important;
        }
        #pweRegistration.accreditations .gform-field-label {
            display: inline !important;
        }
        #pweRegistration.accreditations .gform-field-label .show-consent,
        #pweRegistration.accreditations .gform-field-label .gfield_required_asterisk {
            display: inline !important;
            margin-left: 0;
            padding-left: 0;
        }
        #pweRegistration.accreditations .gfield_required {
            display: none !important;
        }
        /*ROZWIJANE ZGODY*/
        #pweRegistration.accreditations .gfield_consent_description {
            overflow: hidden !important;
            max-height: auto !important;
            border: none !important;
            display: none;
        }
        #pweRegistration.accreditations .show-consent:hover{
            cursor: pointer;
        }
        #pweRegistration.accreditations .ginput_container input {
            margin: 0 !important;
        }
        #pweRegistration.accreditations .gfield_label {
            font-size: 14px !important;
        }
        #pweRegistration.accreditations .gfield_consent_label {
            padding-left: 5px;
        }
        @media (max-width:650px) {
            #pweRegistration.accreditations .gform_legacy_markup_wrapper .gform_footer {
                margin: 0 auto !important;
                padding: 0 !important;
                text-align: center;
            }
        }
        @media (max-width:400px) {
            #pweRegistration.accreditations input[type="submit"] {
                font-size: 12px !important;
            }
        }


        #pweRegistration.accreditations .pwe-registration-fairs-select-container {
            position: relative;
            width: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-select {
            position: relative;
            width: 70%;
            cursor: pointer;
            border-radius: 4px;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-select-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 1px solid #564949 !important;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-search-input {
            width: 0;
            padding: 0;
            border: 1px solid #ccc;
            margin-top: 0;
            visibility: hidden;
            opacity: 0;
            height: 0;
            transition: .3s ease;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-select.open .pwe-registration-fairs-search-input {
            width: 100%;
            padding: 5px;
            margin-top: 10px;
            visibility: visible;
            opacity: 1;
            height: auto;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-options-container {
            visibility: hidden;
            opacity: 0;
            height: 0;
            transition: .3s ease;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            top: 106%;
            width: 0;
            background: #fff;
            border: 2px solid #564949;
            z-index: 10;
            border-radius: 8px;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-select.open .pwe-registration-fairs-options-container {
            visibility: visible;
            opacity: 1;
            height: auto;
            width: 100%;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-option {
            padding: 10px;
            cursor: pointer;
        }
        #pweRegistration.accreditations .pwe-registration-fairs-option:hover {
            background-color: #f0f0f0;
        }
        #pweRegistration.accreditations .arrow-down {
            font-size: 16px;
            color: #333;
        }

        #pweRegistration.accreditations ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }
        #pweRegistration.accreditations ::-webkit-scrollbar-track {
            background: #f1f1f1; /* Kolor tła toru */
            border-radius: 8px;  /* Zaokrąglenie rogów toru */
        }
        #pweRegistration.accreditations ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 8px;
        }
        #pweRegistration.accreditations ::-webkit-scrollbar-thumb:hover {
            background: #555; /* Zmiana koloru rączki podczas najechania */
        }

        #pweRegistration.accreditations .gform_footer .gform_button {
            pointer-events: none !important;
            opacity: 0.5 !important;
            transition: .3s ease;
        }
        #pweRegistration.accreditations .gform_footer .gform_button.active {
            pointer-events: all !important;
            opacity: 1 !important;
        }
    </style>';
} else if ($register_show_ticket === "true" && $domain_gr == "gr3" && (strpos($source_utm, 'utm_source=byli') === false || strpos($source_utm, 'utm_source=premium') === false || strpos($source_utm, 'utm_source=platyna') === false )) {
    $background_color = self::$accent_color;

    $output = '
    <style>
        .wpb_column:has(.exhibitors-catalog) {
            display:none !important;
        }
        .row-container:has(#pweRegistrationTicket) {
            background-image: url(/doc/background.webp);
            background-repea: no-repeat;
            background-position: center;
            background-size: cover;
        }
        #pweRegistrationTicket {
            background: rgba(247, 247, 247, 1);
            border-radius:18px;
        }
        #pweRegistrationTicket, #pweRegistrationTicket .ticket-card__price {
            flex-direction: column;
            align-items: center;
        }

        #pweRegistrationTicket, #pweRegistrationTicket .registration-ticket-container, #pweRegistrationTicket .ticket-card__price, #pweRegistrationTicket .ticket-card__details_button, #pweRegistrationTicket .gform_footer {
            display:flex;
            justify-content:center;
        }

        #pweRegistrationTicket .registration-ticket-container, #pweRegistrationTicket .ticket-card {
            max-width:90%;
            margin:0 auto 25px;
            gap:30px;
        }
        #pweRegistrationTicket .registration-ticket__option {
            background: rgba(255, 255, 255, 1);
            border-radius: 18px;
        }
        #pweRegistrationTicket .registration-ticket__title {
            font-size:36px;
            margin-bottom: 36px;
        }
        #pweRegistrationTicket .ticket-card__label {
            background: '. $background_color .';
            color: rgba(255, 255, 255, 1);
            border-radius: 18px 18px 0 0;
            font-size:18px;
            padding: 14px 0px;
        }
        #pweRegistrationTicket .registration-ticket__title, #pweRegistrationTicket .ticket-card__label, #pweRegistrationTicket .ticket-card__name, #pweRegistrationTicket .ticket-card__price-value, #pweRegistrationTicket .ticket-card__details-title {
            font-weight: 700;
            text-align: center;
        }
        #pweRegistrationTicket .ticket-card__name {
            font-size: 32px;
            padding: 20px 0 10px;
            border-bottom: 4px solid black;
        }
        #pweRegistrationTicket .registration-ticket__option--standard {
            flex: 0.6;
            -webkit-box-shadow: 0px 0px 26px 0px rgba(24, 13, 35, 1);
            -moz-box-shadow: 0px 0px 26px 0px rgba(24, 13, 35, 1);
            box-shadow: 0px 0px 26px 0px rgba(24, 13, 35, 1);
        }
        #pweRegistrationTicket .registration-ticket__option--business {
            flex: 0.4;
            margin-top: 50px;
            position: relative;
        }
        #pweRegistrationTicket .registration-ticket__option--standard .ticket-card__name {
            border-bottom: 4px solid '. $background_color .';
        }
        #pweRegistrationTicket  .registration-ticket__option--business img {
            position: absolute;
            top: -62px;
            right: -35px;
            width: 90px;
            height: 90px;
        }
        #pweRegistrationTicket .ticket-card__price-value {
            font-size: 32px;
        }
        #pweRegistrationTicket .ticket-card__note {
            margin-top:0px;
        }
        #pweRegistrationTicket .registration-ticket__option--business .ticket-card__price .ticket-card__note {
            color: '. $background_color .';
            font-weight: 700;
            font-size: 18px !important;
            max-width: 90%;
            text-align: center;
            line-height: 1.2;
        }
        #pweRegistrationTicket .registration-ticket__option--business  .exhibitor-catalog {
            background-color: '. $background_color .';
            border-radius: 18px;
            color: white !important;
            padding: 5px 10px;
            font-weight: 500;
            font-size: 12px;
            margin-top: 7px;
            transition: .3s ease;
        }
        #pweRegistrationTicket .registration-ticket__option--business  .exhibitor-catalog:hover {
            opacity: .9;
        }
        #pweRegistrationTicket .ticket-card__details-title {
            color:black;
            font-size:18px;
            text-align: left;
        }
        #pweRegistrationTicket .ticket-card__details ul {
            list-style: disc;
            padding-left: 18px;
            margin: 15px 0 25px;
        }
        #pweRegistrationTicket .ticket-card__details ul::marker  {
            margin-right:2px !important;
        }
        #pweRegistrationTicket input {
            border-radius:18px;
        }
        #pweRegistrationTicket input[type="checkbox"] {
            border: 2px solid black;
            border-radius:0px;
        }
        #pweRegistrationTicket .ticket-card__cta, #pweRegistrationTicket input[type="submit"], .popup_katalog {
            background-color: '. $btn_color .' !important;
            border: 2px solid '. $btn_color .' !important;
            border-radius: 35px !important;
            font-size: 1em;
            color: white;
            align-self: center;
            transform: scale(1) !important;
            padding: 13px 31px;
            font-weight: 600 !important;
            text-transform: none;
            min-width: 250px;
            text-align: center;
            margin:0 !important;
        }
        #pweRegistrationTicket  .registration-ticket__option--business .ticket-card__cta, .popup_katalog {
            background-color: black !important;
            border: 2px solid black !important;
        }
        #pweRegistrationTicket .ticket-card__cta:hover, #pweRegistrationTicket input[type="submit"]:hover {
            background-color: '. $darker_btn_color .'!important;
            border: 2px solid '. $darker_btn_color .'!important;
            color: white;
        }
        #pweRegistrationTicket .gfield .iti.iti--allow-dropdown input {
            border: 1px solid #eaeaea;
        }
        #pweRegistrationTicket .gfield .iti.iti--allow-dropdown input::focus {
            border: 1px solid '. $btn_color .';
        }

        @media(max-width:1100px){
            #pweRegistrationTicket .registration-ticket__option--standard, #pweRegistrationTicket .registration-ticket__option--business {
                flex:1;
            }
        }
        @media(max-width:960px){
            .row-container:has(.gform_wrapper, .pwe-container-grupy) .wpb_column, .row-container:has(#pweRegistrationTicket) {
                max-width: 100% !important;
            }
        }
        @media(max-width:800px){
            #pweRegistrationTicket .ticket-card__name {
                font-size: 25px;
            }
        }
        @media(max-width:650px){
            #pweRegistrationTicket .registration-ticket-container {
                flex-wrap:wrap;
            }
            #pweRegistrationTicket .registration-ticket__option {
                min-width: 100%;
            }
        }
    </style>'
    ;
    if(empty($ticket_link)){
        $output .= '
        <style>
            .popup {
                display: none; /* domyślnie ukryty */
                position: fixed;
                justify-content: center;
                align-items: center;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                z-index: 1000;
            }

            .popup__content {
                background: #fff;
                padding: 36px;
                min-width: 360px;
                position: relative;
                border-radius: 8px;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 200px;
                max-width: 800px;
                align-items: stretch;
                flex-direction: column;
            }
            .popup__content_text_container {
                display:flex;
            }
            .popup__close {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 32px;
                cursor: pointer;
                transform: rotate(45deg);
                display: inline-block;
                user-select: none;
            }
            .popup__content_button {
                flex: .1;
                display: flex;
                justify-content: right;
                align-items: flex-start;
            }
            .popup__content_button div {
               color:white;
                border-radius: 50%;
                  width: 50px;
                height: 50px;
                background: black;
                color: white;
                font-size: 50px;
                line-height: 50px;
                text-align: center;
                cursor: pointer;
                transform: rotate(45deg); /* + staje się X */
                user-select: none;
                transition: background 0.3s;
            }
            .popup__content_button div:hover {
                background-color: '. $darker_btn_color .'!important;
            }
            .popup__content_text {
                flex:.9;
                justify-content: start;
                display: flex;
                flex-direction: column;
                align-items: anchor-center;
                min-height: auto;
            }
            .popup__content_button_container {
                display: flex;
                justify-content: space-around;
                flex-wrap: wrap;
            }
            .popup__content_button_container a {
                color: white !important;
                display: inline-block  !important;
                min-width: 240px !important;
                margin-top: 16px !important;
                background-color: '. $darker_btn_color .'!important;
                border:2px solid '. $darker_btn_color .' !important;
            }
            .popup__content_button_container a:hover {
                background-color: black !important;
                border:2px solid black !important;
            }
            .popup__content_button_container  .popup_rej {
                background-color: black !important;
                border:2px solid black !important;
            }
            .popup__content_button_container  .popup_rej:hover {
                background-color: '. $darker_btn_color .'!important;
                border:2px solid '. $darker_btn_color .' !important;
            }
            .popup__content_text p {
                margin-top:0;
                font-weight:700;
                font-size:16px;
            }
            .popup__content_text .text {
                font-size:14px;
                font-weight:600;
                margin-top:12px;
            }

            @media(min-width:1200px){
                .popup__content {
                    min-width: 420px;
                }
            }
        </style>';
    };
    if (glob($_SERVER['DOCUMENT_ROOT'] . '/doc/header_mobile.webp', GLOB_BRACE)) {
        $output .= '
        <style>
            @media (max-width: 960px) {
                .row-container:has(#pweRegistrationTicket) {
                    background-image: url(/doc/header_mobile.webp);
                }
            }
        </style>';
    }
}
if(strpos($source_utm, 'utm_source=platyna') !== false && $domain_gr=="gr2"){

    $output .= '
    <style>
        .limit-width:has(.platyna) {
            max-width:none !important;
            padding: 0px !important;
        }
        .wpb_column:has(.exhibitors-catalog) {
            display:none !important;
        }
        .row-container:has(#pweRegistration) {
            // background: linear-gradient(0deg, rgba(168, 168, 168, 0.58) 13%, rgba(150, 150, 150, 0.94) 23%, rgba(66, 66, 66, 0.02) 28%, rgba(170, 171, 175, 0.84) 100%);
            background-image: url(/wp-content/plugins/pwe-media/media/platyna/platinum_background.webp);
            background-size: contain;
            background-repeat: no-repeat;
        }
        .pwe-registration-image-container img {
            max-width:80%;
        }
        #pweForm {
            --p: 70px;
            margin-left: auto;
            max-width: 60%;
            border-radius: 40px 0 0 40px;
            overflow: hidden;
            border: 1px solid #838B8F;
            background: #838B8F;
            background: linear-gradient(90deg, rgba(131, 139, 143, 1) 0%, rgba(224, 224, 224, 1) 50%, rgba(252, 252, 252, 1) 100%);
        }
        .pweform_container {
            display: flex;
            max-width: 80%;
            margin-left: auto;
            padding: 18px 0;
            margin-right: 15px;
        }
        .form, .benefits {
            flex:1;
        }
        .pwe-registration-column {
            display: flex;
            justify-content: right;
            align-items: center;
            min-height: 650px;
        }
        #pweRegistration .form-title {
            color:#636363;
            font-weight:800;
        }
        .form h3, .form label {
            color:#737374;
            font-weight:600 !important;
        }
        #pweRegistration form ul {
            padding:0 !important;
        }
        #pweRegistration form ul input, input::placeholder {
            border-radius:18px;
            color: #8a8a8a !important;
        }
        #pweRegistration .ginput_container_consent label {
            line-height: 1.4;
            font-size: 12px;
        }
        #pweRegistration form input[type="submit"] {
            display: inline-block;
            width: 100%;
            border-radius: 18px !important;
            color:white !important;
            background-color:#636363;
        }
        #pweRegistration .benefits_icon img {
            width: 70px;
        }
        #pweRegistration .benefits {
            gap: 10px;
            text-align: center;
            justify-content: space-around;
        }
        #pweRegistration .benefits, #pweRegistration .benefits .benefits_icon {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #pweRegistration .benefits .benefits_icon {
            max-width: 250px;
            justify-content: center;
        }
        #pweRegistration .benefits h2 {
            color: #636363;
            font-size: 18px;
            font-weight: 700 !important;
            margin-top: 80px;
        }
        #pweRegistration .benefits p {
            color: #646464;
            font-weight: 500;
            line-height: 1.3;
            margin-top: 0;
            font-size: 14px;
        }
        @media(max-width:1500px){
            .pweform_container {
                padding: 0 0 5px 0;
            }
            .pwe-registration-column {
                min-height: 580px;
            }
            .form h3, #pweRegistration .form-title {
                margin-top: 14px;
            }
            #pweRegistration .benefits h2 {
                margin-top: 60px;
            }
        }
        @media(max-width:1200px){
            .row-container:has(#pweRegistration) {
                background-size: cover;
            }
            .pweform_container {
                max-width: 90%;
            }
        }
        @media(max-width:960px){
            .row-container:has(.gform_wrapper, .pwe-container-grupy) .wpb_column, .row-container:has(.pwe-route) .wpb_column {
                max-width: 100%;
            }
            #pweForm {
                max-width: 100%;
                border-radius: 40px;
            }
        }
        @media(max-width:650px){
            .row-container:has(#pweRegistration) {
                background: #838B8F;
                background: linear-gradient(90deg, rgb(161 161 161) 0%, rgb(255 255 255) 50%, rgb(184 180 180) 100%);
            }
        }
        @media(max-width:480px){
            .pweform_container {
                flex-direction: column;
            }
            #pweRegistration .benefits {
                flex-wrap: wrap;
                flex-direction: row;
            }
            #pweRegistration .benefits .benefits_icon {
                flex: 1;
            }
            #pweRegistration .benefits h2 {
                width: 100%;
                margin-top: 20px;
            }
        }
    </style>';
}