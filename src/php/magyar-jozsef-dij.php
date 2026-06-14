<?php
$newsPageTitle = 'MMK-GT - Magyar József-díj';
$newsPageDescription = 'A Magyar József-díjhoz kapcsolódó hírek, átadók és felvételek itt jelennek meg.';
$newsSectionTitle = 'Magyar József-díj átadók';
$newsSectionId = 'magyar-jozsef-aktualis-hirek';
$newsTypeFilter = 'Magyar József-díj átadó';
$newsEmptyMessage = 'Jelenleg nincs megjeleníthető Magyar József-díj átadó hír.';
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
  $statement->execute(['dij_tipus' => 'Magyar József-díj']);
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
  <title>MMK-GT - Magyar József-díj</title>
  <meta name="description" content="A Magyar József-díj bemutató oldala a díj leírásával, díjazottakkal és díjátadókkal.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="magyar-jozsef-title">
        <div class="news-panel botka-page">
          <header class="news-header botka-hero">
            <div class="botka-hero-copy">
              <div class="news-meta"></div>
              <h1 id="magyar-jozsef-title">Magyar József-díj</h1>
              <p>A díszdobozban elhelyezett kör alakú fém plakett, 
                előlapján felirattal: MAGYAR JÓZSEF-DÍJ, Magyar József portréjával valamint a plakett/oklevél sorszáma 
                hátlapján a Magyar Mérnöki Kamara logója és a következő feliratok: „Magyar Mérnöki Kamara Gépészeti Tagozat” illetve: „a Gépészeti Tagozat érdekében végzett kimagasló tevékenységéért” 
                adományozási okirat, mappában: oklevél - (az adományozott neve, az odaítélés indokolása, esetleges egyéb körülmények, dátum, MMK GT logo, tagozat elnökének aláírása 
                – stílusában rövid és tárgyszerű.)</p>
            </div>

            <figure class="botka-hero-figure">
              <img class="botka-hero-image" src="../../img/site/Magyar-dij/magyar-dij.JPG" alt="Magyar József-díj érme" loading="eager">
            </figure>
          </header>

          <section class="botka-content" aria-labelledby="magyar-jozsef-content-title">
            <p>A díjat elnyerheti állampolgárságra és kamarai tagságra való tekintet nélkül azon személy, aki a magyar műszaki, mérnöki társadalommal működő, konstruktív kapcsolatot tart, valamint: 
                a Gépészeti Tagozat szakmai érdekeltségi területeinek ismertségét, elismertségét előre mozdítja, vagy 
                kiemelkedő szakmai munkát végez, amely összefüggésben áll a Gépészeti Tagozat tevékenységével, vagy 
                közéleti, társadalmi szervezeti munkájával a Gépészeti Tagozat működését eredményesen támogatja, jó hírét erősíti. </p>
          </section>

          <section class="botka-intro" aria-labelledby="magyar-jozsef-intro-title">
            <h2 id="magyar-jozsef-intro-title">Magyar József életrajza</h2>
            <p class="botka-intro-quote">Magyar József 1928 január 8-án született Miskolcon. Itt végezte elemi- és középiskolai tanulmányait, 1946-ban érettségizett. A Budapesti Műszaki Egyetem Gépészmérnöki Karán 1950-ben szerzett gépészmérnöki oklevelet. Már hallgató korában foglalkozott tudományos kutatási feladatok megoldásával, dolgozott a Gépelemek Tanszéken, ahol gyakornokként részt vett az oktatói munkában is. Rövid ideig az iparban üzemmérnökként tevékenykedett, majd 1950-től a Gépelemek Tanszéken először tanársegéd, majd, adjunktus, később docens. 1965-től tanszékvezető a Villamosmérnöki Kar Géptan Tanszékén. 1967-ben egyetemi tanári kinevezést kapott. 1969-től 1976-ig a Gépészmérnöki Kar Gépelemek Tanszékének vezetője volt.

                A gépelemek tárgy előadójaként évtizedeken át korszerűsítette az előadási és gyakorlati tananyagot. Szemléletváltást hozott a szerkezeti kölcsönhatások rendszerszemléletű tárgyalásával, ami pontosabb géptervezési eredményekhez vezetett. 1971-re elkészült az a közel 1000 oldalnyi sokszorosított előadás-vázlata és tervezési segédlete, amelyet nemcsak a hazai, hanem külföldi főiskolák és egyetemek is hasznosítottak. Rektori nívódíjat kapott a 16 x 3 órás laboratóriumi oktatási programja, amelynek teljes gépi felszerelését, bemutató eszközeit, műszerezését a tanszéke saját erőből teremtette meg. A laboratóriumi program tanulmányozására még a 80-as években is jöttek külföldi egyetemi oktatók.

                Kezdeményezésére jött létre a BME Gépészmérnöki Karán a Géptervező Szak és annak Gépszerkesztő Ágazata. Kidolgozta a szak és az ágazat tantervét és több tantárgyának programját. Az 1990-es reformban a Géptervező Modul és az Alkalmazott Mechanika Modul Modulbizottságok elnökeként e két, igen sikeres képzési irány tantervének és tantárgyprogramjainak kidolgozását irányította. Számos mérnöktovábbképző tanfolyama segítette a korszerű géptervezésért tevékenykedő mérnökök munkáját.
                Aktívan részt vett a tudományos közéletben. Az MTA Gépszerkezettani Bizottságának titkáraként, majd elnökeként – 3 évtizedes munkájával – sokat tett a gépszerkezettan tudományos elismertetéséért és a tudományterület oktatásáért. Megteremtette és hosszú időn keresztül vezette a hazai tribológiai tudományos szervezeteket, a Gépipari Tudományos Egyesület Tribológiai Szakosztályát, és az MTA Gépszerkezettani Bizottság Tribológiai Albizottságát.

                Sokrétű ipari szakértői tevékenysége során a magyar fél szakértője volt nemzetközi jogvitákban. Tanulmányai alapozták meg a hazai csavarszivattyú-gyártást és annak fejlődését. A csavarszivattyúval kapcsolatosak szabadalmai megkapták a svéd, a svájci, az angol és a német védettséget is. A magyar csavarszivattyú gyártásnak abban az időben a kelet-európai országokban nem volt konkurenciája.
                Kiemelkedő munkájáért számos elismerést kapott.
            
                Jelentékeny szervezőmunkát végzett a Magyar Mérnöki Kamara, Gépészeti Tagozat elnökeként. Munkatársai, utódai, tanítványai, a tudományos és tanítói munkájukban, gyakran támaszkodtak személyes segítőkészségére Tanított, irányított - példát adva tudásból, akaraterőből, emberségből.

                Dr. Váradi Károly</p>
          </section>

          <section class="botka-section" id="magyar-jozsef-dijazottak" aria-labelledby="magyar-jozsef-dijazottak-title">
            <div class="section-heading">
              <h2 id="magyar-jozsef-dijazottak-title">Díjazottak</h2>
            </div>

            <div class="table-wrap" role="region" aria-label="Magyar József-díj díjazottak táblázat" tabindex="0">
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

          <section class="botka-section" id="magyar-jozsef-dijatadok" aria-labelledby="magyar-jozsef-dijatadok-title">
            <div class="section-heading">
              <h2 id="magyar-jozsef-dijatadok-title">Díjátadók</h2>
              <p>Ide a hírek közé feltöltött cikkek közül a kiválasztott díjátadó-anyagok linkjei kerülnek.</p>
            </div>

            <div class="botka-gallery" aria-label="Magyar József-díj díjátadók képgaléria">

            </div>
          </section>

          <?php include __DIR__ . '/../includes/news-list.php'; ?>
        </div>
      </section>

      <?php include __DIR__ . '/../includes/sidebar-right.php'; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../includes/calendar-script.php'; ?>
</body>
</html>