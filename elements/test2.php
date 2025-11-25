<?php

/**
 * Class PWElementTest2
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementTest2 extends PWElements {

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
            .row-parent:has(.pwe-ticket-activation-3-3) {
                padding: 18px !important;
                margin: 0 auto 36px;
            }
            .row-container:has(.pwe-ticket-activation-3-3) {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                min-height: 100vh;
                background-image: url(/wp-content/plugins/pwe-media/media/ticket-activation3.jpg);
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
            }
            .pwelement:has(.pwe-ticket-activation-3-3) {
                display: flex;
                justify-content: center;
            }
            .pwe-ticket-activation-3-3 {
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
            .pwe-ticket-activation-3-3-wrapper {
                width:100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .pwe-ticket-activation-3-3 h2 {
                color: #004D69;
                font-size: 40px;
                font-weight: 700;
                margin: 0;
                text-align: center;
            }
            .pwe-ticket-activation-3-3 h4 {
                color: #004D69;
                font-size: 28px;
                font-weight: 600;
                text-align: center;
            }
            .pwe-ticket-activation-3-3-step {
                position: absolute;
                bottom: -50px;
                left: 0;
                font-size: 30px;
                font-weight: 600;
                color: white;
            }
            @media (max-width: 960px) {
                .pwe-ticket-activation-3-3 h2 {
                    font-size: 36px;
                }
            }
            @media (max-width: 650px) {
                .pwe-ticket-activation-3-3 h2 {
                    font-size: 30px;
                }
                .pwe-ticket-activation-3-3 h4 {
                    font-size: 24px;
                }
            }
            @media (max-width: 500px) {
                .pwe-ticket-activation-3-3 {
                    padding: 36px 18px;
                    min-height: auto;
                }
                .pwe-ticket-activation-3-3 h2 {
                    font-size: 24px;
                }
                .pwe-ticket-activation-3-3 h4 {
                    font-size: 18px;
                }
            }
        </style>';

        require_once plugin_dir_path(__FILE__) . '/../widgets/flags.php';
        
        $output .= '
        <div class="pwe-ticket-activation-3-3">'. 
            self::languageChecker(
                <<<PL
                <div class="pwe-ticket-activation-3-3-wrapper">
                    <h2>DZIĘKUJEMY,<br>TWÓJ BILET ZOSTAŁ AKTYWOWANY!</h2>
                    <h4>SPRAWDŹ POCZTĘ - NA TWÓJ ADRES MAILOWY OTRZYMAŁEŚ POTWIERDZIENIE AKTYWACJI BILETU.</h4>
                    <h4>ZAPRASZAMY DO WEJŚCIA</h4>
                </div>
                <span class="pwe-ticket-activation-3-3-step">KROK 3/3</span>
                PL,
                <<<EN
                <div class="pwe-ticket-activation-3-3-wrapper">
                    <h2>THANK YOU,<br>YOUR TICKET HAS BEEN ACTIVATED!</h2>
                    <h4>CHECK YOUR EMAIL - YOU HAVE RECEIVED A TICKET ACTIVATION CONFIRMATION AT YOUR EMAIL ADDRESS.</h4>
                    <h4>WE INVITE YOU TO ENTER</h4>
                </div>
                <span class="pwe-ticket-activation-3-3-step">STEP 3/3</span>
                EN
            ).'
        </div>
        ';

        return $output;
    }
}