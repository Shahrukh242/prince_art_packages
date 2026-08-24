<?php
// setup.php — Initial Admin Account Setup Script
// Delete this file after creating your admin account!

require_once __DIR__ . '/includes/db.php';

$message = '';
$error = '';
$userCount = 0;

try {
    $pdo = get_db();
    $stmt = $pdo->query("SELECT COUNT(*) as c FROM admin_users");
    $userCount = (int)$stmt->fetch()['c'];
} catch (Exception $e) {
    $error = "Database Error: " . $e->getMessage() . ". Did you import database/schema.sql into MySQL first?";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'admin';

    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $role]);
            $message = "Admin user '{$username}' created successfully! You can now <a href='admin/login.php'>log in here</a>.<br><strong>Important:</strong> Delete this setup.php file from your server now for security.";
            $userCount++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username '{$username}' already exists.";
            } else {
                $error = "Error creating admin user: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prince Art Packages — Initial Setup</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; padding: 2rem; }
        .setup-card { max-width: 480px; margin: 3rem auto; background: #1e293b; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        h1 { font-size: 1.5rem; margin-top: 0; color: #38bdf8; text-align: center; }
        p { color: #94a3b8; font-size: 0.9rem; line-height: 1.5; }
        label { display: block; margin-bottom: 1rem; color: #cbd5e1; font-weight: 500; }
        input, select { width: 100%; padding: 0.75rem; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: #fff; box-sizing: border-box; margin-top: 0.35rem; }
        button { width: 100%; padding: 0.85rem; background: #0284c7; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-top: 1rem; }
        button:hover { background: #0369a1; }
        .alert { padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-success { background: #064e3b; color: #a7f3d0; border: 1px solid #059669; }
        .alert-danger { background: #7f1d1d; color: #fecaca; border: 1px solid #dc2626; }
        .alert-warning { background: #78350f; color: #fef08a; border: 1px solid #d97706; }
    </style>
</head>
<body>
    <div class="setup-card">
        <h1>Prince Art Packages CMS</h1>
        <p style="text-align:center;">Admin Account Initial Setup</p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($userCount > 0 && empty($message)): ?>
            <div class="alert alert-warning">
                <strong>Notice:</strong> <?= $userCount ?> admin account(s) already exist in the database. You can add another user or <a href="admin/login.php" style="color:#fef08a;">go to Admin Login</a>.
            </div>
        <?php endif; ?>

        <form method="post">
            <label>Username
                <input type="text" name="username" placeholder="e.g. admin" required>
            </label>
            <label>Password
                <input type="password" name="password" placeholder="••••••••" required>
            </label>
            <label>Role
                <select name="role">
                    <option value="admin">Administrator</option>
                    <option value="editor">Editor</option>
                </select>
            </label>
            <button type="submit">Create Admin Account</button>
        </form>
    </div>
</body>
</html>
