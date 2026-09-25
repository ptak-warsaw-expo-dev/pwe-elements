---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: moduł mailingowy — obecnie dormant

`includes/mailing/mailing.php` zawiera klasę `PWEMailing` i podmoduły `FormGenerator`, `NotificationProcessor` oraz presety formularzy/powiadomień.

Kod rejestruje działania na `gform_loaded`, potrafi tworzyć/aktualizować formularze, konfigurować resend, włączać honeypot i czyścić wpisy katalogowego feedbacku.

W **bootstrapie 3.6.8 require tego modułu oraz utworzenie `PWEMailing` są zakomentowane**. Dokumentacja traktuje więc ten proces jako istniejący kod, ale nie jako aktywny lifecycle bieżącej wersji. Jeżeli moduł jest ładowany z zewnątrz lub aktywowany w innej gałęzi, status należy zaktualizować.
