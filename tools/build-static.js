#!/usr/bin/env node
/**
 * Prince Art Packages — Static Site Generator (Option B deploy prep)
 *
 * Problem it solves (Blocker #1): the marketing site is a single index.html
 * where every "page" is a JS-toggled <section> addressed by a #hash. Google
 * sees one URL, so products/articles can't rank individually and the sitemap
 * only lists # links that crawlers ignore.
 *
 * This generator splits index.html into real, separately-indexable HTML pages
 * with unique <title>/meta/canonical and real internal links. Output goes to
 * dist/ and is uploaded to StackCP. The CMS/admin stays on a separate Node host.
 *
 * Run: node tools/build-static.js
 */
const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const PUBLIC = path.join(ROOT, 'public');
const DIST = path.join(ROOT, 'dist');
const SITE = 'https://www.princeartpackages.com';

// Reusable navigation + footer (kept identical across pages for consistency)
const NAV = `
  <header class="main-header">
    <div class="container header-inner">
      <a href="/" class="brand-logo"><img src="images/logo.png" alt="Prince Art Packages (Private) Limited" class="brand-logo-img"></a>
      <nav>
        <ul class="nav-links">
          <li><a href="/">Home</a></li>
          <li><a href="/about.html">About</a></li>
          <li><a href="/products.html">Products</a></li>
          <li><a href="/capabilities.html">Capabilities</a></li>
          <li><a href="/innovation.html">Innovation</a></li>
          <li><a href="/quality.html">Quality</a></li>
          <li><a href="/industries.html">Industries</a></li>
          <li><a href="/sustainability.html">Sustainability</a></li>
          <li><a href="/blog.html">Blog</a></li>
          <li><a href="/contact.html">Contact</a></li>
        </ul>
      </nav>
      <div class="header-actions">
        <a href="/contact.html" class="btn btn-gold btn-sm header-cta-btn">Request a Quote</a>
        <button id="btn-mobile-nav" class="mobile-toggle-btn" aria-label="Toggle Menu"><i class="ri-menu-3-line"></i></button>
      </div>
    </div>
  </header>`;

const FOOTER = `
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div>
          <img src="images/logo.png" alt="Prince Art Packages (Private) Limited" style="height:42px; background:#fff; padding:5px 8px; border-radius:4px; margin-bottom:0.75rem;">
          <p style="font-size:0.85rem; line-height:1.45; margin-bottom:0.75rem;">ISO 9001:2015 & FSC Chain of Custody certified cGMP manufacturer of pharmaceutical secondary packaging.</p>
          <span class="cert-pill" style="display:inline-block; font-size:0.75rem; padding:3px 8px; margin-bottom:0.85rem;">ISO 9001:2015 • FSC CERTIFIED</span>
          <ul class="contact-info-list" style="font-size:0.82rem;">
            <li style="margin-bottom:0.4rem;"><i class="ri-phone-fill" style="color:var(--gold-accent);"></i> +92 21-38893400-3</li>
            <li style="margin-bottom:0.4rem;"><i class="ri-mail-fill" style="color:var(--gold-accent);"></i> info@princeartpackages.com</li>
          </ul>
        </div>
        <div>
          <h4 style="font-size:1rem; margin-bottom:0.85rem;">Quick Links</h4>
          <ul class="footer-links" style="font-size:0.85rem;">
            <li><a href="/">Home</a></li>
            <li><a href="/about.html">About</a></li>
            <li><a href="/products.html">Products</a></li>
            <li><a href="/capabilities.html">Capabilities</a></li>
            <li><a href="/quality.html">Quality</a></li>
            <li><a href="/contact.html">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 style="font-size:1rem; margin-bottom:0.85rem;">Products &amp; Tech</h4>
          <ul class="footer-links" style="font-size:0.85rem;">
            <li><a href="/products/folding-cartons.html">Folding Cartons</a></li>
            <li><a href="/products/leaf-inserts.html">Leaf-Inserts &amp; Outserts</a></li>
            <li><a href="/products/printed-labels.html">Printed Labels</a></li>
            <li><a href="/products/cold-seal-wallet.html">ColdSeal Blister Wallet</a></li>
            <li><a href="/innovation.html">3D-ENGRAVIX&trade; Technology</a></li>
          </ul>
        </div>
        <div>
          <h4 style="font-size:1rem; margin-bottom:0.65rem;">Plant Locations</h4>
          <ul class="contact-info-list" style="font-size:0.8rem; opacity:0.9;">
            <li style="margin-bottom:0.35rem; line-height:1.35;"><i class="ri-map-pin-fill" style="color:var(--teal-brand); font-size:0.95rem;"></i> Unit 1: WH-17-A8, ST-1, Sec 38, Korangi Creek, Karachi</li>
            <li style="margin-bottom:0.35rem; line-height:1.35;"><i class="ri-map-pin-fill" style="color:var(--teal-brand); font-size:0.95rem;"></i> Unit 2: Plot 239, Opp Masco, Korangi Creek Road, Karachi</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <div>&copy; 2026 Prince Art Packages (Private) Limited. All Rights Reserved. ISO 9001:2015 & FSC Certified.</div>
        <div><a href="/privacy.html" style="color:rgba(255,255,255,0.5); font-size:0.8rem;">Privacy Policy</a> &middot; <a href="/terms.html" style="color:rgba(255,255,255,0.5); font-size:0.8rem;">Terms</a></div>
      </div>
    </div>
  </footer>`;

// ---- helpers ---------------------------------------------------------------
function read(file) { return fs.readFileSync(file, 'utf8'); }

function extractSections(html) {
  // Split into: head, and each top-level page-section block (inner HTML only).
  const headMatch = html.match(/<head>([\s\S]*?)<\/head>/i);
  const head = headMatch ? headMatch[1] : '';
  const body = html.replace(/^[\s\S]*<body>/i, '').replace(/<\/body>[\s\S]*$/i, '');

  // Track <main>/<section> depth; capture inner HTML of each top-level
  // element that has id="page-<x>". Uses index slicing so ALL nested
  // content (divs, imgs, text) is preserved.
  const tagRe = /<(\/?)(main|section)\b[^>]*>/gi;
  const sections = [];
  let depth = 0;
  let startIdx = -1;
  let currentId = null;
  let m;
  while ((m = tagRe.exec(body)) !== null) {
    const closing = m[1] === '/';
    const tagEnd = m.index + m[0].length;
    const idMatch = m[0].match(/id=["']page-([a-z-]+)["']/i);
    if (!closing) {
      if (depth === 0 && idMatch) {
        startIdx = tagEnd;          // capture AFTER the opening tag
        currentId = idMatch[1];
        depth = 1;
      } else {
        depth++;
      }
    } else {
      depth--;
      if (depth === 0 && startIdx !== -1) {
        const endIdx = m.index;     // stop BEFORE the closing tag
        sections.push({ id: currentId, html: body.slice(startIdx, endIdx) });
        startIdx = -1;
        currentId = null;
      }
    }
  }
  return { head, sections };
}

function buildHead(title, description, canonicalPath, ogImage) {
  return `<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${title}</title>
  <meta name="description" content="${description}">
  <link rel="canonical" href="${SITE}${canonicalPath}">
  <meta name="robots" content="index, follow">
  <meta property="og:title" content="${title}">
  <meta property="og:description" content="${description}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="${SITE}${canonicalPath}">
  <meta property="og:image" content="${SITE}/${ogImage}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="css/prince-art.css">
</head>`;
}

function pageShell({ title, description, canonicalPath, ogImage, bodyInner, active }) {
  const navActive = NAV.replace(`<a href="${canonicalPath}">`, `<a href="${canonicalPath}" class="active">`);
  return `<!DOCTYPE html>
<html lang="en">
${buildHead(title, description, canonicalPath, ogImage)}
<body>
  ${navActive}
  <main class="page-section active">
${bodyInner}
  </main>
  ${FOOTER}
  <script src="js/site.js"></script>
</body>
</html>`;
}

function ensureDir(p) { fs.mkdirSync(p, { recursive: true }); }
function writePage(relPath, html) {
  const full = path.join(DIST, relPath);
  ensureDir(path.dirname(full));
  fs.writeFileSync(full, html);
  console.log('  ✓', relPath);
}

// ---- product definitions (slug + display) ----------------------------------
const PRODUCTS = [
  { slug: 'folding-cartons', title: 'Folding Cartons', id: 'product-folding-cartons' },
  { slug: 'leaf-inserts', title: 'Leaf-Inserts & Outserts', id: 'product-leaf-inserts' },
  { slug: 'printed-labels', title: 'Printed Labels & Tamper-Evident', id: 'product-printed-labels' },
  { slug: 'honeycomb-separators', title: 'Honeycomb Separators', id: 'product-honeycomb-separators' },
  { slug: 'pill-folders', title: 'Pill-Folders', id: 'product-pill-folders' },
  { slug: 'tamper-evident', title: 'Tamper-Evident Cartons & Labels', id: 'product-temper-evident' },
  { slug: '3d-engravix', title: '3D-ENGRAVIX™ Security', id: 'product-3d-engravix' },
  { slug: 'cold-seal-wallet', title: 'Cold-Seal Blister Wallet', id: 'product-cold-seal-wallet' }
];

// ---- blog definitions (mirror of src/config/seed_blogs.js) -----------------
const BLOGS = [
  { slug: 'iso-9001-cgmp-compliance-pharma-packaging', title: 'ISO 9001:2015 & cGMP Compliance in Pharmaceutical Secondary Packaging', category: 'Quality & Compliance', image: 'images/prod_cartons.jpg', date: '2026-02-15', excerpt: 'How strict Quality Assurance protocols, electronic line clearances, and ISO 9001:2015 standards safeguard medicine cartons against mix-ups and contamination.' },
  { slug: 'anti-counterfeiting-tamper-evident-3d-engravix', title: 'Anti-Counterfeiting Innovations: From Tamper-Evident Seals to 3D-ENGRAVIX', category: 'Anti-Counterfeiting', image: 'images/engravix.jpg', date: '2026-02-10', excerpt: 'Exploring modern packaging security technologies designed to combat counterfeit medicines, including high-relief embossing, micro-text, and cold-seal tamper alerts.' },
  { slug: 'fsc-certified-sustainable-paperboard-pharma-packaging', title: 'FSC Certified Sustainable Paperboard for Modern Pharma Packaging', category: 'Sustainability', image: 'images/facility.jpg', date: '2026-01-28', excerpt: 'Adopting FSC Chain of Custody standards to deliver eco-responsible folding cartons and leaflets without compromising structural durability or barrier protection.' },
  { slug: 'cold-seal-blister-wallets-medication-adherence', title: 'Cold-Seal Blister Wallets: Enhancing Medication Adherence & Child Safety', category: 'Technology', image: 'images/coldseal.jpg', date: '2026-01-14', excerpt: 'How heat-free cold-seal wallet packaging protects heat-sensitive solid oral doses while improving patient dosage compliance and child resistance.' }
];

// ---- build -----------------------------------------------------------------
function build() {
  const html = read(path.join(PUBLIC, 'index.html'));
  const { sections } = extractSections(html);
  const byId = {};
  sections.forEach(s => { byId[s.id] = s.html; });

  if (!fs.existsSync(DIST)) fs.mkdirSync(DIST, { recursive: true });
  // copy static assets (css, js, images)
  ['css', 'js', 'images'].forEach(d => {
    const src = path.join(PUBLIC, d);
    if (fs.existsSync(src)) {
      const dest = path.join(DIST, d);
      ensureDir(dest);
      fs.cpSync(src, dest, { recursive: true });
    }
  });
  console.log('Copied css/ js/ images/ → dist/\n');

  const meta = {
    home: { title: 'Prince Art Packages (Private) Limited | Pharmaceutical Secondary Packaging', desc: 'ISO 9001:2015 & FSC certified cGMP manufacturer of folding cartons, leaf-inserts, printed labels, ColdSeal wallets & 3D-ENGRAVIX anti-counterfeit packaging in Karachi.', img: 'images/facility.jpg' },
    about: { title: 'About Prince Art Packages | cGMP Pharma Packaging Manufacturer', desc: 'Formerly Prince Art Press — ISO 9001:2015 & FSC certified manufacturer operating two plants in Korangi Creek Industrial Park, Karachi.', img: 'images/facility.jpg' },
    products: { title: 'Pharmaceutical Secondary Packaging Products | Prince Art Packages', desc: 'Folding cartons, leaf-inserts, printed labels, honeycomb separators, pill-folders, tamper-evident, 3D-ENGRAVIX and ColdSeal wallets.', img: 'images/prod_cartons.jpg' },
    capabilities: { title: 'Manufacturing Capabilities & Production Capacity | Prince Art Packages', desc: '15 printing machines, 8–10M cartons/month, integrated 8-stage production flow for high-volume pharma packaging.', img: 'images/cartons.jpg' },
    innovation: { title: 'Innovation & Security Technology | ColdSeal & 3D-ENGRAVIX™', desc: 'ColdSeal Blister Wallet (–50% plastic/foil) and 3D-ENGRAVIX™ optical anti-counterfeit — zero-device visual authentication.', img: 'images/coldseal.jpg' },
    quality: { title: 'Quality Certifications & In-House Laboratory | Prince Art Packages', desc: 'ISO 9001:2015 (KQ.2025.5393), FSC Chain of Custody (RR-COC-003348) and cGMP compliant with in-house OurLAB testing.', img: 'images/facility.jpg' },
    industries: { title: 'Industries We Serve | Pharma, Healthcare & Biologics Packaging', desc: 'Specialized secondary packaging for commercial pharmaceuticals, healthcare/OTC, and biologics & medical devices.', img: 'images/prod_labels.jpg' },
    sustainability: { title: 'Sustainable & FSC Certified Packaging | Prince Art Packages', desc: 'FSC Chain of Custody packaging, ColdSeal footprint reduction, low-VOC inks and 98% paperboard recycling.', img: 'images/leaflets.jpg' },
    'case-studies': { title: 'Case Studies & Manufacturing Results | Prince Art Packages', desc: 'Anonymized performance highlights illustrating zero-defect delivery standards for pharmaceutical clients.', img: 'images/cartons.jpg' },
    blogs: { title: 'Pharmaceutical Packaging Insights & Technical Articles', desc: 'Technical publications, regulatory compliance guidelines and anti-counterfeiting innovations from Prince Art Packages.', img: 'images/facility.jpg' },
    contact: { title: 'Request a Quotation & Facility Visit | Prince Art Packages', desc: 'Contact our technical sales team or request a formal plant audit at Unit 1 or Unit 2 in Korangi Creek Industrial Park, Karachi.', img: 'images/facility.jpg' }
  };

  // 1) Top-level pages (use the section HTML, minus the blog modal JS bits)
  Object.keys(meta).forEach(key => {
    const bodyInner = byId[key] || '';
    if (!bodyInner) { console.warn('  ! missing section:', key); return; }
    const m = meta[key];
    const canonical = key === 'home' ? '/' : `/${key}.html`;
    writePage(key === 'home' ? 'index.html' : `${key}.html`,
      pageShell({ title: m.title, description: m.desc, canonicalPath: canonical, ogImage: m.img, bodyInner, active: canonical }));
  });

  // 2) Individual product pages
  PRODUCTS.forEach(p => {
    const cardHtml = byId.products
      ? byId.products.match(new RegExp(`<div class="product-card" id="${p.id}">[\\s\\S]*?<div class="btn-quote-wrapper">[\\s\\S]*?</div>\\s*</div>\\s*</div>`))
      : null;
    const card = cardHtml ? cardHtml[0] : '';
    const inner = `
    <div class="container section">
      <div class="section-header">
        <span class="section-subtitle">Pharmaceutical Secondary Packaging</span>
        <h2>${p.title}</h2>
        <p>Engineered under ISO 9001:2015 and cGMP guidelines for regulated formulation plants.</p>
      </div>
      <div class="grid-3-products">
        ${card}
      </div>
      <div style="margin-top:3rem; text-align:center;">
        <a href="/contact.html" class="btn btn-gold" data-action="quote" data-product="${p.title}">Request a Quote for ${p.title} &rarr;</a>
      </div>
    </div>`;
    writePage(`products/${p.slug}.html`,
      pageShell({ title: `${p.title} | Prince Art Packages`, description: `${p.title} manufactured by Prince Art Packages — ISO 9001:2015 & FSC certified cGMP pharma packaging in Karachi.`, canonicalPath: `/products/${p.slug}.html`, ogImage: 'images/prod_cartons.jpg', bodyInner: inner, active: '/products.html' }));
  });

  // 3) Individual blog article pages
  BLOGS.forEach(b => {
    const inner = `
    <div class="container section">
      <article style="max-width:820px; margin:0 auto;">
        <span class="cert-pill" style="display:inline-block; margin-bottom:0.75rem;">${b.category}</span>
        <h2 style="color:var(--navy-dark); font-size:1.8rem; line-height:1.25; margin-bottom:0.75rem;">${b.title}</h2>
        <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.5rem;">
          <i class="ri-calendar-line"></i> ${new Date(b.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
        </div>
        <div style="width:100%; height:340px; overflow:hidden; border-radius:var(--radius-sm); margin-bottom:1.5rem;">
          <img src="${b.image}" alt="${b.title}" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <p style="font-size:1.05rem; color:var(--text-body);">${b.excerpt}</p>
        <p style="color:var(--text-body);">Full article content is managed in the CMS and rendered here on the live site. The complete technical deep-dive is available once the CMS host is connected.</p>
        <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border-color);">
          <a href="/contact.html" class="btn btn-gold btn-sm">Request a Proposal &rarr;</a>
        </div>
      </article>
    </div>`;
    writePage(`blog/${b.slug}.html`,
      pageShell({ title: `${b.title} | Prince Art Packages`, description: b.excerpt, canonicalPath: `/blog/${b.slug}.html`, ogImage: b.image, bodyInner: inner, active: '/blog.html' }));
  });

  // 4) Stub policy pages (to be filled)
  ['privacy', 'terms'].forEach(p => {
    const t = p === 'privacy' ? 'Privacy Policy' : 'Terms & Conditions';
    const inner = `<div class="container section"><div class="section-header"><h2>${t}</h2></div><p>This page is being finalised. It will cover how Prince Art Packages (Private) Limited collects, uses, and protects visitor information and the terms of website use.</p></div>`;
    writePage(`${p}.html`, pageShell({ title: `${t} | Prince Art Packages`, description: `${t} for princeartpackages.com.`, canonicalPath: `/${p}.html`, ogImage: 'images/facility.jpg', bodyInner: inner, active: '' }));
  });

  console.log('\n✅ Static build complete → dist/');
}

build();
