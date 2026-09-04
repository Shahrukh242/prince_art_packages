<?php
$pageSlug = 'industries';
require __DIR__ . '/includes/header.php';
?>
<!-- ================================================================ -->
<!-- SECTION 01 — PAGE HERO                                           -->
<!-- ================================================================ -->
<section class="page-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden; text-align: center;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2; max-width: 860px; margin: 0 auto;">
    <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
      <i class="ri-focus-3-line"></i> <?= h(get_content('industries', 'hero_eyebrow', 'TARGET PHARMACEUTICAL SECTORS')) ?>
    </span>
    <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
      <?= h(get_content('industries', 'hero_title', 'Specialized Packaging Solutions for Regulated Healthcare Industries')) ?>
    </h1>
    <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin: 0 auto; max-width: 720px;">
      <?= h(get_content('industries', 'hero_intro', 'Tailored secondary packaging engineered specifically for commercial pharmaceuticals, consumer healthcare & OTC products, and sterile biologics & medical devices.')) ?>
    </p>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 02 — TARGET SECTORS (H2 & SUPPORTING CONTENT)            -->
<!-- ================================================================ -->
<section class="section" style="padding: 4.5rem 0;">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-medicine-bottle-line"></i> <?= h(get_content('industries', 'sec_subtitle', 'TARGET SECTORS & FORMULATION TYPES')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('industries', 'sec_h2_title', 'Purpose-Built Packaging for Diverse Healthcare Demands')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('industries', 'sec_h2_intro', 'Every healthcare sector possesses distinct regulatory standards, shelf-life conditions, and machine packing requirements. Prince Art Packages provides purpose-engineered paperboard packaging, patient information leaflets, and security labels adapted to the specific operational constraints of pharmaceutical tablet/capsule blister packing, liquid syrup bottling, ampoule/vial cartoning, and cold-chain biologics storage.')) ?>
      </p>
    </div>

    <div class="grid-3">
      <div class="card card-body">
        <i class="ri-capsule-fill text-teal" style="font-size:2.5rem;"></i>
        <h3><?= h(get_content('industries', 'ind1_title', 'Commercial Pharmaceuticals')) ?></h3>
        <p><?= h(get_content('industries', 'ind1_desc', 'Prescription solid orals, liquids, parenterals, and topical formulations requiring strict cGMP compliance.')) ?></p>
      </div>
      <div class="card card-body">
        <i class="ri-heart-pulse-fill text-teal" style="font-size:2.5rem;"></i>
        <h3><?= h(get_content('industries', 'ind2_title', 'Healthcare & OTC Products')) ?></h3>
        <p><?= h(get_content('industries', 'ind2_desc', 'Consumer healthcare products, vitamin supplements, and over-the-counter medicine boxes.')) ?></p>
      </div>
      <div class="card card-body">
        <i class="ri-syringe-fill text-teal" style="font-size:2.5rem;"></i>
        <h3><?= h(get_content('industries', 'ind3_title', 'Biologics & Medical Devices')) ?></h3>
        <p><?= h(get_content('industries', 'ind3_desc', 'Cold-chain insulated partitions, sterile syringe packaging cards, and high-density labels.')) ?></p>
      </div>
    </div>

    <div class="innovation-banner" style="margin-top: 2.5rem; text-align: center;">
      <h2 class="innovation-banner-heading"><?= h(get_content('industries', 'banner_title', "Don't See Your Industry Listed?")) ?></h2>
      <p class="innovation-banner-desc"><?= h(get_content('industries', 'banner_desc', 'We work with a wide range of regulated manufacturers — talk to us about your specific packaging needs.')) ?></p>
      <?= render_cta_buttons('industries', 'industries_banner', '<a href="contact.php" class="btn btn-gold innovation-banner-btn"><i class="ri-file-list-3-line"></i> Request a Formal Quote</a>') ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
