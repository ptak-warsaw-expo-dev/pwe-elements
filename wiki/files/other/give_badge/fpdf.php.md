# `other/give_badge/fpdf.php`

Plik first-party PWE Elements w kategorii `legacy-tool`.

## Metadane

- **Kategoria:** `legacy-tool`
- **Rozmiar:** 48838 B
- **Liczba linii:** 1935
- **Źródło:** `other/give_badge/fpdf.php`

## Klasy i metody

### `FPDF` — linia 10

  - `__construct($orientation='P', $unit='mm', $size='A4')` — linia 75
  - `SetMargins($left, $top, $right=null)` — linia 168
  - `SetLeftMargin($margin)` — linia 178
  - `SetTopMargin($margin)` — linia 186
  - `SetRightMargin($margin)` — linia 192
  - `SetAutoPageBreak($auto, $margin=0)` — linia 198
  - `SetDisplayMode($zoom, $layout='default')` — linia 206
  - `SetCompression($compress)` — linia 219
  - `SetTitle($title, $isUTF8=false)` — linia 228
  - `SetAuthor($author, $isUTF8=false)` — linia 234
  - `SetSubject($subject, $isUTF8=false)` — linia 240
  - `SetKeywords($keywords, $isUTF8=false)` — linia 246
  - `SetCreator($creator, $isUTF8=false)` — linia 252
  - `AliasNbPages($alias='{nb}')` — linia 258
  - `Error($msg)` — linia 264
  - `Close()` — linia 270
  - `AddPage($orientation='', $size='', $rotation=0)` — linia 287
  - `Header()` — linia 356
  - `Footer()` — linia 361
  - `PageNo()` — linia 366
  - `SetDrawColor($r, $g=null, $b=null)` — linia 372
  - `SetFillColor($r, $g=null, $b=null)` — linia 383
  - `SetTextColor($r, $g=null, $b=null)` — linia 395
  - `GetStringWidth($s)` — linia 405
  - `SetLineWidth($width)` — linia 417
  - `Line($x1, $y1, $x2, $y2)` — linia 425
  - `Rect($x, $y, $w, $h, $style='')` — linia 431
  - `AddFont($family, $style='', $file='', $dir='')` — linia 443
  - `SetFont($family, $style='', $size=0)` — linia 475
  - `SetFontSize($size)` — linia 525
  - `AddLink()` — linia 536
  - `SetLink($link, $y=0, $page=-1)` — linia 544
  - `Link($x, $y, $w, $h, $link)` — linia 554
  - `Text($x, $y, $txt)` — linia 560
  - `AcceptPageBreak()` — linia 574
  - `Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')` — linia 580
  - `MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)` — linia 661
  - `Write($h, $txt, $link='')` — linia 776
  - `Ln($h=null)` — linia 859
  - `Image($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='')` — linia 869
  - `GetPageWidth()` — linia 934
  - `GetPageHeight()` — linia 940
  - `GetX()` — linia 946
  - `SetX($x)` — linia 952
  - `GetY()` — linia 961
  - `SetY($y, $resetX=true)` — linia 967
  - `SetXY($x, $y)` — linia 978
  - `Output($dest='', $name='', $isUTF8=false)` — linia 985
  - `protected _checkoutput()` — linia 1042
  - `protected _getpagesize($size)` — linia 1062
  - `protected _beginpage($orientation, $size, $rotation)` — linia 1081
  - `protected _endpage()` — linia 1129
  - `protected _loadfont($path)` — linia 1134
  - `protected _isascii($s)` — linia 1147
  - `protected _httpencode($param, $value, $isUTF8)` — linia 1159
  - `protected _UTF8encode($s)` — linia 1169
  - `protected _UTF8toUTF16($s)` — linia 1191
  - `protected _escape($s)` — linia 1226
  - `protected _textstring($s)` — linia 1235
  - `protected _dounderline($x, $y, $txt)` — linia 1243
  - `protected _parsejpg($file)` — linia 1252
  - `protected _parsepng($file)` — linia 1271
  - `protected _parsepngstream($f, $file)` — linia 1282
  - `protected _readstream($f, $n)` — linia 1408
  - `protected _readint($f)` — linia 1425
  - `protected _parsegif($file)` — linia 1432
  - `protected _out($s)` — linia 1457
  - `protected _put($s)` — linia 1470
  - `protected _getoffset()` — linia 1476
  - `protected _newobj($n=null)` — linia 1481
  - `protected _putstream($data)` — linia 1490
  - `protected _putstreamobject($data)` — linia 1497
  - `protected _putlinks($n)` — linia 1513
  - `protected _putpage($n)` — linia 1536
  - `protected _putpages()` — linia 1566
  - `protected _putfonts()` — linia 1604
  - `protected _tounicodecmap($uv)` — linia 1721
  - `protected _putimages()` — linia 1772
  - `protected _putimage(&$info)` — linia 1782
  - `protected _putxobjectdict()` — linia 1827
  - `protected _putresourcedict()` — linia 1833
  - `protected _putresources()` — linia 1845
  - `protected _putinfo()` — linia 1857
  - `protected _putcatalog()` — linia 1865
  - `protected _putheader()` — linia 1886
  - `protected _puttrailer()` — linia 1891
  - `protected _enddoc()` — linia 1898

## Funkcje globalne

- Brak funkcji globalnych.

## Rejestracje WordPress / GF

- Brak wykrytych literalnych rejestracji.

## Wybrane wywołania statyczne

- Brak.

## API WordPress rozpoznane heurystycznie

- Brak.

## Dołączane pliki / wyrażenia include

- `$path)`
- `font definition file: '.$path)`
- `d for GIF support')`

## Tabele / właściwości `$wpdb`

- Brak statycznie rozpoznanych.

## Uwagi

- Symbole i hooki są wyciągane tokenizatorem PHP.
- Sekcje zależności i include są statyczną heurystyką i mogą nie widzieć dynamicznych ścieżek.
- Dokładny Call Graph powstaje niezależnie w Code Indexerze.
