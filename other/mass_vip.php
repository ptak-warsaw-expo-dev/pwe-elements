<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $domain = $_SERVER["HTTP_HOST"];
    $secret_key = '^GY0ZlZ!xzn1eM5';
    $hash = hash_hmac('sha256', $domain, $secret_key);
    $response = "false";

    if( $_POST['token'] ==  $hash){
        $new_url = str_replace('private_html','public_html',$_SERVER["DOCUMENT_ROOT"]) .'/wp-load.php';

        if (file_exists($new_url)) {
            require_once($new_url);
            if (class_exists('GFAPI')) {
                $data = '';
                $data = $_POST['data'];
                $all_forms = GFAPI::get_forms();
                $lang = $_POST['lang'];
                $fields = array();
                $all_entrys = array();
                $all_not_valid = array();
                $all_entrys_id = array();
                $full_form = '';
                $custom_form ='';
                $custom_form = $_POST['formId'];


                foreach($all_forms as $form){
                    // if(strpos(strtolower($form['title']), ('rejestracja gości wystawców ' . $lang)) !== false){
                        $form_id = $custom_form;
                        $all_fields = $form['fields'];
                        $full_form = $form;

                        foreach($all_fields as $field){
                            if(strpos(strtolower($field['label']), 'name') !== false || strpos(strtolower($field['label']), 'nazwisko') !== false){
                                $fields['name'] = $field['id'];
                            } elseif(strpos(strtolower($field['label']), 'e-mail') !== false){
                                $fields['email'] = $field['id'];
                            } elseif(strpos(strtolower($field['label']), 'firma') !== false || strpos(strtolower($field['label']), 'company') !== false){
                                $fields['company'] = $field['id'];
                            }
                        // }
                        break;
                    }
                }

                foreach($data as $val){
                    $entry = [
                        'form_id' => $form_id,
                        $fields['name'] => $val['name'],
                        $fields['email'] => $val['email'],
                        $fields['company'] => $_POST['company'],
                    ];

                    $entry_id = GFAPI::add_entry($entry);

                    if(filter_var(trim($val['email']), FILTER_VALIDATE_EMAIL)){
                        $all_entrys_id[] = $entry_id;
                        $all_entrys[] = true;
                    } else {
                        $all_not_valid[] = $entry_id;
                        $all_entrys[] = false;
                    }
                }
            }

            if(count($all_entrys_id) > 0 ){
                global $wpdb;
                // Ustaw nazwę tabeli
                $table_name = $wpdb->prefix . 'mass_exhibitors_invite_query';

                // Sprawdź, czy tabela istnieje
                if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
                    // Tabela nie istnieje, więc ją tworzymy
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
        echo json_decode($response);
    } else {
        echo json_decode('error code 401');
        exit;
    }
} else {
    echo json_decode('error code 401');
    exit;
}