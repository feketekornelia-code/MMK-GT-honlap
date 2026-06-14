<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Mérnökeinknek</title>
  <meta name="description" content="Az MMK Gépészeti Tagozat mérnököknek szóló összefoglaló oldala jogszabályokkal, jelentkezéssel, továbbképzésekkel és pályázatokkal.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="mernokeinknek-title">
        <div class="news-panel">
          <div class="news-header">
            <h1 id="mernokeinknek-title">Mérnökeinknek</h1>
            <p>A gyakorló mérnököknek szóló jogi, szakmai és képzési tartalmak, valamint az utánpótlást támogató anyagok helye.</p>
          </div>

          <div class="news-grid" aria-label="Mérnökeinknek tartalmi blokkjai">
            <article class="news-card" id="jogszabalyok">
              <div class="news-meta"><span class="pill">Szabályozás</span></div>
              <h2>Jogszabályok</h2>
              <p>Jogszabály-gyűjtemények, hivatalos hivatkozások és a napi munkát érintő alapvető előírások listája.</p>
            </article>

            <article class="news-card" id="jelentkezes">
              <div class="news-meta"><span class="pill">Belépés</span></div>
              <h2>Jelentkezés a Kamarába</h2>
              <p>Belépési információk, űrlapok és a tagozathoz való csatlakozás gyakorlati lépései.</p>
            </article>

            <article class="news-card" id="jogosultsag-tanusitvany">
              <div class="news-meta"><span class="pill">Jogosultság</span></div>
              <h2>Jogosultság és tanúsítványok</h2>
              <p>A tanúsítványkérelem, a kapcsolódó dokumentumok és a jogosultságok áttekintő oldala.</p>
            </article>

            <article class="news-card" id="tovabbkepzesek">
              <div class="news-meta"><span class="pill">Képzés</span></div>
              <h2>Továbbképzések</h2>
              <p>Következő kurzusok, események és szakmai képzések összegyűjtött listája.</p>
            </article>

            <article class="news-card" id="szakmai-eloadasok">
              <div class="news-meta"><span class="pill">Szakmai tartalom</span></div>
              <h2>Szakmai előadások</h2>
              <p>Előadások, cikkajánlók és más szakmai tartalmak, amelyek a mindennapi munkát támogatják.</p>
            </article>

            <article class="news-card" id="diplomadij">
              <div class="news-meta"><span class="pill">Utánpótlás</span></div>
              <h2>Diplomadíj pályázat</h2>
              <p>Fiatal mérnököknek szóló pályázatok, eredmények és a kapcsolódó díjazott tartalmak helye.</p>
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