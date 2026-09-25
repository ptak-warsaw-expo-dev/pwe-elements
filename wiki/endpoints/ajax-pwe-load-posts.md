---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# `ajax-pwe-load-posts`

- **Typ:** `wp-ajax`
- **Trigger/route:** `admin-ajax.php?action=pwe_ajax_load_posts`
- **Metody:** `POST`
- **Źródło:** `includes/posts/assets/ajax.php`
- **Autoryzacja wg kodu:** wp_ajax oraz wp_ajax_nopriv; brak jawnego nonce w rejestracji

## Interpretacja

Ten wpis opisuje techniczny punkt wejścia wykryty w wersji 3.6.8. Dalszy przepływ należy śledzić od wskazanego pliku/callbacku przez `Symbols`, `Hooks` i `Calls / Called by`.
