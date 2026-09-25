---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Shortcode `[pwe_exhibitors]`

- **Typ:** `dynamic-map`
- **Źródło:** `backend/shortcodes.php:161`
- **Callback / dispatcher:** `handle_fair_shortcode($atts, "fair_exhibitors_current")`
- **Load status:** `fallback`
- **Callback mode:** `closure-dispatch`

## Opis

Zwraca pole `fair_exhibitors_current` dla bieżącej lub wskazanej domeny. Rejestracja wykonywana przez mapę `pwe_get_shortcode_map()`.
