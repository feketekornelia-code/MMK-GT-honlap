<?php
$newsPageTitle = 'MMK-GT - Beszámolóink';
$newsPageDescription = 'A rendezvényekhez, beszámolókhoz és történeti anyagokhoz tartozó friss hírek itt jelennek meg.';
$newsSectionTitle = 'Beszámolók';
$newsSectionId = 'beszamolok-hirek';
$newsTypeFilter = 'Beszámolók';
$newsEmptyMessage = 'Jelenleg nincs megjeleníthető hír a Beszámolók kategóriában.';
?>
<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Beszámolóink</title>
  <meta name="description" content="Az MMK Gépészeti Tagozat beszámolókat, történeti anyagokat és neves elődeink tartalmait összegző oldala.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="beszamolok-title">
        <div class="news-panel">
          <div class="news-header">
            <h1 id="beszamolok-title">Beszámolóink</h1>
            <p>A rendezvényekről, koszorúzásokról és a kamaratörténethez kapcsolódó anyagokról itt jelenhetnek meg az összegző oldalak.</p>
          </div>

          <div class="news-grid" aria-label="Beszámolóink tartalmi blokkjai">
            <article class="news-card" id="beszamolok">
              <div class="news-meta"><span class="pill">Beszámolók</span></div>
              <h2>Beszámolók</h2>
              <p>A szakmai rendezvények, koszorúzások és megemlékezések rövid, cikkekkel kiegészített összefoglalói.</p>
            </article>

            <article class="news-card" id="tortenet">
              <div class="news-meta"><span class="pill">Történet</span></div>
              <h2>Mérnökkamara története</h2>
              <p>A tagozat és a kamara történeti összefoglalói, valamint az ehhez kapcsolódó kiadványok helye.</p>
            </article>

            <article class="news-card" id="neves-elodeink">
              <div class="news-meta"><span class="pill">Elődök</span></div>
              <h2>Neves elődeink</h2>
              <p>Azok a szakemberek, akikhez a tagozat emléknapjai, díjai vagy szakmai hagyományai kapcsolódnak.</p>
            </article>
          </div>

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