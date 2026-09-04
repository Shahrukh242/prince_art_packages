<?php
$pageSlug = 'products';
require __DIR__ . '/includes/header.php';
?>
<!-- ================================================================ -->
<!-- SECTION 01 â€” PAGE HERO                                           -->
<!-- ================================================================ -->
<section class="page-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden; text-align: center;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2; max-width: 860px; margin: 0 auto;">
    <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
      <i class="ri-box-3-line"></i> <?= h(get_content('products', 'hero_eyebrow', 'PHARMACEUTICAL SECONDARY PACKAGING CATALOG')) ?>
    </span>
    <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
      <?= h(get_content('products', 'hero_title', 'Engineered Secondary Packaging Solutions for Regulated Pharma')) ?>
    </h1>
    <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin: 0 auto; max-width: 720px;">
      <?= h(get_content('products', 'hero_intro', 'High-precision Printed Cartons, prescribing leaflets, self-adhesive roll labels, and protective partitions manufactured under ISO 9001:2015 and cGMP guidelines.')) ?>
    </p>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 02 â€” PRODUCT CATALOG (H2 & SUPPORTING CONTENT)           -->
<!-- ================================================================ -->
<section class="section" style="padding: 4.5rem 0;">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-stack-line"></i> <?= h(get_content('products', 'sec_subtitle', 'MANUFACTURING PRODUCT RANGE')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('products', 'sec_h2_title', 'Comprehensive Pharmaceutical Secondary Packaging Portfolio')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('products', 'sec_h2_intro', 'Prince Art Packages manufactures a specialized range of secondary packaging tailored for pharmaceutical formulation plants, OTC healthcare brands, and medical device manufacturers. Every packaging format is precision-engineered for seamless operation on high-speed automated cartoning lines, blister sealers, and serialization barcode vision systems â€” ensuring zero defect rates, batch traceability, and full regulatory compliance.')) ?>
      </p>
    </div>

      <!-- Uniform 8 Product Cards Grid (3 Columns per Row) -->
      <div class="grid-3-products">
        
        <!-- Product 1: Printed Cartons -->
        <div class="product-card" id="product-printed-cartons">
          <a href="product-detail.php?slug=printed-cartons" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p1_image', 'assets/images/prod_cartons.jpg'), get_content('products', 'p1_title', 'Printed Cartons'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p1_tag', 'SECONDARY PACKAGING')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=printed-cartons" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p1_title', 'Printed Cartons')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p1_desc', 'Precision-printed paperboard Printed Cartons engineered for automated high-speed packaging lines. Available in reverse tuck, crash lock, and custom dividers.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=printed-cartons" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

        <!-- Product 2: Leaf-inserts -->
        <div class="product-card" id="product-leaf-inserts">
          <a href="product-detail.php?slug=leaf-inserts" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p2_image', 'assets/images/prod_leaflets.jpg'), get_content('products', 'p2_title', 'Leaf-inserts'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p2_tag', 'PATIENT INFORMATION')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=leaf-inserts" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p2_title', 'Leaf-inserts')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p2_desc', 'Prescribing information leaflets (PIL) and patient instruction inserts folded to precise dimensions for automated cartoning line insertion.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=leaf-inserts" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

        <!-- Product 3: Printed Labels -->
        <div class="product-card" id="product-printed-labels">
          <a href="product-detail.php?slug=printed-labels" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p3_image', 'assets/images/prod_labels.jpg'), get_content('products', 'p3_title', 'Printed Labels'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p3_tag', 'CONTAINER LABELING')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=printed-labels" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p3_title', 'Printed Labels')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p3_desc', 'High-precision self-adhesive roll labels for pharmaceutical bottles, vials, ampoules, and IV containers with serialization barcode compatibility.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=printed-labels" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

        <!-- Product 4: Honeycomb Separators -->
        <div class="product-card" id="product-honeycomb-separators">
          <a href="product-detail.php?slug=honeycomb-separators" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p4_image', 'assets/images/prod_honeycomb.jpg'), get_content('products', 'p4_title', 'Honeycomb Separators'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p4_tag', 'PROTECTIVE PARTITIONS')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=honeycomb-separators" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p4_title', 'Honeycomb Separators')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p4_desc', 'Protective cardboard honeycomb dividers and grid partitions designed to safeguard glass ampoules and liquid vials against transit breakage.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=honeycomb-separators" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

        <!-- Product 5: Pill-folders -->
        <div class="product-card" id="product-pill-folders">
          <a href="product-detail.php?slug=pill-folders" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p5_image', 'assets/images/prod_pill_folders.jpg'), get_content('products', 'p5_title', 'Pill-folders'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p5_tag', 'DOSE ADHERENCE')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=pill-folders" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p5_title', 'Pill-folders')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p5_desc', 'Paperboard medicine packaging wallets with integrated dose-tracking calendar compartments engineered to support patient medication adherence.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=pill-folders" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

        <!-- Product 6: Temper Evident Cartons & Labels -->
        <div class="product-card" id="product-temper-evident">
          <a href="product-detail.php?slug=tamper-evident" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p6_image', 'assets/images/prod_tamper_labels.jpg'), get_content('products', 'p6_title', 'Temper Evident Cartons & Labels'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p6_tag', 'SECURITY SEALS')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=tamper-evident" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p6_title', 'Temper Evident Cartons & Labels')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p6_desc', 'Destructible security seals and tamper-evident carton structures that provide immediate, irreversible visual evidence if packaging has been breached.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=tamper-evident" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

        <!-- Product 7: 3D-ENGRAVIX -->
        <div class="product-card" id="product-3d-engravix">
          <a href="product-detail.php?slug=3d-engravix" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p7_image', 'assets/images/engravix.jpg'), get_content('products', 'p7_title', '3D-ENGRAVIX'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:rgba(11, 37, 69, 0.15); color:var(--navy-dark); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p7_tag', 'OPTICAL ANTI-COUNTERFEIT')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=3d-engravix" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p7_title', '3D-ENGRAVIXâ„¢')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p7_desc', 'Proprietary micro-structured optical security feature integrated directly onto Printed Cartons for instant visual authentication under ambient light.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=3d-engravix" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

        <!-- Product 8: Cold-seal Wallet -->
        <div class="product-card" id="product-cold-seal-wallet">
          <a href="product-detail.php?slug=coldseal-blister-wallet" style="text-decoration: none; color: inherit;">
            <?= render_image(get_content('products', 'p8_image', 'assets/images/coldseal.jpg'), get_content('products', 'p8_title', 'Cold-seal Wallet'), 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;"><?= h(get_content('products', 'p8_tag', 'PRESSURE-SEALED ECO PACKAGING')) ?></span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=coldseal-blister-wallet" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h(get_content('products', 'p8_title', 'Cold-seal Wallet')) ?>
                </a>
              </h3>
              <p><?= h(get_content('products', 'p8_desc', 'Pressure-sealed (non-heat-sealed) paperboard blister packaging wallet encapsulating blister cards without applying heat to temperature-sensitive medicine.')) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=coldseal-blister-wallet" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ================================================================ -->
  <!-- SECTION 03 — PRODUCT FREQUENTLY ASKED QUESTIONS (FAQ)            -->
  <!-- ================================================================ -->
  <section class="section faq-section" id="product-faqs" style="background: var(--bg-alt); padding: 5rem 0; border-top: 1px solid var(--border-color);">
    <div class="container">
      
      <div class="section-header" style="text-align: center; max-width: 860px; margin: 0 auto 3.5rem auto;">
        <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
          <i class="ri-questionnaire-line"></i> <?= h(get_content('products', 'faq_subtitle', 'Frequently Asked Questions')) ?>
        </span>
        <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
          <?= h(get_content('products', 'faq_title', 'Product & Manufacturing FAQs')) ?>
        </h2>
        <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
          <?= h(get_content('products', 'faq_intro', 'Common technical questions regarding our pharmaceutical secondary packaging specifications, substrates, customization capabilities, machine compatibility, and minimum order requirements.')) ?>
        </p>
      </div>

      <div class="faq-grid">
        <!-- Left Column (Questions 1 to 4) -->
        <div class="faq-column">
          
          <!-- Q1 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_1_q', 'What pharmaceutical secondary packaging products does Prince Art Packages manufacture?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_1_a', 'We manufacture a specialized range of regulated secondary packaging including high-precision Printed Cartons (RTE, STE, auto-lock crash bottom), Leaf-Inserts (prescribing leaflets and miniature outserts down to 27 GSM), self-adhesive Printed Labels, Honeycomb Separators for glass ampoules and vials, Dose-Adherence Pill-Folders, Tamper-Evident Cartons & Security Seals (EN 16679 compliant), 3D-ENGRAVIX™ Optical Anti-Counterfeit Cartons, and eco-friendly ColdSeal Blister Wallets.')) ?></p>
            </div>
          </details>

          <!-- Q2 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_2_q', 'Can you customize carton dimensions, dielines, and board calipers for our specific packaging machines?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_2_a', 'Yes, absolutely. Our in-house structural design desk develops bespoke CAD dielines, electronic PDF proofs, and physical unprinted or printed sample mock-ups tailored to your exact primary container dimensions and automated cartoning lines (such as Bosch, Uhlmann, IMA, Marchesini, and Romaco).')) ?></p>
            </div>
          </details>

          <!-- Q3 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_3_q', 'What paperboard substrates and grammages are available for Printed Cartons?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_3_a', 'We work with certified virgin Folding Box Board (FBB / GC1 and GC2), Solid Bleached Sulfate (SBS), and pharma-grade duplex boxboards ranging from 230 GSM to 450 GSM (thickness: 350 to 650 microns). All raw materials are sourced from FSC-certified and food/pharma-approved global mills.')) ?></p>
            </div>
          </details>

          <!-- Q4 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_4_q', 'How do you prevent product mix-ups on high-density Leaf-Inserts and miniature outserts?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_4_a', 'Every leaflet folding line is equipped with 100% inline optical Pharma-Code and 2D barcode vision inspection scanners. Any unverified or mismatched leaflet is instantly rejected by high-speed pneumatic gates, ensuring zero mix-ups throughout production.')) ?></p>
            </div>
          </details>

        </div>

        <!-- Right Column (Questions 5 to 8) -->
        <div class="faq-column">

          <!-- Q5 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_5_q', 'Are your packaging cartons and labels compatible with serialization (2D DataMatrix) requirements?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_5_a', 'Yes. Our printed cartons and labels are formulated with high-contrast, non-smear aqueous top-coatings optimized for continuous inkjet (CIJ), thermal transfer (TTO), and inline laser serialization coding. Furthermore, our tamper-evident carton designs comply with EN 16679:2014 and EU Directive 2011/62/EU.')) ?></p>
            </div>
          </details>

          <!-- Q6 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_6_q', 'Do you provide in-line Braille embossing on pharmaceutical cartons?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_6_a', 'Yes. We provide certified in-line Braille dot embossing conforming to the Marburg Medium standard (European Directive 2004/27/EC), with dot height and spacing verified via optical inspection gauges to guarantee legibility and disability compliance.')) ?></p>
            </div>
          </details>

          <!-- Q7 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_7_q', 'What are your standard production lead times and minimum order quantities (MOQ)?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_7_a', 'Standard production lead times typically range from 7 to 14 business days after final artwork sign-off and batch clearance. We offer flexible MOQ tiers accommodating pilot formulation batches, clinical trial runs, and high-volume commercial production runs.')) ?></p>
            </div>
          </details>

          <!-- Q8 -->
          <details class="faq-item">
            <summary class="faq-summary">
              <span class="faq-q-text"><?= h(get_content('products', 'faq_8_q', 'How can our QA or procurement team request physical samples or a formal technical quotation?')) ?></span>
              <span class="faq-icon"><i class="ri-add-line"></i></span>
            </summary>
            <div class="faq-answer">
              <p><?= h(get_content('products', 'faq_8_a', 'You can request physical sample kits, unprinted structural mock-ups, or a formal technical quotation directly through any product page on our website, or by contacting our Technical Sales Desk at +92 21-38893400-3 / sales@princeartpackages.com.')) ?></p>
            </div>
          </details>

        </div>
      </div>

      <!-- FAQ Bottom CTA -->
      <div style="text-align: center; margin-top: 3.5rem;">
        <p style="font-size: 1.05rem; color: var(--navy-dark); font-weight: 600; margin-bottom: 1rem;">
          Have specific packaging requirements or need customized dielines?
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; align-items: center;">
          <a href="contact.php" class="btn btn-gold btn-lg">
            <i class="ri-mail-send-line"></i> Contact Our Packaging Engineers &rarr;
          </a>
          <a href="contact.php?action=audit" class="btn btn-outline-navy btn-lg">
            <i class="ri-calendar-check-line"></i> Schedule a Facility Audit
          </a>
        </div>
      </div>

    </div>
  </section>

  <!-- Schema.org JSON-LD FAQPage for SEO -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": <?= json_encode(get_content('products', 'faq_1_q', 'What pharmaceutical secondary packaging products does Prince Art Packages manufacture?')) ?>,
        "acceptedAnswer": {
          "@type": "Answer",
          "text": <?= json_encode(get_content('products', 'faq_1_a', 'We manufacture specialized pharmaceutical secondary packaging.')) ?>
        }
      },
      {
        "@type": "Question",
        "name": <?= json_encode(get_content('products', 'faq_2_q', 'Can you customize carton dimensions, dielines, and board calipers for our specific packaging machines?')) ?>,
        "acceptedAnswer": {
          "@type": "Answer",
          "text": <?= json_encode(get_content('products', 'faq_2_a', 'Yes, our in-house team creates custom CAD dielines and mock-ups.')) ?>
        }
      },
      {
        "@type": "Question",
        "name": <?= json_encode(get_content('products', 'faq_3_q', 'What paperboard substrates and grammages are available for Printed Cartons?')) ?>,
        "acceptedAnswer": {
          "@type": "Answer",
          "text": <?= json_encode(get_content('products', 'faq_3_a', 'Virgin FBB (GC1/GC2), SBS, and pharma duplex boards ranging from 230 to 450 GSM.')) ?>
        }
      },
      {
        "@type": "Question",
        "name": <?= json_encode(get_content('products', 'faq_4_q', 'How do you prevent product mix-ups on high-density Leaf-Inserts and miniature outserts?')) ?>,
        "acceptedAnswer": {
          "@type": "Answer",
          "text": <?= json_encode(get_content('products', 'faq_4_a', 'Every leaflet folding line is equipped with 100% inline optical Pharma-Code scanners.')) ?>
        }
      }
    ]
  }
  </script>

<?php require __DIR__ . '/includes/footer.php'; ?>

