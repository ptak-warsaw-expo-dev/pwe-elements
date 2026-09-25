---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: katalog wystawców

`PWECatalog` ładuje helper `classes/catalog_functions.php`, rejestruje assety, mapę WPBakery i shortcode `[pwe_katalog]`.

`PWECatalogOutput()` pobiera konfigurację, wybiera format katalogu i dynamicznie dołącza odpowiedni plik/klasę renderującą. Kod zawiera również legacy integrację z `custom-element/other/cron_catalog.php` do odświeżania katalogu.

Ze względu na dynamiczny wybór formatu pełny przepływ renderowania może nie być widoczny jako pojedyncze statyczne wywołanie w Call Graphie.
