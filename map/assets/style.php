<?php

if ($map_type === 'PWEMapDynamic') {

    $lighter_accent_color = self::adjustBrightness(self::$accent_color, +70);

    if ($map_dynamic_3d == true && $map_dynamic_preset == 'preset_1') {
        $output = '
        <style>
            .pwe-map__wrapper {
                position: relative;
                display: flex;
                justify-content: space-between;
                margin-top: 18px;
            }
            .pwe-map__staticts {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                max-width: 260px;
                gap: 12px;
                z-index: 1;
                background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.5) 50%, rgba(255, 255, 255, 0) 100%);
            }
            .pwe-map__title {
                margin-top: 0;
                text-transform: uppercase;
                font-size: 34px !important;
                max-width: 550px;
                text-shadow: 0px 0px 2px white;
            }
            .pwe-map__rounded-stat {
                display: flex;
                justify-content: space-around;
                gap: 12px;
            }
            .pwe-map__rounded-element {
                width: 120px;
                min-height: 120px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                border:5px solid;
                border-radius:100%;
                text-align: center;
            }
            .pwe-map__rounded-element p {
                margin-top: 0px;
                line-height: 1;
            }
            .pwe-map__stats-container.mobile {
                display: none;
            }
            .pwe-map__stats-element-title {
                font-weight: 700;
                font-size: 26px;
                text-shadow: 0px 0px 2px white;
            }
            .pwe-map__stats-element-desc {
                font-size: 26px;
                margin-top: 0px;
                line-height: 1;
                text-shadow: 0px 0px 2px white;
            }
            .pwe-map__logotypes {
                align-items: flex-end;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                max-width: 260px;
                z-index: 1;
                background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.5) 50%, rgba(255, 255, 255, 1) 100%);
            }
            .pwe-map__logo-container {
                max-width: 260px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding-bottom: 18px;
            }
            .pwe-map__logotypes-data {
                margin-top: 12px;
                font-weight:750;
                font-size: 20px;
                text-align: center !important;
            }
            .pwe-map__container-3d {
                position: absolute;
                width: 100%;
                max-width: 600px;
                display: flex;
                align-items: center;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .pwe-map__container-3d canvas {
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 1 / 1;
            }
            @media (max-width: 960px) {
                .pwe-map__title {
                    font-size: 24px !important;
                }
                .pwe-map__staticts {
                    background: none;
                }
            }
            @media (max-width: 650px) {
                .pwe-map__wrapper {
                    flex-direction: column;
                }
                .pwe-map__logotypes {
                    display: none;
                }
                .pwe-map__heading {
                    flex-direction: column;
                }
                .pwe-map__logo-container{
                margin: 0 auto;
                }
                .pwe-map__staticts {
                    max-width: 100%;
                    padding: 72px 0;
                }
                .pwe-map__stats-container.desktop {
                    display: none;
                }
                .pwe-map__stats-container.mobile {
                    display: flex;
                    flex-wrap: wrap;
                }
                .pwe-map__rounded-stat .pwe-map__rounded-element {
                    min-height: auto;
                    border-radius: 14px;
                    border: 2px solid black;
                    padding: 10px;
                }
                .pwe-map__stats-element-55 {
                    width: 55%;
                }
                .pwe-map__stats-element-45 {
                    width: 45%;
                }
                .pwe-map__stats-element-title,
                .pwe-map__stats-element-desc {
                    font-size: 22px;
                }
                // .pwe-map__staticts {
                //     background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.5) 50%, rgba(255, 255, 255, 0) 100%);
                // }

                .pwe-map__container-3d {
                    position: relative;
                    max-width: auto;
                    top: auto;
                    left: auto;
                    transform: none;
                }
            }
            @media (max-width: 600px) {
                .pwe-map__staticts {
                    padding: 0;
                    gap: 18px;
                }
            }
            @media (max-width: 450px) {
                .pwe-map__staticts {
                    padding: 0;
                }
                .pwe-map__stats-element-title,
                .pwe-map__stats-element-desc {
                    font-size: 18px;
                }
            }

            @media (min-width: 651px) {
                .pwe-map__stats-diagram.mobile {
                    display: none;
                }
                .pwe-map__stats-diagram.desktop {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                    gap: 18px;
                }
                .pwe-map__stats-diagram-years-container {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    gap: 24px;
                }
                .pwe-map__stats-diagram-year {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .pwe-map__stats-diagram-year-box {
                    width: 20px;
                    aspect-ratio: 1 / 1;
                    background: '. self::$accent_color .';
                }
                .pwe-map__stats-diagram-year:first-of-type .pwe-map__stats-diagram-year-box {
                    background: '. $lighter_accent_color .';
                }
                .pwe-map__stats-diagram-bars-container {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    align-items: flex-end;
                }
                .pwe-map__stats-diagram-bars {
                    display: flex;
                    flex-direction: column-reverse;
                    align-items: center;
                }
                .pwe-map__stats-diagram-bars-wrapper {
                    display: flex;
                    flex-direction: column;
                    gap: 18px;
                    justify-content: center;
                }
                .pwe-map__stats-diagram-bar {
                    display: flex;
                    align-items: flex-end;
                    position: relative;
                    justify-content: flex-end;
                    gap: 8px;
                }
                .pwe-map__stats-diagram-bar.visitors {
                    width: 160px;
                }
                .pwe-map__stats-diagram-bar.visitors .pwe-map__stats-diagram-bar-number {
                    position: absolute;
                    left: -50px;
                }
                .pwe-map__stats-diagram-bar.exhibitors {
                    width: 210px;
                }
                .pwe-map__stats-diagram-bar.exhibitors .pwe-map__stats-diagram-bar-number {
                    position: absolute;
                    left: -30px;
                }
                .pwe-map__stats-diagram-bar.area {
                    width: 250px;
                }
                .pwe-map__stats-diagram-bar.area .pwe-map__stats-diagram-bar-number {
                    position: absolute;
                    left: -60px;
                }
                .pwe-map__stats-diagram-bar-item {
                    background: '. self::$accent_color .';
                    width: 0;
                    height: 25px;
                    border-radius: 12px 0 0 12px;
                    position: relative;
                    display: flex;
                    align-items: flex-end;
                    justify-content: center;
                }
                .pwe-map__stats-diagram-bar:first-of-type .pwe-map__stats-diagram-bar-item {
                    background: '. $lighter_accent_color .';
                }
                .pwe-map__stats-diagram-bar-number {
                    display: flex;
                    text-align: center;
                    font-size: 16px;
                    font-weight: 600;
                    align-self: center;
                }
                .pwe-map__stats-diagram-bar-number sup {
                    top: 0;
                }
                .pwe-map__stats-diagram-bars-label {
                    margin-top: 8px;
                    text-align: center;
                    font-size: 16px;
                    font-weight: 600;
                    align-self: end;
                }
                .pwe-map__stats-section span, .pwe-map__stats-section p {
                    text-align: center;
                    font-size: 16px;
                    font-weight: 600;
                }
                .pwe-map__stats-diagram-countries-container {
                    width: 18%;
                    min-width: 150px;
                    display: flex;
                    justify-content: center;
                    align-items: flex-start;
                }
                .pwe-map__stats-diagram-countries {
                    width: 140px;
                    aspect-ratio: 1 / 1;
                    border-radius: 50%;
                    border: 2px solid;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                }
                .pwe-map__stats-diagram-countries *{
                    margin: 0;
                }
                .pwe-map__stats-diagram-countries h2 {
                    min-width: unset;
                }
                .pwe-map__stats-diagram-countries h2 span {
                    color: '. self::$accent_color .';
                    font-size: 40px;
                    font-weight: 800;
                }

            }



        // @media (max-width: 650px) {
        //     .pwe-map__stats-diagram.mobile {
        //         display: flex;
        //     }
        //     .pwe-map__stats-diagram.mobile {

        //         display: flex;
        //         flex-direction: column;
        //         align-items: center;
        //         gap: 36px;


        //         max-width: 650px;
        //         margin-top: 40px;
        //     }
        //     .pwe-map__stats-diagram-years-container {
        //         width: 100%;
        //         display: flex;
        //         justify-content: center;
        //         gap: 24px;
        //     }
        //     .pwe-map__stats-diagram-year {
        //         display: flex;
        //         align-items: center;
        //         gap: 8px;
        //     }
        //     .pwe-map__stats-diagram-year-box {
        //         width: 20px;
        //         aspect-ratio: 1 / 1;
        //         background: '. self::$accent_color .';
        //     }
        //     .pwe-map__stats-diagram-year:first-of-type .pwe-map__stats-diagram-year-box {
        //         background: '. $lighter_accent_color .';
        //     }
        //     .pwe-map__stats-diagram-bars-container {
        //         width: 100%;
        //         height: 100%;
        //         display: flex;



        //         justify-content: space-evenly;



        //         align-items: flex-end;
        //     }
        //     .pwe-map__stats-diagram-bars {
        //         display: flex;
        //         flex-direction: column;
        //         align-items: center;
        //     }
        //     .pwe-map__stats-diagram-bars-wrapper {
        //         display: flex;



        //         gap: 36px;



        //         justify-content: center;

        //     }
        //     .pwe-map__stats-diagram-bar {
        //         display: flex;
        //         flex-direction: column;
        //         align-items: center;
        //         position: relative;
        //         justify-content: flex-end;
        //         height: 150px;
        //         width: auto;
        //     }
        //     .pwe-map__stats-diagram-bar-item {
        //         background: '. self::$accent_color .';



        //         border-radius: 12px 12px 0 0;
        //         width: 25px;
        //         height: 0;




        //         position: relative;
        //         display: flex;
        //         align-items: flex-end;
        //         justify-content: center;
        //     }
        //     .pwe-map__stats-diagram-bar:first-of-type .pwe-map__stats-diagram-bar-item {
        //         background: '. $lighter_accent_color .';
        //     }
        //     .pwe-map__stats-diagram-bar-number {
        //         position: absolute;
        //         bottom: 100%;
        //         transform: translateY(0);
        //         text-align: center;
        //         font-size: 16px;
        //         font-weight: 600;
        //     }
        //     .pwe-map__stats-diagram-bars-label {
        //         margin-top: 8px;
        //         text-align: center;
        //         font-size: 16px;
        //         font-weight: 600;
        //     }
        //     .pwe-map__stats-section span, .pwe-map__stats-section p {
        //         text-align: center;
        //         font-size: 16px;
        //         font-weight: 600;
        //     }
        //     .pwe-map__stats-diagram-countries-container {
        //         width: 18%;
        //         min-width: 150px;
        //         display: flex;
        //         justify-content: center;
        //         align-items: flex-start;
        //     }
        //     .pwe-map__stats-diagram-countries {
        //         width: 140px;
        //         aspect-ratio: 1 / 1;
        //         border-radius: 50%;
        //         border: 2px solid;
        //         display: flex;
        //         flex-direction: column;
        //         align-items: center;
        //         justify-content: center;
        //         gap: 6px;
        //     }
        //     .pwe-map__stats-diagram-countries *{
        //         margin: 0;
        //     }
        //     .pwe-map__stats-diagram-countries h2 {
        //         min-width: unset;
        //     }
        //     .pwe-map__stats-diagram-countries h2 span {
        //         color: '. self::$accent_color .';
        //         font-size: 40px;
        //         font-weight: 800;
        //     }

        // }


        </style>';

    } else if ($map_dynamic_3d !== true && $map_dynamic_preset == 'preset_1') {
        $output = '
        <style>
            .pwe-mapa-staticts {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .pwe-mapa-staticts h2 {
                margin-top: 0;
                text-transform: uppercase;
                font-size: 40px;
                max-width: 550px;
            }
            .pwe-container-mapa {
                display:flex;
                justify-content: space-between;
                min-height:50vh;
            }
            .pwe-mapa-rounded-stat {
                display: flex;
                gap: 15px;
            }
            .pwe-mapa-rounded-element {
                width: 120px;
                min-height: 120px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                border:5px solid;
                border-radius:100%;
                text-align: center;
            }
            .pwe-mapa-rounded-element p {
                margin-top:0px;
                line-height: 1;
            }
            .pwe-mapa-stats-element-title {
                font-weight: 700;
                font-size: 28px;
            }
            .pwe-mapa-stats-element-desc {
                font-size: 25px;
            }
            .pwe-mapa-stats-element-title, .pwe-mapa-stats-element-desc {
                line-height: 1;
            }
            .pwe-mapa-stats-element {
                margin:20px 0;
            }
            .pwe-mapa-stats-element p {
                margin-top:0px !important;
            }
            .pwe-container-mapa {
                background-image:url(/doc/mapka.webp);
                background-position: center;
                background-size: contain;
                background-repeat: no-repeat;
                display:flex;
                justify-content: space-between;
            }
            .pwe-mapa-stats-element-mobile {
                display:none;
            }';

            if (is_array($map_more_logotypes)){
                $output .=
                '.pwe-mapa-logo-container img {
                    max-width: 200px;
                    margin: 10px;
                }';
            } else {
                $output .=
                '.pwe-mapa-logo-container img {
                    max-width: 250px;
                }';
            }

            $output .= '
            .pwe-mapa-right {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: flex-end;
            }
            .pwe-mapa-right-data {
                margin-top:0;
                font-weight:750;
                font-size: 20px;
            }
            .pwe-mapa-rounded-element-country-right {
                display:none;
            }
            @media (min-width: 1200px){
                .pwe-container-mapa {
                    height: 670px;
                }
            }
            @media(max-width:1100px){
                .pwe-mapa-rounded-element-country-right {
                    display:flex;
                }
                .pwe-mapa-logo-container img {
                    max-width: 200px;
                }
                .pwe-mapa-stats-element-title, .pwe-mapa-stats-element-desc {
                    font-size: 20px;
                }
                .pwe-mapa-staticts h2 {
                    font-size: 25px;
                    max-width: 550px;
                }
                .pwe-mapa-rounded-element {
                    width: 120px;
                    min-height: 120px;
                    border: 3px solid;
                    margin-left: 15px;
                }
                .pwe-mapa-rounded-element-country {
                    display:none;
                }
            }
            @media (max-width: 599px){
                .pwe-mapa-staticts {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                }
                .pwe-mapa-right {
                    display:none;
                }
                .pwe-mapa-stats-element-mobile, .pwe-mapa-rounded-element-country  {
                    display:flex;
                }
                .pwe-container-mapa {
                    background-image:none;
                    justify-content: center;
                }
                .pwe-mapa-rounded-stat {
                    margin: 20px 0 15px;
                }
                .pwe-mapa-stats-container {
                    width:100%;
                }
                .pwe-mapa-staticts .mobile-estymacje-image {
                    margin-top:0 !important;
                    height:230px;
                    background-image:url(/doc/mapka_mobile.webp);
                    background-position: center;
                    background-size: contain;
                    background-repeat: no-repeat;
                }
                .pwe-mapa-stats-element {
                    margin: 10px 0 0;
                }
            }

            .pwe-map__container-3d {
                position: relative;
                width: 100%;
            }
            .pwe-map__container-3d canvas {
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 1 / 1;
            }
        </style>';

    } else if ($map_dynamic_preset == 'preset_2') {
        $lighter_accent_color = self::adjustBrightness(self::$accent_color, +70);

        $output = '
        <style>
            .pwe-map .countup {
                text-shadow: 1px 1px 1px white;
            }
            .pwe-map__wrapper {
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 36px;
            }
            .pwe-map__title {
                margin-top: 0;
                text-transform: uppercase;
                font-size: 26px !important;
                text-shadow: 0px 0px 2px white;
            }
            .pwe-map__subtitle {
                margin-top: 0;
                color: '. self::$accent_color .';
            }
            .pwe-map__stats-section {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                position: relative;
                z-index: 1;
                padding: 18px 0;
            }
            .pwe-map__stats-section:before {
                content: "";
                position: absolute;
                background: linear-gradient(90deg, #eaeaea26 50%, transparent 100%);
                top: 0;
                bottom: 0;
                left: -100%;
                right: -100%;
                z-index: -1;
                overflow: visible;
            }
            .pwe-map__stats-diagram {
                width: 62%;
                min-height: 300px;
                display: flex;
                justify-content: center;
                gap: 36px;
                flex-wrap: wrap;
            }
            .pwe-map__stats-diagram-container {
                width: 64%;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 36px;
            }
            .pwe-map__stats-diagram-years-container {
                width: 100%;
                display: flex;
                justify-content: center;
                gap: 24px;
            }
            .pwe-map__stats-diagram-year {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .pwe-map__stats-diagram-year-box {
                width: 20px;
                aspect-ratio: 1 / 1;
                background: '. self::$accent_color .';
            }
            .pwe-map__stats-diagram-year:first-of-type .pwe-map__stats-diagram-year-box {
                background: '. $lighter_accent_color .';
            }
            .pwe-map__stats-diagram-bars-container {
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
            }
            .pwe-map__stats-diagram-bars {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .pwe-map__stats-diagram-bars-wrapper {
                display: flex;
                gap: 18px;
                justify-content: center;
            }
            .pwe-map__stats-diagram-bar {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                justify-content: flex-end;
                height: 150px;
            }
            .pwe-map__stats-diagram-bar-item {
                background: '. self::$accent_color .';
                width: 42px;
                position: relative;
                display: flex;
                align-items: flex-end;
                justify-content: center;
            }
            .pwe-map__stats-diagram-bar:first-of-type .pwe-map__stats-diagram-bar-item {
                background: '. $lighter_accent_color .';
            }
            .pwe-map__stats-diagram-bar-number {
                position: absolute;
                bottom: 100%;
                transform: translateY(0);
                text-align: center;
                font-size: 16px;
                font-weight: 600;
            }
            .pwe-map__stats-diagram-bar-number sup {
                top: 0;
            }
            .pwe-map__stats-diagram-bars-label {
                margin-top: 8px;
                text-align: center;
                font-size: 16px;
                font-weight: 600;
            }
            .pwe-map__stats-section span, .pwe-map__stats-section p {
                text-align: center;
                font-size: 16px;
                font-weight: 600;
            }
            .pwe-map__stats-diagram-countries-container {
                width: 18%;
                min-width: 150px;
                display: flex;
                justify-content: center;
                align-items: flex-start;
            }
            .pwe-map__stats-diagram-countries {
                width: 140px;
                aspect-ratio: 1 / 1;
                border-radius: 50%;
                border: 2px solid;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }
            .pwe-map__stats-diagram-countries *{
                margin: 0;
            }
            .pwe-map__stats-diagram-countries h2 {
                min-width: unset;
            }
            .pwe-map__stats-diagram-countries h2 span {
                color: '. self::$accent_color .';
                font-size: 40px;
                font-weight: 800;
            }
            .pwe-map__stats-number-container {
                width: 42%;
                display: flex;
                flex-direction: column;
                gap: 36px;
            }
            .pwe-map__stats-number-container .pwe-map__stats-number-box {
                display: flex;
                align-items: center;
                gap: 24px;
            }
            .pwe-map__stats-number-box-text {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }
            .pwe-map__stats-number-container .pwe-map__stats-number-box h2 {
                min-width: 200px;
                text-align: right;
            }
            .pwe-map__stats-number-container .pwe-map__stats-number-box h2 span {
                font-size: 40px;
                font-weight: 800;
            }
            .pwe-map__stats-number-box-text span {
                color: '. self::$accent_color .';
                font-weight: 800;
            }
            .pwe-map__stats-number-box-text p {
                text-transform: uppercase;
                font-size: 12px;
                text-align: left;
            }
            .pwe-map__stats-number-box *{
                margin: 0 !important;
            }
            @media(max-width:960px){
                .pwe-map__stats-section {
                    align-items: center;
                    gap: 36px;
                    flex-wrap: wrap;
                }
                .pwe-map__stats-diagram {
                    width: 50%;
                    min-height: 450px;
                    flex-direction: column-reverse;
                    align-items: center;
                    min-width: 360px;
                }
                .pwe-map__stats-diagram-container {
                    width: 100%;
                }
                .pwe-map__stats-number-container {
                    min-width: 350px;
                }
                .pwe-map__stats-number-container {
                    min-width: 280px;
                }
                .pwe-map__stats-number-container .pwe-map__stats-number-box h2 {
                    min-width: 160px;
                    font-size: 30px;
                }
            }
            @media(max-width:760px){
                .pwe-map__stats-section {
                    flex-direction: column-reverse;
                }
                .pwe-map__stats-diagram {
                    width: 100%;
                }
                .pwe-map__stats-diagram-bars-container {
                    max-width: 500px;
                    min-height: 240px;
                }
                .pwe-map__stats-number-container {
                    width: 100%;
                }
                .pwe-map__stats-number-box:not(.pwe-map__stats-diagram-countries) h2 {
                    width: 50%;
                }
            }
            @media(max-width:420px) {
                .pwe-map__title {
                    font-size: 20px !important;
                }
                .pwe-map__stats-section {
                    align-content: center;
                    padding: 36px 0;
                }
                .pwe-map__stats-diagram {
                    min-width: unset;
                }
                .pwe-map__stats-number-container .pwe-map__stats-number-box {
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 0;
                }
                .pwe-map__stats-number-box:not(.pwe-map__stats-diagram-countries) h2 {
                    width: 100%;
                    text-align: center;
                }
                .pwe-map__stats-number-box-text span {
                    display: none;
                }
                .pwe-map__stats-diagram-bar-number, .pwe-map__stats-diagram-bars-label {
                    font-size: 12px !important;
                }
                .pwe-map__stats-diagram-bar-item {
                    width: 20px;
                }
            }

            .pwe-map__container-3d {
                position: absolute;
                width: 100%;
                max-width: 800px;
                display: flex;
                align-items: center;
                bottom: -50%;
                left: clamp(110%,82vw,200%);
                transform: translate(-50%, 0);
                z-index: 0;
            }
            .pwe-map__container-3d::before {
                content: "";
                background: linear-gradient(20deg, white 10%, transparent 70%);
                width: 100%;
                height: 100%;
                position: absolute;
            }
            .pwe-map__container-3d canvas {
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 1 / 1;
            }
            @media(max-width:960px){
                .pwe-map__container-3d {
                    bottom: -25%;
                }
            }
            @media(max-width:960px){
                .pwe-map__container-3d {
                    bottom: -25%;
                }
            }
            @media(max-width:760px){
                .pwe-map__container-3d {
                    bottom: 5%;
                }
            }
            @media(max-width:600px){
                .pwe-map__container-3d {
                    min-width: 500px;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }
            }
            @media(max-width:420px){
                .pwe-map__container-3d {
                    bottom: 10%;
                }
            }
        </style>';
    } else if ($map_dynamic_preset == 'preset_3') {
        $output .= '
            <style>
                .pwe-map__stats-number-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 40px;
                    justify-content: space-between;
                    align-items: stretch;
                    margin: 36px 0;
                }

                .pwe-map__stats-number-container:before {
                    content: "";
                    position: absolute;
                    top: -9%;
                    left: -3%;
                    width: 60%;
                    height: 118%;
                    background: #f7f7f7;
                    z-index: 0;
                    border-radius: 24px;
                }

                .pwe-map__stats-number-card {
                    display: flex;
                    flex-direction: column;
                    flex: 1 1 260px; /* minimalna szerokość 260px, ale może rosnąć */
                    z-index: 1;
                }

                .pwe-map__title-section {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 8px;
                    padding: 24px;
                }

                .pwe-map__title,
                .pwe-map__edition,
                .pwe-map__year {
                    margin: 0;
                }

                .pwe-map__edition,
                .pwe-map__year {
                    font-size: 20px
                }

                .pwe-map__stats-number-box {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start;
                    padding: 24px;
                    border-radius: 20px;
                    background-color: color-mix(in srgb, var(--accent-color), white 80%);
                    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
                    align-items: flex-start;
                    position: relative;
                    overflow: hidden;
                }

                .pwe-map__stats-number-card:first-of-type .pwe-map__stats-number-box {
                    background: white;
                }

                .pwe-map__stats-number-box h2 {
                margin: 0;
                font-size: 32px;
                font-weight: 800;
                }
                .pwe-map__stats-number-box-text p {
                    margin: 0;
                }

                .pwe-map__button-link {
                    padding: 12px 24px;
                    min-width: 224px;
                    background: var(--main2-color);
                    color: white !important;
                    font-size: 16px;
                    font-weight: 600;
                    text-transform: uppercase;
                    border-radius: 36px;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }

                .pwe-map__button-link svg {
                    fill: white;
                    width: 24px;
                    aspect-ratio: 1/1;
                    margin-left: 2px;
                    transition: 0.3s all;
                }

                .pwe-map__button-link:hover svg {
                    margin-left: 6px;
                }

                .pwe-map__stats-number-card h2 {
                    font-size: 32px;
                    font-weight: 800;
                    z-index: 1;
                }

                .pwe-map__stats-number-box-text p {
                    position: relative;
                    font-weight: 600;
                    font-size: 18px;
                    z-index: 1;
                }

                .pwe-map__divider {
                    height: 4px;
                    width: 100%;
                    background: #0000001c;
                    border: 0;
                    border-radius: 36px;
                    z-index: 1;
                }

                .pwe-map__stats-number-increase {
                    display: flex;
                    align-items: center;
                    gap: 4px;
                    background: #CEEED7;
                    color: #09AB37 !important;
                    font-weight: 600;
                    padding: 2px 10px;
                    border-radius: 36px;
                    margin-top: 8px;
                    z-index: 1;
                }

                .pwe-map__stats-number-increase p {
                    margin: 0;
                }

                .pwe-map__stats-number-increase svg {
                    fill: #09AB37;
                    width : 24px;
                    aspect-ratio: 1/1;
                }

                .pwe-map__logo-section {
                    margin: 24px 12px;
                    padding: 24px;
                    border-radius: 20px;
                    background-color: color-mix(in srgb, var(--main2-color), white 30%);
                    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .pwe-logo-original {
                    max-width: 100%;
                    height: auto;
                    display: block;
                }

                .pwe-map__big-icon {
                    position: absolute;
                    opacity: 0.95;
                    z-index: 0;
                }

                .pwe-map__icon-visitor {
                    right: -18%;
                    bottom: -57%;
                    width: 70%;
                    aspect-ratio: 1/1;
                    max-width: 260px;
                }

                .pwe-map__icon-exhibitor {
                    right: -12%;
                    bottom: -12%;
                    width: 70%;
                    aspect-ratio: 1/1;
                    max-width: 65%;
                }

                .pwe-map__icon-area {
                    right: -18%;
                    bottom: -30%;
                    width: 70%;
                    aspect-ratio: 1/1;
                    max-width: 260px;
                }

                @media (min-width: 600px) and (max-width: 960px) {

                .pwe-map__stats-number-container {
                    gap: 24px;
                    margin: 24px 0;
                }

                .pwe-map__stats-number-container:before {
                    left: 0;
                    width: 75%;
                    top: -4%;
                    height: 112%;
                }
               
                .pwe-map__stats-number-card {
                    flex: 1 1 calc(50% - 24px);
                }

                .pwe-map__stats-number-card:nth-child(3) {
                    flex-basis: 100%;
                }

                .pwe-map__stats-number-card h2 {
                    font-size: 28px;
                }

                .pwe-map__stats-number-box-text p {
                    font-size: 16px;
                }

                
                .pwe-map__big-icon {
                    opacity: 0.85;
                }

                .pwe-map__icon-visitor,
                .pwe-map__icon-exhibitor,
                .pwe-map__icon-area {
                    width: 60%;
                    max-width: 220px;
                }
                }

                @media(max-width:600px) {
                    .pwe-map__stats-number-container {
                        margin: 16px 0;
                    }
                    .pwe-map__stats-number-container:before {
                        width: 50%;
                        top: -1%;
                        height: 102%;
                    }
                    .pwe-map__stats-number-card:has(.pwe-map__logo-section) {
                        flex-direction: column-reverse;
                    }
                    .pwe-map__big-icon {
                        opacity: 0.75;
                    }
                    .pwe-map__icon-exhibitor, .pwe-map__icon-area {
                        max-width: 200px;
                    }
                    .pwe-map__stats-number-card h2 {
                        font-size: 28px;
                    }
                    .pwe-map__edition, .pwe-map__year {
                        font-size: 18px;
                    }
                    .pwe-map__stats-number-box-text p {
                        font-size: 16px;
                    }
                }
            </style>';
    }
} else if ($map_type === 'PWEMap3D') {
    $output = '
    <style>
        .pwe-map__container-3d {
            position: relative;
            width: 100%;
        }
        .pwe-map__container-3d canvas {
            width: 100% !important;
            height: auto !important;
            aspect-ratio: 1 / 1;
        }
        .pwe-map__canvas-overlay {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: '. $map_overlay .';
            z-index: 2;
        }
        .pwe-map__numbers {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
        }
    </style>';
}


