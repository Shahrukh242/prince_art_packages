<?php
$pageSlug = 'case-studies';
require __DIR__ . '/includes/header.php';
?>
<!-- ================================================================ -->
<!-- SECTION 01 — PAGE HERO                                           -->
<!-- ================================================================ -->
<section class="page-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden; text-align: center;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2; max-width: 860px; margin: 0 auto;">
    <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
      <i class="ri-line-chart-line"></i> <?= h(get_content('case-studies', 'hero_eyebrow', 'PROVEN MANUFACTURING RESULTS')) ?>
    </span>
    <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
      <?= h(get_content('case-studies', 'hero_title', 'Proven Manufacturing Results & Audit Performance Metrics')) ?>
    </h1>
    <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin: 0 auto; max-width: 720px;">
      <?= h(get_content('case-studies', 'hero_intro', 'Discover how our precision packaging engineering delivers 99.98% defect-free output, zero cartoning line jams, and 100% regulatory audit compliance.')) ?>
    </p>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 02 — CASE STUDIES GRID (H2 & SUPPORTING CONTENT)         -->
<!-- ================================================================ -->
<section class="section" style="padding: 4.5rem 0;">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-checkbox-circle-line"></i> <?= h(get_content('case-studies', 'sec_subtitle', 'AUDIT PERFORMANCE & CLIENT METRICS')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('case-studies', 'sec_h2_title', 'Real-World Engineering Solutions & Zero-Defect Delivery')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('case-studies', 'sec_h2_intro', 'Modern pharmaceutical packaging requires strict adherence to technical tolerances and rigorous cGMP line clearance. Explore how Prince Art Packages collaborates with leading pharmaceutical manufacturers to solve high-speed cartoning line jams, eliminate leaflet mix-ups with automated sensor vision systems, and pass comprehensive multinational quality audits.')) ?>
      </p>
    </div>

    <div class="grid-2">
      <div class="card card-body">
        <span style="color:var(--teal-brand); font-size:0.8rem; font-weight:700;"><?= h(get_content('case-studies', 'cs1_tag', 'CASE STUDY #201')) ?></span>
        <h3><?= h(get_content('case-studies', 'cs1_title', '99.98% Defect-Free Delivery for Multinational Packaging Audit')) ?></h3>
        <p><strong>Challenge:</strong> <?= h(get_content('case-studies', 'cs1_challenge', 'High-speed automated line jamming caused by carton creasing variation.')) ?></p>
        <p><strong>Solution:</strong> <?= h(get_content('case-studies', 'cs1_solution', 'Re-engineered grain direction and score depth on 300 gsm FBB board.')) ?></p>
        <p><strong>Result:</strong> <?= h(get_content('case-studies', 'cs1_result', 'Zero carton line jams across 15M units delivered.')) ?></p>
      </div>

      <div class="card card-body">
        <span style="color:var(--teal-brand); font-size:0.8rem; font-weight:700;"><?= h(get_content('case-studies', 'cs2_tag', 'CASE STUDY #202')) ?></span>
        <h3><?= h(get_content('case-studies', 'cs2_title', 'Zero Mix-Up Outsert Leaflet Scanning System')) ?></h3>
        <p><strong>Challenge:</strong> <?= h(get_content('case-studies', 'cs2_challenge', 'Mandate requiring 100% barcode verification on miniature outsert leaflets.')) ?></p>
        <p><strong>Solution:</strong> <?= h(get_content('case-studies', 'cs2_solution', 'Integrated Pharma Code sensor scanning on miniature folding equipment.')) ?></p>
        <p><strong>Result:</strong> <?= h(get_content('case-studies', 'cs2_result', 'Passed 6 consecutive regulatory audits with zero mix-up findings.')) ?></p>
      </div>
    </div>

    <div class="innovation-banner" style="margin-top: 3rem; text-align: center;">
      <h2 class="innovation-banner-heading">Deliver Zero-Defect Packaging for Your Next Audit</h2>
      <p class="innovation-banner-desc">Request physical sample packs, technical score cards, and audit certificates for your QA team.</p>
      <?= render_cta_buttons('case-studies', 'case_studies_bottom', '<a href="contact.php" class="btn btn-gold innovation-banner-btn"><i class="ri-shield-check-line"></i> Request an Audit &amp; Verification Samples</a>') ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
