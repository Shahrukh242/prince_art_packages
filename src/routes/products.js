const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');
const { logActivity } = require('../middleware/activityLogger');

/**
 * GET /api/products (Admin/Management list - returns all statuses or filtered)
 */
router.get('/', async (req, res, next) => {
  try {
    const { status, category, search } = req.query;
    let queryStr = 'SELECT * FROM products WHERE 1=1';
    const params = [];

    if (status && status !== 'all') {
      queryStr += ' AND status = ?';
      params.push(status);
    }

    if (category) {
      queryStr += ' AND (category = ? OR category_id = ?)';
      params.push(category, category);
    }

    if (search) {
      queryStr += ' AND (name LIKE ? OR short_description LIKE ? OR full_description LIKE ?)';
      const pattern = `%${search}%`;
      params.push(pattern, pattern, pattern);
    }

    queryStr += ' ORDER BY id DESC';
    const products = await db.query(queryStr, params);
    res.json({ success: true, count: products.length, data: products });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/products/:id
 */
router.get('/:id', async (req, res, next) => {
  try {
    const products = await db.query('SELECT * FROM products WHERE id = ? OR slug = ?', [req.params.id, req.params.id]);
    if (products.length === 0) {
      return res.status(404).json({ success: false, error: 'Product not found' });
    }
    res.json({ success: true, data: products[0] });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/products (Protected)
 */
router.post('/', requireAuth, async (req, res, next) => {
  try {
    const { name, slug, category, category_id, short_description, full_description, featured_image, status, meta_title, meta_description, focus_keyword, price, stock } = req.body;

    if (!name || !slug) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Product Name and slug are required.'
      });
    }

    const prodStatus = status || 'draft';
    const prodPrice = (price === undefined || price === null || price === '') ? null : parseFloat(price);
    const prodStock = (stock === undefined || stock === null || stock === '') ? null : parseInt(stock, 10);

    const result = await db.query(
      `INSERT INTO products (name, slug, category, category_id, short_description, full_description, featured_image, status, meta_title, meta_description, focus_keyword, price, stock) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [name, slug, category || null, category_id || null, short_description || null, full_description || null, featured_image || null, prodStatus, meta_title || null, meta_description || null, focus_keyword || null, prodPrice, prodStock]
    );

    const [newProduct] = await db.query('SELECT * FROM products WHERE id = ?', [result.insertId]);

    logActivity(req.user ? req.user.id : null, 'Created Product', 'products', newProduct.id, { name, status: prodStatus });

    res.status(201).json({
      success: true,
      message: 'Product created successfully',
      data: newProduct
    });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/products/:id (Protected)
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const productId = req.params.id;
    const { name, slug, category, category_id, short_description, full_description, featured_image, status, meta_title, meta_description, focus_keyword, price, stock } = req.body;

    const existing = await db.query('SELECT id FROM products WHERE id = ?', [productId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Product not found' });
    }

    const fieldsToUpdate = [];
    const params = [];

    if (name !== undefined) { fieldsToUpdate.push('name = ?'); params.push(name); }
    if (slug !== undefined) { fieldsToUpdate.push('slug = ?'); params.push(slug); }
    if (category !== undefined) { fieldsToUpdate.push('category = ?'); params.push(category); }
    if (category_id !== undefined) { fieldsToUpdate.push('category_id = ?'); params.push(category_id); }
    if (short_description !== undefined) { fieldsToUpdate.push('short_description = ?'); params.push(short_description); }
    if (full_description !== undefined) { fieldsToUpdate.push('full_description = ?'); params.push(full_description); }
    if (featured_image !== undefined) { fieldsToUpdate.push('featured_image = ?'); params.push(featured_image); }
    if (status !== undefined) { fieldsToUpdate.push('status = ?'); params.push(status); }
    if (meta_title !== undefined) { fieldsToUpdate.push('meta_title = ?'); params.push(meta_title); }
    if (meta_description !== undefined) { fieldsToUpdate.push('meta_description = ?'); params.push(meta_description); }
    if (focus_keyword !== undefined) { fieldsToUpdate.push('focus_keyword = ?'); params.push(focus_keyword); }
    if (price !== undefined) { fieldsToUpdate.push('price = ?'); params.push(parseFloat(price)); }
    if (stock !== undefined) { fieldsToUpdate.push('stock = ?'); params.push(parseInt(stock, 10)); }

    if (fieldsToUpdate.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    const queryStr = `UPDATE products SET ${fieldsToUpdate.join(', ')} WHERE id = ?`;
    params.push(productId);

    await db.query(queryStr, params);

    const [updatedProduct] = await db.query('SELECT * FROM products WHERE id = ?', [productId]);

    logActivity(req.user ? req.user.id : null, 'Updated Product', 'products', productId, { name, status });

    res.json({
      success: true,
      message: 'Product updated successfully',
      data: updatedProduct
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/products/:id (Protected)
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const productId = req.params.id;
    const existing = await db.query('SELECT id FROM products WHERE id = ?', [productId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Product not found' });
    }

    await db.query('DELETE FROM products WHERE id = ?', [productId]);
    logActivity(req.user ? req.user.id : null, 'Deleted Product', 'products', productId);

    res.json({
      success: true,
      message: 'Product deleted successfully',
      id: parseInt(productId, 10)
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
