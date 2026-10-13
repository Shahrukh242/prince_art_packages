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
      <?= h(get_content('innovation', 'hero_intro', 'Pioneering eco-friendly ColdSeal blister wallets, sliding pack geometries, electronic QA verification, and proprietary 3D-ENGRAVIX™ optical security structures for pharmaceutical patient safety and brand authentication.')) ?>
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

      <!-- Sliding Pack Feature -->
      <div id="sliding-pack" class="card card-body" style="margin-bottom: 3rem; border-top: 5px solid var(--gold-accent);">
        <div class="grid-2" style="align-items:center;">
          <div>
            <span class="cert-pill" style="background:rgba(212, 175, 55, 0.15); color:var(--navy-dark);"><?= h(get_content('innovation', 'sliding_badge', 'PATIENT-CENTRIC PACKAGING INNOVATION')) ?></span>
            <h3 style="font-size:1.8rem; margin-top:0.5rem; color:var(--navy-dark);"><?= h(get_content('innovation', 'sliding_title', 'Sliding Pack')) ?></h3>
            <div>
              <?= get_content('innovation', 'sliding_desc', '<p>The <strong>Sliding Pack</strong> is an engineered secondary packaging system combining a rigid outer paperboard sleeve with an integrated smooth-glide inner slide drawer or tray. Developed for patient convenience, dose adherence, and brand distinction, it offers a sustainable paperboard alternative to rigid plastic clamshells.</p><ul style="padding-left:1.25rem; color:var(--text-body); margin: 1rem 0;"><li><strong>Child-Resistant &amp; Senior-Friendly:</strong> Engineered mechanical locking tabs and tactile friction stops prevent accidental slide-out while ensuring easy senior access.</li><li><strong>Unit-Dose &amp; Device Protection:</strong> Custom die-cut retention cavities securely hold solid oral blister cards, pre-filled syringes (PFS), diagnostic kits, and vials.</li><li><strong>Integrated Dose Compliance:</strong> Expansive multi-panel printable surfaces accommodate daily or weekly calendarized dosing matrices and clinical instructions.</li><li><strong>Tamper-Evident Security:</strong> Frangible fiber-tear tabs provide unmistakable visual evidence upon first opening.</li><li><strong>Eco-Friendly Paperboard:</strong> 100% recyclable virgin folding boxboard (FBB / SBS) reducing plastic packaging footprint by up to 60%.</li></ul>') ?>
            </div>

            <?= render_cta_buttons('innovation', 'sliding_cta', '<a href="contact.php?product=Sliding%20Pack#enquiry-form" class="btn btn-gold btn-sm" data-product="Sliding Pack"><i class="ri-mail-send-line"></i> Request Sliding Pack Samples &rarr;</a>') ?>
          </div>
          <div>
            <?= render_image(get_content('innovation', 'sliding_image', 'assets/images/prod_pill_folders.jpg'), 'Sliding Pack Secondary Packaging', '', ['style' => 'width:100%; border-radius:var(--radius-md); box-shadow:var(--shadow-sm);']) ?>
            <div style="margin-top:0.75rem; text-align:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:0.65rem;">
              <small style="color:var(--text-muted); font-size:0.78rem; font-weight:600; display:block;">
                <i class="ri-checkbox-circle-fill text-teal"></i> Precision Sleeve &amp; Drawer Slide Mechanism for High-Value Pharmaceuticals
              </small>
            </div>
          </div>
        </div>
      </div>

      <!-- Electronic Verification Quality Assurance (QA) System Feature -->
      <div id="electronic-verification-qa" class="card card-body" style="margin-bottom: 3rem; border-top: 5px solid var(--teal-primary); background: #ffffff;">
        <div class="grid-2" style="align-items:center;">
          <div>
            <span class="cert-pill" style="background:rgba(0, 168, 150, 0.15); color:var(--teal-primary);"><?= h(get_content('innovation', 'qa_system_badge', 'AUTOMATED DIGITAL PROOFING &amp; INSPECTION')) ?></span>
            <h3 style="font-size:1.8rem; margin-top:0.5rem; color:var(--navy-dark);"><?= h(get_content('innovation', 'qa_system_title', 'Electronic Verification Quality Assurance (QA) System')) ?></h3>
            <div>
              <?= get_content('innovation', 'qa_system_desc', '<p style="font-size:1.02rem; line-height:1.7; color:var(--navy-dark); font-weight:600; background:rgba(0,168,150,0.06); border-left:4px solid var(--teal-brand); padding:0.85rem 1rem; border-radius:0 6px 6px 0; margin-bottom:1rem;">Prince Art Packages (Pvt) Ltd achieved state-of-the-art Electronic Verification Quality Assurance (QA) System that automated print and packaging inspection solution designed to compare printed samples against client approved electronic master files (PDFs). It enables our clients to reduces stress and testing for our supplied materials.</p><ul style="padding-left:1.25rem; color:var(--text-body); margin: 1rem 0;"><li><strong>Master PDF Comparator:</strong> Automatically cross-references scanned press sheets against approved electronic master files (PDFs) pixel-by-pixel to catch discrepancies before delivery.</li><li><strong>Micro-Text &amp; Regulatory Copy Verification:</strong> Detects missing or broken characters, punctuation shifts, and micro-text variations down to 30 microns.</li><li><strong>Barcode &amp; 2D DataMatrix Grading:</strong> Automated decoding and verification of linear barcodes, GS1 DataMatrix, and Pharmacodes to ensure 100% readability on automated packaging lines.</li><li><strong>Braille Accuracy Verification:</strong> Measures Marburg-standard Braille dot height, spacing, and translation accuracy in full compliance with European Directive 2004/27/EC.</li><li><strong>Zero-Defect Client Assurance:</strong> Eliminates human proofreading fatigue, minimizes client incoming quarantine inspection time, and ensures 100% audit-ready packaging lots.</li></ul>') ?>
            </div>

            <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap; margin-top:1.25rem;">
              <button type="button" onclick="openVideoPopup('https://www.youtube-nocookie.com/embed/4S9d2kz_DNY', 'GlobalVision | Print Inspection Software Overview')" class="btn btn-gold btn-sm" style="display:inline-flex; align-items:center; gap:0.4rem; font-weight:700; cursor:pointer; box-shadow:0 2px 8px rgba(212,175,55,0.3);">
                <i class="ri-play-circle-fill" style="color:var(--navy-dark); font-size:1.15rem;"></i> Watch Inspection Demonstration Video
              </button>
              <a href="contact.php?action=audit#enquiry-form" class="btn btn-teal btn-sm" style="display:inline-flex; align-items:center; gap:0.35rem;">
                <i class="ri-shield-check-line"></i> Inquire About QA Protocols &rarr;
              </a>
            </div>
          </div>

          <div>
            <!-- Video / Visual Demonstration Showcase (Click to Open Popup) -->
            <div onclick="openVideoPopup('https://www.youtube-nocookie.com/embed/4S9d2kz_DNY', 'GlobalVision | Print Inspection Software Overview')" style="background: #0f172a; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" title="Click to Watch Video Demonstration">
              <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; overflow: hidden;">
                <img src="https://i.ytimg.com/vi/4S9d2kz_DNY/hqdefault.jpg" alt="GlobalVision Print Inspection Software Overview" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9; transition: transform 0.3s ease;">
                <!-- Dark gradient overlay -->
                <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.6) 100%);"></div>
                <!-- Play Button Overlay -->
                <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;">
                  <div style="width: 60px; height: 60px; border-radius: 50%; background: #ef4444; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.85rem; box-shadow: 0 8px 24px rgba(239,68,68,0.5);">
                    <i class="ri-play-fill" style="margin-left: 3px;"></i>
                  </div>
                  <span style="background: rgba(0,0,0,0.75); color: #ffffff; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 20px; letter-spacing: 0.04em; text-transform: uppercase;">
                    Click to Play Demonstration
                  </span>
                </div>
              </div>
              <div style="padding: 1rem; background: #ffffff; border-top: 1px solid #f1f5f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                  <div>
                    <span style="font-size: 0.72rem; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 0.3rem;">
                      <i class="ri-play-circle-fill"></i> Video Demonstration
                    </span>
                    <h5 style="margin: 0.2rem 0 0 0; font-size: 0.95rem; font-weight: 700; color: var(--navy-dark);">
                      Automated Print &amp; PDF Master Inspection
                    </h5>
                  </div>
                  <span style="font-size: 0.8rem; color: var(--teal-primary); font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                    Watch Video <i class="ri-arrow-right-line"></i>
                  </span>
                </div>
              </div>
            </div>
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

<!-- VIDEO POPUP MODAL -->
<div id="videoPopupModal" style="display: none; position: fixed; inset: 0; background: rgba(10,37,64,0.88); z-index: 100000; backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 1.5rem;">
  <div style="max-width: 860px; width: 100%; background: #0b1320; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.6); position: relative; animation: modalFadeIn 0.25s ease-out; border: 1px solid rgba(255,255,255,0.12);">
    <div style="background: rgba(255,255,255,0.06); color: #ffffff; padding: 0.9rem 1.25rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1);">
      <h4 id="videoModalTitle" style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ri-youtube-fill" style="color: #ef4444; font-size: 1.4rem;"></i>
        <span>GlobalVision | Print Inspection Software Overview</span>
      </h4>
      <button type="button" onclick="closeVideoPopup()" aria-label="Close" style="background: rgba(255,255,255,0.15); border: none; color: #fff; width: 34px; height: 34px; border-radius: 50%; font-size: 1.25rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;">
        <i class="ri-close-line"></i>
      </button>
    </div>
    <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000;">
      <iframe id="videoModalIframe" src="" style="position: absolute; inset: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
    <div style="padding: 0.85rem 1.25rem; background: rgba(255,255,255,0.04); display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.08); font-size: 0.82rem; flex-wrap: wrap; gap: 0.5rem;">
      <span style="color: rgba(255,255,255,0.7);"><i class="ri-shield-check-line text-teal"></i> Electronic Verification Quality Assurance (QA) System</span>
      <a href="https://www.youtube.com/watch?v=4S9d2kz_DNY" target="_blank" rel="noopener noreferrer" style="color: var(--teal-brand); text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem; font-weight: 600;">
        Watch on YouTube <i class="ri-external-link-line"></i>
      </a>
    </div>
  </div>
</div>

<script>
function openVideoPopup(embedUrl, title) {
  var modal = document.getElementById('videoPopupModal');
  var iframe = document.getElementById('videoModalIframe');
  var titleEl = document.getElementById('videoModalTitle');
  if (modal && iframe) {
    if (titleEl && title) {
      titleEl.innerHTML = '<i class="ri-youtube-fill" style="color:#ef4444; font-size:1.4rem;"></i> <span>' + title + '</span>';
    }
    var url = embedUrl || 'https://www.youtube-nocookie.com/embed/4S9d2kz_DNY';
    var sep = (url.indexOf('?') > -1) ? '&' : '?';
    iframe.src = url + sep + 'autoplay=1&rel=0';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
}

function closeVideoPopup() {
  var modal = document.getElementById('videoPopupModal');
  var iframe = document.getElementById('videoModalIframe');
  if (modal) {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
  if (iframe) {
    iframe.src = '';
  }
}

document.addEventListener('DOMContentLoaded', function() {
  var videoModal = document.getElementById('videoPopupModal');
  if (videoModal) {
    videoModal.addEventListener('click', function(e) {
      if (e.target === videoModal) {
        closeVideoPopup();
      }
    });
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      if (videoModal && videoModal.style.display === 'flex') {
        closeVideoPopup();
      }
    }
  });
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
