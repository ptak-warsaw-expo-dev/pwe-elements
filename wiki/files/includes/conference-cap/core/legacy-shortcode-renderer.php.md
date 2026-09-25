# `includes/conference-cap/core/legacy-shortcode-renderer.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 51530 B
- **Liczba linii:** 948
- **Źródło:** `includes/conference-cap/core/legacy-shortcode-renderer.php`

## Klasy i metody

### `PWE_Conference_Cap_Legacy_Renderer` — linia 6

  - `public __construct()` — linia 20
  - `public initElements()` — linia 36
  - `public addingStyles()` — linia 193
  - `public static addingScripts($atts , $speakersDataMapping, $one_conf_mode = false, $archive = '')` — linia 202
  - `public static PWEConferenceCapOutput($atts)` — linia 206

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `init` — linia 23
- **action:** `wp_enqueue_scripts` — linia 27

## Wybrane wywołania statyczne

- `PWE_Conference_Cap_Assets::enqueue_runtime()`
- `PWE_Conference_Cap_Attributes::from_shortcode()`
- `PWEConferenceCapWarsawExpo::output()`
- `PWECommonFunctions::get_database_conferences_data()`
- `PWECommonFunctions::get_database_week_data()`
- `PWECommonFunctions::get_database_week_all()`
- `PWEConferenceCapFunctions::copySpeakerImgByStructure()`
- `PWEConferenceCapFunctions::debugConferencesConsole()`
- `PWECommonFunctions::languageChecker()`
- `PWEConferenceCapFunctions::findConferenceMode()`
- `PWEConferenceCapFunctions::getConferenceOrganizer()`
- `PWEConferenceCapFunctions::getConferenceOrganizersAll()`
- `PWESliderScripts::sliderScripts()`
- `PWEConferenceCapFunctions::getConferencePatronLogosFromList()`
- `mode_class::output()`
- `PWEConferenceCapFunctions::pwe_convert_rgb_to_hex()`
- `self::addingScripts()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_url()`
- `wp_enqueue_style()`
- `esc_attr()`
- `do_shortcode()`
- `esc_url()`
- `wp_get_attachment_url()`
- `esc_html()`
- `sanitize_title()`
- `wp_kses_post()`
- `wp_kses_allowed_html()`
- `wp_kses()`

## Dołączane pliki / wyrażenia include

- `_once PWE_CONFERENCE_CAP_PATH . 'classes/conference-cap-functions.php'`
- `_once PWE_CONFERENCE_CAP_PATH . 'modes/warsawexpo/warsawexpo.php'`
- `_once PWE_CONFERENCE_CAP_PATH . $conference_modes['php']`
- `_once PWE_CONFERENCE_CAP_PATH . '/../../scripts/slider.php'`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
