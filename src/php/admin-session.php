<?php
const MMKGT_ADMIN_AUTH_COOKIE = 'MMKGT_ADMIN_AUTH';
const MMKGT_ADMIN_AUTH_TTL = 28800;

function mmkgt_admin_cookie_path(): string
{
  static $path = null;
  if ($path !== null) {
    return $path;
  }

  $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
  $scriptDir = str_replace('\\', '/', dirname($scriptName));
  if ($scriptDir === '' || $scriptDir === '.' || $scriptDir === '\\') {
    $scriptDir = '/';
  }

  $path = rtrim($scriptDir, '/');
  $path = $path === '' ? '/' : $path . '/';
  return $path;
}

function mmkgt_admin_cookie_secure(): bool
{
  return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
}

function mmkgt_admin_auth_secret(): string
{
  return 'mmkgt-admin-auth-v1';
}

function mmkgt_admin_sign_token(int $adminId, string $adminUsername, int $expiresAt): string
{
  return hash_hmac('sha256', $adminId . '|' . $adminUsername . '|' . $expiresAt, mmkgt_admin_auth_secret());
}

function mmkgt_admin_clear_auth_cookie(): void
{
  $params = [
    'expires' => time() - 3600,
    'path' => mmkgt_admin_cookie_path(),
    'secure' => mmkgt_admin_cookie_secure(),
    'httponly' => true,
    'samesite' => 'Lax',
  ];
  setcookie(MMKGT_ADMIN_AUTH_COOKIE, '', $params);
}

function mmkgt_admin_set_auth_cookie(int $adminId, string $adminUsername): void
{
  $expiresAt = time() + MMKGT_ADMIN_AUTH_TTL;
  $payload = [
    'id' => $adminId,
    'u' => $adminUsername,
    'exp' => $expiresAt,
    'sig' => mmkgt_admin_sign_token($adminId, $adminUsername, $expiresAt),
  ];

  $encoded = base64_encode(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
  $params = [
    'expires' => $expiresAt,
    'path' => mmkgt_admin_cookie_path(),
    'secure' => mmkgt_admin_cookie_secure(),
    'httponly' => true,
    'samesite' => 'Lax',
  ];

  setcookie(MMKGT_ADMIN_AUTH_COOKIE, $encoded, $params);
}

function mmkgt_admin_restore_auth_cookie(): void
{
  if (!empty($_SESSION['admin_id'])) {
    return;
  }

  $raw = (string) ($_COOKIE[MMKGT_ADMIN_AUTH_COOKIE] ?? '');
  if ($raw === '') {
    return;
  }

  $decoded = json_decode((string) base64_decode($raw, true), true);
  if (!is_array($decoded)) {
    mmkgt_admin_clear_auth_cookie();
    return;
  }

  $adminId = (int) ($decoded['id'] ?? 0);
  $adminUsername = (string) ($decoded['u'] ?? '');
  $expiresAt = (int) ($decoded['exp'] ?? 0);
  $signature = (string) ($decoded['sig'] ?? '');

  if ($adminId <= 0 || $adminUsername === '' || $expiresAt < time() || $signature === '') {
    mmkgt_admin_clear_auth_cookie();
    return;
  }

  $expected = mmkgt_admin_sign_token($adminId, $adminUsername, $expiresAt);
  if (!hash_equals($expected, $signature)) {
    mmkgt_admin_clear_auth_cookie();
    return;
  }

  session_regenerate_id(true);
  $_SESSION['admin_id'] = $adminId;
  $_SESSION['admin_username'] = $adminUsername;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
  ini_set('session.use_strict_mode', '1');
  session_name('MMKGT_ADMIN_SESSID');
  session_set_cookie_params([
    'lifetime' => 0,
    'path' => mmkgt_admin_cookie_path(),
    'secure' => mmkgt_admin_cookie_secure(),
    'httponly' => true,
    'samesite' => 'Lax',
  ]);

  session_start();
}

mmkgt_admin_restore_auth_cookie();
