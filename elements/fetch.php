<?php
header('Content-Type: application/json');
$report['status'] = 'false';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SERVER['HTTP_AUTHORIZATION']) || $_SERVER['HTTP_AUTHORIZATION'] !== 'qg58yn58q3yn5v') {
        http_response_code(403);
        echo json_encode(["message" => "Brak autoryzacji"]);
        exit;
    }

    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['formName'])) {
        http_response_code(400);
        echo json_encode(["message" => "Niepoprawne dane"]);
        exit;
    }

    function getFormIdByTitle($formName) {
        $forms = GFAPI::get_forms();
        foreach ($forms as $form) {
            if ($form['title'] === $formName) {
                return $form['id'];
            }
        }
        return null;
    }

    $form_id = getFormIdByTitle($data['formName']);
    $direction = $data['direction'];

    if (!$form_id) {
        echo json_encode(["message" => "Nie znaleziono formularza o podanej nazwie"]);
        exit;
    }

    $form = GFAPI::get_form($form_id);

    if (!$form) {
        echo json_encode(["message" => "Nie znaleziono formularza"]);
        exit;
    }

    if ($direction == "registration") {
        $entry_id = $_SESSION['pwe_reg_entry']['entry_id'];
    } else {
        $entry_id = $_SESSION['pwe_exhibitor_entry']['entry_id'];
    }

    $entry = GFAPI::get_entry($entry_id);
    if (is_wp_error($entry)) {
        echo json_encode(["message" => "Nie znaleziono wpisu"]);
        exit;
    }

    function getFieldIdByAdminLabel($form, $admin_label) {
        foreach ($form['fields'] as $field) {
            if (isset($field->adminLabel) && $field->adminLabel === $admin_label) {
                return $field->id;
            }
        }
        return null;
    }

    function createField($form_id, $admin_label, $label) {
        $new_field = new GF_Field_Text(); // Tworzymy pole tekstowe
        $new_field->label = $label;
        $new_field->adminLabel = $admin_label;
        $new_field->type = 'text'; // Nadal pole tekstowe
        $new_field->visibility = 'hidden'; // Ukrycie pola
        $new_field->isRequired = false;

        $form = GFAPI::get_form($form_id);
        $form['fields'][] = $new_field;
        GFAPI::update_form($form);

        return $new_field->id;
    }


    $field_labels = [
        'name' => 'Imię i nazwisko',
        'street' => 'Ulica',
        'house' => 'Numer domu',
        'apartment' => 'Numer lokalu',
        'post' => 'Kod pocztowy',
        'city' => 'Miasto'
    ];

    if ($direction == "registration") {
        $fields_to_update = [
            'name' => 'name',
            'street' => 'street',
            'house' => 'house',
            'apartment' => 'apartment',
            'post' => 'post',
            'city' => 'city'
        ];
    } else {
        $fields_to_update = [
            'name' => 'name',
            'area' => 'area',
            'company' => 'company',
            'nip' => 'nip',
        ];
    }

    foreach ($fields_to_update as $admin_label => $key) {
        if (!empty($data[$key])) {
            $field_id = getFieldIdByAdminLabel($form, $admin_label);

            // Jeśli direction == "registration", tworzymy brakujące pole
            if (!$field_id && $direction == "registration") {
                $field_id = createField($form_id, $admin_label, $field_labels[$admin_label] ?? ucfirst($admin_label));
            }

            // Aktualizacja wartości pola tylko jeśli pole istnieje
            if ($field_id) {
                GFAPI::update_entry_field($entry_id, $field_id, $data[$key]);
            }
        }
    }

    if (is_wp_error($result)) {
        echo json_encode(["message" => "Błąd aktualizacji"]);
    } else {
        if ($direction == "registration") {
            unset($_SESSION['pwe_reg_entry']);
        } else {
            unset($_SESSION['pwe_exhibitor_entry']);
        }

        if ($direction !== "registration") {
            $entry = GFAPI::get_entry($entry_id);

            $notifications = $form['notifications'];
            foreach ($form["notifications"] as $id => &$key) {
                $key['isActive'] = in_array($key['name'], ['Admin Notification Potwierdzenie']);
            }
            GFAPI::send_notifications($form, $entry);
        }

        echo json_encode(["message" => "Dane zaktualizowane"]);
    }

    if (!empty($entry_id)){
        wp_remote_post(home_url('wp-content/plugins/custom-element/action_handler.php'), [
            'body' => [
                'element' => 'gform_after_submission',
                'entry_id' => $entry_id,
                'url' => null
            ],
            'timeout' => 0.01,
            'blocking' => false,
        ]);
    }
}
?>
