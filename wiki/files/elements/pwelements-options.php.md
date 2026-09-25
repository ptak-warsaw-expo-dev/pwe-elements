# `elements/pwelements-options.php`

Plik first-party PWE Elements w kategorii `legacy-element`.

## Metadane

- **Kategoria:** `legacy-element`
- **Rozmiar:** 39626 B
- **Liczba linii:** 819
- **Źródło:** `elements/pwelements-options.php`

## Klasy i metody

### `PWElements` — linia 3

  - `public __construct()` — linia 13
  - `public initVCMapElements()` — linia 58
  - `private getAvailableElements()` — linia 325
  - `private findClassElements()` — linia 411
  - `public load_more_posts()` — linia 494
  - `public addingStyles()` — linia 501
  - `public addingScripts()` — linia 510
  - `public static findColor($primary, $secondary, $default = '')` — linia 531
  - `public findPalletColors()` — linia 546
  - `public static languageChecker($pl, $en = '')` — linia 578
  - `public static adjustBrightness($hex, $steps)` — linia 587
  - `public findFormsGF()` — linia 610
  - `public static findFormsID($form_name)` — linia 629
  - `public static checkForMobile()` — linia 648
  - `public static findBestLogo($logo_color = false)` — linia 658
  - `public static findAllImages($firstPath, $image_count = false, $secondPath = '/doc/galeria')` — linia 708
  - `public static findBestFile($file_path)` — linia 736
  - `public static isTradeDateExist()` — linia 754
  - `public PWElementsOutput($atts, $content = null)` — linia 775

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- **action:** `wp_enqueue_scripts` — linia 26
- **action:** `wp_enqueue_scripts` — linia 27
- **action:** `init` — linia 30
- **action:** `init` — linia 32

## Wybrane wywołania statyczne

- `PWBadgeElement::initElements()`
- `PWElementVideos::initElements()`
- `PWElementProfile::initElements()`
- `PWElementStickyButtons::initElements()`
- `PWElementForExhibitors::initElements()`
- `PWElementForVisitors::initElements()`
- `PWElementContact::initElements()`
- `PWElementMedalForm::initElements()`
- `PWElementContactInfo::initElements()`
- `PWElementPromot::initElements()`
- `PWElementConferences::initElements()`
- `PWElementHomeGallery::initElements()`
- `PWElementPosts::initElements()`
- `PWElementMainCountdown::initElements()`
- `PWElementFooter::initElements()`
- `PWElementGenerator::initElements()`
- `PWElementRegistration::initElements()`
- `PWElementRegContent::initElements()`
- `PWElementInvite::initElements()`
- `PWElementTicket::initElements()`
- `PWElementNumbers::initElements()`
- `PWElementConfSection::initElements()`
- `PWElementMapa::initElements()`
- `PWElementMapaTest::initElements()`
- `PWElementHeaderConference::initElements()`
- `PWElementPotwierdzenieRejestracji::initElements()`
- `PWElementStepTwoExhibitor::initElements()`
- `PWElementStepTwo::initElements()`
- `PWElementTicketActConf::initElements()`
- `PWElementOpinions::initElements()`
- `PWElementButton::initElements()`
- `PWElementAbout::initElements()`
- `PWElementWhyItsWorth::initElements()`
- `PWElementQRChekcer::initElements()`
- `PWElementConfirmationVip::initElements()`
- `PWElementContactForm::initElements()`
- `PWElementSingleImage::initElements()`
- `PWElementOtherEvents::initElements()`
- `PWElementTwoCols::initElements()`
- `PWElementHale::initElements()`
- `PWElementMedals::initElements()`
- `PWElementTimelineStats::initElements()`
- `PWElementPopup::initElements()`
- `PWElementNewsSummary::initElements()`
- `PWElementFeedback::initElements()`
- `GFAPI::get_forms()`

## API WordPress rozpoznane heurystycznie

- `add_action()`
- `add_shortcode()`
- `plugin_dir_path()`
- `plugin_dir_url()`
- `plugins_url()`
- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `wp_localize_script()`
- `get_option()`
- `get_locale()`
- `is_admin()`
- `do_shortcode()`
- `shortcode_atts()`

## Dołączane pliki / wyrażenia include

- `_once plugin_dir_path(__FILE__) . 'badge-local.php'`
- `_once plugin_dir_path(__FILE__) . 'for-exhibitors.php'`
- `_once plugin_dir_path(__FILE__) . 'for-visitors.php'`
- `_once plugin_dir_path(__FILE__) . 'kontakt.php'`
- `_once plugin_dir_path(__FILE__) . 'kontakt-info.php'`
- `_once plugin_dir_path(__FILE__) . 'gallery.php'`
- `_once plugin_dir_path(__FILE__) . 'videos.php'`
- `_once plugin_dir_path(__FILE__) . 'profile.php'`
- `_once plugin_dir_path(__FILE__) . 'promote-yourself.php'`
- `_once plugin_dir_path(__FILE__) . 'sticky-buttons.php'`
- `_once plugin_dir_path(__FILE__) . 'countdown.php'`
- `_once plugin_dir_path(__FILE__) . 'wydarzenia-ogolne.php'`
- `_once plugin_dir_path(__FILE__) . 'posts.php'`
- `_once plugin_dir_path(__FILE__) . 'footer.php'`
- `_once plugin_dir_path(__FILE__) . 'generator-wystawcow.php'`
- `_once plugin_dir_path(__FILE__) . 'registration.php'`
- `_once plugin_dir_path(__FILE__) . 'registration-content.php'`
- `_once plugin_dir_path(__FILE__) . 'zaproszenie.php'`
- `_once plugin_dir_path(__FILE__) . 'ticket.php'`
- `_once plugin_dir_path(__FILE__) . 'numbers.php'`
- `_once plugin_dir_path(__FILE__) . 'confSection.php'`
- `_once plugin_dir_path(__FILE__) . 'confHeader.php'`
- `_once plugin_dir_path(__FILE__) . 'trends-panel.php'`
- `_once plugin_dir_path(__FILE__) . 'mapa.php'`
- `_once plugin_dir_path(__FILE__) . 'mapa-test.php'`
- `_once plugin_dir_path(__FILE__) . 'step2.php'`
- `_once plugin_dir_path(__FILE__) . 'pot_rej.php'`
- `_once plugin_dir_path(__FILE__) . 'pot_rej_wys.php'`
- `_once plugin_dir_path(__FILE__) . 'pot_akt_bil.php'`
- `_once plugin_dir_path(__FILE__) . 'resend-ticket.php'`
- `_once plugin_dir_path(__FILE__) . 'header-new.php'`
- `_once plugin_dir_path(__FILE__) . 'hale.php'`
- `_once plugin_dir_path(__FILE__) . 'opinions.php'`
- `_once plugin_dir_path(__FILE__) . 'button.php'`
- `_once plugin_dir_path(__FILE__) . 'about.php'`
- `_once plugin_dir_path(__FILE__) . 'why-its-worth.php'`
- `_once plugin_dir_path(__FILE__) . 'qr-check.php'`
- `_once plugin_dir_path(__FILE__) . 'pot_vip.php'`
- `_once plugin_dir_path(__FILE__) . 'contact-form.php'`
- `_once plugin_dir_path(__FILE__) . 'single-image.php'`
- `_once plugin_dir_path(__FILE__) . 'medals.php'`
- `_once plugin_dir_path(__FILE__) . 'other_events.php'`
- `_once plugin_dir_path(__FILE__) . 'two_cols.php'`
- `_once plugin_dir_path(__FILE__) . 'timeline-stats.php'`
- `_once plugin_dir_path(__FILE__) . 'popup.php'`
- `_once plugin_dir_path(__FILE__) . 'news-summary.php'`
- `_once plugin_dir_path(__FILE__) . 'medal-form.php'`
- `_once plugin_dir_path(__FILE__) . 'feedback.php'`
- `_once plugin_dir_path(__FILE__) . '/../backend/load-more-posts.php'`
- `_once plugin_dir_path(__FILE__) . $this->findClassElements()[$pwe_element]`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
