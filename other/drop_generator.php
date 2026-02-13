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
                
                foreach ($all_forms as $key => $value) {
                    switch (true) {
                        case (preg_match('/^\(.{4}\) Rejestracja PL(\s?\(Branzowe\))?$/i', $value['title'])) :
                            $form['def-pl'] = $value['id'];
                            break;
                        
                        case (preg_match('/^\(.{4}\) Rejestracja EN(\s?\(Branzowe\))?$/i', $value['title'])) :
                            $form['def-en'] = $value['id'];
                            break;
                    }
                }

                if(strtolower($data['options']) == 'pl'){
                    $form = GFAPI::get_form($form['def-pl']);
                } else {
                    $form = GFAPI::get_form($form['def-en']);
                }
                
                foreach ($data[$domain] as $id => $value){
                    foreach($all_forms as $form_check){
                        if(strpos(strtolower($form_check['title']), 'rejestracja') !== false ){
                            $entries = GFAPI::get_entries($form_check['id']);
                            foreach($entries as $entry_check){
                                foreach($entry_check as $id => $field_check){
                                    if(is_numeric($id) && $field_check == $value[0]){
                                        $report[$domain_raport]['entry_id'][] = 'OLD_entry_' . $entry_check['id'] . ' ' . $value[0] . ' ' . $value[1] ;
                                        continue 4;
                                    }
                                }
                            }
                        }
                    }
                    $entry = [];
                    $entry['form_id'] = $form['id'];

                    foreach ($form['fields'] as $key){
                        if(strpos(strtolower($key['label']), 'email') !== false ){
                            $entry[$key['id']] = $value[0];
                        } elseif (strpos(strtolower($key['label']), 'telefon') !== false || strpos(strtolower($key['label']), 'phone') !== false){
                            $entry[$key['id']] = $value[1];
                        } elseif (strpos(strtolower($key['label']), 'utm') !== false ){
                            $entry[$key['id']] = 'utm_source=spady_lead&drop_kanal=' . $value[2];
                        }
                    }

                    $report[$domain_raport]['new_entry'][] = 'NEW ' . $value[0] . ' ' . $value[1] ; 
                    
                    $entry_id = GFAPI::add_entry($entry);

                    $qr_feeds = GFAPI::get_feeds( NULL, $form[ 'id' ]);
                    foreach($qr_feeds as $feed){
                        if (gform_get_meta($entry_id, 'qr-code_feed_' . $feed['id'] . '_url')){
                            $qr_code_id = $feed['id'];
                        }   
                    }
                    $meta_key_url = gform_get_meta($entry_id, 'qr-code_feed_' . $qr_code_id . '_url');

                    if(strpos($meta_key_url, 'http://') !== false){
                        $meta_key_url = str_replace('http:', 'https:', $meta_key_url);
                    }

                    $meta_key_image = '<img data-imagetype="External" src="' . $meta_key_url . '" width="200">';
                    
                    foreach($form["notifications"] as $id => $key){
                        if($key["isActive"]){
                            if (strpos($form["notifications"][$id]["message"], '{qrcode-url-' . $qr_code_id . '}') !== false){
                                $form["notifications"][$id]["message"] = str_replace('{qrcode-url-' . $qr_code_id . '}', $meta_key_url , $key["message"]);
                            } else {
                                $form["notifications"][$id]["message"] = str_replace('{qrcode-image-' . $qr_code_id . '}', $meta_key_image, $key["message"]);
                            }
                        }
                    }
                    
                    if ($entry_id && !is_wp_error($entry_id)) {
                        try {
                            GFAPI::send_notifications($form, $entry);
                        } catch (Exception $e) {
                            $report['dane_do_zapisu'] .= 'Błąd send_notifications: ' . $e->getMessage();
                        }
                    } else {
                        $dane_do_zapisu .= 'Błąd dodawania wpisu do Gravity Forms.';
                    }

                }
                $json_response = json_encode($report);
                echo $json_response;

            } else {
                echo 'WordPress problems contact web developers code - "WORDPRESS GF ERROR '.$domain.' ".';
                echo'<br><br>';
                http_response_code(404);
            }
        } else {
            echo 'ivalide token contact web developers code - "Word Press Function PHP error '.$domain.' ".';
            echo'<br><br>';
            http_response_code(404);
        }
    } else {
        echo 'ivalide token contact web developers code - "INVALID TOKEN '.$domain.' ".';
        echo'<br><br>';
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