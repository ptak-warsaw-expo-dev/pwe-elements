<?php
$language = strtoupper((string) ($language ?? 'PL'));
$content_pl = <<<HTML
<h4>Panel trendow i prezentacji:</h4>
<p>Inicjatywa promocyjna, podczas ktorej kazdy nasz wystawca, w cenie wykupionego stoiska moze wystapic podczas prelekcji. Tematyka prezentacji jest dowolna, jednak polecamy, aby byla spojna z tematyka targow, a takze nawiazywala do nowinek branzowych co zawsze zacheci szersze grono odwiedzajacych do poznania Twojej firmy! Wszystkie informacje o Panstwa prelekcjach beda udostepnione na naszej stronie www.</p>
<br>
<div class="pwe-trends-panel-contact-block">
    <p><strong>Jak zglosic swoj udzial:</strong></p>
    <p>Skontaktuj sie mailowo z osoba odpowiedzialna za projekt:</p>
    <p class="pwe-trends-panel-link"><strong><a href="mailto:wydarzenia@warsawexpo.eu">wydarzenia@warsawexpo.eu</a></strong></p>
    <p class="pwe-trends-panel-link"><strong><a href="tel:+48506905615">+48 506 905 615</a></strong></p>
</div>
<p><strong>Decyduje kolejnosc zgloszen!</strong></p>
<p>Masz ciekawy pomysl, aby zorganizowac wydarzenie promocyjne podczas targow? Jestesmy otwarci, aby wesprzec organizacje ciekawych projektow! Posiadamy profesjonalnie urzadzone sale konferencyjne wyposazone miedzy innymi w scene, budki dla tlumaczy, VIP roomy oraz fantastyczne naglosnienie.</p>
<p>W razie dodatkowych pytan zapraszamy do kontaktu z dzialem marketingu:</p>
<p class="pwe-trends-panel-link"><strong><a href="mailto:konsultantmarketingowy@warsawexpo.eu" class="pwe-trends-panel-email"><span>konsultantmarketingowy</span><span>@warsawexpo.eu</span></a></strong></p>
HTML;
$content_en = <<<HTML
<h4>Trends and presentation panel:</h4>
<p>A promotional initiative during which each of our exhibitors can give a lecture at the price of the purchased stand. The topic of the presentation is free, but we recommend that it be consistent with the theme of the fair and refer to industry news.</p>
<br>
<div class="pwe-trends-panel-contact-block">
    <p><strong>How to register:</strong></p>
    <p>Contact the person responsible for the project by e-mail:</p>
    <p class="pwe-trends-panel-link"><strong><a href="mailto:wydarzenia@warsawexpo.eu">wydarzenia@warsawexpo.eu</a></strong></p>
    <p class="pwe-trends-panel-link"><strong><a href="tel:+48506905615">+48 506 905 615</a></strong></p>
</div>
<p><strong>The order of applications is decisive!</strong></p>
<p>Do you have an interesting idea to organize a promotional event during the fair? We are open to supporting the organization of interesting projects! We have professionally furnished conference rooms and technical support from the Ptak Warsaw Expo team.</p>
<p>If you have any additional questions, please contact the marketing department:</p>
<p class="pwe-trends-panel-link"><strong><a href="mailto:konsultantmarketingowy@warsawexpo.eu" class="pwe-trends-panel-email"><span>konsultantmarketingowy</span><span>@warsawexpo.eu</span></a></strong></p>
HTML;
?>
<div id="PWEConferenceCapTrendsPanel" class="pwe-trends-panel">
    <div class="pwe-trends-panel-wrapper">
        <?php echo wp_kses_post($language === 'EN' ? $content_en : $content_pl); ?>
    </div>
</div>

