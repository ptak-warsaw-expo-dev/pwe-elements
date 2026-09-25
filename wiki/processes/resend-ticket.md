---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: ponowne wysłanie biletu

`PWEResendTicket` rejestruje `notification_sender()` na hooku `wp`.

1. Handler wymaga parametru `?data=`.
2. Wartość jest dekodowana z base64 do pary `form_id,entry_id`.
3. Pobierany jest formularz i entry przez `GFAPI`.
4. Kod aktywuje tylko wybrane powiadomienia biletu PL/EN, pozostałe dezaktywuje w lokalnej strukturze formularza.
5. `GFAPI::send_notifications()` wysyła wiadomość ponownie.

Parametr jest identyfikatorem transportowym, nie szyfrowaniem — base64 nie zapewnia poufności ani autoryzacji.
