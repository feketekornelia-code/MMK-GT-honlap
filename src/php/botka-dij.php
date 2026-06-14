<?php
$newsPageTitle = 'MMK-GT - Botka Imre-díj';
$newsPageDescription = 'A Botka Imre-díjhoz kapcsolódó hírek, átadók és felvételek itt jelennek meg.';
$newsSectionTitle = 'Botka Imre-díj átadók';
$newsSectionId = 'botka-aktualis-hirek';
$newsTypeFilter = 'Botka Imre-díj átadó';
$newsEmptyMessage = 'Jelenleg nincs megjeleníthető Botka Imre-díj átadó hír.';
$dijazottakRows = [];

try {
  $pdo = new PDO(
    'mysql:host=localhost;dbname=gepeszeti_tagozat;charset=utf8mb4',
    'root',
    '',
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );

  $statement = $pdo->prepare('SELECT ev, cim, nev, kep_utvonal FROM dijazottak WHERE dij_tipus = :dij_tipus ORDER BY ev DESC, id DESC');
  $statement->execute(['dij_tipus' => 'Botka Imre-díj']);
  $dijazottakRows = $statement->fetchAll();
} catch (Throwable $exception) {
  $dijazottakRows = [];
}
?>
<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Botka Imre-díj</title>
  <meta name="description" content="A Botka Imre-díj bemutató oldala a díj leírásával, díjazottakkal és díjátadókkal.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="botka-title">
        <div class="news-panel botka-page">
          <header class="news-header botka-hero">
            <div class="botka-hero-copy">
              <div class="news-meta">
              </div>
                <h1 id="botka-title">Botka Imre-díj</h1>
                <p>A díszdobozban rozsdamentes acél fogaskerék, polírozott előlapján a névadó arcképe, fel-írat: Botka Imre Díj és az adományozás évszáma. Hátlapja: mattított vagy érdesített felü-let, rajta felírat: „A szolgaságban szabadok valának”.
                adományozási okirat, keretezve (az adományozott neve, az odaítélés indokolása, a díjazott alkotás és ha vannak, munkatársak ismertetése, esetleges egyéb körülmények, dátum, MMK logó, Tagozat elnökének aláírása – stílusában rövid és tárgyszerű.)</p>
            </div>

            <figure class="botka-hero-figure">
              <img class="botka-hero-image" src="../../img/site/Botka-dij/Botka_dij_erme.JPG" alt="Botka Imre-díj érme" loading="eager">
            </figure>
          </header>

          <section class="botka-content" aria-labelledby="botka-content-title">
            <p>A díj a Gépészeti Tagozat szakterületén elért alábbi eredményekért adományozható:
                megvalósított alkotás (szerkezet, folyamat, eljárás), amely a hazai körülmények közt ki-emelkedő színvonalú illetve a világpiacon versenyképes megoldást tartalmaz és ezek el-méleti háttere elégségesen kidolgozott. Az alkotást alapozza meg olyan
                tudományos színvonalú (publikált vagy más alkalmas módon dokumentált) analitikus vagy kísérleti vizsgálattal, amely gyakorlati felhasználásra irányuló lényeges ismeretet alapoz meg illetve elméleti-kísérleti alapon leírt jelenségeket képes előre jelezni vagy fel-tár korábban nem ismert jelenséget.</p>

          <section class="botka-intro" aria-labelledby="botka-intro-title">
            <h2 id="botka-intro-title">Botka Imre életrajza</h2>
            <p class="botka-intro-quote">
                "Ő volt az élő lelkiismeretünk"

                Botka Imre 1906. október elsején született Bácsföldváron, nyolcgyermekes gazdálkodó családban – legidősebb fiúként. A családi körülmények, háború és az impériumváltás megszabta keretekbe a polgári iskola és a lakatos szakma fért bele, a mérnökség nem.

                Mindent meggondolva és megfontolva, minden vagyonrészről, örökségről lemondva tizenhét évesen átjött a határon, az előképzettségi hiányokat pótolva leérettségizett, majd 1932-ben kitűnő minősítésű gépészmérnöki oklevelet szerzett a Műegyetemen. 1933-ban lett újra magyar állampolgár. A válság és diplomás munkanélküliség sújtotta években volt egyetemi tanársegéd meg útépítő mérnök, mígnem 1934-ben „megfelelő emberként a megfelelő helyre”, a Ganz gyárba került. Mint csendes mosollyal említette, csak azért nem kapta meg a közmondásos havi kétszáz fixet, mert nem volt képe ennyit elkérni azokban az időkben. Itt pályája töretlenül ívelt kezdő mérnöktől a főkonstruktőrig és egyre nagyobb feladatokban mutathatta meg képességeit. 1937-ben megtervezte a Belgrád-Dubrovnik motorvonat hajtóművét és két éven át a helyszínen irányította az üzembe helyezést, a személyzet betanítását. Munkájáért magas jugoszláv kitüntetést kapott. Hasonló munkát végzett 1939- ben, Spanyolországban, mellékesen megtanult szerbül és spanyolul. A továbbiakban egyéb kisebb feladatok mellett egész sorozat vasúti trakciós hajtóművet tervezett, köztük 1947-ben a jól ismert „Stuka” villamosét. Szabadalom lett a Ganz-Botka fogazatrendszer. Újabb konstrukciói előbb a szomszédos országokba szállított Ganz mozdonyokba és motorvonatokba kerültek, 1956-ban Argentínába is eljutottak. 1956 őszén készült el a Hidro- Ganz hidromechanikus váltó prototípusa. Más gépipari cégeknél is végzett szakértői munkát (Ganz Hajó- és Darugyár, Rába, Vörös Csillag Traktorgyár). Mindezzel együtt nem tartozott a hivatalosan elismerhetők sorába: bácskai rokonsága révén kulák származéknak, valamint klerikális beállítottságúként tartották számon.

                1958-ban saját kérésére rokkantsági nyugdíjba helyezték. Hogy közben hol volt, tekintve, hogy a beszéd tárgya Botka Imre szakmai pályája, arra legjobb Kosztolányi egy novellahősével válaszolni: „Ott, ahol akkor mindenki. De nála csak vizsgálati volt.” Az viszont már emberi arculatához tartozik, hogy amikor családja adhatott be neki könyvet, ő a Don Quijotét kérte, mégpedig spanyolul, gyakorlás céljára.
                Megviselt egészsége nehezen állt helyre és sosem igazán. Ennek ellenére tanácsadó szakértői munkát végzett előbb a Mávag, majd a fúzió után a Ganz-Mávag számára. Amennyire erejéből tellett, rendszerezte, kiegészítette és összefoglalta eredményeit, részt vett a tudományos közéletben akadémiai bizottsági tagként, lektorálással, védéseken (a szerző hálával emlékszik vissza, hogyan indította el Botka Imre a tudományos kutatásban, és hogy közreműködhetett a Ganz-Botka rendszer számítástechnikai alapjainak kidolgozásában). A lélek mindig kész volt, de a test egyre erőtlenebb lett, sok szenvedés után 1974. július elsején hunyt el Budapesten.

                Legfontosabb tudományos eredményeinek tárgyával már Ganz-beli munkájának kezdeti időszakában találkozott, a motorvonat-hajtóművekre érkezett reklamációk formájában. Ezek okát a későbbiekben teljes sikerrel azonosította és megszüntette. Nagyobb nyilvánosság előtt színre lépése a háború és különféle társadalmi viharok után újra meginduló szakmai-tudományos élet debütálásánál, az 1951. december 14-i MTA Fogaskerék Konferencián történt. Itt a fogazási interferenciára vonatkozó eredményeit ismertette, Varga József Ganz gyári igazgató pedig a Ganz-Botka fogazásról tartott beszámolót.
                Az interferencia (lefejtő módszerrel gyártott kerekek akadása) jelenségének átfogó vizsgálata, különösen a nagy profileltolásoknál előálló un. felső interferencia felismerése lehetővé tette a fogazástartomány határainak szabatos kijelölését. A munka jelentőségét, külföldi visszhangja mellett, jól jellemzi, hogy „kalózkópia” is készült róla: cikkének német fordítása tudta és beleegyezése nélkül jelent meg.
                
                A Ganz-Botka fogazatrendszer két irányban is lényeges előrelépést jelentett. Az evolvens profilú fogaskerekek általános geometriai összefüggéseit már korábban felismerték és gyári titkok körén kívül újra - felfedezték, de ezek a szilárdsági feltételekkel együtt rendkívül nehezen kezelhető nem-lineáris rendszert alkottak. Ennek alapján, főként a manuális számítástechnika eszközeivel, igen körülményesen lehetett adott követelményekhez fogazatot szerkeszteni. Szerző egyetemi hallgató korában olyan segédletekre volt utalva, amelyben az un. anyagtakarékos méretezés képletéből kiadódott egy fogszám-összeg, aztán felvettek valami egészen mást. Évfolyamtársa volt viszont Botka Imre fia, aki révén megismerkedett édesapjával és egy délutáni konzultáció során helyükre kerültek a fogazatméretezés rejtelmei az egyetemi rajzfeladattal együtt. A szerkesztés és méretezés folyamatát Botka Imre nagymértékben algoritmizálta, ezek kiérlelt és továbbfejlesztett formában utolsó művében láttak napvilágot.
                Ennél is fontosabb volt a nagy sebességű, erősen terhelt hajtások helyesbítésének célszerű meghatározása. Korábban a relatív csúszásokat tekintették mérvadónak a károsodásra, mégpedig tisztán geometriai meghatározással. Az erre alapozott méretezési filozófia a fizikai tartalom mérlegelésének híjával a fogazástudományban mintegy szent tehénné vált. A jelző jogosságát igazolja, hogy fel sem merült a kérdés, mi történik terheletlen hajtásban, ahol relatív csúszás ugyan van, de romlás nem lesz. Ha a jellemzőbe (szorzóként) befoglaljuk az átvitt vonalterhelést, a veszélyes kapcsolódási pontok nem szükségképp a legnagyobb csúszások helyén lesznek. A mértékre javaslatba került a Hertz-feszültség és a csúszási sebesség (Almen-féle) szorzata és a Bloktól származó pillanatnyi érintkezési hőmérséklet.

                Botka az utóbbiak legkisebb értékre korlátozására alapozta fogazatrendszerét. Bebizonyította továbbá, hogy azonos vonalnyomással terhelt kapcsolódási helyeken a relatív csúszások kiegyenlítése egyben biztosítja a pillanatnyi hőmérsékletek és az Almen-szorzat azonos értékét is (hármas kiegyenlítési tétel). A legnagyobb csúszások kiegyenlítése azonban nem biztosítja ugyanakkor a legnagyobb hőmérséklet-emelkedés kiegyenlítését, ezzel minimumát.
                A kiegyenlítési esetek határaira vonatkozó eredmények (szerző szerény közreműködése nyomán) részleteiben szintén a már fentebb említett szintézis-műben jelentek meg.
                
                Szellemi hagyatékát, jelentőségét a „vele egy rendűek”, vagyis az ítélkezésre jogosultak azzal becsülték meg, hogy a Magyar Mérnöki Kamara Gépészeti Tagozata róla nevezte el 2001-ben alapított elismerő díját és születésének 100 éves évfordulójára hajdani lakóházán emléktáblát helyeznek el.
                
                A hagyaték további mérnök-nemzedékeknek továbbadásában elsősorban a Miskolci Egyetem, jelesül Terplán Zénó professzor tett sokat. Budapesten, bár Vörös professzor könyvében az eredmények korrektül fel vannak idézve, a szerző előadáson egyszer hallotta a Botka nevet – amikor kiderült, hogy fia nem vezetett jegyzetet (az előadást nem Vörös professzor tartotta).
                Bizonyára ennek következménye, hogy a tárgykörben megjelent művekben, sőt még kandidátusi értekezésben is lehet találni enyhén szólva pontatlan megállapításokat szerzőkre és eredményekre egyaránt.

                Még talányosabb a kép a „magas tudomány” oldaláról. Bár működésének rangos egyetemi ismerői – tudta nélkül – 1966-ban beadványt írtak az Akadémia Tudományos Minősítő Bizottságának, hogy a szokásos vizsgakötelezettségek mellőzésével adjanak néki alkotásai alapján tudományos fokozatot, ezzel a TMB szabályzati lehetőség hiányára hivatkozva nem is foglalkozott. A továbbiakban tudományos körökben bizonyos tartózkodás volt érzékelhető. Botka Imre 1973-ban benyújtott tézisdisszertációja elbírálását már nem érte meg, csak postumus érdemesítették kandidátusi fokozatra. Ami annál is sajnálatosabb, mert a tudománytörténet tanúsága szerint, aki szakmai életében valamelyest is közel került a fogaskerekekhez, az így vagy úgy, de a tudományok doktoraként hal meg.

                Botka Imre Niemanntól, a világhírű müncheni professzortól – és életében – kapta meg a „régóta ismert magyar fogaskerék-király” címet.
                
                Dr. Kolonits Ferenc</p>
          </section>

          <section class="botka-section" id="botka-dijazottak" aria-labelledby="botka-dijazottak-title">
            <div class="section-heading">
              <h2 id="botka-dijazottak-title">Díjazottak</h2>
            </div>

            <div class="table-wrap" role="region" aria-label="Botka Imre-díj díjazottak táblázat" tabindex="0">
              <table class="award-table">
                <thead>
                  <tr>
                    <th scope="col">Év</th>
                    <th scope="col">Díjazott</th>
                    <th scope="col">Díjazott tevékenység</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($dijazottakRows)): ?>
                    <tr>
                      <td colspan="4">Jelenleg nincs megjeleníthető díjazott.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($dijazottakRows as $dijazott): ?>
                      <tr>
                        <td><?php echo htmlspecialchars((string) ($dijazott['ev'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($dijazott['nev'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string) ($dijazott['cim'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                          <?php if (!empty($dijazott['kep_utvonal'])): ?>
                            <img src="<?php echo htmlspecialchars((string) $dijazott['kep_utvonal'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($dijazott['nev'] ?? 'Díjazott képe'), ENT_QUOTES, 'UTF-8'); ?>">
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>

          <section class="botka-section" id="botka-dijatadok" aria-labelledby="botka-dijatadok-title">
            <div class="section-heading">
              <h2 id="botka-dijatadok-title">Díjátadók</h2>
              <p>Ebben a szekcióban a hírek közé feltöltött cikkek közül a kiválasztottakra mutató linkek jelennek meg. A galériaszerű elrendezés később ezeknek a hivatkozásoknak ad helyet.</p>
            <?php include __DIR__ . '/../includes/news-list.php'; ?>
            </div>
            <div class="botka-gallery" aria-label="Botka Imre-díj díjátadók képgaléria">
            </div>
          </section>

          
        </div>
      </section>

      <?php include __DIR__ . '/../includes/sidebar-right.php'; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../includes/calendar-script.php'; ?>
</body>
</html>