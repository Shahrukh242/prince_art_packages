<?php
$pageSlug = 'quality';
require __DIR__ . '/includes/header.php';
?>

<!-- ================================================================ -->
<!-- 1. HERO SECTION                                                  -->
<!-- ================================================================ -->
<section class="page-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden; text-align: center;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2; max-width: 880px; margin: 0 auto;">
    <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
      <i class="ri-shield-check-line"></i> <?= h(get_content('quality', 'hero_eyebrow', 'REGULATORY ASSURANCE & AUDIT COMPLIANCE')) ?>
    </span>
    <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
      <?= h(get_content('quality', 'hero_title', 'Quality & Compliance at the Core of Our Operations')) ?>
    </h1>
    <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin: 0 auto; max-width: 740px;">
      <?= h(get_content('quality', 'hero_intro', 'Stringent cGMP protocols, ISO 9001:2015 certification, and FSC Chain of Custody standards ensuring zero-defect packaging for regulated healthcare markets.')) ?>
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; align-items: center; margin-top: 1.75rem; flex-wrap: wrap;">
      <?= render_cta_buttons('quality', 'hero', '<a href="#quality-commitment" class="btn btn-gold btn-lg"><i class="ri-shield-star-line"></i> Explore Quality Standards &rarr;</a><a href="contact.php?action=audit" class="btn btn-outline-white btn-lg" style="color: #ffffff; border-color: rgba(255,255,255,0.4); background: rgba(255,255,255,0.08);"><i class="ri-calendar-check-line"></i> Schedule Facility Audit</a>') ?>
    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- 2. QUALITY COMMITMENT                                            -->
<!-- ================================================================ -->
<section class="section" id="quality-commitment" style="padding: 5rem 0; background: #ffffff;">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-shield-cross-line"></i> <?= h(get_content('quality', 'commit_subtitle', 'PHARMACEUTICAL QUALITY COMMITMENT')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('quality', 'commit_title', 'Uncompromising Quality Standards for Patient Safety')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('quality', 'commit_intro', 'In pharmaceutical secondary packaging, quality is not merely an inspection checkpoint—it is the foundational pillar of patient safety and brand protection. Prince Art Packages operates under a total quality management philosophy where every printed carton, prescribing leaflet, and security label is engineered to prevent line stoppages, eliminate artwork mix-ups, and withstand rigorous regulatory audits.')) ?>
      </p>
    </div>

    <!-- 4 Pillars of Quality Commitment Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
      
      <div class="card" style="padding: 1.75rem; border-top: 4px solid var(--navy-dark); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(10,37,64,0.08); display: flex; align-items: center; justify-content: center; color: var(--navy-dark); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-forbid-line"></i>
        </div>
        <h3 style="font-size: 1.2rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'commit_pillar_1_title', 'Zero Error Tolerance')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'commit_pillar_1_desc', 'Rigorous SOPs and continuous line monitoring ensure zero deviation from approved technical specifications.')) ?></p>
      </div>

      <div class="card" style="padding: 1.75rem; border-top: 4px solid var(--teal-primary); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(0,168,150,0.1); display: flex; align-items: center; justify-content: center; color: var(--teal-primary); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-barcode-box-line"></i>
        </div>
        <h3 style="font-size: 1.2rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'commit_pillar_2_title', '100% Batch Traceability')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'commit_pillar_2_desc', 'Full end-to-end documentation from incoming raw paperboard reels to finished carton dispatch with assigned batch codes.')) ?></p>
      </div>

      <div class="card" style="padding: 1.75rem; border-top: 4px solid var(--gold-accent); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(212,175,55,0.12); display: flex; align-items: center; justify-content: center; color: var(--gold-dark); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-user-star-line"></i>
        </div>
        <h3 style="font-size: 1.2rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'commit_pillar_3_title', 'Independent QA Oversight')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'commit_pillar_3_desc', 'Our Quality Assurance department operates with full autonomous stop-production authority across both manufacturing plants.')) ?></p>
      </div>

      <div class="card" style="padding: 1.75rem; border-top: 4px solid var(--navy-primary); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(23,59,108,0.1); display: flex; align-items: center; justify-content: center; color: var(--navy-primary); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-speed-up-line"></i>
        </div>
        <h3 style="font-size: 1.2rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'commit_pillar_4_title', 'Continuous Reinvestment')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'commit_pillar_4_desc', 'Ongoing capital investments in calibrated optical testing instruments, inline sensor inspection, and automated tooling.')) ?></p>
      </div>

    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- 3. CERTIFICATIONS & STANDARDS OVERVIEW                            -->
<!-- ================================================================ -->
<section class="section" style="padding: 4.5rem 0; background: var(--bg-alt); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 860px; margin: 0 auto 3rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-award-line"></i> <?= h(get_content('quality', 'cert_hub_subtitle', 'GLOBAL ACCREDITATIONS')) ?>
      </span>
      <h2 style="font-size: 2.3rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1rem;">
        <?= h(get_content('quality', 'cert_hub_title', 'Internationally Verified Certifications & Standards')) ?>
      </h2>
      <p style="font-size: 1.02rem; line-height: 1.7; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('quality', 'cert_hub_intro', 'Our dual manufacturing facilities in Korangi Creek Industrial Park, Karachi, operate in full compliance with internationally recognized quality and environmental stewardship frameworks.')) ?>
      </p>
    </div>

    <div style="display: flex; gap: 1.25rem; justify-content: center; flex-wrap: wrap; align-items: center;">
      <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.15rem 1.75rem; display: flex; align-items: center; gap: 0.85rem; box-shadow: var(--shadow-sm);">
        <i class="ri-checkbox-circle-fill text-teal" style="font-size: 1.6rem;"></i>
        <div>
          <strong style="color: var(--navy-dark); display: block; font-size: 0.95rem;">cGMP Compliant</strong>
          <span style="font-size: 0.82rem; color: var(--text-muted);">Line Clearance & Cleanliness</span>
        </div>
      </div>

      <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.15rem 1.75rem; display: flex; align-items: center; gap: 0.85rem; box-shadow: var(--shadow-sm);">
        <i class="ri-award-fill text-gold" style="font-size: 1.6rem;"></i>
        <div>
          <strong style="color: var(--navy-dark); display: block; font-size: 0.95rem;">ISO 9001:2015</strong>
          <span style="font-size: 0.82rem; color: var(--text-muted);">Cert # KQ.2025.5393 (ASCERT)</span>
        </div>
      </div>

      <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.15rem 1.75rem; display: flex; align-items: center; gap: 0.85rem; box-shadow: var(--shadow-sm);">
        <i class="ri-leaf-fill text-teal" style="font-size: 1.6rem;"></i>
        <div>
          <strong style="color: var(--navy-dark); display: block; font-size: 0.95rem;">FSC Chain of Custody</strong>
          <span style="font-size: 0.82rem; color: var(--text-muted);">License FSC-C222205</span>
        </div>
      </div>

      <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.15rem 1.75rem; display: flex; align-items: center; gap: 0.85rem; box-shadow: var(--shadow-sm);">
        <i class="ri-shield-star-fill text-navy" style="font-size: 1.6rem;"></i>
        <div>
          <strong style="color: var(--navy-dark); display: block; font-size: 0.95rem;">EN 16679 &amp; Marburg</strong>
          <span style="font-size: 0.82rem; color: var(--text-muted);">Tamper Evidence &amp; Braille</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- 4. cGMP / GMP COMPLIANCE                                         -->
<!-- ================================================================ -->
<section class="section" id="cgmp-compliance" style="padding: 5rem 0; background: #ffffff;">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-tools-fill"></i> <?= h(get_content('quality', 'cgmp_subtitle', 'MANUFACTURING DISCIPLINE')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('quality', 'cgmp_title', 'cGMP Compliance & Zero-Mixup Packaging Controls')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('quality', 'cgmp_intro', 'Current Good Manufacturing Practices (cGMP) govern every step of our printing, folding, gluing, and converting workflows. Designed specifically for pharmaceutical clients, our facility protocols eliminate cross-contamination and ensure audit-ready consistency.')) ?>
      </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
      
      <!-- cGMP 1 -->
      <div class="card" style="padding: 2rem; border-left: 4px solid var(--teal-primary); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
          <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; color: var(--teal-primary); font-size: 1.3rem;">
            <i class="ri-checkbox-circle-line"></i>
          </div>
          <h3 style="font-size: 1.15rem; color: var(--navy-dark); font-weight: 800; margin: 0;"><?= h(get_content('quality', 'cgmp_1_title', 'Formal Line Clearance Protocols')) ?></h3>
        </div>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'cgmp_1_desc', 'Documented multi-point physical and digital line clearance before and after every production run to ensure no leftover material from previous batches.')) ?></p>
      </div>

      <!-- cGMP 2 -->
      <div class="card" style="padding: 2rem; border-left: 4px solid var(--gold-accent); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
          <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(212,175,55,0.15); display: flex; align-items: center; justify-content: center; color: var(--gold-dark); font-size: 1.3rem;">
            <i class="ri-qr-code-line"></i>
          </div>
          <h3 style="font-size: 1.15rem; color: var(--navy-dark); font-weight: 800; margin: 0;"><?= h(get_content('quality', 'cgmp_2_title', '100% Optical Barcode & Pharma-Code Verification')) ?></h3>
        </div>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'cgmp_2_desc', 'High-speed sensor cameras on folder-gluers and leaflet folding lines verify 100% of 2D DataMatrix and miniature barcodes with automated pneumatic rejection.')) ?></p>
      </div>

      <!-- cGMP 3 -->
      <div class="card" style="padding: 2rem; border-left: 4px solid var(--navy-primary); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
          <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(23,59,108,0.1); display: flex; align-items: center; justify-content: center; color: var(--navy-primary); font-size: 1.3rem;">
            <i class="ri-temp-cold-line"></i>
          </div>
          <h3 style="font-size: 1.15rem; color: var(--navy-dark); font-weight: 800; margin: 0;"><?= h(get_content('quality', 'cgmp_3_title', 'Controlled Environmental Conditions')) ?></h3>
        </div>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'cgmp_3_desc', 'Temperature and humidity-regulated converting halls protect paperboard dimensional stability and prevent moisture warping.')) ?></p>
      </div>

      <!-- cGMP 4 -->
      <div class="card" style="padding: 2rem; border-left: 4px solid var(--navy-dark); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
          <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(10,37,64,0.08); display: flex; align-items: center; justify-content: center; color: var(--navy-dark); font-size: 1.3rem;">
            <i class="ri-file-list-3-line"></i>
          </div>
          <h3 style="font-size: 1.15rem; color: var(--navy-dark); font-weight: 800; margin: 0;"><?= h(get_content('quality', 'cgmp_4_title', 'Complete Batch Production Records (BPR)')) ?></h3>
        </div>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'cgmp_4_desc', 'Every batch is accompanied by comprehensive Batch Manufacturing Records (BMR) detailing operators, machine parameters, ink lot numbers, and QC signatures.')) ?></p>
      </div>

    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- 5 & 6. ISO 9001:2015 & FSC CHAIN OF CUSTODY                     -->
<!-- ================================================================ -->
<section class="section" id="iso-fsc-certifications" style="padding: 5rem 0; background: var(--bg-alt); border-top: 1px solid var(--border-color);">
  <div class="container">
    
    <div class="section-header" style="text-align: center; max-width: 860px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-shield-check-fill"></i> AUDITED &amp; ACCREDITED
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        Certified Quality Management &amp; Sustainability
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        Our accredited ISO 9001:2015 Quality Management System and FSC Chain of Custody certifications provide independent, verified assurance of process rigor, raw material traceability, and sustainable manufacturing.
      </p>
    </div>

    <!-- Certificate Cards Grid -->
    <div class="grid-2" style="gap: 2rem;">
      
      <!-- 5. ISO 9001 Card -->
      <div class="cert-card" style="background: #ffffff; padding: 2.5rem; border-radius: 10px; border: 1px solid var(--border-color); border-left: 5px solid var(--teal-brand); box-shadow: var(--shadow-sm);">
        <div style="display:flex; align-items:center; gap:1.25rem; margin-bottom: 1.5rem;">
          <div style="width: 60px; height: 60px; border-radius: 12px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; color: var(--teal-brand); font-size: 2.2rem; flex-shrink: 0;">
            <i class="ri-award-fill"></i>
          </div>
          <div>
            <h3 style="font-size: 1.4rem; color: var(--navy-dark); font-weight: 800; margin: 0 0 0.25rem 0;"><?= h(get_content('quality', 'iso_title', 'ISO 9001:2015 Certification')) ?></h3>
            <p style="color:var(--teal-primary); font-weight: 600; font-size: 0.88rem; margin:0;">Quality Management System Standard</p>
          </div>
        </div>
        
        <div class="cert-meta" style="background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 1.25rem;">
          <p style="margin-bottom:0.4rem; font-size: 0.92rem;"><strong>Certificate No:</strong> <code style="background: rgba(0,168,150,0.15); color: var(--navy-dark); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;"><?= h(get_content('quality', 'iso_cert_no', 'KQ.2025.5393')) ?></code></p>
          <p style="margin-bottom:0.4rem; font-size: 0.92rem;"><strong>Certification Body:</strong> <?= h(get_content('quality', 'iso_body', 'ASCERT Certification and Training Services LLC')) ?></p>
          <p style="margin:0; font-size: 0.92rem;"><strong>Accreditation ID:</strong> <?= h(get_content('quality', 'iso_accreditation', 'MSCB-223')) ?></p>
        </div>
        
        <p style="font-size:0.9rem; color: var(--text-body); line-height: 1.6; margin-bottom: 1rem;">
          <strong>Scope:</strong> <?= h(get_content('quality', 'iso_scope', 'Purchasing of paper materials, production and sales of pharmaceutical secondary packaging products.')) ?>
        </p>
        <p style="font-size:0.88rem; color: var(--text-muted); line-height: 1.55; margin: 0;">
          <?= h(get_content('quality', 'iso_desc', 'Our certified Quality Management System enforces structured risk assessment, calibrated measurement tracking, supplier audits, and systematic root-cause corrective actions (CAPA).')) ?>
        </p>
      </div>

      <!-- 6. FSC Card -->
      <div class="cert-card" style="background: #ffffff; padding: 2.5rem; border-radius: 10px; border: 1px solid var(--border-color); border-left: 5px solid var(--gold-accent); box-shadow: var(--shadow-sm);">
        <div style="display:flex; align-items:center; gap:1.25rem; margin-bottom: 1.5rem;">
          <div style="width: 60px; height: 60px; border-radius: 12px; background: rgba(212,175,55,0.15); display: flex; align-items: center; justify-content: center; color: var(--gold-dark); font-size: 2.2rem; flex-shrink: 0;">
            <i class="ri-leaf-fill"></i>
          </div>
          <div>
            <h3 style="font-size: 1.4rem; color: var(--navy-dark); font-weight: 800; margin: 0 0 0.25rem 0;"><?= h(get_content('quality', 'fsc_title', 'FSC Chain of Custody Certification')) ?></h3>
            <p style="color:var(--gold-dark); font-weight: 600; font-size: 0.88rem; margin:0;">Responsible Forest Resource Chain of Custody</p>
          </div>
        </div>
        
        <div class="cert-meta" style="background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 1.25rem;">
          <p style="margin-bottom:0.4rem; font-size: 0.92rem;"><strong>Certificate No:</strong> <code style="background: rgba(212,175,55,0.2); color: var(--navy-dark); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;"><?= h(get_content('quality', 'fsc_cert_no', 'RR-COC-003348')) ?></code></p>
          <p style="margin-bottom:0.4rem; font-size: 0.92rem;"><strong>FSC License Code:</strong> <code style="background: rgba(212,175,55,0.2); color: var(--navy-dark); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;"><?= h(get_content('quality', 'fsc_license', 'FSC-C222205')) ?></code></p>
          <p style="margin:0; font-size: 0.92rem;"><strong>Standard &amp; Validity:</strong> <?= h(get_content('quality', 'fsc_validity', 'FSC-STD-40-004 (Validity: 19 Nov 2025 – 18 Nov 2030)')) ?></p>
        </div>
        
        <p style="font-size:0.9rem; color: var(--text-body); line-height: 1.6; margin-bottom: 1rem;">
          <strong>Scope:</strong> <?= h(get_content('quality', 'fsc_scope', 'FSC 100%, FSC Mix, and FSC Recycled paper product manufacturing and supply.')) ?>
        </p>
        <p style="font-size:0.88rem; color: var(--text-muted); line-height: 1.55; margin: 0;">
          <?= h(get_content('quality', 'fsc_desc', 'Guarantees that all virgin boxboards and lightweight leaflet papers are sourced from responsibly managed forests adhering to strict environmental, social, and economic sustainability standards.')) ?>
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- 7. INCOMING MATERIAL INSPECTION & LABORATORY (OurLAB)             -->
<!-- ================================================================ -->
<section class="section" id="incoming-material-inspection" style="padding: 5rem 0; background: #ffffff;">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-flask-line"></i> <?= h(get_content('quality', 'mat_subtitle', 'RAW MATERIAL QUALITY ASSURANCE')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('quality', 'mat_title', 'Incoming Material Inspection & Laboratory Testing (OurLAB)')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('quality', 'mat_intro', 'Zero-defect packaging starts with flawless raw materials. Every incoming shipment of paperboard, inks, varnishes, foils, and adhesives undergoes rigorous quarantine and laboratory verification prior to release into production.')) ?>
      </p>
    </div>

    <!-- 4 Incoming Inspection Pillars -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
      
      <!-- Mat 1 -->
      <div class="card" style="padding: 2rem; border-top: 4px solid var(--navy-dark); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(10,37,64,0.08); display: flex; align-items: center; justify-content: center; color: var(--navy-dark); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-ruler-line"></i>
        </div>
        <h3 style="font-size: 1.18rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'mat_1_title', 'GSM & Caliper Micrometer Verification')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'mat_1_desc', 'Digital micrometer gauges and precision analytical balances verify paperboard thickness (microns) and substance (GSM) within ±2% tolerances.')) ?></p>
      </div>

      <!-- Mat 2 -->
      <div class="card" style="padding: 2rem; border-top: 4px solid var(--teal-primary); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(0,168,150,0.1); display: flex; align-items: center; justify-content: center; color: var(--teal-primary); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-drop-line"></i>
        </div>
        <h3 style="font-size: 1.18rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'mat_2_title', 'Moisture Content & Cobb Sizing Analysis')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'mat_2_desc', 'Electronic moisture analyzers and Cobb sizing testers measure board water absorption capacity to prevent carton curling and delamination.')) ?></p>
      </div>

      <!-- Mat 3 -->
      <div class="card" style="padding: 2rem; border-top: 4px solid var(--gold-accent); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(212,175,55,0.12); display: flex; align-items: center; justify-content: center; color: var(--gold-dark); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-palette-line"></i>
        </div>
        <h3 style="font-size: 1.18rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'mat_3_title', 'Spectrophotometric Ink & Delta-E Tolerance')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'mat_3_desc', 'Low-migration vegetable oil-based inks are calibrated with digital spectrophotometers to maintain Delta-E color variance under 1.5 across production.')) ?></p>
      </div>

      <!-- Mat 4 -->
      <div class="card" style="padding: 2rem; border-top: 4px solid var(--navy-primary); background: #ffffff; box-shadow: var(--shadow-sm); border-radius: 8px;">
        <div style="width: 48px; height: 48px; border-radius: 8px; background: rgba(23,59,108,0.1); display: flex; align-items: center; justify-content: center; color: var(--navy-primary); font-size: 1.5rem; margin-bottom: 1rem;">
          <i class="ri-test-tube-line"></i>
        </div>
        <h3 style="font-size: 1.18rem; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.5rem;"><?= h(get_content('quality', 'mat_4_title', 'Adhesive Tack, Viscosity & Fiber-Tear Testing')) ?></h3>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin: 0;"><?= h(get_content('quality', 'mat_4_desc', 'Medical-grade adhesives and hot-melts are tested for bonding strength and guaranteed fiber tear under ambient, refrigerated, and deep-freeze temperatures.')) ?></p>
      </div>

    </div>

    <!-- Laboratory Highlight Card -->
    <div style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; border-radius: 12px; padding: 2.5rem 3rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 2rem; box-shadow: 0 10px 30px rgba(10,37,64,0.15);">
      <div style="max-width: 680px;">
        <span style="font-size: 0.8rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--teal-brand); display: block; margin-bottom: 0.5rem;">
          <i class="ri-flask-fill"></i> Dedicated In-House Facility
        </span>
        <h3 style="color: #ffffff; font-size: 1.6rem; font-weight: 800; margin: 0 0 0.75rem 0;">OurLAB — Advanced Quality Control Laboratory</h3>
        <p style="color: rgba(255,255,255,0.85); font-size: 0.95rem; line-height: 1.65; margin: 0;">
          Equipped with state-of-the-art X-Rite spectrophotometers, Sutherland ink rub testers, digital Cobb testers, and calibrated Braille depth micrometers, OurLAB executes rigorous pre-press, in-process, and post-converting quality clearance for every pharmaceutical batch.
        </p>
      </div>
      <a href="contact.php?action=audit" class="btn btn-gold btn-lg" style="white-space: nowrap;">
        <i class="ri-eye-line"></i> Inspect OurLAB During Audit &rarr;
      </a>
    </div>

  </div>
</section>

<!-- ================================================================ -->
<!-- 8. QUALITY & COMPLIANCE FREQUENTLY ASKED QUESTIONS (FAQ)         -->
<!-- ================================================================ -->
<section class="section faq-section" id="quality-faqs" style="background: var(--bg-alt); padding: 5rem 0; border-top: 1px solid var(--border-color);">
  <div class="container">
    
    <div class="section-header" style="text-align: center; max-width: 860px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-questionnaire-line"></i> <?= h(get_content('quality', 'faq_subtitle', 'Frequently Asked Questions')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('quality', 'faq_title', 'Quality, Compliance & Audit FAQs')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('quality', 'faq_intro', 'Explore detailed answers regarding our pharmaceutical packaging audit procedures, Certificate of Analysis (CoA) dispatch, line clearance verification, and laboratory testing protocols.')) ?>
      </p>
    </div>

    <div class="faq-grid">
      <!-- Left Column (Q1 - Q3) -->
      <div class="faq-column">
        
        <!-- Q1 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('quality', 'faq_1_q', 'Can our Quality Assurance and Regulatory team conduct an on-site facility audit?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('quality', 'faq_1_a', 'Yes, absolutely. We regularly host scheduled facility audits for multinational pharmaceutical manufacturers, regulatory authorities, and independent inspection bodies. We provide full access to our SOPs, equipment validation documentation, calibration logs, and cGMP production floors in Korangi Creek, Karachi.')) ?></p>
          </div>
        </details>

        <!-- Q2 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('quality', 'faq_2_q', 'Do you provide a Certificate of Analysis (CoA) with every delivered batch?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('quality', 'faq_2_a', 'Yes. Every dispatched shipment is accompanied by a formal Certificate of Analysis (CoA) and Certificate of Conformance (CoC) signed by our QA Manager, detailing substrate GSM, caliper, moisture levels, ink batch numbers, barcode scan grades, and line clearance verification.')) ?></p>
          </div>
        </details>

        <!-- Q3 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('quality', 'faq_3_q', 'How is line clearance managed between different customer packaging runs?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('quality', 'faq_3_a', 'We enforce strict 3-tier cGMP line clearance SOPs. Before any new job begins, the previous job materials, plates, cutting dies, waste, and documentation are completely cleared from the packaging cell. Line clearance is physically inspected and dual-signed by the Line Incharge and the QA Inspector before machine startup.')) ?></p>
          </div>
        </details>

      </div>

      <!-- Right Column (Q4 - Q6) -->
      <div class="faq-column">

        <!-- Q4 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('quality', 'faq_4_q', 'What spectrophotometric color tolerances (Delta-E) do you guarantee?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('quality', 'faq_4_a', 'Our offset presses operate with closed-loop inline spectrophotometry and densitometry calibrated to X-Rite Pantone digital color standards. We maintain Delta-E (ΔE) color variances under 1.5 to guarantee exact brand shade consistency across multiple batches over years of production.')) ?></p>
          </div>
        </details>

        <!-- Q5 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('quality', 'faq_5_q', 'How is Braille embossing verified on pharmaceutical cartons?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('quality', 'faq_5_a', 'Braille embossing is applied in-line using precision CNC tooling compliant with the Marburg Medium standard (European Directive 2004/27/EC). Our QA laboratory verifies dot height (0.20 mm standard) and dot spacing across sampling sheets using calibrated optical depth micrometers.')) ?></p>
          </div>
        </details>

        <!-- Q6 -->
        <details class="faq-item">
          <summary class="faq-summary">
            <span class="faq-q-text"><?= h(get_content('quality', 'faq_6_q', 'What is your procedure for Corrective and Preventive Actions (CAPA)?')) ?></span>
            <span class="faq-icon"><i class="ri-add-line"></i></span>
          </summary>
          <div class="faq-answer">
            <p><?= h(get_content('quality', 'faq_6_a', 'Under our ISO 9001:2015 QMS framework, any identified non-conformance initiates a structured CAPA investigation utilizing 5-Why root-cause analysis and Ishikawa fishbone diagrams. Corrective actions are implemented, documented, and audited for long-term effectiveness within 14 business days.')) ?></p>
          </div>
        </details>

      </div>
    </div>

  </div>
</section>

<!-- ================================================================ -->
<!-- 9. FINAL CALL TO ACTION (CTA)                                    -->
<!-- ================================================================ -->
<section class="section" style="padding: 5rem 0; background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; text-align: center;">
  <div class="container" style="max-width: 820px; margin: 0 auto;">
    <span style="font-size: 0.85rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--teal-brand); display: inline-block; margin-bottom: 0.75rem;">
      <i class="ri-shield-keyhole-line"></i> AUDIT-READY PHARMACEUTICAL PACKAGING
    </span>
    <h2 style="color: #ffffff; font-size: 2.6rem; font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
      <?= h(get_content('quality', 'cta_title', 'Schedule a Facility Audit or Request Quality Manual')) ?>
    </h2>
    <p style="font-size: 1.1rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin-bottom: 2.25rem;">
      <?= h(get_content('quality', 'cta_desc', 'Speak directly with our Quality Assurance Directorate to arrange an on-site audit, request technical compliance dossiers, or review our laboratory testing capabilities.')) ?>
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; align-items: center;">
      <?= render_cta_buttons('quality', 'quality_banner', '<a href="contact.php?action=audit" class="btn btn-gold btn-lg" style="box-shadow: 0 4px 14px rgba(212,175,55,0.4);"><i class="ri-calendar-check-line"></i> Schedule an On-Site Audit &rarr;</a><a href="contact.php?action=quality-dossier" class="btn btn-outline-white btn-lg" style="color: #ffffff; border-color: rgba(255,255,255,0.4); background: rgba(255,255,255,0.08);"><i class="ri-file-download-line"></i> Request QA Manual Dossier</a>') ?>
    </div>
  </div>
</section>

<!-- Schema.org JSON-LD FAQPage for Quality SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": <?= json_encode(get_content('quality', 'faq_1_q', 'Can our Quality Assurance and Regulatory team conduct an on-site facility audit?')) ?>,
      "acceptedAnswer": {
        "@type": "Answer",
        "text": <?= json_encode(get_content('quality', 'faq_1_a', 'Yes, we regularly host scheduled facility audits for pharmaceutical manufacturers.')) ?>
      }
    },
    {
      "@type": "Question",
      "name": <?= json_encode(get_content('quality', 'faq_2_q', 'Do you provide a Certificate of Analysis (CoA) with every delivered batch?')) ?>,
      "acceptedAnswer": {
        "@type": "Answer",
        "text": <?= json_encode(get_content('quality', 'faq_2_a', 'Yes, every shipment includes a signed CoA and Certificate of Conformance.')) ?>
      }
    },
    {
      "@type": "Question",
      "name": <?= json_encode(get_content('quality', 'faq_3_q', 'How is line clearance managed between different customer packaging runs?')) ?>,
      "acceptedAnswer": {
        "@type": "Answer",
        "text": <?= json_encode(get_content('quality', 'faq_3_a', 'We enforce strict 3-tier cGMP line clearance SOPs dual-signed before machine startup.')) ?>
      }
    }
  ]
}
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
