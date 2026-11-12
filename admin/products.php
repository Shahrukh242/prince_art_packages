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
        ['Slider Pack', 'slider-pack', 'Sliding Drawer Carton', 'Two-piece sliding drawer carton with an outer sleeve and inner pull-out tray for ergonomic patient access and GS1 serialization.', 'assets/images/prod_slider_pack.jpg', 1],
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

            $metaTitle = trim($_POST['meta_title'] ?? '');
            $metaDesc  = trim($_POST['meta_description'] ?? '');

            if ($name !== '') {
                $stmt = $pdo->prepare("INSERT INTO products (name, slug, category, short_description, description, image_path, meta_title, meta_description, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$name, $slug, $category, $desc, $desc, $image, $metaTitle ?: null, $metaDesc ?: null]);
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

            $metaTitle = trim($_POST['meta_title'] ?? '');
            $metaDesc  = trim($_POST['meta_description'] ?? '');

            if ($name !== '') {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, short_description = ?, description = ?, image_path = ?, meta_title = ?, meta_description = ?, is_published = ? WHERE id = ?");
                $stmt->execute([$name, $category, $desc, $desc, $image, $metaTitle ?: null, $metaDesc ?: null, $isPub, $id]);
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

        // Add Media to Library
        elseif ($action === 'add_media') {
            $productId = (int)($_POST['product_id'] ?? 0);
            $mediaType = in_array($_POST['media_type'] ?? '', ['image', 'video'], true) ? $_POST['media_type'] : 'image';
            $mediaUrl  = trim($_POST['media_url'] ?? '');
            $title     = trim($_POST['title'] ?? '');
            $sortOrder = (int)($_POST['sort_order'] ?? 10);

            if ($productId > 0 && $mediaUrl !== '') {
                if ($mediaType === 'video') {
                    $ytId = extract_youtube_id($mediaUrl);
                    if (!$ytId) {
                        $error = 'Please enter a valid YouTube video URL (e.g. https://www.youtube.com/watch?v=...)';
                    }
                }
                if (empty($error)) {
                    $stmt = $pdo->prepare("INSERT INTO product_media (product_id, media_type, media_url, title, sort_order) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$productId, $mediaType, $mediaUrl, $title, $sortOrder]);
                    $saved = 'Media item added to product library successfully.';
                }
            } else {
                $error = 'Media URL / Path is required.';
            }
        }

        // Delete Media from Library
        elseif ($action === 'delete_media') {
            $mediaId = (int)($_POST['media_id'] ?? 0);
            if ($mediaId > 0) {
                $pdo->prepare("DELETE FROM product_media WHERE id = ?")->execute([$mediaId]);
                $saved = 'Media item deleted from product library.';
            }
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
  <a href="../public_site/products.php" target="_blank"
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

          <div style="margin-bottom:1rem; padding:0.85rem; background:#fff; border:1px solid var(--border); border-radius:8px;">
            <label style="margin:0 0 0.5rem 0; font-weight:700; font-size:0.85rem; color:var(--navy); display:block;">Primary Product Picture</label>
            <div style="display:flex; gap:1rem; align-items:center;">
              <?php if (!empty($p['image_path'])): ?>
                <img src="../public_site/<?= h($p['image_path']) ?>" alt="<?= h($p['name']) ?>" style="width:75px; height:55px; object-fit:cover; border-radius:6px; border:1px solid var(--border); flex-shrink:0;">
              <?php else: ?>
                <div style="width:75px; height:55px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center; color:var(--muted); flex-shrink:0;"><i class="ri-image-line" style="font-size:1.5rem;"></i></div>
              <?php endif; ?>
              <div style="flex:1;">
                <input type="text" name="image_path" value="<?= h($p['image_path']) ?>" placeholder="assets/images/prod_cartons.jpg" style="width:100%; font-size:0.85rem;">
                <span style="font-size:0.75rem; color:var(--muted); display:block; margin-top:3px;">Path to primary image displayed in catalog and specification cards.</span>
              </div>
            </div>
          </div>

          <!-- Product SEO Meta Tags -->
          <div style="margin-bottom:1.25rem; padding:0.85rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; border-left:3px solid var(--teal);">
            <div style="font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem; display:flex; align-items:center; gap:0.35rem;">
              <i class="ri-search-eye-line text-teal"></i> Product SEO Meta Tags (Optional)
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.25rem;">
              <label style="font-size:0.8rem; font-weight:600; color:var(--navy); margin:0;">SEO Meta Title
                <input type="text" name="meta_title" value="<?= h($p['meta_title'] ?? '') ?>" placeholder="<?= h($p['name']) ?> Specifications | Prince Art Packages" maxlength="160" style="margin-top:2px; font-size:0.85rem; padding:0.45rem 0.6rem; width:100%;">
                <span style="font-size:0.72rem; color:var(--muted); display:block; margin-top:2px;">Defaults to "<?= h($p['name']) ?> Specifications | Prince Art Packages"</span>
              </label>
              <label style="font-size:0.8rem; font-weight:600; color:var(--navy); margin:0;">SEO Meta Description
                <textarea name="meta_description" rows="2" placeholder="Technical specifications, customizations, and standards for <?= h($p['name']) ?>..." maxlength="300" style="margin-top:2px; font-size:0.85rem; padding:0.45rem 0.6rem; width:100%;"><?= h($p['meta_description'] ?? '') ?></textarea>
                <span style="font-size:0.72rem; color:var(--muted); display:block; margin-top:2px;">Defaults to standard technical description.</span>
              </label>
            </div>
          </div>

          <label style="display:inline-flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;font-size:0.88rem;font-weight:600;">
            <input type="checkbox" name="is_published" <?= $p['is_published'] ? 'checked' : '' ?>>
            Published (Visible in catalog)
          </label>

          <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap; margin-bottom:1.5rem;">
            <button type="submit" style="background:var(--teal);"><i class="ri-save-line"></i> Save Product Details</button>
            <a href="../public_site/product-detail.php?slug=<?= urlencode($p['slug']) ?>" target="_blank" style="padding:0.45rem 0.85rem; border:1px solid var(--teal); color:var(--teal); border-radius:6px; font-size:0.85rem; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.35rem; background:#fff;">
              <i class="ri-external-link-line"></i> View Specification Page
            </a>
            <button type="button"
                    onclick="if(confirm('Delete product \'<?= addslashes(h($p['name'])) ?>\' permanently?')){ document.getElementById('del-prod-<?= (int)$p['id'] ?>').submit(); }"
                    style="background:#b91c1c;">
              <i class="ri-delete-bin-line"></i> Delete Product
            </button>
          </div>
        </form>

        <?php 
          $prodMedia = get_product_media((int)$p['id']); 
        ?>
        <!-- Product Media & Video Library Section -->
        <div style="padding-top:1.25rem; border-top:1.5px dashed var(--border);">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; flex-wrap:wrap; gap:0.5rem;">
            <h4 style="margin:0; font-size:0.98rem; color:var(--navy); display:flex; align-items:center; gap:0.45rem;">
              <i class="ri-movie-2-line" style="color:var(--teal); font-size:1.15rem;"></i> Product Image &amp; Video Library
              <span style="font-size:0.75rem; font-weight:600; color:var(--teal); background:rgba(0,168,150,0.1); padding:2px 8px; border-radius:12px;"><?= count($prodMedia) ?> item<?= count($prodMedia) !== 1 ? 's' : '' ?></span>
            </h4>
            <span style="font-size:0.75rem; color:var(--muted);">Add YouTube demonstration videos or high-resolution gallery images.</span>
          </div>

          <?php if (!empty($prodMedia)): ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(210px, 1fr)); gap:0.85rem; margin-bottom:1.25rem;">
              <?php foreach ($prodMedia as $pm): ?>
                <div style="background:#ffffff; border:1px solid var(--border); border-radius:8px; padding:0.6rem; display:flex; flex-direction:column; gap:0.45rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                  <?php if ($pm['media_type'] === 'video'): 
                    $ytThumb = get_youtube_thumbnail_url($pm['media_url']);
                  ?>
                    <div style="position:relative; aspect-ratio:16/9; background:#0f172a; border-radius:6px; overflow:hidden;">
                      <?php if ($ytThumb): ?>
                        <img src="<?= h($ytThumb) ?>" alt="<?= h($pm['title'] ?: 'YouTube Video') ?>" style="width:100%; height:100%; object-fit:cover;">
                      <?php endif; ?>
                      <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.35); color:#fff; font-size:1.6rem;">
                        <i class="ri-youtube-fill" style="color:#ef4444;"></i>
                      </div>
                      <span style="position:absolute; top:4px; left:4px; background:#dc2626; color:#fff; font-size:0.65rem; font-weight:700; padding:2px 6px; border-radius:4px; text-transform:uppercase; letter-spacing:0.04em;">YouTube</span>
                    </div>
                  <?php else: ?>
                    <div style="aspect-ratio:16/9; background:#f1f5f9; border-radius:6px; overflow:hidden;">
                      <img src="../public_site/<?= h($pm['media_url']) ?>" alt="<?= h($pm['title'] ?: 'Product Image') ?>" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                  <?php endif; ?>

                  <div style="font-size:0.82rem; font-weight:600; color:var(--navy); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= h($pm['title']) ?>">
                    <?= h($pm['title'] ?: ($pm['media_type'] === 'video' ? 'YouTube Video' : 'Gallery Image')) ?>
                  </div>
                  <div style="font-size:0.72rem; color:var(--muted); word-break:break-all; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    <?= h($pm['media_url']) ?>
                  </div>

                  <div style="margin-top:auto; padding-top:0.4rem; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:0.72rem; color:var(--muted);"><i class="ri-sort-asc"></i> Order: <?= (int)$pm['sort_order'] ?></span>
                    <form method="post" style="margin:0;" onsubmit="return confirm('Remove this media item from product library?');">
                      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                      <input type="hidden" name="action" value="delete_media">
                      <input type="hidden" name="media_id" value="<?= (int)$pm['id'] ?>">
                      <button type="submit" style="background:transparent; border:none; color:#dc2626; cursor:pointer; font-size:0.78rem; font-weight:600; padding:2px 4px; display:inline-flex; align-items:center; gap:2px;">
                        <i class="ri-delete-bin-line"></i> Remove
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p style="font-size:0.84rem; color:var(--muted); margin-bottom:1rem; font-style:italic;">No additional media or YouTube videos added for this product yet.</p>
          <?php endif; ?>

          <!-- Inline Add Media Form -->
          <form method="post" style="background:#ffffff; border:1px solid var(--border); border-radius:8px; padding:0.9rem; display:grid; grid-template-columns:130px 1.2fr 1fr 80px auto; gap:0.75rem; align-items:end;">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="action" value="add_media">
            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">

            <label style="margin:0; font-size:0.78rem; font-weight:700; color:var(--navy);">Media Type
              <select name="media_type" style="width:100%; padding:0.45rem; border:1px solid var(--border); border-radius:6px; font-size:0.85rem; margin-top:3px; background:#fff;">
                <option value="image">Image File</option>
                <option value="video">YouTube Video</option>
              </select>
            </label>

            <label style="margin:0; font-size:0.78rem; font-weight:700; color:var(--navy);">Image Path or YouTube Link *
              <input type="text" name="media_url" placeholder="assets/images/... OR https://www.youtube.com/watch?v=..." required style="margin-top:3px; font-size:0.85rem; padding:0.45rem 0.6rem; width:100%;">
            </label>

            <label style="margin:0; font-size:0.78rem; font-weight:700; color:var(--navy);">Title / Description
              <input type="text" name="title" placeholder="e.g. Automated Folding Demonstration" style="margin-top:3px; font-size:0.85rem; padding:0.45rem 0.6rem; width:100%;">
            </label>

            <label style="margin:0; font-size:0.78rem; font-weight:700; color:var(--navy);">Order
              <input type="number" name="sort_order" value="10" style="margin-top:3px; font-size:0.85rem; padding:0.45rem 0.4rem; width:100%;">
            </label>

            <button type="submit" style="background:var(--navy); color:#fff; padding:0.52rem 0.95rem; font-size:0.84rem; white-space:nowrap; border-radius:6px; font-weight:600; cursor:pointer;">
              <i class="ri-add-line"></i> Add to Library
            </button>
          </form>
        </div>

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

    <div style="margin-bottom:1rem; padding:0.85rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; border-left:3px solid var(--teal);">
      <div style="font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem; display:flex; align-items:center; gap:0.35rem;">
        <i class="ri-search-eye-line text-teal"></i> SEO Meta Tags (Optional)
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
        <label style="font-size:0.8rem; font-weight:600; color:var(--navy); margin:0;">SEO Meta Title
          <input type="text" name="meta_title" placeholder="e.g. Product Name | Prince Art Packages" maxlength="160" style="margin-top:2px; font-size:0.85rem; width:100%;">
        </label>
        <label style="font-size:0.8rem; font-weight:600; color:var(--navy); margin:0;">SEO Meta Description
          <textarea name="meta_description" rows="2" placeholder="Brief search snippet summary..." maxlength="300" style="margin-top:2px; font-size:0.85rem; width:100%;"></textarea>
        </label>
      </div>
    </div>

    <button type="submit"><i class="ri-add-line"></i> Add Product</button>
  </form>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
