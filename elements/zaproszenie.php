<?php 

/**
 * Class PWElementInvite
 * Extends PWElements class and defines a custom Visual Composer element for inivitation print.
 */
class PWElementInvite extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        require_once plugin_dir_path(__FILE__) . '../assets/tcpdf/tcpdf.php';
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
                    'group' => 'PWE Element',
                    'heading' => __('Data Wieczory Branżowego', 'pwelement'),
                    'param_name' => 'wieczor',
                    'value' => '',
                    'dependency' => array(
                        'element' => 'pwe_element',
                        'value' => 'PWElementInvite',
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'group' => 'PWE Element',
                    'heading' => __('Osoba odpowiedzialna za wieczór', 'pwelement'),
                    'param_name' => 'opdowiedzialna',
                    'value' => '',
                    'dependency' => array(
                        'element' => 'pwe_element',
                        'value' => 'PWElementInvite',
                    ),
                )
            );
        return $element_output;
    }

    /**
     * Static method to generate pdf.
     * Returns object.
     * 
     * @return object
     */
    private static function generate($htmlhead_to_pdf, $htmlcont_to_pdf) {
       
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont('dejavusans');

        $pdf->SetFont('dejavusans', '', 14, '', true);

        $pdf->SetMargins(PDF_MARGIN_LEFT-17, PDF_MARGIN_TOP-29, PDF_MARGIN_RIGHT-16, PDF_MARGIN_BOTTOM-150);

        $pdf->SetAutoPageBreak(false, 0);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->setHtmlVSpace(array(
            'div' => array(
                0 => array('h'=>0,'n'=>0),
                1 => array('h'=>0,'n'=>0),
            ),
            'p' => array(
                0 => array('h'=>0,'n'=>0),
                1 => array('h'=>0,'n'=>0)
            ),
        ));

        
        $pdf->AddPage();

        $background_image = K_PATH_IMAGES.'zap_ramka.png';

        $bMargin = $pdf->getBreakMargin();
        $auto_page_break = $pdf->getAutoPageBreak();

        $pdf->Image($background_image, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

        $pdf->setPageMark();

        $pdf->WriteHTML($htmlhead_to_pdf, true, false, true, false, '');

        $pdf->SetMargins(PDF_MARGIN_LEFT-5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT+5, PDF_MARGIN_BOTTOM);

        $pdf->WriteHTML($htmlcont_to_pdf, true, false, true, false, '');

        $pdf_url_path = plugin_dir_path(__FILE__) . '../pdf/zaprosznie.pdf';
        
        //Close and output PDF document
        $pdf->Output($pdf_url_path, 'F');
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     */
    public static function output($atts) {
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';
        $btn_text_color = 'color:' . self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important; border-width: 0 !important;';
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important;';
        $btn_shadow_color = 'box-shadow: 9px 9px 0px -5px ' . self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black') . '!important;';
        $btn_border = 'border: 1px solid ' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important;';
        
        $wieczor = ($atts['wieczor'] != '') ? $atts['wieczor'] : substr(do_shortcode("[trade_fair_datetotimer]"),8 ,2) . '.' . substr(do_shortcode("[trade_fair_datetotimer]"),5 ,2)  . '.' . substr(do_shortcode("[trade_fair_datetotimer]"),0 ,4);

        $opdowiedzialna = ($atts['opdowiedzialna'] != '') ? $atts['opdowiedzialna'] : 'jowita.zieba@warswexpo.eu';

        if (isset($_POST['input1']) && isset($_FILES['input2'])) {


            //var_dump($_FILES);
            $upload = wp_upload_bits('nazwa_pliku.jpg', null, file_get_contents($_FILES['input2']['tmp_name']));

            $htmlhead_to_pdf = '
                <div style="width:1200px; height:1750px;">
                    <img style="border-bottom: 4px solid goldenrod;" src="/wp-content/plugins/pwe-media/media/zap_head.jpg">
                </div>';

            $htmlcont_to_pdf = '
                <div style="width:1200px; height:1750px; text-align:center;">
                <br><br><br><br>
                    <div style="padding-top: 100px; width: 600px; display:flex; align-items: center; justify-content: center; gap: 40px;">
                        <img style="width: 60px; height:60px;" src="/wp-content/plugins/pwe-media/media/logo_pwe_black.png">
                        <span style="font-size: 50px; color: goldenrod; font-weight: 600; align-self:middle;">&</span>
                        <img style="widht: 0; height:70px; " src="' . $upload['url'] . '">
                    </div>
                    <br>
                    <p style="width:200px; font-size: 16px; text-align: center;">
                        <strong> ' . $_POST['input1'] . ' </strong><br>
                        w imieniu swoim i Ptak Warsaw Expo ma zaszczyt zaprosić Państwa na:
                    </p>
                    <br><br>
                    <img style="width:0; height:80px;" src="/doc/logo-color.png">
                    <br><br>
                    <p style="width:600px; font-size: 16px; text-align: center;">
                        <strong> ' . do_shortcode("[trade_fair_desc]") . ' </strong>
                    </p>
                    <p style="width:600px; font-size: 16px; text-align: center;">
                    które odbędą się w dniach: 
                    <strong> ' . do_shortcode("[trade_fair_date]") . ' </strong>
                    <br>
                    w Ptak Warsaw Expo
                    </p>
                    <br><br><br>
                    <p style="width:600px; font-size: 16px; text-align: center;">
                    Wieczór Branżowy który odbędzie się w dniu:
                    <br>
                    <strong> ' . $wieczor . '</strong>
                    , o godz. <strong>20:00</strong>
                    </p>
                    <br>
                    <img style="width:0; height: 80px" src="/wp-content/plugins/pwe-media/media/logo_sen.png">
                    <br>
                    <p style="width:600px; font-size: 16px; text-align: center; line-height:3">
                        ul. Wioślarska 6, 00-411 Warszawa
                    </p>
                    <p style="width:600px; font-size: 12px; text-align: center;">
                        Aby wziąć udział w targach, prosimy o rejestrację na stronie internetowej wydarzenia.
                        <br>
                        Zaproszenie upoważnia <strong>2 osoby</strong> do wejścia na Wieczór Branżowy.
                        <br>
                        Do dnia <strong>' . substr(do_shortcode("[trade_fair_1stbuildday]"),0 ,10) . '</strong> prosimy o potwierdzenie swojego udziału w wieczorze baranżowym
                        <br>
                        na adres <strong>' . $opdowiedzialna . '</strong>
                    </p>
                </div>
            ';

            self::generate($htmlhead_to_pdf, $htmlcont_to_pdf);

            $output = '
            <script>
                window.location.href = "/wp-content/plugins/PWElements/pdf/zaprosznie.pdf";
            </script>';

            return $output;
        } else {
            $output = '
                <style>
                    .pwelement_'.self::$rnd_id.' .pwe-btn {
                        '. $btn_text_color
                        . $btn_color
                        . $btn_shadow_color
                        . $btn_border .'
                    }
                    .pwelement_'.self::$rnd_id.' .pwe-btn:hover {
                        color: #000000 !important;
                        background-color: #ffffff !important;
                        border: 1px solid #000000 !important;
                    }
                    .pwelement_'. self::$rnd_id .' input {
                        width: 40%;
                        margin-bottom: 18px;
                    }
                </style>

                <div id="PWEInvite" class="pwe-container-invite">
                <h2> Wygeneruj zaproszenie na Wieczór Branżowy.</h2>
                <br>
                <form id="inv_form" method="post" action="" enctype="multipart/form-data">
                    <label>Nazwa firmy która wyświetli się na zaproszeniu</label>
                    <input type="text" name="input1" maxlength="100">
                    <label>Logotyp firmy</label>
                    <input type="file" name="input2" accept="image/*">
                    <button class="btn pwe-btn" name="submit" id="submit">Wygeneruj</button>
                </form>
                </div>
            ';

            return $output;
        }
    }
}
