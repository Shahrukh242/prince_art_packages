<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$saved = false;
$error = '';

// Standard official navigation items manifest
$defaultNavItems = [
    ['Home',         'index.php',        10],
    ['About',        'about.php',        20],
    ['Products',     'products.php',     30],
    ['Capabilities', 'capabilities.php', 40],
    ['Innovation',   'innovation.php',   50],
    ['Quality',      'quality.php',      60],
    ['Industries',   'industries.php',   70],
    ['Blog',         'blog.php',         80],
    ['Contact',      'contact.php',      90],
];

// Helper to check if a link is in the standard default list
function get_matching_default_nav(string $url, array $defaultNavItems): ?array {
    foreach ($defaultNavItems as $d) {
        if ($d[1] === $url) return $d;
    }
    return null;
}

/* -----------------------------------------------------------------------
   POST handlers
----------------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';

        // Add new link
        if ($action === 'add') {
            $label      = trim($_POST['label']       ?? '');
            $url        = trim($_POST['url']         ?? '');
            $sortOrder  = (int)($_POST['sort_order'] ?? 0);
            $openNewTab = isset($_POST['open_new_tab']) ? 1 : 0;

            if ($label && $url) {
                $stmt = $pdo->prepare(
                    "INSERT INTO nav_links (label, url, sort_order, is_active, open_new_tab)
                     VALUES (?, ?, ?, 1, ?)"
                );
                $stmt->execute([$label, $url, $sortOrder, $openNewTab]);
                $saved = 'Nav item "' . htmlspecialchars($label) . '" added successfully.';
            } else {
                $error = 'Label and URL are both required.';
            }
        }

        // Update link
        elseif ($action === 'update') {
            $id         = (int)$_POST['link_id'];
            $label      = trim($_POST['label']       ?? '');
            $url        = trim($_POST['url']         ?? '');
            $sortOrder  = (int)($_POST['sort_order'] ?? 0);
            $isActive   = isset($_POST['is_active'])   ? 1 : 0;
            $openNewTab = isset($_POST['open_new_tab']) ? 1 : 0;

            if ($label && $url) {
                $stmt = $pdo->prepare(
                    "UPDATE nav_links SET label=?, url=?, sort_order=?, is_active=?, open_new_tab=? WHERE id=?"
                );
                $stmt->execute([$label, $url, $sortOrder, $isActive, $openNewTab, $id]);
                $saved = 'Navigation changes saved.';
            } else {
                $error = 'Label and URL cannot be empty.';
            }
        }

        // Reset a single default nav item
        elseif ($action === 'reset_default') {
            $id  = (int)$_POST['link_id'];
            $url = trim($_POST['url'] ?? '');
            $def = get_matching_default_nav($url, $defaultNavItems);
            if ($def) {
                $stmt = $pdo->prepare("UPDATE nav_links SET label=?, url=?, sort_order=?, is_active=1, open_new_tab=0 WHERE id=?");
                $stmt->execute([$def[0], $def[1], $def[2], $id]);
                $saved = 'Nav item "' . htmlspecialchars($def[0]) . '" has been reset to default.';
            }
        }

        // Restore ALL default navigation items
        elseif ($action === 'restore_all_defaults') {
            $checkStmt = $pdo->prepare("SELECT id FROM nav_links WHERE url = ? LIMIT 1");
            $insStmt   = $pdo->prepare("INSERT INTO nav_links (label, url, sort_order, is_active, open_new_tab) VALUES (?, ?, ?, 1, 0)");
            $updStmt   = $pdo->prepare("UPDATE nav_links SET label = ?, sort_order = ?, is_active = 1 WHERE id = ?");

            $count = 0;
            foreach ($defaultNavItems as [$defLabel, $defUrl, $defOrder]) {
                $checkStmt->execute([$defUrl]);
                $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $updStmt->execute([$defLabel, $defOrder, (int)$row['id']]);
                } else {
                    $insStmt->execute([$defLabel, $defUrl, $defOrder]);
                }
                $count++;
            }
            $saved = "All {$count} standard navigation items restored successfully!";
        }

        // Delete link
        elseif ($action === 'delete') {
            $id = (int)$_POST['link_id'];
            $url = trim($_POST['url'] ?? '');
            $def = get_matching_default_nav($url, $defaultNavItems);

            if ($def) {
                // For standard links, reset rather than permanent destruction
                $stmt = $pdo->prepare("UPDATE nav_links SET label=?, url=?, sort_order=?, is_active=1, open_new_tab=0 WHERE id=?");
                $stmt->execute([$def[0], $def[1], $def[2], $id]);
                $saved = 'Standard item "' . htmlspecialchars($def[0]) . '" was reset to default.';
            } else {
                $pdo->prepare("DELETE FROM nav_links WHERE id=?")->execute([$id]);
                $saved = 'Custom nav item removed.';
            }
        }
    }
}

/* -----------------------------------------------------------------------
   Load all nav links
----------------------------------------------------------------------- */
$links = $pdo->query("SELECT * FROM nav_links ORDER BY sort_order ASC, id ASC")->fetchAll();

// Which default items are missing?
$existingUrls = array_column($links, 'url');
$missingItems = array_filter($defaultNavItems, fn($d) => !in_array($d[1], $existingUrls));

// If any default links were missing completely from table, auto-insert them now
if (!empty($missingItems)) {
    $insStmt = $pdo->prepare("INSERT INTO nav_links (label, url, sort_order, is_active, open_new_tab) VALUES (?, ?, ?, 1, 0)");
    foreach ($missingItems as [$dLabel, $dUrl, $dOrder]) {
        $insStmt->execute([$dLabel, $dUrl, $dOrder]);
    }
    $links = $pdo->query("SELECT * FROM nav_links ORDER BY sort_order ASC, id ASC")->fetchAll();
    $missingItems = [];
}

// Which ID is being edited (from URL param)?
$editingId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
?>

<div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div>
    <h1><i class="ri-navigation-line"></i> Navigation Menu</h1>
    <p class="page-subtitle">Manage, reorder, or edit navigation links. Updates appear instantly across the website header.</p>
  </div>
  <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
    <!-- Restore Defaults Button -->
    <form method="post" onsubmit="return confirm('Restore all standard 9 website navigation links to their original labels and sort order?');" style="display:inline;">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="action"     value="restore_all_defaults">
      <button type="submit" style="background:#fff;border:1.5px solid var(--border);color:var(--text);font-size:0.85rem;font-weight:600;padding:0.55rem 0.9rem;">
        <i class="ri-restart-line text-teal"></i> Restore Default Nav Links
      </button>
    </form>

    <!-- Preview Site Button -->
    <a href="../public_site/index.php" target="_blank"
       style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.55rem 1.1rem;background:var(--navy);border-radius:8px;font-size:0.85rem;font-weight:600;color:#fff;text-decoration:none;">
      <i class="ri-external-link-line"></i> View Live Header
    </a>
  </div>
</div>

<?php if ($saved): ?><p class="success-msg"><i class="ri-check-line"></i> <?= $saved ?></p><?php endif; ?>
<?php if ($error):  ?><p class="error-msg"><i class="ri-error-warning-line"></i> <?= h($error) ?></p><?php endif; ?>

<!-- ====================================================================
     Nav items list
===================================================================== -->
<div class="panel" style="margin-bottom:1.25rem;">
  <h3 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
    <i class="ri-list-ordered text-teal"></i> Current Navigation Items
    <span style="margin-left:auto;font-size:0.8rem;font-weight:400;color:var(--muted);"><?= count($links) ?> item<?= count($links) !== 1 ? 's' : '' ?></span>
  </h3>

  <?php if (empty($links)): ?>
    <p style="color:var(--muted);">No navigation links found. Click "Restore Default Nav Links" above to load standard items.</p>
  <?php else: ?>
    <?php foreach ($links as $link): ?>
      <?php
        $isEditing = ($editingId === (int)$link['id']);
        $def = get_matching_default_nav($link['url'], $defaultNavItems);
        $isDefault = ($def !== null);
      ?>

      <div style="border:1px solid var(--border);border-radius:8px;margin-bottom:0.6rem;overflow:hidden;<?= $isEditing ? 'border-color:var(--teal);box-shadow:0 0 0 2px rgba(28,124,140,0.15);' : '' ?>">

        <!-- ---- Summary row ---- -->
        <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;background:<?= $isEditing ? '#f0fdfc' : '#fff' ?>;flex-wrap:wrap;">
          <!-- Sort order (auto-submit) -->
          <form method="post" style="display:contents;">
            <input type="hidden" name="csrf_token"  value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="action"       value="update">
            <input type="hidden" name="link_id"      value="<?= (int)$link['id'] ?>">
            <input type="hidden" name="label"        value="<?= h($link['label']) ?>">
            <input type="hidden" name="url"          value="<?= h($link['url']) ?>">
            <input type="hidden" name="is_active"    value="<?= $link['is_active'] ? '1' : '0' ?>">
            <input type="hidden" name="open_new_tab" value="<?= $link['open_new_tab'] ? '1' : '0' ?>">
            <input type="number" name="sort_order" value="<?= (int)$link['sort_order'] ?>"
                   title="Sort order — change to reorder immediately"
                   style="width:58px;padding:0.3rem 0.4rem;border:1px solid var(--border);border-radius:5px;font-size:0.82rem;text-align:center;"
                   onchange="this.closest('form').submit()">
          </form>

          <span style="font-weight:700;font-size:0.9rem;min-width:110px;"><?= h($link['label']) ?></span>
          <a href="../public_site/<?= h($link['url']) ?>" target="_blank"
             style="font-size:0.8rem;color:var(--muted);text-decoration:none;margin-right:auto;">
            <i class="ri-link"></i> <?= h($link['url']) ?>
          </a>

          <?php if ($isDefault): ?>
            <span style="font-size:0.72rem;color:var(--muted);background:var(--bg);padding:2px 6px;border-radius:4px;">Standard Link</span>
          <?php endif; ?>

          <?php if (!$link['is_active']): ?>
            <span style="font-size:0.72rem;background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:20px;font-weight:700;">Hidden</span>
          <?php else: ?>
            <span style="font-size:0.72rem;background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:20px;font-weight:700;">Active</span>
          <?php endif; ?>

          <!-- Edit toggle -->
          <?php if ($isEditing): ?>
            <a href="navigation.php" style="font-size:0.82rem;color:var(--muted);display:inline-flex;align-items:center;gap:0.25rem;">
              <i class="ri-arrow-up-s-line"></i> Close
            </a>
          <?php else: ?>
            <a href="?edit=<?= (int)$link['id'] ?>#edit-<?= (int)$link['id'] ?>"
               style="font-size:0.82rem;color:var(--teal);display:inline-flex;align-items:center;gap:0.25rem;font-weight:600;">
              <i class="ri-edit-line"></i> Edit
            </a>
          <?php endif; ?>

          <?php if ($isDefault): ?>
            <!-- Reset default button -->
            <form method="post" onsubmit="return confirm('Reset \'<?= addslashes(h($link['label'])) ?>\' to default label and position?')" style="margin:0;">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="action"     value="reset_default">
              <input type="hidden" name="link_id"    value="<?= (int)$link['id'] ?>">
              <input type="hidden" name="url"        value="<?= h($link['url']) ?>">
              <button type="submit" style="background:none;border:none;color:var(--teal);padding:0.2rem 0.4rem;font-size:0.82rem;cursor:pointer;display:inline-flex;align-items:center;gap:0.25rem;font-weight:600;">
                <i class="ri-restart-line"></i> Reset
              </button>
            </form>
          <?php else: ?>
            <!-- Delete custom link -->
            <form method="post" onsubmit="return confirm('Remove \'<?= addslashes(h($link['label'])) ?>\' from navigation?')" style="margin:0;">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="action"     value="delete">
              <input type="hidden" name="link_id"    value="<?= (int)$link['id'] ?>">
              <button type="submit" style="background:none;border:none;color:#b91c1c;padding:0.2rem 0.4rem;font-size:0.82rem;cursor:pointer;display:inline-flex;align-items:center;gap:0.25rem;font-weight:600;">
                <i class="ri-delete-bin-line"></i> Delete
              </button>
            </form>
          <?php endif; ?>
        </div>

        <!-- ---- Inline edit form ---- -->
        <?php if ($isEditing): ?>
        <div id="edit-<?= (int)$link['id'] ?>" style="padding:1.1rem 1.1rem 1.25rem;border-top:1px solid #99f6e4;background:#f0fdfc;">
          <form method="post" class="content-form" style="margin:0;">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="action"     value="update">
            <input type="hidden" name="link_id"    value="<?= (int)$link['id'] ?>">

            <div style="display:grid;grid-template-columns:1fr 1fr 80px;gap:1rem;margin-bottom:0.9rem;flex-wrap:wrap;">
              <label>Link Label
                <input type="text" name="label" value="<?= h($link['label']) ?>" required>
              </label>
              <label>Target URL / File
                <input type="text" name="url" value="<?= h($link['url']) ?>" required>
              </label>
              <label>Sort Order
                <input type="number" name="sort_order" value="<?= (int)$link['sort_order'] ?>" min="0" step="10">
              </label>
            </div>
            <div style="display:flex;gap:1.5rem;margin-bottom:1rem;flex-wrap:wrap;">
              <label style="display:inline-flex;align-items:center;gap:0.5rem;font-size:0.88rem;font-weight:500;">
                <input type="checkbox" name="is_active" <?= $link['is_active'] ? 'checked' : '' ?>>
                Active (Visible in header menu)
              </label>
              <label style="display:inline-flex;align-items:center;gap:0.5rem;font-size:0.88rem;font-weight:500;">
                <input type="checkbox" name="open_new_tab" <?= $link['open_new_tab'] ? 'checked' : '' ?>>
                Open in new browser tab
              </label>
            </div>
            <div style="display:flex;gap:0.75rem;">
              <button type="submit" style="background:var(--teal);"><i class="ri-save-line"></i> Save Changes</button>
              <a href="navigation.php" style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.7rem 1.2rem;border:1px solid var(--border);border-radius:8px;color:var(--text);text-decoration:none;font-size:0.88rem;">
                Cancel
              </a>
            </div>
          </form>
        </div>
        <?php endif; ?>

      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- ====================================================================
     Add new link
===================================================================== -->
<div class="panel">
  <h3><i class="ri-add-circle-line"></i> Add New Custom Navigation Link</h3>
  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="add">

    <div style="display:grid;grid-template-columns:1fr 1fr 80px;gap:1rem;margin-bottom:1rem;">
      <label>Link Label <sup style="color:var(--teal);">*</sup>
        <input type="text" name="label" placeholder="e.g. Case Studies" required>
      </label>
      <label>Target URL <sup style="color:var(--teal);">*</sup>
        <input type="text" name="url" placeholder="e.g. case-studies.php" required>
      </label>
      <label>Sort Order
        <input type="number" name="sort_order" value="<?= (count($links) + 1) * 10 ?>" min="0" step="10">
      </label>
    </div>
    <label style="display:inline-flex;align-items:center;gap:0.5rem;margin-bottom:1rem;font-size:0.88rem;font-weight:500;">
      <input type="checkbox" name="open_new_tab"> Open in new browser tab
    </label>
    <br>
    <button type="submit"><i class="ri-add-line"></i> Add Nav Link</button>
  </form>
</div>

<!-- Auto-scroll to edit form when ?edit= is in URL -->
<?php if ($editingId): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('edit-<?= $editingId ?>');
    if (el) { el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
  });
</script>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
