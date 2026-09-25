<?php

/**
 * Class PWElementTest
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementQRActiveStart extends PWEQRActive {

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
            .row-parent:has(.pwe-ticket-activation){
                max-width: 100%;
                padding: 0 !important;  
            }
            .wpb_column:has(.pwe-ticket-activation) {
                max-width: 100%;
            }
            .pwe-ticket-activation-wrapper {
                display: flex;
                min-height: 100vh;
            }
            .pwe-ticket-activation-left {
                width: 60%;
                display: flex;
                justify-content: center;
                align-items: center;
                background-image: url(/wp-content/plugins/pwe-media/media/bg-qr-white.png);
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
                background-color: #f3f3f3;
            }
            .pwe-ticket-activation-right {
                width: 40%;
                display: flex;
                justify-content: center;
                align-items: center;
                background-image: url(/wp-content/plugins/pwe-media/media/bg-qr-blue.png);
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
                background-color: #009cff;
            }
            .pwe-ticket-activation-left-content,
            .pwe-ticket-activation-right-content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
            }
            .pwe-ticket-activation-left :is(h3, h4, a) {
                color: #0d6588;
            }
            .pwe-ticket-activation-left h3 {
                font-size: 52px;
                font-weight: 700;
                margin: 0;
            }
            .pwe-ticket-activation-left h4 {
                font-size: 28px;
                margin: 0;
            }
            .pwe-ticket-activation-left h4 span:first-child {
                font-weight: 700;
            }
            .pwe-ticket-activation-left h4 span:nth-child(2) {
                font-weight: 500;
            }
            .pwe-ticket-activation-left-button {
                background-color: #009cff;
                padding: 18px 64px;
                margin-top: 18px; 
                border-radius: 36px;
                min-width: 240px;
            }
            .pwe-ticket-activation-left-button a {
                font-size: 24px;
                font-weight: 600;
                color: white;
            }
            .pwe-ticket-activation-left p {
                color: red;
            }
            .pwe-ticket-activation-alert {
                display: flex;
                gap: 18px;
                margin-top: 36px;
                max-width: 400px;
            }
            .pwe-ticket-activation-alert-exclamation-mark {
                border: 3px solid;
                padding: 2px 15px;
                font-size: 30px;
                font-weight: 600;
                margin: 0;
                display: flex;
                line-height: 1.2;
                justify-content: center;
                align-items: center;
            }
            .pwe-ticket-activation-alert-text {
                margin: 0;
                display: flex;
                align-items: center;
                font-weight: 700;
                line-height: 1.2;
            }
            .pwe-ticket-activation-right :is(h3, p) {
                color: white;
            }
            .pwe-ticket-activation-right h3 {
                margin: 0;
                font-size: 42px;
                font-weight: 700;
                width: 250px;
            }
            .pwe-ticket-activation-right p {
                margin-top: 36px;
                max-width: 260px;
                font-weight: 700;
                line-height: 1.2;
            }
            .pwe-ticket-activation-right-button {
                background-color: white;
                margin-top: 24px;
                padding: 18px 46px;
                border-radius: 36px;
                min-width: 240px;
            }
            .pwe-ticket-activation-right-button a {
                font-size: 20px;
                font-weight: 600;
            }

            .pwe-ticket-activation-left-button:hover a {
                color: white !important;
            }
            .pwe-ticket-activation-right-button:hover a {
                color: black !important;
            }
            .pwe-ticket-activation-button a {
                display: flex;
                justify-content: center; 
                align-items: center;
                width: 100%;
                height: auto;
                gap: 10px;
            }
            .pwe-ticket-activation-button span {
                transition: transform 0.3s ease;
                height: 32px;
                font-size: 22px;
            }
            .pwe-ticket-activation-button:hover span {
                transform: translateX(10px);
            }
            @media (max-width:768px) {
                .pwe-ticket-activation-left {
                    min-height: 60vh;
                }
                .pwe-ticket-activation-right {
                    min-height: 40vh;
                }
                .pwe-ticket-activation-wrapper {
                    flex-direction: column;
                }
                .pwe-ticket-activation-left,
                .pwe-ticket-activation-right {
                    width: 100%;
                    padding: 36px;
                }
            }
            @media (max-width:450px) {
                .pwe-ticket-activation-left h3 {
                    font-size: 36px;
                }
                .pwe-ticket-activation-left h4 {
                    font-size: 24px;
                }
                .pwe-ticket-activation-left-button {
                    padding: 18px 46px;
                }
                .pwe-ticket-activation-right-button {
                    padding: 18px 36px;
                }
                .pwe-ticket-activation-left-button a,
                .pwe-ticket-activation-right-button a {
                    font-size: 16px;
                }
                .pwe-ticket-activation-right h3 {
                    font-size: 34px;
                }
            }
            @media (max-width:400px) {
                .pwe-ticket-activation-alert {
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }
                .pwe-ticket-activation-alert-exclamation-mark {
                    width: 50px;
                }
            }
            .pwe-flag {
                position: absolute;
                top: 18px;
                right: 18px;
                width: 30px;
                height: 20px
            }
            .pwe-flag img {
                width: 100%;
                transition: .3s ease;
            }
            .pwe-flag:hover img {
                transform: scale(1.1);
            }
        </style>';

        require_once plugin_dir_path(__FILE__) . '/../widgets/flags.php';
        
        $output .= '
        <div class="pwe-ticket-activation">
            <div class="pwe-ticket-activation-wrapper">
                <div class="pwe-ticket-activation-left">
                    <div class="pwe-ticket-activation-left-content">'. 
                        self::languageChecker(
                            <<<PL
                                <h3>AKTYWUJ<br>SWÓJ BILET</h3>
                                <h4><span>TO PROSTE!</span><br><span>( 30 SECUND )</span></h4>
                                <div class="pwe-ticket-activation-left-button pwe-ticket-activation-button"><a href="/test-b/">AKTYWUJ <span>⏵</span></a></div>
                                <span class="pwe-ticket-activation-alert"><p class="pwe-ticket-activation-alert-exclamation-mark">!</p><p class="pwe-ticket-activation-alert-text">AKTYWACJA NIEZBĘDNA DO WEJŚCIA NA TEREN TARGÓW</p></span>
                            PL,
                            <<<EN
                                <h3>ACTIVATE<br>YOUR TICKET</h3> 
                                <h4><span>IT'S EASY!</span><br><span>( 30 SECONDS )</span></h4> 
                                <div class="pwe-ticket-activation-left-button pwe-ticket-activation-button"><a href="/test-b/">ACTIVATE <span>⏵</span></a></div> 
                                <span class= "pwe-ticket-activation-alert"><p class="pwe-ticket-activation-alert-exclamation-mark">!</p><p class="pwe-ticket-activation-alert-text">ACTIVATION NECESSARY TO ENTER THE FAIR AREA</p></span>
                            EN
                        ).'
                    </div>
                </div>
                <div class="pwe-ticket-activation-right">
                    <div class="pwe-ticket-activation-right-content">'. 
                        self::languageChecker(
                            <<<PL
                                <h3>NOWY BILET</h3>
                                <p>ZAREJESTRUJ SIĘ I OTRZYMAJ SWÓJ BILET</p>
                                <div class="pwe-ticket-activation-right-button pwe-ticket-activation-button"><a href="/rejestracja/">ZAREJESTRUJ SIĘ <span>⏵</span></a></div>
                            PL,
                            <<<EN
                                <h3>NEW TICKET</h3>
                                <p>REGISTER AND GET YOUR TICKET</p>
                                <div class="pwe-ticket-activation-right-button pwe-ticket-activation-button"><a href="/rejestracja/">REGISTER <span>⏵</span></a></div>
                            EN
                        ).'
                    </div>
                </div>
            </div>
        </div>
        ';

        return $output;
    }
}