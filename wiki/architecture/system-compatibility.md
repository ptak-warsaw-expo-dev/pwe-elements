---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---

# PWE System i lokalny fallback

## Wykrycie PWE System

`PWElementsPlugin::hasPweSystem()` sprawdza obecność plików PWE System oraz jego aktywację: stałą/klasę runtime, listę aktywnych pluginów lub aktywację sieciową w multisite.

## Gdy PWE System jest aktywny

`initSystemCompatibility()`:

1. ładuje `pwe-system/core/class-pwe-system-functions.php`,
2. jeżeli starsza nazwa `PWECommonFunctions` nie istnieje, tworzy alias do `PWE_System_Functions` (lub kompatybilnej `PWE_Functions`),
3. ładuje `pwe-system/modules/shortcodes/backend-shortcodes.php`, jeśli proceduralne API shortcode'ów nie zostało wcześniej zdefiniowane.

## Gdy PWE System nie jest aktywny

Ładowane są lokalnie:

- `pwefunctions.php`,
- `backend/shortcodes.php`.

Wtyczka pozostaje więc samodzielna.

## Konsekwencja dla Code Graph

Wywołanie `PWECommonFunctions::...` nie powinno być zawsze rozwiązywane wyłącznie do symbolu lokalnego. W środowisku z PWE System rzeczywisty target może znajdować się w innym pluginie. Wiki v2 przechowuje tę informację jako zależność i status `delegated/fallback`.
