const express = require('express');
const router = express.Router();
const db = require('../config/db');

/**
 * TECHNICAL SEO & INFRASTRUCTURE ROUTES
 * GET /sitemap.xml
 * GET /robots.txt
 */

// GET /sitemap.xml
router.get('/sitemap.xml', async (req, res, next) => {
  try {
    const protocol = req.protocol || 'http';
    const host = req.get('host') || 'localhost:3000';
    const baseUrl = `${protocol}://${host}`;

    // Static core routes
    const staticRoutes = [
      { loc: `${baseUrl}/`, priority: '1.0', changefreq: 'daily' },
      { loc: `${baseUrl}/#about`, priority: '0.8', changefreq: 'monthly' },
      { loc: `${baseUrl}/#products`, priority: '0.9', changefreq: 'weekly' },
      { loc: `${baseUrl}/#capabilities`, priority: '0.8', changefreq: 'monthly' },
      { loc: `${baseUrl}/#blogs`, priority: '0.8', changefreq: 'weekly' },
      { loc: `${baseUrl}/#contact`, priority: '0.9', changefreq: 'monthly' }
    ];

    // Fetch published products
    const products = await db.query(
      `SELECT slug, updated_at FROM products WHERE status = 'published' AND (noindex = 0 OR noindex IS NULL)`
    );
    const productRoutes = products.map(p => ({
      loc: `${baseUrl}/#products?slug=${p.slug}`,
      lastmod: p.updated_at ? new Date(p.updated_at).toISOString().split('T')[0] : new Date().toISOString().split('T')[0],
      priority: '0.8',
      changefreq: 'weekly'
    }));

    // Fetch published blog posts
    const posts = await db.query(
      `SELECT slug, updated_at FROM blog_posts WHERE status = 'published' AND (noindex = 0 OR noindex IS NULL)`
    );
    const blogRoutes = posts.map(b => ({
      loc: `${baseUrl}/#blogs?slug=${b.slug}`,
      lastmod: b.updated_at ? new Date(b.updated_at).toISOString().split('T')[0] : new Date().toISOString().split('T')[0],
      priority: '0.7',
      changefreq: 'weekly'
    }));

    const allRoutes = [...staticRoutes, ...productRoutes, ...blogRoutes];

    let xml = `<?xml version="1.0" encoding="UTF-8"?>\n`;
    xml += `<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n`;

    allRoutes.forEach(r => {
      xml += `  <url>\n`;
      xml += `    <loc>${r.loc}</loc>\n`;
      if (r.lastmod) xml += `    <lastmod>${r.lastmod}</lastmod>\n`;
      xml += `    <changefreq>${r.changefreq}</changefreq>\n`;
      xml += `    <priority>${r.priority}</priority>\n`;
      xml += `  </url>\n`;
    });

    xml += `</urlset>`;

    res.header('Content-Type', 'application/xml');
    res.status(200).send(xml);
  } catch (error) {
    next(error);
  }
});

// GET /robots.txt
router.get('/robots.txt', (req, res) => {
  const protocol = req.protocol || 'http';
  const host = req.get('host') || 'localhost:3000';
  const baseUrl = `${protocol}://${host}`;

  const robotsTxt = `# robots.txt for Prince Art Packages (Private) Limited
User-agent: *
Allow: /
Disallow: /admin.html
Disallow: /api/

Sitemap: ${baseUrl}/sitemap.xml
`;

  res.header('Content-Type', 'text/plain');
  res.status(200).send(robotsTxt);
});

module.exports = router;
