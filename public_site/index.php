<?php
$pageSlug = 'home';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/mailer.php';

$quoteSubmitted = false;
$quoteError = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'quick_quote') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $quoteError = 'Session expired — please refresh the page and try again.';
    } else {
        $fullName = trim($_POST['full_name'] ?? '');
        $companyName = trim($_POST['company_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $packagingReq = trim($_POST['packaging_requirement'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($fullName === '' || $companyName === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $country === '' || $packagingReq === '' || $message === '') {
            $quoteError = 'Please fill in all required fields marked with an asterisk (*).';
        } else {
            $phone = trim($_POST['phone'] ?? '');
            $productType = trim($_POST['product_type'] ?? '');
            $quantity = trim($_POST['estimated_quantity'] ?? '');

            // Handle an optional artwork upload. Validate both the content type
            // and size; never trust the filename supplied by the browser.
            $attachmentPath = '';
            if (!empty($_FILES['artwork']['name']) && $_FILES['artwork']['error'] === UPLOAD_ERR_OK) {
                $maxUploadBytes = 10 * 1024 * 1024;
                $allowedTypes = [
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'application/zip' => 'zip',
                    'application/x-zip-compressed' => 'zip',
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/vnd.adobe.photoshop' => 'psd',
                ];
                $fileInfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $fileInfo->file($_FILES['artwork']['tmp_name']);
                if ($_FILES['artwork']['size'] > $maxUploadBytes) {
                    $quoteError = 'Artwork files must be 10 MB or smaller.';
                } elseif (!isset($allowedTypes[$mimeType])) {
                    $quoteError = 'Unsupported artwork file type.';
                } else {
                    $uploadDir = __DIR__ . '/assets/uploads/quotes/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $safeFilename = 'rfq_' . bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
                    $destPath = $uploadDir . $safeFilename;
                    if (move_uploaded_file($_FILES['artwork']['tmp_name'], $destPath)) {
                        $attachmentPath = 'assets/uploads/quotes/' . $safeFilename;
                    } else {
                        $quoteError = 'Artwork upload could not be saved. Please try again.';
                    }
                }
            }

            if ($quoteError !== '') {
                // Do not write a partial lead when its optional upload failed validation.
            } else {

            $specs = "Country: " . $country . "\n"
                   . "Requirement: " . $packagingReq;
            if ($attachmentPath) {
                $specs .= "\nAttachment: " . $attachmentPath;
            }

            $pdo = get_db();
            $stmt = $pdo->prepare(
                "INSERT INTO leads (name, company, email, phone, product_type, quantity, specifications, message)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $fullName,
                $companyName,
                $email,
                $phone,
                $productType ?: $packagingReq,
                $quantity,
                $specs,
                $message,
            ]);

            // Email notification if configured
            $notifyEmail = get_setting('notification_email', 'sales@princeartpackages.com');
            if (!empty($notifyEmail)) {
                $subject = "New RFQ Quote Request: " . $fullName . " (" . $companyName . ")";
                $emailBody = "New RFQ Quote Request Received from Prince Art Packages Homepage:\n\n"
                           . "Name: " . $fullName . "\n"
                           . "Company: " . $companyName . "\n"
                           . "Email: " . $email . "\n"
                           . "Country: " . $country . "\n"
                           . "Phone: " . $phone . "\n"
                           . "Product Type: " . $productType . "\n"
                           . "Quantity: " . $quantity . "\n"
                           . "Packaging Requirement: " . $packagingReq . "\n"
                           . "Message: " . $message . "\n"
                           . ($attachmentPath ? "Attachment: " . $attachmentPath . "\n" : "")
                           . "\nView in Admin: " . (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/admin/leads.php' : 'Admin Panel');
                send_email_notification($notifyEmail, $subject, nl2br(h($emailBody)), $emailBody, $email);
            }

            $quoteSubmitted = true;
            }
        }
    }
}

require __DIR__ . '/includes/header.php';

// Load hero CTA buttons from DB (falls back gracefully to empty array)
$heroCtas = get_cta_buttons('home', 'hero');
?>

    <!-- Hero Banner -->
    <section class="hero-section">
      <div class="container hero-grid">
        <div class="hero-content">
          <div class="hero-badges">
            <span class="hero-badge-item"><i class="ri-shield-check-line"></i> cGMP COMPLIANT</span>
            <span class="hero-badge-item"><i class="ri-award-line"></i> ISO 9001:2015 CERTIFIED</span>
            <span class="hero-badge-item"><i class="ri-leaf-line"></i> FSC CHAIN OF CUSTODY</span>
          </div>

          <h1><?= h(get_content('home', 'hero_title', 'Precision Pharmaceutical Secondary Packaging Engineered for Global Audit Compliance')) ?></h1>
          <p class="hero-subtitle"><?= h(get_content('home', 'hero_subtitle', 'ISO 9001:2015 certified manufacturer of printed cartons, leaf-inserts, printed labels, ColdSeal blister wallets, and 3D-Engravix™ optical anti-counterfeit security packaging based in Korangi Creek Industrial Park, Karachi.')) ?></p>

          <div class="hero-cta">
            <?= render_cta_buttons('home', 'hero', '<a href="products.php" class="btn btn-gold"><i class="ri-arrow-right-line"></i> Explore Products &amp; Capabilities</a><a href="contact.php" class="btn btn-outline-navy"><i class="ri-file-list-3-line"></i> Request a Formal Quote</a>') ?>
          </div>
        </div>

        <div class="hero-image-box">
          <?= render_image(get_content('home', 'hero_image', 'assets/images/engravix.jpg'), '3D-Engravix Anti-Counterfeit Packaging', '', ['loading' => 'eager', 'fetchpriority' => 'high']) ?>
        </div>
      </div>
    </section>

    <!-- Trust Bar -->
    <section class="trust-bar">
      <div class="container trust-flex">
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-award-line"></i></div>
          <div class="trust-text">
            <h4><?= h(get_content('home', 'trust_1_title', 'ISO 9001:2015 Certified')) ?></h4>
            <p><?= h(get_content('home', 'trust_1_text', 'Cert No. KQ.2025.5393 (ASCERT)')) ?></p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-leaf-line"></i></div>
          <div class="trust-text">
            <h4><?= h(get_content('home', 'trust_2_title', 'FSC Chain of Custody')) ?></h4>
            <p><?= h(get_content('home', 'trust_2_text', 'Cert No. RR-COC-003348 (FSC-C222205)')) ?></p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-shield-star-line"></i></div>
          <div class="trust-text">
            <h4><?= h(get_content('home', 'trust_3_title', 'cGMP Compliant')) ?></h4>
            <?php $t3 = get_content('home', 'trust_3_text', 'Strict Line Clearance & QA'); ?>
            <p><?= h($t3 === 'WHO-GMP Manufacturing Standards' ? 'Strict Line Clearance & QA' : $t3) ?></p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-capsule-line"></i></div>
          <div class="trust-text">
            <h4><?= h(get_content('home', 'trust_5_title', 'WHO-GMP Compliant')) ?></h4>
            <p><?= h(get_content('home', 'trust_5_text', 'Manufacturing Standards')) ?></p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-building-2-line"></i></div>
          <div class="trust-text">
            <h4><?= h(get_content('home', 'trust_4_title', 'Unit 1 & Unit 2 Plants')) ?></h4>
            <p><?= h(get_content('home', 'trust_4_text', 'Korangi Creek Industrial Park')) ?></p>
          </div>
        </div>
      </div>
    </section>

    <!-- Supporting Value Points Section -->
    <section class="values-section">
      <div class="container">
        <div class="values-grid">
          <!-- Left Column: Narrative & CTA -->
          <div class="values-content">
            <span class="section-subtitle"><?= h(get_content('home', 'values_subtitle', 'Supporting Value Points')) ?></span>
            <h2><?= h(get_content('home', 'values_title', 'Engineered for Compliance & Pharmaceutical Precision')) ?></h2>
            <p class="values-intro"><?= h(get_content('home', 'values_intro', 'As a dedicated pharmaceutical secondary packaging partner, Prince Art Packages operates under strict cGMP and ISO 9001:2015 standards across two state-of-the-art facilities in Korangi Creek Industrial Park, Karachi. We deliver audited packaging integrity designed to meet rigorous global regulatory audits.')) ?></p>
            <div class="values-cta">
              <?= render_cta_buttons('home', 'values_cta', '<a href="about.php" class="btn btn-navy"><i class="ri-information-line"></i> About Prince Art <i class="ri-arrow-right-line" style="margin-left:0.35rem;"></i></a>') ?>
            </div>
          </div>

          <!-- Right Column: 4 Supporting Value Point Cards -->
          <div class="values-cards-grid">
            <!-- 01 — Pharmaceutical Expertise -->
            <div class="value-card">
              <div class="value-card-header">
                <span class="value-card-num">01</span>
                <div class="value-card-icon"><i class="ri-capsule-line"></i></div>
              </div>
              <h3><?= h(get_content('home', 'value_1_title', 'Pharmaceutical Expertise')) ?></h3>
              <p><?= h(get_content('home', 'value_1_text', 'Specialized secondary packaging solutions for pharmaceutical products.')) ?></p>
            </div>

            <!-- 02 — Compliance Focused -->
            <div class="value-card">
              <div class="value-card-header">
                <span class="value-card-num">02</span>
                <div class="value-card-icon"><i class="ri-shield-check-line"></i></div>
              </div>
              <h3><?= h(get_content('home', 'value_2_title', 'Compliance Focused')) ?></h3>
              <p><?= h(get_content('home', 'value_2_text', 'Packaging manufactured with quality and regulatory requirements in mind.')) ?></p>
            </div>

            <!-- 03 — Precision Manufacturing -->
            <div class="value-card">
              <div class="value-card-header">
                <span class="value-card-num">03</span>
                <div class="value-card-icon"><i class="ri-equalizer-line"></i></div>
              </div>
              <h3><?= h(get_content('home', 'value_3_title', 'Precision Manufacturing')) ?></h3>
              <p><?= h(get_content('home', 'value_3_text', 'Controlled production processes for consistent packaging quality.')) ?></p>
            </div>

            <!-- 04 — Custom Solutions -->
            <div class="value-card">
              <div class="value-card-header">
                <span class="value-card-num">04</span>
                <div class="value-card-icon"><i class="ri-tools-line"></i></div>
              </div>
              <h3><?= h(get_content('home', 'value_4_title', 'Custom Solutions')) ?></h3>
              <p><?= h(get_content('home', 'value_4_text', 'Packaging engineered according to product and customer requirements.')) ?></p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Capability Strip / Section 04: Pharmaceutical Packaging Solutions Slider -->
    <section class="section product-slider-section" style="background-color: var(--bg-white);">
      <div class="container">
        <div class="slider-header-wrapper">
          <div class="section-header" style="margin-bottom: 0; text-align: left; max-width: 700px;">
            <span class="section-subtitle"><?= h(get_content('home', 'capability_subtitle', 'PHARMACEUTICAL PACKAGING SOLUTIONS')) ?></span>
            <h2><?= h(get_content('home', 'capability_title', 'Comprehensive Pharmaceutical Packaging Solutions')) ?></h2>
            <p><?= h(get_content('home', 'capability_intro', 'Purpose-engineered paperboard packaging, precision inserts, self-adhesive roll labels, and proprietary security features.')) ?></p>
          </div>

          <!-- Slider Prev / Next Controls -->
          <div class="slider-controls">
            <button type="button" class="slider-btn slider-btn-prev" aria-label="Previous products" id="prodSliderPrev">
              <i class="ri-arrow-left-line"></i>
            </button>
            <button type="button" class="slider-btn slider-btn-next" aria-label="Next products" id="prodSliderNext">
              <i class="ri-arrow-right-line"></i>
            </button>
          </div>
        </div>

        <!-- 6 Cards Slider Track -->
        <div class="product-slider-container">
          <div class="product-slider-track" id="productSliderTrack">

            <!-- Card 01: Printed Cartons -->
            <div class="card product-slide-card">
              <?= render_image(get_content('home', 'feat_1_image', 'assets/images/prod_cartons.jpg'), 'Printed Cartons', 'card-img-top') ?>
              <div class="card-body">
                <div class="card-text-content">
                  <h3 class="card-title"><?= h(get_content('home', 'feat_1_title', 'Printed Cartons')) ?></h3>
                  <p><?= h(get_content('home', 'feat_1_text', 'Reverse tuck, crash lock, tamper-evident, and child-resistant cartons printed on food & pharma-grade virgin board.')) ?></p>
                </div>
                <div class="card-btn-wrapper">
                  <?= render_cta_buttons('home', 'feat_cartons', '<a href="products.php#product-folding-cartons" class="btn btn-gold btn-sm">Specifications &rarr;</a>') ?>
                </div>
              </div>
            </div>

            <!-- Card 02: Leaf-Inserts -->
            <div class="card product-slide-card">
              <?= render_image(get_content('home', 'feat_2_image', 'assets/images/prod_leaflets.jpg'), 'Leaf-Inserts', 'card-img-top') ?>
              <div class="card-body">
                <div class="card-text-content">
                  <h3 class="card-title"><?= h(get_content('home', 'feat_2_title', 'Leaf-Inserts')) ?></h3>
                  <p><?= h(get_content('home', 'feat_2_text', 'Ultra-thin 27gsm to 60gsm prescribing information inserts, cross-folded or miniature outserts for automated packaging lines.')) ?></p>
                </div>
                <div class="card-btn-wrapper">
                  <?= render_cta_buttons('home', 'feat_leaflets', '<a href="products.php#product-leaf-inserts" class="btn btn-gold btn-sm">Specifications &rarr;</a>') ?>
                </div>
              </div>
            </div>

            <!-- Card 03: Printed Labels & Tamper-Evident -->
            <div class="card product-slide-card">
              <?= render_image(get_content('home', 'feat_3_image', 'assets/images/prod_labels.jpg'), 'Printed Labels & Tamper-Evident', 'card-img-top') ?>
              <div class="card-body">
                <div class="card-text-content">
                  <h3 class="card-title"><?= h(get_content('home', 'feat_3_title', 'Printed Labels & Tamper-Evident')) ?></h3>
                  <p><?= h(get_content('home', 'feat_3_text', 'Self-adhesive roll labels for vials, bottles, ampoules, and destructible tamper-evident security seals with 2D barcode serialization.')) ?></p>
                </div>
                <div class="card-btn-wrapper">
                  <?= render_cta_buttons('home', 'feat_labels', '<a href="products.php#product-printed-labels" class="btn btn-gold btn-sm">Specifications &rarr;</a>') ?>
                </div>
              </div>
            </div>

            <!-- Card 04: Tamper-Evident Packaging -->
            <div class="card product-slide-card">
              <?= render_image(get_content('home', 'feat_4_image', 'assets/images/prod_tamper_labels.jpg'), 'Tamper-Evident Packaging', 'card-img-top') ?>
              <div class="card-body">
                <div class="card-text-content">
                  <h3 class="card-title"><?= h(get_content('home', 'feat_4_title', 'Tamper-Evident Packaging')) ?></h3>
                  <p><?= h(get_content('home', 'feat_4_text', 'Security-focused packaging and tamper-evident solutions designed to help protect pharmaceutical products.')) ?></p>
                </div>
                <div class="card-btn-wrapper">
                  <?= render_cta_buttons('home', 'feat_tamper', '<a href="products.php#product-temper-evident" class="btn btn-gold btn-sm">Specifications &rarr;</a>') ?>
                </div>
              </div>
            </div>

            <!-- Card 05: ColdSeal Blister Wallets -->
            <div class="card product-slide-card">
              <?= render_image(get_content('home', 'feat_5_image', 'assets/images/coldseal.jpg'), 'ColdSeal Blister Wallets', 'card-img-top') ?>
              <div class="card-body">
                <div class="card-text-content">
                  <h3 class="card-title"><?= h(get_content('home', 'feat_5_title', 'ColdSeal Blister Wallets')) ?></h3>
                  <p><?= h(get_content('home', 'feat_5_text', 'Secure, cold-seal packaging designed to protect pharmaceutical products with reliable sealing and easy handling.')) ?></p>
                </div>
                <div class="card-btn-wrapper">
                  <?= render_cta_buttons('home', 'feat_coldseal', '<a href="products.php#product-cold-seal-wallet" class="btn btn-gold btn-sm">Specifications &rarr;</a>') ?>
                </div>
              </div>
            </div>

            <!-- Card 06: Anti-Counterfeit Packaging -->
            <div class="card product-slide-card">
              <?= render_image(get_content('home', 'feat_6_image', 'assets/images/engravix.jpg'), 'Anti-Counterfeit Packaging', 'card-img-top') ?>
              <div class="card-body">
                <div class="card-text-content">
                  <h3 class="card-title"><?= h(get_content('home', 'feat_6_title', 'Anti-Counterfeit Packaging')) ?></h3>
                  <p><?= h(get_content('home', 'feat_6_text', 'Security-focused packaging solutions designed to help protect pharmaceutical products from counterfeiting and tampering.')) ?></p>
                </div>
                <div class="card-btn-wrapper">
                  <?= render_cta_buttons('home', 'feat_anticounterfeit', '<a href="products.php#product-3d-engravix" class="btn btn-gold btn-sm">Specifications &rarr;</a>') ?>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Slider Pagination Dots -->
        <div class="slider-dots" id="productSliderDots">
          <button type="button" class="slider-dot active" aria-label="Slide 1"></button>
          <button type="button" class="slider-dot" aria-label="Slide 2"></button>
          <button type="button" class="slider-dot" aria-label="Slide 3"></button>
          <button type="button" class="slider-dot" aria-label="Slide 4"></button>
          <button type="button" class="slider-dot" aria-label="Slide 5"></button>
          <button type="button" class="slider-dot" aria-label="Slide 6"></button>
        </div>
      </div>
    </section>

    <!-- SECTION 05 — WHY CHOOSE PRINCE ART PACKAGES -->
    <section class="why-choose-section">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle"><?= h(get_content('home', 'why_subtitle', 'WHY CHOOSE PRINCE ART PACKAGES')) ?></span>
          <h2><?= h(get_content('home', 'why_title', 'Why Pharmaceutical Manufacturers Choose Prince Art Packages')) ?></h2>
          <p><?= h(get_content('home', 'why_intro', 'Delivering audit-ready secondary packaging backed by decades of specialized manufacturing expertise, advanced multi-color offset printing, strict line clearance protocols, and end-to-end quality assurance.')) ?></p>
        </div>

        <div class="why-choose-grid">
          <!-- 01 — Pharmaceutical Packaging Expertise -->
          <div class="usp-card">
            <div class="usp-card-header">
              <span class="usp-card-num">01</span>
              <div class="usp-card-icon"><i class="ri-capsule-line"></i></div>
            </div>
            <h3><?= h(get_content('home', 'why_1_title', 'Pharmaceutical Packaging Expertise')) ?></h3>
            <p><?= h(get_content('home', 'why_1_text', 'Specialized experience in pharmaceutical secondary packaging.')) ?></p>
          </div>

          <!-- 02 — Compliance-Focused Manufacturing -->
          <div class="usp-card">
            <div class="usp-card-header">
              <span class="usp-card-num">02</span>
              <div class="usp-card-icon"><i class="ri-shield-check-line"></i></div>
            </div>
            <h3><?= h(get_content('home', 'why_2_title', 'Compliance-Focused Manufacturing')) ?></h3>
            <p><?= h(get_content('home', 'why_2_text', 'Processes aligned with pharmaceutical quality and compliance requirements.')) ?></p>
          </div>

          <!-- 03 — Advanced Printing & Finishing -->
          <div class="usp-card">
            <div class="usp-card-header">
              <span class="usp-card-num">03</span>
              <div class="usp-card-icon"><i class="ri-printer-line"></i></div>
            </div>
            <h3><?= h(get_content('home', 'why_3_title', 'Advanced Printing & Finishing')) ?></h3>
            <p><?= h(get_content('home', 'why_3_text', 'High-quality printing and finishing capabilities for pharmaceutical packaging.')) ?></p>
          </div>

          <!-- 04 — Security & Anti-Counterfeit Solutions -->
          <div class="usp-card">
            <div class="usp-card-header">
              <span class="usp-card-num">04</span>
              <div class="usp-card-icon"><i class="ri-shield-keyhole-line"></i></div>
            </div>
            <h3><?= h(get_content('home', 'why_4_title', 'Security & Anti-Counterfeit Solutions')) ?></h3>
            <p><?= h(get_content('home', 'why_4_text', 'Packaging technologies designed to improve product security and brand protection.')) ?></p>
          </div>

          <!-- 05 — Quality Control -->
          <div class="usp-card">
            <div class="usp-card-header">
              <span class="usp-card-num">05</span>
              <div class="usp-card-icon"><i class="ri-flask-line"></i></div>
            </div>
            <h3><?= h(get_content('home', 'why_5_title', 'Quality Control')) ?></h3>
            <p><?= h(get_content('home', 'why_5_text', 'Controlled quality processes throughout manufacturing.')) ?></p>
          </div>

          <!-- 06 — Customized Packaging -->
          <div class="usp-card">
            <div class="usp-card-header">
              <span class="usp-card-num">06</span>
              <div class="usp-card-icon"><i class="ri-tools-line"></i></div>
            </div>
            <h3><?= h(get_content('home', 'why_6_title', 'Customized Packaging')) ?></h3>
            <p><?= h(get_content('home', 'why_6_text', 'Solutions developed according to customer packaging requirements.')) ?></p>
          </div>
        </div>

        <!-- CTA: Explore Our Capabilities -->
        <div class="why-choose-cta-wrap">
          <?= render_cta_buttons('home', 'why_choose_cta', '<a href="capabilities.php" class="btn btn-navy"><i class="ri-settings-4-line"></i> Explore Our Capabilities <i class="ri-arrow-right-line" style="margin-left:0.35rem;"></i></a>') ?>
        </div>
      </div>
    </section>

    <!-- SECTION 07 — INDUSTRIES WE SERVE (Compact) -->
    <section class="industries-section">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle"><?= h(get_content('home', 'ind_sec_subtitle', 'INDUSTRIES WE SERVE')) ?></span>
          <h2><?= h(get_content('home', 'ind_sec_title', 'Packaging Solutions for Regulated Industries')) ?></h2>
          <p><?= h(get_content('home', 'ind_sec_intro', 'Specialized secondary packaging manufacturing tailored for regulated formulation plants.')) ?></p>
        </div>

        <div class="industry-grid">
          <!-- Card 1: Pharmaceutical -->
          <div class="industry-card">
            <div class="industry-card-icon">
              <i class="ri-capsule-fill"></i>
            </div>
            <h3><?= h(get_content('home', 'ind_1_title', 'Pharmaceutical')) ?></h3>
            <p><?= h(get_content('home', 'ind_1_text', 'Prescription solid orals, liquids, parenterals, and cGMP regulated formulations.')) ?></p>
            <div class="industry-pkg-compact">
              <span class="pkg-label"><i class="ri-box-3-line"></i> Packaging</span>
              <span class="pkg-text"><?= h(get_content('home', 'ind_1_pkg', 'Printed Cartons & Leaflets')) ?></span>
            </div>
          </div>

          <!-- Card 2: Healthcare -->
          <div class="industry-card">
            <div class="industry-card-icon">
              <i class="ri-heart-pulse-fill"></i>
            </div>
            <h3><?= h(get_content('home', 'ind_2_title', 'Healthcare')) ?></h3>
            <p><?= h(get_content('home', 'ind_2_text', 'Hospital supplies, diagnostic kits, and patient-care secondary packaging.')) ?></p>
            <div class="industry-pkg-compact">
              <span class="pkg-label"><i class="ri-box-3-line"></i> Packaging</span>
              <span class="pkg-text"><?= h(get_content('home', 'ind_2_pkg', 'Barrier Cartons & Kit Boxes')) ?></span>
            </div>
          </div>

          <!-- Card 3: Medical -->
          <div class="industry-card">
            <div class="industry-card-icon">
              <i class="ri-syringe-fill"></i>
            </div>
            <h3><?= h(get_content('home', 'ind_3_title', 'Medical')) ?></h3>
            <p><?= h(get_content('home', 'ind_3_text', 'Sterile surgical disposables, diagnostic reagents, and device packaging.')) ?></p>
            <div class="industry-pkg-compact">
              <span class="pkg-label"><i class="ri-box-3-line"></i> Packaging</span>
              <span class="pkg-text"><?= h(get_content('home', 'ind_3_pkg', 'Cold-Chain Wallets & Inserts')) ?></span>
            </div>
          </div>

          <!-- Card 4: Consumer Healthcare / OTC -->
          <div class="industry-card">
            <div class="industry-card-icon">
              <i class="ri-medicine-bottle-fill"></i>
            </div>
            <h3><?= h(get_content('home', 'ind_4_title', 'Consumer Healthcare / OTC')) ?></h3>
            <p><?= h(get_content('home', 'ind_4_text', 'OTC medicines, vitamins, supplements, and wellness formulations.')) ?></p>
            <div class="industry-pkg-compact">
              <span class="pkg-label"><i class="ri-box-3-line"></i> Packaging</span>
              <span class="pkg-text"><?= h(get_content('home', 'ind_4_pkg', 'High-Gloss Boxes & Labels')) ?></span>
            </div>
          </div>

          <!-- Card 5: Other Regulated Products -->
          <div class="industry-card">
            <div class="industry-card-icon">
              <i class="ri-shield-check-fill"></i>
            </div>
            <h3><?= h(get_content('home', 'ind_5_title', 'Other Regulated Products')) ?></h3>
            <p><?= h(get_content('home', 'ind_5_text', 'Cosmeceuticals, chemicals, and veterinary health products.')) ?></p>
            <div class="industry-pkg-compact">
              <span class="pkg-label"><i class="ri-box-3-line"></i> Packaging</span>
              <span class="pkg-text"><?= h(get_content('home', 'ind_5_pkg', 'Tamper Seals & Serialized Cartons')) ?></span>
            </div>
          </div>
        </div>

        <!-- CTA: Explore Industry Solutions -->
        <div class="industry-cta-wrap">
          <?= render_cta_buttons('home', 'industry_solutions_cta', '<a href="industries.php" class="btn btn-navy btn-sm"><i class="ri-building-line"></i> Explore Industry Solutions <i class="ri-arrow-right-line" style="margin-left:0.35rem;"></i></a>') ?>
        </div>
      </div>
    </section>

    <!-- SECTION 08 — QUALITY & COMPLIANCE -->
    <section class="quality-section">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle"><?= h(get_content('home', 'quality_sec_subtitle', 'QUALITY & COMPLIANCE')) ?></span>
          <h2><?= h(get_content('home', 'quality_sec_title', 'Quality & Compliance at Every Stage')) ?></h2>
          <p><?= h(get_content('home', 'quality_sec_intro', 'Verified international quality certifications, strict cGMP controls, and in-house laboratory testing ensuring audit-ready packaging.')) ?></p>
        </div>

        <!-- 4 Certifications Row -->
        <div class="quality-cert-pills">
          <!-- ISO 9001:2015 -->
          <div class="cert-pill-item">
            <i class="ri-award-fill"></i>
            <div class="cert-pill-text">
              <strong><?= h(get_content('home', 'qual_cert_1_title', 'ISO 9001:2015')) ?></strong>
              <span><?= h(get_content('home', 'qual_cert_1_sub', 'Certified QMS System')) ?></span>
            </div>
          </div>

          <!-- cGMP Compliance -->
          <div class="cert-pill-item">
            <i class="ri-shield-check-fill"></i>
            <div class="cert-pill-text">
              <strong><?= h(get_content('home', 'qual_cert_2_title', 'cGMP Compliance')) ?></strong>
              <span><?= h(get_content('home', 'qual_cert_2_sub', 'Line Clearance & Hygiene')) ?></span>
            </div>
          </div>

          <!-- WHO-GMP Standards -->
          <div class="cert-pill-item">
            <i class="ri-health-book-fill"></i>
            <div class="cert-pill-text">
              <strong><?= h(get_content('home', 'qual_cert_3_title', 'WHO-GMP Standards')) ?></strong>
              <span><?= h(get_content('home', 'qual_cert_3_sub', 'Manufacturing Assurance')) ?></span>
            </div>
          </div>

          <!-- FSC Chain of Custody -->
          <div class="cert-pill-item cert-pill-fsc">
            <i class="ri-leaf-fill"></i>
            <div class="cert-pill-text">
              <strong><?= h(get_content('home', 'qual_cert_4_title', 'FSC Chain of Custody')) ?></strong>
              <span><?= h(get_content('home', 'qual_cert_4_sub', 'FSC-STD-40-004 Certified')) ?></span>
            </div>
          </div>
        </div>

        <!-- 3 Operational Pillars -->
        <div class="quality-pillars-grid">
          <!-- Quality Control -->
          <div class="quality-pillar-card">
            <div class="pillar-card-header">
              <div class="pillar-icon"><i class="ri-flask-line"></i></div>
              <h3><?= h(get_content('home', 'qual_pill_1_title', 'Quality Control')) ?></h3>
            </div>
            <p><?= h(get_content('home', 'qual_pill_1_text', 'In-house OurLAB testing: spectrophotometer color consistency, Pantone shade formulation, and ink-rub resistance.')) ?></p>
          </div>

          <!-- Manufacturing Controls -->
          <div class="quality-pillar-card">
            <div class="pillar-card-header">
              <div class="pillar-icon"><i class="ri-equalizer-line"></i></div>
              <h3><?= h(get_content('home', 'qual_pill_2_title', 'Manufacturing Controls')) ?></h3>
            </div>
            <p><?= h(get_content('home', 'qual_pill_2_text', 'Strict line clearance, air-controlled production floors, and dual-plant segregation to prevent artwork mix-ups.')) ?></p>
          </div>

          <!-- Inspection & Traceability -->
          <div class="quality-pillar-card">
            <div class="pillar-card-header">
              <div class="pillar-icon"><i class="ri-barcode-box-line"></i></div>
              <h3><?= h(get_content('home', 'qual_pill_3_title', 'Inspection & Traceability')) ?></h3>
            </div>
            <p><?= h(get_content('home', 'qual_pill_3_text', 'Automated optical camera inspection, 2D serialization verification, and comprehensive batch retention archives.')) ?></p>
          </div>
        </div>

        <!-- CTA: Explore Quality & Compliance -->
        <div class="quality-cta-wrap">
          <?= render_cta_buttons('home', 'quality_compliance_cta', '<a href="quality.php" class="btn btn-navy"><i class="ri-shield-check-line"></i> Explore Quality & Compliance <i class="ri-arrow-right-line" style="margin-left:0.35rem;"></i></a>') ?>
        </div>
      </div>
    </section>

    <!-- SECTION 10 — CLIENTS / TRUST / SOCIAL PROOF -->
    <section class="social-proof-section">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle"><?= h(get_content('home', 'social_sec_subtitle', 'CLIENT TRUST & SOCIAL PROOF')) ?></span>
          <h2><?= h(get_content('home', 'social_sec_title', 'Trusted by Pharmaceutical & Healthcare Companies')) ?></h2>
          <p><?= h(get_content('home', 'social_sec_intro', 'Delivering audit-verified secondary packaging excellence, zero-defect delivery track records, and trusted partnerships across leading formulation plants.')) ?></p>
        </div>

        <!-- Trust Metric Badges -->
        <div class="trust-metrics-strip">
          <div class="metric-badge-item">
            <div class="metric-badge-icon"><i class="ri-history-line"></i></div>
            <div class="metric-badge-content">
              <strong><?= h(get_content('home', 'stat_1_val', '40+')) ?></strong>
              <span><?= h(get_content('home', 'stat_1_lbl', 'Years of Heritage')) ?></span>
            </div>
          </div>
          <div class="metric-badge-item">
            <div class="metric-badge-icon"><i class="ri-building-2-line"></i></div>
            <div class="metric-badge-content">
              <strong><?= h(get_content('home', 'stat_2_val', '2 Plants')) ?></strong>
              <span><?= h(get_content('home', 'stat_2_lbl', 'Korangi Creek, Karachi')) ?></span>
            </div>
          </div>
          <div class="metric-badge-item">
            <div class="metric-badge-icon"><i class="ri-box-3-line"></i></div>
            <div class="metric-badge-content">
              <strong><?= h(get_content('home', 'stat_3_val', '150M+')) ?></strong>
              <span><?= h(get_content('home', 'stat_3_lbl', 'Annual Units Delivered')) ?></span>
            </div>
          </div>
          <div class="metric-badge-item">
            <div class="metric-badge-icon"><i class="ri-checkbox-circle-line"></i></div>
            <div class="metric-badge-content">
              <strong><?= h(get_content('home', 'stat_4_val', '99.98%')) ?></strong>
              <span><?= h(get_content('home', 'stat_4_lbl', 'Zero-Defect Standard')) ?></span>
            </div>
          </div>
        </div>

        <!-- Customer Testimonials Grid -->
        <div class="testimonials-grid">
          <!-- Testimonial 1 -->
          <div class="testimonial-card">
            <div class="testi-header">
              <div class="testi-stars">
                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
              </div>
              <i class="ri-double-quotes-l testi-quote-icon"></i>
            </div>
            <p class="testi-quote">“<?= h(get_content('home', 'testi_1_quote', 'Prince Art Packages has consistently met our stringent QA line clearance standards. Their printed cartons run seamlessly on our high-speed cartoning lines with zero jamming.')) ?>”</p>
            <div class="testi-author-wrap">
              <div class="author-avatar">TM</div>
              <div class="author-info">
                <strong><?= h(get_content('home', 'testi_1_name', 'Dr. Tariq Mahmood')) ?></strong>
                <span><?= h(get_content('home', 'testi_1_desig', 'Head of Supply Chain & QA')) ?></span>
                <span class="author-company"><?= h(get_content('home', 'testi_1_company', 'Leading National Pharmaceutical Manufacturer')) ?></span>
              </div>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="testimonial-card">
            <div class="testi-header">
              <div class="testi-stars">
                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
              </div>
              <i class="ri-double-quotes-l testi-quote-icon"></i>
            </div>
            <p class="testi-quote">“<?= h(get_content('home', 'testi_2_quote', 'Their miniature outsert leaflets and 27gsm inserts passed all automated barcode mix-up inspections. The dedication to compliance and delivery accuracy is unmatched.')) ?>”</p>
            <div class="testi-author-wrap">
              <div class="author-avatar">AS</div>
              <div class="author-info">
                <strong><?= h(get_content('home', 'testi_2_name', 'Asif R. Siddiqui')) ?></strong>
                <span><?= h(get_content('home', 'testi_2_desig', 'Director of Operations & Procurement')) ?></span>
                <span class="author-company"><?= h(get_content('home', 'testi_2_company', 'Healthcare & OTC Formulations Ltd.')) ?></span>
              </div>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="testimonial-card">
            <div class="testi-header">
              <div class="testi-stars">
                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
              </div>
              <i class="ri-double-quotes-l testi-quote-icon"></i>
            </div>
            <p class="testi-quote">“<?= h(get_content('home', 'testi_3_quote', 'The dual-manufacturing setup in Korangi Creek gives our supply chain total peace of mind. Their technical team helped re-engineer our carton grain direction to eliminate waste.')) ?>”</p>
            <div class="testi-author-wrap">
              <div class="author-avatar">FK</div>
              <div class="author-info">
                <strong><?= h(get_content('home', 'testi_3_name', 'Farhan A. Khan')) ?></strong>
                <span><?= h(get_content('home', 'testi_3_desig', 'Production & Packaging Manager')) ?></span>
                <span class="author-company"><?= h(get_content('home', 'testi_3_company', 'Multinational Healthcare Group')) ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- CTA: Explore Case Studies -->
        <div class="social-proof-cta-wrap">
          <?= render_cta_buttons('home', 'social_proof_cta', '<a href="case-studies.php" class="btn btn-navy"><i class="ri-article-line"></i> Explore Case Studies &amp; Results <i class="ri-arrow-right-line" style="margin-left:0.35rem;"></i></a>') ?>
        </div>
      </div>
    </section>

    <!-- SECTION 12 — QUICK QUOTE / INQUIRY FORM -->
    <section class="quick-quote-section" id="quote-form">
      <div class="container">
        <div class="quick-quote-grid">
          <!-- Left Column: Context & Benefits -->
          <div class="quote-info-col">
            <span class="section-subtitle"><?= h(get_content('home', 'quote_sec_subtitle', 'QUICK RFQ & INQUIRY')) ?></span>
            <h2><?= h(get_content('home', 'quote_sec_title', 'Request a Packaging Quote')) ?></h2>
            <p class="quote-lead-text"><?= h(get_content('home', 'quote_sec_lead', 'Submit your pharmaceutical packaging requirements. Our technical sales and engineering team will evaluate your die-lines and provide an audit-ready commercial quotation within 24 business hours.')) ?></p>

            <div class="quote-benefits-list">
              <div class="quote-benefit-item">
                <div class="quote-benefit-icon"><i class="ri-time-line"></i></div>
                <div class="quote-benefit-text">
                  <strong><?= h(get_content('home', 'quote_benefit_1_title', '24-Hour Quotation Turnaround')) ?></strong>
                  <span><?= h(get_content('home', 'quote_benefit_1_sub', 'Fast response with precise cost breakdowns and minimum order volumes.')) ?></span>
                </div>
              </div>

              <div class="quote-benefit-item">
                <div class="quote-benefit-icon"><i class="ri-shield-check-line"></i></div>
                <div class="quote-benefit-text">
                  <strong><?= h(get_content('home', 'quote_benefit_2_title', 'cGMP & WHO-GMP Compliant')) ?></strong>
                  <span><?= h(get_content('home', 'quote_benefit_2_sub', 'Complete QA documentation, COA certificates, and plant audit support.')) ?></span>
                </div>
              </div>

              <div class="quote-benefit-item">
                <div class="quote-benefit-icon"><i class="ri-box-3-line"></i></div>
                <div class="quote-benefit-text">
                  <strong><?= h(get_content('home', 'quote_benefit_3_title', 'Die-lines & Physical Samples')) ?></strong>
                  <span><?= h(get_content('home', 'quote_benefit_3_sub', 'Sample packs, carton structural mock-ups, and folding outsert templates.')) ?></span>
                </div>
              </div>
            </div>

            <div class="quote-direct-contact">
              <strong>Need urgent technical assistance?</strong> Call our packaging specialists directly at <a href="tel:+922135121925">+92 (21) 3512-1925</a> or email <a href="mailto:sales@princeartpackages.com">sales@princeartpackages.com</a>.
            </div>
          </div>

          <!-- Right Column: Form Card -->
          <div class="quote-form-card">
            <?php if ($quoteSubmitted): ?>
              <div class="alert" style="padding:1.25rem; margin-bottom:1rem; border-radius:8px; background:#d1fae5; color:#065f46; border:1px solid #a7f3d0;">
                <div style="display:flex; align-items:center; gap:0.5rem; font-weight:700; font-size:1rem; margin-bottom:0.35rem;">
                  <i class="ri-checkbox-circle-fill" style="font-size:1.3rem;"></i> Quote Request Received Successfully!
                </div>
                <p style="margin:0; font-size:0.85rem; line-height:1.5;">Thank you for contacting Prince Art Packages. Our packaging engineering team has received your specifications and will respond within 24 business hours.</p>
              </div>
            <?php else: ?>
              <?php if ($quoteError): ?>
                <div class="alert" style="padding:0.85rem 1rem; margin-bottom:1rem; border-radius:6px; background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; font-size:0.84rem;">
                  <i class="ri-error-warning-line"></i> <?= h($quoteError) ?>
                </div>
              <?php endif; ?>

              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="quick_quote">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

                <div class="quote-form-grid">
                  <!-- Full Name (Required) -->
                  <div class="quote-form-group">
                    <label for="rfq_fullname">Full Name <span class="req-star">*</span></label>
                    <input type="text" id="rfq_fullname" name="full_name" placeholder="e.g. Dr. Salman Ahmed" value="<?= h($_POST['full_name'] ?? '') ?>" required>
                  </div>

                  <!-- Company Name (Required) -->
                  <div class="quote-form-group">
                    <label for="rfq_company">Company Name <span class="req-star">*</span></label>
                    <input type="text" id="rfq_company" name="company_name" placeholder="e.g. Searle Pharmaceuticals" value="<?= h($_POST['company_name'] ?? '') ?>" required>
                  </div>

                  <!-- Business Email (Required) -->
                  <div class="quote-form-group">
                    <label for="rfq_email">Business Email <span class="req-star">*</span></label>
                    <input type="email" id="rfq_email" name="email" placeholder="name@company.com" value="<?= h($_POST['email'] ?? '') ?>" required>
                  </div>

                  <!-- Country (Required) -->
                  <div class="quote-form-group">
                    <label for="rfq_country">Country <span class="req-star">*</span></label>
                    <input type="text" id="rfq_country" name="country" placeholder="e.g. Pakistan, UAE, UK" value="<?= h($_POST['country'] ?? '') ?>" required>
                  </div>

                  <!-- Phone Number (Optional) -->
                  <div class="quote-form-group">
                    <label for="rfq_phone">Phone Number <span class="optional-tag">Optional</span></label>
                    <input type="tel" id="rfq_phone" name="phone" placeholder="+92 300 1234567" value="<?= h($_POST['phone'] ?? '') ?>">
                  </div>

                  <!-- Product Type (Optional) -->
                  <div class="quote-form-group">
                    <label for="rfq_product_type">Product Type <span class="optional-tag">Optional</span></label>
                    <select id="rfq_product_type" name="product_type">
                      <option value="">Select Packaging Type</option>
                      <option value="Printed Cartons">Printed Cartons (Secondary Packaging)</option>
                      <option value="Leaf-Inserts">Leaflets &amp; Inserts (27–60 gsm)</option>
                      <option value="Printed Labels">Printed Self-Adhesive Labels</option>
                      <option value="Tamper-Evident Packaging">Tamper-Evident Packaging</option>
                      <option value="ColdSeal Blister Wallets">ColdSeal Blister Wallets</option>
                      <option value="Anti-Counterfeit Packaging">3D-Engravix™ Anti-Counterfeit</option>
                      <option value="Comprehensive Secondary Solution">Multiple / Complete Packaging Range</option>
                    </select>
                  </div>

                  <!-- Packaging Requirement (Required) -->
                  <div class="quote-form-group">
                    <label for="rfq_requirement">Packaging Requirement <span class="req-star">*</span></label>
                    <input type="text" id="rfq_requirement" name="packaging_requirement" placeholder="e.g. 350gsm FBB Reverse Tuck Box" value="<?= h($_POST['packaging_requirement'] ?? '') ?>" required>
                  </div>

                  <!-- Estimated Quantity (Optional) -->
                  <div class="quote-form-group">
                    <label for="rfq_qty">Estimated Quantity <span class="optional-tag">Optional</span></label>
                    <input type="text" id="rfq_qty" name="estimated_quantity" placeholder="e.g. 50,000 / 100,000 units" value="<?= h($_POST['estimated_quantity'] ?? '') ?>">
                  </div>

                  <!-- Upload Artwork / Specification (Optional) -->
                  <div class="quote-form-group col-span-2">
                    <label for="rfq_artwork">Upload Artwork / Specification <span class="optional-tag">Optional (PDF, AI, ZIP, max 10MB)</span></label>
                    <input type="file" id="rfq_artwork" name="artwork" class="quote-file-input" accept=".pdf,.doc,.docx,.ai,.psd,.zip,.jpg,.jpeg,.png">
                  </div>

                  <!-- Message (Required) -->
                  <div class="quote-form-group col-span-2">
                    <label for="rfq_message">Message <span class="req-star">*</span></label>
                    <textarea id="rfq_message" name="message" rows="3" placeholder="Please mention carton dimensions (L × W × H), paperboard type, color count (CMYK/Pantone), or delivery deadline..." required><?= h($_POST['message'] ?? '') ?></textarea>
                  </div>

                  <!-- Submit CTA Button -->
                  <div class="quote-form-group col-span-2">
                    <button type="submit" class="btn btn-gold quote-submit-btn">
                      <i class="ri-send-plane-fill"></i> <?= h(get_content('home', 'quote_cta_label', 'Request My Quote')) ?>
                    </button>
                  </div>
                </div>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 13 — FAQ -->
    <section class="faq-section">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle"><?= h(get_content('home', 'faq_sec_subtitle', 'FREQUENTLY ASKED QUESTIONS')) ?></span>
          <h2><?= h(get_content('home', 'faq_sec_title', 'Frequently Asked Questions')) ?></h2>
          <p><?= h(get_content('home', 'faq_sec_intro', 'Essential answers regarding our pharmaceutical secondary packaging capabilities, compliance certifications, custom specifications, and ordering process.')) ?></p>
        </div>

        <div class="faq-grid">
          <!-- Left Column (Questions 1 to 6) -->
          <div class="faq-column">
            <!-- Q1 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_1_q', 'What pharmaceutical packaging products does Prince Art Packages manufacture?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_1_a', 'We manufacture specialized secondary packaging including paperboard printed cartons (reverse tuck, crash lock), ultra-thin 27gsm–60gsm patient prescribing information leaflets, miniature multi-folded outserts, self-adhesive roll labels, tamper-evident packaging, and ColdSeal blister wallets.')) ?></p>
              </div>
            </details>

            <!-- Q2 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_2_q', 'Does Prince Art Packages manufacture custom pharmaceutical cartons?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_2_a', 'Yes. We engineer bespoke paperboard cartons customized to your product dimensions (blister packs, vials, bottles, ampoules) with custom grammage (230gsm–400gsm), high-precision die-cutting, embossing, Braille text, and specialty barrier finishes.')) ?></p>
              </div>
            </details>

            <!-- Q3 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_3_q', 'Are your manufacturing facilities cGMP compliant?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_3_a', 'Yes. Both Unit 1 and Unit 2 in Korangi Creek Industrial Park operate under strict cGMP protocols, featuring segregated production lines, physical line clearance procedures, positive air pressure, and zero cross-contamination safeguards.')) ?></p>
              </div>
            </details>

            <!-- Q4 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_4_q', 'What certifications does Prince Art Packages have?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_4_a', 'We hold ISO 9001:2015 Quality Management System Certification (Cert No: KQ.2025.5393) and FSC Chain of Custody Certification (FSC-C222205 / FSC-STD-40-004), fully aligned with WHO-GMP manufacturing and auditing standards.')) ?></p>
              </div>
            </details>

            <!-- Q5 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_5_q', 'Do you manufacture pharmaceutical leaflets and inserts?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_5_a', 'Yes. We print ultra-thin 27gsm to 60gsm prescribing information inserts and miniature outserts on high-speed specialized folding equipment equipped with 100% electronic optical scanning to prevent line mix-ups.')) ?></p>
              </div>
            </details>

            <!-- Q6 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_6_q', 'Do you manufacture printed pharmaceutical labels?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_6_a', 'Yes. We produce high-density self-adhesive roll labels for ampoules, vials, and bottles, printed on pharma-grade substrates with durable adhesives, 2D DataMatrix serialization, and scratch-resistant varnishes.')) ?></p>
              </div>
            </details>
          </div>

          <!-- Right Column (Questions 7 to 11) -->
          <div class="faq-column">
            <!-- Q7 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_7_q', 'Do you provide tamper-evident packaging?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_7_a', 'Yes. We engineer integrated tamper-evident cartons (perforated tear strips, locking security tucks) and destructible tamper-evident security labels that deliver immediate visual evidence of tampering.')) ?></p>
              </div>
            </details>

            <!-- Q8 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_8_q', 'Do you offer anti-counterfeit pharmaceutical packaging?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_8_a', 'Yes. We offer proprietary optical security solutions including our 3D-Engravix™ security feature, covert micro-text, serialized 2D barcodes, guilloche patterns, and security inks to protect pharmaceutical brands from counterfeiting.')) ?></p>
              </div>
            </details>

            <!-- Q9 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_9_q', 'What information is required to request a quotation?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_9_a', 'To receive an accurate quotation, please provide the packaging format (carton, leaflet, label), dimensions (L × W × H), paperboard/paper GSM, color specifications (CMYK/Pantone), special finishing requirements, and estimated order quantities.')) ?></p>
              </div>
            </details>

            <!-- Q10 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_10_q', 'Where is Prince Art Packages located?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_10_a', 'Our corporate offices and two advanced production plants are located at WH-17-A8, ST-1, Sector 38 and Plot 239, Korangi Creek Industrial Park, Karachi, Pakistan.')) ?></p>
              </div>
            </details>

            <!-- Q11 -->
            <details class="faq-item">
              <summary class="faq-summary">
                <span class="faq-q-text"><?= h(get_content('home', 'faq_11_q', 'Do you supply pharmaceutical packaging to international markets?')) ?></span>
                <span class="faq-icon"><i class="ri-add-line"></i></span>
              </summary>
              <div class="faq-answer">
                <p><?= h(get_content('home', 'faq_11_a', 'Yes. In addition to supplying leading pharmaceutical formulation plants across Pakistan, we export audit-compliant secondary packaging to regulated healthcare clients in international markets.')) ?></p>
              </div>
            </details>
          </div>
        </div>

        <!-- CTA / Contact Prompt -->
        <div class="faq-cta-wrap">
          <?= render_cta_buttons('home', 'faq_cta', '<a href="contact.php" class="btn btn-navy"><i class="ri-question-answer-line"></i> Have More Questions? Contact Our Technical Team <i class="ri-arrow-right-line" style="margin-left:0.35rem;"></i></a>') ?>
        </div>
      </div>
    </section>

    <!-- Structured Data for Topical SEO (Schema.org FAQPage) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What pharmaceutical packaging products does Prince Art Packages manufacture?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We manufacture specialized secondary packaging including paperboard printed cartons (reverse tuck, crash lock), ultra-thin 27gsm–60gsm patient prescribing information leaflets, miniature multi-folded outserts, self-adhesive roll labels, tamper-evident packaging, and ColdSeal blister wallets."
          }
        },
        {
          "@type": "Question",
          "name": "Does Prince Art Packages manufacture custom pharmaceutical cartons?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. We engineer bespoke paperboard cartons customized to your product dimensions (blister packs, vials, bottles, ampoules) with custom grammage (230gsm–400gsm), high-precision die-cutting, embossing, Braille text, and specialty barrier finishes."
          }
        },
        {
          "@type": "Question",
          "name": "Are your manufacturing facilities cGMP compliant?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. Both Unit 1 and Unit 2 in Korangi Creek Industrial Park operate under strict cGMP protocols, featuring segregated production lines, physical line clearance procedures, positive air pressure, and zero cross-contamination safeguards."
          }
        },
        {
          "@type": "Question",
          "name": "What certifications does Prince Art Packages have?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We hold ISO 9001:2015 Quality Management System Certification (Cert No: KQ.2025.5393) and FSC Chain of Custody Certification (FSC-C222205 / FSC-STD-40-004), fully aligned with WHO-GMP manufacturing and auditing standards."
          }
        },
        {
          "@type": "Question",
          "name": "Do you manufacture pharmaceutical leaflets and inserts?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. We print ultra-thin 27gsm to 60gsm prescribing information inserts and miniature outserts on high-speed specialized folding equipment equipped with 100% electronic optical scanning to prevent line mix-ups."
          }
        },
        {
          "@type": "Question",
          "name": "Do you manufacture printed pharmaceutical labels?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. We produce high-density self-adhesive roll labels for ampoules, vials, and bottles, printed on pharma-grade substrates with durable adhesives, 2D DataMatrix serialization, and scratch-resistant varnishes."
          }
        },
        {
          "@type": "Question",
          "name": "Do you provide tamper-evident packaging?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. We engineer integrated tamper-evident cartons (perforated tear strips, locking security tucks) and destructible tamper-evident security labels that deliver immediate visual evidence of tampering."
          }
        },
        {
          "@type": "Question",
          "name": "Do you offer anti-counterfeit pharmaceutical packaging?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. We offer proprietary optical security solutions including our 3D-Engravix™ security feature, covert micro-text, serialized 2D barcodes, guilloche patterns, and security inks to protect pharmaceutical brands from counterfeiting."
          }
        },
        {
          "@type": "Question",
          "name": "What information is required to request a quotation?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "To receive an accurate quotation, please provide the packaging format (carton, leaflet, label), dimensions (L × W × H), paperboard/paper GSM, color specifications (CMYK/Pantone), special finishing requirements, and estimated order quantities."
          }
        },
        {
          "@type": "Question",
          "name": "Where is Prince Art Packages located?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Our corporate offices and two advanced production plants are located at WH-17-A8, ST-1, Sector 38 and Plot 239, Korangi Creek Industrial Park, Karachi, Pakistan."
          }
        },
        {
          "@type": "Question",
          "name": "Do you supply pharmaceutical packaging to international markets?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. In addition to supplying leading pharmaceutical formulation plants across Pakistan, we export audit-compliant secondary packaging to regulated healthcare clients in international markets."
          }
        }
      ]
    }
    </script>

    <!-- Featured Innovation Callout Section -->
    <section class="section" style="padding: 3rem 0 5rem; background-color: var(--bg-white);">
      <div class="container">
        <div class="innovation-banner" style="background:linear-gradient(135deg, #0e4c57 0%, #1C7C8C 100%); color:#ffffff; border-radius:12px; padding:clamp(1.25rem, 4vw, 3rem); margin:0; overflow:hidden; box-sizing:border-box; width:100%; max-width:100%;">
          <div class="innovation-banner-grid">
            <div class="innovation-banner-text" style="min-width:0; overflow-wrap:break-word; word-break:break-word;">
              <span class="cert-pill innovation-pill" style="display:inline-block; background:rgba(255,255,255,0.2); color:#fff; font-size:0.75rem; padding:4px 10px; border-radius:20px; font-weight:700; letter-spacing:0.06em; margin-bottom:0.75rem;"><?= h(get_content('home', 'banner_eyebrow', 'FEATURED INNOVATIONS')) ?></span>
              <h2 class="innovation-banner-heading" style="color:#ffffff; font-size:clamp(1.3rem, 3.5vw, 2.1rem); line-height:1.25; margin:0 0 0.85rem; word-wrap:break-word;"><?= h(get_content('home', 'banner_title', 'ColdSeal Blister Wallet & 3D-Engravix™ Security')) ?></h2>
              <p class="innovation-banner-desc" style="color:rgba(255,255,255,0.9); font-size:clamp(0.85rem, 2vw, 0.98rem); line-height:1.55; margin:0 0 1.25rem;"><?= h(get_content('home', 'banner_text', 'Discover our proprietary anti-counterfeit optical security technology (3D-Engravix™) and pressure-sealed eco packaging (ColdSeal Blister Wallet) that reduces plastic/foil footprint by up to 50%.')) ?></p>
              <?= render_cta_buttons('home', 'banner', '<a href="innovation.php" class="btn btn-gold innovation-banner-btn" style="display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; text-align:center; white-space:normal; line-height:1.35; max-width:100%; box-sizing:border-box;"><i class="ri-lightbulb-line"></i> <span>Learn About Innovation &amp; Technology</span></a>') ?>
            </div>
            <div class="innovation-banner-img-wrap" style="width:100%; max-width:100%; overflow:hidden; border-radius:8px;">
              <?= render_image(get_content('home', 'banner_image', 'assets/images/coldseal.jpg'), 'ColdSeal Blister Packaging', 'innovation-banner-img', ['style' => 'width:100%; max-width:100%; height:auto; max-height:220px; object-fit:cover; display:block; border-radius:8px; border:2px solid rgba(255,255,255,0.3);']) ?>
            </div>
          </div>
        </div>
      </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
