<?php

class PWEIndustryEvening extends PWECommonFunctions {

  public static $rnd_id;
  public static $fair_colors;
  public static $accent_color;
  public static $main2_color;

  public function __construct() {
    add_action('init', array($this, 'initVCMapPWEIndustryEvening'));
    add_filter('gform_notification', array($this, 'addAttachmentToZaproszeniaNotification'), 10, 3);
    add_shortcode('pwe_industryevening', array($this, 'PWEIndustryEveningOutput'));
  }

  public function initVCMapPWEIndustryEvening() {
    if (class_exists('Vc_Manager')) {
      vc_map(array(
        'name' => __('PWE Industry Evening', 'pwe_industryevening'),
        'base' => 'pwe_industryevening',
        'category' => __('PWE Elements', 'pwe_industryevening'),
        'description' => __('Wysyłka na wieczór branżowy'),
        'params' => array(
          array(
            'type' => 'textfield',
            'heading' => __('Formularz ID (Gravity Forms)', 'pwe_industryevening'),
            'param_name' => 'form_id',
            'description' => __('Wpisz ID formularza Gravity Forms, który ma się wyświetlić.')
          )
        ),
      ));
    }
  }

public function addAttachmentToZaproszeniaNotification($notification, $form, $entry) {
  if ($notification['name'] !== 'Zaproszenia') {
    return $notification; // Tylko powiadomienie o nazwie "Zaproszenia"
  }

  $upload_dir = wp_upload_dir();
  $site_url = site_url();
  $file_path = ABSPATH . 'doc/zaproszenie.pdf';

  if (file_exists($file_path)) {
    $notification['attachments'][] = $file_path;
  }

  return $notification;
}

public function PWEIndustryEveningOutput($atts) {
  $atts = shortcode_atts([
    'form_id' => '',
  ], $atts);

  if (empty($atts['form_id'])) {
    return '<p>Nie podano ID formularza.</p>';
  }

  $form_id = (int)$atts['form_id'];
  $domain = parse_url(site_url(), PHP_URL_HOST);
  $fair_data = PWECommonFunctions::get_database_fairs_data($domain);
  $domain_gr = strtolower($fair_data[0]->fair_group ?? 'gr1');

  $ranges = [
    'gr1' => [
      '16 m² >' => 6,
      '17 m² - 33 m²' => 8,
      '34 m² - 69 m²' => 10,
      '70 m² - 106 m²' => 12
    ],
    'gr2' => [
      '16 m² >' => 4,
      '17 m² - 33 m²' => 6,
      '34 m² - 69 m²' => 6,
      '70 m² - 106 m²' => 8
    ],
    'gr3' => [
      '16 m² >' => 2,
      '17 m² - 33 m²' => 2,
      '34 m² - 100 m²' => 4,
      '70 m² - 106 m²' => 10
    ]
  ];

  $options = $ranges[$domain_gr] ?? [];

  if (!class_exists('GFAPI')) {
    return '<p>Gravity Forms API nie jest dostępne.</p>';
  }

  $form = GFAPI::get_form($form_id);
  if (!$form) {
    return '<p>Nie znaleziono formularza.</p>';
  }

  // Znajdź ID pola zaproszenia (po adminLabel)
  $zaproszenia_field_id = null;
  foreach ($form['fields'] as &$field) {
    if ($field->adminLabel === 'zaproszenia') {
      $zaproszenia_field_id = $field->id;
      $field->cssClass .= ' zaproszenia-field-hidden';
    }
  }

  if (!$zaproszenia_field_id) {
    return '<p>Nie znaleziono pola "zaproszenia".</p>';
  }

  // Renderuj formularz
  $output = do_shortcode('[gravityform id="' . esc_attr($form_id) . '" ajax="true"]');

  // Dodaj style i skrypt
  $output .= '<style>
    .zaproszenia-field-hidden { display: none !important; }
  </style>';

  $output .= '<script>
    document.addEventListener("DOMContentLoaded", function() {
      const ratioOptions = ' . json_encode($options) . ';
      const wrapper = document.querySelector(".pwe_ratio_placeholder");

      if (wrapper) {
        let html = "<strong>Wybierz powierzchnię stoiska:</strong><br>";
        Object.entries(ratioOptions).forEach(([label, value]) => {
          html += `<label>
            <input type="radio" name="pwe_m2_choice" value="${value}"> ${label}
          </label><br>`;
        });
        wrapper.innerHTML = html;
      }

      const radios = document.querySelectorAll("input[name=\'pwe_m2_choice\']");
      radios.forEach(radio => {
        radio.addEventListener("change", function() {
          const value = this.value;
          const zaproszeniaInput = document.querySelector("#input_' . $form_id . '_' . $zaproszenia_field_id . '");
          if (zaproszeniaInput) {
            zaproszeniaInput.value = value;
          }
        });
      });

      document.addEventListener("gform_confirmation_loaded", function() {
        const placeholder = document.querySelector(".pwe-ratio-placeholder");
        if (placeholder) {
          placeholder.style.display = "none";
        }
      });
    });
  </script>';

  return $output;
}



}
