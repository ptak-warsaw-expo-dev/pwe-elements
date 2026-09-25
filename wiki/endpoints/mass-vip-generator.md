---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# `mass-vip-generator`

- **Typ:** `direct-http`
- **Trigger/route:** `/wp-content/plugins/PWElements/includes/exhibitor-generator/assets/mass_vip.php`
- **Metody:** `POST`
- **Źródło:** `includes/exhibitor-generator/assets/mass_vip.php`
- **Autoryzacja wg kodu:** HMAC domeny wyliczany z AUTH_KEY; endpoint tworzy wpisy Gravity Forms i kolejkę mass_exhibitors_invite_query

## Interpretacja

Ten wpis opisuje techniczny punkt wejścia wykryty w wersji 3.6.8. Dalszy przepływ należy śledzić od wskazanego pliku/callbacku przez `Symbols`, `Hooks` i `Calls / Called by`.
