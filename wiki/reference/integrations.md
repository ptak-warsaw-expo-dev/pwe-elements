---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Integracje

- **WordPress** — `platform`
- **Gravity Forms** — `plugin`
- **WPBakery / Visual Composer** — `plugin`
- **WPML** — `plugin`
- **WP Rocket** — `plugin`
- **PWE System** — `pwe-plugin`; optional delegation with local fallback
- **custom-element** — `pwe-plugin`; HTTP integration
- **CAP / PWE data layer** — `database/external-data`; through PWECommonFunctions or PWE System compatibility layer

## PWE System

Najważniejsza zależność architektoniczna: PWE Elements potrafi delegować wspólne funkcje i backend shortcode'ów do PWE System, zachowując lokalny fallback.

## custom-element

W kodzie istnieją bezpośrednie wywołania HTTP do ścieżek pluginu `custom-element`. Po dodaniu tego pluginu do Developer Wiki powinny stać się krawędziami cross-plugin zamiast nierozwiązanych zewnętrznych URL-i.
