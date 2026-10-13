<?php
$pageSlug = 'capabilities';
require __DIR__ . '/includes/header.php';
?>
<!-- SECTION 01 — HERO -->
    <div class="cap-hero-block">
      <div class="cap-hero-bg"></div>
      <div class="cap-hero-grid-overlay"></div>
      <div class="container">
        <div class="cap-hero-content">
          <span class="cap-eyebrow cap-eyebrow-dark"><?= h(get_content('capabilities', 'hero_eyebrow', 'GLOBAL PACKAGING EXPERTISE & ADVISORY')) ?></span>
          <h1 class="cap-hero-title"><?= h(get_content('capabilities', 'hero_title', 'Packaging Consultant')) ?></h1>
          <p class="cap-hero-lead" style="font-size: 1.25rem; font-weight: 500; color: #ffffff; line-height: 1.6; max-width: 820px; margin-bottom: 1.25rem;">
            <?= h(get_content('capabilities', 'hero_lead', 'We provide our experienced consultancy services internationally to developing the right packaging solution for your brand.')) ?>
          </p>
          <p style="font-size: 1.05rem; color: rgba(255,255,255,0.85); line-height: 1.65; max-width: 780px; margin-bottom: 2rem;">
            <?= h(get_content('capabilities', 'hero_sublead', 'From structural dieline engineering and substrate selection to high-speed cartoning machine compatibility, serialization, and brand security — our packaging consultants deliver end-to-end technical expertise for domestic and global healthcare brands.')) ?>
          </p>
          <div style="display:flex; gap:1.25rem; flex-wrap:wrap; align-items:center;">
            <?= render_cta_buttons('capabilities', 'hero', '<a href="contact.php" class="btn btn-gold btn-lg"><i class="ri-question-answer-line"></i> How to Contact us</a><a href="#consultancy-pillars" class="btn btn-outline-white btn-lg" style="border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.85rem 1.75rem; border-radius: 8px; font-weight: 600; text-decoration: none;"><i class="ri-compass-3-line"></i> Explore Consultancy Services</a>') ?>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 02 — PACKAGING CONSULTANCY SERVICE PILLARS -->
    <div id="consultancy-pillars" style="padding: 5rem 0; background: #ffffff; border-bottom: 1px solid var(--border-color);">
      <div class="container">
        <div style="text-align: center; max-width: 840px; margin: 0 auto 3.5rem auto;">
          <span class="cap-eyebrow" style="color: var(--teal-primary); font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; font-size: 0.85rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px; display: inline-block; margin-bottom: 0.75rem;">
            <i class="ri-customer-service-2-line"></i> CONSULTANCY DOMAINS
          </span>
          <h2 style="color: var(--navy-dark); font-size: 2.4rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem;">
            <?= h(get_content('capabilities', 'consult_heading', 'How Our Packaging Consultants Help Your Brand')) ?>
          </h2>
          <p style="color: var(--text-body); font-size: 1.05rem; line-height: 1.7;">
            <?= h(get_content('capabilities', 'consult_desc', 'Whether you are launching a new pharmaceutical line, entering regulated export markets, or optimizing production efficiency on automated cartoning lines, our technical consultancy bridges the gap between design vision and industrial manufacturing reality.')) ?>
          </p>
        </div>

        <div class="grid-3" style="gap: 2rem;">
          <!-- Pillar 1 -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 2.25rem 1.75rem; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';">
            <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
              <i class="ri-pencil-ruler-2-line text-teal" style="font-size: 1.6rem;"></i>
            </div>
            <h3 style="font-size: 1.25rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.75rem;">Dieline &amp; Structural Engineering</h3>
            <p style="font-size: 0.92rem; line-height: 1.65; color: var(--text-body); margin: 0;">Custom folding carton structures, reverse tucks, crash-lock bottoms, and partition design engineered for structural integrity and optimal board yield.</p>
          </div>

          <!-- Pillar 2 -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 2.25rem 1.75rem; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';">
            <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
              <i class="ri-stack-line text-teal" style="font-size: 1.6rem;"></i>
            </div>
            <h3 style="font-size: 1.25rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.75rem;">Substrate &amp; Material Selection</h3>
            <p style="font-size: 0.92rem; line-height: 1.65; color: var(--text-body); margin: 0;">Board caliper, GSM weight, and stiffness optimization (FBB, SBS, Bleached Board, FSC certified stocks) to minimize unit cost without compromising strength.</p>
          </div>

          <!-- Pillar 3 -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 2.25rem 1.75rem; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';">
            <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
              <i class="ri-settings-4-line text-teal" style="font-size: 1.6rem;"></i>
            </div>
            <h3 style="font-size: 1.25rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.75rem;">Cartoning Line Runnability</h3>
            <p style="font-size: 0.92rem; line-height: 1.65; color: var(--text-body); margin: 0;">Troubleshooting machine jamming, crease opening force testing, and surface slip (COF) calibration for flawless operation on high-speed packaging lines.</p>
          </div>

          <!-- Pillar 4 -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 2.25rem 1.75rem; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';">
            <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
              <i class="ri-barcode-line text-teal" style="font-size: 1.6rem;"></i>
            </div>
            <h3 style="font-size: 1.25rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.75rem;">Serialization &amp; Braille Compliance</h3>
            <p style="font-size: 0.92rem; line-height: 1.65; color: var(--text-body); margin: 0;">2D DataMatrix barcode contrast, GS1 serialisation readiness, and Marburg medium Braille dot height precision conforming to European and international standards.</p>
          </div>

          <!-- Pillar 5 -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 2.25rem 1.75rem; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';">
            <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
              <i class="ri-lock-password-line text-teal" style="font-size: 1.6rem;"></i>
            </div>
            <h3 style="font-size: 1.25rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.75rem;">Security &amp; Tamper Evidence</h3>
            <p style="font-size: 0.92rem; line-height: 1.65; color: var(--text-body); margin: 0;">Anti-counterfeiting strategies including micro-text, guilloche patterns, VOID tamper-evident seals, UV fluorescing inks, and holographic security patches.</p>
          </div>

          <!-- Pillar 6 -->
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 2.25rem 1.75rem; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';">
            <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(0,168,150,0.12); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
              <i class="ri-global-line text-teal" style="font-size: 1.6rem;"></i>
            </div>
            <h3 style="font-size: 1.25rem; color: var(--navy-dark); font-weight: 700; margin-bottom: 0.75rem;">International Brand Development</h3>
            <p style="font-size: 0.92rem; line-height: 1.65; color: var(--text-body); margin: 0;">Consulting for export brand compliance across Middle East, Africa, Central Asia, and Western pharmaceutical import regulations and shelf longevity.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 03 — MANUFACTURING INFRASTRUCTURE -->
    <div id="cap-infra" class="cap-infra-section">
      <div class="container">
        <div style="margin-bottom: 4rem;">
          <span class="cap-eyebrow"><?= h(get_content('capabilities', 'infra_eyebrow', 'PRODUCTION DEPARTMENTS')) ?></span>
          <h2 style="color:var(--navy-dark); margin-bottom:0.75rem; font-size:2.4rem;"><?= h(get_content('capabilities', 'infra_title', 'Manufacturing Infrastructure')) ?></h2>
          <p style="color:var(--text-body); max-width:760px; font-size:1.05rem;"><?= h(get_content('capabilities', 'infra_desc', 'From printing and surface finishing to precision converting, our production infrastructure brings critical packaging processes together under one manufacturing operation.')) ?></p>
        </div>

        <!-- 01 — PRINTING -->
        <div class="cap-infra-row">
          <div>
            <span class="cap-infra-badge"><?= h(get_content('capabilities', 'dept1_badge', '01 • PRINTING')) ?></span>
            <div class="cap-big-spec"><?= h(get_content('capabilities', 'dept1_spec', '1–6 Color')) ?></div>
            <h3 style="font-size:1.6rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'dept1_title', '15 Printing Machines')) ?></h3>
            <p style="color:var(--text-body); font-size:1rem; line-height:1.6; margin-bottom:1.25rem;"><?= h(get_content('capabilities', 'dept1_desc', 'Sheetfed offset printing infrastructure supporting consistent, high-volume production across pharmaceutical packaging requirements.')) ?></p>
          </div>
          <div>
            <?= render_image(get_content('capabilities', 'dept1_image', 'assets/images/prod_labels.jpg'), get_content('capabilities', 'dept1_title', 'Printing Machines'), 'cap-infra-img') ?>
          </div>
        </div>

        <!-- 02 — COATINGS & SURFACE FINISHING -->
        <div class="cap-infra-row reverse">
          <div>
            <span class="cap-infra-badge"><?= h(get_content('capabilities', 'dept2_badge', '02 • SURFACE FINISHING')) ?></span>
            <div class="cap-big-spec"><?= h(get_content('capabilities', 'dept2_spec', '3 Finishing Processes')) ?></div>
            <h3 style="font-size:1.6rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'dept2_title', 'Coatings & Surface Finishing')) ?></h3>
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1.25rem;">
              <span class="badge-tech"><i class="ri-checkbox-circle-fill text-teal"></i> Flexo Coating</span>
              <span class="badge-tech"><i class="ri-checkbox-circle-fill text-teal"></i> UV Coating</span>
              <span class="badge-tech"><i class="ri-checkbox-circle-fill text-teal"></i> Water-Based Coating</span>
            </div>
            <p style="color:var(--text-body); font-size:1rem; line-height:1.6;"><?= h(get_content('capabilities', 'dept2_desc', 'Multiple coating processes provide flexibility across substrates, finishes and packaging requirements.')) ?></p>
          </div>
          <div>
            <?= render_image(get_content('capabilities', 'dept2_image', 'assets/images/engravix.jpg'), get_content('capabilities', 'dept2_title', 'Coatings'), 'cap-infra-img') ?>
          </div>
        </div>

        <!-- 03 — DIE CUTTING & CREASING -->
        <div class="cap-infra-row">
          <div>
            <span class="cap-infra-badge"><?= h(get_content('capabilities', 'dept3_badge', '03 • CONVERTING')) ?></span>
            <div class="cap-big-spec"><?= h(get_content('capabilities', 'dept3_spec', '6 Units')) ?></div>
            <h3 style="font-size:1.6rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'dept3_title', 'Die Cutting & Creasing')) ?></h3>
            <p style="color:var(--text-body); font-size:1rem; line-height:1.6;"><?= h(get_content('capabilities', 'dept3_desc', 'Dedicated die cutting and creasing capacity supporting accurate carton conversion and repeatable production output.')) ?></p>
          </div>
          <div>
            <?= render_image(get_content('capabilities', 'dept3_image', 'assets/images/prod_cartons.jpg'), get_content('capabilities', 'dept3_title', 'Die Cutting'), 'cap-infra-img') ?>
          </div>
        </div>

        <!-- 04 — FOLDING & GLUING -->
        <div class="cap-infra-row reverse">
          <div>
            <span class="cap-infra-badge"><?= h(get_content('capabilities', 'dept4_badge', '04 • CARTON ASSEMBLY')) ?></span>
            <div class="cap-big-spec"><?= h(get_content('capabilities', 'dept4_spec', '2 Units')) ?></div>
            <h3 style="font-size:1.6rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'dept4_title', 'Folding & Gluing')) ?></h3>
            <p style="color:var(--text-body); font-size:1rem; line-height:1.6;"><?= h(get_content('capabilities', 'dept4_desc', 'Folding and gluing capability designed for efficient carton conversion and dependable production continuity.')) ?></p>
          </div>
          <div>
            <?= render_image(get_content('capabilities', 'dept4_image', 'assets/images/coldseal.jpg'), get_content('capabilities', 'dept4_title', 'Folding & Gluing'), 'cap-infra-img') ?>
          </div>
        </div>

        <!-- 05 — PAPER FOLDING -->
        <div class="cap-infra-row">
          <div>
            <span class="cap-infra-badge"><?= h(get_content('capabilities', 'dept5_badge', '05 • LEAFLET PROCESSING')) ?></span>
            <div class="cap-big-spec"><?= h(get_content('capabilities', 'dept5_spec', '4 Units')) ?></div>
            <h3 style="font-size:1.6rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'dept5_title', 'Paper Folding')) ?></h3>
            <p style="color:var(--text-body); font-size:1rem; line-height:1.6;"><?= h(get_content('capabilities', 'dept5_desc', 'Dedicated paper folding capacity supporting pharmaceutical leaflets and folded printed materials.')) ?></p>
          </div>
          <div>
            <?= render_image(get_content('capabilities', 'dept5_image', 'assets/images/prod_leaflets.jpg'), get_content('capabilities', 'dept5_title', 'Paper Folding'), 'cap-infra-img') ?>
          </div>
        </div>

        <!-- 06 — PAPER & BOARD CUTTING -->
        <div class="cap-infra-row reverse">
          <div>
            <span class="cap-infra-badge"><?= h(get_content('capabilities', 'dept6_badge', '06 • STOCK PREPARATION')) ?></span>
            <div class="cap-big-spec"><?= h(get_content('capabilities', 'dept6_spec', '8 Units')) ?></div>
            <h3 style="font-size:1.6rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'dept6_title', 'Paper & Board Cutting')) ?></h3>
            <p style="color:var(--text-body); font-size:1rem; line-height:1.6;"><?= h(get_content('capabilities', 'dept6_desc', 'Precision cutting infrastructure supporting paper and board preparation throughout the production process.')) ?></p>
          </div>
          <div>
            <?= render_image(get_content('capabilities', 'dept6_image', 'assets/images/prod_honeycomb.jpg'), get_content('capabilities', 'dept6_title', 'Paper & Board Cutting'), 'cap-infra-img') ?>
          </div>
        </div>

      </div>
    </div>

    <!-- SECTION 04 — PRODUCTION SCALE -->
    <div class="cap-scale-section">
      <div class="container">
        <div class="grid-2" style="align-items:center;">
          <div>
            <span class="cap-eyebrow cap-eyebrow-dark">PRODUCTION SCALE</span>
            <h2 style="font-size:2.8rem; color:#ffffff; line-height:1.15; margin-bottom:1.5rem;"><?= h(get_content('capabilities', 'scale_title', 'Built to Keep Production Moving.')) ?></h2>
            <p style="color:rgba(255,255,255,0.85); font-size:1.05rem; line-height:1.6; margin-bottom:2rem;"><?= h(get_content('capabilities', 'scale_desc', 'Integrated printing, coating, cutting, converting and finishing capabilities provide the production infrastructure required for high-volume pharmaceutical packaging.')) ?></p>
            <?= render_cta_buttons('capabilities', 'cta_mid', '<a href="contact.php" class="btn btn-gold btn-lg" style="padding:0.85rem 2rem;">Discuss Your Requirement &rarr;</a>') ?>
          </div>
          <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.15); padding:2.5rem; border-radius:var(--radius-md);">
            <div style="margin-bottom:2rem;">
              <div class="cap-scale-number"><?= h(get_content('capabilities', 'scale_cartons', '8–10 Million')) ?></div>
              <div style="font-size:1.1rem; font-weight:700; color:#ffffff; text-transform:uppercase; letter-spacing:0.05em; margin-top:0.35rem;">Cartons / Month</div>
            </div>
            <div>
              <div class="cap-scale-number"><?= h(get_content('capabilities', 'scale_leaflets', '23–25 Million')) ?></div>
              <div style="font-size:1.1rem; font-weight:700; color:#ffffff; text-transform:uppercase; letter-spacing:0.05em; margin-top:0.35rem;">Leaflets / Month</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 05 — END-TO-END PRODUCTION FLOW -->
    <div class="cap-flow-section">
      <div class="container">
        <div style="margin-bottom: 3.5rem; text-align:center;">
          <span class="cap-eyebrow">INTEGRATED WORKFLOW</span>
          <h2 style="color:var(--navy-dark); font-size:2.4rem;">From Raw Material to Finished Packaging</h2>
          <p style="color:var(--text-muted); max-width:640px; margin:0.5rem auto 0 auto;">Sequential manufacturing stages bringing technical precision to high-volume secondary packaging runs.</p>
        </div>

        <div class="cap-flow-grid">
          <div class="cap-flow-step">
            <span class="cap-flow-num">STAGE 01</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">RAW MATERIAL</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">Paperboard &amp; paper stock allocation and inspection.</p>
          </div>

          <div class="cap-flow-step">
            <span class="cap-flow-num">STAGE 02</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">PRINTING</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">1–6 color sheetfed offset printing press execution.</p>
          </div>

          <div class="cap-flow-step">
            <span class="cap-flow-num">STAGE 03</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">COATING</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">Flexo, UV or water-based protective coating application.</p>
          </div>

          <div class="cap-flow-step">
            <span class="cap-flow-num">STAGE 04</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">CUTTING</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">Paper and board sizing across 8 precision units.</p>
          </div>

          <div class="cap-flow-step">
            <span class="cap-flow-num">STAGE 05</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">DIE CUTTING</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">Die cutting and creasing across 6 converting units.</p>
          </div>

          <div class="cap-flow-step">
            <span class="cap-flow-num">STAGE 06</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">FOLDING</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">Leaflet and carton folding across 4 paper folding units.</p>
          </div>

          <div class="cap-flow-step">
            <span class="cap-flow-num">STAGE 07</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">GLUING</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">Automated carton gluing across 2 folding &amp; gluing units.</p>
          </div>

          <div class="cap-flow-step" style="border-top:3px solid var(--teal-brand);">
            <span class="cap-flow-num">STAGE 08</span>
            <h4 style="color:var(--navy-dark); margin-bottom:0.5rem;">FINISHED PACKAGING</h4>
            <p style="font-size:0.85rem; color:var(--text-body); margin:0;">Inspected, palletized and ready for client delivery.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 08 — FINAL CTA -->
    <div style="background-color:var(--bg-main); padding:5.5rem 0; border-top:1px solid var(--border-color); text-align:center;">
      <div class="container" style="max-width:760px;">
        <h2 style="font-size:2.4rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'cta_title', 'Need Expert Packaging Consultancy for Your Brand?')) ?></h2>
        <p style="font-size:1.1rem; color:var(--text-body); margin-bottom:2rem; line-height: 1.7;"><?= h(get_content('capabilities', 'cta_desc', 'We provide our experienced consultancy services internationally to developing the right packaging solution for your brand. Reach out to our technical team to discuss your project.')) ?></p>
        <div style="display:flex; gap:1.25rem; justify-content:center; flex-wrap:wrap; align-items:center;">
          <?= render_cta_buttons('capabilities', 'bottom_cta', '<a href="contact.php" class="btn btn-gold btn-lg"><i class="ri-question-answer-line"></i> How to Contact us</a>') ?>
        </div>
      </div>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
