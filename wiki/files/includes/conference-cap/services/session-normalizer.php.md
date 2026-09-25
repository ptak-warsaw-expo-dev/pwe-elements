# `includes/conference-cap/services/session-normalizer.php`

Plik first-party PWE Elements w kategorii `module`.

## Metadane

- **Kategoria:** `module`
- **Rozmiar:** 1881 B
- **Liczba linii:** 51
- **Źródło:** `includes/conference-cap/services/session-normalizer.php`

## Klasy i metody

### `PWE_Conference_Cap_Session_Normalizer` — linia 6

  - `public __construct(?PWE_Conference_Cap_Speaker_Normalizer $speaker_normalizer = null)` — linia 10
  - `public normalize_sessions(array $sessions, string $conference_slug = '', string $day_key = '')` — linia 17
  - `private stable_id(string $conference_slug, string $day_key, string $session_key, int $counter)` — linia 44

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- `sanitize_title()`

## Dołączane pliki / wyrażenia include

- Brak.

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
