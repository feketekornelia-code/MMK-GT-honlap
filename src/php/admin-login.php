<?php
require_once __DIR__ . '/admin-session.php';

if (!empty($_SESSION['admin_id'])) {
  header('Location: admin-felulet.php');
  exit;
}

$loginError = '';
$loginSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $password === '') {
    $loginError = 'Add meg a felhasználónevet és a jelszót.';
  } else {
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

      $statement = $pdo->prepare('SELECT id, felhasznalonev, jelszo_hash FROM szerkeszto WHERE felhasznalonev = :felhasznalonev AND admin = 1 LIMIT 1');
      $statement->execute(['felhasznalonev' => $username]);
      $admin = $statement->fetch();

      if ($admin && password_verify($password, $admin['jelszo_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_username'] = $admin['felhasznalonev'];
        mmkgt_admin_set_auth_cookie((int) $admin['id'], (string) $admin['felhasznalonev']);
        header('Location: admin-felulet.php');
        exit;
      } else {
        $loginError = 'Hibás felhasználónév vagy jelszó.';
      }
    } catch (Throwable $exception) {
      $loginError = 'Az admin bejelentkezéshez szükséges adatbázis-kapcsolat vagy tábla még nincs előkészítve.';
    }
  }
}
?>

<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMK-GT - Admin bejelentkezés</title>
  <meta name="description" content="Adminisztrátori bejelentkezés az MMK-GT szerkesztői felületéhez.">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

  <a class="skip-link" href="#main">Ugrás a fő tartalomhoz</a>

  <div class="page-shell">
    <main id="main" class="layout" tabindex="-1">
      <?php include __DIR__ . '/../includes/sidebar-left.php'; ?>

      <section class="main" aria-labelledby="admin-login-title">
        <div class="news-panel">
          <div class="news-header">
            <h1 id="admin-login-title">Adminisztrátor bejelentkezés</h1>
          </div>

          <?php if ($loginError !== ''): ?>
            <p class="pill" style="display:inline-flex; margin-bottom: 1rem; background: rgba(139, 63, 87, 0.14); color: #8b3f57;"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>

          <form method="post" class="botka-intro" style="gap: 1rem;">
            <div>
              <label for="username">Felhasználónév</label><br>
              <input id="username" name="username" type="text" autocomplete="username" required style="width:100%; max-width: 28rem; padding: 0.75rem 0.9rem; border-radius: 0.75rem; border: 1px solid rgba(107, 128, 151, 0.22); font: inherit;">
            </div>
            <div>
              <label for="password">Jelszó</label><br>
              <input id="password" name="password" type="password" autocomplete="current-password" required style="width:100%; max-width: 28rem; padding: 0.75rem 0.9rem; border-radius: 0.75rem; border: 1px solid rgba(107, 128, 151, 0.22); font: inherit;">
            </div>
            <div>
              <button class="admin-login-button" type="submit">Belépés</button>
            </div>
          </form>
        </div>
      </section>

      <?php include __DIR__ . '/../includes/sidebar-right.php'; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../includes/calendar-script.php'; ?>
</body>
</html>