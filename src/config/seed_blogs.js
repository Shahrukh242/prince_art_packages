const db = require('./db');

async function seedBlogs() {
  console.log('[Seed] Inserting realistic pharmaceutical packaging blog articles...');

  const posts = [
    {
      title: 'ISO 9001:2015 & cGMP Compliance in Pharmaceutical Secondary Packaging',
      slug: 'iso-9001-cgmp-compliance-pharma-packaging',
      excerpt: 'How strict Quality Assurance protocols, electronic line clearances, and ISO 9001:2015 standards safeguard medicine cartons against mix-ups and contamination.',
      content: `<p>In pharmaceutical secondary packaging, quality assurance is not merely a preference—it is a critical imperative that directly impacts patient safety. At Prince Art Packages (Private) Limited, our manufacturing facilities operate under rigorous ISO 9001:2015 certified Quality Management Systems (Certificate No. KQ.2025.5393).</p>
<h3>Key Quality Protocols in Production</h3>
<ul>
  <li><strong>Electronic Line Clearances:</strong> Automated sensors verify that zero residual cartons or leaflets from prior runs remain on folding-gluing lines.</li>
  <li><strong>Vision Inspection Systems:</strong> 100% inline barcode scanning and optical character recognition (OCR) verify batch numbers and expiration dates in real time.</li>
  <li><strong>Substrate Traceability:</strong> Complete batch tracking from raw paperboard delivery to finished carton dispatch.</li>
</ul>
<p>By integrating cGMP principles into sheetfed offset printing and die-cutting, we provide pharmaceutical manufacturers with total packaging integrity.</p>`,
      featured_image: 'images/prod_cartons.jpg',
      published_at: '2026-02-15 10:00:00',
      status: 'published'
    },
    {
      title: 'Anti-Counterfeiting Innovations: From Tamper-Evident Seals to 3D-ENGRAVIX',
      slug: 'anti-counterfeiting-tamper-evident-3d-engravix',
      excerpt: 'Exploring modern packaging security technologies designed to combat counterfeit medicines, including high-relief embossing, micro-text, and cold-seal tamper alerts.',
      content: `<p>Counterfeit pharmaceuticals pose a growing global threat to public health. Secondary packaging serves as the first line of defense in verifying product authenticity and brand integrity.</p>
<h3>Proprietary Anti-Counterfeiting Technologies</h3>
<p>Prince Art Packages combines structural design and security printing to prevent illicit duplication:</p>
<ul>
  <li><strong>3D-ENGRAVIX High-Relief Embossing:</strong> Multi-layered tactile micro-grooves that cannot be replicated using standard commercial embossing dies.</li>
  <li><strong>Tamper-Evident Cartons & Labels:</strong> Fiber-tear adhesive structures that provide irreversible visual evidence upon opening (EN 16679 compliance).</li>
  <li><strong>Micro-Text & Overt Security Features:</strong> Ultra-fine guilloche patterns and latent image printing embedded directly into folding carton artwork.</li>
</ul>
<p>Implementing multi-tiered anti-counterfeiting features protects both patient well-being and brand reputation.</p>`,
      featured_image: 'images/engravix.jpg',
      published_at: '2026-02-10 14:30:00',
      status: 'published'
    },
    {
      title: 'FSC Certified Sustainable Paperboard for Modern Pharma Packaging',
      slug: 'fsc-certified-sustainable-paperboard-pharma-packaging',
      excerpt: 'Adopting FSC Chain of Custody standards to deliver eco-responsible folding cartons and leaflets without compromising structural durability or barrier protection.',
      content: `<p>Sustainability in pharmaceutical packaging requires balancing environmental responsibility with strict barrier protection and shelf-life stability. Prince Art Packages holds FSC Chain of Custody Certification (License Code FSC-C222205, Certificate RR-COC-003348).</p>
<h3>Benefits of FSC Chain of Custody Certification</h3>
<p>Our commitment to responsible forestry ensures:</p>
<ul>
  <li><strong>100% Responsible Sourcing:</strong> All paperboard and paper materials originate from responsibly managed, renewable forests.</li>
  <li><strong>Recyclability & Biodegradability:</strong> Aqueous water-based coatings and recyclable substrates replace heavy plastic laminates.</li>
  <li><strong>Supply Chain Auditability:</strong> Full chain of custody tracking from pulp supplier to delivered carton.</li>
</ul>
<p>Transitioning to FSC certified packaging allows pharmaceutical brands to reduce carbon footprints while maintaining total regulatory compliance.</p>`,
      featured_image: 'images/facility.jpg',
      published_at: '2026-01-28 09:15:00',
      status: 'published'
    },
    {
      title: 'Cold-Seal Blister Wallets: Enhancing Medication Adherence & Child Safety',
      slug: 'cold-seal-blister-wallets-medication-adherence',
      excerpt: 'How heat-free cold-seal wallet packaging protects heat-sensitive solid oral doses while improving patient dosage compliance and child resistance.',
      content: `<p>Solid oral dose packaging continues to evolve beyond traditional blister packs into integrated cold-seal blister wallets. Cold-Seal Wallets use specialized pressure-sensitive cohesive coatings that seal securely without applying heat during packaging.</p>
<h3>Advantages of Cold-Seal Wallet Architecture</h3>
<ul>
  <li><strong>Thermal Protection:</strong> Zero heat exposure during sealing preserves sensitive active pharmaceutical ingredients (APIs).</li>
  <li><strong>Patient Compliance Calendars:</strong> Clear printed day/time matrices help patients stick to complex daily dosage regimens.</li>
  <li><strong>Child-Resistant & Senior-Friendly:</strong> Engineered tear resistance prevents accidental child access while remaining easily accessible for elderly patients.</li>
</ul>
<p>Cold-Seal packaging represents a versatile, high-integrity solution for clinical trials and commercial pharmaceutical distribution.</p>`,
      featured_image: 'images/coldseal.jpg',
      published_at: '2026-01-14 11:00:00',
      status: 'published'
    }
  ];

  try {
    for (const post of posts) {
      const existing = await db.query('SELECT id FROM blog_posts WHERE slug = ?', [post.slug]);
      if (existing && existing.length === 0) {
        await db.query(
          `INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, published_at, status) 
           VALUES (?, ?, ?, ?, ?, ?, ?)`,
          [post.title, post.slug, post.excerpt, post.content, post.featured_image, post.published_at, post.status]
        );
        console.log(`   ✓ Seeded article: "${post.title}"`);
      } else {
        console.log(`   - Article already exists: "${post.title}"`);
      }
    }
    console.log('[Seed] Blog seeding complete!');
  } catch (err) {
    console.error('[Seed Error] Failed to seed blogs:', err.message);
  }
}

if (require.main === module) {
  seedBlogs().then(() => process.exit(0));
}

module.exports = seedBlogs;
