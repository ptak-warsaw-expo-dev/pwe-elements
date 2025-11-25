<?php

function SendData($all_entrys, $all_entrys_index, $lang){
    wp_remote_post(home_url('wp-content/plugins/custom-element/gf_integration/salesmanago_send_mass.php'), [
        'body' => [
            'secret' => hash_hmac('sha256', $_SERVER["HTTP_HOST"], PWE_API_KEY_5),
            'element' => 'exhibitor_inv',
            'entrys' => $all_entrys,
            'index' => $all_entrys_index,
            'lang' => $lang,
        ],
        'timeout' => 0.01,
        'blocking' => false,
    ]);
}

function SaveUploadedFile($input_name = 'input_logo') {
    if (!isset($_FILES[$input_name]) || $_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $upload_dir_info = wp_upload_dir();
    $target_dir = $upload_dir_info['basedir'] . '/generator-wystawcow';

    if (!file_exists($target_dir)) {
        wp_mkdir_p($target_dir);
    }

    $original_name = basename($_FILES[$input_name]['name']);
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $name_only = pathinfo($original_name, PATHINFO_FILENAME);
    $counter = 0;

    do {
        $filename = $counter === 0
            ? $original_name
            : $name_only . "($counter)." . $extension;
        $target_file = $target_dir . '/' . $filename;
        $counter++;
    } while (file_exists($target_file));

    if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $target_file)) {
        return $upload_dir_info['baseurl'] . '/generator-wystawcow/' . $filename;
    }

    return null;
}

// Check if request method is POST.
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Get wp-load.php location to import wordpress functions.
    $new_url = str_replace('private_html','public_html',$_SERVER["DOCUMENT_ROOT"]) .'/wp-load.php';
    if (file_exists($new_url)) {
        require_once($new_url);

        // Collect information for HASH token,
        $domain = $_SERVER["HTTP_HOST"];
        $secret_key = AUTH_KEY;
        $hash = hash_hmac('sha256', $domain, $secret_key);
        $response = false;

        //Chek token sended.
        if( $_POST['token'] ==  $hash){

            // Check if gravity forms class GFAPI is loded,
            if (class_exists('GFAPI')) {
                // Initialize variables.
                $data = '';
                // $data = $_POST['data'];
                $data = json_decode(stripslashes($_POST['data']), true);
                $all_forms = GFAPI::get_forms();
                $lang = $_POST['lang'];
                $fields = array();
                $all_entrys = array();
                $all_entrys_index = 0;
                $all_not_valid = array();
                $all_entrys_id = array();
                $full_form = '';
                $custom_form = $_POST['formId'];

                // Find "rejestracja gości wystawców" form ID with chosen language,
                $custom_form = $_POST['formId'];
                $form_id = $custom_form;

                $full_form = GFAPI::get_form($form_id);
                $all_fields = $full_form['fields'];

                $fields = [];
                foreach($all_fields as $field){
                    if(strpos(strtolower($field['label']), 'full name') !== false || strpos(strtolower($field['label']), 'nazwisko') !== false){
                        $fields['name'] = $field['id'];
                    } elseif(strpos(strtolower($field['label']), 'e-mail') !== false){
                        $fields['email'] = $field['id'];
                    } elseif(strpos(strtolower($field['label']), 'firma') !== false || strpos(strtolower($field['label']), 'inviting company') !== false){
                        $fields['company'] = $field['id'];
                    } elseif(strpos(strtolower($field['label']), 'telefon') !== false || strpos(strtolower($field['label']), 'phone') !== false){
                        $fields['phone'] = $field['id'];
                    } elseif($field['adminLabel'] == 'exhibitors_name'){
                        $fields['exhibitors_name'] = $field['id'];
                    } elseif($field['adminLabel'] == 'exhibitor_logo'){
                        $fields['exhibitor_logo'] = $field['id'];
                    } elseif($field['adminLabel'] == 'input_logo'){
                        $fields['input_logo'] = $field['id'];
                    } elseif($field['adminLabel'] == 'exhibitor_desc'){
                        $fields['exhibitor_desc'] = $field['id'];
                    } elseif($field['adminLabel'] == 'exhibitor_stand'){
                        $fields['exhibitor_stand'] = $field['id'];
                    } elseif($field['adminLabel'] == 'patron'){
                        $fields['patron'] = $field['id'];
                    }
                }  
                $input_exhibitors_logo = SaveUploadedFile();

                // Process entry data.
                foreach($data as $val){
                    $phoneVal =  $val['phone'] ?? '';
                    $entry = [
                        'form_id' => $form_id,
                        $fields['name'] => $val['name'],
                        $fields['email'] => $val['email'],
                        $fields['company'] => $_POST['company'],
                        $fields['exhibitors_name'] => ($_POST['exhibitor_name'] != '0') ? '' : $_POST['company'],
                        $fields['phone'] => $phoneVal,
                        $fields['patron'] => $_POST['patron'] ?? '',
                        $fields['exhibitor_stand'] => $_POST['exhibitor_stand'] ?? '',
                        $fields['input_logo'] => $input_exhibitors_logo ?? 'https://' . do_shortcode('[trade_fair_domainadress]') . '/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/logotyp_wystawcy.png',
                    ];
                        
                    if(!empty($_POST['exhibitor_desc']) && !empty($fields['exhibitor_desc'])) {
                        $entry[$fields['exhibitor_desc']] =  $_POST['exhibitor_desc'] ?? '';
                    }

                    $entry[$fields['exhibitor_logo']] = !empty($_POST['exhibitor_logo']) ?
                        $_POST['exhibitor_logo'] :
                        'https://' . do_shortcode('[trade_fair_domainadress]') . '/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/media/logotyp_wystawcy.png';

                    // Add entry to form.
                    $entry_id = GFAPI::add_entry($entry);

                    // Add entry ID to entry_id ARRAY.
                    if(filter_var(trim($val['email']), FILTER_VALIDATE_EMAIL)){
                        if(count($all_entrys) > 500){
                            // SendData($all_entrys, $all_entrys_index, $lang);
                            $all_entrys = array();
                            $all_entrys_index++;
                        }
                        $all_entrys[] = $val;
                        $all_entrys_id[] = $entry_id;
                    } else {
                        $all_not_valid[] = $entry_id;
                    }
                }

                if(count($all_entrys) > 0){
                    SendData($all_entrys, $all_entrys_index, $lang);
                }
            }

            // Check if any valid entry was added,
            if(count($all_entrys_id) > 0 ){
                global $wpdb;
                $table_name = $wpdb->prefix . 'mass_exhibitors_invite_query';

                // Chech if table exists, create if it doesn't.
                if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
                    $charset_collate = $wpdb->get_charset_collate();

                    $sql = "CREATE TABLE $table_name (
                        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        gf_entry_id BIGINT(20) UNSIGNED,
                        status VARCHAR(20) DEFAULT 'new',
                        PRIMARY KEY (id)
                    ) $charset_collate;";

                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($sql);
                }

                // Insert all valid entries IDs in to database as "new"
                foreach($all_entrys_id as $single_id){
                    $wpdb->insert(
                        $table_name,
                        array(
                            'gf_entry_id' => $single_id,
                        ),
                        array(
                            '%d',
                        )
                    );
                }

                // Insert all not valid entries IDs in to database as "error"
                foreach($all_not_valid as $single_id){
                    $wpdb->insert(
                        $table_name,
                        array(
                            'gf_entry_id' => $single_id,
                            'status' => "error"
                        ),
                        array(
                            '%d',
                            '%s'
                        )
                    );
                }

                $response = 'true';
            }
        }

        // Send response back to exhibitors generator page
        echo json_encode($response);
    } else {

        // Wrong token send back 401 - Acces Denied
        echo 'error code 401';
        exit;
    }
} else {
    // Wrong request method send back 401 - Acces Denied
    echo 'error code 401';
    exit;
}