<?php

function render_gr1($atts, $all_exhibitors, $pweGeneratorWebsite){
    extract( shortcode_atts( array(
        'generator_form_id' => '',
        'exhibitor_generator_html_text' => '',
        'generator_catalog' => '',
        'generator_patron' => '',
    ), $atts ));

    $output = '';
    $output .= '
        <div class="exhibitor-generator gr1" data-group="gr1">
            <div class="exhibitor-generator__wrapper">
                <div class="exhibitor-generator__left"></div>
                <div class="exhibitor-generator__right">
                    <div class="exhibitor-generator__right-wrapper">
                        <div class="exhibitor-generator__right-title">
                            <h3>' . PWECommonFunctions::languageChecker('Zadbaj o wyjątkowe doświadczenie klientów – nadaj im status VIP już teraz!', 'Ensure a unique experience for your customers!') . '</h3>';
                        $output .= '
                        </div>
                        <div class="exhibitor-generator__right-icons">
                            <h5>' . PWECommonFunctions::languageChecker('Identyfikator VIP uprawnia do:', 'VIP badge entitles to:') . '</h5>
                            <div class="exhibitor-generator__right-icons-wrapper">
                                <!-- <div class="exhibitor-generator__right-icon">
                                    <img src="/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/ico3.png" alt="icon3">
                                    <p>' . PWECommonFunctions::languageChecker('Fast track', 'Fast track') . '</p>
                                </div> -->
                                <div class="exhibitor-generator__right-icon">
                                    <img src="/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/ico4.png" alt="icon4">
                                    <p>' . PWECommonFunctions::languageChecker('Opieki concierge`a', 'Concierge care') . '</p>
                                </div>
                                <div class="exhibitor-generator__right-icon">
                                    <img src="/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/ico1.png" alt="icon1">
                                    <p>' . PWECommonFunctions::languageChecker('VIP ROOM', 'VIP ROOM ') . '</p>
                                </div>
                                <div class="exhibitor-generator__right-icon">
                                    <img src="/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/ico2.png" alt="icon2">
                                    <p>' . PWECommonFunctions::languageChecker('Udział w konferencjach', 'Participation in conferences') . '</p>
                                </div>';

                                if($pweGeneratorWebsite){
                                    $output .= '
                                    <div class="exhibitor-generator__right-icon">
                                        <img src="/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/ico6.png" alt="icon6">
                                        <p>' . PWECommonFunctions::languageChecker('Zaproszenie na Wieczór Branżowy', 'Invitation to the Industry Evening') . '</p>
                                    </div>';
                                }
                            $output .='

                            </div>
                        </div>
                        <div class="exhibitor-generator__right-form">
                            [gravityform id="'. $generator_form_id .'" title="false" description="false" ajax="false"]
                        </div>';

                        // Add a mass invite send button if not on a personal exhibitor page
                        if(get_locale() == "pl_PL" && (!isset($company_array['exhibitor_name'])  && PWEExhibitorVisitorGenerator::fairStartDateCheck()) || current_user_can('administrator')){
                            $output .= '<button type="button" class="tabela-masowa btn-gold">' . PWECommonFunctions::languageChecker('Wysyłka zbiorcza', 'Collective send') . '</button>';
                        }

                        // Add optional content to the page if available
                        if (!empty($generator_html_text_content)) {
                            $output .= '<div type="button" class="exhibitor-generator__right-text">' . $generator_html_text_content . '</div>';
                        }
                    $output .= '
                    </div>
                </div>
            </div>
        </div>
        <script>
           jQuery(document).ready(function($){
                let exhibitor_name = "' . PWEExhibitorVisitorGenerator::$exhibitor_name . '";
                let exhibitor_desc = `' . PWEExhibitorVisitorGenerator::$exhibitor_desc . '`;
                let exhibitor_stand = "' . PWEExhibitorVisitorGenerator::$exhibitor_stand . '";
                const group_tag = $(".exhibitor-generator").data("group");

                $(`.gfield--type-fileupload input[type="file"]`).attr("accept", "image/jpeg, image/png");
                $(".exhibitor_stand").addClass("gfield_visibility_visible").removeClass("gfield_visibility_hidden");
                $(".input_logo").addClass("gfield_visibility_visible").removeClass("gfield_visibility_hidden");

                $(".exhibitor_logo input").val("' . PWEExhibitorVisitorGenerator::$exhibitor_logo_url . '");
                $(".exhibitors_name input").val(exhibitor_name);
                $(".exhibitor_desc input").val(exhibitor_desc);
                $(".exhibitor_stand input").val(exhibitor_stand);

                $(`input[placeholder="FIRMA ZAPRASZAJĄCA"]`).val(exhibitor_name);

                $(`input[placeholder="FIRMA ZAPRASZAJĄCA"]`).on("input", function(){
                    if(!$(".badge_name").find("input").is(":checked")){
                        $(".exhibitors_name input").val($(this).val());
                    }
                    exhibitor_name = $(this).val();
                });

                $(".badge_name").on("change", function(){
                    if($(this).find("input").is(":checked")){
                        $(".exhibitors_name input").val("");
                    } else {
                        $(".exhibitors_name input").val(exhibitor_name);
                    }
                });
           });
        </script>
        ';
        if($pweGeneratorWebsite){
            $output .= '
            <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Funkcja do pobierania parametru z URL
                function getURLParameter(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
                }

                // Pobierz wartość parametru "p"
                const parametr = getURLParameter("p");

                // Jeśli parametr istnieje, znajdź input w elemencie o klasie "parametr" i wpisz wartość
                if (parametr) {
                const inputElement = document.querySelector(".parametr input");
                if (inputElement) {
                    inputElement.value = parametr;
                }
                }
            });
            </script>
            <style>
                .exhibitor-generator-tech-support {
                    display:none !important;
                }
            </style>';
        }

        if(PWEExhibitorVisitorGenerator::senderFlowChecker() || current_user_can('administrator')){
            $output .='
            <div class="modal__element">
                <div class="inner">
                    <span class="btn-close">x</span>';

                    // if ($phone_field){
                    //     $output .='
                    //     <p style="max-width:90%;">'.
                    //         PWECommonFunctions::languageChecker(
                    //             <<<PL
                    //             Uzupełnij poniżej nazwę firmy zapraszającej oraz wgraj plik (csv, xls, xlsx) z danymi osób, które powinny otrzymać zaproszenia VIP GOLD. Przed wysyłką zweryfikuj zgodność swoich danych z załączonym przez nas przykładem prawidłowego pliku.
                    //             PL,
                    //             <<<EN
                    //             Fill in the name of the inviting company below and upload a file (csv, xls, xlsx) with the data of those who should receive VIP GOLD invitations. Before sending, verify the compatibility of your data with the example of a valid file we have attached.
                    //             EN
                    //         )
                    //     .'</p>';
                    // } else {
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
                    // }
                    // if (!empty(self::$exhibitor_logo_url)){
                    //     $output .='<img style="max-height: 70px;" src="' . self::$exhibitor_logo_url . '">';
                    // }
                    $output .='
                    <input type="text" class="patron" value="" style="display: none;">
                    <input type="text" class="company" value="' . PWEExhibitorVisitorGenerator::$exhibitor_name .'" placeholder="'.
                        PWECommonFunctions::languageChecker(
                            <<<PL
                            Firma Zapraszająca (wpisz nazwę swojej firmy)
                            PL,
                            <<<EN
                            Inviting Company (your company's name)
                            EN
                        )
                    .'"></input>';
                    // <select id="exhibitors_selector__modal">';
                    //     $output .='<option class="cat-exhibitor" val="" data-id="' . $cat_id . '">Firma Zapraszająca (wybierz z listy)</option>';
                    //     foreach($all_exhibitors as $cat_id => $cat_value){
                    //         $output .='<option class="cat-exhibitor" val="' . $cat_value['Nazwa_wystawcy'] . '">' . $cat_value['Nazwa_wystawcy'] . '</option>';
                    //     }
                    //     $output .='<option class="cat-exhibitor" val="" data-id="' . $cat_id . '">Patron</option>
                    //     </select>';
                    $output .='
                    <div class="more-info-container" style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" id="exhibitor_stand" name="exhibitor_stand" placeholder="'.
                            PWECommonFunctions::languageChecker(
                                <<<PL
                                Numer Stoiska
                                PL,
                                <<<EN
                                Stund Nr.
                                EN
                            )
                        .'">
                        <div>
                            <label>Dodaj logotyp</label>
                            <input type="file" id="exhibitor_logo" name="exhibitor_logo" style="margin-top:0"  accept=".jpg, .png">
                            <p class="under-label">'.
                                PWECommonFunctions::languageChecker(
                                    <<<PL
                                    Dozwolone pliki .png, jpg;&nbsp;&nbsp;; Rozmiar ~200kb
                                    PL,
                                    <<<EN
                                    Allowed file: .png, jpg;&nbsp;&nbsp; Size ~200kb
                                    EN
                                )
                            .'</p>
                        </div>
                    </div>

                    <label class="mass_checkbox_label" style="display:none;">
                        <input type="checkbox" id="mass_exhibitor_badge" name="mass_exhibitor_badge" class="mass_checkbox">
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
                        .'</p>
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
                </div>
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