<?php
$pageSlug = 'contact';

// Handle form submission BEFORE header.php is included, so we can redirect cleanly
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/mailer.php';

$submitted = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Session expired — please refresh the page and try again.';
    } else {
        $contactName = trim($_POST['contact_name'] ?? '');
        $email       = trim($_POST['email'] ?? '');

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

            $leadId = (int) $pdo->lastInsertId();

            // ---------- HTML Email Notification ----------
            $notifyEmail = get_setting('notification_email', 'princeartpackages@gmail.com');
            if (!empty($notifyEmail)) {
                $proto    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $adminUrl = $proto . '://' . ($_SERVER['HTTP_HOST'] ?? 'princeartpackages.com') . '/admin/leads.php';

                $leadData = [
                    'name'           => $contactName,
                    'company'        => trim($_POST['company_name'] ?? ''),
                    'email'          => $email,
                    'phone'          => trim($_POST['phone'] ?? ''),
                    'product'        => trim($_POST['product_type'] ?? ''),
                    'quantity'       => trim($_POST['estimated_quantity'] ?? ''),
                    'specifications' => trim($_POST['specifications'] ?? ''),
                    'message'        => trim($_POST['message'] ?? ''),
                    'ref_no'         => 'PAP-RFQ-' . sprintf('%05d', $leadId),
                ];

                $subject   = "New RFQ | {$leadData['name']} — {$leadData['company']} | {$leadData['product']}";
                $htmlEmail = build_lead_email_html($leadData, $adminUrl);
                $plainText = "New RFQ Lead Received\n\n"
                           . "Ref: {$leadData['ref_no']}\n"
                           . "Name: {$leadData['name']}\n"
                           . "Company: {$leadData['company']}\n"
                           . "Email: {$leadData['email']}\n"
                           . "Phone: {$leadData['phone']}\n"
                           . "Product: {$leadData['product']}\n"
                           . "Quantity: {$leadData['quantity']}\n"
                           . "Specifications: {$leadData['specifications']}\n"
                           . "Message: {$leadData['message']}\n\n"
                           . "Admin: {$adminUrl}";

                send_email_notification($notifyEmail, $subject, $htmlEmail, $plainText, $email);
            }

            // ---------- Store lead summary in session for Thank You page ----------
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $_SESSION['recent_lead'] = [
                'id'      => $leadId,
                'name'    => $contactName,
                'company' => trim($_POST['company_name'] ?? ''),
                'email'   => $email,
                'product' => trim($_POST['product_type'] ?? ''),
            ];

            // ---------- Redirect to Thank You page ----------
            header('Location: thank-you.php');
            exit;
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<!-- ================================================================ -->
<!-- SECTION 02 — HERO                                                -->
<!-- ================================================================ -->
<section class="contact-hero-section">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2;">
    <div class="section-header" style="max-width: 820px; margin: 0 auto; text-align: center;">
      <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
        <i class="ri-mail-send-line"></i> <?= h(get_content('contact', 'hero_subtitle', 'Contact Us')) ?>
      </span>
      <h1>
        <?= h(get_content('contact', 'hero_title', 'Contact Prince Art Packages')) ?>
      </h1>
      <p>
        <?= h(get_content('contact', 'hero_copy', 'Have a question about our pharmaceutical packaging products, manufacturing capabilities or packaging solutions? Get in touch with our team.')) ?>
      </p>
      <div style="display: inline-block;">
        <?= render_cta_buttons('contact', 'hero_cta', '<a href="#enquiry-form" class="btn btn-gold btn-lg"><i class="ri-mail-send-line"></i> Send an Enquiry &darr;</a>') ?>
      </div>
    </div>
  </div>
</section>

    <div class="container section" id="enquiry-form">
      <div class="section-header">
        <span class="section-subtitle"><?= h(get_content('contact', 'page_subtitle', 'Get in Touch')) ?></span>
        <h2><?= h(get_content('contact', 'intro_heading', 'Request a Quotation & Facility Visit')) ?></h2>
        <p><?= h(get_content('contact', 'intro_text', 'Contact our technical sales team or request a formal plant audit at Unit 1 or Unit 2 in Korangi Creek Industrial Park, Karachi.')) ?></p>
      </div>

      <div class="grid-2">
        <!-- RFQ Form -->
        <div class="rfq-container">
          <h3 style="margin-bottom: 0.35rem; color: var(--navy-dark); font-size: 1.5rem;"><i class="ri-draft-line" style="color:var(--teal-brand);"></i> <?= h(get_content('contact', 'form_title', 'Request a Quotation')) ?></h3>
          <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem;"><?= h(get_content('contact', 'form_subtitle', 'Fill out the form below to receive a formal technical quotation or schedule a plant audit.')) ?></p>

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
                  <?php $reqProduct = trim($_GET['product'] ?? ''); ?>
                  <label>Product Category *</label>
                  <select name="product_type" class="form-control" required>
                    <option value="Printed Cartons" <?= (stripos($reqProduct, 'Carton') !== false) ? 'selected' : '' ?>>Printed Cartons</option>
                    <option value="Leaf-inserts" <?= (stripos($reqProduct, 'Leaf') !== false) ? 'selected' : '' ?>>Leaf-inserts</option>
                    <option value="Printed Labels" <?= (stripos($reqProduct, 'Label') !== false) ? 'selected' : '' ?>>Printed Labels</option>
                    <option value="Honeycomb Separators" <?= (stripos($reqProduct, 'Honeycomb') !== false) ? 'selected' : '' ?>>Honeycomb Separators</option>
                    <option value="Pill-folders" <?= (stripos($reqProduct, 'Pill') !== false) ? 'selected' : '' ?>>Pill-folders</option>
                    <option value="Temper Evident Cartons & Labels" <?= (stripos($reqProduct, 'Temper') !== false || stripos($reqProduct, 'Tamper') !== false) ? 'selected' : '' ?>>Temper Evident Cartons & Labels</option>
                    <option value="3D-ENGRAVIX" <?= (stripos($reqProduct, '3D') !== false || stripos($reqProduct, 'ENGRAVIX') !== false) ? 'selected' : '' ?>>3D-ENGRAVIX</option>
                    <option value="Cold-seal Wallet" <?= (stripos($reqProduct, 'Cold') !== false || stripos($reqProduct, 'ColdSeal') !== false) ? 'selected' : '' ?>>Cold-seal Wallet</option>
                    <option value="Others">Others</option>
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
            <h3 style="color:var(--navy-dark); margin-bottom:0.25rem;"><?= h(get_content('contact', 'company_name', 'Prince Art Packages (Private) Limited')) ?></h3>
            <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.5rem;"><?= h(get_content('contact', 'company_subtitle', '(Formerly Prince Art Press)')) ?></p>

            <ul class="contact-info-list" style="list-style:none; padding:0; margin:0;">
              <li style="display:flex; align-items:flex-start; gap:1rem; margin-bottom:1.25rem;">
                <span style="width:24px; min-width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                  <i class="ri-building-line" style="color:var(--teal-brand); font-size:1.35rem;"></i>
                </span>
                <div style="color:var(--text-charcoal); font-size:0.92rem; line-height:1.5;">
                  <strong style="color:var(--navy-dark);">Unit 1:</strong> <?= h(get_content('contact', 'unit1_address', 'WH-17-A8, ST-1, Sector 38, Korangi Creek Industrial Park, Karachi, Pakistan.')) ?>
                </div>
              </li>
              <li style="display:flex; align-items:flex-start; gap:1rem; margin-bottom:1.25rem;">
                <span style="width:24px; min-width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                  <i class="ri-building-2-line" style="color:var(--teal-brand); font-size:1.35rem;"></i>
                </span>
                <div style="color:var(--text-charcoal); font-size:0.92rem; line-height:1.5;">
                  <strong style="color:var(--navy-dark);">Unit 2:</strong> <?= h(get_content('contact', 'unit2_address', 'Plot 239, Opposite Masco, Main Korangi Creek Road, Karachi, Pakistan.')) ?>
                </div>
              </li>
              <li style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
                <span style="width:24px; min-width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <i class="ri-phone-line" style="color:var(--teal-brand); font-size:1.35rem;"></i>
                </span>
                <div style="color:var(--text-charcoal); font-size:0.92rem;">
                  <strong style="color:var(--navy-dark);">Tel:</strong> <?= h(get_content('contact', 'phone_number', '+92 21-38893400-3')) ?>
                </div>
              </li>
              <li style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
                <span style="width:24px; min-width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <i class="ri-mail-line" style="color:var(--teal-brand); font-size:1.35rem;"></i>
                </span>
                <div style="color:var(--text-charcoal); font-size:0.92rem;">
                  <strong style="color:var(--navy-dark);">Email:</strong> <?= h(get_content('contact', 'email_address', 'info@princeartpackages.com')) ?>
                </div>
              </li>
              <li style="display:flex; align-items:center; gap:1rem; margin-bottom:0;">
                <span style="width:24px; min-width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <i class="ri-time-line" style="color:var(--teal-brand); font-size:1.35rem;"></i>
                </span>
                <div style="color:var(--text-charcoal); font-size:0.92rem;">
                  <strong style="color:var(--navy-dark);">Business Hours:</strong> <?= h(get_content('contact', 'business_hours', 'Monday – Saturday: 9:00 AM – 5:00 PM')) ?>
                </div>
              </li>
            </ul>
          </div>

          <!-- Verified Certifications Box -->
          <div class="card card-body">
            <h4>Verified ISO &amp; FSC Certifications</h4>
            <div style="margin-top:0.75rem; font-size:0.85rem;">
              <p style="margin-bottom:0.4rem;"><i class="ri-checkbox-circle-fill text-teal"></i> <strong>ISO 9001:2015:</strong> Cert No. <code>KQ.2025.5393</code> (ASCERT / MSCB-223)</p>
              <p style="margin:0;"><i class="ri-checkbox-circle-fill text-teal"></i> <strong>FSC Chain of Custody:</strong> Cert No. <code>RR-COC-003348</code> | License <code>FSC-C222205</code> (FSC-STD-40-004)</p>
            </div>
          </div>
        </div>
      </div>
    </div>

<!-- ================================================================ -->
<!-- SECTION 07 — GOOGLE MAP                                          -->
<!-- ================================================================ -->
<section class="section map-section" id="our-location">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 840px; margin: 0 auto 3rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-map-pin-line"></i> <?= h(get_content('contact', 'map_sec_subtitle', 'Our Location')) ?>
      </span>
      <h2 style="font-size: 2.4rem; color: var(--navy-dark); font-weight: 800; line-height: 1.2; margin-bottom: 1rem;">
        <?= h(get_content('contact', 'map_sec_title', 'Our Location')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.7; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('contact', 'map_sec_intro', 'Visit our dual pharmaceutical secondary packaging manufacturing facilities in Korangi Creek Industrial Park, Karachi. We welcome prospective clients, technical auditors, and procurement teams for scheduled facility audits.')) ?>
      </p>
    </div>

    <div class="map-layout-grid">
      <!-- Location Cards Column -->
      <div class="map-info-col">
        
        <!-- Unit 1 Card -->
        <div class="map-location-card">
          <span class="map-card-badge"><i class="ri-building-line"></i> HEAD OFFICE &amp; MANUFACTURING</span>
          <h3>Manufacturing Unit 1</h3>
          <p class="map-card-address">
            <?= h(get_content('contact', 'unit1_address', 'WH-17-A8, ST-1, Sector 38, Korangi Creek Industrial Park, Karachi, Pakistan.')) ?>
          </p>
          <div class="map-card-actions">
            <a href="https://www.google.com/maps/search/?api=1&query=WH-17-A8+ST-1+Sector+38+Korangi+Creek+Industrial+Park+Karachi" target="_blank" rel="noopener" class="btn btn-outline-navy btn-sm">
              <i class="ri-direction-line"></i> Get Directions &rarr;
            </a>
          </div>
        </div>

        <!-- Unit 2 Card -->
        <div class="map-location-card">
          <span class="map-card-badge" style="background: rgba(212,175,55,0.12); color: var(--gold-dark);"><i class="ri-building-2-line"></i> PRODUCTION UNIT 2</span>
          <h3>Manufacturing Unit 2</h3>
          <p class="map-card-address">
            <?= h(get_content('contact', 'unit2_address', 'Plot 239, Opposite Masco, Main Korangi Creek Road, Karachi, Pakistan.')) ?>
          </p>
          <div class="map-card-actions">
            <a href="https://www.google.com/maps/search/?api=1&query=Plot+239+Main+Korangi+Creek+Road+Karachi" target="_blank" rel="noopener" class="btn btn-outline-navy btn-sm">
              <i class="ri-direction-line"></i> Get Directions &rarr;
            </a>
          </div>
        </div>

        <!-- Business Hours Card -->
        <div class="map-location-card" style="padding: 1.25rem 1.5rem;">
          <div style="display: flex; align-items: center; gap: 0.85rem;">
            <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(0,168,150,0.1); color: var(--teal-brand); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
              <i class="ri-time-line"></i>
            </div>
            <div>
              <strong style="color: var(--navy-dark); font-size: 0.95rem; display: block; margin-bottom: 0.2rem;">Business &amp; Operating Hours</strong>
              <p style="font-size: 0.88rem; color: var(--text-body); margin: 0; line-height: 1.4;">
                <?= h(get_content('contact', 'business_hours', 'Monday – Saturday: 9:00 AM – 5:00 PM')) ?>
              </p>
            </div>
          </div>
        </div>

        <!-- Audit Access Note -->
        <div class="map-audit-note">
          <i class="ri-shield-check-fill text-teal" style="font-size: 1.4rem; flex-shrink: 0; margin-top: 2px;"></i>
          <div>
            <strong style="color: var(--navy-dark); display: block; margin-bottom: 0.25rem;">Facility Audit Access</strong>
            <p style="font-size: 0.84rem; color: var(--text-body); margin: 0; line-height: 1.5;">
              Plant audits are conducted under strict cGMP visitor protocols. Please contact our QA coordination desk in advance to schedule your audit date.
            </p>
          </div>
        </div>

      </div>

      <!-- Google Maps Embed Container -->
      <div class="map-embed-wrapper">
        <iframe 
          title="Prince Art Packages Location Map - Korangi Creek Industrial Park, Karachi"
          src="<?= h(get_content('contact', 'map_embed_url', 'https://maps.google.com/maps?q=Korangi+Creek+Industrial+Park,+Karachi,+Pakistan&t=&z=15&ie=UTF8&iwloc=&output=embed')) ?>" 
          width="100%" 
          height="100%" 
          style="border:0; min-height: 440px; display: block;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade"
          aria-label="Google Maps Location of Prince Art Packages">
        </iframe>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

