---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# Status ładowania kodu

Nie każdy plik obecny w repozytorium jest częścią aktywnego requestu.

| Status | Znaczenie |
|---|---|
| `bootstrap` | główny punkt startowy pluginu |
| `active` | ładowany z bieżącego bootstrapu |
| `conditional` | ładowany po spełnieniu warunku |
| `fallback` | lokalna implementacja tylko bez PWE System |
| `delegated` | implementacja przeniesiona do PWE System |
| `dormant` | kod obecny, ale bootstrap ma jego aktywację zakomentowaną |
| `direct-entrypoint` | plik może być uruchamiany bezpośrednio przez HTTP/CLI |
| `third-party` | biblioteka zewnętrzna |

Maszynową listę kluczowych węzłów zawiera `inventory/load-graph.json`.

## Potwierdzone moduły dormant w bootstrapie 3.6.8

- `includes/mailing/mailing.php`,
- `gf-addons/gf-mailcheck-validator/gf-mailcheck-validator.php`,
- `includes/top10/pwelogofetcher.php`,
- `qr-active/main-qr-active.php`.

Ich symbole nadal są indeksowane jako kod repozytorium, ale dokumentacja procesów nie przedstawia ich jako aktywnych bez dodatkowego wejścia.
