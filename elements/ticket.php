<?php 

/**
 * Class PWElementTicket
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementTicket extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {

        $element_output = array(
            array(
                'type' => 'dropdown',
                'heading' => __('Select main color', 'pwelement'),
                'param_name' => 'ticket_main_color',
                'value' => self::$fair_colors,
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Title', 'pwelement'),
                'param_name' => 'ticket_title_checkbox',
                'save_always' => true,
                'value' => array(
                  __('Bilet jednodniowy', 'pwelement') => 'ticket_one_day_title',
                  __('Bilet dwudniowy', 'pwelement') => 'ticket_two_days_title',
                  __('Bilet Junior', 'pwelement') => 'ticket_junior_title',
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Title custom text', 'pwelement'),
                'param_name' => 'ticket_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'textfield', 
                'group' => 'PWE Element',
                'heading' => __('Price', 'pwelement'),
                'param_name' => 'ticket_price',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'heading' => __('Change price', 'pwelement'),
                'param_name' => 'ticket_prices',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Start', 'pwelement'),
                        'param_name' => 'ticket_countdown_start',
                        'description' => __('Format (Y/m/d h:m)', 'pwelement'),
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('End', 'pwelement'),
                        'param_name' => 'ticket_countdown_end',
                        'description' => __('Format (Y/m/d h:m)', 'pwelement'),
                        'save_always' => true,
                        'admin_label' => true
                    ), 
                    array(
                        'type' => 'textfield',
                        'heading' => __('New price', 'pwelement'),
                        'param_name' => 'ticket_new_price',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Placeholder text', 'pwelement'),
                'param_name' => 'ticket_placeholder',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Button text', 'pwelement'),
                'param_name' => 'ticket_button',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Button link', 'pwelement'),
                'param_name' => 'ticket_button_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Info Text', 'pwelement'),
                'param_name' => 'ticket_info',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Info Text Block Height', 'pwelement'),
                'param_name' => 'ticket_info_height',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Footer text', 'pwelement'),
                'param_name' => 'ticket_footer',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTicket',
                ),
            ),   
        );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     */
    public static function output($atts, $content = null) {
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black');
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color);
        $btn_border = '1px solid ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], $btn_color);

        extract( shortcode_atts( array(
            'ticket_main_color' => '',
            'ticket_title_checkbox' => '',
            'ticket_title' => '',
            'ticket_price' => '',
            'ticket_prices' => '',
            'ticket_placeholder' => '',
            'ticket_button' => '',
            'ticket_button_link' => '',
            'ticket_info' => '',
            'ticket_info_height' => '',
            'ticket_footer' => '',
        ), $atts ));

        $ticket_info_decoded = base64_decode($ticket_info);// Decoding Base64
        $ticket_info_decoded = urldecode($ticket_info_decoded); // Decoding URL
        $ticket_info_content = wpb_js_remove_wpautop($ticket_info_decoded, true);// Remowe wpautop

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        $unique_id = rand(10000, 99999);
        $element_unique_id = 'pweTicket-' . $unique_id;

        if (in_array('ticket_one_day_title', explode(',', $ticket_title_checkbox))) {
            $ticket_id = "ticket_one_day_title";
            $ticket_checkbox = (get_locale() == 'pl_PL') ? "Bilet jednodniowy" : "One-day ticket"; 
            $ticket_title = !empty($ticket_title) ? $ticket_title : $ticket_checkbox;
        } else if (in_array('ticket_two_days_title', explode(',', $ticket_title_checkbox))) {
            $ticket_id = "ticket_two_days_title";
            $ticket_checkbox = (get_locale() == 'pl_PL') ? "Bilet dwudniowy" : "Two-days ticket";
            $ticket_title = !empty($ticket_title) ? $ticket_title : $ticket_checkbox;
        } else if (in_array('ticket_junior_title', explode(',', $ticket_title_checkbox))) {
            $ticket_id = "ticket_junior_title";
            $ticket_checkbox = (get_locale() == 'pl_PL') ? "Bilet Junior" : "Junior ticket";
            $ticket_title = !empty($ticket_title) ? $ticket_title : $ticket_checkbox;
        } else {
            $ticket_id = $element_unique_id; 
        }

        if(get_locale() == 'pl_PL') {
            $ticket_placeholder = !empty($ticket_placeholder) ? $ticket_placeholder : "Bilet dostępny wyłącznie online";
            $ticket_button = !empty($ticket_button) ? $ticket_button : "Kup " . strtolower($ticket_title) . "!";
        } else {
            $ticket_placeholder = !empty($ticket_placeholder) ? $ticket_placeholder : "Ticket available only online";
            $ticket_button = !empty($ticket_button) ? $ticket_button : "Buy a " . strtolower($ticket_title) . "!";
        }
        
        
        $ticket_info_height = !empty($ticket_info_height) ? $ticket_info_height : "auto";

        
        $ticket_prices_urldecode = urldecode($ticket_prices );
        $ticket_prices_json = json_decode($ticket_prices_urldecode, true);
        $old_price = '';
        $current_price = '';

        if (is_array($ticket_prices_json)) {
            $todayDate = strtotime(date('Y/m/d H:i', time()));
            foreach ($ticket_prices_json as $price){
                $countdown_start = $price["ticket_countdown_start"];
                $countdown_end = $price["ticket_countdown_end"];
                $new_price = $price["ticket_new_price"]; 
        
                if (strtotime($countdown_start) < $todayDate && $todayDate < strtotime($countdown_end)) {
                    $current_price = $new_price;
                    $old_price = '<span style="color: #044e9b; font-size: 30px; text-decoration: line-through; padding-right: 5px;">'. $ticket_price .'</span>';
                    break;
                } else if ($todayDate > strtotime($countdown_end)) {
                    $current_price = $ticket_price;
                } else if ($todayDate < strtotime($countdown_start)) {
                    $current_price = $ticket_price;
                }
            }
        } else {
            $current_price = $ticket_price;
        }

        $output = '
        <style>
            .pwelement_'. self::$rnd_id .' {
                width: 100%;
                padding: 0 9px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-container {
                padding: 18px 0;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-wrapper {
                text-align: center;
                background-color: white;
                border-radius: 25px 25px 0 25px;
                box-shadow: 5px 5px 15px black;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-header-block {
                background-color: '. self::$accent_color .';
                padding: 36px;
                border-top-left-radius: 24px;
                border-top-right-radius: 24px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-header-block h3 {
                margin: 0;
                width: inherit;
                color: white;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-header-block h3 p {
                margin: 0; 
                font-size: 20px;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-price-block {
                font-size: 45px;
                padding: 18px;
                color: #008000;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-info-block {
                min-height: '. $ticket_info_height .';
                padding: 18px 0;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-info-block :is(h1, h2, h3, h4, h5) {
                margin: 0 auto;
            }
            .pwelement_'. self::$rnd_id .' .pwe-btn {
                color: '. $btn_text_color .';
                background-color:'. $btn_color .';
                border:'. $btn_border .';
                padding: 18px 0;
                font-size: 14px;
                font-weight: 600;
                text-transform: uppercase;
                transition: .3s ease
            }
            .pwelement_'. self::$rnd_id .' .pwe-btn p,
            .pwelement_'. self::$rnd_id .' .pwe-ticket-info-block p,
            .pwelement_'. self::$rnd_id .' .pwe-ticket-footer-text p {
                margin: 0;
            }
            .pwelement_'. self::$rnd_id .' .pwe-btn:hover {
                color: white !important;
                background-color: black;
                border: 1px solid black;
            }
            .pwelement_'. self::$rnd_id .' .pwe-btn:focus:not(:focus-visible) {
                background-color:'. $btn_color .' !important;
                color: '. $btn_text_color .' !important;
                border:'. $btn_border .' !important;
            }
            .pwelement_'. self::$rnd_id .' .pwe-btn:focus {
                outline: none;
            }
            .pwelement_'. self::$rnd_id .' .pwe-ticket-footer-block {
                background-color: '. self::$accent_color .';
                border-bottom-left-radius: 24px;
                padding: 10px;
                color: white;
                min-height: 40px;
            }    
            @media (max-width: 640px) {
                .pwelement_'. self::$rnd_id .' .pwe-btn { 
                    font-size: 12px;
                    min-width: 200px !important;
                }
            } 
        </style>';

        
        if (!empty($ticket_main_color)) {
            $output .= '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-ticket-header-block,
                .pwelement_'. self::$rnd_id .' .pwe-ticket-footer-block {
                    background-color: '. $ticket_main_color .';
                }
                .pwelement_'. self::$rnd_id .' .pwe-btn,
                .pwelement_'. self::$rnd_id .' .pwe-btn:focus:not(:focus-visible) {
                    background-color: '. $ticket_main_color .';
                    border: 1px solid '. $ticket_main_color .';
                }
            </style>';
        } 

        $output .= '
            <div id="'. $ticket_id .'" class="pwe-ticket-container">
                <div class="pwe-ticket-wrapper">
                    <div class="pwe-ticket-header-block"><h3>'. wpb_js_remove_wpautop($ticket_title, true) .'</h3></div>
                    <div class="pwe-ticket-price-block">'. $old_price .'<span class="pwe-ticket-price">'. $current_price .'</span></div> 
                    <div class="pwe-ticket-placeholder-block"><span class="pwe-ticket-placeholder">'. $ticket_placeholder .'</span></div>        
                    <div class="pwe-ticket-button-block">
                        <span class="pwe-btn-container">
                            <a href="'. $ticket_button_link .'" class="pwe-btn" target="_blank">'. wpb_js_remove_wpautop($ticket_button, true) .'</a>
                        </span>
                    </div>
                    <div class="pwe-ticket-info-block">'. $ticket_info_content .'</div>
                    <div class="pwe-ticket-footer-block"><span class="pwe-ticket-footer-text">'. wpb_js_remove_wpautop($ticket_footer, true) .'</span></div>
                </div> 
            </div>';

        return $output;


    }
}