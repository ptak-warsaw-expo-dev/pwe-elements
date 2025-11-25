<?php 

/**
 * Class PWElementVoucher
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementTrendsPanel extends PWElements {

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
     * @param array @atts options
     */
    public static function output($atts) {
        $color_link = self::$main2_color;
        $color_link_hover = self::$accent_color;

        $output = '
            <style>
                #PWETrendsPanel .pwe-trends-panel-contact-block p,
                #PWETrendsPanel .pwe-trends-panel-link {
                    margin: 0;
                }
                #PWETrendsPanel .pwe-trends-panel-link a {
                    color: '. $color_link .' !important;
                    transition: .3s ease
                }
                #PWETrendsPanel .pwe-trends-panel-link a:hover {
                    color: '. $color_link_hover .' !important;
                }
            </style>
            
            <div id="PWETrendsPanel" class="pwe-trends-panel">
                <div class="pwe-trends-panel-wrapper">'.
                    self::languageChecker(
                        <<<PL
                        <h4>Panel trendów i prezentacji:</h4>
                        <p>Inicjatywa promocyjna, podczas której każdy nasz wystawca, w cenie wykupionego stoiska może wystąpić podczas prelekcji. Tematyka prezentacji jest dowolna, jednak polecamy, aby była spójna z tematyką targów, a także nawiązywała do nowinek branżowych co zawsze zachęci szersze grono odwiedzających do poznania Twojej firmy! Wszystkie informacje o Państwa prelekcjach będą udostępnione na naszej stronie www.</p>
                        <br>
                        <div class="pwe-trends-panel-contact-block">
                            <p><strong>Jak zgłosić swój udział:</strong></p>
                            <p>Skontaktuj się mailowo z osobą odpowiedzialną za projekt:</p>
                            <p class="pwe-trends-panel-link"><strong><a href="mailto:wydarzenia@warsawexpo.eu">wydarzenia@warsawexpo.eu</a></strong></p>
                            <p class="pwe-trends-panel-link"><strong><a href="tel:+48 506 905 615">+48 506 905 615</a></strong></p>
                        </div>
                        
                        <p><strong>Decyduje kolejność zgłoszeń!</strong></p>
                        <p>Masz ciekawy pomysł, aby zorganizować wydarzenie promocyjne podczas targów? Jesteśmy otwarci, aby wesprzeć organizację ciekawych projektów! Posiadamy profesjonalnie urządzone sale konferencyjne wyposażone między innymi w scenę, budki dla tłumaczy, VIP roomy oraz fantastyczne nagłośnienie. Do Państwa dyspozycji jest cały wachlarz wyposażenia dodatkowego darmowy parking zewnętrzny, pomieszczenia do samodzielnej adaptacji i wsparcie techniczne od zespołu Ptak Warsaw Expo.</p>
                        <p>W razie dodatkowych pytań zapraszamy do kontaktu z działem marketingu:</p>
                        <p class="pwe-trends-panel-link"><strong><a href="mailto:konsultantmarketingowy@warsawexpo.eu" style="display:flex; flex-wrap:wrap;"><span style="display:block;">konsultantmarketingowy</span><span style="display:block;">@warsawexpo.eu</span></a></strong></p>
                        PL,
                        <<<EN
                        <h4>Trends and presentation panel:</strong></h4>
                        <p>A promotional initiative during which each of our exhibitors can give a lecture at the price of the purchased stand. The topic of the presentation is free, but we recommend that it be consistent with the theme of the fair and refer to industry news, which will always encourage a wider group of visitors to get to know your company! All information about your lectures will be made available on our website.</p>
                        <br>
                        <div class="pwe-trends-panel-contact-block">
                            <p><strong>How to register:</strong></p>
                            <p>Contact the person responsible for the project by e-mail:</p>
                            <p class="pwe-trends-panel-link"><strong><a href="mailto:wydarzenia@warsawexpo.eu">wydarzenia@warsawexpo.eu</a></strong></p>
                            <p class="pwe-trends-panel-link"><strong><a href="tel:+48 506 905 615">+48 506 905 615</a></strong></p>
                        </div>

                        <p><strong>The order of applications is decisive!</strong></p>

                        <p>Do you have an interesting idea to organize a promotional event during the fair? We are open to supporting the organization of interesting projects! We have professionally furnished conference rooms equipped with, among others, a stage, interpreter booths, VIP rooms and a fantastic sound system. At your disposal is a wide range of additional equipment, free outdoor parking, rooms for self-adaptation and technical support from the Ptak Warsaw Expo team.</p>

                        <p>If you have any additional questions, please contact the marketing department:</p>
                        <p class="pwe-trends-panel-link"><strong><a href="mailto:konsultantmarketingowy@warsawexpo.eu" style="display:flex; flex-wrap:wrap;"><span style="display:block;">konsultantmarketingowy</span><span style="display:block;">@warsawexpo.eu</span></a></strong></p>
                        EN
                    )
                .'</div>
            </div>';
        
        return $output;
    }
}