<?php
/**
* Class PWElementAddress
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementAddress extends PWElements {

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
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';

        $output = '';

        $output .= '
            <style>
                #ptakAdress{
                    border: 1px solid black;
                    border-radius: 18px;
                    max-width: 555px;
                    margin:auto;
                    min-width: 350px;
                    display: flex;
                    align-items: center;
                    justify-content: space-around;
                    padding: 36px;
                }
                .row-container:has(#socialMedia) #ptakAdress {
                    max-width: 500px;
                }
                #ptakAdress li {
                    ' . $text_color . '
                }
                .pwe-text-ptakAdress li {
                    font-size:30px !important;
                    font-weight: 700;
                    color:black;
                    line-height: 1.2;
                }
                @media (max-width: 760px) {
                    #ptakAdress, #socialMedia {
                        min-width: 260px;
                    }
                    .pwe-text-ptakAdress li {
                        font-size:24px !important;
                    }
                }
            </style>
            <div id="ptakAdress" class="pwe-container-ptakAdress text-centered">
                <ul class="list-style-none pwe-text-ptakAdress" style="padding-left:0 !important; margin: 0 !important">
                    <li> Ptak Warsaw Expo </li>
                    <li> Al. Katowicka 62, </li>'.
                    self::languageChecker('<li> 05-830 Nadarzyn, Polska </li>', '<li> 05-830 Nadarzyn, Poland </li>').'
                </ul>
            </div>';

        return $output;
    }
}
