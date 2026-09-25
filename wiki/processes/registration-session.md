---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: rejestracja → sesja → kolejny krok

## 1. Shortcode

`PWERegistration` rejestruje `[pwe_registration]`. `PWERegistrationOutput()` wybiera klasę odpowiednią dla typu rejestracji i renderuje właściwy wariant.

## 2. `gform_after_submission`

`PWERegistration::entryToSession()`:

- uruchamia sesję PHP,
- rozpoznaje aktualną stronę na podstawie tłumaczeń URL (z fallbackiem na znane ścieżki),
- dla ścieżki wystawcy zapisuje `pwe_exhibitor_entry`,
- dla rejestracji odwiedzającego zapisuje `pwe_reg_entry`, w tym `utm_source` oraz query UTM,
- kopiuje email i telefon z pól Gravity Forms.

## 3. Kolejne requesty

Dane sesji są używane przez dalsze kroki, m.in. bezpośredni handler `elements/fetch.php`, który aktualizuje entry GF i może wysłać wybrane powiadomienie, oraz `elements/session_fetch.php`, który zwraca email/telefon z sesji.

## 4. Integracja po aktualizacji

`elements/fetch.php` może wysłać nieblokujący POST do `custom-element/action_handler.php` z informacją o `gform_after_submission`.
