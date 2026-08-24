<?php
// includes/auth.php — session-based admin authentication.

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    // Harden session cookie settings
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']), // only send cookie over HTTPS once live
    ]);
    session_start();
}

/** Call at the top of every admin page (except login.php) to enforce login. */
function require_login(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

/** Attempt login; returns true on success, false on failure. Rate-limit this in production. */
function attempt_login(string $username, string $password): bool {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT id, password_hash, role FROM admin_users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true); // prevent session fixation
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_role'] = $user['role'];
        return true;
    }
    return false;
}

function logout(): void {
    $_SESSION = [];
    session_destroy();
}
