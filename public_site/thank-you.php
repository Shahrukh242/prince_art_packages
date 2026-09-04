<?php
$pageSlug = 'thank-you';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$pageSlug = 'thank-you';
require_once __DIR__ . '/../includes/functions.php';

// Retrieve recent lead data from session if available
$leadData = $_SESSION['recent_lead'] ?? null;

$contactName = $leadData['name'] ?? '';
$companyName = $leadData['company'] ?? '';
$productName = $leadData['product'] ?? '';
$leadEmail   = $leadData['email'] ?? '';
$leadId      = $leadData['id'] ?? (rand(1000, 9999));

// SEO Meta
$metaTitle = "Thank You | Prince Art Packages";
$metaDesc  = "Thank you for contacting Prince Art Packages. Our pharmaceutical packaging engineering team has received your quotation request.";

require __DIR__ . '/includes/header.php';
?>

<!-- ================================================================ -->
<!-- THANK YOU CONFIRMATION HERO                                       -->
<!-- ================================================================ -->
<section class="thank-you-hero" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; text-align: center; position: relative; overflow: hidden;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  
  <div class="container" style="position: relative; z-index: 2; max-width: 800px; margin: 0 auto;">
    
    <!-- Success Icon -->
    <div style="width: 84px; height: 84px; border-radius: 50%; background: rgba(0, 168, 150, 0.2); border: 2px solid var(--teal-brand); color: var(--teal-brand); display: inline-flex; align-items: center; justify-content: center; font-size: 2.75rem; margin-bottom: 1.5rem; box-shadow: 0 0 30px rgba(0, 168, 150, 0.3);">
      <i class="ri-checkbox-circle-fill"></i>
    </div>

    <span style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--teal-brand); background: rgba(0,168,150,0.15); padding: 0.35rem 0.9rem; border-radius: 30px; margin-bottom: 1rem;">
      <i class="ri-check-line"></i> INQUIRY CONFIRMED
    </span>

    <h1 style="font-size: 2.6rem; font-weight: 800; color: #ffffff; line-height: 1.25; margin-bottom: 1rem;">
      Thank You! Your Request Has Been Received
    </h1>

    <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin: 0 auto 1.75rem auto; max-width: 680px;">
      <?php if (!empty($contactName)): ?>
        Dear <strong><?= h($contactName) ?></strong><?= !empty($companyName) ? ' (' . h($companyName) . ')' : '' ?>, 
      <?php endif; ?>
      our technical sales and packaging engineering team has received your specifications and is reviewing them. We typically deliver formal technical quotations within 24 business hours.
    </p>

    <!-- Quick Action CTA -->
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
      <a href="products.php" class="btn btn-gold btn-md">
        <i class="ri-box-3-line"></i> Explore Product Range &rarr;
      </a>
      <a href="index.php" class="btn btn-outline-navy btn-md" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">
        <i class="ri-home-line"></i> Return to Homepage
      </a>
    </div>

  </div>
</section>

<!-- ================================================================ -->
<!-- SUBMISSION SUMMARY & NEXT STEPS                                   -->
<!-- ================================================================ -->
<section class="section" style="background: var(--bg-alt); padding: 4rem 0;">
  <div class="container" style="max-width: 960px;">
    
    <!-- Submission Reference Box -->
    <div class="card" style="padding: 2rem; background: var(--bg-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); margin-bottom: 2.5rem;">
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); margin-bottom: 1.5rem;">
        <div>
          <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block;">RFQ Reference Number</span>
          <strong style="font-size: 1.3rem; color: var(--navy-dark); font-family: monospace;">#PAP-RFQ-<?= sprintf('%05d', $leadId) ?></strong>
        </div>
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0, 168, 150, 0.1); color: var(--teal-brand); padding: 0.4rem 0.85rem; border-radius: 20px; font-size: 0.82rem; font-weight: 700;">
          <i class="ri-time-line"></i> Turnaround: 24 Business Hours
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <div>
          <span style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Packaging Category</span>
          <strong style="font-size: 0.98rem; color: var(--navy-dark);"><?= !empty($productName) ? h($productName) : 'General Secondary Packaging' ?></strong>
        </div>
        <div>
          <span style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Contact Email</span>
          <strong style="font-size: 0.98rem; color: var(--navy-dark);"><?= !empty($leadEmail) ? h($leadEmail) : 'Recorded with Sales Desk' ?></strong>
        </div>
        <div>
          <span style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Operating Hours</span>
          <strong style="font-size: 0.98rem; color: var(--navy-dark);">Mon &ndash; Sat: 9:00 AM &ndash; 5:00 PM</strong>
        </div>
      </div>
    </div>

    <!-- What Happens Next Section -->
    <div style="text-align: center; margin-bottom: 2rem;">
      <h2 style="font-size: 1.85rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;">What Happens Next?</h2>
      <p style="color: var(--text-body); font-size: 0.98rem;">Here is how our packaging engineering desk processes your inquiry:</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
      
      <!-- Step 1 -->
      <div class="card" style="padding: 1.5rem; background: var(--bg-white); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
        <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--navy-dark); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; margin-bottom: 1rem;">1</div>
        <h3 style="font-size: 1.1rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.5rem;">Specification Review</h3>
        <p style="font-size: 0.86rem; color: var(--text-body); line-height: 1.55; margin: 0;">Our packaging engineers review your board grammage, dielines, printing finishes, and compliance parameters.</p>
      </div>

      <!-- Step 2 -->
      <div class="card" style="padding: 1.5rem; background: var(--bg-white); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
        <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--teal-brand); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; margin-bottom: 1rem;">2</div>
        <h3 style="font-size: 1.1rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.5rem;">Technical Proposal</h3>
        <p style="font-size: 0.86rem; color: var(--text-body); line-height: 1.55; margin: 0;">We formulate a commercial quotation, unit pricing tiers, tooling estimates, and manufacturing lead time schedule.</p>
      </div>

      <!-- Step 3 -->
      <div class="card" style="padding: 1.5rem; background: var(--bg-white); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
        <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--gold-accent); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; margin-bottom: 1rem;">3</div>
        <h3 style="font-size: 1.1rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.5rem;">Samples &amp; Audit</h3>
        <p style="font-size: 0.86rem; color: var(--text-body); line-height: 1.55; margin: 0;">Upon quotation review, we provide physical pilot samples and coordinate plant audit visits at our Korangi Creek units.</p>
      </div>

    </div>

    <!-- Urgent Contact Callout Banner -->
    <div style="background: linear-gradient(135deg, rgba(11,37,69,0.04) 0%, rgba(0,168,150,0.08) 100%); border: 1px solid rgba(0,168,150,0.25); border-radius: var(--radius-md); padding: 1.75rem 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
      <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--teal-brand); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
          <i class="ri-customer-service-2-line"></i>
        </div>
        <div>
          <h4 style="color: var(--navy-dark); font-size: 1.05rem; margin-bottom: 0.25rem;">Urgent Quotation or Audit Request?</h4>
          <p style="color: var(--text-body); font-size: 0.88rem; margin: 0;">Speak directly with our technical sales management desk.</p>
        </div>
      </div>

      <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <a href="tel:+922138893400" class="btn btn-navy btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
          <i class="ri-phone-line"></i> +92 21-38893400-3
        </a>
        <a href="mailto:info@princeartpackages.com" class="btn btn-outline-teal btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
          <i class="ri-mail-line"></i> info@princeartpackages.com
        </a>
      </div>
    </div>

  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

