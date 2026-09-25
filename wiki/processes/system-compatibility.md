---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: PWE System vs lokalny fallback

1. `PWElementsPlugin::initClasses()` zaczyna od `initSystemCompatibility()`.
2. `hasPweSystem()` weryfikuje pliki i aktywację PWE System.
3. Przy aktywnym PWE System ładowana jest jego klasa funkcji i backend shortcodes.
4. `PWECommonFunctions` jest aliasowane do implementacji systemowej, jeśli starsza nazwa nie istnieje.
5. Bez PWE System ładowane są lokalne `pwefunctions.php` i `backend/shortcodes.php`.
6. Dopiero potem bootstrap tworzy klasy pozostałych modułów PWE Elements.

To jest centralny cross-plugin bridge dla przyszłego globalnego Call Graphu.
