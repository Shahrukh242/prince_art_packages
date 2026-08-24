<?php
$pageSlug = 'quality';
require __DIR__ . '/includes/header.php';
?>
<div class="container section">
      <div class="section-header">
        <span class="section-subtitle">Regulatory Assurance</span>
        <h2>Quality Certifications & In-House Testing Laboratory</h2>
        <p>Verified international quality management certifications and cGMP audit compliance documentation.</p>
      </div>

      <!-- Certificate Badges Grid -->
      <div class="grid-2" style="margin-bottom: 3rem;">
        <!-- ISO 9001 Card -->
        <div class="cert-card">
          <div style="display:flex; align-items:center; gap:1rem;">
            <i class="ri-award-fill" style="font-size:3rem; color:var(--teal-brand);"></i>
            <div>
              <h3>ISO 9001:2015 Certification</h3>
              <p style="color:var(--text-muted); margin:0;">Quality Management System Certification</p>
            </div>
          </div>
          <div class="cert-meta">
            <p style="margin-bottom:0.25rem;"><strong>Certificate No:</strong> <code>KQ.2025.5393</code></p>
            <p style="margin-bottom:0.25rem;"><strong>Certification Body:</strong> ASCERT Certification and Training Services LLC</p>
            <p style="margin:0;"><strong>Body Accreditation:</strong> MSCB-223</p>
          </div>
          <p style="font-size:0.88rem;">Scope: Purchasing of paper materials, production and sales of pharmaceutical secondary packaging products.</p>
        </div>

        <!-- FSC Card -->
        <div class="cert-card" style="border-left-color: var(--gold-accent);">
          <div style="display:flex; align-items:center; gap:1rem;">
            <i class="ri-leaf-fill" style="font-size:3rem; color:var(--gold-accent);"></i>
            <div>
              <h3>FSC Chain of Custody Certification</h3>
              <p style="color:var(--text-muted); margin:0;">Responsible Forest Resource Certification</p>
            </div>
          </div>
          <div class="cert-meta">
            <p style="margin-bottom:0.25rem;"><strong>Certificate No:</strong> <code>RR-COC-003348</code></p>
            <p style="margin-bottom:0.25rem;"><strong>FSC License Code:</strong> <code>FSC-C222205</code></p>
            <p style="margin:0;"><strong>Standard:</strong> FSC-STD-40-004 (Validity: 19 Nov 2025 – 18 Nov 2030)</p>
          </div>
          <p style="font-size:0.88rem;">Scope: FSC 100%, FSC Mix, and FSC Recycled paper product manufacturing and supply.</p>
        </div>
      </div>

      <!-- In-House Laboratory Highlight from Brochure -->
      <div class="card card-body">
        <h3><i class="ri-flask-fill text-teal"></i> Our Quality Control Laboratory (OurLAB)</h3>
        <p>Our in-house laboratory is equipped with the latest pantone & spectrophotometer color controlling/measuring devices to develop exact color recipes, shades, ink-rub resistance testing, and moisture analysis according to client pharmaceutical requirements.</p>
      </div>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
