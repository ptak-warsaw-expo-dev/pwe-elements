<?php 

/**
 * Class PWBadgeElement
 * Extends PWElements class and defines a custom Visual Composer element.
 */
class PWBadgeElement extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * 
     * @return array @element_output.
     */
    public static function initElements() {
        $element_output = array(
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Badge Form ID', 'pwelement'),
                'param_name' => 'badge_form_id',
                'value' => '',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWBadgeElement',
                )
            ),
        );
        return $element_output;
    }

    /**
     * Static method to generate mass bages.
     * 
     * @param number @badge_form_id form id
     *
     * The method processes the following steps:
     * 1. Checks if the form was submitted and necessary inputs are present.
     * 2. Updates the URL of the confirmation message.
     * 3. Collects data from the form inputs and prepares it for badge generation.
     * 4. Generates multiple badges adding to forms and opens each badge URL in a new window for download.w.
     */
    public static function massGenerator($badge_form_id) {

        // 1. Checks if the form was submitted and necessary inputs are present.
        if (
            isset($_POST["gform_submit"]) &&
            $_POST["gform_submit"] == $badge_form_id &&
            !empty($_POST['input_6']) &&
            isset($_POST['input_3'])
        ) {

            // 2. Updates the URL of the confirmation message.
            echo '<script>
            jQuery(function ($) {
                const gfMessage = $(".gform_confirmation_message a");
                if (gfMessage.length) {
                    const urlMessage = gfMessage.eq(0).attr("href") + "?parametr=masowy";
                    gfMessage.eq(0).attr("href", urlMessage);
                    gfMessage.eq(1).hide();
                    window.open(gfMessage.eq(1).attr("href"));
                }
            });
            </script>';

            // base entry data
            $multi_badge = [
                'form_id' => $badge_form_id
            ];

            // 3. Collects data from the form inputs and prepares it for badge generation.
            foreach ($_POST as $key => $value) {
                if (strpos(strtolower($key), 'input') !== false) {
                    preg_match_all('/\d+/', $key, $id);
                    $field_id = $id[0][0];
                    $multi_badge[$field_id] = $value;
                }
            }

            $count = (int) ($_POST['multi_send'] ?? 1);

            // 4. Generates multiple badges adding to forms and opens each badge URL in a new window for download.
            for ($i = 1; $i < $count; $i++) {

                // create entry for each badge
                $entry_id = GFAPI::add_entry($multi_badge);

                if (is_wp_error($entry_id)) {
                    continue;
                }

                // force processing
                $entry = GFAPI::get_entry($entry_id);

                if (!is_wp_error($entry)) {
                    do_action(
                        'gform_after_submission',
                        $entry,
                        ['id' => $badge_form_id]
                    );
                }

                $qr_code_url = '';
                $tries = 0;

                while ($tries < 10) {

                    // priority 1: PWE QR
                    $pwe_url = gform_get_meta($entry_id, 'pwe_qr_code_url_encoded');

                    if (!empty($pwe_url)) {
                        $qr_code_url = $pwe_url;
                        break;
                    }

                    // priority 2: QR CODE FEEDS
                    $qr_feeds = GFAPI::get_feeds(null, $badge_form_id, 'qr-code');

                    if (!is_wp_error($qr_feeds)) {
                        foreach ($qr_feeds as $feed) {

                            $feed_id = $feed['id'] ?? null;
                            if (!$feed_id) {
                                continue;
                            }

                            $url = gform_get_meta(
                                $entry_id,
                                'qr-code_feed_' . $feed_id . '_url'
                            );

                            if (!empty($url)) {
                                $qr_code_url = $url;
                                break 2;
                            }
                        }
                    }

                    usleep(200000);
                    $tries++;
                }

                if (empty($qr_code_url)) {
                    error_log("MassGenerator: missing QR for entry " . $entry_id);
                    continue;
                }

                // generate badge URL with query parameters
                $badge_url =
                    'https://warsawexpo.eu/assets/badge/local/loading.html'
                    . '?category=' . $multi_badge[3]
                    . '&getname=' . $multi_badge[1]
                    . '&firma=' . $multi_badge[2]
                    . '&qrcode=' . $qr_code_url;

                echo '<script>window.open("' . $badge_url . '");</script>';
            }
        }
    }

    /**
     * Static method to generate mass bages.
     * 
     * @param number @badge_form_id form id
     *
     * The method processes the following steps:
     * 1. Checks if the form was submitted and necessary inputs are present.
     * 2. Updates the URL of the confirmation message.
     * 3. Collects data from the form inputs and prepares it for badge generation.
     * 4. Generates multiple badges adding to forms and opens each badge URL in a new window for download.w.
     */
    public static function qrOnlyDownload($badge_form_id) {

        // 1. Checks if the form was submitted and necessary inputs are present.
        if (
            !isset($_POST["gform_submit"]) ||
            $_POST["gform_submit"] != $badge_form_id ||
            empty($_POST['input_6']) ||
            !isset($_POST['input_3'])
        ) {
            return;
        }

        // 2. Updates the URL of the confirmation message.
        echo '<script>
        jQuery(function ($) {
            const gfMessage = $(".gform_confirmation_message a");
            if (gfMessage.length) {
                const urlMessage = gfMessage.eq(0).attr("href") + "?parametr=masowy&qrcode=only";
                gfMessage.eq(0).attr("href", urlMessage);
                gfMessage.eq(1).hide();
            }
        });
        </script>';

        $multi_badge = [
            'form_id' => $badge_form_id
        ];

        // 3. Collects data from the form inputs and prepares it for badge generation.
        foreach ($_POST as $key => $value) {
            if (strpos(strtolower($key), 'input') !== false) {
                preg_match_all('/\d+/', $key, $id);
                $field = $id[0][0];
                $multi_badge[$field] = $value;
            }
        }

        $zip = new ZipArchive();
        $upload_dir = wp_upload_dir();
        $zip_path = $upload_dir['basedir'] . '/' . do_shortcode('[trade_fair_badge]') . '_qr_only.zip';

        // array to keep track of temporary files for cleanup
        $temp_files = [];

        // 4. Generates multiple badges adding to forms and opens each badge URL in a new window for download.
        if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            $count = (int)($_POST['multi_send'] ?? 1);

            for ($i = 0; $i < $count; $i++) {

                $entry_id = GFAPI::add_entry($multi_badge);

                if (is_wp_error($entry_id)) {
                    continue;
                }

                $entry = GFAPI::get_entry($entry_id);

                // force processing
                if (!is_wp_error($entry)) {
                    do_action(
                        'gform_after_submission',
                        $entry,
                        ['id' => $badge_form_id]
                    );
                }

                $file_path = '';

                // dynamic QR (remote)
                $pwe_url = gform_get_meta($entry_id, 'pwe_qr_code_url');

                if (!empty($pwe_url)) {

                    $tmp_file = self::pwe_download_temp_qr($pwe_url);

                    if (!empty($tmp_file) && file_exists($tmp_file)) {
                        $file_path = $tmp_file;
                        $temp_files[] = $tmp_file;
                    }

                } else {

                    // static QR fallback
                    $qr_feeds = GFAPI::get_feeds(null, $badge_form_id, 'qr-code');

                    if (!is_wp_error($qr_feeds)) {
                        foreach ($qr_feeds as $feed) {

                            $path = gform_get_meta(
                                $entry_id,
                                'qr-code_feed_' . $feed['id'] . '_file'
                            );

                            if (!empty($path) && file_exists($path)) {
                                $file_path = $path;
                                break;
                            }
                        }
                    }
                }

                // add to zip
                if (!empty($file_path) && file_exists($file_path)) {

                    $zip->addFile($file_path, basename($file_path));

                    // cleanup temp file immediately after adding to zip
                    if (isset($tmp_file) && file_exists($tmp_file)) {
                        @unlink($tmp_file);
                        $tmp_file = null;
                    }
                }
            }

            $zip->close();

            // fallback cleanup
            foreach ($temp_files as $f) {
                if (file_exists($f)) {
                    @unlink($f);
                }
            }

            echo '<script>
                const url = "' . $upload_dir['baseurl'] . '/' . do_shortcode('[trade_fair_badge]') . '_qr_only.zip?ts=" + Date.now();
                window.open(url, "_blank");
            </script>';
        }
    }

    /**
     * Static method to download QR code from URL and save it as a temporary file.
     * 
     * @param string $url The URL of the QR code to download.
     * 
     * @return string The path to the downloaded temporary file, or an empty string on failure.
     */
    public static function pwe_download_temp_qr($url) {

        if (empty($url)) {
            return '';
        }

        $upload_dir = wp_upload_dir();
        $tmp_dir = $upload_dir['basedir'] . '/tmp_qr/';

        if (!file_exists($tmp_dir)) {
            mkdir($tmp_dir, 0777, true);
        }

        $filename = 'qr_' . md5($url . microtime(true)) . '.png';
        $path = $tmp_dir . $filename;

        $response = wp_remote_get($url, [
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0'
            ]
        ]);

        if (is_wp_error($response)) {
            return '';
        }

        $code = wp_remote_retrieve_response_code($response);
        if ($code !== 200) {
            return '';
        }

        $body = wp_remote_retrieve_body($response);

        if (empty($body) || strpos($body, "\x89PNG") !== 0) {
            return '';
        }

        file_put_contents($path, $body);

        return $path;
    }

    /**
     * Static method to changing form field content.
     * 
     * @param number @badge_form_id form id
     *
     * The method processes the following steps:
     * 1. Finding radio field with badge names.
     * 2. Adjusting badge names in the finded fields.
     * 
     * @return object @content
     */
    public static function badge_name_changer($content, $field, $value, $lead_id, $form_id) {
        // 1. Finding radio field with badge names.
        foreach($field as $f_id => $f_label) {
            if (!is_array($f_label) && strpos(strtolower($f_label), 'wybierz') !== false) {
                // 2. Adjusting badge names in the finded fields.
                $badge = do_shortcode('[trade_fair_badge]');
                
                $content = preg_replace('/(_[a-zA-Z0-9_]+_a6)/', $badge . '$1', $content);
                $content = preg_replace('/[a-zA-Z0-9_]+_empty_wystawca_a6/', 'empty_wystawca_a6', $content);
                $content = preg_replace('/[a-zA-Z0-9_]+_empty_zlot_a6/', 'empty_zlot_a6', $content);
            }
        }
        return $content;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     * 
     * @param array @atts options
     * 
     * The method processes the following steps:
     * 1. Creating styles.
     * 2. Loading GF form.
     * 3. Filtering badge names.
     * 4. Cheking if $_GET parametr=masowy.
     * 5. Adding input for multi use same data and new function for mass form.
     * 
     * @return string @output
     */
    public static function output($atts) {
        // 1. Creating styles.
        // Addjusting colors.
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';
        $btn_text_color = 'color:' . self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'black') . '!important; border-width: 0 !important;';
        $btn_color = 'background-color:' . self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], 'white') . '!important;';
        $btn_shadow_color = 'box-shadow: 9px 9px 0px -5px ' . self::findColor($atts['btn_shadow_color_manual_hidden'], $atts['btn_shadow_color'], 'black') . '!important;';

        $output = '';

        // Creating Styles
        $output .= '<style>
            #badge-generator{
                max-width: 800px;
            }
            #badge-generator :is(.gform_heading p, .gfield :is(input, legend), .gfield_radio label){
                '.$text_color.'
                opacity: 1;
            }
            #badge-generator .gform_footer input{
                '.$btn_color
                .$btn_text_color
                .$btn_shadow_color.'
                border: 2px solid black !important;
            }
            #badge-generator ::placeholder, .gform-field-label, .gform-field-label span{
                color:black !important;
                opacity: 1;
            }
            #badge-generator #multi_send{
                width: 200px;
                
            }
            </style>';

        // 2. Loading GF form.
        $output .= '<div id="badge-generator">[gravityform id="'.$atts['badge_form_id'].'" title="false" description="false" ajax="false"]</div>';
        
        // 3. Filtering badge names.
        add_filter( 'gform_field_content', [ __CLASS__, 'badge_name_changer' ], 10, 5 );

        // 4. Cheking if $_GET parametr=masowy.
        if (isset($_GET['parametr']) && $_GET['parametr'] == 'masowy') {
            if (isset($_POST["gform_submit"]) && isset($_GET['qrcode']) && $_GET['qrcode'] == 'only'){                
                self::qrOnlyDownload($atts['badge_form_id']);
            } else {
                // 5. Adding input for multi use same data and new function for mass form.
                self::massGenerator($atts['badge_form_id']);
            }
        
            $output .= '<script>
                jQuery(function ($) {
                    const gfWraper = $("#gform_wrapper_' . $atts['badge_form_id'] . '");
                    const gfFields = gfWraper.find(".gform_fields");
                    const multiInput = $("<input>", {
                        placeholder: "ilość identyfikatorów",
                        type: "text",
                        id: "multi_send",
                        name: "multi_send"
                    });
                    gfFields.append(multiInput);
                });
            </script>';
        };

        return $output;
    }
}