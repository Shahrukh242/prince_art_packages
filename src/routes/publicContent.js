const express = require('express');
const router = express.Router();
const db = require('../config/db');

/**
 * PUBLIC READ APIS — No authentication required.
 * Returns ONLY published content with SEO metadata.
 */

// GET /api/public/products
router.get('/products', async (req, res, next) => {
  try {
    const { category, limit = 50 } = req.query;
    let queryStr = `SELECT id, name, slug, category_id, category, short_description, full_description, featured_image, gallery, status, 
                    focus_keyword, meta_title, meta_description, canonical_url, og_title, og_description, og_image, noindex, created_at 
                    FROM products WHERE status = 'published'`;
    const params = [];

    if (category) {
      queryStr += ' AND (category = ? OR category_id = ?)';
      params.push(category, category);
    }

    queryStr += ' ORDER BY id ASC LIMIT ?';
    params.push(parseInt(limit, 10));

    const products = await db.query(queryStr, params);
    res.json({ success: true, count: products.length, data: products });
  } catch (error) {
    next(error);
  }
});

// GET /api/public/products/:slug
router.get('/products/:slug', async (req, res, next) => {
  try {
    const rows = await db.query(
      `SELECT * FROM products WHERE (slug = ? OR id = ?) AND status = 'published'`,
      [req.params.slug, req.params.slug]
    );

    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Product not found or unpublished' });
    }

    res.json({ success: true, data: rows[0] });
  } catch (error) {
    next(error);
  }
});

// GET /api/public/blog-posts
router.get('/blog-posts', async (req, res, next) => {
  try {
    const { category, limit = 50 } = req.query;
    let queryStr = `SELECT id, title, slug, excerpt, content, featured_image, category, author, status, 
                    focus_keyword, meta_title, meta_description, canonical_url, og_title, og_description, og_image, noindex, published_at, created_at 
                    FROM blog_posts WHERE status = 'published'`;
    const params = [];

    if (category && category !== 'all') {
      queryStr += ' AND category = ?';
      params.push(category);
    }

    queryStr += ' ORDER BY published_at DESC, id DESC LIMIT ?';
    params.push(parseInt(limit, 10));

    const posts = await db.query(queryStr, params);
    res.json({ success: true, count: posts.length, data: posts });
  } catch (error) {
    next(error);
  }
});

// GET /api/public/blog-posts/:slug
router.get('/blog-posts/:slug', async (req, res, next) => {
  try {
    const rows = await db.query(
      `SELECT * FROM blog_posts WHERE (slug = ? OR id = ?) AND status = 'published'`,
      [req.params.slug, req.params.slug]
    );

    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Article not found or unpublished' });
    }

    res.json({ success: true, data: rows[0] });
  } catch (error) {
    next(error);
  }
});

// GET /api/public/pages
router.get('/pages', async (req, res, next) => {
  try {
    const pages = await db.query(
      `SELECT id, title, slug, sections_data, status, focus_keyword, meta_title, meta_description, canonical_url, og_title, og_description, og_image, noindex, created_at 
       FROM pages WHERE status = 'published' ORDER BY id ASC`
    );
    res.json({ success: true, count: pages.length, data: pages });
  } catch (error) {
    next(error);
  }
});

// GET /api/public/pages/:slug
router.get('/pages/:slug', async (req, res, next) => {
  try {
    const rows = await db.query(
      `SELECT * FROM pages WHERE (slug = ? OR id = ?) AND status = 'published'`,
      [req.params.slug, req.params.slug]
    );

    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Page not found or unpublished' });
    }

    res.json({ success: true, data: rows[0] });
  } catch (error) {
    next(error);
  }
});

// GET /api/public/categories
router.get('/categories', async (req, res, next) => {
  try {
    const categories = await db.query('SELECT * FROM categories ORDER BY name ASC');
    res.json({ success: true, count: categories.length, data: categories });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
