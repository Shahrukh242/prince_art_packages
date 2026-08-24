<?php
$pageSlug = 'contact';

// Handle form submission BEFORE header.php is included, so we can redirect cleanly
require_once __DIR__ . '/../includes/functions.php';

$submitted = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Session expired — please refresh the page and try again.';
    } else {
        $contactName = trim($_POST['contact_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($contactName === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please provide a valid contact name and business email.';
        } else {
            $pdo = get_db();
            $stmt = $pdo->prepare(
                "INSERT INTO leads (name, company, email, phone, product_type, quantity, specifications, message)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $contactName,
                trim($_POST['company_name'] ?? ''),
                $email,
                trim($_POST['phone'] ?? ''),
                trim($_POST['product_type'] ?? ''),
                trim($_POST['estimated_quantity'] ?? ''),
                trim($_POST['specifications'] ?? ''),
                trim($_POST['message'] ?? ''),
            ]);

            // Optional: notify your team by email when a new lead comes in
            // mail('sales@princeartpackages.com', 'New RFQ from website', "From: $contactName ($email)");

            $submitted = true;
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

    <div class="container section">
      <div class="section-header">
        <span class="section-subtitle">Get in Touch</span>
        <h2><?= h(get_content('contact', 'intro_heading', 'Request a Quotation & Facility Visit')) ?></h2>
        <p><?= h(get_content('contact', 'intro_text', 'Contact our technical sales team or request a formal plant audit at Unit 1 or Unit 2 in Korangi Creek Industrial Park, Karachi.')) ?></p>
      </div>

      <div class="grid-2">
        <!-- RFQ Form -->
        <div class="rfq-container">
          <h3 style="margin-bottom: 0.35rem; color: var(--navy-dark); font-size: 1.5rem;"><i class="ri-draft-line" style="color:var(--teal-brand);"></i> Request a Quotation</h3>
          <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem;">Fill out the form below to receive a formal technical quotation or schedule a plant audit.</p>

          <?php if ($submitted): ?>
            <div class="alert" style="padding:1rem; margin-bottom:1rem; border-radius:4px; background:#d1fae5; color:#065f46;">
              Thank you — your request has been received. Our team will be in touch shortly.
            </div>
          <?php else: ?>
            <?php if ($error): ?>
              <div class="alert" style="padding:1rem; margin-bottom:1rem; border-radius:4px; background:#fee2e2; color:#b91c1c;">
                <?= h($error) ?>
              </div>
            <?php endif; ?>

            <form method="post">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <div class="form-row">
                <div class="form-group">
                  <label>Company Name *</label>
                  <input type="text" name="company_name" class="form-control" placeholder="e.g. Acme Pharma (Pvt) Ltd" required>
                </div>
                <div class="form-group">
                  <label>Contact Person *</label>
                  <input type="text" name="contact_name" class="form-control" placeholder="Full Name & Title" required>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Business Email *</label>
                  <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
                </div>
                <div class="form-group">
                  <label>Phone / WhatsApp Number</label>
                  <input type="text" name="phone" class="form-control" placeholder="+92 21-38893400">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Product Category *</label>
                  <select name="product_type" class="form-control" id="rfq-product-type" required>
                    <?php 
                    $preProduct = trim($_GET['product'] ?? '');
                    $productOptions = [
                      'Folding Cartons',
                      'Leaf-inserts',
                      'Printed Labels',
                      'Honeycomb Separators',
                      'Pill-folders',
                      'Temper Evident Cartons & Labels',
                      '3D-ENGRAVIX',
                      'Cold-seal Wallet',
                      'Others'
                    ];
                    foreach ($productOptions as $opt): 
                      $isSelected = false;
                      if ($preProduct !== '') {
                          if (strcasecmp($preProduct, $opt) === 0 || stripos($opt, $preProduct) !== false || stripos($preProduct, $opt) !== false) {
                              $isSelected = true;
                          }
                      }
                    ?>
                      <option value="<?= h($opt) ?>" <?= $isSelected ? 'selected' : '' ?>><?= h($opt) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Estimated Quantity / Annual Run</label>
                  <input type="text" name="estimated_quantity" class="form-control" placeholder="e.g. 50,000 units">
                </div>
              </div>

              <div class="form-group">
                <label>Technical Specifications (GSM, Dimensions, Substrate)</label>
                <textarea name="specifications" class="form-control" placeholder="Specify paperboard gsm, dimensions (L x W x H mm), colors, finishes..."></textarea>
              </div>

              <div class="form-group">
                <label>Additional Notes / Plant Audit Request</label>
                <textarea name="message" class="form-control" style="min-height:75px;" placeholder="Any additional requirements or plant audit timeline..."></textarea>
              </div>

              <button type="submit" class="btn btn-gold" style="width:100%;"><i class="ri-send-plane-fill"></i> Submit Quotation Request</button>
            </form>
          <?php endif; ?>
        </div>

        <!-- Contact Info & Verified Addresses -->
        <div>
          <div class="card card-body" style="margin-bottom: 1.5rem;">
            <h3 style="color:var(--navy-dark); margin-bottom:0.25rem;">Prince Art Packages (Private) Limited</h3>
            <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.5rem;">(Formerly Prince Art Press)</p>

            <ul class="contact-info-list" style="list-style:none; padding:0; margin:0;">
              <li style="display:flex; align-items:flex-start; gap:0.85rem; margin-bottom:1.25rem;">
                <i class="ri-building-line" style="color:var(--teal-brand); font-size:1.35rem; margin-top:2px; flex-shrink:0;"></i>
                <div style="color:var(--text-charcoal); font-size:0.92rem; line-height:1.5;">
                  <strong style="color:var(--navy-dark);">Unit 1:</strong> WH-17-A8, ST-1, Sector 38, Korangi Creek Industrial Park, Karachi, Pakistan.
                </div>
              </li>
              <li style="display:flex; align-items:flex-start; gap:0.85rem; margin-bottom:1.25rem;">
                <i class="ri-building-2-line" style="color:var(--teal-brand); font-size:1.35rem; margin-top:2px; flex-shrink:0;"></i>
                <div style="color:var(--text-charcoal); font-size:0.92rem; line-height:1.5;">
                  <strong style="color:var(--navy-dark);">Unit 2:</strong> Plot 239, Opposite Masco, Main Korangi Creek Road, Karachi, Pakistan.
                </div>
              </li>
              <li style="display:flex; align-items:center; gap:0.85rem; margin-bottom:1.25rem;">
                <i class="ri-phone-line" style="color:var(--teal-brand); font-size:1.35rem; flex-shrink:0;"></i>
                <div style="color:var(--text-charcoal); font-size:0.92rem;">
                  <strong style="color:var(--navy-dark);">Tel:</strong> +92 21-38893400-3
                </div>
              </li>
              <li style="display:flex; align-items:center; gap:0.85rem; margin-bottom:1rem;">
                <i class="ri-mail-line" style="color:var(--teal-brand); font-size:1.35rem; flex-shrink:0;"></i>
                <div style="color:var(--text-charcoal); font-size:0.92rem;">
                  <strong style="color:var(--navy-dark);">Email:</strong> info@princeartpackages.com
                </div>
              </li>
            </ul>
          </div>

          <!-- Verified Certifications Box -->
          <div class="card card-body">
            <h4>Verified ISO & FSC Certifications</h4>
            <div style="margin-top:0.75rem; font-size:0.85rem;">
              <p style="margin-bottom:0.4rem;"><i class="ri-checkbox-circle-fill text-teal"></i> <strong>ISO 9001:2015:</strong> Cert No. <code>KQ.2025.5393</code> (ASCERT / MSCB-223)</p>
              <p style="margin:0;"><i class="ri-checkbox-circle-fill text-teal"></i> <strong>FSC Chain of Custody:</strong> Cert No. <code>RR-COC-003348</code> | License <code>FSC-C222205</code> (FSC-STD-40-004)</p>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
