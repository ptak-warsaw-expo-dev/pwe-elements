<?php 

/**
 * Class PWElementHeaderNew
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementHeaderNew extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }


    public static function output($atts) {

        $output = '
        <style>
        .pwe-header-new {
            
        }
        .pwe-header-new-wrapper {
            display: flex;
            justify-content: space-between;
            position: relative;
            width: 100%;
            max-height: 500px;
        }
        .pwe-header-new-left {
            width: 50%;
            display: flex;
        }
        .pwe-header-new-left-img-desktop {
            width: 50%;
            min-width: 200px;
            position: relative;
        }
        .pwe-header-new-left-img-desktop img {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        .pwe-header-new-left-img-mobile-left,
        .pwe-header-new-left-img-mobile-right {
            display: none;
        }
        .pwe-header-new-left-content * {
            margin: 0 !important;
        }
        .pwe-header-new-left-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 100px 8px 100px 36px;
        }
        .pwe-header-new-left-content-wrapper .pwe-header-new-name {
            font-weight: 700;
        }
        .pwe-header-new-left-content-wrapper .pwe-header-new-edition {
            font-size: 28px;
        }
        .pwe-header-new-left-content-wrapper .pwe-header-new-date {

        }
        .pwe-header-new-left-content-wrapper .pwe-header-new-ptak {
            font-size: 14px;
            padding-top: 8px;
            font-weight: 500;
        }
        .pwe-header-new-left-content .pwe-header-new-logo {
            max-width: 300px;
            padding-bottom: 12px;
        }
        .pwe-header-new-right {
            width: 40%;
            max-width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .pwe-header-new-right-img {
            position: absolute;
            z-index: 1;
            top: 0;
            right: 0;
            bottom: 0;
            height: 100%;
        }
        .pwe-header-new-right-img .pwe-header-new-right-img-desktop {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        .pwe-header-new-right-img .pwe-header-new-right-img-mobile {
            display: none;
        }
        .pwe-header-new-right-btn {
            z-index: 2;
        }
        .pwe-header-new-right-btn a {
            background-image: linear-gradient(to right, #e52d27 0%, #b31217  51%, #e52d27  100%);
            margin: 10px;
            padding: 24px 36px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;            
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
            font-size: 28px;
            font-weight: 600;
            display: block;
        }

        .pwe-header-new-right-btn a:hover {
            background-position: right center;
            color: white !important;   
            text-decoration: none;
        }
        @media (max-width: 1340px) {
            .pwe-header-new-left-content {
                padding: 64px 8px 64px 36px;
            }
        }
        @media (max-width: 1200px) {
            .pwe-header-new-left-content {
                padding: 36px 0 36px 36px;
            }
            .pwe-header-new-right {
                max-width: 45%;
            }
            .pwe-header-new-right-img {
                right: -50px;
            }
        }
        @media (max-width: 1100px) {
            .pwe-header-new-right-img {
                right: -100px;
            }
        }
        @media (max-width: 1000px) {
            .pwe-header-new-right-img {
                right: -150px;
            }
        }
        @media (max-width: 960px) {
            .pwe-header-new-left-content {
                padding: 18px 0 18px 18px;
            }
            .pwe-header-new-left-content .pwe-header-new-logo {
                max-width: 220px;
            }
            .pwe-header-new-left-content-wrapper .pwe-header-new-name {
                font-size: 24px;
            }
            .pwe-header-new-left-content-wrapper .pwe-header-new-edition {
                font-size: 24px;
            }
            .pwe-header-new-left-content-wrapper .pwe-header-new-date {
                font-size: 24px;
            }
            .pwe-header-new-right-img {
                right: -50px;
            }
        }
         @media (max-width: 850px) {
            .pwe-header-new-right-img {
                right: -100px;
            }
        }
        @media (max-width: 820px) {
            .pwe-header-new-wrapper {
                flex-direction: column;
                width: 100%;
                max-height: 100%;
            }
            .pwe-header-new-left {
                width: 100%;
            }
            .pwe-header-new-left-content {
                padding: 0;
                display: flex;
                flex-direction: column;
            }
            .pwe-header-new-left-content-wrapper {
                padding: 18px 36px 100px;
            }
            .pwe-header-new-left-img-desktop {
                display: none;
            }
            .pwe-header-new-left-img-mobile-left,
            .pwe-header-new-left-img-mobile-right {
                display: block;
                height: 100%;
            }
            .pwe-header-new-left-img-mobile-left img,
            .pwe-header-new-left-img-mobile-right img {
                object-fit: cover;
                width: 100%;
                height: 100%;
            }
            .pwe-header-new-left-img-mobile-left img {
                object-position: right center;
            }
            .pwe-header-new-left-img-mobile-right img {
                object-position: left center;
            }
            .pwe-header-new-right {
                width: 100%;
                max-width: 100%;
                position: relative;
                height: auto;
            }
            .pwe-header-new-right-img {
                position: relative;
                width: 100%;
                height: auto;
                margin-top: -100px;
                right: 0px;
            }
            .pwe-header-new-right-img .pwe-header-new-right-img-desktop {
                display: none;
            }
            .pwe-header-new-right-img .pwe-header-new-right-img-mobile {
                display: block;
                width: 100%;
                height: auto;
            }
            .pwe-header-new-right-btn {
                margin-left: 0;
                position: absolute;
            }
            .pwe-header-new-right-btn a {
                padding: 24px;
                font-size: 24px;
            }
        }    
        @media (max-width: 450px) {
            .pwe-header-new-right-btn {
                margin-bottom: 36px;
            }
        }
         
        </style>';
        
        $output .= '
        <div class="pwe-header-new">
            <div class="pwe-header-new-wrapper">
                <div class="pwe-header-new-left">
                    <div class="pwe-header-new-left-img-desktop">
                        <img src="/wp-content/plugins/pwe-media/media/colors.webp">
                    </div>
                    <div class="pwe-header-new-left-content">
                        <div class="pwe-header-new-left-img-mobile-left">
                            <img src="/wp-content/plugins/pwe-media/media/colors_horizontal.webp">
                        </div>
                        <div class="pwe-header-new-left-content-wrapper">
                            <img class="pwe-header-new-logo" src="/doc/logo-color.webp">
                            <h2 class="pwe-header-new-name">MIĘDZYNARODOWE TARGI REKLAMY I DRUKU</h2>
                            <p class="pwe-header-new-edition">20. EDYCJA</p>
                            <h2 class="pwe-header-new-date">28-31|01|2025</h2>
                            <p class="pwe-header-new-ptak">PTAK WARSAW EXPO</p>
                        </div>
                    </div>
                </div>
                <div class="pwe-header-new-right">
                    <div class="pwe-header-new-right-img">
                        <img class="pwe-header-new-right-img-desktop" src="/wp-content/plugins/pwe-media/media/hall_desktop.webp">
                        <img class="pwe-header-new-right-img-mobile" src="/wp-content/plugins/pwe-media/media/hall_mobile.webp">
                    </div>
                    <div class="pwe-header-new-right-btn">
                        <a href="#">WEŹ UDZIAŁ</a>
                    </div>
                </div>
            </div>
        </div>
        ';

        return $output;
    }
}