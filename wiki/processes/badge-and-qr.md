---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: badge i QR

`PWBadgeElement` zawiera dwa istotne warianty masowe: `massGenerator()` oraz `qrOnlyDownload()`.

W obu przepływach kod tworzy wpisy Gravity Forms przez `GFAPI::add_entry()`, pobiera utworzone entry, ręcznie wywołuje `gform_after_submission` i odczytuje feedy dodatku `qr-code`. Dalsze kroki wykorzystują wynik feedu do pobrania/generowania plików QR.

`pwe_download_temp_qr()` pobiera zasób QR przez WordPress HTTP API i zwraca jego zawartość przy poprawnej odpowiedzi.

Proces należy łączyć z pluginem QR Gravity Forms, gdy ten zostanie dodany do globalnego graphu, ponieważ lokalny kod PWE Elements tylko konsumuje feed typu `qr-code`.
