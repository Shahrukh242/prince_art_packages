<?php
require __DIR__ . '/includes/layout_top.php';
require_once __DIR__ . '/../includes/page_blocks_manifest.php';
$pdo = get_db();

/* -----------------------------------------------------------------------
   Helpers & Page Tabs (ordered logically)
----------------------------------------------------------------------- */
$pages = $pdo->query("
    SELECT id, slug, title 
    FROM pages 
    ORDER BY FIELD(slug, 'home', 'about', 'products', 'capabilities', 'innovation', 'quality', 'industries', 'case-studies', 'contact', 'blog', 'privacy', 'terms'), title ASC
")->fetchAll();

$selectedSlug = $_GET['page'] ?? ($pages[0]['slug'] ?? 'home');

// Auto-heal / Ensure standard default blocks exist in database for this page
ensure_page_default_blocks($pdo, $selectedSlug);

// Map of default blocks for the selected page
$allDefaultBlocks = get_all_page_default_blocks();
$pageDefaultMap = [];
if (isset($allDefaultBlocks[$selectedSlug])) {
    foreach ($allDefaultBlocks[$selectedSlug] as [$k, $t, $c, $s]) {
        $pageDefaultMap[$k] = ['type' => $t, 'content' => $c, 'sort' => $s];
    }
}

$saved = false;
$error = '';

/* -----------------------------------------------------------------------
   POST Handlers
----------------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $action = $_POST['action'];
        $pageId = (int)($_POST['page_id'] ?? 0);

        // --- Update SEO meta ---
        if ($action === 'save_seo') {
            $stmt = $pdo->prepare("UPDATE pages SET meta_title = ?, meta_description = ? WHERE id = ?");
            $stmt->execute([$_POST['meta_title'] ?? '', $_POST['meta_description'] ?? '', $pageId]);
            $saved = 'SEO metadata updated successfully.';

        // --- Save / update a single block ---
        } elseif ($action === 'save_block') {
            $blockKey   = trim($_POST['block_key']   ?? '');
            $blockType  = $_POST['block_type']  ?? 'text';
            $content    = $_POST['content']     ?? '';
            $sortOrder  = (int)($_POST['sort_order'] ?? 0);

            if ($blockKey !== '') {
                $stmt = $pdo->prepare(
                    "INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
                     VALUES (?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE block_type = VALUES(block_type),
                                             content    = VALUES(content),
                                             sort_order = VALUES(sort_order)"
                );
                $stmt->execute([$pageId, $blockKey, $blockType, $content, $sortOrder]);
                $saved = 'Block "' . htmlspecialchars($blockKey) . '" saved successfully.';
            } else {
                $error = 'Block Key cannot be empty.';
            }

        // --- Reset a single block to its default text ---
        } elseif ($action === 'reset_block_default') {
            $blockKey = trim($_POST['block_key'] ?? '');
            if ($blockKey !== '' && isset($pageDefaultMap[$blockKey])) {
                $def = $pageDefaultMap[$blockKey];
                $stmt = $pdo->prepare(
                    "INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
                     VALUES (?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE block_type = VALUES(block_type),
                                             content    = VALUES(content),
                                             sort_order = VALUES(sort_order)"
                );
                $stmt->execute([$pageId, $blockKey, $def['type'], $def['content'], $def['sort']]);
                $saved = 'Block "' . htmlspecialchars($blockKey) . '" has been reset to its default text.';
            }

        // --- Restore all default blocks for this page ---
        } elseif ($action === 'restore_page_defaults') {
            $count = restore_page_default_blocks($pdo, $selectedSlug);
            $saved = "Restored {$count} default content blocks for this page successfully!";

        // --- Delete a custom block ---
        } elseif ($action === 'delete_block') {
            $blockId  = (int)($_POST['block_id'] ?? 0);
            $blockKey = trim($_POST['block_key'] ?? '');
            
            // If it's a default system block, reset it rather than destroying it
            if (isset($pageDefaultMap[$blockKey])) {
                $def = $pageDefaultMap[$blockKey];
                $stmt = $pdo->prepare("UPDATE content_blocks SET content = ?, block_type = ?, sort_order = ? WHERE page_id = ? AND block_key = ?");
                $stmt->execute([$def['content'], $def['type'], $def['sort'], $pageId, $blockKey]);
                $saved = 'Standard block "' . htmlspecialchars($blockKey) . '" was reset to its default content.';
            } else {
                if ($blockId > 0) {
                    $pdo->prepare("DELETE FROM content_blocks WHERE id = ? AND page_id = ?")->execute([$blockId, $pageId]);
                }
                $saved = 'Custom block deleted successfully.';
            }
        }
    }
}

/* -----------------------------------------------------------------------
   Load selected page + its blocks
----------------------------------------------------------------------- */
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ? LIMIT 1");
$stmt->execute([$selectedSlug]);
$page = $stmt->fetch();

$blocks = [];
if ($page) {
    $stmt = $pdo->prepare(
        "SELECT id, block_key, block_type, content, sort_order
         FROM content_blocks
         WHERE page_id = ?
         ORDER BY sort_order ASC, id ASC"
    );
    $stmt->execute([$page['id']]);
    $blocks = $stmt->fetchAll();
}

$allMedia = get_all_media();
$previewUrl = ($selectedSlug === 'home') ? 'index.php' : h($selectedSlug) . '.php';
?>

<div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div>
    <h1><i class="ri-file-text-line"></i> Page Content</h1>
    <p class="page-subtitle">Edit any text, heading, or image block on any page. You can link any specific phrase to other pages, products, or blogs.</p>
  </div>
  <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
    <!-- Restore Defaults Button -->
    <form method="post" onsubmit="return confirm('Reset all content blocks on the <?= addslashes(h($page['title'] ?? $selectedSlug)) ?> page back to original defaults? Any custom edits on this page will be restored.');" style="display:inline;">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="action"     value="restore_page_defaults">
      <input type="hidden" name="page_id"    value="<?= (int)($page['id'] ?? 0) ?>">
      <button type="submit" style="background:#fff;border:1.5px solid var(--border);color:var(--text);font-size:0.85rem;font-weight:600;padding:0.55rem 0.9rem;cursor:pointer;">
        <i class="ri-restart-line text-teal"></i> Restore Page Defaults
      </button>
    </form>

    <!-- Preview Page Button -->
    <a href="../public_site/<?= $previewUrl ?>" target="_blank"
       style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.55rem 1.1rem;background:var(--navy);border-radius:8px;font-size:0.85rem;font-weight:600;color:#fff;text-decoration:none;">
      <i class="ri-external-link-line"></i> Preview "<?= h($page['title'] ?? $selectedSlug) ?>" Page
    </a>
  </div>
</div>

<!-- Page tabs -->
<div class="page-tabs">
  <?php foreach ($pages as $p): ?>
    <?php
      $isActive = ($p['slug'] === $selectedSlug);
      $bCount = (int)$pdo->query("SELECT COUNT(*) FROM content_blocks WHERE page_id = {$p['id']}")->fetchColumn();
    ?>
    <a href="content.php?page=<?= urlencode($p['slug']) ?>" class="tab-link <?= $isActive ? 'active' : '' ?>">
      <?= h($p['title']) ?>
      <span class="badge" style="background:<?= $isActive ? 'rgba(255,255,255,0.25)' : 'var(--bg)' ?>; color:<?= $isActive ? '#fff' : 'var(--text-muted)' ?>;"><?= $bCount ?></span>
    </a>
  <?php endforeach; ?>
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
     Section: Existing Blocks for this Page
===================================================================== -->
<div class="panel" style="margin-bottom:2rem;">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.5rem;">
    <h2 style="font-size:1.2rem;margin:0;">
      Content Blocks on "<?= h($page['title'] ?? ucfirst($selectedSlug)) ?>" 
      <span style="font-size:0.85rem;font-weight:400;color:var(--muted);">(<?= count($blocks) ?> blocks)</span>
    </h2>
    <span style="font-size:0.82rem;color:var(--muted);">Click any block to expand and edit. Use <strong>"Link Text to Page"</strong> to insert hyperlinks.</span>
  </div>

  <?php if (empty($blocks)): ?>
    <p style="color:var(--muted); text-align:center; padding:2rem 0;">No blocks defined for this page yet. Use the form below to add one.</p>
  <?php endif; ?>

  <?php foreach ($blocks as $block): ?>
  <?php
    $isDefault = isset($pageDefaultMap[$block['block_key']]);
    $defContent = $isDefault ? $pageDefaultMap[$block['block_key']]['content'] : '';
    $isModified = $isDefault && ($block['content'] !== $defContent);
  ?>

  <!-- Standalone action form for Reset / Delete -->
  <form method="post" id="action-form-<?= (int)$block['id'] ?>" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     id="action-type-<?= (int)$block['id'] ?>" value="delete_block">
    <input type="hidden" name="page_id"    value="<?= (int)$page['id'] ?>">
    <input type="hidden" name="block_id"   value="<?= (int)$block['id'] ?>">
    <input type="hidden" name="block_key"  value="<?= h($block['block_key']) ?>">
  </form>

  <details class="block-accordion" style="border:1px solid var(--border);border-radius:8px;margin-bottom:0.75rem;background:#fff;overflow:hidden;">
    <summary style="padding:0.85rem 1.1rem;cursor:pointer;display:flex;align-items:center;gap:0.75rem;list-style:none;user-select:none;font-size:0.92rem;flex-wrap:wrap;">
      <code style="font-weight:700;color:var(--navy);background:var(--bg);padding:2px 8px;border-radius:4px;font-size:0.82rem;"><?= h($block['block_key']) ?></code>
      
      <span class="type-badge" style="font-size:0.72rem;background:#e0f2fe;color:#0369a1;padding:2px 7px;border-radius:4px;font-weight:600;text-transform:uppercase;">
        <?= h($block['block_type']) ?>
      </span>

      <span style="color:var(--text-muted);font-size:0.82rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:380px;">
        <?= h(strip_tags($block['content'])) ?>
      </span>

      <?php if (strpos($block['content'], '<a ') !== false || strpos($block['content'], 'http') !== false): ?>
        <span style="font-size:0.72rem;background:#fef3c7;color:#92400e;padding:1px 6px;border-radius:4px;font-weight:700;">
          <i class="ri-link"></i> Contains Links
        </span>
      <?php endif; ?>

      <?php if ($isModified): ?>
        <span style="font-size:0.72rem;background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:4px;font-weight:700;">Customized</span>
      <?php elseif ($isDefault): ?>
        <span style="font-size:0.72rem;color:var(--muted);background:var(--bg);padding:2px 6px;border-radius:4px;">Default</span>
      <?php else: ?>
        <span style="font-size:0.72rem;background:#d1fae5;color:#065f46;padding:2px 6px;border-radius:4px;font-weight:700;">Custom Block</span>
      <?php endif; ?>

      <i class="ri-arrow-down-s-line" style="margin-left:auto;color:var(--muted);"></i>
    </summary>

    <div style="padding:1.25rem;border-top:1px solid var(--border);background:#fafbfc;">
      <form method="post" class="content-form" style="margin-bottom:0;">
        <input type="hidden" name="csrf_token"  value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="action"       value="save_block">
        <input type="hidden" name="page_id"      value="<?= (int)$page['id'] ?>">
        <input type="hidden" name="block_id"     value="<?= (int)$block['id'] ?>">
        <input type="hidden" name="block_key"    value="<?= h($block['block_key']) ?>">

        <div style="display:flex;gap:1rem;margin-bottom:0.85rem;flex-wrap:wrap;">
          <label style="flex:1;min-width:140px;">Block Type
            <select name="block_type">
              <option value="text"     <?= $block['block_type'] === 'text'     ? 'selected' : '' ?>>Text with Link Support</option>
              <option value="richtext" <?= $block['block_type'] === 'richtext' ? 'selected' : '' ?>>Rich Text</option>
              <option value="image"    <?= $block['block_type'] === 'image'    ? 'selected' : '' ?>>Image (media path)</option>
            </select>
          </label>
          <label style="width:100px;">Sort Order
            <input type="number" name="sort_order" value="<?= (int)$block['sort_order'] ?>" min="0" step="1">
          </label>
        </div>

        <?php if ($block['block_type'] === 'image'): ?>
          <label>Image Source
            <?php $currentPath = $block['content']; ?>
            <?php if ($currentPath): ?>
              <div style="margin:0.5rem 0;"><img src="../public_site/<?= h($currentPath) ?>" style="max-width:220px;border-radius:6px;border:1px solid var(--border);box-shadow:var(--shadow-sm);"></div>
            <?php endif; ?>
            <select name="content">
              <option value="">— Keep current / use default —</option>
              <?php foreach ($allMedia as $m): ?>
                <option value="<?= h($m['filepath']) ?>" <?= $currentPath === $m['filepath'] ? 'selected' : '' ?>><?= h($m['filename']) ?> (<?= h($m['filepath']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </label>
          <p class="field-hint" style="margin-top:0.35rem;">Or type a relative path manually: <input type="text" name="content" value="<?= h($currentPath) ?>" placeholder="assets/images/xyz.jpg" style="margin-top:0.25rem;"></p>
        <?php else: ?>
          <label>Content
            <!-- FORMATTING & LINK TOOLBAR -->
            <div style="background:#f1f5f9; border:1px solid var(--border); border-bottom:none; border-radius:7px 7px 0 0; padding:0.35rem 0.65rem; display:flex; gap:0.4rem; align-items:center; flex-wrap:wrap;">
              <button type="button" onclick="openLinkModal(this)" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.25rem 0.65rem; border-radius:4px; font-size:0.8rem; font-weight:700; color:var(--navy); cursor:pointer; display:inline-flex; align-items:center; gap:0.3rem;">
                <i class="ri-link text-teal" style="font-size:0.95rem;"></i> Link Text to Page
              </button>
              <button type="button" onclick="formatSelection(this, 'strong')" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.25rem 0.5rem; border-radius:4px; font-size:0.8rem; font-weight:700; cursor:pointer;" title="Bold">
                <i class="ri-bold"></i>
              </button>
              <button type="button" onclick="formatSelection(this, 'em')" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.25rem 0.5rem; border-radius:4px; font-size:0.8rem; font-style:italic; cursor:pointer;" title="Italic">
                <i class="ri-italic"></i>
              </button>
              <span style="font-size:0.75rem; color:var(--muted); margin-left:auto;">Select words and click <strong>Link Text to Page</strong> to create hyperlinks</span>
            </div>
            <textarea name="content" id="block_txt_<?= (int)$block['id'] ?>" rows="4" style="border-radius:0 0 7px 7px; width:100%; padding:0.65rem 0.8rem; border:1.5px solid var(--border); font-family:inherit; font-size:0.9rem; line-height:1.6;"><?= h($block['content']) ?></textarea>
          </label>
        <?php endif; ?>

        <div style="display:flex;gap:0.75rem;align-items:center;margin-top:1.25rem;flex-wrap:wrap;">
          <button type="submit" style="background:var(--teal);cursor:pointer;"><i class="ri-save-line"></i> Save Block</button>
          
          <?php if ($isDefault): ?>
            <!-- Reset default button -->
            <button type="button"
                    onclick="if(confirm('Reset block (<?= h($block['block_key']) ?>) back to its original default text?')){ document.getElementById('action-type-<?= (int)$block['id'] ?>').value='reset_block_default'; document.getElementById('action-form-<?= (int)$block['id'] ?>').submit(); }"
                    style="background:#f3f4f6;color:var(--text);border:1px solid var(--border);cursor:pointer;">
              <i class="ri-restart-line"></i> Reset to Default
            </button>
          <?php else: ?>
            <!-- Delete custom block -->
            <button type="button"
                    onclick="if(confirm('Delete custom block (<?= h($block['block_key']) ?>) permanently?')){ document.getElementById('action-type-<?= (int)$block['id'] ?>').value='delete_block'; document.getElementById('action-form-<?= (int)$block['id'] ?>').submit(); }"
                    style="background:#b91c1c;color:#fff;cursor:pointer;">
              <i class="ri-delete-bin-line"></i> Delete Block
            </button>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </details>
  <?php endforeach; ?>
</div>

<!-- ====================================================================
     Add New Block
===================================================================== -->
<div class="panel">
  <h3><i class="ri-add-circle-line"></i> Add New Custom Content Block to "<?= h($page['title']) ?>"</h3>
  <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1rem;">
    Give the block a unique key (e.g. <code>custom_section_intro</code>). The key can be used anywhere in templates with <code>&lt;?= get_content('<?= h($selectedSlug) ?>', 'your_key') ?&gt;</code>.
  </p>
  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="save_block">
    <input type="hidden" name="page_id"    value="<?= (int)$page['id'] ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr 80px;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
      <label>Block Key <span style="color:var(--muted);font-weight:400;">(snake_case, unique per page)</span>
        <input type="text" name="block_key" placeholder="e.g. section_intro" pattern="[a-z0-9_\-]+" required>
      </label>
      <label>Block Type
        <select name="block_type" id="new-block-type">
          <option value="text">Text with Link Support</option>
          <option value="richtext">Rich Text</option>
          <option value="image">Image</option>
        </select>
      </label>
      <label>Sort Order
        <input type="number" name="sort_order" value="<?= (count($blocks) + 1) * 10 ?>" min="0" step="10">
      </label>
    </div>

    <div id="new-block-content-wrap">
      <label>Content
        <div style="background:#f1f5f9; border:1px solid var(--border); border-bottom:none; border-radius:7px 7px 0 0; padding:0.35rem 0.65rem; display:flex; gap:0.4rem; align-items:center;">
          <button type="button" onclick="openLinkModal('new-block-content')" style="background:#ffffff; border:1px solid #cbd5e1; padding:0.25rem 0.65rem; border-radius:4px; font-size:0.8rem; font-weight:700; color:var(--navy); cursor:pointer; display:inline-flex; align-items:center; gap:0.3rem;">
            <i class="ri-link text-teal"></i> Link Text to Page
          </button>
        </div>
        <textarea name="content" id="new-block-content" rows="4" style="border-radius:0 0 7px 7px;" placeholder="Enter text or link words using the Link button above..."></textarea>
      </label>
    </div>

    <button type="submit" style="background:var(--teal);margin-top:1rem;cursor:pointer;"><i class="ri-add-line"></i> Create Block</button>
  </form>
</div>

<!-- ====================================================================
     GLOBAL PAGE LINK INSERTION MODAL COMPONENT
===================================================================== -->
<?php require_once __DIR__ . '/includes/link_modal.php'; ?>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
