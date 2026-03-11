<?php
if ( ! defined('ABSPATH') ) exit;

class PWE_FormPresets {

    public static function catalog_feedback_form(array $overrides = []) : array {

        $catalog_id = uniqid();
        $shop_id    = uniqid();

        $base = [
            'meta' => [
                'title'       => [
                    'pl' => 'User opinions',
                ],
                'description' => [
                    'pl' => '',
                ],
                'slug'        => 'catalog_feedback',
            ],

            'settings' => [
                'useCurrentUserAsAuthor' => true,
                'markupVersion'          => 2,
                'labelPlacement'         => 'top_label',
                'personalData' => [
                    'preventIP' => true,
                    'retention' => [
                        'policy'              => 'retain',
                        'retain_entries_days' => '1',
                    ],
                ],
                'button' => [
                    'type' => 'text',
                    'text' => [
                        'pl' => 'Wyślij',
                    ],
                    'location'              => 'bottom',
                    'layoutGridColumnSpan'  => 12,
                    'width'                 => 'auto',
                    'imageUrl'              => '',
                    'id'                    => 'submit',
                ],
            ],

            'confirmations' => [
                'default' => [
                    'name'      => 'Dziękujemy',
                    'type'      => 'message',
                    'message'   => 'Dziękujemy za przesłanie opinii.',
                    'isDefault' => true,
                ],
            ],
            'notifications' => [
                $catalog_id => [
                    'id'      => $catalog_id,
                    'name'    => 'Nowa opinia – admin [catalog]',
                    'event'   => 'form_submission',
                    'toType'  => 'email',
                    'to'      => 'opinie@warsawexpo.eu',
                    'subject' => 'Nowa opinia z {embed_url} [catalog]',
                    'message' => '{all_fields}',
                    'isActive' => true,
                    'conditionalLogic' => [
                        'actionType' => 'show',
                        'logicType'  => 'all',
                        'rules' => [
                            [
                                'fieldId'  => 3,
                                'operator' => 'is',
                                'value'    => 'catalog',
                            ]
                        ],
                    ],
                ],

                $shop_id => [
                    'id'      => $shop_id,
                    'name'    => 'Nowa opinia – admin [shop]',
                    'event'   => 'form_submission',
                    'toType'  => 'email',
                    'to'      => 'opinie@warsawexpo.eu',
                    'subject' => 'Nowa opinia z {embed_url} [shop]',
                    'message' => '{all_fields}',
                    'isActive' => true,
                    'conditionalLogic' => [
                        'actionType' => 'show',
                        'logicType'  => 'all',
                        'rules' => [
                            [
                                'fieldId'  => 3,
                                'operator' => 'is',
                                'value'    => 'shop',
                            ]
                        ],
                    ],
                ],
            ],
            
            'fields' => [
                [
                    'id'         => 1,
                    'type'       => 'radio',
                    'label'      => [
                        'pl' => 'opinions_rating',
                    ],
                    'adminLabel' => 'opinions_rating',
                    'required'   => true,
                    'size'              => 'large',
                    'cssClass'          => 'rating',
                    'labelPlacement'    => 'hidden_label',
                    'enableChoiceValue' => true,
                    'choices' => [
                        ['text' => '😡', 'value' => '1', 'isSelected' => false, 'price' => ''],
                        ['text' => '😕', 'value' => '2', 'isSelected' => false, 'price' => ''],
                        ['text' => '😐', 'value' => '3', 'isSelected' => false, 'price' => ''],
                        ['text' => '🙂', 'value' => '4', 'isSelected' => false, 'price' => ''],
                        ['text' => '😍', 'value' => '5', 'isSelected' => false, 'price' => ''],
                    ],
                ],
                [
                    'id'         => 2,
                    'type'       => 'textarea',
                    'label'      => [
                        'pl' => 'Jeśli masz dodatkowe uwagi, daj nam znać',
                    ],
                    'adminLabel' => 'opinions_text',
                    'required'   => false,

                    // zgodnie z dumpem
                    'size'                 => 'small',
                    'cssClass'             => 'description',
                    'layoutGridColumnSpan' => 12,
                ],
                [
                    'id'            => 3,
                    'type'          => 'hidden',
                    'label'         => 'opinions_source',
                    'adminLabel'    => 'opinions_source',
                    'defaultValue'  => '',
                ],
                [
                    'id'            => 4,
                    'type'          => 'hidden',
                    'label'         => 'opinions_device_width',
                    'adminLabel'    => 'opinions_device_width',
                    'defaultValue'  => '',
                ],
                [
                    'id'            => 5,
                    'type'          => 'hidden',
                    'label'         => 'opinions_update_version',
                    'adminLabel'    => 'opinions_update_version',
                    'defaultValue'  => '',
                ],
                [
                    'id'            => 6,
                    'type'          => 'captcha',
                    'label'         => 'CAPTCHA',
                    'labelPlacement'    => 'hidden_label',
                ],
            ],

            // opcjonalnie: wymuś nextFieldId (GF zwykle policzy sam, ale można ustawić)
            'nextFieldId' => 7,
        ];

        return array_replace_recursive($base, $overrides);
    }

    public static function catalog_exhibitors_details(array $overrides = []) : array {

        $catalog_id = uniqid();
        $shop_id    = uniqid();

        $base = [
            'meta' => [
                'title'       => [
                    'pl' => 'Exhibitors details',
                ],
                'description' => [
                    'pl' => '',
                ],
                'slug'        => 'exhibitors_details',
            ],

            'settings' => [
                'useCurrentUserAsAuthor' => true,
                'markupVersion'          => 2,
                'labelPlacement'         => 'top_label',
                'personalData' => [
                    'preventIP' => true,
                    'retention' => [
                        'policy'              => 'retain',
                        'retain_entries_days' => '1',
                    ],
                ],
                'button' => [
                    'type' => 'text',
                    'text' => [
                        'pl' => 'Wyślij',
                    ],
                    'location'              => 'bottom',
                    'layoutGridColumnSpan'  => 12,
                    'width'                 => 'auto',
                    'imageUrl'              => '',
                    'id'                    => 'submit',
                ],
            ],

            'fields' => [
                [
                    'id'         => 1,
                    'type'       => 'email',
                    'label'      => [
                        'pl' => 'Adres Email',
                    ],
                    'adminLabel' => 'pwe_email',
                    'required'   => true,

                    // zgodnie z dumpem
                    'size'                 => 'large',
                    'cssClass'             => 'pwe_email',
                ],
                [
                    'id'         => 2,
                    'type'       => 'phone',
                    'label'      => [
                        'pl' => 'Numer Telefonu',
                    ],
                    'adminLabel' => 'pwe_phone',
                    'required'   => false,

                    // zgodnie z dumpem
                    'size'                 => 'large',
                    'cssClass'             => 'pwe_phone',
                ],
                [
                    'id'         => 3,
                    'type'       => 'textarea',
                    'label'      => [
                        'pl' => 'Zapytanie do wystawcy',
                    ],
                    'adminLabel' => 'pwe_textarea',
                    'required'   => false,

                    // zgodnie z dumpem
                    'size'                 => 'large',
                    'cssClass'             => 'pwe_textarea',
                ],
                [
                    'id'              => 4,
                    'type'            => 'consent',
                    'inputType'       => 'consent',
                    'label'           => 'Zgoda na przetwarzanie danych osobowych',
                    'adminLabel'      => 'pwe-consent',
                    'inputName'       =>  null,
                    'labelPlacement'  => 'hidden_label',
                    'isRequired'      => true,
                    'visibility'      => 'visible',
                    'descriptionPlaceholder' => 'Enter consent agreement text here.  The Consent Field will store this agreement text with the form entry in order to track what the user has consented to.',
                    'pageNumber'       => 1,

                    'checkboxLabel' => 'Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych w celach marketingowych i wysyłki wiadomości. <span class="show-consent">(Więcej)</span>',
                    'description' => 'Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych, tj. 1) imię i nazwisko; 2) adres e-mail 3) nr telefonu w celach wysyłki wiadomości marketingowych i handlowych związanych z produktami i usługami oferowanymi przez Ptak Warsaw Expo sp. z o.o. za pomocą środków komunikacji elektronicznej lub bezpośredniego porozumiewania się na odległość, w tym na otrzymywanie informacji handlowych, stosownie do treści Ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną. Wiem, że wyrażenie zgody jest dobrowolne, lecz konieczne w celu dokonania rejestracji. Zgodę mogę wycofać w każdej chwili.',
                    
                    'inputs' => [
                        [ 'id' => '4.1', 'label' => 'Zgoda', 'name' => '' ],
                        [ 'id' => '4.2', 'label' => 'Tekst', 'name' => '', 'isHidden' => true ],
                        [ 'id' => '4.3', 'label' => 'Opis', 'name' => '', 'isHidden' => true ],
                    ],

                    'choices' => [
                        [
                            'text'       => 'Checked',
                            'value'      => '1',
                            'isSelected' => false,
                        ],
                    ],

                    'cssClass' => 'pwe-consent',
                    'conditionalLogic' => false,
                ],
                [
                    'id'         => 5,
                    'type'       => 'hidden',
                    'label'      => [
                        'pl' => 'EntryCode',
                    ],
                    'adminLabel' => 'pwe_entryCode',
                    'required'   => false,

                    'size'                 => 'large',
                    'cssClass'             => 'pwe_entryCode',
                ],
            ],

            'confirmations' => [
                'default' => [
                    'name'      => 'Dziękujemy',
                    'type'      => 'message',
                    'message'   => 'Dane wystawców powinny się odkryć. 
                    W razie problemów na adres email została wysłana wiadomość z linkiem odkrywającym informacje o wystawcach',
                    'isDefault' => true,
                ],
            ],
            'notifications' => [
                $catalog_id => [
                    'id'      => $catalog_id,
                    'service' => 'wordpress',
                    'name'    => 'Exhibitors details link',
                    'event'   => 'form_submission',
                    'toType'  => 'field',
                    'to'      => '1',
                    'subject' => '{trade_fair_name} link do odkrywania detali wystawców',
                    'message' => str_replace(["\r", "\n", "\t"], '',file_get_contents(__DIR__ . '/details_link.html')),
                    'isActive' => true,
                    'enableAttachments' => false,
                    'messageFormat' => 'html',
                    'conditionalLogic' => false,
                ],
            ],

            // opcjonalnie: wymuś nextFieldId (GF zwykle policzy sam, ale można ustawić)
            'nextFieldId' => 8,
        ];

        return array_replace_recursive($base, $overrides);
    }
}