<?php
require_once __DIR__ . '/admin-session.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_id'])) {
  http_response_code(403);
  echo json_encode(['error' => ['message' => 'Nincs jogosultság a képfeltöltéshez.']]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => ['message' => 'Nem támogatott kérés.']]);
  exit;
}

if (!isset($_FILES['file']) || !is_array($_FILES['file'])) {
  http_response_code(400);
  echo json_encode(['error' => ['message' => 'Nem érkezett feltöltendő fájl.']]);
  exit;
}

$upload = $_FILES['file'];
$errorCode = (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE);
if ($errorCode !== UPLOAD_ERR_OK) {
  http_response_code(400);
  echo json_encode(['error' => ['message' => 'A képfeltöltés nem sikerült.']]);
  exit;
}

$tmpName = (string) ($upload['tmp_name'] ?? '');
if ($tmpName === '' || !is_uploaded_file($tmpName)) {
  http_response_code(400);
  echo json_encode(['error' => ['message' => 'Érvénytelen feltöltött fájl.']]);
  exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = (string) $finfo->file($tmpName);
$allowedMime = [
  'image/jpeg' => 'jpg',
  'image/png' => 'png',
  'image/webp' => 'webp',
  'image/gif' => 'gif',
];

if (!isset($allowedMime[$mimeType])) {
  http_response_code(415);
  echo json_encode(['error' => ['message' => 'Csak JPG, PNG, WEBP vagy GIF kép tölthető fel.']]);
  exit;
}

$maxBytes = 8 * 1024 * 1024;
$fileSize = (int) ($upload['size'] ?? 0);
if ($fileSize <= 0 || $fileSize > $maxBytes) {
  http_response_code(413);
  echo json_encode(['error' => ['message' => 'A képfájl mérete legfeljebb 8 MB lehet.']]);
  exit;
}

$uploadDir = __DIR__ . '/../../img/News/editor/';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
  http_response_code(500);
  echo json_encode(['error' => ['message' => 'A képfeltöltési könyvtár nem hozható létre.']]);
  exit;
}

if (!is_writable($uploadDir)) {
  http_response_code(500);
  echo json_encode(['error' => ['message' => 'A képfeltöltési könyvtár nem írható.']]);
  exit;
}

$extension = $allowedMime[$mimeType];
$storedName = 'news-editor-' . date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $extension;
$targetPath = $uploadDir . $storedName;

if (!move_uploaded_file($tmpName, $targetPath)) {
  http_response_code(500);
  echo json_encode(['error' => ['message' => 'A feltöltött kép mentése sikertelen.']]);
  exit;
}

$publicPath = '../../img/News/editor/' . $storedName;
echo json_encode(['location' => $publicPath]);
