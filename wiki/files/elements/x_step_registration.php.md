# `elements/x_step_registration.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 86988 B
- **Liczba linii:** 1933
- **Źródło:** `elements/x_step_registration.php`

## Klasy i metody

### `PWElementXForm` — linia 7

  - `public __construct()` — linia 13
  - `public addingScripts()` — linia 20
  - `public static initElements()` — linia 28
  - `public static redirectTo($url)` — linia 216
  - `public static x_form_register($reg_form_id)` — linia 239
  - `public static add_info_email($qr_code_url, $post_data)` — linia 287
  - `public static exhibitor_registering($entry_id, $exhibitor, $update = '')` — linia 329
  - `public static add_side_entry($reg_form_id, $post_data)` — linia 357
  - `public static recaptcha_check()` — linia 403
  - `public static registrationHtml($reg_form_id, $step2_url, $conferences = '')` — linia 429
  - `public static registrationHtmlHeader($reg_form_id, $step2_url, $conferences = '', $pwe_header_modes)` — linia 677
  - `public static step2Html($reg_form_id, $confirmation_url, $text_color, $fair_logo, $go_back_url)` — linia 1019
  - `public static confirmYesHtml($atts, $exh_form_id, $text_color)` — linia 1305
  - `public static confirmNoHtml($atts, $reg_form_id,  $conf_form_id, $text_color, $go_back_url)` — linia 1556
  - `public static output($atts)` — linia 1855

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- `parent::__construct()`
- `GFAPI::form_id_exists()`
- `GFAPI::get_form()`
- `GFAPI::add_entry()`
- `GFAPI::get_entry()`
- `GFAPI::send_notifications()`
- `GFAPI::update_entry_field()`
- `self::languageChecker()`
- `self::recaptcha_check()`
- `self::x_form_register()`
- `self::redirectTo()`
- `self::add_side_entry()`
- `self::add_info_email()`
- `self::findColor()`
- `self::findFormsID()`
- `self::registrationHtml()`
- `self::step2Html()`
- `self::confirmYesHtml()`
- `self::confirmNoHtml()`

## API WordPress rozpoznane heurystycznie

- `current_user_can()`
- `sanitize_text_field()`
- `do_shortcode()`
- `wp_mail()`
- `get_option()`
- `get_locale()`
- `plugin_dir_path()`
- `plugins_url()`
- `wp_enqueue_script()`
- `wp_localize_script()`
- `wp_enqueue_style()`
- `plugin_dir_url()`
- `is_admin()`
- `wp_doing_ajax()`

## Dołączane pliki / wyrażenia include

- `d>`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
