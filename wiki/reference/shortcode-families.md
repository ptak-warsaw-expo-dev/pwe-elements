---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Dynamiczne rodziny shortcode'ów

`backend/shortcodes.php` tworzy w runtime rodziny zależne od aktywnych języków WPML. Nie są one wpisane do kompatybilnego `shortcodes.json` jako fikcyjne literalne tagi z `{lang}`. Zamiast tego wzorce są w `inventory/shortcode-families.json`.

- `pwe_name_{lang}` → pole `name_{lang}`
- `pwe_desc_{lang}` → pole `desc_{lang}`
- `pwe_short_desc_{lang}` → pole `short_desc_{lang}`
- `pwe_full_desc_{lang}` → pole `full_desc_{lang}`
- `pwe_conference_title_{lang}` → pole `conference_title_{lang}`
- `pwe_conference_desc_{lang}` → pole `conference_desc_{lang}`
- `pwe_about_title_{lang}` → pole `about_title_{lang}`
- `pwe_about_desc_{lang}` → pole `about_desc_{lang}`
- `pwe_category_{lang}` → pole `category_{lang}`
