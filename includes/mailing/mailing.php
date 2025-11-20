<?php
/**
 * Plugin Name: PWE Mailing
 */

if ( ! defined('ABSPATH') ) exit;

define('PWEM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PWEM_NOTIF_DIR', rtrim(PWEM_PLUGIN_DIR, '/\\') . '/notifications');

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
        add_action('gform_loaded', [ $this, 'register_resend' ], 20);

        add_action('gform_loaded', [ $this, 'register_resend_platyna' ], 20);

        add_action('shutdown', function () {
            self::gateCommit($this->todayYmd, $this->currentVer);
        });
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
}