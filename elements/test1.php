<?php

/**
 * Class PWElementTest1
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementTest1 extends PWElements {

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
            body:has(.pwelement_'. self::$rnd_id .') .menu-wrapper,
            body:has(.pwelement_'. self::$rnd_id .') .site-footer {
                display: none !important;
            }
            .row-parent:has(.pwe-ticket-activation-1-3) {
                padding: 18px !important;
                margin: 0 auto 36px;
            }
            .row-container:has(.pwe-ticket-activation-1-3) {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                min-height: 100vh;
                background-image: url(/wp-content/plugins/pwe-media/media/ticket-activation1.jpg);
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
            }
            .pwelement:has(.pwe-ticket-activation-1-3) {
                display: flex;
                justify-content: center;
            }
            .pwe-ticket-activation-1-3 {
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: rgb(255 255 255);
                padding: 36px;
                border-radius: 36px;
                max-width: 960px;
                min-height: 500px;
            }
            .pwe-ticket-activation-1-3-wrapper {
                width:100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .pwe-ticket-activation-1-3 h2 {
                color: #004D69;
                font-size: 46px;
                font-weight: 700;
                margin: 0;
                text-align: center;
            }
            .pwe-ticket-activation-1-3 img {
                max-width: 650px;
                width: 100%;
            }
            .pwe-ticket-activation-1-3-footer {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 18px;
            }
            .pwe-ticket-activation-1-3-info {
                width: 60%;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .pwe-ticket-activation-1-3-info h4 {
                color: #004D69;
                font-size: 24px;
                font-weight: 600;
                text-align: center;
            }   
            .pwe-ticket-activation-1-3-info-items {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }
            .pwe-ticket-activation-1-3-info-items li {
                font-size: 18px;
                font-weight: 600;
                margin-left: 10px;
                list-style: none;
                line-height: normal;
            }
            .pwe-ticket-activation-1-3-info-items li span {
                font-size: 20px;
            }
            .pwe-ticket-activation-1-3-button {
                background-color: #004D69;
                padding: 18px 64px;
                margin-top: 18px; 
                border-radius: 36px;
                min-width: 240px;
                height: fit-content;
            }
            .pwe-ticket-activation-1-3-button a {
                font-size: 24px;
                font-weight: 600;
                color: white;
                gap: 10px;
            }
            .pwe-ticket-activation-1-3-step {
                position: absolute;
                bottom: -50px;
                left: 0;
                font-size: 30px;
                font-weight: 600;
                color: white;
            }

            .pwe-ticket-activation-button:hover a {
                color: white !important;
            }
            .pwe-ticket-activation-button a {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                height: auto;
            }
            .pwe-ticket-activation-button span {
                transition: transform 0.3s ease;
                height: 32px;
                font-size: 22px;
            }
            .pwe-ticket-activation-button:hover span {
                transform: translateX(10px);
            }
            @media (max-width: 960px) {
                .pwe-ticket-activation-1-3 h2 {
                    font-size: 36px;
                }
                .pwe-ticket-activation-1-3-footer {
                    flex-direction: column;
                }
                .pwe-ticket-activation-1-3-info {
                    width: 100%; 
                }
            }
            @media (max-width: 650px) {
                .pwe-ticket-activation-1-3-info-items {
                    padding: 0 !important;
                }
                .pwe-ticket-activation-1-3-info-items li {
                    font-size: 15px; 
                }
            }
            @media (max-width: 500px) {
                .pwe-ticket-activation-1-3 {
                    padding: 36px 18px;
                }
                .pwe-ticket-activation-1-3-info h4 {
                    font-size: 20px;
                }
                .pwe-ticket-activation-1-3-info-items {
                    flex-wrap: nowrap;
                    flex-direction: column;
                }
                .pwe-ticket-activation-1-3-info-items li {
                    text-align: center;
                }
            }
        </style>';

        require_once plugin_dir_path(__FILE__) . '/../widgets/flags.php';
        
        $output .= '
        <div class="pwe-ticket-activation-1-3">
            <div class="pwe-ticket-activation-1-3-wrapper">'. 
                self::languageChecker(
                    <<<PL
                        <h2>PRZYGOTUJ SWÓJ KOD QR</h2>
                    PL,
                    <<<EN
                        <h2>PREPARATE YOUR QR CODE</h2>
                    EN
                ).'
                <img src="/wp-content/plugins/pwe-media/media/phone-qr.png">
                <div class="pwe-ticket-activation-1-3-footer">
                    <div class="pwe-ticket-activation-1-3-info">'.
                        self::languageChecker(
                            <<<PL
                                <h4>OTRYMASZ NASTĘPUJĄCE BONUSY:</h4>
                                <ul class="pwe-ticket-activation-1-3-info-items">
                                    <li><span>&#8226</span> BILET NA TARGI</li>
                                    <li><span>&#8226</span> DOSTĘP DO VIP ROOM</li>
                                    <li><span>&#8226</span> BEZPŁATNE MATERIAŁY EDUKACYJNE</li>
                                    <li><span>&#8226</span> SPOTKANIE Z PRELEGENTAMI</li>
                                    <li><span>&#8226</span> SPECJALNE ZNIŻKI NA PRODUKTY</li>
                                </ul>
                            PL,
                            <<<EN
                                <h4>YOU WILL RECEIVE THE FOLLOWING BONUSES:</h4>
                                <ul class="pwe-ticket-activation-1-3-info-items">
                                    <li><span>&#8226</span> TICKET TO THE FAIR</li>
                                    <li><span>&#8226</span> ACCESS TO VIP ROOM</li>
                                    <li><span>&#8226</span> FREE EDUCATIONAL MATERIALS</li>
                                    <li><span>&#8226</span> MEETING WITH SPEAKERS</li>
                                    <li><span>&#8226</span> SPECIAL DISCOUNTS ON PRODUCTS</li>
                                </ul>
                            EN
                        ).'
                    </div>
                    <div class="pwe-ticket-activation-1-3-button pwe-ticket-activation-button">'. 
                        self::languageChecker(
                            <<<PL
                                <a href="/test-c/">ZESKANUJ <span>⏵</span></a>
                            PL,
                            <<<EN
                                <a href="/test-c/">SCAN <span>⏵</span></a>
                            EN
                        ).'
                    </div>
                </div>
            </div>'. 
            self::languageChecker(
                <<<PL
                    <span class="pwe-ticket-activation-1-3-step">KROK 1/3</span>
                PL,
                <<<EN
                    <span class="pwe-ticket-activation-1-3-step">STEP 1/3</span>
                EN
            ).'
        </div>
        ';

        return $output;
    }
}