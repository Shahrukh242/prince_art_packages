<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();
$newLeads = $pdo->query("SELECT COUNT(*) as c FROM leads WHERE status = 'new'")->fetch()['c'];
$totalLeads = $pdo->query("SELECT COUNT(*) as c FROM leads")->fetch()['c'];
$totalMedia = $pdo->query("SELECT COUNT(*) as c FROM media")->fetch()['c'];
$recentLeads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<h1><i class="ri-dashboard-3-line"></i> Dashboard</h1>
<p class="page-subtitle">Welcome back — here's what's happening on the site.</p>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-user-star-line"></i></div>
        <span class="stat-number"><?= (int)$newLeads ?></span>
        <span class="stat-label">New Leads</span>
    </div>
    <div class="stat-card gold">
        <div class="stat-icon" style="color: var(--gold);"><i class="ri-bar-chart-box-line"></i></div>
        <span class="stat-number"><?= (int)$totalLeads ?></span>
        <span class="stat-label">Total Leads</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-image-2-line"></i></div>
        <span class="stat-number"><?= (int)$totalMedia ?></span>
        <span class="stat-label">Images in Media Library</span>
    </div>
</div>

<div class="panel">
    <h3><i class="ri-time-line"></i> Recent Leads</h3>
    <table class="data-table">
        <thead>
            <tr><th>Date</th><th>Name</th><th>Company</th><th>Product</th><th>Status</th></tr>
        </thead>
        <tbody>
        <?php foreach ($recentLeads as $lead): ?>
            <tr>
                <td><?= h(date('d M Y', strtotime($lead['created_at']))) ?></td>
                <td><?= h($lead['name']) ?></td>
                <td><?= h($lead['company']) ?></td>
                <td><?= h($lead['product_type']) ?></td>
                <td><?= h(ucfirst($lead['status'])) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($recentLeads)): ?>
            <tr><td colspan="5">No leads yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <p style="margin: 1rem 0 0;"><a href="leads.php">View all leads →</a></p>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
