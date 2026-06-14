# MMK-GT-honlap

- A rendszer jelenleg egy működőképes PHP/MySQL alapú honlap, ahol a publikus oldalak adatbázisból töltődnek, az admin felület pedig kezeli a híreket, dokumentumokat, szakmai anyagokat, naptári eseményeket és díjazottakat. A legfontosabb megmaradt feladat a kód tisztítása, a konfiguráció és a DB séma formalizálása, illetve a biztonsági és karbantartási rétegek erősítése.

- Ha másra nem is, akkor arra legalább jó lesz, hogy lássátok, hogy felépítés ügyileg mégis mire gondoltunk. A bal oldali navigációs rész elvileg végleges, az biztos jó kiindulópont.
- Ha bármi kérdés van: fekete.kornelia@arkadii.hu  vagy  david@arkadii.hu

## Használt technológiák

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- PDO adatbázis-kezelés
- TinyMCE a hír tartalmak szerkesztéséhez (admin felület)
- XAMPP fejlesztési környezet

## Eddig megvalósított főbb fejlesztések

- Admin bejelentkezés és session alapú jogosultságkezelés. (de egyáltalán nem véglegesített, nem biztonságos, stb...)
- Egységes admin felület több táblához tartozó CRUD műveletekkel.
- Hírek kezelése TinyMCE szerkesztővel. (Minden cikk kikerül a hírekhez is, illetve a kijelölt részlegre.)
- Publikus hírlista (index.php),
- Archiválási logika a hírekhez. (1 év után csakis a hírek közül (index.php) kivesszük a frontenden)
- Képes naptár/évfolyamos tartalmak kezelése. (csak a jövőbeli eseményeket jeleníti majd meg)
- Dokumentumlista és dokumentumkategóriák.
- Díjazottak admin felvétele és szerkesztése.
- Botka Imre-díj és Magyar József-díj oldalak adatbázisból töltenek.
- Admin lapozás táblánként, egyszerre 5 rekord megjelenítésével.
- Post-Redirect-Get logika az admin űrlapoknál, hogy frissítéskor ne küldje újra a POST-ot.

## Főbb oldalak és funkciók

- `src/php/index.php` - főoldal, hírek listázása.
- `src/includes/news-list.php` - közös hírlista komponens.
- `src/includes/document-list.php` - dokumentumlista komponens.
- `src/php/dokumentumok.php` - dokumentumok oldala.
- `src/php/botka-dij.php` - Botka Imre-díj oldal.
- `src/php/magyar-jozsef-dij.php` - Magyar József-díj oldal.
- `src/php/dijaink.php` - díjak összefoglaló oldala.
- `src/php/admin-login.php` - admin bejelentkezés.
- `src/php/admin-felulet.php` - admin CRUD felület.
- `src/php/admin-upload-image.php` - TinyMCE képfeltöltés.

## Adatbázis

A projekt MariaDB - MySQL adatbázist használ. A kódban látható kapcsolat:

- host: `localhost`
- adatbázis: `gepeszeti_tagozat`
- felhasználó: `root`
- jelszó: üres

### Táblák és sémák

#### `szerkeszto`

| Mező | Típus | Megjegyzés |
| --- | --- | --- |
| `id` | int(11) | elsődleges kulcs, auto increment |
| `felhasznalonev` | varchar(50) | egyedi admin login név |
| `jelszo_hash` | text | hash-elt jelszó |
| `admin` | tinyint(1) | admin jogosultság jelölése |

#### `dokumentumok`

| Mező | Típus | Megjegyzés |
| --- | --- | --- |
| `id` | int(11) | elsődleges kulcs, auto increment |
| `cim` | varchar(50) | dokumentum címe |
| `dokumentum_tipus` | varchar(20) | kategória |
| `dokumentum_utvonal` | varchar(100) | fájl elérési útja |
| `archivalt` | tinyint(1) | archiválás jelzése |

#### `hirek`

| Mező | Típus | Megjegyzés |
| --- | --- | --- |
| `id` | int(11) | elsődleges kulcs, auto increment |
| `cim` | varchar(50) | hír címe |
| `tartalom` | text | TinyMCE által mentett HTML |
| `hir_tipus` | varchar(60) | hírkategória |
| `feltoltes_datuma` | date | publikálási dátum |
| `archivalt` | tinyint(1) | archiválás jelzése |

#### `hirek_kepek`

| Mező | Típus | Megjegyzés |
| --- | --- | --- |
| `id` | int(11) | elsődleges kulcs, auto increment |
| `hirek_id` | int(11) | idegen kulcs a `hirek.id` mezőre |
| `kep_utvonal` | varchar(100) | kép elérési útja |
| `kep_leiras` | text | kép leírása |

#### `naptar`

| Mező | Típus | Megjegyzés |
| --- | --- | --- |
| `id` | int(11) | elsődleges kulcs, auto increment |
| `esemeny_tipus` | varchar(20) | esemény típusa |
| `cim` | varchar(50) | esemény címe |
| `leiras` | text | részletes leírás |
| `kep_utvonal` | varchar(100) | kép elérési útja |
| `datum` | date | esemény dátuma |

#### `szakmai_anyagok`

| Mező | Típus | Megjegyzés |
| --- | --- | --- |
| `id` | int(11) | elsődleges kulcs, auto increment |
| `szakmai_tipus` | varchar(20) | kiadvány / cikk / előadás |
| `leiras` | text | leírás |
| `link` | text | hivatkozás |
| `cim` | varchar(50) | anyag címe |
| `archivalt` | tinyint(1) | archiválás jelzése |

#### `dijazottak`

| Mező | Típus | Megjegyzés |
| --- | --- | --- |
| `id` | int(11) | elsődleges kulcs, auto increment |
| `ev` | year(4) | díj éve |
| `cim` | text | tevékenység / indoklás |
| `nev` | varchar(50) | díjazott neve |
| `kep_utvonal` | varchar(100) | kép elérési útja |
| `dij_tipus` | varchar(60) | Botka Imre-díj vagy Magyar József-díj |

## Hiányosságok és további fejlesztési feladatok

- Nincs külön SQL dump vagy migrációs fájl a repóban.
- Az adatbázis-kapcsolat jelenleg kódban van megadva, nincs külön konfigurációs réteg.
- A jogosultságkezelés egyszerű session alapú megoldás, CSRF védelem nincs minden űrlapon.
- A fájlfeltöltések működnek, de érdemes egységesebb validálást és törlési logikát hozzáadni.
- Nincs automatizált tesztkészlet.
- Az admin felülethez hasznos lenne külön keresés/szűrés és rendezési vezérlő.
- A projekt átadásához még ajánlott lenne egy telepítési és mentési/restore útmutató.
