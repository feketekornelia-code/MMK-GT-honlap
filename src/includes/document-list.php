<?php
if (!isset($documentPageTitle, $documentPageDescription, $documentTypeFilter, $documentSectionTitle, $documentPageHeading)) {
  throw new RuntimeException('A dokumentum lista megjelenítéséhez szükséges beállítások hiányoznak.');
}

$documentRows = [];

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

  $statement = $pdo->prepare('SELECT id, cim, dokumentum_tipus, dokumentum_utvonal FROM dokumentumok WHERE dokumentum_tipus = :dokumentum_tipus AND archivalt = 0 ORDER BY cim ASC');
  $statement->execute(['dokumentum_tipus' => $documentTypeFilter]);
  $documentRows = $statement->fetchAll();
} catch (Throwable $exception) {
  $documentRows = [];
}
?>

<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($documentPageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($documentPageDescription, ENT_QUOTES, 'UTF-8'); ?>">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="document-page-title">
        <div class="news-panel botka-page">
          <header class="news-header botka-hero">
            <div class="botka-hero-copy">
              <div class="news-meta"></div>
              <h1 id="document-page-title"><?php echo htmlspecialchars($documentPageHeading, ENT_QUOTES, 'UTF-8'); ?></h1>
              <p><?php echo htmlspecialchars($documentPageDescription, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <figure class="botka-hero-figure">
              <img class="botka-hero-image" src="../../img/site/Dokumentumok/dokumentumok.png" alt="MMK Gépészeti Tagozat logó" loading="eager">
            </figure>
          </header>

          <section class="botka-section" aria-labelledby="document-section-title">
            <div class="table-wrap" role="region" aria-label="<?php echo htmlspecialchars($documentSectionTitle, ENT_QUOTES, 'UTF-8'); ?> listája" tabindex="0">
              <table class="award-table document-table">
                <thead>
                  <tr>
                    <th scope="col">Cím</th>
                    <th scope="col">Dokumentum</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($documentRows)): ?>
                    <tr>
                      <td colspan="2">Jelenleg nincs megjeleníthető dokumentum ebben a kategóriában.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($documentRows as $documentRow): ?>
                      <?php $fileName = basename((string) ($documentRow['dokumentum_utvonal'] ?? '')); ?>
                      <tr>
                        <td><?php echo htmlspecialchars((string) ($documentRow['cim'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                          <?php if ($fileName !== ''): ?>
                            <a href="../../docs/dokumentumok/<?php echo rawurlencode($fileName); ?>" download><?php echo htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8'); ?></a>
                          <?php else: ?>
                            <span>Nincs fájlnév</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>
      </section>

      <?php include __DIR__ . '/sidebar-right.php'; ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/calendar-script.php'; ?>
</body>
</html>