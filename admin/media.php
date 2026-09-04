<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$uploadDir = __DIR__ . '/../public_site/assets/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$message = '';
$error = '';

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && csrf_verify($_POST['csrf_token'] ?? null)) {
    $file = $_FILES['image'];
    $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed — please try again.';
    } elseif (!isset($allowedTypes[$file['type']])) {
        $error = 'Only JPG, PNG, WEBP, and GIF images are allowed.';
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        $error = 'Image must be under 5MB.';
    } else {
        $ext = $allowedTypes[$file['type']];
        $safeName = bin2hex(random_bytes(8)) . '.' . $ext;
        $destination = $uploadDir . $safeName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $stmt = $pdo->prepare("INSERT INTO media (filename, filepath, alt_text) VALUES (?, ?, ?)");
            $stmt->execute([
                $file['name'],
                'assets/uploads/' . $safeName,
                trim($_POST['alt_text'] ?? ''),
            ]);
            $message = 'Image uploaded successfully.';
        } else {
            $error = 'Could not save the uploaded file — check folder permissions.';
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && csrf_verify($_GET['csrf'] ?? null)) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT filepath FROM media WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if ($item) {
        // Clear any content blocks pointing at this image first, so pages fall back
        // to their default text/image instead of showing a broken link
        $clearStmt = $pdo->prepare("DELETE FROM content_blocks WHERE block_type = 'image' AND content = ?");
        $clearStmt->execute([$item['filepath']]);

        $fullPath = __DIR__ . '/../public_site/' . $item['filepath'];
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
        $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Image deleted. Any pages using it now show their default image instead.';
    }
}

$mediaItems = get_all_media();
?>
<h1><i class="ri-image-2-line"></i> Media Library</h1>
<p class="page-subtitle">Upload images here, then select them anywhere images are used across the site.</p>

<?php if ($message): ?><p class="success-msg"><?= h($message) ?></p><?php endif; ?>
<?php if ($error): ?><p class="error-msg"><?= h($error) ?></p><?php endif; ?>

<div class="upload-card">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <div class="upload-row">
            <label class="file-input-label">
                <i class="ri-upload-cloud-2-line"></i> Choose Image
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" required>
            </label>
            <input type="text" name="alt_text" placeholder="Describe this image (for SEO / accessibility)" class="alt-text-input">
            <button type="submit"><i class="ri-add-line"></i> Upload</button>
        </div>
    </form>
</div>

<div class="media-grid">
    <?php foreach ($mediaItems as $item): ?>
        <div class="media-tile">
            <img src="../public_site/<?= h($item['filepath']) ?>" alt="<?= h($item['alt_text']) ?>" loading="lazy">
            <div class="media-tile-info">
                <span class="media-filename" title="<?= h($item['filename']) ?>"><?= h($item['filename']) ?></span>
                <div class="media-tile-actions">
                    <button type="button" class="copy-path-btn" data-path="<?= h($item['filepath']) ?>"><i class="ri-file-copy-line"></i> Copy Path</button>
                    <a href="media.php?delete=<?= (int)$item['id'] ?>&csrf=<?= h(csrf_token()) ?>"
                       onclick="return confirm('Delete this image? This cannot be undone.');"
                       class="delete-btn">🗑 Delete</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($mediaItems)): ?>
        <p>No images uploaded yet — use the form above to add your first one.</p>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.copy-path-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        navigator.clipboard.writeText(btn.dataset.path);
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="ri-check-line"></i> Copied';
        setTimeout(() => btn.innerHTML = original, 1500);
    });
});
</script>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
