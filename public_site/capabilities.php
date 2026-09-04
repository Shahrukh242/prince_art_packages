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
          <span class="cap-eyebrow cap-eyebrow-dark"><?= h(get_content('capabilities', 'hero_eyebrow', 'INDUSTRIAL MANUFACTURING INFRASTRUCTURE')) ?></span>
          <h1 class="cap-hero-title"><?= h(get_content('capabilities', 'hero_title', 'Engineered for High-Volume Packaging. Built for Precision.')) ?></h1>
          <p class="cap-hero-lead"><?= h(get_content('capabilities', 'hero_lead', 'Our integrated manufacturing infrastructure combines high-capacity offset printing, advanced coating, converting and finishing capabilities to deliver consistent pharmaceutical packaging at production scale.')) ?></p>
          <div style="display:flex; gap:1.25rem; flex-wrap:wrap; align-items:center;">
            <?= render_cta_buttons('capabilities', 'hero', '<a href="contact" class="btn btn-gold btn-lg"><i class="ri-arrow-right-line"></i> Request a Quote</a>') ?>
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
            <a href="contact.php" class="btn btn-gold btn-lg" style="padding:0.85rem 2rem;">Discuss Your Requirement &rarr;</a>
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
      <div class="container" style="max-width:720px;">
        <h2 style="font-size:2.4rem; color:var(--navy-dark); margin-bottom:0.75rem;"><?= h(get_content('capabilities', 'cta_title', 'Planning Your Next Packaging Run?')) ?></h2>
        <p style="font-size:1.05rem; color:var(--text-body); margin-bottom:2rem;"><?= h(get_content('capabilities', 'cta_desc', 'Share your packaging specification, expected volume and production timeline with our team.')) ?></p>
        <div style="display:flex; gap:1.25rem; justify-content:center; flex-wrap:wrap; align-items:center;">
          <?= render_cta_buttons('capabilities', 'bottom_cta', '<a href="contact.php" class="btn btn-gold btn-lg"><i class="ri-calendar-check-line"></i> Request a Facility Audit</a><a href="contact.php" class="btn btn-outline-navy btn-lg"><i class="ri-file-list-3-line"></i> Request a Quote</a>') ?>
        </div>
      </div>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
