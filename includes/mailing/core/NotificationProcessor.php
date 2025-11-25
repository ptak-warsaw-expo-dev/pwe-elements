<?php
if ( ! defined('ABSPATH') ) exit;

class PWE_NotificationProcessor {

    private static $versionChanged = false;
    public static function setVersionChanged(bool $v): void { self::$versionChanged = $v; }

    public static function mailing_log(string $message) : void {
        $uploadDir = wp_upload_dir();
        $logDir    = $uploadDir['basedir'] . '/pwe-element/mailing';

        if (!is_dir($logDir)) {
            @mkdir($logDir, 0750, true); // utwórz jeśli nie istnieje
        }

        $logFile = $logDir . '/mailing.log';
        $line    = "[" . date('Y-m-d H:i:s') . "] {$message}\n";

        if (@file_put_contents($logFile, $line, FILE_APPEND) === false) {
            error_log("PWE LOG FAIL {$logFile}: {$message}");
        }

        @chmod($logFile, 0640);
    }

    /** Zwraca cooldown w sekundach na podstawie $p['period'] */
    private static function period_seconds(array $p) : int {
        $spec = isset($p['period']) ? strtolower(trim((string)$p['period'])) : 'monthly';

        // liczba bezpośrednio → sekundy (np. '2592000')
        if (ctype_digit($spec)) {
            return (int)$spec;
        }

        // 'ttl:900' | 'minutes:10' | 'hours:6' | 'days:30'
        if (preg_match('/^ttl:(\d+)$/', $spec, $m))     return max(1, (int)$m[1]);
        if (preg_match('/^minutes:(\d+)$/', $spec, $m)) return max(1, (int)$m[1]) * MINUTE_IN_SECONDS;
        if (preg_match('/^hours:(\d+)$/', $spec, $m))   return max(1, (int)$m[1]) * HOUR_IN_SECONDS;
        if (preg_match('/^days:(\d+)$/', $spec, $m))    return max(1, (int)$m[1]) * DAY_IN_SECONDS;

        // skróty: daily/weekly/hourly/monthly (miesiąc ≈ 30 dni umownie)
        if ($spec === 'hourly')  return HOUR_IN_SECONDS;
        if ($spec === 'daily')   return DAY_IN_SECONDS;
        if ($spec === 'weekly')  return 7 * DAY_IN_SECONDS;
        if ($spec === 'monthly') return 30 * DAY_IN_SECONDS; // jeżeli chcesz „do końca miesiąca”, to inna logika, ale tu cooldown

        // fallback
        return 30 * DAY_IN_SECONDS;
    }

    /** Krok 1: wybór formularzy po tytule + wczesny done_option_key */
    private static function pickCandidatesByTitleMap(array $title_map, $option_key_prefix, array $p, array $not_followed_by = []) : array {
        if (!method_exists('GFAPI','get_forms')) {
            self::mailing_log('GF: Brak GFAPI::get_forms().');
            return [];
        }

        $forms      = GFAPI::get_forms();
        $candidates = [];
        $cooldown   = self::period_seconds($p);

        foreach ($forms as $f) {
            $title = $f['title'] ?? ($f->title ?? '');
            $id    = $f['id']    ?? ($f->id ?? null);
            if (!$id || !is_string($title)) { continue; }

            // dopasowanie języka po tytule
            $lang = null;
            foreach ($title_map as $needle => $lng) {
                if (stripos($title, $needle) !== false) { $lang = $lng; break; }
            }
            if (!$lang) { continue; }

            // wykluczenia
            $denyList = array_filter(array_map('strval', $not_followed_by));
            $denyHit  = false;
            foreach ($denyList as $deny) {
                $deny = trim($deny);
                if ($deny !== '' && stripos($title, $deny) !== false) {
                    $denyHit = true; break;
                }
            }
            if ($denyHit) { continue; }

            // cooldown per formularz
            $done_key = $option_key_prefix . (int)$id;
            $last     = (int) get_option($done_key, 0);
            $now      = (int) current_time('timestamp');
            if (!self::$versionChanged) {
                if ($last && ($now - $last) < $cooldown) {
                    continue;
                }
            }

            $candidates[(int)$id] = $lang;
        }

        return $candidates;
    }

    /** Krok 2: przetworzenie pojedyńczego formularza */
    private static function processOne(int $form_id, string $lang, array $p) : void {
        $done_key  = $p['option_key_prefix'] . $form_id;
        $now       = (int) current_time('timestamp');
        $cooldown  = self::period_seconds($p);

        $last = (int) get_option($done_key, 0);
        if (!self::$versionChanged) {
            if ($last && ($now - $last) < $cooldown) {
                return;
            }
        }

        $form = GFAPI::get_form($form_id);

        if (!$form || is_wp_error($form)) {
            self::mailing_log("GF: Nie udało się pobrać formularza ID {$form_id}");
            return;
        }

        $target_name    = $p['notification']['name'][$lang]    ?? null;
        $target_subject = $p['notification']['subject'][$lang] ?? null;
        $template_rel   = $p['notification']['template'][$lang] ?? null;
        if (!$target_name || !$target_subject || !$template_rel) {
            self::mailing_log("GF: Brak name/subject/template dla języka '{$lang}' (form {$form_id}).");
            return;
        }

        // A) sprawdź istniejącą notyfikację po nazwie (opcjonalnie nadpisuj)
        $existing_id = null;
        if (($p['notification']['match_by'] ?? 'name') === 'name') {
            foreach (($form['notifications'] ?? []) as $nid => $n) {
                if (isset($n['name']) && strcasecmp(trim($n['name']), $target_name) === 0) {
                    $existing_id = is_string($nid) ? $nid : ($n['id'] ?? null);
                    break;
                }
            }
        }
        if ($existing_id && empty($p['notification']['overwrite_if_exists'])) {
            update_option($done_key, $now);
            $form_title = is_array($form) && isset($form['title']) ? $form['title'] : 'unknown';
            self::mailing_log('GF: Formularz "' . $form_title . '" został zrobiony wcześniej.');
            return;
        }

        // B) wybór odbiorcy
        $toType = $p['to']['type'] ?? 'field';
        if ($toType === 'field') {
            $toFieldId = self::resolveEmailFieldId($form, $p['to']);
            if (empty($toFieldId)) {
                self::mailing_log("GF: Formularz ID {$form_id} nie ma pola e-mail wg strategii.");
                return;
            }
        } else {
            $toEmailString = (string)($p['to']['email_address'] ?? '');
            if ($toEmailString === '') {
                self::mailing_log("GF: Brak email_address dla toType=email (form {$form_id}).");
                return;
            }
        }

        // C) QR feed – zbierz wszystkie feedy QR
        $qr_feed_ids = [];
        if (!empty($p['qr']['enable'])) {
            $feeds = GFAPI::get_feeds(null, $form_id);
            foreach ($feeds as $feed) {
                $slug = $feed['addon_slug'] ?? '';
                if (in_array($slug, ['qr-code', 'sp-qrcode', 'gf-qr-code'], true)) {
                    $qr_feed_ids[] = (int)$feed['id'];
                }
            }
        }

        // D) treść z szablonu
        $template_dir  = rtrim($p['template_dir'], '/\\');
        $template_file = $template_dir . DIRECTORY_SEPARATOR . $template_rel;

        $message = file_exists($template_file)
            ? file_get_contents($template_file)
            : 'Dziękujemy za udział w wydarzeniu.';

        if (!empty($qr_feed_ids)) {
            $message = str_replace('[qr_feed_id]', $qr_feed_ids[0], $message);
        }

        // E) payload (bez enableQrAttachment / *_image_feed_*)
        $notif_id = $existing_id ?: uniqid();
        $payload = [
            'id'                 => $notif_id,
            'isActive'           => (bool)$p['notification']['isActive'],
            'name'               => $target_name,
            'event'              => $p['notification']['event'],
            'subject'            => $target_subject,
            'message'            => $message,
            'messageFormat'      => 'html',
            'from'               => $p['notification']['from'],
            'fromName'           => $p['notification']['fromName'],
            'disableAutoformat'  => true,
            'enableAttachments'  => false,
        ];

        if ($toType === 'field') {
            $payload['toType']  = 'field';
            $payload['toField'] = (string)$toFieldId;
            $payload['to']      = (string)$toFieldId;
        } else {
            $payload['toType'] = 'email';
            $payload['to']     = $toEmailString;
        }

        // Zaznacz WSZYSTKIE feedy QR
        if (!empty($qr_feed_ids)) {
            foreach ($qr_feed_ids as $fid) {
                $payload['spgfqrcode_notification_feed_' . $fid] = true;
            }
        }

        // F) zapis
        if (!isset($form['notifications']) || !is_array($form['notifications'])) {
            $form['notifications'] = [];
        }

        if ($existing_id && isset($form['notifications'][$existing_id]) && is_array($form['notifications'][$existing_id])) {
            $payload  = array_merge($form['notifications'][$existing_id], $payload);
            $notif_id = $existing_id;
        }

        $form['notifications'][$notif_id] = $payload;

        $result = GFAPI::update_form($form);
        if (is_wp_error($result)) {
            self::mailing_log('GF: Błąd zapisu formularza ID ' . $form_id . ' – ' . $result->get_error_message());
            return;
        } else {
            $action = $existing_id ? 'updated' : 'created';
            $ctx = [
                'form_id'    => $form_id,
                'form_title' => $form['title'] ?? '',
                'action'     => $action,
                'notif'      => [
                    'name'     => $target_name,
                    'isActive' => (bool)($p['notification']['isActive'] ?? false),
                ],
                'template'   => $template_rel,
            ];
            self::mailing_log('GF OK: ' . json_encode($ctx, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        if (false === get_option($done_key, false)) {
            add_option($done_key, $now, '', 'no');
        } else {
            update_option($done_key, $now);
        }
    }

    /** Pomocniczo: wybór ID pola e-mail wg prostej strategii (na razie tylko first/by_admin_label/by_id) */
    private static function resolveEmailFieldId(array $form, array $toOpt) : ?string {
        $fields = is_array($form['fields'] ?? null) ? $form['fields'] : [];

        switch ($toOpt['field_strategy'] ?? 'first_email') {
            case 'by_id':
                return !empty($toOpt['field_id']) ? (string)$toOpt['field_id'] : null;

            case 'by_admin_label':
                $needle = $toOpt['admin_label'] ?? null;
                if (!$needle) return null;
                foreach ($fields as $field) {
                    if (($field->type ?? null) === 'email' && isset($field->adminLabel) && $field->adminLabel === $needle) {
                        return (string)$field->id;
                    }
                }
                return null;

            case 'first_email':
            default:
                foreach ($fields as $field) {
                    if (($field->type ?? null) === 'email') {
                        return (string)$field->id;
                    }
                }
                return null;
        }
    }

    /**
     * Główne wejście: przyjmuje kompletny zestaw parametrów (z presetów)
     * i wykonuje całą procedurę na formularzach „Rejestracja PL/EN”.
     */
    public static function apply(array $p) : void {
        // Walidacja kluczowych pól
        foreach (['target_titles','notification','to','qr','option_key_prefix','template_dir'] as $req) {
            if (empty($p[$req])) { self::mailing_log("GF: Brak klucza parametrów: {$req}"); return; }
        }
        foreach (['name','subject','template','isActive','event','from','fromName','match_by','overwrite_if_exists'] as $k) {
            if (!array_key_exists($k, $p['notification'])) {
                self::mailing_log("GF: Brak notification['{$k}']."); return;
            }
        }

        // 1) Zbierz kandydatów (PL/EN po tytule) i odfiltruj po done_option_key dla BIEŻĄCEGO OKRESU
        $notFollowedBy = isset($p['not_followed_by']) && is_array($p['not_followed_by']) ? $p['not_followed_by'] : [];
        $candidates  = self::pickCandidatesByTitleMap($p['target_titles'], $p['option_key_prefix'], $p, $notFollowedBy);

        if (empty($candidates)) {
            return;
        }

        // 2) Przetwórz każdy formularz
        foreach ($candidates as $form_id => $lang) {
            self::processOne((int)$form_id, (string)$lang, $p);
        }
    }


}