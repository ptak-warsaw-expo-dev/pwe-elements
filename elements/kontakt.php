<?php
/**
 * Class PWElementContact
 *
 * Extends PWElements class and defines a PWE Visual Composer element.
 */
class PWElementContact extends PWElements {

    /**
     * Constructor method.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     *
     * @return array
     */
    public static function initElements() {
        $element_output = [];

        $element_output[] = [
            'type' => 'checkbox',
            'group' => 'PWE Element',
            'heading' => __('Horizontal display', 'pwelement'),
            'param_name' => 'horizontal',
            'value' => '',
            'dependency' => [
                'element' => 'pwe_element',
                'value' => 'PWElementContact',
            ],
        ];

        return $element_output;
    }

    /**
     * Clean scalar value.
     *
     * @param mixed $value
     * @return string
     */
    private static function pwe_clean_value($value) {
        if (is_array($value) || is_object($value)) {
            return '';
        }

        return trim((string) $value);
    }

    /**
     * Get manual option value from WordPress options table.
     * Manual value is treated as the highest-priority source.
     *
     * @param string $option_name
     * @return string
     */
    private static function pwe_option_value($option_name) {
        return self::pwe_clean_value(get_option($option_name, ''));
    }

    /**
     * Return manual value if it exists, otherwise return default value.
     *
     * Priority: manual field > CAP fallback > empty.
     *
     * @param mixed $manual_value
     * @param mixed $default_value
     * @return string
     */
    private static function pwe_first_not_empty($manual_value, $default_value = '') {
        $manual_value = self::pwe_clean_value($manual_value);

        if ($manual_value !== '') {
            return $manual_value;
        }

        return self::pwe_clean_value($default_value);
    }

    /**
     * Split one or many emails separated by comma or semicolon.
     *
     * @param mixed $value
     * @return array
     */
    private static function pwe_split_emails($value) {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $value = self::pwe_clean_value($value);

        if ($value === '') {
            return [];
        }

        $emails = preg_split('/[,;]+/', $value);
        $emails = array_map('trim', $emails);
        $emails = array_filter($emails);

        return array_values($emails);
    }

    /**
     * Prepare phone number for tel: href.
     *
     * @param mixed $phone
     * @return string
     */
    private static function pwe_phone_href($phone) {
        return preg_replace('/[^0-9+]/', '', self::pwe_clean_value($phone));
    }

    /**
     * Read selected field from decoded CAP contact data.
     *
     * @param mixed  $data
     * @param string $field
     * @return string
     */
    private static function pwe_data_value($data, $field) {
        if (!is_object($data)) {
            return '';
        }

        switch ($field) {
            case 'name':
                return self::pwe_clean_value($data->name ?? '');

            case 'phone':
                return self::pwe_clean_value($data->tel ?? '');

            case 'email':
                return self::pwe_clean_value($data->email ?? '');

            default:
                return '';
        }
    }

    /**
     * Render mailto links from one email string or array of email strings.
     *
     * @param mixed $emails
     * @return string
     */
    private static function pwe_render_email_links($emails) {
        $output = '';

        foreach (self::pwe_split_emails($emails) as $email) {
            $email = sanitize_email($email);

            if (empty($email)) {
                continue;
            }

            $domain = '@warsawexpo.eu';

            if (substr($email, -strlen($domain)) === $domain) {
                $display = '<span>' . esc_html(str_replace($domain, '', $email)) . '</span><span>' . esc_html($domain) . '</span>';
            } else {
                $display = '<span>' . esc_html($email) . '</span>';
            }

            $output .= '
            <a href="' . esc_url('mailto:' . $email) . '">
                ' . $display . '
            </a>';
        }

        return $output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     *
     * @param array $atts
     * @return string
     */
    public static function output($atts) {
        $atts = shortcode_atts([
            'horizontal' => '',
            'text_color_manual_hidden' => '',
            'text_color' => '',
        ], (array) $atts);

        $text_color = 'color:' . self::findColor(
            $atts['text_color_manual_hidden'],
            $atts['text_color'],
            'black'
        ) . '!important;';

        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $pwe_groups_contacts_data = PWECommonFunctions::get_database_groups_contacts_data();

        $source_utm = isset($_SERVER['argv'][0]) ? sanitize_text_field(wp_unslash($_SERVER['argv'][0])) : '';
        $current_domain = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
        $current_edition = trim(do_shortcode('[pwe_edition]')) === '1';

        $locked_service = false;
        $locked_marketing = false;

        $service_name = '';
        $service_emails = [];
        $service_phone = '';

        $marketing_media_name = '';
        $marketing_emails = [];
        $marketing_media_phone = '';

        $consultant_email = '';

        $contact_person_name = '';
        $contact_person_email = '';
        $contact_person_phone = '';

        if (is_iterable($pwe_groups_data) && is_iterable($pwe_groups_contacts_data)) {
            foreach ($pwe_groups_data as $group) {
                if (empty($group->fair_domain) || $current_domain !== $group->fair_domain) {
                    continue;
                }

                $current_group = self::pwe_clean_value($group->fair_group ?? '');

                $is_b2c_special = (
                    ($current_group === 'b2c' || $current_group === 'b2c-new')
                    && $current_edition
                );

                foreach ($pwe_groups_contacts_data as $group_contact) {
                    $contact_group_name = self::pwe_clean_value($group_contact->groups_name ?? '');
                    $slug = self::pwe_clean_value($group_contact->groups_slug ?? '');
                    $data = json_decode($group_contact->groups_data ?? '');

                    /*
                    =================================================
                    MODE 1: B2C SPECIAL → GR1 as CAP fallback
                    =================================================
                    */
                    if ($is_b2c_special && $contact_group_name === 'gr1') {
                        if ($slug === 'biuro-ob') {
                            $service_name = self::pwe_data_value($data, 'name');
                            $service_emails = self::pwe_split_emails(self::pwe_data_value($data, 'email'));
                            $service_phone = self::pwe_data_value($data, 'phone');

                            $locked_service = true;
                        }

                        if ($slug === 'ob-marketing-media') {
                            $marketing_media_name = self::pwe_data_value($data, 'name');
                            $marketing_emails = self::pwe_split_emails(self::pwe_data_value($data, 'email'));
                            $marketing_media_phone = self::pwe_data_value($data, 'phone');

                            $locked_marketing = true;
                        }

                        continue;
                    }

                    /*
                    =================================================
                    MODE 2: NORMAL → current group as CAP fallback
                    =================================================
                    */
                    if ($current_group !== $contact_group_name) {
                        continue;
                    }

                    if ($slug === 'biuro-ob' && !$locked_service) {
                        $service_name = self::pwe_data_value($data, 'name');
                        $service_emails = self::pwe_split_emails(self::pwe_data_value($data, 'email'));
                        $service_phone = self::pwe_data_value($data, 'phone');
                    }

                    if ($slug === 'ob-marketing-media' && !$locked_marketing) {
                        $marketing_media_name = self::pwe_data_value($data, 'name');
                        $marketing_emails = self::pwe_split_emails(self::pwe_data_value($data, 'email'));
                        $marketing_media_phone = self::pwe_data_value($data, 'phone');
                    }

                    if ($slug === 'ob-tech-wyst') {
                        $consultant_email = self::pwe_data_value($data, 'email');
                    }

                    if ($slug === 'osoba-kontakt') {
                        $contact_person_name = self::pwe_data_value($data, 'name');
                        $contact_person_email = self::pwe_data_value($data, 'email');
                        $contact_person_phone = self::pwe_data_value($data, 'phone');
                    }
                }
            }
        }

        /*
        =================================================
        Manual fields from panel always have priority.
        Priority: manual field > CAP fallback > empty.
        =================================================
        */
        $service_name = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_service_name'),
            $service_name
        );

        $service_phone = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_service_phone'),
            $service_phone
        );

        $service_emails = self::pwe_split_emails(
            self::pwe_first_not_empty(
                self::pwe_option_value('trade_fair_contact_service_email'),
                implode(',', $service_emails)
            )
        );

        if (empty($service_emails)) {
            $service_emails = ['zgloszenia@warsawexpo.eu'];
        }

        $marketing_media_name = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_media_name'),
            $marketing_media_name
        );

        $marketing_media_phone = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_media_phone'),
            $marketing_media_phone
        );

        $marketing_emails = self::pwe_split_emails(
            self::pwe_first_not_empty(
                self::pwe_option_value('trade_fair_contact_media'),
                implode(',', $marketing_emails)
            )
        );

        $consultant_email = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_tech'),
            $consultant_email
        );

        $contact_person_name = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_media_person_name'),
            $contact_person_name
        );

        $contact_person_email = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_media_person_email'),
            $contact_person_email
        );

        $contact_person_phone = self::pwe_first_not_empty(
            self::pwe_option_value('trade_fair_contact_media_person_phone'),
            $contact_person_phone
        );

        $output = '
        <style>
            .pwelement_' . self::$rnd_id . ' .pwe-container-contact {
                padding: 36px;
                border: 1px solid black;
                border-radius: 18px;
            }
            .pwelement_' . self::$rnd_id . ' .pwe-container-contact-items {
                display: flex;
                flex-direction: column;
                gap: 18px;
                margin-top: 18px;
            }
            .pwelement_' . self::$rnd_id . ' .pwe-contact-icon-item {
                display: flex;
                align-items: center;
                gap: 18px;
            }
            .pwelement_' . self::$rnd_id . ' .pwe-contact-icon-item a {
                font-size: 14px;
                display: flex;
                flex-wrap: wrap;
            }
            .pwelement_' . self::$rnd_id . ' .pwe-container-contact img {
                max-width: 110px !important;
                border-radius: 18px;
            }
            .pwelement_' . self::$rnd_id . ' .uncode_text_column :is(p, a),
            .pwelement_' . self::$rnd_id . ' .pwe-heading-text h4 {
                margin: 0;
                ' . $text_color . '
            }
            .pwelement_' . self::$rnd_id . ' .pwe-container-contact .main-pwe-heading-text {
                padding-top: 0;
                text-transform: uppercase;
            }
            #pweContact .gform_confirmation_message span {
                color: white !important;
            }

            @media (max-width: 860px) {
                .pwelement_' . self::$rnd_id . ' .pwe-container-contact {
                    padding: 18px;
                }
                .pwelement_' . self::$rnd_id . ' .pwe-contact-icon-item {
                    flex-wrap: wrap;
                    justify-content: center;
                    text-align: center;
                    flex-direction: column;
                }
                .pwelement_' . self::$rnd_id . ' .pwe-heading-text {
                    text-align: center;
                }
                .pwelement_' . self::$rnd_id . ' .pwe-heading-text h4 {
                    width: 100%;
                    margin-bottom: 10px;
                }
                .pwelement_' . self::$rnd_id . ' .pwe-contact-icon-item p {
                    min-width: 160px;
                }
            }';

        if (isset($atts['horizontal']) && $atts['horizontal'] === 'true') {
            $output .= '
            .pwelement_' . self::$rnd_id . ' .pwe-container-contact-items {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-evenly;
            }
            .pwelement_' . self::$rnd_id . ' .pwe-contact-icon-item {
                flex-direction: column;
                text-align: center;
                flex: 1;
            }
            .pwelement_' . self::$rnd_id . ' {
                padding: 9px 0;
            }';
        }

        $output .= '
        </style>

        <div id="contact" class="pwe-container-contact">
            <div class="pwe-heading-text main-pwe-heading-text">
                <h4>' . esc_html(PWElementContactForm::multi_translation('customer_service')) . '</h4>
            </div>

            <div class="pwe-container-contact-items">';

        $service_label = !empty($service_name)
            ? $service_name
            : PWElementContactForm::multi_translation('customer_service_office');

        $output .= '
                <div class="pwe-contact-icon-item">
                    <img src="/wp-content/plugins/pwe-media/media/Phone.jpg" alt="grafika słuchawka">
                    <div class="uncode_text_column">
                        <p>
                            <b>' . esc_html($service_label) . '</b>';

        if (!empty($service_phone)) {
            $output .= '
                            <a href="' . esc_url('tel:' . self::pwe_phone_href($service_phone)) . '">' . esc_html($service_phone) . '</a>';
        }

        $output .= self::pwe_render_email_links($service_emails);

        $output .= '
                        </p>
                    </div>
                </div>';

        if (!empty($consultant_email)) {
            $consultant_email = sanitize_email($consultant_email);

            if (!empty($consultant_email)) {
                $output .= '
                <div class="pwe-contact-icon-item">
                    <img src="/wp-content/plugins/pwe-media/media/WystawcyZ.jpg" alt="grafika wystawcy">
                    <div class="uncode_text_column">
                        <p>
                            <b>' . esc_html(PWElementContactForm::multi_translation('technical_support')) . '</b>
                            <a href="' . esc_url('mailto:' . $consultant_email) . '">
                                <span>' . esc_html($consultant_email) . '</span>
                            </a>
                        </p>
                    </div>
                </div>';
            }
        }

        $output .= '
            </div>

            <div class="pwe-heading-text main-pwe-heading-text" style="margin-top: 36px;">
                <h4>' . esc_html(PWElementContactForm::multi_translation('media_marketing')) . '</h4>
            </div>

            <div class="pwe-container-contact-items">';

        if (!empty($marketing_emails) || !empty($marketing_media_phone)) {
            $marketing_label = !empty($marketing_media_name)
                ? $marketing_media_name
                : PWElementContactForm::multi_translation('media_marketing_service');

            $output .= '
                <div class="pwe-contact-icon-item">
                    <img src="/wp-content/plugins/pwe-media/media/Marketing.jpg" alt="grafika technicy">
                    <div class="uncode_text_column" style="overflow-wrap: anywhere;">
                        <p>
                            <b>' . esc_html($marketing_label) . '</b>';

            if (!empty($marketing_media_phone)) {
                $output .= '
                            <a href="' . esc_url('tel:' . self::pwe_phone_href($marketing_media_phone)) . '">' . esc_html($marketing_media_phone) . '</a>';
            }

            $output .= self::pwe_render_email_links($marketing_emails);

            $output .= '
                        </p>
                    </div>
                </div>';
        }

        if (!empty($contact_person_name) && (!empty($contact_person_email) || !empty($contact_person_phone))) {
            $output .= '
                <div class="pwe-contact-icon-item">
                    <img src="/wp-content/plugins/pwe-media/media/Person.jpg" alt="grafika osoby">
                    <div class="uncode_text_column" style="overflow-wrap: anywhere;">
                        <p>
                            <b>' . esc_html($contact_person_name) . '</b>';

            if (!empty($contact_person_phone)) {
                $output .= '
                            <a href="' . esc_url('tel:' . self::pwe_phone_href($contact_person_phone)) . '">' . esc_html($contact_person_phone) . '</a>';
            }

            if (!empty($contact_person_email)) {
                $contact_person_email = sanitize_email($contact_person_email);

                if (!empty($contact_person_email)) {
                    $output .= '
                            <a href="' . esc_url('mailto:' . $contact_person_email) . '">' . esc_html($contact_person_email) . '</a>';
                }
            }

            $output .= '
                        </p>
                    </div>
                </div>';
        }

        $output .= '
            </div>
        </div>';

        $output .= '
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const utm = ' . wp_json_encode($source_utm) . ';
                const utmInput = document.querySelector(".utm-class input");

                if (utmInput) {
                    utmInput.value = utm;
                }
            });
        </script>';

        return $output;
    }
}
