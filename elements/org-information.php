<?php
/**
* Class PWElementOrgInfo
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementOrgInfo extends PWElements {

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
        $text_color = 'color:' . self::findColor($atts["text_color_manual_hidden"], $atts["text_color"], "black") . '!important;';
        $text_color_shadow = self::findColor($atts["text_shadow_color_manual_hidden"], $atts["text_shadow_color"], "white");
        
        $output = '';

        $output .= '
            <style>
                .row-parent:has(.pwelement_'. self::$rnd_id .' #orgInfo) {
                    max-width: 100%;
                    padding: 0 !important;
                }

                .pwelement_'. self::$rnd_id .' .orgInfo-header-text{
                    ' . $text_color . '
                    text-shadow: 2px 2px ' . $text_color_shadow . ';
                }
                .row-container:has(.pwe-container-org-info) .row-parent {
                    padding: 0 !important;
                }
                .pwe-container-org-info a {
                    font-weight: 600;
                    color: blue;
                }
                .pwe-org-info-header {
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: cover;
                }
                .pwe-org-info-header h1 {
                    text-align: center;
                    padding: 100px 18px;
                    margin: 0 auto;
                    max-width: 1200px;
                }
                .pwe-org-info-header h1 span {
                    font-size: 54px;
                }
                @media (min-width: 300px) and (max-width: 1200px) {
                    .pwe-org-info-header h1 span {
                        font-size: calc(24px + (54 - 24) * ( (100vw - 300px) / (1200 - 300) ));
                    }
                }
                .pwe-org-info-fixed-width {
                    margin: 0 auto;
                    max-width: 1200px;
                }
                #dane-kontaktowe, #wazne-informacje, #procedury-stoisk, #rozladunek, #dokumenty,
                #dane-kontaktowe_en, #wazne-informacje_en, #procedury-stoisk_en, #rozladunek_en, #dokumenty_en {
                    scroll-margin: 90px;
                }
                .pwe-container-org-info a{
                    text-decoration: underline;
                }';


        if(self::isTradeDateExist() == false){
            $output .= '
            .no-data_info {
                display: none;
            }
            .no-data_remove {
                display: block;
            }';
        } else {
            $output .= '
            .no-data_info {
                display: block;
            }
            .no-data_remove {
                display: none;
            }';
        };
        
        $output .= '
            </style>

            <div id="orgInfo" class="pwe-container-org-info">
                <div class="pwe-org-info-header pwe-kv-bg" style="background-image: url(' . self::findBestFile("/doc/background") . ');">
                    <h1 class="orgInfo-header-text">
                        <span>'.
                            self::languageChecker('Informacje organizacyjne dla Wystawców', 'Organizational information for Exhibitors')
                        .'</span>
                    </h1>
                </div>'.
                self::languageChecker(
                    '
                        <div class="pwe-org-info-fixed-width single-block-padding">
                            <div class="pwe-org-info-content-item">
                                <ol>
                                    <a href="#dane-kontaktowe"><li>Dane kontaktowe, terminy montaży, demontaży i godzin otwarcia targów.</li></a>
                                    <a href="#wazne-informacje"><li>Ważne informacje techniczne PTAK WARSAW EXPO (PWE): podłączenia prądu, wody, Internetu, dopuszczalne obciążenie powierzchni ekspozycyjnej, maksymalna masa elementów podwieszanych, maksymalna wysokość zabudowy.</li></a>
                                    <a href="#procedury-stoisk"><li>Informacje dotyczące procedur odbiorów stoisk, powierzchni oraz zasad bezpieczeństwa na terenie PWE.</li></a>
                                    <a href="#rozladunek"><li>Informacje na temat rozładunku, przesyłek kurierskich i spedycji w PWE.</li></a>
                                    <a href="#dokumenty"><li>Dokumenty do pobrania.</li></a>
                                </ol>
                            </div>
                            
                            <div id="dane-kontaktowe" class="pwe-org-info-content-item">
                                <h2>1. Dane kontaktowe, terminy montaży, demontaży i godzin otwarcia targów.</h2>
                                <div class="pwe-org-info-block-dates no-data_remove">
                                    <p style="display: flex; flex-wrap: wrap; gap: 5px;">Obsługa Wystawców: <a style="display: flex; flex-wrap: wrap;" href="mailto:konsultanttechniczny@warsawexpo.eu"><span style="display:block;"> konsultanttechniczny</span><span style="display:block;">@warsawexpo.eu</span></a></p>
                                    <h5>Montaż stoisk:</h5>
                                    <ul>
                                        <li>z zabudową indywidualną: [trade_fair_1stbuildday], [trade_fair_2ndbuildday]</li>
                                        <li>z zabudową realizowaną przez Ptak Warsaw Expo: [trade_fair_2ndbuildday]</li>
                                    </ul>
                                    <h5>Demontaż stoiska:</h5>
                                    <ul>
                                        <li>[trade_fair_1stdismantlday]</li>
                                        <li>[trade_fair_2nddismantlday]</li>
                                    </ul>
                                    <h5>Godziny otwarcia Targów [trade_fair_date]:</h5>
                                    <ul>
                                        <li>Dla Wystawców – 8:00 – 18:00</li>
                                        <li>Dla Odwiedzających – 10:00 – 17:00</li>
                                    </ul>
                                </div>
                                <h4 id="pweHiddenParagraphPl" class="pwe-hidden-paragraph no-data_info">Wszystkie szczegóły pojawią się wkrótce</h4>
                            </div>

                            <div id="wazne-informacje" class="pwe-org-info-content-item">
                                <h2>2. Ważne informacje techniczne PTAK WARSAW EXPO (PWE): podłączenia prądu, wody, Internetu, dopuszczalne obciążenie powierzchni ekspozycyjnej, maksymalna masa elementów podwieszanych, maksymalna wysokość zabudowy</h2>
                                <h5>Podłączenia elektryczne:</h5>
                                <p>
                                Wystawca ma obowiązek zamówić odpowiednią moc przyłączy elektrycznych, niezbędną do zasilenia wszystkich urządzeń elektrycznych wykorzystywanych na stoisku.
                                <br>
                                Suma mocy urządzeń i oświetlenia na stoisku będzie podstawą do zapewnienia odpowiedniej mocy przyłączy.
                                </p>
                                <h5>Podłączenia wodne:</h5>
                                <p>Wystawca ma obowiązek zamówić podłączenia wodne w ilości odpowiadającej ilości podłączonych maszyn i urządzeń na stoisku. Zabronione jest podłączanie więcej niż jednego urządzenia lub zlewu do jednego przyłącza wodnego (dotyczy zarówno dopływu jak i odpływu). Niedopuszczalne jest stosowanie rozdzielni i rozgałęzień na kilka urządzeń.</p>
                                <h3>Charakterystyka podłączeń na terenie PWE:</h3>
                                <h5>Podłączenia elektryczne:</h5>
                                <ul>
                                    <li>dostarczamy w przedziałach mocy (w kWh) określonych w formularzu zamówień Organizatora</li>
                                    <li>prąd trójfazowy dostarczany jest od 9 kW</li>
                                    <li>wystawcy zamawiający prąd trójfazowy proszeni są o określenie rodzaju gniazda (16 A, 32 A, 63 A) oraz poboru mocy maszyny/urządzenia podłączanego na stoisku, Wystawca/podwykonawca zabudowy zobowiązany jest do dokładnego określenia na projekcie miejsca wyprowadzenia poszczególnych przyłączy.</li>
                                </ul>
                                <h5>Podłączenia wodne:</h5>
                                <ul>
                                    <li>dopływ – średnica przewodu 0,5 cala, zakończony zaworkiem</li>
                                    <li>odpływ zlew – przewód 1 cal</li>
                                    <li>odpływ urządzenia/maszyny – przewód PCV, średnica 2 cale</li>
                                </ul>
                                <p>
                                    <strong>Podłączenia Internetu</strong> – podłączenie kablowe lub Wi-Fi (w zależności od potrzeb Wystawcy)
                                    <br>
                                    Jedno podłączenie Internetu jest przypisane tylko do jednego komputera.
                                </p>
                                <h4>MAKSYMALNE OBCIĄŻENIE 1 m2 POWIERZCHNI WYSTAWIENNICZEJ: 2,5 T/m2 I KANAŁÓW MEDIALNYCH: 500 kg.</h4>
                                <h4>DOPUSZCZALNA MAKSYMALNA MASA ELEMENTÓW PODWIESZANYCH: 50 kg/1 punkt*</h4>
                                <p>* Wszelkiego rodzaju podwieszenia są każdorazowo uzgadniane z konsultantem technicznym. Zawieszenie elementu klienta do stropu Hali jest możliwe tylko po przekazaniu wymaganej dokumentacji (oświadczenie dotyczące podwieszania elementów, podkład stoiska wraz z projektem i zaznaczonymi miejscami podwieszeń, dokumenty atesty/producent dotyczące konstrukcji Trilock/Quadrolock ewentualnie innej, czy własnej konstrukcji do którego będą podwieszane elementy - nie później niż na 40 dni przed rozpoczęciem imprezy oraz po uzyskaniu zgody Organizatora.</p>
                                <p><strong>MAKSYMALNA WYSOKOŚĆ ZABUDOWY STOISK INDYWIDUALNYCH:</strong> zgodnie z planem technicznym stoiska otrzymanym od Organizatora.</p>
                            </div>

                            <div id="procedury-stoisk" class="pwe-org-info-content-item">
                                <h2>3. Informacje dotyczące procedur odbiorów stoisk, powierzchni oraz zasad bezpieczeństwa na terenie PWE</h2>
                                <p><strong>Miejscem odbioru stoisk jest</strong> Punkt Technicznej Obsługi Wystawców zlokalizowane na terenie PWE.</p>
                                <p>Zamówienia dodatkowe lub zmiany w zabudowie stoisk wykonanych, będą realizowane w miarę możliwości, w dniu odbioru stoisk.</p>
                                <p>Aranżacja stoisk przez Wystawców odbywa się w dniu odbioru stoisk.</p>
                                <p>Zgodnie z regulaminem targów, do dekoracji stoiska mogą być stosowane wyłącznie materiały niepalne lub zabezpieczone środkami ognioochronnymi do stopnia trudnozapalności.</p>
                                <p>Materiały impregnowane przeciwogniowo muszą posiadać aktualny atest przeciwpożarowy.</p>
                                <p>Wystawca zobowiązany jest przedstawić w Punkcie Technicznej Obsługi Wystawców aktualne atesty potwierdzające stopień trudnozapalności, niepalności użytych materiałów.</p>
                                <p>Na terenie PWE obowiązuje całkowity zakaz palenia, wnoszenia i stosowania butli gazowych, również typu turystycznego.</p>
                                <p>Dekoracje użyte na stoisku nie mogą uszkodzić ścian i podłogi PWE, dlatego zabronione jest opieranie o ściany elementów metalowych, szklanych lub mebli bez odpowiedniego zabezpieczenia.</p>
                                <p>Wystawców dekorujących samodzielnie stoiska obowiązuje zakaz wbijania, wkręcania gwoździ, śrub i pinezek w ściany.</p>
                                <p>W przypadku uszkodzenia elementów zabudowy stoiska Wystawca będzie obciążony kosztami wymiany lub naprawy uszkodzonego elementu.</p>
                                <h3>Pozostawianie elementów wystawcy na stoisku:</h3>
                                <p>Zgodnie z regulaminem Targów, wywóz eksponatów oraz likwidacja ekspozycji na stoisku przed zakończeniem Targów są zabronione.</p>
                                <p>Demontaż elementów stoiska odbywa się w godzinach określonych przez Organizatora. Wystawcy zobowiązani są do usunięcia wszelkich dekoracji użytych na stoisku (eksponaty, tablice reklamowe, bannery, plakaty wraz z taśmą dwustronną, kolorowa folia na modułach zabudowy) oraz przywrócić zajmowaną powierzchnię wystawienniczą (stoisko) do stanu z dnia przekazania przez Organizatora.</p>
                                <h4>UWAGA!!!</h4>
                                <p>W przypadku uszkodzenia modułów stoiska lub pozostawienia na stoisku elementów dekoracji (grafiki), Wystawca będzie obciążony kosztami renowacji lub czyszczenia modułów stoiska.</p>
                                <p>
                                Organizator nie ponosi jakiejkolwiek odpowiedzialności za zniszczenie lub zaginięcie mienia Wystawcy nieusuniętego w terminie z terenu Targów przez Wystawcę.
                                <br>
                                Na terenie PWE obowiązuje całkowity zakaz używania otwartego ognia, maszyn typu szlifierka czy spawarka, jak również narzędzi pylących bez odkurzacza wyciągającego.
                                </p>
                                <p><strong>Miejscem odbioru powierzchni</strong> wraz z zamówionymi przyłączami jest Punkt Technicznej Obsługi Wystawców zlokalizowane na terenie PWE.</p>
                                <p>Odbiór powierzchni wraz z podłączeniami mediów pod zabudowę stoiska odbywa się w dniu rozpoczęcia montażu na podstawie <a href="https://warsawexpo.eu/docs/Protokol-odbioru-powierzchni-wystawowej.pdf" target="_blank" rel="noopener"><strong>Protokołu odbioru powierzchni wystawowej</strong></a></p>
                                <p>Osoba odbierająca powierzchnię jeśli nie jest to Wystawca (np. wykonawca zabudowy) powinien posiadać podpisane przez Wystawcę oryginał upoważnienia podpisanego i opieczętowanego przez Wystawcę. Po przyjeździe na montaż i targi, prosimy o zgłoszenie się do Biura Wystawców w celu odebrania identyfikatorów i odbioru stoiska. Na czas montażu wydajemy naklejki „MONTAŻ” a na czas targów identyfikator „WYSTAWCA”. IDENTYFIKATORY SĄ WYDAWANE TYLKO W BIURZE WYSTAWCÓW. Każda z osób przygotowujących i obsługujących stoisko ma obowiązek noszenia identyfikatora (w widocznym miejscu) przez cały czas trwania montażu i targów.
                                Identyfikatory „WYSTAWCA” będą wydawane najwcześniej w przeddzień otwarcia targów.
                                W celu sprawnego wydawania identyfikatorów, zalecamy wypełnienie danych w generatorze.</p>
                                <p>Identyfikatory techniczne ważne są tylko w trakcie montażu i demontażu i nie upoważniają do wstępu na Targi w trakcie trwania imprezy.</p>
                                <p>Stoiska, ich wyposażenie i dekoracja muszą być wykonane wyłącznie z materiałów niepalnych lub zabezpieczone środkami ognioochronnymi o odpowiednim stopniu trudnozapalności. Wystawca zobowiązany jest przedstawić Organizatorowi stosowny atest potwierdzający stopień palności materiałów.</p>
                                <p><strong>Skan lub kserokopię atestów należy przesłać do Organizatora wraz z projektem architektonicznym stoiska na 40 dni przed rozpoczęciem Targów.</strong></p>
                                <p>
                                Wykonawca zabudowy stoiska zobligowany jest do usunięcia ze stoiska oraz ciągów komunikacyjnych wszelkich odpadów, i elementów pozostałych po budowie.
                                <br>
                                Wykonawca zabudowy jest zobligowany do wykończenia lub wysłonięcia, każdej ściany przylegającej do sąsiedniego stoiska i będącej wyższą niż 2,4 m, w sposób estetyczny, w kolorze białym. Wysłona powinna być wykonana z materiałów nieprzeźroczystych.
                                </p>
                                <p>
                                Wystawca ponosi całkowitą odpowiedzialność za ewentualne straty lub uszkodzenia wynikłe z niewłaściwego użytkowania powierzchni oraz pomieszczeń i urządzeń PWE przez pracowników własnych lub firmy wykonującej zabudowę stoiska Wystawcy.
                                <br>
                                Pracownicy wykonujący instalacje elektryczne muszą posiadać aktualne zaświadczenia SEP w zakresie eksploatacji urządzeń elektrycznych.
                                </p>
                                <h5>Wystawca dokonujący zabudowy powierzchni wystawienniczej samodzielnie lub za pośrednictwem wynajętej firmy zewnętrznej zobowiązany jest:</h5>
                                <ul>
                                    <li>podać nazwę i adres firmy wykonującej zabudowę stoiska,</li>
                                    <li>upoważnić wykonawcę zabudowy stoiska do protokolarnego przejęcia powierzchni wystawienniczej pod wykonanie zabudowy targowej,</li>
                                    <li><strong>przekazać projekt architektoniczny, elektryczny i wodno-kanalizacyjny</strong> stoiska do zatwierdzenia przez Organizatora na <strong>40 dni</strong> przed rozpoczęciem targów,</li>
                                    <li>uzyskać zgodę na wykonanie ww. instalacji technicznych.</li>
                                    <li>przesłać upoważnienie oraz oświadczenie wykonawcy, wraz z atestami potwierdzającymi, że użyte materiały do budowy stoiska są niepalne</li>
                                    <li>przesłać pełną dokumentację dotyczącą podwieszanych konstrukcji</li>
                                </ul>
                                <h5>Demontaż stoisk targowych budowanych samodzielnie</h5>
                                <p>Zgodnie z regulaminem Targów, wywóz eksponatów oraz likwidacja ekspozycji na stoisku, przed zakończeniem Targów jest zabronione.</p>
                                <p>Demontaż elementów stoiska odbywa się w godzinach określonych przez Organizatora. Wystawcy zobowiązani są do usunięcia wszelkich dekoracji użytych na stoisku (eksponaty, tablice reklamowe, bannery, plakaty wraz z taśmą dwustronną, kolorowa folia na modułach zabudowy) oraz przywrócić zajmowaną powierzchnię wystawienniczą do stanu z dnia przekazania przez Organizatora.</p>
                                <p>Demontaż stoiska musi być zakończony nie później niż do końca wyznaczonego terminu przez Organizatora. Niedotrzymanie terminów będzie wiązało się z obciążeniem Wystawcy dodatkowymi kosztami. Po zakończeniu demontażu powierzchnia wystawiennicza powinna być przekazana Organizatorowi w stanie nienaruszonym, odpowiadającym kondycji powierzchni przed montażem stoiska. Z powierzchni muszą być usunięte wszystkie elementy wykorzystane do budowy stoiska, wykładzina oraz taśma samoprzylepna.<br>Wystawca ponosi odpowiedzialność za wszelkie uszkodzenia wynikłe z demontażu przeprowadzonego w nieprawidłowy sposób. Organizator nie ponosi jakiejkolwiek odpowiedzialności za zniszczenie lub zaginięcie mienia Wystawcy nie usuniętego w terminie z terenu Targów przez Wystawcę.
                                Ekipy wchodzące na demontaż muszą posiadać identyfikatory “techniczne” (do odbioru w Punkcie Technicznym Obsługi Wystawców, na terenie PWE).
                                Wjazd samochodów dostawczych i ciężarowych na demontaż możliwy dopiero od godziny zakończenia Targów.</p>
                            </div>

                            <div id="rozladunek" class="pwe-org-info-content-item">
                                <h2>4. Informacje na temat rozładunku, przesyłek kurierskich i spedycji w PWE.</h2>
                                <p>Rozładunek i transport materiałów na terenie PWE WYSTAWCY KORZYSTAJĄCY Z FIRM KURIERSKICH SĄ ZOBOWIĄZANI OSOBIŚCIE ODBIERAĆ PRZESYŁANE PACZKI.</p>
                                <p>Dostawa towarów i eksponatów targowych podczas trwania Targów musi być zakończona co najmniej na 30 minut przed otwarciem Targów, a w pozostałe dni odbywa się w godzinach montażu stoisk.</p>
                                <p>Organizator nie bierze odpowiedzialności za przesyłane Wystawcom paczki, dlatego żadna przesyłka nie zostanie odebrana od firmy kurierskiej w Punkcie Technicznej Obsługi Wystawców.</p>
                                <p><strong>Wyłączność na usługi transportowe</strong> (m.in. poruszanie się po halach targowych wózków widłowych i innych pojazdów transportowych) ma poniższa firma:</p>
                                <h4>NETLOG POLSKA Sp. z o.o.</h4>
                                <p>
                                    Rafał Skrobutan<br>
                                    Koordynator / Coordinator<br>
                                    tel. +48 22 256 70 55<br>
                                    tel. +48 668 890 274<br>
                                    e-mail: rafal.skrobutan@netlog.org.pl<br>
                                    <a href="https://www.netlog.org.pl" target="_blank">www.netlog.org.pl</a>
                                </p>
                                <p><strong>Oficjalny Spedytor Targów wykonuje na zasadzie wyłączności usługi wyładunkowo/załadunkowe na terenie PWE z wykorzystaniem urządzeń mechanicznych tj. wózki widłowe i dźwigowe.</strong> W związku z tym, na terenie PWE obowiązuje zakaz wprowadzania na teren obiektu przez Wystawców i osoby trzecie urządzeń mechanicznych służących do rozładunków, np. dźwigi, wózki widłowe mechaniczne, bez zgody Organizatora lub Oficjalnego Spedytora Targów.</p>
                                <h5>Spedytor świadczy usługi poprzez:</h5>
                                <ul>
                                    <li>organizację transportu eksponatów, kompleksową obsługę celną,</li>
                                    <li>rozładunki i załadunki towarów targowych zgodnie z instrukcjami wysyłającego,</li>
                                    <li>wsparcie wykwalifikowanego personelu przy rozpakowaniu / pakowaniu eksponatów na stoisku,</li>
                                    <li>profesjonalne magazynowanie pustych opakowań, eksponatów oraz materiałów zabudowy stoisk,</li>
                                    <li>wynajem wózków i platform transportowych,</li>
                                </ul>
                            </div>

                            <div id="dokumenty" class="pwe-org-info-content-item">
                                <h2>5. Dokumenty do pobrania</h2>
                                <ul style="list-style-type: lower-latin;">
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Oswiadczenie-wykonawcy-zabudowy-stoiska-zabudowa-indywidualna.pdf"><li>Oświadczenie wykonawcy zabudowy stoiska (zabudowa indywidualna)</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Upowaznienie-dla-wykonawcy-zabudowy-stoiska-zabudowa-indywidualna.pdf"><li>Upoważnienie dla wykonawcy zabudowy stoiska (zabudowa indywidualna)</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Oswiadczenie-dotyczace-podwieszenia-elementow-na-stoisku.pdf"><li>Oświadczenie dotyczące podwieszenia elementów na stoisku</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Protokol-odbioru-powierzchni-wystawowej.pdf"><li>Protokół odbioru powierzchni wystawowej</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Akredytacja-dla-zewnetrznej-firmy-zabudowujacej.pdf"><li>Akredytacja dla zewnętrznej firmy zabudowującej</li></a>
                                </ul>
                            </div>
                        </div>
                    ',
                    '
                        <div class="pwe-org-info-fixed-width single-block-padding">
                            <div class="pwe-org-info-content-item">
                                <ol>
                                    <a class="no-data_remove" href="#dane-kontaktowe_en"><li>Contact information, installation and dismantling deadlines, and trade fair opening hours.</li></a>
                                    <a href="#wazne-informacje_en"><li>PTAK WARSAW EXPO (PWE) important technical information: electricity, water and Internet connections, permissible load on the exhibition area, maximum weight of suspended elements, maximum height of the construction.</li></a>
                                    <a href="#procedury-stoisk_en"><li>Procedures for the approval of stands, surfaces and safety rules on the premises of PWE.</li></a>
                                    <a href="#rozladunek_en"><li>Information on unloading, courier packages and forwarding in PWE.</li></a>
                                    <a href="#dokumenty_en"><li>Documents for download PL.</li></a>
                                </ol>
                            </div>

                            <div id="dane-kontaktowe_en" class="pwe-org-info-content-item">
                                <h2>1. Contact information, installation and dismantling deadlines, and trade fair opening hours. </h2>

                                <div class="pwe-org-info-block-dates no-data_remove">
                                    <p style="display: flex; flex-wrap: wrap; gap: 5px;">Exhibitors’ support: <a style="display: flex; flex-wrap: wrap;" href="mailto:konsultanttechniczny@warsawexpo.eu"><span style="display:block;"> konsultanttechniczny</span><span style="display:block;">@warsawexpo.eu</span></a></p>
                                    <h5>Stand assembly:</h5>
                                    <ul>
                                        <li>individual installation: [trade_fair_1stbuildday], [trade_fair_2ndbuildday].</li>
                                        <li>installation by Ptak Warsaw Expo: [trade_fair_2ndbuildday]</li>
                                    </ul>
                                    <h5>Stand disassembly:</h5>
                                    <ul>
                                        <li>[trade_fair_1stdismantlday]</li>
                                        <li>[trade_fair_2nddismantlday]</li>
                                    </ul>
                                    <h5>Exhibition Opening Hours at [trade_fair_date_eng]:</h5>
                                    <ul>
                                        <li>For exhibitors - 8:00 a.m. – 6:00 p.m.</li>
                                        <li>For visitors - 10 a.m. – 5 p.m.</li>
                                    </ul>
                                </div>
                                <h4 id="pweHiddenParagraphEn" class="pwe-hidden-paragraph no-data_info">All details will be available soon</h4>
                            </div>

                            <div id="wazne-informacje_en" class="pwe-org-info-content-item">
                                <h2>2. Important technical information for PTAK WARSAW EXPO (PWE): power, water, Internet connections, allowable load on exhibition space, maximum weight of suspended elements, maximum height of structures.</h2>
                                <h5>Electrical connections:</h5>
                                <p>
                                The exhibitor is obligated to order the appropriate power for electrical connections necessary to supply all electrical devices used at the stand.
                                <br>
                                The total power of devices and lighting at the stand will be the basis for ensuring the appropriate power supply.
                                </p>
                                <h5>Water connections:</h5>
                                <p>The exhibitor is obligated to order water connections corresponding to the number of connected machines and devices at the stand. Connecting more than one device or sink to a single water connection is prohibited (applies to both inflow and outflow). The use of distribution points and branching for multiple devices is not allowed.</p>
                                <h3>Description of connections on the premises of PWE: </h3>
                                <h5>Electrical connections:</h5>
                                <ul>
                                    <li>are delivered within the specified power ranges (in kWh) as determined in the Organizer"s order form,</li>
                                    <li>three-phase power is supplied from 9 kW,</li>
                                    <li>Exhibitors ordering three-phase electricity are requested to specify the type of socket (16 A, 32 A, 63 A) and the power consumption of the machine/device being connected at the stand. The exhibitor/subcontractor responsible for the construction is obliged to accurately determine the location of each connection outlet on the project.</li>
                                </ul>
                                <h5>Water connections:</h5>
                                <ul>
                                    <li>inlet – pipe diameter 0.5 inches, terminated with a valve,</li>
                                    <li>sink drain – 1-inch pipe,</li>
                                    <li>device/machine drainage – PVC pipe, diameter 2 inches.</li>
                                </ul>
                                <p>
                                    <strong>Internet connection:</strong>  wired or Wi-Fi connection (depending on the Exhibitor"s needs).
                                    <br>
                                    One internet connection is assigned to only one computer.
                                </p>
                                <h4>MAXIMUM LOAD of the 1 m2 OF EXHIBITION SPACE: 2.5 T/m2 AND MEDIA CHANNELS: 500 kg.</h4>
                                <h4>MAXIMUM ALLOWABLE WEIGHT OF SUSPENDED ELEMENTS: 50 kg/1 point *</h4>
                                <p>* All types of hangings are always coordinated with the technical consultant. Hanging of the client"s element to the ceiling of the Hall is possible only after submitting the required documentation (declaration regarding the hanging of elements, stand base with a design and marked hanging points, documents attesting to the Trilock/Quadrolock construction or any other, whether proprietary design to which the elements will be attached - no later than 40 days before the start of the event and after obtaining the Organizer"s consent).</p>
                                <p><strong>MAXIMUM HEIGHT OF INDIVIDUAL STAND CONSTRUCTION:</strong> according to the technical plan of the stand received from the Organizer.</p>
                            </div>

                            <div id="procedury-stoisk_en" class="pwe-org-info-content-item">
                                <h2>3. Information regarding the procedures for stand handovers, space allocation, and safety regulations at the PWE premises.</h2>
                                <p><strong>The place of stand pickup is</strong> the Exhibitors" Technical Service Point located on the premises of PWE.</p>
                                <p>Additional orders or changes to the constructed stands will be carried out, if possible, on the day of stand delivery.</p>
                                <p>The arrangement of stands by Exhibitors takes place on the day of stand reception</p>
                                <p>According to the trade fair regulations, only non-flammable materials or materials treated with fire-resistant agents to a degree of difficulty in ignitability are allowed for stand decoration.</p>
                                <p>Fireproof impregnated materials must have a current fireproof certification.</p>
                                <p>The exhibitor is required to present current certificates confirming the level of fire resistance and non-flammability of the materials used at the Exhibitors" Technical Service Point.</p>
                                <p>In the PWE area, smoking, bringing, and using gas cylinders, including the tourist type, are strictly prohibited.</p>
                                <p>The decorations used at the stand cannot damage the walls and floor of PWE, so leaning metal, glass, or furniture elements against the walls without proper protection is prohibited.</p>
                                <p>Exhibitors decorating their stands themselves are prohibited from hammering, screwing nails, screws, and pins into the walls.</p>
                                <p>In case of damage to the components of the stand structure, the Exhibitor will be responsible for the costs of replacing or repairing the damaged component.</p>
                                <h3>Leaving exhibitor items at the stand:</h3>
                                <p>According to the trade fair regulations, the removal of exhibits and the dismantling of the stand before the end of the fair is prohibited.</p>
                                <p>The dismantling of stand elements takes place during hours specified by the Organizer. Exhibitors are obliged to remove all decorations used at the stand (exhibits, advertising boards, banners, posters along with double-sided tape, colored foil on construction modules) and restore the occupied exhibition space (stand) to the condition as of the day handed over by the Organizer.</p>
                                <h4>ATTENTION!!! </h4>
                                <p>In case of damage to the stand modules or leaving decorative elements (graphics) on the stand, the Exhibitor will be responsible for the costs of renovation or cleaning of the stand modules. </p>
                                <p>
                                The organizer is not liable for any damage or loss of property of the Exhibitor not removed from the Trade Fair premises by the Exhibitor within the specified timeframe. 
                                <br>
                                In the PWE area, there is a complete prohibition on using open fire, machinery such as a grinder or welder, as well as dusty tools without a vacuum extractor. 
                                </p>
                                <p>The place of surface pickup along with the ordered connections is the Exhibitors" Technical Service Point located on the premises of the PWE . </p>
                                <p>The acceptance of the surface together with the connections of utilities for the construction of the stand takes place on the day of the assembly commencement based on  <a href="https://warsawexpo.eu/docs/Protokol-odbioru-powierzchni-wystawowej.pdf" target="_blank" rel="noopener"><strong>the Protocol of Acceptance of the exhibition space.</strong></a></p>
                                <p>
                                    Person picking up the area, if not an Exhibitor (e.g., stand contractor), should have the Exhibitor"s original authorization signed and sealed by the Exhibitor. Upon arrival for setup and the exhibition, please report to the Exhibitor Office to collect identifiers and receive the stand. During setup, we issue "SETUP" stickers, and during the exhibition, an "EXHIBITOR" identifier. IDENTIFIERS ARE ISSUED ONLY AT THE EXHIBITOR OFFICE. Each person preparing and managing the stand is required to wear the identifier (in a visible location) throughout the setup and exhibition period.
                                    The "EXHIBITOR" identifiers will be issued no earlier than the day before the opening of the exhibition. To facilitate the efficient issuance of identifiers, we recommend filling in the information in the generator.
                                </p>
                                <p>The technical identifiers are only valid during assembly and disassembly and do not authorize access to the fairgrounds during the event.</p>
                                <p>: Stands, their equipment, and decorations must be made exclusively from non-flammable materials or protected with fire-retardant substances of the appropriate degree of flame resistance. The exhibitor is obliged to present to the Organizer the relevant certificate confirming the flammability rating of the materials.</p>
                                <p><strong>The scan or photocopy of certificates should be sent to the Organizer along with the architectural design of the stand 40 days before the start of the Fair.</strong></p>
                                <p>
                                    The contractor responsible for the construction of the stand is obliged to remove all waste and elements remaining from the construction from the stand and communication pathways.
                                    <br>
                                    The contractor responsible for the installation is obligated to finish or expose, in an aesthetically pleasing manner and in white color, every wall adjacent to the neighboring stand and higher than 2.4 m. The exposure should be made of non-transparent materials.
                                </p>
                                <p>
                                    The exhibitor bears full responsibility for any losses or damages resulting from improper use of the exhibition space, as well as the premises and equipment of the Exhibition and Congress Centre by their own employees or the company responsible for building the exhibitor"s stand.
                                    <br>
                                    Employees performing electrical installations must have current SEP certificates for the operation of electrical devices.
                                </p>
                                <h5>Exhibitor performing stand construction independently or through a hired external company is obligated to:</h5>
                                <ul>
                                    <li>provide the name and address of the company carrying out the stand construction,</li>
                                    <li>authorize the stand construction contractor to formally take over the exhibition space for the construction of the trade fair stand,</li>
                                    <li><strong>submit the architectural, electrical, and plumbing project</strong> of the stand for approval by the Organizer <strong>40 days</strong> before the start of the fair,</li>
                                    <li>obtain approval for the installation of the mentioned technical systems,</li>
                                    <li>submit the authorization and a statement from the contractor, along with certificates confirming that the materials used for stand construction are non-flammable,</li>
                                    <li>submit complete documentation regarding suspended structures.</li>
                                </ul>
                                <h5>Dismantling of self-constructed exhibition stands</h5>
                                <p>According to the Trade Fair regulations, the removal of exhibits and the dismantling of the stand before the end of the Trade Fair is prohibited.</p>
                                <p>The dismantling of stand elements takes place during hours specified by the Organizer. Exhibitors are obliged to remove all decorations used at the stand (exhibits, advertising boards, banners, posters along with double-sided tape, colored foil on construction modules) and restore the occupied exhibition space to the condition it was in on the day handed over by the Organizer.</p>
                                <p>Dismantling of the stand must be completed no later than the end of the specified deadline set by the Organizer. Failure to meet deadlines will result in additional costs for the Exhibitor. After dismantling, the exhibition space should be handed over to the Organizer in an undamaged condition, corresponding to the condition of the space before the stand was assembled. All elements used for stand construction, carpeting, and adhesive tape must be removed from the space. The Exhibitor is responsible for any damages resulting from dismantling conducted improperly. The Organizer assumes no responsibility for the destruction or loss of the Exhibitor"s property not removed from the Fairgrounds by the Exhibitor within the specified timeframe.
                                Teams entering for dismantling must have "technical" identifiers (available for pickup at the Exhibitors" Technical Service Point, on the Fairgrounds).
                                Entry of delivery and heavy-duty vehicles for dismantling is only possible from the time of the Fair"s conclusion.</p>
                            </div>

                            <div id="rozladunek_en" class="pwe-org-info-content-item">
                                <h2>4. Information about unloading, courier shipments, and freight at PWE.</h2>
                                <p>Unloading and transport of materials on the premises of PWE. EXHIBITORS USING COURIER COMPANIES ARE REQUIRED TO PERSONALLY RECEIVE THE DISPATCHED PACKAGES.</p>
                                <p>The delivery of goods and trade show exhibits during the Trade Fair must be completed at least 30 minutes before the opening of the Fair, and on the remaining days, it takes place during the stand setup hours.</p>
                                <p>The organizer does not take responsibility for packages sent to exhibitors, therefore, no shipment will be accepted from the courier company at the Exhibitors" Technical Service Point.</p>
                                <p><strong>Exclusive rights for transport services</strong> (including movement within the trade halls of forklifts and other transport vehicles) are held by the following company:</p>
                                <h4>NETLOG POLSKA Sp. z o.o.</h4>
                                <p>
                                    Rafał Skrobutan<br>
                                    Coordinator<br>
                                    tel. +48 22 256 70 55<br>
                                    mobile: +48 668 890 274<br>
                                    e-mail: rafal.skrobutan@netlog.org.pl<br>
                                    www.netlog.org.pl
                                </p>
                                <p><strong>The official Trade Fair Freight Forwarder performs, on an exclusive basis, loading and unloading services on the premises of the PWE (Polish Exhibition and Congress Center) using mechanical devices such as forklifts and cranes.</strong> In connection with this, a prohibition on the introduction of mechanical devices for unloading, such as cranes and mechanical forklifts, onto the premises by Exhibitors and third parties is in force at the PWE (Exhibition Grounds), without the consent of the Organizer or the Official Fair Freight Forwarder.</p>
                                <h5>The Freight Forwarder provides services through:</h5>
                                <ul>
                                    <li>organization of exhibition transport, comprehensive pwes services,</li>
                                    <li>unloading and loading of trade goods in accordance with the sender"s instructions,</li>
                                    <li>assistance of qualified personnel in unpacking/packing exhibits at the stand,</li>
                                    <li>professional storage of empty packaging, exhibits, and stand construction materials,</li>
                                    <li>rental of forklifts and transport platforms,</li>
                                </ul>

                            </div>

                            <div id="dokumenty_en" class="pwe-org-info-content-item">
                                <h2>5. Documents for download PL.</h2>
                                <ul style="list-style-type: lower-latin;">
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Oswiadczenie-wykonawcy-zabudowy-stoiska-zabudowa-indywidualna.pdf"><li>Oświadczenie wykonawcy zabudowy stoiska (zabudowa indywidualna)</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Upowaznienie-dla-wykonawcy-zabudowy-stoiska-zabudowa-indywidualna.pdf"><li>Upoważnienie dla wykonawcy zabudowy stoiska (zabudowa indywidualna)</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Oswiadczenie-dotyczace-podwieszenia-elementow-na-stoisku.pdf"><li>Oświadczenie dotyczące podwieszenia elementów na stoisku</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Protokol-odbioru-powierzchni-wystawowej.pdf"><li>Protokół odbioru powierzchni wystawowej</li></a>
                                    <a target="_blank" href="https://warsawexpo.eu/docs/Akredytacja-dla-zewnetrznej-firmy-zabudowujacej.pdf"><li>Akredytacja dla zewnętrznej firmy zabudowującej</li></a>
                                </ul>
                            </div>
                        </div>
                    '
                )
            ."</div>";

        return $output;
    }
}