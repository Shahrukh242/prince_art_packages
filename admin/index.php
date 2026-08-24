<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();
$newLeads = $pdo->query("SELECT COUNT(*) as c FROM leads WHERE status = 'new'")->fetch()['c'];
$totalLeads = $pdo->query("SELECT COUNT(*) as c FROM leads")->fetch()['c'];
?>
<h1>Dashboard</h1>
<div class="stat-cards">
    <div class="stat-card">
        <span class="stat-number"><?= (int)$newLeads ?></span>
        <span class="stat-label">New Leads</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?= (int)$totalLeads ?></span>
        <span class="stat-label">Total Leads</span>
    </div>
</div>
<p><a href="leads.php">View all leads →</a></p>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
