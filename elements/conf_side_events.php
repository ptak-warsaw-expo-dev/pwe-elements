<?php
/**
* Class PWElementConfSideEvents
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementConfSideEvents extends PWElements {

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
            // array(
            //     'type' => 'colorpicker',
            //     'group' => 'PWE Element',
            //     'heading' => __('Overlay color', 'pwe_header'),
            //     'param_name' => 'pwe_conference_section_overlay_color',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementConfSideEvents',
            //     ),
            // ),
            // array(
            //     'type' => 'input_range',
            //     'group' => 'PWE Element',
            //     'heading' => __('Overlay opacity', 'pwe_header'),
            //     'param_name' => 'pwe_conference_section_overlay_range',
            //     'value' => '0.80',
            //     'min' => '0',
            //     'max' => '1',
            //     'step' => '0.01',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementConfSideEvents',
            //     ),
            // ),
            // array(
            //     'type' => 'textfield',
            //     'group' => 'PWE Element',
            //     'heading' => __('Title', 'pwelement'),
            //     'param_name' => 'pwe_conference_section_title',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementConfSideEvents',
            //     ),
            // ),
            // array(
            //     'type' => 'textfield',
            //     'group' => 'PWE Element',
            //     'heading' => __('Description', 'pwelement'),
            //     'param_name' => 'pwe_conference_section_desc',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementConfSideEvents',
            //     ),
            // ),
            // array(
            //     'type' => 'textfield',
            //     'group' => 'PWE Element',
            //     'heading' => __('Text', 'pwelement'),
            //     'param_name' => 'pwe_conference_section_text',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementConfSideEvents',
            //     ),
            // ),
            // array(
            //     'type' => 'textfield',
            //     'group' => 'PWE Element',
            //     'heading' => __('Tags', 'pwelement'),
            //     'param_name' => 'pwe_conference_section_tags',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementConfSideEvents',
            //     ),
            // ),
            // array(
            //     'type' => 'textfield',
            //     'group' => 'PWE Element',
            //     'heading' => __('Conference link', 'pwelement'),
            //     'param_name' => 'pwe_conference_section_link',
            //     'save_always' => true,
            //     'dependency' => array(
            //         'element' => 'pwe_element',
            //         'value' => 'PWElementConfSideEvents',
            //     ),
            // ),
            // array(
            //     'type' => 'checkbox',
            //     'group' => 'PWE Element',
            //     'heading' => __('Congress logo color', 'pwe_header'),
            //     'param_name' => 'pwe_conference_section_logo_color',
            //     'description' => __('Add kongres-color.webp', 'pwe_header'),
            //     'save_always' => true,
            //     'value' => array(__('True', 'pwe_header') => 'true',),
            // ),
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
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color);
        $btn_shadow_color = self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black');
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], $btn_color);

        $mobile = preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);

        $output = '
            <style>
                #pweConfSideEvents :is(.conf-side-row, .conf-side-footer){
                    margin-top: 36px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                #pweConfSideEvents .side-event-image{
                    max-width: 35%;
                }
                #pweConfSideEvents .side-event-text{
                    max-width: 60%;
                }
                #pweConfSideEvents .side-event-footer-btn{
                    max-width: 20%;
                    margin: auto;
                }
                #pweConfSideEvents .side-event-footer-text{
                    max-width: 75%;
                }
                #pweConfSideEvents :is(.side-event-text h5, .side-event-heading-text){
                    margin-top: 0;
                    text-transform: uppercase;
                    padding: 0 12px 10px 0;
                    box-shadow: 9px 9px 0px -6px ' . $btn_color . ';
                }
                @media (max-width:569px){
                    #pweConfSideEvents :is(.conf-side-row, .conf-side-footer){
                        flex-direction: column;
                    }
                    #pweConfSideEvents :is(.side-event-image, .side-event-text, .side-event-footer-btn, .side-event-footer-text){
                        margin-top: 9px;
                        max-width: 100%;
                    }
                    #pweConfSideEvents :is(h5, h4){
                        margin: auto;
                    }
                }
            </style>
        ';

        $output .= '
            <div id="pweConfSideEvents">
                <h4 class="side-event-heading-text"> '. 
                    self::languageChecker(
                        <<<PL
                            Zobacz co jeszcze przygotowaliśmy
                        PL,
                        <<<EN
                            See what else we have prepared
                        EN
                    )
                .'</h4>
                <div class="conf-side-row">
                    <div class="side-event-image">
                        <img src="/wp-content/plugins/pwe-media/media/kafelek-medale2.webp">
                    </div>
                    <div class="side-event-text">
                        <h5>'. 
                            self::languageChecker(
                                <<<PL
                                    Uznanie dla naszych wystawców: ceremonia wręczenia medali I dyplomów
                                PL,
                                <<<EN
                                    Celebrating Excellence: Medal Awards Ceremony
                                EN
                            )
                        .'</h5>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    To wydarzenie jest uhonorowaniem najlepszych z najlepszych, podkreślając ich wkład w rozwój branży i prezentację najnowocześniejszych rozwiązań w kategoriach:
                                PL,
                                <<<EN
                                    This event honors the best of the best, highlighting their contributions to the industry’s development and showcasing the most advanced solutions in the following categories:
                                EN
                            )
                        .'</p>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    <strong>Premiera Targowa:</strong> Uznanie dla wystawców, którzy wprowadzili na targach innowacyjne produkty lub usługi, prezentując je po raz pierwszy szerokiej publiczności.
                                PL,
                                <<<EN
                                    <strong>Trade Fair Premiere:</strong> Recognition for exhibitors who introduced innovative products or services at the fair, presenting them to the public for the first time.
                                EN
                            )
                        .'</p>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    <strong>Innowacyjność:</strong> Wyróżnienie dla firm, które zaprezentowały przełomowe technologie lub rozwiązania, które mają potencjał do rewolucjonizowania branży.
                                PL,
                                <<<EN
                                    <strong>Innovation:</strong> An award for companies that have presented groundbreaking technologies or solutions with the potential to revolutionize the industry.
                                EN
                            )
                        .'</p>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    <strong>Ekspozycja Targowa:</strong> Nagroda przyznawana za wyjątkową aranżację i estetykę stoiska, które wyróżnia się kreatywnością i przyciąga uwagę odwiedzających.
                                PL,
                                <<<EN
                                    <strong>Trade Fair Display:</strong> A prize awarded for exceptional booth design and aesthetics that stand out for their creativity and attract visitors’ attention.
                                EN
                            )
                        .'</p>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    <strong>Produkt Targowy:</strong> Nagroda dla wystawców, których produkt wyróżnia się jakością, funkcjonalnością i znaczeniem dla rozwoju branży, zyskując uznanie odwiedzających oraz ekspertów.
                                PL,
                                <<<EN
                                    <strong>Trade Fair Product:</strong> An award for exhibitors whose product stands out for its quality, functionality, and significance for the industry’s development, earning recognition from both visitors and experts.
                                EN
                            )
                        .'</p>
                    </div>
                </div>
                <div class="conf-side-row">';
                    if ($mobile){
                        $output .= '<div class="side-event-image">
                            <img src="/wp-content/plugins/pwe-media/media/'. 
                                self::languageChecker(
                                    <<<PL
                                    kafelek-studio-pl
                                    PL,
                                    <<<EN
                                    kafelek-studio-en
                                    EN
                                )
                            .'.webp">
                        </div>';
                    }
                    $output .= '<div class="side-event-text">
                        <h5>'. 
                            self::languageChecker(
                                <<<PL
                                    Studio targowe
                                PL,
                                <<<EN
                                    The trade fair studio
                                EN
                            )
                        .'</h5>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    Zapraszamy do <strong>Studia Targowego</strong>, gdzie <strong>podczas wyjątkowych wywiadów z ekspertami branży i wybitnymi wystawcami</strong> będzie można poznać najnowsze trendy oraz innowacyjne rozwiązania. W tym inspirującym środowisku liderzy rynku podzielą się swoimi doświadczeniami i wizjami na przyszłość, dostarczając cennych wskazówek i wiedzy. <strong>Studio Targowe</strong> jest również powiązane z <strong>ceremonią wręczenia medali</strong>, które przyznawane są wyróżniającym się wystawcom, co dodaje wydarzeniu prestiżu i podkreśla znaczenie innowacyjności oraz doskonałości w branży.
                                PL,
                                <<<EN
                                    We invite you to the <strong>Trade Fair Studio</strong>, where during exclusive interviews with industry experts and outstanding exhibitors, you will learn about the latest trends and innovative solutions. In this inspiring environment, market leaders will share their experiences and visions for the future, providing valuable insights and knowledge. The <strong>Trade Fair Studio</strong> is also connected with the <strong>Medal Award Ceremony</strong>, where distinguished exhibitors are honored, adding prestige to the event and highlighting the importance of innovation and excellence in the industry..
                                EN
                            )
                        .'</p>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    Dołącz do nas, aby na żywo śledzić fascynujące rozmowy i zyskać wgląd w to, co kształtuje przyszłość branży!
                                PL,
                            )
                        .'</p>
                    </div>';
                    if (!$mobile){
                        $output .= '<div class="side-event-image">
                            <img src="/wp-content/plugins/pwe-media/media/'. 
                                self::languageChecker(
                                    <<<PL
                                    kafelek-studio-pl
                                    PL,
                                    <<<EN
                                    kafelek-studio-en
                                    EN
                                )
                            .'.webp">
                        </div>';
                    }
                $output .= '</div>
                <div class="conf-side-row">
                    <div class="side-event-image">
                        <img src="/wp-content/plugins/pwe-media/media/'. 
                            self::languageChecker(
                                <<<PL
                                kafelek-networking-pl
                                PL,
                                <<<EN
                                kafelek-networking-en
                                EN
                            )
                        .'.webp">
                    </div>
                    <div class="side-event-text">
                        <h5>'. 
                            self::languageChecker(
                                <<<PL
                                    Inspirujące rozmowy I nowe możliwości – strefa networkingu b2b czeka!
                                PL,
                                <<<EN
                                    Inspiring conversations and new opportunities – the networking zone awaits!
                                EN
                            )
                        .'</h5>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    <strong>Strefa Networkingu B2B jest rozwinięciem konferencji</strong>, oferując uczestnikom unikalną okazję do bezpośrednich rozmów z prelegentami i innymi ekspertami branży. W tej strefie uczestnicy będą mogli wymieniać się spostrzeżeniami, zadawać pytania i nawiązywać wartościowe kontakty biznesowe w mniej formalnej atmosferze.
                                PL,
                                <<<EN
                                    <strong>The B2B Networking Zone is an extension of the conference</strong>, offering participants a unique opportunity to engage in direct discussions with speakers and other industry experts. In this zone, attendees can exchange insights, ask questions, and establish valuable business connections in a more informal atmosphere.
                                EN
                            )
                        .'</p>
                        <p>'. 
                            self::languageChecker(
                                <<<PL
                                    Dołącz do nas, aby pogłębić swoją wiedzę, kontynuować inspirujące dyskusje z konferencji i nawiązać cenne relacje, które mogą przynieść korzyści zarówno Tobie, jak i Twojej firmie!
                                PL,
                                <<<EN
                                    Join us to deepen your knowledge, continue the inspiring discussions from the conference, and form valuable relationships that can benefit both you and your company!
                                EN
                            )
                        .'</p>
                    </div>
                </div>
                    '. 
                        self::languageChecker(
                            <<<PL
                                <div class="conf-side-footer">
                                    <div class="side-event-footer-text">
                                        <h5 class="side-event-heading-text">Chcesz współtworzyć program merytoryczny? Zobacz naszą ofertę i skontaktuj się z nami!</h5>
                                    </div>
                                    <a href="/wp-content/plugins/pwe-media/media/oferta-konferencje.pdf" target="blank" class="btn btn-accent custom-link btn-flat side-event-footer-btn" >Pobierz ofertę</a>
                                </div>
                            PL,
                        )
                    .'
            </div>
        ';

        return $output;
    }
}