<?php
$pageSlug = 'industries';
require __DIR__ . '/includes/header.php';
?>
<!-- ================================================================ -->
<!-- SECTION 01 — PAGE HERO                                           -->
<!-- ================================================================ -->
<section class="page-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden; text-align: center;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2; max-width: 900px; margin: 0 auto;">
    <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
      <i class="ri-shield-check-line"></i> <?= h(get_content('industries', 'hero_eyebrow', 'CLIENTS & REGULATED INDUSTRIES')) ?>
    </span>
    <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
      <?= h(get_content('industries', 'hero_title', 'Trusted by Leading Healthcare Brands & Regulated Industries')) ?>
    </h1>
    <p style="font-size: 1.15rem; line-height: 1.7; color: rgba(255,255,255,0.9); margin: 0 auto; max-width: 760px;">
      <?= h(get_content('industries', 'hero_intro', 'Delivering precision-engineered, cGMP-compliant folding cartons, patient information leaflets, and security packaging to premier multinational and domestic pharmaceutical manufacturers.')) ?>
    </p>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 02 — SATISFIED CLIENTS SHOWCASE                          -->
<!-- ================================================================ -->
<section class="section" style="padding: 4.5rem 0 3.5rem 0; background-color: #f8fafc; border-bottom: 1px solid var(--border-color);">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 820px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-award-line"></i> <?= h(get_content('industries', 'clients_subtitle', 'OUR VALUED PARTNERSHIPS')) ?>
      </span>
      <h2 style="font-size: 2.4rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1rem;">
        <?= h(get_content('industries', 'clients_heading', 'Some of Our Satisfied Clients')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.7; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('industries', 'clients_intro', 'Prince Art Packages has established long-term manufacturing partnerships with Pakistan’s most prestigious pharmaceutical leaders and healthcare conglomerates, consistently fulfilling strict QA standards and high-volume demand.')) ?>
      </p>
    </div>

    <!-- Client Logo Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
      
      <!-- Client 1: Getz Pharma -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.55rem; font-weight: 900; color: #1e3a8a; letter-spacing: -0.02em;">Getz <span style="color: #0284c7; font-weight: 700;">pharma</span></span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Commercial Pharma</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Cartons &amp; Security Inserts</div>
      </div>

      <!-- Client 2: GSK -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.85rem; font-weight: 900; color: #f97316; letter-spacing: -0.01em;">gsk</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Multinational Healthcare</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Leaflets &amp; Precision Folding</div>
      </div>

      <!-- Client 3: Abbott -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.65rem; font-weight: 900; color: #0284c7; letter-spacing: 0.02em;">Abbott</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Global Healthcare</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Blister Cartons &amp; Catch Covers</div>
      </div>

      <!-- Client 4: The Searle Company -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.6rem; font-weight: 900; color: #047857; letter-spacing: 0.05em;">SEARLE</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Leading Domestic Pharma</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Secondary Cartoning &amp; PILs</div>
      </div>

      <!-- Client 5: Martin Dow -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.45rem; font-weight: 800; color: #1e293b; letter-spacing: 0.02em;">MARTIN DOW</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Healthcare Group</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">High-Speed Line Packaging</div>
      </div>

      <!-- Client 6: Sami Pharmaceuticals -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.5rem; font-weight: 900; color: #b91c1c; letter-spacing: 0.03em;">SAMI</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Pharmaceuticals</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Dispensing Cartons &amp; Inserts</div>
      </div>

      <!-- Client 7: Hilton Pharma -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.45rem; font-weight: 800; color: #0369a1; letter-spacing: 0.04em;">hilton <span style="font-weight:400; color:#475569;">pharma</span></span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Commercial Therapeutics</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Solid Oral Cartons &amp; Sleeves</div>
      </div>

      <!-- Client 8: Sanofi -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.65rem; font-weight: 900; color: #4338ca; letter-spacing: -0.02em;">sanofi</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Global Healthcare</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">cGMP Certified Converting</div>
      </div>

      <!-- Client 9: CCL Pharmaceuticals -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.6rem; font-weight: 900; color: #0d9488; letter-spacing: 0.06em;">CCL</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">International Pharma</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Prescription Formulation Packs</div>
      </div>

      <!-- Client 10: Ferozsons Laboratories -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.45rem; font-weight: 800; color: #0f172a; letter-spacing: 0.03em;">FEROZSONS</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Laboratories Ltd</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Specialty &amp; Biotech Packaging</div>
      </div>

      <!-- Client 11: Bosch Pharmaceuticals -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.55rem; font-weight: 900; color: #dc2626; letter-spacing: 0.02em;">BOSCH</span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Pharmaceuticals</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Sterile &amp; Non-Sterile Cartoning</div>
      </div>

      <!-- Client 12: PharmEvo -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.75rem 1.5rem; text-align: center; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        <div style="display: flex; align-items: center; justify-content: center; height: 60px; margin-bottom: 1rem;">
          <span style="font-size: 1.55rem; font-weight: 900; color: #059669; letter-spacing: -0.01em;">Pharm<span style="color:#d97706;">Evo</span></span>
        </div>
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--teal-primary); text-transform: uppercase; letter-spacing: 0.05em;">Research &amp; Therapeutics</div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">Braille &amp; DataMatrix Packaging</div>
      </div>

    </div>

    <!-- Trust Endorsement -->
    <div style="background: rgba(0,168,150,0.06); border: 1px dashed rgba(0,168,150,0.35); border-radius: 12px; padding: 1.25rem 2rem; text-align: center; max-width: 860px; margin: 0 auto;">
      <p style="margin: 0; font-size: 0.95rem; color: var(--navy-dark); font-weight: 600;">
        <i class="ri-checkbox-circle-fill text-teal" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.35rem;"></i>
        All client orders are produced under strict confidentiality, non-disclosure compliance, and dual automated electronic QA inspection.
      </p>
    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 03 — TARGET SECTORS & FORMULATION TYPES                  -->
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
      <div class="card card-body" style="padding: 2.25rem 2rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <i class="ri-capsule-fill text-teal" style="font-size:2.5rem; margin-bottom: 1rem; display: inline-block;"></i>
        <h3 style="font-size: 1.35rem; color: var(--navy-dark); margin-bottom: 0.75rem; font-weight: 700;"><?= h(get_content('industries', 'ind1_title', 'Commercial Pharmaceuticals')) ?></h3>
        <p style="font-size: 0.95rem; line-height: 1.65; color: var(--text-body); margin: 0;"><?= h(get_content('industries', 'ind1_desc', 'Prescription solid orals, liquids, parenterals, and topical formulations requiring strict cGMP compliance, high-speed cartoning line stability, and verified barcode legibility.')) ?></p>
      </div>
      <div class="card card-body" style="padding: 2.25rem 2rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <i class="ri-heart-pulse-fill text-teal" style="font-size:2.5rem; margin-bottom: 1rem; display: inline-block;"></i>
        <h3 style="font-size: 1.35rem; color: var(--navy-dark); margin-bottom: 0.75rem; font-weight: 700;"><?= h(get_content('industries', 'ind2_title', 'Healthcare & OTC Products')) ?></h3>
        <p style="font-size: 0.95rem; line-height: 1.65; color: var(--text-body); margin: 0;"><?= h(get_content('industries', 'ind2_desc', 'Consumer healthcare products, vitamin supplements, and over-the-counter medicine boxes designed for shelf-impact, structural sturdiness, and tamper-evident security.')) ?></p>
      </div>
      <div class="card card-body" style="padding: 2.25rem 2rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <i class="ri-syringe-fill text-teal" style="font-size:2.5rem; margin-bottom: 1rem; display: inline-block;"></i>
        <h3 style="font-size: 1.35rem; color: var(--navy-dark); margin-bottom: 0.75rem; font-weight: 700;"><?= h(get_content('industries', 'ind3_title', 'Biologics & Medical Devices')) ?></h3>
        <p style="font-size: 0.95rem; line-height: 1.65; color: var(--text-body); margin: 0;"><?= h(get_content('industries', 'ind3_desc', 'Cold-chain insulated partitions, sterile syringe packaging cards, vial crash-bottom cartons, and moisture-resistant barrier coatings engineered for critical stability.')) ?></p>
      </div>
    </div>

    <div class="innovation-banner" style="margin-top: 3.5rem; text-align: center; background: linear-gradient(135deg, #0d2240 0%, #153b68 100%); border-radius: 16px; padding: 3rem 2rem; color: #ffffff;">
      <h2 class="innovation-banner-heading" style="color: #ffffff; font-size: 2rem; font-weight: 800; margin-bottom: 0.75rem;"><?= h(get_content('industries', 'banner_title', "Don't See Your Industry Listed?")) ?></h2>
      <p class="innovation-banner-desc" style="color: rgba(255,255,255,0.85); font-size: 1.05rem; max-width: 680px; margin: 0 auto 1.75rem auto;"><?= h(get_content('industries', 'banner_desc', 'We work with a wide range of regulated manufacturers — talk to our technical packaging consultants about your specific sector specifications.')) ?></p>
      <?= render_cta_buttons('industries', 'industries_banner', '<a href="contact.php" class="btn btn-gold innovation-banner-btn"><i class="ri-question-answer-line"></i> How to Contact us</a>') ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

