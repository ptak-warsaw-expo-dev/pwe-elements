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
            ],

            // opcjonalnie: wymuś nextFieldId (GF zwykle policzy sam, ale można ustawić)
            'nextFieldId' => 5,
        ];

        return array_replace_recursive($base, $overrides);
    }
}
