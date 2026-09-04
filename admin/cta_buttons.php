<?php
require __DIR__ . '/includes/layout_top.php';
require_once __DIR__ . '/../includes/cta_buttons_manifest.php';
$pdo = get_db();

$saved = false;
$error = '';

/* -----------------------------------------------------------------------
   Load Pages for Tabs
----------------------------------------------------------------------- */
$pages = $pdo->query("
    SELECT id, slug, title 
    FROM pages 
    ORDER BY FIELD(slug, 'global', 'home', 'about', 'products', 'capabilities', 'innovation', 'quality', 'industries', 'case-studies', 'contact', 'blog'), title ASC
")->fetchAll();

$selectedSlug = $_GET['page'] ?? ($pages[0]['slug'] ?? 'global');

// Auto-heal / Ensure standard default CTA buttons exist in database for this page
ensure_page_default_cta_buttons($pdo, $selectedSlug);

// Map of default buttons for the selected page
$allDefaultBtns = get_all_default_cta_buttons();
$pageDefaultBtns = $allDefaultBtns[$selectedSlug] ?? [];

/* -----------------------------------------------------------------------
   POST handlers
----------------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';

        // 1. Add new CTA button
        if ($action === 'add') {
            $pageId     = (int)$_POST['page_id'];
            $label      = trim($_POST['label']       ?? '');
            $url        = trim($_POST['url']         ?? '');
            $actionType = trim($_POST['action_type'] ?? 'link');
            $style      = $_POST['style']       ?? 'btn-gold';
            $icon       = trim($_POST['icon']        ?? '');
            $placement  = trim($_POST['placement']   ?? 'hero');
            $sortOrder  = (int)($_POST['sort_order'] ?? 0);

            if ($label && $placement) {
                if ($actionType === 'popup' && empty($url)) {
                    $url = '#rfq-modal';
                }
                $stmt = $pdo->prepare(
                    "INSERT INTO cta_buttons (page_id, label, url, action_type, style, icon, placement, sort_order, is_active)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)"
                );
                $stmt->execute([$pageId, $label, $url, $actionType, $style, $icon ?: null, $placement, $sortOrder]);
                $saved = 'CTA Button "' . htmlspecialchars($label) . '" added successfully.';
            } else {
                $error = 'Label and Placement Zone are required.';
            }
        }

        // 2. Update existing CTA button
        elseif ($action === 'update') {
            $id         = (int)$_POST['btn_id'];
            $label      = trim($_POST['label']       ?? '');
            $url        = trim($_POST['url']         ?? '');
            $actionType = trim($_POST['action_type'] ?? 'link');
            $style      = $_POST['style']       ?? 'btn-gold';
            $icon       = trim($_POST['icon']        ?? '');
            $placement  = trim($_POST['placement']   ?? '');
            $sortOrder  = (int)($_POST['sort_order'] ?? 0);
            $isActive   = isset($_POST['is_active']) ? 1 : 0;

            if ($actionType === 'popup' && empty($url)) {
                $url = '#rfq-modal';
            }

            $stmt = $pdo->prepare(
                "UPDATE cta_buttons SET label=?, url=?, action_type=?, style=?, icon=?, placement=?, sort_order=?, is_active=?
                 WHERE id=?"
            );
            $stmt->execute([$label, $url, $actionType, $style, $icon ?: null, $placement, $sortOrder, $isActive, $id]);
            $saved = 'CTA Button "' . htmlspecialchars($label) . '" updated successfully.';
        }

        // 3. Reset a standard CTA button to its default values
        elseif ($action === 'reset_default') {
            $id        = (int)$_POST['btn_id'];
            $placement = trim($_POST['placement'] ?? '');
            $sortOrder = (int)$_POST['sort_order'];

            // Find matching default
            $matched = null;
            foreach ($pageDefaultBtns as $def) {
                if ($def['placement'] === $placement && (int)$def['sort_order'] === $sortOrder) {
                    $matched = $def;
                    break;
                }
            }

            if ($matched) {
                $actType = ($matched['placement'] === 'header' && stripos($matched['label'], 'quote') !== false) ? 'popup' : 'link';
                $stmt = $pdo->prepare("UPDATE cta_buttons SET label=?, url=?, action_type=?, style=?, icon=?, is_active=1 WHERE id=?");
                $stmt->execute([$matched['label'], $matched['url'], $actType, $matched['style'], $matched['icon'] ?: null, $id]);
                $saved = 'Button "' . htmlspecialchars($matched['label']) . '" has been reset to default.';
            }
        }

        // 4. Restore all default CTA buttons for this page
        elseif ($action === 'restore_page_defaults') {
            $count = restore_page_default_cta_buttons($pdo, $selectedSlug);
            $saved = "Restored {$count} default CTA buttons for this page successfully!";
        }

        // 5. Delete custom CTA button
        elseif ($action === 'delete') {
            $id = (int)$_POST['btn_id'];
            $pdo->prepare("DELETE FROM cta_buttons WHERE id=?")->execute([$id]);
            $saved = 'CTA button deleted.';
        }
    }
}

/* -----------------------------------------------------------------------
   Load Selected Page Data
----------------------------------------------------------------------- */
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ? LIMIT 1");
$stmt->execute([$selectedSlug]);
$selPage = $stmt->fetch();

$buttons = [];
if ($selPage) {
    $stmt = $pdo->prepare(
        "SELECT * FROM cta_buttons WHERE page_id = ? ORDER BY placement ASC, sort_order ASC, id ASC"
    );
    $stmt->execute([$selPage['id']]);
    $buttons = $stmt->fetchAll();
}

$styles = [
    'btn-gold'                => 'Gold (Solid Accent)',
    'btn-teal'                => 'Teal (Brand Primary)',
    'btn-navy'                => 'Navy (Dark)',
    'btn-outline-navy'        => 'Outline Navy (Ghost/Bordered)',
    'btn-outline-teal'        => 'Outline Teal (Ghost/Bordered)',
    'btn-outline-navy btn-sm' => 'Small Outline Navy (Card Button)',
    'btn-gold btn-sm'         => 'Small Gold (Header/Footer Button)',
];

$previewUrl = ($selectedSlug === 'home') ? 'index.php' : (($selectedSlug === 'global') ? 'index.php' : h($selectedSlug) . '.php');
?>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
  <div>
    <h1 style="margin:0 0 0.25rem 0;">Call-to-Action (CTA) Management</h1>
    <p style="margin:0;color:var(--muted);font-size:0.9rem;">
      Configure button text, styles, destinations, and choose whether buttons <strong>Navigate to Contact Page</strong> or <strong>Open RFQ Popup Modal</strong>.
    </p>
  </div>
  <div style="display:flex;gap:0.75rem;align-items:center;">
    <a href="../public_site/<?= $previewUrl ?>" target="_blank" class="btn" style="background:#0b2545;color:#fff;font-size:0.85rem;padding:0.45rem 0.9rem;">
      <i class="ri-external-link-line"></i> View Page Live
    </a>
  </div>
</div>

<?php if ($saved): ?>
  <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:0.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;">
    <i class="ri-checkbox-circle-fill" style="font-size:1.2rem;"></i> <?= h($saved) ?>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:0.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;">
    <i class="ri-error-warning-fill" style="font-size:1.2rem;"></i> <?= h($error) ?>
  </div>
<?php endif; ?>

<!-- ====================================================================
     Page Selector Navigation Tabs
===================================================================== -->
<div class="tabs-nav" style="display:flex;gap:0.4rem;flex-wrap:wrap;border-bottom:2px solid var(--border);padding-bottom:0.75rem;margin-bottom:1.75rem;">
  <?php foreach ($pages as $p): ?>
    <?php
      $isActive = ($p['slug'] === $selectedSlug);
      $bCount = (int)$pdo->query("SELECT COUNT(*) FROM cta_buttons WHERE page_id = {$p['id']}")->fetchColumn();
    ?>
    <a href="cta_buttons.php?page=<?= urlencode($p['slug']) ?>" 
       class="tab-link <?= $isActive ? 'active' : '' ?>"
       style="padding:0.5rem 1rem;border-radius:6px;text-decoration:none;font-weight:600;font-size:0.88rem;display:inline-flex;align-items:center;gap:0.4rem;<?= $isActive ? 'background:var(--teal);color:#fff;' : 'background:#fff;color:var(--text);border:1px solid var(--border);' ?>">
      <?= h($p['title']) ?>
      <span style="font-size:0.72rem;background:<?= $isActive ? 'rgba(255,255,255,0.25)' : 'var(--bg)' ?>;padding:1px 6px;border-radius:10px;font-weight:700;">
        <?= $bCount ?>
      </span>
    </a>
  <?php endforeach; ?>
</div>

<!-- ====================================================================
     Active Page CTA Buttons Grouped by Placement Zone
===================================================================== -->
<?php
  // Group buttons by placement zone
  $grouped = [];
  foreach ($buttons as $b) {
      $grouped[$b['placement']][] = $b;
  }
?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
  <h2 style="font-size:1.25rem;margin:0;">
    Buttons on "<?= h($selPage['title'] ?? ucfirst($selectedSlug)) ?>" 
    <span style="font-size:0.9rem;font-weight:400;color:var(--muted);">(<?= count($buttons) ?> total)</span>
  </h2>
  <form method="post" onsubmit="return confirm('Restore all original default CTA buttons for <?= addslashes(h($selPage['title'])) ?>? Custom changes will be reset.');">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="restore_page_defaults">
    <button type="submit" style="background:#f3f4f6;color:var(--text);border:1px solid var(--border);font-size:0.8rem;padding:0.35rem 0.75rem;cursor:pointer;">
      <i class="ri-history-line"></i> Reset Page CTA Defaults
    </button>
  </form>
</div>

<?php if (empty($grouped)): ?>
  <div class="panel" style="text-align:center;padding:3rem 1.5rem;">
    <i class="ri-cursor-line" style="font-size:2.5rem;color:var(--muted);display:block;margin-bottom:0.5rem;"></i>
    <p style="color:var(--muted);margin-bottom:1rem;">No CTA buttons configured for this page yet.</p>
  </div>
<?php else: ?>
  <?php foreach ($grouped as $placement => $btns): ?>
  <div class="panel" style="margin-bottom:1.5rem;">
    <h3 style="font-size:1.05rem;display:flex;align-items:center;gap:0.5rem;margin-top:0;margin-bottom:1rem;border-bottom:1px solid var(--border);padding-bottom:0.6rem;">
      <i class="ri-layout-grid-line text-teal"></i>
      Placement Zone: <code style="color:var(--teal);background:rgba(0,168,150,0.1);padding:2px 8px;border-radius:4px;"><?= h($placement) ?></code>
      <span style="margin-left:auto;font-size:0.8rem;font-weight:400;color:var(--muted);"><?= count($btns) ?> button<?= count($btns) !== 1 ? 's' : '' ?></span>
    </h3>

    <?php foreach ($btns as $btn): ?>
    <?php
      $isDefault = false;
      foreach ($pageDefaultBtns as $def) {
          if ($def['placement'] === $btn['placement'] && (int)$def['sort_order'] === (int)$btn['sort_order']) {
              $isDefault = true;
              break;
          }
      }
      $btnActionType = $btn['action_type'] ?? 'link';
    ?>

    <?php /* Standalone action form for Reset / Delete */ ?>
    <form method="post" id="action-btn-form-<?= (int)$btn['id'] ?>" style="display:none;">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="action"     id="action-btn-type-<?= (int)$btn['id'] ?>" value="delete">
      <input type="hidden" name="btn_id"     value="<?= (int)$btn['id'] ?>">
      <input type="hidden" name="placement"  value="<?= h($btn['placement']) ?>">
      <input type="hidden" name="sort_order" value="<?= (int)$btn['sort_order'] ?>">
    </form>

    <details style="border:1px solid var(--border);border-radius:8px;margin-bottom:0.75rem;background:#fff;">
      <summary style="padding:0.85rem 1.1rem;cursor:pointer;display:flex;align-items:center;gap:0.75rem;list-style:none;user-select:none;flex-wrap:wrap;">
        <span class="btn <?= h($btn['style']) ?>" style="pointer-events:none;padding:0.35rem 0.85rem;font-size:0.8rem;white-space:nowrap;">
          <?php if ($btn['icon']): ?><i class="<?= h($btn['icon']) ?>"></i><?php endif; ?>
          <?= h($btn['label']) ?>
        </span>

        <?php if ($btnActionType === 'popup'): ?>
          <span style="font-size:0.75rem;background:#fef3c7;color:#92400e;border:1px solid #fde68a;padding:2px 8px;border-radius:20px;font-weight:700;">
            <i class="ri-window-line"></i> Opens RFQ Popup Modal
          </span>
        <?php else: ?>
          <span style="font-size:0.75rem;background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;padding:2px 8px;border-radius:20px;font-weight:600;">
            <i class="ri-link"></i> Link: <?= h($btn['url']) ?>
          </span>
        <?php endif; ?>

        <?php if (!$btn['is_active']): ?>
          <span style="font-size:0.75rem;background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:20px;font-weight:700;">Hidden</span>
        <?php else: ?>
          <span style="font-size:0.75rem;background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:20px;font-weight:700;">Active</span>
        <?php endif; ?>

        <?php if ($isDefault): ?>
          <span style="font-size:0.72rem;color:var(--muted);background:var(--bg);padding:2px 6px;border-radius:4px;">Standard</span>
        <?php endif; ?>

        <i class="ri-arrow-down-s-line" style="margin-left:auto;color:var(--muted);"></i>
      </summary>

      <div style="padding:1.25rem 1.25rem 1.5rem;border-top:1px solid var(--border);background:#fafbfc;">
        <form method="post" class="content-form" style="margin-bottom:0;">
          <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
          <input type="hidden" name="action"     value="update">
          <input type="hidden" name="btn_id"     value="<?= (int)$btn['id'] ?>">

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:0.85rem;">
            <label>Button Label / Text
              <input type="text" name="label" value="<?= h($btn['label']) ?>" required>
            </label>
            
            <label>Button Action / Click Behavior
              <select name="action_type" style="font-weight:700;">
                <option value="link" <?= $btnActionType === 'link' ? 'selected' : '' ?>>🌐 Navigate to Page URL (e.g. contact.php)</option>
                <option value="popup" <?= $btnActionType === 'popup' ? 'selected' : '' ?>>⚡ Open RFQ Quote Popup Modal</option>
              </select>
            </label>

            <label>Target Page URL (for normal links, e.g. <code>contact.php</code>, <code>#enquiry-form</code>)
              <input type="text" name="url" value="<?= h($btn['url']) ?>">
            </label>

            <label>Button Style / Visual Theme
              <select name="style">
                <?php foreach ($styles as $s => $sLabel): ?>
                  <option value="<?= h($s) ?>" <?= $btn['style'] === $s ? 'selected' : '' ?>><?= h($sLabel) ?></option>
                <?php endforeach; ?>
              </select>
            </label>

            <label>Icon Class <span style="color:var(--muted);font-weight:400;">(e.g. <code>ri-mail-send-line</code>, <code>ri-file-list-3-line</code>)</span>
              <input type="text" name="icon" value="<?= h($btn['icon'] ?? '') ?>" placeholder="ri-mail-send-line">
            </label>

            <label>Placement Zone Key
              <input type="text" name="placement" value="<?= h($btn['placement']) ?>" required pattern="[a-z0-9_\-]+">
            </label>

            <label>Sort Order Sequence
              <input type="number" name="sort_order" value="<?= (int)$btn['sort_order'] ?>" min="0" step="10">
            </label>
          </div>

          <label style="display:inline-flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;font-size:0.88rem;font-weight:600;">
            <input type="checkbox" name="is_active" <?= $btn['is_active'] ? 'checked' : '' ?>>
            Active (Display this button on the website)
          </label>

          <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <button type="submit" style="background:var(--teal);cursor:pointer;"><i class="ri-save-line"></i> Save Changes</button>
            
            <?php if ($isDefault): ?>
              <button type="button"
                      onclick="if(confirm('Reset button \'<?= addslashes(h($btn['label'])) ?>\' back to its original default settings?')){ document.getElementById('action-btn-type-<?= (int)$btn['id'] ?>').value='reset_default'; document.getElementById('action-btn-form-<?= (int)$btn['id'] ?>').submit(); }"
                      style="background:#f3f4f6;color:var(--text);border:1px solid var(--border);cursor:pointer;">
                <i class="ri-restart-line"></i> Reset to Default
              </button>
            <?php else: ?>
              <button type="button"
                      onclick="if(confirm('Delete custom button \'<?= addslashes(h($btn['label'])) ?>\' permanently?')){ document.getElementById('action-btn-type-<?= (int)$btn['id'] ?>').value='delete'; document.getElementById('action-btn-form-<?= (int)$btn['id'] ?>').submit(); }"
                      style="background:#b91c1c;color:#fff;cursor:pointer;">
                <i class="ri-delete-bin-line"></i> Delete Button
              </button>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </details>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<!-- ====================================================================
     Add New CTA Button
===================================================================== -->
<div class="panel">
  <h3><i class="ri-add-circle-line"></i> Add New CTA Button to "<?= h($selPage['title']) ?>"</h3>
  <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1rem;">
    Create a new button and choose whether it navigates to the <strong>Contact Us page</strong> or opens the <strong>RFQ Popup Modal</strong> on any page or blog.
  </p>
  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="add">
    <input type="hidden" name="page_id"    value="<?= (int)($selPage['id'] ?? 0) ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:0.85rem;">
      <label>Button Label / Text
        <input type="text" name="label" placeholder="e.g. Request a Quote / Contact Our Team" required>
      </label>

      <label>Button Action / Click Behavior
        <select name="action_type" style="font-weight:700;">
          <option value="link">🌐 Navigate to Page URL (e.g. contact.php)</option>
          <option value="popup">⚡ Open RFQ Quote Popup Modal</option>
        </select>
      </label>

      <label>Target Page URL (for normal links, e.g. <code>contact.php</code>)
        <input type="text" name="url" placeholder="contact.php" value="contact.php">
      </label>

      <label>Button Style / Theme
        <select name="style">
          <?php foreach ($styles as $s => $sLabel): ?>
            <option value="<?= h($s) ?>"><?= h($sLabel) ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Icon Class <span style="color:var(--muted);font-weight:400;">(RemixIcon, e.g. <code>ri-mail-send-line</code>)</span>
        <input type="text" name="icon" placeholder="ri-mail-send-line">
      </label>

      <label>Placement Zone Key <span style="color:var(--muted);font-weight:400;">(e.g. <code>hero</code>, <code>banner</code>, <code>bottom</code>)</span>
        <input type="text" name="placement" placeholder="hero" value="hero" required pattern="[a-z0-9_\-]+">
      </label>

      <label>Sort Order Sequence
        <input type="number" name="sort_order" value="10" min="0" step="10">
      </label>
    </div>

    <button type="submit" style="background:var(--teal);cursor:pointer;"><i class="ri-add-line"></i> Create CTA Button</button>
  </form>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
