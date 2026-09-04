<?php
$pageSlug = 'about';
require __DIR__ . '/includes/header.php';
?>

<!-- SECTION 01 — PAGE HEADER / HERO -->
<section class="about-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2;">
    <div class="section-header" style="max-width: 820px; margin: 0 auto; text-align: center;">
      <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
        <i class="ri-shield-check-line"></i> <?= h(get_content('about', 'page_eyebrow', 'Corporate History & Facilities')) ?>
      </span>
      <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
        <?= h(get_content('about', 'page_title', 'Prince Art Packages (Private) Limited')) ?>
      </h1>
      <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.85); margin: 0 auto; max-width: 720px;">
        <?= h(get_content('about', 'page_intro', 'Formerly Prince Art Press, our company has evolved into a premier ISO 9001:2015 and FSC certified manufacturer of pharmaceutical secondary packaging in Pakistan.')) ?>
      </p>
    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 02 — COMPANY INTRODUCTION                                -->
<!-- ================================================================ -->
<section class="section company-intro-section" id="who-we-are">
  <div class="container">
    <!-- Section Heading & Company Overview Narrative -->
    <div class="section-header" style="text-align: center; max-width: 900px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-building-4-line"></i> <?= h(get_content('about', 'intro_subtitle', 'Company Introduction')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.2; margin-bottom: 1.25rem;">
        <?= h(get_content('about', 'intro_title', 'Who We Are')) ?>
      </h2>
      <p class="company-overview-lead" style="margin-bottom: 1.15rem;">
        <?= get_content('about', 'intro_overview', 'Prince Art Packages is a pharmaceutical secondary packaging manufacturer focused on delivering reliable, precision-engineered <a href="products.php" class="text-link">pharmaceutical packaging solutions</a> for pharmaceutical and healthcare companies.') ?>
      </p>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); max-width: 820px; margin: 0 auto;">
        <?= h(get_content('about', 'intro_narrative', 'Operating two cGMP-aligned manufacturing plants in Korangi Creek Industrial Park, Karachi, we combine more than 40 years of industrial heritage with state-of-the-art multi-color offset printing, precision converting, and in-house laboratory quality controls to ensure zero-defect secondary packaging for regulated domestic and export healthcare markets.')) ?>
      </p>
    </div>

    <!-- Key Content Pillars (Explaining All 8 Requirements) -->
    <div class="intro-pillars-grid">

      <!-- 01: Established & Experience -->
      <div class="intro-pillar-card">
        <div class="intro-pillar-header">
          <div class="intro-pillar-icon" style="background: rgba(18,48,92,0.08); color: var(--navy-primary);">
            <i class="ri-history-line"></i>
          </div>
          <span class="intro-pillar-tag" style="color: var(--teal-primary); background: var(--teal-bg);">ESTABLISHED 40+ YRS</span>
        </div>
        <h3><?= h(get_content('about', 'pillar_1_title', '40+ Years of Industry Experience')) ?></h3>
        <p><?= h(get_content('about', 'pillar_1_desc', 'Originally founded over 40 years ago as Prince Art Press, our company has grown through four continuous decades of technical innovation into Prince Art Packages (Private) Limited—delivering unmatched consistency and industry trust.')) ?></p>
      </div>

      <!-- 02: Core Business & Pharma Specialization -->
      <div class="intro-pillar-card">
        <div class="intro-pillar-header">
          <div class="intro-pillar-icon" style="background: rgba(0,168,150,0.12); color: var(--teal-primary);">
            <i class="ri-capsule-fill"></i>
          </div>
          <span class="intro-pillar-tag" style="color: var(--navy-primary); background: rgba(18,48,92,0.08);">CORE SPECIALIZATION</span>
        </div>
        <h3><?= h(get_content('about', 'pillar_2_title', 'Pharmaceutical Packaging Specialization')) ?></h3>
        <p><?= h(get_content('about', 'pillar_2_desc', 'Our primary business is 100% focused on pharmaceutical secondary packaging. Production lines are purpose-built to eliminate cross-contamination, prevent mix-ups, and run seamlessly on high-speed automated cartoning lines.')) ?></p>
      </div>

      <!-- 03: Dual Manufacturing Locations -->
      <div class="intro-pillar-card">
        <div class="intro-pillar-header">
          <div class="intro-pillar-icon" style="background: rgba(212,175,55,0.15); color: var(--gold-dark);">
            <i class="ri-building-2-line"></i>
          </div>
          <span class="intro-pillar-tag" style="color: var(--gold-dark); background: rgba(212,175,55,0.12);">KARACHI, PAKISTAN</span>
        </div>
        <h3><?= h(get_content('about', 'pillar_3_title', 'Dual Manufacturing Locations')) ?></h3>
        <p><?= h(get_content('about', 'pillar_3_desc', 'Operating two modern industrial facilities in Korangi Creek Industrial Park, Karachi (Unit 1: Sector 38 and Unit 2: Plot 239 Main Korangi Creek Road), providing built-in production redundancy and risk-mitigated supply chains.')) ?></p>
      </div>

      <!-- 04: Main Products Scope -->
      <div class="intro-pillar-card">
        <div class="intro-pillar-header">
          <div class="intro-pillar-icon" style="background: rgba(18,48,92,0.08); color: var(--navy-primary);">
            <i class="ri-box-3-line"></i>
          </div>
          <span class="intro-pillar-tag" style="color: var(--teal-primary); background: var(--teal-bg);">MAIN PRODUCTS</span>
        </div>
        <h3><?= h(get_content('about', 'pillar_4_title', 'Comprehensive Product Portfolio')) ?></h3>
        <p><?= h(get_content('about', 'pillar_4_desc', 'Manufacturing printed cartons (reverse tuck, crash-lock), 27–60gsm prescribing inserts/outserts, printed self-adhesive roll labels, ColdSeal blister wallets, tamper-evident seals, and 3D-Engravix™ security packaging.')) ?></p>
      </div>

      <!-- 05: Markets Served -->
      <div class="intro-pillar-card">
        <div class="intro-pillar-header">
          <div class="intro-pillar-icon" style="background: rgba(0,168,150,0.12); color: var(--teal-primary);">
            <i class="ri-global-line"></i>
          </div>
          <span class="intro-pillar-tag" style="color: var(--teal-primary); background: var(--teal-bg);">MARKETS SERVED</span>
        </div>
        <h3><?= h(get_content('about', 'pillar_5_title', 'Regulated Healthcare Markets')) ?></h3>
        <p><?= h(get_content('about', 'pillar_5_desc', 'Proudly supplying commercial pharmaceutical manufacturers (solid orals, parenterals, syrups), OTC and consumer healthcare brands, hospitals, medical device companies, and regulated international export markets.')) ?></p>
      </div>

      <!-- 06: Commitment to Quality -->
      <div class="intro-pillar-card">
        <div class="intro-pillar-header">
          <div class="intro-pillar-icon" style="background: rgba(212,175,55,0.15); color: var(--gold-dark);">
            <i class="ri-shield-check-line"></i>
          </div>
          <span class="intro-pillar-tag" style="color: var(--gold-dark); background: rgba(212,175,55,0.12);">AUDIT-READY QA</span>
        </div>
        <h3><?= h(get_content('about', 'pillar_6_title', 'Uncompromising Quality Commitment')) ?></h3>
        <p><?= h(get_content('about', 'pillar_6_desc', 'ISO 9001:2015 certified (Cert No. KQ.2025.5393 - ASCERT) and FSC Chain of Custody certified (FSC-C222205). Governed by cGMP line clearance, batch traceability, and full in-house testing laboratory verification.')) ?></p>
      </div>

    </div>

    <!-- CTA Box -->
    <div class="intro-cta-box">
      <div style="max-width: 620px;">
        <h3 style="font-size: 1.35rem; color: var(--navy-dark); margin-bottom: 0.35rem; font-weight: 700;">
          Ready to Partner with Prince Art Packages?
        </h3>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">
          Explore our complete range of pharmaceutical secondary packaging solutions or connect directly with our packaging engineers.
        </p>
      </div>
      <div style="display: flex; gap: 0.85rem; flex-wrap: wrap; align-items: center;">
        <?= render_cta_buttons('about', 'intro_cta', '<a href="products.php" class="btn btn-gold btn-lg"><i class="ri-arrow-right-line"></i> Explore Packaging Solutions &rarr;</a><a href="contact.php" class="btn btn-outline-navy btn-lg"><i class="ri-file-list-3-line"></i> Request a Quote</a>') ?>
      </div>
    </div>
  </div>
</section>


<!-- ================================================================ -->
<!-- SECTION 04 — COMPANY JOURNEY / EXPERIENCE                        -->
<!-- ================================================================ -->
<section class="section journey-section" id="company-journey">
  <div class="container">
    
    <!-- Section Heading & Narrative -->
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-route-line"></i> <?= h(get_content('about', 'journey_subtitle', 'Company Journey & Experience')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.2; margin-bottom: 1.25rem;">
        <?= h(get_content('about', 'journey_title', 'Built on Decades of Packaging Experience')) ?>
      </h2>
      <p style="font-size: 1.08rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= get_content('about', 'journey_paragraph', 'With more than three decades of specialized manufacturing heritage, Prince Art Packages has developed deep engineering mastery across printed cartons, prescribing leaflets, roll labels, and anti-counterfeit packaging. Our journey from foundational offset printing to advanced dual-plant <a href="capabilities.php" class="text-link">pharmaceutical packaging manufacturing capabilities</a> reflects an enduring commitment to regulatory precision, continuous technical reinvestment, and zero-defect packaging integrity for healthcare leaders across the region.') ?>
      </p>
    </div>

    <!-- Trust Metric Hero Card (Featuring 30+ Years of Experience) -->
    <div class="journey-hero-metric">
      <div class="big-metric-display">
        <span class="big-metric-val"><?= h(get_content('about', 'trust_metric_val', '30+')) ?></span>
        <span class="big-metric-label"><?= h(get_content('about', 'trust_metric_lbl', 'Years of Experience')) ?></span>
      </div>
      <div class="journey-metric-subgrid">
        <div class="journey-mini-metric">
          <strong><?= h(get_content('about', 'journey_stat_1_val', '500M+')) ?></strong>
          <span><?= h(get_content('about', 'journey_stat_1_lbl', 'Secondary Packaging Units Produced Annually')) ?></span>
        </div>
        <div class="journey-mini-metric">
          <strong><?= h(get_content('about', 'journey_stat_2_val', '100%')) ?></strong>
          <span><?= h(get_content('about', 'journey_stat_2_lbl', 'Audit-Ready Line Clearance & Traceability')) ?></span>
        </div>
        <div class="journey-mini-metric">
          <strong><?= h(get_content('about', 'journey_stat_3_val', '2 Plants')) ?></strong>
          <span><?= h(get_content('about', 'journey_stat_3_lbl', 'Dual Production Facilities in Korangi Creek')) ?></span>
        </div>
      </div>
    </div>

    <!-- Journey Timeline / Eras Grid -->
    <div class="journey-timeline-grid">
      <!-- Era 1 -->
      <div class="journey-timeline-card">
        <span class="timeline-era-badge"><?= h(get_content('about', 'era_1_tag', 'ORIGINS')) ?></span>
        <h4><?= h(get_content('about', 'era_1_title', 'Foundations as Prince Art Press')) ?></h4>
        <p><?= h(get_content('about', 'era_1_desc', 'Established over 30 years ago, focusing on industrial printing excellence, paperboard conversion, and uncompromising ink formulation standards.')) ?></p>
      </div>

      <!-- Era 2 -->
      <div class="journey-timeline-card">
        <span class="timeline-era-badge" style="color: var(--teal-primary); background: var(--teal-bg);"><?= h(get_content('about', 'era_2_tag', 'PHARMA PIVOT')) ?></span>
        <h4><?= h(get_content('about', 'era_2_title', 'Pharmaceutical Specialization')) ?></h4>
        <p><?= h(get_content('about', 'era_2_desc', 'Pivoted operations 100% towards regulated healthcare packaging, implementing cGMP segregation, line clearance protocols, and micro-folding technologies.')) ?></p>
      </div>

      <!-- Era 3 -->
      <div class="journey-timeline-card">
        <span class="timeline-era-badge" style="color: var(--gold-dark); background: rgba(212,175,55,0.12);"><?= h(get_content('about', 'era_3_tag', 'EXPANSION')) ?></span>
        <h4><?= h(get_content('about', 'era_3_title', 'Dual Plant Infrastructure')) ?></h4>
        <p><?= h(get_content('about', 'era_3_desc', 'Expanded into two specialized production units in Korangi Creek Industrial Park, Karachi, scaling multi-color offset lines and automated gluing machinery.')) ?></p>
      </div>

      <!-- Era 4 -->
      <div class="journey-timeline-card">
        <span class="timeline-era-badge" style="color: var(--teal-primary); background: var(--teal-bg);"><?= h(get_content('about', 'era_4_tag', 'INNOVATION & AUDIT')) ?></span>
        <h4><?= h(get_content('about', 'era_4_title', 'Global Accreditations & Security')) ?></h4>
        <p><?= h(get_content('about', 'era_4_desc', 'Secured ISO 9001:2015 and FSC certifications; engineered proprietary ColdSeal blister wallets and 3D-Engravix™ optical anti-counterfeit cartons.')) ?></p>
      </div>
    </div>

    <!-- CTA Anchor -->
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; align-items: center; margin-top: 2rem;">
      <?= render_cta_buttons('about', 'journey_cta', '<a href="capabilities.php" class="btn btn-navy btn-lg"><i class="ri-settings-4-line"></i> View Manufacturing Capabilities</a><a href="contact.php" class="btn btn-gold btn-lg"><i class="ri-phone-line"></i> Speak with Our Specialists</a>') ?>
    </div>

  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 07 — QUALITY & COMPLIANCE                                -->
<!-- ================================================================ -->
<section class="section quality-compliance-section" id="quality-compliance">
  <div class="container">
    
    <!-- Section Header -->
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-shield-star-line"></i> <?= h(get_content('about', 'qc_sec_subtitle', 'Quality & Compliance')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.2; margin-bottom: 1.25rem;">
        <?= h(get_content('about', 'qc_sec_title', 'Quality and Compliance at the Core of Our Operations')) ?>
      </h2>
      <p style="font-size: 1.08rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= get_content('about', 'qc_sec_intro', 'Pharmaceutical packaging demands zero error tolerance. We place <a href="quality.php" class="text-link">quality and compliance</a> at the core of our operations, providing procurement leaders, QA managers, and regulatory auditors with absolute confidence through internationally verified certifications, strict cGMP line clearance, and in-house laboratory testing.') ?>
      </p>
    </div>

    <!-- Verified Compliance Standards Cards Grid (5 Key Areas) -->
    <div class="qc-standards-grid">
      
      <!-- 01 — cGMP Compliance -->
      <div class="qc-standard-card">
        <div class="qc-card-top">
          <div class="qc-icon-box" style="background: rgba(0,168,150,0.1); color: var(--teal-primary);">
            <i class="ri-shield-check-fill"></i>
          </div>
          <span class="qc-badge">VERIFIED STANDARD</span>
        </div>
        <h3><?= h(get_content('about', 'qc_cgmp_title', 'cGMP Compliance')) ?></h3>
        <p class="qc-scope"><?= h(get_content('about', 'qc_cgmp_desc', 'Strict line clearance protocols between production batches, air-controlled production bays, segregated raw material staging, and complete operator hygiene controls to eliminate cross-contamination and packaging mix-ups.')) ?></p>
        <div class="qc-meta-list">
          <div><i class="ri-check-line text-teal"></i> Mandatory Line Clearance Checklist</div>
          <div><i class="ri-check-line text-teal"></i> Complete Batch Record Retention & Traceability</div>
        </div>
      </div>

      <!-- 02 — ISO 9001:2015 -->
      <div class="qc-standard-card">
        <div class="qc-card-top">
          <div class="qc-icon-box" style="background: rgba(18,48,92,0.08); color: var(--navy-primary);">
            <i class="ri-award-fill"></i>
          </div>
          <span class="qc-badge">CERTIFIED QMS</span>
        </div>
        <h3><?= h(get_content('about', 'qc_iso_title', 'ISO 9001:2015 Certification')) ?></h3>
        <p class="qc-scope"><?= h(get_content('about', 'qc_iso_desc', 'Certified Quality Management System governing our entire manufacturing process from virgin paperboard procurement to precision offset printing, die-cutting, inspection, and customer delivery.')) ?></p>
        <div class="qc-meta-list">
          <div><strong>Cert No:</strong> <code><?= h(get_content('about', 'qc_iso_no', 'KQ.2025.5393')) ?></code></div>
          <div><strong>Body:</strong> <?= h(get_content('about', 'qc_iso_body', 'ASCERT Certification (MSCB-223)')) ?></div>
        </div>
      </div>

      <!-- 03 — FSC Chain of Custody -->
      <div class="qc-standard-card">
        <div class="qc-card-top">
          <div class="qc-icon-box" style="background: rgba(212,175,55,0.12); color: var(--gold-dark);">
            <i class="ri-leaf-fill"></i>
          </div>
          <span class="qc-badge" style="color: var(--gold-dark); background: rgba(212,175,55,0.12);">CHAIN OF CUSTODY</span>
        </div>
        <h3><?= h(get_content('about', 'qc_fsc_title', 'FSC Chain of Custody')) ?></h3>
        <p class="qc-scope"><?= h(get_content('about', 'qc_fsc_desc', 'FSC certified responsible raw material sourcing guaranteeing sustainable, traceable forest paperboard for regulated multinational pharmaceutical secondary packaging.')) ?></p>
        <div class="qc-meta-list">
          <div><strong>Cert No:</strong> <code><?= h(get_content('about', 'qc_fsc_no', 'RR-COC-003348')) ?></code></div>
          <div><strong>License:</strong> <code><?= h(get_content('about', 'qc_fsc_lic', 'FSC-C222205 (Std: 40-004)')) ?></code></div>
        </div>
      </div>

      <!-- 04 — WHO-GMP Alignment -->
      <div class="qc-standard-card">
        <div class="qc-card-top">
          <div class="qc-icon-box" style="background: rgba(0,168,150,0.1); color: var(--teal-primary);">
            <i class="ri-hospital-line"></i>
          </div>
          <span class="qc-badge">MANUFACTURING AUDIT</span>
        </div>
        <h3><?= h(get_content('about', 'qc_who_title', 'WHO-GMP Alignment')) ?></h3>
        <p class="qc-scope"><?= h(get_content('about', 'qc_who_desc', 'Manufacturing practices strictly aligned with World Health Organization guidelines for pharmaceutical secondary packaging suppliers, ensuring audit compliance for export formulations.')) ?></p>
        <div class="qc-meta-list">
          <div><i class="ri-check-line text-teal"></i> Validated Standard Operating Procedures (SOPs)</div>
          <div><i class="ri-check-line text-teal"></i> Regular Internal & Third-Party QA Audits</div>
        </div>
      </div>

      <!-- 05 — Quality Control Systems (In-House Laboratory) -->
      <div class="qc-standard-card" style="grid-column: 1 / -1; background: linear-gradient(135deg, var(--bg-alt) 0%, var(--bg-white) 100%);">
        <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
          <div class="qc-icon-box" style="width: 56px; height: 56px; font-size: 1.75rem; background: rgba(18,48,92,0.1); color: var(--navy-dark); flex-shrink: 0;">
            <i class="ri-flask-fill"></i>
          </div>
          <div style="flex: 1; min-width: 280px;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.35rem;">
              <h3 style="margin: 0; font-size: 1.35rem;"><?= h(get_content('about', 'qc_lab_title', 'Quality Control Systems & In-House Testing Laboratory (OurLAB)')) ?></h3>
              <span class="qc-badge" style="background: var(--teal-bg); color: var(--teal-primary);">LABORATORY VERIFIED</span>
            </div>
            <p class="qc-scope" style="margin-bottom: 1rem;"><?= h(get_content('about', 'qc_lab_desc', 'Our in-house QC laboratory tests every material batch prior to printing and post-converting using calibrated optical and mechanical instruments.')) ?></p>
            <div style="display: flex; gap: 1rem 2rem; flex-wrap: wrap; font-size: 0.88rem; color: var(--navy-dark); font-weight: 600;">
              <span><i class="ri-checkbox-circle-fill text-teal"></i> Spectrophotometer Delta-E Color Accuracy</span>
              <span><i class="ri-checkbox-circle-fill text-teal"></i> Pantone Formulation Verification</span>
              <span><i class="ri-checkbox-circle-fill text-teal"></i> Sutherland Ink-Rub Abrasion Resistance</span>
              <span><i class="ri-checkbox-circle-fill text-teal"></i> GSM Grammage & Caliper Thickness</span>
              <span><i class="ri-checkbox-circle-fill text-teal"></i> 2D DataMatrix & Barcode Readability</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Assurance Callout & Action Bar -->
    <div class="qc-cta-banner">
      <div style="max-width: 650px;">
        <span style="font-size: 0.78rem; font-weight: 700; color: var(--gold-accent); text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 0.35rem;">
          <i class="ri-file-shield-line"></i> AUDIT & COMPLIANCE READY
        </span>
        <h3 style="font-size: 1.45rem; color: #ffffff; font-weight: 700; margin-bottom: 0.5rem;">
          Require Quality Documentation or a Physical Plant Audit?
        </h3>
        <p style="color: rgba(255,255,255,0.8); font-size: 0.95rem; margin: 0; line-height: 1.6;">
          Our QA team provides full vendor audit questionnaires, certificate copies, technical data sheets (TDS), and Certificates of Analysis (COA) to support your regulatory submissions.
        </p>
      </div>
      <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
        <?= render_cta_buttons('about', 'quality_compliance_cta', '<a href="contact.php" class="btn btn-gold btn-lg"><i class="ri-calendar-check-line"></i> Schedule a Facility Audit</a><a href="quality.php" class="btn btn-outline-navy btn-lg" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.25); color: #ffffff;"><i class="ri-external-link-line"></i> View Quality Accreditations</a>') ?>
      </div>
    </div>

  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 08 — WHAT MAKES US DIFFERENT                             -->
<!-- ================================================================ -->
<section class="section why-choose-section" id="what-makes-us-different">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 860px; margin: 0 auto 1.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-flashlight-line"></i> <?= h(get_content('about', 'diff_sec_subtitle', 'What Makes Us Different')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.2; margin-bottom: 1.25rem;">
        <?= h(get_content('about', 'diff_sec_title', 'Why Choose Prince Art Packages')) ?>
      </h2>
      <p style="font-size: 1.08rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('about', 'diff_sec_intro', 'Delivering audit-ready secondary packaging engineered to prevent line stoppages, artwork mix-ups, and compliance vulnerabilities through focused manufacturing precision.')) ?>
      </p>
    </div>

    <div class="why-choose-grid">
      <!-- 01 — Pharmaceutical Focus -->
      <div class="usp-card">
        <div class="usp-card-header">
          <span class="usp-card-num">01</span>
          <div class="usp-card-icon"><i class="ri-capsule-line"></i></div>
        </div>
        <h3><?= h(get_content('about', 'usp_1_title', 'Pharmaceutical Focus')) ?></h3>
        <p><?= h(get_content('about', 'usp_1_text', 'Specialized knowledge of pharmaceutical secondary packaging requirements.')) ?></p>
      </div>

      <!-- 02 — Manufacturing Expertise -->
      <div class="usp-card">
        <div class="usp-card-header">
          <span class="usp-card-num">02</span>
          <div class="usp-card-icon"><i class="ri-building-4-line"></i></div>
        </div>
        <h3><?= h(get_content('about', 'usp_2_title', 'Manufacturing Expertise')) ?></h3>
        <p><?= h(get_content('about', 'usp_2_text', 'Integrated production capabilities for consistent output.')) ?></p>
      </div>

      <!-- 03 — Quality Focus -->
      <div class="usp-card">
        <div class="usp-card-header">
          <span class="usp-card-num">03</span>
          <div class="usp-card-icon"><i class="ri-equalizer-line"></i></div>
        </div>
        <h3><?= h(get_content('about', 'usp_3_title', 'Quality Focus')) ?></h3>
        <p><?= h(get_content('about', 'usp_3_text', 'Structured quality controls throughout production.')) ?></p>
      </div>

      <!-- 04 — Compliance -->
      <div class="usp-card">
        <div class="usp-card-header">
          <span class="usp-card-num">04</span>
          <div class="usp-card-icon"><i class="ri-shield-check-line"></i></div>
        </div>
        <h3><?= h(get_content('about', 'usp_4_title', 'Compliance')) ?></h3>
        <p><?= h(get_content('about', 'usp_4_text', 'Manufacturing processes designed around applicable pharmaceutical requirements.')) ?></p>
      </div>

      <!-- 05 — Security & Innovation -->
      <div class="usp-card">
        <div class="usp-card-header">
          <span class="usp-card-num">05</span>
          <div class="usp-card-icon"><i class="ri-shield-keyhole-line"></i></div>
        </div>
        <h3><?= h(get_content('about', 'usp_5_title', 'Security & Innovation')) ?></h3>
        <p><?= get_content('about', 'usp_5_text', 'Advanced <a href="innovation.php" class="text-link">anti-counterfeit packaging solutions</a> for product and brand protection.') ?></p>
      </div>

      <!-- 06 — Customer-Focused Solutions -->
      <div class="usp-card">
        <div class="usp-card-header">
          <span class="usp-card-num">06</span>
          <div class="usp-card-icon"><i class="ri-user-heart-line"></i></div>
        </div>
        <h3><?= h(get_content('about', 'usp_6_title', 'Customer-Focused Solutions')) ?></h3>
        <p><?= h(get_content('about', 'usp_6_text', 'Packaging developed around individual product and production requirements.')) ?></p>
      </div>
    </div>

    <!-- CTA -->
    <div class="why-choose-cta-wrap">
      <?= render_cta_buttons('about', 'diff_cta', '<a href="contact.php" class="btn btn-gold btn-lg"><i class="ri-chat-smile-3-line"></i> Discuss Your Requirements &rarr;</a>') ?>
    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 12 — FAQ                                                 -->
<!-- ================================================================ -->
<section class="section faq-section" id="faq">
  <div class="container">
    <div class="section-header">
      <span class="section-subtitle"><i class="ri-questionnaire-line"></i> <?= h(get_content('about', 'faq_subtitle', 'Frequently Asked Questions')) ?></span>
      <h2><?= h(get_content('about', 'faq_title', 'Frequently Asked Questions')) ?></h2>
      <p><?= h(get_content('about', 'faq_intro', 'Explore clear answers to common buyer and compliance questions regarding our pharmaceutical secondary packaging manufacturing, cGMP facilities, certifications, and quotation process.')) ?></p>
    </div>

    <div class="faq-grid">
      <!-- Left Column (Questions 1 to 5) -->
      <div class="faq-column">
        
        <!-- Q1 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_1_q', 'What does Prince Art Packages manufacture?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_1_a', 'Prince Art Packages specializes exclusively in pharmaceutical secondary packaging. We manufacture precision-printed folding paperboard cartons, prescribing information leaflets (PIL), multi-folded outserts, self-adhesive roll labels, ColdSeal blister wallets, honeycomb partitions, and proprietary 3D-Engravix™ optical anti-counterfeit security packaging.')) ?></p>
          </div>
        </details>

        <!-- Q2 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_2_q', 'What is pharmaceutical secondary packaging?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_2_a', 'Pharmaceutical secondary packaging refers to the exterior packaging components—such as printed cartons, patient instruction leaflets, overwrap wallets, and container labels—that enclose, protect, and present the primary packaging (blisters, bottles, ampoules, vials). While primary packaging directly touches the medication, secondary packaging is vital for patient compliance, dosage tracking, light protection, tamper evidence, track-and-trace serialization, and meeting strict health authority labeling regulations.')) ?></p>
          </div>
        </details>

        <!-- Q3 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_3_q', 'How long has Prince Art Packages been in the packaging industry?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_3_a', 'Prince Art Packages has been serving the packaging industry for more than 40 years. Founded originally as Prince Art Press, our company has built four decades of continuous manufacturing craftsmanship, evolving into a modernized, cGMP-compliant enterprise trusted by leading multinational and local pharmaceutical companies.')) ?></p>
          </div>
        </details>

        <!-- Q4 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_4_q', 'Where is Prince Art Packages located?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_4_a', 'Our corporate headquarters and dual manufacturing facilities are strategically located in Karachi, Pakistan, within the prestigious Korangi Creek Industrial Park (KCIP). We operate two dedicated plants: Unit 1 at Sector 38 (KCIP) and Unit 2 on Main Korangi Creek Road, ensuring robust production redundancy and supply chain reliability.')) ?></p>
          </div>
        </details>

        <!-- Q5 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_5_q', 'What pharmaceutical packaging products do you manufacture?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_5_a', 'Our core product lines include: (1) Paperboard Printed Cartons engineered for high-speed automated cartoning machines; (2) Prescribing Information Inserts & Outserts printed on 27gsm to 60gsm pharma-grade paper; (3) Self-Adhesive Container Labels with 2D DataMatrix barcode serialization; (4) ColdSeal Blister Wallets for heat-sensitive medicines; and (5) 3D-Engravix™ optical anti-counterfeit packaging.')) ?></p>
          </div>
        </details>

      </div>

      <!-- Right Column (Questions 6 to 10) -->
      <div class="faq-column">
        
        <!-- Q6 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_6_q', 'Are your manufacturing facilities cGMP compliant?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_6_a', 'Yes. Our production floors, line clearance procedures, and material handling workflows strictly adhere to Current Good Manufacturing Practice (cGMP) and WHO-GMP standards. We implement documented line clearances before and after every production run, positive air pressure zones, segregated staging areas, and full digital batch records to prevent cross-contamination and packaging mix-ups.')) ?></p>
          </div>
        </details>

        <!-- Q7 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_7_q', 'What certifications does Prince Art Packages have?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_7_a', 'Prince Art Packages holds verified international accreditations including ISO 9001:2015 Quality Management System Certification (Certificate No. KQ.2025.5393 by ASCERT) and FSC Chain of Custody Certification (Certificate No. RR-COC-003348 / License Code FSC-C222205). Our facilities are fully audited and aligned with cGMP and WHO-GMP quality requirements.')) ?></p>
          </div>
        </details>

        <!-- Q8 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_8_q', 'Do you manufacture customized pharmaceutical packaging?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_8_a', 'Yes, absolutely. We engineer customized secondary packaging tailored to client bottle, vial, blister, or medical device dimensions. Our in-house structural design team develops custom die-lines, security carton tucks, calendarized pill-folder formats, and bespoke protective partitions, supported by physical mock-ups and automated line testing prior to full-scale production.')) ?></p>
          </div>
        </details>

        <!-- Q9 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_9_q', 'Do you provide anti-counterfeit packaging solutions?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('about', 'faq_9_a', 'Yes. To safeguard pharmaceutical brands and patient health from counterfeit drugs, we provide proprietary 3D-Engravix™ micro-optical packaging features, tamper-evident destructive fiber tear seals, serialized 2D DataMatrix barcodes, guilloche security patterns, and specialized luminescent security inks that enable instant visual authentication.')) ?></p>
          </div>
        </details>

        <!-- Q10 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('about', 'faq_10_q', 'How can I request a quotation?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= get_content('about', 'faq_10_a', 'You can easily <a href="contact.php" class="text-link">request a pharmaceutical packaging quote</a> by submitting our online RFQ form on the Contact page, emailing your technical specifications and dielines directly to info@princeartpackages.com, or calling our technical sales desk at +92 21-38893400-3. Our packaging engineers typically evaluate specifications and deliver a formal quotation within 24 hours.') ?></p>
          </div>
        </details>

      </div>
    </div>

    <!-- CTA / Contact Prompt -->
    <div class="faq-cta-wrap">
      <?= render_cta_buttons('about', 'faq_cta', '<a href="contact.php" class="btn btn-navy btn-lg"><i class="ri-question-answer-line"></i> Have Additional Questions? Contact Our Technical Team &rarr;</a>') ?>
    </div>
  </div>
</section>

<!-- Structured Data for Topical SEO (Schema.org FAQPage) -->
<?php
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_1_q', 'What does Prince Art Packages manufacture?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_1_a', 'Prince Art Packages specializes exclusively in pharmaceutical secondary packaging. We manufacture precision-printed folding paperboard cartons, prescribing information leaflets (PIL), multi-folded outserts, self-adhesive roll labels, ColdSeal blister wallets, honeycomb partitions, and proprietary 3D-Engravix™ optical anti-counterfeit security packaging.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_2_q', 'What is pharmaceutical secondary packaging?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_2_a', 'Pharmaceutical secondary packaging refers to the exterior packaging components—such as printed cartons, patient instruction leaflets, overwrap wallets, and container labels—that enclose, protect, and present the primary packaging (blisters, bottles, ampoules, vials). While primary packaging directly touches the medication, secondary packaging is vital for patient compliance, dosage tracking, light protection, tamper evidence, track-and-trace serialization, and meeting strict health authority labeling regulations.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_3_q', 'How long has Prince Art Packages been in the packaging industry?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_3_a', 'Prince Art Packages has been serving the packaging industry for more than 40 years. Founded originally as Prince Art Press, our company has built four decades of continuous manufacturing craftsmanship, evolving into a modernized, cGMP-compliant enterprise trusted by leading multinational and local pharmaceutical companies.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_4_q', 'Where is Prince Art Packages located?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_4_a', 'Our corporate headquarters and dual manufacturing facilities are strategically located in Karachi, Pakistan, within the prestigious Korangi Creek Industrial Park (KCIP). We operate two dedicated plants: Unit 1 at Sector 38 (KCIP) and Unit 2 on Main Korangi Creek Road, ensuring robust production redundancy and supply chain reliability.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_5_q', 'What pharmaceutical packaging products do you manufacture?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_5_a', 'Our core product lines include: (1) Paperboard Printed Cartons engineered for high-speed automated cartoning machines; (2) Prescribing Information Inserts & Outserts printed on 27gsm to 60gsm pharma-grade paper; (3) Self-Adhesive Container Labels with 2D DataMatrix barcode serialization; (4) ColdSeal Blister Wallets for heat-sensitive medicines; and (5) 3D-Engravix™ optical anti-counterfeit packaging.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_6_q', 'Are your manufacturing facilities cGMP compliant?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_6_a', 'Yes. Our production floors, line clearance procedures, and material handling workflows strictly adhere to Current Good Manufacturing Practice (cGMP) and WHO-GMP standards. We implement documented line clearances before and after every production run, positive air pressure zones, segregated staging areas, and full digital batch records to prevent cross-contamination and packaging mix-ups.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_7_q', 'What certifications does Prince Art Packages have?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_7_a', 'Prince Art Packages holds verified international accreditations including ISO 9001:2015 Quality Management System Certification (Certificate No. KQ.2025.5393 by ASCERT) and FSC Chain of Custody Certification (Certificate No. RR-COC-003348 / License Code FSC-C222205). Our facilities are fully audited and aligned with cGMP and WHO-GMP quality requirements.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_8_q', 'Do you manufacture customized pharmaceutical packaging?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_8_a', 'Yes, absolutely. We engineer customized secondary packaging tailored to client bottle, vial, blister, or medical device dimensions. Our in-house structural design team develops custom die-lines, security carton tucks, calendarized pill-folder formats, and bespoke protective partitions, supported by physical mock-ups and automated line testing prior to full-scale production.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => get_content('about', 'faq_9_q', 'Do you provide anti-counterfeit packaging solutions?'),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => get_content('about', 'faq_9_a', 'Yes. To safeguard pharmaceutical brands and patient health from counterfeit drugs, we provide proprietary 3D-Engravix™ micro-optical packaging features, tamper-evident destructive fiber tear seals, serialized 2D DataMatrix barcodes, guilloche security patterns, and specialized luminescent security inks that enable instant visual authentication.')
            ]
        ],
        [
            "@type" => "Question",
            "name" => strip_tags(get_content('about', 'faq_10_q', 'How can I request a quotation?')),
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => strip_tags(get_content('about', 'faq_10_a', 'You can request a technical packaging quotation by submitting our online RFQ form on the Contact page, emailing your technical specifications and dielines directly to info@princeartpackages.com, or calling our technical sales desk at +92 21-38893400-3. Our packaging engineers typically evaluate specifications and deliver a formal quotation within 24 hours.'))
            ]
        ]
    ]
];
?>
<script type="application/ld+json">
<?= json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?>
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>




