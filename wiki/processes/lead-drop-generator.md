---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: import/drop leadów do Gravity Forms

`other/drop_generator.php` jest bezpośrednim endpointem POST.

1. Wymusza HTTPS.
2. Odczytuje JSON z `php://input` i token z `Authorization`.
3. Weryfikuje token domenowy.
4. Ładuje WordPress i Gravity Forms.
5. Wyszukuje formularz rejestracji PL/EN.
6. Sprawdza duplikaty, przeglądając wpisy formularzy rejestracyjnych.
7. Dla nowego leada buduje entry z e-mailem, telefonem i UTM.
8. `GFAPI::add_entry()` zapisuje wpis.
9. Kod pobiera pełne entry i ręcznie uruchamia `do_action('gform_after_submission', ...)`.
10. W dalszej części może wysłać powiadomienia GF.

Jest to ważny przykład procesu, w którym entry nie powstaje przez standardowe submit formularza, ale downstream lifecycle GF jest uruchamiany ręcznie.
