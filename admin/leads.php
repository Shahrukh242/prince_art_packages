<?php
// admin/leads.php — Lead Submissions & RFQ Requests Management
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$message = '';

// Handle status update & deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf_token'] ?? null)) {
    $action = $_POST['action'] ?? 'update_status';
    $id = (int)($_POST['lead_id'] ?? 0);

    if ($action === 'update_status' && $id > 0) {
        $status = $_POST['status'] ?? 'new';
        $allowed = ['new', 'contacted', 'quoted', 'won', 'lost'];
        if (in_array($status, $allowed, true)) {
            $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $message = "Lead status updated.";
        }
    }

    if ($action === 'delete_lead' && $id > 0) {
        $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Lead submission deleted.";
    }
}

$filter = $_GET['status'] ?? '';
if ($filter && in_array($filter, ['new','contacted','quoted','won','lost'], true)) {
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE status = ? ORDER BY created_at DESC");
    $stmt->execute([$filter]);
} else {
    $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
}
$leads = $stmt->fetchAll();
?>

<h1>Leads & RFQ Submissions</h1>
<?php if ($message): ?><p class="success-msg"><?= h($message) ?></p><?php endif; ?>

<div class="filter-bar" style="margin-bottom:1.5rem;">
    <a href="leads.php" class="<?= !$filter ? 'active' : '' ?>">All</a>
    <a href="leads.php?status=new" class="<?= $filter==='new' ? 'active' : '' ?>">New</a>
    <a href="leads.php?status=contacted" class="<?= $filter==='contacted' ? 'active' : '' ?>">Contacted</a>
    <a href="leads.php?status=quoted" class="<?= $filter==='quoted' ? 'active' : '' ?>">Quoted</a>
    <a href="leads.php?status=won" class="<?= $filter==='won' ? 'active' : '' ?>">Won</a>
    <a href="leads.php?status=lost" class="<?= $filter==='lost' ? 'active' : '' ?>">Lost</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Company</th>
            <th>Email / Phone</th>
            <th>Product Requested</th>
            <th>Quantity</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($leads as $lead): ?>
        <tr>
            <td style="white-space:nowrap;"><?= h(date('d M Y', strtotime($lead['created_at']))) ?></td>
            <td><strong><?= h($lead['name']) ?></strong></td>
            <td><?= h($lead['company']) ?></td>
            <td>
                <a href="mailto:<?= h($lead['email']) ?>"><?= h($lead['email']) ?></a>
                <?php if (!empty($lead['phone'])): ?><br><small style="color:#64748b;"><?= h($lead['phone']) ?></small><?php endif; ?>
            </td>
            <td><?= h($lead['product_type']) ?></td>
            <td><?= h($lead['quantity']) ?></td>
            <td class="message-cell" style="max-width:250px; font-size:0.85rem; line-height:1.4;">
                <?= h($lead['message']) ?>
            </td>
            <td>
                <form method="post" class="status-form">
                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="lead_id" value="<?= (int)$lead['id'] ?>">
                    <select name="status" onchange="this.form.submit()" style="padding:0.4rem; border-radius:4px; border:1px solid #cbd5e1;">
                        <?php foreach (['new','contacted','quoted','won','lost'] as $s): ?>
                            <option value="<?= $s ?>" <?= $lead['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
            <td>
                <form method="post" style="display:inline;" onsubmit="return confirm('Delete this lead submission permanently?');">
                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="delete_lead">
                    <input type="hidden" name="lead_id" value="<?= (int)$lead['id'] ?>">
                    <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer; font-weight:600; padding:0;">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($leads)): ?>
        <tr><td colspan="9" style="text-align:center; color:#64748b; padding:2rem;">No lead submissions found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
