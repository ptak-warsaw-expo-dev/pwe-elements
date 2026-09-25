---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# PWE Elements 3.6.8 — dokumentacja techniczna

Dokumentacja powstała na podstawie rzeczywistego kodu z przesłanego archiwum **PWE Elements 3.6.8**. Katalog `wiki/` jest warstwą wiedzy przeznaczoną do commitowania razem z repozytorium i indeksowania przez prywatną Developer Wiki.

## Co robi wtyczka

`PWE Elements` jest dużą warstwą integracyjną WordPressa dla stron Ptak Warsaw Expo. Łączy elementy WPBakery, shortcody danych targowych, Gravity Forms, rejestracje, generatory wystawców i gości, katalog, kalendarze, treści targowe oraz integracje z innymi pluginami PWE.

Najważniejszą cechą architektury 3.6.8 jest **warstwa kompatybilności z PWE System**. Jeżeli PWE System jest aktywny, wspólne funkcje i niskopoziomowe `[pwe_*]` shortcody są delegowane do niego. W przeciwnym razie wtyczka ładuje lokalny fallback `pwefunctions.php` + `backend/shortcodes.php`.

## Najważniejsze punkty wejścia

| Obszar | Dokument |
|---|---|
| Architektura | [architecture/overview.md](architecture/overview.md) |
| Bootstrap i lifecycle | [architecture/bootstrap-lifecycle.md](architecture/bootstrap-lifecycle.md) |
| PWE System / fallback | [architecture/system-compatibility.md](architecture/system-compatibility.md) |
| Elementy i WPBakery | [architecture/elements-and-wpbakery.md](architecture/elements-and-wpbakery.md) |
| Gravity Forms i sesje | [architecture/gravity-forms-and-sessions.md](architecture/gravity-forms-and-sessions.md) |
| Integracje i dane | [architecture/data-and-integrations.md](architecture/data-and-integrations.md) |
| Kod aktywny / fallback / dormant | [architecture/load-status.md](architecture/load-status.md) |
| Shortcody | [shortcodes/index.md](shortcodes/index.md) |
| Endpointy / AJAX / CLI | [endpoints/index.md](endpoints/index.md) |
| Hooki | [hooks/index.md](hooks/index.md) |
| Procesy biznesowe | [processes/index.md](processes/index.md) |
| Symbole PHP | [symbols/index.md](symbols/index.md) |
| Pliki PHP | [files/index.md](files/index.md) |
| Integracje | [reference/integrations.md](reference/integrations.md) |
| Bezpieczeństwo | [security/review.md](security/review.md) |

## Inwentaryzacja

- wszystkie pliki archiwum: **1060**,
- pliki PHP first-party indeksowane strukturalnie: **261**,
- klasy: **211**,
- metody: **810**,
- funkcje globalne: **202**,
- deterministyczne shortcody w `shortcodes.json`: **86**,
- rodziny shortcode'ów zależne od języka: **9**,
- rejestracje hooków: **128**,
- endpointy/handlery HTTP, AJAX i CLI: **10**.

## Wiki v2

Wiki zachowuje zgodne z dotychczasową aplikacją pliki `files.json`, `php-symbols.json`, `file-analysis.json`, `shortcodes.json`, `endpoints.json`, `hooks.json`, `pages.json` i `documents.json`. Dodatkowo zawiera rozszerzenia v2: `load-graph.json`, `entrypoints.json`, `integrations.json`, `dependencies.json`, `processes.json` i `shortcode-families.json`.

## Zasada interpretacji

Istnienie pliku nie oznacza, że kod jest wykonywany. W tej wersji występują moduły aktywne, warunkowe, lokalne fallbacki, delegacje do PWE System, pliki bezpośrednich endpointów oraz moduły pozostawione w repozytorium, których bootstrap obecnie nie ładuje. Status ten jest jawnie dokumentowany w `inventory/load-graph.json`.
