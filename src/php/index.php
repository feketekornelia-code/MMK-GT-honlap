<?php
$newsPageTitle = 'MMK-GT - Hírek';
$newsPageDescription = 'Friss hírek és közlemények az MMK Gépészeti Tagozattól.';
$newsSectionTitle = 'Friss hírek';
$newsSectionId = 'hirek-lista';
$newsTypeFilter = '';
$newsHideArchived = true;
$newsEmptyMessage = 'Jelenleg nincs megjeleníthető hír.';
?>
<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Friss hírek</title>
  <meta name="description" content="Az MMK Gépészeti Tagozat nyitóoldala, amely a friss híreket, a bal oldali menüt és a naptármozaikot jeleníti meg.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="news-title">
        <div class="news-panel">
          <div class="news-header">
            <h1 id="news-title">Híreink</h1>
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