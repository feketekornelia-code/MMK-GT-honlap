<?php
require_once __DIR__ . '/admin-session.php';

if (empty($_SESSION['admin_id'])) {
  header('Location: admin-login.php');
  exit;
}

$errorMessage = '';
$infoMessage = '';
$messageTargetTable = '';
$showDocumentForm = false;
$documentFormMode = 'create';
$documentRecordId = 0;
$documentTitleValue = '';
$documentTypeValue = '';
$documentFileValue = '';
$currentDateValue = date('Y-m-d');
$currentYearValue = date('Y');
$showHirekForm = false;
$hirekFormMode = 'create';
$hirekRecordId = 0;
$hirekTitleValue = '';
$hirekTypeValue = 'Hírek';
$hirekContentValue = '';
$hirekDateValue = $currentDateValue;
$showSzakmaiForm = false;
$szakmaiFormMode = 'create';
$szakmaiRecordId = 0;
$szakmaiTitleValue = '';
$szakmaiTypeValue = '';
$szakmaiDescriptionValue = '';
$szakmaiLinkValue = '';
$showNaptarForm = false;
$naptarFormMode = 'create';
$naptarRecordId = 0;
$naptarTitleValue = '';
$naptarTypeValue = '';
$naptarDescriptionValue = '';
$naptarDateValue = '';
$naptarImageValue = '';
$showDijazottakForm = false;
$dijazottakFormMode = 'create';
$dijazottakRecordId = 0;
$dijazottakEvValue = $currentYearValue;
$dijazottakCimValue = '';
$dijazottakNevValue = '';
$dijazottakTipusValue = '';
$dijazottakImageValue = '';
$hirekTypeOptions = ['Beszámolók', 'Botka Imre-díj átadó', 'Magyar József-díj átadó', 'Hírek'];
$szakmaiTypeOptions = ['Kiadvány', 'Cikk', 'Előadás'];
$naptarTypeOptions = ['Megemlékezés', 'Továbbképzés', 'Évforduló', 'Konferencia'];
$dijazottakTypeOptions = ['Botka Imre-díj', 'Magyar József-díj'];
$manageableTables = ['dokumentumok', 'hirek', 'szakmai_anyagok', 'naptar', 'dijazottak'];
$archivableTables = ['dokumentumok', 'hirek', 'szakmai_anyagok'];
$formToTableMap = [
  'document_create' => 'dokumentumok',
  'document_update' => 'dokumentumok',
  'hirek_create' => 'hirek',
  'hirek_update' => 'hirek',
  'szakmai_create' => 'szakmai_anyagok',
  'szakmai_update' => 'szakmai_anyagok',
  'naptar_create' => 'naptar',
  'naptar_update' => 'naptar',
  'dijazottak_create' => 'dijazottak',
  'dijazottak_update' => 'dijazottak',
];

if (isset($_SESSION['admin_flash']) && is_array($_SESSION['admin_flash'])) {
  $flash = $_SESSION['admin_flash'];
  $flashType = (string) ($flash['type'] ?? '');
  $flashMessage = (string) ($flash['message'] ?? '');
  $flashTarget = (string) ($flash['target'] ?? '');

  if ($flashMessage !== '') {
    if ($flashType === 'error') {
      $errorMessage = $flashMessage;
    } else {
      $infoMessage = $flashMessage;
    }
  }

  if ($flashTarget !== '') {
    $messageTargetTable = $flashTarget;
  }

  unset($_SESSION['admin_flash']);
}

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
} catch (Throwable $exception) {
  $pdo = null;
  $errorMessage = 'Az admin felület nem éri el az adatbázist. Ellenőrizd a kapcsolatot és a táblákat.';
}

if (isset($_GET['new']) && $_GET['new'] !== '') {
  $requestedTable = preg_replace('/[^a-z_]/', '', (string) $_GET['new']);
  if ($requestedTable !== '') {
    if ($requestedTable === 'dokumentumok') {
      $showDocumentForm = true;
      $messageTargetTable = 'dokumentumok';
    } elseif ($requestedTable === 'hirek') {
      $showHirekForm = true;
      $hirekDateValue = $currentDateValue;
      $hirekTypeValue = 'Hírek';
      $messageTargetTable = 'hirek';
      $infoMessage = 'Új hírek felvitele űrlap megnyitva.';
    } elseif ($requestedTable === 'szakmai_anyagok') {
      $showSzakmaiForm = true;
      $messageTargetTable = 'szakmai_anyagok';
      $infoMessage = 'Új szakmai anyag felvitele űrlap megnyitva.';
    } elseif ($requestedTable === 'naptar') {
      $showNaptarForm = true;
      $messageTargetTable = 'naptar';
      $infoMessage = 'Új naptári esemény felvitele űrlap megnyitva.';
    } elseif ($requestedTable === 'dijazottak') {
      $showDijazottakForm = true;
      $dijazottakEvValue = $currentYearValue;
      $messageTargetTable = 'dijazottak';
      $infoMessage = 'Új díjazott felvitele űrlap megnyitva.';
    } else {
      $infoMessage = 'Új felvitele gomb kattintva: ' . $requestedTable . '.';
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $formType = $_POST['form_type'] ?? '';
  $rowAction = $_POST['row_action'] ?? '';
  $rowTable = preg_replace('/[^a-z_]/', '', (string) ($_POST['row_table'] ?? ''));
  $rowId = (int) ($_POST['row_id'] ?? 0);

  if ($rowTable !== '' && in_array($rowTable, $manageableTables, true)) {
    $messageTargetTable = $rowTable;
  }
  if ($formType !== '' && isset($formToTableMap[$formType])) {
    $messageTargetTable = $formToTableMap[$formType];
  }

  $actionLabels = [
    'edit' => 'Szerkesztés',
    'delete' => 'Törlés',
    'archive' => 'Archiválás',
  ];

  if (isset($actionLabels[$rowAction]) && $rowTable !== '' && $rowId > 0) {
    if ($rowAction === 'edit' && $rowTable === 'dokumentumok' && $pdo instanceof PDO) {
      $statement = $pdo->prepare('SELECT id, cim, dokumentum_tipus, dokumentum_utvonal FROM dokumentumok WHERE id = :id LIMIT 1');
      $statement->execute(['id' => $rowId]);
      $document = $statement->fetch();

      if ($document) {
        $documentFormMode = 'edit';
        $documentRecordId = (int) $document['id'];
        $documentTitleValue = (string) $document['cim'];
        $documentTypeValue = (string) $document['dokumentum_tipus'];
        $documentFileValue = basename((string) $document['dokumentum_utvonal']);
        $showDocumentForm = true;
        $infoMessage = 'A kiválasztott dokumentum adatai betöltve szerkesztésre.';
      } else {
        $errorMessage = 'A kiválasztott dokumentum nem található.';
      }
    } elseif ($rowAction === 'edit' && $rowTable === 'hirek' && $pdo instanceof PDO) {
      $statement = $pdo->prepare('SELECT id, cim, tartalom, hir_tipus, feltoltes_datuma FROM hirek WHERE id = :id LIMIT 1');
      $statement->execute(['id' => $rowId]);
      $hir = $statement->fetch();

      if ($hir) {
        $hirekFormMode = 'edit';
        $hirekRecordId = (int) $hir['id'];
        $hirekTitleValue = (string) $hir['cim'];
        $hirekContentValue = (string) $hir['tartalom'];
        $hirekTypeValue = (string) $hir['hir_tipus'];
        $hirekDateValue = (string) $hir['feltoltes_datuma'];
        $showHirekForm = true;
        $infoMessage = 'A kiválasztott hír adatai betöltve szerkesztésre.';
      } else {
        $errorMessage = 'A kiválasztott hír nem található.';
      }
    } elseif ($rowAction === 'edit' && $rowTable === 'szakmai_anyagok' && $pdo instanceof PDO) {
      $statement = $pdo->prepare('SELECT id, cim, szakmai_tipus, leiras, link FROM szakmai_anyagok WHERE id = :id LIMIT 1');
      $statement->execute(['id' => $rowId]);
      $szakmai = $statement->fetch();

      if ($szakmai) {
        $szakmaiFormMode = 'edit';
        $szakmaiRecordId = (int) $szakmai['id'];
        $szakmaiTitleValue = (string) $szakmai['cim'];
        $szakmaiTypeValue = (string) $szakmai['szakmai_tipus'];
        $szakmaiDescriptionValue = (string) $szakmai['leiras'];
        $szakmaiLinkValue = (string) $szakmai['link'];
        $showSzakmaiForm = true;
        $infoMessage = 'A kiválasztott szakmai anyag adatai betöltve szerkesztésre.';
      } else {
        $errorMessage = 'A kiválasztott szakmai anyag nem található.';
      }
    } elseif ($rowAction === 'edit' && $rowTable === 'naptar' && $pdo instanceof PDO) {
      $statement = $pdo->prepare('SELECT id, cim, esemeny_tipus, leiras, kep_utvonal, datum FROM naptar WHERE id = :id LIMIT 1');
      $statement->execute(['id' => $rowId]);
      $naptar = $statement->fetch();

      if ($naptar) {
        $naptarFormMode = 'edit';
        $naptarRecordId = (int) $naptar['id'];
        $naptarTitleValue = (string) $naptar['cim'];
        $naptarTypeValue = (string) $naptar['esemeny_tipus'];
        $naptarDescriptionValue = (string) $naptar['leiras'];
        $naptarImageValue = basename((string) $naptar['kep_utvonal']);
        $naptarDateValue = (string) $naptar['datum'];
        $showNaptarForm = true;
        $infoMessage = 'A kiválasztott naptári esemény adatai betöltve szerkesztésre.';
      } else {
        $errorMessage = 'A kiválasztott naptári esemény nem található.';
      }
    } elseif ($rowAction === 'edit' && $rowTable === 'dijazottak' && $pdo instanceof PDO) {
      $statement = $pdo->prepare('SELECT id, ev, cim, nev, kep_utvonal, dij_tipus FROM dijazottak WHERE id = :id LIMIT 1');
      $statement->execute(['id' => $rowId]);
      $dijazott = $statement->fetch();

      if ($dijazott) {
        $dijazottakFormMode = 'edit';
        $dijazottakRecordId = (int) $dijazott['id'];
        $dijazottakEvValue = (string) $dijazott['ev'];
        $dijazottakCimValue = (string) $dijazott['cim'];
        $dijazottakNevValue = (string) $dijazott['nev'];
        $dijazottakTipusValue = (string) $dijazott['dij_tipus'];
        $dijazottakImageValue = basename((string) $dijazott['kep_utvonal']);
        $showDijazottakForm = true;
        $infoMessage = 'A kiválasztott díjazott adatai betöltve szerkesztésre.';
      } else {
        $errorMessage = 'A kiválasztott díjazott nem található.';
      }
    } elseif ($rowAction === 'archive' && $rowTable === 'hirek' && $pdo instanceof PDO) {
      try {
        $statement = $pdo->prepare('SELECT archivalt FROM hirek WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $rowId]);
        $currentState = (int) ($statement->fetch()['archivalt'] ?? 0);

        if ($currentState < 0) {
          throw new RuntimeException('A kiválasztott hír nem található.');
        }
        $newState = $currentState === 1 ? 0 : 1;

        $statement = $pdo->prepare('UPDATE hirek SET archivalt = :archivalt WHERE id = :id');
        $statement->execute([
          'archivalt' => $newState,
          'id' => $rowId,
        ]);

        $infoMessage = $newState === 1
          ? 'Rekord archiválása sikeres: ' . $rowTable . ' | #' . $rowId
          : 'Rekord visszaállítása sikeres: ' . $rowTable . ' | #' . $rowId;
      } catch (Throwable $exception) {
        $errorMessage = 'Az archiválás nem sikerült.';
      }
      $showDocumentForm = false;
      $documentFormMode = 'create';
      $documentRecordId = 0;
      $documentTitleValue = '';
      $documentTypeValue = '';
      $documentFileValue = '';
      $showHirekForm = false;
      $hirekFormMode = 'create';
      $hirekRecordId = 0;
      $hirekTitleValue = '';
      $hirekContentValue = '';
      $hirekTypeValue = 'Hírek';
      $hirekDateValue = $currentDateValue;
      $showSzakmaiForm = false;
      $szakmaiFormMode = 'create';
      $szakmaiRecordId = 0;
      $szakmaiTitleValue = '';
      $szakmaiTypeValue = '';
      $szakmaiDescriptionValue = '';
      $szakmaiLinkValue = '';
      $showNaptarForm = false;
      $naptarFormMode = 'create';
      $naptarRecordId = 0;
      $naptarTitleValue = '';
      $naptarTypeValue = '';
      $naptarDescriptionValue = '';
      $naptarDateValue = '';
      $naptarImageValue = '';
      $showDijazottakForm = false;
      $dijazottakFormMode = 'create';
      $dijazottakRecordId = 0;
      $dijazottakEvValue = $currentYearValue;
      $dijazottakCimValue = '';
      $dijazottakNevValue = '';
      $dijazottakTipusValue = '';
      $dijazottakImageValue = '';
    } elseif ($rowAction === 'archive' && $pdo instanceof PDO && in_array($rowTable, $archivableTables, true)) {
      try {
        $statement = $pdo->prepare('SELECT archivalt FROM ' . $rowTable . ' WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $rowId]);
        $currentState = (int) ($statement->fetch()['archivalt'] ?? 0);
        $newState = $currentState === 1 ? 0 : 1;

        $statement = $pdo->prepare('UPDATE ' . $rowTable . ' SET archivalt = :archivalt WHERE id = :id');
        $statement->execute([
          'archivalt' => $newState,
          'id' => $rowId,
        ]);

        $infoMessage = $newState === 1
          ? 'Rekord archiválása sikeres: ' . $rowTable . ' | #' . $rowId
          : 'Rekord visszaállítása sikeres: ' . $rowTable . ' | #' . $rowId;
      } catch (Throwable $exception) {
        $errorMessage = 'Az archiválás nem sikerült.';
      }
      $showDocumentForm = false;
      $documentFormMode = 'create';
      $documentRecordId = 0;
      $documentTitleValue = '';
      $documentTypeValue = '';
      $documentFileValue = '';
      $showHirekForm = false;
      $hirekFormMode = 'create';
      $hirekRecordId = 0;
      $hirekTitleValue = '';
      $hirekContentValue = '';
      $hirekTypeValue = 'Hírek';
      $hirekDateValue = $currentDateValue;
      $showSzakmaiForm = false;
      $szakmaiFormMode = 'create';
      $szakmaiRecordId = 0;
      $szakmaiTitleValue = '';
      $szakmaiTypeValue = '';
      $szakmaiDescriptionValue = '';
      $szakmaiLinkValue = '';
      $showNaptarForm = false;
      $naptarFormMode = 'create';
      $naptarRecordId = 0;
      $naptarTitleValue = '';
      $naptarTypeValue = '';
      $naptarDescriptionValue = '';
      $naptarDateValue = '';
      $naptarImageValue = '';
      $showDijazottakForm = false;
      $dijazottakFormMode = 'create';
      $dijazottakRecordId = 0;
      $dijazottakEvValue = $currentYearValue;
      $dijazottakCimValue = '';
      $dijazottakNevValue = '';
      $dijazottakTipusValue = '';
      $dijazottakImageValue = '';
    } elseif ($rowAction === 'delete' && $pdo instanceof PDO && in_array($rowTable, $manageableTables, true)) {
      if ($rowTable === 'hirek') {
        try {
          $statement = $pdo->prepare('SELECT id FROM hirek WHERE id = :id LIMIT 1');
          $statement->execute(['id' => $rowId]);
          if (!$statement->fetch()) {
            throw new RuntimeException('A kiválasztott hír nem található.');
          }

          $statement = $pdo->prepare('DELETE FROM hirek WHERE id = :id');
          $statement->execute(['id' => $rowId]);

          $infoMessage = 'Rekord törlése sikeres: ' . $rowTable . ' | #' . $rowId;
        } catch (Throwable $exception) {
          $errorMessage = 'A törlés nem sikerült.';
        }
      } else {
        try {
          $statement = $pdo->prepare('DELETE FROM ' . $rowTable . ' WHERE id = :id');
          $statement->execute(['id' => $rowId]);
          $infoMessage = 'Rekord törlése sikeres: ' . $rowTable . ' | #' . $rowId;
        } catch (Throwable $exception) {
          $errorMessage = 'A törlés nem sikerült.';
        }
      }
      $showDocumentForm = false;
      $documentFormMode = 'create';
      $documentRecordId = 0;
      $documentTitleValue = '';
      $documentTypeValue = '';
      $documentFileValue = '';
      $hirekDateValue = $currentDateValue;
      $hirekTypeValue = 'Hírek';
      $hirekContentValue = '';
      $showHirekForm = false;
      $hirekFormMode = 'create';
      $hirekRecordId = 0;
      $hirekTitleValue = '';
      $hirekTypeValue = 'Hírek';
      $hirekDateValue = $currentDateValue;
      $showSzakmaiForm = false;
      $szakmaiFormMode = 'create';
      $szakmaiRecordId = 0;
      $szakmaiTitleValue = '';
      $szakmaiTypeValue = '';
      $szakmaiDescriptionValue = '';
      $szakmaiLinkValue = '';
      $showNaptarForm = false;
      $naptarFormMode = 'create';
      $naptarRecordId = 0;
      $naptarTitleValue = '';
      $naptarTypeValue = '';
      $naptarDescriptionValue = '';
      $naptarDateValue = '';
      $naptarImageValue = '';
      $showDijazottakForm = false;
      $dijazottakFormMode = 'create';
      $dijazottakRecordId = 0;
      $dijazottakEvValue = $currentYearValue;
      $dijazottakCimValue = '';
      $dijazottakNevValue = '';
      $dijazottakTipusValue = '';
      $dijazottakImageValue = '';
    } elseif ($rowAction === 'archive') {
      $infoMessage = 'Az adott táblában nincs archivált mező, így az archiválás nem elérhető.';
    } elseif ($rowAction === 'delete') {
      $infoMessage = 'Művelet kérése fogadva: ' . $actionLabels[$rowAction] . ' | tabla: ' . $rowTable . ' | rekord: #' . $rowId . '. A végrehajtás a következő lépésben kerül be.';
    } else {
      $infoMessage = 'Művelet kérése fogadva: ' . $actionLabels[$rowAction] . ' | tabla: ' . $rowTable . ' | rekord: #' . $rowId . '. A végrehajtás a következő lépésben kerül be.';
    }
  }

  if ($formType === 'document_create') {
    $documentTitle = trim($_POST['document_title'] ?? '');
    $documentType = trim($_POST['document_type'] ?? '');
    $documentFileName = '';

    if (isset($_FILES['document_file']) && is_array($_FILES['document_file'])) {
      $uploadedName = (string) ($_FILES['document_file']['name'] ?? '');
      $documentFileName = basename($uploadedName);
    }

    $allowedTypes = [
      'tagozati beszamolo',
      'szabalyzat',
      'hatarozat',
      'dokumentumok(egyéb)',
    ];

    if ($documentTitle === '' || $documentType === '' || $documentFileName === '') {
      $errorMessage = 'Minden dokumentummezot ki kell tolteni.';
      $showDocumentForm = true;
    } elseif (!in_array($documentType, $allowedTypes, true)) {
      $errorMessage = 'Ervenytelen dokumentum tipus.';
      $showDocumentForm = true;
    } else {
      try {
        $statement = $pdo->prepare('INSERT INTO dokumentumok (cim, dokumentum_tipus, dokumentum_utvonal, archivalt) VALUES (:cim, :dokumentum_tipus, :dokumentum_utvonal, :archivalt)');
        $statement->execute([
          'cim' => $documentTitle,
          'dokumentum_tipus' => $documentType,
          'dokumentum_utvonal' => '../../docs/dokumentumok/' . $documentFileName,
          'archivalt' => 0,
        ]);

        $infoMessage = 'Dokumentum felvitele sikeres. A fajlnev mentve lett: ' . $documentFileName;
        $showDocumentForm = false;
      } catch (Throwable $exception) {
        $errorMessage = 'A dokumentum mentese nem sikerult.';
        $showDocumentForm = true;
      }
    }
  }

  if ($formType === 'hirek_create' || $formType === 'hirek_update') {
    $hirekRecordId = (int) ($_POST['hirek_id'] ?? 0);
    $hirekTitle = trim($_POST['hirek_title'] ?? '');
    $hirekContent = trim($_POST['hirek_content'] ?? '');
    $hirekType = trim($_POST['hirek_type'] ?? '');
    $hirekDate = trim($_POST['hirek_date'] ?? '');

    $hirekTitleValue = $hirekTitle;
    $hirekContentValue = $hirekContent;
    $hirekTypeValue = $hirekType !== '' ? $hirekType : 'Hírek';
    $hirekDateValue = $hirekDate !== '' ? $hirekDate : $currentDateValue;

    if ($hirekTitle === '' || $hirekContent === '' || $hirekType === '' || $hirekDate === '') {
      $errorMessage = 'A hír felviteléhez minden mezőt ki kell tölteni.';
      $hirekFormMode = $formType === 'hirek_update' ? 'edit' : 'create';
      $showHirekForm = true;
    } elseif (!in_array($hirekType, $hirekTypeOptions, true)) {
      $errorMessage = 'Érvénytelen hír típus.';
      $hirekFormMode = $formType === 'hirek_update' ? 'edit' : 'create';
      $showHirekForm = true;
    } else {
      try {
        if ($formType === 'hirek_create') {
          $statement = $pdo->prepare('INSERT INTO hirek (cim, tartalom, hir_tipus, feltoltes_datuma, archivalt) VALUES (:cim, :tartalom, :hir_tipus, :feltoltes_datuma, :archivalt)');
          $statement->execute([
            'cim' => $hirekTitle,
            'tartalom' => $hirekContent,
            'hir_tipus' => $hirekType,
            'feltoltes_datuma' => $hirekDate,
            'archivalt' => 0,
          ]);

          $infoMessage = 'Hír felvitele sikeres.';
        } else {
          if ($hirekRecordId <= 0) {
            throw new RuntimeException('Érvénytelen rekordazonosító.');
          }

          $statement = $pdo->prepare('UPDATE hirek SET cim = :cim, tartalom = :tartalom, hir_tipus = :hir_tipus, feltoltes_datuma = :feltoltes_datuma WHERE id = :id');
          $statement->execute([
            'cim' => $hirekTitle,
            'tartalom' => $hirekContent,
            'hir_tipus' => $hirekType,
            'feltoltes_datuma' => $hirekDate,
            'id' => $hirekRecordId,
          ]);

          $infoMessage = 'Hír frissítése sikeres. A rekord mentve lett: #' . $hirekRecordId;
        }

        $showHirekForm = false;
        $hirekFormMode = 'create';
        $hirekRecordId = 0;
        $hirekTitleValue = '';
        $hirekContentValue = '';
        $hirekTypeValue = 'Hírek';
        $hirekDateValue = $currentDateValue;
      } catch (Throwable $exception) {
        $errorMessage = 'A hír mentése nem sikerült.';
        $hirekFormMode = $formType === 'hirek_update' ? 'edit' : 'create';
        $showHirekForm = true;
      }
    }
  }

  if ($formType === 'szakmai_create' || $formType === 'szakmai_update') {
    $szakmaiRecordId = (int) ($_POST['szakmai_id'] ?? 0);
    $szakmaiTitle = trim($_POST['szakmai_title'] ?? '');
    $szakmaiType = trim($_POST['szakmai_type'] ?? '');
    $szakmaiDescription = trim($_POST['szakmai_description'] ?? '');
    $szakmaiLink = trim($_POST['szakmai_link'] ?? '');

    if ($szakmaiTitle === '' || $szakmaiType === '' || $szakmaiDescription === '' || $szakmaiLink === '') {
      $errorMessage = 'A szakmai anyag felviteléhez minden mezőt ki kell tölteni.';
      $szakmaiFormMode = $formType === 'szakmai_update' ? 'edit' : 'create';
      $showSzakmaiForm = true;
    } elseif (!in_array($szakmaiType, $szakmaiTypeOptions, true)) {
      $errorMessage = 'Érvénytelen szakmai anyag típus.';
      $szakmaiFormMode = $formType === 'szakmai_update' ? 'edit' : 'create';
      $showSzakmaiForm = true;
    } else {
      try {
        if ($formType === 'szakmai_create') {
          $statement = $pdo->prepare('INSERT INTO szakmai_anyagok (cim, szakmai_tipus, leiras, link, archivalt) VALUES (:cim, :szakmai_tipus, :leiras, :link, :archivalt)');
          $statement->execute([
            'cim' => $szakmaiTitle,
            'szakmai_tipus' => $szakmaiType,
            'leiras' => $szakmaiDescription,
            'link' => $szakmaiLink,
            'archivalt' => 0,
          ]);
          $infoMessage = 'Szakmai anyag felvitele sikeres.';
        } else {
          if ($szakmaiRecordId <= 0) {
            throw new RuntimeException('Érvénytelen rekordazonosító.');
          }

          $statement = $pdo->prepare('UPDATE szakmai_anyagok SET cim = :cim, szakmai_tipus = :szakmai_tipus, leiras = :leiras, link = :link WHERE id = :id');
          $statement->execute([
            'cim' => $szakmaiTitle,
            'szakmai_tipus' => $szakmaiType,
            'leiras' => $szakmaiDescription,
            'link' => $szakmaiLink,
            'id' => $szakmaiRecordId,
          ]);
          $infoMessage = 'Szakmai anyag frissítése sikeres. A rekord mentve lett: #' . $szakmaiRecordId;
        }

        $showSzakmaiForm = false;
        $szakmaiFormMode = 'create';
        $szakmaiRecordId = 0;
        $szakmaiTitleValue = '';
        $szakmaiTypeValue = '';
        $szakmaiDescriptionValue = '';
        $szakmaiLinkValue = '';
      } catch (Throwable $exception) {
        $errorMessage = 'A szakmai anyag mentése nem sikerült.';
        $szakmaiFormMode = $formType === 'szakmai_update' ? 'edit' : 'create';
        $showSzakmaiForm = true;
      }
    }
  }

  if ($formType === 'naptar_create' || $formType === 'naptar_update') {
    $naptarRecordId = (int) ($_POST['naptar_id'] ?? 0);
    $naptarTitle = trim($_POST['naptar_title'] ?? '');
    $naptarType = trim($_POST['naptar_type'] ?? '');
    $naptarDescription = trim($_POST['naptar_description'] ?? '');
    $naptarDate = trim($_POST['naptar_date'] ?? '');
    $naptarImageName = basename(trim($_POST['naptar_existing_image'] ?? ''));
    $naptarUploadOk = isset($_FILES['naptar_image']) && is_array($_FILES['naptar_image']) && (int) ($_FILES['naptar_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK;

    if ($naptarUploadOk) {
      $uploadedName = (string) ($_FILES['naptar_image']['name'] ?? '');
      $naptarImageName = basename($uploadedName);
    }

    if ($naptarTitle === '' || $naptarType === '' || $naptarDescription === '' || $naptarDate === '' || $naptarImageName === '') {
      $errorMessage = 'A naptári esemény felviteléhez minden mezőt ki kell tölteni.';
      $naptarFormMode = $formType === 'naptar_update' ? 'edit' : 'create';
      $showNaptarForm = true;
    } elseif (!in_array($naptarType, $naptarTypeOptions, true)) {
      $errorMessage = 'Érvénytelen naptári esemény típus.';
      $naptarFormMode = $formType === 'naptar_update' ? 'edit' : 'create';
      $showNaptarForm = true;
    } else {
      try {
        $calendarUploadDirectory = __DIR__ . '/../../img/Calendar/';

        if ($naptarUploadOk) {
          if (!is_dir($calendarUploadDirectory)) {
            throw new RuntimeException('A naptári képek mappája nem érhető el.');
          }

          $temporaryFile = (string) ($_FILES['naptar_image']['tmp_name'] ?? '');
          if (!is_uploaded_file($temporaryFile) || !move_uploaded_file($temporaryFile, $calendarUploadDirectory . $naptarImageName)) {
            throw new RuntimeException('A naptári kép feltöltése nem sikerült.');
          }
        }

        $storedImagePath = '../../img/Calendar/' . $naptarImageName;

        if ($formType === 'naptar_create') {
          $statement = $pdo->prepare('INSERT INTO naptar (esemeny_tipus, cim, leiras, kep_utvonal, datum) VALUES (:esemeny_tipus, :cim, :leiras, :kep_utvonal, :datum)');
          $statement->execute([
            'esemeny_tipus' => $naptarType,
            'cim' => $naptarTitle,
            'leiras' => $naptarDescription,
            'kep_utvonal' => $storedImagePath,
            'datum' => $naptarDate,
          ]);
          $infoMessage = 'Naptári esemény felvitele sikeres.';
        } else {
          if ($naptarRecordId <= 0) {
            throw new RuntimeException('Érvénytelen rekordazonosító.');
          }

          $statement = $pdo->prepare('UPDATE naptar SET esemeny_tipus = :esemeny_tipus, cim = :cim, leiras = :leiras, kep_utvonal = :kep_utvonal, datum = :datum WHERE id = :id');
          $statement->execute([
            'esemeny_tipus' => $naptarType,
            'cim' => $naptarTitle,
            'leiras' => $naptarDescription,
            'kep_utvonal' => $storedImagePath,
            'datum' => $naptarDate,
            'id' => $naptarRecordId,
          ]);
          $infoMessage = 'Naptári esemény frissítése sikeres. A rekord mentve lett: #' . $naptarRecordId;
        }

        $showNaptarForm = false;
        $naptarFormMode = 'create';
        $naptarRecordId = 0;
        $naptarTitleValue = '';
        $naptarTypeValue = '';
        $naptarDescriptionValue = '';
        $naptarDateValue = '';
        $naptarImageValue = '';
      } catch (Throwable $exception) {
        $errorMessage = 'A naptári esemény mentése nem sikerült.';
        $naptarFormMode = $formType === 'naptar_update' ? 'edit' : 'create';
        $showNaptarForm = true;
      }
    }
  }

  if ($formType === 'dijazottak_create' || $formType === 'dijazottak_update') {
    $dijazottakRecordId = (int) ($_POST['dijazottak_id'] ?? 0);
    $dijazottakEv = trim($_POST['dijazottak_ev'] ?? '');
    $dijazottakCim = trim($_POST['dijazottak_cim'] ?? '');
    $dijazottakNev = trim($_POST['dijazottak_nev'] ?? '');
    $dijazottakTipus = trim($_POST['dijazottak_tipus'] ?? '');
    $dijazottakImageName = basename(trim($_POST['dijazottak_existing_image'] ?? ''));
    $dijazottakUploadOk = isset($_FILES['dijazottak_image']) && is_array($_FILES['dijazottak_image']) && (int) ($_FILES['dijazottak_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK;

    if ($dijazottakUploadOk) {
      $uploadedName = (string) ($_FILES['dijazottak_image']['name'] ?? '');
      $dijazottakImageName = basename($uploadedName);
    }

    $dijazottakEvValue = $dijazottakEv;
    $dijazottakCimValue = $dijazottakCim;
    $dijazottakNevValue = $dijazottakNev;
    $dijazottakTipusValue = $dijazottakTipus !== '' ? $dijazottakTipus : '';
    $dijazottakImageValue = $dijazottakImageName;

    if ($dijazottakEv === '' || $dijazottakCim === '' || $dijazottakNev === '' || $dijazottakTipus === '' || ($formType === 'dijazottak_create' && $dijazottakImageName === '')) {
      $errorMessage = 'A díjazott felviteléhez minden mezőt ki kell tölteni.';
      $dijazottakFormMode = $formType === 'dijazottak_update' ? 'edit' : 'create';
      $showDijazottakForm = true;
    } elseif (!in_array($dijazottakTipus, $dijazottakTypeOptions, true)) {
      $errorMessage = 'Érvénytelen díj típus.';
      $dijazottakFormMode = $formType === 'dijazottak_update' ? 'edit' : 'create';
      $showDijazottakForm = true;
    } elseif (!ctype_digit($dijazottakEv)) {
      $errorMessage = 'A díjazott év csak szám lehet.';
      $dijazottakFormMode = $formType === 'dijazottak_update' ? 'edit' : 'create';
      $showDijazottakForm = true;
    } else {
      try {
        $awardUploadDirectory = __DIR__ . '/../../img/site/dijazottak/';

        if ($dijazottakUploadOk) {
          if (!is_dir($awardUploadDirectory)) {
            mkdir($awardUploadDirectory, 0777, true);
          }

          $temporaryFile = (string) ($_FILES['dijazottak_image']['tmp_name'] ?? '');
          if (!is_uploaded_file($temporaryFile) || !move_uploaded_file($temporaryFile, $awardUploadDirectory . $dijazottakImageName)) {
            throw new RuntimeException('A díjazott kép feltöltése nem sikerült.');
          }
        }

        $storedImagePath = '../../img/site/dijazottak/' . $dijazottakImageName;

        if ($formType === 'dijazottak_create') {
          $statement = $pdo->prepare('INSERT INTO dijazottak (ev, cim, nev, kep_utvonal, dij_tipus) VALUES (:ev, :cim, :nev, :kep_utvonal, :dij_tipus)');
          $statement->execute([
            'ev' => (int) $dijazottakEv,
            'cim' => $dijazottakCim,
            'nev' => $dijazottakNev,
            'kep_utvonal' => $storedImagePath,
            'dij_tipus' => $dijazottakTipus,
          ]);
          $infoMessage = 'Díjazott felvitele sikeres.';
        } else {
          if ($dijazottakRecordId <= 0) {
            throw new RuntimeException('Érvénytelen rekordazonosító.');
          }

          $statement = $pdo->prepare('UPDATE dijazottak SET ev = :ev, cim = :cim, nev = :nev, kep_utvonal = :kep_utvonal, dij_tipus = :dij_tipus WHERE id = :id');
          $statement->execute([
            'ev' => (int) $dijazottakEv,
            'cim' => $dijazottakCim,
            'nev' => $dijazottakNev,
            'kep_utvonal' => $storedImagePath,
            'dij_tipus' => $dijazottakTipus,
            'id' => $dijazottakRecordId,
          ]);
          $infoMessage = 'Díjazott frissítése sikeres. A rekord mentve lett: #' . $dijazottakRecordId;
        }

        $showDijazottakForm = false;
        $dijazottakFormMode = 'create';
        $dijazottakRecordId = 0;
        $dijazottakEvValue = $currentYearValue;
        $dijazottakCimValue = '';
        $dijazottakNevValue = '';
        $dijazottakTipusValue = '';
        $dijazottakImageValue = '';
      } catch (Throwable $exception) {
        $errorMessage = 'A díjazott mentése nem sikerült.';
        $dijazottakFormMode = $formType === 'dijazottak_update' ? 'edit' : 'create';
        $showDijazottakForm = true;
      }
    }
  }

  if ($formType === 'document_update') {
    $documentRecordId = (int) ($_POST['document_id'] ?? 0);
    $documentTitle = trim($_POST['document_title'] ?? '');
    $documentType = trim($_POST['document_type'] ?? '');
    $documentFileName = basename(trim($_POST['document_existing_file'] ?? ''));

    if (isset($_FILES['document_file']) && is_array($_FILES['document_file']) && (int) ($_FILES['document_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
      $uploadedName = (string) ($_FILES['document_file']['name'] ?? '');
      $documentFileName = basename($uploadedName);
    }

    $allowedTypes = [
      'tagozati beszamolo',
      'szabalyzat',
      'hatarozat',
      'dokumentumok(egyeb)',
    ];

    if ($documentRecordId <= 0 || $documentTitle === '' || $documentType === '' || $documentFileName === '') {
      $errorMessage = 'Minden dokumentummezot ki kell tolteni a szerkeszteshez.';
      $documentFormMode = 'edit';
      $showDocumentForm = true;
    } elseif (!in_array($documentType, $allowedTypes, true)) {
      $errorMessage = 'Ervenytelen dokumentum tipus.';
      $documentFormMode = 'edit';
      $showDocumentForm = true;
    } else {
      try {
        $statement = $pdo->prepare('UPDATE dokumentumok SET cim = :cim, dokumentum_tipus = :dokumentum_tipus, dokumentum_utvonal = :dokumentum_utvonal WHERE id = :id');
        $statement->execute([
          'cim' => $documentTitle,
          'dokumentum_tipus' => $documentType,
          'dokumentum_utvonal' => '../../docs/dokumentumok/' . $documentFileName,
          'id' => $documentRecordId,
        ]);

        $infoMessage = 'Dokumentum frissítése sikeres. A rekord mentve lett: #' . $documentRecordId;
        $showDocumentForm = false;
        $documentFormMode = 'create';
        $documentRecordId = 0;
        $documentTitleValue = '';
        $documentTypeValue = '';
        $documentFileValue = '';
      } catch (Throwable $exception) {
        $errorMessage = 'A dokumentum frissítese nem sikerult.';
        $documentFormMode = 'edit';
        $showDocumentForm = true;
      }
    }
  }

  $hasOpenForm = $showDocumentForm || $showHirekForm || $showSzakmaiForm || $showNaptarForm || $showDijazottakForm;
  $isEditAction = $rowAction === 'edit';

  if (!$hasOpenForm && !$isEditAction) {
    if ($errorMessage !== '' || $infoMessage !== '') {
      $_SESSION['admin_flash'] = [
        'type' => $errorMessage !== '' ? 'error' : 'info',
        'message' => $errorMessage !== '' ? $errorMessage : $infoMessage,
        'target' => $messageTargetTable,
      ];
    }

    $redirectParams = $_GET;
    unset($redirectParams['new']);

    $redirectUrl = 'admin-felulet.php';
    if (!empty($redirectParams)) {
      $redirectUrl .= '?' . http_build_query($redirectParams);
    }
    if ($messageTargetTable !== '') {
      $redirectUrl .= '#admin-section-' . rawurlencode($messageTargetTable);
    }

    header('Location: ' . $redirectUrl);
    exit;
  }
}

$sections = [
  [
    'table' => 'dokumentumok',
    'title' => 'Dokumentumok',
    'order_by' => 'id',
    'columns' => [
      'id' => 'ID',
      'cim' => 'Cím',
      'dokumentum_tipus' => 'Típus',
      'dokumentum_utvonal' => 'Út',
      'archivalt' => 'Archivált',
    ],
  ],
  [
    'table' => 'hirek',
    'title' => 'Hírek',
    'order_by' => 'feltoltes_datuma',
    'columns' => [
      'id' => 'ID',
      'cim' => 'Cím',
      'hir_tipus' => 'Típus',
      'feltoltes_datuma' => 'Feltöltés dátuma',
      'archivalt' => 'Archivált',
    ],
  ],
  [
    'table' => 'szakmai_anyagok',
    'title' => 'Szakmai anyagok',
    'order_by' => 'id',
    'columns' => [
      'id' => 'ID',
      'cim' => 'Cím',
      'link' => 'Link',
      'szakmai_tipus' => 'Típus',
      'archivalt' => 'Archivált',
    ],
  ],
  [
    'table' => 'naptar',
    'title' => 'Naptár',
    'order_by' => 'datum',
    'columns' => [
      'id' => 'ID',
      'esemeny_tipus' => 'Esemény típus',
      'cim' => 'Cím',
      'datum' => 'Dátum',
    ],
  ],
  [
    'table' => 'dijazottak',
    'title' => 'Díjazottak',
    'order_by' => 'ev',
    'columns' => [
      'id' => 'ID',
      'ev' => 'Év',
      'cim' => 'Díjazott tevékenység',
      'nev' => 'Díjazott',
      'dij_tipus' => 'Díj típus',
    ],
  ],
];

$recordsByTable = [];
$paginationByTable = [];
$rowsPerPage = 5;

if ($pdo instanceof PDO) {
  foreach ($sections as $section) {
    $table = $section['table'];
    $orderBy = $section['order_by'];
    $pageParam = 'page_' . $table;
    $currentPage = max(1, (int) ($_GET[$pageParam] ?? 1));

    $countStatement = $pdo->query('SELECT COUNT(*) AS total FROM ' . $table);
    $totalRows = (int) ($countStatement->fetch()['total'] ?? 0);
    $totalPages = max(1, (int) ceil($totalRows / $rowsPerPage));
    if ($currentPage > $totalPages) {
      $currentPage = $totalPages;
    }

    $offset = ($currentPage - 1) * $rowsPerPage;
    $statement = $pdo->query(
      'SELECT * FROM ' . $table .
      ' ORDER BY ' . $orderBy .
      ' DESC LIMIT ' . (int) $rowsPerPage .
      ' OFFSET ' . (int) $offset
    );
    $recordsByTable[$table] = $statement->fetchAll();
    $paginationByTable[$table] = [
      'param' => $pageParam,
      'current_page' => $currentPage,
      'total_pages' => $totalPages,
      'total_rows' => $totalRows,
    ];
  }
}
?>

<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Admin felület</title>
  <meta name="description" content="Admin felület az utolsó rekordok listázásához és műveleteihez.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="admin-panel-title">
        <div class="news-panel admin-panel">
          <div class="news-header admin-panel-header">
            <h1 id="admin-panel-title">Admin felület</h1>
            <p>Bejelentkezve mint: <?php echo htmlspecialchars((string) ($_SESSION['admin_username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>

          <?php if ($messageTargetTable === '' && $errorMessage !== ''): ?>
            <p class="pill" style="display:inline-flex; margin-bottom: 1rem; background: rgba(139, 63, 87, 0.14); color: #8b3f57;"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>

          <?php if ($messageTargetTable === '' && $infoMessage !== ''): ?>
            <p class="pill" style="display:inline-flex; margin-bottom: 1rem; background: rgba(53, 100, 143, 0.14); color: #35648f;"><?php echo htmlspecialchars($infoMessage, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>

          <?php foreach ($sections as $section): ?>
            <?php
              $table = $section['table'];
              $rows = $recordsByTable[$table] ?? [];
              $pagination = $paginationByTable[$table] ?? [
                'param' => 'page_' . $table,
                'current_page' => 1,
                'total_pages' => 1,
                'total_rows' => count($rows),
              ];
            ?>
            <section class="admin-block" aria-labelledby="admin-section-<?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?>">
              <div class="admin-block-head">
                <h2 id="admin-section-<?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <a class="admin-login-button" href="admin-felulet.php?new=<?php echo urlencode($table); ?>#admin-section-<?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?>">Új felvitele</a>
              </div>

              <?php if ($messageTargetTable === $table && $errorMessage !== ''): ?>
                <p class="pill" style="display:inline-flex; margin-bottom: 0.5rem; background: rgba(139, 63, 87, 0.14); color: #8b3f57;"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>

              <?php if ($messageTargetTable === $table && $infoMessage !== ''): ?>
                <p class="pill" style="display:inline-flex; margin-bottom: 0.5rem; background: rgba(53, 100, 143, 0.14); color: #35648f;"><?php echo htmlspecialchars($infoMessage, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>

              <?php if ($table === 'dokumentumok' && $showDocumentForm): ?>
                <form method="post" enctype="multipart/form-data" class="botka-intro admin-create-form">
                  <input type="hidden" name="form_type" value="<?php echo $documentFormMode === 'edit' ? 'document_update' : 'document_create'; ?>">
                  <input type="hidden" name="document_id" value="<?php echo (int) $documentRecordId; ?>">
                  <div class="field">
                    <label for="document_title">Cím</label><br>
                    <input id="document_title" name="document_title" type="text" value="<?php echo htmlspecialchars($documentTitleValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>
                  <div class="field">
                    <label for="document_type">Típus</label><br>
                    <select id="document_type" name="document_type" required>
                      <option value="">Válassz típust</option>
                      <option value="tagozati beszamolo" <?php echo $documentTypeValue === 'tagozati beszamolo' ? 'selected' : ''; ?>>tagozati beszámoló</option>
                      <option value="szabalyzat" <?php echo $documentTypeValue === 'szabalyzat' ? 'selected' : ''; ?>>szabályzat</option>
                      <option value="hatarozat" <?php echo $documentTypeValue === 'hatarozat' ? 'selected' : ''; ?>>határozat</option>
                      <option value="dokumentumok(egyeb)" <?php echo $documentTypeValue === 'dokumentumok(egyeb)' ? 'selected' : ''; ?>>dokumentumok(egyéb)</option>
                    </select>
                  </div>
                  <div class="field">
                    <label for="document_file">Fájl</label><br>
                    <input id="document_file" name="document_file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png" <?php echo $documentFormMode === 'create' ? 'required' : ''; ?>>
                    <input type="hidden" name="document_existing_file" value="<?php echo htmlspecialchars($documentFileValue, ENT_QUOTES, 'UTF-8'); ?>">
                    <small>Csak a fájl neve kerül mentésre, a rendszer az előre megadott <strong>../../docs/dokumentumok/</strong> mappából éri el.</small>
                  </div>
                  <div>
                    <button class="admin-login-button" type="submit"><?php echo $documentFormMode === 'edit' ? 'Frissítés' : 'Mentés'; ?></button>
                  </div>
                </form>
              <?php endif; ?>

              <?php if ($table === 'hirek' && $showHirekForm): ?>
                <form method="post" enctype="multipart/form-data" class="botka-intro admin-create-form admin-news-form">
                  <input type="hidden" name="form_type" value="<?php echo $hirekFormMode === 'edit' ? 'hirek_update' : 'hirek_create'; ?>">
                  <input type="hidden" name="hirek_id" value="<?php echo (int) $hirekRecordId; ?>">
                  <div class="meta-grid">
                    <div class="field">
                      <label for="hirek_title">Cím</label>
                      <input id="hirek_title" name="hirek_title" type="text" maxlength="50" value="<?php echo htmlspecialchars($hirekTitleValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="field">
                      <label for="hirek_type">Típus</label>
                      <select id="hirek_type" name="hirek_type" required>
                        <option value="">Válassz típust</option>
                        <option value="Beszámolók" <?php echo $hirekTypeValue === 'Beszámolók' ? 'selected' : ''; ?>>Beszámolók</option>
                        <option value="Botka Imre-díj átadó" <?php echo $hirekTypeValue === 'Botka Imre-díj átadó' ? 'selected' : ''; ?>>Botka Imre-díj átadó</option>
                        <option value="Magyar József-díj átadó" <?php echo $hirekTypeValue === 'Magyar József-díj átadó' ? 'selected' : ''; ?>>Magyar József-díj átadó</option>
                        <option value="Hírek" <?php echo $hirekTypeValue === 'Hírek' ? 'selected' : ''; ?>>Hírek</option>
                      </select>
                    </div>
                  </div>
                  <div class="field">
                    <label for="hirek_date">Feltöltés dátuma</label>
                    <input id="hirek_date" name="hirek_date" type="date" value="<?php echo htmlspecialchars($hirekDateValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>
                  <div class="field">
                    <label for="hirek_content">Tartalom</label>
                    <textarea id="hirek_content" name="hirek_content"><?php echo htmlspecialchars($hirekContentValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
                  <div>
                    <button class="admin-login-button" type="submit"><?php echo $hirekFormMode === 'edit' ? 'Frissítés' : 'Mentés'; ?></button>
                  </div>
                </form>
              <?php endif; ?>

              <?php if ($table === 'szakmai_anyagok' && $showSzakmaiForm): ?>
                <form method="post" class="botka-intro admin-create-form">
                  <input type="hidden" name="form_type" value="<?php echo $szakmaiFormMode === 'edit' ? 'szakmai_update' : 'szakmai_create'; ?>">
                  <input type="hidden" name="szakmai_id" value="<?php echo (int) $szakmaiRecordId; ?>">
                  <div class="meta-grid">
                    <div class="field">
                      <label for="szakmai_title">Cím</label>
                      <input id="szakmai_title" name="szakmai_title" type="text" maxlength="50" value="<?php echo htmlspecialchars($szakmaiTitleValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="field">
                      <label for="szakmai_type">Típus</label>
                      <select id="szakmai_type" name="szakmai_type" required>
                        <option value="">Válassz típust</option>
                        <option value="Kiadvány" <?php echo $szakmaiTypeValue === 'Kiadvány' ? 'selected' : ''; ?>>Kiadvány</option>
                        <option value="Cikk" <?php echo $szakmaiTypeValue === 'Cikk' ? 'selected' : ''; ?>>Cikk</option>
                        <option value="Előadás" <?php echo $szakmaiTypeValue === 'Előadás' ? 'selected' : ''; ?>>Előadás</option>
                      </select>
                    </div>
                  </div>
                  <div class="field">
                    <label for="szakmai_description">Leírás</label>
                    <textarea id="szakmai_description" name="szakmai_description" required><?php echo htmlspecialchars($szakmaiDescriptionValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
                  <div class="field">
                    <label for="szakmai_link">Link</label>
                    <input id="szakmai_link" name="szakmai_link" type="text" value="<?php echo htmlspecialchars($szakmaiLinkValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>
                  <div>
                    <button class="admin-login-button" type="submit"><?php echo $szakmaiFormMode === 'edit' ? 'Frissítés' : 'Mentés'; ?></button>
                  </div>
                </form>
              <?php endif; ?>

              <?php if ($table === 'naptar' && $showNaptarForm): ?>
                <form method="post" enctype="multipart/form-data" class="botka-intro admin-create-form">
                  <input type="hidden" name="form_type" value="<?php echo $naptarFormMode === 'edit' ? 'naptar_update' : 'naptar_create'; ?>">
                  <input type="hidden" name="naptar_id" value="<?php echo (int) $naptarRecordId; ?>">
                  <div class="meta-grid">
                    <div class="field">
                      <label for="naptar_title">Cím</label>
                      <input id="naptar_title" name="naptar_title" type="text" maxlength="50" value="<?php echo htmlspecialchars($naptarTitleValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="field">
                      <label for="naptar_type">Esemény típus</label>
                      <select id="naptar_type" name="naptar_type" required>
                        <option value="">Válassz típust</option>
                        <option value="Megemlékezés" <?php echo $naptarTypeValue === 'Megemlékezés' ? 'selected' : ''; ?>>Megemlékezés</option>
                        <option value="Továbbképzés" <?php echo $naptarTypeValue === 'Továbbképzés' ? 'selected' : ''; ?>>Továbbképzés</option>
                        <option value="Évforduló" <?php echo $naptarTypeValue === 'Évforduló' ? 'selected' : ''; ?>>Évforduló</option>
                        <option value="Konferencia" <?php echo $naptarTypeValue === 'Konferencia' ? 'selected' : ''; ?>>Konferencia</option>
                      </select>
                    </div>
                  </div>
                  <div class="field">
                    <label for="naptar_description">Leírás</label>
                    <textarea id="naptar_description" name="naptar_description" required><?php echo htmlspecialchars($naptarDescriptionValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
                  <div class="meta-grid">
                    <div class="field">
                      <label for="naptar_date">Dátum</label>
                      <input id="naptar_date" name="naptar_date" type="date" value="<?php echo htmlspecialchars($naptarDateValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="field">
                      <label for="naptar_image">Kép</label>
                      <input id="naptar_image" name="naptar_image" type="file" accept=".jpg,.jpeg,.png,.webp" <?php echo $naptarFormMode === 'create' ? 'required' : ''; ?>>
                      <input type="hidden" name="naptar_existing_image" value="<?php echo htmlspecialchars($naptarImageValue, ENT_QUOTES, 'UTF-8'); ?>">
                      <small>Jelenlegi fájl: <?php echo htmlspecialchars($naptarImageValue !== '' ? $naptarImageValue : 'nincs megadva', ENT_QUOTES, 'UTF-8'); ?></small>
                    </div>
                  </div>
                  <div>
                    <button class="admin-login-button" type="submit"><?php echo $naptarFormMode === 'edit' ? 'Frissítés' : 'Mentés'; ?></button>
                  </div>
                </form>
              <?php endif; ?>

              <?php if ($table === 'dijazottak' && $showDijazottakForm): ?>
                <form method="post" enctype="multipart/form-data" class="botka-intro admin-create-form">
                  <input type="hidden" name="form_type" value="<?php echo $dijazottakFormMode === 'edit' ? 'dijazottak_update' : 'dijazottak_create'; ?>">
                  <input type="hidden" name="dijazottak_id" value="<?php echo (int) $dijazottakRecordId; ?>">
                  <div class="meta-grid">
                    <div class="field">
                      <label for="dijazottak_ev">Év</label>
                      <input id="dijazottak_ev" name="dijazottak_ev" type="number" min="1900" max="2100" value="<?php echo htmlspecialchars($dijazottakEvValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="field">
                      <label for="dijazottak_tipus">Díj típus</label>
                      <select id="dijazottak_tipus" name="dijazottak_tipus" required>
                        <option value="">Válassz típust</option>
                        <option value="Botka Imre-díj" <?php echo $dijazottakTipusValue === 'Botka Imre-díj' ? 'selected' : ''; ?>>Botka Imre-díj</option>
                        <option value="Magyar József-díj" <?php echo $dijazottakTipusValue === 'Magyar József-díj' ? 'selected' : ''; ?>>Magyar József-díj</option>
                      </select>
                    </div>
                  </div>
                  <div class="field">
                    <label for="dijazottak_nev">Díjazott neve</label>
                    <input id="dijazottak_nev" name="dijazottak_nev" type="text" maxlength="50" value="<?php echo htmlspecialchars($dijazottakNevValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>
                  <div class="field">
                    <label for="dijazottak_cim">Tevékenység</label>
                    <textarea id="dijazottak_cim" name="dijazottak_cim" required><?php echo htmlspecialchars($dijazottakCimValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
                  <div class="field">
                    <label for="dijazottak_image">Kép</label>
                    <input id="dijazottak_image" name="dijazottak_image" type="file" accept=".jpg,.jpeg,.png,.webp" <?php echo $dijazottakFormMode === 'create' ? 'required' : ''; ?>>
                    <input type="hidden" name="dijazottak_existing_image" value="<?php echo htmlspecialchars($dijazottakImageValue, ENT_QUOTES, 'UTF-8'); ?>">
                    <small>Jelenlegi fájl: <?php echo htmlspecialchars($dijazottakImageValue !== '' ? $dijazottakImageValue : 'nincs megadva', ENT_QUOTES, 'UTF-8'); ?></small>
                  </div>
                  <div>
                    <button class="admin-login-button" type="submit"><?php echo $dijazottakFormMode === 'edit' ? 'Frissítés' : 'Mentés'; ?></button>
                  </div>
                </form>
              <?php endif; ?>

              <div class="table-wrap" role="region" aria-label="<?php echo htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8'); ?> utolso rekordjai" tabindex="0">
                <table class="award-table admin-table">
                  <thead>
                    <tr>
                      <?php foreach ($section['columns'] as $label): ?>
                        <th scope="col"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></th>
                      <?php endforeach; ?>
                      <th scope="col">Műveletek</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($rows)): ?>
                      <tr>
                        <td colspan="<?php echo count($section['columns']) + 1; ?>">Nincs megjelenithető rekord ebben a táblában.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($rows as $row): ?>
                        <tr>
                          <?php foreach (array_keys($section['columns']) as $column): ?>
                            <td>
                              <?php if ($column === 'archivalt'): ?>
                                <?php echo ((int) ($row[$column] ?? 0) === 1) ? 'Igen' : 'Nem'; ?>
                              <?php else: ?>
                                <?php echo htmlspecialchars((string) ($row[$column] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                              <?php endif; ?>
                            </td>
                          <?php endforeach; ?>
                          <td>
                            <form method="post" class="admin-row-actions">
                              <input type="hidden" name="row_table" value="<?php echo htmlspecialchars($table, ENT_QUOTES, 'UTF-8'); ?>">
                              <input type="hidden" name="row_id" value="<?php echo (int) ($row['id'] ?? 0); ?>">
                              <button class="admin-action-button" type="submit" name="row_action" value="edit">Szerkesztés</button>
                              <button class="admin-action-button admin-action-button--danger" type="submit" name="row_action" value="delete" onclick="return confirm('Biztosan törlöd ezt a rekordot?');">Törlés</button>
                              <?php if (in_array($table, $archivableTables, true)): ?>
                                <button class="admin-action-button admin-action-button--alt" type="submit" name="row_action" value="archive">Archiválás</button>
                              <?php endif; ?>
                            </form>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <?php if ((int) $pagination['total_pages'] > 1): ?>
                <?php
                  $pageParam = (string) $pagination['param'];
                  $currentPage = (int) $pagination['current_page'];
                  $totalPages = (int) $pagination['total_pages'];
                  $baseParams = $_GET;
                  unset($baseParams[$pageParam]);
                  unset($baseParams['new']);

                  $prevParams = $baseParams;
                  $prevParams[$pageParam] = max(1, $currentPage - 1);
                  $nextParams = $baseParams;
                  $nextParams[$pageParam] = min($totalPages, $currentPage + 1);

                  $prevUrl = 'admin-felulet.php?' . http_build_query($prevParams) . '#admin-section-' . rawurlencode($table);
                  $nextUrl = 'admin-felulet.php?' . http_build_query($nextParams) . '#admin-section-' . rawurlencode($table);
                ?>
                <div class="botka-intro" style="margin-top: 0.75rem; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                  <?php if ($currentPage > 1): ?>
                    <a class="admin-action-button admin-action-button--alt" href="<?php echo htmlspecialchars($prevUrl, ENT_QUOTES, 'UTF-8'); ?>">Előző oldal</a>
                  <?php else: ?>
                    <span class="admin-action-button admin-action-button--alt" style="opacity: 0.55; pointer-events: none;">Előző oldal</span>
                  <?php endif; ?>

                  <span>
                    Oldal: <?php echo (int) $currentPage; ?> / <?php echo (int) $totalPages; ?>
                    (összesen: <?php echo (int) $pagination['total_rows']; ?> rekord)
                  </span>

                  <?php if ($currentPage < $totalPages): ?>
                    <a class="admin-action-button admin-action-button--alt" href="<?php echo htmlspecialchars($nextUrl, ENT_QUOTES, 'UTF-8'); ?>">Következő oldal</a>
                  <?php else: ?>
                    <span class="admin-action-button admin-action-button--alt" style="opacity: 0.55; pointer-events: none;">Következő oldal</span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </section>
          <?php endforeach; ?>
        </div>
      </section>

      <?php include __DIR__ . '/../includes/sidebar-right.php'; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.5/tinymce.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const newsForm = document.querySelector('.admin-news-form');

      if (newsForm) {
        newsForm.addEventListener('submit', function () {
          if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
          }
        });
      }

      if (typeof tinymce === 'undefined' || !document.querySelector('#hirek_content')) {
        return;
      }

      tinymce.init({
        selector: '#hirek_content',
        menubar: 'file edit view insert format tools table help',
        branding: false,
        promotion: false,
        height: 360,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code preview',
        automatic_uploads: true,
        images_upload_url: 'admin-upload-image.php',
        images_upload_credentials: true,
        file_picker_types: 'image',
        block_formats: 'Bekezdés=p; Címsor 2=h2; Címsor 3=h3; Címsor 4=h4',
        content_style: 'body { font-family: Segoe UI, Arial, sans-serif; font-size: 16px; line-height: 1.6; } img { max-width: 100%; height: auto; }'
      });
    });
  </script>

  <?php include __DIR__ . '/../includes/calendar-script.php'; ?>
</body>
</html>