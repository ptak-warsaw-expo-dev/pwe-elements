---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Przegląd bezpieczeństwa — punkty do weryfikacji

To nie jest audyt penetracyjny. Lista wskazuje miejsca, które z kodu 3.6.8 wymagają szczególnej uwagi.

## Bezpośrednie endpointy w katalogu pluginu

Wtyczka zawiera kilka plików możliwych do wywołania bezpośrednio przez HTTP (`mass_vip.php`, `drop_generator.php`, `exhibitors_count.php`, `elements/fetch.php`, `session_fetch.php`). Część z nich wykonuje operacje na Gravity Forms lub danych sesji.

## Sekrety zapisane w kodzie

W legacy handlerach występują stałe/sekrety autoryzacyjne zapisane bezpośrednio w źródle. Dokumentacja celowo **nie kopiuje ich wartości**. Warto przenieść je do konfiguracji/stałych środowiskowych i rotować, jeśli były używane produkcyjnie.

## `session_fetch.php`

Handler zwraca email i telefon z aktywnej sesji bez jawnej weryfikacji nonce/uprawnień. Zakres ryzyka zależy od polityki cookies/sesji i sposobu osadzenia endpointu.

## `resend-ticket`

Parametr `data` jest base64 pary `form_id,entry_id`; base64 nie jest mechanizmem autoryzacji. Należy ocenić, czy dostęp do resendu powinien wymagać nonce/tokena o ograniczonym czasie życia.

## Public AJAX

Handlery `wp_ajax_nopriv_*` są publiczne. Dla każdego warto osobno potwierdzić walidację parametrów, limity i ochronę przed nadużyciem.

## Kod dormant / legacy

Kod nieładowany przez bootstrap nadal może być dostępny bezpośrednio, jeśli znajduje się pod publicznym URL pluginu. `dormant` nie oznacza automatycznie `nieosiągalny przez HTTP`.
