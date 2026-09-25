---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# Gravity Forms i sesje

Gravity Forms jest jedną z głównych zależności biznesowych PWE Elements.

## Najczęstsze operacje

Kod używa m.in. `GFAPI::get_forms()`, `get_form()`, `get_entries()`, `get_entry()`, `add_entry()`, `update_entry_field()`, `update_form()`, `get_feeds()` oraz `send_notifications()`.

## Sesje rejestracji

`PWERegistration::entryToSession()` działa na `gform_after_submission`. Rozpoznaje, czy submission pochodzi ze strony wystawcy czy rejestracji odwiedzającego, a następnie zapisuje identyfikator entry oraz dane pomocnicze do:

- `$_SESSION['pwe_exhibitor_entry']`,
- `$_SESSION['pwe_reg_entry']`.

Dla rejestracji zachowuje również `utm_source` i pełny query string UTM. Email i telefon są kopiowane z pól formularza do sesji.

## Skutki

Te dane sesyjne są później używane przez kolejne kroki rejestracji oraz bezpośrednie handlery HTTP. Dlatego proces rejestracji należy analizować jako przepływ wielo-requestowy, a nie pojedynczą funkcję.
