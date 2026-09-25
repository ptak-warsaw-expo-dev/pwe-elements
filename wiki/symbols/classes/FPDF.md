# Klasa `FPDF`

**Źródło:** `other/give_badge/fpdf.php:10`  
**Metody:** 87

## Rola

Klasa jest zdefiniowana w `other/give_badge/fpdf.php`. Jej aktywność runtime zależy od sposobu ładowania pliku; dla najważniejszych modułów status jest opisany w `inventory/load-graph.json`.

## Metody

- [`__construct($orientation='P', $unit='mm', $size='A4')`](../methods/FPDF/__construct.md) — linia 75
- [`SetMargins($left, $top, $right=null)`](../methods/FPDF/setmargins.md) — linia 168
- [`SetLeftMargin($margin)`](../methods/FPDF/setleftmargin.md) — linia 178
- [`SetTopMargin($margin)`](../methods/FPDF/settopmargin.md) — linia 186
- [`SetRightMargin($margin)`](../methods/FPDF/setrightmargin.md) — linia 192
- [`SetAutoPageBreak($auto, $margin=0)`](../methods/FPDF/setautopagebreak.md) — linia 198
- [`SetDisplayMode($zoom, $layout='default')`](../methods/FPDF/setdisplaymode.md) — linia 206
- [`SetCompression($compress)`](../methods/FPDF/setcompression.md) — linia 219
- [`SetTitle($title, $isUTF8=false)`](../methods/FPDF/settitle.md) — linia 228
- [`SetAuthor($author, $isUTF8=false)`](../methods/FPDF/setauthor.md) — linia 234
- [`SetSubject($subject, $isUTF8=false)`](../methods/FPDF/setsubject.md) — linia 240
- [`SetKeywords($keywords, $isUTF8=false)`](../methods/FPDF/setkeywords.md) — linia 246
- [`SetCreator($creator, $isUTF8=false)`](../methods/FPDF/setcreator.md) — linia 252
- [`AliasNbPages($alias='{nb}')`](../methods/FPDF/aliasnbpages.md) — linia 258
- [`Error($msg)`](../methods/FPDF/error.md) — linia 264
- [`Close()`](../methods/FPDF/close.md) — linia 270
- [`AddPage($orientation='', $size='', $rotation=0)`](../methods/FPDF/addpage.md) — linia 287
- [`Header()`](../methods/FPDF/header.md) — linia 356
- [`Footer()`](../methods/FPDF/footer.md) — linia 361
- [`PageNo()`](../methods/FPDF/pageno.md) — linia 366
- [`SetDrawColor($r, $g=null, $b=null)`](../methods/FPDF/setdrawcolor.md) — linia 372
- [`SetFillColor($r, $g=null, $b=null)`](../methods/FPDF/setfillcolor.md) — linia 383
- [`SetTextColor($r, $g=null, $b=null)`](../methods/FPDF/settextcolor.md) — linia 395
- [`GetStringWidth($s)`](../methods/FPDF/getstringwidth.md) — linia 405
- [`SetLineWidth($width)`](../methods/FPDF/setlinewidth.md) — linia 417
- [`Line($x1, $y1, $x2, $y2)`](../methods/FPDF/line.md) — linia 425
- [`Rect($x, $y, $w, $h, $style='')`](../methods/FPDF/rect.md) — linia 431
- [`AddFont($family, $style='', $file='', $dir='')`](../methods/FPDF/addfont.md) — linia 443
- [`SetFont($family, $style='', $size=0)`](../methods/FPDF/setfont.md) — linia 475
- [`SetFontSize($size)`](../methods/FPDF/setfontsize.md) — linia 525
- [`AddLink()`](../methods/FPDF/addlink.md) — linia 536
- [`SetLink($link, $y=0, $page=-1)`](../methods/FPDF/setlink.md) — linia 544
- [`Link($x, $y, $w, $h, $link)`](../methods/FPDF/link.md) — linia 554
- [`Text($x, $y, $txt)`](../methods/FPDF/text.md) — linia 560
- [`AcceptPageBreak()`](../methods/FPDF/acceptpagebreak.md) — linia 574
- [`Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')`](../methods/FPDF/cell.md) — linia 580
- [`MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)`](../methods/FPDF/multicell.md) — linia 661
- [`Write($h, $txt, $link='')`](../methods/FPDF/write.md) — linia 776
- [`Ln($h=null)`](../methods/FPDF/ln.md) — linia 859
- [`Image($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='')`](../methods/FPDF/image.md) — linia 869
- [`GetPageWidth()`](../methods/FPDF/getpagewidth.md) — linia 934
- [`GetPageHeight()`](../methods/FPDF/getpageheight.md) — linia 940
- [`GetX()`](../methods/FPDF/getx.md) — linia 946
- [`SetX($x)`](../methods/FPDF/setx.md) — linia 952
- [`GetY()`](../methods/FPDF/gety.md) — linia 961
- [`SetY($y, $resetX=true)`](../methods/FPDF/sety.md) — linia 967
- [`SetXY($x, $y)`](../methods/FPDF/setxy.md) — linia 978
- [`Output($dest='', $name='', $isUTF8=false)`](../methods/FPDF/output.md) — linia 985
- [`protected _checkoutput()`](../methods/FPDF/_checkoutput.md) — linia 1042
- [`protected _getpagesize($size)`](../methods/FPDF/_getpagesize.md) — linia 1062
- [`protected _beginpage($orientation, $size, $rotation)`](../methods/FPDF/_beginpage.md) — linia 1081
- [`protected _endpage()`](../methods/FPDF/_endpage.md) — linia 1129
- [`protected _loadfont($path)`](../methods/FPDF/_loadfont.md) — linia 1134
- [`protected _isascii($s)`](../methods/FPDF/_isascii.md) — linia 1147
- [`protected _httpencode($param, $value, $isUTF8)`](../methods/FPDF/_httpencode.md) — linia 1159
- [`protected _UTF8encode($s)`](../methods/FPDF/_utf8encode.md) — linia 1169
- [`protected _UTF8toUTF16($s)`](../methods/FPDF/_utf8toutf16.md) — linia 1191
- [`protected _escape($s)`](../methods/FPDF/_escape.md) — linia 1226
- [`protected _textstring($s)`](../methods/FPDF/_textstring.md) — linia 1235
- [`protected _dounderline($x, $y, $txt)`](../methods/FPDF/_dounderline.md) — linia 1243
- [`protected _parsejpg($file)`](../methods/FPDF/_parsejpg.md) — linia 1252
- [`protected _parsepng($file)`](../methods/FPDF/_parsepng.md) — linia 1271
- [`protected _parsepngstream($f, $file)`](../methods/FPDF/_parsepngstream.md) — linia 1282
- [`protected _readstream($f, $n)`](../methods/FPDF/_readstream.md) — linia 1408
- [`protected _readint($f)`](../methods/FPDF/_readint.md) — linia 1425
- [`protected _parsegif($file)`](../methods/FPDF/_parsegif.md) — linia 1432
- [`protected _out($s)`](../methods/FPDF/_out.md) — linia 1457
- [`protected _put($s)`](../methods/FPDF/_put.md) — linia 1470
- [`protected _getoffset()`](../methods/FPDF/_getoffset.md) — linia 1476
- [`protected _newobj($n=null)`](../methods/FPDF/_newobj.md) — linia 1481
- [`protected _putstream($data)`](../methods/FPDF/_putstream.md) — linia 1490
- [`protected _putstreamobject($data)`](../methods/FPDF/_putstreamobject.md) — linia 1497
- [`protected _putlinks($n)`](../methods/FPDF/_putlinks.md) — linia 1513
- [`protected _putpage($n)`](../methods/FPDF/_putpage.md) — linia 1536
- [`protected _putpages()`](../methods/FPDF/_putpages.md) — linia 1566
- [`protected _putfonts()`](../methods/FPDF/_putfonts.md) — linia 1604
- [`protected _tounicodecmap($uv)`](../methods/FPDF/_tounicodecmap.md) — linia 1721
- [`protected _putimages()`](../methods/FPDF/_putimages.md) — linia 1772
- [`protected _putimage(&$info)`](../methods/FPDF/_putimage.md) — linia 1782
- [`protected _putxobjectdict()`](../methods/FPDF/_putxobjectdict.md) — linia 1827
- [`protected _putresourcedict()`](../methods/FPDF/_putresourcedict.md) — linia 1833
- [`protected _putresources()`](../methods/FPDF/_putresources.md) — linia 1845
- [`protected _putinfo()`](../methods/FPDF/_putinfo.md) — linia 1857
- [`protected _putcatalog()`](../methods/FPDF/_putcatalog.md) — linia 1865
- [`protected _putheader()`](../methods/FPDF/_putheader.md) — linia 1886
- [`protected _puttrailer()`](../methods/FPDF/_puttrailer.md) — linia 1891
- [`protected _enddoc()`](../methods/FPDF/_enddoc.md) — linia 1898

## Dokument pliku

- [Otwórz dokumentację `other/give_badge/fpdf.php`](../../files/other/give_badge/fpdf.php.md)
