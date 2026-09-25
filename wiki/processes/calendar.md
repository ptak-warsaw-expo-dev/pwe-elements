---
plugin: PWE Elements
version: 3.6.8
source: uploaded archive
source_commit: null
language: pl
---
# Proces: kalendarz wydarzeń

Moduł `includes/calendar/calendar.php` rejestruje CPT `event`, taksonomie, meta-boxy, filtry admina i zapis metadanych. Ładuje klasę loop kalendarza.

`LoopCalendar` rejestruje `wp_ajax_load_more_calendar` oraz `wp_ajax_nopriv_load_more_calendar`. Handler `load_more_calendar()`:

1. odczytuje paginację z POST,
2. pobiera opublikowane wpisy `event`,
3. dla eventów wyprowadza domenę z linku i pobiera daty targów,
4. sortuje eventy,
5. wycina aktualną stronę wyników,
6. renderuje karty HTML,
7. steruje widocznością przycisku „load more”,
8. kończy request przez `wp_die()`.

Bootstrap ładuje moduł kalendarza warunkowo poza mobile.
