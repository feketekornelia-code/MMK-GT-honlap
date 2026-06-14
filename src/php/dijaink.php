<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Díjaink</title>
  <meta name="description" content="Az MMK Gépészeti Tagozat díjainak, díjazottjainak és díjátadóinak összefoglaló oldala.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="dijaink-title">
        <div class="news-panel">
          <div class="news-header">
            <h1 id="dijaink-title">Díjaink</h1>
          </div>

          <div class="news-grid" aria-label="Díjaink tartalmi blokkjai">
            <article class="news-card" id="botka-imre">
              <h2><a href="botka-dij.php">Botka Imre-díj</a></h2>
              <p>A díj bemutató oldala külön aloldalon nyílik meg a díjazottakkal és a díjátadókkal együtt.</p>
            </article>

            <article class="news-card" id="magyar-jozsef">
              <h2><a href="magyar-jozsef-dij.php">Magyar József-díj</a></h2>
              <p>Hasonló felépítésű bemutató oldal a másik szakmai díjhoz, szintén dokumentummal és bemutatkozással.</p>
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