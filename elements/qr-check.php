<?php

/**
 * Class PWElementQRChekcer
 * Combines PWElementQRChekcer and PWElementHeaderNew functionality based on checkbox selection.
 */
class PWElementQRChekcer  extends PWElements {

    /**
     * Constructor method.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Define Visual Composer parameters.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Show QR Folders', 'pwelement'),
                'param_name' => 'pwe_show_qr',
                'save_always' => true,
                'value' => array(__('True', 'pwelement') => 'true',),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementQRChekcer',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Prefix', 'pwelement'),
                'param_name' => 'prefix',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementQRChekcer',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Id forms', 'pwelement'),
                'param_name' => 'form_ids',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementQRChekcer',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Random numbers', 'pwelement'),
                'param_name' => 'random_strings',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementQRChekcer',
                ),
            ),
        );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     */
    public static function output($atts) {
        extract( shortcode_atts( array(
            'form_ids' => '',
            'random_strings' => '',
            'prefix' => '',
            'pwe_show_qr' => '',
        ), $atts ));

        if ($pwe_show_qr === 'true') {
            // Wyświetlanie widoku QR folderów
            $mainFolderPath = $_SERVER['DOCUMENT_ROOT'] . '/qr';

            $output = '<style>
              .pwelement_'. self::$rnd_id .' {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                flex-direction:column !important;
              }

              .pwelement_'. self::$rnd_id .' #folderMenu {
                  display: flex !important;
                  flex-wrap: wrap !important;
                  gap: 10px !important;
                  justify-content: center;
              }
              .pwelement_'. self::$rnd_id .' #folderMenu button {
                  padding: 5px 10px !important;
                  border-radius: 10px !important;
                  background: black !important;
                  color: white !important;
                  min-width: 100px !important;
              }

              .pwelement_'. self::$rnd_id .' .folder div {
                  display: flex;
                  text-align: center;
                  margin: 10px;
                  justify-content: center;
                  flex-direction: column;
                  align-items: center;
              }
            </style>';
            $output .= '<h1>Wybierz folder</h1>';

            if (is_dir($mainFolderPath)) {
                $folders = scandir($mainFolderPath);
                $output .= '<div id="folderMenu">';
                foreach ($folders as $folder) {
                    if ($folder !== '.' && $folder !== '..') {
                        $folderId = htmlspecialchars($folder);
                        $output .= "<button onclick='showFolder(\"$folderId\")'>$folder</button> ";
                    }
                }
                $output .= '</div>';

                foreach ($folders as $folder) {
                    if ($folder !== '.' && $folder !== '..') {
                        $folderPath = $mainFolderPath . '/' . $folder;
                        if (is_dir($folderPath)) {
                            $folderId = htmlspecialchars($folder);
                            $output .= "<div class='folder' id='$folderId' style='display: none;'>";
                            $output .= "<h2>Folder: $folder</h2>";
                            $files = scandir($folderPath);
                            $imageCounter = 1;

                            foreach ($files as $file) {
                                if (pathinfo($file, PATHINFO_EXTENSION) === 'png') {
                                    $filePath = "/qr/$folder/$file";
                                    $output .= "<div style='margin: 125px 0;'>";
                                    $output .= "<img src='$filePath' alt='$file' style='width: 350px;'><br>";
                                    $output .= "<span>Zdjęcie $imageCounter</span>";
                                    $output .= "</div>";
                                    $imageCounter++;
                                }
                            }

                            $output .= "</div>";
                        }
                    }
                }
            } else {
                $output .= "<p>Folder 'qr' nie istnieje w katalogu głównym (public_html).</p>";
            }

            $output .= "
            <script>
                function showFolder(folderId) {
                    var folders = document.querySelectorAll('.folder');
                    folders.forEach(function(folder) {
                        folder.style.display = 'none';
                    });
                    var selectedFolder = document.getElementById(folderId);
                    if (selectedFolder) {
                        selectedFolder.style.display = 'block';
                    }
                }
            </script>";

        } else {
            // Wyświetlanie wpisów formularzy
            $form_ids = !empty($form_ids) ? explode(',', $form_ids) : [];
            $random_strings = !empty($random_strings) ? explode(',', $random_strings) : [];
            $entries_by_form = [];

            foreach ($form_ids as $index => $form_id) {
                $form = GFAPI::get_form($form_id);
                $form_title = isset($form['title']) ? $form['title'] : "Form ID: $form_id";
                $formatted_form_id = sprintf('%03d', $form_id);
                $random_string = isset($random_strings[$index]) ? $random_strings[$index] : '';
                $search_criteria = array();
                $entries = GFAPI::get_entries($form_id, $search_criteria);

                if (count($entries) <= 9) {
                    $entries_by_form[$form_title] = $entries;
                } else {
                    $first_entries = array_slice($entries, 0, 3);
                    $middle_entries = array_slice($entries, floor(count($entries) / 2) - 1, 3);
                    $last_entries = array_slice($entries, -3, 3);
                    $entries_by_form[$form_title] = array_merge($first_entries, $middle_entries, $last_entries);
                }

                $entries_by_form[$form_title]['formatted_form_id'] = $formatted_form_id;
                $entries_by_form[$form_title]['random_string'] = $random_string;
            }
            $output .= '<div class="entries-output">';
            foreach ($entries_by_form as $form_title => $entries) {
                $formatted_form_id = $entries['formatted_form_id'];
                $random_string = $entries['random_string'];
                unset($entries['formatted_form_id'], $entries['random_string']);
                $output .= "<h3>\"$form_title\"</h3><ul>";
                foreach ($entries as $entry) {
                    $entry_id = isset($entry['id']) ? $entry['id'] : 'Unknown';
                    $output .= '<li>' . $prefix . $formatted_form_id . $entry_id . $random_string . $entry_id . '</li>';
                }
                $output .= '</ul>';
            }
            $output .= '</div>';
        }

        return $output;
    }
}
