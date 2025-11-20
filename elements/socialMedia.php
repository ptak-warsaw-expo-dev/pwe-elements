<?php
/**
* Class PWElementSocials
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementSocials extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {
        $text_color = 'color:' . self::findColor($atts["text_color_manual_hidden"], $atts["text_color"], "black") . '!important;';

        $btn_text_color = 'color:' . self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important; border-width: 0 !important;';
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']) . '!important;';
        $btn_shadow_color = 'box-shadow: 9px 9px 0px -5px ' . self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black') . '!important;';
        $btn_border = 'border: 1px solid ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$fair_colors['Accent']) . '!important;';

        $output = '';
        
        $output .= '
                <style>
                    .pwelement_'. self::$rnd_id .' .pwe-btn {
                        ' . $btn_text_color
                        . $btn_color
                        . $btn_shadow_color
                        . $btn_border .'
                    }
                    #socialMedia {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        border: 1px solid black;
                        min-height: 270px;
                        max-width: 500px;
                        margin:auto;
                        min-width: 250px;
                        position: relative;
                    }
                    .pwelement_' . self::$rnd_id . ' :is(#socialMedia h4, .pwe-block-socialMedia ul) {
                        margin-top:0 !important;
                        ' . $text_color . '
                    }
                    #socialMedia span {
                        padding-top: 0;
                    }
                    #socialMedia span a {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                    #socialMedia span p {
                        margin: 0;
                    }
                    .pwe-block-socialMedia {
                        display: flex;
                        align-items: center;
                        margin:18px 0 !important;
                    }
                    .absolute-facebook-Img{
                        position: absolute;
                        left:12%;
                    }
                    @media (max-width: 569px) {
                        .pwe-facebook{
                            transform-origin: center !important;
                        }
                    }
                    @media (max-width: 380px){
                        .absolute-facebook-Img{
                            display: none;
                        }
                    }
                </style>
                <div id="socialMedia" class="pwe-container-socialMedia  text-centered drive">
                    <h4>'.
                        self::languageChecker(
                            <<<PL
                                Śledź nas na social mediach i zyskaj
                            PL,
                            <<<EN
                                Follow us on social media and gain
                            EN
                        )
                    .'</h4>

                    <div class="pwe-block-socialMedia">
                        <img class="absolute-facebook-Img" src="/wp-content/plugins/pwe-media/media/facebookIcon.png" alt="facebookIcon"/>
                        <ul class="list-style-none" style="padding-left:0 !important;">'.
                            self::languageChecker(
                                <<<PL
                                    <li> wiedzę </li>
                                    <li> kontakty </li>
                                    <li> rabaty </li>
                                PL,
                                <<<EN
                                    <li> knowledge </li>
                                    <li> contacts </li>
                                    <li> discounts </li>
                                EN
                            )
                        .'</ul> 
                    </div>

                    <span>
                        <a class="pwe-facebook pwe-link btn border-width-0 shadow-black btn-accent btn-flat" href="[trade_fair_facebook]" alt="facebook link" target="_blank">
                            <i class="fa fa-facebook fa-1x fa-fw"></i><p>Facebook</p>
                        </a>
                    </span>
                </div>';
        return $output;
    }
}