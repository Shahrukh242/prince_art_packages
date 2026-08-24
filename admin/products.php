<?php
// admin/products.php — Complete Products CRUD Management
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$message = '';
$error = '';

// Helper to generate slug
function make_slug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text ?: 'n-a');
}

// 1. Handle Form Submissions (Create / Update / Delete / Toggle)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf_token'] ?? null)) {
    $action = $_POST['action'] ?? '';

    // Save (Insert or Update) Product
    if ($action === 'save_product') {
        $id = (int)($_POST['product_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $short_desc = trim($_POST['short_description'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $spec_sheet = trim($_POST['spec_sheet'] ?? '');
        $image_path = trim($_POST['image_path'] ?? '');
        $meta_title = trim($_POST['meta_title'] ?? '');
        $meta_desc = trim($_POST['meta_description'] ?? '');
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = make_slug($name);
        } else {
            $slug = make_slug($slug);
        }

        if (empty($name)) {
            $error = "Product Name is required.";
        } else {
            if ($id > 0) {
                // Update
                $stmt = $pdo->prepare(
                    "UPDATE products SET name = ?, slug = ?, category = ?, short_description = ?, description = ?, spec_sheet = ?, image_path = ?, meta_title = ?, meta_description = ?, is_published = ? WHERE id = ?"
                );
                $stmt->execute([$name, $slug, $category, $short_desc, $desc, $spec_sheet, $image_path, $meta_title, $meta_desc, $is_published, $id]);
                $message = "Product updated successfully.";
            } else {
                // Insert
                $stmt = $pdo->prepare(
                    "INSERT INTO products (name, slug, category, short_description, description, spec_sheet, image_path, meta_title, meta_description, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([$name, $slug, $category, $short_desc, $desc, $spec_sheet, $image_path, $meta_title, $meta_desc, $is_published]);
                $message = "Product added successfully.";
            }
        }
    }

    // Toggle Published Status
    if ($action === 'toggle_publish') {
        $id = (int)($_POST['product_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE products SET is_published = NOT is_published WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Publish status updated.";
        }
    }

    // Delete Product
    if ($action === 'delete_product') {
        $id = (int)($_POST['product_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Product deleted successfully.";
        }
    }
}

// 2. Fetch Product for Editing if requested
$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$editId]);
    $editProduct = $stmt->fetch();
}

// 3. Fetch All Products
$products = $pdo->query("SELECT * FROM products ORDER BY category ASC, name ASC")->fetchAll();
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <h1>Product Catalog Management</h1>
    <?php if (!$editProduct && !isset($_GET['new'])): ?>
        <a href="products.php?new=1" class="btn-primary" style="display:inline-block; padding:0.6rem 1.2rem; background:#0284c7; color:#fff; text-decoration:none; border-radius:6px; font-weight:600;">+ Add New Product</a>
    <?php endif; ?>
</div>

<?php if ($message): ?><p class="success-msg"><?= h($message) ?></p><?php endif; ?>
<?php if ($error): ?><p class="error-msg" style="color:#ef4444; background:#fef2f2; padding:0.75rem; border-radius:6px; margin-bottom:1rem;"><?= h($error) ?></p><?php endif; ?>

<?php if ($editProduct || isset($_GET['new'])): ?>
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:1.5rem; margin-bottom:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <h2><?= $editProduct ? 'Edit Product: ' . h($editProduct['name']) : 'Add New Product' ?></h2>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="action" value="save_product">
            <?php if ($editProduct): ?>
                <input type="hidden" name="product_id" value="<?= (int)$editProduct['id'] ?>">
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom:1rem;">
                <label style="display:block; font-weight:600; font-size:0.9rem;">Product Name *
                    <input type="text" name="name" value="<?= h($editProduct['name'] ?? '') ?>" required style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px;">
                </label>
                <label style="display:block; font-weight:600; font-size:0.9rem;">Category
                    <select name="category" style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px;">
                        <?php 
                        $cats = ['Folding Cartons', 'Cold-Seal Packaging', 'Leaf-Inserts & Outserts', 'Printed Labels', 'Anti-Counterfeit'];
                        $curCat = $editProduct['category'] ?? 'Folding Cartons';
                        foreach ($cats as $c): ?>
                            <option value="<?= h($c) ?>" <?= $curCat === $c ? 'selected' : '' ?>><?= h($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom:1rem;">
                <label style="display:block; font-weight:600; font-size:0.9rem;">Slug (URL Identifier)
                    <input type="text" name="slug" value="<?= h($editProduct['slug'] ?? '') ?>" placeholder="auto-generated-if-blank" style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px;">
                </label>
                <label style="display:block; font-weight:600; font-size:0.9rem;">Image Asset Path
                    <input type="text" name="image_path" value="<?= h($editProduct['image_path'] ?? '') ?>" placeholder="assets/images/prod_cartons.jpg" style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px;">
                </label>
            </div>

            <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:1rem;">Short Description (Summary)
                <input type="text" name="short_description" value="<?= h($editProduct['short_description'] ?? '') ?>" style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px;">
            </label>

            <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:1rem;">Full Specifications / Description
                <textarea name="description" rows="5" style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px; font-family:inherit;"><?= h($editProduct['description'] ?? '') ?></textarea>
            </label>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom:1rem;">
                <label style="display:block; font-weight:600; font-size:0.9rem;">Meta Title (SEO)
                    <input type="text" name="meta_title" value="<?= h($editProduct['meta_title'] ?? '') ?>" maxlength="160" style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px;">
                </label>
                <label style="display:block; font-weight:600; font-size:0.9rem;">Meta Description (SEO)
                    <input type="text" name="meta_description" value="<?= h($editProduct['meta_description'] ?? '') ?>" maxlength="300" style="width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #cbd5e1; border-radius:4px;">
                </label>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="font-weight:600; font-size:0.9rem; cursor:pointer;">
                    <input type="checkbox" name="is_published" value="1" <?= (!isset($editProduct) || !empty($editProduct['is_published'])) ? 'checked' : '' ?>> Published (Visible on Public Website)
                </label>
            </div>

            <div style="display:flex; gap:1rem;">
                <button type="submit" style="padding:0.75rem 1.5rem; background:#0284c7; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Save Product</button>
                <a href="products.php" style="padding:0.75rem 1.5rem; background:#e2e8f0; color:#334155; text-decoration:none; border-radius:6px; font-weight:600; display:inline-block;">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<table class="data-table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Category</th>
            <th>Short Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><strong><?= h($p['name']) ?></strong><br><small style="color:#64748b;"><?= h($p['slug']) ?></small></td>
            <td><?= h($p['category']) ?></td>
            <td><?= h($p['short_description']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="toggle_publish">
                    <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                    <button type="submit" style="background:none; border:none; cursor:pointer; font-weight:600; padding:0; color:<?= $p['is_published'] ? '#166534' : '#92400e' ?>;">
                        <?= $p['is_published'] ? '● Published' : '○ Draft' ?>
                    </button>
                </form>
            </td>
            <td>
                <a href="products.php?edit=<?= (int)$p['id'] ?>" style="color:#0284c7; text-decoration:none; font-weight:600; margin-right:0.75rem;">Edit</a>
                <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                    <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer; font-weight:600; padding:0;">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($products)): ?>
        <tr><td colspan="5" style="text-align:center; color:#64748b; padding:2rem;">No products in database yet. Click "+ Add New Product" above.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
