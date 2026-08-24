<?php
$pageSlug = 'home';
require __DIR__ . '/includes/header.php';
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
          <p class="hero-subtitle"><?= h(get_content('home', 'hero_subtitle', 'ISO 9001:2015 certified manufacturer of folding cartons, leaf-inserts, printed labels, ColdSeal blister wallets, and 3D-Engravix™ optical anti-counterfeit security packaging based in Korangi Creek Industrial Park, Karachi.')) ?></p>

          <div class="hero-cta">
            <a href="contact.php" class="btn btn-gold" data-action="quote"><i class="ri-file-list-3-line"></i> Request a Formal Quote</a>
            <a href="innovation.php" class="btn btn-teal"><i class="ri-shield-keyhole-line"></i> Explore 3D-Engravix™ & ColdSeal</a>
          </div>
        </div>

        <div class="hero-image-box">
          <img src="assets/images/engravix.jpg" alt="3D-Engravix Anti-Counterfeit Packaging">
        </div>
      </div>
    </section>

    <!-- Trust Bar -->
    <section class="trust-bar">
      <div class="container trust-flex">
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-award-line"></i></div>
          <div class="trust-text">
            <h4>ISO 9001:2015 Certified</h4>
            <p>Cert No. KQ.2025.5393 (ASCERT)</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-leaf-line"></i></div>
          <div class="trust-text">
            <h4>FSC Chain of Custody</h4>
            <p>Cert No. RR-COC-003348 (FSC-C222205)</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-shield-star-line"></i></div>
          <div class="trust-text">
            <h4>cGMP Compliant</h4>
            <p>WHO-GMP Manufacturing Standards</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-icon"><i class="ri-building-2-line"></i></div>
          <div class="trust-text">
            <h4>Unit 1 & Unit 2 Plants</h4>
            <p>Korangi Creek Industrial Park</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Capability Strip -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Manufacturing Capabilities</span>
          <h2>Comprehensive Pharmaceutical Packaging Solutions</h2>
          <p><?= h(get_content('home', 'capability_intro', 'Purpose-engineered paperboard packaging, precision inserts, self-adhesive roll labels, and proprietary security features.')) ?></p>
        </div>

        <div class="grid-3">
          <div class="card">
            <img src="assets/images/cartons.jpg" class="card-img-top" alt="Folding Cartons">
            <div class="card-body">
              <h3 class="card-title">Folding Cartons</h3>
              <p>Reverse tuck, crash lock, tamper-evident, and child-resistant cartons printed on food & pharma-grade virgin board.</p>
              <a href="products.php" class="btn btn-outline-navy btn-sm">Specifications &rarr;</a>
            </div>
          </div>

          <div class="card">
            <img src="assets/images/leaflets.jpg" class="card-img-top" alt="Leaf-Inserts">
            <div class="card-body">
              <h3 class="card-title">Leaf-Inserts</h3>
              <p>Ultra-thin 27gsm to 60gsm prescribing information inserts, cross-folded or miniature outserts for automated packaging lines.</p>
              <a href="products.php" class="btn btn-outline-navy btn-sm">Specifications &rarr;</a>
            </div>
          </div>

          <div class="card">
            <img src="assets/images/labels.jpg" class="card-img-top" alt="Printed Labels">
            <div class="card-body">
              <h3 class="card-title">Printed Labels & Tamper-Evident</h3>
              <p>Self-adhesive roll labels for vials, bottles, ampoules, and destructible tamper-evident security seals with 2D barcode serialization.</p>
              <a href="products.php" class="btn btn-outline-navy btn-sm">Specifications &rarr;</a>
            </div>
          </div>
        </div>

        <!-- Featured Innovation Callout Banner -->
        <div class="innovation-banner" style="margin-top: 3rem;">
          <div class="grid-2" style="align-items:center;">
            <div>
              <span class="cert-pill" style="background:rgba(255,255,255,0.2); color:#fff;">FEATURED INNOVATIONS</span>
              <h2 style="font-size:2.2rem; margin-top:0.5rem;">ColdSeal Blister Wallet & 3D-Engravix™ Security</h2>
              <p>Discover our proprietary anti-counterfeit optical security technology (3D-Engravix™) and pressure-sealed eco packaging (ColdSeal Blister Wallet) that reduces plastic/foil footprint by up to 50%.</p>
              <a href="innovation.php" class="btn btn-gold"><i class="ri-lightbulb-line"></i> Learn About Innovation & Technology</a>
            </div>
            <div>
              <img src="assets/images/coldseal.jpg" style="width:100%; border-radius:var(--radius-md); border:3px solid rgba(255,255,255,0.3);" alt="ColdSeal Blister Packaging">
            </div>
          </div>
        </div>
      </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
