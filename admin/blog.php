<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$saved = false;
$error = '';
$action = $_GET['action'] ?? 'list';
$postId = (int)($_GET['id'] ?? 0);

// Load single post for editing
$post = null;
if ($postId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ? LIMIT 1");
    $stmt->execute([$postId]);
    $post = $stmt->fetch();
}

// POST Handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Security token expired. Please refresh and try again.';
    } else {
        $postAction = $_POST['post_action'] ?? '';

        if ($postAction === 'save') {
            $id          = (int)($_POST['post_id'] ?? 0);
            $title       = trim($_POST['title'] ?? '');
            $slug        = trim($_POST['slug'] ?? '');
            $excerpt     = trim($_POST['excerpt'] ?? '');
            $content     = $_POST['content'] ?? '';
            $imagePath   = trim($_POST['image_path'] ?? 'assets/images/engravix.jpg');
            $isPublished = isset($_POST['is_published']) ? 1 : 0;
            $pubDate     = !empty($_POST['published_at']) ? $_POST['published_at'] : date('Y-m-d H:i:s');

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            }

            if ($title === '') {
                $error = 'Article title is required.';
            } else {
                if ($id > 0) {
                    $stmt = $pdo->prepare(
                        "UPDATE blog_posts SET title=?, slug=?, excerpt=?, content=?, image_path=?, is_published=?, published_at=? WHERE id=?"
                    );
                    $stmt->execute([$title, $slug, $excerpt, $content, $imagePath, $isPublished, $pubDate, $id]);
                    $saved = 'Article "' . htmlspecialchars($title) . '" updated successfully.';
                    $postId = $id;
                    $action = 'edit';
                } else {
                    $stmt = $pdo->prepare(
                        "INSERT INTO blog_posts (title, slug, excerpt, content, image_path, is_published, published_at)
                         VALUES (?, ?, ?, ?, ?, ?, ?)"
                    );
                    $stmt->execute([$title, $slug, $excerpt, $content, $imagePath, $isPublished, $pubDate]);
                    $saved = 'Article "' . htmlspecialchars($title) . '" created successfully.';
                    $postId = (int)$pdo->lastInsertId();
                    $action = 'edit';
                }

                // Reload post
                $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ? LIMIT 1");
                $stmt->execute([$postId]);
                $post = $stmt->fetch();
            }
        } elseif ($postAction === 'delete') {
            $delId = (int)($_POST['post_id'] ?? 0);
            if ($delId > 0) {
                $pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$delId]);
                $saved = 'Article deleted successfully.';
                $action = 'list';
                $post = null;
            }
        }
    }
}

$posts = $pdo->query("SELECT * FROM blog_posts ORDER BY published_at DESC")->fetchAll();
?>

<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div>
    <h1 style="margin:0 0 0.25rem 0;"><i class="ri-article-line"></i> Pharmaceutical Technical Blog Articles</h1>
    <p style="margin:0; color:var(--muted); font-size:0.9rem;">
      Author and manage technical articles with instant inline page linking to products, quality standards, and services.
    </p>
  </div>
  <div>
    <?php if ($action === 'list'): ?>
      <a href="blog.php?action=create" class="btn" style="background:var(--teal); color:#fff; font-weight:700; font-size:0.88rem; padding:0.55rem 1.1rem; border-radius:6px; display:inline-flex; align-items:center; gap:0.4rem; text-decoration:none;">
        <i class="ri-add-line"></i> Write New Article
      </a>
    <?php else: ?>
      <a href="blog.php" class="btn" style="background:#fff; color:var(--text); border:1px solid var(--border); font-size:0.85rem; padding:0.5rem 0.9rem; border-radius:6px; text-decoration:none;">
        <i class="ri-arrow-left-line"></i> Back to Article List
      </a>
    <?php endif; ?>
  </div>
</div>

<?php if ($saved): ?>
  <div class="alert alert-success" style="background:#d1fae5; color:#065f46; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem;">
    <i class="ri-checkbox-circle-fill" style="font-size:1.2rem;"></i> <?= h($saved) ?>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger" style="background:#fee2e2; color:#991b1b; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem;">
    <i class="ri-error-warning-fill" style="font-size:1.2rem;"></i> <?= h($error) ?>
  </div>
<?php endif; ?>

<?php if ($action === 'create' || $action === 'edit'): ?>
  <!-- ====================================================================
       ARTICLE EDITOR FORM
  ===================================================================== -->
  <div class="panel" style="background:#ffffff; border-radius:10px; border:1px solid var(--border); padding:1.75rem; margin-bottom:2rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; border-bottom:1px solid var(--border); padding-bottom:1rem;">
      <h2 style="margin:0; font-size:1.3rem; color:var(--navy);">
        <?= $action === 'create' ? '<i class="ri-edit-line"></i> Write New Article' : '<i class="ri-edit-2-line"></i> Edit Article: ' . h($post['title']) ?>
      </h2>
      <?php if ($post && !empty($post['slug'])): ?>
        <a href="<?= public_url('blog/' . urlencode($post['slug'])) ?>" target="_blank" style="font-size:0.85rem; color:var(--teal); font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem;">
          <i class="ri-external-link-line"></i> View on Live Site
        </a>
      <?php endif; ?>
    </div>

    <form method="post" class="content-form">
      <input type="hidden" name="csrf_token"  value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="post_action" value="save">
      <input type="hidden" name="post_id"     value="<?= (int)($post['id'] ?? 0) ?>">

      <div style="display:grid; grid-template-columns:2fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <label>Article Title <span style="color:#ef4444;">*</span>
          <input type="text" name="title" value="<?= h($post['title'] ?? '') ?>" required placeholder="e.g. ISO 9001:2015 & cGMP Compliance in Pharmaceutical Secondary Packaging" style="font-size:1.05rem; font-weight:600;">
        </label>

        <label>URL Slug
          <input type="text" name="slug" value="<?= h($post['slug'] ?? '') ?>" placeholder="leave blank to generate from title">
        </label>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <label>Featured Image Path
          <input type="text" name="image_path" value="<?= h($post['image_path'] ?? 'assets/images/engravix.jpg') ?>" placeholder="assets/images/engravix.jpg">
        </label>

        <label>Publish Date
          <input type="text" name="published_at" value="<?= h($post['published_at'] ?? date('Y-m-d H:i:s')) ?>">
        </label>

        <div style="display:flex; align-items:center; padding-top:1.5rem;">
          <label style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.92rem; font-weight:700; cursor:pointer;">
            <input type="checkbox" name="is_published" <?= (!isset($post['is_published']) || $post['is_published']) ? 'checked' : '' ?>>
            Published &amp; Live on Website
          </label>
        </div>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label>Lead Excerpt / Summary <span style="color:var(--muted); font-weight:400;">(Shown on blog catalog cards &amp; article lead box)</span>
          <textarea name="excerpt" rows="3" placeholder="Brief technical summary of the article..."><?= h($post['excerpt'] ?? '') ?></textarea>
        </label>
      </div>

      <!-- Content Area with Link Inserter Toolbar -->
      <div style="margin-bottom:1.5rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.4rem;">
          <label style="margin-bottom:0; font-weight:700;">
            Full Article Content <span style="color:var(--muted); font-weight:400;">(HTML supported — use link tool to connect words to other pages)</span>
          </label>
        </div>

        <!-- LINK INSERTION TOOLBAR -->
        <div class="editor-toolbar" style="background:#f1f5f9; border:1px solid var(--border); border-bottom:none; border-radius:8px 8px 0 0; padding:0.5rem 0.75rem; display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
          <button type="button" class="toolbar-btn" onclick="openLinkModal('blogContentTextarea')" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.35rem 0.75rem; border-radius:5px; font-size:0.82rem; font-weight:700; color:var(--navy); cursor:pointer; display:inline-flex; align-items:center; gap:0.35rem;">
            <i class="ri-link text-teal" style="font-size:1rem;"></i> <strong>Add Page Link to Text</strong>
          </button>
          <button type="button" class="toolbar-btn" onclick="formatSelection('blogContentTextarea', 'strong')" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.35rem 0.6rem; border-radius:5px; font-size:0.82rem; font-weight:700; color:#334155; cursor:pointer;">
            <i class="ri-bold"></i> Bold
          </button>
          <button type="button" class="toolbar-btn" onclick="formatSelection('blogContentTextarea', 'em')" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.35rem 0.6rem; border-radius:5px; font-size:0.82rem; font-style:italic; font-weight:600; color:#334155; cursor:pointer;">
            <i class="ri-italic"></i> Italic
          </button>
          <button type="button" class="toolbar-btn" onclick="formatSelection('blogContentTextarea', 'h3')" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.35rem 0.6rem; border-radius:5px; font-size:0.82rem; font-weight:700; color:#334155; cursor:pointer;">
            <i class="ri-heading"></i> Subheading (H3)
          </button>
          <span style="font-size:0.75rem; color:var(--muted); margin-left:auto;">
            <i class="ri-information-line"></i> Select text in the box and click <strong>"Add Page Link to Text"</strong> to link it anywhere.
          </span>
        </div>

        <textarea name="content" id="blogContentTextarea" rows="18" style="border-radius:0 0 8px 8px; font-family:monospace; font-size:0.92rem; line-height:1.6;"><?= h($post['content'] ?? '') ?></textarea>
      </div>

      <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; border-top:1px solid var(--border); padding-top:1.25rem;">
        <a href="blog.php" style="color:var(--muted); text-decoration:none; font-size:0.9rem;">Cancel</a>
        <button type="submit" style="background:var(--teal); color:#ffffff; font-size:0.95rem; font-weight:700; padding:0.65rem 1.5rem; border-radius:6px; cursor:pointer;">
          <i class="ri-save-line"></i> <?= $action === 'create' ? 'Publish Article' : 'Save Changes' ?>
        </button>
      </div>
    </form>
  </div>

<?php else: ?>
  <!-- ====================================================================
       ARTICLES LIST TABLE
  ===================================================================== -->
  <table class="data-table" style="background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    <thead>
      <tr>
        <th style="width:70px;">Image</th>
        <th>Article Title &amp; Excerpt</th>
        <th>Date</th>
        <th>Status</th>
        <th style="text-align:right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
        <tr>
          <td>
            <img src="../public_site/<?= h($p['image_path']) ?>" alt="" style="width:60px; height:42px; object-fit:cover; border-radius:4px; border:1px solid var(--border);">
          </td>
          <td>
            <strong style="font-size:0.95rem; color:var(--navy); display:block; margin-bottom:0.25rem;"><?= h($p['title']) ?></strong>
            <span style="font-size:0.82rem; color:var(--muted); display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"><?= h($p['excerpt']) ?></span>
            <span style="font-size:0.75rem; color:var(--teal); margin-top:0.25rem; display:inline-block;"><code><?= h($p['slug']) ?></code></span>
          </td>
          <td style="white-space:nowrap; font-size:0.85rem;">
            <?= h(date('d M Y', strtotime($p['published_at']))) ?>
          </td>
          <td>
            <?php if ($p['is_published']): ?>
              <span style="display:inline-block; background:#dcfce7; color:#15803d; padding:0.2rem 0.6rem; border-radius:20px; font-size:0.78rem; font-weight:700;">Live</span>
            <?php else: ?>
              <span style="display:inline-block; background:#f3f4f6; color:#6b7280; padding:0.2rem 0.6rem; border-radius:20px; font-size:0.78rem; font-weight:700;">Draft</span>
            <?php endif; ?>
          </td>
          <td style="text-align:right; white-space:nowrap;">
            <a href="<?= public_url('blog/' . urlencode($p['slug'])) ?>" target="_blank" class="btn" style="background:#f3f4f6; color:var(--navy); font-size:0.8rem; padding:0.35rem 0.65rem; border-radius:4px; text-decoration:none; margin-right:0.35rem;">
              <i class="ri-eye-line"></i>
            </a>
            <a href="blog.php?action=edit&id=<?= (int)$p['id'] ?>" class="btn" style="background:var(--navy); color:#fff; font-size:0.8rem; padding:0.35rem 0.75rem; border-radius:4px; text-decoration:none; margin-right:0.35rem;">
              <i class="ri-edit-line"></i> Edit
            </a>
            <form method="post" onsubmit="return confirm('Delete article \'<?= addslashes(h($p['title'])) ?>\' permanently?');" style="display:inline;">
              <input type="hidden" name="csrf_token"  value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="post_action" value="delete">
              <input type="hidden" name="post_id"     value="<?= (int)$p['id'] ?>">
              <button type="submit" style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; font-size:0.8rem; padding:0.35rem 0.65rem; border-radius:4px; cursor:pointer;">
                <i class="ri-delete-bin-line"></i>
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($posts)): ?>
        <tr><td colspan="5" style="text-align:center; padding:3rem; color:var(--muted);">No blog articles found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
<?php endif; ?>

<!-- ====================================================================
     GLOBAL PAGE LINK INSERTION MODAL COMPONENT
===================================================================== -->
<?php require_once __DIR__ . '/includes/link_modal.php'; ?>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
