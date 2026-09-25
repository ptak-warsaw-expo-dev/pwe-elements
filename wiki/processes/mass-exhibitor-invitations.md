---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: masowe generowanie zaproszeń / rejestracji wystawcy

## Entry point UI

`PWEExhibitorGenerator` rejestruje shortcode `pwe_exhibitor_generator`. `PWEExhibitorGeneratorOutput()` wybiera tryb generatora, dołącza odpowiednią klasę i tworzy jej instancję. Dla masowej wysyłki używana jest klasa `PWEMassVipSender`.

## Kontrola pojemności wysyłki

`PWEMassVipSender::senderFlowChecker()` sprawdza tabelę `<prefix>mass_exhibitors_invite_query`, liczy rekordy `status=new` i porównuje kolejkę z przybliżoną pojemnością wysyłki do rozpoczęcia targów.

## Direct HTTP

Frontend otrzymuje URL `includes/exhibitor-generator/assets/mass_vip.php`. Handler POST:

1. ładuje WordPress,
2. weryfikuje HMAC domeny oparty o `AUTH_KEY`,
3. pobiera formularz Gravity Forms wskazany przez `formId`,
4. mapuje pola formularza po label/adminLabel,
5. opcjonalnie zapisuje uploadowane logo w `uploads/generator-wystawcow`,
6. dla każdej osoby wykonuje `GFAPI::add_entry()`,
7. poprawne rekordy wysyła partiami do `custom-element/gf_integration/salesmanago_send_mass.php`,
8. tworzy tabelę `mass_exhibitors_invite_query`, jeśli jej nie ma,
9. zapisuje identyfikatory GF entry do kolejki ze statusem `new`, a błędne e-maile jako `error`.

## Ważne rozróżnienie

W repozytorium istnieje również starszy `other/mass_vip.php`, referencjonowany przez legacy elementy. Nie należy łączyć obu handlerów w jeden symbol — to dwie implementacje endpointu.
