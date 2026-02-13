<?php
// Token verification argument
$token = $argv[1] ?? null;

// Load wp-config to get constants
require_once('/home/glasstec/domains/mr.glasstec.pl/public_html/wp-config.php');

// Token verification key
$secret_key = defined('PWE_API_KEY_2') ? PWE_API_KEY_2 : null;

if ($token === $secret_key) {
    // Log start
    file_put_contents(
        '/home/glasstec/domains/mr.glasstec.pl/public_html/cron_fairs.log',
        "[" . date('Y-m-d H:i:s') . "] CRON START\n",
        FILE_APPEND
    );

    $document_root = '/home/glasstec/domains/mr.glasstec.pl/private_html';
    $wp_load_url = str_replace('private_html','public_html', $document_root) . '/wp-load.php';

    if (!file_exists($wp_load_url)) {
         error_log("Could not load WordPress at: $wp_load_url\n");
        exit(1);
    }

    require_once($wp_load_url);

    $get_fairs_data = function () {

        $pwe_fairs = PWECommonFunctions::get_database_fairs_data();
        $pwe_fairs_translations = PWECommonFunctions::get_database_translations_data();

        $fairs_data = ["fairs" => []];

        if (!empty($pwe_fairs) && is_array($pwe_fairs)) {
            foreach ($pwe_fairs as $fair) {
                $domain = $fair->fair_domain;
                
                $fair_data = PWECommonFunctions::generate_fair_data($fair);
                
                $fairs_data["fairs"][$domain] = $fair_data;
            }
        }

        if (!empty($pwe_fairs_translations) && is_array($pwe_fairs_translations)) {
            foreach ($pwe_fairs_translations as $fair_translations) {
                $domain = $fair_translations['fair_domain'];

                $fair_translation_data = PWECommonFunctions::generate_fair_translation_data($fair_translations);
                
                unset($fair_translation_data['domain']);
                
                if (isset($fairs_data["fairs"][$domain])) {
                    $fairs_data["fairs"][$domain] = array_merge($fairs_data["fairs"][$domain], $fair_translation_data);
                }
            }
        }

        return $fairs_data;
    };

    // Get all the data about the fair
    $all_fairs_data = $get_fairs_data();

    // Path to JSON file
    $json_file = '/home/glasstec/domains/mr.glasstec.pl/public_html/doc/pwe-data.json';

    // Save all data to a JSON file
    if (file_put_contents($json_file, json_encode($all_fairs_data, JSON_PRETTY_PRINT)) === false) {
        error_log("Nie udało się zapisać pliku JSON w $json_file\n");
    }

    var_dump("Dane są zaktualizowane i zapisane do backupu!!!");
}