<?php
if ( ! defined('ABSPATH') ) exit;

class PWE_FormGenerator {

    public static function apply(array $p) : void {

        foreach (['meta','settings','fields'] as $req) {
            if (empty($p[$req])) {
                self::log("FORM: Brak klucza {$req}");
                return;
            }
        }

        foreach ($p['meta']['title'] as $lang => $title) {
            self::processOneLang($lang, $p);
        }
    }

    private static function processOneLang(string $lang, array $p) : void {

        $title = $p['meta']['title'][$lang] ?? null;
        if (!$title) {
            return;
        }

        $form = self::findFormByTitle($title);

        if ($form) {
            self::updateForm($form, $lang, $p);
        } else {
            self::createForm($lang, $p);
        }
    }

    private static function findFormByTitle(string $title) : ?array {

        if (!method_exists('GFAPI','get_forms')) {
            return null;
        }

        foreach (GFAPI::get_forms() as $f) {
            $t = $f['title'] ?? ($f->title ?? '');
            if ($t === $title) {
                return GFAPI::get_form($f['id']);
            }
        }

        return null;
    }

    private static function createForm(string $lang, array $p) : void {

        $form = [
            'title'       => $p['meta']['title'][$lang],
            'description' => $p['meta']['description'][$lang] ?? '',
            'labelPlacement' => $p['settings']['labelPlacement'] ?? 'top_label',
            'fields'      => self::buildFields($lang, $p['fields']),
            'button'      => [
                'type' => 'text',
                'text' => $p['settings']['button']['text'][$lang] ?? 'Wyślij',
            ],
        ];

        // CONFIRMATIONS
        if (!empty($p['confirmations']) && is_array($p['confirmations'])) {
            $form['confirmations'] = [];

            foreach ($p['confirmations'] as $key => $c) {
                $id = uniqid();

                $form['confirmations'][$id] = [
                    'id'               => $id,
                    'name'             => $c['name'] ?? 'Confirmation',
                    'isDefault'        => (bool)($c['isDefault'] ?? false),
                    'type'             => $c['type'] ?? 'message',
                    'message'          => $c['message'] ?? '',
                    'disableAutoformat'=> false,
                    'conditionalLogic' => [],
                ];
            }
        }

        // NOTIFICATIONS
        if (!empty($p['notifications']) && is_array($p['notifications'])) {
            $form['notifications'] = [];

            foreach ($p['notifications'] as $key => $n) {
                $id = uniqid();

                $form['notifications'][$id] = [
                    'id'                => $id,
                    'isActive'          => (bool)($n['isActive'] ?? true),
                    'name'              => $n['name'] ?? 'Notification',
                    'event'             => $n['event'] ?? 'form_submission',
                    'toType'            => $n['toType'] ?? 'email',
                    'to'                => $n['to'] ?? '{admin_email}',
                    'subject'           => $n['subject'] ?? '',
                    'message'           => $n['message'] ?? '',
                    'messageFormat'     => 'html',
                    'disableAutoformat' => false,
                    'conditionalLogic'  => $n['conditionalLogic'] ?? [],
                ];
            }
        }

        $id = GFAPI::add_form($form);

        if (is_wp_error($id)) {
            self::log('FORM: Błąd tworzenia – ' . $id->get_error_message());
            return;
        }

        self::log("FORM OK: utworzono formularz {$form['title']} (ID {$id})");
    }

    private static function updateForm(array $form, string $lang, array $p) : void {

        $form['description'] = $p['meta']['description'][$lang] ?? $form['description'];
        $form['fields']      = self::buildFields($lang, $p['fields']);

        // CONFIRMATIONS – merge
        if (!empty($p['confirmations']) && is_array($p['confirmations'])) {
            $form['confirmations'] = $form['confirmations'] ?? [];

            foreach ($p['confirmations'] as $c) {
                $exists = false;

                foreach ($form['confirmations'] as $existing) {
                    if (
                        isset($existing['name']) &&
                        $existing['name'] === ($c['name'] ?? '')
                    ) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $id = uniqid();
                    $form['confirmations'][$id] = [
                        'id'        => $id,
                        'name'      => $c['name'],
                        'type'      => $c['type'] ?? 'message',
                        'message'   => $c['message'] ?? '',
                        'isDefault' => (bool)($c['isDefault'] ?? false),
                    ];
                }
            }
        }

        // NOTIFICATIONS – merge
        if (!empty($p['notifications']) && is_array($p['notifications'])) {
            $form['notifications'] = $form['notifications'] ?? [];

            foreach ($p['notifications'] as $n) {
                $exists = false;

                foreach ($form['notifications'] as $existing) {
                    if (
                        isset($existing['name']) &&
                        $existing['name'] === ($n['name'] ?? '')
                    ) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $id = uniqid();
                    $form['notifications'][$id] = [
                        'id'        => $id,
                        'isActive'  => (bool)($n['isActive'] ?? true),
                        'name'      => $n['name'],
                        'event'     => $n['event'] ?? 'form_submission',
                        'toType'    => $n['toType'] ?? 'email',
                        'to'        => $n['to'] ?? '{admin_email}',
                        'subject'   => $n['subject'] ?? '',
                        'message'   => $n['message'] ?? '',
                    ];
                }
            }
        }

        $res = GFAPI::update_form($form);

        if (is_wp_error($res)) {
            self::log('FORM: Błąd aktualizacji – ' . $res->get_error_message());
        } else {
            self::log("FORM OK: zaktualizowano formularz {$form['title']} (ID {$form['id']})");
        }
    }

    private static function buildFields(string $lang, array $fields) : array {

    $out = [];

    foreach ($fields as $f) {
        $id = isset($f['id']) ? (int)$f['id'] : null;

        $cfg = [
            'id'         => $id ?: null,
            'type'       => $f['type'] ?? null,
            'label'      => $f['label'][$lang] ?? ($f['label'] ?? ''),
            'adminLabel' => $f['adminLabel'] ?? '',
            'isRequired' => (bool)($f['required'] ?? false),
        ];

        // Przepuść dodatkowe właściwości 1:1 (jeśli podane w presencie)
            $passthroughKeys = [
                'size',
                'cssClass',
                'placeholder',
                'description',
                'labelPlacement',
                'descriptionPlacement',
                'subLabelPlacement',
                'choices',
                'enableChoiceValue',
                'layoutGridColumnSpan',
                'enableEnhancedUI',
                'defaultValue',
                'noDuplicates',
            ];

            foreach ($passthroughKeys as $k) {
                if (array_key_exists($k, $f)) {
                    $cfg[$k] = $f[$k];
                }
            }

            // Ujednolicenie nazw z Twojego presetu do GF:
            // - enableChoiceValue w GF jest bool
            if (isset($cfg['enableChoiceValue'])) {
                $cfg['enableChoiceValue'] = (bool) $cfg['enableChoiceValue'];
            }

            // Wymagane minimum
            if (empty($cfg['type'])) {
                self::log('FORM: Pole bez type – pomijam');
                continue;
            }

            // Tworzenie pola: preferuj GF_Fields::create, fallback na array
            if (class_exists('GF_Fields') && method_exists('GF_Fields', 'create')) {
                $field = GF_Fields::create($cfg);
                $out[] = $field;
            } else {
                // Fallback: GFAPI przyjmie tablicę (zależnie od wersji GF)
                $out[] = $cfg;
            }
        }

        return $out;
    }

    private static function log(string $msg) : void {
        if (class_exists('PWE_NotificationProcessor')) {
            PWE_NotificationProcessor::mailing_log($msg);
        }
    }
}
