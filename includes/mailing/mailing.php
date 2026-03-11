<?php
/**
 * Plugin Name: PWE Mailing
 */

if ( ! defined('ABSPATH') ) exit;

define('PWEM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PWEM_NOTIF_DIR', rtrim(PWEM_PLUGIN_DIR, '/\\') . '/notifications');

require_once __DIR__ . '/core/FormGenerator.php';
require_once __DIR__ . '/config/FormPresets.php';
require_once __DIR__ . '/core/NotificationProcessor.php';
require_once __DIR__ . '/config/NotificationPresets.php';

class PWEMailing extends PWECommonFunctions {

    private bool $shouldRun       = false;
    private bool $versionChanged  = false;
    private ?string $currentVer   = null;
    private int $todayYmd         = 0;

    public function __construct() {

        // PRE-CHECK – nie zapisuje nic do bazy
        [$this->shouldRun, $this->versionChanged, $this->currentVer, $this->todayYmd] = self::gatePrecheck();

        if (!$this->shouldRun) {
            // nic dzisiaj nie robimy
            return;
        }

        PWE_NotificationProcessor::setVersionChanged($this->versionChanged);

        // Uruchom po załadowaniu Gravity Forms

        self::gateCommit($this->todayYmd, $this->currentVer);

        add_action('gform_loaded', [ $this, 'cleanup_catalog_feedback_entries' ], 5);

        add_action('gform_loaded', [ $this, 'catalog_feedback_form' ], 10);

        add_action('gform_loaded', [ $this, 'catalog_exhibitors_details' ], 10);

        add_action('gform_loaded', [ $this, 'register_resend' ], 20);

        add_action('gform_loaded', [ $this, 'register_resend_platyna' ], 20);

        add_action('gform_loaded', [ $this, 'enable_honeypot_for_all_forms' ], 30);


    }

    public function catalog_feedback_form() {
        if ( defined('DOING_CRON') && DOING_CRON ) return;

        $params = PWE_FormPresets::catalog_feedback_form([
            // tu możesz nadpisać np. tytuły, pola, ustawienia, itd.
        ]);

        PWE_FormGenerator::apply($params);
    }

    public function catalog_exhibitors_details() {
        if ( defined('DOING_CRON') && DOING_CRON ) return;

        $params = PWE_FormPresets::catalog_exhibitors_details([
            // tu możesz nadpisać np. tytuły, pola, ustawienia, itd.
        ]);

        PWE_FormGenerator::apply($params);
    }

    public function register_resend() {
        if ( defined('DOING_CRON') && DOING_CRON ) return;

        $params = PWE_NotificationPresets::resend([
            'template_dir'      => PWEM_NOTIF_DIR,
            'option_key_prefix' => 'gf_notification_resend_',
            'period'            => 'days:30',   // 'ttl:900' | 'minutes:10' | 'hours:6' | 'days:30'
        ]);

        PWE_NotificationProcessor::apply($params);
    }

    public function register_resend_platyna() {
        if ( defined('DOING_CRON') && DOING_CRON ) return;

        $params = PWE_NotificationPresets::resend_platyna([
            'template_dir'      => PWEM_NOTIF_DIR,
            'option_key_prefix' => 'gf_notification_resend_platyna_',
            'period'            => 'days:30',
        ]);

        PWE_NotificationProcessor::apply($params);
    }

    public function enable_honeypot_for_all_forms() : void {
        if (defined('DOING_CRON') && DOING_CRON) {
            return;
        }

        if (!class_exists('GFAPI') || !method_exists('GFAPI', 'get_forms')) {
            return;
        }

        $changedIds = [];
        $errorIds   = [];

        foreach (GFAPI::get_forms() as $f) {
            if(stripos('part_', $f['title']) !== false) continue;
            $form = GFAPI::get_form($f['id']);

            if (!$form || !is_array($form)) {
                continue;
            }

            if (!empty($form['enableHoneypot'])) {
                continue;
            }

            $form['enableHoneypot'] = true;

            $res = GFAPI::update_form($form);

            if (is_wp_error($res)) {
                $errorIds[] = (int) $form['id'];
            } else {
                $changedIds[] = (int) $form['id'];
            }
        }

        if (!empty($changedIds) || !empty($errorIds)) {
            $msg = 'HONEYPOT:';

            if (!empty($changedIds)) {
                $msg .= ' zmieniono [' . implode(', ', $changedIds) . ']';
            }

            if (!empty($errorIds)) {
                $msg .= ' błędy [' . implode(', ', $errorIds) . ']';
            }

            PWE_NotificationProcessor::mailing_log($msg);
        }
    }

    /** PRE-CHECK */
    private static function gatePrecheck() : array {
        try {
            $verKey  = 'gf_mailing_ver';
            $dayKey  = 'gf_mailing_day';

            $todayTs = (int) current_time('timestamp');
            $today   = (int) date('Ymd', $todayTs);

            $savedDay = (int) get_option($dayKey, 0);


            $pluginFile = WP_PLUGIN_DIR . '/PWElements/pwelements.php';

            // odczyt wersji
            $currVer = null;
            if (is_file($pluginFile) && is_readable($pluginFile)) {
                $head = file_get_contents($pluginFile, false, null, 0, 16384);
                if ($head && preg_match('/^[ \t\/*#@]*Version\s*:\s*(.+)$/mi', $head, $m)) {
                    $currVer = trim($m[1]);
                }
            }

            $versionChanged = false;
            $shouldRun = false;

            if ($currVer !== null) {
                $savedVer = (string) get_option($verKey, '');
                if ($savedVer === '' || version_compare($currVer, $savedVer, '>')) {
                    $versionChanged = true;
                    $shouldRun = true;
                }
            }

            if (!$shouldRun && ($savedDay === 0 || $today > $savedDay)) {
                $shouldRun = true;
            }

            return [$shouldRun, $versionChanged, $currVer, $today];
        } catch (\Throwable $e) {
            PWE_NotificationProcessor::mailing_log('GF: gatePrecheck exception – ' . $e->getMessage());
            return [false, false, null, 0];
        }
    }

    /** COMMIT */
    private static function gateCommit(int $todayYmd, ?string $currVer) : void {
        try {
            if ($todayYmd > 0) {
                update_option('gf_mailing_day', $todayYmd);
            }
            if ($currVer !== null && $currVer !== '') {
                update_option('gf_mailing_ver', $currVer);
            }
        } catch (\Throwable $e) {
            PWE_NotificationProcessor::mailing_log('GF: gateCommit exception – ' . $e->getMessage());
        }
    }

    private function get_form_id_by_title(string $title) : ?int {

        if ( ! class_exists('GFAPI') ) {
            return null;
        }

        foreach (GFAPI::get_forms() as $form) {
            if (
                isset($form['title']) &&
                trim($form['title']) === $title
            ) {
                return (int) $form['id'];
            }
        }

        return null;
    }

    public function cleanup_catalog_feedback_entries() : void {

        if ( defined('DOING_CRON') && DOING_CRON ) return;
        if ( ! class_exists('GFAPI') ) return;

        $form_id = $this->get_form_id_by_title('User opinions');
        if ( ! $form_id ) return;

        $page     = 1;
        $per_page = 200;
        $trashed  = 0;

        // ID pola opinions_source
        $opinions_source_field_id = 3;

        do {
            $entries = GFAPI::get_entries(
                $form_id,
                [ 'status' => 'active' ],
                null,
                [
                    'page_size' => $per_page,
                    'offset'   => ($page - 1) * $per_page,
                ]
            );

            if ( is_wp_error($entries) || empty($entries) ) {
                break;
            }

            foreach ($entries as $entry) {

                $source_raw = $entry[(string) $opinions_source_field_id] ?? '';
                $source     = trim((string) $source_raw);

                // warunek: puste LUB same cyfry
                if ( $source === '' || ctype_digit($source) ) {
                    GFAPI::update_entry_property($entry['id'], 'status', 'trash');
                    $trashed++;
                }
            }

            $page++;

        } while ( count($entries) === $per_page );

        if ( $trashed > 0 ) {
            PWE_NotificationProcessor::mailing_log(
                'GF cleanup (catalog_feedback): ' . $trashed
            );
        }
    }
}

add_action('plugins_loaded', function () {
    if (class_exists('PWEMailing')) {
        new PWEMailing();
    }
});
