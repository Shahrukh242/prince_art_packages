<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$fields = [
    'ga4_measurement_id' => 'Google Analytics 4 Measurement ID',
    'gsc_verification_tag' => 'Google Search Console Verification Meta Tag',
    'default_meta_description' => 'Site-wide Default Meta Description (fallback)',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf_token'] ?? null)) {
    foreach ($fields as $key => $label) {
        $stmt = $pdo->prepare(
            "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        );
        $stmt->execute([$key, $_POST[$key] ?? '']);
    }
    $saved = true;
}

$current = [];
foreach ($pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll() as $row) {
    $current[$row['setting_key']] = $row['setting_value'];
}
?>
<h1>SEO Settings</h1>
<?php if (!empty($saved)): ?><p class="success-msg">Saved.</p><?php endif; ?>

<form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <?php foreach ($fields as $key => $label): ?>
        <label><?= h($label) ?>
            <input type="text" name="<?= h($key) ?>" value="<?= h($current[$key] ?? '') ?>">
        </label>
    <?php endforeach; ?>
    <button type="submit">Save Settings</button>
</form>

<p style="margin-top:2rem; color:#6b7280;">
    Per-page title/description is edited on the <a href="content.php">Page Content</a> screen —
    this page is only for site-wide values like your GA4 ID.
</p>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
