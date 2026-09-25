---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: dynamiczne shortcody danych targowych

Lokalny `backend/shortcodes.php` jest fallbackiem używanym, gdy PWE System nie jest aktywny.

1. `pwe_get_shortcode_map()` zwraca bazową mapę `[pwe_*] → pole danych`.
2. Lista jest rozszerzana o języki WPML inne niż PL/EN; bez listy WPML kod używa zestawu fallbackowych kodów językowych.
3. `register_dynamic_shortcodes()` na hooku `init` rejestruje dla każdego tagu closure.
4. Closure deleguje do `handle_fair_shortcode($atts, $field)`.
5. Handler pobiera dane bieżącej lub wskazanej domeny przez `get_fair_data()`.
6. `get_fair_data()` preferuje dane z `PWECommonFunctions`, a w lokalnym fallbacku posiada dodatkowy fallback JSON.

Przy aktywnym PWE System analogiczny poziom shortcode'ów jest ładowany z `pwe-system/modules/shortcodes/backend-shortcodes.php`.
