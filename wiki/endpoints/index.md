---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Endpointy, AJAX i CLI

| ID | Typ | Trigger | Źródło |
|---|---|---|---|
| [ajax-load-more-posts](ajax-load-more-posts.md) | `wp-ajax` | `admin-ajax.php?action=load_more_posts` | `backend/load-more-posts.php` |
| [ajax-pwe-load-posts](ajax-pwe-load-posts.md) | `wp-ajax` | `admin-ajax.php?action=pwe_ajax_load_posts` | `includes/posts/assets/ajax.php` |
| [ajax-load-more-calendar](ajax-load-more-calendar.md) | `wp-ajax` | `admin-ajax.php?action=load_more_calendar` | `includes/calendar/classes/loop-calendar.php` |
| [mass-vip-generator](mass-vip-generator.md) | `direct-http` | `/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/mass_vip.php` | `includes/exhibitor-generator/assets/mass_vip.php` |
| [legacy-mass-vip](legacy-mass-vip.md) | `direct-http` | `/wp-content/plugins/PWElements/other/mass_vip.php` | `other/mass_vip.php` |
| [lead-drop-generator](lead-drop-generator.md) | `direct-http` | `/wp-content/plugins/PWElements/other/drop_generator.php` | `other/drop_generator.php` |
| [exhibitors-count](exhibitors-count.md) | `direct-http` | `/wp-content/plugins/PWElements/other/exhibitors_count.php` | `other/exhibitors_count.php` |
| [session-entry-update](session-entry-update.md) | `direct-http` | `/wp-content/plugins/PWElements/elements/fetch.php` | `elements/fetch.php` |
| [session-fetch](session-fetch.md) | `direct-http` | `/wp-content/plugins/PWElements/elements/session_fetch.php` | `elements/session_fetch.php` |
| [save-fairs-json](save-fairs-json.md) | `cli-cron` | `php other/save_fairs_json.php <token>` | `other/save_fairs_json.php` |
