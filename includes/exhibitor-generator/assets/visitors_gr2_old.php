<?php

function modify_placeholders_labels($generator_form_id) {
    $form = GFAPI::get_form($generator_form_id);

    foreach ($form['fields'] as &$field) {
        // 1. Usuń hidden_label
        if (isset($field->labelPlacement) && $field->labelPlacement === 'hidden_label' && $field->type != 'consent') {
            $field->labelPlacement = '';
        }


        // 2. Pogrub pierwsze słowo labela
        if (!empty($field->label) && strip_tags($field->label) === $field->label) {
            $parts = explode(' ', $field->label, 2);
            if (count($parts) > 1 && $field->type != 'consent') {
                $field->label = "<strong> {$parts[0]} </strong> " . $parts[1];

            }
        }

        // 3. Zmień placeholdery
        if (!empty($field->placeholder)) {
            if ($field->placeholder === 'Gość - Imię i Nazwisko') {
                $field->placeholder = 'Imię i Nazwisko';
            }

            if ($field->placeholder === 'E-mail osoby zapraszanej') {
                $field->placeholder = 'E-mail';
            }

            if ($field->placeholder === 'Guest - Full name') {
                $field->placeholder = 'Full name';
            }

            if ($field->placeholder === 'E-mail of the invited person') {
                $field->placeholder = 'E-mail';
            }

        }
    }

    return $form;
}

/**
 * Trwale dodaje pole „Zgoda marketingowa” do formularza GF
 * – **wykonuje się tylko raz** dzięki znacznikowi w wp_options.
 */
function add_field_marketing_consent($generator_form_id) {

    global $local_lang_pl;

    if ( empty( $generator_form_id ) ) {
        return;
    }

    $done_option_key = "gf_consent_added_$generator_form_id";

    // delete_option( $done_option_key );

    // Jeśli już zrobione -> nic nie rób
    if ( get_option( $done_option_key ) ) {
        return;
    }

    $patron_field_id = null;

    // Pobierz formularz
    $form = GFAPI::get_form( $generator_form_id );
    if ( ! $form || is_wp_error( $form ) ) {
        echo "<script>console.log('GF: nie udało się pobrać formularza');</script>";
        return;
    }

    // if (strpos($form_title, 'Rejestracja gości wystawców') === false ) {
    //     return;
    // }

    // Sprawdź, czy pole już istnieje
    foreach ( $form['fields'] as $field ) {

        if ( isset( $field->adminLabel ) && $field->adminLabel === 'patron' ) {
            $patron_field_id = $field->id;
        }

        if ( isset( $field->inputName ) && $field->inputName === 'zgoda_marketingowa' ) {
            update_option( $done_option_key, 1 ); // zaznacz jako wykonane
            return;
        }
    }

    // Ustal kolejne ID pola
    $next_id = max( wp_list_pluck( $form['fields'], 'id' ) ) + 1;

    $new_consent_field = new GF_Field_Consent( array(
        'id'            => $next_id,
        'label'         => $local_lang_pl ? 'Zgoda marketingowa' : 'Marketing consent',
        'inputName'     => 'zgoda_marketingowa',
        'labelPlacement' => 'hidden_label',
        'isRequired'    => false,
        'inputType'     => 'consent',
        'cssClass'      => 'pwe-marketing-consent',
        'checkboxLabel' => $local_lang_pl
            ? 'Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych w celach marketingowych i wysyłki wiadomości. <span class="show-consent">(Więcej)</span>'
            : 'I agree to the processing by PTAK WARSAW EXPO sp. z o.o. my personal data for marketing purposes and sending messages.  <span class="show-consent">(More)</span>',
        'description'   => $local_lang_pl
            ? 'Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych, tj. 1) imię i nazwisko; 2) adres e-mail 3) nr telefonu w celach wysyłki wiadomości marketingowych i handlowych związanych z produktami i usługami oferowanymi przez Ptak Warsaw Expo sp. z o.o. za pomocą środków komunikacji elektronicznej lub bezpośredniego porozumiewania się na odległość, w tym na otrzymywanie informacji handlowych, stosownie do treści Ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną. Wiem, że wyrażenie zgody jest dobrowolne, lecz konieczne w celu dokonania rejestracji. Zgodę mogę wycofać w każdej chwili.'
            : 'I agree to the processing by PTAK WARSAW EXPO sp. z o.o. of my personal data, i.e. 1) name and surname; 2) e-mail address; 3) telephone number for the purposes of sending marketing and commercial messages related to products and services offered by Ptak Warsaw Expo sp. z o.o. by means of electronic communication or direct remote communication, including receiving commercial information, pursuant to the Act of 18 July 2002 on the provision of services by electronic means. I know that the consent is voluntary but necessary for registration. I can withdraw my consent at any time.',
        'inputs' => array(
            array( 'id' => "{$next_id}.1", 'label' => 'Zgoda', 'name' => '' ),
            array( 'id' => "{$next_id}.2", 'label' => 'Tekst', 'name' => '', 'isHidden' => true ),
            array( 'id' => "{$next_id}.3", 'label' => 'Opis', 'name' => '', 'isHidden' => true ),
        ),
        'choices' => array(
            array(
                'text'       => 'Zaznaczone',
                'value'      => '1',
                'isSelected' => false,
                'price'      => ''
            )
        ),
        'conditionalLogic' => array(
            'actionType' => 'show',
            'logicType'  => 'all',
            'rules'      => array(
                array(
                    'fieldId'  => $patron_field_id,
                    'operator' => 'is',
                    'value'    => 'gr2',
                ),
            ),
        ),
    ) );

    $captcha_index = null;
    foreach ( $form['fields'] as $index => $field ) {
        if ( $field->type === 'captcha' ) {
            $captcha_index = $index;
            break;
        }
    }

    if ( $captcha_index !== null ) {
        array_splice( $form['fields'], $captcha_index, 0, array( $new_consent_field ) );
    } else {
        $form['fields'][] = $new_consent_field;
    }

    $result = GFAPI::update_form( $form );

    if ( is_wp_error( $result ) ) {
        error_log( 'GF: błąd zapisu formularza – ' . $result->get_error_message() );
        return;
    }

    // Oznacz jako wykonane, żeby kod więcej nie biegał
    update_option( $done_option_key, 1 );
};

function add_field_phone_number($generator_form_id) {

    global $local_lang_pl;

    if ( empty( $generator_form_id ) ) {
        return;
    }

    $done_option_key = "gf_phone_added_$generator_form_id";

    // delete_option( $done_option_key );

    // Jeśli już zrobione -> nic nie rób
    if ( get_option( $done_option_key ) ) {
        return;
    }

    $phone_field_exists = false;
    $text_phone_field_id_to_remove = null;
    $patron_field_id = null;

    $form = GFAPI::get_form( $generator_form_id );
    if ( ! $form || is_wp_error( $form ) ) {
        echo "<script>console.log('GF: nie udało się pobrać formularza');</script>";
        return;
    }

    $form_title = isset($form['title']) ? $form['title'] : '';

    if (strpos($form_title, 'Rejestracja gości wystawców') === false ) {
        return;
    }

    // Sprawdź, czy pole już istnieje
    foreach ( $form['fields'] as $field ) {

        if ( !empty($field->cssClass) && $filed->cssClass == 'pwe-phone-number') {
            update_option( $done_option_key, 1 ); // zaznacz jako wykonane
            $phone_field_exists = true;
        }
        if ( isset( $field->adminLabel ) && $field->adminLabel === 'patron' ) {
            $patron_field_id = $field->id;
        }
        if (isset($field->type) && $field->type === 'text' && (trim($field->label) === 'Telefon' || trim($field->label) === 'Phone number')) {
            $text_phone_field_id_to_remove = $field->id;
        }
    }

    if ( $phone_field_exists || get_option( $done_option_key ) == 1 || $patron_field_id === null ) {
        return;
    }

    // Deleting the old phone field with text type
    if ($text_phone_field_id_to_remove !== null) {
        $entries = GFAPI::get_entries($generator_form_id);

        $has_value = false;
        foreach ($entries as $entry) {
            if (!empty($entry[$text_phone_field_id_to_remove])) {
                $has_value = true;
                break;
            }
        }

        if (!$has_value) {
            foreach ($form['fields'] as $index => $field) {
                if ($field->id == $text_phone_field_id_to_remove) {
                    unset($form['fields'][$index]);
                    $form['fields'] = array_values($form['fields']);
                    break;
                }
            }
        }
    }


    $next_id = max( wp_list_pluck( $form['fields'], 'id' ) ) + 1;

    $new_phone_field = new GF_Field_Phone( array(
        'id'            => $next_id,
        'label'         => $local_lang_pl ? 'Numer telefonu' : 'Phone number',
        'inputName'     => 'numer_telefonu',
        'placeholder' => $local_lang_pl ? 'Telefon' : 'Phone number',
        'labelPlacement' => 'hidden_label',
        'isRequired'    => false,
        'inputType'     => 'phone',
        'smartPhoneFieldGField' => true,
        'phoneFormat' => 'international',
        'cssClass'      => 'pwe-phone-number',
        'conditionalLogic' => array(
            'actionType' => 'show',
            'logicType'  => 'all',
            'rules'      => array(
                array(
                    'fieldId'  => $patron_field_id,
                    'operator' => 'is',
                    'value'    => 'gr2',
                ),
            ),
        ),
    ) );

    // Znajdź index pola email
    $insert_index = null;
    foreach ($form['fields'] as $index => $field) {
        if ($field->type === 'email') {
            $insert_index = $index + 1;
            break;
        }
    }

    // Wstaw pole po emailu, lub na koniec
    if ($insert_index !== null) {
        array_splice($form['fields'], $insert_index, 0, array($new_phone_field));
    } else {
        $form['fields'][] = $new_phone_field;
    }

    $form['confirmations'] = array(
        'embed_redirect' => array(
            'id'          => uniqid(),
            'name'        => 'embed_redirect',
            'isDefault'   => true,
            'type'        => 'redirect',
            'url'         => '{embed_url}',
            'pageId'      => '',
            'message'     => '',
            'conditionalLogic' => null,
        )
    );

    $result = GFAPI::update_form( $form );

    if ( is_wp_error( $result ) ) {
        error_log( 'GF: błąd zapisu formularza – ' . $result->get_error_message() );
        return;
    }

    // Oznacz jako wykonane, żeby kod więcej nie biegał
    update_option( $done_option_key, 1 );
};

function add_notification_conf_to_form($generator_form_id) {
    global $local_lang_pl;

    $done_option_key = "gf_notification_conf_added_$generator_form_id";

    // delete_option( $done_option_key );

    if (get_option($done_option_key)) {
        return;
    }

    $form = GFAPI::get_form($generator_form_id);
    if (!$form || is_wp_error($form)) {
        echo "<script>console.log('GF: nie udało się pobrać formularza');</script>";
        return;
    }

    // foreach ($form['notifications'] as $key => $notification) {
    //     if (
    //         isset($notification['name']) &&
    //         in_array($notification['name'], [
    //             'Dziękujemy za rejestrację na Targi Konferencja',
    //             'Dziękujemy za rejestrację na Targi Konferencja - ENG',
    //         ])
    //     ) {
    //         unset($form['notifications'][$key]);
    //     }
    // }

    $admin_label_to_id = [];
    foreach ($form['fields'] as $field) {
        if (!empty($field->adminLabel)) {
            $admin_label_to_id[$field->adminLabel] = $field->id;
        }
    }

    // Dodaj lub zmodyfikuj logikę pola telefonu (po adminLabel lub CSS class)
    foreach ($form['fields'] as $index => $field) {
        if (isset($field->cssClass) && strpos($field->cssClass, 'pwe-phone-number') !== false) {

            $patron_id = isset($admin_label_to_id['patron']) ? (string) $admin_label_to_id['patron'] : null;

            if ($patron_id !== null) {
                $form['fields'][$index]->conditionalLogic = [
                    'actionType' => 'show',
                    'logicType' => 'any',
                    'rules' => [
                        [
                            'fieldId' => $patron_id,
                            'operator' => 'is',
                            'value' => 'conf',
                        ],
                        [
                            'fieldId' => $patron_id,
                            'operator' => 'is',
                            'value' => 'gr2',
                        ],
                        [
                            'fieldId' => $patron_id,
                            'operator' => 'is',
                            'value' => 'patron',
                        ],
                    ],
                ];
            }

            // Nie przetwarzaj dalej — zakładamy, że tylko jedno pole telefonu
            break;
        }
    }

    // Dodaj logikę do pola zgody marketingowej
    foreach ($form['fields'] as $index => $field) {
        if (isset($field->inputName) && $field->inputName === 'zgoda_marketingowa') {
            $patron_id = isset($admin_label_to_id['patron']) ? (string) $admin_label_to_id['patron'] : null;

            if ($patron_id !== null) {
                $form['fields'][$index]->conditionalLogic = [
                    'actionType' => 'show',
                    'logicType' => 'any',
                    'rules' => [
                        [
                            'fieldId' => $patron_id,
                            'operator' => 'is',
                            'value' => 'gr2',
                        ],
                        [
                            'fieldId' => $patron_id,
                            'operator' => 'is',
                            'value' => 'conf',
                        ],
                        [
                            'fieldId' => $patron_id,
                            'operator' => 'is',
                            'value' => 'patron',
                        ],
                    ],
                ];
            }

            break; // zakładamy jedno pole zgody
        }
    }

    $lang = $local_lang_pl ? 'pl' : 'en';

    // Wybierz nazwę i temat
    $notification_data = [
        'name' => [
            'pl' => 'Dziękujemy za rejestrację na Targi Konferencja',
            'en' => 'Dziękujemy za rejestrację na Targi Konferencja - ENG',
        ],
        'subject' => [
            'pl' => 'Dziękujemy za rejestrację na {trade_fair_name}',
            'en' => 'Thank you for registering at {trade_fair_name}',
        ],
        'file' => [
            'pl' => 'generator_gosci_conf_notification_pl.html',
            'en' => 'generator_gosci_conf_notification_en.html',
        ],
    ];

    $message = '';
    $template_file = plugin_dir_path(__FILE__) . 'notifications/' . $notification_data['file'][$lang];

    $qr_feed_id = null;
    $feeds = GFAPI::get_feeds(null, $generator_form_id);

    foreach ($feeds as $feed) {
        if (isset($feed['addon_slug']) && $feed['addon_slug'] === 'qr-code') {
            $qr_feed_id = $feed['id'];
            break; // tylko pierwszy QR-code feed
        }
    }

    if (file_exists($template_file)) {
        $message = file_get_contents($template_file);
        $message = str_replace('[qr_feed_id]', $qr_feed_id, $message);
    } else {
        $message = 'Dziękujemy za udział w wydarzeniu.'; // fallback
    }

    $email_input_ref = ''; // np. inputName lub ID

    foreach ($form['fields'] as $field) {
        if ($field->type === 'email') {
            $email_input_ref = $field->inputName ?: $field->id;
            break;
        }
    }

    $new_notification = [
        'id' => uniqid(),
        'isActive' => true,
        'name' => $notification_data['name'][$lang],
        'event' => 'form_submission',
        'toType' => 'field',
        'toField' => $email_input_ref,
        'to' => $email_input_ref,
        'subject' => $notification_data['subject'][$lang],
        'message' => $message,
        'messageFormat' => 'html',
        'from' => '{trade_fair_rejestracja}',
        'fromName' => '{trade_fair_name}',
        'disableAutoformat' => true,
        'enableAttachments' => false,
        'enableQrAttachment' => true,
        'conditionalLogic' => [
            'actionType' => 'show',
            'logicType' => 'any',
            'rules' => [
                [
                    'fieldId' => 'patron',
                    'operator' => 'is',
                    'value' => 'conf'
                ],
            ]
        ],
    ];

    if ($qr_feed_id !== null) {
        $new_notification['spgfqrcode_notification_feed_' . $qr_feed_id] = '1';
        $new_notification['spgfqrcode_embed_image_feed_' . $qr_feed_id] = '1';
    }

    foreach (['confirmation_conditional_logic_object', 'conditionalLogic'] as $logic_key) {
        if (isset($new_notification[$logic_key]['rules'])) {
            foreach ($new_notification[$logic_key]['rules'] as &$rule) {
                $admin_label = $rule['fieldId'];
                if (!is_numeric($admin_label) && isset($admin_label_to_id[$admin_label])) {
                    $rule['fieldId'] = (string) $admin_label_to_id[$admin_label];
                }
            }
        }
    }

    // Dodaj notyfikację do formularza
    $form['notifications'][] = $new_notification;

    $result = GFAPI::update_form($form);
    if (is_wp_error($result)) {
        error_log('GF: Błąd zapisu formularza przy dodawaniu powiadomienia – ' . $result->get_error_message());
        return;
    }

    update_option($done_option_key, 1);
}

function logged_in_exhibitor_fields_hidden($form) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    echo "<script>console.log('Sesja: " . json_encode($_SESSION) . "');</script>";

    $code_count = strlen($_GET['wystawca']) ?? 0;


    if (!empty($_SESSION['logged_in_exhibitor'])) {
        foreach ($form['fields'] as &$field) {
            if ($field->type === 'phone') {
                $field->visibility = 'hidden';
            }
             if ($field->type === 'consent') {
                $field->visibility = 'hidden';
            }
        }
    } else {
        foreach ($form['fields'] as &$field) {
            if ($field->type === 'phone') {
                $field->visibility = 'visible';
            }
            if ($field->type === 'consent') {
                $field->visibility = 'visible';
            }
        }
    }

    return $form;
}

function render_gr2($atts, $all_exhibitors, $all_partners, $all_conferences, $pweGeneratorWebsite, $domain){
    extract( shortcode_atts( array(
        'generator_form_id' => '',
        'exhibitor_generator_html_text' => '',
        'generator_catalog' => '',
        'generator_patron' => '',
    ), $atts ));

    global $local_lang_pl;

    add_field_marketing_consent( $generator_form_id );
    add_field_phone_number( $generator_form_id );
    add_notification_conf_to_form( $generator_form_id );

    $code_count = strlen($_GET['wystawca']) ?? 0;

    $all_senders = array();
    $no_nip_index = 10000;

    foreach ($all_exhibitors as $cat_id => $cat_value) {
        $full_id = (!empty($cat_value['NIP'])) ? $cat_value['NIP'] : $no_nip_index++;
        $id_hash = substr(sha1($full_id), 0, 10);

        $all_senders[$id_hash] = array(
            'name' => $cat_value['Nazwa_wystawcy'],
            'logo' => $cat_value['URL_logo_wystawcy'],
            'pass' => (strlen($full_id) > 5) ? $full_id : '',
            'desc' => $id_hash,
            'type' => 'gr2',
        );
    }


    foreach($all_partners as $part_id => $part_value){
        if(in_array($part_value->logos_type, ['partner-targow', 'patron-medialny']) && $part_value->logos_alt != 'Federacja'){
            $full_id = $part_value->id . $domain;

            // zmiana 'pass' => $id_hash, na 'pass' => $full_id,  aby łatwiej było o hasło do patrona można sprawdzić w capie
            // na jakiej podstawie macie wysyłać powiadomienie patro? bo teraz jako patron bieże gr2

            $id_hash = $hash = substr(sha1($full_id), 0, 12);
            $all_senders[$id_hash] = array(
                'name' => $part_value->logos_alt,
                'logo' => 'https://cap.warsawexpo.eu/public' . $part_value->logos_url,
                'pass' => $full_id,
                'desc' => $id_hash,
                'type' => 'patron',
            );


        }
    }
    $org_hash = substr(sha1('PWE' . $domain), 0, 12);
    $all_senders[$org_hash] = array(
        'name' => 'Ptak Warsaw Expo',
        'logo' => '/wp-content/plugins/pwe-media/media/logo_pwe_black.png',
        'pass' => 'PWE',
        'desc' => $org_hash,
        'type' => 'gr2',
    );
    // dodanie konferencji aby można było wysyłać zaproszenia hasło nazwa konferencji np. "Konferencja PIME HEATING TECH"
    foreach($all_conferences as $conf) {

        if (!empty($conf->conf_name_pl) && !empty($conf->conf_img_pl)) {

            $full_id = $conf->conf_name_pl;
            $id_hash = substr(sha1($full_id), 0, 12);

            $all_senders[$id_hash] = array(
                'name' => ($local_lang_pl ? $conf->conf_name_pl : $conf->conf_name_en),
                'logo' => $conf->conf_img_pl,
                'pass' => $full_id,
                'desc' => $id_hash,
                'type' => 'conf',
            );
        }
    }


    if (isset($_GET['wystawca']) && $_GET['wystawca'] === '7JF9vQcFR5KmzaHqNCSw') {
        uasort($all_senders, function($a, $b) {
            $a_empty = empty($a['pass']);
            $b_empty = empty($b['pass']);

            if ($a_empty && !$b_empty) return -1;
            if (!$a_empty && $b_empty) return 1;
            return 0;
        });
        $output = '
        <style>
            #exhibitors-list {
                list-style: none !important;
                padding: 0 !important;
            }

            #exhibitors-list li{
                padding: 6px !important;
            }

            #exhibitors-list li:nth-of-type(2n) {
                background-color: #E5E4E2 !important;
            }
            </style>
        <ul id="exhibitors-list">';
        foreach ($all_senders as $id => $data) {
            if (is_array($data)) {
                $output .= '<li><strong>' . htmlspecialchars($data['name']) . '</strong>: ' . htmlspecialchars($data['pass']) . '</li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }

    if (isset($_GET['wystawca']) && !is_array($all_senders[$_GET['wystawca']])) {

        $redirect_url = remove_query_arg('wystawca', $_SERVER['REQUEST_URI']);
        wp_safe_redirect($redirect_url);
        exit;
    }


    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $exhibitor_name = $all_senders[$_GET['wystawca']]['name'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'], $_POST['exhibitor_id'])) {

        $password = trim($_POST['password']);

        $exhibitor_id = $_POST['exhibitor_id'];
        $stored_password = $all_senders[$exhibitor_id]['pass'];

        if ($password == $stored_password) {
            $_SESSION['logged_in_exhibitor'] = $exhibitor_id;

            $redirect_url = add_query_arg(
                array('wystawca' => $exhibitor_id),
                remove_query_arg('p', $_SERVER['REQUEST_URI'])
            );
        } else {
            $_SESSION['login_error'] = $local_lang_pl ? 'Błędne hasło lub nazwa użytkownika' : 'Incorrect password or username';

            $redirect_url = $_SERVER['REQUEST_URI'];
        }

        wp_safe_redirect($redirect_url);
        exit;
    }

    $output = '';

    $output = '
        <style>
            .vc_row.row-container:has(.exhibitor-generator.gr2) {
                background-image: url(/doc/background.webp);
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }

            .row.limit-width.row-parent:has(.exhibitor-generator.gr2) {
                padding-top: 0;
            }

            .exhibitor-generator.gr2 .gfield:has(input[placeholder^="Firma"]),
            .exhibitor-generator.gr2 .gfield:has(input[placeholder^="Inviting"]) {
                display: none;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__wrapper {
                gap: 18px;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-badge {
                width: 50%;
                position: relative;
                overflow: hidden;
                border-radius: 24px;
                display: flex;
                flex-direction: column;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-content {
                position: relative;
                padding: 6px 32px;
                min-height: 600px;
                height: 100%;
                background: white;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                background-image: url(/wp-content/plugins/pwe-media/media/generator-gosci-wystawcow/gr2/gen-gosci-gr2-bg.webp);
                background-size: contain;
                background-repeat: no-repeat;
                background-position: top;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-header-badge {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 12px 0;
            }

            .exhibitor-generator.gr2 img.exhibitor-generator__badge-logo {
                width: 34%;
                min-width: 120px;
            }

           .exhibitor-generator.gr2 span.exhibitor-generator__vip-text {
                font-size: 38px;
                font-weight: 800;
                font-style: italic;
                text-transform: uppercase;
                color: #b79663;
            }

            .exhibitor-generator.gr2 .exhibitor-selector__container:has(.exhibitors-logo) {
                display: flex;
                justify-content: center;
                max-width: 160px;
                margin: 18px auto;
                aspect-ratio: 5/3;
                background: white;
                box-shadow: 0 0 8px -4px black;
                padding: 6px 12px;
                border-radius: 12px;
            }

            .exhibitor-generator.gr2 .exhibitors-logo {
                width: 100%;
                object-fit: contain;
            }
            .exhibitor-generator.gr2 .exhibitor-selector__container:has(.exhibitors-logo[src*="/uploads/conf/"]) {
                align-items: center;
                max-width: 220px;
                aspect-ratio: 1 / 1;
                overflow: hidden;
            }
            .exhibitor-generator.gr2 .exhibitors-logo[src*="/uploads/conf/"] {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
            .exhibitor-generator.gr2 .exhibitor-generator__left-badge input {
                border-radius: 36px;
                text-align: center;
                border: 0;
                width: 100%;
                max-width: 90%;
                margin: 0 auto;
                box-shadow: 2px 2px 6px -3px black;
            }

            .exhibitor-generator.gr2 .exhibitor-selector__container {
                width: 100%;
            }

            #exhibitors-login-form {
                max-width: 90%;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                gap: 16px;
                align-items: center;
            }

            .exhibitor-generator.gr2 .exhibitor-selector__container input{
                max-width: 100%;
            }

            .exhibitor-generator.gr2 #exhibitors_selector {
                border-radius: 36px !important;
                background-color: white !important;
                box-shadow: 2px 2px 6px -3px black !important;
                margin: 0 !important;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-badge input::placeholder {
                color: #a5a5a5;
                font-size: 14px !important;
            }

            .exhibitor-generator.gr2 label {
                display: flex !important;
                flex-direction: row;
                justify-content: center;
                gap: 4px;
            }

            .exhibitor-generator.gr2 label strong {
                font-weight: 600;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-badge input[type="submit"],
            .exhibitor-generator.gr2 .exhibitor-generator__left-badge .tabela-masowa {
                border-radius: 36px !important;
                max-width: 46%;
                min-width: 180px !important;
                width: 100% !important;
                font-size: 12px !important;
            }

           .exhibitor-generator.gr2 .gform_wrapper :is(label)  {
                font-size: 12px;
                line-height: 15px;
                color: black !important;
                cursor: default;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-footer-badge {
                display: flex;
                justify-content: center;
                position: relative;
                margin: 0 -12px;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-footer-badge:before {
                content: "";
                background: #b79663;
                width: 100%;
                height: 50%;
                position: absolute;
                left: 0;
                bottom: 0;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__badge-logo-pwe {
                width: 80px;
                filter: invert(1);
                background: white;
                padding: 12px;
                border-radius: 12px;
                margin-bottom: 36px;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__form-badge-top {
                position: relative;
                width: 100%;
            }
            .exhibitor-generator.gr2 .exhibitor-generator__form-badge-right {
                position: absolute;
                height: 100%;
                right: 0;
                top: 0;
                bottom: 0;
                width: 25px;
            }
            .exhibitor-generator.gr2 .exhibitor-generator__form-badge-bottom {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1;
                height: 25px;
                width: 100%;
            }
            .exhibitor-generator.gr2 .exhibitor-generator__form-badge-left {
                position: absolute;
                height: 100%;
                left: 0;
                top: 0;
                bottom: 0;
                width: 25px;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__left-badge .gform-footer {
                max-width: 90% !important;
                margin: 0 auto !important;
            }

           .exhibitor-generator.gr2 .gform_button {
                background: #b79663 !important;
                color: white !important;
                border-radius: 8px !important;
                margin: 0 !important;
                margin-top: 9px !important;
                margin-bottom: 9px !important;
                padding: 8px 20px !important;
                padding-top: 8px !important;
                padding-right: 20px !important;
                padding-bottom: 8px !important;
                padding-left: 20px !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                min-width: 120px !important;
                border-radius: 15px !important;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right {
                width: 50%;
                margin-top: 90px;
                border-radius: 20px;
                border: 1px solid #b79663;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right .exhibitor-generator__right-wrapper {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                width: 90%;
                height: 100%;
                padding: 36px 0;
                max-height: 800px;
                margin: auto;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right-icons {
                width: 100% !important;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right-icons-wrapper {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-around;
                gap: 12px;
                flex-direction: column;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right-icon {
                max-width: unset;
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 12px;
                padding-top: 0;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right-icon p {
                padding: 0;
                margin: 0;
                font-size: 14px;
                font-weight: 500;
                line-height: inherit;
                text-align: left;
                color: black;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right-logotypes {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                margin-top: 12px;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right-logotypes-title {
                font-size: 16px !important;
            }

            .exhibitor-generator.gr2 .exhibitor-generator__right-logo-item {
                max-width: 100px;
                margin: 5px;
            }

            .exhibitor-generator.gr2 .iti--allow-dropdown .iti__flag-container,
            .exhibitor-generator.gr2 .iti--separate-dial-code .iti__flag-container {
                left: 28px !important;
            }

            .exhibitor-generator.gr2 .gfield .iti.iti--allow-dropdown input {
                padding-left: 0 !important;
                border: 0;
            }

            .exhibitor-generator.gr2 .tabela-masowa {
                display: block !important;
            }

            .exhibitor-generator.gr2 .validation_message {
                color: #c00;
                font-size: 12px;
                display: block;
                width: 100%;
                text-align: center;
                margin-top: 5px;
            }

            .exhibitor-generator.gr2 .field-error {
                border: 1px solid red !important;
            }

            .exhibitor-generator.gr2 .gform_footer .tabela-masowa.hidden-important {
                display: none !important;
            }

            @media(max-width:960px) {
                .wpb_column:has(.exhibitor-generator.gr2),
                .row.limit-width.row-parent:has(.exhibitor-generator.gr2) {
                    padding-top: 0 !important;
                }
                .exhibitor-generator.gr2 .exhibitor-generator__left-badge,
                .exhibitor-generator.gr2 .exhibitor-generator__right {
                    width: 100%;
                    margin-top: 0;
                }
            }

            .exhibitor-generator-tech-support {
                display:none !important;
            }

            .exhibitor-generator.gr2 .gform_fields {
                display: flex;
                flex-direction: column;
                gap: 18px !important;
            }
            .exhibitor-generator.gr2 .exhibitor-generator__left-badge input[type="checkbox"] {
                min-width: 16px !important;
                height: 16px !important;
                border-radius: 50% !important;
                box-sizing: content-box;
                display: inline-block;
                font-size: 1em;
                -webkit-appearance: none;
                margin: 0;
                position: relative;
                text-align: center;
                line-height: normal;
                min-height: 0 !important;
                width: 16px;
                height: 16px;
                box-sizing: border-box;
                vertical-align: middle;
            }
            .exhibitor-generator.gr2 .gfield--type-consent label,
            .exhibitor-generator.gr2 .ginput_container_consent {
                display: inline !important;
                margin: 0 !important;
            }
            .exhibitor-generator.gr2 .gfield--type-consent {
                padding: 0;
                max-width: 90%;
                margin: auto;
                border-width: 0;
            }
            .exhibitor-generator.gr2 .gfield--type-consent .gfield_label {
                display: none !important;
                margin: 0;
            }
            .exhibitor-generator.gr2 .gfield--type-consent .gfield_consent_description {
                padding: 6px 8px;
                font-size: 12px;
                line-height: 15px;
                color: black !important;
            }
            .exhibitor-generator.gr2 .gform_footer.top_label {
                display: flex !important;
                justify-content: space-around !important;
            }
            .exhibitor-generator.gr2 input[type=checkbox]:checked:before,
            .exhibitor-generator.gr2 input[type=radio]:checked:before {
                box-sizing: border-box;
                font-family: "uncodeicon";
                margin: auto;
                position: absolute;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                line-height: 1.2em;
                font-size: 11px;
            }

            .exhibitor-generator.gr2 .gform_submission_error {
                font-size: 16px !important;
                margin: 18px auto !important;
                max-width: 90% !important;
                text-align: center !important;
            }
        </style>
    ';

    $exhibitor_id = $_GET['wystawca'] ?? '';
    $gr_data = $all_senders[$exhibitor_id]['type'] ?? 'gr2';

    $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
    $current_domain = $_SERVER['HTTP_HOST'];
    $current_fair_group = null;

    foreach ($pwe_groups_data as $item) {
        if ($item->fair_domain === $current_domain) {
            $current_fair_group = $item->fair_group;
            break;
        }
    }

    $output .= '
    <div class="exhibitor-generator gr2" data-group="' . $gr_data . '">
        <div class="exhibitor-generator__wrapper">
            <div class="exhibitor-generator__left-badge">
                <img class="exhibitor-generator__form-badge-top" src="/wp-content/plugins/pwe-media/media/badge_top.png">
                <div class="exhibitor-generator__left-content">
                    <div class="exhibitor-generator__left-header-badge">
                        <img class="exhibitor-generator__badge-logo" src="/doc/logo-color.webp" alt="Logo targów">
                        <span class="exhibitor-generator__vip-text">Vip Gold</span>
                    </div>
                    <div class="exhibitor-selector__container">';
                        if(isset($_GET['wystawca'])){
                            $output .='<img class="exhibitors-logo" src="' . $all_senders[$_GET['wystawca']]['logo'] . '">';
                        }
                        else {
                            $output .='
                                <form id="exhibitors-login-form" method="post" action="">
                                <select id="exhibitors_selector" name="exhibitor_id">';
                                    $output .='<option class="cat-exhibitor" val="" data-id="' . $cat_id . '">' . PWECommonFunctions::languageChecker('Firma Zapraszająca', 'Inviting Comapny') . '</option>';
                                    foreach($all_senders as $cat_id => $cat_value){
                                        if (!is_array($cat_value)) continue;
                                        $output .='<option class="cat-exhibitor" value="' . $cat_id . '" " data-type="' . $cat_value['type'] . '" data-id="' . $cat_id . '" data-nip="' . $cat_value["pass"] . '">' . $cat_value["name"] . '</option>';
                                    }
                                $output .='</select>
                                <input type="password" id="exhibitors_password" name="password" placeholder="' . PWECommonFunctions::languageChecker('Hasło', 'Password') . '" required />';
                                if (isset($_SESSION['login_error'])) {
                                    $output .= '<div id="login-error-message" style="color:red; text-align:center; font-weight:bold;">' . $_SESSION['login_error'] . '</div>';
                                    unset($_SESSION['login_error']);
                                }
                                $output .='<button type="submit" class="login-button btn-gold">' . PWECommonFunctions::languageChecker('Zatwierdź', 'Confirm') . '</button>
                                </form>';
                        }
                    $output .='</div>
                    [gravityform id="'. $generator_form_id .'" title="false" description="false" ajax="false"]
                    <div class="exhibitor-generator__left-footer-badge">
                        <img class="exhibitor-generator__badge-logo-pwe" src="/wp-content/plugins/pwe-media/media/ptak_black.png" alt="Logo PTAK WARSAW EXPO">
                    </div>
                    <img class="exhibitor-generator__form-badge-left" src="/wp-content/plugins/pwe-media/media/badge_left.png">
                    <img class="exhibitor-generator__form-badge-right" src="/wp-content/plugins/pwe-media/media/badge_right.png">
                    <img class="exhibitor-generator__form-badge-bottom" src="/wp-content/plugins/pwe-media/media/badge_bottom.png">
                </div>
            </div>
            <div class="exhibitor-generator__right">
                <div class="exhibitor-generator__right-wrapper">
                    <div class="exhibitor-generator__right-title">
                        <h3>' . PWECommonFunctions::languageChecker('WYGENERUJ</br>IDENTYFIKATOR VIP DLA SWOICH GOŚCI', 'GENERATE</br>A VIP INVITATION') . '</h3>';

                    $output .= '
                    </div>
                    <div class="exhibitor-generator__right-icons">
                        <h5>' . PWECommonFunctions::languageChecker('Identyfikator VIP uprawnia do:', 'The VIP invitation entitles you to:') . '</h5>
                        <div class="exhibitor-generator__right-icons-wrapper">
                            <div class="exhibitor-generator__right-icon">
                                <img src="/wp-content/plugins/pwe-media/media/generator-gosci-wystawcow/gr2/fast-track-icon.webp" alt="icon3">';
                                if($current_fair_group == 'gr2'){
                                    $output .=' <p>' . PWECommonFunctions::languageChecker('Fast track - szybkie wejście na targi dedykowaną bramką przez 3 dni', 'Fast track - fast entry to the fair through a dedicated gate for 3 days') . '</p>';
                                } else {
                                    $output .=' <p>' . PWECommonFunctions::languageChecker('Fast Track – szybkie wejście na targi dedykowaną bramką w dniu branżowym.', 'Fast Track – quick entry to the trade fair through a dedicated gate on the trade day.') . '</p>';
                                }
                                $output .='
                            </div>
                            <div class="exhibitor-generator__right-icon">
                                <img src="/wp-content/plugins/pwe-media/media/generator-gosci-wystawcow/gr2/vip-room-icon.webp" alt="icon1">
                                <p>' . PWECommonFunctions::languageChecker('VIP room ekskluzywna strefa dla gości wystawców, stworzona z myślą o swobodnych spotkaniach', 'VIP room exclusive area for exhibitors guests, created for casual meetings') . '</p>
                            </div>
                            <div class="exhibitor-generator__right-icon">
                                <img src="/wp-content/plugins/pwe-media/media/generator-gosci-wystawcow/gr2/conference-cion.webp" alt="icon2">
                                <p>' . PWECommonFunctions::languageChecker('Udział w konferencjach i warsztatach', 'Participation in conferences and workshops') . '</p>
                            </div>
                            <div class="exhibitor-generator__right-icon">
                                <img src="/wp-content/plugins/pwe-media/media/generator-gosci-wystawcow/gr2/concierge-icon.webp" alt="icon4">
                                <p>' . PWECommonFunctions::languageChecker('Obsługa concierge`a', 'Concierge service') . '</p>
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
                    </div>';

                    // $output .='
                    // <h5 class="exhibitor-generator__right-logotypes-title">' . PWECommonFunctions::languageChecker('Partnerzy PTAK WARSAW EXPO', 'PTAK WARSAW EXPO Partners') . '</h5>
                    // <div class="exhibitor-generator__right-logotypes">';
                    //     $files = glob($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/pwe-media/media/wspieraja-nas/*.{jpeg,jpg,png,webp,JPEG,JPG,PNG,WEBP}', GLOB_BRACE);
                    //     foreach ($files as $file_path) {
                    //         $file_url = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_path);
                    //         $output .= '
                    //             <div class="exhibitor-generator__right-logo-item">
                    //                 <img src="' . $file_url . '" alt="logo partnera">
                    //             </div>
                    //         ';
                    //     }
                    // $output .= '
                    // </div>
                    // ';

                    // Add a mass invite send button if not on a personal exhibitor page
                    if(get_locale() == "pl_PL" && isset($_SESSION['logged_in_exhibitor']) && (!isset($company_array['exhibitor_name'])  && PWEExhibitorVisitorGenerator::fairStartDateCheck()) || current_user_can('administrator')){
                        $output .= '<button type="button" class="tabela-masowa btn-gold">' . PWECommonFunctions::languageChecker('Wysyłka zbiorcza', 'Collective send') . '</button>';
                    }

                    // Add optional content to the page if available
                    if (!empty($generator_html_text_content)) {
                        $output .= '<div class="exhibitor-generator__right-text">' . $generator_html_text_content . '</div>';
                    }

                $output .= '
                </div>
            </div>
        </div>
    </div>';

    $is_logged_in = isset($_SESSION['logged_in_exhibitor']);
    $exhibitor_id = $is_logged_in ? $_SESSION['logged_in_exhibitor'] : '';

    $output .= '
    <script>
        jQuery(document).ready(function($){

            let exhibitor_name = $(".exhibitors-name").data("ex") ?? ' . json_encode($all_senders[$_GET['wystawca']]['name']) . ';
            let exhibitor_desc = `' . PWEExhibitorVisitorGenerator::$exhibitor_desc . '`;
            let exhibitor_stand = "' . PWEExhibitorVisitorGenerator::$exhibitor_stand . '";
            let submitBtn = $(`input[type="submit"]`);
            let massTable = $(".tabela-masowa");
            console.log("Typ nadawcy: ' . $gr_data . '");
            $(`input[placeholder="patron"]`).val("' . $gr_data . '");
            if ($("#exhibitors_selector").length) {
                submitBtn.addClass("button-blocked");
                massTable.addClass("123");

                $("#exhibitors_selector").on("change",function(){
                    switch($(this).val()){
                        case "Firma Zapraszająca":
                            $(`input[placeholder="Firma Zapraszająca"]`).closest(".gfield").hide();
                            $(`input[placeholder="Inviting Company"]`).closest(".gfield").hide();
                            submitBtn.addClass("button-blocked");
                            break;
                        case "Patron":
                            submitBtn.removeClass("button-blocked");
                            $(`input[placeholder="Firma Zapraszająca"]`).closest(".gfield").show();
                            $(`input[placeholder="Inviting Company"]`).closest(".gfield").show();
                            $(`input[placeholder="Firma Zapraszająca"]`).val("");
                            $(`input[placeholder="Inviting Company"]`).val("");
                            $(`input[placeholder="patron"]`).val("patron");
                            break;
                        default:
                            $(`input[placeholder="Firma Zapraszająca"]`).closest(".gfield").hide();
                            $(`input[placeholder="Inviting Company"]`).closest(".gfield").hide();
                            $(`input[placeholder="Firma Zapraszająca"]`).val($(this).val());
                            $(`input[placeholder="Inviting Company"]`).val($(this).val());
                            $(`input[placeholder="patron"]`).val("gr2");
                            submitBtn.removeClass("button-blocked");
                    }
                });
            }

            if ($("#exhibitors_selector").length) {
                $(".gform_body .gfield").hide();
                $(".gform_footer").hide();

                let checkInterval = setInterval(function() {
                    if ($(".gform_wrapper").length > 0) {
                        $(".gform_wrapper").hide();
                        clearInterval(checkInterval);
                    }
                }, 100); // sprawdzaj co 100 ms


                $("#exhibitors_selector").closest(".gfield").show();
            }

            $(".exhibitor_logo input").val("' . PWEExhibitorVisitorGenerator::$exhibitor_logo_url . '");
            $(".exhibitors_name input").val(exhibitor_name);
            $(".exhibitor_desc input").val(exhibitor_desc);
            $(".exhibitor_stand input").val(exhibitor_stand);

            $(`input[placeholder="Firma Zapraszająca"]`).val(exhibitor_name);
            $(`input[placeholder="Inviting Company"]`).val(exhibitor_name);

            $(`input[placeholder="Firma Zapraszająca"]`).on("input", function(){
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

            $("label").each(function() {
                let decoded = $(this).html()
                    .replace(/&lt;/g, "<")
                    .replace(/&gt;/g, ">")
                    .replace(/&amp;/g, "&");

                if (decoded.includes("<strong>") || decoded.includes("<em>") || decoded.includes("<span")) {
                    $(this).html(decoded);
                }
            });

            /* ---- WALIDACJA PUSTYCH PÓL W .gfield_visibility_visible ---- */
            const errorEmptyField = (event) => {
                event.preventDefault();

                $(event.target).closest("form").find(".gfield_description.validation_message.gfield_validation_message").remove();


                $(event.target)
                    .closest("form").find(".gfield_visibility_visible").not(`[data-conditional-logic="hidden"]`).find("input").each(function() {
                        const $input = $(this);
                        const getLocal = $("html").attr("lang");

                        let errorMessage = "To pole jest wymagane.";
                        if (getLocal != "pl-PL") {
                            errorMessage = "This field is required.";
                        }

                        const errorDiv = $("<span>")
                            .addClass("gfield_description validation_message gfield_validation_message")
                            .text(errorMessage);

                        if ($input.attr("type") === "checkbox") {
                            if (!$input.prop("checked")) {
                                $input.closest(".gfield").append(errorDiv);
                            }
                        } else {
                            if ($.trim($input.val()) === "") {
                                $input.closest(".gfield").append(errorDiv);
                            }
                        }
                    });

                if ($(".validation_message:not(.validation_message--hidden-on-empty)").length === 0 && ($(".gfield--type-phone").hasClass("gfield_visibility_hidden") || $(".pwe-phone-number").hasClass("gfield_visibility_hidden") || $(".pwe-phone-number").is(".gfield_visibility_visible[data-conditional-logic=\'hidden\']"))) {
                    $(event.target).closest("form").submit();
                }
            }

            $("form").has(".gfield_visibility_visible").find(".gform_button").on("click", function (event) {
                if (window.location.hostname !== "warsawexpo.eu") {
                    errorEmptyField(event);
                }
            });
            /* ---- KONIEC WALIDACJI ---- */

            const target = document.querySelector(".gform_footer");

            if (target) {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if ($(target).find(".gform-loader").length > 0) {
                            $(".tabela-masowa").addClass("hidden-important");
                        }
                    });
                });

                observer.observe(target, { childList: true, subtree: true });
            }
        });
    </script>
    ';
    if($generator_patron){
        $output .= '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                function getParamFromURL(param) {
                    const urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get(param);
                }

                const patronValue = getParamFromURL("p");

                if (patronValue) {
                    const labels = document.querySelectorAll("label");

                    labels.forEach(function(label) {

                    if (label.textContent.trim().toLowerCase() === "patron") {
                        const inputId = label.getAttribute("for");
                        if (inputId) {
                        const inputElement = document.getElementById(inputId);
                        if (inputElement) {
                            inputElement.value = patronValue;
                        }
                        }
                    }
                    });
                }
            });
        </script>
        ';
    }
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

    if ( current_user_can('administrator') ) {
        $output .= '
        <script>
            jQuery(document).ready(function($){
                $("#exhibitors_selector").on("change", function(){
                    const selected = $(this).find(":selected");
                    const pass = selected.data("nip") ?? "";
                    const passwordField = $("#exhibitors_password");

                    if ($(this).val() === "Firma Zapraszająca") {
                        passwordField.val("").prop("readonly", false).show();
                    } else if ($(this).val() === "Patron") {
                        passwordField.val("").prop("readonly", false).show();
                    } else {
                        passwordField.val(pass).prop("readonly", true).show();
                    }
                });
            });
        </script>
        ';
    }

    if(PWEExhibitorVisitorGenerator::senderFlowChecker() || current_user_can('administrator')){
        $output .='
        <div class="modal__element">
            <div class="inner">
                <span class="btn-close">x</span>';

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

                $output .='
                <input type="text" class="patron" value=""style="display:none;" >
                <input type="text" class="company" value="' . $exhibitor_name . '" disabled placeholder="'.
                    PWECommonFunctions::languageChecker(
                        <<<PL
                        Firma Zapraszająca (wpisz nazwę swojej firmy)
                        PL,
                        <<<EN
                        Inviting Company (your company's name)
                        EN
                    )
                .'"></input>
                <label class="mass_checkbox_label" style="display:none;">
                    <input type="checkbox" id="mass_exhibitor_badge" name="mass_exhibitor_badge" class="mass_checkbox" >
                    Brak uwzględnienia nazwy firmy na identyfikatorze
                </label>
                <div class="file-uloader">
                    <label for="fileUpload">
                    '.
                    PWECommonFunctions::languageChecker(
                        <<<PL
                        Wybierz plik z danymi
                        PL,
                        <<<EN
                        Select a file with data
                        EN
                    )
                    .'</label>
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
                    // if ($phone_field){
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
                    // }

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

add_filter('gform_pre_render', 'logged_in_exhibitor_fields_hidden');

add_filter('gform_notification', 'inject_qr_code_into_email', 10, 3);
function inject_qr_code_into_email($notification, $form, $entry) {
    // Sprawdź, czy wiadomość zawiera placeholder
    if (strpos($notification['message'], '{qr_feed_url}') !== false) {
        $feeds = GFAPI::get_feeds(null, $form['id']);

        foreach ($feeds as $feed) {
            if ($feed['addon_slug'] === 'qr-code') {
                $url = gform_get_meta($entry['id'], 'qr-code_feed_' . $feed['id'] . '_url');

                if ($url) {
                    $notification['message'] = str_replace('{qr_feed_url}', $url, $notification['message']);
                    break;
                }
            }
        }
    }

    return $notification;
}
