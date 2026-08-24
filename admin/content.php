<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$pages = $pdo->query("SELECT id, slug, title FROM pages ORDER BY title ASC")->fetchAll();
$selectedSlug = $_GET['page'] ?? ($pages[0]['slug'] ?? '');

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf_token'] ?? null)) {
    $pageId = (int)$_POST['page_id'];

    // Update SEO meta
    $stmt = $pdo->prepare("UPDATE pages SET meta_title = ?, meta_description = ? WHERE id = ?");
    $stmt->execute([$_POST['meta_title'] ?? '', $_POST['meta_description'] ?? '', $pageId]);

    // Update each content block that was submitted — skip blanks so an empty field
    // never wipes out real content (or the fallback text) by accident
    foreach ($_POST['blocks'] ?? [] as $blockKey => $value) {
        if (trim($value) === '') {
            continue;
        }
        $stmt = $pdo->prepare(
            "INSERT INTO content_blocks (page_id, block_key, block_type, content)
             VALUES (?, ?, 'richtext', ?)
             ON DUPLICATE KEY UPDATE content = VALUES(content)"
        );
        $stmt->execute([$pageId, $blockKey, $value]);
    }
    $saved = true;
}

// Load the selected page + its content blocks
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ? LIMIT 1");
$stmt->execute([$selectedSlug]);
$page = $stmt->fetch();

$blocks = [];
if ($page) {
    $stmt = $pdo->prepare("SELECT block_key, content FROM content_blocks WHERE page_id = ? ORDER BY sort_order, block_key");
    $stmt->execute([$page['id']]);
    foreach ($stmt->fetchAll() as $row) {
        $blocks[$row['block_key']] = $row['content'];
    }
}

// Default block fields per page — extend this as you add real sections from the Antigravity design
$defaultBlocksByPage = [
    'home' => ['hero_title', 'hero_subtitle', 'trust_bar_line', 'capability_intro'],
    'about' => ['history_text', 'mission_text'],
    'quality-compliance' => ['intro_text', 'qa_process_text'],
    'innovation' => ['coldseal_intro', 'engravix_intro'],
    'contact' => ['intro_text'],
];
$fieldsForThisPage = $defaultBlocksByPage[$selectedSlug] ?? [];
?>
<h1>Page Content</h1>

<div class="page-tabs">
    <?php foreach ($pages as $p): ?>
        <a href="content.php?page=<?= h($p['slug']) ?>"
           class="<?= $p['slug'] === $selectedSlug ? 'active' : '' ?>"><?= h($p['title']) ?></a>
    <?php endforeach; ?>
</div>

<?php if ($saved): ?><p class="success-msg">Saved.</p><?php endif; ?>

<?php if ($page): ?>
<form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="page_id" value="<?= (int)$page['id'] ?>">

    <fieldset>
        <legend>SEO</legend>
        <label>Meta Title (aim for under 60 characters)
            <input type="text" name="meta_title" value="<?= h($page['meta_title']) ?>" maxlength="160">
        </label>
        <label>Meta Description (aim for under 155 characters)
            <textarea name="meta_description" maxlength="300"><?= h($page['meta_description']) ?></textarea>
        </label>
    </fieldset>

    <fieldset>
        <legend>Page Copy</legend>
        <?php foreach ($fieldsForThisPage as $fieldKey): ?>
            <label><?= h(ucwords(str_replace('_', ' ', $fieldKey))) ?>
                <textarea name="blocks[<?= h($fieldKey) ?>]" rows="4"><?= h($blocks[$fieldKey] ?? '') ?></textarea>
            </label>
        <?php endforeach; ?>
        <?php if (empty($fieldsForThisPage)): ?>
            <p>No editable blocks configured for this page yet — add keys to <code>$defaultBlocksByPage</code> in content.php.</p>
        <?php endif; ?>
    </fieldset>

    <button type="submit">Save Changes</button>
</form>
<?php else: ?>
    <p>Page not found.</p>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>