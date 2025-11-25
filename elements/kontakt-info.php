<?php
/**
* Class PWElementContactInfo
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementContactInfo extends PWElements {

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
                'type' => 'textfield',
                'heading' => __('Header', 'pwelement'),
                'group' => 'PWE Element',
                'param_name' => 'contact_header',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementContactInfo'
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Remove content', 'pwelement'),
                'group' => 'PWE Element',
                'param_name' => 'contact_content',
                'save_always' => true,
                'value' => array(
                'Zostań wystawcą' => 'wystawca',
                'Odwiedzający' => 'odwiedzajacy',
                'Współpraca z mediami' => 'media',
                'Obsługa wystawcy' => 'ob_wystawcy',
                'Obsługa techniczna' => 'technicy',
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementContactInfo'
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'param_name' => 'new_contact',
                'param_holder_class' => 'contact-info',
                'save_always' => true,
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'param_name' => 'img',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Image URL', 'pwelement'),
                        'param_name' => 'url',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Name', 'pwelement'),
                        'param_name' => 'name',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Phone', 'pwelement'),
                        'param_name' => 'phone',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Email', 'pwelement'),
                        'param_name' => 'email',
                        'save_always' => true,
                        'admin_label' => true
                    ),
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementContactInfo',
                ),
            ),
        );
        return $element_output;
    }
    
    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {        
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';

        $contact_items = vc_param_group_parse_atts($atts['new_contact'], true);

        $output = '';

        $output .= '
            <style>
                .pwelement_'.self::$rnd_id.' #contact-info :is(a, p, h4, b){
                    ' . $text_color . '
                }
                .pwelement_'. self::$rnd_id .' .raw-pwe-container {
                    display: flex;
                    align-items: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-container-contact-info-items .pwe-contact-image-container {
                    width: 150px;
                    height: 120px;
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-container-contact-info-items .pwe-contact-image-container .contact-info-img-pwe{
                    height: inherit;
                }
            </style>

            <div id="contact-info" class="pwe-container-contact">
                <div class="heading-text el-text main-heading-text half-block-padding">
                    <h4>';
                    if($atts['contact_header'] != ''){
                        $output .= $atts['contact_header'];
                    } else {
                        $output .= self::languageChecker(
                            <<<PL
                                Masz pytania?
                            PL,
                            <<<EN
                                Do you have any questions?
                            EN
                        );
                    };
                    $output .= '</h4>
                </div>';

            foreach($contact_items as $id => $contact) {
                $output .= '<div class="raw-pwe-container pwe-container-contact-info-items half-block-padding">
                                <div class="pwe-contact-image-container">';
                                    if ($contact['url']){
                                        $output .= '<img class="contact-info-img-pwe" src="'.wp_get_attachment_url($contact['url']).'" alt="zdjęcie">';
                                    } elseif ($contact['img']){
                                        $output .= '<img class="contact-info-img-pwe" src="'.wp_get_attachment_url($contact['img']).'" alt="zdjęcie">';
                                    } else {
                                        $output .= '<img class="contact-info-img-pwe" src="/wp-content/plugins/pwe-media/media/WystawcyO.jpg" alt="grafika wystawcy">';
                                    }
                                $output .= '</div>
                                <div class="uncode_text_column">';
                        if ($id === 0){
                            $output .= '<p>Kierownik projektu: <b>'.$contact['name'].'</b></p>';
                        } else {
                            $output .= '<p><b>'.$contact['name'].'</b></p>';
                        }   
                            $output .= '<p>E-mail: <a href="mailto:'.$contact['phone'].'">'.$contact['phone'].'</a></p>
                            <p>Tel.: <a href="tel'.$contact['email'].'">'.$contact['email'].'</a></p>
                        </div>
                    </div>';
            }
            
            if(strpos($atts['contact_content'], 'wystawca') === false){
                $output .= '<div class="raw-pwe-container half-block-padding image-shadow">
                                <img src="/wp-content/plugins/pwe-media/media/WystawcyZ.jpg" alt="grafika wystawcy">
                                <div class="uncode_text_column">
                                    <p>'.
                                    self::languageChecker(
                                        <<<PL
                                            Zostań wystawcą<br><a href="tel:48 517 121 906">+48 517 121 906</a>
                                        PL,
                                        <<<EN
                                            Become an Exhibitor<br><a href="tel:48 517 121 906">+48 517 121 906</a>
                                        EN
                                    )
                                    .'</p>
                                </div>
                            </div>';
            }
            
            if(strpos($atts['contact_content'], 'odwiedzajacy') === false){
                $output .= '<div class="raw-pwe-container half-block-padding image-shadow">
                                <img src="/wp-content/plugins/pwe-media/media/Odwiedzajacy.jpg" alt="grafika odwiedzajacy">
                                <div class="uncode_text_column">
                                    <p>'.
                                        self::languageChecker(
                                            <<<PL
                                                Odwiedzający<br><a href="tel:48 513 903 628">+48 513 903 628</a>
                                            PL,
                                            <<<EN
                                                Visitors<br><a href="tel:48 513 903 628">+48 513 903 628</a>
                                            EN
                                        )
                                    .'</p>
                                </div>
                            </div>';
            }
            
            if(strpos($atts['contact_content'], 'media') === false){
                $output .= '<div class="raw-pwe-container half-block-padding image-shadow">
                                <img src="/wp-content/plugins/pwe-media/media/Media.jpg"  alt="grafika media">
                                <div class="uncode_text_column">
                                    <p>'.
                                        self::languageChecker(
                                            <<<PL
                                                Współpraca z mediami<br><a href="mailto:media@warsawexpo.eu">media@warsawexpo.eu</a>
                                            PL,
                                            <<<EN
                                                For Media<br><a href="mailto:media@warsawexpo.eu">media@warsawexpo.eu</a>
                                            EN
                                        )
                                        .'</p>
                                </div>
                            </div>';
            }
            
            if(strpos($atts['contact_content'], 'ob_wystawcy') === false){
                $output .= '<div class="raw-pwe-container half-block-padding image-shadow">
                                <img src="/wp-content/plugins/pwe-media/media/WystawcyO.jpg" alt="grafika obsluga">
                                <div class="uncode_text_column">
                                    <p>'.
                                        self::languageChecker(
                                            <<<PL
                                                Obsługa Wystawców<br><a href="tel:48 501 239 338">+48 501 239 338</a>
                                            PL,
                                            <<<EN
                                                Exhibitor service<br><a href="tel:48 501 239 338">+48 501 239 338</a>
                                            EN
                                        )
                                        .'</p>
                                </div>
                            </div>';
            }
            
            if(strpos($atts['contact_content'], 'technicy') === false){
                $output .= '<div class="raw-pwe-container half-block-padding image-shadow">
                                <img src="/wp-content/plugins/pwe-media/media/Technicy.jpg" alt="grafika technicy">
                                <div class="uncode_text_column" style="overflow-wrap: anywhere;">
                                    <p>'.
                                        self::languageChecker(
                                            <<<PL
                                                Obsługa techniczna<br><a href="mailto:konsultanttechniczny@warsawexpo.eu">konsultanttechniczny<span style="display:block;">@warsawexpo.eu</span></a>
                                            PL,
                                            <<<EN
                                                Technical service<br><a href="mailto:konsultanttechniczny@warsawexpo.eu">konsultanttechniczny<span style="display:block;">@warsawexpo.eu</span></a>
                                            EN
                                        )
                                    .'</p>
                                </div>
                            </div>';
            }
        $output .= '</div>';

    return $output;
    }
}