<?php
if ( ! defined('ABSPATH') ) exit;

class PWE_NotificationPresets {

    /**
     * Preset „Resend”.
     */
    public static function resend(array $overrides = []) : array {
        $base = [
            // Wykrywanie formularzy po tytule
            'target_titles' => [
                'Rejestracja PL' => 'pl',
                'Rejestracja EN' => 'en',
            ],

            // Parametry powiadomienia
            'notification' => [
                'name'    => [
                    'pl' => 'Resend', 
                    'en' => 'Resend'
                ],
                'subject' => [
                    'pl' => 'Przypominamy o targach {trade_fair_name}',
                    'en' => '{trade_fair_name} Reminder',
                ],
                'template' => [
                    'pl' => 'form_resend_pl.html',
                    'en' => 'form_resend_en.html',
                ],
                'isActive'            => false,
                'event'               => 'form_submission',
                'from'                => '{trade_fair_rejestracja}',
                'fromName'            => '{trade_fair_name}',
                'match_by'            => 'name',   // 'name' | 'none'
                'overwrite_if_exists' => true,
            ],

            // Wyślij do pola
            'to' => [
                'type'           => 'field',        // 'field' | 'email'
                'field_strategy' => 'first_email',  // 'first_email' | 'by_admin_label' | 'by_id'
                'admin_label'    => null,
                'field_id'       => null,
                'email_address'  => null,           // używane gdy 'type' => 'email'
            ],

            // QR-feed
            'qr' => [
                'enable' => true,
                'embed'  => true,
                'attach' => true,
            ],

            // Inne
            'option_key_prefix' => 'gf_notification_resend_',
            'template_dir'      => WP_CONTENT_DIR,
        ];

        return array_replace_recursive($base, $overrides);
    }

        /**
     * Preset „Resend”.
     */
    public static function resend_platyna(array $overrides = []) : array {
        $base = [
            // Wykrywanie formularzy po tytule
            'target_titles' => [
                'Rejestracja PL' => 'pl',
                'Rejestracja EN' => 'en',
                'call centre' => 'cc',
            ],
            'not_followed_by'   => ['FB'],

            // Parametry powiadomienia
            'notification' => [
                'name'    => [
                    'pl' => 'Platyna RESEND - edytowana kopia [platyna - Aktywacja]', 
                    'en' => 'Platyna RESEND - edytowana kopia [(platyna) - Aktywacja]',
                    'cc' => 'Platyna Resend - Edytowana Kopia [Platyna - Aktywacja]'
                ],
                'subject' => [
                    'pl' => 'Zaproszenie VIP {trade_fair_name}',
                    'en' => 'Thank you for registering on {trade_fair_name}',
                    'cc' => 'Zaproszenie VIP na targi {trade_fair_name}'
                ],
                'template' => [
                    'pl' => 'form_resend_platyna_pl.html',
                    'en' => 'form_resend_platyna_en.html',
                    'cc' => 'form_resend_platyna_cc.html'
                ],
                'isActive'            => false,
                'event'               => 'form_submission',
                'from'                => '{trade_fair_rejestracja}',
                'fromName'            => '{trade_fair_name}',
                'match_by'            => 'name',
                'overwrite_if_exists' => true,
            ],
            // Wyślij do pola
            'to' => [
                'type'           => 'field',
                'field_strategy' => 'first_email',
                'admin_label'    => null,
                'field_id'       => null,
                'email_address'  => null,
            ],
            // QR-feed
            'qr' => [
                'enable' => true,
                'embed'  => true,
                'attach' => true,
            ],
            // Inne
            'option_key_prefix' => 'gf_notification_resend_platyna_',
            'template_dir'      => WP_CONTENT_DIR,
        ];

        return array_replace_recursive($base, $overrides);
    }
}
