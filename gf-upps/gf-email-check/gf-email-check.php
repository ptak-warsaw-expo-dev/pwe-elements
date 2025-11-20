<?php

$new_url = str_replace('private_html','public_html',$_SERVER["DOCUMENT_ROOT"]) .'/wp-load.php';
require_once($new_url);

if (isset($_POST['email_value']) || isset($_POST['phone_value'])) {

    $erorreo = '';
    $email_id = $_POST['email_id'];
    $email_value = sanitize_email($_POST['email_value']);
    $phone_id = $_POST['phone_id'];
    $phone_value = $_POST['phone_value'];
    $form_id = $_POST['form_id'];

    $email_criteria = [
        'field_filters' => [
            [
                'key'   => $email_id,
                'value' => $email_value
            ]
        ]
    ];

    $phone_criteria = [
        'field_filters' => [
            [
                'key'   => $phone_id,
                'value' => $phone_value
            ]
        ]
    ];

    $email_entries = GFAPI::get_entries($form_id, $email_criteria);
    $phone_entries = GFAPI::get_entries($form_id, $phone_criteria);
    
    if (!empty($email_entries) && !empty($phone_entries) ) {
        echo json_encode(['email' => true, 'phone' => true]);
    } else if (empty($email_entries) && !empty($phone_entries) ) {
        echo json_encode(['email' => false, 'phone' => true]);
    } else if (!empty($email_entries) && empty($phone_entries) ) {
        echo json_encode(['email' => true, 'phone' => false]);
    } else{
        echo json_encode(['exists' => false]);
    }
}
