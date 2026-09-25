---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# Dane i integracje

## Dane targowe

Lokalny fallback `backend/shortcodes.php` buduje cache danych targów przez `PWECommonFunctions::get_database_fairs_data()` i `get_database_translations_data()`. Jeżeli dane nie są dostępne, kod posiada fallback HTTP do pliku JSON z danymi targowymi.

## `custom-element`

Kilka procesów komunikuje się z pluginem `custom-element` przez HTTP, m.in. `action_handler.php`, `salesmanago_send_mass.php` i legacy `cron_catalog.php`. Jest to zależność cross-plugin, której nie da się poprawnie zamknąć w lokalnym call graphie PWE Elements.

## WPML

WPML wpływa m.in. na listę języków dynamicznych shortcode'ów, tłumaczenia eventów i część danych wielojęzycznych.

## WP Rocket

Bootstrap rejestruje wykluczenia dla delayed/deferred JS i czyści cache po aktualizacji pluginu.
