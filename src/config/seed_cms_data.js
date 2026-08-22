const db = require('./db');

/**
 * Seed initial categories, products, blog posts, and pages for Prince Art Packages.
 */
async function seedCmsData() {
  console.log('[Seed CMS] Seeding core website content into MySQL...');

  try {
    // 1. Seed Categories
    const categories = [
      { name: 'Folding Cartons', slug: 'folding-cartons', description: 'Pharmaceutical folding cartons and secondary packaging.' },
      { name: 'Leaflets & Inserts', slug: 'leaflets-inserts', description: 'Patient information leaflets, outserts, and medical inserts.' },
      { name: 'Printed Labels', slug: 'printed-labels', description: 'High-precision printed pressure sensitive labels and seals.' },
      { name: 'Innovations', slug: 'innovations', description: 'Patented packaging technologies, 3D-ENGRAVIX, and ColdSeal Wallets.' }
    ];

    for (const cat of categories) {
      const existing = await db.query('SELECT id FROM categories WHERE slug = ?', [cat.slug]);
      if (existing.length === 0) {
        await db.query(
          'INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)',
          [cat.name, cat.slug, cat.description]
        );
        console.log(`   ✓ Seeded category: ${cat.name}`);
      }
    }

    // 2. Seed 8 Core Products
    const products = [
      {
        name: 'Folding Cartons',
        slug: 'folding-cartons',
        category_id: 1,
        short_description: 'cGMP high-volume folding cartons with braille embossing and anti-mixup security.',
        full_description: 'High-precision pharmaceutical folding cartons manufactured from 250–400 GSM FBB board. Features 1–6 color offset printing, inline flexo varnish, UV coating, electronic line clearance verification, and 3D Braille embossing complying with EN 15823 standard.',
        featured_image: 'images/prod_cartons.jpg',
        status: 'published'
      },
      {
        name: 'Leaf-inserts & Outserts',
        slug: 'leaf-inserts',
        category_id: 2,
        short_description: 'Precision folded patient information leaflets down to 28 GSM paper weight.',
        full_description: 'Pharmaceutical leaf-inserts and cross-folded outserts printed on ultra-lightweight 28–50 GSM bible paper. Features up to 18 folds, compact wafer-seal closures, and zero-defect optical barcode inspection.',
        featured_image: 'images/prod_leaflets.jpg',
        status: 'published'
      },
      {
        name: 'Printed Labels & Tamper Seals',
        slug: 'printed-labels',
        category_id: 3,
        short_description: 'Self-adhesive pharmaceutical labels, tamper-evident seals, and vial wraps.',
        full_description: 'Pressure-sensitive self-adhesive labels for vials, bottles, and syringes. Manufactured using low-migration inks, tamper-evident VOID substrates, and clear security laminates.',
        featured_image: 'images/prod_labels.jpg',
        status: 'published'
      },
      {
        name: 'Honeycomb Separators',
        slug: 'honeycomb-separators',
        category_id: 1,
        short_description: 'Heavy-duty honeycomb kraft paperboard separators for shipping protection.',
        full_description: 'Eco-friendly, shock-absorbent honeycomb paperboard partitions and outer shipper box inserts designed to protect ampoules, glass vials, and liquid medicine bottles during transit.',
        featured_image: 'images/prod_honeycomb.jpg',
        status: 'published'
      },
      {
        name: 'Pill-folders & Blister Cards',
        slug: 'pill-folders',
        category_id: 1,
        short_description: 'Custom cardstock pill-folders and clinical trial dose calendar packs.',
        full_description: 'Multi-panel paperboard folders for unit-dose blister strips and compliance clinical trial calendar packs. Printed with high-contrast dosing schedules.',
        featured_image: 'images/prod_pill_folders.jpg',
        status: 'published'
      },
      {
        name: 'Tamper-Evident Security Cartons',
        slug: 'tamper-evident-cartons',
        category_id: 4,
        short_description: 'EU FMD compliant tamper-evident cartons with anti-counterfeiting seals.',
        full_description: 'Secondary packaging engineered with fiber-tear void seals and reverse-tuck tamper indication conforming to EU Falsified Medicines Directive (2011/62/EU).',
        featured_image: 'images/prod_tamper_labels.jpg',
        status: 'published'
      },
      {
        name: '3D-ENGRAVIX™ Technology',
        slug: '3d-engravix-technology',
        category_id: 4,
        short_description: 'Patented high-security 3D micro-engraving for brand protection.',
        full_description: 'Proprietary micro-embossing technology creating tactile 3D refractive security patterns directly on paperboard packaging without plastic foil laminates.',
        featured_image: 'images/engravix.jpg',
        status: 'published'
      },
      {
        name: 'Cold-Seal Blister Wallets',
        slug: 'cold-seal-wallet',
        category_id: 4,
        short_description: 'Child-resistant senior-friendly cold-seal blister packaging wallets.',
        full_description: 'Heat-free cold seal cohesive blister wallet cards ensuring zero thermal degradation of heat-sensitive active pharmaceutical ingredients (APIs).',
        featured_image: 'images/coldseal_blister.jpg',
        status: 'published'
      }
    ];

    for (const prod of products) {
      const existing = await db.query('SELECT id FROM products WHERE slug = ?', [prod.slug]);
      if (existing.length === 0) {
        await db.query(
          `INSERT INTO products (name, slug, category_id, short_description, full_description, featured_image, status)
           VALUES (?, ?, ?, ?, ?, ?, ?)`,
          [prod.name, prod.slug, prod.category_id, prod.short_description, prod.full_description, prod.featured_image, prod.status]
        );
        console.log(`   ✓ Seeded product: ${prod.name}`);
      }
    }

    // 3. Seed Core Pages with Structured Data
    const pages = [
      { title: 'Home', slug: 'home', status: 'published' },
      { title: 'About Us', slug: 'about', status: 'published' },
      { title: 'Products', slug: 'products', status: 'published' },
      { title: 'Capabilities', slug: 'capabilities', status: 'published' },
      { title: 'Industries', slug: 'industries', status: 'published' },
      { title: 'Contact', slug: 'contact', status: 'published' }
    ];

    for (const pg of pages) {
      const existing = await db.query('SELECT id FROM pages WHERE slug = ?', [pg.slug]);
      if (existing.length === 0) {
        await db.query(
          'INSERT INTO pages (title, slug, status) VALUES (?, ?, ?)',
          [pg.title, pg.slug, pg.status]
        );
        console.log(`   ✓ Seeded page: ${pg.title}`);
      }
    }

    console.log('[Seed CMS] Core CMS content seeded successfully!');
    return true;
  } catch (err) {
    console.error('[Seed CMS Error]:', err.message);
    throw err;
  }
}

if (require.main === module) {
  seedCmsData().then(() => process.exit(0)).catch(() => process.exit(1));
}

module.exports = seedCmsData;
