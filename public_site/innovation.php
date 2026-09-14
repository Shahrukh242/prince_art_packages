<?php
$pageSlug = 'innovation';
require __DIR__ . '/includes/header.php';
?>
<!-- ================================================================ -->
<!-- SECTION 01 — PAGE HERO                                           -->
<!-- ================================================================ -->
<section class="page-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden; text-align: center;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2; max-width: 860px; margin: 0 auto;">
    <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
      <i class="ri-lightbulb-line"></i> <?= h(get_content('innovation', 'hero_eyebrow', 'PROPRIETARY PACKAGING TECHNOLOGIES')) ?>
    </span>
    <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
      <?= h(get_content('innovation', 'hero_title', 'Advanced Packaging Innovations & Anti-Counterfeiting Security')) ?>
    </h1>
    <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin: 0 auto; max-width: 720px;">
      <?= h(get_content('innovation', 'hero_intro', 'Pioneering eco-friendly ColdSeal blister wallets and proprietary 3D-ENGRAVIX™ optical security structures for pharmaceutical patient safety and brand authentication.')) ?>
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; align-items: center; margin-top: 1.75rem; flex-wrap: wrap;">
      <?= render_cta_buttons('innovation', 'hero', '<a href="contact.php" class="btn btn-gold btn-lg"><i class="ri-shield-check-line"></i> Request Optical Security Samples</a><a href="contact.php" class="btn btn-outline-white btn-lg" style="color:#ffffff;border-color:rgba(255,255,255,0.4);background:rgba(255,255,255,0.08);"><i class="ri-file-list-3-line"></i> Request a Quote</a>') ?>
    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 02 — INNOVATION OVERVIEW (H2 & SUPPORTING CONTENT)       -->
<!-- ================================================================ -->
<section class="section" style="padding: 4.5rem 0;">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-fingerprint-line"></i> <?= h(get_content('innovation', 'sec_subtitle', 'INNOVATION & BRAND PROTECTION')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('innovation', 'sec_h2_title', 'Next-Generation Packaging Engineering for Patient Safety')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('innovation', 'sec_h2_intro', 'As pharmaceutical markets face rising counterfeit threats and increasing environmental sustainability requirements, Prince Art Packages invests heavily in proprietary packaging engineering. From heat-free ColdSeal Blister Wallets that preserve temperature-sensitive pharmaceuticals while reducing plastic usage by up to 50%, to our patented 3D-ENGRAVIX™ micro-structured optical security cartons that provide instant ambient-light authentication without requiring electronic scanners, our innovations deliver robust supply chain integrity.')) ?>
      </p>
    </div>

      <!-- ColdSeal Blister Wallet Feature -->
      <div class="card card-body" style="margin-bottom: 3rem; border-top: 5px solid var(--teal-brand);">
        <div class="grid-2" style="align-items:center;">
          <div>
            <span class="cert-pill"><?= h(get_content('innovation', 'coldseal_badge', 'PATENT-PENDING ECO INNOVATION')) ?></span>
            <h3 style="font-size:1.8rem; margin-top:0.5rem; color:var(--navy-dark);"><?= h(get_content('innovation', 'coldseal_title', 'ColdSeal Blister Wallet')) ?></h3>
            <div>
              <?= get_content('innovation', 'coldseal_desc', '<p>ColdSeal Blister Wallet is a pressure-sealed (non-heat-sealed) blister packaging system encapsulated between paperboard layers.</p><ul style="padding-left:1.25rem; color:var(--text-body); margin: 1rem 0;"><li><strong>50% Material Reduction:</strong> Reduces plastic and aluminum foil usage by up to 50% compared to traditional rigid blisters.</li><li><strong>Child-Resistant & Senior-Friendly:</strong> Engineered latch mechanisms compliant with safety standards.</li><li><strong>Dose Compliance Tracking:</strong> Integrated calendar layout for patient compliance adherence.</li><li><strong>Available Formats:</strong> Wallet format, Box format, and Card format.</li><li><strong>Applications:</strong> Tablets/capsules, pre-filled syringes/pens, ampoules/vials, droppers.</li></ul>') ?>
            </div>

            <?= render_cta_buttons('innovation', 'coldseal_cta', '<a href="contact.php" class="btn btn-gold btn-sm" data-product="ColdSeal Blister Wallet">Request ColdSeal Samples &rarr;</a>') ?>
          </div>
          <div>
            <?= render_image(get_content('innovation', 'coldseal_image', 'assets/images/coldseal.jpg'), 'ColdSeal Blister Packaging', '', ['style' => 'width:100%; border-radius:var(--radius-md);']) ?>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-top:0.75rem;">
              <div style="text-align:center;">
                <?= render_image('assets/images/prod_coldseal_3in1.jpg', 'ColdSeal 3-in-1 Individual Wallets', '', ['style' => 'width:100%; border-radius:6px; aspect-ratio:16/9; object-fit:cover; border:1px solid rgba(0,0,0,0.08);']) ?>
                <small style="color:var(--text-muted); font-size:0.75rem; font-weight:600; display:block; margin-top:0.25rem;">3-in-1 Dispenser Wallets</small>
              </div>
              <div style="text-align:center;">
                <?= render_image('assets/images/coldseal_roller.jpg', 'ColdSeal Jiggle Roller Hand Tool', '', ['style' => 'width:100%; border-radius:6px; aspect-ratio:16/9; object-fit:cover; border:1px solid rgba(0,0,0,0.08);']) ?>
                <small style="color:var(--text-muted); font-size:0.75rem; font-weight:600; display:block; margin-top:0.25rem;">Jiggle Roller Hand Sealer</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 3D-Engravix Feature -->
      <div class="card card-body" style="margin-bottom: 3rem; border-top: 5px solid var(--navy-dark);">
        <div class="grid-2" style="align-items:center;">
          <div>
            <?= render_image(get_content('innovation', 'engravix_image', 'assets/images/engravix.jpg'), '3D-Engravix Anti-Counterfeit Packaging', '', ['style' => 'width:100%; border-radius:var(--radius-md);']) ?>
            <div style="margin-top:0.75rem; text-align:center;">
              <?= render_image('assets/images/prod_eivita.jpg', 'Eivita 2 Blister Wallet with 3D Engravix', '', ['style' => 'width:100%; border-radius:6px; aspect-ratio:16/9; object-fit:cover; border:1px solid rgba(0,0,0,0.08);']) ?>
              <small style="color:var(--text-muted); font-size:0.75rem; font-weight:600; display:block; margin-top:0.25rem;">Eivita 2 Wallet with 3D-Engravix™ Optical Seal</small>
            </div>
          </div>
          <div>
            <span class="cert-pill" style="background:rgba(11, 37, 69, 0.15); color:var(--navy-dark);"><?= h(get_content('innovation', 'engravix_badge', 'OPTICAL ANTI-COUNTERFEIT SECURITY')) ?></span>
            <h3 style="font-size:1.8rem; margin-top:0.5rem; color:var(--navy-dark);"><?= h(get_content('innovation', 'engravix_title', '3D-Engravix™ Optical Technology')) ?></h3>
            <div>
              <?= get_content('innovation', 'engravix_desc', '<p>3D-Engravix™ is an advanced optical security feature integrated directly onto pharmaceutical printed cartons during manufacturing.</p><p><strong>Zero Equipment Needed:</strong> Authentication is 100% visual under ambient light. Pharmacists, distributors, healthcare professionals, and patients require NO scanner, smartphone app, or reader device.</p><h4 style="margin-top:1rem;">Authentication Principles:</h4><ol style="padding-left:1.25rem; color:var(--text-body); font-size:0.9rem;"><li><strong>Micro-Optic Motion:</strong> Spinning blade, flowing motion, and travelling light effects when tilted.</li><li><strong>Flip Effect:</strong> Seamless visual transformation between objects when package is tilted vertically.</li><li><strong>Seal-Base Color Shift:</strong> Angle-dependent color shift (e.g. violet-purple to emerald-green shift).</li></ol>') ?>
            </div>

            <?= render_cta_buttons('innovation', 'engravix_cta', '<a href="contact.php" class="btn btn-teal btn-sm" style="margin-top:1rem;" data-product="3D-Engravix Cartons">Request 3D-Engravix™ Demonstration &rarr;</a>') ?>
          </div>
        </div>
      </div>

      <!-- Security & Value Additions Grid -->
      <div class="section-header" style="margin-bottom: 2rem;">
        <h3><?= h(get_content('innovation', 'suite_title', 'Security & Value Additions Suite')) ?></h3>
        <p><?= h(get_content('innovation', 'suite_subtitle', 'In-house specialized security printing and finishing techniques')) ?></p>
      </div>

      <div class="grid-3">
        <div class="card card-body">
          <i class="ri-eye-line text-teal" style="font-size:2rem;"></i>
          <h4>Color Changing Seal (CCS)</h4>
          <p>Angle-dependent optical color shift seals for visual tamper verification.</p>
        </div>
        <div class="card card-body">
          <i class="ri-coin-line text-teal" style="font-size:2rem;"></i>
          <h4>Invisible Coin Evident (ICE)</h4>
          <p>Invisible security text revealed when rubbed with a metal coin edge.</p>
        </div>
        <div class="card card-body">
          <i class="ri-cpu-line text-teal" style="font-size:2rem;"></i>
          <h4>Conductive Printing</h4>
          <p>Printed conductive circuit tracks for electronic anti-tamper sensing.</p>
        </div>
        <div class="card card-body">
          <i class="ri-temp-hot-line text-teal" style="font-size:2rem;"></i>
          <h4>Thermochromic Printing</h4>
          <p>Temperature-sensitive inks that change color upon touch or cold-chain exposure.</p>
        </div>
        <div class="card card-body">
          <i class="ri-shapes-line text-teal" style="font-size:2rem;"></i>
          <h4>Multi-Dimensional Embossing</h4>
          <p>High-precision multi-level tactile embossing and Braille lettering.</p>
        </div>
        <div class="card card-body">
          <i class="ri-sun-line text-teal" style="font-size:2rem;"></i>
          <h4>Multi-Level UV Coating</h4>
          <p>Full glossy UV, matte UV, textured UV, and spot security varnishes.</p>
        </div>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
