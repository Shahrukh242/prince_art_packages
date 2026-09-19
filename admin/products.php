<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$saved = false;
$error = '';

// Seed standard products if table is empty
$productCount = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
if ($productCount === 0) {
    $seedProducts = [
        ['Printed Cartons', 'folding-cartons', 'Secondary Packaging', 'Reverse tuck, crash lock, tamper-evident, and child-resistant cartons printed on food & pharma-grade virgin board.', 'assets/images/prod_cartons.jpg', 1],
        ['Leaf-Inserts', 'leaf-inserts', 'Patient Information', 'Ultra-thin 27gsm to 60gsm prescribing information inserts, cross-folded or miniature outserts for automated packaging lines.', 'assets/images/prod_leaflets.jpg', 1],
        ['Printed Labels', 'printed-labels', 'Container Labeling', 'Precision bottle & container labels for vials, bottles, ampoules, and destructible tamper-evident security seals with 2D barcode serialization.', 'assets/images/prod_labels.jpg', 1],
        ['Honeycomb Separators', 'honeycomb-separators', 'Protective Partitions', 'Protective cardboard honeycomb dividers and grid partitions designed to safeguard glass ampoules and liquid vials.', 'assets/images/prod_honeycomb.jpg', 1],
        ['Pill-Folders', 'pill-folders', 'Dose Adherence', 'Paperboard medicine packaging wallets with integrated dose-tracking calendar compartments engineered to support patient adherence.', 'assets/images/prod_pill_folders.jpg', 1],
        ['Tamper Evident Cartons & Labels', 'tamper-evident', 'Security Seals', 'Destructible security seals and tamper-evident carton structures that provide immediate, irreversible visual evidence.', 'assets/images/prod_tamper_labels.jpg', 1],
        ['3D-ENGRAVIX™', '3d-engravix', 'Optical Anti-Counterfeit', 'Proprietary micro-structured optical security feature integrated directly onto printed cartons for instant visual authentication.', 'assets/images/engravix.jpg', 1],
        ['ColdSeal Blister Wallet', 'coldseal-blister-wallet', 'Pressure-Sealed Eco Packaging', 'Pressure-sealed (non-heat-sealed) paperboard blister packaging wallet encapsulating blister cards without heat.', 'assets/images/coldseal.jpg', 1],
    ];

    $insStmt = $pdo->prepare("INSERT INTO products (name, slug, category, short_description, description, image_path, is_published) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($seedProducts as $sp) {
        $insStmt->execute([$sp[0], $sp[1], $sp[2], $sp[3], $sp[3], $sp[4], $sp[5]]);
    }
}

/* -----------------------------------------------------------------------
   POST Handlers (Add, Edit, Delete)
----------------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';

        // Add Product
        if ($action === 'add') {
            $name     = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $desc     = trim($_POST['description'] ?? '');
            $image    = trim($_POST['image_path'] ?? '');
            $slug     = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

            if ($name !== '') {
                $stmt = $pdo->prepare("INSERT INTO products (name, slug, category, short_description, description, image_path, is_published) VALUES (?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$name, $slug, $category, $desc, $desc, $image]);
                $saved = 'Product "' . htmlspecialchars($name) . '" added successfully.';
            } else {
                $error = 'Product Name is required.';
            }
        }

        // Update Product
        elseif ($action === 'update') {
            $id       = (int)$_POST['product_id'];
            $name     = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $desc     = trim($_POST['description'] ?? '');
            $image    = trim($_POST['image_path'] ?? '');
            $isPub    = isset($_POST['is_published']) ? 1 : 0;

            if ($name !== '') {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, short_description = ?, description = ?, image_path = ?, is_published = ? WHERE id = ?");
                $stmt->execute([$name, $category, $desc, $desc, $image, $isPub, $id]);
                $saved = 'Product updated successfully.';
            } else {
                $error = 'Product Name cannot be empty.';
            }
        }

        // Delete Product
        elseif ($action === 'delete') {
            $id = (int)$_POST['product_id'];
            $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
            $saved = 'Product deleted successfully.';
        }
    }
}

$products = $pdo->query("SELECT * FROM products ORDER BY id ASC")->fetchAll();
$allMedia = get_all_media();
?>

<div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div>
    <h1><i class="ri-box-3-line"></i> Product Catalog</h1>
    <p class="page-subtitle">Manage individual pharmaceutical products, categories, descriptions, and packaging images.</p>
  </div>
  <a href="<?= public_url('products') ?>" target="_blank"
     style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.55rem 1.1rem;background:var(--navy);border-radius:8px;font-size:0.85rem;font-weight:600;color:#fff;text-decoration:none;">
    <i class="ri-external-link-line"></i> View Products Page
  </a>
</div>

<?php if ($saved): ?><p class="success-msg"><i class="ri-check-line"></i> <?= $saved ?></p><?php endif; ?>
<?php if ($error):  ?><p class="error-msg"><i class="ri-error-warning-line"></i> <?= h($error) ?></p><?php endif; ?>

<!-- Products List -->
<div class="panel" style="margin-bottom:1.5rem;">
  <h3>
    <i class="ri-list-check"></i> All Products
    <span style="margin-left:auto;font-size:0.8rem;font-weight:400;color:var(--muted);"><?= count($products) ?> item<?= count($products) !== 1 ? 's' : '' ?></span>
  </h3>

  <?php if (empty($products)): ?>
    <p style="color:var(--muted);font-size:0.9rem;">No products in catalog yet. Add one below.</p>
  <?php else: ?>
    <?php foreach ($products as $p): ?>
    <details style="border:1px solid var(--border);border-radius:8px;margin-bottom:0.75rem;background:#fff;">
      <summary style="padding:0.85rem 1.1rem;cursor:pointer;display:flex;align-items:center;gap:0.75rem;font-weight:600;font-size:0.9rem;list-style:none;user-select:none;flex-wrap:wrap;">
        <span style="color:var(--navy);font-weight:700;font-size:0.95rem;"><?= h($p['name']) ?></span>
        <span style="font-size:0.75rem;color:var(--teal);background:rgba(28,124,140,0.1);padding:2px 8px;border-radius:20px;font-weight:600;"><?= h($p['category'] ?: 'Uncategorized') ?></span>
        <?php if (!$p['is_published']): ?>
          <span style="font-size:0.72rem;background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:20px;font-weight:700;">Draft</span>
        <?php else: ?>
          <span style="font-size:0.72rem;background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:20px;font-weight:700;">Active</span>
        <?php endif; ?>

        <span style="flex:1;max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.82rem;font-weight:400;color:var(--muted);margin-left:0.5rem;">
          <?= h($p['short_description']) ?>
        </span>

        <i class="ri-arrow-down-s-line" style="margin-left:auto;color:var(--muted);"></i>
      </summary>

      <div style="padding:1.25rem 1.25rem 1.5rem;border-top:1px solid var(--border);background:#fafbfc;">
        <form method="post" class="content-form" style="margin-bottom:0.5rem;">
          <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
          <input type="hidden" name="action"     value="update">
          <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:0.85rem;">
            <label>Product Name <sup style="color:var(--teal);">*</sup>
              <input type="text" name="name" value="<?= h($p['name']) ?>" required>
            </label>
            <label>Category
              <input type="text" name="category" value="<?= h($p['category']) ?>" placeholder="e.g. Secondary Packaging">
            </label>
          </div>

          <label style="margin-bottom:0.85rem;">Description
            <textarea name="description" rows="3" style="width:100%;padding:0.65rem 0.8rem;border:1.5px solid var(--border);border-radius:7px;font-family:inherit;font-size:0.9rem;"><?= h($p['description']) ?></textarea>
          </label>

          <label style="margin-bottom:0.85rem;">Product Image Path
            <input type="text" name="image_path" value="<?= h($p['image_path']) ?>" placeholder="assets/images/prod_cartons.jpg">
          </label>

          <label style="display:inline-flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;font-size:0.88rem;font-weight:600;">
            <input type="checkbox" name="is_published" <?= $p['is_published'] ? 'checked' : '' ?>>
            Published (Visible in catalog)
          </label>

          <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <button type="submit" style="background:var(--teal);"><i class="ri-save-line"></i> Save Changes</button>
            <a href="<?= public_url('product/' . urlencode($p['slug'])) ?>" target="_blank" style="padding:0.45rem 0.85rem; border:1px solid var(--teal); color:var(--teal); border-radius:6px; font-size:0.85rem; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.35rem; background:#fff;">
              <i class="ri-external-link-line"></i> View Specification Page
            </a>
            <button type="button"
                    onclick="if(confirm('Delete product \'<?= addslashes(h($p['name'])) ?>\' permanently?')){ document.getElementById('del-prod-<?= (int)$p['id'] ?>').submit(); }"
                    style="background:#b91c1c;">
              <i class="ri-delete-bin-line"></i> Delete Product
            </button>
          </div>
        </form>

        <form method="post" id="del-prod-<?= (int)$p['id'] ?>" style="display:none;">
          <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
          <input type="hidden" name="action"     value="delete">
          <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
        </form>
      </div>
    </details>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- Add New Product -->
<div class="panel">
  <h3><i class="ri-add-circle-line"></i> Add New Product</h3>
  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="add">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
      <label>Product Name <sup style="color:var(--teal);">*</sup>
        <input type="text" name="name" placeholder="e.g. Child-Resistant Blister Wallets" required>
      </label>
      <label>Category
        <input type="text" name="category" placeholder="e.g. Dose Adherence">
      </label>
    </div>

    <label style="margin-bottom:1rem;">Description
      <textarea name="description" rows="3" placeholder="Brief technical specifications and applications"></textarea>
    </label>

    <label style="margin-bottom:1rem;">Image Path
      <input type="text" name="image_path" placeholder="assets/images/filename.jpg">
    </label>

    <button type="submit"><i class="ri-add-line"></i> Add Product</button>
  </form>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
