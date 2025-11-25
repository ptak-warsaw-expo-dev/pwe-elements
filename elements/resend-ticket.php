<?php

class PWEResendTicket extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        add_action('wp', array($this, 'notification_sender'));
    }

    public static function notification_sender() {
        // Check if the 'data' parameter is set in the query string
        if (!isset($_GET['data'])) {
            // Redirect to the homepage if 'data' is not set
            wp_redirect(home_url());
            exit; // Ensure the script stops executing after the redirect
        }

        // Odszyfrowanie danych z query stringu
        $encrypted_data = $_GET['data'];
        $decoded_data = base64_decode($encrypted_data);

        if (!$decoded_data) {
            echo '<script>console.error("Błąd dekodowania danych.")</script>';
            return;
        }

        $id_array = explode(',', $decoded_data);
        if (count($id_array) !== 2) {
            echo '<script>console.error("Nieprawidłowy format danych.")</script>';
            return;
        }

        $form_id = $id_array[0];
        $entry_id = $id_array[1];

        // Pobranie formularza
        $form = GFAPI::get_form($form_id);
        if (is_wp_error($form) || empty($form)) {
            echo '<script>console.error("Nie udało się pobrać formularza.")</script>';
            return;
        }

        // Pobranie wpisu
        $entry = GFAPI::get_entry($entry_id);
        if (is_wp_error($entry) || empty($entry)) {
            echo '<script>console.error("Nie udało się pobrać potwierdzenia.")</script>';
            return;
        }

        // Aktywacja powiadomień
        $notification_names = [
            'Thank you for registering for the Fair - color',
            'Dziękujemy za rejestrację na Targi - (kolor)'
        ];

        foreach ($form["notifications"] as $id => &$key) {
            if (in_array($key['name'], $notification_names)) {
                $key['isActive'] = true;
            } else {
                $key['isActive'] = false;
            }
        }

        try {
            GFAPI::send_notifications($form, $entry);
        } catch (Exception $e) {
            echo '<script>console.error("Błąd send_notifications.")</script>';
        }
        return true;
    }

    public static function output($atts) {
        self::notification_sender();

        $output = '
            <style>
                #resend {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }
                #resend p {
                    font-size: 24px;
                    text-align: center;
                }
                #resend .akcent {
                    color: '.self::$accent_color .';
                }
                #resend a {
                    background-color: '.self::$accent_color .';
                }
            </style>';
        $output .= '
            <div id="resend" class="">
            <p>'.
                self::languageChecker(
                    <<<PL
                        Twój bilet został wysłany ponownie. Sprawdź swój adres email.
                    PL,
                    <<<EN
                        Your ticket has been resent. Check your email address.
                    EN
                )
            .'</p>
            <p>'.
                self::languageChecker(
                    <<<PL
                        Do zobaczenia na targach </br> <span class="akcent"><strong>[trade_fair_name] [trade_fair_date]</span></strong>
                    PL,
                    <<<EN
                        See you at the fair </br> <span class="akcent"><strong>[trade_fair_name_eng] [trade_fair_date_eng]</span></strong>
                    EN
                )
            .'</p>
            '.
                self::languageChecker(
                    <<<PL
                        <a style="padding: 12px 32px; border-radius: 15px; color: white; margin-top: 25px;" href="/">Strona główna</a>
                    PL,
                    <<<EN
                        <a style="padding: 12px 32px; border-radius: 15px; color: white; margin-top: 25px;" href="/en/">Home page</a>
                    EN
                )
                .'
            </div>';

        return $output;
    }
}