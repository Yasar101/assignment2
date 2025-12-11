
<?php
// functions.php - top
// Harden session cookies
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$cookieParams = [
  'lifetime' => 0,
  'path' => '/',
  'domain' => $_SERVER['HTTP_HOST'],
  'secure' => $secure,          // true if HTTPS
  'httponly' => true,
  'samesite' => 'Lax'          // or 'Strict' as appropriate
];
// set cookie params (PHP 7.3+)
session_set_cookie_params($cookieParams);

// then start session
session_start();


require_once __DIR__ . '/db.php';

// CSRF token funcs
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_check($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
}

// Simple auth helpers
function is_logged_in() {
    return !empty($_SESSION['user']);
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function current_user_id() {
    return $_SESSION['user']['uid'] ?? null;
}

// Escape output
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Basic rate limiter for login attempts (per session)
function login_attempts_exceeded() {
    if (!isset($_SESSION['login_attempts'])) return false;
    return $_SESSION['login_attempts']['count'] >= 10 && (time() - $_SESSION['login_attempts']['last']) < 900;
}
function record_login_attempt($success) {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = ['count'=>0, 'last'=>0];
    }
    if ($success) {
        $_SESSION['login_attempts'] = ['count'=>0, 'last'=>0];
        return;
    }
    $_SESSION['login_attempts']['count'] += 1;
    $_SESSION['login_attempts']['last'] = time();
}
