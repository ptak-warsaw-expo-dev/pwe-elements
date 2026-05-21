<?php 
if ($_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Implement secure password handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
    $domain_raport = $_SERVER ["HTTP_HOST"];
    $domain = 'https://' . $domain_raport . '/';
    $new_url = str_replace('private_html','public_html',$_SERVER["DOCUMENT_ROOT"]) .'/wp-load.php';
    $forms = array();
    $form_id = '';
    $report = array();
    

    if (validateToken($token, $domain)) {

        if (file_exists($new_url)) {

            require_once($new_url);

            if (class_exists('GFAPI')) {

                $all_forms = GFAPI::get_forms();

                $form = [];

                foreach ($all_forms as $value) {
                    switch (true) {

                        case (preg_match('/^\(.{4}\) Rejestracja PL(\s?\(Branzowe\))?$/i', $value['title'])):
                            $form['def-pl'] = $value['id'];
                            break;

                        case (preg_match('/^\(.{4}\) Rejestracja EN(\s?\(Branzowe\))?$/i', $value['title'])):
                            $form['def-en'] = $value['id'];
                            break;
                    }
                }

                $form = (strtolower($data['options']) === 'pl')
                    ? GFAPI::get_form($form['def-pl'])
                    : GFAPI::get_form($form['def-en']);

                $qr_feeds_cache = GFAPI::get_feeds(null, $form['id']);

                foreach ($data[$domain] as $id => $value) {

                    // duplicate check
                    foreach ($all_forms as $form_check) {

                        if (strpos(strtolower($form_check['title']), 'rejestracja') === false) {
                            continue;
                        }

                        $entries = GFAPI::get_entries($form_check['id']);

                        foreach ($entries as $entry_check) {
                            foreach ($entry_check as $field_check) {

                                if (!is_numeric($field_check)) {
                                    continue;
                                }

                                if ($field_check == $value[0]) {

                                    $report[$domain_raport]['entry_id'][] =
                                        'OLD_entry_' . $entry_check['id'] . ' ' . $value[0] . ' ' . $value[1];

                                    continue 4;
                                }
                            }
                        }
                    }

                    // create entry
                    $entry = ['form_id' => $form['id']];

                    foreach ($form['fields'] as $key) {

                        $label = strtolower($key['label']);

                        if (strpos($label, 'email') !== false) {
                            $entry[$key['id']] = $value[0];
                        } elseif (strpos($label, 'telefon') !== false || strpos($label, 'phone') !== false) {
                            $entry[$key['id']] = $value[1];
                        } elseif (strpos($label, 'utm') !== false) {
                            $entry[$key['id']] = 'utm_source=spady_lead&drop_kanal=' . $value[2];
                        }
                    }

                    $report[$domain_raport]['new_entry'][] = 'NEW ' . $value[0] . ' ' . $value[1];

                    $entry_id = GFAPI::add_entry($entry);

                    if (is_wp_error($entry_id)) {
                        continue;
                    }

                    // trigger GF hooks
                    $full_entry = GFAPI::get_entry($entry_id);

                    if (!is_wp_error($full_entry)) {
                        do_action('gform_after_submission', $full_entry, $form);
                    }

                    // QR resolution
                    $qr_code_id = null;
                    $pwe_qr_feed_name = null;
                    $meta_key_url = '';
                    $meta_key_image = '';

                    $pwe_qr_url = gform_get_meta($entry_id, 'pwe_qr_code_url');

                    if (!empty($pwe_qr_url)) {

                        foreach ($qr_feeds_cache as $feed) {
                            if (($feed['addon_slug'] ?? '') === 'pwe_qr') {
                                $pwe_qr_feed_name = $feed['meta']['feedName'] ?? null;
                                break;
                            }
                        }

                        $meta_key_url = $pwe_qr_url;
                        $meta_key_image = '<img data-imagetype="External" src="' . $meta_key_url . '" width="200">';

                    } else {

                        foreach ($qr_feeds_cache as $feed) {

                            $feed_id = $feed['id'] ?? null;
                            if (!$feed_id) {
                                continue;
                            }

                            $url = gform_get_meta($entry_id, 'qr-code_feed_' . $feed_id . '_url');

                            if (!empty($url)) {

                                $qr_code_id = $feed_id;
                                $meta_key_url = $url;
                                $meta_key_image = '<img data-imagetype="External" src="' . $url . '" width="200">';
                                break;
                            }
                        }
                    }

                    if (!empty($meta_key_url) && strpos($meta_key_url, 'http://') !== false) {
                        $meta_key_url = str_replace('http:', 'https:', $meta_key_url);
                        $meta_key_image = '<img data-imagetype="External" src="' . $meta_key_url . '" width="200">';
                    }

                    // notifications
                    foreach ($form["notifications"] as $nid => $notif) {

                        if (empty($notif["isActive"])) {
                            continue;
                        }

                        $message = $notif["message"];

                        // PWE QR
                        if (!empty($pwe_qr_url)) {

                            if (!empty($pwe_qr_feed_name)) {

                                $message = str_replace(
                                    '{pwe_qr_url name=' . $pwe_qr_feed_name . '}',
                                    $meta_key_url,
                                    $message
                                );

                                $message = str_replace(
                                    '{pwe_qr_img name=' . $pwe_qr_feed_name . '}',
                                    $meta_key_image,
                                    $message
                                );
                            }

                            $message = str_replace('{pwe_qr_url}', $meta_key_url, $message);
                            $message = str_replace('{pwe_qr_img}', $meta_key_image, $message);

                        } else {

                            // fallback QR-code
                            if (!empty($qr_code_id)) {

                                $message = str_replace(
                                    '{qrcode-url-' . $qr_code_id . '}',
                                    $meta_key_url,
                                    $message
                                );

                                $message = str_replace(
                                    '{qrcode-image-' . $qr_code_id . '}',
                                    $meta_key_image,
                                    $message
                                );
                            }
                        }

                        $form["notifications"][$nid]["message"] = $message;
                    }

                    try {
                        GFAPI::send_notifications($form, $entry);
                    } catch (Exception $e) {
                        $report['dane_do_zapisu'] .= 'Błąd send_notifications: ' . $e->getMessage();
                    }
                }

                echo json_encode($report);

            } else {
                echo 'WORDPRESS GF ERROR ' . $domain;
                http_response_code(404);
            }

        } else {
            echo 'INVALID PATH ' . $domain;
            http_response_code(404);
        }

    } else {
        echo 'INVALID TOKEN ' . $domain;
        http_response_code(401);
        exit;
    }
}

function generateToken($domain) {
    $secret_key = '^GY0ZlZ!xzn1eM5';
    return hash_hmac('sha256', $domain, $secret_key);
}

// Function to validate a token
function validateToken($token, $domain) {
    $expected_token = generateToken($domain);
    return hash_equals($expected_token, $token);
}