<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify($_POST['csrf_token'] ?? null)) {
    $action = $_POST['action'] ?? 'update_status';
    
    if ($action === 'update_status') {
        $id = (int)($_POST['lead_id'] ?? 0);
        $status = $_POST['status'] ?? 'new';
        $allowed = ['new', 'contacted', 'quoted', 'won', 'lost'];
        if (in_array($status, $allowed, true) && $id > 0) {
            $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
        }
    } elseif ($action === 'delete_lead') {
        $id = (int)($_POST['lead_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
    header('Location: leads.php' . (!empty($_GET['status']) ? '?status=' . urlencode($_GET['status']) : ''));
    exit;
}

$filter = $_GET['status'] ?? '';
$statusCounts = [];
foreach (['all' => '', 'new' => 'new', 'contacted' => 'contacted', 'quoted' => 'quoted', 'won' => 'won', 'lost' => 'lost'] as $key => $val) {
    if ($val === '') {
        $statusCounts[$key] = (int)$pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM leads WHERE status = ?");
        $stmt->execute([$val]);
        $statusCounts[$key] = (int)$stmt->fetchColumn();
    }
}

if ($filter && in_array($filter, ['new','contacted','quoted','won','lost'], true)) {
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE status = ? ORDER BY created_at DESC");
    $stmt->execute([$filter]);
} else {
    $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
}
$leads = $stmt->fetchAll();
?>

<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div>
        <h1 style="margin: 0 0 0.25rem 0;"><i class="ri-user-star-line text-teal"></i> Leads &amp; RFQ Inquiries</h1>
        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">
            Real-time quotation requests captured from website popup forms and contact page.
        </p>
    </div>
    <div style="background: #ffffff; padding: 0.5rem 1.25rem; border-radius: 8px; border: 1.5px solid var(--border-color); font-size: 0.92rem; font-weight: 700; color: var(--navy-dark); box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
        Total Leads: <span style="color: var(--teal-primary); font-size: 1.1rem;"><?= $statusCounts['all'] ?></span>
    </div>
</div>

<!-- Status Filter Pills -->
<div class="filter-bar" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem;">
    <a href="leads.php" class="<?= !$filter ? 'active' : '' ?>" style="padding: 0.45rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">All (<?= $statusCounts['all'] ?>)</a>
    <a href="leads.php?status=new" class="<?= $filter==='new' ? 'active' : '' ?>" style="padding: 0.45rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; color: #1d4ed8;">New (<?= $statusCounts['new'] ?>)</a>
    <a href="leads.php?status=contacted" class="<?= $filter==='contacted' ? 'active' : '' ?>" style="padding: 0.45rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; color: #a16207;">Contacted (<?= $statusCounts['contacted'] ?>)</a>
    <a href="leads.php?status=quoted" class="<?= $filter==='quoted' ? 'active' : '' ?>" style="padding: 0.45rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; color: #7e22ce;">Quoted (<?= $statusCounts['quoted'] ?>)</a>
    <a href="leads.php?status=won" class="<?= $filter==='won' ? 'active' : '' ?>" style="padding: 0.45rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; color: #15803d;">Won (<?= $statusCounts['won'] ?>)</a>
    <a href="leads.php?status=lost" class="<?= $filter==='lost' ? 'active' : '' ?>" style="padding: 0.45rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; color: #b91c1c;">Lost (<?= $statusCounts['lost'] ?>)</a>
</div>

<!-- Compact, Clean Leads Table -->
<div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.04);">
    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse; margin: 0; font-size: 0.88rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 0.85rem 1rem; width: 110px; white-space: nowrap;">Date</th>
                    <th style="padding: 0.85rem 1rem; min-width: 180px;">Client / Contact</th>
                    <th style="padding: 0.85rem 1rem; min-width: 150px;">Product &amp; Qty</th>
                    <th style="padding: 0.85rem 1rem; min-width: 160px;">Trigger Source</th>
                    <th style="padding: 0.85rem 1rem; min-width: 180px;">Inquiry Preview</th>
                    <th style="padding: 0.85rem 1rem; width: 130px;">Status</th>
                    <th style="padding: 0.85rem 1rem; width: 100px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($leads as $lead): ?>
                <?php
                    $leadDataJson = htmlspecialchars(json_encode([
                        'id' => (int)$lead['id'],
                        'name' => $lead['name'] ?? '',
                        'company' => $lead['company'] ?? '',
                        'email' => $lead['email'] ?? '',
                        'phone' => $lead['phone'] ?? '',
                        'product_type' => $lead['product_type'] ?? 'General RFQ',
                        'quantity' => $lead['quantity'] ?? '',
                        'specifications' => $lead['specifications'] ?? '',
                        'message' => $lead['message'] ?? '',
                        'source_button' => $lead['source_button'] ?? 'Request a Quote Button',
                        'source_page' => $lead['source_page'] ?? 'Website',
                        'ip_address' => $lead['ip_address'] ?? '—',
                        'status' => $lead['status'] ?? 'new',
                        'created_at' => date('d M Y, h:i A', strtotime($lead['created_at']))
                    ]), ENT_QUOTES, 'UTF-8');
                ?>
                <tr style="border-bottom: 1px solid var(--border-color); vertical-align: top;">
                    <td style="padding: 0.85rem 1rem; white-space: nowrap;">
                        <strong style="color: var(--navy-dark); font-size: 0.85rem;"><?= h(date('d M Y', strtotime($lead['created_at']))) ?></strong><br>
                        <span style="color: var(--text-muted); font-size: 0.78rem;"><?= h(date('h:i A', strtotime($lead['created_at']))) ?></span>
                    </td>
                    <td style="padding: 0.85rem 1rem;">
                        <strong style="color: var(--navy-dark); font-size: 0.92rem;"><?= h($lead['name']) ?></strong>
                        <?php if (!empty($lead['company'])): ?>
                            <div style="font-size: 0.82rem; color: #4b5563; margin-top: 2px;"><i class="ri-building-line text-muted"></i> <?= h($lead['company']) ?></div>
                        <?php endif; ?>
                        <div style="margin-top: 0.35rem; font-size: 0.8rem; display: flex; flex-direction: column; gap: 2px;">
                            <a href="mailto:<?= h($lead['email']) ?>" style="color: var(--teal-primary); text-decoration: none; font-weight: 600;"><i class="ri-mail-line"></i> <?= h($lead['email']) ?></a>
                            <?php if (!empty($lead['phone'])): ?>
                                <a href="tel:<?= h($lead['phone']) ?>" style="color: var(--text-muted); text-decoration: none;"><i class="ri-phone-line"></i> <?= h($lead['phone']) ?></a>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="padding: 0.85rem 1rem;">
                        <span style="display: inline-block; background: rgba(0,168,150,0.1); color: var(--teal-primary); font-size: 0.78rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 4px; border: 1px solid rgba(0,168,150,0.2); margin-bottom: 0.3rem;">
                            <?= h($lead['product_type'] ?: 'General RFQ') ?>
                        </span>
                        <?php if (!empty($lead['quantity'])): ?>
                            <div style="font-size: 0.82rem; color: #374151;"><strong>Qty:</strong> <?= h($lead['quantity']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0.85rem 1rem;">
                        <div style="display: inline-flex; align-items: center; gap: 0.3rem; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.78rem; font-weight: 700; margin-bottom: 0.25rem;">
                            <i class="ri-cursor-line"></i> <?= h($lead['source_button'] ?? 'Quote Button') ?>
                        </div>
                        <?php 
                            $pageUrl = $lead['source_page'] ?? 'Website';
                            $cleanPage = basename($pageUrl);
                            if ($cleanPage === '') $cleanPage = 'Home Page';
                        ?>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            <i class="ri-link"></i> <?= h($cleanPage) ?>
                        </div>
                    </td>
                    <td style="padding: 0.85rem 1rem; max-width: 220px;">
                        <?php
                            $preview = !empty($lead['specifications']) ? $lead['specifications'] : ($lead['message'] ?? 'No message provided');
                            $truncated = mb_strimwidth($preview, 0, 75, '...');
                        ?>
                        <div style="font-size: 0.82rem; color: #4b5563; line-height: 1.45; margin-bottom: 0.4rem;">
                            <?= h($truncated) ?>
                        </div>
                        <button type="button" onclick="openLeadModal(<?= $leadDataJson ?>)" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: var(--navy-dark); font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem;">
                            <i class="ri-eye-line text-teal"></i> View Full Details
                        </button>
                    </td>
                    <td style="padding: 0.85rem 1rem;">
                        <form method="post" style="margin: 0;">
                            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="lead_id" value="<?= (int)$lead['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="font-weight: 700; font-size: 0.8rem; padding: 0.3rem 0.5rem; border-radius: 6px; border: 1px solid var(--border-color); cursor: pointer; background: <?php
                                switch($lead['status']) {
                                    case 'new': echo '#eff6ff; color:#1d4ed8;'; break;
                                    case 'contacted': echo '#fef9c3; color:#a16207;'; break;
                                    case 'quoted': echo '#f3e8ff; color:#7e22ce;'; break;
                                    case 'won': echo '#dcfce7; color:#15803d;'; break;
                                    case 'lost': echo '#fee2e2; color:#b91c1c;'; break;
                                    default: echo '#ffffff; color:#374151;';
                                }
                            ?>">
                                <?php foreach (['new' => 'New', 'contacted' => 'Contacted', 'quoted' => 'Quoted', 'won' => 'Won', 'lost' => 'Lost'] as $k => $label): ?>
                                    <option value="<?= $k ?>" <?= $lead['status'] === $k ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 0.85rem 1rem; text-align: center;">
                        <form method="post" onsubmit="return confirm('Permanently delete this inquiry from <?= addslashes(h($lead['name'])) ?>?');" style="margin: 0; display: inline;">
                            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                            <input type="hidden" name="action" value="delete_lead">
                            <input type="hidden" name="lead_id" value="<?= (int)$lead['id'] ?>">
                            <button type="submit" style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.95rem; cursor: pointer;" title="Delete Lead">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($leads)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3.5rem 1rem; color: var(--text-muted);">
                        <i class="ri-inbox-line" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem; color: #cbd5e1;"></i>
                        No quotation leads recorded yet.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ====================================================================
     INTERACTIVE LEAD DETAILS MODAL
===================================================================== -->
<div id="leadDetailModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(11,37,69,0.7); z-index: 99999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: #ffffff; border-radius: 12px; max-width: 650px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.3); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        
        <!-- Modal Header -->
        <div style="background: var(--navy-dark); color: #ffffff; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid var(--teal-primary);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(0,168,150,0.2); display: flex; align-items: center; justify-content: center; color: var(--teal-primary); font-size: 1.25rem;">
                    <i class="ri-file-user-line"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; color: #ffffff;">RFQ Lead Details</h3>
                    <span id="modalDate" style="font-size: 0.78rem; color: rgba(255,255,255,0.75);"></span>
                </div>
            </div>
            <button type="button" onclick="closeLeadModal()" style="background: transparent; border: none; color: #ffffff; font-size: 1.35rem; cursor: pointer;"><i class="ri-close-line"></i></button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div style="padding: 1.5rem; overflow-y: auto;">
            
            <!-- Client & Company Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 2px;">Client Name</label>
                    <strong id="modalName" style="font-size: 1rem; color: var(--navy-dark);"></strong>
                </div>
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 2px;">Company</label>
                    <span id="modalCompany" style="font-size: 0.95rem; color: #374151; font-weight: 600;">—</span>
                </div>
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 2px;">Email Address</label>
                    <a id="modalEmail" href="#" style="color: var(--teal-primary); font-weight: 600; text-decoration: none; font-size: 0.9rem;"></a>
                </div>
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 2px;">Phone Number</label>
                    <a id="modalPhone" href="#" style="color: #374151; font-weight: 600; text-decoration: none; font-size: 0.9rem;"></a>
                </div>
            </div>

            <!-- Product & Quantity Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 2px;">Requested Packaging</label>
                    <span id="modalProduct" style="display: inline-block; background: rgba(0,168,150,0.12); color: var(--teal-primary); font-size: 0.85rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 4px;"></span>
                </div>
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 2px;">Quantity / Volume</label>
                    <span id="modalQuantity" style="font-size: 0.95rem; font-weight: 700; color: #1f2937;">—</span>
                </div>
            </div>

            <!-- Lead Attribution & Tracking -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #92400e; font-weight: 700; display: block; margin-bottom: 2px;">Triggered Button</label>
                    <strong id="modalSourceButton" style="font-size: 0.88rem; color: #78350f;"></strong>
                </div>
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #92400e; font-weight: 700; display: block; margin-bottom: 2px;">Origin Page</label>
                    <span id="modalSourcePage" style="font-size: 0.85rem; color: #78350f;"></span>
                </div>
            </div>

            <!-- Specifications -->
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy-dark); display: block; margin-bottom: 0.35rem;">Technical Specifications / Dimensions:</label>
                <div id="modalSpecs" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; padding: 0.85rem 1rem; font-size: 0.88rem; line-height: 1.6; color: #374151; white-space: pre-wrap;"></div>
            </div>

            <!-- Message -->
            <div>
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--navy-dark); display: block; margin-bottom: 0.35rem;">Customer Inquiry / Message:</label>
                <div id="modalMessage" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; padding: 0.85rem 1rem; font-size: 0.88rem; line-height: 1.6; color: #374151; white-space: pre-wrap;"></div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div style="background: #f8fafc; border-top: 1px solid var(--border-color); padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <button type="button" onclick="closeLeadModal()" style="background: var(--navy-dark); color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 6px; font-weight: 600; font-size: 0.88rem; cursor: pointer;">Close Details</button>
        </div>
    </div>
</div>

<script>
function openLeadModal(lead) {
    document.getElementById('modalDate').textContent = lead.created_at;
    document.getElementById('modalName').textContent = lead.name || '—';
    document.getElementById('modalCompany').textContent = lead.company || '—';
    
    const emailEl = document.getElementById('modalEmail');
    emailEl.textContent = lead.email || '—';
    emailEl.href = lead.email ? 'mailto:' + lead.email : '#';
    
    const phoneEl = document.getElementById('modalPhone');
    phoneEl.textContent = lead.phone || '—';
    phoneEl.href = lead.phone ? 'tel:' + lead.phone : '#';
    
    document.getElementById('modalProduct').textContent = lead.product_type || 'General RFQ';
    document.getElementById('modalQuantity').textContent = lead.quantity || '—';
    document.getElementById('modalSourceButton').textContent = lead.source_button || '—';
    document.getElementById('modalSourcePage').textContent = lead.source_page || '—';
    
    document.getElementById('modalSpecs').textContent = lead.specifications || 'No specific dielines / GSM mentioned.';
    document.getElementById('modalMessage').textContent = lead.message || 'No additional message provided.';
    
    document.getElementById('leadDetailModal').style.display = 'flex';
}

function closeLeadModal() {
    document.getElementById('leadDetailModal').style.display = 'none';
}
</script>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
