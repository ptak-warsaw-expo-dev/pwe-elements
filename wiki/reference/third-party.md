---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Bundlowane zależności zewnętrzne

- `plugin-update-checker/` — Plugin Update Checker,
- `assets/tcpdf/` — TCPDF,
- `other/FPDI/` — FPDI.

Pliki pozostają w `files.json`, ale są wyłączone z first-party `php-symbols.json`, aby nie zanieczyszczać wyszukiwania symboli i Call Graphu kodem bibliotek.
