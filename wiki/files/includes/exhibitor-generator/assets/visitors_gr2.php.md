# `includes/exhibitor-generator/assets/visitors_gr2.php`

Plik first-party PWE Elements w kategorii `exhibitor-generator`.

## Metadane

- **Kategoria:** `exhibitor-generator`
- **Rozmiar:** 73580 B
- **Liczba linii:** 1834
- **Źródło:** `includes/exhibitor-generator/assets/visitors_gr2.php`

## Klasy i metody

- Brak klas.

## Funkcje globalne

- `modify_placeholders_labels($generator_form_id)` — linia 3
- `add_field_marketing_consent($generator_form_id)` — linia 50
- `add_field_phone_number($generator_form_id)` — linia 161
- `logged_in_exhibitor_fields_hidden($form)` — linia 549
- `render_gr2($atts, $all_exhibitors, $all_partners, $all_conferences, $pweGeneratorWebsite, $domain, $generator_form_id_auto)` — linia 581
- `pwe_disable_marketing_consent($form)` — linia 600
- `inject_qr_code_into_email($notification, $form, $entry)` — linia 1797

## Rejestracje WordPress / GF

- **filter:** `gform_pre_render` — linia 1794
- **filter:** `gform_notification` — linia 1796

## Wybrane wywołania statyczne

- `GFAPI::get_form()`
- `GFAPI::update_form()`
- `GFAPI::get_entries()`
- `PWECommonFunctions::get_database_groups_data()`
- `PWECommonFunctions::languageChecker()`
- `PWEExhibitorVisitorGenerator::senderFlowChecker()`
- `GFAPI::get_feeds()`

## API WordPress rozpoznane heurystycznie

- `get_option()`
- `is_wp_error()`
- `update_option()`
- `wp_list_pluck()`
- `shortcode_atts()`
- `add_filter()`
- `wp_safe_redirect()`
- `current_user_can()`

## Dołączane pliki / wyrażenia include

- `s/exhibitor-generator/assets/media/parking.webp" alt="icon4">`
- `s/exhibitor-generator/assets/media/ico6.png" alt="icon6">`
- `s("<strong>") || decoded.includes("<em>") || decoded.includes("<span")) {`
- `d."`
- `s/exhibitor-generator/assets/media/genrator-example.xlsx">`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
