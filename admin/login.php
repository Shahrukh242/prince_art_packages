<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Session expired, please try again.';
    } elseif (attempt_login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login — Prince Art Packages</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
    <form method="post" class="login-box">
        <h1>Prince Art Packages</h1>
        <p class="subtitle">Admin Dashboard</p>
        <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <label>Username<input type="text" name="username" required autofocus></label>
        <label>Password<input type="password" name="password" required></label>
        <button type="submit">Log In</button>
    </form>
</body>
</html>
