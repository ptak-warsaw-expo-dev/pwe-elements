<?php

/**
 * Class PWEMassVipSender
 *
 * This class add html of mass generator for exhibitors
 */
class PWEMassVipSender extends PWEExhibitorGenerator {

    /**
     * Constructor method.
     * Calls parent constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output.
     * Creating modal form to upload file with visitors data
     *
     * @param array @atts options
     * @return string html output
     */
    public static function senderFlowChecker() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mass_exhibitors_invite_query';
        if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") == $table_name) {
            $count_new = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_name WHERE status = %s",
                    'new'
                )
            );
        } else {
            return true;
        }

        $today_date = new DateTime();
        $fair_start_date = new DateTime(do_shortcode('[trade_fair_datetotimer]'));

        $date_diffarance = $today_date->diff($fair_start_date);

        if($date_diffarance->invert == 0){
            $hours_remaining = ($date_diffarance->days * 24 + $date_diffarance->h) - 34;
            $total_email_capacity = $hours_remaining * 100;

            $canSend = $total_email_capacity - $count_new;

            if($canSend < -2000 || $canSend > 0){
                echo '<script>console.log('.$canSend.')</script>';
            }

            if($total_email_capacity > $count_new){
                return true;
            }
        }
        return false;
    }

    /**
     * Static method to generate the HTML output,
     * Creating modal form to upload file with visitors data,
     *
     * @param array @atts options
     * @return string html output
     */
    public static function output($atts) {
        extract( shortcode_atts( array(
            'phone_field' => '',
        ), $atts ));

        $catalog_array = self::catalog_data();
        $all_exhibitors = reset($catalog_array)['Wystawcy'];

        $output = '';

        // Check if there is more space for email send,
        // 2400 per day until day before starts of the fair minus mails already in queue,
        if(self::senderFlowChecker() || current_user_can('administrator')){
            $output .='
            <div class="modal__element">
                <div class="inner">
                    <span class="btn-close">x</span>';

                    if ($phone_field){
                        $output .='
                        <p style="max-width:90%;">'.
                            PWECommonFunctions::languageChecker(
                                <<<PL
                                Uzupełnij poniżej nazwę firmy zapraszającej oraz wgraj plik (csv, xls, xlsx) z danymi osób, które powinny otrzymać zaproszenia VIP GOLD. Przed wysyłką zweryfikuj zgodność swoich danych z załączonym przez nas przykładem prawidłowego pliku.
                                PL,
                                <<<EN
                                Fill in the name of the inviting company below and upload a file (csv, xls, xlsx) with the data of those who should receive VIP GOLD invitations. Before sending, verify the compatibility of your data with the example of a valid file we have attached.
                                EN
                            )
                        .'</p>';
                    } else {
                        $output .='
                            <p style="max-width:90%;">'.
                                PWECommonFunctions::languageChecker(
                                    <<<PL
                                    Uzupełnij poniżej nazwę firmy zapraszającej oraz wgraj plik (csv, xls, xlsx) z danymi osób, które powinny otrzymać zaproszenia VIP GOLD. Przed wysyłką zweryfikuj zgodność danych.
                                    PL,
                                    <<<EN
                                    Fill in below the name of the inviting company and the details of the people who should receive VIP GOLD invitations. Verify the accuracy of the data before sending.
                                    EN
                                )
                            .'</p>
                        ';
                    }
                    if (!empty(self::$exhibitor_logo_url)){
                        $output .='<img style="max-height: 70px;" src="' . self::$exhibitor_logo_url . '">';
                    }
                    $output .='
                    <input type="text" class="patron" value="">
                    <input type="text" class="company" value="' . self::$exhibitor_name .'" placeholder="'.
                        PWECommonFunctions::languageChecker(
                            <<<PL
                            Firma Zapraszająca (wpisz nazwę swojej firmy)
                            PL,
                            <<<EN
                            Inviting Company (your company's name)
                            EN
                        )
                    .'"></input>
                    <select id="exhibitors_selector__modal">';
                        $output .='<option class="cat-exhibitor" val="" data-id="' . $cat_id . '">Firma Zapraszająca (wybierz z listy)</option>';
                        foreach($all_exhibitors as $cat_id => $cat_value){
                            $output .='<option class="cat-exhibitor" val="' . $cat_value['Nazwa_wystawcy'] . '">' . $cat_value['Nazwa_wystawcy'] . '</option>';
                        }
                        $output .='<option class="cat-exhibitor" val="" data-id="' . $cat_id . '">Patron</option>';
                    $output .='</select>
                    <label class="mass_checkbox_label" style="display:none;">
                        <input type="checkbox" id="mass_exhibitor_badge" name="mass_exhibitor_badge" class="mass_checkbox" >
                        Brak uwzględnienia nazwy firmy na identyfikatorze
                    </label>
                    <div class="file-uloader">
                        <label for="fileUpload">Wybierz plik z danymi</label>
                        <input type="file" id="fileUpload" name="fileUpload" accept=".csv, .xls, .xlsx">
                        <p class="under-label">'.
                        PWECommonFunctions::languageChecker(
                            <<<PL
                            Dozwolone rozszerzenia .csv, .xls, .xlsx;&nbsp;&nbsp;&nbsp; Rozmiar ~1MB &nbsp;
                            PL,
                            <<<EN
                            Allowed extensions: .csv, .xls, .xlsx;&nbsp;&nbsp;&nbsp; Size ~1MB &nbsp;
                            EN
                        )
                        .'</p>
                        <p class="file-size-error error-color"">'.
                            PWECommonFunctions::languageChecker(
                                <<<PL
                                Zbyt duży plik &nbsp;&nbsp;&nbsp;
                                PL,
                                <<<EN
                                File is to big &nbsp;&nbsp;&nbsp;
                                EN
                            )
                        .'

                        </p>
                        <div class="file-size-info">
                            <h5 style="margin-top: 0">
                                '.
                            PWECommonFunctions::languageChecker(
                                <<<PL
                                Jak obniżyć wielkość pliku:</h5>
                                <ul>
                                    <li>Zapisz plik (eksportuj) w formacie CSV</li>
                                    <li>Usuń kolumny poza Imionami oraz Emailami</li>
                                    <li>Użyj darmowego programu (LibreOffice, Open Office ...) do ponownego przetworzenia i zapisania pliku</li>
                                    <li>Podziel plik na mniejsze pliki</li>
                                </ul>
                                PL,
                                <<<EN
                                How to reduce file size:</h5>
                                    <ul>
                                        <li>Save the file (export) in CSV format</li>
                                        <li>Remove columns other than First Names and Emails</li>
                                        <li>Use free software (LibreOffice, Open Office, etc.) to reprocess and save the file</li>
                                        <li>Split the file into smaller files</li>
                                    </ul>
                                EN
                            ). '
                        </div>';
                        if ($phone_field){
                            $output .='
                                <a href="/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/genrator-example.xlsx">
                                    <button class="btn-gen-file">'.
                                        PWECommonFunctions::languageChecker(
                                            <<<PL
                                            Przykładowy Plik
                                            PL,
                                            <<<EN
                                            Example File
                                            EN
                                        )
                                    .'</button>
                                </a>
                            ';
                        }

                    $output .='
                    </div>
                    <button class="wyslij btn-gold">'.
                        PWECommonFunctions::languageChecker(
                            <<<PL
                            Wyślij
                            PL,
                            <<<EN
                            Send
                            EN
                        )
                    .'</button>
                <div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
            ';
        // Message whene there is no more space in queue
        } else {
            $output .='
            <div class="modal__element">
                <div class="inner">
                    <span class="btn-close">x</span>
                    <h3 style="margin-top: 45px;">'.
                        PWECommonFunctions::languageChecker(
                            <<<PL
                            Przekroczono możliwości wysyłki zbiorczej dla danych targów, po więcej informacji proszę o kontakt pod adresem:
                            PL,
                            <<<EN
                            We have exceeded the capacity of bulk shipping for the fair data, for more information, please contact me at:
                            EN
                        )
                    .'<a href="mailto:generator.wystawcow@warsawexpo.eu" style="text-decoration:underline; color:blue;">generator.wystawcow@warsawexpo.eu</a></h3>
                    <h3>'.
                        PWECommonFunctions::languageChecker(
                            <<<PL
                            Za utrudnienia przepraszamy
                            PL,
                            <<<EN
                            We apologize for any inconvenience
                            EN
                        )
                    .'</h3>
                </div>
            </div>';
        }

        return $output;
    }
}