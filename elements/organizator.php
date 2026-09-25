<?php
/**
* Class PWElementOrganizer
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementOrganizer extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
        require_once plugin_dir_path(__FILE__) . 'calendarApple.php';
        require_once plugin_dir_path(__FILE__) . 'calendarGoogle.php';
    }
    
    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important;';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color) . '!important';
        $btn_border = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color) . '!important';
        $border_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white');

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $video_plug = 'https://i.ytimg.com/vi/-RmRpZN1mHA/sddefault.jpg';
        $video_src = 'https://www.youtube.com/embed/-RmRpZN1mHA?autoplay=1';
        $video_iframe_html = '<iframe class="pwe-iframe" src="' . $video_src . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
        $video_default_html = '<div class="pwe-video-default" style="background-image: url(' . $video_plug . ');">
                                    <img src="/wp-content/plugins/pwe-media/media/youtube-button.webp" alt="youtube play button">
                            </div>';

        $output = '';

        $output .= '
        <style>
            .row-parent:has(.pwelement_' . self::$rnd_id . ' #organizator) {
                max-width: 100%;
                padding: 0 !important;
            }
            .custom-organizator-video.desktop {
                height:500px;
                position: relative;
                overflow: hidden;
            }
            .custom-organizator-video.desktop iframe {
                min-width: 180%; 
                height:120vh; 
                position: absolute;
                top:50%;
                left:50%;
                transform: translate(-50%, -50%);
            }
            .custom-organizator-text {
                position: relative;
                padding: 1px 36px 50px 36px !important;
            }
            .pwelement_' . self::$rnd_id . ' :is(.custom-organizator-text, .custom-inner-mobile-text) :is(h5, p) {
                ' . $text_color . '
            }
            .custom-inner-organizator {
                max-width:1200px;
                margin: auto;
            }
            .custom-organizator-header {
                margin:0;
            }
            .pwelement_' . self::$rnd_id . ' .organizator-box-shadow-left {
                margin-left: -18px;
                margin-bottom: -20px;
                box-shadow: -3px -3px ' . $border_color .';
                width: 170px !important;
                height: 40px;
            }
            .pwelement_' . self::$rnd_id . ' .organizator-box-shadow-right {
                margin-right: -18px;
                margin-top: -20px;
                box-shadow: 3px 3px ' . $border_color .';
                width: 170px !important;
                height: 40px;
                float: right;
            }

            .custom-inner-organizator-mobile {
                display: none;
                padding: 36px;
            }
            .custom-organizator-header-mobile {
                display : flex;
                justify-content: space-between;
            }
            .custom-organizator-header-mobile .image-ptak {
                width: 80px;
                border-radius: 10px;
            }
            .custom-inner-organizator-mobile .secondary-heading-text {
                text-align: left;
                
            }
            .custom-inner-organizator-mobile h4 {
                margin: 0 !important;
                color: white;
            }
            .custom-organizator-video.mobile {
                position: relative;
                margin-top: 18px;
            }
            .custom-organizator-video.mobile iframe {
                aspect-ratio: 16 / 9 !important;
                border-radius: 10px;
            }
            .custom-organizator-video .pwe-video-default {
                display: flex;
                justify-content: center;
                align-items: center;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                aspect-ratio: 16 / 9;
                border-radius: 10px;
            }
                    
            .custom-organizator-video .pwe-video-default img {
                max-width: 80px;
                cursor: pointer;
                transition: .3s ease;
                opacity: 0.4;
            }
            .custom-organizator-video .pwe-video-default img:hover {
                transform: scale(1.1);
                opacity: 1;
            }
            .custom-inner-organizator-mobile .pwe-btn {
                color: '. $btn_text_color .';
                background-color: '. $btn_color .';
                border: 1px solid '. $btn_border .';
            }
            .custom-inner-organizator-mobile .pwe-btn:hover {
                color: '. $btn_text_color .';
                background-color: '. $darker_btn_color .'!important;
                border: 1px solid '. $darker_btn_color .'!important;
            }
            .custom-organizator-header-mobile,
            .custom-organizator-video-mobile {
                max-width: 1200px;
            }
            .custom-organizator-video-mobile {
                display: flex;
                gap: 36px;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .custom-organizator-video-mobile .custom-organizator-video,
            .custom-organizator-video-mobile .custom-inner-mobile-text {
                width: 50%;
                max-width: 500px;
            }
            .custom-organizator-video-mobile .custom-inner-mobile-text {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            @media (max-width:1050px){
                .custom-organizator-video-mobile {
                    justify-content: center;
                }
                .custom-organizator-video-mobile .custom-organizator-video,
                .custom-organizator-video-mobile .custom-inner-mobile-text {
                    width: 100%;
                }
            }
            @media (max-width:960px){
                .custom-organizator-video-desktop {
                    display: none;
                }
                .custom-inner-organizator-mobile {
                    display: block;
                }
            }
            @media (max-width:360px){
                .custom-organizator-header-mobile .image-ptak {
                    width: 60px;
                }
            }
        </style>

        <div id="organizator" class="custom-container-organizator style-accent-bg text-centered">

            <div class="custom-organizator-video-desktop">
                <div class="custom-organizator-video desktop">
                    <iframe title="YouTube video player" frameborder="0" marginwidth="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; muted" 
                    data-src="https://www.youtube.com/embed/dZJblpIVrcQ?autoplay=1&mute=1&loop=1&playlist=dZJblpIVrcQ&controls=0&showinfo=0"></iframe>
                </div>
                <div class="custom-organizator-text">
                    <div class="custom-inner-organizator text-left">
                        <p class="organizator-box-shadow-left">&nbsp;</p>
                        <h5 class="custom-organizator-header">'.
                                self::languageChecker(
                                    <<<PL
                                        O organizatorze
                                    PL,
                                    <<<EN
                                        About the organizer
                                    EN
                                )
                        .'</h5>
                        <p>'.
                            self::languageChecker(
                                <<<PL
                                    Ptak Warsaw Expo to przede wszystkim gwarancja doświadczenia. Już od blisko dekady organizujemy ponad 70 imprez rocznie na 143 000 m2 powierzchni wystawienniczej 6 nowoczesnych hal oraz 500 000 m2 powierzchni zewnętrznej. To pozycjonuje nas jako lidera branży targowej, dysponującego najbardziej innowacyjnym zapleczem organizacyjnym.
                                    <br> To jednak nie wszystko. Zdobyte doświadczenie i wysoka jakość aranżowanych wydarzeń przełożyła się na zbudowanie silnej sieci kontaktów biznesowych. Ta gwarantuje uczestnikom poszczególnych eventów dostęp do nowoczesnych rozwiązań - zarówno znanych na polskim rynku, jak i podbijających branże na polu międzynarodowym. To sprawia, że imprezy Ptak Warsaw Expo otwierają odwiedzających na nowe możliwości biznesowe. Dowodem zdobytego zaufania są rekordowe liczby - 1 000 000 odwiedzających i 10 000 wystawców.
                                    <br> Największą siłą Ptak Warsaw Expo jest jednak nasz zespół. To doświadczona grupa pełnych pasji ludzi, która za cel stawia sobie wyjście naprzeciw oczekiwaniom odwiedzających i wystawców. Elastyczne podejście, umiejętność znalezienia odpowiedzi na zmieniające się okoliczności i otwartość na potrzeby uczestników wydarzeń - te cechy sprawiają, że nasze eventy docenia się ze względu na profesjonalną obsługę.
                                    <br> Te wszystkie czynniki składają się na to, że Ptak Warsaw Expo stało się europejską stolicą targów, organizującą niezapomniane imprezy branżowe i komercyjne. Zachęcamy do kontaktu już dziś, aby dowiedzieć się jak konkretnie możemy pomóc osiągnąć twoje cele i sprawić, że firma otworzy się na nowe możliwości biznesowe.
                                PL,
                                <<<EN
                                    Ptak Warsaw Expo is first and foremost a guarantee of experience. For nearly a decade now, we have been organizing more than 70 events a year on 143,000 sqm of exhibition space of 6 modern halls and 500,000 sqm of outdoor space. This positions us as a leader in the trade fair industry with the most innovative organizational facilities.
                                    <br> However, that's not all. The experience we have gained and the high quality of the events we arrange have translated into building a strong network of business contacts. This guarantees the participants of individual events access to modern solutions - both those known on the Polish market and those conquering the industry on the international field. This makes Ptak Warsaw Expo events open visitors to new business opportunities. Proof of the trust gained is the record numbers - 1,000,000 visitors and 10,000 exhibitors.
                                    <br> However, the greatest strength of Ptak Warsaw Expo is our team. It is an experienced group of passionate people who aim to meet the expectations of visitors and exhibitors. Flexible approach, ability to find answers to changing circumstances and openness to the needs of event participants - these qualities make our events appreciated for their professional service.
                                    <br> All these factors contribute to the fact that Ptak Warsaw Expo has become the European capital of trade fairs, organizing unforgettable industry and commercial events. We encourage you to contact us today to find out how specifically we can help you achieve your goals and make your company open to new business opportunities.
                                EN
                            )
                        .'</p>
                        <p class="organizator-box-shadow-right">&nbsp;</p>
                    </div>
                </div>
            </div>

            <div class="custom-inner-organizator-mobile">
                <div class="custom-organizator-header-mobile">
                    <div class="secondary-heading-text">
                        <h4 class="pwe-uppercase">'.
                        self::languageChecker(
                            <<<PL
                                O organizatorze
                            PL,
                            <<<EN
                                Organizer
                            EN
                        )
                        .'</h4>
                    </div>
                    
                    <a href="https://warsawexpo.eu/" target="_blank"><img class="image-ptak" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp"></a> 
                    
                </div>

                <div class="custom-organizator-video-mobile">
                    <div class="custom-organizator-video mobile">'. $video_default_html .'</div>

                    <div class="custom-inner-mobile-text text-left">
                        
                        <p>'.
                            self::languageChecker(
                                <<<PL
                                <strong>PTAK Warsaw Expo, największe centrum targowo-kongresowe</strong> 
                                oraz <strong>lider organizacji targów</strong> w Europie Środkowej, które 
                                organizuje <strong>ponad 100 targów</strong> rocznie przyciągając zarówno 
                                wystawców jak i odwiedzających z całego świata.
                                PL,
                                <<<EN
                                <strong>PTAK Warsaw Expo, the largest trade fair and congress centre</strong>
                                and <strong>the leader in trade fair organisation</strong> in Central Europe, which
                                organises <strong>over 100 trade fairs</strong> annually, attracting both
                                exhibitors and visitors from all over the world.
                                EN
                            )
                        .'</p>
                        <div class="pwe-btn-container">
                            <a class="pwe-link btn pwe-btn" target="_blank" 
                            href="'. 
                            self::languageChecker(
                                <<<PL
                                https://warsawexpo.eu/kalendarz-targowy/
                                PL,
                                <<<EN
                                https://warsawexpo.eu/en/exhibition-calendar/
                                EN
                            )
                            .'"'. 
                            self::languageChecker(
                                <<<PL
                                alt="kalendarz targowy">Zobacz nasze targi
                                PL,
                                <<<EN
                                alt="exhibition calendar">See our fairs
                                EN
                            )
                            .'</a>  
                        </div>
                    </div>
                </div>              
            </div>

        </div>
            
        <script>
            const videoOrganizator = document.querySelector(".custom-organizator-video.mobile");
            videoOrganizator.addEventListener("click", () => {
                videoOrganizator.innerHTML = `<div class="pwe-video-iframe">'. $video_iframe_html .'</div>`;   
            });
        </script>';

        return $output;
    }
}
