const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');
const { logActivity } = require('../middleware/activityLogger');

/**
 * GET /api/pages (Admin management list)
 */
router.get('/', async (req, res, next) => {
  try {
    const { status, search } = req.query;
    let queryStr = 'SELECT * FROM pages WHERE 1=1';
    const params = [];

    if (status && status !== 'all') {
      queryStr += ' AND status = ?';
      params.push(status);
    }

    if (search) {
      queryStr += ' AND (title LIKE ? OR slug LIKE ?)';
      const pattern = `%${search}%`;
      params.push(pattern, pattern);
    }

    queryStr += ' ORDER BY id DESC';
    const pages = await db.query(queryStr, params);
    res.json({ success: true, count: pages.length, data: pages });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/pages/:id
 */
router.get('/:id', async (req, res, next) => {
  try {
    const pages = await db.query('SELECT * FROM pages WHERE id = ? OR slug = ?', [req.params.id, req.params.id]);
    if (pages.length === 0) {
      return res.status(404).json({ success: false, error: 'Page not found' });
    }
    res.json({ success: true, data: pages[0] });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/pages (Protected)
 */
router.post('/', requireAuth, async (req, res, next) => {
  try {
    const { title, slug, content, sections_data, meta_title, meta_description, focus_keyword, status } = req.body;

    if (!title || !slug) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Title and slug are required.'
      });
    }

    const pageStatus = status || 'draft';
    const sectionsJson = sections_data ? (typeof sections_data === 'string' ? sections_data : JSON.stringify(sections_data)) : null;

    const result = await db.query(
      `INSERT INTO pages (title, slug, content, sections_data, meta_title, meta_description, focus_keyword, status) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      [title, slug, content || null, sectionsJson, meta_title || null, meta_description || null, focus_keyword || null, pageStatus]
    );

    const [newPage] = await db.query('SELECT * FROM pages WHERE id = ?', [result.insertId]);

    logActivity(req.user ? req.user.id : null, 'Created Page', 'pages', newPage.id, { title, status: pageStatus });

    res.status(201).json({
      success: true,
      message: 'Page created successfully',
      data: newPage
    });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/pages/:id (Protected)
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const pageId = req.params.id;
    const { title, slug, content, sections_data, meta_title, meta_description, focus_keyword, status } = req.body;

    const existing = await db.query('SELECT id FROM pages WHERE id = ?', [pageId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Page not found' });
    }

    const fieldsToUpdate = [];
    const params = [];

    if (title !== undefined) { fieldsToUpdate.push('title = ?'); params.push(title); }
    if (slug !== undefined) { fieldsToUpdate.push('slug = ?'); params.push(slug); }
    if (content !== undefined) { fieldsToUpdate.push('content = ?'); params.push(content); }
    if (sections_data !== undefined) {
      const jsonVal = typeof sections_data === 'string' ? sections_data : JSON.stringify(sections_data);
      fieldsToUpdate.push('sections_data = ?'); params.push(jsonVal);
    }
    if (meta_title !== undefined) { fieldsToUpdate.push('meta_title = ?'); params.push(meta_title); }
    if (meta_description !== undefined) { fieldsToUpdate.push('meta_description = ?'); params.push(meta_description); }
    if (focus_keyword !== undefined) { fieldsToUpdate.push('focus_keyword = ?'); params.push(focus_keyword); }
    if (status !== undefined) { fieldsToUpdate.push('status = ?'); params.push(status); }

    if (fieldsToUpdate.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    const queryStr = `UPDATE pages SET ${fieldsToUpdate.join(', ')} WHERE id = ?`;
    params.push(pageId);

    await db.query(queryStr, params);

    const [updatedPage] = await db.query('SELECT * FROM pages WHERE id = ?', [pageId]);

    logActivity(req.user ? req.user.id : null, 'Updated Page', 'pages', pageId, { title, status });

    res.json({
      success: true,
      message: 'Page updated successfully',
      data: updatedPage
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/pages/:id (Protected)
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const pageId = req.params.id;
    const existing = await db.query('SELECT id FROM pages WHERE id = ?', [pageId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Page not found' });
    }

    await db.query('DELETE FROM pages WHERE id = ?', [pageId]);
    logActivity(req.user ? req.user.id : null, 'Deleted Page', 'pages', pageId);

    res.json({
      success: true,
      message: 'Page deleted successfully',
      id: parseInt(pageId, 10)
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
