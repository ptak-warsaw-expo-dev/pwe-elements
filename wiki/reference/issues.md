---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Znane punkty do weryfikacji

Ta lista oddziela wykryte sygnały jakości/bezpieczeństwa od zwykłej dokumentacji. Wpis `review` nie jest automatycznie potwierdzonym błędem produkcyjnym.

## `calendar-ajax-required-argument`

- **Status:** `needs-runtime-verification`
- **Typ:** `review`
- Callback AJAX load_more_calendar jest rejestrowany bez accepted_args, ale metoda deklaruje wymagany parametr.
- **Źródło:** `includes/calendar/classes/loop-calendar.php` — linie 8, 9, 1700
- **Dlaczego:** WordPress wp_ajax_* wywołuje callback bez argumentów; sygnatura load_more_calendar($pwe_calendar_pagination) nie ma wartości domyślnej.

## `fetch-undefined-result`

- **Status:** `likely-bug`
- **Typ:** `review`
- elements/fetch.php sprawdza is_wp_error($result), ale w widocznym przepływie zmienna $result nie jest wcześniej przypisana.
- **Źródło:** `elements/fetch.php` — linie 127

## `hardcoded-auth-secrets`

- **Status:** `review-and-rotate`
- **Typ:** `security`
- Legacy direct HTTP handlery zawierają sekrety/tokeny autoryzacyjne zapisane bezpośrednio w kodzie.

- **Źródła:** `elements/fetch.php`, `other/mass_vip.php`, `other/drop_generator.php`, `other/exhibitors_count.php`
- **Uwaga:** Wartości sekretów celowo nie są kopiowane do Wiki.

## `session-fetch-no-explicit-auth`

- **Status:** `review`
- **Typ:** `security`
- elements/session_fetch.php zwraca email i telefon z bieżącej sesji bez jawnego nonce/uprawnienia.
- **Źródło:** `elements/session_fetch.php` — linie 2, 7, 8

## `resend-ticket-base64-identifier`

- **Status:** `review`
- **Typ:** `security`
- Resend ticket identyfikuje form/entry przez base64 w query stringu; base64 nie jest kontrolą dostępu.
- **Źródło:** `elements/resend-ticket.php` — linie 15, 22, 23, 68

