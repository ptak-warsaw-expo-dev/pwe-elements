---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Shortcody

`shortcodes.json` zawiera deterministyczne tagi, które można wskazać bezpośrednio z kodu lub stałej mapy. Językowe rodziny tworzone w runtime są opisane osobno w `inventory/shortcode-families.json` i [reference/shortcode-families.md](../reference/shortcode-families.md).

| Shortcode | Typ | Callback / dispatcher | Źródło |
|---|---|---|---|
| [`[accent_color]`](accent_color.md) | `dynamic-color` | `PWEStyleVar::get_color("accent", "base")` | `pwe-style-var.php:37` |
| [`[accent_dark_color]`](accent_dark_color.md) | `dynamic-color` | `PWEStyleVar::get_color("accent", "dark")` | `pwe-style-var.php:49` |
| [`[accent_darker_color]`](accent_darker_color.md) | `dynamic-color` | `PWEStyleVar::get_color("accent", "darker")` | `pwe-style-var.php:53` |
| [`[accent_light_color]`](accent_light_color.md) | `dynamic-color` | `PWEStyleVar::get_color("accent", "light")` | `pwe-style-var.php:41` |
| [`[accent_lighter_color]`](accent_lighter_color.md) | `dynamic-color` | `PWEStyleVar::get_color("accent", "lighter")` | `pwe-style-var.php:45` |
| [`[color_mix]`](color_mix.md) | `static` | `PWEStyleVar::handle_color_mix` | `pwe-style-var.php:59` |
| [`[main2_color]`](main2_color.md) | `dynamic-color` | `PWEStyleVar::get_color("main2", "base")` | `pwe-style-var.php:37` |
| [`[main2_dark_color]`](main2_dark_color.md) | `dynamic-color` | `PWEStyleVar::get_color("main2", "dark")` | `pwe-style-var.php:49` |
| [`[main2_darker_color]`](main2_darker_color.md) | `dynamic-color` | `PWEStyleVar::get_color("main2", "darker")` | `pwe-style-var.php:53` |
| [`[main2_light_color]`](main2_light_color.md) | `dynamic-color` | `PWEStyleVar::get_color("main2", "light")` | `pwe-style-var.php:41` |
| [`[main2_lighter_color]`](main2_lighter_color.md) | `dynamic-color` | `PWEStyleVar::get_color("main2", "lighter")` | `pwe-style-var.php:45` |
| [`[pwe_about_desc_en]`](pwe_about_desc_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "about_desc_en")` | `backend/shortcodes.php:194` |
| [`[pwe_about_desc_pl]`](pwe_about_desc_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "about_desc_pl")` | `backend/shortcodes.php:193` |
| [`[pwe_about_fair_info]`](pwe_about_fair_info.md) | `static` | `PWEAboutFairInfo::PWEAboutFairInfoOutput` | `includes/about-fair-info/about-fair-info.php:34` |
| [`[pwe_about_title_en]`](pwe_about_title_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "about_title_en")` | `backend/shortcodes.php:192` |
| [`[pwe_about_title_pl]`](pwe_about_title_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "about_title_pl")` | `backend/shortcodes.php:191` |
| [`[pwe_area]`](pwe_area.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_area_current")` | `backend/shortcodes.php:163` |
| [`[pwe_area_prev]`](pwe_area_prev.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_area_previous")` | `backend/shortcodes.php:169` |
| [`[pwe_article_author]`](pwe_article_author.md) | `static` | `PWEArticleAuthorManager::PWEArticleAuthorOutput` | `includes/article_author/article_author.php:15` |
| [`[pwe_attractions]`](pwe_attractions.md) | `static` | `PWEAttractions::PWEAttractionsOutput` | `includes/attractions/attractions.php:13` |
| [`[pwe_badge]`](pwe_badge.md) | `dynamic-map` | `handle_fair_shortcode($atts, "badge")` | `backend/shortcodes.php:175` |
| [`[pwe_calendar]`](pwe_calendar.md) | `static` | `PWECalendar::pwe_calendar_loop_output` | `includes/calendar/classes/loop-calendar.php:11` |
| [`[pwe_catalog]`](pwe_catalog.md) | `dynamic-map` | `handle_fair_shortcode($atts, "catalog")` | `backend/shortcodes.php:180` |
| [`[pwe_catalog_id]`](pwe_catalog_id.md) | `dynamic-map` | `handle_fair_shortcode($atts, "catalog_id")` | `backend/shortcodes.php:181` |
| [`[pwe_category_en]`](pwe_category_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "category_en")` | `backend/shortcodes.php:183` |
| [`[pwe_category_pl]`](pwe_category_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "category_pl")` | `backend/shortcodes.php:182` |
| [`[pwe_color_accent]`](pwe_color_accent.md) | `dynamic-map` | `handle_fair_shortcode($atts, "color_accent")` | `backend/shortcodes.php:173` |
| [`[pwe_color_main2]`](pwe_color_main2.md) | `dynamic-map` | `handle_fair_shortcode($atts, "color_main2")` | `backend/shortcodes.php:174` |
| [`[pwe_conference_calendar]`](pwe_conference_calendar.md) | `static` | `PWEConferenceCalendar::pwe_conference_calendar_loop_output` | `includes/conference-calendar/conference-calendar.php:9` |
| [`[pwe_conference_cap]`](pwe_conference_cap.md) | `static` | `PWE_Conference_Cap_Legacy_Renderer::PWEConferenceCapOutput` | `includes/conference-cap/core/legacy-shortcode-renderer.php:24` |
| [`[pwe_conference_desc_en]`](pwe_conference_desc_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "conference_desc_en")` | `backend/shortcodes.php:190` |
| [`[pwe_conference_desc_pl]`](pwe_conference_desc_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "conference_desc_pl")` | `backend/shortcodes.php:189` |
| [`[pwe_conference_name]`](pwe_conference_name.md) | `dynamic-map` | `handle_fair_shortcode($atts, "conference_name")` | `backend/shortcodes.php:186` |
| [`[pwe_conference_short_info]`](pwe_conference_short_info.md) | `static` | `PWEConferenceShortInfo::PWEConferenceShortInfoOutput` | `includes/conference-short-info/conference-short-info.php:36` |
| [`[pwe_conference_title_en]`](pwe_conference_title_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "conference_title_en")` | `backend/shortcodes.php:188` |
| [`[pwe_conference_title_pl]`](pwe_conference_title_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "conference_title_pl")` | `backend/shortcodes.php:187` |
| [`[pwe_countries]`](pwe_countries.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_countries_current")` | `backend/shortcodes.php:162` |
| [`[pwe_countries_prev]`](pwe_countries_prev.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_countries_previous")` | `backend/shortcodes.php:168` |
| [`[pwe_date_end]`](pwe_date_end.md) | `dynamic-map` | `handle_fair_shortcode($atts, "date_end")` | `backend/shortcodes.php:156` |
| [`[pwe_date_end_hour]`](pwe_date_end_hour.md) | `dynamic-map` | `handle_fair_shortcode($atts, "date_end_hour")` | `backend/shortcodes.php:157` |
| [`[pwe_date_start]`](pwe_date_start.md) | `dynamic-map` | `handle_fair_shortcode($atts, "date_start")` | `backend/shortcodes.php:154` |
| [`[pwe_date_start_hour]`](pwe_date_start_hour.md) | `dynamic-map` | `handle_fair_shortcode($atts, "date_start_hour")` | `backend/shortcodes.php:155` |
| [`[pwe_desc_en]`](pwe_desc_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "desc_en")` | `backend/shortcodes.php:148` |
| [`[pwe_desc_pl]`](pwe_desc_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "desc_pl")` | `backend/shortcodes.php:147` |
| [`[pwe_display_info]`](pwe_display_info.md) | `static` | `PWEDisplayInfo::PWEDisplayInfoOutput` | `includes/display-info/display-info.php:26` |
| [`[pwe_edition]`](pwe_edition.md) | `dynamic-map` | `handle_fair_shortcode($atts, "edition")` | `backend/shortcodes.php:158` |
| [`[pwe_exhibitor_generator]`](pwe_exhibitor_generator.md) | `static` | `PWEExhibitorGenerator::PWEExhibitorGeneratorOutput` | `includes/exhibitor-generator/exhibitor-generator.php:42` |
| [`[pwe_exhibitors]`](pwe_exhibitors.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_exhibitors_current")` | `backend/shortcodes.php:161` |
| [`[pwe_exhibitors_prev]`](pwe_exhibitors_prev.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_exhibitors_previous")` | `backend/shortcodes.php:167` |
| [`[pwe_facebook]`](pwe_facebook.md) | `dynamic-map` | `handle_fair_shortcode($atts, "facebook")` | `backend/shortcodes.php:176` |
| [`[pwe_fair_id]`](pwe_fair_id.md) | `dynamic-map` | `handle_fair_shortcode($atts, "id")` | `backend/shortcodes.php:149` |
| [`[pwe_full_desc_en]`](pwe_full_desc_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "full_desc_en")` | `backend/shortcodes.php:153` |
| [`[pwe_full_desc_pl]`](pwe_full_desc_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "full_desc_pl")` | `backend/shortcodes.php:152` |
| [`[pwe_group]`](pwe_group.md) | `dynamic-map` | `handle_fair_shortcode($atts, "group")` | `backend/shortcodes.php:185` |
| [`[pwe_hall]`](pwe_hall.md) | `dynamic-map` | `handle_fair_shortcode($atts, "hall")` | `backend/shortcodes.php:171` |
| [`[pwe_hall_entrance]`](pwe_hall_entrance.md) | `dynamic-map` | `handle_fair_shortcode($atts, "hall_entrance")` | `backend/shortcodes.php:172` |
| [`[pwe_header]`](pwe_header.md) | `static` | `PWEHeader::PWEHeaderOutput` | `includes/header/header.php:29` |
| [`[pwe_industry]`](pwe_industry.md) | `dynamic-map` | `handle_fair_shortcode($atts, "industry")` | `backend/shortcodes.php:184` |
| [`[pwe_industryevening]`](pwe_industryevening.md) | `static` | `PWEIndustryEvening::PWEIndustryEveningOutput` | `includes/industry-evening/industry-evening.php:13` |
| [`[pwe_instagram]`](pwe_instagram.md) | `dynamic-map` | `handle_fair_shortcode($atts, "instagram")` | `backend/shortcodes.php:177` |
| [`[pwe_katalog]`](pwe_katalog.md) | `static` | `PWECatalog::PWECatalogOutput` | `includes/katalog-wystawcow/main-katalog-wystawcow.php:29` |
| [`[pwe_linkedin]`](pwe_linkedin.md) | `dynamic-map` | `handle_fair_shortcode($atts, "linkedin")` | `backend/shortcodes.php:178` |
| [`[pwe_logotypes]`](pwe_logotypes.md) | `static` | `PWELogotypes::PWELogotypesOutput` | `includes/logotypes/logotypes.php:26` |
| [`[pwe_map]`](pwe_map.md) | `static` | `PWEMap::PWEMapOutput` | `includes/map/map.php:32` |
| [`[pwe_media_gallery]`](pwe_media_gallery.md) | `static` | `PWEMediaGallery::PWEMediaGalleryOutput` | `includes/media-gallery/media-gallery.php:18` |
| [`[pwe_name_en]`](pwe_name_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "name_en")` | `backend/shortcodes.php:146` |
| [`[pwe_name_pl]`](pwe_name_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "name_pl")` | `backend/shortcodes.php:145` |
| [`[pwe_news]`](pwe_news.md) | `static` | `PWENews::PWENewsOutput` | `includes/news/news.php:15` |
| [`[pwe_posts]`](pwe_posts.md) | `static` | `PWEPosts::pwePostsOutput` | `includes/posts/posts.php:18` |
| [`[pwe_premieres]`](pwe_premieres.md) | `static` | `PWEPremieres::PWEPremieresOutput` | `includes/premieres/premieres.php:14` |
| [`[pwe_profile]`](pwe_profile.md) | `static` | `PWEProfile::PWEProfileOutput` | `includes/profile/profile.php:30` |
| [`[pwe_qr_active]`](pwe_qr_active.md) | `static` | `PWEQRActive::PWEQRActiveOutput` | `qr-active/main-qr-active.php:13` |
| [`[pwe_registration]`](pwe_registration.md) | `static` | `PWERegistration::PWERegistrationOutput` | `includes/registration/registration.php:33` |
| [`[pwe_reviews]`](pwe_reviews.md) | `static` | `PWEReviews::PWEReviewsOutput` | `includes/reviews/reviews.php:15` |
| [`[pwe_short_desc_en]`](pwe_short_desc_en.md) | `dynamic-map` | `handle_fair_shortcode($atts, "short_desc_en")` | `backend/shortcodes.php:151` |
| [`[pwe_short_desc_pl]`](pwe_short_desc_pl.md) | `dynamic-map` | `handle_fair_shortcode($atts, "short_desc_pl")` | `backend/shortcodes.php:150` |
| [`[pwe_statistics_year_curr]`](pwe_statistics_year_curr.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_year_current")` | `backend/shortcodes.php:164` |
| [`[pwe_statistics_year_prev]`](pwe_statistics_year_prev.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_year_previous")` | `backend/shortcodes.php:170` |
| [`[pwe_store]`](pwe_store.md) | `static` | `PWEStore::PWEStoreOutput` | `includes/store/store.php:14` |
| [`[pwe_test]`](pwe_test.md) | `static` | `PWETest::PWETestOutput` | `other/test.php:14` |
| [`[pwe_visitors]`](pwe_visitors.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_visitors_current")` | `backend/shortcodes.php:159` |
| [`[pwe_visitors_foreign]`](pwe_visitors_foreign.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_foreign_current")` | `backend/shortcodes.php:160` |
| [`[pwe_visitors_foreign_prev]`](pwe_visitors_foreign_prev.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_foreign_previous")` | `backend/shortcodes.php:166` |
| [`[pwe_visitors_prev]`](pwe_visitors_prev.md) | `dynamic-map` | `handle_fair_shortcode($atts, "fair_visitors_previous")` | `backend/shortcodes.php:165` |
| [`[pwe_youtube]`](pwe_youtube.md) | `dynamic-map` | `handle_fair_shortcode($atts, "youtube")` | `backend/shortcodes.php:179` |
| [`[pwelement]`](pwelement.md) | `static` | `PWElements::PWElementsOutput` | `elements/pwelements-options.php:33` |
