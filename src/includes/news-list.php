<?php
if (!isset($newsPageTitle, $newsPageDescription, $newsSectionTitle, $newsTypeFilter)) {
  throw new RuntimeException('A hír lista megjelenítéséhez szükséges beállítások hiányoznak.');
}

$newsSectionId = $newsSectionId ?? 'news-feed';
$newsEmptyMessage = $newsEmptyMessage ?? 'Jelenleg nincs megjeleníthető hír ebben a kategóriában.';
$newsHideArchived = $newsHideArchived ?? false;
$newsRows = [];

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

  $newsWhere = [];
  $newsParameters = [];

  if ($newsTypeFilter !== '' && $newsTypeFilter !== null) {
    $newsWhere[] = 'hir_tipus = :hir_tipus';
    $newsParameters['hir_tipus'] = $newsTypeFilter;
  }

  if ($newsHideArchived) {
    $newsWhere[] = 'archivalt = 0';
  }

  $newsQuery = 'SELECT id, cim, tartalom, hir_tipus, feltoltes_datuma FROM hirek';
  if (!empty($newsWhere)) {
    $newsQuery .= ' WHERE ' . implode(' AND ', $newsWhere);
  }
  $newsQuery .= ' ORDER BY feltoltes_datuma DESC, id DESC';

  $statement = $pdo->prepare($newsQuery);
  if (!empty($newsParameters)) {
    $statement->execute($newsParameters);
  } else {
    $statement->execute();
  }
  $newsRows = $statement->fetchAll();
} catch (Throwable $exception) {
  $newsRows = [];
}
?>

  <div class="news-feed-grid" aria-label="<?php echo htmlspecialchars($newsSectionTitle, ENT_QUOTES, 'UTF-8'); ?> hírkártyák">
    <?php if (empty($newsRows)): ?>
      <p class="news-feed-empty"><?php echo htmlspecialchars($newsEmptyMessage, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php else: ?>
      <?php foreach ($newsRows as $newsRow): ?>
        <?php
          $newsTitle = (string) ($newsRow['cim'] ?? '');
          $newsContent = (string) ($newsRow['tartalom'] ?? '');
        ?>
        <article class="news-feed-card">
          <h3 class="news-feed-title"><?php echo htmlspecialchars($newsTitle, ENT_QUOTES, 'UTF-8'); ?></h3>
          <div class="botka-content news-feed-content">
            <?php echo $newsContent; ?>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
