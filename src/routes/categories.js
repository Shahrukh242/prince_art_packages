const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');

/**
 * GET /api/categories
 */
router.get('/', async (req, res, next) => {
  try {
    const categories = await db.query('SELECT * FROM categories ORDER BY id DESC');
    res.json({ success: true, count: categories.length, data: categories });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/categories/:id
 */
router.get('/:id', async (req, res, next) => {
  try {
    const categories = await db.query('SELECT * FROM categories WHERE id = ?', [req.params.id]);
    if (categories.length === 0) {
      return res.status(404).json({ success: false, error: 'Category not found' });
    }
    res.json({ success: true, data: categories[0] });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/categories
 */
router.post('/', requireAuth, async (req, res, next) => {
  try {
    const { name, slug, description, parent_id } = req.body;

    if (!name || !slug) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Name and slug are required.'
      });
    }

    const result = await db.query(
      'INSERT INTO categories (name, slug, description, parent_id) VALUES (?, ?, ?, ?)',
      [name, slug, description || null, parent_id || null]
    );

    const [newCategory] = await db.query('SELECT * FROM categories WHERE id = ?', [result.insertId]);

    res.status(201).json({
      success: true,
      message: 'Category created successfully',
      data: newCategory
    });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/categories/:id
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const categoryId = req.params.id;
    const { name, slug, description, parent_id } = req.body;

    const existing = await db.query('SELECT id FROM categories WHERE id = ?', [categoryId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Category not found' });
    }

    const fieldsToUpdate = [];
    const params = [];

    if (name !== undefined) { fieldsToUpdate.push('name = ?'); params.push(name); }
    if (slug !== undefined) { fieldsToUpdate.push('slug = ?'); params.push(slug); }
    if (description !== undefined) { fieldsToUpdate.push('description = ?'); params.push(description); }
    if (parent_id !== undefined) { fieldsToUpdate.push('parent_id = ?'); params.push(parent_id); }

    if (fieldsToUpdate.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    const queryStr = `UPDATE categories SET ${fieldsToUpdate.join(', ')} WHERE id = ?`;
    params.push(categoryId);

    await db.query(queryStr, params);

    const [updatedCategory] = await db.query('SELECT * FROM categories WHERE id = ?', [categoryId]);

    res.json({
      success: true,
      message: 'Category updated successfully',
      data: updatedCategory
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/categories/:id
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const categoryId = req.params.id;
    const existing = await db.query('SELECT id FROM categories WHERE id = ?', [categoryId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Category not found' });
    }

    await db.query('DELETE FROM categories WHERE id = ?', [categoryId]);

    res.json({
      success: true,
      message: 'Category deleted successfully',
      id: parseInt(categoryId, 10)
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
