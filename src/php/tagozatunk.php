<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Tagozatunk</title>
  <meta name="description" content="Az MMK Gépészeti Tagozat összefoglaló oldala a szervezetről, érdekeltségi területekről, jogosultságokról és dokumentumokról.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="tagozatunk-title">
        <div class="news-panel">
          <div class="news-header">
            <h1 id="tagozatunk-title">Tagozatunk</h1>
            <p>Az oldal a tagozat szervezeti felépítését, fő szakterületeit és dokumentumait összegzi.</p>
          </div>

          <div class="news-grid" aria-label="Tagozatunk tartalmi blokkjai">
            <article class="news-card" id="tagozati-szervezet">
              <div class="news-meta"><span class="pill">Szervezet</span></div>
              <h2>Tagozati szervezet</h2>
              <p>Itt jelenhet meg a tagozat felépítése, a vezetőség, a szakmai csoportok és a kapcsolódó elérhetőségek.</p>
            </article>

            <article class="news-card" id="erdekeltsegi-teruletek">
              <div class="news-meta"><span class="pill">Fókuszterületek</span></div>
              <h2>Érdekeltségi területek</h2>
              <p>A gépészeti tagozat szakmai fókuszai, témacsoportjai és együttműködési területei itt kapnak helyet.</p>
            </article>

            <article class="news-card" id="jogosultsagok">
              <div class="news-meta"><span class="pill">Jogosultságok</span></div>
              <h2>Jogosultságok, tanúsítványok</h2>
              <p>Az oldalon a végzettséghez, jogosultságokhoz és tanúsítványokhoz kapcsolódó információk jelenhetnek meg.</p>
            </article>

            <article class="news-card" id="dokumentumok">
              <div class="news-meta"><span class="pill">Dokumentumok</span></div>
              <h2><a href="dokumentumok.php">Dokumentumok</a></h2>
              <p>A szabályzatok, határozatok és tagozati beszámolók külön letölthető blokkokban rendezhetők el.</p>
            </article>

            <article class="news-card" id="szabalyzatok">
              <h2><a href="szabalyzatok.php">Szabályzatok</a></h2>
              <p>Letölthető vagy beágyazott szabályzati anyagok, amelyek a tagozat működését támogatják.</p>
            </article>

            <article class="news-card" id="hatarozatok">
              <h2><a href="hatarozatok.php">Határozatok</a></h2>
              <p>Az aktuális és korábbi határozatok itt jelenhetnek meg dokumentált, visszakereshető formában.</p>
            </article>

            <article class="news-card" id="tagozati-beszamolok">
              <h2><a href="tagozati-beszamolok.php">Tagozati beszámolók</a></h2>
              <p>Éves vagy időszakos tagozati beszámolók, PDF-ek és kivonatok számára kialakított összegző rész.</p>
            </article>
          </div>
        </div>
      </section>

      <?php include __DIR__ . '/../includes/sidebar-right.php'; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </div>
    <?php include __DIR__ . '/../includes/calendar-script.php'; ?>
  </body>
  </html>